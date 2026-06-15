<?php
/**
 * AccountModel - Data access cho bảng account.
 * Đã nâng cấp: password_hash, remember me, reset password, xác thực email,
 * avatar và khóa/mở khóa tài khoản.
 */
class AccountModel {
    private $conn;

    public function __construct() {
        $this->conn = Database::getInstance()->getConnection();
        $this->ensureTable();
    }

    /**
     * Tự tạo/nâng cấp bảng account nếu chưa tồn tại hoặc còn thiếu cột.
     */
    private function ensureTable() {
        try {
            $this->conn->query("SELECT 1 FROM account LIMIT 1");
        } catch (\PDOException $e) {
            $this->conn->exec("CREATE TABLE IF NOT EXISTS account (
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
        }

        $this->ensureColumns();
        $this->ensureDefaultAccounts();
    }

    private function columnExists($column) {
        try {
            // SQLite compatible
            $driver = $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $stmt = $this->conn->query("PRAGMA table_info(account)");
                $cols = $stmt->fetchAll(PDO::FETCH_ASSOC);
                foreach ($cols as $col) {
                    if ($col['name'] === $column) return true;
                }
                return false;
            }
            $stmt = $this->conn->prepare("SHOW COLUMNS FROM account LIKE :column");
            $stmt->execute([':column' => $column]);
            return (bool)$stmt->fetch(PDO::FETCH_ASSOC);
        } catch (Exception $e) { return true; }
    }

