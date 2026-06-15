<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaTech - Quản Lý Đơn Hàng</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Tùy chỉnh thanh cuộn chuẩn Tech Luxury */
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

            <nav class="flex flex-wrap items-center justify-center gap-1 xl:gap-1.5">
                <?php $current_url = $_GET['url'] ?? 'order'; ?>

                <a href="index.php?url=dashboard" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'dashboard' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                    <span class="text-[11px]">Trung tâm</span>
                    <?php if ($current_url === 'dashboard'): ?> <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span> <?php endif; ?>
                </a>

                <a href="index.php?url=product" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= (str_contains($current_url, 'product') && !str_contains($current_url, 'create')) ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="shopping-bag" class="w-4 h-4"></i>
                    <span class="text-[11px]">Sản phẩm</span>
                    <?php if (str_contains($current_url, 'product') && !str_contains($current_url, 'create')): ?> <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span> <?php endif; ?>
                </a>

                <a href="index.php?url=category" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'category' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="layers" class="w-4 h-4"></i>
                    <span class="text-[11px]">Danh mục</span>
                    <?php if ($current_url === 'category'): ?> <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span> <?php endif; ?>
                </a>

                <a href="index.php?url=order" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'order' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="clipboard-list" class="w-4 h-4 text-cyan-400"></i>
                    <span class="text-[11px]">Đơn hàng</span>
                    <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                </a>

                <a href="index.php?url=wishlist" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'wishlist' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="heart" class="w-4 h-4"></i>
                    <span class="text-[11px]">Yêu thích</span>
                </a>

                <a href="index.php?url=compare" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'compare' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="git-compare" class="w-4 h-4"></i>
                    <span class="text-[11px]">So sánh</span>
                </a>

                <a href="index.php?url=cart" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'cart' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="shopping-cart" class="w-4 h-4"></i>
                    <span class="text-[11px]">Giỏ hàng</span>
                </a>

                <a href="index.php?url=product/create" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group <?= $current_url === 'product/create' ? 'text-cyan-400 font-medium' : 'text-slate-400 hover:text-slate-200' ?>">
                    <i data-lucide="plus-circle" class="w-4 h-4"></i>
                    <span class="text-[11px]">Thêm SP</span>
                </a>

                <a href="index.php?url=api-manager" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group text-slate-400 hover:text-slate-200">
                    <i data-lucide="code-2" class="w-4 h-4"></i>
                    <span class="text-[11px] font-mono">API Manager</span>
                </a>

                <a href="index.php?url=account" class="relative flex flex-col items-center gap-1 px-3 py-1 text-xs transition group text-slate-400 hover:text-slate-200">
                    <i data-lucide="user-cog" class="w-4 h-4"></i>
                    <span class="text-[11px]">Tài khoản</span>
                </a>

                <div class="hidden xl:block h-5 w-px bg-slate-800 mx-1.5"></div>

                <div class="flex items-center gap-2 pl-1">
                    <div class="flex items-center gap-1.5 text-[11px] text-slate-300 bg-[#0d1527] border border-slate-800/80 px-2.5 py-1 rounded-xl">
                        <div class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></div>
                        <span class="font-medium"><?= e($_SESSION['user_name'] ?? 'Quản trị viên') ?></span>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">

        <div class="relative bg-gradient-to-r from-[#1e1b4b] via-[#0f172a] to-[#083344] border border-indigo-900/40 rounded-3xl p-6 md:p-8 overflow-hidden shadow-2xl">
            <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div class="space-y-2">
                    <div class="text-[10px] font-mono text-purple-400 bg-purple-500/10 px-2 py-0.5 rounded border border-purple-500/20 inline-block uppercase tracking-widest">📦 Order Command Center</div>
                    <h1 class="text-2xl font-extrabold text-white tracking-tight">Quản lý đơn hàng</h1>
                    <p class="text-sm text-slate-400 lead-relaxed">Theo dõi luồng giao dịch, xử lý trạng thái đơn hàng và tối ưu hóa vận hành NovaTech Future Store.</p>
                </div>
                <div class="flex gap-2">
                    <button class="bg-[#16223f] hover:bg-[#1e2f56] text-slate-200 text-xs font-bold px-4 py-2.5 rounded-xl border border-slate-800 transition flex items-center gap-2">
                        <i data-lucide="file-down" class="w-4 h-4"></i> Xuất báo cáo
                    </button>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-cyan-500/10 text-cyan-400 rounded-xl"><i data-lucide="shopping-bag" class="w-4 h-4"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-tighter">Tổng đơn hàng</h4>
                        <p class="text-[10px] text-slate-500 font-mono">Total Transaction</p>
                    </div>
                </div>
                <span class="font-mono text-sm font-bold text-white bg-slate-900 px-3 py-1 rounded-xl border border-slate-800">0</span>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl"><i data-lucide="clock" class="w-4 h-4"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-tighter">Đang chờ xử lý</h4>
                        <p class="text-[10px] text-slate-500 font-mono">Pending Orders</p>
                    </div>
                </div>
                <span class="font-mono text-sm font-bold text-amber-400 bg-slate-900 px-3 py-1 rounded-xl border border-slate-800">0</span>
            </div>
            <div class="bg-[#0b0f19] border border-slate-850 p-4 rounded-xl flex items-center justify-between shadow-lg">
                <div class="flex items-center gap-3">
                    <div class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl"><i data-lucide="dollar-sign" class="w-4 h-4"></i></div>
                    <div>
                        <h4 class="text-xs font-bold text-white uppercase tracking-tighter">Doanh thu tạm tính</h4>
                        <p class="text-[10px] text-slate-500 font-mono">Projected Revenue</p>
                    </div>
                </div>
                <span class="font-mono text-sm font-bold text-emerald-400 bg-slate-900 px-3 py-1 rounded-xl border border-slate-800">0 đ</span>
            </div>
        </div>

        <div class="bg-[#0b0f19] border border-slate-850 rounded-2xl overflow-hidden shadow-2xl min-h-[400px] flex flex-col">
            <div class="p-5 border-b border-slate-850 bg-[#060911]/50 flex flex-col sm:flex-row items-center justify-between gap-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="list" class="w-4 h-4 text-cyan-400"></i> Danh sách đơn hàng
                </h3>
                <div class="flex items-center gap-3 w-full sm:w-auto">
                    <div class="bg-[#060911] border border-slate-800 rounded-xl px-3 py-1.5 flex items-center gap-2 w-full sm:w-64">
                        <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                        <input type="text" placeholder="Tìm theo mã đơn, khách..." class="bg-transparent border-none outline-none text-xs text-slate-300 w-full">
                    </div>
                    <button class="p-2 bg-slate-900 border border-slate-800 rounded-xl text-slate-400 hover:text-white transition">
                        <i data-lucide="filter" class="w-4 h-4"></i>
                    </button>
                </div>
            </div>

            <div class="flex-1 flex flex-col items-center justify-center p-12 text-center space-y-4">
                <?php
                // Mockup kiểm tra dữ liệu đơn hàng (giống logic của bạn)
                $hasOrders = false; 
                if (!$hasOrders):
                ?>
                    <div class="relative">
                        <div class="absolute inset-0 bg-cyan-500/20 blur-3xl rounded-full"></div>
                        <div class="relative bg-slate-900/50 p-8 rounded-full border border-slate-800 shadow-2xl">
                            <i data-lucide="shopping-cart" class="w-16 h-16 text-slate-600"></i>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <h4 class="text-lg font-bold text-slate-200">Chưa có đơn hàng nào</h4>
                        <p class="text-sm text-slate-500 max-w-xs mx-auto">Hệ thống hiện tại chưa ghi nhận giao dịch nào. Các đơn hàng mới sẽ xuất hiện tại đây sau khi khách hàng thanh toán.</p>
                    </div>
                    <button class="mt-4 px-5 py-2 bg-cyan-600/10 hover:bg-cyan-600/20 text-cyan-400 border border-cyan-900/40 rounded-xl text-xs font-bold transition flex items-center gap-2">
                        <i data-lucide="refresh-cw" class="w-3.5 h-3.5"></i> Làm mới dữ liệu
                    </button>
                <?php else: ?>
                    <?php endif; ?>
            </div>
        </div>

    </main>

    <footer class="p-8 text-center text-[10px] text-slate-600 font-mono tracking-widest uppercase">
        NovaTech Luxury Store Management System &copy; 2024
    </footer>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>