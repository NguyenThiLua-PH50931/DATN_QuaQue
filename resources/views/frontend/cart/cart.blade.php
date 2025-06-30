@extends('layouts.frontend')
@section('title', 'Giỏ hàng')
@section('contents')

    <section class="py-4 bg-light">
        <div class="container">
            <!-- Tiêu đề và breadcrumb -->
            <div class="breadscrumb-contain mb-6 pb-3 border-bottom d-flex justify-content-between align-items-center">
                <h2 class="mb-0 fw-bold" style="font-size: 1.5rem; color: #212529;">Giỏ hàng</h2>
            </div>
            <style>
                .breadscrumb-contain h2 {
                    font-weight: 700;
                    font-size: 1.5rem;
                    color: #212529;
                    margin-left: 35px;
                    /* đẩy sang phải 15px, bạn chỉnh số này tùy ý */
                }
            </style>

            <!-- Nội dung giỏ hàng và phần tổng tiền -->
            <div class="container px-5">
                <div class="row gx-5">
                    {{-- Các sản phẩm trong giỏ --}}
                    <div class="col-lg-8">
                        <div class="bg-white rounded shadow-sm p-4">
                            <form action="{{ route('client.cart.bulkDelete') }}" method="POST" id="bulk-action-form">
                                @csrf
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="select-all" /></th>
                                            <th>Sản phẩm</th>
                                            <th>Giá</th>
                                            <th style="width: 140px;">Số lượng</th>
                                            <th>Tổng</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $tongTienTamTinh = 0; @endphp

                                        @forelse ($cartItems as $item)
                                            @if (isset($item->product))
                                                @php
                                                    $pricePerItem = $item->price ?? ($item->product->price ?? 0);
                                                    $tongTien = $pricePerItem * $item->quantity;
                                                    $tongTienTamTinh += $tongTien;
                                                    $attrArr = json_decode($item->variant_attributes, true) ?? [];
                                                @endphp
                                                <tr>
                                                    <td>
                                                        <input type="checkbox" name="selected_items[]"
                                                            value="{{ $item->id }}" data-price="{{ $tongTien }}">
                                                    </td>
                                                    <td class="d-flex align-items-center gap-3">
                                                        <img src="{{ asset('storage/' . $item->product->image) }}"
                                                            alt="{{ $item->product->name }}"
                                                            style="width: 80px; height: 80px; object-fit: cover; border-radius: 8px;">
                                                        <div>
                                                            <h6 class="mb-1">{{ $item->product->name }}</h6>
                                                            <small>
                                                                @foreach ($attrArr as $attrId => $valId)
                                                                    {{ $attributesData[$attrId]['name'] ?? $attrId }}:
                                                                    {{ $attributesData[$attrId]['values'][$valId] ?? $valId }}
                                                                    @if (!$loop->last)
                                                                        ,
                                                                    @endif
                                                                @endforeach
                                                            </small>
                                                        </div>
                                                    </td>
                                                    <td><strong>{{ number_format($pricePerItem, 2) }} $</strong></td>
                                                    <td>
                                                        <div class="d-flex align-items-center gap-2">
                                                            <button class="btn btn-outline-secondary btn-sm btn-decrease"
                                                                data-id="{{ $item->id }}">−</button>
                                                            <input type="text" value="{{ $item->quantity }}" readonly
                                                                class="form-control form-control-sm text-center quantity-input"
                                                                data-id="{{ $item->id }}" style="width: 50px;">
                                                            <button class="btn btn-outline-secondary btn-sm btn-increase"
                                                                data-id="{{ $item->id }}">+</button>
                                                        </div>
                                                    </td>
                                                    <td><strong>{{ number_format($tongTien, 2) }} $</strong></td>
                                                    <td>
                                                        <button type="button"
                                                            class="btn btn-link p-0 text-danger btn-delete-item"
                                                            data-id="{{ $item->id }}">Xóa</button>
                                                    </td>
                                                </tr>
                                            @else
                                                <tr>
                                                    <td colspan="6" class="text-center text-danger fw-semibold">Sản phẩm
                                                        không tồn tại</td>
                                                </tr>
                                            @endif
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Giỏ hàng của bạn đang trống.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <button type="submit" class="btn btn-custom" aria-label="Xóa mục đã chọn"
                                    id="btn-bulk-delete">
                                    <i class="fa fa-trash"></i>
                                </button>

                            </form>
                            <script>
                                document.getElementById('btn-bulk-delete').addEventListener('click', function(event) {
                                    if (!confirm('Bạn có chắc chắn muốn xóa các mục đã chọn không?')) {
                                        event.preventDefault(); // Hủy submit form nếu nhấn Cancel
                                    }
                                });
                            </script>
                            <script>
                                document.addEventListener('click', function(e) {
                                    if (!e.target.classList.contains('btn-delete-item')) return;

                                    if (!confirm('Bạn chắc chắn muốn xóa sản phẩm này?')) return;

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
                                                alert(data.message || 'Xóa thất bại');
                                            }
                                        })
                                        .catch(() => alert('Lỗi hệ thống'));

                                });
                            </script>

                        </div>
                    </div>

                    {{-- Tổng tiền giỏ hàng --}}
                    <div class="col-lg-4">
                        <div class="bg-white rounded shadow-sm p-4">
                            <h4 class="fw-bold mb-4">Tổng tiền giỏ hàng</h4>
                            <form action="" method="POST" class="mb-4 d-flex gap-2">
                                @csrf
                                <input type="text" name="ma_giam_gia" class="form-control"
                                    placeholder="Nhập mã giảm giá">
                                <button type="submit" class="btn btn-success">Áp dụng</button>
                            </form>
                            <ul class="list-unstyled mb-4">
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Tạm tính (đã chọn)</span>
                                    <span id="selected-total">0.00 $</span>
                                </li>
                                <li class="d-flex justify-content-between mb-2">
                                    <span>Giảm giá</span>
                                    <span>(-) 0.00 $</span>
                                </li>
                                <li class="d-flex justify-content-between mb-3">
                                    <span>Phí vận chuyển</span>
                                    <span>6.90 $</span>
                                </li>
                            </ul>
                            <div class="d-flex justify-content-between border-top pt-3 fw-bold fs-5 mb-4">
                                <span>Tổng cộng (USD)</span>
                                <span id="total-amount" class="text-success">0.00 $</span>
                            </div>
                            <a href="" class="btn btn-danger w-100 mb-3">Tiến hành thanh toán</a>
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
    </style>

    <script>
        // Định nghĩa hàm updateTotals ở phạm vi toàn cục để gọi ở mọi nơi
        function updateTotals() {
            const checkboxes = document.querySelectorAll('input[name="selected_items[]"]');
            const totalDisplay = document.getElementById('selected-total');
            const totalAmountDisplay = document.getElementById('total-amount');
            const shippingFee = 6.9;

            let selectedTotal = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    selectedTotal += parseFloat(cb.getAttribute('data-price')) || 0;
                }
            });

            if (totalDisplay) totalDisplay.textContent = selectedTotal.toFixed(2) + ' $';
            if (totalAmountDisplay) {
                totalAmountDisplay.textContent = selectedTotal > 0 ? (selectedTotal + shippingFee).toFixed(2) + ' $' :
                    '0.00 $';
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
                                alert('Server trả về lỗi: ' + (data.message || response
                                    .statusText));
                                throw new Error('Server error');
                            }
                            return data;
                        } catch (e) {
                            console.error('Response không phải JSON:', text);
                            alert('Server trả về dữ liệu không hợp lệ');
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
        const priceText = row.querySelector('td:nth-child(3) strong').textContent.replace('$', '').trim();
        const normalizedPriceText = priceText.replace(/,/g, '');
        const pricePerItem = parseFloat(normalizedPriceText);
        const newTotal = pricePerItem * data.quantity;

        const totalCell = row.querySelector('td:nth-child(5) strong');
        totalCell.textContent = newTotal.toFixed(2) + ' $';

        const checkbox = row.querySelector('input[name="selected_items[]"]');
        if (checkbox) {
            checkbox.setAttribute('data-price', newTotal);
        }

        updateTotals();
    } else {
        alert(data.message || 'Cập nhật số lượng thất bại');
    }
})
            });
        });
    </script>
@endsection
