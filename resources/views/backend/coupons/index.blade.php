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
                            <div>
                                <div class="table-responsive">
                                    <table class="table all-package coupon-list-table table-hover theme-table"
                                        id="table_id">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Mã giảm giá</th>

                                                <th>Mô tả</th>
                                                <th>Loại giảm giá</th>
                                                <th>Giá trị giảm</th>
                                                <th>Giá trị đơn tối thiểu</th>
                                                <th>Giảm tối đa</th>
                                                <th>Ngày bắt đầu</th>
                                                <th>Ngày kết thúc</th>
                                                <th>Giới hạn sử dụng</th>
                                                <th>Đã sử dụng</th>
                                                <th>Trạng thái</th>
                                                <th>Ngày tạo</th>
                                                <th>Ngày cập nhật</th>
                                                <th>Tùy chọn</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach ($coupons as $coupon)
                                                <tr>
                                                    <td>{{ $coupon->id }}</td>
                                                    <td>{{ $coupon->code }}</td>
                                                    <td>{{ $coupon->description }}</td>
                                                    <td>{{ ucfirst($coupon->discount_type) }}</td>
                                                    <td>{{ $coupon->discount_value }}{{ $coupon->discount_type == 'Phần trăm' ? '%' : ' đ' }}
                                                    </td>
                                                    <td>{{ number_format($coupon->min_order_amount) }} đ</td>
                                                    <td>{{ number_format($coupon->max_discount_amount) }} đ</td>
                                                    <td>{{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
                                                    </td>
                                                    <td>{{ $coupon->usage_limit }}</td>
                                                    <td>{{ $coupon->used_count }}</td>
                                                    <td>
                                                        @if ($coupon->active)
                                                            <span class="success">Hiện</span>
                                                        @else
                                                            <span class="danger">Không hiện</span>
                                                        @endif
                                                    </td>
                                                    <td>{{ $coupon->created_at->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $coupon->updated_at->format('d/m/Y H:i') }}</td>
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
@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ phiếu giảm giá",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ phiếu giảm giá",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy phiếu giảm giá nào.",
                }
            });
        });
    </script>
@endpush
