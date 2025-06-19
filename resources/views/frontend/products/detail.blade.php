<!-- <pre>{{ var_dump($product) }}</pre> -->
@extends('layouts.frontend') {{-- Đổi lại đúng layout nếu khác --}}

@section('title', $product->name)

@section('contents')

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

                            @if(isset($product->category))
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
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="product-left-box">
                            <div class="row g-2">
                                <!-- Ảnh sản phẩm lớn -->
                                <div class="col-xxl-10 col-lg-12 col-md-10 order-xxl-2 order-lg-1 order-md-2">
                                    <div class="product-main-2 no-arrow">
                                        {{-- 1. Ảnh đại diện sản phẩm --}}
                                        @if($product->image)
                                        <div>
                                            <div class="slider-image">
                                                <img
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    id="img-main"
                                                    data-zoom-image="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid image_zoom_cls-main blur-up lazyload"
                                                    alt="{{ $product->name }} ảnh đại diện" />
                                            </div>
                                        </div>
                                        @endif

                                        {{-- 2. Ảnh album phụ --}}
                                        @foreach($product->images as $index => $img)
                                        <div>
                                            <div class="slider-image">
                                                <img
                                                    src="{{ asset('storage/' . $img->image_url) }}"
                                                    id="img-{{ $index }}"
                                                    data-zoom-image="{{ asset('storage/' . $img->image_url) }}"
                                                    class="img-fluid image_zoom_cls-{{ $index }} blur-up lazyload"
                                                    alt="{{ $product->name }} - ảnh phụ {{ $index+1 }}" />
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- 3. Ảnh biến thể (nếu có) --}}
                                        @if(isset($variants))
                                        @foreach($variants as $variant)
                                        @if($variant->image)
                                        <div>
                                            <div class="slider-image">
                                                <img
                                                    src="{{ asset('storage/' . $variant->image) }}"
                                                    id="img-variant-{{ $variant->id }}"
                                                    data-zoom-image="{{ asset('storage/' . $variant->image) }}"
                                                    class="img-fluid image_zoom_cls-variant-{{ $variant->id }} blur-up lazyload"
                                                    alt="{{ $product->name }} - {{ $variant->name }}" />
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                                <!-- Slider ảnh nhỏ (thumbnail) -->
                                <div class="col-xxl-2 col-lg-12 col-md-2 order-xxl-1 order-lg-2 order-md-1">
                                    <div class="left-slider-image-2 left-slider no-arrow slick-top">
                                        {{-- 1. Thumbnail đại diện --}}
                                        @if($product->image)
                                        <div>
                                            <div class="sidebar-image">
                                                <img
                                                    src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }} thumb đại diện" />
                                            </div>
                                        </div>
                                        @endif

                                        {{-- 2. Thumbnail ảnh phụ --}}
                                        @foreach($product->images as $index => $img)
                                        <div>
                                            <div class="sidebar-image">
                                                <img
                                                    src="{{ asset('storage/' . $img->image_url) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }} thumb {{ $index+1 }}" />
                                            </div>
                                        </div>
                                        @endforeach

                                        {{-- 3. Thumbnail variant --}}
                                        @if(isset($variants))
                                        @foreach($variants as $variant)
                                        @if($variant->image)
                                        <div>
                                            <div class="sidebar-image">
                                                <img
                                                    src="{{ asset('storage/' . $variant->image) }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="{{ $product->name }} thumb {{ $variant->name }}" />
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div
                        class="col-xl-6 wow fadeInUp"
                        data-wow-delay="0.1s">
                        <div class="right-box-contain">
                            {{-- -<h6 class="offer-top">30% Off</h6>--}}
                            <h2 class="name">Creamy Chocolate Cake</h2>
                            <div class="price-rating">
                                <h3 class="theme-color price">
                                    $49.50
                                   {{-- <del class="text-content">$58.46</del>
                                    <span class="offer theme-color">(8% off)</span> --}}
                                </h3>
                                <div class="product-rating custom-rate">
                                    <ul class="rating">
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star"></i>
                                        </li>
                                    </ul>
                                    <span class="review">số đánh giá</span>
                                </div>
                            </div>
                            <div class="product-packege">
                                <div class="product-title">
                                    <h4>Weight</h4>
                                </div>
                                <ul class="select-packege">
                                    <li>
                                        <a
                                            href="javascript:void(0)"
                                            class="active">1/2 KG</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">1 KG</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">1.5 KG</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">Red Roses</a>
                                    </li>
                                    <li>
                                        <a href="javascript:void(0)">With Pink Roses</a>
                                    </li>
                                </ul>
                            </div>

                            {{-- <div
                                class="time deal-timer product-deal-timer mx-md-0 mx-auto"
                                id="clockdiv-1"
                                data-hours="1"
                                data-minutes="2"
                                data-seconds="3">
                                <div class="product-title">
                                    <h4>Hurry up! Sales Ends In</h4>
                                </div>
                                <ul>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="days d-block">
                                                <h5></h5>
                                            </div>
                                            <h6>Days</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div class="hours d-block">
                                                <h5></h5>
                                            </div>
                                            <h6>Hours</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div
                                                class="minutes d-block">
                                                <h5></h5>
                                            </div>
                                            <h6>Min</h6>
                                        </div>
                                    </li>
                                    <li>
                                        <div class="counter d-block">
                                            <div
                                                class="seconds d-block">
                                                <h5></h5>
                                            </div>
                                            <h6>Sec</h6>
                                        </div>
                                    </li>
                                </ul>
                            </div>--}}

                            <div class="note-box product-packege">
                                <div
                                    class="cart_qty qty-box product-qty">
                                    <div class="input-group">
                                        <button
                                            type="button"
                                            class="qty-right-plus"
                                            data-type="plus"
                                            data-field="">
                                            <i
                                                class="fa fa-plus"
                                                aria-hidden="true"></i>
                                        </button>
                                        <input
                                            class="form-control input-number qty-input"
                                            type="text"
                                            name="quantity"
                                            value="0" />
                                        <button
                                            type="button"
                                            class="qty-left-minus"
                                            data-type="minus"
                                            data-field="">
                                            <i
                                                class="fa fa-minus"
                                                aria-hidden="true"></i>
                                        </button>
                                    </div>
                                </div>

                                <button
                                    onclick="location.href = 'cart.html';"
                                    class="btn btn-md bg-dark cart-button text-white w-100">
                                    Add To Cart
                                </button>
                            </div>

                            <div class="buy-box">
                                <a href="wishlist.html">
                                    <i data-feather="heart"></i>
                                    <span>Add To Wishlist</span>
                                </a>

                                <a href="compare.html">
                                    <i data-feather="shuffle"></i>
                                    <span>Add To Compare</span>
                                </a>
                            </div>

                            <div class="pickup-box">
                                <div class="product-info">
                                    <ul
                                        class="product-info-list product-info-list-2">
                                        <li>
                                            SKU :
                                            <a href="javascript:void(0)">(thay đổi theo từng biến thể)</a>
                                        </li>
                                        <li>
                                            Stock :
                                            <a href="javascript:void(0)">(thay đổi theo từng biến thể)</a>
                                        </li>
                                        <li>
                                            Tags :
                                            <a href="javascript:void(0)">(danh mục)</a>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12">
                        <div class="product-section-box">
                            <ul
                                class="nav nav-tabs custom-nav"
                                id="myTab"
                                role="tablist">
                                <li
                                    class="nav-item"
                                    role="presentation">
                                    <button
                                        class="nav-link active"
                                        id="description-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#description"
                                        type="button"
                                        role="tab"
                                        aria-controls="description"
                                        aria-selected="true">
                                        Mô tả chung (theo bảng Products)
                                    </button>
                                </li>

                                <li
                                    class="nav-item"
                                    role="presentation">
                                    <button
                                        class="nav-link"
                                        id="info-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#info"
                                        type="button"
                                        role="tab"
                                        aria-controls="info"
                                        aria-selected="false">
                                        Mô tả biến thể (được chọn)
                                    </button>
                                </li>

                                <li
                                    class="nav-item"
                                    role="presentation">
                                    <button
                                        class="nav-link"
                                        id="review-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#review"
                                        type="button"
                                        role="tab"
                                        aria-controls="review"
                                        aria-selected="false">
                                        Review
                                    </button>
                                </li>
                            </ul>

                            <div
                                class="tab-content custom-tab"
                                id="myTabContent">
                                <div
                                    class="tab-pane fade show active"
                                    id="description"
                                    role="tabpanel"
                                    aria-labelledby="description-tab">
                                    <div class="product-description">
                                        Mô tả sản phẩm đã có cke
                                    </div>
                                </div>

                                <div
                                    class="tab-pane fade"
                                    id="info"
                                    role="tabpanel"
                                    aria-labelledby="info-tab">
                                    <div class="table-responsive">
                                        Mô tả của biến thể được chọn đã có cke
                                    </div>
                                </div>

                                <div
                                    class="tab-pane fade"
                                    id="review"
                                    role="tabpanel"
                                    aria-labelledby="review-tab">
                                    <div class="review-box">
                                        <div class="row g-4">
                                            <div class="col-xl-6">
                                                <div
                                                    class="review-title">
                                                    <h4 class="fw-500">
                                                        Customer reviews
                                                    </h4>
                                                </div>

                                                <div class="d-flex">
                                                    <div
                                                        class="product-rating">
                                                        <ul
                                                            class="rating">
                                                            <li>
                                                                <i
                                                                    data-feather="star"
                                                                    class="fill"></i>
                                                            </li>
                                                            <li>
                                                                <i
                                                                    data-feather="star"
                                                                    class="fill"></i>
                                                            </li>
                                                            <li>
                                                                <i
                                                                    data-feather="star"
                                                                    class="fill"></i>
                                                            </li>
                                                            <li>
                                                                <i
                                                                    data-feather="star"></i>
                                                            </li>
                                                            <li>
                                                                <i
                                                                    data-feather="star"></i>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <h6 class="ms-3">
                                                        4.2 Out Of 5
                                                    </h6>
                                                </div>

                                                <div class="rating-box">
                                                    <ul>
                                                        <li>
                                                            <div
                                                                class="rating-list">
                                                                <h5>
                                                                    5
                                                                    Star
                                                                </h5>
                                                                <div
                                                                    class="progress">
                                                                    <div
                                                                        class="progress-bar"
                                                                        role="progressbar"
                                                                        style="
                                                                                    width: 68%;
                                                                                "
                                                                        aria-valuenow="100"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        68%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <div
                                                                class="rating-list">
                                                                <h5>
                                                                    4
                                                                    Star
                                                                </h5>
                                                                <div
                                                                    class="progress">
                                                                    <div
                                                                        class="progress-bar"
                                                                        role="progressbar"
                                                                        style="
                                                                                    width: 67%;
                                                                                "
                                                                        aria-valuenow="100"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        67%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <div
                                                                class="rating-list">
                                                                <h5>
                                                                    3
                                                                    Star
                                                                </h5>
                                                                <div
                                                                    class="progress">
                                                                    <div
                                                                        class="progress-bar"
                                                                        role="progressbar"
                                                                        style="
                                                                                    width: 42%;
                                                                                "
                                                                        aria-valuenow="100"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        42%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <div
                                                                class="rating-list">
                                                                <h5>
                                                                    2
                                                                    Star
                                                                </h5>
                                                                <div
                                                                    class="progress">
                                                                    <div
                                                                        class="progress-bar"
                                                                        role="progressbar"
                                                                        style="
                                                                                    width: 30%;
                                                                                "
                                                                        aria-valuenow="100"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        30%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li>
                                                            <div
                                                                class="rating-list">
                                                                <h5>
                                                                    1
                                                                    Star
                                                                </h5>
                                                                <div
                                                                    class="progress">
                                                                    <div
                                                                        class="progress-bar"
                                                                        role="progressbar"
                                                                        style="
                                                                                    width: 24%;
                                                                                "
                                                                        aria-valuenow="100"
                                                                        aria-valuemin="0"
                                                                        aria-valuemax="100">
                                                                        24%
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="col-xl-6">
                                                <div
                                                    class="review-title">
                                                    <h4 class="fw-500">
                                                        Add a review
                                                    </h4>
                                                </div>

                                                <div class="row g-4">
                                                    <div
                                                        class="col-md-6">
                                                        <div
                                                            class="form-floating theme-form-floating">
                                                            <input
                                                                type="text"
                                                                class="form-control"
                                                                id="name"
                                                                placeholder="Name" />
                                                            <label
                                                                for="name">Your
                                                                Name</label>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="col-md-6">
                                                        <div
                                                            class="form-floating theme-form-floating">
                                                            <input
                                                                type="email"
                                                                class="form-control"
                                                                id="email"
                                                                placeholder="Email Address" />
                                                            <label
                                                                for="email">Email
                                                                Address</label>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="col-md-6">
                                                        <div
                                                            class="form-floating theme-form-floating">
                                                            <input
                                                                type="url"
                                                                class="form-control"
                                                                id="website"
                                                                placeholder="Website" />
                                                            <label
                                                                for="website">Website</label>
                                                        </div>
                                                    </div>

                                                    <div
                                                        class="col-md-6">
                                                        <div
                                                            class="form-floating theme-form-floating">
                                                            <input
                                                                type="url"
                                                                class="form-control"
                                                                id="review1"
                                                                placeholder="Give your review a title" />
                                                            <label
                                                                for="review1">Review
                                                                Title</label>
                                                        </div>
                                                    </div>

                                                    <div class="col-12">
                                                        <div
                                                            class="form-floating theme-form-floating">
                                                            <textarea
                                                                class="form-control"
                                                                placeholder="Leave a comment here"
                                                                id="floatingTextarea2"
                                                                style="
                                                                            height: 150px;
                                                                        "></textarea>
                                                            <label
                                                                for="floatingTextarea2">Write
                                                                Your
                                                                Comment</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-12">
                                                <div
                                                    class="review-title">
                                                    <h4 class="fw-500">
                                                        Customer
                                                        questions &
                                                        answers
                                                    </h4>
                                                </div>

                                                <div
                                                    class="review-people">
                                                    <ul
                                                        class="review-list">
                                                        <li>
                                                            <div
                                                                class="people-box">
                                                                <div>
                                                                    <div
                                                                        class="people-image">
                                                                        <img
                                                                            src="../assets/images/review/1.jpg"
                                                                            class="img-fluid blur-up lazyload"
                                                                            alt="" />
                                                                    </div>
                                                                </div>

                                                                <div
                                                                    class="people-comment">
                                                                    <a
                                                                        class="name"
                                                                        href="javascript:void(0)">Tracey</a>
                                                                    <div
                                                                        class="date-time">
                                                                        <h6
                                                                            class="text-content">
                                                                            14
                                                                            Jan,
                                                                            2022
                                                                            at
                                                                            12.58
                                                                            AM
                                                                        </h6>

                                                                        <div
                                                                            class="product-rating">
                                                                            <ul
                                                                                class="rating">
                                                                                <li>
                                                                                    <i
                                                                                        data-feather="star"
                                                                                        class="fill"></i>
                                                                                </li>
                                                                                <li>
                                                                                    <i
                                                                                        data-feather="star"
                                                                                        class="fill"></i>
                                                                                </li>
                                                                                <li>
                                                                                    <i
                                                                                        data-feather="star"
                                                                                        class="fill"></i>
                                                                                </li>
                                                                                <li>
                                                                                    <i
                                                                                        data-feather="star"></i>
                                                                                </li>
                                                                                <li>
                                                                                    <i
                                                                                        data-feather="star"></i>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <div
                                                                        class="reply">
                                                                        <p>
                                                                            Icing
                                                                            <a
                                                                                href="javascript:void(0)">Reply</a>
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div
                class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                <div class="right-sidebar-box">
                    <div class="vendor-box">
                        <div class="verndor-contain">
                            <div class="vendor-image">
                                <img
                                    src="../assets/images/product/vendor.png"
                                    class="blur-up lazyload"
                                    alt="" />
                            </div>

                            <div class="vendor-name">
                                <h5 class="fw-500">Noodles Co.</h5>

                                <div class="product-rating mt-1">
                                    <ul class="rating">
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i
                                                data-feather="star"
                                                class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star"></i>
                                        </li>
                                    </ul>
                                    <span>(36 Reviews)</span>
                                </div>
                            </div>
                        </div>

                        <p class="vendor-detail">
                            Noodles
                        </p>

                        <div class="vendor-list">
                            <ul>
                                <li>
                                    <div class="address-contact">
                                        <i data-feather="map-pin"></i>
                                        <h5>
                                            Address:
                                            <span class="text-content">1288 Franklin
                                                Avenue</span>
                                        </h5>
                                    </div>
                                </li>

                                <li>
                                    <div class="address-contact">
                                        <i
                                            data-feather="headphones"></i>
                                        <h5>
                                            Contact Seller:
                                            <span class="text-content">(+1)-123-456-789</span>
                                        </h5>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Trending Product -->
                    <div class="pt-25">
                        <div class="category-menu">
                            <h3>Trending Products</h3>

                            <ul
                                class="product-list product-right-sidebar border-0 p-0">
                                <li>
                                    <div class="offer-product">
                                        <a
                                            href="product-left-thumbnail.html"
                                            class="offer-image">
                                            <img
                                                src="../assets/images/vegetable/product/23.png"
                                                class="img-fluid blur-up lazyload"
                                                alt="" />
                                        </a>

                                        <div class="offer-detail">
                                            <div>
                                                <a
                                                    href="product-left-thumbnail.html">
                                                    <h6 class="name">
                                                        Meatigo Premium
                                                        Goat Curry
                                                    </h6>
                                                </a>
                                                <span>450 G</span>
                                                <h6
                                                    class="price theme-color">
                                                    $ 70.00
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="offer-product">
                                        <a
                                            href="product-left-thumbnail.html"
                                            class="offer-image">
                                            <img
                                                src="../assets/images/vegetable/product/24.png"
                                                class="blur-up lazyload"
                                                alt="" />
                                        </a>

                                        <div class="offer-detail">
                                            <div>
                                                <a
                                                    href="product-left-thumbnail.html">
                                                    <h6 class="name">
                                                        Dates Medjoul
                                                        Premium Imported
                                                    </h6>
                                                </a>
                                                <span>450 G</span>
                                                <h6
                                                    class="price theme-color">
                                                    $ 40.00
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="offer-product">
                                        <a
                                            href="product-left-thumbnail.html"
                                            class="offer-image">
                                            <img
                                                src="../assets/images/vegetable/product/25.png"
                                                class="blur-up lazyload"
                                                alt="" />
                                        </a>

                                        <div class="offer-detail">
                                            <div>
                                                <a
                                                    href="product-left-thumbnail.html">
                                                    <h6 class="name">
                                                        Good Life Walnut
                                                        Kernels
                                                    </h6>
                                                </a>
                                                <span>200 G</span>
                                                <h6
                                                    class="price theme-color">
                                                    $ 52.00
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </li>

                                <li class="mb-0">
                                    <div class="offer-product">
                                        <a
                                            href="product-left-thumbnail.html"
                                            class="offer-image">
                                            <img
                                                src="../assets/images/vegetable/product/26.png"
                                                class="blur-up lazyload"
                                                alt="" />
                                        </a>

                                        <div class="offer-detail">
                                            <div>
                                                <a
                                                    href="product-left-thumbnail.html">
                                                    <h6 class="name">
                                                        Apple Red
                                                        Premium Imported
                                                    </h6>
                                                </a>
                                                <span>1 KG</span>
                                                <h6
                                                    class="price theme-color">
                                                    $ 80.00
                                                </h6>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Banner Section -->
                    <div class="ratio_156 pt-25">
                        <div class="home-contain">
                            <img
                                src="../assets/images/vegetable/banner/8.jpg"
                                class="bg-img blur-up lazyload"
                                alt="" />
                            <div
                                class="home-detail p-top-left home-p-medium">
                                <div>
                                    <h6 class="text-yellow home-banner">
                                        Seafood
                                    </h6>
                                    <h3
                                        class="text-uppercase fw-normal">
                                        <span
                                            class="theme-color fw-bold">Freshes</span>
                                        Products
                                    </h3>
                                    <h3 class="fw-light">every hour</h3>
                                    <button
                                        onclick="location.href = 'shop-left-sidebar.html';"
                                        class="btn btn-animation btn-md fw-bold mend-auto">
                                        Shop Now
                                        <i
                                            class="fa-solid fa-arrow-right icon"></i>
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
<!-- Product Left Sidebar End -->

