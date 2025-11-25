<nav class="navbar navbar-expand-lg navbar-dark bg-secondary px-4">
    <a class="navbar-brand" href="{{ route('admin.dashboard') }}">Admin Panel</a>

    <div class="ms-auto">
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="adminDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    {{ auth()->user()->name }}
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="adminDropdown">
                    <li><a class="dropdown-item" href="#">Tài khoản</a></li>
                    <li><a class="dropdown-item" href="#">Cài đặt</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item" href="{{ route('admin.logout') }}"
                           onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            Đăng xuất
                        </a>
                        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
