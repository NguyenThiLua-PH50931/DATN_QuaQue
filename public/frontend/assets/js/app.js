document.addEventListener('DOMContentLoaded', function() {
    if (window.feather) feather.replace();
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
        new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Quickview modal handler
    document.querySelectorAll('.quickview-btn').forEach(function(btn) {
        btn.addEventListener('click', function() {
            // Lấy data từ button
            var name = btn.getAttribute('data-name') || '';
            var price = btn.getAttribute('data-price') || '';
            var rating = btn.getAttribute('data-rating') || '';
            var description = btn.getAttribute('data-description') || '';
            var code = btn.getAttribute('data-code') || '';
            var category = btn.getAttribute('data-category') || '';
            var region = btn.getAttribute('data-region') || '';
            var image = btn.getAttribute('data-image') || '';
            var link = btn.getAttribute('data-link') || '#';
            var descriptionImages = [];
            try {
                descriptionImages = JSON.parse(btn.getAttribute('data-description-images'));
            } catch (e) {}

            document.getElementById('quickview-name').textContent = name;
            document.getElementById('quickview-price').textContent = price;
            document.getElementById('quickview-rating').innerHTML = renderStars(rating);
            document.getElementById('quickview-description').innerHTML = description || '<em>Không có mô tả</em>';
            document.getElementById('quickview-code').textContent = code;
            document.getElementById('quickview-category').textContent = category;
            document.getElementById('quickview-region').textContent = region;
            document.getElementById('quickview-link').setAttribute('href', link);
            document.getElementById('quickview-main-img').setAttribute('src', image);
            // Render thumbnails
            var thumbWrap = document.getElementById('quickview-thumbnails');
            thumbWrap.innerHTML = '';
            if (descriptionImages && descriptionImages.length > 0) {
                descriptionImages.forEach(function(img, idx) {
                    var thumb = document.createElement('img');
                    thumb.src = img;
                    thumb.className = 'img-thumbnail rounded-2';
                    thumb.style.width = '60px';
                    thumb.style.height = '60px';
                    thumb.style.objectFit = 'cover';
                    thumb.style.cursor = 'pointer';
                    thumb.addEventListener('click', function() {
                        document.getElementById('quickview-main-img').setAttribute('src', img);
                    });
                    thumbWrap.appendChild(thumb);
                });
            }
        });
    });

    function renderStars(rating) {
        var html = '';
        var avg = Math.round(parseFloat(rating) || 0);
        for (var i = 1; i <= 5; i++) {
            html += '<i data-feather="star" class="' + (i <= avg ? 'fill' : '') + '"></i>';
        }
        setTimeout(function() { if (window.feather) feather.replace(); }, 10);
        return html;
    }
});