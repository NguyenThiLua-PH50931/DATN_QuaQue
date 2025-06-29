-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th6 27, 2025 lúc 02:23 AM
-- Phiên bản máy phục vụ: 8.0.30
-- Phiên bản PHP: 8.2.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `datn_quaque`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `recipient_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `province` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `district` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `recipient_name`, `phone`, `address`, `province`, `district`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Nguyễn Văn A', '0901234567', '123 Đường Lê Lợi, Phường Phú Hội', 'Thừa Thiên Huế', 'TP. Huế', 1, '2025-06-14 00:57:04', '2025-06-14 00:57:04'),
(2, 2, 'Trần Thị B', '0912345678', '456 Đường Trần Phú, Phường Bình Thuận', 'Đà Nẵng', 'Hải Châu', 1, '2025-06-14 00:57:04', '2025-06-14 00:57:04'),
(3, 1, 'Nguyễn Văn A', '0901234567', '123 Đường Lê Lợi, Phường Phú Hội', 'Thừa Thiên Huế', 'TP. Huế', 1, '2025-06-23 01:58:23', '2025-06-23 01:58:23'),
(4, 2, 'Trần Thị B', '0912345678', '456 Đường Trần Phú, Phường Bình Thuận', 'Đà Nẵng', 'Hải Châu', 1, '2025-06-23 01:58:23', '2025-06-23 01:58:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `attributes`
--

INSERT INTO `attributes` (`id`, `name`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Chục', 'chuc', '2025-06-14 21:49:45', '2025-06-14 21:49:45', NULL),
(2, 'ưqe', 'uqe', '2025-06-15 01:30:09', '2025-06-15 01:30:09', NULL),
(3, 'sdfs', 'sdfs', '2025-06-15 19:15:12', '2025-06-15 19:17:38', '2025-06-15 19:17:38'),
(6, 'Cái', 'cai', '2025-06-24 07:02:40', '2025-06-24 07:02:40', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attribute_values`
--

CREATE TABLE `attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `value` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `attribute_values`
--

INSERT INTO `attribute_values` (`id`, `attribute_id`, `value`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '1', '1', '2025-06-14 21:49:45', '2025-06-14 21:49:45', NULL),
(2, 1, '0', '0', '2025-06-14 21:49:45', '2025-06-14 21:49:45', NULL),
(3, 2, 'ưer', 'uer', '2025-06-15 01:30:09', '2025-06-15 01:30:09', NULL),
(4, 2, '1', '1', '2025-06-15 01:30:20', '2025-06-15 01:30:20', NULL),
(5, 3, 'f', 'f', '2025-06-15 19:15:12', '2025-06-15 19:17:38', '2025-06-15 19:17:38'),
(6, 3, 's', 's', '2025-06-15 19:15:12', '2025-06-15 19:17:38', '2025-06-15 19:17:38'),
(7, 3, '1', '1', '2025-06-15 19:15:12', '2025-06-15 19:17:38', '2025-06-15 19:17:38'),
(10, 6, '10', '10', '2025-06-24 07:02:40', '2025-06-24 07:02:40', NULL),
(11, 6, '15', '15', '2025-06-24 07:02:48', '2025-06-24 07:02:48', NULL),
(12, 6, '5', '5', '2025-06-24 07:02:53', '2025-06-24 07:02:53', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `banners`
--

CREATE TABLE `banners` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `display_at` datetime DEFAULT NULL,
  `display_end_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `banners`
--

