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
                                 <img src="https://themes.pixelstrap.com/fastkart/assets/svg/product.svg"
                                     class="blur-up lazyload" alt="">
                             </div>
                             <div class="service-detail">
                                 <h5>Sản phẩm tươi ngon mỗi ngày</h5>
                             </div>
                         </div>

                         <!-- Giao hàng miễn phí -->
                         <div class="service-box">
                             <div class="service-image">
                                 <img src="https://themes.pixelstrap.com/fastkart/assets/svg/delivery.svg"
                                     class="blur-up lazyload" alt="">
                             </div>
                             <div class="service-detail">
                                 <h5>Miễn phí giao hàng cho đơn hàng đầu tiên</h5>
                             </div>
                         </div>

                         <!-- Giảm giá mỗi ngày -->
                         <div class="service-box">
                             <div class="service-image">
                                 <img src="https://themes.pixelstrap.com/fastkart/assets/svg/discount.svg"
                                     class="blur-up lazyload" alt="">
                             </div>
                             <div class="service-detail">
                                 <h5>Giảm giá hấp dẫn mỗi ngày</h5>
                             </div>
                         </div>

                         <!-- Giá tốt nhất -->
                         <div class="service-box">
                             <div class="service-image">
                                 <img src="https://themes.pixelstrap.com/fastkart/assets/svg/market.svg"
                                     class="blur-up lazyload" alt="">
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
                             <a href="{{ route('client.home') }}" class="web-logo nav-logo">
                                 <img src="{{ asset('/storage/banners/logo/logo.png') }}"
                                     class="img-fluid blur-up lazyload" alt="Logo Quà Quê"
                                     style="width: 150px; height: auto;">
                             </a>
                         </div>

                         <div class="footer-logo-contain">
                             <p>Quà Quê là nơi bạn tìm thấy những món đặc sản đậm đà hương vị truyền thống từ mọi
                                 miền đất nước – dành tặng người thân hoặc thưởng thức cùng gia đình.</p>

                             <ul class="address">
                                 <li>
                                     <i data-feather="home"></i>
                                     <a href="javascript:void(0)">Hà Nội, Việt Nam</a>
                                 </li>
                                 <li>
                                     <i data-feather="mail"></i>
                                     <a href="mailto:support@quaque.vn">support@quaque.vn</a>
                                 </li>
                             </ul>
                         </div>
                     </div>
                 </div>

                 <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6">
                     <div class="footer-title">
                         <h4>Sản phẩm nổi bật</h4>
                     </div>

                     <div class="footer-contain">
                         <ul>
                             <li>
                                 <a href="{{ route('client.product.index') }}#quandeptrai" class="text-content">Sản phẩm
                                     nổi bật</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}#latest-products" class="text-content">Sản
                                     phẩm mới</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}" class="text-content">Sản phẩm bán
                                     chạy</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}" class="text-content">Quà tặng trong
                                     ngày</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}#banchay" class="text-content">Lựa chọn
                                     hàng đầu</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}" class="text-content">Hàng mới về</a>
                             </li>
                         </ul>
                     </div>
                 </div>

                 <div class="col-xl col-lg-2 col-sm-3">
                     <div class="footer-title">
                         <h4>Liên kết</h4>
                     </div>

                     <div class="footer-contain">
                         <ul>
                             <li>
                                 <a href="{{ route('client.home') }}" class="text-content">Trang chủ</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.product.index') }}" class="text-content">Sản phẩm</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.blog') }}" class="text-content">Tin tức</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.about') }}" class="text-content">Giới thiệu</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.support-ticket.index') }}" class="text-content">Liên hệ</a>
                             </li>
                         </ul>
                     </div>
                 </div>

                 <div class="col-xl-2 col-sm-3">
                     <div class="footer-title">
                         <h4>Trung tâm hỗ trợ</h4>
                     </div>

                     <div class="footer-contain">
                         <ul>
                             <li>
                                 <a href="{{ route('index') }}" class="text-content">Tài khoản</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.orders.index') }}" class="text-content">Đơn hàng của bạn</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.wishlist.index') }}" class="text-content">Danh sách yêu
                                     thích</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.cart.index') }}" class="text-content">Giỏ hàng</a>
                             </li>
                             <li>
                                 <a href="{{ route('client.support-ticket.index') }}" class="text-content">Hỗ trợ khách
                                     hàng</a>
                             </li>
                         </ul>
                     </div>
                 </div>

                 <div class="col-xl-3 col-lg-4 col-sm-6">
                     <div class="footer-title">
                         <h4>Thông tin liên hệ</h4>
                     </div>

                     <div class="footer-contact">
                         <ul>
                             <li>
                                 <div class="footer-number">
                                     <i data-feather="phone"></i>
                                     <div class="contact-number">
                                         <h6 class="text-content">Hotline 24/7 :</h6>
                                         <h5>+84 123 456 789</h5>
                                     </div>
                                 </div>
                             </li>

                             <li>
                                 <div class="footer-number">
                                     <i data-feather="mail"></i>
                                     <div class="contact-number">
                                         <h6 class="text-content">Email Address :</h6>
                                         <h5>support@quaque.vn</h5>
                                     </div>
                                 </div>
                             </li>

                             <li class="social-app mb-0">
                                 <h5 class="mb-2 text-content">Tải ứng dụng :</h5>
                                 <ul>
                                     <li class="mb-0">
                                         <a href="https://play.google.com/store/apps" target="_blank">
                                             <img src="https://themes.pixelstrap.com/fastkart/assets/images/playstore.svg"
                                                 class="blur-up lazyload" alt="Google Play Store">
                                         </a>
                                     </li>
                                     <li class="mb-0">
                                         <a href="https://www.apple.com/in/app-store/" target="_blank">
                                             <img src="https://themes.pixelstrap.com/fastkart/assets/images/appstore.svg"
                                                 class="blur-up lazyload" alt="App Store">
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
                 <h6 class="text-content">©{{ date('Y') }} Quà Quê. Tất cả quyền được bảo lưu.</h6>
             </div>

             <div class="payment">
                 <img src="{{ asset('frontend/assets/images/payment/1.png') }}" class="blur-up lazyload"
                     alt="Payment Methods">
             </div>

             <div class="social-link">
                 <h6 class="text-content">Kết nối với chúng tôi :</h6>
                 <ul>
                     <li>
                         <a href="https://www.facebook.com/quaque.vn" target="_blank">
                             <i class="fa-brands fa-facebook-f"></i>
                         </a>
                     </li>
                     <li>
                         <a href="https://twitter.com/quaque_vn" target="_blank">
                             <i class="fa-brands fa-twitter"></i>
                         </a>
                     </li>
                     <li>
                         <a href="https://www.instagram.com/quaque.vn" target="_blank">
                             <i class="fa-brands fa-instagram"></i>
                         </a>
                     </li>
                     <li>
                         <a href="https://www.youtube.com/channel/quaque" target="_blank">
                             <i class="fa-brands fa-youtube"></i>
                         </a>
                     </li>
                 </ul>
             </div>
         </div>
     </div>
 </footer>
 <!-- Footer Section End -->
