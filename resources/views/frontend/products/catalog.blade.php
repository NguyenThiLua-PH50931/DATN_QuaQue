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
<section class="section-b-space shop-section">
    <div class="container-fluid-lg">
        <div class="row">
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

                            <div class="filter-category ">
                                <h2>Tìm kiếm</h2>
                            </div>
                            <div class="form-floating theme-form-floating-2 search-box">
                                <input
                                    type="search"
                                    class="form-control"
                                    id="search-keyword"
                                    value="{{ request('q') ?? '' }}"
                                    placeholder="Tìm sản phẩm..." />
                                <label for="product-search">Tìm sản phẩm</label>
                            </div>

                        </div>

                        {{-- <div class="filter-category">
                            <div class="filter-title">
                                <h2>Bộ lọc đang dùng</h2>
                                <a href="javascript:void(0)" id="clear-filters">Xóa tất cả</a>
                            </div>
                            <ul id="active-filters-list">
                                @if(request()->has('categories'))
                                @foreach(request('categories') as $catId)
                                @php
                                $cat = $categories->firstWhere('id', $catId);
                                @endphp
                                @if($cat)
                                <li>
                                    <a href="javascript:void(0)" class="active-filter" data-type="category" data-id="{{ $catId }}">
                        {{ $cat->name }} <span class="remove-filter">×</span>
                        </a>
                        </li>

                        @endif
                        @endforeach
                        @endif
                        <li>
                            <a href="">ádya</a>
                        </li>
                        </ul>
                    </div> --}}

                    <div class="accordion custome-accordion" id="accordionExample">
                        {{-- Danh mục --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingOne">
                                <button
                                    class="accordion-button"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseOne"
                                    aria-expanded="true"
                                    aria-controls="collapseOne">
                                    <span>Danh mục</span>
                                </button>
                            </h2>
                            <div
                                id="collapseOne"
                                class="accordion-collapse collapse show"
                                aria-labelledby="headingOne">
                                <div class="accordion-body">
                                    <div class="form-floating theme-form-floating-2 search-box">
                                        <input
                                            type="search"
                                            class="form-control"
                                            id="category-search"
                                            placeholder="Tìm danh mục .." />
                                        <label for="category-search">Tìm danh mục</label>
                                    </div>
                                    <ul class="category-list custom-padding custom-height" id="category-filter-list">
                                        @foreach ($categories as $category)
                                        <li>
                                            <div class="form-check ps-0 m-0 category-list-box">
                                                <input
                                                    class="checkbox_animated category-checkbox"
                                                    type="checkbox"
                                                    id="category-{{ $category->id }}"
                                                    value="{{ $category->id }}"
                                                    @if (is_array(request('dm')) && in_array($category->id, request('dm')))
                                                checked
                                                @endif
                                                />
                                                <label class="form-check-label" for="category-{{ $category->id }}">
                                                    <span class="name">{{ $category->name }}</span>
                                                    <span class="number">({{ $category->products_count ?? 0 }})</span>
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
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseTwo"
                                    aria-expanded="false"
                                    aria-controls="collapseTwo">
                                    <span>Vùng miền</span>
                                </button>
                            </h2>
                            <div
                                id="collapseTwo"
                                class="accordion-collapse collapse show"
                                aria-labelledby="headingTwo">
                                <div class="accordion-body">
                                    <ul class="category-list custom-padding" id="region-filter-list">
                                        @foreach ($regions as $region)
                                        <li>
                                            <div class="form-check ps-0 m-0 category-list-box">
                                                <input
                                                    class="checkbox_animated region-checkbox"
                                                    type="checkbox"
                                                    id="region-{{ $region->id }}"
                                                    value="{{ $region->id }}"
                                                    @if (is_array(request('regions')) && in_array($region->id, request('regions')))
                                                checked
                                                @endif
                                                />
                                                <label class="form-check-label" for="region-{{ $region->id }}">
                                                    <span class="name">{{ $region->name }}</span>
                                                    <span class="number">({{ $region->products_count ?? 0 }})</span>
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
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseThree"
                                    aria-expanded="false"
                                    aria-controls="collapseThree">
                                    <span>Khoảng giá</span>
                                </button>
                            </h2>
                            <div
                                id="collapseThree"
                                class="accordion-collapse collapse show"
                                aria-labelledby="headingThree">
                                <div class="accordion-body">
                                    <div class="range-slider">
                                        <input
                                            type="text"
                                            class="js-range-slider"
                                            id="price-range"
                                            value=""
                                            data-min="0"
                                            data-max="10000000"
                                            data-from="{{ request('min_price') ?? 0 }}"
                                            data-to="{{ request('max_price') ?? 10000000 }}"
                                            data-step="10000"
                                            data-prefix="₫" />
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Đánh giá --}}
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="headingSix">
                                <button
                                    class="accordion-button collapsed"
                                    type="button"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#collapseSix"
                                    aria-expanded="false"
                                    aria-controls="collapseSix">
                                    <span>Đánh giá</span>
                                </button>
                            </h2>
                            <div
                                id="collapseSix"
                                class="accordion-collapse collapse show"
                                aria-labelledby="headingSix">
                                <div class="accordion-body">
                                    <ul class="category-list custom-padding" id="rating-filter-list">
                                        @for ($star = 5; $star >= 1; $star--)
                                        <li>
                                            <div class="form-check ps-0 m-0 category-list-box">
                                                <input
                                                    class="checkbox_animated rating-checkbox"
                                                    type="checkbox"
                                                    id="rating-{{ $star }}"
                                                    value="{{ $star }}"
                                                    @if (is_array(request('rating')) && in_array($star, request('rating')))
                                                    checked
                                                    @endif />
                                                <div class="form-check-label">
                                                    <ul class="rating">
                                                        @for ($i = 1; $i <= 5; $i++)
                                                            <li>
                                                            <i data-feather="star" class="{{ $i <= $star ? 'fill' : '' }}"></i>
                                        </li>
                                        @endfor
                                    </ul>
                                    <span class="text-content">({{ $star }} sao)</span>
                                </div>
                            </div>
                            </li>
                            @endfor
                            </ul>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </div>

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
                        <button
                            class="dropdown-toggle"
                            type="button"
                            id="dropdownMenuButton1"
                            data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span id="current-sort-text" data-sort="{{ request('sort', 'pop') }}">Phổ biến nhất</span>
                            <i class="fa-solid fa-angle-down"></i>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1" id="sort-options">
                            <li><a class="dropdown-item" data-sort="pop" href="javascript:void(0)">Phổ biến nhất</a></li>
                            <li><a class="dropdown-item" data-sort="low" href="javascript:void(0)">Giá thấp đến cao</a></li>
                            <li><a class="dropdown-item" data-sort="high" href="javascript:void(0)">Giá cao đến thấp</a></li>
                            <li><a class="dropdown-item" data-sort="rating" href="javascript:void(0)">Đánh giá trung bình</a></li>
                            <li><a class="dropdown-item" data-sort="aToz" href="javascript:void(0)">A - Z</a></li>
                            <li><a class="dropdown-item" data-sort="zToa" href="javascript:void(0)">Z - A</a></li>
                        </ul>
                    </div>
                </div>

                <div class="grid-option d-none d-md-block">
                    <ul>
                        <li class="three-grid">
                            <a href="javascript:void(0)">
                                <img
                                    src="{{ asset('frontend/assets/svg/grid-3.svg') }}"
                                    class="blur-up lazyload"
                                    alt="" />
                            </a>
                        </li>
                        <li class="grid-btn d-xxl-inline-block d-none active">
                            <a href="javascript:void(0)">
                                <img
                                    src="{{ asset('frontend/assets/svg/grid-4.svg') }}"
                                    class="blur-up lazyload d-lg-inline-block d-none"
                                    alt="" />
                                <img
                                    src="{{ asset('frontend/assets/svg/grid.svg') }}"
                                    class="blur-up lazyload img-fluid d-lg-none d-inline-block"
                                    alt="" />
                            </a>
                        </li>
                        <li class="list-btn">
                            <a href="javascript:void(0)">
                                <img
                                    src="{{ asset('frontend/assets/svg/list.svg') }}"
                                    class="blur-up lazyload"
                                    alt="" />
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

        <div
            class="row g-sm-4 g-3 row-cols-xxl-4 row-cols-xl-3 row-cols-lg-2 row-cols-md-3 row-cols-2 product-list-section">

            {{-- Sản phẩm còn hàng --}}
            @forelse ($productsInStock as $product)
            <div>
                {{-- (Giữ nguyên nội dung sản phẩm như cũ) --}}
                <div class="product-box-3 h-100 wow fadeInUp" data-wow-delay="0.05s">
                    <div class="product-header">
                        <div class="product-image">
                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <img
                                    src="{{ asset('storage/'.$product->image) }}"
                                    class="img-fluid blur-up lazyload"
                                    alt="{{ $product->name }}" />
                            </a>

                            <style>
                                .product-option li {
                                    width: 50% !important;
                                }
                            </style>

                            <ul class="product-option">
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                    <a href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}" title="Xem nhanh">
                                        <i data-feather="eye"></i>
                                    </a>
                                </li>
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
                                    <form action="{{ route('client.wishlist.store') }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="notifi-wishlist btn p-0" style="border:none; background:none; width: 18px; height: 18px; margin-top: 10px">
                                            <i data-feather="heart"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="product-footer">
                        <div class="product-detail">
                            <span class="span-name">
                                {{ $product->categories->pluck('name')->join(', ') }}
                            </span>
                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <h5 class="name">
                                    {{ $product->name }}
                                </h5>
                            </a>
                            <p class="text-content mt-1 mb-2 product-content">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                            <div class="product-rating mt-2">
                                <ul class="rating">
                                    @php
                                    $avgRating = round($product->reviews->avg('rating') ?? 0);
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li>
                                        <i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i>
                                        </li>
                                        @endfor
                                </ul>
                                <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                            </div>
                            @php
                            $variantInStock = $product->variants->firstWhere('stock', '>', 0);
                            @endphp

                            @if ($variantInStock)
                            <h6 class="unit">{{ $variantInStock->name }}</h6>
                            <h5 class="price">
                                <span class="theme-color">{{ number_format($variantInStock->price, 0, ',', '.') }}₫</span>
                            </h5>
                            @else
                            <h5 class="text-danger">Hết hàng</h5>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <p>Không có sản phẩm nào phù hợp.</p>
            @endforelse

            {{-- Sản phẩm hết hàng --}}
            @foreach ($productsOutOfStock as $product)
            <div>
                {{-- Nội dung tương tự nhưng đảm bảo hiển thị hết hàng --}}
                <div class="product-box-3 h-100 wow fadeInUp" data-wow-delay="0.05s">
                    <div class="product-header">
                        <div class="product-image">
                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <img
                                    src="{{ asset('storage/'.$product->image) }}"
                                    class="img-fluid blur-up lazyload"
                                    alt="{{ $product->name }}" />
                            </a>

                            <style>
                                .product-option li {
                                    width: 50% !important;
                                }
                            </style>

                            <ul class="product-option">
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                    <a href="javascript:void(0)" class="quickview-btn" data-slug="{{ $product->slug }}" title="Xem nhanh">
                                        <i data-feather="eye"></i>
                                    </a>
                                </li>
                                <li data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
                                    <form action="{{ route('client.wishlist.store') }}" method="POST" style="display:inline-block;">
                                        @csrf
                                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                                        <button type="submit" class="notifi-wishlist btn p-0" style="border:none; background:none; width: 18px; height: 18px; margin-top: 10px">
                                            <i data-feather="heart"></i>
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="product-footer">
                        <div class="product-detail">
                            <span class="span-name">
                                {{ $product->categories->pluck('name')->join(', ') }}
                            </span>
                            <a href="{{ route('client.product.detail', $product->slug) }}">
                                <h5 class="name">
                                    {{ $product->name }}
                                </h5>
                            </a>
                            <p class="text-content mt-1 mb-2 product-content">
                                {{ Str::limit($product->description, 80) }}
                            </p>
                            <div class="product-rating mt-2">
                                <ul class="rating">
                                    @php
                                    $avgRating = round($product->reviews->avg('rating') ?? 0);
                                    @endphp
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li>
                                        <i data-feather="star" class="{{ $i <= $avgRating ? 'fill' : '' }}"></i>
                                        </li>
                                        @endfor
                                </ul>
                                <span>({{ number_format($product->reviews->avg('rating'), 1) ?? 0 }})</span>
                            </div>
                            <h5 class="text-danger">Hết hàng</h5>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>


        <nav class="custome-pagination">
            {{ $products->links() }}
        </nav>

    </div>
    </div>
    </div>
