@extends('layouts.backend')

@section('title', 'Blog đã xóa mềm')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="title-header option-title">
                                    <h5>Blog đã xóa mềm</h5>
                                </div>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.blog.index') }}"
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
                                <strong>Lưu ý:</strong> Các blog đã xóa mềm sẽ được tự động xóa vĩnh viễn sau 30 ngày.
                            </small>

                            @if (session('success'))
                                <div class="alert alert-success">
                                    {{ session('success') }}
                                </div>
                            @endif

                            <div class="table-responsive category-table">
                                <table class="table all-package theme-table" id="blogTable">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa; width: 30px;">
                                                <input type="checkbox" id="select-all-checkbox">
                                            </th>
                                            <th style="color: black; background-color: #f8f9fa;">ID</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tiêu đề</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tác giả</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($blogs as $blog)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="selected_ids[]"
                                                        value="{{ $blog->id }}">
                                                </td>
                                                <td>{{ $blog->id }}</td>
                                                <td style="max-width: 200px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                    {{ $blog->title }}
                                                </td>
                                                <td>
                                                    <img src="{{ asset('storage/' . $blog->image) }}"
                                                        alt="{{ $blog->title }}" class="w-20 h-20 object-cover"
                                                        width="80px">
                                                </td>
                                                <td>{{ $blog->author ?? 'N/A' }}</td>
                                                <td>{{ $blog->deleted_at->format('d-m-Y H:i:s') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-success restore-btn"
                                                                data-id="{{ $blog->id }}"
                                                                data-name="{{ $blog->title }}">
                                                                <i class="ri-refresh-line"></i> Khôi phục
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a href="javascript:void(0)"
                                                                class="btn btn-sm btn-danger force-delete-btn"
                                                                data-id="{{ $blog->id }}"
                                                                data-name="{{ $blog->title }}">
                                                                <i class="ri-delete-bin-line"></i> Xóa vĩnh viễn
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có blog nào đã bị xóa mềm.
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

@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            try {
                $('#blogTable').DataTable({
                    language: {
                        search: "Tìm kiếm:",
                        lengthMenu: "Hiển thị _MENU_ blog",
                        info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ blog",
                        paginate: {
                            first: "Đầu",
                            last: "Cuối",
                            next: "Sau",
                            previous: "Trước"
                        },
                        zeroRecords: "Không tìm thấy blog nào đã bị xóa mềm.",
                    },
                    "columnDefs": [{
                        "orderable": false,
                        "targets": [0, 6]
                    }]
                });
            } catch (error) {
                console.warn('DataTable initialization failed:', error);
            }

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

            // Xử lý nút kiểm tra tự động xóa
            $('#check-auto-delete-btn').click(function() {
                console.log('Checking auto delete status...');
                        $.ajax({
                    url: '{{ route('admin.blog.checkAutoDeleteStatus') }}',
                    method: 'GET',
                    dataType: 'json',
                            success: function(response) {
                        console.log('Auto delete status response:', response);
                        let content = '<div class="row">';
                        content += '<div class="col-md-6"><strong>Tổng số blog đã xóa:</strong> ' + response.total + '</div>';
                        content += '<div class="col-md-6"><strong>Sắp được xóa (≤7 ngày):</strong> ' + response.will_be_deleted_soon + '</div>';
                        content += '</div><hr>';

                        if (response.days_until_auto_delete.length > 0) {
                            content += '<h6>Chi tiết:</h6><div class="table-responsive"><table class="table table-sm">';
                            content += '<thead><tr><th>Blog</th><th>Còn lại</th><th>Ngày xóa</th></tr></thead><tbody>';

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
                            content += '<p class="text-muted">Không có blog nào trong thùng rác.</p>';
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
@endpush
