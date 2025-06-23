@extends('layouts.frontend')

@section('title', 'Tất cả sản phẩm')

@section('contents')
<div class="container py-4">
    <h2 class="mb-4">Tất cả sản phẩm</h2>
    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-3 col-sm-6 col-12 mb-4">
                <div class="product-box" style="position: relative;">
                    <div class="label-tagg">
                        <span>HOT</span>
                    </div>
                    <div class="product-image">
                        <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}">
                        </a>
                        <ul class="product-option">
                            <li data-bs-toggle="tooltip" data-bs-placement="top" title="Xem nhanh">
                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#view">
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
                                        <i data-feather="heart"
                                           @if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists())
                                               class="text-red-500"
                                           @endif></i>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    <div class="product-detail">
                        <a href="{{ route('client.product.detail', ['slug' => $product->slug]) }}">
                            <h6 class="name">{{ $product->name }}</h6>
                        </a>
                        <h5 class="sold text-content">
                            <span class="theme-color price">
                                {{ number_format(optional($product->variants->first())->price ?? 0) }}₫
                            </span>
                        </h5>
                        <div class="product-rating mt-sm-2 mt-1">
                            <ul class="rating">
                                <li><i data-feather="star" class="fill"></i></li>
                                <li><i data-feather="star" class="fill"></i></li>
                                <li><i data-feather="star" class="fill"></i></li>
                                <li><i data-feather="star" class="fill"></i></li>
                                <li><i data-feather="star"></i></li>
                            </ul>
                            <h6 class="theme-color">In Stock</h6>
                        </div>
                        <div class="add-to-cart-box">
                            <button class="btn btn-add-cart addcart-button">
                                Add
                                <span class="add-icon"><i class="fa-solid fa-plus"></i></span>
                            </button>
                            <div class="cart_qty qty-box">
                                <div class="input-group">
                                    <button type="button" class="qty-left-minus" data-type="minus">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                    <input class="form-control input-number qty-input" type="text" value="0">
                                    <button type="button" class="qty-right-plus" data-type="plus">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> <!-- .product-box -->
            </div>
        @empty
            <p>Không có sản phẩm nào.</p>
        @endforelse
    </div>
</div>
@endsection
