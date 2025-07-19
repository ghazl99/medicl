
@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الطلبات</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('orders.create') }}" class="btn btn-primary">إضافة طلب</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="orders-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>ID</th>
                            <th>اسم الصيدلي </th>
                            <th>اسم المورد </th>
                            <th>حالة الطلب </th>
                            <th>الأدوية</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
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
                </table>
            </div>
        </div>
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
