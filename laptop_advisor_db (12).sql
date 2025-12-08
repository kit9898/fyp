-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 08, 2025 at 01:08 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `laptop_advisor_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_activity_log`
--

CREATE TABLE `admin_activity_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(100) NOT NULL,
  `module` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `affected_record_type` varchar(50) DEFAULT NULL,
  `affected_record_id` int(11) DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `request_method` varchar(10) DEFAULT NULL,
  `request_uri` text DEFAULT NULL,
  `metadata` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`metadata`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_activity_log`
--

INSERT INTO `admin_activity_log` (`log_id`, `admin_id`, `action`, `module`, `description`, `affected_record_type`, `affected_record_id`, `ip_address`, `user_agent`, `request_method`, `request_uri`, `metadata`, `created_at`) VALUES
(1, 2, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-21 20:35:49'),
(2, 2, 'logout', NULL, 'Admin logged out', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-22 06:55:32'),
(3, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-22 06:55:41'),
(4, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-24 16:12:01'),
(5, 1, 'logout', NULL, 'Admin logged out', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-24 17:13:36'),
(6, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-11-24 20:37:57'),
(7, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-12-01 06:36:36'),
(8, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-12-01 08:13:39'),
(9, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-12-02 03:37:38'),
(10, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-12-02 11:29:42'),
(11, 1, 'login', NULL, 'Admin logged in successfully', NULL, NULL, '::1', NULL, NULL, NULL, NULL, '2025-12-02 11:31:17'),
(12, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 15:14:29'),
(13, 1, 'update', 'products', 'Updated product: Asus ROG Strix G16', 'product', 128, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 15:14:35'),
(14, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:22:13'),
(15, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:22:15'),
(16, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:22:17'),
(17, 1, 'update', 'admin_users', 'Updated personal profile', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_profile.php', NULL, '2025-12-07 15:23:38'),
(18, 1, 'update', 'admin_users', 'Updated personal profile', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_profile.php', NULL, '2025-12-07 15:23:48'),
(19, 1, 'update', 'admin_users', 'Updated personal profile', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_profile.php', NULL, '2025-12-07 15:23:49'),
(20, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:24:23'),
(21, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-07 15:24:29'),
(22, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 15:24:31'),
(23, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:24:52'),
(24, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:25:15'),
(25, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 15:25:52'),
(26, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:25:58'),
(27, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:30:22'),
(28, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:30:24'),
(29, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:30:26'),
(30, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-07 15:35:07'),
(31, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 15:35:16'),
(32, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 15:56:17'),
(33, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-07 16:13:38'),
(34, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 16:16:43'),
(35, 1, 'delete', 'products', 'Bulk deleted 6 products', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 16:43:28'),
(36, 1, 'update', 'orders', 'Verified payment for Order #27', 'order', 27, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/transaction_actions.php', NULL, '2025-12-07 16:48:29'),
(37, 1, 'update', 'products', 'Updated product: Asus ROG Strix G16', 'product', 128, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 17:43:11'),
(38, 1, 'update', 'products', 'Updated product: ROG Strix G15', 'product', 7, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 17:50:46'),
(39, 1, 'update', 'products', 'Updated product: Blade 15', 'product', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 17:50:55'),
(40, 1, 'update', 'products', 'Updated product: Blade 15', 'product', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 17:54:27'),
(41, 1, 'update', 'products', 'Updated product: ZenBook Duo', 'product', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 17:58:26'),
(42, 1, 'delete', 'products', 'Bulk deleted 50 products', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-07 18:05:38'),
(43, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-07 18:58:51'),
(44, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 18:58:53'),
(45, 1, 'create', 'admin_users', 'Created new admin: GYZ TZE (GYZLOVETZE@gmail.com)', 'admin', 15, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:15'),
(46, 1, 'update', 'admin_users', 'Updated admin details: GYZ TZE', 'admin', 15, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:20'),
(47, 1, 'update', 'admin_users', 'Updated admin details: GYZ TZE', 'admin', 15, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:25'),
(48, 1, 'update', 'admin_users', 'Updated admin details: GYZ TZE', 'admin', 15, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:33'),
(49, 1, 'create', 'roles', 'Created new role: 111', 'role', 10, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:45'),
(50, 1, 'delete', 'roles', 'Deleted role ID: 10', 'role', 10, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:20:48'),
(51, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-07 19:21:35'),
(52, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-07 19:21:37'),
(53, 1, 'update', 'admin_users', 'Updated admin details: GYZ TZE', 'admin', 15, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:21:49'),
(54, 1, 'create', 'coupons', 'Created coupon: XD985', 'coupon', 4, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:32:14'),
(55, 1, 'update', 'coupons', 'Updated coupon: XD985', 'coupon', 4, '::1', NULL, NULL, NULL, NULL, '2025-12-07 19:33:02'),
(56, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 19:35:22'),
(57, 1, 'update', 'users', 'Updated profile for user ID: 6', 'user', 6, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/update_customer.php', NULL, '2025-12-07 19:37:57'),
(58, 1, 'update', 'users', 'Updated profile for user ID: 4', 'user', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/update_customer.php', NULL, '2025-12-07 19:38:01'),
(59, 1, 'update', 'users', 'Updated profile for user ID: 3', 'user', 3, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/update_customer.php', NULL, '2025-12-07 19:38:04'),
(60, 1, 'update', 'users', 'Updated profile for user ID: 2', 'user', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/update_customer.php', NULL, '2025-12-07 19:38:07'),
(61, 1, 'update', 'users', 'Updated profile for user ID: 1', 'user', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/ajax/update_customer.php', NULL, '2025-12-07 19:38:10'),
(62, 1, 'logout', 'auth', 'Admin logged out', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/logout.php', NULL, '2025-12-08 06:22:23'),
(63, 1, 'login', 'auth', 'Admin logged in successfully', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/login.php', NULL, '2025-12-08 06:22:41'),
(64, 1, 'update', 'products', 'Updated product: IdeaPad Slim 3', 'product', 4, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 06:44:47'),
(65, 1, 'update', 'products', 'Updated product: ASUS ProArt Display PA279CRV', 'product', 227, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:04:33'),
(66, 1, 'update', 'products', 'Updated product: Wooting 60HE', 'product', 218, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:05:08'),
(67, 1, 'update', 'products', 'Updated product: Keychron M3 Wireless', 'product', 213, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:18:31'),
(68, 1, 'update', 'products', 'Updated product: Sony WH-1000XM5', 'product', 219, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:21:00'),
(69, 1, 'update', 'products', 'Updated product: Sony WH-1000XM5', 'product', 219, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:22:46'),
(70, 1, 'update', 'products', 'Updated product: Sony WH-1000XM5', 'product', 219, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:22:53'),
(71, 1, 'update', 'products', 'Updated product: ASUS ROG Strix Scar 18 (2024)', 'product', 179, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:23:00'),
(72, 1, 'update', 'products', 'Updated product: Razer Blade 16 (2024)', 'product', 180, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:23:37'),
(73, 1, 'update', 'products', 'Updated product: MSI Titan 18 HX', 'product', 181, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:24:02'),
(74, 1, 'update', 'products', 'Updated product: Alienware m18 R2', 'product', 182, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:24:22'),
(75, 1, 'update', 'products', 'Updated product: Lenovo Legion 9i Gen 9', 'product', 183, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:24:58'),
(76, 1, 'update', 'products', 'Updated product: Lenovo Legion 9i Gen 9', 'product', 183, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:25:15'),
(77, 1, 'update', 'products', 'Updated product: Acer Predator Helios 18', 'product', 184, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:25:44'),
(78, 1, 'update', 'products', 'Updated product: Acer Predator Helios 18', 'product', 184, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:26:17'),
(79, 1, 'update', 'products', 'Updated product: HP Omen Transcend 14', 'product', 185, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:26:38'),
(80, 1, 'update', 'products', 'Updated product: ASUS Zephyrus G14 (2024)', 'product', 186, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:32:42'),
(81, 1, 'update', 'products', 'Updated product: GIGABYTE AORUS 17X', 'product', 187, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:33:42'),
(82, 1, 'update', 'products', 'Updated product: Dell G16 Gaming Laptop', 'product', 188, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:34:04'),
(83, 1, 'update', 'products', 'Updated product: Apple MacBook Pro 14 (M3 Max)', 'product', 189, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:36:22'),
(84, 1, 'update', 'products', 'Updated product: Dell XPS 16 (2024)', 'product', 190, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:37:25'),
(85, 1, 'update', 'products', 'Updated product: Lenovo ThinkPad X1 Carbon Gen 12', 'product', 191, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:38:44'),
(86, 1, 'update', 'products', 'Updated product: HP Dragonfly G4', 'product', 192, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 07:41:41'),
(87, 1, 'update', 'products', 'Updated product: Microsoft Surface Laptop 6', 'product', 193, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:01:19'),
(88, 1, 'update', 'products', 'Updated product: Framework Laptop 13 (AMD)', 'product', 194, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:02:01'),
(89, 1, 'update', 'products', 'Updated product: LG Gram Pro 16 2-in-1', 'product', 195, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:02:22'),
(90, 1, 'update', 'products', 'Updated product: Samsung Galaxy Book4 Ultra', 'product', 196, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:02:43'),
(91, 1, 'update', 'products', 'Updated product: ASUS ProArt Studiobook 16', 'product', 197, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:03:00'),
(92, 1, 'update', 'products', 'Updated product: MacBook Air 15 (M3)', 'product', 198, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:03:18'),
(93, 1, 'update', 'products', 'Updated product: MSI Creator Z17 HX Studio', 'product', 199, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:03:37'),
(94, 1, 'update', 'products', 'Updated product: Dell Precision 5680', 'product', 200, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:03:56'),
(95, 1, 'update', 'products', 'Updated product: Surface Laptop Studio 2', 'product', 201, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:04:19'),
(96, 1, 'update', 'products', 'Updated product: Lenovo Yoga Pro 9i', 'product', 202, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:04:48'),
(97, 1, 'update', 'products', 'Updated product: Acer Swift Go 14', 'product', 203, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:05:16'),
(98, 1, 'update', 'products', 'Updated product: ASUS Vivobook S 15 OLED', 'product', 204, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:05:34'),
(99, 1, 'update', 'products', 'Updated product: HP Envy x360 14', 'product', 205, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:06:00'),
(100, 1, 'update', 'products', 'Updated product: Lenovo IdeaPad Slim 5 Gen 9', 'product', 206, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:06:20'),
(101, 1, 'update', 'products', 'Updated product: Dell Inspiron 14 Plus', 'product', 207, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:06:38'),
(102, 1, 'update', 'products', 'Updated product: MacBook Air 13 (M2)', 'product', 208, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:07:02'),
(103, 1, 'update', 'products', 'Updated product: Logitech MX Master 3S', 'product', 209, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:07:21'),
(104, 1, 'update', 'products', 'Updated product: Razer DeathAdder V3 Pro', 'product', 210, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:07:46'),
(105, 1, 'update', 'products', 'Updated product: Logitech G502 X Plus', 'product', 211, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:08:02'),
(106, 1, 'update', 'products', 'Updated product: Apple Magic Mouse', 'product', 212, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:08:21'),
(107, 1, 'update', 'products', 'Updated product: Keychron Q1 Pro', 'product', 214, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:08:40'),
(108, 1, 'update', 'products', 'Updated product: Logitech MX Keys S', 'product', 215, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:08:59'),
(109, 1, 'update', 'products', 'Updated product: Razer Huntsman V3 Pro', 'product', 216, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:09:18'),
(110, 1, 'update', 'products', 'Updated product: ASUS ProArt Display PA279CRV', 'product', 227, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:09:37'),
(111, 1, 'update', 'products', 'Updated product: NuPhy Air75 V2', 'product', 217, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:09:57'),
(112, 1, 'update', 'products', 'Updated product: Bose QuietComfort Ultra', 'product', 220, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:10:18'),
(113, 1, 'update', 'products', 'Updated product: SteelSeries Arctis Nova Pro Wireless', 'product', 221, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:10:32'),
(114, 1, 'update', 'products', 'Updated product: Razer BlackShark V2 Pro (2023)', 'product', 222, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:10:48'),
(115, 1, 'update', 'products', 'Updated product: HyperX Cloud III Wireless', 'product', 223, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:11:06'),
(116, 1, 'update', 'products', 'Updated product: Dell UltraSharp U2723QE', 'product', 224, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:11:22'),
(117, 1, 'update', 'products', 'Updated product: LG C3 42-inch OLED', 'product', 225, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:11:39'),
(118, 1, 'update', 'products', 'Updated product: Samsung Odyssey OLED G9', 'product', 226, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:11:55'),
(119, 1, 'update', 'products', 'Updated product: BenQ ScreenBar Halo', 'product', 228, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_products.php', NULL, '2025-12-08 10:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `admin_sessions`
--

CREATE TABLE `admin_sessions` (
  `session_id` varchar(255) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `login_time` datetime DEFAULT current_timestamp(),
  `last_activity` datetime DEFAULT current_timestamp(),
  `expires_at` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_sessions`
--

INSERT INTO `admin_sessions` (`session_id`, `admin_id`, `ip_address`, `user_agent`, `login_time`, `last_activity`, `expires_at`, `is_active`) VALUES
('04d7e9ad8e7f4f93af26da2a8290fa8ff9b49bc9fe15f7bc83293de7f0eeca4d', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-25 04:37:57', '2025-11-25 04:37:57', '2025-11-26 04:37:57', 1),
('30001a156de486bbce092af6d563ee90353850760a6bbe5a28c6456cd0bc7bea', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 16:13:39', '2025-12-01 16:13:39', '2025-12-02 16:13:39', 1),
('3ec7b1dd0b90ef4d6e867e5c32dc899f4123fa4dc6626b9f00dcd91707ea6fbf', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:34:50', '2025-11-22 04:34:50', '2025-11-23 04:34:50', 1),
('467461617411514d096f80d7f8f24c36f894f3fde2c54fe45fd371cda4fae7c8', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 11:37:38', '2025-12-02 11:37:38', '2025-12-03 11:37:38', 1),
('6c10f94d02455d0f739f916183d6038098719dd1132c67f1d568f3d58500b0b1', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-08 03:21:37', '2025-12-08 03:21:37', '2025-12-09 03:21:37', 1),
('6e1e039c5e0918d31acd787f569abfa25eb25f9501de8ab0bfcb0e8cba604ed4', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 19:31:17', '2025-12-02 19:31:17', '2025-12-03 19:31:17', 1),
('79aa47d6395816e07ebf45631ed66d4deab011b293d1cfd0f9185cf05e39939b', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 14:55:41', '2025-11-22 14:55:41', '2025-11-23 14:55:41', 1),
('7d7ab7204a215e7ff33e7ae6d8b0cc7e8e41c80aafe3a8ac69c2982afbe02136', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:34:23', '2025-11-22 04:34:23', '2025-11-23 04:34:23', 1),
('8dd810838b06ecb04f0dabc70db18acc422fd087f6672642649f0a97524cae27', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:34:31', '2025-11-22 04:34:31', '2025-11-23 04:34:31', 1),
('9dc7628840225b89fd580e0584845d2c1a32f16bed8c713599a1ff6980ee99c7', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:35:07', '2025-11-22 04:35:07', '2025-11-23 04:35:07', 1),
('c413ddfda91a8cadd37df9d45ce663c101d1ff00fa9aaaa0c6df38374830ef95', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 19:29:42', '2025-12-02 19:29:42', '2025-12-03 19:29:42', 1),
('dac0fd08b5dae7da01e3cf953d39e7b926dfc277367290e37bb6586bd20eee59', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-08 14:22:41', '2025-12-08 14:22:41', '2025-12-09 14:22:41', 1),
('e9ee9f9532698841ed7956ad667311a8e3d92c888c9a010314e880a13dbb2ecf', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 14:36:36', '2025-12-01 14:36:36', '2025-12-02 14:36:36', 1);

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `admin_code` varchar(20) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `login_count` int(11) DEFAULT 0,
  `failed_login_attempts` int(11) DEFAULT 0,
  `last_failed_login` datetime DEFAULT NULL,
  `lockout_until` datetime DEFAULT NULL,
  `two_factor_enabled` tinyint(1) DEFAULT 0,
  `two_factor_secret` varchar(255) DEFAULT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `timezone` varchar(50) DEFAULT 'America/New_York',
  `language` varchar(10) DEFAULT 'en',
  `email_notifications` tinyint(1) DEFAULT 1,
  `force_password_change` tinyint(1) DEFAULT 0,
  `password_last_changed` datetime DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`admin_id`, `admin_code`, `first_name`, `last_name`, `email`, `password_hash`, `role_id`, `status`, `last_login`, `login_count`, `failed_login_attempts`, `last_failed_login`, `lockout_until`, `two_factor_enabled`, `two_factor_secret`, `profile_picture`, `phone`, `timezone`, `language`, `email_notifications`, `force_password_change`, `password_last_changed`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'ADMIN-001', 'Admin', 'Cho Choong Kit', 'sarah.johnson@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 1, 'active', '2025-12-08 14:22:41', 4, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-12-08 06:22:41'),
(2, 'ADMIN-002', 'Michael', 'Chen', 'michael.chen@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 2, 'active', '2025-11-22 04:35:49', 1, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-24 17:11:28'),
(3, 'ADMIN-003', 'Emily', 'Rodriguez', 'emily.rodriguez@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 4, 'active', NULL, 1, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-12-07 18:50:38'),
(4, 'ADMIN-004', 'David', 'Kim', 'david.kim@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 3, 'active', NULL, 1, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-12-07 18:50:41'),
(15, 'ADMIN-005', 'GYZ', 'TZE', 'GYZ@gmail.com', '$2y$10$nyoPe8CzDE/WovWMY8h0duEbuiJ9MuibIN6pg4.Dm4wBR1ss8n45a', 7, 'active', NULL, 0, 0, NULL, NULL, 0, NULL, NULL, '', 'America/New_York', 'en', 1, 0, NULL, 1, '2025-12-07 19:20:15', '2025-12-07 19:21:49');

-- --------------------------------------------------------

--
-- Table structure for table `ai_performance_metrics`
--

CREATE TABLE `ai_performance_metrics` (
  `metric_id` int(11) NOT NULL,
  `model_version` varchar(50) DEFAULT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `accuracy` decimal(5,2) DEFAULT NULL,
  `precision_score` decimal(5,2) DEFAULT NULL,
  `recall_score` decimal(5,2) DEFAULT NULL,
  `f1_score` decimal(5,2) DEFAULT NULL,
  `auc_roc` decimal(5,2) DEFAULT NULL,
  `avg_response_time_ms` int(11) DEFAULT NULL,
  `success_rate` decimal(5,2) DEFAULT NULL,
  `error_rate` decimal(5,2) DEFAULT NULL,
  `total_requests` int(11) DEFAULT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ai_weightage`
--

CREATE TABLE `ai_weightage` (
  `weightage_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `cpu_weight` decimal(5,2) DEFAULT 0.00,
  `gpu_weight` decimal(5,2) DEFAULT 0.00,
  `battery_weight` decimal(5,2) DEFAULT 0.00,
  `display_weight` decimal(5,2) DEFAULT 0.00,
  `portability_weight` decimal(5,2) DEFAULT 0.00,
  `value_weight` decimal(5,2) DEFAULT 0.00,
  `total_weight` decimal(5,2) DEFAULT 100.00,
  `fairness_score` decimal(5,2) DEFAULT 0.00,
  `is_active` tinyint(1) DEFAULT 1,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `ai_weightage`
--

INSERT INTO `ai_weightage` (`weightage_id`, `persona_id`, `cpu_weight`, `gpu_weight`, `battery_weight`, `display_weight`, `portability_weight`, `value_weight`, `total_weight`, `fairness_score`, `is_active`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 15.00, 10.00, 25.00, 15.00, 20.00, 15.00, 100.00, 85.50, 1, NULL, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(2, 2, 30.00, 15.00, 15.00, 15.00, 10.00, 15.00, 100.00, 82.00, 1, NULL, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(3, 3, 20.00, 35.00, 10.00, 20.00, 5.00, 10.00, 100.00, 78.50, 0, NULL, '2025-11-21 19:46:15', '2025-12-02 15:29:49'),
(4, 4, 20.00, 40.00, 10.00, 15.00, 5.00, 10.00, 100.00, 75.00, 1, NULL, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(5, 5, 35.00, 15.00, 10.00, 15.00, 10.00, 15.00, 100.00, 80.00, 0, NULL, '2025-11-21 19:46:15', '2025-11-24 17:12:46'),
(8, 5, 35.00, 15.00, 10.00, 15.00, 10.00, 15.00, 100.00, 56.67, 1, NULL, '2025-11-24 17:12:46', '2025-11-24 17:12:46'),
(9, 3, 16.67, 16.67, 16.67, 16.67, 16.67, 16.67, 100.00, 100.00, 1, NULL, '2025-12-02 15:29:49', '2025-12-02 15:29:49');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_analytics`
--

CREATE TABLE `chatbot_analytics` (
  `analytics_id` int(11) NOT NULL,
  `date` date NOT NULL,
  `total_conversations` int(11) DEFAULT 0,
  `total_messages` int(11) DEFAULT 0,
  `avg_messages_per_session` decimal(4,2) DEFAULT 0.00,
  `avg_response_time_ms` int(11) DEFAULT 0,
  `intent_accuracy` decimal(5,2) DEFAULT 0.00,
  `resolution_rate` decimal(5,2) DEFAULT 0.00,
  `satisfaction_score` decimal(3,2) DEFAULT 0.00 COMMENT '1.00 to 5.00',
  `positive_sentiment_pct` decimal(5,2) DEFAULT 0.00,
  `neutral_sentiment_pct` decimal(5,2) DEFAULT 0.00,
  `negative_sentiment_pct` decimal(5,2) DEFAULT 0.00,
  `unrecognized_intent_count` int(11) DEFAULT 0,
  `fallback_count` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_analytics`
--

INSERT INTO `chatbot_analytics` (`analytics_id`, `date`, `total_conversations`, `total_messages`, `avg_messages_per_session`, `avg_response_time_ms`, `intent_accuracy`, `resolution_rate`, `satisfaction_score`, `positive_sentiment_pct`, `neutral_sentiment_pct`, `negative_sentiment_pct`, `unrecognized_intent_count`, `fallback_count`, `created_at`, `updated_at`) VALUES
(1, '2025-11-22', 156, 655, 4.20, 2300, 94.20, 92.10, 4.60, 68.50, 25.00, 6.50, 12, 8, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(2, '2025-11-21', 142, 597, 4.21, 2250, 93.80, 91.50, 4.55, 67.00, 26.50, 6.50, 14, 10, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(3, '2025-11-20', 138, 579, 4.20, 2280, 94.00, 92.00, 4.58, 68.00, 25.50, 6.50, 11, 7, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(4, '2025-11-19', 145, 609, 4.20, 2320, 93.50, 91.20, 4.52, 66.50, 27.00, 6.50, 15, 11, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(5, '2025-11-18', 151, 634, 4.20, 2290, 94.10, 92.20, 4.61, 69.00, 24.50, 6.50, 10, 6, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(6, '2025-11-17', 147, 617, 4.20, 2310, 93.90, 91.80, 4.57, 67.50, 26.00, 6.50, 13, 9, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(7, '2025-11-16', 149, 626, 4.20, 2270, 94.20, 92.30, 4.63, 69.50, 24.00, 6.50, 9, 5, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(8, '2025-11-24', 4, 36, 9.00, 10260, 0.00, 75.00, 0.00, 50.00, 50.00, 0.00, 36, 0, '2025-11-24 20:36:32', '2025-11-24 20:36:32'),
(9, '2025-11-25', 8, 25, 3.10, 2325, 100.00, 37.50, 0.00, 0.00, 100.00, 0.00, 25, 0, '2025-11-24 20:36:48', '2025-11-24 20:36:48');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_training_data`
--

CREATE TABLE `chatbot_training_data` (
  `data_id` int(11) NOT NULL,
  `source_name` varchar(150) NOT NULL,
  `source_type` varchar(50) NOT NULL COMMENT 'conversation_logs, intent_examples, faq_database, product_descriptions, manual_upload',
  `file_path` varchar(255) DEFAULT NULL,
  `record_count` int(11) DEFAULT 0,
  `quality_score` decimal(5,2) DEFAULT NULL COMMENT 'Data quality percentage',
  `status` enum('active','inactive','needs_review','processing') DEFAULT 'active',
  `last_synced_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `chatbot_training_data`
--

INSERT INTO `chatbot_training_data` (`data_id`, `source_name`, `source_type`, `file_path`, `record_count`, `quality_score`, `status`, `last_synced_at`, `created_at`, `updated_at`) VALUES
(1, 'Conversation Logs', 'conversation_logs', NULL, 8947, 95.00, 'active', '2025-11-22 03:57:10', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(2, 'Intent Examples', 'intent_examples', NULL, 2456, 98.00, 'active', '2025-11-22 03:57:10', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(3, 'FAQ Database', 'faq_database', NULL, 1234, 87.00, 'needs_review', '2025-11-17 03:57:10', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(4, 'Product Descriptions', 'product_descriptions', NULL, 789, 92.00, 'active', '2025-11-22 03:57:10', '2025-11-21 19:57:10', '2025-11-21 19:57:10');

-- --------------------------------------------------------

--
-- Table structure for table `chat_history`
--

CREATE TABLE `chat_history` (
  `chat_id` int(11) NOT NULL,
  `session_id` varchar(255) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `sender` enum('user','bot','system') NOT NULL,
  `message` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chat_history`
--

INSERT INTO `chat_history` (`chat_id`, `session_id`, `user_id`, `sender`, `message`, `timestamp`) VALUES
(1, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'user', 'gaming', '2025-09-27 20:10:48'),
(2, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'bot', 'For gaming, I recommend looking at models with dedicated GPUs like the Nvidia RTX series. Check our \"Gaming\" category!', '2025-09-27 20:10:48'),
(3, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'user', 'i want you to recoemded', '2025-09-27 20:11:01'),
(4, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-09-27 20:11:01'),
(5, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'user', 'price', '2025-09-27 20:11:09'),
(6, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'bot', 'Our laptops range widely in price. You can use the filters on the Products page to find one in your budget!', '2025-09-27 20:11:09'),
(7, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'user', 'human agent\'', '2025-09-27 20:11:20'),
(8, 'lkiq5gjh8hfhiee5q9jvb95mma', 1, 'bot', 'Please wait while I connect you to a live agent... (This is a simulation. In a real system, this would trigger a notification.)', '2025-09-27 20:11:20'),
(9, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'user', 'asda', '2025-11-21 18:09:17'),
(10, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-11-21 18:09:17'),
(11, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'user', 'asdad', '2025-11-21 18:09:18'),
(12, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-11-21 18:09:18'),
(13, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'user', 'gaming', '2025-11-21 18:09:21'),
(14, 'p6lroo5hg8dt0f2k858ctsjfll', NULL, 'bot', 'For gaming, I recommend looking at models with dedicated GPUs like the Nvidia RTX series. Check our \"Gaming\" category!', '2025-11-21 18:09:21'),
(15, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hello', '2025-12-01 04:02:14'),
(16, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Hello there! How can I help you find the perfect laptop today?', '2025-12-01 04:02:14'),
(17, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'i love u', '2025-12-01 04:02:19'),
(18, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-12-01 04:02:19'),
(19, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'asd', '2025-12-01 04:02:26'),
(20, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-12-01 04:02:26'),
(21, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'sad', '2025-12-01 04:02:27'),
(22, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-12-01 04:02:27'),
(23, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'recommend a laptop', '2025-12-01 04:02:42'),
(24, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Based on your request, you might be interested in: **Creator Z16**, **Spectre x360**, **ROG Strix G15**. You can find more on our Products page!', '2025-12-01 04:02:42'),
(25, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hellp', '2025-12-01 04:08:22'),
(26, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-12-01 04:08:22'),
(27, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'sss', '2025-12-01 07:47:59'),
(28, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m not sure how to answer that. You can ask me to \'recommend a laptop\' or ask about \'gaming\' or \'price\'. You can also ask to speak to a \'human agent\'.', '2025-12-01 07:47:59'),
(29, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:50:59'),
(30, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:50:59'),
(31, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:51:07'),
(32, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:51:07'),
(33, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:51:55'),
(34, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:51:55'),
(35, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:51:59'),
(36, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:51:59'),
(37, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:52:07'),
(38, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:52:07'),
(39, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:52:37'),
(40, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404', '2025-12-02 15:52:37'),
(41, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:56:08'),
(42, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 15:56:08'),
(43, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'gpt-oss:20b', '2025-12-02 15:56:47'),
(44, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 15:56:47'),
(45, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'C:\\xampp\\htdocs\\fyp', '2025-12-02 15:56:49'),
(46, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 15:56:49'),
(47, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:56:52'),
(48, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 15:56:52'),
(49, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Hello, who are you?', '2025-12-02 15:57:02'),
(50, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 15:57:02'),
(51, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'helo', '2025-12-02 15:58:11'),
(52, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 15:58:11'),
(53, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'helo', '2025-12-02 15:58:32'),
(54, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 15:58:32'),
(55, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 's', '2025-12-02 15:58:43'),
(56, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Connection error: Failed to connect to 127.0.0.1 port 11434 after 2022 ms: Couldn\'t connect to server', '2025-12-02 15:58:47'),
(57, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 's', '2025-12-02 15:58:50'),
(58, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 15:58:50'),
(59, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 's', '2025-12-02 15:59:39'),
(60, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 15:59:39'),
(61, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hello', '2025-12-02 16:01:10'),
(62, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', '2025-12-02 16:01:10'),
(63, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:01:27'),
(64, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 16:01:27'),
(65, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hello', '2025-12-02 16:01:29'),
(66, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', '2025-12-02 16:01:29'),
(67, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:01:38'),
(68, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:120b-cloud\' not found\"}', '2025-12-02 16:01:38'),
(69, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:23:13'),
(70, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:23:13'),
(71, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:23:50'),
(72, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:23:50'),
(73, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:25'),
(74, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:25'),
(75, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:25'),
(76, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:25'),
(77, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:26'),
(78, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:26'),
(79, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:26'),
(80, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:26'),
(81, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:27'),
(82, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:27'),
(83, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:27'),
(84, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:27'),
(85, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:24:28'),
(86, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Ollama API returned status code: 404 Body: {\"error\":\"model \'gpt-oss:20b-cloud\' not found\"}', '2025-12-02 16:24:28'),
(87, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:29:58'),
(88, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hello', '2025-12-02 16:30:33'),
(89, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'asd', '2025-12-02 16:30:35'),
(90, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'hello', '2025-12-02 16:30:44'),
(91, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', '2025-12-02 16:30:44'),
(92, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:30:51'),
(93, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Here’s a quick rundown of every laptop we currently have in stock:\n\n| # | Model | Brand | Price | Key Specs | Ideal For |\n|---|-------|-------|------|-----------|-----------|\n| 1 | IdeaPad Slim 3 | Lenovo | $649.00 | AMD Ryzen 5, Integrated Radeon, 8 GB RAM | Students |\n| 2 | HP Pavilion 15 | HP | $799.99 | Intel Core i5‑1235U, Iris Xe, 8 GB RAM | Students |\n| 3 | ASUS ROG Strix G15 | ASUS | $1,299.99 | AMD Ryzen 7 7735HS, NVIDIA RTX 4060, 16 GB RAM | Gaming |\n\nAll of them come with standard free shipping (when you spend over $1,000), a 30‑day return policy, and manufacturer warranties per brand. Let me know if you’d like more details on any of these or help placing an order!', '2025-12-02 16:30:58'),
(94, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:31:13'),
(95, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Sure! Here are the laptops we currently have in stock:\n\n| Laptop | Price | Key Specs | ideal for |\n|--------|-------|-----------|-----------|\n| **Lenovo IdeaPad Slim 3** | **$649.00** | AMD Ryzen 5 • Integrated Radeon graphics • 8 GB RAM | Students & everyday use |\n| **HP Pavilion 15** | **$799.99** | Intel Core i5‑1235U • Intel Iris Xe graphics • 8 GB RAM | Students & productivity |\n| **ASUS ROG Strix G15** | **$1,299.99** | AMD Ryzen 7 7735HS • NVIDIA RTX 4060 • 16 GB RAM | Gaming & high‑performance tasks |\n\nLet me know if you’d like more details about any model or help picking the right one for your needs!', '2025-12-02 16:31:20'),
(96, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'who is xi jing ping', '2025-12-02 16:32:03'),
(97, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'I\'m sorry, I can only help with laptops and store-related questions. How can I assist you today?', '2025-12-02 16:32:06'),
(98, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop u store got?', '2025-12-02 16:35:31'),
(99, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Here are the laptops we currently have in stock:\n\n- **Lenovo IdeaPad Slim 3** – $649  \n  *AMD Ryzen 5, integrated Radeon graphics, 8 GB RAM* – ideal for students and everyday use.\n\n- **HP Pavilion 15** – $799.99  \n  *Intel Core i5‑1235U, Intel Iris Xe graphics, 8 GB RAM* – a solid choice for students who need reliable performance.\n\n- **ASUS ROG Strix G15** – $1,299.99  \n  *AMD Ryzen 7 7735HS, NVIDIA RTX 4060, 16 GB RAM* – built for gaming and demanding creative work.', '2025-12-02 16:35:36'),
(100, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'only 3 product only ?', '2025-12-02 16:35:49'),
(101, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'We have a wide selection of laptops across many brands and price ranges—definitely more than three! To make it easy for you, I can highlight three top picks that match your needs and budget. \n\nJust let me know:\n\n1. Your primary use (gaming, video editing, business, school, etc.)  \n2. Your budget range  \n3. Any must‑have features (e.g., high‑refresh‑rate screen, dedicated GPU, extra storage)\n\nOnce I have those details, I’ll recommend three laptops that fit you perfectly. How can I help?', '2025-12-02 16:35:55'),
(102, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'no need list out all the laptop u got', '2025-12-02 16:36:07'),
(103, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Sure! I can suggest a laptop that fits your needs without showing the whole inventory.\n\nCould you let me know:\n\n1. Primary use (gaming, video editing, business, school, etc.)  \n2. Your budget range  \n3. Any must‑have features (screen size, touch, specific graphics card, storage type, etc.)\n\nThat way I can recommend the perfect model for you.', '2025-12-02 16:36:11'),
(104, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'I want you list out all the laptop u understand?', '2025-12-02 16:36:31'),
(105, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Here are the laptops we currently have in stock:\n\n- **IdeaPad Slim 3 (Lenovo) – $649**  \n  * AMD Ryzen 5 processor  \n  * Integrated Radeon graphics  \n  * 8 GB RAM – great for students and everyday tasks  \n\n- **HP Pavilion 15 (HP) – $799.99**  \n  * Intel Core i5‑1235U processor  \n  * Intel Iris Xe graphics  \n  * 8 GB RAM – a solid choice for students who need a bit more performance  \n\n- **ASUS ROG Strix G15 (ASUS) – $1,299.99**  \n  * AMD Ryzen 7 7735HS processor  \n  * NVIDIA RTX 4060 graphics  \n  * 16 GB RAM – built for gaming and demanding creative work  \n\nLet me know if you’d like more details or help picking the right one for your needs!', '2025-12-02 16:36:35'),
(106, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop', '2025-12-02 16:39:25'),
(107, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Sure! Here’s the full lineup of laptops we currently have in stock:\n\n| Laptop | Brand | Price | Key Specs | Ideal For |\n|--------|-------|-------|-----------|-----------|\n| **IdeaPad Slim 3** | Lenovo | **$649.00** | AMD Ryzen 5, Integrated Radeon graphics, 8 GB RAM | Students & everyday use |\n| **HP Pavilion 15** | HP | **$799.99** | Intel Core i5‑1235U, Intel Iris Xe graphics, 8 GB RAM | Students & productivity |\n| **ASUS ROG Strix G15** | ASUS | **$1,299.99** | AMD Ryzen 7 7735HS, NVIDIA RTX 4060, 16 GB RAM | Gaming & high‑performance tasks |\n\nLet me know if you’d like more details on any model or help choosing the right one for you!', '2025-12-02 16:39:29'),
(108, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'can u list out all the laptop', '2025-12-02 16:42:35'),
(109, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', 'Sure! Here’s the full lineup of laptops we currently have in stock:\n\n| Laptop (Model) | Brand | Price | Key Specs | Ideal For |\n|---|---|---|---|---|\n| IdeaPad Slim 3 | Lenovo | $649.00 | AMD Ryzen 5, Integrated Radeon graphics, 8 GB RAM | Students & everyday use |\n| HP Pavilion 15 | HP | $799.99 | Intel Core i5‑1235U, Intel Iris Xe graphics, 8 GB RAM | Students & productivity |\n| ASUS ROG Strix G15 | ASUS | $1,299.99 | AMD Ryzen 7 7735HS, NVIDIA RTX 4060, 16 GB RAM | Gaming & high‑performance tasks |\n\nLet me know if you’d like more details on any of these or help picking the right one for you!', '2025-12-02 16:42:39'),
(110, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'user', 'Suggest a laptop for gaming under $2000', '2025-12-02 16:47:58'),
(111, 'aqon9fqa1i1mqr08al51mrhb8g', 4, 'bot', '| # | Laptop | Price | Key Specs | Why it\'s a fit |\n|---|---|---|---|---|\n| 1 | ASUS ROG Strix G15 (Ryzen 7) | $1,299.99 | • AMD Ryzen 7 7735HS<br>• NVIDIA GeForce RTX 4060<br>• 16 GB DDR5 RAM<br>• 512 GB SSD | • Excellent 1080p/1440p gaming performance<br>• Strong GPU for current titles under $2000<br>• Good balance of speed and battery life |\n| 2 | ASUS ROG Strix G15 (Ryzen 9) | $1,899.99 | • AMD Ryzen 9 ## (high‑end)<br>• NVIDIA GeForce RTX 3070<br>• 16 GB DDR5 RAM<br>• 1 TB SSD | • Higher‑tier GPU for ultra‑high settings and ray tracing<br>• Future‑proof performance for demanding games<br>• Large storage for game libraries |\n\n**Quick recommendation**  \nIf you want solid performance at a great price, the **ASUS ROG Strix G15 with Ryzen 7 and RTX 4060** is the best choice. It delivers excellent gaming FPS at 1080p–1440p while staying well below $2000. If you need the extra power for ultra‑high settings or future‑proofing, the **Ryzen 9/RTX 3070** model is a strong upgrade still within your budget.', '2025-12-02 16:48:04');

-- --------------------------------------------------------

--
-- Table structure for table `conversations`
--

CREATE TABLE `conversations` (
  `conversation_id` int(11) NOT NULL,
  `session_id` varchar(50) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `user_ip` varchar(45) DEFAULT NULL,
  `source` varchar(50) DEFAULT 'web' COMMENT 'web, mobile_app, etc.',
  `started_at` datetime NOT NULL,
  `ended_at` datetime DEFAULT NULL,
  `duration_seconds` int(11) DEFAULT 0,
  `message_count` int(11) DEFAULT 0,
  `user_message_count` int(11) DEFAULT 0,
  `bot_message_count` int(11) DEFAULT 0,
  `sentiment` varchar(20) DEFAULT 'neutral' COMMENT 'positive, neutral, negative',
  `sentiment_score` decimal(3,2) DEFAULT 0.00,
  `outcome` varchar(50) DEFAULT NULL COMMENT 'recommendation_made, product_inquiry, session_abandoned, order_placed, information_request',
  `outcome_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional outcome details' CHECK (json_valid(`outcome_data`)),
  `satisfaction_rating` decimal(2,1) DEFAULT NULL COMMENT '1.0 to 5.0',
  `flagged` tinyint(1) DEFAULT 0,
  `flag_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversations`
--

INSERT INTO `conversations` (`conversation_id`, `session_id`, `user_id`, `user_ip`, `source`, `started_at`, `ended_at`, `duration_seconds`, `message_count`, `user_message_count`, `bot_message_count`, `sentiment`, `sentiment_score`, `outcome`, `outcome_data`, `satisfaction_rating`, `flagged`, `flag_reason`, `created_at`, `updated_at`) VALUES
(1, 'chat_69246781db096_59dea13fd37de094', NULL, '::1', 'web', '2025-11-24 22:11:13', NULL, 120, 10, 5, 5, 'positive', 0.00, 'recommendation_made', NULL, NULL, 0, NULL, '2025-11-24 14:11:13', '2025-11-24 15:20:22'),
(2, 'chat_69246e180116e_2ff71fda376d5138', 4, '::1', 'web', '2025-11-24 22:39:20', NULL, 180, 12, 6, 6, 'neutral', 0.00, 'product_inquiry', NULL, NULL, 0, NULL, '2025-11-24 14:39:20', '2025-11-24 15:20:24'),
(3, 'chat_69246ffe114f9_85e24ca244fe61a1', 4, '::1', 'web', '2025-11-24 22:47:26', NULL, 300, 36, 18, 18, 'positive', 0.00, 'recommendation_made', NULL, NULL, 1, 'damn', '2025-11-24 14:47:26', '2025-11-24 16:06:08'),
(4, 'chat_69248068319a8_4d3557863899ac34', 4, '::1', 'web', '2025-11-24 23:57:28', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 15:57:28', '2025-11-24 15:57:28'),
(5, 'chat_692487b853ac7_02a00ad332719097', NULL, '::1', 'web', '2025-11-25 00:28:40', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 16:28:40', '2025-11-24 16:28:40'),
(6, 'chat_692487f8bcb82_7055eacdb4d05f65', 4, '::1', 'web', '2025-11-25 00:29:44', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 16:29:44', '2025-11-24 16:29:44'),
(7, 'chat_6924b409b9dbb_cdb290578793ea81', 4, '::1', 'web', '2025-11-25 03:37:45', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 19:37:45', '2025-11-24 19:37:45'),
(8, 'chat_6924b5b8cc24e_d25260b2950db2f3', 4, '::1', 'web', '2025-11-25 03:44:56', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 19:44:56', '2025-11-24 19:44:56'),
(9, 'chat_6924b7bb24db2_7b52c5d6caac29a8', 4, '::1', 'web', '2025-11-25 03:53:31', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 19:53:31', '2025-11-24 19:53:31'),
(10, 'chat_6924b8469f746_a332d9ee640ac6b1', 4, '::1', 'web', '2025-11-25 03:55:50', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 19:55:50', '2025-11-24 19:55:50'),
(11, 'chat_6924bb1a1883b_4aac790a4446ab1b', 4, '::1', 'web', '2025-11-25 04:07:54', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 20:07:54', '2025-11-24 20:07:54'),
(12, 'chat_6924bb29e7372_0520256d7ce96511', 4, '::1', 'web', '2025-11-25 04:08:09', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 20:08:09', '2025-11-24 20:08:09'),
(13, 'chat_6924c27e74666_17c0aa3fa3f9c363', 4, '::1', 'web', '2025-11-25 04:39:26', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 20:39:26', '2025-11-24 20:39:26'),
(14, 'chat_6924c284b74a2_fbae83c1311c5114', 4, '::1', 'web', '2025-11-25 04:39:32', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-24 20:39:32', '2025-11-24 20:39:32'),
(15, 'chat_6925b55550d67_e15a3a14847bc756', 4, '::1', 'web', '2025-11-25 21:55:33', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-11-25 13:55:33', '2025-11-25 13:55:33'),
(16, 'chat_692503d67f3ee_8976cb809c25c205', 4, '::1', 'web', '2025-12-03 01:17:05', NULL, 0, 5, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 17:17:05', '2025-12-02 17:22:52'),
(17, 'chat_692f2577101a7_2c1b26ab3b30cf65', 4, '::1', 'web', '2025-12-03 01:44:23', NULL, 0, 6, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 17:44:23', '2025-12-02 17:49:29'),
(18, 'chat_692f26c7c010e_68233206bf39a780', 4, '::1', 'web', '2025-12-03 01:49:59', NULL, 1644, 5, 0, 0, 'neutral', 0.00, 'general_chat', NULL, NULL, 0, NULL, '2025-12-02 17:49:59', '2025-12-02 18:17:27'),
(19, 'chat_692f2d6a5ee15_9aa9efb95330a180', 4, '::1', 'web', '2025-12-03 02:18:18', NULL, 0, 1, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 18:18:18', '2025-12-02 18:18:18'),
(20, 'chat_692f2d8300e24_829515c16c98da82', 4, '::1', 'web', '2025-12-03 02:18:43', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 18:18:43', '2025-12-02 18:18:43'),
(21, 'chat_692f2da269488_9a26044b586d4b06', 4, '::1', 'web', '2025-12-03 02:19:14', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 18:19:14', '2025-12-02 18:19:14'),
(22, 'chat_692f2dddeef23_deaaa511d61f5e9b', 4, '::1', 'web', '2025-12-03 02:20:13', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-02 18:20:13', '2025-12-02 18:20:13'),
(23, 'chat_692f18cb0d84f_caa9f98f5311ebb6', 6, '::1', 'web', '2025-12-07 16:37:18', NULL, 0, 0, 0, 0, 'neutral', 0.00, 'general_chat', NULL, NULL, 0, NULL, '2025-12-07 08:37:18', '2025-12-07 08:37:23'),
(24, 'chat_69364fc6ec1f8_4cdcb665d9db2528', 6, '::1', 'web', '2025-12-08 12:10:46', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-08 04:10:46', '2025-12-08 04:10:46'),
(25, 'chat_6936ba4ec327c_fb0c492fd62bd1b3', 6, '::1', 'web', '2025-12-08 19:45:18', NULL, 0, 0, 0, 0, 'neutral', 0.00, NULL, NULL, NULL, 0, NULL, '2025-12-08 11:45:18', '2025-12-08 11:45:18');

-- --------------------------------------------------------

--
-- Table structure for table `conversation_messages`
--

CREATE TABLE `conversation_messages` (
  `message_id` int(11) NOT NULL,
  `conversation_id` int(11) NOT NULL,
  `message_type` enum('user','bot') NOT NULL,
  `message_content` text NOT NULL,
  `intent_detected` varchar(100) DEFAULT NULL,
  `intent_confidence` decimal(4,3) DEFAULT NULL COMMENT '0.000 to 1.000',
  `entities_extracted` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Extracted entities from message' CHECK (json_valid(`entities_extracted`)),
  `timestamp` datetime NOT NULL,
  `response_time_ms` int(11) DEFAULT NULL COMMENT 'Time taken to generate response in milliseconds',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `conversation_messages`
--

INSERT INTO `conversation_messages` (`message_id`, `conversation_id`, `message_type`, `message_content`, `intent_detected`, `intent_confidence`, `entities_extracted`, `timestamp`, `response_time_ms`, `created_at`) VALUES
(1, 1, 'user', 'as', NULL, NULL, NULL, '2025-11-24 22:11:16', NULL, '2025-11-24 14:11:16'),
(2, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Ollama API returned status code: 404', NULL, NULL, NULL, '2025-11-24 22:11:16', 4, '2025-11-24 14:11:16'),
(3, 1, 'user', 'gay', NULL, NULL, NULL, '2025-11-24 22:13:45', NULL, '2025-11-24 14:13:45'),
(4, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Ollama API returned status code: 404', NULL, NULL, NULL, '2025-11-24 22:13:45', 1, '2025-11-24 14:13:45'),
(5, 1, 'user', 'fck u', NULL, NULL, NULL, '2025-11-24 22:19:31', NULL, '2025-11-24 14:19:31'),
(6, 1, 'bot', 'I can\'t respond to that request. Is there anything else I can help you with?', NULL, NULL, NULL, '2025-11-24 22:19:42', 10448, '2025-11-24 14:19:42'),
(7, 1, 'user', 'Hello', NULL, NULL, NULL, '2025-11-24 22:20:34', NULL, '2025-11-24 14:20:34'),
(8, 1, 'bot', 'How can I assist you today? Are you looking for a new laptop or need some recommendations? What will be using the laptop for (gaming, work, school, creative projects)?', NULL, NULL, NULL, '2025-11-24 22:20:52', 18036, '2025-11-24 14:20:52'),
(9, 1, 'user', 'I need a gaming laptop', NULL, NULL, NULL, '2025-11-24 22:21:13', NULL, '2025-11-24 14:21:13'),
(10, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Connection error: Operation timed out after 30002 milliseconds with 0 bytes received', NULL, NULL, NULL, '2025-11-24 22:21:43', 30003, '2025-11-24 14:21:43'),
(11, 1, 'user', 'I need a gaming laptop', NULL, NULL, NULL, '2025-11-24 22:21:55', NULL, '2025-11-24 14:21:55'),
(12, 1, 'bot', 'Cool! For a gaming laptop, we\'ll want to focus on powerful specs.\n\nCan you tell me what\'s your budget for the laptop? Also, are you looking for any specific features (e.g., high refresh rate, long battery life, portability)?', NULL, NULL, NULL, '2025-11-24 22:22:19', 23598, '2025-11-24 14:22:19'),
(13, 1, 'user', 'My budget is $1500 and What\'s the difference between RTX 4060 and RTX 4070?', NULL, NULL, NULL, '2025-11-24 22:22:53', NULL, '2025-11-24 14:22:53'),
(14, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Connection error: Operation timed out after 30009 milliseconds with 0 bytes received', NULL, NULL, NULL, '2025-11-24 22:23:23', 30010, '2025-11-24 14:23:23'),
(15, 1, 'user', 'My budget is $1500 and What\'s the difference between RTX 4060 and RTX 4070?', NULL, NULL, NULL, '2025-11-24 22:23:42', NULL, '2025-11-24 14:23:42'),
(16, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Connection error: Operation timed out after 30007 milliseconds with 0 bytes received', NULL, NULL, NULL, '2025-11-24 22:24:12', 30008, '2025-11-24 14:24:12'),
(17, 1, 'user', 'My budget is $1500 and What\'s the difference between RTX 4060 and RTX 4070?', NULL, NULL, NULL, '2025-11-24 22:25:01', NULL, '2025-11-24 14:25:01'),
(18, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Connection error: Operation timed out after 60005 milliseconds with 0 bytes received - Please ensure Ollama is running (run \"ollama serve\" in terminal). First request may take 30-60 seconds as model loads into memory.', NULL, NULL, NULL, '2025-11-24 22:26:01', 60005, '2025-11-24 14:26:01'),
(19, 1, 'user', 'My budget is $1500', NULL, NULL, NULL, '2025-11-24 22:26:32', NULL, '2025-11-24 14:26:32'),
(20, 1, 'bot', 'I apologize, but I\'m having trouble connecting to my AI service right now. Please try again in a moment. Error: Connection error: Operation timed out after 60015 milliseconds with 0 bytes received - Please ensure Ollama is running (run \"ollama serve\" in terminal). First request may take 30-60 seconds as model loads into memory.', NULL, NULL, NULL, '2025-11-24 22:27:32', 60016, '2025-11-24 14:27:32'),
(21, 1, 'user', 'My budget is $1500', NULL, NULL, NULL, '2025-11-24 22:30:55', NULL, '2025-11-24 14:30:55'),
(22, 1, 'bot', 'I’d love to help narrow it down! Let’s pick a few solid choices under $1,500, then you can tell me which fits your play style and size preference.\n\n1. **Lenovo Legion 5 (15”) – RTX 4060 8 GB**  \n   * 144 Hz display, 16 GB RAM, 512 GB SSD  \n   * Great balance of power and battery life for 1080p gaming.\n\n2. **Dell G15 (15.6”) – RTX 4060 Ti 8 GB**  \n   * 165 Hz screen, 16 GB RAM, 512 GB SSD  \n   * Slighted up graphics and higher refresh rate for smoother action.\n\n3. **MSI GF66 Thin (15.6”) – RTX 4060 8 GB**  \n   * 144 Hz display, 8 GB RAM, 1 TB HDD + 256 GB SSD  \n   * Slim, lightweight, good for on‑the‑go play.\n\n---\n\n**Quick note on RTX 4060 vs 4070:**  \n*RTX 4070* packs ~30–40 % more CUDA cores and faster clock speeds, giving you 15–20 % better frame rates, especially at 1440p. It also usually has 8 GB GDDR6 but newer; however, with a $1,500 budget it may push the price ceiling, so the 4060/4060 Ti stays sweet spot.  \n\nDo any of these specs match what you’re looking for? Feel free to tweak screen size, RAM, etc. 😊', NULL, NULL, NULL, '2025-11-24 22:31:00', 5419, '2025-11-24 14:31:00'),
(23, 1, 'user', 'MSI GF66 Thin (15.6”) – RTX 4060 8 GB how much', NULL, NULL, NULL, '2025-11-24 22:34:22', NULL, '2025-11-24 14:34:22'),
(24, 1, 'bot', 'Here’s the latest price snapshot for the **MSI GF66‑Thin (15.6”) – RTX 4060 8 GB**:\n\n- **MSI GF66‑Thin 11UG‑XXX** – **≈ $1,349 USD** (MSRP).  \n- Some retailers (Amazon, Newegg, Best‑Buy) are running it between **$1,280 – $1,390** depending on sales, taxes, and shipping.\n\nIt sits comfortably under your $1,500 budget and gives you a solid 1080p‑to‑1440p gaming experience with a slim, portable chassis. If you’d like to compare other options or need help picking the best retailer, just let me know!', NULL, NULL, NULL, '2025-11-24 22:34:29', 6572, '2025-11-24 14:34:29'),
(25, 2, 'user', 'do u know xi jing ping', NULL, NULL, NULL, '2025-11-24 22:39:29', NULL, '2025-11-24 14:39:29'),
(26, 2, 'bot', 'Xi Jinping is the current paramount leader of China.  \n- He has been the General Secretary of the Communist Party of China (CPC) since 2012 and the President of the People’s Republic of China since 2013.  \n- He chaired the CPC’s Politburo Standing Committee, the highest decision‑making body in China, and has overseen major political, economic, and foreign‑policy initiatives.  \n- His tenure has seen efforts to strengthen party control, pursue a more assertive foreign policy, and push for technological self‑reliance, while also continuing economic reforms and countering corruption.', NULL, NULL, NULL, '2025-11-24 22:39:33', 4347, '2025-11-24 14:39:33'),
(27, 2, 'user', 'what are the product is available for this store', NULL, NULL, NULL, '2025-11-24 22:40:25', NULL, '2025-11-24 14:40:25'),
(28, 2, 'bot', 'Here are all the laptops we currently have in stock:\n\n| Model | Price | CPU | GPU | RAM | Storage | Display | Use Case |\n|-------|-------|-----|-----|-----|---------|---------|----------|\n| Lenovo IdeaPad Slim 3 | $649.00 | AMD Ryzen 5 | Integrated Radeon | 8 GB | 512 GB SSD | 15.60\" | Student |\n| HP Pavilion 15 | $799.00 | Intel Core i5 | Intel Iris Xe | 12 GB | 512 GB SSD | 15.60\" | General |\n| HP Spectre x360 | $1,450.00 | Intel Core i7 | Intel Iris Xe | 16 GB | 512 GB SSD | 13.50\" | Business |\n| Asus ROG Strix G15 | $1,899.99 | AMD Ryzen 9 | NVIDIA RTX 3070 | 16 GB | 1,024 GB SSD | 15.60\" | Gaming |\n| Asus ZenBook Duo | $1,999.00 | Intel Core i9 | NVIDIA RTX 3070 | 32 GB | 1,024 GB SSD | 14.00\" | Business |\n', NULL, NULL, NULL, '2025-11-24 22:40:29', 3306, '2025-11-24 14:40:29'),
(29, 2, 'user', 'what are the product is available for this store', NULL, NULL, NULL, '2025-11-24 22:40:47', NULL, '2025-11-24 14:40:47'),
(30, 2, 'bot', '**Our current laptop lineup**\n\n| Model | Price | Key Specs | Ideal Use |\n|-------|-------|-----------|-----------|\n| **Lenovo IdeaPad Slim 3** | $649.00 | Ryzen 5, 8 GB RAM, 512 GB SSD, 15.6\" | Students & everyday tasks |\n| **HP Pavilion 15** | $799.00 | i5 CPU, Iris Xe, 12 GB RAM, 512 GB SSD, 15.6\" | All‑round work & entertainment |\n| **HP Spectre x360** | $1,450.00 | i7 CPU, Iris Xe, 16 GB RAM, 512 GB SSD, 13.5\" 2‑in‑1 | Business & productivity |\n| **Asus ROG Strix G15** | $1,899.99 | Ryzen 9, RTX 3070, 16 GB RAM, 1 TB SSD, 15.6\" | High‑end gaming |\n| **Asus ZenBook Duo** | $1,999.00 | i9 CPU, RTX 3070, 32 GB RAM, 1 TB SSD, 14\" / 12.6\" dual display | Power‑users & multitaskers |\n\nLet me know your budget or specific needs, and I’ll narrow it down for you!', NULL, NULL, NULL, '2025-11-24 22:40:51', 4030, '2025-11-24 14:40:51'),
(31, 2, 'user', 'got the MacBook Pro 16?', NULL, NULL, NULL, '2025-11-24 22:41:32', NULL, '2025-11-24 14:41:32'),
(32, 2, 'bot', 'I’m sorry, but we don’t carry the MacBook Pro 16.  \nIf you’re looking for a large, high‑performance laptop, you might want to consider the **Asus ZenBook Duo** – 14” primary screen + 12.6” secondary display, i9 CPU, 32 GB RAM, RTX 3070 GPU, and 1 TB SSD. It’s great for power‑user tasks and multitasking.  Feel free to let me know your preferences or budget, and I’ll fine‑tune the recommendation!', NULL, NULL, NULL, '2025-11-24 22:41:35', 2502, '2025-11-24 14:41:35'),
(33, 2, 'user', 'your store got MacBook Pro 16?', NULL, NULL, NULL, '2025-11-24 22:42:02', NULL, '2025-11-24 14:42:02'),
(34, 2, 'bot', 'I’m sorry, but we don’t stock the MacBook Pro 16.  \nThe best match we have for a large, high‑performance laptop is the **Asus ZenBook Duo** (14″ main screen + 12.6″ secondary display, i9 CPU, 32 GB RAM, RTX 3070, 1 TB SSD). It’s great for demanding work, multitasking, and creative projects. Let me know if this fits your needs or if you’d like to explore other options!', NULL, NULL, NULL, '2025-11-24 22:42:04', 2022, '2025-11-24 14:42:04'),
(35, 2, 'user', 'Asus ZenBook Duo  how much', NULL, NULL, NULL, '2025-11-24 22:42:18', NULL, '2025-11-24 14:42:18'),
(36, 2, 'bot', 'The **Asus ZenBook Duo** is priced at **$1,999.00**.  \n\nIt features an Intel Core i9 processor, NVIDIA RTX 3070 graphics, 32 GB of RAM, a 1 TB SSD, and a 14″ main screen plus a 12.6″ secondary display for multitasking and creative work.  \n\nLet me know if you’d like more details or any comparison with our other models!', NULL, NULL, NULL, '2025-11-24 22:42:20', 1877, '2025-11-24 14:42:20'),
(37, 3, 'user', 'got macbook pro 16?', NULL, NULL, NULL, '2025-11-24 22:47:35', NULL, '2025-11-24 14:47:35'),
(38, 3, 'bot', 'I’m sorry, we don’t currently carry a MacBook Pro 16‑inch.  \nWe do have a similar high‑performance lineup that might meet your needs:\n\n| Laptop | Price | Why it matches a MacBook Pro style |\n|--------|-------|------------------------------------|\n| **Asus ZenBook Duo** | $1,999 | 16‑core i9, 32 GB RAM, RTX 3070 – great for multitasking & creative work |\n| **HP Spectre x360** | $1,450 | 11th‑gen i7, 16 GB RAM, 512 GB SSD – sleek 2‑in‑1 with solid performance |\n| **Asus ROG Strix G15** | $1,900 | Ryzen 9 + RTX 3070 for gaming/graphics work |\n\nCould you let me know your budget range and primary use (work, gaming, creative projects, etc.)? That’ll help me narrow down the best fit.', NULL, NULL, NULL, '2025-11-24 22:47:40', 5503, '2025-11-24 14:47:40'),
(39, 3, 'user', '\"Show me all laptops\"', NULL, NULL, NULL, '2025-11-24 22:50:18', NULL, '2025-11-24 14:50:18'),
(40, 3, 'bot', 'Here’s every laptop we currently have in stock:\n\n| # | Model | Price | CPU | GPU | RAM | Storage | Display | Best For |\n|---|-------|-------|-----|-----|-----|---------|---------|----------|\n| 1 | Lenovo IdeaPad Slim 3 | $649 | Ryzen 5 | Radeon (integrated) | 8 GB | 512 GB SSD | 15.6\" | Student |\n| 2 | HP Pavilion 15 | $799 | Core i5 | Iris Xe | 12 GB | 512 GB SSD | 15.6\" | General |\n| 3 | HP Spectre x360 | $1,450 | Core i7 | Iris Xe | 16 GB | 512 GB SSD | 13.5\" (2‑in‑1) | Business |\n| 4 | Asus ROG Strix G15 | $1,900 | Ryzen 9 | RTX 3070 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 5 | Asus ZenBook Duo | $1,999 | Core i9 | RTX 3070 | 32 GB | 1 TB SSD | 14\" (dual‑screen) | Business |\n| 6 | MSI Creator Z16 | $2,500 | Core i7 | RTX 3060 | 16 GB | 1 TB SSD | 16\" | Creative |\n| 7 | Razer Blade 15 | $3,000 | Core i7 | RTX 3080 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 8 | Apple MacBook Pro 16 | $3,500 | M2 Pro | Integrated | 16 GB | 1 TB SSD | 16.2\" | Creative |\n\nLet me know which one interests you most or if you’d like more detail on any model!', NULL, NULL, NULL, '2025-11-24 22:50:22', 4497, '2025-11-24 14:50:22'),
(41, 3, 'user', 'who is xi jing ping', NULL, NULL, NULL, '2025-11-24 22:51:08', NULL, '2025-11-24 14:51:08'),
(42, 3, 'bot', '**Xi Jinping** (born June 15, 1953) is the current General Secretary of the Communist Party of China, the President of the People’s Republic of China, and Chairman of the Central Military Commission. He has led China since 2012, overseeing major reforms, an ambitious domestic policy agenda, and a more assertive foreign‑policy stance, while centralizing power within the party‑state apparatus.', NULL, NULL, NULL, '2025-11-24 22:51:16', 7741, '2025-11-24 14:51:16'),
(43, 3, 'user', '\"Show me all laptops\"', NULL, NULL, NULL, '2025-11-24 22:52:37', NULL, '2025-11-24 14:52:37'),
(44, 3, 'bot', 'Here are all the laptops we offer right now:\n\n| # | Model | Price | CPU | GPU | RAM | Storage | Display | Best for |\n|---|-------|-------|-----|-----|-----|---------|---------|----------|\n| 1 | Lenovo IdeaPad Slim 3 | $649 | Ryzen 5 | Integrated Radeon | 8 GB | 512 GB SSD | 15.6\" | Student |\n| 2 | HP Pavilion 15 | $799 | Core i5 | Iris Xe | 12 GB | 512 GB SSD | 15.6\" | General use |\n| 3 | HP Spectre x360 | $1,450 | Core i7 | Iris Xe | 16 GB | 512 GB SSD | 13.5\" (tablet) | Business |\n| 4 | Asus ROG Strix G15 | $1,900 | Ryzen 9 | RTX 3070 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 5 | Asus ZenBook Duo | $1,999 | Core i9 | RTX 3070 | 32 GB | 1 TB SSD | 14\" (dual‑screen) | Business |\n| 6 | MSI Creator Z16 | $2,500 | Core i7 | RTX 3060 | 16 GB | 1 TB SSD | 16\" | Creative |\n| 7 | Razer Blade 15 | $3,000 | Core i7 | RTX 3080 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 8 | Apple MacBook Pro 16 | $3,500 | M2 Pro | Integrated | 16 GB | 1 TB SSD | 16.2\" | Creative |\n\nLet me know which one you’d like more details on!', NULL, NULL, NULL, '2025-11-24 22:52:42', 5595, '2025-11-24 14:52:42'),
(45, 3, 'user', '\"Show me all laptops\"', NULL, NULL, NULL, '2025-11-24 22:54:21', NULL, '2025-11-24 14:54:21'),
(46, 3, 'bot', 'Here’s the full list of laptops we carry:\n\n| # | Model | Price | CPU | GPU | RAM | Storage | Display | Best For |\n|---|-------|-------|-----|-----|-----|---------|---------|----------|\n| 1 | Lenovo IdeaPad Slim 3 | $649 | AMD Ryzen 5 | Radeon (integrated) | 8 GB | 512 GB SSD | 15.6\" | Student |\n| 2 | HP Pavilion 15 | $799 | Intel Core i5 | Iris Xe | 12 GB | 512 GB SSD | 15.6\" | General |\n| 3 | HP Spectre x360 | $1,450 | Intel Core i7 | Iris Xe | 16 GB | 512 GB SSD | 13.5\" (2‑in‑1) | Business |\n| 4 | Asus ROG Strix G15 | $1,900 | AMD Ryzen 9 | NVIDIA RTX 3070 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 5 | Asus ZenBook Duo | $1,999 | Intel Core i9 | NVIDIA RTX 3070 | 32 GB | 1 TB SSD | 14\" (dual‑screen) | Business |\n| 6 | MSI Creator Z16 | $2,500 | Intel Core i7 | NVIDIA RTX 3060 | 16 GB | 1 TB SSD | 16\" | Creative |\n| 7 | Razer Blade 15 | $3,000 | Intel Core i7 | NVIDIA RTX 3080 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 8 | Apple MacBook Pro 16 | $3,500 | Apple M2 Pro | Integrated | 16 GB | 1 TB SSD | 16.2\" | Creative |\n\nLet me know if you’d like more details on any model!', NULL, NULL, NULL, '2025-11-24 22:54:27', 5300, '2025-11-24 14:54:27'),
(47, 3, 'user', '\"Show me all laptops\"', NULL, NULL, NULL, '2025-11-24 22:54:36', NULL, '2025-11-24 14:54:36'),
(48, 3, 'bot', '**All laptops in stock**\n\n| # | Model | Price | CPU | GPU | RAM | Storage | Display | Best For |\n|---|-------|-------|-----|-----|-----|---------|---------|----------|\n| 1 | Lenovo IdeaPad Slim 3 | $649 | Ryzen 5 | Integrated Radeon | 8 GB | 512 GB SSD | 15.6\" | Student |\n| 2 | HP Pavilion 15 | $799 | Core i5 | Iris Xe | 12 GB | 512 GB SSD | 15.6\" | General |\n| 3 | HP Spectre x360 | $1,450 | Core i7 | Iris Xe | 16 GB | 512 GB SSD | 13.5\" 2‑in‑1 | Business |\n| 4 | Asus ROG Strix G15 | $1,900 | Ryzen 9 | RTX 3070 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 5 | Asus ZenBook Duo | $1,999 | Core i9 | RTX 3070 | 32 GB | 1 TB SSD | 14\" dual‑screen | Business |\n| 6 | MSI Creator Z16 | $2,500 | Core i7 | RTX 3060 | 16 GB | 1 TB SSD | 16\" | Creative |\n| 7 | Razer Blade 15 | $3,000 | Core i7 | RTX 3080 | 16 GB | 1 TB SSD | 15.6\" | Gaming |\n| 8 | Apple MacBook Pro 16 | $3,500 | M2 Pro | Integrated | 16 GB | 1 TB SSD | 16.2\" | Creative |\n\nLet me know which one interests you or if you need more details!', NULL, NULL, NULL, '2025-11-24 22:54:41', 4526, '2025-11-24 14:54:41'),
(49, 3, 'user', '\"Show me all laptops\"', NULL, NULL, NULL, '2025-11-24 22:55:34', NULL, '2025-11-24 14:55:34'),
(50, 3, 'bot', 'I’m happy to recap the full inventory for you—these are **all** the laptops we currently stock:\n\n1. **Lenovo IdeaPad Slim 3** – $649, Ryzen 5, 8 GB RAM, 512 GB SSD, 15.6\" (Student)\n2. **HP Pavilion 15** – $799, Core i5, 12 GB RAM, 512 GB SSD, 15.6\" (General)\n3. **HP Spectre x360** – $1,450, Core i7, 16 GB RAM, 512 GB SSD, 13.5\" 2‑in‑1 (Business)\n4. **Asus ROG Strix G15** – $1,900, Ryzen 9, 16 GB RAM, 1 TB SSD, 15.6\" (Gaming)\n5. **Asus ZenBook Duo** – $1,999, Core i9, 32 GB RAM, 1 TB SSD, 14\" dual‑screen (Business)\n6. **MSI Creator Z16** – $2,500, Core i7, 16 GB RAM, 1 TB SSD, 16\" (Creative)\n7. **Razer Blade 15** – $3,000, Core i7, 16 GB RAM, 1 TB SSD, 15.6\" (Gaming)\n8. **Apple MacBook Pro 16** – $3,500, M2 Pro, 16 GB RAM, 1 TB SSD, 16.2\" (Creative)\n\nLet me know if you’d like more details on any model or need help narrowing your choice!', NULL, NULL, NULL, '2025-11-24 22:55:38', 4284, '2025-11-24 14:55:38'),
(51, 3, 'user', 'who is xi jing ping', NULL, NULL, NULL, '2025-11-24 22:55:58', NULL, '2025-11-24 14:55:58'),
(52, 3, 'bot', '**Xi Jinping** (born June 15, 1953) is the current General Secretary of the Communist Party of China, the President of the People’s Republic of China, and the Chairman of the Central Military Commission. He has led China since 2012, overseeing major economic reforms, an ambitious domestic policy agenda, and a more assertive foreign‑policy stance while consolidating power within the party‑state apparatus.', NULL, NULL, NULL, '2025-11-24 22:56:00', 1627, '2025-11-24 14:56:00'),
(53, 3, 'user', 'how to refund?', NULL, NULL, NULL, '2025-11-24 22:56:42', NULL, '2025-11-24 14:56:42'),
(54, 3, 'bot', '**How to Request a Refund**\n\n1. **Return Window**  \n   You have 30 days from the delivery date to initiate a return.\n\n2. **Contact Us**  \n   • Phone: 1‑800‑LAPTOPS  \n   • Email: support@laptopstore.com  \n   • Live chat on our website (top‑right corner)\n\n3. **Return Shipping**  \n   • We\'ll give you a prepaid return label.  \n   • Pack the laptop in its original box, include all accessories, and attach the label.\n\n4. **Refund Processing**  \n   • Once we receive the package, we’ll inspect it (24‑48 h).  \n   • If everything’s in order, the refund is issued to the original payment method within 5 business days.\n\n5. **Questions**  \n   If you need help with any step, just reply to this chat or call us. We’re here to assist!', NULL, NULL, NULL, '2025-11-24 22:56:45', 2696, '2025-11-24 14:56:45'),
(55, 3, 'user', 'my budget only 1000 and i am the university student i am focus on do some coding something also play some game like valorant cs2 what are the laptop recommended for me?', NULL, NULL, NULL, '2025-11-24 22:58:08', NULL, '2025-11-24 14:58:08'),
(56, 3, 'bot', 'Here are the best options we have that fit a $1,000 budget and are suited for a university student who wants to program and also play games like Valorant or CS 2 on a casual to medium‑setting level.\n\n| # | Laptop | Price | CPU | GPU | RAM | Storage | Notes |\n|---|--------|-------|-----|-----|-----|---------|-------|\n| **1** | **HP Pavilion 15** | $799 | Intel Core i5 | Intel Iris Xe (integrated) | 12 GB | 512 GB SSD | 15.6\" – Best overall for coding & light gaming. 12 GB RAM gives more headroom for VS Code, IDEs, and multitasking. |\n| 2 | Lenovo IdeaPad Slim 3 | $649 | AMD Ryzen 5 | Radeon (integrated) | 8 GB | 512 GB SSD | 15.6\" – Good value for coding. 8 GB RAM will be fine for most student tasks, but fewer resources for high‑setting gaming. |\n\n### Why the HP Pavilion works best for you\n- **More RAM (12 GB)** – Plenty for code editors, browsers, and background processes.  \n- **Better Integrated GPU (Iris Xe)** – Handles Valorant and CS 2 at medium or low settings with playable frame rates, especially on a 15.6″ screen.  \n- **Balanced price** – Leaves room within your $1,000 budget if you plan to add a small external SSD or a gaming mouse later.\n\n### Tips for gaming with the HP Pavilion\n- **Settings**: Start at “Low” or “Medium” and adjust resolution if needed.  \n- **Heat**: Use a cooling pad to keep the laptop temperature down while gaming.  \n- **Driver updates**: Keep Intel graphics drivers up to date for the best performance.\n\n### Alternative budget‑friendly upgrade\nIf you want a slightly stronger GPU but still under $1,000, we don’t have a dedicated graphics card option in stock right now. However, the HP Pavilion’s Iris Xe is typically the best you’ll find in this price range from our current inventory.  \n\nLet me know if you’d like to place an order or need help with any other specifications!', NULL, NULL, NULL, '2025-11-24 22:58:14', 6429, '2025-11-24 14:58:14'),
(57, 3, 'user', 'Who is the president?', NULL, NULL, NULL, '2025-11-24 22:58:45', NULL, '2025-11-24 14:58:45'),
(58, 3, 'bot', 'I’m sorry, I can only help with laptop‑related questions. Is there a laptop I can help you find today?', NULL, NULL, NULL, '2025-11-24 22:58:46', 1369, '2025-11-24 14:58:46'),
(59, 3, 'user', 'my budget only 2000 and i am the university student i am focus on do some coding something also play some game like valorant cs2 what are the laptop recommended for me?', NULL, NULL, NULL, '2025-11-24 22:59:03', NULL, '2025-11-24 14:59:03'),
(60, 3, 'bot', '**With a $2,000 budget, the best options for coding + casual gaming (Valorant, CS 2) are:**\n\n| # | Laptop | Price | Key Specs | Why it’s a fit |\n|---|--------|-------|-----------|----------------|\n| **1** | **Asus ROG Strix G15** | $1,899.99 | • CPU: AMD Ryzen 9 <br>• GPU: NVIDIA RTX 3070 <br>• RAM: 16 GB <br>• Storage: 1 TB SSD <br>• Display: 15.6″ high‑refresh‑rate | • Strong CPU + GPU gives easy flow for IDEs, containers, and programming tools. <br>• RTX 3070 is more than enough for Valorant/CS 2 at medium‑high settings. <br>• Ample storage for code bases, datasets, and media. |\n| **2** | **HP Spectre x360 (2‑in‑1)** | $1,450 | • CPU: Intel Core i7 <br>• RAM: 16 GB <br>• Storage: 512 GB SSD <br>• Display: 13.5″ 2‑in‑1 | • Very solid CPU for any programming workload. <br>• Integrated Intel Iris Xe can run Valorant/CS 2 at low‑medium settings. <br>• Thin, lightweight – great for commuting between lectures and study rooms. |\n\n### Quick recommendation\n- **If you want the best gaming performance** while still programming comfortably: **Asus ROG Strix G15**.  \n- **If you prioritize portability and a business‑class experience** (though gaming will be limited to basic settings): **HP Spectre x360**.\n\nLet me know which model you’re interested in, or if you’d like more details!', NULL, NULL, NULL, '2025-11-24 22:59:11', 8249, '2025-11-24 14:59:11'),
(61, 3, 'user', 'tq for u info', NULL, NULL, NULL, '2025-11-24 22:59:49', NULL, '2025-11-24 14:59:49'),
(62, 3, 'bot', 'You’re welcome! If you have any more questions about laptops or need help picking one, just let me know.', NULL, NULL, NULL, '2025-11-24 22:59:50', 1374, '2025-11-24 14:59:50'),
(63, 3, 'user', 'fck u', NULL, NULL, NULL, '2025-11-24 23:00:21', NULL, '2025-11-24 15:00:21'),
(64, 3, 'bot', 'I’m sorry, I can only help with laptop‑related questions. Is there a laptop I can help you find today?', NULL, NULL, NULL, '2025-11-24 23:00:22', 1640, '2025-11-24 15:00:22'),
(65, 3, 'user', 'how to refund?', NULL, NULL, NULL, '2025-11-24 23:00:32', NULL, '2025-11-24 15:00:32'),
(66, 3, 'bot', 'I’m sorry, I can only help with laptop‑related questions. Is there a laptop I can help you find today?', NULL, NULL, NULL, '2025-11-24 23:00:33', 1419, '2025-11-24 15:00:33'),
(67, 3, 'user', '\"What laptops do you have?\"', NULL, NULL, NULL, '2025-11-24 23:04:11', NULL, '2025-11-24 15:04:11'),
(68, 3, 'bot', 'Here are the laptops we currently have in stock:\n\n1. **Lenovo IdeaPad Slim 3** – $649.00  \n   *CPU*: AMD Ryzen 5 | *GPU*: Integrated Radeon | *RAM*: 8 GB | *Storage*: 512 GB SSD | *Display*: 15.60″ | *Best For*: Student\n\n2. **HP Pavilion 15** – $799.00  \n   *CPU*: Intel Core i5 | *GPU*: Intel Iris Xe | *RAM*: 12 GB | *Storage*: 512 GB SSD | *Display*: 15.60″ | *Best For*: General\n\n3. **HP Spectre x360** – $1,450.00  \n   *CPU*: Intel Core i7 | *GPU*: Intel Iris Xe | *RAM*: 16 GB | *Storage*: 512 GB SSD | *Display*: 13.50″ | *Best For*: Business\n\n4. **Asus ROG Strix G15** – $1,899.99  \n   *CPU*: AMD Ryzen 9 | *GPU*: NVIDIA RTX 3070 | *RAM*: 16 GB | *Storage*: 1,024 GB SSD | *Display*: 15.60″ | *Best For*: Gaming\n\n5. **Asus ZenBook Duo** – $1,999.00  \n   *CPU*: Intel Core i9 | *GPU*: NVIDIA RTX 3070 | *RAM*: 32 GB | *Storage*: 1,024 GB SSD | *Display*: 14.00″ | *Best For*: Business\n\n6. **MSI Creator Z16** – $2,499.99  \n   *CPU*: Intel Core i7 | *GPU*: NVIDIA RTX 3060 | *RAM*: 16 GB | *Storage*: 1,024 GB SSD | *Display*: 16.00″ | *Best For*: Creative\n\n7. **Razer Blade 15** – $2,999.50  \n   *CPU*: Intel Core i7 | *GPU*: NVIDIA RTX 3080 | *RAM*: 16 GB | *Storage*: 1,024 GB SSD | *Display*: 15.60″ | *Best For*: Gaming\n\n8. **Apple MacBook Pro 16** – $3,499.00  \n   *CPU*: Apple M2 Pro | *GPU*: Integrated | *RAM*: 16 GB | *Storage*: 1,024 GB SSD | *Display*: 16.20″ | *Best For*: Creative\n\nLet me know if you’d like more details or help selecting one that fits your needs!', NULL, NULL, NULL, '2025-11-24 23:04:17', 5774, '2025-11-24 15:04:17'),
(69, 3, 'user', '\"How do I track my order?\"', NULL, NULL, NULL, '2025-11-24 23:04:20', NULL, '2025-11-24 15:04:20'),
(70, 3, 'bot', 'Sure thing! Once your order ships, you’ll receive an email with a tracking number and a link to the courier’s website. Here’s a quick guide on how to keep an eye on your package:\n\n1. **Find the Shipment Email**  \n   - Search your inbox (and spam/junk folder just in case) for an email from **Smart Laptop Advisor** that says “Your order has shipped” or “Track your delivery.”\n\n2. **Locate the Tracking Number**  \n   - The email will include a numeric/alphabetic code (e.g., **1Z999AA10123456784**).  \n   - There may also be a “Track it now” button that takes you straight to the carrier’s tracking page.\n\n3. **Go to the Courier’s Website**  \n   - If you prefer, copy the tracking number and paste it into the search bar on the carrier’s portal:  \n     - **USPS** – https://tools.usps.com/go/TrackConfirmAction!  \n     - **UPS** – https://www.ups.com/track?loc=en_US  \n     - **FedEx** – https://www.fedex.com/apps/fedextrack/  \n     *(We use UPS or FedEx for all domestic shipments)*\n\n4. **Check the Status**  \n   - You’ll see the current location of your package (e.g., “In transit – expected delivery: Mon, 29 Oct 12 PM”).  \n\n5. **Confirm Delivery**  \n   - Once it shows “Delivered,” you should receive a confirmation email.  \n\n### Need More Help?\n- **Call or Email:** If you can’t find your tracking info, or if the status is stuck, drop us a line at support@smartlaptopadvisor.com or use this chat for real‑time assistance.\n- **Delivery Time:** Standard shipping takes 3–5 business days. If you ordered over $1,000, you’ll enjoy free shipping, but the delivery window stays the same.\n\nHappy shopping, and we’ll make sure your laptop gets to you safely!', NULL, NULL, NULL, '2025-11-24 23:04:26', 5964, '2025-11-24 15:04:26'),
(71, 3, 'user', 'what r the gaming laptop u have', NULL, NULL, NULL, '2025-11-24 23:04:39', NULL, '2025-11-24 15:04:39'),
(72, 3, 'bot', 'Here are the gaming‑focused laptops we have in stock:\n\n| # | Laptop | Price | CPU | GPU | RAM | Storage | Display | Best For |\n|---|--------|-------|-----|-----|-----|---------|---------|----------|\n| 1 | **Asus ROG Strix G15** | $1,899.99 | AMD Ryzen 9 | NVIDIA RTX 3070 | 16 GB | 1 TB SSD | 15.6″ | High‑refresh‑rate gaming |\n| 2 | **Razer Blade 15** | $2,999.50 | Intel Core i7 | NVIDIA RTX 3080 | 16 GB | 1 TB SSD | 15.6″ | Premium, sleek gaming design |\n\nBoth are fully featured for gaming, from high‑refresh displays to powerful GPUs. If you’re looking for a specific use‑case or budget, let me know and I can help narrow it down!', NULL, NULL, NULL, '2025-11-24 23:04:43', 3158, '2025-11-24 15:04:43'),
(73, 6, 'user', 'asd', NULL, NULL, NULL, '2025-11-25 00:51:23', NULL, '2025-11-24 16:51:23'),
(74, 6, 'bot', 'I\'m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 00:51:24', 1690, '2025-11-24 16:51:24'),
(75, 6, 'user', 'asd', NULL, NULL, NULL, '2025-11-25 00:53:25', NULL, '2025-11-24 16:53:25'),
(76, 6, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 00:53:27', 1843, '2025-11-24 16:53:27'),
(77, 6, 'user', 'list out all the laptop for me', NULL, NULL, NULL, '2025-11-25 00:53:36', NULL, '2025-11-24 16:53:36'),
(78, 6, 'bot', 'Here’s the full list of laptops we currently have in stock:\n\n| # | Model | Price | CPU | GPU | RAM | Storage | Display | Best For | Notes |\n|---|-------|-------|-----|-----|-----|---------|---------|---------|-------|\n| 1 | **Lenovo IdeaPad Slim 3** | $649.00 | AMD Ryzen 5 | Integrated Radeon | 8 GB | 512 GB SSD | 15.60\" | Student | Great for everyday tasks. |\n| 2 | **HP Pavilion 15** | $799.00 | Intel Core i5 | Intel Iris Xe | 12 GB | 512 GB SSD | 15.60\" | General | Reliable all‑rounder. |\n| 3 | **HP Spectre x360** | $1,450.00 | Intel Core i7 | Intel Iris Xe | 16 GB | 512 GB SSD | 13.50\" | Business | Versatile 2‑in‑1. |\n| 4 | **Asus ROG Strix G15** | $1,899.99 | AMD Ryzen 9 | NVIDIA RTX 3070 | 16 GB | 1 GB SSD | 15.60\" | Gaming | High‑refresh‑rate gaming powerhouse. |\n| 5 | **Asus ZenBook Duo** | $1,999.00 | Intel Core i9 | NVIDIA RTX 3070 | 32 GB | 1 GB SSD | 14.00\" | Business | Dual‑screen for productivity. |\n| 6 | **MSI Creator Z16** | $2,499.99 | Intel Core i7 | NVIDIA RTX 3060 | 16 GB | 1 GB SSD | 16.00\" | Creative | Powerful for creative professionals. |\n| 7 | **Razer Blade 15** | $2,999.50 | Intel Core i7 | NVIDIA RTX 3080 | 16 GB | 1 GB SSD | 15.60\" | Gaming | Ultimate gaming laptop with sleek design. |\n| 8 | **Apple MacBook Pro 16** | $3,499.00 | Apple M2 Pro | Integrated | 16 GB | 1 GB SSD | 16.20\" | Creative | Powerful performance for professionals. |\n\nLet me know if you’d like more details on any of these models or help deciding which one is right for you!', NULL, NULL, NULL, '2025-11-25 00:53:42', 5486, '2025-11-24 16:53:42'),
(79, 6, 'user', 'kkk', NULL, NULL, NULL, '2025-11-25 01:01:50', NULL, '2025-11-24 17:01:50'),
(80, 6, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 01:01:51', 1415, '2025-11-24 17:01:51'),
(81, 7, 'user', 'hi', NULL, NULL, NULL, '2025-11-25 03:37:47', NULL, '2025-11-24 19:37:47'),
(82, 7, 'bot', 'Hello! 👋 How can I help you today? If you have any questions about laptops, specs, pricing, ordering, or our store policies, just let me know!', NULL, NULL, NULL, '2025-11-25 03:37:48', 1445, '2025-11-24 19:37:48'),
(83, 7, 'user', 'Looking for a computer', NULL, NULL, NULL, '2025-11-25 03:38:32', NULL, '2025-11-24 19:38:32'),
(84, 7, 'bot', 'Sure thing! All the computers we carry are laptops. Could you let me know what you’ll mainly use it for (e.g., gaming, video editing, general office work, or student use) and what budget you’re considering? That’ll help me suggest the best model from our inventory.', NULL, NULL, NULL, '2025-11-25 03:38:34', 2016, '2025-11-24 19:38:34'),
(85, 8, 'user', 'hello', NULL, NULL, NULL, '2025-11-25 03:44:56', NULL, '2025-11-24 19:44:56'),
(86, 8, 'bot', 'Hello! 👋 How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-11-25 03:44:58', 1618, '2025-11-24 19:44:58'),
(87, 9, 'user', 'asdasd', NULL, NULL, NULL, '2025-11-25 03:53:31', NULL, '2025-11-24 19:53:31'),
(88, 9, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 03:53:33', 1943, '2025-11-24 19:53:33'),
(89, 10, 'user', 'What is the warranty?', NULL, NULL, NULL, '2025-11-25 03:55:50', NULL, '2025-11-24 19:55:50'),
(90, 10, 'bot', 'All of our laptops come with a manufacturer’s warranty. The length varies by brand—generally it’s 1–2 years. If you have a specific model in mind, let me know and I can give you the exact warranty period for that laptop.', NULL, NULL, NULL, '2025-11-25 03:55:53', 2385, '2025-11-24 19:55:53'),
(91, 10, 'user', 'How can I claim warranty?', NULL, NULL, NULL, '2025-11-25 03:56:09', NULL, '2025-11-24 19:56:09'),
(92, 10, 'bot', 'You can claim the manufacturer’s warranty by following these steps:\n\n1. **Verify the Warranty Period**  \n   • All laptops in our store come with a manufacturer’s warranty (typically 1–2 years depending on the brand).  \n   • Your specific model’s warranty length will be displayed on the product page or receipts.\n\n2. **Gather Required Information**  \n   • Purchase receipt or order confirmation number.  \n   • Serial number of the laptop (usually on a sticker under the battery or in the BIOS).  \n   • A clear description of the issue and any error messages.\n\n3. **Contact Our Support Team**  \n   • **Email:** Send the information to support@smartlaptopadvisor.com.  \n   • **Chat:** Use this chat to receive assistance and next steps.  \n\n4. **Submit a Warranty Claim**  \n   • Our support team will review your claim and, if approved, guide you to the authorized service center.  \n   • For in‑store items, we can arrange repair or replacement at your local authorized service provider.\n\n5. **Follow Up**  \n   • Once the repair is completed, we’ll notify you and provide any post‑repair documentation or new serial number if applicable.\n\nIf you prefer, you can also bring the laptop to any authorized service center for an in‑person claim—just bring the same documentation. Feel free to let me know if you need help locating a nearby authorized center or if you have any other questions!', NULL, NULL, NULL, '2025-11-25 03:56:13', 3662, '2025-11-24 19:56:13'),
(93, 7, 'user', 'What is the warranty?', NULL, NULL, NULL, '2025-11-25 03:56:40', NULL, '2025-11-24 19:56:40'),
(94, 7, 'bot', 'All of the laptops we sell come with the manufacturer’s standard warranty, which is typically 1–2 years depending on the brand and model. If you have a specific laptop in mind, I can tell you whether it’s a 1‑year or 2‑year warranty. Feel free to ask about a particular model!', NULL, NULL, NULL, '2025-11-25 03:56:42', 1573, '2025-11-24 19:56:42'),
(95, 7, 'user', 'Warranty details please', NULL, NULL, NULL, '2025-11-25 03:56:51', NULL, '2025-11-24 19:56:51'),
(96, 7, 'bot', 'Our store doesn’t add extra coverage on top of what the manufacturer offers. Every laptop we sell comes with the manufacturer\'s standard warranty, which is usually **1 to 2 years** of coverage depending on the brand and model. If you let me know which laptop you’re interested in, I can confirm whether that particular unit is covered for 1 year or 2 years. Let me know how I can help further!', NULL, NULL, NULL, '2025-11-25 03:56:53', 1850, '2025-11-24 19:56:53'),
(97, 7, 'user', 'warranty', NULL, NULL, NULL, '2025-11-25 04:06:50', NULL, '2025-11-24 20:06:50'),
(98, 7, 'bot', 'All of the laptops we offer come with the manufacturer’s standard warranty—typically **1‑2 years** depending on the brand and model. If you tell me which specific laptop you’re looking at, I can confirm the exact warranty period for that unit. Let me know how I can assist further!', NULL, NULL, NULL, '2025-11-25 04:06:52', 2421, '2025-11-24 20:06:52'),
(99, 7, 'user', 'What is the warranty?', NULL, NULL, NULL, '2025-11-25 04:07:01', NULL, '2025-11-24 20:07:01'),
(100, 7, 'bot', 'All laptops we sell come with the manufacturer’s standard warranty, which is typically **1 to 2 years** depending on the brand and model. If you let me know which specific laptop you’re interested in, I can confirm exactly how many years it’s covered. Happy to help!', NULL, NULL, NULL, '2025-11-25 04:07:02', 1438, '2025-11-24 20:07:02'),
(101, 7, 'user', 'How long is the warranty period?', NULL, NULL, NULL, '2025-11-25 04:07:12', NULL, '2025-11-24 20:07:12'),
(102, 7, 'bot', 'All of the laptops we sell come with the manufacturer’s standard warranty—generally **1 to 2 years** depending on the brand and specific model. If you let me know which particular laptop you’re looking at, I can confirm exactly how many years of warranty it has. How can I help you further?', NULL, NULL, NULL, '2025-11-25 04:07:14', 2278, '2025-11-24 20:07:14'),
(103, 7, 'user', 'Under $800', NULL, NULL, NULL, '2025-11-25 04:07:21', NULL, '2025-11-24 20:07:21'),
(104, 7, 'bot', 'Here are the options we have that are **under $800**:\n\n| Laptop | Price | Highlights |\n|--------|-------|------------|\n| **Lenovo IdeaPad Slim 3** | **$649.00** | AMD Ryzen 5 CPU, 8 GB RAM, 512 GB SSD, 15.6” display – great for students and everyday use. |\n| **HP Pavilion 15** | **$799.00** | Intel Core i5, 12 GB RAM, 512 GB SSD, 15.6” display – a solid all‑rounder for work and entertainment. |\n\nBoth come with the manufacturer’s standard warranty (1–2 years). Let me know if you’d like more details on either model or help with anything else!', NULL, NULL, NULL, '2025-11-25 04:07:25', 4018, '2025-11-24 20:07:25'),
(105, 11, 'user', 'How can I claim warranty?', NULL, NULL, NULL, '2025-11-25 04:07:54', NULL, '2025-11-24 20:07:54'),
(106, 11, 'bot', 'I’m happy to help! All of the laptops you purchase from Smart Laptop Advisor come with the manufacturer’s warranty, which typically ranges from 1 to 2 years depending on the brand (e.g., AMD, Intel, NVIDIA, Apple).\n\nHere’s how you can claim your warranty:\n\n1. **Locate Your Receipt or Order Confirmation**  \n   – Keep the email confirmation or printout of your purchase – you’ll need the order number and product serial number (usually on a sticker inside the laptop or on the packaging).\n\n2. **Contact Our Support Team**  \n   – Email us at **support@smartlaptopadvisor.com**.  \n   – Include:  \n     * Your name and contact information  \n     * Order number (or receipt)  \n     * Laptop model (from the inventory list) and serial number  \n     * A brief description of the issue\n\n3. **We’ll Arrange the Warranty Process**  \n   – Our team will verify your warranty status.  \n   – If the issue is covered, we’ll send you a prepaid shipping label (or arrange a pickup) so you can send the laptop to the manufacturer’s service center.  \n   – Once repaired, we’ll return the laptop to you in a timely manner.\n\n4. **Follow Up**  \n   – You’ll receive email updates at each step: receipt of claim, shipping confirmation, repair progress, and return receipt.\n\nIf you have any additional questions or need help gathering the required information, just let me know!', NULL, NULL, NULL, '2025-11-25 04:07:58', 4413, '2025-11-24 20:07:58'),
(107, 12, 'user', 'What is the warranty?', NULL, NULL, NULL, '2025-11-25 04:08:09', NULL, '2025-11-24 20:08:09'),
(108, 12, 'bot', 'All laptops at Smart Laptop Advisor come with the standard manufacturer warranty. The exact length varies by brand – typically 1–2 years. If you need more detail on a specific model, just let me know!', NULL, NULL, NULL, '2025-11-25 04:08:11', 1293, '2025-11-24 20:08:11'),
(109, 7, 'user', 'What is the warranty?', NULL, NULL, NULL, '2025-11-25 04:08:15', NULL, '2025-11-24 20:08:15'),
(110, 7, 'bot', 'All laptops in our store come with the manufacturer’s standard warranty, which is typically **1 to 2 years** depending on the brand and model.  \nIf you let me know which specific laptop you’re interested in, I can confirm exactly how many years your unit will be covered. Let me know how I can help further!', NULL, NULL, NULL, '2025-11-25 04:08:18', 2685, '2025-11-24 20:08:18'),
(111, 7, 'user', 'Warranty details please', NULL, NULL, NULL, '2025-11-25 04:08:24', NULL, '2025-11-24 20:08:24'),
(112, 7, 'bot', 'All of the laptops we sell come with the manufacturer’s standard warranty.  That means **1 to 2 years of coverage** – the exact length depends on the brand and the specific model.  \n\nIf you tell me which laptop you’re looking at, I can confirm the exact warranty period for that model. Let me know how I can help!', NULL, NULL, NULL, '2025-11-25 04:08:26', 2153, '2025-11-24 20:08:26'),
(113, 7, 'user', 'Good morning', NULL, NULL, NULL, '2025-11-25 04:08:42', NULL, '2025-11-24 20:08:42'),
(114, 7, 'bot', 'I\'m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 04:08:44', 2140, '2025-11-24 20:08:44'),
(115, 7, 'user', 'Good evening', NULL, NULL, NULL, '2025-11-25 04:08:56', NULL, '2025-11-24 20:08:56'),
(116, 7, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 04:09:00', 3931, '2025-11-24 20:09:00'),
(117, 7, 'user', 'what is the time now?', NULL, NULL, NULL, '2025-11-25 04:09:08', NULL, '2025-11-24 20:09:08'),
(118, 7, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-11-25 04:09:10', 1971, '2025-11-24 20:09:10'),
(119, 7, 'user', 'Goodbye', NULL, NULL, NULL, '2025-11-25 04:09:18', NULL, '2025-11-24 20:09:18'),
(120, 7, 'bot', 'Goodbye! Feel free to reach out if you have any more questions about laptops or our store. Have a great day!', NULL, NULL, NULL, '2025-11-25 04:09:19', 1243, '2025-11-24 20:09:19'),
(121, 7, 'user', 'Hello', NULL, NULL, NULL, '2025-11-25 04:09:29', NULL, '2025-11-24 20:09:29'),
(122, 7, 'bot', 'Hello! 👋 How can I help you with your laptop needs today?', NULL, NULL, NULL, '2025-11-25 04:09:30', 1227, '2025-11-24 20:09:30'),
(123, 15, 'user', 'hello', NULL, NULL, NULL, '2025-11-25 21:55:35', NULL, '2025-11-25 13:55:35'),
(124, 15, 'bot', 'Hello! 👋 How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-11-25 21:55:36', 1079, '2025-11-25 13:55:36'),
(125, 16, 'user', 'hello', NULL, NULL, NULL, '2025-12-03 01:17:05', NULL, '2025-12-02 17:17:05'),
(126, 16, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-12-03 01:17:05', NULL, '2025-12-02 17:17:05'),
(127, 16, 'user', 'list out all the laptop', NULL, NULL, NULL, '2025-12-03 01:17:13', NULL, '2025-12-02 17:17:13'),
(128, 16, 'bot', 'Here are all the laptops we currently have in stock:\n\n| # | Laptop | Price | Key Specs | Why it\'s a fit |\n|---|---|---|---|---|\n| 1 | IdeaPad Slim 3 (Lenovo) | $649.00 | • AMD Ryzen 5<br>• Integrated Radeon graphics<br>• 8 GB RAM | • Ideal for students on a budget<br>• Good all‑day battery life for classes<br>• Lightweight and portable |\n| 2 | HP Pavilion 15 (HP) | $799.99 | • Intel Core i5‑1235U<br>• Intel Iris Xe graphics<br>• 8 GB RAM | • Strong performance for coursework and light multitasking<br>• 15‑inch display balances productivity and portability<br>• Slightly higher specs than the IdeaPad for a modest price increase |\n| 3 | ASUS ROG Strix G15 (ASUS) | $1,299.99 | • AMD Ryzen 7 7735HS<br>• NVIDIA RTX 4060<br>• 16 GB RAM | • Powerhouse for gaming and demanding creative work<br>• High‑refresh-rate display for smooth gameplay<br>• Robust cooling system for sustained performance |\n\n**Quick recommendation**  \n- If you need a solid, budget‑friendly laptop for school work, the **IdeaPad Slim 3** offers great value.  \n- For a bit more power without breaking the bank, the **HP Pavilion 15** is a balanced choice.  \n- If gaming or heavy multimedia tasks are a priority, the **ASUS ROG Strix G15** is the clear winner.  ', NULL, NULL, NULL, '2025-12-03 01:17:22', NULL, '2025-12-02 17:17:22'),
(129, 16, 'user', 'got macbook pro 16?', NULL, NULL, NULL, '2025-12-03 01:19:42', NULL, '2025-12-02 17:19:42'),
(130, 16, 'bot', 'Sure! We have the **MacBook Pro 16** in stock.\n\n| # | Laptop | Price | Key Specs | Why it\'s a fit |\n|---|---|---|---|---|\n| 1 | **MacBook Pro 16** (Apple) | $3499.00 | • Apple M2 Pro CPU<br>• Integrated GPU<br>• 16 GB RAM<br>• 16‑inch Retina display | • Powerful M2 Pro chip handles intensive creative workloads (video editing, 3D rendering)<br>• High‑resolution Retina screen gives accurate colors for design work |\n\n**Quick recommendation**  \nIf you need top‑tier performance for creative tasks, the MacBook Pro 16 is the ideal choice—with a strong M2 Pro processor, ample RAM, and a brilliant display, it’s built for professionals who demand the best. Let me know if you’d like to add it to your cart or have any questions about shipping, warranty, or payment!', NULL, NULL, NULL, '2025-12-03 01:19:47', NULL, '2025-12-02 17:19:47'),
(131, 16, 'user', 'got SteelSeries Arctis 7?', NULL, NULL, NULL, '2025-12-03 01:20:19', NULL, '2025-12-02 17:20:19'),
(132, 16, 'bot', 'I\'m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-03 01:20:25', NULL, '2025-12-02 17:20:25'),
(133, 16, 'user', 'got Razer BlackWidow V3?', NULL, NULL, NULL, '2025-12-03 01:21:47', NULL, '2025-12-02 17:21:47'),
(134, 16, 'bot', 'I\'m sorry, I can only help with laptops and store-related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-03 01:21:53', NULL, '2025-12-02 17:21:53'),
(135, 16, 'user', 'what mouse did u recommend to me?', NULL, NULL, NULL, '2025-12-03 01:22:52', NULL, '2025-12-02 17:22:52'),
(136, 16, 'bot', 'I\'m sorry, I can only help with laptops and store-related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-03 01:22:56', NULL, '2025-12-02 17:22:56'),
(137, 17, 'user', 'hello', NULL, NULL, NULL, '2025-12-03 01:44:26', NULL, '2025-12-02 17:44:26'),
(138, 17, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-12-03 01:44:26', NULL, '2025-12-02 17:44:26'),
(139, 17, 'user', 'yo', NULL, NULL, NULL, '2025-12-03 01:44:34', NULL, '2025-12-02 17:44:34'),
(140, 17, 'bot', 'I\'m sorry, I can only help with laptops and store-related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-03 01:44:37', NULL, '2025-12-02 17:44:37'),
(141, 17, 'user', 'greeting', NULL, NULL, NULL, '2025-12-03 01:44:49', NULL, '2025-12-02 17:44:49'),
(142, 17, 'bot', 'Hello! 👋 I\'m the Smart Laptop Advisor. How can I assist you with laptops or any store‑related questions today?', NULL, NULL, NULL, '2025-12-03 01:44:52', NULL, '2025-12-02 17:44:52'),
(143, 17, 'user', 'warranty info', NULL, NULL, NULL, '2025-12-03 01:45:09', NULL, '2025-12-02 17:45:09'),
(144, 17, 'bot', '**Warranty Overview**\n\n- **All laptops** come with the manufacturer’s warranty.  \n  - Most brands offer **1‑year** coverage, while some (e.g., high‑end workstations) provide **up to 2 years**.  \n- The warranty covers **hardware defects** and includes repair or replacement at authorized service centers.  \n- **Accidental damage** (drops, spills, etc.) is **not** covered under the standard warranty; you can purchase an optional extended protection plan for added peace of mind.\n\n**What’s Included**\n\n| Coverage | Typical Duration | What’s Covered |\n|---|---|---|\n| **Standard Manufacturer Warranty** | 1‑2 years (brand‑specific) | • Defective parts<br>• Labor for repairs<br>• Free replacement if unrepairable |\n| **Optional Extended Protection** | Up to 3 years | • Same as standard + accidental damage protection (subject to plan terms) |\n\n**How to Use Your Warranty**\n\n1. **Contact Support** – Email [support@smartlaptopadvisor.com](mailto:support@smartlaptopadvisor.com) or reply here with your order number.  \n2. **Provide Details** – Model, issue description, and proof of purchase.  \n3. **Follow Instructions** – We’ll guide you to the nearest authorized service center or arrange a return for repair/replacement.\n\nIf you have any specific laptop in mind, I can also share its exact warranty length. Let me know how I can help!', NULL, NULL, NULL, '2025-12-03 01:45:22', NULL, '2025-12-02 17:45:22'),
(145, 17, 'user', 'good good tq for the info', NULL, NULL, NULL, '2025-12-03 01:47:53', NULL, '2025-12-02 17:47:53'),
(146, 17, 'bot', 'You’re welcome! 😊 If you have any questions about laptops, specifications, store policies, or need help choosing the right device, just let me know. I’m here to help!', NULL, NULL, NULL, '2025-12-03 01:47:56', NULL, '2025-12-02 17:47:56'),
(147, 17, 'user', 'my rog strix scar 16 got problem how do i claim warranty can u provide the instruction?', NULL, NULL, NULL, '2025-12-03 01:49:29', NULL, '2025-12-02 17:49:29');
INSERT INTO `conversation_messages` (`message_id`, `conversation_id`, `message_type`, `message_content`, `intent_detected`, `intent_confidence`, `entities_extracted`, `timestamp`, `response_time_ms`, `created_at`) VALUES
(148, 17, 'bot', 'Sure! Here’s how you can claim the warranty for your ASUS ROG Strix Scar 16 through Smart Laptop Advisor:\n\n### Warranty Claim Steps\n1. **Locate Your Purchase Info**  \n   - Find the order confirmation email or receipt (order #, purchase date, and price).  \n   - Make sure the laptop is still within the manufacturer’s warranty period (usually 1 year for ASUS; check the exact dates on your receipt).\n\n2. **Prepare the Laptop**  \n   - Back up any personal data – warranty service may require a system restore.  \n   - Keep the original box, accessories, and any included documentation handy.\n\n3. **Contact Our Support Team**  \n   - **Email:** support@smartlaptopadvisor.com  \n   - **Subject line:** “Warranty Claim – [Your Order #] – ROG Strix Scar 16”  \n   - Include a brief description of the issue, photos/videos (if helpful), and attach a scan of the receipt.\n\n4. **Receive a Return Authorization (RMA)**  \n   - Our team will reply with an RMA number and a prepaid shipping label (if applicable).  \n   - Print the label and securely pack the laptop with the original box and accessories.\n\n5. **Ship the Laptop**  \n   - Drop the package at any UPS/USPS location (the label includes tracking).  \n   - Keep the tracking number for reference.\n\n6. **Repair / Replacement**  \n   - Once we receive the laptop, the manufacturer will diagnose the problem.  \n   - If it’s covered under warranty, they’ll repair it or provide a replacement at no cost to you.\n\n7. **Return Shipping**  \n   - After repair/replacement, we’ll ship the laptop back to you using our standard shipping (free for orders over $1000, otherwise standard rates apply).  \n\n### Quick Tips\n- **Act promptly** – processing starts once we receive the laptop.  \n- **Do not uninstall** any hardware components or open the chassis; this could void the warranty.  \n- **Keep all correspondence** in case you need to follow up.\n\nIf you have any trouble generating the RMA or need further help, just let me know! I\'m here to make the process smooth for you.', NULL, NULL, NULL, '2025-12-03 01:49:40', NULL, '2025-12-02 17:49:40'),
(149, 18, 'user', 'hi', NULL, NULL, NULL, '2025-12-03 02:11:26', NULL, '2025-12-02 18:11:26'),
(150, 18, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-12-03 02:11:26', NULL, '2025-12-02 18:11:26'),
(151, 18, 'user', 'recommend laptop for me', NULL, NULL, NULL, '2025-12-03 02:11:39', NULL, '2025-12-02 18:11:39'),
(152, 18, 'bot', 'Here are three great options from our current inventory that cover a range of needs and budgets:\n\n| # | Laptop | Price | Key Specs | Why it\'s a fit |\n|---|---|---|---|---|\n| 1 | IdeaPad Slim 3 (Lenovo) | $649.00 | • AMD Ryzen 5<br>• Integrated Radeon graphics<br>• 8 GB RAM | • Lightweight and affordable – perfect for students or everyday tasks<br>• Long battery life for on‑the‑go use |\n| 2 | HP Pavilion 15 (HP) | $799.99 | • Intel Core i5‑1235U<br>• Intel Iris Xe graphics<br>• 8 GB RAM | • Strong performance for classwork, streaming, and light creative work<br>• Slightly larger screen and premium build for a modest price |\n| 3 | ASUS ROG Strix G15 (ASUS) | $1,299.99 | • AMD Ryzen 7 7735HS<br>• NVIDIA RTX 4060<br>• 16 GB RAM | • High‑end gaming and content‑creation machine<br>• Dedicated GPU for smooth gaming at 1080p+ and fast video rendering |\n\n**Quick recommendation**  \nIf you need a budget‑friendly laptop for school or everyday use, the **IdeaPad Slim 3** is the best value. For a bit more power and a premium feel, the **HP Pavilion 15** balances performance and price. If gaming or intensive creative work is a priority, the **ASUS ROG Strix G15** delivers the performance you need.  \n\nLet me know if you have a specific budget or primary use case, and I can narrow it down further!', NULL, NULL, NULL, '2025-12-03 02:11:46', NULL, '2025-12-02 18:11:46'),
(153, 18, 'user', 'hi', NULL, NULL, NULL, '2025-12-03 02:11:55', NULL, '2025-12-02 18:11:55'),
(154, 18, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-12-03 02:11:55', NULL, '2025-12-02 18:11:55'),
(155, 18, 'user', 'GOOD NIGHT', NULL, NULL, NULL, '2025-12-03 02:12:55', NULL, '2025-12-02 18:12:55'),
(156, 18, 'bot', 'I\'m sorry, I can only help with laptops and store-related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-03 02:12:57', NULL, '2025-12-02 18:12:57'),
(157, 18, 'user', 'start chat', NULL, NULL, NULL, '2025-12-03 02:17:23', NULL, '2025-12-02 18:17:23'),
(158, 18, 'bot', 'Hello! 👋 I\'m your Smart Laptop Advisor. I can help you find the perfect laptop, answer questions about specs, shipping, returns, warranties, and guide you through the ordering process.\n\nWhat can I assist you with today?', NULL, NULL, NULL, '2025-12-03 02:17:27', NULL, '2025-12-02 18:17:27'),
(159, 19, 'user', 'hi', NULL, NULL, NULL, '2025-12-03 02:18:18', NULL, '2025-12-02 18:18:18'),
(160, 19, 'bot', 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', NULL, NULL, NULL, '2025-12-03 02:18:18', NULL, '2025-12-02 18:18:18'),
(161, 22, 'user', 'hi', NULL, NULL, NULL, '2025-12-03 02:20:13', NULL, '2025-12-02 18:20:13'),
(162, 22, 'bot', 'Hello! 👋 I\'m your Smart Laptop Advisor. How can I help you today with laptops, specs, store policies, shipping, returns, or ordering?', NULL, NULL, NULL, '2025-12-03 02:20:15', 1647, '2025-12-02 18:20:15'),
(163, 23, 'user', 's', NULL, NULL, NULL, '2025-12-07 16:37:18', NULL, '2025-12-07 08:37:18'),
(164, 23, 'bot', 'I’m sorry, I can only help with laptops and store‑related questions. How can I assist you today?', NULL, NULL, NULL, '2025-12-07 16:37:23', NULL, '2025-12-07 08:37:23');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `coupon_id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `expiry_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`coupon_id`, `code`, `discount_type`, `discount_value`, `expiry_date`, `is_active`) VALUES
(1, 'SAVE10', 'percentage', 10.00, '2025-12-31', 1),
(2, '50OFF', 'fixed', 50.00, NULL, 1),
(3, 'EXPIRED', 'percentage', 20.00, '2020-01-01', 0),
(4, 'XD985', 'fixed', 10.00, '2025-12-09', 0);

-- --------------------------------------------------------

--
-- Table structure for table `intents`
--

CREATE TABLE `intents` (
  `intent_id` int(11) NOT NULL,
  `intent_name` varchar(100) NOT NULL,
  `display_name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL COMMENT 'greeting, product_inquiry, recommendation, support, etc.',
  `is_active` tinyint(1) DEFAULT 1,
  `priority` int(11) DEFAULT 0 COMMENT 'Higher priority intents are matched first',
  `confidence_threshold` decimal(4,3) DEFAULT 0.750 COMMENT 'Minimum confidence to match',
  `usage_count` int(11) DEFAULT 0,
  `success_count` int(11) DEFAULT 0,
  `last_used_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intents`
--

INSERT INTO `intents` (`intent_id`, `intent_name`, `display_name`, `description`, `category`, `is_active`, `priority`, `confidence_threshold`, `usage_count`, `success_count`, `last_used_at`, `created_at`, `updated_at`) VALUES
(1, 'greeting', 'Greetings & Welcome', 'User greets the chatbot or starts a conversation', 'greeting', 1, 100, 0.750, 8, 8, '2025-12-03 02:20:15', '2025-11-21 19:57:10', '2025-12-02 18:20:15'),
(2, 'find_laptop', 'Laptop Search Requests', 'User wants to find or search for a laptop', 'product_inquiry', 1, 90, 0.750, 1, 1, '2025-11-25 04:06:52', '2025-11-21 19:57:10', '2025-11-24 20:06:52'),
(3, 'gaming_laptop', 'Gaming Laptop Requests', 'User is looking for a gaming laptop', 'recommendation', 1, 85, 0.750, 0, 0, NULL, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(4, 'budget_question', 'Budget Related Queries', 'User specifies their budget or price range', 'support', 1, 80, 0.750, 0, 0, NULL, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(5, 'goodbye', 'Farewell & Thanks', 'User thanks the bot or says goodbye', 'greeting', 1, 70, 0.750, 0, 0, NULL, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(7, 'greeting_copy', 'Greetings & Welcome (Copy)', 'User greets the chatbot or starts a conversation', 'greeting', 1, 10, 0.750, 0, 0, NULL, '2025-11-24 19:54:54', '2025-11-24 19:57:48'),
(8, 'warranty_info', 'Warranty Information', 'Provides users with details about product warranty coverage, duration, and claim procedures.', 'support', 1, 6, 0.750, 4, 4, '2025-11-25 04:08:26', '2025-11-24 19:55:31', '2025-11-24 20:08:26');

-- --------------------------------------------------------

--
-- Table structure for table `intent_responses`
--

CREATE TABLE `intent_responses` (
  `response_id` int(11) NOT NULL,
  `intent_id` int(11) NOT NULL,
  `response_text` text NOT NULL,
  `response_type` enum('text','rich','template') DEFAULT 'text',
  `response_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL COMMENT 'Additional response configuration (buttons, cards, etc.)' CHECK (json_valid(`response_data`)),
  `is_default` tinyint(1) DEFAULT 0,
  `priority` int(11) DEFAULT 0 COMMENT 'Priority for response selection',
  `usage_count` int(11) DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `intent_responses`
--

INSERT INTO `intent_responses` (`response_id`, `intent_id`, `response_text`, `response_type`, `response_data`, `is_default`, `priority`, `usage_count`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', 'text', NULL, 1, 1, 0, 1, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(2, 2, 'I\'d be happy to help you find the perfect laptop! To give you the best recommendations, could you tell me what you\'ll primarily use it for and what your budget range is?', 'text', NULL, 1, 1, 0, 1, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(3, 3, 'Excellent! I can help you find a powerful gaming laptop. What\'s your budget range, and what types of games do you plan to play? This will help me recommend the best GPU and specs for your needs.', 'text', NULL, 1, 1, 0, 1, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(4, 4, 'Perfect! With your budget of [BUDGET], I can show you some excellent options. Let me find laptops that offer the best value in your price range.', 'text', NULL, 1, 1, 0, 1, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(5, 5, 'You\'re very welcome! I\'m glad I could help you find the perfect laptop. If you have any questions in the future, don\'t hesitate to ask. Have a great day! 🎓', 'text', NULL, 1, 1, 0, 1, '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(7, 7, 'Hello! I\'m your Smart Laptop Advisor. How can I help you find the perfect laptop today?', 'text', NULL, 1, 0, 0, 1, '2025-11-24 19:54:54', '2025-11-24 19:54:54'),
(8, 8, 'Our products come with a standard warranty that covers manufacturing defects. Warranty duration and claim steps may vary depending on the product. Please share your product name or model so I can provide exact details.', 'text', NULL, 1, 0, 0, 1, '2025-11-24 19:55:31', '2025-11-24 19:55:31');

-- --------------------------------------------------------

--
-- Table structure for table `inventory_logs`
--

CREATE TABLE `inventory_logs` (
  `log_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `change_amount` int(11) NOT NULL,
  `change_type` enum('sale','restock','adjustment','return') NOT NULL,
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inventory_logs`
--

INSERT INTO `inventory_logs` (`log_id`, `product_id`, `change_amount`, `change_type`, `note`, `created_at`) VALUES
(1, 121, 20, 'restock', '', '2025-12-02 13:21:38'),
(2, 117, -46, 'adjustment', 'Manual update via inventory page', '2025-12-02 13:21:50'),
(3, 117, 11, 'restock', '', '2025-12-02 13:21:52'),
(4, 208, -41, 'adjustment', 'Manual update via inventory page', '2025-12-08 10:12:33'),
(5, 227, -43, 'adjustment', 'Manual update via inventory page', '2025-12-08 10:12:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `order_status` varchar(50) DEFAULT 'Processing',
  `shipping_name` varchar(255) DEFAULT NULL,
  `shipping_address` varchar(500) DEFAULT NULL,
  `shipping_city` varchar(100) DEFAULT NULL,
  `shipping_state` varchar(100) DEFAULT NULL,
  `shipping_zip` varchar(20) DEFAULT NULL,
  `shipping_country` varchar(100) DEFAULT NULL,
  `shipping_phone` varchar(20) DEFAULT NULL,
  `order_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT 'Credit Card',
  `is_flagged` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_status`, `shipping_name`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `shipping_phone`, `order_date`, `payment_method`, `is_flagged`) VALUES
(1, 1, 649.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:17:53', 'Credit Card', 0),
(2, 1, 584.10, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:36:06', 'Credit Card', 0),
(3, 1, 649.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:36:33', 'Credit Card', 0),
(4, 1, 649.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:37:59', 'Credit Card', 0),
(5, 1, 584.10, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:38:17', 'Credit Card', 0),
(6, 1, 799.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:38:26', 'Credit Card', 0),
(7, 1, 719.10, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:41:51', 'Credit Card', 0),
(8, 1, 2898.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 19:42:34', 'Credit Card', 0),
(9, 2, 649.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-28 06:09:52', 'Credit Card', 0),
(10, 1, 649.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-28 07:23:33', 'Credit Card', 0),
(24, 4, 900.00, 'Completed', 'GWK', 'Tingkat Lahat Mines 4', 'Lahat', 'Perak', '31500', 'Malaysia', '+60165076821', '2025-12-01 15:59:29', 'Credit Card', 0),
(25, 4, 5000.00, 'Shipped', 'GWK', 'Tingkat Lahat Mines 4', 'Lahat', 'Perak', '31500', 'Malaysia', '+60165076821', '2025-12-02 15:22:42', 'Credit Card', 0),
(26, 6, 279.98, 'Pending', 'GYZ', 'asdsdad', 'Lahat', 'Perak', '31500', 'Malaysia', '+60115076821', '2025-12-07 08:38:53', 'Credit Card', 0),
(27, 6, 5000.00, 'Completed', 'GYZ', 'asdsdad', 'Lahat', 'Perak', '31500', 'Malaysia', '+60115076821', '2025-12-07 16:14:21', 'Credit Card', 0),
(28, 6, 5000.00, 'Pending', 'GYZ', 'asdsdad', 'Lahat', 'Perak', '31500', 'Malaysia', '+60115076821', '2025-12-07 17:43:48', 'Credit Card', 0),
(593, 12, 9795.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-04 07:35:57', 'Credit Card', 0),
(594, 9, 6697.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-21 00:58:31', 'Credit Card', 0),
(595, 12, 1673.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-12 01:41:54', 'Credit Card', 0),
(596, 9, 1599.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-11 02:59:51', 'Credit Card', 0),
(597, 9, 3499.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-19 00:17:36', 'Credit Card', 0),
(598, 9, 799.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-07 01:23:34', 'Credit Card', 0),
(599, 15, 5697.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-05 00:11:21', 'Credit Card', 0),
(600, 15, 1749.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-18 11:31:10', 'Credit Card', 0),
(601, 15, 6368.97, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-24 02:44:30', 'Credit Card', 0),
(602, 11, 2499.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-21 14:43:31', 'Credit Card', 0),
(603, 9, 297.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-04 09:30:28', 'Credit Card', 0),
(604, 10, 7296.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-02 07:17:13', 'Credit Card', 0),
(605, 7, 1998.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-19 03:47:38', 'Credit Card', 0),
(606, 10, 3045.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-16 01:33:36', 'Credit Card', 0),
(607, 7, 2399.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-01-10 14:00:25', 'Credit Card', 0),
(608, 13, 15395.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-27 09:20:33', 'Credit Card', 0),
(609, 12, 9998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-17 05:00:51', 'Credit Card', 0),
(610, 14, 1998.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-06 03:51:56', 'Credit Card', 0),
(611, 16, 1798.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-01 02:23:51', 'Credit Card', 0),
(612, 9, 1278.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-13 13:36:25', 'Credit Card', 0),
(613, 13, 12297.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-19 05:44:00', 'Credit Card', 0),
(614, 11, 199.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-14 08:10:36', 'Credit Card', 0),
(615, 15, 399.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-02 13:54:45', 'Credit Card', 0),
(616, 12, 8896.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-28 13:57:50', 'Credit Card', 0),
(617, 15, 8297.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-02 01:21:30', 'Credit Card', 0),
(618, 14, 3776.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-10 04:40:13', 'Credit Card', 0),
(619, 8, 5398.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-14 13:17:32', 'Credit Card', 0),
(620, 16, 1948.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-10 13:06:28', 'Credit Card', 0),
(621, 8, 5509.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-22 00:51:24', 'Credit Card', 0),
(622, 12, 3629.49, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-17 00:05:15', 'Credit Card', 0),
(623, 9, 9895.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-13 06:13:02', 'Credit Card', 0),
(624, 11, 5197.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-08 06:19:07', 'Credit Card', 0),
(625, 16, 10696.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-27 11:20:04', 'Credit Card', 0),
(626, 15, 2708.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-19 08:56:23', 'Credit Card', 0),
(627, 8, 888.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-06 04:27:45', 'Credit Card', 0),
(628, 14, 5257.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-20 08:15:21', 'Credit Card', 0),
(629, 9, 6197.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-16 10:16:11', 'Credit Card', 0),
(630, 8, 1999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-13 11:41:01', 'Credit Card', 0),
(631, 8, 12995.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-04 11:19:16', 'Credit Card', 0),
(632, 11, 8397.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-15 07:05:21', 'Credit Card', 0),
(633, 12, 998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-25 01:06:54', 'Credit Card', 0),
(634, 9, 1799.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-05 12:32:55', 'Credit Card', 0),
(635, 9, 11756.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-24 08:27:55', 'Credit Card', 0),
(636, 13, 119.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-13 08:56:19', 'Credit Card', 0),
(637, 13, 13696.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-13 10:51:17', 'Credit Card', 0),
(638, 14, 2599.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-12 08:52:04', 'Credit Card', 0),
(639, 16, 6639.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-22 04:17:55', 'Credit Card', 0),
(640, 14, 8297.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-04 13:09:58', 'Credit Card', 0),
(641, 7, 15894.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-21 05:51:04', 'Credit Card', 0),
(642, 7, 10994.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-07 09:24:29', 'Credit Card', 0),
(643, 11, 3917.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-16 07:00:43', 'Credit Card', 0),
(644, 15, 9198.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-07 08:04:22', 'Credit Card', 0),
(645, 14, 1339.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-07 08:20:49', 'Credit Card', 0),
(646, 11, 4847.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-14 14:24:04', 'Credit Card', 0),
(647, 8, 4898.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-07 13:12:53', 'Credit Card', 0),
(648, 9, 2998.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-20 03:00:14', 'Credit Card', 0),
(649, 13, 249.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-01 11:44:15', 'Credit Card', 0),
(650, 14, 11797.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-14 07:43:21', 'Credit Card', 0),
(651, 7, 6498.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-03-15 07:11:17', 'Credit Card', 0),
(652, 7, 3798.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-23 08:14:32', 'Credit Card', 0),
(653, 12, 3298.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-27 12:50:58', 'Credit Card', 0),
(654, 14, 4495.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-15 09:53:07', 'Credit Card', 0),
(655, 14, 4999.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-06 06:00:30', 'Credit Card', 0),
(656, 7, 2855.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-04 07:22:14', 'Credit Card', 0),
(657, 11, 1998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-10 07:30:49', 'Credit Card', 0),
(658, 12, 6408.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-01 03:37:01', 'Credit Card', 0),
(659, 14, 7698.97, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-15 00:31:51', 'Credit Card', 0),
(660, 7, 3998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 14:06:14', 'Credit Card', 0),
(661, 10, 1998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-06 04:07:02', 'Credit Card', 0),
(662, 15, 4798.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-06 06:07:24', 'Credit Card', 0),
(663, 14, 1838.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 09:44:03', 'Credit Card', 0),
(664, 13, 12676.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-07 00:42:04', 'Credit Card', 0),
(665, 11, 1599.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 01:41:55', 'Credit Card', 0),
(666, 15, 19194.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-10 00:43:22', 'Credit Card', 0),
(667, 9, 6438.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-19 07:52:47', 'Credit Card', 0),
(668, 11, 14715.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-17 06:21:14', 'Credit Card', 0),
(669, 11, 13907.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-01 12:27:25', 'Credit Card', 0),
(670, 9, 8796.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-19 10:21:15', 'Credit Card', 0),
(671, 11, 3247.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-17 08:58:29', 'Credit Card', 0),
(672, 15, 2258.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-20 12:34:47', 'Credit Card', 0),
(673, 11, 13595.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-01 06:18:07', 'Credit Card', 0),
(674, 10, 3547.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-23 09:20:43', 'Credit Card', 0),
(675, 7, 7797.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-24 11:41:59', 'Credit Card', 0),
(676, 8, 3498.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-25 04:28:08', 'Credit Card', 0),
(677, 16, 2999.50, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 07:36:53', 'Credit Card', 0),
(678, 8, 10197.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-11 08:51:37', 'Credit Card', 0),
(679, 10, 1598.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-04-01 13:09:16', 'Credit Card', 0),
(680, 9, 1998.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-21 12:28:16', 'Credit Card', 0),
(681, 12, 3917.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-24 02:02:39', 'Credit Card', 0),
(682, 14, 5798.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-17 05:04:27', 'Credit Card', 0),
(683, 13, 3898.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-05 12:47:53', 'Credit Card', 0),
(684, 7, 49.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-02 10:12:00', 'Credit Card', 0),
(685, 8, 1278.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-10 01:27:26', 'Credit Card', 0),
(686, 11, 4598.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-11 07:51:57', 'Credit Card', 0),
(687, 16, 12796.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-16 04:33:32', 'Credit Card', 0),
(688, 15, 9998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-22 03:44:23', 'Credit Card', 0),
(689, 7, 6796.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-20 13:25:50', 'Credit Card', 0),
(690, 15, 2349.97, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-17 12:44:58', 'Credit Card', 0),
(691, 8, 7325.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-17 12:55:39', 'Credit Card', 0),
(692, 15, 6195.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-23 06:15:48', 'Credit Card', 0),
(693, 9, 5397.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-28 09:46:00', 'Credit Card', 0),
(694, 13, 5946.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-14 08:51:15', 'Credit Card', 0),
(695, 14, 4858.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-05-27 00:04:38', 'Credit Card', 0),
(696, 11, 2066.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-11 00:48:19', 'Credit Card', 0),
(697, 16, 4845.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-19 04:10:04', 'Credit Card', 0),
(698, 8, 4197.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 05:38:50', 'Credit Card', 0),
(699, 10, 3099.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-22 13:34:08', 'Credit Card', 0),
(700, 13, 2998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-01 10:24:42', 'Credit Card', 0),
(701, 15, 5397.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-27 02:53:49', 'Credit Card', 0),
(702, 10, 4399.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-04 10:33:56', 'Credit Card', 0),
(703, 12, 2096.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-21 14:25:10', 'Credit Card', 0),
(704, 8, 3629.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-14 03:09:11', 'Credit Card', 0),
(705, 8, 8276.50, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-07 08:11:02', 'Credit Card', 0),
(706, 12, 174.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-03 13:02:33', 'Credit Card', 0),
(707, 10, 3200.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-05 10:33:22', 'Credit Card', 0),
(708, 10, 5999.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-04 10:02:26', 'Credit Card', 0),
(709, 15, 3697.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-28 11:00:43', 'Credit Card', 0),
(710, 15, 11695.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-06-22 14:33:12', 'Credit Card', 0),
(711, 15, 9998.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-05 10:07:16', 'Credit Card', 0),
(712, 12, 5196.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-27 07:26:50', 'Credit Card', 0),
(713, 10, 169.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-18 00:55:19', 'Credit Card', 0),
(714, 7, 8495.50, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-17 00:47:13', 'Credit Card', 0),
(715, 15, 4547.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-02 02:58:09', 'Credit Card', 0),
(716, 11, 5827.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-23 02:42:12', 'Credit Card', 0),
(717, 9, 2998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-28 14:21:34', 'Credit Card', 0),
(718, 14, 577.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 13:54:33', 'Credit Card', 0),
(719, 16, 3898.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-19 05:15:53', 'Credit Card', 0),
(720, 12, 1499.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-22 00:12:39', 'Credit Card', 0),
(721, 13, 12172.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-05 07:27:54', 'Credit Card', 0),
(722, 9, 3200.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-09 10:16:15', 'Credit Card', 0),
(723, 16, 4039.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-07 08:59:48', 'Credit Card', 0),
(724, 7, 2847.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-26 12:57:03', 'Credit Card', 0),
(725, 9, 2178.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-13 12:10:55', 'Credit Card', 0),
(726, 15, 4747.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-12 13:22:02', 'Credit Card', 0),
(727, 14, 1949.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-08 09:04:24', 'Credit Card', 0),
(728, 16, 3977.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-27 02:07:16', 'Credit Card', 0),
(729, 9, 4598.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-08 01:57:09', 'Credit Card', 0),
(730, 8, 10298.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-10 00:35:12', 'Credit Card', 0),
(731, 13, 1699.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-01 00:30:38', 'Credit Card', 0),
(732, 16, 1599.96, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-18 12:44:53', 'Credit Card', 0),
(733, 14, 17594.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-21 11:11:44', 'Credit Card', 0),
(734, 15, 239.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-04 12:34:47', 'Credit Card', 0),
(735, 7, 8446.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-07-28 04:44:36', 'Credit Card', 0),
(736, 10, 13695.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-12 11:22:56', 'Credit Card', 0),
(737, 14, 1278.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-26 07:31:30', 'Credit Card', 0),
(738, 12, 1048.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-23 03:52:18', 'Credit Card', 0),
(739, 11, 7248.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-14 03:02:02', 'Credit Card', 0),
(740, 7, 199.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-03 05:30:35', 'Credit Card', 0),
(741, 13, 799.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-08 06:10:40', 'Credit Card', 0),
(742, 10, 7498.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-23 10:39:44', 'Credit Card', 0),
(743, 16, 174.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-06 06:01:24', 'Credit Card', 0),
(744, 13, 3199.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-10 10:40:26', 'Credit Card', 0),
(745, 10, 2858.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-28 06:09:09', 'Credit Card', 0),
(746, 14, 6797.97, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-23 11:37:05', 'Credit Card', 0),
(747, 12, 2607.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-17 07:08:11', 'Credit Card', 0),
(748, 16, 1437.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-04 11:19:07', 'Credit Card', 0),
(749, 8, 999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-19 06:58:01', 'Credit Card', 0),
(750, 9, 1998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-13 05:30:06', 'Credit Card', 0),
(751, 8, 12595.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-12 01:34:10', 'Credit Card', 0),
(752, 12, 4847.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-28 01:27:41', 'Credit Card', 0),
(753, 11, 6858.48, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-23 02:15:25', 'Credit Card', 0),
(754, 12, 199.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-13 01:03:37', 'Credit Card', 0),
(755, 9, 968.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-03 05:05:15', 'Credit Card', 0),
(756, 9, 999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-10 08:13:51', 'Credit Card', 0),
(757, 12, 10296.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-08-23 11:41:27', 'Credit Card', 0),
(758, 7, 8395.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-03 09:04:33', 'Credit Card', 0),
(759, 7, 1958.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-08 06:09:57', 'Credit Card', 0),
(760, 9, 8297.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-25 11:48:07', 'Credit Card', 0),
(761, 13, 4066.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-13 12:57:13', 'Credit Card', 0),
(762, 12, 5398.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-22 12:35:21', 'Credit Card', 0),
(763, 7, 6895.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-02 01:33:32', 'Credit Card', 0),
(764, 13, 9097.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-18 09:23:35', 'Credit Card', 0),
(765, 15, 5897.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-09 08:23:05', 'Credit Card', 0),
(766, 16, 4457.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-10 04:53:36', 'Credit Card', 0),
(767, 12, 4598.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-23 10:05:29', 'Credit Card', 0),
(768, 8, 6654.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-02 13:08:55', 'Credit Card', 0),
(769, 7, 3798.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-05 05:27:00', 'Credit Card', 0),
(770, 10, 4758.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-15 12:36:47', 'Credit Card', 0),
(771, 9, 924.96, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-27 07:15:55', 'Credit Card', 0),
(772, 13, 3398.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-22 10:36:03', 'Credit Card', 0),
(773, 16, 6396.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-17 02:44:31', 'Credit Card', 0),
(774, 13, 3148.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-15 06:11:37', 'Credit Card', 0),
(775, 16, 4167.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-20 07:46:53', 'Credit Card', 0),
(776, 16, 6499.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-09-22 07:19:01', 'Credit Card', 0),
(777, 9, 3349.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-03 12:05:29', 'Credit Card', 0),
(778, 13, 9445.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-20 04:35:20', 'Credit Card', 0),
(779, 15, 179.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-27 13:24:35', 'Credit Card', 0),
(780, 14, 1959.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-27 13:46:32', 'Credit Card', 0),
(781, 13, 2755.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-07 07:09:00', 'Credit Card', 0),
(782, 8, 9935.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-07 03:55:40', 'Credit Card', 0),
(783, 7, 799.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-02 05:27:37', 'Credit Card', 0),
(784, 12, 3277.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 09:42:00', 'Credit Card', 0),
(785, 11, 2998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-22 00:46:35', 'Credit Card', 0),
(786, 10, 11096.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-21 13:28:56', 'Credit Card', 0),
(787, 11, 11897.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-16 13:00:52', 'Credit Card', 0),
(788, 9, 4156.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-13 02:21:17', 'Credit Card', 0),
(789, 12, 4098.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-25 11:01:23', 'Credit Card', 0),
(790, 12, 11496.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-27 07:34:35', 'Credit Card', 0),
(791, 9, 6195.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-24 08:51:37', 'Credit Card', 0),
(792, 10, 8798.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-10-20 11:53:25', 'Credit Card', 0),
(793, 8, 11499.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-26 14:32:59', 'Credit Card', 0),
(794, 11, 4346.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-01 00:54:56', 'Credit Card', 0),
(795, 7, 6599.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-07 00:28:03', 'Credit Card', 0),
(796, 13, 999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-20 10:51:09', 'Credit Card', 0),
(797, 10, 12696.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-01 08:08:35', 'Credit Card', 0),
(798, 14, 16597.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-22 10:38:59', 'Credit Card', 0),
(799, 13, 4806.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-17 14:25:56', 'Credit Card', 0),
(800, 14, 349.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-09 05:22:47', 'Credit Card', 0),
(801, 10, 1799.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-22 01:06:06', 'Credit Card', 0),
(802, 10, 2147.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-17 10:07:53', 'Credit Card', 0),
(803, 10, 479.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-21 12:28:12', 'Credit Card', 0),
(804, 13, 5467.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-17 14:43:20', 'Credit Card', 0),
(805, 9, 7997.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 04:59:48', 'Credit Card', 0),
(806, 9, 339.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-16 09:18:08', 'Credit Card', 0),
(807, 13, 2299.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-18 13:03:15', 'Credit Card', 0),
(808, 11, 3277.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-07 04:24:08', 'Credit Card', 0),
(809, 8, 6895.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-04 08:35:55', 'Credit Card', 0),
(810, 7, 6598.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-26 11:02:36', 'Credit Card', 0),
(811, 12, 2499.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-16 02:11:33', 'Credit Card', 0),
(812, 10, 3398.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-09 04:15:35', 'Credit Card', 0),
(813, 13, 5799.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-07 01:17:37', 'Credit Card', 0),
(814, 13, 4497.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-20 08:04:17', 'Credit Card', 0),
(815, 9, 1377.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-11 09:51:54', 'Credit Card', 0),
(816, 15, 9397.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-03 03:22:11', 'Credit Card', 0),
(817, 8, 8797.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-27 06:27:54', 'Credit Card', 0),
(818, 10, 3199.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-05 06:30:25', 'Credit Card', 0),
(819, 10, 2598.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-22 08:56:33', 'Credit Card', 0),
(820, 12, 2599.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-12 11:39:49', 'Credit Card', 0),
(821, 14, 7455.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-11-26 03:13:43', 'Credit Card', 0),
(822, 8, 4396.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-23 14:16:45', 'Credit Card', 0),
(823, 10, 8197.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-21 12:21:03', 'Credit Card', 0),
(824, 16, 5279.96, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-09 01:32:00', 'Credit Card', 0),
(825, 16, 10197.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-28 11:20:50', 'Credit Card', 0),
(826, 10, 1878.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-15 10:07:26', 'Credit Card', 0),
(827, 15, 3598.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-17 03:15:27', 'Credit Card', 0),
(828, 7, 2698.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-20 01:51:04', 'Credit Card', 0),
(829, 8, 1597.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-16 14:11:02', 'Credit Card', 0),
(830, 13, 7096.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-18 00:14:22', 'Credit Card', 0),
(831, 13, 4798.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-14 03:28:17', 'Credit Card', 0),
(832, 10, 8955.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-02 07:31:45', 'Credit Card', 0),
(833, 14, 5298.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-10 05:10:09', 'Credit Card', 0),
(834, 13, 2899.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-06 02:48:02', 'Credit Card', 0),
(835, 7, 6498.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-16 02:19:42', 'Credit Card', 0),
(836, 9, 858.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-03 10:06:53', 'Credit Card', 0),
(837, 15, 1598.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-02 02:28:59', 'Credit Card', 0),
(838, 9, 9698.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-21 07:31:45', 'Credit Card', 0),
(839, 16, 5596.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-14 03:47:04', 'Credit Card', 0),
(840, 12, 2998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-28 09:43:54', 'Credit Card', 0),
(841, 9, 15497.97, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-18 04:05:11', 'Credit Card', 0),
(842, 13, 11598.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-25 00:44:17', 'Credit Card', 0),
(843, 15, 9194.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-17 12:24:06', 'Credit Card', 0),
(844, 13, 3798.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-24 02:29:33', 'Credit Card', 0),
(845, 12, 3997.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-07 09:18:04', 'Credit Card', 0),
(846, 14, 13275.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-27 07:34:01', 'Credit Card', 0),
(847, 16, 6221.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-25 13:42:35', 'Credit Card', 0),
(848, 14, 1498.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-12 04:57:55', 'Credit Card', 0),
(849, 10, 1847.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-07 08:23:45', 'Credit Card', 0),
(850, 13, 8296.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-05 13:14:51', 'Credit Card', 0),
(851, 14, 7798.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-12 00:21:49', 'Credit Card', 0),
(852, 11, 6599.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-18 06:24:33', 'Credit Card', 0),
(853, 12, 7147.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-10 08:56:01', 'Credit Card', 0),
(854, 12, 1799.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-11 08:36:41', 'Credit Card', 0),
(855, 12, 3617.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-20 04:56:12', 'Credit Card', 0),
(856, 10, 847.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-09 12:50:20', 'Credit Card', 0),
(857, 16, 3299.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-19 03:53:52', 'Credit Card', 0),
(858, 12, 4898.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-16 06:35:25', 'Credit Card', 0),
(859, 10, 6197.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-06 03:52:37', 'Credit Card', 0),
(860, 12, 7295.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-03 12:05:04', 'Credit Card', 0),
(861, 10, 959.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-01 13:08:15', 'Credit Card', 0),
(862, 9, 599.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-02 05:46:13', 'Credit Card', 0),
(863, 12, 6397.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-27 03:28:42', 'Credit Card', 0),
(864, 9, 3596.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-11 04:19:10', 'Credit Card', 0),
(865, 13, 1899.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-25 07:32:48', 'Credit Card', 0),
(866, 15, 2399.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-08 14:20:49', 'Credit Card', 0),
(867, 15, 11497.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-15 09:40:51', 'Credit Card', 0),
(868, 13, 9695.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-11 01:52:57', 'Credit Card', 0),
(869, 10, 2399.96, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-21 11:20:10', 'Credit Card', 0),
(870, 14, 3549.48, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-02 00:23:52', 'Credit Card', 0),
(871, 14, 300.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-17 09:37:40', 'Credit Card', 0),
(872, 15, 8398.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-01-12 11:35:13', 'Credit Card', 0),
(873, 10, 4336.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-17 04:33:07', 'Credit Card', 0),
(874, 16, 3098.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-04 13:02:44', 'Credit Card', 0),
(875, 11, 5198.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-05 09:09:58', 'Credit Card', 0),
(876, 11, 8697.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-07 03:50:33', 'Credit Card', 0),
(877, 8, 4597.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-23 13:44:20', 'Credit Card', 0),
(878, 12, 6799.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-14 01:09:28', 'Credit Card', 0),
(879, 7, 219.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-27 14:04:25', 'Credit Card', 0),
(880, 13, 6795.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-16 13:53:30', 'Credit Card', 0),
(881, 12, 3639.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-16 00:04:24', 'Credit Card', 0),
(882, 8, 3498.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-19 11:14:42', 'Credit Card', 0),
(883, 15, 4497.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-04 00:51:33', 'Credit Card', 0),
(884, 10, 9995.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-09 06:57:27', 'Credit Card', 0),
(885, 9, 2900.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-24 02:00:29', 'Credit Card', 0),
(886, 16, 239.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-22 02:49:32', 'Credit Card', 0),
(887, 13, 109.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-01 02:26:41', 'Credit Card', 0),
(888, 11, 4826.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-28 10:35:35', 'Credit Card', 0),
(889, 11, 1999.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-16 05:23:54', 'Credit Card', 0),
(890, 7, 2899.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-24 14:49:52', 'Credit Card', 0),
(891, 12, 5599.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-01 08:11:36', 'Credit Card', 0),
(892, 8, 3200.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-13 00:03:09', 'Credit Card', 0),
(893, 14, 998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-04 00:26:25', 'Credit Card', 0),
(894, 15, 11896.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-16 12:05:02', 'Credit Card', 0),
(895, 16, 9097.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-14 00:35:42', 'Credit Card', 0),
(896, 13, 999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-25 00:32:00', 'Credit Card', 0),
(897, 15, 6396.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-02 03:27:36', 'Credit Card', 0),
(898, 8, 3998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-03 00:35:33', 'Credit Card', 0),
(899, 14, 3097.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-04 09:38:46', 'Credit Card', 0),
(900, 7, 2146.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-10 12:38:40', 'Credit Card', 0),
(901, 16, 6677.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-02 11:17:44', 'Credit Card', 0),
(902, 12, 4398.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-04 00:01:12', 'Credit Card', 0),
(903, 13, 2748.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-22 07:51:40', 'Credit Card', 0),
(904, 9, 300.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-17 03:41:28', 'Credit Card', 0),
(905, 15, 3197.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-18 12:25:03', 'Credit Card', 0),
(906, 13, 8697.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-15 05:15:48', 'Credit Card', 0),
(907, 8, 10297.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-07 08:45:45', 'Credit Card', 0),
(908, 11, 399.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-11 13:20:44', 'Credit Card', 0),
(909, 7, 2895.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-06 06:33:27', 'Credit Card', 0),
(910, 13, 3649.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-26 00:19:41', 'Credit Card', 0),
(911, 9, 379.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-20 10:45:42', 'Credit Card', 0),
(912, 10, 2848.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-02 09:18:04', 'Credit Card', 0),
(913, 9, 2498.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-22 09:08:15', 'Credit Card', 0),
(914, 11, 6599.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-05 12:37:08', 'Credit Card', 0),
(915, 16, 9698.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-03-17 03:39:57', 'Credit Card', 0),
(916, 12, 2998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-19 11:44:30', 'Credit Card', 0),
(917, 9, 1357.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-05 02:56:29', 'Credit Card', 0),
(918, 13, 2518.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-24 13:06:39', 'Credit Card', 0),
(919, 13, 2856.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-27 09:51:23', 'Credit Card', 0),
(920, 11, 6677.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-27 04:08:45', 'Credit Card', 0),
(921, 8, 5206.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 12:07:38', 'Credit Card', 0),
(922, 12, 4205.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 02:41:09', 'Credit Card', 0),
(923, 16, 2816.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-13 09:58:55', 'Credit Card', 0),
(924, 8, 2998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-23 09:33:50', 'Credit Card', 0),
(925, 11, 1749.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-08 04:22:49', 'Credit Card', 0),
(926, 12, 5196.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-05 05:04:34', 'Credit Card', 0),
(927, 14, 5998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-25 01:42:38', 'Credit Card', 0),
(928, 10, 5858.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-03 11:05:45', 'Credit Card', 0),
(929, 11, 9095.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 14:38:00', 'Credit Card', 0),
(930, 16, 7796.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-10 11:13:03', 'Credit Card', 0),
(931, 15, 1899.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-22 03:25:22', 'Credit Card', 0),
(932, 16, 9497.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-12 04:36:00', 'Credit Card', 0),
(933, 11, 699.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-04-21 08:59:18', 'Credit Card', 0),
(934, 14, 3007.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-01 07:20:34', 'Credit Card', 0),
(935, 9, 2197.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-10 07:19:07', 'Credit Card', 0),
(936, 15, 349.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-24 10:40:29', 'Credit Card', 0),
(937, 13, 3598.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-11 10:44:18', 'Credit Card', 0),
(938, 13, 1499.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-28 14:52:48', 'Credit Card', 0),
(939, 15, 497.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-02 05:08:34', 'Credit Card', 0),
(940, 12, 2399.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-14 02:56:02', 'Credit Card', 0),
(941, 16, 7772.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-02 05:48:47', 'Credit Card', 0),
(942, 7, 8695.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-09 06:35:35', 'Credit Card', 0),
(943, 12, 8096.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-23 07:51:20', 'Credit Card', 0),
(944, 8, 6067.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-03 05:22:51', 'Credit Card', 0),
(945, 13, 5117.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-07 13:28:33', 'Credit Card', 0),
(946, 9, 3899.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-21 07:35:56', 'Credit Card', 0),
(947, 16, 119.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-25 09:46:45', 'Credit Card', 0),
(948, 10, 6697.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-12 06:18:07', 'Credit Card', 0),
(949, 12, 4399.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-12 00:02:48', 'Credit Card', 0),
(950, 10, 6846.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-03 01:29:22', 'Credit Card', 0),
(951, 7, 239.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-27 11:56:00', 'Credit Card', 0),
(952, 13, 4614.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-18 10:08:50', 'Credit Card', 0),
(953, 7, 600.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-06 10:38:48', 'Credit Card', 0),
(954, 8, 2357.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-26 08:54:30', 'Credit Card', 0),
(955, 10, 3169.49, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-04 14:48:27', 'Credit Card', 0),
(956, 16, 5298.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-16 09:49:45', 'Credit Card', 0),
(957, 7, 3998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-14 12:23:50', 'Credit Card', 0),
(958, 8, 3237.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-27 11:38:05', 'Credit Card', 0),
(959, 12, 169.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-08 13:13:54', 'Credit Card', 0),
(960, 7, 999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-03 10:48:13', 'Credit Card', 0),
(961, 12, 1450.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-12 05:12:21', 'Credit Card', 0),
(962, 9, 99.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-28 12:09:52', 'Credit Card', 0),
(963, 10, 6297.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-25 02:45:25', 'Credit Card', 0),
(964, 13, 3998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-04 11:04:53', 'Credit Card', 0),
(965, 14, 7997.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-28 14:33:06', 'Credit Card', 0),
(966, 12, 6146.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 06:33:55', 'Credit Card', 0),
(967, 15, 7997.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-03 12:38:03', 'Credit Card', 0),
(968, 12, 10397.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-06 03:53:45', 'Credit Card', 0),
(969, 15, 1939.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-06 07:37:15', 'Credit Card', 0),
(970, 16, 174.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-05 08:29:28', 'Credit Card', 0),
(971, 10, 1138.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-27 08:58:06', 'Credit Card', 0),
(972, 10, 2597.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-18 08:17:30', 'Credit Card', 0),
(973, 7, 6497.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-17 07:29:03', 'Credit Card', 0),
(974, 8, 639.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-10 05:59:32', 'Credit Card', 0),
(975, 7, 629.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-24 07:05:52', 'Credit Card', 0),
(976, 14, 4147.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-05 10:52:33', 'Credit Card', 0),
(977, 16, 3998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-25 06:37:23', 'Credit Card', 0),
(978, 9, 5498.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 08:05:44', 'Credit Card', 0),
(979, 10, 2946.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 14:15:37', 'Credit Card', 0),
(980, 15, 8296.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-05 14:51:20', 'Credit Card', 0),
(981, 13, 3798.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-13 13:53:18', 'Credit Card', 0),
(982, 11, 7097.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-21 02:28:36', 'Credit Card', 0),
(983, 11, 3298.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-21 00:53:30', 'Credit Card', 0),
(984, 15, 5597.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-19 03:54:10', 'Credit Card', 0),
(985, 12, 3199.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-06 04:07:11', 'Credit Card', 0),
(986, 16, 3547.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-15 02:54:53', 'Credit Card', 0),
(987, 8, 1918.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-25 01:06:12', 'Credit Card', 0),
(988, 10, 3598.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-12 09:05:16', 'Credit Card', 0),
(989, 7, 7397.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-11 11:29:28', 'Credit Card', 0),
(990, 9, 3627.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-07 10:33:18', 'Credit Card', 0),
(991, 9, 5599.96, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-20 13:09:56', 'Credit Card', 0),
(992, 14, 4196.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-14 03:34:04', 'Credit Card', 0),
(993, 16, 10199.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-22 00:04:25', 'Credit Card', 0),
(994, 16, 10096.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-03 03:16:27', 'Credit Card', 0),
(995, 9, 349.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-17 09:56:19', 'Credit Card', 0),
(996, 14, 2299.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-05 05:30:30', 'Credit Card', 0),
(997, 7, 3997.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-16 11:39:35', 'Credit Card', 0),
(998, 12, 999.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-06 10:34:49', 'Credit Card', 0),
(999, 15, 6757.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-17 00:01:14', 'Credit Card', 0),
(1000, 15, 3548.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-13 02:04:01', 'Credit Card', 0),
(1001, 11, 7047.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-06 03:21:33', 'Credit Card', 0),
(1002, 9, 9396.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-07 01:04:12', 'Credit Card', 0),
(1003, 13, 2998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-12 09:10:24', 'Credit Card', 0),
(1004, 12, 2499.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-04 08:01:49', 'Credit Card', 0),
(1005, 15, 9176.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-07-04 01:28:10', 'Credit Card', 0),
(1006, 16, 5026.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-10 02:31:30', 'Credit Card', 0),
(1007, 12, 1259.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-12 10:18:16', 'Credit Card', 0),
(1008, 16, 99.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-01 04:10:53', 'Credit Card', 0),
(1009, 11, 6497.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 07:55:14', 'Credit Card', 0),
(1010, 10, 6536.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-04 03:05:42', 'Credit Card', 0),
(1011, 12, 4298.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-24 07:00:45', 'Credit Card', 0),
(1012, 9, 7196.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-23 11:51:08', 'Credit Card', 0),
(1013, 16, 9715.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-03 07:55:20', 'Credit Card', 0),
(1014, 10, 3248.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-10 09:09:05', 'Credit Card', 0),
(1015, 13, 9095.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 05:31:50', 'Credit Card', 0),
(1016, 12, 4999.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 01:11:59', 'Credit Card', 0);
INSERT INTO `orders` (`order_id`, `user_id`, `total_amount`, `order_status`, `shipping_name`, `shipping_address`, `shipping_city`, `shipping_state`, `shipping_zip`, `shipping_country`, `shipping_phone`, `order_date`, `payment_method`, `is_flagged`) VALUES
(1017, 11, 2499.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-09 09:11:15', 'Credit Card', 0),
(1018, 15, 13296.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 13:35:13', 'Credit Card', 0),
(1019, 9, 3498.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 00:04:07', 'Credit Card', 0),
(1020, 9, 7897.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-20 11:21:26', 'Credit Card', 0),
(1021, 14, 3295.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-21 02:58:02', 'Credit Card', 0),
(1022, 11, 4999.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-28 07:14:40', 'Credit Card', 0),
(1023, 15, 3348.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-03 14:47:00', 'Credit Card', 0),
(1024, 15, 1948.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 02:07:14', 'Credit Card', 0),
(1025, 9, 12996.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-13 02:20:12', 'Credit Card', 0),
(1026, 14, 12305.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-25 04:07:52', 'Credit Card', 0),
(1027, 13, 1357.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-11 10:15:17', 'Credit Card', 0),
(1028, 14, 3448.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-18 01:56:59', 'Credit Card', 0),
(1029, 11, 1859.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-13 01:24:02', 'Credit Card', 0),
(1030, 12, 4867.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-13 03:24:27', 'Credit Card', 0),
(1031, 11, 1698.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-19 08:15:10', 'Credit Card', 0),
(1032, 9, 9098.50, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 09:43:41', 'Credit Card', 0),
(1033, 8, 918.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-10 12:12:16', 'Credit Card', 0),
(1034, 14, 1949.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-26 05:13:58', 'Credit Card', 0),
(1035, 15, 7299.96, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 01:00:47', 'Credit Card', 0),
(1036, 11, 5172.49, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 10:11:21', 'Credit Card', 0),
(1037, 15, 5797.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 06:32:10', 'Credit Card', 0),
(1038, 16, 11697.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 03:07:34', 'Credit Card', 0),
(1039, 16, 514.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 06:23:52', 'Credit Card', 0),
(1040, 12, 2257.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-04 08:12:57', 'Credit Card', 0),
(1041, 16, 999.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 08:42:14', 'Credit Card', 0),
(1042, 15, 10995.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 05:58:16', 'Credit Card', 0),
(1043, 10, 1749.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 08:30:41', 'Credit Card', 0),
(1044, 8, 1450.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-08 01:25:51', 'Credit Card', 0),
(1045, 11, 556.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-01 14:47:58', 'Credit Card', 0),
(1046, 12, 10254.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-20 07:20:11', 'Credit Card', 0),
(1047, 10, 1799.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-12 01:12:57', 'Credit Card', 0),
(1048, 8, 7698.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 11:56:10', 'Credit Card', 0),
(1049, 10, 877.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-06 02:44:50', 'Credit Card', 0),
(1050, 10, 5798.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-02 14:13:40', 'Credit Card', 0),
(1051, 8, 5297.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-13 01:58:58', 'Credit Card', 0),
(1052, 10, 5896.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-17 14:33:25', 'Credit Card', 0),
(1053, 16, 139.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-11 05:42:26', 'Credit Card', 0),
(1054, 9, 7075.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-22 13:31:59', 'Credit Card', 0),
(1055, 9, 2417.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-20 01:09:22', 'Credit Card', 0),
(1056, 7, 1597.99, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-28 08:03:49', 'Credit Card', 0),
(1057, 13, 109.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 12:21:18', 'Credit Card', 0),
(1058, 14, 12295.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-05 02:23:18', 'Credit Card', 0),
(1059, 9, 79.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 12:46:19', 'Credit Card', 0),
(1060, 8, 7998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-12 04:19:32', 'Credit Card', 0),
(1061, 12, 699.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 11:19:00', 'Credit Card', 0),
(1062, 10, 11997.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 08:44:08', 'Credit Card', 0),
(1063, 16, 2299.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-24 10:27:37', 'Credit Card', 0),
(1064, 15, 1298.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-16 12:18:17', 'Credit Card', 0),
(1065, 12, 7696.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-25 08:18:25', 'Credit Card', 0),
(1066, 7, 279.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-14 11:52:54', 'Credit Card', 0),
(1067, 11, 5798.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-11 05:56:58', 'Credit Card', 0),
(1068, 9, 5398.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-09 12:57:41', 'Credit Card', 0),
(1069, 16, 349.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-08 07:58:18', 'Credit Card', 0),
(1070, 9, 2499.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-02 10:23:02', 'Credit Card', 0),
(1071, 16, 2298.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-01 02:48:27', 'Credit Card', 0),
(1072, 9, 2998.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-10-21 03:42:43', 'Credit Card', 0),
(1073, 13, 1799.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-09 03:07:38', 'Credit Card', 0),
(1074, 11, 237.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-14 07:13:13', 'Credit Card', 0),
(1075, 14, 2999.50, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-01 10:41:09', 'Credit Card', 0),
(1076, 11, 11997.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-07 14:00:33', 'Credit Card', 0),
(1077, 9, 11916.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-01 09:54:47', 'Credit Card', 0),
(1078, 16, 1999.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-18 02:18:45', 'Credit Card', 0),
(1079, 8, 7898.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-09 14:31:31', 'Credit Card', 0),
(1080, 8, 9897.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-09 11:08:11', 'Credit Card', 0),
(1081, 7, 1598.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-20 02:01:16', 'Credit Card', 0),
(1082, 14, 4996.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-16 12:51:45', 'Credit Card', 0),
(1083, 11, 5497.98, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-25 03:10:06', 'Credit Card', 0),
(1084, 14, 1899.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-05 05:30:49', 'Credit Card', 0),
(1085, 16, 788.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-25 13:56:54', 'Credit Card', 0),
(1086, 15, 7194.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-23 07:09:17', 'Credit Card', 0),
(1087, 8, 6400.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 10:27:16', 'Credit Card', 0),
(1088, 15, 8895.00, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-02 05:46:44', 'Credit Card', 0),
(1089, 15, 1899.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-04 06:39:25', 'Credit Card', 0),
(1090, 16, 499.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 05:49:13', 'Credit Card', 0),
(1091, 12, 3998.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-21 00:49:33', 'Credit Card', 0),
(1092, 15, 7347.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-03 06:03:29', 'Credit Card', 0),
(1093, 15, 1956.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 00:26:56', 'Credit Card', 0),
(1094, 7, 4597.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-21 06:08:56', 'Credit Card', 0),
(1095, 13, 4998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-24 02:49:09', 'Credit Card', 0),
(1096, 10, 6298.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-25 09:37:28', 'Credit Card', 0),
(1097, 15, 6597.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-27 14:44:42', 'Credit Card', 0),
(1098, 14, 1998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-21 12:54:04', 'Credit Card', 0),
(1099, 7, 2899.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 09:49:02', 'Credit Card', 0),
(1100, 16, 1997.98, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-23 02:38:36', 'Credit Card', 0),
(1101, 9, 4797.99, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-06 14:19:01', 'Credit Card', 0),
(1102, 8, 755.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 03:58:19', 'Credit Card', 0),
(1103, 9, 519.96, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-22 14:25:05', 'Credit Card', 0),
(1104, 16, 1499.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-03 02:35:25', 'Credit Card', 0),
(1105, 16, 799.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-12 01:55:45', 'Credit Card', 0),
(1106, 15, 3857.50, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-11 12:20:34', 'Credit Card', 0),
(1107, 13, 2448.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-19 07:25:57', 'Credit Card', 0),
(1108, 10, 499.00, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-19 08:41:45', 'Credit Card', 0),
(1109, 14, 1139.96, 'Shipped', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-18 01:18:56', 'Credit Card', 0),
(1110, 9, 5196.99, 'Pending', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-13 01:34:00', 'Credit Card', 0),
(1111, 12, 6797.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-20 03:10:23', 'Credit Card', 0),
(1112, 8, 998.00, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-19 13:02:00', 'Credit Card', 0),
(1113, 7, 399.97, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-26 00:18:17', 'Credit Card', 0),
(1114, 15, 437.98, 'Completed', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-28 03:52:07', 'Credit Card', 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price_at_purchase` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`order_item_id`, `order_id`, `product_id`, `quantity`, `price_at_purchase`) VALUES
(1, 1, 4, 1, 649.00),
(2, 2, 4, 1, 649.00),
(3, 3, 4, 1, 649.00),
(4, 4, 4, 1, 649.00),
(5, 5, 4, 1, 649.00),
(6, 6, 8, 1, 799.00),
(7, 7, 8, 1, 799.00),
(8, 8, 4, 1, 649.00),
(9, 8, 8, 1, 799.00),
(10, 8, 6, 1, 1450.00),
(11, 9, 4, 1, 649.00),
(12, 10, 4, 1, 649.00),
(28, 24, 117, 3, 300.00),
(29, 25, 128, 1, 5000.00),
(30, 26, 124, 2, 139.99),
(31, 27, 128, 1, 5000.00),
(32, 28, 128, 1, 5000.00),
(1179, 593, 202, 2, 1899.00),
(1180, 593, 219, 2, 399.99),
(1181, 593, 197, 2, 2599.00),
(1182, 594, 188, 1, 1499.00),
(1183, 594, 182, 2, 2599.00),
(1184, 595, 198, 1, 1499.00),
(1185, 595, 218, 1, 174.99),
(1186, 596, 193, 1, 1599.00),
(1187, 597, 5, 1, 3499.00),
(1188, 598, 219, 2, 399.99),
(1189, 599, 193, 2, 1599.00),
(1190, 599, 184, 1, 2499.00),
(1191, 600, 192, 1, 1749.00),
(1192, 601, 7, 2, 1899.99),
(1193, 601, 196, 1, 2399.00),
(1194, 601, 223, 1, 169.99),
(1195, 602, 1, 1, 2499.99),
(1196, 603, 212, 2, 79.00),
(1197, 603, 124, 1, 139.99),
(1198, 604, 192, 2, 1749.00),
(1199, 604, 191, 2, 1899.00),
(1200, 605, 216, 2, 249.99),
(1201, 605, 198, 1, 1499.00),
(1202, 606, 204, 2, 999.00),
(1203, 606, 4, 1, 649.00),
(1204, 606, 214, 2, 199.00),
(1205, 607, 196, 1, 2399.00),
(1206, 608, 192, 2, 1749.00),
(1207, 608, 181, 2, 4999.00),
(1208, 608, 191, 1, 1899.00),
(1209, 609, 181, 2, 4999.00),
(1210, 610, 207, 2, 999.00),
(1211, 611, 208, 1, 999.00),
(1212, 611, 8, 1, 799.00),
(1213, 612, 211, 2, 139.99),
(1214, 612, 208, 1, 999.00),
(1215, 613, 5, 1, 3499.00),
(1216, 613, 183, 2, 4399.00),
(1217, 614, 209, 2, 99.99),
(1218, 615, 222, 2, 199.99),
(1219, 616, 180, 1, 3299.99),
(1220, 616, 204, 1, 999.00),
(1221, 616, 187, 2, 2299.00),
(1222, 617, 1, 1, 2499.99),
(1223, 617, 199, 2, 2899.00),
(1224, 618, 124, 2, 139.99),
(1225, 618, 202, 1, 1899.00),
(1226, 618, 8, 2, 799.00),
(1227, 619, 219, 2, 399.99),
(1228, 619, 207, 1, 999.00),
(1229, 619, 226, 2, 1799.99),
(1230, 620, 191, 1, 1899.00),
(1231, 620, 213, 1, 49.00),
(1232, 621, 1, 2, 2499.99),
(1233, 621, 219, 1, 399.99),
(1234, 621, 215, 1, 109.99),
(1235, 622, 3, 1, 2999.50),
(1236, 622, 224, 1, 629.99),
(1237, 623, 197, 2, 2599.00),
(1238, 623, 185, 1, 1699.00),
(1239, 623, 198, 2, 1499.00),
(1240, 624, 210, 2, 149.99),
(1241, 624, 6, 2, 1450.00),
(1242, 624, 208, 2, 999.00),
(1243, 625, 193, 2, 1599.00),
(1244, 625, 3, 2, 2999.50),
(1245, 625, 198, 1, 1499.00),
(1246, 626, 215, 1, 109.99),
(1247, 626, 186, 1, 1999.00),
(1248, 626, 117, 2, 300.00),
(1249, 627, 206, 1, 749.00),
(1250, 627, 211, 1, 139.99),
(1251, 628, 2, 2, 1999.00),
(1252, 628, 224, 2, 629.99),
(1253, 629, 186, 1, 1999.00),
(1254, 629, 188, 1, 1499.00),
(1255, 629, 201, 1, 2699.00),
(1256, 630, 186, 1, 1999.00),
(1257, 631, 226, 2, 1799.99),
(1258, 631, 179, 2, 3899.00),
(1259, 631, 8, 2, 799.00),
(1260, 632, 221, 2, 349.99),
(1261, 632, 181, 1, 4999.00),
(1262, 632, 201, 1, 2699.00),
(1263, 633, 227, 2, 499.00),
(1264, 634, 226, 1, 1799.99),
(1265, 635, 205, 1, 959.00),
(1266, 635, 181, 1, 4999.00),
(1267, 635, 199, 2, 2899.00),
(1268, 636, 217, 1, 119.99),
(1269, 637, 190, 2, 2899.00),
(1270, 637, 183, 1, 4399.00),
(1271, 637, 5, 1, 3499.00),
(1272, 638, 197, 1, 2599.00),
(1273, 639, 217, 2, 119.99),
(1274, 639, 200, 2, 3200.00),
(1275, 640, 216, 2, 249.99),
(1276, 640, 179, 2, 3899.00),
(1277, 641, 189, 2, 3199.00),
(1278, 641, 203, 2, 849.00),
(1279, 641, 179, 2, 3899.00),
(1280, 642, 182, 2, 2599.00),
(1281, 642, 191, 2, 1899.00),
(1282, 642, 208, 2, 999.00),
(1283, 643, 2, 1, 1999.00),
(1284, 643, 205, 2, 959.00),
(1285, 644, 189, 1, 3199.00),
(1286, 644, 3, 2, 2999.50),
(1287, 645, 223, 2, 169.99),
(1288, 645, 225, 1, 999.99),
(1289, 646, 203, 1, 849.00),
(1290, 646, 186, 2, 1999.00),
(1291, 647, 183, 1, 4399.00),
(1292, 647, 216, 2, 249.99),
(1293, 648, 188, 2, 1499.00),
(1294, 649, 216, 1, 249.99),
(1295, 650, 8, 2, 799.00),
(1296, 650, 209, 2, 99.99),
(1297, 650, 128, 2, 5000.00),
(1298, 651, 182, 1, 2599.00),
(1299, 651, 179, 1, 3899.00),
(1300, 652, 202, 2, 1899.00),
(1301, 653, 199, 1, 2899.00),
(1302, 653, 219, 1, 399.99),
(1303, 654, 219, 2, 399.99),
(1304, 654, 213, 2, 49.00),
(1305, 654, 195, 2, 1799.00),
(1306, 655, 181, 1, 4999.00),
(1307, 656, 227, 2, 499.00),
(1308, 656, 225, 1, 999.99),
(1309, 656, 220, 2, 429.00),
(1310, 657, 204, 2, 999.00),
(1311, 658, 215, 1, 109.99),
(1312, 658, 3, 2, 2999.50),
(1313, 658, 117, 1, 300.00),
(1314, 659, 1, 2, 2499.99),
(1315, 659, 219, 1, 399.99),
(1316, 659, 187, 1, 2299.00),
(1317, 660, 186, 2, 1999.00),
(1318, 661, 208, 2, 999.00),
(1319, 662, 196, 2, 2399.00),
(1320, 663, 223, 2, 169.99),
(1321, 663, 198, 1, 1499.00),
(1322, 664, 7, 2, 1899.99),
(1323, 664, 183, 2, 4399.00),
(1324, 664, 212, 1, 79.00),
(1325, 665, 193, 1, 1599.00),
(1326, 666, 186, 2, 1999.00),
(1327, 666, 183, 2, 4399.00),
(1328, 666, 189, 2, 3199.00),
(1329, 667, 211, 1, 139.99),
(1330, 667, 117, 1, 300.00),
(1331, 667, 3, 2, 2999.50),
(1332, 668, 199, 2, 2899.00),
(1333, 668, 183, 2, 4399.00),
(1334, 668, 217, 1, 119.99),
(1335, 669, 183, 2, 4399.00),
(1336, 669, 215, 1, 109.99),
(1337, 669, 128, 1, 5000.00),
(1338, 670, 199, 2, 2899.00),
(1339, 670, 194, 2, 1499.00),
(1340, 671, 203, 1, 849.00),
(1341, 671, 222, 2, 199.99),
(1342, 671, 186, 1, 1999.00),
(1343, 672, 224, 2, 629.99),
(1344, 672, 204, 1, 999.00),
(1345, 673, 183, 1, 4399.00),
(1346, 673, 201, 2, 2699.00),
(1347, 673, 202, 2, 1899.00),
(1348, 674, 198, 2, 1499.00),
(1349, 674, 117, 1, 300.00),
(1350, 674, 216, 1, 249.99),
(1351, 675, 190, 2, 2899.00),
(1352, 675, 186, 1, 1999.00),
(1353, 676, 188, 1, 1499.00),
(1354, 676, 186, 1, 1999.00),
(1355, 677, 3, 1, 2999.50),
(1356, 678, 197, 2, 2599.00),
(1357, 678, 181, 1, 4999.00),
(1358, 679, 8, 2, 799.00),
(1359, 680, 216, 1, 249.99),
(1360, 680, 192, 1, 1749.00),
(1361, 681, 217, 1, 119.99),
(1362, 681, 191, 2, 1899.00),
(1363, 682, 190, 2, 2899.00),
(1364, 683, 204, 1, 999.00),
(1365, 683, 199, 1, 2899.00),
(1366, 684, 213, 1, 49.00),
(1367, 685, 208, 1, 999.00),
(1368, 685, 211, 2, 139.99),
(1369, 686, 216, 1, 249.99),
(1370, 686, 226, 2, 1799.99),
(1371, 686, 206, 1, 749.00),
(1372, 687, 2, 2, 1999.00),
(1373, 687, 183, 2, 4399.00),
(1374, 688, 181, 2, 4999.00),
(1375, 689, 207, 2, 999.00),
(1376, 689, 187, 1, 2299.00),
(1377, 689, 184, 1, 2499.00),
(1378, 690, 221, 1, 349.99),
(1379, 690, 225, 2, 999.99),
(1380, 691, 201, 2, 2699.00),
(1381, 691, 206, 2, 749.00),
(1382, 691, 220, 1, 429.00),
(1383, 692, 214, 1, 199.00),
(1384, 692, 204, 2, 999.00),
(1385, 692, 2, 2, 1999.00),
(1386, 693, 209, 2, 99.99),
(1387, 693, 182, 2, 2599.00),
(1388, 694, 204, 1, 999.00),
(1389, 694, 196, 2, 2399.00),
(1390, 694, 210, 1, 149.99),
(1391, 695, 179, 1, 3899.00),
(1392, 695, 205, 1, 959.00),
(1393, 696, 215, 1, 109.99),
(1394, 696, 228, 2, 179.00),
(1395, 696, 193, 1, 1599.00),
(1396, 697, 8, 2, 799.00),
(1397, 697, 188, 2, 1499.00),
(1398, 697, 216, 1, 249.99),
(1399, 698, 193, 2, 1599.00),
(1400, 698, 204, 1, 999.00),
(1401, 699, 117, 2, 300.00),
(1402, 699, 184, 1, 2499.00),
(1403, 700, 198, 2, 1499.00),
(1404, 701, 193, 1, 1599.00),
(1405, 701, 198, 1, 1499.00),
(1406, 701, 187, 1, 2299.00),
(1407, 702, 183, 1, 4399.00),
(1408, 703, 204, 2, 999.00),
(1409, 703, 213, 2, 49.00),
(1410, 704, 200, 1, 3200.00),
(1411, 704, 220, 1, 429.00),
(1412, 705, 212, 1, 79.00),
(1413, 705, 182, 2, 2599.00),
(1414, 705, 3, 1, 2999.50),
(1415, 706, 218, 1, 174.99),
(1416, 707, 200, 1, 3200.00),
(1417, 708, 3, 2, 2999.50),
(1418, 709, 195, 2, 1799.00),
(1419, 709, 209, 1, 99.99),
(1420, 710, 190, 1, 2899.00),
(1421, 710, 185, 2, 1699.00),
(1422, 710, 201, 2, 2699.00),
(1423, 711, 200, 1, 3200.00),
(1424, 711, 226, 2, 1799.99),
(1425, 711, 189, 1, 3199.00),
(1426, 712, 208, 2, 999.00),
(1427, 712, 193, 2, 1599.00),
(1428, 713, 223, 1, 169.99),
(1429, 714, 3, 1, 2999.50),
(1430, 714, 192, 2, 1749.00),
(1431, 714, 208, 2, 999.00),
(1432, 715, 213, 1, 49.00),
(1433, 715, 209, 1, 99.99),
(1434, 715, 183, 1, 4399.00),
(1435, 716, 182, 2, 2599.00),
(1436, 716, 224, 1, 629.99),
(1437, 717, 194, 2, 1499.00),
(1438, 718, 214, 2, 199.00),
(1439, 718, 228, 1, 179.00),
(1440, 719, 190, 1, 2899.00),
(1441, 719, 207, 1, 999.00),
(1442, 720, 198, 1, 1499.00),
(1443, 721, 128, 1, 5000.00),
(1444, 721, 218, 1, 174.99),
(1445, 721, 5, 2, 3499.00),
(1446, 722, 200, 1, 3200.00),
(1447, 723, 217, 2, 119.99),
(1448, 723, 7, 2, 1899.99),
(1449, 724, 209, 2, 99.99),
(1450, 724, 191, 1, 1899.00),
(1451, 724, 206, 1, 749.00),
(1452, 725, 228, 1, 179.00),
(1453, 725, 225, 2, 999.99),
(1454, 726, 187, 1, 2299.00),
(1455, 726, 4, 1, 649.00),
(1456, 726, 195, 1, 1799.00),
(1457, 727, 6, 1, 1450.00),
(1458, 727, 216, 2, 249.99),
(1459, 728, 228, 1, 179.00),
(1460, 728, 187, 1, 2299.00),
(1461, 728, 188, 1, 1499.00),
(1462, 729, 199, 1, 2899.00),
(1463, 729, 185, 1, 1699.00),
(1464, 730, 184, 2, 2499.00),
(1465, 730, 117, 1, 300.00),
(1466, 730, 128, 1, 5000.00),
(1467, 731, 185, 1, 1699.00),
(1468, 732, 223, 2, 169.99),
(1469, 732, 224, 2, 629.99),
(1470, 733, 197, 2, 2599.00),
(1471, 733, 183, 2, 4399.00),
(1472, 733, 195, 2, 1799.00),
(1473, 734, 217, 2, 119.99),
(1474, 735, 226, 1, 1799.99),
(1475, 735, 203, 1, 849.00),
(1476, 735, 190, 2, 2899.00),
(1477, 736, 204, 2, 999.00),
(1478, 736, 179, 2, 3899.00),
(1479, 736, 179, 1, 3899.00),
(1480, 737, 124, 2, 139.99),
(1481, 737, 208, 1, 999.00),
(1482, 738, 213, 1, 49.00),
(1483, 738, 204, 1, 999.00),
(1484, 739, 6, 1, 1450.00),
(1485, 739, 199, 2, 2899.00),
(1486, 740, 214, 1, 199.00),
(1487, 741, 219, 2, 399.99),
(1488, 742, 128, 1, 5000.00),
(1489, 742, 198, 1, 1499.00),
(1490, 742, 207, 1, 999.00),
(1491, 743, 218, 1, 174.99),
(1492, 744, 189, 1, 3199.00),
(1493, 745, 205, 1, 959.00),
(1494, 745, 7, 1, 1899.99),
(1495, 746, 201, 2, 2699.00),
(1496, 746, 224, 2, 629.99),
(1497, 746, 124, 1, 139.99),
(1498, 747, 4, 1, 649.00),
(1499, 747, 208, 1, 999.00),
(1500, 747, 205, 1, 959.00),
(1501, 748, 8, 1, 799.00),
(1502, 748, 227, 1, 499.00),
(1503, 748, 211, 1, 139.99),
(1504, 749, 204, 1, 999.00),
(1505, 750, 207, 2, 999.00),
(1506, 751, 226, 2, 1799.99),
(1507, 751, 190, 2, 2899.00),
(1508, 751, 193, 2, 1599.00),
(1509, 752, 202, 1, 1899.00),
(1510, 752, 221, 1, 349.99),
(1511, 752, 182, 1, 2599.00),
(1512, 753, 224, 2, 629.99),
(1513, 753, 3, 1, 2999.50),
(1514, 753, 182, 1, 2599.00),
(1515, 754, 222, 1, 199.99),
(1516, 755, 217, 1, 119.99),
(1517, 755, 203, 1, 849.00),
(1518, 756, 207, 1, 999.00),
(1519, 757, 183, 1, 4399.00),
(1520, 757, 209, 1, 99.99),
(1521, 757, 190, 2, 2899.00),
(1522, 758, 4, 2, 649.00),
(1523, 758, 5, 1, 3499.00),
(1524, 758, 195, 2, 1799.00),
(1525, 759, 205, 1, 959.00),
(1526, 759, 204, 1, 999.00),
(1527, 760, 191, 1, 1899.00),
(1528, 760, 189, 2, 3199.00),
(1529, 761, 202, 1, 1899.00),
(1530, 761, 207, 2, 999.00),
(1531, 761, 223, 1, 169.99),
(1532, 762, 201, 2, 2699.00),
(1533, 763, 187, 2, 2299.00),
(1534, 763, 214, 2, 199.00),
(1535, 763, 191, 1, 1899.00),
(1536, 764, 187, 1, 2299.00),
(1537, 764, 214, 1, 199.00),
(1538, 764, 180, 2, 3299.99),
(1539, 765, 7, 1, 1899.99),
(1540, 765, 2, 2, 1999.00),
(1541, 766, 218, 2, 174.99),
(1542, 766, 2, 2, 1999.00),
(1543, 766, 215, 1, 109.99),
(1544, 767, 226, 2, 1799.99),
(1545, 767, 208, 1, 999.00),
(1546, 768, 220, 2, 429.00),
(1547, 768, 207, 2, 999.00),
(1548, 768, 202, 2, 1899.00),
(1549, 769, 194, 1, 1499.00),
(1550, 769, 187, 1, 2299.00),
(1551, 770, 5, 1, 3499.00),
(1552, 770, 224, 1, 629.99),
(1553, 770, 224, 1, 629.99),
(1554, 771, 221, 1, 349.99),
(1555, 771, 218, 1, 174.99),
(1556, 771, 222, 2, 199.99),
(1557, 772, 196, 1, 2399.00),
(1558, 772, 207, 1, 999.00),
(1559, 773, 227, 2, 499.00),
(1560, 773, 201, 2, 2699.00),
(1561, 774, 190, 1, 2899.00),
(1562, 774, 216, 1, 249.99),
(1563, 775, 223, 1, 169.99),
(1564, 775, 186, 2, 1999.00),
(1565, 776, 180, 1, 3299.99),
(1566, 776, 200, 1, 3200.00),
(1567, 777, 6, 1, 1450.00),
(1568, 777, 191, 1, 1899.00),
(1569, 778, 186, 2, 1999.00),
(1570, 778, 216, 1, 249.99),
(1571, 778, 182, 2, 2599.00),
(1572, 779, 228, 1, 179.00),
(1573, 780, 221, 2, 349.99),
(1574, 780, 224, 2, 629.99),
(1575, 781, 222, 2, 199.99),
(1576, 781, 228, 2, 179.00),
(1577, 781, 204, 2, 999.00),
(1578, 782, 211, 1, 139.99),
(1579, 782, 179, 2, 3899.00),
(1580, 782, 207, 2, 999.00),
(1581, 783, 8, 1, 799.00),
(1582, 784, 194, 2, 1499.00),
(1583, 784, 124, 2, 139.99),
(1584, 785, 198, 2, 1499.00),
(1585, 786, 190, 1, 2899.00),
(1586, 786, 193, 2, 1599.00),
(1587, 786, 1, 2, 2499.99),
(1588, 787, 189, 2, 3199.00),
(1589, 787, 216, 2, 249.99),
(1590, 787, 1, 2, 2499.99),
(1591, 788, 194, 1, 1499.00),
(1592, 788, 220, 2, 429.00),
(1593, 788, 226, 1, 1799.99),
(1594, 789, 179, 1, 3899.00),
(1595, 789, 222, 1, 199.99),
(1596, 790, 6, 2, 1450.00),
(1597, 790, 196, 2, 2399.00),
(1598, 790, 202, 2, 1899.00),
(1599, 791, 207, 2, 999.00),
(1600, 791, 191, 2, 1899.00),
(1601, 791, 222, 2, 199.99),
(1602, 792, 183, 2, 4399.00),
(1603, 793, 188, 1, 1499.00),
(1604, 793, 128, 2, 5000.00),
(1605, 794, 203, 2, 849.00),
(1606, 794, 187, 1, 2299.00),
(1607, 794, 218, 2, 174.99),
(1608, 795, 180, 2, 3299.99),
(1609, 796, 207, 1, 999.00),
(1610, 797, 181, 1, 4999.00),
(1611, 797, 201, 2, 2699.00),
(1612, 797, 187, 1, 2299.00),
(1613, 798, 200, 2, 3200.00),
(1614, 798, 180, 2, 3299.99),
(1615, 798, 195, 2, 1799.00),
(1616, 799, 203, 1, 849.00),
(1617, 799, 205, 1, 959.00),
(1618, 799, 198, 2, 1499.00),
(1619, 800, 221, 1, 349.99),
(1620, 801, 226, 1, 1799.99),
(1621, 802, 192, 1, 1749.00),
(1622, 802, 214, 2, 199.00),
(1623, 803, 124, 2, 139.99),
(1624, 803, 209, 2, 99.99),
(1625, 804, 208, 2, 999.00),
(1626, 804, 180, 1, 3299.99),
(1627, 804, 223, 1, 169.99),
(1628, 805, 195, 1, 1799.00),
(1629, 805, 188, 2, 1499.00),
(1630, 805, 200, 1, 3200.00),
(1631, 806, 223, 2, 169.99),
(1632, 807, 187, 1, 2299.00),
(1633, 808, 198, 2, 1499.00),
(1634, 808, 211, 2, 139.99),
(1635, 809, 8, 2, 799.00),
(1636, 809, 194, 1, 1499.00),
(1637, 809, 202, 2, 1899.00),
(1638, 810, 201, 1, 2699.00),
(1639, 810, 179, 1, 3899.00),
(1640, 811, 184, 1, 2499.00),
(1641, 812, 202, 1, 1899.00),
(1642, 812, 194, 1, 1499.00),
(1643, 813, 190, 1, 2899.00),
(1644, 813, 6, 2, 1450.00),
(1645, 814, 194, 2, 1499.00),
(1646, 814, 194, 1, 1499.00),
(1647, 815, 225, 1, 999.99),
(1648, 815, 228, 1, 179.00),
(1649, 815, 214, 1, 199.00),
(1650, 816, 179, 2, 3899.00),
(1651, 816, 193, 1, 1599.00),
(1652, 817, 186, 1, 1999.00),
(1653, 817, 195, 1, 1799.00),
(1654, 817, 1, 2, 2499.99),
(1655, 818, 189, 1, 3199.00),
(1656, 819, 204, 1, 999.00),
(1657, 819, 193, 1, 1599.00),
(1658, 820, 182, 1, 2599.00),
(1659, 821, 180, 1, 3299.99),
(1660, 821, 2, 2, 1999.00),
(1661, 821, 212, 2, 79.00),
(1662, 822, 190, 1, 2899.00),
(1663, 822, 227, 1, 499.00),
(1664, 822, 227, 2, 499.00),
(1665, 823, 181, 1, 4999.00),
(1666, 823, 193, 2, 1599.00),
(1667, 824, 1, 2, 2499.99),
(1668, 824, 124, 2, 139.99),
(1669, 825, 8, 2, 799.00),
(1670, 825, 226, 2, 1799.99),
(1671, 825, 128, 1, 5000.00),
(1672, 826, 211, 2, 139.99),
(1673, 826, 193, 1, 1599.00),
(1674, 827, 195, 2, 1799.00),
(1675, 828, 8, 1, 799.00),
(1676, 828, 202, 1, 1899.00),
(1677, 829, 194, 1, 1499.00),
(1678, 829, 213, 2, 49.00),
(1679, 830, 182, 1, 2599.00),
(1680, 830, 204, 2, 999.00),
(1681, 830, 1, 1, 2499.99),
(1682, 831, 196, 2, 2399.00),
(1683, 832, 205, 1, 959.00),
(1684, 832, 2, 2, 1999.00),
(1685, 832, 186, 2, 1999.00),
(1686, 833, 195, 1, 1799.00),
(1687, 833, 117, 2, 300.00),
(1688, 833, 199, 1, 2899.00),
(1689, 834, 187, 1, 2299.00),
(1690, 834, 117, 2, 300.00),
(1691, 835, 194, 1, 1499.00),
(1692, 835, 1, 2, 2499.99),
(1693, 836, 220, 2, 429.00),
(1694, 837, 8, 2, 799.00),
(1695, 838, 195, 1, 1799.00),
(1696, 838, 200, 2, 3200.00),
(1697, 838, 198, 1, 1499.00),
(1698, 839, 186, 2, 1999.00),
(1699, 839, 8, 2, 799.00),
(1700, 840, 198, 2, 1499.00),
(1701, 841, 209, 1, 99.99),
(1702, 841, 180, 2, 3299.99),
(1703, 841, 183, 2, 4399.00),
(1704, 842, 180, 2, 3299.99),
(1705, 842, 181, 1, 4999.00),
(1706, 843, 185, 2, 1699.00),
(1707, 843, 214, 2, 199.00),
(1708, 843, 201, 2, 2699.00),
(1709, 844, 202, 2, 1899.00),
(1710, 845, 219, 2, 399.99),
(1711, 845, 227, 1, 499.00),
(1712, 845, 201, 1, 2699.00),
(1713, 846, 124, 2, 139.99),
(1714, 846, 194, 2, 1499.00),
(1715, 846, 181, 2, 4999.00),
(1716, 847, 218, 1, 174.99),
(1717, 847, 182, 2, 2599.00),
(1718, 847, 203, 1, 849.00),
(1719, 848, 227, 1, 499.00),
(1720, 848, 207, 1, 999.00),
(1721, 849, 206, 2, 749.00),
(1722, 849, 221, 1, 349.99),
(1723, 850, 192, 2, 1749.00),
(1724, 850, 196, 2, 2399.00),
(1725, 851, 179, 2, 3899.00),
(1726, 852, 180, 2, 3299.99),
(1727, 853, 5, 2, 3499.00),
(1728, 853, 210, 1, 149.99),
(1729, 854, 195, 1, 1799.00),
(1730, 855, 185, 2, 1699.00),
(1731, 855, 215, 2, 109.99),
(1732, 856, 206, 1, 749.00),
(1733, 856, 213, 2, 49.00),
(1734, 857, 180, 1, 3299.99),
(1735, 858, 199, 1, 2899.00),
(1736, 858, 186, 1, 1999.00),
(1737, 859, 179, 1, 3899.00),
(1738, 859, 202, 1, 1899.00),
(1739, 859, 222, 2, 199.99),
(1740, 860, 214, 1, 199.00),
(1741, 860, 195, 2, 1799.00),
(1742, 860, 192, 2, 1749.00),
(1743, 861, 205, 1, 959.00),
(1744, 862, 216, 1, 249.99),
(1745, 862, 218, 2, 174.99),
(1746, 863, 2, 2, 1999.00),
(1747, 863, 196, 1, 2399.00),
(1748, 864, 8, 1, 799.00),
(1749, 864, 208, 2, 999.00),
(1750, 864, 8, 1, 799.00),
(1751, 865, 202, 1, 1899.00),
(1752, 866, 196, 1, 2399.00),
(1753, 867, 181, 2, 4999.00),
(1754, 867, 188, 1, 1499.00),
(1755, 868, 199, 2, 2899.00),
(1756, 868, 207, 2, 999.00),
(1757, 868, 191, 1, 1899.00),
(1758, 869, 222, 2, 199.99),
(1759, 869, 225, 2, 999.99),
(1760, 870, 3, 1, 2999.50),
(1761, 870, 210, 1, 149.99),
(1762, 870, 219, 1, 399.99),
(1763, 871, 117, 1, 300.00),
(1764, 872, 128, 1, 5000.00),
(1765, 872, 185, 2, 1699.00),
(1766, 873, 214, 1, 199.00),
(1767, 873, 186, 2, 1999.00),
(1768, 873, 124, 1, 139.99),
(1769, 874, 219, 1, 399.99),
(1770, 874, 201, 1, 2699.00),
(1771, 875, 183, 1, 4399.00),
(1772, 875, 219, 2, 399.99),
(1773, 876, 5, 1, 3499.00),
(1774, 876, 182, 2, 2599.00),
(1775, 877, 194, 2, 1499.00),
(1776, 877, 193, 1, 1599.00),
(1777, 878, 226, 2, 1799.99),
(1778, 878, 200, 1, 3200.00),
(1779, 879, 215, 2, 109.99),
(1780, 880, 208, 1, 999.00),
(1781, 880, 207, 2, 999.00),
(1782, 880, 191, 2, 1899.00),
(1783, 881, 223, 2, 169.99),
(1784, 881, 180, 1, 3299.99),
(1785, 882, 219, 2, 399.99),
(1786, 882, 201, 1, 2699.00),
(1787, 883, 186, 2, 1999.00),
(1788, 883, 227, 1, 499.00),
(1789, 884, 226, 1, 1799.99),
(1790, 884, 196, 2, 2399.00),
(1791, 884, 185, 2, 1699.00),
(1792, 885, 6, 2, 1450.00),
(1793, 886, 217, 2, 119.99),
(1794, 887, 215, 1, 109.99),
(1795, 888, 2, 2, 1999.00),
(1796, 888, 222, 2, 199.99),
(1797, 888, 220, 1, 429.00),
(1798, 889, 225, 2, 999.99),
(1799, 890, 199, 1, 2899.00),
(1800, 891, 196, 1, 2399.00),
(1801, 891, 200, 1, 3200.00),
(1802, 892, 200, 1, 3200.00),
(1803, 893, 227, 2, 499.00),
(1804, 894, 183, 2, 4399.00),
(1805, 894, 187, 1, 2299.00),
(1806, 894, 8, 1, 799.00),
(1807, 895, 199, 2, 2899.00),
(1808, 895, 180, 1, 3299.99),
(1809, 896, 208, 1, 999.00),
(1810, 897, 199, 1, 2899.00),
(1811, 897, 194, 1, 1499.00),
(1812, 897, 207, 2, 999.00),
(1813, 898, 186, 2, 1999.00),
(1814, 899, 188, 1, 1499.00),
(1815, 899, 8, 2, 799.00),
(1816, 900, 227, 2, 499.00),
(1817, 900, 210, 2, 149.99),
(1818, 900, 203, 1, 849.00),
(1819, 901, 189, 2, 3199.00),
(1820, 901, 124, 2, 139.99),
(1821, 902, 191, 2, 1899.00),
(1822, 902, 117, 2, 300.00),
(1823, 903, 192, 1, 1749.00),
(1824, 903, 207, 1, 999.00),
(1825, 904, 117, 1, 300.00),
(1826, 905, 188, 2, 1499.00),
(1827, 905, 214, 1, 199.00),
(1828, 906, 199, 1, 2899.00),
(1829, 906, 199, 2, 2899.00),
(1830, 907, 185, 2, 1699.00),
(1831, 907, 1, 2, 2499.99),
(1832, 907, 7, 1, 1899.99),
(1833, 908, 219, 1, 399.99),
(1834, 909, 213, 2, 49.00),
(1835, 909, 8, 1, 799.00),
(1836, 909, 207, 2, 999.00),
(1837, 910, 180, 1, 3299.99),
(1838, 910, 218, 2, 174.99),
(1839, 911, 117, 1, 300.00),
(1840, 911, 212, 1, 79.00),
(1841, 912, 202, 1, 1899.00),
(1842, 912, 210, 1, 149.99),
(1843, 912, 219, 2, 399.99),
(1844, 913, 187, 1, 2299.00),
(1845, 913, 214, 1, 199.00),
(1846, 914, 180, 2, 3299.99),
(1847, 915, 7, 2, 1899.99),
(1848, 915, 200, 1, 3200.00),
(1849, 915, 201, 1, 2699.00),
(1850, 916, 198, 2, 1499.00),
(1851, 917, 228, 2, 179.00),
(1852, 917, 204, 1, 999.00),
(1853, 918, 205, 2, 959.00),
(1854, 918, 117, 2, 300.00),
(1855, 919, 220, 2, 429.00),
(1856, 919, 208, 2, 999.00),
(1857, 920, 189, 2, 3199.00),
(1858, 920, 211, 2, 139.99),
(1859, 921, 220, 2, 429.00),
(1860, 921, 182, 1, 2599.00),
(1861, 921, 192, 1, 1749.00),
(1862, 922, 228, 2, 179.00),
(1863, 922, 198, 2, 1499.00),
(1864, 922, 203, 1, 849.00),
(1865, 923, 193, 1, 1599.00),
(1866, 923, 215, 2, 109.99),
(1867, 923, 227, 2, 499.00),
(1868, 924, 194, 2, 1499.00),
(1869, 925, 192, 1, 1749.00),
(1870, 926, 4, 2, 649.00),
(1871, 926, 221, 2, 349.99),
(1872, 926, 189, 1, 3199.00),
(1873, 927, 117, 2, 300.00),
(1874, 927, 201, 2, 2699.00),
(1875, 928, 128, 1, 5000.00),
(1876, 928, 220, 2, 429.00),
(1877, 929, 197, 2, 2599.00),
(1878, 929, 219, 1, 399.99),
(1879, 929, 192, 2, 1749.00),
(1880, 930, 196, 2, 2399.00),
(1881, 930, 188, 2, 1499.00),
(1882, 931, 7, 1, 1899.99),
(1883, 932, 5, 2, 3499.00),
(1884, 932, 184, 1, 2499.00),
(1885, 933, 222, 2, 199.99),
(1886, 933, 210, 2, 149.99),
(1887, 934, 6, 1, 1450.00),
(1888, 934, 220, 2, 429.00),
(1889, 934, 221, 2, 349.99),
(1890, 935, 214, 1, 199.00),
(1891, 935, 207, 2, 999.00),
(1892, 936, 218, 2, 174.99),
(1893, 937, 195, 2, 1799.00),
(1894, 938, 198, 1, 1499.00),
(1895, 939, 228, 2, 179.00),
(1896, 939, 124, 1, 139.99),
(1897, 940, 196, 1, 2399.00),
(1898, 941, 218, 1, 174.99),
(1899, 941, 197, 1, 2599.00),
(1900, 941, 181, 1, 4999.00),
(1901, 942, 207, 2, 999.00),
(1902, 942, 197, 2, 2599.00),
(1903, 942, 198, 1, 1499.00),
(1904, 943, 180, 1, 3299.99),
(1905, 943, 8, 1, 799.00),
(1906, 943, 2, 2, 1999.00),
(1907, 944, 2, 2, 1999.00),
(1908, 944, 7, 1, 1899.99),
(1909, 944, 223, 1, 169.99),
(1910, 945, 184, 2, 2499.00),
(1911, 945, 217, 1, 119.99),
(1912, 946, 179, 1, 3899.00),
(1913, 947, 217, 1, 119.99),
(1914, 948, 181, 1, 4999.00),
(1915, 948, 203, 2, 849.00),
(1916, 949, 183, 1, 4399.00),
(1917, 950, 193, 2, 1599.00),
(1918, 950, 192, 1, 1749.00),
(1919, 950, 191, 1, 1899.00),
(1920, 951, 217, 2, 119.99),
(1921, 952, 195, 2, 1799.00),
(1922, 952, 220, 2, 429.00),
(1923, 952, 212, 2, 79.00),
(1924, 953, 117, 2, 300.00),
(1925, 954, 194, 1, 1499.00),
(1926, 954, 220, 2, 429.00),
(1927, 955, 223, 1, 169.99),
(1928, 955, 3, 1, 2999.50),
(1929, 956, 7, 1, 1899.99),
(1930, 956, 7, 1, 1899.99),
(1931, 956, 194, 1, 1499.00),
(1932, 957, 186, 2, 1999.00),
(1933, 958, 217, 2, 119.99),
(1934, 958, 198, 2, 1499.00),
(1935, 959, 223, 1, 169.99),
(1936, 960, 208, 1, 999.00),
(1937, 961, 6, 1, 1450.00),
(1938, 962, 209, 1, 99.99),
(1939, 963, 180, 1, 3299.99),
(1940, 963, 194, 2, 1499.00),
(1941, 964, 186, 2, 1999.00),
(1942, 965, 5, 2, 3499.00),
(1943, 965, 207, 1, 999.00),
(1944, 966, 226, 1, 1799.99),
(1945, 966, 206, 1, 749.00),
(1946, 966, 195, 2, 1799.00),
(1947, 967, 1, 2, 2499.99),
(1948, 967, 194, 2, 1499.00),
(1949, 968, 200, 2, 3200.00),
(1950, 968, 225, 2, 999.99),
(1951, 968, 204, 2, 999.00),
(1952, 969, 226, 1, 1799.99),
(1953, 969, 211, 1, 139.99),
(1954, 970, 218, 1, 174.99),
(1955, 971, 124, 1, 139.99),
(1956, 971, 204, 1, 999.00),
(1957, 972, 193, 1, 1599.00),
(1958, 972, 227, 2, 499.00),
(1959, 973, 182, 2, 2599.00),
(1960, 973, 225, 1, 999.99),
(1961, 973, 210, 2, 149.99),
(1962, 974, 216, 2, 249.99),
(1963, 974, 124, 1, 139.99),
(1964, 975, 224, 1, 629.99),
(1965, 976, 218, 2, 174.99),
(1966, 976, 191, 2, 1899.00),
(1967, 977, 2, 2, 1999.00),
(1968, 978, 191, 1, 1899.00),
(1969, 978, 226, 2, 1799.99),
(1970, 979, 227, 1, 499.00),
(1971, 979, 203, 1, 849.00),
(1972, 979, 8, 2, 799.00),
(1973, 980, 182, 1, 2599.00),
(1974, 980, 191, 1, 1899.00),
(1975, 980, 202, 2, 1899.00),
(1976, 981, 193, 1, 1599.00),
(1977, 981, 117, 1, 300.00),
(1978, 981, 202, 1, 1899.00),
(1979, 982, 180, 1, 3299.99),
(1980, 982, 202, 2, 1899.00),
(1981, 983, 193, 1, 1599.00),
(1982, 983, 185, 1, 1699.00),
(1983, 984, 207, 2, 999.00),
(1984, 984, 226, 2, 1799.99),
(1985, 985, 189, 1, 3199.00),
(1986, 986, 193, 2, 1599.00),
(1987, 986, 221, 1, 349.99),
(1988, 987, 205, 2, 959.00),
(1989, 988, 195, 2, 1799.00),
(1990, 989, 202, 1, 1899.00),
(1991, 989, 181, 1, 4999.00),
(1992, 989, 216, 2, 249.99),
(1993, 990, 193, 2, 1599.00),
(1994, 990, 220, 1, 429.00),
(1995, 991, 226, 2, 1799.99),
(1996, 991, 209, 1, 99.99),
(1997, 991, 7, 1, 1899.99),
(1998, 992, 198, 1, 1499.00),
(1999, 992, 214, 2, 199.00),
(2000, 992, 187, 1, 2299.00),
(2001, 993, 214, 1, 199.00),
(2002, 993, 128, 2, 5000.00),
(2003, 994, 225, 2, 999.99),
(2004, 994, 201, 1, 2699.00),
(2005, 994, 201, 2, 2699.00),
(2006, 995, 221, 1, 349.99),
(2007, 996, 187, 1, 2299.00),
(2008, 997, 225, 1, 999.99),
(2009, 997, 194, 2, 1499.00),
(2010, 998, 117, 2, 300.00),
(2011, 998, 219, 1, 399.99),
(2012, 999, 205, 1, 959.00),
(2013, 999, 199, 2, 2899.00),
(2014, 1000, 199, 1, 2899.00),
(2015, 1000, 4, 1, 649.00),
(2016, 1001, 213, 1, 49.00),
(2017, 1001, 182, 1, 2599.00),
(2018, 1001, 183, 1, 4399.00),
(2019, 1002, 189, 2, 3199.00),
(2020, 1002, 194, 2, 1499.00),
(2021, 1003, 194, 2, 1499.00),
(2022, 1004, 1, 1, 2499.99),
(2023, 1005, 186, 2, 1999.00),
(2024, 1005, 181, 1, 4999.00),
(2025, 1005, 228, 1, 179.00),
(2026, 1006, 186, 2, 1999.00),
(2027, 1006, 211, 2, 139.99),
(2028, 1006, 206, 1, 749.00),
(2029, 1007, 224, 1, 629.99),
(2030, 1007, 224, 1, 629.99),
(2031, 1008, 209, 1, 99.99),
(2032, 1009, 199, 1, 2899.00),
(2033, 1009, 195, 2, 1799.00),
(2034, 1010, 183, 1, 4399.00),
(2035, 1010, 124, 1, 139.99),
(2036, 1010, 207, 2, 999.00),
(2037, 1011, 226, 1, 1799.99),
(2038, 1011, 184, 1, 2499.00),
(2039, 1012, 208, 1, 999.00),
(2040, 1012, 8, 1, 799.00),
(2041, 1012, 201, 2, 2699.00),
(2042, 1013, 205, 2, 959.00),
(2043, 1013, 5, 2, 3499.00),
(2044, 1013, 8, 1, 799.00),
(2045, 1014, 117, 1, 300.00),
(2046, 1014, 190, 1, 2899.00),
(2047, 1014, 213, 1, 49.00),
(2048, 1015, 197, 2, 2599.00),
(2049, 1015, 214, 2, 199.00),
(2050, 1015, 5, 1, 3499.00),
(2051, 1016, 1, 2, 2499.99),
(2052, 1017, 1, 1, 2499.99),
(2053, 1018, 5, 2, 3499.00),
(2054, 1018, 185, 2, 1699.00),
(2055, 1018, 6, 2, 1450.00),
(2056, 1019, 192, 2, 1749.00),
(2057, 1020, 201, 1, 2699.00),
(2058, 1020, 197, 2, 2599.00),
(2059, 1021, 213, 2, 49.00),
(2060, 1021, 7, 1, 1899.99),
(2061, 1021, 4, 2, 649.00),
(2062, 1022, 1, 2, 2499.99),
(2063, 1023, 222, 2, 199.99),
(2064, 1023, 221, 1, 349.99),
(2065, 1023, 182, 1, 2599.00),
(2066, 1024, 193, 1, 1599.00),
(2067, 1024, 218, 2, 174.99),
(2068, 1025, 198, 2, 1499.00),
(2069, 1025, 181, 2, 4999.00),
(2070, 1026, 185, 2, 1699.00),
(2071, 1026, 183, 2, 4399.00),
(2072, 1026, 215, 1, 109.99),
(2073, 1027, 224, 2, 629.99),
(2074, 1027, 213, 2, 49.00),
(2075, 1028, 222, 1, 199.99),
(2076, 1028, 190, 1, 2899.00),
(2077, 1028, 218, 2, 174.99),
(2078, 1029, 224, 2, 629.99),
(2079, 1029, 117, 2, 300.00),
(2080, 1030, 223, 1, 169.99),
(2081, 1030, 206, 2, 749.00),
(2082, 1030, 200, 1, 3200.00),
(2083, 1031, 221, 2, 349.99),
(2084, 1031, 204, 1, 999.00),
(2085, 1032, 3, 1, 2999.50),
(2086, 1032, 6, 2, 1450.00),
(2087, 1032, 189, 1, 3199.00),
(2088, 1033, 206, 1, 749.00),
(2089, 1033, 223, 1, 169.99),
(2090, 1034, 226, 1, 1799.99),
(2091, 1034, 210, 1, 149.99),
(2092, 1035, 221, 2, 349.99),
(2093, 1035, 180, 2, 3299.99),
(2094, 1036, 204, 2, 999.00),
(2095, 1036, 3, 1, 2999.50),
(2096, 1036, 218, 1, 174.99),
(2097, 1037, 117, 1, 300.00),
(2098, 1037, 182, 2, 2599.00),
(2099, 1037, 210, 2, 149.99),
(2100, 1038, 185, 1, 1699.00),
(2101, 1038, 181, 2, 4999.00),
(2102, 1039, 223, 2, 169.99),
(2103, 1039, 218, 1, 174.99),
(2104, 1040, 202, 1, 1899.00),
(2105, 1040, 228, 2, 179.00),
(2106, 1041, 208, 1, 999.00),
(2107, 1042, 186, 2, 1999.00),
(2108, 1042, 226, 1, 1799.99),
(2109, 1042, 182, 2, 2599.00),
(2110, 1043, 192, 1, 1749.00),
(2111, 1044, 6, 1, 1450.00),
(2112, 1045, 213, 1, 49.00),
(2113, 1045, 215, 1, 109.99),
(2114, 1045, 214, 2, 199.00),
(2115, 1046, 220, 2, 429.00),
(2116, 1046, 198, 2, 1499.00),
(2117, 1046, 189, 2, 3199.00),
(2118, 1047, 226, 1, 1799.99),
(2119, 1048, 185, 1, 1699.00),
(2120, 1048, 3, 2, 2999.50),
(2121, 1049, 117, 1, 300.00),
(2122, 1049, 215, 2, 109.99),
(2123, 1049, 228, 2, 179.00),
(2124, 1050, 8, 1, 799.00),
(2125, 1050, 1, 2, 2499.99),
(2126, 1051, 203, 2, 849.00),
(2127, 1051, 226, 2, 1799.99),
(2128, 1052, 216, 2, 249.99),
(2129, 1052, 197, 2, 2599.00),
(2130, 1052, 214, 1, 199.00),
(2131, 1053, 211, 1, 139.99),
(2132, 1054, 124, 2, 139.99),
(2133, 1054, 199, 2, 2899.00),
(2134, 1054, 227, 2, 499.00),
(2135, 1055, 205, 2, 959.00),
(2136, 1055, 216, 2, 249.99),
(2137, 1056, 209, 1, 99.99),
(2138, 1056, 206, 2, 749.00),
(2139, 1057, 215, 1, 109.99),
(2140, 1058, 185, 2, 1699.00),
(2141, 1058, 5, 1, 3499.00),
(2142, 1058, 201, 2, 2699.00),
(2143, 1059, 212, 1, 79.00),
(2144, 1060, 3, 2, 2999.50),
(2145, 1060, 2, 1, 1999.00),
(2146, 1061, 221, 2, 349.99),
(2147, 1062, 128, 2, 5000.00),
(2148, 1062, 193, 1, 1599.00),
(2149, 1062, 214, 2, 199.00),
(2150, 1063, 187, 1, 2299.00),
(2151, 1064, 4, 2, 649.00),
(2152, 1065, 197, 2, 2599.00),
(2153, 1065, 187, 1, 2299.00),
(2154, 1065, 222, 1, 199.99),
(2155, 1066, 124, 2, 139.99),
(2156, 1067, 199, 2, 2899.00),
(2157, 1068, 201, 2, 2699.00),
(2158, 1069, 221, 1, 349.99),
(2159, 1070, 1, 1, 2499.99),
(2160, 1071, 219, 2, 399.99),
(2161, 1071, 194, 1, 1499.00),
(2162, 1072, 198, 2, 1499.00),
(2163, 1073, 195, 1, 1799.00),
(2164, 1074, 213, 2, 49.00),
(2165, 1074, 211, 1, 139.99),
(2166, 1075, 3, 1, 2999.50),
(2167, 1076, 225, 2, 999.99),
(2168, 1076, 181, 2, 4999.00),
(2169, 1077, 205, 2, 959.00),
(2170, 1077, 181, 2, 4999.00),
(2171, 1078, 186, 1, 1999.00),
(2172, 1079, 128, 1, 5000.00),
(2173, 1079, 219, 1, 399.99),
(2174, 1079, 184, 1, 2499.00),
(2175, 1080, 188, 2, 1499.00),
(2176, 1080, 226, 2, 1799.99),
(2177, 1080, 180, 1, 3299.99),
(2178, 1081, 8, 2, 799.00),
(2179, 1082, 198, 2, 1499.00),
(2180, 1082, 204, 2, 999.00),
(2181, 1083, 182, 1, 2599.00),
(2182, 1083, 182, 1, 2599.00),
(2183, 1083, 210, 2, 149.99),
(2184, 1084, 7, 1, 1899.99),
(2185, 1085, 4, 1, 649.00),
(2186, 1085, 124, 1, 139.99),
(2187, 1086, 186, 2, 1999.00),
(2188, 1086, 206, 2, 749.00),
(2189, 1086, 203, 2, 849.00),
(2190, 1087, 200, 2, 3200.00),
(2191, 1088, 201, 2, 2699.00),
(2192, 1088, 207, 2, 999.00),
(2193, 1088, 188, 1, 1499.00),
(2194, 1089, 193, 1, 1599.00),
(2195, 1089, 117, 1, 300.00),
(2196, 1090, 216, 2, 249.99),
(2197, 1091, 2, 2, 1999.00),
(2198, 1092, 128, 1, 5000.00),
(2199, 1092, 208, 2, 999.00),
(2200, 1092, 218, 2, 174.99),
(2201, 1093, 225, 1, 999.99),
(2202, 1093, 212, 2, 79.00),
(2203, 1093, 8, 1, 799.00),
(2204, 1094, 225, 1, 999.99),
(2205, 1094, 195, 2, 1799.00),
(2206, 1095, 184, 2, 2499.00),
(2207, 1096, 219, 2, 399.99),
(2208, 1096, 227, 1, 499.00),
(2209, 1096, 128, 1, 5000.00),
(2210, 1097, 188, 2, 1499.00),
(2211, 1097, 226, 2, 1799.99),
(2212, 1098, 207, 2, 999.00),
(2213, 1099, 199, 1, 2899.00),
(2214, 1100, 210, 2, 149.99),
(2215, 1100, 203, 2, 849.00),
(2216, 1101, 202, 2, 1899.00),
(2217, 1101, 225, 1, 999.99),
(2218, 1102, 209, 2, 99.99),
(2219, 1102, 214, 2, 199.00),
(2220, 1102, 212, 2, 79.00),
(2221, 1103, 215, 2, 109.99),
(2222, 1103, 210, 2, 149.99),
(2223, 1104, 198, 1, 1499.00),
(2224, 1105, 219, 2, 399.99),
(2225, 1106, 220, 2, 429.00),
(2226, 1106, 3, 1, 2999.50),
(2227, 1107, 185, 1, 1699.00),
(2228, 1107, 206, 1, 749.00),
(2229, 1108, 227, 1, 499.00),
(2230, 1109, 215, 1, 109.99),
(2231, 1109, 222, 2, 199.99),
(2232, 1109, 224, 1, 629.99),
(2233, 1110, 219, 1, 399.99),
(2234, 1110, 8, 1, 799.00),
(2235, 1110, 186, 2, 1999.00),
(2236, 1111, 198, 2, 1499.00),
(2237, 1111, 7, 2, 1899.99),
(2238, 1112, 227, 2, 499.00),
(2239, 1113, 124, 2, 139.99),
(2240, 1113, 217, 1, 119.99),
(2241, 1114, 212, 2, 79.00),
(2242, 1114, 124, 2, 139.99);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `reset_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expiry` datetime NOT NULL,
  `is_used` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_reset_tokens`
--

INSERT INTO `password_reset_tokens` (`reset_id`, `user_id`, `token`, `expiry`, `is_used`, `created_at`) VALUES
(1, 6, '08b07f4e469eb103dc6f3f83ba712818094195b35025a3ded93356f0c40e7a13', '2025-12-03 08:06:01', 0, '2025-12-02 07:06:01'),
(2, 6, '330f6b73119fffee5d48ffc7e22f9185ab7fc5f9217ba0cf2d9a32ecbcca8e3c', '2025-12-03 08:06:10', 0, '2025-12-02 07:06:10'),
(3, 6, 'a817cefcec4a8cf3957f6e948a70f26579c6a017ccf16f51a9917b44d46c729d', '2025-12-03 08:06:39', 0, '2025-12-02 07:06:39'),
(4, 6, '9e69318719e97aa2df0f8e8918849e327ccf78c0e2ba3aead60c36d93b66bd95', '2025-12-03 08:08:45', 0, '2025-12-02 07:08:45'),
(5, 6, '53941da5729320da09881b8e6226b330d19a936732458d63197ef96b95fc616f', '2025-12-03 08:08:47', 0, '2025-12-02 07:08:47'),
(6, 6, '5888a36083a5085ff399f210b5df9d9a1cc2934411eca0a7780964d7eb8cb238', '2025-12-03 08:10:52', 1, '2025-12-02 07:10:52');

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `permission_id` int(11) NOT NULL,
  `permission_name` varchar(100) NOT NULL,
  `permission_code` varchar(100) NOT NULL,
  `module` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`permission_id`, `permission_name`, `permission_code`, `module`, `description`, `created_at`) VALUES
(1, 'Dashboard Access', 'dashboard.access', 'Dashboard', 'View main dashboard', '2025-11-21 20:12:32'),
(2, 'Dashboard Analytics', 'dashboard.analytics', 'Dashboard', 'View analytics and reports', '2025-11-21 20:12:32'),
(3, 'View Products', 'product.view', 'Product', 'View product listings', '2025-11-21 20:12:32'),
(4, 'Create Products', 'product.create', 'Product', 'Create new products', '2025-11-21 20:12:32'),
(5, 'Edit Products', 'product.edit', 'Product', 'Edit existing products', '2025-11-21 20:12:32'),
(6, 'Delete Products', 'product.delete', 'Product', 'Delete products', '2025-11-21 20:12:32'),
(7, 'Manage Inventory', 'inventory.manage', 'Product', 'Manage inventory levels', '2025-11-21 20:12:32'),
(8, 'View Orders', 'order.view', 'Order', 'View order listings', '2025-11-21 20:12:32'),
(9, 'Process Orders', 'order.process', 'Order', 'Process and update orders', '2025-11-21 20:12:32'),
(10, 'Cancel Orders', 'order.cancel', 'Order', 'Cancel orders', '2025-11-21 20:12:32'),
(11, 'Manage Transactions', 'transaction.manage', 'Order', 'View and manage transactions', '2025-11-21 20:12:32'),
(12, 'View AI Config', 'ai.view', 'AI', 'View AI configuration', '2025-11-21 20:12:32'),
(13, 'Edit AI Config', 'ai.edit', 'AI', 'Edit AI settings and weightage', '2025-11-21 20:12:32'),
(14, 'Manage Personas', 'persona.manage', 'AI', 'Manage user personas', '2025-11-21 20:12:32'),
(15, 'View AI Logs', 'ai.logs', 'AI', 'View recommendation logs', '2025-11-21 20:12:32'),
(16, 'AI Performance', 'ai.performance', 'AI', 'View AI performance metrics', '2025-11-21 20:12:32'),
(17, 'View Conversations', 'chatbot.conversations', 'Chatbot', 'View chat conversations', '2025-11-21 20:12:32'),
(18, 'Manage Intents', 'chatbot.intents', 'Chatbot', 'Manage chatbot intents', '2025-11-21 20:12:32'),
(19, 'Train Chatbot', 'chatbot.train', 'Chatbot', 'Train chatbot models', '2025-11-21 20:12:32'),
(20, 'Chatbot Analytics', 'chatbot.analytics', 'Chatbot', 'View chatbot analytics', '2025-11-21 20:12:32'),
(21, 'View Customers', 'customer.view', 'User', 'View customer accounts', '2025-11-21 20:12:32'),
(22, 'Edit Customers', 'customer.edit', 'User', 'Edit customer information', '2025-11-21 20:12:32'),
(23, 'Suspend Customers', 'customer.suspend', 'User', 'Suspend customer accounts', '2025-11-21 20:12:32'),
(24, 'View Admins', 'admin.view', 'User', 'View admin accounts', '2025-11-21 20:12:32'),
(25, 'Manage Admins', 'admin.manage', 'User', 'Create and edit admin accounts', '2025-11-21 20:12:32'),
(26, 'Manage Roles', 'role.manage', 'User', 'Manage roles and permissions', '2025-11-21 20:12:32'),
(27, 'View Settings', 'settings.view', 'System', 'View system settings', '2025-11-21 20:12:32'),
(28, 'Edit Settings', 'settings.edit', 'System', 'Edit system settings', '2025-11-21 20:12:32'),
(29, 'System Logs', 'logs.view', 'System', 'View system logs', '2025-11-21 20:12:32'),
(30, 'View Reports', 'reports.view', 'Reports', 'View reports', '2025-11-21 20:12:32'),
(31, 'Generate Reports', 'reports.generate', 'Reports', 'Generate custom reports', '2025-11-21 20:12:32'),
(32, 'Export Data', 'data.export', 'Reports', 'Export data to CSV/PDF', '2025-11-21 20:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `personas`
--

CREATE TABLE `personas` (
  `persona_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `short_description` varchar(255) DEFAULT NULL,
  `detailed_description` text DEFAULT NULL,
  `icon_class` varchar(50) DEFAULT NULL,
  `color_theme` varchar(20) DEFAULT NULL,
  `key_priorities` text DEFAULT NULL COMMENT 'Comma-separated list of priorities',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `personas`
--

INSERT INTO `personas` (`persona_id`, `name`, `short_description`, `detailed_description`, `icon_class`, `color_theme`, `key_priorities`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Student', 'Budget-conscious learners', 'Focuses on affordable laptops with good performance for studying, note-taking, and basic productivity tasks.', 'bi bi-mortarboard', 'primary', 'Battery Life,Portability,Value for Money', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(2, 'Professional', 'Business & productivity', 'Targets working professionals who need reliable, efficient laptops for office work, meetings, and multitasking.', 'bi bi-briefcase', 'success', 'CPU Performance,RAM Capacity,Keyboard Quality', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(3, 'Creative', 'Designers & content creators', 'Aimed at graphic designers, video editors, and content creators who need powerful hardware for creative work.', 'bi bi-palette', 'warning', '', 1, '2025-11-21 19:46:15', '2025-12-07 17:24:58'),
(4, 'Gamer', 'Gaming enthusiasts', 'Designed for gaming enthusiasts who prioritize high-performance graphics and smooth gameplay.', 'bi bi-controller', 'danger', 'GPU Performance,Refresh Rate,Cooling System', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(5, 'Developer', 'Software engineers', 'Caters to software developers who need fast compilation, multiple VMs, and coding efficiency.', 'bi bi-laptop', 'info', 'CPU Performance,RAM Capacity,SSD Speed', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(14, 'Home User', 'Everyday performance for daily tasks', 'Designed for Home Users who need a simple, reliable device for everyday tasks with good performance and value.', 'bi bi-house', 'primary', 'cpu_performance,gpu_performance,battery_life', 1, '2025-12-07 17:27:18', '2025-12-07 17:28:08');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_category` enum('laptop','mouse','keyboard','headset','monitor','bag','mousepad','webcam','other') DEFAULT 'laptop',
  `related_to_category` varchar(100) DEFAULT NULL COMMENT 'For accessories: Gaming, Business, Creative, Student, General Use',
  `brand` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 50,
  `min_stock_level` int(11) DEFAULT 10,
  `cpu` varchar(100) DEFAULT NULL,
  `gpu` varchar(100) DEFAULT NULL,
  `ram_gb` int(11) DEFAULT NULL,
  `storage_gb` int(11) DEFAULT NULL,
  `storage_type` varchar(50) DEFAULT 'SSD',
  `display_size` decimal(4,2) DEFAULT NULL,
  `battery_life` varchar(50) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `primary_use_case` varchar(100) DEFAULT NULL COMMENT 'e.g., Gaming, Business, General',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_category`, `related_to_category`, `brand`, `price`, `stock_quantity`, `min_stock_level`, `cpu`, `gpu`, `ram_gb`, `storage_gb`, `storage_type`, `display_size`, `battery_life`, `image_url`, `description`, `primary_use_case`, `is_active`) VALUES
(1, 'Creator Z16', 'laptop', NULL, 'MSI', 2499.99, 50, 10, 'Intel Core i7', 'NVIDIA RTX 3060', 16, 1024, 'SSD', 16.00, NULL, 'images/laptop1.png', 'A powerful laptop for creative professionals.', 'Creative', 1),
(2, 'ZenBook Duo', 'laptop', NULL, 'Asus', 1999.00, 50, 10, 'Intel Core i9', 'NVIDIA RTX 3070', 32, 1024, 'SSD', 14.00, '', 'images/laptop2.png', 'Dual-screen laptop for ultimate productivity.', 'Professional', 1),
(3, 'Blade 15', 'laptop', NULL, 'Razer', 2999.50, 50, 10, 'Intel Core i7', 'NVIDIA RTX 3080', 16, 1024, 'SSD', 15.60, '', 'images/laptop3.png', 'The ultimate gaming laptop with a sleek design.', 'Gamer', 1),
(4, 'IdeaPad Slim 3', 'laptop', NULL, 'Lenovo', 649.00, 50, 10, 'AMD Ryzen 5', 'Integrated Radeon', 8, 512, 'SSD', 15.60, '', 'images/laptop4.jpg', 'Great for students and everyday tasks.', 'Home User', 1),
(5, 'MacBook Pro 16', 'laptop', NULL, 'Apple', 3499.00, 50, 10, 'Apple M2 Pro', 'Integrated', 16, 1024, 'SSD', 16.20, NULL, 'images/laptop5.png', 'Powerful performance for professionals and creatives.', 'Creative', 1),
(6, 'Spectre x360', 'laptop', NULL, 'HP', 1450.00, 50, 10, 'Intel Core i7', 'Intel Iris Xe', 16, 512, 'SSD', 13.50, NULL, 'images/laptop6.png', 'A versatile 2-in-1 laptop with a premium feel.', 'Professional', 1),
(7, 'ROG Strix G15', 'laptop', NULL, 'Asus', 1899.99, 49, 10, 'AMD Ryzen 9', 'NVIDIA RTX 3070', 16, 1024, 'SSD', 15.60, '', 'images/laptop7.png', 'High-refresh-rate gaming powerhouse.', 'Gamer', 1),
(8, 'Pavilion 15', 'mouse', NULL, 'HP', 799.00, 40, 10, 'Intel Core i5', 'Intel Iris Xe', 12, 512, 'SSD', 15.60, NULL, 'LaptopAdvisor/images/product_8_692eee1113b23.webp', 'A reliable all-rounder for work and entertainment.', 'Home User', 0),
(117, 'Logitech G ', 'mouse', 'Gaming', 'Logitech ', 300.00, 12, 10, '', '', 0, 0, 'SSD', 0.00, NULL, 'LaptopAdvisor/images/product_692d9b1204696.png', '', 'Gamer', 0),
(124, 'Razer BlackWidow V3', 'keyboard', 'Gaming', 'Razer', 139.99, 48, 10, '', '', 0, 0, '', 0.00, NULL, NULL, 'Mechanical gaming keyboard with Razer Green switches and RGB', 'Gamer', 0),
(128, 'Asus ROG Strix G16', 'laptop', NULL, 'Asus', 5000.00, 47, 10, ' i7-13650HX', 'NVIDIA GeForce RTX 4060', 32, 1024, 'SSD', 15.60, '', 'LaptopAdvisor/images/product_692f0409da2d4.webp', 'Power Power Up Your Gaming – Windows 11, 13th Gen Intel Core i7-13650HX Processor And NVIDIA GeForce RTX 4060 Laptop GPU, Up To 140W TGP To Win More Games\r\n\r\n', 'Developer', 1),
(179, 'ASUS ROG Strix Scar 18 (2024)', 'laptop', NULL, 'ASUS', 3899.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4090', 32, 2000, 'SSD', 18.00, '', 'LaptopAdvisor/images/product_69367cd44bfff.jpg', 'Ultimate gaming powerhouse with 18-inch Mini LED display.', 'Gamer', 1),
(180, 'Razer Blade 16 (2024)', 'laptop', NULL, 'Razer', 3299.99, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4080', 32, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_69367cf9e8460.jpg', 'Sleek and powerful with the world\'s first OLED 240Hz display.', 'Gamer', 1),
(181, 'MSI Titan 18 HX', 'laptop', NULL, 'MSI', 4999.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4090', 128, 4000, 'SSD', 18.00, '', 'LaptopAdvisor/images/product_69367d1295865.png', 'Desktop replacement with mechanical keyboard and extreme performance.', 'Gamer', 1),
(182, 'Alienware m18 R2', 'laptop', NULL, 'Dell', 2599.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4070', 16, 1000, 'SSD', 18.00, '', 'LaptopAdvisor/images/product_69367d2679824.jpg', 'Massive screen real estate for immersive gaming.', 'Gamer', 1),
(183, 'Lenovo Legion 9i Gen 9', 'laptop', NULL, 'Lenovo', 4399.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4090', 64, 2000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_69367d5b73c1c.webp', 'Liquid-cooled gaming laptop with AI-tuned performance.', 'Gamer', 1),
(184, 'Acer Predator Helios 18', 'laptop', NULL, 'Acer', 2499.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4080', 32, 2000, 'SSD', 18.00, '', 'LaptopAdvisor/images/product_69367d99a6aa3.png', 'High-performance gaming laptop with vibrant Mini-LED screen.', 'Gamer', 1),
(185, 'HP Omen Transcend 14', 'laptop', NULL, 'HP', 1699.00, 50, 10, 'Intel Core Ultra 9 185H', 'NVIDIA GeForce RTX 4060', 16, 1000, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_69367daea63d6.webp', 'Lightweight portable gaming laptop with OLED display.', 'Gamer', 1),
(186, 'ASUS Zephyrus G14 (2024)', 'laptop', NULL, 'ASUS', 1999.00, 50, 10, 'AMD Ryzen 9 8945HS', 'NVIDIA GeForce RTX 4070', 32, 1000, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_69367f1a0b4ff.png', 'The king of compact gaming laptops redesign with OLED.', 'Gamer', 1),
(187, 'GIGABYTE AORUS 17X', 'laptop', NULL, 'GIGABYTE', 2299.00, 50, 10, 'Intel Core i9-14900HX', 'NVIDIA GeForce RTX 4080', 32, 2000, 'SSD', 17.30, '', 'LaptopAdvisor/images/product_69367f56cc140.webp', 'Powerful craftsmanship for hardcore gamers.', 'Gamer', 1),
(188, 'Dell G16 Gaming Laptop', 'laptop', NULL, 'Dell', 1499.00, 50, 10, 'Intel Core i7-13650HX', 'NVIDIA GeForce RTX 4060', 16, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_69367f6c6ecb3.avif', 'Best value mainstream gaming laptop.', 'Gamer', 1),
(189, 'Apple MacBook Pro 14 (M3 Max)', 'laptop', NULL, 'Apple', 3199.00, 50, 10, 'Apple M3 Max', 'Integrated 30-core GPU', 36, 1000, 'SSD', 14.20, '', 'LaptopAdvisor/images/product_69367ff6d61d9.webp', 'Unmatched performance and battery life for professionals.', 'Professional', 1),
(190, 'Dell XPS 16 (2024)', 'laptop', NULL, 'Dell', 2899.00, 50, 10, 'Intel Core Ultra 7 155H', 'NVIDIA GeForce RTX 4060', 32, 1000, 'SSD', 16.30, '', 'LaptopAdvisor/images/product_693680350a7b9.jpg', 'Futuristic design with invisible trackpad and OLED touch.', 'Professional', 1),
(191, 'Lenovo ThinkPad X1 Carbon Gen 12', 'laptop', NULL, 'Lenovo', 1899.00, 50, 10, 'Intel Core Ultra 7 155U', 'Integrated Intel Graphics', 32, 512, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_6936808435c91.avif', 'The ultimate business ultrabook, now with AI capabilities.', 'Professional', 1),
(192, 'HP Dragonfly G4', 'laptop', NULL, 'HP', 1749.00, 50, 10, 'Intel Core i7-1365U', 'Integrated Intel Iris Xe', 16, 1000, 'SSD', 13.50, '', 'LaptopAdvisor/images/product_6936813544085.jpg', 'Incredibly light business laptop with great webcam features.', 'Professional', 1),
(193, 'Microsoft Surface Laptop 6', 'laptop', NULL, 'Microsoft', 1599.00, 50, 10, 'Intel Core Ultra 5 135H', 'Integrated Intel Graphics', 16, 512, 'SSD', 13.50, '', 'LaptopAdvisor/images/product_6936a1ef67202.jpg', 'Sleek, productive, and AI-powered.', 'Professional', 1),
(194, 'Framework Laptop 13 (AMD)', 'laptop', NULL, 'Framework', 1499.00, 50, 10, 'AMD Ryzen 7 7840U', 'Integrated Radeon 780M', 32, 1000, 'SSD', 13.50, '', 'LaptopAdvisor/images/product_6936a2195b567.jpg', 'Modular, repairable, and upgradeable.', 'Professional', 1),
(195, 'LG Gram Pro 16 2-in-1', 'laptop', NULL, 'LG', 1799.00, 50, 10, 'Intel Core Ultra 7 155H', 'Integrated Intel Graphics', 16, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a22e5fe6b.jpg', 'World\'s lightest 16-inch 2-in-1 laptop.', 'Professional', 1),
(196, 'Samsung Galaxy Book4 Ultra', 'laptop', NULL, 'Samsung', 2399.00, 50, 10, 'Intel Core Ultra 9 185H', 'NVIDIA GeForce RTX 4070', 32, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a243b3c62.jpg', 'Premium AMOLED display and seamless Galaxy ecosystem.', 'Professional', 1),
(197, 'ASUS ProArt Studiobook 16', 'laptop', NULL, 'ASUS', 2599.00, 50, 10, 'Intel Core i9-13980HX', 'NVIDIA RTX 3000 Ada', 64, 2000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a254de3af.jpg', 'Designed for creators with a physical dial for precision control.', 'Creative', 1),
(198, 'MacBook Air 15 (M3)', 'laptop', NULL, 'Apple', 1499.00, 50, 10, 'Apple M3', 'Integrated 10-core GPU', 16, 512, 'SSD', 15.30, '', 'LaptopAdvisor/images/product_6936a26677933.jpg', 'Big screen, thin design, perfect for on-the-go creators.', 'Creative', 1),
(199, 'MSI Creator Z17 HX Studio', 'laptop', NULL, 'MSI', 2899.00, 50, 10, 'Intel Core i9-13950HX', 'NVIDIA GeForce RTX 4070', 32, 2000, 'SSD', 17.00, '', 'LaptopAdvisor/images/product_6936a279c6110.jpg', 'Combines aesthetics with top-tier performance for designers.', 'Creative', 1),
(200, 'Dell Precision 5680', 'laptop', NULL, 'Dell', 3200.00, 50, 10, 'Intel Core i7-13800H', 'NVIDIA RTX 2000 Ada', 32, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a28ca961e.avif', 'Mobile workstation power in a thin chassis.', 'Creative', 1),
(201, 'Surface Laptop Studio 2', 'laptop', NULL, 'Microsoft', 2699.00, 50, 10, 'Intel Core i7-13700H', 'NVIDIA GeForce RTX 4060', 64, 1000, 'SSD', 14.40, '', 'LaptopAdvisor/images/product_6936a2a37687e.avif', 'Pull-forward touchscreen for drawing and designing.', 'Creative', 1),
(202, 'Lenovo Yoga Pro 9i', 'laptop', NULL, 'Lenovo', 1899.00, 50, 10, 'Intel Core Ultra 9 185H', 'NVIDIA GeForce RTX 4060', 32, 1000, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a2c08d31a.avif', 'Mini-LED display perfect for photo editing.', 'Creative', 1),
(203, 'Acer Swift Go 14', 'laptop', NULL, 'Acer', 849.00, 50, 10, 'Intel Core Ultra 5 125H', 'Integrated Intel Graphics', 16, 512, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_6936a2dca052d.jpg', 'OLED display on a budget, perfect for students.', 'Student', 1),
(204, 'ASUS Vivobook S 15 OLED', 'laptop', NULL, 'ASUS', 999.00, 50, 10, 'Intel Core Ultra 7 155H', 'Integrated Intel Graphics', 16, 1000, 'SSD', 15.60, '', 'LaptopAdvisor/images/product_6936a2ee6f1f8.jpg', 'Vibrant screen and solid performance for everyday classwork.', 'Student', 1),
(205, 'HP Envy x360 14', 'laptop', NULL, 'HP', 959.00, 50, 10, 'AMD Ryzen 7 8840HS', 'Integrated Radeon 780M', 16, 1000, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_6936a308a5eda.jpg', 'Versatile 2-in-1 for note-taking and studying.', 'Student', 1),
(206, 'Lenovo IdeaPad Slim 5 Gen 9', 'laptop', NULL, 'Lenovo', 749.00, 50, 10, 'AMD Ryzen 5 8645HS', 'Integrated Radeon Graphics', 16, 512, 'SSD', 16.00, '', 'LaptopAdvisor/images/product_6936a31c3c456.jpg', 'Reliable and affordable big-screen laptop for home use.', 'Home User', 1),
(207, 'Dell Inspiron 14 Plus', 'laptop', NULL, 'Dell', 999.00, 50, 10, 'Intel Core i7-13620H', 'Integrated Intel UHD', 16, 1000, 'SSD', 14.00, '', 'LaptopAdvisor/images/product_6936a32ec223b.jpg', 'Solid build quality and reliable performance.', 'Home User', 1),
(208, 'MacBook Air 13 (M2)', 'laptop', NULL, 'Apple', 999.00, 9, 10, 'Apple M2', 'Integrated 8-core GPU', 8, 256, 'SSD', 13.60, '', 'LaptopAdvisor/images/product_6936a3466d607.jpg', 'The gold standard for student laptops.', 'Student', 1),
(209, 'Logitech MX Master 3S', 'mouse', NULL, 'Logitech', 99.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a359a388b.jpg', 'Ultimate productivity mouse with quiet clicks.', 'Professional', 1),
(210, 'Razer DeathAdder V3 Pro', 'mouse', NULL, 'Razer', 149.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a3721f85f.jpg', 'Ultra-lightweight esports gaming mouse.', 'Gamer', 1),
(211, 'Logitech G502 X Plus', 'mouse', NULL, 'Logitech', 139.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a382f2d0b.jpg', 'Legendary gaming mouse reinvented with hybrid switches.', 'Gamer', 1),
(212, 'Apple Magic Mouse', 'mouse', NULL, 'Apple', 79.00, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a39562bea.jpg', 'Multi-touch surface mouse for Mac users.', 'Creative', 1),
(213, 'Keychron M3 Wireless', 'mouse', NULL, 'Keychron', 49.00, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_69367bc747aa0.webp', 'Best value wireless optical mouse.', 'Home User', 1),
(214, 'Keychron Q1 Pro', 'keyboard', NULL, 'Keychron', 199.00, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a3a8c3d30.jpg', 'Custom mechanical keyboard with aluminum body.', 'Professional', 1),
(215, 'Logitech MX Keys S', 'keyboard', NULL, 'Logitech', 109.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a3bbe1fcd.jpg', 'Low-profile wireless keyboard for coding and writing.', 'Professional', 1),
(216, 'Razer Huntsman V3 Pro', 'keyboard', NULL, 'Razer', 249.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a3cea8b34.jpg', 'Analog optical switches for rapid triggers.', 'Gamer', 1),
(217, 'NuPhy Air75 V2', 'keyboard', NULL, 'NuPhy', 119.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a3f5ae8ef.jpg', 'Slim mechanical keyboard perfect for travel.', 'Creative', 1),
(218, 'Wooting 60HE', 'keyboard', NULL, 'Wooting', 174.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_693678a455b9c.webp', 'The fastest keyboard for competitive gaming.', 'Gamer', 1),
(219, 'Sony WH-1000XM5', 'headset', NULL, 'Sony', 399.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_69367ccddc5c3.webp', 'Industry-leading noise canceling headphones.', 'Home User', 1),
(220, 'Bose QuietComfort Ultra', 'headset', NULL, 'Bose', 429.00, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a40ab3501.webp', 'World-class comfort and silence.', 'Professional', 1),
(221, 'SteelSeries Arctis Nova Pro Wireless', 'headset', NULL, 'SteelSeries', 349.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a418c2c44.avif', 'Premium gaming audio with dual-battery system.', 'Gamer', 1),
(222, 'Razer BlackShark V2 Pro (2023)', 'headset', NULL, 'Razer', 199.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a4284f4a7.jpg', 'Esports headset with crystal-clear microphone.', 'Gamer', 1),
(223, 'HyperX Cloud III Wireless', 'headset', NULL, 'HyperX', 169.99, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a43aa439a.avif', 'Comfortable and durable headset with massive battery.', 'Home User', 1),
(224, 'Dell UltraSharp U2723QE', 'monitor', NULL, 'Dell', 629.99, 50, 10, '', '', 0, 0, 'SSD', 27.00, '', 'LaptopAdvisor/images/product_6936a44abb5fa.jpg', '4K IPS Black monitor with incredible contrast.', 'Professional', 1),
(225, 'LG C3 42-inch OLED', 'monitor', NULL, 'LG', 999.99, 50, 10, '', '', 0, 0, 'SSD', 42.00, '', 'LaptopAdvisor/images/product_6936a45bcc508.jpg', 'OLED TV widely used as a high-end gaming monitor.', 'Gamer', 1),
(226, 'Samsung Odyssey OLED G9', 'monitor', NULL, 'Samsung', 1799.99, 50, 10, '', '', 0, 0, 'SSD', 49.00, '', 'LaptopAdvisor/images/product_6936a46b6e0c7.avif', 'Massive ultrawide OLED for immersive simulation.', 'Gamer', 1),
(227, 'ASUS ProArt Display PA279CRV', 'monitor', NULL, 'ASUS', 499.00, 7, 10, '', '', 0, 0, 'SSD', 27.00, '', 'LaptopAdvisor/images/product_6936a3e187eb4.jpg', 'Color-accurate 4K monitor for designers.', 'Creative', 1),
(228, 'BenQ ScreenBar Halo', 'other', NULL, 'BenQ', 179.00, 50, 10, '', '', 0, 0, 'SSD', 0.00, '', 'LaptopAdvisor/images/product_6936a47cc8c4b.png', 'Advanced monitor light bar for eye comfort.', 'Professional', 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_media`
--

CREATE TABLE `product_media` (
  `media_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `media_url` varchar(500) NOT NULL,
  `media_type` enum('image','video') DEFAULT 'image',
  `display_order` int(11) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_media`
--

INSERT INTO `product_media` (`media_id`, `product_id`, `media_url`, `media_type`, `display_order`, `created_at`) VALUES
(13, 117, 'LaptopAdvisor/images/product_117_692d9b12051c9.webp', 'image', 1, '2025-12-01 13:41:38'),
(14, 117, 'LaptopAdvisor/images/product_117_692d9b1205a12.webp', 'image', 2, '2025-12-01 13:41:38'),
(15, 117, 'LaptopAdvisor/images/product_117_692d9b1206834.webp', 'image', 3, '2025-12-01 13:41:38'),
(16, 117, 'LaptopAdvisor/images/product_117_692d9b1207046.webp', 'image', 4, '2025-12-01 13:41:38'),
(17, 117, 'LaptopAdvisor/images/product_117_692d9b1207ba7.webp', 'image', 5, '2025-12-01 13:41:38'),
(18, 117, 'http://youtu.be/EcGoLXoYMqI?si=ejbqoRmpLp4KWm0n', 'video', 6, '2025-12-01 13:41:38'),
(26, 8, 'LaptopAdvisor/images/product_8_692eee1113b23.webp', 'image', 1, '2025-12-02 13:48:01'),
(27, 8, 'LaptopAdvisor/images/product_8_692eee1114424.webp', 'image', 2, '2025-12-02 13:48:01'),
(28, 128, 'LaptopAdvisor/images/product_128_692f0409dae41.webp', 'image', 1, '2025-12-02 15:21:45'),
(29, 128, 'LaptopAdvisor/images/product_128_692f0409db6b7.webp', 'image', 2, '2025-12-02 15:21:45'),
(30, 128, 'LaptopAdvisor/images/product_128_692f0409dbdfa.webp', 'image', 3, '2025-12-02 15:21:45'),
(31, 128, 'LaptopAdvisor/images/product_128_692f0409dc4f1.webp', 'image', 4, '2025-12-02 15:21:45');

-- --------------------------------------------------------

--
-- Table structure for table `product_reviews`
--

CREATE TABLE `product_reviews` (
  `review_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` tinyint(1) NOT NULL,
  `review_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `admin_response` text DEFAULT NULL,
  `admin_response_date` datetime DEFAULT NULL,
  `admin_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`, `admin_response`, `admin_response_date`, `admin_id`) VALUES
(4, 4, 6, 5, 'good products', '2025-12-08 03:30:23', 'TEST RESPONSE 1765166618', NULL, NULL),
(8, 3, 6, 4, 'donked', '2025-12-08 04:12:12', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `recommendation_logs`
--

CREATE TABLE `recommendation_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(100) DEFAULT NULL,
  `persona_id` int(11) DEFAULT NULL,
  `request_query` text DEFAULT NULL,
  `recommended_products` text DEFAULT NULL COMMENT 'JSON array of product IDs',
  `confidence_score` decimal(5,2) DEFAULT NULL,
  `accuracy_score` decimal(5,2) DEFAULT NULL,
  `response_time_ms` int(11) DEFAULT NULL,
  `status` enum('success','failed','partial') DEFAULT 'success',
  `error_message` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `recommendation_ratings`
--

CREATE TABLE `recommendation_ratings` (
  `rating_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL COMMENT '1 for like, -1 for dislike',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `recommendation_ratings`
--

INSERT INTO `recommendation_ratings` (`rating_id`, `user_id`, `product_id`, `rating`, `created_at`) VALUES
(1, 1, 4, -1, '2025-09-27 19:56:58'),
(4, 1, 2, 1, '2025-09-27 19:57:13'),
(5, 1, 6, 1, '2025-09-27 20:02:40'),
(6, 2, 4, 1, '2025-09-28 06:10:39'),
(9, 4, 4, 1, '2025-12-02 15:06:53'),
(10, 4, 122, -1, '2025-12-02 15:33:32'),
(12, 4, 6, 1, '2025-12-02 15:36:21'),
(13, 4, 118, 1, '2025-12-02 15:36:29'),
(14, 6, 128, 1, '2025-12-07 17:49:06'),
(16, 6, 3, 1, '2025-12-07 18:06:39');

-- --------------------------------------------------------

--
-- Table structure for table `review_media`
--

CREATE TABLE `review_media` (
  `media_id` int(11) NOT NULL,
  `review_id` int(11) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `media_type` enum('image','video') NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `review_media`
--

INSERT INTO `review_media` (`media_id`, `review_id`, `file_path`, `media_type`, `uploaded_at`) VALUES
(3, 8, 'uploads/reviews/3/8_6936501c48b24.jpg', 'image', '2025-12-08 04:12:12');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(100) NOT NULL,
  `role_code` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_system_role` tinyint(1) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`, `role_code`, `description`, `is_system_role`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Super Administrator', 'super_admin', 'Full system access and control', 1, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(2, 'Product Manager', 'product_manager', 'Manage products, inventory, and pricing', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(3, 'Order Manager', 'order_manager', 'Process orders and manage transactions', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(4, 'AI Administrator', 'ai_admin', 'Manage AI models and recommendations', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(5, 'Chatbot Manager', 'chatbot_manager', 'Manage chatbot training and responses', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(6, 'Customer Manager', 'customer_manager', 'Handle customer accounts and support', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(7, 'Reports Analyst', 'reports_analyst', 'Generate and analyze system reports', 0, 'active', '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(8, 'Viewer', 'viewer', 'Read-only access to system data', 0, 'inactive', '2025-11-21 20:12:32', '2025-11-21 20:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `role_permission_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `access_level` enum('none','read','write','full') DEFAULT 'read',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`role_permission_id`, `role_id`, `permission_id`, `access_level`, `created_at`) VALUES
(1, 1, 12, 'full', '2025-11-21 20:12:32'),
(2, 1, 13, 'full', '2025-11-21 20:12:32'),
(3, 1, 14, 'full', '2025-11-21 20:12:32'),
(4, 1, 15, 'full', '2025-11-21 20:12:32'),
(5, 1, 16, 'full', '2025-11-21 20:12:32'),
(6, 1, 17, 'full', '2025-11-21 20:12:32'),
(7, 1, 18, 'full', '2025-11-21 20:12:32'),
(8, 1, 19, 'full', '2025-11-21 20:12:32'),
(9, 1, 20, 'full', '2025-11-21 20:12:32'),
(10, 1, 1, 'full', '2025-11-21 20:12:32'),
(11, 1, 2, 'full', '2025-11-21 20:12:32'),
(12, 1, 8, 'full', '2025-11-21 20:12:32'),
(13, 1, 9, 'full', '2025-11-21 20:12:32'),
(14, 1, 10, 'full', '2025-11-21 20:12:32'),
(15, 1, 11, 'full', '2025-11-21 20:12:32'),
(16, 1, 3, 'full', '2025-11-21 20:12:32'),
(17, 1, 4, 'full', '2025-11-21 20:12:32'),
(18, 1, 5, 'full', '2025-11-21 20:12:32'),
(19, 1, 6, 'full', '2025-11-21 20:12:32'),
(20, 1, 7, 'full', '2025-11-21 20:12:32'),
(21, 1, 30, 'full', '2025-11-21 20:12:32'),
(22, 1, 31, 'full', '2025-11-21 20:12:32'),
(23, 1, 32, 'full', '2025-11-21 20:12:32'),
(24, 1, 27, 'full', '2025-11-21 20:12:32'),
(25, 1, 28, 'full', '2025-11-21 20:12:32'),
(26, 1, 29, 'full', '2025-11-21 20:12:32'),
(27, 1, 21, 'full', '2025-11-21 20:12:32'),
(28, 1, 22, 'full', '2025-11-21 20:12:32'),
(29, 1, 23, 'full', '2025-11-21 20:12:32'),
(30, 1, 24, 'full', '2025-11-21 20:12:32'),
(31, 1, 25, 'full', '2025-11-21 20:12:32'),
(32, 1, 26, 'full', '2025-11-21 20:12:32'),
(64, 2, 1, 'full', '2025-11-21 20:12:32'),
(65, 2, 2, 'full', '2025-11-21 20:12:32'),
(66, 2, 32, 'full', '2025-11-21 20:12:32'),
(67, 2, 7, 'full', '2025-11-21 20:12:32'),
(68, 2, 8, 'full', '2025-11-21 20:12:32'),
(69, 2, 4, 'full', '2025-11-21 20:12:32'),
(70, 2, 6, 'full', '2025-11-21 20:12:32'),
(71, 2, 5, 'full', '2025-11-21 20:12:32'),
(72, 2, 3, 'full', '2025-11-21 20:12:32'),
(73, 2, 30, 'full', '2025-11-21 20:12:32'),
(79, 3, 21, 'full', '2025-11-21 20:12:32'),
(80, 3, 1, 'full', '2025-11-21 20:12:32'),
(81, 3, 2, 'full', '2025-11-21 20:12:32'),
(82, 3, 10, 'full', '2025-11-21 20:12:32'),
(83, 3, 9, 'full', '2025-11-21 20:12:32'),
(84, 3, 8, 'full', '2025-11-21 20:12:32'),
(85, 3, 30, 'full', '2025-11-21 20:12:32'),
(86, 3, 11, 'full', '2025-11-21 20:12:32'),
(94, 4, 13, 'full', '2025-11-21 20:12:32'),
(95, 4, 15, 'full', '2025-11-21 20:12:32'),
(96, 4, 16, 'full', '2025-11-21 20:12:32'),
(97, 4, 12, 'full', '2025-11-21 20:12:32'),
(98, 4, 1, 'full', '2025-11-21 20:12:32'),
(99, 4, 2, 'full', '2025-11-21 20:12:32'),
(100, 4, 32, 'full', '2025-11-21 20:12:32'),
(101, 4, 14, 'full', '2025-11-21 20:12:32'),
(102, 4, 30, 'full', '2025-11-21 20:12:32'),
(109, 5, 20, 'full', '2025-11-21 20:12:32'),
(110, 5, 17, 'full', '2025-11-21 20:12:32'),
(111, 5, 18, 'full', '2025-11-21 20:12:32'),
(112, 5, 19, 'full', '2025-11-21 20:12:32'),
(113, 5, 1, 'full', '2025-11-21 20:12:32'),
(114, 5, 2, 'full', '2025-11-21 20:12:32'),
(115, 5, 30, 'full', '2025-11-21 20:12:32'),
(116, 6, 22, 'full', '2025-11-21 20:12:32'),
(117, 6, 23, 'full', '2025-11-21 20:12:32'),
(118, 6, 21, 'full', '2025-11-21 20:12:32'),
(119, 6, 1, 'full', '2025-11-21 20:12:32'),
(120, 6, 2, 'full', '2025-11-21 20:12:32'),
(121, 6, 8, 'full', '2025-11-21 20:12:32'),
(122, 6, 30, 'full', '2025-11-21 20:12:32'),
(123, 7, 15, 'read', '2025-11-21 20:12:32'),
(124, 7, 20, 'read', '2025-11-21 20:12:32'),
(125, 7, 21, 'read', '2025-11-21 20:12:32'),
(126, 7, 1, 'read', '2025-11-21 20:12:32'),
(127, 7, 2, 'read', '2025-11-21 20:12:32'),
(128, 7, 32, 'read', '2025-11-21 20:12:32'),
(129, 7, 8, 'read', '2025-11-21 20:12:32'),
(130, 7, 3, 'read', '2025-11-21 20:12:32'),
(131, 7, 31, 'read', '2025-11-21 20:12:32'),
(132, 7, 30, 'read', '2025-11-21 20:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','integer','boolean','json','text') DEFAULT 'string',
  `category` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `is_editable` tinyint(1) DEFAULT 1,
  `validation_rules` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`validation_rules`)),
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`setting_id`, `setting_key`, `setting_value`, `setting_type`, `category`, `description`, `is_public`, `is_editable`, `validation_rules`, `updated_by`, `updated_at`, `created_at`) VALUES
(1, 'site_name', 'Smart Laptop Advisor', 'string', 'general', 'Website name', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(2, 'site_description', 'Your intelligent companion for finding the perfect laptop tailored to your needs.', 'text', 'general', 'Website description', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(3, 'admin_email', 'admin@smartlaptopadvisor.com', 'string', 'general', 'Primary admin email', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(4, 'timezone', 'America/New_York', 'string', 'general', 'System timezone', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(5, 'currency', 'USD', 'string', 'general', 'Default currency', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(6, 'language', 'en', 'string', 'general', 'Default language', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(7, 'ai_provider', 'openai', 'string', 'ai', 'AI service provider', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(8, 'ai_api_key', 'sk-************', 'string', 'ai', 'AI API key (encrypted)', 0, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(9, 'ai_max_recommendations', '5', 'integer', 'ai', 'Maximum recommendations per query', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(10, 'ai_confidence_threshold', '85', 'integer', 'ai', 'Minimum confidence threshold (%)', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(11, 'ai_learning_mode', 'continuous', 'string', 'ai', 'AI learning mode', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(12, 'ai_enable_feedback', '1', 'boolean', 'ai', 'Enable user feedback collection', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(13, 'smtp_host', 'smtp.gmail.com', 'string', 'email', 'SMTP server host', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(14, 'smtp_port', '587', 'integer', 'email', 'SMTP server port', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(15, 'smtp_username', 'noreply@smartlaptopadvisor.com', 'string', 'email', 'SMTP username', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(16, 'smtp_password', '', 'string', 'email', 'SMTP password (encrypted)', 0, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(17, 'email_new_orders', '1', 'boolean', 'email', 'Send new order notifications', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(18, 'email_low_stock', '1', 'boolean', 'email', 'Send low stock alerts', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(19, 'email_system_errors', '0', 'boolean', 'email', 'Send system error notifications', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(20, 'email_weekly_reports', '1', 'boolean', 'email', 'Send weekly performance reports', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(21, 'session_timeout', '30', 'integer', 'security', 'Session timeout in minutes', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(22, 'max_login_attempts', '5', 'integer', 'security', 'Maximum login attempts before lockout', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(23, 'lockout_duration', '15', 'integer', 'security', 'Account lockout duration in minutes', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(24, 'require_2fa', '0', 'boolean', 'security', 'Require two-factor authentication', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(25, 'require_strong_passwords', '1', 'boolean', 'security', 'Enforce strong password policy', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(26, 'log_security_events', '1', 'boolean', 'security', 'Log all security events', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(27, 'enable_rate_limit', '1', 'boolean', 'security', 'Enable API rate limiting', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(28, 'cache_timeout', '24', 'integer', 'performance', 'Cache timeout in hours', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(29, 'max_requests_per_minute', '100', 'integer', 'performance', 'Maximum requests per minute', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(30, 'database_timeout', '30', 'integer', 'performance', 'Database query timeout in seconds', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(31, 'enable_caching', '1', 'boolean', 'performance', 'Enable response caching', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(32, 'enable_compression', '1', 'boolean', 'performance', 'Enable GZIP compression', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(33, 'enable_cdn', '0', 'boolean', 'performance', 'Use CDN for static assets', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(34, 'enable_minification', '1', 'boolean', 'performance', 'Minify CSS/JS resources', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(35, 'maintenance_mode', '0', 'boolean', 'maintenance', 'Enable maintenance mode', 0, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(36, 'maintenance_message', 'We\'re currently performing scheduled maintenance to improve your experience. Please check back soon!', 'text', 'maintenance', 'Maintenance mode message', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32'),
(37, 'estimated_downtime', '', 'string', 'maintenance', 'Estimated downtime', 1, 1, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `system_settings_history`
--

CREATE TABLE `system_settings_history` (
  `history_id` int(11) NOT NULL,
  `setting_id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` int(11) NOT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `temp_vouchers`
--

CREATE TABLE `temp_vouchers` (
  `voucher_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `voucher_code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `discount_value` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `used` tinyint(1) DEFAULT 0,
  `used_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `training_phrases`
--

CREATE TABLE `training_phrases` (
  `phrase_id` int(11) NOT NULL,
  `intent_id` int(11) NOT NULL,
  `phrase_text` varchar(500) NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `added_by` varchar(100) DEFAULT NULL COMMENT 'admin username or auto-generated',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `training_phrases`
--

INSERT INTO `training_phrases` (`phrase_id`, `intent_id`, `phrase_text`, `is_active`, `added_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Hello', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(2, 1, 'Hi', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(3, 1, 'Hi there', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(4, 1, 'Good morning', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(5, 1, 'Good afternoon', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(6, 1, 'Good evening', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(7, 1, 'Hey', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(8, 1, 'Greetings', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(9, 2, 'I need a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(10, 2, 'Looking for a computer', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(11, 2, 'Find me a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(12, 2, 'I want to buy a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(13, 2, 'Show me laptops', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(14, 2, 'Need laptop recommendations', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(15, 2, 'Can you help me find a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(16, 2, 'I\'m looking for a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(17, 2, 'What laptops do you have', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(18, 2, 'Show me your best laptops', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(19, 2, 'I need a new computer', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(20, 2, 'Looking to purchase a laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(21, 3, 'Gaming laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(22, 3, 'Laptop for gaming', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(23, 3, 'Best gaming computer', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(24, 3, 'I need a gaming laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(25, 3, 'High performance laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(26, 3, 'Laptop for games', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(27, 3, 'Show me gaming laptops', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(28, 3, 'I want a laptop for playing games', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(29, 3, 'What\'s the best gaming laptop', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(30, 3, 'Need a laptop to run AAA games', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(31, 4, 'My budget is $1000', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(32, 4, 'Under $800', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(33, 4, 'Around 1500 dollars', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(34, 4, 'Budget is 2000', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(35, 4, 'Less than $500', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(36, 4, 'Between 1000-1500', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(37, 4, 'I can spend up to $1200', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(38, 4, 'Looking for something under 900', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(39, 4, 'My price range is 700-1000', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(40, 5, 'Thank you', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(41, 5, 'Thanks for your help', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(42, 5, 'Goodbye', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(43, 5, 'That\'s all', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(44, 5, 'Thanks, bye', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(45, 5, 'Appreciate it', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(46, 5, 'See you later', 1, 'system', '2025-11-21 19:57:10', '2025-11-21 19:57:10'),
(62, 8, 'What is the warranty?', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(63, 8, 'How long is the warranty period?', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(64, 8, 'Do you provide warranty?', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(65, 8, 'How can I claim warranty?', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(66, 8, 'Tell me about the warranty', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(67, 8, 'Warranty details please', 1, NULL, '2025-11-24 19:55:31', '2025-11-24 19:55:31'),
(68, 7, 'Hello', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(69, 7, 'Hi', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(70, 7, 'Hi there', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(71, 7, 'Good morning', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(72, 7, 'Good afternoon', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(73, 7, 'Good evening', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(74, 7, 'Hey', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48'),
(75, 7, 'Greetings', 1, NULL, '2025-11-24 19:57:48', '2025-11-24 19:57:48');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'pending',
  `profile_image_url` varchar(255) DEFAULT 'uploads/default.png',
  `primary_use_case` varchar(100) DEFAULT 'General Use',
  `default_shipping_name` varchar(255) DEFAULT NULL,
  `default_shipping_address` varchar(500) DEFAULT NULL,
  `default_shipping_city` varchar(100) DEFAULT NULL,
  `default_shipping_state` varchar(100) DEFAULT NULL,
  `default_shipping_zip` varchar(20) DEFAULT NULL,
  `default_shipping_country` varchar(100) DEFAULT NULL,
  `default_shipping_phone` varchar(20) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `status`, `profile_image_url`, `primary_use_case`, `default_shipping_name`, `default_shipping_address`, `default_shipping_city`, `default_shipping_state`, `default_shipping_zip`, `default_shipping_country`, `default_shipping_phone`, `created_at`) VALUES
(1, 'Heng', 'chansh-am22@student.tarc.edu.my', '$2y$10$3Sm1lxjx6YodMxqCXW0riOY6Plen2z.pV.GXvYL.KH3a.3dmFPRMy', 'active', 'uploads/68d83a959bb49-Screenshot 2024-05-03 151943.png', 'Professional', NULL, NULL, NULL, NULL, NULL, NULL, '', '2025-09-27 17:21:42'),
(2, 'xuan', 'chan123@GMAIL.COM', '$2y$10$UqWAFCaxniqS5NKJDYgndOWtGvfVlv3rwWfdJVo.f0mbUMkYPmY16', 'active', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, '', '2025-09-28 06:08:01'),
(3, 'xuan', 'abc@gmail.com', '$2y$10$WbU7EDXaKg0EcfW.B.spWOqvSqrLro1z9NCpw9DW.Z8xMhe04PRMm', 'active', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, '', '2025-11-11 14:17:17'),
(4, 'GYZ', 'GYZ@gmail.com', '$2y$10$b2eGc843Cu2g7Q0AyP4w1ulwZTmgomHrPv4QpFC9XtHSOAhlCOrqa', 'active', 'uploads/6920af2dc9395-donk.jpg', 'Professional', 'GWK', 'Tingkat Lahat Mines 4', 'Lahat', 'Perak', '31500', 'Malaysia', '+60165076821', '2025-11-21 18:27:28'),
(6, 'cck', 'chock-am22@student.tarc.edu.my', '$2y$10$VzgL/FZDSCQeqhGiGgtxae3fX9.z/4QLAIlDDDmk.GhqSNczOI0tm', 'active', 'uploads/693645450d731-donk.jpg', 'Student', '', '', '', '', '', '', '', '2025-12-02 07:00:41'),
(7, 'Frank Miller', 'frank.m@example.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Professional', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-08 03:23:17'),
(8, 'Sarah Connor', 'sarah.c@sky.net', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Gamer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-08-08 03:23:17'),
(9, 'John Doe', 'john.doe@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-08 03:23:17'),
(10, 'Jane Smith', 'jane.smith@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Creative', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-02-08 03:23:17'),
(11, 'Alice Johnson', 'alice.j@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Home User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2024-12-08 03:23:17'),
(12, 'Bob Brown', 'bob.b@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Professional', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-08 03:23:17'),
(13, 'Charlie Davis', 'charlie.d@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Gamer', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-02-08 03:23:17'),
(14, 'Diana Evans', 'diana.e@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-08 03:23:17'),
(15, 'Ethan Hunt', 'ethan.h@imf.org', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Creative', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-05-08 03:23:17'),
(16, 'Fiona Gallagher', 'fiona.g@test.com', '$2y$10$BI99vV3fWo/Kz9lhjcqF5.MMzHfkeUeFARGt.ZpO41mRwSdkiqnqK', 'active', 'uploads/default.png', 'Home User', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-06-08 03:23:18');

-- --------------------------------------------------------

--
-- Table structure for table `weightage_history`
--

CREATE TABLE `weightage_history` (
  `history_id` int(11) NOT NULL,
  `weightage_id` int(11) NOT NULL,
  `persona_id` int(11) NOT NULL,
  `changes_json` text DEFAULT NULL COMMENT 'JSON of before/after changes',
  `changed_by` int(11) DEFAULT NULL,
  `change_reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_module` (`module`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD PRIMARY KEY (`session_id`),
  ADD KEY `idx_admin` (`admin_id`),
  ADD KEY `idx_expires` (`expires_at`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `admin_code` (`admin_code`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `idx_admin_code` (`admin_code`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_role` (`role_id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `ai_performance_metrics`
--
ALTER TABLE `ai_performance_metrics`
  ADD PRIMARY KEY (`metric_id`),
  ADD KEY `idx_model` (`model_version`),
  ADD KEY `idx_persona` (`persona_id`),
  ADD KEY `idx_recorded` (`recorded_at`);

--
-- Indexes for table `ai_weightage`
--
ALTER TABLE `ai_weightage`
  ADD PRIMARY KEY (`weightage_id`),
  ADD KEY `idx_persona` (`persona_id`);

--
-- Indexes for table `chatbot_analytics`
--
ALTER TABLE `chatbot_analytics`
  ADD PRIMARY KEY (`analytics_id`),
  ADD UNIQUE KEY `unique_date` (`date`),
  ADD KEY `idx_date` (`date`);

--
-- Indexes for table `chatbot_training_data`
--
ALTER TABLE `chatbot_training_data`
  ADD PRIMARY KEY (`data_id`),
  ADD KEY `idx_source_type` (`source_type`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `chat_history`
--
ALTER TABLE `chat_history`
  ADD PRIMARY KEY (`chat_id`);

--
-- Indexes for table `conversations`
--
ALTER TABLE `conversations`
  ADD PRIMARY KEY (`conversation_id`),
  ADD UNIQUE KEY `session_id` (`session_id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_user` (`user_id`),
  ADD KEY `idx_started` (`started_at`),
  ADD KEY `idx_sentiment` (`sentiment`),
  ADD KEY `idx_outcome` (`outcome`);

--
-- Indexes for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `idx_conversation` (`conversation_id`),
  ADD KEY `idx_type` (`message_type`),
  ADD KEY `idx_timestamp` (`timestamp`),
  ADD KEY `idx_intent` (`intent_detected`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`coupon_id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `intents`
--
ALTER TABLE `intents`
  ADD PRIMARY KEY (`intent_id`),
  ADD UNIQUE KEY `intent_name` (`intent_name`),
  ADD KEY `idx_name` (`intent_name`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_category` (`category`);

--
-- Indexes for table `intent_responses`
--
ALTER TABLE `intent_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `idx_intent` (`intent_id`),
  ADD KEY `idx_active` (`is_active`),
  ADD KEY `idx_default` (`is_default`);

--
-- Indexes for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`order_item_id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`reset_id`),
  ADD UNIQUE KEY `token` (`token`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `idx_token_expiry` (`token`,`expiry`,`is_used`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`permission_id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`),
  ADD UNIQUE KEY `permission_code` (`permission_code`),
  ADD KEY `idx_permission_code` (`permission_code`),
  ADD KEY `idx_module` (`module`);

--
-- Indexes for table `personas`
--
ALTER TABLE `personas`
  ADD PRIMARY KEY (`persona_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `product_media`
--
ALTER TABLE `product_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD UNIQUE KEY `unique_user_product_review` (`product_id`,`user_id`),
  ADD KEY `idx_product_id` (`product_id`),
  ADD KEY `idx_user_id` (`user_id`);

--
-- Indexes for table `recommendation_logs`
--
ALTER TABLE `recommendation_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `idx_session` (`session_id`),
  ADD KEY `idx_persona` (`persona_id`),
  ADD KEY `idx_created` (`created_at`);

--
-- Indexes for table `recommendation_ratings`
--
ALTER TABLE `recommendation_ratings`
  ADD PRIMARY KEY (`rating_id`),
  ADD UNIQUE KEY `user_product_rating` (`user_id`,`product_id`);

--
-- Indexes for table `review_media`
--
ALTER TABLE `review_media`
  ADD PRIMARY KEY (`media_id`),
  ADD KEY `idx_review_id` (`review_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`),
  ADD UNIQUE KEY `role_code` (`role_code`),
  ADD KEY `idx_role_code` (`role_code`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`role_permission_id`),
  ADD UNIQUE KEY `unique_role_permission` (`role_id`,`permission_id`),
  ADD KEY `idx_role` (`role_id`),
  ADD KEY `idx_permission` (`permission_id`);

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `updated_by` (`updated_by`),
  ADD KEY `idx_category` (`category`),
  ADD KEY `idx_key` (`setting_key`);

--
-- Indexes for table `system_settings_history`
--
ALTER TABLE `system_settings_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_setting` (`setting_id`),
  ADD KEY `idx_changed_by` (`changed_by`),
  ADD KEY `idx_changed_at` (`changed_at`);

--
-- Indexes for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  ADD PRIMARY KEY (`voucher_id`),
  ADD UNIQUE KEY `voucher_code` (`voucher_code`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `idx_user_unused` (`user_id`,`used`),
  ADD KEY `idx_voucher_code` (`voucher_code`),
  ADD KEY `idx_expires` (`expires_at`);

--
-- Indexes for table `training_phrases`
--
ALTER TABLE `training_phrases`
  ADD PRIMARY KEY (`phrase_id`),
  ADD KEY `idx_intent` (`intent_id`),
  ADD KEY `idx_active` (`is_active`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `weightage_history`
--
ALTER TABLE `weightage_history`
  ADD PRIMARY KEY (`history_id`),
  ADD KEY `idx_weightage` (`weightage_id`),
  ADD KEY `idx_persona` (`persona_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=120;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ai_performance_metrics`
--
ALTER TABLE `ai_performance_metrics`
  MODIFY `metric_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `ai_weightage`
--
ALTER TABLE `ai_weightage`
  MODIFY `weightage_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chatbot_analytics`
--
ALTER TABLE `chatbot_analytics`
  MODIFY `analytics_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `chatbot_training_data`
--
ALTER TABLE `chatbot_training_data`
  MODIFY `data_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chat_history`
--
ALTER TABLE `chat_history`
  MODIFY `chat_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

--
-- AUTO_INCREMENT for table `conversations`
--
ALTER TABLE `conversations`
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `intents`
--
ALTER TABLE `intents`
  MODIFY `intent_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `intent_responses`
--
ALTER TABLE `intent_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `inventory_logs`
--
ALTER TABLE `inventory_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1115;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2243;

--
-- AUTO_INCREMENT for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  MODIFY `reset_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `personas`
--
ALTER TABLE `personas`
  MODIFY `persona_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=229;

--
-- AUTO_INCREMENT for table `product_media`
--
ALTER TABLE `product_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `recommendation_logs`
--
ALTER TABLE `recommendation_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recommendation_ratings`
--
ALTER TABLE `recommendation_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `review_media`
--
ALTER TABLE `review_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
  MODIFY `role_permission_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=133;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `system_settings_history`
--
ALTER TABLE `system_settings_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `training_phrases`
--
ALTER TABLE `training_phrases`
  MODIFY `phrase_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `weightage_history`
--
ALTER TABLE `weightage_history`
  MODIFY `history_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin_activity_log`
--
ALTER TABLE `admin_activity_log`
  ADD CONSTRAINT `admin_activity_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `admin_sessions`
--
ALTER TABLE `admin_sessions`
  ADD CONSTRAINT `admin_sessions_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `admin_users` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `ai_performance_metrics`
--
ALTER TABLE `ai_performance_metrics`
  ADD CONSTRAINT `ai_performance_metrics_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`persona_id`) ON DELETE CASCADE;

--
-- Constraints for table `ai_weightage`
--
ALTER TABLE `ai_weightage`
  ADD CONSTRAINT `ai_weightage_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`persona_id`) ON DELETE CASCADE;

--
-- Constraints for table `conversations`
--
ALTER TABLE `conversations`
  ADD CONSTRAINT `conversations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE SET NULL;

--
-- Constraints for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  ADD CONSTRAINT `conversation_messages_ibfk_1` FOREIGN KEY (`conversation_id`) REFERENCES `conversations` (`conversation_id`) ON DELETE CASCADE;

--
-- Constraints for table `intent_responses`
--
ALTER TABLE `intent_responses`
  ADD CONSTRAINT `intent_responses_ibfk_1` FOREIGN KEY (`intent_id`) REFERENCES `intents` (`intent_id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`);

--
-- Constraints for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD CONSTRAINT `password_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_media`
--
ALTER TABLE `product_media`
  ADD CONSTRAINT `product_media_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `product_reviews`
--
ALTER TABLE `product_reviews`
  ADD CONSTRAINT `fk_review_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_review_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `recommendation_logs`
--
ALTER TABLE `recommendation_logs`
  ADD CONSTRAINT `recommendation_logs_ibfk_1` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`persona_id`) ON DELETE SET NULL;

--
-- Constraints for table `review_media`
--
ALTER TABLE `review_media`
  ADD CONSTRAINT `fk_media_review` FOREIGN KEY (`review_id`) REFERENCES `product_reviews` (`review_id`) ON DELETE CASCADE;

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`permission_id`) ON DELETE CASCADE;

--
-- Constraints for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD CONSTRAINT `system_settings_ibfk_1` FOREIGN KEY (`updated_by`) REFERENCES `admin_users` (`admin_id`) ON DELETE SET NULL;

--
-- Constraints for table `system_settings_history`
--
ALTER TABLE `system_settings_history`
  ADD CONSTRAINT `system_settings_history_ibfk_1` FOREIGN KEY (`setting_id`) REFERENCES `system_settings` (`setting_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `system_settings_history_ibfk_2` FOREIGN KEY (`changed_by`) REFERENCES `admin_users` (`admin_id`) ON DELETE CASCADE;

--
-- Constraints for table `temp_vouchers`
--
ALTER TABLE `temp_vouchers`
  ADD CONSTRAINT `temp_vouchers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `temp_vouchers_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`product_id`) ON DELETE CASCADE;

--
-- Constraints for table `training_phrases`
--
ALTER TABLE `training_phrases`
  ADD CONSTRAINT `training_phrases_ibfk_1` FOREIGN KEY (`intent_id`) REFERENCES `intents` (`intent_id`) ON DELETE CASCADE;

--
-- Constraints for table `weightage_history`
--
ALTER TABLE `weightage_history`
  ADD CONSTRAINT `weightage_history_ibfk_1` FOREIGN KEY (`weightage_id`) REFERENCES `ai_weightage` (`weightage_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `weightage_history_ibfk_2` FOREIGN KEY (`persona_id`) REFERENCES `personas` (`persona_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
