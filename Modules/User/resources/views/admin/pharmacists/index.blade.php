@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الصيادلة</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-start align-items-start mb-4">
                <a href="{{ route('register.pharmacists') }}" class="btn btn-primary">إضافة صيدلي</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="pharmacists-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم الصيدلي</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الصيدلية</th>
                            <th>المدينة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="pharmacists-table-body"> {{-- **Add id to <tbody> here** --}}
                        @include('user::admin.pharmacists._pharmacist_rows')
                    </tbody>
                </table>
            </div>
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
