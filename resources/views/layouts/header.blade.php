<header class="main-header">
    <div class="header-container">

<div class="header-logo col-md-3">
<a href="/" class="logo-link">
    <img src="{{ asset('storage/logo-website-123.png') }}" alt="GAME HOBBY Logo" class="logo-img">
</a>
</div>
        <nav class="main-nav col-md-9 d-flex">
            <ul class="navbar-nav flex-row w-100">
    @foreach ($categories->take(9) as $category)
        @if($category->children->count())
            <li class="nav-item dropdown me-3 text-center">
                <a class="nav-link dropdown-toggle d-flex flex-column align-items-center" 
                   href="{{ url('/category/' . $category->slug) }}"
                   id="navbarDropdown{{ $category->id }}" role="button" data-bs-toggle="dropdown"
                   aria-expanded="false">
                    @if ($category->image)
                        <img src="{{ asset('storage/' . $category->image) }}" 
                             alt="{{ $category->name }}" 
                             class="rounded" 
                             style="width:40px; height:40px; object-fit:cover; margin-bottom:5px;">
                    @endif
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
            <li class="nav-item me-3 text-center">
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
            </li>
        @endif
    @endforeach
</ul>
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
@if(Auth::check())
    <span>Xin chào, {{ Auth::user()->name }}</span>
    <a href="{{ route('logout') }}"
       onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        Đăng xuất
    </a>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
        @csrf
    </form>
@else
    <a href="{{ route('login') }}" 
       class="{{ request()->is('login') ? 'active' : '' }}">
       Đăng nhập
    </a>
    <a href="{{ route('register') }}" 
       class="{{ request()->is('register') ? 'active' : '' }}">
       Đăng ký
    </a>
@endif
        </div>
</nav>



    </div>
</header>







{{-- <ul class="navbar-nav flex-row justify-content-center w-100">
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
</ul> --}}

