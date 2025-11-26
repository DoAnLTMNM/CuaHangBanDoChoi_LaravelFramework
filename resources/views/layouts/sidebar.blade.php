<aside class="main-sidebar">
    <!-- Dedicated button for Trang chủ -->


    <nav class="sidebar-nav">
        <a href="{{ url('/') }}" class="home-button {{ request()->is('/') ? 'active' : '' }}">
            <span>Trang chủ</span>
        </a>
        <a href="{{ url('/new-arrivals') }}" class="home-button {{ request()->is('new-arrivals') ? 'active' : '' }}">
            <i class="fas fa-box"></i> <span>Hàng Mới Về</span>
        </a>
    </nav>

    <div class="sidebar-sale-products">
        <h4 class="mb-3">Sản phẩm khuyến mãi</h4>

        @php
            // Lấy 5 sản phẩm đang giảm giá
            $saleProducts = \App\Models\Product::with('images', 'discount')
                ->whereHas('discount', function ($q) {
                    $q->where('is_active', 1);
                })
                ->take(5)
                ->get();
        @endphp

        @foreach ($saleProducts as $product)
            @php
                $discountedPrice = $product->price;
                if ($product->discount) {
                    if ($product->discount->discount_percent) {
                        $discountedPrice = $product->price * (1 - $product->discount->discount_percent / 100);
                    } elseif ($product->discount->discount_amount) {
                        $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                    }
                }
            @endphp

            <div class="card mb-2 d-flex flex-row align-items-center card-sales">
                <img src="{{ $product->images->first() ? asset('storage/' . $product->images->first()->image) : asset('placeholder.png') }}"
                    class="me-2" style="width: 90px; height: 90px; object-fit: cover;">
                <div class="flex-grow-1">
                    <h4 class="mb-1 product-name" style="font-size: 0.9rem;">
                        {{ $product->name }}
                    </h4>

                    <div>
                        <span class="text-muted" style="text-decoration: line-through; font-size:0.8rem;">
                            {{ number_format($product->price, 0, '.', ',') }}₫
                        </span>
                        <span class="text-danger fw-bold" style="font-size:0.9rem;">
                            {{ number_format($discountedPrice, 0, '.', ',') }}₫
                        </span>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

</aside>
