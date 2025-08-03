@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="text-right">إدارة الصيادلة</h3>
        </div>
        <div class="card-body">
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('register.pharmacists') }}" class="btn btn-primary">إضافة صيدلي</a>
            </div>
            <div class="mb-3 text-right">
                <form id="pharmacist-search-form" class="mb-3">
                    <div class="row justify-content-start">
                        <div class="col-md-4 col-sm-6 mb-2">
                            <input type="text" name="search" id="pharmacist-search-input"
                                value="{{ request('search') }}" placeholder="ابحث عن الصيدلي..." class="form-control" />
                        </div>
                        {{-- No search button needed here --}}
                    </div>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="pharmacists-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>اسم الصيدلي</th>
                            <th>رقم الهاتف</th>
                            <th>اسم الصيدلية</th>
                            <th>المدينة</th>
                            <th>الحالة</th>
                            <th>إجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="pharmacists-table-body">
                        @include('user::admin.pharmacists._pharmacists_table_rows', [
                            'pharmacists' => $pharmacists,
                        ])
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4" id="pharmacists-pagination-links">
                    {{ $pharmacists->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            // Initialize DataTable but disable its default search/paging if you're handling it via AJAX
            $('#pharmacists-datatable').DataTable({
                paging: false,    // Laravel's pagination will handle this
                searching: false, // Custom AJAX search will handle this
                ordering: true,   // You can keep ordering for client-side sorting if desired
                info: false,
                // Add any other DataTable options you need
            });

            let searchTimeout = null; // Variable to hold the timeout ID

            // --- AJAX Search on keyup ---
            $('#pharmacist-search-input').on('keyup', function() {
                clearTimeout(searchTimeout); // Clear any existing timeout
                let keyword = $(this).val();

                searchTimeout = setTimeout(function() {
                    fetchPharmacists(keyword); // Call the function after a delay
                }, 300); // 300ms delay after the user stops typing
            });

            // --- AJAX Pagination Clicks ---
            // Use event delegation for dynamically loaded pagination links
            $(document).on('click', '#pharmacists-pagination-links .pagination a', function(e) {
                e.preventDefault(); // Prevent default link behavior (page reload)

                let pageUrl = $(this).attr('href');
                let currentSearchKeyword = $('#pharmacist-search-input').val(); // Get the current search term

                // Call the fetch function with the specific page URL and current search term
                fetchPharmacists(currentSearchKeyword, pageUrl);
            });

            // --- Helper Function to Fetch Pharmacists via AJAX ---
            function fetchPharmacists(keyword, url = "{{ route('pharmacists.index') }}") {
                // Construct URL to include search parameter and page number if pagination is clicked
                let finalUrl = new URL(url);
                finalUrl.searchParams.set('search', keyword); // Add or update the search param

                $.ajax({
                    url: finalUrl.toString(), // Use the constructed URL
                    type: "GET",
                    // No need to pass 'data' object here if params are in URL
                    // data: { search: keyword }, // If you prefer to send 'search' as separate data, uncomment this
                    success: function(response) {
                        $('#pharmacists-table-body').html(response.html); // Update table rows
                        $('#pharmacists-pagination-links').html(response.pagination); // Update pagination links
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: ", status, error, xhr.responseText);
                        // Optional: Display a user-friendly error message
                        $('#pharmacists-table-body').html(
                            `<tr><td colspan="6" class="text-center text-danger">حدث خطأ أثناء تحميل البيانات.</td></tr>`
                        );
                        $('#pharmacists-pagination-links').empty(); // Clear pagination on error
                    }
                });
            }
        });
    </script>
@endsection
