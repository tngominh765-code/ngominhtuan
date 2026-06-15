-- =============================================
-- CƠ SỞ DỮ LIỆU NOVATECH STORE - BÁN HÀNG CÔNG NGHỆ
-- Bản nâng cấp tài khoản: email verification, reset password, avatar, remember me, lock/unlock.
-- =============================================

-- Bắt buộc để tránh lỗi font/mojibake (ký tự lạ) khi import bằng phpMyAdmin/dòng lệnh
-- trên các client có charset mặc định khác utf8mb4 (ví dụ latin1).
SET NAMES utf8mb4;




CREATE TABLE IF NOT EXISTS category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS product (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DOUBLE NOT NULL,
    image VARCHAR(255),
    category_id INT,
    FOREIGN KEY (category_id) REFERENCES category(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng tài khoản người dùng nâng cao
CREATE TABLE IF NOT EXISTS account (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL UNIQUE,
    email VARCHAR(255) NULL,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(255) NOT NULL,
    avatar VARCHAR(255) NULL,
    role ENUM('admin', 'user') DEFAULT 'user',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    email_verified_at DATETIME NULL,
    email_verification_token VARCHAR(64) NULL,
    password_reset_token VARCHAR(64) NULL,
    password_reset_expires DATETIME NULL,
    remember_token_hash VARCHAR(255) NULL,
    last_login_at DATETIME NULL,
    failed_login_attempts INT NOT NULL DEFAULT 0,
    locked_until DATETIME NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NULL,
    INDEX idx_account_email (email),
    INDEX idx_account_reset_token (password_reset_token),
    INDEX idx_account_verify_token (email_verification_token)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS orders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    account_id INT NULL,
    name VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    email VARCHAR(255) NULL,
    address TEXT NOT NULL,
    note TEXT NULL,
    payment_method VARCHAR(50) DEFAULT 'COD',
    subtotal_amount DOUBLE DEFAULT 0,
    discount_amount DOUBLE DEFAULT 0,
    shipping_fee DOUBLE DEFAULT 0,
    total_amount DOUBLE DEFAULT 0,
    status VARCHAR(30) DEFAULT 'new',
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_orders_account_id (account_id),
    FOREIGN KEY (account_id) REFERENCES account(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS order_details (
    id INT AUTO_INCREMENT PRIMARY KEY,
    order_id INT NOT NULL,
    product_id INT,
    quantity INT NOT NULL,
    price DOUBLE NOT NULL,
    FOREIGN KEY (order_id) REFERENCES orders(id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id) REFERENCES product(id)
        ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Bảng lưu giỏ hàng theo tài khoản. Giúp giỏ hàng không mất khi đóng/mở lại trình duyệt hoặc đăng nhập lại.
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

-- Bảng đánh giá sản phẩm
CREATE TABLE IF NOT EXISTS reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    product_id INT NOT NULL,
    reviewer_name VARCHAR(100) NOT NULL,
    rating TINYINT NOT NULL,
    comment TEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_reviews_product (product_id),
    CONSTRAINT fk_reviews_product
        FOREIGN KEY (product_id) REFERENCES product(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bảng danh sách yêu thích (theo session)
CREATE TABLE IF NOT EXISTS wishlist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    session_id VARCHAR(128) NOT NULL,
    product_id INT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY uq_wish (session_id, product_id),
    CONSTRAINT fk_wishlist_product
        FOREIGN KEY (product_id) REFERENCES product(id)
        ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dữ liệu mẫu - danh mục
INSERT INTO category (name, description) VALUES
('Điện thoại', 'Smartphone cao cấp từ các thương hiệu hàng đầu thế giới'),
('Laptop', 'Máy tính xách tay cho công việc, sáng tạo và giải trí'),
('Phụ kiện', 'Phụ kiện công nghệ chính hãng'),
('Tablet', 'Máy tính bảng đa năng'),
('Âm thanh', 'Tai nghe và loa không dây cao cấp'),
('Đồng hồ', 'Đồng hồ thông minh thế hệ mới');

-- Dữ liệu mẫu - sản phẩm
INSERT INTO product (name, description, price, image, category_id) VALUES
('iPhone 15 Pro Max 256GB', 'Chip A17 Pro mạnh mẽ, camera 48MP với zoom quang 5x, khung Titan cực bền. Màn hình Super Retina XDR 6.7 inch, Dynamic Island, USB-C tốc độ cao.', 34990000, 'iphone15.png', 1),
('Samsung Galaxy S24 Ultra', 'Chip Snapdragon 8 Gen 3, camera 200MP, bút S-Pen tích hợp, Galaxy AI thông minh. Màn hình Dynamic AMOLED 2X 6.8 inch, pin 5000mAh.', 31990000, 'samsung.png', 1),
('Xiaomi 14 Ultra 5G 512GB', 'Camera Leica chuyên nghiệp 50MP, chip Snapdragon 8 Gen 3, sạc nhanh 90W. Màn hình LTPO AMOLED 6.73 inch 120Hz, chống nước IP68.', 22990000, 'samsung.png', 1),
('MacBook Pro M3 14 inch', 'Chip Apple M3 Pro, RAM 18GB thống nhất. Màn hình Liquid Retina XDR, pin lên tới 17 giờ, 3 cổng Thunderbolt 4, MagSafe 3.', 49990000, 'macbook.png', 2),
('Dell XPS 15 9530 Core i7', 'Intel Core i7-13700H, RAM 16GB DDR5, SSD 512GB, NVIDIA RTX 4050. Màn hình OLED 3.5K 15.6 inch, thiết kế InfinityEdge.', 35990000, 'dell.png', 2),
('ASUS ROG Zephyrus G14 2024', 'AMD Ryzen 9 8945HS, RTX 4060 8GB, RAM 16GB DDR5x. Màn hình ROG Nebula 14 inch 2.8K OLED 120Hz.', 39990000, 'dell.png', 2),
('AirPods Pro 2 USB-C', 'Chip H2 với khử tiếng ồn chủ động, âm thanh không gian cá nhân hóa, chống nước IP54, sạc MagSafe, pin 6 giờ.', 6490000, 'airpods.png', 3),
('Sạc MagSafe Apple 15W', 'Sạc không dây chuẩn Qi2, công suất 15W, tương thích iPhone 12 trở lên. Nam châm bám chắc, cáp USB-C dài 1m.', 1290000, 'airpods.png', 3),
('iPad Air M2 11 inch 128GB', 'Chip Apple M2 8-core, màn hình Liquid Retina 11 inch. Hỗ trợ Apple Pencil Pro, Magic Keyboard, camera 12MP.', 18990000, 'ipad.png', 4),
('Samsung Galaxy Tab S9 FE', 'Chip Exynos 1380, màn hình TFT 10.9 inch 90Hz, bút S-Pen. RAM 6GB, bộ nhớ 128GB, pin 8000mAh, chống nước IP68.', 16990000, 'ipad.png', 4),
('Sony WH-1000XM5 Wireless', 'Tai nghe chụp tai cao cấp, khử ồn hàng đầu. Driver 30mm, LDAC Hi-Res, pin 30 giờ, kết nối multipoint.', 8490000, 'airpods.png', 5),
('Apple Watch Ultra 2 49mm', 'Chip S9 SiP, màn hình OLED 2000 nits, vỏ Titan, GPS 2 tần số. Chống nước 100m, pin 36 giờ, nút Action tùy biến.', 21990000, 'iphone15.png', 6);

-- Tài khoản mặc định. MD5 được giữ để tương thích khi import SQL; ứng dụng sẽ tự nâng cấp sang password_hash sau lần đăng nhập thành công.
INSERT INTO account (username, email, password, fullname, role, is_active, email_verified_at) VALUES
('admin', 'admin@novatech.local', MD5('123456'), 'Quản trị viên', 'admin', 1, NOW()),
('user1', 'user1@novatech.local', MD5('123456'), 'Nguyễn Văn A', 'user', 1, NOW());
