@extends('layouts.backend')

@section('title', 'Chi tiết đơn hàng')

@section('content')
    <style>
        /* Table và layout */
        .table-responsive {
            overflow-x: auto;
            max-width: 100%;
        }

        table {
            width: 100% !important;
            table-layout: fixed;
            border-collapse: collapse;
        }

        th,
        td {
            white-space: normal !important;
            word-wrap: break-word;
            text-align: center;
            padding: 8px 10px;
            border: 1px solid #ddd;
        }

        thead th {
            position: sticky;
            top: 0;
            background: #fff;
            z-index: 10;
            font-weight: bold;
        }

        tbody tr:hover {
            background-color: #f1f1f1;
            cursor: pointer;
        }

        /* Dropdown trạng thái */
        .status-select {
            width: 100%;
            max-width: 220px;
            padding: 6px 35px 6px 10px;
            font-size: 0.93rem;
            border: 1px solid #ccc;
            border-radius: 4px;
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20viewBox='0%200%2010%206'%3E%3Cpath%20fill='gray'%20d='M1%200l4%204%204-4z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 12px center;
            background-size: 10px 6px;
        }

        /* Badge màu trạng thái */
        .badge {
            display: inline-block;
            padding: 6px 12px;
            font-size: 1rem;
            border-radius: 6px;
            margin-top: 8px;
            font-weight: 500;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffeeba;
        }

        .status-confirmed {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-processing {
            background: #cce5ff;
            color: #004085;
            border: 1px solid #b8daff;
        }

        .status-shipped {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .status-in_transit {
            background: #d1ecf1;
            color: #0c5460;
            border: 1px solid #bee5eb;
        }

        .status-delivered {
            background: #c3e6cb;
            color: #155724;
            border: 1px solid #8fd19e;
            font-weight: bold;
        }

        .status-cancelled {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .status-failed_delivery {
            background: #f5c6cb;
            color: #721c24;
            border: 1px solid #f1b0b7;
        }

        /* Chỉnh khối phải */
        .order-info-card {
            background: #e6f4f1;
            padding: 1.3rem 1.1rem;
            border-radius: 10px;
            color: #198754;
        }

        .order-info-card h5 {
            font-weight: bold;
            margin-bottom: 1rem;
        }

        .order-info-card ul {
            list-style: none;
            padding: 0;
            margin: 0;
            font-size: 1rem;
        }

        .order-info-card li {
            margin-bottom: 0.55rem;
        }

        .order-info-card .label {
            min-width: 130px;
            display: inline-block;
            color: #888;
        }

        /* Nút */
        .btn-outline-success {
            margin-right: .4rem;
        }

        /* Responsive mobile */
        @media(max-width: 767px) {
            .order-info-card {
                margin-top: 1rem;
            }
        }

        .status-select {
            width: 100%;
            max-width: 260px;
            padding: 10px 36px 10px 16px;
            font-size: 1.05rem;
            font-weight: 500;
            border-radius: 16px;
            border: 1.5px solid #b8e5e1;
            background: #e8f7f5;
            color: #18534f;
            transition: border 0.2s, box-shadow 0.2s;
            box-shadow: 0 2px 10px 0 rgba(140, 230, 224, 0.08);
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg width='13' height='8' viewBox='0 0 13 8' fill='none' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M1 1L6.5 7L12 1' stroke='%234bbfa2' stroke-width='2'/%3E%3C/svg%3E%0A");
            background-repeat: no-repeat;
            background-position: right 14px center;
            background-size: 22px 13px;
        }

        .status-select:focus {
            outline: none;
            border-color: #4bbfa2;
            background: #dbf1ee;
            box-shadow: 0 2px 14px 0 rgba(74, 191, 162, 0.10);
        }

        /* Badge */
        #order-status-badge {
            display: inline-block;
            min-width: 140px;
            padding: 9px 24px;
            margin-top: 10px;
            margin-left: 0;
            font-size: 1.08rem;
            font-weight: 700;
            border-radius: 18px;
            background: #d2ebfd;
            color: #18534f;
            letter-spacing: 0.05em;
            border: none;
            box-shadow: 0 2px 8px 0 rgba(71, 158, 188, 0.06);
            user-select: none;
        }

        /* Badge theo trạng thái - dùng màu pastel */
        .status-pending {
            background: #fffbe0 !important;
            color: #b49c2e !important;
        }

        .status-confirmed {
            background: #e8f7f5 !important;
            color: #118a7e !important;
        }

        .status-processing {
            background: #e6f0fd !important;
            color: #3178c6 !important;
        }

        .status-shipped {
            background: #e7fbe7 !important;
            color: #2c8336 !important;
        }

        .status-in_transit {
            background: #e8f3fd !important;
            color: #41759c !important;
        }

        .status-delivered {
            background: #e9faef !important;
            color: #178d42 !important;
        }

        .status-cancelled,
        .status-failed_delivery {
            background: #fbecec !important;
            color: #db4444 !important;
        }
    </style>

    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex flex-wrap justify-content-between align-items-center mb-4">
                                <div class="title-header option-title">
                                    <h5>Đơn hàng #{{ $order->order_code }}</h5>
                                </div>
                                <ul class="list-inline mb-0">
                                    <li class="list-inline-item">{{ $order->created_at->format('d/m/Y H:i') }}</li>
                                    <li class="list-inline-item">|</li>
                                    <li class="list-inline-item">{{ $order->items->sum('quantity') }} sản phẩm</li>
                                </ul>
                            </div>

                            <div class="row g-4">
                                <div class="col-lg-8">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead class="table-light">
                                                <tr>
                                                    <th colspan="2" class="text-center">Sản phẩm</th>
                                                    <th class="text-center">SL</th>
                                                    <th class="text-end">Đơn giá</th>
                                                    <th class="text-end">Tạm tính</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $subtotal = $order->items->sum(
                                                        fn($item) => $item->price * $item->quantity,
                                                    );
                                                    $itemCount = count($order->items);
                                                @endphp
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <td class="text-center align-middle">
                                                            <img src="{{ asset('storage/' . $item->product_image) }}"
                                                                class="img-thumbnail"
                                                                style="width:75px; height:75px; object-fit:cover;"
                                                                alt="{{ $item->product_name }}">
                                                        </td>
                                                        <td class="align-middle">
                                                            <div class="fw-semibold">{{ $item->product_name }}</div>
                                                            @if ($item->productVariant)
                                                                <div class="text-muted small">Loại:
                                                                    {{ $item->productVariant->name }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center align-middle">{{ $item->quantity }}</td>
                                                        <td class="text-end align-middle">
                                                            {{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                                        @if ($loop->first)
                                                            <td class="text-end fw-bold align-middle"
                                                                rowspan="{{ $itemCount }}">
                                                                {{ number_format($subtotal, 0, ',', '.') }} VNĐ
                                                            </td>
                                                        @endif
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                            <tfoot>
                                                @php
                                                    $discount_total = $order->items->sum(
                                                        fn($item) => $item->discount * $item->quantity,
                                                    );
                                                    $shipping = $order->shipping_cost ?? 0;
                                                    $voucher = $order->discount_amount ?? 0;
                                                    $final_total = $subtotal + $shipping - $discount_total - $voucher;
                                                @endphp
                                                @if ($discount_total > 0)
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold">Giảm giá sản phẩm:</td>
                                                        <td class="text-end text-danger">
                                                            -{{ number_format($discount_total, 0, ',', '.') }} VNĐ</td>
                                                    </tr>
                                                @endif
                                                @if ($voucher > 0)
                                                    <tr>
                                                        <td colspan="4" class="text-end fw-bold">
                                                            Mã giảm giá
                                                            @if ($order->discountCode)
                                                                ({{ $order->discountCode->code }})
                                                            @endif:
                                                        </td>
                                                        <td class="text-end text-danger">
                                                            -{{ number_format($voucher, 0, ',', '.') }} VNĐ</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold">Phí vận chuyển:</td>
                                                    <td class="text-end">{{ number_format($shipping, 0, ',', '.') }} VNĐ
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4" class="text-end fw-bold text-success">Tổng cộng:</td>
                                                    <td class="text-end fw-bold text-success">
                                                        {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <!-- KHỐI PHẢI -->
                                <div class="col-lg-4">
                                    <div class="order-info-card">
                                        <h5 class="fw-bold mb-3">Thông tin đơn hàng</h5>
                                        <ul>
                                            <li><span class="label">Mã ĐH:</span>
                                                <strong>{{ $order->order_code }}</strong>
                                            </li>
                                            <li><span class="label">Ngày đặt:</span>
                                                {{ $order->created_at->format('d/m/Y') }}</li>
                                            <li><span class="label">Thành tiền:</span>
                                                <strong>{{ number_format($order->total_amount, 0, ',', '.') }} VNĐ</strong>
                                            </li>
                                        </ul>
                                        <h5 class="fw-bold mt-4 mb-3">Địa chỉ nhận hàng</h5>
                                        <ul>
                                            <li><span class="label">Họ tên:</span>
                                                {{ $order->recipient_name ?? ($order->address->recipient_name ?? 'N/A') }}
                                            </li>
                                            <li><span class="label">Địa chỉ:</span>
                                                {{ $order->full_address ?? ($order->address->address ?? '') }}</li>
                                            <li><span class="label">SĐT:</span>
                                                {{ $order->phone ?? ($order->address->phone ?? '') }}</li>
                                        </ul>
                                        <h5 class="fw-bold mt-4 mb-2">PT Thanh toán</h5>
                                        <p>
                                            @php
                                                $method = match ($order->payment_method) {
                                                    'cod' => 'Thanh toán khi nhận hàng (COD)',
                                                    'bank' => 'Chuyển khoản ngân hàng',
                                                    'momo' => 'Ví MoMo',
                                                    default => 'Không xác định',
                                                };
                                            @endphp
                                            {{ $method }}
                                        </p>
                                        <h5 class="fw-bold mt-4 mb-2">Trạng thái thanh toán</h5>
                                        <p
                                            class="fw-semibold {{ $order->payment_status == 'paid' ? 'text-success' : 'text-danger' }}">
                                            {{ $order->payment_status == 'paid' ? 'Đã thanh toán' : 'Chưa thanh toán' }}
                                        </p>

                                        {{-- TRẠNG THÁI ĐƠN HÀNG: DROPDOWN & BADGE --}}
                                        <h5 class="fw-bold mt-4 mb-2">Trạng thái đơn hàng</h5>
                                        <form id="update-status-form">
                                            <select name="status" id="order-status-select"
                                                class="form-select status-select mb-2" data-order-id="{{ $order->id }}">
                                                <option value="pending" @selected($order->status == 'pending')>Chờ xác nhận</option>
                                                <option value="confirmed" @selected($order->status == 'confirmed')>Đã xác nhận</option>
                                                <option value="processing" @selected($order->status == 'processing')>Đang chuẩn bị
                                                </option>
                                                <option value="shipped" @selected($order->status == 'shipped')>Đã gửi hàng</option>
                                                <option value="in_transit" @selected($order->status == 'in_transit')>Đang vận chuyển
                                                </option>
                                                <option value="delivered" @selected($order->status == 'delivered')>Đã giao hàng</option>
                                                <option value="cancelled" @selected($order->status == 'cancelled')>Đã hủy</option>
                                                <option value="failed_delivery" @selected($order->status == 'failed_delivery')>Giao thất bại
                                                </option>
                                            </select>
                                        </form>
                                        <span id="order-status-badge" class="badge status-{{ $order->status }}">
                                            {{ [
                                                'pending' => 'Chờ xác nhận',
                                                'confirmed' => 'Đã xác nhận',
                                                'processing' => 'Đang chuẩn bị',
                                                'shipped' => 'Đã gửi hàng',
                                                'in_transit' => 'Đang vận chuyển',
                                                'delivered' => 'Đã giao hàng',
                                                'cancelled' => 'Đã hủy',
                                                'failed_delivery' => 'Giao thất bại',
                                            ][$order->status] ?? $order->status }}
                                        </span>
                                        <div class="mt-4">
                                            <h5 class="fw-bold">Ngày giao dự kiến:</h5>
                                            <p class="mb-1 fw-semibold" style="color: #008c7e;">
                                                {{ $order->created_at->addDays(3)->format('d/m/Y') }}
                                            </p>
                                            <div class="btn-group" role="group">
                                                <a href="{{ route('admin.orders.index') }}"
                                                    class="btn btn-outline-success btn-sm">Quay lại</a>
                                                <a href="{{ route('admin.orders.tracking', $order->id) }}"
                                                    class="btn btn-outline-success btn-sm">Nhật ký đơn hàng</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- end col -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @includeIf('backend.footer')
        </div>
    </div>

    {{-- AJAX cập nhật trạng thái --}}

@endsection
