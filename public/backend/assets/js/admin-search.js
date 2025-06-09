(function () {
    window.sidebarItems = window.sidebarItems || [
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
        { name: 'List Page', url: '/admin/list-page' },
        { name: 'Regions', url: '/admin/regions' },
        { name: 'Banners', url: '/admin/banners' },
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
