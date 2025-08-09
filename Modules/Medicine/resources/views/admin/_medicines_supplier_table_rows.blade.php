{{-- resources/views/medicine/admin/_medicines_supplier_table_rows.blade.php --}}

@forelse ($medicines as $k => $medicine)
    <tr>
        <td>
            <input type="checkbox" name="medicines[]" value="{{ $medicine->id }}"
                @if (in_array($medicine->id, $supplierMedicineIds)) checked @endif>
        </td>
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
        <td>{{ $medicine->note }}</td>
        <td>{{ $medicine->description }}</td>
        <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}</td>
        <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}</td>

    </tr>
@empty
    <tr>
        <td colspan="10" class="text-center">لا توجد أدوية مطابقة لبحثك.</td>
    </tr>
@endforelse
