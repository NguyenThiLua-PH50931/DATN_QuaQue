@extends('layouts.backend')

@section('title', 'Thuộc tính đã xóa mềm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="title-header option-title">
                                    <h5>Thuộc tính đã xóa mềm</h5>
                                </div>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.attributes.index') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="list"></i> Quay lại danh sách
                                    </a>
                                </form>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive category-table">
                                <table class="table all-package theme-table" id="attributeTable">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">ID</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tên Thuộc Tính</th>
                                            <th style="color: black; background-color: #f8f9fa;">Giá Trị Thuộc Tính</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($attributes as $attribute)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $attribute->id }}">
                                                </td>
                                                <td>{{ $attribute->id }}</td>
                                                <td>{{ $attribute->name }}</td>
                                                <td>
                                                    @php
                                                        $values = $attribute->values->pluck('value')->toArray();
                                                    @endphp
                                                    {{ implode(', ', $values) }}
                                                </td>
                                                <td>{{ $attribute->deleted_at->format('d-m-Y H:i:s') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-purple restore-btn"
                                                                data-id="{{ $attribute->id }}"
                                                                data-name="{{ $attribute->name }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-danger force-delete-btn"
                                                                data-id="{{ $attribute->id }}"
                                                                data-name="{{ $attribute->name }}">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="text-center">Không có thuộc tính nào đã bị xóa mềm.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <button type="button" id="bulk-restore-btn"
                                        class="align-items-center btn btn-purple d-flex me-2" style="display: none;">
                                        <i class="ri-refresh-line"></i> Khôi phục đã chọn
                                    </button>
                                    <button type="button" id="bulk-force-delete-btn"
                                        class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa vĩnh viễn đã chọn
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Restore Single Modal --}}
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục thuộc tính</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục thuộc tính <strong id="attributeNameRestore"></strong> không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="confirm-restore-single-btn">Khôi phục</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Force Delete Single Modal --}}
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn thuộc tính</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn thuộc tính <strong id="attributeNameForceDelete"></strong> không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-force-delete-single-btn">Xóa vĩnh viễn</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Restore Modal --}}
    <div class="modal fade" id="bulkRestoreModal" tabindex="-1" aria-labelledby="bulkRestoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkRestoreModalLabel">Xác nhận khôi phục hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục <span id="selectedRestoreCount"></span> thuộc tính đã chọn không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="confirm-bulk-restore-btn">Khôi phục hàng loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Force Delete Modal --}}
    <div class="modal fade" id="bulkForceDeleteModal" tabindex="-1" aria-labelledby="bulkForceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkForceDeleteModalLabel">Xác nhận xóa vĩnh viễn hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selectedForceDeleteCount"></span> thuộc tính đã chọn không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-force-delete-btn">Xóa vĩnh viễn hàng loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- No Selection Modal --}}
    <div class="modal fade" id="noSelectionModal" tabindex="-1" aria-labelledby="noSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noSelectionModalLabel">Thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Vui lòng chọn ít nhất một thuộc tính để thực hiện hành động này.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cannot Perform Action Modal --}}
    <div class="modal fade" id="cannotPerformActionModal" tabindex="-1" aria-labelledby="cannotPerformActionModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cannotPerformActionModalLabel">Lỗi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="cannotPerformActionMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')
@endsection

