@extends('layouts.backend')
@section('title', 'Chi tiết sản phẩm')
@section('content')

<!-- Modal mô tả đầy đủ -->
<div class="modal fade" id="descriptionModal" tabindex="-1" aria-labelledby="descriptionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="descriptionModalLabel">Mô tả chi tiết</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body" id="descriptionModalBody"></div>
        </div>
    </div>
</div>

<div class="page-body">
    <div class="card card-table p-3">

        <div class="title-header option-title d-sm-flex d-block align-items-center justify-content-between">
            <h5 class="mb-3 mb-sm-0">{{ $product->name }}</h5>
            <a class="btn btn-solid" href="{{ route('admin.products.index') }}">Quay lại</a>
        </div>

        @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Nav tabs -->
        <ul class="nav nav-tabs mt-3" id="productTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="info-tab" data-bs-toggle="tab" data-bs-target="#info"
                    type="button" role="tab">Thông tin</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="variants-tab" data-bs-toggle="tab" data-bs-target="#variants"
                    type="button" role="tab">Biến thể</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="reviews-tab" data-bs-toggle="tab" data-bs-target="#reviews" type="button"
                    role="tab">Đánh giá</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="comments-tab" data-bs-toggle="tab" data-bs-target="#comments"
                    type="button" role="tab">Bình luận</button>
            </li>
        </ul>

        <!-- Tab content -->
        <div class="tab-content mt-3" id="productTabContent">

            <!-- Thông tin -->
            <div class="tab-pane fade show active" id="info" role="tabpanel" aria-labelledby="info-tab">
                <div class="row g-4">
                    <div class="col-md-5">

                        @php
                        $gallery = [];
                        if (
                        !empty($product->main_image) &&
                        \Storage::disk('public')->exists(
                        str_replace(asset('storage/'), '', $product->main_image),
                        )
                        ) {
                        $gallery[] = $product->main_image;
                        }
                        if ($product->product_images && $product->product_images->count()) {
                        foreach ($product->product_images as $img) {
                        if (
                        !empty($img->image_url) &&
                        \Storage::disk('public')->exists(
                        str_replace(asset('storage/'), '', $img->image_url),
                        )
                        ) {
                        $gallery[] = $img->image_url;
                        }
                        }
                        }
                        if ($product->variants && $product->variants->count()) {
                        foreach ($product->variants as $variant) {
                        if (
                        !empty($variant->image_url) &&
                        \Storage::disk('public')->exists(
                        str_replace(asset('storage/'), '', $variant->image_url),
                        )
                        ) {
                        $gallery[] = $variant->image_url;
                        }
                        }
                        }
                        @endphp

                        <div class="product-gallery" style="max-width:350px; margin:auto;">
                            <!-- Ảnh lớn hiển thị chính -->
                            <div class="main-image-wrapper"
                                style="border:1px solid #ddd; border-radius:10px; padding:10px; background:#fafafa;">
                                <img id="mainImage"
                                    src="{{ $gallery[0] ?? asset('backend/assets/images/placeholder.webp') }}"
                                    alt="Ảnh sản phẩm"
                                    style="width:100%; height:auto; border-radius:10px; object-fit:contain;">
                            </div>

                            <!-- Ảnh thumbnail -->
                            <div class="thumbnail-wrapper"
                                style="display:flex; justify-content:center; gap:8px; margin-top:10px; overflow-x:auto; padding-bottom:5px;">
                                @foreach ($gallery as $index => $img)
                                <img src="{{ $img }}" alt="Thumbnail {{ $index + 1 }}"
                                    class="thumbnail-image" data-index="{{ $index }}"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:pointer;">
                                @endforeach
                                @if (empty($gallery))
                                <img src="{{ asset('backend/assets/images/placeholder.webp') }}" alt="Không có ảnh"
                                    style="width:60px; height:60px; object-fit:cover; border-radius:6px; border:2px solid transparent; cursor:default;">
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <p><b>Danh mục:</b> {{ $product->category->name ?? 'N/A' }}</p>
                        <p><b>Vùng miền:</b> {{ $product->region->name ?? 'N/A' }}</p>
                        <p><b>Trạng thái:</b>
                            <span
                                class="badge status-badge product-status-badge {{ $product->active ? 'bg-success' : 'bg-danger' }}"
                                style="cursor:pointer;" data-id="{{ $product->id }}" data-name="{{ $product->name }}"
                                data-status="{{ $product->active }}">
                                {{ $product->active ? 'Đang bán' : 'Ẩn' }}
                            </span>
                        </p>
                        <p><b>Lượt xem tổng:</b> {{ $product->view_total }}</p>
                        <p><b>Lượt xem ngày:</b> {{ $product->view_day }}</p>
                        <p><b>Lượt xem tuần:</b> {{ $product->view_week }}</p>
                        <p><b>Lượt xem tháng:</b> {{ $product->view_month }}</p>

                        <p><b>Mô tả chung:</b></p>
                        <p class="description-short text-primary" data-bs-toggle="modal"
                            data-bs-target="#descriptionModal" data-id="{{ $product->id }}">
                            {!! Str::limit(strip_tags($product->description), 200) !!}
                            <br><small><i>Nhấn để xem thêm</i></small>
                        </p>

                    </div>
                </div>
            </div>

            <!-- Biến thể -->
            <div class="tab-pane fade" id="variants" role="tabpanel" aria-labelledby="variants-tab">
                <form id="bulk-delete-variants-form" method="POST"
                    action="{{ route('admin.products.variant.bulkDelete') }}">
                    @csrf
                    <div class="product-table-wrapper">
                        <table id="variantTable" class="table product-table align-middle">
                            <thead>
                                <tr>
                                    {{-- <th><input type="checkbox" id="select-all-variants"></th> --}}
                                    <th>Tên biến thể</th>
                                    <th>Ảnh</th>
                                    <th>Mô tả</th>
                                    <th>Giá</th>
                                    <th>Số lượng</th>
                                    <th>Trạng thái</th>

                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($product->variants as $variant)
                                <tr class="product-row">
                                    {{-- <td>
                                        <input type="checkbox" class="variant-checkbox row-checkbox" name="variant_ids[]" value="{{ $variant->id }}">
                                    </td> --}}
                                    <td>
                                        <a href="#"
                                            class="fw-bold text-primary show-variant-info"
                                            style="font-size:16px;"
                                            data-bs-toggle="modal"
                                            data-bs-target="#variantInfoModal"
                                            data-name="{{ $variant->name }}"
                                            data-image="{{ asset('storage/' . $variant->image) }}"
                                            data-description="{{ strip_tags($variant->description) }}"
                                            data-price="{{ number_format($variant->price, 0, ',', '.') }}₫"
                                            data-stock="{{ $variant->stock }}"
                                            data-status="{{ $variant->active ? 'Đang bán' : 'Ngừng bán' }}">
                                            {{ Str::limit($variant->name, 20) }}
                                        </a>
                                    </td>
                                    <td>
                                        <img src="{{ asset('storage/' . $variant->image) }}" alt="{{ $variant->name }}"
                                            class="w-20 h-20 object-cover" width="100px">
                                    </td>
                                    <td>
                                        <span class="text-truncate description-short text-primary"
                                            style="max-width: 250px; cursor: pointer;" data-bs-toggle="modal"
                                            data-bs-target="#descriptionModal"
                                            data-variant-id="{{ $variant->id }}">
                                            {!! Str::limit(strip_tags($variant->description), 50) ?: '<em>(Chưa có mô tả)</em>' !!}
                                        </span>
                                    </td>
                                    <td>{{ number_format($variant->price, 0, ',', '.') }}₫</td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            class="edit-stock-btn text-decoration-none text-primary"
                                            data-id="{{ $variant->id }}"
                                            data-stock="{{ $variant->stock }}"
                                            data-name="{{ $variant->name }}"
                                            data-bs-toggle="modal"
                                            data-bs-target="#editStockModal">
                                            {{ $variant->stock }}
                                        </a>
                                    </td>

                                    <td>
                                        <span
                                            class="badge {{ $variant->active ? 'bg-success' : 'bg-danger' }} status-badge variant-status-badge"
                                            style="cursor:pointer" data-id="{{ $variant->id }}"
                                            data-name="{{ $variant->name }}"
                                            data-status="{{ $variant->active }}">
                                            {{ $variant->active ? 'Đang bán' : 'Ngừng bán' }}
                                        </span>
                                    </td>
                                    {{-- <td>
                                        <a href="{{ route('admin.products.variant.show', $variant->id) }}" class="action-link text-decoration-none me-2">
                                    <i class="ri-eye-line"></i>
                                    </a>
                                    <a href="{{ route('admin.products.variant.edit', $variant->id) }}" class="action-link text-decoration-none me-2">
                                        <i class="ri-pencil-line"></i>
                                    </a>
                                    <a href="javascript:void(0)" class="action-link text-decoration-none text-danger"
                                        data-bs-toggle="modal" data-bs-target="#deleteVariantModal"
                                        data-id="{{ $variant->id }}" data-name="{{ $variant->name }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </a>
                                    </td> --}}
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </div>
                </form>
            </div>

            <!-- Đánh giá -->
            <div class="tab-pane fade" id="reviews" role="tabpanel" aria-labelledby="reviews-tab">
                @if ($product->reviews->count())
                @foreach ($product->reviews->take(5) as $review)
                <div class="review-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $review->user->name ?? 'Ẩn danh' }}</strong>
                        <span class="text-warning">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa{{ $i <= $review->rating ? 's' : 'r' }} fa-star"></i>
                                @endfor
                                <span class="ms-1 text-dark">({{ $review->rating }}/5)</span>
                        </span>
                    </div>
                    <div class="text-muted mb-2" style="font-size: 13px;">
                        {{ $review->created_at->format('d/m/Y H:i') }}
                    </div>
                    <div>{{ $review->content }}</div>
                </div>
                @endforeach
                @if ($product->reviews->count() > 5)
                <button class="btn btn-link" id="loadMoreReviewsBtn">Xem thêm đánh giá</button>
                @endif
                @else
                <div class="text-muted">Chưa có đánh giá nào.</div>
                @endif
            </div>

            <!-- Bình luận -->
            <div class="tab-pane fade" id="comments" role="tabpanel" aria-labelledby="comments-tab">
                @if ($product->comments->count())
                @foreach ($product->comments->take(5) as $comment)
                <div class="comment-item border rounded p-3 mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $comment->user->name ?? 'Ẩn danh' }}</strong>
                        <span class="text-muted"
                            style="font-size: 13px;">{{ $comment->created_at->format('d/m/Y H:i') }}</span>
                    </div>
                    <div>{!! nl2br(e($comment->content)) !!}</div>
                    @if ($comment->replies->count())
                    <div class="reply-box ms-3 mt-2">
                        @foreach ($comment->replies as $reply)
                        <div
                            class="{{ $reply->user && $reply->user->is_admin ? 'text-primary' : '' }}">
                            <strong>
                                {{ $reply->user && $reply->user->is_admin ? 'Quản trị viên ' : '' }}{{ $reply->user->name ?? 'Ẩn danh' }}
                                trả lời:
                            </strong>
                            {!! nl2br(e($reply->reply)) !!}
                            <span class="text-muted"
                                style="font-size:12px;">({{ $reply->created_at->format('d/m/Y H:i') }})</span>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endforeach
                @if ($product->comments->count() > 5)
                <button class="btn btn-link" id="loadMoreCommentsBtn">Xem thêm bình luận</button>
                @endif
                @else
                <div class="text-muted">Chưa có bình luận nào.</div>
                @endif
            </div>

        </div>
    </div>
