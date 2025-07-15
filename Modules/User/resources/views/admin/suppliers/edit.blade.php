@extends('core::components.layouts.master')
@section('content')
    <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بيانات المورد</h2>

    <form method="POST" action="{{ route('users.update', $supplier->id) }}">
        @csrf
        @method('patch')

        <div class="row g-3">
            {{-- اسم المستخدم --}}
            <div class="col-md-6">
                <label for="name" class="form-label">اسم المستخدم</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $supplier->name) }}" {{-- ملء الحقل بقيمة المورد الحالية --}}
                    placeholder="ادخل اسم المستخدم" required autocomplete="name" autofocus />
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- البريد الإلكتروني --}}
            <div class="col-md-6">
                <label for="email" class="form-label">البريد الإلكتروني</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                    name="email" value="{{ old('email', $supplier->email) }}" {{-- ملء الحقل بقيمة المورد الحالية --}}
                    placeholder="example@example.com" required autocomplete="email" />
                @error('email')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>


            {{-- رقم الهاتف --}}
            <div class="col-md-6">
                <label for="phone" class="form-label">رقم الهاتف</label>
                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                    name="phone" value="{{ old('phone', $supplier->phone) }}" {{-- ملء الحقل بقيمة المورد الحالية --}}
                    placeholder="09xxxxxxxx" required />
                @error('phone')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- اسم مكان العمل --}}
            <div class="col-md-6">
                <label for="workplace_name" class="form-label">اسم الموردة</label>
                <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                    id="workplace_name" name="workplace_name"
                    value="{{ old('workplace_name', $supplier->workplace_name) }}" {{-- ملء الحقل بقيمة المورد الحالية --}}
                    placeholder="اسم الموردة أو الشركة" required />
                @error('workplace_name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- المدينة --}}
            <div class="col-md-6">
                <label for="city" class="form-label">المدينة</label>
                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city"
                    value="{{ old('city', $supplier->city) }}" {{-- ملء الحقل بقيمة المورد الحالية --}}
                    placeholder="اسم المدينة" required />
                @error('city')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

        </div>

        <button type="submit" class="btn btn-primary btn-sm mt-4">حفظ التغييرات</button>
    </form>
@endsection
