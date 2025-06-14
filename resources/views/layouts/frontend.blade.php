<!DOCTYPE html>
<html lang="en">
<!-- Mirrored from themes.pixelstrap.com/fastkart/front-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Nov 2024 13:10:56 GMT -->
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Fastkart">
    <meta name="keywords" content="Fastkart">
    <meta name="author" content="Fastkart">

    <!-- Favicon -->
    <link rel="icon" href="{{ asset('frontend/assets/images/favicon/1.png') }}" type="image/x-icon">

    <!-- Title -->
    <title>@yield('title')</title>

    <!-- Base URL để tránh lỗi 404 khi có prefix route như /client/... -->
    <base href="{{ url('/') }}/" />

    <!-- Main style -->
    <link href="{{ asset('frontend/assets/css/style.css') }}" rel="stylesheet">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Russo+One&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Kaushan+Script&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <!-- Vendor CSS -->
    <link id="rtl-link" rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/aa.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/slick/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/slick/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bulk-style.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/animate.css') }}">

    <!-- Template Style -->
    <link id="color-link" rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
</head>


<body class="bg-effect">
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        const savedThemeColor = localStorage.getItem('theme-color');
        if (savedThemeColor) {
            document.body.style.setProperty('--theme-color', savedThemeColor);
            document.body.style.setProperty('--theme-color-rgb', savedThemeColor);
            const colorPick = document.getElementById('colorPick');
            if (colorPick) colorPick.value = savedThemeColor;
        }

        const savedThemeMode = localStorage.getItem('theme-mode');
        if (savedThemeMode) {
            const colorLink = document.getElementById('color-link');
            if (savedThemeMode === 'dark') {
                document.body.classList.add('dark');
                document.body.classList.remove('light');
                if (colorLink) colorLink.setAttribute('href', "{{ asset('frontend/assets/css/dark.css') }}");
            } else {
                document.body.classList.add('light');
                document.body.classList.remove('dark');
                if (colorLink) colorLink.setAttribute('href', "{{ asset('frontend/assets/css/style.css') }}");
            }
        }

        const savedThemeDirection = localStorage.getItem('theme-direction');
        if (savedThemeDirection) {
            const rtlLink = document.getElementById('rtl-link');
            if (savedThemeDirection === 'rtl') {
                document.documentElement.setAttribute('dir', 'rtl');
                document.body.classList.add('rtl');
                document.body.classList.remove('ltr');
                if (rtlLink) rtlLink.setAttribute('href', "{{ asset('frontend/assets/css/vendors/bootstrap.rtl.css') }}");
            } else {
                document.documentElement.setAttribute('dir', '');
                document.body.classList.add('ltr');
                document.body.classList.remove('rtl');
                if (rtlLink) rtlLink.setAttribute('href', "{{ asset('frontend/assets/css/vendors/bootstrap.css') }}");
            }
        }
    });
