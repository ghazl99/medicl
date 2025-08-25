@extends('pharmacist::components.layouts.master')
@section('css')
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

        .no-suppliers {
            color: red;
            font-weight: bold;
        }
    </style>
@endsection

@section('content')
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title"> {{ $medicine->type }}( {{ $medicine->type_ar }} )</h2>
            <p class="section-subtitle">{{ $medicine->composition }}</p>
        </div>

        <div class="cart-items">
            @php
                $image = $medicine->getFirstMediaUrl('medicine_images') ?: asset('assets/img/medicine.avif');
            @endphp
            <img src="{{ $image }}" width="150" alt="{{ $medicine->type }}" class="mb-3">

            @if ($medicine->suppliers->isEmpty())
                <p class="no-suppliers">لا يتوفر موردين لهذا الدواء</p>
            @else
                @forelse ($medicine->suppliers->where('pivot.is_available', 1) as $supplier)
                    <div class="c-item" data-supplier-id="{{ $supplier->id }}">
                        <!-- السطر الأول: الاسم والسعر -->
                        <div class="item-main d-flex justify-content-between align-items-center mb-1">
                            <p class="mb-0">{{ $supplier->name }}</p>
                            <span class="item-price mb-0">{{ number_format($supplier->pivot->price, 2) ?? '' }} $</span>
                        </div>

                        <!-- السطر الثاني: الملاحظة -->
                        @if ($supplier->pivot->notes)
                            <div class="item-actions-wrapper mb-1">
                                <h6 class="mb-0">{{ $supplier->pivot->notes }}</h6>
                            </div>
                        @endif

                        <!-- السطر الثالث: العرض، حقل الكمية، زر الإضافة -->
                        <div class="item-main d-flex justify-content-between align-items-center">
                            <div class="d-flex align-items-center gap-2 flex-grow-1">
                                <!-- العرض إذا وجد -->
                                @if ($supplier->pivot->offer_qty && $supplier->pivot->offer_free_qty)
                                    <span class="item-price">
                                        {{ $supplier->pivot->offer_qty }} + {{ $supplier->pivot->offer_free_qty }}
                                    </span>
                                @else
                                    <span class="item-price">&nbsp;</span> <!-- مكان فاضي إذا ما في عرض -->
                                @endif

                                <!-- حقل الكمية -->
                                @role('صيدلي')
                                    <input type="number" name="quantity" min="1" value="1"
                                        class="form-control form-control-sm">
                                @endrole
                            </div>

                            <!-- زر الإضافة دايمًا بالنهاية -->
                            @role('صيدلي')
                                <button type="button" class="btn btn-success btn-sm add-to-cart-mini">✔</button>
                            @endrole
                        </div>
                    </div>
                @empty
                    <p class="no-suppliers">الدوا غير متوفر</p>
                @endforelse
            @endif
        </div>
    </section>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const buttons = document.querySelectorAll('.add-to-cart-mini');

            buttons.forEach(button => {
                button.addEventListener('click', function() {
                    const cartItem = button.closest('.c-item');
                    const supplierId = cartItem.dataset.supplierId;
                    const quantity = cartItem.querySelector('input[name="quantity"]').value;
                    const medicineId = {{ $medicine->id }};
                    const price = {{ $medicine->net_dollar_new }};

                    fetch('{{ route('cart.store') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                medicine_id: medicineId,
                                supplier_id: supplierId,
                                quantity: quantity,
                                price: price
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'تمت الإضافة!',
                                    text: data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'خطأ',
                                    text: data.message ||
                                        'حدث خطأ، يرجى المحاولة مرة أخرى.',
                                    timer: 2000,
                                    showConfirmButton: false
                                });
                            }
                        })
                        .catch(err => {
                            console.error(err);
                            Swal.fire({
                                icon: 'error',
                                title: 'خطأ',
                                text: 'حدث خطأ في الاتصال بالخادم.',
                                timer: 2000,
                                showConfirmButton: false
                            });
                        });
                });
            });
        });
    </script>
@endsection
