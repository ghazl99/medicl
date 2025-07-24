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
                            <th>التوفر</th>
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
                                <td>{{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}
                                </td>
                               <td class="text-center">
    <div class="mb-2">
        @if ($medicine->pivot->is_available ?? false)
            <span class="badge bg-primary">متوفر</span>
        @else
            <span class="badge bg-danger">غير متوفر</span>
        @endif
    </div>

    <form action="{{ route('medicines.toggle-availability', $medicine->id) }}" method="POST" style="display:inline-block;">
        @csrf
        @if ($medicine->pivot->is_available ?? false)
            <button type="submit" class="addMore btn btn-sm btn-outline-danger" title="تعطيل دواء"><i class="bi bi-x-circle"></i></button>
        @else
            <button type="submit" class="addMore btn btn-sm btn-outline-primary" title="تفعيل دواء"><i class="bi bi-check-circle"></i></button>
        @endif
    </form>
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
