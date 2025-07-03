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
<div class="container-fluid-lg">
    <div class="row">
        <!-- Sidebar Filter -->
        <div class="col-lg-3 col-md-4 mb-4 mb-md-0">
            <div class="left-box wow fadeInUp">
                <div class="shop-left-sidebar">
                    <form method="GET" action="" id="product-filter-form">
                        <div class="filter-category mb-4">
                            <div class="filter-title d-flex align-items-center justify-content-between" style="margin-bottom: 2em;">
                                <h2 style="font-weight: bold; font-size: 1.5rem;">Bộ lọc sản phẩm</h2>
                            </div>
                            <div class="mb-2" style="font-weight: bold; font-size: 1.5rem; margin-bottom: 2em;">Danh mục</div>
                            <ul style="max-height: 220px; overflow-y: auto; padding-right: 6px; margin-bottom: 2em;">
                                @foreach($categories as $cat)
                                    <li style="margin-bottom: 1.5em; display: block;">
                                        <label style="font-size: 1.1rem; font-weight: normal; display: flex; align-items: center;">
                                            <input type="radio" name="category_id" value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'checked' : '' }} style="margin-right: 8px;"> {{ $cat->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="filter-category mb-4" style="margin-top: 2em;">
                            <div class="filter-title" style="margin-bottom: 2em;">
                                <h2 style="font-weight: bold; font-size: 1.5rem;">Vùng miền</h2>
                            </div>
                            <ul style="max-height: 220px; overflow-y: auto; padding-right: 6px; margin-bottom: 2em;">
                                @foreach($regions as $region)
                                    <li style="margin-bottom: 1.5em; display: block;">
                                        <label style="font-size: 1.1rem; font-weight: normal; display: flex; align-items: center;">
                                            <input type="radio" name="region_id" value="{{ $region->id }}" {{ request('region_id') == $region->id ? 'checked' : '' }} style="margin-right: 8px;"> {{ $region->name }}
                                        </label>
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                        <div class="filter-category mb-4">
                            <div class="filter-title" style="margin-bottom: 2em;">
                                <h2 style="font-weight: bold; font-size: 1.5rem;">Khoảng Giá</h2>
                            </div>
                            <div class="mb-2" style="margin-bottom: 2em;">
                                <div class="range-slider" style="margin: 0 0 1.5em 0; min-height: 30px;">
                                    <input type="text" class="js-range-slider" value="">
                                </div>
                                <div class="d-flex align-items-center mb-2 gap-2" style="margin-bottom: 1.5em;">
                                    <input type="text" class="form-control form-control-sm js-input-from" style="width: 120px; font-size: 1rem;" name="price_min" value="{{ number_format(request('price_min', 0), 0, ',', '.') }}" placeholder="Từ">
                                    <span>-</span>
                                    <input type="text" class="form-control form-control-sm js-input-to" style="width: 120px; font-size: 1rem;" name="price_max" value="{{ number_format(request('price_max', 10000000), 0, ',', '.') }}" placeholder="Đến">
                                </div>
                            </div>
                        </div>
                        <div class="filter-category mb-4" style="margin-top: 2em;">
                            <div class="filter-title" style="margin-bottom: 2em;">
                                <h2 style="font-weight: bold; font-size: 1.5rem;">Đánh Giá</h2>
                            </div>
                            <ul>
                                @for($i=5; $i>=1; $i--)
                                    <li>
                                        <label style="font-size: 1.1rem; font-weight: normal;">
                                            <input type="radio" name="rating" value="{{ $i }}" {{ request('rating') == $i ? 'checked' : '' }}>
                                            @for($j=1; $j<=5; $j++)
                                                <i class="fa fa-star{{ $j <= $i ? ' text-warning' : '' }}"></i>
                                            @endfor
                                            trở lên
                                        </label>
                                    </li>
                                @endfor
                            </ul>
                        </div>
                        <div class="d-flex align-items-center gap-2 mt-3">
                            <button type="submit" class="btn btn-primary w-100">Lọc</button>
                            <a href="{{ route('client.product.index') }}" class="btn btn-outline-secondary">Bỏ lọc</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <!-- End Sidebar Filter -->

        <!-- Product List -->
        <div class="col-lg-9 col-md-8">
            <div class="show-button">
                <div class="top-filter-menu d-flex align-items-center justify-content-between mb-3">
                    <div class="category-dropdown">
                        <h5 class="text-content">Sắp xếp:</h5>
                        <form method="GET" id="sort-form">
                            @foreach(request()->except('sort', 'page') as $key => $val)
                                <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                            @endforeach
                            <select name="sort" class="form-select d-inline w-auto" onchange="document.getElementById('sort-form').submit()">
                                <option value="" {{ !request('sort') ? 'selected' : '' }}>Mặc định</option>
                                <option value="price_asc" {{ request('sort')=='price_asc' ? 'selected' : '' }}>Giá tăng dần</option>
                                <option value="price_desc" {{ request('sort')=='price_desc' ? 'selected' : '' }}>Giá giảm dần</option>
                                <option value="rating" {{ request('sort')=='rating' ? 'selected' : '' }}>Đánh giá cao</option>
                                <option value="name_asc" {{ request('sort')=='name_asc' ? 'selected' : '' }}>Tên A-Z</option>
                                <option value="name_desc" {{ request('sort')=='name_desc' ? 'selected' : '' }}>Tên Z-A</option>
                            </select>
                        </form>
                    </div>
                </div>
            </div>
            <div class="row g-sm-4 g-3 row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 product-list-section">
                @forelse($products as $product)
                    <div class="col">
                        <div class="product-box-3 h-100 wow fadeInUp">
                            <div class="product-header">
                                <div class="product-image product-image-rounded">
                                    <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                                        <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid blur-up lazyload product-img-large"
                                        alt="{{ $product->name }}" style="width: 300px; height: 230px; object-fit: cover; border-radius: 24px;">
                                    </a>
                                    <ul class="product-option">
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Xem nhanh">
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view"
                                               class="quickview-btn"
                                               data-name="{{ $product->name }}"
                                               data-price="{{ number_format(optional($product->variants->first())->price ?? 0) }}đ"
                                               data-rating="{{ $product->reviews->avg('rating') ?? '' }}"
                                               data-description="{!! $product->description !!}"
                                               data-code="{{ $product->variants->first()->sku ?? '' }}"
                                               data-origin="{{ $product->origin ?? '' }}"
                                               data-image="{{ asset('storage/' . $product->image) }}"
                                               data-link="{{ route('client.product.detail', ['slug' => $product->slug]) }}"
                                               data-description-images='@json(
                                                    collect([$product->image])
                                                        ->merge($product->images?->pluck("image_url") ?? [])
                                                        ->filter(fn($img) => !empty($img))
                                                        ->map(fn($img) => asset('storage/' . $img))
                                                        ->values()
                                                        ->toArray()
                                                )'>
                                                <i data-feather="eye"></i>
                                            </a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="So sánh">
                                            <a href="{{ url('compare') }}"><i data-feather="refresh-cw"></i></a>
                                        </li>
                                        <li data-bs-toggle="tooltip" data-bs-placement="top" title="Thêm yêu thích">
                                            <form action="{{ route('client.wishlist.store') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $product->id }}">
                                                <button type="submit" class="notifi-wishlist btn p-0">
                                                    <i data-feather="heart" @if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) class="text-red-500" @endif></i>
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
                                            @php $avg = round($product->reviews->avg('rating')); @endphp
                                            @for($i=1;$i<=5;$i++)
                                                <li><i data-feather="star" class="{{ $i <= $avg ? 'fill' : '' }}"></i></li>
                                            @endfor
                                        </ul>
                                        <span>({{ number_format($product->reviews->avg('rating'),1) }})</span>
                                    </div>
                                    <h6 class="unit">{{ $product->variants->first()->weight ?? '' }}</h6>
                                    <h5 class="price"><span class="theme-color">{{ number_format(optional($product->variants->first())->price ?? 0) }}₫</span></h5>
                                    <div class="add-to-cart-box bg-white">
                                        <button class="btn btn-add-cart addcart-button">Add
                                            <span class="add-icon bg-light-gray">
                                                <i class="fa-solid fa-plus"></i>
                                            </span>
                                        </button>
                                        <div class="cart_qty qty-box">
                                            <div class="input-group bg-white">
                                                <button type="button" class="qty-left-minus bg-gray" data-type="minus" data-field="">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                                <input class="form-control input-number qty-input" type="text" name="quantity" value="0">
                                                <button type="button" class="qty-right-plus bg-gray" data-type="plus" data-field="">
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
                    <p>Không có sản phẩm nào.</p>
                @endforelse
            </div>
            <br>
        <!-- Pagination -->
           <div class="d-flex justify-content-center">
            {{ $products->links('pagination::bootstrap-4') }}
        </div>
        </div>
        <!-- End Product List -->
    </div>
