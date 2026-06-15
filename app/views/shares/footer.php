    </div>
</div>

<footer class="footer">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6 mb-4">
                <h5 class="font-display"><i class="fas fa-atom mr-2" style="color:var(--accent);"></i>NovaTech</h5>
                <p>Cửa hàng công nghệ giao diện tương lai: tìm kiếm nhanh, lọc thông minh, wishlist, so sánh, voucher, quản lý đơn và dashboard vận hành.</p>
                <div class="social-icons mt-3">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>

            <div class="col-lg-2 col-md-6 mb-4">
                <h5>Điều hướng</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><a href="index.php?url=dashboard">Trung tâm</a></li>
                    <li class="mb-2"><a href="index.php?url=product">Sản phẩm</a></li>
                    <li class="mb-2"><a href="index.php?url=category">Danh mục</a></li>
                    <li class="mb-2"><a href="index.php?url=order">Đơn hàng</a></li>
                    <li class="mb-2"><a href="index.php?url=cart">Giỏ hàng</a></li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Tính năng đã thêm</h5>
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-heart mr-2" style="width:14px;color:var(--rose);"></i>Wishlist sản phẩm</li>
                    <li class="mb-2"><i class="fas fa-balance-scale mr-2" style="width:14px;color:var(--cyan);"></i>So sánh tối đa 4 sản phẩm</li>
                    <li class="mb-2"><i class="fas fa-ticket-alt mr-2" style="width:14px;color:var(--accent);"></i>Voucher FUTURE10, VIP15, TECH500</li>
                    <li class="mb-2"><i class="fas fa-shield-alt mr-2" style="width:14px;color:var(--warning);"></i>Trạng thái đơn hàng</li>
                </ul>
            </div>

            <div class="col-lg-3 col-md-6 mb-4">
                <h5>Cập nhật ưu đãi</h5>
                <p>Nhập email để nhận thông tin sản phẩm mới và đợt giảm giá.</p>
                <div class="input-group mt-3">
                    <input type="email" class="form-control" placeholder="Email của bạn" id="newsletterEmail">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="button" id="newsletterBtn"><i class="fas fa-paper-plane"></i></button>
                    </div>
                </div>
            </div>
        </div>

        <hr>

        <div class="d-flex flex-column flex-md-row justify-content-between align-items-center">
            <p class="mb-2 mb-md-0" style="font-size:.8rem;">&copy; <?= date('Y') ?> NovaTech Store. Future-ready PHP MVC.</p>
            <div style="font-size:.8rem;">
                <i class="fas fa-lock mr-1" style="color:var(--accent);"></i> Thanh toán an toàn
                &nbsp;|&nbsp;
                <i class="fas fa-rocket mr-1" style="color:var(--cyan);"></i> Giao hàng toàn quốc
            </div>
        </div>
    </div>
</footer>

<div class="support-fab" aria-label="Hỗ trợ nhanh">
    <a href="index.php?url=cart" title="Giỏ hàng"><i class="fas fa-shopping-bag"></i></a>
    <a href="index.php?url=order/checkout" title="Thanh toán"><i class="fas fa-credit-card"></i></a>
    <a href="#" title="Chat hỗ trợ" onclick="showToast('Hỗ trợ NovaTech đang sẵn sàng.');return false;"><i class="fas fa-headset"></i></a>
</div>

<div class="modal fade" id="quickViewModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title font-display"><i class="fas fa-bolt mr-2" style="color:var(--accent);"></i>Xem nhanh sản phẩm</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="row align-items-center">
                    <div class="col-md-5 mb-3 mb-md-0">
                        <img src="" alt="" id="quickViewImage" class="quick-view-img">
                    </div>
                    <div class="col-md-7">
                        <span class="category-badge" id="quickViewCategory"><i class="fas fa-layer-group"></i></span>
                        <h3 id="quickViewName" class="font-weight-bold mt-2"></h3>
                        <div class="price mb-3" id="quickViewPrice"></div>
                        <p id="quickViewDescription" style="color:var(--text-secondary);line-height:1.8;"></p>
                        <div class="d-flex flex-wrap" style="gap:8px;">
                            <a href="#" id="quickViewDetail" class="btn btn-secondary"><i class="fas fa-eye mr-1"></i>Chi tiết</a>
                            <a href="#" id="quickViewCart" class="btn btn-cart"><i class="fas fa-cart-plus mr-1"></i>Thêm giỏ</a>
                            <a href="#" id="quickViewWishlist" class="btn btn-outline-danger"><i class="fas fa-heart mr-1"></i>Yêu thích</a>
                            <a href="#" id="quickViewCompare" class="btn btn-outline-secondary"><i class="fas fa-balance-scale mr-1"></i>So sánh</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="modal fade confirm-premium" id="confirmModal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" style="max-width:400px" role="document">
    <div class="modal-content">
      <div class="modal-body text-center p-4">
        <div style="width:56px;height:56px;border-radius:50%;background:rgba(255,59,107,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
          <i class="fas fa-exclamation-triangle" style="color:var(--rose);font-size:1.4rem;"></i>
        </div>
        <h5 class="font-weight-bold mb-2" id="confirmTitle">Xác nhận</h5>
        <p class="mb-0" style="color:var(--text-secondary);" id="confirmMsg"></p>
      </div>
      <div class="modal-footer border-0 justify-content-center pb-4" style="gap:10px;">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-danger" id="confirmOk">Xác nhận</button>
      </div>
    </div>
  </div>
