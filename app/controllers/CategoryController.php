<?php
/**
 * CategoryController - Quản lý danh mục
 * CRUD: Thêm, Hiển thị, Sửa, Xóa danh mục
 */

require_once BASE_PATH . '/app/models/CategoryModel.php';
require_once BASE_PATH . '/app/helpers/SessionHelper.php';

class CategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
    }

    /**
     * Hiển thị danh sách danh mục
     */
    public function index() {
        $categories = $this->categoryModel->getCategoriesWithProductCount();
        include BASE_PATH . '/app/views/category/index.php';
    }

    /**
     * Hiển thị form thêm danh mục
     */
    public function create() {
        requireAdmin();
        $errors = [];
        $old = [];
        include BASE_PATH . '/app/views/category/create.php';
    }

    /**
     * Xử lý lưu danh mục mới (POST)
     */
    public function store() {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=category/create');
            exit;
        }
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Token không hợp lệ.';
            redirect('index.php?url=category/create');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $errors = [];
        $old = $_POST;

        // Ràng buộc: Tên không được rỗng
        if (empty($name)) {
            $errors[] = 'Tên danh mục không được để trống.';
        }

        // Nếu có lỗi
        if (!empty($errors)) {
            include BASE_PATH . '/app/views/category/create.php';
            return;
        }

        $result = $this->categoryModel->createCategory($name, $description);

        if ($result) {
            $_SESSION['success'] = 'Thêm danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        redirect('index.php?url=category');
        exit;
    }

    /**
     * Hiển thị form sửa danh mục
     */
    public function edit($id) {
        requireAdmin();
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại!';
            redirect('index.php?url=category');
            exit;
        }

        $errors = [];
        include BASE_PATH . '/app/views/category/edit.php';
    }

    /**
     * Xử lý cập nhật danh mục (POST)
     */
    public function update($id) {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=category/edit/' . $id);
            exit;
        }
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Token không hợp lệ.';
            redirect('index.php?url=category/edit/' . $id);
        }

        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại!';
            redirect('index.php?url=category');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $errors = [];

        if (empty($name)) {
            $errors[] = 'Tên danh mục không được để trống.';
        }

        if (!empty($errors)) {
            $category['name'] = $name;
            $category['description'] = $description;
            include BASE_PATH . '/app/views/category/edit.php';
            return;
        }

        $result = $this->categoryModel->updateCategory($id, $name, $description);

        if ($result) {
            $_SESSION['success'] = 'Cập nhật danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        redirect('index.php?url=category');
        exit;
    }

    /**
     * Xóa danh mục
     */
    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $_SESSION['error'] = 'Yêu cầu xóa phải dùng POST.'; redirect('index.php?url=category'); }
        requireAdmin();
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=category'); }
        $category = $this->categoryModel->getCategoryById($id);
        if (!$category) {
            $_SESSION['error'] = 'Danh mục không tồn tại!';
            redirect('index.php?url=category');
            exit;
        }

        $result = $this->categoryModel->deleteCategory($id);

        if ($result) {
            $_SESSION['success'] = 'Xóa danh mục thành công!';
        } else {
            $_SESSION['error'] = 'Có lỗi xảy ra, vui lòng thử lại!';
        }

        redirect('index.php?url=category');
        exit;
    }
}
