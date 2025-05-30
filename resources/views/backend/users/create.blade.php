@extends('layouts.backend')

@section('title', 'Thêm tài khoản')

@section('content')
    <div class="page-body">
        <!-- New User start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-10 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Thêm tài khoản mới</h5>
                                    </div>
                                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" id="pills-home-tab" data-bs-toggle="pill"
                                                data-bs-target="#pills-home" type="button">Tài khoản</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                            <form class="theme-form theme-form-2 mega-form" method="POST"
                                                action="{{ route('admin.user.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="row">
                                                    <div class="mb-4 row align-items-center">
                                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">
                                                            Họ và tên</label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="text" name="name"
                                                                value="{{ old('name') }}">
                                                            @error('name')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label
                                                            class="col-lg-2 col-md-3 col-form-label form-label-title">Email
                                                        </label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="email" name="email"
                                                                value="{{ old('email') }}">
                                                            @error('email')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-lg-2 col-md-3 col-form-label form-label-title">Số
                                                            điện thoại
                                                        </label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="text" name="phone"
                                                                value="{{ old('phone') }}">
                                                            @error('phone')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-lg-2 col-md-3 col-form-label form-label-title">Vai
                                                            trò</label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <select class="form-control" name="role">
                                                                <option value="">-- Chọn vai trò --</option>
                                                                <option value="admin"
                                                                    {{ old('role') == 'admin' ? 'selected' : '' }}>Admin
                                                                </option>
                                                                <option value="member"
                                                                    {{ old('role') == 'member' ? 'selected' : '' }}>Member
                                                                </option>
                                                            </select>
                                                            @error('role')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>


                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-lg-2 col-md-3 col-form-label form-label-title">Mật
                                                            khẩu</label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="password" name="password">
                                                            @error('password')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="mb-4 row align-items-center">
                                                        <label class="col-lg-2 col-md-3 col-form-label form-label-title">Ảnh
                                                            đại diện</label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="file" name="avatar">
                                                            @error('avatar')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center">
                                                        <label class="col-lg-2 col-md-4 col-form-label form-label-title">Xác
                                                            nhận lại mật khẩu
                                                        </label>
                                                        <div class="col-md-9 col-lg-10">
                                                            <input class="form-control" type="password"
                                                                name="password_confirmation">
                                                            @error('password_confirmation')
                                                                <p class="text-danger">{{ $message }}</p>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="row align-items-center">
                                                        <div class="mt-4 text-end">
                                                            <button type="submit" class="btn btn-primary">Thêm tài
                                                                khoản</button>
                                                        </div>
                                                    </div>

                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- New User End -->

        @includeIf('backend.footer')
    </div>
@endsection