{{-- Include Toastr CSS and JS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- Include SweetAlert2 CSS and JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@push('scripts')
    <script>
        $(document).ready(function() {
            // Cấu hình Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "5000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };

            // Hiển thị thông báo thành công/lỗi từ session flash
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            $('#attributeTable').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ thuộc tính",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ thuộc tính",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy thuộc tính nào đã bị xóa mềm.",
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 5]
                }]
            });

            // Logic cho chức năng chọn tất cả và ẩn/hiện nút hành động hàng loạt
            $('#select-all-checkbox').change(function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkActionButtons();
            });

            $(document).on('change', '.row-checkbox', function() {
                toggleBulkActionButtons();
            });

            function toggleBulkActionButtons() {
                if ($('.row-checkbox:checked').length > 0) {
                    $('#bulk-force-delete-btn').show();
                    $('#bulk-restore-btn').show();
                } else {
                    $('#bulk-force-delete-btn').hide();
                    $('#bulk-restore-btn').hide();
                }
            }

            // Xử lý sự kiện click nút khôi phục từng thuộc tính
            $(document).on('click', '.restore-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#attributeNameRestore').text(name);
                $('#confirm-restore-single-btn').data('id', id); // Lưu ID vào nút confirm
                $('#restoreModal').modal('show');
            });

            // Xử lý xác nhận khôi phục (đơn lẻ)
            $(document).on('click', '#confirm-restore-single-btn', function() {
                var attributeId = $(this).data('id');
                $('#restoreModal').modal('hide');

                $.ajax({
                    url: '{{ url('admin/attributes') }}/' + attributeId + '/restore',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                        window.location.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Lỗi khi khôi phục thuộc tính.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Xử lý sự kiện click nút xóa vĩnh viễn từng thuộc tính
            $(document).on('click', '.force-delete-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#attributeNameForceDelete').text(name);
                $('#confirm-force-delete-single-btn').data('id', id); // Lưu ID vào nút confirm
                $('#forceDeleteModal').modal('show');
            });

            // Xử lý xác nhận xóa vĩnh viễn (đơn lẻ)
            $(document).on('click', '#confirm-force-delete-single-btn', function() {
                var attributeId = $(this).data('id');
                $('#forceDeleteModal').modal('hide');

                $.ajax({
                    url: '{{ url('admin/attributes') }}/' + attributeId + '/force',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        _method: 'DELETE' // Bắt buộc để Laravel nhận là DELETE
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                        window.location.reload();
                    },
                    error: function(xhr) {
                        let errorMessage = 'Lỗi khi xóa vĩnh viễn thuộc tính.';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Xử lý sự kiện click nút Khôi phục đã chọn (Bulk Restore)
            $('#bulk-restore-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedRestoreCount').text(selectedIds.length);
                    $('#bulkRestoreModal').modal('show');

                    $('#confirm-bulk-restore-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.attributes.bulkRestore') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds.join(',')
                            },
                            success: function(response) {
                                $('#bulkRestoreModal').modal('hide');
                                if (response.success) {
                                    toastr.success(response.message);
                                } else {
                                    toastr.error(response.message);
                                }
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkRestoreModal').modal('hide');
                                let errorMessage = 'Lỗi khi khôi phục hàng loạt thuộc tính.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    $('#noSelectionModal').modal('show');
                }
            });

            // Xử lý sự kiện click nút Xóa vĩnh viễn đã chọn (Bulk Force Delete)
            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedForceDeleteCount').text(selectedIds.length);
                    $('#bulkForceDeleteModal').modal('show');

                    $('#confirm-bulk-force-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.attributes.bulkForceDelete') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds.join(',')
                            },
                            success: function(response) {
                                $('#bulkForceDeleteModal').modal('hide');
                                if (response.success) {
                                    toastr.success(response.message);
                                } else if (response.status === 'warning') {
                                     toastr.warning(response.message);
                                } else {
                                    toastr.error(response.message);
                                }
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkForceDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn hàng loạt thuộc tính.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    $('#noSelectionModal').modal('show');
                }
            });

            // Hiển thị modal lỗi nếu có session error từ server (cho các trường hợp không phải AJAX)
            @if(session('error'))
                var errorMessage = "{{ session('error') }}";
                $('#cannotPerformActionMessage').text(errorMessage);
                $('#cannotPerformActionModal').modal('show');
            @endif
        });
    </script>
@endpush

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<style>
    .btn.btn-warning {
        background-color: #ffc107 !important; /* Màu vàng */
        border-color: #ffc107 !important;
        color: #212529 !important; /* Màu chữ đen */
    }

    .btn.btn-danger {
        background-color: #dc3545 !important; /* Màu đỏ */
        border-color: #dc3545 !important;
        color: #fff !important; /* Màu chữ trắng */
    }
    .btn.btn-success{
        background-color: #28a745 !important;
        border-color: #28a745 !important;
        color: #fff !important;
    }

    .btn.btn-purple {
        background-color: #6f42c1 !important; /* Màu tím */
        border-color: #6f42c1 !important;
        color: #fff !important;
    }
</style>
