<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - نظام إدارة الصيدليات</title>
    <link rel="icon" href="{{ URL::asset('assets/img/capsule.png') }}" type="image/x-icon" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #e0e7ff 0%, #f8fafc 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.08);
            padding: 2.5rem 2rem;
            max-width: 370px;
            width: 100%;
        }

        .login-title {
            font-weight: bold;
            margin-bottom: 1.5rem;
            color: #3b82f6;
            text-align: center;
        }

        .form-label {
            font-weight: 500;
        }

        .form-control:focus {
            border-color: #3b82f6;
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, .15);
        }

        .btn-primary {
            background-color: #3b82f6;
            border: none;
        }

        .btn-primary:hover {
            background-color: #2563eb;
        }
    </style>
</head>

<body>
    <div class="login-box">
        <h2 class="login-title">تسجيل الدخول</h2>
        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Hidden FCM Token -->
            <input type="text" hidden name="fcm_token" id="fcm_token">
            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email') }}" placeholder="example@example.com" required
                    autocomplete="email" />
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- كلمة المرور --}}
            <div class="mb-3">
                <label for="password" class="form-label">كلمة المرور</label>
                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                    name="password" placeholder="ادخل كلمة المرور" required autocomplete="new-password" />
                @error('password')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            <!-- Remember Me -->
            {{-- <div class="block mt-4">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox"
                        class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                        name="remember">
                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                </label>
            </div> --}}

            <div class="flex items-center justify-end mt-4">
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
                    href="{{ route('register.suppliers') }}">
                    {{ __('إنشاء حساب للمورد') }}
                </a>
                <br>
                <button type="submit" class="btn btn-primary w-100">دخول</button>
            </div>
        </form>
    </div>
    {{-- SweetAlert --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- رسائل الجلسة باستخدام Swal --}}
    <script>
        function getSwalThemeOptions() {
            const isDark = document.body.classList.contains('dark-theme');
            return isDark ? {
                background: '#141b2d',
                color: '#ffffff'
            } : {
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

    <script type="module">
        import {
            initializeApp
        } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-app.js";
        import {
            getAnalytics
        } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-analytics.js";
        import {
            getMessaging,
            getToken,
            onMessage
        } from "https://www.gstatic.com/firebasejs/12.1.0/firebase-messaging.js";
        // Your web app's Firebase configuration
        // For Firebase JS SDK v7.20.0 and later, measurementId is optional
        const firebaseConfig = {
            apiKey: "AIzaSyC9Bsp_V1BLRFtX5z985ebrdwuPVoygYO8",
            authDomain: "medical-3dbfb.firebaseapp.com",
            projectId: "medical-3dbfb",
            storageBucket: "medical-3dbfb.firebasestorage.app",
            messagingSenderId: "3861161428",
            appId: "1:3861161428:web:37c9514c82c5214ede2241",
            measurementId: "G-4Z61EGYPRK"
        };

        // Initialize Firebase
        const app = initializeApp(firebaseConfig);
        const messaging = getMessaging(app);
        // طلب إذن الإشعارات
        Notification.requestPermission().then((permission) => {
            if (permission === 'granted') {
                console.log('Notification permission granted.');

                // الحصول على التوكن الخاص بالمتصفح
                getToken(messaging, {
                    vapidKey: 'BFA76F894wxUY-icARXZkF6mQxWfEhaxl_0uq8toNVa6QxPbW2aUF25PNnlarChYphOJLJLxXM7nRazd--zxo6Q'
                }).then((currentToken) => {
                    if (currentToken) {
                        console.log('FCM Token:', currentToken);
                        document.getElementById('fcm_token').value = currentToken;
                    } else {
                        console.log('No registration token available. Request permission to generate one.');
                    }
                }).catch((err) => {
                    console.log('An error occurred while retrieving token. ', err);
                });
            } else {
                console.log('Unable to get permission to notify.');
            }
        });


    </script>
</body>

</html>
