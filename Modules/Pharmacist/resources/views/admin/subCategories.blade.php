@extends('pharmacist::components.layouts.master')

@section('content')
    <section id="portfolio" class="portfolio" dir="rtl">
        <!-- Section Title -->
        <div class="container  mt-5">
            <h1>{{ $category->name }}</h2>
                @if ($category->children->isEmpty())
                    <p>لا توجد فئات فرعية.</p>
                @else
                    <div class="isotope-layout" data-default-filter="*" data-layout="fitRows" data-sort="original-order">

                        <div class="row gy-4 portfolio-grid isotope-container">
                            @foreach ($category->children as $sub)
                                @php
                                    $media = $sub->getFirstMedia('category_images');
                                    $image = $media
                                        ? route('category.image', $media->id)
                                        : asset('assets/img/medicine.avif');
                                @endphp
                                <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                                    <div class="portfolio-card">
                                        <div class="image-container">
                                            <img src="{{ $image }}" class="img-fluid" alt="{{ $sub->name }}"
                                                loading="lazy">
                                            <div class="overlay">
                                                <div class="overlay-content">
                                                    <a href="{{ route('sub.categories.medicines', $sub->id) }}"
                                                        class="details-link" title="عرض أدوية الصنف الفرعي">
                                                        <i class="bi bi-arrow-right"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <h3>{{ $sub->name }}</h3>
                                        </div>
                                    </div>

                                </div><!-- End Portfolio Grid -->
                            @endforeach

                        </div>

                    </div>
                @endif
        </div><!-- End Section Title -->
    </section><!-- /Portfolio Section -->


@endsection
