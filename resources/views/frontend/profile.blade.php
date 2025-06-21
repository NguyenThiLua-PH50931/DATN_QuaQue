@extends('layouts.frontend')
@section('title', 'Chỉnh sửa hồ sơ')
@section('contents')
<section class="contact-box-section py-5">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow-sm border-0">
            <div class="card-body">

                {{-- Hiển thị thông báo thành công --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                {{-- Hiển thị lỗi validation chung --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('update') }}" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')

                    {{-- Họ tên --}}
                    <div class="mb-4 position-relative">
                        <label for="name" class="form-label fw-semibold">Họ tên</label>
                        <input type="text" id="name" name="name"
                            class="form-control @error('name') is-invalid @enderror ps-5" placeholder="Nhập họ tên"
                            value="{{ old('name', $user->name ?? '') }}">
                        <i class="fa-solid fa-user position-absolute"
                            style="left: 15px; top: 38px; color: #6c757d;"></i>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Số điện thoại --}}
                    <div class="mb-4 position-relative">
                        <label for="phone" class="form-label fw-semibold">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" maxlength="10"
                            class="form-control @error('phone') is-invalid @enderror ps-5"
                            placeholder="Nhập số điện thoại"
                            oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);"
                            value="{{ old('phone', $user->phone ?? '') }}">
                        <i class="fa-solid fa-mobile-screen-button position-absolute"
                            style="left: 15px; top: 38px; color: #6c757d;"></i>
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="mb-4 position-relative">
                        <label for="email" class="form-label fw-semibold">Email</label>
                        <input type="email" id="email" name="email"
                            class="form-control @error('email') is-invalid @enderror ps-5" placeholder="Nhập email"
                            value="{{ old('email', $user->email ?? '') }}">
                        <i class="fa-solid fa-envelope position-absolute"
                            style="left: 15px; top: 38px; color: #6c757d;"></i>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Ảnh đại diện --}}
                    <div class="mb-4">
                        <label for="avatar" class="form-label fw-semibold">Ảnh đại diện</label>
                        <input type="file" id="avatar" name="avatar"
                            class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        @error('avatar')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror

                        @if (!empty($user->avatar))
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar"
                                    class="rounded-circle" width="100" height="100" style="object-fit: cover;">
                            </div>
                        @endif
                    </div>

                    {{-- Mật khẩu mới --}}
                    <div class="mb-4 position-relative">
                        <label for="password" class="form-label fw-semibold">Mật khẩu mới</label>
                        <input type="password" id="password" name="password"
                            class="form-control @error('password') is-invalid @enderror ps-5"
                            placeholder="Để trống nếu không đổi">
                        <i class="fa-solid fa-lock position-absolute"
                            style="left: 15px; top: 38px; color: #6c757d;"></i>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Xác nhận lại mật khẩu --}}
                    <div class="mb-4 position-relative">
                        <label for="password_confirmation" class="form-label fw-semibold">Xác nhận lại mật khẩu</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                            class="form-control @error('password_confirmation') is-invalid @enderror ps-5"
                            placeholder="Nhập lại mật khẩu mới">
                        <i class="fa-solid fa-lock position-absolute"
                            style="left: 15px; top: 38px; color: #6c757d;"></i>
                        @error('password_confirmation')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Nút gửi --}}
                    <button type="submit" class="btn btn-animation btn-md fw-bold ms-auto">
                        Chỉnh sửa
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
