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
                            <th>التركيب</th>
                            <th>الشكل</th>
                            <th>الشركة</th>
                            <th>ملاحظات</th>
                            <!--<th>نت دولار حالي</th>-->
                            <!--<th>عموم دولار حالي</th>-->
                            <th>النت </th>
                            <th>العموم </th>
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
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // وظيفة البحث الديناميكي والترقيم
            function fetchMedicines(page = 1, searchQuery = '') {
                $.ajax({
                    url: "{{ route('my-medicines') }}", // تأكد أن هذا هو المسار الصحيح لوحدة التحكم الخاصة بك
                    method: 'GET',
                    data: {
                        page: page,
                        search: searchQuery
                    },
                    success: function(response) {
                        $('#medicines-table-body').html(response.html);
                        $('#pagination-links').html(response.pagination);
                        // أعد ربط وظيفة المودال بعد تحديث المحتوى
                        bindImageModalEvents();
                    },
                    error: function(xhr) {
                        console.error("حدث خطأ أثناء جلب البيانات:", xhr);
                    }
                });
            }

            // عند الكتابة في حقل البحث
            let searchTimeout;
            $('#medicine-search-input').on('keyup', function() {
                clearTimeout(searchTimeout);
                let searchQuery = $(this).val();
                searchTimeout = setTimeout(function() {
                    fetchMedicines(1, searchQuery); // ابدأ دائمًا من الصفحة الأولى عند البحث
                }, 300); // تأخير 300 مللي ثانية لمنع الطلبات المتعددة عند الكتابة السريعة
            });

            // عند النقر على روابط الترقيم
            $(document).on('click', '#pagination-links .pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                let searchQuery = $('#medicine-search-input').val();
                fetchMedicines(page, searchQuery);
            });

            // وظيفة ربط أحداث فتح المودال للصور
            function bindImageModalEvents() {
                const modal = document.getElementById("myModal");
                const modalImg = document.getElementById("img01");
                const captionText = document.getElementById("caption");
                const closeBtn = document.getElementsByClassName("close_myModal")[0];

                const images = document.querySelectorAll(
                    '.myImg'); // استخدام .myImg بدلاً من #myImg لربط جميع الصور

                images.forEach(img => {
                    img.onclick = function() {
                        modal.style.display = "block";
                        modalImg.src = this.src;
                        captionText.innerHTML = this.alt || "";
                    }
                });

                closeBtn.onclick = function() {
                    modal.style.display = "none";
                }

                modal.onclick = function(event) {
                    if (event.target === modal) {
                        modal.style.display = "none";
                    }
                }
            }

            // استدعاء وظيفة ربط المودال عند تحميل الصفحة لأول مرة
            bindImageModalEvents();

            // تحديد الكل - هذا الكود يجب أن يكون داخل $(document).ready()
            $('#select-all').click(function() {
                $('input[name="medicines[]"]').prop('checked', this.checked);
            });

            // لا تحتاج إلى جلب عناصر الصور وربط الأحداث هنا مرة أخرى
            // لأن bindImageModalEvents() تقوم بذلك عند تحميل الصفحة وعند تحديث الجدول
        });
    </script>
    <script>
        $(document).ready(function() {
            // عند الضغط على أي مكان في خلية الملاحظة (td)
            $(document).on('click', '.note-cell', function() {
                // إخفاء النص
                $(this).find('.note-text').addClass('d-none');
                // إظهار حقل الإدخال وتركيزه
                $(this).find('.note-input').removeClass('d-none').focus();
            });

            // عند فقدان التركيز من حقل الإدخال
            $(document).on('blur', '.note-input', function() {
                saveNote($(this));
            });

            // عند الضغط على زر Enter داخل حقل الإدخال
            $(document).on('keypress', '.note-input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $(this).blur(); // يحفز حدث blur ليتم الحفظ
                }
            });

            // دالة لحفظ الملاحظة عبر AJAX
            function saveNote($input) {
                const $td = $input.closest('.note-cell');
                const medicineUserId = $td.data('id'); // الحصول على معرف الدواء من data-id
                const newNote = $input.val();
                const $span = $td.find('.note-text');
                console.log('medicineUserId:', medicineUserId);

                $.ajax({
                    url: `/medicine-user/${medicineUserId}/update-note`,
                    method: 'POST',
                    data: {
                        notes: newNote,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // تحديث النص وعرضه
                        $span.text(newNote).removeClass('d-none');
                        // إخفاء حقل الإدخال
                        $input.addClass('d-none');

                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم تحديث الملاحظة بنجاح',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        // في حالة الخطأ، إظهار النص وإخفاء الإدخال
                        $span.removeClass('d-none');
                        $input.addClass('d-none');

                        Swal.fire({
                            icon: 'error',
                            title: 'فشل الحفظ',
                            text: 'حدث خطأ أثناء تحديث الملاحظة',
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            }
        });

        $(document).ready(function() {
            // عند النقر على خلية العرض
            $(document).on('click', '.offer-cell', function() {
                $(this).find('.offer-text').addClass('d-none');
                $(this).find('.offer-input').removeClass('d-none').focus();
            });

            // عند الخروج من حقل الإدخال
            $(document).on('blur', '.offer-input', function() {
                saveOffer($(this));
            });

            // عند الضغط على Enter
            $(document).on('keypress', '.offer-input', function(e) {
                if (e.which === 13) {
                    e.preventDefault();
                    $(this).blur();
                }
            });

            function saveOffer($input) {
                const $td = $input.closest('.offer-cell');
                const medicineUserId = $td.data('id');
                const newOffer = $input.val();
                const $span = $td.find('.offer-text');

                $.ajax({
                    url: `/medicine-user/${medicineUserId}/update-offer`,
                    method: 'POST',
                    data: {
                        offer: newOffer,
                        _token: '{{ csrf_token() }}'
                    },

                    success: function(response) {
                        $span.text(newOffer).removeClass('d-none');
                        $input.addClass('d-none');

                        Swal.fire({
                            icon: 'success',
                            title: 'تم الحفظ',
                            text: 'تم تحديث العرض بنجاح',
                            timer: 1500,
                            showConfirmButton: false
                        });
                    },
                    error: function() {
                        $span.removeClass('d-none');
                        $input.addClass('d-none');

                        Swal.fire({
                            icon: 'error',
                            title: 'فشل الحفظ',
                            text: 'حدث خطأ أثناء تحديث العرض',
                            confirmButtonText: 'موافق'
                        });
                    }
                });
            }
        });
    </script>
@endsection
