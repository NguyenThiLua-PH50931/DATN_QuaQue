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
                                                    @foreach ($shippingMethods as $method)
                                                        <div class="col-xxl-6">
                                                            <div class="delivery-option">
                                                                <div
                                                                    class="form-check custom-form-check d-flex align-items-center">
                                                                    <input class="form-check-input" type="radio"
                                                                        name="shipping_method_id"
                                                                        id="shipping_method_{{ $method->id }}"
                                                                        value="{{ $method->id }}"
                                                                        {{ $shippingMethodId == $method->id ? 'checked' : '' }}>
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
                                            </div>
                                        </div>
                                    </li>
                                    <script>
                                        document.querySelectorAll('input[name="shipping_method_id"]').forEach(function(radio) {
                                            radio.addEventListener('change', function() {
                                                var shippingMethodId = this.value;
                                                fetch("{{ route('client.checkout.updateShippingMethod') }}", {
                                                        method: 'POST',
                                                        headers: {
                                                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                            'Content-Type': 'application/json'
                                                        },
                                                        body: JSON.stringify({
                                                            shipping_method_id: shippingMethodId
                                                        })
                                                    }).then(res => res.json())
                                                    .then(data => {
                                                        document.getElementById('shipping-fee').innerText = data.shipping_cost
                                                            .toLocaleString('vi-VN') + ' VNĐ';
                                                        document.getElementById('total-amount').innerText = data.total.toLocaleString(
                                                            'vi-VN') + ' VNĐ';
                                                        document.getElementById('subtotal-amount').innerText = data.subtotal
                                                            .toLocaleString('vi-VN') + ' VNĐ';
                                                        document.getElementById('discount-amount').innerText = '-' + data
                                                            .discount_amount.toLocaleString('vi-VN') + ' VNĐ';

                                                        // Update QR code TPBank
                                                        var qrUrl = "https://img.vietqr.io/image/TPB-00005320304-compact2.png?amount=" +
                                                            data.total + "&addInfo=" + encodeURIComponent('Thanh toan don tam') +
                                                            "&accountName=" + encodeURIComponent('VU VAN QUAN');
                                                        document.getElementById('qr-bank').src = qrUrl;
                                                    });
                                            });
                                        });
                                    </script>
                                    <!-- Mã giảm giá -->
                                    <li>
                                        <div class="checkout-icon">...</div>
                                        <div class="checkout-box">
                                            <div class="checkout-title">
                                                <h4>Mã giảm giá</h4>
                                            </div>
                                            <div class="checkout-detail">
                                                <div class="row g-4">
                                                    <div class="mb-4 d-flex gap-2 align-items-center">

                                                        <select id="discount_code_select" name="discount_codes[]"
                                                            class="form-control" multiple
                                                            @if (isset($appliedDiscountCodes) && $appliedDiscountCodes->isNotEmpty()) disabled @endif
                                                            style="min-width: 300px;">
                                                            @foreach ($validDiscountCodes as $code)
                                                                <option value="{{ $code->code }}"
                                                                    @if (isset($selectDiscount) &&
                                                                            $selectDiscount &&
                                                                            isset($appliedDiscountCodes) &&
                                                                            $appliedDiscountCodes->contains('code', $code->code)) selected @endif>
                                                                    {{ $code->code }} — {{ $code->description }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        @if (!isset($appliedDiscountCodes) || $appliedDiscountCodes->isEmpty())
                                                            <button type="button" id="btn-apply-discount"
                                                                class="btn btn-success">Áp dụng</button>
                                                        @else
                                                            <button type="button" id="btn-remove-discount"
                                                                class="btn btn-danger">Xoá mã</button>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>

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
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" id="flush-headingFour">
                                                            <div class="accordion-button collapsed"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapseFour">
                                                                <div class="custom-form-check form-check mb-0">
                                                                    <label class="form-check-label" for="cash"><input
                                                                            class="form-check-input mt-0" type="radio"
                                                                            name="payment_method" id="cash"
                                                                            value="cod" checked> Thanh toán khi nhận
                                                                        hàng</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="flush-collapseFour"
                                                            class="accordion-collapse collapse show"
                                                            data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <p class="cod-review">Thanh toán khi nhận hàng, giúp bạn
                                                                    yên tâm và thuận tiện hơn trong mỗi giao dịch!
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="accordion-item">
                                                        <div class="accordion-header" id="flush-headingTwo">
                                                            <div class="accordion-button collapsed"
                                                                data-bs-toggle="collapse"
                                                                data-bs-target="#flush-collapseTwo">
                                                                <div class="custom-form-check form-check mb-0">
                                                                    <label class="form-check-label" for="banking"><input
                                                                            class="form-check-input mt-0" type="radio"
                                                                            name="payment_method" id="banking"
                                                                            value="bank">Chuyển khoản ngân hàng
                                                                    </label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                                            data-bs-parent="#accordionFlushExample">
                                                            <div class="accordion-body">
                                                                <p class="cod-review">Với phương thức chuyển khoản ngân
                                                                    hàng, bạn có thể dễ dàng thanh toán nhanh chóng và an
                                                                    toàn.</p>
                                                                <div class="row g-2">
                                                                    <div class="col-md-6 text-center">
                                                                        @php
                                                                            $bankCode = 'TPB';
                                                                            $accountNumber = '00005320304';
                                                                            $accountName = 'VU VAN QUAN';
                                                                            $amount = $total ?? 0;
                                                                            $addInfo = 'Thanh toan don tam';
                                                                            $qrUrl =
                                                                                "https://img.vietqr.io/image/{$bankCode}-{$accountNumber}-compact2.png?amount={$amount}&addInfo=" .
                                                                                urlencode($addInfo) .
                                                                                '&accountName=' .
                                                                                urlencode($accountName);
                                                                        @endphp
                                                                        <img id="qr-bank" src="{{ $qrUrl }}"
                                                                            alt="QR chuyển khoản TPBank"
                                                                            style="width:220px;max-width:100%;margin-bottom:10px;">
                                                                        <div style="font-size:15px">
                                                                            <span class="text-danger small">* Quét mã bằng
                                                                                app ngân hàng. Đảm bảo đúng số tiền và nội
                                                                                dung chuyển khoản!</span>
                                                                        </div>
                                                                        <!-- Nút xác nhận chuyển khoản -->
                                                                        <button type="button"
                                                                            class="btn btn-warning mt-3"
                                                                            id="btn-bank-confirm">Tôi đã chuyển
                                                                            khoản</button>
                                                                        <div id="bank-confirm-message"
                                                                            class="mt-2 text-success"
                                                                            style="display: none;">
                                                                            Cảm ơn bạn! Shop đã ghi nhận xác nhận chuyển
                                                                            khoản của bạn.
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
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
                                @if (count($cartItems))
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
                                        <h4>Tổng (VNĐ)</h4>
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
                            <input type="hidden" name="bank_transfer_confirmed" id="bank_transfer_confirmed_input"
                                value="0">
                            <button type="submit" id="submit-order-btn"
                                class="btn theme-bg-color text-white btn-md w-100 mt-4 fw-bold">Đặt hàng</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
    <!-- Script chọn tỉnh/huyện/xã -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const bankBtn = document.getElementById('btn-bank-confirm');
            const msg = document.getElementById('bank-confirm-message');
            const hiddenInput = document.getElementById('bank_transfer_confirmed_input');
            if (bankBtn && hiddenInput) {
                bankBtn.addEventListener('click', function() {
                    hiddenInput.value = 1;
                    if (msg) msg.style.display = 'block';
                    bankBtn.disabled = true;
                });
            }

            // Nếu bạn vẫn muốn dùng button type="button" cho nút đặt hàng:
            const submitOrderBtn = document.getElementById('submit-order-btn');
            const form = document.getElementById('checkout-form');
            // if (submitOrderBtn && form) {
            //     submitOrderBtn.addEventListener('click', function () {
            //         form.submit();
            //     });
            // }
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let locationsData = null;
            const provinceSelect = document.getElementById('province');
            const districtSelect = document.getElementById('district');
            const wardSelect = document.getElementById('ward');
            let selectedProvince = @json(old('province', $address->province ?? ''));
            let selectedDistrict = @json(old('district', $address->district ?? ''));
            let selectedWard = @json(old('ward', $address->ward ?? ''));

            function loadWards(provinceName, districtName) {
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                wardSelect.disabled = true;
                const province = locationsData.find(p => p.Name === provinceName);
                if (!province) return;
                const district = province.Districts.find(d => d.Name === districtName);
                if (!district) return;
                district.Wards.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.Name;
                    option.text = ward.Name;
                    wardSelect.add(option);
                });
                wardSelect.disabled = false;
                if (selectedWard) {
                    setTimeout(() => {
                        wardSelect.value = selectedWard;
                    }, 0);
                }
            }

            function loadDistricts(provinceName) {
                districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                districtSelect.disabled = true;
                wardSelect.disabled = true;
                const province = locationsData.find(p => p.Name === provinceName);
                if (!province) return;
                province.Districts.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.Name;
                    option.text = district.Name;
                    districtSelect.add(option);
                });
                districtSelect.disabled = false;
                if (selectedDistrict) {
                    setTimeout(() => {
                        districtSelect.value = selectedDistrict;
                        loadWards(provinceName, selectedDistrict);
                    }, 0);
                }
            }
            fetch('/data/vietnamAddress.json')
                .then(response => response.json())
                .then(data => {
                    locationsData = data;
                    locationsData.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.Name;
                        option.text = province.Name;
                        provinceSelect.add(option);
                    });
                    if (selectedProvince) {
                        provinceSelect.value = selectedProvince;
                        loadDistricts(selectedProvince);
                    }
                }).catch(e => {
                    console.error('Lỗi load JSON:', e);
                });
            provinceSelect.addEventListener('change', function() {
                selectedDistrict = '';
                selectedWard = '';
                loadDistricts(this.value);
            });
            districtSelect.addEventListener('change', function() {
                selectedWard = '';
                loadWards(provinceSelect.value, this.value);
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('checkout-form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const paymentMethodInput = document.querySelector(
                        'input[name="payment_method"]:checked');
                    const bankConfirmedInput = document.getElementById('bank_transfer_confirmed_input');
                    const paymentMethod = paymentMethodInput ? paymentMethodInput.value : '';
                    const bankConfirmed = bankConfirmedInput ? bankConfirmedInput.value : '0';
                    if (paymentMethod === 'bank' && bankConfirmed != "1") {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: 'Vui lòng nhấn nút "Tôi đã chuyển khoản" trước khi đặt hàng!',
                            confirmButtonText: 'OK'
                        });
                    }
                });
            }
        });

        // Xử lý mã giảm giá
        document.getElementById('btn-apply-discount').addEventListener('click', function() {
            const select = document.getElementById('discount_code_select');
            const selectedCodes = Array.from(select.selectedOptions).map(opt => opt.value);

            if (selectedCodes.length === 0) {
                alert('Vui lòng chọn ít nhất một mã giảm giá');
                return;
            }

            fetch('{{ route('client.checkout.applyDiscount') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content')
                    },
                    body: JSON.stringify({
                        discount_codes: selectedCodes
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert(data.message || 'Áp dụng mã giảm giá thất bại');
                    }
                })
                .catch(() => alert('Lỗi kết nối, vui lòng thử lại'));
        });

        // Xóa mã giảm giá
        document.getElementById('btn-remove-discount').addEventListener('click', function() {
            fetch('{{ route('client.checkout.removeDiscount') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute(
                            'content'),
                        'Content-Type': 'application/json',
                    },
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.reload();
                    } else {
                        alert('Xoá mã giảm giá thất bại');
                    }
                })
                .catch(() => alert('Lỗi kết nối, vui lòng thử lại'));
        });
    </script>
    <!-- Checkout section End -->
@endsection
