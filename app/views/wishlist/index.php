<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-heart"></i> Wishlist</span>
            <h2 class="font-display">Sản phẩm yêu thích</h2>
            <p>Lưu các sản phẩm muốn mua sau, thêm nhanh vào giỏ hoặc đưa vào bảng so sánh.</p>
        </div>
        <div class="mt-2 mt-md-0 d-flex flex-wrap" style="gap:8px;">
            <a href="index.php?url=product" class="btn btn-outline-light"><i class="fas fa-store mr-1"></i>Tiếp tục xem</a>
            <?php if (!empty($products)): ?><form method="POST" action="index.php?url=wishlist/clear" class="m-0"><?= csrf_input() ?><button type="submit" class="btn btn-danger" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Xóa toàn bộ wishlist?', () => this.form.submit());"><i class="fas fa-trash mr-1"></i>Xóa hết</button></form><?php endif; ?>
        </div>
    </div>
</div>

<?php if (empty($products)): ?>
    <div class="empty-state"><i class="fas fa-heart-broken"></i><h4>Wishlist đang trống</h4><p>Bấm nút trái tim ở sản phẩm để lưu lại.</p><a href="index.php?url=product" class="btn btn-primary mt-3"><i class="fas fa-store mr-1"></i>Xem sản phẩm</a></div>
<?php else: ?>
    <div class="row">
        <?php foreach ($products as $index => $product): ?>
            <?php $imgSrc = getImageSrc($product['image'], $product['name'], $product['category_id'] ?? null); $tag = getProductTag($product); ?>
            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 animate-in" style="animation-delay:<?= $index * .05 ?>s;">
                <div class="product-card h-100">
                    <div class="img-wrapper">
                        <?php if ($tag): ?><span class="product-tag" style="color:<?= e($tag['color']) ?>;background:<?= e($tag['bg']) ?>;"><?= e($tag['label']) ?></span><?php endif; ?>
                        <img src="uploads/product-placeholder.svg" data-src="<?= e($imgSrc) ?>" class="card-img-top img-lazy" alt="<?= e($product['name']) ?>" loading="lazy">
                        <div class="overlay"><a href="index.php?url=cart/add/<?= (int) $product['id'] ?>" class="btn btn-success btn-sm"><i class="fas fa-cart-plus mr-1"></i>Mua</a><form method="POST" action="index.php?url=wishlist/remove/<?= (int) $product['id'] ?>" class="d-inline m-0"><?= csrf_input() ?><button class="btn btn-outline-light btn-sm" type="submit"><i class="fas fa-times mr-1"></i>Bỏ</button></form></div>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <span class="category-badge"><i class="fas fa-microchip"></i><?= e($product['category_name'] ?? 'Công nghệ') ?></span>
                        <h5 class="card-title"><?= e($product['name']) ?></h5>
                        <div class="price my-2"><?= moneyVnd($product['price']) ?></div>
                        <small style="color:var(--muted);">Đã lưu: <?= e($product['wishlist_at']) ?></small>
                        <div class="d-flex flex-wrap mt-3" style="gap:6px;">
                            <a href="index.php?url=cart/add/<?= (int) $product['id'] ?>" class="btn btn-cart btn-sm flex-fill"><i class="fas fa-cart-plus mr-1"></i>Giỏ</a>
                            <a href="index.php?url=compare/add/<?= (int) $product['id'] ?>" class="btn btn-outline-secondary btn-sm"><i class="fas fa-balance-scale"></i></a>
                            <a href="index.php?url=product/show/<?= (int) $product['id'] ?>" class="btn btn-secondary btn-sm"><i class="fas fa-eye"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
