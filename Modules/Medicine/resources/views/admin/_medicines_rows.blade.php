@forelse ($medicines as $medicine)
    <tr>
        <td>{{ $medicine->name }}</td>
        <td>{{ $medicine->manufacturer }}</td>
        <td>{{ $medicine->quantity_available }}</td>
        <td>{{ $medicine->price }}</td>
        <td>{{ $medicine->created_at->format('Y-m-d') }}</td>
        <td>
            <a href="{{ route('medicines.edit', $medicine->id) }}"
                class="btn btn-sm btn-outline-primary me-1">تعديل</a>
            {{-- إذا أردت إضافة زر حذف، فك تعليق هذا الجزء واضبط المسار --}}
            {{-- <form action="{{ route('medicines.destroy', $medicine->id) }}" method="POST"
                style="display:inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger"
                    onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
            </form> --}}
        </td>
    </tr>
@empty
    <tr>
        <td colspan="5" class="text-center">لا توجد أدوية متاحة.</td>
    </tr>
@endforelse
