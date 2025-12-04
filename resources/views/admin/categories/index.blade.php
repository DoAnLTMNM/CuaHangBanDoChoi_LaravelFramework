@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách danh mục</h2>
        </div>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
            + Thêm danh mục
        </button>

        <!-- 🔍 FORM TÌM KIẾM & LỌC TRẠNG THÁI -->
        <form method="GET" action="{{ route('admin.categories.index') }}" class="d-flex flex-wrap mb-3" id="filterForm">

            <!-- Tìm kiếm tên -->
            <div class="flex-grow-1 me-2 mb-2">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control w-100"
                    placeholder="Tìm theo tên danh mục..." id="keywordInput">
            </div>

            <!-- Trạng thái -->
            <div class="flex-grow-1 me-2 mb-2">
                <select name="status" class="form-select w-100" id="statusSelect">
                    <option value="">Tất cả trạng thái</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <!-- Danh mục cha -->
            <div class="flex-grow-1 me-2 mb-2">
                <select name="parent_id" id="parentSelect" class="form-control mb-2">
                    <option value="">Chọn danh mục cha</option>
                    @foreach ($allCategories as $category)
                        @if (!$category->parent_id)
                            <option value="{{ $category->id }}"
                                {{ request('parent_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @foreach ($category->children as $child)
                                <option value="{{ $child->id }}"
                                    {{ request('parent_id') == $child->id ? 'selected' : '' }}>
                                    — {{ $child->name }}
                                </option>
                            @endforeach
                        @endif
                    @endforeach
                </select>
            </div>



            <!-- Ngày tạo -->
            <div class="flex-grow-1 me-2 mb-2">
                <input type="date" name="created_date" value="{{ request('created_date') }}" class="form-control w-100"
                    id="createdDate">
            </div>

        </form>

        <script>
            // Tự submit khi thay đổi
            document.getElementById('statusSelect').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
            document.getElementById('parentSelect').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });
            document.getElementById('createdDate').addEventListener('change', function() {
                document.getElementById('filterForm').submit();
            });


            // Optional: tự submit khi gõ tên (với debounce 500ms)
            let typingTimer;
            const keywordInput = document.getElementById('keywordInput');
            keywordInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(function() {
                    document.getElementById('filterForm').submit();
                }, 500);
            });
        </script>

        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên danh mục</th>
                    <th>Danh mục cha</th>
                    <th>Trạng thái</th>
                    <th>Ngày tạo</th>
                    <th width="180px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $cate)
                    <tr>
                        <td>{{ $cate->id }}</td>
                        <td>
                            @if ($cate->image)
                                <img src="{{ asset('storage/' . $cate->image) }}" width="60">
                            @else
                                <span class="text-muted">Không ảnh</span>
                            @endif
                        </td>
                        <td>{{ $cate->name }}</td>
                        <td>{{ $cate->parent ? $cate->parent->name : 'Không' }}</td>
                        <td>
                            @if ($cate->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>{{ $cate->created_at->format('d/m/Y H:i') }}</td> <!-- hiện ngày tạo -->
                        <td>
                            <!-- Nút sửa -->
                            <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                data-bs-target="#editCategoryModal{{ $cate->id }}">
                                Sửa
                            </button>

                            <!-- Nút xóa -->
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteCategoryModal{{ $cate->id }}">
                                Xóa
                            </button>
                        </td>
                    </tr>

                    <!-- Modal Sửa -->
                    <div class="modal fade" id="editCategoryModal{{ $cate->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.categories.update', $cate->id) }}" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Sửa danh mục</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" name="name" class="form-control mb-2"
                                            value="{{ $cate->name }}" required>
                                        <input type="file" name="image" class="form-control mb-2">
                                        <select name="parent_id" class="form-control mb-2">
                                            <option value="">Chọn danh mục cha</option>
                                            @foreach ($allCategories as $category)
                                                @if (!$category->parent_id && $category->id != $cate->id)
                                                    {{-- tránh chọn chính nó --}}
                                                    <option value="{{ $category->id }}"
                                                        {{ $cate->parent_id == $category->id ? 'selected' : '' }}>
                                                        {{ $category->name }}
                                                    </option>
                                                    @foreach ($category->children as $child)
                                                        @if ($child->id != $cate->id)
                                                            {{-- tránh chọn chính nó --}}
                                                            <option value="{{ $child->id }}"
                                                                {{ $cate->parent_id == $child->id ? 'selected' : '' }}>
                                                                — {{ $child->name }}
                                                            </option>
                                                        @endif
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        </select>


                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="is_active" value="1"
                                                id="is_active_{{ $cate->id }}"
                                                {{ $cate->is_active ? 'checked' : '' }}>
                                            <label class="form-check-label" id="is_active_label_{{ $cate->id }}">
                                                {{ $cate->is_active ? 'Hiển thị' : 'Ẩn' }}
                                            </label>
                                        </div>

                                        <script>
                                            document.getElementById('is_active_{{ $cate->id }}').addEventListener('change', function() {
                                                document.getElementById('is_active_label_{{ $cate->id }}').innerText = this.checked ? 'Hiển thị' :
                                                    'Ẩn';
                                            });
                                        </script>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Xóa -->
                    <div class="modal fade" id="deleteCategoryModal{{ $cate->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.categories.destroy', $cate->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Xóa danh mục</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn muốn xóa danh mục <strong>{{ $cate->name }}</strong> không?
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Hủy</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-3">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal Thêm -->
    <div class="modal fade" id="addCategoryModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Thêm danh mục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="name" class="form-control mb-2" placeholder="Tên danh mục"
                            required>
                        <input type="file" name="image" class="form-control mb-2">
                        <select name="parent_id" class="form-control mb-2">
                            <option value="">Chọn danh mục cha</option>
                            @foreach ($allCategories as $category)
                                @if (!$category->parent_id)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @foreach ($category->children as $child)
                                        <option value="{{ $child->id }}">— {{ $child->name }}</option>
                                    @endforeach
                                @endif
                            @endforeach
                        </select>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                            <label class="form-check-label">Hiển thị</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Thêm</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
