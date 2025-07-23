@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة طلب جديد</h2>

            <div class="text-start mb-4">
                <h3 style="color: red">السعر النهائي: <span id="final-total">0</span> $</h3>
            </div>

            <form action="{{ route('orders.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label for="supplier_id">المورد:</label>
                        <select name="supplier_id" class="form-control" required>
                            <option value="">-- اختر المورد --</option>
                            @foreach ($suppliers as $supplier)
                                <option value="{{ $supplier->id }}"
                                    {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                    {{ $supplier->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('supplier_id')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-5 mb-3">
                        <label>الدواء:</label>
                        <select name="medicines[]" class="form-control medicine-select" required>
                            <option value="">-- اختر الدواء --</option>

                        </select>
                        @error('medicines.0')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>الكمية:</label>
                        <input type="number" name="quantities[]" class="form-control quantity" min="1"
                            value="{{ old('quantities.0', 1) }}" required>
                        @error('quantities.0')
                            <small class="text-danger d-block">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="col-md-2 mb-3 total-price">
                        <label>السعر الكلي:</label>
                        <input type="text" class="form-control total" value="0" readonly>
                    </div>
                </div>

                <div id="new"></div>

                <button type="button" id="addMedicine" class="btn btn-secondary">إضافة دواء آخر</button>
                <button type="submit" class="btn btn-success">إرسال الطلب</button>
            </form>

            <div id="medicines" hidden>
                <div class="row align-items-end">
                    <div class="col-md-5 mb-3">
                        <label>الدواء:</label>
                        <select name="medicines[]" class="form-control medicine-select" required>
                            <option value="">-- اختر الدواء --</option>

                        </select>
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>الكمية:</label>
                        <input type="number" name="quantities[]" class="form-control quantity" min="1"
                            value="1" required>
                    </div>

                    <div class="col-md-2 mb-3 total-price">
                        <label>السعر الكلي:</label>
                        <input type="text" class="form-control total" value="0" readonly>
                    </div>

                    <div class="col-md-2 mb-3">
                        <button type="button" class="btn btn-outline-danger delete mt-4">
                            <i class="fas fa-times"></i> حذف
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
        @endsection

        @section('scripts')
            <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

            <script>
                $(document).ready(function() {

                    // عند الضغط على زر "إضافة دواء آخر" يضيف صف جديد لصنف دواء
                    $('#addMedicine').click(function() {
                        let newRow = $('#medicines').children().clone(); // نسخ النموذج المخفي
                        $('#new').append(newRow); // إضافته للصفحات الجديدة
                        let addedRow = $('#new').children().last();
                        calculateTotal(addedRow); // حساب السعر الكلي للصف الجديد
                        updateFinalTotal(); // تحديث السعر النهائي
                    });

                    // حذف صف الدواء عند الضغط على زر الحذف داخل الصفوف المضافة
                    $('#new').on('click', 'button.delete', function() {
                        $(this).closest('.row').remove(); // إزالة الصف الحالي
                        updateFinalTotal(); // تحديث السعر النهائي
                    });

                    // حساب السعر الكلي لصف واحد (الكمية × السعر)
                    function calculateTotal(row) {
                        let quantity = parseInt(row.find('.quantity').val()) || 0;
                        let price = parseFloat(row.find('.medicine-select option:selected').data('price')) || 0;
                        let total = quantity * price;
                        row.find('.total').val(total.toFixed(2)); // عرض السعر الكلي بصيغة رقم عشري
                    }

                    // حساب السعر النهائي لجميع الصفوف المعروضة في الفورم
                    function updateFinalTotal() {
                        let finalTotal = 0;
                        $('input.total').each(function() {
                            let val = parseFloat($(this).val()) || 0;
                            finalTotal += val;
                        });
                        $('#final-total').text(finalTotal.toFixed(2)); // عرض السعر النهائي
                    }

                    // عند تغيير الدواء أو الكمية في أي صف يتم إعادة حساب السعر تلقائيًا
                    $('form').on('input change', '.quantity, .medicine-select', function() {
                        let row = $(this).closest('.row');
                        calculateTotal(row);
                        updateFinalTotal();
                    });

                    // حساب السعر في أول صف عند تحميل الصفحة
                    let firstRow = $('.medicine-select').first().closest('.row');
                    calculateTotal(firstRow);
                    updateFinalTotal();
                });

                // عند اختيار مورد من القائمة يتم جلب أدوية المورد عبر AJAX
                $('select[name="supplier_id"]').on('change', function() {
                    let supplierId = $(this).val();
                    if (!supplierId) return; // إذا لم يتم اختيار مورد لا تفعل شيئًا

                    $.ajax({
                        url: '/api/supplier/' + supplierId + '/medicines', // رابط الـ API لجلب الأدوية
                        type: 'GET',
                        success: function(response) {
                            console.log('Full response:', response);

                            // استخراج مصفوفة الأدوية من الاستجابة
                            let medicines = [];
                            if (response.original && Array.isArray(response.original)) {
                                medicines = response.original;
                            } else if (Array.isArray(response)) {
                                medicines = response;
                            } else if (response.data && Array.isArray(response.data)) {
                                medicines = response.data;
                            } else {
                                alert('لم يتم استلام بيانات الأدوية بشكل صحيح.');
                                return;
                            }

                            // تحديث جميع قوائم الدواء في الفورم بالبيانات الجديدة
                            $('.medicine-select').each(function() {
                                let select = $(this);
                                select.empty(); // حذف الخيارات السابقة
                                select.append('<option value="">-- اختر الدواء --</option>');
                                medicines.forEach(medicine => {
                                    select.append(
                                        `<option value="${medicine.id}" data-price="${medicine.price}">${medicine.name}</option>`
                                    );
                                });
                            });
                        },

                        error: function(xhr) {
                            alert(xhr.responseJSON.error || 'حدث خطأ'); // عرض رسالة خطأ في حالة فشل الطلب
                        }
                    });
                });
            </script>
        @endsection
