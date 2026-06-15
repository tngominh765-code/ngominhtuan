<?php
class ReviewModel {
    private $conn;
    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    private function ensureTable() {
        try {
            $this->conn->exec("CREATE TABLE IF NOT EXISTS reviews (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                reviewer_name VARCHAR(100) NOT NULL,
                rating TINYINT NOT NULL,
                comment TEXT,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                INDEX idx_reviews_product (product_id)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
        } catch (Exception $e) {
            // Bỏ qua nếu DB user không có quyền CREATE TABLE; bảng nên đã có sẵn từ database.sql.
        }
    }

    public function getByProduct($productId) {
        $stmt = $this->conn->prepare("SELECT * FROM reviews WHERE product_id = :pid ORDER BY created_at DESC");
        $stmt->execute(['pid' => (int)$productId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getStats($productId) {
        $stmt = $this->conn->prepare("SELECT rating, COUNT(*) AS total FROM reviews WHERE product_id = :pid GROUP BY rating");
        $stmt->execute(['pid' => (int)$productId]);
        $dist = [1=>0,2=>0,3=>0,4=>0,5=>0]; $sum = 0; $count = 0;
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $r) {
            $rating = (int)$r['rating']; $total = (int)$r['total'];
            $dist[$rating] = $total; $sum += $rating * $total; $count += $total;
        }
        return ['avg' => $count ? $sum / $count : 0, 'count' => $count, 'dist' => $dist];
    }
    public function create($data) {
        $name = trim((string)($data['reviewer_name'] ?? ''));
        $comment = trim((string)($data['comment'] ?? ''));
        $rating = max(1, min(5, (int)($data['rating'] ?? 0)));
        $stmt = $this->conn->prepare("INSERT INTO reviews(product_id, reviewer_name, rating, comment) VALUES(:pid,:name,:rating,:comment)");
        return $stmt->execute(['pid'=>(int)$data['product_id'], 'name'=>$name, 'rating'=>$rating, 'comment'=>$comment]);
    }
    public function canReview($productId) { return true; }
}
