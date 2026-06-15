<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaTech - Danh Sách Sản Phẩm</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom thanh cuộn hệ thống chuẩn Dark Tech */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</head>
<body class="bg-[#090d16] text-[#94a3b8] flex flex-col min-h-screen antialiased selection:bg-cyan-500 selection:text-slate-900">

    <header class="bg-[#030712]/90 backdrop-blur-xl border-b border-slate-800/60 sticky top-0 z-50 px-4 py-2">
        <div class="max-w-[1600px] mx-auto flex flex-col xl:flex-row items-center justify-between gap-3">
            
            <div class="flex items-center gap-2.5 shrink-0">
                <div class="bg-gradient-to-tr from-blue-600 to-cyan-400 p-2 rounded-xl text-white shadow-lg shadow-cyan-500/10">
                    <i data-lucide="cpu" class="w-4 h-4"></i>
                </div>
                <div>
                    <span class="text-sm font-bold tracking-wider text-white">Nova<span class="text-cyan-400">Tech</span></span>
                    <span class="block text-[8px] text-cyan-500/80 font-mono tracking-widest uppercase -mt-1">Luxury Tech</span>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-[#0f172a] border border-slate-800 px-3.5 py-1.5 rounded-xl w-full max-w-xs focus-within:border-cyan-500/50 transition">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                <input type="text" placeholder="Tìm kiếm hệ thống..." class="bg-transparent border-none outline-none text-xs text-slate-200 w-full placeholder-slate-600">
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-1 xl:gap-1.5">
                
                <?php 
                // Nhận diện route hiện tại để xử lý trạng thái Active cho nút "Sản phẩm"
                $current_url = $_GET['url'] ?? 'product'; 
                ?>

                <a href="index.php?url=dashboard" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'dashboard' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span class="text-[11px]">Trung tâm</span>
                    <?php if ($current_url === 'dashboard'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=product" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= (str_contains($current_url, 'product') && !str_contains($current_url, 'create')) ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span class="text-[11px]">Sản phẩm</span>
                    <?php if (str_contains($current_url, 'product') && !str_contains($current_url, 'create')): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=category" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'category' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span class="text-[11px]">Danh mục</span>
                    <?php if ($current_url === 'category'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=order" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'order' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="clipboard-list" class="w-4 h-4"></i>
                    <span class="text-[11px]">Đơn hàng</span>
                    <?php if ($current_url === 'order'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=wishlist" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'wishlist' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="heart" class="w-4 h-4 group-hover:text-pink-500 transition-colors"></i>
                    <span class="text-[11px] flex items-center gap-1">Yêu thích</span>
                    <?php if ($current_url === 'wishlist'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=compare" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'compare' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="git-compare" class="w-4 h-4"></i>
                    <span class="text-[11px]">So sánh</span>
                    <?php if ($current_url === 'compare'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=cart" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'cart' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span class="text-[11px]">Giỏ hàng</span>
                    <?php if ($current_url === 'cart'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=product/create" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'product/create' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-cyan-500/80"></i>
                    <span class="text-[11px]">Thêm SP</span>
                    <?php if ($current_url === 'product/create'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=api-manager" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'api-manager' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="code-2" class="w-4 h-4 text-purple-500/80"></i>
                    <span class="text-[11px] font-mono">API Manager</span>
                    <?php if ($current_url === 'api-manager'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <a href="index.php?url=account" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'account' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                    <span class="text-[11px]">Tài khoản</span>
                    <?php if ($current_url === 'account'): ?>
                        <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                    <?php endif; ?>
                </a>

                <div class="hidden xl:block h-5 w-px bg-slate-800 mx-1.5"></div>

                <div class="flex items-center gap-2 pl-1">
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-300 bg-[#0d1527] border border-slate-800/80 px-2.5 py-1 rounded-xl">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></div>
                        <span class="font-medium"><?= e($_SESSION['user_name'] ?? 'Quản trị viên') ?></span>
                    </div>

                    <button type="button" class="p-1.5 bg-[#0f172a] hover:bg-slate-900 text-slate-400 hover:text-white border border-slate-800 rounded-xl transition group" title="Hệ thống">
                        <i data-lucide="settings" class="w-3.5 h-3.5 group-hover:rotate-45 transition-transform duration-300"></i>
                    </button>
                </div>

            </nav>
        </div>
    </header>

    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">

        <div class="relative bg-gradient-to-r from-[#1e1b4b] via-[#0f172a] to-[#083344] border border-indigo-900/40 rounded-3xl p-6 md:p-8 overflow-hidden shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="text-xs font-mono text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20 inline-block">📁 Luxury Commerce Grid</div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">NovaTech Product Gallery</h1>
                    <p class="text-sm text-slate-400">Mặt tiền sản phẩm được nâng cấp theo phong cách luxury tech: glass card, tilt 3D, badge nổi, quick-info và CTA có micro-interaction.</p>
                </div>
                <div>
                    <a href="index.php?url=product/create" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition shadow-lg shadow-blue-600/20 whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4"></i> Thêm sản phẩm
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center gap-3">
                <div class="p-2 bg-cyan-500/10 text-cyan-400 rounded-lg"><i data-lucide="zap" class="w-4 h-4"></i></div>
                <div><h4 class="text-xs font-bold text-white">Mua nhanh</h4><p class="text-[10px] text-slate-500">Ripple + icon bounce</p></div>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center gap-3">
                <div class="p-2 bg-blue-500/10 text-blue-400 rounded-lg"><i data-lucide="box" class="w-4 h-4"></i></div>
                <div><h4 class="text-xs font-bold text-white">3D Tilt</h4><p class="text-[10px] text-slate-500">Chạy khi hover chuột</p></div>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center gap-3">
                <div class="p-2 bg-purple-500/10 text-purple-400 rounded-lg"><i data-lucide="sliders" class="w-4 h-4"></i></div>
                <div><h4 class="text-xs font-bold text-white">Quick strip</h4><p class="text-[10px] text-slate-500">Stock + rating khi hover</p></div>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center gap-3">
                <div class="p-2 bg-amber-500/10 text-amber-400 rounded-lg"><i data-lucide="ticket" class="w-4 h-4"></i></div>
                <div><h4 class="text-xs font-bold text-white">Voucher</h4><p class="text-[10px] text-slate-500">FUTURE10 / VIP15</p></div>
            </div>
            
            <div class="bg-[#0b0f19]/60 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2"><div class="w-2 h-2 bg-cyan-500 rounded-full"></div><span class="text-[11px] text-slate-400">Tổng sản phẩm</span></div>
                <span class="font-mono text-xs font-bold text-white bg-slate-900 px-2 py-0.5 rounded border border-slate-800"><?= isset($totalProducts) ? e($totalProducts) : 46 ?></span>
            </div>
            <div class="bg-[#0b0f19]/60 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2"><div class="w-2 h-2 bg-blue-500 rounded-full"></div><span class="text-[11px] text-slate-400">Danh mục hoạt động</span></div>
                <span class="font-mono text-xs font-bold text-white bg-slate-900 px-2 py-0.5 rounded border border-slate-800">12</span>
            </div>
            <div class="bg-[#0b0f19]/60 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2"><div class="w-2 h-2 bg-emerald-500 rounded-full"></div><span class="text-[11px] text-slate-400">Giá trung bình</span></div>
                <span class="font-mono text-xs font-bold text-emerald-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-800">449.750 đ</span>
            </div>
            <div class="bg-[#0b0f19]/60 border border-slate-850 p-3 rounded-xl flex items-center justify-between">
                <div class="flex items-center gap-2"><div class="w-2 h-2 bg-purple-500 rounded-full"></div><span class="text-[11px] text-slate-400">Filter đang bật</span></div>
                <span class="font-mono text-xs font-bold text-purple-400 bg-slate-900 px-2 py-0.5 rounded border border-slate-800">0</span>
            </div>
        </div>

        <form method="GET" action="index.php" class="bg-[#0b0f19] border border-slate-850 p-5 rounded-2xl grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 items-end shadow-xl">
            <input type="hidden" name="url" value="product">
            
            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase text-slate-500 flex items-center gap-1"><i data-lucide="search" class="w-3 h-3"></i> Tìm kiếm</label>
                <input type="text" name="search" value="<?= e($_GET['search'] ?? '') ?>" placeholder="Tên sản phẩm, mô tả..." class="w-full bg-[#060911] border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 outline-none focus:border-cyan-500/50 transition">
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase text-slate-500 flex items-center gap-1"><i data-lucide="layers" class="w-3 h-3"></i> Danh mục</label>
                <select name="category_id" class="w-full bg-[#060911] border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 outline-none focus:border-cyan-500/50 transition">
                    <option value="">Tất cả</option>
                    <option value="1" <?= ($_GET['category_id'] ?? '') == '1' ? 'selected' : '' ?>>Giày công sở</option>
                    <option value="2" <?= ($_GET['category_id'] ?? '') == '2' ? 'selected' : '' ?>>Dép trong nhà</option>
                </select>
            </div>

            <div class="space-y-1.5">
                <label class="text-[10px] font-bold uppercase text-slate-500 flex items-center gap-1"><i data-lucide="arrow-up-down" class="w-3 h-3"></i> Sắp xếp</label>
                <select name="sort" class="w-full bg-[#060911] border border-slate-800 rounded-xl px-3 py-2 text-xs text-slate-200 outline-none focus:border-cyan-500/50 transition">
                    <option value="newest" <?= ($_GET['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Mới nhất</option>
                    <option value="price_asc" <?= ($_GET['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Giá tăng dần</option>
                    <option value="price_desc" <?= ($_GET['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Giá giảm dần</option>
                </select>
            </div>

            <div class="flex gap-2">
                <a href="index.php?url=product" class="w-1/2 bg-[#16223f] hover:bg-[#1e2f56] border border-slate-800 text-slate-300 text-xs font-medium py-2 rounded-xl text-center transition flex items-center justify-center gap-1"><i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Reset</a>
                <button type="submit" class="w-1/2 bg-cyan-600 hover:bg-cyan-500 text-slate-950 text-xs font-bold py-2 rounded-xl transition flex items-center justify-center gap-1 shadow-lg shadow-cyan-600/10"><i data-lucide="filter" class="w-3.5 h-3.5"></i> Áp dụng</button>
            </div>
        </form>

        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            
            <?php 
            $mockProducts = [
                ['id' => 1, 'name' => 'Giày công sở nam da đen', 'price' => 829000, 'category' => 'Giày công sở', 'image' => '', 'stock' => 5],
                ['id' => 2, 'name' => 'Giày công sở nữ đế thấp', 'price' => 512000, 'category' => 'Giày công sở', 'image' => '', 'stock' => 0],
                ['id' => 3, 'name' => 'Dép nhựa chống trượt', 'price' => 120000, 'category' => 'Bảo hộ trong nhà', 'image' => '', 'stock' => 2],
                ['id' => 4, 'name' => 'Dép bông đi trong nhà', 'price' => 95000, 'category' => 'Bảo hộ trong nhà', 'image' => '', 'stock' => 15],
            ];

            foreach ($mockProducts as $product): 
                $tag = getProductTag($product); 
                $imgSrc = getImageSrc($product['image'] ?? '', $product['name'], $product['id']);
            ?>
            <div class="bg-[#0b0f19] border border-slate-850 rounded-2xl overflow-hidden shadow-lg hover:border-cyan-500/40 hover:shadow-cyan-500/5 transition-all duration-300 flex flex-col justify-between group relative">
                
                <div class="relative bg-[#060911] p-6 flex items-center justify-center h-48 border-b border-slate-900/50 overflow-hidden">
                    <?php if ($tag): ?>
                        <span class="absolute top-3 left-3 text-[9px] font-mono font-bold px-2 py-0.5 rounded shadow z-10" style="color: <?= $tag['color'] ?>; background: <?= $tag['bg'] ?>;">
                            <?= e($tag['label']) ?>
                        </span>
                    <?php endif; ?>

                    <span class="absolute top-3 right-3 font-mono text-[9px] text-slate-600">ID: #<?= $product['id'] ?></span>

                    <div class="w-32 h-32 bg-slate-900/80 border border-slate-800 rounded-xl flex flex-col items-center justify-center p-3 text-center transition-transform group-hover:scale-105 duration-300 shadow-inner">
                        <span class="text-3xl mb-1">📦</span>
                        <span class="text-[10px] font-mono text-cyan-500 font-bold">NovaStore</span>
                        <span class="text-[9px] text-slate-500 truncate w-full">Product Image</span>
                    </div>
                </div>

                <div class="p-4 space-y-3 flex-1 flex flex-col justify-between bg-gradient-to-b from-[#0b0f19] to-[#030712]">
                    <div class="space-y-1">
                        <span class="inline-block text-[10px] font-bold uppercase tracking-wider text-cyan-500/70 bg-cyan-950/20 px-2 py-0.5 rounded border border-cyan-900/20"><?= e($product['category']) ?></span>
                        <h4 class="text-xs font-bold text-slate-200 group-hover:text-white transition line-clamp-1 mt-1"><?= e($product['name']) ?></h4>
                        <p class="text-[11px] text-slate-500 line-clamp-2 leading-relaxed">Sản phẩm công nghệ cao cấp chính hãng phân phối tại NovaTech Future Store.</p>
                    </div>

                    <div class="pt-2 border-t border-slate-850 flex items-center justify-between">
                        <div>
                            <span class="block text-[9px] text-slate-500 uppercase tracking-widest font-mono">Giá bán</span>
                            <span class="text-sm font-mono font-bold text-white"><?= moneyVnd($product['price']) ?></span>
                        </div>
                        
                        <div class="flex gap-1">
                            <a href="index.php?url=product/edit/<?= $product['id'] ?>" class="p-1.5 bg-slate-900 text-slate-400 border border-slate-800 rounded-lg hover:text-white hover:border-slate-700 transition" title="Sửa">
                                <i data-lucide="edit-3" class="w-3.5 h-3.5"></i>
                            </a>
                            <button type="button" class="p-1.5 bg-cyan-950/40 text-cyan-400 border border-cyan-900/40 rounded-lg hover:bg-cyan-500 hover:text-slate-950 transition-all shadow-sm" title="Thêm vào giỏ">
                                <i data-lucide="shopping-cart" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
            <?php endforeach; ?>

        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>