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
                                <h5>All Users</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ url('/admin/users/create') }}"
                                        class="align-items-center btn btn-theme d-flex">
<<<<<<< HEAD
                                        <i data-feather="plus"></i>Add New
=======
                                        <i data-feather="plus"></i>Thêm mới
>>>>>>> origin/main
                                    </a>
                                </form>
                            </div>

                            <div class="table-responsive table-product">
                                <table class="table all-package theme-table" id="table_id">
                                    <thead>
                                        <tr>
<<<<<<< HEAD
                                            <th style="color: black; background-color: #f8f9fa;">User</th>
                                            <th style="color: black; background-color: #f8f9fa;">Name</th>
                                            <th style="color: black; background-color: #f8f9fa;">Phone</th>
                                            <th style="color: black; background-color: #f8f9fa;">Email</th>
                                            <th style="color: black; background-color: #f8f9fa;">Option</th>
=======
                                            <th>Ảnh</th>
                                            <th>Họ tên</th>
                                            <th>Số điện thoại</th>
                                            <th>Email</th>
                                            <th>Vai trò</th>
                                            <th>Option</th>
>>>>>>> origin/main
                                        </tr>
                                    </thead>

                                    <tbody>
<<<<<<< HEAD
                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/1.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Everett C. Green</span>
                                                    <span>Essex Court</span>
                                                </div>
                                            </td>

                                            <td>+ 802 - 370 - 2430</td>

                                            <td>EverettCGreen@rhyta.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/2.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Caroline L. Harris</span>
                                                    <span>Davis Lane</span>
                                                </div>
                                            </td>

                                            <td>+ 720 - 276 - 9403</td>

                                            <td>CarolineLHarris@rhyta.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/3.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Lucy j. Morile</span>
                                                    <span>Clifton</span>
                                                </div>
                                            </td>

                                            <td>+ 351 - 756 - 6549</td>

                                            <td>LucyMorile456@gmail.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/4.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Jennifer A. Straight</span>
                                                    <span>Brunswick</span>
                                                </div>
                                            </td>

                                            <td>+ 912 - 265 - 1550</td>

                                            <td>JenniferAStraight@rhyta.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/5.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Louise J. Stiles</span>
                                                    <span>Indianapolis</span>
                                                </div>
                                            </td>

                                            <td>+ 304 - 921 - 8122</td>

                                            <td>KevinAMillett@jourrapide.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/1.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Scott T. Thomas</span>
                                                    <span>Kotzebue</span>
                                                </div>
                                            </td>

                                            <td>+ 907 - 442 - 8122</td>

                                            <td>scott.thomas@packiu.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/2.jpg" class="img-fluid" alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Everett C. Green</span>
                                                    <span>Essex Court</span>
                                                </div>
                                            </td>

                                            <td>+ 218 - 244 - 7026</td>

                                            <td>KevinAMillett@jourrapide.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/3.jpg" class="img-fluid"
                                                        alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Dillon J. Bradshaw</span>
                                                    <span>Redbud Drive</span>
                                                </div>
                                            </td>

                                            <td>+ 347 - 649 - 7283</td>

                                            <td>DillonJBradshaw@teleworm.us</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/4.jpg" class="img-fluid"
                                                        alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Lorna M. Bonner</span>
                                                    <span>Broadway Street</span>
                                                </div>
                                            </td>

                                            <td>+ 843 - 765 - 6166</td>

                                            <td>LornaMBonner@teleworm.us</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/5.jpg" class="img-fluid"
                                                        alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Everett C. Green</span>
                                                    <span>Essex Court</span>
                                                </div>
                                            </td>

                                            <td>+ 802 - 370 - 2430</td>

                                            <td>EverettCGreen@rhyta.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    <img src="assets/images/users/1.jpg" class="img-fluid"
                                                        alt="">
                                                </div>
                                            </td>

                                            <td>
                                                <div class="user-name">
                                                    <span>Lorraine D. McDowell</span>
                                                    <span>Woodland Terrace</span>
                                                </div>
                                            </td>

                                            <td>+ 916 - 942 - 7555</td>

                                            <td>LorraineDMcDowell@dayrep.com</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#exampleModalToggle">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
=======
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
                                                            <a href="javascript:void(0)">
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
>>>>>>> origin/main
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
<<<<<<< HEAD
@push('scripts')
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ người dùng",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ người dùng",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy người dùng nào.",
            }
        });
    });
</script>
@endpush
=======
>>>>>>> origin/main
