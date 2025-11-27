<aside class="main-sidebar">
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
            $saleProducts = \App\Models\Product::with('images', 'discount')
                ->whereHas('discount', function ($q) {
                    $q->where('is_active', 1);
                })
                ->take(5)
                ->get();
        @endphp

        @foreach ($saleProducts as $product)
            @php
                $firstImage = $product->images->first()
                    ? asset('storage/' . $product->images->first()->image)
                    : asset('placeholder.png');

                $discountedPrice = $product->price;
                if ($product->discount) {
                    if ($product->discount->discount_percent) {
                        $discountedPrice = $product->price * (1 - $product->discount->discount_percent / 100);
                    } elseif ($product->discount->discount_amount) {
                        $discountedPrice = max($product->price - $product->discount->discount_amount, 0);
                    }
                }
            @endphp

            <a href="{{ route('products.show', $product->id) }}"
               class="text-decoration-none text-dark"
               style="display:block; margin-bottom: 1rem;">

                <div class="card d-flex flex-row align-items-center card-sales"
                     style="cursor:pointer; border-radius:10px; border:none; padding:0;">

                    <img src="{{ $firstImage }}"
                         class="me-2 rounded"
                         style="width: 90px; height: 90px; object-fit: cover;">

                    <div class="flex-grow-1">

                        <h6 class="mb-1"
                            style="font-size:0.8rem; font-weight:600; height:2.2em; overflow:hidden; text-transform:uppercase;">
                            {{ $product->name }}
                        </h6>

                        <div>
                            <span class="text-muted"
                                  style="text-decoration: line-through; font-size:0.8rem;">
                                {{ number_format($product->price, 0, '.', ',') }}₫
                            </span>

                            <span class="text-danger fw-bold" style="font-size:0.9rem;">
                                {{ number_format($discountedPrice, 0, '.', ',') }}₫
                            </span>
                        </div>

                    </div>

                </div>
            </a>

        @endforeach
    </div>

</aside>

<style>
    .card-sales:hover {
        background-color: #f8f8f8;
        transition: .2s ease;
    }
</style>
