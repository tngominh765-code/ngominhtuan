<?php
/**
 * ApiaccountController - RESTful API cho tài khoản (LAB 5 & 6).
 * Endpoints: checkLogin, register, me, profile, changePassword, forgotPassword.
 */
require_once BASE_PATH . '/app/models/AccountModel.php';
require_once BASE_PATH . '/app/helpers/JwtHelper.php';
require_once BASE_PATH . '/app/middlewares/JwtMiddleware.php';

class ApiaccountController {
    private $accountModel;

    public function __construct() {
        $this->accountModel = new AccountModel();
        header('Content-Type: application/json; charset=UTF-8');
    }

    public function index($action = null) {
        $routes = [
            'checkLogin'      => 'POST',
            'register'        => 'POST',
            'me'              => 'GET',
            'profile'         => 'PUT',
            'changePassword'  => 'PUT',
            'forgotPassword'  => 'POST',
            'refreshToken'     => 'POST',
        ];

        if (!$action || !array_key_exists($action, $routes)) {
            http_response_code(404);
            echo json_encode(['error' => 'API endpoint not found']);
            exit;
        }

        $expected = $routes[$action];
        if ($_SERVER['REQUEST_METHOD'] !== $expected) {
            http_response_code(405);
            echo json_encode(['error' => "Method not allowed. Use $expected"]);
            exit;
        }

        $this->$action();
    }

