<?php
/**
 * AccountController - Xác thực người dùng.
 * Đã nâng cấp: Remember Me, quên/đặt lại mật khẩu, xác thực email,
 * profile + avatar, khóa/mở khóa tài khoản, password_hash tương thích MD5 cũ.
 */
require_once BASE_PATH . '/app/helpers/SessionHelper.php';
require_once BASE_PATH . '/app/models/AccountModel.php';

class AccountController {
    private $accountModel;

    public function __construct() {
        $this->accountModel = new AccountModel();
    }

    /**
     * Đăng nhập - GET: hiển thị form / POST: xử lý đăng nhập
     */
    public function login() {
        if (isLoggedIn()) {
            redirect(isAdmin() ? 'index.php?url=dashboard' : 'index.php?url=product');
        }

        $errors = [];
        $loginAttempts = (int)($_SESSION['login_attempts'] ?? 0);
        $loginLastFail = (int)($_SESSION['login_last_fail'] ?? 0);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = 'Token không hợp lệ. Vui lòng tải lại trang và thử lại.';
            }

            $username = trim($_POST['username'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $remember = !empty($_POST['remember_me']);
            $locked = $loginAttempts >= 5 && (time() - $loginLastFail) < 600;

            if ($locked) {
                $remain = 600 - (time() - $loginLastFail);
                $errors[] = 'Quá nhiều lần thử. Vui lòng đợi ' . floor($remain / 60) . ' phút ' . ($remain % 60) . ' giây.';
            }
            if ($username === '') $errors[] = 'Vui lòng nhập tên đăng nhập hoặc email.';
            if ($password === '') $errors[] = 'Vui lòng nhập mật khẩu.';

            if (empty($errors)) {
                $account = $this->accountModel->findByUsernameOrEmail($username);
                if ($account && (int)($account['is_active'] ?? 1) !== 1) {
                    $errors[] = 'Tài khoản của bạn đang bị khóa. Vui lòng liên hệ quản trị viên.';
                } elseif ($account && !empty($account['email']) && empty($account['email_verified_at'])) {
                    $errors[] = 'Tài khoản chưa xác thực email. Vui lòng kiểm tra email hoặc bấm gửi lại email xác thực.';
                    $_SESSION['pending_verify_account'] = (int)$account['id'];
                } elseif ($account && $this->accountModel->verifyPassword($password, $account['password'])) {
                    $_SESSION['login_attempts'] = 0;
                    unset($_SESSION['login_last_fail']);
                    // Không xóa giỏ hàng tại đây. Nếu user có giỏ tạm hoặc giỏ đã lưu DB,
                    // hệ thống sẽ tự merge/load sau khi đăng nhập để tránh mất giỏ.

                    if ($this->accountModel->passwordNeedsRehash($account['password'])) {
                        $this->accountModel->upgradePasswordHash($account['id'], $password);
                        $account = $this->accountModel->getAccountById($account['id']);
                    }

                    setAuthSession($account);
                    $this->accountModel->markLastLogin($account['id']);

                    if ($remember) {
                        $plainToken = bin2hex(random_bytes(32));
                        $this->accountModel->setRememberToken($account['id'], $plainToken);
                        setRememberCookie($account['id'], $plainToken, 30);
                    }

                    $_SESSION['success'] = 'Đăng nhập thành công! Xin chào ' . ($account['fullname'] ?? $account['username']);
                    redirect(($account['role'] === 'admin') ? 'index.php?url=dashboard' : 'index.php?url=product');
                } else {
                    $_SESSION['login_attempts'] = ((int)($_SESSION['login_attempts'] ?? 0)) + 1;
                    $_SESSION['login_last_fail'] = time();
                    $loginAttempts = (int)$_SESSION['login_attempts'];
                    $errors[] = 'Tên đăng nhập/email hoặc mật khẩu không đúng.';
                }
            }
        }

        include BASE_PATH . '/app/views/account/login.php';
    }

    /**
     * Đăng ký - GET: hiển thị form / POST: xử lý đăng ký
     */
    public function register() {
        if (isLoggedIn()) {
            redirect(isAdmin() ? 'index.php?url=dashboard' : 'index.php?url=product');
        }

        $errors = [];
        $old = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = 'Token không hợp lệ. Vui lòng tải lại trang và thử lại.';
            }

            $username        = trim($_POST['username'] ?? '');
            $fullname        = trim($_POST['fullname'] ?? '');
            $email           = trim($_POST['email'] ?? '');
            $password        = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            $old = $_POST;

