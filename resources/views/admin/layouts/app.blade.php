<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Toy Shop Admin</title>
    <link rel="stylesheet" href="{{ asset('css/appadmin.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    {{-- Toast phải nằm bên trong body --}}
    <x-toast />

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        window.showToast = function (message, type = 'success') {
            const toastEl = document.getElementById('appToast');
            const msgEl = document.getElementById('appToastMessage');

            toastEl.classList.remove('bg-success', 'bg-danger', 'bg-warning');

            if (type === 'success') toastEl.classList.add('bg-success');
            if (type === 'error') toastEl.classList.add('bg-danger');
            if (type === 'warning') toastEl.classList.add('bg-warning');

            msgEl.innerText = message;

            new bootstrap.Toast(toastEl).show();
        };
    </script>

    {{-- Hiển thị toast từ session --}}
    @if (session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast("{{ session('success') }}", "success");
        });
    </script>
    @endif

    @if (session('error'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            showToast("{{ session('error') }}", "error");
        });
    </script>
    @endif

    {{-- Header --}}
    @include('admin.layouts.header')

    <div class="d-flex">
        {{-- Sidebar --}}
        <div class="bg-dark text-white p-3" style="width:15%; min-height:100vh;">
            @include('admin.layouts.sidebar')
        </div>

        {{-- Content --}}
        <main class="flex-grow-1 p-4">
            @yield('content')
        </main>
    </div>

</body>
</html>
