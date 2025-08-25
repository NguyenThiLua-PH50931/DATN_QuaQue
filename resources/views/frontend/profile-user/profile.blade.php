@extends('layouts.frontend')
@section('title', 'Chỉnh sửa hồ sơ')
@section('contents')
<style>
    .log-in-box {
        width: calc(100% + 150px);
        margin-left: -20px;
    }

    @media (max-width: 900px) {
        .log-in-box {
            width: 100%;
            margin-left: 0;
        }
    }

    .row {
        justify-content: left;
    }
    #changePasswordModal .form-control {
  min-width: 500px;
  font-size: 1.08rem;
}
#birthday + .btn {
  position: absolute;
  top: 50%;
  right: 14px;
  transform: translateY(-50%);
  z-index: 2;
}
.theme-form-floating { position: relative; }
</style>
<!-- profile edit section start -->

<!-- profile edit section end -->
<!-- User Dashboard Section Start -->
<section class="user-dashboard-section section-b-space">
    <div class="container-fluid-lg">
        <div class="row">
            <div class="col-xxl-3 col-lg-4">
                <div class="dashboard-left-sidebar">
                    <!-- Nút đóng cho mobile -->
                    <div class="close-button d-flex d-lg-none">
                        <button class="close-sidebar">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>

                    <!-- Box avatar + cover -->
                    <div class="profile-box">
                        <div class="cover-image">
                            <img
                                src="{{ asset('frontend/assets/images/inner-page/cover-img.jpg') }}"
                                class="img-fluid blur-up lazyload"
                                alt="Ảnh cover" />
                        </div>

                        <div class="profile-contain">
                            <div class="profile-image">
                                <div class="position-relative">
                                    <img
                                        id="user-avatar-preview"
                                        src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('frontend/assets/images/inner-page/user/1.jpg') }}"
                                        class="blur-up lazyload update_img"
                                        alt="{{ $user->name ?? 'avatar' }}" />
                                    <div class="cover-icon">
                                        <form id="avatar-upload-form" enctype="multipart/form-data">
                                            <label for="user-avatar-input" style="cursor:pointer;margin-bottom:0;">
                                                <i class="fa-solid fa-pen"></i>
                                                <input type="file" id="user-avatar-input" name="avatar" style="display:none"
                                                    accept="image/*" />
                                            </label>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="profile-name">
                                <h3>{{ $user->name ?? 'Chưa đặt tên' }}</h3>
                                <h6 class="text-content">{{ $user->email ?? '' }}</h6>
                            </div>
                        </div>
                    </div>

                    <!-- MENU sidebar -->
                    <ul class="nav nav-pills user-nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="pills-dashboard-tab" data-bs-toggle="pill" data-bs-target="#pills-dashboard"
                                type="button" role="tab" aria-controls="pills-dashboard" aria-selected="true">
                                <i data-feather="home"></i> Tổng quan
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-order-tab" data-bs-toggle="pill" data-bs-target="#pills-order"
                                type="button" role="tab" aria-controls="pills-order" aria-selected="false">
                                <i data-feather="shopping-bag"></i> Đơn hàng
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-wishlist-tab" data-bs-toggle="pill" data-bs-target="#pills-wishlist"
                                type="button" role="tab" aria-controls="pills-wishlist" aria-selected="false">
                                <i data-feather="heart"></i> Yêu thích
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-address-tab" data-bs-toggle="pill" data-bs-target="#pills-address"
                                type="button" role="tab" aria-controls="pills-address" aria-selected="false">
                                <i data-feather="map-pin"></i> Địa chỉ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-profile-tab" data-bs-toggle="pill" data-bs-target="#pills-profile"
                                type="button" role="tab" aria-controls="pills-profile" aria-selected="false">
                                <i data-feather="user"></i> Hồ sơ
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="pills-security-tab" data-bs-toggle="pill" data-bs-target="#pills-security"
                                type="button" role="tab" aria-controls="pills-security" aria-selected="false">
                                <i data-feather="shield"></i> Bảo mật
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-xxl-9 col-lg-8">
                <button
                    class="btn left-dashboard-show btn-animation btn-md fw-bold d-block mb-4 d-lg-none">
                    Show Menu
                </button>
                <div class="dashboard-right-sidebar">
                    <div class="tab-content" id="pills-tabContent">
                        <div
                            class="tab-pane fade show active"
                            id="pills-dashboard"
                            role="tabpanel"
                            aria-labelledby="pills-dashboard-tab">
                            <div class="dashboard-home">
                                <div class="title">
                                    <h2>Bảng điều khiển tài khoản</h2>
                                    <span class="title-leaf">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>

                                <div class="dashboard-user-name">
                                    <h6 class="text-content">
                                        Xin chào,
                                        <b class="text-title">{{ $user->name }}</b>
                                    </h6>
                                    <p class="text-content">
                                        Tại trang tài khoản, bạn có thể xem nhanh các hoạt động gần đây và cập nhật thông tin cá nhân. Chọn một mục dưới đây để xem hoặc chỉnh sửa.
                                    </p>
                                </div>

                                <div class="total-box">
                                    <div class="row g-sm-4 g-3">
                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="totle-contain">
                                                <img src="{{ asset('frontend/assets/images/svg/pending.svg') }}" class="img-1 blur-up lazyload" alt="" />
                                                <img src="{{ asset('frontend/assets/images/svg/pending.svg') }}" class=" blur-up lazyload" alt="" />
                                                <div class="totle-detail">
                                                    <h5>Tổng số đơn hàng</h5>
                                                    <h3>{{ $totalOrder }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="totle-contain">
                                                <img src="{{ asset('frontend/assets/images/svg/order.svg') }}" class="img-1 blur-up lazyload" alt="" />
                                                <img src="{{ asset('frontend/assets/images/svg/order.svg') }}" class=" blur-up lazyload" alt="" />
                                                <div class="totle-detail">
                                                    <h5>Đơn hàng đang chờ</h5>
                                                    <h3>{{ $totalPendingOrder }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-xxl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="totle-contain">
                                                <img src="{{ asset('frontend/assets/images/svg/wishlist.svg') }}" class="img-1 blur-up lazyload" alt="" />
                                                <img src="{{ asset('frontend/assets/images/svg/wishlist.svg') }}" class=" blur-up lazyload" alt="" />
                                                <div class="totle-detail">
                                                    <h5>Sản phẩm yêu thích</h5>
                                                    <h3>{{ $totalWishlist }}</h3>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="dashboard-title">
                                    <h3>Thông tin tài khoản</h3>
                                </div>

                                <div class="row g-4">
                                    <div class="col-xxl-6">
                                        <div class="dashboard-contant-title">
                                            <h4>
                                                Thông tin liên hệ
                                                <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Sửa</a>
                                            </h4>
                                        </div>
                                        <div class="dashboard-detail">
                                            <h6 class="text-content">{{ $user->name }}</h6>
                                            <h6 class="text-content">{{ $user->email }}</h6>
                                            <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#changePasswordModal">Đổi mật khẩu</a>
                                        </div>
                                    </div>

                                    <div class="col-xxl-6">
                                        <div class="dashboard-contant-title">
                                            <h4>
                                                Sổ địa chỉ
                                                <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editAddressModal">Sửa</a> -->
                                            </h4>
                                        </div>
                                        <div class="row g-4">
                                            <div class="col-xxl-6">
                                                <div class="dashboard-detail">
                                                    <h6 class="text-content">Địa chỉ mặc định</h6>
                                                    @if($defaultAddress)
                                                    <h6 class="text-content">
                                                        {{ $defaultAddress->recipient_name }} <br>
                                                        {{ $defaultAddress->phone }} <br>
                                                        {{ $defaultAddress->address }}, {{ $defaultAddress->ward }},
                                                        {{ $defaultAddress->district }}, {{ $defaultAddress->province }}
                                                    </h6>
                                                    @else
                                                    <h6 class="text-content">Bạn chưa thiết lập địa chỉ mặc định.</h6>
                                                    @endif
                                                    <!-- <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editAddressModal">Sửa địa chỉ</a> -->
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div
                            class="tab-pane fade show"
                            id="pills-wishlist"
                            role="tabpanel"
                            aria-labelledby="pills-wishlist-tab">
                            <div class="dashboard-wishlist">
                                <div class="title">
                                    <h2>Danh sách sản phẩm yêu thích</h2>
                                    <span class="title-leaf title-leaf-gray">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>
                                <div id="wishlist-wrapper">
                                    @include('frontend.profile-user._wishlist-list', ['wishlist' => $wishlist])
                                </div>
                            </div>

                        </div>

                        <div
                            class="tab-pane fade show"
                            id="pills-order"
                            role="tabpanel"
                            aria-labelledby="pills-order-tab">
                            <div class="dashboard-order">
                                <div class="title">
                                    <h2>Lịch sử đơn hàng của tôi</h2>
                                    <span class="title-leaf title-leaf-gray">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>
                                <div id="order-history-wrapper">
                                    @include('frontend.profile-user._order-list', ['orders' => $orders])
                                </div>
                            </div>

                        </div>

                        <div
                            class="tab-pane fade show"
                            id="pills-address"
                            role="tabpanel"
                            aria-labelledby="pills-address-tab">
                            <div class="dashboard-address">
                                <div class="title title-flex">
                                    <div>
                                        <h2>Địa chỉ của tôi</h2>
                                        <span class="title-leaf">
                                            <svg class="icon-width bg-gray">
                                                <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                            </svg>
                                        </span>
                                    </div>
                                </div>

                                <div class="row g-sm-4 g-3">
                                    <div>
                                        <div class="address-box">
                                            @if($defaultAddress)
                                            <div>
                                                <div class="table-responsive address-table">
                                                    <table class="table">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="2">
                                                                    {{ $defaultAddress->recipient_name }}
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Địa chỉ:</td>
                                                                <td>
                                                                    <p>
                                                                        {{ $defaultAddress->address }},
                                                                        {{ $defaultAddress->ward }},
                                                                        {{ $defaultAddress->district }},
                                                                        {{ $defaultAddress->province }}
                                                                    </p>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td>Điện thoại:</td>
                                                                <td>{{ $defaultAddress->phone }}</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <!-- <div class="button-group">
                                                <button class="btn btn-sm add-button w-100" data-bs-toggle="modal" data-bs-target="#editAddressModal">
                                                    <i data-feather="edit"></i>
                                                    Sửa
                                                </button>
                                            </div> -->
                                            @else
                                            <div class="alert alert-warning">
                                                Bạn chưa thiết lập địa chỉ mặc định.
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div
                            class="tab-pane fade show"
                            id="pills-profile"
                            role="tabpanel"
                            aria-labelledby="pills-profile-tab">
                            <div class="dashboard-profile">
                                <div class="title">
                                    <h2>Thông tin cá nhân</h2>
                                    <span class="title-leaf">
                                        <svg class="icon-width bg-gray">
                                            <use xlink:href="../assets/svg/leaf.svg#leaf"></use>
                                        </svg>
                                    </span>
                                </div>

                                <div class="profile-detail dashboard-bg-box">
                                    <div class="dashboard-title">
                                        <h3>Họ và tên</h3>
                                    </div>
                                    <div class="profile-name-detail">
                                        <div class="d-sm-flex align-items-center d-block">
                                            <h3>{{ $user->name ?? 'Chưa cập nhật' }}</h3>
                                        </div>
                                        <a href="javascript:void(0)" data-bs-toggle="modal" data-bs-target="#editProfile">Sửa</a>
                                    </div>

                                    <div class="location-profile">
                                        <ul>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="map-pin"></i>
                                                    <h6>
                                                        @if($defaultAddress)
                                                        {{ $defaultAddress->district ?? '' }}, {{ $defaultAddress->province ?? '' }}
                                                        @else
                                                        Chưa cập nhật
                                                        @endif
                                                    </h6>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="mail"></i>
                                                    <h6>{{ $user->email ?? 'Chưa cập nhật' }}</h6>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="location-box">
                                                    <i data-feather="check-square"></i>
                                                    <h6>
                                                        Thành viên {{ $user->created_at ? $user->created_at->diffForHumans() : '' }}
                                                        ({{ $user->created_at ? $user->created_at->format('d/m/Y') : '' }})
                                                    </h6>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>

                                    <div class="profile-description">
                                        <p>
                                            {{-- Nếu có trường tiểu sử (bio) --}}
                                            {{ $user->bio ?? '' }}
                                        </p>
                                    </div>
                                </div>

                                <div class="profile-about dashboard-bg-box">
                                    <div class="row">
                                        <div class="col-xxl-7">
                                            <div class="dashboard-title mb-3">
                                                <h3>Thông tin chi tiết</h3>
                                            </div>

                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Giới tính :</td>
                                                            <td>{{ $user->gender == 'female' ? 'Nữ' : ($user->gender == 'male' ? 'Nam' : 'Chưa cập nhật') }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Ngày sinh :</td>
                                                            <td>{{ $user->birthday ? \Carbon\Carbon::parse($user->birthday)->format('d/m/Y') : 'Chưa cập nhật' }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td>Số điện thoại :</td>
                                                            <td>
                                                                <a href="javascript:void(0)">
                                                                    {{ $user->phone ?? 'Chưa cập nhật' }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Địa chỉ :</td>
                                                            <td style="max-width:390px; white-space:normal; word-break:break-word;">
                                                                @if($defaultAddress)
                                                                {{ $defaultAddress->address }},
                                                                {{ $defaultAddress->ward }},
                                                                {{ $defaultAddress->district }},
                                                                {{ $defaultAddress->province }}
                                                                @else
                                                                Chưa cập nhật
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div class="dashboard-title mb-3">
                                                <h3>Thông tin đăng nhập</h3>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <tbody>
                                                        <tr>
                                                            <td>Email :</td>
                                                            <td>
                                                                <a href="javascript:void(0)">
                                                                    {{ $user->email }}
                                                                    <span data-bs-toggle="modal" data-bs-target="#editProfile">Sửa</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Mật khẩu :</td>
                                                            <td>
                                                                <a href="javascript:void(0)">
                                                                    ●●●●●●
                                                                    <span data-bs-toggle="modal" data-bs-target="#changePasswordModal">Đổi mật khẩu</span>
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="col-xxl-5">
                                            <div class="profile-image">
                                                <img
                                                    src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('frontend/assets/images/inner-page/dashboard-profile.png') }}"
                                                    class="img-fluid blur-up lazyload"
                                                    alt="" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div
                            class="tab-pane fade show"
                            id="pills-security"
                            role="tabpanel"
                            aria-labelledby="pills-security-tab">
                            <div class="dashboard-privacy">
                                <div class="dashboard-bg-box mt-4">
                                    <div class="dashboard-title mb-4">
                                        <h3>Cài đặt tài khoản</h3>
                                    </div>

                                    <div class="privacy-box">
                                        <div class="d-flex align-items-start">
                                            <h6>Xóa tài khoản vĩnh viễn</h6>
                                            <div class="form-check form-switch switch-radio ms-auto">
                                                <input class="form-check-input" type="checkbox" role="switch" id="delete-permanent-switch" />
                                                <label class="form-check-label" for="delete-permanent-switch"></label>
                                            </div>
                                        </div>
                                        <p class="text-content">
                                            Sau khi xóa, bạn sẽ bị đăng xuất và không thể đăng nhập lại. Dữ liệu sẽ bị xóa vĩnh viễn.
                                        </p>
                                    </div>

                                    <div class="privacy-box">
                                        <div class="d-flex align-items-start">
                                            <h6>Xóa tài khoản tạm thời (30 ngày)</h6>
                                            <div class="form-check form-switch switch-radio ms-auto">
                                                <input class="form-check-input" type="checkbox" role="switch" id="delete-temporary-switch" />
                                                <label class="form-check-label" for="delete-temporary-switch"></label>
                                            </div>
                                        </div>
                                        <p class="text-content">
                                            Nếu bạn chọn xóa tạm thời, tài khoản sẽ bị khóa trong 30 ngày.<br>
                                            Nếu sau 30 ngày bạn không đăng nhập lại thì tài khoản sẽ bị xóa vĩnh viễn.
                                        </p>
                                    </div>

                                    <button id="delete-account-btn"
                                        class="btn theme-bg-color btn-md fw-bold mt-4 text-white"
                                        data-bs-toggle="modal" data-bs-target="#removeProfile">
                                        Xóa tài khoản của tôi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- User Dashboard Section End -->
<!-- Edit Profile Start -->
<div class="modal fade theme-modal"
     id="editProfile"
     tabindex="-1"
     aria-labelledby="editProfileLabel"
     aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered modal-fullscreen-sm-down">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="editProfileLabel">Chỉnh sửa hồ sơ</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
      <form id="editProfileForm" method="POST" action="{{ route('user.update-profile') }}">
        @csrf
        <div class="modal-body">
          <div class="row g-4">
            <div class="col-xxl-12">
              <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="pname" name="name"
                  value="{{ old('name', auth()->user()->name ?: 'Chưa cập nhật') }}" />
                <label for="pname">Họ tên</label>
                <div class="invalid-feedback" id="error-name"></div>
              </div>
            </div>
            <div class="col-xxl-6">
              <div class="form-floating theme-form-floating">
                <input type="text" class="form-control" id="email" name="email"
                  value="{{ old('email', auth()->user()->email ?: 'Chưa cập nhật') }}" />
                <label for="email">Email</label>
                <div class="invalid-feedback" id="error-email"></div>
              </div>
            </div>
            <div class="col-xxl-6">
              <div class="form-floating theme-form-floating">
                <input class="form-control" type="tel" name="phone" id="phone"
                  maxlength="15"
                  value="{{ old('phone', auth()->user()->phone ?: '') }}"
                  placeholder="Chưa cập nhật"
                  oninput="if (this.value.length > this.maxLength) this.value = this.value.slice(0, this.maxLength);" />
                <label for="phone">Số điện thoại</label>
                <div class="invalid-feedback" id="error-phone"></div>
              </div>
            </div>
            <div class="col-xxl-6">
              <div class="form-floating theme-form-floating">
                <select class="form-select" id="gender" name="gender">
                  <option value="">Chưa cập nhật</option>
                  <option value="male" {{ old('gender', auth()->user()->gender) == 'male' ? 'selected' : '' }}>Nam</option>
                  <option value="female" {{ old('gender', auth()->user()->gender) == 'female' ? 'selected' : '' }}>Nữ</option>
                </select>
                <label for="gender">Giới tính</label>
                <div class="invalid-feedback" id="error-gender"></div>
              </div>
            </div>
            <div class="col-xxl-6">
  <div class="form-floating theme-form-floating">
    <input class="form-control" type="text" name="birthday" id="birthday"
      value="{{ old('birthday', auth()->user()->birthday ? \Carbon\Carbon::parse(auth()->user()->birthday)->format('d/m/Y') : '') }}"
      placeholder="Chưa cập nhật" autocomplete="off" />
    <label for="birthday">Ngày sinh</label>
    <div class="invalid-feedback" id="error-birthday"></div>
  </div>
</div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">
            Đóng
          </button>
          <button type="submit" class="btn theme-bg-color btn-md fw-bold text-light">
            Lưu thay đổi
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
<!-- Edit Profile End -->

<!-- Modal xác nhận xóa tài khoản -->
<div class="modal fade theme-modal remove-profile" id="removeProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header d-block text-center">
                <h5 class="modal-title w-100" id="exampleModalLabel22">
                    Bạn có chắc chắn muốn xóa tài khoản?
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="remove-box">
                    <p>
                        Sau khi xác nhận, tài khoản sẽ bị xóa theo hình thức bạn đã chọn ở trên.
                    </p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">
                    Không
                </button>
                <button id="confirm-delete-account" type="button" class="btn theme-bg-color btn-md fw-bold text-light">
                    Đồng ý
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal thông báo đã xóa thành công -->
<div class="modal fade theme-modal remove-profile" id="successRemoveProfile" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-fullscreen-sm-down">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel12">
                    Hoàn tất!
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="remove-box text-center">
                    <h4 class="text-content">Tài khoản đã được xóa.</h4>
                </div>
            </div>
            <div class="modal-footer pt-0">
                <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">
                    Đóng
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Modal đổi mật khẩu -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="changePasswordForm" method="POST" action="{{ route('user.change-password') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePasswordModalLabel">Đổi mật khẩu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="currentPassword" class="form-label">Mật khẩu cũ</label>
            <input type="password" class="form-control form-control-lg" id="currentPassword" name="old_password" autocomplete="current-password">
            <div class="invalid-feedback" id="error-old_password"></div>
            <div class="mt-1">
              <a href="{{ route('forgot') }}" id="forgot-link" tabindex="-1" class="text-decoration-underline small">Quên mật khẩu?</a>
            </div>
          </div>
          <div class="mb-3">
            <label for="newPassword" class="form-label">Mật khẩu mới</label>
            <input type="password" class="form-control form-control-lg" id="newPassword" name="new_password" autocomplete="new-password">
            <div class="invalid-feedback" id="error-new_password"></div>
          </div>
          <div class="mb-3">
            <label for="newPasswordConfirmation" class="form-label">Nhập lại mật khẩu mới</label>
            <input type="password" class="form-control form-control-lg" id="newPasswordConfirmation" name="new_password_confirmation" autocomplete="new-password">
            <div class="invalid-feedback" id="error-new_password_confirmation"></div>
          </div>
          <!-- Hiện mật khẩu -->
          <div class="form-check ps-0 m-0 remember-box">
            <input class="checkbox_animated check-box" type="checkbox" id="showPass">
            <label class="form-check-label" for="showPass">Hiện mật khẩu</label>
          </div>
        </div>
        <div class="modal-footer pt-0">
          <button type="button" class="btn btn-animation btn-md fw-bold mt-3" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn theme-bg-color btn-md fw-bold text-light mt-3">Đổi mật khẩu</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- Modal sửa địa chỉ -->
<div class="modal fade" id="editAddressModal" tabindex="-1" aria-labelledby="editAddressLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <form id="editAddressForm" method="POST" action="{{ route('user.address.update') }}">
      @csrf
      <input type="hidden" name="address_id" value="{{ old('address_id', $defaultAddress->id ?? '') }}">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="editAddressLabel">Sửa địa chỉ giao hàng</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
        </div>
        <div class="modal-body">
          <div class="container-fluid">
            <div class="row g-3">
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Họ và tên*</label>
                  <input type="text" name="recipient_name" class="form-control" id="edit-recipient_name"
                    value="{{ old('recipient_name', $defaultAddress->recipient_name ?? '') }}">
                  <div class="invalid-feedback" id="error-edit-recipient_name"></div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Số điện thoại*</label>
                  <input type="text" name="phone" class="form-control" id="edit-phone"
                    value="{{ old('phone', $defaultAddress->phone ?? '') }}">
                  <div class="invalid-feedback" id="error-edit-phone"></div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Tỉnh/Thành phố*</label>
                  <select id="edit-province" name="province" class="form-select"></select>
                  <div class="invalid-feedback" id="error-edit-province"></div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Quận/Huyện*</label>
                  <select id="edit-district" name="district" class="form-select"></select>
                  <div class="invalid-feedback" id="error-edit-district"></div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Phường/Xã*</label>
                  <select id="edit-ward" name="ward" class="form-select"></select>
                  <div class="invalid-feedback" id="error-edit-ward"></div>
                </div>
              </div>
              <div class="col-md-6 col-12">
                <div class="mb-3">
                  <label>Địa chỉ cụ thể*</label>
                  <input type="text" name="address" class="form-control" id="edit-address"
                    value="{{ old('address', $defaultAddress->address ?? '') }}">
                  <div class="invalid-feedback" id="error-edit-address"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-animation btn-md fw-bold" data-bs-dismiss="modal">Hủy</button>
          <button type="submit" class="btn theme-bg-color text-white">Lưu địa chỉ</button>
        </div>
      </div>
    </form>
  </div>
</div>

<!-- xoa tk -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Lấy element theo id mới
        const permanentSwitch = document.getElementById('delete-permanent-switch');
        const temporarySwitch = document.getElementById('delete-temporary-switch');

        // Chỉ cho phép chọn 1 loại
        permanentSwitch.addEventListener('change', function() {
            if (permanentSwitch.checked) temporarySwitch.checked = false;
        });
        temporarySwitch.addEventListener('change', function() {
            if (temporarySwitch.checked) permanentSwitch.checked = false;
        });

        // Xác định kiểu xóa khi mở modal xác nhận
        let deleteType = null;
        document.getElementById('delete-account-btn').addEventListener('click', function() {
            if (permanentSwitch.checked) {
                deleteType = 'permanent';
            } else if (temporarySwitch.checked) {
                deleteType = 'temporary';
            } else {
                deleteType = null;
            }
        });

        // Khi xác nhận xóa
        document.getElementById('confirm-delete-account').addEventListener('click', function() {
            if (!deleteType) {
                bootstrap.Modal.getInstance(document.getElementById('removeProfile')).hide();
                alert('Bạn cần chọn hình thức xóa tài khoản!');
                return;
            }

            fetch('/user/delete-account', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        type: deleteType
                    })
                })
                .then(res => res.json())
                .then(data => {
                    bootstrap.Modal.getInstance(document.getElementById('removeProfile')).hide();
                    if (data.success) {
                        let modal = new bootstrap.Modal(document.getElementById('successRemoveProfile'));
                        modal.show();
                        setTimeout(function() {
                            window.location.href = '/login';
                        }, 2000);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra, vui lòng thử lại!');
                    }
                })
                .catch(e => {
                    bootstrap.Modal.getInstance(document.getElementById('removeProfile')).hide();
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                });
        });
    });
