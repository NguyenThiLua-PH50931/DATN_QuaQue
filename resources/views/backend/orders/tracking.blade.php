@extends('layouts.backend')

@section('title', 'Theo dõi đơn hàng')

@section('content')
<div class="page-body">
    <!-- Order Tracking Section starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="card">
                            <div class="card-body">
                                <div class="title-header option-title">
                                    <h5>Theo dõi đơn hàng</h5>
                                </div>
                                <div class="row">
                                    <div class="col-12 overflow-hidden">
                                        <div class="order-left-image d-flex">
                                            <div class="order-image-contain">
                                                <div class="order-products-list">
                                                    @foreach ($order->items as $item)
                                                        <div class="product-item d-flex mb-3">
                                                            <div class="product-image me-3" style="width: 100px;">
                                                                <img src="{{ $item->product_image ?? asset('assets/images/default-product.png') }}" alt="{{ $item->product_name }}" class="img-fluid">
                                                            </div>
                                                            <div class="product-info">
                                                                <h5>{{ $item->product_name }}</h5>
                                                                <p>Số lượng: {{ $item->quantity }}</p>
                                                                <p>Tổng: {{ number_format($item->total, 0, ',', '.') }} VNĐ</p>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <h5>
                                                    @if ($order->status === 'completed')
                                                        Đơn hàng đã được giao thành công.
                                                    @elseif ($order->status === 'cancelled')
                                                        Đơn hàng đã bị hủy.
                                                    @else
                                                        Đơn hàng đang được xử lý. Thông tin tracking sẽ cập nhật trong 24 giờ tới.
                                                    @endif
                                                </h5>
                                            </div>
                                        </div>
                                    </div>

                                    <ol class="progtrckr">
                                        @foreach ($steps as $step)
                                            <li class="{{ $step['done'] ? 'progtrckr-done' : 'progtrckr-todo' }}">
                                                <h5>{{ $step['name'] }}</h5>
                                                <h6>{{ $step['done'] ? 'Hoàn thành' : 'Đang chờ' }}</h6>
                                            </li>
                                        @endforeach
                                    </ol>

                                    <div class="col-12 overflow-visible">
                                        <div class="tracker-table">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr class="table-head">
                                                            <th scope="col">Ngày</th>
                                                            <th scope="col">Giờ</th>
                                                            <th scope="col">Mô tả</th>
                                                            <th scope="col">Địa điểm</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody>
                                                        @foreach ($order->trackingUpdates ?? [] as $update)
                                                        <tr>
                                                            <td><h6>{{ \Carbon\Carbon::parse($update->date)->format('d/m/Y') }}</h6></td>
                                                            <td><h6>{{ \Carbon\Carbon::parse($update->date)->format('H:i A') }}</h6></td>
                                                            <td><p class="fw-bold">{{ $update->description }}</p></td>
                                                            <td><h6>{{ $update->location }}</h6></td>
                                                        </tr>
                                                        @endforeach

                                                        @if(empty($order->trackingUpdates) || count($order->trackingUpdates) === 0)
                                                        <tr>
                                                            <td colspan="4" class="text-center">Chưa có thông tin tracking.</td>
                                                        </tr>
                                                        @endif
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-end border-0 pb-0 d-flex justify-content-end">
                                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline me-3">Quay lại</a>
                                <button class="btn btn-primary">Cập nhật</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
     </div>
    <!-- Container-fluid Ends-->
    @includeIf('backend.footer')
</div>
@endsection
