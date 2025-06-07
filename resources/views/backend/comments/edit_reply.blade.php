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
        .alert {
            border-radius: 5px;
        }
        .text-muted {
            color: #6c757d;
        }
    </style>
@endpush

@section('title', 'Sửa phản hồi')

@section('content')
<div class="page-body">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">Sửa phản hồi</h1>
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

                        @if ($comment && $reply)
                            <div class="mb-4">
                                <h4>Thông tin bình luận</h4>
                                <p><strong>Người dùng:</strong> {{ $comment->user->name }}</p>
                                <p><strong>Sản phẩm:</strong> {{ $comment->product->name }}</p>
                                <p><strong>Nội dung bình luận:</strong> {{ $comment->content }}</p>
                                <p><strong>Thời gian bình luận:</strong> {{ $comment->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            <form method="POST" action="{{ route('admin.comments.updateReply', [$comment->id, $reply->id]) }}">
                                @csrf
                                @method('PUT')
                                <div class="form-group mb-3">
                                    <label for="reply">Nội dung phản hồi</label>
                                    <textarea name="reply" class="form-control" rows="5" required>{{ old('reply', $reply->reply) }}</textarea>
                                    @error('reply')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Cập nhật phản hồi</button>
                                <a href="{{ route('admin.comments.reply', $comment->id) }}" class="btn btn-secondary btn-sm">Quay lại</a>
                            </form>
                        @else
                            <div class="alert alert-danger">
                                Bình luận hoặc phản hồi không tồn tại.
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
