@extends('layouts.backend')
@section('title', 'Chi tiết sản phẩm')
@section('content')
<style>
    /* Base style */
    .dataTables_paginate .pagination .page-item .page-link {
        min-width: 36px;
        height: 36px;
        font-size: 16px;
        border-radius: 8px;
        margin: 0 1.5px;
        color: #495057;
        background: none;
        border: none !important;
        font-weight: 500;
        box-shadow: none;
        transition: background 0.15s, color 0.15s;
        cursor: pointer;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    .dataTables_paginate .pagination .page-item.active .page-link,
    .dataTables_paginate .pagination .page-item.active .page-link:hover,
    .dataTables_paginate .pagination .page-item.active .page-link:focus,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:hover,
    .dataTables_wrapper .dataTables_paginate .paginate_button.active:focus {
        background: #0da487 !important;
        border-radius: 7px;
        color: #fff !important;
        background-image: none !important;
        box-shadow: 0 2px 8px #0da48725 !important;
        border: none !important;
        outline: none !important;
    }


    .dataTables_paginate .pagination .page-item .page-link {
        min-width: 28px;
        /* hoặc 24px nếu muốn nhỏ nữa */
        height: 28px;
        font-size: 14px;
        /* chữ nhỏ hơn */
        border-radius: 6px;
        margin: 0 1px;
        padding: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
    }

    /* Hover các nút KHÔNG phải active hoặc disabled */
    .dataTables_paginate .pagination .page-item:not(.active):not(.disabled) .page-link:hover {
        background: #fff !important;
        color: #0da487 !important;
        box-shadow: 0 2px 8px #0da48725;
        border: none !important;
    }

    /* Nút disabled */
    .dataTables_paginate .pagination .page-item.disabled .page-link {
        color: #e5e7eb !important;
        background: none !important;
        pointer-events: none;
    }

    /* Nút ellipsis (dấu ...) */
    .dataTables_paginate .pagination .page-item .ellipsis {
        pointer-events: none;
        color: #b1bacf !important;
        background: none !important;
        font-size: 16px;
        border: none !important;
    }

    .dataTables_paginate .pagination .page-item.active .page-link:hover,
    .dataTables_paginate .pagination .page-item.active .page-link:focus {
        background: #0da487 !important;
        color: #fff !important;
        box-shadow: 0 2px 8px #0da48725 !important;
        border: none !important;
        outline: none !important;
        pointer-events: none;
    }
</style>
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
                        <span class="badge {{ $product->active ? 'bg-success' : 'bg-danger' }} status-badge"
                            style="cursor:pointer"
                            data-id="{{ $product->id }}"
                            data-name="{{ $product->name ?? '' }}"
                            data-status="{{ $product->active }}">
                            {{ $product->active ? 'Đang bán' : 'Ẩn' }}
                        </span>
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
                                <table class="table table-bordered align-middle product-table" id="variantTable">
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
                                                <span class="badge status-badge {{ $variant->active ? 'bg-success' : 'bg-danger' }}"
                                                    style="cursor:pointer"
                                                    data-id="{{ $variant->id }}"
                                                    data-name="{{ $variant->name ?? '' }}"
                                                    data-status="{{ $variant->active }}">
                                                    {{ $variant->active ? 'Đang bán' : 'Ngừng bán' }}
                                                </span>
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
<!-- Modal đổi trạng thái biến thể -->
<div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title mb-3" id="statusModalLabel">Đổi trạng thái biến thể</h5>
                <p id="modal-status-text"></p>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box text-end mt-4">
                    <button type="button" class="btn btn--no btn-secondary me-2" data-bs-dismiss="modal">Không</button>
                    <form id="status-toggle-form" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn--yes btn-primary">Đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal đổi trạng thái sản phẩm -->
<div class="modal fade" id="statusModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="statusModalLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <h5 class="modal-title mb-3" id="statusModalLabel">Đổi trạng thái sản phẩm</h5>
                <p id="modal-status-text"></p>
                <button type="button" class="btn-close position-absolute top-0 end-0 m-2" data-bs-dismiss="modal" aria-label="Close"></button>
                <div class="button-box text-end mt-4">
                    <button type="button" class="btn btn--no btn-secondary me-2" data-bs-dismiss="modal">Không</button>
                    <form id="status-toggle-form" method="POST" style="display:inline;">
                        @csrf
                        <button type="submit" class="btn btn--yes btn-primary">Đồng ý</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@push('styles')
<link rel="stylesheet" href="//cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="//cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    $('#variantTable').DataTable({
        "pagingType": "full_numbers",
        "lengthMenu": [10, 25, 50, 100],
        "language": {
            "lengthMenu": "Hiển thị _MENU_ dòng/trang",
            "zeroRecords": "Không tìm thấy dữ liệu",
            "info": "Hiển thị _START_ đến _END_ của _TOTAL_ dòng",
            "infoEmpty": "Không có dữ liệu",
            "infoFiltered": "(lọc từ _MAX_ dòng)",
            "search": "Tìm kiếm:",
            "paginate": {
                "first": "1",
                "last": "",
                "next": ">",
                "previous": "<"
            }
        },
        "columnDefs": [{
            "orderable": false,
            "targets": [0, 8] // 0: STT, 8: Trạng thái (tùy table bạn không có cột thao tác nữa)
        }]
    });

    // Fix nhãn "last" thành số cuối, ẩn khi chỉ 1 trang (y chang index)
    $('#variantTable').on('draw.dt', function() {
        var table = $('#variantTable').DataTable();
        var totalPages = table.page.info().pages;

        if (totalPages === 1) {
            $('.paginate_button').not('.previous,.active,.next').hide();
            $('.paginate_button.last').hide();
        } else {
            $('.paginate_button').show();
            $('.paginate_button.last a').text(totalPages);
            $('.paginate_button.first a').text(1);
        }
    });
</script>

@endpush
@push('scripts')
<script>
    $('.status-badge').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');

        let nextStatus = (status == 1) ? 'Ngừng bán' : 'Đang bán';
        if (!name) name = '(ID ' + id + ')';
        $('#modal-status-text').html('Bạn muốn chuyển trạng thái biến thể <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');

        // Set action cho form: route đổi trạng thái biến thể!
        let url = '{{ route("admin.products.variants.toggle", ":id") }}';
        url = url.replace(':id', id);
        $('#status-toggle-form').attr('action', url);

        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    });
</script>

<script>
    $('.status-badge').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name'); // phải có data-name ở trên
        var status = $(this).data('status');

        let nextStatus = (status == 1) ? 'Ngừng bán' : 'Đang bán';
        // Nếu name rỗng (undefined/null), show id cho dễ debug
        if (!name) name = '(ID ' + id + ')';
        $('#modal-status-text').html('Bạn muốn chuyển trạng thái sản phẩm <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');

        // Set action cho form
        let url = '{{ route("admin.products.toggle", ":id") }}';
        url = url.replace(':id', id);
        $('#status-toggle-form').attr('action', url);

        // Show modal
        var modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    });
</script>
@endpush
@endsection