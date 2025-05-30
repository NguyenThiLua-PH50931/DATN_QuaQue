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
                                        <th>STT</th>
                                        <th>Người đặt</th>
                                        <th>Ngày đặt</th>
                                        <th>Mã đơn hàng</th>
                                        <th>Phương thức TT</th>
                                        <th>Trạng thái</th>
                                        <th>Số tiền</th>
                                        <th>Tuỳ chọn</th>
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
                                                        <option value="shipping" {{ $order->status == 'shipping' ? 'selected' : '' }}>Đang giao</option>
                                                        <option value="completed" {{ $order->status == 'completed' ? 'selected' : '' }}>Đã giao</option>
                                                        <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
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
                                                   
                                                    @if ($order->status == 'completed' || $order->status == 'cancelled')
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
                    <!-- <div class="pagination-box">
                                    <nav class="ms-auto me-auto" aria-label="...">
                                        <ul class="pagination pagination-primary">
                                            <li class="page-item disabled">
                                                <a class="page-link" href="javascript:void(0)">Previous</a>
                                            </li>
                                            <li class="page-item active">
                                                <a class="page-link" href="javascript:void(0)">1</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">2</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">3</a>
                                            </li>
                                            <li class="page-item">
                                                <a class="page-link" href="javascript:void(0)">Next</a>
                                            </li>
                                        </ul>
                                    </nav>
                                </div> -->
                    <!-- Pagination Box End -->
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection