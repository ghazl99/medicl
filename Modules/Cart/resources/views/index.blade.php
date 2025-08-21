@extends('pharmacist::components.layouts.master')
@section('css')
<style>

</style>
@endsection
@section('content')
    @php
        $supplierItems = $cartItems->where('supplier_id', $supplier_id);
    @endphp
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title">جميع طلبات المسودة</h2>
            <p class="section-subtitle">مراجعة طلبك من أدوية المورد
                {{ $supplierItems->first()->supplier->name ?? 'غير معروف' }} </p>
        </div>

        <div class="c-items">
            @if ($supplierItems->count())
                @foreach ($supplierItems as $item)
                    <div class="c-item" data-id="{{ $item->id }}">
                        <div class="item-main">
                            <div class="item-image"><i class="bi bi-capsule"></i></div>

                            <div class="item-info">
                                <h4>{{ $item->medicine->type }}</h4>
                                <p class="item-description">{{ $item->supplier->workplace_name }}</p>
                                <span class="item-price" data-price="{{ $item->medicine->net_dollar_new }}">
                                    {{ number_format($item->medicine->net_dollar_new, 2, '.', '') }} $
                                </span>
                            </div>
                        </div>

                        <div class="item-actions-wrapper">
                            <div class="item-actions">
                                <button class="quantity-btn minus">-</button>
                                <span class="quantity">{{ $item->quantity }}</span>
                                <button class="quantity-btn plus">+</button>
                            </div>

                            <button class="remove-item btn btn-danger btn-sm">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                @endforeach
            @else
            @endif
        </div>

        @if (!$cartItems->isEmpty())
            <div class="cart-summary cart-total">
                <span>المجموع الكلي:</span>
                <b id="total-price" style="color: darkred"></b><b style="color: darkred">$</b>
            </div>
            <form action="{{ route('orders.store') }}" method="POST">
                @csrf
                <input type="hidden" name="supplier_id" value="{{ $supplier_id }}">

                <div class="cart-actions mb-2">
                    <button type="submit" class="checkout-btn" <i class="bi bi-check2-circle"></i> تأكيد الطلب
                    </button>
                </div>
            </form>
        @endif
    </section>
@endsection


@section('scripts')
    <script>
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.c-item').forEach(item => {
                const price = parseFloat(item.querySelector('.item-price').dataset.price);
                const quantity = parseInt(item.querySelector('.quantity').textContent);
                total += price * quantity;
            });
            document.getElementById('total-price').textContent = total.toFixed(2);
        }

        // تحديث الكمية + Ajax
        function updateQuantityAjax(id, quantity, itemEl) {
            fetch(`/cart/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        quantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        itemEl.querySelector('.quantity').textContent = quantity;
                        calculateTotal();
                        updateCartBadge(data.cart_count);
                    }
                });
        }

        // تحديث الـ badge
        function updateCartBadge(count) {
            const badge = document.querySelector('.nav-item .badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                } else {
                    badge.remove();
                }
            }
        }

        // زيادة الكمية
        document.querySelectorAll('.plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;
                const qtyEl = itemEl.querySelector('.quantity');
                let quantity = parseInt(qtyEl.textContent) + 1;
                qtyEl.textContent = quantity;
                updateQuantityAjax(id, quantity, itemEl);
            });
        });

        // نقصان الكمية
        document.querySelectorAll('.minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;
                const qtyEl = itemEl.querySelector('.quantity');
                let quantity = parseInt(qtyEl.textContent);
                if (quantity > 1) {
                    quantity -= 1;
                    qtyEl.textContent = quantity;
                    updateQuantityAjax(id, quantity, itemEl);
                }
            });
        });

        // حذف المنتج
        document.querySelectorAll('.remove-item').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;

                Swal.fire({
                    title: 'هل أنت متأكد؟',
                    text: "لن تتمكن من التراجع عن الحذف!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'نعم، احذف!',
                    cancelButtonText: 'إلغاء'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/cart/delete/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    itemEl.remove();
                                    calculateTotal();
                                    updateCartBadge(data.cart_count);
                                    Swal.fire('تم الحذف!', 'تم حذف المنتج من السلة.',
                                        'success');
                                } else {
                                    Swal.fire('خطأ!', 'تعذر حذف المنتج، حاول مجدداً.', 'error');
                                }
                            });
                    }
                });
            });
        });

        // حساب المجموع عند تحميل الصفحة
        calculateTotal();
    </script>
@endsection
