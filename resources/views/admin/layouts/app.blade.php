<!DOCTYPE html>
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
    @include('admin.layouts.sidebar')
    <div class="flex-1 ml-64 d-flex">
        <div>
            @include('admin.layouts.header')
        </div>
        <main class="container mx-auto px-4 py-6">
            @yield('content')
        </main>
    </div>
</body>

</html>
