<style>
    /* الوضع النهاري - Light Mode */
    .app-sidebar {
        background-image: linear-gradient(135deg, #2563eb 0%, #38bdf8 60%, #22c55e 100%);
        color: #fff;
        border-radius: 0 32px 32px 0;
        padding: 2rem 1rem;
        min-height: 100vh;
        transition: background 0.3s ease;

    }


    /* Sidebar Title */
    .sidebar-title {
        text-align: center;
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 0.5rem;
    }

    .sidebar-title .logo {
        width: 60px;
        height: 60px;
        background: #fff;
        border-radius: 50%;
        margin-top: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 12px rgba(56, 189, 248, 0.18);
        border: 3px solid #38bdf8;
    }

    .app-sidebar .theme-toggle {
        position: absolute;
        bottom: 3.2rem;
        left: 2.2rem;
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

    .app-sidebar .side-menu {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }

    /* القاعدة العامة لجميع عناصر القائمة */
    .app-sidebar .side-menu_item,
    .app-sidebar .side-menu__label,
    .app-sidebar .side-menu__icon {
        color: #fff !important;
        font-size: 18px;
        transition: all 0.3s ease;
    }

    /* عند تمرير الماوس */
    .app-sidebar .side-menu_item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
    }

    /* العنصر النشط (active) */
    .app-sidebar .side-menu_item.active,
    .app-sidebar .side-menu_item:active,
    .app-sidebar .side-menu_item.active:focus {
        background-color: #fff !important;
        color: #2563eb !important;
        border-radius: 12px;
        height: 55px;
    }

    /* تغيير لون الأيقونة والنص داخل العنصر النشط */
    .app-sidebar .side-menu_item.active .side-menu__icon,
    .app-sidebar .side-menu_item.active .side-menu__label {
        color: #2563eb !important;
        font-size: 18px;
    }

    /* عنصر العنوان (category) */
    .app-sidebar .side-item-category {
        color: #fff;
        font-weight: bold;
        padding: 10px 20px 5px;
        font-size: 14px;
        opacity: 0.9;
    }

    .slide.is-expanded a,
    .dark-theme .slide.is-expanded a {
        color: #fff
    }

    /* لتصغير الفراغات وتحسين التباعد */
    .app-sidebar .side-menu_item {
        display: flex;
        align-items: center;
        opacity: 0.92;
        transition: color 0.18s;
        gap: 0.9rem;
        color: #fff;
        font-weight: 600;
        font-size: 1.05rem;
        padding: 0.85rem 1.1rem;
        border-radius: 12px;
        text-decoration: none;
    }

    .slide-item.active,
    .slide-item:hover,
    .slide-item:focus,
    .dark-theme .slide-item.active,
    .slide-item:hover,
    .slide-item:focus {
        ,
        text-decoration: none;
        color: #031b4e !important;
        height: 55px;
        /* لون نص كخلي غامق */
        background-color: #f4f2ee91 !important;
        /* لون خلفية كخلي فاتح */
        border-radius: 8px;
    }

    /* الوضع النهاري - Light Mode */
    .app-sidebar {
        background-image: linear-gradient(135deg, #2563eb 0%, #38bdf8 60%, #22c55e 100%);
        border-radius: 0 32px 32px 0;
        color: #fff;
    }

    /* الوضع الليلي - Dark Mode */
    .dark-theme .app-sidebar {
        background-image: linear-gradient(135deg, #141b2d 0%, rgb(60 73 93) 60%, rgb(34, 197, 94) 100%);
        color: #fff;
        border-radius: 0 32px 32px 0;
    }

    .dark-theme .app-sidebar .side-menu_item.active,
    .dark-theme .app-sidebar .side-menu_item:active,
    .dark-theme .app-sidebar .side-menu_item.active:focus {
        background-color: rgb(42, 50, 66) !important;
        box-shadow: 0 2px 12px #38bdf81a;
        color: #2563eb !important;
        border-radius: 12px;
        height: 55px;
    }
</style>
<!-- main-sidebar -->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>

<aside class=" app-sidebar sidebar-scroll sidebar-left">
    <div class="main-sidebar-header active">
        <a class="theme-toggle" id="themeToggle" title="تبديل الوضع الليلي">
            <i class="bi bi-moon-stars" id="theme-icon"></i>
        </a>
        <div class="sidebar-title mb-4">
            <div class="logo">
                <i class="bi bi-capsule-pill" style="font-size:2.2rem;color:#38bdf8;"></i>
            </div>
            <h2>نظام الصيدليات</h2>
        </div>
    </div>

    <ul class="main-sidemenu side-menu">


        @hasanyrole('المشرف|مورد')
            {{-- dashboard --}}
            <li class="slide mt-1">
                <a class="side-menu_item" href="{{ route('dashboard') }}">
                    <i class="side-menu__icon bi bi-house-door p-2"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>


            {{-- offers --}}
            <li class="slide">
                <a class="side-menu_item" href="{{ route('offers.index') }}">
                    <i class="side-menu__icon bi bi-tags p-2"></i>
                    <span class="side-menu__label">العروض</span>
                </a>
            </li>
        @endhasanyrole
        @role('المشرف')
            {{-- categories --}}
            <li class="slide">
                <a class="side-menu_item" href="{{ route('category.index') }}">
                    <i class="side-menu__icon bi bi-grid p-2" viewBox="0 0 24 24"></i>

                    <span class="side-menu__label">الأصناف</span>
                </a>
            </li>

            {{-- users --}}
            <li class="slide">
                <a class="side-menu_item" href="{{ route('pharmacists.index') }}">
                    <i class="side-menu__icon bi bi-person-badge p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">الصيادلة</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu_item" href="{{ route('suppliers.index') }}">
                    <i class="side-menu__icon bi bi-truck " viewBox="0 0 24 24" style="transform: scaleX(-1);"></i>
                    <span class="side-menu__label">الموردين</span>
                </a>
            </li>
        @endrole
        @hasanyrole('المشرف|صيدلي|مورد')
            @php
                $newMedicinesCount = \Modules\Medicine\Models\Medicine::where('is_new', true)
                    ->whereDate('new_start_date', '<=', now())
                    ->whereDate('new_end_date', '>=', now())
                    ->count();
            @endphp
            {{-- show all subCategories with medicine it --}}
            @isset($subcategories)
                @if ($subcategories->isNotEmpty())
                    <li class="slide">
                        <a class="side-menu_item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i
                                class="side-menu__icon bi bi-diagram-3 " viewBox="0 0 24 24"></i><span
                                class="side-menu__label">تصنيفات</span><i class="angle fe fe-chevron-down"></i></a>
                        <ul class="slide-menu">
                            @foreach ($subcategories as $subcategory)
                                <li><a class="slide-item"
                                        href="{{ route('category.show', $subcategory->id) }}">{{ $subcategory->name }}</a></li>
                            @endforeach
                        </ul>
                    </li>
                @endif
            @endisset
            {{-- medicines --}}
            <li class="slide">
                <a class="side-menu_item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i
                        class="side-menu__icon bi bi-capsule p-2" viewBox="0 0 24 24"></i><span
                        class="side-menu__label">الأدوية</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('medicines.index') }}">جميع الأدوية</a></li>
                    @if ($newMedicinesCount > 0)
                        <li><a class="slide-item" href="{{ route('medicines.new') }}">الأدوية جديدة</a></li>
                    @endif
                    @role('مورد')
                        <li><a class="slide-item" href="{{ route('my-medicines') }}">أدوية مستودعي</a></li>
                        <li><a class="slide-item" href="{{ route('offers.index') }}">عروض الأدوية</a></li>
                    @endrole
                </ul>
            </li>

            {{-- orders --}}
            <li class="slide">
                <a class="side-menu_item" href="{{ route('orders.index') }}">
                    <i class="side-menu__icon bi bi-bag-check p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">الطلبات</span>
                </a>
            </li>
        @endhasanyrole
        @hasanyrole('المشرف|مورد')
            {{-- reports --}}
            <li class="slide">
                <a class="side-menu_item" href="#">
                    <i class="side-menu__icon bi bi-bar-chart-line p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">تقارير المبيعات</span>
                </a>
            </li>
        @endhasanyrole
    </ul>
</aside>
<!-- main-sidebar -->
