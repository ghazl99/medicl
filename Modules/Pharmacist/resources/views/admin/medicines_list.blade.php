@if ($medicines->isNotEmpty())
    @foreach ($medicines as $medicine)
        <div class="card h-100 mb-2">
            @php
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
                class="medicine-row list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                    <strong>{!! $name !!}</strong><br>
                    <small class="text-muted">التركيب: {!! $composition !!}</small>
                </div>
            </a>
        </div>
    @endforeach

    <div class="d-flex justify-content-center mt-3 mb-4" id="medicines-pagination-links">
        {!! $medicines->links() !!}
    </div>
@else
@endif
