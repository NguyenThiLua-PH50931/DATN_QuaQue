@extends('layouts.frontend')
@section('title', 'Đơn hàng của tôi')
@section('contents')

    <style>
        .orders-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: #2c3e50;
            letter-spacing: 2px;
            margin-bottom: 0;
            text-align: center;
        }

        .orders-subtitle {
            text-transform: uppercase;
            letter-spacing: 2px;
            color: #16a085;
            font-size: 0.9rem;
            text-align: center;
            margin-bottom: 2rem;
        }

        /* Table thead kiểu bo góc từng ô + cùng màu */
        .custom-table thead tr th {
            background: linear-gradient(135deg, #e6fafd 80%, #d1f4fa 100%);
            color: #223345;
            border: none !important;
            border-radius: 22px 22px 0 0;
            font-size: 1.09rem;
            font-weight: 700;
            text-align: left;
            padding-top: 20px;
            padding-bottom: 18px;
            padding-left: 18px;
            padding-right: 8px;
            box-shadow: 0 2px 0 0 #def2f6;
        }

        .custom-table thead tr th:not(:first-child) {
            margin-left: 12px;
        }

        .custom-table thead tr {
            border: none !important;
        }

        .custom-table {
            border-collapse: separate !important;
            border-spacing: 0 0;
        }

        .custom-table tbody tr {
            transition: background 0.18s;
        }

        .custom-table tbody tr:hover {
            background: #f8fafc;
            cursor: pointer;
        }

        .custom-badge {
            border-radius: 16px;
            padding: 5px 18px;
            font-size: 0.98rem;
            font-weight: 600;
            box-shadow: 0 2px 8px #0001;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }

        .badge-pending {
            background: #eee;
            color: #555;
        }

        .badge-confirmed {
            background: #e0f7fa;
            color: #0097a7;
        }

        .badge-processing {
            background: #fffbe6;
            color: #ffc107;
        }

        .badge-shipped,
        .badge-in_transit {
            background: #d2eafd;
            color: #1976d2;
        }

        .badge-delivered {
            background: #e8f5e9;
            color: #43a047;
        }

        .badge-cancelled {
            background: #ffebee;
            color: #e53935;
        }

        .badge-failed_delivery {
            background: #f3e5f5;
            color: #8e24aa;
        }

        .badge-cod {
            background: #eceff1;
            color: #37474f;
        }

        .badge-bank {
            background: #b2ebf2;
            color: #0097a7;
        }

        .orders-filter select,
        .orders-filter input[type="date"],
        .orders-filter input[type="text"] {
            min-width: 140px;
            border-radius: 10px;
            border: 1px solid #dde7ef;
        }

        .orders-filter input[type="text"]:focus,
        .orders-filter input[type="date"]:focus,
        .orders-filter select:focus {
            border-color: #16a085 !important;
            box-shadow: 0 0 0 1px #16a0852c;
        }

        .orders-filter .btn-filter {
            background: linear-gradient(90deg, #16a085, #0bc1e7);
            color: #fff;
            font-weight: bold;
            padding: 5px 26px;
            border-radius: 12px;
            border: none;
            transition: background 0.2s, box-shadow 0.2s;
            box-shadow: 0 4px 18px -8px #00bcd41a;
        }

        .orders-filter .btn-filter:hover {
            background: linear-gradient(90deg, #0bc1e7, #16a085);
            color: #fff;
        }

        .order-link {
            color: #059669 !important;
            font-weight: 600;
            text-decoration: underline dotted #87e3d8;
        }

        .table thead th,
        .table tbody td {
            vertical-align: middle;
        }

        /* Bo góc cho header từng ô (chỉ 2 đầu bảng) */
        .custom-table thead th:first-child {
            border-radius: 22px 0 0 0 !important;
        }

        .custom-table thead th:last-child {
            border-radius: 0 22px 0 0 !important;
        }

        .custom-table thead th {
            background: #e6fafd !important;
            /* cùng 1 màu xanh nhạt */
            color: #223345;
            border: none !important;
            border-radius: 0 !important;
            font-size: 1.09rem;
            font-weight: 700;
            text-align: left;
            padding-top: 20px;
            padding-bottom: 18px;
            padding-left: 18px;
            padding-right: 8px;
            /* Không box-shadow, không border dưới từng ô */
        }

        .custom-table thead tr {
            border: none !important;
        }

        .custom-table {
            border-collapse: separate !important;
            border-spacing: 0;
        }
    </style>

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Đơn hàng</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="/">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Đơn hàng</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="container">
        <div class="orders-title mb-1">Đơn Hàng Của Tôi</div>
        <div class="orders-subtitle mb-4">Kiểm tra và theo dõi các đơn hàng cá nhân</div>
        {{-- FILTER FORM --}}
        <form class="row gx-2 gy-2 align-items-center orders-filter mb-4 justify-content-center" method="GET"
            action="">
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">-- TT đơn hàng --</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang chuẩn bị
                    </option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>Đang vận chuyển
                    </option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                    <option value="failed_delivery" {{ request('status') == 'failed_delivery' ? 'selected' : '' }}>Giao thất
                        bại</option>
                </select>
            </div>
            <div class="col-auto">
                <select name="payment_method" class="form-select form-select-sm">
                    <option value="">-- PTTT --</option>
                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="bank" {{ request('payment_method') == 'bank' ? 'selected' : '' }}>Chuyển khoản</option>
                </select>
            </div>
            <div class="col-auto">
                <input type="date" name="date_from" class="form-control form-control-sm" placeholder="dd/mm/yyyy"
                    value="{{ request('date_from') }}">
            </div>
            <div class="col-auto">
                <input type="date" name="date_to" class="form-control form-control-sm" placeholder="dd/mm/yyyy"
                    value="{{ request('date_to') }}">
            </div>
            <div class="col-auto">
                <input type="text" name="keyword" class="form-control form-control-sm" placeholder="Mã đơn..."
                    value="{{ request('keyword') }}">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-filter btn-sm px-4 ms-1"><i class="fa fa-filter me-1"></i>
                    Lọc</button>
            </div>
        </form>

        {{-- TABLE ORDERS --}}
        <div class="table-responsive">
            <table class="table align-middle custom-table mb-0">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>PTTT</th>
                        <th>Tuỳ chọn</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($orders as $order)
                        <tr>
                            <td>
                                <a href="{{ route('client.orders.show', $order->id) }}" class="order-link">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td class="text-success fw-bold">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</td>
                            <td>
                                @php
                                    $statusMap = [
                                        'pending' => ['Chờ xác nhận', 'badge-pending', 'fa-hourglass-half'],
                                        'confirmed' => ['Đã xác nhận', 'badge-confirmed', 'fa-circle-check'],
                                        'processing' => ['Đang chuẩn bị', 'badge-processing', 'fa-bowl-food'],
                                        'shipped' => ['Đã gửi hàng', 'badge-shipped', 'fa-truck'],
                                        'in_transit' => ['Đang vận chuyển', 'badge-in_transit', 'fa-shipping-fast'],
                                        'delivered' => ['Đã giao', 'badge-delivered', 'fa-check'],
                                        'cancelled' => ['Đã huỷ', 'badge-cancelled', 'fa-xmark-circle'],
                                        'failed_delivery' => [
                                            'Giao thất bại',
                                            'badge-failed_delivery',
                                            'fa-triangle-exclamation',
                                        ],
                                    ];
                                    $st = $statusMap[$order->status] ?? [
                                        $order->status,
                                        'badge-pending',
                                        'fa-question',
                                    ];
                                @endphp
                                <span class="custom-badge {{ $st[1] }}">
                                    <i class="fa-solid {{ $st[2] }}"></i>
                                    {{ $st[0] }}
                                </span>
                            </td>
                            <td>
                                @if ($order->payment_method == 'cod')
                                    <span class="custom-badge badge-cod"><i class="fa-solid fa-money-bill"></i> COD</span>
                                @elseif($order->payment_method == 'bank')
                                    <span class="custom-badge badge-bank"><i class="fa-solid fa-building-columns"></i>
                                        Chuyển khoản</span>
                                @else
                                    <span class="custom-badge badge-pending">{{ $order->payment_method }}</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('client.orders.show', $order->id) }}" class="text-decoration-none mx-1"
                                    title="Xem chi tiết">
                                    <i class="fa fa-eye" style="color:#14b8a6; font-size:1.25rem"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted">Bạn chưa có đơn hàng nào.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-center mt-3">
                {{ $orders->links() }}
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setInterval(function() {
                const ids = @json($orders->pluck('id')->toArray());
                if (!ids.length) return;

                const params = new URLSearchParams();
                ids.forEach(id => params.append('ids[]', id));
                params.append('t', Date.now()); // tránh cache

                fetch('{{ route('client.orders.statusBulk') }}' + '?' + params.toString(), {
                        credentials: 'same-origin'
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (!data?.data) return;

                        let reloadNeeded = false;
                        const currentStatuses = @json($orders->mapWithKeys(fn($o) => [$o->id => $o->status])->toArray());

                        data.data.forEach(order => {
                            if (currentStatuses[order.id] && currentStatuses[order.id] !== order
                                .status) {
                                reloadNeeded = true;
                            }
                        });

                        if (reloadNeeded) location.reload();
                    })
                    .catch(err => console.error(err));
            }, 2000);
        });
    </script>


@endsection
