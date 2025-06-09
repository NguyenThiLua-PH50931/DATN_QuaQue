@extends('layouts.backend')

@section('title', 'Thêm mã giảm giá')

@section('content')
    <!-- Create Coupon Table start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card">
                                <div class="card-body">
                                    <div class="title-header option-title">
                                        <h5>Tạo mã giảm giá</h5>
                                    </div>
                                    <div></div>
                                    <div class="tab-content" id="pills-tabContent">
                                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                            <form class="theme-form theme-form-2 mega-form" method="POST"
                                                action="{{ route('admin.coupon.store') }}">
                                                @csrf
                                                <div class="tab-content" id="pills-tabContent">

                                                    <div class="tab-pane fade show active" id="pills-home" role="tabpanel">
                                                        <div class="row">
                                                            <!-- Tiêu đề mã giảm giá -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Tiêu
                                                                    đề mã giảm giá</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="text"
                                                                        name="description" value="{{ old('description') }}">
                                                                    @error('description')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Mã giảm giá -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label
                                                                    class="col-lg-2 col-md-3 col-form-label form-label-title">Mã
                                                                    giảm giá</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="text"
                                                                        name="code" value="{{ old('code') }}">
                                                                    @error('code')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Ngày bắt đầu -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label
                                                                    class="col-lg-2 col-md-3 col-form-label form-label-title">Ngày
                                                                    bắt đầu</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="date"
                                                                        name="start_date" value="{{ old('start_date') }}">
                                                                    @error('start_date')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Ngày kết thúc -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label
                                                                    class="col-lg-2 col-md-3 col-form-label form-label-title">Ngày
                                                                    kết thúc</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="date"
                                                                        name="end_date" value="{{ old('end_date') }}">
                                                                    @error('end_date')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>


                                                            <!-- Số lượng (giới hạn sử dụng) -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label
                                                                    class="col-lg-2 col-md-3 col-form-label form-label-title">Số
                                                                    lượng</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="number"
                                                                        name="usage_limit" value="{{ old('usage_limit') }}">
                                                                    @error('usage_limit')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Loại giảm giá -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="col-sm-2 col-form-label form-label-title">Loại
                                                                    giảm giá</label>
                                                                <div class="col-sm-10">
                                                                    <select class="form-select" name="discount_type">
                                                                        <option disabled
                                                                            {{ old('discount_type') ? '' : 'selected' }}>
                                                                            --Chọn--</option>
                                                                        <option value="percent"
                                                                            {{ old('discount_type') == 'percent' ? 'selected' : '' }}>
                                                                            Phần trăm</option>
                                                                        <option value="fixed"
                                                                            {{ old('discount_type') == 'fixed' ? 'selected' : '' }}>
                                                                            Tiền cố định</option>
                                                                    </select>
                                                                    @error('discount_type')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Giá trị giảm -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="col-sm-2 col-form-label form-label-title">Giá
                                                                    trị giảm</label>
                                                                <div class="col-sm-10">
                                                                    <input class="form-control" type="number"
                                                                        step="0.01" name="discount_value"
                                                                        value="{{ old('discount_value') }}">
                                                                    @error('discount_value')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Trạng thái -->
                                                            <div class="row align-items-center">
                                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Trạng
                                                                    thái</label>
                                                                <div class="col-md-9">
                                                                    <div class="form-check user-checkbox ps-0">
                                                                        <input class="form-check-input" type="checkbox"
                                                                            name="active" value="1"
                                                                            {{ old('active') ? 'checked' : '' }}>
                                                                        <label class="form-check-label">Kích hoạt mã giảm
                                                                            giá</label>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <!-- Thêm phần giới hạn vào đây -->

                                                            <!-- Giá trị đơn hàng tối thiểu -->
                                                            <div class="mb-4 row align-items-center mt-4">
                                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá
                                                                    trị đơn hàng tối thiểu</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="number"
                                                                        step="0.01" name="min_order_amount"
                                                                        value="{{ old('min_order_amount') }}">
                                                                    @error('min_order_amount')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Giá trị giảm tối đa -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Giá
                                                                    trị giảm tối đa</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="number"
                                                                        step="0.01" name="max_discount_amount"
                                                                        value="{{ old('max_discount_amount') }}">
                                                                    @error('max_discount_amount')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            <!-- Số lần đã sử dụng -->
                                                            <div class="mb-4 row align-items-center">
                                                                <label class="form-label-title col-lg-2 col-md-3 mb-0">Số
                                                                    lần đã sử dụng</label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <input class="form-control" type="number"
                                                                        name="used_count"
                                                                        value="{{ old('used_count', 0) }}">
                                                                    @error('used_count')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>

                                                            {{-- Sử dụng cho sản phẩm nào --}}
                                                            <div class="mb-4 row align-items-center">
                                                                <label for="products"
                                                                    class="form-label-title col-lg-2 col-md-3 mb-0">
                                                                    Sử dụng cho sản phẩm
                                                                </label>
                                                                <div class="col-md-9 col-lg-10">
                                                                    <select name="products[]" multiple
                                                                        class="form-control">
                                                                        @foreach ($products as $product)
                                                                            <option value="{{ $product->id }}">
                                                                                {{ $product->name }}
                                                                            </option>
                                                                        @endforeach
                                                                    </select>
                                                                    @error('products')
                                                                        <div class="text-danger">{{ $message }}</div>
                                                                    @enderror
                                                                </div>
                                                            </div>


                                                        </div>
                                                    </div>

                                                    <!-- Nếu bạn vẫn muốn giữ 2 tab còn lại, có thể xóa hoặc để không chứa gì -->

                                                    <div class="tab-pane fade" id="pills-profile" role="tabpanel">
                                                        <!-- Có thể bỏ trống hoặc thêm nội dung khác -->
                                                    </div>

                                                    <div class="tab-pane fade" id="pills-usage" role="tabpanel">
                                                        <!-- Có thể bỏ trống hoặc thêm nội dung khác -->
                                                    </div>

                                                </div>
                                                <div class="d-flex justify-content-end gap-2 mt-4">
                                                    <button type="submit" class="btn btn-primary">Tạo mã giảm
                                                        giá</button>
                                                    <a href="{{ route('admin.coupon.index') }}"
                                                        class="btn btn-secondary">Quay
                                                        lại</a>
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
        </div>
    </div>
    <!-- Create Coupon Table End -->
    @includeIf('backend.footer')
    </div>
@endsection
