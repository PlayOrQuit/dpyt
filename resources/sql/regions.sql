-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th4 21, 2019 lúc 01:04 PM
-- Phiên bản máy phục vụ: 10.1.38-MariaDB
-- Phiên bản PHP: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `laravel-youtube`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `regions`
--

CREATE TABLE `regions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(75) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gl` varchar(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `regions`
--

INSERT INTO `regions` (`id`, `name`, `gl`, `created_at`, `updated_at`) VALUES
(1, 'Algeria', 'DZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(2, 'Argentina', 'AR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(3, 'Australia', 'AU', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(4, 'Austria', 'AT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(5, 'Azerbaijan', 'AZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(6, 'Bahrain', 'BH', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(7, 'Belarus', 'BY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(8, 'Belgium', 'BE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(9, 'Bolivia', 'BO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(10, 'Bosnia and Herzegovina', 'BA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(11, 'Brazil', 'BR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(12, 'Bulgaria', 'BG', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(13, 'Canada', 'CA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(14, 'Chile', 'CL', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(15, 'Colombia', 'CO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(16, 'Costa Rica', 'CR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(17, 'Croatia', 'HR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(18, 'Cyprus', 'CY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(19, 'Czechia', 'CZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(20, 'Denmark', 'DK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(21, 'Dominican Republic', 'DO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(22, 'Ecuador', 'EC', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(23, 'Egypt', 'EG', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(24, 'El Salvador', 'SV', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(25, 'Estonia', 'EE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(26, 'Finland', 'FI', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(27, 'France', 'FR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(28, 'Georgia', 'GE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(29, 'Germany', 'DE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(30, 'Ghana', 'GH', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(31, 'Greece', 'GR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(32, 'Guatemala', 'GT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(33, 'Honduras', 'HN', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(34, 'Hong Kong', 'HK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(35, 'Hungary', 'HU', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(36, 'Iceland', 'IS', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(37, 'India', 'IN', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(38, 'Indonesia', 'ID', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(39, 'Iraq', 'IQ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(40, 'Ireland', 'IE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(41, 'Israel', 'IL', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(42, 'Italy', 'IT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(43, 'Jamaica', 'JM', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(44, 'Japan', 'JP', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(45, 'Jordan', 'JO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(46, 'Kazakhstan', 'KZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(47, 'Kenya', 'KE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(48, 'Kuwait', 'KW', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(49, 'Latvia', 'LV', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(50, 'Lebanon', 'LB', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(51, 'Libya', 'LY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(52, 'Liechtenstein', 'LI', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(53, 'Lithuania', 'LT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(54, 'Luxembourg', 'LU', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(55, 'Malaysia', 'MY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(56, 'Malta', 'MT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(57, 'Mexico', 'MX', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(58, 'Montenegro', 'ME', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(59, 'Morocco', 'MA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(60, 'Nepal', 'NP', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(61, 'Netherlands', 'NL', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(62, 'New Zealand', 'NZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(63, 'Nicaragua', 'NI', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(64, 'Nigeria', 'NG', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(65, 'North Macedonia', 'MK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(66, 'Norway', 'NO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(67, 'Oman', 'OM', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(68, 'Pakistan', 'PK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(69, 'Panama', 'PA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(70, 'Paraguay', 'PY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(71, 'Peru', 'PE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(72, 'Philippines', 'PH', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(73, 'Poland', 'PL', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(74, 'Portugal', 'PT', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(75, 'Puerto Rico', 'PR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(76, 'Qatar', 'QA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(77, 'Romania', 'RO', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(78, 'Russia', 'RU', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(79, 'Saudi Arabia', 'SA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(80, 'Senegal', 'SN', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(81, 'Serbia', 'RS', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(82, 'Singapore', 'SG', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(83, 'Slovakia', 'SK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(84, 'Slovenia', 'SI', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(85, 'South Africa', 'ZA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(86, 'South Korea', 'KR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(87, 'Spain', 'ES', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(88, 'Sri Lanka', 'LK', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(89, 'Sweden', 'SE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(90, 'Switzerland', 'CH', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(91, 'Taiwan', 'TW', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(92, 'Tanzania', 'TZ', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(93, 'Thailand', 'TH', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(94, 'Tunisia', 'TN', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(95, 'Turkey', 'TR', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(96, 'Uganda', 'UG', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(97, 'Ukraine', 'UA', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(98, 'United Arab Emirates', 'AE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(99, 'United Kingdom', 'GB', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(100, 'United States', 'US', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(101, 'Uruguay', 'UY', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(102, 'Vietnam', 'VN', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(103, 'Yemen', 'YE', '2019-04-21 04:02:32', '2019-04-21 04:02:32'),
(104, 'Zimbabwe', 'ZW', '2019-04-21 04:02:32', '2019-04-21 04:02:32');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `regions`
--
ALTER TABLE `regions`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `regions`
--
ALTER TABLE `regions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;