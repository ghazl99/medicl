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
                <h2 class="mb-2">الأدوية الجديدة</h2>
                <div class="isotope-layout" data-default-filter="*" data-layout="fitRows" data-sort="original-order">

                    <div class="row gy-4 portfolio-grid isotope-container">
                        @foreach ($medicines as $medicine)
                            @php
                                $media = $medicine->getFirstMedia('medicine_images');
                                $image = $media
                                    ? route('medicines.image', $media->id)
                                    : asset('assets/img/medicine.avif');
                            @endphp
                            <div class="col-lg-4 col-md-6 portfolio-item isotope-item filter-branding">
                                <div class="portfolio-card">
                                    <div class="image-container">
                                        <img src="{{ $image }}" class="img-fluid" alt="{{ $medicine->type }}"
                                            loading="lazy">
                                        <div class="overlay">
                                            <div class="overlay-content">
                                                <a href="{{ route('medicines.show', $medicine->id) }}" class="details-link"
                                                    title="عرض تفاصيل المنتج الجديد ">
                                                    <i class="bi bi-plus"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <h3>{{ $medicine->type }}</h3>
                                    </div>
                                </div>

                            </div><!-- End Portfolio Grid -->
                        @endforeach

                    </div>

                </div>
            @endif

        </div><!-- End Section Title -->
    </section><!-- /Portfolio Section -->

    <!-- أزرار التصفح -->
    <div class="d-flex justify-content-center mt-2 mb-4">
        {{ $medicines->links() }}
    </div>
@endsection
