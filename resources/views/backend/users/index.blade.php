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

                            @if (session('success'))
                                <div class="alert alert-success mt-3">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger mt-3">
                                    {{ session('error') }}
                                </div>
                            @endif


                            <form method="GET" action="{{ route('admin.user.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <!-- Lọc theo vai trò -->
                                    <div class="col-md-2">
                                        <select name="role" class="form-control">
                                            <option value="">-- Vai trò --</option>
                                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin
                                            </option>
                                            <option value="member" {{ request('role') == 'member' ? 'selected' : '' }}>
                                                Member</option>
                                        </select>
                                    </div>
                                    {{-- Lọc theo trạng thái --}}
                                    <div class="col-md-2">
                                        <select name="status" class="form-control">
                                            <option value="">-- Trạng thái --</option>
                                            <option value="1" {{ request('status') === '1' ? 'selected' : '' }}>Hiện
                                            </option>
                                            <option value="0" {{ request('status') === '0' ? 'selected' : '' }}>Ẩn
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Lọc từ ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ request('date_from') }}">
                                    </div>
                                    <!-- Lọc đến ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>


                            <div class="table-responsive table-product">
                                <table class="table all-package theme-table">
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
                                            @if ($value->id != Auth::id())
                                                <tr>
                                                    <td>
                                                        <div class="table-image">
                                                            @if ($value->avatar)
                                                                <img src="{{ asset('storage/' . $value->avatar) }}"
                                                                    class="img-fluid" width="60"
                                                                    alt="{{ $value->name }}">
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

                                                            <!-- Modal xác nhận xóa tài khoản -->
                                                            <div class="modal fade" id="deleteModal{{ $value->id }}"
                                                                tabindex="-1"
                                                                aria-labelledby="deleteModalLabel{{ $value->id }}"
                                                                aria-hidden="true">
                                                            </div>

                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endif
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Phân trang -->

        <!-- All User Table Ends-->
        @includeIf('backend.footer')
    </div>
@endsection
