<!-- Page Sidebar Start -->
<div class="sidebar-wrapper" style="display: flex; flex-direction: column; height: 100vh;">
    <div id="sidebarEffect"></div>

    <!-- Fixed Logo Header -->
    <div class="logo-wrapper logo-wrapper-center" style="">
        <a href="{{ url('/admin/reports') }}" title="">
            <img class="img-fluid for-white" src="{{ asset('storage/banners/logo/logo_1.png') }}" alt="logo" style="top:30px">
        </a>
        <div class="back-btn">
            <i class="fa fa-angle-left"></i>
        </div>
    </div>
    <!-- Scrollable Navigation Area -->
    <nav class="sidebar-main">
        <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
        <div id="sidebar-menu">
            <ul class="sidebar-links" id="simple-bar">
                <li class="back-btn"></li> <br><br><br><br>
                {{-- <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.dashboard') }}">
                        <i class="ri-list-check"></i>
                        <span>Báo cáo hệ thống</span>
                    </a>
                </li> --}}
                <li class="sidebar-list">
                    <a class="sidebar-link" href="{{ url('/admin/reports') }}">
                        <i class="ri-list-check-2"></i>
                        <span>Báo cáo hệ thống</span>
                    </a>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-user-3-line"></i>
                        <span>Người dùng</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.user.index') }}">Tài khoản</a></li>
                        {{-- <li><a href="{{ route('admin.user.hidden') }}">Tài khoản đã ẩn</a></li> --}}
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-store-3-line"></i>
                        <span>Sản Phẩm</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ url('/admin/products') }}">Danh sách sản phẩm</a></li>
                        <li><a href="{{ url('/admin/products/create') }}">Thêm sản phẩm</a></li>
                        <li><a href="{{ url('/admin/products/trashed') }}">Thùng rác</a></li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-list-check-2"></i>
                        <span>Danh mục</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ url('/admin/categories') }}">Danh sách danh mục</a></li>
                        <li><a href="{{ url('/admin/categories/trashed') }}">Thùng rác</a></li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-landscape-line"></i>
                        <span>Vùng miền</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ url('/admin/regions') }}">Danh sách vùng miền</a></li>
                        <li><a href="{{ url('/admin/regions/trashed') }}">Thùng rác</a></li>
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
                        <li><a href="{{ url('/admin/attributes/trashed') }}">Thùng rác</a></li>
                    </ul>
                </li>



                <li class="sidebar-list">
                    <a class="sidebar-link" href="{{ url('/admin/orders') }}">
                        <i class="ri-list-check-2"></i>
                        <span>Đơn hàng</span>
                    </a>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-image-2-line"></i>
                        <span>Banner</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ url('/admin/banners') }}">Danh sách banner</a></li>
                        <li><a href="{{ url('/admin/banners/create') }}">Thêm mới banner</a></li>
                        <li><a href="{{ url('/admin/banners/trashed') }}">Thùng rác</a></li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-feedback-line"></i>
                        <span>Bình luận</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li>
                            <a href="{{ route('admin.comments.index') }}">Danh sách bình luận </a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-megaphone-line"></i>
                        <span>Tin tức</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ url('/admin/blog/index') }}"> Danh sách tin tức</a></li>
                        <li><a href="{{ url('/admin/blog/trashed') }}">Thùng rác</a></li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.reviews.index') }}">
                        <i class="ri-star-line"></i>
                        <span>Đánh giá</span>
                    </a>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-price-tag-3-line"></i>
                        <span>Mã giảm giá</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.coupon.index') }}">Danh sách</a></li>
                    </ul>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title link-nav" href="{{ route('admin.support-ticket.index') }}">
                        <i class="ri-phone-line"></i>
                        <span>Hỗ trợ</span>
                    </a>
                </li>

                <li class="sidebar-list">
                    <a class="sidebar-link sidebar-title" href="javascript:void(0)">
                        <i class="ri-settings-line"></i>
                        <span>Cài đặt</span>
                    </a>
                    <ul class="sidebar-submenu">
                        <li><a href="{{ route('admin.setting.profile') }}">Chỉnh sửa hồ sơ</a></li>
                    </ul>
                </li>
            </ul>
        </div>
        <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
    </nav>

</div>
<!-- Page Sidebar Ends -->
<style>
.logo-wrapper {
  position: sticky;
  top: 0;
  z-index: 10; /* Đảm bảo logo nằm trên menu */
  background-color: inherit; /* Giữ màu nền để logo không bị trong suốt */
}

.sidebar-main {
  overflow-y: auto;
  flex: 1 1 auto;
}
.logo-wrapper {
  height: 180px; /* hoặc chiều cao logo của bạn */
  position: sticky;
  top: 0;
  z-index: 10;
  background-color: inherit;
  display: flex;
  align-items: center;
  justify-content: center;
}
</style>
