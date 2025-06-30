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
                            $variantImages = [];
                            foreach ($product->variants as $variant) {
                                if (!empty($variant->image) && !empty($variant->value_ids)) {
                                    foreach ($variant->value_ids as $valueId) {
                                        $variantImages[$valueId] = asset('storage/' . $variant->image);
                                    }
                                }
                            }
                        @endphp
                        <div class="col-xl-6 wow fadeInUp">
                            <div class="product-left-box card shadow-sm p-3" style="border-radius:18px; background:#fff;">
                                <div class="main-image-wrapper d-flex justify-content-center align-items-center mb-3"
                                    style="border-radius:14px; background:#fafbfc; border:1.5px solid #e5e7eb; min-height:340px;">
                                    <img id="mainImage"
                                        src="{{ $descImgs[0] ?? asset('backend/assets/images/placeholder.webp') }}"
                                        alt="Ảnh sản phẩm"
                                        style="width:100%; max-width:420px; height:auto; border-radius:14px; object-fit:contain; box-shadow:0 2px 12px 0 rgba(0,0,0,0.04);">
                                </div>
                                <div class="thumbnail-wrapper d-flex justify-content-center gap-2 flex-wrap"
                                    style="margin-top:10px;">
                                    @foreach ($descImgs as $index => $img)
                                        <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}"
                                            class="thumbnail-image" data-index="{{ $index }}"
                                            style="width:56px; height:56px; object-fit:cover; border-radius:8px; border:2px solid #e5e7eb; cursor:pointer; transition:border 0.2s;">
                                    @endforeach
                                    @if (empty($descImgs))
                                        <img src="{{ asset('backend/assets/images/placeholder.webp') }}" alt="Không có ảnh"
                                            style="width:56px; height:56px; object-fit:cover; border-radius:8px; border:2px solid #e5e7eb; cursor:default;">
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="col-xl-6 wow fadeInUp" data-wow-delay="0.1s">
                            <div class="right-box-contain card shadow-sm p-4" style="border-radius:18px; background:#fff;">
                                <h2 class="name fw-bold mb-2" style="font-size:2rem;">{{ $product->name }}</h2>
                                <div class="price-rating d-flex align-items-center mb-3">
                                    <h3 class="theme-color price me-4 mb-0" id="product-price"
                                        style="font-size:1.7rem; font-weight:700;">
                                        {{ number_format($product->variants[0]->price ?? 0) }} đ</h3>
                                    <div>
                                        <div class="product-rating custom-rate d-flex align-items-center">
                                            <ul class="rating mb-0" style="font-size:1.1rem;">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li style="display:inline-block;"><i data-feather="star"
                                                            class="{{ $i <= round($product->reviews->avg('rating')) ? 'fill' : '' }}"></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                            <span class="review ms-2 text-muted"
                                                style="font-size:1rem;">{{ $product->reviews->count() }} đánh giá</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-packege mb-3">
                                    @foreach ($attributes as $attrId => $attr)
                                        <div class="product-title mb-1">
                                            <h6 class="fw-bold mb-1">{{ $attr['name'] }}</h6>
                                        </div>
                                        <ul class="select-packege d-flex flex-wrap gap-2 mb-2">
                                            @foreach ($attr['values'] as $valueId => $value)
                                                <li>
                                                    <a href="javascript:void(0)" data-attr="{{ $attrId }}"
                                                        data-value="{{ $valueId }}"
                                                        @if (isset($variantImages[$valueId])) data-variant-image="{{ $variantImages[$valueId] }}" @endif
                                                        class="attribute-select btn btn-outline-secondary px-3 py-1 rounded-pill @if (isset($defaultSelected[$attrId]) && $defaultSelected[$attrId] == $valueId) active2 @endif"
                                                        style="font-size:1rem;">{{ $value }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endforeach
                                </div>
                                <div class="note-box product-packege mb-3">
                                    <div class="cart_qty qty-box product-qty d-flex align-items-center">
                                        <div class="input-group" style="max-width:140px;">
                                            <button type="button" class="qty-right-plus btn btn-light border"
                                                data-type="plus" data-field=""><i class="fa fa-plus"
                                                    aria-hidden="true"></i></button>
                                            <input class="form-control input-number qty-input text-center" type="text"
                                                name="quantity" value="0" style="max-width:48px;" />
                                            <button type="button" class="qty-left-minus btn btn-light border"
                                                data-type="minus" data-field=""><i class="fa fa-minus"
                                                    aria-hidden="true"></i></button>
                                        </div>
                                        <button onclick="location.href = 'cart.html';"
                                            class="btn btn-md bg-dark cart-button text-white ms-3 px-4 py-2 rounded-pill fw-bold">Thêm
                                            vào
                                            giỏ</button>
                                    </div>
                                </div>
                                <div class="buy-box mb-3">
                                    <form action="{{ route('client.wishlist.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="notifi-wishlist btn p-0 d-flex align-items-center">
                                            <i data-feather="heart" style="width:22px;height:22px;"
                                                @if (auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) class="text-red-500" @endif></i>
                                            <span class="ms-2 fw-semibold">Thêm vào yêu thích</span>
                                        </button>
                                    </form>
                                </div>
                                <div class="pickup-box">
                                    <div class="product-info">
                                        <ul class="product-info-list product-info-list-2 list-unstyled mb-0">
                                            <li class="mb-1">SKU : <span id="product-sku"
                                                    class="fw-semibold">{{ $product->variants[0]->sku ?? '—' }}</span>
                                            </li>
                                            <li>Danh mục : <span
                                                    class="fw-semibold">{{ $product->category->name ?? '' }}</span></li>
                                            <li>Vùng miền : <span
                                                    class="fw-semibold">{{ $product->region->name ?? '' }}</span></li>
                                            <li>Xuất xứ : <span class="fw-semibold">{{ $product->origin ?? '' }}</span>
                                            </li>
                                            <li class="mb-1">Trong kho còn : <span id="product-stock"
                                                    class="fw-semibold">{{ $product->variants[0]->stock ?? '—' }}</span>
                                                sản phẩm</li>
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
                                            Mô tả sản phẩm
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

                                    <div class="tab-pane fade" id="care" role="tabpanel"
                                        aria-labelledby="care-tab">
                                        <div class="information-box">
                                            @if ($product->comments->count())
                                                <ul class="review-list">
                                                    @foreach ($product->comments as $comment)
                                                        <li>
                                                            <div class="people-box">
                                                                <div>
                                                                    <div class="people-image">
                                                                        <img src="{{ asset('frontend/assets/images/review/1.jpg') }}"
                                                                            class="img-fluid blur-up lazyload"
                                                                            alt="" />
                                                                    </div>
                                                                </div>
                                                                <div class="people-comment">
                                                                    <a class="name"
                                                                        href="javascript:void(0)">{{ $comment->user->name ?? 'Ẩn danh' }}</a>
                                                                    <div class="date-time">
                                                                        <h6 class="text-content">
                                                                            {{ $comment->created_at ? $comment->created_at->format('d/m/Y H:i') : '' }}
                                                                        </h6>
                                                                    </div>
                                                                    <div class="reply">
                                                                        <p>{{ $comment->content }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <p class="text-muted">Chưa có bình luận nào cho sản phẩm này.</p>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="review" role="tabpanel"
                                        aria-labelledby="review-tab">
                                        <div class="review-box">
                                            <div class="row g-4">
                                                <div class="col-xl-6">
                                                    <div class="review-title">
                                                        <h4 class="fw-500">Đánh giá của khách hàng</h4>
                                                    </div>
                                                    <div class="d-flex">
                                                        <div class="product-rating">
                                                            <ul class="rating">
                                                                @php $avg = round($product->reviews->avg('rating')); @endphp
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    <li><i data-feather="star"
                                                                            class="{{ $i <= $avg ? 'fill' : '' }}"></i>
                                                                    </li>
                                                                @endfor
                                                            </ul>
                                                        </div>
                                                        <h6 class="ms-3">
                                                            {{ number_format($product->reviews->avg('rating'), 1) }} / 5
                                                        </h6>
                                                    </div>
                                                    <div class="rating-box mt-3">
                                                        <ul>
                                                            @for ($i = 5; $i >= 1; $i--)
                                                                <li>
                                                                    <div class="rating-list d-flex align-items-center">
                                                                        <h5 class="mb-0">{{ $i }} Star</h5>
                                                                        <div class="progress mx-2"
                                                                            style="width: 120px; height: 8px;">
                                                                            @php
                                                                                $total = $product->reviews->count();
                                                                                $count = $product->reviews
                                                                                    ->where('rating', $i)
                                                                                    ->count();
                                                                                $percent = $total
                                                                                    ? round(($count / $total) * 100)
                                                                                    : 0;
                                                                            @endphp
                                                                            <div class="progress-bar" role="progressbar"
                                                                                style="width: {{ $percent }}%;"
                                                                                aria-valuenow="{{ $percent }}"
                                                                                aria-valuemin="0" aria-valuemax="100">
                                                                                {{ $percent }}%</div>
                                                                        </div>
                                                                        <span
                                                                            class="text-muted">({{ $count }})</span>
                                                                    </div>
                                                                </li>
                                                            @endfor
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="col-xl-6">
                                                    <div class="review-title">
                                                        <h4 class="fw-500">Thêm đánh giá mới</h4>
                                                    </div>
                                                    <div class="row g-4">
                                                        <div class="col-12">
                                                            <div class="form-floating theme-form-floating">
                                                                <textarea class="form-control" placeholder="Leave a comment here" id="floatingTextarea2" style="height: 150px;"></textarea>
                                                                <label for="floatingTextarea2">Viết đanh giá tại
                                                                    đây...</label>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-4">
                                                    <div class="review-title">
                                                        <h4 class="fw-500">Danh sách đánh giá</h4>
                                                    </div>
                                                    <div class="review-people">
                                                        <ul class="review-list">
                                                            @forelse($product->reviews as $review)
                                                                <li>
                                                                    <div class="people-box">
                                                                        <div>
                                                                            <div class="people-image">
                                                                                <img src="{{ asset('frontend/assets/images/review/1.jpg') }}"
                                                                                    class="img-fluid blur-up lazyload"
                                                                                    alt="" />
                                                                            </div>
                                                                        </div>
                                                                        <div class="people-comment">
                                                                            <a class="name"
                                                                                href="javascript:void(0)">{{ $review->user->name ?? 'Ẩn danh' }}</a>
                                                                            <div class="date-time">
                                                                                <h6 class="text-content">
                                                                                    {{ $review->created_at ? $review->created_at->format('d/m/Y H:i') : '' }}
                                                                                </h6>
                                                                                <div class="product-rating">
                                                                                    <ul class="rating">
                                                                                        @for ($i = 1; $i <= 5; $i++)
                                                                                            <li><i data-feather="star"
                                                                                                    class="{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                                                                            </li>
                                                                                        @endfor
                                                                                    </ul>
                                                                                </div>
                                                                            </div>
                                                                            <div class="reply">
                                                                                <p>{{ $review->comment }}</p>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </li>
                                                            @empty
                                                                <li>
                                                                    <p class="text-muted">Chưa có đánh giá nào cho sản phẩm
                                                                        này.</p>
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
                        <!-- Sản phẩm thịnh hành -->
                        @if (isset($topViewedProducts) && $topViewedProducts->count())
                            <div class="section-t-space">
                                <div class="category-menu">
                                    <h3>Sản phẩm thịnh hành</h3>
                                    <ul class="product-list border-0 p-0 d-block">
                                        @foreach ($topViewedProducts->take(4) as $product)
                                            <li>
                                                <div class="offer-product">
                                                    <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}"
                                                        class="offer-image">
                                                        <img src="{{ asset('storage/' . $product->image) }}"
                                                            class="blur-up lazyload" alt="{{ $product->name }}">
                                                    </a>
                                                    <div class="offer-detail">
                                                        <div>
                                                            <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}"
                                                                class="text-title">
                                                                <h6 class="name">{{ $product->name }}</h6>
                                                            </a>
                                                            <span>{{ $product->variants->first()->weight ?? '' }}</span>
                                                            <h6 class="price theme-color">
                                                                {{ number_format(optional($product->variants->first())->price ?? 0) }}₫
                                                            </h6>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                        <!-- Banner Section -->
                        @if (isset($productSectionPromoLeftTop))
                            <div class="ratio_156 pt-25">
                                <div class="home-contain hover-effect">
                                    <img src="{{ asset('storage/' . $productSectionPromoLeftTop->image) }}"
                                        class="bg-img blur-up lazyload" alt="{{ $productSectionPromoLeftTop->title }}" />
                                    <div class="home-detail p-top-left home-p-medium">
                                        <div>
                                            <h6 class="text-yellow home-banner">{!! $productSectionPromoLeftTop->title !!}</h6>
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
    <!-- Product Left Sidebar End -->

    <section class="product-list-section section-b-space">
        <div class="container-fluid-lg">
            <style>
                .product-list-section .product-box-3 .product-image {
                    height: 200px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    margin-bottom: 15px;
                }

                .product-list-section .product-box-3 .product-image img {
                    width: 250px !important;
                    height: 160px !important;
                    object-fit: cover;
                    border-radius: 16px;
                    background: #f8f8f8;
                }

                .product-list-section .product-box-3 .product-image a {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    width: 100%;
                    height: 100%;
                }
            </style>
            <div class="title">
                <h2>Sản phẩm tương tự</h2>
                <span class="title-leaf">
                    <svg class="icon-width">
                        <use xlink:href="../frontend/assets/svg/leaf.svg#leaf"></use>
                    </svg>
                </span>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="slider-6_1 product-wrapper">
                        @forelse($related as $product)
                            <div>
                                <div class="product-box-3 wow fadeInUp" data-wow-delay="{{ $loop->index * 0.05 }}s">
                                    <div class="product-header">
                                        <div class="product-image">
                                            <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                                                <img src="{{ asset('storage/' . $product->image) }}"
                                                    class="img-fluid blur-up lazyload" alt="{{ $product->name }}">
                                            </a>
                                            <ul class="product-option">
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Xem nhanh">
                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                        data-bs-target="#view" class="quickview-btn"
                                                        data-name="{{ $product->name }}"
                                                        data-price="{{ number_format(optional($product->variants->first())->price ?? 0) }}đ"
                                                        data-rating="{{ $product->reviews->avg('rating') ?? '' }}"
                                                        data-description="{!! $product->description !!}"
                                                        data-code="{{ $product->variants->first()->sku ?? '' }}"
                                                        data-origin="{{ $product->origin ?? '' }}"
                                                        data-image="{{ asset('storage/' . $product->image) }}"
                                                        data-link="{{ route('client.product.detail', ['slug' => $product->slug]) }}"
                                                        data-description-images='@json(collect([$product->image])->merge($product->images?->pluck('image_url') ?? [])->filter(fn($img) => !empty($img))->map(fn($img) => asset('storage/' . $img))->values()->toArray())'>
                                                        <i data-feather="eye"></i>
                                                    </a>
                                                </li>
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="So sánh">
                                                    <a href="{{ url('compare') }}">
                                                        <i data-feather="refresh-cw"></i>
                                                    </a>
                                                </li>
                                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
                                                    <form action="{{ route('client.wishlist.store') }}" method="POST">
                                                        @csrf
                                                        <input type="hidden" name="product_id"
                                                            value="{{ $product->id }}">
                                                        <button type="submit" class="notifi-wishlist btn p-0">
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
                                                    @php $avg = round($product->reviews->avg('rating') ?? 0); @endphp
                                                    @for ($i = 1; $i <= 5; $i++)
                                                        <li>
                                                            <i data-feather="star"
                                                                class="{{ $i <= $avg ? 'fill' : '' }}"></i>
                                                        </li>
                                                    @endfor
                                                </ul>
                                                <span>({{ number_format($avg, 1) }})</span>
                                            </div>
                                            <h6 class="unit">{{ $product->variants->first()->weight ?? '' }}</h6>
                                            <h5 class="price">
                                                <span
                                                    class="theme-color">{{ number_format(optional($product->variants->first())->price ?? 0) }}₫</span>
                                                @if ($product->variants->first()->original_price ?? false)
                                                    <del>{{ number_format($product->variants->first()->original_price) }}₫</del>
                                                @endif
                                            </h5>
                                            <div class="add-to-cart-box bg-white">
                                                <button class="btn btn-add-cart addcart-button">Add
                                                    <span class="add-icon bg-light-gray">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </span>
                                                </button>
                                                <div class="cart_qty qty-box">
                                                    <div class="input-group bg-white">
                                                        <button type="button" class="qty-left-minus bg-gray"
                                                            data-type="minus" data-field="">
                                                            <i class="fa fa-minus"></i>
                                                        </button>
                                                        <input class="form-control input-number qty-input" type="text"
                                                            name="quantity" value="0">
                                                        <button type="button" class="qty-right-plus bg-gray"
                                                            data-type="plus" data-field="">
                                                            <i class="fa fa-plus"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p>Không có sản phẩm nào cùng danh mục.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        window.VARIANTS = @json($variantMap ?? []);
        let selected = {};
        let variants = window.VARIANTS;

        document.querySelectorAll('.attribute-select').forEach(function(btn) {
            btn.addEventListener('click', function() {
                let attr = btn.getAttribute('data-attr');
                let val = btn.getAttribute('data-value');

                // Remove active CHỈ trong nhóm này
                btn.closest('ul').querySelectorAll('a').forEach(a => a.classList.remove('active2'));
                btn.classList.add('active2');
                selected[attr] = parseInt(val);

                // Nếu đã chọn đủ thuộc tính
                if (Object.keys(selected).length === Object.keys(@json($attributes)).length) {
                    let attrValueIds = Object.values(selected).map(Number).sort((a, b) => a - b);
                    let found = variants.find(v =>
                        v.value_ids.length === attrValueIds.length &&
                        v.value_ids.slice().sort((a, b) => a - b).every((id, i) => id === attrValueIds[
                            i])
                    );
                    if (found) {
                        document.getElementById('product-sku').textContent = found.sku || 'N/A';
                        document.getElementById('product-stock').textContent = found.stock ?? 'N/A';
                        document.getElementById('product-price').textContent = found.price ? (Number(found
                            .price).toLocaleString() + ' đ') : 'Liên hệ';
                    } else {
                        document.getElementById('product-sku').textContent = 'Không tồn tại';
                        document.getElementById('product-stock').textContent = 'Không tồn tại';
                        document.getElementById('product-price').textContent = 'Không tồn tại';
                    }
                } else {
                    document.getElementById('product-sku').textContent = '—';
                    document.getElementById('product-stock').textContent = '—';
                    document.getElementById('product-price').textContent = '—';
                }

                // Đổi ảnh nếu có data-variant-image
                var img = btn.getAttribute('data-variant-image');
                if (img) {
                    document.getElementById('mainImage').src = img;
                }
            });
        });

        document.querySelectorAll('.thumbnail-image').forEach(function(img) {
            img.addEventListener('click', function() {
                document.getElementById('mainImage').src = this.src;
                document.querySelectorAll('.thumbnail-image').forEach(i => i.style.border =
                    '2px solid transparent');
                this.style.border = '2px solid #0da487';
            });
        });
    </script>

@endsection
@push('scripts')
@endpush
