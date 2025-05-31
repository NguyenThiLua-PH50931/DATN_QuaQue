// Kiểm tra và áp dụng trạng thái giao diện tối từ localStorage
if (localStorage.getItem("dark")) {
    $("body").addClass("dark");
}

// Kiểm tra và áp dụng màu từ localStorage
if (localStorage.getItem("color")) {
    $("#color").attr("href", "assets/css/" + localStorage.getItem("color") + ".css");
}

// Apply RTL state from localStorage immediately
const isRtl = localStorage.getItem("rtl");
if (isRtl) {
    $("body").addClass("rtl");
    $("html").attr("dir", "rtl");
} else {
    $("body").removeClass("rtl");
    $("html").attr("dir", "");
}

// Live customizer js
$(document).ready(function () {
    // Kiểm tra xem nút đã tồn tại chưa, nếu chưa thì thêm
    if ($('.customizer-links').length === 0) {
        const isRtl = localStorage.getItem("rtl");
        const buttonText = isRtl ? "LTR" : "RTL";
        const buttonClass = isRtl ? "rtl-btn rtl" : "rtl-btn";
        $('<div class="customizer-links"><button class="' + buttonClass + '">' + buttonText + '</button></div>').appendTo($('body'));

        // Áp dụng trạng thái RTL từ localStorage
        if (isRtl) {
            $("body").addClass("rtl");
            $("html").attr("dir", "rtl");
        } else {
            $("body").removeClass("rtl");
            $("html").attr("dir", "");
        }
    }

    // Gắn sự kiện click cho nút RTL/LTR
    $('.rtl-btn').on('click', function () {
        $(this).toggleClass('rtl');
        if ($(this).hasClass('rtl')) {
            $(this).text('LTR');
            $('body').addClass('rtl');
            $("html").attr("dir", "rtl");
            localStorage.setItem("rtl", "true");
        } else {
            $(this).text('RTL');
            $('body').removeClass('rtl');
            $("html").attr("dir", "");
            localStorage.removeItem("rtl");
        }
    });
});
