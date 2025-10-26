@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4">
    <h2>Giỏ hàng của bạn</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cartItems->count() > 0)
        <table class="table table-bordered mt-3">
            <thead>
                <tr>
                    <th>Sản phẩm</th>
                    <th>Hình ảnh</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cartItems as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>
                        @if($item->product->image_url)
                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="height:50px;">
                        @endif
                    </td>
                    <td>{{ number_format($item->product->price,0,',','.') }}₫</td>
                    <td>
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" class="d-flex">
                            @csrf
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control text-center" style="width:70px;">
                            <button type="submit" class="btn btn-sm btn-primary ms-2">Cập nhật</button>
                        </form>
                    </td>
                    <td>{{ number_format($item->product->price * $item->quantity,0,',','.') }}₫</td>
                    <td>
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <h4 class="mt-3">Tổng tiền: 
            {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity),0,',','.') }}₫
        </h4>

        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Tiếp tục mua hàng</a>
        {{-- <a href="{{ route('checkout') }}" class="btn btn-success mt-3">Thanh toán</a> --}}

    @else
        <p>Giỏ hàng trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
    @endif
</div>
@endsection
