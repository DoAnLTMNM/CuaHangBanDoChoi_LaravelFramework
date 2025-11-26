@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách Banner</h2>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addBannerModal">
                + Thêm Banner
            </button>
        </div>

        <!-- Filter trạng thái -->
        <form method="GET" action="{{ route('admin.banners.index') }}" class="row g-2 mb-3" id="filterForm">
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Tìm theo tiêu đề..."
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <select name="is_active" class="form-select" id="statusSelect">
                    <option value="">Tất cả trạng thái</option>
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
                    <th>Target type</th>
                    <th>Target ID</th>
                    <th>Trạng thái</th>
                    <th width="180px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($banners as $banner)
                    <tr data-id="{{ $banner->id }}" data-title="{{ $banner->title }}"
                        data-image="{{ $banner->image_url ? asset('storage/' . $banner->image_url) : '' }}"
                        data-link="{{ $banner->link_url }}" data-target-type="{{ $banner->target_type }}"
                        data-target-id="{{ $banner->target_id }}" data-is-active="{{ $banner->is_active }}">
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
                            <span class="badge {{ $banner->is_active ? 'bg-success' : 'bg-secondary' }}">
                                {{ $banner->is_active ? 'Hiển thị' : 'Ẩn' }}
                            </span>
                        </td>
                        <td>
                            <button class="btn btn-sm btn-warning btn-edit" data-bs-toggle="modal"
                                data-bs-target="#editBannerModal">Sửa</button>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteBannerModal{{ $banner->id }}">Xóa</button>
                        </td>
                    </tr>

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

        {{ $banners->links() }}
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
                        <div class="mb-2">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ảnh Banner</label>
                            <input type="file" name="image_url" class="form-control" id="create_banner_input" required>
                            <img id="create_banner_preview" src="#"
                                style="display:none; width:200px; margin-top:10px;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Link URL</label>
                            <input type="text" name="link_url" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Target type</label>
                            <input type="text" name="target_type" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Target ID</label>
                            <input type="text" name="target_id" class="form-control">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" value="1" checked>
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

    <!-- Modal Edit chung -->
    <div class="modal fade" id="editBannerModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="editBannerForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-header">
                        <h5 class="modal-title">Sửa Banner</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="banner_id" id="edit_banner_id">
                        <div class="mb-2">
                            <label class="form-label">Tiêu đề</label>
                            <input type="text" name="title" id="edit_banner_title" class="form-control" required>
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Ảnh Banner</label>
                            <input type="file" name="image_url" class="form-control" id="edit_banner_input">
                            <img id="edit_banner_preview" src="#"
                                style="display:none; width:200px; margin-top:10px;">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Link URL</label>
                            <input type="text" name="link_url" id="edit_banner_link" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Target type</label>
                            <input type="text" name="target_type" id="edit_banner_target_type" class="form-control">
                        </div>
                        <div class="mb-2">
                            <label class="form-label">Target ID</label>
                            <input type="text" name="target_id" id="edit_banner_target_id" class="form-control">
                        </div>
                        <div class="form-check">
                            <input type="checkbox" name="is_active" class="form-check-input" id="edit_banner_is_active">
                            <label class="form-check-label" id="edit_banner_is_active_label">Hiển thị</label>
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

    <script>
        // Filter trạng thái submit tự động
        document.getElementById('statusSelect').addEventListener('change', function() {
            document.getElementById('filterForm').submit();
        });

        // Preview ảnh create
        const createInput = document.getElementById('create_banner_input');
        const createPreview = document.getElementById('create_banner_preview');
        createInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    createPreview.src = e.target.result;
                    createPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Edit modal chung
        const editModal = document.getElementById('editBannerModal');
        const editForm = document.getElementById('editBannerForm');
        const editId = document.getElementById('edit_banner_id');
        const editTitle = document.getElementById('edit_banner_title');
        const editInput = document.getElementById('edit_banner_input');
        const editPreview = document.getElementById('edit_banner_preview');
        const editLink = document.getElementById('edit_banner_link');
        const editTargetType = document.getElementById('edit_banner_target_type');
        const editTargetId = document.getElementById('edit_banner_target_id');
        const editIsActive = document.getElementById('edit_banner_is_active');
        const editIsActiveLabel = document.getElementById('edit_banner_is_active_label');

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                const tr = this.closest('tr');
                const id = tr.dataset.id;
                const title = tr.dataset.title;
                const image = tr.dataset.image;
                const link = tr.dataset.link;
                const targetType = tr.dataset.targetType;
                const targetId = tr.dataset.targetId;
                const isActive = tr.dataset.isActive === '1';

                editId.value = id;
                editTitle.value = title;
                editLink.value = link;
                editTargetType.value = targetType;
                editTargetId.value = targetId;
                editIsActive.checked = isActive;
                editIsActiveLabel.innerText = isActive ? 'Hiển thị' : 'Ẩn';
                if (image) {
                    editPreview.src = image;
                    editPreview.style.display = 'block';
                } else {
                    editPreview.style.display = 'none';
                }

                editForm.action = `/admin/banners/${id}`;
            });
        });

        // Preview ảnh edit
        editInput.addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = e => {
                    editPreview.src = e.target.result;
                    editPreview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            }
        });

        // Checkbox trạng thái edit label
        editIsActive.addEventListener('change', function() {
            editIsActiveLabel.innerText = this.checked ? 'Hiển thị' : 'Ẩn';
        });
    </script>
@endsection
