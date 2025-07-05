@extends('layouts.frontend')
@section('title', 'Chỉnh sửa hồ sơ')
@section('contents')
<style>
    .log-in-box {
        width: calc(100% + 150px);
        margin-left: -20px;
    }
    @media (max-width: 900px) {
        .log-in-box {
            width: 100%;
            margin-left: 0;
        }
    }
</style>
<!-- profile edit section start -->
<section class="log-in-section background-image-2 section-b-space">
    <div class="container-fluid-lg w-100">
        <div class="row">
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="/frontend/assets/images/inner-page/dashboard-profile.png" class="img-fluid" alt="">
                </div>
            </div>
            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title">
                        <h3>Chỉnh sửa hồ sơ</h3>
                    </div>
                    @if(session('success'))
                        <div class="alert alert-success mt-3">{{ session('success') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <div class="input-box">
                        <form method="POST" class="row g-4" action="{{ route('update') }}" enctype="multipart/form-data" novalidate>
                            @csrf
                            @method('PUT')
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="text" id="name" name="name" class="form-control @error('name') is-invalid @enderror" placeholder="Họ tên" value="{{ old('name', $user->name ?? '') }}">
                                    <label for="name">Họ tên</label>
                                </div>
                                @error('name')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="tel" id="phone" name="phone" maxlength="10" class="form-control @error('phone') is-invalid @enderror" placeholder="Số điện thoại" value="{{ old('phone', $user->phone ?? '') }}">
                                    <label for="phone">Số điện thoại</label>
                                </div>
                                @error('phone')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="email" id="email" name="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email" value="{{ old('email', $user->email ?? '') }}">
                                    <label for="email">Email</label>
                                </div>
                                @error('email')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <label for="avatar" class="form-label">Ảnh đại diện</label>
                                <input type="file" id="avatar" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                                @error('avatar')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                                @if (!empty($user->avatar))
                                    <div class="mt-3 text-center">
                                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="avatar" class="rounded-circle" width="80" height="80" style="object-fit: cover;">
                                    </div>
                                @endif
                            </div>
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="password" id="password" name="password" class="form-control @error('password') is-invalid @enderror" placeholder="Mật khẩu mới">
                                    <label for="password">Mật khẩu mới</label>
                                </div>
                                @error('password')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Xác nhận lại mật khẩu">
                                    <label for="password_confirmation">Xác nhận lại mật khẩu</label>
                                </div>
                                @error('password_confirmation')<div class="text-danger small mt-1">{{ $message }}</div>@enderror
                            </div>
                            <div class="col-12">
                                <button class="btn btn-animation w-100 justify-content-center" type="submit">Cập nhật hồ sơ</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- profile edit section end -->
@endsection
