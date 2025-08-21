@extends('layouts.frontend')
@section('title', 'Trang chủ')
@section('contents')
<!-- HOME SECTION START -->
<section class="home-section pt-2">
    <div class="container-fluid-lg">
        <div class="row g-4">
            <div class="col-xl-8 ratio_65">
                <div class="home-contain h-100">
                    @if ($mainHeroBanner)
                    <img src="{{ asset('storage/' . $mainHeroBanner->image) }}" class="bg-img blur-up lazyload"
                        alt="{{ $mainHeroBanner->title }}">
                    <div class="home-detail p-center-left w-75">
                        <div>
                            <h6>{!! $mainHeroBanner->title !!}</h6>
                            <h1 class="text-uppercase">
                                {{-- {{ $mainHeroBanner->link }} --}}
                            </h1>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <div class="col-xl-4 ratio_65">
                <div class="row g-4">
                    @if ($smallPromoTopBanner)
                    <div class="col-xl-12 col-md-6">
                        <div class="home-contain">
                            <img src="{{ asset('storage/' . $smallPromoTopBanner->image) }}"
                                class="bg-img blur-up lazyload" alt="{{ $smallPromoTopBanner->title }}">
                            <div class="home-detail p-center-left home-p-sm w-75">
                                <div>
                                    <h2 class="mt-0 text-danger">{!! $smallPromoTopBanner->title !!}</h2>
                                    {{-- <h3 class="theme-color">{{ $smallPromoTopBanner->link }}</h3> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    @if ($smallPromoBottomBanner)
                    <div class="col-xl-12 col-md-6">
                        <div class="home-contain">
                            <img src="{{ asset('storage/' . $smallPromoBottomBanner->image) }}"
                                class="bg-img blur-up lazyload" alt="{{ $smallPromoBottomBanner->title }}">
                            <div class="home-detail p-center-left home-p-sm w-75">
                                <div>
                                    <h3 class="mt-0 theme-color fw-bold">{!! $smallPromoBottomBanner->title !!}</h3>
                                    {{-- <h4 class="text-danger">{{ $smallPromoBottomBanner->link }}</h4> --}}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>
<!-- HOEM SECTION END -->

{{-- BANNER SECTION START --}}
<section class="banner-section ratio_60 wow fadeInUp">
    <div class="container-fluid-lg">
        <div class="banner-slider">
            @forelse ($sliderBanners as $banner)
            <div>
                <div class="banner-contain hover-effect">
                    <img src="{{ asset('storage/' . $banner->image) }}" class="bg-img blur-up lazyload"
                        alt="{{ $banner->title }}">
                    <div class="banner-details">
                        <div class="banner-box">
                            <h5>{!! $banner->title !!}</h5>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            {{-- Không hiển thị gì nếu không có banner --}}
            @endforelse
        </div>
    </div>
</section>
{{-- BANNER SECTION END --}}

<!-- PRODUCT SECTION STRAT -->
<section class="product-section">
    <div class="container-fluid-lg">
        <div class="row g-sm-4 g-3">
            <div class="col-xxl-3 col-xl-4 d-none d-xl-block">
                <div class="p-sticky">
                    <div class="category-menu">
                        {{-- <h3>Danh Mục</h3>
                        <ul>
                            @foreach ($categories as $category)
                            @php
                            // Chuyển tên category thành tên ảnh (bỏ dấu cách, ký tự đặc biệt)
                            $imgName = strtolower($category->name);
                            $imgName = str_replace([' ', '&'], '-', $imgName);
                            $imgName = preg_replace('/[^a-z0-9\-]/', '', $imgName);
                            @endphp

                            <li @if ($loop->last) class="pb-30" @endif>
                                <div class="category-list">
                                    <img src="{{ asset('storage/' . $category->image) }}"
                        alt="{{ $category->name }}"
                        style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;"
                        class="w-20 h-20 object-cover">
                        <h5>
                            <a
                                href="{{ route('client.product.index', ['category_id' => $category->id]) }}">{{ $category->name }}</a>
                        </h5>
                    </div>
                    </li>
                    @endforeach
                    </ul> --}}
                    <h3>Danh Mục</h3>
                    <ul>
                        @foreach ($categories as $category)
                        @php
                        // Xử lý tên ảnh nếu cần
                        $imgName = strtolower($category->name);
                        $imgName = str_replace([' ', '&'], '-', $imgName);
                        $imgName = preg_replace('/[^a-z0-9\-]/', '', $imgName);
                        @endphp

                        <li @if ($loop->last) class="pb-30" @endif>
                            <div class="category-list">
                                <img src="{{ asset('storage/' . $category->image) }}"
                                    alt="{{ $category->name }}"
                                    style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;"
                                    class="w-20 h-20 object-cover">

                                <h5>
                                    {{-- dùng dm[] để request('dm') là mảng và sidebar sẽ tick sẵn --}}
                                    <a
                                        href="{{ route('client.product.catalog', ['dm[]' => $category->id, 'page' => 1]) }}">
                                        {{ $category->name }}
                                    </a>
                                </h5>
                            </div>
                        </li>
                        @endforeach
                    </ul>

                    <ul class="value-list">
                        <li>
                            <div class="category-list">
                                <h5 class="ms-0 text-title">
                                    <a href="#quandeptrai">Ưu đãi hot nhất</a>
                                </h5>
                            </div>
                        </li>
                        <li class="mb-0">
                            <div class="category-list">
                                <h5 class="ms-0 text-title">
                                    <a href="#latest-products">Hàng mới về</a>
                                </h5>
                            </div>
                        </li>
                        <li>
                            <div class="category-list">
                                <h5 class="ms-0 text-title">
                                    <a href="#banchay">Lựa chọn hàng đầu</a>
                                </h5>
                            </div>
                        </li>
                    </ul>
                </div>


                @if ($productSectionPromoLeftTop)
                <div class="ratio_156 section-t-space">
                    <div class="home-contain hover-effect">
                        <img src="{{ asset('storage/' . $productSectionPromoLeftTop->image) }}"
                            class="bg-img blur-up lazyload" alt="{{ $productSectionPromoLeftTop->title }}">
                        <div class="home-detail p-top-left home-p-medium">
                            <div>
                                <h6 class="text-yellow home-banner">{!! $productSectionPromoLeftTop->title !!}</h6>
                                {{-- <h3 class="text-uppercase fw-normal"><span class="theme-color fw-bold">{{ $productSectionPromoLeftTop->link }}</span></h3> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if ($productSectionPromoLeftBottom)
                <div class="ratio_medium section-t-space">
                    <div class="home-contain hover-effect">
                        <img src="{{ asset('storage/' . $productSectionPromoLeftBottom->image) }}"
                            class="img-fluid blur-up lazyload"
                            alt="{{ $productSectionPromoLeftBottom->title }}">
                        <div class="home-detail p-top-left home-p-medium">
                            <div>
                                <h4 class="text-yellow text-exo home-banner">{!! $productSectionPromoLeftBottom->title !!}</h4>
                                {{-- <h2 class="text-uppercase fw-normal mb-0 text-russo theme-color">{{ $productSectionPromoLeftBottom->link }}</h2> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="section-t-space">
                    <div class="category-menu">
                        <h3>Sản phẩm thịnh hành</h3>

                        <ul class="product-list border-0 p-0 d-block">
                            @php
                            $trendingProducts = $topViewedProducts->take(4);
                            @endphp
                            @foreach ($trendingProducts as $product)
                            @php
                            // Xử lý logic stock cho sản phẩm thịnh hành
                            $displayProduct = null;
                            $displayVariant = null;

                            if ($product->has_variants) {
                            // Sản phẩm có biến thể
                            $availableVariants = $product->variants
                            ->where('stock', '>', 0)
                            ->where('active', 1);
                            if ($availableVariants->count() > 0) {
                            $displayProduct = $product;
                            $displayVariant = $availableVariants->first();
                            }
                            } else {
                            // Sản phẩm không có biến thể
                            if ($product->stock > 0) {
                            $displayProduct = $product;
                            }
                            }
                            @endphp

                            @if ($displayProduct)
                            <li>
                                <div class="offer-product">
                                    <a href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}"
                                        class="offer-image">
                                        <img src="{{ $displayProduct->image ? asset('storage/' . $displayProduct->image) : asset('images/no-image.png') }}"
                                            class="blur-up lazyload" alt="{{ $displayProduct->name }}">

                                    </a>
                                    <div class="offer-detail">
                                        <div>
                                            <a href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}"
                                                class="text-title">
                                                <h6 class="name">{{ $displayProduct->name }}</h6>
                                            </a>
                                            <span>{{ $displayVariant ? $displayVariant->weight : $displayProduct->weight ?? '' }}</span>
                                            <h6 class="price theme-color">
                                                {{ number_format($displayVariant ? $displayVariant->price : $displayProduct->price) }}₫
                                            </h6>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endif
                            @endforeach
                        </ul>
                    </div>
                </div>

                <div class="section-t-space">
                    <div class="category-menu">
                        <h3>Nhận xét của khách hàng</h3> <!-- Tiêu đề phần -->
                        <div class="review-box">
                            <div class="review-contain">
                                <h5 class="w-75">Chúng tôi quan tâm đến trải nghiệm của khách hàng</h5>
                                <!-- Thay đổi tiêu đề con -->
                                <p>Chúng tôi luôn nỗ lực để mang lại trải nghiệm tốt nhất cho khách hàng. Cảm ơn bạn
                                    đã tin tưởng và đồng hành cùng chúng tôi.</p> <!-- Thay đổi đoạn văn bản -->
                            </div>

                            <div class="review-profile">
                                <div class="review-image">
                                    <img src="../backend/assets/images/users/27.jpg"
                                        class="img-fluid blur-up lazyload" alt="">
                                </div>
                                <div class="review-detail">
                                    <h5>Sir Alex Ferguson</h5> <!-- Tên người đánh giá -->
                                    <h6>Giám đốc bán hàng</h6> <!-- Chức vụ -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xxl-9 col-xl-8">
            <div class="title title-flex">
                <div id="quandeptrai">
                    <h2>Sản Phẩm Nổi Bật</h2>
                    <span class="title-leaf">
                        <svg class="icon-width">
                            <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                        </svg>
                    </span>
                    <p>Sản phẩm hot nhất tuần này – Đặt sớm kẻo lỡ, số lượng có hạn!</p>
                </div>
                {{-- <div class="timing-box">
                            <div class="timing">
                                <i data-feather="clock"></i>
                                <h6 class="name">Expires in :</h6>
                                <div class="time" id="clockdiv-1" data-hours="1" data-minutes="2" data-seconds="3">
                                    <ul>
                                        <li>
                                            <div class="counter">
                                                <div class="days">
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div class="hours">
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div class="minutes">
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="counter">
                                                <div class="seconds">
                                                    <h6></h6>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div> --}}
            </div>

            <div class="section-b-space">
                <div class="product-border overflow-hidden">
                    <div class="container">
                        @foreach ($topViewedProducts->chunk(4) as $chunk)
                        <div class="row">
                            @foreach ($chunk as $product)
                            @php
                            // Xử lý logic stock cho sản phẩm
                            $displayProduct = null;
                            $displayVariant = null;

                            if ($product->has_variants) {
                            // Sản phẩm có biến thể
                            $availableVariants = $product->variants
                            ->where('stock', '>', 0)
                            ->where('active', 1);
                            if ($availableVariants->count() > 0) {
                            $displayProduct = $product;
                            $displayVariant = $availableVariants->first();
                            }
                            } else {
                            // Sản phẩm không có biến thể
                            if ($product->stock > 0) {
                            $displayProduct = $product;
                            }
                            }
                            @endphp

                            @if ($displayProduct)
                            <div class="col-md-3 col-sm-6 col-12 mb-4">
                                <div class="product-box" style="position: relative;">
                                    <div class="label-tagg label-tagg-top">
                                        <span>TOP</span>
                                    </div>
                                    <style>
                                        .label-tagg-top {
                                            border: none;
                                            /* bỏ viền */
                                            box-shadow: none;
                                            /* bỏ bóng viền */
                                        }

                                        .wishlist-btn.fill-heart svg {
                                            fill: #4a5568 !important;
                                            stroke: #4a5568 !important;
                                        }

                                        .product-option li {
                                            width: 50% !important;
                                        }
                                    </style>
                                    <div class="product-image">
                                        <a
                                            href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                            @php
                                            $imagePath = $displayProduct->image
                                            ? asset('storage/' . $displayProduct->image)
                                            : asset('images/no-image.png');
                                            @endphp
                                            <img src="{{ $imagePath }}"
                                                alt="{{ $displayProduct->name }}"
                                                class="img-fluid blur-up lazyload"
                                                style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;"
                                                onerror="this.src='{{ asset('images/no-image.png') }}'">

                                        </a>
                                        <ul class="product-option">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                title="View">
                                                <a href="javascript:void(0)" class="quickview-btn"
                                                    data-slug="{{ $product->slug }}"
                                                    title="Xem nhanh">
                                                    <i data-feather="eye"></i>
                                                </a>
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
                                    <div class="product-detail">
                                        <a
                                            href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                            <h6 class="name">{{ $displayProduct->name }}</h6>
                                        </a>

                                        <h5 class="sold text-content">
                                            <span
                                                class="theme-color price">{{ number_format($displayVariant ? $displayVariant->price : $displayProduct->price) }}₫</span>
                                        </h5>
                                        <div class="product-rating mt-sm-2 mt-1">
                                            <ul class="rating">
                                                @php
                                                // Nếu có avg_rating > 0 thì làm tròn lên mỗi 0.5 thành 1 sao
                                                if (
                                                $product->avg_rating &&
                                                $product->avg_rating > 0
                                                ) {
                                                $filledStars = ceil(
                                                $product->avg_rating - 0.5 + 0.0001,
                                                ); // trừ 0.5 rồi làm tròn lên
                                                $filledStars = max(0, min($filledStars, 5)); // không vượt quá 5 sao
                                                } else {
                                                $filledStars = 4; // mặc định nếu chưa có đánh giá
                                                }
                                                @endphp

                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li>
                                                    <i data-feather="star"
                                                        @if ($i <=$filledStars) class="fill" @endif></i>
                                                    </li>
                                                    @endfor
                                            </ul>

                                            <h6 class="theme-color">Còn hàng</h6>
                                        </div>
                                        <div class="add-to-cart-box">
                                            {{-- <button class="btn btn-add-cart addcart-button">Add
                                                                <span class="add-icon"><i
                                                                        class="fa-solid fa-plus"></i></span>
                                                            </button> --}}
                                            <div class="cart_qty qty-box">
                                                <div class="input-group">
                                                    <button type="button" class="qty-left-minus"
                                                        data-type="minus">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </button>
                                                    <input class="form-control input-number qty-input"
                                                        type="text" value="0">
                                                    <button type="button" class="qty-right-plus"
                                                        data-type="plus">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- .product-box -->
                            </div>
                            @endif
                            @endforeach
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
            {{-- <div class="title">
                    <h2>Sản phẩm theo danh mục</h2>
                    <span class="title-leaf">
                        <svg class="icon-width">
                            <use xlink:href="{{ asset('frontend/assets/svg/leaf.svg#leaf') }}"></use>
            </svg>
            </span>
            <p>Khám phá đa dạng các loại đặc sản</p>
        </div>

        <div class="category-slider-2 product-wrapper no-arrow">
            @forelse ($categories as $category)
            <div>
                <a href="{{ route('client.product.index', ['category_id' => $category->id]) }}"
                    class="category-box category-dark">
                    <div>
                        <img src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;">
                        <h5>{{ $category->name }}</h5>
                    </div>
                </a>
            </div>
            @empty
            <p>Không có danh mục nào.</p>
            @endforelse
        </div> --}}
        <div class="title">
            <h2>Sản phẩm theo danh mục</h2>
            <span class="title-leaf">
                <svg class="icon-width">
                    <use xlink:href="{{ asset('frontend/assets/svg/leaf.svg#leaf') }}"></use>
                </svg>
            </span>
            <p>Khám phá đa dạng các loại đặc sản</p>
        </div>

        <div class="category-slider-2 product-wrapper no-arrow">
            @forelse ($categories as $category)
            <div>
                {{-- đẩy dm[] để request('dm') nhận dạng là mảng và checkbox được tick --}}
                <a href="{{ route('client.product.catalog', ['dm[]' => $category->id, 'page' => 1]) }}"
                    class="category-box category-dark" title="Xem {{ $category->name }}">
                    <div>
                        <img src="{{ asset('storage/' . $category->image) }}"
                            alt="{{ $category->name }}"
                            style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;">
                        <h5>{{ $category->name }}</h5>
                    </div>
                </a>
            </div>
            @empty
            <p>Không có danh mục nào.</p>
            @endforelse
        </div>



        <div class="section-t-space section-b-space">
            <div class="row g-md-4 g-3">
                <div class="col-md-6">
                    <div class="banner-contain hover-effect">
                        <img src="../frontend/assets/images/vegetable/banner/9.jpg"
                            class="bg-img blur-up lazyload" alt="">
                        <div class="banner-details p-center-left p-4">
                            <div>
                                <h3 class="text-exo">Ưu đãi 50%</h3>
                                <h4 class="text-russo fw-normal theme-color mb-2">Thơm ngon – Tươi mới</h4>
                                <button onclick="location.href = 'shop-left-sidebar.html';"
                                    class="btn btn-animation btn-sm mend-auto">Mua ngay <i
                                        class="fa-solid fa-arrow-right icon"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="banner-contain hover-effect">
                        <img src="../frontend/assets/images/vegetable/banner/10.jpg"
                            class="bg-img blur-up lazyload" alt="">
                        <div class="banner-details p-center-left p-4">
                            <div>
                                <h3 class="text-exo">Ưu đãi 50%</h3>
                                <h4 class="text-russo fw-normal theme-color mb-2">Chất lượng – Giá tốt</h4>
                                <button onclick="location.href = 'shop-left-sidebar.html';"
                                    class="btn btn-animation btn-sm mend-auto">Mua ngay <i
                                        class="fa-solid fa-arrow-right icon"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="title d-block" id="latest-products">
            <h2>Sản phẩm mới</h2>
            <span class="title-leaf">
                <svg class="icon-width">
                    <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                </svg>
            </span>
            <p>Khám phá những món quà quê mới nhất, tươi ngon và đậm đà hương vị truyền thống.</p>
        </div>

        <div class="product-border overflow-hidden wow fadeInUp">
            <div class="container">
                <div class="row">
                    @foreach ($latestProducts as $product)
                    @php
                    // Xử lý logic stock cho sản phẩm mới
                    $displayProduct = null;
                    $displayVariant = null;

                    if ($product->has_variants) {
                    // Sản phẩm có biến thể
                    $availableVariants = $product->variants
                    ->where('stock', '>', 0)
                    ->where('active', 1);
                    if ($availableVariants->count() > 0) {
                    $displayProduct = $product;
                    $displayVariant = $availableVariants->first();
                    }
                    } else {
                    // Sản phẩm không có biến thể
                    if ($product->stock > 0) {
                    $displayProduct = $product;
                    }
                    }
                    @endphp

                    @if ($displayProduct)
                    <div class="col-6 col-md-3 mb-4">
                        <div class="product-box">
                            <div class="label-tag"><span>NEW</span></div>
                            <div class="product-image">
                                <a
                                    href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                    <img src="{{ $displayProduct->image ? asset('storage/' . $displayProduct->image) : asset('images/no-image.png') }}"
                                        alt="{{ $displayProduct->name }}"
                                        class="img-fluid blur-up lazyload"
                                        style="filter:none !important; mix-blend-mode:normal !important; opacity:1 !important;">

                                </a>
                                <ul class="product-option">
                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                        title="View">
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
                            <div class="product-detail">
                                <a
                                    href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                    <h6 class="name h-100">{{ $displayProduct->name }}</h6>
                                </a>


                                <h5 class="sold text-content">
                                    <span
                                        class="theme-color price">{{ number_format($displayVariant ? $displayVariant->price : $displayProduct->price, 0, ',', '.') }}₫</span>
                                    @if ($displayProduct->old_price)
                                    <del>{{ number_format($displayProduct->old_price, 0, ',', '.') }}₫</del>
                                    @endif
                                </h5>
                                <div class="product-rating mt-sm-2 mt-1">
                                    <ul class="rating">
                                        @php
                                        // Nếu có avg_rating > 0 thì làm tròn lên mỗi 0.5 thành 1 sao
                                        if ($product->avg_rating && $product->avg_rating > 0) {
                                        $filledStars = ceil(
                                        $product->avg_rating - 0.5 + 0.0001,
                                        ); // trừ 0.5 rồi làm tròn lên
                                        $filledStars = max(0, min($filledStars, 5)); // không vượt quá 5 sao
                                        } else {
                                        $filledStars = 4; // mặc định nếu chưa có đánh giá
                                        }
                                        @endphp

                                        @for ($i = 1; $i <= 5; $i++)
                                            <li>
                                            <i data-feather="star"
                                                @if ($i <=$filledStars) class="fill" @endif></i>
                                            </li>
                                            @endfor
                                    </ul>

                                    <h6 class="theme-color">
                                        {{ ($displayVariant ? $displayVariant->stock : $displayProduct->stock) > 0 ? 'Còn hàng' : 'Out of Stock' }}
                                    </h6>
                                </div>
                                <div class="add-to-cart-box">
                                    {{-- <button class="btn btn-add-cart addcart-button">Add
                                                        <span class="add-icon">
                                                            <i class="fa-solid fa-plus"></i>
                                                        </span>
                                                    </button> --}}
                                    <div class="cart_qty qty-box">
                                        <div class="input-group">
                                            <button type="button" class="qty-left-minus"
                                                data-type="minus" data-field="">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                            <input class="form-control input-number qty-input"
                                                type="text" name="quantity" value="0">
                                            <button type="button" class="qty-right-plus"
                                                data-type="plus" data-field="">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- .product-box -->
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Cashback banner --}}
        @if ($newProductsCashbackBanner)
        <div class="section-t-space">
            <div class="banner-contain hover-effect" style="min-height: 450px;">
                <img src="{{ asset('storage/' . $newProductsCashbackBanner->image) }}"
                    class="bg-img blur-up lazyload" alt="{{ $newProductsCashbackBanner->title }}">
                <div class="banner-details p-center p-4 text-white text-center">
                    <div>
                        <h3 class="lh-base fw-bold offer-text">{!! $newProductsCashbackBanner->title !!}</h3>
                        {{-- <h6 class="coupon-code">{!! $newProductsCashbackBanner->link !!}</h6> --}}
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Promo banners --}}
        <div class="section-t-space section-b-space">
            <div class="row g-md-4 g-3">
                @if ($newProductsPromoLeft)
                <div class="col-xxl-8 col-xl-12 col-md-7">
                    <div class="banner-contain hover-effect" style="min-height: 350px;">
                        <img src="{{ asset('storage/' . $newProductsPromoLeft->image) }}"
                            class="bg-img blur-up lazyload" alt="{{ $newProductsPromoLeft->title }}">
                        <div class="banner-details p-center-left p-4">
                            <div>
                                <h2 class="text-kaushan fw-normal theme-color">{!! $newProductsPromoLeft->title !!}</h2>
                                {{-- <h3 class="mt-2 mb-3">{!! $newProductsPromoLeft->link !!}</h3> --}}
                                {{-- <button onclick="location.href = '{{ $newProductsPromoLeft->link ?? '#' }}';"
                                class="btn btn-animation btn-sm mend-auto">Shop Now <i
                                    class="fa-solid fa-arrow-right icon"></i></button> --}}
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                @if ($newProductsPromoRight)
                <div class="col-xxl-4 col-xl-12 col-md-5">
                    <div class="banner-contain hover-effect h-100" style="min-height: 350px;">
                        {{-- <a href="{{ $newProductsPromoRight->link ?? '#' }}" class="banner-contain hover-effect h-100"> --}}
                        <img src="{{ asset('storage/' . $newProductsPromoRight->image) }}"
                            class="bg-img blur-up lazyload" alt="{{ $newProductsPromoRight->title }}">
                        <div class="banner-details p-center-left p-4 h-100">
                            <div>
                                <h2 class="text-kaushan fw-normal text-danger">{!! $newProductsPromoRight->title !!}</h2>
                                {{-- <h3 class="mt-2 mb-2 theme-color">{!! $newProductsPromoRight->link !!}</h3> --}}
                            </div>
                        </div>
                        {{-- </a> --}}
                    </div>
                </div>
                @endif
            </div>
        </div>
        <div class="title d-block" id="banchay">
            <h2>Sản phẩm bán chạy</h2>
            <span class="title-leaf">
                <svg class="icon-width">
                    <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                </svg>
            </span>
            <p>Trợ lý ảo thu thập các sản phẩm từ danh sách của bạn</p>
        </div>
        @php
        $chunks = $bestSellingProducts->chunk(4);
        @endphp
        <div class="section-b-space">
            <div class="product-border overflow-hidden">
                <div class="container">
                    @foreach ($chunks as $chunk)
                    <div class="row">
                        @foreach ($chunk as $product)
                        @php
                        // Xử lý logic stock cho sản phẩm bán chạy
                        $displayProduct = null;
                        $displayVariant = null;

                        if ($product->has_variants) {
                        // Sản phẩm có biến thể
                        $availableVariants = $product->variants
                        ->where('stock', '>', 0)
                        ->where('active', 1);
                        if ($availableVariants->count() > 0) {
                        $displayProduct = $product;
                        $displayVariant = $availableVariants->first();
                        }
                        } else {
                        // Sản phẩm không có biến thể
                        if ($product->stock > 0) {
                        $displayProduct = $product;
                        }
                        }
                        @endphp

                        @if ($displayProduct)
                        <div class="col-md-3 col-sm-6 col-12 mb-4">
                            <div class="product-box" style="position: relative;">
                                <div class="label-tagg label-tagg-hot">
                                    <span>HOT</span>
                                </div>
                                <div class="product-image">
                                    <a
                                        href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                        <img src="{{ $displayProduct->image ? asset('storage/' . $displayProduct->image) : asset('images/no-image.png') }}"
                                            alt="{{ $displayProduct->name }}">

                                    </a>
                                    <ul class="product-option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="View">
                                            <a href="javascript:void(0)" class="quickview-btn"
                                                data-slug="{{ $product->slug }}"
                                                title="Xem nhanh">
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

                                <div class="product-detail">
                                    <a
                                        href="{{ route('client.product.detail', ['slug' => $displayProduct->slug]) }}">
                                        <h6 class="name">{{ $displayProduct->name }}</h6>
                                    </a>
                                    <h5 class="sold text-content">
                                        <span
                                            class="theme-color price">{{ number_format($displayVariant ? $displayVariant->price : $displayProduct->price) }}₫</span>
                                    </h5>
                                    <p class="text-muted small">Đã bán:
                                        {{ $displayProduct->total_sold ?? 0 }}
                                    </p>
                                    <!-- ✅ dòng mới -->
                                    <div class="product-rating mt-sm-2 mt-1">
                                        <ul class="rating">
                                            @php
                                            // Nếu có avg_rating > 0 thì làm tròn lên mỗi 0.5 thành 1 sao
                                            if (
                                            $product->avg_rating &&
                                            $product->avg_rating > 0
                                            ) {
                                            $filledStars = ceil(
                                            $product->avg_rating - 0.5 + 0.0001,
                                            ); // trừ 0.5 rồi làm tròn lên
                                            $filledStars = max(0, min($filledStars, 5)); // không vượt quá 5 sao
                                            } else {
                                            $filledStars = 4; // mặc định nếu chưa có đánh giá
                                            }
                                            @endphp

                                            @for ($i = 1; $i <= 5; $i++)
                                                <li>
                                                <i data-feather="star"
                                                    @if ($i <=$filledStars) class="fill" @endif></i>
                                                </li>
                                                @endfor
                                        </ul>

                                        <h6 class="theme-color">Còn hàng</h6>
                                    </div>
                                </div>

                            </div> <!-- .product-box -->
                        </div>
                        @endif
                        @endforeach
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    </div>
</section>
<!-- PRODUCT SECTION END -->

