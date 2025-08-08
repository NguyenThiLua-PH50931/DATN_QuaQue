<link rel="stylesheet" href="{{ asset('resources/views/frontend/products/review.css') }}">
<div class="review-box">
    <div class="row">
        <div class="col-xl-5">
            <div class="product-rating-box">
                <div class="row">
                    <div class="col-xl-12">
                        <div class="product-main-rating">
                            <h2>
                                {{ number_format($averageRating, 2) }}
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star">
                                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                </svg>
                            </h2>
                            <h5>{{ $totalReviews }} Đánh giá</h5>
                        </div>
                    </div>

                    <div class="col-xl-12">
                        <ul class="product-rating-list">
                            @for ($i = 5; $i >= 1; $i--)
                            @php
                            $count = $ratingsCount[$i] ?? 0;
                            $percent = $totalReviews > 0 ? round($count / $totalReviews * 100) : 0;
                            @endphp
                            <li>
                                <div class="rating-product">
                                    <h5>
                                        {{ $i }}
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-star">
                                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon>
                                        </svg>
                                    </h5>
                                    <div class="progress">
                                        <div class="progress-bar" style="width: {{ $percent }}%;"></div>
                                    </div>
                                    <h5 class="total">{{ $count }}</h5>
                                </div>
                            </li>
                            @endfor
                        </ul>

                        <div class="review-title-2">
                            <h4 class="fw-bold mb-2">Bộ lọc</h4>
                            <h4 class="mb-2">Lọc sao</h4>
                            <select class="form-select select-form-size mb-2" id="filter-star">
                                <option value="all" selected>Tất cả</option>
                                @foreach($starOptions as $star)
                                <option value="{{ $star }}">{{ $star }} sao</option>
                                @endforeach
                            </select>
                            <h4 class="mb-2">Lọc phân loại</h4>
                            <select class="form-select select-form-size mb-3" id="filter-variant">
                                <option value="all" selected>Tất cả</option>
                                @foreach($variants as $variant)
                                @if($variantOptions->contains($variant->id))
                                <option value="{{ $variant->id }}">{{ $variant->name ?? 'Phân loại #'.$variant->id }}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-7">
           <div class="review-people">
    <ul class="review-list" id="review-list">
        @forelse($reviews as $review)
            @php
                $avatar = ($review->user && $review->user->avatar)
                    ? asset('storage/' . $review->user->avatar)
                    : 'https://ui-avatars.com/api/?name=' . urlencode($review->user->name ?? 'Ẩn danh');
            @endphp
            <li class="review-item"
                data-rating="{{ $review->rating }}"
                data-variant="{{ $review->product_variant_value_id }}">
                <div class="people-box d-flex gap-3">
                    <!-- Avatar -->
                    <div>
                        <div class="people-image people-text">
                            <img alt="user" class="img-fluid"
                                 style="width:48px;height:48px;object-fit:cover;border-radius:50%;"
                                 src="{{ $avatar }}">
                        </div>
                    </div>
                    <!-- Thông tin bình luận -->
                    <div class="people-comment flex-grow-1">
                        <div class="people-name d-flex align-items-center gap-2">
                            <span class="fw-bold">{{ $review->user->name ?? 'Ẩn danh' }}</span>
                            <span class="text-muted" style="font-size:0.95em;">
                                {{ $review->created_at->format('d/m/Y H:i') }}
                            </span>
                            <div class="product-rating ms-2">
                                <ul class="rating mb-0" style="display: inline-flex;">
                                    @for ($i = 1; $i <= 5; $i++)
                                        <li>
                                            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20"
                                                 viewBox="0 0 24 24"
                                                 fill="none" stroke="currentColor" stroke-width="2"
                                                 stroke-linecap="round" stroke-linejoin="round"
                                                 class="feather feather-star{{ $i <= $review->rating ? ' fill' : '' }}">
                                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                                            </svg>
                                        </li>
                                    @endfor
                                </ul>
                            </div>
                        </div>
                        <div class="reply mt-1">
                            <div class="product-variant mb-2 text-muted">
                                Phân loại:
                                <b>{{ $review->product_variant_name ?? 'Không xác định' }}</b>
                            </div>
                            <p class="mb-0">{{ $review->content }}</p>
                        </div>
                    </div>
                </div>
            </li>
        @empty
            <li>
                <div class="people-box text-center">
                    <span class="text-muted">Chưa có đánh giá nào cho sản phẩm này.</span>
                </div>
            </li>
        @endforelse
    </ul>
