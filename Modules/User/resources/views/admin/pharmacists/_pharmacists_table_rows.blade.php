 @forelse ($pharmacists as $pharmacist)
     <tr>
         <td>{{ $pharmacist->name }}</td>
         <td>{{ $pharmacist->phone }}</td>
         <td>{{ $pharmacist->workplace_name }}</td>
         <td>{{ $pharmacist->city }}</td>
         <td>
             @if ($pharmacist->is_approved)
                 <span class="badge bg-success">معتمد</span>
             @else
                 <span class="badge bg-danger">غير معتمد</span>
             @endif
         </td>
         <td>
             <a href="{{ route('users.edit', $pharmacist->id) }}" class="btn btn-sm btn-outline-primary me-1">تعديل</a>
             {{-- إذا أردت إضافة زر حذف، فك تعليق هذا الجزء واضبط المسار --}}
             {{-- <form action="{{ route('pharmacists.destroy', $pharmacist->id) }}" method="POST"
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
         <td colspan="5" class="text-center">لا توجد صيادلة متاحون.</td>
     </tr>
 @endforelse
