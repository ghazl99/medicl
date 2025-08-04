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
         <td>{{ $medicine->note }}</td>
         <!--<td>{{ $medicine->net_dollar_old !== null ? number_format($medicine->net_dollar_old, 2) : '-' }}-->
         <!--</td>-->
         <!--<td>{{ $medicine->public_dollar_old !== null ? number_format($medicine->public_dollar_old, 2) : '-' }}-->
         <!--</td>-->
         <td>{{ $medicine->net_dollar_new !== null ? number_format($medicine->net_dollar_new, 2) : '-' }}
         </td>
         <td>{{ $medicine->public_dollar_new !== null ? number_format($medicine->public_dollar_new, 2) : '-' }}
         </td>
         <!--<td>{{ $medicine->net_syp !== null ? number_format($medicine->net_syp, 2) : '-' }}</td>-->
         <!--<td>{{ $medicine->public_syp !== null ? number_format($medicine->public_syp, 2) : '-' }}-->
         <!--</td>-->
         <!--<td>{{ $medicine->note_2 }}</td>-->
         <!--<td>{{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}-->
         <!--</td>-->
         <td class="text-center">
             <div class="mb-2">
                 @if ($medicine->pivot->is_available ?? false)
                     <span class="badge bg-primary">متوفر</span>
                 @else
                     <span class="badge bg-danger">غير متوفر</span>
                 @endif
             </div>

             <form action="{{ route('medicines.toggle-availability', $medicine->id) }}" method="POST"
                 style="display:inline-block;">
                 @csrf
                 @if ($medicine->pivot->is_available ?? false)
                     <button type="submit" class="addMore btn btn-sm btn-outline-danger" title="تعطيل دواء"><i
                             class="bi bi-x-circle"></i></button>
                 @else
                     <button type="submit" class="addMore btn btn-sm btn-outline-primary" title="تفعيل دواء"><i
                             class="bi bi-check-circle"></i></button>
                 @endif
             </form>
         </td>
         <td>
             <a href="{{ route('offers.create', $medicine->id) }}" class="btn btn-sm btn-warning">
                 <i class="fas fa-tags"></i> عرض
             </a>
         </td>
     </tr>
 @endforeach
