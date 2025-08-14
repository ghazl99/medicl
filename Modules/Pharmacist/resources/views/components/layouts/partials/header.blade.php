 <header id="header" class="header d-flex align-items-center fixed-top">
     <div class="container-fluid container-xl position-relative d-flex align-items-center justify-content-between">

         <a href="index.html" class="logo d-flex align-items-center">
             <!-- Uncomment the line below if you also wish to use an image logo -->
             <!-- <img src="assets/img/logo.webp" alt=""> -->
             <h1 class="sitename">صيدليات</h1>
         </a>

         <nav id="navmenu" class="navmenu">
              @php
                $newMedicinesCount = \Modules\Medicine\Models\Medicine::where('is_new', true)
                    ->whereDate('new_start_date', '<=', now())
                    ->whereDate('new_end_date', '>=', now())
                    ->count();
            @endphp
             <ul>
                 <li>
                     <a href="{{ route('pharmacist.home') }}"
                         class="{{ request()->routeIs('pharmacist.home') ? 'active' : '' }}">
                         الرئيسية
                     </a>
                 </li>
                 @if ($newMedicinesCount > 0)
                 <li>
                     <a href="{{ route('new.medicines') }}" class="{{ request()->routeIs('new.medicines') ? 'active' : '' }}">
                         المنجات الجديدة
                     </a>
                 </li>
                 @endif
                 <li>
                     <a href="{{ route('main.categories') }}"
                         class="{{ request()->routeIs('main.categories') ? 'active' : '' }}">
                         استكشاف
                     </a>
                 </li>

                 @php
                     use Modules\Cart\Models\Cart;

                     $cart = Cart::withCount('items')
                         ->where('user_id', auth()->id())
                         ->first();
                     $cartCount = $cart ? $cart->items_count : 0;
                 @endphp

                 <li class="nav-item position-relative">
                     <a href="{{ route('cart.index') }}"
                         class="nav-link {{ request()->routeIs('cart.index') ? 'active' : '' }}">
                         <i class="bi bi-cart3" style="font-size: 1.4rem;"></i>
                         @if ($cartCount > 0)
                             <span
                                 class="badge bg-danger position-absolute top-0 start-100 translate-middle p-1 px-2 rounded-circle">
                                 {{ $cartCount }}
                             </span>
                         @endif
                     </a>
                 </li>
             </ul>

             <i class="mobile-nav-toggle d-xl-none bi bi-list"></i>
         </nav>

     </div>
 </header>