</div>

</div>
</div>
</div>
<script>
    $(document).ready(function() {
    function filterReviews() {
        let rating = $('#filter-star').val();
        let variant = $('#filter-variant').val();

        $('#review-list .review-item').each(function() {
            let show = true;
            if (rating !== 'all' && String($(this).data('rating')) !== rating) show = false;
            if (variant !== 'all' && String($(this).data('variant')) !== variant) show = false;
            $(this).toggle(show);
        });
    }

    $('#filter-star, #filter-variant').on('change', filterReviews);
});

</script>
<style>
    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .custom-select .select2-container .select2-selection {
        width: 100% !important;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 17px 23px;
        gap: 14px;
        margin-bottom: 22px;
        border-radius: 8px;
        background-color: #f8f8f8;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-image {
        width: 80px;
        border-radius: 5px;
        overflow: hidden;
        background-color: #fff;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .name {
        font-size: calc(14px + 1 * (100vw - 320px) / 1600);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 6px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating label {
        color: #4a5568;
        font-weight: 400;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating .price-number {
        line-height: 1;
        color: rgba(74, 85, 104, 0.6);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 3px;
        margin: 5px 0 0;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating .rating-number {
        line-height: 1;
        margin-left: 8px;
        color: rgba(27, 27, 27, 0.6);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 3px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating .rating-number i {
        margin-top: -1px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .product-wrapper .product-content .product-review-rating .product-rating .rating li {
        line-height: 1;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .review-box+.review-box {
        margin-top: 21px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .review-box .product-review-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 9px;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .review-box .product-review-rating .product-rating .rating li {
        line-height: 1;
    }

    .question-modal .modal-dialog .modal-content .modal-body .product-review-form .review-box .product-review-rating .product-rating .rating li i {
        font-size: 16px;
    }

    .product-review-form .product-wrapper {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding: 17px 23px;
        gap: 14px;
        margin-bottom: 22px;
        border-radius: 8px;
        background-color: #f8f8f8;
    }

    .product-review-form .product-wrapper .product-image {
        width: 80px;
        border-radius: 5px;
        overflow: hidden;
        background-color: #fff;
    }

    .product-review-form .product-wrapper .product-content .name {
        font-size: calc(14px + 1 * (100vw - 320px) / 1600);
        font-weight: 600;
        margin-bottom: 4px;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 6px;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating label {
        color: #4a5568;
        font-weight: 400;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating .price-number {
        line-height: 1;
        color: rgba(74, 85, 104, 0.6);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 3px;
        margin: 5px 0 0;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating .rating-number {
        line-height: 1;
        margin-left: 8px;
        color: rgba(27, 27, 27, 0.6);
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 3px;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating .rating-number i {
        margin-top: -1px;
    }

    .product-review-form .product-wrapper .product-content .product-review-rating .product-rating .rating li {
        line-height: 1;
    }

    .product-review-form .review-box+.review-box {
        margin-top: 21px;
    }

    .product-review-form .review-box .product-review-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 9px;
    }

    .product-review-form .review-box .product-review-rating .product-rating .rating li {
        line-height: 1;
    }

    .product-review-form .review-box .product-review-rating .product-rating .rating li i {
        font-size: 16px;
    }

    .product-section-box .review-box .product-rating-box .product-main-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 25px;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 14px;
    }

    .product-section-box .review-box .product-rating-box .product-main-rating h2 {
        font-weight: 500;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        gap: 5px;
        font-size: calc(28px + 9 * (100vw - 320px) / 1600);
    }

    .product-section-box .review-box .product-rating-box .product-main-rating h2 i {
        font-size: calc(19px + 4 * (100vw - 320px) / 1600);
        font-weight: normal;
        color: #ffb321;
    }

    .product-section-box .review-box .product-rating-box .product-main-rating h2 .feather {
        width: calc(19px + 4 * (100vw - 320px) / 1600);
        height: calc(19px + 4 * (100vw - 320px) / 1600);
        fill: #ffb321;
        stroke: #ffb321;
    }

    .product-section-box .review-box .product-rating-box .product-main-rating h5 {
        line-height: 1.4;
        font-weight: 400;
        color: #4a5568;
        font-size: 17px;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list {
        display: grid;
        gap: calc(7px + 5 * (100vw - 320px) / 1600);
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li:nth-child(4) .rating-product .progress .progress-bar {
        background-color: #ffa53b;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li:last-child .rating-product .progress .progress-bar {
        background-color: #ff4f4f;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 12px;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product h5 {
        white-space: nowrap;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 3px;
        font-size: 18px;
        width: 40px;
        font-weight: 600;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product h5 i {
        font-size: 15px;
        font-weight: normal;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product h5 .feather {
        width: 15px;
        height: 15px;
        fill: #222;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product .progress {
        width: calc(100% - 12px - 28px - 15px);
        height: 9px;
        border-radius: 100px;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product .progress .progress-bar {
        background-color: var(--theme-color);
        border-radius: 100px;
    }

    .product-section-box .review-box .product-rating-box .product-rating-list li .rating-product .total {
        white-space: nowrap;
        width: auto;
        color: rgba(74, 85, 104, 0.878);
        font-size: 16px;
        font-weight: 400;
        width: 15px;
        display: block;
    }

    .product-section-box .review-box .review-people {
        border-left: 1px solid #ececec;
        padding-left: 23px;
        max-height: 421px;
        height: 100%;
        overflow: auto;
    }

    body.rtl .product-section-box .review-box .review-people {
        border-right-width: 1px;
        border-right-style: solid;
        padding-right: 23px;
        border-left: unset;
        padding-left: unset;
    }

    @media (max-width: 1199px) {
        .product-section-box .review-box .review-people {
            padding-left: unset;
            padding-top: 23px;
            border-left: unset;
            border-top: 1px solid rgba(119, 119, 119, 0.44);
            margin-top: 23px;
        }

        body.rtl .product-section-box .review-box .review-people {
            padding-right: 0;
            border-right: unset;
        }
    }

    .product-section-box .review-box .review-people::-webkit-scrollbar-track {
        border-radius: 10px;
        background-color: rgba(85, 85, 85, 0.14);
    }

    .product-section-box .review-box .review-people::-webkit-scrollbar {
        width: 4px;
        background-color: #f5f5f5;
        border-radius: 50px;
    }

    .product-section-box .review-box .review-people::-webkit-scrollbar-thumb {
        border-radius: 10px;
        background-color: rgba(85, 85, 85, 0.5);
    }

    @media (max-width: 991px) {
        .product-section-box .review-box .review-people {
            border: none;
            padding: 0;
            margin-top: 25px;
        }
    }

    .product-section-box .review-box .review-people .review-list {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        gap: 17px;
    }

    body.rtl .product-section-box .review-box .review-people .review-list {
        padding-right: 0;
    }

    .product-section-box .review-box .review-people .review-list li {
        display: block;
        width: 100%;
    }

    .product-section-box .review-box .review-people .review-list li .people-box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        gap: 15px;
        width: 100%;
        border-radius: 10px;
        padding: calc(16px + 4 * (100vw - 320px) / 1600) calc(16px + 10 * (100vw - 320px) / 1600);
        background: #f8f8f8;
    }

    @media (max-width: 480px) {
        .product-section-box .review-box .review-people .review-list li .people-box {
            display: block;
        }
    }

    .product-section-box .review-box .review-people .review-list li .people-box:hover .reply a {
        opacity: 1;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-image {
        width: 70px;
        height: 70px;
        margin: 0 auto 8px;
    }

    @media (max-width: 480px) {
        .product-section-box .review-box .review-people .review-list li .people-box .people-image {
            margin: 0 auto;
        }
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-image img {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        -o-object-fit: cover;
        object-fit: cover;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    @media (max-width: 575px) {
        .product-section-box .review-box .review-people .review-list li .people-box .people-image img {
            border-radius: 8px;
        }
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-text .user-round {
        width: 70px;
        height: 70px;
        -o-object-fit: contain;
        object-fit: contain;
        background-color: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        font-size: 40px;
        border-radius: 7px;
        -webkit-box-shadow: 0 0 6px rgba(34, 34, 34, 0.16);
        box-shadow: 0 0 6px rgba(34, 34, 34, 0.16);
        margin: 0 auto;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-text .user-round h4 {
        font-size: 41px;
        font-weight: 600;
        color: var(--theme-color);
    }

    .product-section-box .review-box .review-people .review-list li .people-box .name-user {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        width: 70px;
        height: 70px;
        background-color: #ececec;
        border-radius: 10px;
        color: #4a5568;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .name-user h3 {
        font-size: 37px;
        font-weight: 600;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment {
        width: calc(100% - 70px - 15px);
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        gap: 8px;
    }

    @media (max-width: 400px) {
        .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name {
            display: block;
        }
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name h5 .name {
        display: block;
        font-weight: 600;
        font-size: 17px;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name h5 .name:hover {
        color: var(--theme-color);
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name .date-time {
        width: 100%;
        margin-top: 4px;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name .date-time h6 {
        font-size: 13px;
        color: #777;
        margin-block: 4px 6px;
        -webkit-line-clamp: 1;
        -webkit-box-orient: vertical;
        display: -webkit-box;
        overflow: hidden;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .people-name .product-rating {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .name {
        white-space: nowrap;
        display: block;
        font-weight: 600;
        font-size: calc(14px + 1 * (100vw - 320px) / 1600);
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .name:hover {
        color: var(--theme-color);
    }

    body.rtl .product-section-box .review-box .review-people .review-list li .people-box .people-comment {
        padding-left: unset;
        padding-right: 15px;
    }

    @media (max-width: 480px) {
        .product-section-box .review-box .review-people .review-list li .people-box .people-comment {
            padding: 0;
            width: 100%;
            margin-top: 16px;
        }
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .date-time {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        width: 100%;
        margin: 0;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .people-comment .date-time h6 {
        font-size: 13px;
        color: #777;
        margin: 0;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .reply {
        margin-top: 6px;
        line-height: 1.6;
        color: #4a5568;
        position: relative;
    }

    .product-section-box .review-box .review-people .review-list li .people-box .reply p {
        margin: 0;
        line-height: 1.6;
    }

    @media (max-width: 480px) {
        .product-section-box .review-box .review-people .review-list li .people-box .reply p {
            width: 100%;
        }
    }


    .product-theme-box .review-rating span {
        font-size: 13px;
        line-height: 1;
        margin-top: 1px;
    }


    .review-box:hover .review-profile .review-image {
        border-radius: 6px;
    }

    .review-box .review-contain {
        margin-bottom: 40px;
    }

    .review-box .review-contain h5 {
        font-size: 16px;
        line-height: 22px;
        font-weight: 600;
        margin-bottom: 15px;
    }

    .review-box .review-contain p {
        color: #4a5568;
        line-height: 27px;
        margin-bottom: 0;
    }

    .review-box .review-profile {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .review-box .review-profile .review-image {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        overflow: hidden;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    .review-box .review-profile .review-detail {
        padding-left: 12px;
    }

    [dir="rtl"] .review-box .review-profile .review-detail {
        padding-left: unset;
        padding-right: 12px;
    }

    .review-box .review-profile .review-detail h5 {
        font-size: 18px;
        margin-bottom: 7px;
        font-weight: 500;
        color: #222;
    }

    .review-box .review-profile .review-detail h6 {
        font-size: 16px;
        font-weight: 500;
        color: #4a5568;
    }

    .checkout-section-2 .left-sidebar-checkout .checkout-detail-box>ul>li .checkout-box .checkout-detail .custom-accordion .accordion-item .accordion-collapse .accordion-body .cod-review {
        margin: 0;
        line-height: 1.5;
        color: #4a5568;
    }

    .checkout-section-2 .left-sidebar-checkout .checkout-detail-box>ul>li .checkout-box .checkout-detail .custom-accordion .accordion-item .accordion-collapse .accordion-body .cod-review a:hover {
        color: var(--theme-color);
    }

    .review-title h4 {
        font-size: calc(16px + 2 * (100vw - 320px) / 1600);
        margin-bottom: calc(7px + 6 * (100vw - 320px) / 1600);
        color: #222;
        font-weight: 400;
    }

    .review-title h2 {
        font-size: calc(26px + 14 * (100vw - 320px) / 1600);
        margin-bottom: calc(12px + 18 * (100vw - 320px) / 1600);
        width: 80%;
        line-height: 1.3;
        position: relative;
    }

    @media (max-width: 480px) {
        .review-title h2 {
            width: 100%;
        }
    }

    .review-title h2.center::before {
        left: 50%;
        -webkit-transform: translateX(-50%);
        transform: translateX(-50%);
        color: var(--theme-color);
    }

    .review-section {
        background-color: #f8f8f8;
    }

    .review-section .reviewer-box {
        background-color: #fff;
        padding: calc(18px + 16 * (100vw - 320px) / 1600);
        border-radius: 10px;
        z-index: 0;
        position: relative;
        overflow: hidden;
    }

    .review-section .reviewer-box:hover i {
        color: var(--theme-color);
        font-size: 143px;
        opacity: 0.18;
        -webkit-transform: rotate(7deg);
        transform: rotate(7deg);
        bottom: -29px;
        right: -7px;
    }

    .review-section .reviewer-box i {
        position: absolute;
        font-size: 106px;
        opacity: 0.05;
        z-index: -1;
        -webkit-transform: rotate(15deg);
        transform: rotate(15deg);
        bottom: -30px;
        right: -8px;
        -webkit-transition: all 0.3s ease-in-out;
        transition: all 0.3s ease-in-out;
    }

    [dir="rtl"] .review-section .reviewer-box i {
        -webkit-transform: rotate(-15deg) rotateY(183deg);
        transform: rotate(-15deg) rotateY(183deg);
        left: -8px;
        right: unset;
    }

    .review-section .reviewer-box h3 {
        font-weight: 400;
        margin: 10px 0 13px;
        font-size: 20px;
        line-height: 1.5;
    }

    .review-section .reviewer-box p {
        color: #4a5568;
        line-height: 1.7;
        margin-bottom: 23px;
        font-size: calc(14px + 1 * (100vw - 320px) / 1600);
    }

    .review-section .reviewer-box .reviewer-profile {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        gap: calc(12px + 7 * (100vw - 320px) / 1600);
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }

    .review-section .reviewer-box .reviewer-profile .reviewer-image {
        width: calc(65px + 10 * (100vw - 320px) / 1600);
        height: auto;
        border-radius: 8px;
        overflow: hidden;
    }

    .review-section .reviewer-box .reviewer-profile .reviewer-image img {
        width: 100%;
        height: 100%;
        -o-object-fit: contain;
        object-fit: contain;
    }

    .review-section .reviewer-box .reviewer-profile .reviewer-name h4 {
        font-weight: 700;
        font-size: calc(16px + 2 * (100vw - 320px) / 1600);
        color: var(--theme-color);
    }

    .review-section .reviewer-box .reviewer-profile .reviewer-name h6 {
        color: #4a5568;
        margin-top: 6px;
    }

    .review-title-2 {
        border-top: 1px solid #ececec;
        padding-top: calc(13px + 12 * (100vw - 320px) / 1600);
        margin-top: calc(13px + 12 * (100vw - 320px) / 1600);
    }

    .review-title-2 h4 {
        font-size: calc(16px + 2 * (100vw - 320px) / 1600);
        color: #222;
        font-weight: 400;
    }

    .review-title-2 p {
        margin-bottom: calc(7px + 6 * (100vw - 320px) / 1600);
        margin-top: 3px;
        font-size: 15px;
        line-height: 1.6;
        color: #4a5568;
    }

    .review-title-2 button {
        width: 100%;
        padding: 10px 14px;
        background-color: #f8f8f8 !important;
        color: #4a5568 !important;
        border: 1px solid #eee !important;
        font-size: 16px;
    }

    .review-title-2 button:hover {
        background-color: #f8f8f8;
        color: #4a5568;
        border: 1px solid #ececec;
    }

    .product-section .right-box-contain .price-rating .custom-rate .review {
        font-size: 13px;
        margin-left: 12px;
    }

    [dir="rtl"] .product-section .right-box-contain .price-rating .custom-rate .review {
        margin-left: unset;
        margin-right: 12px;
    }
</style>