</div>



<div class="modal fade" id="toggleStatusModal" tabindex="-1" aria-labelledby="toggleStatusModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="toggleStatusForm" method="POST" action="">
                @csrf
                <div class="modal-body text-center">
                    <h5 class="modal-title mb-3" id="toggleStatusModalLabel">Đổi trạng thái sản phẩm</h5>
                    <p id="toggleStatusModalText"></p>
                    <div class="btn space-between">
                        <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Hủy</button>
                        <button type="submit" class="btn btn-primary">Đồng ý</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="variantInfoModal" tabindex="-1" aria-labelledby="variantInfoLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="variantInfoLabel">Thông tin biến thể</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
            </div>
            <div class="modal-body">
                <table class="table table-borderless">
                    <tbody>
                        <tr>
                            <th>Tên biến thể:</th>
                            <td id="modalVariantName"></td>
                        </tr>
                        <tr>
                            <th>Ảnh:</th>
                            <td><img id="modalVariantImage" src="" alt="" style="max-width:100px;"></td>
                        </tr>
                        <tr>
                            <th>Giá:</th>
                            <td id="modalVariantPrice"></td>
                        </tr>
                        <tr>
                            <th>Số lượng:</th>
                            <td id="modalVariantStock"></td>
                        </tr>
                        <tr>
                            <th>Trạng thái:</th>
                            <td id="modalVariantStatus"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="editStockModal" tabindex="-1" aria-labelledby="editStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form method="POST" id="editStockForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editStockModalLabel">Cập nhật số lượng biến thể</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="variant_id" id="stockVariantId">
                    <div class="mb-3">
                        <label for="stockInput" class="form-label">Số lượng mới</label>
                        <input type="number" class="form-control" id="stockInput" name="stock" min="0" required>
                    </div>
                    <p><small id="variantNameHelp" class="text-muted"></small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </div>
        </form>
    </div>