</div>

<div class="toast-lite" id="toastLite"><i class="fas fa-sparkles mr-2" style="color:var(--accent);"></i><span></span></div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js"></script>

<script>
(function () {
    var storedTheme = localStorage.getItem('novatech-theme') || 'dark';
    document.documentElement.setAttribute('data-theme', storedTheme);
    updateThemeIcon(storedTheme);

    function updateThemeIcon(theme) {
        var icon = document.querySelector('#themeToggle i');
        if (!icon) return;
        icon.className = theme === 'dark' ? 'fas fa-moon' : 'fas fa-sun';
    }

    window.showToast = function (message) {
        var toast = $('#toastLite');
        toast.find('span').text(message);
        toast.stop(true, true).fadeIn(160).delay(2100).fadeOut(240);
    };

    $(function () {
        setTimeout(function () { $('.alert').fadeOut(350); }, 4400);

        $('#themeToggle').on('click', function () {
            var current = document.documentElement.getAttribute('data-theme') || 'dark';
            var next = current === 'dark' ? 'light' : 'dark';
            document.documentElement.setAttribute('data-theme', next);
            localStorage.setItem('novatech-theme', next);
            updateThemeIcon(next);
            showToast(next === 'dark' ? 'Đã bật giao diện Dark Future' : 'Đã bật giao diện Light Tech');
        });

        $('.custom-file-input').on('change', function () {
            var fileName = $(this).val().split('\\').pop();
            $(this).next('.custom-file-label').html(fileName || 'Chọn file...');
        });

        $('.animate-in').each(function (i) {
            $(this).css('animation-delay', (i * 0.04) + 's');
        });

        $('[data-quick-view]').on('click', function () {
            var data = $(this).data();
            $('#quickViewImage').attr('src', data.image || 'uploads/product-placeholder.svg').attr('alt', data.name || 'Product');
            $('#quickViewCategory').html('<i class="fas fa-layer-group"></i> ' + (data.category || 'Công nghệ'));
            $('#quickViewName').text(data.name || 'Sản phẩm');
            $('#quickViewPrice').text(data.price || '');
            $('#quickViewDescription').text(data.description || 'Chưa có mô tả chi tiết.');
            $('#quickViewDetail').attr('href', 'index.php?url=product/show/' + data.id);
            $('#quickViewCart').attr('href', 'index.php?url=cart/add/' + data.id);
            $('#quickViewWishlist').attr('href', 'index.php?url=wishlist/add/' + data.id);
            $('#quickViewCompare').attr('href', 'index.php?url=compare/add/' + data.id);
            $('#quickViewModal').modal('show');
        });

        $('.copy-text').on('click', function () {
            var value = $(this).data('copy') || $(this).text();
            if (navigator.clipboard) {
                navigator.clipboard.writeText(value);
                showToast('Đã sao chép: ' + value);
            }
        });

        $('#newsletterBtn').on('click', function () {
            var email = ($('#newsletterEmail').val() || '').trim();
            showToast(email ? 'Đã ghi nhận email ưu đãi.' : 'Bạn hãy nhập email trước nhé.');
        });

        function updatePaymentInfo() {
            var method = $('input[name="payment_method"]:checked').val() || 'COD';
            $('.payment-info').removeClass('active');
            $('#paymentInfo' + method).addClass('active');
        }
        $('input[name="payment_method"]').on('change', updatePaymentInfo);
        updatePaymentInfo();
    });

    // ─── JWT Token Management (Bài 6) ───
    window.NovaTechAPI = {
        getToken: function () {
            return localStorage.getItem('jwtToken');
        },
        setToken: function (token) {
            localStorage.setItem('jwtToken', token);
        },
        removeToken: function () {
            localStorage.removeItem('jwtToken');
        },
        fetch: function (url, options) {
            options = options || {};
            options.headers = options.headers || {};
            var token = this.getToken();
            if (token) {
                options.headers['Authorization'] = 'Bearer ' + token;
            }
            if (!options.headers['Content-Type'] && options.method && options.method !== 'GET') {
                options.headers['Content-Type'] = 'application/json';
            }
            return fetch(url, options).then(function (response) {
                if (response.status === 401) {
                    NovaTechAPI.removeToken();
                }
                return response;
            });
        }
    };
})();
</script>



