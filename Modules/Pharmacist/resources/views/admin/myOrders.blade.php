@extends('pharmacist::components.layouts.master')

@section('content')
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title">جميع طلباتي</h2>
        </div>

        <div class="cart-items">
            @if ($orders->isEmpty())
                @hasanyrole('المشرف|صيدلي')
                    @forelse ($cartItems->groupBy('supplier_id') as $supplierId => $supplierItems)
                        <div class="cart-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="item-image me-3">
                                    <i class="bi bi-clipboard-check fs-3"></i>
                                </div>

                                <div class="item-info me-3">
                                    <h3>{{ $supplierItems->first()->supplier->name ?? 'غير معروف' }}</h3>

                                    <span class="badge bg-danger">مسودة</span>
                                </div>
                            </div>

                            <div class="item-actions">
                                <a href="{{ route('details.items', $supplierItems->first()->supplier->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="عرض تفاصيل الطلب">
                                    <i class="bi bi-card-list"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @endhasanyrole
            @else
                @hasanyrole('المشرف|صيدلي')
                    @forelse ($cartItems->groupBy('supplier_id') as $supplierId => $supplierItems)
                        <div class="cart-item d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center">
                                <div class="item-image me-3">
                                    <i class="bi bi-clipboard-check fs-3"></i>
                                </div>

                                <div class="item-info me-3">
                                    <h3>{{ $supplierItems->first()->supplier->name ?? 'غير معروف' }}</h3>

                                    <span class="badge bg-danger">مسودة</span>
                                </div>
                            </div>

                            <div class="item-actions">
                                <a href="{{ route('details.items', $supplierItems->first()->supplier->id) }}"
                                    class="btn btn-sm btn-outline-primary" title="عرض تفاصيل الطلب">
                                    <i class="bi bi-card-list"></i>
                                </a>
                            </div>
                        </div>
                    @empty
                    @endforelse
                @endhasanyrole
                @foreach ($orders as $item)
                    <div class="cart-item d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <div class="item-image me-3">
                                <i class="bi bi-clipboard-check fs-3"></i>
                            </div>

                            <div class="item-info me-3">
                                @hasanyrole('المشرف|مورد')
                                    <h3>{{ $item->pharmacist->name }}</h3>
                                @endhasanyrole

                                @hasanyrole('المشرف|صيدلي')
                                    <h3>{{ $item->supplier->name }}</h3>
                                @endhasanyrole
                                @if ($item->status == 'قيد الانتظار')
                                    <span class="badge bg-primary">قيد الانتظار</span>
                                @elseif ($item->status == 'مرفوض جزئياً')
                                    <span class="badge bg-warning text-dark">مرفوض جزئياً</span>
                                @elseif ($item->status == 'تم التسليم')
                                    <span class="badge bg-success">تم التسليم</span>
                                @elseif ($item->status == 'ملغي')
                                    <span class="badge bg-danger">ملغي</span>
                                @else
                                    <span class="badge bg-info">تم التأكيد</span>
                                @endif

                            </div>
                        </div>

                        <div class="item-actions">
                            <a href="{{ route('details.order', $item->id) }}" class="btn btn-sm btn-outline-primary"
                                title="عرض تفاصيل الطلب">
                                <i class="bi bi-card-list"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>
        <div class="d-flex justify-content-center mt-2 mb-4">
            {{ $orders->links() }}
        </div>
    </section>
@endsection
