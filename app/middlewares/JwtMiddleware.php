<?php
/**
 * JwtMiddleware - middleware dùng chung để kiểm tra JWT và phân quyền Admin/User.
 * Dùng cho LAB 6: Authorization: Bearer <token>
 */
require_once BASE_PATH . '/app/helpers/JwtHelper.php';

class JwtMiddleware {
    public static function requireAuth($requiredRole = null) {
        $token = JwtHelper::getBearerToken();
        if (!$token) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - Token required'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        $payload = JwtHelper::decode($token);
        if (!$payload) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized - Invalid or expired token'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        if ($requiredRole !== null && ($payload['role'] ?? '') !== $requiredRole) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden - Tài khoản không đủ quyền'], JSON_UNESCAPED_UNICODE);
            exit;
        }

        return $payload;
    }
}
