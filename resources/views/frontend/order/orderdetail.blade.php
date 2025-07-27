@extends('layouts.frontend')
@section('title', 'Chi tiết đơn hàng')
@section('contents')
    <style>
        .btn-mualai {
            border: 1.5px solid #ddd;
            border-radius: 8px;
            padding: 0.45rem 1.3rem;
            color: #444;
            background: #fff;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.18s;
        }

        .btn-mualai:hover {
            border-color: #aaa;
            color: #222;
            background: #f7f7f7;
            text-decoration: none;
        }

        .btn-danhgia {
            border: 1.5px solid #ffb700;
            border-radius: 8px;
            background: #fff;
            color: #b8820a;
            padding: 0.45rem 1.3rem;
            font-weight: 500;
            font-size: 1rem;
            transition: all 0.18s;
        }

        .btn-danhgia:hover {
            border-color: #e19700;
            color: #fff;
            background: #ffb700;
            text-decoration: none;
        }
    </style>
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
        <div class="row g-4 flex-lg-row flex-column-reverse">

            <!-- Thông tin đơn hàng, người nhận, chi phí -->
            <div class="col-lg-4">
                <!-- Thông tin đơn hàng -->
                <div class="border rounded-4 p-4 bg-white mb-4 shadow-sm">
                    <div class="fw-bold fs-5 mb-3" style="color:#f47721"><i class="fa fa-receipt"></i> Thông tin đơn hàng
                    </div>
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
                    <div>
                        <strong>Mã giảm giá:</strong>
                        @if ($order->discount_code)
                            <span class="badge bg-info text-dark">{{ $order->discount_code }}</span>
                        @else
                            <span class="text-muted">Không có mã giảm giá</span>
                        @endif
                    </div>
                    <div>
                        <strong>Mã miễn phí vận chuyển:</strong>
                        @if ($order->free_shipping_code)
                            <span class="badge bg-success">{{ $order->free_shipping_code }}</span>
                        @else
                            <span class="text-muted">Không có mã freeship</span>
                        @endif
                    </div>
                    @if (in_array($order->status, ['pending']) &&
                            !(in_array($order->payment_method, ['momo', 'bank']) && $order->payment_status === 'paid'))
                        <button type="button" id="btn-cancel-order" class="btn btn-danger btn-sm mt-3">
                            <i class="fa fa-times"></i> Huỷ đơn hàng
                        </button>
                    @endif
                </div>
                <!-- Người nhận -->
                <div class="border rounded-4 p-4 bg-white mb-4 shadow-sm">
                    <div class="fw-bold fs-5 mb-3" style="color:#f47721"><i class="fa fa-user"></i> Người nhận</div>
                    <div><strong>Họ tên:</strong> {{ $order->recipient_name ?? '---' }}</div>
                    <div><strong>Điện thoại:</strong> {{ $order->phone ?? '---' }}</div>
                    <div><strong>Địa chỉ:</strong> {{ $order->full_address ?? '---' }}</div>
                </div>
                <!-- Chi phí -->
                <div class="border rounded-4 p-4 bg-white mb-4 shadow-sm">
                    <div class="fw-bold fs-5 mb-3" style="color:#f47721"><i class="fa fa-coins"></i> Chi phí</div>
                    <div class="d-flex justify-content-between"><span>Tạm
                            tính:</span><span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span></div>
                    <div class="d-flex justify-content-between"><span>Phí vận
                            chuyển:</span><span>{{ number_format($order->shipping_cost, 0, ',', '.') }} ₫</span></div>
                    @if ($order->free_shipping_code)
                        <div class="d-flex justify-content-between">
                            <span>Mã miễn phí vận chuyển:</span>
                            <span>{{ $order->free_shipping_code }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between"><span>Giảm
                            giá:</span><span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span></div>
                    <div class="d-flex justify-content-between fw-bold mt-2"><span>Tổng cộng:</span><span
                            class="text-success fs-4">{{ number_format($order->total_amount, 0, ',', '.') }} ₫</span></div>
                </div>
            </div>

            <!-- Sản phẩm trong đơn hàng, đánh giá -->
            <div class="col-lg-8">
                <div class="border rounded-4 p-4 bg-white shadow-lg mb-4">
                    <div class="fw-bold fs-4 mb-4 text-center" style="color:#f47721"><i class="fa fa-box-open"></i> Sản phẩm
                        trong đơn</div>
                    @foreach ($order->items as $item)
                        @php
                            $pricePerItem = $item->price ?? ($item->product->price ?? 0);
                        @endphp
                        <div class="card mb-4 shadow-sm border-0">
                            <div class="row g-0 align-items-center">
                                <div class="col-auto p-3">
                                    <img src="{{ asset('storage/' . $item->product_image) }}"
                                        class="img-fluid rounded-3 border"
                                        style="width:90px; height:90px; object-fit:cover;">
                                </div>
                                <div class="col ps-0">
                                    <div class="card-body p-2">
                                        <div class="fw-bold fs-5 mb-1">{{ $item->product_name }}</div>
                                        @if ($item->product_variant_value_name)
                                            <div class="small text-muted mb-1">Phân loại:
                                                {{ $item->product_variant_value_name }}</div>
                                        @endif
                                        <div class="small text-muted mb-1">Mã SP:
                                            <b>{{ $item->productVariant?->sku ?? '---' }}</b>
                                        </div>
                                        <div class="small text-muted mb-2">Số lượng: x{{ $item->quantity }}</div>
                                        <div class="small text-muted mb-2">Giá gốc:
                                            x{{ number_format($pricePerItem, 0, ',', '.') }} ₫</div>
                                        <div class="text-end fw-bold" style="color: #229a71; font-size: 1.25rem;">Thành
                                            tiền:
                                            {{ number_format($item->total, 0, ',', '.') }} ₫</div>
                                        <div class="d-flex justify-content-end mt-2 gap-2">
                                            {{-- Nút đánh giá, chỉ hiện nếu đơn đã giao và chưa đánh giá --}}
                                            @if ($order->status == 'delivered' && (!isset($item->is_reviewed) || !$item->is_reviewed))
                                                <a href="" class="btn-danhgia">Đánh Giá</a>
                                            @elseif(isset($item->is_reviewed) && $item->is_reviewed)
                                                <span class="badge bg-success">Đã đánh giá</span>
                                            @endif
                                            {{-- Nút mua lại --}}
                                            <a href="{{ route('client.product.detail', ['slug' => $item->product->slug]) }}"
                                                class="btn-mualai">
                                                Mua Lại
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- LỊCH SỬ ĐƠN HÀNG -->
        <div class="border rounded-4 p-4 bg-white mb-4 shadow-sm">
            <div class="fw-bold fs-5 mb-3" style="color:#f47721"><i class="fa fa-clock"></i> Lịch sử trạng thái đơn hàng
            </div>
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
            <a href="{{ route('client.orders.index') }}" class="btn btn-outline-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>

    <!-- MODAL HỦY ĐƠN HÀNG -->
    <div class="modal fade" id="cancelReasonModal" tabindex="-1" aria-labelledby="cancelReasonModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('client.orders.cancel', $order->id) }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Chọn lý do huỷ đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>
                    <div class="modal-body">
                        @php
                            $cancelReasons = [
                                'Tôi không muốn mua nữa',
                                'Tôi muốn thay đổi sản phẩm / số lượng',
                                'Tôi muốn thay đổi địa chỉ giao hàng',
                                'Đặt nhầm đơn hàng',
                                'Lý do khác',
                            ];
                        @endphp

                        @foreach ($cancelReasons as $reason)
                            <div class="form-check mb-2">
                                <input class="form-check-input reason-radio" type="radio" name="cancel_reason"
                                    value="{{ $reason }}" required>
                                <label class="form-check-label">{{ $reason }}</label>
                            </div>
                        @endforeach

                        <!-- Lý do khác: hiện ô nhập -->
                        <div id="other-reason-group" class="mt-2 d-none">
                            <label for="other_reason_input">Vui lòng ghi rõ lý do:</label>
                            <textarea class="form-control" name="other_reason" id="other_reason_input" rows="3"></textarea>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận huỷ</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Script popup hủy đơn -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btn = document.getElementById('btn-cancel-order');
            const modalEl = document.getElementById('cancelReasonModal');
            const modal = new bootstrap.Modal(modalEl);
            const reasonRadios = document.querySelectorAll('.reason-radio');
            const otherGroup = document.getElementById('other-reason-group');

            if (btn) {
                btn.addEventListener('click', function() {
                    modal.show();
                });
            }

            reasonRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'Lý do khác') {
                        otherGroup.classList.remove('d-none');
                    } else {
                        otherGroup.classList.add('d-none');
                        document.getElementById('other_reason_input').value = '';
                    }
                });
            });
        });
    </script>

@endsection
