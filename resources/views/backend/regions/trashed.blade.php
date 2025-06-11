@extends('layouts.backend')

@section('title', 'Vùng miền đã xóa')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Vùng miền đã xóa</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.regions.index') }}"
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

                            <div class="table-responsive region-table">
                                <table class="table all-package theme-table" id="trashed_regions_table">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">Tên vùng miền</th>
                                            <th style="color: black; background-color: #f8f9fa;">Slug</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($regions as $region)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]" value="{{ $region->id }}">
                                                </td>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->slug }}</td>
                                                <td>{{ $region->deleted_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <button type="button" class="btn btn-success btn-sm restore-btn"
                                                                data-id="{{ $region->id }}" data-name="{{ $region->name }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="btn btn-danger btn-sm force-delete-btn"
                                                                data-id="{{ $region->id }}" data-name="{{ $region->name }}">
                                                                <i data-feather="trash-2"></i> Xoá vĩnh viễn
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Không có vùng miền đã xóa.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                 <form class="d-inline-flex">
                                    <button type="button" id="bulk-restore-btn"
                                        class="align-items-center btn btn-success d-flex me-2" style="display: none;">
                                        <i class="ri-refresh-line"></i> Khôi phục đã chọn
                                    </button>
                                    <button type="button" id="bulk-force-delete-btn" class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa đã chọn (vĩnh viễn)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add any necessary modals here, e.g., confirmation modals -->

    </div>

    {{-- Bulk Restore Modal --}}
    <div class="modal fade" id="bulkRestoreModal" tabindex="-1" aria-labelledby="bulkRestoreModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkRestoreModalLabel">Xác nhận khôi phục hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục <span id="selectedTrashedRegionCountRestore"></span> vùng miền
                    đã chọn không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="confirm-bulk-restore-btn">Khôi phục hàng loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cannot Delete Modal (for trashed regions) --}}
    <div class="modal fade" id="cannotDeleteModal" tabindex="-1" aria-labelledby="cannotDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="cannotDeleteModalLabel">Lỗi xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span id="cannotDeleteMessage"></span>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    @includeIf('backend.footer')

    <!-- Modal xác nhận khôi phục -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục vùng miền <span id="restoreRegionName"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="restoreForm" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Khôi phục</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal xác nhận xóa vĩnh viễn -->
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn vùng miền <span id="forceDeleteRegionName"></span>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="forceDeleteForm" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                    </form>
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
                    Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selectedTrashedRegionCount"></span> vùng miền đã chọn không? Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-force-delete-btn">Xóa vĩnh viễn hàng loạt</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    {{-- Include Toastr CSS and JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance
            const table = $('#trashed_regions_table').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ vùng miền đã xóa",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ vùng miền đã xóa",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy vùng miền đã xóa nào.",
                }
            });

            // Modal xác nhận khôi phục
            $('.restore-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#restoreRegionName').text('"' + name + '"');
                $('#restoreForm').attr('action', '/admin/regions/' + id + '/restore');
                $('#restoreModal').modal('show');
            });
            // Modal xác nhận xóa vĩnh viễn
            $('.force-delete-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#forceDeleteRegionName').text('"' + name + '"');
                $('#forceDeleteForm').attr('action', '/admin/regions/' + id + '/force');
                $('#forceDeleteForm').data('row-id', id);
                $('#forceDeleteModal').modal('show');
            });
            // Xử lý submit form xóa vĩnh viễn bằng AJAX
            $('#forceDeleteForm').submit(function(e) {
                e.preventDefault();
                var form = $(this);
                var url = form.attr('action');
                var id = form.data('row-id');
                var row = $('button.force-delete-btn[data-id="' + id + '"]').closest('tr');
                $.ajax({
                    url: url,
                    method: 'DELETE',
                    data: form.serialize(),
                    success: function(response) {
                        $('#forceDeleteModal').modal('hide');
                        if (row.length) {
                            row.remove();
                        }
                        toastr.success(response.message || 'Xóa vĩnh viễn vùng miền thành công!');
                    },
                    error: function(xhr) {
                        $('#forceDeleteModal').modal('hide');
                        let errorMessage = 'Lỗi khi xóa vĩnh viễn vùng miền';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errorMessage = xhr.responseJSON.message;
                        } else if (xhr.responseText) {
                            errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) +
                                '...';
                        } else {
                            errorMessage = 'Lỗi không xác định';
                        }
                        toastr.error(errorMessage);
                    }
                });
            });

            // Logic cho chức năng chọn tất cả và xóa hàng loạt vĩnh viễn
            $('#select-all-checkbox').change(function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkForceDeleteButton();
            });

            $('.row-checkbox').change(function() {
                toggleBulkForceDeleteButton();
            });

            function toggleBulkForceDeleteButton() {
                if ($('.row-checkbox:checked').length > 0) {
                    $('#bulk-force-delete-btn').show();
                    $('#bulk-restore-btn').show();
                } else {
                    $('#bulk-force-delete-btn').hide();
                    $('#bulk-restore-btn').hide();
                }
            }

            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedRegionCount').text(selectedIds.length);
                    $('#bulkForceDeleteModal').modal('show');

                    $('#confirm-bulk-force-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.regions.bulkForceDelete') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE',
                                ids: selectedIds.join(',')
                            },
                            success: function(response) {
                                $('#bulkForceDeleteModal').modal('hide');
                                toastr.success(response.message ||
                                    'Xóa vĩnh viễn vùng miền đã chọn thành công!');
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkForceDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn vùng miền đã chọn';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) +
                                        '...';
                                } else {
                                    errorMessage = 'Lỗi không xác định';
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    toastr.error('Vui lòng chọn ít nhất một vùng miền để xóa vĩnh viễn.');
                }
            });

            // Logic cho chức năng khôi phục hàng loạt
            $('#bulk-restore-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedRegionCountRestore').text(selectedIds.length);
                    $('#bulkRestoreModal').modal('show');

                    $('#confirm-bulk-restore-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.regions.bulkRestore') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkRestoreModal').modal('hide');
                                toastr.success(response.message ||
                                    'Khôi phục vùng miền đã chọn thành công!');
                                // Tải lại trang hoặc cập nhật bảng nếu cần
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkRestoreModal').modal('hide');
                                let errorMessage = 'Lỗi khi khôi phục vùng miền đã chọn';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) +
                                        '...';
                                } else {
                                    errorMessage = 'Lỗi không xác định';
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    toastr.error('Vui lòng chọn ít nhất một vùng miền để khôi phục.');
                }
            });

            // Hiển thị modal lỗi nếu có session error
            @if (session('error'))
                var errorMessage = "{{ session('error') }}";
                if (errorMessage.includes('sản phẩm liên kết')) {
                    $('#cannotDeleteMessage').text(errorMessage);
                    $('#cannotDeleteModal').modal('show');
                }
            @endif
        });
    </script>
@endpush
