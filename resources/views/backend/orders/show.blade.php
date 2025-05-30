@extends('layouts.backend')

@section('title', 'Chi tiết đơn hàng')


    @section('content')
    <div class="page-body">
    <!-- tracking table start -->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="title-header title-header-block package-card">
                          <div>
                            <h5>Đơn hàng #{{ $order->order_code }}</h5>
                        </div>

                        <div class="card-order-section">
                            <ul>
                                <li>{{ $order->created_at->format('m, d, Y') }} lúc {{ $order->created_at->format('H:i') }}</li>
                                <li>{{ $order->items->sum('quantity') }} Sản phẩm</li>
                                {{-- <li>Tổng số tiền @if(fmod($order->total_amount, 1) == 0)
                                    {{ number_format($order->total_amount, 0, ',', '.') }}
                                    @else
                                    {{ number_format($order->total_amount, 2, ',', '.') }}
                                    @endif VNĐ
                                </li> --}}
                            </ul>
                        </div>


                        </div>
                        <div class="bg-inner cart-section order-details-table">
                            <div class="row g-4">
                                <div class="col-xl-8">
                                    <div class="table-responsive table-details">
                                        <table class="table cart-table table-borderless">
                                            <thead>
                                                <tr>
                                                    <th colspan="2">Sản phẩm</th>
                                                    <th>Số lượng</th>
                                                    <th>Giá</th>
                                                    <th>Giảm giá</th> {{-- Cột giảm giá --}}
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach ($order->items as $item)
                                                    <tr class="table-order">
                                                        <td width="80">
                                                            <a href="javascript:void(0)">
                                                            <img src="{{ asset('backend/assets/images/profile/' . $item->product_image) }}" class="img-fluid blur-up lazyload" alt="{{ $item->product_name }}">
                                                            </a>
                                                        </td>
                                                        <td>
                                                            <p>{{ $item->product_name }}</p>
                                                        </td>
                                                        <td>
                                                            <h5>{{ $item->quantity }}</h5>
                                                        </td>
                                                        <td>
                                                            <h5>{{ number_format($item->price, 0, ',', '.') }} VNĐ</h5>
                                                        </td>
                                                        <td>
                                                            {{-- Tổng giảm giá theo số lượng sản phẩm --}}
                                                            <h5 class="text-danger">
                                                                -{{ number_format($item->discount * $item->quantity, 0, ',', '.') }} VNĐ
                                                            </h5>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>


                                                <tfoot>
                                                @php
                                                    $subtotal = $order->items->sum('total');
                                                    $shipping = $order->shipping_cost ?? 0;
                                                    $discount_total = $order->items->sum(function($item) {
                                                        return $item->discount * $item->quantity;
                                                    });
                                                    $final_total = $subtotal + $shipping;
                                                @endphp

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h5>Giảm giá:</h5>
                                                    </td>
                                                    <td>
                                                        <h5 class="text-danger">-{{ number_format($discount_total, 0, ',', '.') }} VNĐ</h5>
                                                    </td>
                                                </tr>

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h5>Tạm tính:</h5>
                                                    </td>
                                                    <td>
                                                        <h5>{{ number_format($subtotal, 0, ',', '.') }} VNĐ</h5>
                                                    </td>
                                                </tr>

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h5>Phí vận chuyển:</h5>
                                                    </td>
                                                    <td>
                                                        <h5>{{ number_format($shipping, 0, ',', '.') }} VNĐ</h5>
                                                    </td>
                                                </tr>

                                                <tr class="table-order">
                                                    <td colspan="3">
                                                        <h4 class="theme-color fw-bold">Tổng cộng:</h4>
                                                    </td>
                                                    <td>
                                                        <h4 class="theme-color fw-bold">{{ number_format($final_total, 0, ',', '.') }} VNĐ</h4>
                                                    </td>
                                                </tr>
                                            </tfoot>

                                            </table>

                                    </div>
                                </div>

                                <div class="col-xl-4">
                                    <div class="order-success">
                                        <div class="row g-4">
                                           <h4>Thông tin đơn hàng</h4>
                                            <ul class="order-details">
                                                <li>Mã đơn hàng: {{ $order->order_code }}</li>
                                                <li>Ngày đặt: {{ $order->created_at->format('d/m/Y') }}</li>
                                                <li>Thành tiền: {{ number_format($final_total, 0, ',', '.') }} VNĐ</li>
                                            </ul>


                                            <h4>Địa chỉ nhận hàng</h4>
                                                <ul class="order-details">
                                                    <li>{{ $order->address->recipient_name ?? 'N/A' }}</li>
                                                    <li>{{ $order->address->address ?? '' }}</li>
                                                    <li>{{ $order->address->district ?? '' }}, {{ $order->address->province ?? '' }}</li>
                                                    <li>SĐT: {{ $order->address->phone ?? '' }}</li>
                                                </ul>


                                            @php
                                                $method = match ($order->payment_method) {
                                                    'cod' => 'Thanh toán khi nhận hàng (COD)',
                                                    'bank' => 'Chuyển khoản ngân hàng',
                                                    'momo' => 'Ví MoMo',
                                                    default => 'Không xác định',
                                                };
                                            @endphp

                                            <div class="payment-mode">
                                                <h4>Phương thức thanh toán</h4>
                                                <p>{{ $method }}</p>
                                            </div>


                                            <div class="delivery-sec">
    <h3>
        Ngày giao dự kiến: 
        <span>{{ $order->created_at->addDays(3)->format('d/m/Y') }}</span>
    </h3>
    <a href="{{ route('admin.orders.tracking', $order->id) }}">Theo dõi đơn hàng</a>
</div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- section end -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- tracking table end -->
    @includeIf('backend.footer')
</div>
@endsection