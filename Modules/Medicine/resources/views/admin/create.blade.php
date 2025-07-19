@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة دواء جديد</h2>

            <form method="POST" action="{{ route('medicines.store') }}">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم الدواء</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="ادخل اسم الدواء" required autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="manufacturer" class="form-label">الشركة المصنعة</label>
                        <input type="text" class="form-control @error('manufacturer') is-invalid @enderror"
                            id="manufacturer" name="manufacturer" value="{{ old('manufacturer') }}"
                            placeholder="ادخل اسم الشركة المصنعة" />
                        @error('manufacturer')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="quantity_available" class="form-label">الكمية المتوفرة</label>
                        <input type="number" class="form-control @error('quantity_available') is-invalid @enderror"
                            id="quantity_available" name="quantity_available" value="{{ old('quantity_available', 0) }}"
                            placeholder="ادخل الكمية المتوفرة" required min="0" />
                        @error('quantity_available')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="price" class="form-label">السعر</label>
                        <input type="text" step="0.01" class="form-control @error('price') is-invalid @enderror"
                            id="price" name="price" value="{{ old('price') }}" placeholder="ادخل سعر الدواء" required
                            min="0" />
                        @error('price')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-4">إضافة الدواء</button>
            </form>
        </div>
    </div>
        @endsection
