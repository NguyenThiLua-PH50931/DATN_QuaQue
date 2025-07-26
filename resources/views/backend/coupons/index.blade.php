@extends('layouts.backend')

@section('title', 'Mã giảm giá')

@section('content')
    <style>
        .coupon-list-table th,
        .coupon-list-table td {
            font-size: 15px;
            color: #32475b;
            padding: 10px 12px !important;
            vertical-align: middle !important;
            background: transparent;
            border: none;
            max-width: 145px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            font-weight: 500;
        }

        .coupon-list-table th {
            background: #f7f7f7;
            font-weight: 700;
            color: #29355b;
            border-bottom: 1.5px solid #e5eaf2 !important;
        }

        .coupon-list-table tr {
            border-radius: 8px !important;
            background: #fff;
            border-bottom: 8px solid #fafbfc !important;
        }

        /* Badge style giống bảng orders */
        .badge {
            background: #f3f4f6;
            color: #344767;
            font-size: 14px;
            padding: 4px 12px;
            border-radius: 8px;
            font-weight: 500;
            box-shadow: none;
            display: inline-block;
        }

        .badge-yellow {
            background: #ffe6b1;
            color: #b68900;
        }

        .badge-blue {
            background: #b2e4ff;
            color: #096ea6;
        }

        .badge-purple {
            background: #d1c3ff;
            color: #684db5;
        }

        .badge-green {
            background: #d1ffd6;
            color: #22863a;
        }

        .badge-gray {
            background: #eaeaea;
            color: #6e7787;
        }

        .badge-orange {
            background: #ffe1cc;
            color: #c46a1c;
        }

        /* Nút icon */
        .coupon-actions {
            display: flex;
            gap: 6px;
            align-items: center;
            justify-content: center;
        }

        .coupon-action-btn {
            width: 32px;
            height: 32px;
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #f3f4f6;
            color: #32475b;
            transition: background 0.13s;
            font-size: 17px;
            padding: 0;
        }

        .coupon-action-btn:hover {
            background: #e7eaf1;
            color: #1a63c4;
        }

        .coupon-action-btn.delete:hover {
            background: #ffeaea;
            color: #e41d1d;
        }

        .condition-col {
            max-width: 115px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .condition-col .badge {
            max-width: 110px;
            display: inline-block;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            font-size: 14px;
        }
    </style>


    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-flex justify-content-between align-items-center">
                                <h5>Danh sách mã giảm giá</h5>
                                <a class="btn btn-solid" href="{{ route('admin.coupon.create') }}">
                                    <i class="ri-add-line"></i> Tạo mã giảm giá
                                </a>
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

                            {{-- Filter --}}
                            <form method="GET" action="{{ route('admin.coupon.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-2">
                                        <input type="text" name="q" class="form-control"
                                            placeholder="Tìm kiếm code, mô tả..." value="{{ request('q') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="active" class="form-control">
                                            <option value="">--Trạng thái--</option>
                                            <option value="1" {{ $filterActive === '1' ? 'selected' : '' }}>Hiện
                                            </option>
                                            <option value="0" {{ $filterActive === '0' ? 'selected' : '' }}>Không hiện
                                            </option>
                                        </select>
                                    </div>
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
                                    <div class="col-md-2">
                                        <select name="scope" class="form-control">
                                            <option value="">--Phạm vi áp dụng--</option>
                                            <option value="global" {{ request('scope') == 'global' ? 'selected' : '' }}>Toàn
                                                hệ thống</option>
                                            <option value="conditional"
                                                {{ request('scope') == 'conditional' ? 'selected' : '' }}>Theo điều kiện
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ $filterDateFrom }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ $filterDateTo }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>



                            <div class="table-responsive overflow-auto">
                                <table class="table table-hover w-100 coupon-list-table theme-table">
                                    <thead>
                                        <tr>
                                            <th>Mã</th>
                                            <th>Mô tả</th>
                                            <th>Phạm vi</th>
                                            <th>Điều kiện</th>
                                            <th>Loại</th>
                                            <th>Số lượng</th>
                                            <th>Ngày bắt đầu</th>
                                            <th>Ngày kết thúc</th>
                                            <th class="text-center">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coupons as $coupon)
                                            <tr>
                                                <td title="{{ $coupon->code }}">
                                                    <b>{{ \Str::limit($coupon->code, 20) }}</b>
                                                </td>
                                                <td title="{{ $coupon->description }}">
                                                    {{ \Str::limit($coupon->description, 30) }}</td>
                                                <td>
                                                    @if ($coupon->scope == 'global')
                                                        <span class="badge badge-blue">Hệ thống</span>
                                                    @elseif($coupon->scope == 'conditional')
                                                        <span class="badge badge-yellow">Điều kiện</span>
                                                    @else
                                                        <span class="badge badge-gray">{{ $coupon->scope }}</span>
                                                    @endif
                                                </td>
                                                <td class="condition-col">
                                                    @if ($coupon->scope == 'conditional')
                                                        @switch($coupon->condition_type)
                                                            @case('new_user_30d')
                                                                <span class="badge badge-gray" title="Tài khoản dưới 30 ngày">Tài
                                                                    khoản &lt; 30 ngày</span>
                                                            @break

                                                            @case('first_order')
                                                                <span class="badge badge-gray" title="Đơn hàng đầu tiên">Đơn hàng
                                                                    đầu tiên</span>
                                                            @break

                                                            @default
                                                                <span>-</span>
                                                        @endswitch
                                                    @else
                                                        <span>-</span>
                                                    @endif
                                                </td>

                                                <td>
                                                    @if ($coupon->type == 'free_shipping')
                                                        <span class="badge badge-green"
                                                            title="Miễn phí vận chuyển">🚚</span>
                                                    @elseif($coupon->discount_type == 'percent')
                                                        <span class="badge badge-yellow"
                                                            title="Giảm theo phần trăm">%</span>
                                                    @elseif($coupon->discount_type == 'fixed')
                                                        <span class="badge badge-purple" title="Giảm theo số tiền">₫</span>
                                                    @else
                                                        <span class="badge badge-gray" title="Không xác định">?</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="badge badge-purple">
                                                        {{ $coupon->used_count ?? 0 }}/{{ $coupon->usage_limit }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $coupon->start_date ? \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td>
                                                    {{ $coupon->end_date ? \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="coupon-actions">
                                                        <a href="{{ route('admin.coupon.edit', $coupon->id) }}"
                                                            class="coupon-action-btn" title="Sửa">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                        <button type="button" class="coupon-action-btn delete"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $coupon->id }}"
                                                            title="Xóa">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>
                                                    </div>
                                                    <!-- Modal Xóa -->
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
                                                                            id="deleteModalLabel{{ $coupon->id }}">Xác
                                                                            nhận xóa</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Đóng"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        Bạn có chắc chắn muốn xóa mã
                                                                        <strong>{{ $coupon->code }}</strong>?
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary"
                                                                            data-bs-dismiss="modal">Hủy</button>
                                                                        <button type="submit"
                                                                            class="btn btn-danger">Xóa</button>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="9" class="text-center text-muted">Không có mã giảm giá
                                                        nào!</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>

                                </div>
                                <div class="d-flex justify-content-end mt-2">
                                    {{ $coupons->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @includeIf('backend.footer')
        </div>
    @endsection
