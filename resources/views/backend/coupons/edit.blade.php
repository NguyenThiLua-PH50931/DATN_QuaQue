@extends('layouts.backend')

@section('title', 'Chỉnh sửa mã giảm giá')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Chỉnh sửa mã giảm giá</h5>
                            </div>

                            <form class="theme-form theme-form-2 mega-form" method="POST"
                                action="{{ route('admin.coupon.update', $coupon->id) }}">
                                @csrf
                                @method('PUT')

                                <div class="row">

                                    {{-- Tiêu đề mã giảm giá --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Tiêu đề mã giảm giá</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="text" name="description"
                                                value="{{ old('description', $coupon->description) }}">
                                            @error('description')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Mã giảm giá --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Mã giảm giá</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="text" name="code"
                                                value="{{ old('code', $coupon->code) }}">
                                            @error('code')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Ngày bắt đầu --}}
                                    <div class="mb-4 row align-items-center">
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
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Ngày kết thúc</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="date" name="end_date"
                                                value="{{ old('end_date', optional($coupon->end_date)->format('Y-m-d')) }}">
                                            @error('end_date')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Số lượng giới hạn --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Số lượng</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="number" name="usage_limit"
                                                value="{{ old('usage_limit', $coupon->usage_limit) }}">
                                            @error('usage_limit')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Loại giảm giá --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Loại giảm giá</label>
                                        <div class="col-md-9 col-lg-10">
                                            <select class="form-select" name="discount_type">
                                                <option value="" disabled
                                                    {{ old('discount_type', $coupon->discount_type) == null ? 'selected' : '' }}>
                                                    --Chọn--</option>
                                                <option value="Phần trăm"
                                                    {{ old('discount_type', $coupon->discount_type) == 'Phần trăm' ? 'selected' : '' }}>
                                                    Phần trăm</option>
                                                <option value="Tiền cố định"
                                                    {{ old('discount_type', $coupon->discount_type) == 'Tiền cố định' ? 'selected' : '' }}>
                                                    Tiền cố định</option>
                                            </select>
                                            @error('discount_type')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Giá trị giảm --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá trị giảm</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="number" step="0.01" name="discount_value"
                                                value="{{ old('discount_value', $coupon->discount_value) }}">
                                            @error('discount_value')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Trạng thái --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Trạng thái</label>
                                        <div class="col-md-9 col-lg-10">
                                            <div class="form-check ps-0">
                                                <input class="form-check-input" type="checkbox" name="active"
                                                    value="1" {{ old('active', $coupon->active) ? 'checked' : '' }}>
                                                <label class="form-check-label">Kích hoạt mã giảm giá</label>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Giá trị đơn hàng tối thiểu --}}
                                    <div class="mb-4 row align-items-center">
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
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá trị giảm tối đa</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="number" step="0.01"
                                                name="max_discount_amount"
                                                value="{{ old('max_discount_amount', $coupon->max_discount_amount) }}">
                                            @error('max_discount_amount')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Số lần đã sử dụng (nếu bạn muốn hiển thị để quản trị) --}}
                                    <div class="mb-4 row align-items-center">
                                        <label class="form-label-title col-lg-2 col-md-3 mb-0">Số lần đã sử dụng</label>
                                        <div class="col-md-9 col-lg-10">
                                            <input class="form-control" type="number" name="used_count"
                                                value="{{ old('used_count', $coupon->used_count ?? 0) }}">
                                            @error('used_count')
                                                <div class="text-danger">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    {{-- Nút submit --}}
                                    <div class="d-flex justify-content-end gap-2 mt-4">
                                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                                        <a href="{{ route('admin.coupon.index') }}" class="btn btn-secondary">Quay
                                            lại</a>
                                    </div>


                                </div>
                            </form>

                        </div> <!-- end card-body -->
                    </div> <!-- end card -->
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
@endsection
