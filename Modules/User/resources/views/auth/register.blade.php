<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>إنشاء حساب - نظام إدارة الصيدليات</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.rtl.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

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
            max-width: 500px;
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
            box-shadow: 0 0 0 0.2rem rgba(59, 130, 246, 0.15);
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
        <h2 class="login-title">إنشاء حساب</h2>
        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="row g-3">
                {{-- اسم المستخدم --}}
                {{-- <div class="col-6">
                    <label for="name" class="form-label">اسم المستخدم</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                        name="name" value="{{ old('name') }}" placeholder="ادخل اسم المستخدم" required
                        autocomplete="name" autofocus />
                    @error('name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div> --}}

                {{-- البريد الإلكتروني --}}
                <div class="col-6">
                    <label for="email" class="form-label">البريد الإلكتروني</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email') }}" placeholder="example@example.com" required
                        autocomplete="email" />
                    @error('email')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- رقم الهاتف --}}
                <div class="col-6">
                    <label for="phone" class="form-label">رقم الهاتف</label>
                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                        name="phone" value="{{ old('phone') }}" placeholder="09xxxxxxxx" required />
                    @error('phone')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>
                {{-- كلمة المرور --}}
                <div class="col-6">
                    <label for="password" class="form-label">كلمة المرور</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password" placeholder="ادخل كلمة المرور" required autocomplete="new-password" />
                    @error('password')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- تأكيد كلمة المرور --}}
                <div class="col-6">
                    <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation" placeholder="اعد كتابة كلمة المرور"
                        required autocomplete="new-password" />
                    @error('password_confirmation')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>


                {{-- اسم مكان العمل --}}
                <div class="col-6">
                    <label for="workplace_name" class="form-label">اسم مكان العمل</label>
                    <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                        id="workplace_name" name="workplace_name" value="{{ old('workplace_name') }}"
                        placeholder="اسم المستودع " required />
                    @error('workplace_name')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- المدن --}}
                <div class="col-md-6">
                    <label for="cities" class="form-label">المدن</label>
                    <select name="cities[]" id="cities" class="form-control @error('cities') is-invalid @enderror"
                        multiple>
                        @foreach ($cities as $city)
                            <option value="{{ $city->id }}">
                                {{ $city->name }}
                            </option>
                            @foreach ($city->children as $subCity)
                                <option value="{{ $subCity->id }}">
                                    -- {{ $subCity->name }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                    @error('cities')
                        <small class="text-danger">{{ $message }}</small>
                    @enderror
                </div>

                {{-- الدور مخفي --}}
                <input type="hidden" name="role" id="role" value="مورد" />
                @error('role')
                    <small class="text-danger">{{ $message }}</small>
                @enderror

            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">إنشاء الحساب</button>
        </form>
    </div>

</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function() {
        $('#cities').select2({
            tags: true,
            dir: "rtl",
            tokenSeparators: [',', '،']
        });
    });
</script>

</html>
