@extends('layouts.frontend')
@section('title', 'Đăng ký')
@section('contents')

<!-- log in section start -->
<section class="log-in-section section-b-space">
    <div class="container-fluid-lg w-100">
        <div class="row">
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="{{ asset('frontend/assets/images/inner-page/sign-up.png') }}" class="img-fluid" alt="">
                </div>
            </div>

            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title">
                        <h3>Đăng ký tài khoản mới</h3>
                    </div>

                    <div class="input-box">
                        <form method="POST" action="{{ route('register') }}" class="row g-4">
                            @csrf

                            <!-- Full Name -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                                        value="{{ old('name') }}" placeholder="Họ và tên">
                                    <label for="name">Họ và tên</label>
                                </div>
                                @error('name')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Email Address -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                        value="{{ old('email') }}" placeholder="Email">
                                    <label for="email">Địa chỉ email</label>
                                </div>
                                @error('email')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Phone -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone"
                                        value="{{ old('phone') }}" placeholder="Số điện thoại">
                                    <label for="phone">Số điện thoại</label>
                                </div>
                                @error('phone')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Password -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                                        placeholder="Mật khẩu">
                                    <label for="password">Mật khẩu</label>
                                </div>
                                @error('password')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Confirm Password -->
                            <div class="col-12">
                                <div class="form-floating theme-form-floating">
                                    <input type="password" class="form-control" id="password_confirmation"
                                        name="password_confirmation" placeholder="Xác nhận mật khẩu">
                                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="forgot-box">
                                    <div class="form-check ps-0 m-0 remember-box">
                                        <input class="checkbox_animated check-box" type="checkbox" id="showPass">
                                        <label class="form-check-label" for="showPass">Hiện mật khẩu
                                    </div>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-animation w-100" type="submit">Đăng ký</button>
                            </div>
                        </form>
                    </div>
                    <div class="other-log-in">
                            <h6></h6>
                        </div>
                    <div class="sign-up-box">
                        <h4>Bạn đã có tài khoản?</h4>
                        <a href="{{ route('login') }}">Đăng nhập</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xxl-7 col-xl-6 col-lg-6"></div>
    </div>
</section>
<!-- log in section end -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const showPassCheckbox = document.getElementById('showPass');
    const passInput = document.getElementById('password');
    const confirmInput = document.getElementById('password_confirmation');

    showPassCheckbox.addEventListener('change', function() {
        const type = this.checked ? 'text' : 'password';
        passInput.type = type;
        confirmInput.type = type;
    });
});
</script>

@endsection