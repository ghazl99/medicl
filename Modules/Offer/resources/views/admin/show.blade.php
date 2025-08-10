@extends('core::components.layouts.master')
@section('content')
    <br>
    <div class="card">
        <div class="card-header text-right">
            <h3>تفاصيل العرض: {{ $offer->title }}</h3>
        </div>
        <div class="card-body text-right">
            <p><strong>التفاصيل:</strong> {{ $offer->details }}</p>
            <p><strong>تاريخ البداية:</strong> {{ $offer->offer_start_date->format('Y-m-d') }}</p>
            <p><strong>تاريخ الانتهاء:</strong> {{ $offer->offer_end_date->format('Y-m-d') }}</p>

            <h5>الصور:</h5>

            <div class="row">
                @foreach ($offer->getMedia('offer_images') as $image)
                    <div class="col-md-3 mb-3">
                        <img src="{{ route('offer.image', $image->id) }}" class="img-fluid rounded" alt="صورة العرض">
                    </div>
                @endforeach

            </div>

            <a href="{{ route('offers.index') }}" class="btn btn-secondary mt-3">عودة للعروض</a>
        </div>
    </div>
@endsection
