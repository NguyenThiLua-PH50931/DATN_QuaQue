@extends('layouts.frontend')

@section('title', 'Tất cả sản phẩm')

@section('contents')
    <div style="margin-bottom: 40px">
        <section class="breadscrumb-section pt-0">
            <div class="container-fluid-lg">
                <div class="row">
                    <div class="col-12">
                        <div class="breadscrumb-contain">
                            <h2>Sản phẩm</h2>
                            <nav>
                                <ol class="breadcrumb mb-0">
                                    <li class="breadcrumb-item">
                                        <a href="{{ route('client.home') }}">
                                            <i class="fa-solid fa-house"></i>
                                        </a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">Sản phẩm</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Shop Section Start -->
    {{-- resources/views/frontend/products/catalog.blade.php --}}
    @php
        // Chuẩn hoá tham số lọc từ URL (hỗ trợ array hoặc CSV)
        $dmSelected = request()->has('dm')
            ? (is_array(request('dm'))
                ? request('dm')
                : explode(',', request('dm')))
            : [];
        $regionSelected = request()->has('regions')
            ? (is_array(request('regions'))
                ? request('regions')
                : explode(',', request('regions')))
            : [];
        $ratingSelected = request()->has('rating')
            ? (is_array(request('rating'))
                ? request('rating')
                : explode(',', request('rating')))
            : [];

        // Giá min/max mặc định
        $minPrice = request('min_price', 0);
        $maxPrice = request('max_price', 10000000);

        // Map nhãn sắp xếp
        $sortMap = [
            'aToz' => 'A - Z',
            'zToa' => 'Z - A',
            'pop' => 'Phổ biến nhất',
            'low' => 'Giá thấp đến cao',
            'high' => 'Giá cao đến thấp',
            'rating' => 'Đánh giá trung bình',
        ];
        $currentSort = request('sort', 'pop');
        $currentSortText = $sortMap[$currentSort] ?? $sortMap['pop'];
    @endphp

    <section class="section-b-space shop-section">
        <div class="container-fluid-lg">
            <div class="row">
                {{-- SIDEBAR --}}
                <div class="col-custome-3">
                    <div class="left-box wow fadeInUp">
                        <div class="shop-left-sidebar">
                            <div class="back-button">
                                <h3>
                                    <i class="fa-solid fa-arrow-left"></i>
                                    Back
                                </h3>
                            </div>

                            <div class="filter-product">
                                <div class="filter-category">
                                    <h2>Tìm kiếm</h2>
                                </div>
                                <div class="form-floating theme-form-floating-2 search-box">
                                    <input type="search" class="form-control" id="search-keyword"
                                        value="{{ request('q') ?? '' }}" placeholder="Tìm sản phẩm..." />
                                    <label for="product-search">Tìm sản phẩm</label>
                                </div>
                            </div>

                            <div class="accordion custome-accordion" id="accordionExample">
                                {{-- Danh mục --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingOne">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                            <span>Danh mục</span>
                                        </button>
                                    </h2>
                                    <div id="collapseOne" class="accordion-collapse collapse show"
                                        aria-labelledby="headingOne">
                                        <div class="accordion-body">
                                            <div class="form-floating theme-form-floating-2 search-box">
                                                <input type="search" class="form-control" id="category-search"
                                                    placeholder="Tìm danh mục .." />
                                                <label for="category-search">Tìm danh mục</label>
                                            </div>
                                            <ul class="category-list custom-padding custom-height"
                                                id="category-filter-list">
                                                @foreach ($categories as $category)
                                                    @php $checked = in_array($category->id, array_map('intval', $dmSelected)); @endphp
                                                    <li>
                                                        <div class="form-check ps-0 m-0 category-list-box">
                                                            <input class="checkbox_animated category-checkbox"
                                                                type="checkbox" id="category-{{ $category->id }}"
                                                                value="{{ $category->id }}"
                                                                @if ($checked) checked @endif />
                                                            <label class="form-check-label"
                                                                for="category-{{ $category->id }}">
                                                                <span class="name">{{ $category->name }}</span>
                                                                <span
                                                                    class="number">({{ $category->products_count ?? 0 }})</span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Vùng miền --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingTwo">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                            <span>Vùng miền</span>
                                        </button>
                                    </h2>
                                    <div id="collapseTwo" class="accordion-collapse collapse show"
                                        aria-labelledby="headingTwo">
                                        <div class="accordion-body">
                                            <ul class="category-list custom-padding" id="region-filter-list">
                                                @foreach ($regions as $region)
                                                    @php $checked = in_array($region->id, array_map('intval', $regionSelected)); @endphp
                                                    <li>
                                                        <div class="form-check ps-0 m-0 category-list-box">
                                                            <input class="checkbox_animated region-checkbox" type="checkbox"
                                                                id="region-{{ $region->id }}" value="{{ $region->id }}"
                                                                @if ($checked) checked @endif />
                                                            <label class="form-check-label"
                                                                for="region-{{ $region->id }}">
                                                                <span class="name">{{ $region->name }}</span>
                                                                <span
                                                                    class="number">({{ $region->products_count ?? 0 }})</span>
                                                            </label>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                {{-- Khoảng giá --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="false"
                                            aria-controls="collapseThree">
                                            <span>Khoảng giá</span>
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse show"
                                        aria-labelledby="headingThree">
                                        <div class="accordion-body">
                                            <div class="range-slider">
                                                <input type="text" class="js-range-slider" id="price-range"
                                                    value="" data-min="0" data-max="10000000"
                                                    data-from="{{ (int) $minPrice }}" data-to="{{ (int) $maxPrice }}"
                                                    data-step="10000" data-prefix="₫" />
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Đánh giá --}}
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingSix">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false"
                                            aria-controls="collapseSix">
                                            <span>Đánh giá</span>
                                        </button>
                                    </h2>
                                    <div id="collapseSix" class="accordion-collapse collapse show"
                                        aria-labelledby="headingSix">
                                        <div class="accordion-body">
                                            <ul class="category-list custom-padding" id="rating-filter-list">
                                                @for ($star = 5; $star >= 1; $star--)
                                                    @php $checked = in_array($star, array_map('intval', $ratingSelected)); @endphp
                                                    <li>
                                                        <div class="form-check ps-0 m-0 category-list-box">
                                                            <input class="checkbox_animated rating-checkbox"
                                                                type="checkbox" id="rating-{{ $star }}"
                                                                value="{{ $star }}"
                                                                @if ($checked) checked @endif />
                                                            <div class="form-check-label">
                                                                <ul class="rating">
                                                                    @for ($i = 1; $i <= 5; $i++)
                                                                        <li>
                                                                            <i data-feather="star"
                                                                                class="{{ $i <= $star ? 'fill' : '' }}"></i>
                                                                        </li>
                                                                    @endfor
                                                                </ul>
                                                                <span class="text-content">({{ $star }}
                                                                    sao)</span>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                {{-- /Đánh giá --}}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- CONTENT --}}
                <div class="col-custome-9">
                    <div class="show-button">
                        <div class="filter-button-group mt-0">
                            <div class="filter-button d-inline-block d-lg-none">
                                <a><i class="fa-solid fa-filter"></i> Filter Menu</a>
                            </div>
                        </div>

                        <div class="top-filter-menu">
                            <div class="category-dropdown">
                                <h5 class="text-content">Sắp xếp theo :</h5>
                                <div class="dropdown">
                                    <button class="dropdown-toggle" type="button" id="dropdownMenuButton1"
                                        data-bs-toggle="dropdown" aria-expanded="false">
                                        <span id="current-sort-text"
                                            data-sort="{{ $currentSort }}">{{ $currentSortText }}</span>
                                        <i class="fa-solid fa-angle-down"></i>
                                    </button>
                                    <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="sort-options">
                                        <li><a class="dropdown-item" data-sort="aToz" href="javascript:void(0)">A - Z</a>
                                        </li>
                                        <li><a class="dropdown-item" data-sort="zToa" href="javascript:void(0)">Z - A</a>
                                        </li>
                                        <li><a class="dropdown-item" data-sort="pop" href="javascript:void(0)">Phổ biến
                                                nhất</a></li>
                                        <li><a class="dropdown-item" data-sort="low" href="javascript:void(0)">Giá thấp
                                                đến cao</a></li>
                                        <li><a class="dropdown-item" data-sort="high" href="javascript:void(0)">Giá cao
                                                đến thấp</a></li>
                                        <li><a class="dropdown-item" data-sort="rating" href="javascript:void(0)">Đánh
                                                giá trung bình</a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="grid-option d-none d-md-block">
                                <ul>
                                    <li class="three-grid">
                                        <a href="javascript:void(0)">
                                            <img src="{{ asset('frontend/assets/svg/grid-3.svg') }}"
                                                class="blur-up lazyload" alt="" />
                                        </a>
                                    </li>
                                    <li class="grid-btn d-xxl-inline-block d-none active">
                                        <a href="javascript:void(0)">
                                            <img src="{{ asset('frontend/assets/svg/grid-4.svg') }}"
                                                class="blur-up lazyload d-lg-inline-block d-none" alt="" />
                                            <img src="{{ asset('frontend/assets/svg/grid.svg') }}"
                                                class="blur-up lazyload img-fluid d-lg-none d-inline-block"
                                                alt="" />
                                        </a>
                                    </li>
                                    <li class="list-btn">
                                        <a href="javascript:void(0)">
                                            <img src="{{ asset('frontend/assets/svg/list.svg') }}"
                                                class="blur-up lazyload" alt="" />
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <style>
                        .row {
                            justify-content: left !important;
                        }
                    </style>

                    {{-- WRAPPER để AJAX thay thế --}}
                    <div id="product-list-wrapper">
                        <div
                            class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">
                            @forelse ($products as $product)
                                <div>
                                    <div class="product-box-3 h-100 wow fadeInUp" data-wow-delay="0.05s">
                                        <div class="product-header">
                                            <div class="product-image">
                                                <a href="{{ route('client.product.detail', $product->slug) }}">
                                                    <img src="{{ asset('storage/' . $product->image) }}"
                                                        class="img-fluid blur-up lazyload" alt="{{ $product->name }}" />
                                                </a>

                                                <style>
                                                    .product-option li {
                                                        width: 50% !important;
                                                    }

                                                    /* ==== CARD: bố cục cột, cao bằng nhau ==== */
                                                    .product-box-3 {
                                                        display: flex;
                                                        flex-direction: column;
                                                        height: 100%;
                                                    }

                                                    /* Footer co giãn để giữ card bằng nhau */
                                                    .product-box-3 .product-footer {
                                                        flex: 1;
                                                        display: flex;
                                                    }

                                                    .product-box-3 .product-detail {
                                                        flex: 1;
                                                        display: flex;
                                                        flex-direction: column;
                                                        gap: .4rem;
                                                    }

                                                    /* ==== Category: ép 1 dòng ==== */
                                                    .product-detail .span-name {
                                                        display: -webkit-box;
                                                        -webkit-line-clamp: 1;
                                                        -webkit-box-orient: vertical;
                                                        overflow: hidden;
                                                        line-height: 1.3;
                                                        min-height: calc(1em * 1.3);
                                                    }

                                                    /* ==== Tên sản phẩm: ép 2 dòng ==== */
                                                    .product-detail .name {
                                                        display: -webkit-box;
                                                        -webkit-line-clamp: 2;
                                                        -webkit-box-orient: vertical;
                                                        overflow: hidden;
                                                        line-height: 1.35;
                                                        min-height: calc(1em * 2 * 1.35);
                                                    }

                                                    /* ==== Mô tả: ép 3 dòng ==== */
                                                    .product-detail .description-limit {
                                                        display: -webkit-box;
                                                        -webkit-line-clamp: 3;
                                                        -webkit-box-orient: vertical;
                                                        overflow: hidden;
                                                        text-overflow: ellipsis;
                                                        white-space: normal;
                                                        line-height: 1.4;
                                                        min-height: calc(1em * 3 * 1.4);
                                                    }

                                                    /* ==== Giá: luôn dính đáy ==== */
                                                    .product-detail .price {
                                                        margin-top: auto;
                                                    }

                                                    /* ==== Ảnh: vuông, thu nhỏ bên trong ==== */
                                                    .product-box-3 .product-image {
                                                        position: relative;
                                                        overflow: hidden;
                                                        border-radius: 16px;
                                                        background: #fff;
                                                        /* viền trắng */
                                                    }

                                                    /* Khung ảnh vuông */
                                                    .product-box-3 .product-image::before {
                                                        content: "";
                                                        display: block;
                                                        padding-top: 100%;
                                                        /* 1:1; đổi 75% cho 4:3 */
                                                    }

                                                    /* Vị trí ảnh bên trong, có khoảng trống viền */
                                                    .product-box-3 .product-image>a {
                                                        position: absolute;
                                                        inset: 24px;
                                                        /* ảnh nhỏ hơn, tạo viền trắng */
                                                        border-radius: 12px;
                                                        overflow: hidden;
                                                    }

                                                    /* Ảnh lấp đầy phần bên trong */
                                                    .product-box-3 .product-image>a>img {
                                                        width: 100% !important;
                                                        height: 100% !important;
                                                        object-fit: cover !important;
                                                        display: block;
                                                        border-radius: inherit;
                                                    }

                                                    /* ==== Badge TOP (optional) ==== */
                                                    .product-box-3 .product-image::after {
                                                        position: absolute;
                                                        top: 8px;
                                                        left: 8px;
                                                        background: #12d6a7;
                                                        color: #fff;
                                                        font-weight: bold;
                                                        font-size: 0.8rem;
                                                        padding: 2px 8px;
                                                        border-radius: 6px;
                                                    }
                                                </style>

                                                <ul class="product-option">
                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Xem nhanh">
                                                        <a href="javascript:void(0)" class="quickview-btn"
                                                            data-slug="{{ $product->slug }}">
                                                            <i data-feather="eye"></i>
                                                        </a>
                                                    </li>
                                                    <li data-bs-toggle="tooltip" data-bs-placement="top"
                                                        title="Yêu thích">
                                                        <form action="{{ route('client.wishlist.store') }}"
                                                            method="POST" style="display:inline-block;">
                                                            @csrf
                                                            <input type="hidden" name="product_id"
                                                                value="{{ $product->id }}">
                                                            <button type="submit" class="notifi-wishlist btn p-0"
                                                                style="border:none; background:none; width:18px; height:18px; margin-top:10px;">
                                                                <i data-feather="heart" style="color:#4a5568;"
                                                                    @if (auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) class="text-red-500" @endif></i>
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>

                                        <div class="product-footer">
                                            <div class="product-detail">
                                                @php
                                                    $categoryNames = $product->categories->pluck('name');
                                                    $showCategories = $categoryNames->take(3);
                                                    $more = $categoryNames->count() > 3;
                                                @endphp

                                                <span class="span-name">
                                                    {{ $showCategories->join(', ') }} @if ($more)
                                                        ...
                                                    @endif
                                                </span>

                                                <a href="{{ route('client.product.detail', $product->slug) }}">
                                                    <h5 class="name">{{ $product->name }}</h5>
                                                </a>

                                                <p class="text-content mt-1 mb-2 product-content description-limit">
                                                    {!! Str::limit(strip_tags($product->description), 200) !!}
                                                </p>
                                                <style>
                                                    .description-limit {
                                                        display: -webkit-box;
                                                        -webkit-line-clamp: 3;
                                                        -webkit-box-orient: vertical;
                                                        overflow: hidden;
                                                        text-overflow: ellipsis;
                                                        white-space: normal;
                                                    }
                                                </style>

                                                <div class="product-rating mt-2">
                                                    @php $avgRating = round($product->reviews->avg('rating') ?? 0); @endphp
                                                    <ul class="rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <li><i data-feather="star"
                                                                    class="{{ $i <= $avgRating ? 'fill' : '' }}"></i></li>
                                                        @endfor
                                                    </ul>
                                                    <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                                                </div>

                                                @php
                                                    // chỉ cần active == 1 (trigger stock -> active đã đảm bảo)
                                                    $variantInStock = $product->variants->firstWhere(
                                                        fn($v) => $v->active == 1,
                                                    );
                                                @endphp

                                                @if ($variantInStock && $product->active == 1)
                                                    <h6 class="unit">{{ $variantInStock->name }}</h6>
                                                    <h5 class="price">
                                                        <span
                                                            class="theme-color">{{ number_format($variantInStock->price, 0, ',', '.') }}₫</span>
                                                    </h5>
                                                @else
                                                    <h3 class="text-danger text-center">Hết hàng</h3>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <p>Không có sản phẩm nào phù hợp.</p>
                            @endforelse
                        </div>

                        <nav class="custome-pagination">
                            {{ $products->withQueryString()->links() }}
                        </nav>
                    </div>
                    {{-- /#product-list-wrapper --}}
                </div>
            </div>
        </div>
    </section>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const baseUrl = '/client/san-pham/';

            /* ---------------- helpers ---------------- */

            function qs(sel, root = document) {
                return root.querySelector(sel);
            }

            function qsa(sel, root = document) {
                return Array.from(root.querySelectorAll(sel));
            }

            // Lấy params từ UI (không mang theo page để filter mới luôn về trang 1)
            function buildParams(extra = {}) {
                const params = new URLSearchParams();

                // Tìm kiếm
                const keyword = qs('#search-keyword')?.value.trim();
                if (keyword) params.set('q', keyword);

                // Danh mục
                const dm = qsa('.category-checkbox:checked').map(cb => cb.value);
                if (dm.length) params.set('dm', dm.join(','));

                // Vùng miền
                const regions = qsa('.region-checkbox:checked').map(cb => cb.value);
                if (regions.length) params.set('regions', regions.join(','));

                // Khoảng giá (đọc từ dataset do ionRangeSlider fill sẵn)
                const priceEl = qs('#price-range');
                if (priceEl) {
                    const from = priceEl.dataset.from,
                        to = priceEl.dataset.to;
                    if (from && parseInt(from) > 0) params.set('min_price', from);
                    if (to && parseInt(to) < 10000000) params.set('max_price', to);
                }

                // Đánh giá
                const rating = qsa('.rating-checkbox:checked').map(cb => cb.value);
                if (rating.length) params.set('rating', rating.join(','));

                // Sắp xếp
                const sort = qs('#current-sort-text')?.dataset.sort;
                if (sort && sort !== 'pop') params.set('sort', sort);

                // Thêm params phụ (ví dụ page)
                for (const [k, v] of Object.entries(extra)) params.set(k, v);

                return params;
            }

            // Tải list qua AJAX & cập nhật wrapper
            async function fetchProducts(params) {
                const url = baseUrl + (params.toString() ? ('?' + params.toString()) : '');
                history.pushState({}, '', url); // cập nhật URL không reload

                const res = await fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });
                const html = await res.text();

                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');

                const newWrapper = doc.querySelector('#product-list-wrapper');
                const wrapper = document.querySelector('#product-list-wrapper');

                // Server trả partial có wrapper → thay innerHTML; nếu không có, fallback: thay toàn bộ wrapper
                if (newWrapper && wrapper) {
                    wrapper.innerHTML = newWrapper.innerHTML;
                } else if (wrapper) {
                    wrapper.innerHTML = html;
                }

                // Re-init icons / tooltips nếu cần
                if (window.feather && typeof feather.replace === 'function') feather.replace();

                // bind lại pagination (nếu bạn không dùng event delegation)
                // (ở dưới mình đã dùng delegation nên không cần rebind thêm)
                // cuộn lên đầu danh sách cho UX
                wrapper?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }

            function applyFilter(extra = {}) {
                // reset page khi đổi filter (trừ khi explicit set)
                if (!('page' in extra)) extra.page = 1;
                const params = buildParams(extra);
                fetchProducts(params);
            }

            /* ---------------- bindings ---------------- */

            // Tìm kiếm: Enter + debounce nhập liệu
            const searchInput = qs('#search-keyword');
            let searchTimer = null;
            searchInput?.addEventListener('keypress', e => {
                if (e.key === 'Enter') applyFilter();
            });
            searchInput?.addEventListener('input', () => {
                clearTimeout(searchTimer);
                searchTimer = setTimeout(() => applyFilter(), 500);
            });

            // Checkbox danh mục / vùng miền / rating
            qsa('.category-checkbox, .region-checkbox, .rating-checkbox')
                .forEach(cb => cb.addEventListener('change', () => applyFilter()));

            // Khoảng giá (ionRangeSlider)
            // Nếu bạn khởi tạo ionRangeSlider ở nơi khác, bắt sự kiện finish để tránh call liên tục
            if (typeof $ !== 'undefined' && $('#price-range').length) {
                const $slider = $('#price-range').data('ionRangeSlider') ? $('#price-range') : null;
                // Nếu đã init, đăng ký sự kiện finish
                if ($slider) {
                    $slider.on('change', function() {
                        const inst = $(this).data('ionRangeSlider');
                        this.dataset.from = inst.result.from;
                        this.dataset.to = inst.result.to;
                    });
                    $slider.on('finish', function() {
                        const inst = $(this).data('ionRangeSlider');
                        this.dataset.from = inst.result.from;
                        this.dataset.to = inst.result.to;
                        applyFilter();
                    });
                } else {
                    // fallback: lắng nghe change thường
                    $('#price-range').on('change', function() {
                        const inst = $(this).data('ionRangeSlider');
                        if (inst && inst.result) {
                            this.dataset.from = inst.result.from;
                            this.dataset.to = inst.result.to;
                        }
                        applyFilter();
                    });
                }
            }

            // Sắp xếp
            qsa('#sort-options .dropdown-item').forEach(item => {
                item.addEventListener('click', function() {
                    const curr = qs('#current-sort-text');
                    curr.dataset.sort = this.dataset.sort;
                    curr.textContent = this.textContent.trim();
                    applyFilter();
                });
            });

            // Pagination: event delegation để không phải rebind sau mỗi lần AJAX
            document.addEventListener('click', function(e) {
                const a = e.target.closest('.custome-pagination a');
                if (!a) return;
                e.preventDefault();
                const url = new URL(a.href);
                const page = url.searchParams.get('page') || 1;
                applyFilter({
                    page
                });
            });

            // Hỗ trợ nút Back/Forward của trình duyệt
            window.addEventListener('popstate', function() {
                // lấy param hiện tại từ URL và fetch lại (không đọc UI)
                const params = new URLSearchParams(window.location.search);
                fetchProducts(params);
            });
        });
    </script>

    <!-- Shop Section End -->
@endsection
