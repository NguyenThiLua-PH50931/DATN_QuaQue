@extends('layouts.backend')

@section('title', 'Quản lý sản phẩm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title d-sm-flex d-block">
                                <h5>Danh sách sản phẩm</h5>
                                <div class="right-options">
                                    <ul>
                                        <li>
                                            <a class="btn btn-solid" href="{{ route('admin.products.create') }}">Thêm sản phẩm</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger">
                                    {{ session('error') }}
                                </div>
                            @endif

                            {{-- Filter Form --}}
                            <form class="row g-3 mb-3" method="GET" action="{{ route('admin.products.index') }}">
                                <div class="col-md-3">
                                    <label for="category" class="form-label">Danh mục:</label>
                                    <select class="form-select" id="category" name="category">
                                        <option value="">Tất cả danh mục</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="region" class="form-label">Vùng miền:</label>
                                    <select class="form-select" id="region" name="region">
                                        <option value="">Tất cả vùng miền</option>
                                        @foreach($regions as $region)
                                            <option value="{{ $region->id }}" {{ request('region') == $region->id ? 'selected' : '' }}>
                                                {{ $region->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="status" class="form-label">Trạng thái:</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="">Tất cả trạng thái</option>
                                        <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Đang bán</option>
                                        <option value="0" {{ request('status') == '0' ? 'selected' : '' }}>Ngừng bán</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Lọc</button>
                                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Đặt lại</a>
                                </div>
                            </form>

                            <div class="table-responsive">
                                <table class="table all-package theme-table" id="productTable">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">Tên sản phẩm</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            <th style="color: black; background-color: #f8f9fa;">Danh mục</th>
                                            <th style="color: black; background-color: #f8f9fa;">Vùng miền</th>
                                            <th style="color: black; background-color: #f8f9fa;">Cập nhật lúc</th>
                                            <th style="color: black; background-color: #f8f9fa;">Trạng thái</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($products as $product)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]" value="{{ $product->id }}">
                                                </td>
                                                <td>
                                                    <div>
                                                        <a href="{{ route('admin.products.show', $product->slug) }}" class="fw-bold text-primary" style="font-size:16px;">
                                                            {{ $product->name }}
                                                        </a>
                                                        <div class="small text-muted mt-1">
                                                            {{ $product->short_desc ?? '' }}
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                                         class="w-20 h-20 object-cover" width="100px">
                                                </td>
                                                <td>{{ $product->category->name ?? '' }}</td>
                                                <td>{{ $product->region->name ?? '' }}</td>
                                                <td>{{ $product->updated_at->format('d-m-Y H:i:s') }}</td>
                                                <td>
                                                    <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }} status-badge"
                                                          style="cursor:pointer"
                                                          data-id="{{ $product->id }}"
                                                          data-name="{{ $product->name }}"
                                                          data-status="{{ $product->active }}">
                                                        {{ $product->active ? 'Đang bán' : 'Ngừng bán' }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('admin.products.show', $product->slug) }}">
                                                                <i class="ri-eye-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.products.edit', $product->slug) }}">
                                                                <i class="ri-pencil-line"></i>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="delete-btn"
                                                               data-bs-toggle="modal"
                                                               data-bs-target="#deleteModal"
                                                               data-id="{{ $product->id }}"
                                                               data-name="{{ $product->name }}">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="py-4 px-4 text-center">Không có sản phẩm nào.</td>
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
                            <form id="bulk-delete-form" action="{{ route('admin.products.bulkDelete') }}" method="POST" style="display: none;">
                                @csrf
                                <input type="hidden" name="ids" id="bulk-delete-ids">
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa sản phẩm</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa sản phẩm này không? Sản phẩm sẽ được xóa mềm và có thể khôi phục sau này.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" action="" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Xóa mềm</button>
                    </form>
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
                    Bạn có chắc chắn muốn xóa mềm <span id="selectedProductCount"></span> sản phẩm đã chọn không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="button" class="btn btn-danger" id="confirm-bulk-delete-btn">Xóa hàng loạt</button>
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

    @includeIf('backend.footer')
    <script src="{{ asset('backend/js/product.js') }}"></script>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#productTable').DataTable({
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
                    zeroRecords: "Không tìm thấy sản phẩm nào.",
                }
            });

            // Xử lý sự kiện click nút xóa mềm
            $(document).on('click', '.delete-btn', function(e) {
                var id = $(this).data('id');
                var name = $(this).data('name');

                if (id) {
                    var formAction = '{{ url('admin/products/') }}' + '/' + id;
                    $('#deleteForm').attr('action', formAction);
                } else {
                    e.preventDefault();
                    $('#errorMessageContent').text('Không thể xóa sản phẩm này do thiếu thông tin ID.');
                    $('#errorMessageModal').modal('show');
                }
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
                    $('#selectedProductCount').text(selectedIds.length);
                    $('#bulkDeleteModal').modal('show');

                    $('#confirm-bulk-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.products.bulkDelete') }}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkDeleteModal').modal('hide');
                                if (response.status === 'success') {
                                    $('#successMessageContent').text(response.message || 'Xóa sản phẩm đã chọn thành công!');
                                    $('#successMessageModal').modal('show');
                                } else if (response.status === 'warning') {
                                    $('#errorMessageContent').text(response.message || 'Có một số sản phẩm không thể xóa.');
                                    $('#errorMessageModal').modal('show');
                                } else {
                                    $('#errorMessageContent').text(response.message || 'Lỗi khi xóa sản phẩm đã chọn.');
                                    $('#errorMessageModal').modal('show');
                                }
                                $('#successMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                                $('#errorMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                $('#bulkDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa sản phẩm đã chọn';
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
                    $('#errorMessageContent').text('Vui lòng chọn ít nhất một sản phẩm để xóa.');
                    $('#errorMessageModal').modal('show');
                }
            });

            // Xử lý đổi trạng thái sản phẩm
            $('.status-badge').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                var status = $(this).data('status');
                var nextStatus = status == 1 ? 'Ngừng bán' : 'Đang bán';

                $('#modal-status-text').html('Bạn muốn chuyển trạng thái sản phẩm <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');
                $('#status-toggle-form').attr('action', '{{ url('admin/products/toggle') }}/' + id);
                $('#statusModal').modal('show');
            });

            // Hiển thị modal lỗi nếu có session error
            @if(session('error'))
                var errorMessage = "{{ session('error') }}";
                $('#errorMessageContent').text(errorMessage);
                $('#errorMessageModal').modal('show');
            @endif
        });
    </script>
@endpush
