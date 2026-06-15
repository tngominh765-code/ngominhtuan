<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaTech - Quản Lý Danh Mục</title>
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
                // Ép trạng thái route hiện tại nhận diện cho 'category' để kích hoạt Active nháy sáng
                $current_url = $_GET['url'] ?? 'category'; 
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
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span class="text-[11px]">Yêu thích</span>
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
                        <span class="font-medium"><?= isset($_SESSION['user_name']) ? e($_SESSION['user_name']) : 'Quản trị viên' ?></span>
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
                    <div class="text-xs font-mono text-cyan-400 bg-cyan-500/10 px-2 py-0.5 rounded border border-cyan-500/20 inline-block">🛠️ Category Manager</div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Quản lý danh mục</h1>
                    <p class="text-sm text-slate-400">Hệ thống phân loại sản phẩm theo nhóm cấu trúc dữ liệu cây thông minh giúp tối ưu hóa luồng tìm kiếm và hiển thị.</p>
                </div>
                <div>
                    <a href="index.php?url=category/create" class="bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-slate-950 text-xs font-bold px-4 py-2.5 rounded-xl flex items-center gap-2 transition shadow-lg shadow-cyan-500/10 whitespace-nowrap">
                        <i data-lucide="plus" class="w-4 h-4"></i> Thêm danh mục
                    </a>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center justify-between shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-cyan-500/10 text-cyan-400 rounded-xl"><i data-lucide="layers" class="w-4 h-4"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Tổng danh mục</h4>
                        <p class="text-[10px] text-slate-500">Phân loại đang hoạt động</p>
                    </div>
                </div>
                <span class="font-mono text-sm font-bold text-cyan-400 bg-slate-900 px-3 py-1 rounded-xl border border-slate-800">12</span>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center justify-between shadow-md">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-blue-500/10 text-blue-400 rounded-xl"><i data-lucide="box" class="w-4 h-4"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-white">Tổng sản phẩm</h4>
                        <p class="text-[10px] text-slate-500">Đã liên kết danh mục</p>
                    </div>
                </div>
                <span class="font-mono text-sm font-bold text-blue-400 bg-slate-900 px-3 py-1 rounded-xl border border-slate-800"><?= isset($totalProducts) ? e($totalProducts) : 48 ?></span>
            </div>
        </div>

        <div class="bg-[#0b0f19] border border-slate-850 rounded-2xl overflow-hidden shadow-2xl">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-xs">
                    <thead>
                        <tr class="bg-[#060911] border-b border-slate-850 text-slate-400 font-mono text-[10px] tracking-wider uppercase">
                            <th class="py-3.5 px-4 w-16 text-center">#</th>
                            <th class="py-3.5 px-4">Tên danh mục</th>
                            <th class="py-3.5 px-4 w-28 text-center">Số sản phẩm</th>
                            <th class="py-3.5 px-4">Mô tả</th>
                            <th class="py-3.5 px-4 w-36 text-center">Hành động</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-900/60 text-slate-300">
                        
                        <?php
                        // Vòng lặp mock dữ liệu đồng nhất khớp chính xác dữ liệu gốc của bạn
                        $mockCategories = [
                            ['id' => 12, 'name' => 'Giày công sở', 'count' => '2 SP', 'desc' => 'Giày đi làm lịch sự, bền đẹp và thoải mái.'],
                            ['id' => 11, 'name' => 'Dép đi trong nhà', 'count' => '2 SP', 'desc' => 'Dép mang trong nhà mềm mại, chống trơn trượt.'],
                            ['id' => 10, 'name' => 'Phụ kiện giày', 'count' => '0 SP', 'desc' => 'Tất, dây giày, lót giày và vệ sinh giày.'],
                            ['id' => 9,  'name' => 'Giày trẻ em', 'count' => '0 SP', 'desc' => 'Giày dép trẻ em mềm nhẹ, an toàn và dễ vận động.'],
                            ['id' => 8,  'name' => 'Boots', 'count' => '0 SP', 'desc' => 'Boots cổ thấp, cổ cao phong cách cá tính.'],
                            ['id' => 7,  'name' => 'Giày búp bê', 'count' => '0 SP', 'desc' => 'Giày búp bê nữ êm ái, dễ mang và dễ phối đồ.']
                        ];

                        foreach ($mockCategories as $cat):
                        ?>
                        <tr class="hover:bg-[#0f1626]/40 transition duration-150 group">
                            <td class="py-4 px-4 text-center font-mono text-slate-500 text-[11px]"><?= $cat['id'] ?></td>
                            
                            <td class="py-4 px-4">
                                <div class="flex items-center gap-2.5">
                                    <div class="p-1.5 bg-blue-500/5 text-blue-400 group-hover:text-cyan-400 border border-slate-800/60 rounded-lg group-hover:border-cyan-500/30 transition">
                                        <i data-lucide="folder" class="w-3.5 h-3.5"></i>
                                    </div>
                                    <span class="font-bold text-slate-200 group-hover:text-white transition"><?= e($cat['name']) ?></span>
                                </div>
                            </td>
                            
                            <td class="py-4 px-4 text-center">
                                <span class="font-mono text-[10px] font-bold px-2 py-0.5 rounded-full border bg-cyan-950/20 text-cyan-400 border-cyan-900/30">
                                    <?= e($cat['count']) ?>
                                </span>
                            </td>
                            
                            <td class="py-4 px-4 text-slate-400 font-normal max-w-sm truncate leading-relaxed">
                                <?= e($cat['desc']) ?>
                            </td>
                            
                            <td class="py-4 px-4">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="index.php?url=category/show/<?= $cat['id'] ?>" class="px-2 py-1 text-[10px] font-medium bg-[#131b2e] text-slate-300 hover:text-white border border-slate-800 hover:border-slate-700 rounded-lg transition flex items-center gap-1">
                                        <i data-lucide="eye" class="w-3 h-3"></i> Xem
                                    </a>
                                    <a href="index.php?url=category/edit/<?= $cat['id'] ?>" class="px-2 py-1 text-[10px] font-bold bg-amber-500/10 text-amber-400 hover:bg-amber-500 hover:text-slate-950 rounded-lg transition flex items-center gap-1">
                                        <i data-lucide="edit-2" class="w-3 h-3"></i> Sửa
                                    </a>
                                    <a href="index.php?url=category/delete/<?= $cat['id'] ?>" onclick="return confirm('Xác nhận xóa danh mục này?')" class="px-2 py-1 text-[10px] font-bold bg-rose-500/10 text-rose-400 hover:bg-rose-500 hover:text-white rounded-lg transition flex items-center gap-1">
                                        <i data-lucide="trash-2" class="w-3 h-3"></i> Xóa
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>