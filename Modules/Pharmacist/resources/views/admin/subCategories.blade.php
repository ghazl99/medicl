@extends('pharmacist::components.layouts.master')

@section('content')
<div class="container mt-4">
    <h3>{{ $category->name }}</h3>

    @if ($category->children->isEmpty())
        <p>لا توجد فئات فرعية.</p>
    @else
        <div class="row">
            @foreach ($category->children as $sub)
                @php
                    $media = $sub->getFirstMedia('category_images');
                    $image = $media ? route('category.image', $media->id) : asset('assets/img/medicine.avif');
                @endphp
                <div class="col-md-3 mb-3">
                    <a href="{{ route('sub.categories.medicines', $sub->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100">
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $sub->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $sub->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
