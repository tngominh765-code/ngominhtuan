<?php include BASE_PATH . '/app/views/shares/header.php'; ?>

<div class="container py-4">
    <div class="mb-4">
        <h1 class="h3 mb-1"><i class="fas fa-key mr-2"></i>JWT Demo - Bài 6.4</h1>
        <p class="text-muted mb-0">Lấy JWT từ browser, giải mã payload và gọi API bảo mật bằng Bearer token.</p>
    </div>

    <div id="jwtAlert" class="alert d-none" role="alert"></div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white"><strong>Bước 1 — Đăng nhập lấy Token</strong></div>
        <div class="card-body">
            <form id="jwtLoginForm" autocomplete="off">
                <div class="form-row">
                    <div class="form-group col-md-5">
                        <label for="jwtUsername">Username</label>
                        <input type="text" class="form-control" id="jwtUsername" placeholder="admin" required>
                    </div>
                    <div class="form-group col-md-5">
                        <label for="jwtPassword">Password</label>
                        <input type="password" class="form-control" id="jwtPassword" placeholder="123456" required>
                    </div>
                    <div class="form-group col-md-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary btn-block">Đăng nhập</button>
                    </div>
                </div>
            </form>
            <label for="jwtTokenBox">JWT Token</label>
            <textarea id="jwtTokenBox" class="form-control" rows="4" readonly placeholder="Token sẽ hiển thị ở đây để copy..."></textarea>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-info text-white"><strong>Bước 2 — Giải mã & Hiển thị thông tin Token</strong></div>
        <div class="card-body">
            <button type="button" id="btnDecodeToken" class="btn btn-info mb-3">
                <i class="fas fa-search mr-1"></i>Giải mã Token hiện tại
            </button>
            <div id="tokenCountdown" class="mb-3"></div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead class="thead-light">
                        <tr>
                            <th>username</th>
                            <th>fullname</th>
                            <th>role</th>
                            <th>Thời gian tạo (iat)</th>
                            <th>Thời gian hết hạn (exp)</th>
                        </tr>
                    </thead>
                    <tbody id="tokenPayloadTable">
                        <tr><td colspan="5" class="text-center text-muted">Chưa giải mã token.</td></tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-dark text-white"><strong>Bước 3 — Gọi API có bảo vệ</strong></div>
        <div class="card-body">
            <div class="form-row align-items-end">
                <div class="form-group col-md-6">
                    <label for="apiChoice">Chọn API</label>
                    <select id="apiChoice" class="form-control">
                        <option value="get-product">GET /api/product</option>
                        <option value="get-order">GET /api/order</option>
                        <option value="post-product">POST /api/product (tạo test)</option>
                    </select>
                </div>
                <div class="form-group col-md-3">
                    <button type="button" id="btnCallApi" class="btn btn-success btn-block">Gọi API với Token</button>
                </div>
                <div class="form-group col-md-3">
                    <button type="button" id="btnCallNoToken" class="btn btn-outline-danger btn-block">Gọi không có Token</button>
                </div>
            </div>
            <h6>Request gửi đi</h6>
            <pre class="bg-dark text-light p-3 rounded" id="requestPreview">Chưa có request.</pre>
            <h6>Response nhận về</h6>
            <pre class="bg-dark text-light p-3 rounded" id="responsePreview">Chưa có response.</pre>
        </div>
    </div>

    <div class="card shadow-sm mb-4">
        <div class="card-header bg-secondary text-white"><strong>Bước 4 — Quản lý Token</strong></div>
        <div class="card-body">
            <p id="tokenStorageStatus" class="mb-3"></p>
            <button type="button" id="btnClearJwt" class="btn btn-outline-danger mr-2 mb-2">
                <i class="fas fa-trash-alt mr-1"></i>Xóa Token
            </button>
            <button type="button" id="btnCopyJwt" class="btn btn-outline-primary mb-2">
                <i class="fas fa-copy mr-1"></i>Copy Token
            </button>
            <hr>
            <p class="mb-1"><strong>JWT là gì?</strong> JWT là chuỗi token gồm header, payload và chữ ký, dùng để chứng minh người dùng đã đăng nhập.</p>
            <p class="mb-1"><strong>Bearer token dùng để làm gì?</strong> Browser gửi token trong header <code>Authorization: Bearer &lt;token&gt;</code> để API xác thực request.</p>
            <p class="mb-0"><strong>Khi nào hết hạn?</strong> Token của NovaTech được cấu hình hết hạn sau 1 giờ, dựa trên trường <code>exp</code> trong payload.</p>
        </div>
    </div>
</div>

