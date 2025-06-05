@extends('layouts.backend')

@section('title', 'Danh mục')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Tất cả danh mục</h5>
                            <form class="d-inline-flex">
                                {{-- Link to Trashed categories --}}
                                <a href="{{ route('admin.categories.trashed') }}"
                                    class="align-items-center btn btn-warning d-flex me-2">
                                    <i data-feather="trash-2"></i> Thùng rác
                                </a>
                                {{-- Button to Create New Category --}}
                                <a href="javascript:void(0)" 
                                   class="align-items-center btn btn-theme d-flex"
                                   data-bs-toggle="modal" 
                                   data-bs-target="#createModal">
                                    <i data-feather="plus-square"></i> Thêm mới
                                </a>
                            </form>
                        </div>

                        {{-- Session Success Message --}}
                        @if(session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        <div class="table-responsive category-table">
                            <table class="table all-package theme-table" id="table_id">
                                <thead>
                                    <tr>
                                        <th style="color: black; background-color: #f8f9fa;">Tên danh mục</th>
                                        <th style="color: black; background-color: #f8f9fa;">Slug</th>
                                        <th style="color: black; background-color: #f8f9fa;">Ngày tạo</th>
                                        <th style="color: black; background-color: #f8f9fa;">Tùy chọn</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->slug }}</td>
                                            <td>{{ $category->created_at->format('d-m-Y') }}</td>
                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="javascript:void(0)" 
                                                           class="edit-btn"
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#editModal"
                                                           data-id="{{ $category->id }}"
                                                           data-name="{{ $category->name }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0)" class="delete-btn" 
                                                           data-bs-toggle="modal" 
                                                           data-bs-target="#deleteModal"
                                                           data-id="{{ $category->id }}"
                                                           data-name="{{ $category->name }}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
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
</div>

{{-- Create Modal --}}
<div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="createModalLabel">Thêm danh mục mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('admin.categories.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm mới</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Edit Modal --}}
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel">Sửa danh mục</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="edit_name" class="form-label">Tên danh mục</label>
                        <input type="text" class="form-control" id="edit_name" name="name" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Delete Modal --}}
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa danh mục "<span id="categoryName"></span>"?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
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
                lengthMenu: "Hiển thị _MENU_ danh mục",
                info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ danh mục",
                paginate: {
                    first: "Đầu",
                    last: "Cuối",
                    next: "Sau",
                    previous: "Trước"
                },
                zeroRecords: "Không tìm thấy danh mục nào.",
            }
        });

        // Xử lý sự kiện click nút xóa
        $('.delete-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#categoryName').text(name);
            $('#deleteForm').attr('action', '/admin/categories/' + id + '/soft-delete');
        });

        // Xử lý sự kiện click nút sửa
        $('.edit-btn').click(function() {
            var id = $(this).data('id');
            var name = $(this).data('name');
            $('#edit_name').val(name);
            $('#editForm').attr('action', '/admin/categories/' + id);
        });

        // Reset form khi đóng modal
        $('#createModal').on('hidden.bs.modal', function () {
            $('#createModal form')[0].reset();
        });

        $('#editModal').on('hidden.bs.modal', function () {
            $('#editModal form')[0].reset();
        });
    });
</script>
@endpush


