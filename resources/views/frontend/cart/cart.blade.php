@extends('layouts.frontend')
@section('title', 'Giỏ hàng')
@section('contents')
<section class="breadscrumb-section pt-3 pb-3"
    style="background-color: #f8f8f8; width: 100vw; position: relative; left: 50%; right: 50%; margin-left: -50vw; margin-right: -50vw; height:80px;">
    <div class="px-5" style="max-width: 1200px; margin: 0 auto; height: 100%;">
        <div class="row align-items-center" style="height: 100%; margin-top: -30px">
            <div class="col-6 d-flex align-items-center" style="height: 100%;">
                <h2 style="font-weight: 700; font-size: 1.5rem; margin-left: -100px; margin-bottom: 10px">Giỏ hàng</h2>
            </div>
            <div class="col-6 d-flex align-items-center justify-content-end breadscrumb-contain">
                <nav aria-label="breadcrumb" style="font-size: 0.9rem; margin-top: 10px">
                    <ol class="breadcrumb mb-0 d-flex align-items-center" style="background: transparent; padding: 0; margin: 0; gap: 5px;">
                        <li class="breadcrumb-item" style="padding: 0; margin-right: -10px;">
                            <a href="" style="color: #4a5568; text-decoration: none; display: flex; align-items: center;">
                                <i class="fa-solid fa-house"></i>
                            </a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Giỏ hàng</li>
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

                    {{-- Phần hiển thị các đơn pending thanh toán chưa hoàn tất --}}
                    @if ($pendingPayments->isNotEmpty())
                        <div class="col-lg-12 mb-4">
                            @foreach ($pendingPayments as $pendingPayment)
                                @php
                                    $snapshot = $pendingPayment->cart_items_snapshot ?? [];
                                    $productNames = collect($snapshot)->pluck('product_name')->unique()->toArray();
                                    $productText = count($productNames) > 2
                                        ? implode(', ', array_slice($productNames, 0, 2)) . '...'
                                        : implode(', ', $productNames);
                                @endphp
                                <div class="pending-warning mb-3" x-data="{ showDetail: false }" style="border: 1px solid #ffc107; padding: 15px; border-radius: 8px; background: #fffbea;">
                                    <div class="pending-warning-content d-flex align-items-center gap-3">
                                        <div class="emoji" style="font-size: 2rem;">🚨</div>
                                        <div>
                                            <strong>Chú ý!</strong>
                                            Bạn đã <b>thanh toán thành công</b> đơn hàng
                                            <span class="text-primary">{{ $productText ?: 'Sản phẩm chưa xác định' }}</span>
                                            qua <span class="highlight-momo">MoMo</span>
                                            <b>nhưng chưa hoàn tất đặt hàng!</b>
                                            <div class="mt-1 text-danger" style="font-size: 0.9rem;">
                                                👉 Nhấn <u @click="showDetail = !showDetail" style="cursor:pointer;"
                                                    x-text="showDetail ? 'Đóng chi tiết' : 'Xem chi tiết'"></u> để xem lại
                                                đơn và <u>Đặt hàng ngay</u> để hoàn tất!
                                            </div>
                                        </div>
                                    </div>

                                    <div class="pending-warning-buttons mt-3 d-flex gap-2">
                                        <button type="button" class="btn btn-outline-info animated-pulse"
                                            @click="showDetail = !showDetail"
                                            x-text="showDetail ? 'Đóng chi tiết' : 'Xem chi tiết'">
                                        </button>

                                        <form method="POST" action="{{ route('client.order.placeFromPending') }}">
                                            @csrf
                                            <input type="hidden" name="pending_payment_id" value="{{ $pendingPayment->id }}">
                                            <button class="btn btn-danger animated-bounce">Đặt hàng ngay</button>
                                        </form>
                                    </div>

                                    <div class="pending-warning-detail mt-3" x-show="showDetail" x-transition @click.away="showDetail = false" style="border-top: 1px solid #ffc107; padding-top: 15px;">
                                        <h5 class="fw-bold mb-3 highlight-momo">Chi tiết đơn đã thanh toán:</h5>
                                        @foreach ($snapshot as $item)
                                            <div class="pending-warning-item d-flex align-items-center gap-3 mb-2">
                                                @if (!empty($item['image']))
                                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['product_name'] }}"
                                                        style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                                @endif
                                                <div>
                                                    <div class="fw-bold">{{ $item['product_name'] }}</div>
                                                    @if (!empty($item['variant_name']))
                                                        <div class="text-muted small">Biến thể: {{ $item['variant_name'] }}</div>
                                                    @endif
                                                    @if (!empty($item['sku']))
                                                        <div class="text-muted small">Mã SP: {{ $item['sku'] }}</div>
                                                    @endif
                                                    <div>
                                                        <span class="badge bg-secondary">SL: {{ $item['quantity'] ?? 1 }}</span>
                                                        <span class="badge bg-info text-dark ms-2">
                                                            Đơn giá: {{ number_format($item['price'] ?? 0, 0, ',', '.') }}₫
                                                        </span>
                                                    </div>
                                                </div>
                                                <div class="ms-auto fw-semibold text-success">
                                                    {{ number_format(($item['price'] ?? 0) * ($item['quantity'] ?? 1), 0, ',', '.') }}₫
                                                </div>
                                            </div>
                                        @endforeach

                                        <div class="mt-2"><b>Mã pending:</b> {{ $pendingPayment->order_id }}</div>
                                        <div><b>Phương thức thanh toán:</b> <span class="highlight-momo">MoMo</span></div>
                                        <div><b>Số tiền:</b> <span class="fw-bold text-success">{{ number_format($pendingPayment->amount, 0, ',', '.') }}₫</span></div>
                                        <div class="mt-2">
                                            <span class="badge bg-warning text-dark">Bạn cần bấm "Đặt hàng ngay" để hoàn tất!</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif

                    {{-- Phần hiển thị giỏ hàng sản phẩm --}}
                    <div class="col-lg-9">
                        <div class="bg-white rounded shadow-sm p-4">
                            <!-- FORM xử lý xoá nhiều sản phẩm -->
                            <form action="{{ route('client.cart.bulkDelete') }}" method="POST" id="bulk-action-form">
                                @csrf
                                @method('POST')
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
                                                        <input type="checkbox" name="selected_items[]" value="{{ $item->id }}" data-price="{{ $tongTien }}" style="accent-color: #07a37f;">
                                                    </td>
                                                    <td class="d-flex align-items-center gap-3">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                                        <div>
                                                            <h6 class="mb-1 fw-semibold text-truncate" style="max-width: 300px;">
                                                                {{ $item->product->name }}
                                                            </h6>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-outline-danger btn-sm open-variant-modal"
                                                            data-bs-toggle="modal" data-bs-target="#variantModal{{ $item->id }}"
                                                            data-cart-id="{{ $item->id }}">
                                                            {{ $item->variant->name ?? 'Chọn biến thể' }}
                                                        </button>
                                                    </td>
                                                    <td class="text-end fw-semibold">
                                                        {{ number_format($pricePerItem, 0, ',', '.') }} ₫
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                                            <button class="btn btn-outline-secondary btn-sm btn-decrease px-2 py-0" style="font-size: 0.85rem;" data-id="{{ $item->id }}">−</button>
                                                            <input type="text" value="{{ $item->quantity }}" readonly
                                                                class="form-control form-control-sm text-center quantity-input"
                                                                data-id="{{ $item->id }}" style="width: 45px; height: 30px; font-size: 0.9rem; padding: 0;">
                                                            <button class="btn btn-outline-secondary btn-sm btn-increase px-2 py-0" style="font-size: 0.85rem;" data-id="{{ $item->id }}">+</button>
                                                        </div>
                                                    </td>
                                                    <td class="text-end fw-semibold">
                                                        {{ number_format($tongTien, 0, ',', '.') }} ₫
                                                    </td>
                                                    <td class="text-center">
                                                        <button type="button" class="btn btn-link p-0 text-danger btn-delete-item" data-id="{{ $item->id }}">
                                                            Xóa
                                                        </button>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="7" class="text-center text-danger fw-semibold">Sản phẩm không tồn tại</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Giỏ hàng của bạn đang trống.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>

                                <!-- Nút xoá mục đã chọn -->
                                <div class="mt-3">
                                    <button type="submit" class="btn btn-sm text-white" id="bulk-delete-button"
                                        style="background-color: #ffa53b; padding: 6px 16px; font-size: 0.875rem; border-radius: 4px;">
                                        <i class="fa-solid fa-trash-can me-1"></i> Xoá mục đã chọn
                                    </button>
                                </div>
                            </form>

                            <!-- Modal chọn biến thể -->
                            @foreach ($cartItems as $item)
                                @if (isset($item->product))
                                    <div class="modal fade" id="variantModal{{ $item->id }}" tabindex="-1" aria-labelledby="variantModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="variantModalLabel{{ $item->id }}">Chọn biến thể</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="variant-btn-group">
                                                        @foreach ($item->product->variants as $variant)
                                                            <button type="button" class="variant-btn" data-variant-id="{{ $variant->id }}">
                                                                {{ $variant->name }}
                                                            </button>
                                                        @endforeach
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Trở lại</button>
                                                    <button type="button" class="btn btn-primary btn-confirm-variant" data-cart-id="{{ $item->id }}">Xác nhận</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    {{-- Tổng tiền và nút đặt hàng --}}
                    <div class="col-lg-3">
                        <div class="bg-white rounded shadow-sm p-4">
                            <h4 class="fw-bold mb-3">Tổng tiền giỏ hàng</h4>
                            <div class="d-flex justify-content-between border-top pt-3 fw-bold fs-5 mb-4">
                                <span>Tổng cộng (VNĐ)</span>
                                <span id="selected-total" class="text-success">0 ₫</span>
                            </div>

                            <!-- Form đặt hàng sản phẩm đã chọn -->
                            <form action="{{ route('client.cart.proceedCheckout') }}" method="POST" id="checkout-selected-form">
                                @csrf
                                <div id="selected-items-hidden"></div>
                                <button type="submit" class="btn btn-danger w-100">Đặt hàng</button>
                            </form>

                            <a href="{{ route('client.product.index') }}" class="btn btn-outline-secondary w-100 mt-3">
                                <i class="fa-solid fa-arrow-left-long me-2"></i> Tiếp tục mua hàng
                            </a>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    </div>
