@extends('core::components.layouts.master')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>إدارة الأدوية</h2>
        <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء جديد</a>
    </div>

    <form action="{{ route('medicines.index') }}" method="GET" class="mb-4">
        <div class="mb-3">
            <input type="text" id="medicine-search-input" name="search_term" class="form-control"
                placeholder="ابحث باسم الدواء أو اسم الشركة المصنعة..." value="{{ request('search_term') }}">
        </div>
       
    </form>

    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>اسم الدواء</th>
                    <th>الشركة المصنعة</th>
                    <th>الكمية المتوفرة</th>
                    <th>السعر</th>
                    <th>تاريخ الإضافة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody id="medicines-table-body"> {{-- **تأكد أن الـ ID هنا** --}}
                @include('medicine::admin._medicines_rows')
            </tbody>
        </table>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('medicine-search-input');
            const medicinesTableBody = document.getElementById('medicines-table-body');
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
                const url = new URL("{{ route('medicines.index') }}");

                if (searchValue) {
                    url.searchParams.set('search_term', searchValue);
                } else {
                    url.searchParams.delete('search_term');
                }

                fetch(url.toString(), {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
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
                        medicinesTableBody.innerHTML = '';
                        if (data.html) {
                            medicinesTableBody.innerHTML = data.html;
                        } else {
                            medicinesTableBody.innerHTML =
                                '<tr><td colspan="7" class="text-center">لا توجد نتائج مطابقة.</td></tr>'; // colspan 7
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                        medicinesTableBody.innerHTML =
                            '<tr><td colspan="7" class="text-center text-danger">حدث خطأ أثناء البحث.</td></tr>'; // colspan 7
                    });
            }
        });
    </script>
@endsection
