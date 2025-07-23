@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="container">
        <h2>تفاصيل الطلب رقم #{{ $order->id }}</h2>

        <div class="mb-3">
            <strong>الصيدلي:</strong> {{ $order->pharmacist->name }}
        </div>

        <div class="mb-3">
            <strong>المورد:</strong> {{ $order->supplier->name }}
        </div>

        <div class="mb-3">
            <strong>حالة الطلب:</strong> {{ $order->status }}
        </div>

        <h3>الأدوية المطلوبة:</h3>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>اسم الدواء</th>
                    <th>الكمية</th>
                    <th>سعر الوحدة (ل.س)</th>
                    <th>السعر الكلي (ل.س)</th>
                    <th>الحالة</th>
                </tr>
            </thead>
            <tbody>
                @php $totalPrice = 0; @endphp
                @foreach ($order->medicines as $medicine)
                    @php
                        $unitPrice = $medicine->net_syp ?? 0;
                        $quantity = $medicine->pivot->quantity;
                        $subtotal = $unitPrice * $quantity;
                        $totalPrice += $subtotal;
                    @endphp
                    <tr>
                        <td>{{ $medicine->type }}</td>
                        <td>{{ $quantity }}</td>
                        <td>{{ number_format($unitPrice, 2) }}</td>
                        <td>{{ number_format($subtotal, 2) }}</td>
                        <td>
                            @if ($medicine->pivot->status == 'مقبول')
                                <span class="badge bg-success">مقبول</span>
                            @elseif ($medicine->pivot->status == 'مرفوض')
                                <span class="badge bg-danger">مرفوض</span>
                            @else
                                <span class="badge bg-secondary">{{ $medicine->pivot->status }}</span>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-end mt-3">
            <strong>السعر النهائي للطلبية: </strong>
            <span class="badge bg-danger" style="font-size: 1.2em">{{ number_format($totalPrice, 2) }} ل.س</span>
        </div>


    </div>
@endsection
