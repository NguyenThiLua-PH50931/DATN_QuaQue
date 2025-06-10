@extends('layouts.backend')

@section('title', 'Đơn hỗ trợ')

@section('content')
    <!-- Ticket Section Start -->
    <div class="page-body">
        <div class="container-fluid">
            <div class="card">
                <div class="title-header option-title">
                    <h5>Yêu cầu hỗ trợ</h5>
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

                    <!-- Form tìm kiếm và lọc -->
                    <form method="GET" action="{{ route('admin.support-ticket.index') }}" class="mb-4">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control"
                                    placeholder="Tìm kiếm theo tiêu đề, nội dung, người gửi..."
                                    value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">-- Chọn trạng thái --</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý
                                    </option>
                                    <option value="resolved" {{ request('status') == 'resolved' ? 'selected' : '' }}>Đã giải
                                        quyết</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
                            </div>
                            <div class="col-md-2">
                                <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>Date</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th>Options</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tickets as $ticket)
                                    <tr>
                                        <td>#{{ $ticket->id }}</td>
                                        <td>{{ $ticket->created_at ? $ticket->created_at->format('d/m/Y') : 'Chưa xác định' }}
                                        </td>
                                        <td>{{ Str::limit($ticket->title, 50) }}</td>
                                        <td>
                                            @if ($ticket->status == 'pending')
                                                <span class="badge badge-warning">Chờ xử lý</span>
                                            @else
                                                <span class="badge badge-success">Đã giải quyết</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div style="display: flex; gap: 6px; align-items: center;">
                                                <a href="{{ route('admin.support-ticket.show', $ticket->id) }}"
                                                    class="btn btn-primary btn-sm" title="Xem chi tiết"
                                                    style="width: 36px; height: 36px; padding: 0; display: flex; justify-content: center; align-items: center;">
                                                    <i class="fa fa-eye"></i>
                                                </a>

                                                <form method="POST"
                                                    action="{{ route('admin.support-ticket.destroy', $ticket->id) }}"
                                                    style="margin: 0;">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm" title="Xóa"
                                                        onclick="return confirm('Bạn có chắc muốn xóa?')"
                                                        style="width: 36px; height: 36px; padding: 0; display: flex; justify-content: center; align-items: center;">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Không có yêu cầu hỗ trợ nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $tickets->links('pagination::bootstrap-5') }}
                    </div>
                </div>
            </div>
        </div>

        @includeIf('backend.footer')
    </div>
    <!-- Ticket Section End -->
    </div>
    <!-- Page Body End-->
@endsection
{{ url('/admin//create') }}
