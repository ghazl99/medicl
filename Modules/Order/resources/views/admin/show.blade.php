@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="container">
        <h2>تفاصيل الطلب رقم #{{ $order->id }}</h2>

        <div class="mb-3">
            <strong>الصيدلي:</strong> {{ $order->pharmacist->name }}
        </div>

        <div class="mb-3">
            <strong>المورد:</strong> {{ $order->supplier->name }}
        </div>

        <div class="mb-3">
            <strong>حالة الطلب:</strong>
            <span class="badge bg-info" id="order-status-badge">{{ $order->status }}</span>
        </div>

        <div class="d-flex justify-content-between align-items-center mb-3">
            <h3>الأدوية المطلوبة:</h3>

            {{-- زر تغيير الحالة على اليسار --}}
            <div>
                @php
                    $userRole = auth()->user()->getRoleNames()->first();
                @endphp

                @if ($order->status == 'قيد المعالجة')
                    @if ($userRole == 'صيدلي')
                        <button class="btn  btn-danger change-status-btn" data-order-id="{{ $order->id }}"
                            data-status="ملغي">
                            إلغاء الطلب
                        </button>
                    @elseif ($userRole == 'مورد')
                        <button class="btn  btn-warning change-status-btn" data-order-id="{{ $order->id }}"
                            data-status="قيد التنفيذ">
                            قيد التنفيذ
                        </button>
                    @endif
                @elseif ($order->status == 'قيد التنفيذ' && $userRole == 'مورد')
                    <button class="btn  btn-success change-status-btn" data-order-id="{{ $order->id }}"
                        data-status="تم التسليم">
                        تم التسليم
                    </button>
                @endif
            </div>
        </div>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>اسم الدواء</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة (ل.س)</th>
                    <th>السعر الكلي (ل.س)</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp
                @foreach ($order->medicines as $medicine)
                    @php
                        $unitPrice = $medicine->net_syp ?? 0;
                        $quantity = $medicine->pivot->quantity;
                        $subtotal = $unitPrice * $quantity;

                        // اجمع فقط الأدوية المقبولة
                        if ($medicine->pivot->status == 'مقبول') {
                            $totalPrice += $subtotal;
                        }
                    @endphp
                    <tr>
                        <td>{{ $medicine->type }}</td>
                        <td>{{ $quantity }}</td>
                        <td>{{ number_format($unitPrice, 2) }}</td>
                        <td>{{ number_format($subtotal, 2) }}</td>
                        <td>
                            @if ($medicine->pivot->status != 'مرفوض')
                                <form action="{{ route('orders.reject-medicine', [$order->id, $medicine->id]) }}"
                                    method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="رفض الدواء">
                                        <i class="bi bi-x-circle"></i> رفض
                                    </button>
                                </form>
                            @else
                                <span class="badge bg-danger">مرفوض</span>
                            @endif
                        </td>

                    </tr>
                @endforeach

            </tbody>
        </table>

        <div class="text-end mt-3">
            <strong>السعر النهائي للطلبية: </strong>
            <span class="badge bg-danger" style="font-size: 1.2em">{{ number_format($totalPrice, 2) }} ل.س</span>
        </div>
    </div>
@endsection


@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $('#updateStatusModal').on('show.bs.modal', function(event) {
            const button = $(event.relatedTarget);
            const orderId = button.data('order-id');
            const status = button.data('order-status');

            $('#orderStatusSelect').val(status);
            $('#updateStatusForm').attr('action', `/orders/${orderId}/status`);
        });

        // إرسال النموذج الرئيسي عبر AJAX
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

        // أزرار التحديث المباشر بالحالة مع swal.fire
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
    </script>
@endsection
