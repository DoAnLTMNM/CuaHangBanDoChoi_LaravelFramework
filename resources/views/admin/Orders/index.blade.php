@extends('admin.layouts.app')

@section('content')
<div class="container mt-4">
    <h2>Danh sách đơn hàng</h2>
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-2">
        <input type="text" name="code" class="form-control form-control-sm" placeholder="Mã đơn" value="{{ request('code') }}">
    </div>
    <div class="col-md-2">
        <input type="date" name="date" class="form-control form-control-sm" value="{{ request('date') }}">
    </div>
    <div class="col-md-2">
        <select name="status" class="form-select form-select-sm">
            <option value="">-- Trạng thái --</option>
            <option value="pending" {{ request('status')=='pending'?'selected':'' }}>Chờ xác nhận</option>
            <option value="processing" {{ request('status')=='processing'?'selected':'' }}>Đang xử lý</option>
            <option value="completed" {{ request('status')=='completed'?'selected':'' }}>Hoàn thành</option>
            <option value="cancelled" {{ request('status')=='cancelled'?'selected':'' }}>Đã hủy</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-sm btn-primary">Lọc</button>
    </div>
</form>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-hover mt-3">
        <thead class="table-light">
            <tr>
                <th>ID</th>
                <th>Khách hàng</th>
                <th>Số điện thoại</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
                {{-- <th>Thanh toán</th> --}}
                <th>Ngày tạo</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td>{{ $order->id }}</td>
                <td>{{ $order->fullname ?? $order->user->name }}</td>
                <td>{{ $order->phone }}</td>
                <td>{{ number_format($order->total_price,0,',','.') }}₫</td>
                <td>{{ $order->status_text }}</td>
                {{-- <td>{{ $order->payment_status_text }}</td> --}}
                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                <td>
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">Xem</a>
                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('PUT')
                        <select name="status" class="form-select form-select-sm d-inline-block" style="width:auto;">
                            <option value="pending" {{ $order->status=='pending'?'selected':'' }}>Chờ xác nhận</option>
                            <option value="processing" {{ $order->status=='processing'?'selected':'' }}>Đang xử lý</option>
                            <option value="completed" {{ $order->status=='completed'?'selected':'' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->status=='cancelled'?'selected':'' }}>Đã hủy</option>
                        </select>
                        <button class="btn btn-sm btn-success">Cập nhật</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="d-flex justify-content-end">
        {{ $orders->links() }}
    </div>
</div>
@endsection
