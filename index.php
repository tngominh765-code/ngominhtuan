<?php
/**
 * Front controller - NovaTech Future Store.
 * Output buffering + UTF-8 header fix redirect/header issues on localhost.
 */

ob_start();
session_start();
header('Content-Type: text/html; charset=UTF-8');

define('BASE_PATH', __DIR__);
define('BASE_URL', 'index.php');

require_once BASE_PATH . '/app/config/database.php';
require_once BASE_PATH . '/app/helpers/SessionHelper.php';
attemptRememberMeLogin();
if (file_exists(BASE_PATH . '/app/models/CartModel.php')) require_once BASE_PATH . '/app/models/CartModel.php';
if (file_exists(BASE_PATH . '/app/models/WishlistModel.php')) require_once BASE_PATH . '/app/models/WishlistModel.php';

/**
 * Đồng bộ giỏ hàng DB -> session cho user đã đăng nhập.
 * Nếu session đang có giỏ tạm, tự merge vào giỏ DB để không mất dữ liệu.
 */
function syncPersistentCartForLoggedInUser() {
    if (!function_exists('isLoggedIn') || !isLoggedIn() || !class_exists('CartModel')) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        return;
    }

    $userId = (int)($_SESSION['user_id'] ?? 0);
    if ($userId <= 0) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
        return;
    }

    $loadedFor = (int)($_SESSION['cart_loaded_for_user'] ?? 0);
    if ($loadedFor === $userId && isset($_SESSION['cart'])) {
        return;
    }

    try {
        $cartModel = new CartModel();
        $sessionCart = $_SESSION['cart'] ?? [];
        if (!empty($sessionCart)) {
            $_SESSION['cart'] = $cartModel->mergeSessionCart($userId, $sessionCart);
        } else {
            $_SESSION['cart'] = $cartModel->getCartForUser($userId);
        }
        $_SESSION['cart_loaded_for_user'] = $userId;
    } catch (Exception $e) {
        if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    }
}

syncPersistentCartForLoggedInUser();

