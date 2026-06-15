<?php
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/WishlistModel.php';

class WishlistController {
    private $productModel;
    private $wishlistModel;
    public function __construct() { $this->productModel = new ProductModel(); $this->wishlistModel = new WishlistModel(); }
    public function index() { $products = $this->wishlistModel->getBySession(session_id()); include BASE_PATH . '/app/views/wishlist/index.php'; }
    public function add($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) { $_SESSION['error'] = 'Sản phẩm không tồn tại.'; redirect('index.php?url=product'); }
        $this->wishlistModel->add(session_id(), (int)$id);
        $_SESSION['success'] = 'Đã lưu "' . $product['name'] . '" vào wishlist.';
        $this->back('index.php?url=wishlist');
    }
    public function remove($id) { if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=wishlist'); } if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=wishlist'); } $this->wishlistModel->remove(session_id(), (int)$id); $_SESSION['success'] = 'Đã xóa sản phẩm khỏi wishlist.'; $this->back('index.php?url=wishlist'); }
    public function clear() { if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=wishlist'); } if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=wishlist'); } $this->wishlistModel->clear(session_id()); $_SESSION['success'] = 'Đã làm sạch wishlist.'; redirect('index.php?url=wishlist'); }
    private function back($fallback) { redirect($_SERVER['HTTP_REFERER'] ?? $fallback); }
}
