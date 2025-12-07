-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2025 at 05:29 PM
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
(34, 1, 'update', 'settings', 'Updated personal preferences', NULL, NULL, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', NULL, '/fyp/admin/admin_settings.php', NULL, '2025-12-07 16:16:43');

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
('6e1e039c5e0918d31acd787f569abfa25eb25f9501de8ab0bfcb0e8cba604ed4', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 19:31:17', '2025-12-02 19:31:17', '2025-12-03 19:31:17', 1),
('79aa47d6395816e07ebf45631ed66d4deab011b293d1cfd0f9185cf05e39939b', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 14:55:41', '2025-11-22 14:55:41', '2025-11-23 14:55:41', 1),
('7d7ab7204a215e7ff33e7ae6d8b0cc7e8e41c80aafe3a8ac69c2982afbe02136', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:34:23', '2025-11-22 04:34:23', '2025-11-23 04:34:23', 1),
('8dd810838b06ecb04f0dabc70db18acc422fd087f6672642649f0a97524cae27', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:34:31', '2025-11-22 04:34:31', '2025-11-23 04:34:31', 1),
('9dc7628840225b89fd580e0584845d2c1a32f16bed8c713599a1ff6980ee99c7', 2, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-11-22 04:35:07', '2025-11-22 04:35:07', '2025-11-23 04:35:07', 1),
('c413ddfda91a8cadd37df9d45ce663c101d1ff00fa9aaaa0c6df38374830ef95', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-02 19:29:42', '2025-12-02 19:29:42', '2025-12-03 19:29:42', 1),
('e9ee9f9532698841ed7956ad667311a8e3d92c888c9a010314e880a13dbb2ecf', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/142.0.0.0 Safari/537.36 Edg/142.0.0.0', '2025-12-01 14:36:36', '2025-12-01 14:36:36', '2025-12-02 14:36:36', 1),
('ebc0c1d6934f72fa7e86e0f23bd98a37eaed04162996392c418ceb297a66bffd', 1, '::1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36 Edg/143.0.0.0', '2025-12-07 23:24:31', '2025-12-07 23:24:31', '2025-12-08 23:24:31', 1);

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
(1, 'ADMIN-001', 'Admin', 'Cho Choong Kit', 'sarah.johnson@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 1, 'active', '2025-12-07 23:35:16', 3491, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-12-07 15:35:16'),
(2, 'ADMIN-002', 'Michael', 'Chen', 'michael.chen@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 2, 'active', '2025-11-22 04:35:49', 1, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-24 17:11:28'),
(3, 'ADMIN-003', 'Emily', 'Rodriguez', 'emily.rodriguez@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 4, 'active', NULL, 98, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:34:41'),
(4, 'ADMIN-004', 'David', 'Kim', 'david.kim@smartlaptop.com', '$2y$10$16u7PX4cGXybAi5et0rXTuNPdX8XiOQYQBUwHfmPun7IBiQGMrwL2', 3, 'active', NULL, 45, 0, NULL, NULL, 0, NULL, NULL, NULL, 'America/New_York', 'en', 1, 0, NULL, NULL, '2025-11-21 20:12:32', '2025-11-21 20:34:41');

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
(23, 'chat_692f18cb0d84f_caa9f98f5311ebb6', 6, '::1', 'web', '2025-12-07 16:37:18', NULL, 0, 0, 0, 0, 'neutral', 0.00, 'general_chat', NULL, NULL, 0, NULL, '2025-12-07 08:37:18', '2025-12-07 08:37:23');

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
(3, 'EXPIRED', 'percentage', 20.00, '2020-01-01', 0);

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
(3, 117, 11, 'restock', '', '2025-12-02 13:21:52');

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
(27, 6, 5000.00, 'Pending', 'GYZ', 'asdsdad', 'Lahat', 'Perak', '31500', 'Malaysia', '+60115076821', '2025-12-07 16:14:21', 'Credit Card', 0);

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
(31, 27, 128, 1, 5000.00);

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
(3, 'Creative', 'Designers & content creators', 'Aimed at graphic designers, video editors, and content creators who need powerful hardware for creative work.', 'bi bi-palette', 'warning', 'GPU Performance,Display Quality,Color Accuracy', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(4, 'Gamer', 'Gaming enthusiasts', 'Designed for gaming enthusiasts who prioritize high-performance graphics and smooth gameplay.', 'bi bi-controller', 'danger', 'GPU Performance,Refresh Rate,Cooling System', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15'),
(5, 'Developer', 'Software engineers', 'Caters to software developers who need fast compilation, multiple VMs, and coding efficiency.', 'bi bi-laptop', 'info', 'CPU Performance,RAM Capacity,SSD Speed', 1, '2025-11-21 19:46:15', '2025-11-21 19:46:15');

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
  `primary_use_case` varchar(100) DEFAULT NULL COMMENT 'e.g., Gaming, Business, General'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `product_name`, `product_category`, `related_to_category`, `brand`, `price`, `stock_quantity`, `min_stock_level`, `cpu`, `gpu`, `ram_gb`, `storage_gb`, `storage_type`, `display_size`, `battery_life`, `image_url`, `description`, `primary_use_case`) VALUES
(1, 'Creator Z16', 'laptop', NULL, 'MSI', 2499.99, 50, 10, 'Intel Core i7', 'NVIDIA RTX 3060', 16, 1024, 'SSD', 16.00, NULL, 'images/laptop1.png', 'A powerful laptop for creative professionals.', 'Creative'),
(2, 'ZenBook Duo', 'laptop', NULL, 'Asus', 1999.00, 50, 10, 'Intel Core i9', 'NVIDIA RTX 3070', 32, 1024, 'SSD', 14.00, NULL, 'images/laptop2.png', 'Dual-screen laptop for ultimate productivity.', 'Business'),
(3, 'Blade 15', 'laptop', NULL, 'Razer', 2999.50, 50, 10, 'Intel Core i7', 'NVIDIA RTX 3080', 16, 1024, 'SSD', 15.60, NULL, 'images/laptop3.png', 'The ultimate gaming laptop with a sleek design.', 'Gaming'),
(4, 'IdeaPad Slim 3', 'laptop', NULL, 'Lenovo', 649.00, 50, 10, 'AMD Ryzen 5', 'Integrated Radeon', 8, 512, 'SSD', 15.60, NULL, 'images/laptop4.jpg', 'Great for students and everyday tasks.', 'Student'),
(5, 'MacBook Pro 16', 'laptop', NULL, 'Apple', 3499.00, 50, 10, 'Apple M2 Pro', 'Integrated', 16, 1024, 'SSD', 16.20, NULL, 'images/laptop5.png', 'Powerful performance for professionals and creatives.', 'Creative'),
(6, 'Spectre x360', 'laptop', NULL, 'HP', 1450.00, 50, 10, 'Intel Core i7', 'Intel Iris Xe', 16, 512, 'SSD', 13.50, NULL, 'images/laptop6.png', 'A versatile 2-in-1 laptop with a premium feel.', 'Business'),
(7, 'ROG Strix G15', 'laptop', NULL, 'Asus', 1899.99, 49, 10, 'AMD Ryzen 9', 'NVIDIA RTX 3070', 16, 1024, 'SSD', 15.60, NULL, 'images/laptop7.png', 'High-refresh-rate gaming powerhouse.', 'Gaming'),
(8, 'Pavilion 15', 'mouse', NULL, 'HP', 799.00, 40, 10, 'Intel Core i5', 'Intel Iris Xe', 12, 512, 'SSD', 15.60, NULL, 'LaptopAdvisor/images/product_692eee166259e.jpg', 'A reliable all-rounder for work and entertainment.', 'General'),
(117, 'Logitech G ', 'mouse', 'Gaming', 'Logitech ', 300.00, 12, 10, '', '', 0, 0, 'SSD', 0.00, NULL, 'LaptopAdvisor/images/product_692d9b1204696.webp', '', 'Gaming'),
(118, 'Dell XPS 15', 'laptop', NULL, 'Dell', 1599.99, 50, 10, 'Intel Core i7-13700H', 'NVIDIA RTX 4050', 16, 512, 'SSD', 15.60, NULL, NULL, 'Premium business laptop with stunning display and powerful performance', 'Business'),
(119, 'MacBook Pro 14', 'laptop', NULL, 'Apple', 1999.99, 50, 10, 'Apple M3 Pro', 'Integrated', 18, 512, 'SSD', 14.20, NULL, NULL, 'Professional-grade laptop for creative work with excellent display', 'Creative'),
(120, 'ASUS ROG Strix G15', 'laptop', NULL, 'ASUS', 1299.99, 50, 10, 'AMD Ryzen 7 7735HS', 'NVIDIA RTX 4060', 16, 1000, 'SSD', 15.60, NULL, NULL, 'High-performance gaming laptop with RGB lighting', 'Gaming'),
(121, 'Lenovo ThinkPad X1', 'laptop', NULL, 'Lenovo', 1399.99, 20, 10, 'Intel Core i5-1340P', 'Intel Iris Xe', 16, 512, 'SSD', 14.00, NULL, NULL, 'Business ultrabook with legendary keyboard and security features', 'Business'),
(122, 'HP Pavilion 15', 'laptop', NULL, 'HP', 799.99, 50, 10, 'Intel Core i5-1235U', 'Intel Iris Xe', 8, 512, 'SSD', 15.60, NULL, NULL, 'Affordable laptop perfect for students and everyday computing', 'Student'),
(124, 'Razer BlackWidow V3', 'keyboard', 'Gaming', 'Razer', 139.99, 48, 10, '', '', 0, 0, '', 0.00, NULL, NULL, 'Mechanical gaming keyboard with Razer Green switches and RGB', 'Gaming'),
(125, 'SteelSeries Arctis 7', 'headset', 'Gaming', 'SteelSeries', 149.99, 50, 10, '', '', 0, 0, '', 0.00, NULL, NULL, 'Wireless gaming headset with lossless audio and 24-hour battery', 'Gaming'),
(128, 'Asus ROG Strix G16', 'laptop', NULL, 'Asus', 5000.00, 48, 10, ' i7-13650HX', 'NVIDIA GeForce RTX 4060', 32, 1024, 'SSD', 15.60, '10 hours', 'LaptopAdvisor/images/product_692f0409da2d4.webp', 'Power Power Up Your Gaming – Windows 11, 13th Gen Intel Core i7-13650HX Processor And NVIDIA GeForce RTX 4060 Laptop GPU, Up To 140W TGP To Win More Games\r\n\r\n', 'Gaming');

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
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_reviews`
--

INSERT INTO `product_reviews` (`review_id`, `product_id`, `user_id`, `rating`, `review_text`, `created_at`) VALUES
(1, 8, 4, 4, 'good laptop', '2025-11-25 12:23:07'),
(3, 117, 4, 5, 'good product', '2025-12-02 03:36:53');

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
(13, 4, 118, 1, '2025-12-02 15:36:29');

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
(1, 'Heng', 'chansh-am22@student.tarc.edu.my', '$2y$10$3Sm1lxjx6YodMxqCXW0riOY6Plen2z.pV.GXvYL.KH3a.3dmFPRMy', 'pending', 'uploads/68d83a959bb49-Screenshot 2024-05-03 151943.png', 'Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-27 17:21:42'),
(2, 'xuan', 'chan123@GMAIL.COM', '$2y$10$UqWAFCaxniqS5NKJDYgndOWtGvfVlv3rwWfdJVo.f0mbUMkYPmY16', 'pending', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-09-28 06:08:01'),
(3, 'xuan', 'abc@gmail.com', '$2y$10$WbU7EDXaKg0EcfW.B.spWOqvSqrLro1z9NCpw9DW.Z8xMhe04PRMm', 'pending', 'uploads/default.png', 'Student', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-11-11 14:17:17'),
(4, 'GYZ', 'GYZ@gmail.com', '$2y$10$b2eGc843Cu2g7Q0AyP4w1ulwZTmgomHrPv4QpFC9XtHSOAhlCOrqa', 'active', 'uploads/6920af2dc9395-donk.jpg', 'Business', 'GWK', 'Tingkat Lahat Mines 4', 'Lahat', 'Perak', '31500', 'Malaysia', '+60165076821', '2025-11-21 18:27:28'),
(6, 'cck', 'chock-am22@student.tarc.edu.my', '$2y$10$VzgL/FZDSCQeqhGiGgtxae3fX9.z/4QLAIlDDDmk.GhqSNczOI0tm', 'pending', 'uploads/default.png', 'General Use', NULL, NULL, NULL, NULL, NULL, NULL, NULL, '2025-12-02 07:00:41');

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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

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
  MODIFY `conversation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `conversation_messages`
--
ALTER TABLE `conversation_messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=165;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `coupon_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

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
  MODIFY `persona_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=129;

--
-- AUTO_INCREMENT for table `product_media`
--
ALTER TABLE `product_media`
  MODIFY `media_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `product_reviews`
--
ALTER TABLE `product_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `recommendation_logs`
--
ALTER TABLE `recommendation_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `recommendation_ratings`
--
ALTER TABLE `recommendation_ratings`
  MODIFY `rating_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
