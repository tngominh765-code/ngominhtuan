<?php
/**
 * CartController - quản lý giỏ hàng, voucher và cập nhật số lượng.
 */

require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CartModel.php';
require_once BASE_PATH . '/app/helpers/SessionHelper.php';

class CartController {
    private $productModel;
    private $cartModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->cartModel = new CartModel();
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        $this->loadPersistentCart();
    }

    public function index() {
        $cart = $_SESSION['cart'];
        $totals = getCartTotals($cart);
        include BASE_PATH . '/app/views/cart/index.php';
    }

    public function add($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            redirect('index.php?url=product');
            exit;
        }

        // Kiểm tra tồn kho trước khi thêm vào giỏ
        $stock = (int)($product['stock'] ?? 999);
        if ($stock <= 0) {
            $_SESSION['error'] = 'Sản phẩm "' . $product['name'] . '" đã hết hàng!';
            $this->back('index.php?url=product');
            exit;
        }

        $quantity = max(1, intval($_POST['quantity'] ?? $_GET['quantity'] ?? 1));

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category_id' => $product['category_id'] ?? null,
                'quantity' => $quantity
            ];
        }

        // Không cho số lượng trong giỏ vượt quá tồn kho/thang an toàn.
        $maxAllowed = $stock > 0 ? min(99, $stock) : 99;
        $_SESSION['cart'][$id]['quantity'] = min($maxAllowed, (int)$_SESSION['cart'][$id]['quantity']);

        $this->persistCart();
        $_SESSION['success'] = 'Đã thêm "' . $product['name'] . '" vào giỏ hàng!';
        $this->back('index.php?url=cart');
    }


    public function buyNow($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại!';
            redirect('index.php?url=product');
        }

        // Kiểm tra tồn kho trước khi thêm vào giỏ
        $stock = (int)($product['stock'] ?? 999);
        if ($stock <= 0) {
            $_SESSION['error'] = 'Sản phẩm "' . $product['name'] . '" đã hết hàng!';
            $this->back('index.php?url=product');
            exit;
        }

        $quantity = max(1, intval($_POST['quantity'] ?? $_GET['quantity'] ?? 1));
        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['quantity'] += $quantity;
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'image' => $product['image'],
                'category_id' => $product['category_id'] ?? null,
                'quantity' => $quantity
            ];
        }

        // Không cho số lượng trong giỏ vượt quá tồn kho/thang an toàn.
        $maxAllowed = $stock > 0 ? min(99, $stock) : 99;
        $_SESSION['cart'][$id]['quantity'] = min($maxAllowed, (int)$_SESSION['cart'][$id]['quantity']);

        $this->persistCart();
        $_SESSION['success'] = 'Đã thêm sản phẩm và chuyển tới thanh toán.';
        redirect('index.php?url=order/checkout');
    }

    public function remove($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=cart'); }
        if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=cart'); }
        if (isset($_SESSION['cart'][$id])) {
            $name = $_SESSION['cart'][$id]['name'];
            unset($_SESSION['cart'][$id]);
            $this->persistCart();
            $_SESSION['success'] = 'Đã xóa "' . $name . '" khỏi giỏ hàng!';
        }
        redirect('index.php?url=cart');
        exit;
    }

    public function update() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=cart');
            exit;
        }
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=cart'); exit; }

        $id = intval($_POST['id'] ?? 0);
        $action = $_POST['action'] ?? '';
        $quantity = intval($_POST['quantity'] ?? 1);

        if (isset($_SESSION['cart'][$id])) {
            if ($action === 'increase') {
                $_SESSION['cart'][$id]['quantity'] += 1;
            } elseif ($action === 'decrease') {
                $_SESSION['cart'][$id]['quantity'] -= 1;
            } elseif ($action === 'set') {
                $_SESSION['cart'][$id]['quantity'] = $quantity;
            }

            if ($_SESSION['cart'][$id]['quantity'] <= 0) {
                unset($_SESSION['cart'][$id]);
            } else {
                $_SESSION['cart'][$id]['quantity'] = min(99, $_SESSION['cart'][$id]['quantity']);
            }
        }

        $this->persistCart();
        redirect('index.php?url=cart');
        exit;
    }

    public function applyVoucher() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=cart');
            exit;
        }

        $code = strtoupper(trim($_POST['voucher_code'] ?? ''));
        $validCodes = ['FUTURE10', 'VIP15', 'TECH500'];

        if ($code === '') {
            $_SESSION['error'] = 'Vui lòng nhập mã ưu đãi.';
        } elseif (!in_array($code, $validCodes, true)) {
            $_SESSION['error'] = 'Mã ưu đãi không hợp lệ. Thử FUTURE10, VIP15 hoặc TECH500.';
        } else {
            $_SESSION['voucher'] = ['code' => $code, 'applied_at' => date('Y-m-d H:i:s')];
            $_SESSION['success'] = 'Đã áp dụng mã ' . $code . '.';
        }

        redirect('index.php?url=cart');
        exit;
    }

    public function removeVoucher() {
        unset($_SESSION['voucher']);
        $_SESSION['success'] = 'Đã gỡ mã ưu đãi.';
        redirect('index.php?url=cart');
        exit;
    }

    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=cart'); }
        if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=cart'); }
        $_SESSION['cart'] = [];
        unset($_SESSION['voucher']);
        $this->persistCart();
        $_SESSION['success'] = 'Đã xóa toàn bộ giỏ hàng!';
        redirect('index.php?url=cart');
        exit;
    }

    private function currentUserId() {
        return isLoggedIn() ? (int)($_SESSION['user_id'] ?? 0) : 0;
    }

    private function loadPersistentCart() {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return;
        }

        $loadedFor = (int)($_SESSION['cart_loaded_for_user'] ?? 0);
        if ($loadedFor === $userId && isset($_SESSION['cart'])) {
            return;
        }

        $sessionCart = $_SESSION['cart'] ?? [];
        if (!empty($sessionCart)) {
            $_SESSION['cart'] = $this->cartModel->mergeSessionCart($userId, $sessionCart);
        } else {
            $_SESSION['cart'] = $this->cartModel->getCartForUser($userId);
        }
        $_SESSION['cart_loaded_for_user'] = $userId;
    }

    private function persistCart() {
        $userId = $this->currentUserId();
        if ($userId <= 0) {
            return;
        }

        $this->cartModel->replaceCart($userId, $_SESSION['cart'] ?? []);
        $_SESSION['cart'] = $this->cartModel->getCartForUser($userId);
        $_SESSION['cart_loaded_for_user'] = $userId;
    }

    private function back($fallback) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? $fallback;
        redirect($redirect);
        exit;
    }
}
