<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ route('dashboard') }}" class="brand-link text-right">
        <i class="bi bi-capsule-pill" style="font-size:2.2rem; color:#38bdf8;"></i>
        <span class="brand-text font-weight-light">نظام الصيدليات</span>
    </a>

    <div class="sidebar">
        <!-- User Panel -->
        <div class="user-panel mt-3 pb-3 mb-3 d-flex justify-content-end text-right">
            <div class="image">
                <img src="{{ asset('dist/img/user2-160x160.jpg') }}" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
                <a href="#" class="d-block">اسم المستخدم</a>
            </div>
        </div>

        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column text-right" data-widget="treeview" role="menu" data-accordion="false">
                
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-house-door"></i>
                        <p>الرئيسية</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('pharmacists.index') }}" class="nav-link {{ request()->routeIs('pharmacists.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-person-badge"></i>
                        <p>الصيادلة</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('suppliers.index') }}" class="nav-link {{ request()->routeIs('suppliers.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-truck"></i>
                        <p>الموردون</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('medicines.index') }}" class="nav-link {{ request()->routeIs('medicines.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-capsule"></i>
                        <p>الأدوية</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="{{ route('orders.index') }}" class="nav-link {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                        <i class="nav-icon bi bi-bag-check"></i>
                        <p>الطلبات</p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="sales_report.html" class="nav-link">
                        <i class="nav-icon bi bi-bar-chart-line"></i>
                        <p>تقارير المبيعات</p>
                    </a>
                </li>

                <!-- Logout -->
                <li class="nav-item mt-4">
                    <form method="POST" action="{{ route('logout') }}" class="nav-link p-0 m-0">
                        @csrf
                        <button type="submit" class="btn btn-link text-white text-right w-100 p-0 m-0 text-decoration-none">
                            <i class="nav-icon bi bi-box-arrow-right"></i>
                            <p>تسجيل الخروج</p>
                        </button>
                    </form>
                </li>

            </ul>
        </nav>
    </div>
</aside>
