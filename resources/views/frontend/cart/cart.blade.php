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
                            <div class="bg-white rounded shadow-sm p-4">
                                <form action="{{ route('client.cart.bulkDelete') }}" method="POST" id="bulk-action-form">
                                    @csrf
                                    <table class="table table-hover">
                                        {{-- <thead class="table-light">
                                            <tr>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col"></th>
                                                <th scope="col" class="text-end"></th>
                                                <th scope="col" style="width: 140px;" class="text-center"></th>
                                                <th scope="col" class="text-end"></th>
                                                <th scope="col" class="text-center"></th>
                                            </tr>
                                        </thead> --}}
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
                                                        <td><input type="checkbox" name="selected_items[]"
                                                                value="{{ $item->id }}" data-price="{{ $tongTien }}"
                                                                style="accent-color: #07a37f;"></td>
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

                                                            <!-- Modal biến thể -->
                                                            <div class="modal fade" id="variantModal{{ $item->id }}"
                                                                tabindex="-1"
                                                                aria-labelledby="variantModalLabel{{ $item->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered"
                                                                    style="max-width: 380px;">
                                                                    <div class="modal-content rounded-3 shadow-sm">
                                                                        <div class="modal-header border-0 pb-2">
                                                                            <h5 class="modal-title fw-semibold fs-5"
                                                                                id="variantModalLabel{{ $item->id }}">
                                                                                Chọn biến thể</h5>
                                                                            <button type="button"
                                                                                class="btn-close btn-close-sm"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Đóng"></button>
                                                                        </div>
                                                                        <div class="modal-body py-2">
                                                                            <div
                                                                                class="variant-btn-group d-flex flex-column gap-2">
                                                                                @foreach ($item->product->variants as $variant)
                                                                                    <button type="button"
                                                                                        class="variant-btn btn btn-outline-secondary {{ $variant->id == $item->variant_id ? 'active' : '' }} text-start"
                                                                                        data-variant-id="{{ $variant->id }}"
                                                                                        style="font-size: 0.9rem; padding: 8px 14px; border-radius: 0.375rem;">
                                                                                        {{ $variant->name }} —
                                                                                        {{ number_format($variant->price, 0, ',', '.') }}
                                                                                        ₫
                                                                                    </button>
                                                                                @endforeach
                                                                            </div>
                                                                        </div>
                                                                        <div
                                                                            class="modal-footer border-0 pt-2 justify-content-between">
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-danger"
                                                                                data-bs-dismiss="modal">Trở lại</button>

                                                                            <button type="button"
                                                                                class="btn btn-sm btn-danger btn-confirm-variant"
                                                                                data-cart-id="{{ $item->id }}">Xác
                                                                                nhận</button>

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </td>
                                                        <td class="text-end fw-semibold">
                                                            {{ number_format($pricePerItem, 0, ',', '.') }} ₫</td>
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
                                                            {{ number_format($tongTien, 0, ',', '.') }} ₫</td>
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

                                    <button type="submit" aria-label="Xóa mục đã chọn" id="btn-bulk-delete"
                                        class="btn-no-border">
                                        Xóa mục đã chọn
                                    </button>

                                    <style>
                                        .btn-no-border {
                                            background-color: #0da487;
                                            /* màu nền bạn muốn */
                                            color: white;
                                            /* màu chữ */
                                            padding: 8px 16px;
                                            border: none;
                                            /* bỏ viền */
                                            border-radius: 6px;
                                            /* bo góc */
                                            font-size: 14px;
                                            cursor: pointer;
                                            transition: background-color 0.3s ease;
                                            font-weight: 400;
                                        }

                                        .btn-no-border:hover {
                                            background-color: #07a37f;
                                            /* màu nền khi hover */
                                        }
                                    </style>


                                </form>
                                <script>
                                    document.getElementById('btn-bulk-delete').addEventListener('click', function(event) {
                                        event.preventDefault(); // Ngăn form submit mặc định trước

                                        // Mở modal thông báo xác nhận xóa
                                        const modalBody = document.getElementById('notificationModalBody');
                                        modalBody.textContent = 'Bạn có chắc chắn muốn xóa các mục đã chọn không?';

                                        const modalEl = document.getElementById('notificationModal');
                                        const modal = new bootstrap.Modal(modalEl);

                                        modal.show();

                                        // Tạo nút "Xác nhận" tạm thời để người dùng bấm đồng ý
                                        const footer = modalEl.querySelector('.modal-footer');

                                        // // Tạo nút xác nhận
                                        let confirmBtn = document.createElement('button');
                                        confirmBtn.className = 'btn btn-sm btn-danger'; // bạn có thể đổi style
                                        confirmBtn.textContent = 'Xác nhận';

                                        // Khi người dùng nhấn xác nhận thì submit form
                                        confirmBtn.addEventListener('click', function() {
                                            modal.hide();
                                            document.getElementById('bulk-action-form').submit();
                                        });

                                        // Thêm nút xác nhận vào modal footer
                                        footer.appendChild(confirmBtn);

                                        // Khi modal đóng, loại bỏ nút xác nhận để không bị thừa khi mở lại
                                        modalEl.addEventListener('hidden.bs.modal', function() {
                                            confirmBtn.remove();
                                        }, {
                                            once: true
                                        });
                                    });
                                </script>
                                <script>
                                    document.addEventListener('click', function(e) {
                                        if (!e.target.classList.contains('btn-delete-item')) return;

                                        // Lấy modal
                                        const modalEl = document.getElementById('notificationModal');
                                        const modalBody = document.getElementById('notificationModalBody');
                                        const modalTitle = modalEl.querySelector('.modal-title');
                                        const modalFooter = modalEl.querySelector('.modal-footer');

                                        // Đưa nội dung xác nhận vào modal
                                        modalTitle.textContent = 'Xác nhận';
                                        modalBody.textContent = 'Bạn chắc chắn muốn xóa sản phẩm này?';

                                        // Xóa footer cũ để thêm nút mới
                                        modalFooter.innerHTML = `
        <button type="button" class="btn btn-sm btn-success" data-bs-dismiss="modal">Hủy</button>
        <button type="button" class="btn btn-sm btn-danger" id="confirmDeleteBtn">Xóa</button>
    `;

                                        // Hiển thị modal
                                        const modal = new bootstrap.Modal(modalEl);
                                        modal.show();

                                        // Bắt sự kiện click nút Xóa trong modal
                                        const confirmBtn = document.getElementById('confirmDeleteBtn');
                                        confirmBtn.onclick = function() {
                                            const id = e.target.dataset.id;
                                            fetch(`/client/cart/delete/${id}`, {
                                                    method: 'DELETE',
                                                    headers: {
                                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                        'Accept': 'application/json',
                                                        'Content-Type': 'application/json'
                                                    }
                                                })
                                                .then(res => res.json())
                                                .then(data => {
                                                    if (data.success) {
                                                        location.reload();
                                                    } else {
                                                        showNotificationModal(data.message || 'Xóa thất bại');
                                                    }
                                                })
                                                .catch(() => showNotificationModal('Lỗi hệ thống'));
                                            modal.hide();
                                        };
                                    });
                                </script>

                            </div>
                        </div>

                        {{-- Tổng tiền giỏ hàng --}}
                        <div class="col-lg-3">
                            <div class="bg-white rounded shadow-sm p-4">
                                <h4 class="fw-bold mb-3">Tổng tiền giỏ hàng</h4>
                                <hr>
                                
                                <ul class="list-unstyled mb-4">
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Tạm tính (đã chọn)</span>
                                        <span id="selected-total">0 ₫</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-2">
                                        <span>Giảm giá</span>
                                        <span>(-) 0 ₫</span>
                                    </li>
                                    <li class="d-flex justify-content-between mb-3">
                                        <span>Phí vận chuyển</span>
                                        <span>6.900 ₫</span>
                                    </li>
                                </ul>
                                <div class="d-flex justify-content-between border-top pt-3 fw-bold fs-5 mb-4">
                                    <span>Tổng cộng (VNĐ)</span>
                                    <span id="total-amount" class="text-success">0 ₫</span>
                                </div>
                                <a
    href="{{ $cartItems->count() > 0 ? route('client.checkout') : 'javascript:void(0);' }}"
    class="btn btn-danger w-100 mb-3 {{ $cartItems->count() == 0 ? 'disabled' : '' }}"
    @if($cartItems->count() == 0) onclick="Swal.fire({icon:'warning',title:'Thông báo',text:'Giỏ hàng của bạn đang trống!'})" @endif
