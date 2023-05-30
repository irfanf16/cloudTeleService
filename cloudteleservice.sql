-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 30, 2023 at 06:21 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `cloudteleservice`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

DROP TABLE IF EXISTS `calendars`;
CREATE TABLE IF NOT EXISTS `calendars` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sync_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `calendars_ref_id_unique` (`ref_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `ref_id`, `timezone`, `sync_token`, `created_at`, `updated_at`) VALUES
('98cc7316-bdd5-4075-8f50-285622686f88', '1d5b1a8d1a8471c662c48fe64ffb5da98ecdcd3a1de3066d68545d5e67c5ecbc@group.calendar.google.com', 'America/New_York', 'abc', '2023-04-29 13:13:17', '2023-04-29 13:13:17');

-- --------------------------------------------------------

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
CREATE TABLE IF NOT EXISTS `contacts` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1=>for yes 0=> for no',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `terms`, `created_at`, `updated_at`) VALUES
(1, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-01 02:09:14', '2023-05-01 02:09:14'),
(2, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-01 02:09:16', '2023-05-01 02:09:16'),
(3, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-01 02:09:17', '2023-05-01 02:09:17'),
(4, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-01 02:09:19', '2023-05-01 02:09:19'),
(5, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-01 02:09:21', '2023-05-01 02:09:21'),
(6, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:35', '2023-05-02 09:39:35'),
(7, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:38', '2023-05-02 09:39:38'),
(8, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:39', '2023-05-02 09:39:39'),
(9, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:40', '2023-05-02 09:39:40'),
(10, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:41', '2023-05-02 09:39:41'),
(11, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:42', '2023-05-02 09:39:42'),
(12, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:42', '2023-05-02 09:39:42'),
(13, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:43', '2023-05-02 09:39:43'),
(14, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:44', '2023-05-02 09:39:44'),
(15, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:45', '2023-05-02 09:39:45'),
(16, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:46', '2023-05-02 09:39:46'),
(17, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:46', '2023-05-02 09:39:46'),
(18, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:47', '2023-05-02 09:39:47'),
(19, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:48', '2023-05-02 09:39:48'),
(20, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:49', '2023-05-02 09:39:49'),
(21, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:49', '2023-05-02 09:39:49'),
(22, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:50', '2023-05-02 09:39:50'),
(23, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:51', '2023-05-02 09:39:51'),
(24, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:52', '2023-05-02 09:39:52'),
(25, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:52', '2023-05-02 09:39:52'),
(26, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:53', '2023-05-02 09:39:53'),
(27, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:54', '2023-05-02 09:39:54'),
(28, 'hanry', 'demo@gmail.com', '03091234567', 'subject', 'dsdsada', 0, '2023-05-02 09:39:54', '2023-05-02 09:39:54');

-- --------------------------------------------------------

--
-- Table structure for table `documents`
--

DROP TABLE IF EXISTS `documents`;
CREATE TABLE IF NOT EXISTS `documents` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_by` int DEFAULT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `doc_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `documents_event_id_foreign` (`event_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `documents`
--

INSERT INTO `documents` (`id`, `event_id`, `created_by`, `type`, `description`, `doc_link`, `created_at`, `updated_at`) VALUES
('990ce536-fbfe-4769-9681-59a8b6d2112e', '990ce4a7-7980-4e27-a7c7-d419d40180f1', NULL, 'document', 'image.png', 'documents/8kuzlguJIAQP3Ekm9TvpkE4Zmmm3kIcjTCeWm8tR.png', '2023-04-29 13:17:22', '2023-05-01 00:40:17');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

DROP TABLE IF EXISTS `events`;
CREATE TABLE IF NOT EXISTS `events` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ref_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `calendar_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `summary` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `services` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `hangoutLink` longtext COLLATE utf8mb4_unicode_ci,
  `start` datetime NOT NULL,
  `end` datetime NOT NULL,
  `timezone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('confirmed','tentative','cancelled','pending') COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `events_ref_id_unique` (`ref_id`),
  KEY `events_calendar_id_foreign` (`calendar_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `ref_id`, `calendar_id`, `description`, `summary`, `services`, `hangoutLink`, `start`, `end`, `timezone`, `status`, `created_at`, `updated_at`) VALUES
('990ce4a7-7980-4e27-a7c7-d419d40180f1', NULL, '98cc7316-bdd5-4075-8f50-285622686f88', 'description', 'Appointment booking from demo@gmail.com', 'travel1', NULL, '2023-04-17 09:00:00', '2023-04-17 10:00:00', 'America/New_York', 'cancelled', '2023-04-29 13:15:48', '2023-05-01 02:21:24'),
('990fe66e-d062-4e18-8119-070aedf9bf7a', NULL, '98cc7316-bdd5-4075-8f50-285622686f88', 'description', 'Appointment booking from demo@gmail.com', 'travel1', NULL, '2023-04-17 09:00:00', '2023-04-17 10:00:00', 'America/New_York', 'pending', '2023-05-01 01:08:16', '2023-05-01 01:08:16');

-- --------------------------------------------------------

--
-- Table structure for table `event_attendees`
--

DROP TABLE IF EXISTS `event_attendees`;
CREATE TABLE IF NOT EXISTS `event_attendees` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lname` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_attendees`
--

INSERT INTO `event_attendees` (`id`, `fname`, `lname`, `phone`, `email`, `created_at`, `updated_at`) VALUES
('990ce4a7-8674-44ff-a676-3098050efc40', 'fname', 'lname', '03091503545', 'demo@gmail.com', '2023-04-29 13:15:48', '2023-04-29 13:15:48');

-- --------------------------------------------------------

--
-- Table structure for table `event_event_attendee`
--

DROP TABLE IF EXISTS `event_event_attendee`;
CREATE TABLE IF NOT EXISTS `event_event_attendee` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `event_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_attendee_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `event_event_attendee_event_id_foreign` (`event_id`),
  KEY `event_event_attendee_event_attendee_id_foreign` (`event_attendee_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_event_attendee`
--

INSERT INTO `event_event_attendee` (`id`, `event_id`, `event_attendee_id`, `created_at`, `updated_at`) VALUES
(1, '990ce4a7-7980-4e27-a7c7-d419d40180f1', '990ce4a7-8674-44ff-a676-3098050efc40', NULL, NULL),
(2, '990fe66e-d062-4e18-8119-070aedf9bf7a', '990ce4a7-8674-44ff-a676-3098050efc40', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `flights`
--

DROP TABLE IF EXISTS `flights`;
CREATE TABLE IF NOT EXISTS `flights` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `designation` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `class` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `terms` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `flights`
--

INSERT INTO `flights` (`id`, `name`, `email`, `phone`, `location`, `designation`, `class`, `terms`, `created_at`, `updated_at`) VALUES
('990fdf41-100e-414e-aa16-2e9d00556107', 'flight name', 'demo@gmail.com', '03121111111', 'lhr', 'HR', 'class', '0', '2023-05-01 00:48:11', '2023-05-01 00:48:11');

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
(4, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
(5, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
(6, '2016_06_01_000004_create_oauth_clients_table', 1),
(7, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
(8, '2019_08_19_000000_create_failed_jobs_table', 1),
(9, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(10, '2023_02_23_201946_create_job_batches_table', 1),
(11, '2023_03_25_211347_create_calendars_table', 1),
(12, '2023_03_26_135349_create_events_table', 1),
(13, '2023_03_26_143845_create_event_attendees_table', 1),
(14, '2023_03_26_180122_create_event_event_attendee', 1),
(15, '2023_04_16_011510_create_flights_table', 1),
(16, '2023_04_17_103107_create_documents_table', 1),
(17, '2023_04_17_103940_create_payments_table', 1),
(18, '2023_04_20_132116_create_contacts_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `oauth_access_tokens`
--

DROP TABLE IF EXISTS `oauth_access_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_auth_codes`
--

DROP TABLE IF EXISTS `oauth_auth_codes`;
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint UNSIGNED NOT NULL,
  `client_id` bigint UNSIGNED NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_clients`
--

DROP TABLE IF EXISTS `oauth_clients`;
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED DEFAULT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_personal_access_clients`
--

DROP TABLE IF EXISTS `oauth_personal_access_clients`;
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `client_id` bigint UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `oauth_refresh_tokens`
--

DROP TABLE IF EXISTS `oauth_refresh_tokens`;
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
CREATE TABLE IF NOT EXISTS `password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
CREATE TABLE IF NOT EXISTS `payments` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `event_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `flight_id` char(36) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `stripeToken` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `payments_event_id_foreign` (`event_id`),
  KEY `payments_flight_id_foreign` (`flight_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `event_id`, `flight_id`, `amount`, `stripeToken`, `created_at`, `updated_at`) VALUES
('990ce4a7-a68c-4804-b357-62d1a0be3f08', '990ce4a7-7980-4e27-a7c7-d419d40180f1', NULL, '149.99', 'ch_3N2M3ULhXeNg18m20fBLlVud', '2023-04-29 13:15:48', '2023-04-29 13:15:48'),
('990fdf41-1b1e-48e8-a517-460bbcff4067', NULL, '990fdf41-100e-414e-aa16-2e9d00556107', '99.99', 'ch_3N2tL7LhXeNg18m23isqq2VJ', '2023-05-01 00:48:11', '2023-05-01 00:48:11'),
('990fe66f-7d6f-4111-9b1e-c55dee4e727b', '990fe66e-d062-4e18-8119-070aedf9bf7a', NULL, '149.99', 'ch_3N2teWLhXeNg18m22keKsmD8', '2023-05-01 01:08:16', '2023-05-01 01:08:16');

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` char(26) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
('01gz7h7pk3z8ejg7axqgkcfef1', 'Super Admin', 'admin@dev.com', NULL, '$2y$10$0HHq6JpUI6GVl0RqpHbx9.3aBQLW2ZejOFHiBvE5n440bG2.DrwS.', NULL, '2023-04-29 13:13:17', '2023-04-29 13:13:17');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
