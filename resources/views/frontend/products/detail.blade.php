@extends('layouts.frontend') {{-- Đổi lại đúng layout nếu khác --}}

@section('title', $product->name)

@section('contents')
    <script>
        window.VARIANTS = @json($variantMap);
    </script>

    <!-- Breadcrumb Section Start -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>{{ $product->name }}</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                @if (isset($product->category))
                                    <li class="breadcrumb-item">
                                        <a href="#">
                                            {{-- <a href="{{ route('category.show', $product->category->slug) }}"> --}}
                                            {{ $product->category->name }}
                                        </a>
                                    </li>
                                @endif
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $product->name }}
                                </li>
                            </ol>
                        </nav>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->


    <!-- Product Left Sidebar Start -->
    <section class="product-section">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-xxl-9 col-xl-8 col-lg-7 wow fadeInUp">
                    <div class="row g-4">
                        @php
                            $mainImages = [];
                            if ($product->image) {
                                $mainImages[] = asset('storage/' . $product->image);
                            }
                            foreach ($product->images as $img) {
                                $mainImages[] = asset('storage/' . $img->image_url);
                            }
                            foreach ($variants as $variant) {
                                if ($variant->image) {
                                    $mainImages[] = asset('storage/' . $variant->image);
                                }
                            }
                            $mainImages = array_unique($mainImages); // Loại trùng nếu có
                            $thumbImages = $mainImages;
                        @endphp
                        <div class="col-xl-6 wow fadeInUp">
                            <div class="product-left-box">
                                <div class="row g-2">
                                    <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                                        <div class="product-main-2 no-arrow">
                                            @foreach ($mainImages as $i => $img)
                                                <div>
                                                    <div class="slider-image">
                                                        <img src="{{ $img }}" id="img-{{ $i + 1 }}"
                                                            data-zoom-image="{{ $img }}"
                                                            class="img-fluid image_zoom_cls-{{ $i }} blur-up lazyload"
                                                            alt="Ảnh sản phẩm {{ $i + 1 }}" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <div class="col-xxl-2 col-lg-12 col-md-2 order-xxl-1 order-lg-2 order-md-1">
                                        <div class="left-slider-image-2 left-slider no-arrow slick-top">
                                            @foreach ($thumbImages as $i => $img)
                                                <div>
                                                    <div class="sidebar-image">
                                                        <img src="{{ $img }}" class="img-fluid blur-up lazyload"
                                                            alt="Thumbnail {{ $i + 1 }}" />
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="right-box-contain">
                                {{-- <h6 class="offer-top">30% Off</h6> --}}
                                <h2 class="name">{{ $product->name }}</h2>

                                <div class="price-rating">
                                    <h3 class="theme-color price" id="product-price">
                                        {{ number_format($product->variants[0]->price ?? 0) }} đ
                                    </h3>

                                    <div class="product-rating custom-rate">
                                        <ul class="rating">
                                            @php
                                                $avgRating = round($product->reviews->avg('rating'));
                                            @endphp
                                            @for ($i = 1; $i <= 5; $i++)
                                                <li>
                                                    <i data-feather="star"
                                                        class="{{ $i <= $avgRating ? 'fill' : '' }}"></i>
                                                </li>
                                            @endfor
                                        </ul>
                                        <span class="review">{{ $product->reviews->count() }} Đánh giá</span>
                                    </div>
                                </div>
                                @if ($isActive)
                                    <div class="product-packege">

                                        @foreach ($attributes as $attrId => $attr)
                                            <div class="product-title">
                                                <h4>{{ $attr['name'] }}</h4>
                                            </div>

                                            {{-- Thêm class disabled vào ul.select-packege --}}
                                            <ul class="select-packege {{ $isActive ? '' : 'disabled' }}">
                                                @foreach ($attr['values'] as $valueId => $value)
                                                    <li>
                                                        <a href="javascript:void(0)" data-attr="{{ $attrId }}"
                                                            data-value="{{ $valueId }}"
                                                            class="attribute-select {{ isset($defaultSelected[$attrId]) && $defaultSelected[$attrId] == $valueId ? 'active2' : '' }}">
                                                            {{ $value }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endforeach
                                    </div>
                                @endif
                                <form method="POST" action="{{ route('client.cart.add') }}" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}" />

                                    <div class="note-box product-packege {{ $isActive ? '' : 'disabled' }}">
                                        <div class="cart_qty qty-box product-qty">
                                            <div class="input-group">
                                                <button type="button" class="qty-left-minus" data-type="minus"
                                                    data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text"
                                                    name="quantity" value="1" min="1"
                                                    data-stock="{{ $variant->stock ?? 999999 }}"
                                                    data-cart-item-id="{{ $cartItemId ?? '' }}" />
                                                <button type="button" class="qty-right-plus" data-type="plus"
                                                    data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" id="variant_attributes" name="variant_attributes"
                                            value="" />

                                        <button type="submit" class="btn btn-md bg-dark cart-button text-white w-100"
                                            {{ $isActive ? '' : 'disabled' }}>
                                            Thêm giỏ hàng
                                        </button>
                                    </div>
                                </form>

                                <div class="pickup-box">
                                    <div class="product-info">
                                        <ul class="product-info-list product-info-list-2">
                                            @php
                                                $variantWithMaxStock = $product->variants->sortByDesc('stock')->first();
                                            @endphp
                                            <li>SKU : <a href="javascript:void(0)" id="product-sku">
                                                    {{ $variantWithMaxStock ? $variantWithMaxStock->sku ?? 'N/A' : '—' }}
                                                </a></li>
                                            <li>
                                                Số lượng :
                                                {{-- Nếu inactive thì hiển thị luôn "Sản phẩm tạm hết hàng" màu đỏ --}}
                                                @if (!$isActive)
                                                    <span id="product-stock" style="color: #ff4f4f; font-weight: bold;">Sản
                                                        phẩm tạm hết hàng</span>
                                                @else
                                                    @if ($variantWithMaxStock)
                                                        @if ($variantWithMaxStock->stock > 0)
                                                            <a href="javascript:void(0)"
                                                                id="product-stock">{{ $variantWithMaxStock->stock }}</a>
                                                        @else
                                                            <span id="product-stock"
                                                                style="color: #ff4f4f; font-weight: bold;">Sản phẩm tạm hết
                                                                hàng</span>
                                                        @endif
                                                    @else
                                                        <a href="javascript:void(0)" id="product-stock">—</a>
                                                    @endif
                                                @endif
                                            </li>
                                            <li>TAG:
                                                @if ($product->categories->isNotEmpty())
                                                    @foreach ($product->categories as $category)
                                                        <a
                                                            href="{{ route('client.product.catalog', ['dm[]' => $category->id, 'page' => 1]) }}">
                                                            {{ $category->name }}
                                                        </a>
                                                        @if (!$loop->last)
                                                            ,
                                                        @endif
                                                    @endforeach
                                                @else
                                                    <span>Không có TAG</span>
                                                @endif
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <style>
                            .disabled {
                                pointer-events: none;
                                opacity: 0.5;
                                cursor: not-allowed;
                            }
                        </style>


                        <div class="col-12">
                            <div class="product-section-box">
                                <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                            data-bs-target="#description" type="button" role="tab"
                                            aria-controls="description" aria-selected="true">
                                            Mô tả
                                        </button>
                                    </li>

                                    @if ($product->variants->count() > 1)
                                        <li class="nav-item" role="presentation">
                                            <button class="nav-link" id="info-tab" data-bs-toggle="tab"
                                                data-bs-target="#info" type="button" role="tab"
                                                aria-controls="info" aria-selected="false">
                                                Mô tả Biến thể
                                            </button>
                                        </li>
                                    @endif

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="care-tab" data-bs-toggle="tab"
                                            data-bs-target="#care" type="button" role="tab" aria-controls="care"
                                            aria-selected="false">
                                            Bình luận
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="review-tab" data-bs-toggle="tab"
                                            data-bs-target="#review" type="button" role="tab"
                                            aria-controls="review" aria-selected="false">
                                            Đánh giá
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content custom-tab" id="myTabContent">
                                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                                        aria-labelledby="description-tab">
                                        <div class="product-description">
                                            {!! $product->description !!}
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="info" role="tabpanel"
                                        aria-labelledby="info-tab">
                                        <div class="table-responsive">
                                            <div class="table-responsive" id="variant-description">
                                                {!! $product->variants[0]->description ?? '<p>Chưa có mô tả biến thể</p>' !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="care" role="tabpanel"
                                        aria-labelledby="care-tab">
                                        <div class="information-box">
                                            @includeIf('frontend.products.comment', [
                                                'comments' => $comments,
                                            ])
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="review" role="tabpanel"
                                        aria-labelledby="review-tab">
                                        @includeIf('frontend.products.review')
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                    <div class="right-sidebar-box">

                        <!-- Trending Product -->
                        <div class="pt-25">
                            <div class="category-menu">
                                <h3>Sản phẩm nổi bật</h3>
                                <ul class="product-list product-right-sidebar border-0 p-0">
                                    @foreach ($topMonthlyProducts as $item)
                                        <li>
                                            <div class="offer-product">
                                                <a href="{{ route('client.product.detail', $item->slug) }}"
                                                    class="offer-image">
                                                    <img src="{{ asset('storage/' . ($item->image ?? 'default.png')) }}"
                                                        class="img-fluid blur-up lazyload" alt="{{ $item->name }}" />

                                                </a>
                                                <div class="offer-detail">
                                                    <div>
                                                        <a href="{{ route('client.product.detail', $item->slug) }}">
                                                            <h6 class="name">{{ $item->name }}</h6>
                                                        </a>
                                                        <span>{{ $item->weight ?? '' }}</span> {{-- Nếu có trường weight --}}
                                                        <h6 class="price theme-color">
                                                            {{ number_format($item->variants->min('price') ?? 0) }} đ
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>

                        </div>

                        <!-- Banner Section -->
                        {{-- <div class="ratio_156 pt-25">
                <div class="home-contain">
                    <img src="../assets/images/vegetable/banner/8.jpg" class="bg-img blur-up lazyload"
                        alt="" />
                    <div class="home-detail p-top-left home-p-medium">
                        <div>
                            <h6 class="text-yellow home-banner">
                                Seafood
                            </h6>
                            <h3 class="text-uppercase fw-normal">
                                <span class="theme-color fw-bold">Freshes</span>
                                Products
                            </h3>
                            <h3 class="fw-light">every hour</h3>
                            <button onclick="location.href = 'shop-left-sidebar.html';"
                                class="btn btn-animation btn-md fw-bold mend-auto">
                                Shop Now
                                <i class="fa-solid fa-arrow-right icon"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Product Left Sidebar End -->

    <!-- Releted Product Section Start -->
    <section class="product-list-section section-b-space mt-3">
        <div class="container-fluid-lg">
            <div class="title">
                <h2>
                    {{ $relatedProducts->isEmpty() ? 'Sản phẩm mới nhất' : 'Sản phẩm liên quan' }}
                </h2>
                <span class="title-leaf">
                    <svg class="icon-width">
                        <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                    </svg>
                </span>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-6_1 product-wrapper">
                        @forelse($relatedProducts as $product)
                            <div>
                                <div class="product-box-3 wow fadeInUp">
                                    <div class="product-header">
                                        <div class="product-image">
                                            <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid blur-up lazyload" alt="{{ $product->name }}" />
                                            </a>
                                            <style>
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
                                            </style>
                                            <ul class="product-option">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                    <a href="javascript:void(0)" class="quickview-btn"
                                                        data-slug="{{ $product->slug }}" title="Xem nhanh">
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
                                        </div>
                                    </div>
                                    <div class="product-footer">
                                        <div class="product-detail">
                                            <span class="span-name">{{ $product->category->name ?? '' }}</span>
                                            <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                                                <h5 class="name">{{ $product->name }}</h5>
                                            </a>
                                            <div class="product-rating mt-2">
                                                <ul class="rating">
                                                    @php
                                                        $avgRating = round($product->reviews->avg('rating') ?? 0);
                                                    @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li><i data-feather="star"
                                                                class="{{ $i <= $avgRating ? 'fill' : '' }}"></i></li>
                                                    @endfor
                                                </ul>
                                                <span>({{ number_format($product->reviews->avg('rating') ?? 0, 1) }})</span>
                                            </div>
                                            @php
                                                $firstVariant = $product->variants->first();
                                            @endphp

                                            <h6 class="variant-name">
                                                @if ($firstVariant)
                                                    {{ $firstVariant->name }}
                                                @else
                                                    Không có biến thể
                                                @endif
                                            </h6>

                                            <h5 class="price">
                                                @if ($firstVariant)
                                                    <span
                                                        class="theme-color">{{ number_format($firstVariant->price) }}₫</span>
                                                @else
                                                    <span class="theme-color">Liên hệ</span>
                                                @endif
                                            </h5>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Không có sản phẩm nào để hiển thị.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Releted Product Section End -->
    <!-- Quick View Modal Box Start -->



    <!-- Quick View Modal Box End -->


    {{-- Sửa lỗi style lồng nhau và CSS sai cú pháp --}}
    <style>
        .attribute-select.active2 {
            background: #0da386 !important;
            color: #fff !important;
            border: 1px solid #0da386 !important;
            /* tuỳ bạn muốn style thêm gì nữa thì thêm */
        }

        .attribute-select.disabled-variant {
            color: #aaa !important;
            cursor: not-allowed !important;
            pointer-events: none !important;
        }

        .section-b-space {
            padding-top: 0px !important;
        }
    </style>

    <script>
        window.productVariants = @json($variantMap ?? []);
    </script>

    <script>
        document.getElementById('filter_star').addEventListener('change', function() {
            const star = this.value;
            const slug = "{{ $product->slug }}";

            fetch(`/client/san-pham/${slug}/reviews?star=${star}`)
                .then(response => response.text())
                .then(html => {
                    document.querySelector('#review-container .review-list').innerHTML = html;
                    feather.replace();
                });
        });
    </script>
    {{-- update số lượng trang detail --}}
    <script>
        window.VARIANTS = @json($variantMap ?? []);

        let selected = {};
        let variants = window.VARIANTS;

        function canSelectValue(attrValueId, attrId) {
            return variants.some(v => {
                if (Number(v.active) !== 1) return false;

                if (!v.value_ids.includes(Number(attrValueId))) return false;

                for (let selectedAttrId in selected) {
                    if (parseInt(selectedAttrId) === attrId) continue;
                    let selectedValueId = selected[selectedAttrId];
                    if (!v.value_ids.includes(selectedValueId)) {
                        return false;
                    }
                }
                return true;
            });
        }

        function updateDisabledStates() {
            document.querySelectorAll('.attribute-select').forEach(function(btn) {
                let val = parseInt(btn.getAttribute('data-value'), 10);
                let attr = parseInt(btn.getAttribute('data-attr'), 10);

                if (canSelectValue(val, attr)) {
                    btn.classList.remove('disabled-variant');
                    btn.style.pointerEvents = 'auto';
                    btn.style.color = '';
                    btn.style.cursor = 'pointer';
                } else {
                    btn.classList.add('disabled-variant');
                    btn.style.pointerEvents = 'none';
                    btn.style.color = '#aaa';
                    btn.style.cursor = 'not-allowed';

                    if (btn.classList.contains('active2')) {
                        btn.classList.remove('active2');
                        delete selected[attr];
                    }
                }
            });
        }

        function updateVariantDescription(found) {
            let variantDescEl = document.getElementById('variant-description');
            if (found && Number(found.active) === 1 && found.description) {
                variantDescEl.innerHTML = found.description;
            } else {
                variantDescEl.innerHTML = 'Chưa có mô tả biến thể';
            }
        }

        // Khởi tạo disable lần đầu (nếu có selected mặc định)
        updateDisabledStates();

        document.querySelectorAll('.attribute-select').forEach(function(btn) {
            if (btn.classList.contains('disabled-variant')) return;

            btn.addEventListener('click', function() {
                let attr = parseInt(btn.getAttribute('data-attr'));
                let val = parseInt(btn.getAttribute('data-value'));

                btn.closest('ul').querySelectorAll('a').forEach(a => a.classList.remove('active2'));

                btn.classList.add('active2');
                selected[attr] = val;

                updateDisabledStates();

                // Tìm variant và cập nhật thông tin + mô tả biến thể mỗi lần chọn
                if (Object.keys(selected).length === Object.keys(@json($attributes)).length) {
                    let attrValueIds = Object.values(selected).map(Number).sort((a, b) => a - b);
                    let found = variants.find(v =>
                        v.value_ids.length === attrValueIds.length &&
                        v.value_ids.slice().sort((a, b) => a - b).every((id, i) => id === attrValueIds[
                            i])
                    );

                    if (found && Number(found.active) === 1) {
                        document.getElementById('product-sku').textContent = found.sku || 'N/A';
                        document.getElementById('product-stock').textContent = found.stock ?? 'N/A';
                        document.getElementById('product-price').textContent = found.price ? (Number(found
                            .price).toLocaleString() + ' đ') : 'Liên hệ';
                    } else {
                        document.getElementById('product-sku').textContent = '—';
                        document.getElementById('product-stock').textContent = '—';
                        document.getElementById('product-price').textContent = '—';
                    }

                    updateVariantDescription(found);

                } else {
                    document.getElementById('product-sku').textContent = '—';
                    document.getElementById('product-stock').textContent = '—';
                    document.getElementById('product-price').textContent = '—';

                    // Chưa chọn đủ thì mô tả biến thể về mặc định mô tả sản phẩm chung
                    document.getElementById('variant-description').innerHTML = `{!! addslashes($product->description) !!}`;
                }

                var img = btn.getAttribute('data-variant-image');
                if (img) {
                    document.getElementById('mainImage').src = img;
                }
            });
        });
    </script>
    {{-- cộng trừ số lượng giỏ  --}}
    <script>
        const updateQuantityUrl = "{{ route('client.cart.updateQuantity') }}";

        document.querySelectorAll('.qty-left-minus, .qty-right-plus').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                // Tổng số thuộc tính cần chọn
                const totalAttributes = Object.keys(@json($attributes)).length;

                // Đếm số biến thể đã chọn (class active2)
                const selectedCount = document.querySelectorAll('.attribute-select.active2').length;

                if (selectedCount !== totalAttributes) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Thông báo',
                        text: 'Vui lòng chọn đầy đủ biến thể sản phẩm trước khi thêm số lượng.',
                        confirmButtonColor: '#0da487',
                        confirmButtonText: 'OK',
                        width: 350,
                        padding: '1rem 1.5rem'
                    });
                    return;
                }

                // Lấy input số lượng
                const input = button.closest('.input-group').querySelector('input[name="quantity"]');
                if (!input) return;

                // Lấy giá trị biến thể đã chọn (value_ids)
                let selected = {};
                document.querySelectorAll('.attribute-select.active2').forEach(el => {
                    selected[el.getAttribute('data-attr')] = parseInt(el.getAttribute('data-value'),
                        10);
                });

                // Chuẩn bị mảng value_ids đã chọn (đã sort)
                const selectedValueIds = Object.values(selected).sort((a, b) => a - b);

                // Tìm biến thể tương ứng trong window.VARIANTS
                let foundVariant = window.VARIANTS.find(v =>
                    v.value_ids.length === selectedValueIds.length &&
                    v.value_ids.every((id, i) => id === selectedValueIds[i]) &&
                    Number(v.active) === 1
                );

                // Lấy tồn kho của biến thể tìm được, hoặc mặc định lớn nếu không tìm thấy
                const maxStock = foundVariant ? parseInt(foundVariant.stock, 10) || 999999 : 999999;

                let val = parseInt(input.value, 10) || 1;
                const min = parseInt(input.min, 10) || 1;

                let action;

                if (button.classList.contains('qty-right-plus')) {
                    if (val < maxStock) {
                        val++;
                        action = 'increase';
                    } else {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: 'Số lượng đã đạt tối đa trong kho!',
                            confirmButtonColor: '#0da487',
                            confirmButtonText: 'OK',
                            width: 400,
                            padding: '1rem 1.5rem'
                        });
                        return;
                    }
                } else if (button.classList.contains('qty-left-minus')) {
                    if (val > min) {
                        val--;
                        action = 'decrease';
                    } else {
                        return; // không giảm nữa
                    }
                }

                input.value = val; // Cập nhật số lượng lên input

                // Gọi API cập nhật nếu có cart_item_id (ở trang giỏ hàng)
                const cartItemId = input.getAttribute('data-cart-item-id');
                if (!cartItemId) {
                    // Không có cartItemId, chỉ update local (ví dụ trang chi tiết)
                    return;
                }

                fetch(updateQuantityUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .getAttribute('content'),
                        },
                        body: JSON.stringify({
                            cart_item_id: cartItemId,
                            action: action
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            input.value = data.quantity; // cập nhật số lượng theo server trả về
                            console.log('Quantity updated to:', data.quantity);
                        } else {
                            alert(data.message || 'Cập nhật số lượng thất bại');
                        }
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Có lỗi xảy ra, vui lòng thử lại');
                    });
            });
        });
    </script>
    <!-- xử lý thêm vào giỏ -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const defaultStock = Number("{{ $variantWithMaxStock->stock ?? 999999 }}");
            updateQuantityControls(defaultStock);
        });

        function updateQuantityControls(stock) {
            const minusBtn = document.querySelector('.qty-left-minus');
            const plusBtn = document.querySelector('.qty-right-plus');
            const qtyInput = document.querySelector('input[name="quantity"]');
            const addToCartBtn = document.querySelector('.add-to-cart-form button[type="submit"]');

            if (stock === 0) {
                minusBtn.disabled = true;
                plusBtn.disabled = true;
                qtyInput.disabled = true;
                addToCartBtn.disabled = true;

                minusBtn.style.opacity = 0.5;
                plusBtn.style.opacity = 0.5;
                qtyInput.style.opacity = 0.5;
                addToCartBtn.style.opacity = 0.5;

                qtyInput.value = 0;
            } else {
                minusBtn.disabled = false;
                plusBtn.disabled = false;
                qtyInput.disabled = false;
                addToCartBtn.disabled = false;

                minusBtn.style.opacity = 1;
                plusBtn.style.opacity = 1;
                qtyInput.style.opacity = 1;
                addToCartBtn.style.opacity = 1;

                if (parseInt(qtyInput.value, 10) === 0) {
                    qtyInput.value = 1;
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-to-cart-form');
            const variantInput = document.getElementById('variant_attributes');

            // Số lượng thuộc tính cần chọn (dựa vào backend truyền qua blade)
            const attributesCount = Object.keys(@json($attributes)).length;

            // Biến lưu trạng thái lựa chọn biến thể (attrId -> valueId)
            let selected = {};
            const qtyInput = document.querySelector('input[name="quantity"]');
            if (qtyInput) {
                ['blur', 'change'].forEach(evt => {
                    qtyInput.addEventListener(evt, function() {
                        const maxStock = parseInt(qtyInput.getAttribute('data-stock'), 10) ||
                            999999;
                        let val = parseInt(qtyInput.value, 10);

                        if (isNaN(val) || val < 1) {
                            qtyInput.value = 1;
                            return;
                        }

                        if (val > maxStock) {
                            qtyInput.value = maxStock;
                            Swal.fire({
                                icon: 'warning',
                                title: 'Thông báo',
                                text: `Số lượng tối đa là ${maxStock}`,
                                confirmButtonColor: '#0da487',
                                confirmButtonText: 'OK'
                            });
                        }
                    });
                });
            }
            // Hàm cập nhật input ẩn variant_attributes
            function updateVariantInput() {
                if (Object.keys(selected).length === 0) {
                    variantInput.value = null;
                } else {
                    variantInput.value = JSON.stringify(selected);
                }
                console.log('Updated variant_attributes:', variantInput.value);
            }

            // Hàm cập nhật tồn kho cho input số lượng
            function updateStockOfInput(foundVariant) {
                const input = document.querySelector('input[name="quantity"]');
                if (!input) return;

                const stock = foundVariant.stock ?? 999999;
                input.setAttribute('data-stock', stock);
                input.setAttribute('max', stock);

                let val = parseInt(input.value, 10) || 1;
                if (val > stock) {
                    input.value = stock;
                }
            }

            // Gán sự kiện click cho từng lựa chọn biến thể
            document.querySelectorAll('.attribute-select').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    let attr = btn.getAttribute('data-attr');
                    let val = btn.getAttribute('data-value');

                    // Xóa class active2 trong cùng nhóm (ul cha)
                    const ulParent = btn.closest('ul');
                    if (ulParent) {
                        ulParent.querySelectorAll('a').forEach(a => a.classList.remove('active2'));
                    }
                    // Thêm class active2 cho lựa chọn hiện tại
                    btn.classList.add('active2');

                    // Cập nhật lựa chọn
                    selected[attr] = parseInt(val);

                    // Cập nhật input ẩn
                    updateVariantInput();

                    // Nếu chọn đủ biến thể thì tìm biến thể phù hợp
                    if (Object.keys(selected).length === attributesCount) {
                        let attrValueIds = Object.values(selected).map(Number).sort((a, b) => a -
                            b);

                        // Tìm biến thể trùng
                        let found = window.VARIANTS.find(v =>
                            v.value_ids.length === attrValueIds.length &&
                            v.value_ids.slice().sort((a, b) => a - b).every((id, i) => id ===
                                attrValueIds[i])
                        );

                        if (found) {
                            document.getElementById('product-sku').textContent = found.sku || 'N/A';
                            document.getElementById('product-stock').textContent = found.stock ??
                                'N/A';
                            document.getElementById('product-price').textContent = found.price ? (
                                Number(found.price).toLocaleString() + ' đ') : 'Liên hệ';
                            document.getElementById('variant-price').value = found.price || '';

                            // Cập nhật tồn kho cho input số lượng
                            updateStockOfInput(found);
                        } else {
                            document.getElementById('product-sku').textContent = 'Không tồn tại';
                            document.getElementById('product-stock').textContent = 'Không tồn tại';
                            document.getElementById('product-price').textContent = 'Không tồn tại';
                            document.getElementById('variant-price').value = '';

                            // Reset tồn kho lớn
                            updateStockOfInput({
                                stock: 999999
                            });
                            updateQuantityControls(0);
                        }
                    } else {
                        // Chưa chọn đủ biến thể => reset hiển thị và tồn kho
                        document.getElementById('product-sku').textContent = '—';
                        document.getElementById('product-stock').textContent = '—';
                        document.getElementById('product-price').textContent = '—';
                        document.getElementById('variant-price').value = '';

                        updateStockOfInput({
                            stock: 999999
                        });
                    }
                });
            });

            // Khi submit form, kiểm tra đủ biến thể chưa
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (attributesCount > 0 && Object.keys(selected).length !== attributesCount) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Thông báo',
                            text: 'Vui lòng chọn đầy đủ biến thể sản phẩm trước khi thêm vào giỏ hàng.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#0da487',
                            width: 300,
                            padding: '0.8rem 1rem',
                            customClass: {
                                title: 'swal2-title-smaller',
                                content: 'swal2-content-smaller'
                            }
                        });
                        return false;
                    }
                    updateVariantInput();
                });
            }

            // Khởi tạo input ẩn lần đầu
            updateVariantInput();
        });
    </script>

    <style>
        .swal2-title-smaller {
            font-size: 14px !important;
            margin-bottom: 0.3rem;
            text-align: center;
        }

        .swal2-content-smaller {
            font-size: 12px !important;
            text-align: center;
            white-space: normal;
        }
    </style>

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


    <style>
        .swal2-popup-small {
            font-size: 14px !important;
            padding: 1rem !important;
        }

        .swal2-title-small {
            font-size: 18px !important;
            margin-bottom: 0.4rem;
        }

        .swal2-content-small {
            font-size: 14px !important;
            white-space: normal;
        }
        .wishlist-btn.fill-heart svg {
            fill: #4a5568 !important;
            stroke: #4a5568 !important;
    }
    </style>

@endsection
