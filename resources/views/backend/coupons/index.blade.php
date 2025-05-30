@extends('layouts.backend')

@section('title', 'Mã giảm giá')

@section('content')
            <!-- Coupon list table starts-->
            <div class="page-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Coupon List</h5>
                                        <div class="right-options">
                                            <ul>
                                                <li>
                                                    <a class="btn btn-solid" href="{{ url('/admin/coupons/create') }}">Add Coupon</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table all-package coupon-list-table table-hover theme-table"
                                                id="table_id">
                                                <thead>
                                                    <tr>
                                                        <th>
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated checkall"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </th>
                                                        <th style="color: black; background-color: #f8f9fa;">Title</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Code</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Discount</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Status</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Option</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>10% Off</td>
                                                        <td>5488165</td>
                                                        <td class="theme-color">10%</td>
                                                        <td class="menu-status">
                                                            <span class="danger">Restitute</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>25% Off</td>
                                                        <td>2143235</td>
                                                        <td class="theme-color">17%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>12% Off</td>
                                                        <td>3243468</td>
                                                        <td class="theme-color">20%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>45% Off</td>
                                                        <td>7846289</td>
                                                        <td class="theme-color">50%</td>
                                                        <td class="menu-status">
                                                            <span class="danger">Restitute</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>45% Off</td>
                                                        <td>3614376</td>
                                                        <td class="theme-color">60%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>80% Off</td>
                                                        <td>8328192</td>
                                                        <td class="theme-color">45%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>60% Off</td>
                                                        <td>7218376</td>
                                                        <td class="theme-color">30%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                            <span class="form-check user-checkbox m-0 p-0">
                                                                <input class="checkbox_animated check-it"
                                                                    type="checkbox" value="">
                                                            </span>
                                                        </td>
                                                        <td>40% Off</td>
                                                        <td>1872349</td>
                                                        <td class="theme-color">42%</td>
                                                        <td class="menu-status">
                                                            <span class="success">Success</span>
                                                        </td>
                                                        <td>
                                                            <ul>
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
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <!-- Pagination End -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->
@includeIf('backend.footer')
</div>
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ phiếu giảm giá",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ phiếu giảm giá",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy phiếu giảm giá nào.",
            }
        });
    });
</script>
@endpush
