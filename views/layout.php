<!DOCTYPE html>
<html lang="tr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kütüphane Otomasyonu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* ── Theme Variables ── */
        :root {
            --transition-speed: 0.45s;
            --transition-ease: cubic-bezier(0.4, 0, 0.2, 1);
        }

        [data-theme="dark"] {
            --bg-primary: #0f1117;
            --bg-secondary: #1a1d27;
            --bg-card: #1e2130;
            --bg-navbar: #141620;
            --text-primary: #e8eaed;
            --text-secondary: #9aa0b0;
            --border-color: rgba(255, 255, 255, 0.06);
            --shadow-color: rgba(0, 0, 0, 0.4);
            --accent: #6c9fff;
            --accent-glow: rgba(108, 159, 255, 0.15);
            --input-bg: #252837;
            --table-stripe: rgba(255, 255, 255, 0.02);
            --toggle-bg: #252837;
            --toggle-knob: #6c9fff;
        }

        [data-theme="light"] {
            --bg-primary: #f6f0e1;
            --bg-secondary: #fff8e6;
            --bg-card: #fffdf5;
            --bg-navbar: #fbf2db;
            --text-primary: #2b2a25;
            --text-secondary: #6b6558;
            --border-color: rgba(43, 42, 37, 0.12);
            --shadow-color: rgba(43, 42, 37, 0.10);
            --accent: #3b6ea5;
            --accent-glow: rgba(59, 110, 165, 0.12);
            --input-bg: #fff4d6;
            --table-stripe: rgba(43, 42, 37, 0.03);
            --toggle-bg: #eadfbe;
            --toggle-knob: #c6922a;
        }

        /* ── Global Transitions ── */
        body,
        .navbar,
        .container,
        .card,
        .table,
        .form-control,
        .form-select,
        .btn,
        .modal-content,
        .alert,
        input, select, textarea {
            transition:
                background-color var(--transition-speed) var(--transition-ease),
                color var(--transition-speed) var(--transition-ease),
                border-color var(--transition-speed) var(--transition-ease),
                box-shadow var(--transition-speed) var(--transition-ease);
        }

        /* ── Body ── */
        body {
            background-color: var(--bg-primary) !important;
            color: var(--text-primary) !important;
            min-height: 100vh;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }

        /* ── Sidebar (Left Navbar) ── */
        .sidebar {
            /* Eğer bu sayfa login sayfasıysa gizle */
            <?php if (isset($_GET['action']) && $_GET['action'] === 'login') echo 'display: none !important;'; ?>
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            height: 100vh;
            background: var(--bg-navbar) !important;
            border-right: 1px solid var(--border-color);
            box-shadow: 2px 0 12px var(--shadow-color);
            backdrop-filter: blur(12px);
            z-index: 1000;
            padding-top: 3rem;
            display: flex;
            flex-direction: column;
            transition: width var(--transition-speed) var(--transition-ease);
        }

        .main-content {
            /* Eğer bu sayfa login sayfasıysa margin 0 olmalı */
            <?php if (isset($_GET['action']) && $_GET['action'] === 'login') echo 'margin-left: 0 !important;'; else echo 'margin-left: 250px;'; ?>
            min-height: 100vh;
            padding: 2rem;
            transition: margin-left var(--transition-speed) var(--transition-ease);
        }

        /* Sidebar Toggle Button */
        .sidebar-toggle-btn {
            position: absolute;
            right: 1rem;
            top: 0.75rem;
            width: 28px;
            height: 28px;
            background: transparent;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            cursor: pointer;
            z-index: 1001;
            transition: transform var(--transition-speed) var(--transition-ease), color 0.2s ease, right var(--transition-speed) var(--transition-ease);
        }

        .sidebar-toggle-btn:hover {
            color: var(--accent);
        }

        /* Nav Texts and Icons */
        .nav-text {
            transition: opacity var(--transition-speed) var(--transition-ease), max-width var(--transition-speed) var(--transition-ease), margin var(--transition-speed) var(--transition-ease), padding var(--transition-speed) var(--transition-ease);
            white-space: nowrap;
            overflow: hidden;
            display: inline-block;
            vertical-align: middle;
            max-width: 200px;
            opacity: 1;
        }

        .brand-icon, .nav-link svg {
            transition: margin var(--transition-speed) var(--transition-ease), font-size var(--transition-speed) var(--transition-ease), transform var(--transition-speed) var(--transition-ease);
        }

        .sidebar-brand {
            color: var(--text-primary) !important;
            font-weight: 600;
            letter-spacing: -0.02em;
            font-size: 1rem;
            padding: 0 1.5rem 2rem 1.5rem;
            display: flex;
            align-items: center;
            text-decoration: none;
            overflow: hidden;
            white-space: nowrap;
        }

        .sidebar .nav {
            flex-direction: column;
            padding-left: 0;
            margin-bottom: 0;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            transition: all 0.25s ease !important;
            padding: 0.75rem 1.5rem;
            border-radius: 0 2rem 2rem 0;
            margin-right: 1rem;
            font-weight: 500;
            display: flex;
            align-items: center;
            overflow: hidden;
            white-space: nowrap;
        }

        .nav-link:hover {
            color: var(--accent) !important;
            background-color: var(--accent-glow) !important;
            transform: translateX(4px);
        }

        .nav-link.text-info {
            color: var(--accent) !important;
        }

        .nav-link.text-warning {
            color: #f5a623 !important;
        }

        .main-content .nav-pills .nav-link {
            background-color: transparent;
            color: var(--text-secondary) !important;
            border: 1px solid transparent;
            box-shadow: none !important;
            outline: none;
        }

        .main-content .nav-pills .nav-link:hover {
            background-color: var(--accent-glow) !important;
            color: var(--accent) !important;
            transform: none !important;
        }

        .main-content .nav-pills .nav-link.active,
        .main-content .nav-pills .show > .nav-link {
            background-color: var(--accent-glow) !important;
            color: var(--accent) !important;
            border-color: var(--border-color) !important;
            box-shadow: none !important;
        }

        .main-content .nav-pills .nav-link:focus,
        .main-content .nav-pills .nav-link:focus-visible {
            box-shadow: none !important;
            outline: none !important;
        }

        .sidebar-bottom {
            margin-top: auto;
            padding: 0.75rem;
            border-top: 1px solid var(--border-color);
        }

        .user-card {
            display: flex;
            align-items: center;
            padding: 0.5rem;
            background: var(--bg-secondary);
            border-radius: 10px;
            margin-bottom: 0.75rem;
            border: 1px solid var(--border-color);
            transition: all var(--transition-speed);
        }

        .user-avatar {
            width: 32px;
            height: 32px;
            background: var(--accent);
            color: #fff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            box-shadow: 0 2px 8px var(--accent-glow);
        }

        .user-info-text {
            margin-left: 10px;
            overflow: hidden;
            line-height: 1.2;
        }

        .user-name-small {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-primary);
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .user-role-small {
            font-size: 0.7rem;
            color: var(--text-secondary);
        }

        .sidebar-footer-actions {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0.25rem;
        }

        .logout-btn-minimal {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 8px;
            color: #f5a623;
            transition: all 0.2s;
            text-decoration: none;
        }

        .logout-btn-minimal:hover {
            background: rgba(245, 166, 35, 0.1);
            transform: translateY(-2px);
        }

        @media (min-width: 769px) {
            /* Collapsed State (Desktop Only) */
            html.sidebar-collapsed .sidebar {
                width: 80px;
            }

            html.sidebar-collapsed .main-content {
                margin-left: 80px;
            }
            
            html.sidebar-collapsed .sidebar-toggle-btn {
                right: 26px;
                transform: rotate(180deg);
            }

            html.sidebar-collapsed .nav-text {
                opacity: 0;
                max-width: 0;
                margin-left: 0 !important;
                margin-right: 0 !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }

            html.sidebar-collapsed .sidebar-brand {
                padding: 0 0 2rem 0;
                justify-content: center;
            }
            
            html.sidebar-collapsed .sidebar-brand .brand-icon {
                font-size: 1.8rem;
                margin: 0 !important;
            }

            html.sidebar-collapsed .nav-link {
                padding: 0.75rem;
                margin: 0 0.75rem;
                border-radius: 0.75rem;
                justify-content: center;
            }

            html.sidebar-collapsed .nav-link:hover {
                transform: translateY(-2px);
                transform: translateX(0); /* Override translate X */
            }

            html.sidebar-collapsed .nav-link svg {
                margin-right: 0 !important;
            }

            html.sidebar-collapsed .sidebar-bottom {
                padding: 0.75rem 0.5rem;
            }

            html.sidebar-collapsed .user-card {
                padding: 0.4rem;
                justify-content: center;
                margin-bottom: 0.5rem;
            }

            html.sidebar-collapsed .user-info-text {
                display: none;
            }

            html.sidebar-collapsed .sidebar-footer-actions {
                flex-direction: column;
                gap: 12px;
            }

            html.sidebar-collapsed .theme-toggle {
                margin: 0;
                transform: scale(0.7);
                transform-origin: center;
            }

            html.sidebar-collapsed .logout-btn-minimal {
                width: 36px;
                height: 36px;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                height: auto;
                position: relative;
                border-right: none;
                border-bottom: 1px solid var(--border-color);
                padding-bottom: 1rem;
            }
            .sidebar .nav {
                flex-direction: row;
                flex-wrap: wrap;
                justify-content: center;
            }
            .nav-link {
                border-radius: 0.5rem;
                margin: 0.25rem;
            }
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            .sidebar-toggle-btn {
                display: none !important;
            }
        }

        /* ── Cards & Tables ── */
        .card {
            background: var(--bg-card) !important;
            border: 1px solid var(--border-color) !important;
            box-shadow: 0 2px 16px var(--shadow-color) !important;
        }

        .table {
            color: var(--text-primary) !important;
            background-color: transparent !important;
            /* Override Bootstrap's internal table CSS variables */
            --bs-table-color: var(--text-primary);
            --bs-table-bg: transparent;
            --bs-table-border-color: var(--border-color);
            --bs-table-striped-color: var(--text-primary);
            --bs-table-striped-bg: var(--table-stripe);
            --bs-table-hover-color: var(--text-primary);
            --bs-table-hover-bg: var(--accent-glow);
        }

        .table thead th {
            border-bottom-color: var(--border-color) !important;
            color: var(--text-secondary) !important;
        }

        /* Override Bootstrap's table-dark so it uses theme variables */
        .table-dark,
        .table-dark th,
        .table-dark td,
        .table thead.table-dark th {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            --bs-table-color: var(--text-primary);
            --bs-table-bg: var(--bg-secondary);
        }

        .table td, .table th {
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .table tbody tr {
            background-color: var(--bg-card) !important;
            transition: background-color 0.2s ease;
        }

        .table-striped > tbody > tr:nth-of-type(odd) > * {
            background-color: var(--table-stripe) !important;
            color: var(--text-primary) !important;
        }

        /* Hover rows */
        .table-hover > tbody > tr:hover > * {
            background-color: var(--accent-glow) !important;
            color: var(--text-primary) !important;
        }

        .table-responsive {
            background-color: transparent !important;
        }

        /* ── Typography System ── */

        /* Heading scale — consistent weight + tracking across all pages */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
            font-weight: 600;
            letter-spacing: -0.02em;
            line-height: 1.25;
            transition: color var(--transition-speed) var(--transition-ease);
        }

        h1 { font-size: 1.75rem; }
        h2 { font-size: 1.375rem; }
        h3 { font-size: 1.125rem; }
        h4 { font-size: 1rem;    }
        h5 { font-size: 0.9375rem; }
        h6 { font-size: 0.875rem; }

        /* Page-level heading row (title + action button) */
        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 1.5rem;
        }

        .page-header h2 {
            margin-bottom: 0;
        }

        /* Subtitle under page headings */
        .page-subtitle {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 2px;
            margin-bottom: 0;
        }

        /* Body text */
        body  { font-size: 0.9375rem; line-height: 1.6; }
        small, .small { font-size: 0.8125rem; }

        /* Table headers */
        .table thead th {
            font-size: 0.75rem;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
        }

        /* Card titles */
        .card-title {
            font-size: 0.9375rem;
            font-weight: 600;
            letter-spacing: -0.01em;
        }

        /* Form labels */
        .form-label {
            font-size: 0.8125rem;
            font-weight: 500;
            letter-spacing: 0.01em;
        }

        /* Sidebar nav links */
        .nav-link {
            font-size: 0.875rem;
            font-weight: 500;
        }


        /* ── Buttons theme transition ── */
        .btn {
            transition:
                background-color 0.25s ease,
                color 0.25s ease,
                border-color 0.25s ease,
                box-shadow 0.25s ease,
                transform 0.15s ease !important;
        }

        .btn:hover {
            transform: translateY(-1px);
        }

        .btn-secondary {
            background-color: var(--bg-secondary) !important;
            border-color: var(--border-color) !important;
            color: var(--text-primary) !important;
        }

        .btn-secondary:hover {
            background-color: var(--text-secondary) !important;
            color: var(--bg-primary) !important;
        }

        /* ── Badges ── */
        .badge {
            transition: background-color var(--transition-speed) var(--transition-ease);
        }

        /* ── Forms ── */
        .form-control, .form-select {
            background-color: var(--input-bg) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
        }

        .form-control:focus, .form-select:focus {
            border-color: var(--accent) !important;
            box-shadow: 0 0 0 3px var(--accent-glow) !important;
        }

        .form-label {
            color: var(--text-secondary) !important;
        }

        .form-control::file-selector-button {
            background-color: var(--bg-secondary) !important;
            color: var(--text-primary) !important;
            border-color: var(--border-color) !important;
            transition: background-color var(--transition-speed) var(--transition-ease), color var(--transition-speed) var(--transition-ease);
        }

        .form-control:hover::file-selector-button {
            background-color: var(--accent) !important;
            color: #fff !important;
        }

        .form-text, .text-muted {
            color: var(--text-secondary) !important;
        }

        /* ── Alerts ── */
        .alert {
            border: 1px solid var(--border-color) !important;
        }

        /* ── Theme Toggle Button ── */
        .theme-toggle {
            position: relative;
            width: 56px;
            height: 28px;
            border-radius: 50px;
            background: var(--toggle-bg);
            border: 2px solid var(--border-color);
            cursor: pointer;
            padding: 0;
            display: flex;
            align-items: center;
            outline: none;
            transition:
                background var(--transition-speed) var(--transition-ease),
                border-color var(--transition-speed) var(--transition-ease),
                box-shadow var(--transition-speed) var(--transition-ease);
            margin-left: 12px;
        }

        .theme-toggle:hover {
            box-shadow: 0 0 18px var(--accent-glow);
            border-color: var(--accent);
        }

        .theme-toggle__knob {
            position: absolute;
            top: 2px;
            left: 2px;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            background: var(--toggle-knob);
            display: flex;
            align-items: center;
            justify-content: center;
            transition:
                transform var(--transition-speed) var(--transition-ease),
                background var(--transition-speed) var(--transition-ease),
                box-shadow var(--transition-speed) var(--transition-ease);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.25);
        }

        [data-theme="light"] .theme-toggle__knob {
            transform: translateX(28px);
            box-shadow: 0 2px 10px rgba(245, 166, 35, 0.35);
        }

        .theme-toggle__icon {
            width: 12px;
            height: 12px;
            transition:
                opacity var(--transition-speed) var(--transition-ease),
                transform var(--transition-speed) var(--transition-ease);
        }

        /* Moon icon */
        .theme-toggle__moon {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        [data-theme="light"] .theme-toggle__moon {
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }

        /* Sun icon */
        .theme-toggle__sun {
            position: absolute;
            opacity: 0;
            transform: rotate(90deg) scale(0.5);
        }

        [data-theme="light"] .theme-toggle__sun {
            opacity: 1;
            transform: rotate(0deg) scale(1);
        }

        /* ── Toggle Decorative Stars (dark mode) ── */
        .theme-toggle__stars {
            position: absolute;
            right: 8px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 3px;
            transition: opacity var(--transition-speed) var(--transition-ease);
        }

        .theme-toggle__star {
            width: 3px;
            height: 3px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
        }

        .theme-toggle__star:nth-child(2) {
            width: 2px;
            height: 2px;
            opacity: 0.7;
            margin-top: 3px;
        }

        .theme-toggle__star:nth-child(3) {
            width: 2.5px;
            height: 2.5px;
            opacity: 0.4;
            margin-top: -2px;
        }

        [data-theme="light"] .theme-toggle__stars {
            opacity: 0;
        }

        /* ── Toggle Decorative Clouds (light mode) ── */
        .theme-toggle__clouds {
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            display: flex;
            gap: 2px;
            opacity: 0;
            transition: opacity var(--transition-speed) var(--transition-ease);
        }

        .theme-toggle__cloud {
            width: 5px;
            height: 3px;
            background: rgba(255, 255, 255, 0.8);
            border-radius: 50%;
        }

        .theme-toggle__cloud:nth-child(2) {
            width: 7px;
            height: 4px;
            margin-top: -1px;
        }

        [data-theme="light"] .theme-toggle__clouds {
            opacity: 1;
        }

        /* ── Smooth page entrance ── */
        .container.mt-4 {
            animation: fadeUp 0.4s var(--transition-ease);
        }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Scrollbar theming ── */
        ::-webkit-scrollbar {
            width: 8px;
        }

        ::-webkit-scrollbar-track {
            background: var(--bg-primary);
        }

        ::-webkit-scrollbar-thumb {
            background: var(--text-secondary);
            border-radius: 4px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: var(--accent);
        }
    </style>
    <!-- Prevent flash of wrong theme and sidebar state -->
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
            
            var s = localStorage.getItem('sidebar_collapsed');
            if (s === 'true') {
                document.documentElement.classList.add('sidebar-collapsed');
            }
        })();
    </script>
