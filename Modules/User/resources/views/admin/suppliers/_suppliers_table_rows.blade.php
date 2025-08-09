@forelse ($suppliers as $supplier)
    <tr>
        {{-- <td>{{ $supplier->name }}</td> --}}
        <td>{{ $supplier->phone }}</td>
        <td>{{ $supplier->workplace_name }}</td>
        <td>{{ $supplier->cities->pluck('name')->implode(', ') }}</td>
        <td>
            @if ($supplier->is_approved)
                <span class="badge bg-success">معتمد</span>
            @else
                <span class="badge bg-danger">غير معتمد</span>
            @endif
        </td>
        <td>
            <a href="{{ route('users.edit', $supplier->id) }}" class="btn btn-sm btn-outline-primary me-1">تعديل</a>
            {{-- Form for delete if needed --}}
            {{-- <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST"
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
        {{-- Adjust colspan based on the number of columns in your table (6 in this case) --}}
        <td class="text-center">لا توجد موردين متاحين.</td>
    </tr>
@endforelse