</script>

<!-- sp yeu thich va don hang-->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-delete-form').forEach(function(form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                let url = form.action;
                let productBox = form.closest('.product-box-3');

                fetch(url, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': form.querySelector('input[name=_token]').value,
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        },
                        body: new URLSearchParams({
                            _method: 'DELETE',
                            _token: form.querySelector('input[name=_token]').value
                        })
                    })
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            productBox.classList.add('animate__animated', 'animate__fadeOut');
                            setTimeout(() => {
                                productBox.parentElement.remove();
                                if (document.querySelectorAll('.product-box-3').length === 0) {
                                    document.querySelector('.row.g-sm-4.g-3').innerHTML = `
                                <div class="col-12 text-center py-5">
                                    <h4>Bạn chưa có sản phẩm nào trong wishlist.</h4>
                                </div>
                            `;
                                }
                            }, 400);

                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: data.message || 'Đã xóa khỏi wishlist!',
                                showConfirmButton: false,
                                timer: 1500
                            });
                        } else {
                            throw new Error(data.message || 'Lỗi khi xoá!');
                        }
                    })
                    .catch(err => {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: err.message || 'Có lỗi xảy ra!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                    });
            });
        });
    });
</script>
<script>
    $(document).on('click', '.custome-pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (!url) return;

        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            beforeSend: function() {
                $('#order-history-wrapper').css('opacity', '0.5');
            },
            success: function(data) {
                $('#order-history-wrapper').html(data).css('opacity', '1');
                // Nếu có dùng feather icons:
                if (window.feather) feather.replace();
            },
            error: function() {
                alert('Không thể tải dữ liệu phân trang!');
            }
        });
    });
