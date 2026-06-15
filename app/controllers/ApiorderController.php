<?php
/**
 * ApiOrderController - RESTful API cho đơn hàng (Bài 5).
 */
require_once BASE_PATH . '/app/models/OrderModel.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApiorderController {
    private $orderModel;
    private $payload;

    public function __construct() {
        $this->orderModel = new OrderModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($id = null) {
        $this->payload = $this->checkAuth();
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->getById($id);
                } else {
                    $this->getAll();
                }
                break;
            case 'POST':
                $this->createOrder((int)$this->payload['id']);
                break;
            case 'PUT':
                $this->updateStatus($id);
                break;
            case 'DELETE':
                $this->deleteOrder($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
        exit;
    }

    private function getAll() {
        if (($this->payload['role'] ?? '') === 'admin') {
            $orders = $this->orderModel->getRecentOrders('', 100);
        } else {
            $userId = (int)$this->payload['id'];
            $fullname = $this->payload['fullname'] ?? '';
            $orders = $this->orderModel->getOrdersByUser($userId, $fullname);
        }
        echo json_encode($orders);
    }

    private function getById($id) {
        $order = $this->orderModel->getOrderById($id);
        if ($order) {
            if (($this->payload['role'] ?? '') !== 'admin' && !$this->orderModel->isOrderOwnedByUser($id, (int)($this->payload['id'] ?? 0), $this->payload['fullname'] ?? '')) {
                http_response_code(403);
                echo json_encode(['error' => 'Forbidden - Bạn không có quyền xem đơn hàng này']);
                return;
            }
            $details = $this->orderModel->getOrderDetails($id);
            $order['details'] = $details;
            echo json_encode($order);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Order not found']);
        }
    }

    private function updateStatus($id) {
        // Chỉ admin mới được cập nhật trạng thái
        if (!isset($this->payload['role']) || $this->payload['role'] !== 'admin') {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Chỉ admin mới có quyền cập nhật trạng thái']);
            return;
        }
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Order ID required']);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        $status = $data['status'] ?? '';

        $result = $this->orderModel->updateOrderStatus($id, $status);
        if ($result) {
            echo json_encode(['message' => 'Order status updated successfully']);
        } else {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid status or update failed']);
        }
    }

    private function createOrder($userId) {
        require_once BASE_PATH . '/app/models/CartModel.php';
        $cartModel = new CartModel();
        $cart = $cartModel->getCartForUser($userId);

        if (empty($cart)) {
            http_response_code(422);
            echo json_encode(['error' => 'Giỏ hàng trống, không thể đặt hàng']);
            return;
        }

        $data    = json_decode(file_get_contents('php://input'), true);
        $name    = trim($data['name'] ?? $this->payload['fullname'] ?? '');
        $phone   = trim($data['phone'] ?? '');
        $address = trim($data['address'] ?? '');
        $email   = trim($data['email'] ?? $this->payload['email'] ?? '');
        $note    = trim($data['note'] ?? '');
        $payment = $data['payment_method'] ?? 'COD';

        $errors = [];
        if (!$name)    $errors['name']    = 'Tên không được để trống';
        if (!$phone)   $errors['phone']   = 'Số điện thoại không được để trống';
        if (!$address) $errors['address'] = 'Địa chỉ không được để trống';
        if (!empty($errors)) { http_response_code(422); echo json_encode(['errors' => $errors]); return; }

        $orderId = $this->orderModel->createOrder($name, $phone, $address, $cart, [
            'email'          => $email,
            'note'           => $note,
            'payment_method' => $payment,
            'account_id'     => $userId,
        ]);

        if ($orderId) {
            $cartModel->clearCart($userId);
            http_response_code(201);
            echo json_encode(['message' => 'Đặt hàng thành công', 'order_id' => $orderId]);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Đặt hàng thất bại']);
        }
    }

    private function deleteOrder($id) {
        if (!$id) { http_response_code(400); echo json_encode(['error' => 'Order ID required']); return; }
        $order = $this->orderModel->getOrderById($id);
        if (!$order) { http_response_code(404); echo json_encode(['error' => 'Không tìm thấy đơn hàng']); return; }

        $isAdmin = ($this->payload['role'] ?? '') === 'admin';
        $isOwner = $this->orderModel->isOrderOwnedByUser($id, (int)$this->payload['id'], $this->payload['fullname'] ?? '');

        if (!$isAdmin && !$isOwner) { http_response_code(403); echo json_encode(['error' => 'Forbidden']); return; }

        // User thường chỉ hủy được đơn đang chờ xử lý (status: new hoặc pending)
        if (!$isAdmin && !in_array($order['status'] ?? '', ['new', 'pending'])) {
            http_response_code(422);
            echo json_encode(['error' => 'Chỉ có thể hủy đơn hàng đang ở trạng thái chờ xử lý']);
            return;
        }

        if ($this->orderModel->updateOrderStatus($id, 'cancelled')) {
            echo json_encode(['message' => 'Đã hủy đơn hàng thành công', 'status' => 'cancelled'], JSON_UNESCAPED_UNICODE);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Hủy đơn thất bại'], JSON_UNESCAPED_UNICODE);
        }
    }

    private function checkAuth() {
        return JwtMiddleware::requireAuth();
    }
}
