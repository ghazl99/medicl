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
        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
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

        <div class="card-header d-flex justify-content-between align-items-center">
            <h3 class="text-right">إدارة الأصناف</h3>
            <a href="{{ route('category.create') }}" class="btn btn-primary">إضافة صنف</a>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-bordered text-right" id="categories-datatable" dir="rtl">
                    <thead class="text-right">
                        <tr>
                            <th>الاسم</th>
                            <th>صورة</th>
                            <th>الإجراءات</th>
                        </tr>
                    </thead>
                    <tbody id="medicines-table-body">
                        @foreach ($categories as $k => $category)
                            <tr>

                                <td>{{ $category->name }}</td>
                                <td>
                                    @php
                                        $media = $category->getFirstMedia('category_images');
                                    @endphp
                                    @if ($media)
                                        <img src="{{ route('category.image', $media->id) }}" class="myImg" alt="صورة الصنف"
                                            style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px; cursor:pointer;">
                                    @else
                                        <span class="text-muted">لا توجد صورة</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('category.edit', $category->id) }}"
                                        class="btn btn-sm btn-warning">تعديل</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="d-flex justify-content-center mt-4">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>
        <!-- The Modal -->
        <div id="myModal" class="modal">

            <!-- The Close Button -->
            <span class="close" style="color: white">&times;</span>

            <!-- Modal Content (The Image) -->
            <img class="modal-content" id="img01">

            <!-- Modal Caption (Image Text) -->
            <div id="caption"></div>
        </div>
    @endsection

    @section('scripts')
        <script>
            $(document).ready(function() {
                $('#categories-datatable').DataTable({
                    paging: false,
                    searching: true,
                    ordering: true,
                    info: false,
                    pageLength: 10,
                });

                // جلب عناصر الصور
                const modal = document.getElementById("myModal");
                const modalImg = document.getElementById("img01");
                const captionText = document.getElementById("caption");
                const closeBtn = document.getElementsByClassName("close")[0];

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
