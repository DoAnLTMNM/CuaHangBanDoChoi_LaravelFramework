<div class="sidebar" id="sidebar">
    <div class="sidebar-header d-flex justify-content-between align-items-center px-3 mb-3">
        <button class="btn btn-sm btn-light d-md-none" id="sidebarCollapse">
            <i class="bi bi-list"></i>
        </button>
    </div>

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
                <i class="arrow bi bi-chevron-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.categories.index') }}">Danh mục sản phẩm</a></li>
                <li><a href="{{ route('admin.products.index') }}">Danh sách sản phẩm</a></li>
            </ul>
        </li>

        <!-- QUẢN LÝ BANNER -->
        <li class="menu-item has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle">
                <span>🏞️ Quản lý Banner</span>
                <i class="arrow bi bi-chevron-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.banners.index') }}">Danh sách Banner</a></li>
            </ul>
        </li>

        <!-- QUẢN LÝ ĐƠN HÀNG -->
        <li class="menu-item has-submenu">
            <a href="javascript:void(0)" class="submenu-toggle">
                <span>🧾 Quản lý đơn hàng</span>
                <i class="arrow bi bi-chevron-down"></i>
            </a>
            <ul class="submenu">
                <li><a href="{{ route('admin.orders.index') }}">Danh sách đơn hàng</a></li>
            </ul>
        </li>
    </ul>
</div>

<style>
/* Sidebar chung */
.sidebar {
    width: 220px;
    min-height: 100vh;
    color: #fff;
    padding: 1rem 0;
    font-family: Arial, sans-serif;
    transition: transform 0.3s ease;
}
.sidebar .menu {
    list-style: none;
    padding: 0;
    margin: 0;
}
.sidebar .menu li a {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 0.75rem 1.2rem;
    color: #fff;
    text-decoration: none;
    font-weight: 500;
    transition: background 0.2s;
    border-radius: 4px;
}
.sidebar .menu li a:hover {
    background-color: #343454;
}
.sidebar .menu li.has-submenu > .submenu {
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.3s ease;
    list-style: none;
    padding-left: 0;
}
.sidebar .menu li.has-submenu.active > .submenu {
    /* max-height set by JS */
}
.sidebar .menu li .submenu li a {
    padding: 0.5rem 2rem;
    display: block;
    color: #d1d1e0;
    font-size: 0.95rem;
}
.sidebar .menu li .submenu li a:hover {
    background-color: #2e2e4d;
}
.sidebar .menu li .arrow {
    transition: transform 0.3s;
}
.sidebar .menu li.has-submenu.active > a .arrow {
    transform: rotate(180deg);
}

/* Responsive: ẩn sidebar mobile */
@media (max-width: 768px){
    .sidebar {
        position: fixed;
        top: 0;
        left: 0;
        transform: translateX(-100%);
        z-index: 1000;
    }
    .sidebar.show {
        transform: translateX(0);
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function(){
    // Toggle submenu
    document.querySelectorAll('.submenu-toggle').forEach(item => {
        item.addEventListener('click', e => {
            const parent = item.parentElement;
            const submenu = parent.querySelector('.submenu');

            parent.classList.toggle('active');

            // slide toggle
            if(submenu.style.maxHeight){
                submenu.style.maxHeight = null;
            } else {
                submenu.style.maxHeight = submenu.scrollHeight + "px";
            }

            // Chỉ mở 1 submenu
            document.querySelectorAll('.menu li.has-submenu').forEach(li => {
                if(li !== parent){
                    li.classList.remove('active');
                    li.querySelector('.submenu').style.maxHeight = null;
                }
            });
        });
    });

    // Toggle sidebar mobile
    const sidebar = document.getElementById('sidebar');
    const toggleBtn = document.getElementById('sidebarCollapse');
    if(toggleBtn){
        toggleBtn.addEventListener('click', () => {
            sidebar.classList.toggle('show');
        });
    }
});
</script>
