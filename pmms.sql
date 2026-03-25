-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 25, 2026 at 06:55 AM
-- Server version: 8.0.44
-- PHP Version: 8.4.16

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `pmms`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '#4f46e5',
  `description` text COLLATE utf8mb4_unicode_ci,
  `is_default` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `user_id`, `name`, `slug`, `type`, `color`, `description`, `is_default`, `created_at`, `updated_at`) VALUES
(1, 1, 'Salary', 'salary', 'income', '#0f766e', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 'Freelance', 'freelance', 'income', '#2563eb', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 'Business', 'business', 'income', '#7c3aed', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 1, 'Investment', 'investment', 'income', '#059669', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(5, 1, 'Gift', 'gift', 'income', '#d946ef', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(6, 1, 'Food', 'food', 'expense', '#f97316', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(7, 1, 'Transport', 'transport', 'expense', '#0ea5e9', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(8, 1, 'Rent', 'rent', 'expense', '#ef4444', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(9, 1, 'Utility', 'utility', 'expense', '#22c55e', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(10, 1, 'Shopping', 'shopping', 'expense', '#8b5cf6', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(11, 1, 'Internet', 'internet', 'expense', '#ec4899', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(12, 2, 'Salary', 'salary', 'income', '#0f766e', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(13, 2, 'Freelance', 'freelance', 'income', '#2563eb', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(14, 2, 'Business', 'business', 'income', '#7c3aed', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(15, 2, 'Investment', 'investment', 'income', '#059669', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(16, 2, 'Gift', 'gift', 'income', '#d946ef', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(17, 2, 'Food', 'food', 'expense', '#f97316', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(18, 2, 'Transport', 'transport', 'expense', '#0ea5e9', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(19, 2, 'Rent', 'rent', 'expense', '#ef4444', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(20, 2, 'Utility', 'utility', 'expense', '#22c55e', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(21, 2, 'Shopping', 'shopping', 'expense', '#8b5cf6', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(22, 2, 'Internet', 'internet', 'expense', '#ec4899', NULL, 1, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(23, 3, 'Salary', 'salary', 'income', '#0f766e', NULL, 1, '2026-03-16 22:44:37', '2026-03-16 22:44:37'),
(24, 3, 'Freelance', 'freelance', 'income', '#2563eb', NULL, 1, '2026-03-16 22:44:37', '2026-03-16 22:44:37'),
(25, 3, 'Business', 'business', 'income', '#7c3aed', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(26, 3, 'Investment', 'investment', 'income', '#059669', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(27, 3, 'Gift', 'gift', 'income', '#d946ef', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(28, 3, 'Food', 'food', 'expense', '#f97316', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(29, 3, 'Transport', 'transport', 'expense', '#0ea5e9', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(30, 3, 'Rent', 'rent', 'expense', '#ef4444', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(31, 3, 'Utility', 'utility', 'expense', '#22c55e', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(32, 3, 'Shopping', 'shopping', 'expense', '#8b5cf6', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(33, 3, 'Internet', 'internet', 'expense', '#ec4899', NULL, 1, '2026-03-16 22:44:38', '2026-03-16 22:44:38'),
(34, 4, 'Salary', 'salary', 'income', '#0f766e', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(35, 4, 'Freelance', 'freelance', 'income', '#2563eb', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(36, 4, 'Business', 'business', 'income', '#7c3aed', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(37, 4, 'Investment', 'investment', 'income', '#059669', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(38, 4, 'Gift', 'gift', 'income', '#d946ef', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(39, 4, 'Food', 'food', 'expense', '#f97316', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(40, 4, 'Transport', 'transport', 'expense', '#0ea5e9', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(41, 4, 'Rent', 'rent', 'expense', '#ef4444', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(42, 4, 'Utility', 'utility', 'expense', '#22c55e', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(43, 4, 'Shopping', 'shopping', 'expense', '#8b5cf6', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(44, 4, 'Internet', 'internet', 'expense', '#ec4899', NULL, 1, '2026-03-17 02:50:15', '2026-03-17 02:50:15'),
(45, 4, 'Updated Bonus', 'updated-bonus', 'income', '#0ea5e9', 'Updated category', 0, '2026-03-17 02:50:15', '2026-03-17 02:50:15');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_via` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `paid_to` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expense_date` date NOT NULL,
  `due_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expenses`
--

INSERT INTO `expenses` (`id`, `user_id`, `category_id`, `amount`, `status`, `paid_via`, `paid_to`, `expense_date`, `due_date`, `notes`, `attachment_path`, `created_at`, `updated_at`) VALUES
(1, 1, 8, 18000.00, 'paid', 'bank', 'House Owner', '2025-11-05', '2025-11-05', 'Monthly house rent', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 9, 3200.00, 'paid', 'mobile_banking', 'DESCO', '2025-11-08', NULL, 'Electricity bill', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 6, 6800.00, 'paid', 'cash', 'Groceries', '2025-12-14', NULL, 'Family groceries', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 1, 11, 1500.00, 'paid', 'mobile_banking', 'FiberNet', '2025-12-09', NULL, 'Home internet bill', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(5, 1, 7, 2400.00, 'paid', 'cash', 'Ride and fuel', '2026-01-17', NULL, 'Travel cost', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(6, 1, 10, 5200.00, 'paid', 'card', 'Lifestyle Store', '2026-01-22', NULL, 'Home and personal items', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(7, 1, 6, 7200.00, 'paid', 'cash', 'Groceries', '2026-02-12', NULL, 'Monthly groceries', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(8, 1, 8, 18000.00, 'paid', 'bank', 'House Owner', '2026-02-05', '2026-02-05', 'Monthly house rent', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(9, 1, 9, 3500.00, 'pending', 'bank', 'DESCO', '2026-03-25', '2026-03-21', 'Pending electricity bill', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(10, 1, 11, 1500.00, 'paid', 'mobile_banking', 'FiberNet', '2026-03-03', NULL, 'Internet paid', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(11, 1, 7, 2800.00, 'pending', 'cash', 'Fuel station', '2026-03-18', '2026-03-19', 'Vehicle fuel pending entry', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(12, 2, 17, 4800.00, 'paid', 'cash', 'Fresh market', '2026-02-27', NULL, 'Home groceries', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(13, 2, 22, 1200.00, 'pending', 'mobile_banking', 'NetLink', '2026-03-04', '2026-03-23', 'Internet renewal pending', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
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
-- Table structure for table `given_loans`
--

CREATE TABLE `given_loans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `given_date` date NOT NULL,
  `expected_return_date` date DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `returned_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `returned_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `given_loans`
--

INSERT INTO `given_loans` (`id`, `user_id`, `person_name`, `amount`, `given_date`, `expected_return_date`, `status`, `returned_amount`, `returned_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Rahim', 10000.00, '2025-12-12', '2026-01-12', 'partial', 4000.00, NULL, 'Emergency family support', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 'Kamal', 7000.00, '2026-02-08', '2026-03-22', 'pending', 0.00, NULL, 'Short-term personal loan', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 'Jannat', 6000.00, '2026-01-21', '2026-02-21', 'returned', 6000.00, '2026-02-22', 'Returned on time', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 3, 'ripan', 3000.00, '2026-03-16', '2026-03-20', 'pending', 0.00, NULL, NULL, '2026-03-16 22:47:12', '2026-03-16 22:47:12');

-- --------------------------------------------------------

--
-- Table structure for table `incomes`
--

CREATE TABLE `incomes` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `category_id` bigint UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `received_by` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `received_from` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `expected_date` date DEFAULT NULL,
  `received_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `attachment_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_recurring` tinyint(1) NOT NULL DEFAULT '0',
  `recurrence_cycle` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `incomes`
--

INSERT INTO `incomes` (`id`, `user_id`, `category_id`, `amount`, `status`, `received_by`, `received_from`, `expected_date`, `received_date`, `notes`, `attachment_path`, `is_recurring`, `recurrence_cycle`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 55000.00, 'paid', 'bank', 'TechNova Ltd.', '2025-11-02', '2025-11-02', 'Monthly salary', NULL, 1, 'monthly', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 1, 55000.00, 'paid', 'bank', 'TechNova Ltd.', '2025-12-02', '2025-12-02', 'Monthly salary', NULL, 1, 'monthly', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 2, 18000.00, 'paid', 'mobile_banking', 'Pixel Forge Studio', '2026-01-18', '2026-01-19', 'Landing page project payment', NULL, 0, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 1, 3, 22000.00, 'paid', 'bank', 'Local shop sales', '2026-02-26', '2026-02-27', 'Monthly business profit', NULL, 0, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(5, 1, 2, 12000.00, 'pending', 'bank', 'Green Peak Agency', '2026-03-23', NULL, 'Awaiting final milestone release', NULL, 0, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(6, 1, 4, 7500.00, 'paid', 'bank', 'Dividend payout', '2026-03-10', '2026-03-10', 'Quarterly investment return', NULL, 0, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(7, 2, 12, 42000.00, 'paid', 'bank', 'BrightPath School', '2026-02-03', '2026-02-03', 'Teaching salary', NULL, 1, 'monthly', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(8, 2, 16, 5000.00, 'paid', 'cash', 'Family gift', '2026-02-25', '2026-02-25', 'Festival gift', NULL, 0, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(9, 3, 23, 15000.00, 'paid', 'bank', 'company', '2026-03-10', '2026-03-05', NULL, NULL, 0, NULL, '2026-03-16 22:45:27', '2026-03-16 22:45:27');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
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
-- Table structure for table `job_batches`
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
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_03_17_021942_create_categories_table', 1),
(5, '2026_03_17_021942_create_expenses_table', 1),
(6, '2026_03_17_021942_create_incomes_table', 1),
(7, '2026_03_17_021943_create_given_loans_table', 1),
(8, '2026_03_17_021943_create_reminders_table', 1),
(9, '2026_03_17_021943_create_taken_loans_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reminders`
--

CREATE TABLE `reminders` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'manual',
  `channel` varchar(30) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dashboard',
  `reminder_date` date NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `notes` text COLLATE utf8mb4_unicode_ci,
  `related_resource` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `related_id` bigint UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reminders`
--

INSERT INTO `reminders` (`id`, `user_id`, `title`, `type`, `channel`, `reminder_date`, `status`, `notes`, `related_resource`, `related_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'Follow up freelance invoice', 'income', 'dashboard', '2026-03-20', 'pending', 'Check with Green Peak Agency about the pending milestone.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 'Electricity bill due', 'expense', 'dashboard', '2026-03-21', 'pending', 'Pay before the due date to avoid late fees.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 'Collect from Kamal', 'receivable', 'notification', '2026-03-22', 'pending', 'Friendly follow-up call in the evening.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 1, 'Return to Arif', 'payable', 'dashboard', '2026-03-24', 'pending', 'Plan partial payment from current month surplus.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(5, 1, 'Renew internet package', 'manual', 'dashboard', '2026-03-27', 'pending', 'Check for upgraded plan before renewal.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(6, 2, 'Pay internet bill', 'expense', 'dashboard', '2026-03-23', 'pending', 'Use bKash before due date.', NULL, NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(7, 3, 'Laborum porro et mod', 'manual', 'dashboard', '1984-03-01', 'completed', 'Aliquid dolor laboru', NULL, NULL, '2026-03-17 02:43:01', '2026-03-17 02:43:01');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
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
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('akV9n0Ye66Nimzwe5zwquqiUa1OnCP4hIkZ34M94', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.26.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoielllNjlZeGxZVHp6MWhBZVZuZVF6SFo4OHU4T2dxRk44UW9mQ0tuaSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9wbW1zLnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1773736952),
('LLh8civXX8UXGgBjz4tVUdoKmWmuFc284C5mtKE7', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.26.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiQzh4Qnl5Wk5aQmxoWjlRTEkwNnpNazVQWmQ3SkFLZ1hPc0dCS1kxNiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9wbW1zLnRlc3QvP2hlcmQ9cHJldmlldyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1774420575),
('msmRAfMwgA2SV1C51K4TyQqaUMBHcqCdVdlipuFC', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.26.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiMm40SzR1akhKRTIybUhxeDVyc0Q2VTM4bDRWQmVQMTFGTVdzTlU0byI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly9wbW1zLnRlc3QvP2hlcmQ9cHJldmlldyI7czo1OiJyb3V0ZSI7Tjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1773736952),
('PvHYBlkrkk5p4uHvRduXC4fcczpAlDE0I9WUsyxj', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTUZlYkZyVm1KWWtkUXFCZTQyYjBzN2c4WnpqTEt2QnJ2ZzhLU3BpNyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjY6Imh0dHA6Ly9wbW1zLnRlc3QvcmVtaW5kZXJzIjtzOjU6InJvdXRlIjtzOjE1OiJyZW1pbmRlcnMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTozO30=', 1774420889),
('uI136tnc7gRi1rfkbu1o1V4M2qN7wMyIikOejeLW', 3, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36 Edg/146.0.0.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoid2ptcnUxRmJXbGduSHdjUUtFYjdrekh4eW13aFFKUVNWMExpeFJtciI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly9wbW1zLnRlc3QvZXhwZW5zZXMiO3M6NToicm91dGUiO3M6MTQ6ImV4cGVuc2VzLmluZGV4Ijt9czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mzt9', 1773742227),
('yINEY1I3xkvgcPCbA47lsJQ31uXgQkUi9YKdcZro', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Herd/1.26.0 Chrome/120.0.6099.291 Electron/28.2.5 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUEpXbUN2YXYwcWZMaGJBN2dUOFFMTmU0dmptQ0JUSDQ3ZzJPSFpOQyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjI6Imh0dHA6Ly9wbW1zLnRlc3QvbG9naW4iO3M6NToicm91dGUiO3M6NToibG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1774420575);

-- --------------------------------------------------------

--
-- Table structure for table `taken_loans`
--

CREATE TABLE `taken_loans` (
  `id` bigint UNSIGNED NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `person_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `borrow_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `reason` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_amount` decimal(12,2) NOT NULL DEFAULT '0.00',
  `paid_date` date DEFAULT NULL,
  `notes` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `taken_loans`
--

INSERT INTO `taken_loans` (`id`, `user_id`, `person_name`, `amount`, `borrow_date`, `return_date`, `reason`, `status`, `paid_amount`, `paid_date`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 'Arif', 15000.00, '2026-01-07', '2026-03-25', 'Laptop repair and emergency cash buffer', 'partial', 5000.00, NULL, 'Will clear after next salary', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 1, 'Nabila', 8000.00, '2026-02-10', '2026-03-29', 'Travel advance', 'pending', 0.00, NULL, 'Pending full payment', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 1, 'Hasan', 5000.00, '2025-12-16', '2026-01-18', 'Medical support', 'paid', 5000.00, '2026-01-18', 'Closed', '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(4, 3, 'ripan', 2500.00, '2026-03-17', NULL, NULL, 'pending', 0.00, NULL, NULL, '2026-03-16 22:48:41', '2026-03-16 22:48:41');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `currency` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'BDT',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `currency`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Demo Owner', 'demo@pmms.test', '01700000000', 'BDT', NULL, '$2y$12$MUPGcfeefgFnB.7ybLymAOy0AziZj.aAQ8fII4.2kFPRNxNaWUZzO', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(2, 'Sadia Personal', 'sadia@pmms.test', '01800000000', 'BDT', NULL, '$2y$12$Ho6m4YFBuqMz4N/4egzs2.YDJfB6Dc7nX6AltIZDKzmfS54Unq/SO', NULL, '2026-03-16 21:47:51', '2026-03-16 21:47:51'),
(3, 'khademul', 'admin@gmail.com', '123456789', 'BDT', NULL, '$2y$12$lOCqrTpzyYUoqevFDLAyfO7HLBnMjpS.2v6ZjwvP3X5IhNezL./mO', NULL, '2026-03-16 22:44:37', '2026-03-25 00:41:07'),
(4, 'Stacey Vandervort V', 'alisha.hills@example.com', NULL, 'BDT', '2026-03-17 02:50:15', '$2y$12$9I9.bI1mP8QOw.dsWz7XU.Xb1oFzIvQTexD0idb5dGf8rM8G1LSSS', 'WDtFRLVTxK', '2026-03-17 02:50:15', '2026-03-17 02:50:15');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_user_id_slug_type_unique` (`user_id`,`slug`,`type`),
  ADD UNIQUE KEY `categories_user_id_name_type_unique` (`user_id`,`name`,`type`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `expenses_category_id_foreign` (`category_id`),
  ADD KEY `expenses_user_id_status_expense_date_index` (`user_id`,`status`,`expense_date`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `given_loans`
--
ALTER TABLE `given_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `given_loans_user_id_status_expected_return_date_index` (`user_id`,`status`,`expected_return_date`);

--
-- Indexes for table `incomes`
--
ALTER TABLE `incomes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `incomes_category_id_foreign` (`category_id`),
  ADD KEY `incomes_user_id_status_expected_date_index` (`user_id`,`status`,`expected_date`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `reminders`
--
ALTER TABLE `reminders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reminders_user_id_status_reminder_date_index` (`user_id`,`status`,`reminder_date`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `taken_loans`
--
ALTER TABLE `taken_loans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `taken_loans_user_id_status_return_date_index` (`user_id`,`status`,`return_date`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `given_loans`
--
ALTER TABLE `given_loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `incomes`
--
ALTER TABLE `incomes`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `reminders`
--
ALTER TABLE `reminders`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `taken_loans`
--
ALTER TABLE `taken_loans`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `expenses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `given_loans`
--
ALTER TABLE `given_loans`
  ADD CONSTRAINT `given_loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `incomes`
--
ALTER TABLE `incomes`
  ADD CONSTRAINT `incomes_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `incomes_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reminders`
--
ALTER TABLE `reminders`
  ADD CONSTRAINT `reminders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taken_loans`
--
ALTER TABLE `taken_loans`
  ADD CONSTRAINT `taken_loans_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
