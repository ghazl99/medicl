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
            <h2 class="cart-section-title"> {{ $medicine->type }}</h2>
            <p class="section-subtitle">{{ $medicine->composition }}</p>
            <h3 class="text-white">{{ $medicine->net_dollar_new }} $</h3>
        </div>

        <div class="cart-items">
            @php
                $image = $medicine->getFirstMediaUrl('medicine_images') ?: asset('assets/img/medicine.avif');
            @endphp
            <img src="{{ $image }}" width="150" alt="{{ $medicine->type }}" class="mb-3">

            @if ($medicine->suppliers->isEmpty())
                <p class="no-suppliers">لا يتوفر موردين لهذا الدواء</p>
            @else
                @foreach ($medicine->suppliers as $supplier)
                    <div class="c-item" data-supplier-id="{{ $supplier->id }}">
                        <div class="item-main">
                            <div class="item-info">
                                <p>{{ $supplier->name }}</p>
                                <span class="item-price">{{ $supplier->pivot->offer ?? '' }}</span>
                            </div>

                            <div class="item-actions">
                                <input type="number" name="quantity" min="1" value="1"
                                    class="form-control form-control-sm">
                            </div>

                            <button type="button" class="btn btn-success btn-sm add-to-cart-mini">
                                ✔
                            </button>
                        </div>
                        <div class="item-actions-wrapper">
                            <h6>{{ $supplier->pivot->notes ?? '' }}</h6>
                        </div>

                    </div>
                @endforeach
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
