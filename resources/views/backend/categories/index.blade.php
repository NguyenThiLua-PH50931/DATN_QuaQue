@extends('layouts.backend')

@section('title', 'Danh mục')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Tất cả danh mục</h5>
                                <form class="d-inline-flex">
                                    {{-- Button to Create New Category --}}
                                    <a href="javascript:void(0)" class="align-items-center btn btn-theme d-flex"
                                        data-bs-toggle="modal" data-bs-target="#createModal">
                                        <i data-feather="plus-square"></i> Thêm mới
                                    </a>
                                </form>
                            </div>

                            {{-- Session Success Message --}}
                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive category-table">
                                <table class="table all-package theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">Tên danh mục</th>
                                            <th style="color: black; background-color: #f8f9fa;">Slug</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày tạo</th>
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
                                                <td>{{ $category->slug }}</td>
                                                <td>{{ $category->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)" class="edit-btn"
                                                                data-bs-toggle="modal" data-bs-target="#editModal"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                data-id="{{ $category->id }}"
                                                                data-name="{{ $category->name }}">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>

                                        @empty
                                            <tr>
                                                <td colspan="5" class="py-4 px-4 text-center">Không tìm thấy danh mục
                                                    nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <button type="button" id="bulk-delete-btn"
                                        class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa đã chọn
                                    </button>
                                </form>

                            </div>
                            <form id="bulk-delete-form" action="{{ route('admin.categories.bulkDelete') }}" method="POST"
                                style="display: none;">
                                @csrf
                                @method('DELETE')
                                <input type="hidden" name="ids" id="bulk-delete-ids">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Create Modal --}}
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Thêm danh mục mới</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Thêm mới</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal --}}
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Sửa danh mục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_name" class="form-label">Tên danh mục</label>
                            <input type="text" class="form-control" id="edit_name" name="name" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa danh mục "<span id="categoryName"></span>"?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Delete Modal --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">Xác nhận xóa hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa mềm <span id="selectedCategoryCount"></span> danh mục đã chọn không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-delete-btn">Xóa hàng loạt</button>
                </div>
            </div>
        </div>
    </div>

    {{-- No Selection Modal --}}
    <div class="modal fade" id="noSelectionModal" tabindex="-1" aria-labelledby="noSelectionModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="noSelectionModalLabel">Thông báo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Vui lòng chọn ít nhất một danh mục để xóa.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Cannot Delete Modal --}}
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
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ danh mục",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ danh mục",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy danh mục nào.",
                }
            });

            // Xử lý sự kiện click nút xóa (sử dụng event delegation)
            $(document).on('click', '.delete-btn', function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#categoryName').text(name);
                $('#deleteForm').attr('action', '/admin/categories/' + id + '/trashed');
            });

            // Xử lý sự kiện click nút sửa
            $('.edit-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#edit_name').val(name);
                $('#editForm').attr('action', '/admin/categories/' + id);
            });

            // Reset form khi đóng modal
            $('#createModal').on('hidden.bs.modal', function() {
                $('#createModal form')[0].reset();
            });

            $('#editModal').on('hidden.bs.modal', function() {
                $('#editModal form')[0].reset();
            });

            // Logic cho chức năng chọn tất cả và xóa hàng loạt
            $('#select-all-checkbox').change(function() {
                $('.row-checkbox').prop('checked', $(this).prop('checked'));
                toggleBulkDeleteButton();
            });

            $('.row-checkbox').change(function() {
                toggleBulkDeleteButton();
            });

            function toggleBulkDeleteButton() {
                if ($('.row-checkbox:checked').length > 0) {
                    $('#bulk-delete-btn').show();
                } else {
                    $('#bulk-delete-btn').hide();
                }
            }

            $('#bulk-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    // Mở modal xác nhận xóa hàng loạt
                    $('#selectedCategoryCount').text(selectedIds.length);
                    $('#bulkDeleteModal').modal('show');

                    // Gán sự kiện cho nút xác nhận trong modal
                    $('#confirm-bulk-delete-btn').off('click').on('click', function() {
                        var selectedIds = [];
                        $('.row-checkbox:checked').each(function() {
                            selectedIds.push($(this).val());
                        });

                        $.ajax({
                            url: '{{ route('admin.categories.bulkDelete') }}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkDeleteModal').modal('hide');
                                if (response.status === 'success') {
                                    toastr.success(response.message);
                                } else if (response.status === 'warning') {
                                    toastr.warning(response.message);
                                } else {
                                    toastr.info(response.message);
                                }
                                window.location.reload(); // Tải lại trang sau khi hoàn thành
                            },
                            error: function(xhr) {
                                $('#bulkDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa hàng loạt danh mục';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                } else if (xhr.responseText) {
                                    errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) + '...';
                                } else {
                                    errorMessage = 'Lỗi không xác định';
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    });
                } else {
                    $('#noSelectionModal').modal('show');
                }
            });

            // Hiển thị modal lỗi nếu có session error
            @if(session('error'))
                var errorMessage = "{{ session('error') }}";
                if (errorMessage.includes('sản phẩm liên kết')) {
                    $('#cannotDeleteMessage').text(errorMessage);
                    $('#cannotDeleteModal').modal('show');
                }
            @endif
        });
    </script>
@endpush

<style>
    .btn.btn-warning {
        background-color: #ffc107 !important; /* Màu vàng */
        border-color: #ffc107 !important;
        color: #212529 !important; /* Màu chữ đen */
    }

    .btn.btn-danger {
        background-color: #35dc94 !important; /* Màu đỏ */
        border-color: #dc3545 !important;
        color: #fff !important; /* Màu chữ trắng */
    }
</style>
