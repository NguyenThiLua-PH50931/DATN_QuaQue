(function () {
    // Danh sách các mục sidebar để tìm kiếm
    var sidebarItems = [
        { name: 'Dashboard', url: '/admin' },
        { name: 'Product', url: '/admin/products' },
        { name: 'Products', url: '/admin/products' },
        { name: 'Add New Product', url: '/admin/products/create' },
        { name: 'Category', url: '/admin/categories' },
        { name: 'Category List', url: '/admin/categories' },
        { name: 'Add New Category', url: '/admin/categories/create' },
        { name: 'Attributes', url: '/admin/attributes' },
        { name: 'Add Attributes', url: '/admin/attributes/create' },
        { name: 'Users', url: '/admin/users' },
        { name: 'All Users', url: '/admin/users' },
        { name: 'Add New User', url: '/admin/users/create' },
        { name: 'Roles', url: '/admin/roles' },
        { name: 'All Roles', url: '/admin/roles' },
        { name: 'Create Role', url: '/admin/roles/create' },
        { name: 'Media', url: '/admin/media' },
        { name: 'Orders', url: '/admin/orders' },
        { name: 'Order List', url: '/admin/orders' },
        { name: 'Order Detail', url: '/admin/orders/detail' },
        { name: 'Order Tracking', url: '/admin/orders/tracking' },
        { name: 'Coupons', url: '/admin/coupons' },
        { name: 'Coupon List', url: '/admin/coupons' },
        { name: 'Create Coupon', url: '/admin/coupons/create' },
        { name: 'Tax', url: '/admin/taxes' },
        { name: 'Product Review', url: '/admin/product-review' },
        { name: 'Support Ticket', url: '/admin/support-ticket' },
        { name: 'Settings', url: '/admin/profile-setting' },
        { name: 'Profile Setting', url: '/admin/profile-setting' },
        { name: 'Reports', url: '/admin/reports' },
        { name: 'List Page', url: '/admin/list-page' }
    ];

    // Khởi tạo biến
    var searchInput = $('#search-input');
    var typeaheadMenu = $('#typeahead-menu');
    var searchForm = $('#search-form');
    var pageWrapper = $('.page-wrapper');
    var pageBodyWrapper = $('.page-body-wrapper');

    // Ngăn chặn submit mặc định
    searchForm.on('submit', function(e) {
        e.preventDefault();
        console.log('Form submit prevented');
    });

    // Vô hiệu hóa sự kiện gốc của theme
    $(document).ready(function() {
        $('.header-search, .form-inline.search-full, .Typeahead-input').off('click submit keypress keydown keyup input');
        $('.search-full input').off('keyup input');
        $('.search-full input').on('keyup input', function(e) {
            e.stopImmediatePropagation();
            console.log('Blocked original keyup/input event');
        });
        console.log('Initialized event overrides');
    });

    $(window).on('load', function() {
        $('.search-full input').off('keyup input');
        console.log('Re-ensured original events are removed');
    });

    // Khởi tạo Typeahead
    var typeahead = new Bloodhound({
        datumTokenizer: Bloodhound.tokenizers.obj.whitespace('name'),
        queryTokenizer: Bloodhound.tokenizers.whitespace,
        local: sidebarItems
    });

    // Cấu hình Typeahead
    searchInput.typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'sidebar-items',
        display: 'name',
        source: typeahead,
        templates: {
            suggestion: function(data) {
                return '<div class="suggestion-item">' + data.name + '</div>';
            },
            empty: '<div class="suggestion-item">Không tìm thấy kết quả</div>'
        }
    });

    // Hàm làm nổi bật mục trong sidebar
    function highlightSidebarItems(query) {
        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
        $('.sidebar-submenu').hide();

        if (query) {
            query = query.toLowerCase().trim();
            console.log('Highlighting items for query:', query);
            $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').each(function() {
                var text = $(this).text().toLowerCase();
                if (text.includes(query)) {
                    $(this).addClass('active');
                    console.log('Highlighted:', $(this).text());
                    var parentSubmenu = $(this).closest('.sidebar-submenu');
                    if (parentSubmenu.length) {
                        parentSubmenu.show();
                        parentSubmenu.prev('.sidebar-title').find('span').addClass('active');
                        console.log('Opened parent menu for:', parentSubmenu.prev('.sidebar-title').find('span').text());
                    }
                }
            });
        }
    }

    // Xử lý khi nhập từ khóa
    searchInput.on('input', function(e) {
        var query = $(this).val();
        highlightSidebarItems(query);
        console.log('Input event triggered with query:', query);
    });

    // Xử lý khi chọn gợi ý
    searchInput.on('typeahead:select', function(ev, suggestion) {
        try {
            console.log('Chuyển hướng đến:', suggestion.url);
            $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
            $('.sidebar-links .sidebar-submenu a[href="' + suggestion.url + '"]').addClass('active');
            $('.sidebar-links .sidebar-title span:contains("' + suggestion.name + '")').addClass('active');
            window.location.href = suggestion.url;
        } catch (error) {
            console.error('Lỗi chuyển hướng:', error);
        }
    });

    // Xử lý khi nhấn Enter
    searchInput.on('keypress', function(e) {
        if (e.which === 13) {
            e.preventDefault();
            var query = $(this).val().toLowerCase().trim();
            if (query) {
                console.log('Tìm kiếm:', query);
                var matchedItem = sidebarItems.find(function(item) {
                    return item.name.toLowerCase() === query;
                });
                if (matchedItem) {
                    try {
                        console.log('Chuyển hướng đến:', matchedItem.url);
                        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
                        $('.sidebar-links .sidebar-submenu a[href="' + matchedItem.url + '"]').addClass('active');
                        $('.sidebar-links .sidebar-title span:contains("' + matchedItem.name + '")').addClass('active');
                        window.location.href = matchedItem.url;
                    } catch (error) {
                        console.error('Lỗi chuyển hướng:', error);
                    }
                } else {
                    typeaheadMenu.html('<div class="suggestion-item">Không tìm thấy kết quả</div>');
                    console.log('Không tìm thấy kết quả cho:', query);
                }
            }
        }
    });

    // Xử lý nút xóa tìm kiếm
    $('.close-search').on('click', function() {
        searchInput.typeahead('val', '');
        typeaheadMenu.empty();
        $('.sidebar-links .sidebar-title span, .sidebar-links .sidebar-submenu a').removeClass('active');
        $('.sidebar-submenu').hide();
        console.log('Đã xóa nội dung tìm kiếm');
        $('body').removeClass('offcanvas');
        pageWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        pageBodyWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
    });

    // Đảm bảo nội dung trang không bị ẩn
    $(document).ready(function() {
        pageWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        pageBodyWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        $('body').removeClass('offcanvas');
        $('.search-full').removeClass('open');
        $('.sidebar-submenu').hide();
        console.log('Đã khôi phục hiển thị page-wrapper và page-body-wrapper');
    });

    // Theo dõi thay đổi class trên form.search-full
    $(document).on('click keypress', '.search-full, .Typeahead-input', function() {
        if ($('.search-full').hasClass('open')) {
            console.log('Class "open" được thêm vào form.search-full');
            $('.search-full').removeClass('open');
            $('body').removeClass('offcanvas');
            pageWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
            pageBodyWrapper.css({ 'display': 'block', 'visibility': 'visible', 'opacity': '1' });
        }
    });
})(); 