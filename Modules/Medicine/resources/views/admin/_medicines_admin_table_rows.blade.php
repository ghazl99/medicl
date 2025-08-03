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
        <td>{{ $medicine->composition }}</td>
        <td>{{ $medicine->form }}</td>
        <td>{{ $medicine->company }}</td>
        <td>{{ $medicine->note }}</td>
        <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}</td>
        <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}</td>
        <td class="text-center">
            {{-- Assuming 'pivot' is available if medicines are fetched via supplier relationship.
                 If this is a global list, medicine->pivot->is_available won't exist directly.
                 You might need to adjust how availability is shown for admin. --}}
            @if (Auth::user()->hasRole('مورد'))
                 <div class="mb-2">
                    @if ($medicine->pivot->is_available ?? false)
                        <span class="badge bg-primary">متوفر</span>
                    @else
                        <span class="badge bg-danger">غير متوفر</span>
                    @endif
                </div>
                <form action="{{ route('medicines.toggle-availability', $medicine->id) }}" method="POST" style="display:inline-block;">
                    @csrf
                    @if ($medicine->pivot->is_available ?? false)
                        <button type="submit" class="addMore btn btn-sm btn-outline-danger" title="تعطيل دواء"><i class="bi bi-x-circle"></i></button>
                    @else
                        <button type="submit" class="addMore btn btn-sm btn-outline-primary" title="تفعيل دواء"><i class="bi bi-check-circle"></i></button>
                    @endif
                </form>
            @else
                {{-- If this is the admin view, you might show global availability or no availability column --}}
                <span class="badge bg-info">غير مطبق</span> {{-- Or remove this column if not relevant --}}
            @endif
        </td>
        <td>
            {{-- Add edit/delete actions for admin here if needed --}}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="11" class="text-center">لا توجد أدوية مطابقة لبحثك.</td>
    </tr>
@endforelse
