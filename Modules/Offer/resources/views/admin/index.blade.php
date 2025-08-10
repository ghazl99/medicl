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
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-right">العروض الحالية</h3>
            @role('مورد')
                <a href="{{ route('offers.create') }}" class="btn btn-primary">إضافة عرض</a>
            @endrole
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="offers-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>العرض</th>

                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="suppliers-table-body">
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $offer->title }}</td>
                                <td>
                                    <a href="{{ route('offers.show', $offer->id) }}" class="btn btn-info btn-sm">تفاصيل</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">لا توجد عروض حالياً</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $offers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#offers-datatable').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,

            });
        });
    </script>
@endsection
