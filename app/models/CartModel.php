<?php
/**
 * CartModel - Lưu giỏ hàng bền vững theo tài khoản người dùng.
 *
 * Trước đây giỏ hàng chỉ lưu trong $_SESSION nên khi đóng trình duyệt,
 * session hết hạn hoặc đăng xuất thì giỏ bị mất. Model này lưu giỏ vào DB
 * để user đăng nhập lại vẫn thấy các sản phẩm đã thêm.
 */
class CartModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    private function ensureTable() {
        try {
            $this->conn->exec(
                "CREATE TABLE IF NOT EXISTS user_cart_items (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    account_id INT NOT NULL,
                    product_id INT NOT NULL,
                    quantity INT NOT NULL DEFAULT 1,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                    UNIQUE KEY uq_user_cart_item (account_id, product_id),
                    INDEX idx_user_cart_account (account_id),
                    INDEX idx_user_cart_product (product_id),
                    CONSTRAINT fk_user_cart_account
                        FOREIGN KEY (account_id) REFERENCES account(id)
                        ON DELETE CASCADE ON UPDATE CASCADE,
                    CONSTRAINT fk_user_cart_product
                        FOREIGN KEY (product_id) REFERENCES product(id)
                        ON DELETE CASCADE ON UPDATE CASCADE
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
            );
        } catch (Exception $e) {
            // Không làm gián đoạn website nếu DB user hiện tại chưa có quyền ALTER/CREATE.
        }
    }

    public function getCartForUser($accountId) {
        $accountId = (int)$accountId;
        if ($accountId <= 0) return [];

        try {
            $stmt = $this->conn->prepare(
                "SELECT p.*, c.name AS category_name, ci.quantity
                 FROM user_cart_items ci
                 JOIN product p ON p.id = ci.product_id
                 LEFT JOIN category c ON c.id = p.category_id
                 WHERE ci.account_id = :account_id
                 ORDER BY ci.created_at ASC, ci.id ASC"
            );
            $stmt->execute(['account_id' => $accountId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }

        $cart = [];
        foreach ($rows as $row) {
            $id = (int)$row['id'];
            $stock = isset($row['stock']) ? (int)$row['stock'] : 99;
            $quantity = $this->normalizeQuantity($row['quantity'], $stock);
            if ($quantity <= 0) continue;

            $cart[$id] = [
                'id' => $id,
                'name' => $row['name'],
                'price' => $row['price'],
                'image' => $row['image'],
                'category_id' => $row['category_id'] ?? null,
                'quantity' => $quantity,
            ];
        }
        return $cart;
    }

    public function replaceCart($accountId, array $cart) {
        $accountId = (int)$accountId;
        if ($accountId <= 0) return false;

        try {
            $this->conn->beginTransaction();
            $delete = $this->conn->prepare("DELETE FROM user_cart_items WHERE account_id = :account_id");
            $delete->execute(['account_id' => $accountId]);

            $insert = $this->conn->prepare(
                "INSERT INTO user_cart_items (account_id, product_id, quantity, created_at, updated_at)
                 VALUES (:account_id, :product_id, :quantity, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)"
            );

            foreach ($cart as $key => $item) {
                $productId = (int)($item['id'] ?? $key);
                $quantity = $this->normalizeQuantity($item['quantity'] ?? 0);
                if ($productId <= 0 || $quantity <= 0) continue;
                $insert->execute([
                    'account_id' => $accountId,
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }

            $this->conn->commit();
            return true;
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            return false;
        }
    }

    public function clearCart($accountId) {
        $accountId = (int)$accountId;
        if ($accountId <= 0) return false;
        try {
            $stmt = $this->conn->prepare("DELETE FROM user_cart_items WHERE account_id = :account_id");
            return $stmt->execute(['account_id' => $accountId]);
        } catch (Exception $e) {
            return false;
        }
    }

    public function mergeSessionCart($accountId, array $sessionCart) {
        $accountId = (int)$accountId;
        if ($accountId <= 0) return [];

        $merged = $this->getCartForUser($accountId);
        foreach ($sessionCart as $key => $item) {
            $productId = (int)($item['id'] ?? $key);
            $quantity = $this->normalizeQuantity($item['quantity'] ?? 0);
            if ($productId <= 0 || $quantity <= 0) continue;

            if (isset($merged[$productId])) {
                $merged[$productId]['quantity'] = $this->normalizeQuantity(((int)$merged[$productId]['quantity']) + $quantity);
            } else {
                $merged[$productId] = [
                    'id' => $productId,
                    'name' => $item['name'] ?? '',
                    'price' => $item['price'] ?? 0,
                    'image' => $item['image'] ?? '',
                    'category_id' => $item['category_id'] ?? null,
                    'quantity' => $quantity,
                ];
            }
        }

        $this->replaceCart($accountId, $merged);
        return $this->getCartForUser($accountId);
    }

    private function normalizeQuantity($quantity, $stock = 99) {
        $quantity = max(0, (int)$quantity);
        $stock = (int)$stock;
        $max = $stock > 0 ? min(99, $stock) : 99;
        return min($max, $quantity);
    }
}
