@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بيانات الشخصية</h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')

                <div class="row g-3">
                    {{-- اسم المستخدم --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $user->name) }}" placeholder="ادخل اسم المستخدم" required
                            autocomplete="name" autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" placeholder="example@example.com"
                            required autocomplete="email" />
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- رقم الهاتف --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone', $user->phone) }}" placeholder="09xxxxxxxx" required />
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- اسم مكان العمل --}}
                    <div class="col-md-6">
                        <label for="workplace_name" class="form-label">اسم الموردة</label>
                        <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                            id="workplace_name" name="workplace_name"
                            value="{{ old('workplace_name', $user->workplace_name) }}" placeholder="اسم الموردة أو الشركة"
                            required />
                        @error('workplace_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- المدينة --}}
                    <div class="col-md-6">
                        <label for="city" class="form-label">المدينة</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                            name="city" value="{{ old('city', $user->city) }}" placeholder="اسم المدينة" required />
                        @error('city')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- حقل رفع الصورة --}}
                    <div class="col-md-6">
                        <label for="profile_photo" class="form-label">تغيير صورة الملف الشخصي</label>
                        <input type="file" class="form-control @error('profile_photo') is-invalid @enderror"
                            id="profile_photo" name="profile_photo" accept="image/*" />
                        @error('profile_photo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-4">حفظ التغييرات</button>
            </form>
        </div>
    </div>
@endsection
