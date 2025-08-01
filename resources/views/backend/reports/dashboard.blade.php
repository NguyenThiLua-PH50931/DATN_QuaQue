@extends('layouts.backend')

@section('title', 'Báo cáo tổng quan')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            @php
                function renderStarRatingSimple($rating)
                {
                    $fullStars = floor($rating);
                    $emptyStars = 5 - $fullStars;

                    $html = '';
                    for ($i = 0; $i < $fullStars; $i++) {
                        $html .= '<i class="fa-solid fa-star" style="color:#ffd700; margin-right: 2px;"></i>';
                    }
                    for ($i = 0; $i < $emptyStars; $i++) {
                        $html .= '<i class="fa-regular fa-star" style="color:#ccc; margin-right: 2px;"></i>';
                    }
                    return $html;
                }
            @endphp
            {{-- FILTER NGÀY --}}
            <div class="row mb-4">
                <div class="col-12">
                    <form method="GET" action="{{ route('admin.dashboard') }}"
                        class="row gy-2 gx-3 align-items-end bg-white rounded shadow-sm p-3">
                        <div class="col-md-3">
                            <label class="form-label mb-1">Ngày cụ thể</label>
                            <input type="date" name="rating_date" class="form-control form-control-sm"
                                value="{{ request('rating_date') }}" id="rating_date" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">Từ ngày</label>
                            <input type="date" name="rating_from_date" class="form-control form-control-sm"
                                value="{{ request('rating_from_date') }}" id="rating_from_date" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label mb-1">Đến ngày</label>
                            <input type="date" name="rating_to_date" class="form-control form-control-sm"
                                value="{{ request('rating_to_date') }}" id="rating_to_date" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-3 d-flex gap-2">
                            <button type="submit" class="btn btn-primary btn-sm flex-fill">Lọc</button>
                            <button type="submit" name="reset" value="1"
                                class="btn btn-outline-secondary btn-sm flex-fill">Reset</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- DÃY CARD THỐNG KÊ --}}
            <div class="row g-3">
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 border-0 card-hover card o-hidden">
                        <div class="custome-1-bg b-r-4 card-body">
                            <div class="media align-items-center static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Tổng doanh thu</span>
                                    <h4 class="mb-0 counter">
                                        {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}₫
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-database-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                        <div class="custome-2-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Đơn hoàn thành</span>
                                    <h4 class="mb-0 counter">
                                        {{ $completedOrders ?? 0 }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-shopping-bag-3-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0  card o-hidden">
                        <div class="custome-3-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Người dùng mới (tháng)</span>
                                    <h4 class="mb-0 counter">
                                        {{ $newUsers ?? 0 }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-user-add-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xxl-3 col-lg-6">
                    <div class="main-tiles border-5 card-hover border-0 card o-hidden">
                        <div class="custome-4-bg b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="media-body p-0">
                                    <span class="m-0">Yêu cầu hỗ trợ</span>
                                    <h4 class="mb-0 counter">
                                        {{ $totalRequests ?? 0 }}
                                    </h4>
                                </div>
                                <div class="align-self-center text-center">
                                    <i class="ri-customer-service-2-line"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- CARD BÁO CÁO CHI TIẾT --}}
            <div class="row g-3 mt-1">
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 pb-2 bg-white">
                        <h5 class="mb-0 fw-bold">Sản phẩm bán chạy</h5>
                        <div>
                            {{-- Chỗ này để thêm bộ lọc, xem toàn màn hình nếu cần --}}
                        </div>
                    </div>
                    <div class="card-body pb-2 pt-2">
                        <div class="table-responsive">
                            <table id="topProductTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th class="align-middle">Sản phẩm</th>
                                        <th class="align-middle">Biến thể</th>
                                        <th class="align-middle">Giá bán</th>
                                        <th class="align-middle">Số lượng</th>
                                        <th class="align-middle">Tồn kho</th>
                                        <th class="align-middle">Tổng doanh thu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($topProductVariants as $v)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ asset('storage/' . ($v->image ?? 'default.png')) }}"
                                                        alt="" class="rounded" width="38" height="38"
                                                        style="object-fit:cover;">
                                                    <span class="fw-semibold">{{ $v->product_name }}</span>
                                                </div>
                                            </td>
                                            <td class="align-middle">{{ $v->variant_name }}</td>
                                            <td class="align-middle">{{ number_format($v->price, 0, ',', '.') }}₫</td>
                                            <td class="align-middle">{{ $v->sold_quantity }}</td>
                                            <td class="align-middle">{{ $v->stock }}</td>
                                            <td class="align-middle">{{ number_format($v->total_revenue, 0, ',', '.') }}₫
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                {{-- Đánh giá --}}
                <div class="card mt-4">
                    <div class="card-header d-flex justify-content-between align-items-center border-0 pb-2 bg-white">
                        <h5 class="mb-0 fw-bold">Sản Phẩm Đã Đánh Giá</h5>
                    </div>
                    <div class="card-body pb-2 pt-2">
                        <div class="table-responsive">
                            <table id="ratedProductsTable" class="table table-hover" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>Sản phẩm</th>
                                        <th>Sao trung bình</th>
                                        <th>Tổng lượt đánh giá</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($ratedProducts as $p)
                                        <tr class="rating-row @if ($loop->first) hot-row @endif">
                                            <td>
                                                <div class="d-flex align-items-center gap-2">
                                                    <img src="{{ asset('storage/' . ($p->image ?? 'default.png')) }}"
                                                        alt="{{ $p->product_name }}" class="product-avatar">
                                                    <span class="product-title">{{ $p->product_name }}</span>
                                                </div>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="star-rating">{!! renderStarRatingSimple($p->average_rating) !!}</span>
                                            </td>
                                            <td class="text-center align-middle">
                                                <span class="fw-bold">{{ $p->review_count }}</span>
                                                <div class="mini-progress">
                                                    <div class="mini-progress-bar"
                                                        style="width: {{ min(100, ($p->review_count / ($maxReviewCount ?? 1)) * 100) }}%">
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="card mt-0">
                            <div class="card-header border-0 pb-2">
                                <h5 class="mb-0 fw-bold">Báo cáo vùng bán hàng</h5>
                            </div>
                            <div class="card-body p-0">
                                <div class="table-responsive">
                                    <table id="regionSalesTable" class="table table-hover" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Tên vùng</th>
                                                <th>Tổng tiền</th>
                                                <th>Tổng sản phẩm</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($regionSales as $region)
                                                <tr>
                                                    <td>
                                                        <span class="region-icon me-2">
                                                            <i class="fa-solid fa-location-dot"></i>
                                                            {{-- Hoặc: <img src="{{ asset('icons/location-red.png') }}" ... > --}}
                                                        </span>
                                                        {{ $region->name }}
                                                    </td>
                                                    <td>{{ number_format($region->total_revenue, 0, ',', '.') }}₫</td>
                                                    <td>{{ $region->total_quantity_sold }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="card card-hover h-100">
                            <div class="card-header">
                                <h5 class="mb-0">Thống kê trạng thái đơn hàng</h5>
                            </div>
                            <div class="card-body" style="max-width: 300px; margin: 0 auto;">
                                <canvas id="orderStatusChart" style="width: 100%; height: auto;"></canvas>
                                <div class="mt-3 text-center">
                                    <span class="badge bg-success me-2">Đã hoàn thành: {{ $completed ?? 0 }}</span>
                                    <span class="badge bg-danger">Bị hủy: {{ $canceled ?? 0 }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card mb-4 shadow-sm">
                            <div
                                class="card-header border-0 pb-2 bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-bold">
                                    Biểu đồ doanh thu & đơn hàng theo tháng
                                </h5>
                                <form method="get" action="{{ route('admin.dashboard') }}"
                                    class="d-flex align-items-center">
                                    <label for="year" class="me-2 mb-0 fw-normal">Năm</label>
                                    <select name="year" id="year" class="form-select form-select-sm"
                                        style="width: 90px" onchange="this.form.submit()">
                                        @for ($y = date('Y') - 3; $y <= date('Y'); $y++)
                                            <option value="{{ $y }}"
                                                @if ($year == $y) selected @endif>{{ $y }}
                                            </option>
                                        @endfor
                                    </select>
                                </form>
                            </div>
                            <div class="card-body">
                                <canvas id="trendChart" height="110"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    @endsection
    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // --- PIE CHART ---
                const ctxOrderStatus = document.getElementById('orderStatusChart')?.getContext('2d');
                if (ctxOrderStatus) {
                    new Chart(ctxOrderStatus, {
                        type: 'pie',
                        data: {
                            labels: ['Đã hoàn thành', 'Bị hủy'],
                            datasets: [{
                                data: [{{ $completed ?? 0 }}, {{ $canceled ?? 0 }}],
                                backgroundColor: ['#4CAF50', '#F44336'],
                                borderColor: '#fff',
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
                }

                // --- DỮ LIỆU ---
                const months = @json($monthlyStats->pluck('month') ?? []);
                const revenueData = @json($monthlyStats->pluck('revenue') ?? []);
                const orderData = @json($monthlyStats->pluck('orders') ?? []);
                const monthLabels = Array.from({
                    length: 12
                }, (_, i) => 'Th' + (i + 1));
                let revenueFull = Array(12).fill(0);
                let orderFull = Array(12).fill(0);
                months.forEach((m, idx) => {
                    revenueFull[m - 1] = revenueData[idx];
                    orderFull[m - 1] = orderData[idx];
                });

                // --- TÍNH GIÁ TRỊ MAX ĐƠN HÀNG ---
                let maxOrder = Math.max(...orderFull, 10);
                // Đảm bảo hiển thị trục hợp lý: luôn >=20, làm tròn lên theo bội 10
                maxOrder = Math.max(20, Math.ceil(maxOrder / 10) * 10);

                // --- LINE CHART ---
                const ctxTrend = document.getElementById('trendChart')?.getContext('2d');
                if (ctxTrend) {
                    // Nếu cần clear chart cũ, dùng biến lưu lại để destroy (fix bug canvas already in use)
                    if (window.trendChartIns) {
                        window.trendChartIns.destroy();
                    }
                    window.trendChartIns = new Chart(ctxTrend, {
                        type: 'line',
                        data: {
                            labels: monthLabels,
                            datasets: [{
                                    label: 'Doanh thu (₫)',
                                    data: revenueFull,
                                    borderColor: '#4f8cff',
                                    backgroundColor: 'rgba(79,140,255,0.09)',
                                    fill: false,
                                    tension: 0.33,
                                    pointRadius: 3,
                                    borderWidth: 2,
                                    yAxisID: 'y'
                                },
                                {
                                    label: 'Đơn hàng',
                                    data: orderFull,
                                    borderColor: '#34c997',
                                    backgroundColor: 'rgba(52,201,151,0.07)',
                                    fill: false,
                                    tension: 0.33,
                                    pointRadius: 3,
                                    borderWidth: 2,
                                    yAxisID: 'y2'
                                }
                            ]
                        },
                        options: {
                            responsive: true,
                            plugins: {
                                tooltip: {
                                    mode: 'nearest',
                                    intersect: false, // Hover vào vùng dọc sẽ show tooltip gần nhất
                                    callbacks: {
                                        label: function(context) {
                                            let label = context.dataset.label || '';
                                            if (label === 'Doanh thu (₫)') {
                                                return label + ': ' + (context.parsed.y || 0)
                                                    .toLocaleString('vi-VN') + '₫';
                                            }
                                            if (label === 'Đơn hàng') {
                                                return label + ': ' + (context.parsed.y || 0)
                                                    .toLocaleString('vi-VN');
                                            }
                                            return label + ': ' + (context.parsed.y || 0);
                                        }
                                    }
                                }
                            },
                            interaction: {
                                mode: 'nearest',
                                intersect: false
                            },
                            scales: {
                                x: {
                                    grid: {
                                        display: true,
                                        drawBorder: false,
                                        color: '#bbb',
                                        borderDash: [5, 5],
                                        lineWidth: 1
                                    }
                                },
                                y: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: 10000000, // 10 triệu, tuỳ chỉnh
                                    title: {
                                        display: true,
                                        text: 'Doanh thu (₫)'
                                    },
                                    ticks: {
                                        stepSize: 2000000,
                                        callback: value => (value / 1000000) + 'tr'
                                    },
                                    grid: {
                                        color: 'rgba(200,200,200,0.06)'
                                    }
                                },
                                y2: {
                                    beginAtZero: true,
                                    min: 0,
                                    max: maxOrder,
                                    position: 'right',
                                    title: {
                                        display: true,
                                        text: 'Đơn hàng'
                                    },
                                    ticks: {
                                        stepSize: 5,
                                        callback: value => value
                                    },
                                    grid: {
                                        drawOnChartArea: false
                                    }
                                }
                            }
                        }
                    });
                }

                // --- DataTable ---
                function initDataTable(selector, orderColIndex = 3, orderDir = 'desc') {
                    if (!$(selector).length) return;
                    $(selector).DataTable({
                        language: {
                            search: "",
                            searchPlaceholder: "Tìm kiếm...",
                            lengthMenu: "Hiển thị _MENU_ dòng",
                            info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ dòng",
                            paginate: {
                                next: "Sau",
                                previous: "Trước"
                            },
                            zeroRecords: "Không tìm thấy kết quả phù hợp",
                            infoEmpty: "Không có dữ liệu",
                            infoFiltered: "(lọc từ _MAX_ dòng)"
                        },
                        pageLength: 5,
                        lengthMenu: [5, 10, 20, 50],
                        order: [
                            [orderColIndex, orderDir]
                        ],
                        columnDefs: [{
                            orderable: true,
                            targets: "_all"
                        }],
                        dom: '<"d-flex flex-wrap align-items-center mb-3"<"me-auto"l><"ms-auto"f>>t<"d-flex flex-wrap align-items-center mt-2"<"me-auto"i><"ms-auto"p>>',
                        pagingType: "simple"
                    });
                }

                initDataTable('#topProductTable', 3, 'desc');
                initDataTable('#ratedProductsTable', 1, 'desc');
            });
        </script>


        <style>
            /* Bảng tổng thể */
            #ratedProductsTable,
            #ratedProductsTable th,
            #ratedProductsTable td {
                border: none !important;
                background: transparent !important;
            }

            #ratedProductsTable {
                border-collapse: separate;
                border-spacing: 0 8px;
                background: #fff;
                font-size: 15px;
            }

            /* Header */
            #ratedProductsTable th {
                font-weight: 700;
                color: #313150;
                background: #f9fafc !important;
                border-bottom: 2px solid #e9edf3 !important;
                text-align: left;
                font-size: 15.2px;
                padding: 7px 14px !important;
                letter-spacing: 0.1px;
            }

            #ratedProductsTable th:first-child,
            #ratedProductsTable td:first-child {
                width: 35% !important;
                max-width: 35% !important;
                min-width: 180px;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #ratedProductsTable th:nth-child(2),
            #ratedProductsTable td:nth-child(2),
            #ratedProductsTable th:nth-child(3),
            #ratedProductsTable td:nth-child(3) {
                width: 20% !important;
                max-width: 22% !important;
                text-align: center;
            }

            /* Dòng table */
            .rating-row {
                transition: background 0.2s, box-shadow 0.15s;
                background: #f7fafd !important;
                border-radius: 11px !important;
            }

            .rating-row.hot-row {
                background: linear-gradient(90deg, #fffbe9 80%, #fff8e6 100%) !important;
                font-weight: 600;
                box-shadow: 0 2px 10px 0 rgba(255, 220, 120, 0.05);
            }

            .rating-row:hover {
                background: #f2f7ff !important;
                box-shadow: 0 2px 12px 0 rgba(44, 98, 255, 0.08);
                cursor: pointer;
            }

            /* Ảnh, tên, badge */
            .product-avatar {
                width: 34px;
                height: 34px;
                border-radius: 7px;
                object-fit: cover;
                box-shadow: 0 1px 7px 0 rgba(50, 60, 90, 0.09);
                border: 1.5px solid #f5f5f9;
            }

            .product-title {
                font-weight: 600;
                font-size: 15px;
                color: #222;
            }

            .badge.bg-gradient-purple {
                background: linear-gradient(90deg, #6366f1 10%, #c084fc 100%);
                color: #fff !important;
                font-weight: 500;
                border-radius: 7px;
                padding: 4px 12px;
                font-size: 12px;
                margin-left: 5px;
                vertical-align: middle;
                display: inline-flex;
                align-items: center;
                gap: 3px;
                box-shadow: 0 1px 6px 0 rgba(122, 97, 255, 0.08);
            }

            /* Star rating */
            .star-rating i {
                font-size: 21px !important;
                color: #ffd700 !important;
                margin-right: 1.5px;
                transition: transform 0.12s;
                filter: drop-shadow(0 1px 3px #fffae0);
                vertical-align: middle;
            }

            .star-rating i.fa-regular {
                color: #d2d2d2 !important;
                filter: none;
            }

            .star-rating i:hover {
                transform: scale(1.13) rotate(-9deg);
                filter: drop-shadow(0 0 7px #ffe492);
            }

            /* Progress bar số review nhỏ */
            .mini-progress {
                background: #e6eefa;
                border-radius: 7px;
                width: 44px;
                height: 7px;
                overflow: hidden;
                margin-left: 6px;
                box-shadow: 0 0 4px #e0eaff;
                display: inline-block;
                vertical-align: middle;
            }

            .mini-progress-bar {
                height: 100%;
                background: linear-gradient(90deg, #36caff 25%, #156eff 100%);
                border-radius: 7px;
                transition: width 0.28s cubic-bezier(.41, 0, .6, 1);
            }

            /* Responsive nhỏ gọn hơn */
            @media (max-width: 900px) {

                #ratedProductsTable th,
                #ratedProductsTable td {
                    font-size: 13px !important;
                    padding: 5px 7px !important;
                }

                .product-title {
                    font-size: 12px;
                }

                .star-rating i {
                    font-size: 15px !important;
                }

                .badge.bg-gradient-purple {
                    font-size: 9px;
                }
            }

            #ratedProductsTable th:first-child,
            #ratedProductsTable td:first-child {
                width: 45% !important;
                max-width: 45% !important;
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
            }

            #ratedProductsTable th:nth-child(2),
            #ratedProductsTable td:nth-child(2),
            #ratedProductsTable th:nth-child(3),
            #ratedProductsTable td:nth-child(3) {
                width: 27.5% !important;
                max-width: 27.5% !important;
                text-align: center;
            }


            /* Căn chỉnh chung cho bảng */
            #topProductTable,
            #ratedProductsTable {
                border-collapse: separate;
                border-spacing: 0;
                background: #fff;
            }

            #topProductTable th,
            #topProductTable td,
            #ratedProductsTable th,
            #ratedProductsTable td {
                border: none !important;
                background: none !important;
                padding: 13px 12px !important;
                font-size: 16px;
            }

            #topProductTable th,
            #ratedProductsTable th {
                font-weight: 600;
                color: #2c2e35;
                background: #fafcff;
                border-bottom: 1px solid #f0f1f7 !important;
                text-align: left;
            }

            #topProductTable td,
            #ratedProductsTable td {
                vertical-align: middle;
            }

            #topProductTable tbody tr:hover,
            #ratedProductsTable tbody tr:hover {
                background: #f6faff !important;
            }

            /* SEARCH & SELECT chung */
            .dataTables_filter input,
            .dataTables_length select {
                border-radius: 0;
                border: 1px solid #ececec;
                background: #fafdff;
                padding: 6px 14px;
                font-size: 15px;
                height: 38px;
                box-shadow: none;
            }

            /* Info và paginate nhỏ gọn */
            .dataTables_info,
            .dataTables_paginate {
                font-size: 14px;
                color: #747474;
                padding-top: 10px !important;
            }

            /* PHÂN TRANG vuông, đều, nhẹ */
            .dataTables_wrapper .dataTables_paginate {
                padding-top: 14px !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button {
                border-radius: 0 !important;
                border: none !important;
                background: #f5f7fa !important;
                margin: 0 1.5px;
                padding: 2px 10px;
                color: #444 !important;
                box-shadow: none !important;
                font-size: 14px;
                min-width: 24px;
                transition: background 0.12s;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.current,
            .dataTables_wrapper .dataTables_paginate .paginate_button:active {
                background: #eaf1ff !important;
                color: #2462e6 !important;
                font-weight: 700;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.current) {
                background: #eaeaea !important;
                color: #1e1e1e !important;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button:focus {
                outline: none !important;
                box-shadow: none !important;
            }

            .paginate_button.dots {
                color: #b6b6b6 !important;
                background: transparent !important;
                cursor: default !important;
                padding: 2px 6px;
                font-size: 14px;
            }

            .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
                color: #babec5 !important;
                background: #f5f7fa !important;
                cursor: default !important;
            }
        </style>
    @endpush
