@extends('layouts.app')

@section('content')
    <div class="category-products mb-3">
        <div class="mt-3 mb-3 d-flex align-items-center justify-content-between flex-wrap">
            <!-- Tên category -->
            <h5 class="mb-2">{{ $category->name }}</h5>

            <!-- Dropdown lọc sản phẩm -->
            <div class="d-flex gap-2 flex-wrap mb-3">
                <form method="GET" class="d-flex gap-2 flex-wrap">

                    {{-- Lọc theo giá --}}
                    <select name="price" class="form-select" style="width:200px;" onchange="this.form.submit()">
                        <option value="">Lọc theo giá</option>
                        <option value="asc" {{ request('price') == 'asc' ? 'selected' : '' }}>Giá tăng dần</option>
                        <option value="desc" {{ request('price') == 'desc' ? 'selected' : '' }}>Giá giảm dần</option>
                    </select>

                    {{-- Sắp xếp theo chữ cái --}}
                    {{-- <select name="sort" class="form-select" style="width:200px;" onchange="this.form.submit()">
            <option value="">Sắp xếp A-Z</option>
            <option value="a-z" {{ request('sort') == 'a-z' ? 'selected' : '' }}>A → Z</option>
            <option value="z-a" {{ request('sort') == 'z-a' ? 'selected' : '' }}>Z → A</option>
        </select> --}}

                </form>
            </div>

        </div>

        <div class="d-flex flex-wrap gap-3">
            @foreach ($products as $product)
                @php
                    $discountedPrice = $product->price;
                    if ($product->discount && $product->discount->is_active) {
                        if ($product->discount->discount_percent) {
                            $discountedPrice = $product->price * (1 - $product->discount->discount_percent / 100);
                        } elseif ($product->discount->discount_amount) {
                            $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                        }
                    }

                    $firstImage = $product->images->first()
                        ? asset('storage/' . $product->images->first()->image)
                        : asset('placeholder.png');
                    $secondImage =
                        $product->images->count() > 1 ? asset('storage/' . $product->images[1]->image) : $firstImage;
                @endphp

                <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark"
                    style="width:24%; margin-bottom: 1rem;">
                    <div class="card h-100" style="text-align: left; cursor: pointer;">
                        <div class="position-relative">
                            <img src="{{ $firstImage }}" class="card-img-top product-hover-image"
                                data-hover="{{ $secondImage }}"
                                style="width: 100%; height: 250px; object-fit: cover; border-radius: 10px;"
                                alt="{{ $product->name }}">
                        </div>
                        <div class="card-body p-2">
                            <h6 class="card-title mb-1 product-name"
                                style="font-size: 1rem; height: 2.2em; overflow: hidden; text-overflow: ellipsis; text-transform: uppercase;">
                                {{ $product->name }}
                            </h6>
                            <div>
                                @if ($product->discount && $product->discount->is_active)
                                    <div class="d-flex gap-2 align-items-center">
                                        <span class="text-muted" style="text-decoration: line-through; font-size:0.9rem;">
                                            {{ number_format($product->price, 0, '.', ',') }}₫
                                        </span>
                                        <span class="text-danger fw-bold" style="font-size:1rem;">
                                            {{ number_format($discountedPrice, 0, '.', ',') }}₫
                                        </span>
                                    </div>
                                @else
                                    <span class="fw-bold" style="font-size:1rem; color: #00b751;">
                                        {{ number_format($product->price, 0, '.', ',') }}₫
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
        </div>
        {{-- Pagination --}}
        <div class="mt-3 w-100 d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>

    {{-- JS hover hình --}}
    <script>
        document.querySelectorAll('.product-hover-image').forEach(img => {
            const originalSrc = img.src;
            const hoverSrc = img.dataset.hover;

            if (hoverSrc !== originalSrc) {
                img.addEventListener('mouseenter', () => {
                    img.src = hoverSrc;
                });
                img.addEventListener('mouseleave', () => {
                    img.src = originalSrc;
                });
            }
        });
    </script>
@endsection
