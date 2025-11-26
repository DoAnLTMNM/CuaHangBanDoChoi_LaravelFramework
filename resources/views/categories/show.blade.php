@extends('layouts.app')

@section('content')
    <div class="category-products mb-3">
        <div class="mt-3 mb-3 d-flex align-items-center justify-content-between flex-wrap">
            <!-- Tên category -->
            <h5 class="mb-2">{{ $category->name }}</h5>

            <!-- Dropdown lọc sản phẩm -->
            <div class="d-flex gap-2 flex-wrap">
                <!-- Lọc theo giá -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="priceFilterDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Lọc theo giá
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="priceFilterDropdown">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['price' => 'asc']) }}">Giá tăng
                                dần</a></li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['price' => 'desc']) }}">Giá giảm
                                dần</a></li>
                    </ul>
                </div>

                <!-- Sắp xếp theo chữ cái -->
                <div class="dropdown">
                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" id="alphabetSortDropdown"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Sắp xếp theo chữ cái
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="alphabetSortDropdown">
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'a-z']) }}">A → Z</a>
                        </li>
                        <li><a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['sort' => 'z-a']) }}">Z → A</a>
                        </li>
                    </ul>
                </div>
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

    </div>
@endsection
