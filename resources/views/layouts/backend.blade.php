<!DOCTYPE html>
<html lang="en" dir="ltr">
<!-- Mirrored from themes.pixelstrap.com/fastkart/back-end/index.html by HTTrack Website Copier/3.x [XR&CO'2014], Wed, 06 Nov 2024 14:35:16 GMT -->

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Fastkart admin is super flexible, powerful, clean &amp; modern responsive bootstrap 5 admin template with unlimited possibilities.">
    <meta name="keywords"
        content="admin template, Fastkart admin template, dashboard template, flat admin template, responsive admin template, web app">
    <meta name="author" content="pixelstrap">
    <link rel="icon" href="{{ asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('backend/assets/images/favicon.png') }}" type="image/x-icon">
    <title>@yield('title', 'Admin Panel')</title>

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

    @stack('styles')
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

    </div>
</div>

    <!-- page-wrapper End-->

    <!-- Modal Start -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
        aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog  modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <h5 class="modal-title" id="staticBackdropLabel">Logging Out</h5>
                    <p>Are you sure you want to log out?</p>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    <div class="button-box">
                        <button type="button" class="btn btn--no" data-bs-dismiss="modal">No</button>
                        <button type="button" class="btn  btn--yes btn-primary">Yes</button>
                    </div>
<!-- Logout Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
     aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog  modal-dialog-centered">
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
        @yield('script')
<!-- Scripts -->
<script src="{{ asset('backend/assets/js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/icons/feather-icon/feather-icon.js') }}"></script>
<script src="{{ asset('backend/assets/js/scrollbar/simplebar.js') }}"></script>
<script src="{{ asset('backend/assets/js/scrollbar/custom.js') }}"></script>
<script src="{{ asset('backend/assets/js/config.js') }}"></script>
<script src="{{ asset('backend/assets/js/tooltip-init.js') }}"></script>
<script src="{{ asset('backend/assets/js/sidebar-menu.js') }}"></script>
<script src="{{ asset('backend/assets/js/notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/notify/index.js') }}"></script>
<script src="{{ asset('backend/assets/js/chart/apex-chart/apex-chart1.js') }}"></script>
<script src="{{ asset('backend/assets/js/chart/apex-chart/moment.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/chart/apex-chart/apex-chart.js') }}"></script>
<script src="{{ asset('backend/assets/js/chart/apex-chart/stock-prices.js') }}"></script>
<script src="{{ asset('backend/assets/js/chart/apex-chart/chart-custom1.js') }}"></script>
<script src="{{ asset('backend/assets/js/slick.min.js') }}"></script>
<script src="{{ asset('backend/assets/js/custom-slick.js') }}"></script>
<script src="{{ asset('backend/assets/js/customizer.js') }}"></script>
<script src="{{ asset('backend/assets/js/ratio.js') }}"></script>
<script src="{{ asset('backend/assets/js/sidebareffect.js') }}"></script>
<script src="{{ asset('backend/assets/js/script.js') }}"></script>

                    @stack('scripts')
</body>

</html>
