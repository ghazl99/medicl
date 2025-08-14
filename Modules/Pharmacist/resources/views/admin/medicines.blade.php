@extends('pharmacist::components.layouts.master')
@section('css')
@endsection
@section('content')

    <div class="container">
        <br>
        <h3>مستودع  {{ $user->name }}</h3>
        <div class="row">
            @foreach ($medicines as $medicine)
                @php
                    $media = $medicine->getFirstMedia('medicine_images');
                    $image = $media ? route('medicines.image', $media->id) : asset('assets/img/medicine.avif');
                @endphp
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        <img src="{{ $image }}"
                            class="card-img-top" alt="{{ $medicine->type }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $medicine->type }}</h5>
                            <p>السعر: {{ $medicine->net_dollar_new }}$</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- أزرار التصفح -->
        <div class="d-flex justify-content-center">
            {{ $medicines->links() }}
        </div>
    </div>
@endsection
