<div class="sidebar">
    <ul class="menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="menu-item">
                <span>📊 Dashboard</span>
            </a>
        </li>

        <!-- QUẢN LÝ SẢN PHẨM -->
        <li class="menu-item has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle">
                <span>🛒 Quản lý sản phẩm</span>
                <span class="arrow">▾</span>
            </a>

            <ul class="submenu">
                <li><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
                <li><a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
            </ul>
        </li>
        <li class="menu-item has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle">
                <span>🏞️ Quản lý Banner</span>
                <span class="arrow">▾</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.banners.index') }}">Danh sách Banner</a></li>
            </ul>
        </li>
                <!-- QUẢN LÝ ĐƠN HÀNG -->
        <li class="menu-item has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle">
                <span>🧾 Quản lý đơn hàng</span>
                <span class="arrow">▾</span>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.orders.index') }}">Danh sách đơn hàng</a></li>
            </ul>
        </li>
    </ul>
</div>
