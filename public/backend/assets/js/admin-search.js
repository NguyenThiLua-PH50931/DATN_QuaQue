(function () {
    window.sidebarItems = window.sidebarItems || [
        { name: 'Trang chủ', url: '/admin/home' },
        { name: 'Sản Phẩm', url: '/admin/products' },
        { name: 'Danh sách sản phẩm', url: '/admin/products' },
        { name: 'Thêm sản phẩm', url: '/admin/products/create' },
        { name: 'Danh mục', url: '/admin/categories' },
        { name: 'Danh sách danh mục', url: '/admin/categories' },
        { name: 'Thùng rác', url: '/admin/categories/trashed' },
        { name: 'Vùng miền', url: '/admin/regions' },
        { name: 'Danh sách vùng miền', url: '/admin/regions' },
        { name: 'Thùng rác', url: '/admin/regions/trashed' },
        { name: 'Thuộc tính', url: '/admin/attributes' },
        { name: 'Danh sách thuộc tính', url: '/admin/attributes' },
        { name: 'Thêm thuộc tính', url: '/admin/attributes/create' },
        { name: 'Người dùng', url: '/admin/user/index' },
        { name: 'Tài khoản', url: '/admin/user/index' },
        { name: 'Tài khoản đã ẩn', url: '/admin/user/hidden' },
        { name: 'Bình luận', url: '/admin/comments' },
        { name: 'Danh sách bình luận', url: '/admin/comments/index' },
        { name: 'Tin tức', url: '/admin/blog/index' },
        { name: 'Danh sách tin tức', url: '/admin/blog/index' },
        { name: 'Banner', url: '/admin/banners' },
        { name: 'Danh sách banner', url: '/admin/banners' },
        { name: 'Thùng rác', url: '/admin/banners/trashed' },
        { name: 'Đơn hàng', url: '/admin/orders' },
        { name: 'Danh sách', url: '/admin/orders' },
        { name: 'Chi tiết', url: '/admin/orders/detail' },
        { name: 'Theo dõi đơn hàng', url: '/admin/orders/tracking' },
        { name: 'Đánh giá', url: '/admin/reviews' },
        { name: 'Cài đặt', url: '/admin/setting/profile' },
        { name: 'Chỉnh sửa hồ sơ', url: '/admin/setting/profile' },
        { name: 'Mã giảm giá', url: '/admin/coupon/index' },
        { name: 'Danh sách', url: '/admin/coupon/index' },
        { name: 'Create Coupon', url: '/admin/coupons/create' },
        { name: 'Support Ticket', url: '/admin/support-ticket' },
    ];

    var searchInput = $('#search-input');
    var typeaheadMenu = $('#typeahead-menu');
    var searchForm = $('#search-form');
    var pageWrapper = $('.page-wrapper');
    var pageBodyWrapper = $('.page-body-wrapper');

    // Hàm khôi phục hiển thị nội dung
    function restorePageContent() {
        $('body').removeClass('offcanvas');
        $('.search-full').removeClass('open');
        // Xóa triệt để overlay và backdrop
        $('.modal-backdrop, .overlay').removeClass('show').remove();
        pageWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        pageBodyWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        console.log('Restored page content visibility');
    }

    // Ngăn chặn submit mặc định
    searchForm.on('submit', function(e) {
        e.preventDefault();
        console.log('Form submit prevented');
    });

    // Vô hiệu hóa sự kiện gốc
    $(document).ready(function() {
        $('.header-search, .form-inline.search-full, .Typeahead-input').off('submit keypress keydown keyup input');
        $('.search-full input').off('keyup input');
        $('.search-full input').on('keyup input', function(e) {
            e.stopImmediatePropagation();
            console.log('Blocked original keyup/input event');
            restorePageContent();
        });
        console.log('Initialized event overrides');
        restorePageContent();
    });

    $(window).on('load', function() {
        $('.search-full input').off('keyup input');
        console.log('Re-ensured original events are removed');
        restorePageContent();
    });

    // Khởi tạo Typeahead
    var typeahead = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: window.sidebarItems
    });

    searchInput.typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'sidebar-items',
        display: 'name',
        source: typeahead,
        templates: {
            suggestion: function(data) { return '<div class="suggestion-item">' + data.name + '</div>'; },
            empty: '<div class="suggestion-item">Không tìm thấy kết quả</div>'
        }
    });

    function highlightSidebarItems(query) {
        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
        $('.sidebar-submenu').hide();
        if (query) {
            query = query.toLowerCase().trim();
            $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.includes(query)) {
                    $(this).addClass('active');
                    var parentSubmenu = $(this).closest('.sidebar-submenu');
                    if (parentSubmenu.length) {
                        parentSubmenu.show();
                        parentSubmenu.prev('.sidebar-title').find('span').addClass('active');
                    }
                }
            });
        }
    }

    searchInput.on('input', function(e) {
        var query = $(this).val();
        highlightSidebarItems(query);
        restorePageContent();
    });

    searchInput.on('typeahead:select', function(ev, suggestion) {
        try {
            $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
            $('.sidebar-links .sidebar-submenu a[href="' + suggestion.url + '"]').addClass('active');
            $('.sidebar-links .sidebar-title span:contains("' + suggestion.name + '")').addClass('active');
            window.location.href = suggestion.url;
        } catch (error) {
            console.error('Lỗi chuyển hướng:', error);
            restorePageContent();
        }
    });

    searchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            var query = $(this).val().toLowerCase().trim();
            if (query) {
                var matchedItem = window.sidebarItems.find(function(item) {
                    return item.name.toLowerCase() === query;
                });
                if (matchedItem) {
                    try {
                        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
                        $('.sidebar-links .sidebar-submenu a[href="' + matchedItem.url + '"]').addClass('active');
                        $('.sidebar-links .sidebar-title span:contains("' + matchedItem.name + '")').addClass('active');
                        window.location.href = matchedItem.url;
                    } catch (error) {
                        console.error('Lỗi chuyển hướng:', error);
                        restorePageContent();
                    }
                } else {
                    typeaheadMenu.html('<div class="suggestion-item">Không tìm thấy kết quả</div>');
                    restorePageContent();
                }
            }
        }
    });

    $('.close-search').on('click', function() {
        searchInput.typeahead('val', '');
        typeaheadMenu.empty();
        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
        $('.sidebar-submenu').hide();
        restorePageContent();
    });

    $(document).on('click keypress', '.search-full, .Typeahead-input', function(e) {
        e.stopPropagation();
        restorePageContent();
    });

    // Xử lý khi modal đóng
    $(document).on('hidden.bs.modal', '.modal', function () {
        console.log('Modal đã đóng, khôi phục giao diện');
        $('.modal-backdrop').remove();
        restorePageContent();
    });

    // Xử lý lỗi 404 cho hình ảnh
    $(document).ready(function() {
        $('img').on('error', function() {
            console.warn('Không thể tải hình ảnh:', $(this).attr('src'));
            $(this).hide();
            restorePageContent();
        });
    });

    // Thêm sự kiện click để đóng overlay thủ công (phòng trường hợp bị kẹt)
    $(document).on('click', '.modal-backdrop, .overlay', function() {
        $(this).removeClass('show').remove();
        restorePageContent();
    });
})();
