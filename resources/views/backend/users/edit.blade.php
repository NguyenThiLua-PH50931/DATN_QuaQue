@extends('layouts.backend')

@section('title', 'Cập nhật tài khoản')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-10 m-auto">
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Cập nhật tài khoản</h5>
                                    </div>

                                    <ul class="nav nav-pills mb-3" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link active" data-bs-toggle="pill" data-bs-target="#account-info" type="button">Thông tin tài khoản</button>
                                        </li>
                                    </ul>

                                    <div class="tab-content">
                                        <div class="tab-pane fade show active" id="account-info" role="tabpanel">
                                            <form class="theme-form theme-form-2 mega-form" method="POST"
                                                action="{{ route('admin.user.update', $user->id) }}"
                                                enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')

                                                {{-- Họ và tên --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Họ và tên</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="text" name="name" value="{{ old('name', $user->name) }}">
                                                        @error('name')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Email --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Email</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="email" name="email" value="{{ old('email', $user->email) }}">
                                                        @error('email')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Số điện thoại --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Số điện thoại</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="text" name="phone" value="{{ old('phone', $user->phone) }}">
                                                        @error('phone')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Vai trò --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Vai trò</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <select class="form-control" name="role">
                                                            <option value="">-- Chọn vai trò --</option>
                                                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                                                            <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>Member</option>
                                                        </select>
                                                        @error('role')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Mật khẩu (để trống nếu không đổi) --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Mật khẩu mới</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="password" name="password">
                                                        <small class="form-text text-muted">Để trống nếu không đổi mật khẩu</small>
                                                        @error('password')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Xác nhận mật khẩu --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Xác nhận mật khẩu</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control" type="password" name="password_confirmation">
                                                        @error('password_confirmation')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Ảnh đại diện --}}
                                                <div class="mb-4 row align-items-center">
                                                    <label class="form-label-title col-lg-2 col-md-3 mb-0">Ảnh đại diện</label>
                                                    <div class="col-md-9 col-lg-10">
                                                        <input class="form-control mb-2" type="file" name="avatar">
                                                        @if ($user->avatar)
                                                            <img src="{{ asset('storage/' . $user->avatar) }}" width="100" class="img-thumbnail">
                                                        @endif
                                                        @error('avatar')
                                                            <p class="text-danger">{{ $message }}</p>
                                                        @enderror
                                                    </div>
                                                </div>

                                                {{-- Nút gửi --}}
                                                <div class="row align-items-center">
                                                    <div class="col-md-12 text-end mt-3">
                                                        <button type="submit" class="btn btn-primary">Cập nhật tài khoản</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div> <!-- /.tab-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
@endsection
