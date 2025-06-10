<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Fastkart admin is super flexible, powerful, clean & modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Fastkart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'Admin Panel')</title>

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@100;400;700;900&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS -->
    <link rel="stylesheet" href="{{ asset('backend/assets/css/linearicon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/font-awesome.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/themify.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/ratio.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/remixicon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/feather-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/scrollbar.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/animate.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vector-map.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/vendors/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/style.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/css/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('backend/assets/css/vendors/bootstrap-tagsinput.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/remixicon@4.3.0/fonts/remixicon.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset('backend/assets/css/aa.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/modal-fix.css') }}">
</head>

<body>
    <!-- Tap to Top -->
    <div class="tap-top">
        <span class="lnr lnr-chevron-up"></span>
    </div>
    <!-- Page Wrapper -->
    <div class="page-wrapper compact-wrapper" id="pageWrapper">

        <!-- Header -->
        @includeIf('backend.header')

        <!-- Page Body -->
        <div class="page-body-wrapper">

            <!-- Sidebar -->
            @includeIf('backend.sidebar')

            <!-- Main Content -->
            @yield('content')

            <!-- Footer Start -->
            <div class="container-fluid">
                <footer class="footer">
                    <div class="row">
                        <div class="col-md-12 footer-copyright text-center">
                            <p class="mb-0">Copyright 2022 © Fastkart theme by pixelstrap</p>
                        </div>
                    </div>
                </footer>
            </div>
            <!-- Footer End -->
        </div>
    </div>
    <!-- Logout Modal -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="staticBackdropLabel">Logging Out</h5>
                    <p>Are you sure you want to log out?</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="button-box">
                        <button type="button" class="btn btn--no" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn btn--yes btn-primary">Yes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="{{ asset('backend/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/icons/feather-icon/feather.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scrollbar/simplebar.js') }}"></script>
    <script src="{{ asset('backend/assets/js/scrollbar/custom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/config.js') }}"></script>
    <script src="{{ asset('backend/assets/js/tooltip-init.js') }}"></script>
    <script src="{{ asset('backend/assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/notify/index.js') }}"></script>
    <!-- Chỉ tải ApexCharts cho trang cần thiết -->
    @if (Request::is('dashboard*'))
        <script src="{{ asset('backend/assets/js/chart/apex-chart/apex-chart1.js') }}"></script>
        <script src="{{ asset('backend/assets/js/chart/apex-chart/moment.min.js') }}"></script>
        <script src="{{ asset('backend/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
        <script src="{{ asset('backend/assets/js/chart/apex-chart/stock-prices.js') }}"></script>
        <script src="{{ asset('backend/assets/js/chart/apex-chart/chart-custom1.js') }}"></script>
    @endif
    <script src="{{ asset('backend/assets/js/slick.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/custom-slick.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ratio.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sidebareffect.js') }}"></script>
    <script src="{{ asset('backend/assets/js/sidebar-menu.js') }}"></script>
    <script src="{{ asset('backend/assets/js/script.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="{{ asset('backend/assets/js/ckeditor.js') }}"></script>
    <script src="{{ asset('backend/assets/js/ckeditor-custom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/select2.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/select2-custom.js') }}"></script>
    <script src="{{ asset('backend/assets/js/bootstrap-tagsinput.min.js') }}"></script>
    <!-- Đặt customizer.js cuối cùng -->
    <script src="{{ asset('backend/assets/js/customizer.js') }}"></script>

    <!-- Script to handle light/dark mode persistence -->
    <script>
        (function() {
            const body = document.body;
            const modeToggle = document.querySelector('.mode');
            const savedMode = localStorage.getItem('themeMode');

            // Áp dụng trạng thái từ localStorage
            if (savedMode === 'dark') {
                body.classList.add('dark');
                localStorage.setItem('dark', 'true'); // Đồng bộ với customizer.js
                if (modeToggle && modeToggle.querySelector('i')) {
                    modeToggle.querySelector('i').classList.remove('ri-moon-line');
                    modeToggle.querySelector('i').classList.add('ri-sun-line');
                }
            } else {
                body.classList.remove('dark');
                localStorage.removeItem('dark'); // Xóa key 'dark' để đồng bộ
                if (modeToggle && modeToggle.querySelector('i')) {
                    modeToggle.querySelector('i').classList.remove('ri-sun-line');
                    modeToggle.querySelector('i').classList.add('ri-moon-line');
                }
            }

            // Xử lý khi người dùng nhấp vào biểu tượng chuyển đổi
            if (modeToggle) {
                modeToggle.addEventListener('click', function() {
                    body.classList.toggle('dark');
                    if (body.classList.contains('dark')) {
                        localStorage.setItem('themeMode', 'dark');
                        localStorage.setItem('dark', 'true');
                        if (modeToggle.querySelector('i')) {
                            modeToggle.querySelector('i').classList.remove('ri-moon-line');
                            modeToggle.querySelector('i').classList.add('ri-sun-line');
                        }
                    } else {
                        localStorage.setItem('themeMode', 'light');
                        localStorage.removeItem('dark');
                        if (modeToggle.querySelector('i')) {
                            modeToggle.querySelector('i').classList.remove('ri-sun-line');
                            modeToggle.querySelector('i').classList.add('ri-moon-line');
                        }
                    }
                });
            }
        })();
    </script>

    @stack('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
</body>

</html>
