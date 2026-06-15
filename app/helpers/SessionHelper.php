<?php
/**
 * SessionHelper - Quản lý session, Remember Me và kiểm tra quyền truy cập.
 */

function SessionHelper() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}

function isLoggedIn() {
    SessionHelper();
    return isset($_SESSION['username']);
}

function isAdmin() {
    SessionHelper();
    return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
}

function getCurrentUser() {
    SessionHelper();
    if (!isLoggedIn()) return null;
    return [
        'user_id'  => $_SESSION['user_id'] ?? null,
        'username' => $_SESSION['username'] ?? '',
        'fullname' => $_SESSION['fullname'] ?? '',
        'email'    => $_SESSION['email'] ?? '',
        'avatar'   => $_SESSION['avatar'] ?? '',
        'role'     => $_SESSION['role'] ?? 'user',
    ];
}

function setAuthSession(array $account) {
    SessionHelper();
    session_regenerate_id(true);
    $_SESSION['user_id']  = (int)($account['id'] ?? 0);
    $_SESSION['username'] = $account['username'] ?? '';
    $_SESSION['fullname'] = $account['fullname'] ?? '';
    $_SESSION['email']    = $account['email'] ?? '';
    $_SESSION['avatar']   = $account['avatar'] ?? '';
    $_SESSION['role']     = $account['role'] ?? 'user';
}

function clearAuthSession() {
    SessionHelper();
    unset($_SESSION['user_id'], $_SESSION['username'], $_SESSION['fullname'], $_SESSION['email'], $_SESSION['avatar'], $_SESSION['role']);
}

function getRememberCookieName() {
    return 'novatech_remember';
}

function setRememberCookie($userId, $plainToken, $days = 30) {
    $value = (int)$userId . ':' . $plainToken;
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie(getRememberCookieName(), $value, [
        'expires' => time() + ($days * 86400),
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function clearRememberCookie() {
    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
    setcookie(getRememberCookieName(), '', [
        'expires' => time() - 3600,
        'path' => '/',
        'secure' => $secure,
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    unset($_COOKIE[getRememberCookieName()]);
}

function attemptRememberMeLogin() {
    SessionHelper();
    if (isLoggedIn() || empty($_COOKIE[getRememberCookieName()])) return;
    if (!defined('BASE_PATH')) return;

    $parts = explode(':', (string)$_COOKIE[getRememberCookieName()], 2);
    if (count($parts) !== 2 || !ctype_digit($parts[0]) || $parts[1] === '') {
        clearRememberCookie();
        return;
    }

    require_once BASE_PATH . '/app/models/AccountModel.php';
    try {
        $model = new AccountModel();
        $account = $model->validateRememberToken((int)$parts[0], $parts[1]);
        if ($account) {
            setAuthSession($account);
            $model->markLastLogin((int)$account['id']);
        } else {
            clearRememberCookie();
        }
    } catch (Exception $e) {
        clearRememberCookie();
    }
}

function isAdminLoggedIn() {
    return isAdmin();
}

function requireLogin($redirectUrl = 'index.php?url=account/login') {
    SessionHelper();
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục.';
        redirect($redirectUrl);
    }
}

function requireAdmin() {
    SessionHelper();
    if (!isLoggedIn()) {
        $_SESSION['error'] = 'Vui lòng đăng nhập bằng tài khoản quản trị.';
        redirect('index.php?url=account/login');
    }
    if (!isAdminLoggedIn()) {
        $_SESSION['error'] = 'Bạn không có quyền truy cập khu vực quản trị.';
        redirect('index.php?url=product');
    }
}