</script>

    <!-- Loader Start -->
    <!-- <div class="fullpage-loader">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div> -->
    <!-- Loader End -->

    <!-- Header Start -->
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
                            <ul class="about-list right-nav-about">
                                <li class="right-nav-list">
                                    <div class="dropdown theme-form-select">
                                        <button class="btn dropdown-toggle" type="button" id="select-language"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <img src="../frontend/assets/images/country/united-states.png"
                                                class="img-fluid blur-up lazyload" alt="">
                                            <span>Tiếng Anh</span>

                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="select-language">
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" id="english">
                                                    <img src="../frontend/assets/images/country/united-kingdom.png"
                                                        class="img-fluid blur-up lazyload" alt="">
                                                    <span>Tiếng Anh</span>

                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" id="france">
                                                    <img src="../frontend/assets/images/country/germany.png"
                                                        class="img-fluid blur-up lazyload" alt="">
                                                    <span>Tiếng Đức</span>

                                                </a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="javascript:void(0)" id="chinese">
                                                    <img src="../frontend/assets/images/country/turkish.png"
                                                        class="img-fluid blur-up lazyload" alt="">
                                                    <span>Tiếng Thổ Nhĩ Kì</span>

                                                </a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                                <li class="right-nav-list">
                                    <div class="dropdown theme-form-select">
                                        <button class="btn dropdown-toggle" type="button" id="select-dollar"
                                            data-bs-toggle="dropdown" aria-expanded="false">
                                            <span>VNĐ</span>

                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end sm-dropdown-menu"
                                            aria-labelledby="select-dollar">
                                            <li>
                                                <a class="dropdown-item" id="aud" href="javascript:void(0)">AUD</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" id="eur" href="javascript:void(0)">EUR</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" id="cny" href="javascript:void(0)">USD</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
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
                                    <img src="../frontend/assets/images/logo/1.png" class="img-fluid blur-up lazyload" alt="">
                                </a>

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
                                            <input type="search" class="form-control" placeholder="Tìm kiếm..."
                                                aria-label="Recipient's username" aria-describedby="button-addon2">
                                            <button class="btn" type="button" id="button-addon2">
                                                <i data-feather="search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>

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
                                            <a href="{{ url('/wishlist') }}" class="btn p-0 position-relative header-wishlist">
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
                                                        <a href="{{ url('/cart') }}" class="btn btn-sm cart-button">View Cart</a>
                                                        <a href="{{ url('/checkout') }}" class="btn btn-sm cart-button theme-bg-color
                                                        text-white">Checkout</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </li>
                                        <li class="right-side onhover-dropdown">
                                            <div class="delivery-login-box">
                                                <div class="delivery-icon">
                                                    <i data-feather="user"></i>
                                                </div>
                                                <div class="delivery-detail">
                                                    <h6>Hello,</h6>
                                                    <h5>My Account</h5>
                                                </div>
                                            </div>

                                            <div class="onhover-div onhover-div-login">
                                                <ul class="user-box-name">
                                                    <li class="product-box-contain">
                                                        <i></i>
                                                        <a href="{{ route('login') }}">Log In</a>
                                                    </li>

                                                    <li class="product-box-contain">
                                                        <a href="{{ route('register') }}">Register</a>

                                                    </li>

                                                    <li class="product-box-contain">
                                                        <a href="forgot.html">Forgot Password</a>
                                                    </li>
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
                                <button class="dropdown-category">
                                    <i data-feather="align-left"></i>
                                    <span>Chọn Quà</span>
                                </button>

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
                                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)"

                                                        data-bs-toggle="dropdown">Trang chủ</a>

                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="index.html">Kartshop</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-2.html">Sweetshop</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-3.html">Organic</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-4.html">Supershop</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-5.html">Classic shop</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-6.html">Furniture</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-7.html">Search Oriented</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-8.html">Category Focus</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="index-9.html">Fashion</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li class="nav-item dropdown">
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
                                                            <a class="dropdown-item" href="{{ url('/products/category') }}">Shop Banner</a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="{{ url('/products/category') }}">Shop Left
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
                                                            <a class="dropdown-item" href="{{ url('/products/category') }}">Shop Top
                                                                Filter</a>
                                                        </li>
                                                    </ul>
                                                </li>

                                                <li class="nav-item dropdown">
                                                    <a class="nav-link dropdown-toggle" href="javascript:void(0)"
                                                        data-bs-toggle="dropdown">Sản phẩm</a>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="product-4-image.html">Product
                                                                4 Image</a>
                                                        </li>
                                                        <li class="sub-dropdown-hover">
                                                            <a href="javascript:void(0)" class="dropdown-item">Product
                                                                Thumbnail</a>
                                                            <ul class="sub-menu">
                                                                <li>
                                                                    <a href="product-left-thumbnail.html">Left Thumbnail</a>
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

                                                <li class="nav-item dropdown dropdown-mega">
                                                    <a class="nav-link dropdown-toggle ps-xl-2 ps-0"
                                                        href="javascript:void(0)" data-bs-toggle="dropdown">Menu mở rộng</a>

                                                    <div class="dropdown-menu dropdown-menu-2">
                                                        <div class="row">
                                                            <div class="dropdown-column col-xl-3">
                                                                <h5 class="dropdown-header">Daily Vegetables</h5>
                                                                <a class="dropdown-item" href="shop-left-sidebar.html">Beans
                                                                    & Brinjals</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Broccoli & Cauliflower</a>

                                                                <a href="shop-left-sidebar.html"
                                                                    class="dropdown-item">Chilies, Garlic</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Vegetables & Salads</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Gourd, Cucumber</a>

                                                                <a class="dropdown-item" href="shop-left-sidebar.html">Herbs
                                                                    & Sprouts</a>

                                                                <a href="demo-personal-portfolio.html"
                                                                    class="dropdown-item">Lettuce & Leafy</a>
                                                            </div>

                                                            <div class="dropdown-column col-xl-3">
                                                                <h5 class="dropdown-header">Baby Tender</h5>
                                                                <a class="dropdown-item" href="shop-left-sidebar.html">Beans
                                                                    & Brinjals</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Broccoli & Cauliflower</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Chilies, Garlic</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Vegetables & Salads</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Gourd, Cucumber</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Potatoes & Tomatoes</a>

                                                                <a href="shop-left-sidebar.html" class="dropdown-item">Peas
                                                                    & Corn</a>
                                                            </div>

                                                            <div class="dropdown-column col-xl-3">
                                                                <h5 class="dropdown-header">Exotic Vegetables</h5>
                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Asparagus & Artichokes</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Avocados & Peppers</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Broccoli & Zucchini</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Celery, Fennel & Leeks</a>

                                                                <a class="dropdown-item"
                                                                    href="shop-left-sidebar.html">Chilies & Lime</a>
                                                            </div>

                                                            <div class="dropdown-column dropdown-column-img col-3"></div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <li class="nav-item dropdown new-nav-item">
                                                    <a class="nav-link dropdown-toggle" href="{{ route('blog') }}">Tin tức</a>
                                                </li>

                                                <li class="nav-item dropdown new-nav-item">
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
                                                            <a class="dropdown-item" href="coming-soon.html">Coming Soon</a>
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
                                                </li>

                                                <li class="nav-item dropdown">
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
                                                </li>
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
    <!-- Header End -->

    <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li class="active">
                <a href="index.html">
                    <i class="iconly-Home icli"></i>
                    <span>Trang chủ</span>
                </a>
            </li>

            <li class="mobile-category">
                <a href="javascript:void(0)">
                    <i class="iconly-Category icli js-link"></i>
                    <span>Danh mục</span>
                </a>
            </li>

            <li>
                <a href="search.html" class="search-box">
                    <i class="iconly-Search icli"></i>
                    <span>Tìm kiếm</span>
                </a>
            </li>

            <li>
                <a href="wishlist.html" class="notifi-wishlist">
                    <i class="iconly-Heart icli"></i>
                    <span>Sản phẩm yêu thích</span>
                </a>
            </li>

            <li>
                <a href="cart.html">
                    <i class="iconly-Bag-2 icli fly-cate"></i>
                    <span>Giỏ hàng</span>
                </a>
            </li>
        </ul>
    </div>
    <!-- mobile fix menu end -->

    {{-- Contents --}}
    @yield('contents')

    <!-- Footer Section Start -->
    <footer class="section-t-space">
        <div class="container-fluid-lg">
            <div class="service-section">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="service-contain">
                            <!-- Sản phẩm tươi mới mỗi ngày -->
                            <div class="service-box">
                                <div class="service-image">
                                    <img src="https://themes.pixelstrap.com/fastkart/assets/svg/product.svg" class="blur-up lazyload" alt="">
                                </div>
                                <div class="service-detail">
                                    <h5>Sản phẩm tươi ngon mỗi ngày</h5>
                                </div>
                            </div>

                            <!-- Giao hàng miễn phí -->
                            <div class="service-box">
                                <div class="service-image">
                                    <img src="https://themes.pixelstrap.com/fastkart/assets/svg/delivery.svg" class="blur-up lazyload" alt="">
                                </div>
                                <div class="service-detail">
                                    <h5>Miễn phí giao hàng cho đơn hàng đầu tiên</h5>
                                </div>
                            </div>

                            <!-- Giảm giá mỗi ngày -->
                            <div class="service-box">
                                <div class="service-image">
                                    <img src="https://themes.pixelstrap.com/fastkart/assets/svg/discount.svg" class="blur-up lazyload" alt="">
                                </div>
                                <div class="service-detail">
                                    <h5>Giảm giá hấp dẫn mỗi ngày</h5>
                                </div>
                            </div>

                            <!-- Giá tốt nhất -->
                            <div class="service-box">
                                <div class="service-image">
                                    <img src="https://themes.pixelstrap.com/fastkart/assets/svg/market.svg" class="blur-up lazyload" alt="">
                                </div>
                                <div class="service-detail">
                                    <h5>Giá tốt nhất thị trường</h5>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main-footer section-b-space section-t-space">
                <div class="row g-md-4 g-3">
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="footer-logo">
                            <div class="theme-logo">
                                <a href="index.html">
                                    <img src="../frontend/assets/images/logo/1.png" class="blur-up lazyload" alt="Logo Quà Quê">
                                </a>
                            </div>

                            <div class="footer-logo-contain">
                                <p>Quà Quê là nơi bạn tìm thấy những món đặc sản đậm đà hương vị truyền thống từ mọi miền đất nước – dành tặng người thân hoặc thưởng thức cùng gia đình.</p>

                                <ul class="address">
                                    <li>
                                        <i data-feather="home"></i>
                                        <a href="javascript:void(0)">1418 Riverwood Drive, CA 96052, US</a> <!-- Có thể thay bằng địa chỉ thật -->
                                    </li>
                                    <li>
                                        <i data-feather="mail"></i>
                                        <a href="javascript:void(0)">support@quaque.vn</a> <!-- Gợi ý email thương hiệu -->
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                        <div class="footer-title">
                            <h4>Categories</h4>
                        </div>

                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Vegetables & Fruit</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Beverages</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Meats & Seafood</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Frozen Foods</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Biscuits & Snacks</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Grocery & Staples</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl col-lg-2 col-sm-3">
                        <div class="footer-title">
                            <h4>Useful Links</h4>
                        </div>

                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="index.html" class="text-content">Home</a>
                                </li>
                                <li>
                                    <a href="shop-left-sidebar.html" class="text-content">Shop</a>
                                </li>
                                <li>
                                    <a href="about-us.html" class="text-content">About Us</a>
                                </li>
                                <li>
                                    <a href="blog-list.html" class="text-content">Blog</a>
                                </li>
                                <li>
                                    <a href="contact-us.html" class="text-content">Contact Us</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-2 col-sm-3">
                        <div class="footer-title">
                            <h4>Help Center</h4>
                        </div>

                        <div class="footer-contain">
                            <ul>
                                <li>
                                    <a href="order-success.html" class="text-content">Your Order</a>
                                </li>
                                <li>
                                    <a href="user-dashboard.html" class="text-content">Your Account</a>
                                </li>
                                <li>
                                    <a href="order-tracking.html" class="text-content">Track Order</a>
                                </li>
                                <li>
                                    <a href="wishlist.html" class="text-content">Your Wishlist</a>
                                </li>
                                <li>
                                    <a href="search.html" class="text-content">Search</a>
                                </li>
                                <li>
                                    <a href="faq.html" class="text-content">FAQ</a>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="footer-title">
                            <h4>Contact Us</h4>
                        </div>

                        <div class="footer-contact">
                            <ul>
                                <li>
                                    <div class="footer-number">
                                        <i data-feather="phone"></i>
                                        <div class="contact-number">
                                            <h6 class="text-content">Hotline 24/7 :</h6>
                                            <h5>+91 888 104 2340</h5>
                                        </div>
                                    </div>
                                </li>

                                <li>
                                    <div class="footer-number">
                                        <i data-feather="mail"></i>
                                        <div class="contact-number">
                                            <h6 class="text-content">Email Address :</h6>
                                            <h5>fastkart@hotmail.com</h5>
                                        </div>
                                    </div>
                                </li>

                                <li class="social-app mb-0">
                                    <h5 class="mb-2 text-content">Download App :</h5>
                                    <ul>
                                        <li class="mb-0">
                                            <a href="https://play.google.com/store/apps" target="_blank">
                                                <img src="https://themes.pixelstrap.com/fastkart/assets/images/playstore.svg" class="blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                        <li class="mb-0">
                                            <a href="https://www.apple.com/in/app-store/" target="_blank">
                                                <img src="https://themes.pixelstrap.com/fastkart/assets/images/appstore.svg" class="blur-up lazyload"
                                                    alt="">
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="sub-footer section-small-space">
                <div class="reserve">
                    <h6 class="text-content">©2022 Fastkart All rights reserved</h6>
                </div>

                <div class="payment">
                    <img src="../frontend/assets/images/payment/1.png" class="blur-up lazyload" alt="">
                </div>

                <div class="social-link">
                    <h6 class="text-content">Stay connected :</h6>
                    <ul>
                        <li>
                            <a href="https://www.facebook.com/" target="_blank">
                                <i class="fa-brands fa-facebook-f"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://twitter.com/" target="_blank">
                                <i class="fa-brands fa-twitter"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://www.instagram.com/" target="_blank">
                                <i class="fa-brands fa-instagram"></i>
                            </a>
                        </li>
                        <li>
                            <a href="https://in.pinterest.com/" target="_blank">
                                <i class="fa-brands fa-pinterest-p"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

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
                                <img src="../frontend/assets/images/product/category/1.jpg" class="img-fluid blur-up lazyload"
                                    alt="">
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class="right-sidebar-modal">
                                <h4 class="title-name">Peanut Butter Bite Premium Butter Cookies 600 g</h4>
                                <h4 class="price">$36.99</h4>
                                <div class="product-rating">
                                    <ul class="rating">
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star" class="fill"></i>
                                        </li>
                                        <li>
                                            <i data-feather="star"></i>
                                        </li>
                                    </ul>
                                    <span class="ms-2">8 Reviews</span>
                                    <span class="ms-2 text-danger">6 sold in last 16 hours</span>
                                </div>

                                <div class="product-detail">
                                    <h4>Product Details :</h4>
                                    <p>Candy canes sugar plum tart cotton candy chupa chups sugar plum chocolate I love.
                                        Caramels marshmallow icing dessert candy canes I love soufflé I love toffee.
                                        Marshmallow pie sweet sweet roll sesame snaps tiramisu jelly bear claw. Bonbon
                                        muffin I love carrot cake sugar plum dessert bonbon.</p>
                                </div>

                                <ul class="brand-list">
                                    <li>
                                        <div class="brand-box">
                                            <h5>Brand Name:</h5>
                                            <h6>Black Forest</h6>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="brand-box">
                                            <h5>Product Code:</h5>
                                            <h6>W0690034</h6>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="brand-box">
                                            <h5>Product Type:</h5>
                                            <h6>White Cream Cake</h6>
                                        </div>
                                    </li>
                                </ul>

                                <div class="select-size">
                                    <h4>Cake Size :</h4>
                                    <select class="form-select select-form-size">
                                        <option selected>Select Size</option>
                                        <option value="1.2">1/2 KG</option>
                                        <option value="0">1 KG</option>
                                        <option value="1.5">1/5 KG</option>
                                        <option value="red">Red Roses</option>
                                        <option value="pink">With Pink Roses</option>
                                    </select>
                                </div>

                                <div class="modal-button">
                                    <button onclick="location.href = 'cart.html';"
                                        class="btn btn-md add-cart-button icon">Add
                                        To Cart</button>
                                    <button onclick="location.href = 'product-left.html';"
                                        class="btn theme-bg-color view-button icon text-white fw-bold btn-md">
                                        View More Details</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Quick View Modal Box End -->

    <!-- Location Modal Start -->
    <div class="modal location-modal fade theme-modal" id="locationModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Choose your Delivery Location</h5>
                    <p class="mt-1 text-content">Enter your address and we will specify the offer for your area.</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="location-list">
                        <div class="search-input">
                            <input type="search" class="form-control" placeholder="Search Your Area">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>

                        <div class="disabled-box">
                            <h6>Select a Location</h6>
                        </div>

                        <ul class="location-select custom-height">
                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Alabama</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Arizona</h6>
                                    <span>Min: $150</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>California</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Colorado</h6>
                                    <span>Min: $140</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Florida</h6>
                                    <span>Min: $160</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Georgia</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Kansas</h6>
                                    <span>Min: $170</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Minnesota</h6>
                                    <span>Min: $120</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>New York</h6>
                                    <span>Min: $110</span>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)">
                                    <h6>Washington</h6>
                                    <span>Min: $130</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Location Modal End -->

    <!-- Cookie Bar Box Start -->
    <div class="cookie-bar-box">
        <div class="cookie-box">
            <div class="cookie-image">
                <img src="../frontend/assets/images/cookie-bar.png" class="blur-up lazyload" alt="">
                <h2>Cookies!</h2>
            </div>

            <div class="cookie-contain">
                <h5 class="text-content">We use cookies to make your experience better</h5>
            </div>
        </div>

        <div class="button-group">
            <button class="btn privacy-button">Privacy Policy</button>
            <button class="btn ok-button">OK</button>
        </div>
    </div>
    <!-- Cookie Bar Box End -->

    <!-- Deal Box Modal Start -->
    <div class="modal fade theme-modal deal-modal" id="deal-box" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
            <div class="modal-content">
                <div class="modal-header">
                    <div>
                        <h5 class="modal-title w-100" id="deal_today">Deal Today</h5>
                        <p class="mt-1 text-content">Recommended deals for you.</p>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="deal-offer-box">
                        <ul class="deal-offer-list">
                            <li class="list-1">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../frontend/assets/images/vegetable/product/10.png" class="blur-up lazyload"
                                            alt="">
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                        <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-2">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../frontend/assets/images/vegetable/product/11.png" class="blur-up lazyload"
                                            alt="">
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                        <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-3">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../frontend/assets/images/vegetable/product/12.png" class="blur-up lazyload"
                                            alt="">
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                        <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                    </a>
                                </div>
                            </li>

                            <li class="list-1">
                                <div class="deal-offer-contain">
                                    <a href="shop-left-sidebar.html" class="deal-image">
                                        <img src="../frontend/assets/images/vegetable/product/13.png" class="blur-up lazyload"
                                            alt="">
                                    </a>

                                    <a href="shop-left-sidebar.html" class="deal-contain">
                                        <h5>Blended Instant Coffee 50 g Buy 1 Get 1 Free</h5>
                                        <h6>$52.57 <del>57.62</del> <span>500 G</span></h6>
                                    </a>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Deal Box Modal End -->

    <!-- Tap to top and theme setting button start -->
    <div class="theme-option">
        <div class="setting-box">
            <button class="btn setting-button">
                <i class="fa-solid fa-gear"></i>
            </button>

            <div class="theme-setting-2">
                <div class="theme-box">
                    <ul>
                        <li>
                            <div class="setting-name">
                                <h4>Color</h4>
                            </div>
                            <div class="theme-setting-button color-picker">
                                <form class="form-control">
                                    <label for="colorPick" class="form-label mb-0">Theme Color</label>
                                    <input type="color" class="form-control form-control-color" id="colorPick"
                                        value="#0da487" title="Choose your color">
                                </form>
                            </div>
                        </li>

                        <li>
                            <div class="setting-name">
                                <h4>Dark</h4>
                            </div>
                            <div class="theme-setting-button">
                                <button class="btn btn-2 outline" id="darkButton">Dark</button>
                                <button class="btn btn-2 unline" id="lightButton">Light</button>
                            </div>
                        </li>

                        <li>
                            <div class="setting-name">
                                <h4>RTL</h4>
                            </div>
                            <div class="theme-setting-button rtl">
                                <button class="btn btn-2 rtl-unline">LTR</button>
                                <button class="btn btn-2 rtl-outline">RTL</button>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="back-to-top">
            <a id="back-to-top" href="#">
                <i class="fas fa-chevron-up"></i>
            </a>
        </div>
    </div>
    <!-- Tap to top and theme setting button end -->

    <!-- Bg overlay Start -->
    <div class="bg-overlay"></div>
    <!-- Bg overlay End -->

   <!-- latest jquery-->
