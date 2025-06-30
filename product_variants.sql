-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th6 30, 2025 lúc 02:48 AM
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
(139, 16, 'SKU-685CC0D1D73AF', NULL, 'Mặc định', NULL, NULL, 22.00, NULL, NULL, 2, 'active', 1, '2025-06-27 07:30:44', '2025-06-27 07:30:44', NULL),
(140, 5, 'PRD-1751009656281-10-0', NULL, '10', NULL, 'products/variants/variant-1750921913-685cf2b9b1b16.jpg', 11.00, NULL, NULL, 1, 'active', 1, '2025-06-27 07:34:22', '2025-06-27 07:34:22', NULL),
(141, 5, 'PRD-1751009656281-15-1', NULL, '15', NULL, 'products/variants/variant-1750921913-685cf2b9bd848.jpg', 12.00, NULL, NULL, 1, 'active', 1, '2025-06-27 07:34:22', '2025-06-27 07:34:22', NULL),
(142, 5, 'PRD-1751009656281-5-2', NULL, '5', NULL, 'products/variants/variant-1750921913-685cf2b9c0091.jpg', 22.00, NULL, NULL, 22, 'active', 1, '2025-06-27 07:34:22', '2025-06-27 07:34:22', NULL),
(143, 19, 'SKU-685CC2819C19F', NULL, 'Mặc định', NULL, NULL, 222.00, NULL, NULL, 22, 'active', 1, '2025-06-27 09:46:36', '2025-06-27 09:46:36', NULL),
(144, 7, 'SKU-685A0F3FF0874', NULL, 'Mặc định', NULL, NULL, 22.00, NULL, NULL, 2, 'active', 1, '2025-06-30 02:40:06', '2025-06-30 02:40:06', NULL),
(145, 10, 'PRD-1750904423012-10-0', NULL, 'Mặc định', NULL, NULL, 10.00, NULL, NULL, 11, 'active', 1, '2025-06-30 02:40:19', '2025-06-30 02:40:19', NULL);

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=146;

--
-- Ràng buộc đối với các bảng kết xuất
--

--
-- Ràng buộc cho bảng `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
