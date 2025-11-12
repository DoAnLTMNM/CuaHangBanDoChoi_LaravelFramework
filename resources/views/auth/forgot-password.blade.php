@extends('layouts.app2')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="w-50 mt-5" style="max-width: 600px;">
            <h3 class="text-center title-underline mb-4 w-100">Quên mật khẩu</h3>

            <div class="mb-4 text-muted text-center">
                {{ __('Nhập địa chỉ email của bạn, chúng tôi sẽ gửi liên kết để đặt lại mật khẩu mới.') }}
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        value="{{ old('email') }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">Quay lại đăng nhập</a>
                </div>

                <button type="submit" class="btn btn-primary px-4 w-100 fs-5 fw-semibold">
                    Gửi liên kết đặt lại mật khẩu
                </button>
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