    private function addColumnIfMissing($column, $definition) {
        if (!$this->columnExists($column)) {
            // SQLite không hỗ trợ AFTER keyword
            $driver = $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') {
                $definition = preg_replace('/\s+AFTER\s+\S+/i', '', $definition);
            }
            $this->conn->exec("ALTER TABLE account ADD COLUMN {$column} {$definition}");
        }
    }

    private function ensureIndex($indexName, $sql) {
        try {
            $driver = $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            if ($driver === 'sqlite') return; // SQLite không cần index như MySQL
            $stmt = $this->conn->prepare("SHOW INDEX FROM account WHERE Key_name = :name");
            $stmt->execute([':name' => $indexName]);
            if (!$stmt->fetch(PDO::FETCH_ASSOC)) {
                $this->conn->exec($sql);
            }
        } catch (Exception $e) {
            // Không làm ứng dụng dừng nếu tài khoản DB không có quyền tạo index.
        }
    }

    private function ensureColumns() {
        $this->addColumnIfMissing('email', "VARCHAR(255) NULL AFTER username");
        $this->addColumnIfMissing('avatar', "VARCHAR(255) NULL AFTER fullname");
        $this->addColumnIfMissing('is_active', "TINYINT(1) NOT NULL DEFAULT 1 AFTER role");
        $this->addColumnIfMissing('email_verified_at', "DATETIME NULL AFTER is_active");
        $this->addColumnIfMissing('email_verification_token', "VARCHAR(64) NULL AFTER email_verified_at");
        $this->addColumnIfMissing('password_reset_token', "VARCHAR(64) NULL AFTER email_verification_token");
        $this->addColumnIfMissing('password_reset_expires', "DATETIME NULL AFTER password_reset_token");
        $this->addColumnIfMissing('remember_token_hash', "VARCHAR(255) NULL AFTER password_reset_expires");
        $this->addColumnIfMissing('last_login_at', "DATETIME NULL AFTER remember_token_hash");
        $this->addColumnIfMissing('updated_at', "DATETIME NULL AFTER created_at");
        $this->addColumnIfMissing('failed_login_attempts', "INT NOT NULL DEFAULT 0 AFTER last_login_at");
        $this->addColumnIfMissing('locked_until', "DATETIME NULL AFTER failed_login_attempts");

        $this->ensureIndex('idx_account_email', "ALTER TABLE account ADD INDEX idx_account_email (email)");
        $this->ensureIndex('idx_account_reset_token', "ALTER TABLE account ADD INDEX idx_account_reset_token (password_reset_token)");
        $this->ensureIndex('idx_account_verify_token', "ALTER TABLE account ADD INDEX idx_account_verify_token (email_verification_token)");
    }

    private function ensureDefaultAccounts() {
        if (!$this->getAccountByUsername('admin')) {
            $stmt = $this->conn->prepare("INSERT INTO account (username, email, password, fullname, role, is_active, email_verified_at) VALUES (:username, :email, :password, :fullname, :role, 1, CURRENT_TIMESTAMP)");
            $stmt->execute([
                ':username' => 'admin',
                ':email' => 'admin@novatech.local',
                ':password' => $this->hashPassword('123456'),
                ':fullname' => 'Quản trị viên',
                ':role' => 'admin',
            ]);
        }
        if (!$this->getAccountByUsername('user1')) {
            $stmt = $this->conn->prepare("INSERT INTO account (username, email, password, fullname, role, is_active, email_verified_at) VALUES (:username, :email, :password, :fullname, :role, 1, CURRENT_TIMESTAMP)");
            $stmt->execute([
                ':username' => 'user1',
                ':email' => 'user1@novatech.local',
                ':password' => $this->hashPassword('123456'),
                ':fullname' => 'Nguyễn Văn A',
                ':role' => 'user',
            ]);
        }

        $this->conn->exec("UPDATE account SET email = 'admin@novatech.local', email_verified_at = COALESCE(email_verified_at, CURRENT_TIMESTAMP) WHERE username = 'admin' AND (email IS NULL OR email = '')");
        $this->conn->exec("UPDATE account SET email = 'user1@novatech.local', email_verified_at = COALESCE(email_verified_at, CURRENT_TIMESTAMP) WHERE username = 'user1' AND (email IS NULL OR email = '')");
    }

    public function hashPassword($password) {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function verifyPassword($password, $storedHash) {
        if (!$storedHash) return false;
        $info = password_get_info($storedHash);
        if (($info['algo'] ?? 0) !== 0) {
            return password_verify($password, $storedHash);
        }
        // Tương thích dữ liệu cũ đang dùng MD5 trong bài ban đầu.
        return hash_equals((string)$storedHash, md5($password));
    }

    public function passwordNeedsRehash($storedHash) {
        $info = password_get_info((string)$storedHash);
        return (($info['algo'] ?? 0) === 0) || password_needs_rehash((string)$storedHash, PASSWORD_DEFAULT);
    }

    public function upgradePasswordHash($id, $plainPassword) {
        $stmt = $this->conn->prepare("UPDATE account SET password = :password, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':password' => $this->hashPassword($plainPassword), ':id' => (int)$id]);
    }

    public function getAccountByUsername($username) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE username = :username LIMIT 1");
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getAccountByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE email = :email LIMIT 1");
        $stmt->execute([':email' => $email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function findByUsernameOrEmail($identifier) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE username = :identifier OR email = :identifier LIMIT 1");
        $stmt->execute([':identifier' => $identifier]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function isEmailAvailable($email, $ignoreId = null) {
        $email = trim((string)$email);
        if ($email === '') return true;
        $sql = "SELECT id FROM account WHERE email = :email";
        $params = [':email' => $email];
        if ($ignoreId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = (int)$ignoreId;
        }
        $sql .= " LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return !$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function register($username, $fullname, $email, $password, $role = 'user') {
        $existing = $this->getAccountByUsername($username);
        if ($existing || !$this->isEmailAvailable($email)) {
            return false;
        }
        $token = bin2hex(random_bytes(32));
        $stmt = $this->conn->prepare(
            "INSERT INTO account (username, email, password, fullname, role, is_active, email_verification_token, email_verified_at)
             VALUES (:username, :email, :password, :fullname, :role, 1, :token, NULL)"
        );
        $ok = $stmt->execute([
            ':username' => $username,
            ':email'    => $email,
            ':password' => $this->hashPassword($password),
            ':fullname' => $fullname,
            ':role'     => $role,
            ':token'    => $token,
        ]);
        if (!$ok) return false;
        $account = $this->getAccountByUsername($username);
        return $account ?: false;
    }

    public function getAllAccounts() {
        $stmt = $this->conn->prepare("SELECT id, username, email, fullname, avatar, role, is_active, email_verified_at, last_login_at, created_at FROM account ORDER BY id DESC");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function deleteAccount($id) {
        $stmt = $this->conn->prepare("DELETE FROM account WHERE id = :id AND role != 'admin'");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function getAccountById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => (int)$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $fullname, $email = null, $emailChanged = false) {
        $params = [':fullname' => $fullname, ':id' => (int)$id];
        if ($emailChanged) {
            $token = bin2hex(random_bytes(32));
            $stmt = $this->conn->prepare("UPDATE account SET fullname = :fullname, email = :email, email_verified_at = NULL, email_verification_token = :token, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
            $params[':email'] = $email;
            $params[':token'] = $token;
            $ok = $stmt->execute($params);
            return $ok ? $token : false;
        }
        $stmt = $this->conn->prepare("UPDATE account SET fullname = :fullname, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute($params);
    }

    public function updateAvatar($id, $avatar) {
        $stmt = $this->conn->prepare("UPDATE account SET avatar = :avatar, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':avatar' => $avatar, ':id' => (int)$id]);
    }

    public function changePassword($id, $newPassword) {
        $stmt = $this->conn->prepare("UPDATE account SET password = :password, password_reset_token = NULL, password_reset_expires = NULL, remember_token_hash = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':password' => $this->hashPassword($newPassword), ':id' => (int)$id]);
    }

    public function setRememberToken($id, $plainToken) {
        $hash = hash('sha256', $plainToken);
        $stmt = $this->conn->prepare("UPDATE account SET remember_token_hash = :hash, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':hash' => $hash, ':id' => (int)$id]);
    }

    public function clearRememberToken($id) {
        $stmt = $this->conn->prepare("UPDATE account SET remember_token_hash = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function validateRememberToken($id, $plainToken) {
        $account = $this->getAccountById($id);
        if (!$account || empty($account['remember_token_hash'])) return false;
        if ((int)($account['is_active'] ?? 1) !== 1) return false;
        $hash = hash('sha256', $plainToken);
        return hash_equals((string)$account['remember_token_hash'], $hash) ? $account : false;
    }

    public function markLastLogin($id) {
        $stmt = $this->conn->prepare("UPDATE account SET last_login_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function createPasswordResetToken($id, $minutes = 30) {
        $token = bin2hex(random_bytes(32));
        $minutes = max(5, min(1440, (int)$minutes));
        // Dùng giờ của MySQL cho cả lúc tạo và lúc kiểm tra token để tránh lệch timezone PHP/MySQL làm link vừa tạo đã hết hạn.
        $stmt = $this->conn->prepare("UPDATE account SET password_reset_token = :token, password_reset_expires = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL {$minutes} MINUTE), updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':token' => $token, ':id' => (int)$id]) ? $token : false;
    }

    public function getByValidResetToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE password_reset_token = :token AND password_reset_expires >= CURRENT_TIMESTAMP LIMIT 1");
        $stmt->execute([':token' => $token]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function clearPasswordResetToken($id) {
        $stmt = $this->conn->prepare("UPDATE account SET password_reset_token = NULL, password_reset_expires = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function verifyEmailByToken($token) {
        $stmt = $this->conn->prepare("SELECT * FROM account WHERE email_verification_token = :token LIMIT 1");
        $stmt->execute([':token' => $token]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$account) return false;
        $up = $this->conn->prepare("UPDATE account SET email_verified_at = CURRENT_TIMESTAMP, email_verification_token = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        $up->execute([':id' => (int)$account['id']]);
        return $this->getAccountById($account['id']);
    }

    public function createEmailVerificationToken($id) {
        $token = bin2hex(random_bytes(32));
        $stmt = $this->conn->prepare("UPDATE account SET email_verification_token = :token, email_verified_at = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id");
        return $stmt->execute([':token' => $token, ':id' => (int)$id]) ? $token : false;
    }

    public function setActiveStatus($id, $isActive) {
        $stmt = $this->conn->prepare("UPDATE account SET is_active = :active, remember_token_hash = NULL, updated_at = CURRENT_TIMESTAMP WHERE id = :id AND role != 'admin'");
        return $stmt->execute([':active' => $isActive ? 1 : 0, ':id' => (int)$id]);
    }

    // ─── Khóa tài khoản khi đăng nhập sai nhiều lần (LAB 6 - nâng cao) ───

    const MAX_LOGIN_ATTEMPTS = 5;
    const LOCK_MINUTES = 15;

    /**
     * Kiểm tra tài khoản đang bị khóa tạm thời do đăng nhập sai nhiều lần hay không.
     * Trả về số giây còn lại nếu đang khóa, hoặc 0 nếu không bị khóa.
     */
    public function getLockRemainingSeconds($account) {
        if (empty($account['locked_until'])) return 0;
        $lockedUntil = strtotime($account['locked_until']);
        $remaining = $lockedUntil - time();
        return $remaining > 0 ? $remaining : 0;
    }

    /**
     * Tăng số lần đăng nhập sai. Nếu đạt ngưỡng, khóa tài khoản trong LOCK_MINUTES phút
     * và reset bộ đếm về 0.
     */
    public function incrementFailedLoginAttempts($id) {
        $attempts = $this->conn->prepare("UPDATE account SET failed_login_attempts = failed_login_attempts + 1 WHERE id = :id");
        $attempts->execute([':id' => (int)$id]);

        $account = $this->getAccountById($id);
        $current = (int)($account['failed_login_attempts'] ?? 0);

        if ($current >= self::MAX_LOGIN_ATTEMPTS) {
            $lock = $this->conn->prepare("UPDATE account SET locked_until = DATE_ADD(CURRENT_TIMESTAMP, INTERVAL :minutes MINUTE), failed_login_attempts = 0 WHERE id = :id");
            $lock->bindValue(':minutes', self::LOCK_MINUTES, PDO::PARAM_INT);
            $lock->bindValue(':id', (int)$id, PDO::PARAM_INT);
            $lock->execute();
            return true; // tài khoản vừa bị khóa
        }
        return false;
    }

    /**
     * Reset bộ đếm đăng nhập sai và mở khóa (sau khi đăng nhập thành công).
     */
    public function resetFailedLoginAttempts($id) {
        $stmt = $this->conn->prepare("UPDATE account SET failed_login_attempts = 0, locked_until = NULL WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }
}
