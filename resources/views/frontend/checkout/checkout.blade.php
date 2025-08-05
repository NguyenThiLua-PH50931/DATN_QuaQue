@extends('layouts.frontend')
@section('title', 'Đặt hàng')
@section('contents')
    <style>
        <style>.form-check-label {
            font-size: 1.08rem;
            padding-left: 3px;
        }

        .form-check-input {
            margin-top: 0;
        }

        .delivery-option {
            padding: 1rem 0.8rem;
            border-radius: 10px;
            border: 1.5px solid #e5e7eb;
            background: #fff;
            transition: border-color 0.2s;
        }

        input[type="radio"]:checked~.form-check-label {
            color: #0da487;
        }

        .payment-qr-wrapper,
        .col-md-6.text-center,
        #payment-qr {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-left: auto;
            margin-right: auto;
            width: 100%;
        }

        .payment-qr-wrapper>*,
        .col-md-6.text-center>*,
        #payment-qr>* {
            text-align: center;
            width: 100%;
        }

        .custom-modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999;
            background: rgba(0, 0, 0, 0.18);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .custom-modal {
            background: #fff;
            border-radius: 16px;
            width: 340px;
            max-width: 95vw;
            box-shadow: 0 10px 32px rgba(0, 0, 0, 0.13);
            padding: 28px 22px 18px 22px;
            text-align: center;
            animation: modalshow .18s cubic-bezier(.29, .86, .57, 1.01);
        }

        @keyframes modalshow {
            from {
                opacity: 0;
                transform: scale(.91);
            }

            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .custom-modal-icon {
            margin-bottom: 8px;
        }

        .custom-modal-title {
            font-size: 1.18rem;
            font-weight: 600;
            color: #F6A721;
            margin-bottom: 6px;
        }

        .custom-modal-message {
            color: #222;
            margin-bottom: 22px;
            font-size: 1.03rem;
        }

        .custom-modal-btn {
            border: none;
            border-radius: 7px;
            padding: 8px 28px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all .14s;
            min-width: 95px;
            background: #16a085;
            color: #fff;
            margin-top: 3px;
        }

        .custom-modal-btn:hover {
            filter: brightness(.94);
        }

        .custom-modal-cancel {
            background: #eee !important;
            color: #666 !important;
        }
    </style>
    @if (session('success') || session('error'))
        <div style="position: fixed; top: 32px; right: 32px; z-index: 1055; min-width:320px;max-width:90vw;">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible shadow rounded-3 fade show mb-2 px-4 py-3 fs-6"
                    role="alert">
                    <i class="fa fa-check-circle me-2"></i> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible shadow rounded-3 fade show mb-2 px-4 py-3 fs-6"
                    role="alert">
                    <i class="fa fa-exclamation-circle me-2"></i> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif
        </div>
        <script>
            setTimeout(function() {
                let alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    let closeBtn = alert.querySelector('.btn-close');
                    if (closeBtn) closeBtn.click();
                });
            }, 4000);
        </script>
    @endif

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Đặt hàng</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="/">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Đặt hàng</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- Checkout section Start -->
    <section class="checkout-section-2 section-b-space">
        <div class="container-fluid-lg">
            <form id="checkout-form" action="{{ route('client.checkout.process') }}" method="POST">
                @csrf
                @foreach ($selected_cart_item_ids ?? [] as $id)
                    <input type="hidden" name="selected_cart_item_ids[]" value="{{ $id }}">
                @endforeach


                <div class="row g-sm-4 g-3">
                    <div class="col-lg-8">
                        <div class="left-sidebar-checkout">
                            <div class="checkout-detail-box">
                                <ul>
                                    <!-- Địa chỉ giao hàng -->
                                    <li>
                                        <div class="checkout-icon">
                                            <lord-icon target=".nav-item" src="https://cdn.lordicon.com/ggihhudh.json"
                                                trigger="loop-on-hover"
                                                colors="primary:#121331,secondary:#646e78,tertiary:#0baf9a"
                                                class="lord-icon">
                                            </lord-icon>
                                        </div>
                                        <div class="checkout-box">
                                            <div class="checkout-title">
                                                <h4>Địa chỉ giao hàng</h4>
                                            </div>
                                            <div class="checkout-detail">
                                                <input type="hidden" name="address_id"
                                                    value="{{ old('address_id', $address->id ?? '') }}">
                                                <div class="mb-3">
                                                    <label>Họ và tên*</label>
                                                    <input type="text" name="recipient_name"
                                                        class="form-control @error('recipient_name') is-invalid @enderror"
                                                        value="{{ old('recipient_name', $address->recipient_name ?? '') }}"
                                                        placeholder="@error('recipient_name') {{ $message }} @else Họ và tên @enderror">
                                                </div>


                                                <div class="mb-3">
                                                    <label>Số điện thoại*</label>
                                                    <input type="text" name="phone"
                                                        class="form-control @error('phone') is-invalid @enderror"
                                                        value="{{ old('phone', $address->phone ?? '') }}"
                                                        placeholder="@error('phone') {{ $message }} @else Số điện thoại @enderror">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Tỉnh/Thành phố*</label>
                                                    <select id="province" name="province"
                                                        class="form-select @error('province') is-invalid @enderror">
                                                        <option value="">Chọn tỉnh/thành phố</option>
                                                        {{-- Load options qua JS --}}
                                                    </select>
                                                    @error('province')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label>Quận/Huyện*</label>
                                                    <select id="district" name="district"
                                                        class="form-select @error('district') is-invalid @enderror">
                                                        <option value="">Chọn quận/huyện</option>
                                                    </select>
                                                    @error('district')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label>Phường/Xã*</label>
                                                    <select id="ward" name="ward"
                                                        class="form-select @error('ward') is-invalid @enderror">
                                                        <option value="">Chọn phường/xã</option>
                                                    </select>
                                                    @error('ward')
                                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                                    @enderror
                                                </div>

                                                <div class="mb-3">
                                                    <label>Địa chỉ cụ thể*</label>
                                                    <input type="text" name="address"
                                                        class="form-control @error('address') is-invalid @enderror"
                                                        value="{{ old('address', $address->address ?? '') }}"
                                                        placeholder="@error('address') {{ $message }} @else Địa chỉ cụ thể @enderror">
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <script>
                                        let selectedProvince = @json(old('province', $address->province ?? ''));
                                        let selectedDistrict = @json(old('district', $address->district ?? ''));
                                        let selectedWard = @json(old('ward', $address->ward ?? ''));
                                    </script>
                                    <!-- Tùy chọn giao hàng -->
                                    <li>
                                        <div class="checkout-icon">
                                            <lord-icon target=".nav-item" src="https://cdn.lordicon.com/oaflahpk.json"
                                                trigger="loop-on-hover" colors="primary:#0baf9a" class="lord-icon">
                                            </lord-icon>
                                        </div>
                                        <div class="checkout-box">
                                            <div class="checkout-title">
                                                <h4>Tùy chọn giao hàng</h4>
                                            </div>
                                            <div class="checkout-detail">
                                                <div class="row g-4">
                                                    @php
                                                        if (!isset($shippingMethods)) {
                                                            // Log hoặc hiển thị debug
                                                            echo '<div style="color:red;">$shippingMethods chưa được truyền xuống view!</div>';
                                                            $shippingMethods = collect(); // Khởi tạo rỗng tránh lỗi foreach
                                                        }
                                                    @endphp
                                                    @foreach ($shippingMethods as $method)
                                                        <div class="col-xxl-6">
                                                            <div class="delivery-option">
                                                                <div
                                                                    class="form-check custom-form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="shipping_method_id"
                                                                        id="shipping_method_{{ $method->id }}"
                                                                        value="{{ $method->id }}"
                                                                        {{ $shippingMethodId == $method->id ? 'checked' : '' }}
                                                                        @if (!empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0) disabled @endif>
                                                                    <label class="form-check-label"
                                                                        for="shipping_method_{{ $method->id }}">
                                                                        {{ $method->name }}
                                                                        <span>({{ $method->description }})</span>
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                @if (!empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0)
                                                    <div class="alert alert-warning mt-2 mb-0 p-2 small">
                                                        🚫 Bạn đã thanh toán MoMo. Không thể thay đổi phương thức giao hàng.
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </li>

                                    <!-- Mã giảm giá -->
                                    <li>
                                        <div class="checkout-icon"></div>
                                        <div class="checkout-box">
                                            <div class="checkout-title">
                                                <h4>Mã giảm giá</h4>
                                            </div>
                                            <div class="mb-3">
                                                <label for="order_discount_code">Mã giảm giá đơn hàng</label>
                                                <select id="order_discount_code" name="order_discount_code"
                                                    class="form-control" style="min-width: 300px;"
                                                    @if (!empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0) disabled @endif>
                                                    <option value="">-- Chọn mã --</option>
                                                    @foreach ($validDiscountCodes->filter(function ($code) {
            return $code->type === 'order_discount';
        }) as $code)
                                                        <option value="{{ $code->code }}"
                                                            @if (isset($appliedDiscountCodes) && $appliedDiscountCodes->contains('code', $code->code)) selected @endif>
                                                            {{ $code->code }} — {{ $code->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-3">
                                                <label for="free_shipping_code">Mã miễn phí vận chuyển</label>
                                                <select id="free_shipping_code" name="free_shipping_code"
                                                    class="form-control" style="min-width: 300px;"
                                                    @if (!empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0) disabled @endif>
                                                    <option value="">-- Chọn mã --</option>
                                                    @foreach ($validDiscountCodes->filter(function ($code) {
            return $code->type === 'free_shipping';
        }) as $code)
                                                        <option value="{{ $code->code }}"
                                                            @if (isset($appliedDiscountCodes) && $appliedDiscountCodes->contains('code', $code->code)) selected @endif>
                                                            {{ $code->code }} — {{ $code->description }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            @if (!empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0)
                                                <div class="alert alert-warning mt-2 mb-0 p-2 small">
                                                    🚫 Bạn đã thanh toán MoMo. Không thể thay đổi mã giảm giá.
                                                </div>
                                            @endif
                                        </div>
                                    </li>



                                    <!-- Tùy chọn thanh toán -->
                                    <li>
                                        <div class="checkout-icon">
                                            <lord-icon target=".nav-item" src="https://cdn.lordicon.com/qmcsqnle.json"
                                                trigger="loop-on-hover" colors="primary:#0baf9a,secondary:#0baf9a"
                                                class="lord-icon">
                                            </lord-icon>
                                        </div>
                                        <div class="checkout-box">
                                            <div class="checkout-title">
                                                <h4>Tùy chọn thanh toán</h4>
                                            </div>
                                            <div class="checkout-detail">
                                                <div class="accordion accordion-flush custom-accordion"
                                                    id="accordionFlushExample">

                                                    {{-- CHỈ hiển thị COD nếu KHÔNG phải thanh toán MoMo thành công --}}
                                                    @if (empty($momoResult) || (isset($momoResult['resultCode']) && $momoResult['resultCode'] != 0))
                                                        <div class="accordion-item">
                                                            <div class="accordion-header" id="flush-headingFour">
                                                                <div class="accordion-button collapsed"
                                                                    data-bs-toggle="collapse"
                                                                    data-bs-target="#flush-collapseFour">
                                                                    <div class="custom-form-check form-check mb-0">
                                                                        <label class="form-check-label" for="cash">
                                                                            <input class="form-check-input mt-0"
                                                                                type="radio" name="payment_method"
                                                                                id="cash" value="cod" checked>
                                                                            Thanh toán khi nhận hàng
                                                                        </label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div id="flush-collapseFour"
                                                                class="accordion-collapse collapse show"
                                                                data-bs-parent="#accordionFlushExample">
                                                                <div class="accordion-body">
                                                                    <p class="cod-review">Thanh toán khi nhận hàng, giúp
                                                                        bạn
                                                                        yên tâm và thuận tiện hơn trong mỗi giao dịch!
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif

                                                    {{-- MOMO luôn hiển thị --}}
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" id="flush-headingMomo">
                                                            <div class="accordion-button collapsed"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapseMomo">
                                                                <div class="custom-form-check form-check mb-0">
                                                                    <label class="form-check-label" for="momo">
                                                                        <input class="form-check-input mt-0"
                                                                            type="radio" name="payment_method"
                                                                            id="momo" value="momo"
                                                                            {{ !empty($momoResult) && isset($momoResult['resultCode']) && $momoResult['resultCode'] == 0 ? 'checked' : '' }}>
                                                                        Thanh toán qua MoMo ảo (Test Sandbox)
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="flush-collapseMomo" class="accordion-collapse collapse"
                                                            data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body text-center">
                                                                {{-- Ẩn nút khi đã thanh toán thành công --}}
                                                                @if (empty($momoResult) || (isset($momoResult['resultCode']) && $momoResult['resultCode'] != 0))
                                                                    <button type="button" class="btn btn-primary mt-2"
                                                                        id="btn-momo-pay"></button>
                                                                @endif
                                                            </div>
                                                        </div>

                                                    </div>

                                                    {{-- THÔNG BÁO KẾT QUẢ THANH TOÁN MOMO --}}
                                                    @if (!empty($momoResult))
                                                        <div
                                                            class="alert {{ $momoResult['resultCode'] == 0 ? 'alert-success' : 'alert-danger' }}">
                                                            @if ($momoResult['resultCode'] == 0)
                                                                ✅ Thanh toán MoMo thành công! Bạn hãy nhấn "Đặt hàng" để
                                                                hoàn tất đơn.
                                                            @else
                                                                ❌ Thanh toán MoMo chưa thành công
                                                                ({{ $momoResult['message'] ?? 'Lỗi không xác định' }})<br>
                                                                Vui lòng chọn phương thức khác hoặc thử lại!
                                                            @endif
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="right-side-summery-box">
                            <div class="summery-box-2">
                                <div class="summery-header">
                                    <h3>Đơn hàng</h3>
                                </div>
                                @if ($cartItems && count($cartItems))
                                    <ul class="summery-contain">
                                        @foreach ($cartItems as $item)
                                            @if (isset($item->product))
                                                <li>
                                                    <img src="{{ asset('storage/' . $item->product->image) }}"
                                                        class="img-fluid blur-up lazyloaded checkout-image"
                                                        alt="{{ $item->product->name }}">
                                                    <h4>
                                                        {{ $item->product->name }}
                                                        @if ($item->variant)
                                                            ({{ $item->variant->name }})
                                                        @endif
                                                        <span>X {{ $item->quantity }}</span>
                                                    </h4>
                                                    <h4 class="price">
                                                        {{ number_format(($item->price ?? $item->product->price) * $item->quantity, 0, ',', '.') }}
                                                        ₫
                                                    </h4>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                @else
                                    <p>Giỏ hàng của bạn đang trống.</p>
                                @endif
                                <ul class="summery-total">
                                    <li>
                                        <h4>Tạm tính</h4>
                                        <h4 id="subtotal-amount" class="price">
                                            {{ number_format($subtotal, 0, ',', '.') }} VNĐ</h4>
                                    </li>
                                    <li>
                                        <h4>Phí vận chuyển</h4>
                                        <h4 id="shipping-fee" class="price">
                                            {{ number_format($shippingCost, 0, ',', '.') }} VNĐ</h4>
                                    </li>
                                    <li>
                                        <h4>Mã giảm giá</h4>
                                        <h4 id="discount-amount" class="price text-danger">
                                            -{{ number_format($discountAmount, 0, ',', '.') }} VNĐ
                                            @if (isset($appliedDiscountCodes) && $appliedDiscountCodes->isNotEmpty() && $discountAmount > 0)
                                                <span class="text-secondary">
                                                    ({{ $appliedDiscountCodes->pluck('code')->implode(', ') }})
                                                </span>
                                            @endif
                                        </h4>
                                    </li>

                                    <li class="list-total">
                                        <h4 id="total-amount" class="price text-success">
                                            {{ number_format($total, 0, ',', '.') }} VNĐ</h4>

                                    </li>
                                </ul>

                            </div>
                            <div class="checkout-offer">
                                <div class="offer-title">
                                    <div class="offer-icon">
                                        <img src="../frontend/assets/images/inner-page/offer.svg" class="img-fluid"
                                            alt="">
                                    </div>
                                    <div class="offer-name">
                                        <h6>Đặt hàng ngay</h6>
                                    </div>
                                </div>
                                <ul class="offer-detail">
                                    <li>
                                        <p>🙏 Cảm ơn bạn đã tin tưởng và đặt hàng tại <strong>Quà Quê</strong>.</p>
                                    </li>
                                    <li>
                                        <p>💚 Chúng tôi sẽ xử lý đơn hàng và giao đến bạn trong thời gian sớm nhất.</p>
                                    </li>
                                </ul>
                            </div>
                            <!-- Đặt ở ngay trên nút Đặt hàng hoặc trước </form> đều được -->
                            {{-- <input type="hidden" name="bank_transfer_confirmed" id="bank_transfer_confirmed_input"
                                value="0"> --}}
                            @if (session('pending_momo_payment.orderId'))
                                <input type="hidden" name="momo_order_id"
                                    value="{{ session('pending_momo_payment.orderId') }}">
                            @endif
                            <button type="submit" id="submit-order-btn"
                                class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Modal xác nhận MoMo -->
    <!-- Popup xác nhận MoMo style đẹp -->
    <div id="custom-momo-modal" class="custom-modal-overlay" style="display: none;">
        <div class="custom-modal">
            <div class="custom-modal-icon">
                <svg width="84" height="84" viewBox="0 0 84 84" fill="none">
                    <circle cx="42" cy="42" r="40" stroke="#f8bb86" stroke-width="4" />
                    <text x="50%" y="57%" text-anchor="middle" font-size="50" fill="#f8bb86" font-family="Arial"
                        dy=".3em">!</text>
                </svg>
            </div>
            <div class="custom-modal-title" style="margin-bottom: 4px;">Thông báo</div>
            <div class="custom-modal-message" style="margin-bottom: 20px;">
                Bạn có chắc chắn muốn thanh toán đơn hàng qua MoMo không?
            </div>
            <button class="custom-modal-btn custom-modal-ok">OK</button>
            <button class="custom-modal-btn custom-modal-cancel"
                style="background:#eee;color:#555;margin-left:10px;">Huỷ</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Reload nếu quay về bằng nút "Back" hoặc "Quay lại" từ MoMo
            if (performance && performance.getEntriesByType("navigation")[0]?.type === "back_forward") {
                location.reload();
            }

            const momoRadio = document.getElementById('momo');
            const momoBtn = document.getElementById('btn-momo-pay');
            const modal = document.getElementById('custom-momo-modal');
            const btnOk = modal?.querySelector('.custom-modal-ok');
            const btnCancel = modal?.querySelector('.custom-modal-cancel');

            function showModal() {
                if (modal) modal.style.display = 'flex';
            }

            function hideModal() {
                if (modal) modal.style.display = 'none';
            }

            if (momoRadio) {
                momoRadio.addEventListener('change', function() {
                    if (this.checked) showModal();
                });
            }

            if (momoBtn) momoBtn.addEventListener('click', showModal);
            if (btnCancel) btnCancel.onclick = hideModal;

            if (btnOk) {
                btnOk.onclick = function() {
                    hideModal();
                    const amountText = document.getElementById('total-amount')?.innerText || '0';
                    const amount = parseInt(amountText.replace(/[^\d]/g, '')) || 0;

                    // Kiểm tra số tiền > 1000
                    if (amount < 1000) {
                        alert('Số tiền phải lớn hơn 1000đ để thanh toán qua MoMo!');
                        return;
                    }

                    // Sinh orderId cực unique (chống trùng): QQyyyymmdd-xxxx-timestamp
                    const now = new Date();
                    const yyyy = now.getFullYear();
                    const mm = String(now.getMonth() + 1).padStart(2, '0');
                    const dd = String(now.getDate()).padStart(2, '0');
                    const dateStr = `${yyyy}${mm}${dd}`;
                    const random4 = Math.floor(1000 + Math.random() * 9000);
                    const timestamp = Date.now();
                    const orderId = `QQ${dateStr}-${random4}-${timestamp}`;

                    // LẤY DANH SÁCH SẢN PHẨM ĐƯỢC CHỌN
                    const selectedCartItemIds = Array.from(document.querySelectorAll(
                            'input[name="selected_cart_item_ids[]"]'))
                        .map(input => parseInt(input.value))
                        .filter(val => !isNaN(val));

                    if (selectedCartItemIds.length === 0) {
                        alert('Bạn chưa chọn sản phẩm nào để thanh toán!');
                        return;
                    }

                    // DEBUG:
                    console.log('selectedCartItemIds:', selectedCartItemIds);

                    fetch('/client/pay/momo', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            body: JSON.stringify({
                                amount,
                                orderId,
                                selected_cart_item_ids: selectedCartItemIds
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.payUrl) {
                                window.location.href = data.payUrl;
                            } else {
                                alert(data.error || 'Không lấy được link thanh toán MoMo!');
                            }
                        })
                        .catch(() => alert('Có lỗi khi kết nối tới server!'));
                };
            }
        });
    </script>


    <script>
        function updateOrderSummary() {
            const orderDiscountCode = document.getElementById('order_discount_code')?.value || '';
            const freeShippingCode = document.getElementById('free_shipping_code')?.value || '';
            const shippingMethodId = document.querySelector('input[name="shipping_method_id"]:checked')?.value || '';

            const selectedCartItemIds = Array.from(document.querySelectorAll('input[name="selected_cart_item_ids[]"]'))
                .map(input => parseInt(input.value));

            fetch('{{ route('client.checkout.updateShippingMethod') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        shipping_method_id: shippingMethodId,
                        order_discount_code: orderDiscountCode,
                        free_shipping_code: freeShippingCode,
                        selected_cart_item_ids: selectedCartItemIds
                    })
                })
                .then(res => res.json())
                .then(data => {
                    document.getElementById('subtotal-amount').innerText = data.subtotal.toLocaleString('vi-VN') +
                        ' VNĐ';
                    document.getElementById('shipping-fee').innerText = data.shipping_cost.toLocaleString('vi-VN') +
                        ' VNĐ';
                    document.getElementById('discount-amount').innerText = '-' + data.discount_amount.toLocaleString(
                        'vi-VN') + ' VNĐ';
                    document.getElementById('total-amount').innerText = data.total.toLocaleString('vi-VN') + ' VNĐ';
                })
                .catch(() => alert('Lỗi khi cập nhật đơn hàng!'));
        }
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gọi lại khi đổi phương thức giao hàng
            document.querySelectorAll('input[name="shipping_method_id"]').forEach(radio => {
                radio.addEventListener('change', updateOrderSummary);
            });

            // Gọi lại khi chọn mã giảm giá hoặc freeship
            const orderDiscountSelect = document.getElementById('order_discount_code');
            const freeShippingSelect = document.getElementById('free_shipping_code');

            if (orderDiscountSelect) {
                orderDiscountSelect.addEventListener('change', updateOrderSummary);
                if (!orderDiscountSelect.value) orderDiscountSelect.selectedIndex = 0;
            }

            if (freeShippingSelect) {
                freeShippingSelect.addEventListener('change', updateOrderSummary);
                if (!freeShippingSelect.value) freeShippingSelect.selectedIndex = 0;
            }

            // Gọi một lần để chắc chắn giá đúng khi load trang
            updateOrderSummary();
        });
    </script>

    <script>
        document.querySelectorAll('input[name="shipping_method_id"]').forEach((radio) => {
            radio.addEventListener('change', function() {
                fetch('/save-shipping-method', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        shipping_method_id: this.value
                    })
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const submitOrderBtn = document.getElementById('submit-order-btn');
            if (submitOrderBtn) {
                submitOrderBtn.addEventListener('click', function() {
                    sessionStorage.setItem('orderPlaced', 'true');
                });
            }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const addressFields = [
                'recipient_name', 'phone', 'address',
                'province', 'district', 'ward'
            ];
            addressFields.forEach(function(field) {
                document.querySelector(`[name="${field}"]`)?.addEventListener('change', sendAddressUpdate);
            });

            function sendAddressUpdate() {
                // Chỉ update khi có pending MoMo (order đã thanh toán chưa đặt hàng)
                const momoOrderId = document.querySelector('input[name="momo_order_id"]')?.value;
                if (!momoOrderId) return;
                fetch('/client/checkout/update-pending-payment-address', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        momo_order_id: momoOrderId,
                        recipient_name: document.querySelector('input[name="recipient_name"]')
                            .value,
                        phone: document.querySelector('input[name="phone"]').value,
                        address: document.querySelector('input[name="address"]').value,
                        province: document.querySelector('select[name="province"]').value,
                        district: document.querySelector('select[name="district"]').value,
                        ward: document.querySelector('select[name="ward"]').value,
                    })
                });
            }
        });
    </script>

@endsection
