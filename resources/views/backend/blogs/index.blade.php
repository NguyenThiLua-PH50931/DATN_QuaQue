@extends('layouts.backend') 

@section('title', 'Quản lý tin tức') 

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <div class="card-body">
                        <div class="title-header option-title d-sm-flex d-block">
                            <h5>Tin tức</h5>
                            <div class="right-options">
                                <ul>
                                    <li>
                                        <a class="btn btn-solid" href="{{ route('admin.blog.create') }}">Thêm mới</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif
                        {{-- Date Filter Form --}}
                        <form class="row g-3 mb-3" method="GET" action="{{ route('admin.blog.index') }}">
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
                                <a href="{{ route('admin.blog.index') }}" class="btn btn-secondary">Đặt lại</a>
                            </div>
                        </form>
                        <div>
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-hover w-90 coupon-list-table theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tiêu đề</th>
                                            <th>Đường link</th>
                                            <th>Ngày hiển thị</th>
                                            <th>Ngày dừng hiển thị</th>
                                            <th>Hành động</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($blog as $item)
                                        <tr>
                                            <td>
                                                <div class="table-image">
                                                    @if($item->thumbnail)
                                                        <img src="{{ asset($item->thumbnail) }}" alt="{{ $item->title }}" width="100">
                                                    @endif
                                                </div>
                                            </td>

                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->slug }}</td>
                                            <td>{{ $item->start_date ? $item->start_date->format('m/d/Y') : '' }}</td>
                                            <td>{{ $item->end_date ? $item->end_date->format('m/d/Y') : '' }}</td>

                                            <td>
                                                <ul>
                                                    <li>
                                                        <a href="{{ route('admin.blog.show', $item->id) }}">
                                                            <i class="ri-eye-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="{{ route('admin.blog.edit', $item->id) }}">
                                                            <i class="ri-pencil-line"></i>
                                                        </a>
                                                    </li>

                                                    <li>
                                                        <a href="javascript:void(0)" data-bs-toggle="modal"
                                                            data-bs-target="#deleteModal{{ $item->id }}">
                                                            <i class="ri-delete-bin-line"></i>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                @foreach($blog as $item)
                                    <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel{{ $item->id }}" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="deleteModalLabel{{ $item->id }}">Xác nhận xóa</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                   Bạn chắc chắn muốn xóa <strong>{{ $item->title }}</strong>?
                                                </div>
                                                <div class="modal-footer">
                                                    <form action="{{ route('admin.blog.softDelete', $item->id) }}" method="POST" class="d-flex justify-content-end">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="submit" class="btn btn-danger">Xóa</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->

@includeIf('backend.footer')
@endsection
@push('scripts')
<script>
    $(document).ready(function () {
    $('#table_id').DataTable({
        language: {
            search: "Search:",
            lengthMenu: "",
            info: "Showing _START_ to _END_ of _TOTAL_ entries",
            paginate: {
                first: "First",
                last: "Last",
                next: "Next",
                previous: "Previous"
            },
            zeroRecords: "Không tìm thấy tin tức nào.",
        }
    });
});
</script>
@endpush

