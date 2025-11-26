<header class="main-header">
    <style>
        .cat-item {
            position: relative;
        }
        .cat-toggle-btn {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            margin-left: 4px;
            cursor: pointer;
            font-size: 14px;
            line-height: 1;
        }
        .cat-toggle-btn:hover {
            color: #007bff;
        }
    </style>

    <div class="header-container d-flex">

        <div class="header-logo col-md-3">
            <a href="/" class="logo-link">
                <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
            </a>
        </div>

        <nav class="main-nav col-md-9 d-flex">
            <ul class="navbar-nav flex-row w-100">

                @foreach ($categories->take(9) as $category)
                    <li class="nav-item dropdown me-3 text-center cat-item">

                        {{-- LINK DANH MỤC CHA → LUÔN ĐIỀU HƯỚNG --}}
                        <a class="nav-link d-flex flex-column align-items-center"
                           href="{{ url('/category/' . $category->slug) }}">

                            @if ($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->name }}"
                                     class="rounded"
                                     style="width:40px; height:40px; object-fit:cover; margin-bottom:5px;">
                            @endif

                            {{ $category->name }}
                        </a>

                        {{-- NÚT MỞ DROPDOWN (nếu có con) --}}
                        @if ($category->children->count())
                            <button class="cat-toggle-btn" 
                                    data-bs-toggle="dropdown" 
                                    aria-expanded="false">
                                ▾
                            </button>

                            <ul class="dropdown-menu">
                                @foreach ($category->children as $child)
                                    <li>
                                        <a class="dropdown-item" href="{{ url('/category/' . $child->slug) }}">
                                            {{ $child->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @endif

                    </li>
                @endforeach

            </ul>

            {{-- ACTIONS --}}
            <div class="header-actions">

                <a class="nav-link" href="{{ route('cart.index') }}">
                    Giỏ hàng
                    @if (session('cart'))
                        ({{ count(session('cart')) }})
                    @endif
                </a>

                <a href="/services" class="action-btn text-link">Dịch vụ</a>

                @if (Auth::check())
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown click-dropdown">
                            <a class="nav-link dropdown-toggle fw-medium text-dark" href="#" id="userDropdown"
                               role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name ?? Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Thông tin tài khoản</a></li>
                                <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <a class="dropdown-item text-danger" href="#"
                                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        Đăng xuất
                                    </a>
                                </li>
                            </ul>
                        </li>
                    </ul>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>

                @else
                    <a href="{{ route('login') }}">Đăng nhập</a>
                @endif
            </div>
        </nav>
    </div>

    <script>
        // Dropdown chỉ mở khi click vào nút "▾"
        document.querySelectorAll(".cat-toggle-btn").forEach(btn => {
            btn.addEventListener("click", function (e) {
                e.stopPropagation();
                let menu = this.parentElement.querySelector(".dropdown-menu");
                menu.classList.toggle("show");
            });
        });

        // Click ra ngoài thì đóng dropdown
        document.addEventListener("click", function () {
            document.querySelectorAll(".dropdown-menu.show").forEach(menu => {
                menu.classList.remove("show");
            });
        });
    </script>

</header>