<!-- Releted Product Section Start -->
<section class="product-list-section section-b-space">
    <div class="container-fluid-lg">
        <div class="title">
            <h2>Related Products</h2>
            <span class="title-leaf">
                <svg class="icon-width">
                    <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                </svg>
            </span>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="slider-6_1 product-wrapper">
                    <div>
                        <div class="product-box-3 wow fadeInUp">
                            <div class="product-header">
                                <div class="product-image">
                                    <a href="product-left.htm">
                                        <img
                                            src="../assets/images/cake/product/11.png"
                                            class="img-fluid blur-up lazyload"
                                            alt="" />
                                    </a>

                                    <ul class="product-option">
                                        <li
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View">
                                            <a
                                                href="javascript:void(0)"
                                                data-bs-toggle="modal"
                                                data-bs-target="#view">
                                                <i
                                                    data-feather="eye"></i>
                                            </a>
                                        </li>

                                        <li
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Compare">
                                            <a href="compare.html">
                                                <i
                                                    data-feather="refresh-cw"></i>
                                            </a>
                                        </li>

                                        <li
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="Wishlist">
                                            <a
                                                href="wishlist.html"
                                                class="notifi-wishlist">
                                                <i
                                                    data-feather="heart"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </div>

                            <div class="product-footer">
                                <div class="product-detail">
                                    <span class="span-name">Cake</span>
                                    <a
                                        href="product-left-thumbnail.html">
                                        <h5 class="name">
                                            Chocolate Chip Cookies 250 g
                                        </h5>
                                    </a>
                                    <div class="product-rating mt-2">
                                        <ul class="rating">
                                            <li>
                                                <i
                                                    data-feather="star"
                                                    class="fill"></i>
                                            </li>
                                            <li>
                                                <i
                                                    data-feather="star"
                                                    class="fill"></i>
                                            </li>
                                            <li>
                                                <i
                                                    data-feather="star"
                                                    class="fill"></i>
                                            </li>
                                            <li>
                                                <i
                                                    data-feather="star"
                                                    class="fill"></i>
                                            </li>
                                            <li>
                                                <i
                                                    data-feather="star"
                                                    class="fill"></i>
                                            </li>
                                        </ul>
                                        <span>(5.0)</span>
                                    </div>
                                    <h6 class="unit">500 G</h6>
                                    <h5 class="price">
                                        <span class="theme-color">$10.25</span>
                                        <del>$12.57</del>
                                    </h5>
                                    <div
                                        class="add-to-cart-box bg-white">
                                        <button
                                            class="btn btn-add-cart addcart-button">
                                            Add
                                            <span
                                                class="add-icon bg-light-gray">
                                                <i
                                                    class="fa-solid fa-plus"></i>
                                            </span>
                                        </button>
                                        <div class="cart_qty qty-box">
                                            <div
                                                class="input-group bg-white">
                                                <button
                                                    type="button"
                                                    class="qty-left-minus bg-gray"
                                                    data-type="minus"
                                                    data-field="">
                                                    <i
                                                        class="fa fa-minus"
                                                        aria-hidden="true"></i>
                                                </button>
                                                <input
                                                    class="form-control input-number qty-input"
                                                    type="text"
                                                    name="quantity"
                                                    value="0" />
                                                <button
                                                    type="button"
                                                    class="qty-right-plus bg-gray"
                                                    data-type="plus"
                                                    data-field="">
                                                    <i
                                                        class="fa fa-plus"
                                                        aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection