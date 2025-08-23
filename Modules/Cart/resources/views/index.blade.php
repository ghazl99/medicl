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

                        <div class="note-section mt-2">
                            @if ($item->note)
                                <span class="note-text-display">{{ $item->note }}</span>
                                <button class="btn btn-link btn-sm add-note-btn" type="button">تعديل الملاحظة</button>
                            @else
                                <button class="btn btn-link btn-sm add-note-btn" type="button">أضف ملاحظة</button>
                            @endif

                            <div class="note-input d-none mt-1">
                                <textarea class="form-control note-text" rows="1" placeholder="اكتب ملاحظتك هنا">{{ $item->note }}</textarea>
                                <button class="btn btn-primary btn-sm save-note-btn mt-1">حفظ الملاحظة</button>
                            </div>
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
        // حساب المجموع الكلي
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('.c-item').forEach(item => {
                const price = parseFloat(item.querySelector('.item-price').dataset.price);
                const quantity = parseInt(item.querySelector('.quantity').textContent);
                total += price * quantity;
            });
            document.getElementById('total-price').textContent = total.toFixed(2);
        }

        // تحديث عنصر السلة عبر AJAX
        function updateCartItem(id, data, itemEl = null) {
            fetch(`/cart/update/${id}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                })
                .then(res => res.json())
                .then(res => {
                    if (res.success) {
                        // تحديث الكمية
                        if (res.quantity !== undefined && itemEl) {
                            itemEl.querySelector('.quantity').textContent = res.quantity;
                        }

                        // تحديث الملاحظة
                        if (res.note !== undefined && itemEl) {
                            let noteDisplay = itemEl.querySelector('.note-text-display');
                            let noteBtn = itemEl.querySelector('.add-note-btn');
                            if (!noteDisplay) {
                                noteDisplay = document.createElement('span');
                                noteDisplay.classList.add('note-text-display');
                                itemEl.querySelector('.note-section').prepend(noteDisplay);
                            }
                            noteDisplay.textContent = res.note;
                            noteDisplay.classList.remove('d-none');
                            noteBtn.textContent = res.note ? 'تعديل الملاحظة' : 'أضف ملاحظة';
                            itemEl.querySelector('.note-input').classList.add('d-none');
                        }

                        // تحديث السعر الكلي
                        calculateTotal();

                        // تحديث عداد السلة إذا موجود
                        if (res.cart_count !== undefined) updateCartBadge(res.cart_count);
                    } else {
                        Swal.fire('خطأ!', res.message || 'حدث خطأ', 'error');
                    }
                });
        }

        // زيادة الكمية
        document.querySelectorAll('.plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;
                let quantity = parseInt(itemEl.querySelector('.quantity').textContent) + 1;
                updateCartItem(id, {
                    quantity
                }, itemEl);
            });
        });

        // نقصان الكمية
        document.querySelectorAll('.minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;
                let quantity = parseInt(itemEl.querySelector('.quantity').textContent);
                if (quantity > 1) {
                    quantity -= 1;
                    updateCartItem(id, {
                        quantity
                    }, itemEl);
                }
            });
        });

        // إظهار حقل الملاحظة
        document.querySelectorAll('.add-note-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const noteSection = this.closest('.note-section').querySelector('.note-input');
                noteSection.classList.toggle('d-none');
            });
        });

        // حفظ الملاحظة
        document.querySelectorAll('.save-note-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const itemEl = this.closest('.c-item');
                const id = itemEl.dataset.id;
                const noteText = itemEl.querySelector('.note-text').value;
                updateCartItem(id, {
                    note: noteText
                }, itemEl);
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
                                    if (data.cart_count !== undefined) updateCartBadge(data
                                        .cart_count);
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