<script>
(function() {
  window.showConfirm = function(title, msg, onOk) {
    document.getElementById('confirmTitle').textContent = title || 'Xác nhận';
    document.getElementById('confirmMsg').textContent = msg || 'Bạn có chắc muốn tiếp tục?';
    $('#confirmModal').modal('show');
    document.getElementById('confirmOk').onclick = function() {
      $('#confirmModal').modal('hide');
      if (typeof onOk === 'function') onOk();
    };
  };

  document.addEventListener('click', function(e) {
    var btn = e.target.closest('.btn-cart, .btn-primary, .js-ripple');
    if (!btn) return;
    var rect = btn.getBoundingClientRect();
    var size = Math.max(rect.width, rect.height);
    var x = e.clientX - rect.left - size / 2;
    var y = e.clientY - rect.top - size / 2;
    var rip = document.createElement('span');
    rip.className = 'ripple';
    rip.style.cssText = 'width:'+size+'px;height:'+size+'px;left:'+x+'px;top:'+y+'px;';
    btn.appendChild(rip);
    rip.addEventListener('animationend', function(){ rip.remove(); });
  });

  function animateCount(el) {
    var raw = el.getAttribute('data-target') || el.textContent;
    var numStr = raw.replace(/[^0-9]/g, '');
    var suffix = raw.replace(/[0-9.,]/g, '').trim();
    var target = parseInt(numStr, 10);
    if (isNaN(target) || target === 0) return;
    var duration = 1200, start = null;
    function step(ts) {
      if (!start) start = ts;
      var progress = Math.min((ts - start) / duration, 1);
      var ease = 1 - Math.pow(1 - progress, 3);
      var current = Math.floor(ease * target);
      el.textContent = current.toLocaleString('vi-VN') + (suffix ? ' ' + suffix : '');
      if (progress < 1) requestAnimationFrame(step);
    }
    requestAnimationFrame(step);
  }
  if ('IntersectionObserver' in window) {
    var obs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) { if (entry.isIntersecting) { animateCount(entry.target); obs.unobserve(entry.target); } });
    }, { threshold: 0.3 });
    document.querySelectorAll('.stat-value').forEach(function(el) { el.setAttribute('data-target', el.textContent); obs.observe(el); });
  }

  function loadLazyImages() {
    if (!('IntersectionObserver' in window)) {
      document.querySelectorAll('img[data-src]').forEach(function(img){ img.src = img.dataset.src; img.classList.add('loaded'); });
      return;
    }
    var imgObs = new IntersectionObserver(function(entries) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          var img = entry.target;
          img.src = img.dataset.src;
          img.onload = function(){ img.classList.add('loaded'); };
          imgObs.unobserve(img);
        }
      });
    }, { rootMargin: '100px' });
    document.querySelectorAll('img[data-src]').forEach(function(img){ imgObs.observe(img); });
  }
  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', loadLazyImages); else loadLazyImages();

  $(function() {
    var $input = $('input[name="q"].form-control').first();
    if (!$input.length) return;
    var $wrap = $input.closest('.input-group, form').first();
    $wrap.css('position','relative');
    var $dd = $('<div class="search-dropdown" id="searchDropdown"></div>').appendTo($wrap);
    var timer;
    function esc(s){ return $('<div>').text(s || '').html(); }
    $input.on('input', function() {
      clearTimeout(timer);
      var q = $(this).val().trim();
      if (q.length < 2) { $dd.hide(); return; }
      timer = setTimeout(function() {
        $.getJSON('index.php?url=search/suggest&q=' + encodeURIComponent(q), function(data) {
          if (!data.length) { $dd.hide(); return; }
          $dd.html(data.map(function(d) {
            return '<a class="search-item" href="' + esc(d.url) + '">' +
              '<img src="' + esc(d.img) + '" alt="">' +
              '<div class="search-item-info"><div class="name">' + esc(d.name) + '</div>' +
              '<div class="price">' + esc(d.price) + ' <span style="color:var(--muted);font-weight:400;">• ' + esc(d.category) + '</span></div></div></a>';
          }).join('')).show();
        });
      }, 280);
    }).on('blur', function(){ setTimeout(function(){ $dd.hide(); }, 180); });
  });
})();
</script>

</body>
</html>