<!-- NEWSLETTER SECTION START -->
<section class="newsletter-section section-b-space">
    <div class="container-fluid-lg">
        <div class="newsletter-box newsletter-box-2">
            <div class="newsletter-contain py-5">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-xxl-4 col-lg-5 col-md-7 col-sm-9 offset-xxl-2 offset-md-1">
                            <div class="newsletter-detail">
                                <h2>Đăng ký nhận bản tin của chúng tôi và nhận...</h2>
                                <h5>Giảm giá 20 đô la cho đơn hàng đầu tiên của bạn</h5>
                                <div class="input-box">
                                    <input type="email" class="form-control" id="exampleFormControlInput1"
                                        placeholder="Nhập email của bạn" required>
                                    <i class="fa-solid fa-envelope arrow"></i>
                                    <button class="sub-btn  btn-animation">
                                        <span class="d-sm-block d-none">Đăng ký</span>
                                        <i class="fa-solid fa-arrow-right icon"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- NEWSLETTER SECTION END -->

<style>
    .row {
        justify-content: left !important;
    }

    /* Ảnh sản phẩm ở các phần nổi bật, mới, bán chạy */
    .product-box .product-image,
    .product-image,
    .offer-product .offer-image {
        width: 100%;
        max-width: 3050px;
        aspect-ratio: 1/1;
        position: relative;
        overflow: hidden;
        border-radius: 25px;
        background: #f8f8f8;
        display: block;
    }

    .product-box .product-image img,
    .product-image img,
    .offer-product .offer-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        border-radius: 16px;
        display: block;
        background: #f8f8f8;
    }

    /* Chỉ CSS cho ảnh sản phẩm thịnh hành */
    .offer-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
        display: block;
        background: #f8f8f8;
    }
