<header class="main-header">
    <div class="header-container">

<div class="header-logo">
<a href="/" class="logo-link">
    <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
</a>
</div>
        <nav class="main-nav">
<ul class="navbar-nav ms-auto">
    @foreach ($categories->take(9) as $category)
        <li class="nav-item">
            <a class="nav-link" href="{{ url('/category/' . $category->slug) }}">
                {{ $category->name }}
            </a>
        </li>
    @endforeach
</ul>        </nav>

        <div class="header-actions">
            <a href="/services" class="action-btn text-link">Dịch vụ</a>
            <a href="/news" class="action-btn text-link">Tin tức</a>
<a 
    href="{{ url('login') }}" 
    class="{{ request()->is('login') ? 'active' : '' }}">
    Đăng nhập
</a>            {{-- <a href="/register" class="action-btn register-btn primary-bg">ĐĂNG KÝ</a> --}}
        </div>

    </div>
</header>