@extends('layouts.frontend')
@section('title', 'Giỏ hàng')
@section('contents')

    <style>
        /* Modal content */
        .modal-content {
            border-radius: 12px;
            border: 1px solid #ddd;
            box-shadow: none;
        }

        /* Modal header */
        .modal-header {
            border-bottom: none;
            padding-bottom: 0;
        }

        .modal-title {
            color: #333;
            font-weight: 600;
            font-size: 1.1rem;
        }

        /* Close button */
        .btn-close {
            filter: none;
        }

        /* Modal body */
        .modal-body {
            padding-top: 0.75rem;
            padding-bottom: 1rem;
        }

        /* Nhóm biến thể theo nhóm (ví dụ Phân loại hàng) */
        .variant-group-label {
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #c6c6c6;
            font-size: 0.95rem;
        }

        .variant-btn-group {
            display: flex;
            flex-direction: column;
            /* Ép các nút xếp theo cột */
            gap: 10px;
            margin-bottom: 1rem;
        }

        .variant-btn {
            width: 100% !important;
            /* Chiếm hết chiều rộng */
            max-width: 100% !important;
            box-sizing: border-box;
            background-color: white;
            color: #616161;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-weight: 400;
            font-size: 0.9rem;
            padding: 8px 14px;
            cursor: pointer;
            text-align: center;
            transition: all 0.2s ease;
            user-select: none;
        }


        /* Khi active */
        .variant-btn.active {
            background-color: #0da487;
            color: rgb(195, 193, 193);
            border-color: #0da487;
            box-shadow: none;
        }

        /* Khi active */
        .variant-btn.active {
            background-color: #f1a661;
            color: white;
            border-color: #f1a661;
        }

        /* Footer modal */
        .modal-footer {
            border-top: none;
            padding-top: 1rem;
            justify-content: flex-end;
            gap: 12px;
        }

        /* Nút Trở lại */
        .modal-footer .btn-secondary {
            background-color: transparent;
            border: none;
            color: #060606;
            font-weight: 600;
            padding: 6px 20px;
            border-radius: 4px;
        }

        /* .modal-footer .btn-secondary:hover {
                            color: #fdfefe;
                        } */

        /* Nút Xác nhận */
        .modal-footer .btn-primary {
            background-color: #f1a661;
            border: none;
            font-weight: 700;
            padding: 6px 20px;
            border-radius: 4px;
            color: white
        }

        .open-variant-modal {
            position: relative;
            background: transparent !important;
            color: #212529;
            border: none !important;
            box-shadow: none !important;
            padding-right: 20px;
            font-weight: normal;
            cursor: pointer;
            font-size: 0.9rem;
            /* giảm size */
            max-width: 160px;
            /* giới hạn chiều rộng */
            white-space: nowrap;
            /* không xuống dòng */
            overflow: hidden;
            /* ẩn phần chữ tràn */
            text-overflow: ellipsis;
            /* hiển thị ... nếu chữ dài */
            line-height: 1.5;
            display: inline-block;
            /* đảm bảo tính khối để giới hạn chiều rộng */
            vertical-align: middle;
        }

        /* Đồng thời kiểm tra phần cột chứa nút, ví dụ: */
        td:nth-child(3) {
            max-width: 180px;
            /* hoặc theo chiều rộng bạn muốn */
            white-space: nowrap;
            /* không cho cột xuống dòng */
            overflow: hidden;
        }

        .open-variant-modal:hover {
            background-color: transparent !important;
            color: #212529 !important;
            border: none !important;
            box-shadow: none !important;
            text-decoration: none !important;
            cursor: default !important;
        }

        .open-variant-modal::after {
            content: "";
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border-left: 5px solid transparent;
            border-right: 5px solid transparent;
            border-top: 5px solid currentColor;
            /* Mũi tên màu giống chữ */
            pointer-events: none;
        }

        .col-lg-9>.bg-white {
            background-color: #f8f8f8 !important;
        }

        .col-lg-3>.bg-white {
            background-color: #f8f8f8 !important;
        }

        .variant-btn:hover {
            background-color: #f8f8f8 !important;
            color: inherit !important;
        }
    </style>
    <style>
        /* Các dòng viền trong bảng mờ đi */
        table.table th,
        table.table td {
            border-color: rgba(0, 0, 0, 0.1) !important;
        }

        table.table thead tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.1) !important;
        }

        table.table tbody tr {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05) !important;
        }

        /* Giữ nguyên các background mờ bạn đã đặt */
        .col-lg-9>.bg-white {
            background-color: #f8f8f8 !important;
        }

        .col-lg-3>.bg-white {
            background-color: #f8f8f8 !important;
        }
    </style>
    <style>
        .btn-no-border {
            background-color: #0da487;
            color: white;
            padding: 8px 16px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.3s ease;
            font-weight: 400;
        }

        .btn-no-border:hover {
            background-color: #07a37f;
        }
    </style>
    <style>
        .btn-custom {
            background: transparent;
            color: #0da487;
            border: 2px solid #0da487;
            border-radius: 6px;
            padding: 6px 12px;
            font-weight: normal;
            font-size: 14px;
            transition: all 0.3s ease;
        }

        .btn-custom:hover {
            background: linear-gradient(90deg, #0da487 0%, #07a37f 100%);
            color: white;
            border: none;
            opacity: 1;
        }

        .breadscrumb-contain {
            border-bottom: 1px solid #e6e6e6;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .breadscrumb-contain h2 {
            font-weight: 700;
            font-size: 1.5rem;
            color: #212529;
        }

        .breadcrumb {
            background-color: transparent;
            padding: 0;
            margin: 0;
            font-size: 0.95rem;
        }

        .breadcrumb-item+.breadcrumb-item::before {
            content: ">";
            padding: 0 0.5rem;
            color: #6c757d;
        }

        .breadcrumb-item a {
            color: #212529;
            text-decoration: none;
            transition: color 0.3s ease;
        }

        .breadcrumb-item a:hover {
            color: #0da487;
        }

        .breadcrumb-item.active {
            color: #6c757d;
        }

        .col-lg-3>.bg-white {
            padding: 1rem !important;
            /* giảm padding (mặc định p-4 = 1.5rem) */
            font-size: 0.9rem;
            /* giảm font-size chung */
        }

        .col-lg-3 h4 {
            margin-bottom: 1rem !important;
            /* giảm khoảng cách dưới tiêu đề */
            font-size: 1.25rem !important;
            /* nhỏ hơn tiêu đề */
        }

        .col-lg-3 form.mb-4 {
            gap: 0.5rem !important;
            /* giảm khoảng cách giữa input và nút */
        }

        .col-lg-3 form input.form-control {
            padding: 0.25rem 0.5rem !important;
            /* giảm padding input */
            font-size: 0.9rem !important;
        }

        .col-lg-3 ul.list-unstyled li {
            margin-bottom: 0.5rem !important;
            /* giảm khoảng cách giữa các dòng */
            font-size: 0.9rem !important;
        }

        .col-lg-3 .d-flex.border-top {
            padding-top: 0.75rem !important;
            /* giảm padding trên */
            font-size: 1.1rem !important;
            margin-bottom: 1rem !important;
        }

        .col-lg-3 a.btn {
            padding: 0.4rem 0 !important;
            /* giảm chiều cao nút */
            font-size: 0.9rem !important;
        }

        .col-lg-3 a.btn i {
            font-size: 0.85rem !important;
            /* icon nhỏ hơn */
        }

        /* Khối alert lớn */
        .pending-warning {
            background: linear-gradient(90deg, #fffbe6 0%, #fff7ea 100%);
            border: 3px solid #ffe7b2;
            border-radius: 18px;
            box-shadow: 0 4px 18px 0 rgba(253, 195, 44, 0.08);
            padding: 32px 32px 24px 32px;
            position: relative;
            margin-bottom: 36px;
            max-width: 820px;
            margin-left: auto;
            margin-right: auto;
            font-size: 1.13em;
            animation: bgflash 2.2s infinite alternate;
        }

        @keyframes bgflash {
            0% {
                box-shadow: 0 4px 22px 0 rgba(253, 195, 44, 0.08);
            }

            50% {
                box-shadow: 0 8px 30px 0 rgba(253, 195, 44, 0.15);
            }

            100% {
                box-shadow: 0 2px 16px 0 rgba(253, 195, 44, 0.05);
            }
        }

        .pending-warning-content {
            display: flex;
            gap: 22px;
            align-items: flex-start;
            margin-bottom: 18px;
        }

        .pending-warning .emoji {
            font-size: 2.4em;
            line-height: 1;
            animation: tada 2s infinite;
        }

        @keyframes tada {
            0% {
                transform: rotate(-10deg);
            }

            25% {
                transform: rotate(7deg);
            }

            50% {
                transform: rotate(-8deg);
            }

            75% {
                transform: rotate(4deg);
            }

            100% {
                transform: rotate(0);
            }
        }

        .pending-warning strong {
            color: #ed145b;
            font-size: 1.15em;
        }

        .pending-warning .text-primary {
            color: #1976d2 !important;
            font-weight: bold;
        }

        .pending-warning .highlight-momo {
            color: #a50064;
            font-weight: bold;
            letter-spacing: 0.04em;
            background: linear-gradient(90deg, #ffc9e3 0%, #fff0f6 100%);
            border-radius: 7px;
            padding: 2px 8px;
            margin: 0 2px;
            display: inline-block;
        }

        /* Nút bấm */
        .pending-warning-buttons {
            display: flex;
            gap: 18px;
            margin-bottom: 10px;
            margin-top: 4px;
        }

        .btn-outline-info.animated-pulse {
            border-radius: 12px;
            border-width: 2px;
            font-weight: bold;
            letter-spacing: 0.03em;
            animation: pulseBtn 1.2s infinite;
            transition: box-shadow 0.18s, background 0.14s;
        }

        @keyframes pulseBtn {
            0% {
                box-shadow: 0 0 0 0 #9eefff55;
            }

            70% {
                box-shadow: 0 0 0 8px #c2e9fb00;
            }

            100% {
                box-shadow: 0 0 0 0 #e3fcff22;
            }
        }

        .btn-danger.animated-bounce {
            background: linear-gradient(92deg, #ff87a8, #ffb366, #f47721);
            border: none;
            color: #fff;
            box-shadow: 0 4px 18px 0 rgba(255, 174, 120, 0.18);
            border-radius: 14px;
            font-weight: bold;
            font-size: 1.16em;
            padding: 10px 32px;
            letter-spacing: 0.02em;
            position: relative;
            animation: bounceBtn 1.4s infinite;
            transition: transform 0.13s, box-shadow 0.13s;
        }

        @keyframes bounceBtn {

            0%,
            100% {
                transform: translateY(0);
            }

            10% {
                transform: translateY(-3px);
            }

            20% {
                transform: translateY(-6px);
            }

            30% {
                transform: translateY(-9px);
            }

            40% {
                transform: translateY(-12px);
            }

            50% {
                transform: translateY(-15px);
            }

            60% {
                transform: translateY(-12px);
            }

            70% {
                transform: translateY(-9px);
            }

            80% {
                transform: translateY(-6px);
            }

            90% {
                transform: translateY(-3px);
            }
        }

        .btn-danger.animated-bounce:hover,
        .btn-danger.animated-bounce:focus {
            transform: scale(1.07);
            filter: brightness(1.06);
            box-shadow: 0 6px 24px 0 #ff93ad44;
        }

        /* Chi tiết đơn đã thanh toán (hiện ra) */
        .pending-warning-detail {
            background: #fffde7;
            border: 2px solid #ffe4b5;
            border-radius: 15px;
            margin-top: 18px;
            margin-bottom: 0;
            padding: 22px 18px 16px 18px;
            box-shadow: 0 2px 14px 0 #fae0b740;
            font-size: 1.04em;
            animation: popupFadeIn .5s;
            z-index: 12;
        }

        @keyframes popupFadeIn {
            0% {
                opacity: 0;
                transform: scale(0.93);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }

        .pending-warning-item {
            display: flex;
            align-items: center;
            gap: 13px;
            border-bottom: 1px dashed #ffd1d1;
            padding-bottom: 8px;
            margin-bottom: 9px;
        }

        .pending-warning-item img {
            width: 54px;
            height: 54px;
            object-fit: cover;
            border-radius: 7px;
            border: 2.5px solid #ffe7e7;
        }

        .pending-warning-item .fw-bold {
            color: #d4008b;
        }

        .pending-warning-detail .badge {
            font-size: 0.95em;
            padding: 4px 10px;
        }

        @media (max-width: 600px) {
            .pending-warning {
                padding: 15px 7px 10px 7px;
            }

            .pending-warning-detail {
                padding: 10px 3px;
                font-size: 0.98em;
            }

            .pending-warning-buttons {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
    {{--  --}}


    <section class="breadscrumb-section pt-3 pb-3"
        style="background-color: #f8f8f8; width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; height:80px;">
        <div class="px-5" style="max-width: 1200px; margin: 0 auto; height: 100%;">
            <div class="row align-items-center" style="height: 100%; margin-top: -30px">
                <div class="col-6 d-flex align-items-center" style="height: 100%;">
                    <h2 style="font-weight: 700; font-size: 1.5rem; margin-left: -100px; margin-bottom: 10px">Giỏ hàng</h2>
                </div>
                <div class="col-6 d-flex align-items-center justify-content-end breadscrumb-contain">
                    <nav aria-label="breadcrumb" style="font-size: 0.9rem; margin-top: 10px">
                        <ol class="breadcrumb mb-0 d-flex align-items-center"
                            style="background: transparent; padding: 0; margin: 0; gap: 5px;">
                            <li class="breadcrumb-item" style="padding: 0; margin-right: -10px;">
                                <a href=""
                                    style="color: #4a5568; text-decoration: none; display: flex; align-items: center;">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item active" aria-current="page">
                                Giỏ hàng
                            </li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>


    <section class="py-4 bg-white">
        <div class="container">
            <!-- Nội dung giỏ hàng và phần tổng tiền -->
            <section class="cart-section section-b-space">
                <div class="container px-5">
                    <div class="row gx-5">
                        {{-- Các sản phẩm trong giỏ --}}
                        <div class="col-lg-9">
                            @if ($pendingPayments->isNotEmpty())
                                @foreach ($pendingPayments as $pendingPayment)
                                    @php
                                        $snapshot = $pendingPayment->cart_items_snapshot ?? [];
                                        $productNames = collect($snapshot)->pluck('product_name')->unique()->toArray();
                                        $productText =
                                            count($productNames) > 2
                                                ? implode(', ', array_slice($productNames, 0, 2)) . '...'
                                                : implode(', ', $productNames);
                                    @endphp

                                    <div class="pending-warning" x-data="{ showDetail: false }">
                                        <div class="pending-warning-content">
                                            <div class="emoji">🚨</div>
                                            <div>
                                                <strong>Chú ý!</strong>
                                                <span>
                                                    Bạn đã <b>thanh toán thành công</b> đơn hàng
                                                    <span
                                                        class="text-primary">{{ $productText ?: 'Sản phẩm chưa xác định' }}</span>
                                                    qua <span class="highlight-momo">MoMo</span>
                                                    <b>nhưng chưa hoàn tất đặt hàng!</b>
                                                </span>
                                                <div class="mt-1 text-danger">
                                                    👉 Nhấn <u @click="showDetail = !showDetail" style="cursor:pointer;"
                                                        x-text="showDetail ? 'Đóng chi tiết' : 'Xem chi tiết'"></u> để xem
                                                    lại đơn và <u>Đặt hàng ngay</u> để hoàn tất!
                                                </div>
                                            </div>
                                        </div>

                                        <div class="pending-warning-buttons">
                                            <button type="button" class="btn btn-outline-info animated-pulse"
                                                @click="showDetail = !showDetail"
                                                x-text="showDetail ? 'Đóng chi tiết' : 'Xem chi tiết'">
                                            </button>

                                            <form method="POST" action="{{ route('client.order.placeFromPending') }}">
                                                @csrf
                                                <input type="hidden" name="pending_payment_id"
                                                    value="{{ $pendingPayment->id }}">
                                                <button class="btn btn-danger animated-bounce">Đặt hàng ngay</button>
                                            </form>
                                        </div>

                                        <div class="pending-warning-detail" x-show="showDetail" x-transition
                                            @click.away="showDetail = false">
                                            <h5 class="fw-bold mb-3 highlight-momo">Chi tiết đơn đã thanh toán:</h5>
                                            @foreach ($pendingPayment->cart_items_snapshot as $item)
                                                <div class="pending-warning-item">
                                                    @if (!empty($item['image']))
                                                        <img src="{{ asset('storage/' . $item['image']) }}"
                                                            alt="{{ $item['product_name'] }}">
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">{{ $item['product_name'] }}</div>
                                                        @if (!empty($item['variant_name']))
                                                            <div class="text-muted small">Biến thể:
                                                                {{ $item['variant_name'] }}</div>
                                                        @endif
                                                        @if (!empty($item['sku']))
                                                            <div class="text-muted small">Mã SP: {{ $item['sku'] }}</div>
                                                        @endif
                                                        <div>
                                                            <span class="badge bg-secondary">SL:
                                                                {{ $item['quantity'] ?? 1 }}</span>
                                                            <span class="badge bg-info text-dark ms-2">
                                                                Đơn giá:
                                                                {{ number_format($item['price'] ?? 0, 0, ',', '.') }}₫
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="ms-auto fw-semibold text-success">
                                                        {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}₫
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="mt-2"><b>Mã pending:</b> {{ $pendingPayment->order_id }}</div>
                                            <div><b>Phương thức thanh toán:</b> <span class="highlight-momo">MoMo</span>
                                            </div>
                                            <div><b>Số tiền:</b> <span
                                                    class="fw-bold text-success">{{ number_format($pendingPayment->amount, 0, ',', '.') }}₫</span>
                                            </div>
                                            <div class="mt-2">
                                                <span class="badge bg-warning text-dark">Bạn cần bấm "Đặt hàng ngay" để hoàn
                                                    tất!</span>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif



                            <div class="bg-white rounded shadow-sm p-4">
                                <!-- FORM XÓA mục đã chọn -->
                                <form action="{{ route('client.cart.bulkDelete') }}" method="POST" id="bulk-action-form">
                                    @csrf
                                    <table class="table table-hover">
                                        <tbody>
                                            @php $tongTienTamTinh = 0; @endphp
                                            @forelse ($cartItems as $item)
                                                @if (isset($item->product))
                                                    @php
                                                        $pricePerItem = $item->price ?? ($item->product->price ?? 0);
                                                        $tongTien = $pricePerItem * $item->quantity;
                                                        $tongTienTamTinh += $tongTien;
                                                    @endphp
                                                    <tr class="align-middle">
                                                        <td>
                                                            <input type="checkbox" name="selected_items[]"
                                                                value="{{ $item->id }}"
                                                                data-price="{{ $tongTien }}"
                                                                style="accent-color: #07a37f;">
                                                        </td>
                                                        <td class="d-flex align-items-center gap-3">
                                                            <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                alt="{{ $item->product->name }}"
                                                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                                            <div>
                                                                <h6 class="mb-1 fw-semibold text-truncate"
                                                                    style="max-width: 300px;">{{ $item->product->name }}
                                                                </h6>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <button type="button"
                                                                class="btn btn-outline-danger btn-sm open-variant-modal"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#variantModal{{ $item->id }}">
                                                                {{ $item->variant->name ?? 'Chọn biến thể' }}
                                                            </button>
                                                            <!-- Modal biến thể (giữ nguyên nếu bạn đã có) -->
                                                            {{-- @foreach ($cartItems as $item)
                                                                <!-- Modal biến thể cho mỗi sản phẩm -->
                                                                <div class="modal fade" id="variantModal{{ $item->id }}"
                                                                    tabindex="-1"
                                                                    aria-labelledby="variantModalLabel{{ $item->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <div class="modal-header">
                                                                                <h5 class="modal-title"
                                                                                    id="variantModalLabel{{ $item->id }}">
                                                                                    Chọn biến thể</h5>
                                                                                <button type="button" class="btn-close"
                                                                                    data-bs-dismiss="modal"
                                                                                    aria-label="Đóng"></button>
                                                                            </div>
                                                                            <div class="modal-body">
                                                                                <div class="variant-btn-group">
                                                                                    @foreach ($item->product->variants as $variant)
                                                                                        <button type="button"
                                                                                            class="variant-btn"
                                                                                            data-variant-id="{{ $variant->id }}">
                                                                                            {{ $variant->name }}
                                                                                        </button>
                                                                                    @endforeach
                                                                                </div>
                                                                            </div>
                                                                            <div class="modal-footer">
                                                                                <button type="button"
                                                                                    class="btn btn-secondary"
                                                                                    data-bs-dismiss="modal">Trở lại</button>
                                                                                <button type="button"
                                                                                    class="btn btn-primary btn-confirm-variant"
                                                                                    data-cart-id="{{ $item->id }}">Xác
                                                                                    nhận</button>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            @endforeach --}}

                                                        </td>
                                                        <td class="text-end fw-semibold">
                                                            {{ number_format($pricePerItem, 0, ',', '.') }} ₫
                                                        </td>
                                                        <td class="text-center">
                                                            <div
                                                                class="d-flex justify-content-center align-items-center gap-2">
                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm btn-decrease px-2 py-0"
                                                                    style="font-size: 0.85rem;"
                                                                    data-id="{{ $item->id }}">−</button>
                                                                <input type="text" value="{{ $item->quantity }}"
                                                                    readonly
                                                                    class="form-control form-control-sm text-center quantity-input"
                                                                    data-id="{{ $item->id }}"
                                                                    style="width: 45px; height: 30px; font-size: 0.9rem; padding: 0;">
                                                                <button
                                                                    class="btn btn-outline-secondary btn-sm btn-increase px-2 py-0"
                                                                    style="font-size: 0.85rem;"
                                                                    data-id="{{ $item->id }}">+</button>
                                                            </div>
                                                        </td>
                                                        <td class="text-end fw-semibold">
                                                            {{ number_format($tongTien, 0, ',', '.') }} ₫
                                                        </td>
                                                        <td class="text-center">
                                                            <button type="button"
                                                                class="btn btn-link p-0 text-danger btn-delete-item"
                                                                data-id="{{ $item->id }}">Xóa</button>
                                                        </td>
                                                    </tr>
                                                @else
                                                    <tr>
                                                        <td colspan="7" class="text-center text-danger fw-semibold">Sản
                                                            phẩm không tồn tại</td>
                                                    </tr>
                                                @endif
                                            @empty
                                                <tr>
                                                    <td colspan="7" class="text-center">Giỏ hàng của bạn đang trống.
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    @foreach ($cartItems as $item)
                                        <div class="modal fade" id="variantModal{{ $item->id }}" tabindex="-1"
                                            aria-labelledby="variantModalLabel{{ $item->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title"
                                                            id="variantModalLabel{{ $item->id }}">
                                                            Chọn biến thể</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Đóng"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        <div class="variant-btn-group">
                                                            @foreach ($item->product->variants as $variant)
                                                                <button type="button" class="variant-btn"
                                                                    data-variant-id="{{ $variant->id }}">
                                                                    {{ $variant->name }}
                                                                </button>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Trở lại</button>
                                                        <button type="button" class="btn btn-primary btn-confirm-variant"
                                                            data-cart-id="{{ $item->id }}">Xác
                                                            nhận</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    <button type="submit" aria-label="Xóa mục đã chọn" id="btn-bulk-delete"
                                        class="btn-no-border">
                                        Xóa mục đã chọn
                                    </button>
                                </form>
                            </div>
                        </div>
                        {{-- Tổng tiền + Nút đặt hàng --}}
                        <div class="col-lg-3">
                            <div class="bg-white rounded shadow-sm p-4">
                                <h4 class="fw-bold mb-3">Tổng tiền giỏ hàng</h4>
                                {{-- <hr> --}}
                                {{-- <ul class="list-unstyled mb-4">
                        <li class="d-flex justify-content-between mb-2">
                            <span>Tạm tính (đã chọn)</span>
                            <span id="selected-total">0 ₫</span>
                        </li>
                       
                    </ul> --}}
                                <div class="d-flex justify-content-between border-top pt-3 fw-bold fs-5 mb-4">
                                    <span>Tổng cộng (VNĐ)</span>
                                    <span id="selected-total" class="text-success">0 ₫</span>
                                </div>
                                <!-- Form đặt hàng sản phẩm đã chọn -->
                                <form action="{{ route('client.cart.proceedCheckout') }}" method="POST"
                                    id="checkout-selected-form">
                                    @csrf
                                    <div id="selected-items-hidden"></div>
                                    <button type="submit" class="btn btn-danger w-100">Đặt hàng</button>
                                </form>
                                <a href="{{ route('client.home') }}" class="btn btn-outline-secondary w-100 mt-3">
                                    <i class="fa-solid fa-arrow-left-long me-2"></i> Tiếp tục mua hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <!-- Modal xác nhận xóa -->
            <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Xác nhận xóa</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body">
                            Bạn có chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                            <button type="button" class="btn btn-danger" id="confirm-delete-btn">Xóa</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const submitOrderBtn = document.getElementById('submit-order-btn');

                    // Nếu có nút Đặt hàng ở checkout, lưu flag khi click
                    if (submitOrderBtn) {
                        submitOrderBtn.addEventListener('click', function() {
                            sessionStorage.setItem('orderPlaced', 'true');
                        });
                    }

                    // Ở trang cart, ẩn thông báo nếu đã đặt hàng rồi
                    if (sessionStorage.getItem('orderPlaced') === 'true') {
                        const pendingAlert = document.querySelector(
                            '.alert-info.d-flex.justify-content-between.align-items-center');
                        if (pendingAlert) {
                            pendingAlert.style.display = 'none';
                        }
                        sessionStorage.removeItem('orderPlaced');
                    }
                });
            </script>

            <script>
                function attachDeleteEvents() {
                    document.querySelectorAll('.btn-delete-item').forEach(button => {
                        button.removeEventListener('click', handleDeleteClick);
                        button.addEventListener('click', handleDeleteClick);
                    });
                }

                function handleDeleteClick(event) {
                    cartItemIdToDelete = this.getAttribute('data-id');
                    const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
                    modal.show();
                }

                document.getElementById('confirm-delete-btn').addEventListener('click', function() {
                    if (!cartItemIdToDelete) return;

                    fetch(`/client/cart/delete/${cartItemIdToDelete}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                                    'content'),
                                'Accept': 'application/json'
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            const modalEl = document.getElementById('confirmDeleteModal');
                            const modalInstance = bootstrap.Modal.getInstance(modalEl);
                            modalInstance.hide();

                            if (data.success) {
                                const row = document.querySelector(`.btn-delete-item[data-id="${cartItemIdToDelete}"]`)
                                    .closest('tr');
                                if (row) row.remove();

                                showNotificationModal(data.message || 'Đã xóa sản phẩm');
                                updateTotals();

                                // Rebind lại nút còn lại
                                setTimeout(() => {
                                    attachDeleteEvents();
                                    cartItemIdToDelete = null;
                                }, 50);
                            } else {
                                showNotificationModal(data.message || 'Không thể xóa sản phẩm');
                                cartItemIdToDelete = null;
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            showNotificationModal('Đã có lỗi xảy ra khi xóa sản phẩm');
                            cartItemIdToDelete = null;
                        });
                });

                // Gắn ban đầu
                attachDeleteEvents();
            </script>
            {{-- Script đồng bộ selected checkbox cho đặt hàng --}}
            <script>
                document.getElementById('checkout-selected-form').addEventListener('submit', function(e) {
                    const hiddenDiv = document.getElementById('selected-items-hidden');
                    hiddenDiv.innerHTML = '';

                    const checked = document.querySelectorAll(
                        '#bulk-action-form input[type=checkbox][name="selected_items[]"]:checked');

                    checked.forEach(function(checkbox) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'selected_items[]';
                        input.value = checkbox.value;
                        hiddenDiv.appendChild(input);
                    });

                    if (checked.length === 0) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: 'Bạn chưa chọn sản phẩm nào để đặt hàng!'
                        });
                    }
                });
            </script>
            <script>
                // Hàm định dạng tiền Việt Nam
                function formatVND(amount) {
                    return amount.toLocaleString('vi-VN') + ' ₫';
                }

                // Định nghĩa hàm updateTotals ở phạm vi toàn cục để gọi ở mọi nơi
                function updateTotals() {
                    const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                    const totalDisplay = document.getElementById('selected-total');
                    const totalAmountDisplay = document.getElementById('total-amount');
                    const shippingFee = 20000; // 6.900 ₫

                    let selectedTotal = 0;
                    checkboxes.forEach(cb => {
                        if (cb.checked) {
                            selectedTotal += parseFloat(cb.getAttribute('data-price')) || 0;
                        }
                    });

                    if (totalDisplay) totalDisplay.textContent = formatVND(selectedTotal);
                    if (totalAmountDisplay) {
                        totalAmountDisplay.textContent = selectedTotal > 0 ? formatVND(selectedTotal + shippingFee) : '0 ₫';
                    }
                    console.log('Updated totals:', totalDisplay?.textContent, totalAmountDisplay?.textContent);
                }

                document.addEventListener('DOMContentLoaded', () => {
                    const selectAll = document.getElementById('select-all');

                    // Thiết lập sự kiện cho checkbox chọn tất cả
                    if (selectAll) {
                        selectAll.addEventListener('change', function() {
                            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                            checkboxes.forEach(cb => cb.checked = this.checked);
                            updateTotals();
                        });
                    }

                    // Thiết lập sự kiện cho từng checkbox
                    const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
                    checkboxes.forEach(cb => {
                        cb.addEventListener('change', updateTotals);
                    });

                    // Cập nhật tổng tiền ngay khi load trang
                    updateTotals();
                });

                // Xử lý nút tăng giảm số lượng
                document.querySelectorAll('.btn-increase, .btn-decrease').forEach(button => {
                    button.addEventListener('click', function(e) {
                        e.preventDefault();
                        const cartItemId = this.getAttribute('data-id');
                        const action = this.classList.contains('btn-increase') ? 'increase' : 'decrease';

                        fetch('{{ route('client.cart.updateQuantity') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    cart_item_id: cartItemId,
                                    action: action,
                                }),
                            })
                            .then(async response => {
                                const text = await response.text();
                                try {
                                    const data = JSON.parse(text);
                                    if (!response.ok) {
                                        console.error('Server error:', data);
                                        showNotificationModal(data.message ||
                                            response.statusText);

                                        throw new Error('Server error');
                                    }
                                    return data;
                                } catch (e) {
                                    console.error('Response không phải JSON:', text);
                                    throw new Error('Invalid JSON');
                                }
                            })
                            .then(data => {
                                if (data.success) {
                                    const inputQuantity = document.querySelector(
                                        `input.quantity-input[data-id='${cartItemId}']`
                                    );

                                    inputQuantity.value = data.quantity;

                                    const row = inputQuantity.closest('tr');
                                    const priceText = row.querySelector('td:nth-child(4)').textContent.trim()
                                        .replace('₫', '').replace(/\./g, '').trim();
                                    const pricePerItem = parseFloat(priceText);
                                    const newTotal = pricePerItem * data.quantity;
                                    const totalCell = row.querySelector('td:nth-child(6)');
                                    totalCell.textContent = formatVND(newTotal);

                                    const checkbox = row.querySelector('input[name="selected_items[]"]');
                                    if (checkbox) {
                                        checkbox.setAttribute('data-price', newTotal);
                                    }

                                    updateTotals();
                                } else {
                                    showNotificationModal(data.message || 'Cập nhật số lượng thất bại');
                                }
                            })
                    });
                });
            </script>
            <script>
                // Cập nhập biến thể:
                document.querySelectorAll('.variant-btn').forEach(button => {
                    button.addEventListener('click', function() {
                        // Bỏ active hết các button cùng nhóm
                        const group = this.parentNode;
                        group.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
                        // Active button được chọn
                        this.classList.add('active');
                    });
                });

                document.querySelectorAll('.btn-confirm-variant').forEach(button => {
                    button.addEventListener('click', function() {
                        const modal = this.closest('.modal');
                        const cartId = this.dataset.cartId;
                        // Lấy variant id đang active
                        const activeBtn = modal.querySelector('.variant-btn.active');
                        if (!activeBtn) {
                            showNotificationModal('Vui lòng chọn biến thể');
                            return;
                        }
                        const newVariantId = activeBtn.dataset.variantId;

                        fetch('{{ route('client.cart.updateVariant') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    cart_item_id: cartId,
                                    variant_id: newVariantId
                                }),
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    // Cập nhật UI trong bảng
                                    const row = document.querySelector(`button[data-cart-id="${cartId}"]`)
                                        .closest('tr');
                                    // Cập nhật nút mở modal (tên biến thể mới)
                                    const variantBtn = row.querySelector('.open-variant-modal');
                                    if (variantBtn) {
                                        variantBtn.textContent = data.newVariantName || 'Biến thể đã chọn';
                                    }

                                    // Cập nhật giá, tổng tiền
                                    row.querySelector('td:nth-child(4)').textContent = formatVND(data.newPrice);
                                    row.querySelector('td:nth-child(6)').textContent = formatVND(data
                                        .newPrice * data.quantity);

                                    // Cập nhật checkbox data-price
                                    const checkbox = row.querySelector('input[name="selected_items[]"]');
                                    if (checkbox) checkbox.setAttribute('data-price', data.newPrice * data
                                        .quantity);

                                    updateTotals();

                                    // Đóng modal
                                    var modalInstance = bootstrap.Modal.getInstance(modal);
                                    modalInstance.hide();
                                } else {
                                    showNotificationModal(data.message || 'Cập nhật biến thể thất bại');
                                }
                            });

                    });
                });
            </script>

            {{-- Hiển thị thông báo modal --}}
            <!-- Modal Thông báo -->
            <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="notificationModalLabel">Thông báo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                        </div>
                        <div class="modal-body" id="notificationModalBody">
                            <!-- Nội dung thông báo sẽ được đẩy vào đây -->
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-sm btn-primary" data-bs-dismiss="modal">Đóng</button>
                        </div>
                    </div>
                </div>
            </div>
            <script>
                function showNotificationModal(message) {
                    const modalBody = document.getElementById('notificationModalBody');
                    modalBody.textContent = message;
                    const modalEl = document.getElementById('notificationModal');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                }
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    if (sessionStorage.getItem('orderPlaced') === 'true') {
                        const pendingAlert = document.querySelector(
                            '.alert-info.d-flex.justify-content-between.align-items-center');
                        if (pendingAlert) {
                            pendingAlert.style.display = 'none';
                        }
                        sessionStorage.removeItem('orderPlaced');
                    }
                });
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const checkoutForm = document.getElementById('checkout-selected-form');
                    if (!checkoutForm) return;

                    checkoutForm.addEventListener('submit', function(e) {
                        e.preventDefault();
                        const hiddenDiv = document.getElementById('selected-items-hidden');
                        hiddenDiv.innerHTML = '';

                        // Lấy các checkbox sản phẩm được chọn
                        const checked = document.querySelectorAll(
                            '#bulk-action-form input[type=checkbox][name="selected_items[]"]:checked'
                        );
                        if (checked.length === 0) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Thông báo',
                                text: 'Bạn chưa chọn sản phẩm nào để đặt hàng!'
                            });
                            return;
                        }

                        // Thu thập id các cart item được chọn
                        let ids = [];
                        checked.forEach(function(checkbox) {
                            ids.push(checkbox.value);
                        });

                        // Gọi AJAX kiểm tra tồn kho
                        fetch('{{ route('client.cart.checkStock') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Content-Type': 'application/json',
                                    'Accept': 'application/json',
                                },
                                body: JSON.stringify({
                                    selected_items: ids
                                }),
                            })
                            .then(res => {
                                if (!res.ok) throw new Error('Lỗi hệ thống!');
                                return res.json();
                            })
                            .then(data => {
                                if (data.success) {
                                    // Nếu đủ kho, thêm các hidden input và submit form thật sự!
                                    ids.forEach(function(id) {
                                        const input = document.createElement('input');
                                        input.type = 'hidden';
                                        input.name = 'selected_items[]';
                                        input.value = id;
                                        hiddenDiv.appendChild(input);
                                    });
                                    checkoutForm.submit();
                                } else {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Thiếu hàng!',
                                        html: (data.messages && data.messages.length) ? data.messages
                                            .join('<br>') : 'Không xác định lỗi!'
                                    });
                                }
                            })
                            .catch(error => {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Lỗi hệ thống!',
                                    text: error.message || 'Vui lòng thử lại sau hoặc liên hệ admin.'
                                });
                            });
                    });
                });
            </script>

            <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        @endsection

        {{-- Lụa làm trang cart chán thí css js lung ta lung tung -> fix lại rồi nhé --}}