<script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>

<!-- jquery ui-->
<script src="{{ asset('frontend/assets/js/jquery-ui.min.js') }}"></script>

<!-- Bootstrap js-->
<script src="{{ asset('frontend/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/bootstrap/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/bootstrap/popper.min.js') }}"></script>

<!-- feather icon js-->
<script src="{{ asset('frontend/assets/js/feather/feather.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/feather/feather-icon.js') }}"></script>

<!-- Lazyload Js -->
<script src="{{ asset('frontend/assets/js/lazysizes.min.js') }}"></script>

<!-- Slick js-->
<script src="{{ asset('frontend/assets/js/slick/slick.js') }}"></script>
<script src="{{ asset('frontend/assets/js/slick/slick-animation.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/slick/custom_slick.js') }}"></script>

<!-- Auto Height Js -->
<script src="{{ asset('frontend/assets/js/auto-height.js') }}"></script>

<!-- Timer Js -->
<script src="{{ asset('frontend/assets/js/timer1.js') }}"></script>

<!-- Fly Cart Js -->
<script src="{{ asset('frontend/assets/js/fly-cart.js') }}"></script>

<!-- Quantity js -->
<script src="{{ asset('frontend/assets/js/quantity-2.js') }}"></script>

<!-- WOW js -->
<script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
<script src="{{ asset('frontend/assets/js/custom-wow.js') }}"></script>

<!-- script js -->
<script src="{{ asset('frontend/assets/js/script.js') }}"></script>

<!-- theme setting js -->
<script src="{{ asset('frontend/assets/js/theme-setting.js') }}"></script>
</body>
</html>
