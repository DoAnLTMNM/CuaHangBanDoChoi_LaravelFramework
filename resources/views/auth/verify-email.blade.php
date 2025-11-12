{{-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-green-600">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout> --}}


@extends('layouts.app2')

@section('content')
    <div class="d-flex justify-content-center align-items-center">
        <div class="w-50 mt-5" style="max-width: 600px;">
            <h3 class="text-center title-underline mb-4">Xác minh email</h3>

            <div class="mb-4 text-muted text-center">
                Cảm ơn bạn đã đăng ký! Vui lòng kiểm tra hộp thư và nhấp vào liên kết xác minh để hoàn tất đăng ký.
                <br>
                Nếu bạn chưa nhận được email, hãy nhấn nút bên dưới để gửi lại.
            </div>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success text-center">
                    Một liên kết xác minh mới đã được gửi đến email bạn đã đăng ký.
                </div>
            @endif

            <div class="d-flex justify-content-between align-items-center mt-4">
                <!-- Gửi lại email -->
                <form method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" class="btn btn-primary px-3">
                        Gửi lại email xác minh
                    </button>
                </form>

                <!-- Đăng xuất -->
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-secondary">
                        Đăng xuất
                    </button>
                </form>
            </div>
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
