@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <!-- Sidebar với bộ lọc -->
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h5>Bộ lọc sản phẩm</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('products.index') }}" method="GET">
                        <!-- Tìm kiếm -->
                        <div class="mb-3">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}">
                        </div>

                        <!-- Lọc theo danh mục -->
                        <div class="mb-3">
                            <label class="form-label">Danh mục</label>
                            @foreach($categories as $category)
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="category" 
                                           id="category{{ $category->id }}" value="{{ $category->id }}"
                                           {{ request('category') == $category->id ? 'checked' : '' }}>
                                    <label class="form-check-label" for="category{{ $category->id }}">
                                        {{ $category->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <!-- Lọc theo giá -->
                        <div class="mb-3">
                            <label class="form-label">Khoảng giá</label>
                            <div class="row">
                                <div class="col">
                                    <input type="number" class="form-control" name="min_price" 
                                           placeholder="Từ" value="{{ request('min_price') }}">
                                </div>
                                <div class="col">
                                    <input type="number" class="form-control" name="max_price" 
                                           placeholder="Đến" value="{{ request('max_price') }}">
                                </div>
                            </div>
                        </div>

                        <!-- Sắp xếp -->
                        <div class="mb-3">
                            <label class="form-label">Sắp xếp theo</label>
                            <select class="form-select" name="sort">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>Mới nhất</option>
                                <option value="price" {{ request('sort') == 'price' ? 'selected' : '' }}>Giá</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Tên</option>
                            </select>
                            <select class="form-select mt-2" name="direction">
                                <option value="asc" {{ request('direction') == 'asc' ? 'selected' : '' }}>Tăng dần</option>
                                <option value="desc" {{ request('direction') == 'desc' ? 'selected' : '' }}>Giảm dần</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Áp dụng</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Danh sách sản phẩm -->
        <div class="col-md-9">
            <div class="row">
                @foreach($products as $product)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ asset('storage/' . $product->image) }}" class="card-img-top" alt="{{ $product->name }}">
                            <div class="card-body">
                                <h5 class="card-title">{{ $product->name }}</h5>
                                <p class="card-text">{{ Str::limit($product->description, 100) }}</p>
                                <p class="card-text"><strong>Giá: </strong>{{ number_format($product->price) }} VNĐ</p>
                                <a href="{{ route('products.show', $product) }}" class="btn btn-primary">Chi tiết</a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Phân trang -->
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        </div>
    </div>
</div>
@endsection 