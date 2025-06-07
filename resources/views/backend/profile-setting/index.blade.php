@extends('layouts.backend')

@section('title', 'Trang cá nhân')

@section('content')
    <!-- Settings Section Start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <!-- Details Start -->
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Chỉnh sửa hồ sơ</h5>
                                    </div>
                                    <form class="theme-form theme-form-2 mega-form" method="POST"
                                        action="{{ route('admin.setting.update') }}" enctype="multipart/form-data">
                                        @csrf
                                           @method('PUT')
                                        <div class="row">
                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Họ tên</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" name="name"
                                                        value="{{ old('name', $user->name) }}" required>
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Số điện thoại</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="text" name="phone"
                                                        value="{{ old('phone', $user->phone) }}">
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Email</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="email" name="email"
                                                        value="{{ old('email', $user->email) }}" required>
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="col-sm-2 col-form-label form-label-title">Ảnh đại diện</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control form-choose" type="file" name="avatar">
                                                    @if ($user->avatar)
                                                        <img src="{{ asset('storage/' . $user->avatar) }}" width="80"
                                                            class="mt-2 rounded-circle" alt="avatar" />
                                                    @endif
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Mật khẩu mới</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="password" name="password"
                                                        placeholder="Để trống nếu không đổi">
                                                </div>
                                            </div>

                                            <div class="mb-4 row align-items-center">
                                                <label class="form-label-title col-sm-2 mb-0">Xác nhận lại</label>
                                                <div class="col-sm-10">
                                                    <input class="form-control" type="password"
                                                        name="password_confirmation">
                                                </div>
                                            </div>

                                            <div class="row">
                                                <div class="col-sm-10 offset-sm-2">
                                                    <button type="submit" class="btn btn-primary">Cập nhật thông
                                                        tin</button>
                                                </div>
                                            </div>
                                        </div>
                                    </form>


                                </div>
                            </div>
                            <!-- Details End -->

                            {{-- <!-- Address Start -->
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-header-2 mb-3">
                                                <h5>Address</h5>
                                            </div>

                                            <div class="save-details-box">
                                                <div class="row g-4">
                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="save-details">
                                                            <div class="save-name">
                                                                <h5>Mark Jugal</h5>
                                                            </div>

                                                            <div class="save-position">
                                                                <h6>Home</h6>
                                                            </div>

                                                            <div class="save-address">
                                                                <p>549 Sulphur Springs Road</p>
                                                                <p>Downers Grove, IL</p>
                                                                <p>60515</p>
                                                            </div>

                                                            <div class="mobile">
                                                                <p class="mobile">Mobile No. +1-123-456-7890</p>
                                                            </div>

                                                            <div class="button">
                                                                <a href="javascript:void(0)" class="btn btn-sm">Edit</a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-sm">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="save-details">
                                                            <div class="save-name">
                                                                <h5>Method Zaki</h5>
                                                            </div>

                                                            <div class="save-position">
                                                                <h6>Office</h6>
                                                            </div>

                                                            <div class="save-address">
                                                                <p>549 Sulphur Springs Road</p>
                                                                <p>Downers Grove, IL</p>
                                                                <p>60515</p>
                                                            </div>

                                                            <div class="mobile">
                                                                <p class="mobile">Mobile No. +1-123-456-7890</p>
                                                            </div>

                                                            <div class="button">
                                                                <a href="javascript:void(0)" class="btn btn-sm">Edit</a>
                                                                <a href="javascript:void(0)" class="btn btn-sm">
                                                                    Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="col-xl-4 col-md-6">
                                                        <div class="save-details">
                                                            <div class="save-name">
                                                                <h5>Mark Jugal</h5>
                                                            </div>

                                                            <div class="save-position">
                                                                <h6>Home</h6>
                                                            </div>

                                                            <div class="save-address">
                                                                <p>549 Sulphur Springs Road</p>
                                                                <p>Downers Grove, IL</p>
                                                                <p>60515</p>
                                                            </div>

                                                            <div class="mobile">
                                                                <p class="mobile">Mobile No. +1-123-456-7890</p>
                                                            </div>

                                                            <div class="button">
                                                                <a href="javascript:void(0)" class="btn btn-sm">Edit</a>
                                                                <a href="javascript:void(0)"
                                                                    class="btn btn-sm">Remove</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Address End --> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Settings Section End -->
    </div>
    <!-- Page Body End-->
    @includeIf('backend.footer')
    </div>
@endsection
{{ url('/admin//create') }}
