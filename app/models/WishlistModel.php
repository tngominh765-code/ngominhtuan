<?php
class WishlistModel {
    private $conn;
    public function __construct() { $this->conn = Database::getInstance()->getConnection(); $this->ensureTable(); }
    private function ensureTable() {
        try { $this->conn->exec("CREATE TABLE IF NOT EXISTS wishlist (id INT AUTO_INCREMENT PRIMARY KEY, session_id VARCHAR(128) NOT NULL, product_id INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE KEY uq_wish (session_id, product_id)) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4"); } catch (Exception $e) {}
    }
    public function getBySession($sid) {
        $stmt = $this->conn->prepare("SELECT p.*, c.name AS category_name, w.created_at AS wishlist_at FROM wishlist w JOIN product p ON p.id=w.product_id LEFT JOIN category c ON c.id=p.category_id WHERE w.session_id=:sid ORDER BY w.created_at DESC");
        $stmt->execute(['sid'=>$sid]); return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function add($sid, $productId) { $stmt=$this->conn->prepare("INSERT IGNORE INTO wishlist(session_id, product_id) VALUES(:sid,:pid)"); return $stmt->execute(['sid'=>$sid,'pid'=>(int)$productId]); }
    public function remove($sid, $productId) { $stmt=$this->conn->prepare("DELETE FROM wishlist WHERE session_id=:sid AND product_id=:pid"); return $stmt->execute(['sid'=>$sid,'pid'=>(int)$productId]); }
    public function clear($sid) { $stmt=$this->conn->prepare("DELETE FROM wishlist WHERE session_id=:sid"); return $stmt->execute(['sid'=>$sid]); }
    public function count($sid) { $stmt=$this->conn->prepare("SELECT COUNT(*) FROM wishlist WHERE session_id=:sid"); $stmt->execute(['sid'=>$sid]); return (int)$stmt->fetchColumn(); }
}
