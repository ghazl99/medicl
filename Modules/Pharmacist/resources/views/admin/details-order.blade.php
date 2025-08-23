@extends('pharmacist::components.layouts.master')

@section('content')
    @php
        $userRole = auth()->user()->getRoleNames()->first();
    @endphp
    <section class="cart-section">
        <div class="cart-header">
            <h2 class="cart-section-title">تفاصيل الطلب #{{ $order->id }}</h2>
            <p class="section-subtitle">مراجعة طلباتك</p>
        </div>
        <div class="cart-items ">
            <div class="card shadow-sm ">
                <div class="card-body mb-10">
                    @role('صيدلي')
                    <p class="mb-1"><strong>المورد:</strong> {{ $order->supplier->name }}</p>
                    @endrole
                    @role('مورد')
                    <p class="mb-1"><strong>الصيدلي/ة:</strong> {{ $order->pharmacist->name }}</p>

                    @endrole
                    {{-- حالة الطلب --}}
                    <p class="mt-2">
                        <strong>حالة الطلب:</strong>
                        @if ($order->status == 'قيد الانتظار')
                            <span class="badge bg-primary">قيد الانتظار</span>
                        @elseif ($order->status == 'مرفوض جزئياً')
                            <span class="badge bg-warning text-dark">مرفوض جزئياً</span>
                        @elseif ($order->status == 'تم التسليم')
                            <span class="badge bg-success">تم التسليم</span>
                        @elseif ($order->status == 'ملغي')
                            <span class="badge bg-danger">ملغي</span>
                        @else
                            <span class="badge bg-info">تم التأكيد</span>
                        @endif
                    </p>

                    {{-- جدول الأدوية --}}
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">الأدوية المطلوبة</h5>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped mb-0 text-center">
                                    <thead class="table-light">
                                        <tr>
                                            <th>اسم الدواء</th>
                                            <th>الكمية</th>
                                            <th>ملاحظة</th>
                                            <th>سعر الوحدة (ل.س)</th>
                                            <th>الإجمالي (ل.س)</th>
                                            @if (
                                                ($order->status == 'قيد الانتظار' && $userRole == 'مورد') ||
                                                    ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي'))
                                                <th>الحالة</th>
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $totalPrice = 0; @endphp
                                        @foreach ($order->medicines as $medicine)
                                            @php
                                                $unitPrice = $medicine->net_syp ?? 0;
                                                $quantity = $medicine->pivot->quantity;
                                                $subtotal = $unitPrice * $quantity;
                                                if ($medicine->pivot->status == 'مقبول') {
                                                    $totalPrice += $subtotal;
                                                }
                                            @endphp
                                            <tr>
                                                <td>{{ $medicine->type }}</td>
                                                <td>
                                                    {{-- إذا الرفض جزئي والصيدلي ممكن يعدل الكمية --}}
                                                    @if ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي' && $medicine->pivot->status == 'مرفوض')
                                                        <input type="number" min="0"
                                                            class="form-control quantity-input"
                                                            data-medicine-id="{{ $medicine->id }}"
                                                            value="{{ $medicine->pivot->quantity }}">
                                                    @else
                                                        {{ $quantity }}
                                                    @endif
                                                </td>
                                                <td>{{ $medicine->pivot->note }}</td>
                                                <td>{{ number_format($unitPrice, 2) }}</td>
                                                <td>{{ number_format($subtotal, 2) }}</td>

                                                @if ($order->status == 'قيد الانتظار' && $userRole == 'مورد')
                                                    <td>
                                                        @if ($medicine->pivot->status != 'مرفوض')
                                                            <button
                                                                class="btn btn-sm btn-outline-danger btn-reject-medicine"
                                                                data-medicine-id="{{ $medicine->id }}"
                                                                data-order-id="{{ $order->id }}">
                                                                رفض مع سبب
                                                            </button>
                                                        @else
                                                            <span class="badge bg-danger">مرفوض</span><br>
                                                            <small>السبب: {{ $medicine->pivot->note }}</small>
                                                        @endif
                                                    </td>
                                                @elseif ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي')
                                                    <td class="medicine-status" data-medicine-id="{{ $medicine->id }}">
                                                        @if ($medicine->pivot->status == 'مرفوض')
                                                            <span class="badge bg-danger">مرفوض</span><br>
                                                            <small>السبب: {{ $medicine->pivot->note }}</small>
                                                        @else
                                                            <span class="badge bg-success">مقبول</span>
                                                        @endif
                                                    </td>
                                                @endif
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- السعر النهائي --}}
                    <div class="text-end mt-3">
                        <h5 class="fw-bold">السعر النهائي:</h5>
                        <h3 style="color:#f43636; font-weight:700;">
                            {{ number_format($totalPrice, 2) }} ل.س
                        </h3>
                    </div>

                    {{-- Status change buttons depending on role and order status --}}
                    <div>
                        @if ($order->status == 'قيد الانتظار' && $userRole == 'مورد')
                            <button class="btn btn-warning change-status-btn" data-order-id="{{ $order->id }}"
                                data-status="تم التأكيد">
                                تأكيد الطلب
                            </button>
                        @elseif ($order->status == 'تم التأكيد' && $userRole == 'مورد')
                            <button class="btn btn-success change-status-btn" data-order-id="{{ $order->id }}"
                                data-status="تم التسليم">
                                تسليم الطلب
                            </button>
                        @elseif ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي')
                            <button class="btn btn-success change-status-btn" data-order-id="{{ $order->id }}"
                                data-status="تم التأكيد">
                                موافق
                            </button>
                            <button class="btn btn-danger change-status-btn" data-order-id="{{ $order->id }}"
                                data-status="ملغي">
                                إلغاء الطلب
                            </button>
                        @elseif ($order->status == 'قيد الانتظار' && $userRole == 'صيدلي')
                            <button class="btn btn-danger change-status-btn" data-order-id="{{ $order->id }}"
                                data-status="ملغي">
                                إلغاء الطلب
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- مودال سبب الرفض -->
    <div class="modal fade" id="rejectMedicineModal" tabindex="-1" aria-labelledby="rejectMedicineModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form id="rejectMedicineForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="rejectMedicineModalLabel">سبب رفض الدواء</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق"></button>
                    </div>
                    <div class="modal-body">
                        <textarea name="note" class="form-control" placeholder="أدخل سبب الرفض..." required></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">إرسال</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // أزرار تغيير الحالة
        $('.change-status-btn').on('click', function() {
            const orderId = $(this).data('order-id');
            const status = $(this).data('status');

            Swal.fire({
                title: 'هل أنت متأكد؟',
                text: `سيتم تغيير حالة الطلب إلى "${status}"`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'نعم، تحديث',
                cancelButtonText: 'إلغاء',
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: `/orders/${orderId}/status`,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            _method: 'PATCH',
                            status: status
                        },
                        success: function() {
                            Swal.fire('تم التحديث!', 'تم تحديث حالة الطلب.', 'success')
                                .then(() => location.reload());
                        },
                        error: function() {
                            Swal.fire('خطأ!', 'فشل في تحديث الطلب.', 'error');
                        }
                    });
                }
            });
        });

        let rejectForm = $('#rejectMedicineForm');

        // فتح المودال لتحديد سبب الرفض
        $('.btn-reject-medicine').click(function() {
            let medicineId = $(this).data('medicine-id');
            let orderId = $(this).data('order-id');

            rejectForm.attr('action', `/orders/${orderId}/reject-medicine/${medicineId}`);
            $('#rejectMedicineModal').modal('show');
        });

        // تحديث السعر الإجمالي مباشرة عند تغيير الكمية
        $('.quantity-input').on('input', function() {
            let input = $(this);
            let newQuantity = parseInt(input.val()) || 0;
            let row = input.closest('tr');

            let unitPriceText = row.find('td').eq(2).text();
            let unitPrice = parseFloat(unitPriceText.replace(/,/g, ''));

            let newSubtotal = unitPrice * newQuantity;
            row.find('td').eq(3).text(newSubtotal.toFixed(2));

            updateTotalPrice();
        });

        // تحديث السعر النهائي
        function updateTotalPrice() {
            let total = 0;
            $('tbody tr').each(function() {
                let subtotalText = $(this).find('td').eq(3).text();
                let subtotal = parseFloat(subtotalText.replace(/,/g, '')) || 0;
                total += subtotal;
            });
            $('.text-end h3').text(total.toFixed(2) + ' ل.س');
        }

        // إرسال التغيير إلى السيرفر عند انتهاء التعديل
        $('.quantity-input').on('change', function() {
            let input = $(this);
            let newQuantity = parseInt(input.val()) || 0;
            let medicineId = input.data('medicine-id');
            let orderId = {{ $order->id }};

            $.ajax({
                url: `/orders/${orderId}/medicines/${medicineId}/update-quantity`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: newQuantity
                },
                success: function() {
                    Swal.fire({
                        icon: 'success',
                        title: 'تم تحديث الكمية وتحويل الحالة إلى مقبول',
                        timer: 1500,
                        showConfirmButton: false,
                    }).then(() => location.reload());
                },
                error: function() {
                    Swal.fire('خطأ!', 'فشل في تحديث الكمية.', 'error');
                }
            });
        });
    </script>
@endsection
