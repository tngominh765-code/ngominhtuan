<?php
require_once BASE_PATH . '/app/models/ReviewModel.php';
require_once BASE_PATH . '/app/models/ProductModel.php';

class ReviewController {
    public function store($productId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { redirect('index.php?url=product/show/' . (int)$productId); }
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=product/show/' . (int)$productId . '#reviews'); }
        $name = trim($_POST['reviewer_name'] ?? '');
        $rating = (int)($_POST['rating'] ?? 0);
        $comment = trim($_POST['comment'] ?? '');
        $errors = [];
        if ($name === '') $errors[] = 'Vui lòng nhập tên người đánh giá.';
        if ($rating < 1 || $rating > 5) $errors[] = 'Vui lòng chọn số sao từ 1 đến 5.';
        if ($comment === '') $errors[] = 'Vui lòng nhập nội dung đánh giá.';
        if (textLengthUtf8($comment) > 500) $errors[] = 'Nội dung đánh giá tối đa 500 ký tự.';
        if ($errors) { $_SESSION['error'] = implode(' ', $errors); redirect('index.php?url=product/show/' . (int)$productId . '#reviews'); }
        $model = new ReviewModel();
        $model->create(['product_id'=>(int)$productId,'reviewer_name'=>$name,'rating'=>$rating,'comment'=>$comment]);
        $_SESSION['success'] = 'Cảm ơn bạn đã gửi đánh giá.';
        redirect('index.php?url=product/show/' . (int)$productId . '#reviews');
    }
}
