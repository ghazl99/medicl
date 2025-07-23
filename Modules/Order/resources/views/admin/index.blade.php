@extends('core::components.layouts.master')

@section('content')
    <br>
    @php
        $userRole = auth()->user()->getRoleNames()->first(); // Assuming one role per user
    @endphp

    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الطلبات</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                @role('صيدلي')
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">إضافة طلب</a>
                @endrole
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="orders-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>ID</th>
                            <th>اسم الصيدلي </th>
                            <th>اسم المورد </th>
                            <th>حالة الطلب </th>
                            <th>الإجراءات</th>

                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        @foreach ($orders as $k => $order)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                <td>{{ $order->pharmacist->name }}</td>
                                <td>{{ $order->supplier->name }}</td>
                                <td style="color: white">
                                    @if ($order->status == 'قيد المعالجة')
                                        <span class="badge bg-primary">قيد المعالجة</span>
                                    @elseif ($order->status == 'قيد التنفيذ')
                                        <span class="badge bg-warning text-dark">قيد التنفيذ</span>
                                    @elseif ($order->status == 'تم التسليم')
                                        <span class="badge bg-success">تم التسليم</span>
                                    @elseif ($order->status == 'ملغي')
                                        <span class="badge bg-danger">ملغي</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        عرض التفاصيل
                                    </a>

                                    {{-- إذا كان الطلب قيد المعالجة --}}
                                    @if ($order->status == 'قيد المعالجة')
                                        @if ($userRole == 'صيدلي')
                                            <button class="btn btn-sm btn-danger change-status-btn"
                                                data-order-id="{{ $order->id }}" data-status="ملغي">
                                                إلغاء الطلب
                                            </button>
                                        @elseif ($userRole == 'مورد')
                                            <button class="btn btn-sm btn-warning change-status-btn"
                                                data-order-id="{{ $order->id }}" data-status="قيد التنفيذ">
                                                قيد التنفيذ
                                            </button>
                                        @endif
                                    @elseif ($order->status == 'قيد التنفيذ' && $userRole == 'مورد')
                                        <button class="btn btn-sm btn-success change-status-btn"
                                            data-order-id="{{ $order->id }}" data-status="تم التسليم">
                                            تم التسليم
                                        </button>
                                    @else
                                        <button class="btn btn-sm btn-secondary">
                                            لا يوجد تغيير حالة
                                        </button>
                                    @endif
                                </td>

                            </tr>
                        @endforeach
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // عند فتح مودال تحديث الحالة
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
                                Swal.fire('تم التحديث!', 'تم تحديث حالة الطلب.',
                                    'success');
                                $('#orders-table-body').load(location.href +
                                    ' #orders-table-body > *');

                            },
                            error: function() {
                                Swal.fire('خطأ!', 'فشل في تحديث الطلب.', 'error');
                            }
                        });
                    }
                });
            });


        });
        $(document).ready(function() {
            $('#orders-datatable').DataTable({

                paging: true,
                searching: true,
                ordering: true,
                info: true,
                pageLength: 10,
                language: {
                    "sProcessing": "جاري التحميل...",
                    "sZeroRecords": "لم يتم العثور على أية سجلات مطابقة",
                    "sInfo": "عرض _START_ إلى _END_ من _TOTAL_ سجل",
                    "sInfoEmpty": "عرض 0 إلى 0 من 0 سجل",
                    "sInfoFiltered": "(تمت تصفية _MAX_ سجل)",
                    "sSearch": "بحث:",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    },
                    "oAria": {
                        "sSortAscending": ": تفعيل لترتيب العمود تصاعدياً",
                        "sSortDescending": ": تفعيل لترتيب العمود تنازلياً"
                    }
                },
                "dom": '<"row"<"col-sm-12"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-6 text-right"i><"col-sm-6 text-left"p>>',
            });
        });
    </script>
@endsection
