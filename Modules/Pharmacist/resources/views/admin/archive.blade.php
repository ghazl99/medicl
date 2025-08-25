@extends('pharmacist::components.layouts.master')

@section('content')
    <section class="cart-section">
        <div class="cart-header ">
            <h2 class="cart-section-title">الأرشيف</h2>
        </div>

        <div class="cart-items">
            @foreach ($orders as $item)
                <div class="cart-item d-flex justify-content-between align-items-center bg-light">
                    <div class="d-flex align-items-center">
                        <div class="item-image me-3">
                            <i class="bi bi-archive fs-3"></i>
                        </div>
                        <div class="item-info me-3">
                            @hasanyrole('المشرف|مورد')
                                <h3>{{ $item->pharmacist->name }}</h3>
                            @endhasanyrole
                            @hasanyrole('المشرف|صيدلي')
                                <h3>{{ $item->supplier->name }}</h3>
                            @endhasanyrole
                            @if ($item->status == 'تم التسليم')
                                <span class="badge bg-success">تم التسليم</span>
                            @endif
                        </div>
                    </div>
                    <div class="item-actions">
                        <a href="{{ route('details.order', $item->id) }}" class="btn btn-sm btn-outline-primary"
                            title="عرض تفاصيل الطلب">
                            <i class="bi bi-arrow-left"></i>
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="d-flex justify-content-center mt-2 mb-4">
            {{ $orders->links() }}
        </div>
    </section>
@endsection
