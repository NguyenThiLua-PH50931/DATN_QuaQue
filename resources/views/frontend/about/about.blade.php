@extends('layouts.frontend')
@section('title', 'Giới thiệu')
@section('contents')
<style>
 .reviewer-box {
    min-height: 350px;
    display: flex;
    flex-direction: column;
    padding: 20px;
    border-radius: 10px;
    background: #fff;
}

/* Tiêu đề */
.reviewer-box h5 {
    margin-bottom: 8px;
}

/* Nội dung bình luận */
.reviewer-box p {
    margin: 0 0 16px;
    max-height: 80px;
    overflow: hidden;
    display: -webkit-box;
    -webkit-line-clamp: 4;
    -webkit-box-orient: vertical;
    text-overflow: ellipsis;
    color: #555;
    font-style: italic;
}

/* Footer: đẩy xuống cuối box */
.reviewer-footer {
    margin-top: auto;
    display: flex;
    align-items: center;
    gap: 10px;
}

</style>
    {{-- {{-- <!-- Breadcrumb Section Start --> --}}
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Giới thiệu</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="index.html">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                   Giới thiệu
                                </li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Breadcrumb Section End -->

    <!-- Fresh Vegetable Section Start -->
    <section class="fresh-vegetable-section section-lg-space">
        <div class="container-fluid-lg">
            <div class="row gx-xl-5 gy-xl-0 g-3 ratio_148_1">
                <div class="col-xl-6 col-12">
                    <div class="row g-sm-4 g-2">
                        <div class="col-6">
                            <div class="fresh-image-2">
                                <div>
                                    <img src="{{ asset('storage/banners/anh2.png') }}" class="bg-img blur-up lazyload"
                                        alt="" />
                                </div>
                            </div>
                        </div>

                        <div class="col-6">
                            <div class="fresh-image">
                                <div>
                                    <img src="{{ asset('storage/banners/anh1.png') }}" class="bg-img blur-up lazyload"
                                        alt="" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-6 col-12">
                    <div class="fresh-contain p-center-left">
                        <div>
                            <div class="review-title">
                                <h2>Quà Quê - Đặc sản vùng miền Việt Nam</h2>
                            </div>

                            <div class="delivery-list">
                                <p class="text-content">
                                   Quà Quê tự hào mang đến những sản phẩm đặc sản tươi ngon, thuần khiết từ các vùng miền quê hương Việt Nam.
                                Chúng tôi cam kết chọn lọc kỹ càng, bảo tồn hương vị truyền thống và chất lượng tự nhiên của từng món quà quê,
                                để mỗi sản phẩm là một câu chuyện, một phần ký ức thân thương gửi đến khách hàng.
                                </p>

                                {{-- <ul class="delivery-box">
                                    <li>
                                        <div class="delivery-box">
                                            <div class="delivery-icon">
                                                <img src="../assets/svg/3/delivery.svg" class="blur-up lazyload" alt="" />
                                            </div>

                                            <div class="delivery-detail">
                                                <h5 class="text">
                                                    Free delivery for all
                                                    orders
                                                </h5>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="delivery-box">
                                            <div class="delivery-icon">
                                                <img src="../assets/svg/3/leaf.svg" class="blur-up lazyload" alt="" />
                                            </div>

                                            <div class="delivery-detail">
                                                <h5 class="text">
                                                    Only fresh foods
                                                </h5>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="delivery-box">
                                            <div class="delivery-icon">
                                                <img src="../assets/svg/3/delivery.svg" class="blur-up lazyload" alt="" />
                                            </div>

                                            <div class="delivery-detail">
                                                <h5 class="text">
                                                    Free delivery for all
                                                    orders
                                                </h5>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="delivery-box">
                                            <div class="delivery-icon">
                                                <img src="../assets/svg/3/leaf.svg" class="blur-up lazyload" alt="" />
                                            </div>

                                            <div class="delivery-detail">
                                                <h5 class="text">
                                                    Only fresh foods
                                                </h5>
                                            </div>
                                        </div>
                                    </li>
                                </ul> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Fresh Vegetable Section End -->

    {{-- <!-- Client Section Start -->
    <section class="client-section section-lg-space">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="about-us-title text-center">
                        <h4>What We Do</h4>
                        <h2 class="center">We are Trusted by Clients</h2>
                    </div>

                    <div class="slider-3_1 product-wrapper">
                        <div>
                            <div class="clint-contain">
                                <div class="client-icon">
                                    <img src="../assets/svg/3/work.svg" class="blur-up lazyload" alt="" />
                                </div>
                                <h2>10</h2>
                                <h4>Business Years</h4>
                                <p>
                                    A coffee shop is a small business that
                                    sells coffee, pastries, and other
                                    morning goods. There are many different
                                    types of coffee shops around the world.
                                </p>
                            </div>
                        </div>

                        <div>
                            <div class="clint-contain">
                                <div class="client-icon">
                                    <img src="../assets/svg/3/buy.svg" class="blur-up lazyload" alt="" />
                                </div>
                                <h2>80 K+</h2>
                                <h4>Products Sales</h4>
                                <p>
                                    Some coffee shops have a seating area,
                                    while some just have a spot to order and
                                    then go somewhere else to sit down. The
                                    coffee shop that I am going to.
                                </p>
                            </div>
                        </div>

                        <div>
                            <div class="clint-contain">
                                <div class="client-icon">
                                    <img src="../assets/svg/3/user.svg" class="blur-up lazyload" alt="" />
                                </div>
                                <h2>90%</h2>
                                <h4>Happy Customers</h4>
                                <p>
                                    My goal for this coffee shop is to be
                                    able to get a coffee and get on with my
                                    day. It's a Thursday morning and I am
                                    rushing between meetings.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Client Section End --> --}}

    <!-- Review Section Start -->
   <section class="review-section section-lg-space">
    <div class="container-fluid">
        <div class="about-us-title text-center">
            <h2 class="center">Bình luận mới nhất</h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="slider-4-half product-wrapper">
                    @forelse ($recentComments as $comment)
                        <div>
                            <div class="reviewer-box">
                                <i class="fa-solid fa-quote-right"></i>

                                <h3>{{ $comment->blog->title ?? 'Không rõ bài viết' }}</h3>

                                <p>"{!! nl2br(e(Str::limit($comment->content, 200))) !!}"</p>

                                <div class="reviewer-profile">
                                    <div class="reviewer-image">
                                        <img src="{{ $comment->user?->avatar
                                                    ? asset('storage/' . $comment->user->avatar)
                                                    : asset('assets/images/users/default.jpg') }}"
                                            class="blur-up lazyload" width="60" alt="{{ $comment->user->name ?? 'Ẩn danh' }}" />
                                    </div>
                                    <div class="reviewer-name">
                                        <h4>{{ $comment->user->name ?? 'Ẩn danh' }}</h4>
                                        <h6>{{ $comment->created_at->format('d/m/Y') }}</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted text-center">Chưa có bình luận nào.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Review Section End -->

    <!-- Blog Section Start -->
    <section class="section-lg-space">
        <div class="container-fluid-lg">
            <div class="about-us-title text-center">
                <h2 class="center">Tin Tức Mới Nhất</h2>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-5 ratio_87">
                        @foreach($blog as $item)
                            <div>
                                <div class="blog-box">
                                    <div class="blog-box-image">
                                        <div class="blog-image">
                                            <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}" class="rounded-3">
                                                @if($item->thumbnail)
                                                    <img src="{{ asset($item->thumbnail) }}" class="bg-img blur-up lazyload"
                                                        alt="{{ $item->title }}">
                                                @endif
                                            </a>
                                        </div>
                                    </div>

                                    <a href="{{ route('client.blogs-detail', ['id' => $item->id]) }}"
                                        class="blog-detail d-block">
                                        {{-- <h6>{{ $item->category->name ?? 'No Category' }}</h6> --}}
                                        <h5>{{ $item->title }}</h5>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- Blog Section End -->

    <!-- Location Modal Start -->
    <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">
                        Choose your Delivery Location
                    </h5>
                    <p class="mt-1 text-content">
                        Enter your address and we will specify the offer for
                        your area.
                    </p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="location-list">
                        <div class="search-input">
                            <input type="search" class="form-control" placeholder="Search Your Area" />
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <div class="disabled-box">
                            <h6>Select a Location</h6>
                        </div>

                        <ul class="location-select custom-height">
                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Alabama</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Arizona</h6>
                                    <span>Min: $150</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>California</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Colorado</h6>
                                    <span>Min: $140</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Florida</h6>
                                    <span>Min: $160</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Georgia</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Kansas</h6>
                                    <span>Min: $170</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Minnesota</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>New York</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Washington</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Location Modal End -->

    <!-- Deal Box Modal Start -->
    <div class="modal fade theme-modal deal-modal" id="deal-box" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title w-100" id="deal_today">
                            Deal Today
                        </h5>
                        <p class="mt-1 text-content">
                            Recommended deals for you.
                        </p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="deal-offer-box">
                        <ul class="deal-offer-list">
                            <li class="list-1">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../assets/images/vegetable/product/10.png" class="blur-up lazyload"
                                            alt="" />
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>
                                            Blended Instant Coffee 50 g Buy
                                            1 Get 1 Free
                                        </h5>
                                        <h6>
                                            $52.57 <del>57.62</del>
                                            <span>500 G</span>
                                        </h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-2">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../assets/images/vegetable/product/11.png" class="blur-up lazyload"
                                            alt="" />
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>
                                            Blended Instant Coffee 50 g Buy
                                            1 Get 1 Free
                                        </h5>
                                        <h6>
                                            $52.57 <del>57.62</del>
                                            <span>500 G</span>
                                        </h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-3">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../assets/images/vegetable/product/12.png" class="blur-up lazyload"
                                            alt="" />
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>
                                            Blended Instant Coffee 50 g Buy
                                            1 Get 1 Free
                                        </h5>
                                        <h6>
                                            $52.57 <del>57.62</del>
                                            <span>500 G</span>
                                        </h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-1">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../assets/images/vegetable/product/13.png" class="blur-up lazyload"
                                            alt="" />
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>
                                            Blended Instant Coffee 50 g Buy
                                            1 Get 1 Free
                                        </h5>
                                        <h6>
                                            $52.57 <del>57.62</del>
                                            <span>500 G</span>
                                        </h6>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Deal Box Modal End -->

    <!-- Tap to top start -->
    <div class="theme-option">
        <div class="back-to-top">
            <a id="back-to-top" href="#">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <!-- Tap to top end -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->

    <!-- latest jquery-->
    <script src="../assets/js/jquery-3.6.0.min.js"></script>

    <!-- jquery ui-->
    <script src="../assets/js/jquery-ui.min.js"></script>

    <!-- Bootstrap js-->
    <script src="../assets/js/bootstrap/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/bootstrap/popper.min.js"></script>
    <script src="../assets/js/bootstrap/bootstrap-notify.min.js"></script>

    <!-- feather icon js-->
    <script src="../assets/js/feather/feather.min.js"></script>
    <script src="../assets/js/feather/feather-icon.js"></script>

    <!-- Lazyload Js -->
    <script src="../assets/js/lazysizes.min.js"></script>

    <!-- Slick js-->
    <script src="../assets/js/slick/slick.js"></script>
    <script src="../assets/js/slick/slick-animation.min.js"></script>
    <script src="../assets/js/slick/custom_slick.js"></script>

    <!-- script js -->
    <script src="../assets/js/script.js"></script>

@endsection