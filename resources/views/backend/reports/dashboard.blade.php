@extends('layouts.backend')

@section('title', 'Thống kê')

@section('content')

    {{-- resources/views/report/dashboard-content.blade.php --}}
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-sm-flex d-block">
                                <h5>Thống Kê</h5>
                            </div>
                            <div class="cr-tools">
                                <div id="pagedate">
                                    <div class="cr-date-range" title="Chọn ngày">
                                        <span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Thẻ card thống kê nhanh -->
                        <div class="row mb-4">
                            <div class="col-xl-12">
                                <form action="javascript:void(0)" method="get" id="dashboardFilter"
                                    class="d-flex align-items-center justify-content-end gap-3 flex-wrap p-3 bg-white rounded shadow-sm">
                                    <div class="form-group d-flex align-items-center mb-0 gap-2">
                                        <label for="from_date" class="mb-0 font-weight-bold" style="white-space:nowrap;">Từ
                                            ngày</label>
                                        <input type="date" class="form-control filter-date" name="from_date"
                                            id="from_date" value="{{ $fromDate ?? '' }}" max="{{ date('Y-m-d') }}">
                                    </div>
                                    <div class="form-group d-flex align-items-center mb-0 gap-2">
                                        <label for="to_date" class="mb-0 font-weight-bold" style="white-space:nowrap;">Đến
                                            ngày</label>
                                        <input type="date" class="form-control filter-date" name="to_date" id="to_date"
                                            value="{{ $toDate ?? '' }}" max="{{ date('Y-m-d') }}">
                                    </div>
                                    <button type="button" id="filterToday" class="btn btn-filter-today filter-btn">Hôm
                                        nay</button>
                                    <button type="submit" class="btn btn-primary filter-btn">Lọc</button>
                                    <a href="{{ route('admin.dashboard') }}"
                                        class="btn btn-light filter-btn border">Reset</a>
                                </form>

                            </div>
                        </div>


                        <div class="col-xl-12">
                            <div class="row">
                                <!-- Khách hàng -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="cr-card">
                                        <div class="cr-card-content label-card">
                                            <div class="title">
                                                <span class="icon icon-1"><i class="ri-shield-user-line"></i></span>
                                                <div class="growth-numbers">
                                                    <h4>Khách hàng</h4>
                                                    <h5 id="dashboard_totalCustomers">{{ number_format($totalCustomers) }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <div class="growth-block">
                                                <div class="growth-line"></div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                <!-- Đơn hàng -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="cr-card">
                                        <div class="cr-card-content label-card">
                                            <div class="title">
                                                <span class="icon icon-2"><i class="ri-shopping-bag-3-line"></i></span>
                                                <div class="growth-numbers">
                                                    <h4>Đơn hàng</h4>
                                                    <h5 id="dashboard_completedOrders">{{ number_format($completedOrders) }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <p class="card-groth" id="growth_order">
                                            <div class="growth-block">
                                                <div class="growth-line"></div>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Doanh thu -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="cr-card">
                                        <div class="cr-card-content label-card">
                                            <div class="title">
                                                <span class="icon icon-3"><i class="ri-money-dollar-circle-line"></i></span>
                                                <div class="growth-numbers">
                                                    <h4>Doanh thu</h4>
                                                    <h5 id="dashboard_totalRevenue">
                                                        {{ number_format($totalRevenue, 0, ',', '.') }}₫</h5>
                                                </div>
                                            </div>
                                            <p class="card-groth" id="growth_revenue">
                                            <div class="growth-block">
                                                <div class="growth-line"></div>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                <!-- Yêu cầu hỗ trợ -->
                                <div class="col-xl-3 col-md-6">
                                    <div class="cr-card">
                                        <div class="cr-card-content label-card">
                                            <div class="title">
                                                <span class="icon icon-4">
                                                    <i class="ri-customer-service-2-line"></i>
                                                </span>
                                                <div class="growth-numbers">
                                                    <h4>Yêu cầu hỗ trợ</h4>
                                                    <h5 id="dashboard_totalRequests">{{ number_format($totalRequests) }}
                                                    </h5>
                                                </div>
                                            </div>
                                            <p class="card-groth" id="growth_support">
                                            <div class="growth-block">
                                                <div class="growth-line"></div>
                                            </div>
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- BIỂU ĐỒ DOANH THU VÀ ĐƠN HÀNG -->
                        <div class="row">
                            <div class="col-xxl-8 col-xl-12">
                                <div class="cr-card revenue-overview" id="revenueOverviewCard" style="position: relative;">
                                    <div
                                        class="cr-card-header header-575 d-flex justify-content-between align-items-center">
                                        <h4 class="cr-card-title mb-0">Tổng Quan Doanh Thu</h4>
                                        <div class="d-flex align-items-center gap-2" style="position:relative;">
                                            <a href="javascript:void(0)" id="btnRevenueFullscreen" class="btn-square"
                                                title="Toàn màn hình">
                                                <i class="ri-fullscreen-line"></i>
                                            </a>
                                            <a href="javascript:void(0)" id="btnRevenueFilter" title="Lọc"
                                                class="btn-square">
                                                <i class="ri-filter-3-line"></i>
                                            </a>
                                            <div id="revenueFilterDropdown"
                                                style="display:none; position:absolute; right:0; top:38px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                @for ($i = now()->year; $i >= now()->year - 4; $i--)
                                                    <div class="form-check px-3 py-2">
                                                        <input class="form-check-input" type="radio"
                                                            name="filterRevenueYear" id="filterRevenue{{ $i }}"
                                                            value="{{ $i }}"
                                                            {{ $year == $i ? 'checked' : '' }}>
                                                        <label class="form-check-label"
                                                            for="filterRevenue{{ $i }}">Năm
                                                            {{ $i }}</label>
                                                    </div>
                                                @endfor
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cr-card-content">
                                        <div class="cr-chart-header d-flex gap-4">
                                            <div class="block">
                                                <h6>Đơn hàng</h6>
                                                <h5 id="revenueOrders">{{ $completedOrders }} <span class="up"><i
                                                            class="ri-arrow-up-line"></i></span></h5>
                                            </div>
                                            <div class="block">
                                                <h6>Doanh thu</h6>
                                                <h5 id="revenueTotal">{{ number_format($totalRevenue) }}đ <span
                                                        class="up"><i class="ri-arrow-up-line"></i></span></h5>
                                            </div>
                                        </div>
                                        <div class="cr-chart-content">
                                            <div id="newrevenueChart" class="mb-m-24"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- ĐƠN HÀNG --}}
                            <div class="col-xxl-4 col-xl-6 col-md-12">
                                <div class="cr-card" id="campaigns">
                                    <div class="cr-card-header d-flex justify-content-between align-items-center">
                                        <h4 class="cr-card-title">Đơn hàng</h4>
                                        <div class="header-tools d-flex align-items-center gap-2">
                                            <button id="btnCampaignsFullscreen" class="btn-square" title="Toàn màn hình">
                                                <i class="ri-fullscreen-line"></i>
                                            </button>
                                            <button id="btnCampaignsFilter" class="btn-square"
                                                title="Lọc theo thời gian">
                                                <i class="ri-filter-3-line"></i>
                                            </button>
                                            <div id="campaignsFilterDropdown"
                                                style="display:none; position:absolute; right:0; top:40px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCampaignsTime" id="filterCampaignsDay"
                                                        value="day">
                                                    <label class="form-check-label" for="filterCampaignsDay">Hôm
                                                        nay</label>
                                                </div>

                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCampaignsTime" id="filterCampaignsWeek"
                                                        value="week" checked>
                                                    <label class="form-check-label" for="filterCampaignsWeek">Tuần
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCampaignsTime" id="filterCampaignsMonth"
                                                        value="month">
                                                    <label class="form-check-label" for="filterCampaignsMonth">Tháng
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCampaignsTime" id="filterCampaignsYear"
                                                        value="year">
                                                    <label class="form-check-label" for="filterCampaignsYear">Năm
                                                        nay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="cr-card-content">
                                        <div class="cr-chart-content">
                                            <div id="newcampaignsChart"></div>
                                        </div>
                                        <div style="max-width:320px; margin: 0 auto 18px; text-align:center;">
                                            <div id="echartsDonut" style="width: 320px; height: 280px;"></div>
                                            <div style="font-size:15px;color:#bdbdbd;margin-top:10px;">Thống kê đơn hàng
                                            </div>
                                            <div style="font-size:13px; color:#666; margin-top:7px;">
                                                <span style="color:#00C49A;font-weight:bold;">■ Hoàn thành</span>
                                                <span style="color:red; margin-left:20px;font-weight:bold;">■ Hủy</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <script src="https://cdn.jsdelivr.net/npm/echarts@5"></script>
                        <script>
                            document.addEventListener('DOMContentLoaded', () => {
                                const completed = {{ $completed ?? 0 }};
                                const canceled = {{ $canceled ?? 0 }};
                                var myChart = echarts.init(document.getElementById('echartsDonut'));
                                myChart.setOption({
                                    tooltip: {
                                        trigger: 'item',
                                        formatter: '{b}: <b>{c}</b> ({d}%)'
                                    },
                                    series: [{
                                        name: 'Đơn hàng',
                                        type: 'pie',
                                        radius: ['65%', '90%'],
                                        avoidLabelOverlap: false,
                                        label: {
                                            show: true,
                                            position: 'center',
                                            formatter: [
                                                '{a|' + (completed + canceled) + '}',
                                                '{b|Tổng đơn}'
                                            ].join('\n'),
                                            rich: {
                                                a: {
                                                    fontSize: 28,
                                                    fontWeight: 'bold',
                                                    color: '#222'
                                                },
                                                b: {
                                                    fontSize: 13,
                                                    color: '#888',
                                                    padding: [3, 0, 0, 0]
                                                }
                                            }
                                        },
                                        data: [{
                                                value: completed,
                                                name: 'Hoàn thành',
                                                itemStyle: {
                                                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                                            offset: 0,
                                                            color: '#43e97b'
                                                        },
                                                        {
                                                            offset: 1,
                                                            color: '#38f9d7'
                                                        }
                                                    ])
                                                }
                                            },
                                            {
                                                value: canceled,
                                                name: 'Hủy',
                                                itemStyle: {
                                                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                                            offset: 0,
                                                            color: '#fa709a'
                                                        },
                                                        {
                                                            offset: 1,
                                                            color: '#fee140'
                                                        }
                                                    ])
                                                }
                                            }
                                        ]
                                    }]
                                });
                                window.addEventListener('resize', function() {
                                    myChart.resize();
                                });
                            });
                        </script>


                        <!-- Bán chạy nhất & Sản phẩm nổi bật -->
                        <div class="row">
                            <div class="col-xxl-6 col-xl-12">
                                <div class="cr-card" id="best_seller_tbl"
                                    style="background: #fff; border: 1px solid #eee;">
                                    <!-- Header -->
                                    <div class="cr-card-header d-flex justify-content-between align-items-center"
                                        style="border-bottom:1px solid #eee; position:relative;">
                                        <h4 class="cr-card-title mb-0 fw-bold">Sản phẩm bán chạy</h4>
                                        <div class="d-flex align-items-center gap-2" style="position:relative;">
                                            <a href="javascript:void(0)" id="btnFullscreen" class="btn-square">
                                                <i class="ri-fullscreen-line"></i>
                                            </a>

                                            <a href="javascript:void(0)" id="btnFilter" title="Filter"
                                                class="btn-square">
                                                <i class="ri-filter-3-line"></i>
                                            </a>

                                            <!-- Filter dropdown đặt ngay đây -->
                                            <div id="filterDropdown"
                                                style="display:none; position:absolute; right:0; top:38px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterTime"
                                                        id="filterTodayOption" value="today">
                                                    <label class="form-check-label" for="filterTodayOption">Hôm
                                                        nay</label>
                                                </div>

                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterTime"
                                                        id="filterWeek" value="week" checked>
                                                    <label class="form-check-label" for="filterWeek">Tuần
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterTime"
                                                        id="filterMonth" value="month">
                                                    <label class="form-check-label" for="filterMonth">Tháng
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterTime"
                                                        id="filterYear" value="year">
                                                    <label class="form-check-label" for="filterYear">Năm
                                                        nay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Filter row -->
                                    <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                        <select class="form-select"
                                            style="width: 80px; background: none;border: 1px solid #eee; color: #777;"
                                            id="pageSizeSelect">
                                            <option>5</option>
                                            <option>10</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                            id="searchInput"
                                            style="max-width: 250px; background: none;border: 1px solid #eee; color: #777;">
                                    </div>
                                    <!-- Table -->
                                    <div class="table-responsive px-3">
                                        <table class="table align-middle" style="background: none;" id="bestSellerTable">
                                            <thead>
                                                <tr style="background: #f6f6f6;">
                                                    <th class="fw-bold" style="min-width: 170px; font-size: 14px;">Sản
                                                        Phẩm
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                    <th class="fw-bold" style="min-width: 120px; font-size: 14px;">Giá
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                    <th class="fw-bold text-center"
                                                        style="min-width: 90px; font-size: 14px;">
                                                        <span>Tồn kho</span>
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                    <th class="fw-bold text-center"
                                                        style="min-width: 100px; font-size: 14px;">
                                                        <span>Đã bán</span>
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="bestSellerTbody">
                                                <!-- JS sẽ fill dữ liệu ở đây -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Footer -->
                                    <div class="d-flex justify-content-between align-items-center py-2 px-3"
                                        style="font-size: 14px; color: #bdbdbd;">
                                        <span id="showingInfo"></span>
                                        <nav>
                                            <ul class="pagination mb-0" id="pagination">
                                                {{-- JS sẽ fill phân trang ở đây --}}
                                            </ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>




                            <div class="col-xxl-6 col-xl-12">
                                <div class="cr-card" id="top_rated_tbl"
                                    style="background: #fff; border: 1px solid #eee;">
                                    <div class="cr-card-header d-flex justify-content-between align-items-center"
                                        style="border-bottom:1px solid #eee; position:relative;">
                                        <h4 class="cr-card-title mb-0 fw-bold">Sản Phẩm Được Đánh Giá Cao</h4>
                                        <div class="d-flex align-items-center gap-2" style="position:relative;">
                                            <a href="javascript:void(0)" id="btnTopRatedFullscreen" class="btn-square">
                                                <i class="ri-fullscreen-line"></i>
                                            </a>
                                            <a href="javascript:void(0)" id="btnTopRatedFilter" title="Filter"
                                                class="btn-square">
                                                <i class="ri-filter-3-line"></i>
                                            </a>
                                            <!-- Filter dropdown -->
                                            <div id="topRatedFilterDropdown"
                                                style="display:none; position:absolute; right:0; top:38px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterRatedTime"
                                                        id="filterRatedToday" value="today">
                                                    <label class="form-check-label" for="filterRatedToday">Hôm nay</label>
                                                </div>

                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterRatedTime"
                                                        id="filterRatedWeek" value="week" checked>
                                                    <label class="form-check-label" for="filterRatedWeek">Tuần này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterRatedTime"
                                                        id="filterRatedMonth" value="month">
                                                    <label class="form-check-label" for="filterRatedMonth">Tháng
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio" name="filterRatedTime"
                                                        id="filterRatedYear" value="year">
                                                    <label class="form-check-label" for="filterRatedYear">Năm nay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Filter row -->
                                    <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                        <select class="form-select"
                                            style="width: 80px; background: none;border: 1px solid #eee; color: #777;"
                                            id="topRatedPageSizeSelect">
                                            <option>5</option>
                                            <option>10</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                            id="topRatedSearchInput"
                                            style="max-width: 250px; background: none;border: 1px solid #eee; color: #777;">
                                    </div>
                                    <!-- Table -->
                                    <div class="table-responsive px-3">
                                        <table class="table align-middle" style="background: none;" id="topRatedTable">
                                            <thead>
                                                <tr style="background: #f6f6f6;">
                                                    <th class="fw-bold" style="min-width: 170px;">Sản phẩm
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                    <th class="fw-bold" style="min-width: 100px;">Đánh Giá TB
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                    <th class="fw-bold text-center" style="min-width: 90px;">Số Đánh Giá
                                                        <span
                                                            style="font-size: 12px; color:#bdbdbd; vertical-align:middle;">&#8597;</span>
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="topRatedTbody">
                                                <!-- JS sẽ fill dữ liệu ở đây -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Footer -->
                                    <div class="d-flex justify-content-between align-items-center py-2 px-3"
                                        style="font-size: 14px; color: #bdbdbd;">
                                        <span id="topRatedShowingInfo"></span>
                                        <nav>
                                            <ul class="pagination mb-0" id="topRatedPagination"></ul>
                                        </nav>
                                    </div>
                                </div>

                                <!-- Modal fullscreen (copy modal giống block bán chạy và sửa id) -->
                                <div id="topRatedModal"
                                    style="display:none;position:fixed;z-index:99999;top:0;left:0;right:0;bottom:0;background:#0009;">
                                    <div
                                        style="background:#fff;max-width:900px;margin:50px auto 0 auto;padding:30px 30px 10px 30px; border-radius:8px;position:relative;">
                                        <button id="closeTopRatedModal"
                                            style="position:absolute;top:12px;right:20px;font-size:18px;border:none;background:transparent;cursor:pointer;">&times;</button>
                                        <div id="modalTopRatedContent"></div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        {{-- TOP --}}
                        <div class="row g-4" style="margin-bottom:30px;    border-radius: 0 !important;">
                            @php
                                $boxes = [
                                    [
                                        'title' => 'TOP Khách hàng',
                                        'id' => 'topCustomer',
                                        'value' => $topCustomer->name ?? 'Không có',
                                        'sub' => number_format($topCustomer->total_amount ?? 0) . '₫',
                                        'color' => 'primary',
                                        'filter' => [
                                            'today' => 'Hôm nay',
                                            'week' => 'Tuần này',
                                            'month' => 'Tháng này',
                                            'year' => 'Năm nay',
                                        ],
                                    ],
                                    [
                                        'title' => 'Đơn cần xử lý',
                                        'id' => 'processingOrders',
                                        'value' => $processingOrders ?? 0,
                                        'sub' => '<i class="ri-timer-line"></i>',
                                        'color' => 'danger',
                                        'filter' => [
                                            'today' => 'Hôm nay',
                                            'week' => 'Tuần này',
                                            'month' => 'Tháng này',
                                            'year' => 'Năm nay',
                                        ],
                                    ],
                                    [
                                        'title' => 'TOP Danh mục',
                                        'id' => 'topCategory',
                                        'value' => $topCategory->name ?? 'Không có',
                                        'sub' => number_format($topCategory->total_sold ?? 0) . ' sản phẩm',
                                        'color' => 'success',
                                        'filter' => [
                                            'today' => 'Hôm nay',
                                            'week' => 'Tuần này',
                                            'month' => 'Tháng này',
                                            'year' => 'Năm nay',
                                        ],
                                    ],
                                    [
                                        'title' => 'TOP tìm kiếm',
                                        'id' => 'topSearchedProduct',
                                        'value' => $topSearchedProduct->name ?? 'Không có',
                                        'sub' => number_format($topSearchedProduct->search_count ?? 0) . ' lượt',
                                        'color' => 'warning',
                                        'filter' => [
                                            'today' => 'Hôm nay',
                                            'week' => 'Tuần này',
                                            'month' => 'Tháng này',
                                            'year' => 'Năm nay',
                                        ],
                                    ],
                                ];
                            @endphp

                            @foreach ($boxes as $box)
                                <div class="col-sm-6 col-xxl-3">
                                    <div class="card custom-tile border-0 shadow-sm">
                                        <div
                                            class="card-body d-flex flex-column justify-content-between bg-{{ $box['color'] }}-soft rounded-4 p-4 position-relative">
                                            <div class="d-flex justify-content-between align-items-start">
                                                <div>
                                                    <p class="text-muted fw-semibold mb-1">{{ $box['title'] }}</p>
                                                    <h5 class="fw-bold mb-0" id="{{ $box['id'] }}Name">
                                                        {!! $box['value'] !!}</h5>
                                                    <span class="badge bg-light text-dark mt-1 d-inline-block"
                                                        id="{{ $box['id'] }}Sub">{!! $box['sub'] !!}</span>
                                                </div>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-light rounded-circle"
                                                        data-dropdown-id="{{ $box['id'] }}">
                                                        <i class="ri-filter-3-line"></i>
                                                    </button>
                                                    <div class="dropdown-menu shadow-sm p-2 rounded-3"
                                                        id="dropdown-{{ $box['id'] }}" style="display: none;">
                                                        @foreach ($box['filter'] as $key => $label)
                                                            <div class="form-check">
                                                                <input class="form-check-input" type="radio"
                                                                    name="{{ $box['id'] }}Filter"
                                                                    value="{{ $key }}"
                                                                    id="{{ $box['id'] . ucfirst($key) }}"
                                                                    {{ $loop->first ? 'checked' : '' }}>
                                                                <label class="form-check-label"
                                                                    for="{{ $box['id'] . ucfirst($key) }}">{{ $label }}</label>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>


                        <!-- Đơn hàng gần đây & Quốc gia top -->
                        <div class="row">
                            <div class="col-xxl-8 col-xl-12">
                                <div class="cr-card" id="cancelled_product_tbl"
                                    style="background: #fff; border: 1px solid #eee;">
                                    <div class="cr-card-header d-flex justify-content-between align-items-center"
                                        style="border-bottom:1px solid #eee;">
                                        <h4 class="cr-card-title mb-0 fw-bold">Sản phẩm bị hủy nhiều nhất</h4>
                                        <div class="d-flex align-items-center gap-2" style="position:relative;">
                                            <a href="javascript:void(0)" id="btnCancelledFullscreen" class="btn-square">
                                                <i class="ri-fullscreen-line"></i>
                                            </a>
                                            <a href="javascript:void(0)" id="btnCancelledFilter" title="Filter"
                                                class="btn-square">
                                                <i class="ri-filter-3-line"></i>
                                            </a>
                                            <!-- Filter dropdown -->
                                            <div id="cancelledFilterDropdown"
                                                style="display:none; position:absolute; right:0; top:38px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCancelledTime" id="filterCancelledToday"
                                                        value="today" checked>
                                                    <label class="form-check-label" for="filterCancelledToday">Hôm
                                                        nay</label>
                                                </div>


                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCancelledTime" id="filterCancelledWeek"
                                                        value="week">
                                                    <label class="form-check-label" for="filterCancelledWeek">Tuần
                                                        này</label>
                                                </div>

                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCancelledTime" id="filterCancelledMonth"
                                                        value="month">
                                                    <label class="form-check-label" for="filterCancelledMonth">Tháng
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterCancelledTime" id="filterCancelledYear"
                                                        value="year">
                                                    <label class="form-check-label" for="filterCancelledYear">Năm
                                                        nay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Filter row -->
                                    <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                        <select class="form-select"
                                            style="width: 80px; background: none;border: 1px solid #eee; color: #777;"
                                            id="cancelledPageSizeSelect">
                                            <option>5</option>
                                            <option>10</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                            id="cancelledSearchInput"
                                            style="max-width: 250px; background: none;border: 1px solid #eee; color: #777;">
                                    </div>
                                    <!-- Table -->
                                    <div class="table-responsive px-3">
                                        <table class="table align-middle" style="background: none;"
                                            id="cancelledProductTable">
                                            <thead>
                                                <tr style="background: #f6f6f6;">
                                                    <th class="fw-bold" style="min-width: 170px;">Sản phẩm</th>
                                                    <th class="fw-bold text-center" style="min-width: 100px;">SL hủy
                                                    </th>
                                                    <th class="fw-bold text-center" style="min-width: 100px;">Số đơn
                                                        hủy</th>
                                                    <th class="fw-bold text-center" style="min-width: 90px;">% hủy
                                                    </th>
                                                    <th class="fw-bold text-center" style="min-width:80px;">Chi tiết
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody id="cancelledProductTbody">
                                                <!-- JS sẽ fill dữ liệu ở đây -->
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Footer -->
                                    <div class="d-flex justify-content-between align-items-center py-2 px-3"
                                        style="font-size: 14px; color: #bdbdbd;">
                                        <span id="cancelledShowingInfo"></span>
                                        <nav>
                                            <ul class="pagination mb-0" id="cancelledPagination"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>

                            <!-- Card 2: Tỉnh Tiêu Biểu -->
                            <div class="col-xxl-4 col-xl-6 col-md-12">
                                <div class="cr-card" id="region_sales_card"
                                    style="background: #fff; border: 1px solid #eee;">
                                    <div class="cr-card-header d-flex justify-content-between align-items-center"
                                        style="border-bottom:1px solid #eee;">
                                        <h4 class="cr-card-title mb-0 fw-bold">Vùng miền tiêu biểu</h4>
                                        <div class="d-flex align-items-center gap-2" style="position:relative;">
                                            <a href="javascript:void(0)" id="btnRegionFullscreen" class="btn-square"
                                                title="Toàn màn hình">
                                                <i class="ri-fullscreen-line"></i>
                                            </a>
                                            <a href="javascript:void(0)" id="btnRegionFilter" title="Filter"
                                                class="btn-square">
                                                <i class="ri-filter-3-line"></i>
                                            </a>
                                            <!-- Filter dropdown -->
                                            <div id="regionFilterDropdown"
                                                style="display:none; position:absolute; right:0; top:38px; background:#fff; box-shadow:0 4px 16px #0002; border-radius:6px; min-width:140px; z-index:9999;">
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterRegionTime" id="filterRegionToday" value="today">
                                                    <label class="form-check-label" for="filterRegionToday">Hôm
                                                        nay</label>
                                                </div>

                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterRegionTime" id="filterRegionWeek" value="week"
                                                        checked>
                                                    <label class="form-check-label" for="filterRegionWeek">Tuần
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterRegionTime" id="filterRegionMonth" value="month">
                                                    <label class="form-check-label" for="filterRegionMonth">Tháng
                                                        này</label>
                                                </div>
                                                <div class="form-check px-3 py-2">
                                                    <input class="form-check-input" type="radio"
                                                        name="filterRegionTime" id="filterRegionYear" value="year">
                                                    <label class="form-check-label" for="filterRegionYear">Năm
                                                        nay</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- Filter row -->
                                    <div class="d-flex justify-content-between align-items-center py-3 px-3">
                                        <select class="form-select"
                                            style="width: 80px; background: none;border: 1px solid #eee; color: #777;"
                                            id="regionPageSizeSelect">
                                            <option>3</option>
                                            <option>6</option>
                                        </select>
                                        <input type="text" class="form-control" placeholder="Tìm kiếm..."
                                            id="regionSearchInput"
                                            style="max-width: 250px; background: none;border: 1px solid #eee; color: #777;">
                                    </div>
                                    <!-- ...Phần filter... -->
                                    <div style="margin: 0 auto 12px; width:100%; max-width:260px; min-height:72px;">
                                        <canvas id="miniRegionChart" height="72"></canvas>

                                        <div style="font-size:13px;color:#bdbdbd;margin-top:3px;">
                                            Thống kê dựa trên vùng miền địa lý
                                        </div>
                                        <div style="font-size:12px; color:#666; margin-top:5px;">
                                            <span style="color:#2A85FF;">■ Miền Bắc</span>
                                            <span style="color:#00C49A; margin-left:12px;">■ Miền Trung</span>
                                            <span style="color:#FFAC3E; margin-left:12px;">■ Miền Nam</span>
                                        </div>
                                    </div>
                                    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
                                    <script>
                                        const data = [2142000, 1953000, 200000];
                                        const colors = ['#2A85FF', '#00C49A', '#FFAC3E'];
                                        const ctx = document.getElementById('miniRegionChart').getContext('2d');
                                        new Chart(ctx, {
                                            type: 'doughnut',
                                            data: {
                                                labels: ['Miền Bắc', 'Miền Trung', 'Miền Nam'],
                                                datasets: [{
                                                    data: data,
                                                    backgroundColor: colors,
                                                    borderWidth: 0
                                                }]
                                            },
                                            options: {
                                                cutout: '70%', // tăng cutout để "mỏng" hơn
                                                plugins: {
                                                    legend: {
                                                        display: false
                                                    },
                                                    tooltip: {
                                                        callbacks: {
                                                            label: function(context) {
                                                                return $ {
                                                                    context.label
                                                                }: $ {
                                                                    context.parsed.toLocaleString()
                                                                }
                                                                đ;
                                                            }
                                                        }
                                                    }
                                                }
                                            }
                                        });
                                    </script>

                                    <div class="cr-map-detail px-3 pb-2" id="regionListWrap">
                                        <!-- JS sẽ fill list vùng miền ở đây -->
                                    </div>
                                    <!-- Footer -->
                                    <div class="d-flex justify-content-between align-items-center py-2 px-3"
                                        style="font-size: 14px; color: #bdbdbd;">
                                        <span id="regionShowingInfo"></span>
                                        <nav>
                                            <ul class="pagination mb-0" id="regionPagination"></ul>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.PRODUCTS = @json($bestSellingProducts);
    </script>
    <script>
        window.TOP_RATED = @json($topRatedProducts);
    </script>

    <script>
        window.CANCELLED_PRODUCTS = @json($cancelledProducts);
    </script>

    {{-- SẢN PHẨM BÁN CHẠY --}}
    <script>
        let products = window.PRODUCTS || [];
        let filterType = "today"; // Mặc định tuần này
        let filteredData = [];
        let currentPage = 1;
        let pageSize = 5;

        // Helper: cắt tên sản phẩm
        function cutWords(str, count) {
            if (!str) return '';
            let arr = str.trim().split(/\s+/);
            return arr.slice(0, count).join(' ') + (arr.length > count ? '...' : '');
        }

        // Helper: lấy số tuần ISO
        function getWeekNumber(dt) {
            const d = new Date(Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate()));
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }

        // Lọc dữ liệu theo thời gian
        function filterByTime(data, type) {
            const now = new Date();
            return data.filter(p => {
                if (!p.created_at) return false;
                const d = new Date(p.created_at);
                if (type === 'today') {
                    return now.toDateString() === d.toDateString();
                }

                if (type === 'week') {
                    return now.getFullYear() === d.getFullYear() && getWeekNumber(now) === getWeekNumber(d);
                }
                if (type === 'month') {
                    return now.getFullYear() === d.getFullYear() && now.getMonth() === d.getMonth();
                }
                if (type === 'year') {
                    return now.getFullYear() === d.getFullYear();
                }
                return true;
            });
        }

        // Render bảng
        function renderTable(page = 1, size = 5, data = filteredData) {
            currentPage = page;
            pageSize = size;
            const tbody = document.getElementById('bestSellerTbody');
            tbody.innerHTML = '';
            const start = (page - 1) * size;
            const end = start + size;
            const pageData = data.slice(start, end);

            for (let p of pageData) {
                tbody.innerHTML += `
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <img src="${p.image.startsWith('http') ? p.image : '/storage/' + p.image}" alt="Ảnh sản phẩm"
                         style="width: 44px; height: 44px; object-fit: cover;">
                    <div>
                        <div class="fw-semibold" style="font-size: 15px;">
                            ${cutWords(p.product_name, 2)}
                        </div>
                        <div class="text-muted" style="font-size: 13px;">
                            ${p.variant_name ?? ''}
                        </div>
                    </div>
                </div>
            </td>
            <td style="vertical-align: middle;">
                ${parseFloat(p.price).toLocaleString('vi-VN')} đ
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.stock}
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.sold_quantity}
            </td>
        </tr>
        `;
            }

            document.getElementById('showingInfo').innerText =
                `Hiển thị từ ${data.length ? (start + 1) : 0} đến ${Math.min(end, data.length)} trong ${data.length} mục`;

            renderPagination(data.length, page, size);
        }

        // Render phân trang
        function renderPagination(total, page, size) {
            const ul = document.getElementById('pagination');
            ul.innerHTML = '';
            const totalPages = Math.ceil(total / size);
            if (totalPages <= 1) {
                ul.style.display = 'none';
                return;
            }
            ul.style.display = '';

            ul.innerHTML += `<li class="page-item ${page == 1 ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page-1}">Previous</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                ul.innerHTML += `<li class="page-item ${i == page ? 'active' : ''}">
                <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            ul.innerHTML += `<li class="page-item ${page == totalPages ? 'disabled' : ''}">
            <a class="page-link" href="#" data-page="${page+1}">Next</a></li>`;

            ul.querySelectorAll('a.page-link').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    let p = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(p) && p > 0 && p <= totalPages && p !== currentPage) {
                        renderTable(p, pageSize, filteredData);
                    }
                };
            });
        }

        // Đổi số dòng/trang
        document.getElementById('pageSizeSelect').onchange = function() {
            pageSize = parseInt(this.value);
            renderTable(1, pageSize, filteredData);
        };

        // Search + filter thời gian
        document.getElementById('searchInput').oninput = function() {
            const kw = this.value.trim().toLowerCase();
            let tempData = filterByTime(products, filterType);
            filteredData = tempData.filter(p =>
                (p.product_name && p.product_name.toLowerCase().includes(kw)) ||
                (p.variant_name && p.variant_name.toLowerCase().includes(kw))
            );
            renderTable(1, pageSize, filteredData);
        };

        // Dropdown filter tuần/tháng/năm
        document.getElementById('btnFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('filterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
            // Set lại trạng thái checked
            document.getElementById('filterTodayOption').checked = (filterType === 'today');
            document.getElementById('filterWeek').checked = (filterType === 'week');
            document.getElementById('filterMonth').checked = (filterType === 'month');
            document.getElementById('filterYear').checked = (filterType === 'year');
        };
        // Đóng dropdown khi click ngoài
        document.body.onclick = function(e) {
            if (!e.target.closest('#filterDropdown') && !e.target.closest('#btnFilter')) {
                document.getElementById('filterDropdown').style.display = 'none';
            }
        };
        // Chọn filter radio
        document.querySelectorAll('input[name="filterTime"]').forEach(radio => {
            radio.onchange = function() {
                filterType = this.value;
                document.getElementById('searchInput').value = '';
                fetchBestSellers(filterType);
                document.getElementById('filterDropdown').style.display = 'none';
            };
        });

        // Hàm gọi backend lấy sản phẩm bán chạy theo filter
        function fetchBestSellers(type) {
            fetch(`/admin/reports/best-sellers?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    window.PRODUCTS = data;
                    filteredData = data;
                    renderTable(1, pageSize, filteredData);
                })
                .catch(err => {
                    alert("Lỗi tải dữ liệu sản phẩm bán chạy!");
                });
        }

        // ========= Popup Fullscreen =========
        document.getElementById('btnFullscreen').onclick = function() {
            document.getElementById('bestSellerModal').style.display = 'block';
            // Lấy đúng 10 sản phẩm đầu tiên của filter hiện tại
            let showData = filteredData.slice(0, 10);
            let html = `
            <table class="table align-middle" style="background: none;">
                <thead>
                    <tr style="background: #f6f6f6;">
                        <th class="fw-bold" style="min-width: 170px;">Sản Phẩm</th>
                        <th class="fw-bold" style="min-width: 120px;">Giá</th>
                        <th class="fw-bold text-center" style="min-width: 90px;">Tồn kho</th>
                        <th class="fw-bold text-center" style="min-width: 100px;">Đã bán</th>
                    </tr>
                </thead>
                <tbody>
        `;
            for (let p of showData) {
                html += `
                <tr>
                    <td>
                        <div class="d-flex align-items-center gap-3">
                            <img src="${p.image.startsWith('http') ? p.image : '/storage/' + p.image}" alt="Ảnh sản phẩm"
                                 style="width: 44px; height: 44px; object-fit: cover;">
                            <div>
                                <div class="fw-semibold" style="font-size: 15px;">
                                    ${cutWords(p.product_name, 2)}
                                </div>
                                <div class="text-muted" style="font-size: 13px;">
                                    ${p.variant_name ?? ''}
                                </div>
                            </div>
                        </div>
                    </td>
                    <td style="vertical-align: middle;">
                        ${parseFloat(p.price).toLocaleString('vi-VN')} đ
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        ${p.stock}
                    </td>
                    <td class="text-center" style="vertical-align: middle;">
                        ${p.sold_quantity}
                    </td>
                </tr>
            `;
            }
            html += `</tbody></table>
            <div style="font-size:14px; color:#bdbdbd; margin-top:10px;">
                Hiển thị ${showData.length} sản phẩm bán chạy nhất
            </div>
        `;
            document.getElementById('modalBestSellerContent').innerHTML = html;
        };

        document.getElementById('closeBestSellerModal').onclick = function() {
            document.getElementById('bestSellerModal').style.display = 'none';
        };
        document.getElementById('bestSellerModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        // =================== Khởi tạo lần đầu ===================
        fetchBestSellers('today');
        document.addEventListener('DOMContentLoaded', function() {
            // --- Các phần tử filter ---
            const filterForm = document.getElementById('dashboardFilter');
            const fromDate = document.getElementById('from_date');
            const toDate = document.getElementById('to_date');
            const todayBtn = document.getElementById('filterToday');

            // --- Đảm bảo không chọn ngày tương lai ---
            const todayStr = (new Date()).toISOString().slice(0, 10);
            if (fromDate) fromDate.max = todayStr;
            if (toDate) toDate.max = todayStr;

            // --- Khi chọn "Từ ngày", giới hạn min cho "Đến ngày" ---
            fromDate.addEventListener('change', function() {
                if (toDate) {
                    toDate.min = fromDate.value;
                    // Nếu đang chọn đến ngày < từ ngày thì tự chỉnh lại
                    if (toDate.value && toDate.value < fromDate.value) {
                        toDate.value = fromDate.value;
                    }
                }
            });

            // --- (Không bắt buộc) Khi chọn "Đến ngày", có thể set max cho "Từ ngày" ---
            toDate.addEventListener('change', function() {
                if (fromDate) {
                    // Nếu muốn: fromDate.max = toDate.value;
                    // Nếu để max là hôm nay, thì chỉ khi đến ngày nhỏ hơn hôm nay mới set
                    if (toDate.value < todayStr) {
                        fromDate.max = toDate.value;
                    } else {
                        fromDate.max = todayStr;
                    }
                    // Nếu "Từ ngày" > "Đến ngày", tự chỉnh lại cho hợp lý
                    if (fromDate.value && fromDate.value > toDate.value) {
                        fromDate.value = toDate.value;
                    }
                }
            });

            // --- Bắt sự kiện submit lọc ---
            filterForm.addEventListener('submit', function(e) {
                e.preventDefault();
                let from = fromDate.value;
                let to = toDate.value;
                const todayStr = (new Date()).toISOString().slice(0, 10);

                // Nếu chưa nhập từ ngày
                if (!from) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thiếu thông tin!',
                        text: 'Vui lòng chọn "Từ ngày" trước khi lọc.',
                        confirmButtonText: 'OK'
                    });
                    fromDate.focus();
                    return;
                }

                // Nếu chỉ nhập từ ngày, chưa nhập đến ngày, tự set đến ngày là hôm nay
                if (!to) {
                    toDate.value = todayStr;
                    to = todayStr;
                }

                fetchDashboardData(from, to);
            });

            // --- Bắt sự kiện click "Hôm nay" ---
            if (todayBtn) {
                todayBtn.addEventListener('click', function() {
                    fromDate.value = todayStr;
                    toDate.value = todayStr;
                    fromDate.max = todayStr;
                    toDate.max = todayStr;
                    toDate.min = todayStr;
                    fetchDashboardData(todayStr, todayStr);
                });
            }

            // --- Hàm gọi AJAX và cập nhật dashboard ---
            function fetchDashboardData(from, to) {
                fetch("{{ route('admin.reports.ajaxDashboard') }}?from_date=" + from + "&to_date=" + to)
                    .then(res => res.json())
                    .then(data => {
                        document.getElementById('dashboard_totalCustomers').innerText =
                            (parseInt(data.totalCustomers ?? 0)).toLocaleString('vi-VN');
                        document.getElementById('dashboard_completedOrders').innerText =
                            (parseInt(data.completedOrders ?? 0)).toLocaleString('vi-VN');
                        document.getElementById('dashboard_totalRevenue').innerText =
                            (parseFloat(data.totalRevenue ?? 0)).toLocaleString('vi-VN') + '₫';
                        document.getElementById('dashboard_totalRequests').innerText =
                            (parseInt(data.totalRequests ?? 0)).toLocaleString('vi-VN');
                    })
                    .catch(err => {
                        alert("Lỗi tải dữ liệu, vui lòng thử lại!");
                    });
            }

        });
    </script>
    {{-- SẢN PHẨM ĐÁNH GIÁ CAO NHẤT --}}
    <script>
        // ====================== SẢN PHẨM ĐÁNH GIÁ CAO NHẤT ======================
        let topRatedProducts = window.TOP_RATED || [];
        let ratedFilterType = "today"; // mặc định tuần này
        let filteredRatedData = [];
        let ratedCurrentPage = 1;
        let ratedPageSize = 5;

        // Helper cắt tên sản phẩm
        function cutWords(str, count) {
            if (!str) return '';
            let arr = str.trim().split(/\s+/);
            return arr.slice(0, count).join(' ') + (arr.length > count ? '...' : '');
        }

        // Helper lấy số tuần ISO
        function getWeekNumber(dt) {
            const d = new Date(Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate()));
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }

        // Lọc theo thời gian
        function filterRatedByTime(data, type) {
            const now = new Date();
            return data.filter(p => {
                if (!p.created_at) return false;
                const d = new Date(p.created_at);
                if (type === 'today') {
                    return now.toDateString() === d.toDateString();
                }

                if (type === 'week') {
                    return now.getFullYear() === d.getFullYear() && getWeekNumber(now) === getWeekNumber(d);
                }
                if (type === 'month') {
                    return now.getFullYear() === d.getFullYear() && now.getMonth() === d.getMonth();
                }
                if (type === 'year') {
                    return now.getFullYear() === d.getFullYear();
                }
                return true;
            });
        }

        // Render bảng
        function renderRatedTable(page = 1, size = 5, data = filteredRatedData) {
            ratedCurrentPage = page;
            ratedPageSize = size;
            const tbody = document.getElementById('topRatedTbody');
            tbody.innerHTML = '';
            const start = (page - 1) * size;
            const end = start + size;
            const pageData = data.slice(start, end);

            for (let p of pageData) {
                tbody.innerHTML += `
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <img src="${p.image?.startsWith('http') ? p.image : '/storage/' + p.image}" alt="Ảnh sản phẩm"
                         style="width: 44px; height: 44px; object-fit: cover;">
                    <div>
                        <div class="fw-semibold" style="font-size: 15px;">
                            ${cutWords(p.product_name, 2)}
                        </div>
                    </div>
                </div>
            </td>
            <td style="vertical-align: middle;">
                <span style="color: #FABB05; font-size:17px;">&#9733;</span>
                <span style="font-weight:600;">${parseFloat(p.average_rating).toFixed(1)}</span>
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.review_count}
            </td>
        </tr>
        `;
            }

            document.getElementById('topRatedShowingInfo').innerText =
                `Hiển thị từ ${data.length ? (start + 1) : 0} đến ${Math.min(end, data.length)} trong ${data.length} mục`;

            renderRatedPagination(data.length, page, size);
        }

        // Render phân trang
        function renderRatedPagination(total, page, size) {
            const ul = document.getElementById('topRatedPagination');
            ul.innerHTML = '';
            const totalPages = Math.ceil(total / size);
            if (totalPages <= 1) {
                ul.style.display = 'none';
                return;
            }
            ul.style.display = '';

            ul.innerHTML += `<li class="page-item ${page == 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${page-1}">Previous</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                ul.innerHTML += `<li class="page-item ${i == page ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            ul.innerHTML += `<li class="page-item ${page == totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${page+1}">Next</a></li>`;

            ul.querySelectorAll('a.page-link').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    let p = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(p) && p > 0 && p <= totalPages && p !== ratedCurrentPage) {
                        renderRatedTable(p, ratedPageSize, filteredRatedData);
                    }
                };
            });
        }

        // Đổi số dòng/trang
        document.getElementById('topRatedPageSizeSelect').onchange = function() {
            ratedPageSize = parseInt(this.value);
            renderRatedTable(1, ratedPageSize, filteredRatedData);
        };

        // Search + filter thời gian
        document.getElementById('topRatedSearchInput').oninput = function() {
            const kw = this.value.trim().toLowerCase();
            let tempData = filterRatedByTime(topRatedProducts, ratedFilterType);
            filteredRatedData = tempData.filter(p =>
                (p.product_name && p.product_name.toLowerCase().includes(kw))
            );
            renderRatedTable(1, ratedPageSize, filteredRatedData);
        };

        // Dropdown filter tuần/tháng/năm
        document.getElementById('btnTopRatedFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('topRatedFilterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
            // Set lại trạng thái checked
            document.getElementById('filterRatedToday').checked = (ratedFilterType === 'today');

            document.getElementById('filterRatedWeek').checked = (ratedFilterType === 'week');
            document.getElementById('filterRatedMonth').checked = (ratedFilterType === 'month');
            document.getElementById('filterRatedYear').checked = (ratedFilterType === 'year');
        };
        // Đóng dropdown khi click ngoài
        document.body.onclick = function(e) {
            if (!e.target.closest('#topRatedFilterDropdown') && !e.target.closest('#btnTopRatedFilter')) {
                document.getElementById('topRatedFilterDropdown').style.display = 'none';
            }
        };
        // Chọn filter radio
        // Chọn filter radio
        document.querySelectorAll('input[name="filterRatedTime"]').forEach(radio => {
            radio.onchange = function() {
                ratedFilterType = this.value;
                document.getElementById('topRatedSearchInput').value = '';
                fetchTopRatedProducts(ratedFilterType);
                document.getElementById('topRatedFilterDropdown').style.display = 'none';
            };
        });

        // Hàm gọi backend lấy sản phẩm đánh giá cao nhất theo filter
        function fetchTopRatedProducts(type) {
            fetch(`/admin/reports/top-rated-products?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    topRatedProducts = data;
                    filteredRatedData = data;
                    renderRatedTable(1, ratedPageSize, filteredRatedData);
                })
                .catch(err => {
                    alert("Lỗi tải dữ liệu sản phẩm đánh giá cao nhất!");
                });
        }


        // ========= Popup Fullscreen =========
        document.getElementById('btnTopRatedFullscreen').onclick = function() {
            // Show modal lớn, fill 10 sản phẩm đầu của filter hiện tại
            let showData = filteredRatedData.slice(0, 10);
            let html = `
    <table class="table align-middle" style="background: none;">
        <thead>
            <tr style="background: #f6f6f6;">
                <th class="fw-bold" style="min-width: 170px;">Sản phẩm</th>
                <th class="fw-bold" style="min-width: 100px;">Đánh giá TB</th>
                <th class="fw-bold text-center" style="min-width: 90px;">Số đánh giá</th>
            </tr>
        </thead>
        <tbody>
    `;
            for (let p of showData) {
                html += `
            <tr>
                <td>
                    <div class="d-flex align-items-center gap-3">
                        <img src="${p.image?.startsWith('http') ? p.image : '/storage/' + p.image}" alt="Ảnh sản phẩm"
                             style="width: 44px; height: 44px; object-fit: cover;">
                        <div>
                            <div class="fw-semibold" style="font-size: 15px;">
                                ${cutWords(p.product_name, 2)}
                            </div>
                        </div>
                    </div>
                </td>
                <td style="vertical-align: middle;">
                    <span style="color: #FABB05; font-size:17px;">&#9733;</span>
                    <span style="font-weight:600;">${parseFloat(p.average_rating).toFixed(1)}</span>
                </td>
                <td class="text-center" style="vertical-align: middle;">
                    ${p.review_count}
                </td>
            </tr>
        `;
            }
            html += `</tbody></table>
    <div style="font-size:14px; color:#bdbdbd; margin-top:10px;">
        Hiển thị ${showData.length} sản phẩm đánh giá cao nhất
    </div>
    `;
            // Show modal, giống logic bạn đang có cho bán chạy
            document.getElementById('modalTopRatedContent').innerHTML = html;
            document.getElementById('topRatedModal').style.display = 'block';
        };
        document.getElementById('closeTopRatedModal').onclick = function() {
            document.getElementById('topRatedModal').style.display = 'none';
        };
        document.getElementById('topRatedModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        // =================== Khởi tạo lần đầu ===================
        // filteredRatedData = filterRatedByTime(topRatedProducts, ratedFilterType);
        // renderRatedTable();
        fetchTopRatedProducts('today');
    </script>
    {{-- SẢN PHẨM BỊ HỦY NHIỀU NHẤT --}}
    <script>
        // ====================== SẢN PHẨM BỊ HỦY NHIỀU NHẤT ======================
        let cancelledProducts = window.CANCELLED_PRODUCTS || [];
        let cancelledFilterType = "today";
        let filteredCancelledData = [];
        let cancelledCurrentPage = 1;
        let cancelledPageSize = 5;

        // Helper cắt tên sản phẩm
        function cutWords(str, count) {
            if (!str) return '';
            let arr = str.trim().split(/\s+/);
            return arr.slice(0, count).join(' ') + (arr.length > count ? '...' : '');
        }

        // Helper lấy số tuần ISO
        function getWeekNumber(dt) {
            const d = new Date(Date.UTC(dt.getFullYear(), dt.getMonth(), dt.getDate()));
            d.setUTCDate(d.getUTCDate() + 4 - (d.getUTCDay() || 7));
            const yearStart = new Date(Date.UTC(d.getUTCFullYear(), 0, 1));
            return Math.ceil((((d - yearStart) / 86400000) + 1) / 7);
        }

        // Lọc theo thời gian
        function filterCancelledByTime(data, type) {
            const now = new Date();
            return data.filter(p => {
                if (!p.last_cancelled) return false;
                const d = new Date(p.last_cancelled);
                if (type === 'today') {
                    return now.toDateString() === d.toDateString();
                }
                if (type === 'week') {
                    return now.getFullYear() === d.getFullYear() && getWeekNumber(now) === getWeekNumber(d);
                }
                if (type === 'month') {
                    return now.getFullYear() === d.getFullYear() && now.getMonth() === d.getMonth();
                }
                if (type === 'year') {
                    return now.getFullYear() === d.getFullYear();
                }
                return true;
            });
        }


        // Render bảng
        function renderCancelledTable(page = 1, size = 5, data = filteredCancelledData) {
            cancelledCurrentPage = page;
            cancelledPageSize = size;
            const tbody = document.getElementById('cancelledProductTbody');
            tbody.innerHTML = '';
            const start = (page - 1) * size;
            const end = start + size;
            const pageData = data.slice(start, end);

            for (let [idx, p] of pageData.entries()) {
                tbody.innerHTML += `
    <tr>
        <td>
            <div class="d-flex align-items-center gap-3">
                <img src="${p.product_image?.startsWith('http') ? p.product_image : '/storage/' + p.product_image}" alt="Ảnh sản phẩm"
                     style="width: 44px; height: 44px; object-fit: cover;">
                <div>
                    <div class="fw-semibold" style="font-size: 15px;">
                        ${cutWords(p.product_name, 2)}
                    </div>
                </div>
            </div>
        </td>
        <td class="text-center" style="vertical-align: middle;">
            ${p.total_cancelled_qty}
        </td>
        <td class="text-center" style="vertical-align: middle;">
            ${p.total_cancelled_orders}
        </td>
        <td class="text-center" style="vertical-align: middle;">
            ${p.cancel_percent}%
        </td>
        <td class="text-center" style="vertical-align: middle;">
            <button class="btn btn-link" style="padding:0;" onclick="showCancelledVariants(${start + idx})">
                Chi tiết
            </button>
        </td>
    </tr>
    `;
            }


            document.getElementById('cancelledShowingInfo').innerText =
                `Hiển thị từ ${data.length ? (start + 1) : 0} đến ${Math.min(end, data.length)} trong ${data.length} mục`;

            renderCancelledPagination(data.length, page, size);
        }

        // Render phân trang
        function renderCancelledPagination(total, page, size) {
            const ul = document.getElementById('cancelledPagination');
            ul.innerHTML = '';
            const totalPages = Math.ceil(total / size);
            if (totalPages <= 1) {
                ul.style.display = 'none';
                return;
            }
            ul.style.display = '';

            ul.innerHTML += `<li class="page-item ${page == 1 ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${page-1}">Previous</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                ul.innerHTML += `<li class="page-item ${i == page ? 'active' : ''}">
            <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            ul.innerHTML += `<li class="page-item ${page == totalPages ? 'disabled' : ''}">
        <a class="page-link" href="#" data-page="${page+1}">Next</a></li>`;

            ul.querySelectorAll('a.page-link').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    let p = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(p) && p > 0 && p <= totalPages && p !== cancelledCurrentPage) {
                        renderCancelledTable(p, cancelledPageSize, filteredCancelledData);
                    }
                };
            });
        }

        // Đổi số dòng/trang
        document.getElementById('cancelledPageSizeSelect').onchange = function() {
            cancelledPageSize = parseInt(this.value);
            renderCancelledTable(1, cancelledPageSize, filteredCancelledData);
        };

        // Search + filter thời gian
        document.getElementById('cancelledSearchInput').oninput = function() {
            const kw = this.value.trim().toLowerCase();
            let tempData = filterCancelledByTime(cancelledProducts, cancelledFilterType);
            filteredCancelledData = tempData.filter(p =>
                (p.product_name && p.product_name.toLowerCase().includes(kw)) ||
                (p.variant_name && p.variant_name.toLowerCase().includes(kw))
            );
            renderCancelledTable(1, cancelledPageSize, filteredCancelledData);
        };

        // Dropdown filter tuần/tháng/năm
        document.getElementById('btnCancelledFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('cancelledFilterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
            document.getElementById('filterCancelledWeek').checked = (cancelledFilterType === 'week');
            document.getElementById('filterCancelledMonth').checked = (cancelledFilterType === 'month');
            document.getElementById('filterCancelledYear').checked = (cancelledFilterType === 'year');
        };
        // Đóng dropdown khi click ngoài
        document.body.onclick = function(e) {
            if (!e.target.closest('#cancelledFilterDropdown') && !e.target.closest('#btnCancelledFilter')) {
                document.getElementById('cancelledFilterDropdown').style.display = 'none';
            }
        };
        // Chọn filter radio
        // Chọn filter radio
        document.querySelectorAll('input[name="filterCancelledTime"]').forEach(radio => {
            radio.onchange = function() {
                cancelledFilterType = this.value;
                document.getElementById('cancelledSearchInput').value = '';
                fetchCancelledProducts(cancelledFilterType);
                document.getElementById('cancelledFilterDropdown').style.display = 'none';
            };
        });

        // Hàm gọi backend lấy sản phẩm bị huỷ nhiều nhất theo filter
        function fetchCancelledProducts(type) {
            fetch(`/admin/reports/cancelled-products?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    cancelledProducts = data;
                    filteredCancelledData = data;
                    renderCancelledTable(1, cancelledPageSize, filteredCancelledData);
                })
                .catch(err => {
                    alert("Lỗi tải dữ liệu sản phẩm bị hủy!");
                });
        }


        // ========= Popup Fullscreen =========
        document.getElementById('btnCancelledFullscreen').onclick = function() {
            let showData = filteredCancelledData.slice(0, 10);
            let html = `
    <table class="table align-middle" style="background: none;">
        <thead>
            <tr style="background: #f6f6f6;">
                <th class="fw-bold" style="min-width: 170px;">Sản phẩm</th>
                <th class="fw-bold text-center" style="min-width: 100px;">Số lượng bị hủy</th>
                <th class="fw-bold text-center" style="min-width: 100px;">Số đơn bị hủy</th>
                <th class="fw-bold text-center" style="min-width: 90px;">% hủy trên tổng</th>
                <th class="fw-bold" style="min-width: 170px;">Lý do hủy nhiều nhất</th>
            </tr>
        </thead>
        <tbody>
    `;
            for (let p of showData) {
                html += `
        <tr>
            <td>
                <div class="d-flex align-items-center gap-3">
                    <img src="${p.product_image?.startsWith('http') ? p.product_image : '/storage/' + p.product_image}" alt="Ảnh sản phẩm"
                         style="width: 44px; height: 44px; object-fit: cover;">
                    <div>
                        <div class="fw-semibold" style="font-size: 15px;">
                            ${cutWords(p.product_name, 2)}
                        </div>
                        <div class="text-muted" style="font-size: 13px;">
                            ${p.variant_name ?? ''}
                        </div>
                    </div>
                </div>
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.cancelled_qty}
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.cancelled_orders}
            </td>
            <td class="text-center" style="vertical-align: middle;">
                ${p.cancel_percent}%
            </td>
            <td style="vertical-align: middle;">
                <span style="color:#d9534f;font-weight:500;">
                    ${p.top_reason ? p.top_reason : ''}
                </span>
            </td>
        </tr>
        `;
            }
            html += `</tbody></table>
    <div style="font-size:14px; color:#bdbdbd; margin-top:10px;">
        Hiển thị ${showData.length} sản phẩm bị hủy nhiều nhất
    </div>
    `;
            document.getElementById('modalCancelledContent').innerHTML = html;
            document.getElementById('cancelledModal').style.display = 'block';
        };
        document.getElementById('closeCancelledModal').onclick = function() {
            document.getElementById('cancelledModal').style.display = 'none';
        };
        document.getElementById('cancelledModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        // =================== Khởi tạo lần đầu ===================
        // filteredCancelledData = filterCancelledByTime(cancelledProducts, cancelledFilterType);
        // renderCancelledTable();
        fetchCancelledProducts('today');

        function showCancelledVariants(idx) {
            const product = filteredCancelledData[idx];
            if (!product || !product.variants || !product.variants.length) return;

            let html = `
        <h5 class="mb-3 fw-bold">${product.product_name}</h5>
        <div style="overflow-x:auto;">
        <table class="table align-middle" style="background: none;">
            <thead>
                <tr style="background: #f6f6f6;">
                    <th class="fw-bold">Biến thể</th>
                    <th class="fw-bold text-center">SL hủy</th>
                    <th class="fw-bold text-center">Đơn hủy</th>
                    <th class="fw-bold text-center">% hủy</th>
                    <th class="fw-bold">Lý do hủy nhiều nhất</th>
                </tr>
            </thead>
            <tbody>
    `;

            for (let v of product.variants) {
                html += `
            <tr>
                <td>${v.variant_name || 'Mặc định'}</td>
                <td class="text-center">${v.cancelled_qty}</td>
                <td class="text-center">${v.cancelled_orders}</td>
                <td class="text-center">${v.cancel_percent}%</td>
                <td>${v.top_reason || ''}</td>
            </tr>
        `;
            }

            html += `</tbody></table></div>`;

            document.getElementById('modalCancelledContent').innerHTML = html;
            document.getElementById('cancelledModal').style.display = 'block';
        }
    </script>

    {{-- VÙNG BÁN CHẠY --}}
    <script>
        let miniRegionChart = null;

        function renderMiniRegionChart(regionData) {
            const ctx = document.getElementById('miniRegionChart').getContext('2d');
            const colors = ['#2A85FF', '#00C49A', '#FFAC3E', '#4C51BF', '#F87171', '#34D399'];
            const labels = regionData.map(r => r.region);
            const data = regionData.map(r => r.total_revenue);

            if (miniRegionChart) miniRegionChart.destroy();

            miniRegionChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: labels,
                    datasets: [{
                        data: data,
                        backgroundColor: colors,
                        borderWidth: 0
                    }]
                },
                options: {
                    cutout: '70%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    return `${context.label}: ${context.parsed.toLocaleString()}đ`;
                                }
                            }
                        }
                    }
                }
            });
        }

        let regionSales = @json($regionSales ?? []); // <-- Laravel trả về vùng miền
        let regionFilterType = "today";
        let filteredRegionData = [];
        let regionCurrentPage = 1;
        let regionPageSize = 3;

        // Render danh sách vùng miền
        function renderRegionList(page = 1, size = 3, data = filteredRegionData) {
            regionCurrentPage = page;
            regionPageSize = size;
            const listWrap = document.getElementById('regionListWrap');
            listWrap.innerHTML = '';
            const start = (page - 1) * size;
            const end = start + size;
            const pageData = data.slice(start, end);

            // Tính max để vẽ progress
            const maxRevenue = data.length ? Math.max(...data.map(p => p.total_revenue)) : 1;

            for (let p of pageData) {
                listWrap.innerHTML += `
        <div class="cr-detail-list pb-2 pt-2" style="border-bottom:1px solid #f6f6f6;">
            <div class="d-flex justify-content-between align-items-center">
                <div class="d-flex align-items-center gap-2">
                    <label class="fw-semibold mb-0" style="color:#666;">${p.region} <span style="color:#12b76a;font-size:13px;"><i class="ri-arrow-up-line"></i></span></label>
                </div>
                <p class="fw-bold mb-0" style="font-size:16px; color:#222;">
                    ${Number(p.total_revenue).toLocaleString('vi-VN')}đ
                </p>
            </div>
            <div class="progress mt-1" style="height:4px;">
                <div class="progress-bar bg-primary" role="progressbar"
                    style="width: ${Math.min(100, Math.round(p.total_revenue / maxRevenue * 100))}%"></div>
            </div>
        </div>
    `;
            }
            document.getElementById('regionShowingInfo').innerText =
                `Hiển thị từ ${data.length ? (start + 1) : 0} đến ${Math.min(end, data.length)}`;

            renderRegionPagination(data.length, page, size);
        }

        // Pagination
        function renderRegionPagination(total, page, size) {
            const ul = document.getElementById('regionPagination');
            ul.innerHTML = '';
            const totalPages = Math.ceil(total / size);
            if (totalPages <= 1) {
                ul.style.display = 'none';
                return;
            }
            ul.style.display = '';

            ul.innerHTML += `<li class="page-item ${page == 1 ? 'disabled' : ''}">
    <a class="page-link" href="#" data-page="${page-1}">Previous</a></li>`;
            for (let i = 1; i <= totalPages; i++) {
                ul.innerHTML += `<li class="page-item ${i == page ? 'active' : ''}">
        <a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
            }
            ul.innerHTML += `<li class="page-item ${page == totalPages ? 'disabled' : ''}">
    <a class="page-link" href="#" data-page="${page+1}">Next</a></li>`;

            ul.querySelectorAll('a.page-link').forEach(btn => {
                btn.onclick = function(e) {
                    e.preventDefault();
                    let p = parseInt(this.getAttribute('data-page'));
                    if (!isNaN(p) && p > 0 && p <= totalPages && p !== regionCurrentPage) {
                        renderRegionList(p, regionPageSize, filteredRegionData);
                    }
                };
            });
        }

        // Đổi số dòng/trang
        document.getElementById('regionPageSizeSelect').onchange = function() {
            regionPageSize = parseInt(this.value);
            renderRegionList(1, regionPageSize, filteredRegionData);
        };

        // Tìm kiếm
        document.getElementById('regionSearchInput').oninput = function() {
            const kw = this.value.trim().toLowerCase();
            filteredRegionData = regionSales.filter(p =>
                (p.region && p.region.toLowerCase().includes(kw))
            );
            renderRegionList(1, regionPageSize, filteredRegionData);
        };

        // Dropdown filter tuần/tháng/năm
        document.getElementById('btnRegionFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('regionFilterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
            document.getElementById('filterRegionToday').checked = (regionFilterType === 'today');
            document.getElementById('filterRegionWeek').checked = (regionFilterType === 'week');
            document.getElementById('filterRegionMonth').checked = (regionFilterType === 'month');
            document.getElementById('filterRegionYear').checked = (regionFilterType === 'year');
        };
        // Đóng dropdown khi click ngoài
        document.body.onclick = function(e) {
            if (!e.target.closest('#regionFilterDropdown') && !e.target.closest('#btnRegionFilter')) {
                document.getElementById('regionFilterDropdown').style.display = 'none';
            }
        };
        // Chọn filter radio
        document.querySelectorAll('input[name="filterRegionTime"]').forEach(radio => {
            radio.onchange = function() {
                regionFilterType = this.value;
                document.getElementById('regionSearchInput').value = '';
                fetchRegionSales(regionFilterType);
                document.getElementById('regionFilterDropdown').style.display = 'none';
            };
        });

        // Hàm lấy dữ liệu vùng miền theo filter
        function fetchRegionSales(type) {
            fetch(`/admin/reports/region-sales?type=${type}`)
                .then(res => res.json())
                .then(data => {
                    regionSales = data;
                    filteredRegionData = data;
                    renderMiniRegionChart(filteredRegionData); // Gọi cập nhật lại chart
                    renderRegionList(1, regionPageSize, filteredRegionData);
                })
                .catch(err => {
                    alert("Lỗi tải dữ liệu doanh thu vùng miền!");
                });
        }

        // ========= Popup Fullscreen cho Region =========
        document.getElementById('btnRegionFullscreen').onclick = function() {
            let showData = filteredRegionData.slice(0, 6);
            let maxRevenue = showData.length ? Math.max(...showData.map(p => p.total_revenue)) : 1;
            let html = '';
            for (let p of showData) {
                html += `
<div class="cr-detail-list" style="border-bottom:1px solid #f6f6f6;padding:2px 0;">
    <div class="d-flex justify-content-between align-items-center" style="margin-bottom: 0;">
        <div class="d-flex align-items-center gap-2" style="margin-bottom: 0;">
            <label class="fw-semibold mb-0" style="color:#666;margin-bottom:0;">${p.region} <span style="color:#12b76a;font-size:13px;"><i class="ri-arrow-up-line"></i></span></label>
        </div>
        <p class="fw-bold mb-0" style="font-size:16px; color:#222; margin-bottom:0;">${Number(p.total_revenue).toLocaleString('vi-VN')}đ</p>
    </div>
    <div class="progress" style="height:4px; margin-top:2px; margin-bottom:0;">
        <div class="progress-bar bg-primary" role="progressbar"
            style="width: ${Math.min(100, Math.round(p.total_revenue / maxRevenue * 100))}%"></div>
    </div>
</div>
`;
            }

            html += `<div style="font-size:14px; color:#bdbdbd; margin-top:10px;">
        Hiển thị ${showData.length} vùng miền doanh thu cao nhất
    </div>`;
            document.getElementById('modalRegionContent').innerHTML = html;
            document.getElementById('regionModal').style.display = 'block';
        };
        document.getElementById('closeRegionModal').onclick = function() {
            document.getElementById('regionModal').style.display = 'none';
        };
        document.getElementById('regionModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        // =================== Khởi tạo lần đầu ===================
        // filteredRegionData = regionSales;
        // renderRegionList();
        fetchRegionSales('today');
    </script>
    {{-- BIỂU ĐỒ THỐNG KÊ CẢ NĂM --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        let monthlyStats = @json($monthlyStats);
        let year = '{{ $year }}';

        function buildChartData(stats) {
            // Tạo mảng 12 tháng, gán giá trị doanh thu, đơn hàng
            let revenue = Array(12).fill(0);
            let orders = Array(12).fill(0);
            stats.forEach(item => {
                let idx = item.month - 1;
                revenue[idx] = Number(item.revenue);
                orders[idx] = Number(item.orders);
            });
            return {
                revenue,
                orders
            };
        }

        let revenueChart;

        function renderRevenueChart(target = "#newrevenueChart", stats = monthlyStats) {
            let chartData = buildChartData(stats);
            // Xóa chart cũ nếu có
            if (revenueChart) {
                revenueChart.destroy();
            }
            let options = {
                chart: {
                    height: 340,
                    type: 'line',
                    stacked: false,
                    toolbar: {
                        show: false
                    }
                },
                dataLabels: {
                    enabled: false
                },
                series: [{
                        name: "Doanh thu",
                        type: 'line',
                        data: chartData.revenue
                    },
                    {
                        name: "Đơn hàng",
                        type: 'line',
                        data: chartData.orders
                    }
                ],
                stroke: {
                    width: [3, 3],
                    curve: "smooth"
                },
                colors: ["#3f51b5", "#50d1f8"],
                xaxis: {
                    categories: ["1", "2", "3", "4", "5", "6", "7", "8", "9", "10", "11", "12"],
                    axisTicks: {
                        show: false
                    },
                    axisBorder: {
                        show: false
                    }
                },
                yaxis: [{
                        title: {
                            text: "Doanh thu"
                        },
                        min: 0,
                        max: 25000000, // Ví dụ 25 triệu
                        tickAmount: 5,
                        labels: {
                            formatter: function(e) {
                                if (e === 0) return '';
                                return e.toLocaleString('vi-VN') + ' đ';
                            },
                            offsetX: -10
                        }
                    },
                    {
                        opposite: true,
                        title: {
                            text: "Đơn hàng"
                        },
                        min: 0,
                        max: 100,
                        tickAmount: 5,
                        labels: {
                            formatter: function(e) {
                                return e;
                            },
                            offsetX: 10
                        }
                    }
                ],
                legend: {
                    show: true,
                    horizontalAlign: "center",
                    offsetY: -5
                },
                grid: {
                    show: false
                },
                tooltip: {
                    shared: true,
                    y: {
                        formatter: function(value, {
                            series,
                            seriesIndex,
                            dataPointIndex,
                            w
                        }) {
                            if (seriesIndex === 0) {
                                // Doanh thu hiển thị dạng số đầy đủ, có phân cách hàng nghìn và đ đằng sau
                                return value.toLocaleString('vi-VN') + ' đ';
                            } else {
                                return value; // Đơn hàng giữ nguyên
                            }
                        }
                    }
                }
            };


            revenueChart = new ApexCharts(document.querySelector(target), options);
            revenueChart.render();
        }

        // Dropdown filter năm
        document.getElementById('btnRevenueFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('revenueFilterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
        };
        document.body.onclick = function(e) {
            if (!e.target.closest('#revenueFilterDropdown') && !e.target.closest('#btnRevenueFilter')) {
                document.getElementById('revenueFilterDropdown').style.display = 'none';
            }
        };

        document.querySelectorAll('input[name="filterRevenueYear"]').forEach(radio => {
            radio.onchange = function() {
                const selectedYear = this.value;
                fetch(`/admin/reports/yearly-data?year=${selectedYear}`)
                    .then(res => res.json())
                    .then(data => {
                        year = data.year;
                        monthlyStats = data.monthlyStats;
                        // Cập nhật lại chart
                        renderRevenueChart("#newrevenueChart", monthlyStats);
                        // Cập nhật lại số đơn và doanh thu bên trên
                        document.getElementById('revenueOrders').innerHTML =
                            data.sumOrders + ' <span class="up"><i class="ri-arrow-up-line"></i></span>';
                        document.getElementById('revenueTotal').innerHTML =
                            Number(data.sumRevenue).toLocaleString('vi-VN') +
                            'đ <span class="up"><i class="ri-arrow-up-line"></i></span>';
                        document.getElementById('revenueFilterDropdown').style.display = 'none';
                    })
                    .catch(err => {
                        console.error("Lỗi lấy dữ liệu:", err);
                    });
            };
        });


        // ===== Fullscreen modal =====
        document.getElementById('btnRevenueFullscreen').onclick = function() {
            document.getElementById('revenueModal').style.display = 'block';
            document.getElementById('modalRevenueContent').innerHTML =
                `<div id="newrevenueChartFull" style="min-width:100%; min-height:380px"></div>`;
            setTimeout(() => {
                renderRevenueChart("#newrevenueChartFull", monthlyStats);
            }, 10);
        };
        document.getElementById('closeRevenueModal').onclick = function() {
            document.getElementById('revenueModal').style.display = 'none';
            if (revenueChart) revenueChart.destroy();
            setTimeout(() => {
                renderRevenueChart("#newrevenueChart", monthlyStats);
            }, 10);
        };
        document.getElementById('revenueModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };

        // ==== Khởi tạo lần đầu ====
        document.addEventListener('DOMContentLoaded', function() {
            renderRevenueChart();
        });
    </script>
    {{-- THỐNG KÊ BIỂU ĐỒ --}}
    <script>
        // Biến lưu chart hiện tại
        let campaignsChart;
        let campaignsChartFull;

        // Dữ liệu mẫu cho từng loại lọc
        const sampleData = {
            week: {
                labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
                completed: [10, 15, 12, 20, 18, 25, 30],
                cancelled: [2, 3, 1, 0, 4, 1, 2]
            },
            month: {
                labels: Array.from({
                    length: 30
                }, (_, i) => i + 1),
                completed: Array.from({
                    length: 30
                }, () => Math.floor(Math.random() * 40) + 10),
                cancelled: Array.from({
                    length: 30
                }, () => Math.floor(Math.random() * 10))
            },
            year: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                completed: [200, 180, 220, 210, 230, 250, 240, 260, 270, 280, 300, 320],
                cancelled: [15, 10, 12, 11, 9, 8, 10, 7, 5, 6, 8, 9]
            }
        };

        // Hàm render chart (target: selector, data: dữ liệu)
        function renderCampaignsChart(target, data) {
            // Nếu đã có chart rồi thì destroy
            if (target === '#newcampaignsChart' && campaignsChart) {
                campaignsChart.destroy();
            }
            if (target === '#newcampaignsChartFull' && campaignsChartFull) {
                campaignsChartFull.destroy();
            }

            const ctx = document.querySelector(target).getContext('2d');
            const newChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                            label: 'Đơn hàng hoàn thành',
                            data: data.completed,
                            borderColor: '#3f51b5',
                            fill: false,
                            tension: 0.3,
                            borderWidth: 2,
                        },
                        {
                            label: 'Đơn hàng bị hủy',
                            data: data.cancelled,
                            borderColor: '#ff4d4f',
                            fill: false,
                            tension: 0.3,
                            borderWidth: 2,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 5
                            }
                        }
                    },
                    plugins: {
                        legend: {
                            position: 'bottom',
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false,
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        intersect: false
                    }
                }
            });

            if (target === '#newcampaignsChart') campaignsChart = newChart;
            else if (target === '#newcampaignsChartFull') campaignsChartFull = newChart;
        }

        // Hàm gọi "ajax" lấy data, ở đây giả lập bằng Promise
        function fetchCampaignData(type) {
            return new Promise((resolve) => {
                setTimeout(() => {
                    resolve(sampleData[type]);
                }, 300); // Giả lập delay
            });
        }

        // Init mặc định với week
        let currentType = 'week';
        fetchCampaignData(currentType).then(data => {
            renderCampaignsChart('#newcampaignsChart', data);
        });

        // Xử lý dropdown filter
        document.getElementById('btnCampaignsFilter').onclick = function(e) {
            e.stopPropagation();
            const dd = document.getElementById('campaignsFilterDropdown');
            dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
        };
        document.body.onclick = function(e) {
            if (!e.target.closest('#campaignsFilterDropdown') && !e.target.closest('#btnCampaignsFilter')) {
                document.getElementById('campaignsFilterDropdown').style.display = 'none';
            }
        };
        document.querySelectorAll('input[name="filterCampaignsTime"]').forEach(radio => {
            radio.onchange = function() {
                currentType = this.value;
                document.getElementById('campaignsFilterDropdown').style.display = 'none';
                // Lấy dữ liệu rồi render
                fetchCampaignData(currentType).then(data => {
                    renderCampaignsChart('#newcampaignsChart', data);
                });
            };
        });

        // Xử lý fullscreen modal
        document.getElementById('btnCampaignsFullscreen').onclick = function() {
            document.getElementById('campaignsModal').style.display = 'block';
            fetchCampaignData(currentType).then(data => {
                renderCampaignsChart('#newcampaignsChartFull', data);
            });
        };
        document.getElementById('closeCampaignsModal').onclick = function() {
            document.getElementById('campaignsModal').style.display = 'none';
            if (campaignsChartFull) {
                campaignsChartFull.destroy();
                campaignsChartFull = null;
            }
        };
        document.getElementById('campaignsModal').onclick = function(e) {
            if (e.target === this) this.style.display = 'none';
        };
    </script>
    {{-- THỐNG KÊ ĐON HÀNG --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let completed = 0;
            let canceled = 0;
            let campaignsFilterType = "day";
            // mặc định tuần này

            // Hàm vẽ donut chart
            function renderDonut(target) {
                const total = completed + canceled;
                const chartDom = document.querySelector(target);
                if (!chartDom) return;
                if (chartDom.echartInstance) {
                    chartDom.echartInstance.dispose();
                }
                let chart = echarts.init(chartDom);
                chartDom.echartInstance = chart;
                chart.setOption({
                    tooltip: {
                        trigger: 'item',
                        formatter: '{b}: <b>{c}</b> ({d}%)'
                    },
                    series: [{
                        name: 'Đơn hàng',
                        type: 'pie',
                        radius: ['65%', '90%'],
                        avoidLabelOverlap: false,
                        label: {
                            show: true,
                            position: 'center',
                            formatter: [
                                '{a|' + total + '}',
                                '{b|Tổng đơn}'
                            ].join('\n'),
                            rich: {
                                a: {
                                    fontSize: 28,
                                    fontWeight: 'bold',
                                    color: '#222'
                                },
                                b: {
                                    fontSize: 13,
                                    color: '#888',
                                    padding: [3, 0, 0, 0]
                                }
                            }
                        },
                        data: [{
                                value: completed,
                                name: 'Hoàn thành',
                                itemStyle: {
                                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                            offset: 0,
                                            color: '#43e97b'
                                        },
                                        {
                                            offset: 1,
                                            color: '#38f9d7'
                                        }
                                    ])
                                }
                            },
                            {
                                value: canceled,
                                name: 'Hủy',
                                itemStyle: {
                                    color: new echarts.graphic.LinearGradient(0, 0, 0, 1, [{
                                            offset: 0,
                                            color: '#fa709a'
                                        },
                                        {
                                            offset: 1,
                                            color: '#fee140'
                                        }
                                    ])
                                }
                            }
                        ]
                    }]
                });
                window.addEventListener('resize', function() {
                    chart.resize();
                });
            }

            // Lấy số liệu đơn hàng hoàn thành/hủy qua AJAX
            function fetchDonutStats(type) {
                fetch(`/admin/reports/donut-stats?type=${type}`)
                    .then(res => res.json())
                    .then(data => {
                        completed = data.completed;
                        canceled = data.canceled;
                        renderDonut('#echartsDonut');
                    })
                    .catch(() => {
                        alert("Lỗi tải dữ liệu đơn hàng!");
                    });
            }

            // Gắn sự kiện filter radio (dùng đúng radio của bạn)
            document.querySelectorAll('input[name="filterCampaignsTime"]').forEach(radio => {
                radio.onchange = function() {
                    campaignsFilterType = this.value;
                    fetchDonutStats(campaignsFilterType);
                    document.getElementById('campaignsFilterDropdown').style.display = 'none';
                };
            });

            // Sự kiện mở/đóng filter dropdown (nếu có)
            document.getElementById('btnCampaignsFilter').onclick = function(e) {
                e.stopPropagation();
                const dd = document.getElementById('campaignsFilterDropdown');
                dd.style.display = (dd.style.display === 'block') ? 'none' : 'block';
                document.getElementById('filterCampaignsDay').checked = (campaignsFilterType === 'day');
                document.getElementById('filterCampaignsWeek').checked = (campaignsFilterType === 'week');
                document.getElementById('filterCampaignsMonth').checked = (campaignsFilterType === 'month');
                document.getElementById('filterCampaignsYear').checked = (campaignsFilterType === 'year');
            };
            document.body.onclick = function(e) {
                if (!e.target.closest('#campaignsFilterDropdown') && !e.target.closest('#btnCampaignsFilter')) {
                    document.getElementById('campaignsFilterDropdown').style.display = 'none';
                }
            };

            // Popup fullscreen chart
            const btnFullscreen = document.getElementById('btnCampaignsFullscreen');
            const modal = document.getElementById('campaignsModal');
            const closeModal = document.getElementById('closeCampaignsModal');
            if (btnFullscreen) {
                btnFullscreen.onclick = function() {
                    modal.style.display = 'block';
                    setTimeout(() => {
                        renderDonut('#newcampaignsChartFull');
                    }, 10);
                };
            }
            if (closeModal) {
                closeModal.onclick = function() {
                    modal.style.display = 'none';
                };
            }
            if (modal) {
                modal.onclick = function(e) {
                    if (e.target === modal) modal.style.display = 'none';
                };
            }

            // Lần đầu load trang, mặc định hôm nay
            fetchDonutStats(campaignsFilterType); // ✅ load đúng theo biến mặc định

        });
    </script>
    {{-- TOP USER; CATEGORY ; SEARCH ; CẦN XÁC NHẬN ĐƠN HÀNG --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Mở dropdown
            document.querySelectorAll('[data-dropdown-id]').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.stopPropagation();
                    document.querySelectorAll('.dropdown-menu').forEach(el => el.style.display =
                        'none');
                    const id = this.getAttribute('data-dropdown-id');
                    const dropdown = document.getElementById('dropdown-' + id);
                    if (dropdown) dropdown.style.display = 'block';
                });
            });

            // Đóng dropdown khi click ngoài
            document.addEventListener('click', () => {
                document.querySelectorAll('.dropdown-menu').forEach(el => el.style.display = 'none');
            });

            // Cập nhật dữ liệu
            const endpoints = {
                topCustomer: '/admin/reports/top-customer',
                processingOrders: '/admin/reports/processing-orders',
                topCategory: '/admin/reports/top-category',
                topSearchedProduct: '/admin/reports/top-searched-product'
            };

            const format = (val, suffix = '') => val ? Number(val).toLocaleString('vi-VN') + suffix : '0' + suffix;

            const fetchData = (id, type) => {
                fetch(`${endpoints[id]}?type=${type}`)
                    .then(res => res.json())
                    .then(data => {
                        if (id === 'topCustomer') {
                            document.getElementById(id + 'Name').innerText = data.name || 'Không có';
                            document.getElementById(id + 'Sub').innerText = format(data.total_amount, '₫');
                        } else if (id === 'processingOrders') {
                            document.getElementById(id + 'Name').innerText = data.count ?? 0;
                        } else if (id === 'topCategory') {
                            document.getElementById(id + 'Name').innerText = data.name || 'Không có';
                            document.getElementById(id + 'Sub').innerText = format(data.total_sold,
                                ' sản phẩm');
                        } else if (id === 'topSearchedProduct') {
                            document.getElementById(id + 'Name').innerText = data.name || 'Không có';
                            document.getElementById(id + 'Sub').innerText = format(data.search_count,
                                ' lượt');
                        }
                    });
            };

            ['topCustomer', 'processingOrders', 'topCategory', 'topSearchedProduct'].forEach(id => {
                document.querySelectorAll(`input[name="${id}Filter"]`).forEach(radio => {
                    radio.addEventListener('change', () => fetchData(id, radio.value));
                });
            });

            // Load mặc định
            fetchData('topCustomer', 'today');
            fetchData('processingOrders', 'today');
            fetchData('topCategory', 'today');
            fetchData('topSearchedProduct', 'today');

        });
    </script>




    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- moment.js -->
    {{-- <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>

    <!-- daterangepicker -->
    <link href="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
    <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="{{ asset('report/assets/css/vendor/apexcharts.css') }}">
    <link rel="stylesheet" href="{{ asset('report/assets/css/vendor/datatables.bootstrap5.min.css') }}">
    <link rel="stylesheet" href="{{ asset('report/assets/css/dashboard-custom.css') }}">
    <script src="{{ asset('report/assets/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('report/assets/js/vendor/jquery.datatables.min.js') }}"></script>
    <script src="{{ asset('report/assets/js/data/ecommerce-chart-data.js') }}"></script> --}}

    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
        <link rel="stylesheet" href="{{ asset('report/assets/css/vendor/apexcharts.css') }}">
        <link rel="stylesheet" href="{{ asset('report/assets/css/vendor/datatables.bootstrap5.min.css') }}">
        <link rel="stylesheet" href="{{ asset('report/assets/css/dashboard-custom.css') }}">
        <link rel="icon" href="{{ asset('frontend/assets/images/favicon/icon.png') }}" type="image/x-icon">
        <link rel="shortcut icon" href="{{ asset('frontend/assets/images/favicon/icon.png') }}" type="image/x-icon">
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/moment/min/moment.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/jvectormap@2.0.5/jquery-jvectormap.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="{{ asset('report/assets/js/vendor/apexcharts.min.js') }}"></script>
        <script src="{{ asset('report/assets/js/vendor/jquery.datatables.min.js') }}"></script>
    @endpush
