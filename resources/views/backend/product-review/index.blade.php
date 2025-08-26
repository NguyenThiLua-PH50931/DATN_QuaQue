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
                                <!-- <div class="col-md-2">
                                    <label for="is_reply">Loại</label>
                                    <select name="is_reply" id="is_reply" class="form-control">
                                        <option value="">Tất cả</option>
                                        <option value="1" {{ request('is_reply') === '1' ? 'selected' : '' }}>Phản hồi</option>
                                        <option value="0" {{ request('is_reply') === '0' ? 'selected' : '' }}>Review gốc</option>
                                    </select>
                                </div> -->
                                <div class="col-md-12 mt-2" style="display: flex;">
                                    <button type="submit" class="btn btn-primary">Lọc</button>
                                    <a href="{{ route('admin.reviews.index') }}" class="btn btn-outline-secondary ms-2">Xóa lọc</a>
                                </div>
                                <div class="col-md-12 mt-2">
                                    
                                </div>
                            </div>
                        </form>
                    </div>
                    <!-- Table Start -->
                    <div class="table-responsive">
                        <table class="user-table ticket-table review-table theme-table table" id="table_id">
                            <thead>
                                <tr>
                                    <th>Người Dùng</th>
                                    <th>Sản Phẩm</th>
                                    <th>Đánh Giá</th>
                                    <th>Bình Luận</th>
                                    <th class="text-center align-middle" style="min-width: 20px;">Hành Động</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($reviews as $key => $review)

                                <tr>
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
                                    <td class="review-content"
                                        data-full-content="{{ $review->content }}"
                                        data-variant-name="{{ $variantNames[$review->product_variant_value_id] ?? 'N/A' }}">
                                        {{ \Illuminate\Support\Str::limit($review->content, 20) }}
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)" class="delete-btn text-danger"
                                            data-bs-toggle="modal"
                                            data-bs-target="#deleteReviewModal"
                                            data-id="{{ $review->id }}"
                                            data-content="{{ \Illuminate\Support\Str::limit($review->content, 30) }}"
                                            title="Xóa">
                                            <i class="ri-delete-bin-line"></i>
                                        </a>
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
<!-- Modal -->
<div id="reviewModal" class="modal" style="display:none; position:fixed; z-index:99; left:0; top:0; width:100vw; height:100vh; background:rgba(0,0,0,0.2);">
    <div style="background:#fff; margin:10% auto; padding:20px; width:350px; border-radius:12px; position:relative;">
        <span id="closeModal" style="cursor:pointer; position:absolute; top:10px; right:16px;">&times;</span>
        <h4 id="modalVariantName"></h4>
        <div id="modalContent" style="margin-top:10px;"></div>
    </div>
</div>
<!-- Modal xác nhận xóa review -->
<div class="modal fade" id="deleteReviewModal" tabindex="-1" aria-labelledby="deleteReviewModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="deleteReviewForm" method="POST">
        @csrf
        @method('DELETE')
        <div class="modal-header">
          <h5 class="modal-title" id="deleteReviewModalLabel">Xác nhận xóa đánh giá</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <p>Bạn có chắc chắn muốn xóa đánh giá này không?</p>
          <div id="reviewContentPreview" class="text-muted"></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn btn-danger">Xóa</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
    $(document).ready(function() {
        $('#table_id').DataTable({
            info: false,
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
<script>
    document.querySelectorAll('.review-content').forEach(td => {
        td.onclick = function() {
            document.getElementById('modalContent').innerText = td.dataset.fullContent;
            document.getElementById('modalVariantName').innerText = 'Phân loại: ' + td.dataset.variantName;
            document.getElementById('reviewModal').style.display = 'block';
        };
    });
    document.getElementById('closeModal').onclick = function() {
        document.getElementById('reviewModal').style.display = 'none';
    };
    // Đóng modal khi click ra ngoài
    document.getElementById('reviewModal').onclick = function(e) {
        if (e.target === this) this.style.display = 'none';
    }
</script>
<script>
document.querySelectorAll('.delete-btn').forEach(function(btn) {
    btn.addEventListener('click', function() {
        let reviewId = btn.getAttribute('data-id');
        let reviewContent = btn.getAttribute('data-content');
        let form = document.getElementById('deleteReviewForm');
        // Đổi action route đúng id
        form.action = "{{ route('admin.reviews.destroy', '__ID__') }}".replace('__ID__', reviewId);
        // Hiện preview nội dung
        document.getElementById('reviewContentPreview').innerText = 'Nội dung: ' + reviewContent;
    });
});
</script>
@endsection