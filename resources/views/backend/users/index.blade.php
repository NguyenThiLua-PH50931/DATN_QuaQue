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
                            <div class="title-header option-title">
                                <h5>Tài khoản</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.user.create') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="plus"></i>Thêm mới
                                    </a>
                                </form>
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
                                            <th>Hành động</th>
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

                                                <td>
                                                    <div class="user-name">
                                                        <span>{{ $value->name }}</span>
                                                    </div>
                                                </td>

                                                <td>{{ $value->phone }}</td>

                                                <td>{{ $value->email }}</td>
                                                <td>{{ $value->role }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('admin.user.toggleStatus', $value->id) }}"
                                                                title="{{ $value->status ? 'Ẩn người dùng' : 'Hiện người dùng' }}">
                                                                @if ($value->status)
                                                                    <i class="ri-eye-line text-success"></i>
                                                                @else
                                                                    <i class="ri-eye-off-line text-danger"></i>
                                                                @endif
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="{{ route('admin.user.edit', $value->id) }}">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>

                                                        <li>
                                                            <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                data-bs-target="#exampleModalToggle">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>

                                                        <!-- Modal xác nhận xóa tài khoản -->
                                                        <div class="modal fade" id="exampleModalToggle" tabindex="-1"
                                                            aria-labelledby="exampleModalToggleLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="exampleModalToggleLabel">Xác nhận xóa tài
                                                                            khoản</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Đóng"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Bạn có chắc chắn muốn xóa tài khoản này không?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <form
                                                                            action="{{ route('admin.user.delete', $value->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary me-2"
                                                                                    data-bs-dismiss="modal">Hủy</button>
                                                                                <button type="submit"
                                                                                    class="btn btn-danger">Xóa</button>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

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
