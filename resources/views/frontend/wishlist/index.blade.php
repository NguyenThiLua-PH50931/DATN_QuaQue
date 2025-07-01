@extends('layouts.frontend')
@section('title', 'Sản phẩm yêu thích')
@section('contents')
    <style>
        .wishlist-img {
            width: 270px;
            max-width: 100%;
            height: 270px;
            aspect-ratio: 1/1;
            object-fit: cover;
            border-radius: 20px !important;
            background: #f8f8f8;
            display: block;
        }

        .product-image {
            width: 100%;
            margin-bottom: 12px;
            height: 320px !important; /* Tăng chiều cao khung ảnh */
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .product-header {
            position: relative;
        }

        /* Ghi đè CSS mặc định nếu có xung đột */
        .product-box-3 .product-header .product-image img {
            border-radius: 20px !important;
            width: 270px !important;
            height: 270px !important;
        }

        .product-box-3 {
            min-width: 320px;
            max-width: 100%;
            min-height: 420px;
            padding-bottom: 24px;
            background: #ffffff;
            border-radius: 18px;
            box-shadow: 0 2px 12px rgba(0,0,0,0.04);
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }
        .product-header {
            margin-bottom: 18px;
        }
        .product-image {
            height: 220px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-bottom: 18px;
        }
        .product-detail .span-name {
            margin-top: 12px;
            margin-bottom: 6px;
            display: block;
            color: #b0b0b0;
            font-size: 0.95rem;
        }
        .product-detail .name {
            margin-bottom: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            color: #333;
        }
        .product-detail .unit {
            margin-bottom: 8px;
            color: #b0b0b0;
            font-size: 0.97rem;
        }
        .product-detail .price {
            margin-bottom: 16px;
            font-size: 1.1rem;
        }
        .add-to-cart-box {
            margin-top: 16px;
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
                <div class="row g-4 row-cols-xxl-3 row-cols-xl-3 row-cols-lg-3 row-cols-md-2 row-cols-1">
                    @foreach ($wishlist as $item)
                        <div class="col">
                            <div class="product-box-3 h-100" style="min-width: 320px; max-width: 100%;">
                                <div class="product-header">
                                    <div class="product-image d-flex justify-content-center align-items-center" style="height: 320px;">
                                        <a href="{{ route('client.product.detail', $item->product->slug) }}">
                                            <img src="{{ asset('storage/' . $item->product->image) }}" alt="{{ $item->product->name }}"
                                            style="width: 200px; height: 200px; object-fit: cover; border-radius: 16px; background: #f8f8f8;">
                                        </a>
                                        <div class="product-header-top position-absolute top-0 end-0 m-2">
                                            <form action="{{ route('client.wishlist.destroy', $item->product_id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn wishlist-button close_button"><i data-feather="x"></i></button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                <div class="product-footer" style="min-height: 180px;">
                                    <div class="product-detail">
                                         <a href="{{ route('client.product.detail', $item->product->slug) }}">
                                            <h5 class="name">{{ $item->product->name }}</h5>
                                        </a>
                                        <span class="span-name">{{ $item->product->category->name ?? 'Unknown' }}</span>
                                        <h6 class="unit mt-1">{{ $item->product->origin }}</h6>
                                        <h5 class="price">
                                            <span class="theme-color">
                                                {{ number_format($item->product->variants->first()->price ?? 0, 2) }}₫
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
