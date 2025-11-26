{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toy Shop Admin</title>
    <link rel="stylesheet" href="{{ asset('css/appadmin.css') }}">
    @if (file_exists(public_path('css/app.css')))
        <link rel="stylesheet" href="{{ asset('css/appadmin.css') }}">
    @endif
</head>
<body class="flex">
    @include('layouts.admin.header')
    <div class="flex-1 ml-64">
    @include('layouts.admin.sidebar')
        <main class="container mx-auto px-4 py-6">
            @yield('content')
        </main>
    </div>
</body>
</html> --}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - nShop Admin</title>
    <link rel="stylesheet" href="{{ asset('css/appadmin.css') }}">
    @stack('styles')
</head>
<body class="bg-gray-100">
    <div class="flex">      
        @include('layouts.admin.sidebar')
        <div class="flex-1 ml-64">
            @include('layouts.admin.header')
            <main class="container mx-auto px-4 py-6">
                @yield('content')
            </main>
        </div>
    </div>
    @stack('scripts')
</body>
</html>