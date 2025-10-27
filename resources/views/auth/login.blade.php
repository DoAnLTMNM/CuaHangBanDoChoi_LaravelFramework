@extends('layouts.app2')

@section('title', 'Đăng nhập hoặc Đăng ký')

@section('content')
<div class="auth-page-wrapper">
    <div class="auth-card">

        {{-- Tabs --}}
        <div class="auth-tabs">
            <button class="tab-item active" id="login-tab">Đăng nhập</button>
            <button class="tab-item" id="register-tab">Đăng ký</button>
        </div>

        {{-- Form đăng nhập --}}
        <div class="auth-form" id="login-form">
            <form>
                <label for="login_phone">Số điện thoại</label>
                <input type="text" id="login_phone" name="phone" placeholder="Nhập số điện thoại" required>

                <label for="login_password">Mật khẩu</label>
                <div class="password-input-wrapper">
                    <input type="password" id="login_password" name="password" placeholder="Nhập mật khẩu" required>
                    <span class="password-toggle"><i class="far fa-eye-slash"></i></span>
                </div>
                
                <a href="#" class="forgot-password-link">Quên mật khẩu?</a>

                {{-- @include('auth.benefits') --}}

                <button type="submit" class="auth-btn submit-btn primary-bg">Đăng Nhập</button>
            </form>
        </div>
        
        {{-- Form đăng ký --}}
        <div class="auth-form hidden" id="register-form">
            <form>
                <label for="reg_phone">Số điện thoại</label>
                <input type="text" id="reg_phone" name="phone" placeholder="Nhập số điện thoại" required>

                {{-- @include('auth.benefits') --}}

                <button type="submit" class="auth-btn submit-btn primary-bg">Gửi mã xác nhận</button>
            </form>
        </div>
        
        <div class="social-login-separator">Hoặc</div>

        <button class="auth-btn social-btn google-btn">
            <i class="fab fa-google"></i> Tiếp tục với Google
        </button>

        <button class="auth-btn social-btn facebook-btn">
            <i class="fab fa-facebook-f"></i> Tiếp tục với Facebook
        </button>

    </div>
</div>

{{-- Script chuyển tab --}}
<script>
    const loginTab = document.getElementById('login-tab');
    const registerTab = document.getElementById('register-tab');
    const loginForm = document.getElementById('login-form');
    const registerForm = document.getElementById('register-form');

    loginTab.onclick = () => {
        loginTab.classList.add('active');
        registerTab.classList.remove('active');
        loginForm.classList.remove('hidden');
        registerForm.classList.add('hidden');
    };

    registerTab.onclick = () => {
        registerTab.classList.add('active');
        loginTab.classList.remove('active');
        registerForm.classList.remove('hidden');
        loginForm.classList.add('hidden');
    };
</script>
@endsection
