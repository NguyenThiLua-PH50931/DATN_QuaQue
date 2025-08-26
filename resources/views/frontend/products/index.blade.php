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
                                <div class="filter-title d-flex align-items-center justify-content-between"
                                    style="margin-bottom: 2em;">
                                    <h2 style="font-weight: bold; font-size: 1.5rem;">Bộ lọc sản phẩm</h2>
                                </div>
                                <div class="mb-2" style="font-weight: bold; font-size: 1.5rem; margin-bottom: 2em;">Danh
                                    mục</div>
                                <ul style="max-height: 220px; overflow-y: auto; padding-right: 6px; margin-bottom: 2em;">
                                    @foreach ($categories as $cat)
                                        <li style="margin-bottom: 1.5em; display: block;">
                                            <label
                                                style="font-size: 1.1rem; font-weight: normal; display: flex; align-items: center;">
                                                <input type="radio" name="category_id" value="{{ $cat->id }}"
                                                    {{ request('category_id') == $cat->id ? 'checked' : '' }}
                                                    style="margin-right: 8px;"> {{ $cat->name }}
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
                                    @foreach ($regions as $region)
                                        <li style="margin-bottom: 1.5em; display: block;">
                                            <label
                                                style="font-size: 1.1rem; font-weight: normal; display: flex; align-items: center;">
                                                <input type="radio" name="region_id" value="{{ $region->id }}"
                                                    {{ request('region_id') == $region->id ? 'checked' : '' }}
                                                    style="margin-right: 8px;"> {{ $region->name }}
                                            </label>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                            {{-- Thanh lọc giá với noUiSlider --}}
                            <div class="filter-category mb-4">
                                <div class="filter-title" style="margin-bottom: 2em;">
                                    <h2 style="font-weight: bold; font-size: 1.5rem;">Khoảng Giá</h2> <br>
                                </div>
                                <div class="mb-2" style="margin-bottom: 2em;">
                                    <div id="noui-price-slider" class="mb-3" data-min="0" data-max="20000000"></div>
                                    <div class="d-flex align-items-center mb-2 gap-2" style="margin-bottom: 1.5em;">
                                        <input type="text" class="form-control form-control-sm js-input-from vnd-input"
                                            style="width: 120px; font-size: 1rem;" name="price_min"
                                            value="{{ request('price_min', 0) }}" placeholder="Từ (₫)">
                                        <span>-</span>
                                        <input type="text" class="form-control form-control-sm js-input-to vnd-input"
                                            style="width: 120px; font-size: 1rem;" name="price_max"
                                            value="{{ request('price_max', 1000000) }}" placeholder="Đến (₫)">
                                    </div>
                                    <div id="selected-range"
                                        style="font-size: 1rem; color: #007bff; font-weight: 500; margin-top: 0.5em;"></div>
                                </div>
                            </div>
                            <div class="filter-category mb-4" style="margin-top: 2em;">
                                <div class="filter-title" style="margin-bottom: 2em;">
                                    <h2 style="font-weight: bold; font-size: 1.5rem;">Đánh Giá</h2>
                                </div>
                                <ul style="display:block;">
                                    @for ($i = 5; $i >= 1; $i--)
                                        <li style="margin-bottom: 0.5em; display: flex; align-items: center;">
                                            <label
                                                style="font-size: 1.1rem; font-weight: normal; display: flex; align-items: center; gap: 8px;">
                                                <input type="radio" name="rating" value="{{ $i }}"
                                                    {{ request('rating') == $i ? 'checked' : '' }}>
                                                <span>
                                                    @for ($j = 1; $j <= 5; $j++)
                                                        <i class="fa fa-star{{ $j <= $i ? ' text-warning' : '' }}"></i>
                                                    @endfor
                                                </span>
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
                                @foreach (request()->except('sort', 'page') as $key => $val)
                                    <input type="hidden" name="{{ $key }}" value="{{ $val }}">
                                @endforeach
                                <select name="sort" class="form-select d-inline w-auto"
                                    onchange="document.getElementById('sort-form').submit()">
                                    <option value="" {{ !request('sort') ? 'selected' : '' }}>Mặc định</option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá
                                        tăng
                                        dần</option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá
                                        giảm dần</option>
                                    <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>Đánh giá
                                        cao
                                    </option>
                                    <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Tên A-Z
                                    </option>
                                    <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Tên
                                        Z-A
                                    </option>
                                </select>
                            </form>
                        </div>
                    </div>
                </div>
                <div
                    class="row g-sm-4 g-3 row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1 product-list-section">
                    @forelse($products as $product)
                        @php
                            // Xác định biến thể còn hàng đầu tiên (nếu có)
                            $variantInStock = $product->has_variants
                                ? $product->variants->where('stock', '>', 0)->where('active', 1)->first()
                                : $product->variants->where('stock', '>', 0)->where('active', 1)->first();
                            $isOutOfStock = false;
                            if ($product->has_variants) {
                                $isOutOfStock =
                                    $product->variants->where('stock', '>', 0)->where('active', 1)->count() == 0;
                            } else {
                                $isOutOfStock = !$variantInStock || $variantInStock->stock == 0;
                            }
                            $minPrice = $product->variants->min('price');
                        @endphp
                        <div class="col">
                            <div class="product-box-3 h-100 wow fadeInUp">
                                <div class="product-header">
                                    <div class="product-image product-image-rounded position-relative">
                                        <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="img-fluid blur-up lazyload product-img-large"
                                                alt="{{ $product->name }}"
                                                style="width: 300px; height: 230px; object-fit: cover; border-radius: 24px;">
                                        </a>
                                        @if ($isOutOfStock)
                                            <div class="sold-out-ribbon-center">
                                                <span
                                                    style="margin-right:10px; font-size:2.2rem; line-height:1;">&#9888;</span>
                                                <span style="font-size:2rem; font-weight:900; letter-spacing:3px;">BÁN
                                                    HẾT</span>
                                            </div>
                                        @endif
                                        <style>
                                            .product-option li {
                                                width: 50% !important;
                                            }

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

                                            /* Tiêu đề: kẹp số dòng để chiều cao đồng đều */
                                            .product-box-3 .product-detail .name {
                                                display: -webkit-box;
                                                -webkit-line-clamp: 2;
                                                /* 2 dòng, đổi 3 nếu muốn */
                                                -webkit-box-orient: vertical;
                                                overflow: hidden;
                                                line-height: 1.35;
                                                min-height: calc(1em * 2 * 1.35);
                                            }

                                            /* Giá đẩy xuống đáy */
                                            .product-box-3 .product-detail .price {
                                                margin-top: auto;
                                            }

                                            /* ==== ẢNH: vuông, thu nhỏ bên trong ==== */
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
                                                inset: 8px;
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

                                            /* ==== Badge TOP (tuỳ chọn) ==== */
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

                                            /* Đảm bảo các button vẫn có thể click được */
                                            .product-box-3 .product-option {
                                                z-index: 20;
                                                position: relative;
                                            }

                                            .product-box-3 .product-option a {
                                                background: rgba(255, 255, 255, 0.95);
                                                border-radius: 50%;
                                                transition: all 0.3s ease;
                                                box-shadow: 0 2px 8px rgba(0,0,0,0.2);
                                            }

                                            .product-box-3 .product-option a:hover {
                                                background: rgba(255, 255, 255, 1);
                                                transform: scale(1.1);
                                                box-shadow: 0 4px 12px rgba(0,0,0,0.3);
                                            }
                                        </style>
                                        <ul class="product-option">
                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="View">
                                                <a href="javascript:void(0)" class="quickview-btn"
                                                    data-slug="{{ $product->slug }}" title="Xem nhanh">
                                                    <i data-feather="eye"></i>
                                                </a>
                                            </li>

                                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Yêu thích">
                                                <a href="javascript:void(0)"
                                                    class="notifi-wishlist wishlist-btn
                                                    @if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists()) fill-heart @endif"
                                                    data-product-id="{{ $product->id }}"
                                                    data-liked="@if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists())1 @else 0 @endif"
                                                    title="Yêu thích"
                                                    style="width:18px; height:18px; display:inline-flex; align-items:center; justify-content:center; color:#4a5568; margin-top:10px;">
                                                    <i data-feather="heart"></i>
                                                </a>
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
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li><i data-feather="star"
                                                            class="{{ $i <= $avg ? 'fill' : '' }}"></i></li>
                                                @endfor
                                            </ul>
                                            <span>({{ number_format($product->reviews->avg('rating'), 1) }})</span>
                                        </div>
                                        @if (!$isOutOfStock)
                                            <h6 class="unit">{{ $variantInStock->weight ?? '' }}</h6>
                                            <h5 class="price"><span
                                                    class="theme-color">{{ number_format($variantInStock ? $variantInStock->price : $minPrice) }}₫</span>
                                            </h5>
                                        @else
                                            <h3 class="text-danger text-center">Hết hàng</h3>
                                        @endif
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css">
    <style>
        .product-image.product-image-rounded {
            border-radius: 24px !important;
            overflow: hidden;
            width: 200px;
            height: 100px;
            margin: 0 auto;
            background: #f8f8f8;
            position: relative;
        }

        .product-img-large {
            width: 200px !important;
            height: 100px !important;
            object-fit: cover;
            border-radius: 24px !important;
        }

        .sold-out-ribbon-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background: rgba(255, 255, 255, 0.85);
            padding: 12px 24px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            z-index: 10;
            box-shadow: 0 2px 8px #0002;
        }

        .filter-category ul {
            padding-left: 0;
            list-style: none;
        }

        #price-slider {
            margin-bottom: 1.5em !important;
        }

        #noui-price-slider {
            margin-top: 0;
            margin-bottom: 1em;
        }

        #noui-price-slider .noUi-tooltip {
            top: unset !important;
            bottom: -48px !important;
            transform: none !important;
        }

        .noUi-target {
            background: #e0e0e0;
            border-radius: 8px;
            box-shadow: 0 2px 8px #0001;
        }

        .noUi-connect {
            background: #1abc9c;
        }

        .noUi-handle {
            background: #1abc9c;
            border: 2px solid #fff;
            box-shadow: 0 2px 8px #0002;
        }

        .noUi-tooltip {
            background: #1abc9c;
            color: #fff;
            border-radius: 6px;
            font-weight: bold;
            font-size: 1rem;
            padding: 4px 12px;
        }

        /* Quickview Modal Custom Styles */
        #quickviewModal .main-image-wrapper {
            width: 350px;
            height: 350px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f8f8f8;
            border-radius: 16px;
            overflow: hidden;
            margin: 0 auto;
        }

        #quickviewModal .main-quickview-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 16px;
            display: block;
            background: #f8f8f8;
        }

        #quickviewModal .description-thumbnails {
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            margin-top: 16px;
            gap: 8px;
            flex-wrap: wrap;
        }

        #quickviewModal .description-thumbnails img {
            width: 60px;
            height: 60px;
            object-fit: cover;
            border-radius: 8px;
            border: 2px solid #eee;
            cursor: pointer;
            margin-right: 0;
            transition: border 0.2s, box-shadow 0.2s;
            background: #f8f8f8;
        }

        #quickviewModal .description-thumbnails img.active {
            border: 2px solid #0da487;
            box-shadow: 0 0 0 2px #0da48733;
        }

        #quickviewModal .description-thumbnails span {
            color: #888;
            font-size: 15px;
        }

        #quickviewModal .show-more-btn {
            color: #0da487;
            font-size: 17px;
            font-weight: 500;
            margin-top: 4px;
            cursor: pointer;
            background: none;
            border: none;
            text-decoration: underline;
            display: inline-block;
        }

        .desc-thumb.active {
            border: 2px solid #ff6f61 !important;
            box-shadow: 0 0 0 2px #ff6f6133;
        }

        .description-thumbnails img {
            transition: border 0.2s, box-shadow 0.2s;
        }

        .modal-backdrop.show {
            z-index: 1050;
        }

        .modal-backdrop {
            opacity: 0.5 !important;
        }
    </style>
