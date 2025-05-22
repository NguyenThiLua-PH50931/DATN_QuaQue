@extends('layouts.backend')

@section('title', 'Thuế')

@section('content')
<!-- Texes Table Section Start -->
<div class="page-body">

    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <!-- Table Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Taxes</h5>
                        </div>
                        <div class="table-responsive">
                            <table class="table all-package theme-table coupon-list-table" id="table_id">
                                <thead>
                                    <tr>
                                        <th>
                                            <span class="form-check user-checkbox m-0 p-0">
                                                <input class="checkbox_animated checkall" type="checkbox"
                                                    value="">
                                            </span>
                                        </th>
                                        <th>Tax Detail</th>
                                        <th>Tax Schedule</th>
                                        <th>Tax Rate</th>
                                        <th>Total Tax Amount</th>
                                        <th>Options</th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <tr>
                                        <td>
                                            <span class="form-check user-checkbox m-0 p-0">
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        </td>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>10%</td>
                                        <td>15.24</td>
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
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>18%</td>
                                        <td>5.29</td>
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
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        </td>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>25%</td>
                                        <td>4.78</td>
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
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        </td>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>40%</td>
                                        <td>3.20</td>
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
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        </td>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>80%</td>
                                        <td>8.4</td>
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
                                                <input class="checkbox_animated check-it" type="checkbox"
                                                    value="">
                                            </span>
                                        </td>
                                        <td>USASTE-PS6N0</td>
                                        <td>USASTCITY-6 <span class="theme-color">*</span></td>
                                        <td>50%</td>
                                        <td>4.78</td>
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
                    <!-- Table End -->

                    <!-- Pagination Start -->
                    <!-- <div class="pagination-box">
                                    <nav class="ms-auto me-auto" aria-label="...">
                                        <ul class="pagination pagination-primary">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:void(0)">Previous</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="javascript:void(0)">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div> -->
                    <!-- Pagination End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection
