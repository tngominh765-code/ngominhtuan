<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1"><i class="fas fa-code mr-2"></i>API Manager - jQuery RESTful</h1>
            <p class="text-muted mb-0">Quản lý sản phẩm bằng jQuery AJAX, RESTful API và JWT Bearer Token.</p>
        </div>
        <button class="btn btn-outline-primary mt-3 mt-md-0" id="btnReloadProducts" type="button">
            <i class="fas fa-sync-alt mr-1"></i>Tải danh sách
        </button>
    </div>

    <div id="apiAlert" class="alert d-none" role="alert"></div>

    <div class="row">
        <div class="col-lg-4 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-primary text-white">
                    <strong>A. Đăng nhập lấy JWT</strong>
                </div>
                <div class="card-body">
                    <form id="tokenForm" autocomplete="off">
                        <div class="form-group">
                            <label for="apiUsername">Username</label>
                            <input type="text" class="form-control" id="apiUsername" placeholder="admin" required>
                        </div>
                        <div class="form-group">
                            <label for="apiPassword">Password</label>
                            <input type="password" class="form-control" id="apiPassword" placeholder="123456" required>
                        </div>
                        <button type="submit" class="btn btn-primary btn-block">
                            <i class="fas fa-key mr-1"></i>Lấy Token
                        </button>
                    </form>

                    <hr>
                    <div id="tokenStatus" class="small text-muted mb-3">Đang kiểm tra token...</div>
                    <button type="button" class="btn btn-outline-danger btn-sm" id="btnClearToken">
                        <i class="fas fa-trash-alt mr-1"></i>Xóa token
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-8 mb-4">
            <div class="card shadow-sm h-100">
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
                    <strong>B. Danh sách sản phẩm</strong>
                    <span class="badge badge-light" id="productCount">0 sản phẩm</span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width:70px">STT</th>
                                    <th>Tên</th>
                                    <th style="width:150px">Giá</th>
                                    <th style="width:160px">Danh mục</th>
                                    <th style="width:160px">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="productTableBody">
                                <tr><td colspan="5" class="text-center text-muted py-4">Nhấn “Tải danh sách” để xem sản phẩm.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white">
            <strong>C. Form thêm / sửa sản phẩm</strong>
        </div>
        <div class="card-body">
            <form id="productForm">
                <input type="hidden" id="editId">
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="productName">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="productName" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="productPrice">Giá</label>
                        <input type="number" min="1" step="1000" class="form-control" id="productPrice" required>
                    </div>
                    <div class="form-group col-md-3">
                        <label for="categoryId">Category ID</label>
                        <input type="number" min="1" class="form-control" id="categoryId" required>
                    </div>
                </div>
                <div class="form-group">
                    <label for="productDescription">Mô tả</label>
                    <textarea class="form-control" id="productDescription" rows="4"></textarea>
                </div>
                <div class="d-flex flex-wrap align-items-center">
                    <button type="submit" class="btn btn-success mr-2 mb-2">
                        <i class="fas fa-save mr-1"></i>Lưu sản phẩm
                    </button>
                    <button type="button" class="btn btn-outline-secondary mb-2" id="btnResetForm">
                        <i class="fas fa-undo mr-1"></i>Reset form
                    </button>
                    <span class="ml-md-3 text-muted mb-2" id="formMode">Chế độ: thêm mới</span>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    const API_PRODUCT_URL = 'index.php?url=api/product';
    const API_LOGIN_URL = 'index.php?url=api/account/checkLogin';

    function getToken() {
        return localStorage.getItem('jwt_token') || '';
    }

    function showAlert(message, type) {
        $('#apiAlert')
            .removeClass('d-none alert-success alert-danger alert-warning alert-info')
            .addClass('alert-' + (type || 'info'))
            .html(message);
    }

    function hideAlert() {
        $('#apiAlert').addClass('d-none').html('');
    }

    function updateTokenStatus() {
        const token = getToken();
        if (token) {
            $('#tokenStatus').html('<span class="text-success font-weight-bold">✅ Đã lấy token thành công</span><br><code>' + token.substring(0, 30) + '...</code>');
        } else {
            $('#tokenStatus').html('<span class="text-muted">Chưa có token trong localStorage.</span>');
        }
    }

    function formatPrice(price) {
        const number = Number(price || 0);
        return number.toLocaleString('vi-VN') + ' đ';
    }

    function normalizeErrors(xhr) {
        if (xhr.status === 401) {
            return 'Token hết hạn hoặc không hợp lệ. Vui lòng lấy token mới.';
        }
        if (xhr.status === 422) {
            const response = xhr.responseJSON || {};
            const errors = response.errors || response.error || response.message || 'Dữ liệu không hợp lệ.';
            if (typeof errors === 'object') {
                return Object.keys(errors).map(function (key) { return key + ': ' + errors[key]; }).join('<br>');
            }
            return errors;
        }
        if (xhr.status === 0) {
            return 'Lỗi kết nối';
        }
        const response = xhr.responseJSON || {};
        return response.error || response.message || 'Có lỗi xảy ra. HTTP ' + xhr.status;
    }

    function renderProducts(products) {
        const rows = [];
        if (!Array.isArray(products) || products.length === 0) {
            $('#productTableBody').html('<tr><td colspan="5" class="text-center text-muted py-4">Chưa có sản phẩm.</td></tr>');
            $('#productCount').text('0 sản phẩm');
            return;
        }

        products.forEach(function (product, index) {
            rows.push(
                '<tr>' +
                    '<td>' + (index + 1) + '</td>' +
                    '<td><strong>' + $('<div>').text(product.name || '').html() + '</strong></td>' +
                    '<td>' + formatPrice(product.price) + '</td>' +
                    '<td>' + $('<div>').text(product.category_name || ('#' + (product.category_id || ''))).html() + '</td>' +
                    '<td>' +
                        '<button type="button" class="btn btn-sm btn-warning mr-1 btn-edit" data-id="' + product.id + '"><i class="fas fa-edit"></i> Sửa</button>' +
                        '<button type="button" class="btn btn-sm btn-danger btn-delete" data-id="' + product.id + '"><i class="fas fa-trash"></i> Xóa</button>' +
                    '</td>' +
                '</tr>'
            );
        });
        $('#productTableBody').html(rows.join(''));
        $('#productCount').text(products.length + ' sản phẩm');
    }

    function loadProducts() {
        hideAlert();
        $('#productTableBody').html('<tr><td colspan="5" class="text-center text-muted py-4">Đang tải...</td></tr>');
        $.ajax({
            url: API_PRODUCT_URL,
            method: 'GET',
            dataType: 'json',
            success: function (data) {
                renderProducts(data);
            },
            error: function (xhr) {
                $('#productTableBody').html('<tr><td colspan="5" class="text-center text-danger py-4">Không tải được danh sách.</td></tr>');
                showAlert(normalizeErrors(xhr), 'danger');
            }
        });
    }

    function resetForm() {
        $('#editId').val('');
        $('#productName').val('');
        $('#productDescription').val('');
        $('#productPrice').val('');
        $('#categoryId').val('');
        $('#formMode').text('Chế độ: thêm mới');
    }

    $('#tokenForm').on('submit', function (event) {
        event.preventDefault();
        hideAlert();
        $.ajax({
            url: API_LOGIN_URL,
            method: 'POST',
            contentType: 'application/json',
            dataType: 'json',
            data: JSON.stringify({
                username: $('#apiUsername').val(),
                password: $('#apiPassword').val()
            }),
            success: function (data) {
                if (data.token) {
                    localStorage.setItem('jwt_token', data.token);
                    updateTokenStatus();
                    showAlert('✅ Đã lấy token thành công', 'success');
                } else {
                    showAlert('Response không có token.', 'warning');
                }
            },
            error: function (xhr) {
                showAlert(normalizeErrors(xhr), 'danger');
            }
        });
    });

    $('#btnClearToken').on('click', function () {
        localStorage.removeItem('jwt_token');
        updateTokenStatus();
        showAlert('Đã xóa token khỏi localStorage.', 'info');
    });

    $('#btnReloadProducts').on('click', loadProducts);
    $('#btnResetForm').on('click', resetForm);

    $('#productTableBody').on('click', '.btn-edit', function () {
        const id = $(this).data('id');
        hideAlert();
        $.ajax({
            url: API_PRODUCT_URL + '/' + id,
            method: 'GET',
            dataType: 'json',
            success: function (product) {
                $('#editId').val(product.id || id);
                $('#productName').val(product.name || '');
                $('#productDescription').val(product.description || '');
                $('#productPrice').val(product.price || '');
                $('#categoryId').val(product.category_id || '');
                $('#formMode').text('Chế độ: đang sửa ID #' + (product.id || id));
                $('html, body').animate({ scrollTop: $('#productForm').offset().top - 120 }, 250);
            },
            error: function (xhr) {
                showAlert(normalizeErrors(xhr), 'danger');
            }
        });
    });

    $('#productTableBody').on('click', '.btn-delete', function () {
        const id = $(this).data('id');
        const token = getToken();
        if (!token) {
            showAlert('Vui lòng lấy token trước khi xóa.', 'warning');
            return;
        }
        if (!confirm('Bạn chắc chắn muốn xóa sản phẩm #' + id + '?')) return;

        $.ajax({
            url: API_PRODUCT_URL + '/' + id,
            method: 'DELETE',
            headers: { Authorization: 'Bearer ' + token },
            success: function () {
                showAlert('Đã xóa sản phẩm thành công.', 'success');
                loadProducts();
            },
            error: function (xhr) {
                showAlert(normalizeErrors(xhr), 'danger');
            }
        });
    });

    $('#productForm').on('submit', function (event) {
        event.preventDefault();
        const token = getToken();
        if (!token) {
            showAlert('Vui lòng lấy token trước khi lưu sản phẩm.', 'warning');
            return;
        }

        const id = $('#editId').val();
        const payload = {
            name: $('#productName').val(),
            description: $('#productDescription').val(),
            price: Number($('#productPrice').val()),
            category_id: Number($('#categoryId').val()),
            image: ''
        };

        $.ajax({
            url: id ? (API_PRODUCT_URL + '/' + id) : API_PRODUCT_URL,
            method: id ? 'PUT' : 'POST',
            contentType: 'application/json',
            dataType: 'json',
            headers: { Authorization: 'Bearer ' + token },
            data: JSON.stringify(payload),
            success: function () {
                showAlert(id ? 'Đã cập nhật sản phẩm thành công.' : 'Đã thêm sản phẩm thành công.', 'success');
                resetForm();
                loadProducts();
            },
            error: function (xhr) {
                showAlert(normalizeErrors(xhr), 'danger');
            }
        });
    });

    updateTokenStatus();
    loadProducts();
});
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
