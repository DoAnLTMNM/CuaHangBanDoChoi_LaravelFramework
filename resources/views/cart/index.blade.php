@extends('layouts.app')

@section('title', 'Giỏ hàng')

@section('content')
    <div class="container mt-4">
        <h2>Giỏ hàng của bạn</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (count($cartItems) > 0)
            <div class="row">
                <!-- Danh sách sản phẩm -->
                <div class="cart-items-list col-md-8">
                    <div class="p-3 number-products">
                        Bạn đang có <strong>{{ count($cartItems) }}</strong>
                        sản phẩm trong giỏ hàng
                    </div>

                    @foreach ($cartItems as $item)
                        @php
                            // Chuẩn hóa $product cho user chưa đăng nhập
                            $product = isset($item->product) ? $item->product : null;
                            $quantity = $item->quantity;
                            $cartId = $item->id ?? null; // nếu session thì có thể null
                        @endphp

                        <div class="cart-item border-bottom mb-3">
                            <div class="d-flex align-items-center">
                                <!-- Hình ảnh -->
                                <div class="me-3">
                                    @if ($product && isset($product->image))
                                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                            class="cart-item-img">
                                    @endif
                                </div>

                                <!-- Thông tin -->
                                <div class="flex-grow-1">
                                    <h6 class="mb-1 fw-bold">{{ $product->name ?? '' }}</h6>

                                    <!-- Số lượng -->
                                    <div class="d-inline-flex align-items-center">
                                        <div class="input-group input-group-sm" style="width: 110px;">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn btn-minus"
                                                data-id="{{ $cartId }}">−</button>
                                            <input type="number" value="{{ $quantity }}" min="1"
                                                class="form-control text-center quantity-input"
                                                data-id="{{ $cartId }}">
                                            <button type="button" class="btn btn-outline-secondary quantity-btn btn-plus"
                                                data-id="{{ $cartId }}">+</button>
                                        </div>
                                    </div>

                                    <!-- Giá sản phẩm -->
                                    <div class="text-muted small mt-1">
                                        {{ number_format($product->price ?? 0, 2, ',', '.') }}₫
                                    </div>
                                </div>

                                <!-- Xóa -->
                                <div class="ms-3">
                                    @if ($cartId)
                                        <form action="{{ route('cart.remove', $cartId) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link text-danger p-0">
                                                Xóa
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </div>
                            <!-- Thành tiền -->
                            <div class="d-flex justify-content-between fw-semibold subtotal mt-1"
                                id="subtotal-{{ $cartId }}" data-price="{{ $product->price ?? 0 }}">
                                <div>Thành tiền:</div>
                                <div>{{ number_format(($product->price ?? 0) * $quantity, 2, ',', '.') }}₫</div>
                            </div>
                        </div>
                    @endforeach

                </div>

                {{-- Tổng tiền của giỏ hàng --}}
                <div class="col-md-4">
                    <div class="cart-total-box p-4 border rounded bg-white shadow-sm">
                        <h5 class="mb-3 fw-bold">Thông tin đơn hàng</h5>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Tổng tiền:</span>
                            <span class="text-danger fw-bold fs-5" id="cart-total">
                                @php
                                    $total = 0;
                                    foreach ($cartItems as $item) {
                                        // Chuẩn hóa $product cho cả user login và chưa login
                                        $product = isset($item->product) ? $item->product : null;
                                        $quantity = $item->quantity ?? 0;
                                        if ($product) {
                                            $total += $product->price * $quantity;
                                        }
                                    }
                                @endphp
                                {{ number_format($total, 0, ',', '.') }}₫
                            </span>
                        </div>

                        <p class="text-muted small mb-3">
                            Bạn có thể nhập mã giảm giá ở trang thanh toán
                        </p>

                        <a href="#" class="btn btn-danger w-100 py-2 fw-bold text-uppercase">
                            Thanh toán
                        </a>
                    </div>
                </div>
            </div>

            <a href="{{ route('products.index') }}" class="btn btn-secondary mt-3">Tiếp tục mua hàng</a>
        @else
            <p>Giỏ hàng trống.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary mt-3">Quay lại cửa hàng</a>
        @endif
    </div>
@endsection
<script>
    document.addEventListener("DOMContentLoaded", function() {

        function recalcTotal() {
            let total = 0;
            document.querySelectorAll('.cart-item').forEach(itemDiv => {
                const quantityInput = itemDiv.querySelector('.quantity-input');
                const subtotalDiv = itemDiv.querySelector('.subtotal div:last-child');
                const price = parseFloat(itemDiv.querySelector('.subtotal').dataset.price || 0);
                const qty = parseInt(quantityInput.value) || 0;
                const subtotal = price * qty;
                subtotalDiv.textContent = subtotal.toLocaleString('vi-VN') + '₫';
                total += subtotal;
            });
            document.querySelector('#cart-total').textContent = total.toLocaleString('vi-VN') + '₫';
        }

        document.querySelectorAll('.btn-plus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.querySelector(
                    `.quantity-input[data-id="${this.dataset.id}"]`);
                input.stepUp();
                recalcTotal(); // tính lại tổng tiền
            });
        });

        document.querySelectorAll('.btn-minus').forEach(btn => {
            btn.addEventListener('click', function() {
                const input = document.querySelector(
                    `.quantity-input[data-id="${this.dataset.id}"]`);
                input.stepDown();
                recalcTotal(); // tính lại tổng tiền
            });
        });

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function() {
                this.value = Math.max(1, parseInt(this.value));
                recalcTotal(); // tính lại tổng tiền
            });
        });

    });
</script>