<script>
(function () {
    const API_LOGIN_URL = 'index.php?url=api/account/checkLogin';
    let countdownTimer = null;

    function getToken() {
        return localStorage.getItem('jwt_token') || '';
    }

    function setAlert(message, type) {
        const alertBox = document.getElementById('jwtAlert');
        alertBox.className = 'alert alert-' + (type || 'info');
        alertBox.innerHTML = message;
    }

    function hideAlert() {
        const alertBox = document.getElementById('jwtAlert');
        alertBox.className = 'alert d-none';
        alertBox.innerHTML = '';
    }

    function htmlEscape(value) {
        return String(value === undefined || value === null ? '' : value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    function decodeToken(token) {
        const payload = JSON.parse(atob(token.split('.')[1].replace(/-/g, '+').replace(/_/g, '/')));
        return payload;
    }

    function formatDate(timestamp) {
        if (!timestamp) return '';
        return new Date(timestamp * 1000).toLocaleString('vi-VN');
    }

    function renderStorageStatus() {
        const token = getToken();
        document.getElementById('jwtTokenBox').value = token;
        document.getElementById('tokenStorageStatus').innerHTML = token
            ? '<span class="badge badge-success">Token đang lưu trong localStorage</span> <code>' + htmlEscape(token.substring(0, 30)) + '...</code>'
            : '<span class="badge badge-secondary">Chưa có token</span>';
    }

    function renderPayload(payload) {
        document.getElementById('tokenPayloadTable').innerHTML = '<tr>' +
            '<td>' + htmlEscape(payload.username) + '</td>' +
            '<td>' + htmlEscape(payload.fullname) + '</td>' +
            '<td><span class="badge badge-' + (payload.role === 'admin' ? 'success' : 'secondary') + '">' + htmlEscape(payload.role) + '</span></td>' +
            '<td>' + htmlEscape(formatDate(payload.iat)) + '</td>' +
            '<td>' + htmlEscape(formatDate(payload.exp)) + '</td>' +
            '</tr>';
    }

    function startCountdown(payload) {
        const countdown = document.getElementById('tokenCountdown');
        if (countdownTimer) clearInterval(countdownTimer);

        function tick() {
            const remain = Math.max(0, Math.floor((payload.exp * 1000 - Date.now()) / 1000));
            const minutes = Math.floor(remain / 60);
            const seconds = remain % 60;
            if (remain <= 0) {
                countdown.innerHTML = '<span class="badge badge-danger">Token đã hết hạn</span>';
                clearInterval(countdownTimer);
            } else {
                countdown.innerHTML = '<span class="badge badge-success">Token còn hiệu lực: ' + minutes + ' phút ' + seconds + ' giây</span>';
            }
        }

        tick();
        countdownTimer = setInterval(tick, 1000);
    }

    function decodeCurrentToken() {
        const token = getToken();
        if (!token) {
            setAlert('Chưa có token để giải mã.', 'warning');
            return;
        }
        try {
            const payload = decodeToken(token);
            renderPayload(payload);
            startCountdown(payload);
            hideAlert();
        } catch (error) {
            setAlert('Token không đúng định dạng JWT.', 'danger');
        }
    }

    function getApiConfig(choice) {
        if (choice === 'get-order') {
            return { method: 'GET', url: 'index.php?url=api/order', body: null };
        }
        if (choice === 'post-product') {
            return {
                method: 'POST',
                url: 'index.php?url=api/product',
                body: {
                    name: 'Sản phẩm test API ' + new Date().toLocaleTimeString('vi-VN'),
                    description: 'Tạo thử từ trang JWT Demo.',
                    price: 1000000,
                    category_id: 1,
                    image: ''
                }
            };
        }
        return { method: 'GET', url: 'index.php?url=api/product', body: null };
    }

    async function callApi(withToken) {
        hideAlert();
        const config = getApiConfig(document.getElementById('apiChoice').value);
        const token = getToken();
        const headers = {};
        if (config.body) headers['Content-Type'] = 'application/json';
        if (withToken && token) headers['Authorization'] = 'Bearer ' + token;

        document.getElementById('requestPreview').textContent = JSON.stringify({
            method: config.method,
            url: config.url,
            headers: Object.assign({}, headers, headers.Authorization ? { Authorization: 'Bearer ' + token.substring(0, 20) + '...' } : {}),
            body: config.body
        }, null, 2);

        try {
            const response = await fetch(config.url, {
                method: config.method,
                headers: headers,
                body: config.body ? JSON.stringify(config.body) : undefined
            });
            let data;
            try {
                data = await response.json();
            } catch (error) {
                data = { error: 'Response không phải JSON' };
            }
            document.getElementById('responsePreview').textContent = 'HTTP status: ' + response.status + '\n' + JSON.stringify(data, null, 2);
        } catch (error) {
            document.getElementById('responsePreview').textContent = 'Lỗi kết nối: ' + error.message;
        }
    }

    document.getElementById('jwtLoginForm').addEventListener('submit', async function (event) {
        event.preventDefault();
        hideAlert();
        try {
            const response = await fetch(API_LOGIN_URL, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    username: document.getElementById('jwtUsername').value,
                    password: document.getElementById('jwtPassword').value
                })
            });
            const data = await response.json();
            if (!response.ok || !data.token) {
                setAlert(data.error || 'Đăng nhập thất bại.', 'danger');
                return;
            }
            localStorage.setItem('jwt_token', data.token);
            renderStorageStatus();
            setAlert('Đã lấy JWT token thành công.', 'success');
        } catch (error) {
            setAlert('Lỗi kết nối: ' + error.message, 'danger');
        }
    });

    document.getElementById('btnDecodeToken').addEventListener('click', decodeCurrentToken);
    document.getElementById('btnCallApi').addEventListener('click', function () { callApi(true); });
    document.getElementById('btnCallNoToken').addEventListener('click', function () { callApi(false); });
    document.getElementById('btnClearJwt').addEventListener('click', function () {
        localStorage.removeItem('jwt_token');
        renderStorageStatus();
        document.getElementById('tokenPayloadTable').innerHTML = '<tr><td colspan="5" class="text-center text-muted">Chưa giải mã token.</td></tr>';
        document.getElementById('tokenCountdown').innerHTML = '';
        setAlert('Đã xóa token.', 'info');
    });
    document.getElementById('btnCopyJwt').addEventListener('click', async function () {
        const token = getToken();
        if (!token) {
            setAlert('Chưa có token để copy.', 'warning');
            return;
        }
        await navigator.clipboard.writeText(token);
        setAlert('Đã copy token vào clipboard.', 'success');
    });

    renderStorageStatus();
})();
</script>

<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
