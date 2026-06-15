-- Migration 006 FIX cho MySQL 8.0 / HeidiSQL / Laragon
-- Lý do: MySQL 8.0 không hỗ trợ cú pháp: ALTER TABLE ... ADD COLUMN IF NOT EXISTS
-- Chạy sau 004_create_reviews.sql và 005_create_wishlist.sql.

USE my_store;

DROP PROCEDURE IF EXISTS add_account_column_if_missing;
DROP PROCEDURE IF EXISTS add_account_index_if_missing;

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

CREATE PROCEDURE add_account_index_if_missing(
    IN p_table_name VARCHAR(64),
    IN p_index_name VARCHAR(64),
    IN p_create_index_sql TEXT
)
BEGIN
    IF NOT EXISTS (
        SELECT 1
        FROM INFORMATION_SCHEMA.STATISTICS
        WHERE TABLE_SCHEMA = DATABASE()
          AND TABLE_NAME = p_table_name
          AND INDEX_NAME = p_index_name
    ) THEN
        SET @sql = p_create_index_sql;
        PREPARE stmt FROM @sql;
        EXECUTE stmt;
        DEALLOCATE PREPARE stmt;
    END IF;
END$$

DELIMITER ;

CALL add_account_column_if_missing('account', 'email', '`email` VARCHAR(255) NULL AFTER `username`');
CALL add_account_column_if_missing('account', 'avatar', '`avatar` VARCHAR(255) NULL AFTER `fullname`');
CALL add_account_column_if_missing('account', 'is_active', '`is_active` TINYINT(1) NOT NULL DEFAULT 1 AFTER `role`');
CALL add_account_column_if_missing('account', 'email_verified_at', '`email_verified_at` DATETIME NULL AFTER `is_active`');
CALL add_account_column_if_missing('account', 'email_verification_token', '`email_verification_token` VARCHAR(64) NULL AFTER `email_verified_at`');
CALL add_account_column_if_missing('account', 'password_reset_token', '`password_reset_token` VARCHAR(64) NULL AFTER `email_verification_token`');
CALL add_account_column_if_missing('account', 'password_reset_expires', '`password_reset_expires` DATETIME NULL AFTER `password_reset_token`');
CALL add_account_column_if_missing('account', 'remember_token_hash', '`remember_token_hash` VARCHAR(255) NULL AFTER `password_reset_expires`');
CALL add_account_column_if_missing('account', 'last_login_at', '`last_login_at` DATETIME NULL AFTER `remember_token_hash`');
CALL add_account_column_if_missing('account', 'updated_at', '`updated_at` DATETIME NULL AFTER `created_at`');

CALL add_account_index_if_missing('account', 'idx_account_email', 'CREATE INDEX idx_account_email ON account (email)');
CALL add_account_index_if_missing('account', 'idx_account_reset_token', 'CREATE INDEX idx_account_reset_token ON account (password_reset_token)');
CALL add_account_index_if_missing('account', 'idx_account_verify_token', 'CREATE INDEX idx_account_verify_token ON account (email_verification_token)');

UPDATE account
SET is_active = 1
WHERE is_active IS NULL;

UPDATE account
SET email_verified_at = NOW()
WHERE email IS NOT NULL
  AND email <> ''
  AND email_verified_at IS NULL;

DROP PROCEDURE IF EXISTS add_account_column_if_missing;
DROP PROCEDURE IF EXISTS add_account_index_if_missing;
