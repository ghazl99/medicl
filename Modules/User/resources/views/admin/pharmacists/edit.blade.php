@extends('core::components.layouts.master')
@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بيانات الصيدلي</h2>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('users.update', $pharmacist->id) }}">
                @csrf
                @method('patch') {{-- إضافة هذا لتحديد طريقة HTTP كـ PATCH --}}

                <div class="row g-3">
                    {{-- اسم المستخدم --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $pharmacist->name) }}" {{-- ملء الحقل بقيمة الصيدلي الحالية --}}
                            placeholder="ادخل اسم المستخدم" required autocomplete="name" autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $pharmacist->email) }}" {{-- ملء الحقل بقيمة الصيدلي الحالية --}}
                            placeholder="example@example.com" required autocomplete="email" />
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- رقم الهاتف --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone', $pharmacist->phone) }}" {{-- ملء الحقل بقيمة الصيدلي الحالية --}}
                            placeholder="09xxxxxxxx" required />
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- اسم مكان العمل --}}
                    <div class="col-md-6">
                        <label for="workplace_name" class="form-label">اسم الصيدلية</label>
                        <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                            id="workplace_name" name="workplace_name"
                            value="{{ old('workplace_name', $pharmacist->workplace_name) }}" {{-- ملء الحقل بقيمة الصيدلي الحالية --}}
                            placeholder="اسم الصيدلية أو الشركة" required />
                        @error('workplace_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- المدينة --}}
                    <div class="col-md-6">
                        <label for="city" class="form-label">المدينة</label>
                        <input type="text" class="form-control @error('city') is-invalid @enderror" id="city"
                            name="city" value="{{ old('city', $pharmacist->city) }}" {{-- ملء الحقل بقيمة الصيدلي الحالية --}}
                            placeholder="اسم المدينة" required />
                        @error('city')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    {{-- الموافقة --}}
                    <div class="col-md-6">
                        <label for="is_approved" class="form-label d-block">حالة الموافقة</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                value="1" {{ old('is_approved', $pharmacist->is_approved) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_approved">موافق عليه</label>
                        </div>
                        @error('is_approved')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-4">حفظ التغييرات</button>
            </form>
        </div>
    </div>
@endsection