@endsection

<style>
    .bg-primary-soft {
        background-color: #e8f0fe;
    }

    .bg-danger-soft {
        background-color: #ffe5e5;
    }

    .bg-success-soft {
        background-color: #e6f5ea;
    }

    .bg-warning-soft {
        background-color: #fff6e0;
    }

    .custom-tile .dropdown-menu {
        min-width: 150px;
        z-index: 1000;
        position: absolute;
        right: 0;
        top: 100%;
        background-color: #fff;
        border: 1px solid #ddd;
        display: none;
    }

    .custom-tile .btn {
        font-size: 16px;
        width: 36px;
        height: 36px;
        padding: 0;
    }

    .form-check {
        padding: 0.25rem 0;
    }

    .form-check-label {
        margin-left: 5px;
    }

    /* Màu nền mềm cho các thẻ */
    .bg-primary-soft {
        background-color: #e8f0fe;
        border-left: 4px solid #3b7ddd !important;
    }

    .bg-danger-soft {
        background-color: #ffe5e5;
        border-left: 4px solid #dc3545 !important;
    }

    .bg-success-soft {
        background-color: #e6f5ea;
        border-left: 4px solid #28a745 !important;
    }

    .bg-warning-soft {
        background-color: #fff6e0;
        border-left: 4px solid #fd7e14 !important;
    }

    /* Style cho card */
    .card.custom-tile {
        transition: all 0.3s ease;
        border-radius: 0 !important;
        overflow: hidden;
    }

    .card.custom-tile:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0, 0, 0, 0.1) !important;
    }

    /* Style cho dropdown */
    .custom-tile .dropdown-menu {
        min-width: 150px;
        z-index: 1000;
        position: absolute;
        right: 0;
        top: 100%;
        background-color: #fff;
        border: 1px solid rgba(0, 0, 0, 0.1);
        border-radius: 0 !important;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
        display: none;
        padding: 8px;
    }

    .custom-tile .dropdown-menu.show {
        display: block;
        animation: fadeIn 0.2s ease-in-out;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .custom-tile .btn {
        font-size: 16px;
        width: 36px;
        height: 36px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: white;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: all 0.2s ease;
    }

    .custom-tile .btn:hover {
        background-color: #f8f9fa;
        transform: scale(1.05);
    }

    /* Style cho các text trong card */
    .custom-tile .text-muted {
        color: #6c757d !important;
        font-size: 14px;
    }

    .custom-tile h5 {
        font-size: 18px;
        color: #343a40;
        margin-top: 8px;
    }

    .custom-tile .badge {
        font-size: 13px;
        font-weight: 500;
        padding: 4px 8px;
        margin-top: 8px;
        border-radius: 4px;
    }

    /* Style cho radio buttons trong dropdown */
    .form-check {
        padding: 6px 12px;
        border-radius: 4px;
        transition: background-color 0.2s ease;
    }

    .form-check:hover {
        background-color: #f8f9fa;
    }

    .form-check-input {
        cursor: pointer;
    }

    .form-check-label {
        margin-left: 8px;
        cursor: pointer;
        color: #495057;
        font-size: 14px;
    }

    /* Màu cho icon */
    .custom-tile .ri-timer-line {
        color: #dc3545;
    }

    /* Giới hạn text hiển thị tối đa 2 dòng và thêm dấu ... */
    .custom-tile h5 {
        font-size: 18px;
        color: #343a40;
        margin-top: 8px;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        /* Giới hạn số dòng */
        -webkit-box-orient: vertical;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
        line-height: 1.4;
        /* Điều chỉnh cho đẹp */
        min-height: 2.8em;
        /* 2 dòng x 1.4 line-height */
    }

    /* Với các text ngắn 1 dòng thì vẫn giữ nguyên */
    .custom-tile .badge {
        font-size: 13px;
        font-weight: 500;
        padding: 4px 8px;
        margin-top: 8px;
        border-radius: 4px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 100%;
    }

    /* Đảm bảo card có chiều cao cố định */
    .card.custom-tile {
        height: 100%;
        min-height: 150px;
        display: flex;
        flex-direction: column;
    }

    .card.custom-tile .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 16px;
        /* Thêm padding cho đẹp */
    }

    /* Sửa radio trong filter dropdown cho căn đều đẹp */
    #filterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #filterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #filterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    /* */
    #topRatedFilterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #topRatedFilterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #topRatedFilterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    /* */
    #cancelledFilterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #cancelledFilterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #cancelledFilterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    /* */
    #regionFilterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #regionFilterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #regionFilterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    /* */
    #revenueFilterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #revenueFilterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #revenueFilterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }

    /* */
    #campaignsFilterDropdown .form-check {
        display: flex;
        align-items: center;
        gap: 8px;
    }

    #campaignsFilterDropdown .form-check-input {
        margin-top: 0 !important;
        margin-right: 8px;
        margin-left: 0 !important;
        /* Ẩn default outline nếu thích */
    }

    #campaignsFilterDropdown .form-check-label {
        padding-left: 0 !important;
        font-size: 15px;
        color: #333;
        font-weight: 500;
    }


    .btn-square {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 30px;
        /* Bạn có thể để 32px, 36px hoặc 40px tùy mắt */
        height: 30px;
        border: 1px solid #eee;
        color: #777;
        border-radius: 7px;
        /* Vuông vắn, nhẹ nhàng, đổi 8px hoặc 6px nếu thích */
        background: none;
        padding: 0;
        /* Rất quan trọng: không để padding */
        cursor: pointer;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }

    .cr-detail-list {
        padding-top: 2px !important;
        padding-bottom: 2px !important;
        margin-bottom: 0 !important;
    }

    .cr-detail-list .progress {
        margin-top: 2px !important;
        margin-bottom: 0 !important;
    }

    .cr-detail-list label,
    .cr-detail-list p {
        margin-bottom: 0 !important;
    }


    .btn-square i {
        font-size: 14px;
        /* Kích thước icon */
        color: #777;
        /* Màu icon, đổi #000 nếu muốn đậm hơn */
    }

    .dashboard-growth-placeholder {
        color: #00b894;
        /* hoặc #bbb nếu muốn nhạt */
        font-size: 2em;
        /* to hơn nữa thì tăng lên 2.5em hoặc 3em */
        font-weight: bold;
        font-style: normal;
        letter-spacing: 4px;
        opacity: 0.7;
        margin-left: 6px;
        margin-top: 10px;
        display: inline-block;
        line-height: 1.2;
    }

    .growth-block {
        display: flex;
        flex-direction: column;
        align-items: flex-end;
        /* Căn phải cho số */
        margin-top: 20px;
        /* Hoặc điều chỉnh margin-top cho vừa mắt */
        position: relative;
    }

    .growth-value {
        font-size: 2em;
        font-weight: bold;
        color: #34495e;
    }

    .growth-line {
        margin-top: 8px;
        width: 70%;
        /* Độ dài line, điều chỉnh cho đẹp */
        height: 4px;
        background: #55bfa3;
        /* Màu xanh nhạt */
        border-radius: 2px;
        align-self: flex-end;
        /* Bắt đầu ngay dưới số */
    }

    /* Form filter container */
    #dashboardFilter {
        padding: 0.75rem 1rem;
        gap: 1rem;
    }

    /* Form group styling */
    #dashboardFilter .form-group {
        margin-bottom: 0;
    }

    /* Label styling */
    #dashboardFilter label {
        font-size: 0.875rem;
        color: #495057;
        font-weight: 500;
        min-width: 60px;
    }

    /* Input field styling */
    #dashboardFilter .form-control {
        height: 36px;
        font-size: 0.875rem;
        border: 1px solid #e0e0e0;
        border-radius: 4px;
        padding: 0.375rem 0.75rem;
        width: 150px;
    }

    /* Button styling */
    #dashboardFilter .filter-btn {
        height: 36px;
        font-size: 0.875rem;
        padding: 0.375rem 0.75rem;
        border-radius: 4px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Primary filter button */
    #dashboardFilter .btn-primary {
        background-color: #3f80ea;
        border-color: #3f80ea;
    }

    /* Today button */
    #dashboardFilter .btn-filter-today {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
        color: #495057;
    }

    /* Reset button */
    #dashboardFilter .btn-light {
        background-color: #f8f9fa;
        border: 1px solid #e0e0e0;
    }

    /* Hover effects */
    #dashboardFilter .filter-btn:hover {
        opacity: 0.9;
        transform: translateY(-1px);
        transition: all 0.2s ease;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        #dashboardFilter {
            flex-direction: column;
            align-items: flex-start;
        }

        #dashboardFilter .form-group {
            width: 100%;
        }

        #dashboardFilter .form-control {
            width: 100%;
        }

        #dashboardFilter .filter-btn {
            width: 100%;
        }
    }
