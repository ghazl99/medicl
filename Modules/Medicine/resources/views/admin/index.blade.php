@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        @role('مورد')
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">إدارة الأدوية</h3>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
            </div>

            <div class="card-body">
                <div class="table-responsive">
                    <form id="medicines-selection-form" method="POST" action="{{ route('checked-medicine') }}">
                        @csrf
                        <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                            <thead class="text-right">
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th> {{-- تحديد الكل --}}
                                    <th>الصنف</th>
                                    <th>التركيب</th>
                                    <th>الشكل</th>
                                    <th>الشركة</th>
                                    <th>ملاحظات</th>
                                    <th>نت دولار حالي</th>
                                    <th>عموم دولار حالي</th>
                                    <th>النت دولار الجديد</th>
                                    <th>العموم دولار الجديد</th>
                                    <th>نت سوري</th>
                                    <th>عموم سوري</th>
                                    <th>ملاحظات 2</th>
                                    <th>نسبة تغير السعر</th>
                                </tr>
                            </thead>
                            <tbody id="medicines-table-body">
                                @foreach ($medicines as $k => $medicine)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="medicines[]" value="{{ $medicine->id }}"
                                                @if (in_array($medicine->id, $supplierMedicineIds)) checked @endif>
                                        </td>
                                        <td>{{ $medicine->type }}</td>
                                        <td>{{ $medicine->composition }}</td>
                                        <td>{{ $medicine->form }}</td>
                                        <td>{{ $medicine->company }}</td>
                                        <td>{{ $medicine->note }}</td>
                                        <td>{{ $medicine->net_dollar_old !== null ? number_format($medicine->net_dollar_old, 2) : '-' }}
                                        </td>
                                        <td>{{ $medicine->public_dollar_old !== null ? number_format($medicine->public_dollar_old, 2) : '-' }}
                                        </td>
                                        <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}
                                        </td>
                                        <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}
                                        </td>
                                        <td>{{ $medicine->net_syp !== null ? number_format($medicine->net_syp, 2) : '-' }}</td>
                                        <td>{{ $medicine->public_syp !== null ? number_format($medicine->public_syp, 2) : '-' }}
                                        </td>
                                        <td>{{ $medicine->note_2 }}</td>
                                        <td>
                                            {{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medicines->links() }}
                        </div>

                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-success">إضافة الأدوية المحددة للمورد</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        @else
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">جميع الأدوية</h3>
                @role('المشرف')
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary mr-1">إضافة دواء</a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                            استيراد ملف إكسل
                        </button>
                    </div>
                @endrole
            </div>
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                        <thead class="text-right">
                            <tr>
                                <th>ID</th>
                                <th>الصنف</th>
                                <th>التركيب</th>
                                <th>الشكل</th>
                                <th>الشركة</th>
                                <th>ملاحظات</th>
                                <th>نت دولار حالي</th>
                                <th>عموم دولار حالي</th>
                                <th>النت دولار الجديد</th>
                                <th>العموم دولار الجديد</th>
                                <th>نت سوري</th>
                                <th>عموم سوري</th>
                                <th>ملاحظات 2</th>
                                <th>نسبة تغير السعر</th>
                            </tr>
                        </thead>
                        <tbody id="medicines-table-body">
                            @foreach ($medicines as $k => $medicine)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $medicine->type }}</td>
                                    <td>{{ $medicine->composition }}</td>
                                    <td>{{ $medicine->form }}</td>
                                    <td>{{ $medicine->company }}</td>
                                    <td>{{ $medicine->note }}</td>
                                    <td>{{ $medicine->net_dollar_old !== null ? number_format($medicine->net_dollar_old, 2) : '-' }}
                                    </td>
                                    <td>{{ $medicine->public_dollar_old !== null ? number_format($medicine->public_dollar_old, 2) : '-' }}
                                    </td>
                                    <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}
                                    </td>
                                    <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}
                                    </td>
                                    <td>{{ $medicine->net_syp !== null ? number_format($medicine->net_syp, 2) : '-' }}</td>
                                    <td>{{ $medicine->public_syp !== null ? number_format($medicine->public_syp, 2) : '-' }}
                                    </td>
                                    <td>{{ $medicine->note_2 }}</td>
                                    <td>
                                        {{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $medicines->links() }}
                    </div>

                </div>
            </div>
        @endrole
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('medicines.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">استيراد ملف إكسل</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">اختر ملف الإكسل للاستيراد:</label>
                            <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv"
                                class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#medicines-datatable').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                pageLength: 10,


            });

            // تحديد الكل
            $('#select-all').click(function() {
                $('input[name="medicines[]"]').prop('checked', this.checked);
            });
        });
    </script>
@endsection
