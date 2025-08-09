@extends('core::components.layouts.master')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
<br>
    <div class="card">
        <div class="card-header">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بيانات المورد</h2>
        </div>
        <div class="card-body">

            <form method="POST" action="{{ route('users.update', $supplier->id) }}">
                @csrf
                @method('patch')

                <div class="row g-3">
                    {{-- اسم المستخدم --}}
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم المستخدم</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name', $supplier->name) }}" placeholder="ادخل اسم المستخدم"
                            required autocomplete="name" autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- البريد الإلكتروني --}}
                    <div class="col-md-6">
                        <label for="email" class="form-label">البريد الإلكتروني</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $supplier->email) }}" placeholder="example@example.com"
                            required autocomplete="email" />
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>


                    {{-- رقم الهاتف --}}
                    <div class="col-md-6">
                        <label for="phone" class="form-label">رقم الهاتف</label>
                        <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone"
                            name="phone" value="{{ old('phone', $supplier->phone) }}" placeholder="09xxxxxxxx" required />
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- اسم مكان العمل --}}
                    <div class="col-md-6">
                        <label for="workplace_name" class="form-label">اسم الموردة</label>
                        <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                            id="workplace_name" name="workplace_name"
                            value="{{ old('workplace_name', $supplier->workplace_name) }}"
                            placeholder="اسم الموردة أو الشركة" required />
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
                                <option value="{{ $city->id }}"
                                    {{ in_array($city->id, old('cities', $supplier->cities->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                                @foreach ($city->children as $subCity)
                                    <option value="{{ $subCity->id }}"
                                        {{ in_array($subCity->id, old('cities', $supplier->cities->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        -- {{ $subCity->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('cities')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- الموافقة --}}
                    <div class="col-md-6">
                        <label for="is_approved" class="form-label d-block">حالة الموافقة</label>
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_approved" name="is_approved"
                                value="1" {{ old('is_approved', $supplier->is_approved) ? 'checked' : '' }}>
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
