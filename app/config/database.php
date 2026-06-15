<?php
/**
 * DATABASE CONFIGURATION
 * Sử dụng PDO để kết nối MySQL
 * Pattern: Singleton
 */

class Database {
    private static $instance = null;
    private $conn;

    // Thông tin kết nối
    private $host = 'localhost';
    private $dbname = 'my_store';
    private $username = 'root';
    private $password = '';

    /**
     * Constructor - Tạo kết nối PDO
     */
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            // Thiết lập chế độ lỗi: Exception
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            // Thiết lập chế độ fetch mặc định: Associative Array
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi kết nối Database: " . $e->getMessage());
        }
    }

    /**
     * Lấy instance duy nhất (Singleton Pattern)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lấy đối tượng PDO connection
     */
    public function getConnection() {
        return $this->conn;
    }
}
