<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<?php
$subtotal = isset($order['subtotal_amount']) ? (float) $order['subtotal_amount'] : 0;
$discount = isset($order['discount_amount']) ? (float) $order['discount_amount'] : 0;
$shipping = isset($order['shipping_fee']) ? (float) $order['shipping_fee'] : 0;
$total = isset($order['total_amount']) && (float) $order['total_amount'] > 0 ? (float) $order['total_amount'] : 0;
if ($total <= 0) {
    foreach ($orderDetails as $detail) $total += $detail['quantity'] * $detail['price'];
    $subtotal = $total;
}
?>

<div class="page-header">
    <div class="text-center">
        <span class="hero-chip mb-3"><i class="fas fa-check-circle"></i> Order Confirmed</span>
        <h2 class="font-display">Đặt hàng thành công!</h2>
        <p>Mã đơn hàng <strong class="copy-text" data-copy="#<?= (int) $order['id'] ?>">#<?= (int) $order['id'] ?></strong> đã được ghi nhận.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <div class="card animate-in mb-4">
            <div class="card-header"><i class="fas fa-user-check mr-2" style="color:var(--accent);"></i>Thông tin nhận hàng</div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">Khách hàng</span><br><strong><?= e($order['name']) ?></strong></div>
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">Số điện thoại</span><br><strong><?= e($order['phone']) ?></strong></div>
                    <?php if (!empty($order['email'])): ?><div class="col-md-6 mb-3"><span style="color:var(--muted);">Email</span><br><strong><?= e($order['email']) ?></strong></div><?php endif; ?>
                    <div class="col-md-6 mb-3"><span style="color:var(--muted);">Thanh toán</span><br><strong><?= e($order['payment_method'] ?? 'COD') ?></strong></div>
                    <div class="col-12"><span style="color:var(--muted);">Địa chỉ</span><br><strong><?= nl2br(e($order['address'])) ?></strong></div>
                    <?php if (!empty($order['note'])): ?><div class="col-12 mt-3"><span style="color:var(--muted);">Ghi chú</span><br><strong><?= nl2br(e($order['note'])) ?></strong></div><?php endif; ?>
                </div>
            </div>
        </div>

        <div class="card animate-in mb-4">
            <div class="card-header"><i class="fas fa-box-open mr-2" style="color:var(--cyan);"></i>Sản phẩm trong đơn</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table mb-0">
                        <thead><tr><th>Sản phẩm</th><th class="text-center">SL</th><th class="text-right">Giá</th><th class="text-right">Tạm tính</th></tr></thead>
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
            <div class="card-body" style="border-top:1px solid var(--border);">
                <div class="checkout-summary ml-auto" style="max-width:360px;">
                    <div class="d-flex justify-content-between mb-2"><span>Tạm tính</span><strong><?= moneyVnd($subtotal) ?></strong></div>
                    <?php if ($discount > 0): ?><div class="d-flex justify-content-between mb-2"><span>Ưu đãi</span><strong style="color:var(--rose);">-<?= moneyVnd($discount) ?></strong></div><?php endif; ?>
                    <div class="d-flex justify-content-between mb-2"><span>Vận chuyển</span><strong><?= moneyVnd($shipping) ?></strong></div>
                    <hr style="border-color:var(--border);"><div class="d-flex justify-content-between align-items-center"><span style="font-weight:900;">Tổng</span><span class="order-total"><?= moneyVnd($total) ?></span></div>
                </div>
            </div>
        </div>

        <div class="text-center mb-4">
            <a href="index.php?url=product" class="btn btn-primary"><i class="fas fa-store mr-1"></i>Tiếp tục mua</a>
            <a href="index.php?url=order/show/<?= (int) $order['id'] ?>" class="btn btn-secondary"><i class="fas fa-receipt mr-1"></i>Xem đơn hàng</a>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
