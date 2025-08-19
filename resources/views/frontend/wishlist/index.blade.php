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
        .row {
            justify-content: left !important;
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
                                                action="{{ route('client.wishlist.destroy', $item->product_id) }}"
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
        /* Ảnh đồng nhất: mặc định vuông, crop giữa */
        .wishlist-section .product-image {
            width: 100%;
            aspect-ratio: 1 / 1;
            /* Vuông, có thể đổi 4/3 nếu thích */
            overflow: hidden;
            position: relative;
            border-radius: 8px;
            background: #f7f7f7;
            /* nền xám nhạt khi ảnh load */
        }

        .wishlist-section .product-image>a {
            display: block;
            height: 100%;
        }

        .wishlist-section .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            /* lấp đầy khung, cắt phần thừa */
            object-position: center;
            display: block;
        }

        /* Tùy chọn: trên màn hình lớn dùng tỷ lệ 4:3 (nếu thích) */
        @media (min-width: 1200px) {
            .wishlist-section .product-image {
                aspect-ratio: 4 / 3;
            }
        }

        /* Fallback cho trình duyệt cũ không hỗ trợ aspect-ratio */
        @supports not (aspect-ratio: 1 / 1) {
            .wishlist-section .product-image {
                position: relative;
            }

            .wishlist-section .product-image::before {
                content: "";
                display: block;
                padding-top: 100%;
                /* 1:1 */
            }

            .wishlist-section .product-image>a {
                position: absolute;
                inset: 0;
            }
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

        /* Bạn đang dùng justify-content: left; nên đổi về flex-start chuẩn */
        .row {
            justify-content: flex-start !important;
        }
    </style>

@endsection
