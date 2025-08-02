@extends('core::components.layouts.master')
@section('css')
    <style>
        /* Style the Image Used to Trigger the Modal */
        #myImg {
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        #myImg:hover {
            opacity: 0.7;
        }

        /* The Modal (background) */
        .modal {
            display: none;
            /* Hidden by default */
            position: fixed;
            /* Stay in place */
            z-index: 1;
            /* Sit on top */
            padding-top: 100px;
            /* Location of the box */
            left: 0;
            top: 0;
            width: 100%;
            /* Full width */
            height: 100%;
            /* Full height */
            overflow: auto;
            /* Enable scroll if needed */
            background-color: rgb(0, 0, 0);
            /* Fallback color */
            background-color: rgba(0, 0, 0, 0.9);
            /* Black w/ opacity */
        }

        /* Modal Content (Image) */
        .modal-content {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        /* Caption of Modal Image (Image Text) - Same Width as the Image */
        #caption {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
            text-align: center;
            color: #ccc;
            padding: 10px 0;
            height: 150px;
        }

        /* Add Animation - Zoom in the Modal */
        .modal-content,
        #caption {
            animation-name: zoom;
            animation-duration: 0.6s;
        }

        @keyframes zoom {
            from {
                transform: scale(0)
            }

            to {
                transform: scale(1)
            }
        }

        /* The Close Button */
        .close_myModal {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close_myModal:hover,
        .close_myModal:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* 100% Image Width on Smaller Screens */
        @media only screen and (max-width: 700px) {
            .modal-content {
                width: 100%;
            }
        }
    </style>
@endsection
@section('content')
    <br>
    <div class="card">
        @role('مورد')
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">إدارة الأدوية</h3>
                <a href="{{ route('medicines.create') }}" class="btn btn-primary">إضافة دواء</a>
            </div>
            <div class="card-body">
                <div class="mb-3 text-right">
                    <form action="{{ route('medicines.index') }}" method="GET" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                            <div class="col-auto mb-2">
                                <button type="submit" class="btn btn-primary w-100">بحث</button>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="table-responsive">
                    <form id="medicines-selection-form" method="POST" action="{{ route('checked-medicine') }}">
                        @csrf
                        <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                            <thead class="text-right">
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th> {{-- تحديد الكل --}}
                                    <th>النوع</th>
                                    <th>صورة المنتج</th>
                                    <th>الصنف</th>
                                    <th>التركيب</th>
                                    <th>الشكل</th>
                                    <th>الشركة</th>
                                    <th>ملاحظات</th>
                                    <!--<th>نت دولار حالي</th>-->
                                    <!--<th>عموم دولار حالي</th>-->
                                    <th>النت دولار </th>
                                    <th>العموم دولار </th>
                                    <!--<th>نت سوري</th>-->
                                    <!--<th>عموم سوري</th>-->
                                    <!--<th>ملاحظات 2</th>-->
                                    <!--<th>نسبة تغير السعر</th>-->
                                </tr>
                            </thead>
                            <tbody id="medicines-table-body">
                                @foreach ($medicines as $k => $medicine)
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
                                                <img src="{{ route('medicines.image', $media->id) }}" class="myImg"
                                                    alt="صورة الدواء"
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
                                        <!--<td>-->
                                        <!--    {{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}-->
                                        <!--</td>-->
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <div class="d-flex justify-content-center mt-4">
                            {{ $medicines->links() }}
                        </div>

                        <div class="text-left mt-3">
                            <button type="submit" class="btn btn-success">إضافة الأدوية المحددة للمورد</button>
                        </div>
                        <br>
                    </form>
                </div>
            </div>
        @else
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="text-right">جميع الأدوية</h3>
                @role('المشرف')
                    <div class="d-flex gap-2 flex-wrap">
                        <a href="{{ route('medicines.create') }}" class="btn btn-primary mr-1">إضافة دواء</a>
                        <button type="button" class="btn btn-success" data-toggle="modal" data-target="#importModal">
                            استيراد ملف إكسل
                        </button>
                    </div>
                @endrole
            </div>
            <div class="card-body">
                <div class="mb-3 text-right">
                    <form action="{{ route('medicines.index') }}" method="GET" class="mb-3">
                        <div class="row justify-content-start">
                            <div class="col-md-4 col-sm-6 mb-2">
                                <input type="text" name="search" value="{{ request('search') }}"
                                    placeholder="ابحث عن دواء..." class="form-control" />
                            </div>
                            <div class="col-auto mb-2">
                                <button type="submit" class="btn btn-primary w-100">بحث</button>
                            </div>
                        </div>
                    </form>

                </div>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered text-right" id="medicines-datatable" dir="rtl">
                        <thead class="text-right">
                            <tr>
                                <th>ID</th>
                                <th>النوع</th>
                                <th>صورة المنتج</th>
                                <th>الصنف</th>
                                <th>التركيب</th>
                                <th>الشكل</th>
                                <th>الشركة</th>
                                <th>ملاحظات</th>
                                <!--<th>نت دولار حالي</th>-->
                                <!--<th>عموم دولار حالي</th>-->
                                <th>النت دولار </th>
                                <th>العموم دولار </th>
                                <!--<th>نت سوري</th>-->
                                <!--<th>عموم سوري</th>-->
                                <!--<th>ملاحظات 2</th>-->
                                <!--<th>نسبة تغير السعر</th>-->
                            </tr>
                        </thead>
                        <tbody id="medicines-table-body">
                            @foreach ($medicines as $k => $medicine)
                                <tr>
                                    <td>{{ $k + 1 }}</td>
                                    <td>{{ $medicine->category ? $medicine->category->name : 'غير محدد' }}</td>

                                    <td>
                                        @php
                                            $media = $medicine->getFirstMedia('medicine_images');
                                        @endphp
                                        @if ($media)
                                            <img src="{{ route('medicines.image', $media->id) }}" class="myImg"
                                                alt="صورة الدواء"
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
                                    <!--<td>-->
                                    <!--    {{ $medicine->price_change_percentage !== null ? number_format($medicine->price_change_percentage, 2) . '%' : '-' }}-->
                                    <!--</td>-->
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="d-flex justify-content-center mt-4">
                        {{ $medicines->links() }}
                    </div>

                </div>
            </div>
        @endrole
    </div>

    <!-- Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form action="{{ route('medicines.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="importModalLabel">استيراد ملف إكسل</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="إغلاق">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>

                    <div class="modal-body">
                        <div class="form-group">
                            <label for="file">اختر ملف الإكسل للاستيراد:</label>
                            <input type="file" name="file" id="file" accept=".xls,.xlsx,.csv"
                                class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">إغلاق</button>
                        <button type="submit" class="btn btn-primary">استيراد</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- The Modal -->
    <div id="myModal" class="modal">

        <!-- The Close Button -->
        <span class="close_myModal" style="color: white">&times;</span>

        <!-- Modal Content (The Image) -->
        <img class="modal-content" id="img01">

        <!-- Modal Caption (Image Text) -->
        <div id="caption"></div>
    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#medicines-datatable').DataTable({
                paging: false,
                searching: false,
                ordering: true,
                info: false,


            });

            // تحديد الكل
            $('#select-all').click(function() {
                $('input[name="medicines[]"]').prop('checked', this.checked);
            });
            // جلب عناصر الصور
            const modal = document.getElementById("myModal");
            const modalImg = document.getElementById("img01");
            const captionText = document.getElementById("caption");
            const closeBtn = document.getElementsByClassName("close_myModal")[0];

            // تحديد كل الصور ذات الكلاس myImg
            const images = document.querySelectorAll('.myImg');

            images.forEach(img => {
                img.onclick = function() {
                    modal.style.display = "block";
                    modalImg.src = this.src;
                    captionText.innerHTML = this.alt || "";
                }
            });

            // زر الإغلاق
            closeBtn.onclick = function() {
                modal.style.display = "none";
            }

            // إغلاق المودال إذا ضغط المستخدم خارج الصورة
            modal.onclick = function(event) {
                if (event.target === modal) {
                    modal.style.display = "none";
                }
            }
        });
    </script>
@endsection
