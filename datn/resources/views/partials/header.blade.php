<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Efway Fresh Stores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --background-image: url('{{ asset('image/background.jpg') }}');
        }
    </style>
    @vite(['resources/css/header.css'])

</head>

<body>
    <div class="container-fluid my-3">
        <div class="header-wrapper">
            <div class="container-fluid d-flex justify-content-between align-items-center topbar">
                <div class="pe-3">New Offers This Weekend only to <span class="green">Get 50%</span> Flate</div>
                <div class="d-flex align-items-center">
                    <div class="px-3 border-end text-muted"><i class="bi bi-geo-alt"></i> Store location</div>
                    <div class="ps-3 text-muted"><i class="bi bi-headset"></i> (+048) - 1800 33 689</div>
                </div>
            </div>

            <div class="row align-items-center">
                {{-- Logo --}}
                <div class="col-md-3 p-0 border-end" style="height: 100%;">
                    <div class="d-flex justify-content-center align-items-center w-100 h-100" style="min-height:120px;">
                        <img src="image/logo.jpg" alt="Logo" width="130">
                    </div>
                </div>

                {{-- Search + Icons --}}
                <div class="col-md-9 d-flex align-items-center ps-4">
                    <div class="search-container flex-grow-1 me-4">
                        <input type="text" class="search-input" placeholder="Tìm kiếm sản phẩm">
                        <button class="search-button">Search</button>
                    </div>

                    {{-- User Icons --}}
                    <div class="user-actions d-flex align-items-center gap-3">
                        {{-- Login --}}
                        <div class="dropdown user-dropdown position-relative border-end pe-3">
                            <span class="user dropdown-toggle"><i class="bi bi-person"></i> Tài khoản</span>
                            <div class="dropdown-menu custom-dropdown">
                                <a class="dropdown-item" href="#">Đăng nhập</a>
                                <a class="dropdown-item" href="#">Đăng ký</a>
                            </div>
                        </div>
                        {{-- Wishlist --}}
                        <div class="position-relative border-end pe-3">
                            <div class="icon-box">
                                <i class="bi bi-heart"></i>
                                <div class="icon-badge">0</div>
                            </div>
                        </div>
                        {{-- Cart --}}
                        <div class="d-flex align-items-center">
                            <div class="icon-box" style="background-color: #e8f0d9;">
                                <i class="bi bi-bag"></i>
                                <div class="icon-badge green">0</div>
                            </div>
                            <span class="ms-2">$0.00</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Navbar --}}
            <nav class="navbar navbar-expand-lg navbar-light p-0">
                <div class="collapse navbar-collapse justify-content-center">
                    <ul class="navbar-nav">
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="bi bi-house-door me-1"></i>Trang chủ</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-shop me-1"></i>Cửa hàng
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Bố trí cửa hàng</a></li>
                                <li><a class="dropdown-item" href="#">Danh sách cửa hàng</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-file-earmark-text me-1"></i>Trang
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">FAQ</a></li>
                                <li><a class="dropdown-item" href="#">Điều khoản</a></li>
                            </ul>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Blog</a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#">Blog List</a></li>
                                <li><a class="dropdown-item" href="#">Blog Single</a></li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#"><i class="bi bi-info-circle me-1"></i>Giới thiệu</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link active" href="#"><i class="bi bi-envelope me-1"></i>Liên hệ</a>
                        </li>
                    </ul>
                </div>
            </nav>
        </div>
    </div>

    {{-- Main Content --}}
    <div class="container mt-4">
        @yield('content')
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
