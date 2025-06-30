<!-- Quick View Modal Box Start -->
<div class="modal fade theme-modal view-modal" id="view" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-xl modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header p-0">
                <button type="button" class="btn-close" data-bs-dismiss="modal">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="row g-sm-4 g-2">
                    <div class="col-lg-6">
                        <div class="slider-image">
                            <img src="" class="img-fluid blur-up lazyload main-quickview-image" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-sidebar-modal">
                            <h4 class="title-name"></h4>
                            <h4 class="price"></h4>
                            <div class="product-rating"></div>
                            <div class="product-detail">
                                <h4>Mô tả sản phẩm:</h4>
                                <p class="description-text"></p>
                                <button class="btn btn-link p-0 show-more-btn" style="display:none">Xem thêm</button>
                            </div>
                            <ul class="brand-list"></ul>
                            <div class="modal-button d-flex gap-2 align-items-center">
                                <button class="btn theme-bg-color view-button icon text-white fw-bold btn-md">Xem chi tiết</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    function bindQuickviewEvents() {
    document.querySelectorAll('.quickview-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            var name = this.getAttribute('data-name') || '';
            var price = this.getAttribute('data-price') || '';
            var rating = this.getAttribute('data-rating') || '';
            var description = this.getAttribute('data-description') || '';
            var code = this.getAttribute('data-code') || '';
            var origin = this.getAttribute('data-origin') || '';
            var image = this.getAttribute('data-image') || '';
            var link = this.getAttribute('data-link') || '#';

                var modal = document.getElementById('view');
                modal.querySelector('.title-name').textContent = name;
                modal.querySelector('.price').textContent = price;
                modal.querySelector('.slider-image img').src = image;
                modal.querySelector('.slider-image img').alt = name;

            var ratingHtml = '';
            if (rating) {
                ratingHtml = '<ul class="rating">';
                for (var i = 1; i <= 5; i++) {
                    ratingHtml += '<li><i data-feather="star" class="' + (i <= Math.round(rating) ? 'fill' : '') + '"></i></li>';
                }
                ratingHtml += '</ul>';
                ratingHtml += '<span class="ms-2">' + rating + ' sao</span>';
            }
                modal.querySelector('.product-rating').innerHTML = ratingHtml;
            if (window.feather) feather.replace();

                var brandList = modal.querySelector('.brand-list');
            brandList.innerHTML = '';
            if (code) {
                brandList.innerHTML += `<li><div class="brand-box"><h5>Mã sản phẩm:</h5><h6>${code}</h6></div></li>`;
            }
            if (origin) {
                brandList.innerHTML += `<li><div class="brand-box"><h5>Xuất xứ:</h5><h6>${origin}</h6></div></li>`;
            }

                var detailBtn = modal.querySelector('.view-button');
            if (detailBtn && link) {
                detailBtn.onclick = function() {
                    window.location.href = link;
                };
            }

                // Mô tả sản phẩm rút gọn
                var descElem = modal.querySelector('.description-text');
                var showMoreBtn = modal.querySelector('.show-more-btn');
                var words = description.split(/\s+/);
                if (words.length > 40) {
                    var shortDesc = words.slice(0, 40).join(' ') + '...';
                    descElem.innerHTML = shortDesc;
                    showMoreBtn.style.display = 'inline-block';
                    showMoreBtn.onclick = function() {
                        window.location.href = link;
                    };
                } else {
                    descElem.innerHTML = description;
                    showMoreBtn.style.display = 'none';
            }
        });
    });
    }
    bindQuickviewEvents();
});
</script>
@endpush