</script>
<script>
    $(document).on('click', '.wishlist-pagination a', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        if (!url) return;
        $.ajax({
            url: url,
            type: 'GET',
            dataType: 'html',
            beforeSend: function() {
                $('#wishlist-wrapper').css('opacity', '0.5');
            },
            success: function(data) {
                $('#wishlist-wrapper').html(data).css('opacity', '1');
                if (window.feather) feather.replace();
            },
            error: function() {
                alert('Không thể tải phân trang wishlist!');
            }
        });
    });
</script>
<!-- sua anh dai dien -->
    <script>
$(function() {
    $('#user-avatar-input').on('change', function() {
        const formData = new FormData();
        formData.append('avatar', this.files[0]);
        formData.append('_token', '{{ csrf_token() }}');

        // Preview UI
        if (this.files && this.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                $('#user-avatar-preview').attr('src', e.target.result);
            }
            reader.readAsDataURL(this.files[0]);
        }

        // Ajax upload
        $.ajax({
            url: '{{ route('client.profile.update_avatar') }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function() {
                // Có thể thêm loading ở đây nếu muốn
            },
            success: function(res) {
                if(res.success) {
                    $('#user-avatar-preview').attr('src', res.avatar_url);

                    // SweetAlert2 toast thành công
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: res.message || "Cập nhật ảnh đại diện thành công!",
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                    });
                } else {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: "Có lỗi khi cập nhật ảnh đại diện!",
                        showConfirmButton: false,
                        timer: 2500,
                        timerProgressBar: true,
                    });
                }
            },
            error: function(xhr) {
                let msg = "Có lỗi khi cập nhật ảnh đại diện!";
                if (xhr.responseJSON && xhr.responseJSON.errors) {
                    msg = Object.values(xhr.responseJSON.errors).join('<br>');
                }
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: msg,
                    showConfirmButton: false,
                    timer: 2500,
                    timerProgressBar: true,
                    html: true
                });
            }
        });
    });
});
</script>

