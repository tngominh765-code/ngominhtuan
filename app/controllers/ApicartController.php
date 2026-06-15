<?php
/**
 * ApicartController - RESTful API cho giỏ hàng (LAB 5 & 6).
 * Giỏ hàng lưu trong bảng user_cart_items qua CartModel.
 * Yêu cầu JWT để dùng tất cả các endpoint.
 */
require_once BASE_PATH . '/app/models/CartModel.php';
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApicartController {
    private $cartModel;
    private $productModel;
    private $payload;

    public function __construct() {
        $this->cartModel    = new CartModel();
        $this->productModel = new ProductModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($action = null) {
        $this->payload = $this->checkAuth();
        $userId = (int)$this->payload['id'];
        $method = $_SERVER['REQUEST_METHOD'];

        // Route: GET /api/cart/total
        if ($action === 'total' && $method === 'GET') {
            $this->getTotal($userId);
            exit;
        }
        // Route: DELETE /api/cart/clear
        if ($action === 'clear' && $method === 'DELETE') {
            $this->clearAll($userId);
            exit;
        }

        switch ($method) {
            case 'GET':    $this->getCart($userId);             break;
            case 'POST':   $this->addItem($userId);             break;
            case 'PUT':    $this->updateItem($userId, $action); break;
            case 'DELETE': $this->removeItem($userId, $action); break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
        exit;
    }

    private function getCart($userId) {
        $cart = $this->cartModel->getCartForUser($userId);
        echo json_encode(array_values($cart));
    }

    private function getTotal($userId) {
        $cart = $this->cartModel->getCartForUser($userId);
        $total = 0;
        foreach ($cart as $item) {
            $total += (float)$item['price'] * (int)$item['quantity'];
        }
        echo json_encode(['total' => $total, 'item_count' => count($cart)]);
    }

    private function addItem($userId) {
        $data = json_decode(file_get_contents('php://input'), true);
        $productId = (int)($data['product_id'] ?? 0);
        $quantity  = (int)($data['quantity'] ?? 1);

        if ($productId <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'product_id không hợp lệ']);
            return;
        }
        if ($quantity <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Số lượng phải lớn hơn 0']);
            return;
        }

        $product = $this->productModel->getProductById($productId);
        if (!$product) {
            http_response_code(404);
            echo json_encode(['error' => 'Sản phẩm không tồn tại']);
            return;
        }

        $cart = $this->cartModel->getCartForUser($userId);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            $cart[$productId] = [
                'id'       => $productId,
                'name'     => $product['name'],
                'price'    => $product['price'],
                'image'    => $product['image'],
                'category_id' => $product['category_id'],
                'quantity' => $quantity,
            ];
        }
        $cart[$productId]['quantity'] = min(99, $cart[$productId]['quantity']);
        $this->cartModel->replaceCart($userId, $cart);

        http_response_code(201);
        echo json_encode(['message' => 'Đã thêm vào giỏ hàng', 'cart' => array_values($this->cartModel->getCartForUser($userId))]);
    }

    private function updateItem($userId, $productId) {
        if (!$productId) {
            http_response_code(400);
            echo json_encode(['error' => 'product_id required']);
            return;
        }
        $data     = json_decode(file_get_contents('php://input'), true);
        $quantity = (int)($data['quantity'] ?? 0);
        if ($quantity <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'Số lượng phải lớn hơn 0']);
            return;
        }

        $cart = $this->cartModel->getCartForUser($userId);
        if (!isset($cart[(int)$productId])) {
            http_response_code(404);
            echo json_encode(['error' => 'Sản phẩm không có trong giỏ']);
            return;
        }
        $cart[(int)$productId]['quantity'] = min(99, $quantity);
        $this->cartModel->replaceCart($userId, $cart);
        echo json_encode(['message' => 'Đã cập nhật số lượng']);
    }

    private function removeItem($userId, $productId) {
        if (!$productId) {
            http_response_code(400);
            echo json_encode(['error' => 'product_id required']);
            return;
        }
        $cart = $this->cartModel->getCartForUser($userId);
        unset($cart[(int)$productId]);
        $this->cartModel->replaceCart($userId, $cart);
        echo json_encode(['message' => 'Đã xóa sản phẩm khỏi giỏ']);
    }

    private function clearAll($userId) {
        $this->cartModel->clearCart($userId);
        echo json_encode(['message' => 'Đã xóa toàn bộ giỏ hàng']);
    }

    private function checkAuth() {
        $token = JwtHelper::getBearerToken();
        if (!$token) { http_response_code(401); echo json_encode(['error' => 'Unauthorized']); exit; }
        $payload = JwtHelper::decode($token);
        if (!$payload) { http_response_code(401); echo json_encode(['error' => 'Token không hợp lệ hoặc đã hết hạn']); exit; }
        return $payload;
    }
}
