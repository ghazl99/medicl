@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">أدوية الصنف: {{ $subcategory->name }}</h3>

        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="orders-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>النوع</th>
                            <th>صورة المنتج</th>
                            <th>التركيب</th>
                            <th>الشكل</th>
                            <th>الشركة</th>
                            <th>ملاحظات</th>
                            <th>وصف الدواء</th>
                            <th>النت </th>
                            <th>العموم </th>
                        </tr>
                    </thead>
                    <tbody id="orders-table-body">
                        @forelse ($subcategory->medicines as $k => $medicine)
                            <tr>
                                <td>{{ $medicine->type }}</td>
                                <td>
                                    @php
                                        $media = $medicine->getFirstMedia('medicine_images');
                                    @endphp
                                    @if ($media)
                                        <img src="{{ route('medicines.image', $media->id) }}" class="myImg"
                                            alt="صورة الدواء"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor:pointer;">
                                    @else
                                        <span>لا توجد صورة</span>
                                    @endif
                                </td>
                                <td>{{ $medicine->composition }}</td>
                                <td>{{ $medicine->form }}</td>
                                <td>{{ $medicine->company }}</td>
                                <td>{{ $medicine->note }}</td>
                                <td>{{ $medicine->description }}</td>
                                <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}
                                </td>
                                <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
