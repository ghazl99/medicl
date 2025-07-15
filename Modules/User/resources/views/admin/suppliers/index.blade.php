@extends('core::components.layouts.master')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>إدارة الموردين</h3>
        <a href="{{ route('register.suppliers') }}" class="btn btn-primary">إضافة مورد</a>
    </div>

    <form action="{{ route('suppliers.index') }}" method="GET" class="mb-4">
        <div class="mb-3">
            <input type="text" id="supplier-search-input" name="search_term" class="form-control"
                placeholder="ابحث باسم المورد أو اسم الشركة..." value="{{ request('search_term') }}">
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle"> {{-- Remove id from <table> --}}
            <thead>
                <tr>
                    <th>اسم المورد</th>
                    <th>رقم الهاتف</th>
                    <th>اسم الشركة</th>
                    <th>المدينة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="suppliers-table-body"> {{-- **Add id to <tbody> here** --}}
                @include('user::admin.suppliers._supplier_rows')
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('supplier-search-input');
            // تأكد أن هذا ID يشير للعنصر الصحيح (<tbody>)
            const suppliersTableBody = document.getElementById('suppliers-table-body');
            let typingTimer;
            const doneTypingInterval = 300;

            searchInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(performSearch, doneTypingInterval);
            });

            searchInput.addEventListener('keydown', function() {
                clearTimeout(typingTimer);
            });

            function performSearch() {
                const searchValue = searchInput.value;
                const url = new URL("{{ route('suppliers.index') }}");

                if (searchValue) {
                    url.searchParams.set('search_term', searchValue);
                } else {
                    url.searchParams.delete('search_term');
                }

                fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest', // Laravel can detect AJAX requests
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        // تحديث محتوى tbody
                        suppliersTableBody.innerHTML = ''; // مسح الصفوف القديمة

                        if (data.html) { // تأكد أن الـ backend يرجع مفتاح 'html'
                            suppliersTableBody.innerHTML = data.html;
                        } else {
                            suppliersTableBody.innerHTML =
                                '<tr><td colspan="5" class="text-center">لا توجد نتائج مطابقة.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                        suppliersTableBody.innerHTML =
                            '<tr><td colspan="5" class="text-center text-danger">حدث خطأ أثناء البحث.</td></tr>';
                    });
            }
        });
    </script>
@endsection