<!-- doi mk -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('changePasswordForm');
    const modal = document.getElementById('changePasswordModal');
    const showPassCheckbox = document.getElementById('showPass');

    // Hiện/ẩn mật khẩu
    showPassCheckbox.addEventListener('change', function () {
        const type = this.checked ? 'text' : 'password';
        document.getElementById('currentPassword').type = type;
        document.getElementById('newPassword').type = type;
        document.getElementById('newPasswordConfirmation').type = type;
    });

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        // Xóa lỗi cũ
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                // Đóng modal, reset form, toast thành công
                bootstrap.Modal.getInstance(modal).hide();
                form.reset();
                showPassCheckbox.checked = false;
                document.getElementById('currentPassword').type = 'password';
                document.getElementById('newPassword').type = 'password';
                document.getElementById('newPasswordConfirmation').type = 'password';
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Đổi mật khẩu thành công!',
                    showConfirmButton: false,
                    timer: 2000
                });
            } else if (data.errors) {
                // Chuyển lỗi "confirmed" sang trường xác nhận
                if (data.errors.new_password && data.errors.new_password.some(
                        msg => msg.toLowerCase().includes('không khớp') || msg.toLowerCase().includes('does not match'))) {
                    data.errors.new_password_confirmation = data.errors.new_password;
                    delete data.errors.new_password;
                }
                // Hiển thị lỗi dưới từng input
                let firstErrorInput = null;
                for (const [field, msgs] of Object.entries(data.errors)) {
                    const input = form.querySelector('[name="' + field + '"]');
                    const errDiv = document.getElementById('error-' + field);
                    if (input) {
                        input.classList.add('is-invalid');
                        if (!firstErrorInput) firstErrorInput = input;
                    }
                    if (errDiv) errDiv.textContent = msgs.join(' ');
                }
                if (firstErrorInput) firstErrorInput.focus();
            } else {
                // Lỗi server khác
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'error',
                    title: data.message || 'Đổi mật khẩu thất bại!',
                    showConfirmButton: false,
                    timer: 2000
                });
            }
        })
        .catch(() => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: 'Có lỗi xảy ra, vui lòng thử lại!',
                showConfirmButton: false,
                timer: 2000
            });
        });
    });
});
</script>

