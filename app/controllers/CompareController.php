<?php
require_once BASE_PATH . '/app/models/ProductModel.php';

class CompareController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        if (!isset($_SESSION['compare'])) {
            $_SESSION['compare'] = [];
        }
    }

    public function index() {
        $products = [];
        foreach ($_SESSION['compare'] as $id => $createdAt) {
            $product = $this->productModel->getProductById($id);
            if ($product) {
                $product['compare_at'] = $createdAt;
                $products[] = $product;
            } else {
                unset($_SESSION['compare'][$id]);
            }
        }
        include BASE_PATH . '/app/views/compare/index.php';
    }

    public function add($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'Sản phẩm không tồn tại.';
            redirect('index.php?url=product');
            exit;
        }
        if (!isset($_SESSION['compare'][(int) $id]) && count($_SESSION['compare']) >= 4) {
            $_SESSION['error'] = 'Bảng so sánh tối đa 4 sản phẩm. Hãy xóa bớt một sản phẩm trước.';
            $this->back('index.php?url=compare');
        }
        $_SESSION['compare'][(int) $id] = date('Y-m-d H:i:s');
        $_SESSION['success'] = 'Đã thêm "' . $product['name'] . '" vào bảng so sánh.';
        $this->back('index.php?url=compare');
    }

    public function remove($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=compare'); }
        if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=compare'); }
        unset($_SESSION['compare'][(int) $id]);
        $_SESSION['success'] = 'Đã xóa sản phẩm khỏi bảng so sánh.';
        $this->back('index.php?url=compare');
    }

    public function clear() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=compare'); }
        if (!verify_csrf()) { $_SESSION['error']='Token không hợp lệ.'; redirect('index.php?url=compare'); }
        $_SESSION['compare'] = [];
        $_SESSION['success'] = 'Đã làm sạch bảng so sánh.';
        redirect('index.php?url=compare');
        exit;
    }

    private function back($fallback) {
        $redirect = $_SERVER['HTTP_REFERER'] ?? $fallback;
        redirect($redirect);
        exit;
    }
}
