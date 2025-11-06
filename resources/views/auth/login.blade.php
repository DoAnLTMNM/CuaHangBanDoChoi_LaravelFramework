{{-- <x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}
@extends('layouts.app2')

@section('content')
<div class="container mt-5 mb-5" style="max-width: 450px;">
    <div class="card border-0">
        <div class="card-header bg-white border-0 p-0">
            <ul class="nav nav-tabs justify-content-center" id="authTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active fw-bold text-dark fs-5 py-3 px-4" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-content" type="button" role="tab" aria-controls="login-content" aria-selected="true" style="border-bottom: 3px solid #dc3545;">
                        Đăng nhập
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link fw-bold text-muted fs-5 py-3 px-4" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-content" type="button" role="tab" aria-controls="register-content" aria-selected="false">
                        Đăng ký
                    </button>
                </li>
            </ul>
        </div>

        <div class="card-body p-4">
            <div class="tab-content" id="authTabContent">

                {{-- --- ĐĂNG NHẬP (Login Content) --- --}}
                <div class="tab-pane fade show active" id="login-content" role="tabpanel" aria-labelledby="login-tab">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="phone" class="form-label text-muted fw-semibold">Số điện thoại</label>
                            <input id="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" type="text" name="phone"
                                value="{{ old('phone') }}" required autofocus autocomplete="tel">
                            @error('phone')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                            {{-- Giữ lại email cho trường hợp cần xác thực, bạn có thể ẩn đi hoặc thay đổi logic controller --}}
                            <input type="hidden" name="email" value="{{ old('email') ?? 'default@email.com' }}"> 
                        </div>

                        <div class="mb-3 position-relative">
                            <label for="password_login" class="form-label text-muted fw-semibold">Mật khẩu</label>
                            <input id="password_login" class="form-control form-control-lg @error('password') is-invalid @enderror" type="password" name="password" required autocomplete="current-password">
                            
                            {{-- Icon mắt để show/hide password --}}
                            <span class="position-absolute top-50 end-0 translate-middle-y me-3 text-muted" style="cursor: pointer; margin-top: 1rem;" onclick="togglePasswordVisibility('password_login')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                                    <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13.133 13.133 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13.133 13.133 0 0 1 14.828 8c-.058.087-.122.183-.195.288-.335.48-.83 1.12-1.465 1.849C11.859 12.35 10.08 13.5 8 13.5c-2.12 0-3.879-1.168-5.168-2.457A13.134 13.134 0 0 1 1.172 8z"/>
                                    <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
                                </svg>
                            </span>
                            
                            <div class="d-flex justify-content-end">
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none small text-muted mt-2" href="{{ route('password.request') }}">
                                        Quên mật khẩu?
                                    </a>
                                @endif
                            </div>
                            @error('password')
                                <span class="text-danger small">{{ $message }}</span>
                            @enderror
                        </div>
                        
                        {{-- Phần Thông tin Đăng ký thành viên NSHOP --}}
                        <div class="mt-4 pt-3 border-top">
                            <p class="fw-bold text-danger small mb-2">ĐĂNG KÝ THÀNH VIÊN NSHOP - NHẬN NHIỀU ƯU ĐÃI HẤP DẪN</p>
                            <p class="small text-muted">
                                Hãy đăng ký để trở thành thành viên của nShop bằng chính <strong class="text-dark">số điện thoại mua hàng</strong> của bạn để có thể:
                            </p>
                            <ul class="small text-muted" style="list-style-type: decimal; padding-left: 20px;">
                                <li><strong>Đánh giá đơn hàng bạn vừa mua và kiểm tra lịch sử mua hàng:</strong> Bạn có thể chọn đơn hàng ở Danh sách đơn hàng và Đánh giá sản phẩm, dịch vụ của nShop để nShop có thể nâng cao sản phẩm và dịch vụ để phục vụ bạn tốt hơn.</li>
                                <li><strong>Nhận ưu đãi mua sắm chỉ dành riêng cho bạn.</strong></li>
                                <li><strong>Cập nhật thông tin mới nhất từ nShop.</strong></li>
                            </ul>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">
                                ĐĂNG NHẬP
                            </button>
                        </div>
                    </form>
                </div>

                {{-- --- ĐĂNG KÝ (Register Content) --- --}}
                <div class="tab-pane fade" id="register-content" role="tabpanel" aria-labelledby="register-tab">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="mb-3">
                            <label for="name" class="form-label text-muted fw-semibold">Họ và tên</label>
                            <input id="name" type="text" name="name" class="form-control form-control-lg @error('name') is-invalid @enderror" value="{{ old('name') }}" required autofocus autocomplete="name">
                            @error('name')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="mb-3">
                            <label for="phone_register" class="form-label text-muted fw-semibold">Số điện thoại</label>
                            <input id="phone_register" type="text" name="phone" class="form-control form-control-lg @error('phone') is-invalid @enderror" value="{{ old('phone') }}" required autocomplete="tel">
                            @error('phone')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email_register" class="form-label text-muted fw-semibold">Địa chỉ Email (tùy chọn)</label>
                            <input id="email_register" type="email" name="email" class="form-control form-control-lg @error('email') is-invalid @enderror" value="{{ old('email') }}" autocomplete="username">
                            @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="password_register" class="form-label text-muted fw-semibold">Mật khẩu</label>
                            <input id="password_register" type="password" name="password" class="form-control form-control-lg @error('password') is-invalid @enderror" required autocomplete="new-password">
                            @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-4">
                            <label for="password_confirmation" class="form-label text-muted fw-semibold">Xác nhận mật khẩu</label>
                            <input id="password_confirmation" type="password" name="password_confirmation" class="form-control form-control-lg" required autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-danger btn-lg fw-bold">ĐĂNG KÝ</button>
                        </div>
                        
                        <div class="text-center mt-3">
                            <a href="{{ route('login') }}" class="text-decoration-none small text-muted" onclick="document.getElementById('login-tab').click(); return false;">Đã có tài khoản? Đăng nhập ngay</a>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
// JavaScript để chuyển đổi giữa Đăng nhập và Đăng ký tab
document.addEventListener('DOMContentLoaded', function () {
    const authTab = document.getElementById('authTab');
    const navLinks = authTab.querySelectorAll('.nav-link');

    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            // Xóa style của tất cả link
            navLinks.forEach(l => {
                l.style.borderBottom = 'none';
                l.classList.remove('text-dark');
                l.classList.add('text-muted');
            });
            // Thêm style cho link đang active
            this.style.borderBottom = '3px solid #dc3545';
            this.classList.remove('text-muted');
            this.classList.add('text-dark');
        });
    });

    // Hàm show/hide password (cho trường Mật khẩu Đăng nhập)
    window.togglePasswordVisibility = function(fieldId) {
        const passwordField = document.getElementById(fieldId);
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
        } else {
            passwordField.type = 'password';
        }
    }
});
</script>
@endsection