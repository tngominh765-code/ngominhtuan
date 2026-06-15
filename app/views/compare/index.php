<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-balance-scale"></i> Compare Matrix</span>
            <h2 class="font-display">So sánh sản phẩm</h2>
            <p>Đặt tối đa 4 sản phẩm cạnh nhau để so sánh giá, danh mục và mô tả.</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap:8px;">
            <a href="index.php?url=product" class="btn btn-outline-light"><i class="fas fa-plus mr-1"></i>Thêm sản phẩm</a>
            <?php if (!empty($products)): ?><form method="POST" action="index.php?url=compare/clear" class="m-0"><?= csrf_input() ?><button type="submit" class="btn btn-danger" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Xóa bảng so sánh?', () => this.form.submit());"><i class="fas fa-trash mr-1"></i>Xóa hết</button></form><?php endif; ?>
        </div>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state"><i class="fas fa-balance-scale-left"></i><h4>Chưa có sản phẩm để so sánh</h4><p>Bấm biểu tượng cân ở sản phẩm để thêm vào bảng so sánh.</p><a href="index.php?url=product" class="btn btn-primary mt-3"><i class="fas fa-store mr-1"></i>Xem sản phẩm</a></div>
<?php else: ?>
    <div class="card animate-in">
        <div class="card-header"><i class="fas fa-table mr-2" style="color:var(--accent);"></i>Bảng so sánh</div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0">
                    <tbody>
                        <tr>
                            <th style="width:170px;">Hình ảnh</th>
                            <?php foreach ($products as $product): ?>
                                <?php $imgSrc = getImageSrc($product['image'], $product['name'], $product['category_id'] ?? null); ?>
                                <td class="text-center"><img src="uploads/product-placeholder.svg" data-src="<?= e($imgSrc) ?>" class="img-lazy" style="width:150px;height:150px;object-fit:cover;border-radius:22px;border:1px solid var(--line);" alt="<?= e($product['name']) ?>" loading="lazy"></td>
                            <?php endforeach; ?>
                        </tr>
                        <tr><th>Tên</th><?php foreach ($products as $product): ?><td><strong><?= e($product['name']) ?></strong></td><?php endforeach; ?></tr>
                        <tr><th>Giá</th><?php foreach ($products as $product): ?><td><span class="price"><?= moneyVnd($product['price']) ?></span></td><?php endforeach; ?></tr>
                        <tr><th>Danh mục</th><?php foreach ($products as $product): ?><td><span class="category-badge"><i class="fas fa-microchip"></i><?= e($product['category_name'] ?? 'Công nghệ') ?></span></td><?php endforeach; ?></tr>
                        <tr><th>Mô tả</th><?php foreach ($products as $product): ?><td style="min-width:220px;color:var(--text-secondary);line-height:1.7;"><?= e(textExcerpt($product['description'] ?? 'Chưa có mô tả', 180)) ?></td><?php endforeach; ?></tr>
                        <tr>
                            <th>Thao tác</th>
                            <?php foreach ($products as $product): ?>
                                <td>
                                    <div class="d-flex flex-wrap" style="gap:6px;">
                                        <a href="index.php?url=cart/add/<?= (int) $product['id'] ?>" class="btn btn-cart btn-sm"><i class="fas fa-cart-plus mr-1"></i>Giỏ</a>
                                        <a href="index.php?url=product/show/<?= (int) $product['id'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                                        <form method="POST" action="index.php?url=compare/remove/<?= (int) $product['id'] ?>" class="d-inline m-0"><?= csrf_input() ?><button type="submit" class="btn btn-outline-danger btn-sm"><i class="fas fa-times"></i></button></form>
                                    </div>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
