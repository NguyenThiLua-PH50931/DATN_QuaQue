@extends('layouts.backend')

@section('title', 'Đơn hàng')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <!-- Table Start -->
                  <div class="container-fluid">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card card-table">
                                    <div class="card-body">
                                        <div class="title-header option-title">
                                            <h5>Danh sách đơn hàng</h5>

                                        </div>
                                        <div>
                                            <div class="table-responsive">
                                                <table class="table all-package order-table theme-table" id="table_id">
                                                    <thead>
                                                        <tr>
                                                            <th style="color: black; background-color: #f8f9fa;">STT</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Người đặt</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Ngày đặt</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Mã đơn hàng</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Phương thức TT</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Trạng thái</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Số tiền</th>
                                                            <th style="color: black; background-color: #f8f9fa;">Tuỳ chọn</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($orders as $index => $order)
                                                            <tr data-bs-toggle="offcanvas" href="#order-details">
                                                                <td>{{ $index + 1 }}</td>
                                                                <td>{{ $order->user->name ?? 'N/A' }}</td>

                                                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                                <td>{{ $order->order_code }}</td>
                                                                <td>{{ $order->payment_method ?? 'N/A' }}</td>

                                                            <td>
                                                                    <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="status-form">
                                                                        @csrf
                                                                        @method('PUT')
                                                                        <select name="status" class="form-select status-select status-{{ $order->status }}" onchange="this.form.submit()">
                                                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                                                            <option value="confirmed" {{ $order->status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                                                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>Đang chuẩn bị</option>
                                                                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>Đã gửi hàng</option>
                                                                            <option value="in_transit" {{ $order->status == 'in_transit' ? 'selected' : '' }}>Đang vận chuyển</option>
                                                                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                                                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                                                                            <option value="failed_delivery" {{ $order->status == 'failed_delivery' ? 'selected' : '' }}>Giao thất bại</option>
                                                                        </select>
                                                                    </form>

                                                                </td>

                                                                @php
                                                                    $computedTotal = $order->items->sum(function ($item) {
                                                                        return ($item->price - $item->discount) * $item->quantity;
                                                                    }) + ($order->shipping_cost ?? 0);
                                                                @endphp
                                                                <td>
                                                                    {{ number_format($computedTotal, 0, ',', '.') }} VNĐ
                                                                </td>

                                                                <td>
                                                                    <ul>
                                                                        <li>
                                                                            <a href="{{ route('admin.orders.show', $order->id) }}">
                                                                                <i class="ri-eye-line"></i>
                                                                            </a>
                                                                        </li>

                                                                        <li>
                                                                            <a href="{{ route('admin.orders.tracking', $order->id) }}">
                                                                                <i class="ri-map-pin-line"></i> <!-- Icon tracking (bản đồ hoặc chỉ đường) -->
                                                                            </a>
                                                                        </li>

                                                                        @if ($order->status == 'delivered' || $order->status == 'cancelled' || $order->status == 'failed_delivery')
                                                                            <li>
                                                                                <form action="{{ route('admin.orders.destroy', ['order' => $order->id]) }}" method="POST" onsubmit="return confirm('Bạn có chắc chắn muốn xóa đơn hàng này không?');">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <button type="submit" class="border-0 bg-transparent">
                                                                                        <i class="ri-delete-bin-line text-danger"></i>
                                                                                    </button>
                                                                                </form>
                                                                            </li>
                                                                        @endif


                                                                    </ul>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Table End -->

                    <!-- Pagination Box Start -->
                   <div class="pagination-box">
    <nav class="ms-auto me-auto" aria-label="...">
        <ul class="pagination pagination-primary">
            {{-- Previous Page Link --}}
            @if ($orders->onFirstPage())
                <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Previous</a></li>
            @else
                <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">Previous</a></li>
            @endif

            {{-- Pagination Elements --}}
            @foreach ($orders->getUrlRange(1, $orders->lastPage()) as $page => $url)
                <li class="page-item {{ $orders->currentPage() == $page ? 'active' : '' }}">
                    <a class="page-link" href="{{ $url }}">{{ $page }}</a>
                </li>
            @endforeach

            {{-- Next Page Link --}}
            @if ($orders->hasMorePages())
                <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next</a></li>
            @else
                <li class="page-item disabled"><a class="page-link" href="javascript:void(0)">Next</a></li>
            @endif
        </ul>
    </nav>
</div>

                    <!-- Pagination Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection
