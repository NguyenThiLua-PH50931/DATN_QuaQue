@extends('layouts.frontend')
@section('title', 'Liên hệ')
@section('contents')
    <section class="contact-box-section section-b-space" style="background: url('/frontend/assets/images/inner-page/log-in-bg.png') center/cover no-repeat;">
        <div class="container-fluid-lg">
            <div class="row g-lg-5 g-3">
                <div class="col-lg-6 d-flex align-items-center justify-content-center">
                    <div class="left-sidebar-box w-100">
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="contact-image text-center d-flex align-items-center justify-content-center" style="min-height: 500px;">
                                    <img src="uploads/anh3.png" class="img-fluid blur-up lazyloaded" alt="" style="max-width: 600px; width: 100%; height: auto; margin: 0 auto; display: block;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="title d-xxl-none d-block">
                        <h2>Liên hệ</h2>
                    </div>
                    <form method="POST" action="{{ route('client.submit') }}">
                        @if (session('success'))
                            <div class="alert alert-success mt-3">
                                {{ session('success') }}
                            </div>
                        @endif
                        @csrf
                        <div class="right-sidebar-box">
                            <div class="row">
                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="first_name" class="form-label">Họ</label>
                                        <div class="custom-input position-relative">
                                            <input type="text"
                                                class="form-control @error('first_name') is-invalid @enderror"
                                                name="first_name" id="first_name" placeholder="Họ của bạn"
                                                value="{{ old('first_name') }}" />
                                            <i class="fa-solid fa-user position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                            @error('first_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="last_name" class="form-label">Tên</label>
                                        <div class="custom-input position-relative">
                                            <input type="text"
                                                class="form-control @error('last_name') is-invalid @enderror"
                                                name="last_name" id="last_name" placeholder="Tên của bạn"
                                                value="{{ old('last_name') }}" />
                                            <i class="fa-solid fa-user position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                            @error('last_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="email" class="form-label">Địa chỉ Email</label>
                                        <div class="custom-input position-relative">
                                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                                name="email" id="email" placeholder="Email của bạn"
                                                value="{{ old('email') }}" />
                                            <i class="fa-solid fa-envelope position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                            @error('email')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-6 col-lg-12 col-sm-6">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="phone" class="form-label">Số điện thoại</label>
                                        <div class="custom-input position-relative">
                                            <input type="tel" class="form-control @error('phone') is-invalid @enderror"
                                                name="phone" id="phone" placeholder="Số điện thoại của bạn"
                                                maxlength="10" value="{{ old('phone') }}"
                                                oninput="if(this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                                            <i class="fa-solid fa-mobile-screen-button position-absolute"
                                                style="right: 10px; top: 50%; transform: translateY(-50%);"></i>
                                            @error('phone')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="mb-md-4 mb-3 custom-form">
                                        <label for="message" class="form-label">Lời nhắn</label>
                                        <div class="custom-textarea position-relative">
                                            <textarea class="form-control @error('message') is-invalid @enderror" name="message" id="message"
                                                placeholder="Điều bạn thắc mắc" rows="6">{{ old('message') }}</textarea>
                                            <i class="fa-solid fa-message position-absolute"
                                                style="right: 10px; top: 10px;"></i>
                                            @error('message')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-animation btn-md fw-bold ms-auto">
                                Gửi
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </section>
    {{-- Map --}}
    <section class="map-section">
        <div class="container-fluid p-0">
            <div class="map-box">
                <iframe
                    src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d3723.863556662289!2d105.74468687445314!3d21.0381447623719!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1zVHLGsOG7nW5nIENhbyDEkeG6s25nIEZQVCBQb2x5dGVjaG5pYw!5e0!3m2!1svi!2s!4v1750352713751!5m2!1svi!2s"
                    width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>
        </div>
    </section>

    <style>
        .custom-input i,
        .custom-textarea i {
            pointer-events: none;
        }
    </style>

@endsection
