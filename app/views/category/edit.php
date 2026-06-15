<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-pen-fancy mr-2"></i>Chỉnh sửa danh mục</h2>
    <p class="mb-0"><?= htmlspecialchars($category['name']) ?></p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card animate-in">
            <div class="card-header">
                <i class="fas fa-folder mr-2" style="color:var(--primary);"></i>Cập nhật thông tin
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

                <form action="index.php?url=category/update/<?= $category['id'] ?>" method="POST">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label for="name"><i class="fas fa-tag mr-1" style="color:var(--primary);"></i>Tên danh mục <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= htmlspecialchars($category['name']) ?>" required>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left mr-1" style="color:var(--primary);"></i>Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($category['description']) ?></textarea>
                    </div>

                    <hr style="border-color:var(--border);">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?url=category" class="btn btn-secondary"><i class="fas fa-arrow-left mr-1"></i> Quay lại</a>
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save mr-1"></i> Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
