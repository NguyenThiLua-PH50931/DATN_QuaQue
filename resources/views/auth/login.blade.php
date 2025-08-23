@extends('layouts.frontend')
@section('title', 'Đăng nhập')
@section('contents')
<!-- log in section start -->
<section class="log-in-section background-image-2 section-b-space">
    <div class="container-fluid-lg w-100">
        <div class="row">
            <div class="col-xxl-6 col-xl-5 col-lg-6 d-lg-block d-none ms-auto">
                <div class="image-contain">
                    <img src="{{ asset('frontend/assets/images/inner-page/log-in.png') }}" class="img-fluid"
                        alt="">
                </div>
            </div>

            <div class="col-xxl-4 col-xl-5 col-lg-6 col-sm-8 mx-auto">
                <div class="log-in-box">
                    <div class="log-in-title">
                        <h3>Đăng nhập</h3>
                    </div>
                    @if (session('status'))
                    <div class="alert alert-success mt-3">
                        {{ session('status') }}
                    </div>
                    @endif
                    <div class="input-box">
                        <form method="POST" class="row g-4" action="{{ route('checklogin') }}" id="loginForm">
                            @csrf
                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Email Address">
                                    <label for="email">Địa chỉ Email</label>
                                </div>
                                @error('email')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="form-floating theme-form-floating log-in-form">
                                    <input type="password" class="form-control" name="password" id="password"
                                        placeholder="Password">
                                    <label for="password">Mật khẩu</label>
                                </div>
                                @error('password')
                                <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <div class="forgot-box">
                                    <div class="form-check ps-0 m-0 remember-box">
                                        <input class="checkbox_animated check-box" type="checkbox"
                                            id="showPass">
                                        <label class="form-check-label" for="showPass">Hiện mật khẩu</label>
                                    </div>
                                    <a href="{{ route('forgot') }}" class="forgot-password">Quên mật khẩu</a>
                                </div>
                            </div>

                            <div class="col-12">
                                <button class="btn btn-animation w-100 justify-content-center" type="submit">
                                    Đăng nhập
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="other-log-in">
                        <h6></h6>
                    </div>
                </div>
            </div>
        </div>
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