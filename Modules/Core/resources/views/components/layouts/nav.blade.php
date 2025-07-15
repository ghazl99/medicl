<nav class="col-12 col-md-3 col-lg-2 sidebar">
    <button class="theme-toggle" id="themeToggle" title="تبديل الوضع الليلي"><i class="bi bi-moon-stars"></i></button>
    <div class="sidebar-title mb-4">
        <div class="logo">
            <i class="bi bi-capsule-pill" style="font-size:2.2rem;color:#38bdf8;"></i>
        </div>
        <span>نظام الصيدليات</span>
    </div>
    <div class="sidebar-nav">
        <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-door"></i> الرئيسية
        </a>
        <a href="{{ route('pharmacists.index') }}"
            class="nav-link {{ request()->routeIs('pharmacists.index') ? 'active' : '' }}"><i
                class="bi bi-person-badge"></i> الصيادلة</a>
        <a href="{{ route('suppliers.index') }}"
            class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}"><i class="bi bi-truck"></i>
            الموردون</a>
        <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.index') ? 'active' : '' }}"><i class="bi bi-capsule"></i> الأدوية</a>
        <a href="orders.html" class="nav-link"><i class="bi bi-bag-check"></i> الطلبات</a>
        <a href="sales_report.html" class="nav-link"><i class="bi bi-bar-chart-line"></i> تقارير المبيعات</a>
    </div>
    <div class="mt-auto">
        <hr class="bg-light">

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-link btn btn-link p-0 m-0 border-0 text-decoration-none">
                <i class="bi bi-box-arrow-right"></i> تسجيل الخروج
            </button>
        </form>
    </div>

</nav>
