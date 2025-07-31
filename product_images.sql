-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th7 31, 2025 lúc 01:13 AM
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
(61, 19, 'products/download-40-175090956927.jpg', '2025-06-26 03:46:09', '2025-06-26 03:46:09'),
(62, 20, 'products/download-41-175125300443.jpg', '2025-06-30 03:10:04', '2025-06-30 03:10:04'),
(63, 20, 'products/download-42-175125300446.jpg', '2025-06-30 03:10:04', '2025-06-30 03:10:04'),
(64, 20, 'products/download-43-175125300448.jpg', '2025-06-30 03:10:04', '2025-06-30 03:10:04'),
(65, 21, 'products/download-1-175289392253.jpg', '2025-07-19 02:58:42', '2025-07-19 02:58:42'),
(66, 21, 'products/download-3-175289392258.jpg', '2025-07-19 02:58:42', '2025-07-19 02:58:42'),
(67, 21, 'products/download-4-175289392281.jpg', '2025-07-19 02:58:42', '2025-07-19 02:58:42'),
(68, 21, 'products/download-5-175289392229.jpg', '2025-07-19 02:58:42', '2025-07-19 02:58:42');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
