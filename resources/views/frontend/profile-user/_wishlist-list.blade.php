<div class="row g-sm-4 g-3">
    @forelse ($wishlist as $item)
        @php
            $product = $item->product;
            $variants = $product->variants ?? collect();
            $variantCount = $variants->count();
            $firstVariant = $variants->first();
            $categories = $product->categories ?? collect();
            $categoryNames = $categories->pluck('name')->take(2)->toArray();
            $categoryString = implode(', ', $categoryNames);
        @endphp
        <div class="col-xxl-3 col-lg-6 col-md-4 col-sm-6">
            <div class="product-box-3 theme-bg-white h-100">
                <div class="product-header">
                    <div class="product-image">
                        <a href="{{ route('client.product.detail', $product->slug) }}">
                            <img src="{{ asset('storage/' . $product->image) }}"
                                 class="img-fluid blur-up lazyloaded" alt="{{ $product->name }}">
                        </a>
                        <div class="product-header-top">
                            <form class="wishlist-delete-form" data-product-id="{{ $item->product_id }}"
                                action="{{ route('client.wishlist.destroy.main', $item->product_id) }}"
                                method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn wishlist-button close_button"
                                    title="Xóa khỏi wishlist">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                        class="feather feather-x">
                                        <line x1="18" y1="6" x2="6" y2="18">
                                        </line>
                                        <line x1="6" y1="6" x2="18" y2="18">
                                        </line>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="product-footer">
                    <div class="product-detail">
                        <span class="span-name">
                            {{ $categoryString }}
                            @if ($categories->count() > 2)
                                , ...
                            @endif
                            @if ($categories->count() == 0)
                                Chưa phân loại
                            @endif
                        </span>

                        <a href="{{ route('client.product.detail', $product->slug) }}">
                            <h5 class="name">{{ $product->name }}</h5>
                        </a>
                        <h6 class="unit mt-1">
                            @if ($variantCount > 0)
                                {{ $firstVariant->name ?? '' }}
                            @else
                                Không có thông tin
                            @endif
                        </h6>
                        <h5 class="price">
                            <span class="theme-color">
                                @if ($variantCount > 0)
                                    {{ number_format($firstVariant->price ?? 0, 0, ',', '.') }}₫
                                @else
                                    Đang cập nhật
                                @endif
                            </span>
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <p class="alert alert-warning" style="width: 100%;">Bạn chưa có sản phẩm yêu thích nào.</p>
    @endforelse
</div>
<div class="custome-pagination">
    {{ $wishlist->links() }}
</div>
