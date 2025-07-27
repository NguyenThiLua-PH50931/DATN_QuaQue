@extends('layouts.backend')

@section('title', 'Sửa mã giảm giá')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Sửa mã giảm giá</h5>
                                    </div>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                    <form class="theme-form theme-form-2 mega-form" method="POST"
                                        action="{{ route('admin.coupon.update', $coupon->id) }}">
                                        @csrf
                                        @method('PUT')
                                        <div class="row">

                                            {{-- Tiêu đề mã giảm giá --}}
                                            <div class="mb-4 row align-items-center group-global">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Tiêu đề mã giảm
                                                    giá</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="text" name="description"
                                                        value="{{ old('description', $coupon->description) }}">
                                                    @error('description')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Mã giảm giá --}}
                                            <div class="mb-4 row align-items-center group-global">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Mã giảm giá</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="text" name="code"
                                                        value="{{ old('code', $coupon->code) }}">
                                                    @error('code')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Phạm vi áp dụng --}}
                                            <div class="mb-4 row align-items-center group-global">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Phạm vi áp
                                                    dụng</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <select class="form-select" name="scope" id="scope" required
                                                        @if (isset($coupon) && $coupon->id) disabled @endif>
                                                        <option value="global"
                                                            {{ old('scope', $coupon->scope) == 'global' ? 'selected' : '' }}>
                                                            Toàn hệ thống
                                                        </option>
                                                        <option value="conditional"
                                                            {{ old('scope', $coupon->scope) == 'conditional' ? 'selected' : '' }}>
                                                            Theo điều kiện
                                                        </option>
                                                    </select>
                                                    @if (isset($coupon) && $coupon->id)
                                                        <input type="hidden" name="scope" value="{{ $coupon->scope }}">
                                                    @endif
                                                </div>
                                            </div>

                                            {{-- Chọn điều kiện --}}
                                            <div class="mb-4 row align-items-center d-none" id="condition-type-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Chọn điều
                                                    kiện</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <select class="form-select" name="condition_type" id="condition_type"
                                                        @if (isset($coupon) && $coupon->id) disabled @endif>
                                                        <option value="">-- Chọn điều kiện --</option>
                                                        <option value="new_user_30d"
                                                            {{ old('condition_type', $coupon->condition_type) == 'new_user_30d' ? 'selected' : '' }}>
                                                            Tài khoản đăng ký dưới 30 ngày (Freeship)
                                                        </option>
                                                        <option value="first_order"
                                                            {{ old('condition_type', $coupon->condition_type) == 'first_order' ? 'selected' : '' }}>
                                                            Đơn hàng đầu tiên (Giảm giá đơn hàng)
                                                        </option>
                                                    </select>
                                                    @if (isset($coupon) && $coupon->id)
                                                        <input type="hidden" name="condition_type"
                                                            value="{{ $coupon->condition_type }}">
                                                    @endif
                                                    <div class="mb-3 d-none" id="condition-desc"></div>
                                                </div>
                                            </div>

                                            {{-- Ngày bắt đầu --}}
                                            <div class="mb-4 row align-items-center group-global" id="start-date-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Ngày bắt đầu</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="date" name="start_date"
                                                        value="{{ old('start_date', optional($coupon->start_date)->format('Y-m-d')) }}">
                                                    @error('start_date')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Ngày kết thúc --}}
                                            <div class="mb-4 row align-items-center group-global" id="end-date-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Ngày kết thúc</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="date" name="end_date"
                                                        value="{{ old('end_date', optional($coupon->end_date)->format('Y-m-d')) }}">
                                                    @error('end_date')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Số lượng (usage_limit) --}}
                                            <div class="mb-4 row align-items-center group-usage group-global"
                                                id="usage-limit-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Số lượng</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="number" name="usage_limit"
                                                        id="usage_limit"
                                                        value="{{ old('usage_limit', $coupon->usage_limit) }}"
                                                        min="1" required>
                                                    @error('usage_limit')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Loại mã --}}
                                            <div class="mb-4 row align-items-center group-discount group-freeship group-global"
                                                id="type-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Loại mã</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <select class="form-select" name="type" id="discount-type-select"
                                                        required>
                                                        <option value="order_discount"
                                                            {{ old('type', $coupon->type) == 'order_discount' ? 'selected' : '' }}>
                                                            Giảm giá đơn hàng</option>
                                                        <option value="free_shipping"
                                                            {{ old('type', $coupon->type) == 'free_shipping' ? 'selected' : '' }}>
                                                            Miễn phí vận chuyển</option>
                                                    </select>
                                                    @error('type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Loại giảm giá --}}
                                            <div class="mb-4 row align-items-center group-discount" id="discount-type-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Loại giảm giá</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <select class="form-select" name="discount_type">
                                                        <option disabled
                                                            {{ old('discount_type', $coupon->discount_type) ? '' : 'selected' }}>
                                                            --Chọn--</option>
                                                        <option value="percent"
                                                            {{ old('discount_type', $coupon->discount_type) == 'percent' ? 'selected' : '' }}>
                                                            Phần trăm</option>
                                                        <option value="fixed"
                                                            {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                                                            Tiền cố định</option>
                                                    </select>
                                                    @error('discount_type')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Giá trị giảm --}}
                                            <div class="mb-4 row align-items-center group-discount"
                                                id="discount-value-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá trị giảm</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="number" step="0.01"
                                                        name="discount_value"
                                                        value="{{ old('discount_value', $coupon->discount_value) }}">
                                                    @error('discount_value')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Giá trị đơn hàng tối thiểu --}}
                                            <div class="mb-4 row align-items-center group-discount" id="min-order-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá trị đơn hàng tối
                                                    thiểu</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="number" step="0.01"
                                                        name="min_order_amount"
                                                        value="{{ old('min_order_amount', $coupon->min_order_amount) }}">
                                                    @error('min_order_amount')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Giá trị giảm tối đa --}}
                                            <div class="mb-4 row align-items-center group-discount" id="max-discount-row">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá trị giảm tối
                                                    đa</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="number" step="0.01"
                                                        name="max_discount_amount"
                                                        value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                                    @error('max_discount_amount')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Số lần đã sử dụng --}}
                                            <div class="mb-4 row align-items-center group-discount group-global">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Số lần đã sử
                                                    dụng</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <input class="form-control" type="number" name="used_count"
                                                        value="{{ old('used_count', $coupon->used_count) }}" readonly>
                                                    @error('used_count')
                                                        <div class="text-danger">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            {{-- Trạng thái --}}
                                            <div class="row align-items-center group-global">
                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Trạng thái</label>
                                                <div class="col-md-9 col-lg-10">
                                                    <div class="form-check ps-0">
                                                        <input class="form-check-input" type="checkbox" name="active"
                                                            value="1"
                                                            {{ old('active', $coupon->active) ? 'checked' : '' }}>
                                                        <label class="form-check-label">Kích hoạt mã giảm giá</label>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Nút Submit --}}
                                            <div class="d-flex justify-content-end gap-2 mt-4 group-global">
                                                <button type="submit" class="btn btn-primary">Cập nhật mã giảm
                                                    giá</button>
                                                <a href="{{ route('admin.coupon.index') }}"
                                                    class="btn btn-secondary">Quay lại</a>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const conditionConfig = {
                    'new_user_30d': {
                        type: 'freeship',
                        showUsage: false
                    },
                    'first_order': {
                        type: 'discount',
                        showUsage: false
                    }
                };

                // Lấy các field
                const scopeSelect = document.getElementById('scope');
                const conditionTypeSelect = document.getElementById('condition_type');
                const typeSelect = document.getElementById('discount-type-select');
                const discountTypeSelect = document.querySelector('select[name="discount_type"]');
                const discountTypeRow = document.getElementById('discount-type-row') || discountTypeSelect?.closest(
                    '.row');
                const discountValueRow = document.getElementById('discount-value-row') || document.querySelector(
                    'input[name="discount_value"]')?.closest('.row');
                const minOrderRow = document.getElementById('min-order-row') || document.querySelector(
                    'input[name="min_order_amount"]')?.closest('.row');
                const maxDiscountRow = document.getElementById('max-discount-row') || document.querySelector(
                    'input[name="max_discount_amount"]')?.closest('.row');
                const maxDiscountInput = document.querySelector('input[name="max_discount_amount"]');
                const startDateRow = document.getElementById('start-date-row') || document.querySelector(
                    'input[name="start_date"]')?.closest('.row');
                const endDateRow = document.getElementById('end-date-row') || document.querySelector(
                    'input[name="end_date"]')?.closest('.row');
                const usageInput = document.querySelector('input[name="usage_limit"]');
                const usageRow = usageInput?.closest('.row');
                const condRow = document.getElementById('condition-type-row');

                // Toggle hiện/ẩn max_discount_amount field
                function toggleMaxDiscountField() {
                    if (!discountTypeSelect || !maxDiscountRow) return;
                    if (discountTypeSelect.value === 'percent') {
                        maxDiscountRow.classList.remove('d-none');
                        if (maxDiscountInput) maxDiscountInput.required = true;
                    } else {
                        maxDiscountRow.classList.add('d-none');
                        if (maxDiscountInput) {
                            maxDiscountInput.required = false;
                            maxDiscountInput.value = '';
                        }
                    }
                }

                function updateFormFields() {
                    const scope = scopeSelect.value;
                    const conditionType = conditionTypeSelect?.value || null;

                    // Luôn cho phép gửi usage_limit (không set disabled!)
                    if (usageInput) {
                        usageInput.readOnly = false;
                        usageInput.disabled = false;
                        usageInput.style.background = '';
                    }

                    if (scope === 'conditional') {
                        condRow?.classList.remove('d-none');
                        if (conditionType && conditionConfig[conditionType]) {
                            // Ẩn ngày
                            startDateRow?.classList.add('d-none');
                            endDateRow?.classList.add('d-none');
                            // Chọn & khóa loại mã
                            if (typeSelect) {
                                typeSelect.value = (conditionConfig[conditionType].type === 'freeship') ?
                                    'free_shipping' :
                                    'order_discount';
                                typeSelect.readOnly = true;
                                typeSelect.style.pointerEvents = 'none';
                                typeSelect.style.background = '#f5f5f5';
                            }
                            // Hiện/ẩn field discount/freeship
                            if (conditionConfig[conditionType].type === 'discount') {
                                discountTypeRow?.classList.remove('d-none');
                                discountValueRow?.classList.remove('d-none');
                                minOrderRow?.classList.remove('d-none');
                                // toggle maxDiscount theo loại giảm giá
                                toggleMaxDiscountField();
                            } else if (conditionConfig[conditionType].type === 'freeship') {
                                discountTypeRow?.classList.add('d-none');
                                discountValueRow?.classList.add('d-none');
                                minOrderRow?.classList.remove('d-none'); // HIỆN
                                maxDiscountRow?.classList.add('d-none');
                            }
                            // usage_limit = 1, readonly, KHÔNG disabled!
                            usageRow?.classList.remove('d-none');
                            if (usageInput) {
                                usageInput.value = 1;
                                usageInput.readOnly = true;
                                usageInput.style.background = "#f5f5f5";
                            }
                        } else {
                            // Chưa chọn điều kiện cụ thể
                            if (typeSelect) {
                                typeSelect.readOnly = true;
                                typeSelect.style.pointerEvents = 'none';
                                typeSelect.style.background = '#f5f5f5';
                                typeSelect.value = '';
                            }
                            discountTypeRow?.classList.add('d-none');
                            discountValueRow?.classList.add('d-none');
                            minOrderRow?.classList.add('d-none');
                            maxDiscountRow?.classList.add('d-none');
                            usageRow?.classList.remove('d-none');
                            if (usageInput) {
                                usageInput.value = 1;
                                usageInput.readOnly = true;
                                usageInput.style.background = "#f5f5f5";
                            }
                            startDateRow?.classList.add('d-none');
                            endDateRow?.classList.add('d-none');
                        }
                    } else {
                        // GLOBAL (toàn hệ thống)
                        condRow?.classList.add('d-none');
                        if (typeSelect) {
                            typeSelect.readOnly = false;
                            typeSelect.style.pointerEvents = 'auto';
                            typeSelect.style.background = '';
                        }
                        // Nếu là miễn phí vận chuyển toàn hệ thống: hiện minOrderRow, ẩn field giảm giá
                        if (typeSelect?.value === 'free_shipping') {
                            discountTypeRow?.classList.add('d-none');
                            discountValueRow?.classList.add('d-none');
                            minOrderRow?.classList.remove('d-none'); // luôn hiện!
                            maxDiscountRow?.classList.add('d-none');
                        } else {
                            discountTypeRow?.classList.remove('d-none');
                            discountValueRow?.classList.remove('d-none');
                            minOrderRow?.classList.remove('d-none');
                            // toggle maxDiscount theo loại giảm giá
                            toggleMaxDiscountField();
                        }
                        startDateRow?.classList.remove('d-none');
                        endDateRow?.classList.remove('d-none');
                        usageRow?.classList.remove('d-none');
                        if (usageInput) {
                            usageInput.readOnly = false;
                            usageInput.style.background = '';
                            if (!usageInput.value || usageInput.value < 1) usageInput.value = 1;
                        }
                    }
                }

                scopeSelect.addEventListener('change', updateFormFields);
                conditionTypeSelect?.addEventListener('change', updateFormFields);
                typeSelect?.addEventListener('change', updateFormFields);
                if (discountTypeSelect) {
                    discountTypeSelect.addEventListener('change', toggleMaxDiscountField);
                }

                // Gọi 1 lần lúc đầu để set đúng trạng thái
                updateFormFields();
                toggleMaxDiscountField();
            });
        </script>
    @endpush
    @includeIf('backend.footer')
@endsection
