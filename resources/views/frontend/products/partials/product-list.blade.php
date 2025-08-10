
    {{-- Sản phẩm còn hàng --}}
    @forelse ($productsInStock as $product)
    <div>
        <div class="product-box-3 h-100 wow fadeInUp" data-wow-delay="0.05s">
            <div class="product-header">
                <div class="product-image">
                    <a href="{{ route('client.product.detail', $product->slug) }}">
                        <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}" />
                    </a>
                    <ul class="product-option">
                        <li data-bs-toggle="tooltip" title="Xem nhanh">
                            <a href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}">
                                <i data-feather="eye"></i>
                            </a>
                        </li>
                        <li data-bs-toggle="tooltip" title="Yêu thích">
                            <form action="{{ route('client.wishlist.store') }}" method="POST" style="display:inline-block;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="notifi-wishlist btn p-0" style="border:none; background:none; width: 18px; height: 18px;">
                                    <i data-feather="heart"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="product-footer">
                <div class="product-detail">
                    <span class="span-name">{{ $product->categories->pluck('name')->join(', ') }}</span>
                    <a href="{{ route('client.product.detail', $product->slug) }}">
                        <h5 class="name">{{ $product->name }}</h5>
                    </a>
                    <p class="text-content mt-1 mb-2 product-content">{{ Str::limit($product->description, 80) }}</p>
                    <div class="product-rating mt-2">
                        @php $avgRating = round($product->reviews->avg('rating') ?? 0); @endphp
                        <ul class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <li><i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i></li>
                            @endfor
                        </ul>
                        <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                    </div>
                    @php $variantInStock = $product->variants->firstWhere('stock', '>', 0); @endphp
                    @if ($variantInStock)
                        <h6 class="unit">{{ $variantInStock->name }}</h6>
                        <h5 class="price"><span class="theme-color">{{ number_format($variantInStock->price, 0, ',', '.') }}₫</span></h5>
                    @else
                        <h5 class="text-danger">Hết hàng</h5>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @empty
        <p>Không có sản phẩm phù hợp.</p>
    @endforelse

    {{-- Sản phẩm hết hàng --}}
    @foreach ($productsOutOfStock as $product)
    <div>
        <div class="product-box-3 h-100 wow fadeInUp" data-wow-delay="0.05s">
            <div class="product-header">
                <div class="product-image">
                    <a href="{{ route('client.product.detail', $product->slug) }}">
                        <img src="{{ asset('storage/'.$product->image) }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}" />
                    </a>
                    <ul class="product-option">
                        <li data-bs-toggle="tooltip" title="Xem nhanh">
                            <a href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}">
                                <i data-feather="eye"></i>
                            </a>
                        </li>
                        <li data-bs-toggle="tooltip" title="Yêu thích">
                            <form action="{{ route('client.wishlist.store') }}" method="POST" style="display:inline-block;">
                                @csrf
                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                <button type="submit" class="notifi-wishlist btn p-0" style="border:none; background:none; width: 18px; height: 18px;">
                                    <i data-feather="heart"></i>
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="product-footer">
                <div class="product-detail">
                    <span class="span-name">{{ $product->categories->pluck('name')->join(', ') }}</span>
                    <a href="{{ route('client.product.detail', $product->slug) }}">
                        <h5 class="name">{{ $product->name }}</h5>
                    </a>
                    <p class="text-content mt-1 mb-2 product-content">{{ Str::limit($product->description, 80) }}</p>
                    <div class="product-rating mt-2">
                        @php $avgRating = round($product->reviews->avg('rating') ?? 0); @endphp
                        <ul class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <li><i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i></li>
                            @endfor
                        </ul>
                        <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                    </div>
                    <h5 class="text-danger">Hết hàng</h5>
                </div>
            </div>
        </div>
    </div>
    @endforeach