@extends('layouts.frontend')
@section('title', 'Chi tiết đơn hàng')
@section('contents')

    <section class="breadscrumb-section pt-0 mb-3">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Chi tiết đơn hàng</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house"></i></a></li>
                                <li class="breadcrumb-item"><a href="{{ route('client.orders.index') }}">Đơn hàng</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Chi tiết</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="container py-3">
        <div class="row g-3">
            <!-- THÔNG TIN ĐƠN HÀNG -->
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white mb-3 shadow-sm">
                    <div class="fw-bold mb-2" style="color:#f47721">Thông tin đơn hàng</div>
                    <div><strong>Mã đơn hàng:</strong> {{ $order->order_code }}</div>
                    <div><strong>Ngày đặt:</strong> {{ optional($order->created_at)->format('d/m/Y H:i') }}</div>
                    <div><strong>Trạng thái:</strong>
                        @php
                            $statusMap = [
                                'pending' => ['Chờ xác nhận', 'secondary', 'fa-hourglass-half'],
                                'confirmed' => ['Đã xác nhận', 'info', 'fa-circle-check'],
                                'processing' => ['Đang chuẩn bị', 'warning', 'fa-bowl-food'],
                                'shipped' => ['Đã gửi hàng', 'primary', 'fa-truck'],
                                'in_transit' => ['Đang vận chuyển', 'primary', 'fa-shipping-fast'],
                                'delivered' => ['Đã giao', 'success', 'fa-check'],
                                'cancelled' => ['Đã huỷ', 'danger', 'fa-xmark-circle'],
                                'failed_delivery' => ['Giao thất bại', 'dark', 'fa-triangle-exclamation'],
                            ];
                            $st = $statusMap[$order->status] ?? [$order->status, 'secondary', 'fa-question'];
                        @endphp
                        <span class="badge bg-{{ $st[1] }}"><i class="fa-solid {{ $st[2] }}"></i>
                            {{ $st[0] }}</span>
                    </div>
                    <div><strong>PT Thanh toán:</strong>
                        @if ($order->payment_method == 'cod')
                            <span class="badge bg-secondary">COD</span>
                        @elseif($order->payment_method == 'bank')
                            <span class="badge bg-info text-dark">Chuyển khoản</span>
                        @elseif($order->payment_method == 'wallet')
                            <span class="badge bg-success text-white">Ví điện tử</span>
                        @else
                            {{ $order->payment_method }}
                        @endif
                    </div>
                    <div><strong>Trạng thái thanh toán:</strong>
                        @if ($order->payment_status == 'paid')
                            <span class="badge bg-success">Đã thanh toán</span>
                        @else
                            <span class="badge bg-warning text-dark">Chưa thanh toán</span>
                        @endif
                    </div>
                    <li>
                        <strong>Mã giảm giá:</strong>
                        @if ($order->discountCode)
                            <span class="badge bg-info text-dark">{{ $order->discountCode->code }}</span>
                            @if ($order->discountCode->description)
                                <span class="text-muted ms-2">{{ $order->discountCode->description }}</span>
                            @endif
                        @else
                            <span class="text-muted">Không có mã giảm giá</span>
                        @endif
                    </li>
                
                    @if (in_array($order->status, ['pending', 'confirmed', 'processing']) &&
                            !($order->payment_method === 'bank' && $order->payment_status === 'paid'))
                        <form id="cancelOrderForm" action="{{ route('client.orders.cancel', $order->id) }}" method="POST">
                            @csrf
                            <button type="button" id="btn-cancel-order" class="btn btn-danger btn-sm">
                                <i class="fa fa-times"></i> Huỷ đơn hàng
                            </button>
                        </form>
                    @endif


                    <script>
                        document.addEventListener('DOMContentLoaded', function() {
                            const btn = document.getElementById('btn-cancel-order');
                            if (btn) {
                                btn.addEventListener('click', function(e) {
                                    Swal.fire({
                                        icon: 'warning',
                                        title: 'Thông báo',
                                        text: 'Bạn chắc chắn muốn huỷ đơn hàng này?',
                                        showCancelButton: true,
                                        confirmButtonColor: '#16a085',
                                        cancelButtonColor: '#d33',
                                        confirmButtonText: 'OK',
                                        cancelButtonText: 'Huỷ'
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            document.getElementById('cancelOrderForm').submit();
                                        }
                                    });
                                });
                            }
                        });
                    </script>



                </div>
                <!-- NGƯỜI NHẬN -->
                <div class="border rounded-3 p-3 bg-white mb-3 shadow-sm">
                    <div class="fw-bold mb-2" style="color:#f47721">Người nhận</div>
                    <div><strong>Họ tên:</strong> {{ $order->address->recipient_name ?? '---' }}</div>
                    <div><strong>Điện thoại:</strong> {{ $order->address->phone ?? '---' }}</div>
                    <div>
                        <strong>Địa chỉ:</strong>
                        {{ $order->address->address ?? '' }},
                        {{ $order->address->ward ?? '' }},
                        {{ $order->address->district ?? '' }},
                        {{ $order->address->province ?? '' }}
                    </div>
                </div>
            </div>
            <!-- CHI TIẾT SẢN PHẨM & CHI PHÍ -->
            <div class="col-lg-6">
                <div class="border rounded-3 p-3 bg-white mb-3 shadow-sm">
                    <div class="fw-bold mb-2" style="color:#f47721">Sản phẩm trong đơn</div>
                    @foreach ($order->items as $item)
                        <div class="mb-3 pb-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <img src="{{ asset('storage/' . $item->product_image) }}" class="img-thumbnail"
                                    style="width:80px; height:80px; object-fit:cover;" alt="{{ $item->product_name }}">
                                <div>
                                    <div><strong>{{ $item->product_name }}</strong></div>
                                    <div>
                                        Mã SP: <b>{{ $item->productVariant?->sku ?? '---' }}</b>
                                    </div>
                                    @if ($item->product_variant_value_name)
                                        <div class="small text-muted">Biến thể: {{ $item->product_variant_value_name }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="mt-2">
                                <span><strong>SL:</strong> {{ $item->quantity }}</span> |
                                <span><strong>Đơn giá:</strong> {{ number_format($item->price, 0, ',', '.') }} ₫</span> |
                                <span><strong>Thành tiền:</strong> <b>{{ number_format($item->total, 0, ',', '.') }}
                                        ₫</b></span>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="border rounded-3 p-3 bg-white mb-3 shadow-sm">
                    <div class="fw-bold mb-2" style="color:#f47721">Chi phí</div>
                    <div class="d-flex justify-content-between"><span>Phí vận
                            chuyển:</span><span>{{ number_format($order->shipping_cost, 0, ',', '.') }} ₫</span></div>
                    <div class="d-flex justify-content-between"><span>Giảm
                            giá:</span><span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span></div>
                    <div class="d-flex justify-content-between fw-bold mt-2"><span>Tổng cộng:</span><span
                            class="text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span></div>
                </div>
            </div>
        </div>
        <!-- LỊCH SỬ ĐƠN HÀNG (nếu có quan hệ logs) -->
        <div class="border rounded-3 p-3 bg-white mb-4 shadow-sm">
            <div class="fw-bold mb-2" style="color:#f47721">Lịch sử trạng thái đơn hàng</div>
            <div class="table-responsive">
                <table class="table table-sm table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Ngày</th>
                            <th>Giờ</th>
                            <th colspan="3" class="text-center">Trạng thái đơn hàng</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $statusMap = [
                                'pending' => 'Chờ xác nhận',
                                'confirmed' => 'Đã xác nhận',
                                'processing' => 'Đang chuẩn bị',
                                'shipped' => 'Đã gửi hàng',
                                'in_transit' => 'Đang vận chuyển',
                                'delivered' => 'Đã giao hàng',
                                'cancelled' => 'Đã hủy',
                                'failed_delivery' => 'Giao thất bại',
                            ];
                        @endphp

                        @forelse ($order->statusLogs ?? [] as $log)
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('d/m/Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('H:i:s') }}</td>
                                <td class="text-end" style="width: 30%; color: #888;">
                                    {{ $statusMap[$log->from_status] ?? $log->from_status }}
                                </td>
                                <td class="text-center" style="width: 5%;">→</td>
                                <td class="text-start" style="width: 30%;">
                                    <span class="badge bg-primary">
                                        {{ $statusMap[$log->to_status] ?? $log->to_status }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">Chưa có lịch sử thay đổi trạng thái.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>


        <div class="text-end">
            <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary"><i class="fa fa-arrow-left"></i>
                Quay lại danh sách</a>
        </div>
    </div>
@endsection
