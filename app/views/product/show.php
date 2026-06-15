<?php
$seoTitle = ($product['name'] ?? 'Sản phẩm') . ' — NovaTech';
$seoDescription = textExcerpt($product['description'] ?? '', 150);
$seoImage = getImageSrc($product['image'] ?? '', $product['name'] ?? '', $product['category_id'] ?? null);
?>
<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<?= breadcrumb([['label'=>'Sản phẩm','url'=>'index.php?url=product'],['label'=>$product['name'] ?? 'Chi tiết']]) ?>

<?php $imgSrc = getImageSrc($product['image'], $product['name'], $product['category_id'] ?? null); ?>

<div class="page-header">
    <div class="d-flex justify-content-between align-items-center flex-wrap">
        <div>
            <span class="hero-chip mb-3"><i class="fas fa-microchip"></i> Product Detail</span>
            <h2 class="font-display"><?= e($product['name']) ?></h2>
            <p><?= e($product['category_name'] ?? 'Công nghệ') ?> • Mã sản phẩm #<?= (int) $product['id'] ?></p>
        </div>
        <a href="index.php?url=product" class="btn btn-outline-light mt-2 mt-md-0"><i class="fas fa-arrow-left mr-1"></i> Quay lại</a>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card animate-in h-100">
            <div class="card-body position-relative">
                <span class="hero-chip detail-gallery-badge"><i class="fas fa-crown"></i> Premium</span>
                <?php $heroTag = getProductTag($product); ?>
                <?php if ($heroTag): ?><span class="product-tag" style="color:<?= e($heroTag['color']) ?>;background:<?= e($heroTag['bg']) ?>;"><?= e($heroTag['label']) ?></span><?php endif; ?>
                <?php if ($imgSrc): ?>
                    <img src="uploads/product-placeholder.svg" data-src="<?= e($imgSrc) ?>" alt="<?= e($product['name']) ?>" class="product-hero-img img-lazy" loading="lazy">
                <?php else: ?>
                    <div class="img-placeholder" style="height:520px;border-radius:24px;"><i class="fas fa-camera"></i></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-6 mb-4">
        <div class="card animate-in h-100" style="animation-delay:.08s;">
            <div class="card-body d-flex flex-column">
                <div class="mb-3">
                    <span class="category-badge"><i class="fas fa-layer-group"></i><?= e($product['category_name'] ?? 'Chưa phân loại') ?></span>
                    <span class="mini-chip"><i class="fas fa-shield-alt"></i>Bảo hành chính hãng</span>
                    <span class="mini-chip"><i class="fas fa-truck"></i>Giao nhanh</span>
                </div>

                <h1 class="font-weight-bold" style="font-size:2rem;line-height:1.25;"><?= e($product['name']) ?></h1>
                <div class="price my-3" style="font-size:2rem;"><?= moneyVnd($product['price']) ?></div>

                <div class="checkout-summary mb-4">
                    <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Trả góp 0%</span><strong>Có hỗ trợ</strong></div>
                    <div class="d-flex justify-content-between mb-2"><span style="color:var(--text-secondary);">Đổi trả</span><strong>7 ngày</strong></div>
                    <div class="d-flex justify-content-between"><span style="color:var(--text-secondary);">Tình trạng</span><strong style="color:var(--accent);">Sẵn hàng</strong></div>
                </div>

                <div class="mb-4">
                    <h5 class="font-weight-bold"><i class="fas fa-align-left mr-2" style="color:var(--accent);"></i>Mô tả</h5>
                    <p style="color:var(--text-secondary);line-height:1.85;">
                        <?= !empty($product['description']) ? nl2br(e($product['description'])) : '<em style="color:var(--text-muted);">Chưa có mô tả</em>' ?>
                    </p>
                </div>

                <form action="index.php?url=cart/add/<?= (int) $product['id'] ?>" method="POST" class="mt-auto" id="detailCartForm">
                    <?= csrf_input() ?>
                    <div class="form-row align-items-end mb-3">
                        <div class="col-sm-4">
                            <label>Số lượng</label>
                            <input type="number" name="quantity" class="form-control" value="1" min="1" max="99">
                        </div>
                        <div class="col-sm-8 mt-3 mt-sm-0">
                            <button type="submit" class="btn btn-cart btn-block" style="padding:12px 28px;"><i class="fas fa-cart-plus mr-2"></i>Thêm vào giỏ</button>
                        </div>
                    </div>
                    <button type="submit" formaction="index.php?url=cart/buyNow/<?= (int) $product['id'] ?>" class="btn btn-primary btn-block mb-3" style="padding:13px 28px;"><i class="fas fa-bolt mr-2"></i>Mua ngay / Thanh toán</button>
                </form>

                <div class="d-flex flex-wrap" style="gap:8px;">
                    <a href="index.php?url=wishlist/add/<?= (int) $product['id'] ?>" class="btn btn-outline-danger"><i class="fas fa-heart mr-1"></i>Yêu thích</a>
                    <a href="index.php?url=compare/add/<?= (int) $product['id'] ?>" class="btn btn-outline-secondary"><i class="fas fa-balance-scale mr-1"></i>So sánh</a>
                    <?php if (isAdminLoggedIn()): ?>
                    <a href="index.php?url=product/edit/<?= (int) $product['id'] ?>" class="btn btn-warning"><i class="fas fa-pen mr-1"></i>Sửa</a>
                    <form method="POST" action="index.php?url=product/delete/<?= (int) $product['id'] ?>" class="d-inline m-0">
                        <?= csrf_input() ?>
                        <button type="submit" class="btn btn-danger" onclick="event.preventDefault(); showConfirm('Xác nhận xóa', 'Hành động này không thể hoàn tác.', () => this.form.submit());"><i class="fas fa-trash-alt mr-1"></i>Xóa</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="feature-strip">
    <div class="feature-pill"><i class="fas fa-shipping-fast"></i><span>Giao nhanh 2H<br><small>Nội thành hỗ trợ siêu tốc</small></span></div>
    <div class="feature-pill"><i class="fas fa-medal"></i><span>Hàng chính hãng<br><small>Cam kết nguồn gốc rõ ràng</small></span></div>
    <div class="feature-pill"><i class="fas fa-sync-alt"></i><span>Đổi trả 7 ngày<br><small>Hỗ trợ lỗi kỹ thuật</small></span></div>
    <div class="feature-pill"><i class="fas fa-tools"></i><span>Bảo hành tận tâm<br><small>Đội kỹ thuật chuyên nghiệp</small></span></div>
