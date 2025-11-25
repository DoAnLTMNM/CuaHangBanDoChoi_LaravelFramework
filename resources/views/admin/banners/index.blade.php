@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Danh sách Banner</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
            + Thêm Banner
        </button>
    </div>

    <!-- 🔍 Lọc trạng thái -->
    <form method="GET" action="{{ route('admin.banners.index') }}" class="row g-2 mb-3" id="filterForm">
        <div class="col-md-3">
            <select name="is_active" class="form-select" id="statusSelect">
                <option value="">-- Tất cả trạng thái --</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Hiển thị</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Ẩn</option>
            </select>
        </div>
    </form>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Ảnh</th>
                <th>Tiêu đề</th>
                <th>Link</th>
                <th>Loại target</th>
                <th>Target ID</th>
                <th>Trạng thái</th>
                <th width="180px">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($banners as $banner)
            <tr>
                <td>{{ $banner->id }}</td>
                <td>
                    @if ($banner->image_url)
                        <img src="{{ asset('storage/' . $banner->image_url) }}" width="80">
                    @else
                        <span class="text-muted">Không ảnh</span>
                    @endif
                </td>
                <td>{{ $banner->title }}</td>
                <td>{{ $banner->link_url }}</td>
                <td>{{ $banner->target_type }}</td>
                <td>{{ $banner->target_id }}</td>
                <td>
                    @if ($banner->is_active)
                        <span class="badge bg-success">Hiển thị</span>
                    @else
                        <span class="badge bg-secondary">Ẩn</span>
                    @endif
                </td>
                <td>
                    <!-- Sửa -->
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#editBannerModal{{ $banner->id }}">Sửa</button>

                    <!-- Xóa -->
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                        data-bs-target="#deleteBannerModal{{ $banner->id }}">Xóa</button>
                </td>
            </tr>

            <!-- Modal Sửa -->
<div class="modal fade" id="editBannerModal{{ $banner->id }}" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.banners.update', $banner->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Sửa Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Tiêu đề -->
                    <div class="mb-2">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" value="{{ $banner->title }}" required>
                    </div>

                    <!-- Ảnh -->
                    <div class="mb-2">
                        <label class="form-label">Ảnh Banner</label>
                        <input type="file" name="image_url" class="form-control" id="edit_banner_image_input_{{ $banner->id }}">
                        @if ($banner->image_url)
                            <img id="edit_banner_image_preview_{{ $banner->id }}" src="{{ asset('storage/' . $banner->image_url) }}" alt="Preview" style="width:200px; margin-top:10px;">
                        @else
                            <img id="edit_banner_image_preview_{{ $banner->id }}" src="#" alt="Preview" style="display:none; width:200px; margin-top:10px;">
                        @endif
                    </div>

                    <!-- Link URL -->
                    <div class="mb-2">
                        <label class="form-label">Link URL</label>
                        <input type="text" name="link_url" class="form-control" value="{{ $banner->link_url }}">
                    </div>

                    <!-- Target type -->
                    <div class="mb-2">
                        <label class="form-label">Target type</label>
                        <input type="text" name="target_type" class="form-control" value="{{ $banner->target_type }}">
                    </div>

                    <!-- Target ID -->
                    <div class="mb-2">
                        <label class="form-label">Target ID</label>
                        <input type="text" name="target_id" class="form-control" value="{{ $banner->target_id }}">
                    </div>

                    <!-- Trạng thái -->
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active_{{ $banner->id }}" {{ $banner->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active_{{ $banner->id }}">
                            {{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}
                        </label>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- JS preview ảnh -->
<script>
    const editInput{{ $banner->id }} = document.getElementById('edit_banner_image_input_{{ $banner->id }}');
    const editPreview{{ $banner->id }} = document.getElementById('edit_banner_image_preview_{{ $banner->id }}');

    editInput{{ $banner->id }}.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                editPreview{{ $banner->id }}.src = e.target.result;
                editPreview{{ $banner->id }}.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });

    // Cập nhật label trạng thái ngay lập tức
    const checkbox{{ $banner->id }} = document.getElementById('is_active_{{ $banner->id }}');
    const label{{ $banner->id }} = checkbox{{ $banner->id }}.nextElementSibling;
    checkbox{{ $banner->id }}.addEventListener('change', function() {
        label{{ $banner->id }}.innerText = this.checked ? 'Hiển thị' : 'Ẩn';
    });
</script>


            <!-- Modal Xóa -->
            <div class="modal fade" id="deleteBannerModal{{ $banner->id }}" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form action="{{ route('admin.banners.destroy', $banner->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <div class="modal-header">
                                <h5 class="modal-title">Xóa Banner</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn xóa banner <strong>{{ $banner->title }}</strong> không?
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-danger">Xóa</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
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
        {{ $banners->links() }}
    </div>
</div>

<!-- Modal Thêm -->
<div class="modal fade" id="addBannerModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.banners.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Thêm Banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">

                    <!-- Tiêu đề -->
                    <div class="mb-2">
                        <label class="form-label">Tiêu đề</label>
                        <input type="text" name="title" class="form-control" placeholder="Tiêu đề" required>
                    </div>

                    <!-- Ảnh -->
                    <div class="mb-2">
                        <label class="form-label">Ảnh Banner</label>
                        <input type="file" name="image_url" id="banner_image_input" class="form-control" required>
                        <img id="banner_image_preview" src="#" alt="Preview" style="display:none; width: 200px; margin-top:10px;">
                    </div>

                    <!-- Link URL -->
                    <div class="mb-2">
                        <label class="form-label">Link URL</label>
                        <input type="text" name="link_url" class="form-control" placeholder="Link URL">
                    </div>

                    <!-- Target type -->
                    <div class="mb-2">
                        <label class="form-label">Target type</label>
                        <input type="text" name="target_type" class="form-control" placeholder="Target type">
                    </div>

                    <!-- Target ID -->
                    <div class="mb-2">
                        <label class="form-label">Target ID</label>
                        <input type="text" name="target_id" class="form-control" placeholder="Target ID">
                    </div>

                    <!-- Trạng thái -->
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

<!-- JS preview ảnh -->
<script>
    const bannerInput = document.getElementById('banner_image_input');
    const bannerPreview = document.getElementById('banner_image_preview');

    bannerInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                bannerPreview.src = e.target.result;
                bannerPreview.style.display = 'block';
            }
            reader.readAsDataURL(file);
        }
    });
</script>


@endsection
