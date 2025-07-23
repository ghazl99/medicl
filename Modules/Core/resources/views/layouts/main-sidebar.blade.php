<style>
    /* القاعدة العامة لجميع عناصر القائمة */
    .app-sidebar .side-menu__item,
    .app-sidebar .side-menu__label,
    .app-sidebar .side-menu__icon,
    h4 {
        color: #fff !important;
        transition: all 0.3s ease;
    }

    /* عند تمرير الماوس */
    .app-sidebar .side-menu__item:hover {
        background-color: rgba(255, 255, 255, 0.1);
        border-radius: 12px;
    }

    /* العنصر النشط (active) */
    .app-sidebar .side-menu__item.active,
    .app-sidebar .side-menu__item:active,
    .app-sidebar .side-menu__item.active:focus {
        background-color: #fff !important;
        color: #2563eb !important;
        border-radius: 12px;
    }

    /* تغيير لون الأيقونة والنص داخل العنصر النشط */
    .app-sidebar .side-menu__item.active .side-menu__icon,
    .app-sidebar .side-menu__item.active .side-menu__label {
        color: #2563eb !important;
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
    .app-sidebar .side-menu__item {
        padding: 10px 15px;
        display: flex;
        align-items: center;
        gap: 10px;
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

        background-image:linear-gradient(135deg, #141b2d 0%, rgb(60 73 93) 60%, rgb(34, 197, 94) 100%);

        color: #fff;
        border-radius: 0 32px 32px 0;

    }
</style>
<!-- main-sidebar -->
<aside class="app-sidebar sidebar-scroll">
    <div class="m-3 active text-center">
        <a href="{{ route('dashboard') }}"
            class="desktop-logo logo-light active d-flex align-items-center justify-content-center gap-2 ">
            <i class="fa fa-capsules p-1" style="font-size: 1.2rem;color:white"></i>
            <h4 class="font-weight-semibold mb-0"> نظام الصيدليات </h4>
        </a>
    </div>
    <ul class="side-menu">

        {{-- dashboard --}}
        @hasanyrole('المشرف|مورد')
            <li class="side-item side-item-category">لوحة التحكم :</li>
            <li class="slide">
                <a class="side-menu__item" href="{{ route('dashboard') }}">
                    <i class="side-menu__icon bi bi-house-door p-2"></i>
                    <span class="side-menu__label">الرئيسية</span>
                </a>
            </li>
        @endhasanyrole
        @role('المشرف')
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
        @endrole
        @hasanyrole('المشرف|صيدلي|مورد')
            {{-- medicines , orders and reports --}}
            <li class="side-item side-item-category">المبيعات والمخزون :</li>

            {{-- medicines --}}
            <li class="slide">
                <a class="side-menu__item" data-toggle="slide" href="{{ url('/' . ($page = '#')) }}"><i
                        class="side-menu__icon bi bi-capsule p-2" viewBox="0 0 24 24"></i><span
                        class="side-menu__label">الأدوية</span><i class="angle fe fe-chevron-down"></i></a>
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
        @endhasanyrole
        @hasanyrole('المشرف|مورد')
            {{-- reports --}}
            <li class="slide">
                <a class="side-menu__item" href="#">
                    <i class="side-menu__icon bi bi-bar-chart-line p-2" viewBox="0 0 24 24"></i>
                    <span class="side-menu__label">تقارير المبيعات</span>
                </a>
            </li>
        @endhasanyrole
    </ul>
</aside>
<!-- main-sidebar -->
