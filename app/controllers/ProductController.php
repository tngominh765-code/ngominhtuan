<?php
/**
 * ProductController - Product CRUD + filtering UI support.
 */
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';
if (file_exists(BASE_PATH . '/app/models/ReviewModel.php')) require_once BASE_PATH . '/app/models/ReviewModel.php';
require_once BASE_PATH . '/app/helpers/SessionHelper.php';

class ProductController {
    private $productModel;
    private $categoryModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index() {
        $keyword = trim($_GET['q'] ?? '');
        $categoryId = intval($_GET['category_id'] ?? 0);
        $sort = $_GET['sort'] ?? 'newest';
        $minPrice = $this->parseOptionalNumber($_GET['min_price'] ?? null);
        $maxPrice = $this->parseOptionalNumber($_GET['max_price'] ?? null);
        if ($minPrice !== null && $maxPrice !== null && $minPrice > $maxPrice) {
            $tmp = $minPrice;
            $minPrice = $maxPrice;
            $maxPrice = $tmp;
        }
        $page = max(1, intval($_GET['page'] ?? 1));
        $perPage = 8;

        $filters = [
            'keyword' => $keyword,
            'category_id' => $categoryId,
            'min_price' => $minPrice,
            'max_price' => $maxPrice,
        ];

        $totalProducts = $this->productModel->countFilteredProducts($filters);
        $totalPages = max(1, (int) ceil($totalProducts / $perPage));
        if ($page > $totalPages) {
            $page = $totalPages;
        }

        $products = $this->productModel->getFilteredProducts($filters, $sort, $page, $perPage);
        $categories = $this->categoryModel->getAllCategories();
        $currentCategoryName = '';
        if ($categoryId > 0) {
            foreach ($categories as $cat) {
                if ((int)$cat['id'] === $categoryId) { $currentCategoryName = $cat['name']; break; }
            }
        }
        $priceBounds = $this->productModel->getPriceBounds();
        $productStats = $this->productModel->getProductStats();

        $activeFilterCount = 0;
        if ($keyword !== '') $activeFilterCount++;
        if ($categoryId > 0) $activeFilterCount++;
        if ($minPrice !== null) $activeFilterCount++;
        if ($maxPrice !== null) $activeFilterCount++;
        if ($sort !== 'newest') $activeFilterCount++;

        include BASE_PATH . '/app/views/product/index.php';
    }

