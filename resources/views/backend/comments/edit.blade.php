@extends('layouts.backend')
@push('styles')
    <style>
        .page-body {
            background-color: #f8f9fa;
        }
        .main-content {
            min-height: calc(100vh - 120px);
            padding: 20px;
        }
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .card-header {
            background-color: #ffffff;
            border-bottom: 1px solid #e9ecef;
            padding: 15px 20px;
            border-radius: 10px 10px 0 0;
        }
        .card-body {
            padding: 20px;
        }
        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }
        .badge-success { background-color: #28a745; }
        .badge-warning { background-color: #ffc107; color: #212529; }
        .form-control, .btn {
            border-radius: 5px;
        }
        .btn-sm {
            padding: 6px 12px;
            font-size: 0.875rem;
            border-radius: 5px;
            transition: all 0.2s ease;
        }
        .btn-sm:hover {
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .action-buttons {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }
        .alert {
            border-radius: 5px;
        }
        .text-muted {
            color: #6c757d;
        }
    </style>
@endpush

@section('title', 'Sửa trạng thái bình luận')

@section('content')
<div class="page-body">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">Sửa trạng thái bình luận</h1>
                    </div>
                    <div class="card-body">
                        @if (session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif
                        @if (session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if ($comment)
                            <div class="mb-4">
                                <h4>Thông tin bình luận</h4>
                                <p><strong>Người dùng:</strong> {{ $comment->user->name }}</p>
                                <p><strong>Sản phẩm:</strong> {{ $comment->product->name }}</p>
                                <p><strong>Nội dung:</strong> {{ $comment->content }}</p>
                                <p><strong>Trạng thái hiện tại:</strong>
                                    @if ($comment->status == 'visible')
                                        <span class="badge badge-success">Hiện</span>
                                    @else
                                        <span class="badge badge-warning">Ẩn</span>
                                    @endif
                                </p>
                                <p><strong>Thời gian:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <form method="POST" action="{{ route('admin.comments.update', $comment->id) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-3">
                                    <label for="content">Nội dung bình luận</label>
                                    <textarea name="content" class="form-control" rows="5" readonly>{{ $comment->content }}</textarea>
                                </div>
                                <div class="form-group mb-3">
                                    <label for="status">Cập nhật trạng thái</label>
                                    <select name="status" class="form-control" required>
                                        <option value="visible" {{ $comment->status == 'visible' ? 'selected' : '' }}>Hiện</option>
                                        <option value="hidden" {{ $comment->status == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                    @error('status')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật trạng thái</button>
                                <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                            </form>

                            <!-- Nút riêng để trả lời -->
                            <a href="{{ route('admin.comments.reply', $comment->id) }}" class="btn btn-info btn-sm mt-3">Trả lời bình luận</a>

                            <!-- Hiển thị các phản hồi -->
                            @if ($comment->replies->count() > 0)
                                <h4 class="mt-4">Phản hồi từ admin</h4>
                                @foreach ($comment->replies as $reply)
                                    <div class="card mb-2">
                                        <div class="card-body">
                                            <p>{{ $reply->reply }}</p>
                                            <small class="text-muted">Bởi: {{ $reply->admin->name }} - {{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                            <div class="action-buttons mt-2">
                                                <a href="{{ route('admin.comments.editReply', [$comment->id, $reply->id]) }}" class="btn btn-primary btn-sm">Sửa</a>
                                                <form method="POST" action="{{ route('admin.comments.destroyReply', [$comment->id, $reply->id]) }}" style="display:inline;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc muốn xóa phản hồi này?')">Xóa</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @else
                            <div class="alert alert-danger">
                                Bình luận không tồn tại.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
(includeIf('backend.footer'))
@endsection
