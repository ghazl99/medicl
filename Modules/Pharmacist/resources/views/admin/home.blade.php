@extends('pharmacist::components.layouts.master')

@section('css')
<style>
    .highlight {
        background-color: yellow;
        font-weight: bold;
    }
</style>
@endsection

@section('content')
<div class="search-container mb-4">
    <h2 class="title" style="color: #ffffff;">البحث عن الأدوية</h2>
    <p class="section-subtitle">ابحث عن الدواء المناسب لك بسرعة وسهولة</p>
    <div class="search-input-group input-group">
        <i class="bi bi-search search-icon"></i>
        <input type="text" name="search" class="search-input" id="searchInput"
               placeholder="اكتب اسم الدواء أو المرض..." autocomplete="off">
    </div>
</div>

<div class="container mt-4">
    <h5 id="search-results-header" class="mb-3 text-end d-none" dir="rtl"></h5>
    <div id="search-results">
        @include('pharmacist::admin.medicines_list', ['medicines' => $medicines, 'keyword' => $keyword])
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    let searchInput = document.getElementById("searchInput");
    let resultsContainer = document.getElementById("search-results");
    let resultsHeader = document.getElementById("search-results-header");
    let timer = null;

    function fetchResults(query, url = null) {
        let fetchUrl = url ? url : "{{ route('pharmacist.home') }}?search=" + encodeURIComponent(query);

        fetch(fetchUrl, {
            headers: { "X-Requested-With": "XMLHttpRequest" }
        })
        .then(response => response.text())
        .then(data => {
            resultsContainer.innerHTML = data;
            resultsHeader.classList.remove("d-none");
            resultsHeader.innerHTML = `نتائج البحث عن <b class="text-primary">${query}</b>`;

            // ربط روابط الترقيم للصفحات الجديدة
            document.querySelectorAll("#medicines-pagination-links a").forEach(link => {
                link.addEventListener("click", function (e) {
                    e.preventDefault();
                    fetchResults(query, this.href);
                });
            });
        })
        .catch(error => console.error("خطأ في جلب البيانات:", error));
    }

    searchInput.addEventListener("keyup", function (e) {
        clearTimeout(timer);
        let query = this.value.trim();

        // إذا ضغط المستخدم Enter
        if (e.key === "Enter") {
            e.preventDefault();
            this.blur(); // هذا يجعل الكيبورد يختفي على الموبايل
            if (query.length > 1) {
                fetchResults(query);
            }
        } else {
            timer = setTimeout(() => {
                if (query.length > 1) {
                    fetchResults(query);
                } else {
                    resultsContainer.innerHTML = "";
                    resultsHeader.classList.add("d-none");
                }
            }, 400);
        }
    });
});
</script>
@endsection
