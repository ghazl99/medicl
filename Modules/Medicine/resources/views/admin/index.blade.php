@extends('core::components.layouts.master')

@section('css')
    <style>
        /* ==========================
           Image Modal Styling
        =========================== */
        .myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }
        .myImg:hover {
            opacity: 0.7;
        }

        /* Modal overlay */
        .modal-overlay {
            display: none;
            position: fixed;
            z-index: 1050;
            padding-top: 100px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.9);
        }

        /* Modal image content */
        .modal-content-img {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        /* Caption for modal */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from { transform: scale(0) }
            to { transform: scale(1) }
        }

        /* Close button for modal */
        .close_myModal {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }
        .close_myModal:hover,
        .close_myModal:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        @media only screen and (max-width: 700px) {
            .modal-content-img { width: 100%; }
        }
    </style>
@endsection

@section('content')
    <br>
    <div class="card">
        @role('مورد')
            <!-- ==========================
                 Supplier Medicines Table
            =========================== -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">إدارة الأدوية</h3>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
            </div>

            <div class="card-body">
                <!-- Search form -->
                <div class="mb-3 text-right">
                    <form id="supplier-medicines-search-form" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" id="supplier-medicines-search-input"
                                    value="{{ request('search') }}" placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Medicines table -->
                <div class="table-responsive">
                    <form id="medicines-selection-form" method="POST" action="{{ route('checked-medicine') }}">
                        @csrf
                        <input type="hidden" name="all_selected_medicines" id="all_selected_medicines" value="">
                        <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                            <thead class="text-right">
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>النوع</th>
                                    <th>صورة المنتج</th>
                                    <th>الصنف</th>
                                    <th>الصنف باللغة العربية</th>
                                    <th>التركيب</th>
                                    <th>الشكل</th>
                                    <th>الشركة</th>
                                    <th>ملاحظات</th>
                                    <th>وصف الدواء</th>
                                    <th>النت</th>
                                    <th>العموم</th>
                                </tr>
                            </thead>
                            <tbody id="medicines-table-body">
                                @include('medicine::admin._medicines_supplier_table_rows', compact('medicines', 'supplierMedicineIds'))
                            </tbody>
                        </table>

                        <!-- Pagination -->
                        <div class="d-flex justify-content-center mt-4" id="medicines-pagination-links">
                            {{ $medicines->links() }}
                        </div>

                        <!-- Submit selected medicines -->
                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-success">إضافة الأدوية المحددة للمورد</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>

        @else
            <!-- ==========================
                 Admin / Supervisor Medicines Table
            =========================== -->
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">جميع الأدوية</h3>

                @role('المشرف')
                    <div class="d-flex flex-column flex-md-row flex-wrap mb-3">
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary mb-2 mb-md-0 mr-md-2">
                            إضافة دواء
                        </a>
                        <button type="button" class="btn btn-success mb-2 mb-md-0" data-toggle="modal" data-target="#importModal">
                            استيراد <i class="fas fa-file-import ml-1"></i>
                        </button>
                    </div>
                @endrole
            </div>

            <div class="card-body">
                <!-- Search form -->
                <div class="mb-3 text-right">
                    <form id="admin-medicines-search-form" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" id="admin-medicines-search-input"
                                    value="{{ request('search') }}" placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Medicines table -->
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                        <thead class="text-right">
                            <tr>
                                <th>ID</th>
                                <th>النوع</th>
                                <th>صورة المنتج</th>
                                <th>الصنف</th>
                                <th>الصنف باللغة العربية</th>
                                <th>التركيب</th>
                                <th>الشكل</th>
                                <th>الشركة</th>
                                <th>ملاحظات</th>
                                <th>وصف الدواء</th>
                                <th>النت</th>
                                <th>العموم</th>
                                <th>الحالة</th>
                            </tr>
                        </thead>
                        <tbody id="medicines-table-body">
                            @include('medicine::admin._medicines_admin_table_rows', compact('medicines'))
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-center mt-4" id="medicines-pagination-links">
                        {{ $medicines->links() }}
                    </div>
                </div>
            </div>
        @endrole
    </div>

    <!-- ==========================
         Modals
    =========================== -->

    <!-- Import Excel Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('medicines.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">استيراد ملف إكسل</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">اختر ملف الإكسل للاستيراد:</label>
                            <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Image Modal -->
    <div id="imageModalOverlay" class="modal-overlay">
        <span class="close_myModal" style="color: white">&times;</span>
        <img class="modal-content-img" id="modalImageContent">
        <div id="caption"></div>
    </div>

    <!-- New Status Modal -->
    <div class="modal fade" id="newStatusModal" tabindex="-1" aria-labelledby="newStatusModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form id="new-status-form">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="newStatusModalLabel">تحديد فترة الحالة الجديدة</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="new_start_date" class="form-label">تاريخ بداية الحالة الجديدة</label>
                            <input type="date" class="form-control" id="new_start_date" name="new_start_date" required>
                        </div>
                        <div class="mb-3">
                            <label for="new_end_date" class="form-label">تاريخ نهاية الحالة الجديدة</label>
                            <input type="date" class="form-control" id="new_end_date" name="new_end_date" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">تأكيد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            /* ==========================
               Initialize DataTable
            =========================== */
            $('#medicines-datatable').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false
            });

            /* ==========================
               AJAX Search & Pagination
            =========================== */
            let searchTimeout = null;

            $('#supplier-medicines-search-input, #admin-medicines-search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                let keyword = $(this).val();
                searchTimeout = setTimeout(() => fetchMedicines(keyword), 300);
            });

            $(document).on('click', '#medicines-pagination-links .pagination a', function(e) {
                e.preventDefault();
                const pageUrl = $(this).attr('href');
                const keyword = $('#supplier-medicines-search-input').length ?
                    $('#supplier-medicines-search-input').val() :
                    $('#admin-medicines-search-input').val();
                fetchMedicines(keyword, pageUrl);
            });

            function fetchMedicines(keyword, url = "{{ route('medicines.index') }}") {
                const finalUrl = new URL(url);
                finalUrl.searchParams.set('search', keyword);

                $.ajax({
                    url: finalUrl.toString(),
                    type: "GET",
                    success: function(response) {
                        $('#medicines-table-body').html(response.html);
                        $('#medicines-pagination-links').html(response.pagination);
                        afterTableUpdate(); // Re-attach checkbox & modal listeners
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        $('#medicines-table-body').html(`<tr><td colspan="11" class="text-center text-danger">حدث خطأ أثناء تحميل البيانات.</td></tr>`);
                        $('#medicines-pagination-links').empty();
                    }
                });
            }

            /* ==========================
               Image Modal Logic
            =========================== */
            const imageModal = document.getElementById("imageModalOverlay");
            const modalImage = document.getElementById("modalImageContent");
            const captionText = document.getElementById("caption");
            const closeModalBtn = document.getElementsByClassName("close_myModal")[0];

            function attachImageModalListeners() {
                document.querySelectorAll('.myImg').forEach(img => {
                    img.onclick = function() {
                        imageModal.style.display = "block";
                        modalImage.src = this.src;
                        captionText.innerHTML = this.alt || "";
                    }
                });
            }
            attachImageModalListeners();

            closeModalBtn.onclick = () => imageModal.style.display = "none";
            imageModal.onclick = (event) => { if (event.target === imageModal) imageModal.style.display = "none"; }

            /* ==========================
               Medicines Selection (Supplier)
            =========================== */
            const STORAGE_KEY = 'selectedMedicineIds{{ Auth::user()->id }}';
            let selectedMedicineIds = new Set(@json($supplierMedicineIds ?? []).map(id => id.toString()));

            function saveSelectedToStorage() { localStorage.setItem(STORAGE_KEY, JSON.stringify([...selectedMedicineIds])); }
            function updateCheckboxStates() {
                $('input[name="medicines[]"]').each(function() {
                    $(this).prop('checked', selectedMedicineIds.has($(this).val().toString()));
                });
            }
            function updateSelectAllCheckbox() {
                const all = $('input[name="medicines[]"]');
                $('#select-all').prop('checked', all.length && all.length === $('input[name="medicines[]"]:checked').length);
            }
            function afterTableUpdate() { updateCheckboxStates(); updateSelectAllCheckbox(); }

            $(document).on('change', 'input[name="medicines[]"]', function() {
                const val = $(this).val().toString();
                $(this).is(':checked') ? selectedMedicineIds.add(val) : selectedMedicineIds.delete(val);
                saveSelectedToStorage();
            });

            $('#select-all').on('change', function() {
                const checked = $(this).is(':checked');
                $('input[name="medicines[]"]').each(function() {
                    $(this).prop('checked', checked);
                    checked ? selectedMedicineIds.add($(this).val().toString()) : selectedMedicineIds.delete($(this).val().toString());
                });
                saveSelectedToStorage();
            });

            $('#medicines-selection-form').on('submit', function(e) {
                e.preventDefault();
                $('#all_selected_medicines').val([...selectedMedicineIds].join(','));
                if (!selectedMedicineIds.size) { alert('يرجى اختيار دواء واحد على الأقل.'); return; }

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        if (response.success) Swal.fire({ icon: 'success', title: 'نجاح', text: response.message, timer: 1500, showConfirmButton: false });
                    },
                    error: () => Swal.fire('حدث خطأ', 'حاول مرة أخرى', 'error')
                });
            });
            afterTableUpdate();

            /* ==========================
               Editable Cells (Type AR)
            =========================== */
            $(document).on('click', '.editable-type-ar', function(e) {
                e.stopPropagation();
                const span = $(this).find('.editable-text');
                const input = $(this).find('.edit-input');
                span.hide(); input.show().focus();
            });

            $(document).on('blur keypress', '.edit-input', function(e) {
                if (e.type === 'blur' || e.which === 13) {
                    e.preventDefault(); e.stopPropagation();
                    const input = $(this);
                    const newValue = input.val().trim();
                    const td = input.closest('.editable-type-ar');
                    const span = td.find('.editable-text');
                    const medicineId = td.data('medicine-id');

                    if (newValue === span.text().trim()) { input.hide(); span.show(); return; }

                    $.ajax({
                        url: '/medicines/' + medicineId,
                        type: 'POST',
                        data: { _token: $('meta[name="csrf-token"]').attr('content'), _method: 'PUT', type_ar: newValue },
                        success: () => span.text(newValue),
                        error: xhr => alert('حدث خطأ أثناء التحديث: ' + (xhr.responseJSON?.message || 'حاول مرة أخرى')),
                        complete: () => { input.hide(); span.show(); }
                    });
                }
            });

            /* ==========================
               Toggle New Status Modal
            =========================== */
            const newStatusModal = new bootstrap.Modal(document.getElementById('newStatusModal'));
            let selectedMedicineId = null;

            $(document).on('click', '.toggle-new-status', function() {
                selectedMedicineId = $(this).data('medicine-id');
                if ($(this).text().trim() === 'جديد') { Swal.fire('الدواء بالفعل جديد.'); return; }
                newStatusModal.show();
            });

            $('#new-status-form').on('submit', function(e) {
                e.preventDefault();
                const startDate = $('#new_start_date').val();
                const endDate = $('#new_end_date').val();
                if (!startDate || !endDate) { Swal.fire('يرجى تعبئة التواريخ بشكل صحيح.', '', 'warning'); return; }

                $.ajax({
                    url: '/medicines/' + selectedMedicineId + '/toggle-new',
                    method: 'POST',
                    data: { _token: '{{ csrf_token() }}', is_new: 1, new_start_date: startDate, new_end_date: endDate },
                    success: res => res.success ? location.reload() : Swal.fire('حدث خطأ أثناء التحديث.', '', 'error'),
                    error: () => Swal.fire('حدث خطأ أثناء الاتصال بالسيرفر.', '', 'error')
                });
            });

        });
    </script>
@endsection