</section>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(function() {
        function getFilters() {
            return {
                q: $('#search-keyword').val() || '',
                dm: $('.category-checkbox:checked').map(function() {
                    return this.value;
                }).get(),
                vm: $('.region-checkbox:checked').map(function() {
                    return this.value;
                }).get(),
                sao: $('.rating-checkbox:checked').map(function() {
                    return this.value;
                }).get(),
                gia: $('#price-range').data('from') + '-' + $('#price-range').data('to'),
                sort: $('#current-sort-text').data('sort') || 'pop', // lấy sort từ data-sort
                page: 1,
            };
        }

        function loadProducts() {
            const filters = getFilters();

            $.ajax({
                url: '{{ route("client.product.catalog") }}',
                method: 'GET',
                data: filters,
                dataType: 'json',
                success: function(res) {
                    $('.product-list-section').html(res.html);
                    $('.custome-pagination').html(res.pagination);

                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    window.scrollTo(0, 0);
                },
                error: function() {
                    alert('Lỗi khi tải sản phẩm. Vui lòng thử lại.');
                }
            });
        }

        // Sự kiện chọn sắp xếp
        $('#sort-options a').on('click', function(e) {
            e.preventDefault();

            const sortValue = $(this).data('sort');
            const sortText = $(this).text();

            // Cập nhật text và data-sort cho nút hiện thị
            $('#current-sort-text').text(sortText).data('sort', sortValue);

            loadProducts();
        });

        // Các sự kiện khác (tìm kiếm, checkbox, khoảng giá, phân trang) bạn giữ nguyên như hiện tại
        $('#search-keyword').on('input', loadProducts);
        $(document).on('change', '.category-checkbox, .region-checkbox, .rating-checkbox', loadProducts);
        $('#price-range').on('change', loadProducts);

        $('#clear-filters').on('click', function() {
            $('#search-keyword').val('');
            $('.category-checkbox, .region-checkbox, .rating-checkbox').prop('checked', false);
            $('#price-range').data('from', 0).data('to', 10000000);
            $('#current-sort-text').text('Phổ biến nhất').data('sort', 'pop');
            loadProducts();
        });
        $(document).on('click', '.active-filter', function() {
            const type = $(this).data('type');
            const id = $(this).data('id');

            // Lấy filters hiện tại
            let filters = getFilters();

            if (type === 'category') {
                filters.dm = filters.dm.filter(item => item != id);
            }
            // Nếu bạn thêm vùng miền, sao,... tương tự ở đây

            loadProductsWithFilters(filters);
        });
        $('#clear-filters').on('click', function() {
            // reset toàn bộ filter UI (checkbox, input, khoảng giá,...)
            $('#search-keyword').val('');
            $('.category-checkbox, .region-checkbox, .rating-checkbox').prop('checked', false);
            $('#price-range').data('from', 0).data('to', 10000000);
            $('#current-sort-text').text('Phổ biến nhất').data('sort', 'pop');

            // gọi load lại sản phẩm với bộ lọc mặc định
            loadProductsWithFilters({
                q: '',
                dm: [],
                vm: [],
                sao: [],
                gia: '0-10000000',
                sort: 'pop',
                page: 1,
            });
        });

        $(document).on('click', '.custome-pagination a', function(e) {
            e.preventDefault();
            let url = $(this).attr('href');
            let page = new URLSearchParams(url.split('?')[1]).get('page') || 1;
            let filters = getFilters();
            filters.page = page;
            $.ajax({
                url: '{{ route("client.product.catalog") }}',
                method: 'GET',
                data: filters,
                dataType: 'json',
                success: function(res) {
                    $('.product-list-section').html(res.html);
                    $('.custome-pagination').html(res.pagination);
                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }
                    window.scrollTo(0, 0);
                },
                error: function() {
                    alert('Lỗi khi tải sản phẩm.');
                }
            });
        });
    });
</script>

<!-- Shop Section End -->
@endsection