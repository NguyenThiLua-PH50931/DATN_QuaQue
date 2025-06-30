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
                                <h2 class="name">Creamy Chocolate Cake</h2>
                                <div class="price-rating">
                                    <h3 class="theme-color price" id="product-price">
                                        {{ number_format($product->variants[0]->price ?? 0) }} đ</h3>

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

                                <div class="product-packege">
                                    @foreach ($attributes as $attrId => $attr)
                                        <div class="product-title">
                                            <h4>{{ $attr['name'] }}</h4>
                                        </div>
                                        <ul class="select-packege">
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
                            </div> --}}

                                {{-- Thêm giỏ hàng --}}
                                <!-- Ví dụ trong file sản phẩm (product.blade.php hoặc trang danh sách sản phẩm) -->
                                <form action="{{ route('client.cart.add') }}" method="POST" class="add-to-cart-form">
                                    @csrf
                                    <input type="hidden" name="variant_attributes" id="variant_attributes">
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="price" id="variant-price"
                                        value="{{ $product->variants->count() > 0 ? $product->variants[0]->price : $product->price }}">



                                    <div class="note-box product-packege">
                                        <div class="cart_qty qty-box product-qty">
                                            <div class="input-group">
                                                <button type="button" class="qty-left-minus" data-type="minus"
                                                    data-field="quantity">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="number"
                                                    name="quantity" value="1" min="1"
                                                    data-stock="{{ $variantStock ?? $product->stock }}"  data-cart-item-id="{{ $product->id }}" />
                                                <button type="button" class="qty-right-plus" data-type="plus"
                                                    data-field="quantity">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-md bg-dark cart-button text-white w-100">
                                            Thêm giỏ hàng
                                        </button>
                                    </div>
                                </form>

                                <div class="buy-box">
                                    <a href="wishlist.html">
                                        <i data-feather="heart"></i>
                                        <span>Add To Wishlist</span>
                                    </a>
                                    {{--
                                <a href="compare.html">
                                    <i data-feather="shuffle"></i>
                                    <span>Add To Compare</span>
                                </a> --}}
                                </div>

                                <div class="pickup-box">

                                    <div class="product-info">
                                        <ul class="product-info-list product-info-list-2">
                                            <li>SKU : <a href="javascript:void(0)" id="product-sku">—</a></li>
                                            <li>Stock : <a href="javascript:void(0)" id="product-stock">—</a></li>
                                            <li>Tags : <a
                                                    href="javascript:void(0)">{{ $product->category->name ?? '' }}</a>
                                            </li>
                                        </ul>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="product-section-box">
                                <ul class="nav nav-tabs custom-nav" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active" id="description-tab" data-bs-toggle="tab"
                                            data-bs-target="#description" type="button" role="tab"
                                            aria-controls="description" aria-selected="true">
                                            Description
                                        </button>
                                    </li>

                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link" id="info-tab" data-bs-toggle="tab"
                                            data-bs-target="#info" type="button" role="tab" aria-controls="info"
                                            aria-selected="false">
                                            Mô tả Biến thể
                                        </button>
                                    </li>

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
                                            Review
                                        </button>
                                    </li>
                                </ul>

                                <div class="tab-content custom-tab" id="myTabContent">
                                    <div class="tab-pane fade show active" id="description" role="tabpanel"
                                        aria-labelledby="description-tab">
                                        <div class="product-description">
                                            mô tả theo cke
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="info" role="tabpanel"
                                        aria-labelledby="info-tab">
                                        <div class="table-responsive">
                                            mô tả biến thể theo cke
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="care" role="tabpanel"
                                        aria-labelledby="care-tab">
                                        <div class="information-box">
                                            bình luận
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="review" role="tabpanel"
                                        aria-labelledby="review-tab">
                                        <div class="review-box">
                                            <div class="row g-4">
                                                <div class="col-xl-6">
                                                    <div class="review-title">
                                                        <h4 class="fw-500">
                                                            Customer reviews
                                                        </h4>
                                                    </div>

                                                    <div class="d-flex">
                                                        <div class="product-rating">
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
                                                                    <i data-feather="star"></i>
                                                                </li>
                                                                <li>
                                                                    <i data-feather="star"></i>
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
                                                                <div class="rating-list">
                                                                    <h5>
                                                                        5
                                                                        Star
                                                                    </h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="
                                                                                    width: 68%;
                                                                                "
                                                                            aria-valuenow="100" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                            68%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="rating-list">
                                                                    <h5>
                                                                        4
                                                                        Star
                                                                    </h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="
                                                                                    width: 67%;
                                                                                "
                                                                            aria-valuenow="100" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                            67%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="rating-list">
                                                                    <h5>
                                                                        3
                                                                        Star
                                                                    </h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="
                                                                                    width: 42%;
                                                                                "
                                                                            aria-valuenow="100" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                            42%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="rating-list">
                                                                    <h5>
                                                                        2
                                                                        Star
                                                                    </h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="
                                                                                    width: 30%;
                                                                                "
                                                                            aria-valuenow="100" aria-valuemin="0"
                                                                            aria-valuemax="100">
                                                                            30%
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </li>

                                                            <li>
                                                                <div class="rating-list">
                                                                    <h5>
                                                                        1
                                                                        Star
                                                                    </h5>
                                                                    <div class="progress">
                                                                        <div class="progress-bar" role="progressbar"
                                                                            style="
                                                                                    width: 24%;
                                                                                "
                                                                            aria-valuenow="100" aria-valuemin="0"
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
                                                    <div class="review-title">
                                                        <h4 class="fw-500">
                                                            Add a review
                                                        </h4>
                                                    </div>

                                                    <div class="row g-4">

                                                        <div class="col-12">
                                                            <div class="form-floating theme-form-floating">
                                                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2"
                                                                    style="
                                                                            height: 150px;
                                                                        "></textarea>
                                                                <label for="floatingTextarea2">Write
                                                                    Your
                                                                    Comment</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="review-title">
                                                        <h4 class="fw-500">
                                                            Customer
                                                            questions &
                                                            answers
                                                        </h4>
                                                    </div>

                                                    <div class="review-people">
                                                        <ul class="review-list">
                                                            <li>
                                                                <div class="people-box">
                                                                    <div>
                                                                        <div class="people-image">
                                                                            <img src="../assets/images/review/1.jpg"
                                                                                class="img-fluid blur-up lazyload"
                                                                                alt="" />
                                                                        </div>
                                                                    </div>

                                                                    <div class="people-comment">
                                                                        <a class="name"
                                                                            href="javascript:void(0)">Tracey</a>
                                                                        <div class="date-time">
                                                                            <h6 class="text-content">
                                                                                14
                                                                                Jan,
                                                                                2022
                                                                                at
                                                                                12.58
                                                                                AM
                                                                            </h6>

                                                                            <div class="product-rating">
                                                                                <ul class="rating">
                                                                                    <li>
                                                                                        <i data-feather="star"
                                                                                            class="fill"></i>
                                                                                    </li>
                                                                                    <li>
                                                                                        <i data-feather="star"
                                                                                            class="fill"></i>
                                                                                    </li>
                                                                                    <li>
                                                                                        <i data-feather="star"
                                                                                            class="fill"></i>
                                                                                    </li>
                                                                                    <li>
                                                                                        <i data-feather="star"></i>
                                                                                    </li>
                                                                                    <li>
                                                                                        <i data-feather="star"></i>
                                                                                    </li>
                                                                                </ul>
                                                                            </div>
                                                                        </div>

                                                                        <div class="reply">
                                                                            <p>
                                                                                nội dung đánh giá.<a
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

                <div class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                    <div class="right-sidebar-box">
                        <div class="vendor-box">
                            <div class="verndor-contain">
                                <div class="vendor-image">
                                    <img src="../assets/images/product/vendor.png" class="blur-up lazyload"
                                        alt="" />
                                </div>

                                <div class="vendor-name">
                                    <h5 class="fw-500">Noodles Co.</h5>

                                    <div class="product-rating mt-1">
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
                                        <span>(36 Reviews)</span>
                                    </div>
                                </div>
                            </div>

                            <p class="vendor-detail">
                                Noodles & Company is an American fast-casual
                                restaurant that offers international and
                                American noodle dishes and pasta.
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
                                            <i data-feather="headphones"></i>
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

                                <ul class="product-list product-right-sidebar border-0 p-0">
                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../assets/images/vegetable/product/23.png"
                                                    class="img-fluid blur-up lazyload" alt="" />
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html">
                                                        <h6 class="name">
                                                            Meatigo Premium
                                                            Goat Curry
                                                        </h6>
                                                    </a>
                                                    <span>450 G</span>
                                                    <h6 class="price theme-color">
                                                        $ 70.00
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../assets/images/vegetable/product/24.png"
                                                    class="blur-up lazyload" alt="" />
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html">
                                                        <h6 class="name">
                                                            Dates Medjoul
                                                            Premium Imported
                                                        </h6>
                                                    </a>
                                                    <span>450 G</span>
                                                    <h6 class="price theme-color">
                                                        $ 40.00
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../assets/images/vegetable/product/25.png"
                                                    class="blur-up lazyload" alt="" />
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html">
                                                        <h6 class="name">
                                                            Good Life Walnut
                                                            Kernels
                                                        </h6>
                                                    </a>
                                                    <span>200 G</span>
                                                    <h6 class="price theme-color">
                                                        $ 52.00
                                                    </h6>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li class="mb-0">
                                        <div class="offer-product">
                                            <a href="product-left-thumbnail.html" class="offer-image">
                                                <img src="../assets/images/vegetable/product/26.png"
                                                    class="blur-up lazyload" alt="" />
                                            </a>

                                            <div class="offer-detail">
                                                <div>
                                                    <a href="product-left-thumbnail.html">
                                                        <h6 class="name">
                                                            Apple Red
                                                            Premium Imported
                                                        </h6>
                                                    </a>
                                                    <span>1 KG</span>
                                                    <h6 class="price theme-color">
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
                                            <img src="../assets/images/cake/product/11.png"
                                                class="img-fluid blur-up lazyload" alt="" />
                                        </a>

                                        <ul class="product-option">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <a href="javascript:void(0)" data-bs-toggle="modal"
                                                    data-bs-target="#view">
                                                    <i data-feather="eye"></i>
                                                </a>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Compare">
                                                <a href="compare.html">
                                                    <i data-feather="refresh-cw"></i>
                                                </a>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Wishlist">
                                                <a href="wishlist.html" class="notifi-wishlist">
                                                    <i data-feather="heart"></i>
                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <div class="product-footer">
                                    <div class="product-detail">
                                        <span class="span-name">Cake</span>
                                        <a href="product-left-thumbnail.html">
                                            <h5 class="name">
                                                Chocolate Chip Cookies 250 g
                                            </h5>
                                        </a>
                                        <div class="product-rating mt-2">
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
                                                    <i data-feather="star" class="fill"></i>
                                                </li>
                                            </ul>
                                            <span>(5.0)</span>
                                        </div>
                                        <h6 class="unit">500 G</h6>
                                        <h5 class="price">
                                            <span class="theme-color">$10.25</span>
                                            <del>$12.57</del>
                                        </h5>
                                        <div class="add-to-cart-box bg-white">
                                            <button class="btn btn-add-cart addcart-button">
                                                Add
                                                <span class="add-icon bg-light-gray">
                                                    <i class="fa-solid fa-plus"></i>
                                                </span>
                                            </button>
                                            <div class="cart_qty qty-box">
                                                {{-- <div class="input-group bg-white">
                                                    <button type="button" class="qty-left-minus bg-gray"
                                                        data-type="minus" data-field="">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </button>
                                                    <input class="form-control input-number qty-input" type="text"
                                                        name="quantity" value="0" />
                                                    <button type="button" class="qty-right-plus bg-gray"
                                                        data-type="plus" data-field="">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </div> --}}
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
    <style>
        .attribute-select.active2 {
            background: #0da386 !important;
            color: #fff !important;
            border: 1px solid #0da386 !important;
            /* tuỳ bạn muốn style thêm gì nữa thì thêm */
        }
    </style>


