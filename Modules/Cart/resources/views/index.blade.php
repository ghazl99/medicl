@extends('pharmacist::components.layouts.master')

@section('css')
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: center;
        }

        .quantity-btn {
            padding: 2px 6px;
            cursor: pointer;
            margin: 0 2px;
        }

        .total-price {
            font-weight: bold;
            font-size: 1.2rem;
            text-align: right;
            margin-top: 20px;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h3>سلة المشتريات</h3>

        @if ($cartItems->isEmpty())
            <p>السلة فارغة.</p>
        @else
            <table>
                <thead>
                    <tr>
                        <th>الدواء</th>
                        <th>المورد</th>
                        <th>السعر الفردي ($)</th>
                        <th>الكمية</th>
                        <th>السعر الإجمالي ($)</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- تهيئة متغير لحساب المجموع الكلي --}}
                    @php
                        $grandTotal = 0;
                    @endphp
                <tbody>
                    @foreach ($cartItems as $item)
                        <tr data-id="{{ $item->id }}">
                            <td>{{ $item->medicine->type }}</td>
                            <td>{{ $item->supplier->workplace_name }}</td>
                            <td class="price" data-price="{{ $item->medicine->net_dollar_new }}">
                                {{ number_format($item->medicine->net_dollar_new, 2, '.', '') }}
                            </td>
                            <td>
                                <button type="button" class="quantity-btn decrease">-</button>
                                <input type="number" class="quantity-input" value="{{ $item->quantity }}" min="1"
                                    style="width:50px;">
                                <button type="button" class="quantity-btn increase">+</button>
                            </td>
                            <td class="item-total">
                                {{ number_format($item->medicine->net_dollar_new * $item->quantity, 2, '.', '') }}
                            </td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete-item"
                                    data-id="{{ $item->id }}">حذف</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>

                </tbody>
            </table>

            <div class="total-price">
                المجموع الكلي: $<span id="total-price">{{ number_format($grandTotal, 2, '.', '') }}</span>
            </div>
        @endif
    </div>
@endsection
@section('scripts')
    <script>
        function calculateTotal() {
            let total = 0;
            document.querySelectorAll('tbody tr').forEach(row => {
                const price = parseFloat(row.querySelector('.price').dataset.price);
                const quantity = parseInt(row.querySelector('.quantity-input').value) || 1;
                const itemTotal = price * quantity;
                row.querySelector('.item-total').textContent = itemTotal.toFixed(2);
                total += itemTotal;
            });
            document.getElementById('total-price').textContent = total.toFixed(2);
        }

        // زيادة الكمية
        document.querySelectorAll('.increase').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.quantity-input');
                input.value = parseInt(input.value) + 1;
                updateQuantityAjax(row.dataset.id, input.value, row);
            });
        });

        // نقصان الكمية
        document.querySelectorAll('.decrease').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('tr');
                const input = row.querySelector('.quantity-input');
                if (parseInt(input.value) > 1) {
                    input.value = parseInt(input.value) - 1;
                    updateQuantityAjax(row.dataset.id, input.value, row);
                }
            });
        });

        // تحديث badge السلة في الهيدر
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

        // حذف المنتج
        document.querySelectorAll('.delete-item').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const id = this.dataset.id;
                const row = this.closest('tr');

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
                                    row.remove();
                                    calculateTotal();
                                    updateCartBadge(data.cart_count); // 🔹 تحديث badge

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

        function updateQuantityAjax(id, quantity, row) {
            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    row.querySelector('.item-total').textContent = data.item_total;
                    calculateTotal();
                    updateCartBadge(data.cart_count);
                }
            });
        }

        function updateQuantityAjax(id, quantity, row) {
            fetch(`/cart/update/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    quantity: quantity
                })
            }).then(res => res.json()).then(data => {
                if (data.success) {
                    row.querySelector('.item-total').textContent = data.item_total;
                    calculateTotal();
                }
            });
        }

        // حساب المجموع عند تحميل الصفحة
        calculateTotal();
    </script>
@endsection
