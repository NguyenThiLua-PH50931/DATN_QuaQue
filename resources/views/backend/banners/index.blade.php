@extends('layouts.backend')

@section('title', 'Quản lý Banner')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Tất cả banner</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.banners.create') }}"
                                        class="align-items-center btn btn-theme d-flex">
                                        <i data-feather="plus-square"></i> Thêm mới
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
                                            <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                        </tr>
                                    </thead>
                                    <tbody>
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
                                                <td colspan="8" class="py-4 px-4 text-center">Không có banner nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.banners.trashed') }}"
                                        class="align-items-center btn btn-warning d-flex me-2">
                                        <i data-feather="trash-2"></i> Thùng rác
                                    </a>
                                    <button type="button" id="bulk-delete-btn"
                                        class="align-items-center btn btn-danger d-flex ms-2" style="display: none;">
                                        <i data-feather="trash"></i> Xóa đã chọn
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
                    Bạn có chắc chắn muốn xóa mềm banner "<span id="bannerTitleDelete"></span>" không?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
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

    @includeIf('backend.footer')
@endsection

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
                    zeroRecords: "Không tìm thấy banner nào.",
                }
            });

            // Xử lý sự kiện click nút xóa mềm
            $('.delete-btn').click(function() {
                var id = $(this).data('id');
                var title = $(this).data('title');
                $('#bannerTitleDelete').text(title);
                $('#deleteForm').attr('action', '{{ url('admin/banners/') }}' + '/' + id + '/soft');
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
                    $('#selectedBannerCount').text(selectedIds.length);
                    $('#bulkDeleteModal').modal('show');

                    // Gán sự kiện cho nút xác nhận trong modal
                    $('#confirm-bulk-delete-btn').off('click').on('click', function() {
                        $('#bulk-delete-ids').val(selectedIds.join(','));
                        $('#bulk-delete-form').submit();
                        $('#bulkDeleteModal').modal('hide');
                    });
                } else {
                    alert('Vui lòng chọn ít nhất một banner để xóa.');
                }
            });

        });
    </script>
@endpush