</style>
{{-- popup fullsreeen --}}
<div id="bestSellerModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:90vw; max-width:1200px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Sản phẩm bán chạy</h4>
            <button id="closeBestSellerModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalBestSellerContent">
                <!-- JS sẽ render bảng 10 sản phẩm vào đây -->
            </div>
        </div>
    </div>
</div>
<div id="topRatedModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:90vw; max-width:1200px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Sản phẩm được đánh giá cao</h4>
            <button id="closeTopRatedModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalTopRatedContent">
                <!-- JS sẽ render bảng 10 sản phẩm vào đây -->
            </div>
        </div>
    </div>
</div>
<div id="cancelledModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:90vw; max-width:1200px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Sản phẩm bị hủy nhiều nhất</h4>
            <button id="closeCancelledModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalCancelledContent">
                <!-- JS sẽ render bảng 10 sản phẩm vào đây -->
            </div>
        </div>
    </div>
</div>
<!-- Modal Fullscreen -->
<div id="regionModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:90vw; max-width:700px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Top 6 vùng miền doanh thu cao nhất</h4>
            <button id="closeRegionModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalRegionContent">
                <!-- JS sẽ render bảng 6 vùng miền vào đây -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Fullscreen -->
{{-- <div id="cancelledModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:90vw; max-width:1200px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Top sản phẩm bị hủy nhiều nhất</h4>
            <button id="closeCancelledModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalCancelledContent">
                <!-- JS sẽ render bảng 10 sản phẩm vào đây -->
            </div>
        </div>
    </div>
</div> --}}
{{-- Modal Fullscreen --}}
<div id="revenueModal"
    style="display:none; position:fixed; z-index:9999999; left:0; top:0; width:100vw; height:100vh; background:rgba(30,40,60,0.33);">
    <div
        style="position:absolute; left:50%; top:50%; transform:translate(-50%,-50%); width:96vw; max-width:1200px; background:#fff; box-shadow:0 0 24px 0 #0004; border-radius:10px; overflow:auto; max-height:95vh;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Biểu đồ Tổng Quan Doanh Thu</h4>
            <button id="closeRevenueModal"
                style="background:none;border:none;font-size:34px;cursor:pointer;line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="modalRevenueContent">
                <!-- JS sẽ render chart vào đây -->
            </div>
        </div>
    </div>
</div>
<div id="campaignsModal"
    style="display:none; position:fixed; z-index:99999; top:0; left:0; width:100vw; height:100vh; background:rgba(0,0,0,0.6);">
    <div
        style="position:absolute; top:50%; left:50%; transform:translate(-50%, -50%);
        background:#fff; border-radius:8px; width:90vw; max-width:900px; max-height:90vh; overflow:auto;">
        <div
            style="display:flex; justify-content:space-between; align-items:center; padding:18px 28px; border-bottom:1px solid #eee;">
            <h4 style="font-size:21px; margin:0;">Thống kê Đơn hàng</h4>
            <button id="closeCampaignsModal"
                style="background:none; border:none; font-size:34px; cursor:pointer; line-height:1;">&times;</button>
        </div>
        <div style="padding:28px;">
            <div id="newcampaignsChartFull" style="min-height:400px;"></div>
        </div>
    </div>
</div>
{{-- QUÂN ĐẸP TRAI --}}
