-- Migration 008: Khóa tài khoản khi đăng nhập sai nhiều lần (LAB 6 - nâng cao)
-- Tự chứa (idempotent), có thể chạy độc lập trên MySQL 8.0 / Laragon.

USE my_store;

DROP PROCEDURE IF EXISTS add_account_column_if_missing;

DELIMITER $$

CREATE PROCEDURE add_account_column_if_missing(
    IN p_table_name VARCHAR(64),
    IN p_column_name VARCHAR(64),
    IN p_column_definition TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.COLUMNS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table_name
          AND COLUMN_NAME = p_column_name
    ) THEN
        SET @sql = CONCAT('ALTER TABLE `', p_table_name, '` ADD COLUMN ', p_column_definition);
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

-- Số lần đăng nhập sai liên tiếp
CALL add_account_column_if_missing('account', 'failed_login_attempts', '`failed_login_attempts` INT NOT NULL DEFAULT 0 AFTER `last_login_at`');

-- Thời điểm tài khoản được mở khóa (NULL = không bị khóa)
CALL add_account_column_if_missing('account', 'locked_until', '`locked_until` DATETIME NULL AFTER `failed_login_attempts`');

DROP PROCEDURE IF EXISTS add_account_column_if_missing;