function e($value) {
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function moneyVnd($amount) {
    return number_format((float) $amount, 0, ',', '.') . ' đ';
}

function textLengthUtf8($text) {
    $text = (string) $text;
    return function_exists('mb_strlen') ? mb_strlen($text, 'UTF-8') : strlen($text);
}

function textExcerpt($text, $limit = 160) {
    $text = trim(strip_tags((string) $text));
    if ($text === '') {
        return '';
    }
    $len = textLengthUtf8($text);
    if ($len <= $limit) {
        return $text;
    }
    $slice = function_exists('mb_substr') ? mb_substr($text, 0, $limit, 'UTF-8') : substr($text, 0, $limit);
    return rtrim($slice) . '...';
}

// ─── CSRF Protection ───
function generateCsrfToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCsrfToken() {
    $token = $_POST['csrf_token'] ?? '';
    return $token !== '' && hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function csrfField() {
    return '<input type="hidden" name="csrf_token" value="' . e(generateCsrfToken()) . '">';
}

function csrf_input() { return csrfField(); }
function verify_csrf() { return validateCsrfToken(); }

// Tạo CSRF token sẵn cho mỗi request
generateCsrfToken();

function redirect($url, $statusCode = 302) {
    $url = trim((string) $url);
    if ($url === '' || preg_match('/[\r\n]/', $url)) {
        $url = 'index.php?url=dashboard';
    }

    if (!headers_sent()) {
        header('Location: ' . $url, true, $statusCode);
    } else {
        echo '<script>window.location.href=' . json_encode($url) . ';</script>';
        echo '<noscript><meta http-equiv="refresh" content="0;url=' . e($url) . '"></noscript>';
    }
    exit;
}

function getCartCount() {
    $count = 0;
    if (!empty($_SESSION['cart'])) {
        foreach ($_SESSION['cart'] as $item) {
            $count += (int) ($item['quantity'] ?? 0);
        }
    }
    return $count;
}


function getWishlistCount() {
    if (class_exists('WishlistModel')) {
        try { return (new WishlistModel())->count(session_id()); } catch (Exception $e) {}
    }
    return isset($_SESSION['wishlist']) ? count($_SESSION['wishlist']) : 0;
}

function breadcrumb(array $items) {
    $html = '<nav class="breadcrumb-nav" aria-label="breadcrumb">';
    $html .= '<a href="index.php?url=dashboard"><i class="fas fa-home"></i></a>';
    foreach ($items as $item) {
        $html .= '<span class="sep">/</span>';
        if (isset($item['url'])) {
            $html .= '<a href="' . e($item['url']) . '">' . e($item['label']) . '</a>';
        } else {
            $html .= '<span class="current">' . e($item['label']) . '</span>';
        }
    }
    $html .= '</nav>';
    return $html;
}

function renderStars($rating) {
    $rating = max(0, min(5, (float)$rating));
    $html = '<span class="rating-stars" aria-label="' . e(number_format($rating, 1)) . '/5">';
    for ($i = 1; $i <= 5; $i++) {
        if ($rating >= $i) $html .= '<i class="fas fa-star"></i>';
        elseif ($rating >= $i - 0.5) $html .= '<i class="fas fa-star-half-alt"></i>';
        else $html .= '<i class="far fa-star"></i>';
    }
    return $html . '</span>';
}

function getProductTag($product) {
    $stock = (int)($product['stock'] ?? 8);
    $price = (float)($product['price'] ?? 0);
    $createdAt = $product['created_at'] ?? null;
    if ($stock <= 0) return ['label'=>'HẾT HÀNG','color'=>'#fff','bg'=>'rgba(255,59,107,.88)'];
    if ($stock <= 3) return ['label'=>'SẮP HẾT','color'=>'#1a1200','bg'=>'rgba(251,191,36,.92)'];
    if ($price > 30000000) return ['label'=>'LUXURY','color'=>'#fff','bg'=>'linear-gradient(135deg,#7c3cff,#22d3ee)'];
    // Sản phẩm tạo trong 7 ngày gần đây → NEW
    if ($createdAt && strtotime($createdAt) >= strtotime('-7 days')) return ['label'=>'NEW','color'=>'#03131d','bg'=>'linear-gradient(135deg,#00f5c8,#22d3ee)'];
    // Sản phẩm giá cao → HOT
    if ($price > 15000000) return ['label'=>'HOT','color'=>'#fff','bg'=>'linear-gradient(135deg,#ff3b6b,#ff8a00)'];
    return null;
}

function getCartTotals($cart = null) {
    $cart = $cart ?? ($_SESSION['cart'] ?? []);
    $subtotal = 0;
    foreach ($cart as $item) {
        $subtotal += ((float) ($item['price'] ?? 0)) * ((int) ($item['quantity'] ?? 0));
    }

    $voucher = $_SESSION['voucher'] ?? null;
    $discount = 0;
    $shipping = $subtotal > 0 ? 0 : 0;
    $label = '';

    if ($voucher && $subtotal > 0) {
        $code = strtoupper(trim($voucher['code'] ?? ''));
        if ($code === 'FUTURE10') {
            $discount = min($subtotal * 0.10, 2000000);
            $label = 'FUTURE10 - giảm 10% tối đa 2.000.000đ';
        } elseif ($code === 'VIP15') {
            $discount = min($subtotal * 0.15, 3000000);
            $label = 'VIP15 - giảm 15% tối đa 3.000.000đ';
        } elseif ($code === 'TECH500') {
            $discount = min(500000, $subtotal);
            $label = 'TECH500 - giảm 500.000đ';
        }
    }

    return [
        'subtotal' => $subtotal,
        'discount' => $discount,
        'shipping' => $shipping,
        'total' => max(0, $subtotal - $discount + $shipping),
        'voucher_label' => $label,
    ];
}

/**
 * Resolve product image (url, uploads file, keyword/category fallback, placeholder).
 */
function assetUrl($path) {
    return ltrim((string) $path, '/');
}

/**
 * Resolve product image safely (external url, uploads file, category/keyword fallback, placeholder).
 */
function getImageSrc($image, $nameHint = '', $categoryId = null) {
    $placeholder = 'uploads/product-placeholder.svg';

    if (!empty($image)) {
        $image = trim((string) $image);
        if (preg_match('#^https?://#i', $image)) {
            return $image;
        }

        $image = basename(str_replace('\\', '/', $image));
        if ($image !== '' && file_exists(BASE_PATH . '/uploads/' . $image)) {
            return 'uploads/' . rawurlencode($image);
        }
    }

    $fallbackByCategory = [
        1 => 'iphone15.png',
        2 => 'macbook.png',
        3 => 'airpods.png',
        4 => 'ipad.png',
        5 => 'airpods.png',
        6 => 'iphone15.png',
    ];

    if ($categoryId !== null) {
        $categoryId = (int) $categoryId;
        if (isset($fallbackByCategory[$categoryId])) {
            $candidate = $fallbackByCategory[$categoryId];
            if (file_exists(BASE_PATH . '/uploads/' . $candidate)) {
                return 'uploads/' . $candidate;
            }
        }
    }

    $name = function_exists('mb_strtolower') ? mb_strtolower((string) $nameHint, 'UTF-8') : strtolower((string) $nameHint);
    $fallbackByKeyword = [
        'iphone' => 'iphone15.png',
        'galaxy' => 'samsung.png',
        'samsung' => 'samsung.png',
        'xiaomi' => 'samsung.png',
        'macbook' => 'macbook.png',
        'dell' => 'dell.png',
        'asus' => 'dell.png',
        'rog' => 'dell.png',
        'ipad' => 'ipad.png',
        'tab' => 'ipad.png',
        'airpods' => 'airpods.png',
        'magsafe' => 'airpods.png',
        'sony' => 'airpods.png',
        'watch' => 'iphone15.png',
    ];

    foreach ($fallbackByKeyword as $keyword => $candidate) {
        if ($name !== '' && strpos($name, $keyword) !== false && file_exists(BASE_PATH . '/uploads/' . $candidate)) {
            return 'uploads/' . $candidate;
        }
    }

    return file_exists(BASE_PATH . '/' . $placeholder) ? $placeholder : '';
}

// ─── URL Routing ───
$rawUrl = isset($_GET['url']) ? (string) $_GET['url'] : 'dashboard';
$rawUrl = trim($rawUrl, "/ ");
$rawUrl = $rawUrl === '' ? 'dashboard' : $rawUrl;
$rawUrl = preg_replace('/[^a-zA-Z0-9_\-\/]/', '', $rawUrl);
$url = explode('/', $rawUrl);

$controllerName = ucfirst(strtolower($url[0])) . 'Controller';
$action = isset($url[1]) ? $url[1] : 'index';
$param = isset($url[2]) ? $url[2] : null;

/**
 * Chuẩn hóa tên action để link dạng camelCase, lowercase, kebab-case,
 * snake_case đều chạy được. Ví dụ:
 * - account/resetPassword
 * - account/resetpassword
 * - account/reset-password
 * - account/reset_password
 */
function normalizeRouteAction($action) {
    $action = (string)$action;
    if ($action === '') return 'index';
    if (strpos($action, '-') === false && strpos($action, '_') === false) {
        return $action;
    }
    $parts = preg_split('/[-_]+/', strtolower($action));
    $first = array_shift($parts);
    foreach ($parts as $part) {
        $first .= ucfirst($part);
    }
    return $first;
}

// ─── API Routing (Bài 5) ───
// URL pattern: api/product, api/category, api/order
if (strtolower($url[0]) === 'api' && isset($url[1])) {
    $apiResource = ucfirst(strtolower($url[1]));
    $controllerName = 'Api' . strtolower($url[1]) . 'Controller';
    $action = 'index';
    $param = isset($url[2]) ? $url[2] : null;
}

$controllerFile = BASE_PATH . '/app/controllers/' . $controllerName . '.php';

try {
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();

        $resolvedAction = $action;
        if (!method_exists($controller, $resolvedAction)) {
            $normalizedAction = normalizeRouteAction($action);
            if (method_exists($controller, $normalizedAction)) {
                $resolvedAction = $normalizedAction;
            }
        }

        if (method_exists($controller, $resolvedAction)) {
            if ($param !== null) {
                $controller->$resolvedAction($param);
            } else {
                $controller->$resolvedAction();
            }
        } else {
            http_response_code(404);
            include BASE_PATH . '/app/views/errors/404.php';
        }
    } else {
        http_response_code(404);
        include BASE_PATH . '/app/views/errors/404.php';
    }
} catch (Throwable $e) {
    http_response_code(500);
    error_log($e->getMessage());
    include BASE_PATH . '/app/views/errors/500.php';
}
