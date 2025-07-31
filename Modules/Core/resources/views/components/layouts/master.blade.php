<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>لوحة التحكم - نظام إدارة الصيدليات</title>

    <!-- Bootstrap 4 RTL -->
    <link rel="stylesheet" href="https://cdn.rtlcss.com/bootstrap/v4.2.1/css/bootstrap.min.css">

    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Ionicons -->
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">


    <!-- Google Font: Source Sans Pro -->
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">

    {{-- dataTables --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.2/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/5.0.4/css/fixedColumns.dataTables.css">
    @include('core::layouts.head')
</head>

<body class="main-body app sidebar-mini light-theme">
    <!-- Loader -->
    <div id="global-loader">
        <img src="{{ URL::asset('assets/img/loader.svg') }}" class="loader-img" alt="Loader">
    </div>
    <!-- /Loader -->
    @include('core::layouts.main-sidebar')

    <!-- main-content -->
    <div class="main-content app-content">
        @include('core::layouts.main-header')
        <!-- container -->
        <div class="container-fluid">

            @yield('content')
            @include('core::layouts.sidebar')
            @include('core::layouts.models')
            @include('core::layouts.footer-scripts')
        </div>
    </div>

    <!-- dataTables Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/fixedColumns.dataTables.js"></script>

    <!-- Bootstrap 4 rtl -->
    <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>

     {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- رسائل الجلسة باستخدام Swal --}}
    <script>
        function getSwalThemeOptions() {
            const isDark = document.body.classList.contains('dark-theme');
            return isDark ?
                {
                    background: '#141b2d',
                    color: '#ffffff'
                } :
                {
                    background: '#ffffff',
                    color: '#000000'
                };
        }

        window.addEventListener('load', function() {
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'نجاح',
                    text: @json(session('success')),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    ...getSwalThemeOptions()
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'خطأ',
                    text: @json(session('error')),
                    timer: 3000,
                    timerProgressBar: true,
                    showConfirmButton: false,
                    ...getSwalThemeOptions()
                });
            @endif
        });
    </script>

   <script>
    document.addEventListener('DOMContentLoaded', function () {
        const body = document.body;
        const toggleBtn = document.getElementById('themeToggle');
        const themeIcon = document.getElementById('theme-icon');

        // تحميل الوضع المحفوظ
        const savedTheme = localStorage.getItem('theme');
        if (savedTheme === 'dark') {
            body.classList.add('dark-theme');
            body.classList.remove('light-theme');
            themeIcon.classList.replace('bi-moon-stars', 'bi-sun-fill');
        } else {
            body.classList.add('light-theme');
        }

        // تبديل الوضع عند الضغط
        toggleBtn.addEventListener('click', function () {
            body.classList.toggle('dark-theme');
            body.classList.toggle('light-theme');

            // تبديل الأيقونة
            if (body.classList.contains('dark-theme')) {
                themeIcon.classList.replace('bi-moon-stars', 'bi-sun-fill');
                localStorage.setItem('theme', 'dark');
            } else {
                themeIcon.classList.replace('bi-sun-fill', 'bi-moon-stars');
                localStorage.setItem('theme', 'light');
            }
        });
    });
</script>


    @yield('scripts')
</body>

</html>
