@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة دواء جديد</h2>
            <form method="POST" action="{{ route('medicines.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="category_id">الصنف</label>
                        <select class="form-control" name="category_id" id="category_id" required>
                            <option value="">اختر صنفًا</option>
                            @foreach ($categories as $category)
                                @foreach ($category->children as $child)
                                    <option value="{{ $child->id }}"
                                        {{ old('category_id') == $child->id ? 'selected' : '' }}>
                                        {{ $category->name }} - {{ $child->name }}
                                    </option>
                                @endforeach
                            @endforeach


                        </select>
                        @error('category_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="image" class="form-label">صورة الدواء</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*" />
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- الصنف -->
                    <div class="col-md-6">
                        <label for="type" class="form-label">الصنف</label>
                        <input type="text" class="form-control " id="type" name="type"
                            value="{{ old('type') }}" placeholder="ادخل تركيب الدواء" />
                        @error('type')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- التركيب -->
                    <div class="col-md-6">
                        <label for="composition" class="form-label">التركيب</label>
                        <input type="text" class="form-control" id="composition" name="composition"
                            value="{{ old('composition') }}" placeholder="ادخل اسم الدواء" />
                        @error('composition')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- الشكل -->
                    <div class="col-md-6">
                        <label for="form" class="form-label">الشكل</label>
                        <input type="text" class="form-control" id="form" name="form"
                            value="{{ old('form') }}" placeholder="ادخل شكل الدواء" />
                        @error('form')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- الشركة المصنعة -->
                    <div class="col-md-6">
                        <label for="company" class="form-label">الشركة المصنعة</label>
                        <input type="text" class="form-control " id="company" name="company"
                            value="{{ old('company') }}" placeholder="ادخل اسم الشركة" />
                        @error('company')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- ملاحظات -->
                    <div class="col-md-6">
                        <label for="note" class="form-label">ملاحظات</label>
                        <input type="text" class="form-control " id="note" name="note"
                            value="{{ old('note') }}" placeholder="أدخل ملاحظات إن وجدت" />
                        @error('note')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                     <!--  الوصف -->
                    <div class="col-md-6">
                        <label for="description" class="form-label">وصف الدواء</label>
                        <input type="text" class="form-control" id="description"
                            name="description" value="{{ old('description') }}" placeholder="وصف الدواء" />
                        @error('description')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <!-- نت دولار حالي -->
                    {{-- <div class="col-md-6">
                        <label for="net_dollar_old" class="form-label">نت دولار حالي</label>
                        <input type="number" step="0.01"
                            class="form-control " id="net_dollar_old"
                            name="net_dollar_old" value="{{ old('net_dollar_old') }}" placeholder="ادخل السعر" />
                        @error('net_dollar_old')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <!-- عموم دولار حالي -->
                    {{-- <div class="col-md-6">
                        <label for="public_dollar_old" class="form-label">عموم دولار حالي</label>
                        <input type="number" step="0.01"
                            class="form-control " id="public_dollar_old"
                            name="public_dollar_old" value="{{ old('public_dollar_old') }}" placeholder="ادخل السعر" />
                        @error('public_dollar_old')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <!-- نت دولار جديد -->
                    <div class="col-md-6">
                        <label for="net_dollar_new" class="form-label">نت دولار </label>
                        <input type="number" step="0.01" class="form-control " id="net_dollar_new" name="net_dollar_new"
                            value="{{ old('net_dollar_new') }}" placeholder="ادخل السعر" />
                        @error('net_dollar_new')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- عموم دولار جديد -->
                    <div class="col-md-6">
                        <label for="public_dollar_new" class="form-label">عموم دولار </label>
                        <input type="number" step="0.01" class="form-control" id="public_dollar_new"
                            name="public_dollar_new" value="{{ old('public_dollar_new') }}" placeholder="ادخل السعر" />
                        @error('public_dollar_new')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <!-- نت سوري -->
                    {{-- <div class="col-md-6">
                        <label for="net_syp" class="form-label">نت سوري</label>
                        <input type="number" step="0.01" class="form-control "
                            id="net_syp" name="net_syp" value="{{ old('net_syp') }}"
                            placeholder="ادخل السعر بالليرة السورية" />
                        @error('net_syp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}

                    <!-- عموم سوري -->
                    {{-- <div class="col-md-6">
                        <label for="public_syp" class="form-label">عموم سوري</label>
                        <input type="number" step="0.01"
                            class="form-control" id="public_syp"
                            name="public_syp" value="{{ old('public_syp') }}"
                            placeholder="ادخل السعر بالليرة السورية" />
                        @error('public_syp')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}



                    <!-- نسبة تغير السعر -->
                    {{-- <div class="col-md-6">
                        <label for="price_change_percentage" class="form-label">نسبة تغير السعر (%)</label>
                        <input type="number" step="0.01"
                            class="form-control"
                            id="price_change_percentage" name="price_change_percentage"
                            value="{{ old('price_change_percentage') }}" placeholder="مثال: 12.5" />
                        @error('price_change_percentage')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div> --}}
                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-4">إضافة الدواء</button>
            </form>
        </div>
    </div>
@endsection
