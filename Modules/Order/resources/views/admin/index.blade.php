@extends('core::components.layouts.master')

@section('content')
    <br>

    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الطلبات</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                @role('صيدلي')
                    <a href="{{ route('orders.create') }}" class="btn btn-primary">إضافة طلب</a>
                @endrole
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="orders-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>ID</th>
                            @hasanyrole('المشرف|مورد')
                                <th>اسم الصيدلي </th>
                            @endhasanyrole
                            @hasanyrole('المشرف|صيدلي')
                                <th>اسم المورد </th>
                            @endhasanyrole
                            <th>حالة الطلب </th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        @foreach ($orders as $k => $order)
                            <tr>
                                <td>{{ $k + 1 }}</td>
                                @hasanyrole('المشرف|مورد')
                                    <td>{{ $order->pharmacist->name }}</td>
                                @endhasanyrole
                                @hasanyrole('المشرف|صيدلي')
                                    <td>{{ $order->supplier->name }}</td>
                                @endhasanyrole
                                <td style="color: white">
                                    @if ($order->status == 'قيد المعالجة')
                                        <span class="badge bg-primary">قيد المعالجة</span>
                                    @elseif ($order->status == 'قيد التنفيذ')
                                        <span class="badge bg-warning text-dark">قيد التنفيذ</span>
                                    @elseif ($order->status == 'تم التسليم')
                                        <span class="badge bg-success">تم التسليم</span>
                                    @elseif ($order->status == 'ملغي')
                                        <span class="badge bg-danger">ملغي</span>
                                    @else
                                        <span class="badge bg-secondary">{{ $order->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info">
                                        عرض التفاصيل
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $orders->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // DataTable
            $('#orders-datatable').DataTable({
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                pageLength: 10,
            });
        });
    </script>
@endsection