<!-- thay thong tin -->
 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/css/datepicker.min.css">
<script src="https://cdn.jsdelivr.net/npm/vanillajs-datepicker@1.3.4/dist/js/datepicker-full.min.js"></script></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  // 1. Xử lý AJAX submit form
  var form = document.getElementById('editProfileForm');
  form.addEventListener('submit', function (e) {
    e.preventDefault();

    // Clear lỗi cũ
    form.querySelectorAll('.is-invalid').forEach(function (el) { el.classList.remove('is-invalid'); });
    form.querySelectorAll('.invalid-feedback').forEach(function (el) { el.textContent = ''; });

    var formData = new FormData(form);

    fetch(form.action, {
      method: 'POST',
      headers: {
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
      },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
      if (data.success) {
        bootstrap.Modal.getInstance(document.getElementById('editProfile')).hide();
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'success',
          title: 'Cập nhật hồ sơ thành công!',
          showConfirmButton: false,
          timer: 2000
        });
      } else if (data.errors) {
        let firstErrorInput = null;
        for (const [field, msgs] of Object.entries(data.errors)) {
          var input = form.querySelector('[name="' + field + '"]');
          var errDiv = document.getElementById('error-' + field);
          if (input) {
            input.classList.add('is-invalid');
            if (!firstErrorInput) firstErrorInput = input;
          }
          if (errDiv) errDiv.textContent = msgs.join(' ');
        }
        if (firstErrorInput) firstErrorInput.focus();
      } else {
        Swal.fire({
          toast: true,
          position: 'top-end',
          icon: 'error',
          title: data.message || 'Cập nhật thất bại!',
          showConfirmButton: false,
          timer: 2000
        });
      }
    })
    .catch(() => {
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: 'Có lỗi xảy ra, vui lòng thử lại!',
        showConfirmButton: false,
        timer: 2000
      });
    });
  });

  // 2. Khởi tạo datepicker cho ngày sinh
  const birthdayInput = document.getElementById('birthday');
  if (birthdayInput) {
    // Tạo nút mở lịch
    let btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'btn btn-outline-secondary btn-sm ms-2';
    btn.tabIndex = -1;
    btn.title = 'Chọn ngày sinh';
    btn.innerHTML = '<i class="fa-solid fa-calendar-days"></i>';
    birthdayInput.parentNode.appendChild(btn);

    // Khởi tạo datepicker
    const datepicker = new Datepicker(birthdayInput, {
      format: 'dd/mm/yyyy',
      autohide: true,
      clearBtn: true,
      endDate: new Date(), // chặn chọn ngày tương lai
      language: 'vi'
    });

    // Khi bấm nút sẽ show lịch
    btn.addEventListener('click', function (e) {
      e.preventDefault();
      birthdayInput.focus();
      datepicker.show();
    });
  }
});
</script>
<!-- sua dia chi -->
 <script>
    function setupVietnamAddressSelect(provinceId, districtId, wardId, provinceValue = '', districtValue = '', wardValue = '') {
    let locationsData = null;
    const provinceSelect = document.getElementById(provinceId);
    const districtSelect = document.getElementById(districtId);
    const wardSelect = document.getElementById(wardId);

    if (!provinceSelect || !districtSelect || !wardSelect) return;

    function loadWards(provinceName, districtName) {
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        wardSelect.disabled = true;

        const province = locationsData.find(p => p.Name === provinceName);
        if (!province) return;

        const district = province.Districts.find(d => d.Name === districtName);
        if (!district) return;

        district.Wards.forEach(ward => {
            const option = document.createElement('option');
            option.value = ward.Name;
            option.text = ward.Name;
            wardSelect.add(option);
        });

        wardSelect.disabled = false;
        if (wardValue) setTimeout(() => { wardSelect.value = wardValue; }, 0);
    }

    function loadDistricts(provinceName) {
        districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
        wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        districtSelect.disabled = true;
        wardSelect.disabled = true;

        const province = locationsData.find(p => p.Name === provinceName);
        if (!province) return;

        province.Districts.forEach(district => {
            const option = document.createElement('option');
            option.value = district.Name;
            option.text = district.Name;
            districtSelect.add(option);
        });

        districtSelect.disabled = false;

        if (districtValue) {
            setTimeout(() => {
                districtSelect.value = districtValue;
                loadWards(provinceName, districtValue);
            }, 0);
        }
    }

    fetch('/data/vietnamAddress.json')
        .then(response => response.json())
        .then(data => {
            locationsData = data;
            // Load danh sách tỉnh
            locationsData.forEach(province => {
                const option = document.createElement('option');
                option.value = province.Name;
                option.text = province.Name;
                provinceSelect.add(option);
            });

            if (provinceValue) {
                provinceSelect.value = provinceValue;
                loadDistricts(provinceValue);
            }
        }).catch(e => {
            console.error('Lỗi load JSON:', e);
        });

    provinceSelect.addEventListener('change', function () {
        districtValue = '';
        wardValue = '';
        loadDistricts(this.value);
    });

    districtSelect.addEventListener('change', function () {
        wardValue = '';
        loadWards(provinceSelect.value, this.value);
    });
}
// Gọi hàm setupVietnamAddressSelect như đã gửi ở trên khi mở modal!

