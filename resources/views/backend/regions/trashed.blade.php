@extends('layouts.backend')

@section('title', 'Vùng miền đã xóa')

@section('content')
    <div class="page-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12">
                    <div class="card card-table">
                        <div class="card-body">
                            <div class="title-header option-title">
                                <h5>Vùng miền đã xóa</h5>
                                <form class="d-inline-flex">
                                    <a href="{{ route('admin.regions.index') }}"
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

                            <div class="table-responsive region-table">
                                <table class="table all-package theme-table" id="trashed_regions_table">
                                    <thead>
                                        <tr>
                                            <th style="color: black; background-color: #f8f9fa;">Tên vùng miền</th>
                                            <th style="color: black; background-color: #f8f9fa;">Slug</th>
                                            <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                            <th style="color: black; background-color: #f8f9fa;">Tùy chọn</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($regions as $region)
                                            <tr>
                                                <td>{{ $region->name }}</td>
                                                <td>{{ $region->slug }}</td>
                                                <td>{{ $region->deleted_at->format('d-m-Y') }}</td>
                                                <td>
                                                    <ul>
                                                        <li>
                                                            <button type="button" class="btn btn-success btn-sm restore-btn"
                                                                data-id="{{ $region->id }}" data-name="{{ $region->name }}">
                                                                <i class="ri-arrow-go-back-line"></i> Khôi phục
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button type="button" class="btn btn-danger btn-sm force-delete-btn"
                                                                data-id="{{ $region->id }}" data-name="{{ $region->name }}">
                                                                <i data-feather="trash-2"></i> Xoá vĩnh viễn
                                                            </button>
                                                        </li>
                                                    </ul>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add any necessary modals here, e.g., confirmation modals -->

    </div>
    @includeIf('backend.footer')

    <!-- Modal xác nhận khôi phục -->
    <div class="modal fade" id="restoreModal" tabindex="-1" aria-labelledby="restoreModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="restoreModalLabel">Xác nhận khôi phục</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn khôi phục vùng miền <span id="restoreRegionName"></span>?
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
    <div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Bạn có chắc chắn muốn xóa vĩnh viễn vùng miền <span id="forceDeleteRegionName"></span>?
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
@endsection

@push('scripts')
    {{-- Include Toastr CSS and JS --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable and store the instance
            const table = $('#trashed_regions_table').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ vùng miền đã xóa",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ vùng miền đã xóa",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy vùng miền đã xóa nào.",
                }
            });

            // Modal xác nhận khôi phục
            $('.restore-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#restoreRegionName').text('"' + name + '"');
                $('#restoreForm').attr('action', '/admin/regions/' + id + '/restore');
                $('#restoreModal').modal('show');
            });
            // Modal xác nhận xóa vĩnh viễn
            $('.force-delete-btn').click(function() {
                var id = $(this).data('id');
                var name = $(this).data('name');
                $('#forceDeleteRegionName').text('"' + name + '"');
                $('#forceDeleteForm').attr('action', '/admin/regions/' + id + '/force');
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
                        toastr.success(response.message || 'Xóa vĩnh viễn vùng miền thành công!');
                    },
                    error: function(xhr) {
                        $('#forceDeleteModal').modal('hide');
                        let errorMessage = 'Lỗi khi xóa vĩnh viễn vùng miền';
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
        });
    </script>
@endpush 