</section>

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
            input.name = 'selected_cart_item_ids[]';
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
        font-size: 0.9rem;
    }

    .col-lg-3 h4 {
        margin-bottom: 1rem !important;
        font-size: 1.25rem !important;
    }

    .col-lg-3 form.mb-4 {
        gap: 0.5rem !important;
    }

    .col-lg-3 form input.form-control {
        padding: 0.25rem 0.5rem !important;
        font-size: 0.9rem !important;
    }

    .col-lg-3 ul.list-unstyled li {
        margin-bottom: 0.5rem !important;
        font-size: 0.9rem !important;
    }

    .col-lg-3 .d-flex.border-top {
        padding-top: 0.75rem !important;
        font-size: 1.1rem !important;
        margin-bottom: 1rem !important;
    }

    .col-lg-3 a.btn {
        padding: 0.4rem 0 !important;
        font-size: 0.9rem !important;
    }

    .col-lg-3 a.btn i {
        font-size: 0.85rem !important;
    }

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
        gap: 10px;
        margin-bottom: 1rem;
    }

    .variant-btn {
        width: 100% !important;
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
        max-width: 160px;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        line-height: 1.5;
        display: inline-block;
        vertical-align: middle;
    }

    td:nth-child(3) {
        max-width: 180px;
        white-space: nowrap;
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
        const shippingFee = 20000; // 20.000₫ (bạn chỉnh lại phí vận chuyển nếu cần)

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
        //console.log('Updated totals:', totalDisplay?.textContent, totalAmountDisplay?.textContent);
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
                            showNotificationModal(data.message || response.statusText);
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
                        const inputQuantity = document.querySelector(`input.quantity-input[data-id='${cartItemId}']`);
                        inputQuantity.value = data.quantity;

                        const row = inputQuantity.closest('tr');
                        const priceText = row.querySelector('td:nth-child(4)').textContent.trim().replace('₫', '').replace(/\./g, '').trim();
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

    document.addEventListener('click', function(event) {
        const btn = event.target.closest('.btn-confirm-variant');
        if (!btn) return;

        const modal = btn.closest('.modal');
        const cartId = btn.dataset.cartId;

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
                    const variantButton = document.querySelector(`button.open-variant-modal[data-cart-id="${cartId}"]`);

                    if (!variantButton) {
                        console.warn('Không tìm thấy button với cartId:', cartId);
                        return;
                    }
                    const row = variantButton.closest('tr');
                    if (!row) {
                        console.warn('Không tìm thấy hàng tương ứng');
                        return;
                    }

                    // Cập nhật tên biến thể trên nút mở modal
                    const variantBtn = row.querySelector('.open-variant-modal');
                    if (variantBtn) {
                        variantBtn.textContent = data.newVariantName || 'Biến thể đã chọn';
                    }

                    // Cập nhật giá và tổng tiền
                    const priceCell = row.querySelector('td:nth-child(4)');
                    const totalCell = row.querySelector('td:nth-child(6)');

                    if (priceCell) priceCell.textContent = formatVND(data.newPrice);
                    if (totalCell) totalCell.textContent = formatVND(data.newPrice * data.quantity);

                    // Cập nhật thuộc tính data-price của checkbox
                    const checkbox = row.querySelector('input[name="selected_items[]"]');
                    if (checkbox) {
                        checkbox.setAttribute('data-price', data.newPrice * data.quantity);
                    }

                    updateTotals();

                    // Đóng modal
                    let modalInstance = bootstrap.Modal.getInstance(modal);
                    if (!modalInstance) {
                        modalInstance = new bootstrap.Modal(modal);
                    }
                    modalInstance.hide();

                } else {
                    showNotificationModal(data.message || 'Cập nhật biến thể thất bại');
                }
            })
            .catch(error => {
                console.error('Lỗi khi cập nhật biến thể:', error);
                showNotificationModal('Đã có lỗi xảy ra khi cập nhật biến thể.');
            });
    });

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.variant-btn').forEach(button => {
            button.addEventListener('click', function() {
                const group = this.parentNode;
                group.querySelectorAll('.variant-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });
    });

    function showNotificationModal(message) {
        document.getElementById('infoModalBody').textContent = message;
        new bootstrap.Modal(document.getElementById('infoModal')).show();
    }

    function confirmModal(message, onConfirm) {
        document.getElementById('confirmModalBody').textContent = message;

        const yesBtn = document.getElementById('confirmModalYesBtn');
        const modalEl = document.getElementById('confirmModal');
        const modal = new bootstrap.Modal(modalEl);

        const newHandler = function() {
            yesBtn.removeEventListener('click', newHandler);
            modal.hide();
            if (typeof onConfirm === 'function') onConfirm();
        };

        yesBtn.addEventListener('click', newHandler);
        modal.show();
    }

    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.btn-delete-item').forEach(button => {
            button.addEventListener('click', function() {
                const cartItemId = this.getAttribute('data-id');
                const row = this.closest('tr');

                confirmModal('Bạn có chắc chắn muốn xoá sản phẩm này?', function() {
                    fetch(`/client/cart/delete/${cartItemId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                            }
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                row.remove();
                                updateTotals();
                                showNotificationModal('Đã xoá sản phẩm khỏi giỏ hàng.');
                            } else {
                                showNotificationModal(data.message || 'Xoá thất bại!');
                            }
                        })
                        .catch(error => {
                            console.error('Lỗi:', error);
                            showNotificationModal('Đã xảy ra lỗi khi xoá sản phẩm.');
                        });
                });
            });
        });
    });

    document.addEventListener('DOMContentLoaded', () => {
        const bulkForm = document.getElementById('bulk-action-form');
        const deleteBtn = document.getElementById('bulk-delete-button');

        deleteBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const checked = bulkForm.querySelectorAll('input[name="selected_items[]"]:checked');

            if (checked.length === 0) {
                showNotificationModal('Vui lòng chọn ít nhất một sản phẩm để xoá.');
                return;
            }

            confirmModal('Bạn có chắc chắn muốn xoá các sản phẩm đã chọn?', function() {
                bulkForm.submit();
            });
        });
    });
</script>

<!-- Modal Thông báo -->
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">Thông báo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body" id="infoModalBody">
                <!-- Nội dung đổ qua JS -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary btn-sm" data-bs-dismiss="modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Xác nhận -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-labelledby="confirmModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body" id="confirmModalBody">Bạn có chắc chắn không?</div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal" style="padding: 4px 12px; font-size: 0.85rem;">
                    Hủy
                </button>
                <button type="button" class="btn btn-sm text-white" id="confirmModalYesBtn" style="background-color: #ffa53b; padding: 4px 14px; font-size: 0.85rem; border-radius: 4px;">
                    Đồng ý
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
