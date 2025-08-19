@extends('pharmacist::components.layouts.master')
@section('css')
@endsection
@section('content')
    <div style="margin: 190px 20px">
        <h3>أدوية الفئة: {{ $subcategory->name }}</h3>

        @if ($subcategory->medicines->isEmpty())
            <p>لا توجد أدوية في هذه الفئة.</p>
        @else
            <div class="row">
                @foreach ($medicines as $medicine)
                    @php
                        $media = $medicine->getFirstMedia('medicine_images');
                        $image = $media ? route('medicines.image', $media->id) : asset('assets/img/medicine.avif');
                    @endphp
                    <div class="col-md-3 mb-3">
                        <div class="card h-100">
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $medicine->type }}"
                                style="height:150px; object-fit:cover;">
                            <div class="card-body">
                                <h5 class="card-title">{{ $medicine->type }}</h5>
                                <p class="mb-1">التركيب: {{ $medicine->composition }}</p>
                                <p class="mb-1">الشركة: {{ $medicine->company }}</p>
                                <p class="mb-1">السعر:
                                    {{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) . '$' : '-' }}
                                </p>
                                <p class="mb-0 text-truncate">الشكل:{{ $medicine->form }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="d-flex justify-content-center mt-2 mb-4">
                {{ $medicines->links() }}
            </div>
        @endif
    </div>

@endsection
