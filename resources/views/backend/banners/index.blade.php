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
                                <a href="{{ route('admin.banners.trashed') }}"
                                    class="align-items-center btn btn-warning d-flex me-2">
                                    <i data-feather="trash-2"></i> Thùng rác
                                </a>
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
                                            <td>{{ $banner->id }}</td>
                                            <td>{{ $banner->title }}</td>
                                            <td>
                                                <img src="{{ asset('storage/' . $banner->image) }}" alt="{{ $banner->title }}" class="w-20 h-20 object-cover" width="100px">
                                            </td>
                                            <td>{{ $banner->link }}</td>
                                            <td>{{ $banner->active ? 'Có' : 'Không' }}</td>
                                            <td>{{ $banner->display_at }}</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('admin.banners.show', $banner->id) }}"><i class="ri-eye-line"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="{{ route('admin.banners.edit', $banner->id) }}"><i class="ri-pencil-line"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="btn btn-sm btn-danger delete-btn"
                                                           data-bs-toggle="modal"
                                                           data-bs-target="#deleteModal"
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
                                            <td colspan="7" class="py-4 px-4 text-center">Không có banner nào.</td>
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

    });
</script>
@endpush
