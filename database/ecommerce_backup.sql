-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: Aug 01, 2026 at 10:18 PM
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
-- Database: `ecommerce`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `country` varchar(255) NOT NULL DEFAULT 'Pakistan',
  `province` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `address_line_1` text NOT NULL,
  `address_line_2` text DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `customer_id`, `country`, `province`, `city`, `postal_code`, `address_line_1`, `address_line_2`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 2, 'Pakistan', 'Sindh', 'Karachi', '75300', 'Gulshan ', 'Karachi', 1, '2026-07-24 13:06:40', '2026-07-24 13:06:40');

-- --------------------------------------------------------

--
-- Table structure for table `attendances`
--

CREATE TABLE `attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `attendance_date` date NOT NULL,
  `check_in` time DEFAULT NULL,
  `late_in_minutes` decimal(8,2) NOT NULL,
  `check_out` time DEFAULT NULL,
  `overtime` tinyint(1) NOT NULL DEFAULT 0,
  `overtime_minutes` decimal(8,2) NOT NULL DEFAULT 0.00,
  `remarks` text DEFAULT NULL,
  `status` enum('present','absent','late','half_day','leave') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `attendances`
--

INSERT INTO `attendances` (`id`, `employee_id`, `attendance_date`, `check_in`, `late_in_minutes`, `check_out`, `overtime`, `overtime_minutes`, `remarks`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-18', '17:30:00', 0.00, NULL, 0, 0.00, '', 'late', '2026-07-18 12:30:10', '2026-07-18 12:40:39'),
(2, 1, '2026-07-17', '15:37:00', 0.00, '23:43:00', 0, 0.00, '', 'present', '2026-07-18 12:30:28', '2026-07-18 12:38:06'),
(3, 1, '2026-07-16', NULL, 0.00, NULL, 0, 0.00, '', 'absent', '2026-07-18 12:38:49', '2026-07-18 12:38:49'),
(4, 1, '2026-07-15', '16:39:00', 0.00, '17:44:00', 0, 0.00, '', 'late', '2026-07-18 12:39:56', '2026-07-18 12:40:19'),
(5, 1, '2026-07-20', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(6, 1, '2026-07-21', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(7, 1, '2026-07-22', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(8, 1, '2026-07-23', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(9, 1, '2026-07-24', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(10, 1, '2026-07-27', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(11, 1, '2026-07-28', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(12, 1, '2026-07-29', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(13, 1, '2026-07-30', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(14, 1, '2026-07-31', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(15, 1, '2026-08-03', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(16, 1, '2026-08-04', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(17, 1, '2026-08-05', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(18, 1, '2026-08-06', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(19, 1, '2026-08-07', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-18 14:35:27', '2026-07-18 14:35:27'),
(20, 2, '2026-07-15', '11:53:00', 0.00, '22:42:00', 0, 0.00, '', 'leave', '2026-07-19 16:54:20', '2026-07-19 16:55:39'),
(21, 2, '2026-07-07', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(22, 2, '2026-07-08', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(23, 2, '2026-07-09', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(24, 2, '2026-07-10', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(25, 2, '2026-07-13', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(26, 2, '2026-07-14', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(27, 2, '2026-07-16', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(28, 2, '2026-07-17', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(29, 2, '2026-07-20', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(30, 2, '2026-07-21', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(31, 2, '2026-07-22', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(32, 2, '2026-07-23', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(33, 2, '2026-07-24', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(34, 2, '2026-07-27', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(35, 2, '2026-07-28', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(36, 2, '2026-07-29', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(37, 2, '2026-07-30', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-19 16:55:39', '2026-07-19 16:55:39'),
(38, 4, '2026-07-24', NULL, 0.00, NULL, 0, 0.00, '', 'absent', '2026-07-24 13:23:56', '2026-07-24 13:23:56'),
(39, 3, '2026-07-24', '18:24:00', 0.00, '22:28:00', 0, 0.00, '', 'present', '2026-07-24 13:24:17', '2026-07-24 13:24:17'),
(40, 4, '2026-07-27', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(41, 4, '2026-07-28', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(42, 4, '2026-07-29', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(43, 4, '2026-07-30', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(44, 4, '2026-07-31', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(45, 4, '2026-08-03', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(46, 4, '2026-08-04', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(47, 4, '2026-08-05', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(48, 4, '2026-08-06', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(49, 4, '2026-08-07', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(50, 4, '2026-08-10', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(51, 4, '2026-08-11', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:50:18', '2026-07-26 09:50:18'),
(52, 3, '2026-07-27', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:51:16', '2026-07-26 09:51:16'),
(53, 3, '2026-07-28', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:51:16', '2026-07-26 09:51:16'),
(54, 3, '2026-07-29', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:51:16', '2026-07-26 09:51:16'),
(55, 3, '2026-07-30', NULL, 0.00, NULL, 0, 0.00, NULL, 'leave', '2026-07-26 09:51:16', '2026-07-26 09:51:16'),
(56, 6, '2026-07-27', '10:44:00', 0.00, '20:47:00', 0, 0.00, '', 'present', '2026-07-27 11:45:02', '2026-07-27 11:45:02'),
(57, 5, '2026-07-27', '23:45:00', 0.00, '17:49:00', 0, 0.00, '', 'present', '2026-07-27 11:45:39', '2026-07-27 11:45:39'),
(58, 6, '2026-07-24', '17:46:00', 0.00, '11:52:00', 0, 0.00, '', 'present', '2026-07-27 11:46:42', '2026-07-27 11:46:42');

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `title`, `description`, `logo`, `status`, `created_at`, `updated_at`) VALUES
(1, 'HP', NULL, 'brands/hp-1784376945.png', '1', '2026-07-18 12:15:45', '2026-07-18 12:15:45'),
(2, 'DELL', NULL, 'brands/dell-1784376983.png', '1', '2026-07-18 12:16:23', '2026-07-24 13:11:37'),
(3, 'Samsung', NULL, 'brands/samsung-1784377027.png', '1', '2026-07-18 12:17:07', '2026-07-18 12:17:07'),
(4, 'Haier', NULL, 'brands/haier-1784473251.png', '1', '2026-07-19 15:00:51', '2026-07-19 15:00:51'),
(5, 'Apple', NULL, 'brands/appl-1784474709.png', '1', '2026-07-19 15:25:09', '2026-07-19 15:25:25'),
(6, 'Infinix', NULL, 'brands/infinix-1784474903.png', '1', '2026-07-19 15:28:23', '2026-07-19 15:28:23'),
(7, 'Tecno', NULL, 'brands/tecno-1784477655.png', '1', '2026-07-19 16:14:15', '2026-07-19 16:14:15'),
(8, 'Realme', NULL, 'brands/realme-1784548863.png', '1', '2026-07-20 12:01:03', '2026-07-20 12:01:03'),
(9, 'Redmi', NULL, 'brands/redmi-1784548909.png', '1', '2026-07-20 12:01:49', '2026-07-20 12:01:49'),
(10, 'Nokia', NULL, 'brands/nokia-1784548958.png', '1', '2026-07-20 12:02:38', '2026-07-20 12:02:38'),
(11, 'Oppo', NULL, 'brands/oppo-1784549051.png', '1', '2026-07-20 12:04:11', '2026-07-20 12:04:11');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_documents`
--

CREATE TABLE `candidate_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_application_id` bigint(20) UNSIGNED NOT NULL,
  `document_type` enum('resume','cover_letter','national_id','passport','driving_license','photograph','degree','transcript','experience_letter','certification','portfolio','reference_letter','police_clearance','medical_certificate','visa','work_permit','other') NOT NULL,
  `title` varchar(255) NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `mime_type` varchar(100) DEFAULT NULL,
  `file_size` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `is_verified` tinyint(1) NOT NULL DEFAULT 0,
  `verified_by` bigint(20) UNSIGNED DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_education`
--

CREATE TABLE `candidate_education` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_application_id` bigint(20) UNSIGNED NOT NULL,
  `graduate_year` year(4) NOT NULL,
  `grade` decimal(8,2) NOT NULL,
  `degree_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_portfolios`
--

CREATE TABLE `candidate_portfolios` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_application_id` bigint(20) UNSIGNED NOT NULL,
  `portfolio_website` varchar(255) NOT NULL,
  `github` varchar(255) NOT NULL,
  `linkedin` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `candidate_work_experiences`
--

CREATE TABLE `candidate_work_experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `month_of_experience` decimal(8,2) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `experience_type` enum('cooperate','freelance') NOT NULL DEFAULT 'cooperate',
  `responsibility` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `carts`
--

CREATE TABLE `carts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('active','ordered','abandoned','cancelled') NOT NULL DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `carts`
--

INSERT INTO `carts` (`id`, `user_id`, `ip_address`, `created_at`, `updated_at`, `status`) VALUES
(9, 15, '127.0.0.1', '2026-07-25 11:35:28', '2026-07-25 11:35:28', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `cart_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `cart_id`, `product_id`, `quantity`, `price`, `created_at`, `updated_at`) VALUES
(11, 9, 3, 1, 67500.00, '2026-07-25 11:35:28', '2026-07-25 11:35:28'),
(12, 9, 4, 1, 55000.00, '2026-07-28 13:35:38', '2026-07-28 13:35:38');

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Laptop', NULL, 1, '2026-07-18 11:28:08', '2026-07-18 11:28:08'),
(2, 'Mobile', NULL, 1, '2026-07-18 12:14:08', '2026-07-18 12:14:08'),
(3, 'Television', NULL, 1, '2026-07-18 12:14:59', '2026-07-18 12:14:59'),
(4, 'Smart Watch', NULL, 1, '2026-07-19 15:28:48', '2026-07-19 15:28:48'),
(5, 'Refrigerator', NULL, 1, '2026-07-19 15:30:34', '2026-07-19 15:30:34');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `status`, `email`, `phone`, `subject`, `message`, `created_at`, `updated_at`) VALUES
(2, 'Syed Abdul Samad Ahsan', 'replied', 'abdulsamadahsan@gmail.com', '03421462082', 'Inquiry ', 'When App Will Be Launched', '2026-07-18 10:12:42', '2026-07-18 11:38:53'),
(3, 'Waqas ', 'replied', 'waqas@gmail.com', '03421462082', 'Request', 'I want to register as supplier', '2026-07-18 10:58:26', '2026-07-18 11:33:20'),
(4, 'Syed Abdul Samad Ahsan', 'replied', 'syedabdultechnicalcop@gmail.com', '03421462082', 'Request', 'i want to register', '2026-07-18 11:56:18', '2026-07-18 12:02:01'),
(5, 'Wahaj', 'replied', 'abdulsamadahsan@gmail.com', '03421462082', 'Inquiry', 'i Want to register as delivery boy', '2026-07-18 12:09:47', '2026-07-18 12:10:42');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(12,2) NOT NULL,
  `minimum_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `maximum_discount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `expiry_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `minimum_order_amount`, `maximum_discount`, `usage_limit`, `used_count`, `start_date`, `expiry_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'SUMMER10', 'percentage', 10.00, 0.00, NULL, NULL, 0, '2026-07-24', '2026-08-25', 1, '2026-07-24 13:27:23', '2026-07-24 13:27:23');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `referral_by` bigint(20) UNSIGNED DEFAULT NULL,
  `referral_bonus` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `referral_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `user_id`, `phone`, `status`, `referral_by`, `referral_bonus`, `created_at`, `updated_at`, `referral_code`) VALUES
(1, 12, '03421462082', 1, NULL, 0.00, '2026-07-19 15:48:30', '2026-07-19 15:48:30', NULL),
(2, 15, '03421462082', 1, NULL, 0.00, '2026-07-21 05:22:49', '2026-07-21 05:22:49', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `customer_support_tickets`
--

CREATE TABLE `customer_support_tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('open','in_progress','resolved','closed') NOT NULL DEFAULT 'open',
  `assigned_to` bigint(20) UNSIGNED DEFAULT NULL,
  `resolved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customer_support_tickets`
--

INSERT INTO `customer_support_tickets` (`id`, `ticket_no`, `customer_id`, `order_id`, `subject`, `message`, `priority`, `status`, `assigned_to`, `resolved_at`, `created_at`, `updated_at`) VALUES
(1, 'TCK-20260722-000001', 2, 6, 'Item is missing', 'Product is missing', 'medium', 'open', NULL, NULL, '2026-07-22 14:30:44', '2026-07-22 14:30:44'),
(2, 'TCK-20260725-000002', 2, 7, 'Product is not delivered', 'Product is missing', 'medium', 'closed', NULL, NULL, '2026-07-25 15:06:39', '2026-07-25 16:18:11');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_assignments`
--

CREATE TABLE `delivery_assignments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipment_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_boy_id` bigint(20) UNSIGNED NOT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `picked_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `status` enum('assigned','picked','in_transit','delivered','failed') NOT NULL DEFAULT 'assigned',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `failed_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_assignments`
--

