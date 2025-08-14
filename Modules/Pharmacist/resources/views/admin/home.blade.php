@extends('pharmacist::components.layouts.master')
@section('css')
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
        }

        .medicine-row {
            padding: 8px 0;
            border-bottom: 1px solid #ddd;
        }
    </style>
@endsection

@section('content')
    <div class="container mt-4">
        <h3>بحث عن الأدوية</h3>

        <form action="{{ route('pharmacist.home') }}" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="اكتب اسم الدواء أو الصنف"
                    value="{{ request('search') }}">
                <button class="btn btn-primary" type="submit">بحث</button>
            </div>
        </form>

        @if (request()->has('search'))
            @if ($medicines->isEmpty())
                <p>لا يوجد دواء متوفر.</p>
            @else
                @php
                    $filteredMedicines = $medicines->filter(function ($medicine) {
                        return $medicine->suppliers->where('pivot.is_available', 1)->count() > 0;
                    });
                @endphp

                @if ($filteredMedicines->isNotEmpty())
                    <div class="list-group">
                        @foreach ($filteredMedicines as $medicine)
                            @php
                                $keyword = request('search');
                                $name = $medicine->type;
                                $composition = $medicine->composition;

                                if ($keyword) {
                                    $escapedKeyword = preg_quote($keyword, '/');
                                    $pattern = "/($escapedKeyword)/i";

                                    $name = preg_replace($pattern, '<span class="highlight">$1</span>', $name);
                                    $composition = preg_replace($pattern, '<span class="highlight">$1</span>', $composition);
                                }
                            @endphp

                            <a href="{{ route('medicines.show', $medicine) }}"
                                class="medicine-row d-flex align-items-center list-group-item list-group-item-action text-black">
                                <div>
                                    {!! $name !!} - التركيب: {!! $composition !!}
                                </div>
                            </a>
                        @endforeach
                    </div>

                    <div class="d-flex justify-content-center mt-4" id="medicines-pagination-links">
                        {{ $medicines->links() }}
                    </div>
                @else
                    <p class="mt-3">لا يوجد أدوية متاحة حاليًا.</p>
                @endif
            @endif
        @endif
    </div>
@endsection
