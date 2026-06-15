<?php
$seoTitle = $seoTitle ?? 'NovaTech Store — Cửa hàng công nghệ tương lai';
$seoDescription = $seoDescription ?? 'Mua sắm điện thoại, laptop, phụ kiện công nghệ cao cấp tại NovaTech. Bảo hành chính hãng, giao hàng toàn quốc.';
$seoImage = $seoImage ?? 'uploads/novatech-logo.svg';
$seoUrl = $seoUrl ?? 'https://novatech.store/';
$seoRobots = $seoRobots ?? 'index, follow';
?>
<!DOCTYPE html>
<html lang="vi" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seoTitle) ?></title>
    <meta name="description" content="<?= e($seoDescription) ?>">
    <meta name="robots" content="<?= e($seoRobots) ?>">
    <meta property="og:title" content="<?= e($seoTitle) ?>">
    <meta property="og:description" content="<?= e($seoDescription) ?>">
    <meta property="og:image" content="<?= e($seoImage) ?>">
    <meta property="og:url" content="<?= e($seoUrl) ?>">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seoTitle) ?>">
    <meta name="twitter:description" content="<?= e($seoDescription) ?>">
    <meta name="twitter:image" content="<?= e($seoImage) ?>">
    <link rel="icon" href="uploads/novatech-logo.svg" type="image/svg+xml">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&family=Orbitron:wght@600;700;800&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #7c3cff;
            --primary-dark: #5b21d9;
            --primary-light: #a78bfa;
            --primary-50: rgba(124, 60, 255, .12);
            --primary-soft: rgba(124, 60, 255, .14);
            --accent: #00f5c8;
            --accent-dark: #00bf9f;
            --cyan: #22d3ee;
            --warning: #fbbf24;
            --amber: #f59e0b;
            --danger: #ff3b6b;
            --rose: #fb7185;
            --ink: #eef6ff;
            --text: #eef6ff;
            --ink-soft: #bfd0e9;
            --muted: #8ea4c6;
            --text-secondary: #bfd0e9;
            --text-muted: #8ea4c6;
            --line: rgba(255,255,255,.12);
            --border: rgba(255,255,255,.12);
            --border-light: rgba(255,255,255,.08);
            --surface: rgba(10,18,38,.72);
            --surface-2: rgba(17,28,58,.82);
            --bg: #060914;
            --radius-sm: 12px;
            --radius-md: 16px;
            --radius-lg: 22px;
            --radius-xl: 30px;
            --radius-full: 999px;
            --shadow-sm: 0 12px 26px rgba(0,0,0,.20);
            --shadow-md: 0 20px 48px rgba(0,0,0,.34);
            --shadow-lg: 0 32px 80px rgba(0,0,0,.45);
            --grad-main: linear-gradient(135deg, #7c3cff 0%, #22d3ee 52%, #00f5c8 100%);
            --grad-green: linear-gradient(135deg, #00f5c8 0%, #22d3ee 100%);
            --grad-warm: linear-gradient(135deg, #f59e0b 0%, #fb7185 100%);
            --gradient-primary: linear-gradient(135deg, #7c3cff 0%, #22d3ee 52%, #00f5c8 100%);
            --gradient-accent: linear-gradient(135deg, #00f5c8 0%, #22d3ee 100%);
            --gradient-warm: linear-gradient(135deg, #f59e0b 0%, #fb7185 100%);
            --gradient-rose: linear-gradient(135deg, #ff3b6b 0%, #7c3cff 100%);
            --transition: all .24s cubic-bezier(.2,.8,.2,1);
        }

        html[data-theme="light"] {
            --ink: #10192f;
            --text: #10192f;
            --ink-soft: #385071;
            --muted: #6c7f9f;
            --text-secondary: #385071;
            --text-muted: #6c7f9f;
            --line: rgba(28,45,80,.13);
            --border: rgba(28,45,80,.13);
            --border-light: rgba(28,45,80,.08);
            --surface: rgba(255,255,255,.82);
            --surface-2: rgba(247,251,255,.92);
            --bg: #eef6ff;
            --shadow-sm: 0 12px 28px rgba(23,41,77,.08);
            --shadow-md: 0 20px 48px rgba(23,41,77,.14);
            --shadow-lg: 0 32px 80px rgba(23,41,77,.18);
        }

        * { box-sizing: border-box; }
        html { scroll-behavior: smooth; }
        body {
            margin: 0;
            font-family: 'Inter', sans-serif;
            color: var(--ink);
            background:
                radial-gradient(900px 520px at 8% -8%, rgba(124,60,255,.36), transparent 62%),
                radial-gradient(780px 420px at 96% 0%, rgba(34,211,238,.25), transparent 60%),
                radial-gradient(700px 480px at 35% 105%, rgba(0,245,200,.16), transparent 60%),
                linear-gradient(180deg, var(--bg), var(--bg));
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            overflow-x: hidden;
        }
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            pointer-events: none;
            background-image:
                linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
            background-size: 46px 46px;
            mask-image: radial-gradient(circle at 50% 0%, #000, transparent 78%);
            z-index: -2;
        }
        body::after {
            content: '';
            position: fixed;
            width: 460px;
            height: 460px;
            right: -180px;
            bottom: -180px;
            border-radius: 50%;
            background: conic-gradient(from 180deg, rgba(124,60,255,.36), rgba(34,211,238,.18), rgba(0,245,200,.26), rgba(124,60,255,.36));
            filter: blur(20px);
            opacity: .65;
            z-index: -3;
            animation: orbitGlow 12s linear infinite;
        }
        @keyframes orbitGlow { to { transform: rotate(360deg); } }

        a { color: inherit; }
        a:hover { text-decoration: none; }
        .font-display { font-family: 'Orbitron', sans-serif; letter-spacing: .02em; }

        .navbar-custom {
            background: rgba(3,7,18,.72) !important;
            backdrop-filter: blur(22px);
            border-bottom: 1px solid rgba(255,255,255,.10);
            position: sticky;
            top: 0;
            z-index: 1050;
            padding: .35rem 0;
            box-shadow: 0 10px 40px rgba(0,0,0,.16);
        }
        html[data-theme="light"] .navbar-custom { background: rgba(248,252,255,.78) !important; }
        .navbar-custom .navbar-brand {
            display: flex;
            align-items: center;
            gap: 11px;
            font-family: 'Orbitron', sans-serif;
            font-weight: 800;
            color: var(--ink) !important;
            font-size: 1.08rem;
            letter-spacing: .04em;
        }
        .brand-icon {
            width: 42px;
            height: 42px;
            border-radius: 16px;
            background: var(--grad-main);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #04101f;
            box-shadow: 0 0 30px rgba(34,211,238,.42);
            position: relative;
        }
        .brand-icon::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 19px;
            border: 1px solid rgba(34,211,238,.28);
        }
        .navbar-custom .nav-link {
            color: var(--ink-soft) !important;
            font-weight: 800;
            border-radius: 999px;
            padding: .62rem .9rem !important;
            margin: .12rem;
            transition: var(--transition);
            font-size: .86rem;
        }
        .navbar-custom .nav-link:hover,
        .navbar-custom .nav-link.active {
            color: var(--ink) !important;
            background: rgba(255,255,255,.10);
            box-shadow: inset 0 0 0 1px rgba(255,255,255,.09);
        }
        html[data-theme="light"] .navbar-custom .nav-link:hover,
        html[data-theme="light"] .navbar-custom .nav-link.active { background: rgba(124,60,255,.10); }

        .cart-link, .count-link { position: relative; }
        .cart-count, .nav-count {
            position: absolute;
            top: 0;
            right: 0;
            min-width: 19px;
            height: 19px;
            padding: 0 5px;
            border-radius: 999px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--gradient-rose);
            color: #fff;
            font-size: .64rem;
            font-weight: 900;
            border: 2px solid #050914;
        }
        html[data-theme="light"] .cart-count,
        html[data-theme="light"] .nav-count { border-color: #fff; }
        .theme-toggle {
            width: 42px;
            height: 42px;
            border-radius: 999px;
            border: 1px solid var(--border);
            background: rgba(255,255,255,.08);
            color: var(--ink);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: var(--transition);
        }
        .theme-toggle:hover { transform: translateY(-2px); box-shadow: 0 0 28px rgba(34,211,238,.24); }

        .main-content { flex: 1; padding: 30px 0 54px; }
        .page-header {
            background:
                linear-gradient(135deg, rgba(124,60,255,.92), rgba(34,211,238,.70), rgba(0,245,200,.58)),
                linear-gradient(180deg, rgba(255,255,255,.10), rgba(255,255,255,.03));
            color: #fff;
            border-radius: var(--radius-xl);
            padding: 32px;
            margin-bottom: 26px;
            box-shadow: var(--shadow-lg), 0 0 0 1px rgba(255,255,255,.13) inset;
            position: relative;
            overflow: hidden;
        }
        .page-header::before {
            content: '';
            position: absolute;
            width: 280px;
            height: 280px;
            border-radius: 50%;
            background: rgba(255,255,255,.18);
            right: -105px;
            top: -105px;
            filter: blur(2px);
        }
        .page-header::after {
            content: '';
            position: absolute;
            inset: auto 24px 0 24px;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,.74), transparent);
        }
        .page-header > * { position: relative; z-index: 1; }
        .page-header h2 { margin: 0; font-size: 1.64rem; font-weight: 900; letter-spacing: -.025em; }
        .page-header p { margin: 7px 0 0; opacity: .91; }
        .hero-chips { margin-top: 15px; display: flex; flex-wrap: wrap; gap: 9px; }
        .hero-chip, .mini-chip {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            background: rgba(5,10,22,.18);
            border: 1px solid rgba(255,255,255,.30);
            color: #fff;
            border-radius: 999px;
            padding: 7px 12px;
            font-size: .76rem;
            font-weight: 800;
            backdrop-filter: blur(12px);
        }
        .mini-chip { color: var(--ink); background: rgba(255,255,255,.07); border-color: var(--border); }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 15px;
        }
        .stat-card, .cyber-card, .card {
            background: linear-gradient(180deg, var(--surface), rgba(255,255,255,.035));
            border: 1px solid var(--border);
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(18px);
            color: var(--ink);
        }
        .stat-card {
            padding: 16px;
            display: flex;
            align-items: center;
            gap: 13px;
            position: relative;
            overflow: hidden;
        }
        .stat-card::after, .cyber-card::after, .card::after {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: inherit;
            pointer-events: none;
            background: linear-gradient(135deg, rgba(255,255,255,.14), transparent 35%, rgba(0,245,200,.08));
            opacity: .7;
        }
        .cyber-card, .card { position: relative; overflow: hidden; }
        .card:hover, .product-card:hover, .stat-card:hover { box-shadow: var(--shadow-md), 0 0 34px rgba(34,211,238,.13); }
        .stat-icon {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            background: var(--grad-main);
            color: #06111f;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 0 28px rgba(34,211,238,.25);
            flex-shrink: 0;
        }
        .stat-content h6 { margin: 0; font-size: .72rem; color: var(--muted); text-transform: uppercase; letter-spacing: .08em; font-weight: 900; }
        .stat-value { font-size: 1.16rem; font-weight: 900; color: var(--ink); }

        .card-header {
            border-bottom: 1px solid var(--line);
            background: rgba(255,255,255,.035);
            font-size: .95rem;
            font-weight: 900;
            padding: 1rem 1.25rem;
            color: var(--ink);
            position: relative;
            z-index: 1;
        }
        .card-body { padding: 1.25rem; position: relative; z-index: 1; }

        .filter-panel {
            background: rgba(255,255,255,.045);
            border: 1px solid var(--line);
            border-radius: var(--radius-md);
            padding: 16px;
        }
        .product-card {
            border: 1px solid var(--line);
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: linear-gradient(180deg, var(--surface), rgba(255,255,255,.035));
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
            position: relative;
            backdrop-filter: blur(16px);
        }
        .product-card:hover { transform: translateY(-7px) scale(1.01); border-color: rgba(34,211,238,.48); }
        .product-card .quick-meta {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            font-size: .72rem;
            color: var(--text-muted);
            margin-bottom: 7px;
            font-weight: 800;
        }
        .product-card .quick-meta i { color: var(--accent); }
        .img-wrapper { position: relative; overflow: hidden; background: rgba(255,255,255,.045); }
        .product-card .card-img-top { height: 232px; object-fit: cover; transition: transform .42s ease, filter .42s ease; }
        .product-card:hover .card-img-top { transform: scale(1.08); filter: saturate(1.15) contrast(1.06); }
        .overlay {
            position: absolute;
            inset: 0;
            display: flex;
            align-items: end;
            justify-content: center;
            flex-wrap: wrap;
            gap: 8px;
            padding: 0 12px 17px;
            background: linear-gradient(to top, rgba(3,7,18,.78), rgba(3,7,18,.18), transparent);
            opacity: 0;
            transition: var(--transition);
        }
        .product-card:hover .overlay { opacity: 1; }
        .card-title { font-size: .98rem; font-weight: 900; line-height: 1.38; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: var(--ink); }
        .category-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: var(--primary-soft);
            color: var(--primary-light);
            padding: 5px 10px;
            border-radius: 999px;
            font-size: .7rem;
            font-weight: 900;
            margin-bottom: 8px;
            border: 1px solid rgba(124,60,255,.22);
        }
        html[data-theme="light"] .category-badge { color: var(--primary-dark); }
        .price { font-size: 1.15rem; font-weight: 900; color: var(--accent); text-shadow: 0 0 16px rgba(0,245,200,.18); }
        .price small { color: var(--muted); font-size: .74rem; }

        .btn { font-weight: 900; border-radius: 13px; border: none; transition: var(--transition); font-size: .85rem; padding: .58rem 1rem; }
        .btn:hover { transform: translateY(-2px); }
        .btn-primary { background: var(--grad-main); color: #06111f; box-shadow: 0 12px 28px rgba(124,60,255,.25); }
        .btn-primary:hover { color: #06111f; }
        .btn-success, .btn-cart { background: var(--grad-green); color: #06111f; box-shadow: 0 12px 25px rgba(0,245,200,.20); }
        .btn-success:hover, .btn-cart:hover { color: #06111f; }
        .btn-warning { background: var(--grad-warm); color: #14110a; }
        .btn-warning:hover { color: #14110a; }
        .btn-danger { background: linear-gradient(135deg, #ff3b6b, #d946ef); color: #fff; }
        .btn-danger:hover { color: #fff; }
        .btn-secondary, .btn-outline-secondary {
            background: rgba(255,255,255,.08);
            color: var(--ink-soft);
            border: 1px solid var(--line);
        }
        .btn-secondary:hover, .btn-outline-secondary:hover { background: rgba(255,255,255,.14); color: var(--ink); }
        .btn-outline-light { border: 1.8px solid rgba(255,255,255,.64); color: #fff; background: rgba(255,255,255,.10); border-radius: 999px; }
        .btn-outline-light:hover { background: #fff; color: #151b2b; }
        .btn-outline-danger { border: 1px solid rgba(255,59,107,.35); background: rgba(255,59,107,.08); color: var(--rose); }
        .btn-outline-danger:hover { background: rgba(255,59,107,.16); color: #fff; }

        .form-control {
            border: 1.5px solid var(--line);
            border-radius: 13px;
            min-height: 44px;
            font-size: .9rem;
            color: var(--ink);
            background: rgba(255,255,255,.07);
        }
        .form-control:focus { border-color: rgba(34,211,238,.72); box-shadow: 0 0 0 .2rem rgba(34,211,238,.12); background: rgba(255,255,255,.10); color: var(--ink); }
        .form-control::placeholder { color: var(--muted); }
        .form-group label { font-weight: 900; font-size: .82rem; color: var(--ink-soft); margin-bottom: .38rem; }
        .custom-file-label { border: 1.5px solid var(--line); border-radius: 13px; min-height: 44px; line-height: 1.8; background: rgba(255,255,255,.07); color: var(--muted); }
        .custom-file-label::after { height: 42px; border-radius: 0 12px 12px 0; background: var(--primary-soft); color: var(--primary-light); font-weight: 900; border-left: 1px solid var(--line); }

        .table { margin-bottom: 0; color: var(--ink); }
        .table thead th { border: none; background: rgba(6,14,32,.80); color: var(--accent); font-size: .73rem; text-transform: uppercase; letter-spacing: .08em; padding: .9rem .92rem; white-space: nowrap; }
        html[data-theme="light"] .table thead th { background: rgba(17,28,58,.92); color: #bbfff4; }
        .table tbody td { border-top: 1px solid var(--border-light); padding: .9rem .92rem; vertical-align: middle; font-size: .89rem; }
        .table tbody tr:hover { background: rgba(255,255,255,.04); }
        .table-img { width: 58px; height: 58px; border-radius: 14px; object-fit: cover; border: 1px solid var(--line); box-shadow: 0 8px 18px rgba(0,0,0,.18); }

        .alert { border: 1px solid var(--line); border-radius: 16px; font-weight: 800; padding: .9rem 1rem; backdrop-filter: blur(18px); }
        .alert-success { background: rgba(0,245,200,.11); color: var(--accent); border-left: 4px solid var(--accent); }
        .alert-danger { background: rgba(255,59,107,.11); color: var(--rose); border-left: 4px solid var(--danger); }
        .close { color: inherit; text-shadow: none; opacity: .85; }

        .empty-state { text-align: center; padding: 68px 16px; }
        .empty-state i { font-size: 3.35rem; color: var(--cyan); margin-bottom: 16px; filter: drop-shadow(0 0 18px rgba(34,211,238,.25)); }
        .empty-state h4 { font-weight: 900; color: var(--ink); }
        .empty-state p { color: var(--muted); }
        .img-placeholder { width: 100%; height: 232px; display: flex; align-items: center; justify-content: center; font-size: 2.4rem; color: var(--cyan); background: linear-gradient(135deg, rgba(124,60,255,.14), rgba(34,211,238,.08)); }
        .checkout-summary { background: rgba(255,255,255,.055); border: 1px solid var(--line); border-radius: 18px; padding: 17px; }
        .order-total { font-size: 1.45rem; font-weight: 900; color: var(--accent); }
        .qty-input { display: flex; align-items: center; }
        .qty-input input { width: 58px; text-align: center; border: 1.5px solid var(--line); border-radius: 10px; margin: 0 6px; padding: .3rem; font-weight: 900; background: rgba(255,255,255,.07); color: var(--ink); }
        .modern-pagination .page-link { border: 1px solid var(--line); color: var(--ink-soft); margin: 0 3px; border-radius: 11px; background: rgba(255,255,255,.06); }
        .modern-pagination .page-item.active .page-link { background: var(--grad-main); border-color: transparent; color: #06111f; }
        .badge-status { border-radius: 999px; padding: 6px 10px; font-size: .72rem; font-weight: 900; border: 1px solid var(--line); }
        .status-new { background: rgba(34,211,238,.12); color: var(--cyan); }
        .status-processing { background: rgba(124,60,255,.14); color: var(--primary-light); }
        .status-shipping { background: rgba(251,191,36,.13); color: var(--warning); }
        .status-completed { background: rgba(0,245,200,.12); color: var(--accent); }
        .status-cancelled { background: rgba(255,59,107,.12); color: var(--rose); }
        .progress-line { height: 8px; background: rgba(255,255,255,.08); border-radius: 999px; overflow: hidden; }
        .progress-line span { display:block; height:100%; background: var(--grad-main); border-radius: inherit; }
        .animate-in { animation: fadeUp .48s ease both; }
        @keyframes fadeUp { from { opacity: 0; transform: translateY(18px); } to { opacity: 1; transform: translateY(0); } }
        .footer { background: rgba(3,7,18,.82); color: var(--muted); margin-top: auto; padding: 48px 0 22px; border-top: 1px solid var(--line); backdrop-filter: blur(20px); }
        .footer h5 { color: var(--ink); font-weight: 900; font-size: 1rem; margin-bottom: 14px; }
        .footer p, .footer li { font-size: .86rem; line-height: 1.7; }
        .footer a { color: var(--muted); text-decoration: none; transition: var(--transition); }
        .footer a:hover { color: var(--accent); text-decoration: none; }
        .footer .social-icons a { width: 38px; height: 38px; border-radius: 13px; background: rgba(255,255,255,.08); display: inline-flex; align-items: center; justify-content: center; margin-right: 7px; border: 1px solid var(--line); }
        .footer hr { border-color: var(--line); margin: 24px 0 16px; }
        .quick-view-img { width: 100%; min-height: 280px; object-fit: cover; border-radius: 22px; border: 1px solid var(--line); background: rgba(255,255,255,.06); }
        .modal-content { background: var(--surface-2); color: var(--ink); border: 1px solid var(--line); border-radius: 24px; backdrop-filter: blur(22px); box-shadow: var(--shadow-lg); }
        .modal-header, .modal-footer { border-color: var(--line); }
        .toast-lite { position: fixed; right: 22px; bottom: 22px; z-index: 2000; background: var(--surface-2); color: var(--ink); border: 1px solid var(--line); border-radius: 16px; padding: 12px 15px; box-shadow: var(--shadow-md); display: none; }

        /* Auth Dropdown */
        .dropdown-item { color: var(--ink-soft); border-radius: 8px; padding: .5rem .75rem; font-weight: 700; font-size: .85rem; transition: var(--transition); }
        .dropdown-item:hover { background: rgba(255,255,255,.08); color: var(--ink); }
        .dropdown-item-text { display: block; padding: .3rem .75rem; }



        .top-announcement {
            background: linear-gradient(90deg, rgba(124,60,255,.18), rgba(34,211,238,.12), rgba(0,245,200,.16));
            border-bottom: 1px solid var(--line);
            color: var(--ink-soft);
            font-size: .78rem;
            font-weight: 800;
            padding: 8px 0;
            backdrop-filter: blur(18px);
        }
        .top-announcement .container { display:flex; justify-content:space-between; align-items:center; gap:12px; flex-wrap:wrap; }
        .top-announcement span { display:inline-flex; align-items:center; gap:7px; margin-right:14px; }
        .brand-logo-img { width:44px; height:44px; border-radius:16px; box-shadow:0 0 30px rgba(34,211,238,.34); }
        .brand-text small { display:block; font-family:'Inter', sans-serif; font-size:.63rem; letter-spacing:.16em; color:var(--accent); margin-top:-3px; }
        .nav-search { min-width: 260px; max-width: 360px; width: 30vw; margin-left: 18px; }
        .nav-search .input-group { background: rgba(255,255,255,.07); border:1px solid var(--line); border-radius:999px; overflow:hidden; }
        .nav-search .form-control { border:0; background:transparent; min-height:39px; height:39px; font-size:.83rem; padding-left:16px; }
        .nav-search .btn { border-radius:999px; padding:.45rem .78rem; margin:3px; }
        .premium-banner {
            display:grid; grid-template-columns:1.2fr .8fr; gap:18px; align-items:stretch; margin-bottom:24px;
        }
        .premium-banner-card {
            border:1px solid var(--line); border-radius:var(--radius-xl); padding:24px; position:relative; overflow:hidden;
            background:linear-gradient(135deg, rgba(124,60,255,.22), rgba(34,211,238,.10)), var(--surface);
            box-shadow:var(--shadow-md); backdrop-filter:blur(20px);
        }
        .premium-banner-card::before { content:''; position:absolute; inset:-35% auto auto 60%; width:360px; height:360px; border-radius:999px; background:radial-gradient(circle, rgba(0,245,200,.30), transparent 62%); }
        .banner-title { font-size:1.55rem; font-weight:900; margin:0 0 8px; color:var(--ink); }
        .feature-strip { display:grid; grid-template-columns:repeat(4,1fr); gap:12px; margin-bottom:24px; }
        .feature-pill { border:1px solid var(--line); border-radius:18px; background:rgba(255,255,255,.055); padding:14px; display:flex; align-items:center; gap:11px; color:var(--ink-soft); font-weight:800; }
        .feature-pill i { width:38px; height:38px; border-radius:14px; display:inline-flex; align-items:center; justify-content:center; background:var(--grad-main); color:#06111f; flex-shrink:0; }
        .detail-gallery-badge { position:absolute; left:28px; top:28px; z-index:2; }
        .product-hero-img { width:100%; height:520px; object-fit:cover; border-radius:24px; border:1px solid var(--line); box-shadow:var(--shadow-md); background:rgba(255,255,255,.06); }
        .related-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; }
        .support-fab { position:fixed; right:22px; bottom:82px; z-index:1500; display:flex; flex-direction:column; gap:10px; }
        .support-fab a { width:46px; height:46px; border-radius:16px; display:flex; align-items:center; justify-content:center; color:#06111f; background:var(--grad-main); box-shadow:var(--shadow-md); }
        .checkout-steps { display:grid; grid-template-columns:repeat(3,1fr); gap:10px; margin-bottom:18px; }
        .checkout-step { border:1px solid var(--line); border-radius:16px; padding:12px; background:rgba(255,255,255,.055); font-weight:900; color:var(--ink-soft); display:flex; align-items:center; gap:10px; }
        .checkout-step.active { color:var(--accent); border-color:rgba(0,245,200,.35); background:rgba(0,245,200,.08); }
        .payment-info { display:none; margin-top:10px; border:1px dashed rgba(34,211,238,.36); border-radius:16px; padding:12px; color:var(--text-secondary); background:rgba(34,211,238,.055); }
        .payment-info.active { display:block; }
        @media (max-width: 1199px) { .nav-search { width:100%; max-width:none; margin:10px 0; } }
        @media (max-width: 991px) { .premium-banner { grid-template-columns:1fr; } .feature-strip, .related-grid { grid-template-columns:repeat(2,1fr); } }
        @media (max-width: 576px) { .feature-strip, .related-grid, .checkout-steps { grid-template-columns:1fr; } .support-fab { display:none; } .product-hero-img { height:330px; } }

        @media (max-width: 991px) { .stats-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
        @media (max-width: 767px) { .main-content { padding: 20px 0 34px; } .page-header { padding: 24px; border-radius: 24px; } .product-card .card-img-top, .img-placeholder { height: 190px; } }
        @media (max-width: 576px) { .stats-grid { grid-template-columns: 1fr; } .navbar-custom .nav-link { padding: .55rem .7rem !important; } }


        /* ==========================================================
           NovaTech Luxury Tech System Override — Apple x Razer x Vercel
           Appended intentionally at the end of header.php to override Bootstrap 4.6
        ========================================================== */
        :root {
            --font-body: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            --font-heading: 'Space Grotesk', 'Inter', system-ui, sans-serif;
            --font-brand: 'Orbitron', 'Space Grotesk', sans-serif;
            --font-mono: 'JetBrains Mono', 'DM Mono', ui-monospace, SFMono-Regular, Menlo, Consolas, monospace;
            --heading-color: #f8fbff;
            --body-color: #d6e4f7;
            --color-success-glow: 0 0 34px rgba(0, 245, 200, .38), 0 0 86px rgba(0, 245, 200, .16);
            --color-warning-glow: 0 0 34px rgba(251, 191, 36, .36), 0 0 86px rgba(251, 191, 36, .14);
            --glass-bg: linear-gradient(145deg, rgba(255,255,255,.105), rgba(255,255,255,.035));
            --glass-stroke: linear-gradient(135deg, rgba(255,255,255,.38), rgba(34,211,238,.34), rgba(124,60,255,.35), rgba(255,255,255,.10));
            --cta-conic: conic-gradient(from 210deg at 50% 50%, #7c3cff, #22d3ee, #00f5c8, #7c3cff);
            --noise-layer: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='160' height='160' viewBox='0 0 160 160'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.85' numOctaves='3' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='160' height='160' filter='url(%23n)' opacity='.28'/%3E%3C/svg%3E");
        }
        html[data-theme="light"] {
            --heading-color: #07111f;
            --body-color: #263a58;
            --glass-bg: linear-gradient(145deg, rgba(255,255,255,.88), rgba(255,255,255,.58));
            --glass-stroke: linear-gradient(135deg, rgba(255,255,255,.95), rgba(34,211,238,.34), rgba(124,60,255,.24), rgba(13,31,55,.08));
        }
        body {
            font-family: var(--font-body);
            color: var(--body-color);
            background-image:
                var(--noise-layer),
                radial-gradient(1100px 620px at 8% -8%, rgba(124,60,255,.38), transparent 62%),
                radial-gradient(880px 520px at 96% 0%, rgba(34,211,238,.26), transparent 60%),
                radial-gradient(760px 520px at 35% 105%, rgba(0,245,200,.17), transparent 60%),
                linear-gradient(180deg, var(--bg), var(--bg));
            background-blend-mode: soft-light, normal, normal, normal, normal;
            line-height: 1.65;
        }
        h1, h2, h3, h4, h5, h6,
        .page-header h2, .banner-title, .card-title, .modal-title {
            font-family: var(--font-heading);
            line-height: 1.1;
            letter-spacing: -.035em;
            color: var(--heading-color);
        }
        p, .card-text, .text-muted, small { line-height: 1.65; }
        .font-display, .navbar-custom .navbar-brand { font-family: var(--font-brand); }
        .price, .stat-value, .cart-count, .nav-count, .badge, .badge-status, .product-kicker, .category-badge { font-family: var(--font-mono); }

        .card, .stat-card, .feature-pill, .premium-banner-card, .checkout-summary, .modal-content, .dropdown-menu {
            border: 1px solid transparent !important;
            background: var(--glass-bg) padding-box, var(--glass-stroke) border-box !important;
            backdrop-filter: blur(24px) saturate(180%);
            -webkit-backdrop-filter: blur(24px) saturate(180%);
            box-shadow: var(--shadow-md), inset 0 1px 0 rgba(255,255,255,.13), inset 0 -42px 80px rgba(255,255,255,.025);
            position: relative;
            overflow: hidden;
        }
        .card::after, .stat-card::after, .feature-pill::after, .premium-banner-card::after {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--noise-layer);
            opacity: .055;
            mix-blend-mode: overlay;
            pointer-events: none;
        }
        .card-header, .modal-header {
            box-shadow: inset 0 1px 0 rgba(255,255,255,.12), inset 0 -1px 0 rgba(255,255,255,.06);
        }

        .btn {
            position: relative;
            overflow: hidden;
            border-radius: 999px;
            font-weight: 900;
            letter-spacing: -.01em;
            will-change: transform;
        }
        .btn-primary, .btn-cart {
            background: var(--cta-conic) !important;
            color: #04101f !important;
            border: 0 !important;
            box-shadow: 0 0 0 1px rgba(255,255,255,.13) inset, 0 14px 32px rgba(34,211,238,.20), var(--color-success-glow);
        }
        .btn-primary:hover, .btn-cart:hover { transform: translateY(-2px); filter: saturate(118%); }
        .btn .ripple-dot {
            position: absolute;
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: rgba(255,255,255,.7);
            transform: translate(-50%, -50%) scale(0);
            animation: rippleNova .58s ease-out forwards;
            pointer-events: none;
        }
        @keyframes rippleNova { to { opacity: 0; transform: translate(-50%, -50%) scale(18); } }
        @keyframes cartBounce { 0%,100%{ transform: translateY(0) scale(1); } 38%{ transform: translateY(-5px) scale(1.18); } 68%{ transform: translateY(1px) scale(.96); } }
        .btn-cart.is-bouncing i, .js-add-cart.is-bouncing i { animation: cartBounce .58s cubic-bezier(.2,.9,.2,1.35); }

        .form-control {
            background: rgba(3,8,20,.50) !important;
            border-color: rgba(255,255,255,.13) !important;
            color: var(--ink) !important;
            box-shadow: inset 0 1px 0 rgba(255,255,255,.06);
        }
        html[data-theme="light"] .form-control { background: rgba(255,255,255,.76) !important; }
        .form-control:focus {
            border-color: rgba(34,211,238,.72) !important;
            box-shadow: 0 0 0 .2rem rgba(34,211,238,.13), 0 0 34px rgba(34,211,238,.18), inset 0 1px 0 rgba(255,255,255,.09) !important;
        }
        .floating-field { position: relative; }
        .floating-field .form-control { padding-top: 1.08rem; }
        .floating-field label {
            position: absolute;
            top: 50%; left: 16px;
            transform: translateY(-50%);
            margin: 0;
            color: var(--muted);
            pointer-events: none;
            transition: .2s cubic-bezier(.2,.8,.2,1);
            background: transparent;
        }
        .floating-field .form-control:focus + label,
        .floating-field .form-control:not(:placeholder-shown) + label {
            top: 8px;
            transform: translateY(0);
            font-size: .66rem;
            color: var(--cyan);
            letter-spacing: .08em;
            text-transform: uppercase;
        }

        .top-announcement { background: rgba(3,7,18,.56); backdrop-filter: blur(20px) saturate(170%); }
        html[data-theme="light"] .top-announcement { background: rgba(248,252,255,.72); }
        .navbar-custom {
            background: rgba(3,7,18,.54) !important;
            backdrop-filter: blur(22px) saturate(170%);
            -webkit-backdrop-filter: blur(22px) saturate(170%);
            transition: background .28s ease, box-shadow .28s ease, border-color .28s ease, backdrop-filter .28s ease;
        }
        .navbar-custom.is-scrolled {
            background: rgba(3,7,18,.84) !important;
            backdrop-filter: blur(30px) saturate(190%);
            -webkit-backdrop-filter: blur(30px) saturate(190%);
            border-bottom-color: rgba(34,211,238,.18);
            box-shadow: 0 18px 55px rgba(0,0,0,.34), 0 0 36px rgba(34,211,238,.08);
        }
        html[data-theme="light"] .navbar-custom.is-scrolled { background: rgba(248,252,255,.9) !important; }
        .navbar-custom .nav-link {
            background: transparent !important;
            box-shadow: none !important;
            border-radius: 0;
            position: relative;
            isolation: isolate;
        }
        .navbar-custom .nav-link::after {
            content: '';
            position: absolute;
            left: .86rem;
            right: .86rem;
            bottom: .32rem;
            height: 2px;
            border-radius: 999px;
            background: var(--grad-main);
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .24s cubic-bezier(.2,.8,.2,1);
            box-shadow: 0 0 14px rgba(34,211,238,.42);
        }
        .navbar-custom .nav-link:hover::after, .navbar-custom .nav-link.active::after { transform: scaleX(1); }
        .nav-live-dot {
            width: 8px; height: 8px; border-radius: 999px; display: inline-block; margin-right: 6px;
            background: var(--accent); box-shadow: var(--color-success-glow); animation: livePulse 1.45s ease infinite;
        }
        @keyframes livePulse { 0%,100%{ opacity: 1; transform: scale(1); } 50%{ opacity: .35; transform: scale(.72); } }

        .nav-search { transition: width .28s ease, max-width .28s ease, filter .28s ease; }
        .nav-search .input-group {
            border: 1px solid transparent;
            background: rgba(255,255,255,.07) padding-box, var(--glass-stroke) border-box;
            transition: box-shadow .22s ease, transform .22s ease;
        }
        .nav-search:focus-within { width: 38vw; max-width: 470px; filter: drop-shadow(0 0 22px rgba(34,211,238,.22)); }
        .nav-search:focus-within .input-group { box-shadow: 0 0 0 3px rgba(34,211,238,.12), var(--color-success-glow); }

        .nav-category { position: relative; }
        .mega-menu-panel {
            position: absolute;
            top: calc(100% + 16px);
            left: 50%;
            transform: translateX(-50%) translateY(10px);
            width: min(620px, 92vw);
            opacity: 0;
            visibility: hidden;
            pointer-events: none;
            transition: .22s cubic-bezier(.2,.8,.2,1);
            z-index: 1060;
        }
        .nav-category:hover .mega-menu-panel, .nav-category:focus-within .mega-menu-panel {
            opacity: 1; visibility: visible; pointer-events: auto; transform: translateX(-50%) translateY(0);
        }
        .mega-menu-card {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            padding: 16px;
            border-radius: 24px;
            border: 1px solid transparent;
            background: var(--glass-bg) padding-box, var(--glass-stroke) border-box;
            backdrop-filter: blur(28px) saturate(190%);
            box-shadow: var(--shadow-lg), inset 0 1px 0 rgba(255,255,255,.12);
            overflow: hidden;
        }
        .mega-menu-card::before {
            content: '';
            position: absolute;
            inset: 0;
            background-image: var(--noise-layer);
            opacity: .055;
            pointer-events: none;
        }
        .mega-menu-item {
            display: flex;
            gap: 12px;
            padding: 13px;
            border-radius: 18px;
            color: var(--ink-soft);
            background: rgba(255,255,255,.045);
            border: 1px solid rgba(255,255,255,.075);
            transition: .2s ease;
        }
        .mega-menu-item:hover { color: var(--ink); transform: translateY(-2px); background: rgba(34,211,238,.09); }
        .mega-menu-item i { width: 38px; height: 38px; border-radius: 14px; background: var(--cta-conic); color: #04101f; display: inline-flex; align-items:center; justify-content:center; flex-shrink:0; }
        .mega-menu-item strong { display:block; font-family:var(--font-heading); color:var(--heading-color); line-height:1.1; }
        .mega-menu-item small { display:block; color:var(--muted); margin-top:4px; }

        .mobile-nav-backdrop { display:none; position:fixed; inset:0; background:rgba(0,0,0,.56); z-index:1040; opacity:0; transition:opacity .22s ease; }
        .mobile-nav-backdrop.is-open { display:block; opacity:1; }
        .navbar-toggler { border-radius: 14px; padding: .55rem .7rem; }

        .page-header {
            background:
                var(--noise-layer),
                radial-gradient(600px 340px at 8% 8%, rgba(255,255,255,.20), transparent 52%),
                linear-gradient(135deg, rgba(124,60,255,.82), rgba(34,211,238,.58), rgba(0,245,200,.38));
            background-blend-mode: overlay, normal, normal;
        }
        .page-header::before { animation: auroraDrift 9s ease-in-out infinite alternate; }
        @keyframes auroraDrift { from { transform: translate3d(0,0,0) scale(1); } to { transform: translate3d(-60px,36px,0) scale(1.18); } }

        .toast-lite {
            display: block;
            opacity: 0;
            transform: translate3d(28px, 28px, 0) scale(.96);
            transition: opacity .32s cubic-bezier(.2,.9,.2,1.25), transform .32s cubic-bezier(.2,.9,.2,1.25);
            pointer-events: none;
        }
        .toast-lite.is-visible { opacity: 1; transform: translate3d(0,0,0) scale(1); }
        .cart-count, .nav-count { animation: badgeBounce .58s cubic-bezier(.2,.9,.2,1.35); }
        @keyframes badgeBounce { 0%{ transform: scale(.72); } 45%{ transform: scale(1.22); } 100%{ transform: scale(1); } }

        @media (max-width: 991px) {
            .navbar-collapse {
                display: block !important;
                position: fixed;
                top: 0;
                right: 0;
                width: min(390px, 88vw);
                height: 100vh;
                padding: 92px 20px 24px;
                transform: translateX(108%);
                transition: transform .32s cubic-bezier(.2,.8,.2,1);
                background: rgba(3,7,18,.92);
                backdrop-filter: blur(30px) saturate(190%);
                border-left: 1px solid rgba(255,255,255,.12);
                box-shadow: -24px 0 70px rgba(0,0,0,.42);
                z-index: 1055;
                overflow-y: auto;
            }
            html[data-theme="light"] .navbar-collapse { background: rgba(248,252,255,.94); }
            .navbar-collapse.show { transform: translateX(0); }
            .navbar-custom .container { position: relative; }
            .navbar-toggler { position: relative; z-index: 1060; }
            .nav-search { min-width: 0; width: 100% !important; max-width: none !important; margin: 0 0 14px; }
            .navbar-nav { align-items: stretch !important; }
            .navbar-custom .nav-link { padding: .86rem .2rem !important; }
            .navbar-custom .nav-link::after { left: 0; right: auto; width: 90px; bottom: .44rem; }
            .mega-menu-panel { position: static; transform: none !important; opacity: 1; visibility: visible; pointer-events: auto; width: 100%; margin: 8px 0 12px; }
            .mega-menu-card { grid-template-columns: 1fr; padding: 10px; border-radius: 18px; }
        }
        @media (max-width: 767px) {
            .product-card .card-img-top, .img-placeholder { height: auto; }
        }
        @media (prefers-reduced-motion: reduce) {
            *, *::before, *::after { animation-duration: .001ms !important; animation-iteration-count: 1 !important; scroll-behavior: auto !important; transition-duration: .001ms !important; }
        }

    

/* === NovaTech global hardening + polish pack === */
:root { --color-success-glow: rgba(0,245,200,.34); --color-warning-glow: rgba(251,191,36,.32); --font-heading:'Space Grotesk',Inter,sans-serif; --font-mono:'JetBrains Mono','DM Mono',monospace; }
h1,h2,h3,.font-display{font-family:var(--font-heading)!important;line-height:1.1;letter-spacing:-.035em}.brand-text{font-family:'Orbitron',var(--font-heading)!important}.price,.nav-count,.cart-count,.badge,.mini-chip{font-family:var(--font-mono)}
.card,.product-card{position:relative;border:1px solid transparent;background-clip:padding-box;backdrop-filter:blur(24px) saturate(180%);-webkit-backdrop-filter:blur(24px) saturate(180%)}
.card::before,.product-card::before{content:'';position:absolute;inset:-1px;border-radius:inherit;padding:1px;background:linear-gradient(135deg,rgba(124,60,255,.28),rgba(34,211,238,.18),rgba(0,245,200,.14),transparent 60%);-webkit-mask:linear-gradient(#fff 0 0) content-box,linear-gradient(#fff 0 0);-webkit-mask-composite:xor;mask-composite:exclude;pointer-events:none;z-index:0}.card>*{position:relative;z-index:1}.card-header{box-shadow:inset 0 1px 0 rgba(255,255,255,.12)}
body::before{content:'';position:fixed;inset:0;pointer-events:none;z-index:-1;opacity:.07;background-image:url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='140' height='140' viewBox='0 0 140 140'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.8' numOctaves='2' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='140' height='140' filter='url(%23n)' opacity='.45'/%3E%3C/svg%3E")}
.btn-primary{background:conic-gradient(from 180deg at 50% 50%,var(--accent),var(--cyan),#7c3cff,var(--accent))!important;border-color:transparent!important}.btn{position:relative;overflow:hidden}.btn .ripple,.btn .ripple-dot{position:absolute;border-radius:50%;transform:scale(0);animation:rippleAnim .55s linear;background:rgba(255,255,255,.32);pointer-events:none}@keyframes rippleAnim{to{transform:scale(4);opacity:0}}
@keyframes shimmer{0%{background-position:-400px 0}100%{background-position:400px 0}}.skeleton{background:linear-gradient(90deg,rgba(255,255,255,.06) 25%,rgba(255,255,255,.14) 50%,rgba(255,255,255,.06) 75%);background-size:400px 100%;animation:shimmer 1.4s infinite;border-radius:12px}html[data-theme="light"] .skeleton{background:linear-gradient(90deg,#f0f0f0 25%,#e0e0e0 50%,#f0f0f0 75%);background-size:400px 100%}
.breadcrumb-nav{display:flex;align-items:center;gap:8px;font-size:.78rem;font-weight:800;color:var(--muted);margin-bottom:14px;flex-wrap:wrap}.breadcrumb-nav a{color:var(--muted);transition:var(--transition)}.breadcrumb-nav a:hover{color:var(--accent);text-decoration:none}.breadcrumb-nav .sep{color:var(--border)}.breadcrumb-nav .current{color:var(--ink-soft)}
.product-tag{position:absolute;top:12px;left:12px;z-index:10;padding:4px 10px;border-radius:999px;font-size:.68rem;font-weight:900;letter-spacing:.06em;text-transform:uppercase;backdrop-filter:blur(8px);border:1px solid rgba(255,255,255,.18);box-shadow:0 4px 12px rgba(0,0,0,.2)}
.img-lazy{filter:blur(8px);transform:scale(1.04);transition:filter .4s ease,transform .4s ease}.img-lazy.loaded{filter:none;transform:scale(1)}
.float-group{position:relative;margin-bottom:1.2rem}.float-group input.form-control,.float-group textarea.form-control{padding-top:1.5rem;padding-bottom:.4rem}.float-group label{position:absolute;top:50%;left:15px;transform:translateY(-50%);font-size:.9rem;color:var(--muted);pointer-events:none;transition:all .2s ease;font-weight:800;margin:0}.float-group textarea~label{top:1rem;transform:none}.float-group input:not(:placeholder-shown)~label,.float-group input:focus~label,.float-group textarea:not(:placeholder-shown)~label,.float-group textarea:focus~label{top:.45rem;transform:none;font-size:.7rem;color:var(--accent);letter-spacing:.04em}
.search-dropdown{position:absolute;top:calc(100% + 6px);left:0;right:0;background:var(--surface-2);border:1px solid var(--border);border-radius:var(--radius-md);box-shadow:var(--shadow-lg);backdrop-filter:blur(22px);z-index:2000;max-height:420px;overflow-y:auto;display:none}.search-item{display:flex;align-items:center;gap:12px;padding:10px 14px;cursor:pointer;border-bottom:1px solid var(--border-light);transition:var(--transition);text-decoration:none}.search-item:hover{background:rgba(255,255,255,.08);text-decoration:none}.search-item img{width:42px;height:42px;border-radius:10px;object-fit:cover}.search-item-info .name{font-weight:900;font-size:.85rem;color:var(--ink)}.search-item-info .price{font-size:.78rem;color:var(--accent);font-weight:800}
.confirm-premium .modal-content{border-radius:24px;background:var(--surface-2);border:1px solid var(--border);box-shadow:var(--shadow-lg);backdrop-filter:blur(24px)}.toast-lite{position:fixed!important;right:22px;bottom:22px;z-index:3000;animation:toastIn .44s cubic-bezier(.2,1.4,.4,1)}@keyframes toastIn{from{transform:translate3d(120%,30px,0);opacity:0}to{transform:none;opacity:1}}
.progress-line span{display:block;height:100%;border-radius:inherit;background:linear-gradient(90deg,var(--accent),var(--cyan))}.rating-stars{color:var(--warning);letter-spacing:.08em}.stat-value{transition:color .3s}.page-header{background-size:200% 200%;animation:auroraShift 8s ease infinite}@keyframes auroraShift{0%,100%{background-position:0% 50%}50%{background-position:100% 50%}}
.form-control:focus{border-color:rgba(0,245,200,.85)!important;box-shadow:0 0 0 3px rgba(0,245,200,.12),0 0 20px rgba(0,245,200,.08)!important}::-webkit-scrollbar{width:6px;height:6px}::-webkit-scrollbar-track{background:transparent}::-webkit-scrollbar-thumb{background:rgba(34,211,238,.28);border-radius:999px}::-webkit-scrollbar-thumb:hover{background:rgba(34,211,238,.5)}
@media(prefers-reduced-motion:reduce){*,*::before,*::after{animation-duration:.01ms!important;animation-iteration-count:1!important;scroll-behavior:auto!important}}

/* ========================================
   Premium User Dropdown Menu
======================================== */
.user-dropdown-menu {
    border-radius: 20px !important;
    padding: 0 !important;
    min-width: 260px !important;
    overflow: hidden;
    border: 1px solid transparent !important;
    background: var(--glass-bg) padding-box, var(--glass-stroke) border-box !important;
    backdrop-filter: blur(28px) saturate(190%) !important;
    box-shadow: var(--shadow-lg), 0 0 40px rgba(124,60,255,.12) !important;
}
.user-dropdown-header {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 18px 18px 14px;
    background: linear-gradient(135deg, rgba(124,60,255,.15), rgba(34,211,238,.08));
}
.user-dropdown-avatar {
    width: 46px;
    height: 46px;
    border-radius: 14px;
    background: var(--cta-conic);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #04101f;
    font-size: 1.1rem;
    flex-shrink: 0;
    box-shadow: 0 0 22px rgba(34,211,238,.3);
    position: relative;
}
.user-dropdown-avatar::after {
    content: '';
    position: absolute;
    inset: -3px;
    border-radius: 17px;
    border: 1.5px solid rgba(34,211,238,.25);
}
.user-dropdown-info {
    display: flex;
    flex-direction: column;
    gap: 2px;
    min-width: 0;
}
.user-dropdown-info strong {
    font-size: .9rem;
    font-weight: 900;
    color: var(--heading-color);
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.user-dropdown-info span {
    font-size: .72rem;
    font-weight: 800;
    color: var(--accent);
    text-transform: uppercase;
    letter-spacing: .08em;
}
.user-dropdown-menu .dropdown-item {
    color: var(--ink-soft) !important;
    padding: 10px 18px !important;
    border-radius: 0 !important;
    font-weight: 700;
    font-size: .85rem;
    transition: var(--transition);
    display: flex;
    align-items: center;
}
.user-dropdown-menu .dropdown-item:hover {
    background: rgba(34,211,238,.08) !important;
    color: var(--ink) !important;
    padding-left: 22px !important;
}
.user-dropdown-menu .dropdown-item i {
    width: 18px;
    text-align: center;
    color: var(--muted);
    transition: var(--transition);
}
.user-dropdown-menu .dropdown-item:hover i {
    color: var(--accent);
}
.dropdown-logout {
    color: var(--rose) !important;
    background: rgba(255,59,107,.05);
    margin: 0;
}
.dropdown-logout:hover {
    background: rgba(255,59,107,.14) !important;
    color: #fff !important;
}
.dropdown-logout i {
    color: var(--rose) !important;
}

/* ========================================
   Premium Auth Pages (Login / Register)
======================================== */
.auth-wrapper {
    min-height: calc(100vh - 300px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 40px 16px;
    position: relative;
}
.auth-wrapper::before {
    content: '';
    position: absolute;
    width: 500px;
    height: 500px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(124,60,255,.25), transparent 60%);
    top: -120px;
    left: -160px;
    filter: blur(60px);
    pointer-events: none;
    animation: authOrb1 8s ease-in-out infinite alternate;
}
.auth-wrapper::after {
    content: '';
    position: absolute;
    width: 400px;
    height: 400px;
    border-radius: 50%;
    background: radial-gradient(circle, rgba(0,245,200,.20), transparent 60%);
    bottom: -100px;
    right: -120px;
    filter: blur(50px);
    pointer-events: none;
    animation: authOrb2 10s ease-in-out infinite alternate;
}
@keyframes authOrb1 { from { transform: translate(0, 0); } to { transform: translate(60px, 40px); } }
@keyframes authOrb2 { from { transform: translate(0, 0); } to { transform: translate(-50px, -30px); } }

.auth-card {
    width: 100%;
    max-width: 480px;
    position: relative;
    z-index: 1;
}
.auth-card .card {
    border-radius: 28px !important;
    overflow: hidden;
}
.auth-card .card-body {
    padding: 44px 40px !important;
}
.auth-logo-ring {
    width: 80px;
    height: 80px;
    border-radius: 24px;
    background: var(--cta-conic);
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 20px;
    box-shadow: 0 0 40px rgba(34,211,238,.3), 0 0 80px rgba(124,60,255,.15);
    position: relative;
    animation: logoFloat 4s ease-in-out infinite;
}
.auth-logo-ring::after {
    content: '';
    position: absolute;
    inset: -5px;
    border-radius: 28px;
    border: 2px solid rgba(34,211,238,.2);
    animation: logoPulse 2s ease-in-out infinite;
}
@keyframes logoFloat { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-6px); } }
@keyframes logoPulse { 0%, 100% { opacity: .4; transform: scale(1); } 50% { opacity: .8; transform: scale(1.06); } }
.auth-logo-ring i {
    font-size: 2rem;
    color: #04101f;
}
.auth-title {
    font-size: 1.6rem;
    font-weight: 900;
    margin: 0 0 4px;
    background: var(--grad-main);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.auth-subtitle {
    color: var(--muted);
    font-size: .86rem;
    margin: 0 0 6px;
}
.auth-card .float-group {
    margin-bottom: 1.4rem;
}
.auth-card .float-group .form-control {
    border-radius: 16px;
    min-height: 52px;
    font-size: .92rem;
    padding-left: 48px;
    padding-top: 1.4rem;
    padding-bottom: .5rem;
}
.auth-card .float-group label {
    left: 48px;
}
.auth-card .float-group .input-icon {
    position: absolute;
    left: 16px;
    top: 50%;
    transform: translateY(-50%);
    color: var(--muted);
    font-size: .95rem;
    z-index: 2;
    transition: var(--transition);
    pointer-events: none;
}
.auth-card .float-group .form-control:focus ~ .input-icon {
    color: var(--accent);
}
.auth-card .float-group input:not(:placeholder-shown) ~ label,
.auth-card .float-group input:focus ~ label {
    left: 48px;
}
.auth-btn {
    width: 100%;
    padding: 14px !important;
    border-radius: 16px !important;
    font-size: .95rem !important;
    font-weight: 900;
    letter-spacing: .01em;
    position: relative;
    overflow: hidden;
}
.auth-btn::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,.18), transparent);
    transition: left .5s ease;
}
.auth-btn:hover::before {
    left: 100%;
}
.auth-divider {
    display: flex;
    align-items: center;
    gap: 12px;
    margin: 24px 0;
    color: var(--muted);
    font-size: .78rem;
    font-weight: 700;
}
.auth-divider::before,
.auth-divider::after {
    content: '';
    flex: 1;
    height: 1px;
    background: linear-gradient(90deg, transparent, var(--border), transparent);
}
.auth-link {
    color: var(--accent) !important;
    font-weight: 800;
    text-decoration: none;
    position: relative;
    transition: var(--transition);
}
.auth-link::after {
    content: '';
    position: absolute;
    bottom: -2px;
    left: 0;
    right: 0;
    height: 2px;
    background: var(--grad-main);
    transform: scaleX(0);
    transition: transform .24s ease;
}
.auth-link:hover {
    color: var(--cyan) !important;
    text-decoration: none;
}
.auth-link:hover::after {
    transform: scaleX(1);
}
.auth-demo-box {
    background: rgba(34,211,238,.06);
    border: 1px solid rgba(34,211,238,.18);
    border-radius: 14px;
    padding: 12px 16px;
    display: flex;
    align-items: center;
    gap: 10px;
}
.auth-demo-box i {
    color: var(--cyan);
    font-size: 1rem;
}
.auth-demo-box code {
    font-family: var(--font-mono);
    font-size: .82rem;
    font-weight: 700;
    color: var(--accent);
    background: transparent;
}
.password-toggle {
    position: absolute;
    right: 14px;
    top: 50%;
    transform: translateY(-50%);
    background: none;
    border: none;
    color: var(--muted);
    cursor: pointer;
    padding: 4px;
    z-index: 2;
    font-size: .9rem;
    transition: var(--transition);
}
.password-toggle:hover {
    color: var(--accent);
}
.auth-card .form-group label {
    font-size: .84rem;
    font-weight: 800;
    color: var(--ink-soft);
    display: flex;
    align-items: center;
    gap: 6px;
}
.auth-card .form-group .form-control {
    border-radius: 14px;
    min-height: 48px;
}
.auth-card .form-group .text-danger {
    color: var(--rose) !important;
}

@media (max-width: 576px) {
    .auth-card .card-body { padding: 30px 24px !important; }
    .auth-logo-ring { width: 64px; height: 64px; border-radius: 20px; }
    .auth-logo-ring i { font-size: 1.6rem; }
    .auth-title { font-size: 1.3rem; }
}

</style>

<style>
:root{
--primary:#2563eb!important;--primary-dark:#1d4ed8!important;--primary-light:#60a5fa!important;
--accent:#3b82f6!important;--bg:#f4f8fc!important;--surface:#ffffff!important;--surface-2:#ffffff!important;
--text:#0f172a!important;--ink:#0f172a!important;--muted:#64748b!important;--border:#e2e8f0!important;
}
body{background:#f4f8fc!important}
.hero,.hero-section,.hero-banner{background:linear-gradient(135deg,#2563eb,#60a5fa)!important;border-radius:24px!important}
.card,.product-card,.glass-card,.dashboard-card,.cart-item{
background:#fff!important;border:1px solid #e2e8f0!important;box-shadow:0 10px 30px rgba(0,0,0,.08)!important}
.btn-primary,.btn-success{background:#2563eb!important;border-color:#2563eb!important}
.navbar,.navbar-custom{background:#2563eb!important}
</style>
<style>
/* Responsive layout - balanced mobile & desktop */
html, body {
    min-width: 320px;
    max-width: 100%;
    overflow-x: hidden;
}
.container {
    padding-left: 16px;
    padding-right: 16px;
    margin: 0 auto;
    width: 100%;
}
/* Mobile first (< 768px) */
.container {
    max-width: 100%;
}
.navbar-custom {
    padding: 0.6rem 0;
}
.nav-search {
    width: 100%;
    max-width: none;
    margin: 10px 0 14px;
}
.navbar-collapse {
    display: block !important;
    position: fixed;
    top: 0;
    right: 0;
    width: min(380px, 85vw);
    height: 100vh;
    padding: 90px 18px 24px;
    transform: translateX(108%);
    transition: transform 0.32s cubic-bezier(0.2, 0.8, 0.2, 1);
    background: rgba(3,7,18,0.96);
    backdrop-filter: blur(28px) saturate(180%);
    border-left: 1px solid rgba(255,255,255,0.12);
    box-shadow: -20px 0 60px rgba(0,0,0,0.3);
    z-index: 1060;
    overflow-y: auto;
}
.navbar-collapse.show {
    transform: translateX(0);
}
.navbar-nav {
    align-items: stretch;
}
.navbar-custom .nav-link {
    border-radius: 16px;
    padding: 0.85rem 1rem;
    margin: 0 0 8px;
    font-size: 0.95rem;
}
.nav-category .mega-menu-panel {
    position: static !important;
    transform: none !important;
    opacity: 1 !important;
    visibility: visible !important;
    pointer-events: auto !important;
    width: 100%;
    margin: 8px 0 12px;
}
.mega-menu-card {
    grid-template-columns: 1fr !important;
    padding: 12px;
}
.main-content {
    padding: 12px 0 80px;
}
.page-header {
    padding: 20px;
    margin-bottom: 16px;
    border-radius: 20px;
}
.page-header h2 {
    font-size: 1.4rem;
}
.row {
    margin-left: -8px;
    margin-right: -8px;
}
.row > [class*="col-"] {
    padding-left: 8px;
    padding-right: 8px;
    width: 100%;
}
.table {
    font-size: 0.9rem;
}
.table td, .table th {
    padding: 0.65rem 0.75rem;
}
.form-control, .btn {
    min-height: 44px;
}
.support-fab {
    display: none;
}

/* Tablet & above (≥ 768px) */
@media (min-width: 768px) {
    .container {
        max-width: 720px;
    }
    .navbar-custom {
        padding: 0.4rem 0;
    }
    .nav-search {
        width: auto;
        max-width: 360px;
        margin: 0 0 0 18px;
    }
    .navbar-collapse {
        display: flex !important;
        position: static !important;
        width: auto !important;
        height: auto !important;
        padding: 0 !important;
        transform: none !important;
        background: transparent !important;
        backdrop-filter: none !important;
        border: none !important;
        box-shadow: none !important;
        z-index: auto !important;
        overflow: visible !important;
    }
    .navbar-nav {
        align-items: center !important;
    }
    .navbar-custom .nav-link {
        border-radius: 999px;
        padding: 0.65rem 1rem;
        margin: 0.1rem;
        font-size: 0.88rem;
        display: inline-block;
    }
    .nav-category .mega-menu-panel {
        position: absolute !important;
        top: calc(100% + 16px) !important;
        left: 50% !important;
        transform: translateX(-50%) translateY(10px) !important;
        width: min(620px, 92vw) !important;
        opacity: 0 !important;
        visibility: hidden !important;
        pointer-events: none !important;
        transition: 0.22s cubic-bezier(0.2, 0.8, 0.2, 1) !important;
        margin: 0 !important;
    }
    .nav-category:hover .mega-menu-panel {
        opacity: 1 !important;
        visibility: visible !important;
        pointer-events: auto !important;
        transform: translateX(-50%) translateY(0) !important;
    }
    .mega-menu-card {
        grid-template-columns: 1fr 1fr !important;
        padding: 16px !important;
    }
    .main-content {
        padding: 30px 0 54px;
    }
    .page-header {
        padding: 28px;
        margin-bottom: 24px;
        border-radius: 22px;
    }
    .page-header h2 {
        font-size: 1.62rem;
    }
    .row > [class*="col-"] {
        width: auto;
    }
    .table {
        font-size: 0.95rem;
    }
    .table td, .table th {
        padding: 0.75rem 1rem;
    }
    .form-control, .btn {
        min-height: 46px;
    }
    .support-fab {
        display: flex !important;
    }
}

/* Desktop (≥ 1024px) */
@media (min-width: 1024px) {
    .container {
        max-width: 960px;
    }
    .nav-search {
        max-width: 400px;
    }
    .page-header {
        padding: 32px;
    }
    .page-header h2 {
        font-size: 1.8rem;
    }
}

/* Wide desktop (≥ 1200px) */
@media (min-width: 1200px) {
    .container {
        max-width: 1140px;
    }
    .page-header {
        padding: 36px;
    }
    .page-header h2 {
        font-size: 2rem;
    }
    .table {
        font-size: 1rem;
    }
}

/* Extra large (≥ 1400px) */
@media (min-width: 1400px) {
    .container {
        max-width: 1320px;
    }
}

@media (max-width: 430px) {
    .container {
        padding-left: 12px;
        padding-right: 12px;
    }
    .navbar-collapse {
        width: 100vw;
    }
    .page-header {
        padding: 16px;
    }
    .page-header h2 {
        font-size: 1.2rem;
    }
}
</style>
</head>
<body>

<div class="top-announcement">
    <div class="container">
        <div>
            <span><i class="fas fa-bolt" style="color:var(--accent);"></i> Future Tech UI v2.0</span>
            <span><i class="fas fa-truck" style="color:var(--cyan);"></i> Freeship toàn quốc</span>
            <span><i class="fas fa-shield-alt" style="color:var(--warning);"></i> Bảo hành chính hãng</span>
        </div>
        <div><i class="fas fa-headset mr-1" style="color:var(--accent);"></i> Hotline: 1900 NOVA</div>
    </div>
</div>

<?php
$seoTitle = $seoTitle ?? 'NovaTech Store — Cửa hàng công nghệ tương lai';
$seoDescription = $seoDescription ?? 'Mua sắm điện thoại, laptop, phụ kiện công nghệ cao cấp tại NovaTech. Bảo hành chính hãng, giao hàng toàn quốc.';
$seoImage = $seoImage ?? 'uploads/novatech-logo.svg';
$seoUrl = $seoUrl ?? 'https://novatech.store/';
$seoRobots = $seoRobots ?? 'index, follow';
$cartCount = getCartCount();
$wishlistCount = getWishlistCount();
$compareCount = isset($_SESSION['compare']) ? count($_SESSION['compare']) : 0;
$currentUrl = $_GET['url'] ?? 'dashboard';
$currentSection = strtolower(explode('/', $currentUrl)[0]);
?>

<div class="mobile-nav-backdrop" id="mobileNavBackdrop"></div>
<nav class="navbar navbar-expand-lg navbar-dark navbar-custom" id="siteNavbar">
    <div class="container">
        <a class="navbar-brand" href="<?= isAdmin() ? 'index.php?url=dashboard' : 'index.php?url=product' ?>" aria-label="NovaTech">
            <img class="brand-logo-img" src="uploads/novatech-logo.svg" alt="NovaTech logo">
            <span class="brand-text">NovaTech<small>LUXURY TECH</small></span>
        </a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Mở menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <form class="nav-search" method="GET" action="index.php" role="search">
                <input type="hidden" name="url" value="product">
                <div class="input-group">
                    <input type="text" class="form-control" name="q" placeholder="Tìm iPhone, MacBook, Razer..." value="<?= e($_GET['q'] ?? '') ?>" aria-label="Tìm kiếm sản phẩm">
                    <div class="input-group-append"><button class="btn btn-primary" type="submit" aria-label="Tìm kiếm"><i class="fas fa-search"></i></button></div>
                </div>
            </form>
            <ul class="navbar-nav ml-auto align-items-lg-center">
                <?php if (isAdmin()): ?>
                <li class="nav-item"><a class="nav-link <?= $currentSection === 'dashboard' ? 'active' : '' ?>" href="index.php?url=dashboard"><span class="nav-live-dot"></span>Trung tâm</a></li>
                <?php endif; ?>
                <li class="nav-item"><a class="nav-link <?= $currentSection === 'product' ? 'active' : '' ?>" href="index.php?url=product"><i class="fas fa-store mr-1"></i>Sản phẩm</a></li>
                <li class="nav-item nav-category">
                    <a class="nav-link <?= $currentSection === 'category' ? 'active' : '' ?>" href="index.php?url=category" aria-haspopup="true"><i class="fas fa-layer-group mr-1"></i>Danh mục</a>
                    <div class="mega-menu-panel" aria-label="Mega menu danh mục">
                        <div class="mega-menu-card">
                            <a class="mega-menu-item" href="index.php?url=product&sort=newest"><i class="fas fa-sparkles"></i><span><strong>New Arrivals</strong><small>Sản phẩm mới nhất trong store</small></span></a>
                            <a class="mega-menu-item" href="index.php?url=product&sort=price_desc"><i class="fas fa-gem"></i><span><strong>Flagship Luxury</strong><small>Thiết bị cao cấp, hiệu năng mạnh</small></span></a>
                            <a class="mega-menu-item" href="index.php?url=category"><i class="fas fa-microchip"></i><span><strong>Danh mục</strong><small>Quản lý laptop, phone, audio, phụ kiện</small></span></a>
                            <a class="mega-menu-item" href="index.php?url=compare"><i class="fas fa-balance-scale"></i><span><strong>So sánh nhanh</strong><small>Đặt tối đa 4 sản phẩm cạnh nhau</small></span></a>
                        </div>
                    </div>
                </li>
                <li class="nav-item"><a class="nav-link <?= $currentSection === 'order' ? 'active' : '' ?>" href="index.php?url=order"><i class="fas fa-receipt mr-1"></i>Đơn hàng</a></li>
                <li class="nav-item"><a class="nav-link count-link <?= $currentSection === 'wishlist' ? 'active' : '' ?>" href="index.php?url=wishlist"><i class="fas fa-heart mr-1"></i>Yêu thích<?php if ($wishlistCount > 0): ?><span class="nav-count"><?= $wishlistCount ?></span><?php endif; ?></a></li>
                <li class="nav-item"><a class="nav-link count-link <?= $currentSection === 'compare' ? 'active' : '' ?>" href="index.php?url=compare"><i class="fas fa-balance-scale mr-1"></i>So sánh<?php if ($compareCount > 0): ?><span class="nav-count"><?= $compareCount ?></span><?php endif; ?></a></li>
                <li class="nav-item">
                    <a class="nav-link cart-link <?= $currentSection === 'cart' ? 'active' : '' ?>" href="index.php?url=cart">
                        <i class="fas fa-shopping-bag mr-1"></i>Giỏ hàng
                        <?php if ($cartCount > 0): ?><span class="cart-count"><?= $cartCount ?></span><?php endif; ?>
                    </a>
                </li>
                <?php if (isLoggedIn()): ?>
                <?php if (isAdmin()): ?>
                <li class="nav-item"><a class="nav-link" href="index.php?url=product/create"><i class="fas fa-plus mr-1"></i>Thêm SP</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?url=product/apimanager"><i class="fas fa-code mr-1"></i>API Manager</a></li>
                <li class="nav-item"><a class="nav-link <?= $currentSection === 'account' ? 'active' : '' ?>" href="index.php?url=account/manage"><i class="fas fa-users-cog mr-1"></i>Tài khoản</a></li>
                <?php endif; ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="userDropdown" data-toggle="dropdown">
                        <?php $navAvatarMini = !empty($_SESSION['avatar']) && file_exists(BASE_PATH . '/' . $_SESSION['avatar']) ? $_SESSION['avatar'] : ''; ?>
                        <?php if ($navAvatarMini): ?><img src="<?= e($navAvatarMini) ?>" alt="Avatar" style="width:24px;height:24px;border-radius:999px;object-fit:cover;margin-right:6px;border:1px solid var(--line)"><?php else: ?><i class="fas fa-user-circle mr-1"></i><?php endif; ?><?= e($_SESSION['fullname'] ?? $_SESSION['username'] ?? '') ?>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right user-dropdown-menu">
                        <div class="user-dropdown-header">
                            <?php $navAvatar = !empty($_SESSION['avatar']) && file_exists(BASE_PATH . '/' . $_SESSION['avatar']) ? $_SESSION['avatar'] : ''; ?>
                            <div class="user-dropdown-avatar" style="overflow:hidden">
                                <?php if ($navAvatar): ?>
                                    <img src="<?= e($navAvatar) ?>" alt="Avatar" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">
                                <?php else: ?>
                                    <i class="fas fa-user"></i>
                                <?php endif; ?>
                            </div>
                            <div class="user-dropdown-info">
                                <strong><?= e($_SESSION['fullname'] ?? $_SESSION['username'] ?? '') ?></strong>
                                <span><?= e($_SESSION['role'] ?? 'user') === 'admin' ? 'Quản trị viên' : 'Thành viên' ?><?= !empty($_SESSION['email']) ? ' • ' . e($_SESSION['email']) : '' ?></span>
                            </div>
                        </div>
                        <div class="dropdown-divider" style="border-color:var(--line);margin:0"></div>
                        <a class="dropdown-item" href="index.php?url=order"><i class="fas fa-receipt mr-2"></i>Đơn hàng của tôi</a>
                        <a class="dropdown-item" href="index.php?url=wishlist"><i class="fas fa-heart mr-2"></i>Sản phẩm yêu thích</a>
                        <a class="dropdown-item" href="index.php?url=account/profile"><i class="fas fa-user-cog mr-2"></i>Quản lý tài khoản</a>
                        <?php if (isAdmin()): ?>
                        <div class="dropdown-divider" style="border-color:var(--line);margin:0"></div>
                        <a class="dropdown-item" href="index.php?url=account/manage"><i class="fas fa-users-cog mr-2"></i>Quản lý người dùng</a>
                        <a class="dropdown-item" href="index.php?url=account/jwtdemo"><i class="fas fa-key mr-2"></i>JWT Demo (Bài 6)</a>
                        <?php endif; ?>
                        <div class="dropdown-divider" style="border-color:var(--line);margin:0"></div>
                        <a class="dropdown-item dropdown-logout" href="index.php?url=account/logout"><i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất</a>
                    </div>
                </li>
                <?php else: ?>
                <li class="nav-item"><a class="nav-link <?= $currentSection === 'account' ? 'active' : '' ?>" href="index.php?url=account/login"><i class="fas fa-sign-in-alt mr-1"></i>Đăng nhập</a></li>
                <?php endif; ?>
                <li class="nav-item ml-lg-2 mt-2 mt-lg-0"><button class="theme-toggle" type="button" id="themeToggle" title="Đổi giao diện"><i class="fas fa-moon"></i></button></li>
            </ul>
        </div>
    </div>
</nav>

<script>
(function () {
    function ready(fn) {
        if (document.readyState !== 'loading') fn();
        else document.addEventListener('DOMContentLoaded', fn);
    }
    ready(function () {
        var navbar = document.getElementById('siteNavbar');
        var collapse = document.getElementById('navbarNav');
        var backdrop = document.getElementById('mobileNavBackdrop');
        var toggler = document.querySelector('.navbar-toggler');

        function onScroll() {
            if (!navbar) return;
            navbar.classList.toggle('is-scrolled', window.scrollY > 80);
        }
        onScroll();
        window.addEventListener('scroll', onScroll, { passive: true });

        if (collapse && backdrop) {
            $('#navbarNav').on('show.bs.collapse', function () { backdrop.classList.add('is-open'); document.body.style.overflow = 'hidden'; });
            $('#navbarNav').on('hide.bs.collapse', function () { backdrop.classList.remove('is-open'); document.body.style.overflow = ''; });
            backdrop.addEventListener('click', function () { $('#navbarNav').collapse('hide'); });
            collapse.querySelectorAll('a.nav-link, .mega-menu-item').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (window.innerWidth < 992) $('#navbarNav').collapse('hide');
                });
            });
        }
        if (toggler) toggler.addEventListener('click', function () { setTimeout(onScroll, 40); });
    });
})();
</script>

<div class="main-content">
    <div class="container">
        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle mr-2"></i><?= e($_SESSION['success']); unset($_SESSION['success']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle mr-2"></i><?= e($_SESSION['error']); unset($_SESSION['error']); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>
