<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<?php
$avatarSrc = !empty($account['avatar']) && file_exists(BASE_PATH . '/' . $account['avatar']) ? $account['avatar'] : '';
$emailVerified = !empty($account['email_verified_at']);
?>

<div class="auth-wrapper" style="min-height:auto;padding:30px 16px">
    <div class="auth-card animate-in" style="max-width:760px">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="auth-logo-ring" style="overflow:hidden;padding:0;background:rgba(255,255,255,.08)">
                        <?php if ($avatarSrc): ?>
                            <img src="<?= e($avatarSrc) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:20px">
                        <?php else: ?>
                            <i class="fas fa-user-cog"></i>
                        <?php endif; ?>
                    </div>
                    <h2 class="auth-title">Hồ sơ cá nhân</h2>
                    <p class="auth-subtitle">Xem, cập nhật thông tin cá nhân, ảnh đại diện và bảo mật tài khoản</p>
                </div>

                <?php if (!empty($successMsg)): ?>
                <div class="alert alert-success"><i class="fas fa-check-circle mr-2"></i><?= e($successMsg) ?></div>
                <?php endif; ?>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-triangle mr-1"></i><?= e($err) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <div class="auth-demo-box mb-4" style="align-items:flex-start">
                    <i class="fas fa-info-circle"></i>
                    <div>
                        <div style="font-size:.78rem;color:var(--muted);font-weight:700">Tên đăng nhập</div>
                        <code style="font-size:.9rem"><?= e($account['username'] ?? '') ?></code>
                    </div>
                    <div class="ml-auto text-right">
                        <div style="font-size:.78rem;color:var(--muted);font-weight:700">Vai trò</div>
                        <span style="color:var(--accent);font-weight:800;font-size:.85rem"><?= e($account['role'] ?? 'user') === 'admin' ? 'Quản trị viên' : 'Thành viên' ?></span>
                        <div style="font-size:.78rem;color:var(--muted);font-weight:700;margin-top:8px">Email</div>
                        <?php if (!empty($account['email'])): ?>
                            <span class="badge <?= $emailVerified ? 'badge-success' : 'badge-warning' ?>"><?= $emailVerified ? 'Đã xác thực' : 'Chưa xác thực' ?></span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Chưa có email</span>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-4 mb-md-0">
                        <h5 style="font-weight:900;margin-bottom:14px;font-size:.95rem">
                            <i class="fas fa-camera mr-2" style="color:var(--accent)"></i>Ảnh đại diện
                        </h5>
                        <div class="text-center p-3" style="border:1px solid var(--line);border-radius:22px;background:rgba(255,255,255,.05)">
                            <div style="width:150px;height:150px;border-radius:32px;margin:0 auto 16px;overflow:hidden;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;border:1px solid var(--line)">
                                <?php if ($avatarSrc): ?>
                                    <img src="<?= e($avatarSrc) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover">
                                <?php else: ?>
                                    <i class="fas fa-user" style="font-size:3.2rem;color:var(--muted)"></i>
                                <?php endif; ?>
                            </div>
                            <form method="POST" action="index.php?url=account/profile" enctype="multipart/form-data">
                                <?= csrf_input() ?>
                                <input type="hidden" name="action" value="upload_avatar">
                                <div class="custom-file mb-3 text-left">
                                    <input type="file" class="custom-file-input" id="avatarFile" name="avatar" accept="image/jpeg,image/png,image/gif,image/webp" required>
                                    <label class="custom-file-label" for="avatarFile">Chọn ảnh...</label>
                                </div>
                                <button type="submit" class="btn btn-secondary btn-block" style="border-radius:14px">
                                    <i class="fas fa-upload mr-2"></i>Tải ảnh lên
                                </button>
                                <small class="d-block mt-2" style="color:var(--muted)">JPG, PNG, GIF, WEBP. Tối đa 2MB.</small>
                            </form>
                        </div>
                    </div>

                    <div class="col-md-7">
                        <h5 style="font-weight:900;margin-bottom:14px;font-size:.95rem">
                            <i class="fas fa-edit mr-2" style="color:var(--accent)"></i>Cập nhật thông tin
                        </h5>
                        <form method="POST" action="index.php?url=account/profile">
                            <?= csrf_input() ?>
                            <input type="hidden" name="action" value="update_profile">
                            <div class="float-group">
                                <input type="text" name="fullname" class="form-control" placeholder=" "
                                       value="<?= e($account['fullname'] ?? '') ?>" required>
                                <label>Họ và tên</label>
                                <i class="fas fa-id-card input-icon"></i>
                            </div>
                            <div class="float-group">
                                <input type="email" name="email" class="form-control" placeholder=" "
                                       value="<?= e($account['email'] ?? '') ?>" required>
                                <label>Email</label>
                                <i class="fas fa-envelope input-icon"></i>
                            </div>
                            <button type="submit" class="btn btn-primary auth-btn mb-3">
                                <i class="fas fa-save mr-2"></i>Lưu thay đổi
                            </button>
                        </form>
                    </div>
                </div>

                <div class="auth-divider"><span>Bảo mật</span></div>

                <h5 style="font-weight:900;margin-bottom:14px;font-size:.95rem">
                    <i class="fas fa-shield-alt mr-2" style="color:var(--warning)"></i>Đổi mật khẩu
                </h5>
                <form method="POST" action="index.php?url=account/profile" id="changePasswordForm">
                    <?= csrf_input() ?>
                    <input type="hidden" name="action" value="change_password">
                    <div class="float-group">
                        <input type="password" name="current_password" id="currentPw" class="form-control" placeholder=" " required>
                        <label>Mật khẩu hiện tại</label>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePw('currentPw', this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="float-group">
                        <input type="password" name="new_password" id="newPw" class="form-control" placeholder=" " required minlength="6">
                        <label>Mật khẩu mới</label>
                        <i class="fas fa-key input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePw('newPw', this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                    <div class="float-group">
                        <input type="password" name="confirm_password" id="confirmPw" class="form-control" placeholder=" " required>
                        <label>Xác nhận mật khẩu mới</label>
                        <i class="fas fa-shield-alt input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePw('confirmPw', this)" tabindex="-1"><i class="fas fa-eye"></i></button>
                    </div>
                    <button type="submit" class="btn btn-warning auth-btn" style="color:#14110a">
                        <i class="fas fa-key mr-2"></i>Đổi mật khẩu
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePw(id, btn) {
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
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
