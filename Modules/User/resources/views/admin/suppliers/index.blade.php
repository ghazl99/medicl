@extends('core::components.layouts.master')
@section('css')
    <!--  Owl-carousel css-->
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <!-- Maps css -->
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
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
                        @forelse ($suppliers as $supplier)
                            <tr>
                                <td>{{ $supplier->name }}</td>
                                <td>{{ $supplier->phone }}</td>
                                <td>{{ $supplier->workplace_name }}</td>
                                <td>{{ $supplier->city }}</td>
                                <td>
                                    <a href="{{ route('users.edit', $supplier->id) }}"
                                        class="btn btn-sm btn-outline-primary me-1">تعديل</a>
                                    {{-- Form for delete if needed --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد موردين متاحين.</td>
                            </tr>
                        @endforelse
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
                paging: false,
                searching: true,
                ordering: true,
                info: false,
                pageLength: 10,

            });
        });
    </script>
@endsection
