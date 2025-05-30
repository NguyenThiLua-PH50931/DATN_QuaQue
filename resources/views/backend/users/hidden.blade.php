@extends('layouts.backend')

@section('title', 'Tài khoản')

@section('content')
    <div class="page-body">
        <!-- All User Table Start -->
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-flex justify-content-between align-items-center">
                                <h5>Tài khoản đang bị ẩn</h5>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.user.hidden') }}" class="btn btn-outline-warning">
                                        <i class="ri-eye-off-line"></i> Tài khoản đã ẩn
                                    </a>
                                    <a href="{{ url('/admin/users/create') }}" class="btn btn-theme d-flex align-items-center">
                                        <i data-feather="plus"></i> Thêm mới
                                    </a>
                                </div>
                            </div>

                            <div class="table-responsive table-product">
                                <table class="table all-package theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Họ tên</th>
                                            <th>Số điện thoại</th>
                                            <th>Email</th>
                                            <th>Vai trò</th>
                                            <th>Tùy chọn</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach ($users as $value)
                                            <tr>
                                                <td>
                                                    <div class="table-image">
                                                        @if ($value->avatar)
                                                            <img src="{{ asset('storage/' . $value->avatar) }}"
                                                                class="img-fluid" width="60" alt="{{ $value->name }}">
                                                        @else
                                                            <img src="{{ asset('assets/images/users/default.jpg') }}"
                                                                class="img-fluid" width="60" alt="Default Avatar">
                                                        @endif
                                                    </div>
                                                </td>

                                                <td>{{ $value->name }}</td>
                                                <td>{{ $value->phone }}</td>
                                                <td>{{ $value->email }}</td>
                                                <td>{{ ucfirst($value->role) }}</td>

                                                <td>
                                                    <ul class="d-flex gap-2 list-unstyled">
                                                        <li>
                                                            <a href="{{ route('admin.user.toggleStatus', $value->id) }}"
                                                                title="Hiện người dùng">
                                                                <i class="ri-eye-off-line text-danger"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" title="Chỉnh sửa">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle" title="Xóa">
                                                                <i class="ri-delete-bin-line text-danger"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- All User Table Ends-->
        @includeIf('backend.footer')
    </div>
@endsection
