@extends('core::components.layouts.master')

@section('content')
    <br>
    <div class="card">
        <div class="card-body">
            <h2 class="mb-4" style="color: var(--main-color); font-weight: 700;">إضافة عرض جديد</h2>
            <form action="{{ route('offers.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="medicine_user_id" value="{{ $medicineUser->id }}">

                <div class="form-group">
                    <label>عنوان العرض</label>
                    <input type="text" name="title" class="form-control" required>
                </div>

                <div class="form-group">
                    <label>تفاصيل العرض</label>
                    <textarea name="details" class="form-control" required></textarea>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label>تاريخ البداية</label>
                        <input type="date" name="offer_start_date" class="form-control" required>
                    </div>
                    <div class="form-group col-md-6">
                        <label>تاريخ الانتهاء</label>
                        <input type="date" name="offer_end_date" class="form-control" required>
                    </div>
                </div>

                {{-- حقل رفع صور متعددة --}}
                <div class="form-group">
                    <label>صور العرض</label>
                    <input type="file" name="images[]" class="form-control" multiple accept="image/*" id="imagesInput">
                </div>

                {{-- مكان عرض الصور المختارة --}}
                <div id="previewImages" style="display: flex; gap: 10px; flex-wrap: wrap;"></div>

                <button type="submit" class="btn btn-success mt-3">حفظ العرض</button>
            </form>

        </div>
    </div>
@endsection
@section('scripts')
<script>
    let selectedFiles = [];

    document.getElementById('imagesInput').addEventListener('change', function (e) {
        const files = Array.from(e.target.files);

        files.forEach(file => {
            if (!file.type.startsWith('image/')) return;

            selectedFiles.push(file);

            const reader = new FileReader();
            reader.onload = function (event) {
                const imgWrapper = document.createElement('div');
                imgWrapper.style.position = 'relative';

                const img = document.createElement('img');
                img.src = event.target.result;
                img.style.width = '120px';
                img.style.height = '120px';
                img.style.objectFit = 'cover';
                img.style.border = '1px solid #ccc';
                img.style.borderRadius = '5px';

                const removeBtn = document.createElement('span');
                removeBtn.innerHTML = '&times;';
                removeBtn.style.position = 'absolute';
                removeBtn.style.top = '5px';
                removeBtn.style.right = '5px';
                removeBtn.style.background = 'rgba(0,0,0,0.5)';
                removeBtn.style.color = '#fff';
                removeBtn.style.cursor = 'pointer';
                removeBtn.style.padding = '2px 5px';
                removeBtn.style.borderRadius = '50%';
                removeBtn.onclick = function () {
                    imgWrapper.remove();
                    selectedFiles = selectedFiles.filter(f => f !== file);
                    updateFileInput();
                };

                imgWrapper.appendChild(img);
                imgWrapper.appendChild(removeBtn);
                document.getElementById('previewImages').appendChild(imgWrapper);
            };
            reader.readAsDataURL(file);
        });

        updateFileInput();
    });

    function updateFileInput() {
        const dataTransfer = new DataTransfer();
        selectedFiles.forEach(file => dataTransfer.items.add(file));
        document.getElementById('imagesInput').files = dataTransfer.files;
    }
</script>
@endsection