document.addEventListener('DOMContentLoaded', function() {
    // Khi mở modal sửa địa chỉ, gán lại province/district/ward
    $('#editAddressModal').on('shown.bs.modal', function () {
        setupVietnamAddressSelect(
            'edit-province', 'edit-district', 'edit-ward',
            '{{ old('province', $defaultAddress->province ?? '') }}',
            '{{ old('district', $defaultAddress->district ?? '') }}',
            '{{ old('ward', $defaultAddress->ward ?? '') }}'
        );
    });

    // Xử lý submit AJAX cho form sửa địa chỉ
    document.getElementById('editAddressForm').addEventListener('submit', function (e) {
        e.preventDefault();
        let form = this;
        // Xóa lỗi cũ
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

        let formData = new FormData(form);
        fetch(form.action, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                bootstrap.Modal.getInstance(document.getElementById('editAddressModal')).hide();
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'success',
                    title: 'Cập nhật địa chỉ thành công!',
                    showConfirmButton: false, timer: 2000
                });
                // Có thể reload lại list địa chỉ hoặc cập nhật ngoài view:
                // setTimeout(() => window.location.reload(), 1000);
            } else if (data.errors) {
                let firstError = null;
                for (const [field, msgs] of Object.entries(data.errors)) {
                    let input = form.querySelector(`[name="${field}"]`);
                    let errDiv = document.getElementById('error-edit-' + field);
                    if (input) {
                        input.classList.add('is-invalid');
                        if (!firstError) firstError = input;
                    }
                    if (errDiv) errDiv.textContent = msgs.join(' ');
                }
                if (firstError) firstError.focus();
            } else {
                Swal.fire({
                    toast: true, position: 'top-end', icon: 'error',
                    title: data.message || 'Cập nhật thất bại!',
                    showConfirmButton: false, timer: 2000
                });
            }
        })
        .catch(() => {
            Swal.fire({
                toast: true, position: 'top-end', icon: 'error',
                title: 'Có lỗi xảy ra, vui lòng thử lại!',
                showConfirmButton: false, timer: 2000
            });
        });
    });
});

 </script>
@endsection