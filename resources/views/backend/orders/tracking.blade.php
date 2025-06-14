@extends('layouts.backend')

@section('title', 'Theo dõi đơn hàng')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        {{-- <div class="mb-4 border-bottom pb-2 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">📦 Theo dõi đơn hàng</h5>
                        </div> --}}
                        <div class="title-header option-title">
                                <h5>Theo dõi đơn hàng</h5>
                            </div>
                        <!-- Thông tin sản phẩm -->
                        <div class="mb-4">
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-center gap-3 border rounded p-3 mb-2">
                                    <img src="{{ $item->product_image ?? asset('assets/images/default-product.png') }}" alt="{{ $item->product_name }}" style="width: 70px; height: 70px; object-fit: cover;" class="rounded">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        @if ($item->variant_name)
                                            <small class="text-muted d-block mb-1">Biến thể: {{ $item->variant_name }}</small>
                                        @endif
                                        <small>Số lượng: {{ $item->quantity }}</small>
                                    </div>
                                    <div class="text-end fw-semibold align-self-center" style="min-width: 120px;">
                                        {{ number_format($item->total, 0, ',', '.') }} VNĐ
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <!-- Trạng thái đơn hàng -->
                        <div class="mb-5 text-center">
                            <h6 class="text-muted">Trạng thái hiện tại:</h6>
                            <h5 class="fw-bold">
                                @if ($order->status === 'completed')
                                    <i class="ri-checkbox-circle-line text-success me-1"></i> Đơn hàng đã được giao thành công.
                                @elseif ($order->status === 'cancelled')
                                    <i class="ri-close-circle-line text-danger me-1"></i> Đơn hàng đã bị hủy.
                                @else
                                    <i class="ri-truck-line text-warning me-1"></i> Đơn hàng đang được xử lý. Thông tin sẽ cập nhật sớm.
                                @endif
                            </h5>
                        </div>

                        <!-- Tiến trình đơn hàng nằm ngang -->
                        <div class="mb-5">
                            <div class="d-flex flex-wrap justify-content-between align-items-start text-center">
                                @foreach ($steps as $step)
                                    <div class="flex-fill px-2">
                                        <div class="mb-2">
                                            <span class="badge rounded-pill {{ $step['done'] ? 'bg-success' : 'bg-secondary' }} px-3 py-2">
                                                {{ $step['name'] }}
                                            </span>
                                        </div>
                                        <small class="text-muted">{{ $step['done'] ? 'Hoàn thành' : 'Đang chờ' }}</small>
                                    </div>
                                @endforeach
                            </div>
                        </div>

                        <!-- Bảng tracking -->
                        <div class="table-responsive">
                            <table class="table table-sm table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Ngày</th>
                                        <th>Giờ</th>
                                        <th>Mô tả</th>
                                        <th>Địa điểm</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($order->trackingUpdates ?? [] as $update)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($update->date)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($update->date)->format('H:i A') }}</td>
                                            <td>{{ $update->description }}</td>
                                            <td>{{ $update->location }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center text-muted">Chưa có thông tin tracking.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer d-flex justify-content-end gap-2 border-0 pt-3">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">
                            <i class="ri-arrow-left-line me-1"></i> Quay lại
                        </a>
                        <button class="btn btn-primary">
                            <i class="ri-refresh-line me-1"></i> Cập nhật
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('backend.footer')
</div>

@endsection
