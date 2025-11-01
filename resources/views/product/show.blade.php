@extends('layouts.app2')

@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <div class="row">
        <!-- Cột trái: Hình ảnh - Sticky -->
        <div class="col-md-6 sticky-image-container">
            <div class="sticky-image">
                <img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded">
            </div>
        </div>

        <!-- Cột phải: Thông tin sản phẩm -->
        <div class="col-md-6">
            <div class="product-info">
                <h2 class="mb-3">{{ $product->name }}</h2>
                <h4 class="text-success fw-bold mb-3">{{ number_format($product->price, 0, ',', '.') }}₫</h4>

                <!-- Form thêm vào giỏ -->
                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center mb-4">
                    @csrf
                    <div class="input-group" style="width: 132px;">
                        <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="this.parentNode.querySelector('input[type=number]').stepDown()">−</button>
                        <input type="number" name="quantity" value="1" min="1" class="form-control quantity-input">
                        <button type="button" class="btn btn-outline-secondary quantity-btn" onclick="this.parentNode.querySelector('input[type=number]').stepUp()">+</button>
                    </div>
                    <button type="submit" class="btn btn-danger ms-2 btn-add-to-cart">Thêm vào giỏ hàng</button>
                </form>

                <!-- Mô tả -->
                <div class="mb-4">
                    <h5>Mô tả:</h5>
                    <p>{!! nl2br(e($product->description ?? 'Chưa có mô tả')) !!}</p>
                </div>

                <!-- Bộ sản phẩm đi kèm -->
                @if($product->items->count())
                    <div class="mb-4">
                        <h5>Bộ sản phẩm bao gồm:</h5>
                        <ul class="list-group">
                            @foreach($product->items as $item)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $item->item_name }} x{{ $item->quantity }}
                                    @if($item->image_url)
                                        <img src="{{ $item->image_url }}" alt="{{ $item->item_name }}" style="height:40px; object-fit: contain;">
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <!-- Tính năng nổi bật -->
                @if($product->features->count())
                    <div class="mb-4">
                        <h5>Tính năng nổi bật:</h5>
                        <ul class="list-group">
                            @foreach($product->features as $feature)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    {{ $feature->feature }}
                                    @if($feature->image_url)
                                        <img src="{{ $feature->image_url }}" alt="icon" style="height:30px; object-fit: contain;">
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
            </div>
        </div>
    </div>
</div>
@endsection