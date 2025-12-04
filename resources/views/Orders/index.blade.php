@extends('layouts.app3')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="container mt-5">
    <h2 class="mb-4 text-center">Danh sách đơn hàng</h2>

    @if(session('success'))
        <div class="alert alert-success text-center">{{ session('success') }}</div>
    @endif

    @if($orders->isEmpty())
        <p class="text-center">Bạn chưa có đơn hàng nào.</p>
    @else
        <div class="table-responsive">
            <table class="table table-hover align-middle text-center shadow-sm">
                <thead class="table-dark">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thanh toán</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                        @php
                            $statusClass = match($order->status) {
                                'pending' => 'bg-warning text-dark',
                                'processing' => 'bg-primary text-white',
                                'completed' => 'bg-success text-white',
                                'cancelled' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };

                            $paymentClass = match($order->payment_status) {
                                'pending' => 'bg-warning text-dark',
                                'unpaid' => 'bg-secondary text-white',
                                'paid' => 'bg-success text-white',
                                'failed' => 'bg-danger text-white',
                                default => 'bg-secondary text-white'
                            };
                        @endphp

                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>{{ number_format($order->total_price,0,',','.') }}₫</td>

                            <td>
                                <span class="badge {{ $statusClass }} px-3 py-2">
                                    {{ $order->status_text }}
                                </span>
                            </td>

                            <td>
                                <span class="badge {{ $paymentClass }} px-3 py-2">
                                    {{ $order->payment_status_text }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info me-1">
                                    <i class="bi bi-eye"></i> Xem
                                </a>

                                {{-- CHỈ Hiện nút Hủy nếu trạng thái = pending --}}
                                @if($order->status === 'pending')
                                    <form action="{{ route('orders.destroy', $order->id) }}"
                                          method="POST"
                                          class="d-inline-block"
                                          onsubmit="return confirm('Bạn có chắc muốn hủy đơn hàng này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle"></i> Hủy
                                        </button>
                                    </form>
                                @endif

                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
