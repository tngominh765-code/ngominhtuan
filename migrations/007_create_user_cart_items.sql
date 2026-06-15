-- Migration 007 - Lưu giỏ hàng theo tài khoản để không mất khi đóng/mở lại trình duyệt
USE my_store;

CREATE TABLE IF NOT EXISTS user_cart_items (
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
