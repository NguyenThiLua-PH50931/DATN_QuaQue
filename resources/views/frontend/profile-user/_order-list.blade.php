<div class="order-contain">
    @forelse($orders as $order)
        @foreach($order->items as $item)
            <div class="order-box dashboard-bg-box">
                <div class="order-container">
                    <div class="order-icon">
                        <i data-feather="box"></i>
                    </div>
                    <div class="order-detail">
                        <h4>
                            Trạng thái:
                            <span
                                @if(!in_array(strtolower($order->status), ['failed', 'cancelled', 'pending']))
                                    class="success-bg"
                                @endif>
                                {{
                                    [
                                        'pending'    => 'Chờ xác nhận',
                                        'confirmed'  => 'Đã xác nhận',
                                        'shipping'   => 'Đang giao',
                                        'delivered'  => 'Đã giao hàng',
                                        'cancelled'  => 'Đã hủy',
                                        'success'    => 'Thành công',
                                        'failed'     => 'Thất bại',
                                        'refunded'   => 'Đã hoàn tiền',
                                    ][$order->status] ?? ucfirst($order->status)
                                }}
                            </span>
                        </h4>
                        <h6 class="text-content">
                            {!! $item->product && $item->product->description
                                ? \Illuminate\Support\Str::limit(strip_tags($item->product->description), 150)
                                : 'Không có mô tả'
                            !!}
                        </h6>
                    </div>
                </div>
                <div class="product-order-detail">
                    <a href="{{ route('client.product.detail', $item->product->slug) }}" class="order-image">
                        <img width="139px"
                             src="{{ asset('storage/' . $item->product->image) }}"
                             class="blur-up lazyload"
                             alt="{{ $item->product->name }}" />
                    </a>
                    <div class="order-wrap">
                        <a href="{{ route('client.product.detail', $item->product->slug) }}">
                            <h3>{{ $item->product->name }}</h3>
                        </a>
                        <p class="text-content">
                            @if(!empty($item->product_variant_value_name))
                                {{ $item->product_variant_value_name }}
                            @else
                                {{ \Illuminate\Support\Str::limit(strip_tags($item->product->description ?? ''), 100) }}
                            @endif
                        </p>
                        <ul class="product-size">
                            <li>
                                <div class="size-box">
                                    <h6 class="text-content">Giá:</h6>
                                    <h5>{{ number_format($item->price, 0, ',', '.') }}đ</h5>
                                </div>
                            </li>
                            <li>
                                <div class="size-box">
                                    <h6 class="text-content">Số lượng:</h6>
                                    <h5>{{ $item->quantity }}</h5>
                                </div>
                            </li>
                            <li>
                                <div class="size-box">
                                    <h6 class="text-content">Danh mục:</h6>
                                    <h5 style="display: inline;">
                                        @if ($item->product && $item->product->categories->isNotEmpty())
                                            @foreach ($item->product->categories as $category)
                                                <a href="{{ route('client.product.catalog', ['dm[]' => $category->id, 'page' => 1]) }}" style="display:inline;">
                                                    {{ $category->name }}
                                                </a>@if (!$loop->last), @endif
                                            @endforeach
                                        @else
                                            <span>Không có danh mục</span>
                                        @endif
                                    </h5>
                                </div>
                            </li>
                            <li>
                                <div class="size-box">
                                    <h6 class="text-content">Biến thể:</h6>
                                    <h5>{{ $item->product_variant_value_name ?? 'Không có' }}</h5>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        @endforeach
    @empty
        <p class="alert alert-warning" style="width: 100%;">Bạn chưa có đơn hàng nào.</p>
    @endforelse

    <div class="custome-pagination">
        {{ $orders->links() }}
    </div>
</div>
