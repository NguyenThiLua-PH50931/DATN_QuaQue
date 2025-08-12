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

        .rating {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 4px;
            user-select: none;
        }

        .rating li svg {
            transition: fill 0.2s;
            fill: none;
            cursor: pointer;
        }

        .rating li svg.fill {
            fill: #FFC107 !important;
            /* Màu vàng nổi bật */
        }

        .cl-btn {
            border: 1px solid #0da487;
            color: #0da487;
            transition: .5s;
        }

        .cl-btn:hover {
            background: #0da487;
            color: #fff;
        }

        .swal2-smaller-toast {
            font-size: 1rem !important;
            min-width: 160px !important;
            max-width: 220px !important;
            padding: 0.7em 1.2em !important;
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
                    <div>
                        <strong>Trạng thái thanh toán:</strong>
                        @php
                            // Ưu tiên hiển thị "Đã hoàn tiền"
                            $isRefunded =
                                $order->payment_status === 'refunded' ||
                                // fallback: nếu là ZaloPay đã hủy và refund_status báo success/pending thì cũng coi là đã hoàn tiền
                                ($order->payment_method === 'zalopay' &&
                                    $order->status === 'cancelled' &&
                                    in_array($order->refund_status ?? null, ['success', 'pending'], true));

                            $isRefundPending =
                                $order->payment_status === 'paid' && ($order->refund_status ?? null) === 'pending';

                            // COD: chỉ xem là đã thanh toán khi đã giao
                            $isPaidByCod = $order->payment_method === 'cod' && $order->status === 'delivered';

                            // Paid bình thường (không refund)
                            $isPaid = $order->payment_status === 'paid' || $isPaidByCod;
                        @endphp

                        @if ($isRefunded)
                            <span class="badge bg-success">Đã hoàn tiền</span>
                        @elseif($isRefundPending)
                            <span class="badge bg-warning text-dark">Đang hoàn tiền</span>
                        @elseif($isPaid)
                            <span class="badge bg-success">Đã thanh toán</span>
                        @elseif($order->payment_status === 'failed')
                            <span class="badge bg-danger">Thanh toán thất bại</span>
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
                    @if ($order->status === 'pending')
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
                    <div class="d-flex justify-content-between"><span>Tổng tiền
                            hàng:</span><span>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</span></div>
                    @if ($order->free_shipping_code)
                        <div class="d-flex justify-content-between">
                            <span>Mã miễn phí vận chuyển:</span>
                            <span>{{ $order->free_shipping_code }}</span>
                        </div>
                    @endif
                    <div class="d-flex justify-content-between"><span>Phí vận
                            chuyển:</span><span>{{ number_format($order->shipping_cost, 0, ',', '.') }} ₫</span></div>
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
                                        <div class="fw-bold fs-5 mb-1"><a class="fw-bold fs-5 mb-1"
                                                href="{{ route('client.product.detail', ['slug' => $item->product->slug]) }}">{{ $item->product_name }}</a>
                                        </div>
                                        @if ($item->product_variant_value_name)
                                            <div class="small text-muted mb-1">Phân loại:
                                                {{ $item->product_variant_value_name }}
                                            </div>
                                        @endif
                                        <div class="small text-muted mb-1">Mã SP:
                                            <b>{{ $item->productVariant?->sku ?? '---' }}</b>
                                        </div>
                                        <div class="small text-muted mb-2">Số lượng: x{{ $item->quantity }}</div>
                                        <div class="small text-muted mb-2">Giá gốc:
                                            x{{ number_format($pricePerItem, 0, ',', '.') }} ₫</div>
                                        <div class="text-end fw-bold" style="color: #229a71; font-size: 1.25rem;">Thành
                                            tiền:
                                            {{ number_format($item->total, 0, ',', '.') }} ₫
                                        </div>
                                        <div class="d-flex justify-content-end  mt-2 gap-2">
                                            {{-- Nút đánh giá, chỉ hiện nếu đơn đã giao và chưa đánh giá --}}
                                            @if ($order->status == 'delivered' && !$item->is_reviewed)
                                                <a href="#" class="btn-danhgia btn fw-bold " data-bs-toggle="modal"
                                                    data-bs-target="#writeReviewModal" data-order-id="{{ $order->id }}"
                                                    data-order-item-id="{{ $item->id }}"
                                                    data-product-id="{{ $item->product_id }}"
                                                    data-product-variant-value-id="{{ $item->product_variant_value_id ?? '' }}"
                                                    data-product-name="{{ $item->product_name }}"
                                                    data-product-image="{{ asset('storage/' . $item->product_image) }}"
                                                    data-product-price="{{ number_format($item->price) }}₫"
                                                    data-product-variant-value-name="{{ $item->product_variant_value_name ?? '' }}">
                                                    Đánh Giá
                                                </a>
                                                {{-- dump($item->product_variant_value_id) --}}
                                            @elseif($item->is_reviewed == 1)
                                                <span class="btn fw-bold"
                                                    style="background-color: #0da487; color: white;pointer-events: none;">Đã
                                                    đánh giá</span>
                                            @endif
                                            {{-- Nút mua lại --}}
                                            <a href="#" class="btn fw-bold cl-btn btn-cartstorequick"
                                                data-product-id="{{ $item->product_id }}"
                                                data-variant-id="{{ $item->product_variant_value_id }}"
                                                data-quantity="{{ $item->quantity }}">
                                                Mua lại
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
                                <td colspan="5" class="text-center text-muted">Chưa có lịch sử thay đổi trạng thái.
                                </td>
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
                        <button type="button" class="btn btn-outline-secondary fw-bold"
                            data-bs-dismiss="modal">Đóng</button>
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

    <!-- modal danh giá -->
    <div class="modal fade" id="writeReviewModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form id="productReviewForm" method="POST">
                    @csrf
                    <input type="hidden" name="order_id" value="">
                    <input type="hidden" name="order_item_id" value="">
                    <input type="hidden" name="product_id" value="">
                    <input type="hidden" name="product_variant_value_id" value="">
                    <input type="hidden" name="product_variant_name" value="">
                    <input type="hidden" name="rating" id="ratingInput" value="">

                    <div class="modal-header">
                        <h5 class="modal-title">Đánh giá sản phẩm</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <!-- Thông tin sản phẩm -->
                        <div class="d-flex align-items-center mb-3">
                            <img id="productImage" src="" alt="Product Image"
                                style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px; margin-right: 12px;">
                            <div>
                                <h6 id="productName" class="mb-1"></h6>
                                <div id="productVariantName" class="text-muted" style="font-size: 0.9rem;"></div>
                                <div id="productPrice" class="text-success fw-bold" style="font-size: 1rem;"></div>
                            </div>
                        </div>
                        <!-- Chọn sao -->
                        <label class="form-label">Đánh giá</label>
                        <ul class="rating" id="ratingStars">
                            @for ($i = 1; $i <= 5; $i++)
                                <li data-value="{{ $i }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" class="feather feather-star fill">
                                        <polygon
                                            points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2">
                                        </polygon>
                                    </svg>
                                </li>
                            @endfor
                        </ul>
                        <!-- Nội dung đánh giá -->
                        <div class="mb-3 mt-1">
                            <label for="content" class="form-label">Nội dung đánh giá</label>
                            <textarea class="form-control" id="content" name="content" rows="3" placeholder="Nhập đánh giá..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn fw-bold cl-btn" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn fw-bold" style="background-color: #0da487; color: white;">Gửi
                            đánh giá</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Toast thông báo thành công/thất bại -->
    <div id="reviewToast" class="toast position-fixed top-0 end-0 m-3" role="alert" aria-live="assertive"
        aria-atomic="true" data-bs-delay="2000" style="z-index:9999;">
        <div class="toast-header bg-success text-white">
            <strong class="me-auto">Thông báo</strong>
            <button type="button" class="btn-close btn-close-white ms-2 mb-1" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="reviewToastBody">
            <!-- Nội dung ở đây -->
        </div>
    </div>

    <!-- js danh gia -->
    <script>
        // Hiệu ứng sao vàng
        function setStars(num) {
            $('#ratingStars li').each(function(i, el) {
                let svg = $(el).find('svg');
                if (i < num) {
                    svg.addClass('fill');
                } else {
                    svg.removeClass('fill');
                }
            });
        }

        // Toast bằng SweetAlert2
        function showToastSwal(msg, success = true) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: success ? 'success' : 'error',
                title: msg,
                showConfirmButton: false,
                timer: 7000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal2-smaller-toast'
                }
            });
        }

        // Mở modal và reset form, lưu lại nút đánh giá đang thao tác
        $(document).on('click', '.btn-danhgia', function(e) {
            e.preventDefault();
            let $btn = $(this);
            $('#productReviewForm').data('review-btn', $btn);

            $('#productReviewForm input[name=order_id]').val($btn.data('order-id'));
            $('#productReviewForm input[name=order_item_id]').val($btn.data('order-item-id'));
            $('#productReviewForm input[name=product_id]').val($btn.data('product-id'));
            $('#productReviewForm input[name=product_variant_value_id]').val($btn.data('product-variant-value-id'));
            $('#productReviewForm input[name=product_variant_name]').val($btn.data(
                'product-variant-name')); // Gán tên biến thể

            $('#productReviewForm input[name=rating]').val('');
            $('#productReviewForm textarea[name=content]').val('');

            $('#productName').text($btn.data('product-name'));
            $('#productImage').attr('src', $btn.data('product-image'));
            $('#productPrice').text($btn.data('product-price'));
            $('#productVariantName').text($btn.data('product-variant-name'));

            setStars(0);
        });

        // Hover sao vàng tạm thời
        $('#ratingStars li').on('mouseenter', function() {
            let value = $(this).data('value');
            setStars(value);
        });
        $('#ratingStars li').on('mouseleave', function() {
            let selected = $('#ratingInput').val() || 0;
            setStars(selected);
        });

        // Click chọn sao
        $('#ratingStars li').on('click', function() {
            let value = $(this).data('value');
            $('#ratingInput').val(value);
            setStars(value);
        });

        // Submit form đánh giá
        $('#productReviewForm').on('submit', function(e) {
            e.preventDefault();
            let $form = $(this);
            let $btnSubmit = $form.find('button[type=submit]');
            let $reviewBtn = $form.data('review-btn');

            $btnSubmit.prop('disabled', true).text('Đang gửi...');

            $.ajax({
                url: '/client/danh-gia/store',
                method: 'POST',
                data: $form
                    .serialize(), // dữ liệu sẽ có product_variant_name vì bạn đã thêm input ẩn và gán bên trên
                success: function(res) {
                    showToastSwal(res.message || 'Đã gửi đánh giá!', true);
                    $('#writeReviewModal').modal('hide');
                    // Thay nút Đánh Giá thành badge "Đã đánh giá"
                    if ($reviewBtn) {
                        $reviewBtn.replaceWith(
                            '<span class="btn fw-bold" style="background-color: #0da487; color: white; pointer-events: none;">Đã đánh giá</span>'
                        );
                    }
                },
                error: function(xhr) {
                    let msg = 'Đã có lỗi xảy ra!';
                    if (xhr.status == 401) msg = 'Bạn cần đăng nhập để đánh giá!';
                    else if (xhr.responseJSON && xhr.responseJSON.message) msg = xhr.responseJSON
                        .message;
                    showToastSwal(msg, false);
                },
                complete: function() {
                    $btnSubmit.prop('disabled', false).text('Gửi đánh giá');
                }
            });
        });
    </script>


    <!-- Script popup php đơn -->
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
            btn.addEventListener('click', function() {
                btn.disabled = true;
                setTimeout(() => btn.disabled = false, 1500);
            });

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
    <!-- js mua lại -->
    <script>
        // Hàm toast đẹp bằng SweetAlert2
        function showToastSwal(message, success = true) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: success ? 'success' : 'error',
                title: message,
                showConfirmButton: false,
                timer: 4000,
                timerProgressBar: true,
                customClass: {
                    popup: 'swal2-smaller-toast'
                }
            });
        }

        $('.btn-cartstorequick').on('click', function(e) {
            e.preventDefault();

            const $btn = $(this);
            const productId = $btn.data('product-id');
            const variantId = $btn.data('variant-id');
            const quantity = $btn.data('quantity') || 1;

            $.ajax({
                url: '{{ route('client.cart.storeQuick') }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    product_id: productId,
                    variant_id: variantId,
                    quantity: quantity
                },
                success: function(res) {
                    showToastSwal(res.message, true);
                },
                error: function(xhr) {
                    let msg = xhr.responseJSON?.message || 'Có lỗi xảy ra.';
                    showToastSwal(msg, false);
                }
            });
        });
    </script>
    <script>
        setInterval(function() {
            fetch('{{ route('client.orders.status', ['order' => $order->id]) }}')
                .then(res => res.json())
                .then(data => {
                    if (data.status !== '{{ $order->status }}') {
                        location.reload();
                    }
                });
        }, 1000);
    </script>
    <style>
        #btn-cancel-order {
            padding: 8px 20px;
            border: none;
            border-radius: 6px;
            font-weight: 600;
            position: relative;
            overflow: hidden;
            transition: all 0.4s;
            box-shadow: 0 2px 10px rgba(220, 53, 69, 0.3);
            background: #dc3545;
            color: white;
            z-index: 1;
        }

        #btn-cancel-order::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 0%;
            background: #c82333;
            transition: all 0.4s cubic-bezier(0.65, 0, 0.35, 1);
            z-index: -1;
            border-radius: 6px;
        }

        #btn-cancel-order:hover {
            transform: translateY(-3px);
            box-shadow: 0 5px 15px rgba(220, 53, 69, 0.6);
        }

        #btn-cancel-order:hover::before {
            height: 100%;
        }

        #btn-cancel-order:active {
            transform: translateY(1px);
        }

        #btn-cancel-order i {
            margin-right: 8px;
            transition: all 0.3s;
        }

        #btn-cancel-order:hover i {
            transform: rotate(90deg) scale(1.2);
        }
    </style>
@endsection
