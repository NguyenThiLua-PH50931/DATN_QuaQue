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
    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- Favicon -->
    <link rel="icon" href="{{ asset('frontend/assets/images/favicon/icon.png') }}" type="image/x-icon">

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
    <link href="https://fonts.googleapis.com/css2?family=Exo+2:wght@400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

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
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/vendors/ion.rangeSlider.min.css') }}">
    <!-- Template Style -->
    <link id="color-link" rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">
</head>

<body class="bg-effect">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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
                    if (rtlLink) rtlLink.setAttribute('href',
                        "{{ asset('frontend/assets/css/vendors/bootstrap.rtl.css') }}");
                } else {
                    document.documentElement.setAttribute('dir', '');
                    document.body.classList.add('ltr');
                    document.body.classList.remove('rtl');
                    if (rtlLink) rtlLink.setAttribute('href',
                        "{{ asset('frontend/assets/css/vendors/bootstrap.css') }}");
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

    <!-- Header -->
    @includeIf('frontend.header')
    <!-- mobile fix menu start -->
    <div class="mobile-menu d-md-none d-block mobile-cart">
        <ul>
            <li class="active">
                <a href="{{ route('client.home') }}">
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
    @includeIf('frontend.footer')
    @include('frontend.wishlist.quickview')

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
                                        <img src="../frontend/assets/images/vegetable/product/10.png"
                                            class="blur-up lazyload" alt="">
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
                                        <img src="../frontend/assets/images/vegetable/product/11.png"
                                            class="blur-up lazyload" alt="">
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
                                        <img src="../frontend/assets/images/vegetable/product/12.png"
                                            class="blur-up lazyload" alt="">
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
                                        <img src="../frontend/assets/images/vegetable/product/13.png"
                                            class="blur-up lazyload" alt="">
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
     <script src="{{ asset('frontend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ion.rangeSlider.min.js') }}"></script>

    @stack('scripts')
</body>
    @stack('scripts')
</html>
<style>
    .onhover-div-login {
        min-width: 140px;
        /* Độ rộng tối thiểu vừa phải */
        max-width: 180px;
        /* Giới hạn chiều rộng tối đa */
        background: #fff;
        border: 1px solid #ddd;
        border-radius: 4px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        font-size: 13px;
        color: #222;
        padding: 6px 0;
    }

    .user-box-name {
        margin: 0;
        padding: 0;
        list-style: none;
    }

    .user-box-name li {
        padding: 6px 14px;
        border-bottom: 1px solid #eee;
    }

    .user-box-name li:last-child {
        border-bottom: none;
    }

    .user-box-name li a {
        color: #333;
        text-decoration: none;
        display: block;
        white-space: nowrap;
        /* Không xuống dòng */
        overflow: hidden;
        text-overflow: ellipsis;
        /* Ẩn chữ dài quá */
    }

    .user-box-name li a:hover {
        background-color: #e7e7e7;
        color: #000;
    }

    /* Tăng cỡ chữ modal lên 4-5px */
    #view .modal-content,
    #view .modal-content * {
        font-size: 20px !important;
    }

    #view .title-name {
        font-size: 28px !important;
        font-weight: bold;
    }

    #view .price {
        font-size: 24px !important;
        color: #0da487;
    }

    #view .main-quickview-image {
        width: 500px !important;
        height: 350px !important;
        aspect-ratio: 5/4;
        object-fit: cover !important;
        border-radius: 16px;
        display: block;
        margin: 0 auto;
        background: #f8f8f8;
    }

    #view .description-thumbnails img {
        width: 48px;
        height: 48px;
        object-fit: cover;
        border-radius: 8px;
        border: 2px solid #eee;
        cursor: pointer;
        transition: border 0.2s;
        background: #f8f8f8;
        margin-right: 8px;
    }

    #view .description-thumbnails img.active {
        border: 2px solid #0da487;
    }

    #view .description-thumbnails {
        margin-top: 16px;
        justify-content: flex-start;
        gap: 0;
        flex-wrap: wrap;
    }
</style>