INSERT INTO `delivery_assignments` (`id`, `shipment_id`, `delivery_boy_id`, `assigned_at`, `picked_at`, `delivered_at`, `status`, `remarks`, `created_at`, `updated_at`, `failed_reason`) VALUES
(1, 2, 1, '2026-07-19 16:00:54', NULL, NULL, 'assigned', NULL, '2026-07-19 16:00:54', '2026-07-19 16:00:54', NULL),
(2, 8, 1, '2026-07-24 13:15:21', NULL, NULL, 'assigned', '', '2026-07-24 13:15:21', '2026-07-24 13:15:21', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `delivery_attempts`
--

CREATE TABLE `delivery_attempts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipment_id` bigint(20) UNSIGNED NOT NULL,
  `delivery_assignment_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_boy_id` bigint(20) UNSIGNED DEFAULT NULL,
  `attempt_number` tinyint(3) UNSIGNED NOT NULL,
  `attempted_at` datetime NOT NULL,
  `reason` enum('customer_unavailable','customer_refused','wrong_address','address_not_found','phone_unreachable') DEFAULT NULL,
  `status` enum('delivered','rescheduled','failed') NOT NULL,
  `remarks` text DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `recipient_name` varchar(255) DEFAULT NULL,
  `recipient_phone` varchar(20) DEFAULT NULL,
  `otp_verified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `delivery_boys`
--

CREATE TABLE `delivery_boys` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `vehicle_type` varchar(255) DEFAULT NULL,
  `vehicle_number` varchar(255) DEFAULT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_boys`
--

INSERT INTO `delivery_boys` (`id`, `user_id`, `cnic`, `vehicle_type`, `vehicle_number`, `is_available`, `status`, `created_at`, `updated_at`, `phone`) VALUES
(1, 13, '42201-12345678-9', 'bike', 'ALKL-1022', 1, 'active', '2026-07-19 15:59:58', '2026-07-19 15:59:58', '03111234567');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Sales ', 'It Manages Sales', 1, '2026-07-18 09:21:41', NULL),
(2, 'Product Management', '', 1, '2026-07-21 07:32:20', NULL),
(3, 'Customer Support', '', 1, '2026-07-21 07:33:14', NULL),
(4, 'Inventory', 'It manages inventory', 1, '2026-07-21 07:34:28', NULL),
(5, 'Order Management', '', 1, '2026-07-21 07:51:08', NULL),
(6, 'Finance', '', 1, '2026-08-01 12:37:14', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `educations`
--

CREATE TABLE `educations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `institution_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `short_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `educations`
--

INSERT INTO `educations` (`id`, `institution_id`, `name`, `status`, `short_code`, `created_at`, `updated_at`) VALUES
(1, 1, 'Bachelor Of Computer Science', '1', 'BSCS', '2026-07-18 09:20:37', '2026-07-18 09:20:37'),
(3, 2, 'Pre Enginnering (Intermediate)', '1', 'Pre Enginnering', '2026-07-28 14:44:34', '2026-07-28 14:44:34');

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `institution_id` bigint(20) UNSIGNED DEFAULT NULL,
  `education_id` bigint(20) UNSIGNED DEFAULT NULL,
  `father_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `designation` varchar(255) DEFAULT NULL,
  `joining_date` date DEFAULT NULL,
  `address` text DEFAULT NULL,
  `cnic` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `status` enum('retired','terminated','active','suspended') NOT NULL DEFAULT 'active',
  `gender` enum('male','female') NOT NULL DEFAULT 'male',
  `marital_status` enum('married','single','divorced') NOT NULL DEFAULT 'single',
  `emergency_contact_name` varchar(255) DEFAULT NULL,
  `emergency_contact_number` varchar(255) DEFAULT NULL,
  `employment_type` enum('internship','contract','part-time','permanent') NOT NULL DEFAULT 'permanent',
  `probation_period` varchar(255) DEFAULT NULL,
  `emergency_contact_relationship` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_title` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) NOT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `branch_name` varchar(255) DEFAULT NULL,
  `branch_code` varchar(255) DEFAULT NULL,
  `swift_code` varchar(255) DEFAULT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `shift` enum('morning','evening','night') NOT NULL DEFAULT 'morning',
  `reporting_time` time DEFAULT NULL,
  `notice_period` decimal(8,2) NOT NULL DEFAULT 30.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `user_id`, `department_id`, `institution_id`, `education_id`, `father_name`, `date_of_birth`, `phone`, `designation`, `joining_date`, `address`, `cnic`, `photo`, `status`, `gender`, `marital_status`, `emergency_contact_name`, `emergency_contact_number`, `employment_type`, `probation_period`, `emergency_contact_relationship`, `created_at`, `updated_at`, `bank_name`, `account_title`, `account_number`, `iban`, `branch_name`, `branch_code`, `swift_code`, `is_primary`, `notes`, `shift`, `reporting_time`, `notice_period`) VALUES
(1, 7, 1, 1, 1, 'Ali ', '1982-08-04', '03421462082', 'Sales Manager', '2021-01-08', 'D.1 Islamic arcade main university road near samama', '42201-1234562-9', 'employees/employee-1784377479-gF6OWL0W.jpg', 'active', 'male', 'single', NULL, NULL, 'permanent', '2 ', NULL, '2026-07-18 12:24:40', '2026-07-19 12:59:30', 'MCB', 'Waqas ', '038483473636', 'PK18MCB038483473636', 'Gulshan', 'GULSHAN12', 'MCB13373', 1, NULL, 'night', '00:00:00', 30.00),
(2, 14, 5, 1, 1, 'Ali', '1976-02-20', '03421462082', 'Order Dispatcher', '2025-11-07', 'D.1 Islamic arcade main university road near samama', '42201-1234567-8', 'employees/employee-1784479051-eWiAyDjh.jpg', 'active', 'female', 'single', NULL, NULL, 'permanent', '2 ', NULL, '2026-07-19 16:37:32', '2026-07-21 07:51:54', 'Al Habib ', 'Sajal Ali', '0393939292', 'PK18AlHABIB0393939292', 'Gulshan', 'GUL123', 'HAB123', 1, NULL, 'morning', '09:00:00', 30.00),
(3, 19, 1, 1, 1, 'Ahmed', '1978-07-11', '03421462082', 'Social Media Manager', '2025-01-27', 'D.1 Islamic arcade main university road near samama', '61101-2000381-1', 'employees/employee-1784618466-egxfXtej.jpg', 'active', 'male', 'single', 'Ali', '03421462082', 'permanent', '2', 'Brother', '2026-07-21 07:21:07', '2026-07-21 07:31:04', 'Alfalah', 'Sohail Ahmed', '033939393', 'PK18ALFA033939393', 'Gulshan', '12222223', 'PK12293', 1, NULL, 'evening', '15:00:00', 30.00),
(4, 20, 2, 1, 1, 'Salman Mirza', '1983-07-13', '0333-668623', 'Pricing Analyst', '2025-12-28', 'D1 Islamic\nArcade', '42103-2015753-1', 'employees/employee-1784619838-PPA0vcPz.jpg', 'active', 'male', 'single', 'Abdul Samad Ahsan', '03421462082', 'contract', '2', 'Cousin', '2026-07-21 07:43:59', '2026-07-21 07:43:59', 'M', 'Ali Mizra', '00323939933', 'PK1800323939933', 'Gulshan', '122233', NULL, 1, NULL, 'evening', '15:00:00', 30.00),
(5, 22, 3, 1, 1, 'Tauqeer', '1999-03-03', '03357467403', 'CSR', '2025-08-05', 'Pakistan', '42103-2000213-5', 'employees/employee-1785143682-9bSbQ6iR.png', 'active', 'female', 'single', NULL, NULL, 'permanent', '1', NULL, '2026-07-27 09:14:43', '2026-07-27 09:59:34', 'MCB', 'Zoha Tauqeer', '1373733', 'PK181373733', 'Gulshan', '12222', 'PK133', 1, NULL, 'morning', '09:12:00', 30.00),
(6, 24, 4, 1, 1, 'Noor Ahmed', '2000-02-27', '03421462082', 'Inventory Inspector', '2024-06-12', 'Karachi,Pakistan', '42102-2018719-5', 'employees/employee-1785148039-i8yUZNec.png', 'active', 'female', 'single', NULL, NULL, 'permanent', '1', NULL, '2026-07-27 10:27:19', '2026-07-27 11:01:28', 'MCB', 'Areeba Noor', '484838939289', 'PK18MCB484838939289', 'Gulshan', '1234', 'PK1292', 1, NULL, 'morning', '15:25:00', 30.00);

-- --------------------------------------------------------

--
-- Table structure for table `employee_documents`
--

CREATE TABLE `employee_documents` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `document_number` varchar(255) DEFAULT NULL,
  `file` varchar(255) NOT NULL,
  `issue_date` date DEFAULT NULL,
  `expiry_date` date DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `document_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `employee_documents`
--

INSERT INTO `employee_documents` (`id`, `employee_id`, `document_number`, `file`, `issue_date`, `expiry_date`, `remarks`, `document_type`, `created_at`, `updated_at`) VALUES
(1, 4, '	CNIC-000005', 'employee-documents/KVIEVAzUDhTfnQz1QafmsgNFNUBr89HBVuYuNdj9.png', '2026-07-29', '2031-07-29', '', 'CNIC', '2026-07-29 11:19:08', '2026-07-29 11:19:08'),
(2, 1, '	CNIC-000004', 'employee-documents/mGM76vsnJLwmTynklCPVSdEpHpA9Xtvn2YH094Nm.png', '2026-07-29', '2031-07-09', '', 'CNIC', '2026-07-29 11:20:53', '2026-07-29 11:20:53'),
(3, 6, '	CNIC-000003', 'employee-documents/ywrutSf9wuLP6KJRSb2G5Hld7jmpgv4UoJ5O3M1C.png', '2026-07-29', '2031-07-29', '', 'CNIC', '2026-07-29 11:39:55', '2026-07-29 11:39:55'),
(4, 3, '	CNIC-000002', 'employee-documents/AHxsGfRHMiwjY6IO9i4cwgUZ26u915iGtjU9oWqG.png', '2026-07-29', '2031-07-29', '', 'CNIC', '2026-07-29 11:45:38', '2026-07-29 11:45:38'),
(16, 4, 'EC-000001', 'cards/employee-Ali Mirza.png', '2026-07-30', '2031-07-30', NULL, 'EmployeeCard', '2026-07-30 18:12:13', '2026-07-30 18:12:13'),
(17, 6, 'EC-000002', 'cards/employee-Areeba Noor.png', '2026-07-30', '2031-07-30', NULL, 'EmployeeCard', '2026-07-30 18:12:28', '2026-07-30 18:12:28'),
(18, 2, 'EC-000003', 'cards/employee-Sajal.png', '2026-07-30', '2031-07-30', NULL, 'EmployeeCard', '2026-07-30 18:21:21', '2026-07-30 18:21:21'),
(19, 2, 'CNIC-000001', 'employee-documents/BYmrbGpv9gJBG77gz6RMplyRGujEJtbsr0EWqzLJ.png', '2026-07-30', '2031-07-30', '', 'CNIC', '2026-07-30 18:31:26', '2026-07-30 18:31:26'),
(20, 5, 'CNIC-000002', 'employee-documents/BNKQSP8jUrfRnySYv9boL6yUdb8VQZIhhEVrJtGX.png', '2026-07-30', '2031-07-30', '', 'CNIC', '2026-07-30 18:36:19', '2026-07-30 18:36:19'),
(21, 3, 'EC-000004', 'cards/employee-Sohail .png', '2026-07-30', '2031-07-30', NULL, 'EmployeeCard', '2026-07-30 18:38:06', '2026-07-30 18:38:06'),
(22, 1, 'EC-000005', 'cards/employee-Waqas .png', '2026-07-30', '2031-07-30', NULL, 'EmployeeCard', '2026-07-30 18:38:19', '2026-07-30 18:38:19');

-- --------------------------------------------------------

--
-- Table structure for table `employee_education`
--

CREATE TABLE `employee_education` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `graduate_year` year(4) NOT NULL,
  `grade` decimal(8,2) NOT NULL,
  `degree_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_experiences`
--

CREATE TABLE `employee_experiences` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `month_of_experience` decimal(8,2) NOT NULL,
  `designation` varchar(255) NOT NULL,
  `company` varchar(255) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `experience_type` enum('cooperate','freelance') NOT NULL DEFAULT 'cooperate',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_resignations`
--

CREATE TABLE `employee_resignations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `resignation_date` date NOT NULL,
  `last_working_day` date NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_terminations`
--

CREATE TABLE `employee_terminations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `last_working_day` date NOT NULL,
  `status` enum('Pending','Approved','Rejected','Completed') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employee_testimonials`
--

CREATE TABLE `employee_testimonials` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `review` text NOT NULL,
  `rating` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_category_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `expense_date` date NOT NULL,
  `payment_method` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `expense_category_id`, `amount`, `expense_date`, `payment_method`, `status`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 2300000.00, '2026-07-19', 'card', 'completed', NULL, '2026-07-19 15:36:47', '2026-07-19 15:36:47'),
(2, 1, 2500000.00, '2026-07-19', 'card', 'completed', NULL, '2026-07-19 15:41:51', '2026-07-19 15:41:51'),
(3, 1, 500000.00, '2026-07-19', 'card', 'completed', NULL, '2026-07-19 16:11:39', '2026-07-19 16:11:39'),
(4, 1, 400000.00, '2026-07-19', 'card', 'completed', NULL, '2026-07-19 16:15:42', '2026-07-19 16:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'purchase', NULL, 1, '2026-07-19 15:36:47', '2026-07-19 15:36:47');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `question` text NOT NULL,
  `answer` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_received_notes`
--

CREATE TABLE `goods_received_notes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `grn_number` varchar(255) NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `received_by` bigint(20) UNSIGNED NOT NULL,
  `received_date` date NOT NULL,
  `supplier_invoice_number` varchar(255) DEFAULT NULL,
  `status` enum('draft','received','partially_received','completed','cancelled') NOT NULL DEFAULT 'received',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `goods_received_note_items`
--

CREATE TABLE `goods_received_note_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `goods_received_note_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_item_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `ordered_quantity` decimal(12,2) NOT NULL,
  `previously_received_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `received_quantity` decimal(12,2) NOT NULL,
  `accepted_quantity` decimal(12,2) NOT NULL,
  `rejected_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_cost` decimal(15,2) NOT NULL,
  `line_total` decimal(15,2) NOT NULL,
  `quality_status` enum('accepted','partially_accepted','rejected') NOT NULL DEFAULT 'accepted',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `holiday_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `institutions`
--

CREATE TABLE `institutions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `type` varchar(255) DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `institutions`
--

INSERT INTO `institutions` (`id`, `name`, `type`, `city`, `address`, `status`, `created_at`, `updated_at`) VALUES
(1, 'University Of Karachi', 'university', 'Karachi', 'Karachi Pakistan', 1, '2026-07-18 09:19:46', '2026-07-18 09:19:46'),
(2, 'Commecs College', 'college', 'Karachi', 'Karachi Pakistan', 1, '2026-07-28 14:43:08', '2026-07-28 14:43:08');

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_application_id` bigint(20) UNSIGNED NOT NULL,
  `scheduled_at` datetime NOT NULL,
  `type` enum('online','physical','phone') NOT NULL,
  `meeting_link` varchar(255) DEFAULT NULL,
  `status` enum('scheduled','completed','cancelled','delayed') NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `interviewer` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interview_feedback`
--

CREATE TABLE `interview_feedback` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `interview_id` bigint(20) UNSIGNED NOT NULL,
  `technical_score` tinyint(3) UNSIGNED NOT NULL,
  `communication_score` tinyint(3) UNSIGNED NOT NULL,
  `attitude_score` tinyint(3) UNSIGNED NOT NULL,
  `overall_score` tinyint(3) UNSIGNED NOT NULL,
  `comments` text NOT NULL,
  `recommended` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('draft','unpaid','paid','partially_paid','cancelled') NOT NULL DEFAULT 'unpaid',
  `invoice_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `pdf_path` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `invoices`
--

INSERT INTO `invoices` (`id`, `sale_id`, `status`, `invoice_date`, `due_date`, `pdf_path`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 'unpaid', '2026-07-19', NULL, 'invoices/SALE-20260719-20530001.pdf', NULL, '2026-07-19 15:55:07', '2026-07-19 15:55:08'),
(2, 3, 'unpaid', '2026-07-19', NULL, 'invoices/SALE-20260719-21160003.pdf', NULL, '2026-07-19 16:17:09', '2026-07-19 16:17:10'),
(3, 4, 'unpaid', '2026-07-20', NULL, 'invoices/SALE-20260720-17450004.pdf', NULL, '2026-07-20 12:45:35', '2026-07-20 12:45:42'),
(4, 5, 'unpaid', '2026-07-20', NULL, 'invoices/SALE-20260720-20010005.pdf', NULL, '2026-07-20 15:02:18', '2026-07-20 15:02:24'),
(5, 6, 'unpaid', '2026-07-21', NULL, 'invoices/SALE-20260721-10240006.pdf', NULL, '2026-07-21 05:24:35', '2026-07-21 05:24:41'),
(6, 7, 'unpaid', '2026-07-24', NULL, 'invoices/SALE-20260724-18030007.pdf', NULL, '2026-07-24 13:03:36', '2026-07-24 13:03:40'),
(7, 8, 'unpaid', '2026-07-24', NULL, 'invoices/SALE-20260724-18070008.pdf', NULL, '2026-07-24 13:09:25', '2026-07-24 13:09:26'),
(8, 9, 'unpaid', '2026-07-25', NULL, 'invoices/SALE-20260725-16330009.pdf', NULL, '2026-07-25 11:34:38', '2026-07-25 11:34:44');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_applications`
--

CREATE TABLE `job_applications` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_posting_id` bigint(20) UNSIGNED NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `father_name` varchar(255) NOT NULL,
  `date_of_birth` date NOT NULL,
  `photo` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `resume` varchar(255) NOT NULL,
  `last_education` varchar(255) NOT NULL,
  `last_institute` varchar(255) NOT NULL,
  `month_of_exprience` varchar(255) NOT NULL,
  `cnic` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `expected_salary` decimal(10,2) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `status` enum('pending','shortlisted','interview','rejected','hired') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `gender` enum('female','male') NOT NULL,
  `bio` text NOT NULL,
  `reason_to_join` text NOT NULL,
  `willing_to_relocate` tinyint(1) NOT NULL DEFAULT 1,
  `having_any_disablities` tinyint(1) NOT NULL DEFAULT 1,
  `location` varchar(255) DEFAULT NULL,
  `any_query` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_offers`
--

CREATE TABLE `job_offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `job_application_id` bigint(20) UNSIGNED NOT NULL,
  `salary` decimal(10,2) NOT NULL,
  `joining_date` date NOT NULL,
  `probation_months` tinyint(3) UNSIGNED NOT NULL DEFAULT 3,
  `notice_period_days` tinyint(3) UNSIGNED NOT NULL DEFAULT 30,
  `status` enum('pending','accepted','declined') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_postings`
--

CREATE TABLE `job_postings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `department_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `job_title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `responsibilities` text DEFAULT NULL,
  `requirements` text DEFAULT NULL,
  `benefits` text DEFAULT NULL,
  `vacancies` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `minimum_salary` decimal(10,2) DEFAULT NULL,
  `maximum_salary` decimal(10,2) DEFAULT NULL,
  `employment_type` enum('permanent','part-time','contract','internship') NOT NULL,
  `work_mode` enum('onsite','remote','hybrid') NOT NULL,
  `closing_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `year_experience` decimal(10,0) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `job_postings`
--

INSERT INTO `job_postings` (`id`, `department_id`, `created_by`, `job_title`, `description`, `responsibilities`, `requirements`, `benefits`, `vacancies`, `minimum_salary`, `maximum_salary`, `employment_type`, `work_mode`, `closing_date`, `is_active`, `created_at`, `updated_at`, `year_experience`) VALUES
(1, 2, 1, 'Category Executive', 'Responsible for product management operations in a large eCommerce business.', 'Manage daily product management activities,\nCollaborate with other departments,\nMeet KPIs and company standards,', 'Relevant degree or diploma,\nComputer skills,\nCommunication skills,\nProblem-solving ability', 'Medical insurance,\nAnnual leave,\nPerformance bonus,\nTraining & career growth', 1, 80000.00, 120000.00, 'permanent', 'onsite', '2026-08-31', 1, '2026-08-01 08:25:43', '2026-08-01 08:25:43', 1),
(2, 1, 1, 'Regional Sales Manager', 'Responsible for sales operations .', 'Manage daily sales activities,\nCollaborate with other departments,\nMeet KPIs and company standards', 'Bachelor In Business Administration,\nCommnication Skill \nHandle Extreme Pressure', 'Medical Insurance\nPaid Leave\n2 day work from home', 1, 180000.00, 400000.00, 'permanent', 'onsite', '2026-08-31', 1, '2026-08-01 12:43:08', '2026-08-01 12:43:08', 3);

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `leave_type` enum('casual','sick','annual','unpaid') NOT NULL,
  `from_date` date NOT NULL,
  `to_date` date NOT NULL,
  `days` int(11) NOT NULL DEFAULT 1,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `employee_id`, `leave_type`, `from_date`, `to_date`, `days`, `reason`, `status`, `approved_by`, `approved_at`, `created_at`, `updated_at`) VALUES
(5, 1, 'sick', '2026-07-18', '2026-08-07', 15, '', 'approved', 1, '2026-07-18 14:49:38', '2026-07-18 14:49:38', '2026-07-18 14:49:38'),
(6, 2, 'sick', '2026-07-07', '2026-07-30', 18, '', 'approved', 1, '2026-07-19 16:55:39', '2026-07-19 16:55:24', '2026-07-19 16:55:39'),
(7, 4, 'casual', '2026-07-27', '2026-08-11', 12, '', 'approved', 1, '2026-07-26 09:50:18', '2026-07-26 09:49:58', '2026-07-26 09:50:19'),
(8, 3, 'sick', '2026-07-26', '2026-07-30', 4, '', 'approved', 1, '2026-07-26 09:51:16', '2026-07-26 09:50:58', '2026-07-26 09:51:16');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_06_04_160403_create_categories_table', 1),
(5, '2026_06_04_160419_create_suppliers_table', 1),
(6, '2026_06_04_160431_create_products_table', 1),
(7, '2026_06_04_160923_create_customers_table', 1),
(8, '2026_06_04_161029_create_sales_table', 1),
(9, '2026_06_04_161348_create_sale_items_table', 1),
(10, '2026_06_06_152950_create_brands_table', 1),
(11, '2026_06_07_155947_create_employees_table', 1),
(12, '2026_06_07_160419_create_warehouses_table', 1),
(13, '2026_06_12_223506_add_user_id_to_table', 1),
(14, '2026_06_12_230528_create_orders_table', 1),
(15, '2026_06_12_231515_create_departments_table', 1),
(16, '2026_06_13_142514_create_carts_table', 1),
(17, '2026_06_17_115143_create_cart_items_table', 1),
(18, '2026_06_17_120801_create_payments_table', 1),
(19, '2026_06_17_122512_create_purchases_table', 1),
(20, '2026_06_17_122623_create_purchase_items_table', 1),
(21, '2026_06_17_122703_create_transactions_table', 1),
(22, '2026_06_17_122924_create_wallets_table', 1),
(23, '2026_06_17_123045_create_wallet_transactions_table', 1),
(24, '2026_06_17_123220_create_purchase_returns_table', 1),
(25, '2026_06_17_123302_create_purchase_return_items_table', 1),
(26, '2026_06_17_123544_create_sales_returns_table', 1),
(27, '2026_06_17_123648_create_sales_return_items_table', 1),
(28, '2026_06_17_123650_create_role_table_and_role_to_user', 1),
(29, '2026_06_18_130137_create_wishlists_table', 1),
(30, '2026_06_18_130138_create_wishlist_items_table', 1),
(31, '2026_06_18_130331_create_stock_movements_table', 1),
(32, '2026_06_18_130332_create_stock_transfer_items_table', 1),
(33, '2026_06_18_130332_create_stock_transfer_table', 1),
(34, '2026_06_18_130952_create_reviews_table', 1),
(35, '2026_06_18_143207_create_stocks_table', 1),
(36, '2026_06_18_144054_create_customer_support_tickets_table', 1),
(37, '2026_06_18_144147_create_ticket_messages_table', 1),
(38, '2026_06_18_144310_create_coupons_table', 1),
(39, '2026_06_18_144510_create_supplier_payments_table', 1),
(40, '2026_06_18_144638_create_taxes_table', 1),
(41, '2026_06_18_144721_create_addresses_table', 1),
(42, '2026_06_18_145552_create_shipping_methods_table', 1),
(43, '2026_06_18_145554_create_shipments_table', 1),
(44, '2026_06_18_145643_create_shipment_trackings_table', 1),
(45, '2026_06_18_150316_create_delivery_boys_table', 1),
(46, '2026_06_18_150501_create_delivery_assignments_table', 1),
(47, '2026_06_18_150807_create_attendances_table', 1),
(48, '2026_06_18_151130_create_leaves_table', 1),
(49, '2026_06_18_152246_create_salaries_table', 1),
(50, '2026_06_18_152600_create_payrolls_table', 1),
(51, '2026_06_18_153004_create_salary_payments_table', 1),
(52, '2026_06_18_153606_create_wallet_topup_requests_table', 1),
(53, '2026_06_18_160635_create_product_images_table', 1),
(54, '2026_06_18_160754_create_invoices_table', 1),
(55, '2026_06_19_110520_create_contact_messages_table', 1),
(56, '2026_06_19_114425_create_expense_categories_table', 1),
(57, '2026_06_19_115138_create_expenses_table', 1),
(58, '2026_06_19_175702_update_employee_table', 1),
(59, '2026_06_19_182045_create_organization_eduction_table', 1),
(60, '2026_06_20_000001_create_taxes_table', 1),
(61, '2026_06_20_000002_create_coupons_table', 1),
(62, '2026_06_20_191644_add_fields_to_table', 1),
(63, '2026_06_22_123936_create_employee_bank_accounts_table', 1),
(64, '2026_06_23_032017_update_salaries_table', 1),
(65, '2026_06_26_124146_add_discount_product_table', 1),
(66, '2026_06_26_154845_add_category_tax_table', 1),
(67, '2026_06_26_204017_add_ipadd_cart_table', 1),
(68, '2026_06_26_223325_add_type_shipping_methods_table', 1),
(69, '2026_06_27_124904_add_type_coupon_code_shipping_cost_table', 1),
(70, '2026_06_27_150608_add_remaining_column_table', 1),
(71, '2026_06_27_164124_create_payment_methods_table', 1),
(72, '2026_06_27_164814_update_payment_table', 1),
(73, '2026_06_28_000511_add_optional_sales_field', 1),
(74, '2026_06_28_015523_update_cart_tables', 1),
(75, '2026_06_28_045549_add_city_orders', 1),
(76, '2026_06_29_010001_add_action_sale_table', 2),
(77, '2026_06_29_145706_update_sales_return_items', 2),
(78, '2026_06_30_031855_update_reviews_table', 2),
(79, '2026_06_30_033018_update_reviews_sale_id_table', 2),
(80, '2026_06_30_130515_update_shipment_table', 2),
(81, '2026_06_30_145254_update_delivery_boys_table', 2),
(82, '2026_07_03_070415_update_ticket_messages_table', 2),
(83, '2026_07_04_064519_create_settings_table', 2),
(84, '2026_07_06_103130_update_purchase_returns_table', 2),
(85, '2026_07_06_103206_update_purchase_return_items_table', 2),
(86, '2026_07_11_101649_create_purchase_return_payments_table', 2),
(87, '2026_07_18_052157_update_contact_messages_table', 2),
(89, '2026_07_19_132832_add_referral_by_to_customers_table', 3),
(90, '2026_07_19_134320_update_settings_table', 4),
(91, '2026_07_19_152551_update_employee_add_gender_table', 5),
(92, '2026_07_21_104238_add_more_fields_to_employee_table', 6),
(93, '2026_07_21_112251_update_settings_add_grace_time', 7),
(94, '2026_07_21_112251_update_settings_add_grace_times', 8),
(95, '2026_07_26_153324_create_shifts_table', 9),
(96, '2026_07_28_190349_create_employee_documents_table', 10),
(97, '2026_07_30_113336_update_shift_attendance_table', 11),
(98, '2026_07_30_120803_create_holidays_table', 12),
(99, '2026_07_30_121427_create_employee_experiences_table', 13),
(100, '2026_07_30_122955_update_add_notice_period_employee', 13),
(101, '2026_07_30_123711_create_employee_resignations_table', 14),
(102, '2026_07_30_124409_create_employee_terminations_table', 15),
(103, '2026_07_30_125142_update_customer_add_referal_bounus_table', 16),
(104, '2026_07_30_130732_create_job_postings_table', 17),
(105, '2026_07_30_131838_create_job_applications_table', 18),
(106, '2026_07_30_132249_create_interviews_table', 19),
(107, '2026_07_30_132606_create_interview_feedback_table', 20),
(108, '2026_07_30_132743_create_job_offers_table', 21),
(110, '2026_07_30_133542_create_candidate_documents_table', 22),
(111, '2026_07_30_134448_create_delivery_attempts_table', 23),
(112, '2026_07_30_164951_update_job_applications_table', 24),
(113, '2026_07_31_012748_create_faqs_table', 24),
(114, '2026_07_31_012924_create_employee_testimonials_table', 24),
(115, '2026_07_31_160143_update_job_applications_add_photo_date_of_birth_father_name', 25),
(116, '2026_07_31_162843_create_purchase_orders_table', 26),
(117, '2026_07_31_163336_create_purchase_order_items_table', 26),
(122, '2026_07_31_170909_create_warranty_claims_table', 27),
(123, '2026_07_31_163526_create_warranties_table.php', 27),
(124, '2026_07_31_163526_create_warranties_table', 28),
(125, '2026_07_31_174136_create_supplier_quotations_table', 28),
(126, '2026_07_31_174149_create_supplier_quotation_items_table', 28),
(127, '2026_07_31_174944_create_supplier_quotation_attachments_table', 29),
(128, '2026_07_31_175432_create_purchase_requisitions_table', 30),
(129, '2026_07_31_175658_create_purchase_requisition_items_table', 30),
(130, '2026_07_31_175816_create_request_for_quotations_table', 30),
(131, '2026_07_31_180104_create_request_for_quotation_items_table', 31),
(132, '2026_07_31_180922_create_goods_received_notes_table', 32),
(133, '2026_07_31_182100_create_goods_received_note_items_table', 33),
(134, '2026_08_01_234635_update_supplier_table', 34),
(135, '2026_08_02_003208_create_candidate_education_table', 34),
(136, '2026_08_02_003801_create_candidate_portfolios_table', 34),
(137, '2026_08_02_004556_create_employee_education_table', 34),
(138, '2026_08_02_004815_update_job_application_add_biography', 35),
(139, '2026_08_02_010319_create_candidate_work_experiences_table', 36),
(140, '2026_08_02_010702_update_interview_table', 37);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `address` text NOT NULL,
  `order_status` enum('pending','confirmed','processing','shipped','delivered','cancelled','returned') NOT NULL DEFAULT 'pending',
  `order_date` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `coupon_code` varchar(255) DEFAULT NULL,
  `cancellation_reason` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Karachi'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `sale_id`, `address`, `order_status`, `order_date`, `created_at`, `updated_at`, `coupon_code`, `cancellation_reason`, `notes`, `city`) VALUES
(2, 2, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-19', '2026-07-19 15:55:07', '2026-07-19 15:55:07', NULL, NULL, NULL, 'Karachi'),
(3, 3, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-19', '2026-07-19 16:17:09', '2026-07-19 16:17:09', NULL, NULL, NULL, 'Karachi'),
(4, 4, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-20', '2026-07-20 12:45:35', '2026-07-20 12:45:35', NULL, NULL, NULL, 'Karachi'),
(5, 5, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-20', '2026-07-20 15:02:18', '2026-07-20 15:02:18', NULL, NULL, NULL, 'Karachi'),
(6, 6, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-21', '2026-07-21 05:24:35', '2026-07-21 05:24:35', NULL, NULL, NULL, 'Karachi'),
(7, 7, 'D.1 Islamic arcade main university road near samama', 'pending', '2026-07-24', '2026-07-24 13:03:36', '2026-07-24 13:04:27', NULL, 'I dont need the product', NULL, 'Karachi'),
(8, 8, 'Gulshan  Karachi Sindh Karachi', 'pending', '2026-07-24', '2026-07-24 13:09:25', '2026-07-24 13:09:25', NULL, NULL, NULL, 'Karachi'),
(9, 9, 'Gulshan  Karachi Sindh Karachi', 'pending', '2026-07-25', '2026-07-25 11:34:38', '2026-07-25 11:34:38', 'SUMMER10', NULL, NULL, 'Karachi');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `status` enum('pending','paid','failed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment_methods`
--

CREATE TABLE `payment_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `gateway` enum('stripe','paypal','jazzcash','easypaisa','card') NOT NULL,
  `payment_token` varchar(255) NOT NULL,
  `card_brand` varchar(30) DEFAULT NULL,
  `last_four` varchar(4) DEFAULT NULL,
  `expiry_month` tinyint(3) UNSIGNED DEFAULT NULL,
  `expiry_year` smallint(5) UNSIGNED DEFAULT NULL,
  `card_holder_name` varchar(255) DEFAULT NULL,
  `is_default` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `month` varchar(255) NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `allowances` decimal(12,2) NOT NULL DEFAULT 0.00,
  `bonus` decimal(12,2) NOT NULL DEFAULT 0.00,
  `overtime` decimal(12,2) NOT NULL DEFAULT 0.00,
  `deductions` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `net_salary` decimal(12,2) NOT NULL,
  `status` enum('pending','paid') NOT NULL DEFAULT 'pending',
  `paid_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payrolls`
--

INSERT INTO `payrolls` (`id`, `employee_id`, `month`, `basic_salary`, `allowances`, `bonus`, `overtime`, `deductions`, `tax`, `net_salary`, `status`, `paid_date`, `created_at`, `updated_at`) VALUES
(9, 1, '2026-07', 80000.00, 1000.00, 0.00, 0.00, 6000.00, 8000.00, 67000.00, 'paid', '2026-07-26', '2026-07-19 09:52:16', '2026-07-26 15:32:39'),
(10, 1, '2026-06', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'paid', '2026-07-26', '2026-07-19 09:53:19', '2026-07-26 16:36:11'),
(11, 1, '2026-05', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'pending', NULL, '2026-07-19 09:53:45', '2026-07-19 09:53:45'),
(12, 1, '2026-04', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'pending', NULL, '2026-07-19 09:54:20', '2026-07-19 09:54:20'),
(13, 1, '2026-03', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'pending', NULL, '2026-07-19 09:54:41', '2026-07-19 09:54:41'),
(14, 1, '2026-02', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'pending', NULL, '2026-07-19 09:55:20', '2026-07-19 09:55:20'),
(15, 1, '2026-01', 80000.00, 1000.00, 0.00, 0.00, 0.00, 8000.00, 73000.00, 'pending', NULL, '2026-07-19 09:55:54', '2026-07-19 09:55:54'),
(16, 2, '2026-01', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:56:13', '2026-07-19 16:56:13'),
(17, 2, '2026-02', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:56:26', '2026-07-19 16:56:26'),
(18, 2, '2026-03', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:56:53', '2026-07-19 16:56:53'),
(19, 2, '2026-04', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:57:38', '2026-07-19 16:57:38'),
(20, 2, '2026-05', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:57:52', '2026-07-19 16:57:52'),
(21, 2, '2026-06', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-19 16:58:22', '2026-07-19 16:58:22'),
(22, 2, '2026-07', 70000.00, 0.00, 0.00, 0.00, 10800.00, 7000.00, 52200.00, 'pending', NULL, '2026-07-19 16:58:38', '2026-07-19 16:58:38'),
(24, 4, '2026-07', 70000.00, 0.00, 0.00, 0.00, 0.00, 7000.00, 63000.00, 'pending', NULL, '2026-07-25 17:41:26', '2026-07-25 17:41:26'),
(25, 3, '2026-07', 45000.00, 0.00, 0.00, 0.00, 0.00, 4500.00, 40500.00, 'pending', NULL, '2026-07-25 17:41:40', '2026-07-25 17:41:40'),
(26, 6, '2026-07', 67000.00, 0.00, 0.00, 0.00, 1000.00, 6700.00, 59300.00, 'paid', '2026-07-27', '2026-07-27 11:04:00', '2026-07-27 11:05:35'),
(27, 5, '2026-06', 100000.00, 0.00, 0.00, 0.00, 0.00, 10000.00, 90000.00, 'pending', NULL, '2026-07-27 11:47:53', '2026-07-27 11:47:53'),
(28, 5, '2026-05', 100000.00, 0.00, 0.00, 0.00, 0.00, 10000.00, 90000.00, 'pending', NULL, '2026-07-27 11:48:22', '2026-07-27 11:48:49'),
(29, 6, '2026-06', 67000.00, 0.00, 0.00, 0.00, 0.00, 6700.00, 60300.00, 'pending', NULL, '2026-07-27 11:49:53', '2026-07-27 11:49:53'),
(30, 6, '2026-07', 67000.00, 0.00, 37000.00, 1000.00, 0.00, 6700.00, 98300.00, 'pending', NULL, '2026-07-27 11:51:10', '2026-07-27 11:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `purchase_price` decimal(12,2) NOT NULL,
  `selling_price` decimal(12,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) NOT NULL DEFAULT 5,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `brand_id` bigint(20) UNSIGNED NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `warehouse_id`, `supplier_id`, `category_id`, `name`, `sku`, `purchase_price`, `selling_price`, `quantity`, `minimum_stock`, `description`, `image`, `status`, `created_at`, `updated_at`, `brand_id`, `discount`) VALUES
(1, 1, 2, 1, 'HP Spectre x360 14', 'hp-spectre-x360-14', 230000.00, 270000.00, 8, 5, 'Ram :8gb', 'products/hp-spectre-x360-14-1784475407.jpg', '1', '2026-07-19 15:36:47', '2026-07-20 12:54:35', 1, 5.00),
(2, 1, 2, 1, 'Dell XPS 13 Plus', 'dell-xps-13-plus', 250000.00, 300000.00, 7, 5, 'Ram:16gb', 'products/dell-xps-13-plus-1784475711.jpg', '1', '2026-07-19 15:41:51', '2026-07-20 12:50:39', 2, 10.00),
(3, 1, 1, 3, 'Haier Smart Tv ', 'haier-smart-tv', 50000.00, 75000.00, 8, 5, '', 'products/haier-smart-tv-1784477499.jpg', '1', '2026-07-19 16:11:39', '2026-07-24 13:19:10', 4, 10.00),
(4, 1, 4, 2, 'Tecno Spark', 'tecno-spark', 40000.00, 55000.00, 7, 5, '', 'products/tecno-spark-1784477742.jpg', '1', '2026-07-19 16:15:42', '2026-07-25 11:34:38', 7, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_primary` tinyint(1) NOT NULL DEFAULT 0,
  `sort_order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchases`
--

CREATE TABLE `purchases` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_no` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) DEFAULT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `paid_amount` decimal(12,2) NOT NULL,
  `due_amount` decimal(12,2) NOT NULL,
  `notes` text NOT NULL,
  `purchase_date` date NOT NULL,
  `status` varchar(255) NOT NULL,
  `payment_status` varchar(255) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchases`
--

INSERT INTO `purchases` (`id`, `supplier_id`, `purchase_no`, `subtotal`, `discount`, `tax`, `total_amount`, `paid_amount`, `due_amount`, `notes`, `purchase_date`, `status`, `payment_status`, `created_at`, `updated_at`) VALUES
(1, 2, 'PUR-20260719-0001', NULL, 0.00, 0.00, 2300000.00, 2300000.00, 0.00, 'Item Purchased', '2026-07-19', 'completed', 'paid', '2026-07-19 15:36:47', '2026-07-19 15:36:47'),
(2, 2, 'PUR-20260719-0002', NULL, 0.00, 0.00, 2500000.00, 2500000.00, 0.00, 'Item Purchased', '2026-07-19', 'completed', 'paid', '2026-07-19 15:41:51', '2026-07-19 15:41:51'),
(3, 1, 'PUR-20260719-0003', NULL, 0.00, 0.00, 500000.00, 500000.00, 0.00, 'Item Purchased', '2026-07-19', 'completed', 'paid', '2026-07-19 16:11:39', '2026-07-19 16:11:39'),
(4, 4, 'PUR-20260719-0004', NULL, 0.00, 0.00, 400000.00, 400000.00, 0.00, 'Item Purchased', '2026-07-19', 'completed', 'paid', '2026-07-19 16:15:42', '2026-07-19 16:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_items`
--

CREATE TABLE `purchase_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `purchase_price` decimal(12,2) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_items`
--

INSERT INTO `purchase_items` (`id`, `purchase_id`, `product_id`, `quantity`, `purchase_price`, `subtotal`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 10, 230000.00, 2300000.00, '2026-07-19 15:36:47', '2026-07-19 15:36:47'),
(2, 2, 2, 10, 250000.00, 2500000.00, '2026-07-19 15:41:51', '2026-07-19 15:41:51'),
(3, 3, 3, 10, 50000.00, 500000.00, '2026-07-19 16:11:39', '2026-07-19 16:11:39'),
(4, 4, 4, 10, 40000.00, 400000.00, '2026-07-19 16:15:42', '2026-07-19 16:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_orders`
--

CREATE TABLE `purchase_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `po_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `order_date` date NOT NULL,
  `expected_delivery_date` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','pending_approval','approved','sent','partially_received','completed','cancelled') NOT NULL DEFAULT 'draft',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_order_items`
--

CREATE TABLE `purchase_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `received_quantity` decimal(12,2) NOT NULL DEFAULT 0.00,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisitions`
--

CREATE TABLE `purchase_requisitions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `requisition_number` varchar(255) NOT NULL,
  `department_id` bigint(20) UNSIGNED DEFAULT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `requested_by` bigint(20) UNSIGNED NOT NULL,
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `request_date` date NOT NULL,
  `required_date` date DEFAULT NULL,
  `priority` enum('low','medium','high','urgent') NOT NULL DEFAULT 'medium',
  `status` enum('draft','pending','approved','rejected','cancelled','converted') NOT NULL DEFAULT 'draft',
  `purpose` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_requisition_items`
--

CREATE TABLE `purchase_requisition_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_returns`
--

CREATE TABLE `purchase_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED DEFAULT NULL,
  `purchase_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','declined') NOT NULL,
  `return_no` varchar(255) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_returns`
--

INSERT INTO `purchase_returns` (`id`, `warehouse_id`, `purchase_id`, `status`, `return_no`, `total_amount`, `reason`, `created_at`, `updated_at`, `supplier_id`) VALUES
(1, 1, 2, 'approved', 'PRET-20260719-000001', 250000.00, 'Not Need Now', '2026-07-19 16:05:43', '2026-07-19 16:07:43', 2),
(2, 1, 2, 'approved', 'PRET-20260720-000002', 250000.00, 'Defected Piece', '2026-07-20 12:49:46', '2026-07-20 12:50:39', 2),
(3, 1, 1, 'approved', 'PRET-20260720-000003', 230000.00, 'Defected Piece', '2026-07-20 12:53:00', '2026-07-20 12:54:35', 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_items`
--

CREATE TABLE `purchase_return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `purchase_item_id` varchar(255) DEFAULT NULL,
  `unit_price` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_return_items`
--

INSERT INTO `purchase_return_items` (`id`, `purchase_return_id`, `product_id`, `quantity`, `amount`, `created_at`, `updated_at`, `purchase_item_id`, `unit_price`) VALUES
(1, 1, 2, 1, 250000.00, '2026-07-19 16:05:43', '2026-07-19 16:05:43', '2', '250000'),
(2, 2, 2, 1, 250000.00, '2026-07-20 12:49:46', '2026-07-20 12:49:46', '2', '250000'),
(3, 3, 1, 1, 230000.00, '2026-07-20 12:53:00', '2026-07-20 12:53:00', '1', '230000');

-- --------------------------------------------------------

--
-- Table structure for table `purchase_return_payments`
--

CREATE TABLE `purchase_return_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `purchase_return_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` enum('approved','pending') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `purchase_return_payments`
--

INSERT INTO `purchase_return_payments` (`id`, `purchase_return_id`, `supplier_id`, `amount`, `status`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 250000.00, 'approved', '2026-07-19 16:07:43', '2026-07-19 16:07:43'),
(3, 2, 2, 250000.00, 'approved', '2026-07-20 12:50:39', '2026-07-20 12:50:39'),
(4, 3, 2, 230000.00, 'approved', '2026-07-20 12:54:35', '2026-07-20 12:54:35');

-- --------------------------------------------------------

--
-- Table structure for table `request_for_quotations`
--

CREATE TABLE `request_for_quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `rfq_number` varchar(255) NOT NULL,
  `purchase_requisition_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `issue_date` date NOT NULL,
  `closing_date` date NOT NULL,
  `status` enum('draft','published','closed','cancelled','completed') NOT NULL DEFAULT 'draft',
  `terms_and_conditions` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `request_for_quotation_items`
--

CREATE TABLE `request_for_quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `request_for_quotation_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit` varchar(50) DEFAULT NULL,
  `specification` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `review` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `is_approved` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `customer_id`, `rating`, `review`, `status`, `is_approved`, `created_at`, `updated_at`, `sale_id`) VALUES
(1, 2, 1, 5, 'Excellent ', 'pending', 0, '2026-07-19 16:02:07', '2026-07-19 16:02:07', 2);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `created_at`, `updated_at`) VALUES
(1, 'Admin', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(2, 'Customer', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(3, 'Employee', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(4, 'Manager', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(5, 'Supplier', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(6, 'WarehouseManager', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(7, 'SalesManager', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(8, 'HRManager', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(9, 'Accountant', '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(10, 'DeliveryBoy', '2026-07-18 09:13:25', '2026-07-18 09:13:25');

-- --------------------------------------------------------

--
-- Table structure for table `salaries`
--

CREATE TABLE `salaries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `basic_salary` decimal(12,2) NOT NULL,
  `allowance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `effective_from` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tax_deduction` varchar(255) NOT NULL,
  `net_salary` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salaries`
--

INSERT INTO `salaries` (`id`, `employee_id`, `basic_salary`, `allowance`, `effective_from`, `is_active`, `created_at`, `updated_at`, `tax_deduction`, `net_salary`) VALUES
(1, 1, 80000.00, 1000.00, '2021-01-08', 1, '2026-07-18 12:24:40', '2026-07-18 12:27:45', '8000', '73000'),
(2, 2, 70000.00, 0.00, '2025-11-07', 1, '2026-07-19 16:37:32', '2026-07-19 16:37:32', '7000', '63000'),
(3, 3, 45000.00, 0.00, '2025-01-27', 1, '2026-07-21 07:21:07', '2026-07-21 07:21:07', '4500', '40500'),
(4, 4, 70000.00, 0.00, '2025-12-28', 1, '2026-07-21 07:43:59', '2026-07-21 07:43:59', '7000', '63000'),
(5, 5, 100000.00, 0.00, '2025-08-05', 1, '2026-07-27 09:14:43', '2026-07-27 09:14:43', '10000', '90000'),
(6, 6, 67000.00, 0.00, '2024-06-12', 1, '2026-07-27 10:27:19', '2026-07-27 10:27:19', '6700', '60300');

-- --------------------------------------------------------

--
-- Table structure for table `salary_payments`
--

CREATE TABLE `salary_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `employee_id` bigint(20) UNSIGNED NOT NULL,
  `salary_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payroll_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','bank','cheque','easypaisa','jazzcash') NOT NULL DEFAULT 'cash',
  `transaction_id` varchar(255) DEFAULT NULL,
  `paid_date` date DEFAULT NULL,
  `status` enum('pending','paid','cancelled') NOT NULL DEFAULT 'paid',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `salary_payments`
--

INSERT INTO `salary_payments` (`id`, `employee_id`, `salary_id`, `payroll_id`, `amount`, `payment_method`, `transaction_id`, `paid_date`, `status`, `notes`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 9, 67000.00, 'cash', NULL, '2026-07-26', 'paid', NULL, '2026-07-19 09:52:16', '2026-07-26 15:32:39'),
(6, 1, 1, 10, 73000.00, 'cash', NULL, '2026-07-26', 'paid', NULL, '2026-07-19 09:53:19', '2026-07-26 16:36:11'),
(7, 1, 1, 11, 73000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 09:53:45', '2026-07-19 09:53:45'),
(8, 1, 1, 12, 73000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 09:54:20', '2026-07-19 09:54:20'),
(9, 1, 1, 13, 73000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 09:54:41', '2026-07-19 09:54:41'),
(10, 1, 1, 14, 73000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 09:55:20', '2026-07-19 09:55:20'),
(11, 1, 1, 15, 73000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 09:55:54', '2026-07-19 09:55:54'),
(12, 2, 2, 16, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:56:13', '2026-07-19 16:56:13'),
(13, 2, 2, 17, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:56:26', '2026-07-19 16:56:26'),
(14, 2, 2, 18, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:56:53', '2026-07-19 16:56:53'),
(15, 2, 2, 19, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:57:38', '2026-07-19 16:57:38'),
(16, 2, 2, 20, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:57:52', '2026-07-19 16:57:52'),
(17, 2, 2, 21, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:58:22', '2026-07-19 16:58:22'),
(18, 2, 2, 22, 52200.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-19 16:58:38', '2026-07-19 16:58:38'),
(20, 4, 4, 24, 63000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-25 17:41:26', '2026-07-25 17:41:26'),
(21, 3, 3, 25, 40500.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-25 17:41:40', '2026-07-25 17:41:40'),
(22, 6, 6, 26, 59300.00, 'cash', NULL, '2026-07-27', 'paid', NULL, '2026-07-27 11:04:00', '2026-07-27 11:05:35'),
(23, 5, 5, 27, 90000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-27 11:47:53', '2026-07-27 11:47:53'),
(24, 5, 5, 28, 90000.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-27 11:48:22', '2026-07-27 11:48:49'),
(25, 6, 6, 29, 60300.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-27 11:49:53', '2026-07-27 11:49:53'),
(26, 6, 6, 30, 98300.00, 'cash', NULL, NULL, 'pending', NULL, '2026-07-27 11:51:10', '2026-07-27 11:51:10');

-- --------------------------------------------------------

--
-- Table structure for table `sales`
--

CREATE TABLE `sales` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `invoice_no` varchar(255) NOT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_cost` varchar(255) NOT NULL DEFAULT '120',
  `payment_method` enum('cash','card','bank_transfer','jazzcash','easypaisa') NOT NULL DEFAULT 'cash',
  `payment_status` enum('pending','partial','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `due_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sale_type` enum('pos','online') NOT NULL DEFAULT 'pos',
  `cashier_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales`
--

INSERT INTO `sales` (`id`, `customer_id`, `invoice_no`, `subtotal`, `discount`, `tax`, `total_amount`, `created_at`, `updated_at`, `shipping_cost`, `payment_method`, `payment_status`, `paid_amount`, `due_amount`, `sale_type`, `cashier_id`) VALUES
(2, 1, 'SALE-20260719-20530001', 526500.00, 0.00, 94770.00, 622270.00, '2026-07-19 15:55:07', '2026-07-19 16:01:17', '1000', 'cash', 'paid', 622270.00, 0.00, 'online', NULL),
(3, 1, 'SALE-20260719-21160003', 122500.00, 0.00, 22050.00, 145550.00, '2026-07-19 16:17:09', '2026-07-19 16:17:09', '1000', 'cash', 'pending', 0.00, 145550.00, 'online', NULL),
(4, 1, 'SALE-20260720-17450004', 256500.00, 0.00, 46170.00, 303670.00, '2026-07-20 12:45:34', '2026-07-20 12:45:34', '1000', 'cash', 'pending', 0.00, 303670.00, 'online', NULL),
(5, 1, 'SALE-20260720-20010005', 67500.00, 0.00, 12150.00, 80650.00, '2026-07-20 15:02:18', '2026-07-20 15:02:18', '1000', 'cash', 'pending', 0.00, 80650.00, 'online', NULL),
(6, 2, 'SALE-20260721-10240006', 55000.00, 0.00, 9900.00, 65900.00, '2026-07-21 05:24:35', '2026-07-21 05:24:35', '1000', 'cash', 'pending', 0.00, 65900.00, 'online', NULL),
(7, 2, 'SALE-20260724-18030007', 110000.00, 0.00, 19800.00, 130800.00, '2026-07-24 13:03:36', '2026-07-24 13:04:27', '1000', 'cash', 'refunded', 0.00, 130800.00, 'online', NULL),
(8, 2, 'SALE-20260724-18070008', 67500.00, 0.00, 12150.00, 80650.00, '2026-07-24 13:09:25', '2026-07-24 13:18:21', '1000', 'card', 'paid', 80650.00, 0.00, 'online', NULL),
(9, 2, 'SALE-20260725-16330009', 55000.00, 5500.00, 8910.00, 59410.00, '2026-07-25 11:34:38', '2026-07-25 11:34:38', '1000', 'cash', 'pending', 0.00, 59410.00, 'online', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sales_returns`
--

CREATE TABLE `sales_returns` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `return_no` varchar(255) NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` enum('pending','approved','declined') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_returns`
--

INSERT INTO `sales_returns` (`id`, `sale_id`, `return_no`, `total_amount`, `reason`, `created_at`, `updated_at`, `status`) VALUES
(1, 2, 'RET-20260719210349-QLUSV', 256500.00, 'Damaged Product', '2026-07-19 16:03:49', '2026-07-19 16:04:08', 'approved'),
(2, 8, 'RET-20260724181851-RZGQY', 67500.00, 'Damaged Product', '2026-07-24 13:18:51', '2026-07-24 13:19:10', 'approved');

-- --------------------------------------------------------

--
-- Table structure for table `sales_return_items`
--

CREATE TABLE `sales_return_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sales_return_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `unit_price` varchar(255) NOT NULL DEFAULT '0',
  `total_price` varchar(255) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sales_return_items`
--

INSERT INTO `sales_return_items` (`id`, `sales_return_id`, `product_id`, `quantity`, `created_at`, `updated_at`, `unit_price`, `total_price`) VALUES
(1, 1, 1, 1, '2026-07-19 16:03:49', '2026-07-19 16:03:49', '256500.00', '256500'),
(2, 2, 3, 1, '2026-07-24 13:18:51', '2026-07-24 13:18:51', '67500.00', '67500');

-- --------------------------------------------------------

--
-- Table structure for table `sale_items`
--

CREATE TABLE `sale_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sale_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sale_items`
--

INSERT INTO `sale_items` (`id`, `sale_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(3, 2, 2, 1, 270000.00, 270000.00, '2026-07-19 15:55:07', '2026-07-19 15:55:07'),
(4, 2, 1, 1, 256500.00, 256500.00, '2026-07-19 15:55:07', '2026-07-19 15:55:07'),
(5, 3, 4, 1, 55000.00, 55000.00, '2026-07-19 16:17:09', '2026-07-19 16:17:09'),
(6, 3, 3, 1, 67500.00, 67500.00, '2026-07-19 16:17:09', '2026-07-19 16:17:09'),
(7, 4, 1, 1, 256500.00, 256500.00, '2026-07-20 12:45:35', '2026-07-20 12:45:35'),
(8, 5, 3, 1, 67500.00, 67500.00, '2026-07-20 15:02:18', '2026-07-20 15:02:18'),
(9, 6, 4, 1, 55000.00, 55000.00, '2026-07-21 05:24:35', '2026-07-21 05:24:35'),
(10, 7, 4, 2, 55000.00, 110000.00, '2026-07-24 13:03:36', '2026-07-24 13:03:36'),
(11, 8, 3, 1, 67500.00, 67500.00, '2026-07-24 13:09:25', '2026-07-24 13:09:25'),
(12, 9, 4, 1, 55000.00, 55000.00, '2026-07-25 11:34:38', '2026-07-25 11:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `max_cash_order_amount` varchar(255) NOT NULL,
  `cancellation_penalty` varchar(255) NOT NULL,
  `cancellation_window` varchar(255) NOT NULL,
  `late_penalty` decimal(8,2) NOT NULL,
  `referral_bonus` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `grace_time` tinyint(3) UNSIGNED NOT NULL DEFAULT 15
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `max_cash_order_amount`, `cancellation_penalty`, `cancellation_window`, `late_penalty`, `referral_bonus`, `created_at`, `updated_at`, `grace_time`) VALUES
(1, '500000', '1000', '30', 1000.00, 50.00, NULL, NULL, 20);

-- --------------------------------------------------------

--
-- Table structure for table `shifts`
--

CREATE TABLE `shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL DEFAULT 'morning',
  `reporting_time` time NOT NULL,
  `end_time` time NOT NULL,
  `grace_time` decimal(8,2) NOT NULL,
  `duration` decimal(8,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shifts`
--

INSERT INTO `shifts` (`id`, `name`, `reporting_time`, `end_time`, `grace_time`, `duration`, `created_at`, `updated_at`) VALUES
(1, 'Morning', '11:00:00', '00:00:00', 0.00, 0.00, '2026-07-29 08:34:43', '2026-07-29 08:50:24'),
(2, 'Evening', '18:35:00', '00:00:00', 0.00, 0.00, '2026-07-29 08:35:56', '2026-07-29 08:35:56'),
(3, 'Night ', '00:30:00', '00:00:00', 0.00, 0.00, '2026-07-29 08:36:32', '2026-07-29 08:36:32');

-- --------------------------------------------------------

--
-- Table structure for table `shipments`
--

CREATE TABLE `shipments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `shipping_method_id` bigint(20) UNSIGNED DEFAULT NULL,
  `tracking_number` varchar(255) NOT NULL,
  `status` enum('pending','packed','shipped','in_transit','out_for_delivery','delivered','returned','cancelled') NOT NULL DEFAULT 'pending',
  `shipped_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `packed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expected_delivery` datetime DEFAULT NULL,
  `cancelled_at` datetime DEFAULT NULL,
  `dispatch_by` bigint(20) UNSIGNED DEFAULT NULL,
  `canceled_by` bigint(20) UNSIGNED DEFAULT NULL,
  `notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipments`
--

INSERT INTO `shipments` (`id`, `order_id`, `shipping_method_id`, `tracking_number`, `status`, `shipped_at`, `delivered_at`, `packed_at`, `created_at`, `updated_at`, `expected_delivery`, `cancelled_at`, `dispatch_by`, `canceled_by`, `notes`) VALUES
(2, 2, 1, 'TRK-20260719-RTVW6HBO', 'delivered', '2026-07-19 16:00:54', '2026-07-19 16:01:17', '2026-07-19 21:00:42', '2026-07-19 15:55:07', '2026-07-19 16:01:17', '2026-07-22 21:00:54', NULL, 1, NULL, NULL),
(3, 3, 1, 'TRK-20260719-R5XRB7ZE', 'pending', NULL, NULL, NULL, '2026-07-19 16:17:09', '2026-07-19 16:17:09', NULL, NULL, NULL, NULL, NULL),
(4, 4, 1, 'TRK-20260720-J3JX7DZ6', 'pending', NULL, NULL, NULL, '2026-07-20 12:45:35', '2026-07-20 12:45:35', NULL, NULL, NULL, NULL, NULL),
(5, 5, 1, 'TRK-20260720-SVEQZBID', 'pending', NULL, NULL, NULL, '2026-07-20 15:02:18', '2026-07-20 15:02:18', NULL, NULL, NULL, NULL, NULL),
(6, 6, 1, 'TRK-20260721-QTEN05EJ', 'pending', NULL, NULL, NULL, '2026-07-21 05:24:35', '2026-07-21 05:24:35', NULL, NULL, NULL, NULL, NULL),
(7, 7, 1, 'TRK-20260724-NFZKRACD', 'cancelled', NULL, NULL, NULL, '2026-07-24 13:03:36', '2026-07-24 13:04:27', NULL, NULL, NULL, NULL, NULL),
(8, 8, 1, 'TRK-20260724-SRF3R2VI', 'delivered', NULL, '2026-07-24 13:18:21', NULL, '2026-07-24 13:09:25', '2026-07-24 13:18:21', NULL, NULL, NULL, NULL, NULL),
(9, 9, 1, 'TRK-20260725-SURVN7IX', 'pending', NULL, NULL, NULL, '2026-07-25 11:34:38', '2026-07-25 11:34:38', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `shipment_trackings`
--

CREATE TABLE `shipment_trackings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `shipment_id` bigint(20) UNSIGNED NOT NULL,
  `location` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `shipping_methods`
--

CREATE TABLE `shipping_methods` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `cost` decimal(12,2) NOT NULL DEFAULT 0.00,
  `estimated_days` int(11) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `region` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `shipping_category` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `shipping_methods`
--

INSERT INTO `shipping_methods` (`id`, `name`, `cost`, `estimated_days`, `description`, `region`, `is_active`, `created_at`, `updated_at`, `shipping_category`) VALUES
(1, 'Express', 1000.00, NULL, '', NULL, 1, '2026-07-19 15:53:18', '2026-07-19 15:53:18', 'Express');

-- --------------------------------------------------------

--
-- Table structure for table `stocks`
--

CREATE TABLE `stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `minimum_stock` int(11) NOT NULL DEFAULT 5,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `action` enum('purchase','sale','sale_return','purchase_return','adjustment','transfer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stocks`
--

INSERT INTO `stocks` (`id`, `product_id`, `warehouse_id`, `quantity`, `minimum_stock`, `created_at`, `updated_at`, `action`) VALUES
(1, 1, 1, 8, 5, '2026-07-19 15:36:47', '2026-07-20 12:54:35', 'purchase'),
(2, 2, 1, 7, 5, '2026-07-19 15:41:51', '2026-07-20 12:50:39', 'purchase'),
(3, 3, 1, 8, 5, '2026-07-19 16:11:39', '2026-07-24 13:19:10', 'purchase'),
(4, 4, 1, 7, 5, '2026-07-19 16:15:42', '2026-07-25 11:34:38', 'purchase');

-- --------------------------------------------------------

--
-- Table structure for table `stock_movements`
--

CREATE TABLE `stock_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('purchase_return','purchase','sale','return','adjustment','transfer_in','transfer_out') NOT NULL,
  `quantity` int(11) NOT NULL,
  `stock_before` int(11) NOT NULL,
  `stock_after` int(11) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stock_movements`
--

INSERT INTO `stock_movements` (`id`, `product_id`, `warehouse_id`, `supplier_id`, `type`, `quantity`, `stock_before`, `stock_after`, `reference_no`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, 'purchase', 10, 0, 10, 'PUR-20260719-0001', NULL, '2026-07-19 15:36:47', '2026-07-19 15:36:47'),
(2, 2, 1, 2, 'purchase', 10, 0, 10, 'PUR-20260719-0002', NULL, '2026-07-19 15:41:51', '2026-07-19 15:41:51'),
(5, 2, 1, 2, 'sale', 1, 10, 9, 'SALE-20260719-20530001', NULL, '2026-07-19 15:55:07', '2026-07-19 15:55:07'),
(6, 1, 1, 2, 'sale', 1, 10, 9, 'SALE-20260719-20530001', NULL, '2026-07-19 15:55:07', '2026-07-19 15:55:07'),
(7, 1, 1, 2, 'return', 1, 9, 10, NULL, NULL, '2026-07-19 16:04:08', '2026-07-19 16:04:08'),
(8, 2, 1, 2, 'purchase_return', 1, 9, 8, NULL, NULL, '2026-07-19 16:07:43', '2026-07-19 16:07:43'),
(9, 3, 1, 1, 'purchase', 10, 0, 10, 'PUR-20260719-0003', NULL, '2026-07-19 16:11:39', '2026-07-19 16:11:39'),
(10, 4, 1, 4, 'purchase', 10, 0, 10, 'PUR-20260719-0004', NULL, '2026-07-19 16:15:42', '2026-07-19 16:15:42'),
(11, 4, 1, 4, 'sale', 1, 10, 9, 'SALE-20260719-21160003', NULL, '2026-07-19 16:17:09', '2026-07-19 16:17:09'),
(12, 3, 1, 1, 'sale', 1, 10, 9, 'SALE-20260719-21160003', NULL, '2026-07-19 16:17:09', '2026-07-19 16:17:09'),
(13, 1, 1, 2, 'sale', 1, 10, 9, 'SALE-20260720-17450004', NULL, '2026-07-20 12:45:35', '2026-07-20 12:45:35'),
(14, 2, 1, 2, 'purchase_return', 1, 8, 7, NULL, NULL, '2026-07-20 12:50:39', '2026-07-20 12:50:39'),
(15, 1, 1, 2, 'purchase_return', 1, 9, 8, NULL, NULL, '2026-07-20 12:54:35', '2026-07-20 12:54:35'),
(16, 3, 1, 1, 'sale', 1, 9, 8, 'SALE-20260720-20010005', NULL, '2026-07-20 15:02:18', '2026-07-20 15:02:18'),
(17, 4, 1, 4, 'sale', 1, 9, 8, 'SALE-20260721-10240006', NULL, '2026-07-21 05:24:35', '2026-07-21 05:24:35'),
(18, 4, 1, 4, 'sale', 2, 8, 6, 'SALE-20260724-18030007', NULL, '2026-07-24 13:03:36', '2026-07-24 13:03:36'),
(19, 4, 1, 4, 'return', 2, 6, 8, 'ORD-CANCEL-7', 'Stock returned because order #7 was cancelled', '2026-07-24 13:04:27', '2026-07-24 13:04:27'),
(20, 3, 1, 1, 'sale', 1, 8, 7, 'SALE-20260724-18070008', NULL, '2026-07-24 13:09:25', '2026-07-24 13:09:25'),
(21, 3, 1, 1, 'return', 1, 7, 8, NULL, NULL, '2026-07-24 13:19:10', '2026-07-24 13:19:10'),
(22, 4, 1, 4, 'sale', 1, 8, 7, 'SALE-20260725-16330009', NULL, '2026-07-25 11:34:38', '2026-07-25 11:34:38');

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfers`
--

CREATE TABLE `stock_transfers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transfer_no` varchar(255) NOT NULL,
  `from_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `to_warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','approved','in_transit','completed','cancelled') NOT NULL DEFAULT 'pending',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stock_transfer_items`
--

CREATE TABLE `stock_transfer_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `stock_transfer_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `suppliers`
--

CREATE TABLE `suppliers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `credit_limit` decimal(12,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `suppliers`
--

INSERT INTO `suppliers` (`id`, `user_id`, `company_name`, `email`, `phone`, `address`, `opening_balance`, `status`, `created_at`, `updated_at`, `deleted_at`, `credit_limit`) VALUES
(1, 8, 'Haier Store', 'haier_store@gmail.com', '03421462082', 'Lahore ,Pakistan', 0.00, 1, '2026-07-19 14:59:22', '2026-07-19 14:59:22', NULL, 0.00),
(2, 9, 'Tech Store', 'tech_store@gmail.com', '03421462082', 'D.1 Islamic arcade main university road near samama', 0.00, 1, '2026-07-19 15:12:57', '2026-07-19 15:12:57', NULL, 0.00),
(3, 10, 'samsung Store', 'samsung_store@gmail.com', '03421462082', 'Karachi ,Pakistan', 0.00, 1, '2026-07-19 15:22:21', '2026-07-19 15:22:21', NULL, 0.00),
(4, 11, 'Mobile City ', 'mobile_city@gmail.com', '03421462082', 'Lahore,Pakistan', 0.00, 1, '2026-07-19 15:23:37', '2026-07-19 15:23:37', NULL, 0.00),
(5, 21, 'City Electronics ', 'city_electronics@gmail.com', '03421462082', 'D.1 Islamic arcade main university road near samama', 0.00, 1, '2026-07-24 13:13:56', '2026-07-24 13:13:56', NULL, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `supplier_payments`
--

CREATE TABLE `supplier_payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `purchase_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','cheque','card','wallet') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `supplier_payments`
--

INSERT INTO `supplier_payments` (`id`, `supplier_id`, `purchase_id`, `amount`, `payment_method`, `transaction_id`, `payment_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 2300000.00, 'card', 'TXN-6A5CEF0F506C8', '2026-07-19', 'Item Purchased Quantity: 10', '2026-07-19 15:36:47', '2026-07-19 15:36:47'),
(2, 2, 2, 2500000.00, 'card', 'TXN-6A5CF03FA0AC0', '2026-07-19', 'Item Purchased Quantity: 10', '2026-07-19 15:41:51', '2026-07-19 15:41:51'),
(3, 1, 3, 500000.00, 'card', 'TXN-6A5CF73B86FF0', '2026-07-19', 'Item Purchased Quantity: 10', '2026-07-19 16:11:39', '2026-07-19 16:11:39'),
(4, 4, 4, 400000.00, 'card', 'TXN-6A5CF82EE8AA6', '2026-07-19', 'Item Purchased Quantity: 10', '2026-07-19 16:15:42', '2026-07-19 16:15:42');

-- --------------------------------------------------------

--
-- Table structure for table `supplier_quotations`
--

CREATE TABLE `supplier_quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_number` varchar(255) NOT NULL,
  `supplier_id` bigint(20) UNSIGNED NOT NULL,
  `warehouse_id` bigint(20) UNSIGNED NOT NULL,
  `created_by` bigint(20) UNSIGNED NOT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `subtotal` decimal(15,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `shipping_cost` decimal(15,2) NOT NULL DEFAULT 0.00,
  `grand_total` decimal(15,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','received','accepted','rejected','expired','converted') NOT NULL DEFAULT 'draft',
  `terms` text DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_quotation_attachments`
--

CREATE TABLE `supplier_quotation_attachments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_quotation_id` bigint(20) UNSIGNED NOT NULL,
  `file` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `supplier_quotation_items`
--

CREATE TABLE `supplier_quotation_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `supplier_quotation_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` decimal(12,2) NOT NULL,
  `unit_price` decimal(15,2) NOT NULL,
  `discount` decimal(15,2) NOT NULL DEFAULT 0.00,
  `tax` decimal(15,2) NOT NULL DEFAULT 0.00,
  `total` decimal(15,2) NOT NULL,
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `taxes`
--

CREATE TABLE `taxes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `rate` decimal(5,2) NOT NULL,
  `type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `category` varchar(255) NOT NULL DEFAULT 'sales'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taxes`
--

INSERT INTO `taxes` (`id`, `name`, `rate`, `type`, `is_active`, `created_at`, `updated_at`, `category`) VALUES
(1, 'Gst', 18.00, 'percentage', 1, '2026-07-19 15:51:59', '2026-07-19 15:51:59', 'sales');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_support_ticket_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `message_by` enum('admin','customer') NOT NULL DEFAULT 'customer',
  `attachment` varchar(255) DEFAULT NULL,
  `is_internal` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_messages`
--

INSERT INTO `ticket_messages` (`id`, `customer_support_ticket_id`, `message`, `message_by`, `attachment`, `is_internal`, `created_at`, `updated_at`) VALUES
(44, 1, 'we componsate you', 'admin', NULL, 0, '2026-07-23 16:25:20', '2026-07-23 16:25:20'),
(45, 1, 'Ok  i will wait', 'customer', NULL, 0, '2026-07-23 16:25:37', '2026-07-23 16:25:37'),
(46, 1, 'thanks', 'admin', NULL, 0, '2026-07-23 16:25:48', '2026-07-23 16:25:48'),
(47, 1, 'one product is damaged ', 'customer', NULL, 0, '2026-07-23 16:27:52', '2026-07-23 16:27:52'),
(48, 1, 'show me the picture of the product', 'admin', NULL, 0, '2026-07-23 16:29:16', '2026-07-23 16:29:16'),
(49, 1, 'i will email you', 'customer', NULL, 0, '2026-07-23 16:33:06', '2026-07-23 16:33:06'),
(50, 1, 'i will process the refund after seeing picture', 'admin', NULL, 0, '2026-07-23 16:33:36', '2026-07-23 16:33:36'),
(51, 1, 'you have not given the image of damaged product through email', 'admin', NULL, 0, '2026-07-25 13:18:22', '2026-07-25 13:18:22'),
(52, 1, 'i will send you tomorrow', 'customer', NULL, 0, '2026-07-25 13:19:33', '2026-07-25 13:19:33'),
(53, 1, 'i  have attached the image', 'customer', NULL, 0, '2026-07-25 13:36:59', '2026-07-25 13:36:59'),
(55, 1, 'i have attached the image', 'customer', 'support-tickets/1784989307_QJF23EqKPo.jpg', 0, '2026-07-25 14:21:47', '2026-07-25 14:21:47'),
(56, 1, 'i have attached the image', 'customer', 'support-tickets/1784989606_jI6eRQpUP4.jpg', 0, '2026-07-25 14:26:46', '2026-07-25 14:26:46'),
(57, 1, 'i have upload the image', 'customer', 'support-tickets/1784989843_zhVBcVbQSq.jpg', 0, '2026-07-25 14:30:43', '2026-07-25 14:30:43'),
(58, 1, 'i have attached the image', 'customer', 'support-tickets/1784990440_Cjektwv6Ll.jpg', 0, '2026-07-25 14:40:40', '2026-07-25 14:40:40'),
(59, 1, 'i have attach the image look it is damaged', 'customer', 'support-tickets/1784990585_81aVdYW8HX.jpg', 0, '2026-07-25 14:43:05', '2026-07-25 14:43:05'),
(60, 1, 'i have attached the image', 'customer', 'support-tickets/1784990971_mCj1LTkpJo.jpg', 0, '2026-07-25 14:49:31', '2026-07-25 14:49:31'),
(61, 1, 'my infinix notepro is damaged', 'customer', 'support-tickets/1784991637_4V8Sj3b6Vq.jpg', 0, '2026-07-25 15:00:37', '2026-07-25 15:00:37'),
(62, 1, 'ok i will refund you', 'admin', NULL, 0, '2026-07-25 15:01:25', '2026-07-25 15:01:25'),
(63, 1, 'ok i will refund you', 'admin', NULL, 0, '2026-07-25 15:01:48', '2026-07-25 15:01:48'),
(64, 2, 'Product is missing', 'customer', NULL, 0, '2026-07-25 15:07:15', '2026-07-25 15:07:15'),
(65, 2, 'we will delivered you soon', 'admin', NULL, 0, '2026-07-25 15:08:03', '2026-07-25 15:08:03'),
(66, 2, 'i w', 'admin', NULL, 0, '2026-07-25 15:09:20', '2026-07-25 15:09:20');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `reference_no` varchar(255) NOT NULL,
  `type` enum('sale','purchase','payment','refund','cashback') NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `description` text DEFAULT NULL,
  `transaction_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 1, 'Admin', 'admin@gmail.com', NULL, '$2y$12$ZS7PMa/354XdElyCOsuXPOMYFJr71LrefJnOSYyusKtlHOMKn8jp.', NULL, '2026-07-18 09:13:25', '2026-07-18 09:13:25'),
(7, 3, 'Waqas ', 'waqas@gmail.com', NULL, '$2y$12$Vxi3b.Bqzt.LTx06JHTXq.x1wBRV.uGEbX9ZnyuGwLIfvbABsSIci', NULL, '2026-07-18 12:24:40', '2026-07-18 12:24:40'),
(8, 5, 'Haier Store', 'haier_store@gmail.com', NULL, '$2y$12$1OsFQXKKkegCQrP9w7lp6.azkjfxClfxtYICJ6y.0VNjP6Kol1HQK', NULL, '2026-07-19 14:59:22', '2026-07-19 14:59:22'),
(9, 5, 'Tech Store', 'tech_store@gmail.com', NULL, '$2y$12$JsezxIo9dOw419Rygp87kugh0EmRy9uE4Hg1Tfst1DtFX.uAekTJG', NULL, '2026-07-19 15:12:57', '2026-07-19 15:12:57'),
(10, 5, 'Samsung  Store ', 'samsung_store@gmail.com', NULL, '$2y$12$slcofDQPvlT.tvuqllRYLukBlknu13o/THK3xNuoxxR3pHSj6JCYu', NULL, '2026-07-19 15:22:21', '2026-07-19 15:22:21'),
(11, 5, 'Mobile City', 'mobile_city@gmail.com', NULL, '$2y$12$vg3.G0lHMEW7PXVjHi99Hebev.VybI0c3UbeIRNKBlvnW/MDoPYnW', NULL, '2026-07-19 15:23:37', '2026-07-19 15:23:37'),
(12, 2, 'Asad  Mukhtar', 'asad_mukhtar@gmail.com', NULL, '$2y$12$ph1uVQGaoPYawzfdhbsJve7leLjEF6t/Ba8RguwiyjeHHTp41ndtq', NULL, '2026-07-19 15:48:30', '2026-07-19 15:48:30'),
(13, 10, 'Waseem', 'waseem@gmail.com', NULL, '$2y$12$5/h3l1wqO69uEbQnHL3f/uxCm/hiMX6odbNh3C5mhSeRIEGooDkoG', NULL, '2026-07-19 15:59:58', '2026-07-19 15:59:58'),
(14, 3, 'Sajal', 'sajal@gmail.com', NULL, '$2y$12$bxYmooAPp7qwkb0BXXHWw.0PTUV.jQRN9DOmahMySukVxo19cALBq', NULL, '2026-07-19 16:37:32', '2026-07-19 16:37:32'),
(15, 2, 'Syed Abdul Samad Ahsan', 'abdulsamadahsan@gmail.com', NULL, '$2y$12$yHZBpf7e.g0BdA/HJV6GTud7CTcm3nOHvItrz9bDuFxCdBwOsGLUC', NULL, '2026-07-21 05:22:49', '2026-07-21 05:22:49'),
(19, 3, 'Sohail ', 'sohail_ahmed@gmail.com', NULL, '$2y$12$sjKPyNXOtFNMiVz/1/8N/eeU2HBq/8UTGVja6wJb5OP4bpG31pHaO', NULL, '2026-07-21 07:21:07', '2026-07-21 07:21:07'),
(20, 3, 'Ali Mirza', 'ali_mirza@gmail.com', NULL, '$2y$12$XV8y2xt9FzI59F21q9hC..vvwbtZCly3GESMjFgWBdZ9rSJr11gVG', NULL, '2026-07-21 07:43:59', '2026-07-21 07:43:59'),
(21, 5, 'City Electronics', 'city_electronics@gmail.com', NULL, '$2y$12$8v72lljInKkMBK85P7pG4uA/5tpx53cV4Okpi.XIWTOQGU1Q04/n.', NULL, '2026-07-24 13:13:56', '2026-07-24 13:13:56'),
(22, 3, 'Zoha', 'zoha@gmail.com', NULL, '$2y$12$0VUgViifkDRYqmCuBR9QLesoMXrymjQw37WMV3kcGMa1fE6HcVT1a', NULL, '2026-07-27 09:14:43', '2026-07-27 09:14:43'),
(24, 3, 'Areeba Noor', 'areeba_noor@gmail.com', NULL, '$2y$12$qZAnI6HnsKcRak02efwdHOinTZZD0o85AZjuRjtsPPoislr.drTQG', NULL, '2026-07-27 10:27:19', '2026-07-27 10:27:19');

-- --------------------------------------------------------

--
-- Table structure for table `wallets`
--

CREATE TABLE `wallets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallets`
--

INSERT INTO `wallets` (`id`, `customer_id`, `balance`, `created_at`, `updated_at`) VALUES
(1, 1, 256500.00, '2026-07-19 15:48:30', '2026-07-19 16:04:08'),
(2, 2, 67500.00, '2026-07-21 05:22:49', '2026-07-24 13:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_topup_requests`
--

CREATE TABLE `wallet_topup_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` enum('cash','bank_transfer','easypaisa','jazzcash','card') NOT NULL,
  `transaction_id` varchar(255) DEFAULT NULL,
  `card_holder_name` varchar(255) DEFAULT NULL,
  `card_number` varchar(255) DEFAULT NULL,
  `card_expiry` varchar(255) DEFAULT NULL,
  `mobile_account_name` varchar(255) DEFAULT NULL,
  `mobile_account_number` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `account_title` varchar(255) DEFAULT NULL,
  `account_number` varchar(255) DEFAULT NULL,
  `iban` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `approved_by` bigint(20) UNSIGNED DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_topup_requests`
--

INSERT INTO `wallet_topup_requests` (`id`, `customer_id`, `wallet_id`, `amount`, `payment_method`, `transaction_id`, `card_holder_name`, `card_number`, `card_expiry`, `mobile_account_name`, `mobile_account_number`, `bank_name`, `account_title`, `account_number`, `iban`, `notes`, `status`, `approved_by`, `approved_at`, `rejection_reason`, `created_at`, `updated_at`) VALUES
(1, 2, 2, 1000.00, 'easypaisa', '8803681784898325', NULL, NULL, NULL, 'Abdul Samad Ahsan', '03111234567', '', '', '', '', NULL, 'pending', NULL, NULL, NULL, '2026-07-24 13:05:25', '2026-07-24 13:05:25');

-- --------------------------------------------------------

--
-- Table structure for table `wallet_transactions`
--

CREATE TABLE `wallet_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL,
  `reference_type` varchar(255) DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wallet_transactions`
--

INSERT INTO `wallet_transactions` (`id`, `wallet_id`, `amount`, `type`, `reference_type`, `reference_id`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 256500.00, 'credit', NULL, 9211784477048, 'Amount Refunded', '2026-07-19 16:04:08', '2026-07-19 16:04:08'),
(2, 2, 67500.00, 'credit', NULL, 6681784899150, 'Amount Refunded', '2026-07-24 13:19:10', '2026-07-24 13:19:10');

-- --------------------------------------------------------

--
-- Table structure for table `warehouses`
--

CREATE TABLE `warehouses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `code` varchar(255) NOT NULL,
  `manager_id` bigint(20) UNSIGNED DEFAULT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `warehouses`
--

INSERT INTO `warehouses` (`id`, `name`, `code`, `manager_id`, `address`, `phone`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Gulshan', 'GUL-001', 1, 'D.1 Islamic arcade main university road near samama', '03421462082', 1, '2026-07-19 14:54:01', '2026-07-19 14:54:01');

-- --------------------------------------------------------

--
-- Table structure for table `warranties`
--

CREATE TABLE `warranties` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `warranty_type` enum('manufacturer','seller','extended') NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `duration_months` smallint(5) UNSIGNED NOT NULL,
  `status` enum('active','expired','void','claimed') NOT NULL DEFAULT 'active',
  `terms` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `warranty_claims`
--

CREATE TABLE `warranty_claims` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `warranty_id` bigint(20) UNSIGNED NOT NULL,
  `sale_item_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `received_by` bigint(20) UNSIGNED DEFAULT NULL,
  `claim_date` date NOT NULL,
  `issue_description` text NOT NULL,
  `resolution` enum('pending','repair','replace','refund','rejected') NOT NULL DEFAULT 'pending',
  `resolution_notes` text DEFAULT NULL,
  `resolved_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `wishlists`
--

CREATE TABLE `wishlists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `wishlists`
--

INSERT INTO `wishlists` (`id`, `customer_id`, `created_at`, `updated_at`) VALUES
(1, 1, '2026-07-19 16:16:15', '2026-07-19 16:16:15');

-- --------------------------------------------------------

--
-- Table structure for table `wishlist_items`
--

CREATE TABLE `wishlist_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wishlist_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `attendances`
--
ALTER TABLE `attendances`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attendances_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `candidate_documents`
--
ALTER TABLE `candidate_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_documents_verified_by_foreign` (`verified_by`),
  ADD KEY `candidate_documents_job_application_id_index` (`job_application_id`),
  ADD KEY `candidate_documents_document_type_index` (`document_type`),
  ADD KEY `candidate_documents_is_verified_index` (`is_verified`);

--
-- Indexes for table `candidate_education`
--
ALTER TABLE `candidate_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_education_job_application_id_foreign` (`job_application_id`);

--
-- Indexes for table `candidate_portfolios`
--
ALTER TABLE `candidate_portfolios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `candidate_portfolios_job_application_id_foreign` (`job_application_id`);

--
-- Indexes for table `candidate_work_experiences`
--
ALTER TABLE `candidate_work_experiences`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `carts`
--
ALTER TABLE `carts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `carts_user_id_foreign` (`user_id`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `cart_items_cart_id_foreign` (`cart_id`),
  ADD KEY `cart_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_name_unique` (`name`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customers_user_id_foreign` (`user_id`),
  ADD KEY `customers_referral_by_foreign` (`referral_by`);

--
-- Indexes for table `customer_support_tickets`
--
ALTER TABLE `customer_support_tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_support_tickets_ticket_no_unique` (`ticket_no`),
  ADD KEY `customer_support_tickets_customer_id_foreign` (`customer_id`),
  ADD KEY `customer_support_tickets_order_id_foreign` (`order_id`),
  ADD KEY `customer_support_tickets_assigned_to_foreign` (`assigned_to`);

--
-- Indexes for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_assignments_shipment_id_foreign` (`shipment_id`),
  ADD KEY `delivery_assignments_delivery_boy_id_foreign` (`delivery_boy_id`);

--
-- Indexes for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_attempts_shipment_id_attempt_number_unique` (`shipment_id`,`attempt_number`),
  ADD KEY `delivery_attempts_delivery_assignment_id_foreign` (`delivery_assignment_id`),
  ADD KEY `delivery_attempts_shipment_id_index` (`shipment_id`),
  ADD KEY `delivery_attempts_delivery_boy_id_index` (`delivery_boy_id`),
  ADD KEY `delivery_attempts_attempted_at_index` (`attempted_at`),
  ADD KEY `delivery_attempts_status_index` (`status`);

--
-- Indexes for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_boys_cnic_unique` (`cnic`),
  ADD KEY `delivery_boys_user_id_foreign` (`user_id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `departments_name_unique` (`name`);

--
-- Indexes for table `educations`
--
ALTER TABLE `educations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `educations_short_code_unique` (`short_code`),
  ADD KEY `educations_institution_id_foreign` (`institution_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employees_account_number_unique` (`account_number`),
  ADD UNIQUE KEY `employees_cnic_unique` (`cnic`),
  ADD KEY `employees_user_id_foreign` (`user_id`),
  ADD KEY `employees_department_id_foreign` (`department_id`),
  ADD KEY `employees_institution_id_foreign` (`institution_id`),
  ADD KEY `employees_education_id_foreign` (`education_id`);

--
-- Indexes for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_documents_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_education`
--
ALTER TABLE `employee_education`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_education_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_experiences`
--
ALTER TABLE `employee_experiences`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_experiences_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_resignations`
--
ALTER TABLE `employee_resignations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_resignations_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_terminations`
--
ALTER TABLE `employee_terminations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_terminations_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `employee_testimonials`
--
ALTER TABLE `employee_testimonials`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_testimonials_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_expense_category_id_foreign` (`expense_category_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `goods_received_notes_grn_number_unique` (`grn_number`),
  ADD KEY `goods_received_notes_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `goods_received_notes_supplier_id_foreign` (`supplier_id`),
  ADD KEY `goods_received_notes_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `goods_received_notes_received_by_foreign` (`received_by`);

--
-- Indexes for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `goods_received_note_items_goods_received_note_id_foreign` (`goods_received_note_id`),
  ADD KEY `goods_received_note_items_purchase_order_item_id_foreign` (`purchase_order_item_id`),
  ADD KEY `goods_received_note_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `institutions`
--
ALTER TABLE `institutions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interviews_job_application_id_foreign` (`job_application_id`),
  ADD KEY `interviews_interviewer_foreign` (`interviewer`);

--
-- Indexes for table `interview_feedback`
--
ALTER TABLE `interview_feedback`
  ADD PRIMARY KEY (`id`),
  ADD KEY `interview_feedback_interview_id_foreign` (`interview_id`);

--
-- Indexes for table `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD KEY `invoices_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_applications_job_posting_id_foreign` (`job_posting_id`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `job_offers`
--
ALTER TABLE `job_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_offers_job_application_id_foreign` (`job_application_id`);

--
-- Indexes for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_postings_department_id_foreign` (`department_id`),
  ADD KEY `job_postings_created_by_foreign` (`created_by`);

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`),
  ADD KEY `leaves_employee_id_foreign` (`employee_id`),
  ADD KEY `leaves_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `orders_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_purchase_id_foreign` (`purchase_id`),
  ADD KEY `payments_payment_method_id_foreign` (`payment_method_id`);

--
-- Indexes for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_methods_payment_token_unique` (`payment_token`),
  ADD KEY `payment_methods_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payrolls_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_supplier_id_foreign` (`supplier_id`),
  ADD KEY `products_category_id_foreign` (`category_id`),
  ADD KEY `products_brand_id_foreign` (`brand_id`),
  ADD KEY `products_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchases`
--
ALTER TABLE `purchases`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchases_purchase_no_unique` (`purchase_no`),
  ADD KEY `purchases_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_items_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_orders_po_number_unique` (`po_number`),
  ADD KEY `purchase_orders_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_orders_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_orders_created_by_foreign` (`created_by`),
  ADD KEY `purchase_orders_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_order_items_purchase_order_id_foreign` (`purchase_order_id`),
  ADD KEY `purchase_order_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_requisitions_requisition_number_unique` (`requisition_number`),
  ADD KEY `purchase_requisitions_department_id_foreign` (`department_id`),
  ADD KEY `purchase_requisitions_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `purchase_requisitions_requested_by_foreign` (`requested_by`),
  ADD KEY `purchase_requisitions_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_requisition_items_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  ADD KEY `purchase_requisition_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `purchase_returns_return_no_unique` (`return_no`),
  ADD KEY `purchase_returns_purchase_id_foreign` (`purchase_id`),
  ADD KEY `purchase_returns_supplier_id_foreign` (`supplier_id`),
  ADD KEY `purchase_returns_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_items_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `purchase_return_payments`
--
ALTER TABLE `purchase_return_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `purchase_return_payments_purchase_return_id_foreign` (`purchase_return_id`),
  ADD KEY `purchase_return_payments_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `request_for_quotations`
--
ALTER TABLE `request_for_quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `request_for_quotations_rfq_number_unique` (`rfq_number`),
  ADD KEY `request_for_quotations_purchase_requisition_id_foreign` (`purchase_requisition_id`),
  ADD KEY `request_for_quotations_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `request_for_quotations_created_by_foreign` (`created_by`);

--
-- Indexes for table `request_for_quotation_items`
--
ALTER TABLE `request_for_quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `request_for_quotation_items_request_for_quotation_id_foreign` (`request_for_quotation_id`),
  ADD KEY `request_for_quotation_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_customer_id_foreign` (`customer_id`),
  ADD KEY `reviews_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Indexes for table `salaries`
--
ALTER TABLE `salaries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salaries_employee_id_foreign` (`employee_id`);

--
-- Indexes for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `salary_payments_employee_id_foreign` (`employee_id`),
  ADD KEY `salary_payments_salary_id_foreign` (`salary_id`),
  ADD KEY `salary_payments_payroll_id_foreign` (`payroll_id`);

--
-- Indexes for table `sales`
--
ALTER TABLE `sales`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_invoice_no_unique` (`invoice_no`),
  ADD KEY `sales_customer_id_foreign` (`customer_id`),
  ADD KEY `sales_cashier_id_foreign` (`cashier_id`);

--
-- Indexes for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `sales_returns_return_no_unique` (`return_no`),
  ADD KEY `sales_returns_sale_id_foreign` (`sale_id`);

--
-- Indexes for table `sales_return_items`
--
ALTER TABLE `sales_return_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sales_return_items_sales_return_id_foreign` (`sales_return_id`),
  ADD KEY `sales_return_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sale_items_sale_id_foreign` (`sale_id`),
  ADD KEY `sale_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shifts`
--
ALTER TABLE `shifts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shipments`
--
ALTER TABLE `shipments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `shipments_tracking_number_unique` (`tracking_number`),
  ADD KEY `shipments_order_id_foreign` (`order_id`),
  ADD KEY `shipments_shipping_method_id_foreign` (`shipping_method_id`),
  ADD KEY `shipments_dispatch_by_foreign` (`dispatch_by`),
  ADD KEY `shipments_canceled_by_foreign` (`canceled_by`);

--
-- Indexes for table `shipment_trackings`
--
ALTER TABLE `shipment_trackings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shipment_trackings_shipment_id_foreign` (`shipment_id`);

--
-- Indexes for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stocks`
--
ALTER TABLE `stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stocks_product_id_foreign` (`product_id`),
  ADD KEY `stocks_warehouse_id_foreign` (`warehouse_id`);

--
-- Indexes for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_movements_product_id_foreign` (`product_id`),
  ADD KEY `stock_movements_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `stock_movements_supplier_id_foreign` (`supplier_id`);

--
-- Indexes for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `stock_transfers_transfer_no_unique` (`transfer_no`),
  ADD KEY `stock_transfers_from_warehouse_id_foreign` (`from_warehouse_id`),
  ADD KEY `stock_transfers_to_warehouse_id_foreign` (`to_warehouse_id`);

--
-- Indexes for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `stock_transfer_items_stock_transfer_id_foreign` (`stock_transfer_id`),
  ADD KEY `stock_transfer_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `suppliers_user_id_foreign` (`user_id`);

--
-- Indexes for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_payments_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_payments_purchase_id_foreign` (`purchase_id`);

--
-- Indexes for table `supplier_quotations`
--
ALTER TABLE `supplier_quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `supplier_quotations_quotation_number_unique` (`quotation_number`),
  ADD KEY `supplier_quotations_supplier_id_foreign` (`supplier_id`),
  ADD KEY `supplier_quotations_warehouse_id_foreign` (`warehouse_id`),
  ADD KEY `supplier_quotations_created_by_foreign` (`created_by`);

--
-- Indexes for table `supplier_quotation_attachments`
--
ALTER TABLE `supplier_quotation_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_quotation_attachments_supplier_quotation_id_foreign` (`supplier_quotation_id`);

--
-- Indexes for table `supplier_quotation_items`
--
ALTER TABLE `supplier_quotation_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `supplier_quotation_items_supplier_quotation_id_foreign` (`supplier_quotation_id`),
  ADD KEY `supplier_quotation_items_product_id_foreign` (`product_id`);

--
-- Indexes for table `taxes`
--
ALTER TABLE `taxes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_messages_customer_support_ticket_id_foreign` (`customer_support_ticket_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_reference_no_unique` (`reference_no`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_role_id_foreign` (`role_id`);

--
-- Indexes for table `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallets_customer_id_unique` (`customer_id`);

--
-- Indexes for table `wallet_topup_requests`
--
ALTER TABLE `wallet_topup_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_topup_requests_customer_id_foreign` (`customer_id`),
  ADD KEY `wallet_topup_requests_wallet_id_foreign` (`wallet_id`),
  ADD KEY `wallet_topup_requests_approved_by_foreign` (`approved_by`);

--
-- Indexes for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_transactions_wallet_id_foreign` (`wallet_id`);

--
-- Indexes for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `warehouses_code_unique` (`code`),
  ADD KEY `warehouses_manager_id_foreign` (`manager_id`);

--
-- Indexes for table `warranties`
--
ALTER TABLE `warranties`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warranties_product_id_foreign` (`product_id`);

--
-- Indexes for table `warranty_claims`
--
ALTER TABLE `warranty_claims`
  ADD PRIMARY KEY (`id`),
  ADD KEY `warranty_claims_warranty_id_foreign` (`warranty_id`),
  ADD KEY `warranty_claims_sale_item_id_foreign` (`sale_item_id`),
  ADD KEY `warranty_claims_customer_id_foreign` (`customer_id`),
  ADD KEY `warranty_claims_received_by_foreign` (`received_by`);

--
-- Indexes for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlists_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wishlist_items_wishlist_id_foreign` (`wishlist_id`),
  ADD KEY `wishlist_items_product_id_foreign` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `attendances`
--
ALTER TABLE `attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `candidate_documents`
--
ALTER TABLE `candidate_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_education`
--
ALTER TABLE `candidate_education`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_portfolios`
--
ALTER TABLE `candidate_portfolios`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `candidate_work_experiences`
--
ALTER TABLE `candidate_work_experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `carts`
--
ALTER TABLE `carts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customer_support_tickets`
--
ALTER TABLE `customer_support_tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `educations`
--
ALTER TABLE `educations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `employee_documents`
--
ALTER TABLE `employee_documents`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `employee_education`
--
ALTER TABLE `employee_education`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_experiences`
--
ALTER TABLE `employee_experiences`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_resignations`
--
ALTER TABLE `employee_resignations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_terminations`
--
ALTER TABLE `employee_terminations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `employee_testimonials`
--
ALTER TABLE `employee_testimonials`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `institutions`
--
ALTER TABLE `institutions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interview_feedback`
--
ALTER TABLE `interview_feedback`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_applications`
--
ALTER TABLE `job_applications`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_offers`
--
ALTER TABLE `job_offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_postings`
--
ALTER TABLE `job_postings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment_methods`
--
ALTER TABLE `payment_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchases`
--
ALTER TABLE `purchases`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_items`
--
ALTER TABLE `purchase_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `purchase_return_payments`
--
ALTER TABLE `purchase_return_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `request_for_quotations`
--
ALTER TABLE `request_for_quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `request_for_quotation_items`
--
ALTER TABLE `request_for_quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `salaries`
--
ALTER TABLE `salaries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `salary_payments`
--
ALTER TABLE `salary_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `sales`
--
ALTER TABLE `sales`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `sales_returns`
--
ALTER TABLE `sales_returns`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sales_return_items`
--
ALTER TABLE `sales_return_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `sale_items`
--
ALTER TABLE `sale_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shifts`
--
ALTER TABLE `shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shipments`
--
ALTER TABLE `shipments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shipment_trackings`
--
ALTER TABLE `shipment_trackings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shipping_methods`
--
ALTER TABLE `shipping_methods`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `stocks`
--
ALTER TABLE `stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stock_movements`
--
ALTER TABLE `stock_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `suppliers`
--
ALTER TABLE `suppliers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `supplier_quotations`
--
ALTER TABLE `supplier_quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_quotation_attachments`
--
ALTER TABLE `supplier_quotation_attachments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier_quotation_items`
--
ALTER TABLE `supplier_quotation_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `taxes`
--
ALTER TABLE `taxes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=67;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `wallets`
--
ALTER TABLE `wallets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `wallet_topup_requests`
--
ALTER TABLE `wallet_topup_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `warehouses`
--
ALTER TABLE `warehouses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `warranties`
--
ALTER TABLE `warranties`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warranty_claims`
--
ALTER TABLE `warranty_claims`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `wishlists`
--
ALTER TABLE `wishlists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attendances`
--
ALTER TABLE `attendances`
  ADD CONSTRAINT `attendances_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `candidate_documents`
--
ALTER TABLE `candidate_documents`
  ADD CONSTRAINT `candidate_documents_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `candidate_documents_verified_by_foreign` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `candidate_education`
--
ALTER TABLE `candidate_education`
  ADD CONSTRAINT `candidate_education_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `candidate_portfolios`
--
ALTER TABLE `candidate_portfolios`
  ADD CONSTRAINT `candidate_portfolios_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `carts`
--
ALTER TABLE `carts`
  ADD CONSTRAINT `carts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_cart_id_foreign` FOREIGN KEY (`cart_id`) REFERENCES `carts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customers`
--
ALTER TABLE `customers`
  ADD CONSTRAINT `customers_referral_by_foreign` FOREIGN KEY (`referral_by`) REFERENCES `customers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `customer_support_tickets`
--
ALTER TABLE `customer_support_tickets`
  ADD CONSTRAINT `customer_support_tickets_assigned_to_foreign` FOREIGN KEY (`assigned_to`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `customer_support_tickets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `customer_support_tickets_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_assignments`
--
ALTER TABLE `delivery_assignments`
  ADD CONSTRAINT `delivery_assignments_delivery_boy_id_foreign` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_assignments_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_attempts`
--
ALTER TABLE `delivery_attempts`
  ADD CONSTRAINT `delivery_attempts_delivery_assignment_id_foreign` FOREIGN KEY (`delivery_assignment_id`) REFERENCES `delivery_assignments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_attempts_delivery_boy_id_foreign` FOREIGN KEY (`delivery_boy_id`) REFERENCES `delivery_boys` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `delivery_attempts_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_boys`
--
ALTER TABLE `delivery_boys`
  ADD CONSTRAINT `delivery_boys_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `educations`
--
ALTER TABLE `educations`
  ADD CONSTRAINT `educations_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employees`
--
ALTER TABLE `employees`
  ADD CONSTRAINT `employees_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_education_id_foreign` FOREIGN KEY (`education_id`) REFERENCES `educations` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_institution_id_foreign` FOREIGN KEY (`institution_id`) REFERENCES `institutions` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `employees_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_documents`
--
ALTER TABLE `employee_documents`
  ADD CONSTRAINT `employee_documents_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_education`
--
ALTER TABLE `employee_education`
  ADD CONSTRAINT `employee_education_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_experiences`
--
ALTER TABLE `employee_experiences`
  ADD CONSTRAINT `employee_experiences_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_resignations`
--
ALTER TABLE `employee_resignations`
  ADD CONSTRAINT `employee_resignations_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_terminations`
--
ALTER TABLE `employee_terminations`
  ADD CONSTRAINT `employee_terminations_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_testimonials`
--
ALTER TABLE `employee_testimonials`
  ADD CONSTRAINT `employee_testimonials_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_expense_category_id_foreign` FOREIGN KEY (`expense_category_id`) REFERENCES `expense_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_received_notes`
--
ALTER TABLE `goods_received_notes`
  ADD CONSTRAINT `goods_received_notes_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_notes_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_notes_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_notes_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `goods_received_note_items`
--
ALTER TABLE `goods_received_note_items`
  ADD CONSTRAINT `goods_received_note_items_goods_received_note_id_foreign` FOREIGN KEY (`goods_received_note_id`) REFERENCES `goods_received_notes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_note_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `goods_received_note_items_purchase_order_item_id_foreign` FOREIGN KEY (`purchase_order_item_id`) REFERENCES `purchase_order_items` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_interviewer_foreign` FOREIGN KEY (`interviewer`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `interviews_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `interview_feedback`
--
ALTER TABLE `interview_feedback`
  ADD CONSTRAINT `interview_feedback_interview_id_foreign` FOREIGN KEY (`interview_id`) REFERENCES `interviews` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_applications`
--
ALTER TABLE `job_applications`
  ADD CONSTRAINT `job_applications_job_posting_id_foreign` FOREIGN KEY (`job_posting_id`) REFERENCES `job_postings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_offers`
--
ALTER TABLE `job_offers`
  ADD CONSTRAINT `job_offers_job_application_id_foreign` FOREIGN KEY (`job_application_id`) REFERENCES `job_applications` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `job_postings`
--
ALTER TABLE `job_postings`
  ADD CONSTRAINT `job_postings_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `job_postings_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`);

--
-- Constraints for table `leaves`
--
ALTER TABLE `leaves`
  ADD CONSTRAINT `leaves_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaves_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_payment_method_id_foreign` FOREIGN KEY (`payment_method_id`) REFERENCES `payment_methods` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment_methods`
--
ALTER TABLE `payment_methods`
  ADD CONSTRAINT `payment_methods_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD CONSTRAINT `payrolls_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_brand_id_foreign` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `products_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `product_images_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchases`
--
ALTER TABLE `purchases`
  ADD CONSTRAINT `purchases_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_items`
--
ALTER TABLE `purchase_items`
  ADD CONSTRAINT `purchase_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_items_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_orders`
--
ALTER TABLE `purchase_orders`
  ADD CONSTRAINT `purchase_orders_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_orders_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `purchase_orders_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_orders_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_order_items`
--
ALTER TABLE `purchase_order_items`
  ADD CONSTRAINT `purchase_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_order_items_purchase_order_id_foreign` FOREIGN KEY (`purchase_order_id`) REFERENCES `purchase_orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisitions`
--
ALTER TABLE `purchase_requisitions`
  ADD CONSTRAINT `purchase_requisitions_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requisitions_department_id_foreign` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `purchase_requisitions_requested_by_foreign` FOREIGN KEY (`requested_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requisitions_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_requisition_items`
--
ALTER TABLE `purchase_requisition_items`
  ADD CONSTRAINT `purchase_requisition_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_requisition_items_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_returns`
--
ALTER TABLE `purchase_returns`
  ADD CONSTRAINT `purchase_returns_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_returns_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_return_items`
--
ALTER TABLE `purchase_return_items`
  ADD CONSTRAINT `purchase_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_items_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `purchase_return_payments`
--
ALTER TABLE `purchase_return_payments`
  ADD CONSTRAINT `purchase_return_payments_purchase_return_id_foreign` FOREIGN KEY (`purchase_return_id`) REFERENCES `purchase_returns` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `purchase_return_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_for_quotations`
--
ALTER TABLE `request_for_quotations`
  ADD CONSTRAINT `request_for_quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_for_quotations_purchase_requisition_id_foreign` FOREIGN KEY (`purchase_requisition_id`) REFERENCES `purchase_requisitions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_for_quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `request_for_quotation_items`
--
ALTER TABLE `request_for_quotation_items`
  ADD CONSTRAINT `request_for_quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `request_for_quotation_items_request_for_quotation_id_foreign` FOREIGN KEY (`request_for_quotation_id`) REFERENCES `request_for_quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salaries`
--
ALTER TABLE `salaries`
  ADD CONSTRAINT `salaries_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `salary_payments`
--
ALTER TABLE `salary_payments`
  ADD CONSTRAINT `salary_payments_employee_id_foreign` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payments_payroll_id_foreign` FOREIGN KEY (`payroll_id`) REFERENCES `payrolls` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `salary_payments_salary_id_foreign` FOREIGN KEY (`salary_id`) REFERENCES `salaries` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales`
--
ALTER TABLE `sales`
  ADD CONSTRAINT `sales_cashier_id_foreign` FOREIGN KEY (`cashier_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `sales_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `sales_returns`
--
ALTER TABLE `sales_returns`
  ADD CONSTRAINT `sales_returns_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sales_return_items`
--
ALTER TABLE `sales_return_items`
  ADD CONSTRAINT `sales_return_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sales_return_items_sales_return_id_foreign` FOREIGN KEY (`sales_return_id`) REFERENCES `sales_returns` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `sale_items`
--
ALTER TABLE `sale_items`
  ADD CONSTRAINT `sale_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `sale_items_sale_id_foreign` FOREIGN KEY (`sale_id`) REFERENCES `sales` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `shipments`
--
ALTER TABLE `shipments`
  ADD CONSTRAINT `shipments_canceled_by_foreign` FOREIGN KEY (`canceled_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipments_dispatch_by_foreign` FOREIGN KEY (`dispatch_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `shipments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `shipments_shipping_method_id_foreign` FOREIGN KEY (`shipping_method_id`) REFERENCES `shipping_methods` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `shipment_trackings`
--
ALTER TABLE `shipment_trackings`
  ADD CONSTRAINT `shipment_trackings_shipment_id_foreign` FOREIGN KEY (`shipment_id`) REFERENCES `shipments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stocks`
--
ALTER TABLE `stocks`
  ADD CONSTRAINT `stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stocks_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_movements`
--
ALTER TABLE `stock_movements`
  ADD CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_movements_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `stock_movements_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfers`
--
ALTER TABLE `stock_transfers`
  ADD CONSTRAINT `stock_transfers_from_warehouse_id_foreign` FOREIGN KEY (`from_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfers_to_warehouse_id_foreign` FOREIGN KEY (`to_warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stock_transfer_items`
--
ALTER TABLE `stock_transfer_items`
  ADD CONSTRAINT `stock_transfer_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `stock_transfer_items_stock_transfer_id_foreign` FOREIGN KEY (`stock_transfer_id`) REFERENCES `stock_transfers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `suppliers`
--
ALTER TABLE `suppliers`
  ADD CONSTRAINT `suppliers_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_payments`
--
ALTER TABLE `supplier_payments`
  ADD CONSTRAINT `supplier_payments_purchase_id_foreign` FOREIGN KEY (`purchase_id`) REFERENCES `purchases` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `supplier_payments_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_quotations`
--
ALTER TABLE `supplier_quotations`
  ADD CONSTRAINT `supplier_quotations_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_quotations_supplier_id_foreign` FOREIGN KEY (`supplier_id`) REFERENCES `suppliers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_quotations_warehouse_id_foreign` FOREIGN KEY (`warehouse_id`) REFERENCES `warehouses` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_quotation_attachments`
--
ALTER TABLE `supplier_quotation_attachments`
  ADD CONSTRAINT `supplier_quotation_attachments_supplier_quotation_id_foreign` FOREIGN KEY (`supplier_quotation_id`) REFERENCES `supplier_quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `supplier_quotation_items`
--
ALTER TABLE `supplier_quotation_items`
  ADD CONSTRAINT `supplier_quotation_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `supplier_quotation_items_supplier_quotation_id_foreign` FOREIGN KEY (`supplier_quotation_id`) REFERENCES `supplier_quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_customer_support_ticket_id_foreign` FOREIGN KEY (`customer_support_ticket_id`) REFERENCES `customer_support_tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wallet_topup_requests`
--
ALTER TABLE `wallet_topup_requests`
  ADD CONSTRAINT `wallet_topup_requests_approved_by_foreign` FOREIGN KEY (`approved_by`) REFERENCES `employees` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `wallet_topup_requests_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wallet_topup_requests_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `wallet_transactions`
--
ALTER TABLE `wallet_transactions`
  ADD CONSTRAINT `wallet_transactions_wallet_id_foreign` FOREIGN KEY (`wallet_id`) REFERENCES `wallets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warehouses`
--
ALTER TABLE `warehouses`
  ADD CONSTRAINT `warehouses_manager_id_foreign` FOREIGN KEY (`manager_id`) REFERENCES `employees` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `warranties`
--
ALTER TABLE `warranties`
  ADD CONSTRAINT `warranties_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `warranty_claims`
--
ALTER TABLE `warranty_claims`
  ADD CONSTRAINT `warranty_claims_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warranty_claims_received_by_foreign` FOREIGN KEY (`received_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `warranty_claims_sale_item_id_foreign` FOREIGN KEY (`sale_item_id`) REFERENCES `sale_items` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `warranty_claims_warranty_id_foreign` FOREIGN KEY (`warranty_id`) REFERENCES `warranties` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlists`
--
ALTER TABLE `wishlists`
  ADD CONSTRAINT `wishlists_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist_items`
--
ALTER TABLE `wishlist_items`
  ADD CONSTRAINT `wishlist_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_items_wishlist_id_foreign` FOREIGN KEY (`wishlist_id`) REFERENCES `wishlists` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
