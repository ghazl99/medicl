<header id="header" class="header d-flex flex-column align-items-center fixed-top">
    @php
        $newMedicinesCount = \Modules\Medicine\Models\Medicine::where('is_new', true)
            ->whereDate('new_start_date', '<=', now())
            ->whereDate('new_end_date', '>=', now())
            ->count();

        use Modules\Cart\Models\Cart;

        $cart = Cart::withCount('items')
            ->where('user_id', auth()->id())
            ->first();
        $cartCount = $cart ? $cart->items_count : 0;

        use Modules\Core\Models\Notification;

        $notifications = Notification::where('user_id', auth()->id())
            ->latest()
            ->take(10)
            ->get();
        $unreadCount = $notifications->where('is_read', false)->count();
    @endphp

    <div class="container-fluid container-xl d-flex align-items-center justify-content-between">

        <!-- Logo -->
        <a href="{{ route('pharmacist.home') }}" class="logo d-flex align-items-center">
            <h1 class="sitename">صيدليتي</h1>
        </a>

        <!-- روابط الـ Top nav تظهر فقط باللابتوب -->
        <ul class="navbar-nav d-none d-xl-flex flex-row gap-3 align-items-center m-0">

            <li class="nav-item">
                <a href="{{ route('pharmacist.home') }}"
                    class="nav-link {{ request()->routeIs('pharmacist.home') ? 'active' : '' }}">
                    الرئيسية
                </a>
            </li>
            @hasanyrole('مورد|المشرف')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        dashboard
                    </a>
                </li>
            @endhasanyrole
            <li class="nav-item">
                <a href="{{ route('main.categories') }}"
                    class="nav-link {{ request()->routeIs('main.categories') ||
                    request()->routeIs('sub.categories') ||
                    request()->routeIs('sub.categories.medicines') ||
                    request()->routeIs('new.medicines')
                        ? 'active'
                        : '' }}">
                    استكشاف
                </a>
            </li>
            @role('صيدلي')
                <li class="nav-item position-relative">
                    <a href="{{ route('cart.index') }}"
                        class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                        <i class="bi bi-cart3 fs-5"></i>
                        @if ($cartCount > 0)
                            <span class="badge bg-danger position-absolute top-0 start-100 translate-middle">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>

            @endrole

            <li class="nav-item">
                <a href="{{ route('my.orders') }}"
                    class="nav-link {{ request()->routeIs('my.orders') ? 'active' : '' }}">
                    طلباتي
                </a>
            </li>
        </ul>

        <!-- الإشعارات -->
        <a href="{{ route('notifications') }}" class="position-relative">
            <i class="bi bi-bell fs-4" style="color: white"></i>
            @if ($unreadCount > 0)
                <span
                    class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-circle">
                    {{ $unreadCount }}
                </span>
            @endif
        </a>


    </div>

    <!-- Header Subtitle -->
    <div class="header-subtitle text-center mt-2">
        <p class="mb-1">تطبيق إدارة الصيدليات والبحث عن الأدوية</p>
        <span class="medical-tag">🏥 طبي معتمد</span>
    </div>

</header>
