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

    .img-thumb {
        transition: .25s;
    }

    .thumb-swiper .swiper-slide img {
        cursor: pointer;
    }

    .thumb-swiper .swiper-slide {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .review-item,
    .comment-item {
        background: #f9f9fc;
        border: 1px solid #e0e7ef;
        border-radius: 8px;
    }

    .reply-box {
        background: #f0f5ff;
        padding: 8px 12px;
        border-radius: 6px;
        font-size: 14px;
    }

    .bulk-delete-btn[disabled] {
        background: #e1e8f3 !important;
        color: #66708a !important;
        border: none !important;
        cursor: not-allowed !important;
        opacity: 1 !important;
    }

    .bulk-delete-btn[disabled] .delete-bulk-icon {
        color: #66708a !important;
    }

    .bulk-delete-btn:not([disabled]) {
        background: #becde4 !important;
        color: #495057 !important;
        border: none !important;
        cursor: pointer !important;
        box-shadow: 0 2px 8px #becde480;
        transition: background .5s, color .5s;
    }

    .bulk-delete-btn:not([disabled]) .delete-bulk-icon {
        color: #495057 !important;
    }

    .bulk-delete-btn:not([disabled]):hover {
        background: #aac4e7 !important;
    }
</style>
<!-- SwiperJS CSS & JS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<div class="page-body">
    <div class="container my-4">
        <div class="card card-table">
            <div class="title-header option-title d-sm-flex d-block">
                <h5>{{ $product->name }}</h5>
                <div class="right-options">
                    <ul>
                        <li>
                            <a class="btn btn-solid" href="{{ route('admin.products.index') }}">Quay lại</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <!-- Thông tin sản phẩm chung -->
                <div class="col-md-5">
                    @php
                    $gallery = [];
                    if ($product->image) $gallery[] = asset('storage/' . $product->image);
                    foreach ($product->images as $img) $gallery[] = asset('storage/' . $img->image_url);
                    @endphp

                    <div style="width:465px;height:350px;display:flex;gap:12px;">
                        {{-- Thumbnails vertical --}}
                        <div class="swiper thumb-swiper" style="height:350px; width:85px;">
                            <div class="swiper-wrapper">
                                @foreach($gallery as $img)
                                <div class="swiper-slide">
                                    <img src="{{ $img }}" class="img-thumb" style="width:80px; height:60px; object-fit:cover; border-radius:7px; border:2px solid #ccc;">
                                </div>
                                @endforeach
                            </div>
                        </div>
                        {{-- Main image --}}
                        <div style="flex:1; height:350px; display:flex; align-items:center; justify-content:center; background:#fafafa; border-radius:10px;">
                            <img id="mainProductImg" src="{{ $gallery[0] ?? '' }}"
                                class="img-fluid"
                                style="max-width:350px; max-height:330px; object-fit:contain; border-radius:10px;">
                        </div>
                    </div>

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
                    <p><b>Mô tả chung:</b></p>
                    <div>{!! $product->description !!}</div>
                    <a href="{{ route('admin.products.edit', $product->slug) }}" class="btn btn-secondary mt-3">Sửa sản phẩm</a>
                </div>
                <!-- Biến thể -->
                <div class="col-md-7">
                    <div class="card card-table">
                        <div class="card-body">
                            <h4>Biến thể</h4>
                            <div class="table-responsive">
                                <form id="bulk-delete-form" method="POST" action="{{ route('admin.products.variant.bulkDelete') }}">
                                    @csrf
                                    <table class="table product-table align-middle" id="variantTable">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="select-all"></th> <!-- Checkbox chọn tất cả -->
                                                <th>STT</th>
                                                <th>Tên biến thể</th>
                                                <th>Ảnh</th>
                                                <th>Mô tả</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>SKU</th>
                                                <th>Barcode</th>
                                                <th>Trạng thái</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($product->variants as $k => $variant)
                                            <tr class="product-row">
                                                <td>
                                                    <input type="checkbox" class="row-checkbox" name="ids[]" value="{{ $variant->id }}">
                                                </td>
                                                <td>{{ $k + 1 }}</td>
                                                <td>
                                                    <a href="{{ route('admin.products.variant.show', $variant->id) }}" class="fw-bold text-primary" style="font-size:16px;">
                                                        {{ $variant->name }}
                                                    </a>
                                                </td>
                                                <td>
                                                    @if($variant->image)
                                                    <img src="{{ asset('storage/' . $variant->image) }}" height="50px" style="border-radius:8px; object-fit:cover;">
                                                    @else
                                                    <img src="{{ asset('storage/' . $product->image) }}" height="50px" style="border-radius:8px; object-fit:cover;">
                                                    @endif
                                                </td>
                                                <td>{{ \Illuminate\Support\Str::limit(strip_tags($variant->description), 50, '...') }}</td>

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
                                                <td>
                                                    <a href="{{ route('admin.products.variant.show', $variant->id) }}" class="action-link text-decoration-none me-2" title="Xem">
                                                        <i class="ri-eye-line"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.variant.edit', $variant->id) }}" class="action-link text-decoration-none me-2" title="Sửa">
                                                        <i class="ri-pencil-line"></i>
                                                    </a>
                                                    <a href="javascript:void(0)" class="action-link text-decoration-none text-danger"
                                                        data-bs-toggle="modal" data-bs-target="#deleteVariantModal"
                                                        data-id="{{ $variant->id }}" data-name="{{ $variant->name }}">
                                                        <i class="ri-delete-bin-line"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <button type="button"
                                        id="delete-selected"
                                        class="btn bulk-delete-btn btn-sm mt-2 d-inline-flex align-items-center gap-2 mb-3"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteBulkModal"
                                        disabled>
                                        <i class="ri-delete-bin-line delete-bulk-icon"></i> Xóa
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Modal xác nhận xóa biến thể -->
                    <div class="modal fade" id="deleteVariantModal" tabindex="-1" aria-labelledby="deleteVariantModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <form id="delete-variant-form" method="POST" action="">
                                    @csrf
                                    @method('DELETE')
                                    <div class="modal-body text-center">
                                        <h5 class="modal-title mb-2">Xóa biến thể?</h5>
                                        <p id="delete-variant-message">Bạn chắc chắn muốn xóa biến thể này?</p>
                                        <div class="button-box mt-4">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Không</button>
                                            <button type="submit" class="btn btn-danger">Có</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <hr>

            <!-- Đánh giá và bình luận -->
            <div class="row mt-4">
                {{-- Đánh giá --}}
                <div class="col-md-6 mb-4">
                    <h4 class="mb-3">Đánh giá sản phẩm</h4>
                    @if($product->reviews->count())
                    @foreach($product->reviews as $review)
                    <div class="review-item border rounded p-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <b>{{ $review->user->name ?? 'Ẩn danh' }}</b>
                            <span class="text-warning">
                                @for($i=1;$i<=5;$i++)
                                    <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                    @endfor
                                    <span class="ms-1 text-dark">({{ $review->rating }}/5)</span>
                            </span>
                        </div>
                        <div class="text-muted mb-1" style="font-size: 13px;">{{ $review->created_at->format('d/m/Y H:i') }}</div>
                        <div>{{ $review->comment }}</div>
                    </div>
                    @endforeach
                    @else
                    <div class="text-muted">Chưa có đánh giá nào.</div>
                    @endif
                </div>

                {{-- Bình luận --}}
                <div class="col-md-6 mb-4">
                    <h4 class="mb-3">Bình luận sản phẩm</h4>
                    @if($product->comments->count())
                    @foreach($product->comments as $comment)
                    <div class="comment-item border rounded p-2 mb-3">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <b>{{ $comment->user->name ?? 'Ẩn danh' }}</b>
                            <span class="text-muted" style="font-size: 13px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                        <div>{{ $comment->content }}</div>

                        {{-- Các trả lời (nếu có) --}}
                        @if($comment->replies && count($comment->replies))
                        <div class="reply-box ms-3 mt-2">
                            @foreach($comment->replies as $reply)
                            @php
                            $isAdmin = $reply->user && $reply->user->is_admin;
                            $userName = $reply->user->name ?? 'Ẩn danh';
                            @endphp
                            <div class="{{ $isAdmin ? 'text-primary' : '' }}">
                                <b>
                                    {{ $isAdmin ? 'Quản trị viên '.$userName.' trả lời:' : $userName.' trả lời:' }}
                                </b>
                                {{ $reply->reply }}
                                <span class="text-muted" style="font-size:12px;">
                                    ({{ $reply->created_at->format('d/m/Y H:i') }})
                                </span>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                    @endforeach
                    @else
                    <div class="text-muted">Chưa có bình luận nào.</div>
                    @endif
                </div>
            </div>


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

    $(document).on('click', '.status-badge', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');

        let nextStatus = (status == 1) ? 'Ngừng bán' : 'Đang bán';
        if (!name) name = '(ID ' + id + ')';
        $('#modal-status-text').html('Bạn muốn chuyển trạng thái biến thể <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');

        // Tạo URL chính xác bằng cách thay :id bằng giá trị thực
        let url = toggleUrlBase.replace(':id', id);
        $('#status-toggle-form').attr('action', url);

        var modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    });

    $('.status-badge').click(function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        var status = $(this).data('status');

        let nextStatus = (status == 1) ? 'Ngừng bán' : 'Đang bán';
        if (!name) name = '(ID ' + id + ')';
        $('#modal-status-text').html('Bạn muốn chuyển trạng thái biến thể <b>' + name + '</b> sang <span class="text-primary">' + nextStatus + '</span>?');

        let url = toggleUrlBase.replace(':id', id); // Thay :id thành id thực tế
        $('#status-toggle-form').attr('action', url);

        var modal = new bootstrap.Modal(document.getElementById('statusModal'));
        modal.show();
    });

    document.addEventListener("DOMContentLoaded", function() {
        var gallery = @json($gallery);
        var currentIndex = 0;

        var thumbSwiper = new Swiper('.thumb-swiper', {
            direction: 'vertical',
            slidesPerView: 4,
            spaceBetween: 7,
            mousewheel: true,
            loop: true,
            slideToClickedSlide: false,
        });

        function updateThumbActive(idx) {
            // Reset tất cả thumbnail về mờ
            document.querySelectorAll('.thumb-swiper .swiper-slide img').forEach(img => {
                img.style.filter = 'grayscale(20%) blur(1px)';
                img.style.opacity = '0.8';
                img.style.borderColor = '#ccc';
            });
            // Chỉ ảnh ở ô đầu tiên được rõ nét
            const slides = document.querySelectorAll('.thumb-swiper .swiper-slide');
            if (slides.length > 0) {
                const firstSlide = slides[thumbSwiper.activeIndex]; // ảnh ở ô đầu
                if (firstSlide) {
                    const img = firstSlide.querySelector('img');
                    if (img) {
                        img.style.filter = 'none';
                        img.style.opacity = '1';
                        img.style.borderColor = '#1976d2';
                    }
                }
            }
        }

        function showMainImage(idx) {
            document.getElementById('mainProductImg').src = gallery[idx];
            thumbSwiper.slideToLoop(idx, 400, false);
            setTimeout(() => updateThumbActive(idx), 410); // Đợi slide xong mới cập nhật active
        }

        // Sự kiện click vào thumbnail (luôn lấy realIndex gốc)
        thumbSwiper.on('click', function(swiper, e) {
            var clickedSlide = e.target.closest('.swiper-slide');
            if (!clickedSlide) return;
            var idx = parseInt(clickedSlide.getAttribute('data-swiper-slide-index')) || 0;
            showMainImage(idx);
        });

        // Khi next/prev bằng chuột/drag
        thumbSwiper.on('slideChange', function() {
            var idx = thumbSwiper.realIndex;
            showMainImage(idx);
        });

        // Init lần đầu
        showMainImage(0);
    });
    $('#deleteVariantModal').on('show.bs.modal', function(e) {
        var button = $(e.relatedTarget);
        var id = button.data('id');
        var name = button.data('name');
        var form = $('#delete-variant-form');
        form.attr('action', '/admin/products/variant/' + id);
        $('#delete-variant-message').text('Bạn chắc chắn muốn xóa biến thể "' + name + '"?');
    });
    $(function() {
        $('#select-all').on('change', function() {
            $('.row-checkbox').prop('checked', $(this).is(':checked'));
            updateBulkDeleteBtn();
        });

        $('.row-checkbox').on('change', function() {
            updateBulkDeleteBtn();
        });

        function updateBulkDeleteBtn() {
            $('#delete-selected').prop('disabled', $('.row-checkbox:checked').length === 0);
        }

        $('#confirm-bulk-delete').on('click', function() {
            $('#bulk-delete-form').submit();
        });
    });
</script>


@endpush
@endsection