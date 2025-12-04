@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Chi tiết đơn hàng #{{ $order->id }}</h2>

    <div class="mb-3">
        <h5>Thông tin khách hàng</h5>
        <p><strong>Họ tên:</strong> {{ $order->fullname ?? $order->user->name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $order->phone }}</p>
        <p><strong>Email:</strong> {{ $order->email ?? ($order->user->email ?? '-') }}</p>
        <p><strong>Địa chỉ:</strong> {{ $order->address }}</p>
    </div>

    <div class="mb-3">
        <h5>Trạng thái</h5>
        <p><strong>Trạng thái đơn hàng:</strong> {{ $order->status_text }}</p>
        <p><strong>Phương thức thanh toán:</strong> {{ strtoupper($order->payment_method) ?? '-' }}</p>
        <p><strong>Trạng thái thanh toán:</strong> {{ $order->payment_status_text }}</p>
        <p><strong>Ngày tạo:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Cập nhật lần cuối:</strong> {{ $order->updated_date ?? $order->updated_at->format('d/m/Y H:i') }}</p>
    </div>

    <div class="mb-3">
        <h5>Sản phẩm trong đơn</h5>
        <table class="table table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Sản phẩm</th>
                    <th>Số lượng</th>
                    <th>Giá</th>
                    <th>Thành tiền</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price,0,',','.') }}₫</td>
                    <td>{{ number_format($item->price * $item->quantity,0,',','.') }}₫</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <h5 class="text-end">Tổng tiền: {{ number_format($order->total_price,0,',','.') }}₫</h5>
    </div>

    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">Quay lại danh sách</a>
</div>
@endsection
