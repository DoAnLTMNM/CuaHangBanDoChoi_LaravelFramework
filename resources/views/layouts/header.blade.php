<header class="main-header">
    <div class="header-container">

<div class="header-logo">
<a href="/" class="logo-link">
    <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
</a>
</div>
        <nav class="main-nav">
<ul class="navbar-nav flex-row justify-content-center w-100">
    @foreach ($categories->take(9) as $category)
        @if($category->children->count())
            <li class="nav-item dropdown mx-3">
                <a class="nav-link dropdown-toggle" href="{{ url('/category/' . $category->slug) }}"
                   id="navbarDropdown{{ $category->id }}" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    {{ $category->name }}
                </a>
                <ul class="dropdown-menu" aria-labelledby="navbarDropdown{{ $category->id }}">
                    @foreach($category->children as $child)
                        <li>
                            <a class="dropdown-item" href="{{ url('/category/' . $child->slug) }}">
                                {{ $child->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        @else
            <li class="nav-item mx-3">
                <a class="nav-link" href="{{ url('/category/' . $category->slug) }}">
                    {{ $category->name }}
                </a>
            </li>
        @endif
    @endforeach
</ul>
</nav>

        <div class="header-actions">
            <!-- Link giỏ hàng -->
                    <a class="nav-link" href="{{ route('cart.index') }}">
                        Giỏ hàng
                        @if(session('cart'))
                            ({{ count(session('cart')) }})
                        @endif
                    </a>
            <a href="/services" class="action-btn text-link">Dịch vụ</a>
            {{-- <a href="/news" class="action-btn text-link">Tin tức</a> --}}
<a 
    href="{{ url('login') }}" 
    class="{{ request()->is('login') ? 'active' : '' }}">
    Đăng nhập
</a>            {{-- <a href="/register" class="action-btn register-btn primary-bg">ĐĂNG KÝ</a> --}}
        </div>

    </div>
</header>