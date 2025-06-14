/*=====================
      Color Picker
==========================*/
var color_picker1 = document.getElementById("colorPick").value;
document.getElementById("colorPick").onchange = function () {
    color_picker1 = this.value;
    document.body.style.setProperty("--theme-color", color_picker1);
    document.body.style.setProperty("--theme-color-rgb", color_picker1);
    localStorage.setItem("theme-color", color_picker1);
};

/*========================
 Dark setting js
 ==========================*/
$("#darkButton").on("click", function () {
    var href = $("#color-link").attr("href");
    $("body").removeClass("light");
    $("body").addClass("dark");
    document
        .getElementById("color-link")
        .setAttribute("href", "/frontend/assets/css/dark.css");
    localStorage.setItem("theme-mode", "dark");
});

$("#lightButton").on("click", function () {
    var href = $("#color-link").attr("href");
    $("body").removeClass("dark");
    $("body").addClass("light");
    document
        .getElementById("color-link")
        .setAttribute("href", "/frontend/assets/css/style.css");
    localStorage.setItem("theme-mode", "light");
});

/*========================
   RTL setting js
   ==========================*/
$(".rtl").on("click", function () {
    if ($("body").hasClass("ltr")) {
        $("html").attr("dir", "rtl");
        $("body").removeClass("ltr");
        $("body").addClass("rtl");
        $("#rtl-link").attr("href", "/frontend/assets/css/vendors/bootstrap.rtl.css");
        localStorage.setItem("theme-direction", "rtl");
    } else {
        $("html").attr("dir", "");
        $("body").removeClass("rtl");
        $("body").addClass("ltr");
        $("#rtl-link").attr("href", "/frontend/assets/css/vendors/bootstrap.css");
        localStorage.setItem("theme-direction", "ltr");
    }
});
