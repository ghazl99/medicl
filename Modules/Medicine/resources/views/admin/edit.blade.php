{{-- resources/views/medicines/edit.blade.php --}}
@extends('core::components.layouts.master')

@section('content')
    <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">تعديل بيانات الدواء</h2>


    <form method="POST" action="{{ route('medicines.update', $medicine->id) }}">
        @csrf
        @method('patch')

        <div class="row g-3">
            {{-- اسم الدواء --}}
            <div class="col-md-6">
                <label for="name" class="form-label">اسم الدواء</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name', $medicine->name) }}"
                    placeholder="ادخل اسم الدواء" required autofocus />
                @error('name')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- الشركة المصنعة --}}
            <div class="col-md-6">
                <label for="manufacturer" class="form-label">الشركة المصنعة</label>
                <input type="text" class="form-control @error('manufacturer') is-invalid @enderror" id="manufacturer"
                    name="manufacturer" value="{{ old('manufacturer', $medicine->manufacturer) }}"
                    placeholder="ادخل اسم الشركة المصنعة" />
                @error('manufacturer')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- الكمية المتوفرة --}}
            <div class="col-md-6">
                <label for="quantity_available" class="form-label">الكمية المتوفرة</label>
                <input type="number" class="form-control @error('quantity_available') is-invalid @enderror" id="quantity_available"
                    name="quantity_available" value="{{ old('quantity_available', $medicine->quantity_available) }}"
                    placeholder="ادخل الكمية المتوفرة" required min="0" />
                @error('quantity_available')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>

            {{-- السعر --}}
            <div class="col-md-6">
                <label for="price" class="form-label">السعر</label>
                <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror" id="price"
                    name="price" value="{{ old('price', $medicine->price) }}"
                    placeholder="ادخل سعر الدواء" required min="0" />
                @error('price')
                    <small class="text-danger">{{ $message }}</small>
                @enderror
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-sm mt-4">حفظ التغييرات</button>
    </form>
@endsection
