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
                                     {{-- @error('password')
                                        <div class="text-danger mt-1">{{ $message }}</div>
                                    @enderror --}}
                                </div>

                                <!-- Sign Up Button -->
                                <div class="col-12">
                                    <button class="btn btn-animation w-100" type="submit">Đăng ký</button>
                                </div>
                            </form>
                        </div>

                        <div class="other-log-in">
                            <h6>hoặc</h6>
                        </div>

                        <div class="log-in-button">
                            <ul>
                                <li>
                                    <a href="https://accounts.google.com/signin/v2/identifier?flowName=GlifWebSignIn&flowEntry=ServiceLogin"
                                        class="btn google-button w-100">
                                        <img src="{{ asset('frontend/assets/images/inner-page/google.png') }}" class="blur-up lazyload" alt="">
                                        Đăng nhập với Google
                                    </a>
                                </li>
                                <li>
                                    <a href="https://www.facebook.com/" class="btn google-button w-100">
                                        <img src="{{ asset('frontend/assets/images/inner-page/facebook.png') }}" class="blur-up lazyload" alt="">
                                        Đăng nhập với Facebook
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="sign-up-box">
                            <h4>Bạn đã có tài khoản?</h4>
                            <a href="{{ route('login') }}">Đăng nhập</a>
                        </div>
                    </div>
                </div>

                <div class="col-xxl-7 col-xl-6 col-lg-6"></div>
            </div>
        </div>
    </section>
    <!-- log in section end -->

    <div class="theme-option">
        <div class="back-to-top">
            <a id="back-to-top" href="#"><i class="fas fa-chevron-up"></i></a>
        </div>
    </div>

    <div class="bg-overlay"></div>

@endsection
