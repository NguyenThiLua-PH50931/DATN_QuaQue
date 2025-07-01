@extends('layouts.backend')

@section('title', 'Dashboard Báo cáo')

@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Dashboard Báo cáo</title>
</head>

<body>
    <div class="page-body">

        <div class="container mt-5">
            <h2 class="mb-4">Thống kê</h2>
            <div class="p-3">
                <form method="GET" action="{{ route('admin.dashboard') }}" class="mb-4">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label">Ngày cụ thể:</label>
                            <input type="date" name="rating_date" class="form-control form-control-sm"
                                value="{{ request('rating_date') }}" id="rating_date" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Từ ngày:</label>
                            <input type="date" name="rating_from_date" class="form-control form-control-sm"
                                value="{{ request('rating_from_date') }}" id="rating_from_date" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Đến ngày:</label>
                            <input type="date" name="rating_to_date" class="form-control form-control-sm"
                                value="{{ request('rating_to_date') }}" id="rating_to_date" max="{{ date('Y-m-d') }}">
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col text-end d-flex justify-content-end">
                            <button type="submit" class="btn btn-primary btn-sm me-2">Lọc</button>
                            <button type="submit" name="reset" value="1" class="btn btn-secondary btn-sm">Reset</button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="row">
                <!-- Tổng doanh thu -->
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 border-0 card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng doanh thu năm {{ date('Y') }}</span>
                                    <h4 class="mb-0 counter">
                                        {{ number_format($totalRevenue ?? 0) }} VND
                                        <span class="badge badge-light-primary grow">
                                            <i data-feather="trending-up"></i>8.5%
                                        </span>
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-database-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Số đơn hàng hoàn thành -->
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                        <div class="custome-2-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Đơn hàng hoàn thành</span>
                                    <h4 class="mb-0 counter">
                                        {{ $completedOrders ?? 0 }}
                                        <span class="badge badge-light-danger grow">
                                            <i data-feather="trending-down"></i>8.5%
                                        </span>
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-shopping-bag-3-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Tổng yêu cầu hỗ trợ -->
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                        <div class="custome-3-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng yêu cầu hỗ trợ</span>
                                    <h4 class="mb-0 counter">
                                        {{ $totalRequests ?? 0 }}
                                        <a href="#" class="badge badge-light-secondary grow">
                                            CHI TIẾT</a>
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-chat-3-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Người đăng ký mới -->
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                        <div class="custome-4-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Người đăng ký mới (tháng {{ date('m') }})</span>
                                    <h4 class="mb-0 counter">
                                        {{ $newUsers ?? 0 }}
                                        <span class="badge badge-light-success grow">
                                            <i data-feather="trending-up"></i>8.5%
                                        </span>
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-user-add-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sản phẩm bán chạy nhất -->
                <div class="col-xl-6 col-md-12">
                    <div class="card o-hidden card-hover">
                        <div class="card-header card-header-top card-header--2 px-0 pt-0">
                            <div class="card-header-title">
                                <h4>Sản phẩm bán chạy nhất</h4>
                            </div>

                        </div>

                        <div class="card-body p-0">
                            <div>
                                <div class="table-responsive">
                                    <table class="best-selling-table w-image table border-0">
                                        <tbody>

                                            @if($topProduct)

                                            <tr>
                                                <td>
                                                    <div class="best-product-box">
                                                        <div class="product-image">
                                                            <img src="{{ asset('storage/' . ($topProduct->image ?? 'default.png')) }}"
                                                                class="img-fluid" alt="Product">
                                                        </div>
                                                        <div class="product-name">
                                                            @if ($topProduct)
                                                                <h5>{{ $topProduct->name }}</h5>
                                                                <h6>
                                                                    {{ $topProduct->created_at ? \Carbon\Carbon::parse($topProduct->created_at)->format('d-m-Y') : 'N/A' }}
                                                                </h6>
                                                            @else
                                                                <span>Không có dữ liệu</span>
                                                            @endif

                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="product-detail-box">
                                                        <h6>Doanh thu</h6>
                                                        <h5>{{ number_format($topProduct->total_revenue ?? 0) }} VND</h5>
                                                    </div>
                                                </td>
                                                <td colspan="3" class="text-muted text-center">
                                                    <em>Sản phẩm có doanh thu cao nhất</em>
                                                </td>
                                            </tr>
                                            @else
                                            <tr>
                                                <td colspan="5" class="text-center text-muted py-4">
                                                    <em>Chưa có dữ liệu sản phẩm bán chạy</em>
                                                </td>
                                            </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Vùng miền bán chạy nhất -->
                <div class="col-xl-6 col-md-12">
                    <div class="card o-hidden card-hover">
                        <div class="card-header card-header-top card-header--2 px-0 pt-0">
                            <div class="card-header-title">
                                <h4>Vùng miền bán chạy nhất</h4>
                            </div>

                        </div>

                        <div class="card-body p-0">
                            {{-- ✅ Box: Vùng bán chạy nhất --}}
                            <div class="p-3 border-bottom">
                                <h5 class="mb-2">Vùng bán chạy nhất</h5>
                                @if($topRegion)
                                <p class="mb-0 text-muted">
                                    <strong>{{ $topRegion->name }}</strong>: {{ number_format($topRegion->total_revenue ?? 0) }} VND
                                </p>
                                @else
                                <p class="mb-0 text-muted">Chưa có dữ liệu</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>




                <!-- Sản phẩm được đánh giá cao nhất -->
                <div class="col-xl-6 col-md-12">
                    <div class="card o-hidden card-hover">
                        <div class="card-header card-header-top card-header--2 px-0 pt-0">
                            <div class="card-header-title">
                                <h4>Sản phẩm được đánh giá cao nhất</h4>
                            </div>


                        </div>

                        <div class="card-body p-0">
                            <!-- Thêm phần sản phẩm được đánh giá cao nhất ở đây -->
                            <div class="p-3">
                                <h5 class="card-title">Sản phẩm được đánh giá cao nhất</h5>
                                @if($topRatedProduct)
                                <p class="card-text">
                                    {{ $topRatedProduct->name }}:
                                    {{ number_format($topRatedProduct->average_rating ?? 0, 1) }} ★
                                </p>
                                @else
                                <p class="card-text">Chưa có dữ liệu</p>
                                @endif
                            </div>

                        </div>
                    </div>
                </div>


                <!-- Thống kê đơn hàng -->
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <div class="card-body">
                            <h5 class="card-title">Thống kê đơn hàng 2</h5>
                            <canvas id="orderStatusChart" width="200" height="200"></canvas>

                        </div>
                        <div class="card-body">
                            <canvas id="orderStatusChart" height="50"></canvas>
                            <div class="mt-3 text-center">
                                <span class="badge bg-success me-2">Đã hoàn thành: {{ $completed ?? 0 }}</span>
                                <span class="badge bg-danger">Bị hủy: {{ $canceled ?? 0 }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Thống kê đơn hàng -->
    {{-- <div class="card">
        <div class="card-header">
            <h4 class="mb-0">Thống kê đơn hàng</h4>
        </div>
        <div class="card-body">
            <canvas id="orderStatusChart" height="200"></canvas>
            <div class="mt-3 text-center">
                <span class="badge bg-success me-2">Đã hoàn thành: {{ $completed ?? 0 }}</span>
                <span class="badge bg-danger">Bị hủy: {{ $canceled ?? 0 }}</span>
            </div>
        </div>
    </div> --}}

    <!-- Thư viện Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- Script vẽ biểu đồ -->
    <script>
        const ctx = document.getElementById('orderStatusChart').getContext('2d');
        const completed = parseInt('{{ $completed ?? 0 }}');
        const canceled = parseInt('{{ $canceled ?? 0 }}');

        new Chart(ctx, {
            type: 'pie',
            data: {
                labels: ['Đã hoàn thành', 'Bị hủy'],
                datasets: [{
                    data: [completed, canceled],
                    backgroundColor: ['#4CAF50', '#F44336'],
                    borderColor: '#ffffff',
                    borderWidth: 2
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            font: {
                                size: 14
                            },
                            color: '#333'
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>

<!-- @includeIf('backend.footer') -->
</div>
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ratingDate = document.getElementById('rating_date');
        const fromDate = document.getElementById('rating_from_date');
        const toDate = document.getElementById('rating_to_date');

        function toggleDateFields() {
            if (ratingDate.value) {
                fromDate.disabled = true;
                toDate.disabled = true;
            } else {
                fromDate.disabled = false;
                toDate.disabled = false;
            }

            if (fromDate.value || toDate.value) {
                ratingDate.disabled = true;
            } else {
                ratingDate.disabled = false;
            }
        }

        ratingDate.addEventListener('change', toggleDateFields);
        fromDate.addEventListener('change', toggleDateFields);
        toDate.addEventListener('change', toggleDateFields);


        toggleDateFields();
    });
</script>
@endpush
@endsection