</div>


<section id="reviews" class="mb-4">
  <div class="card animate-in mb-4">
    <div class="card-header"><i class="fas fa-star mr-2" style="color:var(--warning);"></i>Đánh giá sản phẩm</div>
    <div class="card-body">
      <div class="d-flex align-items-center" style="gap:24px;flex-wrap:wrap;">
        <div class="text-center"><div style="font-size:3rem;font-weight:900;color:var(--warning);"><?= number_format($reviewStats['avg'] ?? 0,1) ?></div><div><?= renderStars($reviewStats['avg'] ?? 0) ?></div><small style="color:var(--muted);"><?= (int)($reviewStats['count'] ?? 0) ?> đánh giá</small></div>
        <div style="flex:1;min-width:220px;">
          <?php for($s=5;$s>=1;$s--): $cnt=(int)($reviewStats['dist'][$s]??0); $pct=($reviewStats['count']??0)>0?round($cnt/($reviewStats['count'])*100):0; ?>
          <div class="d-flex align-items-center mb-1" style="gap:8px;"><span style="width:14px;font-size:.78rem;"><?=$s?></span><i class="fas fa-star" style="color:var(--warning);font-size:.72rem;"></i><div class="progress-line" style="flex:1;height:8px;"><span style="width:<?= $pct ?>%;"></span></div><span style="font-size:.78rem;color:var(--muted);width:20px;"><?= $cnt ?></span></div>
          <?php endfor; ?>
        </div>
      </div>
    </div>
  </div>
  <div class="card animate-in mb-4">
    <div class="card-header"><i class="fas fa-pen-nib mr-2" style="color:var(--accent);"></i>Gửi đánh giá của bạn</div>
    <div class="card-body">
      <form method="POST" action="index.php?url=review/store/<?= (int)$product['id'] ?>">
        <?= csrf_input() ?>
        <div class="form-row"><div class="col-md-5"><div class="float-group"><input class="form-control" name="reviewer_name" placeholder=" " required><label>Tên của bạn *</label></div></div><div class="col-md-7"><div class="mb-3" style="font-weight:800;">Chọn sao: <?php for($i=5;$i>=1;$i--): ?><label class="mr-2"><input type="radio" name="rating" value="<?=$i?>" <?= $i==5?'checked':'' ?>> <?=$i?>★</label><?php endfor; ?></div></div></div>
        <div class="float-group"><textarea class="form-control" name="comment" rows="4" maxlength="500" placeholder=" "></textarea><label>Bình luận</label></div>
        <button class="btn btn-primary" type="submit"><i class="fas fa-paper-plane mr-1"></i>Gửi đánh giá</button>
      </form>
      <hr style="border-color:var(--line);">
      <?php if (empty($reviews)): ?><p class="mb-0" style="color:var(--muted);">Chưa có đánh giá nào.</p><?php else: ?><?php foreach($reviews as $rv): ?><div class="mb-3 pb-3" style="border-bottom:1px solid var(--line);"><strong><?= e($rv['reviewer_name']) ?></strong> <span><?= renderStars($rv['rating']) ?></span><br><small style="color:var(--muted);"><?= e($rv['created_at']) ?></small><p class="mb-0 mt-2"><?= nl2br(e($rv['comment'])) ?></p></div><?php endforeach; ?><?php endif; ?>
    </div>
  </div>
