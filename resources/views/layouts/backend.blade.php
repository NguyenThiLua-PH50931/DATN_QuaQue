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
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" href="{{ asset('frontend/assets/images/favicon/icon.png') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('frontend/assets/images/favicon/icon.png') }}" type="image/x-icon">
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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />

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
            @include('backend.footer')
            <!-- Footer End -->
            <!-- Loader Start -->
            <div class="fullpage-loader">
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
                <span></span>
            </div>
            <!-- Loader End -->
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
    {{--     <script src="{{ asset('backend/assets/js/notify/bootstrap-notify.min.js') }}"></script>
    <script src="{{ asset('backend/assets/js/notify/index.js') }}"></script> --}}
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.status-select').forEach(function(select) {
                // Kiểm tra trang show (chỉ có badge, 1 đơn)
                const isShowPage = !!document.getElementById('order-status-badge') && select.id ===
                    'order-status-select';
                // Lấy orderId, endpoint, token...
                const orderId = select.getAttribute('data-order-id');
                let updateUrl = '';
                let token = '';
                let statusBadge = null;
                let form = null;

                if (isShowPage) {
                    updateUrl = `/admin/orders/${orderId}/update-status`;
                    token = '{{ csrf_token() }}';
                    statusBadge = document.getElementById('order-status-badge');
                } else {
                    form = document.getElementById('status-form-' + orderId);
                    updateUrl = form ? form.action : '';
                    token = form ? form.querySelector('input[name="_token"]').value : '';
                }

                // Giới hạn trạng thái chuyển tiếp
                function updateStatusOptions() {
                    const current = select.value;
                    let allowed = [];
                    switch (current) {
                        case 'pending':
                            allowed = ['confirmed'];
                            break;
                        case 'confirmed':
                            allowed = ['processing'];
                            break;
                        case 'processing':
                            allowed = ['shipped'];
                            break;
                        case 'shipped':
                            allowed = ['in_transit'];
                            break;
                        case 'in_transit':
                            allowed = ['delivered', 'failed_delivery'];
                            break;
                        default:
                            allowed = [];
                    }
                    Array.from(select.options).forEach(opt => {
                        if (opt.value !== current && !allowed.includes(opt.value)) opt.disabled =
                            true;
                        else opt.disabled = false;
                    });
                }
                updateStatusOptions();

                select.addEventListener('change', function() {
                    const newStatus = this.value;

                    // Nếu là trang show, cập nhật badge trước
                    if (isShowPage && statusBadge) {
                        statusBadge.textContent = "Đang cập nhật...";
                        statusBadge.className = "badge status-pending";
                    }

                    fetch(updateUrl, {
                            method: 'PUT',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest',
                            },
                            body: JSON.stringify({
                                status: newStatus
                            }),
                        })
                        .then(async res => {
                            let data = {};
                            try {
                                data = await res.json();
                            } catch (e) {}
                            if (res.ok && (data.success || data.message)) {
                                // Trang show
                                if (isShowPage && statusBadge) {
                                    const statusTextMap = {
                                        pending: "Chờ xác nhận",
                                        confirmed: "Đã xác nhận",
                                        processing: "Đang chuẩn bị",
                                        shipped: "Đã gửi hàng",
                                        in_transit: "Đang vận chuyển",
                                        delivered: "Đã giao hàng",
                                        cancelled: "Đã hủy",
                                        failed_delivery: "Giao thất bại"
                                    };
                                    statusBadge.textContent = statusTextMap[newStatus] ||
                                        newStatus;
                                    statusBadge.className = 'badge status-' + newStatus;
                                    // Swal ở show
                                    if (window.Swal) {
                                        Swal.fire({
                                            title: 'Cập nhật trạng thái!',
                                            text: data.message ||
                                                'Trạng thái đã được cập nhật.',
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false
                                        });
                                    } else {
                                        alert(data.message ||
                                            'Cập nhật trạng thái thành công');
                                    }
                                }

                                // Trang list
                                if (!isShowPage && form) {
                                    // Swal cho list
                                    if (window.Swal) {
                                        Swal.fire({
                                            title: 'Cập nhật trạng thái!',
                                            text: data.message ||
                                                'Cập nhật trạng thái thành công',
                                            icon: 'success',
                                            confirmButtonText: 'OK',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false
                                        });
                                    } else {
                                        alert(data.message ||
                                            'Cập nhật trạng thái thành công');
                                    }
                                    // Cập nhật trạng thái cho select
                                    const currentStatus = select.getAttribute(
                                        'data-current-status');
                                    select.setAttribute('data-current-status', newStatus);
                                    select.classList.remove('status-' + currentStatus);
                                    select.classList.add('status-' + newStatus);

                                    // --------- UPDATE CỘT TTTT (thanh toán) NGAY SAU KHI ĐỔI TRẠNG THÁI ---------
                                    // Lấy <tr> hiện tại
                                    const tr = form.closest('tr');
                                    if (tr) {
                                        const tds = tr.querySelectorAll('td');
                                        const paymentTd = tds[
                                            5
                                        ]; // cột TTTT (thanh toán) là cột thứ 6 (index 5)
                                        if (paymentTd) {
                                            if (data.payment_method === 'bank') {
                                                paymentTd.innerHTML = `
                                            <select class="form-select payment-status-select${data.payment_status === 'paid' ? ' payment-paid-bank' : ''}" data-order-id="${orderId}">
                                                <option value="unpaid"${data.payment_status === 'unpaid' ? ' selected' : ''}>Chưa thanh...</option>
                                                <option value="paid"${data.payment_status === 'paid' ? ' selected' : ''}>Đã thanh...</option>
                                            </select>
                                        `;
                                                // Gắn lại event nếu muốn cho phép đổi TTTT trực tiếp trên select này
                                            } else {
                                                let labelClass =
                                                    "payment-status-label payment-" + data
                                                    .payment_status;
                                                let text = 'Chưa thanh toán';
                                                if (data.payment_status === 'paid' || (data
                                                        .payment_method === 'cod' && data
                                                        .status === 'delivered')) {
                                                    labelClass =
                                                        "payment-status-label payment-paid";
                                                    text = 'Đã thanh toán';
                                                } else if (data.payment_status ===
                                                    'failed') {
                                                    labelClass =
                                                        "payment-status-label payment-failed";
                                                    text = 'Thanh toán thất bại';
                                                }
                                                paymentTd.innerHTML =
                                                    `<span class="${labelClass}" data-payment-method="${data.payment_method}">${text}</span>`;
                                            }
                                        }
                                    }
                                    // ------------------------------------------------------------------------

                                    // Cập nhật action nếu có ul
                                    const ul = tr ? tr.querySelector('td:last-child ul') :
                                        null;
                                    if (ul) {
                                        const isHidden = !!data.is_hidden;
                                        ul.innerHTML = `
    <li>
        <a href="${form.action.replace('/update-status', '')}">
            <i class="ri-eye-line"></i>
        </a>
    </li>
    <li>
        <a href="${form.action.replace('/update-status', '/tracking')}">
            <i class="ri-map-pin-line"></i>
        </a>
    </li>
`;



                                    }
                                }
                            } else {
                                // Báo lỗi
                                const errMsg = (data && data.message) ? data.message :
                                    "Cập nhật thất bại.";
                                if (isShowPage && statusBadge) {
                                    statusBadge.textContent = "Lỗi!";
                                    statusBadge.className = 'badge status-cancelled';
                                    if (window.Swal) {
                                        Swal.fire({
                                            title: 'Lỗi!',
                                            text: errMsg,
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false
                                        });
                                    } else {
                                        alert('Lỗi: ' + errMsg);
                                    }
                                } else {
                                    if (window.Swal) {
                                        Swal.fire({
                                            title: 'Lỗi!',
                                            text: errMsg,
                                            icon: 'error',
                                            confirmButtonText: 'OK',
                                            allowOutsideClick: false,
                                            allowEscapeKey: false
                                        });
                                    } else {
                                        alert(errMsg);
                                    }
                                    // Reset về trạng thái cũ nếu có
                                    const currentStatus = select.getAttribute(
                                        'data-current-status');
                                    if (currentStatus) select.value = currentStatus;
                                }
                            }
                            updateStatusOptions();
                        })
                        .catch((err) => {
                            if (isShowPage && statusBadge) {
                                statusBadge.textContent = "Lỗi!";
                                statusBadge.className = 'badge status-cancelled';
                                if (window.Swal) {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: 'Không thể cập nhật trạng thái!',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false
                                    });
                                } else {
                                    alert('Không thể cập nhật trạng thái!');
                                }
                            } else {
                                if (window.Swal) {
                                    Swal.fire({
                                        title: 'Lỗi!',
                                        text: 'Không thể cập nhật trạng thái!',
                                        icon: 'error',
                                        confirmButtonText: 'OK',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false
                                    });
                                } else {
                                    alert('Không thể cập nhật trạng thái!');
                                }
                                // Reset về trạng thái cũ nếu có
                                const currentStatus = select.getAttribute(
                                    'data-current-status');
                                if (currentStatus) select.value = currentStatus;
                            }
                            updateStatusOptions();
                        });
                });
            });
        });
    </script>


    <script>
        document.addEventListener('submit', function(e) {
            if (e.target.matches('form[action*="hide"]')) {
                e.preventDefault();
                const form = e.target;
                const token = form.querySelector('input[name="_token"]').value;
                const action = form.action;
                const tr = form.closest('tr');

                fetch(action, {
                        method: 'PATCH', // dùng PATCH cho đúng route
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({}),
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => {
                            throw err;
                        });
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message || 'Đơn hàng đã được ẩn');
                        // Xoá dòng khỏi giao diện (vì đang ở tab HIỂN THỊ)
                        tr.remove();
                    })
                    .catch(error => {
                        alert(error.message || 'Ẩn đơn hàng thất bại!');
                    });
            }
        });
    </script>
    <script>
        document.querySelectorAll('.payment-status-select').forEach(function(select) {
            select.addEventListener('change', function() {
                const orderId = this.getAttribute('data-order-id');
                const newPaymentStatus = this.value;
                const token = document.querySelector('input[name="_token"]').value;

                fetch('/admin/orders/' + orderId + '/update-payment-status', {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': token,
                            'Accept': 'application/json',
                        },
                        body: JSON.stringify({
                            payment_status: newPaymentStatus
                        }),
                    })
                    .then(response => {
                        if (!response.ok) return response.json().then(err => {
                            throw err;
                        });
                        return response.json();
                    })
                    .then(data => {
                        alert(data.message || 'Cập nhật trạng thái thanh toán thành công');

                        // Cập nhật trạng thái của các option sau khi thay đổi
                        updateSelectOptions(this);
                    })
                    .catch(error => {
                        alert(error.message || 'Lỗi khi cập nhật trạng thái thanh toán');
                        // Reset về giá trị cũ nếu có lỗi
                        this.value = this.getAttribute('data-previous-value');
                    });
            });

            // Lưu giá trị ban đầu để có thể reset nếu có lỗi
            select.setAttribute('data-previous-value', select.value);

            // Khởi tạo trạng thái các option khi tải trang
            updateSelectOptions(select);
        });

        // Hàm cập nhật trạng thái của các option
        function updateSelectOptions(selectElement) {
            const currentValue = selectElement.value;
            const options = selectElement.options;

            // Reset tất cả options về enabled
            for (let i = 0; i < options.length; i++) {
                options[i].disabled = false;
            }

            if (currentValue === 'paid') {
                // Khi chọn 'Đã thanh toán' disable option 'Chưa thanh toán'
                for (let i = 0; i < options.length; i++) {
                    if (options[i].value === 'unpaid') {
                        options[i].disabled = true;
                    }
                }
            }
            // Nếu currentValue là 'unpaid' thì không disable gì cả

            // Lưu lại giá trị hiện tại
            selectElement.setAttribute('data-previous-value', currentValue);
        }
    </script>
    <script>
        document.querySelectorAll('.payment-status-select').forEach(function(select) {
            function updateColor() {
                if (select.value === 'paid') {
                    select.classList.add('payment-paid-bank');
                } else {
                    select.classList.remove('payment-paid-bank');
                }
            }
            updateColor();
            select.addEventListener('change', updateColor);
        });
    </script>
    <script>
        // Hàm cập nhật màu cho payment-status-label dựa vào order status và phương thức thanh toán
        function updatePaymentLabelColor(orderId) {
            const paymentSpan = document.querySelector(`#payment-status-${orderId}`);
            const orderStatusSelect = document.querySelector(`#status-form-${orderId} select[name="status"]`);
            const paymentMethod = paymentSpan?.getAttribute('data-payment-method');

            if (!paymentSpan || !orderStatusSelect) return;

            // Nếu cod và trạng thái đơn hàng là delivered => đổi màu xanh
            if (paymentMethod === 'cod' && orderStatusSelect.value === 'delivered') {
                paymentSpan.classList.add('payment-paid-cod-delivered');
                paymentSpan.classList.remove('payment-unpaid');
                paymentSpan.textContent = 'Đã thanh toán';
            } else {
                // Nếu không thì trả về mặc định (dựa vào class payment-unpaid hoặc payment-paid hiện tại)
                if (!paymentSpan.classList.contains('payment-paid-cod-delivered')) {
                    paymentSpan.classList.remove('payment-paid-cod-delivered');
                }
            }
        }

        // Lắng nghe sự kiện thay đổi trạng thái đơn hàng
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const orderId = this.getAttribute('data-order-id');
                updatePaymentLabelColor(orderId);
            });
        });

        // Khi trang load chạy 1 lần để cập nhật đúng màu
        document.querySelectorAll('.status-select').forEach(select => {
            const orderId = select.getAttribute('data-order-id');
            updatePaymentLabelColor(orderId);
        });
    </script>
</body>
@stack('scripts')

</html>
