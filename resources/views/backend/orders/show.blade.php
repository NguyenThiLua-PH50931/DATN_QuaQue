@extends('layouts.backend')

@section('title', 'Chi tiết đơn hàng')
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
th, td {
    white-space: normal !important;
    word-wrap: break-word;
    text-align: center; /* Bạn có thể đổi thành left nếu muốn */
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
    color: #fff; /* chữ trắng cho nổi bật */
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
    background: linear-gradient(135deg, #ffa726, #fb8c00); /* gradient cam sáng -> cam đậm */
    border: 1px solid #f57c00;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(251, 140, 0, 0.4);
}

.bg-success {
    background: linear-gradient(135deg, #66bb6a, #388e3c); /* gradient xanh lá tươi sáng */
    border: 1px solid #2e7d32;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(56, 142, 60, 0.4);
}

.bg-danger {
    background: linear-gradient(135deg, #ef5350, #c62828); /* gradient đỏ sáng -> đỏ đậm */
    border: 1px solid #b71c1c;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(198, 40, 40, 0.4);
}

.bg-secondary {
    background: linear-gradient(135deg, #9e9e9e, #616161); /* gradient xám */
    border: 1px solid #424242;
    color: #fff !important;
    box-shadow: 0 2px 6px rgba(97, 97, 97, 0.4);
}
.filter-container form select.form-select-sm,
.filter-container form input.form-control-sm {
    min-width: 180px;  /* tăng lên 180px hoặc theo ý bạn */
    max-width: 220px;  /* tùy chỉnh tối đa */
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


</style>
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            {{-- <h5 class="fw-bold mb-0">Đơn hàng #{{ $order->order_code }}</h5> --}}
                            <div class="title-header option-title">
                                <h5>Danh sách đơn hàng #{{ $order->order_code }}</h5>
                            </div>
                            <ul class="list-inline mb-0">
                                <li class="list-inline-item">{{ $order->created_at->format('d/m/Y \l\ú\c H:i') }}</li>
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
                                                <th colspan="2">Sản phẩm</th>
                                                <th class="text-center">Số lượng</th>
                                                <th class="text-end">Giá</th>
                                                <th class="text-end">Giảm giá</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                                @foreach ($order->items as $item)
                                                    <tr>
                                                        <td style="width: 60px">
                                                            <img src="{{ asset('backend/assets/images/profile/' . $item->product_image) }}" class="img-thumbnail" style="width: 60px; height: 60px" alt="{{ $item->product_name }}">
                                                        </td>
                                                        <td>
                                                            {{ $item->product_name }}
                                                            @if ($item->variant)
                                                                <div class="text-muted small">Biến thể: {{ $item->variant->name }}</div>
                                                            @endif
                                                        </td>
                                                        <td class="text-center">{{ $item->quantity }}</td>
                                                        <td class="text-end">{{ number_format($item->price, 0, ',', '.') }} VNĐ</td>
                                                        <td class="text-end text-danger">-{{ number_format($item->discount * $item->quantity, 0, ',', '.') }} VNĐ</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>

                                        <tfoot>
                                            @php
                                                $subtotal = $order->items->sum(fn($item) => $item->price * $item->quantity);
                                                $discount_total = $order->items->sum(fn($item) => $item->discount * $item->quantity);
                                                $shipping = $order->shipping_cost ?? 0;
                                                $final_total = $subtotal + $shipping - $discount_total;
                                            @endphp
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Giảm giá:</td>
                                                <td class="text-end text-danger">-{{ number_format($discount_total, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Tạm tính:</td>
                                                <td class="text-end">{{ number_format($subtotal, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold">Phí vận chuyển:</td>
                                                <td class="text-end">{{ number_format($shipping, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                            <tr>
                                                <td colspan="4" class="text-end fw-bold text-success">Tổng cộng:</td>
                                                <td class="text-end fw-bold text-success">{{ number_format($final_total, 0, ',', '.') }} VNĐ</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                           <div class="col-lg-4">
                                <div style="background-color: #e6f4f1; padding: 1rem; border-radius: 0.5rem; color: #198754;">
                                    <h5 class="fw-bold mb-3">Thông tin đơn hàng</h5>
                                    <ul class="list-unstyled">
                                        <li style="margin-bottom: 0.5rem;"><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</li>
                                        <li style="margin-bottom: 0.5rem;"><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y') }}</li>
                                        <li style="margin-bottom: 0.5rem;"><strong>Thành tiền:</strong> {{ number_format($final_total, 0, ',', '.') }} VNĐ</li>
                                    </ul>

                                    <h5 class="fw-bold mt-4 mb-3">Địa chỉ nhận hàng</h5>
                                    <ul class="list-unstyled">
                                        <li style="margin-bottom: 0.5rem;">{{ $order->address->recipient_name ?? 'N/A' }}</li>
                                        <li style="margin-bottom: 0.5rem;">{{ $order->address->address ?? '' }}</li>
                                        <li style="margin-bottom: 0.5rem;">{{ $order->address->district ?? '' }}, {{ $order->address->province ?? '' }}</li>
                                        <li style="margin-bottom: 0.5rem;">SĐT: {{ $order->address->phone ?? '' }}</li>
                                    </ul>

                                    @php
                                        $method = match ($order->payment_method) {
                                            'cod' => 'Thanh toán khi nhận hàng (COD)',
                                            'bank' => 'Chuyển khoản ngân hàng',
                                            'momo' => 'Ví MoMo',
                                            default => 'Không xác định',
                                        };
                                    @endphp
                                    <h5 class="fw-bold mt-4 mb-2">Phương thức thanh toán</h5>
                                    <p>{{ $method }}</p>

                                    <div class="mt-4">
                                        <h5 class="fw-bold">Ngày giao dự kiến:</h5>
                                        <p class="mb-1 fw-semibold" style="color: #008c7e;">{{ $order->created_at->addDays(3)->format('d/m/Y') }}</p>
                                        <a href="{{ route('admin.orders.tracking', $order->id) }}" class="btn btn-outline-success btn-sm">Theo dõi đơn hàng</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('backend.footer')
</div>
@endsection
