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
    <link rel="stylesheet" href="{{ asset('backend/assets/css/aa.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/assets/css/modal-fix.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet" />
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
    <!-- Typeahead (nếu cần) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.11.1/typeahead.bundle.min.js"></script>
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        /**
         * JS dùng chung cho trang SHOW và INDEX.
         * - Đồng bộ flow chuyển trạng thái, badge, payment, icon action.
         * - Không cần reload/F5.
         */

        (function() {
            const STATUS_TEXT_MAP = {
                pending: "Chờ xác nhận",
                confirmed: "Đã xác nhận",
                processing: "Đang chuẩn bị",
                shipped: "Đã gửi hàng",
                in_transit: "Đang vận chuyển",
                delivered: "Đã giao hàng",
                cancelled: "Đã hủy",
                failed_delivery: "Giao thất bại",
            };

            const ALLOWED_TRANSITIONS = {
                pending: ["confirmed", "cancelled"],
                confirmed: ["processing", "cancelled"],
                processing: ["shipped", "cancelled"],
                shipped: ["in_transit", "cancelled"], // thêm cancelled
                in_transit: ["delivered", "failed_delivery", "cancelled"], // thêm cancelled
                delivered: [],
                cancelled: [],
                failed_delivery: [],
            };


            // Utils
            const $ = (sel, ctx = document) => ctx.querySelector(sel);
            const $$ = (sel, ctx = document) => Array.from(ctx.querySelectorAll(sel));

            // Phát hiện có phải trang show (có badge + select id cố định)
            function getPageContextForSelect(select) {
                const isShowPage = !!document.getElementById("order-status-badge") && select.id ===
                    "order-status-select";

                let orderId = select.getAttribute("data-order-id");
                let updateUrl = "";
                let token = "";
                let statusBadge = null;
                let form = null;

                if (isShowPage) {
                    updateUrl = `/admin/orders/${orderId}/update-status`;
                    token = document.querySelector('meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";
                    statusBadge = document.getElementById("order-status-badge");
                } else {
                    form = document.getElementById("status-form-" + orderId);
                    updateUrl = form ? form.action : "";
                    token = form ? form.querySelector('input[name="_token"]').value : (document.querySelector(
                        'meta[name="csrf-token"]')?.content || "");
                }

                return {
                    isShowPage,
                    orderId,
                    updateUrl,
                    token,
                    statusBadge,
                    form
                };
            }

            // Disable/enable option theo flow cho select hiện tại (theo value hiện tại)
            function applyAllowedOptions(select) {
                const current = select.value;
                const allowed = ALLOWED_TRANSITIONS[current] || [];
                Array.from(select.options).forEach((opt) => {
                    const v = opt.value;
                    if (v === current) {
                        opt.disabled = false;
                    } else {
                        opt.disabled = !allowed.includes(v);
                    }
                });
            }

            // Cập nhật badge trạng thái ở trang show
            function updateShowBadge(statusBadge, newStatus) {
                if (!statusBadge) return;
                statusBadge.textContent = STATUS_TEXT_MAP[newStatus] || newStatus;
                statusBadge.className = "badge status-" + newStatus;
            }

            // Cập nhật cell thanh toán tại trang index sau đổi trạng thái
            function updatePaymentCellAfterStatusChangeOnIndex(form, payloadFromServer) {
                if (!form) return;
                const tr = form.closest("tr");
                if (!tr) return;

                const paymentTd = tr.querySelectorAll("td")[5]; // cột TTTT
                if (!paymentTd) return;

                const data = payloadFromServer || {};
                const paymentMethod = data.payment_method;
                const paymentStatus = data.payment_status;
                const orderStatus = data.status;

                // Nếu bank -> dropdown
                if (paymentMethod === "bank") {
                    paymentTd.innerHTML = `
        <select class="form-select payment-status-select${paymentStatus === "paid" ? " payment-paid-bank" : ""}"
                data-order-id="${data.id || form.getAttribute("data-order-id")}">
          <option value="unpaid"${paymentStatus === "unpaid" ? " selected" : ""}>Chưa thanh...</option>
          <option value="paid"${paymentStatus === "paid" ? " selected" : ""}>Đã thanh...</option>
        </select>
      `;
                    // Không cần gắn listener thủ công, đã có event delegation bên dưới
                } else {
                    // Với COD: delivered => ĐÃ THANH TOÁN
                    let labelClass = "payment-status-label payment-unpaid";
                    let text = "Chưa thanh toán";

                    if (paymentStatus === "failed") {
                        labelClass = "payment-status-label payment-failed";
                        text = "Thanh toán thất bại";
                    } else if (paymentStatus === "paid" || (paymentMethod === "cod" && orderStatus === "delivered")) {
                        labelClass = "payment-status-label payment-paid";
                        text = "Đã thanh toán";
                    }

                    paymentTd.innerHTML = `<span id="payment-status-${data.id || form.getAttribute("data-order-id")}"
                                 class="${labelClass}"
                                 data-payment-method="${paymentMethod}">${text}</span>`;
                }
            }

            // Cập nhật nhóm icon action (mắt, map, hủy) theo trạng thái mới
            function updateActionIconsOnIndex(form, newStatus) {
                if (!form) return;
                const tr = form.closest("tr");
                if (!tr) return;

                const ul = tr.querySelector("td:last-child ul");
                if (!ul) return;

                // Lấy các URL chuẩn từ các anchor hiện có (nếu có)
                const eyeLink = ul.querySelector('a[href*="/orders/"][href$=""]') || ul.querySelector('a i.ri-eye-line')
                    ?.closest("a");
                const mapLink = ul.querySelector('a[href*="/orders/"][href*="/tracking"]') || ul.querySelector(
                    'a i.ri-map-pin-line')?.closest("a");

                // Tái dựng 2 link cơ bản (xem + tracking)
                const baseShowHref =
                    eyeLink?.getAttribute("href") ||
                    (function() {
                        const formAction = form.action; // .../orders/{id}/update-status
                        return formAction.replace("/update-status", "");
                    })();

                const baseTrackingHref =
                    mapLink?.getAttribute("href") ||
                    (function() {
                        const formAction = form.action;
                        return formAction.replace("/update-status", "/tracking");
                    })();

                // Chỉ hiển thị nút hủy khi trạng thái nằm trong pending|confirmed|processing
                const canCancel = ["pending", "confirmed", "processing", "shipped", "in_transit"].includes(newStatus);
                let html = `
      <li><a href="${baseShowHref}"><i class="ri-eye-line"></i></a></li>
      <li><a href="${baseTrackingHref}"><i class="ri-map-pin-line"></i></a></li>
    `;

                if (canCancel) {
                    // Cần data-order-id và data-order-code từ row hiện tại (đã có ở link cũ), nếu không, cố gắng suy luận
                    const orderId = form.getAttribute("data-order-id") || form.getAttribute("action").match(
                        /orders\/(\d+)/)?.[1] || "";
                    const code = tr.querySelector('td:nth-child(3) span')?.getAttribute('title') || ("#" + orderId);

                    html += `
        <li>
          <a href="javascript:void(0);" class="btn-admin-cancel"
             data-order-id="${orderId}"
             data-order-code="${code}"
             data-bs-toggle="modal"
             data-bs-target="#adminCancelModal" title="Hủy đơn">
            <i class="ri-close-circle-line"></i>
          </a>
        </li>
      `;
                }

                ul.innerHTML = html;
            }

            // Cập nhật màu/label thanh toán cho COD khi trạng thái delivered
            function updatePaymentLabelColor(orderId) {
                const paymentSpan = document.querySelector(`#payment-status-${orderId}`);
                const orderStatusSelect = document.querySelector(`#status-form-${orderId} select[name="status"]`);
                const paymentMethod = paymentSpan?.getAttribute("data-payment-method");

                if (!paymentSpan || !orderStatusSelect) return;

                if (paymentMethod === "cod" && orderStatusSelect.value === "delivered") {
                    paymentSpan.classList.add("payment-paid-cod-delivered");
                    paymentSpan.classList.remove("payment-unpaid");
                    paymentSpan.textContent = "Đã thanh toán";
                } else {
                    paymentSpan.classList.remove("payment-paid-cod-delivered");
                }
            }

            // Gửi request cập nhật trạng thái
            async function putJson(url, token, payload) {
                const res = await fetch(url, {
                    method: "PUT",
                    headers: {
                        "Content-Type": "application/json",
                        Accept: "application/json",
                        "X-CSRF-TOKEN": token,
                        "X-Requested-With": "XMLHttpRequest",
                    },
                    body: JSON.stringify(payload || {}),
                });
                let data = {};
                try {
                    data = await res.json();
                } catch (e) {}
                if (!res.ok || (!data.success && !data.message)) {
                    throw new Error(data.message || "Cập nhật thất bại.");
                }
                return data;
            }

            // Lắng nghe đổi trạng thái đơn hàng (cho cả show & index)
            function bindStatusSelect(select) {
                // Khởi tạo state hiện tại để revert nếu lỗi
                if (!select.hasAttribute("data-current-status")) {
                    select.setAttribute("data-current-status", select.value);
                }

                applyAllowedOptions(select);

                select.addEventListener("change", async function() {
                    const prevStatus = this.getAttribute("data-current-status");
                    const newStatus = this.value;

                    const {
                        isShowPage,
                        updateUrl,
                        token,
                        statusBadge,
                        form
                    } = getPageContextForSelect(this);

                    // Chặn flow sai
                    const allowed = ALLOWED_TRANSITIONS[prevStatus] || [];
                    if (!allowed.includes(newStatus)) {
                        this.value = prevStatus;
                        applyAllowedOptions(this);
                        if (window.Swal) Swal.fire("Không hợp lệ",
                            "Không thể chuyển trạng thái theo luồng này.", "warning");
                        return;
                    }

                    // Với show page: hiển thị "Đang cập nhật..." tạm
                    if (isShowPage && statusBadge) {
                        statusBadge.textContent = "Đang cập nhật...";
                        statusBadge.className = "badge status-pending";
                    }

                    // Xây payload
                    const payload = {
                        status: newStatus
                    };

                    // Nếu hủy -> yêu cầu lý do
                    if (newStatus === "cancelled") {
                        if (window.Swal) {
                            const {
                                value,
                                isConfirmed
                            } = await Swal.fire({
                                title: "Hủy đơn hàng",
                                input: "textarea",
                                inputLabel: "Lý do hủy (bắt buộc)",
                                inputPlaceholder: "Nhập lý do hủy...",
                                showCancelButton: true,
                                confirmButtonText: "Xác nhận hủy",
                                cancelButtonText: "Đóng",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                preConfirm: (val) => {
                                    if (!val || !val.trim()) {
                                        Swal.showValidationMessage("Vui lòng nhập lý do hủy");
                                        return false;
                                    }
                                    return val.trim();
                                },
                            });
                            if (!isConfirmed) {
                                this.value = prevStatus;
                                applyAllowedOptions(this);
                                return;
                            }
                            payload.cancel_reason = value.trim();
                        } else {
                            const p = prompt("Nhập lý do hủy (bắt buộc):");
                            if (!p || !p.trim()) {
                                this.value = prevStatus;
                                applyAllowedOptions(this);
                                return;
                            }
                            payload.cancel_reason = p.trim();
                        }
                    }

                    try {
                        const data = await putJson(updateUrl, token, payload);

                        // SHOW: cập nhật badge + thông báo
                        if (isShowPage) {
                            updateShowBadge(statusBadge, newStatus);

                            // ✅ Cập nhật chính select trên trang show
                            const prev = this.getAttribute("data-current-status");
                            this.setAttribute("data-current-status", newStatus);
                            if (prev) this.classList.remove("status-" + prev);
                            this.classList.add("status-" + newStatus);
                            applyAllowedOptions(this);

                            if (window.Swal) {
                                Swal.fire("Thành công", data.message || "Cập nhật trạng thái thành công",
                                    "success");
                            }
                        }

                        // INDEX: cập nhật class, data-current-status, payment cell, action icons
                        if (!isShowPage && form) {
                            // cập nhật select class
                            const prev = this.getAttribute("data-current-status");
                            this.setAttribute("data-current-status", newStatus);
                            if (prev) this.classList.remove("status-" + prev);
                            this.classList.add("status-" + newStatus);

                            // Chuẩn hóa object data để helper dùng
                            const enrich = {
                                id: this.getAttribute("data-order-id"),
                                status: newStatus,
                                payment_method: data.payment_method || $("td:nth-child(5)", form
                                    .closest("tr"))?.textContent?.trim() || "",
                                payment_status: data.payment_status || (function() {
                                    // nếu không trả về, cố suy đoán từ DOM (không bắt buộc)
                                    const td = form.closest("tr").querySelectorAll("td")[5];
                                    if (!td) return "";
                                    const span = td.querySelector(".payment-status-label");
                                    if (span?.classList.contains("payment-paid")) return "paid";
                                    if (span?.classList.contains("payment-failed"))
                                        return "failed";
                                    return "unpaid";
                                })(),
                            };

                            updatePaymentCellAfterStatusChangeOnIndex(form, enrich);
                            updateActionIconsOnIndex(form, newStatus);

                            // Cập nhật màu cho COD khi delivered
                            updatePaymentLabelColor(enrich.id);

                            if (window.Swal) Swal.fire("Thành công", data.message ||
                                "Cập nhật trạng thái thành công", "success");
                        }

                        applyAllowedOptions(this);
                    } catch (err) {
                        // Revert khi lỗi
                        this.value = prevStatus;
                        applyAllowedOptions(this);
                        if (isShowPage && statusBadge) {
                            statusBadge.textContent = "Lỗi!";
                            statusBadge.className = "badge status-cancelled";
                        }
                        if (window.Swal) Swal.fire("Lỗi", err.message || "Không thể cập nhật trạng thái!",
                            "error");
                        else alert(err.message || "Không thể cập nhật trạng thái!");
                    }
                });
            }

            // Event delegation: cập nhật payment status cho BANK (index)
            document.addEventListener("change", async function(e) {
                const select = e.target.closest(".payment-status-select");
                if (!select) return;

                const orderId = select.getAttribute("data-order-id");
                const newPaymentStatus = select.value;
                // Lấy token: ưu tiên hidden _token trong row, fallback meta
                const token =
                    select
                    .closest("tr")
                    ?.querySelector('input[name="_token"]')
                    ?.value || document.querySelector('meta[name="csrf-token"]')?.content ||
                    "{{ csrf_token() }}";

                try {
                    const data = await putJson(`/admin/orders/${orderId}/update-payment-status`, token, {
                        payment_status: newPaymentStatus,
                    });

                    if (window.Swal) Swal.fire("Thành công", data.message ||
                        "Cập nhật trạng thái thanh toán thành công", "success");
                    else alert(data.message || "Cập nhật trạng thái thanh toán thành công");

                    // Disable 'unpaid' nếu đang 'paid'
                    updateBankSelectOptions(select);
                    // Cập nhật màu cho bank select
                    updateBankSelectColor(select);
                } catch (error) {
                    if (window.Swal) Swal.fire("Lỗi", error.message ||
                        "Lỗi khi cập nhật trạng thái thanh toán", "error");
                    else alert(error.message || "Lỗi khi cập nhật trạng thái thanh toán");
                    // Reset về giá trị cũ
                    select.value = select.getAttribute("data-previous-value");
                    updateBankSelectOptions(select);
                    updateBankSelectColor(select);
                }
            });

            function updateBankSelectOptions(select) {
                const currentValue = select.value;
                const options = select.options;
                for (let i = 0; i < options.length; i++) options[i].disabled = false;
                if (currentValue === "paid") {
                    for (let i = 0; i < options.length; i++) {
                        if (options[i].value === "unpaid") options[i].disabled = true;
                    }
                }
                select.setAttribute("data-previous-value", currentValue);
            }

            function updateBankSelectColor(select) {
                if (select.value === "paid") {
                    select.classList.add("payment-paid-bank");
                } else {
                    select.classList.remove("payment-paid-bank");
                }
            }

            // INIT
            document.addEventListener("DOMContentLoaded", function() {
                // Gắn xử lý cho tất cả select trạng thái
                $$(".status-select").forEach((sel) => bindStatusSelect(sel));

                // Khởi tạo các payment-status-select hiện có (bank)
                $$(".payment-status-select").forEach((sel) => {
                    if (!sel.hasAttribute("data-previous-value")) sel.setAttribute(
                        "data-previous-value", sel.value);
                    updateBankSelectOptions(sel);
                    updateBankSelectColor(sel);
                });

                // Cập nhật màu COD khi delivered (chạy 1 lần khi load)
                $$(".status-select").forEach((select) => {
                    const orderId = select.getAttribute("data-order-id");
                    updatePaymentLabelColor(orderId);
                });
            });

            // Form hide (PATCH) bằng delegation
            document.addEventListener("submit", function(e) {
                const form = e.target;
                if (!form.matches('form[action*="hide"]')) return;

                e.preventDefault();

                const token = form.querySelector('input[name="_token"]')?.value || document.querySelector(
                    'meta[name="csrf-token"]')?.content || "{{ csrf_token() }}";
                const action = form.action;
                const tr = form.closest("tr");

                fetch(action, {
                        method: "PATCH",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": token,
                            Accept: "application/json",
                        },
                        body: JSON.stringify({}),
                    })
                    .then((response) => {
                        if (!response.ok) return response.json().then((err) => {
                            throw err;
                        });
                        return response.json();
                    })
                    .then((data) => {
                        if (window.Swal) Swal.fire("Thành công", data.message || "Đơn hàng đã được ẩn",
                            "success");
                        else alert(data.message || "Đơn hàng đã được ẩn");
                        if (tr) tr.remove();
                    })
                    .catch((error) => {
                        if (window.Swal) Swal.fire("Lỗi", error.message || "Ẩn đơn hàng thất bại!",
                            "error");
                        else alert(error.message || "Ẩn đơn hàng thất bại!");
                    });
            });

            // Modal "Hủy đơn" (adminCancelModal): set action + code
            document.addEventListener("click", function(e) {
                const btn = e.target.closest(".btn-admin-cancel");
                if (!btn) return;

                const modalEl = document.getElementById("adminCancelModal");
                const form = document.getElementById("admin-cancel-form");
                const codeEl = document.getElementById("cancel-order-code");

                const id = btn.getAttribute("data-order-id");
                const code = btn.getAttribute("data-order-code") || ("#" + id);

                // ĐỔI ACTION sang update-status để controller updateStatus xử lý và lưu cancel_reason
                form.action = `/admin/orders/${id}/update-status`;
                codeEl.textContent = code;
            });
        })();
    </script>

    <!-- Modal Hủy Đơn: giữ nguyên cấu trúc, chỉ sửa JS binding ở trên -->
    <div class="modal fade" id="adminCancelModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <form id="admin-cancel-form" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Hủy đơn hàng</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                    </div>

                    <div class="modal-body">
                        <p class="mb-2">
                            Bạn chắc chắn muốn hủy đơn: <strong id="cancel-order-code">#</strong>?
                        </p>

                        <!-- gửi đúng tên field theo controller -->
                        <input type="hidden" name="status" value="cancelled">

                        <label class="form-label">Lý do (bắt buộc)</label>
                        <textarea name="cancel_reason" class="form-control" rows="3" placeholder="Ghi chú lý do hủy..."></textarea>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-danger">Xác nhận hủy</button>
                    </div>
                </div>
            </form>
        </div>
    </div>


    @stack('scripts')

</html>
