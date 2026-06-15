<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-shopping-bag"></i> Smart Cart</span>
            <h2 class="font-display">Giỏ hàng của bạn</h2>
            <p class="mb-0"><?= !empty($cart) ? array_sum(array_column($cart, 'quantity')) . ' sản phẩm trong giỏ hàng' : 'Giỏ hàng trống' ?></p>
        </div>
        <a href="index.php?url=product" class="btn btn-outline-light mt-2 mt-md-0"><i class="fas fa-arrow-left mr-1"></i> Tiếp tục mua</a>
    </div>
</div>

<?php if (empty($cart)): ?>
    <div class="empty-state">
        <i class="fas fa-shopping-basket"></i>
        <h4>Giỏ hàng trống</h4>
        <p>Hãy khám phá và thêm sản phẩm yêu thích vào giỏ hàng!</p>
        <a href="index.php?url=product" class="btn btn-primary mt-3"><i class="fas fa-shopping-bag mr-1"></i> Xem sản phẩm</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-lg-8 mb-4">
            <div class="card animate-in">
                <div class="card-header"><i class="fas fa-list mr-2" style="color:var(--accent);"></i>Sản phẩm đã chọn</div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-center" style="width:170px">Số lượng</th>
                                    <th class="text-right" style="width:140px">Đơn giá</th>
                                    <th class="text-right" style="width:150px">Thành tiền</th>
                                    <th style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart as $id => $item): ?>
                                    <?php $subtotal = $item['price'] * $item['quantity']; $imgSrc = getImageSrc($item['image'], $item['name'], $item['category_id'] ?? null); ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if ($imgSrc): ?><img src="<?= e($imgSrc) ?>" class="table-img mr-3" alt="<?= e($item['name']) ?>"><?php endif; ?>
                                                <div>
                                                    <strong style="font-size:0.92rem;"><?= e($item['name']) ?></strong><br>
                                                    <a href="index.php?url=product/show/<?= (int) $item['id'] ?>" style="color:var(--cyan);font-size:.78rem;">Xem chi tiết</a>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <form action="index.php?url=cart/update" method="POST" class="qty-input justify-content-center">
                                                    <?= csrf_input() ?>
                                                <input type="hidden" name="id" value="<?= (int) $id ?>">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="this.form.querySelector('.cart-action').value='decrease'"><i class="fas fa-minus" style="font-size:0.65rem;"></i></button>
                                                <input type="number" name="quantity" value="<?= (int) $item['quantity'] ?>" min="0" max="99" onchange="this.form.querySelector('.cart-action').value='set'; this.form.submit();">
                                                <button type="submit" class="btn btn-sm btn-outline-secondary" onclick="this.form.querySelector('.cart-action').value='increase'"><i class="fas fa-plus" style="font-size:0.65rem;"></i></button>
                                                <input type="hidden" name="action" value="set" class="cart-action">
                                            </form>
                                        </td>
                                        <td class="text-right" style="color:var(--text-secondary);"><?= moneyVnd($item['price']) ?></td>
                                        <td class="text-right"><strong><?= moneyVnd($subtotal) ?></strong></td>
                                        <td class="text-center">
                                            <form method="POST" action="index.php?url=cart/remove/<?= (int) $id ?>" class="m-0"><?= csrf_input() ?><button type="submit" class="btn btn-sm btn-outline-danger" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Xóa sản phẩm này khỏi giỏ hàng?', () => this.form.submit());" title="Xóa"><i class="fas fa-times" style="font-size:0.75rem;"></i></button></form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4 mb-4">
            <div class="card animate-in" style="animation-delay:0.15s;position:sticky;top:92px;">
                <div class="card-header"><i class="fas fa-receipt mr-2" style="color:var(--accent);"></i>Tóm tắt đơn hàng</div>
                <div class="card-body">
                    <div class="checkout-summary">
                        <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Tạm tính:</span><strong><?= moneyVnd($totals['subtotal']) ?></strong></div>
                        <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Vận chuyển:</span><span style="color:var(--accent);font-weight:800;">Miễn phí</span></div>
                        <?php if ($totals['discount'] > 0): ?>
                            <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Ưu đãi:</span><strong style="color:var(--rose);">-<?= moneyVnd($totals['discount']) ?></strong></div>
                            <small style="color:var(--muted);"><?= e($totals['voucher_label']) ?></small>
                        <?php endif; ?>
                        <hr style="border-color:var(--border);">
                        <div class="d-flex justify-content-between align-items-center"><span style="font-weight:900;">Tổng cộng:</span><span class="order-total"><?= moneyVnd($totals['total']) ?></span></div>
                    </div>

                    <form action="index.php?url=cart/applyVoucher" method="POST" class="mt-3">
                        <?= csrf_input() ?>
                        <label><i class="fas fa-ticket-alt mr-1" style="color:var(--accent);"></i>Mã ưu đãi</label>
                        <div class="input-group">
                            <input type="text" name="voucher_code" class="form-control" placeholder="FUTURE10 / VIP15 / TECH500" value="<?= e($_SESSION['voucher']['code'] ?? '') ?>">
                            <div class="input-group-append"><button class="btn btn-primary" type="submit">Áp dụng</button></div>
                        </div>
                        <div class="d-flex flex-wrap mt-2" style="gap:6px;">
                            <span class="mini-chip copy-text" data-copy="FUTURE10">FUTURE10</span>
                            <span class="mini-chip copy-text" data-copy="VIP15">VIP15</span>
                            <span class="mini-chip copy-text" data-copy="TECH500">TECH500</span>
                            <?php if (!empty($_SESSION['voucher'])): ?><a href="index.php?url=cart/removeVoucher" class="mini-chip" style="color:var(--rose);"><i class="fas fa-times"></i>Gỡ mã</a><?php endif; ?>
                        </div>
                    </form>

                    <a href="index.php?url=order/checkout" class="btn btn-primary btn-block mt-4" style="padding:12px;"><i class="fas fa-lock mr-2"></i>Thanh toán</a>
                    <form method="POST" action="index.php?url=cart/clear" class="mt-2"><?= csrf_input() ?><button type="submit" class="btn btn-outline-danger btn-block" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Xóa toàn bộ giỏ hàng?', () => this.form.submit());"><i class="fas fa-trash-alt mr-1"></i> Xóa giỏ hàng</button></form>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
