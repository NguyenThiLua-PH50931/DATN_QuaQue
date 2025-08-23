@extends('layouts.backend')

@section('title', 'Quản lý Banner')

@section('content')
<style>
    /* Đảm bảo scroll hoạt động bình thường */
    body, html {
        overflow: auto !important;
        height: auto !important;
    }

    /* Style cho alert */
    .alert {
        position: relative;
        z-index: 1000;
    }

    /* Đảm bảo table có thể scroll */
    .table-responsive {
        overflow: visible;
    }
</style>
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Tất cả banner</h5>
                            </div>

                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" id="success-alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if (session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" id="error-alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            {{-- Date Filter Form --}}
                            <form class="row g-3 mb-3" method="GET" action="{{ route('admin.banners.index') }}">
                                <div class="col-md-4">
                                    <label for="start_date" class="form-label">Ngày bắt đầu hiển thị:</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date"
                                        value="{{ request('start_date') }}">
                                </div>
                                <div class="col-md-4">
                                    <label for="end_date" class="form-label">Ngày dừng hiển thị:</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date"
                                        value="{{ request('end_date') }}">
                                </div>
                                <div class="col-md-4 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary me-2">Lọc</button>
                                    <a href="{{ route('admin.banners.index') }}" class="btn btn-secondary">Đặt lại</a>
                                </div>
                            </form>

                            <div class="category-table">
                                <table class="table all-package theme-table" id="table_id"
                                    style="width: 100%; table-layout: fixed;">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 5%;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">ID</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            <th style="color: black; background-color: #f8f9fa;">Vị trí</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hoạt động</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                            {{-- <th style="color: black; background-color: #f8f9fa;">Link</th> --}}
                                            {{-- <th style="color: black; background-color: #f8f9fa;">Ngày hiển thị </th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày dừng hiển thị</th> --}}

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
                                                'category_section_promo_right' =>
                                                    'Banner Sản Phẩm Theo Danh Mục - Phải',
                                                'new_products_cashback_banner' => 'Banner Sản Phẩm Mới',
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
                                                <td style="word-wrap: break-word;">
                                                    <img src="{{ asset('storage/' . $banner->image) }}"
                                                        alt="{{ $banner->title }}" class="img-thumbnail"
                                                        style="max-width: 100px; height: auto;">
                                                </td>
                                                <td style="word-wrap: break-word;">
                                                    {{ $locationNames[$banner->location] ?? ($banner->location ?? 'N/A') }}
                                                </td>
                                                <td>{{ $banner->active ? 'Có' : 'Không' }}</td>
                                                {{-- <td>{{ $banner->link }}</td>
                                                <td>{{ $banner->display_at ? $banner->display_at->format('d-m-Y H:i:s') : 'N/A' }}
                                                </td>
                                                {{-- <td>{{ $banner->link }}</td>
                                                <td>{{ $banner->active ? 'Có' : 'Không' }}</td>
                                                <td>{{ $banner->display_at ? $banner->display_at->format('d-m-Y H:i:s') : 'N/A' }}</td>
                                                <td>{{ $banner->display_end_at ? $banner->display_end_at->format('d-m-Y H:i:s') : 'N/A' }}
                                                </td> --}}
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="{{ route('admin.banners.show', $banner->id) }}"><i
                                                                    class="ri-eye-line"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="{{ route('admin.banners.edit', $banner->id) }}"><i
                                                                    class="ri-pencil-line"></i></a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)" class="delete-btn"
                                                                data-bs-toggle="modal" data-bs-target="#deleteModal"
                                                                data-id="{{ $banner->id }}"
                                                                data-title="{{ $banner->title }}">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="10" class="py-4 px-4 text-center">Không có banner nào.</td>
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
                            <form id="bulk-delete-form" action="{{ route('admin.banners.bulkDelete') }}" method="POST"
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

    {{-- Delete Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa banner</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa banner không? Banner này sẽ được xóa mềm và có thể khôi phục sau này.
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
        <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="bulkDeleteModalLabel">Xác nhận xóa hàng loạt</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Bạn có chắc chắn muốn xóa mềm <span id="selectedBannerCount"></span> banner đã chọn không?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                        <button type="button" class="btn btn-danger" id="confirm-bulk-delete-btn">Xóa hàng loạt</button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Success Message Modal --}}
        <div class="modal fade" id="successMessageModal" tabindex="-1" aria-labelledby="successMessageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="successMessageModalLabel">Thành công!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
        <div class="modal fade" id="errorMessageModal" tabindex="-1" aria-labelledby="errorMessageModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title" id="errorMessageModalLabel">Lỗi!</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Close"></button>
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
                        zeroRecords: "Không tìm thấy banner nào.",
                    }
                });

                // Xử lý sự kiện click nút xóa mềm (sử dụng event delegation)
                $(document).on('click', '.delete-btn', function(e) {
                    var id = $(this).data('id');
                    var title = $(this).data('title');

                    if (id) {
                        var formAction = '{{ url('admin/banners/') }}' + '/' + id + '/soft';
                        $('#bannerTitleDelete').text(title);
                        $('#deleteForm').attr('action', formAction);
                        console.log('Delete form action set to:', formAction);
                    } else {
                        e.preventDefault();
                        $('#errorMessageContent').text('Không thể xóa banner này do thiếu thông tin ID.');
                        $('#errorMessageModal').modal('show');
                        console.error('Error: data-id is missing for delete button:', this);
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
                        $('#selectedBannerCount').text(selectedIds.length);
                        $('#bulkDeleteModal').modal('show');

                        $('#confirm-bulk-delete-btn').off('click').on('click', function() {
                            $.ajax({
                                url: '{{ route('admin.banners.bulkDelete') }}',
                                method: 'DELETE',
                                data: {
                                    _token: '{{ csrf_token() }}',
                                    ids: selectedIds
                                },
                                success: function(response) {
                                    $('#bulkDeleteModal').modal('hide');
                                    if (response.status === 'success') {
                                        $('#successMessageContent').text(response.message ||
                                            'Xóa banner đã chọn thành công!');
                                        $('#successMessageModal').modal('show');
                                    } else if (response.status === 'warning') {
                                        $('#errorMessageContent').text(response.message ||
                                            'Có một số banner không thể xóa.');
                                        $('#errorMessageModal').modal('show');
                                    } else {
                                        $('#errorMessageContent').text(response.message ||
                                            'Lỗi khi xóa banner đã chọn.');
                                        $('#errorMessageModal').modal('show');
                                    }
                                    $('#successMessageModal').on('hidden.bs.modal',
                                        function() {
                                            window.location.reload();
                                        });
                                    $('#errorMessageModal').on('hidden.bs.modal',
                                        function() {
                                            window.location.reload();
                                        });
                                },
                                error: function(xhr) {
                                    $('#bulkDeleteModal').modal('hide');
                                    let errorMessage = 'Lỗi khi xóa banner đã chọn';
                                    if (xhr.responseJSON && xhr.responseJSON.message) {
                                        errorMessage = xhr.responseJSON.message;
                                    } else if (xhr.responseText) {
                                        errorMessage = 'Lỗi server: ' + xhr.responseText
                                            .substring(0, 100) + '...';
                                    } else {
                                        errorMessage = 'Lỗi không xác định';
                                    }
                                    $('#errorMessageContent').text(errorMessage);
                                    $('#errorMessageModal').modal('show');
                                    $('#errorMessageModal').on('hidden.bs.modal',
                                        function() {
                                            window.location.reload();
                                        });
                                }
                            });
                        });
                    } else {
                        $('#errorMessageContent').text('Vui lòng chọn ít nhất một banner để xóa.');
                        $('#errorMessageModal').modal('show');
                    }
                });

                // Hiển thị modal lỗi nếu có session error từ server (đối với xóa mềm cá nhân)
                @if (session('error'))
                    var errorMessage = "{{ session('error') }}";
                    $('#errorMessageContent').text(errorMessage);
                    $('#errorMessageModal').modal('show');
                @endif

                // Xử lý vấn đề scroll và focus sau khi redirect
                $(document).ready(function() {
                    // Nếu có thông báo lỗi hoặc thành công, scroll đến đó và focus
                    if ($('#error-alert').length > 0) {
                        $('#error-alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Tự động ẩn thông báo sau 5 giây
                        setTimeout(function() {
                            $('#error-alert').fadeOut();
                        }, 5000);
                    }

                    if ($('#success-alert').length > 0) {
                        $('#success-alert')[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                        // Tự động ẩn thông báo sau 3 giây
                        setTimeout(function() {
                            $('#success-alert').fadeOut();
                        }, 3000);
                    }

                    // Đảm bảo body có thể scroll
                    $('body').css('overflow', 'auto');
                    $('html').css('overflow', 'auto');
                });
            });
        </script>
    @endsection