>
    Đặt hàng
</a>

                                <a href="{{ route('client.home') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fa-solid fa-arrow-left-long me-2"></i> Tiếp tục mua hàng
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

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
                    const shippingFee = 6900; // 6.900 ₫

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
                    background-color: #0da487;
                    color: white;
                    border-color: #0da487;
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
                    color: #666;
                    font-weight: 600;
                    padding: 6px 20px;
                    border-radius: 4px;
                }

                .modal-footer .btn-secondary:hover {
                    color: #fdfefe;
                }

                /* Nút Xác nhận */
                .modal-footer .btn-primary {
                    background-color: #0da487;
                    border: none;
                    font-weight: 700;
                    padding: 6px 20px;
                    border-radius: 4px;
                }

                .modal-footer .btn-primary:hover {
                    background-color: #0da487;
                }


                .open-variant-modal {
                    position: relative;
                    background: transparent !important;
                    color: #212529;
                    /* Màu chữ */
                    border: none !important;
                    box-shadow: none !important;
                    padding-right: 20px;
                    /* Khoảng cách để chứa mũi tên */
                    font-weight: normal;
                    cursor: pointer;
                    font-size: 1rem;
                    /* Bạn có thể điều chỉnh size chữ nếu cần */
                    line-height: 1.5;
                    /* Đảm bảo chiều cao dòng */
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
        @endsection
