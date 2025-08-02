@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الصيادلة</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('register.pharmacists') }}" class="btn btn-primary">إضافة صيدلي</a>
            </div>
            <div class="mb-3 text-right">
                <form action="{{ route('pharmacists.index') }}" method="GET" class="mb-3">
                    <div class="row justify-content-start">
                        <div class="col-md-4 col-sm-6 mb-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="ابحث عن الصيدلي..." class="form-control" />
                        </div>
                        <div class="col-auto mb-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="pharmacists-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم الصيدلي</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الصيدلية</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="pharmacists-table-body"> {{-- **Add id to <tbody> here** --}}
                        @forelse ($pharmacists as $pharmacist)
                            <tr>
                                <td>{{ $pharmacist->name }}</td>
                                <td>{{ $pharmacist->phone }}</td>
                                <td>{{ $pharmacist->workplace_name }}</td>
                                <td>{{ $pharmacist->city }}</td>
                                <td>
                                    @if ($pharmacist->is_approved)
                                        <span class="badge bg-success">معتمد</span>
                                    @else
                                        <span class="badge bg-danger">غير معتمد</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('users.edit', $pharmacist->id) }}"
                                        class="btn btn-sm btn-outline-primary me-1">تعديل</a>
                                    {{-- إذا أردت إضافة زر حذف، فك تعليق هذا الجزء واضبط المسار --}}
                                    {{-- <form action="{{ route('pharmacists.destroy', $pharmacist->id) }}" method="POST"
                                        style="display:inline-block;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                            onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                                    </form> --}}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">لا توجد صيادلة متاحون.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $pharmacists->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#pharmacists-datatable').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,

            });
        });
    </script>
@endsection
