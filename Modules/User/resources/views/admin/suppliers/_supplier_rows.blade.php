@forelse ($suppliers as $supplier)
                    <tr>
                        <td>{{ $supplier->name }}</td>
                        <td>{{ $supplier->phone }}</td>
                        <td>{{ $supplier->workplace_name }}</td>
                        <td>{{ $supplier->city }}</td>
                        <td>
                            <a href="{{ route('users.edit', $supplier->id) }}"
                                class="btn btn-sm btn-outline-primary me-1">تعديل</a>
                            {{-- Form for delete if needed --}}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">لا توجد موردين متاحين.</td>
                    </tr>
                @endforelse
