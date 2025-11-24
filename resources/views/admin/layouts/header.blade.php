<div class="header">
    <span>Xin chào, {{ auth()->user()->name }}</span>
    <form class="logout-form" method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" style="background:none; border:none; color:#fff; cursor:pointer;">Đăng xuất</button>
    </form>
</div>