    public function show($id) {
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'San pham khong ton tai!';
            redirect('index.php?url=product');
            exit;
        }
        $relatedProducts = $this->productModel->getRelatedProducts($product['category_id'] ?? 0, $product['id'], 4);
        $reviews = []; $reviewStats = ['avg'=>0,'count'=>0,'dist'=>[1=>0,2=>0,3=>0,4=>0,5=>0]];
        if (class_exists('ReviewModel')) { $reviewModel = new ReviewModel(); $reviews = $reviewModel->getByProduct($product['id']); $reviewStats = $reviewModel->getStats($product['id']); }
        include BASE_PATH . '/app/views/product/show.php';
    }

    public function create() {
        requireAdmin();
        $categories = $this->categoryModel->getAllCategories();
        $errors = [];
        $old = [];
        include BASE_PATH . '/app/views/product/create.php';
    }

    public function store() {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=product/create');
            exit;
        }
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Token không hợp lệ.';
            redirect('index.php?url=product/create');
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $categoryId = intval($_POST['category_id'] ?? 0);
        $errors = [];
        $old = $_POST;

        $nameLen = textLengthUtf8($name);
        if ($nameLen < 10 || $nameLen > 100) {
            $errors[] = 'Ten san pham phai tu 10 den 100 ky tu (hien tai: ' . $nameLen . ').';
        }

        if ($price <= 0) {
            $errors[] = 'Gia san pham phai lon hon 0.';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Vui long chon danh muc.';
        }

        $imageName = '';
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imageName = $this->uploadImage($_FILES['image'], $errors);
        }

        if (!empty($errors)) {
            $categories = $this->categoryModel->getAllCategories();
            include BASE_PATH . '/app/views/product/create.php';
            return;
        }

        $result = $this->productModel->createProduct($name, $description, $price, $imageName, $categoryId);
        if ($result) {
            $_SESSION['success'] = 'Them san pham thanh cong!';
        } else {
            $_SESSION['error'] = 'Co loi xay ra, vui long thu lai!';
        }

        redirect('index.php?url=product');
        exit;
    }

    public function edit($id) {
        requireAdmin();
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'San pham khong ton tai!';
            redirect('index.php?url=product');
            exit;
        }

        $categories = $this->categoryModel->getAllCategories();
        $errors = [];
        include BASE_PATH . '/app/views/product/edit.php';
    }

    public function update($id) {
        requireAdmin();
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php?url=product/edit/' . $id);
            exit;
        }
        if (!verify_csrf()) {
            $_SESSION['error'] = 'Token không hợp lệ.';
            redirect('index.php?url=product/edit/' . $id);
        }

        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'San pham khong ton tai!';
            redirect('index.php?url=product');
            exit;
        }

        $name = trim($_POST['name'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $price = floatval($_POST['price'] ?? 0);
        $categoryId = intval($_POST['category_id'] ?? 0);
        $errors = [];

        $nameLen = textLengthUtf8($name);
        if ($nameLen < 10 || $nameLen > 100) {
            $errors[] = 'Ten san pham phai tu 10 den 100 ky tu (hien tai: ' . $nameLen . ').';
        }

        if ($price <= 0) {
            $errors[] = 'Gia san pham phai lon hon 0.';
        }

        if ($categoryId <= 0) {
            $errors[] = 'Vui long chon danh muc.';
        }

        $imageName = $product['image'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $newImage = $this->uploadImage($_FILES['image'], $errors);
            if ($newImage) {
                if (!empty($product['image']) && file_exists(BASE_PATH . '/uploads/' . $product['image'])) {
                    unlink(BASE_PATH . '/uploads/' . $product['image']);
                }
                $imageName = $newImage;
            }
        }

        if (!empty($errors)) {
            $product['name'] = $name;
            $product['description'] = $description;
            $product['price'] = $price;
            $product['category_id'] = $categoryId;
            $categories = $this->categoryModel->getAllCategories();
            include BASE_PATH . '/app/views/product/edit.php';
            return;
        }

        $result = $this->productModel->updateProduct($id, $name, $description, $price, $imageName, $categoryId);

        if ($result) {
            $_SESSION['success'] = 'Cap nhat san pham thanh cong!';
        } else {
            $_SESSION['error'] = 'Co loi xay ra, vui long thu lai!';
        }

        redirect('index.php?url=product');
        exit;
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $_SESSION['error'] = 'Yêu cầu xóa phải dùng POST.'; redirect('index.php?url=product'); }
        requireAdmin();
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=product'); }
        $product = $this->productModel->getProductById($id);
        if (!$product) {
            $_SESSION['error'] = 'San pham khong ton tai!';
            redirect('index.php?url=product');
            exit;
        }

        if (!empty($product['image']) && file_exists(BASE_PATH . '/uploads/' . $product['image'])) {
            unlink(BASE_PATH . '/uploads/' . $product['image']);
        }

        $result = $this->productModel->deleteProduct($id);

        if ($result) {
            $_SESSION['success'] = 'Xoa san pham thanh cong!';
        } else {
            $_SESSION['error'] = 'Co loi xay ra, vui long thu lai!';
        }

        redirect('index.php?url=product');
        exit;
    }



    public function apimanager() {
        requireAdmin();
        include BASE_PATH . '/app/views/product/api_manager.php';
    }

    /**
     * Parse optional price filter from GET query.
     * Empty strings are treated as null so the product listing page does not crash.
     */
    private function parseOptionalNumber($value) {
        if ($value === null) {
            return null;
        }
        if (is_string($value)) {
            $value = trim($value);
        }
        if ($value === '') {
            return null;
        }
        // Accept common Vietnamese formatted values such as 10.000.000 or 10,000,000.
        if (is_string($value)) {
            $normalized = str_replace([' ', '.', ','], '', $value);
            if ($normalized !== '' && is_numeric($normalized)) {
                return max(0, (float)$normalized);
            }
        }
        return is_numeric($value) ? max(0, (float)$value) : null;
    }

    private function uploadImage($file, &$errors) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;
        $mime = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        } else {
            $info = getimagesize($file['tmp_name']);
            $mime = $info['mime'] ?? ($file['type'] ?? '');
        }
        if (!in_array($mime, $allowedTypes, true)) { $errors[] = 'Chỉ chấp nhận JPG, PNG, GIF, WEBP.'; return ''; }
        if (($file['size'] ?? 0) > $maxSize) { $errors[] = 'Dung lượng tối đa 5MB.'; return ''; }
        $processed = $this->processUploadedImage($file['tmp_name'], $file['name']);
        if (!$processed) { $errors[] = 'Không thể xử lý ảnh tải lên.'; return ''; }
        return $processed;
    }

    private function processUploadedImage($tmpPath, $fileName) {
        $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif','webp'];
        if (!in_array($ext, $allowed, true)) return false;
        $destDir = BASE_PATH . '/uploads/';
        if (!is_dir($destDir)) mkdir($destDir, 0775, true);
        $safeExt = $ext === 'jpeg' ? 'jpg' : $ext;
        $safeName = uniqid('img_', true) . '.' . $safeExt;
        $destPath = $destDir . $safeName;
        if (!function_exists('imagecreatefromjpeg')) { move_uploaded_file($tmpPath, $destPath); return $safeName; }
        $info = getimagesize($tmpPath); if (!$info) return false;
        [$origW, $origH, $type] = [$info[0], $info[1], $info[2]];
        $ratio = min(800 / $origW, 800 / $origH, 1.0);
        $newW = (int)round($origW * $ratio); $newH = (int)round($origH * $ratio);
        switch ($type) {
            case IMAGETYPE_JPEG: $src = imagecreatefromjpeg($tmpPath); break;
            case IMAGETYPE_PNG: $src = imagecreatefrompng($tmpPath); break;
            case IMAGETYPE_GIF: $src = imagecreatefromgif($tmpPath); break;
            case IMAGETYPE_WEBP: $src = function_exists('imagecreatefromwebp') ? imagecreatefromwebp($tmpPath) : false; break;
            default: $src = false;
        }
        if (!$src) { move_uploaded_file($tmpPath, $destPath); return $safeName; }
        $dst = imagecreatetruecolor($newW, $newH);
        if (in_array($type, [IMAGETYPE_PNG, IMAGETYPE_GIF], true)) { imagealphablending($dst, false); imagesavealpha($dst, true); imagefilledrectangle($dst, 0, 0, $newW, $newH, imagecolorallocatealpha($dst, 0, 0, 0, 127)); }
        imagecopyresampled($dst, $src, 0, 0, 0, 0, $newW, $newH, $origW, $origH);
        switch ($type) {
            case IMAGETYPE_JPEG: imagejpeg($dst, $destPath, 82); break;
            case IMAGETYPE_PNG: imagepng($dst, $destPath, 7); break;
            case IMAGETYPE_GIF: imagegif($dst, $destPath); break;
            case IMAGETYPE_WEBP: function_exists('imagewebp') ? imagewebp($dst, $destPath, 82) : imagejpeg($dst, $destPath, 82); break;
            default: imagejpeg($dst, $destPath, 82);
        }
        imagedestroy($src); imagedestroy($dst); return $safeName;
    }
}
