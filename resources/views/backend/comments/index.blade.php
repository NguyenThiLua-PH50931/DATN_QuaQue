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
            max-width: 1400px;
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

        .table {
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
        }

        .table th,
        .table td {
            vertical-align: middle;
            padding: 12px;
        }

        .badge {
            padding: 6px 12px;
            border-radius: 12px;
            font-size: 0.9rem;
            font-weight: 500;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #212529;
        }

        .badge-success {
            background-color: #28a745;
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

        .form-control,
        .btn {
            border-radius: 5px;
        }

        .alert {
            border-radius: 5px;
        }

        .pagination {
            justify-content: center;
            margin-top: 20px;
        }

        .pagination .page-link {
            border-radius: 5px;
            margin: 0 3px;
        }

        @media (max-width: 768px) {
            .action-buttons {
                flex-direction: column;
                gap: 5px;
            }

            .table-responsive {
                font-size: 0.85rem;
            }
        }
    </style>
@endpush

@section('title', 'Danh sách bình luận')

@section('content')
    <div class="page-body">
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="card">
                        <div class="title-header option-title">
                            <h5>Quản lý bình luận</h5>
                        </div>
                        <div class="card-body">
                            @if (session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"
                                        aria-label="Close"></button>
                                </div>
                            @endif

                            <!-- Form tìm kiếm và lọc -->
                            <form method="GET" action="{{ route('admin.comments.index') }}" class="mb-4">
                                <div class="row g-3">
                                    <div class="col-md-3">
                                        <select name="status" class="form-control">
                                            <option value="">-- Chọn trạng thái --</option>
                                            <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>
                                                Hiện</option>
                                            <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <input type="date" name="date" class="form-control"
                                            value="{{ request('date') }}">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    </div>
                                </div>
                            </form>

                            <div class="table-responsive overflow-hidden">
                                <table class="table all-package theme-table">
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
                                                <td>{{ $comment->created_at->format('d/m/Y H:i') }}</td>
                                                <td>
                                                    @if ($comment->status == 'visible')
                                                        <span class="badge badge-success">Hiện</span>
                                                    @else
                                                        <span class="badge badge-warning">Ẩn</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="action-buttons">
                                                        <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                                            class="btn btn-primary btn-sm">Sửa trạng thái</a>
                                                        <form method="POST"
                                                            action="{{ route('admin.comments.destroy', $comment->id) }}"
                                                            style="display:inline;">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-danger btn-sm"
                                                                onclick="return confirm('Bạn có chắc muốn xóa?')">Xóa</button>
                                                        </form>
                                                        <a href="{{ route('admin.comments.reply', $comment->id) }}"
                                                            class="btn btn-info btn-sm">Trả lời</a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="7" class="text-center">Không có bình luận nào.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center">
                                {{ $comments->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('backend.footer')
@endsection
