{{-- resources/views/medicine/admin/_medicines_admin_table_rows.blade.php --}}

@forelse ($medicines as $k => $medicine)
    <tr>
        <td>{{ ($medicines->currentPage() - 1) * $medicines->perPage() + $loop->index + 1 }}</td> {{-- For row numbering --}}
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
        <td class="editable-type-ar" data-medicine-id="{{ $medicine->id }}">
            <span class="editable-text">{{ $medicine->type_ar ?? '—' }}</span>
            <input type="text" class="edit-input form-control" value="{{ $medicine->type_ar }}"
                style="display:none; width: 150px;" />
        </td>

        <td>{{ $medicine->composition }}</td>
        <td>{{ $medicine->form }}</td>
        <td>{{ $medicine->company }}</td>
        <td>{{ $medicine->note }}</td>
        <td>{{ $medicine->description }}</td>
        <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}</td>
        <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}</td>
        <td class="toggle-new-status" data-medicine-id="{{ $medicine->id }}" style="cursor:pointer;">
            @if ($medicine->is_new)
                <span class="badge bg-success">جديد</span>
            @else
                <span class="badge bg-danger">غير جديد</span>
            @endif
        </td>


    </tr>
@empty
    <tr>
        <td class="text-center">لا توجد أدوية مطابقة لبحثك.</td>
    </tr>
@endforelse
