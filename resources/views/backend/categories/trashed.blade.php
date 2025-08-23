@extends('layouts.backend')

@section('title', 'Danh mục đã xóa')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Danh mục đã xóa</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.categories.index') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="list"></i> Quay lại danh sách
                                    </a>
                                    <button type="button" id="check-auto-delete-btn" class="btn btn-info ms-2">
                                        <i class="ri-information-line"></i> Kiểm tra tự động xóa
                                    </button>
                                </form>
                            </div>

                            <small class="text-muted">
                                <i class="ri-information-line"></i>
                                <strong>Lưu ý:</strong> Các danh mục đã xóa mềm sẽ được tự động xóa vĩnh viễn sau 30 ngày.
                            </small>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive category-table">
                                <table class="table all-package theme-table" id="trashed_categories_table">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">Tên danh mục</th>
                                            <th style="color: black; background-color: #f8f9fa;">Icon</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>

                                            <th style="color: black; background-color: #f8f9fa;">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($categories as $category)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $category->id }}">
                                                </td>
                                                <td>{{ $category->name }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $category->image) }}"
                                                        alt="{{ $category->name }}" class="w-20 h-20 object-cover"
                                                        width="50">
                                                </td>
                                                <td>{{ $category->deleted_at->format('d-m-Y') }}</td>

                                                <td>
                                                    <ul>
                                                        <li>
                                                            <button type="button"
                                                                class="btn btn-success btn-sm restore-btn"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button"
                                                                class="btn btn-danger btn-sm force-delete-btn"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}">
                                                                <i data-feather="trash-2"></i> Xoá vĩnh viễn
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5">Không có danh mục đã xóa.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <button type="button" id="bulk-restore-btn"
                                        class="align-items-center btn btn-success d-flex me-2" style="display: none;">
                                        <i class="ri-refresh-line"></i> Khôi phục đã chọn
                                    </button>
                                    <button type="button" id="bulk-force-delete-btn"
                                        class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa đã chọn (vĩnh viễn)
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal xác nhận khôi phục -->
        <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn khôi phục danh mục <span id="restoreCategoryName"></span>?
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
        <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa vĩnh viễn danh mục <span id="forceDeleteCategoryName"></span>?
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
                        Bạn có chắc chắn muốn khôi phục <span id="selectedTrashedCategoryCountRestore"></span> danh mục
                        đã chọn không?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-success" id="confirm-bulk-restore-btn">Khôi phục hàng loạt</button>
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
                        Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selectedTrashedCategoryCount"></span> danh mục đã
                        chọn không? Hành động này không thể hoàn tác.
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" id="confirm-bulk-force-delete-btn">Xóa vĩnh viễn
                            hàng loạt</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Cannot Delete Modal (for trashed categories) --}}
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

        {{-- Auto Delete Status Modal --}}
        <div class="modal fade" id="autoDeleteStatusModal" tabindex="-1" aria-labelledby="autoDeleteStatusModalLabel" aria-hidden="true">
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

    </div>
    @includeIf('backend.footer')

    {{-- Include Toastr CSS and JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance
            let table;
            try {
                table = $('#trashed_categories_table').DataTable({
                    language: {
                        search: "Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ danh mục đã xóa",
                        info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ danh mục đã xóa",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "Sau",
                            previous: "Trước"
                        },
                        zeroRecords: "Không tìm thấy danh mục đã xóa nào.",
                    }
                });
            } catch (error) {
                console.warn('DataTable initialization failed:', error);
            }

            // Handle force delete using AJAX
            $(document).on('submit', '.force-delete-form', function(e) {
                e.preventDefault(); // Prevent default form submission
                const form = $(this);
                const url = form.attr('action');
                // Get the DataTables row object associated with this form's parent row
                const row = table.row(form.closest('tr'));

                if (confirm('Bạn có chắc chắn muốn xóa vĩnh viễn danh mục này?')) {
                    $.ajax({
                        url: url,
                        method: 'DELETE', // Use DELETE method for force deletion
                        data: form.serialize(),
                        success: function(response) {
                            // Remove the row using DataTables API
                            console.log('Force delete success via AJAX!', response);
                            if (row && row.remove) {
                                row.remove().draw(); // Remove row and redraw the table
                            } else {
                                console.error(
                                    'Could not get valid DataTables row object. Removing row directly.'
                                );
                                form.closest('tr').remove();
                            }
                            toastr.success(response.message ||
                                'Xóa vĩnh viễn danh mục thành công!');
                        },
                        error: function(xhr) {
                            console.error('Force delete AJAX failed!', xhr);
                            let errorMessage = 'Lỗi khi xóa vĩnh viễn danh mục';
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
                }
            });

            // Handle restore (if needed to be AJAX, otherwise current form submission is fine)
            // If you want restore to be AJAX, you'd add a similar script for a restore form.

            // Modal xác nhận khôi phục
            $('.restore-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#restoreCategoryName').text('"' + name + '"');
                $('#restoreForm').attr('action', '/admin/categories/' + id + '/restore');
                $('#restoreModal').modal('show');
            });

            // Modal xác nhận xóa vĩnh viễn
            $('.force-delete-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#forceDeleteCategoryName').text('"' + name + '"');
                $('#forceDeleteForm').attr('action', '/admin/categories/' + id + '/force');
                // Lưu lại id để xóa dòng sau khi xóa thành công
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
                        toastr.success(response.message ||
                            'Xóa vĩnh viễn danh mục thành công!');
                    },
                    error: function(xhr) {
                        $('#forceDeleteModal').modal('hide');
                        let errorMessage = 'Lỗi khi xóa vĩnh viễn danh mục';
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
                    $('#bulk-restore-btn').show(); // Hiển thị nút khôi phục hàng loạt
                } else {
                    $('#bulk-force-delete-btn').hide();
                    $('#bulk-restore-btn').hide(); // Ẩn nút khôi phục hàng loạt
                }
            }

            // Logic cho chức năng khôi phục hàng loạt
            $('#bulk-restore-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedCategoryCountRestore').text(selectedIds.length);
                    $('#bulkRestoreModal').modal('show');

                    $('#confirm-bulk-restore-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.categories.bulkRestore') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkRestoreModal').modal('hide');
                                toastr.success(response.message ||
                                    'Khôi phục danh mục đã chọn thành công!');
                                // Tải lại trang hoặc cập nhật bảng nếu cần
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkRestoreModal').modal('hide');
                                let errorMessage = 'Lỗi khi khôi phục danh mục đã chọn';
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
                    toastr.error('Vui lòng chọn ít nhất một danh mục để khôi phục.');
                }
            });

            // Logic cho chức năng xóa hàng loạt vĩnh viễn
            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedCategoryCount').text(selectedIds.length);
                    $('#bulkForceDeleteModal').modal('show');

                    $('#confirm-bulk-force-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.categories.bulkForceDelete') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE',
                                ids: selectedIds.join(',')
                            },
                            success: function(response) {
                                $('#bulkForceDeleteModal').modal('hide');
                                toastr.success(response.message ||
                                    'Xóa vĩnh viễn danh mục đã chọn thành công!');
                                window.location.reload();
                            },
                            error: function(xhr) {
                                $('#bulkForceDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn danh mục đã chọn';
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
                    toastr.error('Vui lòng chọn ít nhất một danh mục để xóa vĩnh viễn.');
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

            // Xử lý nút kiểm tra tự động xóa
            $('#check-auto-delete-btn').click(function() {
                console.log('Checking auto delete status...');
                $.ajax({
                    url: '{{ route('admin.categories.checkAutoDeleteStatus') }}',
                    method: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        console.log('Auto delete status response:', response);
                        let content = '<div class="row">';
                        content += '<div class="col-md-6"><strong>Tổng số danh mục đã xóa:</strong> ' + response.total + '</div>';
                        content += '<div class="col-md-6"><strong>Sắp được xóa (≤7 ngày):</strong> ' + response.will_be_deleted_soon + '</div>';
                        content += '</div><hr>';

                        if (response.days_until_auto_delete.length > 0) {
                            content += '<h6>Chi tiết:</h6><div class="table-responsive"><table class="table table-sm">';
                            content += '<thead><tr><th>Danh mục</th><th>Còn lại</th><th>Ngày xóa</th></tr></thead><tbody>';

                            response.days_until_auto_delete.forEach(function(item) {
                                const badgeClass = item.days_left <= 7 ? 'bg-danger' : 'bg-warning';
                                content += '<tr>';
                                content += '<td>' + item.name + '</td>';
                                content += '<td><span class="badge ' + badgeClass + '">' + item.days_left + ' ngày</span></td>';
                                content += '<td>' + item.auto_delete_at + '</td>';
                                content += '</tr>';
                            });

                            content += '</tbody></table></div>';
                        } else {
                            content += '<p class="text-muted">Không có danh mục nào trong thùng rác.</p>';
                        }

                        $('#autoDeleteStatusContent').html(content);
                        $('#autoDeleteStatusModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error('Error checking auto delete status:', error);
                        $('#autoDeleteStatusContent').html('<div class="alert alert-danger">Lỗi khi kiểm tra trạng thái tự động xóa.</div>');
                        $('#autoDeleteStatusModal').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
