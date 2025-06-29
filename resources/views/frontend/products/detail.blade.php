<!-- <pre>{{ var_dump($product) }}</pre> -->
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
                    @php
                    $descImgs = [];
                    if (!empty($product->image)) {
                    $descImgs[] = asset('storage/' . $product->image);
                    }
                    if ($product->images && $product->images->count()) {
                    foreach ($product->images as $img) {
                    if (!empty($img->image_url)) {
                    $descImgs[] = asset('storage/' . $img->image_url);
                    }
                    }
                    }
                    // Map valueId => ảnh biến thể (nếu có)
                    $variantImages = [];
                    foreach($product->variants as $variant) {
                    if (!empty($variant->image) && !empty($variant->value_ids)) {
                    foreach($variant->value_ids as $valueId) {
                    $variantImages[$valueId] = asset('storage/'.$variant->image);
                    }
                    }
                    }
                    @endphp
                    <div class="col-xl-6 wow fadeInUp">
                        <div class="product-left-box">
                            <div class="main-image-wrapper" style="border:1px solid #ddd; border-radius:10px; padding:10px; background:#fafafa; text-align:center;">
                                <img id="mainImage" src="{{ $descImgs[0] ?? asset('backend/assets/images/placeholder.webp') }}" alt="Ảnh sản phẩm" style="width:100%; max-width:420px; height:auto; border-radius:10px; object-fit:contain;">
                            </div>
                            <div class="thumbnail-wrapper" style="display:flex; justify-content:center; gap:8px; margin-top:10px; overflow-x:auto; padding-bottom:5px;">
                                @foreach ($descImgs as $index => $img)
                                <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}" class="thumbnail-image" data-index="{{ $index }}" style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer;">
                                @endforeach
                                @if (empty($descImgs))
                                <img src="{{ asset('backend/assets/images/placeholder.webp') }}" alt="Không có ảnh" style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:default;">
                                @endif
                            </div>
                        </div>
                    </div>


                    <div
                        class="col-xl-6 wow fadeInUp"
                        data-wow-delay="0.1s">
                        <div class="right-box-contain">
                            {{-- <h6 class="offer-top">30% Off</h6> --}}
                            <h2 class="name">{{ $product->name }}</h2>
                            <div class="price-rating">
                                <h3 class="theme-color price" id="product-price">{{ number_format($product->variants[0]->price ?? 0) }} đ</h3>

                                <div class="product-rating custom-rate">
                                    <ul class="rating">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <li><i data-feather="star" class="{{ $i <= round($product->reviews->avg('rating')) ? 'fill' : '' }}"></i></li>
                                            @endfor
                                    </ul>
                                    <span class="review">{{ $product->reviews->count() }} đánh giá</span>
                                </div>
                            </div>

                            <div class="product-packege">
                                @foreach($attributes as $attrId => $attr)
                                <div class="product-title">
                                    <h4>{{ $attr['name'] }}</h4>
                                </div>
                                <ul class="select-packege">
                                    @foreach($attr['values'] as $valueId => $value)
                                    <li>
                                        <a href="javascript:void(0)"
                                            data-attr="{{ $attrId }}"
                                            data-value="{{ $valueId }}"
                                            @if(isset($variantImages[$valueId])) data-variant-image="{{ $variantImages[$valueId] }}" @endif
                                            class="attribute-select {{ (isset($defaultSelected[$attrId]) && $defaultSelected[$attrId] == $valueId) ? 'active2' : '' }}">
                                            {{ $value }}
                                        </a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endforeach
                            </div>

                            {{--<div
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
                                    Thêm vào giỏ
                                </button>
                            </div>

                            <div class="buy-box">
                                <a href="wishlist.html">
                                    <i data-feather="heart"></i>
                                    <span>Thêm vào yêu thích</span>
                                </a>
                                {{--
                                <a href="compare.html">
                                    <i data-feather="shuffle"></i>
                                    <span>Add To Compare</span>
                                </a>--}}
                            </div>

                            <div class="pickup-box">

                                <div class="product-info">
                                    <ul class="product-info-list product-info-list-2">
                                        <li>SKU : <a href="javascript:void(0)" id="product-sku">{{ $product->variants[0]->sku ?? '—' }}</a></li>
                                        <li>Trong kho còn : <a href="javascript:void(0)" id="product-stock">{{ $product->variants[0]->stock ?? '—' }}</a> sản phẩm</li>
                                        <li>Danh mục : <a href="javascript:void(0)">{{ $product->category->name ?? '' }}</a></li>
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
                                        Description
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
                                        Mô tả Biến thể
                                    </button>
                                </li>

                                <li
                                    class="nav-item"
                                    role="presentation">
                                    <button
                                        class="nav-link"
                                        id="care-tab"
                                        data-bs-toggle="tab"
                                        data-bs-target="#care"
                                        type="button"
                                        role="tab"
                                        aria-controls="care"
                                        aria-selected="false">
                                        Bình luận
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
                                <div class="tab-pane fade show active" id="description" role="tabpanel" aria-labelledby="description-tab">
                                    <div class="product-description" id="product-description">
                                        {!! $product->description !!}
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="info" role="tabpanel" aria-labelledby="info-tab">
                                    <div class="table-responsive" id="variant-description">
                                        {!! $product->variants[0]->description ?? 'Chưa có mô tả biến thể' !!}
                                    </div>
                                </div>

                                <div
                                    class="tab-pane fade"
                                    id="care"
                                    role="tabpanel"
                                    aria-labelledby="care-tab">
                                    <div class="information-box">
                                        <section class="blog-section">
                                            <div class="comment-box overflow-hidden">
                                                <div class="leave-title">
                                                    <h3>Bình luận</h3>
                                                </div>

                                                <div class="user-comment-box">
                                                    <ul>
                                                        <li>
                                                            <div class="user-box border-color">
                                                                <div class="reply-button">
                                                                    <i
                                                                        class="fa-solid fa-reply"></i>
                                                                    <span class="theme-color">Reply</span>
                                                                </div>
                                                                <div class="user-iamge">
                                                                    <img
                                                                        src="{{ asset('frontend/assets/images/inner-page/user/2.jpg') }}"
                                                                        class="img-fluid blur-up lazyload"
                                                                        alt="" />
                                                                    <div class="user-name">
                                                                        <h6>30 Jan, 2022</h6>
                                                                        <h5 class="text-content">
                                                                            Glenn Greer
                                                                        </h5>
                                                                    </div>
                                                                </div>

                                                                <div class="user-contain">
                                                                    <p>
                                                                        Nội dung bình luận
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </li>

                                                        <li class="li-padding">
                                                            <div class="user-box">
                                                                <div class="reply-button">
                                                                    <i
                                                                        class="fa-solid fa-reply"></i>
                                                                    <span class="theme-color">Reply</span>
                                                                </div>
                                                                <div class="user-iamge">
                                                                    <img
                                                                        src="{{ asset('frontend/assets/images/inner-page/user/3.jpg') }}"
                                                                        class="img-fluid blur-up lazyload"
                                                                        alt="" />
                                                                    <div class="user-name">
                                                                        <h6>30 Jan, 2022</h6>
                                                                        <h5 class="text-content">
                                                                            ALex HEKE
                                                                        </h5>
                                                                    </div>
                                                                </div>

                                                                <div class="user-contain">
                                                                    <p>
                                                                        Nội dung trả lời
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="leave-box">
                                                <div class="leave-title mt-0">
                                                    <h3>Leave Comment</h3>
                                                </div>

                                                <div class="leave-comment">
             
                                                    <div class="row g-3 mb-3">
                                                        <div class="col-12">
                                                            <div class="blog-input">
                                                                <textarea
                                                                    class="form-control"
                                                                    id="exampleFormControlTextarea1"
                                                                    rows="4"
                                                                    placeholder="Comments"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <button
                                                        class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold">
                                                        Post Comment
                                                    </button>
                                                </div>
                                            </div>
                                        </section>
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

            <div
                class="col-xxl-3 col-xl-4 col-lg-5 d-none d-lg-block wow fadeInUp">
                <div class="right-sidebar-box">
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
            <h2>Sản phẩm chung danh mục</h2>
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
    .blog-section {
        padding-top: 0px !important ;
    }
    .user-comment-box ul li{
        list-style-type: none !important;
    }
</style>
<script>
    window.VARIANTS = @json($variantMap ?? []);

    let selected = {};
    let variants = window.VARIANTS;

    // Hàm kiểm tra xem giá trị attrValueId có thuộc ít nhất 1 variant active thỏa mãn điều kiện selected hiện tại không
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
                    v.value_ids.slice().sort((a, b) => a - b).every((id, i) => id === attrValueIds[i])
                );

                if (found && Number(found.active) === 1) {
                    document.getElementById('product-sku').textContent = found.sku || 'N/A';
                    document.getElementById('product-stock').textContent = found.stock ?? 'N/A';
                    document.getElementById('product-price').textContent = found.price ? (Number(found.price).toLocaleString() + ' đ') : 'Liên hệ';
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

@endsection

@push('scripts')

@endpush