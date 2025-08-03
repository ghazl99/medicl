@extends('core::components.layouts.master')
@section('css')
    <link href="{{ URL::asset('assets/plugins/owl-carousel/owl.carousel.css') }}" rel="stylesheet" />
    <link href="{{ URL::asset('assets/plugins/jqvmap/jqvmap.min.css') }}" rel="stylesheet">
@endsection
@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الموردين</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('register.suppliers') }}" class="btn btn-primary">إضافة مورد</a>
            </div>
            <div class="mb-3 text-right">
                {{-- Removed action and method from form, as AJAX handles submission --}}
                <form id="supplier-search-form" class="mb-3">
                    <div class="row justify-content-start">
                        <div class="col-md-4 col-sm-6 mb-2">
                            <input type="text" name="search" id="supplier-search-input"
                                value="{{ request('search') }}" placeholder="ابحث عن مورد..." class="form-control" />
                        </div>
                        {{-- Removed the search button --}}
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="suppliers-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم المورد</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الشركة</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="suppliers-table-body">
                        {{-- Include the partial view for table rows --}}
                        @include('user::admin.suppliers._suppliers_table_rows', ['suppliers' => $suppliers])
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4" id="suppliers-pagination-links">
                    {{ $suppliers->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable but disable its default search/paging if you're handling it via AJAX
            $('#suppliers-datatable').DataTable({
                paging: false,    // Laravel's pagination will handle this
                searching: false, // Custom AJAX search will handle this
                ordering: true,   // You can keep ordering for client-side sorting if desired
                info: false,
                // Add any other DataTable options you need
            });

            let searchTimeout = null; // Variable to hold the timeout ID

            // --- AJAX Search on keyup ---
            $('#supplier-search-input').on('keyup', function() {
                clearTimeout(searchTimeout); // Clear any existing timeout
                let keyword = $(this).val();

                searchTimeout = setTimeout(function() {
                    fetchSuppliers(keyword); // Call the function after a delay
                }, 300); // 300ms delay after the user stops typing
            });

            // --- AJAX Pagination Clicks ---
            // Use event delegation for dynamically loaded pagination links
            $(document).on('click', '#suppliers-pagination-links .pagination a', function(e) {
                e.preventDefault(); // Prevent default link behavior (page reload)

                let pageUrl = $(this).attr('href');
                let currentSearchKeyword = $('#supplier-search-input').val(); // Get the current search term

                // Call the fetch function with the specific page URL and current search term
                fetchSuppliers(currentSearchKeyword, pageUrl);
            });

            // --- Helper Function to Fetch Suppliers via AJAX ---
            function fetchSuppliers(keyword, url = "{{ route('suppliers.index') }}") {
                // Construct URL to include search parameter and page number if pagination is clicked
                let finalUrl = new URL(url);
                finalUrl.searchParams.set('search', keyword); // Add or update the search param

                $.ajax({
                    url: finalUrl.toString(), // Use the constructed URL
                    type: "GET",
                    // No need to pass 'data' object here if params are in URL, it's already in the URL
                    success: function(response) {
                        $('#suppliers-table-body').html(response.html); // Update table rows
                        $('#suppliers-pagination-links').html(response.pagination); // Update pagination links
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error, xhr.responseText);
                        // Optional: Display a user-friendly error message
                        $('#suppliers-table-body').html(
                            `<tr><td colspan="6" class="text-center text-danger">حدث خطأ أثناء تحميل البيانات.</td></tr>`
                        );
                        $('#suppliers-pagination-links').empty(); // Clear pagination on error
                    }
                });
            }
        });
    </script>
@endsection
