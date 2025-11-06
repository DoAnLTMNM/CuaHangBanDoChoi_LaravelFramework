@extends('layouts.app')

@section('content')
<div id="bannerCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000">
    <div class="carousel-inner">
        @foreach ($banners as $key => $banner)
            <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                <a href="{{ $banner->link_url ?? '#' }}">
                    <img src="{{ asset('storage/' . $banner->image_url) }}"
                         class="d-block w-100 banner-img"
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

{{-- // Sản phẩm nổi bật --}}
<div class="container mt-4">
    <h2 class="mb-3">Hàng Mới</h2>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-4">
                <a href="{{ route('products.show', $product->id) }}" class="card-product h-100 text-decoration-none text-dark">
                    <div class="card h-100">
                        <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">{{ number_format($product->price, 0, ',', '.') }}₫</p>
                            @if ($product->stock == 0)
                                <span class="out-of-stock">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                </a>
            </div>
        @empty
            <p>Chưa có sản phẩm nào.</p>
        @endforelse
    </div>
</div>

@endsection

{{-- <script>
    const myCarousel = document.querySelector('#bannerCarousel');
    const carousel = new bootstrap.Carousel(myCarousel, {
        interval: 2000, 
        ride: 'carousel'
    });
</script> --}}