@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Sửa sản phẩm</h2>
    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách</a>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Tên sản phẩm -->
                <div class="mb-3">
                    <label for="name" class="form-label">Tên sản phẩm</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ $product->name }}" required>
                </div>

                <!-- Slug -->
                <div class="mb-3">
                    <label for="slug" class="form-label">Slug</label>
                    <input type="text" name="slug" id="slug" class="form-control" value="{{ $product->slug }}" readonly>
                </div>

                <!-- Giá -->
                <div class="mb-3">
                    <label for="price" class="form-label">Giá</label>
                    <input type="number" name="price" id="price" class="form-control" value="{{ $product->price }}" required>
                </div>

                <!-- Kho -->
                <div class="mb-3">
                    <label for="stock" class="form-label">Kho</label>
                    <input type="number" name="stock" id="stock" class="form-control" value="{{ $product->stock }}">
                </div>

                <!-- Ảnh sản phẩm -->
                <div class="mb-3">
                    <label for="images" class="form-label">Ảnh sản phẩm</label>
                    <input type="file" name="images[]" id="images" class="form-control" multiple>

                    <!-- Hiển thị ảnh cũ -->
                    <div id="existingImages" style="margin-top:10px;">
                        @if($product->images)
                            @foreach($product->images as $img)
                                <div class="img-wrapper" style="display:inline-block; position:relative; margin-right:5px; margin-bottom:5px;">
                                    <img src="{{ asset('storage/'.$img) }}" style="max-width:100px; height:100px; object-fit:cover; display:block; border:1px solid #ccc; border-radius:5px;">
                                    <span class="remove-image" style="position:absolute; top:0; right:0; cursor:pointer; background:red; color:white; padding:2px 5px; font-weight:bold;">&times;</span>
                                    <input type="hidden" name="existing_images[]" value="{{ $img }}">
                                </div>
                            @endforeach
                        @endif
                    </div>

                    <!-- Preview ảnh mới -->
                    <div id="previewImages" style="margin-top:10px;"></div>
                </div>

                <!-- Danh mục -->
                <div class="mb-3">
                    <label for="category_id" class="form-label">Danh mục</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">-- Không có danh mục --</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Thương hiệu -->
                <div class="mb-3">
                    <label for="brand" class="form-label">Thương hiệu</label>
                    <input type="text" name="brand" id="brand" class="form-control" value="{{ $product->brand }}">
                </div>

                <!-- Trạng thái -->
                <div class="form-check mb-3">
                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                    <label class="form-check-label" id="is_active_label" for="is_active">{{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}</label>
                </div>

                <button type="submit" class="btn btn-primary">Cập nhật</button>
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
    const isActiveCheckbox = document.getElementById('is_active');
    const isActiveLabel = document.getElementById('is_active_label');
    isActiveCheckbox.addEventListener('change', function() {
        isActiveLabel.innerText = this.checked ? 'Hiển thị' : 'Ẩn';
    });

    // Preview nhiều ảnh mới
    const imagesInput = document.getElementById('images');
    const previewImages = document.getElementById('previewImages');
    imagesInput.addEventListener('change', function() {
        previewImages.innerHTML = '';
        Array.from(this.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                img.style.maxWidth = '100px';
                img.style.height = '100px';
                img.style.objectFit = 'cover';
                img.style.marginRight = '5px';
                img.style.marginBottom = '5px';
                previewImages.appendChild(img);
            }
            reader.readAsDataURL(file);
        });
    });

    // Xóa ảnh cũ khi click dấu x
    document.querySelectorAll('#existingImages .remove-image').forEach(btn => {
        btn.addEventListener('click', function() {
            const wrapper = this.parentElement;
            wrapper.remove();
        });
    });
</script>
@endsection