            if ($username === '') $errors[] = 'Tên đăng nhập không được để trống.';
            if (!preg_match('/^[a-zA-Z0-9_\.\-]{3,50}$/', $username)) $errors[] = 'Tên đăng nhập chỉ gồm chữ, số, dấu gạch dưới/gạch ngang/dấu chấm và dài 3-50 ký tự.';
            if ($fullname === '') $errors[] = 'Họ tên không được để trống.';
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email không hợp lệ.';
            if ($email !== '' && !$this->accountModel->isEmailAvailable($email)) $errors[] = 'Email đã được sử dụng.';
            if (strlen($password) < 6) $errors[] = 'Mật khẩu phải có ít nhất 6 ký tự.';
            if ($password !== $confirmPassword) $errors[] = 'Xác nhận mật khẩu không khớp.';

            if (empty($errors)) {
                $account = $this->accountModel->register($username, $fullname, $email, $password);
                if ($account) {
                    $verifyUrl = $this->buildUrl('index.php?url=account/verify-email/' . rawurlencode($account['email_verification_token']));
                    $this->sendAccountMail($email, 'Xác thực tài khoản NovaTech',
                        "Xin chào {$fullname},\n\nVui lòng bấm liên kết sau để xác thực tài khoản:\n{$verifyUrl}\n\nNếu bạn không tạo tài khoản, hãy bỏ qua email này."
                    );
                    $_SESSION['success'] = 'Đăng ký thành công! Vui lòng kiểm tra email để xác thực tài khoản trước khi đăng nhập. Khi chạy local, link xác thực được lưu trong storage/mail.log.';
                    redirect('index.php?url=account/login');
                } else {
                    $errors[] = 'Tên đăng nhập hoặc email đã tồn tại.';
                }
            }
        }

        include BASE_PATH . '/app/views/account/register.php';
    }

    /**
     * Đăng xuất
     */
    public function logout() {
        if (isLoggedIn() && !empty($_SESSION['user_id'])) {
            $this->accountModel->clearRememberToken((int)$_SESSION['user_id']);
        }
        clearRememberCookie();
        // Chỉ xóa giỏ trong session hiện tại để tránh lộ cho khách dùng chung máy.
        // Giỏ của tài khoản vẫn được lưu trong DB và sẽ tự nạp lại khi đăng nhập.
        unset($_SESSION['cart'], $_SESSION['voucher'], $_SESSION['cart_loaded_for_user']);
        clearAuthSession();
        $_SESSION['success'] = 'Đã đăng xuất thành công.';
        redirect('index.php?url=account/login');
    }

    /**
     * Quản lý tài khoản (Admin only)
     */
    public function manage() {
        requireAdmin();
        $accounts = $this->accountModel->getAllAccounts();
        include BASE_PATH . '/app/views/account/manage.php';
    }

    /**
     * Xoá tài khoản (Admin only)
     */
    public function delete($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $_SESSION['error'] = 'Yêu cầu xóa phải dùng POST.'; redirect('index.php?url=account/manage'); }
        requireAdmin();
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=account/manage'); }

        if ($id && (int)$id !== (int)($_SESSION['user_id'] ?? 0)) {
            $target = $this->accountModel->getAccountById($id);
            if ($target && $target['role'] !== 'admin') {
                if (!empty($target['avatar']) && file_exists(BASE_PATH . '/' . $target['avatar'])) {
                    @unlink(BASE_PATH . '/' . $target['avatar']);
                }
                $this->accountModel->deleteAccount($id);
                $_SESSION['success'] = 'Đã xoá tài khoản.';
            } else {
                $_SESSION['error'] = 'Không thể xóa tài khoản admin.';
            }
        }
        redirect('index.php?url=account/manage');
    }

    /**
     * Admin khóa/mở khóa user.
     */
    public function toggleLock($id = null) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') { $_SESSION['error'] = 'Yêu cầu phải dùng POST.'; redirect('index.php?url=account/manage'); }
        requireAdmin();
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=account/manage'); }

        $target = $id ? $this->accountModel->getAccountById($id) : null;
        if (!$target) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản.';
        } elseif ((int)$target['id'] === (int)($_SESSION['user_id'] ?? 0)) {
            $_SESSION['error'] = 'Bạn không thể tự khóa tài khoản đang đăng nhập.';
        } elseif ($target['role'] === 'admin') {
            $_SESSION['error'] = 'Không thể khóa tài khoản admin.';
        } else {
            $newStatus = (int)($target['is_active'] ?? 1) === 1 ? 0 : 1;
            $this->accountModel->setActiveStatus($target['id'], $newStatus);
            $_SESSION['success'] = $newStatus ? 'Đã mở khóa tài khoản.' : 'Đã khóa tài khoản.';
        }
        redirect('index.php?url=account/manage');
    }

    /**
     * Cập nhật profile / Đổi mật khẩu / Đổi avatar
     */
    public function profile() {
        requireLogin();

        $account = $this->accountModel->getAccountById((int)($_SESSION['user_id'] ?? 0));
        if (!$account) {
            $_SESSION['error'] = 'Không tìm thấy tài khoản.';
            redirect('index.php?url=account/login');
        }

        $errors = [];
        $successMsg = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $_SESSION['error'] = 'Token không hợp lệ.';
                redirect('index.php?url=account/profile');
            }

            $action = $_POST['action'] ?? '';

            if ($action === 'update_profile') {
                $fullname = trim($_POST['fullname'] ?? '');
                $email = trim($_POST['email'] ?? '');
                if ($fullname === '') {
                    $errors[] = 'Họ tên không được để trống.';
                }
                if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $errors[] = 'Email không hợp lệ.';
                } elseif (!$this->accountModel->isEmailAvailable($email, $account['id'])) {
                    $errors[] = 'Email đã được sử dụng bởi tài khoản khác.';
                }

                if (empty($errors)) {
                    $emailChanged = strtolower((string)$email) !== strtolower((string)($account['email'] ?? ''));
                    $result = $this->accountModel->updateProfile($account['id'], $fullname, $email, $emailChanged);
                    $_SESSION['fullname'] = $fullname;
                    $_SESSION['email'] = $email;
                    $successMsg = 'Cập nhật thông tin thành công.';

                    if ($emailChanged && $result) {
                        $verifyUrl = $this->buildUrl('index.php?url=account/verify-email/' . rawurlencode($result));
                        $this->sendAccountMail($email, 'Xác thực email NovaTech',
                            "Xin chào {$fullname},\n\nBạn vừa cập nhật email. Vui lòng xác thực email mới tại:\n{$verifyUrl}"
                        );
                        $successMsg .= ' Email mới cần được xác thực. Link đã được gửi qua email và lưu trong storage/mail.log khi chạy local.';
                    }

                    $account = $this->accountModel->getAccountById($account['id']);
                }
            } elseif ($action === 'change_password') {
                $currentPassword = $_POST['current_password'] ?? '';
                $newPassword = $_POST['new_password'] ?? '';
                $confirmPassword = $_POST['confirm_password'] ?? '';

                if ($currentPassword === '') $errors[] = 'Vui lòng nhập mật khẩu hiện tại.';
                if (strlen($newPassword) < 6) $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
                if ($newPassword !== $confirmPassword) $errors[] = 'Xác nhận mật khẩu không khớp.';

                if (empty($errors)) {
                    if (!$this->accountModel->verifyPassword($currentPassword, $account['password'])) {
                        $errors[] = 'Mật khẩu hiện tại không đúng.';
                    } else {
                        $this->accountModel->changePassword($account['id'], $newPassword);
                        clearRememberCookie();
                        $successMsg = 'Đổi mật khẩu thành công. Các phiên ghi nhớ đăng nhập cũ đã bị hủy.';
                        $account = $this->accountModel->getAccountById($account['id']);
                    }
                }
            } elseif ($action === 'upload_avatar') {
                $avatar = $this->uploadAvatar($_FILES['avatar'] ?? null, $errors, $account['avatar'] ?? '');
                if (empty($errors) && $avatar) {
                    $this->accountModel->updateAvatar($account['id'], $avatar);
                    $_SESSION['avatar'] = $avatar;
                    $successMsg = 'Cập nhật ảnh đại diện thành công.';
                    $account = $this->accountModel->getAccountById($account['id']);
                }
            }
        }

        include BASE_PATH . '/app/views/account/profile.php';
    }

    /**
     * Form quên mật khẩu.
     */
    public function forgotPassword() {
        if (isLoggedIn()) redirect('index.php?url=account/profile');
        $errors = [];
        $sent = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!verify_csrf()) {
                $errors[] = 'Token không hợp lệ. Vui lòng tải lại trang và thử lại.';
            }
            $identifier = trim($_POST['identifier'] ?? '');
            if ($identifier === '') $errors[] = 'Vui lòng nhập username hoặc email.';

            if (empty($errors)) {
                $account = $this->accountModel->findByUsernameOrEmail($identifier);
                if ($account && (int)($account['is_active'] ?? 1) === 1 && !empty($account['email'])) {
                    $token = $this->accountModel->createPasswordResetToken($account['id']);
                    if ($token) {
                        $resetUrl = $this->buildUrl('index.php?url=account/reset-password/' . rawurlencode($token));
                        $this->sendAccountMail($account['email'], 'Đặt lại mật khẩu NovaTech',
                            "Xin chào {$account['fullname']},\n\nBấm liên kết sau để đặt lại mật khẩu trong 30 phút:\n{$resetUrl}\n\nNếu bạn không yêu cầu, hãy bỏ qua email này."
                        );
                        // Khi chạy local nhiều máy không gửi được email thật; hiển thị link một lần để test nhanh.
                        $_SESSION['last_password_reset_url'] = $resetUrl;
                    }
                }
                // Không tiết lộ tài khoản có tồn tại hay không.
                $sent = true;
            }
        }

        include BASE_PATH . '/app/views/account/forgot_password.php';
    }

    /**
     * Form đặt lại mật khẩu.
     */
    public function resetPassword($tokenFromUrl = null) {
        if (isLoggedIn()) redirect('index.php?url=account/profile');
        $token = trim($_GET['token'] ?? $_POST['token'] ?? $tokenFromUrl ?? '');
        $errors = [];
        $account = $token !== '' ? $this->accountModel->getByValidResetToken($token) : false;

        if (!$account) {
            $errors[] = 'Liên kết đặt lại mật khẩu không hợp lệ hoặc đã hết hạn.';
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $account) {
            if (!verify_csrf()) {
                $errors[] = 'Token không hợp lệ. Vui lòng tải lại trang và thử lại.';
            }
            $newPassword = $_POST['new_password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            if (strlen($newPassword) < 6) $errors[] = 'Mật khẩu mới phải có ít nhất 6 ký tự.';
            if ($newPassword !== $confirmPassword) $errors[] = 'Xác nhận mật khẩu không khớp.';

            if (empty($errors)) {
                $this->accountModel->changePassword($account['id'], $newPassword);
                $this->accountModel->clearPasswordResetToken($account['id']);
                $_SESSION['success'] = 'Đặt lại mật khẩu thành công. Vui lòng đăng nhập.';
                redirect('index.php?url=account/login');
            }
        }

        include BASE_PATH . '/app/views/account/reset_password.php';
    }

    /**
     * Xác thực email qua token.
     */
    public function verifyEmail($tokenFromUrl = null) {
        $token = trim($_GET['token'] ?? $tokenFromUrl ?? '');
        if ($token === '') {
            $_SESSION['error'] = 'Thiếu mã xác thực email.';
            redirect('index.php?url=account/login');
        }

        $account = $this->accountModel->verifyEmailByToken($token);
        if ($account) {
            if (isLoggedIn() && (int)($_SESSION['user_id'] ?? 0) === (int)$account['id']) {
                $_SESSION['email'] = $account['email'] ?? '';
            }
            $_SESSION['success'] = 'Xác thực email thành công. Bạn có thể đăng nhập.';
        } else {
            $_SESSION['error'] = 'Mã xác thực email không hợp lệ hoặc đã được sử dụng.';
        }
        redirect('index.php?url=account/login');
    }

    /**
     * Gửi lại email xác thực cho tài khoản đang chờ.
     */
    public function resendVerification() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect('index.php?url=account/login');
        if (!verify_csrf()) { $_SESSION['error'] = 'Token không hợp lệ.'; redirect('index.php?url=account/login'); }

        $accountId = (int)($_SESSION['pending_verify_account'] ?? $_POST['account_id'] ?? 0);
        $account = $accountId ? $this->accountModel->getAccountById($accountId) : null;
        if ($account && !empty($account['email']) && empty($account['email_verified_at'])) {
            $token = $this->accountModel->createEmailVerificationToken($account['id']);
            $verifyUrl = $this->buildUrl('index.php?url=account/verify-email/' . rawurlencode($token));
            $this->sendAccountMail($account['email'], 'Gửi lại xác thực tài khoản NovaTech',
                "Xin chào {$account['fullname']},\n\nVui lòng xác thực tài khoản tại:\n{$verifyUrl}"
            );
            $_SESSION['success'] = 'Đã gửi lại email xác thực. Khi chạy local, link cũng được lưu trong storage/mail.log.';
        } else {
            $_SESSION['error'] = 'Không tìm thấy tài khoản cần xác thực.';
        }
        redirect('index.php?url=account/login');
    }

    /**
     * API Login - Trả về JWT token (Bài 6)
     */
    public function checkLogin() {
        header('Content-Type: application/json; charset=UTF-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Method not allowed']);
            exit;
        }

        $data = json_decode(file_get_contents('php://input'), true);
        $username = trim($data['username'] ?? '');
        $password = trim($data['password'] ?? '');

        if ($username === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Username/email và password là bắt buộc.']);
            exit;
        }

        $account = $this->accountModel->findByUsernameOrEmail($username);
        if (!$account || !$this->accountModel->verifyPassword($password, $account['password'])) {
            http_response_code(401);
            echo json_encode(['error' => 'Đăng nhập thất bại']);
            exit;
        }
        if ((int)($account['is_active'] ?? 1) !== 1) {
            http_response_code(423);
            echo json_encode(['error' => 'Tài khoản đang bị khóa']);
            exit;
        }
        if (!empty($account['email']) && empty($account['email_verified_at'])) {
            http_response_code(403);
            echo json_encode(['error' => 'Tài khoản chưa xác thực email']);
            exit;
        }

        if ($this->accountModel->passwordNeedsRehash($account['password'])) {
            $this->accountModel->upgradePasswordHash($account['id'], $password);
        }
        $this->accountModel->markLastLogin($account['id']);

        require_once BASE_PATH . '/app/helpers/JwtHelper.php';
        $token = JwtHelper::encode([
            'id'       => $account['id'],
            'username' => $account['username'],
            'fullname' => $account['fullname'],
            'email'    => $account['email'] ?? '',
            'avatar'   => $account['avatar'] ?? '',
            'role'     => $account['role'],
        ]);
        echo json_encode(['token' => $token]);
        exit;
    }

    /**
     * JWT Demo page - Bài 6.4
     */
    public function jwtdemo() {
        requireAdmin();
        include BASE_PATH . '/app/views/account/jwt_demo.php';
    }

    private function uploadAvatar($file, &$errors, $oldAvatar = '') {
        if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Vui lòng chọn ảnh đại diện.';
            return '';
        }
        if (($file['error'] ?? UPLOAD_ERR_OK) !== UPLOAD_ERR_OK) {
            $errors[] = 'Không thể tải ảnh lên.';
            return '';
        }
        if (($file['size'] ?? 0) > 2 * 1024 * 1024) {
            $errors[] = 'Ảnh đại diện tối đa 2MB.';
            return '';
        }

        $allowedTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
        $mime = '';
        if (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime = finfo_file($finfo, $file['tmp_name']);
            finfo_close($finfo);
        } else {
            $info = getimagesize($file['tmp_name']);
            $mime = $info['mime'] ?? ($file['type'] ?? '');
        }
        if (!isset($allowedTypes[$mime])) {
            $errors[] = 'Chỉ chấp nhận ảnh JPG, PNG, GIF hoặc WEBP.';
            return '';
        }

        $dir = BASE_PATH . '/uploads/avatars';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $filename = 'avatar_' . (int)($_SESSION['user_id'] ?? 0) . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $allowedTypes[$mime];
        $dest = $dir . '/' . $filename;
        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            $errors[] = 'Không thể lưu ảnh đại diện.';
            return '';
        }

        if ($oldAvatar && strpos($oldAvatar, 'uploads/avatars/') === 0 && file_exists(BASE_PATH . '/' . $oldAvatar)) {
            @unlink(BASE_PATH . '/' . $oldAvatar);
        }
        return 'uploads/avatars/' . $filename;
    }

    private function buildUrl($path) {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $baseDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '/index.php')), '/');
        $baseDir = $baseDir === '/' ? '' : $baseDir;
        return $scheme . '://' . $host . $baseDir . '/' . ltrim($path, '/');
    }

    private function sendAccountMail($to, $subject, $body) {
        $logDir = BASE_PATH . '/storage';
        if (!is_dir($logDir)) mkdir($logDir, 0775, true);
        $line = "\n==== " . date('Y-m-d H:i:s') . " ====\nTo: {$to}\nSubject: {$subject}\n{$body}\n";
        file_put_contents($logDir . '/mail.log', $line, FILE_APPEND);

        $headers = "From: NovaTech <no-reply@novatech.local>\r\n" .
                   "Content-Type: text/plain; charset=UTF-8\r\n";
        if (function_exists('mail')) {
            @mail($to, '=?UTF-8?B?' . base64_encode($subject) . '?=', $body, $headers);
        }
        return true;
    }
}