</section>

<?php if (!empty($relatedProducts)): ?>
    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap">
        <div>
            <span class="mini-chip"><i class="fas fa-magic"></i> Gợi ý thông minh</span>
            <h3 class="font-display mt-2 mb-0">Sản phẩm liên quan</h3>
        </div>
        <a href="index.php?url=product" class="btn btn-secondary mt-2 mt-md-0"><i class="fas fa-th-large mr-1"></i>Xem tất cả</a>
    </div>
    <div class="related-grid mb-4">
        <?php foreach ($relatedProducts as $rel): ?>
            <?php $relImg = getImageSrc($rel['image'], $rel['name'], $rel['category_id'] ?? null); ?>
            <div class="product-card animate-in">
                <?php $tag = getProductTag($product); ?>
                <div class="img-wrapper">
                    <?php if ($tag): ?><span class="product-tag" style="color:<?= e($tag['color']) ?>;background:<?= e($tag['bg']) ?>;"><?= e($tag['label']) ?></span><?php endif; ?>
                    <?php if ($relImg): ?><img src="<?= e($relImg) ?>" class="card-img-top" alt="<?= e($rel['name']) ?>"><?php else: ?><div class="img-placeholder"><i class="fas fa-camera"></i></div><?php endif; ?>
                </div>
                <div class="card-body d-flex flex-column">
                    <span class="category-badge"><i class="fas fa-microchip"></i><?= e($rel['category_name'] ?? 'Công nghệ') ?></span>
                    <h5 class="card-title"><?= e($rel['name']) ?></h5>
                    <div class="price mt-2 mb-3"><?= moneyVnd($rel['price']) ?></div>
                    <div class="d-flex flex-wrap mt-auto" style="gap:6px;">
                        <a href="index.php?url=product/show/<?= (int) $rel['id'] ?>" class="btn btn-secondary btn-sm flex-fill"><i class="fas fa-eye mr-1"></i>Chi tiết</a>
                        <a href="index.php?url=cart/add/<?= (int) $rel['id'] ?>" class="btn btn-cart btn-sm"><i class="fas fa-cart-plus"></i></a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