@endsection

@push('scripts')
    <script>
        window.VARIANTS = {!! json_encode($variantMap ?? [], JSON_HEX_TAG) !!};
    </script>

{{-- update số lương trang detail --}}
    <script>
        const updateQuantityUrl = "{{ route('client.cart.updateQuantity') }}";

        document.querySelectorAll('.qty-left-minus, .qty-right-plus').forEach(button => {
            button.addEventListener('click', function(e) {
                e.preventDefault();

                const input = button.closest('.input-group').querySelector('input[name="quantity"]');
                if (!input) return;

                let val = parseInt(input.value, 10) || 1;
                const min = 1;
                const maxStock = parseInt(input.getAttribute('data-stock'), 10) || 999999;

                const cartItemId = input.getAttribute('data-cart-item-id');
                if (!cartItemId) {
                    console.error('cart_item_id not found');
                    return;
                }

                let action;

                if (button.classList.contains('qty-right-plus')) {
                    if (val < maxStock) {
                        val++;
                        action = 'increase';
                    } else {
                        alert('Số lượng đã đạt tối đa trong kho!');
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
                            input.value = data.quantity; // cập nhật số lượng mới lên input
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


    <script>
        const updateQuantityUrl = "{{ route('client.cart.updateQuantity') }}";
    </script>


    {{-- Code chọn biên thể --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('.add-to-cart-form');
            const variantInput = document.getElementById('variant_attributes');

            // Lấy số lượng thuộc tính cần chọn (dựa vào dữ liệu attributes truyền từ backend)
            const attributesCount = Object.keys(@json($attributes)).length;

            // Biến lưu trạng thái lựa chọn
            let selected = {};

            // Hàm cập nhật input ẩn và log (bạn có thể tắt console.log khi cần)
            function updateVariantInput() {
                if (Object.keys(selected).length === 0) {
                    variantInput.value = null; // hoặc variantInput.value = '';
                } else {
                    variantInput.value = JSON.stringify(selected);
                }
                console.log('Updated variant_attributes:', variantInput.value);
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

                    // Cập nhật thông tin biến thể (giá, sku, stock) nếu chọn đủ biến thể
                    if (Object.keys(selected).length === attributesCount) {
                        let attrValueIds = Object.values(selected).map(Number).sort((a, b) => a -
                            b);
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
                        } else {
                            document.getElementById('product-sku').textContent = 'Không tồn tại';
                            document.getElementById('product-stock').textContent = 'Không tồn tại';
                            document.getElementById('product-price').textContent = 'Không tồn tại';
                            document.getElementById('variant-price').value = '';
                        }
                    } else {
                        document.getElementById('product-sku').textContent = '—';
                        document.getElementById('product-stock').textContent = '—';
                        document.getElementById('product-price').textContent = '—';
                        document.getElementById('variant-price').value = '';
                    }
                });
            });

            // Khi submit form, kiểm tra đủ biến thể chưa
            if (form) {
                form.addEventListener('submit', function(e) {
                    if (attributesCount > 0 && Object.keys(selected).length !== attributesCount) {
                        e.preventDefault();
                        alert('Vui lòng chọn đầy đủ biến thể sản phẩm trước khi thêm vào giỏ hàng.');
                        return false;
                    }
                    // Cập nhật lại input ẩn lần cuối trước khi submit
                    updateVariantInput();
                });
            }

            // Khởi tạo giá trị input ẩn ban đầu
            updateVariantInput();

            // --- Phần tăng giảm số lượng (bạn giữ nguyên nếu muốn) ---
            document.querySelectorAll('.qty-left-minus, .qty-right-plus').forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = button.closest('.input-group').querySelector(
                        'input[name="quantity"]');
                    if (!input) return;

                    let val = parseInt(input.value, 10) || 1;
                    const min = 1; // min cứng

                    if (button.classList.contains('qty-right-plus')) {
                        val += 1;
                    } else if (button.classList.contains('qty-left-minus')) {
                        val = val > min ? val - 1 : min;
                    }

                    input.value = val;
                    console.log('Quantity updated to:', val);
                });
            });
        });
    </script>
@endpush