</div>
@endsection

@push('styles')
<style>
    .product-image.product-image-rounded {
        border-radius: 24px !important;
        overflow: hidden;
        width: 200px;
        height: 100px;
        margin: 0 auto;
        background: #f8f8f8;
    }
    .product-img-large {
        width: 200px !important;
        height: 100px !important;
        object-fit: cover;
        border-radius: 24px !important;
    }
    .filter-category ul {
        padding-left: 0;
        list-style: none;
    }
    #price-slider .noUi-target {
        width: 100% !important;
        min-width: 180px;
        margin: 0 auto;
    }
    #price-slider {
        margin-bottom: 1.5em !important;
    }
</style>
@endpush

@push('scripts')
<script src="{{ asset('frontend/assets/js/ion.rangeSlider.min.js') }}"></script>
<script>
    function formatVND(n) {
        n = parseInt(n) || 0;
        return n.toLocaleString('vi-VN') + '₫';
    }
    $(function () {
        var $range = $(".js-range-slider"),
            $inputFrom = $(".js-input-from"),
            $inputTo = $(".js-input-to"),
            min = 0,
            max = 10000000,
            from = Number($inputFrom.val().replace(/\D/g, '')) || min,
            to = Number($inputTo.val().replace(/\D/g, '')) || max;

        $range.ionRangeSlider({
            type: "double",
            min: min,
            max: max,
            from: from,
            to: to,
            step: 10000,
            prettify_enabled: true,
            prettify_separator: ".",
            values_separator: " - ",
            force_edges: true,
            postfix: '₫',
            onStart: updateInputs,
            onChange: updateInputs
        });
        var instance = $range.data("ionRangeSlider");
        function updateInputs(data) {
            from = data.from;
            to = data.to;
            $inputFrom.val(formatVND(from));
            $inputTo.val(formatVND(to));
        }
        $inputFrom.on("input", function () {
            var val = +$(this).val().replace(/\D/g, '');
            if (val < min) val = min;
            if (val > to) val = to;
            instance.update({ from: val });
            $(this).val(formatVND(val));
        });
        $inputTo.on("input", function () {
            var val = +$(this).val().replace(/\D/g, '');
            if (val < from) val = from;
            if (val > max) val = max;
            instance.update({ to: val });
            $(this).val(formatVND(val));
        });
    });
</script>
@endpush
