@extends('layouts.backend')

@section('title', 'Sản phẩm đã xóa mềm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="title-header option-title">
                                    <h5>Sản phẩm đã xóa mềm</h5>
                                </div>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.products.index') }}"
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

                            <div class="table-responsive category-table" style="overflow-x: auto; width: 100%;">
                                <table class="table all-package theme-table" id="productTableDeleted" style="width: 100%; min-width: 900px;">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            {{-- <th style="color: black; background-color: #f8f9fa;">ID</th> --}}
                                            <th style="color: black; background-color: #f8f9fa;">Tên sản phẩm</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            {{-- <th style="color: black; background-color: #f8f9fa;">Trạng thái</th> --}}
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $product->id }}">
                                                </td>
                                                {{-- <td>{{ $product->id }}</td> --}}
                                                <td style="max-width: 220px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $product->name }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        alt="{{ $product->name }}" class="w-20 h-20 object-cover"
                                                        width="80px">
                                                </td>
                                                {{-- <td>{{ $product->active ? 'Đang bán' : 'Ngừng bán' }}</td> --}}
                                                <td>{{ $product->deleted_at->format('d-m-Y H:i:s') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-success restore-btn"
                                                                data-bs-toggle="modal" data-bs-target="#restoreModal"
                                                                data-id="{{ $product->id }}"
                                                                data-name="{{ $product->name }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-danger force-delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#forceDeleteModal"
                                                                data-id="{{ $product->id }}"
                                                                data-name="{{ $product->name }}">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có sản phẩm nào đã bị xóa mềm.
                                                </td>
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

@endsection

{{-- Include Toastr CSS and JS --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

{{-- Include SweetAlert2 CSS and JS --}}
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

            // Hiển thị thông báo thành công từ session flash (nếu có)
            @if (session('success'))
                toastr.success('{{ session('success') }}');
            @endif

            // Hiển thị thông báo lỗi từ session flash (nếu có)
            @if (session('error'))
                toastr.error('{{ session('error') }}');
            @endif

            $('#productTableDeleted').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ sản phẩm",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ sản phẩm",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy sản phẩm nào đã bị xóa mềm.",
                },
                "columnDefs": [{
                    "orderable": false,
                    "targets": [0, 3, 6] // Tắt sắp xếp cho cột checkbox, ảnh, hành động (cột 0, 3, 6)
                }]
            });

            // Logic cho chức năng chọn tất cả và ẩn/hiện nút hành động hàng loạt
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

            // Xử lý sự kiện click nút khôi phục từng sản phẩm
            $(document).on('click', '.restore-btn', function() {
                var productId = $(this).data('id');
                var productName = $(this).data('name');
                Swal.fire({
                    title: 'Xác nhận khôi phục?',
                    text: 'Bạn có chắc chắn muốn khôi phục sản phẩm "' + productName + '" không?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Khôi phục',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/products/' + productId + '/restore',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                toastr.success(response.message || 'Khôi phục sản phẩm thành công!');
                                window.location.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = 'Lỗi khi khôi phục sản phẩm.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    }
                });
            });

            // Xử lý sự kiện click nút xóa vĩnh viễn từng sản phẩm
            $(document).on('click', '.force-delete-btn', function() {
                var productId = $(this).data('id');
                var productName = $(this).data('name');
                Swal.fire({
                    title: 'Xác nhận xóa vĩnh viễn?',
                    text: 'Bạn có chắc chắn muốn xóa vĩnh viễn sản phẩm "' + productName + '" không? Hành động này không thể hoàn tác.',
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Xóa vĩnh viễn',
                    cancelButtonText: 'Hủy'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/products/' + productId + '/force',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                _method: 'DELETE'
                            },
                            success: function(response) {
                                toastr.success(response.message || 'Xóa vĩnh viễn sản phẩm thành công!');
                                window.location.reload();
                            },
                            error: function(xhr) {
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn sản phẩm.';
                                if (xhr.responseJSON && xhr.responseJSON.message) {
                                    errorMessage = xhr.responseJSON.message;
                                }
                                toastr.error(errorMessage);
                            }
                        });
                    }
                });
            });

            // Xử lý sự kiện click nút khôi phục hàng loạt
            $('#bulk-restore-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    Swal.fire({
                        title: 'Xác nhận khôi phục hàng loạt?',
                        text: 'Bạn có chắc chắn muốn khôi phục ' + selectedIds.length + ' sản phẩm đã chọn không?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Khôi phục hàng loạt',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('admin.products.bulkRestore') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ids: selectedIds.join(',')
                                },
                                success: function(response) {
                                    toastr.success(response.message || 'Khôi phục sản phẩm đã chọn thành công!');
                                    window.location.reload();
                                },
                                error: function(xhr) {
                                    let errorMessage = 'Lỗi khi khôi phục các sản phẩm đã chọn.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    toastr.error(errorMessage);
                                }
                            });
                        }
                    });
                } else {
                    toastr.warning('Vui lòng chọn ít nhất một sản phẩm để khôi phục.');
                }
            });

            // Xử lý sự kiện click nút xóa vĩnh viễn hàng loạt
            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    Swal.fire({
                        title: 'Xác nhận xóa vĩnh viễn hàng loạt?',
                        text: 'Bạn có chắc chắn muốn xóa vĩnh viễn ' + selectedIds.length + ' sản phẩm đã chọn không? Hành động này không thể hoàn tác.',
                        icon: 'error',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Xóa vĩnh viễn hàng loạt',
                        cancelButtonText: 'Hủy'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('admin.products.bulkForceDelete') }}',
                                method: 'POST',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ids: selectedIds.join(',')
                                },
                                success: function(response) {
                                    toastr.success(response.message || 'Xóa vĩnh viễn sản phẩm đã chọn thành công!');
                                    window.location.reload();
                                },
                                error: function(xhr) {
                                    let errorMessage = 'Lỗi khi xóa vĩnh viễn các sản phẩm đã chọn.';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    }
                                    toastr.error(errorMessage);
                                }
                            });
                        }
                    });
                } else {
                    toastr.warning('Vui lòng chọn ít nhất một sản phẩm để xóa vĩnh viễn.');
                }
            });
        });
    </script>