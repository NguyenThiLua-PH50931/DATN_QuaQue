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
                         <a href="{{ url('/client/home') }}" class="web-logo nav-logo">
                             <img src="{{ asset('/storage/banners/logo/logo.png') }}" class="img-fluid blur-up lazyload"
                                 alt="Logo Quà Quê" style="height: 50px;">
                         </a>
                         {{-- TÌM KIẾM SẢN PHẨM --}}
                         {{-- <div class="middle-box">

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
                                         const query = $('#searchInput').val().trim();
                                         if (query.length > 0) {
                                             window.location.href = '/client/san-pham/search?search=' + encodeURIComponent(query);
                                         }
                                     });

                                     $('#searchInput').on('keypress', function(e) {
                                         if (e.which == 13) { // Enter key
                                             const query = $('#searchInput').val().trim();
                                             if (query.length > 0) {
                                                 window.location.href = '/client/san-pham/search?search=' + encodeURIComponent(
                                                 query);
                                             }
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
                                                 url: '/client/san-pham/search-ajax',
                                                 type: 'GET',
                                                 data: {
                                                     q: query
                                                 },
                                                 success: function(response) {
                                                     $('#searchResults').empty();

                                                     if (response.length > 0) {
                                                         response.forEach(product => {
                                                             $('#searchResults').append(`
                                                                <a href="/client/san-pham/${product.slug}" class="list-group-item list-group-item-action d-flex align-items-center">
                                                                    <img src="${product.image ? '/storage/' + product.image : '/images/default.jpg'}"
                                                                        alt="${product.name}"
                                                                        style="width: 50px; height: 50px; margin-right: 10px; object-fit: cover;" />
                                                                    ${product.name}
                                                                </a>`);
                                                         });
                                                         // Thêm liên kết "Xem thêm" nếu có hơn 3 sản phẩm
                                                         if (response.length > 3) {
                                                             $('#searchResults').append(
                                                                 `<a href="/client/san-pham/search?search=${encodeURIComponent(query)}"
                                                                 class="list-group-item list-group-item-action text-center text-primary">
                                                                 Xem tất cả kết quả cho "${query}"</a>`
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
                         </div> --}}
                         <div class="middle-box">
                             <div class="search-box">
                                 <div class="input-group">
                                     <input type="search" class="form-control" id="searchInput"
                                         placeholder="Tìm kiếm..." aria-label="Tìm kiếm"
                                         aria-describedby="button-addon2" value="{{ request('q') ?? '' }}" />
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
                                     // Click nút search
                                     $('#button-addon2').on('click', function() {
                                         const query = $('#searchInput').val().trim();
                                         if (query.length > 0) {
                                             // Redirect về trang catalog với param q
                                             window.location.href = '/client/san-pham?q=' + encodeURIComponent(query);
                                         }
                                     });

                                     // Nhấn enter trong ô input
                                     $('#searchInput').on('keypress', function(e) {
                                         if (e.which == 13) { // Enter key
                                             const query = $(this).val().trim();
                                             if (query.length > 0) {
                                                 window.location.href = '/client/san-pham?q=' + encodeURIComponent(query);
                                             }
                                         }
                                     });

                                     // Tự động search khi nhập trên 2 ký tự
                                     $('#searchInput').on('input', function() {
                                         const query = $(this).val().trim();
                                         if (query.length > 2) {
                                             performSearch(query);
                                         } else {
                                             $('#searchResults').hide().empty();
                                         }
                                     });

                                     // Hàm ajax search gợi ý
                                     function performSearch(query) {
                                         $.ajax({
                                             url: '/client/san-pham/search-ajax',
                                             type: 'GET',
                                             data: {
                                                 q: query
                                             }, // gửi param q giống catalog
                                             success: function(response) {
                                                 $('#searchResults').empty();

                                                 if (response.length > 0) {
                                                     response.forEach(product => {
                                                         $('#searchResults').append(`
                                    <a href="/client/san-pham/${product.slug}" class="list-group-item list-group-item-action d-flex align-items-center">
                                        <img src="${product.image ? '/storage/' + product.image : '/images/default.jpg'}"
                                            alt="${product.name}"
                                            style="width: 50px; height: 50px; margin-right: 10px; object-fit: cover;" />
                                        ${product.name}
                                    </a>`);
                                                     });
                                                     if (response.length > 3) {
                                                         $('#searchResults').append(
                                                             `<a href="/client/san-pham?q=${encodeURIComponent(query)}"
                                    class="list-group-item list-group-item-action text-center text-primary">
                                    Xem tất cả kết quả cho "${query}"</a>`
                                                         );
                                                     }
                                                     $('#searchResults').show();
                                                 } else {
                                                     $('#searchResults').append(
                                                             '<div class="list-group-item">Không tìm thấy sản phẩm.</div>')
                                                         .show();
                                                 }
                                             },
                                             error: function(xhr) {
                                                 $('#searchResults').append('<div class="list-group-item">Có lỗi xảy ra: ' + xhr
                                                     .status + '</div>').show();
                                             }
                                         });
                                     }

                                     // Ẩn kết quả khi click ngoài search box
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
                                         {{-- <div class="delivery-detail">
                                             <h6>Vận chuyển 24/7</h6>
                                             <h5>+84 987612345</h5>
                                         </div> --}}
                                     </a>
                                 </li>
                                 <li class="right-side">
                                     <a href="{{ route('client.wishlist.index') }}"
                                         class="btn p-0 position-relative header-wishlist">
                                         <i data-feather="heart"></i>
                                     </a>
                                 </li>

                                 <li class="right-side">
                                     <div class="header-badge">
                                         <a href="{{ route('client.cart.index') }}" class="cart-link text-reset">
                                             <i data-feather="shopping-cart"></i>
                                         </a>

                                         <div class="cart-popup">
                                             <ul class="cart-items-list">
                                                 @php $totalPrice = 0; @endphp
                                                 @forelse ($globalCartItems as $item)
                                                     @php
                                                         $itemTotal =
                                                             ($item->price ?? $item->product->price) * $item->quantity;
                                                         $totalPrice += $itemTotal;
                                                     @endphp
                                                     <li class="cart-item d-flex align-items-center">
                                                         @if ($item->product && $item->product->image)
                                                             <img src="{{ asset('storage/' . $item->product->image) }}"
                                                                 alt="{{ $item->product->name }}" class="cart-item-img">
                                                         @else
                                                             <img src="{{ asset('images/default-product.png') }}"
                                                                 alt="Sản phẩm không tồn tại" class="cart-item-img">
                                                         @endif

                                                         <div class="cart-item-info">
                                                             @if ($item->product)
                                                                 <a href="{{ route('client.product.detail', ['slug' => $item->product->slug]) }}"
                                                                     class="cart-item-name">
                                                                     {{ \Illuminate\Support\Str::limit($item->product->name, 20) }}
                                                                 </a>
                                                             @else
                                                                 <span class="cart-item-name text-muted">Sản phẩm không
                                                                     tồn tại</span>
                                                             @endif

                                                             <div class="cart-item-qty-price">
                                                                 {{ $item->quantity }} x
                                                                 {{ number_format($item->price ?? $item->product->price, 0, ',', '.') }}
                                                             </div>
                                                         </div>
                                                         <button type="button" class="cart-item-remove"
                                                             data-id="{{ $item->id }}" aria-label="Remove item">
                                                             &times;
                                                         </button>
                                                     </li>
                                                 @empty
                                                     <li class="text-center p-3 text-muted">Giỏ hàng trống</li>
                                                 @endforelse
                                             </ul>
                                             <div
                                                 class="cart-popup-total d-flex justify-content-between align-items-center">
                                                 <strong>Tổng: </strong>
                                                 <strong
                                                     class="total-price">{{ number_format($totalPrice, 0, ',', '.') }}
                                                 </strong>
                                             </div>
                                             <div class="cart-popup-actions d-flex justify-content-between mt-3">
                                                 <a href="{{ route('client.cart.index') }}"
                                                     class="btn btn-outline-danger btn-sm">Xem giỏ</a>
                                             </div>
                                         </div>
                                     </div>

                                     <style>
                                         .cart-popup {
                                             width: 320px;
                                             background: #fff;
                                             border-radius: 10px;
                                             box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);
                                             padding: 15px;
                                             position: absolute;
                                             right: 0;
                                             top: 100%;
                                             z-index: 1100;
                                             display: none;
                                             /* mặc định ẩn, bật hiển thị khi hover */
                                         }

                                         .cart-items-list {
                                             list-style: none;
                                             padding: 0;
                                             margin: 0 0 15px 0;
                                             max-height: 280px;
                                             overflow-y: auto;
                                         }

                                         .cart-item {
                                             gap: 10px;
                                             border-bottom: 1px solid #f0f0f0;
                                             padding: 8px 0;
                                         }

                                         .cart-item:last-child {
                                             border-bottom: none;
                                         }

                                         .cart-item-img {
                                             width: 60px;
                                             height: 60px;
                                             object-fit: cover;
                                             border-radius: 6px;
                                             flex-shrink: 0;
                                         }

                                         .cart-item-info {
                                             flex-grow: 1;
                                         }

                                         .cart-item-name {
                                             display: block;
                                             font-weight: 600;
                                             font-size: 0.95rem;
                                             color: #00897B;
                                             /* màu xanh giống mẫu */
                                             text-decoration: none;
                                             white-space: nowrap;
                                             overflow: hidden;
                                             text-overflow: ellipsis;
                                         }

                                         .cart-item-name:hover {
                                             text-decoration: underline;
                                         }

                                         .cart-item-qty-price {
                                             color: #666;
                                             font-size: 0.85rem;
                                             margin-top: 3px;
                                         }

                                         .cart-item-remove {
                                             border: none;
                                             background: transparent;
                                             font-size: 1.3rem;
                                             color: #999;
                                             cursor: pointer;
                                             padding: 0 6px;
                                             line-height: 1;
                                             transition: color 0.3s;
                                         }

                                         .cart-item-remove:hover {
                                             color: #d32f2f;
                                         }

                                         .cart-popup-total {
                                             font-size: 1.1rem;
                                             color: #00897B;
                                             font-weight: 700;
                                         }

                                         .cart-popup-actions .btn {
                                             width: 48%;
                                             font-size: 0.9rem;
                                             padding: 6px 0;
                                             border-radius: 6px;
                                         }
                                     </style>

                                     <script>
                                         cartBadge.addEventListener('mouseenter', () => {
                                             popup.style.display = 'block';
                                         });
                                         cartBadge.addEventListener('mouseleave', () => {
                                             popup.style.display = 'none';
                                         });

                                         // Xử lý xóa sản phẩm
                                         popup.querySelectorAll('.cart-item-remove').forEach(button => {
                                             button.addEventListener('click', function() {
                                                 const itemId = this.getAttribute('data-id');
                                                 if (!itemId) return;

                                                 const modalEl = document.getElementById('notificationModal');
                                                 const modalBody = document.getElementById('notificationModalBody');
                                                 const modalFooter = modalEl.querySelector('.modal-footer');

                                                 modalBody.textContent = 'Bạn chắc chắn muốn xóa sản phẩm này?';

                                                 // Reset footer nút
                                                 modalFooter.innerHTML =
                                                     `<button type="button" class="btn btn-sm btn-success" data-bs-dismiss="modal">Hủy</button>
                                                        <button type="button" class="btn btn-sm btn-danger" id="confirmDeleteBtn">Xóa</button>`;
                                                 const modal = new bootstrap.Modal(modalEl);
                                                 modal.show();

                                                 const confirmBtn = document.getElementById('confirmDeleteBtn');

                                                 confirmBtn.onclick = () => {
                                                     fetch('/client/cart/remove/' + itemId, {
                                                             method: 'DELETE',
                                                             headers: {
                                                                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                                                 'Accept': 'application/json'
                                                             }
                                                         })
                                                         .then(res => {
                                                             if (!res.ok) throw new Error('Lỗi server');
                                                             return res.json();
                                                         })
                                                         .then(data => {
                                                             if (data.success) {
                                                                 // Xóa phần tử cart-item khỏi DOM
                                                                 const cartItemElem = button.closest('.cart-item');
                                                                 if (cartItemElem) cartItemElem.remove();

                                                                 // Cập nhật tổng tiền nếu có hàm updateTotal
                                                                 if (typeof updateTotal === 'function') updateTotal();

                                                                 modal.hide();
                                                             } else {
                                                                 alert(data.message || 'Xóa thất bại');
                                                             }
                                                         })
                                                         .catch(err => {
                                                             console.error(err);
                                                             alert('Lỗi xảy ra, vui lòng thử lại');
                                                         });
                                                 };
                                             });
                                         });



                                         function updateTotal() {
                                             let total = 0;
                                             popup.querySelectorAll('.cart-items-list li').forEach(li => {
                                                 const qtyPriceText = li.querySelector('.cart-item-qty-price')?.textContent || '';
                                                 const match = qtyPriceText.match(/(\d+)\s*x\s*([\d\.]+)/);
                                                 if (match) {
                                                     const qty = parseInt(match[1]);
                                                     const priceStr = match[2].replace(/\./g, ''); // bỏ dấu chấm ngăn cách hàng nghìn
                                                     const price = parseInt(priceStr);
                                                     total += qty * price;
                                                 }

                                                 if (match) {
                                                     total += parseInt(match[1]) * parseFloat(match[2]);
                                                 }
                                             });
                                             popup.querySelector('.total-price').textContent = 'đ' + total.toFixed(2);
                                         }
                                         });
                                     </script>
                                 </li>
                                 <li class="right-side">
                                     <a href="{{ route('client.orders.index') }}" class="btn p-0 position-relative"
                                         title="Đơn hàng của tôi">
                                         <i data-feather="package"></i>
                                         {{-- Có thể thay "package" bằng "clipboard-list" hoặc "shopping-bag" tuỳ bộ icon --}}
                                     </a>
                                 </li>

                                 <li class="right-side onhover-dropdown">
                                     <div class="delivery-login-box">
                                         @if (Auth::check())
                                             <div class="delivery-icon d-flex align-items-center gap-2">
                                                 <img src="{{ asset('storage/' . Auth::user()->avatar) ?? 'path/to/default-avatar.png' }}"
                                                     alt="Avatar"
                                                     style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover;">
                                                 <strong>{{ Auth::user()->name }}</strong>
                                             </div>
                                         @else
                                             <div class="delivery-icon d-flex align-items-center gap-2">
                                                 <i data-feather="user"></i>
                                                 <span style="font-size: 18px;" class="fw-b">Tài khoản</span>
                                             </div>
                                         @endif
                                     </div>


                                     <div class="onhover-div onhover-div-login">
                                         <ul class="user-box-name">
                                             @if (Auth::check())
                                                 <li class="product-box-contain">
                                                    <a href="#" id="logout-link">Đăng xuất</a>
                                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                        @csrf
                                                    </form>
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
                                         <li class="nav-item">
                                             <a class="nav-link" href="{{ route('client.product.catalog') }}">Sản
                                                 phẩm</a>
                                         </li>

                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle"
                                                 href="{{ route('client.blog') }}">Tin
                                                 tức</a>
                                         </li>

                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle"
                                                 href="{{ route('client.about') }}">Giới
                                                 thiệu
                                             </a>
                                         </li>

                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle"
                                                 href="{{ route('client.support-ticket.index') }}">Liên
                                                 hệ
                                             </a>
                                         </li>
                                         <li class="nav-item dropdown new-nav-item">
                                             <a class="nav-link dropdown-toggle"
                                                 href="{{ route('client.wishlist.index') }}">Yêu thích
                                             </a>
                                         </li>

                                         <style>
                                             a.nav-link::before {
                                                 display: none !important;
                                                 content: none !important;
                                                 width: 0 !important;
                                                 height: 0 !important;
                                             }
                                         </style>
                                     </ul>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     {{-- modal xóa trong giỏ ở trang home --}}
     <!-- Modal xác nhận xóa sản phẩm tự tạo -->
     <div id="customConfirmModal"
         style="display:none; position: fixed; inset: 0; background: rgba(0,0,0,0.5);
    justify-content: center; align-items: center; z-index: 9999;">
         <div
             style="background: white; padding: 20px; border-radius: 8px; max-width: 320px; width: 90%; text-align: center;">
             <p id="customConfirmMessage" style="margin-bottom: 20px;">Bạn chắc chắn muốn xóa sản phẩm này?</p>
             <button id="customConfirmCancel"
                 style="padding: 6px 12px; background: #0da487; color: white; border: none; border-radius: 4px;">Hủy</button>
             <button id="customConfirmOk"
                 style="padding: 6px 12px; background: #d32f2f; color: white; border: none; border-radius: 4px;">Xóa</button>
         </div>
     </div>
     <script>
         document.addEventListener('DOMContentLoaded', function() {
             const cartBadge = document.querySelector('.header-badge');
             const popup = cartBadge.querySelector('.cart-popup');

             const customConfirmModal = document.getElementById('customConfirmModal');
             const customConfirmCancel = document.getElementById('customConfirmCancel');
             const customConfirmOk = document.getElementById('customConfirmOk');

             let currentDeleteButton = null;

             // Hiển thị popup giỏ hàng khi hover vào icon giỏ hàng
             cartBadge.addEventListener('mouseenter', () => {
                 popup.style.display = 'block';
                 updateTotal();
             });

             cartBadge.addEventListener('mouseleave', () => {
                 popup.style.display = 'none';
             });

             // Ủy quyền sự kiện click nút xóa sản phẩm trong popup giỏ hàng
             popup.addEventListener('click', function(event) {
                 if (event.target.classList.contains('cart-item-remove')) {
                     currentDeleteButton = event.target;
                     customConfirmModal.style.display = 'flex';
                 }
             });

             // Nút Hủy trong modal xác nhận
             customConfirmCancel.addEventListener('click', () => {
                 customConfirmModal.style.display = 'none';
                 currentDeleteButton = null;
             });

             // Nút Xóa trong modal xác nhận
             customConfirmOk.addEventListener('click', () => {
                 if (!currentDeleteButton) return;

                 const itemId = currentDeleteButton.getAttribute('data-id');
                 if (!itemId) return;

                 fetch('/client/cart/remove/' + itemId, {
                         method: 'DELETE',
                         headers: {
                             'X-CSRF-TOKEN': '{{ csrf_token() }}',
                             'Accept': 'application/json'
                         }
                     })
                     .then(res => {
                         if (!res.ok) throw new Error('Lỗi server');
                         return res.json();
                     })
                     .then(data => {
                         if (data.success) {
                             const cartItemElem = currentDeleteButton.closest('.cart-item');
                             if (cartItemElem) cartItemElem.remove();
                             updateTotal();
                         } else {
                             alert(data.message || 'Xóa thất bại');
                         }
                     })
                     .catch(err => {
                         console.error(err);
                         alert('Lỗi xảy ra, vui lòng thử lại');
                     })
                     .finally(() => {
                         customConfirmModal.style.display = 'none';
                         currentDeleteButton = null;
                     });
             });

             function updateTotal() {
                 let total = 0;
                 popup.querySelectorAll('.cart-items-list li.cart-item').forEach(li => {
                     const qtyPriceText = li.querySelector('.cart-item-qty-price')?.textContent || '';
                     const match = qtyPriceText.match(/(\d+)\s*x\s*([\d\.]+)/);
                     if (match) {
                         const qty = parseInt(match[1]);
                         const priceStr = match[2].replace(/\./g, '');
                         const price = parseInt(priceStr);
                         total += qty * price;
                     }
                 });
                 popup.querySelector('.total-price').textContent = total.toLocaleString('vi-VN', {
                     style: 'currency',
                     currency: 'VND'
                 });
             }
         });
     </script>

 </header>
