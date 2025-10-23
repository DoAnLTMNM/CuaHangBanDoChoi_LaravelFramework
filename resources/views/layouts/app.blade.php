<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

</head>
<body>
 {{-- Header --}}
    @include('layouts.header')

    <div class="container">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-md-3">
                @include('layouts.sidebar')
            </div>

            {{-- Nội dung chính --}}
            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>

    {{-- Footer --}}
    @include('layouts.footer')

</body>
</html>