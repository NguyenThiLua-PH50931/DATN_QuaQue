 <header class="pb-md-4 pb-0">
     <div class="header-top">
         <div class="container-fluid-lg">
             <div class="row">
                 <div class="col-xxl-3 d-xxl-block d-none">
                     <div class="top-left-header">
                         <i class="iconly-Location icli text-white"></i>
                         <span class="text-white">Trịnh Văn Bô, Nam Từ Liêm, Hà Nội</span>
                     </div>
                 </div>

                 <div class="col-xxl-6 col-lg-9 d-lg-block d-none">
                     <div class="header-offer">
                         <div class="notification-slider">
                             <div>
                                 <div class="timer-notification">
                                     <h6>
                                         <strong class="me-1">Chào mừng đến với Quà Quê!</strong>
                                         Ưu đãi mới mỗi ngày cuối tuần – đừng bỏ lỡ!
                                         <strong class="ms-1">Mã giảm giá: QUAQUE2024</strong>
                                         <h6>
                                 </div>
                             </div>

                             <div>
                                 <div class="timer-notification">
                                     <h6>
                                         Món quà bạn yêu thích đang được giảm giá!
                                         <a href="shop-left-sidebar.html" class="text-white">Mua ngay!</a>
                                         <h6>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

                 <div class="col-lg-3">
                 </div>
             </div>
         </div>
     </div>

     <div class="top-nav top-header sticky-header">
         <div class="container-fluid-lg">
             <div class="row">
                 <div class="col-12">
                     <div class="navbar-top">
                         <button class="navbar-toggler d-xl-none d-inline navbar-menu-button" type="button"
                             data-bs-toggle="offcanvas" data-bs-target="#primaryMenu">
                             <span class="navbar-toggler-icon">
                                 <i class="fa-solid fa-bars"></i>
                             </span>
                         </button>
                         <a href="{{ url('/') }}" class="web-logo nav-logo">
                             <img src="{{ asset('/storage/banners/logo/logo.png') }}" class="img-fluid blur-up lazyload"
                                 alt="Logo Quà Quê" style="width: 150px; height: auto;">
                         </a>
                         {{-- TÌM KIẾM SẢN PHẨM --}}
                         <div class="middle-box">
                             <div class="location-box">
                                 <button class="btn location-button" data-bs-toggle="modal"
                                     data-bs-target="#locationModal">
                                     <span class="location-arrow">
                                         <i data-feather="map-pin"></i>
                                     </span>
                                     <span class="locat-name">Vị trí</span>
                                     <i class="fa-solid fa-angle-down"></i>
                                 </button>
                             </div>
                             <div class="search-box">
                                 <div class="input-group">
                                     <input type="search" class="form-control" id="searchInput"
                                         placeholder="Tìm kiếm..." aria-label="Recipient's username"
                                         aria-describedby="button-addon2">
                                     <button class="btn" type="button" id="button-addon2">
                                         <i data-feather="search"></i>
                                     </button>
                                 </div>
                                 <div id="searchResults" class="search-results list-group"
                                     style="
                                                width: inherit;
                                                max-width: 100%;
                                                z-index: 1000;
                                                display: none;
                                                border: 1px solid #ccc;
                                                background: #fff;
                                                max-height: 200px;
                                                overflow-y: auto;">
                                 </div>
                             </div>

                             <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                             <script>
                                 $(document).ready(function() {
                                     $('#button-addon2').on('click', function() {
                                         performSearch();
                                     });

                                     $('#searchInput').on('keypress', function(e) {
                                         if (e.which == 13) { // Enter key
                                             performSearch();
                                         }
                                     });

                                     $('#searchInput').on('input', function() {
                                         const query = $(this).val().trim();
                                         if (query.length > 2) {
                                             performSearch();
                                         } else {
                                             $('#searchResults').hide().empty();
                                         }
                                     });

                                     function performSearch() {
                                         const query = $('#searchInput').val().trim();
                                         if (query.length > 0) {
                                             $.ajax({
                                                 url: '/admin/products/search',
                                                 type: 'GET',
                                                 data: {
                                                     q: query
                                                 },
                                                 success: function(response) {
                                                     $('#searchResults').empty();
                                                     if (response.length > 0) {
                                                         // Giới hạn hiển thị 3 sản phẩm
                                                         response.slice(0, 3).forEach(product => {
                                                             $('#searchResults').append(
                                                                 `<a href="/client/san-pham/${product.slug}" class="list-group-item list-group-item-action d-flex align-items-center">
                                            <img src="${product.image ? '/storage/' + product.image : '/images/default.jpg'}" alt="${product.name}" style="width: 50px; height: 50px; margin-right: 10px; object-fit: cover;"> ${product.name}
                                        </a>`
                                                             );
                                                         });
                                                         // Thêm liên kết "Xem thêm" nếu có hơn 3 sản phẩm
                                                         if (response.length > 3) {
                                                             $('#searchResults').append(
                                                                 `<a href="/client/san-pham?search=${encodeURIComponent(query)}" class="list-group-item list-group-item-action text-center text-primary">
                                            Xem thêm các sản phẩm liên quan
                                        </a>`
                                                             );
                                                         }
                                                         $('#searchResults').show();
                                                     } else {
                                                         $('#searchResults').append(
                                                             '<div class="list-group-item">Không tìm thấy sản phẩm.</div>'
                                                         ).show();
                                                     }
                                                 },
                                                 error: function(xhr) {
                                                     $('#searchResults').append(
                                                         '<div class="list-group-item">Có lỗi xảy ra: ' + xhr.status +
                                                         '</div>'
                                                     ).show();
                                                 }
                                             });
                                         } else {
                                             $('#searchResults').hide().empty();
                                         }
                                     }

                                     // Ẩn kết quả khi click ra ngoài
                                     $(document).on('click', function(e) {
                                         if (!$(e.target).closest('.search-box').length) {
                                             $('#searchResults').hide();
                                         }
                                     });
                                 });
                             </script>
                         </div>
                         {{-- END TÌM KIẾM SẢN PHẨM --}}

                         <div class="rightside-box">
                             <div class="search-full">
                                 <div class="input-group">
                                     <span class="input-group-text">
                                         <i data-feather="search" class="font-light"></i>
                                     </span>
                                     <input type="text" class="form-control search-type" placeholder="Search here..">
                                     <span class="input-group-text close-search">
                                         <i data-feather="x" class="font-light"></i>
                                     </span>
                                 </div>
                             </div>
                             <ul class="right-side-menu">
                                 <li class="right-side">
                                     <div class="delivery-login-box">
                                         <div class="delivery-icon">
                                             <div class="search-box">
                                                 <i data-feather="search"></i>
                                             </div>
                                         </div>
                                     </div>
                                 </li>
                                 <li class="right-side">
                                     <a href="{{ route('client.support-ticket.index') }}" class="delivery-login-box">
                                         <div class="delivery-icon">
                                             <i data-feather="phone-call"></i>
                                         </div>
                                         <div class="delivery-detail">
                                             <h6>Vận chuyển 24/7</h6>
                                             <h5>+84 987612345</h5>
                                         </div>
                                     </a>
                                 </li>
                                 <li class="right-side">
                                     <a href="{{ route('client.wishlist.index') }}"
                                         class="btn p-0 position-relative header-wishlist">
                                         <i data-feather="heart"></i>
                                     </a>
                                 </li>
                                 <li class="right-side">
                                     <div class="onhover-dropdown header-badge">
                                         <button type="button" class="btn p-0 position-relative header-wishlist">
                                             <i data-feather="shopping-cart"></i>
                                             <span class="position-absolute top-0 start-100 translate-middle badge">2
                                                 <span class="visually-hidden">Tin nhắn chưa đọc</span>
                                             </span>
                                         </button>

                                         <div class="onhover-div">
                                             <ul class="cart-list">
                                                 <li class="product-box-contain">
                                                     <div class="drop-cart">
                                                         <a href="product-left-thumbnail.html" class="drop-image">
                                                             <img src="../frontend/assets/images/vegetable/product/1.png"
                                                                 class="blur-up lazyload" alt="">
                                                         </a>

                                                         <div class="drop-contain">
                                                             <a href="product-left-thumbnail.html">
                                                                 <h5>Fantasy Crunchy Choco Chip Cookies</h5>
                                                             </a>
                                                             <h6><span>1 x</span> $80.58</h6>
                                                             <button class="close-button close_button">
                                                                 <i class="fa-solid fa-xmark"></i>
                                                             </button>
                                                         </div>
                                                     </div>
                                                 </li>

                                                 <li class="product-box-contain">
                                                     <div class="drop-cart">
                                                         <a href="product-left-thumbnail.html" class="drop-image">
                                                             <img src="../frontend/assets/images/vegetable/product/2.png"
                                                                 class="blur-up lazyload" alt="">
                                                         </a>

                                                         <div class="drop-contain">
                                                             <a href="product-left-thumbnail.html">
                                                                 <h5>Peanut Butter Bite Premium Butter Cookies 600 g
                                                                 </h5>
                                                             </a>
                                                             <h6><span>1 x</span> $25.68</h6>
                                                             <button class="close-button close_button">
                                                                 <i class="fa-solid fa-xmark"></i>
                                                             </button>
                                                         </div>
                                                     </div>
                                                 </li>
                                             </ul>

                                             <div class="price-box">
                                                 <h5>Total :</h5>
                                                 <h4 class="theme-color fw-bold">$106.58</h4>
                                             </div>

                                             <div class="button-group">
                                                 <a href="{{ url('/cart') }}" class="btn btn-sm cart-button">View
                                                     Cart</a>
                                                 <a href="{{ url('/checkout') }}"
                                                     class="btn btn-sm cart-button theme-bg-color
                                                        text-white">Checkout</a>
                                             </div>
                                         </div>
                                     </div>
                                 </li>
                                 <li class="right-side onhover-dropdown">
                                     <div class="delivery-login-box">
                                         @if (Auth::check())
                                             <div class="delivery-icon">
                                                 <i data-feather="user"></i>
                                                 <strong>Xin chào, {{ Auth::user()->name }}</strong>
                                             </div>
                                         @else
                                             <div class="delivery-icon">
                                                 <i data-feather="user"></i>
                                                 <span>Tài khoản</span>
                                             </div>
                                             <div class="delivery-detail">
                                                 <h6>Chào mừng!</h6>
                                                 <h5>Vui lòng đăng nhập</h5>
                                             </div>
                                         @endif
                                     </div>

                                     <div class="onhover-div onhover-div-login">
                                         <ul class="user-box-name">
                                             @if (Auth::check())
                                                 <li class="product-box-contain">
                                                     <a href="{{ route('logout') }}"
                                                         onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                         Đăng xuất
                                                     </a>
                                                 </li>
                                                 <li class="product-box-contain">
                                                     <a href="{{ route('index') }}">
                                                         Chỉnh sửa hồ sơ
                                                     </a>
                                                 </li>
                                                 @if (Auth::user()->role === 'admin')
                                                     <li class="product-box-contain">
                                                         <a href="{{ route('admin.dashboard') }}">Quay lại
                                                             Admin</a>
                                                     </li>
                                                 @endif

                                                 <form id="logout-form" action="{{ route('logout') }}"
                                                     method="POST" style="display: none;">
                                                     @csrf
                                                 </form>
                                             @else
                                                 <li class="product-box-contain">
                                                     <a href="{{ route('login') }}">Đăng nhập</a>
                                                 </li>
                                                 <li class="product-box-contain">
                                                     <a href="{{ route('register') }}">Đăng ký</a>
                                                 </li>
                                                 <li class="product-box-contain">
                                                     <a href="{{ route('forgot') }}">Quên mật khẩu</a>
                                                 </li>
                                             @endif
                                         </ul>
                                     </div>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <div class="container-fluid-lg">
         <div class="row">
             <div class="col-12">
                 <div class="header-nav">
                     <div class="header-nav-left">
                         {{-- <button class="dropdown-category">
                                <i data-feather="align-left"></i>
                                <span>Chọn Quà</span>
                            </button> --}}

                         <div class="category-dropdown">
                             <div class="category-title">
                                 <h5>Categories</h5>
                                 <button type="button" class="btn p-0 close-button text-content">
                                     <i class="fa-solid fa-xmark"></i>
                                 </button>
                             </div>

                             <ul class="category-list">
                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/vegetable.svg" alt="">
                                         <h6>Vegetables & Fruit</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Organic Vegetables</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Potato & Tomato</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cucumber & Capsicum</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Leafy Vegetables</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Root Vegetables</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Beans & Okra</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cabbage & Cauliflower</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Gourd & Drumstick</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Specialty</a>
                                                 </li>
                                             </ul>
                                         </div>

                                         <div class="list-2">
                                             <div class="category-title-box">
                                                 <h5>Fresh Fruit</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Banana & Papaya</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Kiwi, Citrus Fruit</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Apples & Pomegranate</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Seasonal Fruits</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Mangoes</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Fruit Baskets</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/cup.svg" alt="">
                                         <h6>Beverages</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box w-100">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Energy & Soft Drinks</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Soda & Cocktail Mix</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Soda & Cocktail Mix</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Sports & Energy Drinks</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Non Alcoholic Drinks</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Packaged Water</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Spring Water</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Flavoured Water</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/meats.svg" alt="">
                                         <h6>Meats & Seafood</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Meat</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Fresh Meat</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Frozen Meat</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Marinated Meat</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Fresh & Frozen Meat</a>
                                                 </li>
                                             </ul>
                                         </div>

                                         <div class="list-2">
                                             <div class="category-title-box">
                                                 <h5>Seafood</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Fresh Water Fish</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Dry Fish</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Frozen Fish & Seafood</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Marine Water Fish</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Canned Seafood</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Prawans & Shrimps</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Other Seafood</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/breakfast.svg" alt="">
                                         <h6>Breakfast & Dairy</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Breakfast Cereals</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Oats & Porridge</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Kids Cereal</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Muesli</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Flakes</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Granola & Cereal Bars</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Instant Noodles</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Pasta & Macaroni</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Frozen Non-Veg Snacks</a>
                                                 </li>
                                             </ul>
                                         </div>

                                         <div class="list-2">
                                             <div class="category-title-box">
                                                 <h5>Dairy</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Milk</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Curd</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Paneer, Tofu & Cream</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Butter & Margarine</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Condensed, Powdered Milk</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Buttermilk & Lassi</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Yogurt & Shrikhand</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Flavoured, Soya Milk</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/frozen.svg" alt="">
                                         <h6>Frozen Foods</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box w-100">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Noodle, Pasta</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Instant Noodles</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Hakka Noodles</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cup Noodles</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Vermicelli</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Instant Pasta</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/biscuit.svg" alt="">
                                         <h6>Biscuits & Snacks</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Biscuits & Cookies</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Salted Biscuits</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Marie, Health, Digestive</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cream Biscuits & Wafers</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Glucose & Milk Biscuits</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cookies</a>
                                                 </li>
                                             </ul>
                                         </div>

                                         <div class="list-2">
                                             <div class="category-title-box">
                                                 <h5>Bakery Snacks</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Bread Sticks & Lavash</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Cheese & Garlic Bread</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Puffs, Patties, Sandwiches</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Breadcrumbs & Croutons</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>

                                 <li class="onhover-category-list">
                                     <a href="javascript:void(0)" class="category-name">
                                         <img src="../frontend/assets/svg/1/grocery.svg" alt="">
                                         <h6>Grocery & Staples</h6>
                                         <i class="fa-solid fa-angle-right"></i>
                                     </a>

                                     <div class="onhover-category-box">
                                         <div class="list-1">
                                             <div class="category-title-box">
                                                 <h5>Grocery</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Lemon, Ginger & Garlic</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Indian & Exotic Herbs</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Vegetables</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Fruits</a>
                                                 </li>
                                             </ul>
                                         </div>

                                         <div class="list-2">
                                             <div class="category-title-box">
                                                 <h5>Organic Staples</h5>
                                             </div>
                                             <ul>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Dry Fruits</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Dals & Pulses</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Millet & Flours</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Sugar, Jaggery</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Masalas & Spices</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Rice, Other Rice</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Flours</a>
                                                 </li>
                                                 <li>
                                                     <a href="javascript:void(0)">Organic Edible Oil, Ghee</a>
                                                 </li>
                                             </ul>
                                         </div>
                                     </div>
                                 </li>
                             </ul>
                         </div>
                     </div>

                     <div class="header-nav-middle">
                         <div class="main-nav navbar navbar-expand-xl navbar-light navbar-sticky">
                             <div class="offcanvas offcanvas-collapse order-xl-2" id="primaryMenu">
                                 <div class="offcanvas-header navbar-shadow">
                                     <h5>Menu</h5>
                                     <button class="btn-close lead" type="button" data-bs-dismiss="offcanvas"
                                         aria-label="Close"></button>
                                 </div>
                                 <div class="offcanvas-body">
                                     <ul class="navbar-nav">
                                         <li class="nav-item dropdown">
                                             <a class="nav-link dropdown-toggle" href="{{ route('client.home') }}"
                                                 data-bs-toggle="">Trang chủ</a>
                                         </li>

                                         {{-- <li class="nav-item dropdown">
                                                <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                    data-bs-toggle="dropdown">Cửa hàng</a>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item" href="shop-category-slider.html">Shop
                                                            Category Slider</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="shop-category.html">Shop
                                                            Category Sidebar</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ url('/products/category') }}">Shop Banner</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ url('/products/category') }}">Shop Left
                                                            Sidebar</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="shop-list.html">Shop List</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="shop-right-sidebar.html">Shop
                                                            Right Sidebar</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item"
                                                            href="{{ url('/products/category') }}">Shop Top
                                                            Filter</a>
                                                    </li>
                                                </ul>
                                            </li> --}}

                                         <li class="nav-item dropdown">
                                             <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                 data-bs-toggle="dropdown">Sản phẩm</a>
                                             <ul class="dropdown-menu">
                                                 <li>
                                                     <a class="dropdown-item"
                                                         href="{{ route('client.product.index') }}">Tất cả sản
                                                         phẩm</a>
                                                 </li>
                                                 <li class="sub-dropdown-hover">
                                                     <a href="javascript:void(0)" class="dropdown-item">Product
                                                         Thumbnail</a>
                                                     <ul class="sub-menu">
                                                         <li>
                                                             <a href="product-left-thumbnail.html">Left
                                                                 Thumbnail</a>
                                                         </li>

                                                         <li>
                                                             <a href="product-right-thumbnail.html">Right
                                                                 Thumbnail</a>
                                                         </li>

                                                         <li>
                                                             <a href="product-bottom-thumbnail.html">Bottom
                                                                 Thumbnail</a>
                                                         </li>
                                                     </ul>
                                                 </li>
                                                 <li>
                                                     <a href="product-bundle.html" class="dropdown-item">Product
                                                         Bundle</a>
                                                 </li>
                                                 <li>
                                                     <a href="product-slider.html" class="dropdown-item">Product
                                                         Slider</a>
                                                 </li>
                                                 <li>
                                                     <a href="product-sticky.html" class="dropdown-item">Product
                                                         Sticky</a>
                                                 </li>
                                             </ul>
                                         </li>

                                         {{-- <li class="nav-item dropdown dropdown-mega">
                                                <a class="nav-link dropdown-toggle ps-xl-2 ps-0"
                                                    href="javascript:void(0)" data-bs-toggle="dropdown">Menu mở
                                                    rộng</a>

                                                <div class="dropdown-menu dropdown-menu-2">
                                                    <div class="row">
                                                        <div class="dropdown-column col-xl-3">
                                                            <h5 class="dropdown-header">Daily Vegetables</h5>
                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Beans
                                                                & Brinjals</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Broccoli &
                                                                Cauliflower</a>

                                                            <a href="shop-left-sidebar.html"
                                                                class="dropdown-item">Chilies, Garlic</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Vegetables & Salads</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Gourd, Cucumber</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Herbs
                                                                & Sprouts</a>

                                                            <a href="demo-personal-portfolio.html"
                                                                class="dropdown-item">Lettuce & Leafy</a>
                                                        </div>

                                                        <div class="dropdown-column col-xl-3">
                                                            <h5 class="dropdown-header">Baby Tender</h5>
                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Beans
                                                                & Brinjals</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Broccoli &
                                                                Cauliflower</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Chilies, Garlic</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Vegetables & Salads</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Gourd, Cucumber</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Potatoes & Tomatoes</a>

                                                            <a href="shop-left-sidebar.html"
                                                                class="dropdown-item">Peas
                                                                & Corn</a>
                                                        </div>

                                                        <div class="dropdown-column col-xl-3">
                                                            <h5 class="dropdown-header">Exotic Vegetables</h5>
                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Asparagus &
                                                                Artichokes</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Avocados & Peppers</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Broccoli & Zucchini</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Celery, Fennel &
                                                                Leeks</a>

                                                            <a class="dropdown-item"
                                                                href="shop-left-sidebar.html">Chilies & Lime</a>
                                                        </div>

                                                        <div class="dropdown-column dropdown-column-img col-3"></div>
                                                    </div>
                                                </div>
                                            </li> --}}

                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle" href="{{ route('blog') }}">Tin
                                                 tức</a>
                                         </li>

                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle" href="{{ route('blog') }}">Giới
                                                 thiệu
                                             </a>
                                         </li>
                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle"
                                                 href="{{ route('client.lienhe') }}">Liên
                                                 hệ
                                             </a>
                                         </li>
                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle" href="{{ route('blog') }}">Giỏ
                                                 hàng
                                             </a>
                                         </li>
                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle" href="{{ route('blog') }}">Thanh
                                                 toán
                                             </a>
                                         </li>

                                         {{-- <li class="nav-item dropdown new-nav-item">
                                                <label class="new-dropdown">Mới</label>
                                                <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                    data-bs-toggle="dropdown">Trang</a>
                                                <ul class="dropdown-menu">
                                                    <li class="sub-dropdown-hover">
                                                        <a class="dropdown-item" href="javascript:void(0)">Email
                                                            Template <span class="new-text"><i
                                                                    class="fa-solid fa-bolt-lightning"></i></span></a>
                                                        <ul class="sub-menu">
                                                            <li>
                                                                <a
                                                                    href="../email-templete/abandonment-email.html">Abandonment</a>
                                                            </li>
                                                            <li>
                                                                <a href="../email-templete/offer-template.html">Offer
                                                                    Template</a>
                                                            </li>
                                                            <li>
                                                                <a href="../email-templete/order-success.html">Order
                                                                    Success</a>
                                                            </li>
                                                            <li>
                                                                <a href="../email-templete/reset-password.html">Reset
                                                                    Password</a>
                                                            </li>
                                                            <li>
                                                                <a href="../email-templete/welcome.html">Welcome
                                                                    template</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li class="sub-dropdown-hover">
                                                        <a class="dropdown-item" href="javascript:void(0)">Invoice
                                                            Template <span class="new-text"><i
                                                                    class="fa-solid fa-bolt-lightning"></i></span></a>
                                                        <ul class="sub-menu">
                                                            <li>
                                                                <a href="../invoice/invoice-1.html">Invoice 1</a>
                                                            </li>

                                                            <li>
                                                                <a href="../invoice/invoice-2.html">Invoice 2</a>
                                                            </li>

                                                            <li>
                                                                <a href="../invoice/invoice-3.html">Invoice 3</a>
                                                            </li>
                                                        </ul>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="404.html">404</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="about-us.html">About Us</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="cart.html">Cart</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="contact-us.html">Contact</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="checkout.html">Checkout</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="coming-soon.html">Coming
                                                            Soon</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="compare.html">Compare</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="faq.html">Faq</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="order-success.html">Order
                                                            Success</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="order-tracking.html">Order
                                                            Tracking</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="otp.html">OTP</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="search.html">Search</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="user-dashboard.html">User
                                                            Dashboard</a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="wishlist.html">Wishlist</a>
                                                    </li>
                                                </ul>
                                            </li> --}}

                                         {{-- <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                        data-bs-toggle="dropdown">Người bán</a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/seller/become-seller') }}">Trở thành người bán</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/seller/seller-dashboard') }}">Trang người bán</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="seller-detail.html">Seller
                                                                Detail</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="seller-detail-2.html">Seller
                                                                Detail 2</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="seller-grid.html">Seller Grid</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="seller-grid-2.html">Seller Grid
                                                                2</a>
                                                        </li>
                                                    </ul>
                                                </li> --}}
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>

                     <div class="header-nav-right">
                         <button class="btn deal-button" data-bs-toggle="modal" data-bs-target="#deal-box">
                             <i data-feather="zap"></i>
                             <span>Ưu đãi hôm nay</span>
                         </button>
                     </div>
                 </div>
             </div>
         </div>
     </div>
 </header>
