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
                                {{-- Link to Create New Category --}}
                                <a href="{{ route('admin.categories.create') }}"
                                    class="align-items-center btn btn-theme d-flex">
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

                        {{-- Simple Search Form --}}

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
                                                        <a href="{{ route('admin.categories.edit', $category->id) }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>
                                                    <li>
                                                        {{-- Soft Delete Form (Standard Submit) --}}
                                                        <form action="{{ route('admin.categories.softDelete', $category->id) }}"
                                                            method="POST" style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc chắn muốn xoá mềm danh mục này?')">
                                                                <i class="ri-delete-bin-line"></i>
                                                            </button>
                                                        </form>
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
    });
</script>
@endpush


