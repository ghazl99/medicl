@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة عرض جديد</h2>
            <form action="{{ route('offers.store') }}" method="POST">
                @csrf
                <input type="hidden" name="medicine_user_id" value="{{ $medicineUser->id }}">

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>كمية الشراء</label>
                        <input type="number" name="offer_buy_quantity" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>الكمية المجانية</label>
                        <input type="number" name="offer_free_quantity" class="form-control" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>تاريخ البداية</label>
                        <input type="date" name="offer_start_date" class="form-control" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label>تاريخ الانتهاء</label>
                        <input type="date" name="offer_end_date" class="form-control" required>
                    </div>
                </div>

                <div class="form-group">
                    <label>ملاحظات (اختياري)</label>
                    <textarea name="notes" class="form-control"></textarea>
                </div>

                <button type="submit" class="btn btn-success">حفظ العرض</button>
            </form>

        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#addMedicine').click(function() {
                let newRow = $('.medicine-row').first().clone();
                newRow.find('input').val('');
                newRow.find('select').val('');
                $('#medicine-container').append(newRow);
            });

            $(document).on('click', '.delete-row', function() {
                if ($('.medicine-row').length > 1) {
                    $(this).closest('.medicine-row').remove();
                }
            });
        });
    </script>
@endsection
