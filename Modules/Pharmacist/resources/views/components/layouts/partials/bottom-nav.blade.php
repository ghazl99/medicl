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
    <!-- Bottom Navbar يظهر فقط على الموبايل -->
    <nav class="navbar navbar-light bg-light navbar-expand fixed-bottom d-xl-none border-top" style="border-radius:25px;">
        <ul class="navbar-nav nav-justified w-100">
            <li class="nav-item">
                <a href="{{ route('pharmacist.home') }}"
                    class="nav-link text-center {{ request()->routeIs('pharmacist.home') ? 'active' : '' }}">
                    <i class="bi bi-house-door fs-5"></i><br>الرئيسية
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('main.categories') }}"
                    class="nav-link text-center {{ (request()->routeIs('main.categories') ||
                                                    request()->routeIs('sub.categories') ||
                                                    request()->routeIs('sub.categories.medicines') ||
                                                    request()->routeIs('new.medicines'))? 'active' : '' }}">
                    <i class="bi bi-compass fs-5"></i><br>استكشاف
                </a>
            </li>
            @role('صيدلي')
                <li class="nav-item position-relative">
                    <a href="{{ route('cart.index') }}"
                        class="nav-link text-center {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                        <i class="bi bi-cart3 fs-5"></i><br>السلة
                        @if ($cartCount > 0)
                            <span class="badge bg-danger position-absolute top-0 start-50 translate-middle-x">
                                {{ $cartCount }}
                            </span>
                        @endif
                    </a>
                </li>
            @endrole
            @hasanyrole('مورد|المشرف')
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}"
                        class="nav-link text-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2 fs-5"></i><br>لوحة التحكم
                    </a>
                </li>
            @endhasanyrole
            <li class="nav-item">
                <a href="{{ route('my.orders') }}" class="nav-link text-center {{ (request()->routeIs('my.orders')|| request()->routeIs('details.order')) ? 'active' : '' }}">
                    <i class="bi bi-bag-check fs-5"></i><br>طلباتي
                </a>
            </li>
        </ul>
    </nav>
