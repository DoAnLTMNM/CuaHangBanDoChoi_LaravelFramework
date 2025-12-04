@extends('layouts.app3')

@include('components.toast')

@section('content')
<div class="container mt-4">
    <h2>Thông tin cá nhân</h2>

    {{-- Hiển thị Toast khi cập nhật thành công --}}
    @if (session('success'))
        <script>
            document.addEventListener("DOMContentLoaded", function () {
                let toastEl = document.getElementById('appToast');
                let toast = new bootstrap.Toast(toastEl);

                document.getElementById('appToastMessage').innerText = "{{ session('success') }}";

                toast.show();
            });
        </script>
    @endif

    <form action="{{ route('profile.update') }}" method="POST" class="mt-3">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label>Họ tên (được phép sửa)</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $user->name) }}">
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="mb-3">
            <label>Email (không được sửa)</label>
            <input type="email" value="{{ $user->email }}" class="form-control" readonly>
        </div>

        <div class="mb-3">
            <label>Giới tính</label>
            <input type="text" class="form-control" value="{{ $user->gender ?? 'N/A' }}" readonly>
        </div>

        <div class="mb-3">
            <label>Ngày tạo tài khoản</label>
            <input type="text" class="form-control" value="{{ $user->created_at }}" readonly>
        </div>

        <button class="btn btn-primary mt-3">Cập nhật</button>
    </form>
</div>
@endsection
