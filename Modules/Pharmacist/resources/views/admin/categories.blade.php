@extends('pharmacist::components.layouts.master')
@section('css')
@endsection
@section('content')
    <div class="container">
        <br>
        <div class="row">
            @foreach ($categories as $category)
                @php
                    $media = $category->getFirstMedia('category_images');
                    $image = $media ? route('category.image', $media->id) : asset('assets/img/medicine.avif');
                @endphp
                <div class="col-md-3 mb-3">
                    <a href="{{ route('sub.categories', $category->id) }}" class="text-decoration-none text-dark">

                        <div class="card h-100">
                            <img src="{{ $image }}" class="card-img-top" alt="{{ $category->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $category->name }}</h5>
                            </div>
                        </div>
                    </a>
                </div>
            @endforeach
        </div>

        <!-- أزرار التصفح -->
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>
@endsection
