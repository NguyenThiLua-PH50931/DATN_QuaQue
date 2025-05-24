@extends('layouts.frontend')
@section('title', 'Trang chủ')
@section('contents')
     <!-- HOME SECTION START -->
        <section class="home-section pt-2">
            <div class="container-fluid-lg">
                <div class="row g-4">
                    <div class="col-xl-8 ratio_65">
                        <div class="home-contain h-100">
                            <div class="h-100">
                                <img src="../frontend/assets/images/vegetable/banner/1.jpg" class="bg-img blur-up lazyload" alt="">
                            </div>
                            <div class="home-detail p-center-left w-75">
                                <div>
                                    <h6>Ưu đãi đặc biệt <span>Giảm 30%</span></h6>
                                    <h1 class="text-uppercase">
                                        Ở nhà an toàn & giao tận nơi <span class="daily">Món quà quê hằng ngày</span>
                                    </h1>
                                    <p class="w-75 d-none d-sm-block">
                                        Rau củ chứa nhiều vitamin và khoáng chất tốt cho sức khỏe của bạn.
                                    </p>
                                    <button onclick="location.href = 'shop-left-sidebar.html';"
                                        class="btn btn-animation mt-xxl-4 mt-2 home-button mend-auto">
                                        Mua ngay <i class="fa-solid fa-right-long icon"></i>
                                    </button>
                                </div>
                            </div>

                        </div>
                    </div>

                    <div class="col-xl-4 ratio_65">
                        <div class="row g-4">
                            <div class="col-xl-12 col-md-6">
                                <div class="home-contain">
                                    <img src="../frontend/assets/images/vegetable/banner/2.jpg" class="bg-img blur-up lazyload" alt="">
                                    <div class="home-detail p-center-left home-p-sm w-75">
                                        <div>
                                            <h2 class="mt-0 text-danger">45% <span class="discount text-title">GIẢM</span></h2>
                                            <h3 class="theme-color">Bộ Sưu Tập Hạt</h3>
                                            <p class="w-75">Chúng tôi giao rau củ quả hữu cơ đến tận nhà bạn</p>
                                            <a href="shop-left-sidebar.html" class="shop-button">Mua ngay <i class="fa-solid fa-right-long"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-xl-12 col-md-6">
                                <div class="home-contain">
                                    <img src="../frontend/assets/images/vegetable/banner/3.jpg" class="bg-img blur-up lazyload" alt="">
                                    <div class="home-detail p-center-left home-p-sm w-75">
                                        <div>
                                            <h3 class="mt-0 theme-color fw-bold">Thực Phẩm Lành Mạnh</h3>
                                            <h4 class="text-danger">Chợ Hữu Cơ</h4>
                                            <p class="organic">Bắt đầu mua sắm hằng ngày với các sản phẩm hữu cơ chất lượng</p>
                                            <a href="shop-left-sidebar.html" class="shop-button">Mua ngay <i class="fa-solid fa-right-long"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
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
                    <div>
                        <div class="banner-contain hover-effect">
                            <img src="../frontend/assets/images/vegetable/banner/4.jpg" class="bg-img blur-up lazyload" alt="">
                            <div class="banner-details">
                                <div class="banner-box">
                                    <h6 class="text-danger">Giảm 5%</h6>
                                    <h5>Ưu Đãi Hot Cho Sản Phẩm Mới</h5>
                                    <h6 class="text-content">Thực Phẩm Hằng Ngày: Trứng & Sữa</h6>
                                </div>
                                <a href="shop-left-sidebar.html" class="banner-button text-white">Mua Ngay <i
                                        class="fa-solid fa-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="banner-contain hover-effect">
                            <img src="../frontend/assets/images/vegetable/banner/5.jpg" class="bg-img blur-up lazyload" alt="">
                            <div class="banner-details">
                                <div class="banner-box">
                                    <h6 class="text-danger">Giảm 5%</h6>
                                    <h5>Mua Nhiều Hơn, Tiết Kiệm Hơn</h5>
                                    <h6 class="text-content">Rau Củ Tươi Ngon</h6>
                                </div>
                                <a href="shop-left-sidebar.html" class="banner-button text-white">Mua Ngay <i
                                        class="fa-solid fa-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="banner-contain hover-effect">
                            <img src="../frontend/assets/images/vegetable/banner/6.jpg" class="bg-img blur-up lazyload" alt="">
                            <div class="banner-details">
                                <div class="banner-box">
                                    <h6 class="text-danger">Giảm 5%</h6>
                                    <h5>Thịt Hữu Cơ Tươi Ngon</h5>
                                    <h6 class="text-content">Giao Tận Nhà Bạn</h6>
                                </div>
                                <a href="shop-left-sidebar.html" class="banner-button text-white">Mua Ngay <i
                                        class="fa-solid fa-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>

                    <div>
                        <div class="banner-contain hover-effect">
                            <img src="../frontend/assets/images/vegetable/banner/7.jpg" class="bg-img blur-up lazyload" alt="">
                            <div class="banner-details">
                                <div class="banner-box">
                                    <h6 class="text-danger">Giảm 5%</h6>
                                    <h5>Mua Nhiều Hơn, Tiết Kiệm Hơn</h5>
                                    <h6 class="text-content">Hạt & Đồ Ăn Vặt</h6>
                                </div>
                                <a href="shop-left-sidebar.html" class="banner-button text-white">Mua Ngay <i
                                        class="fa-solid fa-right-long ms-2"></i></a>
                            </div>
                        </div>
                    </div>
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
                                <h3>Danh Mục</h3>
                               <ul>
                                    @foreach ($categories as $category)
                                        @php
                                            // Chuyển tên category thành tên ảnh (bỏ dấu cách, ký tự đặc biệt)
                                            $imgName = strtolower($category->name);
                                            $imgName = str_replace([' ', '&'], '-', $imgName);
                                            $imgName = preg_replace('/[^a-z0-9\-]/', '', $imgName);
                                        @endphp

                                        <li @if($loop->last) class="pb-30" @endif>
                                            <div class="category-list">
                                                 <img src="{{ asset('frontend/assets/svg/1/' . $category->image) }}" alt="{{ $category->name }}">
                                                <h5>
                                                    <a href="{{ url('/products/category') }}">{{ $category->name }}</a>
                                                </h5>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>


                               <ul class="value-list">
                                    <li>
                                        <div class="category-list">
                                            <h5 class="ms-0 text-title">
                                                <a href="shop-left-sidebar.html">Quà tặng trong ngày</a>
                                            </h5>
                                        </div>
                                    </li>
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
                                </ul>

                            </div>

                            <div class="ratio_156 section-t-space">
                                <div class="home-contain hover-effect">
                                    <img src="../frontend/assets/images/vegetable/banner/8.jpg" class="bg-img blur-up lazyload" alt="">
                                    <div class="home-detail p-top-left home-p-medium">
                                        <div>
                                            <h6 class="text-yellow home-banner">Đặc sản</h6>
                                            <h3 class="text-uppercase fw-normal"><span class="theme-color fw-bold">Sản phẩm</span> Tươi ngon</h3>
                                            <h3 class="fw-light">luôn sẵn sàng mỗi ngày</h3>
                                            <button onclick="location.href = 'shop-left-sidebar.html';"
                                                class="btn btn-animation btn-md mend-auto">Mua ngay <i
                                                    class="fa-solid fa-arrow-right icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="ratio_medium section-t-space">
                                <div class="home-contain hover-effect">
                                    <img src="../frontend/assets/images/vegetable/banner/11.jpg" class="img-fluid blur-up lazyload"
                                        alt="">
                                    <div class="home-detail p-top-left home-p-medium">
                                        <div>
                                            <h4 class="text-yellow text-exo home-banner">Organic</h4>
                                            <h2 class="text-uppercase fw-normal mb-0 text-russo theme-color">fresh</h2>
                                            <h2 class="text-uppercase fw-normal text-title">Vegetables</h2>
                                            <p class="mb-3">Super Offer to 50% Off</p>
                                            <button onclick="location.href = 'shop-left-sidebar.html';"
                                                class="btn btn-animation btn-md mend-auto">Shop Now <i
                                                    class="fa-solid fa-arrow-right icon"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="section-t-space">
                                <div class="category-menu">
                                    <h3>Trending Products</h3>

                                    <ul class="product-list border-0 p-0 d-block">
                                        <li>
                                            <div class="offer-product">
                                                <a href="product-left-thumbnail.html" class="offer-image">
                                                    <img src="../frontend/assets/images/vegetable/product/23.png"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="offer-detail">
                                                    <div>
                                                        <a href="product-left-thumbnail.html" class="text-title">
                                                            <h6 class="name">Meatigo Premium Goat Curry</h6>
                                                        </a>
                                                        <span>450 G</span>
                                                        <h6 class="price theme-color">$ 70.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="offer-product">
                                                <a href="product-left-thumbnail.html" class="offer-image">
                                                    <img src="../frontend/assets/images/vegetable/product/24.png"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="offer-detail">
                                                    <div>
                                                        <a href="product-left-thumbnail.html" class="text-title">
                                                            <h6 class="name">Dates Medjoul Premium Imported</h6>
                                                        </a>
                                                        <span>450 G</span>
                                                        <h6 class="price theme-color">$ 40.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li>
                                            <div class="offer-product">
                                                <a href="product-left-thumbnail.html" class="offer-image">
                                                    <img src="../frontend/assets/images/vegetable/product/25.png"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="offer-detail">
                                                    <div>
                                                        <a href="product-left-thumbnail.html" class="text-title">
                                                            <h6 class="name">Good Life Walnut Kernels</h6>
                                                        </a>
                                                        <span>200 G</span>
                                                        <h6 class="price theme-color">$ 52.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>

                                        <li class="mb-0">
                                            <div class="offer-product">
                                                <a href="product-left-thumbnail.html" class="offer-image">
                                                    <img src="../frontend/assets/images/vegetable/product/26.png"
                                                        class="blur-up lazyload" alt="">
                                                </a>

                                                <div class="offer-detail">
                                                    <div>
                                                        <a href="product-left-thumbnail.html" class="text-title">
                                                            <h6 class="name">Apple Red Premium Imported</h6>
                                                        </a>
                                                        <span>1 KG</span>
                                                        <h6 class="price theme-color">$ 80.00</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="section-t-space">
                                <div class="category-menu">
                                    <h3>Customer Comment</h3>

                                    <div class="review-box">
                                        <div class="review-contain">
                                            <h5 class="w-75">We Care About Our Customer Experience</h5>
                                            <p>In publishing and graphic design, Lorem ipsum is a placeholder text commonly
                                                used to demonstrate the visual form of a document or a typeface without
                                                relying on meaningful content.</p>
                                        </div>

                                        <div class="review-profile">
                                            <div class="review-image">
                                                <img src="../frontend/assets/images/vegetable/review/1.jpg"
                                                    class="img-fluid blur-up lazyload" alt="">
                                            </div>
                                            <div class="review-detail">
                                                <h5>Tina Mcdonnale</h5>
                                                <h6>Sale Manager</h6>
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
                            <div class="timing-box">
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
                            </div>
                        </div>

                        <div class="section-b-space">
                            <div class="product-border overflow-hidden">
                                <div class="container">
                                    @foreach ($topViewedProducts->chunk(4) as $chunk)
                                        <div class="row">
                                            @foreach ($chunk as $product)
                                                <div class="col-md-3 col-sm-6 col-12 mb-4">
                                                    <div class="product-box" style="position: relative;">
                                                         <div class="label-tagg">
                                                            <span>HOT</span>
                                                        </div>
                                                        <div class="product-image">
                                                            <a href="#">
                                                                <img src="{{ asset('frontend/assets/images/vegetable/product/' . $product->image) }}" alt="{{ $product->name }}">
                                                            </a>
                                                            <ul class="product-option">
                                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                                    <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
                                                                        <i data-feather="eye"></i>
                                                                    </a>
                                                                </li>
                                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                                                    <a href="{{ url('compare') }}"><i data-feather="refresh-cw"></i></a>
                                                                </li>
                                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                                                    <a href="{{ url('wishlist') }}" class="notifi-wishlist"><i data-feather="heart"></i></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="product-detail">
                                                            <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                                <h6 class="name">{{ $product->name }}</h6>
                                                            </a>

                                                            <h5 class="sold text-content">
                                                                <span class="theme-color price">{{ number_format($product->price) }}₫</span>
                                                            </h5>
                                                            <div class="product-rating mt-sm-2 mt-1">
                                                                <ul class="rating">
                                                                    <li><i data-feather="star" class="fill"></i></li>
                                                                    <li><i data-feather="star" class="fill"></i></li>
                                                                    <li><i data-feather="star" class="fill"></i></li>
                                                                    <li><i data-feather="star" class="fill"></i></li>
                                                                    <li><i data-feather="star"></i></li>
                                                                </ul>
                                                                <h6 class="theme-color">In Stock</h6>
                                                            </div>
                                                            <div class="add-to-cart-box">
                                                                <button class="btn btn-add-cart addcart-button">Add
                                                                    <span class="add-icon"><i class="fa-solid fa-plus"></i></span>
                                                                </button>
                                                                <div class="cart_qty qty-box">
                                                                    <div class="input-group">
                                                                        <button type="button" class="qty-left-minus" data-type="minus">
                                                                            <i class="fa fa-minus" aria-hidden="true"></i>
                                                                        </button>
                                                                        <input class="form-control input-number qty-input" type="text" value="0">
                                                                        <button type="button" class="qty-right-plus" data-type="plus">
                                                                            <i class="fa fa-plus" aria-hidden="true"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> <!-- .product-box -->
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="title">
                            <h2>Sản phẩm theo danh mục</h2>
                            <span class="title-leaf">
                                <svg class="icon-width">
                                    <use xlink:href="{{ asset('frontend/assets/svg/leaf.svg#leaf') }}"></use>
                                </svg>
                            </span>
                            <p>Khám phá đa dạng đặc sản theo từng vùng miền.</p>
                        </div>

                        <div class="category-slider-2 product-wrapper no-arrow">
                            @forelse ($categories as $category)
                                <div>
                                    <a href="#" class="category-box category-dark">
                                        <div>
                                            <img src="{{ asset('frontend/assets/svg/1/' . $category->image) }}" alt="{{ $category->name }}">
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
                                        <img src="../frontend/assets/images/vegetable/banner/9.jpg" class="bg-img blur-up lazyload" alt="">
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
                                        <img src="../frontend/assets/images/vegetable/banner/10.jpg" class="bg-img blur-up lazyload" alt="">
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
                                        <div class="col-6 col-md-3 mb-4"> {{-- 4 sản phẩm trên 1 hàng (12/3=4) --}}
                                            <div class="product-box">
                                                <div class="label-tag"><span>NEW</span></div>
                                                <div class="product-image">
                                                    <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                        <img src="{{ asset('frontend/assets/images/vegetable/product/' . $product->image) }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
                                                    </a>
                                                    <ul class="product-option">
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
                                                                <i data-feather="eye"></i>
                                                            </a>
                                                        </li>
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                                            <a href="{{ url('compare') }}">
                                                                <i data-feather="refresh-cw"></i>
                                                            </a>
                                                        </li>
                                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                                            <a href="{{ url('wishlist') }}" class="notifi-wishlist">
                                                                <i data-feather="heart"></i>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="product-detail">
                                                    <a href="{{ route('product.detail', ['slug' => $product->slug]) }}">
                                                        <h6 class="name h-100">{{ $product->name }}</h6>
                                                    </a>
                                                    <h5 class="sold text-content">
                                                        <span class="theme-color price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                                                        @if($product->old_price)
                                                            <del>{{ number_format($product->old_price, 0, ',', '.') }}₫</del>
                                                        @endif
                                                    </h5>
                                                    <div class="product-rating mt-sm-2 mt-1">
                                                        <ul class="rating">
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                <li>
                                                                    <i data-feather="star" class="{{ $i <= $product->rating ? 'fill' : '' }}"></i>
                                                                </li>
                                                            @endfor
                                                        </ul>
                                                        <h6 class="theme-color">{{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}</h6>
                                                    </div>
                                                    <div class="add-to-cart-box">
                                                        <button class="btn btn-add-cart addcart-button">Add
                                                            <span class="add-icon">
                                                                <i class="fa-solid fa-plus"></i>
                                                            </span>
                                                        </button>
                                                        <div class="cart_qty qty-box">
                                                            <div class="input-group">
                                                                <button type="button" class="qty-left-minus" data-type="minus" data-field="">
                                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                                </button>
                                                                <input class="form-control input-number qty-input" type="text" name="quantity" value="0">
                                                                <button type="button" class="qty-right-plus" data-type="plus" data-field="">
                                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div> <!-- .product-box -->
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>



                        <div class="section-t-space">
                            <div class="banner-contain">
                                <img src="../frontend/assets/images/vegetable/banner/15.jpg" class="bg-img blur-up lazyload" alt="">
                                <div class="banner-details p-center p-4 text-white text-center">
                                    <div>
                                        <h3 class="lh-base fw-bold offer-text">Nhận ngay 30.000đ hoàn tiền! Cho đơn hàng từ 300.000đ</h3>
                                        <h6 class="coupon-code">Dùng mã: QUAQUE30</h6>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="section-t-space section-b-space">
                            <div class="row g-md-4 g-3">
                                <div class="col-xxl-8 col-xl-12 col-md-7">
                                    <div class="banner-contain hover-effect">
                                        <img src="../frontend/assets/images/vegetable/banner/12.jpg" class="bg-img blur-up lazyload"
                                            alt="">
                                        <div class="banner-details p-center-left p-4">
                                            <div>
                                                <h2 class="text-kaushan fw-normal theme-color">Get Ready To</h2>
                                                <h3 class="mt-2 mb-3">TAKE ON THE DAY!</h3>
                                                <p class="text-content banner-text">In publishing and graphic design, Lorem
                                                    ipsum is a placeholder text commonly used to demonstrate.</p>
                                                <button onclick="location.href = 'shop-left-sidebar.html';"
                                                    class="btn btn-animation btn-sm mend-auto">Shop Now <i
                                                        class="fa-solid fa-arrow-right icon"></i></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-xxl-4 col-xl-12 col-md-5">
                                    <a href="shop-left-sidebar.html" class="banner-contain hover-effect h-100">
                                        <img src="../frontend/assets/images/vegetable/banner/13.jpg" class="bg-img blur-up lazyload"
                                            alt="">
                                        <div class="banner-details p-center-left p-4 h-100">
                                            <div>
                                                <h2 class="text-kaushan fw-normal text-danger">20% Off</h2>
                                                <h3 class="mt-2 mb-2 theme-color">SUMMRY</h3>
                                                <h3 class="fw-normal product-name text-title">Product</h3>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="title d-block">
                            <div>
                                <h2>Our best Seller</h2>
                                <span class="title-leaf">
                                    <svg class="icon-width">
                                        <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                                    </svg>
                                </span>
                                <p>A virtual assistant collects the products from your list</p>
                            </div>
                        </div>

                        <div class="best-selling-slider product-wrapper wow fadeInUp">
                            <div>
                                <ul class="product-list">
                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/11.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Tuffets Whole Wheat Bread</h6>
                                                    </a>
                                                    <span>500 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/12.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Potato</h6>
                                                    </a>
                                                    <span>500 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/13.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Green Chilli</h6>
                                                    </a>
                                                    <span>200 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/14.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Muffets Burger Bun</h6>
                                                    </a>
                                                    <span>150 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <ul class="product-list">
                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/15.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Tuffets Britannia Cheezza</h6>
                                                    </a>
                                                    <span>500 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/16.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Long Life Toned Milk</h6>
                                                    </a>
                                                    <span>1 L</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/17.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Organic Tomato</h6>
                                                    </a>
                                                    <span>1 KG</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/18.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Organic Jam</h6>
                                                    </a>
                                                    <span>150 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div>
                                <ul class="product-list">
                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/19.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Good Life Refined Sunflower Oil</h6>
                                                    </a>
                                                    <span>1 L</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/20.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Good Life Raw Peanuts</h6>
                                                    </a>
                                                    <span>500 G</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/21.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">TufBest Farms Moong Dal</h6>
                                                    </a>
                                                    <span>1 KG</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../frontend/assets/images/vegetable/product/22.png"
                                                    class="blur-up lazyload" alt="">
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html" class="text-title">
                                                        <h6 class="name">Frooti Mango Drink</h6>
                                                    </a>
                                                    <span>160 ML</span>
                                                    <h6 class="price theme-color">$ 10.00</h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="section-t-space">
                            <div class="banner-contain hover-effect">
                                <img src="../frontend/assets/images/vegetable/banner/14.jpg" class="bg-img blur-up lazyload" alt="">
                                <div class="banner-details p-center banner-b-space w-100 text-center">
                                    <div>
                                        <h6 class="ls-expanded theme-color mb-sm-3 mb-1">SUMMER</h6>
                                        <h2 class="banner-title">VEGETABLE</h2>
                                        <h5 class="lh-sm mx-auto mt-1 text-content">Save up to 5% OFF</h5>
                                        <button onclick="location.href = 'shop-left-sidebar.html';"
                                            class="btn btn-animation btn-sm mx-auto mt-sm-3 mt-2">Shop Now <i
                                                class="fa-solid fa-arrow-right icon"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="title section-t-space">
                            <h2>Featured Blog</h2>
                            <span class="title-leaf">
                                <svg class="icon-width">
                                    <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                                </svg>
                            </span>
                            <p>A virtual assistant collects the products from your list</p>
                        </div>

                        <div class="slider-3-blog ratio_65 no-arrow product-wrapper">
                            <div>
                                <div class="blog-box">
                                    <div class="blog-box-image">
                                        <a href="blog-detail.html" class="blog-image">
                                            <img src="../frontend/assets/images/vegetable/blog/1.jpg" class="bg-img blur-up lazyload"
                                                alt="">
                                        </a>
                                    </div>

                                    <a href="blog-detail.html" class="blog-detail">
                                        <h6>20 March, 2022</h6>
                                        <h5>Fresh Vegetable Online</h5>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <div class="blog-box">
                                    <div class="blog-box-image">
                                        <a href="blog-detail.html" class="blog-image">
                                            <img src="../frontend/assets/images/vegetable/blog/2.jpg" class="bg-img blur-up lazyload"
                                                alt="">
                                        </a>
                                    </div>

                                    <a href="blog-detail.html" class="blog-detail">
                                        <h6>10 April, 2022</h6>
                                        <h5>Fresh Combo Fruit</h5>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <div class="blog-box">
                                    <div class="blog-box-image">
                                        <a href="blog-detail.html" class="blog-image">
                                            <img src="../frontend/assets/images/vegetable/blog/3.jpg" class="bg-img blur-up lazyload"
                                                alt="">
                                        </a>
                                    </div>

                                    <a href="blog-detail.html" class="blog-detail">
                                        <h6>10 April, 2022</h6>
                                        <h5>Nuts to Eat for Better Health</h5>
                                    </a>
                                </div>
                            </div>

                            <div>
                                <div class="blog-box">
                                    <div class="blog-box-image">
                                        <a href="blog-detail.html" class="blog-image">
                                            <img src="../frontend/assets/images/vegetable/blog/1.jpg" class="bg-img blur-up lazyload"
                                                alt="">
                                        </a>
                                    </div>

                                    <a href="blog-detail.html" class="blog-detail">
                                        <h6>20 March, 2022</h6>
                                        <h5>Fresh Vegetable Online</h5>
                                    </a>
                                </div>
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
                                        <h2>Join our newsletter and get...</h2>
                                        <h5>$20 discount for your first order</h5>
                                        <div class="input-box">
                                            <input type="email" class="form-control" id="exampleFormControlInput1"
                                                placeholder="Enter Your Email">
                                            <i class="fa-solid fa-envelope arrow"></i>
                                            <button class="sub-btn  btn-animation">
                                                <span class="d-sm-block d-none">Subscribe</span>
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
@endsection