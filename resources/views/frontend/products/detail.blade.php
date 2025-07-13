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

                            <form method="POST" action="{{ route('client.cart.add') }}" class="add-to-cart-form">
                                @csrf
                                <!-- Input ẩn gửi product_id -->
                                <input type="hidden" name="product_id" value="{{ $product->id }}" />

                                <div class="note-box product-packege">
                                    <div class="cart_qty qty-box product-qty">
                                        <div class="input-group">
                                            <button type="button" class="qty-left-minus" data-type="minus"
                                                data-field="">
                                                <i class="fa fa-minus" aria-hidden="true"></i>
                                            </button>
                                            <input class="form-control input-number qty-input" type="text" name="quantity" value="1" min="1" data-stock="{{ $variant->stock ?? 999999 }}" data-cart-item-id="{{ $cartItemId ?? '' }}" />
                                            <button type="button" class="qty-right-plus" data-type="plus"
                                                data-field="">
                                                <i class="fa fa-plus" aria-hidden="true"></i>
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Input ẩn lưu dữ liệu biến thể, JS sẽ cập nhật giá trị này khi người dùng chọn biến thể -->
                                    <input type="hidden" id="variant_attributes" name="variant_attributes"
                                        value="" />

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
                            </div>

                            <div class="pickup-box">

                                <div class="product-info">
                                    <ul class="product-info-list product-info-list-2">
                                        <li>SKU : <a href="javascript:void(0)" id="product-sku">—</a></li>
                                        <li>Số lượng : <a href="javascript:void(0)" id="product-stock">—</a></li>
                                        <li>Tags : <a
                                                href="javascript:void(0)">{{ $product->category->name ?? '' }}</a></li>
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
                                        Mô tả
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
                                        <section class="blog-section section-b-space">
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
                                                                        src="../assets/images/inner-page/user/2.jpg"
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
                                                                        src="../assets/images/inner-page/user/3.jpg"
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
                                                    <h3>Để lại bình luận</h3>
                                                </div>

                                                <div class="leave-comment mb-3">
                                                    <div class="col-12">
                                                        <div class="blog-input">
                                                            <textarea
                                                                class="form-control"
                                                                id="exampleFormControlTextarea1"
                                                                rows="4"
                                                                placeholder="Để lại bình luận..."></textarea>
                                                        </div>
                                                    </div>
                                                    <button
                                                        class="btn btn-animation ms-xxl-auto mt-xxl-0 mt-3 btn-md fw-bold">
                                                        Bình luận
                                                    </button>
                                                </div>
                                            </div>
                                        </section>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="review" role="tabpanel"
                                    aria-labelledby="review-tab">
                                    <div class="review-box">
                                        <div class="row g-4">
                                            <div class="col-xl-6">
                                                <div class="review-title">
                                                    <h4 class="fw-500">
                                                        Đánh giá
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
                                                                        style="width: 68%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 67%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 42%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 30%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 24%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                @if($canReview)
                                                {{-- Form đánh giá --}}

                                                <form action="{{ route('client.product.reviews.store') }}" method="POST">
                                                    @csrf
                                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                    {{-- Nếu bạn cần đánh giá theo biến thể cụ thể, thêm input hidden cho variant_id --}}

                                                    <div class="mb-3">
                                                        <label for="rating">Đánh giá sao:</label>
                                                        <select name="rating" class="form-select" required>
                                                            <option value="">Chọn số sao</option>
                                                            @for ($i = 5; $i >= 1; $i--)
                                                            <option value="{{ $i }}">{{ $i }} sao</option>
                                                            @endfor
                                                        </select>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label for="comment">Nội dung đánh giá:</label>
                                                        <textarea name="comment" class="form-control" rows="4" required placeholder="Viết đánh giá..."></textarea>
                                                    </div>

                                                    <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
                                                </form>
                                                @else
                                                <div class="alert alert-warning">
                                                    Bạn chỉ có thể đánh giá sau khi đã nhận được sản phẩm.
                                                </div>
                                                @endif
                                            </div>

                                            <div class="col-12">
                                                <div class="review-title">
                                                    <h4 class="fw-500">
                                                        Customer
                                                        questions &
                                                        answers
                                                    </h4>
                                                </div>
                                                <label for="filter_star">Lọc theo số sao:</label>
                                                <select id="filter_star" class="form-select w-auto d-inline-block">
                                                    <option value="">Tất cả</option>
                                                    @for ($i = 5; $i >= 1; $i--)
                                                    <option value="{{ $i }}">{{ $i }} sao</option>
                                                    @endfor
                                                </select>
                                                <div class="review-people" id="review-container">
                                                    <ul class="review-list">
                                                        @forelse ($reviews as $review)
                                                        <li>
                                                            <div class="people-box">
                                                                <div>
                                                                    <div class="people-image">
                                                                        <img src="{{ asset('assets/images/review/default.jpg') }}"
                                                                            class="img-fluid blur-up lazyload"
                                                                            alt="{{ $review->user->name ?? 'User' }}" />
                                                                    </div>
                                                                </div>

                                                                <div class="people-comment">
                                                                    <a class="name" href="javascript:void(0)">
                                                                        {{ $review->user->name ?? 'Người dùng' }}
                                                                    </a>

                                                                    <div class="date-time">
                                                                        <h6 class="text-content">
                                                                            {{ $review->created_at->format('d M, Y \a\t H:i') }}
                                                                        </h6>

                                                                        <div class="product-rating">
                                                                            <ul class="rating">
                                                                                @for ($i = 1; $i <= 5; $i++)
                                                                                    <li>
                                                                                    <i data-feather="star" class="{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                                        </li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>

                                            <div class="reply">
                                                <p>
                                                    {{ $review->comment }}
                                                    {{-- <a href="javascript:void(0)">Reply</a> --}}
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    </li>
                                    @empty
                                    <li>
                                        <p class="text-muted">Chưa có đánh giá nào.</p>
                                    </li>
                                    @endforelse
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
                    <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
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
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload" alt="{{ $product->name }}" />
                                    </a>
                                    <ul class="product-option">
                                        <li
                                            data-bs-toggle="tooltip"
                                            data-bs-placement="top"
                                            title="View">
                                            <a
                                                href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}" title="Xem nhanh">
                                                <i
                                                    data-feather="eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="So sánh">
                                            <a href="{{ url('compare') }}"><i
                                                    data-feather="refresh-cw"></i></a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top"
                                            title="Yêu thích">
                                            <form action="{{ route('client.wishlist.store') }}"
                                                method="POST" style="display:inline-block;">
                                                @csrf
                                                <input type="hidden" name="product_id"
                                                    value="{{ $product->id }}">
                                                <button type="submit" class="notifi-wishlist btn p-0"
                                                    style="border:none; background:none; width: 18px; height: 18px; margin-top: 10px">
                                                    <i data-feather="heart"
                                                        @if (auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) class="text-red-500" @endif></i>
                                                </button>
                                            </form>
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
                                                <li><i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i></li>
                                                @endfor
                                        </ul>
                                        <span>({{ number_format($product->reviews->avg('rating') ?? 0, 1) }})</span>
                                    </div>
                                    @php
                                    $firstVariant = $product->variants->first();
                                    @endphp

                                    <h6 class="variant-name">
                                        @if($firstVariant)
                                        {{ $firstVariant->name }}
                                        @else
                                        Không có biến thể
                                        @endif
                                    </h6>

                                    <h5 class="price">
                                        @if($firstVariant)
                                        <span class="theme-color">{{ number_format($firstVariant->price) }}₫</span>
                                        @else
                                        <span class="theme-color">Liên hệ</span>
                                        @endif
                                    </h5>
                                    {{-- <div class="add-to-cart-box bg-white">
                                        <button class="btn btn-add-cart addcart-button">
                                            Add
                                            <span class="add-icon bg-light-gray">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                        </button>
                                        <div class="cart_qty qty-box">
                                            <div class="input-group bg-white">
                                                <button type="button" class="qty-left-minus bg-gray" data-type="minus" data-field="">
                                                    <i class="fa fa-minus" aria-hidden="true"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text" name="quantity" value="0" />
                                                <button type="button" class="qty-right-plus bg-gray" data-type="plus" data-field="">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>


                                        <!-- Input ẩn lưu dữ liệu biến thể, JS sẽ cập nhật giá trị này khi người dùng chọn biến thể -->
                                        <input type="hidden" id="variant_attributes" name="variant_attributes"
                                            value="" />

                                        <button type="submit" class="btn btn-md bg-dark cart-button text-white w-100">
                                            Thêm vào giỏ hàng
                                        </button>
                                    </div>
                                </form>
                                <div class="buy-box">
                                    <a href="wishlist.html">
                                        <i data-feather="heart"></i>
                                        <span>Add To Wishlist</span>
                                    </a>
                                </div>

                                <div class="pickup-box">

                                    <div class="product-info">
                                        <ul class="product-info-list product-info-list-2">
                                            <li>SKU : <a href="javascript:void(0)" id="product-sku">—</a></li>
                                            <li>Số lượng : <a href="javascript:void(0)" id="product-stock">—</a></li>
                                            <li>Tags : <a
                                                    href="javascript:void(0)">{{ $product->category->name ?? '' }}</a></li>
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
                                        Mô tả
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
                                                        Đánh giá
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
                                                                        style="width: 68%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 67%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 42%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 30%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                                                                        style="width: 24%;" aria-valuenow="100"
                                                                        aria-valuemin="0" aria-valuemax="100">
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
                            </div> --}}
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
<div
    class="modal fade theme-modal view-modal"
    id="quickviewModal"
    tabindex="-1"
    aria-labelledby="quickviewModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header p-0">
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row g-sm-4 g-2">
                    <div class="col-lg-6">
                        <div class="slider-image">
                            <img
                                src=""
                                id="quickview-image"
                                class="img-fluid blur-up lazyload"
                                alt="Product Image" />
                        </div>
                    </div>

                    <div class="col-lg-6">
                        <div class="right-sidebar-modal">
                            <h4 class="title-name" id="quickview-name">Tên sản phẩm</h4>
                            <h4 class="price" id="quickview-price">Giá</h4>
                            <div class="product-rating">
                                <ul class="rating" id="quickview-rating">
                                    <!-- JS render stars -->
                                </ul>
                                <span class="ms-2" id="quickview-review-count">0 Reviews</span>
                            </div>

                            <div class="product-detail">
                                <h4>Mô tả sản phẩm:</h4>
                                <div id="quickview-description"></div>
                            </div>

                            <ul class="brand-list">
                                <li>
                                    <div class="brand-box">
                                        <h5>Danh mục:</h5>
                                        <h6 id="quickview-category-name"></h6>
                                    </div>
                                </li>
                            </ul>

                            <div id="quickview-attributes-container" class="select-size">
                                <!-- Thuộc tính biến thể render ở đây -->
                            </div>

                            <div class="modal-button">
                                {{-- <button
                                    id="quickview-add-to-cart"
                                    class="btn btn-md add-cart-button icon">
                                    Thêm vào giỏ hàng
                                </button> --}}
                                <button
                                    id="quickview-view-details"
                                    class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
                                    Xem chi tiết
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


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
                selected[el.getAttribute('data-attr')] = parseInt(el.getAttribute('data-value'), 10);
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
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
                    const maxStock = parseInt(qtyInput.getAttribute('data-stock'), 10) || 999999;
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
                    let attrValueIds = Object.values(selected).map(Number).sort((a, b) => a - b);

                    // Tìm biến thể trùng
                    let found = window.VARIANTS.find(v =>
                        v.value_ids.length === attrValueIds.length &&
                        v.value_ids.slice().sort((a, b) => a - b).every((id, i) => id === attrValueIds[i])
                    );

                    if (found) {
                        document.getElementById('product-sku').textContent = found.sku || 'N/A';
                        document.getElementById('product-stock').textContent = found.stock ?? 'N/A';
                        document.getElementById('product-price').textContent = found.price ? (Number(found.price).toLocaleString() + ' đ') : 'Liên hệ';
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
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('error'))
        Swal.fire({
            icon: 'error', // hoặc 'success'
            title: 'Lỗi vượt quá số lượng tồn kho',
            text: '{{ session('
            error ') }}',
            confirmButtonColor: '#0da487',
            width: 350, // giảm chiều ngang
            padding: '1rem 1.5rem', // giảm padding
            customClass: {
                popup: 'swal2-popup-small',
                title: 'swal2-title-small',
                content: 'swal2-content-small'
            }
        });
        @endif

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Thành công',
            text: '{{ session('
            success ') }}',
            confirmButtonColor: '#0da487',
            width: 350,
            padding: '1rem 1.5rem',
            customClass: {
                popup: 'swal2-popup-small',
                title: 'swal2-title-small',
                content: 'swal2-content-small'
            }
        });
        @endif
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.quickview-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const slug = this.dataset.slug;
                if (!slug) return alert('Không có slug sản phẩm.');

                const modal = document.getElementById('quickviewModal');
                if (!modal) return alert('Modal Quick View chưa tồn tại trên trang.');

                // Reset modal trước khi load dữ liệu mới
                modal.querySelector('#quickview-image').src = '';
                modal.querySelector('#quickview-name').textContent = '';
                modal.querySelector('#quickview-price').textContent = '';
                modal.querySelector('#quickview-description').innerHTML = '';
                modal.querySelector('#quickview-category-name').textContent = '';
                modal.querySelector('#quickview-review-count').textContent = '';
                modal.querySelector('#quickview-rating').innerHTML = '';

                // Mở modal Bootstrap
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                fetch(`/client/san-pham/quickview/${slug}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`Lỗi tải dữ liệu (${res.status})`);
                        return res.json();
                    })
                    .then(data => {
                        const product = data.product;

                        modal.querySelector('#quickview-image').src = product.image || '/assets/images/no-image.png';
                        modal.querySelector('#quickview-name').textContent = product.name;
                        modal.querySelector('#quickview-description').innerHTML = product.description || '';
                        modal.querySelector('#quickview-category-name').textContent = product.category_name || '';
                        modal.querySelector('#quickview-review-count').textContent = `${product.review_count} Reviews`;

                        // Biến thể đã là mảng chuẩn, lấy biến thể đầu tiên
                        const variantsArray = product.variants || [];
                        if (variantsArray.length > 0) {
                            modal.querySelector('#quickview-price').textContent = Number(variantsArray[0].price).toLocaleString() + ' đ';
                        } else {
                            modal.querySelector('#quickview-price').textContent = 'Liên hệ';
                        }

                        // Render rating sao
                        const ratingEl = modal.querySelector('#quickview-rating');
                        ratingEl.innerHTML = '';
                        for (let i = 1; i <= 5; i++) {
                            ratingEl.innerHTML += `<li><i data-feather="star" class="${i <= product.avg_rating ? 'fill' : ''}"></i></li>`;
                        }
                        feather.replace();

                        // Nút xem chi tiết chuyển đến trang chi tiết sản phẩm
                        modal.querySelector('#quickview-view-details').onclick = () => {
                            window.location.href = `/client/san-pham/${slug}`;
                        };

                        // TODO: Bạn có thể thêm xử lý chọn biến thể, cập nhật giá, thêm giỏ hàng tại đây
                    })
                    .catch(err => {
                        console.error('Lỗi tải Quick View:', err);
                        alert('Không thể tải thông tin sản phẩm, vui lòng thử lại sau.');
                        bsModal.hide();
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
</style>

@endsection