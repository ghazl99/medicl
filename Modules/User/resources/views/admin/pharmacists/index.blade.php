@extends('core::components.layouts.master')

@section('content')
    <div class="card">

        <div class="card-header">
            <h3>إدارة الصيادلة</h3>
        </div>
        <div class="card-body">

            <div class="d-flex justify-content-end align-items-end mb-4">
                <a href="{{ route('register.pharmacists') }}" class="btn btn-primary">إضافة صيدلي</a>
            </div>
            <table class="table table-striped table-bordered" id="pharmacists-datatable">
                <thead>
                    <tr>
                        <th>اسم الصيدلي</th>
                        <th>رقم الهاتف</th>
                        <th>اسم الصيدلية</th>
                        <th>المدينة</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody id="suppliers-table-body"> {{-- **Add id to <tbody> here** --}}
                    @include('user::admin.pharmacists._pharmacist_rows')
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#pharmacists-datatable').DataTable({
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
