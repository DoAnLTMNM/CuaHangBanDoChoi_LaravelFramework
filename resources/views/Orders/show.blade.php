@extends('layouts.app2')

@section('title', 'Chi tiết đơn hàng #'.$order->id)

@section('content')
<div class="container mt-5">
    <div class="text-center">

        <h2 class="text-success mb-3">Chi tiết đơn hàng</h2>
        <p class="lead">Thông tin chi tiết đơn hàng của bạn như sau:</p>
    </div>

    <div class="card shadow-sm p-4 mt-4">
        <h4 class="mb-3">Thông tin đơn hàng</h4>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Mã đơn hàng:</strong> {{ $order->id }}</div>
            <div class="col-md-6"><strong>Họ tên:</strong> {{ $order->fullname }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12"><strong>Địa chỉ:</strong> {{ $order->address }}, {{ $order->ward_name }}, {{ $order->district_name }}, {{ $order->province_name }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4"><strong>Số điện thoại:</strong> {{ $order->phone }}</div>
            <div class="col-md-4"><strong>Email:</strong> {{ $order->email ?? 'Không có' }}</div>
            <div class="col-md-4"><strong>Tổng tiền:</strong> {{ number_format($order->total_price,0,',','.') }}₫</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</div>
            <div class="col-md-6"><strong>Trạng thái đơn hàng:</strong> {{ $order->status_text }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12"><strong>Trạng thái thanh toán:</strong> {{ ucfirst($order->payment_status) }}</div>
        </div>

        <h4 class="mt-4 mb-3">Chi tiết sản phẩm</h4>
        <ul class="list-group">
            @foreach($order->items as $item)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong>{{ $item->product->name ?? 'Sản phẩm #' . $item->product_id }}</strong> <br>
                        Số lượng: {{ $item->quantity }}
                    </div>
                    <span class="fw-bold">{{ number_format($item->price,0,',','.') }}₫</span>
                </li>
            @endforeach
        </ul>

        <div class="text-center mt-4">
            <a href="{{ route('orders.index') }}" class="btn btn-primary btn-lg me-2">Quay lại danh sách đơn hàng</a>
            <a href="{{ route('home') }}" class="btn btn-secondary btn-lg">Trang chủ</a>
        </div>
    </div>
</div>
@endsection
