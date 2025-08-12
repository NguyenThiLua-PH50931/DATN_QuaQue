@extends('layouts.backend')
@section('title', 'Đơn hàng')
@section('content')
    <style>
        /* Container chứa bảng cho phép cuộn ngang nhẹ khi màn hình quá nhỏ */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        /* Bảng luôn rộng 100%, cố định layout cột */
        table {
            width: 100% !important;
            table-layout: fixed;
            border-collapse: collapse;
        }

        /* Cột tiêu đề và nội dung tự xuống dòng, tránh tràn ngang */
        th,
        td {
            white-space: normal !important;
            word-wrap: break-word;
            text-align: center;
            /* Bạn có thể đổi thành left nếu muốn */
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        /* Tiêu đề bảng cố định khi cuộn dọc */
        thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
            font-weight: bold;
        }

        /* Một số style cho hover trên dòng (tuỳ chọn) */
        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Style cho select trạng thái */
        .status-select {
            width: 100%;
            padding: 4px 6px;
            font-size: 0.9rem;
        }

        /* Style cho nút xóa, icon */
        button.border-0.bg-transparent {
            cursor: pointer;
        }

        /* Sửa lại ul cho tùy chọn */
        ul {
            list-style: none;
            padding-left: 0;
            margin: 0;
            /* display: flex; */
            gap: 10px;
            justify-content: center;
        }

        /* Style chung cho select trạng thái */
        .status-select {
            width: 100%;
            padding: 6px 35px 6px 10px;
            font-size: 0.9rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            cursor: pointer;
            box-sizing: border-box;

            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;

            /* Icon tam giác mềm hơn */
            background-image: url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2010%206'%3E%3Cpath%20fill='gray'%20d='M1%200l4%204%204-4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 10px 6px;
        }

        /* Hover và focus */
        .status-select:hover,
        .status-select:focus {
            border-color: #666;
            outline: none;
        }


        /* Hover và focus cho select */
        .status-select:hover,
        .status-select:focus {
            border-color: #666;
            outline: none;
        }

        /* Màu nền và chữ theo trạng thái */
        .status-pending {
            background-color: #fff3cd;
            color: #856404;
            border-color: #ffeeba;
        }

        .status-confirmed {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .status-processing {
            background-color: #cce5ff;
            color: #004085;
            border-color: #b8daff;
        }

        .status-shipped {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }

        .status-in_transit {
            background-color: #d1ecf1;
            color: #0c5460;
            border-color: #bee5eb;
        }

        .status-delivered {
            background-color: #c3e6cb;
            color: #155724;
            border-color: #8fd19e;
            font-weight: bold;
        }

        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }

        .status-failed_delivery {
            background-color: #f5c6cb;
            color: #721c24;
            border-color: #f1b0b7;
        }

        .badge {
            display: inline-block;
            max-width: 100px;
            padding: 6px 10px;
            font-size: 0.9rem;
            font-weight: 500;
            color: #fff;
            /* chữ trắng cho nổi bật */
            border-radius: 4px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            text-align: center;
            box-sizing: border-box;
            user-select: none;
        }

        /* Chưa thanh toán - màu cam */
        .bg-warning {
            background: linear-gradient(135deg, #ffa726, #fb8c00);
            /* gradient cam sáng -> cam đậm */
            border: 1px solid #f57c00;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(251, 140, 0, 0.4);
        }

        .bg-success {
            background: linear-gradient(135deg, #66bb6a, #388e3c);
            /* gradient xanh lá tươi sáng */
            border: 1px solid #2e7d32;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(56, 142, 60, 0.4);
        }

        .bg-danger {
            background: linear-gradient(135deg, #ef5350, #c62828);
            /* gradient đỏ sáng -> đỏ đậm */
            border: 1px solid #b71c1c;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(198, 40, 40, 0.4);
        }

        .bg-secondary {
            background: linear-gradient(135deg, #9e9e9e, #616161);
            /* gradient xám */
            border: 1px solid #424242;
            color: #fff !important;
            box-shadow: 0 2px 6px rgba(97, 97, 97, 0.4);
        }

        .filter-container form select.form-select-sm,
        .filter-container form input.form-control-sm {
            min-width: 180px;
            /* tăng lên 180px hoặc theo ý bạn */
            max-width: 220px;
            /* tùy chỉnh tối đa */
        }

        .filter-container form input[type="date"] {
            min-width: 160px;
            max-width: 180px;
        }

        .filter-container form input[type="text"] {
            min-width: 220px;
            max-width: 260px;
        }

        .filter-container form button {
            min-width: 90px;
        }

        .payment-status-label {
            display: inline-block;
            max-width: 120px;
            padding: 6px 10px;
            font-size: 0.9rem;
            font-weight: 600;
            border-radius: 4px;
            text-align: center;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            box-sizing: border-box;
            user-select: none;
            background-color: transparent !important;
            /* giữ nền trắng */
            border: none !important;
            /* bỏ border */
            box-shadow: none !important;
            /* bỏ shadow */
        }

        .payment-unpaid {
            color: #d35400;
            /* cam đậm */
        }

        .payment-paid {
            color: #2e7d32;
            /* xanh lá đậm */
        }

        /* Với select: giữ nền trắng, đổi màu chữ khi đã thanh toán */
        .payment-status-select {
            width: 100%;
            padding: 6px 10px;
            font-size: 0.9rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            cursor: pointer;
            box-sizing: border-box;
            background-color: #fff;
            color: #856404;
            /* màu chữ cam mặc định (chưa thanh toán) */
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            transition: color 0.3s ease;
        }

        /* Khi trạng thái đã thanh toán đổi màu chữ xanh */
        .payment-status-select.payment-paid-bank {
            color: #2e7d32 !important;
            /* xanh lá đậm */
        }

        .payment-paid-cod-delivered {
            font-size: 0.85rem !important;
            /* cỡ chữ nhỏ */
            font-weight: normal !important;
            /* bỏ in đậm */
        }
    </style>
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <!-- Table Start -->
                        <div class="title-header option-title">
                            <h5>Danh sách đơn hàng</h5>
                        </div>
                        <div class="filter-container mb-3">
                            <form method="GET" action="{{ route('admin.orders.index') }}"
                                class="d-flex flex-wrap align-items-center gap-2 mt-2">
                                <select name="status" class="form-select form-select-sm" style="width: 160px;">
                                    <option value="">-- TT đơn hàng --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác
                                        nhận</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã
                                        xác nhận</option>
                                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>
                                        Đang chuẩn bị</option>
                                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đã gửi
                                        hàng</option>
                                    <option value="in_transit" {{ request('status') == 'in_transit' ? 'selected' : '' }}>
                                        Đang vận chuyển</option>
                                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã
                                        giao hàng</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã
                                        hủy</option>
                                    <option value="failed_delivery"
                                        {{ request('status') == 'failed_delivery' ? 'selected' : '' }}>Giao thất bại
                                    </option>
                                </select>

                                <select name="payment_status" class="form-select form-select-sm" style="width: 140px;">
                                    <option value="">-- TT thanh toán --</option>
                                    <option value="unpaid" {{ request('payment_status') == 'unpaid' ? 'selected' : '' }}>
                                        Chưa thanh toán</option>
                                    <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>
                                        Đã thanh toán</option>
                                    <option value="refunded"
                                        {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền
                                    </option>
                                    <option value="failed" {{ request('payment_status') == 'failed' ? 'selected' : '' }}>
                                        Thanh toán thất bại</option>
                                </select>


                                <select name="payment_method" class="form-select form-select-sm" style="width: 140px;">
                                    <option value="">-- Phương thức TT --</option>
                                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD
                                    </option>
                                    <option value="momo" {{ request('payment_method') == 'momo' ? 'selected' : '' }}>Ví
                                        MoMo</option>
                                    <option value="zalopay" {{ request('payment_method') == 'zalopay' ? 'selected' : '' }}>
                                        ZaloPay</option>
                                </select>


                                {{-- <select name="bank_transfer_confirmed" class="form-select form-select-sm" style="width: 150px;">
                                <option value="">-- XN chuyển khoản --</option>
                                <option value="1" {{ request('bank_transfer_confirmed') === '1' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="0" {{ request('bank_transfer_confirmed') === '0' ? 'selected' : '' }}>Chưa xác nhận</option>
                            </select> --}}


                                <input type="date" name="date_from" class="form-control form-control-sm"
                                    style="width: 130px;" value="{{ request('date_from') }}" placeholder="Từ ngày">

                                <input type="date" name="date_to" class="form-control form-control-sm"
                                    style="width: 130px;" value="{{ request('date_to') }}" placeholder="Đến ngày">


                                <input type="text" name="keyword" class="form-control form-control-sm"
                                    style="width: 160px;" value="{{ request('keyword') }}"
                                    placeholder="Mã đơn / Người đặt">



                                <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
                            </form>
                        </div>
                        <div>
                            <div class="table-responsive">
                                <table class="table all-package order-table theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Người đặt</th>
                                            <th>Ngày đặt</th>
                                            <th>MĐH</th>
                                            <th>Số tiền</th>
                                            <th>PTTT</th>
                                            <th>TTTT</th>
                                            <th>TT đơn hàng</th>
                                            <th>Tuỳ chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($orders as $order)
                                            @php
                                                $computedTotal =
                                                    $order->items->sum(function ($item) {
                                                        return ($item->price - $item->discount) * $item->quantity;
                                                    }) +
                                                    ($order->shipping_cost ?? 0);

                                                $paymentStatusText = match ($order->payment_status) {
                                                    'unpaid' => 'Chưa thanh toán',
                                                    'paid' => 'Đã thanh toán',
                                                    'failed' => 'Thanh toán thất bại',
                                                    default => 'N/A',
                                                };
                                            @endphp

                                            <tr data-bs-toggle="offcanvas" href="#order-details">
                                                <td>{{ $order->user->name ?? $order->user_id }}</td>
                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                <td class="text-truncate" style="max-width: 130px;">
                                                    <span class="d-inline-block text-truncate" style="max-width: 100%;"
                                                        title="{{ $order->order_code }}">
                                                        {{ $order->order_code }}
                                                    </span>
                                                </td>
                                                {{-- 👉 Số tiền lên trước --}}
                                                <td>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                                {{-- Xác nhận chuyển khoản --}}


                                                {{-- PTTT --}}
                                                <td>{{ $order->payment_method ?? 'N/A' }}</td>

                                                {{-- TTTT (trạng thái thanh toán) --}}
                                                <td>
                                                    @php
                                                        // Mặc định
                                                        $payText = 'Chưa thanh toán';
                                                        $payClass = 'payment-status-label payment-unpaid';

                                                        // Ưu tiên trạng thái "đã hoàn tiền"
                                                        if ($order->payment_status === 'refunded') {
                                                            $payText = 'Đã hoàn tiền';
                                                            $payClass = 'payment-status-label payment-refunded';
                                                        }
                                                        // Đã thanh toán
                                                        elseif (
                                                            $order->payment_status === 'paid' ||
                                                            ($order->payment_method === 'cod' &&
                                                                $order->status === 'delivered')
                                                        ) {
                                                            // Nếu có theo dõi hoàn tiền
                                                            if (($order->refund_status ?? null) === 'pending') {
                                                                $payText = 'Đã hoàn tiền';
                                                                $payClass =
                                                                    'payment-status-label payment-refund-pending';
                                                            } elseif (($order->refund_status ?? null) === 'success') {
                                                                $payText = 'Đã hoàn tiền';
                                                                $payClass = 'payment-status-label payment-refunded';
                                                            } elseif (($order->refund_status ?? null) === 'failed') {
                                                                $payText = 'Đã thanh toán (Hoàn tiền thất bại)';
                                                                $payClass = 'payment-status-label payment-paid';
                                                            } else {
                                                                $payText = 'Đã thanh toán';
                                                                $payClass = 'payment-status-label payment-paid';
                                                            }
                                                        }
                                                        // Thanh toán thất bại
                                                        elseif ($order->payment_status === 'failed') {
                                                            $payText = 'Thanh toán thất bại';
                                                            $payClass = 'payment-status-label payment-failed';
                                                        }

                                                        // Riêng BANK: giữ dropdown như cũ
                                                        $paymentSelectClass = 'payment-status-select';
                                                        if (
                                                            $order->payment_method === 'bank' &&
                                                            $order->payment_status === 'paid'
                                                        ) {
                                                            $paymentSelectClass .= ' payment-paid-bank';
                                                        }
                                                    @endphp

                                                    @if ($order->payment_method === 'bank')
                                                        <select class="form-select {{ $paymentSelectClass }}"
                                                            data-order-id="{{ $order->id }}">
                                                            <option value="unpaid" title="Chưa thanh toán"
                                                                {{ $order->payment_status === 'unpaid' ? 'selected' : '' }}>
                                                                Chưa thanh...</option>
                                                            <option value="paid" title="Đã thanh toán"
                                                                {{ $order->payment_status === 'paid' ? 'selected' : '' }}>
                                                                Đã thanh...</option>
                                                        </select>
                                                    @else
                                                        <span id="payment-status-{{ $order->id }}"
                                                            class="{{ $payClass }}"
                                                            data-payment-method="{{ $order->payment_method }}">
                                                            {{ $payText }}
                                                        </span>
                                                    @endif
                                                </td>

                                                {{-- Trạng thái đơn hàng --}}
                                                <td>
                                                    <form id="status-form-{{ $order->id }}"
                                                        action="{{ route('admin.orders.updateStatus', $order->id) }}"
                                                        method="POST" class="status-form">
                                                        @csrf
                                                        @method('PUT')
                                                        <select name="status"
                                                            class="form-select status-select status-{{ $order->status }}"
                                                            data-order-id="{{ $order->id }}"
                                                            data-current-status="{{ $order->status }}">
                                                            <option value="pending"
                                                                {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác
                                                                nhận</option>
                                                            <option value="confirmed"
                                                                {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã
                                                                xác nhận</option>
                                                            <option value="processing"
                                                                {{ $order->status == 'processing' ? 'selected' : '' }}>Đang
                                                                chuẩn bị</option>
                                                            <option value="shipped"
                                                                {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã gửi
                                                                hàng</option>
                                                            <option value="in_transit"
                                                                {{ $order->status == 'in_transit' ? 'selected' : '' }}>Đang
                                                                vận chuyển</option>
                                                            <option value="delivered"
                                                                {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã
                                                                giao hàng</option>
                                                            <option value="cancelled"
                                                                {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã
                                                                hủy</option>

                                                            <option value="failed_delivery"
                                                                {{ $order->status == 'failed_delivery' ? 'selected' : '' }}>
                                                                Giao thất bại</option>
                                                        </select>
                                                    </form>
                                                </td>
                                                {{-- Tuỳ chọn --}}
                                                <td>
                                                    <ul class="d-flex align-items-center gap-2">
                                                        <li>
                                                            <a href="{{ route('admin.orders.show', $order->id) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.orders.tracking', $order->id) }}">
                                                                <i class="ri-map-pin-line"></i>
                                                            </a>
                                                        </li>

                                                        @if (in_array($order->status, ['pending', 'confirmed', 'processing'], true))
                                                            <li>
                                                                <a href="javascript:void(0);" class="btn-admin-cancel"
                                                                    data-order-id="{{ $order->id }}"
                                                                    data-order-code="{{ $order->order_code }}"
                                                                    data-bs-toggle="modal"
                                                                    data-bs-target="#adminCancelModal" title="Hủy đơn">
                                                                    <i class="ri-close-circle-line"></i>
                                                                </a>
                                                            </li>
                                                        @endif


                                                    </ul>
                                                </td>


                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                {{-- Phân trang --}}
                                <div class="mt-3">
                                    {{ $orders->withQueryString()->links() }}
                                </div>
                            </div>
                        </div>


                        <!-- Table End -->

                        <!-- Pagination Box Start -->
                        {{-- <div class="pagination-box">
                        <nav class="ms-auto me-auto" aria-label="...">
                            <ul class="pagination pagination-primary">
                      
                                @if ($orders->onFirstPage())
                                    <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Previous</a></li>
                                @else
                                    <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">Previous</a></li>
                                @endif

                                @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                                    <li class="page-item {{ $orders->currentPage() == $page ? 'active' : '' }}">
                                        <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                                    </li>
                                @endforeach

                                @if ($orders->hasMorePages())
                                    <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a></li>
                                @else
                                    <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>
                                @endif
                            </ul>
                        </nav>
                    </div> --}}
                        <!-- Pagination Box End -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
        @includeIf('backend.footer')
    </div>
    <script>
        let lastOrderId = @json($lastOrderId);
        setInterval(function() {
            fetch('/admin/orders/latest-id')
                .then(res => res.json())
                .then(data => {
                    if (data.latest_id && data.latest_id > lastOrderId) {
                        location.reload();
                    }
                });
        }, 1000); // nên để 3 giây để giảm tải server
    </script>

@endsection
