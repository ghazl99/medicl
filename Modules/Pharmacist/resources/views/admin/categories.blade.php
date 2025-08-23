@extends('pharmacist::components.layouts.master')
@section('css')
@endsection
@section('content')
    @php
        $newMedicinesCount = \Modules\Medicine\Models\Medicine::where('is_new', true)
            ->whereDate('new_start_date', '<=', now())
            ->whereDate('new_end_date', '>=', now())
            ->count();
    @endphp
    <section id="portfolio" class="portfolio" dir="rtl">
        <!-- Section Title -->
        <div class="container mt-2">
            @if ($newMedicinesCount)
                <div class="cart-header">
                    <h2 class="cart-section-title">الأدوية الجديدة</h2>
                    <a href="{{ route('new.medicines') }}" class="btn btn-outline-light mb-3">عرض الأدوية الجديدة</a>
                </div>
            @endif
            <h1 class="mb-2">الأصناف الرئيسية</h2>
                <div class="isotope-layout" data-default-filter="*" data-layout="fitRows" data-sort="original-order">

                    <div class="row gy-4 portfolio-grid isotope-container">
                        @foreach ($categories as $category)
                            @php
                                $media = $category->getFirstMedia('category_images');
                                $image = $media
                                    ? route('category.image', $media->id)
                                    : asset('assets/img/medicine.avif');
                            @endphp
                            <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                                <div class="portfolio-card">
                                    <div class="image-container">
                                        <img src="{{ $image }}" class="img-fluid" alt="{{ $category->name }}"
                                            loading="lazy">
                                        <div class="overlay">
                                            <div class="overlay-content">
                                                <a href="{{ route('sub.categories', $category->id) }}" class="details-link"
                                                    title="عرض الاصناف الفرعية">
                                                    <i class="bi bi-arrow-right"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3>{{ $category->name }}</h3>
                                    </div>
                                </div>

                            </div><!-- End Portfolio Grid -->
                        @endforeach

                    </div>

                </div>
        </div><!-- End Section Title -->
    </section><!-- /Portfolio Section -->

    <!-- أزرار التصفح -->
    <div class="d-flex justify-content-center mt-2 mb-4">
        {{ $categories->links() }}
    </div>
@endsection
