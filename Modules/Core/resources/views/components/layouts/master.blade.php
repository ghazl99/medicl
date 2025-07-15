<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --main-color: #2563eb;
            --accent-color: #38bdf8;
            --success-color: #22c55e;
            --sidebar-gradient: linear-gradient(135deg, #2563eb 0%, #38bdf8 60%, #22c55e 100%);
            --sidebar-active: #fff;
            --sidebar-active-text: #2563eb;
            --sidebar-hover: rgba(34, 197, 94, 0.10);
            --bg-light: #f6f8fa;
            --card-bg: #fff;
            --card-shadow: 0 4px 24px rgba(37, 99, 235, 0.08);
            --radius: 18px;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --stat-icon-bg: #e0fbe8;
            --stat-icon-color: #22c55e;
            --stat-value: #22c55e;
            --sidebar-logo-border: #38bdf8;
        }

        body[data-theme="dark"] {
            --main-color: #3b5b7a;
            --accent-color: #60a5fa;
            --success-color: #34d399;
            --sidebar-gradient: linear-gradient(135deg, #232b3b 0%, #334155 60%, #22c55e 100%);
            --sidebar-active: #2a3242;
            --sidebar-active-text: #60a5fa;
            --sidebar-hover: rgba(52, 211, 153, 0.10);
            --bg-light: #1a2233;
            --card-bg: #232b3b;
            --card-shadow: 0 4px 24px rgba(52, 211, 153, 0.10);
            --radius: 18px;
            --text-main: #e2e8f0;
            --text-muted: #a0aec0;
            --stat-icon-bg: #1e293b;
            --stat-icon-color: #34d399;
            --stat-value: #34d399;
            --sidebar-logo-border: #34d399;
        }

        body {
            background: var(--bg-light);
            min-height: 100vh;
            font-family: 'Cairo', sans-serif;
            color: var(--text-main);
            transition: background 0.3s, color 0.3s;
        }

        .sidebar {
            min-height: 100vh;
            background: var(--sidebar-gradient);
            color: #fff;
            padding: 2rem 1rem 1rem 1rem;
            border-radius: 0 32px 32px 0;
            box-shadow: 2px 0 16px rgba(37, 99, 235, 0.10);
            display: flex;
            flex-direction: column;
            align-items: stretch;
            position: relative;
            z-index: 2;
            transition: background 0.3s;
        }

        .sidebar .sidebar-title {
            font-size: 1.6rem;
            font-weight: bold;
            margin-bottom: 2.5rem;
            text-align: center;
            letter-spacing: 1px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .sidebar .logo {
            width: 60px;
            height: 60px;
            margin-bottom: 0.5rem;
            background: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 12px rgba(56, 189, 248, 0.18);
            border: 3px solid var(--sidebar-logo-border);
            transition: border 0.3s;
        }

        .sidebar-nav {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .sidebar .nav-link {
            display: flex;
            align-items: center;
            gap: 0.9rem;
            color: #fff;
            font-weight: 600;
            font-size: 1.08rem;
            padding: 0.85rem 1.1rem;
            border-radius: 12px;
            transition: background 0.18s, color 0.18s;
            position: relative;
            text-decoration: none;
            letter-spacing: 0.5px;
        }

        .sidebar .nav-link .bi {
            font-size: 1.35rem;
            opacity: 0.92;
            transition: color 0.18s;
        }

        .sidebar .nav-link.active {
            background: var(--sidebar-active);
            color: var(--sidebar-active-text);
            box-shadow: 0 2px 12px #38bdf81a;
        }

        .sidebar .nav-link.active .bi {
            color: var(--success-color);
        }

        .sidebar .nav-link:hover:not(.active) {
            background: var(--sidebar-hover);
            color: #fff;
        }

        .sidebar .nav-link:hover .bi {
            color: var(--success-color);
        }

        .sidebar .mt-auto {
            margin-top: auto;
        }

        .sidebar .logout-link {
            color: #fff;
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.7rem 1rem;
            border-radius: 10px;
            transition: background 0.18s, color 0.18s;
            text-decoration: none;
        }

        .sidebar .logout-link:hover {
            background: #fff2;
            color: #fff;
        }

        .sidebar .theme-toggle {
            position: absolute;
            top: 1.2rem;
            left: 1.2rem;
            background: rgba(255, 255, 255, 0.13);
            border: none;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            font-size: 1.3rem;
            cursor: pointer;
            transition: background 0.2s, color 0.2s;
            z-index: 10;
        }

        .sidebar .theme-toggle:hover {
            background: rgba(56, 189, 248, 0.18);
            color: var(--success-color);
        }

        .main-content {
            padding: 2rem;
            transition: background 0.3s, color 0.3s;
        }

        .dashboard-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2.5rem;
            flex-wrap: wrap;
        }

        .dashboard-header .welcome {
            font-size: 2rem;
            font-weight: bold;
            color: var(--main-color);
            transition: color 0.3s;
        }

        .dashboard-header .subtitle {
            color: var(--text-muted);
            font-size: 1.1rem;
            margin-top: 0.5rem;
            transition: color 0.3s;
        }

        .stat-card {
            background: var(--card-bg);
            border-radius: var(--radius);
            box-shadow: var(--card-shadow);
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1.2rem;
            transition: transform 0.15s, box-shadow 0.15s, background 0.3s, color 0.3s;
            cursor: pointer;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px) scale(1.03);
            box-shadow: 0 8px 32px rgba(34, 197, 94, 0.13);
        }

        .stat-icon {
            font-size: 2.5rem;
            color: var(--stat-icon-color);
            background: var(--stat-icon-bg);
            border-radius: 50%;
            width: 56px;
            height: 56px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s, color 0.3s;
        }

        .stat-info {
            flex: 1;
        }

        .stat-title {
            font-size: 1.1rem;
            color: var(--text-muted);
            margin-bottom: 0.2rem;
            transition: color 0.3s;
        }

        .stat-value {
            font-size: 2rem;
            font-weight: bold;
            color: var(--stat-value);
            transition: color 0.3s;
        }

        .stat-link {
            position: absolute;
            left: 1.5rem;
            bottom: 1.2rem;
            color: var(--accent-color);
            font-size: 0.95rem;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.15s;
        }

        .stat-link:hover {
            color: var(--main-color);
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .sidebar {
                border-radius: 0 0 24px 24px;
                min-height: auto;
                padding: 1rem 0.5rem;
            }

            .main-content {
                padding: 1rem;
            }

            .dashboard-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            @include('core::components.layouts.nav')
            <main class="col main-content">
                <div class="container">
                    @if (session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
                    @if (session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif
                    @if (session('warning')) <div class="alert alert-warning">{{ session('warning') }}</div> @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const themeToggle = document.getElementById('themeToggle');
        const body = document.body;

        function setTheme(theme) {
            body.setAttribute('data-theme', theme);
            localStorage.setItem('pharmacy-theme', theme);
            themeToggle.innerHTML = theme === 'dark' ? '<i class="bi bi-brightness-high"></i>' : '<i class="bi bi-moon-stars"></i>';
            themeToggle.title = theme === 'dark' ? 'تبديل إلى الوضع النهاري' : 'تبديل إلى الوضع الليلي';
        }
        themeToggle.addEventListener('click', () => {
            const current = body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
            setTheme(current);
        });
        (function() {
            const saved = localStorage.getItem('pharmacy-theme');
            setTheme(saved === 'dark' ? 'dark' : 'light');
        })();

        $(document).ready(function() {
            $(".alert").slideDown(300).delay(4000).slideUp(300);
        });
    </script>

    @yield('scripts')
</body>
</html>
