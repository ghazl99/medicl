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
        <td>{{ $medicine->composition }}</td>
        <td>{{ $medicine->form }}</td>
        <td>{{ $medicine->company }}</td>

        <td class="note-cell" data-id="{{ $medicine->pivot->id ?? 'pivot id not set' }}">
            <span class="note-text" style="min-height:1.5em; display:inline-block; cursor:pointer;">
                {{ $medicine->pivot->notes ?? '' }}
            </span>
            <textarea class="form-control note-input d-none" rows="2" placeholder="أدخل ملاحظة هنا">{{ $medicine->pivot->notes ?? '' }}</textarea>
        </td>

        <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}</td>
        <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}</td>

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
            <span class="offer-text">{{ $medicine->pivot->offer ?? '' }}</span>
            <input type="text" class="form-control offer-input d-none" value="{{ $medicine->pivot->offer ?? '' }}">
        </td>
    </tr>
@endforeach
