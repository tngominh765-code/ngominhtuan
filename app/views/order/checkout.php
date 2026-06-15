<?php
$seoTitle = 'Thanh toán — NovaTech';
$seoDescription = 'Hoàn tất đơn hàng NovaTech.';
$seoRobots = 'noindex, nofollow';
?>
<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <?= breadcrumb([['label'=>'Giỏ hàng','url'=>'index.php?url=cart'],['label'=>'Thanh toán']]) ?>
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-credit-card"></i> Secure Checkout</span>
            <h2 class="font-display">Thanh toán đơn hàng</h2>
            <p class="mb-0">Điền thông tin giao hàng, chọn phương thức thanh toán và xác nhận.</p>
        </div>
        <a href="index.php?url=cart" class="btn btn-outline-light mt-2 mt-md-0"><i class="fas fa-arrow-left mr-1"></i> Giỏ hàng</a>
    </div>
</div>

<div class="checkout-steps">
    <div class="checkout-step active"><i class="fas fa-shopping-bag"></i><span>1. Giỏ hàng</span></div>
    <div class="checkout-step active"><i class="fas fa-address-card"></i><span>2. Thông tin</span></div>
    <div class="checkout-step active"><i class="fas fa-shield-alt"></i><span>3. Xác nhận</span></div>
</div>

<div class="row">
    <div class="col-lg-7 mb-4">
        <div class="card animate-in">
            <div class="card-header"><i class="fas fa-user-astronaut mr-2" style="color:var(--accent);"></i>Thông tin người nhận</div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger"><i class="fas fa-exclamation-triangle mr-2"></i><strong>Vui lòng sửa các lỗi:</strong><ul class="mb-0 mt-2"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div>
                <?php endif; ?>

                <form action="index.php?url=order/place" method="POST">
                    <?= csrf_input() ?>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="name"><i class="fas fa-user mr-1" style="color:var(--accent);"></i>Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name" value="<?= e($old['name'] ?? '') ?>" placeholder="Nguyễn Văn A" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="phone"><i class="fas fa-phone mr-1" style="color:var(--cyan);"></i>Số điện thoại <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="phone" name="phone" value="<?= e($old['phone'] ?? '') ?>" placeholder="0912345678" required>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email"><i class="fas fa-envelope mr-1" style="color:var(--primary-light);"></i>Email nhận hóa đơn</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= e($old['email'] ?? '') ?>" placeholder="you@example.com">
                    </div>

                    <div class="form-group">
                        <label for="address"><i class="fas fa-map-marker-alt mr-1" style="color:var(--rose);"></i>Địa chỉ giao hàng <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="address" name="address" rows="3" placeholder="Số nhà, đường, phường/xã, quận/huyện, tỉnh/thành" required><?= e($old['address'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label for="note"><i class="fas fa-comment-dots mr-1" style="color:var(--warning);"></i>Ghi chú</label>
                        <textarea class="form-control" id="note" name="note" rows="2" placeholder="Ví dụ: giao buổi tối, gọi trước khi giao..."><?= e($old['note'] ?? '') ?></textarea>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-wallet mr-1" style="color:var(--accent);"></i>Phương thức thanh toán</label>
                        <div class="row">
                            <?php $payment = $old['payment_method'] ?? 'COD'; ?>
                            <div class="col-md-4 mb-2"><label class="checkout-summary d-block mb-0"><input type="radio" name="payment_method" value="COD" <?= $payment === 'COD' ? 'checked' : '' ?>> COD<br><small style="color:var(--muted);">Thanh toán khi nhận</small></label></div>
                            <div class="col-md-4 mb-2"><label class="checkout-summary d-block mb-0"><input type="radio" name="payment_method" value="BANK" <?= $payment === 'BANK' ? 'checked' : '' ?>> BANK<br><small style="color:var(--muted);">Chuyển khoản</small></label></div>
                            <div class="col-md-4 mb-2"><label class="checkout-summary d-block mb-0"><input type="radio" name="payment_method" value="MOMO" <?= $payment === 'MOMO' ? 'checked' : '' ?>> MOMO<br><small style="color:var(--muted);">Ví điện tử</small></label></div>
                        </div>
                        <div id="paymentInfoCOD" class="payment-info"><strong>COD:</strong> Thanh toán tiền mặt khi nhận hàng. Nhân viên sẽ gọi xác nhận trước khi giao.</div>
                        <div id="paymentInfoBANK" class="payment-info"><strong>Chuyển khoản:</strong> NOVATECH STORE - STK 9999999999 - Ngân hàng Future Bank. Nội dung: SĐT + mã đơn.</div>
                        <div id="paymentInfoMOMO" class="payment-info"><strong>MOMO:</strong> Quét ví 0909 999 999 - NovaTech Store. Hệ thống ghi nhận sau khi xác nhận đơn.</div>
                    </div>

                    <hr style="border-color:var(--border);">
                    <div class="d-flex justify-content-between flex-wrap" style="gap:8px;">
                        <a href="index.php?url=cart" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Giỏ hàng</a>
                        <button type="submit" class="btn btn-primary" style="padding:10px 30px;"><i class="fas fa-check-circle mr-1"></i> Đặt hàng</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-5 mb-4">
        <div class="card animate-in" style="animation-delay:0.15s;position:sticky;top:92px;">
            <div class="card-header"><i class="fas fa-shopping-bag mr-2" style="color:var(--accent);"></i>Đơn hàng (<?= array_sum(array_column($cart, 'quantity')) ?> SP)</div>
            <div class="card-body" style="max-height:420px;overflow-y:auto;">
                <?php foreach ($cart as $item): ?>
                    <?php $subtotal = $item['price'] * $item['quantity']; $imgSrc = getImageSrc($item['image'], $item['name'], $item['category_id'] ?? null); ?>
                    <div class="d-flex align-items-center mb-3 pb-3" style="border-bottom:1px solid var(--border-light);">
                        <?php if ($imgSrc): ?><img src="<?= e($imgSrc) ?>" class="table-img mr-3" alt="<?= e($item['name']) ?>"><?php endif; ?>
                        <div style="flex:1;min-width:0;"><div style="font-weight:800;font-size:0.9rem;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;"><?= e($item['name']) ?></div><div style="color:var(--text-muted);font-size:0.78rem;">SL: <?= (int) $item['quantity'] ?></div></div>
                        <strong style="font-size:0.86rem;white-space:nowrap;margin-left:8px;"><?= moneyVnd($subtotal) ?></strong>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="card-body" style="border-top:1px solid var(--border);padding-top:1rem;">
                <div class="checkout-summary">
                    <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Tạm tính:</span><strong><?= moneyVnd($totals['subtotal']) ?></strong></div>
                    <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Vận chuyển:</span><span style="color:var(--accent);font-weight:800;">Miễn phí</span></div>
                    <?php if ($totals['discount'] > 0): ?><div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Ưu đãi:</span><strong style="color:var(--rose);">-<?= moneyVnd($totals['discount']) ?></strong></div><?php endif; ?>
                    <hr style="border-color:var(--border);">
                    <div class="d-flex justify-content-between align-items-center"><span style="font-weight:900;font-size:1rem;">Tổng cộng:</span><span class="order-total"><?= moneyVnd($totals['total']) ?></span></div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
