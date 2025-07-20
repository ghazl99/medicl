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
            <div class="mt-4">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                @if (session('warning'))
                    <div class="alert alert-warning">{{ session('warning') }}</div>
                @endif
            </div>
            @yield('content')
            @include('core::layouts.sidebar')
            @include('core::layouts.models')
            @include('core::layouts.footer-scripts')
        </div>
    </div>

    <!-- dataTables Scripts -->

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.2/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/dataTables.fixedColumns.js"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/5.0.4/js/fixedColumns.dataTables.js"></script>

    <!-- Bootstrap 4 rtl -->
    <script src="https://cdn.rtlcss.com/bootstrap/v4.2.1/js/bootstrap.min.js"></script>

    <!-- Alert Auto-hide -->
    <script>
        $(document).ready(function() {
            $(".alert").slideDown(300).delay(4000).slideUp(300);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const body = document.body;
            const toggleBtn = document.querySelector('.theme-toggle');
            const themeIcon = document.getElementById('theme-icon');

            // تحقق من الوضع المخزن
            const savedTheme = localStorage.getItem('theme');
            if (savedTheme === 'dark') {
                body.classList.remove('light-theme');
                body.classList.add('dark-theme');
                themeIcon.classList.remove('bi-moon-fill');
                themeIcon.classList.add('bi-sun-fill');
            }

            toggleBtn.addEventListener('click', function() {
                body.classList.toggle('dark-theme');
                body.classList.toggle('light-theme');

                // تحديث الأيقونة
                themeIcon.classList.toggle('bi-moon-fill');
                themeIcon.classList.toggle('bi-sun-fill');

                // تخزين الوضع الجديد
                if (body.classList.contains('dark-theme')) {
                    localStorage.setItem('theme', 'dark');
                } else {
                    localStorage.setItem('theme', 'light');
                }
            });
        });
    </script>

    @yield('scripts')
</body>

</html>
