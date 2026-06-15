<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NovaTech Future Store - Bảng Điều Khiển Hệ Thống</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        /* Custom thanh cuộn chuẩn Dark Mode công nghệ */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #1e293b; border-radius: 999px; }
        ::-webkit-scrollbar-thumb:hover { background: #334155; }
    </style>
</head>
<body class="bg-[#090d16] text-[#94a3b8] flex flex-col min-h-screen antialiased selection:bg-cyan-500 selection:text-slate-900">

    <header class="bg-[#030712]/90 backdrop-blur-xl border-b border-slate-800/60 sticky top-0 z-50 px-4 py-2.5 transition-all">
        <div class="max-w-[1600px] mx-auto flex flex-col xl:flex-row items-center justify-between gap-4">
            
            <div class="flex items-center gap-3 shrink-0">
                <div class="bg-gradient-to-tr from-blue-600 to-cyan-400 p-2 rounded-xl text-white shadow-lg shadow-cyan-500/10">
                    <i data-lucide="cpu" class="w-5 h-5"></i>
                </div>
                <div>
                    <span class="text-base font-bold tracking-wider text-white">Nova<span class="text-cyan-400">Tech</span></span>
                    <span class="block text-[9px] text-cyan-500/80 font-mono tracking-widest uppercase -mt-1">Luxury Tech</span>
                </div>
            </div>

            <div class="flex items-center gap-2 bg-[#0f172a] border border-slate-800 px-3.5 py-1.5 rounded-xl w-full max-w-xs focus-within:border-cyan-500/50 transition">
                <i data-lucide="search" class="w-3.5 h-3.5 text-slate-500"></i>
                <input type="text" placeholder="Tìm kiếm nhanh..." class="bg-transparent border-none outline-none text-xs text-slate-200 w-full placeholder-slate-600">
            </div>

            <nav class="flex flex-wrap items-center justify-center gap-1 xl:gap-2">
                
                <a href="index.php?url=dashboard" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-cyan-400 font-medium text-xs transition group">
                    <i data-lucide="layout-dashboard" class="w-4 h-4 text-cyan-400"></i>
                    <span>Trung tâm</span>
                    <span class="absolute bottom-0 left-2 right-2 h-[2px] bg-gradient-to-r from-blue-500 to-cyan-400 rounded-full"></span>
                </a>

                <a href="index.php?url=product" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="shopping-bag" class="w-4 h-4 group-hover:text-cyan-400 transition"></i>
                    <span>Sản phẩm</span>
                </a>

                <a href="index.php?url=category" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="layers" class="w-4 h-4 group-hover:text-cyan-400 transition"></i>
                    <span>Danh mục</span>
                </a>

                <a href="index.php?url=order" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="clipboard-list" class="w-4 h-4 group-hover:text-cyan-400 transition"></i>
                    <span>Đơn hàng</span>
                </a>

                <a href="index.php?url=wishlist" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="heart" class="w-4 h-4 group-hover:text-pink-500 transition"></i>
                    <span class="flex items-center gap-1">
                        Yêu thích 
                        <span class="text-[10px] px-1 bg-purple-950 text-purple-400 border border-purple-800 rounded-md font-mono"><?= getWishlistCount() ?></span>
                    </span>
                </a>

                <a href="index.php?url=compare" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="git-compare" class="w-4 h-4 group-hover:text-cyan-400 transition"></i>
                    <span>So sánh</span>
                </a>

                <a href="index.php?url=cart" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="shopping-cart" class="w-4 h-4 group-hover:text-emerald-400 transition"></i>
                    <span class="flex items-center gap-1">
                        Giỏ hàng 
                        <span class="text-[10px] px-1 bg-emerald-950 text-emerald-400 border border-emerald-900 rounded-md font-mono"><?= getCartCount() ?></span>
                    </span>
                </a>

                <a href="index.php?url=product/create" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="plus-circle" class="w-4 h-4 text-cyan-400 transition"></i>
                    <span>Thêm SP</span>
                </a>

                <a href="index.php?url=api-manager" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-indigo-400 text-xs transition group font-mono">
                    <i data-lucide="code-2" class="w-4 h-4 text-purple-500"></i>
                    <span>&lt;/&gt; API Manager</span>
                </a>

                <a href="index.php?url=account" class="relative flex flex-col items-center gap-1 px-3 py-1.5 text-slate-400 hover:text-slate-200 text-xs transition group">
                    <i data-lucide="user-cog" class="w-4 h-4 group-hover:text-blue-400 transition"></i>
                    <span>Tài khoản</span>
                </a>

                <div class="hidden xl:block h-6 w-px bg-slate-800 mx-2"></div>

                <div class="flex items-center gap-2 pl-2">
                    <div class="flex items-center gap-1.5 text-xs text-slate-300 bg-[#0d1527] border border-slate-800 px-3 py-1.5 rounded-xl">
                        <i data-lucide="shield-check" class="w-4 h-4 text-emerald-400"></i>
                        <span class="font-medium"><?= e($_SESSION['user_name'] ?? 'Quản trị viên') ?></span>
                    </div>

                    <button class="p-2 bg-[#0f172a] hover:bg-slate-900 text-slate-400 hover:text-white border border-slate-800 rounded-xl transition group" title="Cấu hình hệ thống">
                        <i data-lucide="settings" class="w-4 h-4 group-hover:rotate-45 transition-transform duration-300"></i>
                    </button>
                </div>

            </nav>
        </div>
    </header>

    <main class="flex-1 p-6 max-w-7xl w-full mx-auto space-y-6">

        <div class="relative bg-gradient-to-r from-[#1e1b4b] via-[#0f172a] to-[#083344] border border-indigo-900/40 rounded-3xl p-6 md:p-8 overflow-hidden shadow-2xl">
            <div class="absolute -right-20 -top-20 w-80 h-80 bg-cyan-500/10 rounded-full blur-3xl pointer-events-none"></div>
            <div class="absolute -left-20 -bottom-20 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>
            
            <div class="relative z-10 max-w-2xl space-y-4">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-mono bg-indigo-500/10 text-indigo-400 border border-indigo-500/20">
                    <span class="w-1.5 h-1.5 bg-indigo-400 rounded-full animate-pulse"></span> Command Center Online
                </span>
                <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Trung tâm điều khiển NovaTech</h1>
                <p class="text-sm text-slate-400 leading-relaxed">
                    Dashboard tổng quan vận hành hệ thống công nghệ bán hàng: quản lý sản phẩm, đơn hàng, doanh thu, wishlist, so sánh và giỏ hàng.
                </p>
                <div class="pt-2 flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-slate-900/80 border border-slate-800 rounded-lg text-xs font-medium text-slate-300">⚡ Laragon Localhost</span>
                    <span class="px-3 py-1 bg-slate-900/80 border border-slate-800 rounded-lg text-xs font-medium text-slate-300">🔒 CSRF Protected</span>
                    <span class="px-3 py-1 bg-slate-900/80 border border-slate-800 rounded-lg text-xs font-medium text-slate-300">🛠️ PHP Clean Engine</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="lg:col-span-2 bg-[#0d1527] border border-slate-850 p-6 rounded-2xl flex flex-col justify-between shadow-lg relative overflow-hidden group">
                <div class="space-y-3">
                    <div class="flex items-center gap-2 text-cyan-400 text-xs font-mono uppercase tracking-wider">
                        <i data-lucide="rocket" class="w-4 h-4"></i> NovaTech Future Launch
                    </div>
                    <h2 class="text-lg font-bold text-white">Không gian bán hàng công nghệ chuẩn tương lai</h2>
                    <p class="text-xs text-slate-400 leading-relaxed">
                        Header mới, logo SVG, banner cao cấp, tìm kiếm nhanh, giỏ hàng, wishlist, so sánh, voucher checkout thông minh và quản lý trạng thái đơn hàng.
                    </p>
                </div>
                <div class="mt-5 flex gap-3">
                    <a href="index.php?url=product" class="px-4 py-2 bg-cyan-600 hover:bg-cyan-500 text-slate-950 text-xs font-bold rounded-xl transition flex items-center gap-1.5 shadow-lg shadow-cyan-600/10">Khám phá sản phẩm <i data-lucide="arrow-right" class="w-3.5 h-3.5"></i></a>
                    <a href="index.php?url=cart" class="px-4 py-2 bg-[#16223f] hover:bg-[#1e2f56] text-slate-200 text-xs font-medium rounded-xl border border-slate-800 transition flex items-center gap-1.5">Vào giỏ hàng <i data-lucide="shopping-bag" class="w-3.5 h-3.5"></i></a>
                </div>
            </div>

            <div class="bg-gradient-to-b from-[#111827] to-[#030712] border border-slate-850 p-6 rounded-2xl flex flex-col justify-between shadow-lg">
                <div class="space-y-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-2 text-purple-400 text-xs font-mono uppercase tracking-wider">
                            <i data-lucide="ticket" class="w-4 h-4"></i> Voucher Đang Bật
                        </div>
                        <span class="w-2 h-2 bg-emerald-500 rounded-full animate-ping"></span>
                    </div>
                    <p class="text-xs text-slate-400">Bám vào mã để sao chép nhanh, áp dụng trực tiếp trong giỏ hàng.</p>
                    
                    <div class="pt-2 space-y-2">
                        <div class="flex items-center justify-between p-2.5 bg-[#0b0f19] border border-slate-800 rounded-xl hover:border-purple-500/40 transition group cursor-pointer">
                            <span class="font-mono text-xs font-bold text-purple-400 bg-purple-950/40 px-2 py-1 rounded border border-purple-900/30">FUTURE10</span>
                            <span class="text-[11px] text-slate-500 group-hover:text-slate-300 transition">Giảm 10% tổng giỏ</span>
                        </div>
                        <div class="flex items-center justify-between p-2.5 bg-[#0b0f19] border border-slate-800 rounded-xl hover:border-purple-500/40 transition group cursor-pointer">
                            <span class="font-mono text-xs font-bold text-cyan-400 bg-cyan-950/40 px-2 py-1 rounded border border-cyan-900/30">VIP15</span>
                            <span class="text-[11px] text-slate-500 group-hover:text-slate-300 transition">Ưu đãi VIP 15%</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-[#0b0f19] border border-slate-850 p-5 rounded-2xl flex items-center justify-between shadow-md group hover:border-cyan-500/30 transition-all duration-300">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Sản Phẩm Hệ Thống</span>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight"><?= isset($totalProducts) ? e($totalProducts) : 40 ?></h3>
                </div>
                <div class="w-12 h-12 bg-cyan-500/5 text-cyan-400 rounded-xl flex items-center justify-center border border-cyan-500/10 group-hover:bg-cyan-500 group-hover:text-slate-950 transition-all duration-300">
                    <i data-lucide="box" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="bg-[#0b0f19] border border-slate-850 p-5 rounded-2xl flex items-center justify-between shadow-md group hover:border-blue-500/30 transition-all duration-300">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Tổng Đơn Hàng</span>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight"><?= getCartCount() ?> Đơn</h3>
                </div>
                <div class="w-12 h-12 bg-blue-500/5 text-blue-400 rounded-xl flex items-center justify-center border border-blue-500/10 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                    <i data-lucide="shopping-cart" class="w-5 h-5"></i>
                </div>
            </div>

            <?php $totals = getCartTotals(); ?>
            <div class="bg-[#0b0f19] border border-slate-850 p-5 rounded-2xl flex items-center justify-between shadow-md group hover:border-emerald-500/30 transition-all duration-300">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Doanh Thu Giỏ Hàng</span>
                    <h3 class="text-xl font-extrabold text-emerald-400 tracking-tight"><?= moneyVnd($totals['subtotal']) ?></h3>
                </div>
                <div class="w-12 h-12 bg-emerald-500/5 text-emerald-400 rounded-xl flex items-center justify-center border border-emerald-500/10 group-hover:bg-emerald-500 group-hover:text-slate-950 transition-all duration-300">
                    <i data-lucide="dollar-sign" class="w-5 h-5"></i>
                </div>
            </div>

            <div class="bg-[#0b0f19] border border-slate-850 p-5 rounded-2xl flex items-center justify-between shadow-md group hover:border-purple-500/30 transition-all duration-300">
                <div class="space-y-1">
                    <span class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Đơn Hôm Nay</span>
                    <h3 class="text-2xl font-extrabold text-white tracking-tight">0</h3>
                </div>
                <div class="w-12 h-12 bg-purple-500/5 text-purple-400 rounded-xl flex items-center justify-center border border-purple-500/10 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                    <i data-lucide="heart" class="w-5 h-5"></i>
                </div>
            </div>
        </div>

        <div class="bg-[#0b0f19] border border-slate-850 p-6 rounded-2xl shadow-xl space-y-6">
            <div class="flex items-center justify-between">
                <div class="space-y-1">
                    <h3 class="text-base font-bold text-white flex items-center gap-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-cyan-400"></i> Doanh thu 7 ngày qua
                    </h3>
                    <p class="text-xs text-slate-500 font-sans">Số liệu phân tích thuật toán thống kê biểu đồ sóng</p>
                </div>
                <div class="text-xs text-cyan-400 bg-cyan-950/40 border border-cyan-900/40 px-3 py-1 rounded-xl font-mono">
                    Live Monitor
                </div>
            </div>

            <div class="relative bg-[#060911] border border-slate-900 rounded-xl p-4 h-64 w-full flex items-center justify-center">
                <div class="absolute inset-0 grid grid-rows-4 grid-cols-6 p-4 opacity-[0.03] pointer-events-none">
                    <?php for($i=0; $i<24; $i++): ?><div class="border-t border-l border-white"></div><?php endfor; ?>
                </div>

                <svg class="w-full h-full overflow-visible" viewBox="0 0 600 200" preserveAspectRatio="none">
                    <defs>
                        <linearGradient id="chart-grad" x1="0" y1="0" x2="0" y2="1">
                            <stop offset="0%" stop-color="#22d3ee" stop-opacity="0.25"/>
                            <stop offset="100%" stop-color="#22d3ee" stop-opacity="0.00"/>
                        </linearGradient>
                    </defs>
                    <path d="M 0 170 Q 100 150 200 135 T 400 100 Q 500 60 600 80 L 600 200 L 0 200 Z" fill="url(#chart-grad)" />
                    <path d="M 0 170 Q 100 150 200 135 T 400 100 Q 500 60 600 80" fill="none" stroke="#22d3ee" stroke-width="2.5" stroke-linecap="round" />
                    <circle cx="600" cy="80" r="4" fill="#22d3ee" class="animate-ping" style="transform-origin: 600px 80px;" />
                    <circle cx="600" cy="80" r="3" fill="#090d16" stroke="#22d3ee" stroke-width="2" />
                </svg>

                <div class="absolute bottom-1 left-4 right-4 flex justify-between text-[10px] text-slate-600 font-mono">
                    <span>09:00</span><span>10:00</span><span>11:00</span><span>12:00</span><span>13:00</span><span>14:00</span><span>15:00</span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <div class="bg-[#0b0f19] border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-purple-400"></i> Phân bổ danh mục
                </h3>
                <div class="space-y-4 pt-2">
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-slate-300">Giày công sở</span>
                            <span class="text-slate-500 font-mono">12 SP</span>
                        </div>
                        <div class="w-full bg-[#060911] h-1.5 rounded-full overflow-hidden border border-slate-900">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    <div class="space-y-1">
                        <div class="flex justify-between text-xs font-medium">
                            <span class="text-slate-300">Dép đi trong nhà</span>
                            <span class="text-slate-500 font-mono">8 SP</span>
                        </div>
                        <div class="w-full bg-[#060911] h-1.5 rounded-full overflow-hidden border border-slate-900">
                            <div class="bg-gradient-to-r from-blue-500 to-cyan-400 h-full rounded-full" style="width: 25%"></div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-2 bg-[#0b0f19] border border-slate-850 p-6 rounded-2xl shadow-xl space-y-4">
                <h3 class="text-sm font-bold text-white flex items-center gap-2">
                    <i data-lucide="sparkles" class="w-4 h-4 text-amber-400"></i> Sản phẩm mới nhất hệ thống
                </h3>
                
                <div class="pt-2 divide-y divide-slate-850">
                    <div class="flex items-center justify-between py-3 first:pt-0 last:pb-0 group">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-[#060911] border border-slate-800 flex items-center justify-center text-slate-500 font-bold text-xs">
                                👟
                            </div>
                            <div>
                                <h4 class="text-xs font-bold text-slate-200 group-hover:text-cyan-400 transition">Giày công sở nam da đen</h4>
                                <span class="text-[11px] text-slate-500 font-mono">Mã sản phẩm: SP-2449</span>
                            </div>
                        </div>
                        <div class="flex items-center gap-4">
                            <span class="text-xs font-mono font-bold text-white">829.000 đ</span>
                            <button class="p-1.5 bg-[#16223f] text-cyan-400 border border-cyan-900/30 rounded-lg hover:bg-cyan-500 hover:text-slate-950 transition">
                                <i data-lucide="plus" class="w-3.5 h-3.5"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>