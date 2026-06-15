<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card animate-in" style="max-width:520px">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="auth-logo-ring"><i class="fas fa-unlock-alt"></i></div>
                    <h2 class="auth-title">Quên mật khẩu</h2>
                    <p class="auth-subtitle">Nhập username hoặc email để nhận liên kết đặt lại mật khẩu</p>
                </div>

                <?php if (!empty($sent)): ?>
                    <div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i>Nếu tài khoản tồn tại, hệ thống đã gửi liên kết đặt lại mật khẩu. Khi chạy local, link được lưu trong <code>storage/mail.log</code>.</div>
                    <?php if (!empty($_SESSION['last_password_reset_url'])): ?>
                        <div class="alert alert-info" style="word-break:break-all">
                            <strong>Link test local:</strong><br>
                            <a href="<?= e($_SESSION['last_password_reset_url']) ?>"><?= e($_SESSION['last_password_reset_url']) ?></a>
                        </div>
                        <?php unset($_SESSION['last_password_reset_url']); ?>
                    <?php endif; ?>
                    <div class="text-center"><a href="index.php?url=account/login" class="auth-link">Quay lại đăng nhập</a></div>
                <?php else: ?>
                    <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $err): ?>
                            <div><i class="fas fa-exclamation-triangle mr-1"></i><?= e($err) ?></div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <form method="POST" action="index.php?url=account/forgot-password">
                        <?= csrf_input() ?>
                        <div class="float-group">
                            <input type="text" name="identifier" class="form-control" placeholder=" " required autofocus>
                            <label>Username hoặc email</label>
                            <i class="fas fa-user-shield input-icon"></i>
                        </div>
                        <button type="submit" class="btn btn-primary auth-btn">
                            <i class="fas fa-paper-plane mr-2"></i>Gửi liên kết đặt lại
                        </button>
                    </form>
                    <div class="text-center mt-4"><a href="index.php?url=account/login" class="auth-link">Tôi nhớ mật khẩu</a></div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
