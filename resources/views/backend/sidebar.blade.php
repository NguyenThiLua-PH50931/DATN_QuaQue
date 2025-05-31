<!-- Page Sidebar Start -->
<div class="sidebar-wrapper">
    <div id="sidebarEffect"></div>
    <div>
        <div class="logo-wrapper logo-wrapper-center">
            <a href="{{ url('/admin') }}" title="">
                <img class="img-fluid for-white" src="{{ asset('backend/assets/images/logo/full-white.png') }}"
                    alt="logo">
            </a>
            <div class="back-btn">
                <i class="fa fa-angle-left"></i>
            </div>
            <div class="toggle-sidebar">
                <i class="ri-apps-line status_toggle middle sidebar-toggle"></i>
            </div>
        </div>

        <div class="logo-icon-wrapper">
            <a href="{{ url('/admin') }}">
                <img class="img-fluid main-logo main-white" src="{{ asset('backend/assets/images/logo/logo.png') }}"
                    alt="logo">
                <img class="img-fluid main-logo main-dark"
                    src="{{ asset('backend/assets/images/logo/logo-white.png') }}" alt="logo">
            </a>
        </div>

        <nav class="sidebar-main">
            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="sidebar-menu">
                <ul class="sidebar-links" id="simple-bar">
                    <li class="back-btn"></li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/home') }}">
                            <i class="ri-home-line"></i>
                            <span>Trang quản trị</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-store-3-line"></i>
                            <span>Sản Phẩm</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/products') }}">Danh sách sản phẩm</a></li>
                            <li><a href="{{ url('/admin/products/create') }}">Thêm sản phẩm</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-list-check-2"></i>
                            <span>Danh Mục</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/categories') }}">Danh sách danh mục</a></li>
                            <li><a href="{{ url('/admin/categories/create') }}">Thêm danh mục</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-list-settings-line"></i>
                            <span>Thuộc tính</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/attributes') }}">Danh sách thuộc tính</a></li>
                            <li><a href="{{ url('/admin/attributes/create') }}">Thêm thuộc tính</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>Người dùng</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ route('admin.user.index') }}">Tài khoản</a></li>
                            <li><a href="{{ route('admin.user.create') }}">Thêm tài khoản</a></li>
                            <li><a href="{{ route('admin.user.hidden') }}">Tài khoản đã ẩn</a></li>
                        </ul>
                    </li>
                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>Quản lý bình luận</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li>
                                <a href="{{ route('admin.comments.index') }}">Danh sách bình luận </a>
                            </li>
                        </ul>
                    </li>
                       <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-store-3-line"></i>
                            <span>Tin tức</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/blog/index') }}">Tin tức</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-user-3-line"></i>
                            <span>Vai Trò</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/roles') }}">All Roles</a></li>
                            <li><a href="{{ url('/admin/roles/create') }}">Create Role</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/media') }}">
                            <i class="ri-price-tag-3-line"></i>
                            <span>Media</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-archive-line"></i>
                            <span>Đơn hàng</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/orders') }}">Danh sách đơn hàng</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-focus-3-line"></i>
                            <span>Localization Bỏ</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/translation') }}">Translation</a></li>
                            <li><a href="{{ url('/admin/currency-rates') }}">Currency Rates</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-price-tag-3-line"></i>
                            <span>Coupons</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/coupons') }}">Coupon List</a></li>
                            <li><a href="{{ url('/admin/coupons/create') }}">Create Coupon</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/taxes') }}">
                            <i class="ri-price-tag-3-line"></i>
                            <span>Tax</span>
                        </a>
                    </li>

                    <li class="sidebar-list">

                        <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.reviews.index') }}">
                            <i class="ri-star-line"></i>
                            <span>Product Review</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/support-ticket') }}">
                            <i class="ri-phone-line"></i>
                            <span>Support Ticket</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                            <i class="ri-settings-line"></i>
                            <span>Settings</span>
                        </a>
                        <ul class="sidebar-submenu">
                            <li><a href="{{ url('/admin/profile-setting') }}">Profile Setting</a></li>
                        </ul>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/reports') }}">
                            <i class="ri-file-chart-line"></i>
                            <span>Reports</span>
                        </a>
                    </li>

                    <li class="sidebar-list">
                        <a class="sidebar-link sidebar-title link-nav" href="{{ url('/admin/list-page') }}">
                            <i class="ri-list-check"></i>
                            <span>List Page</span>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </nav>
    </div>
</div>
<!-- Page Sidebar Ends -->
