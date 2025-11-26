<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

</head>
<body class="bg-light">
    <div class="layout-app123">

 {{-- Header --}}
    @include('layouts.header')

<div class="layout-app123"> {{-- dùng cùng class như header --}}
        <div class="row">
            <div class="col-md-3">
                @include('layouts.sidebar')
            </div>

            <div class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>
    </div>
    {{-- Footer --}}
    @include('layouts.footer')

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>