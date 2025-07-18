@extends('core::components.layouts.master')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الأدوية</h2>
        <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء جديد</a>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-bordered" id="medicines-datatable">
            <thead>
                <tr>
                    @hasanyrole('المشرف|صيدلي')
                        <th>اسم المورد</th>
                    @endrole
                    <th>اسم الدواء</th>
                    <th>الشركة المصنعة</th>
                    <th>الكمية المتوفرة</th>
                    <th>السعر</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="medicines-table-body"> {{-- **تأكد أن الـ ID هنا** --}}
                @include('medicine::admin._medicines_rows')
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#medicines-datatable').DataTable({
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
