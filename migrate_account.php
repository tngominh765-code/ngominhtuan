<?php
/**
 * Migration script - Tạo/nâng cấp bảng account và dữ liệu mẫu.
 * Chạy: php migrate_account.php
 */
try {
    $pdo = new PDO('mysql:host=localhost;dbname=my_store;charset=utf8mb4', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $pdo->exec("CREATE TABLE IF NOT EXISTS account (
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
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        updated_at DATETIME NULL,
        INDEX idx_account_email (email),
        INDEX idx_account_reset_token (password_reset_token),
        INDEX idx_account_verify_token (email_verification_token)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "OK: account table created/exists\n";

    $columns = [
        'email' => "VARCHAR(255) NULL AFTER username",
        'avatar' => "VARCHAR(255) NULL AFTER fullname",
        'is_active' => "TINYINT(1) NOT NULL DEFAULT 1 AFTER role",
        'email_verified_at' => "DATETIME NULL AFTER is_active",
        'email_verification_token' => "VARCHAR(64) NULL AFTER email_verified_at",
        'password_reset_token' => "VARCHAR(64) NULL AFTER email_verification_token",
        'password_reset_expires' => "DATETIME NULL AFTER password_reset_token",
        'remember_token_hash' => "VARCHAR(255) NULL AFTER password_reset_expires",
        'last_login_at' => "DATETIME NULL AFTER remember_token_hash",
        'updated_at' => "DATETIME NULL AFTER created_at",
    ];
    foreach ($columns as $name => $definition) {
        $stmt = $pdo->prepare("SHOW COLUMNS FROM account LIKE ?");
        $stmt->execute([$name]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec("ALTER TABLE account ADD COLUMN {$name} {$definition}");
            echo "OK: added column {$name}\n";
        }
    }

    foreach ([
        'idx_account_email' => 'ALTER TABLE account ADD INDEX idx_account_email (email)',
        'idx_account_reset_token' => 'ALTER TABLE account ADD INDEX idx_account_reset_token (password_reset_token)',
        'idx_account_verify_token' => 'ALTER TABLE account ADD INDEX idx_account_verify_token (email_verification_token)',
    ] as $index => $sql) {
        $stmt = $pdo->prepare("SHOW INDEX FROM account WHERE Key_name = ?");
        $stmt->execute([$index]);
        if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
            $pdo->exec($sql);
            echo "OK: added index {$index}\n";
        }
    }

    $stmt = $pdo->query("SELECT COUNT(*) as c FROM account WHERE username = 'admin'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row['c'] == 0) {
        $hash = password_hash('123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO account (username, email, password, fullname, role, is_active, email_verified_at) VALUES (?, ?, ?, ?, ?, 1, NOW())");
        $stmt->execute(['admin', 'admin@novatech.local', $hash, 'Quản trị viên', 'admin']);
        $stmt->execute(['user1', 'user1@novatech.local', $hash, 'Nguyễn Văn A', 'user']);
        echo "OK: Default accounts inserted (admin/123456, user1/123456)\n";
    } else {
        echo "INFO: Admin account already exists\n";
    }

    $pdo->exec("UPDATE account SET is_active = 1 WHERE is_active IS NULL");
    $pdo->exec("UPDATE account SET email_verified_at = NOW() WHERE (email IS NOT NULL AND email <> '') AND email_verified_at IS NULL");

    $pdo->exec("CREATE TABLE IF NOT EXISTS user_cart_items (
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
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    echo "OK: user_cart_items table created/exists
";

    $accounts = $pdo->query("SELECT id, username, email, fullname, role, is_active, email_verified_at FROM account ORDER BY id")->fetchAll(PDO::FETCH_ASSOC);
    echo "\nAccounts:\n";
    foreach ($accounts as $a) {
        $status = $a['is_active'] ? 'active' : 'locked';
        $verified = $a['email_verified_at'] ? 'verified' : 'unverified';
        echo "  #{$a['id']} {$a['username']} <{$a['email']}> ({$a['fullname']}) - {$a['role']} - {$status} - {$verified}\n";
    }
} catch (PDOException $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
}
