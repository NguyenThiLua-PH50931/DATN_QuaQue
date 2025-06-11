@extends('layouts.backend')

@section('title', 'Banner đã xóa mềm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="title-header option-title">
                                    <h5>Banner đã xóa mềm</h5>
                                </div>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.banners.index') }}"
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
                                <table class="table all-package theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">ID</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tiêu đề</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            <th style="color: black; background-color: #f8f9fa;">Link</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hoạt động</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hiển thị lúc</th>
                                            <th style="color: black; background-color: #f8f9fa;">Vị trí</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $locationNames = [
                                                'main_hero_banner' => 'Banner Chính Đầu Trang',
                                                'small_promo_banner_top' => 'Banner Đầu Trang Nhỏ Bên Phải (Trên)',
                                                'small_promo_banner_bottom' => 'Banner Đầu Trang Nhỏ Bên Phải (Dưới)',
                                                'slider_banner' => 'Banner Trượt (Slider)',
                                                'product_section_promo_left_top' => 'Banner Sản Phẩm Dọc - Trên',
                                                'product_section_promo_left_bottom' => 'Banner Sản Phẩm Dọc - Dưới',
                                                'category_section_promo_left' => 'Banner Sản Phẩm Theo Danh Mục - Trái',
                                                'category_section_promo_right' => 'Banner Sản Phẩm Theo Danh Mục - Phải',
                                                'new_products_cashback_banner' => 'Banner Sản Phẩm Mới (Hoàn Tiền)',
                                                'new_products_promo_left' => 'Banner Sản Phẩm Mới (Trái)',
                                                'new_products_promo_right' => 'Banner Sản Phẩm Mới (Phải)',
                                                'last_page_promo_banner' => 'Banner Cuối Trang (Quảng Cáo)',
                                            ];
                                        @endphp
                                        @forelse ($banners as $banner)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $banner->id }}">
                                                </td>
                                                <td>{{ $banner->id }}</td>
                                                <td>{{ $banner->title }}</td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $banner->image) }}"
                                                        alt="{{ $banner->title }}" class="w-20 h-20 object-cover"
                                                        width="100px">
                                                </td>
                                                <td>{{ $banner->link }}</td>
                                                <td>{{ $banner->active ? 'Có' : 'Không' }}</td>
                                                <td>{{ $banner->display_at }}</td>
                                                <td>{{ $locationNames[$banner->location] ?? $banner->location ?? 'N/A' }}</td>
                                                <td>{{ $banner->deleted_at }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-success restore-btn"
                                                                data-bs-toggle="modal" data-bs-target="#restoreModal"
                                                                data-id="{{ $banner->id }}"
                                                                data-title="{{ $banner->title }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-danger force-delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#forceDeleteModal"
                                                                data-id="{{ $banner->id }}"
                                                                data-title="{{ $banner->title }}">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="text-center">Không có banner nào đã bị xóa mềm.
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
                                        <i data-feather="trash"></i> Xóa đã chọn (vĩnh viễn)
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
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục banner "<span id="bannerTitleRestore"></span>" không?
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
                    Bạn có chắc chắn muốn khôi phục <span id="selectedTrashedBannerCountRestore"></span> banner đã chọn
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
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn banner "<span id="bannerTitleForceDelete"></span>" không? Hành động
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
                    Bạn có chắc chắn muốn xóa vĩnh viễn <span id="selectedTrashedBannerCount"></span> banner đã chọn không?
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
            $('#table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ banner",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ banner",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy banner nào đã bị xóa mềm.",
                }
            });

            // Xử lý sự kiện click nút khôi phục
            $('.restore-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#bannerTitleRestore').text(title);
                $('#restoreForm').attr('action', '{{ url('admin/banners/') }}' + '/' + id + '/restore');
            });

            // Xử lý sự kiện click nút xóa vĩnh viễn
            $('.force-delete-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#bannerTitleForceDelete').text(title);
                $('#forceDeleteForm').attr('action', '{{ url('admin/banners/') }}' + '/' + id + '/force');
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
                    $('#selectedTrashedBannerCountRestore').text(selectedIds.length);
                    $('#bulkRestoreModal').modal('show');

                    $('#confirm-bulk-restore-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.banners.bulkRestore') }}',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkRestoreModal').modal('hide');
                                $('#successMessageContent').text(response.message || 'Khôi phục banner đã chọn thành công!');
                                $('#successMessageModal').modal('show');
                                $('#successMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                $('#bulkRestoreModal').modal('hide');
                                let errorMessage = 'Lỗi khi khôi phục banner đã chọn';
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
                    $('#errorMessageContent').text('Vui lòng chọn ít nhất một banner để khôi phục.');
                    $('#errorMessageModal').modal('show');
                }
            });

            $('#bulk-force-delete-btn').click(function() {
                var selectedIds = [];
                $('.row-checkbox:checked').each(function() {
                    selectedIds.push($(this).val());
                });

                if (selectedIds.length > 0) {
                    $('#selectedTrashedBannerCount').text(selectedIds.length);
                    $('#bulkForceDeleteModal').modal('show');

                    $('#confirm-bulk-force-delete-btn').off('click').on('click', function() {
                        $.ajax({
                            url: '{{ route('admin.banners.bulkForceDelete') }}',
                            method: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}',
                                ids: selectedIds
                            },
                            success: function(response) {
                                $('#bulkForceDeleteModal').modal('hide');
                                $('#successMessageContent').text(response.message || 'Xóa vĩnh viễn banner đã chọn thành công!');
                                $('#successMessageModal').modal('show');
                                $('#successMessageModal').on('hidden.bs.modal', function () {
                                    window.location.reload();
                                });
                            },
                            error: function(xhr) {
                                $('#bulkForceDeleteModal').modal('hide');
                                let errorMessage = 'Lỗi khi xóa vĩnh viễn banner đã chọn';
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
                    $('#errorMessageContent').text('Vui lòng chọn ít nhất một banner để xóa vĩnh viễn.');
                    $('#errorMessageModal').modal('show');
                }
            });
        });
    </script>
@endpush
