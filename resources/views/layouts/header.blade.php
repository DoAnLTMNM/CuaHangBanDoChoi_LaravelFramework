<header class="main-header">
    <div class="header-container d-flex">
        <!-- Logo -->
        <div class="header-logo col-md-3">
            <a href="/" class="logo-link">
                <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
            </a>
        </div>
        <!-- Nav + Categories -->
        <nav class="main-nav col-md-9 d-flex">
            <ul class="navbar-nav flex-row w-100">
                @foreach ($categories->take(9) as $category)
                    <li class="nav-item dropdown me-3 text-center cat-item">
                        <a class="nav-link d-flex flex-column align-items-center" style="font-size: 0.9rem"
                            href="{{ url('/category/' . $category->slug) }}">
                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" alt="{{ $category->name }}"
                                    class="rounded"
                                    style="width:30px; height:30px; object-fit:cover; margin-bottom:5px;">
                            @endif
                            {{ $category->name }}
                        </a>

                        @if ($category->children->count())
                            <button class="cat-toggle-btn" data-bs-toggle="dropdown" aria-expanded="false"></button>
                            <ul class="dropdown-menu">
                                @foreach ($category->children as $child)
                                    <li><a class="dropdown-item"
                                            href="{{ url('/category/' . $child->slug) }}">{{ $child->name }}</a></li>
                                @endforeach
                            </ul>
                        @endif
                    </li>
                @endforeach
            </ul>

            <div class="header-actions d-flex align-items-center ms-auto">
                <!-- Cart icon with badge -->
                <a class="nav-link position-relative" href="{{ route('cart.index') }}">
                    <i class="bi bi-cart3 fs-5"></i>
                    <span id="cart-count"
                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        {{ Auth::check() ? \App\Models\Cart::where('user_id', Auth::id())->sum('quantity') : (session('cart') ? array_sum(array_map(fn($i) => $i['quantity'], session('cart'))) : 0) }}
                        <span class="visually-hidden">sản phẩm trong giỏ hàng</span>
                    </span>
                </a>

                <!-- Nút search -->
                <button id="searchToggle" class="btn btn-light ms-3">
                    <i class="bi bi-search"></i>
                </button>

                @if (Auth::check())
                    <ul class="navbar-nav ms-3">
                        <li class="nav-item dropdown click-dropdown">
                            <a class="nav-link dropdown-toggle fw-medium text-dark" href="#" id="userDropdown"
                                role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Thông tin tài khoản</a>
                                </li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">Đơn hàng</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                @else
                    <a href="{{ route('login') }}" class="ms-3">Đăng nhập</a>
                @endif
            </div>
        </nav>
    </div>

    <!-- Overlay màn đen -->
    <div id="searchOverlay"></div>

    <!-- Popup search -->
    <div id="searchPopup">
        <header>
            <h5>Tìm kiếm</h5>
            <button id="closeSearch">✖</button>
        </header>
        <div class="search-body">
            <input type="text" id="searchInput" placeholder="Tìm kiếm sản phẩm...">

            <div id="hotKeywordsWrapper">
                <p style="font-weight:bold; margin-bottom:8px;">Từ khóa nổi bật</p>
                <div class="hot-keywords">
                    @foreach (\App\Models\Category::latest()->take(7)->get() as $keyword)
                        <a href="{{ url('/category/' . $keyword->slug) }}">{{ $keyword->name }}</a>
                    @endforeach
                </div>
            </div>

            <div id="searchResults"></div>
        </div>
    </div>

    <script>
        // ----- Helper cập nhật cart badge -----
        function updateCartBadge(count) {
            const cartBadge = document.getElementById('cart-count');
            if (cartBadge) cartBadge.textContent = count;
        }

        // Dropdown category
        document.querySelectorAll(".cat-toggle-btn").forEach(btn => {
            btn.addEventListener("click", function(e) {
                e.stopPropagation();
                this.parentElement.querySelector(".dropdown-menu").classList.toggle("show");
            });
        });
        document.addEventListener("click", () => {
            document.querySelectorAll(".dropdown-menu.show").forEach(menu => menu.classList.remove("show"));
        });

        // Popup search + overlay
        const searchBtn = document.getElementById('searchToggle');
        const searchPopup = document.getElementById('searchPopup');
        const closeSearch = document.getElementById('closeSearch');
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');
        const hotKeywordsWrapper = document.getElementById('hotKeywordsWrapper');
        const searchOverlay = document.getElementById('searchOverlay');

        function openSearch() {
            searchPopup.classList.add('show');
            searchOverlay.classList.add('show');
            document.body.style.overflow = 'hidden'; // khóa scroll
            searchInput.focus();
        }

        function closeSearchPopup() {
            searchPopup.classList.remove('show');
            searchOverlay.classList.remove('show');
            document.body.style.overflow = ''; // mở lại scroll
        }

        searchBtn.addEventListener('click', e => {
            e.stopPropagation();
            openSearch();
        });
        closeSearch.addEventListener('click', closeSearchPopup);
        searchOverlay.addEventListener('click', closeSearchPopup);
        document.addEventListener('click', e => {
            if (!searchPopup.contains(e.target) && !searchBtn.contains(e.target)) closeSearchPopup();
        });
        searchPopup.addEventListener('click', e => e.stopPropagation());

        // Live search AJAX
        searchInput.addEventListener('keyup', function() {
            const keyword = searchInput.value.trim();
            if (!keyword) {
                searchResults.innerHTML = '';
                hotKeywordsWrapper.style.display = 'block';
                return;
            }
            hotKeywordsWrapper.style.display = 'none';
            fetch(`/search-popup?q=${encodeURIComponent(keyword)}`)
                .then(res => res.json())
                .then(data => {
                    searchResults.innerHTML = '';
                    if (data.length === 0) {
                        searchResults.innerHTML = '<p>Không tìm thấy sản phẩm</p>';
                        return;
                    }
                    data.forEach(product => {
                        const img = product.image ?? '/images/default.png';
                        let discountedPrice = null;
                        if (product.has_discount && product.discount_percentage) {
                            discountedPrice = product.price - (product.price * product
                                .discount_percentage / 100);
                        }
                        searchResults.innerHTML += `
                            <a href="/products/${product.id}">
                                <div class="card-sales">
                                    <img src="${img}" alt="${product.name}">
                                    <div class="flex-grow-1">
                                        <h6>${product.name}</h6>
                                        <div>
                                            ${discountedPrice 
                                                ? `<div class="d-flex gap-2 align-items-center">
                                                                        <span class="text-muted" style="text-decoration: line-through; font-size:0.9rem;">
                                                                            ${Number(product.price).toLocaleString('vi-VN')}₫
                                                                        </span>
                                                                        <span class="text-danger fw-bold" style="font-size:1rem;">
                                                                            ${Number(discountedPrice).toLocaleString('vi-VN')}₫
                                                                        </span>
                                                                   </div>` 
                                                : `<span class="fw-bold" style="font-size:1rem; color:#00b751;">
                                                                        ${Number(product.price).toLocaleString('vi-VN')}₫
                                                                   </span>`
                                            }
                                        </div>
                                    </div>
                                </div>
                            </a>
                        `;
                    });
                });
        });
    </script>



</header>
