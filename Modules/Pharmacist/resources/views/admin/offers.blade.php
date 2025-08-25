@extends('pharmacist::components.layouts.master')

@section('content')
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title"> العروض</h2>
        </div>

        <div class="cart-items">
            @forelse ($offers as $offer)
                <div class="cart-item d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <div class="item-image me-3">
                            <i class="bi bi-tags fs-3"></i>
                        </div>

                        <div class="item-info me-3">
                            <h3>{{ $offer->title ?? 'غير معروف' }}</h3>

                        </div>
                    </div>

                    <div class="item-actions">
                        <a href="{{ route('new.offer.details', $offer) }}"
                            class="btn btn-sm btn-outline-primary" title="عرض تفاصيل العرض">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            @empty
            @endforelse

        </div>
        <div class="d-flex justify-content-center mt-2 mb-4">
            {{ $offers->links() }}
        </div>

    </section>
@endsection
