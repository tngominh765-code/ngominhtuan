<?php
/**
 * ProductModel - Data access for products.
 */
class ProductModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureStockColumn();
    }

    /**
     * Tự thêm cột stock nếu chưa tồn tại.
     */
    private function ensureStockColumn() {
        try {
            $stmt = $this->conn->prepare("SHOW COLUMNS FROM product LIKE 'stock'");
            $stmt->execute();
            if (!$stmt->fetch()) {
                $this->conn->exec("ALTER TABLE product ADD COLUMN stock INT DEFAULT 10 AFTER category_id");
            }
        } catch (Exception $e) {
            // Bỏ qua nếu không thể ALTER
        }
    }

    public function getAllProducts() {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.name AS category_name
             FROM product p
             LEFT JOIN category c ON p.category_id = c.id
             ORDER BY p.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getFilteredProducts($filters, $sort = 'newest', $page = 1, $perPage = 12) {
        $params = [];
        $whereSql = $this->buildFilterWhere($filters, $params);
        $orderBy = $this->getSortSql($sort);
        $offset = max(0, ($page - 1) * $perPage);

        $sql = "SELECT p.*, c.name AS category_name
                FROM product p
                LEFT JOIN category c ON p.category_id = c.id
                {$whereSql}
                ORDER BY {$orderBy}
                LIMIT :limit OFFSET :offset";

        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }
        $stmt->bindValue(':limit', (int) $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int) $offset, PDO::PARAM_INT);

        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function countFilteredProducts($filters) {
        $params = [];
        $whereSql = $this->buildFilterWhere($filters, $params);

        $sql = "SELECT COUNT(*) AS total FROM product p {$whereSql}";
        $stmt = $this->conn->prepare($sql);
        foreach ($params as $key => $value) {
            $stmt->bindValue($key, $value);
        }

        $stmt->execute();
        $row = $stmt->fetch();
        return (int) ($row['total'] ?? 0);
    }

    public function getPriceBounds() {
        $stmt = $this->conn->prepare("SELECT MIN(price) AS min_price, MAX(price) AS max_price FROM product");
        $stmt->execute();
        $row = $stmt->fetch();

        return [
            'min_price' => isset($row['min_price']) ? (float) $row['min_price'] : 0,
            'max_price' => isset($row['max_price']) ? (float) $row['max_price'] : 0,
        ];
    }

    public function getProductStats() {
        $stmt = $this->conn->prepare(
            "SELECT
                COUNT(*) AS total_products,
                COUNT(DISTINCT category_id) AS used_categories,
                COALESCE(AVG(price), 0) AS avg_price,
                COALESCE(MIN(price), 0) AS min_price,
                COALESCE(MAX(price), 0) AS max_price
             FROM product"
        );
        $stmt->execute();
        return $stmt->fetch();
    }

    public function getProductById($id) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.name AS category_name
             FROM product p
             LEFT JOIN category c ON p.category_id = c.id
             WHERE p.id = :id"
        );
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function getProductsByCategory($categoryId) {
        $stmt = $this->conn->prepare(
            "SELECT p.*, c.name AS category_name
             FROM product p
             LEFT JOIN category c ON p.category_id = c.id
             WHERE p.category_id = :category_id
             ORDER BY p.id DESC"
        );
        $stmt->execute(['category_id' => $categoryId]);
        return $stmt->fetchAll();
    }

    public function createProduct($name, $description, $price, $image, $categoryId) {
        $stmt = $this->conn->prepare(
            "INSERT INTO product (name, description, price, image, category_id)
             VALUES (:name, :description, :price, :image, :category_id)"
        );
        return $stmt->execute([
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image,
            'category_id' => $categoryId
        ]);
    }

    public function updateProduct($id, $name, $description, $price, $image, $categoryId) {
        $stmt = $this->conn->prepare(
            "UPDATE product
             SET name = :name, description = :description, price = :price,
                 image = :image, category_id = :category_id
             WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description,
            'price' => $price,
            'image' => $image,
            'category_id' => $categoryId
        ]);
    }

    public function deleteProduct($id) {
        $stmt = $this->conn->prepare("DELETE FROM product WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }


    public function getRelatedProducts($categoryId, $excludeId, $limit = 4) {
        $categoryId = (int) $categoryId;
        $excludeId = (int) $excludeId;
        $limit = max(1, min(12, (int) $limit));

        if ($categoryId > 0) {
            $stmt = $this->conn->prepare(
                "SELECT p.*, c.name AS category_name
                 FROM product p
                 LEFT JOIN category c ON p.category_id = c.id
                 WHERE p.category_id = :category_id AND p.id <> :exclude_id
                 ORDER BY p.id DESC
                 LIMIT :limit"
            );
            $stmt->bindValue(':category_id', $categoryId, PDO::PARAM_INT);
            $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
            $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
            $stmt->execute();
            $rows = $stmt->fetchAll();
            if (!empty($rows)) {
                return $rows;
            }
        }

        $stmt = $this->conn->prepare(
            "SELECT p.*, c.name AS category_name
             FROM product p
             LEFT JOIN category c ON p.category_id = c.id
             WHERE p.id <> :exclude_id
             ORDER BY p.id DESC
             LIMIT :limit"
        );
        $stmt->bindValue(':exclude_id', $excludeId, PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    private function buildFilterWhere($filters, &$params) {
        $conditions = [];

        if (!empty($filters['keyword'])) {
            $conditions[] = '(p.name LIKE :keyword OR p.description LIKE :keyword)';
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }

        if (!empty($filters['category_id'])) {
            $conditions[] = 'p.category_id = :category_id';
            $params[':category_id'] = (int) $filters['category_id'];
        }

        if (isset($filters['min_price']) && $filters['min_price'] !== '' && $filters['min_price'] !== null) {
            $conditions[] = 'p.price >= :min_price';
            $params[':min_price'] = (float) $filters['min_price'];
        }

        if (isset($filters['max_price']) && $filters['max_price'] !== '' && $filters['max_price'] !== null) {
            $conditions[] = 'p.price <= :max_price';
            $params[':max_price'] = (float) $filters['max_price'];
        }

        if (empty($conditions)) {
            return '';
        }

        return 'WHERE ' . implode(' AND ', $conditions);
    }

    private function getSortSql($sort) {
        $sortMap = [
            'newest' => 'p.id DESC',
            'oldest' => 'p.id ASC',
            'price_asc' => 'p.price ASC',
            'price_desc' => 'p.price DESC',
            'name_asc' => 'p.name ASC',
            'name_desc' => 'p.name DESC',
        ];

        return $sortMap[$sort] ?? $sortMap['newest'];
    }

    /**
     * Trừ tồn kho sau khi đặt hàng thành công.
     */
    public function reduceStock($productId, $quantity) {
        try {
            $stmt = $this->conn->prepare(
                "UPDATE product SET stock = GREATEST(0, stock - :qty) WHERE id = :id"
            );
            return $stmt->execute(['qty' => (int)$quantity, 'id' => (int)$productId]);
        } catch (Exception $e) {
            // Nếu cột stock chưa tồn tại, bỏ qua
            return false;
        }
    }
}

