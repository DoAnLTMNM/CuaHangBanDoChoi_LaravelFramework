@extends('layouts.app')

@section('content')
    <div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
        <div class="carousel-inner">
            @foreach ($banners as $key => $banner)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <a href="{{ $banner->link_url ?? '#' }}">
                        <img src="{{ asset('storage/' . $banner->image_url) }}" class="d-block w-100 banner-img"
                            alt="{{ $banner->title }}">
                    </a>
                </div>
            @endforeach
        </div>

        <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>

        <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

{{-- Sản phẩm mới nhất --}}
<div class="sidebar-latest-products mb-3">
    <h5 class="mb-2">Sản phẩm mới nhất</h5>

    @php
        $latestProducts = \App\Models\Product::with(['images', 'discount'])
            ->orderByDesc('created_at')
            ->take(8)
            ->get();
    @endphp

    <div class="d-flex flex-wrap justify-content-between">
        @foreach ($latestProducts as $product)
            @php
                $discountedPrice = $product->price;
                if ($product->discount && $product->discount->is_active) {
                    if ($product->discount->discount_percent) {
                        $discountedPrice = $product->price * (1 - $product->discount->discount_percent / 100);
                    } elseif ($product->discount->discount_amount) {
                        $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                    }
                }

                $firstImage = $product->images->first() ? asset('storage/' . $product->images->first()->image) : asset('placeholder.png');
                $secondImage = $product->images->count() > 1 ? asset('storage/' . $product->images[1]->image) : $firstImage;
            @endphp

            <a href="{{ route('products.show', $product->id) }}" 
               class="text-decoration-none text-dark" style="width:24%; margin-bottom: 1rem;">
                <div class="card h-100" style="text-align: left; cursor: pointer;">
                    <div class="position-relative">
                        <img src="{{ $firstImage }}" 
                            class="card-img-top product-hover-image"
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


@endsection

