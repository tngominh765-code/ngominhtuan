<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="auth-wrapper">
    <div class="auth-card animate-in" style="max-width:520px">
        <div class="card">
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="auth-logo-ring">
                        <i class="fas fa-user-astronaut"></i>
                    </div>
                    <h2 class="auth-title">Tạo tài khoản</h2>
                    <p class="auth-subtitle">Gia nhập cộng đồng NovaTech — công nghệ tương lai</p>
                </div>

                <?php if (!empty($errors)): ?>
                <div class="alert alert-danger">
                    <?php foreach ($errors as $err): ?>
                        <div><i class="fas fa-exclamation-triangle mr-1"></i><?= e($err) ?></div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>

                <form method="POST" action="index.php?url=account/register" id="registerForm">
                    <?= csrf_input() ?>
                    <div class="float-group">
                        <input type="text" name="username" class="form-control" placeholder=" "
                               value="<?= e($old['username'] ?? '') ?>" required autocomplete="username">
                        <label>Tên đăng nhập</label>
                        <i class="fas fa-at input-icon"></i>
                    </div>
                    <div class="float-group">
                        <input type="text" name="fullname" class="form-control" placeholder=" "
                               value="<?= e($old['fullname'] ?? '') ?>" required autocomplete="name">
                        <label>Họ và tên</label>
                        <i class="fas fa-id-card input-icon"></i>
                    </div>
                    <div class="float-group">
                        <input type="email" name="email" class="form-control" placeholder=" "
                               value="<?= e($old['email'] ?? '') ?>" required autocomplete="email">
                        <label>Email xác thực</label>
                        <i class="fas fa-envelope input-icon"></i>
                    </div>
                    <div class="float-group">
                        <input type="password" name="password" id="regPassword" class="form-control" placeholder=" " required
                               autocomplete="new-password" minlength="6">
                        <label>Mật khẩu</label>
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('regPassword', this)" tabindex="-1" aria-label="Hiện/ẩn mật khẩu">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="float-group" style="margin-bottom:.6rem">
                        <input type="password" name="confirm_password" id="regConfirm" class="form-control" placeholder=" " required
                               autocomplete="new-password">
                        <label>Xác nhận mật khẩu</label>
                        <i class="fas fa-shield-alt input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword('regConfirm', this)" tabindex="-1" aria-label="Hiện/ẩn mật khẩu">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>

                    <div class="d-flex align-items-center mb-3" style="gap:8px;">
                        <div id="strengthBar" style="flex:1;height:4px;border-radius:999px;background:rgba(255,255,255,.08);overflow:hidden">
                            <div id="strengthFill" style="height:100%;width:0;border-radius:inherit;transition:width .3s,background .3s"></div>
                        </div>
                        <small id="strengthText" style="color:var(--muted);font-size:.72rem;font-weight:800;min-width:70px;text-align:right"></small>
                    </div>

                    <button type="submit" class="btn btn-primary auth-btn">
                        <i class="fas fa-rocket mr-2"></i>Đăng ký tài khoản
                    </button>
                </form>

                <div class="text-center mt-4">
                    <p class="auth-subtitle" style="margin:0">Đã có tài khoản?
                        <a href="index.php?url=account/login" class="auth-link">Đăng nhập</a>
                    </p>
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

// Password strength meter
(function() {
    var pw = document.getElementById('regPassword');
    var fill = document.getElementById('strengthFill');
    var text = document.getElementById('strengthText');
    if (!pw || !fill || !text) return;

    pw.addEventListener('input', function() {
        var val = this.value;
        var score = 0;
        if (val.length >= 6) score++;
        if (val.length >= 10) score++;
        if (/[A-Z]/.test(val)) score++;
        if (/[0-9]/.test(val)) score++;
        if (/[^a-zA-Z0-9]/.test(val)) score++;

        var levels = [
            { width: '0%', color: 'transparent', label: '' },
            { width: '20%', color: '#ff3b6b', label: 'Rất yếu' },
            { width: '40%', color: '#f59e0b', label: 'Yếu' },
            { width: '60%', color: '#fbbf24', label: 'Trung bình' },
            { width: '80%', color: '#22d3ee', label: 'Mạnh' },
            { width: '100%', color: '#00f5c8', label: 'Rất mạnh' }
        ];

        if (val.length === 0) score = 0;
        var level = levels[Math.min(score, 5)];
        fill.style.width = level.width;
        fill.style.background = level.color;
        text.textContent = level.label;
        text.style.color = level.color;
    });

    // Confirm password matching
    var confirm = document.getElementById('regConfirm');
    if (confirm) {
        confirm.addEventListener('input', function() {
            if (this.value && this.value !== pw.value) {
                this.style.borderColor = 'rgba(255,59,107,.6)';
                this.style.boxShadow = '0 0 0 3px rgba(255,59,107,.1)';
            } else if (this.value && this.value === pw.value) {
                this.style.borderColor = 'rgba(0,245,200,.6)';
                this.style.boxShadow = '0 0 0 3px rgba(0,245,200,.1)';
            } else {
                this.style.borderColor = '';
                this.style.boxShadow = '';
            }
        });
    }
})();
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
