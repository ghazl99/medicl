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
            <h3 class="text-right">العروض الحالية</h3>
        </div>
        <div class="card-body">

            <div class="mb-3 text-right">
                <form action="{{ route('offers.index') }}" method="GET" class="mb-3">
                    <div class="row justify-content-start">
                        <div class="col-md-4 col-sm-6 mb-2">
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="ابحث عن عرض..." class="form-control" />
                        </div>
                        <div class="col-auto mb-2">
                            <button type="submit" class="btn btn-primary w-100">بحث</button>
                        </div>
                    </div>
                </form>

            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="offers-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>العرض</th>
                            <th>الدواء</th>
                            <th>الكمية</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ الانتهاء</th>
                        </tr>
                    </thead>
                    <tbody id="suppliers-table-body">
                        @forelse($offers as $offer)
                            <tr>
                                <td>{{ $offer->id }}</td> {{-- أو أي حقل معرف للعرض --}}
                                <td>{{ $offer->medicineUser->medicine->type ?? '-' }}</td>
                                <td>شراء {{ $offer->offer_buy_quantity }} + {{ $offer->offer_free_quantity }} مجاناً</td>
                                <td>{{ $offer->offer_start_date->format('Y-m-d') }}</td>
                                <td>{{ $offer->offer_end_date->format('Y-m-d') }}</td>
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
