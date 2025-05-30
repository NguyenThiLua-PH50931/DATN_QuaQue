<!-- Page Header Start-->
<div class="page-header">
    <div class="header-wrapper m-0">
        <div class="header-logo-wrapper p-0">
            <div class="logo-wrapper">
                <a href="{{ url('/admin') }}">
                    <img class="img-fluid main-logo" src="{{ asset('backend/assets/images/logo/1.png') }}" alt="logo">
                    <img class="img-fluid white-logo" src="{{ asset('backend/assets/images/logo/1-white.png') }}"
                        alt="logo">
                </a>
            </div>
            <div class="toggle-sidebar">
                <i class="status_toggle middle sidebar-toggle" data-feather="align-center"></i>
                <a href="{{ url('/admin') }}">
                    <img src="{{ asset('backend/assets/images/logo/1.png') }}" class="img-fluid" alt="">
                </a>
            </div>
        </div>

        <form class="form-inline search-full" action="javascript:void(0)" method="get" id="search-form">
            <div class="form-group w-100">
                <div class="Typeahead Typeahead--twitterUsers">
                    <div class="u-posRelative">
                        <input class="demo-input Typeahead-input form-control-plaintext w-100" type="text"
                            placeholder="Tìm kiếm trong admin..." name="q" title="" id="search-input" autocomplete="off">
                        <i class="close-search" data-feather="x"></i>
                        <div class="spinner-border Typeahead-spinner" role="status">
                            <span class="sr-only">Đang tải...</span>
                        </div>
                    </div>
                    <div class="Typeahead-menu" id="typeahead-menu"></div>
                </div>
            </div>
        </form>

        <div class="nav-right col-6 pull-right right-header p-0">
            <ul class="nav-menus">
                <li>
                    <span class="header-search">
                        <i class="ri-search-line"></i>
                    </span>
                </li>

                <li class="onhover-dropdown">
                    <div class="notification-box">
                        <i class="ri-notification-line"></i>
                        <span class="badge rounded-pill badge-theme">4</span>
                    </div>
                    <ul class="notification-dropdown onhover-show-div">
                        <li>
                            <i class="ri-notification-line"></i>
                            <h6 class="f-18 mb-0">Thông báo</h6>
                        </li>
                        <li>
                            <p><i class="fa fa-circle me-2 font-primary"></i>Đang xử lý giao hàng <span
                                    class="pull-right">10 phút</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle me-2 font-success"></i>Hoàn tất đơn hàng <span class="pull-right">1
                                    giờ</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle me-2 font-info"></i>Tạo vé hỗ trợ <span class="pull-right">3
                                    giờ</span></p>
                        </li>
                        <li>
                            <p><i class="fa fa-circle me-2 font-danger"></i>Hoàn tất giao hàng <span class="pull-right">6
                                    giờ</span></p>
                        </li>
                        <li>
                            <a class="btn btn-primary" href="javascript:void(0)">Xem tất cả thông báo</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <div class="mode">
                        <i class="ri-moon-line"></i>
                    </div>
                </li>

                <li class="profile-nav onhover-dropdown pe-0 me-0">
                    @auth
                    <div class="media profile-media">
                        <img class="user-profile rounded-circle"
                            src="{{ Auth::user()->avatar ? asset('storage/avatars/' . Auth::user()->avatar) : asset('backend/assets/images/users/default.jpg') }}"
                            alt="Avatar người dùng">
                        <div class="user-name-hide media-body">
                            <span>{{ Auth::user()->name }}</span>
                            <p class="mb-0 font-roboto">
                                {{ ucfirst(Auth::user()->role) }}
                                <i class="middle ri-arrow-down-s-line"></i>
                            </p>
                        </div>
                    </div>

                    <ul class="profile-dropdown onhover-show-div">
                        <li>
                            <a href="{{ url('/admin/users') }}">
                                <i data-feather="users"></i>
                                <span>Người dùng</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/orders') }}">
                                <i data-feather="archive"></i>
                                <span>Đơn hàng</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/support-ticket') }}">
                                <i data-feather="phone"></i>
                                <span>Vé hỗ trợ</span>
                            </a>
                        </li>
                        <li>
                            <a href="{{ url('/admin/profile') }}">
                                <i data-feather="settings"></i>
                                <span>Cài đặt</span>
                            </a>
                        </li>
                        <li>
                            <a data-bs-toggle="modal" data-bs-target="#staticBackdrop" href="javascript:void(0)">
                                <i data-feather="log-out"></i>
                                <span>Đăng xuất</span>
                            </a>
                        </li>
                    </ul>
                    @else
                    {{-- Tùy chọn: Hiển thị liên kết đăng nhập hoặc nội dung khác khi chưa đăng nhập --}}
                    {{-- <li><a href="{{ route('login') }}">Đăng nhập</a></li> --}}
                    @endauth
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- Page Header Ends -->

<!-- Thêm các file CSS và JS cần thiết -->
<link rel="stylesheet" href="{{ asset('backend/assets/css/style-search.css') }}">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
<script src="{{ asset('backend/assets/js/admin-search.js') }}"></script>


