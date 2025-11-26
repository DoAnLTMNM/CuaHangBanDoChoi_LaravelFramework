@extends('admin.layouts.app')
@vite('resources/js/admin/product/create.product.js')

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

                            @foreach ($allCategories as $category)
                                @if (!$category->parent_id)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>

                                    @foreach ($category->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>
                    </div>


                    <!-- Thương hiệu -->
                    <div class="mb-3">
                        <label for="brand" class="form-label">Thương hiệu</label>
                        <input type="text" name="brand" id="brand" class="form-control"
                            value="{{ old('brand') }}">
                    </div>
                    <!-- Mô tả sản phẩm -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả sản phẩm</label>
                        <textarea name="description" id="description">{{ old('description') }}</textarea>
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-check mb-3">
                        <input type="checkbox" name="is_active" id="is_active" class="form-check-input" value="1"
                            checked>
                        <label class="form-check-label" for="is_active" id="is_active_label">Hiển thị</label>
                    </div>

                    <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
                </form>
            </div>
        </div>
    </div>

<script src="https://cdn.ckeditor.com/ckeditor5/41.2.1/classic/ckeditor.js"></script>

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
                        document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    );

                    xhr.onload = () => {
                        if (xhr.status === 201) {
                            resolve({
                                default: xhr.response.url
                            });
                        } else {
                            reject('Upload error');
                        }
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

        ClassicEditor
            .create(document.querySelector('#description'), {
                extraPlugins: [MyCustomUploadAdapterPlugin],
                toolbar: [
                    'heading',
            '|', 'bold', 'italic', 'underline', 'strikethrough',
            '|', 'alignment:left', 'alignment:center', 'alignment:right',
            '|', 'link', 'bulletedList', 'numberedList', 'blockQuote',
            '|', 'insertTable', 'undo', 'redo', 'imageUpload',
            '|', 'mediaEmbed', 'htmlEmbed'
                ],
                image: {
                    toolbar: [
                        'imageTextAlternative',
                        'imageStyle:full',
                        'imageStyle:side'
                    ]
                },
                table: {
                    contentToolbar: ['tableColumn', 'tableRow', 'mergeTableCells']
                }
            })
            .catch(error => console.error(error));
    </script>
@endsection
