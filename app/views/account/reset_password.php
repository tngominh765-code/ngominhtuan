<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card animate-in" style="max-width:520px">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="auth-logo-ring"><i class="fas fa-key"></i></div>
                    <h2 class="auth-title">Đặt lại mật khẩu</h2>
                    <p class="auth-subtitle">Tạo mật khẩu mới cho tài khoản NovaTech</p>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-triangle mr-1"></i><?= e($err) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <?php if (!empty($account)): ?>
                <form method="POST" action="index.php?url=account/reset-password">
                    <?= csrf_input() ?>
                    <input type="hidden" name="token" value="<?= e($token ?? '') ?>">
                    <div class="float-group">
                        <input type="password" name="new_password" id="resetPw" class="form-control" placeholder=" " required minlength="6">
                        <label>Mật khẩu mới</label>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('resetPw', this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="float-group">
                        <input type="password" name="confirm_password" id="resetConfirm" class="form-control" placeholder=" " required minlength="6">
                        <label>Xác nhận mật khẩu</label>
                        <i class="fas fa-shield-alt input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('resetConfirm', this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                    <button type="submit" class="btn btn-primary auth-btn">
                        <i class="fas fa-save mr-2"></i>Lưu mật khẩu mới
                    </button>
                </form>
                <?php endif; ?>

                <div class="text-center mt-4"><a href="index.php?url=account/login" class="auth-link">Quay lại đăng nhập</a></div>
            </div>
        </div>
    </div>
</div>

<script>
function togglePassword(id, btn) {
    var input = document.getElementById(id);
    var icon = btn.querySelector('i');
    if (input.type === 'password') { input.type = 'text'; icon.className = 'fas fa-eye-slash'; }
    else { input.type = 'password'; icon.className = 'fas fa-eye'; }
}
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
