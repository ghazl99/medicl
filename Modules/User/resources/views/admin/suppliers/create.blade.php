@extends('core::components.layouts.master')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة مورد جديد</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row g-3">
                    {{-- اسم المستخدم --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="ادخل اسم المستخدم" required
                            autocomplete="name" autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email') }}" placeholder="example@example.com" required
                            autocomplete="email" />
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- كلمة المرور --}}
                    <div class="col-md-6">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password" placeholder="ادخل كلمة المرور" required autocomplete="new-password" />
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- تأكيد كلمة المرور --}}
                    <div class="col-md-6">
                        <label for="password_confirmation" class="form-label">تأكيد كلمة المرور</label>
                        <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                            id="password_confirmation" name="password_confirmation" placeholder="اعد كتابة كلمة المرور"
                            required autocomplete="new-password" />
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- رقم الهاتف --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone') }}" placeholder="09xxxxxxxx" required />
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- اسم مكان العمل --}}
                    <div class="col-md-6">
                        <label for="workplace_name" class="form-label">اسم الشركة </label>
                        <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                            id="workplace_name" name="workplace_name" value="{{ old('workplace_name') }}"
                            placeholder="اسم الشركة" required />
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

                <button type="submit" class="btn btn-primary btn-sm mt-4">إنشاء الحساب</button>
            </form>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            $('#cities').select2({
                tags: true,
                tokenSeparators: [',', '،']
            });
        });
    </script>
@endsection
