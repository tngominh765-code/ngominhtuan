<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<?php
$flash = $_SESSION['success'] ?? '';
$error = $_SESSION['error'] ?? '';
unset($_SESSION['success'], $_SESSION['error']);
?>

<?php if ($flash): ?>
<div class="alert alert-success"><i class="fas fa-check-circle mr-1"></i><?= e($flash) ?></div>
<?php endif; ?>
<?php if ($error): ?>
<div class="alert alert-danger"><i class="fas fa-exclamation-circle mr-1"></i><?= e($error) ?></div>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="section-title mb-0"><i class="fas fa-users-cog mr-1"></i>Quản lý người dùng</h2>
    <span class="badge badge-info" style="font-size:.82rem;padding:9px 12px;border-radius:999px">Admin có thể khóa/mở khóa tài khoản User</span>
</div>

<?php if (empty($accounts)): ?>
<div class="empty-state">
    <i class="fas fa-users"></i>
    <h4>Chưa có tài khoản nào</h4>
</div>
<?php else: ?>
<div class="table-responsive">
    <table class="table">
        <thead>
            <tr>
                <th>#</th>
                <th>Người dùng</th>
                <th>Email</th>
                <th class="text-center">Vai trò</th>
                <th class="text-center">Trạng thái</th>
                <th>Đăng nhập cuối</th>
                <th>Ngày tạo</th>
                <th class="text-center" style="width:170px">Thao tác</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($accounts as $acc): ?>
            <?php
                $avatar = !empty($acc['avatar']) && file_exists(BASE_PATH . '/' . $acc['avatar']) ? $acc['avatar'] : '';
                $isActive = (int)($acc['is_active'] ?? 1) === 1;
                $isSelf = (int)$acc['id'] === (int)($_SESSION['user_id'] ?? 0);
                $canManage = $acc['role'] !== 'admin' && !$isSelf;
            ?>
            <tr>
                <td><?= (int) $acc['id'] ?></td>
                <td>
                    <div class="d-flex align-items-center" style="gap:10px;min-width:180px">
                        <div style="width:42px;height:42px;border-radius:14px;overflow:hidden;background:rgba(255,255,255,.08);display:flex;align-items:center;justify-content:center;border:1px solid var(--line)">
                            <?php if ($avatar): ?><img src="<?= e($avatar) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover"><?php else: ?><i class="fas fa-user" style="color:var(--muted)"></i><?php endif; ?>
                        </div>
                        <div>
                            <strong><?= e($acc['username']) ?></strong>
                            <div style="font-size:.8rem;color:var(--muted)"><?= e($acc['fullname']) ?></div>
                        </div>
                    </div>
                </td>
                <td>
                    <?= e($acc['email'] ?? '') ?: '<span class="text-muted small">Chưa có</span>' ?>
                    <div>
                        <?php if (!empty($acc['email'])): ?>
                            <span class="badge <?= !empty($acc['email_verified_at']) ? 'badge-success' : 'badge-warning' ?>"><?= !empty($acc['email_verified_at']) ? 'Đã xác thực' : 'Chưa xác thực' ?></span>
                        <?php endif; ?>
                    </div>
                </td>
                <td class="text-center">
                    <?php if ($acc['role'] === 'admin'): ?>
                        <span class="badge badge-danger">Admin</span>
                    <?php else: ?>
                        <span class="badge badge-info">User</span>
                    <?php endif; ?>
                </td>
                <td class="text-center">
                    <span class="badge <?= $isActive ? 'badge-success' : 'badge-secondary' ?>"><?= $isActive ? 'Đang hoạt động' : 'Đã khóa' ?></span>
                </td>
                <td><?= e($acc['last_login_at'] ?? '') ?: '<span class="text-muted small">Chưa có</span>' ?></td>
                <td><?= e($acc['created_at'] ?? '') ?></td>
                <td class="text-center">
                    <?php if ($canManage): ?>
                        <form method="POST" action="index.php?url=account/toggleLock/<?= (int)$acc['id'] ?>" class="d-inline">
                            <?= csrf_input() ?>
                            <button type="submit" class="btn btn-sm <?= $isActive ? 'btn-warning' : 'btn-success' ?>" style="border-radius:10px" onclick="event.preventDefault(); showConfirm('Xác nhận', '<?= $isActive ? 'Khóa' : 'Mở khóa' ?> tài khoản <?= e($acc['username']) ?>?', () => this.form.submit());" title="<?= $isActive ? 'Khóa' : 'Mở khóa' ?>">
                                <i class="fas <?= $isActive ? 'fa-lock' : 'fa-unlock' ?>"></i>
                            </button>
                        </form>
                        <form method="POST" action="index.php?url=account/delete/<?= (int)$acc['id'] ?>" class="d-inline">
                            <?= csrf_input() ?>
                            <button type="submit" class="btn btn-sm btn-danger" style="border-radius:10px" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Xóa tài khoản <?= e($acc['username']) ?>?', () => this.form.submit());" title="Xóa"><i class="fas fa-trash-alt"></i></button>
                        </form>
                    <?php else: ?>
                        <span class="text-muted small"><?= $isSelf ? 'Tài khoản hiện tại' : '—' ?></span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
