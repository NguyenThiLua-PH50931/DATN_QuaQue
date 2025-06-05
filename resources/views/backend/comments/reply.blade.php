@extends('layouts.backend')
@push('styles')
    <style>
        .main-content {
            min-height: calc(100vh - 120px); /* Điều chỉnh dựa trên chiều cao header/footer */
            padding-bottom: 20px;
        }
        .page-content {
            padding: 20px;
        }
        .container-fluid {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .card-body {
            padding: 10px;
        }
        .text-muted {
            color: #6c757d;
        }
    </style>
@endpush

@section('title', 'sua binh luon')

@section('content')
 <div class="page-body">


 <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <h1 class="mb-4">Trả lời bình luận</h1>

                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if ($comment)
                    <form method="POST" action="{{ route('admin.comments.reply', $comment->id) }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="reply">Phản hồi</label>
                            <textarea name="reply" class="form-control" rows="3" required></textarea>
                            @error('reply')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-success">Gửi phản hồi</button>
                        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">Quay lại</a>
                    </form>

                    <!-- Hiển thị nội dung bình luận gốc -->
                    <h3 class="mt-4">Bình luận gốc</h3>
                    <div class="card mb-2">
                        <div class="card-body">
                            <p>{{ $comment->content }}</p>
                            <small class="text-muted">Bởi: {{ $comment->user->name }} - {{ $comment->created_at }}</small>
                        </div>
                    </div>

                    <!-- Hiển thị các phản hồi (nếu có) -->
                    @if ($comment->replies->count() > 0)
                        <h3 class="mt-4">Phản hồi từ admin</h3>
                        @foreach ($comment->replies as $reply)
                            <div class="card mb-2">
                                <div class="card-body">
                                    <p>{{ $reply->reply }}</p>
                                    <small class="text-muted">Bởi: {{ $reply->admin->name }} - {{ $reply->created_at }}</small>
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
@includeIf('backend.footer')
@endsection
