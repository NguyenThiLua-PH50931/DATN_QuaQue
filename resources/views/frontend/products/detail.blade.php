<!-- <pre>{{ var_dump($product) }}</pre> -->
@extends('layouts.frontend') {{-- Đổi lại đúng layout nếu khác --}}

@section('title', $product->name)

@section('content')

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
                                <a href="{{ url('/') }}">
                                    <i class="fa-solid fa-house"></i>
                                </a>
                            </li>
                            <li class="breadcrumb-item">
                                <a href="{{ url('danh-muc/' . ($product->category->slug ?? '')) }}">
                                    {{ $product->category->name ?? '' }}
                                </a>
                            </li>
                            <li class="breadcrumb-item active">{{ $product->name }}</li>
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
                    <!-- Ảnh sản phẩm -->
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="product-left-box">
                            <div class="row g-2">
                                <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                                    <div class="product-main-2 no-arrow">
                                        <!-- Ảnh chính -->
                                        <div>
                                            <div class="slider-image">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    id="img-main"
                                                    data-zoom-image="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid image_zoom_cls-0 blur-up lazyload"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                        <!-- Ảnh phụ (nếu có) -->
                                        @foreach($product->images as $img)
                                        <div>
                                            <div class="slider-image">
                                                <img src="{{ asset('storage/' . $img->image_url) }}"
                                                    data-zoom-image="{{ asset('storage/' . $img->image_url) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="col-xxl-2 col-lg-12 col-md-2 order-xxl-1 order-lg-2 order-md-1">
                                    <div class="left-slider-image-2 left-slider no-arrow slick-top">
                                        <div>
                                            <div class="sidebar-image">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                        @foreach($product->images as $img)
                                        <div>
                                            <div class="sidebar-image">
                                                <img src="{{ asset('storage/' . $img->image_url) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }}">
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Thông tin sản phẩm -->
                    <div class="col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                        <div class="right-box-contain">
                            @if($product->discount)
                            <h6 class="offer-top">{{ $product->discount }}% Off</h6>
                            @endif
                            <h2 class="name">{{ $product->name }}</h2>
                            <div class="price-rating">
                                <h3 class="theme-color price">{{ $product->price }} ₫ <del class="text-content">$58.46</del> <span
                                        class="offer theme-color">(8% off)</span></h3>
                                <div class="product-rating custom-rate">
                                    <ul class="rating">
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star"></i>
                                        </li>
                                    </ul>
                                    <span class="review">23 Customer Review</span>
                                </div>
                            </div>

                            <div class="procuct-contain">
                                <p>{{ $product->description }}</p>
                            </div>

                            <div class="product-packege">
                                <div class="product-title">
                                    <h4>Kho: <span class="fw-normal">{{ $product->stock }}</span></h4>
                                </div>
                            </div>
                            <div class="product-packege">
                                <div class="product-title">
                                    <h4>Xuất xứ: <span class="fw-normal">{{ $product->origin }}</span></h4>
                                </div>
                            </div>

                            <!-- Nút thêm giỏ hàng -->
                            <div class="note-box product-packege mt-2">
                                <form action="" method="POST" class="d-flex align-items-center">
                                    @csrf
                                    <div class="cart_qty qty-box product-qty me-2">
                                        <div class="input-group">
                                            <input class="form-control input-number qty-input" type="number"
                                                name="quantity" value="1" min="1" max="{{ $product->stock }}">
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-md bg-dark cart-button text-white">Thêm vào giỏ hàng</button>
                                </form>
                            </div>

                            <div class="buy-box mt-3">
                                <a href="">
                                    <i data-feather="heart"></i>
                                    <span>Yêu thích</span>
                                </a>
                            </div>
                            <div class="pickup-box">
                                <div class="product-title">
                                    <h4>Người bán</h4>
                                </div>
                                <div class="pickup-detail">
                                    <h4 class="text-content">{{ $product->seller->name ?? 'Chưa có' }}</h4>
                                </div>
                                <div class="product-info">
                                    <ul class="product-info-list product-info-list-2">
                                        <li>Email: <span class="text-content">{{ $product->seller->email ?? '' }}</span></li>
                                        <li>Phone: <span class="text-content">{{ $product->seller->phone ?? '' }}</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="paymnet-option mt-3">
                                <div class="product-title">
                                    <h4>Thanh toán an toàn</h4>
                                </div>
                                <ul>
                                    <li><img src="{{ asset('frontend/assets/images/product/payment/1.svg') }}" class="blur-up lazyload" alt=""></li>
                                    <li><img src="{{ asset('frontend/assets/images/product/payment/2.svg') }}" class="blur-up lazyload" alt=""></li>
                                    <li><img src="{{ asset('frontend/assets/images/product/payment/3.svg') }}" class="blur-up lazyload" alt=""></li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Tab mô tả & đánh giá -->
                    <div class="col-12">
                        <div class="product-section-box">
                            <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                        data-bs-target="#description" type="button" role="tab"
                                        aria-controls="description" aria-selected="true">Mô tả</button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="review-tab" data-bs-toggle="tab"
                                        data-bs-target="#review" type="button" role="tab" aria-controls="review"
                                        aria-selected="false">Đánh giá</button>
                                </li>
                            </ul>
                            <div class="tab-content custom-tab" id="myTabContent">
                                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                    <div class="product-description">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="review" role="tabpanel" aria-labelledby="review-tab">
                                    <div class="review-box">
                                        <div class="review-title">
                                            <h4 class="fw-500">Đánh giá sản phẩm</h4>
                                        </div>
                                        <!-- Thêm form đánh giá nếu đã đăng nhập -->
                                        @auth
                                        <form action="" method="POST" class="mt-3">
                                            @csrf
                                            <div class="mb-2">
                                                <label>Chấm điểm:</label>
                                                <select name="rating" class="form-select w-auto d-inline">
                                                    @for($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} sao</option>
                                                    @endfor
                                                </select>
                                            </div>
                                            <div class="mb-2">
                                                <textarea name="comment" class="form-control" placeholder="Nhận xét..." required></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                        </form>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar phải: thông tin shop + sản phẩm nổi bật -->
            <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                <div class="right-sidebar-box">
                    <div class="vendor-box">
                        <div class="verndor-contain">
                            <div class="vendor-image">
                                <img src="{{ asset('frontend/assets/images/product/vendor.png') }}" class="blur-up lazyload" alt="">
                            </div>
                            <div class="vendor-name">
                                <h5 class="fw-500">{{ $product->seller->name ?? 'Shop' }}</h5>
                                <div class="product-rating mt-1">
                                    <ul class="rating">
                                        @for($i=1; $i<=5; $i++)
                                            <li>
                                            <i data-feather="star" class=""></i>
                                            </li>
                                            @endfor
                                    </ul>
                                    <span> Đánh giá</span>
                                </div>
                            </div>
                        </div>
                        <p class="vendor-detail">Liên hệ người bán để biết thêm thông tin sản phẩm và hỗ trợ.</p>
                        <div class="vendor-list">
                            <ul>
                                <li>
                                    <div class="address-contact">
                                        <i data-feather="map-pin"></i>
                                        <h5>Địa chỉ: <span class="text-content">Đang cập nhật</span></h5>
                                    </div>
                                </li>
                                <li>
                                    <div class="address-contact">
                                        <i data-feather="headphones"></i>
                                        <h5>Liên hệ: <span class="text-content">{{ $product->seller->phone ?? '-' }}</span></h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <!-- Trending Product (tùy chỉnh nếu có) -->
                    <div class="pt-25">
                        <div class="category-menu">
                            <h3>Sản phẩm nổi bật</h3>
                            <ul class="product-list product-right-sidebar border-0 p-0">
                                @foreach($topProducts as $top)
                                <li>
                                    <div class="offer-product">
                                        <a href="{{ route('products.detail', $top->slug) }}" class="offer-image">
                                            <img src="{{ asset('storage/' . $top->image) }}" class="img-fluid blur-up lazyload" alt="">
                                        </a>
                                        <div class="offer-detail">
                                            <div>
                                                <a href="{{ route('products.detail', $top->slug) }}">
                                                    <h6 class="name">{{ $top->name }}</h6>
                                                </a>
                                                <h6 class="price theme-color">{{ number_format($top->price, 0, ',', '.') }} đ</h6>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                    <!-- Banner -->
                    <div class="ratio_156 pt-25">
                        <div class="home-contain">
                            <img src="{{ asset('frontend/assets/images/vegetable/banner/8.jpg') }}" class="bg-img blur-up lazyload" alt="">
                            <div class="home-detail p-top-left home-p-medium">
                                <div>
                                    <h6 class="text-yellow home-banner">Seafood</h6>
                                    <h3 class="text-uppercase fw-normal"><span class="theme-color fw-bold">Freshes</span> Products</h3>
                                    <h3 class="fw-light">every hour</h3>
                                    <button onclick="location.href = '';"
                                        class="btn btn-animation btn-md fw-bold mend-auto">Shop Now <i class="fa-solid fa-arrow-right icon"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Product Left Sidebar End -->

