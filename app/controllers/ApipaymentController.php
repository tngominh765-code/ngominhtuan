<?php
/**
 * ApipaymentController - RESTful API cho thanh toán (LAB 5 & 6).
 * Endpoint: POST /api/payment/{order_id}
 */
require_once BASE_PATH . '/app/models/OrderModel.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApipaymentController {
    private $orderModel;
    private $payload;

    public function __construct() {
        $this->orderModel = new OrderModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($orderId = null) {
        $this->payload = $this->checkAuth();
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->createPayment($orderId);
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
        }
        exit;
    }

    private function createPayment($orderId) {
        if (!$orderId) {
            http_response_code(400);
            echo json_encode(['error' => 'order_id required']);
            return;
        }

        $order = $this->orderModel->getOrderById($orderId);
        if (!$order) {
            http_response_code(404);
            echo json_encode(['error' => 'Đơn hàng không tồn tại']);
            return;
        }

        // Kiểm tra quyền: chỉ chủ đơn hoặc admin
        $isAdmin = ($this->payload['role'] ?? '') === 'admin';
        $isOwner = $this->orderModel->isOrderOwnedByUser($orderId, (int)$this->payload['id'], $this->payload['fullname'] ?? '');
        if (!$isAdmin && !$isOwner) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }

        // Kiểm tra đã thanh toán chưa
        $paymentStatus = $order['payment_status'] ?? $order['status'] ?? '';
        if (in_array($paymentStatus, ['paid', 'completed', 'pending_cod'])) {
            http_response_code(422);
            echo json_encode(['error' => 'Đơn hàng này đã được thanh toán']);
            return;
        }

        $data   = json_decode(file_get_contents('php://input'), true);
        $method_pay = $data['payment_method'] ?? 'COD';
        $allowed = ['COD', 'bank_transfer', 'e_wallet'];
        if (!in_array($method_pay, $allowed)) {
            http_response_code(422);
            echo json_encode(['error' => 'Phương thức thanh toán không hợp lệ. Chọn: COD, bank_transfer, e_wallet']);
            return;
        }

        // Cập nhật trạng thái thanh toán
        try {
            $db = Database::getInstance()->getConnection();
            // Thêm cột payment_status nếu chưa có
            try { $db->exec("ALTER TABLE orders ADD COLUMN payment_status VARCHAR(20) DEFAULT 'unpaid'"); } catch(Exception $e) {}
            try { $db->exec("ALTER TABLE orders ADD COLUMN payment_method VARCHAR(30) DEFAULT 'COD'"); } catch(Exception $e) {}

            $newStatus = ($method_pay === 'COD') ? 'pending_cod' : 'paid'; // COD đã đăng ký thanh toán khi nhận hàng, không cho tạo lại
            $stmt = $db->prepare("UPDATE orders SET payment_status = :ps, payment_method = :pm WHERE id = :id");
            $stmt->execute([':ps' => $newStatus, ':pm' => $method_pay, ':id' => $orderId]);

            echo json_encode([
                'message'        => $method_pay === 'COD' ? 'Đặt thanh toán COD thành công' : 'Thanh toán thành công (mô phỏng)',
                'order_id'       => $orderId,
                'payment_method' => $method_pay,
                'payment_status' => $newStatus,
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Cập nhật thanh toán thất bại']);
        }
    }

    private function checkAuth() {
        return JwtMiddleware::requireAuth();
    }
}
