@extends('layouts.frontend')
@section('title', 'Đặt lại mật khẩu')
@section('contents')

<!-- Breadcrumb Section Start -->
<section class="breadscrumb-section pt-0">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-12">
                <div class="breadscrumb-contain">
                    <h2>Đặt lại mật khẩu</h2>
                    <nav>
                        <ol class="breadcrumb mb-0">
                            <li class="breadcrumb-item">
                                <a href="{{ url('/') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active">
                                Đặt lại mật khẩu
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Breadcrumb Section End -->

<!-- Reset Password Section Start -->
<section class="log-in-section section-b-space forgot-section">
    <div class="container-fluid-lg w-100">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="input-box p-4 shadow-sm rounded bg-white">
                    <form method="POST" action="{{ route('password.update') }}" class="row g-4">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}" />

                        <div class="col-12">
                            <div class="form-floating theme-form-floating log-in-form">
                                <input
                                    type="email"
                                    name="email"
                                    id="email"
                                    placeholder="Email"
                                    value="{{ old('email') }}"
                                    class="form-control highlight-input @error('email') is-invalid @enderror"
                                />
                                <label for="email">Email</label>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating theme-form-floating log-in-form">
                                <input
                                    type="password"
                                    name="password"
                                    id="password"
                                    placeholder="Mật khẩu mới"
                                    class="form-control highlight-input @error('password') is-invalid @enderror"
                                />
                                <label for="password">Mật khẩu mới</label>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-floating theme-form-floating log-in-form">
                                <input
                                    type="password"
                                    name="password_confirmation"
                                    id="password_confirmation"
                                    placeholder="Xác nhận mật khẩu"
                                    class="form-control highlight-input"
                                />
                                <label for="password_confirmation">Xác nhận mật khẩu</label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button class="btn btn-animation w-100" type="submit">Đặt lại mật khẩu</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Reset Password Section End -->

<style>
    .highlight-input {
    background-color: #f9faff; /* nền xanh nhẹ */
    border: 1.5px solid #6c63ff; /* viền màu tím đậm */
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.highlight-input:focus {
    border-color: #4a47a3; /* viền tím đậm hơn khi focus */
    box-shadow: 0 0 8px rgba(74, 71, 163, 0.5);
    background-color: #ffffff; /* nền trắng khi nhập */
    outline: none;
}
</style>

@endsection
