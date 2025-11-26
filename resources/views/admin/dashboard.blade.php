{{-- Nối layout chính --}}
@extends('admin.layouts.app')

{{-- Thay đổi title trang --}}
@section('title', 'Dashboard Admin')

{{-- Nội dung chính --}}
@section('content')
    <div class="container mt-5">
        <h1>Chào mừng Admin: {{ auth()->user()->name }}</h1>
        <p>Đây là trang dashboard admin.</p>

        <ul>
            <li><a href="{{ route('admin.products.index') }}">Thêm sản phẩm</a></li>
            <li><a href="{{ route('admin.logout') }}" 
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                   Đăng xuất
                </a>
            </li>
        </ul>

        <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display:none;">
            @csrf
        </form>
    </div>
@endsection
