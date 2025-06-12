<!-- resources/views/backend/product-review/index.blade.php -->
@extends('layouts.backend')

@section('title', 'Đánh giá sản phẩm')

@section('content')
<div class="page-body">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                <div class="card card-table">
                    <!-- Filter Form Start -->
                    <div class="card-body">
                        <div class="title-header option-title">
                            <h5>Đánh Giá Sản Phẩm</h5>
                        </div>
                        <form method="GET" action="{{ route('admin.reviews.index') }}" class="mb-4">
                            <div class="row">
                                <div class="col-md-3">
                                    <label for="product_id">Sản phẩm</label>
                                    <select name="product_id" id="product_id" class="form-control">
                                        <option value="">Tất cả sản phẩm</option>
                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>
                                                {{ $product->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="user_id">Người dùng</label>
                                    <select name="user_id" id="user_id" class="form-control">
                                        <option value="">Tất cả người dùng</option>
                                        @foreach ($users as $user)
                                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                                {{ $user->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="rating">Điểm đánh giá</label>
                                    <select name="rating" id="rating" class="form-control">
                                        <option value="">Tất cả</option>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>
                                                {{ $i }} sao
                                            </option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="after_date">Từ ngày</label>
                                    <input type="date" name="after_date" class="form-control" value="{{ request('after_date') }}">
                                </div>
                                <div class="col-md-2">
                                    <label for="before_date">Đến ngày</label>
                                    <input type="date" name="before_date" class="form-control" value="{{ request('before_date') }}">
                                </div>
                                <!-- <div class="col-md-3">
                                    <label for="parent_id">Review/Phản hồi</label>
                                    <select name="parent_id" id="parent_id" class="form-control">
                                        <option value="">Tất cả</option>
                                        @foreach ($reviews as $review)
                                            <option value="{{ $review->id }}" {{ request('parent_id') == $review->id ? 'selected' : '' }}>
                                                {{ $review->id }} - {{ $review->comment }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div> -->
                                <div class="col-md-2">
                                    <label for="is_reply">Loại</label>
                                    <select name="is_reply" id="is_reply" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="1" {{ request('is_reply') === '1' ? 'selected' : '' }}>Phản hồi</option>
                                        <option value="0" {{ request('is_reply') === '0' ? 'selected' : '' }}>Review gốc</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-2">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Table Start -->
                    <div class="table-responsive">
                        <table class="user-table ticket-table review-table theme-table table" id="table_id">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Người Dùng</th>
                                    <th>Sản Phẩm</th>
                                    <th>Đánh Giá</th>
                                    <th>Bình Luận</th>
                                    <th>Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $key => $review)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $review->user->name }}</td>
                                        <td>{{ $review->product->name }}</td>
                                        <td>
                                            <ul class="rating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <li>
                                                        <i class="fas fa-star {{ $i <= $review->rating ? 'theme-color' : '' }}"></i>
                                                    </li>
                                                @endfor
                                            </ul>
                                        </td>
                                        <td>{{ $review->comment }}</td>
                                        <td>
                                            <form action="{{ route('admin.reviews.destroy', $review->id) }}" method="POST" style="display: inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Xóa</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- Table End -->
                    {{ $reviews->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('#table_id').DataTable({
                language: {
                    search: "Tìm kiếm:",
                    lengthMenu: "Hiển thị _MENU_ đánh giá sản phẩm",
                    info: "Hiển thị _START_ đến _END_ trong tổng _TOTAL_ đánh giá sản phẩm",
                    paginate: {
                        first: "Đầu",
                        last: "Cuối",
                        next: "Sau",
                        previous: "Trước"
                    },
                    zeroRecords: "Không tìm thấy đánh giá nào.",
                }
            });
        });
    </script>
@endpush