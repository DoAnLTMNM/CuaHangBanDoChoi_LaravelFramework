@extends('layouts.app')

@section('title', 'Đặt hàng thành công')

@section('content')
<div class="container mt-5">
    <div class="text-center">
        <!-- Tick xanh lớn -->
        <div class="mx-auto mb-4" style="width:100px; height:100px; border-radius:50%; background-color:#28a745; display:flex; align-items:center; justify-content:center;">
            <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" fill="white" class="bi bi-check" viewBox="0 0 16 16">
                <path d="M13.485 1.929a.75.75 0 0 1 1.06 1.06l-8.25 8.25a.75.75 0 0 1-1.06 0L1.455 8.515a.75.75 0 0 1 1.06-1.06l3.52 3.52 7.45-7.446z"/>
            </svg>
        </div>

        <h2 class="text-success mb-3">Đặt hàng thành công!</h2>
        <p class="lead">Cảm ơn bạn đã mua sắm. Chi tiết đơn hàng của bạn như sau:</p>
    </div>

    <div class="card shadow-sm p-4 mt-4">
        <h4 class="mb-3">Thông tin đơn hàng</h4>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Mã đơn hàng:</strong> {{ $order->id }}</div>
            <div class="col-md-6"><strong>Họ tên:</strong> {{ $order->fullname }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-12"><strong>Địa chỉ:</strong> {{ $order->address }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-4"><strong>Số điện thoại:</strong> {{ $order->phone }}</div>
            <div class="col-md-4"><strong>Email:</strong> {{ $order->email ?? 'Không có' }}</div>
            <div class="col-md-4"><strong>Tổng tiền:</strong> {{ number_format($order->total_price,0,',','.') }}₫</div>
        </div>
        <div class="row mb-2">
            <div class="col-md-6"><strong>Phương thức thanh toán:</strong> {{ $order->payment_method }}</div>
            <div class="col-md-6"><strong>Trạng thái đơn hàng:</strong> {{ $order->status }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-md-12"><strong>Trạng thái thanh toán:</strong> {{ $order->payment_status }}</div>
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

        {{-- <div class="text-center mt-4">
            <a href="{{ route('home') }}" class="btn btn-success btn-lg">Quay lại trang chủ</a>
        </div> --}}
    </div>
</div>
@endsection