</div>

<style>
    .swal2-smaller-toast {
        font-size: 1rem !important;
        min-width: 160px !important;
        max-width: 220px !important;
        padding: 0.7em 1.2em !important;
    }

    .gallery-container {
        display: flex;
        gap: 12px;
        max-width: 465px;
        height: 350px;
    }

    .thumb-swiper {
        height: 350px;
        width: 85px;
    }

    .thumb-swiper .swiper-slide img {
        width: 80px;
        height: 60px;
        object-fit: cover;
        border-radius: 7px;
        border: 2px solid #ccc;
    }

    .main-image-wrapper {
        flex: 1;
        height: 320px;
        max-width: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fafafa;
        border-radius: 10px;
        overflow: hidden;
    }

    .main-image-wrapper img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
        border-radius: 10px;
    }

    .description-short {
        cursor: pointer;
        color: #0d6efd;
    }

    .thumbnail-image.selected {
        border-color: #0d6efd !important;
    }

    .modal-dialog {
        display: flex !important;
        align-items: center !important;
        min-height: calc(100vh - 1rem) !important;
    }

    .variant-name {
        color: #0d6efd;
        /* xanh bootstrap */
        font-weight: 700;
        /* in đậm */
        text-decoration: none;
    }

    .variant-name:hover {
        text-decoration: underline;
    }

    .product-table-wrapper {
        background: #f8fafd;
        border-radius: 12px;
        box-shadow: 0 2px 14px #dbeafe40;
        padding: 16px;
    }

    .product-table thead tr,
    .product-table tfoot tr {
        background: #f1f5f9;
    }

    .product-row {
        background: #fff;
        border-radius: 10px;
        border-bottom: 1px solid #e5e7eb;
        transition: box-shadow .2s;
    }

    .product-row:hover {
        background: #eef6ff;
        box-shadow: 0 2px 8px #93c5fd22;
    }

    .action-link {
        color: #7c3aed !important;
        font-weight: 500;
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
        table tbody tr:nth-child(odd) {
        background-color: #ffffff;
        /* màu trắng */
    }

    table tbody tr:nth-child(even) {
        background-color: #f8f9fa;
        /* xám nhạt, bạn có thể đổi màu theo ý thích */
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Gallery ảnh sản phẩm
    const mainImage = document.getElementById('mainImage');
    const thumbnails = document.querySelectorAll('.thumbnail-image');
    const gallery = @json($gallery ?? []);

    function setActiveThumbnail(index) {
        thumbnails.forEach((thumb, i) => {
            thumb.classList.toggle('selected', i === index);
        });
    }

    thumbnails.forEach(thumb => {
        thumb.addEventListener('click', function () {
            const idx = parseInt(this.getAttribute('data-index'));
            if (!isNaN(idx) && gallery[idx]) {
                mainImage.src = gallery[idx];
                setActiveThumbnail(idx);
            }
        });
    });
    if (gallery.length > 0) {
        setActiveThumbnail(0);
        mainImage.src = gallery[0];
    }

    // Modal mô tả chi tiết sản phẩm/biến thể
    const descriptionModalEl = document.getElementById('descriptionModal');
    const descriptionModal = new bootstrap.Modal(descriptionModalEl);
    document.querySelectorAll('.description-short').forEach(el => {
        el.addEventListener('click', function () {
            let url = '';
            const productId = this.getAttribute('data-id');
            const variantId = this.getAttribute('data-variant-id');
            if (variantId) url = `/admin/products/variant/${variantId}/description`;
            else if (productId) url = `/admin/products/${productId}/description`;
            else return alert('Không tìm thấy mô tả.');

            fetch(url)
                .then(r => {
                    if (!r.ok) throw new Error('Không tải được mô tả.');
                    return r.text();
                })
                .then(html => {
                    document.getElementById('descriptionModalBody').innerHTML = html;
                    document.getElementById('descriptionModalLabel').textContent = 'Mô tả chi tiết';
                    descriptionModal.show();
                })
                .catch(e => alert(e.message));
        });
    });

    // Hiển thị thông tin biến thể khi click tên biến thể
    document.querySelectorAll('.show-variant-info').forEach(link => {
        link.addEventListener('click', function () {
            document.getElementById('modalVariantName').innerText = this.dataset.name;
            document.getElementById('modalVariantImage').src = this.dataset.image;
            document.getElementById('modalVariantPrice').innerText = this.dataset.price;
            document.getElementById('modalVariantStock').innerText = this.dataset.stock;
            document.getElementById('modalVariantStatus').innerText = this.dataset.status;
        });
    });

    // Modal đổi trạng thái sản phẩm
    document.querySelectorAll('.product-status-badge').forEach(badge => {
        badge.addEventListener('click', function () {
            const id = badge.getAttribute('data-id');
            const name = badge.getAttribute('data-name');
            const status = badge.getAttribute('data-status');
            const nextStatusText = status == '1' ? 'Ẩn' : 'Đang bán';
            document.getElementById('toggleStatusModalText').innerHTML = `Bạn có chắc muốn đổi trạng thái sản phẩm <strong>${name}</strong> sang <span class="text-primary">${nextStatusText}</span>?`;
            document.getElementById('toggleStatusForm').action = `/admin/products/${id}/toggle`;
            (new bootstrap.Modal(document.getElementById('toggleStatusModal'))).show();
        });
    });

    // Modal đổi trạng thái biến thể (phải có modal trong html với id changeVariantStatusModal và form id change-variant-status-form)
    if (document.getElementById('changeVariantStatusModal')) {
        document.querySelectorAll('.variant-status-badge').forEach(badge => {
            badge.addEventListener('click', function () {
                const id = badge.getAttribute('data-id');
                const name = badge.getAttribute('data-name');
                const status = badge.getAttribute('data-status');
                const nextStatusText = status == '1' ? 'Ngừng bán' : 'Đang bán';
                document.getElementById('changeVariantStatusModalText').innerHTML = `Bạn có muốn đổi trạng thái biến thể <strong>${name}</strong> sang <span class="text-primary">${nextStatusText}</span>?`;
                document.getElementById('change-variant-status-form').action = `/admin/products/variant/${id}/toggle-status`;
                (new bootstrap.Modal(document.getElementById('changeVariantStatusModal'))).show();
            });
        });
    }

    // Modal cập nhật số lượng biến thể
    document.querySelectorAll('.edit-stock-btn').forEach(btn => {
        btn.addEventListener('click', function () {
            document.getElementById('stockVariantId').value = btn.getAttribute('data-id');
            document.getElementById('stockInput').value = btn.getAttribute('data-stock');
            document.getElementById('variantNameHelp').textContent = `Biến thể: ${btn.getAttribute('data-name')}`;
            document.getElementById('editStockForm').action = `/admin/products/variant/${btn.getAttribute('data-id')}/update-stock`;
        });
    });

    // DataTable: remove Vietnamese tones cho search
    function removeVietnameseTones(str) {
        if (!str) return '';
        str = str.toLowerCase();
        str = str.replace(/á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ/g, "a");
        str = str.replace(/é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ/g, "e");
        str = str.replace(/i|í|ì|ỉ|ĩ|ị/g, "i");
        str = str.replace(/ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ/g, "o");
        str = str.replace(/ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự/g, "u");
        str = str.replace(/ý|ỳ|ỷ|ỹ|ỵ/g, "y");
        str = str.replace(/đ/g, "d");
        str = str.replace(/\u0300|\u0301|\u0303|\u0309|\u0323/g, "");
        str = str.replace(/\u02C6|\u0306|\u031B/g, "");
        str = str.replace(/[^a-z0-9\s]/g, "");
        return str;
    }
    if (typeof $ !== 'undefined' && $.fn.dataTable) {
        $.fn.dataTable.ext.type.search.string = function (data) {
            return !data ? '' : removeVietnameseTones(data);
        };
        const variantTable = $('#variantTable').DataTable({
            pagingType: 'full_numbers',
            lengthMenu: [10, 25, 50, 100],
            language: {
                lengthMenu: 'Hiển thị _MENU_ dòng/trang',
                zeroRecords: 'Không tìm thấy dữ liệu',
                info: 'Hiển thị _START_ đến _END_ của _TOTAL_ dòng',
                infoEmpty: 'Không có dữ liệu',
                infoFiltered: '(lọc từ _MAX_ dòng)',
                search: 'Tìm kiếm:',
                paginate: {
                    first: '1',
                    last: '',
                    next: '>',
                    previous: '<',
                },
            },
            columnDefs: [{ orderable: false, targets: [1] }],
        });
        variantTable.on('draw.dt', function () {
            const totalPages = variantTable.page.info().pages;
            if (totalPages === 1) {
                $('.paginate_button').not('.previous,.active,.next').hide();
                $('.paginate_button.last').hide();
            } else {
                $('.paginate_button').show();
                $('.paginate_button.last a').text(totalPages);
                $('.paginate_button.first a').text(1);
            }
        });
    }

    // Toast thông báo khi có session (SweetAlert2)
       function showToastSwal(msg, success = true) {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: success ? 'success' : 'error',
            title: msg,
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            customClass: { popup: 'swal2-smaller-toast' }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        @if(session('success'))
            showToastSwal("{{ session('success') }}", true);
        @endif
        @if(session('error'))
            showToastSwal("{{ session('error') }}", false);
        @endif
    });

});
</script>


@endsection