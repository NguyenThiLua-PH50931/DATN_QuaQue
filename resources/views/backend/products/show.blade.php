@extends('layouts.backend')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<div class="page-body">
    <div class="container my-4">
        <div class="card card-table">
            <div class="title-header option-title d-sm-flex d-block">
                <h5>{{ $product->name }}</h5>
                <div class="right-options">
                    <ul>
                        <li>
                            <a class="btn btn-solid" href="">Quay lại</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin sản phẩm chung -->
                <div class="col-md-5">
                    <img src="{{ asset('storage/' . $product->image) }}" class="img-fluid mb-2" alt="">
                    <p><b>Danh mục:</b> {{ $product->category->name ?? 'N/A' }}</p>
                    <p><b>Vùng miền:</b> {{ $product->region->name ?? 'N/A' }}</p>
                    <p><b>Trạng thái:</b>
                        @if($product->active)
                        <span class="badge bg-success">Đang bán</span>
                        @else
                        <span class="badge bg-secondary">Ẩn</span>
                        @endif
                    </p>
                    <p><b>Lượt xem tổng:</b> {{ $product->view_total }}</p>
                    <p><b>Lượt xem ngày:</b> {{ $product->view_day }}</p>
                    <p><b>Lượt xem tuần:</b> {{ $product->view_week }}</p>
                    <p><b>Lượt xem tháng:</b> {{ $product->view_month }}</p>
                    <p><b>Mô tả chung:</b> {!! nl2br(e($product->description)) !!}</p>
                    <hr>
                </div>
                <!-- Biến thể -->
                <div class="col-md-7">
                    <div class="card card-table">
                        <div class="card-body">
                            <h4>Biến thể</h4>
                            <div class="table-responsive">
                                <!-- table biến thể ở đây -->
                                <table class="table table-bordered align-middle" id="variantTable">
                                    <thead>
                                        <tr>
                                            <th>STT</th>
                                            <th>Tên biến thể</th>
                                            <th>Ảnh</th>
                                            <th>Mô tả</th>
                                            <th>Giá</th>
                                            <th>Số lượng</th>
                                            <th>SKU</th>
                                            <th>Barcode</th>
                                            <th>Trạng thái</th>
                                            <th>Thao tác</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($product->variants as $k => $variant)
                                        <tr>
                                            <td>{{ $k+1 }}</td>
                                            <td>{{ $variant->name }}</td>
                                            <td>
                                                @if($variant->image)
                                                <img src="{{ asset('storage/' . $variant->image) }}" width="70">
                                                @else
                                                <img src="{{ asset('storage/' . $product->image) }}" width="70">
                                                @endif
                                            </td>
                                            <td>{!! nl2br(e($variant->description)) !!}</td>
                                            <td>{{ number_format($variant->price, 0, ',', '.') }}₫</td>
                                            <td>{{ $variant->stock }}</td>
                                            <td>{{ $variant->sku }}</td>
                                            <td>{{ $variant->barcode }}</td>
                                            <td>
                                                @if($variant->active)
                                                <span class="badge bg-success">Đang bán</span>
                                                @else
                                                <span class="badge bg-danger">Ngừng bán</span>
                                                @endif
                                            </td>
                                            <td>
                                                <form action="{{ route('admin.variants.toggle', $variant->id) }}" method="POST" style="display:inline;">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm {{ $variant->active ? 'btn-danger' : 'btn-success' }}">
                                                        {{ $variant->active ? 'Hủy' : 'Kích hoạt' }}
                                                    </button>
                                                </form>

                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <hr>

            <!-- Đánh giá và bình luận -->
            <div class="row mt-4">
                <div class="col-12">
                    <h4>Đánh giá & Bình luận</h4>
                    @foreach($product->reviews as $review)
                    <div>
                        <b>{{ $review->user->name ?? 'Ẩn danh' }}</b>
                        <span>Đánh giá: <b>{{ $review->rating }}/5</b></span>
                        <span>{{ $review->created_at->format('d/m/Y H:i') }}</span>
                        <div>{{ $review->comment }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
            <a href="" class="btn btn-secondary mt-3">Quay lại danh sách</a>
        </div>
    </div>
    @includeIf('backend.footer')
</div>
@push('styles')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $(document).ready(function() {
        $('#variantTable').DataTable({
            "lengthMenu": [5, 7, 10, 25, 50, 100, -1],
            "language": {
                "lengthMenu": "Hiển thị _MENU_ dòng/trang",
                "zeroRecords": "Không tìm thấy dữ liệu",
                "info": "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
                "infoEmpty": "Không có dữ liệu",
                "infoFiltered": "(lọc từ _MAX_ dòng)",
                "search": "Tìm kiếm:",
                "paginate": {
                    "first": "Đầu",
                    "last": "Cuối",
                    "next": "Tiếp",
                    "previous": "Trước"
                },
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": [2, 7, 8]
                } // Disable sort ảnh, trạng thái, nút thao tác
            ]
        });
    });
</script>
@endpush

@endsection