    // POST /api/account/checkLogin
    private function checkLogin() {
        $data     = json_decode(file_get_contents('php://input'), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if (!$username || !$password) {
            http_response_code(400);
            echo json_encode(['error' => 'Username/email và password là bắt buộc']);
            exit;
        }

        $account = $this->accountModel->findByUsernameOrEmail($username);

        // Tài khoản đang bị khóa tạm thời do đăng nhập sai quá nhiều lần
        if ($account) {
            $remaining = $this->accountModel->getLockRemainingSeconds($account);
            if ($remaining > 0) {
                http_response_code(423);
                echo json_encode([
                    'error' => 'Tài khoản đang bị khóa tạm thời do đăng nhập sai quá nhiều lần. Vui lòng thử lại sau ' . ceil($remaining / 60) . ' phút.',
                    'locked_seconds_remaining' => $remaining,
                ], JSON_UNESCAPED_UNICODE);
                exit;
            }
        }

        if (!$account || !$this->accountModel->verifyPassword($password, $account['password'])) {
            if ($account) {
                $justLocked = $this->accountModel->incrementFailedLoginAttempts($account['id']);
                if ($justLocked) {
                    http_response_code(423);
                    echo json_encode([
                        'error' => 'Bạn đã đăng nhập sai quá ' . AccountModel::MAX_LOGIN_ATTEMPTS . ' lần. Tài khoản bị khóa tạm thời trong ' . AccountModel::LOCK_MINUTES . ' phút.',
                    ], JSON_UNESCAPED_UNICODE);
                    exit;
                }
            }
            http_response_code(401);
            echo json_encode(['error' => 'Sai tài khoản hoặc mật khẩu']);
            exit;
        }
        if ((int)($account['is_active'] ?? 1) !== 1) {
            http_response_code(423);
            echo json_encode(['error' => 'Tài khoản đang bị khóa']);
            exit;
        }

        // Đăng nhập đúng -> reset bộ đếm đăng nhập sai
        $this->accountModel->resetFailedLoginAttempts($account['id']);

        if (method_exists($this->accountModel, 'upgradePasswordHash') && method_exists($this->accountModel, 'passwordNeedsRehash') && $this->accountModel->passwordNeedsRehash($account['password'])) {
            $this->accountModel->upgradePasswordHash($account['id'], $password);
        }
        $this->accountModel->markLastLogin($account['id']);

        $token = JwtHelper::encode([
            'id'       => $account['id'],
            'username' => $account['username'],
            'fullname' => $account['fullname'],
            'email'    => $account['email'] ?? '',
            'avatar'   => $account['avatar'] ?? '',
            'role'     => $account['role'],
        ]);
        $refreshToken = JwtHelper::encodeRefreshToken([
            'id'       => $account['id'],
            'username' => $account['username'],
            'fullname' => $account['fullname'],
            'email'    => $account['email'] ?? '',
            'avatar'   => $account['avatar'] ?? '',
            'role'     => $account['role'],
        ]);
        echo json_encode(['token' => $token, 'refresh_token' => $refreshToken, 'role' => $account['role']], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // POST /api/account/register
    private function register() {
        $data     = json_decode(file_get_contents('php://input'), true);
        $username = trim($data['username'] ?? '');
        $fullname = trim($data['fullname'] ?? '');
        $email    = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        $errors = [];
        if (!$username) $errors['username'] = 'Username không được để trống';
        if (!$fullname) $errors['fullname'] = 'Họ tên không được để trống';
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Email không hợp lệ';
        if (strlen($password) < 6) $errors['password'] = 'Mật khẩu tối thiểu 6 ký tự';
        if (!empty($errors)) { http_response_code(422); echo json_encode(['errors' => $errors]); exit; }

        if (!$this->accountModel->isEmailAvailable($email)) {
            http_response_code(409);
            echo json_encode(['error' => 'Email đã được sử dụng']);
            exit;
        }
        if ($this->accountModel->findByUsernameOrEmail($username)) {
            http_response_code(409);
            echo json_encode(['error' => 'Username đã tồn tại']);
            exit;
        }

        $result = $this->accountModel->register($username, $fullname, $email, $password, 'user');
        if ($result) {
            http_response_code(201);
            echo json_encode(['message' => 'Đăng ký thành công']);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Đăng ký thất bại']);
        }
        exit;
    }

    // POST /api/account/refreshToken  (nhận refresh_token và trả access token mới)
    private function refreshToken() {
        $data = json_decode(file_get_contents('php://input'), true);
        $refreshToken = trim($data['refresh_token'] ?? '');
        if (!$refreshToken) { http_response_code(400); echo json_encode(['error' => 'refresh_token là bắt buộc'], JSON_UNESCAPED_UNICODE); exit; }
        $newToken = JwtHelper::refreshAccessToken($refreshToken);
        if (!$newToken) { http_response_code(401); echo json_encode(['error' => 'Refresh token không hợp lệ hoặc hết hạn'], JSON_UNESCAPED_UNICODE); exit; }
        echo json_encode(['token' => $newToken], JSON_UNESCAPED_UNICODE);
        exit;
    }

    // GET /api/account/me  (yêu cầu JWT)
    private function me() {
        $payload = $this->checkAuth();
        $account = $this->accountModel->getAccountById($payload['id']);
        if (!$account) { http_response_code(404); echo json_encode(['error' => 'Không tìm thấy tài khoản']); exit; }
        unset($account['password'], $account['remember_token_hash'], $account['password_reset_token']);
        echo json_encode($account);
        exit;
    }

    // PUT /api/account/profile  (yêu cầu JWT)
    private function profile() {
        $payload  = $this->checkAuth();
        $data     = json_decode(file_get_contents('php://input'), true);
        $fullname = trim($data['fullname'] ?? '');
        $email    = trim($data['email'] ?? '');

        if (!$fullname) { http_response_code(422); echo json_encode(['error' => 'Họ tên không được để trống']); exit; }
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(422); echo json_encode(['error' => 'Email không hợp lệ']); exit; }

        $this->accountModel->updateProfile($payload['id'], $fullname, $email ?: null);
        echo json_encode(['message' => 'Cập nhật hồ sơ thành công']);
        exit;
    }

    // PUT /api/account/changePassword  (yêu cầu JWT)
    private function changePassword() {
        $payload     = $this->checkAuth();
        $data        = json_decode(file_get_contents('php://input'), true);
        $oldPassword = trim($data['old_password'] ?? '');
        $newPassword = trim($data['new_password'] ?? '');

        if (!$oldPassword || !$newPassword) { http_response_code(400); echo json_encode(['error' => 'old_password và new_password là bắt buộc']); exit; }
        if (strlen($newPassword) < 6) { http_response_code(422); echo json_encode(['error' => 'Mật khẩu mới tối thiểu 6 ký tự']); exit; }

        $account = $this->accountModel->getAccountById($payload['id']);
        if (!$this->accountModel->verifyPassword($oldPassword, $account['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Mật khẩu hiện tại không đúng']);
            exit;
        }

        $this->accountModel->changePassword($payload['id'], $newPassword);
        echo json_encode(['message' => 'Đổi mật khẩu thành công']);
        exit;
    }

    // POST /api/account/forgotPassword  (mô phỏng)
    private function forgotPassword() {
        $data  = json_decode(file_get_contents('php://input'), true);
        $email = trim($data['email'] ?? '');

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { http_response_code(422); echo json_encode(['error' => 'Email không hợp lệ']); exit; }

        $account = $this->accountModel->getAccountByEmail($email);
        // Không tiết lộ email có tồn tại hay không (bảo mật)
        echo json_encode(['message' => 'Nếu email tồn tại, hướng dẫn đặt lại mật khẩu đã được gửi (mô phỏng)']);

        if ($account) {
            // Mô phỏng: tạo token reset (thực tế sẽ gửi email)
            $this->accountModel->createPasswordResetToken($account['id']);
            // Log ra để test (production: gửi email)
            error_log("[ForgotPassword] Token tạo cho user_id=" . $account['id']);
        }
        exit;
    }

    private function checkAuth() {
        return JwtMiddleware::requireAuth();
    }
}
