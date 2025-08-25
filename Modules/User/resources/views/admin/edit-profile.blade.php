@extends('core::layouts.master')
@section('css')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
@endsection
@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بياناتي الشخصية</h2>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
                @csrf
                @method('patch')


                <div class="row g-3">
                    @hasanyrole('المشرف|صيدلي')
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
                    @endhasanyrole
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
                        <label for="workplace_name" class="form-label">
                            @if (auth()->user()->hasRole('صيدلي'))
                                اسم الصيدلية
                            @elseif(auth()->user()->hasRole('مورد'))
                                اسم الشركة
                            @else
                                الاسم
                            @endif
                        </label>
                        <input type="text" class="form-control @error('workplace_name') is-invalid @enderror"
                            id="workplace_name" name="workplace_name"
                            value="{{ old('workplace_name', $user->workplace_name) }}" placeholder="اسم الموردة أو الشركة"
                            required />
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
                                    {{ in_array($city->id, old('cities', $user->cities->pluck('id')->toArray())) ? 'selected' : '' }}>
                                    {{ $city->name }}
                                </option>
                                @foreach ($city->children as $subCity)
                                    <option value="{{ $subCity->id }}"
                                        {{ in_array($subCity->id, old('cities', $user->cities->pluck('id')->toArray())) ? 'selected' : '' }}>
                                        -- {{ $subCity->name }}
                                    </option>
                                @endforeach
                            @endforeach
                        </select>
                        @error('cities')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- حقل رفع الصورة --}}
                    <div class="col-md-6">
                        <label for="profile_photo" class="form-label">تغيير صورة الملف الشخصي</label>
                        <input type="file" class="form-control" id="profile_photo" name="profile_photo"
                            accept="image/*" />

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
@section('js')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <script>
        $(function() {
            $('#cities').select2({
                tags: true,
                tokenSeparators: [',', '،'],
                width: '100%'
            });
        });
    </script>
@endsection
