@extends('layouts.backend')

@section('title', 'Mã giảm giá')

@section('content')
    <!-- Coupon list table starts-->
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Danh sách mã giảm giá</h5>
                                <div class="right-options">
                                    <ul>
                                        <li>
                                            <a class="btn btn-solid" href="{{ route('admin.coupon.create') }}">Tạo mã giảm
                                                giá</a>
                                        </li>
                                    </ul>
                                </div>
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


                            {{--  Form lọc --}}
                            <form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <!-- Lọc theo trạng thái -->
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">--Trạng thái--</option>
                                            <option value="1" {{ $filterActive === '1' ? 'selected' : '' }}>Hiện
                                            </option>
                                            <option value="0" {{ $filterActive === '0' ? 'selected' : '' }}>
                                                Không hiện</option>
                                        </select>
                                    </div>
                                    <!-- Lọc theo loại mã -->
                                    <div class="col-md-2">
                                        <select name="type" class="form-control">
                                            <option value="">--Loại mã--</option>
                                            <option value="order_discount"
                                                {{ request('type') == 'order_discount' ? 'selected' : '' }}>Giảm giá đơn
                                                hàng</option>
                                            <option value="free_shipping"
                                                {{ request('type') == 'free_shipping' ? 'selected' : '' }}>Miễn phí vận
                                                chuyển</option>
                                        </select>
                                    </div>
                                    <!-- Lọc từ ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ $filterDateFrom }}">
                                    </div>
                                    <!-- Lọc đến ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ $filterDateTo }}">
                                    </div>

                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>

                            <div>
                                <div class="table-responsive overflow-hidden">
                                    <table class="table table-hover w-100 coupon-list-table theme-table" id="table_id">
                                        <thead>
                                            <tr>
                                                <th>Mã giảm giá</th>
                                                <th>Loại giảm giá</th>
                                                <th>Loại áp dụng</th>
                                                <th>Giá trị giảm</th>
                                                <th>Ngày bắt đầu</th>
                                                <th>Ngày kết thúc</th>
                                                <th>Trạng thái</th>
                                                <th>Tùy chọn</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($coupons as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>
                                                        @if ($coupon->type === 'free_shipping')
                                                            <span class="badge bg-danger">Miễn phí vận chuyển</span>
                                                        @elseif ($coupon->discount_type === 'percent')
                                                            <span class="badge bg-danger">Phần trăm</span>
                                                        @elseif ($coupon->discount_type === 'fixed')
                                                            <span class="badge bg-secondary">Tiền cố định</span>
                                                        @else
                                                            <span class="badge bg-warning">Không xác định</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        @if ($coupon->type === 'free_shipping')
                                                            <span class="badge bg-success">Miễn phí vận chuyển</span>
                                                        @else
                                                            <span class="badge bg-success">Giảm giá đơn hàng</span>
                                                        @endif

                                                    </td>

                                                    <td>
                                                        @if ($coupon->type === 'free_shipping')
                                                            -
                                                        @elseif ($coupon->discount_type === 'percent')
                                                            {{ $coupon->discount_value }}%
                                                        @else
                                                            {{ number_format($coupon->discount_value, 0, ',', '.') }} đ
                                                        @endif
                                                    </td>

                                                    <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>
                                                        @if ($coupon->active)
                                                            <span class="success">Hiện</span>
                                                        @else
                                                            <span class="danger">Không hiện</span>
                                                        @endif
                                                    </td>
                                                    <td>
                                                        <ul>
                                                            <li>
                                                                <a href="{{ route('admin.coupon.edit', $coupon->id) }}">
                                                                    <i class="ri-pencil-line"></i>
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#deleteModal{{ $coupon->id }}">
                                                                    <i class="ri-delete-bin-line"></i>
                                                                </a>
                                                                <!-- Delete Modal -->
                                                                <!-- Modal -->
                                                                <div class="modal fade" id="deleteModal{{ $coupon->id }}"
                                                                    tabindex="-1"
                                                                    aria-labelledby="deleteModalLabel{{ $coupon->id }}"
                                                                    aria-hidden="true">
                                                                    <div class="modal-dialog modal-dialog-centered">
                                                                        <div class="modal-content">
                                                                            <form method="POST"
                                                                                action="{{ route('admin.coupon.destroy', $coupon->id) }}">
                                                                                @csrf
                                                                                @method('DELETE')

                                                                                <div class="modal-header">
                                                                                    <h5 class="modal-title"
                                                                                        id="deleteModalLabel{{ $coupon->id }}">
                                                                                        Xác nhận xóa</h5>
                                                                                    <button type="button" class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Đóng"></button>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    Bạn có chắc chắn muốn xóa mã
                                                                                    <strong>{{ $coupon->code }}</strong>?
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button type="button"
                                                                                        class="btn btn-secondary"
                                                                                        data-bs-dismiss="modal">Hủy</button>
                                                                                    <button type="submit"
                                                                                        class="btn btn-danger">Xóa</button>
                                                                                </div>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                            </li>
                                                        </ul>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>

                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Container-fluid Ends-->
        @includeIf('backend.footer')
    </div>
@endsection
