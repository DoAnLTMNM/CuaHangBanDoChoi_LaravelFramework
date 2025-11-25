@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Thêm sản phẩm mới</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách</a>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Tên sản phẩm -->
                <div class="mb-3">
                    <label for="name">Tên sản phẩm</label>
                    <input type="text" name="name" id="name" class="form-control" required>
                </div>

                <!-- Slug tự động -->
                <div class="mb-3">
                    <label for="slug">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" readonly>
                </div>

                <!-- Giá -->
                <div class="mb-3">
                    <label for="price">Giá</label>
                    <input type="number" name="price" id="price" class="form-control" required>
                </div>

                <!-- Kho -->
                <div class="mb-3">
                    <label for="stock">Kho</label>
                    <input type="number" name="stock" id="stock" class="form-control">
                </div>

                <!-- Ảnh sản phẩm -->
                <div class="mb-3">
                    <label for="images">Ảnh sản phẩm</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>
                    <div id="previewImages" class="d-flex flex-wrap mt-2"></div>
                </div>

                <!-- Danh mục -->
                <div class="mb-3">
                    <label for="category_id">Danh mục</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">-- Không có danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Trạng thái -->
                <div class="form-check mb-3">
                    <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1" checked>
                    <label class="form-check-label" for="is_active" id="is_active_label">Hiển thị</label>
                </div>

                <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
            </form>
        </div>
    </div>
</div>

<script>
    // Slug tự động
    const nameInput = document.getElementById('name');
    const slugInput = document.getElementById('slug');
    nameInput.addEventListener('keyup', function() {
        slugInput.value = this.value.toLowerCase().replace(/ /g,'-').replace(/[^\w-]+/g,'');
    });

    // Checkbox hiển thị/ẩn
    const isActiveInput = document.getElementById('is_active');
    const isActiveLabel = document.getElementById('is_active_label');
    isActiveInput.addEventListener('change', () => {
        isActiveLabel.innerText = isActiveInput.checked ? 'Hiển thị' : 'Ẩn';
    });

    // Preview nhiều ảnh và xóa preview
    const imagesInput = document.getElementById('images');
    const previewImages = document.getElementById('previewImages');

    imagesInput.addEventListener('change', function() {
        previewImages.innerHTML = '';
        Array.from(this.files).forEach((file, index) => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const div = document.createElement('div');
                div.style.position = 'relative';
                div.style.marginRight = '10px';
                div.style.marginBottom = '10px';

                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.width = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.border = '1px solid #ccc';
                img.style.borderRadius = '5px';
                div.appendChild(img);

                const btn = document.createElement('button');
                btn.type = 'button';
                btn.innerHTML = '&times;';
                btn.style.position = 'absolute';
                btn.style.top = '0';
                btn.style.right = '0';
                btn.style.background = 'red';
                btn.style.color = 'white';
                btn.style.border = 'none';
                btn.style.borderRadius = '50%';
                btn.style.width = '20px';
                btn.style.height = '20px';
                btn.style.cursor = 'pointer';
                btn.addEventListener('click', () => {
                    div.remove();
                    // Xóa file trong input.files (khó, nên gửi js object riêng nếu muốn)
                });
                div.appendChild(btn);

                previewImages.appendChild(div);
            }
            reader.readAsDataURL(file);
        });
    });
</script>
@endsection
