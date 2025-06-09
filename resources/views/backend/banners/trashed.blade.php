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
                                        <th style="color: black; background-color: #f8f9fa;">ID</th>
                                        <th style="color: black; background-color: #f8f9fa;">Tiêu đề</th>
                                        <th style="color: black; background-color: #f8f9fa;">Ảnh</th>
                                        <th style="color: black; background-color: #f8f9fa;">Link</th>
                                        <th style="color: black; background-color: #f8f9fa;">Hoạt động</th>
                                        <th style="color: black; background-color: #f8f9fa;">Hiển thị lúc</th>
                                        <th style="color: black; background-color: #f8f9fa;">Ngày xóa</th>
                                        <th style="color: black; background-color: #f8f9fa;">Hành động</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($banners as $banner)
                                        <tr>
                                            <td>{{ $banner->id }}</td>
                                            <td>{{ $banner->title }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-20 h-20 object-cover">
                                            </td>
                                            <td>{{ $banner->link }}</td>
                                            <td>{{ $banner->active ? 'Có' : 'Không' }}</td>
                                            <td>{{ $banner->display_at }}</td>
                                            <td>{{ $banner->deleted_at }}</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-success restore-btn"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#restoreModal"
                                                           data-id="{{ $banner->id }}"
                                                           data-title="{{ $banner->title }}">
                                                            <i class="ri-refresh-line"></i> Khôi phục
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger force-delete-btn"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#forceDeleteModal"
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
                                            <td colspan="8" class="text-center">Không có banner nào đã bị xóa mềm.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
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

{{-- Force Delete Modal --}}
<div class="modal fade" id="forceDeleteModal" tabindex="-1" aria-labelledby="forceDeleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="forceDeleteModalLabel">Xác nhận xóa vĩnh viễn banner</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa vĩnh viễn banner "<span id="bannerTitleForceDelete"></span>" không? Hành động này không thể hoàn tác.
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

@includeIf('backend.footer')
@endsection

@push('scripts')
<script>
    $(document).ready(function () {
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

    });
</script>
@endpush
