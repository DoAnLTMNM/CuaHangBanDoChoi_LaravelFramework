<div class="card shadow-sm">
    <div class="card-body">

        {{-- Thông tin người dùng --}}
        <div class="d-flex align-items-center mb-4">
            <div class="me-3">
                <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff"
                     class="rounded-circle" width="55" height="55">
            </div>
            <div>
                <div class="fw-bold">{{ Auth::user()->name }}</div>
                <small class="text-muted">{{ Auth::user()->email }}</small>
            </div>
        </div>

        <hr>

        {{-- Sidebar menu --}}
        <div class="list-group">

            <a href="{{ route('profile.edit') }}"
               class="list-group-item list-group-item-action
               {{ request()->routeIs('profile.edit') ? 'active' : '' }}">
                <i class="bi bi-person-circle me-2"></i>
                Thông tin tài khoản
            </a>

            <a href="{{ route('orders.index') }}"
               class="list-group-item list-group-item-action
               {{ request()->routeIs('orders.index') ? 'active' : '' }}">
                <i class="bi bi-bag-check me-2"></i>
                Đơn hàng
            </a>

            <a href="{{ route('logout') }}"
               onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
               class="list-group-item list-group-item-action text-danger">
                <i class="bi bi-box-arrow-right me-2"></i>
                Đăng xuất
            </a>

            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                @csrf
            </form>

        </div>

    </div>
</div>
