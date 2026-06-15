<?php
/**
 * ApiCategoryController - RESTful API cho danh mục (Bài 5).
 */
require_once BASE_PATH . '/app/models/CategoryModel.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApicategoryController {
    private $categoryModel;

    public function __construct() {
        $this->categoryModel = new CategoryModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($id = null) {
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
                $this->checkAuth();
                $this->create();
                break;
            case 'PUT':
                $this->checkAuth();
                $this->update($id);
                break;
            case 'DELETE':
                $this->checkAuth();
                $this->deleteCategory($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed']);
        }
        exit;
    }

    private function getAll() {
        $categories = $this->categoryModel->getAllCategories();
        echo json_encode($categories);
    }

    private function getById($id) {
        $category = $this->categoryModel->getCategoryById($id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }

    private function create() {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || empty(trim($data['name'] ?? ''))) {
            http_response_code(422);
            echo json_encode(['error' => 'Category name is required']);
            return;
        }

        $result = $this->categoryModel->createCategory($data['name'], $data['description'] ?? '');
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Category created successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to create category']);
        }
    }

    private function update($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Category ID required']);
            return;
        }
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            http_response_code(400);
            echo json_encode(['error' => 'Invalid JSON']);
            return;
        }
        if (empty(trim($data['name'] ?? ''))) {
            http_response_code(422);
            echo json_encode(['error' => 'Tên danh mục không được để trống'], JSON_UNESCAPED_UNICODE);
            return;
        }

        $result = $this->categoryModel->updateCategory($id, trim($data['name']), $data['description'] ?? '');
        if ($result) {
            echo json_encode(['message' => 'Category updated successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to update category']);
        }
    }

    private function deleteCategory($id) {
        if (!$id) {
            http_response_code(400);
            echo json_encode(['error' => 'Category ID required']);
            return;
        }

        // Kiểm tra còn sản phẩm thuộc danh mục này không
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT COUNT(*) AS cnt FROM product WHERE category_id = :id");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        if ((int)($row['cnt'] ?? 0) > 0) {
            http_response_code(422);
            echo json_encode(['error' => 'Không thể xóa danh mục vì vẫn còn sản phẩm thuộc danh mục này']);
            return;
        }

        $result = $this->categoryModel->deleteCategory($id);
        if ($result) {
            echo json_encode(['message' => 'Category deleted successfully']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to delete category']);
        }
    }

    private function checkAuth() {
        return JwtMiddleware::requireAuth('admin');
    }
}
