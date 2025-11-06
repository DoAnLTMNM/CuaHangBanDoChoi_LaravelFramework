@extends('layouts.app2')

@section('content')
<div class="d-flex justify-content-center align-items-center">
    <div class="w-50 mt-5" style="max-width: 600px;">
        <h3 class="text-center">Đăng ký tài khoản</h3>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Họ và tên</label>
                <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autofocus autocomplete="name">
                @error('name')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email Address -->
            <div class="mb-3">
                <label for="email" class="form-label">Địa chỉ Email</label>
                <input id="email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
                @error('email')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Password -->
            <div class="mb-3">
                <label for="password" class="form-label">Mật khẩu</label>
                <input id="password" type="password" name="password" class="form-control" required autocomplete="new-password">
                @error('password')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div class="mb-4">
                <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                @error('password_confirmation')
                    <div class="text-danger small mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="d-flex justify-content-between align-items-center">
                <a href="{{ route('login') }}" class="text-decoration-none">Đã có tài khoản?</a>
                
            </div>
            <button type="submit" class="btn btn-primary px-4 w-100 fs-4 fw-semibold">Đăng ký</button>
        </form>
    </div>
</div>

<style>
.title-underline {
    display: inline-block;
    position: relative;
    padding-bottom: 5px;
}
.title-underline::after {
    content: "";
    position: absolute;
    bottom: 0;
    left: 50%;
    width: 50%;
    height: 3px;
    background-color: #0d6efd;
    transform: translateX(-50%);
    border-radius: 2px;
}
</style>
@endsection
