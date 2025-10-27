<header class="main-header bg-gray-800 text-white shadow-md">
    <div class="header-container max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between items-center h-16">
        <!-- Logo -->
        <div class="flex items-center">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                <img src="{{ asset('images/logo.png') }}" alt="Admin Logo" class="h-10 w-auto">
                <span class="text-xl font-bold">Admin Panel</span>
            </a>
        </div>

        <!-- Navigation Menu -->
        <nav class="hidden md:flex space-x-6">
            <a href="{{ route('admin.dashboard') }}" class="hover:text-gray-300 {{ Route::is('admin.dashboard') ? 'text-blue-400' : '' }}">Dashboard</a>
            <a href="{{ route('admin.users') }}" class="hover:text-gray-300 {{ Route::is('admin.users') ? 'text-blue-400' : '' }}">Users</a>
            <a href="{{ route('admin.settings') }}" class="hover:text-gray-300 {{ Route::is('admin.settings') ? 'text-blue-400' : '' }}">Settings</a>
        </nav>

        <!-- User Info & Dropdown -->
        <div class="relative flex items-center space-x-4">
            <span class="text-sm">{{ Auth::user()->name }}</span>
            <img src="{{ Auth::user()->avatar ?? asset('images/default-avatar.png') }}" alt="User Avatar" class="h-8 w-8 rounded-full">
            <div class="relative">
                <button class="focus:outline-none" onclick="toggleDropdown()">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
                <div id="userDropdown" class="hidden absolute right-0 mt-2 w-48 bg-white text-gray-800 rounded-md shadow-lg z-10">
                    <a href="{{ route('admin.profile') }}" class="block px-4 py-2 hover:bg-gray-100">Profile</a>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="block px-4 py-2 hover:bg-gray-100">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</header>

<!-- JavaScript để toggle dropdown -->
<script>
    function toggleDropdown() {
        document.getElementById('userDropdown').classList.toggle('hidden');
    }
</script>