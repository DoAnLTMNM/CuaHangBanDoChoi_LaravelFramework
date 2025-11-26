@extends('admin.layouts.app')

@section('content')
    <div class="container mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Danh sách sản phẩm</h2>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                + Thêm sản phẩm
            </a>
        </div>

        <!-- 🔍 Filter live -->
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mb-3" id="filterForm">
            <!-- Tìm theo tên -->
            <div class="col-md-3">
                <input type="text" name="keyword" value="{{ request('keyword') }}" class="form-control"
                    placeholder="Tìm theo tên sản phẩm..." id="keywordInput">
            </div>

            <!-- Trạng thái -->
            <div class="col-md-2">
                <select name="status" class="form-select" id="statusSelect">
                    <option value="">-- Tất cả trạng thái --</option>
                    <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiển thị</option>
                    <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn</option>
                </select>
            </div>

            <!-- Danh mục -->
            <div class="col-md-3">
                <select name="category_id" class="form-select" id="categorySelect">
                    <option value="">-- Tất cả danh mục --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Trạng thái giảm giá -->
            <div class="col-md-2">
                <select name="discount_status" class="form-select" id="discountStatusSelect">
                    <option value="">tất cả các sản phẩm</option>
                    <option value="1" {{ request('discount_status') === '1' ? 'selected' : '' }}>Đang giảm giá</option>
                    <option value="0" {{ request('discount_status') === '0' ? 'selected' : '' }}>Không giảm giá
                    </option>
                </select>
            </div>

            <!-- Ngày tạo -->
            <div class="col-md-2">
                <input type="date" name="created_date" value="{{ request('created_date') }}" class="form-control"
                    id="createdDate">
            </div>
        </form>

        <script>
            ['statusSelect', 'categorySelect', 'discountStatusSelect', 'createdDate'].forEach(id => {
                document.getElementById(id).addEventListener('change', () => {
                    document.getElementById('filterForm').submit();
                });
            });


            // Debounce tìm kiếm tên
            let typingTimer;
            const keywordInput = document.getElementById('keywordInput');
            keywordInput.addEventListener('keyup', function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(() => document.getElementById('filterForm').submit(), 500);
            });
        </script>

        <!-- Bảng sản phẩm -->
        <table class="table table-bordered table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    {{-- <th>Giảm giá</th> --}}
                    <th>Kho</th>
                    <th>Trạng thái</th>
                    <th width="180px">Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            @if ($product->images->count() > 0)
                                @foreach ($product->images as $img)
                                    <img src="{{ asset('storage/' . $img->image) }}" width="60" height="60"
                                        class="me-1 rounded" style="object-fit:cover;">
                                @endforeach
                            @else
                                <span class="text-muted">Không ảnh</span>
                            @endif
                        </td>

                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ? $product->category->name : 'Không' }}</td>
                        <td>
                            @php
                                $discountedPrice = $product->price; // giá sau giảm
                                $hasDiscount = $product->discount && $product->discount->is_active;
                                if ($hasDiscount) {
                                    if ($product->discount->discount_percent) {
                                        $discountedPrice =
                                            $product->price * (1 - $product->discount->discount_percent / 100);
                                    } elseif ($product->discount->discount_amount) {
                                        $discountedPrice = max(
                                            $product->price - $product->discount->discount_amount,
                                            0,
                                        );
                                    }
                                }
                            @endphp

                            @if ($hasDiscount)
                                <span class="text-muted" style="text-decoration: line-through;">
                                    {{ number_format($product->price, 0, '.', ',') }}₫
                                </span>
                                <br>
                                <span class="text-danger fw-bold">
                                    {{ number_format($discountedPrice, 0, '.', ',') }}₫
                                </span>
                            @else
                                {{ number_format($product->price, 0, '.', ',') }}₫
                            @endif
                        </td>
                        {{-- <td>
                            @if ($product->discount && $product->discount->is_active)
                                <span class="badge bg-success">Đang giảm</span>
                            @else
                                <span class="badge bg-secondary">Không</span>
                            @endif
                        </td> --}}
                        <td>{{ $product->stock ?? 0 }}</td>
                        <td>
                            @if ($product->is_active)
                                <span class="badge bg-success">Hiển thị</span>
                            @else
                                <span class="badge bg-secondary">Ẩn</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.products.edit', $product->id) }}"
                                class="btn btn-sm btn-warning">Sửa</a>
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#deleteProductModal{{ $product->id }}">Xóa</button>
                        </td>
                    </tr>

                    <!-- Modal Xóa -->
                    <div class="modal fade" id="deleteProductModal{{ $product->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-header">
                                        <h5 class="modal-title">Xóa sản phẩm</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>
                                    <div class="modal-body">
                                        Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $product->name }}</strong> không?
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
            {{ $products->links() }}
        </div>
    </div>
@endsection
