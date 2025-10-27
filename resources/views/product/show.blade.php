@extends('layouts.app2')

@section('title', $product->name)

@section('content')
<div class="container mt-4">
    <h2>{{ $product->name }}</h2>
    <div class="row">
        <!-- Ảnh sản phẩm -->
        <div class="col-md-6">
            <<img src="{{ asset('storage/products/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid">
        </div>

        <!-- Thông tin sản phẩm -->
        <div class="col-md-6">
            <h4>Giá: {{ number_format($product->price, 0, ',', '.') }}₫</h4>
            <p>Trạng thái: {{ $product->is_active ? 'Hoạt động' : 'Ngưng hoạt động' }}</p>
            <p>Mô tả: {{ $product->description ?? 'Chưa có mô tả' }}</p>

            <!-- Chọn số lượng -->
<form action="{{ route('cart.add', $product->id) }}" method="POST" class="d-flex align-items-center mt-3">
    @csrf
    <div class="input-group" style="width:120px;">
        <button type="button" class="btn btn-outline-secondary" onclick="this.nextElementSibling.stepDown()">-</button>
        <input type="number" name="quantity" value="1" min="1" class="form-control text-center">
        <button type="button" class="btn btn-outline-secondary" onclick="this.previousElementSibling.stepUp()">+</button>
    </div>
    <button type="submit" class="btn btn-primary ms-2">Thêm vào giỏ hàng</button>
</form>
        </div>
    </div>

    <!-- Bộ sản phẩm đi kèm -->
    @if($product->items->count())
        <div class="mt-4">
            <h4>Bộ sản phẩm bao gồm:</h4>
            <ul class="list-group">
                @foreach($product->items as $item)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $item->item_name }} x{{ $item->quantity }}
                        @if($item->image_url)
                            <img src="{{ $item->image_url }}" alt="{{ $item->item_name }}" style="height:40px;">
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Tính năng nổi bật -->
    @if($product->features->count())
        <div class="mt-4">
            <h4>Tính năng nổi bật:</h4>
            <ul class="list-group">
                @foreach($product->features as $feature)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $feature->feature }}
                        @if($feature->image_url)
                            <img src="{{ $feature->image_url }}" alt="icon" style="height:30px;">
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

    <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Quay lại</a>
</div>
@endsection
