@if ($medicines->isNotEmpty())
    @foreach ($medicines as $medicine)
        <div class="card h-100 mb-2">
            @php
                $name = $medicine->type;
                $name_ar = $medicine->type_ar;
                $composition = $medicine->composition;
                $company=$medicine->company;

                if ($keyword) {
                    $escapedKeyword = preg_quote($keyword, '/');
                    $pattern = "/($escapedKeyword)/i";

                    $name = preg_replace($pattern, '<span class="highlight">$1</span>', $name);
                    $name_ar = preg_replace($pattern, '<span class="highlight">$1</span>', $name_ar);
                    $company = preg_replace($pattern, '<span class="highlight">$1</span>', $company);
                    $composition = preg_replace($pattern, '<span class="highlight">$1</span>', $composition);
                }
            @endphp

            <a href="{{ route('medicines.show', $medicine) }}"
                class="medicine-row list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                    <strong>{!! $name !!}</strong> {!! $name_ar !!}<br>
                    <small class="text-muted">التركيب: {!! $composition !!}</small><br>
                    <small class="text-muted">{!! $company !!}</small>
                </div>
            </a>
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-3 mb-4" id="medicines-pagination-links">
        {!! $medicines->links() !!}
    </div>
@else
@endif
