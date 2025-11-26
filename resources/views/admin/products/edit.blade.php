@extends('admin.layouts.app')
@vite('resources/js/admin/product/edit.product.js')

@section('content')
    <div class="container mt-4">
        <h2>Sửa sản phẩm</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary mb-3">Quay lại danh sách</a>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <!-- Thông tin cơ bản -->
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" name="name" id="name" class="form-control"
                            value="{{ $product->name }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug</label>
                        <input type="text" name="slug" id="slug" class="form-control"
                            value="{{ $product->slug }}" readonly>
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">Giá</label>
                        <input type="number" name="price" id="price" class="form-control"
                            value="{{ $product->price }}" min="0" required>
                    </div>

                    <div class="mb-3">
                        <label for="stock" class="form-label">Kho</label>
                        <input type="number" name="stock" id="stock" class="form-control"
                            value="{{ $product->stock }}" min="0">
                    </div>

                    <!-- Ảnh sản phẩm -->
                    <div class="mb-3">
                        <label for="images" class="form-label">Ảnh sản phẩm</label>
                        <input type="file" name="images[]" id="images" class="form-control" multiple>

                        <div id="existingImages" style="margin-top:10px;">
                            @if ($product->images)
                                @foreach ($product->images as $img)
                                    <div class="img-wrapper"
                                        style="display:inline-block; position:relative; margin-right:5px; margin-bottom:5px;">
                                        <img src="{{ asset('storage/' . $img->image) }}"
                                            style="max-width:100px; height:100px; object-fit:cover; display:block; border:1px solid #ccc; border-radius:5px;">
                                        <span class="remove-existing"
                                            style="position:absolute; top:0; right:0; cursor:pointer; background:red; color:white; padding:2px 5px; font-weight:bold;">&times;</span>
                                        <input type="hidden" name="existing_images[]" value="{{ $img->image }}">
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div id="previewImages" style="margin-top:10px;"></div>
                    </div>

                    <!-- Danh mục -->
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select name="category_id" id="category_id" class="form-select">
                            <option value="">-- Không có danh mục --</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $product->category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Thương hiệu -->
                    <div class="mb-3">
                        <label for="brand" class="form-label">Thương hiệu</label>
                        <input type="text" name="brand" id="brand" class="form-control"
                            value="{{ $product->brand }}">
                    </div>

                    <!-- Mô tả -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea name="description" id="description" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>

                    <!-- Giảm giá -->
                    <div class="mb-3">
                        <h5>Giảm giá (nếu có)</h5>

                        <!-- Chọn kiểu giảm giá -->
                        <div class="mb-2">
                            <label class="form-label">Chọn loại giảm giá:</label>
                            <div class="btn-group" role="group" aria-label="Loại giảm giá">
                                <input type="radio" class="btn-check" name="discount_type" id="discount_percent_radio"
                                    value="percent" autocomplete="off"
                                    {{ isset($product->discount) && $product->discount->discount_percent !== null ? 'checked' : (!isset($product->discount) ? 'checked' : '') }}>
                                <label class="btn btn-outline-primary" for="discount_percent_radio">Giảm %</label>

                                <input type="radio" class="btn-check" name="discount_type" id="discount_amount_radio"
                                    value="amount" autocomplete="off"
                                    {{ isset($product->discount) && $product->discount->discount_amount !== null ? 'checked' : '' }}>
                                <label class="btn btn-outline-primary" for="discount_amount_radio">Giảm theo số
                                    tiền</label>
                            </div>
                        </div>

                        <!-- Input giảm % -->
                        <div class="mb-2 discount-input" id="discount_percent_div">
                            <label for="discount_percent" class="form-label">Phần trăm giảm (%)</label>
                            <input type="number" name="discount_percent" id="discount_percent" class="form-control"
                                value="{{ $product->discount->discount_percent ?? '' }}" min="0" max="100"
                                step="0.01">
                            <!-- Thẻ hiển thị giá giảm -->
                            <div class="mt-1" id="discount_preview" style="font-weight:bold;"></div>
                        </div>

                        <!-- Input giảm tiền -->
                        <div class="mb-2 discount-input" id="discount_amount_div">
                            <label for="discount_amount" class="form-label">Giảm theo số tiền</label>
                            <input type="number" name="discount_amount" id="discount_amount" class="form-control"
                                value="{{ $product->discount->discount_amount ?? '' }}" min="0" step="0.01">
                            <!-- Thẻ hiển thị giá giảm -->
                            <div class="mt-1" id="discount_preview_amount" style="font-weight:bold;"></div>
                        </div>

                        <!-- Trạng thái giảm giá -->
                        <div class="form-check mt-2">
                            <input type="checkbox" class="form-check-input" name="discount_is_active"
                                id="discount_is_active" value="1"
                                {{ isset($product->discount) && $product->discount->is_active ? 'checked' : '' }}>
                            <label class="form-check-label" for="discount_is_active">Kích hoạt giảm giá</label>
                        </div>

                        <!-- Ngày bắt đầu / kết thúc -->
                        <div class="row mt-2">
                            <div class="col-md-6">
                                <label for="discount_start_date" class="form-label">Ngày bắt đầu</label>
                                <input type="datetime-local" name="discount_start_date" id="discount_start_date"
                                    class="form-control"
                                    value="{{ isset($product->discount->start_date) ? \Carbon\Carbon::parse($product->discount->start_date)->format('Y-m-d\TH:i') : '' }}">
                            </div>
                            <div class="col-md-6">
                                <label for="discount_end_date" class="form-label">Ngày kết thúc</label>
                                <input type="datetime-local" name="discount_end_date" id="discount_end_date"
                                    class="form-control"
                                    value="{{ isset($product->discount->end_date) ? \Carbon\Carbon::parse($product->discount->end_date)->format('Y-m-d\TH:i') : '' }}">
                            </div>
                        </div>
                    </div>
                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const priceInput = document.getElementById('price');
                            const discountPercentInput = document.getElementById('discount_percent');
                            const discountAmountInput = document.getElementById('discount_amount');
                            const percentPreview = document.getElementById('discount_preview');
                            const amountPreview = document.getElementById('discount_preview_amount');

                            const percentDiv = document.getElementById('discount_percent_div');
                            const amountDiv = document.getElementById('discount_amount_div');

                            const percentRadio = document.getElementById('discount_percent_radio');
                            const amountRadio = document.getElementById('discount_amount_radio');

                            function formatCurrency(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(value);
                            }

                            function updatePercentPreview() {
                                const price = parseFloat(priceInput.value) || 0;
                                const percent = parseFloat(discountPercentInput.value) || 0;
                                if (percent > 0) {
                                    const discounted = price * (1 - percent / 100);
                                    percentPreview.innerHTML =
                                        `<span style="text-decoration: line-through;">${formatCurrency(price)}</span> → ${formatCurrency(discounted)}`;
                                } else {
                                    percentPreview.innerHTML = '';
                                }
                            }

                            function updateAmountPreview() {
                                const price = parseFloat(priceInput.value) || 0;
                                const amount = parseFloat(discountAmountInput.value) || 0;
                                if (amount > 0) {
                                    const discounted = price - amount;
                                    amountPreview.innerHTML =
                                        `<span style="text-decoration: line-through;">${formatCurrency(price)}</span> → ${formatCurrency(discounted > 0 ? discounted : 0)}`;
                                } else {
                                    amountPreview.innerHTML = '';
                                }
                            }

                            // function toggleDiscountInputs() {
                            //     if (percentRadio.checked) {
                            //         percentDiv.style.display = 'block';
                            //         amountDiv.style.display = 'none';
                            //     } else if (amountRadio.checked) {
                            //         percentDiv.style.display = 'none';
                            //         amountDiv.style.display = 'block';
                            //     }
                            // }

                            function toggleDiscountInputs() {
                                if (percentRadio.checked) {
                                    percentDiv.style.display = 'block';
                                    amountDiv.style.display = 'none';
                                    // Xóa input giảm tiền
                                    discountAmountInput.value = '';
                                    amountPreview.innerHTML = '';
                                    updatePercentPreview();
                                } else if (amountRadio.checked) {
                                    percentDiv.style.display = 'none';
                                    amountDiv.style.display = 'block';
                                    // Xóa input giảm %
                                    discountPercentInput.value = '';
                                    percentPreview.innerHTML = '';
                                    updateAmountPreview();
                                }
                            }


                            // Event radio
                            percentRadio.addEventListener('change', toggleDiscountInputs);
                            amountRadio.addEventListener('change', toggleDiscountInputs);

                            // Event input
                            discountPercentInput.addEventListener('input', updatePercentPreview);
                            discountAmountInput.addEventListener('input', updateAmountPreview);
                            priceInput.addEventListener('input', () => {
                                updatePercentPreview();
                                updateAmountPreview();
                            });

                            // Khởi tạo
                            toggleDiscountInputs();
                            updatePercentPreview();
                            updateAmountPreview();
                        });
                    </script>



                    <!-- Trạng thái sản phẩm -->
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1"
                            {{ $product->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" id="is_active_label"
                            for="is_active">{{ $product->is_active ? 'Hiển thị' : 'Ẩn' }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/38.1.1/classic/ckeditor.js"></script>
    <script>
        class MyUploadAdapter {
            constructor(loader) {
                this.loader = loader;
            }
            upload() {
                return this.loader.file.then(file => new Promise((resolve, reject) => {
                    const data = new FormData();
                    data.append('upload', file);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', "{{ route('admin.products.uploadImage') }}", true);
                    xhr.responseType = 'json';
                    xhr.setRequestHeader('X-CSRF-TOKEN',
                        document.querySelector('meta[name="csrf-token"]').content
                    );
                    xhr.onload = () => {
                        if (xhr.status === 201) resolve({
                            default: xhr.response.url
                        });
                        else reject('Upload error');
                    };
                    xhr.onerror = () => reject('Upload error');
                    xhr.send(data);
                }));
            }
            abort() {}
        }

        function MyCustomUploadAdapterPlugin(editor) {
            editor.plugins.get('FileRepository').createUploadAdapter =
                (loader) => new MyUploadAdapter(loader);
        }

        ClassicEditor.create(document.querySelector('#description'), {
            extraPlugins: [MyCustomUploadAdapterPlugin],
            toolbar: [
                'heading', '|',
                'bold', 'italic', 'underline', 'strikethrough', '|',
                'link', 'bulletedList', 'numberedList', 'blockQuote', '|',
                'insertTable', 'undo', 'redo', '|',
                'alignment', 'imageUpload', 'fontColor', 'fontBackgroundColor'
            ],
            image: {
                toolbar: ['imageTextAlternative', 'imageStyle:full', 'imageStyle:side']
            },
            table: {
                contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
            }
        }).catch(console.error);
    </script>
@endsection
