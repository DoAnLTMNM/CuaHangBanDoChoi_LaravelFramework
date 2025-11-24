<!DOCTYPE html>
<html>
<head>
    <title>Admin Login</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container mt-5">
        <h2>Đăng nhập Admin</h2>
        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <div>
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <div style="color:red">{{ $message }}</div>
                @enderror
            </div>
            <div>
                <label>Mật khẩu</label>
                <input type="password" name="password" required>
                @error('password')
                    <div style="color:red">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit">Đăng nhập</button>
        </form>
    </div>
</body>
</html>