</style>
<script>
    (() => {
        // Nếu có header dính, bù trừ để không bị che
        const OFFSET =
            document.querySelector('.header-sticky, .sticky-top, header')?.offsetHeight || 0;

        function scrollWithOffset(el) {
            if (!el) return;
            const y = el.getBoundingClientRect().top + window.scrollY - OFFSET;
            window.scrollTo({
                top: y,
                behavior: 'smooth'
            });
        }

        // Chặn TẤT CẢ handler khác (dùng capture + stopImmediatePropagation)
        document.querySelectorAll('.value-list a[href^="#"]').forEach(a => {
            // Vô hiệu các thư viện điều hướng (nếu đang dùng)
            a.setAttribute('data-turbo', 'false'); // Hotwire/Turbo
            a.setAttribute('wire:navigate', 'false'); // Livewire v3 navigate

            a.addEventListener('click', (e) => {
                const hash = a.getAttribute('href');
                const target = document.querySelector(hash);
                if (!target) return;

                e.preventDefault();
                e.stopPropagation();
                if (typeof e.stopImmediatePropagation === 'function') {
                    e.stopImmediatePropagation();
                }

                history.pushState(null, '', hash); // cập nhật URL
                scrollWithOffset(target);
            }, true); // capture: chạy trước các delegated handler toàn cục
        });

        // Nếu vào trang đã có hash (#banchay...) thì cuộn luôn
        window.addEventListener('load', () => {
            if (location.hash) {
                const t = document.querySelector(location.hash);
                if (t) setTimeout(() => scrollWithOffset(t), 0);
            }
        });
    })();
</script>

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

@endsection