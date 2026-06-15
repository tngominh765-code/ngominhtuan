<?php
/**
 * OrderController - Checkout + order management.
 */
require_once BASE_PATH . '/app/models/OrderModel.php';
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CartModel.php';
require_once BASE_PATH . '/app/helpers/SessionHelper.php';

class OrderController {
    private $orderModel;

    public function __construct() {
        $this->orderModel = new OrderModel();
    }

    public function index() {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem đơn hàng.';
            redirect('index.php?url=account/login');
        }
        $keyword = trim($_GET['q'] ?? '');
        $perPage = 15;
        $page = max(1, intval($_GET['page'] ?? 1));

        // Admin xem tất cả đơn; user thường chỉ xem đơn và thống kê của chính mình
        if (isAdmin()) {
            $total = $this->orderModel->countOrders($keyword);
            $totalPages = max(1, (int)ceil($total / $perPage));
            $page = min($page, $totalPages);
            $orders = $this->orderModel->getOrders($keyword, $page, $perPage);
            $orderStats = $this->orderModel->getOrderStats();
        } else {
            $userId = (int)($_SESSION['user_id'] ?? 0);
            $fullname = $_SESSION['fullname'] ?? '';
            $total = $this->orderModel->countOrdersByUser($userId, $fullname, $keyword);
            $totalPages = max(1, (int)ceil($total / $perPage));
            $page = min($page, $totalPages);
            $orders = $this->orderModel->getOrdersByUser($userId, $fullname, $keyword, $page, $perPage);
            $orderStats = $this->orderModel->getOrderStatsByUser($userId, $fullname);
        }
        include BASE_PATH . '/app/views/order/index.php';
    }

    public function show($id) {
        if (!isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để xem đơn hàng.';
            redirect('index.php?url=account/login');
        }
        $order = $this->orderModel->getOrderById($id);
        if (!$order) {
            $_SESSION['error'] = 'Đơn hàng không tồn tại.';
            redirect('index.php?url=order');
            exit;
        }

        if (!isAdmin() && !$this->orderModel->isOrderOwnedByUser($id, (int)($_SESSION['user_id'] ?? 0), $_SESSION['fullname'] ?? '')) {
            $_SESSION['error'] = 'Bạn không có quyền xem đơn hàng này.';
            redirect('index.php?url=order');
        }

        $orderDetails = $this->orderModel->getOrderDetails($id);
        include BASE_PATH . '/app/views/order/show.php';
    }

    public function checkout() {
        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Giỏ hàng trống. Vui lòng thêm sản phẩm trước khi đặt hàng.';
            redirect('index.php?url=product');
            exit;
        }

        $cart = $_SESSION['cart'];
        $totals = getCartTotals($cart);
        $errors = [];
        $old = [];
        include BASE_PATH . '/app/views/order/checkout.php';
    }

    public function place() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=order/checkout');
            exit;
        }

        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=order/checkout'); }

        if (empty($_SESSION['cart'])) {
            $_SESSION['error'] = 'Giỏ hàng trống!';
            redirect('index.php?url=product');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $phone = preg_replace('/\s+/', '', trim($_POST['phone'] ?? ''));
        $email = trim($_POST['email'] ?? '');
        $address = trim($_POST['address'] ?? '');
        $note = trim($_POST['note'] ?? '');
        $paymentMethod = trim($_POST['payment_method'] ?? 'COD');
        $errors = [];
        $old = $_POST;
        $cart = $_SESSION['cart'];
        $totals = getCartTotals($cart);

        if ($name === '') {
            $errors[] = 'Vui lòng nhập họ tên.';
        }

        if ($phone === '') {
            $errors[] = 'Vui lòng nhập số điện thoại.';
        } elseif (!preg_match('/^[0-9]{9,11}$/', $phone)) {
            $errors[] = 'Số điện thoại không hợp lệ (9-11 chữ số).';
        }

        if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email không hợp lệ.';
        }

        if ($address === '') {
            $errors[] = 'Vui lòng nhập địa chỉ giao hàng.';
        }

        $allowedPayments = ['COD', 'BANK', 'MOMO'];
        if (!in_array($paymentMethod, $allowedPayments, true)) {
            $paymentMethod = 'COD';
        }

        if (!empty($errors)) {
            include BASE_PATH . '/app/views/order/checkout.php';
            return;
        }

        $orderId = $this->orderModel->createOrder($name, $phone, $address, $cart, [
            'email' => $email,
            'note' => $note,
            'payment_method' => $paymentMethod,
            'totals' => $totals,
            'account_id' => (int)($_SESSION['user_id'] ?? 0),
        ]);

        if ($orderId) {
            // Trừ tồn kho sau khi đặt hàng thành công
            $productModel = new ProductModel();
            foreach ($cart as $item) {
                $productModel->reduceStock($item['id'], $item['quantity']);
            }

            if (isLoggedIn() && !empty($_SESSION['user_id'])) {
                (new CartModel())->clearCart((int)$_SESSION['user_id']);
            }
            unset($_SESSION['cart'], $_SESSION['voucher'], $_SESSION['cart_loaded_for_user']);
            $order = $this->orderModel->getOrderById($orderId);
            $orderDetails = $this->orderModel->getOrderDetails($orderId);
            include BASE_PATH . '/app/views/order/confirmation.php';
            return;
        }

        $_SESSION['error'] = 'Có lỗi xảy ra khi đặt hàng. Vui lòng thử lại!';
        redirect('index.php?url=order/checkout');
        exit;
    }

    public function status($id) {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=order/show/' . $id);
            exit;
        }
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=order/show/' . $id); }
        $status = $_POST['status'] ?? 'new';
        if ($this->orderModel->updateOrderStatus($id, $status)) {
            $_SESSION['success'] = 'Đã cập nhật trạng thái đơn hàng.';
        } else {
            $_SESSION['error'] = 'Không thể cập nhật trạng thái đơn hàng.';
        }
        redirect('index.php?url=order/show/' . $id);
        exit;
    }

    public function exportCsv() {
        requireAdmin();
        $keyword = trim($_GET['q'] ?? '');
        $orders = $this->orderModel->searchOrders($keyword, 1, 9999)['orders'];
        header('Content-Type: text/csv; charset=UTF-8');
        header('Content-Disposition: attachment; filename="orders_' . date('Ymd_His') . '.csv"');
        header('Pragma: no-cache');
        echo "\xEF\xBB\xBF";
        $out = fopen('php://output', 'w');
        fputcsv($out, ['Mã đơn','Khách hàng','SĐT','Email','Địa chỉ','Trạng thái','Thanh toán','Tổng tiền','Ngày tạo']);
        foreach ($orders as $o) {
            fputcsv($out, ['#' . $o['id'], $o['name'], $o['phone'], $o['email'] ?? '', $o['address'], $o['order_status'] ?? ($o['status'] ?? 'new'), $o['payment_label'] ?? ($o['payment_method'] ?? 'COD'), number_format((float)($o['total_amount'] ?? 0), 0, ',', '.') . 'đ', $o['created_at'] ?? '']);
        }
        fclose($out); exit;
    }
}
