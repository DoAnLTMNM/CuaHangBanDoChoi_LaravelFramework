@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
<div class="container mt-4">
    <h2>Giỏ hàng của bạn</h2>

    {{-- Toast container --}}
    <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index:9999"></div>

    @if(count($cartItems) > 0)
        <div class="row" id="cart-items-container">
            <!-- Danh sách sản phẩm -->
            <div class="cart-items-list col-md-8">
                <div class="p-3 number-products">
                    Bạn đang có <strong>{{ count($cartItems) }}</strong> sản phẩm trong giỏ hàng
                </div>

                @foreach($cartItems as $item)
                    @php
                        $product = $item->product ?? null;
                        $quantity = $item->quantity;
                        $cartId = $item->id;

                        $discountedPrice = $product->price ?? 0;
                        if($product && $product->discount && $product->discount->is_active) {
                            if($product->discount->discount_percent) {
                                $discountedPrice = $product->price * (1 - $product->discount->discount_percent / 100);
                            } elseif($product->discount->discount_amount) {
                                $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                            }
                        }

                        $firstImage = $product && $product->images->first()
                            ? asset('storage/' . $product->images->first()->image)
                            : asset('placeholder.png');
                    @endphp

                    <div class="cart-item border-bottom mb-3" data-id="{{ $cartId }}" data-price="{{ $discountedPrice }}">
                        <div class="d-flex align-items-center">
                            <div class="me-3">
                                <img src="{{ $firstImage }}" class="cart-item-img"
                                     style="width:100px;height:100px;object-fit:cover;border-radius:5px;">
                            </div>

                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">{{ $product->name ?? '' }}</h6>

                                <div class="mb-1">
                                    @if($product && $product->discount && $product->discount->is_active)
                                        <span class="text-muted" style="text-decoration: line-through;">
                                            {{ number_format($product->price,0,',','.') }}₫
                                        </span>
                                        <span class="text-danger fw-bold">
                                            {{ number_format($discountedPrice,0,',','.') }}₫
                                        </span>
                                    @else
                                        <span class="fw-bold text-success">
                                            {{ number_format($product->price ?? 0,0,',','.') }}₫
                                        </span>
                                    @endif
                                </div>

                                <div class="d-inline-flex align-items-center">
                                    <div class="input-group input-group-sm" style="width:110px;">
                                        <button type="button" class="btn btn-outline-secondary btn-minus">−</button>
                                        <input type="number" class="form-control text-center quantity-input" value="{{ $quantity }}" min="1" data-id="{{ $cartId }}" data-url="{{ route('cart.update', $cartId) }}">
                                        <button type="button" class="btn btn-outline-secondary btn-plus">+</button>
                                    </div>
                                </div>
                            </div>

                            {{-- Nút Xóa bằng button --}}
                            <div class="ms-3">
                                <button type="button" class="btn btn-link text-danger p-0 btn-remove" 
                                    data-id="{{ $cartId }}" 
                                    data-url="{{ route('cart.remove', $cartId) }}">
                                    Xóa
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between fw-semibold subtotal mt-1">
                            <div>Thành tiền:</div>
                            <div>{{ number_format($discountedPrice * $quantity,0,',','.') }}₫</div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Tổng tiền -->
            <div class="col-md-4">
                <div class="cart-total-box p-4 border rounded bg-white shadow-sm">
                    <h5 class="mb-3 fw-bold">Thông tin đơn hàng</h5>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tổng tiền:</span>
                        <span class="text-danger fw-bold fs-5" id="cart-total">0₫</span>
                    </div>

                    <p class="text-muted small mb-3">Bạn có thể nhập mã giảm giá ở trang thanh toán</p>

                    <a href="#" class="btn btn-danger w-100 py-2 fw-bold text-uppercase">Thanh toán</a>
                </div>
            </div>
        </div>

        <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Tiếp tục mua hàng</a>
    @else
        <p id="empty-cart-message">Giỏ hàng trống.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
    @endif
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {

    function recalcTotal() {
        let total = 0;
        document.querySelectorAll('.cart-item').forEach(item => {
            const qty = parseInt(item.querySelector('.quantity-input').value) || 0;
            const price = parseFloat(item.dataset.price);
            const subtotal = price * qty;
            item.querySelector('.subtotal div:last-child').textContent = subtotal.toLocaleString('vi-VN') + '₫';
            total += subtotal;
        });
        document.querySelector('#cart-total').textContent = total.toLocaleString('vi-VN') + '₫';
    }

    function showToast(message) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.classList.add('toast', 'align-items-center', 'text-bg-success', 'border-0', 'show');
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">${message}</div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    recalcTotal();

    // Nút +/- tăng giảm số lượng live
    document.querySelectorAll('.btn-plus, .btn-minus').forEach(btn => {
        btn.addEventListener('click', function() {
            const input = this.parentElement.querySelector('.quantity-input');
            if (this.classList.contains('btn-plus')) input.stepUp();
            else input.stepDown();

            const url = input.dataset.url;
            const qty = parseInt(input.value);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    recalcTotal();
                    showToast(data.success);
                }
            });
        });
    });

    // Thay đổi số lượng input trực tiếp
    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', function() {
            this.value = Math.max(1, parseInt(this.value));
            const url = this.dataset.url;
            const qty = parseInt(this.value);

            fetch(url, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ quantity: qty })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    recalcTotal();
                    showToast(data.success);
                }
            });
        });
    });

    // Xóa sản phẩm live
    document.querySelectorAll('.btn-remove').forEach(btn => {
        btn.addEventListener('click', function() {
            const cartItemDiv = this.closest('.cart-item');
            const action = this.dataset.url;

            fetch(action, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    cartItemDiv.remove();
                    recalcTotal();
                    showToast(data.success);

                    // Nếu giỏ hàng rỗng, hiện thông báo
                    if(document.querySelectorAll('.cart-item').length === 0){
                        document.getElementById('cart-items-container').innerHTML = `
                            <p id="empty-cart-message">Giỏ hàng trống.</p>
                            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
                        `;
                    }
                }
            });
        });
    });

});
</script>
@endsection
