@extends('layouts.backend')

@section('title', 'Nhật ký đơn hàng')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="title-header option-title">
                                <h5>Nhật ký đơn hàng</h5>
                            </div>
                        <div class="mb-4">
                            @foreach ($order->items as $item)
                                <div class="d-flex align-items-center gap-3 border rounded p-3 mb-2">
                                    <img src="{{ asset('storage/' . $item->product_image) }}" 
                                        class="img-thumbnail" 
                                        style="width:80px; height:80px; object-fit:cover;" 
                                        alt="{{ $item->product_name }}">
                                    <div class="flex-grow-1">
                                        <h6 class="mb-1">{{ $item->product_name }}</h6>
                                        @if ($item->productVariant)
                                            <small class="text-muted d-block mb-1">Loại: {{ $item->productVariant->name }}</small>
                                        @endif
                                        <small>Số lượng: {{ $item->quantity }}</small>
                                    </div>
                                </div>
                            @endforeach

                            {{-- ✅ Tổng tiền sau khi liệt kê xong sản phẩm --}}
                            <div class="text-end fw-bold mt-3">
                                Tổng cộng: {{ number_format($order->total_amount, 0, ',', '.') }} VNĐ
                            </div>
                        </div>
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
                                        <th colspan="3" class="text-center">Trạng thái đơn hàng</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $statusMap = [
                                            'pending' => 'Chờ xác nhận',
                                            'confirmed' => 'Đã xác nhận',
                                            'processing' => 'Đang chuẩn bị',
                                            'shipped' => 'Đã gửi hàng',
                                            'in_transit' => 'Đang vận chuyển',
                                            'delivered' => 'Đã giao hàng',
                                            'cancelled' => 'Đã hủy',
                                            'failed_delivery' => 'Giao thất bại',
                                        ];
                                    @endphp

                                    @forelse ($order->statusLogs as $log)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('d/m/Y') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($log->changed_at)->format('H:i:s') }}</td>
                                            <td class="text-end" style="width: 30%;">{{ $statusMap[$log->from_status] ?? $log->from_status }}</td>
                                            <td class="text-center" style="width: 5%;">→</td>
                                            <td class="text-start" style="width: 30%;">{{ $statusMap[$log->to_status] ?? $log->to_status }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Chưa có lịch sử thay đổi trạng thái.</td>
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
                    </div>
                </div>
            </div>
        </div>
    </div>
    @includeIf('backend.footer')
</div>

@endsection
