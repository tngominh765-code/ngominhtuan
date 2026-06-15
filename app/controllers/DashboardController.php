<?php
require_once BASE_PATH . '/app/models/ProductModel.php';
require_once BASE_PATH . '/app/models/CategoryModel.php';
require_once BASE_PATH . '/app/models/OrderModel.php';

class DashboardController {
    private $productModel;
    private $categoryModel;
    private $orderModel;

    public function __construct() {
        $this->productModel = new ProductModel();
        $this->categoryModel = new CategoryModel();
        $this->orderModel = new OrderModel();
    }

    public function index() {
        requireAdmin();
        $productStats = $this->productModel->getProductStats();
        $orderStats = $this->orderModel->getOrderStats();
        $categories = $this->categoryModel->getCategoriesWithProductCount();
        $recentOrders = $this->orderModel->getRecentOrders('', 6);
        $latestProducts = $this->productModel->getFilteredProducts([], 'newest', 1, 4);
        [$labels, $data] = $this->orderModel->getRevenueLast7Days();
        $chartLabels = json_encode($labels, JSON_UNESCAPED_UNICODE);
        $chartData = json_encode($data);
        $db = Database::getInstance()->getConnection();
        $lowStockProducts = [];
        try {
            $cols = $db->query("SHOW COLUMNS FROM product LIKE 'stock'")->fetchAll(PDO::FETCH_ASSOC);
            if (!empty($cols)) {
                $stmt = $db->prepare("SELECT p.id, p.name, p.stock, c.name AS category_name FROM product p LEFT JOIN category c ON p.category_id = c.id WHERE p.stock <= 5 ORDER BY p.stock ASC LIMIT 8");
                $stmt->execute();
                $lowStockProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (Exception $e) { $lowStockProducts = []; }
        include BASE_PATH . '/app/views/dashboard/index.php';
    }
}
