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
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .badge:hover {
        transform: scale(1.05);
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
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

    .status-badge {
        cursor: pointer;
        user-select: none;
    }

    .status-badge:hover {
        opacity: 0.8;
    }

    .modal-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #dee2e6;
    }

    .modal-footer {
        background-color: #f8f9fa;
        border-top: 1px solid #dee2e6;
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
                        {{-- Session Success Message --}}
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
                                        <option value="visible" {{ request('status') == 'visible' ? 'selected' : '' }}>Hiện</option>
                                        <option value="hidden" {{ request('status') == 'hidden' ? 'selected' : '' }}>Ẩn</option>
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="from_date" class="form-control" value="{{ request('from_date') }}" placeholder="Từ ngày">
                                </div>
                                <div class="col-md-3">
                                    <input type="date" name="to_date" class="form-control" value="{{ request('to_date') }}" placeholder="Đến ngày">
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary w-100">Lọc</button>
                                    <a href="{{ route('admin.comments.index') }}" class="btn btn-outline-secondary w-100">Xóa lọc</a>
                                </div>
                            </div>
                        </form>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover table-bordered align-middle">
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
                                            <span
                                                class="badge status-badge {{ $comment->status == 'visible' ? 'badge-success' : 'badge-warning' }}"
                                                onclick="showStatusModal({{ $comment->id }}, '{{ $comment->status }}', '{{ $comment->user->name }}')"
                                                data-comment-id="{{ $comment->id }}"
                                                data-current-status="{{ $comment->status }}">
                                                {{ $comment->status == 'visible' ? 'Hiện' : 'Ẩn' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center justify-content-center gap-2 action-icons">
                                                <a href="{{ route('admin.comments.edit', $comment->id) }}"
                                                    class="text-primary"
                                                    title="Xem/Chi tiết">
                                                    <i class="ri-eye-line"></i>
                                                </a>
                                                <a href="{{ route('admin.comments.reply', $comment->id) }}"
                                                    class="text-info"
                                                    title="Trả lời">
                                                    <i class="ri-reply-line"></i>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    class="delete-btn text-danger"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#deleteCommentModal"
                                                    data-id="{{ $comment->id }}"
                                                    data-user="{{ $comment->user->name }}"
                                                    title="Xóa">
                                                    <i class="ri-delete-bin-line"></i>
                                                </a>
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

<!-- Modal Xóa -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn xóa bình luận của <strong id="userName"></strong>?</p>
                <p class="text-muted">Hành động này không thể hoàn tác.</p>
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

<!-- Modal Thay đổi trạng thái -->
<div class="modal fade" id="statusModal" tabindex="-1" aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="statusModalLabel">Thay đổi trạng thái</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn thay đổi trạng thái bình luận của <strong id="modalUserName"></strong> thành
                    <strong id="newStatusText"></strong>?
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="statusForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="status" id="statusInput">
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal Thông báo thành công -->
<div class="modal fade" id="successModal" tabindex="-1" aria-labelledby="successModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="successModalLabel">Thành công!</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="successMessage">Thao tác đã được thực hiện thành công!</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal"
                    onclick="location.reload()">Đóng</button>
            </div>
        </div>
    </div>
</div>

@includeIf('backend.footer')
@endsection

@push('scripts')
<script>
    // Hiển thị modal xóa
    function showDeleteModal(commentId, userName) {
        document.getElementById('userName').textContent = userName;
        document.getElementById('deleteForm').action = `/admin/comments/${commentId}`;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    }

    // Hiển thị modal thay đổi trạng thái
    function showStatusModal(commentId, currentStatus, userName) {
        document.getElementById('modalUserName').textContent = userName;
        document.getElementById('newStatusText').textContent = currentStatus === 'visible' ? 'Ẩn' : 'Hiện';
        document.getElementById('statusInput').value = currentStatus === 'visible' ? 'hidden' : 'visible';
        document.getElementById('statusForm').action = `/admin/comments/${commentId}`;
        new bootstrap.Modal(document.getElementById('statusModal')).show();
    }

    // Hiển thị modal thành công
    function showSuccessModal(message) {
        document.getElementById('successMessage').textContent = message;
        new bootstrap.Modal(document.getElementById('successModal')).show();
    }

    // Xử lý form thay đổi trạng thái
    document.getElementById('statusForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'PUT');
        const commentId = this.action.split('/').pop();

        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content'));

        xhr.onload = function() {
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response:', xhr.responseText);
            console.log('XHR Content-Type:', xhr.getResponseHeader('content-type'));

            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // Đóng modal
                    bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                    // Hiển thị modal thành công
                    showSuccessModal('Đã cập nhật trạng thái thành công!');
                } else {
                    alert('Có lỗi xảy ra khi cập nhật trạng thái!');
                }
            } catch (e) {
                console.log('Not JSON response, treating as success');
                // Nếu không parse được JSON, có thể là redirect thành công
                bootstrap.Modal.getInstance(document.getElementById('statusModal')).hide();
                showSuccessModal('Đã cập nhật trạng thái thành công!');
            }
        };

        xhr.onerror = function() {
            console.error('XHR Error');
            alert('Có lỗi xảy ra khi cập nhật trạng thái!');
        };

        xhr.send(formData);
    });

    // Xử lý form xóa
    document.getElementById('deleteForm').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append('_method', 'DELETE');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', this.action, true);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', document.querySelector('meta[name="csrf-token"]').getAttribute(
            'content'));

        xhr.onload = function() {
            console.log('XHR Status:', xhr.status);
            console.log('XHR Response:', xhr.responseText);
            console.log('XHR Content-Type:', xhr.getResponseHeader('content-type'));

            try {
                const data = JSON.parse(xhr.responseText);
                if (data.success) {
                    // Đóng modal xóa
                    bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                    // Hiển thị modal thành công
                    showSuccessModal('Đã xóa bình luận thành công!');
                }
            } catch (e) {
                console.log('Not JSON response, treating as success');
                // Nếu không parse được JSON, có thể là redirect thành công
                bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
                showSuccessModal('Đã xóa bình luận thành công!');
            }
        };

        xhr.onerror = function() {
            console.error('XHR Error');
            alert('Có lỗi xảy ra khi xóa bình luận!');
        };

        xhr.send(formData);
    });
</script>
@endpush