<header class="main-header">
    <div class="header-container">

<div class="header-logo">
<a href="/" class="logo-link">
    <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
</a>
</div>

        <nav class="main-nav">
            <ul class="nav-list">
                <li class="nav-item">
                    <a href="/nintendo" class="nav-link">
                        <img src="icon-nintendo.svg" alt="Nintendo" class="nav-icon">
                        <span>Nintendo</span>
                    </a>
                </li>
              
            </ul>
        </nav>

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