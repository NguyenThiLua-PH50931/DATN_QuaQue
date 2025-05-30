@extends('layouts.backend')

@section('title', 'Báo cáo')

@section('content')
<!-- Reports Section Start -->
<div class="page-body">
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <!-- Salery Summy star-->
            <div class="col-xl-8 col-lg-12 col-md-6">
                <div class="card o-hidden">
                    <div class="card-header border-0 pb-1">
                        <div class="card-header-title">
                            <h4>Sales Summary</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="saler-summary"></div>
                    </div>
                </div>
            </div>
            <!-- Salery Summy end-->

            <!-- Employ Salary  start-->
            <div class="col-xl-4 col-lg-12 col-md-6">
                <div class="h-100">
                    <div class="card o-hidden">
                        <div class="card-header border-0 pb-1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="card-header-title">
                                    <h4>Employees Satisfied</h4>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="pie-chart">
                                <div id="employ-salary"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Employ Salary end-->

            <!-- Expenses star-->
            <div class="col-xl-4 col-lg-12 col-md-6">
                <div class="card o-hidden">
                    <div class="card-header border-0 pb-1">
                        <div class="card-header-title">
                            <h4>Expenses</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="expenses-cart"></div>
                    </div>
                </div>
            </div>
            <!-- Expenses end-->

            <!-- Sales / Purchase chart start -->
            <div class="col-xl-8 col-lg-12 col-md-6">
                <div class="card">
                    <div class="card-header border-0 pb-1">
                        <div class="card-header-title">
                            <h4>Sales / Purchase</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="sales-purchase-chart"></div>
                    </div>
                </div>
            </div>
            <!-- Sales / Purchase chart end -->

            <!-- Sales / Purchase Return star-->
            <div class="col-12">
                <div class="card o-hidden">
                    <div class="card-header border-0 pb-1">
                        <div class="card-header-title">
                            <h4>Sales / Purchase Return</h4>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div id="sales-purchase-return-cart"></div>
                    </div>
                </div>
            </div>
            <!-- Sales / Purchase Return end-->

            <!-- Booking history start-->
            <div class="col-12">
                <div class="card">
                    <div class="card-header border-0 pb-1">
                        <div class="card-header-title">
                            <h4>Transfer History</h4>
                        </div>
                    </div>

                    <div class="card-body">
                        <div>
                            <div class="table-responsive">
                                <table class="user-table list-table table">
                                    <thead>
                                        <tr>
                                            <th>Transfer Id</th>
                                            <th>Name</th>
                                            <th>Date</th>
                                            <th>Total</th>
                                            <th>Options</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>14783112</td>
                                            <td>Gray Brody</td>
                                            <td>20-05-2020</td>
                                            <td>$369</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <span class="ri-eye-line"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-pencil"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-trash"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>87541221</td>
                                            <td>Perez Alonzo</td>
                                            <td>07-12-2020</td>
                                            <td>$546</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <span class="ri-eye-line"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-pencil"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-trash"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>213514462</td>
                                            <td>woters maxine</td>
                                            <td>12-12-2020</td>
                                            <td>$369</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <span class="ri-eye-line"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-pencil"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-trash"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>235896144</td>
                                            <td>christian</td>
                                            <td>16-05-2020</td>
                                            <td>$4699</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <span class="ri-eye-line"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-pencil"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-trash"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>461178242</td>
                                            <td>Lane Skylar</td>
                                            <td>25-10-2020</td>
                                            <td>$1342</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="order-detail.html">
                                                            <span class="ri-eye-line"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-pencil"></span>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)">
                                                            <span class="lnr lnr-trash"></span>
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
            <!-- Booking history  end-->
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection
@session('scripts')
    <script src="assets/js/chart/apex-chart/apex-chart1.js"></script>
    <script src="assets/js/chart/apex-chart/moment.min.js"></script>
    <script src="assets/js/chart/apex-chart/apex-chart.js"></script>
    <script src="assets/js/chart/apex-chart/stock-prices.js"></script>
    <script src="assets/js/chart/apex-chart/chart-custom.js"></script>
@endsession
{{ url('/admin//create') }}