<!-- Releted Product Section Start -->
<section class="product-list-section section-b-space">
    <div class="container-fluid-lg">
        <div class="title">
            <h2>Sản phẩm liên quan</h2>
            <span class="title-leaf">
                <svg class="icon-width">
                    <use xlink:href="{{ asset('frontend/assets/svg/leaf.svg#leaf') }}"></use>
                </svg>
            </span>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="slider-6_1 product-wrapper">
                    @foreach($relatedProducts as $rel)
                    <div>
                        <div class="product-box-3 wow fadeInUp">
                            <div class="product-header">
                                <div class="product-image">
                                    <a href="{{ route('products.detail', $rel->slug) }}">
                                        <img src="{{ asset('storage/' . $rel->image) }}" class="img-fluid blur-up lazyload" alt="{{ $rel->name }}">
                                    </a>
                                </div>
                            </div>
                            <div class="product-footer">
                                <div class="product-detail">
                                    <span class="span-name">{{ $rel->category->name ?? '' }}</span>
                                    <a href="{{ route('products.detail', $rel->slug) }}">
                                        <h5 class="name">{{ $rel->name }}</h5>
                                    </a>
                                    <div class="product-rating mt-2">
                                        <ul class="rating">
                                            <li><i data-feather="star" class="fill"></i></li>
                                            <li><i data-feather="star" class="fill"></i></li>
                                            <li><i data-feather="star" class="fill"></i></li>
                                            <li><i data-feather="star" class="fill"></i></li>
                                            <li><i data-feather="star" class="fill"></i></li>
                                        </ul>
                                        <span>(5)</span>
                                    </div>
                                    {{-- Nếu muốn có đơn vị thêm ở đây, còn không thì bỏ --}}
                                    {{-- <h6 class="unit">500 G</h6> --}}
                                    <h5 class="price">
                                        <span class="theme-color">{{ number_format($rel->price, 0, ',', '.') }} đ</span>
                                        {{-- Nếu có giá gốc thì hiện <del> ở đây --}}
                                        {{-- @if($rel->old_price)
                                            <del>{{ number_format($rel->old_price, 0, ',', '.') }} đ</del>
                                        @endif --}}
                                    </h5>
                                    <div class="add-to-cart-box bg-white">
                                        <button class="btn btn-add-cart addcart-button">Thêm
                                            <span class="add-icon bg-light-gray">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                        </button>
                                        {{-- Nếu có muốn cho nhập số lượng thì code thêm --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Releted Product Section End -->

@endsection