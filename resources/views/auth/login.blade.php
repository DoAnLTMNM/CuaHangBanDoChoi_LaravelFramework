@extends('layouts.app2')

@section('content')
<div class="d-flex justify-content-center align-items-center">
    {{-- Container chung, lấy style từ code Đăng ký của bạn --}}
    <div class="w-50 mt-5" style="max-width: 600px;"> 

        @php
            // Logic này kiểm tra xem lỗi validation trả về thuộc về form nào
            // Nếu có lỗi 'name' hoặc 'password_confirmation', chúng ta biết đó là từ form Đăng ký.
            $isRegisterError = $errors->has('name') || $errors->has('password_confirmation');
        @endphp

        <ul class="nav nav-tabs nav-fill" id="authTabs" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ !$isRegisterError ? 'active' : '' }}" id="login-tab" data-bs-toggle="tab" data-bs-target="#login-pane" type="button" role="tab" aria-controls="login-pane" aria-selected="{{ !$isRegisterError ? 'true' : 'false' }}">
                    Đăng nhập
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link {{ $isRegisterError ? 'active' : '' }}" id="register-tab" data-bs-toggle="tab" data-bs-target="#register-pane" type="button" role="tab" aria-controls="register-pane" aria-selected="{{ $isRegisterError ? 'true' : 'false' }}">
                    Đăng ký
                </button>
            </li>
        </ul>

        <div class="tab-content" id="authTabsContent">

            <div class="tab-pane fade p-4 {{ !$isRegisterError ? 'show active' : '' }}" id="login-pane" role="tabpanel" aria-labelledby="login-tab" tabindex="0">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="mb-3"> {{-- Thay <div> bằng <div class="mb-3"> --}}
                        {{-- 
                          LƯU Ý: Đổi 'id' và 'for' thành "login_email" 
                          để không bị trùng với form đăng ký.
                          'name="email"' BẮT BUỘC giữ nguyên.
                        --}}
                        <label for="login_email">Địa chỉ Email</label>
                        <input id="login_email" class="form-control mt-1" type="email" name="email"
                            value="{{ old('email') }}" required autofocus autocomplete="username">
                        
                        {{-- Chỉ hiển thị lỗi email nếu tab này đang active --}}
                        @error('email')
                            @if(!$isRegisterError)
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>

                    <div class="mt-4 mb-3"> {{-- Thêm mb-3 --}}
                        <label for="login_password">Mật khẩu</label>
                        <input id="login_password" class="form-control mt-1" type="password" name="password" required autocomplete="current-password">
                        
                        @error('password')
                             @if(!$isRegisterError)
                                <span class="text-danger small mt-1">{{ $message }}</span>
                            @endif
                        @enderror
                    </div>

                    <div class="form-check mt-3">
                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember">
                        <label for="remember_me" class="form-check-label">Ghi nhớ tài khoản</label>
                    </div>

                    <div class="mt-4 d-flex justify-content-end align-items-center"> {{-- Đổi justify-content-between thành end --}}
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none small text-muted" href="{{ route('password.request') }}">
                                Quên mật khẩu?
                            </a>
                        @endif
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fs-4 fw-semibold mt-3"> {{-- Thêm mt-3 --}}
                        Đăng nhập
                    </button>
                </form>
            </div>

            <div class="tab-pane fade p-4 {{ $isRegisterError ? 'show active' : '' }}" id="register-pane" role="tabpanel" aria-labelledby="register-tab" tabindex="0">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">Họ và tên</label>
                        <input id="name" type="text" name="name" class="form-control" value="{{ old('name') }}" required autocomplete="name">
                        @error('name')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="register_email" class="form-label">Địa chỉ Email</label>
                        <input id="register_email" type="email" name="email" class="form-control" value="{{ old('email') }}" required autocomplete="username">
                        
                        @error('email')
                            @if($isRegisterError)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="register_password" class="form-label">Mật khẩu</label>
                        <input id="register_password" type="password" name="password" class="form-control" required autocomplete="new-password">
                        
                        @error('password')
                            @if($isRegisterError)
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @endif
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password_confirmation" class="form-label">Xác nhận mật khẩu</label>
                        <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required autocomplete="new-password">
                        @error('password_confirmation')
                            <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    {{-- Xóa link "Đã có tài khoản?" vì giờ đã là dạng tab --}}
                    <button type="submit" class="btn btn-primary px-4 w-100 fs-4 fw-semibold">Đăng ký</button>
                </form>
            </div>

        </div> </div> </div> <style>
/* Style cho tab giống với hình ảnh của bạn */
.nav-tabs {
    border-bottom: 1px solid #dee2e6; /* Đường kẻ mờ bên dưới */
    margin-bottom: 1rem;
}

.nav-tabs .nav-link {
    border: none;
    border-bottom: 4px solid transparent; /* Gạch chân khi không active */
    color: #6c757d; /* Màu chữ tab không active */
    font-size: 1.25rem;
    font-weight: 500;
    padding-top: 0;
    padding-bottom: 0.75rem;
}

/* Style cho tab khi được chọn (active) */
.nav-tabs .nav-link.active,
.nav-tabs .nav-item.show .nav-link {
    color: #000; /* Màu chữ active (đen) */
    font-weight: 700;
    background-color: transparent;
    border-color: transparent;
    /* Màu gạch chân (đen) - bạn có thể đổi thành màu đỏ nếu muốn */
    border-bottom-color: #000; 
}

/* Style .title-underline gốc từ file đăng ký của bạn */
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