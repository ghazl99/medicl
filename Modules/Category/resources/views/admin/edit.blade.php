@extends('core::components.layouts.master')

@section('content')
    <div class="card">
        <div class="card-header">تعديل الصنف</div>
        <div class="card-body">
            <form action="{{ route('category.update', $category->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="name">اسم الصنف</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $category->name) }}" required>
                </div>

                <div class="form-group mt-3">
                    <label for="image">صورة الصنف (اختياري)</label>
                    <input type="file" name="image" id="image" class="form-control">
                    @if($category->getFirstMediaUrl('category_images'))
                        <img src="{{ $category->getFirstMediaUrl('category_images') }}" alt="صورة الصنف" style="width: 80px; height: 80px; margin-top:10px;">
                    @endif
                </div>

                <button type="submit" class="btn btn-primary mt-3">تحديث</button>
            </form>
        </div>
    </div>
@endsection
