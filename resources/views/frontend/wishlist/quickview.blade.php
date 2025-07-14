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
                    <div class="col-lg-6 d-flex flex-column align-items-center justify-content-center">
                        <div class="slider-image w-100 d-flex flex-column align-items-center">
                            <div class="main-image-wrapper" style="width:350px; height:350px; display:flex; align-items:center; justify-content:center; background:#f8f8f8; border-radius:16px; overflow:hidden;">
                                <img src="" class="img-fluid blur-up lazyload main-quickview-image" alt="" style="width:100%; height:100%; object-fit:cover; border-radius:16px; display:block;">
                            </div>
                            <div class="description-thumbnails d-flex flex-row align-items-center mt-3" style="justify-content:center;"></div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="right-sidebar-modal">
                            <h4 class="title-name" style="color:#222;"></h4>
                            <h4 class="price theme-color"></h4>
                            <div class="product-rating"></div>
                            <div class="product-detail">
                                <h4 style="font-size:24px;">Mô tả sản phẩm:</h4>
                                <p class="description-text" style="font-size:20px;"></p>
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
            var descImgs = [];
            try {
                descImgs = JSON.parse(this.getAttribute('data-description-images')) || [];
            } catch(e) {}

            var modal = document.getElementById('view');
            modal.querySelector('.title-name').textContent = name;
            var priceElem = modal.querySelector('.price');
            priceElem.textContent = price;
            var mainImg = modal.querySelector('.main-quickview-image');
            mainImg.src = image;
            mainImg.alt = name;

            // Render description thumbnails
            var thumbContainer = modal.querySelector('.description-thumbnails');
            thumbContainer.innerHTML = '';
            if (descImgs && descImgs.length > 0) {
                descImgs.forEach(function(imgUrl, idx) {
                    var thumb = document.createElement('img');
                    thumb.src = imgUrl;
                    thumb.alt = 'Ảnh mô tả ' + (idx+1);
                    thumb.className = 'desc-thumb';
                    thumb.style.width = '60px';
                    thumb.style.height = '60px';
                    thumb.style.objectFit = 'cover';
                    thumb.style.borderRadius = '8px';
                    thumb.style.border = '2px solid #eee';
                    thumb.style.cursor = 'pointer';
                    thumb.style.marginRight = '8px';
                    if(idx === 0) thumb.classList.add('active');
                    thumb.addEventListener('click', function() {
                        mainImg.src = imgUrl;
                        thumbContainer.querySelectorAll('img').forEach(t => t.classList.remove('active'));
                        thumb.classList.add('active');
                    });
                    thumbContainer.appendChild(thumb);
                });
            }

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
