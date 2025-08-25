@extends('core::components.layouts.master')
@section('css')
    <style>
        /* Style the Image Used to Trigger the Modal */
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content,
        #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
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

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <br>
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-right">جميع أدوية مستودعي </h3>
            {{-- زر إرسال --}}
            <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
        </div>
        <div class="card-body">
            {{-- حقل البحث --}}
            <div class="mb-3">
                <div class="row justify-content-start">
                    <div class="col-md-4 col-sm-6 mb-2">
                        <input type="text" id="medicine-search-input" class="form-control" placeholder="ابحث عن دواء...">
                    </div>
                </div>
            </div>

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
                            <!--<th>نت دولار حالي</th>-->
                            <!--<th>عموم دولار حالي</th>-->
                            <th>النت </th>
                            <!--<th>نت سوري</th>-->
                            <!--<th>عموم سوري</th>-->
                            <!--<th>ملاحظات 2</th>-->
                            <!--<th>نسبة تغير السعر</th>-->
                            <th>التوفر</th>
                            <th>عرض(الكمية)</th>
                        </tr>
                    </thead>
                    <tbody id="medicines-table-body">
                        @include('medicine::admin._myMedicine_supplier_table_rows', compact('medicines'))
                    </tbody>

                </table>
                <div class="d-flex justify-content-center mt-4" id="pagination-links">
                    {{ $medicines->links() }}
                </div>
            </div>
        </div>

    </div>
    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- The Close Button -->
        <span class="close_myModal" style="color: white">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img01">

        <!-- Modal Caption (Image Text) -->
        <div id="caption"></div>
    </div>
    <div class="modal fade" id="offerModal" tabindex="-1" aria-labelledby="offerModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="offerModalLabel">تعديل العرض</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="إغلاق">X</button>
                </div>
                <div class="modal-body">
                    <form id="offer-form">
                        @csrf
                        <input type="hidden" id="offer_pivot_id">
                        <div class="mb-3">
                            <label for="offer_qty" class="form-label">الكمية المطلوبة للشراء</label>
                            <input type="number" class="form-control" id="offer_qty" name="offer_qty" min="0">
                        </div>
                        <div class="mb-3">
                            <label for="offer_free_qty" class="form-label">الكمية المجانية</label>
                            <input type="number" class="form-control" id="offer_free_qty" name="offer_free_qty"
                                min="0">
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">تعديل</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">X</button>
                </div>
                <div class="modal-body">
                    <input type="text" id="modalInput" class="form-control">
                    <input type="hidden" id="modalCellId">
                    <input type="hidden" id="modalType">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="saveModal">حفظ</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {

            // ==========================
            // بحث ديناميكي وترقيم الصفحات
            // ==========================
            function fetchMedicines(page = 1, searchQuery = '') {
                $.ajax({
                    url: "{{ route('my-medicines') }}",
                    method: 'GET',
                    data: {
                        page: page,
                        search: searchQuery
                    },
                    success: function(response) {
                        $('#medicines-table-body').html(response.html);
                        $('#pagination-links').html(response.pagination);
                        bindImageModalEvents(); // إعادة ربط المودالات
                    },
                    error: function(xhr) {
                        console.error("حدث خطأ أثناء جلب البيانات:", xhr);
                    }
                });
            }

            let searchTimeout;
            $('#medicine-search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                let searchQuery = $(this).val();
                searchTimeout = setTimeout(() => fetchMedicines(1, searchQuery), 300);
            });

            $(document).on('click', '#pagination-links .pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let searchQuery = $('#medicine-search-input').val();
                fetchMedicines(page, searchQuery);
            });

            // ==========================
            // مودال الصور
            // ==========================
            function bindImageModalEvents() {
                const modal = document.getElementById("myModal");
                const modalImg = document.getElementById("img01");
                const captionText = document.getElementById("caption");
                const closeBtn = document.getElementsByClassName("close_myModal")[0];

                document.querySelectorAll('.myImg').forEach(img => {
                    img.onclick = function() {
                        modal.style.display = "block";
                        modalImg.src = this.src;
                        captionText.innerHTML = this.alt || "";
                    };
                });

                closeBtn.onclick = function() {
                    modal.style.display = "none";
                }
                modal.onclick = function(event) {
                    if (event.target === modal) modal.style.display = "none";
                }
            }
            bindImageModalEvents();

            // ==========================
            // حفظ البيانات عبر AJAX
            // ==========================
            function savePivotData(pivotId, data, onSuccess) {
                data._token = '{{ csrf_token() }}';
                $.ajax({
                    url: `/medicine-user/${pivotId}/update-pivot`,
                    method: 'POST',
                    data: data,
                    success: function() {
                        if (onSuccess) onSuccess();
                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم تحديث البيانات بنجاح',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function(xhr) {
                        console.error(xhr.responseText);
                        Swal.fire({
                            icon: 'error',
                            title: 'فشل الحفظ',
                            text: 'حدث خطأ أثناء التحديث',
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            }

            // ==========================
            // مودال الملاحظات والسعر
            // ==========================
            let currentCell = null;

            $(document).on('click', '.note-cell, .net-cell', function() {
                currentCell = $(this);
                const currentValue = currentCell.find('.editable-text').text().trim();
                $('#modalInput').val(currentValue);
                $('#modalCellId').val(currentCell.data('id'));
                $('#modalType').val(currentCell.hasClass('note-cell') ? 'note-cell' : 'net-cell');
                $('#editModal').modal('show');
            });

            $('#saveModal').on('click', function() {
                const newValue = $('#modalInput').val();
                const pivotId = $('#modalCellId').val();
                const cellType = $('#modalType').val();

                let data = {};
                if (cellType === 'note-cell') data.notes = newValue;
                else if (cellType === 'net-cell') data.price = parseFloat(newValue) || null;

                savePivotData(pivotId, data, function() {
                    currentCell.find('.editable-text').text(newValue);
                    $('#editModal').modal('hide');
                });
            });

            // ==========================
            // مودال تعديل العرض
            // ==========================
            $(document).on('click', '.offer-cell', function() {
                const pivotId = $(this).data('id');
                const offerText = $(this).find('.offer-text').text().trim();

                let offer_qty = '';
                let offer_free_qty = '';
                if (offerText.includes('+')) {
                    const parts = offerText.split('+');
                    offer_qty = parseInt(parts[0]) || '';
                    offer_free_qty = parseInt(parts[1]) || '';
                }

                $('#offer_pivot_id').val(pivotId);
                $('#offer_qty').val(offer_qty);
                $('#offer_free_qty').val(offer_free_qty);
                $('#offerModal').modal('show');
            });

            $('#offer-form').on('submit', function(e) {
                e.preventDefault();
                const pivotId = $('#offer_pivot_id').val();
                const offer_qty = $('#offer_qty').val();
                const offer_free_qty = $('#offer_free_qty').val();

                savePivotData(pivotId, {
                    offer_qty,
                    offer_free_qty
                }, function() {
                    $(`.offer-cell[data-id="${pivotId}"] .offer-text`).text(
                        `${offer_qty}+${offer_free_qty}`);
                    $('#offerModal').modal('hide');
                });
            });

        });
    </script>
@endsection
