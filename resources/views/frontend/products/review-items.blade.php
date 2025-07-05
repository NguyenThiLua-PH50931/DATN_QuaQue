@forelse ($reviews as $review)
    <li>
        <!-- HTML hiển thị review -->
        <div class="people-box">
            <div>
                <div class="people-image">
                    <img src="{{ asset('assets/images/review/default.jpg') }}" class="img-fluid" />
                </div>
            </div>
            <div class="people-comment">
                <a class="name">{{ $review->user->name ?? 'Người dùng' }}</a>
                <div class="date-time">
                    <h6 class="text-content">{{ $review->created_at->format('d M, Y \a\t H:i') }}</h6>
                    <div class="product-rating">
                        <ul class="rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <li>
                                    <i data-feather="star" class="{{ $i <= $review->rating ? 'fill' : '' }}"></i>
                                </li>
                            @endfor
                        </ul>
                    </div>
                </div>
                <div class="reply">
                    <p>{{ $review->comment }}</p>
                </div>
            </div>
        </div>
    </li>
@empty
    <li><p class="text-muted">Không có đánh giá phù hợp.</p></li>
@endforelse