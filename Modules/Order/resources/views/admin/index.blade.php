@extends('core::components.layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الطلبات</h2>
        <a href="{{ route('orders.create') }}" class="btn btn-primary">إضافة طلب جديد</a>
    </div>
    <div class="table-responsive">

        <table class="table table-striped table-bordered" id="orders-datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>اسم الصيدلي </th>
                    <th>اسم المورد </th>
                    <th>حالة الطلب </th>
                    <th>الأدوية</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($orders as $order)
                    <tr>
                        <td>{{ $order->id }}</td>
                        <td>{{ $order->pharmacist->name }}</td>
                        <td>{{ $order->supplier->name }}</td>
                        <td>{{ $order->status }}</td>
                        <td>
                            <ul>
                                @foreach ($order->medicines as $medicine)
                                    <li>{{ $medicine->name }} (x{{ $medicine->pivot->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
@section('scripts')
 <script>
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
                    "sInfoPostFix": "",
                    "sSearch": "بحث:",
                    "sUrl": "",
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
                // حافظ على هذا الـ DOM ليأخذ حقل البحث كامل عرض الـ col-md-12
                "dom": '<"row"<"col-sm-12 col-md-12"f>><"row"<"col-sm-12"tr>><"row"<"col-sm-12 col-md-5 text-md-end"p><"col-sm-12 col-md-7 text-md-start"i>>',
            });
        });
    </script>
@endsection
