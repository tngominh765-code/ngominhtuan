<?php
/**
 * CategoryModel - Category data access.
 */
class CategoryModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
    }

    public function getAllCategories() {
        $stmt = $this->conn->prepare("SELECT * FROM category ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategoriesWithProductCount() {
        $stmt = $this->conn->prepare(
            "SELECT c.*, COUNT(p.id) AS product_count
             FROM category c
             LEFT JOIN product p ON p.category_id = c.id
             GROUP BY c.id
             ORDER BY c.id DESC"
        );
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getCategoryById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM category WHERE id = :id");
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }

    public function createCategory($name, $description) {
        $stmt = $this->conn->prepare(
            "INSERT INTO category (name, description) VALUES (:name, :description)"
        );
        return $stmt->execute([
            'name' => $name,
            'description' => $description
        ]);
    }

    public function updateCategory($id, $name, $description) {
        $stmt = $this->conn->prepare(
            "UPDATE category SET name = :name, description = :description WHERE id = :id"
        );
        return $stmt->execute([
            'id' => $id,
            'name' => $name,
            'description' => $description
        ]);
    }

    public function deleteCategory($id) {
        $stmt = $this->conn->prepare("DELETE FROM category WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
