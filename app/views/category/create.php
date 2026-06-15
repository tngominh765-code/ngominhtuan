<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-folder-plus mr-2"></i>Thêm danh mục mới</h2>
    <p class="mb-0">Tạo danh mục để phân loại sản phẩm</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card animate-in">
            <div class="card-header">
                <i class="fas fa-folder mr-2" style="color:var(--primary);"></i>Thông tin danh mục
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="index.php?url=category/store" method="POST">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label for="name"><i class="fas fa-tag mr-1" style="color:var(--primary);"></i>Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>"
                               placeholder="VD: Điện thoại, Laptop..." required>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left mr-1" style="color:var(--primary);"></i>Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"
                                  placeholder="Mô tả ngắn về danh mục..."><?= isset($old['description']) ? htmlspecialchars($old['description']) : '' ?></textarea>
                    </div>

                    <hr style="border-color:var(--border);">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?url=category" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Lưu danh mục</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
