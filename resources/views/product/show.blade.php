@extends('layouts.app2')

@section('title', $product->name)

@section('content')

    @if (!empty($breadcrumbs))
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                @foreach ($breadcrumbs as $crumb)
                    @if ($loop->last)
                        <li class="breadcrumb-item active" aria-current="page">{{ $crumb['title'] }}</li>
                    @else
                        <li class="breadcrumb-item"><a href="{{ $crumb['url'] }}">{{ $crumb['title'] }}</a></li>
                    @endif
                @endforeach
            </ol>
        </nav>
    @endif
    
    <div class="container mt-4">
        <div class="row">
            <!-- Cột trái: Hình ảnh - Sticky -->
            <div class="col-md-6">
                {{-- Sticky container cho cả main image + thumbnails --}}
                <div class="sticky-image-container" style="position: sticky; top: 20px;">
                    {{-- Hình lớn --}}
                    <div class="mb-2">
                        <img id="mainProductImage"
                            src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image) : asset('placeholder.png') }}"
                            alt="{{ $product->name }}" class="img-fluid rounded"
                            style="width:620px; height:620px; object-fit:cover;">
                    </div>

                    {{-- Thumbnail slider --}}
                    @if ($product->images->count() > 1)
                        <div class="d-flex align-items-center gap-1" style="width:620px;">
                            <button type="button" id="prevThumb" class="btn btn-outline-secondary btn-sm">&lt;</button>
                            <div class="overflow-hidden flex-grow-1">
                                <div id="thumbnailWrapper" class="d-flex" style="transition: transform 0.3s; gap: 4px;">
                                    @foreach ($product->images as $img)
                                        <img src="{{ asset('storage/' . $img->image) }}"
                                            data-full="{{ asset('storage/' . $img->image) }}"
                                            class="thumbnail-img rounded border"
                                            style="width:150px; height:150px; object-fit:cover; cursor:pointer;">
                                    @endforeach
                                </div>
                            </div>
                            <button type="button" id="nextThumb" class="btn btn-outline-secondary btn-sm">&gt;</button>
                        </div>
                    @endif
                </div>
            </div>



            <!-- Cột phải: Thông tin sản phẩm -->
            <div class="col-md-6">
                <div class="product-info">
                    <h2 class="mb-3">{{ $product->name }}</h2>
                    <h4 class="text-success fw-bold mb-3">{{ number_format($product->price, 0, ',', '.') }}₫</h4>

                    <!-- Form thêm vào giỏ -->
                    <form action="{{ route('cart.add', $product->id) }}" method="POST"
                        class="d-flex align-items-center mb-4">
                        @csrf
                        <div class="input-group" style="width: 132px;">
                            <button type="button" class="btn btn-outline-secondary quantity-btn"
                                onclick="this.parentNode.querySelector('input[type=number]').stepDown()">−</button>
                            <input type="number" name="quantity" value="1" min="1"
                                class="form-control quantity-input">
                            <button type="button" class="btn btn-outline-secondary quantity-btn"
                                onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                        </div>
                        <button type="submit" class="btn btn-danger ms-2 btn-add-to-cart">Thêm vào giỏ hàng</button>
                    </form>
                    <!-- Hiển thị slug -->
                    <p class="text-muted mb-2"><strong>tags:</strong> {{ $product->slug }}</p>
                    <!-- Mô tả sản phẩm -->
                    <div class="mb-4">
                        <h5>Mô tả:</h5>
                        <div class="product-description">
                            {!! $product->description ?? '<p>Chưa có mô tả</p>' !!}
                        </div>
                    </div>

                    <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
                </div>
            </div>
        </div>
    </div>

    {{-- CSS tuỳ chọn để mô tả hiển thị đẹp --}}
    <style>
        .product-description img {
            max-width: 100%;
            height: auto;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        .product-description table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1rem;
        }

        .product-description table,
        .product-description th,
        .product-description td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .product-description blockquote {
            border-left: 4px solid #ccc;
            padding-left: 10px;
            color: #555;
            margin-bottom: 1rem;
        }

        .sticky-image-container {
            position: sticky;
            top: 20px;
            /* Khoảng cách so với top khi scroll */
            z-index: 10;
            /* Nếu cần đứng trên các phần khác */
        }
    </style>

    {{-- Script đặt cuối --}}
    <script>
        window.addEventListener('DOMContentLoaded', function() {
            const mainImage = document.getElementById('mainProductImage');
            const thumbnails = document.querySelectorAll('.thumbnail-img');
            const wrapper = document.getElementById('thumbnailWrapper');
            const thumbCount = thumbnails.length;
            const visibleThumbs = 4;
            let startIndex = 0;

            // Click thumbnail
            thumbnails.forEach(th => {
                th.addEventListener('click', () => {
                    mainImage.src = th.dataset.full;
                });
            });

            // Update position
            function updateThumbPosition() {
                const style = window.getComputedStyle(thumbnails[0]);
                const thumbWidth = thumbnails[0].offsetWidth + parseInt(style.marginRight);
                wrapper.style.transform = `translateX(-${startIndex * (thumbWidth + 4)}px)`;
            }

            // Previous
            document.getElementById('prevThumb').addEventListener('click', () => {
                if (startIndex > 0) {
                    startIndex--;
                    updateThumbPosition();
                }
            });

            // Next
            document.getElementById('nextThumb').addEventListener('click', () => {
                if (startIndex + visibleThumbs < thumbCount) {
                    startIndex++;
                    updateThumbPosition();
                }
            });
        });
    </script>
@endsection
