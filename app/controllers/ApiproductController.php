<?php
/**
 * ApiProductController - RESTful API sản phẩm (Bài 5 + Bài 6 JWT).
 * Hỗ trợ JSON và multipart/form-data upload ảnh qua API.
 */
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApiproductController {
    private $productModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($id = null) {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($id === 'upload' && $method === 'POST') {
            JwtMiddleware::requireAuth('admin');
            $this->uploadImage();
            exit;
        }

        switch ($method) {
            case 'GET':
                $id ? $this->getById($id) : $this->getAll();
                break;
            case 'POST':
                JwtMiddleware::requireAuth('admin');
                $this->create();
                break;
            case 'PUT':
            case 'PATCH':
                JwtMiddleware::requireAuth('admin');
                $this->update($id);
                break;
            case 'DELETE':
                JwtMiddleware::requireAuth('admin');
                $this->deleteProduct($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Method not allowed'], JSON_UNESCAPED_UNICODE);
        }
        exit;
    }

    private function getAll() {
        $filters = [
            'keyword'     => $_GET['name'] ?? ($_GET['keyword'] ?? ''),
            'category_id' => $_GET['category_id'] ?? '',
            'min_price'   => $_GET['min_price'] ?? '',
            'max_price'   => $_GET['max_price'] ?? '',
        ];
        $sort = $_GET['sort'] ?? 'newest';
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = max(1, min(100, intval($_GET['per_page'] ?? 20)));
        $products = $this->productModel->getFilteredProducts($filters, $sort, $page, $perPage);
        $total = $this->productModel->countFilteredProducts($filters);
        echo json_encode(['data'=>$products, 'total'=>$total, 'page'=>$page, 'per_page'=>$perPage], JSON_UNESCAPED_UNICODE);
    }

    private function getById($id) {
        $product = $this->productModel->getProductById((int)$id);
        if ($product) echo json_encode($product, JSON_UNESCAPED_UNICODE);
        else { http_response_code(404); echo json_encode(['error'=>'Product not found'], JSON_UNESCAPED_UNICODE); }
    }

    private function getRequestData($allowFiles = false) {
        $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
        if (stripos($contentType, 'multipart/form-data') !== false) {
            $data = $_POST;
            if ($allowFiles && !empty($_FILES['image_file']['name'])) {
                $uploaded = $this->handleImageUpload($_FILES['image_file']);
                if (isset($uploaded['error'])) return ['__upload_error' => $uploaded['error']];
                $data['image'] = $uploaded['path'];
            }
            return $data;
        }
        $raw = file_get_contents('php://input');
        return json_decode($raw, true) ?: [];
    }

    private function create() {
        $data = $this->getRequestData(true);
        if (isset($data['__upload_error'])) { http_response_code(422); echo json_encode(['errors'=>['image'=>$data['__upload_error']]], JSON_UNESCAPED_UNICODE); return; }
        if (!$data) { http_response_code(400); echo json_encode(['error'=>'Invalid request data'], JSON_UNESCAPED_UNICODE); return; }

        $errors = $this->validate($data);
        if ($errors) { http_response_code(422); echo json_encode(['errors'=>$errors], JSON_UNESCAPED_UNICODE); return; }

        $result = $this->productModel->createProduct(trim($data['name']), $data['description'] ?? '', (float)$data['price'], $data['image'] ?? '', (int)$data['category_id']);
        if ($result) { http_response_code(201); echo json_encode(['message'=>'Product created successfully'], JSON_UNESCAPED_UNICODE); }
        else { http_response_code(500); echo json_encode(['error'=>'Failed to create product'], JSON_UNESCAPED_UNICODE); }
    }

    private function update($id) {
        if (!$id || !is_numeric($id)) { http_response_code(400); echo json_encode(['error'=>'Product ID required'], JSON_UNESCAPED_UNICODE); return; }
        if (!$this->productModel->getProductById((int)$id)) { http_response_code(404); echo json_encode(['error'=>'Product not found'], JSON_UNESCAPED_UNICODE); return; }
        $data = $this->getRequestData(false);
        if (!$data) { http_response_code(400); echo json_encode(['error'=>'Invalid JSON'], JSON_UNESCAPED_UNICODE); return; }
        $errors = $this->validate($data);
        if ($errors) { http_response_code(422); echo json_encode(['errors'=>$errors], JSON_UNESCAPED_UNICODE); return; }

        $result = $this->productModel->updateProduct((int)$id, trim($data['name']), $data['description'] ?? '', (float)$data['price'], $data['image'] ?? '', (int)$data['category_id']);
        if ($result) echo json_encode(['message'=>'Product updated successfully'], JSON_UNESCAPED_UNICODE);
        else { http_response_code(500); echo json_encode(['error'=>'Failed to update product'], JSON_UNESCAPED_UNICODE); }
    }

    private function deleteProduct($id) {
        if (!$id || !is_numeric($id)) { http_response_code(400); echo json_encode(['error'=>'Product ID required'], JSON_UNESCAPED_UNICODE); return; }
        if (!$this->productModel->getProductById((int)$id)) { http_response_code(404); echo json_encode(['error'=>'Product not found'], JSON_UNESCAPED_UNICODE); return; }
        $result = $this->productModel->deleteProduct((int)$id);
        if ($result) echo json_encode(['message'=>'Product deleted successfully'], JSON_UNESCAPED_UNICODE);
        else { http_response_code(500); echo json_encode(['error'=>'Failed to delete product'], JSON_UNESCAPED_UNICODE); }
    }

    private function validate($data) {
        $errors = [];
        if (empty(trim($data['name'] ?? ''))) $errors['name'] = 'Tên sản phẩm không được để trống';
        if (!isset($data['price']) || !is_numeric($data['price']) || (float)$data['price'] <= 0) $errors['price'] = 'Giá phải là số và lớn hơn 0';
        if (empty($data['category_id']) || !is_numeric($data['category_id'])) $errors['category_id'] = 'Danh mục sản phẩm là bắt buộc';
        else {
            $catModel = new CategoryModel();
            if (!$catModel->getCategoryById((int)$data['category_id'])) $errors['category_id'] = 'Danh mục không tồn tại';
        }
        if (!empty($data['image'])) {
            $allowed = ['jpg','jpeg','png','webp','gif'];
            $ext = strtolower(pathinfo(parse_url($data['image'], PHP_URL_PATH) ?: $data['image'], PATHINFO_EXTENSION));
            if ($ext && !in_array($ext, $allowed, true)) $errors['image'] = 'Định dạng ảnh không hợp lệ (jpg, jpeg, png, webp, gif)';
        }
        return $errors;
    }

    private function uploadImage() {
        if (empty($_FILES['image_file']['name'])) { http_response_code(400); echo json_encode(['error'=>'image_file required'], JSON_UNESCAPED_UNICODE); return; }
        $uploaded = $this->handleImageUpload($_FILES['image_file']);
        if (isset($uploaded['error'])) { http_response_code(422); echo json_encode(['error'=>$uploaded['error']], JSON_UNESCAPED_UNICODE); return; }
        echo json_encode(['message'=>'Upload thành công', 'image'=>$uploaded['path']], JSON_UNESCAPED_UNICODE);
    }

    private function handleImageUpload($file) {
        if (($file['error'] ?? UPLOAD_ERR_NO_FILE) !== UPLOAD_ERR_OK) return ['error'=>'Upload ảnh thất bại'];
        $allowedExt = ['jpg','jpeg','png','webp','gif'];
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowedExt, true)) return ['error'=>'Định dạng ảnh không hợp lệ'];
        if (($file['size'] ?? 0) > 3 * 1024 * 1024) return ['error'=>'Ảnh không được vượt quá 3MB'];
        $uploadDir = BASE_PATH . '/uploads';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0775, true);
        $filename = 'api_' . date('Ymd_His') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $target = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $target)) return ['error'=>'Không thể lưu file ảnh'];
        return ['path'=>'uploads/' . $filename];
    }
}
