@extends('layouts.frontend')
@section('title', 'Sản phẩm yêu thích')
@section('contents')
    <style>
        .wishlist-img {
            width: 320px; /* Lấp đầy container cha */
            max-width: 100%; /* Đảm bảo không vượt quá kích thước cha */
            height: auto; /* Chiều cao tự động dựa trên tỷ lệ */
            aspect-ratio: 4/3; /* Duy trì tỷ lệ 4/3 */
            object-fit: cover; /* Đảm bảo ảnh không bị méo */
            border-radius: 20px !important; /* Bo góc trực tiếp cho ảnh, tăng lên 20px để rõ hơn */
            background: #f8f8f8; /* Nền mặc định nếu ảnh không tải */
            display: block; /* Loại bỏ khoảng cách không mong muốn */
        }

        .product-image {
            width: 100%; /* Đảm bảo container cha chiếm toàn bộ chiều rộng */
            margin-bottom: 12px; /* Giữ khoảng cách như yêu cầu */
        }

        .product-header {
            position: relative; /* Hỗ trợ định vị nút xóa */
        }

        /* Ghi đè CSS mặc định nếu có xung đột */
        .product-box-3 .product-header .product-image img {
            border-radius: 20px !important;
            width: 100% !important;
            height: auto !important;
        }
    </style>
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

    <!-- WISHLIST SECTION START -->
    <section class="wishlist-section section-b-space">
        <div class="container-fluid-lg">
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-4 mb-4">
                    {{ session('success') }}
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 text-red-700 p-4 mb-4">
                    {{ session('error') }}
                </div>
            @endif

            @if ($wishlist->isEmpty())
                <p class="text-center">Chưa có sản phẩm trong wishlist.</p>
            @else
                <div class="row g-sm-3 g-2">
                    @foreach ($wishlist as $item)
                        <div class="col-xxl-2 col-lg-3 col-md-4 col-6 product-box-contain">
                            <div class="product-box-3 h-100">
                                <div class="product-header">
                                    <div class="product-image">
                                        <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}" class="wishlist-img">
                                        <div class="product-header-top">
                                            <form action="{{ route('client.wishlist.destroy', $item->product_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn wishlist-button close_button">
                                                    <i data-feather="x"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-footer">
                                    <div class="product-detail">
                                        <span class="span-name">{{ $item->product->category->name ?? 'Unknown' }}</span>
                                        <a href="{{ route('client.product.detail', $item->product->slug) }}">
                                            <h5 class="name">{{ $item->product->name }}</h5>
                                        </a>
                                        <h6 class="unit mt-1">{{ $item->product->origin }}</h6>
                                        <h5 class="price">
                                            <span class="theme-color">
                                                {{ number_format($item->product->variants->first()->price ?? 0, 2) }}
                                            </span>
                                            @if ($item->product->variants->first()->original_price ?? false)
                                                <del>{{ number_format($item->product->variants->first()->original_price, 2) }}</del>
                                            @endif
                                        </h5>

                                        <div class="add-to-cart-box bg-white mt-2">
                                            <form action="#" method="POST">
                                                @csrf
                                                <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                                <input type="hidden" name="quantity" value="1">
                                                <button type="submit" class="btn btn-add-cart addcart-button">Add
                                                    <span class="add-icon bg-light-gray">
                                                        <i class="fa-solid fa-plus"></i>
                                                    </span>
                                                </button>
                                            </form>
                                            <div class="cart_qty qty-box">
                                                <div class="input-group bg-white">
                                                    <button type="button" class="qty-left-minus bg-gray" data-type="minus" data-field="">
                                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                                    </button>
                                                    <input class="form-control input-number qty-input" type="text" name="quantity" value="1">
                                                    <button type="button" class="qty-right-plus bg-gray" data-type="plus" data-field="">
                                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                {{ $wishlist->links() }}
            @endif
        </div>
    </section>
    <!-- WISHLIST SECTION END -->
@endsection
