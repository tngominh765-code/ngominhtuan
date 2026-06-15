<?php include BASE_PATH . '/app/views/shares/header.php'; ?>
<div class="empty-state" style="position:relative;overflow:hidden;min-height:420px;display:flex;flex-direction:column;justify-content:center;">
  <div style="position:absolute;inset:auto -60px -80px auto;width:260px;height:260px;border-radius:50%;background:radial-gradient(circle,rgba(0,245,200,.18),transparent 65%);animation:auroraShift 8s ease infinite;"></div>
  <div class="font-display" style="font-family:Orbitron,Space Grotesk,sans-serif;font-size:clamp(5rem,18vw,12rem);font-weight:900;line-height:.85;background:var(--grad-main,var(--gradient-primary));-webkit-background-clip:text;color:transparent;">404</div>
  <h2 class="font-display mt-3">Trang không tồn tại</h2>
  <p>URL bạn nhập không tìm thấy trong hệ thống NovaTech.</p>
  <div class="d-flex justify-content-center flex-wrap" style="gap:10px"><a href="index.php?url=dashboard" class="btn btn-primary"><i class="fas fa-home mr-1"></i>Về trang chủ</a><a href="index.php?url=product" class="btn btn-secondary"><i class="fas fa-store mr-1"></i>Xem sản phẩm</a></div>
</div>
<?php include BASE_PATH . '/app/views/shares/footer.php'; ?>
