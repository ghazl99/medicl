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
            <div class="mb-3 text-right">
                    <form action="{{ route('suppliers.index') }}" method="GET" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                            <div class="col-auto mb-2">
                                <button type="submit" class="btn btn-primary w-100">بحث</button>
                            </div>
                        </div>
                    </form>

                </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="suppliers-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم المورد</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الشركة</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
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
                                    @if ($supplier->is_approved)
                                        <span class="badge bg-success">معتمد</span>
                                    @else
                                        <span class="badge bg-danger">غير معتمد</span>
                                    @endif
                                </td>
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
                <div class="d-flex justify-content-center mt-4">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#suppliers-datatable').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,

            });
        });
    </script>
@endsection
