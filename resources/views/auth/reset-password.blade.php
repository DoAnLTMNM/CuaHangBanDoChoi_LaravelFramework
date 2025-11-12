{{-- <x-guest-layout>
    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- Password Reset Token -->
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $request->email)" required
                autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Reset Password') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout> --}}


@extends('layouts.app2')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="w-50 mt-5" style="max-width: 600px;">
            <h3 class="text-center title-underline mb-4">Đặt lại mật khẩu</h3>

            <form method="POST" action="{{ route('password.store') }}">
                @csrf

                <!-- Password Reset Token -->
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div class="mb-3">
                    <label for="email" class="form-label">Địa chỉ Email</label>
                    <input id="email" type="email" name="email" class="form-control"
                        value="{{ old('email', $request->email) }}" required autofocus autocomplete="username">
                    @error('email')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Password -->
                <div class="mb-3">
                    <label for="password" class="form-label">Mật khẩu mới</label>
                    <input id="password" type="password" name="password" class="form-control" required
                        autocomplete="new-password">
                    @error('password')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Xác nhận mật khẩu mới</label>
                    <input id="password_confirmation" type="password" name="password_confirmation"
                        class="form-control" required autocomplete="new-password">
                    @error('password_confirmation')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <a href="{{ route('login') }}" class="text-decoration-none">Quay lại đăng nhập</a>
                </div>

                <button type="submit" class="btn btn-primary px-4 w-100 fs-5 fw-semibold">
                    Cập nhật mật khẩu
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
