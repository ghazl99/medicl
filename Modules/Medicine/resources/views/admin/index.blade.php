@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        @role('مورد')
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">إدارة الأدوية</h3>
                
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <form id="medicines-selection-form" method="POST" action="{{ route('checked-medicine') }}">
                        @csrf
                        <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                            <thead class="text-right">
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th> {{-- تحديد الكل --}}

                                    <th>اسم الدواء</th>
                                    <th>الشركة المصنعة</th>
                                    <th>الكمية المتوفرة</th>
                                    <th>السعر</th>
                                    <th>تاريخ الإضافة</th>
                                </tr>
                            </thead>
                            <tbody id="medicines-table-body">
                                @foreach ($medicines as $medicine)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="medicines[]" value="{{ $medicine->id }}"
                                                @if (in_array($medicine->id, $supplierMedicineIds)) checked @endif>
                                        </td>

                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ $medicine->manufacturer }}</td>
                                        <td>{{ $medicine->quantity_available }}</td>
                                        <td>{{ $medicine->price }} $</td>
                                        <td>{{ $medicine->created_at->format('Y-m-d') }}</td>

                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-success">إضافة الأدوية المحددة للمورد</button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">جميع الأدوية</h3>
                @role('المشرف')
                 <a href="{{  route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
                @endrole
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                        <thead class="text-right">
                            <tr>
                                <th>اسم المورد</th>
                                <th>اسم الدواء</th>
                                <th>الشركة المصنعة</th>
                                <th>الكمية المتوفرة</th>
                                <th>السعر</th>
                                <th>تاريخ الإضافة</th>
                                @role('المشرف')
                                    <th>إجراءات</th>
                                @endrole
                            </tr>
                        </thead>
                        <tbody id="medicines-table-body">
                            @foreach ($medicines as $medicine)
                                <tr>
                                    <td>{{ $medicine->suppliers->pluck('name')->join(', ') }}</td>
                                    <td>{{ $medicine->name }}</td>
                                    <td>{{ $medicine->manufacturer }}</td>
                                    <td>{{ $medicine->quantity_available }}</td>
                                    <td>{{ $medicine->price }} $</td>
                                    <td>{{ $medicine->created_at->format('Y-m-d') }}</td>
                                    @role('المشرف')
                                        <td>
                                            <a href="{{ route('medicines.edit', $medicine->id) }}"
                                                class="btn btn-sm btn-outline-primary">تعديل</a>

                                        </td>
                                    @endrole
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endrole
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
