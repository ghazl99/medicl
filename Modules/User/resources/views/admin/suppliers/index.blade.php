@extends('core::components.layouts.master')
@section('css')
<!--  Owl-carousel css-->
<link href="{{URL::asset('assets/plugins/owl-carousel/owl.carousel.css')}}" rel="stylesheet" />
<!-- Maps css -->
<link href="{{URL::asset('assets/plugins/jqvmap/jqvmap.min.css')}}" rel="stylesheet">
@endsection
@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الموردين</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('register.suppliers') }}" class="btn btn-primary">إضافة مورد</a>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="suppliers-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم المورد</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الشركة</th>
                            <th>المدينة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="suppliers-table-body">
                        @include('user::admin.suppliers._supplier_rows')
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#suppliers-datatable').DataTable({
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
