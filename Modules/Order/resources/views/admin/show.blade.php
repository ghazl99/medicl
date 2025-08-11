@extends('core::components.layouts.master')

@section('content')
    <br>
    @php
        $userRole = auth()->user()->getRoleNames()->first();
    @endphp
    <div class="card">
        <div class="card-body">
            <h2>تفاصيل الطلب رقم #{{ $order->id }}</h2>

            <div class="mb-3">
                <strong>الصيدلي:</strong> {{ $order->pharmacist->name }}
            </div>

            <div class="mb-3">
                <strong>المورد:</strong> {{ $order->supplier->name }}
            </div>

            <div class="mb-3">
                <strong>حالة الطلب:</strong>
                {{-- Display order status with badges --}}
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
            </div>

            <div class="d-flex justify-content-between align-items-center mb-3">
                <h3>الأدوية المطلوبة:</h3>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="orders-datatable" dir="rtl">
                    <thead>
                        <tr>
                            <th>اسم الدواء</th>
                            <th>الكمية</th>
                            <th>سعر الوحدة (ل.س)</th>
                            <th>السعر الكلي (ل.س)</th>
                            {{-- Show status column only if conditions match --}}
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
                                // Sum total price only if medicine is accepted
                                if ($medicine->pivot->status == 'مقبول') {
                                    $totalPrice += $subtotal;
                                }
                            @endphp
                            <tr>
                                <td>{{ $medicine->type }}</td>
                                <td>
                                    {{-- Editable quantity input if partially rejected and pharmacist role --}}
                                    @if ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي' && $medicine->pivot->status == 'مرفوض')
                                        <input type="number" min="0" class="form-control quantity-input"
                                            data-medicine-id="{{ $medicine->id }}" value="{{ $medicine->pivot->quantity }}">
                                    @else
                                        {{ $quantity }}
                                    @endif
                                </td>

                                <td>{{ number_format($unitPrice, 2) }}</td>
                                <td>{{ number_format($subtotal, 2) }}</td>

                                {{-- Status column for supplier when order waiting --}}
                                @if ($order->status == 'قيد الانتظار' && $userRole == 'مورد')
                                    <td>
                                        @if ($medicine->pivot->status != 'مرفوض')
                                            <button class="btn btn-sm btn-outline-danger btn-reject-medicine"
                                                data-medicine-id="{{ $medicine->id }}" data-order-id="{{ $order->id }}">
                                                رفض مع سبب
                                            </button>
                                        @else
                                            <span class="badge bg-danger">مرفوض</span><br>
                                            <small>السبب: {{ $medicine->pivot->note }}</small>
                                        @endif
                                    </td>
                                    {{-- Status display for pharmacist in partially rejected order --}}
                                @elseif ($order->status == 'مرفوض جزئياً' && $userRole == 'صيدلي')
                                    <td class="medicine-status" data-medicine-id="{{ $medicine->id }}">
                                        @if ($medicine->pivot->status == 'مرفوض')
                                            <span class="badge bg-danger">مرفوض</span><br>
                                            <small>السبب: {{ $medicine->pivot->note }}</small>
                                        @else
                                            <span class="badge bg-success">مقبول</span><br>
                                            <small>{{ $medicine->pivot->note }}</small>
                                        @endif
                                    </td>
                                @else
                                    <td></td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <strong>السعر النهائي للطلبية: </strong>
                {{-- Total price styled with dark red and bold --}}
                <h3 style="color: #8B0000; font-weight: 700;">{{ number_format($totalPrice, 2) }} ل.س</h3>
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
                @endif
            </div>
        </div>
    </div>
    <!-- Reject reason modal -->
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Modal event to set update status form action dynamically
        $('#updateStatusModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const orderId = button.data('order-id');
            const status = button.data('order-status');

            $('#orderStatusSelect').val(status);
            $('#updateStatusForm').attr('action', `/orders/${orderId}/status`);
        });

        // Submit main update status form via AJAX
        $('#updateStatusForm').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const actionUrl = form.attr('action');
            const formData = form.serialize();

            $.ajax({
                url: actionUrl,
                method: 'POST',
                data: formData,
                success: function() {
                    $('#updateStatusModal').modal('hide');
                    reloadOrdersTable();
                },
                error: function() {
                    alert('فشل في تحديث الحالة');
                }
            });
        });

        // Change status buttons with confirmation dialog using SweetAlert2
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
                            Swal.fire('تم التحديث!', 'تم تحديث حالة الطلب.', 'success').then(
                                () => {
                                    location.reload();
                                });
                        },
                        error: function() {
                            Swal.fire('خطأ!', 'فشل في تحديث الطلب.', 'error');
                        }
                    });
                }
            });
        });

        let rejectForm = $('#rejectMedicineForm');

        // Open reject reason modal and set form action dynamically
        $('.btn-reject-medicine').click(function() {
            let medicineId = $(this).data('medicine-id');
            let orderId = $(this).data('order-id');

            rejectForm.attr('action', `/orders/${orderId}/reject-medicine/${medicineId}`);

            $('#rejectMedicineModal').modal('show');
        });

        // Update subtotal and total price instantly on quantity input change
        $('.quantity-input').on('input', function() {
            let input = $(this);
            let newQuantity = parseInt(input.val()) || 0;
            let row = input.closest('tr');

            // Get unit price from third column (index 2)
            let unitPriceText = row.find('td').eq(2).text();
            let unitPrice = parseFloat(unitPriceText.replace(/,/g, ''));

            // Calculate new subtotal and update fourth column (index 3)
            let newSubtotal = unitPrice * newQuantity;
            row.find('td').eq(3).text(newSubtotal.toFixed(2));

            // Update total price at bottom
            updateTotalPrice();
        });

        // Function to update total price from all subtotal columns
        function updateTotalPrice() {
            let total = 0;
            $('tbody tr').each(function() {
                let row = $(this);
                let subtotalText = row.find('td').eq(3).text();
                let subtotal = parseFloat(subtotalText.replace(/,/g, '')) || 0;
                total += subtotal;
            });
            // Update total price display
            $('.text-end h3').text(total.toFixed(2) + ' ل.س');
        }

        // On quantity input change (after user finishes typing), send AJAX to update DB
        $('.quantity-input').on('change', function() {
            let input = $(this);
            let newQuantity = parseInt(input.val()) || 0;
            let medicineId = input.data('medicine-id');
            let orderId = {{ $order->id }};

            let row = input.closest('tr');

            $.ajax({
                url: `/orders/${orderId}/medicines/${medicineId}/update-quantity`,
                method: 'PATCH',
                data: {
                    _token: '{{ csrf_token() }}',
                    quantity: newQuantity
                },
                success: function(response) {
                    // Update the status cell to "accepted"
                    let statusCell = $('.medicine-status[data-medicine-id="' + medicineId + '"]');
                    statusCell.html(
                        '<span class="badge bg-success">مقبول</span><br><small>' +
                        (statusCell.find('small').text() || '') +
                        '</small>'
                    );

                    Swal.fire({
                        icon: 'success',
                        title: 'تم تحديث الكمية وتحويل الحالة إلى مقبول',
                        timer: 1500,
                        showConfirmButton: false,
                    });
                },
                error: function() {
                    Swal.fire('خطأ!', 'فشل في تحديث الكمية.', 'error');
                }
            });
        });
    </script>
@endsection
