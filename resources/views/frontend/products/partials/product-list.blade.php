{{-- resources/views/frontend/products/partials/product-list.blade.php --}}
<div id="product-list-wrapper">
    <div class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
        @forelse ($products as $product)
            <div>
                <div class="product-box-3 h-100 wow fadeInUp @if(!$product->variants->firstWhere(fn($v) => $v->stock > 0) || $product->active != 1) out-of-stock @endif" data-wow-delay="0.05s">
                    <div class="product-header">
                        <div class="product-image">
                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload"
                                    alt="{{ $product->name }}" />
                            </a>

                            <style>
                                    .wishlist-btn.fill-heart svg {
                                            fill: #4a5568 !important;
                                            stroke: #4a5568 !important;
                                        }
                                .product-option li {
                                    width: 50% !important;
                                }

                                /* ==== CARD: bố cục cột, cao bằng nhau ==== */
                                .product-box-3 {
                                    display: flex;
                                    flex-direction: column;
                                    height: 100%;
                                }

                                /* Footer co giãn để giữ card bằng nhau */
                                .product-box-3 .product-footer {
                                    flex: 1;
                                    display: flex;
                                }

                                .product-box-3 .product-detail {
                                    flex: 1;
                                    display: flex;
                                    flex-direction: column;
                                    gap: .4rem;
                                }

                                /* Tiêu đề: kẹp số dòng để chiều cao đồng đều */
                                .product-box-3 .product-detail .name {
                                    display: -webkit-box;
                                    -webkit-line-clamp: 2;
                                    /* 2 dòng, đổi 3 nếu muốn */
                                    -webkit-box-orient: vertical;
                                    overflow: hidden;
                                    line-height: 1.35;
                                    min-height: calc(1em * 2 * 1.35);
                                }

                                /* Giá đẩy xuống đáy */
                                .product-box-3 .product-detail .price {
                                    margin-top: auto;
                                }

                                /* ==== ẢNH: vuông, thu nhỏ bên trong ==== */
                                .product-box-3 .product-image {
                                    position: relative;
                                    overflow: hidden;
                                    border-radius: 16px;
                                    background: #fff;
                                    /* viền trắng */
                                }

                                /* Khung ảnh vuông */
                                .product-box-3 .product-image::before {
                                    content: "";
                                    display: block;
                                    padding-top: 100%;
                                    /* 1:1; đổi 75% cho 4:3 */
                                }

                                /* Vị trí ảnh bên trong, có khoảng trống viền */
                                .product-box-3 .product-image>a {
                                    position: absolute;
                                    inset: 8px;
                                    /* ảnh nhỏ hơn, tạo viền trắng */
                                    border-radius: 12px;
                                    overflow: hidden;
                                }

                                /* Ảnh lấp đầy phần bên trong */
                                .product-box-3 .product-image>a>img {
                                    width: 100% !important;
                                    height: 100% !important;
                                    object-fit: cover !important;
                                    display: block;
                                    border-radius: inherit;
                                }

                                /* ==== Badge TOP (tuỳ chọn) ==== */
                                .product-box-3 .product-image::after {
                                    position: absolute;
                                    top: 8px;
                                    left: 8px;
                                    background: #12d6a7;
                                    color: #fff;
                                    font-weight: bold;
                                    font-size: 0.8rem;
                                    padding: 2px 8px;
                                    border-radius: 6px;
                                }

                                /* ==== Badge HẾT HÀNG ==== */
                                .out-of-stock-badge {
                                    position: absolute;
                                    top: 8px;
                                    right: 8px;
                                    background: #dc3545;
                                    color: #fff;
                                    font-weight: bold;
                                    font-size: 0.75rem;
                                    padding: 4px 8px;
                                    border-radius: 6px;
                                    z-index: 10;
                                    box-shadow: 0 2px 4px rgba(0,0,0,0.2);
                                }

                                .out-of-stock-badge span {
                                    display: block;
                                    text-align: center;
                                }

                                /* Overlay mờ cho sản phẩm hết hàng */
                                .product-box-3.out-of-stock .product-image::after {
                                    content: "";
                                    position: absolute;
                                    top: 0;
                                    left: 0;
                                    right: 0;
                                    bottom: 0;
                                    background: rgba(0, 0, 0, 0.3);
                                    border-radius: 16px;
                                    z-index: 5;
                                }

                                .product-box-3.out-of-stock .product-image > a > img {
                                    filter: grayscale(30%);
                                }
                            </style>

                            <ul class="product-option">
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Xem nhanh">
                                    <a href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}">
                                        <i data-feather="eye"></i>
                                    </a>
                                </li>
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
                                    <a href="javascript:void(0)"
                                        class="notifi-wishlist wishlist-btn
                                        @if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) fill-heart @endif"
                                        data-product-id="{{ $product->id }}"
                                        data-liked="@if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists())1 @else 0 @endif"
                                        title="Yêu thích"
                                        style="width:18px; height:18px; display:inline-flex; align-items:center; justify-content:center; color:#4a5568; margin-top:10px;">
                                        <i data-feather="heart"></i>
                                    </a>
                                </li>
                            </ul>

                            @php
                                $variantInStock = $product->variants->firstWhere(fn($v) => $v->stock > 0);
                                $isOutOfStock = !$variantInStock || $product->active != 1;
                            @endphp

                            @if ($isOutOfStock)
                                <div class="out-of-stock-badge">
                                    <span>Hết hàng</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="product-footer">
                        <div class="product-detail">
                            @php
                                $categoryNames = $product->categories->pluck('name');
                                $showCategories = $categoryNames->take(3);
                                $more = $categoryNames->count() > 3;
                            @endphp

                            <span class="span-name">
                                {{ $showCategories->join(', ') }} @if ($more)
                                    ...
                                @endif
                            </span>

                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <h5 class="name">{{ $product->name }}</h5>
                            </a>

                            <p class="text-content mt-1 mb-2 product-content description-limit">
                                {!! Str::limit(strip_tags($product->description), 200) !!}
                            </p>
                            <style>
                                .description-limit {
                                    display: -webkit-box;
                                    -webkit-line-clamp: 3;
                                    -webkit-box-orient: vertical;
                                    overflow: hidden;
                                    text-overflow: ellipsis;
                                    white-space: normal;
                                }
                            </style>

                            <div class="product-rating mt-2">
                                @php $avgRating = round($product->reviews->avg('rating') ?? 0); @endphp
                                <ul class="rating">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li><i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i>
                                        </li>
                                    @endfor
                                </ul>
                                <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                            </div>

                            @php
                                // Kiểm tra stock > 0 thay vì active
                                $variantInStock = $product->variants->firstWhere(fn($v) => $v->stock > 0);
                            @endphp

                            @if ($variantInStock && $product->active == 1)
                                <h6 class="unit">{{ $variantInStock->name }}</h6>
                                <h5 class="price">
                                    <span
                                        class="theme-color">{{ number_format($variantInStock->price, 0, ',', '.') }}₫</span>
                                </h5>
                            @else
                                <h3 class="text-danger text-center">Hết hàng</h3>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <p>Không có sản phẩm nào phù hợp.</p>
        @endforelse
    </div>

    <nav class="custome-pagination">
        {{ $products->withQueryString()->links() }}
    </nav>
</div>
    
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-btn').forEach(function(btn) {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                const productId = btn.getAttribute('data-product-id');
                const url = "{{ route('client.wishlist.store') }}";
                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            product_id: productId
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            if (data.toggled === 'removed') {
                                btn.setAttribute('data-liked', '0');
                                btn.classList.remove('fill-heart');
                            } else {
                                btn.setAttribute('data-liked', '1');
                                btn.classList.add('fill-heart');
                            }
                            if (window.feather) feather.replace();

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message,
                                showConfirmButton: false,
                                timer: 1400
                            });
                        } else {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: data.message || 'Lỗi thao tác',
                                showConfirmButton: false,
                                timer: 1400
                            });
                        }
                    });
            });
        });
    });
</script>