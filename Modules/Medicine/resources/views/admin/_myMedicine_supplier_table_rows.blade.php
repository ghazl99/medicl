@foreach ($medicines as $k => $medicine)
    <tr>
        <td>{{ $k + 1 }}</td>
        <td>{{ $medicine->category ? $medicine->category->name : 'غير محدد' }}</td>

        <td>
            @php
                $media = $medicine->getFirstMedia('medicine_images');
            @endphp
            @if ($media)
                <img src="{{ route('medicines.image', $media->id) }}" class="myImg" alt="صورة الدواء"
                    style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor:pointer;">
            @else
                <span>لا توجد صورة</span>
            @endif
        </td>

        <td>{{ $medicine->type }}</td>
        <td>{{ $medicine->type_ar }}</td>
        <td>{{ $medicine->composition }}</td>
        <td>{{ $medicine->form }}</td>
        <td>{{ $medicine->company }}</td>

        <!-- ملاحظات -->
        <td class="note-cell" data-id="{{ $medicine->pivot->id }}">
            <span class="editable-text">{{ $medicine->pivot->notes ?? ' ' }}</span>
        </td>

        <td class="net-cell" data-id="{{ $medicine->pivot->id }}">
            <span class="editable-text">{{ number_format($medicine->pivot->price, 2) }}</span>
        </td>

        <td class="text-center">
            <div class="mb-2">
                @if ($medicine->pivot && $medicine->pivot->is_available)
                    <span class="badge bg-primary">متوفر</span>
                @else
                    <span class="badge bg-danger">غير متوفر</span>
                @endif
            </div>

            <form action="{{ route('medicines.toggle-availability', $medicine->id) }}" method="POST"
                style="display:inline-block;">
                @csrf
                @if ($medicine->pivot && $medicine->pivot->is_available)
                    <button type="submit" class="addMore btn btn-sm btn-outline-danger" title="تعطيل دواء">
                        <i class="bi bi-x-circle"></i>
                    </button>
                @else
                    <button type="submit" class="addMore btn btn-sm btn-outline-primary" title="تفعيل دواء">
                        <i class="bi bi-check-circle"></i>
                    </button>
                @endif
            </form>
        </td>

        <td class="offer-cell" data-id="{{ $medicine->pivot->id ?? 'N/A' }}">
            <span class="offer-text">
                @if ($medicine->pivot->offer_qty && $medicine->pivot->offer_free_qty)
                    {{ $medicine->pivot->offer_qty }}  + {{ $medicine->pivot->offer_free_qty }} 
                @else
                @endif
            </span>
        </td>


    </tr>
@endforeach