@endpush

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/wnumb@1.2.0/wNumb.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var priceSlider = document.getElementById('noui-price-slider');
            var inputFrom = document.querySelector('.js-input-from');
            var inputTo = document.querySelector('.js-input-to');
            var min = 0;
            var max = 20000000;
            var from = Number(inputFrom.value.replace(/\D/g, '')) || 0;
            var to = Number(inputTo.value.replace(/\D/g, '')) || 1000000;

            // Flag để tránh vòng lặp vô hạn
            var isUpdatingFromSlider = false;

            noUiSlider.create(priceSlider, {
                start: [from, to],
                connect: true,
                step: 1,
                range: {
                    'min': min,
                    'max': max
                },
                tooltips: [
                    wNumb({
                        decimals: 0,
                        thousand: '.',
                        suffix: '₫',
                        prefix: ''
                    }),
                    wNumb({
                        decimals: 0,
                        thousand: '.',
                        suffix: '₫',
                        prefix: ''
                    })
                ],
                format: wNumb({
                    decimals: 0,
                    thousand: '',
                    suffix: '',
                    prefix: ''
                })
            });

            function updateSelectedRange(v1, v2) {
                document.getElementById('selected-range').textContent =
                    'Đang chọn: ' + v1.toLocaleString('vi-VN') + '₫ - ' + v2.toLocaleString('vi-VN') + '₫';
            }

            // Sự kiện khi kéo slider
            priceSlider.noUiSlider.on('update', function(values, handle) {
                if (!isUpdatingFromSlider) {
                    isUpdatingFromSlider = true;
                    var v1 = parseInt(values[0]);
                    var v2 = parseInt(values[1]);
                    inputFrom.value = v1;
                    inputTo.value = v2;
                    updateSelectedRange(v1, v2);
                    // Reset flag sau một khoảng thời gian ngắn
                    setTimeout(function() {
                        isUpdatingFromSlider = false;
                    }, 100);
                }
            });

            // Sự kiện khi kết thúc kéo slider
            priceSlider.noUiSlider.on('change', function(values, handle) {
                isUpdatingFromSlider = false;
            });

            function syncSliderFromInput() {
                if (!isUpdatingFromSlider) {
                    isUpdatingFromSlider = true;
                    var v1 = parseInt(inputFrom.value.replace(/\D/g, ''));
                    var v2 = parseInt(inputTo.value.replace(/\D/g, ''));
                    if (isNaN(v1)) v1 = min;
                    if (isNaN(v2)) v2 = max;
                    // Ép về min/max nếu nhập ngoài khoảng
                    v1 = Math.max(min, Math.min(v1, max));
                    v2 = Math.max(min, Math.min(v2, max));
                    // Nếu min > max, hoán đổi
                    if (v1 > v2) {
                        var tmp = v1;
                        v1 = v2;
                        v2 = tmp;
                    }
                    priceSlider.noUiSlider.set([v1, v2]);
                    updateSelectedRange(v1, v2);
                    // Reset flag sau một khoảng thời gian ngắn
                    setTimeout(function() {
                        isUpdatingFromSlider = false;
                    }, 100);
                }
            }

            function updateSelectedRangeFromInput() {
                if (!isUpdatingFromSlider) {
                    var v1 = parseInt(inputFrom.value.replace(/\D/g, ''));
                    var v2 = parseInt(inputTo.value.replace(/\D/g, ''));
                    if (isNaN(v1)) v1 = min;
                    if (isNaN(v2)) v2 = max;
                    updateSelectedRange(v1, v2);
                }
            }

            inputFrom.addEventListener('input', updateSelectedRangeFromInput);
            inputTo.addEventListener('input', updateSelectedRangeFromInput);
            inputFrom.addEventListener('change', syncSliderFromInput);
            inputTo.addEventListener('change', syncSliderFromInput);
            inputFrom.addEventListener('blur', syncSliderFromInput);
            inputTo.addEventListener('blur', syncSliderFromInput);

            // Khi submit form, nếu inputTo rỗng thì không giới hạn max
            var form = document.getElementById('product-filter-form');
            if (form) {
                form.addEventListener('submit', function() {
                    if (!inputTo.value || isNaN(parseInt(inputTo.value))) {
                        inputTo.value = '';
                    }
                });
            }
        });
    </script>
@endpush
