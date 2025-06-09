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
        .alert {
            border-radius: 5px;
        }
        .text-muted {
            color: #6c757d;
        }
        .reply-card {
            background-color: #f8f9fa;
            border-left: 3px solid #28a745;
        }
    </style>
@endpush

@section('title', 'Chi tiết yêu cầu hỗ trợ')

@section('content')
<div class="page-body">
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">
                <div class="card">
                    <div class="card-header">
                        <h1 class="mb-0">Yêu cầu hỗ trợ #{{ $ticket->id }}</h1>
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

                        @if ($ticket)
                            <div class="mb-4">
                                <h4>Thông tin yêu cầu</h4>
                                <p><strong>Người gửi:</strong> {{ $ticket->user->name }}</p>
                                <p><strong>Email:</strong> {{ $ticket->user->email }}</p>
                                <p><strong>Tiêu đề:</strong> {{ $ticket->title }}</p>
                                <p><strong>Nội dung:</strong> {{ $ticket->content }}</p>
                                <p><strong>Trạng thái:</strong>
                                    @if ($ticket->status == 'pending')
                                        <span class="badge badge-warning">Chờ xử lý</span>
                                    @else
                                        <span class="badge badge-success">Đã giải quyết</span>
                                    @endif
                                </p>
                                <p><strong>Thời gian gửi:</strong> {{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : 'Chưa xác định' }}</p>
                            </div>

                            <h4 class="mb-3">Gửi phản hồi</h4>
                            <form method="POST" action="{{ route('admin.support-ticket.storeReply', $ticket->id) }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="reply">Nội dung phản hồi</label>
                                    <textarea name="reply" class="form-control" rows="5" aria-label="Nội dung phản hồi" required>{{ old('reply') }}</textarea>
                                    @error('reply')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-sm">Gửi phản hồi</button>
                                <a href="{{ route('admin.support-ticket.index') }}" class="btn btn-secondary btn-sm">Quay lại</a>
                            </form>

                            @if ($ticket->replies->count() > 0)
                                <h4 class="mt-4">Lịch sử phản hồi</h4>
                                @foreach ($ticket->replies as $reply)
                                    <div class="card reply-card mb-2">
                                        <div class="card-body">
                                            <p>{{ $reply->reply }}</p>
                                            <small class="text-muted">Bởi: {{ $reply->admin->name }} - {{ $reply->created_at->format('d/m/Y H:i') }}</small>
                                        </div>
                                    </div>
                                @endforeach
                            @endif
                        @else
                            <div class="alert alert-danger">
                                Yêu cầu hỗ trợ không tồn tại.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@includeIf('backend.footer')
@endsection
