@extends('layouts.app2')

@section('content')
<div class="container mt-5"style="max-width: 600px;">
    <h3 class="text-center title-underline">Đăng nhập</h3>
    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <label for="email">Địa chỉ Email</label>
            <input id="email" class="form-control mt-1" type="email" name="email"
                value="{{ old('email') }}" required autofocus autocomplete="username">
            @error('email')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Password -->
        <div class="mt-4">
            <label for="password">Mật khẩu</label>
            <input id="password" class="form-control mt-1" type="password" name="password" required autocomplete="current-password">
            @error('password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <!-- Remember Me -->
        <div class="form-check mt-3">
            <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
            <label for="remember_me" class="form-check-label">Ghi nhớ tài khoản</label>
        </div>

        <div class="mt-4 d-flex justify-content-between align-items-center">
            @if (Route::has('password.request'))
                <a class="text-decoration-none small text-muted" href="{{ route('password.request') }}">
                    Quên mật khẩu?
                </a>
            @endif

        </div>
        
            <button type="submit" class="btn btn-primary w-100 fs-4 fw-semibold">
                Đăng nhập
            </button>
    </form>
</div>
@endsection
