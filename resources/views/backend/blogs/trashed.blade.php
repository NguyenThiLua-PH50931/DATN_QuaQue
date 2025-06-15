@extends('layouts.backend')

@section('title', 'Tin tức đã xóa')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="title-header option-title">
                                    <h5>Tin tức đã xóa</h5>
                                </div>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.blog.index') }}"
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
                                <table class="table all-package theme-table" id="blog_table_id">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">ID</th>
                                            <th>Ảnh</th>
                                            <th>Tiêu đề</th>
                                            <th>Đường link</th>
                                            <th>Ngày hiển thị</th>
                                            <th>Ngày dừng hiển thị</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($blogs as $item)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $item->id }}">
                                                </td>
                                                <td>{{ $item->id }}</td>
                                                <td>
                                                    <div class="table-image">
                                                        @if($item->thumbnail)
                                                            <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" width="100">
                                                        @else
                                                            <span class="text-muted">Không có ảnh</span> {{-- <- giữ td không bị rỗng --}}
                                                        @endif
                                                    </div>
                                                </td>
    
                                                <td>{{ $item->title }}</td>
                                                <td>{{ $item->slug }}</td>
                                                <td>{{ $item->start_date ? $item->start_date->format('m/d/Y') : '' }}</td>
                                                <td>{{ $item->end_date ? $item->end_date->format('m/d/Y') : '' }}</td>
                                                <td>{{ $item->deleted_at }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-success restore-btn"
                                                                data-bs-toggle="modal" data-bs-target="#restoreModal"
                                                                data-id="{{ $item->id }}"
                                                                data-title="{{ $item->title }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-danger force-delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#forceDeleteModal"
                                                                data-id="{{ $item->id }}"
                                                                data-title="{{ $item->title }}">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <button type="button" id="bulk-restore-btn"
                                        class="align-items-center btn btn-success d-flex me-2" style="display: none;">
                                        <i class="ri-refresh-line"></i> Khôi phục
                                    </button>
                                    <button type="button" id="bulk-force-delete-btn"
                                        class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa vĩnh viễn
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Restore Modal --}}
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục blog "<span id="blogTitleRestore"></span>" không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="restoreForm" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="btn btn-success">Khôi phục</button>
                    </form>
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
                    Bạn có chắc chắn muốn khôi phục <span id="selectedTrashedBlogCountRestore"></span> blog đã chọn
                    không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-success" id="confirm-bulk-restore-btn">Khôi phục hàng
                        loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Force Delete Modal --}}
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn blog</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn blog "<span id="blogTitleForceDelete"></span>" không? Hành động
                    này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="forceDeleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa vĩnh viễn</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Force Delete Modal --}}
    <div class="modal fade" id="bulkForceDeleteModal" tabindex="-1" aria-labelledby="bulkForceDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkForceDeleteModalLabel">Xác nhận xóa vĩnh viễn hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selectedTrashedBlogCount"></span> blog đã chọn không?
                    Hành động này không thể hoàn tác.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-force-delete-btn">Xóa vĩnh viễn hàng
                        loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Success Message Modal --}}
    <div class="modal fade" id="successMessageModal" tabindex="-1" aria-labelledby="successMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title" id="successMessageModalLabel">Thành công!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="successMessageContent">
                    <!-- Message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Error Message Modal --}}
    <div class="modal fade" id="errorMessageModal" tabindex="-1" aria-labelledby="errorMessageModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title" id="errorMessageModalLabel">Lỗi!</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="errorMessageContent">
                    <!-- Message will be inserted here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

@endsection

{{-- Include Toastr CSS and JS (Moved here to ensure it loads before other scripts) --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#blog_table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ blog",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy blog nào đã bị xóa.",
                    emptyTable: "Không có blog nào đã bị xóa.",
                }
            });

            // Xử lý sự kiện click nút khôi phục
            $('.restore-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#blogTitleRestore').text(title);
                $('#restoreForm').attr('action', '{{ url('admin/blog/') }}' + '/' + id + '/restore');
            });

            // Xử lý sự kiện click nút xóa vĩnh viễn
            $('.force-delete-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#blogTitleForceDelete').text(title);
                $('#forceDeleteForm').attr('action', '{{ url('admin/blog/') }}' + '/' + id + '/force');
            });

            // Logic cho chức năng chọn tất cả và xóa hàng loạt vĩnh viễn
            $('#select-all-checkbox').change(function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkActionButtons();
            });

            $('.row-checkbox').change(function() {
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

            $('#bulk-restore-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedBlogCountRestore').text(selectedIds.length);
                    $('#bulkRestoreModal').modal('show');

                    $('#confirm-bulk-restore-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.blog.bulkRestore') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkRestoreModal').modal('hide');
                                $('#successMessageContent').text(response.message || 'Khôi phục blog đã chọn thành công!');
                                $('#successMessageModal').modal('show');
                                $('#successMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                $('#bulkRestoreModal').modal('hide');
                                let errorMessage = 'Lỗi khi khôi phục blog đã chọn';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) + '...';
                                } else {
                                    errorMessage = 'Lỗi không xác định';
                                }
                                $('#errorMessageContent').text(errorMessage);
                                $('#errorMessageModal').modal('show');
                                $('#errorMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            }
                        });
                    });
                } else {
                    $('#errorMessageContent').text('Vui lòng chọn ít nhất một blog để khôi phục.');
                    $('#errorMessageModal').modal('show');
                }
            });

            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedBlogCount').text(selectedIds.length);
                    $('#bulkForceDeleteModal').modal('show');

                    $('#confirm-bulk-force-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.blog.bulkForceDelete') }}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkForceDeleteModal').modal('hide');
                                $('#successMessageContent').text(response.message || 'Xóa vĩnh viễn blog đã chọn thành công!');
                                $('#successMessageModal').modal('show');
                                $('#successMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                $('#bulkForceDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn blog đã chọn';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) + '...';
                                } else {
                                    errorMessage = 'Lỗi không xác định';
                                }
                                $('#errorMessageContent').text(errorMessage);
                                $('#errorMessageModal').modal('show');
                                $('#errorMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            }
                        });
                    });
                } else {
                    $('#errorMessageContent').text('Vui lòng chọn ít nhất một blog để xóa vĩnh viễn.');
                    $('#errorMessageModal').modal('show');
                }
            });
        });
    </script>
@endpush