</head>
<body>
    <nav class="sidebar">
        <button id="sidebarToggleBtn" class="sidebar-toggle-btn" aria-label="Menüyü Daralt/Genişlet" title="Menüyü Daralt/Genişlet">
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <polyline points="15 18 9 12 15 6"></polyline>
            </svg>
        </button>
        <a class="sidebar-brand" href="index.php">
            <span class="brand-icon me-2">📚</span>
            <span class="nav-text">Kütüphane</span>
        </a>
        <?php if (!empty($_SESSION['user_id'])): ?>
        <ul class="nav">
            <li class="nav-item">
                <a class="nav-link" href="?action=books" title="Kitaplar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M2 3h6a4 4 0 0 1 4 4v14a3 3 0 0 0-3-3H2z"></path><path d="M22 3h-6a4 4 0 0 0-4 4v14a3 3 0 0 1 3-3h7z"></path></svg>
                    <span class="nav-text">Kitaplar</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?action=my_library" title="Kütüphanem">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                    <span class="nav-text">Kütüphanem</span>
                </a>
            </li>
            <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
            <li class="nav-item">
                <a class="nav-link" href="?action=users" title="Üyeler">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                    <span class="nav-text">Üyeler</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?action=loans" title="Ödünç İşlemleri">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><path d="M17 3v4"></path><path d="M7 3v4"></path><path d="M17 11v4"></path><path d="M7 11v4"></path><path d="M17 19v4"></path><path d="M7 19v4"></path><rect x="3" y="3" width="18" height="18" rx="2" ry="2"></rect></svg>
                    <span class="nav-text">Ödünç İşlemleri</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="?action=reservations" title="Rezervasyonlar">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="nav-text">Rezervasyonlar</span>
                </a>
            </li>
            <?php endif; ?>
            <?php if ($_SESSION['user_role'] === User::ROLE_MEMBER): ?>
            <li class="nav-item">
                <a class="nav-link" href="?action=my_reservations" title="Rezervasyonlarım">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="me-2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    <span class="nav-text">Rezervasyonlarım</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        
        <div class="sidebar-bottom">
            <div class="user-card" title="<?= htmlspecialchars($_SESSION['user_name']) ?>">
                <div class="user-avatar">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
                <div class="user-info-text">
                    <span class="user-name-small"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    <span class="user-role-small"><?= $_SESSION['user_role'] === User::ROLE_ADMIN ? 'Yönetici' : ($_SESSION['user_role'] === User::ROLE_STAFF ? 'Personel' : 'Üye') ?></span>
                </div>
            </div>
            <div class="sidebar-footer-actions">
                <button class="theme-toggle" id="themeToggle" aria-label="Tema değiştir" title="Koyu / Açık Mod" style="transform: scale(0.8); transform-origin: left; margin: 0;">
                    <span class="theme-toggle__stars">
                        <span class="theme-toggle__star"></span>
                        <span class="theme-toggle__star"></span>
                        <span class="theme-toggle__star"></span>
                    </span>
                    <span class="theme-toggle__clouds">
                        <span class="theme-toggle__cloud"></span>
                        <span class="theme-toggle__cloud"></span>
                    </span>
                    <span class="theme-toggle__knob">
                        <svg class="theme-toggle__icon theme-toggle__moon" viewBox="0 0 24 24" fill="currentColor" color="white"><path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/></svg>
                        <svg class="theme-toggle__icon theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    </span>
                </button>
                <a class="logout-btn-minimal" href="?action=logout" title="Çıkış">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </a>
            </div>
        </div>
        <?php endif; ?>
        
        <?php if (empty($_SESSION['user_id'])): ?>
        <div class="sidebar-bottom">
            <button class="theme-toggle ms-3" id="themeToggleGuest" aria-label="Tema değiştir" title="Koyu / Açık Mod">
                <span class="theme-toggle__stars">
                    <span class="theme-toggle__star"></span>
                    <span class="theme-toggle__star"></span>
                    <span class="theme-toggle__star"></span>
                </span>
                <span class="theme-toggle__clouds">
                    <span class="theme-toggle__cloud"></span>
                    <span class="theme-toggle__cloud"></span>
                </span>
                <span class="theme-toggle__knob">
                    <svg class="theme-toggle__icon theme-toggle__moon" viewBox="0 0 24 24" fill="currentColor" color="white">
                        <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                    </svg>
                    <svg class="theme-toggle__icon theme-toggle__sun" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="5"/>
                        <line x1="12" y1="1" x2="12" y2="3"/>
                        <line x1="12" y1="21" x2="12" y2="23"/>
                        <line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/>
                        <line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/>
                        <line x1="1" y1="12" x2="3" y2="12"/>
                        <line x1="21" y1="12" x2="23" y2="12"/>
                        <line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/>
                        <line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/>
                    </svg>
                </span>
            </button>
        </div>
        <?php endif; ?>
    </nav>
    
    <main class="main-content">
        <div class="container">
            <?= $content ?? '' ?>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        (function() {
            function toggleTheme() {
                var html = document.documentElement;
                var current = html.getAttribute('data-theme');
                var next = current === 'dark' ? 'light' : 'dark';
                html.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
            }

            var btn1 = document.getElementById('themeToggle');
            var btn2 = document.getElementById('themeToggleGuest');
            if (btn1) btn1.addEventListener('click', toggleTheme);
            if (btn2) btn2.addEventListener('click', toggleTheme);

            var sidebarBtn = document.getElementById('sidebarToggleBtn');
            if (sidebarBtn) {
                sidebarBtn.addEventListener('click', function() {
                    var html = document.documentElement;
                    html.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('sidebar_collapsed', html.classList.contains('sidebar-collapsed'));
                });
            }
        })();
    </script>
</body>
</html>