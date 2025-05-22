@extends('layouts.backend') {{-- Kế thừa layout chính --}}

@section('title', 'Quản lý sản phẩm') {{-- Tiêu đề trang --}}

@section('content')
<div class="page-body">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card card-table">
                                <div class="card-body">
                                    <div class="title-header option-title d-sm-flex d-block">
                                        <h5>Products List</h5>
                                        <div class="right-options">
                                            <ul>
                                                <li>
                                                    <a href="javascript:void(0)" style="color: black">import</a>
                                                </li>
                                                <li>
                                                    <a href="javascript:void(0)" style="color: black">Export</a>
                                                </li>
                                                <li>
                                                    <a class="btn btn-solid" href="{{ url('/admin/products/create') }}">Add Product</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="table-responsive">
                                            <table class="table all-package theme-table table-product" id="table_id">
                                                <thead>
                                                    <tr>
                                                        <th style="color: black; background-color: #f8f9fa;">Product Image</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Product Name</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Category</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Current Qty</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Price</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Status</th>
                                                        <th style="color: black; background-color: #f8f9fa;">Option</th>
                                                    </tr>
                                                </thead>

                                                <tbody>
                                                    <tr>
                                                        <td>
                                                            <div class="table-image">
                                                                <img src="assets/images/product/1.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Aata Buscuit</td>

                                                        <td>Buscuit</td>

                                                        <td>12</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-danger">
                                                            <span>Pending</span>
                                                        </td>

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
                                                                <img src="assets/images/product/2.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Cold Brew Coffee</td>

                                                        <td>Drinks</td>

                                                        <td>10</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                                <img src="assets/images/product/3.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Peanut Butter Cookies</td>

                                                        <td>Cookies</td>

                                                        <td>9</td>

                                                        <td class="td-price">$86.35</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>
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
                                                                <img src="assets/images/product/4.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Wheet Flakes</td>

                                                        <td>Flakes</td>

                                                        <td>8</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-danger">
                                                            <span>Pending</span>
                                                        </td>
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
                                                                <img src="assets/images/product/5.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Potato Chips</td>

                                                        <td>Chips</td>

                                                        <td>23</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                                <img src="assets/images/product/6.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Tuwer Dal</td>

                                                        <td>Dals</td>

                                                        <td>50</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                                <img src="assets/images/product/7.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Almond Milk</td>

                                                        <td>Milk</td>

                                                        <td>25</td>

                                                        <td class="td-price">$121.43</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                                <img src="assets/images/product/11.png"
                                                                    class="img-fluid" alt="">
                                                            </div>
                                                        </td>

                                                        <td>Wheat Bread</td>

                                                        <td>Breads</td>

                                                        <td>6</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-danger">
                                                            <span>Pending</span>
                                                        </td>

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
                                                                <img src="assets/images/product/8.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Dog Food</td>

                                                        <td>Pet Food</td>

                                                        <td>11</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>
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
                                                                <img src="assets/images/product/9.png" class="img-fluid"
                                                                    alt="">
                                                            </div>
                                                        </td>

                                                        <td>Fresh Meat</td>

                                                        <td>Meats</td>

                                                        <td>18</td>

                                                        <td class="td-price">$95.97</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                                <img src="assets/images/product/10.png"
                                                                    class="img-fluid" alt="">
                                                            </div>
                                                        </td>

                                                        <td>Classic Coffee</td>

                                                        <td>Coffee</td>

                                                        <td>25</td>

                                                        <td class="td-price">$86.35</td>

                                                        <td class="status-close">
                                                            <span>Approved</span>
                                                        </td>

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
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container-fluid Ends-->

@includeIf('backend.footer')
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
        $('#table_id').DataTable({
            language: {
                search: "Tìm kiếm:",
                lengthMenu: "Hiển thị _MENU_ sản phẩm",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ sản phẩm",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy sản phẩm nào.",
            }
        });
    });
</script>
@endpush
