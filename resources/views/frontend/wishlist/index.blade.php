@extends('layouts.frontend')
@section('title', 'Sản phẩm yêu thích')
@section('contents')


    <!-- BREADCRUMB SECTION START -->
    <section class="breadscrumb-section pt-0">
        <div class="container-fluid-lg">
            <div class="row">
                <div class="col-12">
                    <div class="breadscrumb-contain">
                        <h2>Sản phẩm yêu thích của bạn</h2>
                        <nav>
                            <ol class="breadcrumb mb-0">
                                <li class="breadcrumb-item">
                                    <a href="{{ route('client.home') }}">
                                        <i class="fa-solid fa-house"></i>
                                    </a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">Trang sản phẩm yêu thích</li>
                            </ol>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- BREADCRUMB SECTION END -->
    <style>
        /* Reset và cải thiện layout cơ bản */
        .wishlist-section {
            padding: 20px 0;
        }

        .wishlist-section .container-fluid-lg {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 15px;
        }

        /* Cải thiện grid system */
        .wishlist-section .row {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start !important;
            margin: 0 -10px;
        }

        .wishlist-section .col-xxl-2 {
            flex: 0 0 auto;
            width: calc(16.666667% - 20px);
            margin: 0 10px 20px 10px;
            min-width: 200px;
        }

        /* Responsive breakpoints */
        @media (max-width: 1400px) {
            .wishlist-section .col-xxl-2 {
                width: calc(20% - 20px);
            }
        }

        @media (max-width: 1200px) {
            .wishlist-section .col-xxl-2 {
                width: calc(25% - 20px);
            }
        }

        @media (max-width: 992px) {
            .wishlist-section .col-xxl-2 {
                width: calc(33.333333% - 20px);
            }
        }

        @media (max-width: 768px) {
            .wishlist-section .col-xxl-2 {
                width: calc(50% - 20px);
            }
        }

        @media (max-width: 576px) {
            .wishlist-section .col-xxl-2 {
                width: calc(100% - 20px);
            }
        }
    </style>
    <!-- WISHLIST SECTION START -->
    <section class="wishlist-section section-b-space">
        <div class="container-fluid-lg">
            @if ($wishlist->isEmpty())
                <p class="text-center">Chưa có sản phẩm trong wishlist.</p>
            @else
                <div class="row g-sm-3 g-2">
                    @foreach ($wishlist as $item)
                        @php
                            $product = $item->product;
                            $variants = $product->variants ?? collect();
                            $variantCount = $variants->count();
                            $firstVariant = $variants->first();
                        @endphp
                        <div class="col-xxl-2 product-box-contain">
                            <div class="product-box-3" style="height: 310px;">
                                <div class="product-header">
                                    <div class="product-image">
                                        <a href="{{ route('client.product.detail', $product->slug) }}">
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                class="img-fluid blur-up lazyloaded" alt="{{ $product->name }}">
                                        </a>
                                        <div class="product-header-top">
                                            <form class="wishlist-delete-form" data-product-id="{{ $item->product_id }}"
                                                action="{{ route('client.wishlist.destroy.main', $item->product_id) }}"
                                                method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn wishlist-button close_button"
                                                    title="Xóa khỏi wishlist">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                                        class="feather feather-x">
                                                        <line x1="18" y1="6" x2="6" y2="18">
                                                        </line>
                                                        <line x1="6" y1="6" x2="18" y2="18">
                                                        </line>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-footer">
                                    <div class="product-detail">
                                        @php
                                            $categories = $product->categories ?? collect();
                                            $categoryNames = $categories->pluck('name')->take(2)->toArray();
                                            $categoryString = implode(', ', $categoryNames);
                                        @endphp

                                        <span class="span-name">
                                            {{ $categoryString }}
                                            @if ($categories->count() > 2)
                                                , ...
                                            @endif
                                            @if ($categories->count() == 0)
                                                Chưa phân loại
                                            @endif
                                        </span>

                                        <a href="{{ route('client.product.detail', $product->slug) }}">
                                            <h5 class="name">{{ $product->name }}</h5>
                                        </a>
                                        <h6 class="unit mt-1">
                                            @if ($variantCount > 0)
                                                {{ $firstVariant->name ?? '' }}
                                            @else
                                                Không có thông tin
                                            @endif
                                        </h6>
                                        <h5 class="price">
                                            <span class="theme-color">
                                                @if ($variantCount > 0)
                                                    {{ number_format($firstVariant->price ?? 0, 0, ',', '.') }}₫
                                                @else
                                                    Đang cập nhật
                                                @endif
                                            </span>
                                        </h5>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <div class="mt-3">
                    {{ $wishlist->links() }}
                </div>
            @endif
        </div>
    </section>
    <!-- WISHLIST SECTION END -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.wishlist-delete-form').forEach(function(form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    let productId = form.dataset.productId;
                    let url = form.action;
                    let productBox = form.closest('.product-box-3');

                    // Gửi AJAX DELETE
                    fetch(url, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json'
                            },
                            body: new URLSearchParams({
                                _method: 'DELETE',
                                _token: form.querySelector('input[name=_token]').value
                            })
                        })
                        .then(res => res.json())
                        .then(data => {
                            if (data.success) {
                                // Ẩn box wishlist
                                productBox.classList.add('animate__animated',
                                    'animate__fadeOut');
                                setTimeout(() => {
                                    productBox.closest('.col-xxl-2').remove();
                                }, 400);
                                // Hiển thị toast thành công
                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: data.message || 'Đã xóa khỏi wishlist!',
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            } else {
                                throw new Error(data.message || 'Lỗi khi xoá!');
                            }
                        })
                        .catch(err => {
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'error',
                                title: err.message || 'Có lỗi xảy ra!',
                                showConfirmButton: false,
                                timer: 2000
                            });
                        });
                });
            });
        });
    </script>
    <style>
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

        /* Giữ chân card đều nhau khi tên dài/ngắn khác nhau (tuỳ chọn) */
        .product-box-3 {
            display: flex;
            flex-direction: column;
        }

        .product-box-3 .product-footer {
            margin-top: auto;
        }

        /* (Tuỳ chọn) hạn chế số dòng tên để không kéo card cao quá mức */
        .product-detail .name {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Cải thiện product box để ổn định ở mọi zoom */
        .wishlist-section .product-box-3 {
            display: flex;
            flex-direction: column;
            height: auto !important;
            min-height: 350px;
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .wishlist-section .product-box-3:hover {
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Cải thiện product header */
        .wishlist-section .product-header {
            position: relative;
            flex-shrink: 0;
        }

        /* Cải thiện product footer */
        .wishlist-section .product-footer {
            flex: 1;
            display: flex;
            flex-direction: column;
            padding: 15px;
            background: #fff;
        }

        .wishlist-section .product-detail {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        /* Cải thiện text alignment */
        .wishlist-section .span-name {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
            text-align: center;
        }

        .wishlist-section .name {
            font-size: 14px;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 8px;
            text-align: center;
            line-height: 1.3;
            min-height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .wishlist-section .unit {
            font-size: 13px;
            color: #6b7280;
            margin-bottom: 10px;
            text-align: center;
        }

        .wishlist-section .price {
            text-align: center;
            margin-top: auto;
        }

        .wishlist-section .price .theme-color {
            font-size: 16px;
            font-weight: 700;
            color: #059669;
        }

        /* Cải thiện close button */
        .wishlist-section .close_button {
            position: absolute;
            top: 8px;
            right: 8px;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
            transition: all 0.2s ease;
        }

        .wishlist-section .close_button:hover {
            background: #ef4444;
            color: white;
        }

        .wishlist-section .close_button svg {
            width: 16px;
            height: 16px;
        }

        /* Fix cho mọi mức zoom */
        @media screen and (min-resolution: 120dpi) {
            .wishlist-section .col-xxl-2 {
                width: calc(16.666667% - 20px);
            }
        }

        @media screen and (min-resolution: 96dpi) {
            .wishlist-section .col-xxl-2 {
                width: calc(16.666667% - 20px);
            }
        }

        /* Đảm bảo layout ổn định ở mọi zoom level */
        @media (min-width: 1400px) {
            .wishlist-section .container-fluid-lg {
                max-width: 1600px;
            }
        }

        @media (min-width: 1600px) {
            .wishlist-section .container-fluid-lg {
                max-width: 1800px;
            }
        }

        /* Fix cho zoom 100% trở lên */
        @media screen and (min-resolution: 1.1) {
            .wishlist-section .row {
                justify-content: flex-start !important;
            }

            .wishlist-section .col-xxl-2 {
                flex: 0 0 auto;
                width: calc(16.666667% - 20px);
            }
        }
    </style>

@endsection
