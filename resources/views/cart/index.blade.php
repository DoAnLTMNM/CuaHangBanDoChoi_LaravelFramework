@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4">
    <h2>Giỏ hàng của bạn</h2>

        @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($cartItems->count() > 0)
        <!-- Thông báo số lượng sản phẩm -->
        <div class="alert alert-light border mb-4 text-center">
            Bạn đang có <strong>{{ $cartItems->count() }}</strong> 
            sản phẩm trong giỏ hàng
        </div>

        <div class="row">
        <!-- Danh sách sản phẩm -->
        <div class="cart-items-list col-md-7">
            @foreach($cartItems as $item)
            <div class="cart-item border rounded p-3 mb-3">
                <div class="d-flex align-items-center">
                     <!-- Hình ảnh -->
                <div class="me-3">
                    @if($item->product->image_url)
                        <img src="{{ $item->product->image_url }}" 
                             alt="{{ $item->product->name }}" 
                             class="cart-item-img">
                    @endif
                </div>

                <!-- Thông tin -->
                <div class="flex-grow-1">
                    <h6 class="mb-1 fw-bold">{{ $item->product->name }}</h6>

                    <!-- Số lượng -->
                    <div class="d-inline-flex align-items-center">
                        <div class="input-group input-group-sm" style="width: 110px;">
                            <button type="button" class="btn btn-outline-secondary quantity-btn btn-minus" data-id="{{ $item->id }}">−</button>
                            <input type="number" value="{{ $item->quantity }}" min="1"
                                class="form-control text-center quantity-input"
                                data-id="{{ $item->id }}">
                            <button type="button" class="btn btn-outline-secondary quantity-btn btn-plus" data-id="{{ $item->id }}">+</button>
                        </div>
                    </div>

                    <!-- Giá sản phẩm -->
                    <div class="text-muted small mt-1">
                        {{ number_format($item->product->price, 2, ',', '.') }}₫
                    </div>

                   
                </div>

                <!-- Xóa -->
                <div class="ms-3">
                    <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-link text-danger p-0">
                            {{-- <i class="bi bi-trash"></i> --}}
                            Xóa
                        </button>
                    </form>
                </div>
                </div>
                    <!-- Thành tiền -->
                    <div class="d-flex justify-content-between fw-semibold subtotal mt-1" id="subtotal-{{ $item->id }}">
                        <div>Thành tiền:</div>
                        <div>{{ number_format($item->product->price * $item->quantity, 2, ',', '.') }}₫</div>
                    </div>
               
            </div>

            @endforeach
        </div>

        {{-- Tổng tiền của giỏ hàng --}}
<div class="col-md-5">
        <div class="cart-total-box p-4 border rounded bg-white shadow-sm">
            <h5 class="mb-3 fw-bold">Thông tin đơn hàng</h5>
            
            <div class="d-flex justify-content-between mb-2">
                <span>Tổng tiền:</span>
                <span class="text-danger fw-bold fs-5" id="cart-total">
                    {{ number_format($cartItems->sum(fn($i) => $i->product->price * $i->quantity), 0, ',', '.') }}₫
                </span>
            </div>

            <p class="text-muted small mb-3">
                Bạn có thể nhập mã giảm giá ở trang thanh toán
            </p>

            <a href="#" class="btn btn-danger w-100 py-3 fw-bold text-uppercase">
                Thanh toán
            </a>
        </div>
    </div>

        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Tiếp tục mua hàng</a>
        {{-- <a href="{{ route('checkout') }}" class="btn btn-success mt-3">Thanh toán</a> --}}

    @else
        <p>Giỏ hàng trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
    @endif
</div>
@endsection
<script>
document.addEventListener("DOMContentLoaded", function () {
    const csrfToken = '{{ csrf_token() }}';

    function updateCart(id, quantity) {
        fetch(`/cart/update/${id}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            },
            body: new URLSearchParams({
                quantity: quantity
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Cập nhật phần "Thành tiền"
                document.querySelector(`#subtotal-${id}`).innerHTML =
                    'Thành tiền: ' + data.newSubtotal.toLocaleString('vi-VN') + '₫';

                //Cập nhật tổng tiền giỏ hàng
                const totalElem = document.querySelector('#cart-total');
                if (totalElem) {
                    totalElem.innerHTML = data.total.toLocaleString('vi-VN') + '₫';
                }
            }
        })
        .catch(err => console.error(err));
    }

    // Xử lý tăng/giảm như cũ
    document.querySelectorAll('.btn-plus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            input.stepUp();
            updateCart(id, input.value);
        });
    });

    document.querySelectorAll('.btn-minus').forEach(btn => {
        btn.addEventListener('click', function () {
            const id = this.dataset.id;
            const input = document.querySelector(`.quantity-input[data-id="${id}"]`);
            input.stepDown();
            updateCart(id, input.value);
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function () {
            const id = this.dataset.id;
            const qty = Math.max(1, parseInt(this.value));
            this.value = qty;
            updateCart(id, qty);
        });
    });
});
</script>