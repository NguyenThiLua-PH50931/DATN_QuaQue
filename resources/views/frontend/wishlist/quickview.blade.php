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
                                <h4 class="description-label" style="font-size:20px; color:#0da487; font-weight:600;">Mô tả sản phẩm:</h4>
                                <p class="description-text" style="font-size:19px;"></p>
                                <button class="btn btn-link p-0 show-more-btn" style="display:none">Xem thêm</button>
                                <div class="product-origin" style="margin-top: 15px;">
                                    <h4 class="origin-label" style="font-size:20px; color:#0da487; display:inline;">Xuất xứ:</h4>
                                    <span class="origin-text" style="font-size:19px; margin-left: 10px;"></span>
                                </div>
                            </div>
                            <ul class="brand-list" style="margin-top: 18px;"></ul>
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

@push('styles')
<style>
    /* Quickview Modal Custom Styles */
    #view .main-image-wrapper {
        width: 350px;
        height: 350px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f8f8f8;
        border-radius: 16px;
        overflow: hidden;
        margin: 0 auto;
    }
    #view .main-quickview-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        border-radius: 16px;
        display: block;
        background: #f8f8f8;
    }
    #view .description-thumbnails {
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: center;
        margin-top: 16px;
        gap: 8px;
        flex-wrap: wrap;
    }
    #view .description-thumbnails img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
        cursor: pointer;
        margin-right: 0;
        transition: border 0.2s, box-shadow 0.2s;
        background: #f8f8f8;
    }
    #view .description-thumbnails img.active {
        border: 2px solid #0da487;
        box-shadow: 0 0 0 2px #0da48733;
    }
    #view .description-thumbnails span {
        color: #888;
        font-size: 15px;
    }
    #view .description-text {
        font-size: 20px !important;
        color: #222;
        margin-bottom: 8px;
        min-height: 32px;
        white-space: pre-line;
        word-break: break-word;
        max-height: 180px;
        overflow: hidden;
        position: relative;
        transition: max-height 0.3s;
    }
    #view .origin-text {
        font-size: 20px !important;
        color: #222;
        display: inline !important;
    }
    #view .product-origin {
        display: inline-block !important;
        margin-top: 15px !important;
    }
    #view .product-rating .rating li i {
        color: #ffc107 !important;
    }
    #view .product-rating .rating li i svg {
        stroke: #ffc107 !important;
        fill: none !important;
    }
    #view .product-rating .rating li i.fill svg {
        stroke: #ffc107 !important;
        fill: #ffc107 !important;
    }
    #view .description-text.expanded {
        max-height: none;
        overflow: visible;
    }
    #view .show-more-btn {
        color: #0da487;
        font-size: 17px;
        font-weight: 500;
        margin-top: 4px;
        cursor: pointer;
        background: none;
        border: none;
        text-decoration: underline;
        display: inline-block;
    }
    .desc-thumb.active {
        border: 2px solid #ff6f61 !important;
        box-shadow: 0 0 0 2px #ff6f6133;
    }
    .description-thumbnails img {
        transition: border 0.2s, box-shadow 0.2s;
    }
    .modal-backdrop.show { z-index: 1050; }
    .modal-backdrop { opacity: 0.5 !important; }
    #view .product-rating .rating { padding-left:0; margin-bottom:0; display:inline-flex; }
    #view .product-rating .rating li { list-style:none; margin-right:2px; }
    #view .product-rating .rating li i { color: #ffc107 !important; font-size: 1.3rem !important; }
    .description-label, .origin-label { font-size: 20px !important; color: #0da487 !important; font-weight: 600; }
    @media (max-width: 991.98px) {
      .description-label, .origin-label { font-size: 18px !important; }
    }
</style>
@endpush

@push('scripts')
<script>
// Hàm rút gọn HTML theo số ký tự, không cắt giữa thẻ
function truncateHTML(html, maxLength) {
    var div = document.createElement('div');
    div.innerHTML = html;
    var result = '';
    var len = 0;
    function walk(node) {
        if (len >= maxLength) return;
        if (node.nodeType === 3) { // text
            var text = node.nodeValue;
            if (len + text.length > maxLength) {
                result += text.slice(0, maxLength - len);
                len = maxLength;
            } else {
                result += text;
                len += text.length;
            }
        } else if (node.nodeType === 1) { // element
            result += `<${node.nodeName.toLowerCase()}`;
            for (var i = 0; i < node.attributes.length; i++) {
                var attr = node.attributes[i];
                result += ` ${attr.name}="${attr.value}"`;
            }
            result += '>';
            for (var j = 0; j < node.childNodes.length; j++) {
                walk(node.childNodes[j]);
                if (len >= maxLength) break;
            }
            result += `</${node.nodeName.toLowerCase()}>`;
        }
    }
    for (var i = 0; i < div.childNodes.length; i++) {
        walk(div.childNodes[i]);
        if (len >= maxLength) break;
    }
    if (len >= maxLength) result += '...';
    return result;
}
document.addEventListener('DOMContentLoaded', function() {
    function bindQuickviewEvents() {
        document.querySelectorAll('.quickview-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                const slug = this.dataset.slug;
                if (!slug) return alert('Không có slug sản phẩm.');

                const modal = document.getElementById('view');
                if (!modal) return alert('Modal Quick View chưa tồn tại trên trang.');

                                        // Reset modal trước khi load dữ liệu mới
                        modal.querySelector('.main-quickview-image').src = '';
                        modal.querySelector('.title-name').textContent = '';
                        modal.querySelector('.price').textContent = '';
                        modal.querySelector('.description-text').innerHTML = '';
                        modal.querySelector('.origin-text').innerHTML = '';
                        modal.querySelector('.product-rating').innerHTML = '';
                        modal.querySelector('.brand-list').innerHTML = '';
                        modal.querySelector('.description-thumbnails').innerHTML = '';
                        modal.querySelector('.show-more-btn').style.display = 'none';

                // Mở modal Bootstrap
                const bsModal = new bootstrap.Modal(modal);
                bsModal.show();

                fetch(`/client/san-pham/quickview/${slug}`)
                    .then(res => {
                        if (!res.ok) throw new Error(`Lỗi tải dữ liệu (${res.status})`);
                        return res.json();
                    })
                    .then(data => {
                        const product = data.product;

                        modal.querySelector('.main-quickview-image').src = product.image ||
                            '/assets/images/no-image.png';
                        modal.querySelector('.title-name').textContent = product.name;

                        // Biến thể đã là mảng chuẩn, lấy biến thể đầu tiên
                        const variantsArray = product.variants || [];
                        if (variantsArray.length > 0) {
                            modal.querySelector('.price').textContent = Number(
                                variantsArray[0].price).toLocaleString() + ' đ';
                        } else {
                            modal.querySelector('.price').textContent = 'Liên hệ';
                        }

                        // Render rating sao + số lượng đánh giá
                        var ratingHtml = '<ul class="rating">';
                        var ratingNum = Number(product.avg_rating);
                        for (var i = 1; i <= 5; i++) {
                            ratingHtml += '<li><i class="fa fa-star' + (i <= Math.round(ratingNum) ? '' : '-o') + '" style="color:#ffc107 !important; font-size:1.3rem !important;"></i></li>';
                        }
                        ratingHtml += '</ul>';
                        ratingHtml += '<span class="ms-2 small text-muted">(' + (product.total_reviews || 0) + ' đánh giá)</span>';
                        modal.querySelector('.product-rating').innerHTML = ratingHtml;
                        feather.replace();

                        // Render ảnh mô tả
                        const thumbContainer = modal.querySelector('.description-thumbnails');
                        const descriptionImages = product.description_images || [];
                        if (descriptionImages.length > 0) {
                            descriptionImages.forEach((imgUrl, idx) => {
                                const thumb = document.createElement('img');
                                thumb.src = imgUrl;
                                thumb.alt = 'Ảnh mô tả ' + (idx + 1);
                                thumb.className = 'desc-thumb';
                                if (idx === 0) thumb.classList.add('active');
                                thumb.addEventListener('click', function() {
                                    modal.querySelector('.main-quickview-image').src = imgUrl;
                                    thumbContainer.querySelectorAll('img').forEach(t => t.classList.remove('active'));
                                    thumb.classList.add('active');
                                });
                                thumbContainer.appendChild(thumb);
                            });
                        } else {
                            thumbContainer.innerHTML = '<span>Không có ảnh mô tả</span>';
                        }

                                                                        // Xử lý mô tả sản phẩm với nút "xem thêm"
                        const descElem = modal.querySelector('.description-text');
                        const showMoreBtn = modal.querySelector('.show-more-btn');
                        const maxDescLength = 300; // ký tự

                        if (product.description && product.description.replace(/<[^>]+>/g, '').trim().length > maxDescLength) {
                            // Rút gọn mô tả
                            const shortDesc = product.description.substring(0, maxDescLength) + '...';
                            descElem.innerHTML = shortDesc;
                            descElem.classList.remove('expanded');
                            showMoreBtn.style.display = 'inline-block';
                            showMoreBtn.onclick = function() {
                                // Dẫn đến trang chi tiết sản phẩm thay vì mở rộng mô tả
                                window.location.href = `/client/san-pham/${slug}`;
                            };
                        } else {
                            descElem.innerHTML = product.description || '';
                            descElem.classList.add('expanded');
                            showMoreBtn.style.display = 'none';
                        }

                        // Hiển thị xuất xứ sản phẩm
                        const originElem = modal.querySelector('.origin-text');
                        originElem.innerHTML = product.origin || 'Chưa có thông tin';

                        // Nút xem chi tiết chuyển đến trang chi tiết sản phẩm
                        modal.querySelector('.view-button').onclick = () => {
                            window.location.href = `/client/san-pham/${slug}`;
                        };
                    })
                    .catch(err => {
                        console.error('Lỗi tải Quick View:', err);
                        alert('Không thể tải thông tin sản phẩm, vui lòng thử lại sau.');
                        bsModal.hide();
                    });
            });
        });
    }
    bindQuickviewEvents();

    // Sửa lỗi overlay đen không tắt khi đóng modal và không khóa scroll
    var modal = document.getElementById('view');
    // Đảm bảo khi modal ẩn thì backdrop cũng bị xóa nếu không còn modal nào mở
    modal.addEventListener('hidden.bs.modal', function () {
        setTimeout(function() {
            // Kiểm tra còn modal nào mở không
            if (!document.querySelector('.modal.show')) {
                var backdrop = document.querySelector('.modal-backdrop');
                if (backdrop) backdrop.remove();
                document.body.classList.remove('modal-open');
                document.body.style.overflow = '';
            }
        }, 100);
    });
    // Xử lý khi click nút X hoặc click ra ngoài
    document.querySelectorAll('.btn-close').forEach(function(btn) {
        btn.addEventListener('click', function() {
            setTimeout(function() {
                if (!document.querySelector('.modal.show')) {
                    var backdrop = document.querySelector('.modal-backdrop');
                    if (backdrop) backdrop.remove();
                    document.body.classList.remove('modal-open');
                    document.body.style.overflow = '';
                }
            }, 100);
        });
    });
});
</script>
@endpush
