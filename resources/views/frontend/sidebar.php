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
                                <li><i data-feather="star" class=""></i></li>
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
        <!-- Trending Product (sản phẩm nổi bật theo view_week) -->
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
