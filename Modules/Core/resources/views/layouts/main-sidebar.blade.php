<!-- main-sidebar -->
<div class="app-sidebar__overlay " data-toggle="sidebar"></div>
<aside class="app-sidebar sidebar-scroll">
    <div class="main-sidebar-header active text-center">
        <a href="{{ route('dashboard') }}"
            class="desktop-logo logo-light active d-flex align-items-center justify-content-center gap-2 ">
            <i class="fa fa-capsules p-1" style="font-size: 1.2rem;"></i>
            <h4 class="font-weight-semibold mb-0"> نظام الصيدليات </h4>
        </a>
    </div>


    <div class="main-sidemenu">

        <ul class="side-menu">

            {{-- dashboard --}}
            <li class="side-item side-item-category">لوحة التحكم :</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard') }}">
                    <i class="side-menu__icon bi bi-house-door p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>

            {{-- users --}}
            <li class="side-item side-item-category">المستخدمين :</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('pharmacists.index') }}">
                    <i class="side-menu__icon bi bi-person-badge p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">الصيادلة</span>
                </a>
            </li>

            <li class="slide">
                <a class="side-menu__item" href="{{ route('suppliers.index') }}">
                    <i class="side-menu__icon bi bi-truck " viewBox="0 0 24 24" style="transform: scaleX(-1);"></i>
                    <span class="side-menu__label">الموردين</span>
                </a>
            </li>

            {{-- medicines , orders and reports --}}
            <li class="side-item side-item-category">المبيعات والمخزون :</li>
            {{-- medicines --}}
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i class="side-menu__icon bi bi-capsule p-2" viewBox="0 0 24 24"></i><span class="side-menu__label">الأدوية</span><i class="angle fe fe-chevron-down"></i></a>
                <ul class="slide-menu">
                    <li><a class="slide-item" href="{{ route('medicines.index') }}">جميع الأدوية</a></li>
                    @role('مورد')
                    <li><a class="slide-item" href="{{ route('my-medicines') }}">أدوية مستودعي</a></li>
                    @endrole
                </ul>
            </li>

            {{-- orders --}}
            <li class="slide">
                <a class="side-menu__item" href="{{ route('orders.index') }}">
                    <i class="side-menu__icon bi bi-bag-check p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">الطلبات</span>
                </a>
            </li>
            {{-- reports --}}
            <li class="slide">
                <a class="side-menu__item" href="{{ route('orders.index') }}">
                    <i class="side-menu__icon bi bi-bar-chart-line p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">تقارير المبيعات</span>
                </a>
            </li>
        </ul>
    </div>
</aside>
<!-- main-sidebar -->
