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
                          <form method="GET" action="{{ route('admin.blog.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <!-- Lọc từ ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_from" class="form-control"
                                            value="{{ request('date_from') }}">
                                    </div>
                                    <!-- Lọc đến ngày -->
                                    <div class="col-md-2">
                                        <input type="date" name="date_to" class="form-control"
                                            value="{{ request('date_to') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>

                        <div>
                            <div class="table-responsive overflow-hidden">
                                <table class="table table-hover w-90 coupon-list-table theme-table" id="table_id">
                                    <thead>
                                        <tr>
                                            <th>Ảnh</th>
                                            <th>Tiêu đề</th>
                                            <th>Đường Link</th>
                                            <th>Ngày tạo</th>
                                            <th>Hàng động</th>
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
                                            <td>{{ $item->created_at->format('d/m/Y') }}</td>

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
                                                    <form action="{{ route('admin.blog.destroy', $item->id) }}" method="POST" class="d-flex justify-content-end">
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

