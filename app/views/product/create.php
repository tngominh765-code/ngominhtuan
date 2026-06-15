<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="page-header">
    <h2><i class="fas fa-plus-circle mr-2"></i>Thêm sản phẩm mới</h2>
    <p class="mb-0">Điền thông tin chi tiết sản phẩm vào form bên dưới</p>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card animate-in">
            <div class="card-header">
                <i class="fas fa-box mr-2" style="color:var(--primary);"></i>Thông tin sản phẩm
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <strong>Vui lòng sửa các lỗi sau:</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach ($errors as $error): ?>
                                <li><?= htmlspecialchars($error) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form action="index.php?url=product/store" method="POST" enctype="multipart/form-data">
                    <?= csrf_input() ?>
                    <div class="form-group">
                        <label for="name"><i class="fas fa-tag mr-1" style="color:var(--primary);"></i>Tên sản phẩm <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="<?= isset($old['name']) ? htmlspecialchars($old['name']) : '' ?>"
                               placeholder="Nhập tên sản phẩm (10-100 ký tự)" required>
                        <small class="form-text text-muted">Tên phải từ 10 đến 100 ký tự</small>
                    </div>

                    <div class="form-group">
                        <label for="description"><i class="fas fa-align-left mr-1" style="color:var(--primary);"></i>Mô tả</label>
                        <textarea class="form-control" id="description" name="description" rows="4"
                                  placeholder="Mô tả chi tiết sản phẩm..."><?= isset($old['description']) ? htmlspecialchars($old['description']) : '' ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price"><i class="fas fa-coins mr-1" style="color:var(--amber);"></i>Giá (VNĐ) <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="<?= isset($old['price']) ? htmlspecialchars($old['price']) : '' ?>"
                                       placeholder="Nhập giá > 0" min="1" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id"><i class="fas fa-folder mr-1" style="color:var(--cyan);"></i>Danh mục <span class="text-danger">*</span></label>
                                <select class="form-control" id="category_id" name="category_id" required>
                                    <option value="">-- Chọn danh mục --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?= $cat['id'] ?>" <?= (isset($old['category_id']) && $old['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($cat['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label><i class="fas fa-image mr-1" style="color:var(--accent);"></i>Hình ảnh sản phẩm</label>
                        <div class="custom-file">
                            <input type="file" class="custom-file-input" id="image" name="image" accept="image/*">
                            <label class="custom-file-label" for="image">Chọn file hình ảnh...</label>
                        </div>
                        <small class="form-text text-muted">JPG, PNG, GIF, WEBP. Tối đa 5MB</small>
                    </div>

                    <div class="form-group" id="imagePreview" style="display:none;">
                        <label>Xem trước:</label><br>
                        <img id="previewImg" src="" alt="Preview" style="max-width:280px;max-height:200px;border-radius:var(--radius-md);border:1px solid var(--border);object-fit:cover;">
                    </div>

                    <hr style="border-color:var(--border);">
                    <div class="d-flex justify-content-between">
                        <a href="index.php?url=product" class="btn btn-secondary">
                            <i class="fas fa-arrow-left mr-1"></i> Quay lại
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save mr-1"></i> Lưu sản phẩm
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('image').addEventListener('change', function(e) {
    var file = e.target.files[0];
    if (file) {
        var reader = new FileReader();
        reader.onload = function(ev) {
            document.getElementById('previewImg').src = ev.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
