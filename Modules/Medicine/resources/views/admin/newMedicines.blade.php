{{-- resources/views/medicines/new.blade.php --}}
@extends('core::components.layouts.master')

@section('content')
    <br>

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-right">الأدوية الجديدة</h3>
        </div>

        @if ($medicines->count())
            <div class="card-body">

                <div class="table-responsive">
                    <table class="table table-bordered text-center">
                        <thead>
                            <tr>
                                <th>الاسم</th>
                                <th>الصنف</th>
                                <th>من تاريخ</th>
                                <th>إلى تاريخ</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($medicines as $medicine)
                                <tr>
                                    <td>{{ $medicine->type }}</td>
                                    <td>{{ $medicine->category->name ?? '-' }}</td>
                                    <td>{{ $medicine->new_start_date }}</td>
                                    <td>{{ $medicine->new_end_date }}</td>
                                    <td>
                                        <span class="badge bg-success">جديد</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{ $medicines->links() }} {{-- pagination --}}
            </div>
            @else
                <p class="text-muted">لا توجد أدوية جديدة حالياً.</p>
        @endif
    </div>
@endsection
