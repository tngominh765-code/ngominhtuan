<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<?php
$flashSuccess = $_SESSION['success'] ?? '';
$flashError   = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<div class="auth-wrapper">
    <div class="auth-card animate-in">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="auth-logo-ring">
                        <i class="fas fa-fingerprint"></i>
                    </div>
                    <h2 class="auth-title">Đăng nhập</h2>
                    <p class="auth-subtitle">Xác thực tài khoản để truy cập NovaTech Store</p>
                </div>

                <?php if ($flashSuccess): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i><?= $flashSuccess ?></div>
                <?php endif; ?>
                <?php if ($flashError): ?>
                <div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-2"></i><?= $flashError ?></div>
                <?php endif; ?>

                <?php if (($loginAttempts ?? ($_SESSION['login_attempts'] ?? 0)) >= 3 && (($loginAttempts ?? ($_SESSION['login_attempts'] ?? 0)) < 5)): ?>
                <div class="alert alert-warning"><i class="fas fa-shield-alt mr-2"></i>Còn <?= max(0, 5 - (int)($loginAttempts ?? $_SESSION['login_attempts'])) ?> lần thử trước khi bị khóa tạm thời.</div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-triangle mr-1"></i><?= e($err) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="index.php?url=account/login" id="loginForm">
                    <?= csrf_input() ?>
                    <div class="float-group">
                        <input type="text" name="username" class="form-control" placeholder=" " required value="<?= e($_POST['username'] ?? '') ?>" autofocus autocomplete="username">
                        <label>Tên đăng nhập hoặc email</label>
                        <i class="fas fa-user input-icon"></i>
                    </div>
                    <div class="float-group">
                        <input type="password" name="password" id="loginPassword" class="form-control" placeholder=" " required autocomplete="current-password">
                        <label>Mật khẩu</label>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('loginPassword', this)" tabindex="-1" aria-label="Hiện/ẩn mật khẩu">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3" style="gap:12px;">
                        <div class="custom-control custom-checkbox">
                            <input type="checkbox" class="custom-control-input" id="rememberMe" name="remember_me" value="1" <?= !empty($_POST['remember_me']) ? 'checked' : '' ?>>
                            <label class="custom-control-label" for="rememberMe" style="color:var(--muted);font-size:.84rem;font-weight:800">Ghi nhớ đăng nhập</label>
                        </div>
                        <a href="index.php?url=account/forgot-password" class="auth-link" style="font-size:.84rem">Quên mật khẩu?</a>
                    </div>
                    <button type="submit" class="btn btn-primary auth-btn mt-2">
                        <i class="fas fa-sign-in-alt mr-2"></i>Đăng nhập
                    </button>
                </form>


                <?php if (!empty($_SESSION['pending_verify_account'])): ?>
                <form method="POST" action="index.php?url=account/resendVerification" class="text-center mt-3">
                    <?= csrf_input() ?>
                    <button type="submit" class="btn btn-sm btn-secondary" style="border-radius:12px">
                        <i class="fas fa-envelope mr-1"></i>Gửi lại email xác thực
                    </button>
                </form>
                <?php endif; ?>

                <div class="text-center mt-4">
                    <p class="auth-subtitle" style="margin:0">Chưa có tài khoản?
                        <a href="index.php?url=account/register" class="auth-link">Đăng ký ngay</a>
                    </p>
                </div>

                <div class="auth-divider"><span>Tài khoản demo</span></div>

                <div class="auth-demo-box">
                    <i class="fas fa-terminal"></i>
                    <div>
                        <div style="font-size:.8rem;color:var(--muted);font-weight:700;margin-bottom:2px">Đăng nhập nhanh</div>
                        <code>admin</code> <span style="color:var(--muted);font-size:.78rem;margin:0 4px">/</span> <code>123456</code>
                    </div>
                    <button type="button" class="btn btn-sm btn-secondary ml-auto" onclick="quickFill()" style="border-radius:10px;font-size:.75rem;padding:6px 12px">
                        <i class="fas fa-bolt mr-1"></i>Fill
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    var input = document.getElementById(id);
    var icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.className = 'fas fa-eye-slash';
    } else {
        input.type = 'password';
        icon.className = 'fas fa-eye';
    }
}
function quickFill() {
    var form = document.getElementById('loginForm');
    form.querySelector('[name="username"]').value = 'admin';
    form.querySelector('[name="password"]').value = '123456';
    if (typeof showToast === 'function') showToast('Đã điền tài khoản demo!');
}
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
