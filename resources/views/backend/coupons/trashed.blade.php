@extends('layouts.backend')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-flex justify-content-between align-items-center">
                                <h5>Thùng rác mã giảm giá</h5>
                               <form class="d-inline-flex">
                                    <a href="{{ route('admin.coupon.index') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="list"></i> Quay lại danh sách
                                    </a>
                                    <button type="button" id="check-auto-delete-btn" class="btn btn-info ms-2">
                                        <i class="ri-time-line"></i> Kiểm tra tự động xóa
                                    </button>
                                </form>
                            </div>

                            <small class="text-muted">
                                <i class="ri-information-line"></i>
                                <strong>Lưu ý:</strong> Các mã giảm giá đã xóa mềm sẽ được tự động xóa vĩnh viễn sau 30
                                ngày.
                            </small>

                            {{-- Filter cơ bản (tuỳ chọn) --}}
                            <form method="GET" action="{{ route('admin.coupon.trashed') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <input type="text" name="q" class="form-control"
                                            placeholder="Tìm kiếm code, mô tả..." value="{{ $q }}">
                                    </div>
                                    <div class="col-md-2">
                                        <select name="type" class="form-control">
                                            <option value="">--Loại mã--</option>
                                            <option value="order_discount"
                                                {{ $filterType == 'order_discount' ? 'selected' : '' }}>Giảm đơn hàng
                                            </option>
                                            <option value="free_shipping"
                                                {{ $filterType == 'free_shipping' ? 'selected' : '' }}>
                                                Miễn phí VC</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <select name="discount_type" class="form-control">
                                            <option value="">--Hình thức--</option>
                                            <option value="percent"
                                                {{ $filterDiscountType == 'percent' ? 'selected' : '' }}>Phần
                                                trăm</option>
                                            <option value="fixed" {{ $filterDiscountType == 'fixed' ? 'selected' : '' }}>
                                                Tiền cố
                                                định</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ $filterDateFrom }}" placeholder="Xóa từ">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ $filterDateTo }}" placeholder="Xóa đến">
                                    </div>
                                    <div class="col-md-1">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive overflow-auto">
                                <table class="table table-hover w-100 theme-table">
                                    <thead>
                                        <tr>
                                            <th>Mã</th>
                                            <th>Mô tả</th>
                                            <th>Loại</th>
                                            <th>Hình thức</th>
                                            <th>Đã dùng/Tổng</th>
                                            <th>Ngày xóa</th>
                                            <th class="text-center">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($coupons as $coupon)
                                            <tr>
                                                <td><b title="{{ $coupon->code }}">{{ \Str::limit($coupon->code, 20) }}</b>
                                                </td>
                                                <td title="{{ $coupon->description }}">
                                                    {{ \Str::limit($coupon->description, 30) }}</td>
                                                <td>
                                                    @if ($coupon->type == 'free_shipping')
                                                        <span class="badge badge-green">Freeship</span>
                                                    @else
                                                        <span class="badge badge-blue">Giảm đơn hàng</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($coupon->type == 'free_shipping')
                                                        <span class="badge badge-green">Freeship</span>
                                                    @elseif ($coupon->discount_type == 'percent')
                                                        <span
                                                            class="badge badge-yellow">{{ (int) $coupon->discount_value }}%</span>
                                                    @elseif ($coupon->discount_type == 'fixed')
                                                        <span
                                                            class="badge badge-purple">{{ number_format($coupon->discount_value, 0, ',', '.') }}₫</span>
                                                    @else
                                                        <span class="badge badge-gray">?</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span
                                                        class="badge badge-purple">{{ $coupon->used_count ?? 0 }}/{{ $coupon->usage_limit }}</span>
                                                </td>
                                                <td>{{ $coupon->deleted_at ? $coupon->deleted_at->format('d/m/Y H:i') : '-' }}
                                                </td>
                                                <td class="text-center">
                                                    <div class="d-flex gap-2 justify-content-center">
                                                        {{-- Khôi phục --}}
                                                        <form method="POST"
                                                            action="{{ route('admin.coupon.restore', $coupon->id) }}">
                                                            @csrf @method('PUT')
                                                            <button type="submit" class="btn btn-sm btn-success"
                                                                title="Khôi phục">
                                                                <i class="ri-history-line"></i>
                                                            </button>
                                                        </form>

                                                        {{-- Xóa vĩnh viễn (modal xác nhận) --}}
                                                        <button type="button" class="btn btn-sm btn-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#forceDeleteModal{{ $coupon->id }}"
                                                            title="Xóa vĩnh viễn">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </button>

                                                        <div class="modal fade" id="forceDeleteModal{{ $coupon->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="forceDeleteLabel{{ $coupon->id }}"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-dialog-centered">
                                                                <div class="modal-content">
                                                                    <form method="POST"
                                                                        action="{{ route('admin.coupon.force-delete', $coupon->id) }}">
                                                                        @csrf @method('DELETE')
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="forceDeleteLabel{{ $coupon->id }}">
                                                                                Xác nhận xóa vĩnh viễn</h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Đóng"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            Bạn chắc chắn muốn <strong>xóa vĩnh
                                                                                viễn</strong> mã
                                                                            <b>{{ $coupon->code }}</b>? Hành động này không
                                                                            thể khôi phục.
                                                                        </div>
                                                                        <div class="modal-footer">
                                                                            <button type="button" class="btn btn-secondary"
                                                                                data-bs-dismiss="modal">Hủy</button>
                                                                            <button type="submit"
                                                                                class="btn btn-danger">Xóa vĩnh
                                                                                viễn</button>
                                                                        </div>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Thùng rác trống!</td>
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
    </div>

    {{-- Auto Delete Status Modal --}}
    <div class="modal fade" id="autoDeleteStatusModal" tabindex="-1" aria-labelledby="autoDeleteStatusModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="autoDeleteStatusModalLabel">Trạng thái tự động xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="autoDeleteStatusContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            // Xử lý nút kiểm tra tự động xóa
            $('#check-auto-delete-btn').click(function() {
                console.log('Checking auto delete status...');
                $.ajax({
                    url: '{{ route('admin.coupon.checkAutoDeleteStatus') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Auto delete status response:', response);
                        let content = '<div class="row">';
                        content +=
                            '<div class="col-md-6"><strong>Tổng số mã giảm giá đã xóa:</strong> ' +
                            response.total + '</div>';
                        content +=
                            '<div class="col-md-6"><strong>Sắp được xóa (≤7 ngày):</strong> ' +
                            response.will_be_deleted_soon + '</div>';
                        content += '</div><hr>';

                        if (response.days_until_auto_delete.length > 0) {
                            content +=
                                '<h6>Chi tiết:</h6><div class="table-responsive"><table class="table table-sm">';
                            content +=
                                '<thead><tr><th>Mã giảm giá</th><th>Còn lại</th><th>Ngày xóa</th></tr></thead><tbody>';

                            response.days_until_auto_delete.forEach(function(item) {
                                const badgeClass = item.days_left <= 7 ? 'bg-danger' :
                                    'bg-warning';
                                content += '<tr>';
                                content += '<td>' + item.code + '</td>';
                                content += '<td><span class="badge ' + badgeClass +
                                    '">' + item.days_left + ' ngày</span></td>';
                                content += '<td>' + item.auto_delete_at + '</td>';
                                content += '</tr>';
                            });

                            content += '</tbody></table></div>';
                        } else {
                            content +=
                                '<p class="text-muted">Không có mã giảm giá nào trong thùng rác.</p>';
                        }

                        $('#autoDeleteStatusContent').html(content);
                        $('#autoDeleteStatusModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error checking auto delete status:', error);
                        $('#autoDeleteStatusContent').html(
                            '<div class="alert alert-danger">Lỗi khi kiểm tra trạng thái tự động xóa.</div>'
                            );
                        $('#autoDeleteStatusModal').modal('show');
                    }
                });
            });
        });
    </script>
@endpush
