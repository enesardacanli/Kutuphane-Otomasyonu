<!DOCTYPE html>
<html lang="tr" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kütüphane Otomasyonu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
            --bg-primary: #f4f6fb;
            --bg-secondary: #ffffff;
            --bg-card: #ffffff;
            --bg-navbar: #ffffff;
            --text-primary: #1a1d27;
            --text-secondary: #5f6577;
            --border-color: rgba(0, 0, 0, 0.08);
            --shadow-color: rgba(0, 0, 0, 0.06);
            --accent: #4a7aff;
            --accent-glow: rgba(74, 122, 255, 0.1);
            --input-bg: #f0f2f7;
            --table-stripe: rgba(0, 0, 0, 0.02);
            --toggle-bg: #d1d9e6;
            --toggle-knob: #f5a623;
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
        }

        /* ── Navbar ── */
        .navbar {
            background: var(--bg-navbar) !important;
            border-bottom: 1px solid var(--border-color);
            box-shadow: 0 1px 12px var(--shadow-color);
            backdrop-filter: blur(12px);
        }

        .navbar-brand {
            color: var(--text-primary) !important;
            font-weight: 700;
            letter-spacing: -0.3px;
        }

        .nav-link {
            color: var(--text-secondary) !important;
            transition: color 0.25s ease, transform 0.2s ease !important;
            position: relative;
        }

        .nav-link:hover {
            color: var(--accent) !important;
            transform: translateY(-1px);
        }

        .nav-link.text-info {
            color: var(--accent) !important;
            font-weight: 500;
        }

        .nav-link.text-warning {
            color: #f5a623 !important;
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

        /* ── Headings ── */
        h1, h2, h3, h4, h5, h6 {
            color: var(--text-primary) !important;
            transition: color var(--transition-speed) var(--transition-ease);
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
    <!-- Prevent flash of wrong theme -->
    <script>
        (function() {
            var t = localStorage.getItem('theme') || 'dark';
            document.documentElement.setAttribute('data-theme', t);
        })();
    </script>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="index.php">📚 Kütüphane</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="?action=books">Kitaplar</a>
                    </li>
                    <?php if (in_array($_SESSION['user_role'], [User::ROLE_ADMIN, User::ROLE_STAFF], true)): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=users">Üyeler</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=loans">Ödünç İşlemleri</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=reservations">Rezervasyonlar</a>
                    </li>
                    <?php endif; ?>
                    <?php if ($_SESSION['user_role'] === User::ROLE_MEMBER): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="?action=my_reservations">Rezervasyonlarım</a>
                    </li>
                    <?php endif; ?>
                    <li class="nav-item">
                        <span class="nav-link text-info"><?= htmlspecialchars($_SESSION['user_name']) ?></span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-warning" href="?action=logout">Çıkış</a>
                    </li>
                    <li class="nav-item">
                        <button class="theme-toggle" id="themeToggle" aria-label="Tema değiştir" title="Koyu / Açık Mod">
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
                                <!-- Moon SVG -->
                                <svg class="theme-toggle__icon theme-toggle__moon" viewBox="0 0 24 24" fill="currentColor" color="white">
                                    <path d="M21 12.79A9 9 0 1 1 11.21 3a7 7 0 0 0 9.79 9.79z"/>
                                </svg>
                                <!-- Sun SVG -->
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
                    </li>
                </ul>
            </div>
            <?php endif; ?>
            <?php if (empty($_SESSION['user_id'])): ?>
            <button class="theme-toggle" id="themeToggleGuest" aria-label="Tema değiştir" title="Koyu / Açık Mod" style="margin-left:auto;">
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
            <?php endif; ?>
        </div>
    </nav>
    <div class="container mt-4">
        <?= $content ?? '' ?>
    </div>
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
        })();
    </script>
</body>
</html>