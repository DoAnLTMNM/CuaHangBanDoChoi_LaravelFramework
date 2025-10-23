<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Trang người dùng')</title>
        @vite(['resources/css/auth.css', 'resources/js/app.js'])

</head>
<body>
    {{-- Header --}}
    @include('layouts.header')

    {{-- Nội dung chính --}}
    <main class="container py-5">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
</body>
</html>
