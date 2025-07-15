@extends('core::components.layouts.master')
@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>إدارة الصيادلة</h3>
        <a href="{{ route('register.pharmacists') }}" class="btn btn-primary">إضافة صيدلي</a>
    </div>
    {{-- فورم البحث --}}
    <form action="{{ route('pharmacists.index') }}" method="GET" class="mb-4">
        <div class="mb-3">
            <input type="text" id="pharmacist-search-input" name="search_term" class="form-control"
                placeholder="ابحث باسم الصيدلي أو اسم الصيدلية..." value="{{ request('search_term') }}">
        </div>
    </form>
    <div class="table-responsive">
        <table class="table table-bordered align-middle" id="pharmacists-table-body">
            <thead>
                <tr>
                    <th>اسم الصيدلي</th>
                    <th>رقم الهاتف</th>
                    <th>اسم الصيدلية</th>
                    <th>المدينة</th>
                    <th>إجراءات</th>
                </tr>
            </thead>
            <tbody>
                @include('user::admin.pharmacists._pharmacist_rows')

            </tbody>
        </table>

    </div>
    </main>
@endsection
@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('pharmacist-search-input');
            const pharmacistsTableBody = document.getElementById('pharmacists-table-body'); // تحديث ID
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
                const url = new URL("{{ route('pharmacists.index') }}"); // تحديث المسار

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
                        pharmacistsTableBody.innerHTML = ''; // تحديث ID
                        if (data.html) {
                            pharmacistsTableBody.innerHTML = data.html;
                        } else {
                            pharmacistsTableBody.innerHTML =
                                '<tr><td colspan="5" class="text-center">لا توجد نتائج مطابقة.</td></tr>';
                        }
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                        pharmacistsTableBody.innerHTML =
                            '<tr><td colspan="5" class="text-center text-danger">حدث خطأ أثناء البحث.</td></tr>';
                    });
            }
        });
    </script>
@endsection
