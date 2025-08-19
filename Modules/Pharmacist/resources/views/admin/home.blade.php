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
        <h2 class="title" style=" color: #ffffff;">البحث عن الأدوية</h2>
        <p class="section-subtitle">ابحث عن الدواء المناسب لك بسرعة وسهولة</p>
        <form action="{{ route('pharmacist.home') }}" method="GET" class="search-form" id="searchForm">
            <div class="search-input-group input-group">
                <i class="bi bi-search search-icon"></i>
                <input type="text" name="search" class=" search-input" id="searchInput"
                    placeholder="اكتب اسم الدواء أو المرض..." value="{{ request('search') }}">
                <button type="submit " class="search-btn">
                    <i class="bi bi-arrow-left"></i>
                </button>
            </div>
        </form>
    </div>

    <div class="container mt-4">

        @if (request()->has('search'))
            @php
                $keyword = request('search');
                $filteredMedicines = $medicines->filter(function ($medicine) {
                    return $medicine->suppliers->where('pivot.is_available', 1)->count() > 0;
                });
            @endphp

            <h5 class="mb-3 text-end" dir="rtl">
                @if ($filteredMedicines->isNotEmpty())
                    عدد نتائج البحث عن <b class="text-primary">{{ $keyword }}</b>
                    <span class="badge bg-primary">{{ $filteredMedicines->count() }} </span>
                @else
                    لا توجد نتائج
                @endif
            </h5>


            @if ($filteredMedicines->isNotEmpty())
                <div class="list-group shadow-sm rounded">
                    @foreach ($filteredMedicines as $medicine)
                        @php
                            $name = $medicine->type;
                            $composition = $medicine->composition;

                            if ($keyword) {
                                $escapedKeyword = preg_quote($keyword, '/');
                                $pattern = "/($escapedKeyword)/i";

                                $name = preg_replace($pattern, '<span class="highlight">$1</span>', $name);
                                $composition = preg_replace(
                                    $pattern,
                                    '<span class="highlight">$1</span>',
                                    $composition,
                                );
                            }
                        @endphp

                        <a href="{{ route('medicines.show', $medicine) }}"
                            class="medicine-row list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                            <div>
                                {!! $name !!} التركيب: {!! $composition !!}
                            </div>
                        </a>
                    @endforeach
                </div>

                <div class="d-flex justify-content-center mt-3 mb-4" id="medicines-pagination-links">
                    {{ $medicines->links() }}
                </div>
            @else
                <p class="mt-3 text-danger">لا توجد أدوية متاحة حاليًا.</p>
            @endif
        @endif
    </div>

@endsection
