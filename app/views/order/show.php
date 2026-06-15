<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<?= breadcrumb([['label'=>'Đơn hàng','url'=>'index.php?url=order'],['label'=>'#'.(int)$order['id']]]) ?>

<?php
$status = $order['status'] ?? 'new';
$subtotal = isset($order['subtotal_amount']) ? (float) $order['subtotal_amount'] : 0;
$discount = isset($order['discount_amount']) ? (float) $order['discount_amount'] : 0;
$shipping = isset($order['shipping_fee']) ? (float) $order['shipping_fee'] : 0;
$total = isset($order['total_amount']) && (float) $order['total_amount'] > 0 ? (float) $order['total_amount'] : 0;
if ($total <= 0) {
    foreach ($orderDetails as $detail) $total += $detail['quantity'] * $detail['price'];
    $subtotal = $total;
}
$statusOptions = [
    'new' => 'Mới',
    'processing' => 'Đang xử lý',
    'shipping' => 'Đang giao',
    'completed' => 'Hoàn tất',
    'cancelled' => 'Đã hủy',
];
?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-receipt"></i> Order #<?= (int) $order['id'] ?></span>
            <h2 class="font-display">Chi tiết đơn hàng #<?= (int) $order['id'] ?></h2>
            <p><?= !empty($order['created_at']) ? date('d/m/Y H:i', strtotime($order['created_at'])) : '' ?> • <span class="badge-status status-<?= e($status) ?>"><?= e($statusOptions[$status] ?? $status) ?></span></p>
        </div>
        <a href="index.php?url=order" class="btn btn-outline-light mt-2 mt-md-0"><i class="fas fa-arrow-left mr-1"></i> Danh sách đơn</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8 mb-4">
        <div class="card animate-in mb-4">
            <div class="card-header"><i class="fas fa-box-open mr-2" style="color:var(--accent);"></i>Sản phẩm</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Sản phẩm</th><th class="text-center">Số lượng</th><th class="text-right">Giá</th><th class="text-right">Thành tiền</th></tr></thead>
                        <tbody>
                            <?php foreach ($orderDetails as $detail): ?>
                                <?php $imgSrc = getImageSrc($detail['image'], $detail['product_name'], $detail['category_id'] ?? null); ?>
                                <tr>
                                    <td><div class="d-flex align-items-center"><?php if ($imgSrc): ?><img src="<?= e($imgSrc) ?>" class="table-img mr-3" alt=""><?php endif; ?><strong><?= e($detail['product_name']) ?></strong></div></td>
                                    <td class="text-center"><?= (int) $detail['quantity'] ?></td>
                                    <td class="text-right"><?= moneyVnd($detail['price']) ?></td>
                                    <td class="text-right"><strong><?= moneyVnd($detail['quantity'] * $detail['price']) ?></strong></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="card animate-in">
            <div class="card-header"><i class="fas fa-user-check mr-2" style="color:var(--cyan);"></i>Thông tin khách hàng</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">Tên</span><br><strong><?= e($order['name']) ?></strong></div>
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">SĐT</span><br><strong><?= e($order['phone']) ?></strong></div>
                    <?php if (!empty($order['email'])): ?><div class="col-md-6 mb-3"><span style="color:var(--muted);">Email</span><br><strong><?= e($order['email']) ?></strong></div><?php endif; ?>
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">Thanh toán</span><br><strong><?= e($order['payment_method'] ?? 'COD') ?></strong></div>
                    <div class="col-12"><span style="color:var(--muted);">Địa chỉ</span><br><strong><?= nl2br(e($order['address'])) ?></strong></div>
                    <?php if (!empty($order['note'])): ?><div class="col-12 mt-3"><span style="color:var(--muted);">Ghi chú</span><br><strong><?= nl2br(e($order['note'])) ?></strong></div><?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4 mb-4">
        <div class="card animate-in" style="position:sticky;top:92px;">
            <div class="card-header"><i class="fas fa-sliders-h mr-2" style="color:var(--warning);"></i>Điều phối đơn</div>
            <div class="card-body">
                <?php if (isAdmin()): ?>
                <form action="index.php?url=order/status/<?= (int) $order['id'] ?>" method="POST" class="mb-4">
                    <?= csrf_input() ?>
                    <label>Trạng thái đơn hàng</label>
                    <select name="status" class="form-control mb-2">
                        <?php foreach ($statusOptions as $value => $label): ?><option value="<?= e($value) ?>" <?= $status === $value ? 'selected' : '' ?>><?= e($label) ?></option><?php endforeach; ?>
                    </select>
                    <button class="btn btn-primary btn-block" type="submit"><i class="fas fa-save mr-1"></i>Cập nhật</button>
                </form>
                <?php endif; ?>

                <div class="checkout-summary">
                    <div class="d-flex justify-content-between mb-2"><span>Tạm tính</span><strong><?= moneyVnd($subtotal) ?></strong></div>
                    <?php if ($discount > 0): ?><div class="d-flex justify-content-between mb-2"><span>Ưu đãi</span><strong style="color:var(--rose);">-<?= moneyVnd($discount) ?></strong></div><?php endif; ?>
                    <div class="d-flex justify-content-between mb-2"><span>Vận chuyển</span><strong><?= moneyVnd($shipping) ?></strong></div>
                    <hr style="border-color:var(--border);"><div class="d-flex justify-content-between align-items-center"><span style="font-weight:900;">Tổng</span><span class="order-total"><?= moneyVnd($total) ?></span></div>
                </div>

                <button class="btn btn-secondary btn-block mt-3 copy-text" data-copy="#<?= (int) $order['id'] ?> - <?= e($order['name']) ?> - <?= moneyVnd($total) ?>"><i class="fas fa-copy mr-1"></i>Copy thông tin</button>
                <a href="index.php?url=product" class="btn btn-outline-secondary btn-block mt-2"><i class="fas fa-store mr-1"></i>Tiếp tục bán hàng</a>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
