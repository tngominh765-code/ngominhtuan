<!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LAB 5 & 6 - API Frontend</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#f7f9fc}.card{box-shadow:0 8px 22px rgba(0,0,0,.06);border:0}.log{height:220px;overflow:auto;background:#0b1020;color:#d6e4ff;border-radius:10px;padding:12px;white-space:pre-wrap}.product-img{width:64px;height:64px;object-fit:cover;border-radius:10px;background:#eef}.badge-role{letter-spacing:.04em}</style>
</head>
<body>
<nav class="navbar navbar-expand-lg bg-dark navbar-dark"><div class="container"><a class="navbar-brand" href="index.php?url=lab56">LAB 5&6 Web API</a><span class="navbar-text small">Frontend dùng fetch + JWT + RESTful API</span></div></nav>
<div class="container py-4">
<div class="row g-4">
<div class="col-lg-4">
<div class="card mb-4"><div class="card-body"><h4>1. Tài khoản / JWT</h4>
<div class="mb-2"><input id="loginUser" class="form-control" value="admin" placeholder="username hoặc email"></div>
<div class="mb-2"><input id="loginPass" type="password" class="form-control" value="123456" placeholder="password"></div>
<button class="btn btn-primary" onclick="login()">Đăng nhập API</button>
<button class="btn btn-outline-secondary" onclick="logout()">Đăng xuất</button>
<button class="btn btn-outline-info" onclick="me()">/api/account/me</button>
<div class="mt-3 small">Token: <span id="tokenState" class="badge bg-secondary badge-role">chưa đăng nhập</span></div>
</div></div>
<div class="card mb-4"><div class="card-body"><h4>2. Thêm/Sửa sản phẩm qua API</h4>
<input type="hidden" id="pId"><div class="mb-2"><input id="pName" class="form-control" placeholder="Tên sản phẩm"></div>
<div class="mb-2"><textarea id="pDesc" class="form-control" placeholder="Mô tả"></textarea></div>
<div class="mb-2"><input id="pPrice" type="number" class="form-control" placeholder="Giá"></div>
<div class="mb-2"><select id="pCat" class="form-select"></select></div>
<div class="mb-2"><input id="pImage" class="form-control" placeholder="Tên/URL ảnh, ví dụ uploads/demo.png"></div>
<button class="btn btn-success" onclick="saveProduct()">Lưu bằng POST/PUT</button>
<button class="btn btn-outline-secondary" onclick="clearProductForm()">Làm mới</button>
<hr><label class="form-label">Upload ảnh qua API</label><input id="imageFile" type="file" class="form-control mb-2"><button class="btn btn-outline-primary" onclick="uploadImage()">Upload /api/product/upload</button>
</div></div>
<div class="card"><div class="card-body"><h4>Log API</h4><div id="log" class="log">Sẵn sàng...</div></div></div>
</div>
<div class="col-lg-8">
<div class="card mb-4"><div class="card-body"><div class="d-flex gap-2 flex-wrap align-items-end"><div class="flex-grow-1"><label>Tìm kiếm</label><input id="q" class="form-control" placeholder="Tên sản phẩm"></div><div><label>Danh mục</label><select id="filterCat" class="form-select"><option value="">Tất cả</option></select></div><div><label>Sắp xếp</label><select id="sort" class="form-select"><option value="newest">Mới nhất</option><option value="price_asc">Giá tăng</option><option value="price_desc">Giá giảm</option><option value="name_asc">Tên A-Z</option></select></div><button class="btn btn-dark" onclick="loadProducts()">GET /api/product</button></div></div></div>
<div class="card mb-4"><div class="card-body"><h4>Danh sách sản phẩm từ API</h4><div class="table-responsive"><table class="table align-middle"><thead><tr><th>Ảnh</th><th>Sản phẩm</th><th>Giá</th><th>Danh mục</th><th>Thao tác</th></tr></thead><tbody id="productRows"></tbody></table></div></div></div>
<div class="row g-4"><div class="col-md-6"><div class="card"><div class="card-body"><h4>Giỏ hàng API</h4><button class="btn btn-outline-dark btn-sm" onclick="loadCart()">GET giỏ</button><button class="btn btn-outline-danger btn-sm" onclick="clearCart()">DELETE clear</button><div id="cartBox" class="mt-3 small"></div></div></div></div><div class="col-md-6"><div class="card"><div class="card-body"><h4>Đặt hàng / Thanh toán</h4><input id="oName" class="form-control mb-2" placeholder="Họ tên"><input id="oPhone" class="form-control mb-2" placeholder="SĐT"><input id="oAddress" class="form-control mb-2" placeholder="Địa chỉ"><select id="payMethod" class="form-select mb-2"><option>COD</option><option>bank_transfer</option><option>e_wallet</option></select><button class="btn btn-primary btn-sm" onclick="createOrder()">POST /api/order</button><button class="btn btn-outline-primary btn-sm" onclick="payLastOrder()">POST /api/payment/id</button><button class="btn btn-outline-dark btn-sm" onclick="loadOrders()">GET đơn hàng</button><div id="orderBox" class="mt-3 small"></div></div></div></div></div>
</div></div></div>
<script>
const API = 'index.php?url=api/';
let lastOrderId = null;
function getToken(){return localStorage.getItem('jwt_token') || ''}
function setToken(t){ t ? localStorage.setItem('jwt_token', t) : localStorage.removeItem('jwt_token'); updateTokenState(); }
function updateTokenState(){document.getElementById('tokenState').textContent = getToken() ? 'đã lưu localStorage' : 'chưa đăng nhập'; document.getElementById('tokenState').className='badge '+(getToken()?'bg-success':'bg-secondary')+' badge-role'}
function log(data){document.getElementById('log').textContent = typeof data==='string'?data:JSON.stringify(data,null,2)}
async function api(path, options={}){ options.headers=options.headers||{}; if(!(options.body instanceof FormData)) options.headers['Content-Type']='application/json'; if(getToken()) options.headers['Authorization']='Bearer '+getToken(); const res=await fetch(API+path, options); let data; try{data=await res.json()}catch(e){data=await res.text()} log({status:res.status, data}); if(!res.ok) throw data; return data; }
async function login(){ const data=await api('account/checkLogin',{method:'POST',body:JSON.stringify({username:loginUser.value,password:loginPass.value})}); setToken(data.token); if(data.refresh_token) localStorage.setItem('refresh_token', data.refresh_token); }
function logout(){setToken(''); localStorage.removeItem('refresh_token'); log('Đã xóa token khỏi localStorage')}
async function me(){ await api('account/me'); }
async function loadCategories(){ const cats=await api('category'); [pCat,filterCat].forEach((el,i)=>{const keep=i?'<option value="">Tất cả</option>':''; el.innerHTML=keep+cats.map(c=>`<option value="${c.id}">${c.name}</option>`).join('')}); }
function imgSrc(v){ if(!v) return 'uploads/product-placeholder.svg'; if(v.startsWith('http')) return v; return v.replace(/^\/+/, ''); }
async function loadProducts(){ const qs=new URLSearchParams(); if(q.value) qs.set('name',q.value); if(filterCat.value) qs.set('category_id',filterCat.value); qs.set('sort',sort.value); const data=await api('product&'+qs.toString().replace(/^/,'').replace(/&$/,'')); const rows=(data.data||data).map(p=>`<tr><td><img class="product-img" src="${imgSrc(p.image)}"></td><td><b>${p.name}</b><br><small>${p.description||''}</small></td><td>${Number(p.price).toLocaleString('vi-VN')}đ</td><td>${p.category_name||p.category_id||''}</td><td><button class="btn btn-sm btn-outline-primary" onclick='editProduct(${JSON.stringify(p).replace(/'/g,"&#39;")})'>Sửa</button> <button class="btn btn-sm btn-outline-success" onclick="addCart(${p.id})">Thêm giỏ</button> <button class="btn btn-sm btn-outline-danger" onclick="deleteProduct(${p.id})">Xóa</button></td></tr>`).join(''); productRows.innerHTML=rows||'<tr><td colspan="5">Không có dữ liệu</td></tr>'; }
function editProduct(p){pId.value=p.id;pName.value=p.name;pDesc.value=p.description||'';pPrice.value=p.price;pCat.value=p.category_id||'';pImage.value=p.image||'';scrollTo(0,0)}
function clearProductForm(){pId.value=pName.value=pDesc.value=pPrice.value=pImage.value='';}
async function saveProduct(){ const body={name:pName.value,description:pDesc.value,price:Number(pPrice.value),category_id:Number(pCat.value),image:pImage.value}; await api('product'+(pId.value?'/'+pId.value:''),{method:pId.value?'PUT':'POST',body:JSON.stringify(body)}); clearProductForm(); loadProducts(); }
async function deleteProduct(id){ if(confirm('Xóa sản phẩm?')){await api('product/'+id,{method:'DELETE'}); loadProducts();} }
async function uploadImage(){ const f=imageFile.files[0]; if(!f) return alert('Chọn file ảnh'); const fd=new FormData(); fd.append('image_file',f); const data=await api('product/upload',{method:'POST',body:fd,headers:{}}); pImage.value=data.image||''; }
async function addCart(id){ await api('cart',{method:'POST',body:JSON.stringify({product_id:id,quantity:1})}); loadCart(); }
async function loadCart(){ const cart=await api('cart'); const total=await api('cart/total'); cartBox.innerHTML=cart.map(i=>`${i.name} x ${i.quantity}`).join('<br>')+`<hr><b>Tổng: ${Number(total.total).toLocaleString('vi-VN')}đ</b>`; }
async function clearCart(){ await api('cart/clear',{method:'DELETE'}); loadCart(); }
async function createOrder(){ const data=await api('order',{method:'POST',body:JSON.stringify({name:oName.value,phone:oPhone.value,address:oAddress.value,payment_method:payMethod.value})}); lastOrderId=data.order_id; orderBox.innerHTML='Đơn mới: #'+lastOrderId; }
async function payLastOrder(){ if(!lastOrderId) return alert('Chưa có order_id'); await api('payment/'+lastOrderId,{method:'POST',body:JSON.stringify({payment_method:payMethod.value})}); }
async function loadOrders(){ const data=await api('order'); orderBox.innerHTML=data.map(o=>`#${o.id} - ${o.name||''} - ${o.status||o.order_status||''}`).join('<br>')||'Chưa có đơn'; }
updateTokenState(); loadCategories().then(loadProducts).catch(()=>{});
</script>
</body>
</html>
