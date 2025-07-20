@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-right">جميع أدوية مستودعي </h3>
            {{-- زر إرسال --}}
            <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
        </div>
        <div class="card-body">

            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم الدواء</th>
                            <th>الشركة المصنعة</th>
                            <th>الكمية المتوفرة</th>
                            <th>السعر</th>
                            <th>الحالة</th>
                            <th>تاريخ الإضافة</th>
                        </tr>
                    </thead>
                    <tbody id="medicines-table-body">
                        @foreach ($medicines as $medicine)
                            <tr>
                                <td>{{ $medicine->name }}</td>
                                <td>{{ $medicine->manufacturer }}</td>
                                <td>{{ $medicine->quantity_available }}</td>
                                <td>{{ $medicine->price }} $</td>
                                <td>
                                    @if ($medicine->pivot->is_available)
                                        <span class="badge badge-success">متوفر</span>
                                    @else
                                        <span class="badge badge-danger">غير متوفر</span>
                                    @endif
                                </td>
                                <td>{{ $medicine->created_at->format('Y-m-d') }}</td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

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
                    "sSearch": "بحث:",
                    "oPaginate": {
                        "sFirst": "الأول",
                        "sPrevious": "السابق",
                        "sNext": "التالي",
                        "sLast": "الأخير"
                    }
                },
                "dom": '<"row"<"col-sm-12"f>>' +
                    '<"row"<"col-sm-12"tr>>' +
                    '<"row"<"col-sm-6 text-right"i><"col-sm-6 text-left"p>>',
            });

            // تحديد الكل
            $('#select-all').click(function() {
                $('input[name="medicines[]"]').prop('checked', this.checked);
            });
        });
    </script>
@endsection
