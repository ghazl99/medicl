@extends('core::components.layouts.master')
@section('css')
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

@endsection

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة صنف جديد جديد</h2>

            <form method="POST" action="{{ route('category.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="name" class="form-label">اسم الصنف</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name"
                            name="name" value="{{ old('name') }}" placeholder="ادخل اسم الصنف" required autofocus />
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-6">
                        <label for="image" class="form-label">صورة الصنف</label>
                        <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                            name="image" accept="image/*" />
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="col-md-12 mt-3">
                        <label for="subcategories" class="form-label">أقسام فرعية (اختياري)</label>
                        <select name="subcategories[]" id="subcategories" class="form-control" multiple="multiple">
                            {{-- فارغ بدون خيارات مبدئية --}}
                        </select>
                        <small class="text-muted">يمكنك كتابة أقسام جديدة مباشرة وسيتم إضافتها تلقائيًا.</small>
                    </div>


                </div>

                <button type="submit" class="btn btn-primary btn-sm mt-4">إضافة </button>
            </form>
        </div>
    </div>
@endsection
@section('js')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(function () {
        $('#subcategories').select2({
            tags: true,
            tokenSeparators: [',', '،']
        });
    });
</script>
@endsection

