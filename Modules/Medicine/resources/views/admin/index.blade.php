@extends('core::components.layouts.master')
@section('css')
    <style>
        /* Style the Image Used to Trigger the Modal */
        .myImg {
            /* Changed from #myImg to .myImg as it's a class now */
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal-overlay {
            /* Changed class to avoid conflict with Bootstrap modal */
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1050;
            /* Higher z-index than Bootstrap modals */
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
        .modal-content-img {
            /* Changed class */
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
        .modal-content-img,
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
            .modal-content-img {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <br>
    <div class="card">
        @role('مورد')
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">إدارة الأدوية</h3>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
            </div>
            <div class="card-body">
                <div class="mb-3 text-right">
                    {{-- Form for supplier role --}}
                    <form id="supplier-medicines-search-form" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" id="supplier-medicines-search-input"
                                    value="{{ request('search') }}" placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                            {{-- Removed the search button --}}
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <form id="medicines-selection-form" method="POST" action="{{ route('checked-medicine') }}">
                        @csrf
                        <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                            <thead class="text-right">
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th> {{-- تحديد الكل --}}
                                    <th>النوع</th>
                                    <th>صورة المنتج</th>
                                    <th>الصنف</th>
                                    <th>التركيب</th>
                                    <th>الشكل</th>
                                    <th>الشركة</th>
                                    <th>ملاحظات</th>
                                    <th>النت دولار </th>
                                    <th>العموم دولار </th>
                                </tr>
                            </thead>
                            <tbody id="medicines-table-body">
                                {{-- Initial load of table rows for supplier --}}
                                @include(
                                    'medicine::admin._medicines_supplier_table_rows',
                                    compact('medicines', 'supplierMedicineIds'))
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4" id="medicines-pagination-links">
                            {{ $medicines->links() }}
                        </div>

                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-success">إضافة الأدوية المحددة للمورد</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        @else
            {{-- For 'المشرف' role --}}
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
                <div class="mb-3 text-right">
                    {{-- Form for admin role --}}
                    <form id="admin-medicines-search-form" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" id="admin-medicines-search-input"
                                    value="{{ request('search') }}" placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                            {{-- Removed the search button --}}
                        </div>
                    </form>
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
                                <th>النت دولار </th>
                                <th>العموم دولار </th>
                                
                            </tr>
                        </thead>
                        <tbody id="medicines-table-body">
                            {{-- Initial load of table rows for admin --}}
                            @include('medicine::admin._medicines_admin_table_rows', compact('medicines'))
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4" id="medicines-pagination-links">
                        {{ $medicines->links() }}
                    </div>
                </div>
            </div>
        @endrole
    </div>

    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
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
                            <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv"
                                class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="imageModalOverlay" class="modal-overlay"> {{-- Changed ID and class --}}
        <span class="close_myModal" style="color: white">&times;</span>
        <img class="modal-content-img" id="modalImageContent"> {{-- Changed ID and class --}}
        <div id="caption"></div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable but disable its default search/paging if you're handling it via AJAX
            $('#medicines-datatable').DataTable({
                paging: false, // Laravel's pagination will handle this
                searching: false, // Custom AJAX search will handle this
                ordering: true, // You can keep ordering for client-side sorting if desired
                info: false,
                // Add any other DataTable options you need
            });

            let searchTimeout = null; // Variable to hold the timeout ID

            // --- AJAX Search on keyup (for both supplier and admin search inputs) ---
            // Use a common class or listen to both IDs
            $('#supplier-medicines-search-input, #admin-medicines-search-input').on('keyup', function() {
                clearTimeout(searchTimeout); // Clear any existing timeout
                let keyword = $(this).val();

                searchTimeout = setTimeout(function() {
                    fetchMedicines(keyword); // Call the function after a delay
                }, 300); // 300ms delay after the user stops typing
            });

            // --- AJAX Pagination Clicks ---
            // Use event delegation for dynamically loaded pagination links
            $(document).on('click', '#medicines-pagination-links .pagination a', function(e) {
                e.preventDefault(); // Prevent default link behavior (page reload)

                let pageUrl = $(this).attr('href');
                // Get the current search keyword from whichever search input is visible/active
                let currentSearchKeyword = $('#supplier-medicines-search-input').length ?
                    $('#supplier-medicines-search-input').val() :
                    $('#admin-medicines-search-input').val();

                // Call the fetch function with the specific page URL and current search term
                fetchMedicines(currentSearchKeyword, pageUrl);
            });

            // --- Helper Function to Fetch Medicines via AJAX ---
            function fetchMedicines(keyword, url = "{{ route('medicines.index') }}") {
                let finalUrl = new URL(url);
                finalUrl.searchParams.set('search', keyword); // Add or update the search param

                $.ajax({
                    url: finalUrl.toString(),
                    type: "GET",
                    success: function(response) {
                        $('#medicines-table-body').html(response.html); // Update table rows
                        $('#medicines-pagination-links').html(response
                            .pagination); // Update pagination links

                        // Re-attach "select all" functionality after new rows are loaded (for supplier role)
                        $('#select-all').off('click').on('click', function() {
                            $('input[name="medicines[]"]').prop('checked', this.checked);
                        });

                        // Re-attach image modal functionality to newly loaded images
                        attachImageModalListeners();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error, xhr.responseText);
                        // Optional: Display a user-friendly error message
                        $('#medicines-table-body').html(
                            `<tr><td colspan="11" class="text-center text-danger">حدث خطأ أثناء تحميل البيانات.</td></tr>`
                        );
                        $('#medicines-pagination-links').empty(); // Clear pagination on error
                    }
                });
            }

            // --- Image Modal Logic ---
            const imageModal = document.getElementById("imageModalOverlay"); // Corrected ID
            const modalImage = document.getElementById("modalImageContent"); // Corrected ID
            const captionText = document.getElementById("caption");
            const closeModalBtn = document.getElementsByClassName("close_myModal")[0];

            function attachImageModalListeners() {
                const images = document.querySelectorAll('.myImg'); // Select all images with class 'myImg'

                images.forEach(img => {
                    img.onclick = function() {
                        imageModal.style.display = "block";
                        modalImage.src = this.src;
                        captionText.innerHTML = this.alt || "";
                    }
                });
            }

            // Initial attachment of listeners
            attachImageModalListeners();

            // Close button for image modal
            closeModalBtn.onclick = function() {
                imageModal.style.display = "none";
            }

            // Close image modal if user clicks outside the image
            imageModal.onclick = function(event) {
                if (event.target === imageModal) {
                    imageModal.style.display = "none";
                }
            }
        });
    </script>
@endsection
