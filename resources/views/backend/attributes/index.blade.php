@extends('layouts.backend')

@section('title', 'Quản lý thuộc tính')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-sm-flex d-block">
                                <h5>Tất cả thuộc tính</h5>
                                <div class="right-options">
                                    <ul>
                                        <li>
                                            <a class="btn btn-theme d-flex align-items-center"
                                                href="{{ route('admin.attributes.create') }}">
                                                <i data-feather="plus-square"></i> Thêm mới
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <style>
                                .table {
                                    width: 100%;
                                    margin-bottom: 1rem;
                                    background-color: transparent;
                                    border-collapse: collapse;
                                    border: none;
                                }

                    .table th {
                                    background-color: #f8f9fa !important;
                                    color: #333 !important;
                                    font-weight: 600;
                                    text-align: center !important;
                                    vertical-align: middle;
                                    padding: 12px 8px;
                                    border: none;
                                    border-bottom: 2px solid #dee2e6;
                        white-space: nowrap;
                                }

                                .table td {
                                    padding: 12px 8px;
                        vertical-align: middle;
                                    border: none;
                                    border-bottom: 1px solid #f0f0f0;
                                    text-align: center !important;
                                }

                                /* Cột checkbox */
                                .table th:first-child,
                                .table td:first-child {
                                    width: 50px;
                                    text-align: center;
                                }

                                /* Cột ID */
                                .table th:nth-child(2),
                                .table td:nth-child(2) {
                                    width: 80px;
                                    text-align: center;
                                }

                                                                /* Cột tên thuộc tính */
                                .table th:nth-child(3),
                                .table td:nth-child(3) {
                                    text-align: center !important;
                                    min-width: 200px;
                                }

                                /* Cột giá trị thuộc tính */
                                .table th:nth-child(4),
                                .table td:nth-child(4) {
                                    text-align: center !important;
                                    min-width: 250px;
                                    max-width: 300px;
                                    word-wrap: break-word;
                                    white-space: normal;
                                }

                                /* Cột ngày cập nhật */
                                .table th:nth-child(5),
                                .table td:nth-child(5) {
                                    width: 150px;
                                    text-align: center;
                                }

                                /* Cột hành động */
                                .table th:last-child,
                                .table td:last-child {
                                    width: 120px;
                                    text-align: center;
                                }

                                /* Hover effect */
                                .table tbody tr:hover {
                                    background-color: #f8f9fa;
                                }

                                /* Ẩn đường viền bảng */
                                .table,
                                .table th,
                                .table td {
                                    border: none !important;
                                }

                                /* Đảm bảo tất cả các cột đều căn giữa */
                                .table th,
                                .table td {
                                    text-align: center !important;
                                }

                                /* Chỉ giữ đường viền dưới cho header và các hàng */
                                .table th {
                                    border-bottom: 2px solid #dee2e6 !important;
                                }

                                .table td {
                                    border-bottom: 1px solid #f0f0f0 !important;
                                }

                                /* Link trong bảng */
                                .table a {
                                    text-decoration: none;
                                    color: #007bff;
                                    display: inline-block;
                                    text-align: center;
                                    width: 100%;
                                }

                                .table a:hover {
                                    color: #0056b3;
                                    text-decoration: underline;
                                }

                                /* Nút hành động */
                                .table .action-buttons {
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    gap: 8px;
                                }

                                .table .action-buttons a {
                                    padding: 6px 8px;
                                    border-radius: 4px;
                                    transition: all 0.2s;
                                }

                                .table .action-buttons a:hover {
                                    background-color: #f8f9fa;
                    }
                            </style>
                           <div class="table-responsive" style="overflow-x:unset;"> {{-- bỏ thuộc tính max-width nếu có --}}
                            <table class="table" id="attributeTable">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th>ID</th>
                                            <th>Tên Thuộc Tính</th>
                                            <th>Giá Trị Thuộc Tính</th>
                                            <th>Ngày Cập Nhật</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($attributes as $attribute)
                                            <tr>
                                                <td class="text-center">
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $attribute->id }}">
                                                </td>
                                                <td class="text-center">{{ $attribute->id }}</td>
                                                <td>
                                                    <a href="{{ route('admin.attributes.edit', $attribute->slug) }}"
                                                        class="fw-bold text-primary">
                                                        {{ $attribute->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @php
                                                        $values = $attribute->values->pluck('value')->toArray();
                                                    @endphp
                                                    <span title="{{ implode(', ', $values) }}">
                                                    {{ implode(', ', $values) }}
                                                    </span>
                                                </td>
                                                <td>
                                                    {{ $attribute->updated_at ? $attribute->updated_at->format('d/m/Y H:i') : '' }}
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('admin.attributes.edit', $attribute->slug) }}"
                                                           title="Sửa thuộc tính">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                            <a href="javascript:void(0)" class="delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                data-id="{{ $attribute->id }}"
                                                           data-name="{{ $attribute->name }}"
                                                           title="Xóa thuộc tính">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="6" class="py-4 px-4 text-center">Không có thuộc tính nào.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <button type="button" id="bulk-delete-btn" class="align-items-center btn d-flex ms-2"
                                        style="display: none; background-color: #ffc107; color: white; border-color: #ffc107; font-size: 0.85rem; padding: 0.25rem 0.5rem;">
                                        <i data-feather="trash" style="color: white; width: 16px; height: 16px;"></i> Xóa đã
                                        chọn
                                    </button>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal (Soft Delete Single) --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa thuộc tính</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn chuyển thuộc tính <strong id="attributeNameDelete"></strong> vào thùng rác không?
                    Thuộc tính này sẽ được xóa mềm và có thể khôi phục sau này.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-delete-single-btn">Chuyển vào thùng
                        rác</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Bulk Delete Modal --}}
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="bulkDeleteModalLabel">Xác nhận xóa hàng loạt</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn chuyển <span id="selectedAttributeCount"></span> thuộc tính đã chọn vào thùng rác
                    không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-delete-btn">Chuyển vào thùng rác</button>
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
                    Vui lòng chọn ít nhất một thuộc tính để xóa.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Đóng</button>
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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

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
                        zeroRecords: "Không tìm thấy thuộc tính nào.",
                    },
                    "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 5]
                    }]
                });

                // Xử lý sự kiện click nút sửa (chuyển sang trang edit)
                $(document).on('click', '.edit-btn', function() {
                    // Không cần xử lý modal, chỉ cần click vào link sẽ chuyển trang
                });

                // Reset form khi đóng modal tạo mới (đã loại bỏ modal tạo mới)
                // $('#createModal').on('hidden.bs.modal', function() {
                //     $('#createModal form')[0].reset();
                // });

                // Reset form khi đóng modal sửa (đã loại bỏ modal sửa)
                // $('#editModal').on('hidden.bs.modal', function() {
                //     $('#editModal form')[0].reset();
                // });

                // Xử lý sự kiện click nút xóa mềm (đơn lẻ)
                $(document).on('click', '.delete-btn', function() {
                    var id = $(this).data('id');
                    var name = $(this).data('name');
                    $('#attributeNameDelete').text(name);
                    $('#confirm-delete-single-btn').data('id', id); // Lưu ID vào nút confirm
                });

                // Xử lý xác nhận xóa mềm (đơn lẻ)
                $(document).on('click', '#confirm-delete-single-btn', function() {
                    var attributeId = $(this).data('id');
                    $('#deleteModal').modal('hide');

                    $.ajax({
                        url: '{{ url('admin/attributes') }}/' + attributeId,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function(response) {
                            if (response.success) {
                                toastr.success(response.message);
                            } else if (response.status === 'warning') {
                                toastr.warning(response.message);
                            } else {
                                toastr.info(response.message);
                            }
                            window.location.reload();
                        },
                        error: function(xhr) {
                            let errorMessage = 'Lỗi khi xóa thuộc tính.';
                            if (xhr.responseJSON && xhr.responseJSON.message) {
                                errorMessage = xhr.responseJSON.message;
                            } else if (xhr.responseText) {
                                errorMessage = 'Lỗi server: ' + xhr.responseText.substring(0, 100) +
                                    '...';
                            }
                            toastr.error(errorMessage);
                        }
                    });
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
                        $('#bulk-delete-btn').show();
                    } else {
                        $('#bulk-delete-btn').hide();
                    }
                }

                // Xử lý sự kiện click nút xóa hàng loạt (Soft Delete Bulk)
                $('#bulk-delete-btn').click(function() {
                    var selectedIds = [];
                    $('.row-checkbox:checked').each(function() {
                        selectedIds.push($(this).val());
                    });

                    if (selectedIds.length > 0) {
                        $('#selectedAttributeCount').text(selectedIds.length);
                        $('#bulkDeleteModal').modal('show');

                        $('#confirm-bulk-delete-btn').off('click').on('click', function() {
                            $.ajax({
                                url: '{{ route('admin.attributes.bulkDelete') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ids: selectedIds.join(',') // Gửi mảng ID dưới dạng chuỗi
                                },
                                success: function(response) {
                                    $('#bulkDeleteModal').modal('hide');
                                    if (response.success) {
                                        toastr.success(response.message);
                                    } else if (response.status === 'warning') {
                                        toastr.warning(response.message);
                                    } else {
                                        toastr.info(response.message);
                                    }
                                    window.location.reload();
                                },
                                error: function(xhr) {
                                    $('#bulkDeleteModal').modal('hide');
                                    let errorMessage =
                                        'Lỗi khi chuyển thuộc tính đã chọn vào thùng rác.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    } else if (xhr.responseText) {
                                        errorMessage = 'Lỗi server: ' + xhr.responseText
                                            .substring(0, 100) + '...';
                                    }
                                    toastr.error(errorMessage);
                                }
                            });
                        });
                    } else {
                        $('#noSelectionModal').modal('show');
                    }
                });

                // Hiển thị modal lỗi nếu có session error từ server
                @if (session('error'))
                    var errorMessage = "{{ session('error') }}";
                    $('#cannotDeleteMessage').text(errorMessage);
                    $('#cannotDeleteModal').modal('show');
                @endif
            });
        </script>

    @endsection
