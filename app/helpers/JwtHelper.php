<?php
/**
 * JwtHelper - Tự implement JWT cho học tập (Bài 6).
 * Không cần Composer/firebase-jwt.
 */
class JwtHelper {
    private static $secretKey = 'NovaTech_SecretKey_2024_COS340';
    private static $algorithm = 'HS256';

    /**
     * Tạo JWT token từ payload.
     */
    public static function encode($payload) {
        $header = json_encode(['typ' => 'JWT', 'alg' => self::$algorithm]);
        $payload['iat'] = time();
        $payload['exp'] = time() + ($payload['token_lifetime'] ?? 3600); // access token mặc định hết hạn sau 1 giờ
        unset($payload['token_lifetime']);
        $payload = json_encode($payload);

        $base64Header  = self::base64UrlEncode($header);
        $base64Payload = self::base64UrlEncode($payload);

        $signature = hash_hmac('sha256', $base64Header . '.' . $base64Payload, self::$secretKey, true);
        $base64Signature = self::base64UrlEncode($signature);

        return $base64Header . '.' . $base64Payload . '.' . $base64Signature;
    }


    /**
     * Tạo refresh token mô phỏng cho bài nâng cao.
     */
    public static function encodeRefreshToken($payload) {
        $payload['type'] = 'refresh';
        $payload['token_lifetime'] = 7 * 24 * 3600;
        return self::encode($payload);
    }

    /**
     * Tạo access token mới từ refresh token hợp lệ.
     */
    public static function refreshAccessToken($refreshToken) {
        $payload = self::decode($refreshToken);
        if (!$payload || ($payload['type'] ?? '') !== 'refresh') {
            return null;
        }
        unset($payload['iat'], $payload['exp'], $payload['type']);
        return self::encode($payload);
    }

    /**
     * Giải mã và validate JWT token.
     */
    public static function decode($token) {
        $parts = explode('.', $token);
        if (count($parts) !== 3) return null;

        list($base64Header, $base64Payload, $base64Signature) = $parts;

        // Verify signature
        $signature = self::base64UrlEncode(
            hash_hmac('sha256', $base64Header . '.' . $base64Payload, self::$secretKey, true)
        );

        if (!hash_equals($signature, $base64Signature)) return null;

        $payload = json_decode(self::base64UrlDecode($base64Payload), true);
        if (!$payload) return null;

        // Check expiration
        if (isset($payload['exp']) && $payload['exp'] < time()) return null;

        return $payload;
    }

    /**
     * Lấy Bearer token từ Authorization header.
     */
    public static function getBearerToken() {
        $headers = '';
        if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = $_SERVER['HTTP_AUTHORIZATION'];
        } elseif (isset($_SERVER['REDIRECT_HTTP_AUTHORIZATION'])) {
            $headers = $_SERVER['REDIRECT_HTTP_AUTHORIZATION'];
        } elseif (function_exists('apache_request_headers')) {
            $allHeaders = apache_request_headers();
            if (isset($allHeaders['Authorization'])) {
                $headers = $allHeaders['Authorization'];
            }
        }

        if (preg_match('/Bearer\s(\S+)/', $headers, $matches)) {
            return $matches[1];
        }
        return null;
    }

    /**
     * Lấy secret key.
     */
    public static function getSecretKey() {
        return self::$secretKey;
    }

    private static function base64UrlEncode($data) {
        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    private static function base64UrlDecode($data) {
        return base64_decode(strtr($data, '-_', '+/') . str_repeat('=', 3 - (3 + strlen($data)) % 4));
    }
}
