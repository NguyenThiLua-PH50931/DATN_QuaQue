@extends('layouts.backend')
@push('styles')
    <style>
        .badge {
            padding: 5px 10px;
            border-radius: 3px;
            color: white;
        }
        .badge-warning { background-color: #ffca2c; }
        .badge-success { background-color: #28a745; }
        .badge-danger { background-color: #dc3545; }
    </style>
@endpush

@section('title', 'Danh sach binh luon')

@section('content')
<div class="page-body">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <h1 class="mb-4">Quản lý bình luận</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <!-- Form tìm kiếm và lọc -->
                <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-4">
                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Tìm kiếm..." value="{{ request('search') }}">
                        </div>
                        <div class="col-md-3">
                            <select name="status" class="form-control">
                                <option value="">-- Chọn trạng thái --</option>
                                <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ duyệt</option>
                                <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Đã duyệt</option>
                                <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Bị từ chối</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-primary">Lọc</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người dùng</th>
                                <th>Sản phẩm</th>
                                <th>Nội dung</th>
                                <th>Thời gian</th>
                                <th>Trạng thái</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($comments as $comment)
                                <tr>
                                    <td>{{ $comment->id }}</td>
                                    <td>{{ $comment->user->name }}</td>
                                    <td>{{ $comment->product->name }}</td>
                                    <td>{{ Str::limit($comment->content, 50) }}</td>
                                    <td>{{ $comment->created_at }}</td>
                                    <td>
                                        @if ($comment->status == 'pending')
                                            <span class="badge badge-warning">Chờ duyệt</span>
                                        @elseif ($comment->status == 'approved')
                                            <span class="badge badge-success">Đã duyệt</span>
                                        @else
                                            <span class="badge badge-danger">Bị từ chối</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if ($comment->status == 'pending')
                                            <!-- Nút Duyệt -->
                                            <form method="POST" action="{{ route('admin.comments.approve', $comment->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Bạn có chắc muốn duyệt bình luận này?')">Duyệt</button>
                                            </form>
                                            <!-- Nút Từ chối -->
                                            <form method="POST" action="{{ route('admin.comments.reject', $comment->id) }}" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-warning btn-sm" onclick="return confirm('Bạn có chắc muốn từ chối bình luận này?')">Từ chối</button>
                                            </form>
                                        @endif
                                        <a href="{{ route('admin.comments.edit', $comment->id) }}" class="btn btn-primary btn-sm">Sửa</a>
                                        <form method="POST" action="{{ route('admin.comments.destroy', $comment->id) }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7">Không có bình luận nào.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $comments->links() }}
            </div>
        </div>
    </div>
</div>
@includeIf('backend.footer')
@endsection
