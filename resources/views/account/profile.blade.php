{{-- resources/views/account/index.blade.php --}}
@extends('layouts.app2')

@section('title', 'Thông tin tài khoản')

@section('content')
<div class="container py-4">
    <div class="row g-4">
        <!-- Sidebar -->
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold text-dark mb-4">Trung tâm cá nhân</h5>
                    <ul class="list-unstyled">
                        <li class="mb-3">
                            <a href="{{ route('account.index') }}" class="text-primary fw-medium text-decoration-none">
                                → Tài khoản của tôi
                            </a>
                        </li>
                        <li class="mb-3">
                            <a href="{{ route('address.index') }}" class="text-muted text-decoration-none">
                                → Sổ địa chỉ
                            </a>
                        </li>
                        <li class="mt-4">
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-link text-danger text-decoration-none p-0 border-0 bg-transparent fw-medium">
                                    Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-9">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4 p-md-5">
                    <h4 class="fw-bold mb-4">Thông tin tài khoản</h4>

                    <form action="{{ route('account.update') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- Họ -->
                        <div class="mb-3">
                            <label for="last_name" class="form-label fw-medium">Họ</label>
                            <input type="text" name="last_name" id="last_name" class="form-control @error('last_name') is-invalid @enderror"
                                   value="{{ old('last_name', $user->last_name) }}" required>
                            @error('last_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Tên -->
                        <div class="mb-3">
                            <label for="first_name" class="form-label fw-medium">Tên</label>
                            <input type="text" name="first_name" id="first_name" class="form-control @error('first_name') is-invalid @enderror"
                                   value="{{ old('first_name', $user->first_name) }}" required>
                            @error('first_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Ngày sinh -->
                        <div class="mb-3">
                            <label for="birthday" class="form-label fw-medium">Ngày sinh</label>
                            <input type="text" name="birthday" id="birthday" placeholder="mm/dd/yyyy"
                                   class="form-control @error('birthday') is-invalid @enderror"
                                   value="{{ old('birthday', $user->birthday?->format('m/d/Y')) }}">
                            @error('birthday')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Giới tính -->
                        <div class="mb-3">
                            <label class="form-label fw-medium">Giới tính</label>
                            <div class="d-flex gap-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="female"
                                           {{ old('gender', $user->gender) === 'female' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="female">Nữ</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="male"
                                           {{ old('gender', $user->gender) === 'male' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="male">Nam</label>
                                </div>
                            </div>
                            @error('gender')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                            @enderror
                        </div>

                        <!-- Email (chỉ xem) -->
                        <div class="mb-4">
                            <label class="form-label fw-medium">Email</label>
                            <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly>
                        </div>

                        <!-- Nút cập nhật -->
                        <button type="submit" class="btn btn-danger px-4">Cập nhật</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection