@extends('pharmacist::components.layouts.master')
@section('css')
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

        .no-suppliers {
            color: red;
            font-weight: bold;
        }
    </style>
@endsection
@section('content')
    <div class="container mt-4">
        <h3>{{ $medicine->type }}</h3>
        <p>التركيب: {{ $medicine->composition }}</p>
        <p>السعر :{{ $medicine->net_dollar_new }} $</p>
        <p>عرض (الكمية): {{ $medicine->offer ?? 'لا يوجد عرض' }}</p>
        @php
            $image = $medicine->getFirstMediaUrl('medicine_images') ?: asset('assets/img/medicine.avif');
        @endphp
        <img src="{{ $image }}" width="150" alt="{{ $medicine->type }}" class="mb-3">

        @if ($medicine->suppliers->isEmpty())
            <p class="no-suppliers">لا يتوفر موردين لهذا الدواء</p>
        @else
            <form action="{{ route('cart.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medicine_id" value="{{ $medicine->id }}">
                <input type="hidden" name="price" value="{{ $medicine->net_dollar_new }}">

                <div class="mb-3">
                    <label for="supplier_id">اختر المورد:</label>
                    <select name="supplier_id" id="supplier_id" class="form-control" required>
                        @foreach ($medicine->suppliers as $supplier)
                            <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="quantity">الكمية:</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1"
                        required>
                </div>

                <button type="submit" class="btn btn-success">إضافة للسلة</button>
            </form>
        @endif
    </div>
@endsection
