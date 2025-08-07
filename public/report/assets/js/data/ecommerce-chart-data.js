// /* ====== Chart ====== */

// (function ($) {
//     "use strict";
//     function newrevenueChart() {
//         var options = {
//             chart: {
//                 height: 365,
//                 type: "line",
//                 stacked: false,
//                 foreColor: "#373d3f",
//                 sparkline: {
//                     enabled: !1,
//                 },
//                 dropShadow: {
//                     enabled: true,
//                     enabledOnSeries: undefined,
//                     top: 5,
//                     left: 5,
//                     blur: 3,
//                     color: "#000",
//                     opacity: 0.1,
//                 },
//                 toolbar: {
//                     show: !1,
//                 },
//             },
//             dataLabels: {
//                 enabled: !1,
//             },
//             series: [
//                 {
//                     name: "Revenue",
//                     data: [25, 65, 42, 52, 14, 32, 54, 12, 24, 63, 24],
//                 },
//                 {
//                     name: "Orders",
//                     data: [15, 65, 45, 44, 65, 85, 23, 74, 53, 65, 75],
//                 },
//                 {
//                     name: "Expence",
//                     data: [12, 31, 40, 39, 54, 74, 20, 48, 50, 25, 43],
//                 },
//             ],
//             plotOptions: {
//                 bar: {
//                     horizontal: false,
//                     columnWidth: "20%",
//                 },
//             },
//             stroke: {
//                 width: [2, 2, 2],
//                 curve: "smooth",
//             },
//             fill: {
//                 opacity: [1, 1, 1],
//                 gradient: {
//                     inverseColors: false,
//                     shade: "light",
//                     type: "vertical",
//                     opacityFrom: 0.45,
//                     opacityTo: 0.05,
//                     stops: [50, 100, 100, 100],
//                 },
//             },
//             colors: ["#3f51b5", "#50d1f8", "#5caf90"],
//             xaxis: {
//                 categories: [
//                     "Jan",
//                     "Feb",
//                     "Mar",
//                     "Apr",
//                     "May",
//                     "Jun",
//                     "Jul",
//                     "Aug",
//                     "Sep",
//                     "Oct",
//                     "Nov",
//                     "Dec",
//                 ],
//                 axisTicks: {
//                     show: !1,
//                 },
//                 axisBorder: {
//                     show: !1,
//                 },
//             },
//             yaxis: {
//                 labels: {
//                     formatter: function (e) {
//                         return e + "k";
//                     },
//                     offsetX: -15,
//                 },
//             },
//             legend: {
//                 show: !0,
//                 horizontalAlign: "center",
//                 offsetX: 0,
//                 offsetY: -5,
//                 markers: {
//                     width: 15,
//                     height: 10,
//                     radius: 6,
//                 },
//                 itemMargin: {
//                     horizontal: 10,
//                     vertical: 0,
//                 },
//             },
//             grid: {
//                 show: !1,
//                 xaxis: {
//                     lines: {
//                         show: !1,
//                     },
//                 },
//                 yaxis: {
//                     lines: {
//                         show: !1,
//                     },
//                 },
//                 padding: {
//                     top: 0,
//                     right: -2,
//                     bottom: 15,
//                     left: 0,
//                 },
//             },
//             responsive: [
//                 {
//                     breakpoint: 480,
//                     options: {
//                         chart: {
//                             height: "250px",
//                         },
//                         yaxis: {
//                             show: false,
//                         },
//                     },
//                 },
//             ],
//         };
//         var newrevenueChart = new ApexCharts(
//             document.querySelector("#newrevenueChart"),
//             options
//         );
//         newrevenueChart.render();
//     }
//     function newcampaignsChart() {
//         var options = {
//             series: [44, 55, 67],
//             chart: {
//                 height: 350,
//                 type: "radialBar",
//             },
//             plotOptions: {
//                 radialBar: {
//                     dataLabels: {
//                         name: {
//                             fontSize: "22px",
//                         },
//                         value: {
//                             fontSize: "16px",
//                         },
//                         total: {
//                             show: true,
//                             label: "Total",
//                             formatter: function (w) {
//                                 return 249;
//                             },
//                         },
//                     },
//                 },
//             },
//             labels: ["Social", "Referral", "Organic"],
//             colors: ["#3f51b5", "#50d1f8", "#5caf90"],
//         };

//         var newcampaignsChart = new ApexCharts(
//             document.querySelector("#newcampaignsChart"),
//             options
//         );
//         newcampaignsChart.render();
//     }

//     jQuery(window).on("load", function () {
//         newrevenueChart();
//         newcampaignsChart();
//     });
// })(jQuery);