INSERT INTO `banners` (`id`, `title`, `image`, `location`, `link`, `active`, `display_at`, `display_end_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, 'ha', 'dsd', NULL, 'http://127.0.0.1:8000/admin/banners', 1, '3333-12-23 00:00:00', NULL, '2025-06-06 02:45:35', '2025-06-09 21:07:00', '2025-06-09 21:07:00'),
(5, 'Ha', 'download (4).jpg', NULL, 'http://127.0.0.1:8000/admin/banners/create', 1, '2025-06-17 00:00:00', NULL, '2025-06-08 00:45:02', '2025-06-09 21:07:00', '2025-06-09 21:07:00'),
(7, 'Ha', 'download (4).jpg', NULL, 'http://127.0.0.1:8000/admin/banners/create', 1, '2025-06-17 00:00:00', NULL, '2025-06-08 00:47:49', '2025-06-09 21:07:00', '2025-06-09 21:07:00'),
(10, '<h4>Ưu đãi độc quyền giảm<font color=\"#ff0000\"> giá 30%\r\n</font></h4><h2>Ở nhà và nhận nhu cầu hàng ngày của bạn\r\n</h2><h3>Rau chứa nhiều vitamin và khoáng chất tốt cho sức khỏe của bạn. </h3>', 'banners/nZaprD8peoFEiNeRyWod5pwY9DkicQXj7aaARGfg.jpg', 'main_hero_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-10 00:00:00', '2025-07-12 23:59:59', '2025-06-09 21:35:33', '2025-06-18 00:49:10', NULL),
(11, 'Banner phụ 1', 'banners/KrdZVs4i9gCCdjEVtDRo2rCQIlHmnAqnU2xNfANY.jpg', 'small_promo_banner_top', 'http://127.0.0.1:8000/client/home', 1, '2025-06-06 00:00:00', '2025-07-12 23:59:59', '2025-06-09 21:43:38', '2025-06-11 19:59:11', NULL),
(12, 'Banner silde', 'banners/lxDXa6Ci0x1bCuthmCZNYZ4cdrXVBZyVBRfR8ytf.jpg', 'new_products_promo_right', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 20:53:06', '2025-06-11 19:59:21', NULL),
(13, 'Banner trượt 1', 'banners/w4TVyyyNMqPcOuTRrquxaqyN3rR75Hv6M4GplHYf.jpg', 'slider_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 21:07:58', '2025-06-11 19:59:33', NULL),
(14, 'Banner trượt 2', 'banners/GqI2xRVVumbTcevIfTCsMGNB6Yjyoy8uqLhYIXCA.jpg', 'slider_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 21:08:58', '2025-06-11 19:59:44', NULL),
(15, 'Banner trượt 3', 'banners/vOlhY5y55edNg36liDRHbecWFiqoHYQFIr5XXBjm.png', 'slider_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 21:09:52', '2025-06-11 19:59:55', NULL),
(16, 'Banner chính 3', 'banners/FwHes8kEkQpYPKDPJLbpUtlF31dEHFKPjXiEe22j.jpg', 'slider_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 21:17:29', '2025-06-11 20:00:05', NULL),
(17, 'Banner đầu', 'banners/2nStGxoUwp0H4C0KXKzoTcgACKSsOGFHvU2I3kSy.webp', 'small_promo_banner_bottom', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-10 23:58:39', '2025-06-11 20:00:15', NULL),
(18, 'Banner dọc', 'banners/gCbEYrabQ7XOBtH9YeMWtbuW86oqla1gTRaYqH2M.jpg', 'product_section_promo_left_top', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:01:05', '2025-06-11 20:00:24', NULL),
(19, 'Banner dọc dưới', 'banners/bGBWWvCyE32HqTnmSqA8No7kTg21rnn2VOZmFXf3.jpg', 'product_section_promo_left_bottom', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-04 23:59:59', '2025-06-11 00:01:58', '2025-06-17 19:00:27', NULL),
(20, 'Banner danh mục', 'banners/DcWYwB0oFw4wziS77YJpeIxQ7qGjOg4NeAmwGKss.gif', 'category_section_promo_left', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:02:49', '2025-06-15 00:39:26', NULL),
(21, 'Banner danh mục', 'banners/Wr5n0cV5NPL3jDxF04fa9eLljPdg5eCg6w7ACuVZ.webp', 'category_section_promo_right', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:03:33', '2025-06-15 00:39:45', NULL),
(22, 'Banner sp mới', 'banners/fxDV59VgBNiYEWZz8brIw2KVXX4LgPBBvKLQ8YYr.jpg', 'new_products_cashback_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:05:47', '2025-06-15 00:40:11', NULL),
(23, 'banner sp mới', 'banners/bfDfWixolN7w6McftVY28x48od28xQZVBzaIxwoO.jpg', 'new_products_promo_left', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:06:28', '2025-06-15 00:40:25', NULL),
(24, 'Banner cuối', 'banners/A35LQM073vsc1zF11aGe3sfiafz2FYBd4ojJL4K6.jpg', 'last_page_promo_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-11 00:00:00', '2025-07-12 23:59:59', '2025-06-11 00:16:49', '2025-06-19 02:37:24', NULL),
(25, 'Banner chính', 'banners/oHTzOsmj3wgUJMRa0oA6B1ygcTfH1YvlW3nQvNt9.jpg', 'main_hero_banner', 'http://127.0.0.1:8000/client/home', 0, '2025-06-12 00:00:00', '2025-06-14 00:00:00', '2025-06-11 19:07:28', '2025-06-11 19:23:22', '2025-06-11 19:23:22'),
(26, 'háhajs', 'banners/Bg43loa7yLDZ5iw5nU03Mz9wSNovXCmNXqnomInX.webp', 'main_hero_banner', 'http://127.0.0.1:8000/client/home', 1, '2025-06-02 00:00:00', '2025-06-05 23:59:59', '2025-06-11 20:38:50', '2025-06-11 20:39:02', '2025-06-11 20:39:02');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `blogs`
--

CREATE TABLE `blogs` (
  `id` bigint UNSIGNED NOT NULL,
  `title` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `blogs`
--

INSERT INTO `blogs` (`id`, `title`, `slug`, `content`, `thumbnail`, `start_date`, `end_date`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Giới thiệu Laravel cơ bản', 'gioi-thieu-laravel-co-ban', 'Đây là nội dung chi tiết của bài viết: Giới thiệu Laravel cơ bản.', 'https://picsum.photos/seed/blog0/600/400', NULL, NULL, '2025-06-14 00:57:04', '2025-06-15 20:12:13', '2025-06-15 20:12:13'),
(2, 'Cách tạo REST API với Laravel', 'cach-tao-rest-api-voi-laravel', '<p>Đây là nội dung chi tiết của bài viết: Cách tạo REST API với Laravel.</p>', 'uploads/blogs/1750232446_464-600x400.jpg', '2025-06-06', '2025-07-12', '2025-06-14 00:57:04', '2025-06-18 00:40:46', NULL),
(3, 'Hướng dẫn xác thực người dùng trong Laravel', 'huong-dan-xac-thuc-nguoi-dung-trong-laravel', '<p>Đây là nội dung chi tiết của bài viết: Hướng dẫn xác thực người dùng trong Laravel.</p>', 'uploads/blogs/1750232431_29-600x400.jpg', '2025-06-18', '2025-07-05', '2025-06-14 00:57:04', '2025-06-18 00:40:31', NULL),
(4, 'Tối ưu hiệu năng ứng dụng Laravel', 'toi-uu-hieu-nang-ung-dung-laravel', '<p>Đây là nội dung chi tiết của bài viết: Tối ưu hiệu năng ứng dụng Laravel.</p>', 'uploads/blogs/1750232419_212-600x400.jpg', '2025-06-18', '2025-07-12', '2025-06-14 00:57:04', '2025-06-18 00:40:19', NULL),
(5, 'Triển khai Laravel trên server thật', 'trien-khai-laravel-tren-server-that', '<p>Đây là nội dung chi tiết của bài viết: Triển khai Laravel trên server thật.</p>', 'uploads/blogs/1750231953_841-600x400.jpg', '2025-06-12', '2025-06-28', '2025-06-14 00:57:04', '2025-06-18 00:32:34', NULL),
(6, 'd', 'd', '<p>s</p>', 'uploads/blogs/1750043723_download (2).jpg', '2025-06-16', '2025-07-12', '2025-06-15 20:15:23', '2025-06-16 23:44:31', '2025-06-16 23:44:31'),
(7, 'Giới thiệu Laravel cơ bản', 'gioi-thieu-laravel-co-ban', 'Đây là nội dung chi tiết của bài viết: Giới thiệu Laravel cơ bản.', 'https://picsum.photos/seed/blog0/600/400', NULL, NULL, '2025-06-23 01:58:24', '2025-06-23 01:58:24', NULL),
(8, 'Cách tạo REST API với Laravel', 'cach-tao-rest-api-voi-laravel', 'Đây là nội dung chi tiết của bài viết: Cách tạo REST API với Laravel.', 'https://picsum.photos/seed/blog1/600/400', NULL, NULL, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(9, 'Hướng dẫn xác thực người dùng trong Laravel', 'huong-dan-xac-thuc-nguoi-dung-trong-laravel', 'Đây là nội dung chi tiết của bài viết: Hướng dẫn xác thực người dùng trong Laravel.', 'https://picsum.photos/seed/blog2/600/400', NULL, NULL, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(10, 'Tối ưu hiệu năng ứng dụng Laravel', 'toi-uu-hieu-nang-ung-dung-laravel', 'Đây là nội dung chi tiết của bài viết: Tối ưu hiệu năng ứng dụng Laravel.', 'https://picsum.photos/seed/blog3/600/400', NULL, NULL, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(11, 'Triển khai Laravel trên server thật', 'trien-khai-laravel-tren-server-that', 'Đây là nội dung chi tiết của bài viết: Triển khai Laravel trên server thật.', 'https://picsum.photos/seed/blog4/600/400', NULL, NULL, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `carts`
--

CREATE TABLE `carts` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `quantity` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `image`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Đặc sản vùng miền', 'dac-san-vung-mien', 'categories/G90vLDcu0t9lgzHejcX6ppeOsGCANjvFDhoN6WtF.png', '2025-06-23 01:58:24', '2025-06-23 06:55:30', NULL),
(2, 'Thực phẩm khô', 'thuc-pham-kho', 'categories/Gj3701oLo36uG2h3FcASnhlpoXt3tjHPJ6J1uo1P.png', '2025-06-23 01:58:24', '2025-06-23 07:09:36', NULL),
(3, 'Đồ uống truyền thống', 'do-uong-truyen-thong', 'categories/NnXQdchAgeI57FQ1EXhB0ZIin6FqejONreBl7Ww5.png', '2025-06-23 01:58:24', '2025-06-23 07:09:47', NULL),
(4, 'Mứt và kẹo', 'mut-va-keo', 'categories/RpyRwwE0dFYHSETK3EOLurMR0eZ9QV03OfaXdgjW.png', '2025-06-23 01:58:24', '2025-06-23 07:10:03', NULL),
(5, 'Gia vị', 'gia-vi', 'categories/f7lwvKHkRA8qw0724nWTwOH7OQ98GuI2GhkQIth2.png', '2025-06-23 01:58:24', '2025-06-23 07:15:30', NULL),
(6, 'Thủ công mỹ nghệ', 'thu-cong-my-nghe', 'categories/ioJjdSoMOIm0oKUCzM66hOueenNyFtznYhSKXR1i.png', '2025-06-23 01:58:24', '2025-06-24 03:00:43', NULL),
(7, 'Quà biếu và tặng', 'qua-bieu-va-tang', 'categories/QVePXkiwvJMFGnge3yUZctMlnfdc6l5AqPhA6FfP.png', '2025-06-23 01:58:24', '2025-06-23 07:26:16', NULL),
(8, 'Hàng lưu niệm', 'hang-luu-niem', 'categories/YLuQ6lzMiTbWM0jwOf9AjsqW5jiYkRKQ0z8DKaF5.png', '2025-06-23 01:58:24', '2025-06-23 07:30:43', NULL),
(10, 'aa', 'aa', NULL, '2025-06-23 05:04:48', '2025-06-23 05:04:54', '2025-06-23 05:04:54');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comments`
--

CREATE TABLE `comments` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('visible','hidden') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'visible',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `comments`
--

INSERT INTO `comments` (`id`, `user_id`, `product_id`, `content`, `status`, `created_at`, `updated_at`) VALUES
(3, 2, 1, 'Sản phẩm rất ngon, giao hàng nhanh!', 'visible', '2025-06-23 01:58:24', '2025-06-23 01:58:24'),
(4, 3, 2, 'Trà ngon, nhưng giá hơi cao.', 'visible', '2025-06-23 01:58:24', '2025-06-23 01:58:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `comment_replies`
--

CREATE TABLE `comment_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `comment_id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coupon_product`
--

CREATE TABLE `coupon_product` (
  `id` bigint UNSIGNED NOT NULL,
  `coupon_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `discount_codes`
--

CREATE TABLE `discount_codes` (
  `id` bigint UNSIGNED NOT NULL,
  `code` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `discount_type` enum('percent','fixed') COLLATE utf8mb4_unicode_ci NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `max_discount_amount` decimal(10,2) DEFAULT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `usage_limit` int NOT NULL DEFAULT '1',
  `used_count` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `discount_codes`
--

INSERT INTO `discount_codes` (`id`, `code`, `description`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `start_date`, `end_date`, `usage_limit`, `used_count`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(11, 'RBWM6J9I', 'Giảm giá 5% cho đơn hàng', 'percent', 5.00, 100000.00, 50000.00, '2025-06-22 08:58:25', '2025-07-23 08:58:25', 100, 0, 1, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(12, 'BDP6DZQ9', 'Giảm giá 10% cho đơn hàng', 'percent', 10.00, 100000.00, 50000.00, '2025-06-22 08:58:25', '2025-07-23 08:58:25', 100, 0, 1, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(13, 'K90JQP6X', 'Giảm giá 15% cho đơn hàng', 'percent', 15.00, 100000.00, 50000.00, '2025-06-22 08:58:25', '2025-07-23 08:58:25', 100, 0, 1, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(14, 'SPHXNLVQ', 'Giảm giá 20% cho đơn hàng', 'percent', 20.00, 100000.00, 50000.00, '2025-06-22 08:58:25', '2025-07-23 08:58:25', 100, 0, 1, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL),
(15, '5CXE4JJH', 'Giảm giá 25% cho đơn hàng', 'percent', 25.00, 100000.00, 50000.00, '2025-06-22 08:58:25', '2025-07-23 08:58:25', 100, 0, 1, '2025-06-23 01:58:25', '2025-06-23 01:58:25', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint UNSIGNED NOT NULL,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint UNSIGNED NOT NULL,
  `reserved_at` int UNSIGNED DEFAULT NULL,
  `available_at` int UNSIGNED NOT NULL,
  `created_at` int UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_05_21_083303_update_users_table', 1),
(5, '2025_05_21_084607_create_addresses_table', 1),
(6, '2025_05_21_085226_create_categories_table', 1),
(7, '2025_05_21_085351_create_regions_table', 1),
(8, '2025_05_21_085450_create_products_table', 1),
(9, '2025_05_21_085850_create_product_images_table', 1),
(10, '2025_05_21_151455_create_discount_codes_table', 1),
(11, '2025_05_21_151456_create_shipping_methods_table', 1),
(12, '2025_05_21_152915_create_blogs_table', 1),
(13, '2025_05_21_153034_create_reviews_table', 1),
(14, '2025_05_21_153225_create_favorites_table', 1),
(15, '2025_05_22_011704_create_wallets_table', 1),
(16, '2025_05_22_011945_create_wallet_transactions_table', 1),
(17, '2025_05_22_012241_create_support_requests_table', 1),
(18, '2025_05_22_012453_create_banners_table', 1),
(19, '2025_05_22_012609_create_search_history_table', 1),
(20, '2025_05_22_013117_create_search_suggestions_table', 1),
(21, '2025_05_22_013802_create_carts_table', 1),
(22, '2025_05_22_014805_create_cart_items_table', 1),
(23, '2025_05_22_134159_add_view_columns_to_products_table', 1),
(24, '2025_05_23_071500_add_image_to_categories_table', 1),
(25, '2025_05_24_153529_fix_role_enum_in_users_table', 1),
(26, '2025_05_24_154506_create_product_variants_table', 1),
(27, '2025_05_28_111750_create_comments_table', 1),
(28, '2025_05_28_142300_drop_seller_id_from_products_table', 1),
(29, '2025_05_28_142330_create_variants_table', 1),
(30, '2025_05_28_143345_update_products_table_for_variants', 1),
(31, '2025_05_28_143718_add_fields_to_variants_table', 1),
(32, '2025_05_29_032813_create_orders_table', 1),
(33, '2025_05_29_032814_create_order_items_table', 1),
(34, '2025_05_29_085423_add_order_code_to_orders_table', 1),
(35, '2025_05_29_152053_add_status_to_users_table', 1),
(36, '2025_05_30_020915_add_deleted_at_to_categories_table', 1),
(37, '2025_05_30_051632_create_comment_replies_table', 1),
(38, '2025_05_30_051634_add_status_to_comments_table', 1),
(39, '2025_05_30_073315_update_status_column_in_orders_table', 1),
(40, '2025_05_30_080903_add_deleted_at_to_regions_table', 1),
(41, '2025_05_30_090212_add_deleted_at_to_users_table', 1),
(42, '2025_06_01_000000_add_softdeletes_and_displayat_to_banners_table', 1),
(43, '2025_06_04_082156_create_attributes_table', 1),
(44, '2025_06_04_082228_create_attribute_values_table', 1),
(45, '2025_06_04_082240_update_variants_table_for_attributes', 1),
(46, '2025_06_06_125919_add_deleted_at_to_discount_codes_table', 1),
(47, '2025_06_06_161409_create_support_tickets_table', 1),
(48, '2025_06_06_161419_create_support_ticket_replies_table', 1),
(49, '2025_06_07_151804_create_coupon_product_table', 1),
(50, '2025_06_08_054438_add_payment_status_to_orders_table', 1),
(51, '2025_06_09_091739_add_display_end_at_to_banners_table', 1),
(52, '2025_06_09_142633_create_product_variant_attribute_values_table', 1),
(53, '2025_06_10_091734_add_location_to_banners_table', 1),
(54, '2025_06_13_075239_add_missing_fields_to_product_variants_table', 1),
(55, '2025_06_13_075456_add_image_to_product_variants_table', 1),
(56, '2025_06_13_081009_drop_attribute_value_id_from_product_variants_table', 1),
(57, '2025_06_14_092003_add_deleted_at_to_products_table', 2),
(58, '2025_06_15_075424_add_deleted_at_to_product_variants_table', 3),
(59, '2025_06_15_083555_add_deleted_at_to_attributes_table', 4),
(60, '2025_06_16_021618_add_deleted_at_to_attribute_values_table', 5),
(61, '2025_06_14_132358_add_start_end_time_and_deleted_at_to_blogs_table', 6),
(62, '2025_06_16_164128_add_deleted_at_to_orders_table', 7),
(63, '2025_06_17_024838_add_is_hidden_to_orders_table', 7),
(64, '2025_06_17_053645_update_order_items_with_variant_relation', 8),
(65, '2025_06_17_055325_add_product_name_to_order_items_table', 8),
(66, '2025_06_20_173502_create_wishlist_table', 8),
(67, '2025_06_20_044230_create_review_replies_table', 9),
(68, '2025_06_20_095700_remove_discount_note_status_from_order_items_table', 9),
(69, '2025_06_20_100300_add_user_id_to_comment_replies_table', 9),
(70, '2025_06_20_101752_remove_receiver_columns_from_orders_table', 9),
(71, '2025_06_20_103807_add_discount_amount_to_orders_table', 9),
(72, '2025_06_20_125016_create_order_status_logs_table', 9),
(73, '2025_06_26_091356_add_has_variants_to_products_table', 10);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` bigint UNSIGNED NOT NULL,
  `order_code` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `address_id` bigint UNSIGNED NOT NULL,
  `shipping_method_id` bigint UNSIGNED NOT NULL,
  `discount_code_id` bigint UNSIGNED DEFAULT NULL,
  `discount_amount` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_amount` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL,
  `status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `is_hidden` tinyint(1) NOT NULL DEFAULT '0',
  `payment_method` enum('cod','bank','wallet') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'cod',
  `payment_status` enum('unpaid','paid','refunded') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_variant_value_id` bigint UNSIGNED DEFAULT NULL,
  `product_variant_value_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantity` int NOT NULL DEFAULT '1',
  `price` decimal(10,2) NOT NULL,
  `total` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `order_status_logs`
--

CREATE TABLE `order_status_logs` (
  `id` bigint UNSIGNED NOT NULL,
  `order_id` bigint UNSIGNED NOT NULL,
  `from_status` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `to_status` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `changed_at` timestamp NOT NULL DEFAULT '2025-06-23 01:57:46',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `region_id` bigint UNSIGNED NOT NULL,
  `name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `origin` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `view_total` int UNSIGNED NOT NULL DEFAULT '0',
  `view_day` int UNSIGNED NOT NULL DEFAULT '0',
  `view_week` int UNSIGNED NOT NULL DEFAULT '0',
  `view_month` int UNSIGNED NOT NULL DEFAULT '0',
  `deleted_at` timestamp NULL DEFAULT NULL,
  `has_variants` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `category_id`, `region_id`, `name`, `slug`, `description`, `image`, `origin`, `active`, `created_at`, `updated_at`, `view_total`, `view_day`, `view_week`, `view_month`, `deleted_at`, `has_variants`) VALUES
(1, 2, 1, 'Đặc sản mắm tôm Hạ Long', 'dac-san-mam-tom-ha-long', 'Mắm tôm truyền thống ngon tuyệt hảo của vùng biển Hạ Long.', 'products/mam-tom-ha-long.jpg', 'Quảng Ninh', 1, '2025-06-23 01:58:24', '2025-06-23 02:11:18', 200, 15, 60, 180, '2025-06-23 02:11:18', 1),
(2, 3, 3, 'Trà Shan Tuyết cổ thụ Tuyên Quang', 'tra-shan-tuyet-co-thu-tuyen-quang', 'Trà Shan Tuyết được hái từ những cây trà cổ thụ hàng trăm tuổi.', 'products/tra-shan-tuyet.jpg', 'Tuyên Quang', 1, '2025-06-23 01:58:24', '2025-06-23 02:11:18', 200, 15, 60, 180, '2025-06-23 02:11:18', 1),
(3, 1, 2, 'Bánh đậu xanh Hải Dương', 'banh-dau-xanh-hai-duong', 'Bánh đậu xanh mềm mịn, thơm ngon đặc trưng Hải Dương.', 'products/banh-dau-xanh-hai-duong.jpg', 'Hải Dương', 1, '2025-06-23 01:58:24', '2025-06-23 02:11:18', 200, 15, 60, 180, '2025-06-23 02:11:18', 1),
(4, 2, 2, 'Bánh đậu xanh', 'banh-dau-xanh', 'Bánh đậu xanh mềm mịn, thơm ngon đặc trưng Hải Dương.', 'products/banh-dau-xanh-hai-duong.jpg', 'Hải Dương', 1, '2025-06-23 01:58:24', '2025-06-23 02:11:25', 200, 15, 60, 180, '2025-06-23 02:11:25', 1),
(5, 1, 2, 'Nem chua', 'nem-chua-1750644668', '<p>Nem chua thanh hoá</p>', 'products/download-2-1750644667.jpg', 'Thanh hoá', 1, '2025-06-23 02:11:08', '2025-06-23 02:33:22', 0, 0, 0, 0, NULL, 1),
(6, 2, 1, 'Bánh mỳ', 'banh-my-1750732433', '<p>Bánh mỳ</p>', 'products/1-1750732431.png', 'Hà Nội', 1, '2025-06-24 02:33:53', '2025-06-24 02:33:53', 0, 0, 0, 0, NULL, 1),
(7, 7, 2, 'Bánh gai Tứ Trụ', 'banh-gai-tu-tru-1750732607', '<p>Bánh gai</p>', 'products/banh-gai-tu-tru-1750732607.jpg', 'Thanh Hoá', 0, '2025-06-24 02:36:47', '2025-06-24 06:56:51', 0, 0, 0, 0, NULL, 1),
(8, 1, 3, 'Bánh răng bừa', 'banh-rang-bua-1750732700', '<p>1111</p>', 'products/banh-rang-bua-1750732699.jpg', 'Thanh Hoá', 1, '2025-06-24 02:38:20', '2025-06-24 02:38:20', 0, 0, 0, 0, NULL, 1),
(9, 1, 4, 'Chè lam Phủ Quảng', 'che-lam-phu-quang-1750732792', '<p>&nbsp;Chè lam Phủ Quảng</p>', 'products/che-lam-phu-quang-1750732792.jpg', 'Thanh Hoá', 1, '2025-06-24 02:39:52', '2025-06-24 02:39:52', 0, 0, 0, 0, NULL, 1),
(10, 2, 2, 'Bánh đa', 'banh-da-1750748772', '<p>Bánh đa Minh Châu tại Thanh Hóa được làm ra từ bột gạo xay nhuyễn, sau đó bánh được tráng và thêm một chút mè đen để tạo mùi thơm, béo cho món ăn. Không chỉ có thể làm loại bánh ăn vặt, mà du khách có thể ăn bánh đa cùng nhiều món ăn khác như hến xào Sông Chu.</p>', 'products/dac-san-thanh-hoa-10-1750748772.webp', 'Thanh hoá', 0, '2025-06-24 07:06:12', '2025-06-26 04:13:22', 0, 0, 0, 0, NULL, 1),
(11, 1, 1, 'Chả mực Hạ Long', 'cha-muc-ha-long-1750908630', '<p>Làm từ mực tươi giã tay, chiên sẵn</p>', 'products/download-6-1750908630.jpg', 'Quảng Ninh', 1, '2025-06-26 03:30:30', '2025-06-26 03:30:30', 0, 0, 0, 0, NULL, 0),
(12, 1, 4, 'Lạp xưởng tươi', 'lap-xuong-tuoi-1750908724', '<p>Làm từ thịt heo, mỡ heo, có vị ngọt nhẹ, béo</p>', 'products/download-11-1750908724.jpg', 'Long An', 1, '2025-06-26 03:32:04', '2025-06-26 03:32:04', 0, 0, 0, 0, NULL, 0),
(13, 1, 3, 'Bánh pía Sóc Trăng', 'banh-pia-soc-trang-1750908809', '<p>Nhân sầu riêng, đậu xanh, trứng muối</p>', 'products/download-14-1750908809.jpg', 'Sóc Trăng', 1, '2025-06-26 03:33:29', '2025-06-26 03:33:29', 0, 0, 0, 0, NULL, 0),
(14, 1, 1, 'Cá kho làng Vũ Đại', 'ca-kho-lang-vu-dai-1750908910', '<p>Cá trắm đen kho bằng niêu đất, gia vị đậm đà.</p>', 'products/download-19-1750908910.jpg', 'Hà Nam', 1, '2025-06-26 03:35:10', '2025-06-26 03:35:10', 0, 0, 0, 0, NULL, 0),
(15, 1, 2, 'Tré Bà Đệ', 'tre-ba-de-1750909037', '<p>Món lên men từ tai, thịt, da heo, gói trong lá chuối, ăn kèm mè, ớt.</p>', 'products/download-1750909037.jpg', 'Đà Nẵng', 1, '2025-06-26 03:37:17', '2025-06-26 03:37:17', 0, 0, 0, 0, NULL, 0),
(16, 1, 1, 'Bánh cốm Hàng Than', 'banh-com-hang-than-1750909137', '<p>Nhân đậu xanh, vỏ cốm dẻo, đặc trưng mùa thu Hà Nội.</p>', 'products/download-25-1750909137.jpg', 'Hà Nội', 1, '2025-06-26 03:38:57', '2025-06-26 03:38:57', 0, 0, 0, 0, NULL, 0),
(17, 1, 2, 'Giò me (Giò bê)', 'gio-me-gio-be-1750909291', '<p>hịt bê cuộn với gia vị, tạo thành cây giò, ăn mát rất ngon</p>', 'products/download-31-1750909291.jpg', 'Nghệ An', 1, '2025-06-26 03:41:31', '2025-06-26 03:41:31', 0, 0, 0, 0, NULL, 0),
(18, 2, 3, 'Hạt điều rang muối', 'hat-dieu-rang-muoi-1750909376', '<p>Hạt điều giòn bùi, vị mặn nhẹ, không dầu mỡ</p>', 'products/download-36-1750909376.jpg', 'Bình Phước', 1, '2025-06-26 03:42:56', '2025-06-26 03:42:56', 0, 0, 0, 0, NULL, 0),
(19, 2, 4, 'Khô cá lóc', 'kho-ca-loc-1750909569', '<p>Cá lóc tẩm ướp, phơi khô, nướng hoặc chiên đều ngon.</p>', 'products/download-38-1750909569.jpg', 'An Giang', 1, '2025-06-26 03:46:09', '2025-06-26 03:46:09', 0, 0, 0, 0, NULL, 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `image_url`, `created_at`, `updated_at`) VALUES
(24, 5, 'products/download-1-175064466855.jpg', '2025-06-23 02:11:08', '2025-06-23 02:11:08'),
(25, 5, 'products/download-2-175064466870.jpg', '2025-06-23 02:11:08', '2025-06-23 02:11:08'),
(26, 5, 'products/download-3-175064466873.jpg', '2025-06-23 02:11:08', '2025-06-23 02:11:08'),
(27, 5, 'products/download-4-175064466840.jpg', '2025-06-23 02:11:08', '2025-06-23 02:11:08'),
(29, 5, 'products/download-175064466849.jpg', '2025-06-23 02:11:08', '2025-06-23 02:11:08'),
(30, 6, 'products/banner-dac-san-huong-viet-175073243311.jpg', '2025-06-24 02:33:53', '2025-06-24 02:33:53'),
(31, 7, 'products/banner-web-5-175073260752.jpg', '2025-06-24 02:36:47', '2025-06-24 02:36:47'),
(32, 8, 'products/dac-san-175073270042.webp', '2025-06-24 02:38:20', '2025-06-24 02:38:20'),
(33, 9, 'products/z6690140506345-d10dafaad1f700ae4e32d90e2e72ec99-175073279217.jpg', '2025-06-24 02:39:52', '2025-06-24 02:39:52'),
(34, 10, 'products/ben-tre-175074877239.gif', '2025-06-24 07:06:12', '2025-06-24 07:06:12'),
(35, 11, 'products/download-6-175090863045.jpg', '2025-06-26 03:30:30', '2025-06-26 03:30:30'),
(36, 11, 'products/download-8-175090863085.jpg', '2025-06-26 03:30:30', '2025-06-26 03:30:30'),
(37, 12, 'products/download-9-175090872473.jpg', '2025-06-26 03:32:04', '2025-06-26 03:32:04'),
(38, 12, 'products/download-10-175090872435.jpg', '2025-06-26 03:32:04', '2025-06-26 03:32:04'),
(39, 12, 'products/download-12-175090872416.jpg', '2025-06-26 03:32:04', '2025-06-26 03:32:04'),
(40, 13, 'products/download-13-175090880927.jpg', '2025-06-26 03:33:29', '2025-06-26 03:33:29'),
(41, 13, 'products/download-15-175090880936.jpg', '2025-06-26 03:33:29', '2025-06-26 03:33:29'),
(42, 13, 'products/download-16-175090880961.jpg', '2025-06-26 03:33:29', '2025-06-26 03:33:29'),
(43, 14, 'products/download-17-175090891033.jpg', '2025-06-26 03:35:10', '2025-06-26 03:35:10'),
(44, 14, 'products/download-18-175090891047.jpg', '2025-06-26 03:35:10', '2025-06-26 03:35:10'),
(45, 14, 'products/download-20-175090891089.jpg', '2025-06-26 03:35:10', '2025-06-26 03:35:10'),
(46, 15, 'products/download-21-175090903732.jpg', '2025-06-26 03:37:17', '2025-06-26 03:37:17'),
(47, 15, 'products/download-22-175090903739.jpg', '2025-06-26 03:37:17', '2025-06-26 03:37:17'),
(48, 15, 'products/download-24-175090903721.jpg', '2025-06-26 03:37:17', '2025-06-26 03:37:17'),
(49, 15, 'products/download-175090903737.jpg', '2025-06-26 03:37:17', '2025-06-26 03:37:17'),
(50, 16, 'products/download-26-175090913716.jpg', '2025-06-26 03:38:57', '2025-06-26 03:38:57'),
(51, 16, 'products/download-27-175090913770.jpg', '2025-06-26 03:38:57', '2025-06-26 03:38:57'),
(52, 16, 'products/download-28-175090913790.jpg', '2025-06-26 03:38:57', '2025-06-26 03:38:57'),
(53, 17, 'products/download-29-175090929158.jpg', '2025-06-26 03:41:31', '2025-06-26 03:41:31'),
(54, 17, 'products/download-30-175090929169.jpg', '2025-06-26 03:41:31', '2025-06-26 03:41:31'),
(55, 17, 'products/download-32-175090929157.jpg', '2025-06-26 03:41:31', '2025-06-26 03:41:31'),
(56, 18, 'products/download-33-175090937621.jpg', '2025-06-26 03:42:56', '2025-06-26 03:42:56'),
(57, 18, 'products/download-34-175090937661.jpg', '2025-06-26 03:42:56', '2025-06-26 03:42:56'),
(58, 18, 'products/download-35-175090937636.jpg', '2025-06-26 03:42:56', '2025-06-26 03:42:56'),
(59, 19, 'products/download-37-175090956967.jpg', '2025-06-26 03:46:09', '2025-06-26 03:46:09'),
(60, 19, 'products/download-39-175090956960.jpg', '2025-06-26 03:46:09', '2025-06-26 03:46:09'),
(61, 19, 'products/download-40-175090956927.jpg', '2025-06-26 03:46:09', '2025-06-26 03:46:09');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `weight` decimal(8,2) DEFAULT NULL,
  `volume` decimal(8,2) DEFAULT NULL,
  `stock` int NOT NULL DEFAULT '0',
  `status` enum('active','inactive') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
  `active` tinyint NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `sku`, `barcode`, `name`, `description`, `image`, `price`, `weight`, `volume`, `stock`, `status`, `active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(78, 6, 'SKU-685A0E911F2B6', NULL, 'Mặc định', NULL, NULL, 11.00, NULL, NULL, 1, 'active', 1, '2025-06-24 02:33:53', '2025-06-24 02:33:53', NULL),
(79, 7, 'SKU-685A0F3FF0874', NULL, 'Mặc định', NULL, NULL, 22.00, NULL, NULL, 2, 'active', 1, '2025-06-24 02:36:47', '2025-06-24 02:36:47', NULL),
(80, 8, 'SKU-685A0F9C05F9E', NULL, 'Mặc định', NULL, NULL, 11.00, NULL, NULL, 11, 'active', 1, '2025-06-24 02:38:20', '2025-06-24 02:38:20', NULL),
(99, 9, 'PRD-1750903212459-1-0', NULL, 'Mặc định', NULL, NULL, 111.00, NULL, NULL, 11, 'active', 1, '2025-06-26 02:01:24', '2025-06-26 02:01:24', NULL),
(109, 10, 'PRD-1750904423012-10-0', NULL, '10', NULL, 'products/variants/variant-1750903818-685cac0a6045a.png', 10.00, NULL, NULL, 11, 'active', 1, '2025-06-26 02:20:30', '2025-06-26 02:20:30', NULL),
(110, 10, 'PRD-1750904423012-15-1', NULL, '15', NULL, 'products/variants/variant-1750903818-685cac0a69a8d.png', 33.00, NULL, NULL, 33, 'active', 1, '2025-06-26 02:20:30', '2025-06-26 02:20:30', NULL),
(111, 10, 'PRD-1750904423012-5-2', NULL, '5', NULL, 'products/variants/variant-1750903818-685cac0a6c955.jpg', 222.00, NULL, NULL, 22, 'active', 1, '2025-06-26 02:20:30', '2025-06-26 02:20:30', NULL),
(112, 11, 'SKU-685CBED66E140', NULL, 'Mặc định', NULL, NULL, 1234.00, NULL, NULL, 33, 'active', 1, '2025-06-26 03:30:30', '2025-06-26 03:30:30', NULL),
(113, 12, 'SKU-685CBF346C500', NULL, 'Mặc định', NULL, NULL, 2222.00, NULL, NULL, 2, 'active', 1, '2025-06-26 03:32:04', '2025-06-26 03:32:04', NULL),
(114, 13, 'SKU-685CBF89274AE', NULL, 'Mặc định', NULL, NULL, 456.00, NULL, NULL, 5, 'active', 1, '2025-06-26 03:33:29', '2025-06-26 03:33:29', NULL),
(115, 14, 'SKU-685CBFEEB9BFD', NULL, 'Mặc định', NULL, NULL, 20000.00, NULL, NULL, 1, 'active', 1, '2025-06-26 03:35:10', '2025-06-26 03:35:10', NULL),
(116, 15, 'SKU-685CC06D50069', NULL, 'Mặc định', NULL, NULL, 2222.00, NULL, NULL, 2, 'active', 1, '2025-06-26 03:37:17', '2025-06-26 03:37:17', NULL),
(117, 16, 'SKU-685CC0D1D73AF', NULL, 'Mặc định', NULL, NULL, 22.00, NULL, NULL, 2, 'active', 1, '2025-06-26 03:38:57', '2025-06-26 03:38:57', NULL),
(118, 17, 'SKU-685CC16B84E32', NULL, 'Mặc định', NULL, NULL, 2222.00, NULL, NULL, 22, 'active', 1, '2025-06-26 03:41:31', '2025-06-26 03:41:31', NULL),
(119, 18, 'SKU-685CC1C05D599', NULL, 'Mặc định', NULL, NULL, 2322.00, NULL, NULL, 222, 'active', 1, '2025-06-26 03:42:56', '2025-06-26 03:42:56', NULL),
(120, 19, 'SKU-685CC2819C19F', NULL, 'Mặc định', NULL, NULL, 222.00, NULL, NULL, 22, 'active', 1, '2025-06-26 03:46:09', '2025-06-26 03:46:09', NULL),
(124, 5, 'PRD-1750924909204-10-0', NULL, '10', NULL, 'products/variants/variant-1750921913-685cf2b9b1b16.jpg', 11.00, NULL, NULL, 1, 'active', 1, '2025-06-26 08:02:11', '2025-06-26 08:02:11', NULL),
(125, 5, 'PRD-1750924909204-15-1', NULL, '15', NULL, 'products/variants/variant-1750921913-685cf2b9bd848.jpg', 12.00, NULL, NULL, 1, 'active', 1, '2025-06-26 08:02:11', '2025-06-26 08:02:11', NULL),
(126, 5, 'PRD-1750924909204-5-2', NULL, '5', NULL, 'products/variants/variant-1750921913-685cf2b9c0091.jpg', 22.00, NULL, NULL, 22, 'active', 1, '2025-06-26 08:02:11', '2025-06-26 08:02:11', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_variant_attribute_values`
--

CREATE TABLE `product_variant_attribute_values` (
  `id` bigint UNSIGNED NOT NULL,
  `product_variant_id` bigint UNSIGNED NOT NULL,
  `attribute_id` bigint UNSIGNED NOT NULL,
  `attribute_value_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_variant_attribute_values`
--

INSERT INTO `product_variant_attribute_values` (`id`, `product_variant_id`, `attribute_id`, `attribute_value_id`, `created_at`, `updated_at`) VALUES
(142, 109, 6, 10, NULL, NULL),
(143, 110, 6, 11, NULL, NULL),
(144, 111, 6, 12, NULL, NULL),
(148, 124, 6, 10, NULL, NULL),
(149, 125, 6, 11, NULL, NULL),
(150, 126, 6, 12, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `regions`
--

CREATE TABLE `regions` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `regions`
--

INSERT INTO `regions` (`id`, `name`, `slug`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Miền Bắc', 'mien-bac', '2025-06-23 01:58:24', '2025-06-23 01:58:24', NULL),
(2, 'Miền Trung', 'mien-trung', '2025-06-23 01:58:24', '2025-06-23 01:58:24', NULL),
(3, 'Miền Nam', 'mien-nam', '2025-06-23 01:58:24', '2025-06-23 01:58:24', NULL),
(4, 'Miền Tây', 'mien-tay', '2025-06-23 01:58:24', '2025-06-23 01:58:24', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `rating` tinyint NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `product_id`, `rating`, `comment`, `created_at`, `updated_at`) VALUES
(3, 1, 1, 4, 'Sản phẩm chất lượng, đáng tiền!', '2025-06-23 01:58:24', '2025-06-23 01:58:24'),
(4, 2, 2, 2, 'Did not meet my expectations.', '2025-06-23 01:58:24', '2025-06-23 01:58:24');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review_replies`
--

CREATE TABLE `review_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `review_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `search_history`
--

CREATE TABLE `search_history` (
  `user_id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `result_count` int NOT NULL DEFAULT '0',
  `searched_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `search_suggestions`
--

CREATE TABLE `search_suggestions` (
  `id` bigint UNSIGNED NOT NULL,
  `keyword` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint UNSIGNED DEFAULT NULL,
  `region_id` bigint UNSIGNED DEFAULT NULL,
  `search_count` int NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('FXfk1u5pjZl1ufbvCGeNyj60Qs5makGDxcYBf4iZ', 4, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/137.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoibXRkSFlMbEM0aWN0Smg3S0U0c1BKM0ZLZ29IV25VT09ocDBBTkRhaiI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9jbGllbnQvc2FuLXBoYW0vYmFuaC1teS0xNzUwNzMyNDMzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6NDt9', 1750990729);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `cost` decimal(10,2) NOT NULL,
  `estimated_days` int NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `description`, `cost`, `estimated_days`, `active`, `created_at`, `updated_at`) VALUES
(1, 'Giao hàng nhanh', 'Giao trong 1-2 ngày', 20000.00, 2, 1, '2025-06-14 00:57:04', '2025-06-14 00:57:04'),
(2, 'Giao hàng tiết kiệm', 'Giao trong 3-5 ngày', 15000.00, 5, 1, '2025-06-14 00:57:04', '2025-06-14 00:57:04'),
(3, 'Giao hàng nhanh', 'Giao trong 1-2 ngày', 20000.00, 2, 1, '2025-06-23 01:58:23', '2025-06-23 01:58:23'),
(4, 'Giao hàng tiết kiệm', 'Giao trong 3-5 ngày', 15000.00, 5, 1, '2025-06-23 01:58:23', '2025-06-23 01:58:23');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `support_requests`
--

CREATE TABLE `support_requests` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('open','resolved','closed') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `support_tickets`
--

CREATE TABLE `support_tickets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('pending','resolved') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `title`, `content`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'dfd', 'dfđ', 'pending', '2025-06-16 21:31:09', '2025-06-16 21:31:09', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `support_ticket_replies`
--

CREATE TABLE `support_ticket_replies` (
  `id` bigint UNSIGNED NOT NULL,
  `support_ticket_id` bigint UNSIGNED NOT NULL,
  `admin_id` bigint UNSIGNED NOT NULL,
  `reply` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` enum('member','admin') COLLATE utf8mb4_unicode_ci DEFAULT 'member',
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `avatar`, `remember_token`, `created_at`, `updated_at`, `status`, `deleted_at`) VALUES
(1, 'Nguyen Van A', 'user1@example.com', '0912345678', NULL, '$2y$12$n430.Pbvjx3Y1Y05Nck6HOpnjkdpebnMywiUHQbcQxuobcmGo5Dtq', 'member', NULL, NULL, '2025-06-23 01:58:23', '2025-06-23 01:58:23', 1, NULL),
(2, 'Le Thi B', 'seller1@example.com', '0987654321', NULL, '$2y$12$YYd7y3wBlZnmTIx5t8lqnurgFg2ZapjcpmJo/LXvDh5lgBpP/0LBi', 'member', NULL, NULL, '2025-06-23 01:58:23', '2025-06-24 01:44:06', 0, NULL),
(3, 'Admin User', 'admin@example.com', '0909090909', NULL, '$2y$12$dRRVNPSkTldxlY9iBKkqueY7sYCvIDFpENlgGXL2v2HjWZmz66kje', 'admin', NULL, NULL, '2025-06-23 01:58:23', '2025-06-24 01:43:48', 0, NULL),
(4, 'Hoàng Hà', 'ha@gmail.com', '0123456789', NULL, '$2y$12$tw5yqqXhOfQbGsgFZVjQ3.YhiV1gM10xGRtj7YodK34cKsG/NJKL.', 'admin', NULL, NULL, '2025-06-23 02:02:20', '2025-06-23 02:02:20', 1, NULL),
(5, 'Ha14', 'ha14@gmail.com', '11122233344', NULL, '$2y$12$geVA6ERFwY703xc8qynexu19iDOI3GunJmdWrtOerCGTi89MPc93a', 'member', NULL, NULL, '2025-06-24 01:42:16', '2025-06-24 01:42:16', 1, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `variants`
--

CREATE TABLE `variants` (
  `id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `attribute_value_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `stock` int NOT NULL DEFAULT '0',
  `sku` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `barcode` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `balance` decimal(10,2) NOT NULL DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint UNSIGNED NOT NULL,
  `wallet_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('deposit','payment','refund') COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wishlist`
--

CREATE TABLE `wishlist` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `product_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(4, 4, 19, '2025-06-27 02:11:26', '2025-06-27 02:11:26'),
(5, 4, 6, '2025-06-27 02:11:37', '2025-06-27 02:11:37');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attributes_name_unique` (`name`),
  ADD UNIQUE KEY `attributes_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attribute_values_attribute_id_value_unique` (`attribute_id`,`value`);

--
-- Chỉ mục cho bảng `banners`
--
ALTER TABLE `banners`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `blogs`
--
ALTER TABLE `blogs`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Chỉ mục cho bảng `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_user_id_foreign` (`user_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comments_user_id_foreign` (`user_id`),
  ADD KEY `comments_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `comment_replies_comment_id_foreign` (`comment_id`),
  ADD KEY `comment_replies_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_product_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_product_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `discount_codes`
--
ALTER TABLE `discount_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `discount_codes_code_unique` (`code`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD KEY `favorites_user_id_foreign` (`user_id`),
  ADD KEY `favorites_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_code_unique` (`order_code`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_address_id_foreign` (`address_id`),
  ADD KEY `orders_shipping_method_id_foreign` (`shipping_method_id`),
  ADD KEY `orders_discount_code_id_foreign` (`discount_code_id`);

--
-- Chỉ mục cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_product_variant_value_id_foreign` (`product_variant_value_id`);

--
-- Chỉ mục cho bảng `order_status_logs`
--
ALTER TABLE `order_status_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_status_logs_order_id_foreign` (`order_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_region_id_foreign` (`region_id`);

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variant_attribute_unique` (`product_variant_id`,`attribute_id`),
  ADD KEY `product_variant_attribute_values_attribute_id_foreign` (`attribute_id`),
  ADD KEY `product_variant_attribute_values_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Chỉ mục cho bảng `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `regions_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_replies_review_id_foreign` (`review_id`),
  ADD KEY `review_replies_admin_id_foreign` (`admin_id`);

--
-- Chỉ mục cho bảng `search_history`
--
ALTER TABLE `search_history`
  ADD KEY `search_history_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `search_suggestions`
--
ALTER TABLE `search_suggestions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `search_suggestions_category_id_foreign` (`category_id`),
  ADD KEY `search_suggestions_region_id_foreign` (`region_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `support_requests`
--
ALTER TABLE `support_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_requests_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_tickets_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `support_ticket_replies_support_ticket_id_foreign` (`support_ticket_id`),
  ADD KEY `support_ticket_replies_admin_id_foreign` (`admin_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- Chỉ mục cho bảng `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `variants_product_id_foreign` (`product_id`),
  ADD KEY `variants_attribute_value_id_foreign` (`attribute_value_id`);

--
-- Chỉ mục cho bảng `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallets_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`);

--
-- Chỉ mục cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_user_id_foreign` (`user_id`),
  ADD KEY `wishlist_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `attribute_values`
--
ALTER TABLE `attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `banners`
--
ALTER TABLE `banners`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT cho bảng `blogs`
--
ALTER TABLE `blogs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `comments`
--
ALTER TABLE `comments`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `comment_replies`
--
ALTER TABLE `comment_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `coupon_product`
--
ALTER TABLE `coupon_product`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `discount_codes`
--
ALTER TABLE `discount_codes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `order_status_logs`
--
ALTER TABLE `order_status_logs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=62;

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=127;

--
-- AUTO_INCREMENT cho bảng `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=151;

--
-- AUTO_INCREMENT cho bảng `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `search_suggestions`
--
ALTER TABLE `search_suggestions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `support_requests`
--
ALTER TABLE `support_requests`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `attribute_values`
--
ALTER TABLE `attribute_values`
  ADD CONSTRAINT `attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `comments_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `comment_replies`
--
ALTER TABLE `comment_replies`
  ADD CONSTRAINT `comment_replies_comment_id_foreign` FOREIGN KEY (`comment_id`) REFERENCES `comments` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `comment_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Ràng buộc cho bảng `coupon_product`
--
ALTER TABLE `coupon_product`
  ADD CONSTRAINT `coupon_product_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `discount_codes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_address_id_foreign` FOREIGN KEY (`address_id`) REFERENCES `addresses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_discount_code_id_foreign` FOREIGN KEY (`discount_code_id`) REFERENCES `discount_codes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_method_id_foreign` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_variant_value_id_foreign` FOREIGN KEY (`product_variant_value_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `order_status_logs`
--
ALTER TABLE `order_status_logs`
  ADD CONSTRAINT `order_status_logs_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `product_variant_attribute_values`
--
ALTER TABLE `product_variant_attribute_values`
  ADD CONSTRAINT `product_variant_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attribute_values_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_attribute_values_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  ADD CONSTRAINT `review_replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_replies_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `search_history`
--
ALTER TABLE `search_history`
  ADD CONSTRAINT `search_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `search_suggestions`
--
ALTER TABLE `search_suggestions`
  ADD CONSTRAINT `search_suggestions_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `search_suggestions_region_id_foreign` FOREIGN KEY (`region_id`) REFERENCES `regions` (`id`) ON DELETE SET NULL;

--
-- Ràng buộc cho bảng `support_requests`
--
ALTER TABLE `support_requests`
  ADD CONSTRAINT `support_requests_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `support_ticket_replies`
--
ALTER TABLE `support_ticket_replies`
  ADD CONSTRAINT `support_ticket_replies_admin_id_foreign` FOREIGN KEY (`admin_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `support_ticket_replies_support_ticket_id_foreign` FOREIGN KEY (`support_ticket_id`) REFERENCES `support_tickets` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `variants`
--
ALTER TABLE `variants`
  ADD CONSTRAINT `variants_attribute_value_id_foreign` FOREIGN KEY (`attribute_value_id`) REFERENCES `attribute_values` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Ràng buộc cho bảng `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
