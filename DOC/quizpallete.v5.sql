-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 23, 2025 at 06:41 AM
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
-- Database: `quizpallete`
--

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `criteria_type` enum('score','attempts','streak','level','category') NOT NULL,
  `criteria_value` int(11) NOT NULL,
  `badge_color` varchar(7) DEFAULT '#FFD700',
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(100) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `slug`, `description`, `icon`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'JSC', 'JSC', 'For the Students of Class 8', 'jsc', 'active', 1, '2025-05-31 15:25:50', '2025-06-21 16:25:43'),
(2, 'SSC', 'SSC', 'For the Students of Class 9-10', 'ssc', 'active', 1, '2025-05-31 19:19:05', '2025-06-21 16:26:29'),
(3, 'HSC', 'HSC', 'For the Students of Collage', 'hsc', 'active', 1, '2025-06-02 19:34:45', '2025-06-21 16:27:29'),
(4, 'Primary', 'Primary', 'For the Students of Play-Group & Kinder-Garden', 'primary', 'active', 1, '2025-06-03 03:28:12', '2025-06-21 16:32:51'),
(5, 'Admission', 'Admission', 'For the Students of Admission', 'admission', 'active', 1, '2025-06-21 16:35:12', '2025-06-21 16:35:12');

-- --------------------------------------------------------

--
-- Table structure for table `classes`
--

CREATE TABLE `classes` (
  `id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `classes`
--

INSERT INTO `classes` (`id`, `category_id`, `name`, `slug`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 1, 'Class 8', 'Class 8', 'For the Students of Class 8', 'active', 1, '2025-05-31 15:47:51', '2025-06-21 16:29:21'),
(2, 2, 'Class 9-10 (Science)', 'Class 9 (Science)', 'for the Students of Class 9 (Science)', 'active', 1, '2025-05-31 19:19:22', '2025-06-21 16:39:22'),
(3, 3, 'Collage 1st year(Science)', 'Collage 1st year(Science)', 'For the students of Collage 1st year(Science)', 'active', 1, '2025-06-02 19:34:57', '2025-06-21 16:31:30'),
(4, 4, 'Pre Play', 'pre-play', 'pre play', 'active', 1, '2025-06-03 03:28:41', '2025-06-03 03:28:41'),
(5, 2, 'Class 9-10 (Arts)', 'Class 9 (Arts)', 'For the Students of Class 9 (Arts)', 'active', 1, '2025-06-21 16:35:51', '2025-06-21 16:39:13'),
(6, 3, 'College 1st year(Arts)', 'College 1st year(Arts)', 'For the Students of College 1st year(Arts)', 'active', 1, '2025-06-21 16:36:51', '2025-06-21 16:36:51'),
(7, 3, 'College 2nd year(Arts)', 'College 2nd year(Arts)', 'For the Students of College 2nd year(Arts)', 'active', 1, '2025-06-21 16:37:38', '2025-06-21 16:37:38'),
(8, 3, 'College 2nd year(Science)', 'College 2nd year(Science)', 'For the Students of College 2nd year(Science)', 'active', 1, '2025-06-21 16:38:11', '2025-06-21 16:38:11'),
(9, 2, 'Class 9-10 (Common)', 'Class 9-10 (Common)', 'For All the Students of Class 9-10', 'active', 1, '2025-06-21 16:40:40', '2025-06-21 16:40:40'),
(10, 5, 'Engineering', 'engineering', '', 'active', 1, '2025-06-22 17:40:29', '2025-06-22 17:40:29'),
(11, 5, 'Medical', 'medical', '', 'active', 1, '2025-06-22 17:43:47', '2025-06-22 17:43:47'),
(12, 5, 'University', 'university', '', 'active', 1, '2025-06-22 17:44:39', '2025-06-22 17:44:39');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `slug` varchar(150) NOT NULL,
  `event_date` date DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `name`, `slug`, `event_date`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'emni', 'qwertghj', NULL, 'werty', 'inactive', 1, '2025-05-31 16:46:28', '2025-05-31 16:46:28'),
(2, 'jhol2', 'seryhserh', NULL, 'strhsth', 'active', 1, '2025-06-02 19:35:26', '2025-06-02 19:35:26');

-- --------------------------------------------------------

--
-- Table structure for table `exams`
--

CREATE TABLE `exams` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `category_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exams`
--

INSERT INTO `exams` (`id`, `title`, `slug`, `description`, `duration`, `status`, `category_id`, `class_id`, `subject_id`, `event_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'SSC Model Test ', 'SSC Model Test ', 'For the SSC Model Test ', 50, 'active', 2, 2, 17, NULL, 2, '2025-06-01 01:10:58', '2025-06-21 19:08:40'),
(2, 'JSC Model Test', 'JSC Model Test', 'For JSC Students to Test their Preparations', 50, 'active', 1, 1, 13, NULL, 2, '2025-06-01 01:21:38', '2025-06-21 19:06:49');

-- --------------------------------------------------------

--
-- Table structure for table `exam_quizzes`
--

CREATE TABLE `exam_quizzes` (
  `exam_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `exam_quizzes`
--

INSERT INTO `exam_quizzes` (`exam_id`, `quiz_id`) VALUES
(1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `leaderboards`
--

CREATE TABLE `leaderboards` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `level` varchar(20) DEFAULT NULL,
  `total_score` int(11) DEFAULT 0,
  `total_attempts` int(11) DEFAULT 0,
  `average_score` decimal(5,2) DEFAULT 0.00,
  `rank_position` int(11) DEFAULT 0,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `type` enum('info','success','warning','error') DEFAULT 'info',
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `transaction_id` varchar(100) NOT NULL,
  `bkash_transaction_id` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'BDT',
  `payment_method` varchar(20) DEFAULT 'bkash',
  `status` enum('pending','completed','failed','refunded') DEFAULT 'pending',
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `verification_status` enum('pending','verified','rejected') DEFAULT 'pending',
  `verified_by` int(11) DEFAULT NULL,
  `verified_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pending_questions`
--

CREATE TABLE `pending_questions` (
  `id` int(11) NOT NULL,
  `question` text NOT NULL,
  `option_a` varchar(255) NOT NULL,
  `option_b` varchar(255) NOT NULL,
  `option_c` varchar(255) NOT NULL,
  `option_d` varchar(255) NOT NULL,
  `correct_option` enum('a','b','c','d') NOT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `category_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pending_questions`
--

INSERT INTO `pending_questions` (`id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`, `status`, `category_id`, `class_id`, `subject_id`, `event_id`, `created_by`, `created_at`) VALUES
(1, 'eyshtrdhbngh', 'dsrghfc', 'eshg', 'rstjkm', 'rtujh', 'd', 'approved', NULL, NULL, NULL, NULL, NULL, '2025-06-01 04:08:30');

-- --------------------------------------------------------

--
-- Table structure for table `questions`
--

CREATE TABLE `questions` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `question_text` text NOT NULL,
  `question_type` enum('multiple_choice','true_false','fill_blank') DEFAULT 'multiple_choice',
  `marks` int(11) DEFAULT 1,
  `explanation` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `order_index` int(11) DEFAULT 0,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `tags` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `questions`
--

INSERT INTO `questions` (`id`, `quiz_id`, `question_text`, `question_type`, `marks`, `explanation`, `image`, `order_index`, `status`, `created_at`, `updated_at`, `difficulty`, `tags`) VALUES
(1, 1, 'what is the right ans?', 'multiple_choice', 1, NULL, NULL, 0, 'active', '2025-06-01 10:37:32', '2025-06-01 10:37:32', 'medium', NULL),
(2, 2, 'What is the largest ocean in the world?', 'multiple_choice', 1, NULL, NULL, 0, 'active', '2025-06-01 10:37:32', '2025-06-21 18:26:26', 'medium', NULL),
(4, 6, 'Test Question?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-03 04:35:04', '2025-06-03 04:35:04', 'medium', NULL),
(5, 7, 'How many fingers we have?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-03 04:42:51', '2025-06-03 04:42:51', 'medium', NULL),
(6, 8, 'Apple is', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-03 04:46:23', '2025-06-03 04:46:23', 'medium', NULL),
(7, 9, 'Cherry is', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-03 04:47:15', '2025-06-03 04:47:15', 'medium', NULL),
(8, 10, 'What is the title of Unit 7.2.2 in the Class 8 English textbook?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 16:54:34', '2025-06-21 16:54:34', 'medium', NULL),
(9, 11, 'What is the main topic of Unit 1, Lesson 3 in the Class 8 English textbook?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 16:55:32', '2025-06-21 16:55:32', 'medium', NULL),
(10, 12, 'What is the main focus of Unit 1, Lesson 1 in the Class 8 English textbook?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 16:58:51', '2025-06-21 16:58:51', 'medium', NULL),
(11, 13, 'What does \\\"Monsoon\\\" refer to in the passage about river gypsies?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 16:59:41', '2025-06-21 16:59:41', 'medium', NULL),
(12, 14, 'Where was Begum Rokeya born?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 17:00:24', '2025-06-21 17:00:24', 'medium', NULL),
(13, 15, 'Which of the following sentences uses the correct tense?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 17:07:28', '2025-06-21 17:07:28', 'medium', NULL),
(14, 16, 'Identify the sentence with the correct use of an adjective.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 17:09:00', '2025-06-21 17:09:00', 'medium', NULL),
(15, 17, 'What is the past participle of the verb \\\"begin\\\"?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:04:43', '2025-06-21 18:04:43', 'medium', NULL),
(16, 18, 'What does the idiom \\\"break a leg\\\" mean?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:05:48', '2025-06-21 18:05:48', 'medium', NULL),
(17, 19, 'Which gas is primarily responsible for the greenhouse effect?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:17:15', '2025-06-21 18:17:15', 'medium', NULL),
(18, 20, 'What is the name of the process by which plants make their own food?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:18:11', '2025-06-21 18:18:11', 'medium', NULL),
(19, 21, 'What is the SI unit of force?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:19:21', '2025-06-21 18:19:21', 'medium', NULL),
(20, 22, 'What is the capital of Australia?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:20:37', '2025-06-21 18:20:37', 'medium', NULL),
(21, 23, 'Which planet is known as the \\\"Red Planet\\\"?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:21:36', '2025-06-21 18:21:36', 'medium', NULL),
(22, 24, 'Which of the following is NOT one of the Five Pillars of Islam?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:30:21', '2025-06-21 18:30:21', 'medium', NULL),
(23, 25, 'What is the name of the first mosque built in Islam?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:31:40', '2025-06-21 18:31:40', 'medium', NULL),
(24, 26, 'What is the degree of the polynomial 5x³ - 4x² + 7?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:33:06', '2025-06-21 18:33:06', 'medium', NULL),
(25, 27, 'If (x+1) is a factor of the polynomial 2x² + kx, what is the value of k?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:41:13', '2025-06-21 18:41:13', 'medium', NULL),
(26, 28, 'If (2, 3) is a solution of the equation 3x + ay = 12, what is the value of a?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:42:08', '2025-06-21 18:42:08', 'medium', NULL),
(27, 29, 'The sides of a triangle are 6cm, 8cm, and 10cm. What is its area?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:43:01', '2025-06-21 18:43:01', 'medium', NULL),
(28, 30, 'Which of the following is a renewable energy source?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:44:33', '2025-06-21 18:44:33', 'medium', NULL),
(29, 31, 'The formula for potential energy is:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:45:25', '2025-06-21 18:45:25', 'medium', NULL),
(30, 32, 'What is the speed of light in a vacuum?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:46:17', '2025-06-21 18:46:17', 'medium', NULL),
(31, 33, 'Which of the following is NOT a form of matter?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:48:02', '2025-06-21 18:48:02', 'medium', NULL),
(32, 34, 'What is the basic unit of matter?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:49:58', '2025-06-21 18:49:58', 'medium', NULL),
(33, 35, 'What is the name of the scientist who proposed the atomic theory?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:52:52', '2025-06-21 18:52:52', 'medium', NULL),
(34, 36, 'Which of the following is NOT a function of the cell membrane?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:54:18', '2025-06-21 18:54:18', 'medium', NULL),
(35, 37, 'What is the primary function of the xylem in plants?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 18:55:23', '2025-06-21 18:55:23', 'medium', NULL),
(36, 38, 'An object moves with a velocity v=2t2−4tv=2t2−4t m/s (where t is time in seconds). The acceleration at t=3st=3s is:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 19:01:43', '2025-06-21 19:01:43', 'medium', NULL),
(37, 39, 'If the radius of Earth were reduced to half while keeping its mass constant, the value of gg at the surface would:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 19:02:59', '2025-06-21 19:02:59', 'medium', NULL),
(38, 40, 'The drugs were innocuous and had no side effect.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:20:02', '2025-06-22 07:20:02', 'medium', NULL),
(39, 41, 'The affluence of most visiting Arabs is astonishing.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:22:26', '2025-06-22 07:24:09', 'medium', NULL),
(40, 42, 'He was a contemplative person.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:23:45', '2025-06-22 07:23:45', 'medium', NULL),
(41, 43, 'The story which Gaurav narrated was very exciting.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:26:07', '2025-06-22 07:26:07', 'medium', NULL),
(42, 44, 'He was dismissed from service because they found him dishonest.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:27:34', '2025-06-22 07:27:34', 'medium', NULL),
(43, 45, 'It is so gratifying to know that there are not many small pox cases these days.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:32:10', '2025-06-22 07:32:10', 'medium', NULL),
(44, 46, 'The cordial talks between the two foreign ministers cover the entire gamut of their relations.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:33:16', '2025-06-22 07:33:16', 'medium', NULL),
(45, 47, 'We should always try to maintain and promote communal amity.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:34:45', '2025-06-22 07:34:45', 'medium', NULL),
(46, 48, 'He has won great admiration amongst his students because of his verdant outlook.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:36:03', '2025-06-22 07:36:03', 'medium', NULL),
(47, 49, 'The novel was so interesting that I was oblivious of my surroundings.', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:37:39', '2025-06-22 07:37:39', 'medium', NULL),
(48, 50, 'Choose the correct synonym of the given word:\r\n Nastiness', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:40:22', '2025-06-22 07:46:10', 'medium', NULL),
(49, 51, 'Choose the correct synonym of the given word:\r\nLucid', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:45:35', '2025-06-22 07:53:16', 'medium', NULL),
(50, 52, 'Choose the correct synonym of the given word:\r\nModicum', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:47:34', '2025-06-22 07:56:33', 'medium', NULL),
(51, 53, 'Choose the correct synonym of the given word:\r\nDeterrent', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:48:47', '2025-06-22 07:53:45', 'medium', NULL),
(52, 54, 'Choose the correct synonym of the given word:\r\nEulogy', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:50:05', '2025-06-22 07:56:11', 'medium', NULL),
(53, 55, 'Choose the correct synonym of the given word:\r\nForeigner', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:55:44', '2025-06-22 07:56:54', 'medium', NULL),
(54, 56, 'Choose the correct synonym of the given word:\r\nVerbose', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 07:59:31', '2025-06-22 07:59:56', 'medium', NULL),
(55, 57, 'What is the largest river in Bangladesh by water flow?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 15:55:28', '2025-06-22 15:55:28', 'medium', NULL),
(56, 58, 'Which district of Bangladesh is known for tea production?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 15:56:54', '2025-06-22 15:56:54', 'medium', NULL),
(57, 59, 'The Sundarbans is located in which part of Bangladesh?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 15:58:03', '2025-06-22 15:58:03', 'medium', NULL),
(58, 60, 'Which international organization helps Bangladesh with flood forecasting?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:00:10', '2025-06-22 16:00:10', 'medium', NULL),
(59, 61, 'When did the Language Movement take place in Bangladesh?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:01:23', '2025-06-22 16:01:23', 'medium', NULL),
(60, 62, 'Who was the first President of Bangladesh?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:03:08', '2025-06-22 16:03:08', 'medium', NULL),
(61, 63, 'What was the main cause of the 1971 Liberation War?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:04:28', '2025-06-22 16:04:28', 'medium', NULL),
(62, 64, 'Which sector played a key role in the 1971 war by broadcasting news globally?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:05:42', '2025-06-22 16:05:42', 'medium', NULL),
(63, 65, 'When was the Chittagong Armory Raid led by Masterda Surya Sen?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:06:38', '2025-06-22 16:06:38', 'medium', NULL),
(64, 66, 'Who is called the father of history?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:17:54', '2025-06-22 16:17:54', 'medium', NULL),
(65, 67, 'Which civilization is known as the earliest in human history?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:19:05', '2025-06-22 16:19:05', 'medium', NULL),
(66, 68, 'Where did the Indus Valley Civilization develop?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:19:56', '2025-06-22 16:19:56', 'medium', NULL),
(67, 69, 'The Great Pyramid of Giza is located in:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:21:35', '2025-06-22 16:21:35', 'medium', NULL),
(68, 70, 'Who was the founder of the Maurya Empire?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:22:29', '2025-06-22 16:22:29', 'medium', NULL),
(69, 71, 'Ashoka adopted which religion after the Kalinga War?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:23:45', '2025-06-22 16:23:45', 'medium', NULL),
(70, 72, 'The Rigveda is written in which language?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:24:33', '2025-06-22 16:24:33', 'medium', NULL),
(71, 73, 'The Battle of Plassey was fought in:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:25:26', '2025-06-22 16:25:26', 'medium', NULL),
(72, 74, 'Who was the last ruler of the Mughal dynasty?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:26:38', '2025-06-22 16:26:38', 'medium', NULL),
(73, 75, 'The capital of the Gupta Empire was:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:27:33', '2025-06-22 16:27:33', 'medium', NULL),
(74, 76, 'Alexander the Great came to India during the reign of:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:28:55', '2025-06-22 16:28:55', 'medium', NULL),
(75, 77, 'The term â€œNeolithicâ€ means:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:31:22', '2025-06-22 16:31:22', 'medium', NULL),
(76, 78, 'The earliest evidence of agriculture in India is found at:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:33:22', '2025-06-22 16:33:22', 'medium', NULL),
(77, 79, 'The Harappan civilization belonged to which age?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:34:13', '2025-06-22 16:34:13', 'medium', NULL),
(78, 80, 'Nalanda University was a center for:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:35:03', '2025-06-22 16:35:03', 'medium', NULL),
(79, 81, 'The Upanishads are books of:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:35:54', '2025-06-22 16:35:54', 'medium', NULL),
(80, 82, 'The language spoken by the Aryans was:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:37:42', '2025-06-22 16:37:42', 'medium', NULL),
(81, 83, 'The Ajanta Caves are located in:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 16:45:30', '2025-06-22 16:45:30', 'medium', NULL),
(82, 84, 'Who was the founder of Jainism?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:03:44', '2025-06-22 17:03:44', 'medium', NULL),
(83, 85, 'The â€˜Tripitakasâ€™ are sacred books of:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:04:58', '2025-06-22 17:04:58', 'medium', NULL),
(84, 86, 'Which site has the earliest remains of cotton?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:06:15', '2025-06-22 17:06:15', 'medium', NULL),
(85, 87, 'The term â€˜Mesolithicâ€™ refers to:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:07:23', '2025-06-22 17:07:23', 'medium', NULL),
(86, 88, 'Which ruler had a council of ministers called \\\'Mantriparishad\\\'?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:08:58', '2025-06-22 17:08:58', 'medium', NULL),
(87, 89, 'Vikram Samvat was started by:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 17:09:57', '2025-06-22 17:09:57', 'medium', NULL),
(88, 90, 'The number of significant figures in 0.0050 isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:06:17', '2025-06-22 18:06:17', 'medium', NULL),
(89, 91, 'Which one is not a pure substance?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:07:33', '2025-06-22 18:07:33', 'medium', NULL),
(90, 92, 'The correct order of increasing radii isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:08:50', '2025-06-22 18:08:50', 'medium', NULL),
(91, 93, 'Which element has the highest electronegativity?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:10:04', '2025-06-22 18:10:04', 'medium', NULL),
(92, 94, 'The shape of NHâ‚ƒ molecule isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:11:25', '2025-06-22 18:11:25', 'medium', NULL),
(93, 95, 'In the electrolysis of NaCl (aq), the product at the anode isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:12:42', '2025-06-22 18:12:42', 'medium', NULL),
(94, 96, 'Who first discovered the cell?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:15:12', '2025-06-22 18:15:12', 'medium', NULL),
(95, 97, 'Which organelle is called the \\\"powerhouse of the cell\\\"?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:16:18', '2025-06-22 18:16:18', 'medium', NULL),
(96, 98, 'Which of the following is not a polysaccharide?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:17:20', '2025-06-22 18:17:20', 'medium', NULL),
(97, 99, 'The basic unit of a protein isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:18:27', '2025-06-22 18:18:27', 'medium', NULL),
(98, 100, 'Enzymes are generallyâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:19:21', '2025-06-22 18:19:21', 'medium', NULL),
(99, 101, 'The enzyme that breaks down hydrogen peroxide (Hâ‚‚Oâ‚‚) isâ€”', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-22 18:20:19', '2025-06-22 18:20:19', 'medium', NULL),
(100, 102, 'What is the SI unit of force?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:09:55', '2025-06-23 03:09:55', 'medium', NULL),
(101, 103, 'If a car accelerates from rest at 2 m/sÂ² for 5 seconds, what is its final velocity?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:10:57', '2025-06-23 03:10:57', 'medium', NULL),
(102, 104, 'The momentum of an object depends on:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:13:13', '2025-06-23 03:13:13', 'medium', NULL),
(103, 105, 'The speed of sound is highest in:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:14:11', '2025-06-23 03:14:11', 'medium', NULL),
(104, 106, 'Which of the following is a sedimentary rock?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:23:49', '2025-06-23 03:23:49', 'medium', NULL),
(105, 107, 'The \\\"Ring of Fire\\\" is associated with:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:24:54', '2025-06-23 03:24:54', 'medium', NULL),
(106, 108, 'Which process is responsible for the formation of sand dunes?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:25:57', '2025-06-23 03:25:57', 'medium', NULL),
(107, 109, 'The equatorial region is characterized by:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:26:57', '2025-06-23 03:26:57', 'medium', NULL),
(108, 110, 'The Coriolis Effect causes winds to:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:27:54', '2025-06-23 03:27:54', 'medium', NULL),
(109, 111, 'Which gas is most responsible for global warming?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:28:55', '2025-06-23 03:28:55', 'medium', NULL),
(110, 112, 'The most densely populated continent is:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:30:26', '2025-06-23 03:30:26', 'medium', NULL),
(111, 113, 'The \\\"Demographic Transition Model\\\" stage with high birth rates and declining death rates is:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:31:29', '2025-06-23 03:31:29', 'medium', NULL),
(112, 114, 'Which factor is NOT a push factor for migration?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:32:28', '2025-06-23 03:32:28', 'medium', NULL),
(113, 115, 'The process of urban sprawl refers to:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:33:58', '2025-06-23 03:33:58', 'medium', NULL),
(114, 116, 'A megacity has a population of at least:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:35:18', '2025-06-23 03:35:18', 'medium', NULL),
(115, 117, 'Which urban model proposes concentric zones radiating from a central business district (CBD)?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:36:27', '2025-06-23 03:36:27', 'medium', NULL),
(116, 118, 'What is the primary function of the executive branch of government?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:38:59', '2025-06-23 03:38:59', 'medium', NULL),
(117, 119, 'Which country follows a unitary system of government?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:39:56', '2025-06-23 03:39:56', 'medium', NULL),
(118, 120, 'A democracy is best defined as:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:41:02', '2025-06-23 03:41:02', 'medium', NULL),
(119, 121, 'The highest law of a country is its:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:42:00', '2025-06-23 03:42:00', 'medium', NULL),
(120, 122, 'The first constitution of Bangladesh was adopted in:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:43:18', '2025-06-23 03:43:18', 'medium', NULL),
(121, 123, 'Which fundamental right ensures equality before the law?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:44:21', '2025-06-23 03:44:21', 'medium', NULL),
(122, 124, 'What is the main function of the World Trade Organization (WTO)?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:50:36', '2025-06-23 03:50:36', 'medium', NULL),
(123, 125, 'Which of the following is a key feature of a market economy?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:51:40', '2025-06-23 03:51:40', 'medium', NULL),
(124, 126, 'What is \\\"GDP\\\" an indicator of?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:53:16', '2025-06-23 03:53:16', 'medium', NULL),
(125, 127, 'What is the main role of the United Nations (UN)?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:55:39', '2025-06-23 03:55:39', 'medium', NULL),
(126, 128, 'Which of the following is a characteristic of good citizenship?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:57:40', '2025-06-23 03:57:40', 'medium', NULL),
(127, 129, 'What does \\\"sustainable development\\\" mean?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 03:59:11', '2025-06-23 03:59:11', 'medium', NULL),
(128, 130, 'What is the SI unit of electric charge?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:02:21', '2025-06-23 04:02:21', 'medium', NULL),
(129, 131, 'Which law states that the induced EMF is proportional to the rate of change of magnetic flux?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:03:43', '2025-06-23 04:03:43', 'medium', NULL),
(130, 132, 'Which principle explains why ships float in water?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:19:33', '2025-06-23 04:19:33', 'medium', NULL),
(131, 133, 'The first law of thermodynamics is a statement of:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:20:44', '2025-06-23 04:20:44', 'medium', NULL),
(132, 134, 'In a nuclear reactor, what is used to control the chain reaction?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:21:39', '2025-06-23 04:21:39', 'medium', NULL),
(133, 135, 'The phenomenon of splitting white light into its constituent colors is called:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:22:41', '2025-06-23 04:22:41', 'medium', NULL),
(134, 136, 'The rate of a chemical reaction depends on:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:25:19', '2025-06-23 04:25:19', 'medium', NULL),
(135, 137, 'Which of the following is a strong electrolyte?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:26:22', '2025-06-23 04:26:22', 'medium', NULL),
(136, 138, 'Which of the following is NOT a colligative property?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:27:25', '2025-06-23 04:27:25', 'medium', NULL),
(137, 139, 'The process of converting alkyl halides into alcohols is called:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:28:27', '2025-06-23 04:28:27', 'medium', NULL),
(138, 140, 'Which metal is used as a catalyst in the Haber process?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:30:57', '2025-06-23 04:30:57', 'medium', NULL),
(139, 141, 'What is the product of the reaction between a carboxylic acid and an alcohol?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:32:14', '2025-06-23 04:32:14', 'medium', NULL),
(140, 142, 'Which of the following is the site of photosynthesis in plant cells?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:34:18', '2025-06-23 04:34:18', 'medium', NULL),
(141, 143, 'What is the functional unit of the kidney?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:35:17', '2025-06-23 04:35:17', 'medium', NULL),
(142, 144, 'Which of the following is a sex-linked disease?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:36:27', '2025-06-23 04:36:27', 'medium', NULL),
(143, 145, 'The process by which plants lose water vapor is called:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:37:24', '2025-06-23 04:37:24', 'medium', NULL),
(144, 146, 'Which of the following is a greenhouse gas?', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-23 04:38:52', '2025-06-23 04:38:52', 'medium', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `question_options`
--

CREATE TABLE `question_options` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `option_text` text NOT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `order_index` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `question_options`
--

INSERT INTO `question_options` (`id`, `question_id`, `option_text`, `is_correct`, `order_index`) VALUES
(1, 1, 'aa', 0, 1),
(2, 1, 'bb', 1, 2),
(3, 1, 'cc', 0, 3),
(4, 1, 'dd', 0, 4),
(5, 2, 'Atlantic Ocean', 0, 1),
(6, 2, 'Indian Ocean', 0, 2),
(7, 2, 'Pacific Ocean', 1, 3),
(8, 2, 'Arctic Ocean', 0, 4),
(13, 4, 'Option A', 1, 1),
(14, 4, 'Option B', 0, 2),
(15, 4, 'Option C', 0, 3),
(16, 4, 'Option D', 0, 4),
(17, 5, '1', 0, 1),
(18, 5, '5', 0, 2),
(19, 5, '10', 1, 3),
(20, 5, '20', 0, 4),
(21, 6, 'Yellow', 0, 1),
(22, 6, 'Blue', 0, 2),
(23, 6, 'Red', 0, 3),
(24, 6, 'Green', 1, 4),
(25, 7, 'Green', 0, 1),
(26, 7, 'Red', 1, 2),
(27, 7, 'Blue', 0, 3),
(28, 7, 'Yellow', 0, 4),
(29, 8, 'A Day to Remember', 0, 1),
(30, 8, 'A Hole in the Fence', 1, 2),
(31, 8, 'People\\\'s Music', 0, 3),
(32, 8, 'Our Ethnic Friends', 0, 4),
(33, 9, 'People\\\'s Music', 0, 1),
(34, 9, 'A Hole in the Fence', 0, 2),
(35, 9, 'Our Ethnic Friends', 1, 3),
(36, 9, 'Bepin Choudhury\\\'s Lapse of Memory', 0, 4),
(37, 10, 'Bangladeshi folk music and its cultural significance', 1, 1),
(38, 10, 'The lifestyle and culture of river gypsies', 0, 2),
(39, 10, 'The story of Begum Rokeya', 0, 3),
(40, 10, 'The story of Zainul Abedin', 0, 4),
(41, 11, 'Summer', 0, 1),
(42, 11, 'Winter', 0, 2),
(43, 11, 'Rainy Season', 1, 3),
(44, 11, 'Autumn', 0, 4),
(45, 12, 'Rongpur', 0, 1),
(46, 12, 'Cumilla', 0, 2),
(47, 12, 'Dhaka', 0, 3),
(48, 12, 'Kishoreganj', 1, 4),
(49, 13, 'They are going to the park now.', 0, 1),
(50, 13, 'They were going to the park yesterday.', 0, 2),
(51, 13, 'They will go to the park tomorrow.', 0, 3),
(52, 13, 'All of the above.', 1, 4),
(53, 14, 'She is a more intelligent than him.', 0, 1),
(54, 14, 'She is more intelligent than him.', 1, 2),
(55, 14, 'She is the most intelligent than him.', 0, 3),
(56, 14, 'She is more intelligent then him.', 0, 4),
(57, 15, 'began', 1, 1),
(58, 15, 'begun', 0, 2),
(59, 15, 'begining', 0, 3),
(60, 15, 'begined', 0, 4),
(61, 16, 'to have an accident', 0, 1),
(62, 16, 'to wish someone good luck', 1, 2),
(63, 16, 'to tell someone to be quiet', 0, 3),
(64, 16, 'to fall down', 0, 4),
(65, 17, 'Oxygen', 0, 1),
(66, 17, 'Carbon dioxide', 1, 2),
(67, 17, 'Nitrogen', 0, 3),
(68, 17, 'Hydrogen', 0, 4),
(69, 18, 'Respiration', 0, 1),
(70, 18, 'Photosynthesis', 1, 2),
(71, 18, 'Digestion', 0, 3),
(72, 18, 'Excretion', 0, 4),
(73, 19, 'Joule', 0, 1),
(74, 19, 'Pascal', 0, 2),
(75, 19, 'Newton', 1, 3),
(76, 19, 'Watt', 0, 4),
(77, 20, 'Sydney', 0, 1),
(78, 20, 'Canberra', 1, 2),
(79, 20, 'Melbourne', 0, 3),
(80, 20, 'Perth', 0, 4),
(81, 21, 'Venus', 0, 1),
(82, 21, 'Jupiter', 0, 2),
(83, 21, 'Mars', 1, 3),
(84, 21, 'Saturn', 0, 4),
(85, 22, 'Shahada (Declaration of faith)', 0, 1),
(86, 22, 'Zakat (Charity)', 0, 2),
(87, 22, 'Salat (Prayer)', 0, 3),
(88, 22, 'Jihad (Striving or struggle)', 1, 4),
(89, 23, 'Masjid an-Nabawi', 0, 1),
(90, 23, 'Masjid al-Aqsa', 0, 2),
(91, 23, 'Masjid Quba', 1, 3),
(92, 23, 'Masjid al-Haram', 0, 4),
(93, 24, '1', 0, 1),
(94, 24, '2', 0, 2),
(95, 24, '3', 1, 3),
(96, 24, '7', 0, 4),
(97, 25, '-2', 0, 1),
(98, 25, '2', 1, 2),
(99, 25, '-3', 0, 3),
(100, 25, '3', 0, 4),
(101, 26, '2', 1, 1),
(102, 26, '3', 0, 2),
(103, 26, '6', 0, 3),
(104, 26, '-2', 0, 4),
(105, 27, '24 cm²', 1, 1),
(106, 27, '28 cm²', 0, 2),
(107, 27, '34 cm²', 0, 3),
(108, 27, '48 cm²', 0, 4),
(109, 28, 'Coal', 0, 1),
(110, 28, 'Solar Energy', 1, 2),
(111, 28, 'Natural Gas', 0, 3),
(112, 28, 'Petroleum', 0, 4),
(113, 29, 'KE = 1/2 mv²', 0, 1),
(114, 29, 'PE = mgh', 1, 2),
(115, 29, 'F = ma', 0, 3),
(116, 29, 'P = W/t', 0, 4),
(117, 30, '3 x 10^5 m/s', 0, 1),
(118, 30, '3 x 10^8 m/s', 1, 2),
(119, 30, '3.3 x 10^8 m/s', 0, 3),
(120, 30, '3 x 10^7 m/s', 0, 4),
(121, 31, 'Solid', 0, 1),
(122, 31, 'Liquid', 0, 2),
(123, 31, 'Gas', 0, 3),
(124, 31, 'Energy', 1, 4),
(125, 32, 'Molecule', 0, 1),
(126, 32, 'Atom', 1, 2),
(127, 32, 'Compound', 0, 3),
(128, 32, 'Mixture', 0, 4),
(129, 33, 'J.J. Thomson', 0, 1),
(130, 33, 'Ernest Rutherford', 0, 2),
(131, 33, 'John Dalton', 1, 3),
(132, 33, 'Niels Bohr', 0, 4),
(133, 34, 'Protection', 0, 1),
(134, 34, 'Transportation', 0, 2),
(135, 34, 'Photosynthesis', 1, 3),
(136, 34, 'Cell recognition.', 0, 4),
(137, 35, 'Transporting water', 1, 1),
(138, 35, 'Photosynthesis', 0, 2),
(139, 35, 'Transporting food', 0, 3),
(140, 35, 'Reproduction', 0, 4),
(141, 36, '4 m/s2 ', 0, 1),
(142, 36, '10 m/s2', 0, 2),
(143, 36, '8 m/s2', 1, 3),
(144, 36, '14 m/s2', 0, 4),
(145, 37, 'Decrease to g/2', 0, 1),
(146, 37, 'Increase to 4g', 1, 2),
(147, 37, 'Increase to 2g', 0, 3),
(148, 37, 'Remain unchanged', 0, 4),
(149, 38, 'Newly discovered', 0, 1),
(150, 38, 'Imported', 0, 2),
(151, 38, 'Harmless', 1, 3),
(152, 38, 'Effective', 0, 4),
(153, 39, 'Endeavour', 0, 1),
(154, 39, 'Influence', 0, 2),
(155, 39, 'Wealth', 1, 3),
(156, 39, 'Ostentation', 0, 4),
(157, 40, 'Mischievous', 0, 1),
(158, 40, 'Over-zealous', 0, 2),
(159, 40, 'Careless', 0, 3),
(160, 40, 'Thoughtful', 1, 4),
(161, 41, 'Explained', 0, 1),
(162, 41, 'Revealed', 0, 2),
(163, 41, 'Told', 1, 3),
(164, 41, 'Disclosed', 0, 4),
(165, 42, 'Stopped', 0, 1),
(166, 42, 'Retired', 0, 2),
(167, 42, 'Prevented', 0, 3),
(168, 42, 'Removed', 1, 4),
(169, 43, 'satisfying', 1, 1),
(170, 43, 'surprising', 0, 2),
(171, 43, 'pleasing', 0, 3),
(172, 43, 'happy', 0, 4),
(173, 44, 'range', 1, 1),
(174, 44, 'territory', 0, 2),
(175, 44, 'sphere', 0, 3),
(176, 44, 'scope', 0, 4),
(177, 45, ') contention', 0, 1),
(178, 45, 'friendship', 1, 2),
(179, 45, 'bondage', 0, 3),
(180, 45, 'understanding', 0, 4),
(181, 46, 'logical', 0, 1),
(182, 46, 'fresh', 1, 2),
(183, 46, 'optimistic', 0, 3),
(184, 46, 'wide', 0, 4),
(185, 47, 'aware', 0, 1),
(186, 47, 'watchful', 0, 2),
(187, 47, 'indifferent', 0, 3),
(188, 47, 'unmindful', 1, 4),
(189, 48, 'Cruelty', 1, 1),
(190, 48, 'Indignity', 0, 2),
(191, 48, 'Garbage', 0, 3),
(192, 48, 'Painfulness', 0, 4),
(193, 49, 'Audible', 0, 1),
(194, 49, 'Clear', 1, 2),
(195, 49, 'Distinct', 0, 3),
(196, 49, 'Reasonable', 0, 4),
(197, 50, 'Little', 1, 1),
(198, 50, 'Benefit', 0, 2),
(199, 50, 'Division', 0, 3),
(200, 50, 'End', 0, 4),
(201, 51, 'Anchor', 0, 1),
(202, 51, 'Chain', 0, 2),
(203, 51, 'Harness', 0, 3),
(204, 51, 'Restriction', 1, 4),
(205, 52, 'Chant', 0, 1),
(206, 52, 'Celebration', 0, 2),
(207, 52, 'Tribute', 1, 3),
(208, 52, 'Memorable', 0, 4),
(209, 53, 'Alien', 1, 1),
(210, 53, 'Local', 0, 2),
(211, 53, 'National', 0, 3),
(212, 53, 'Native', 0, 4),
(213, 54, 'Talkative', 1, 1),
(214, 54, 'Natural', 0, 2),
(215, 54, 'Effortless', 0, 3),
(216, 54, 'Random', 0, 4),
(217, 55, 'Padma', 0, 1),
(218, 55, 'Meghna', 1, 2),
(219, 55, 'Jamuna', 0, 3),
(220, 55, 'Brahmaputra', 0, 4),
(221, 56, 'Khulna', 0, 1),
(222, 56, 'Sylhet', 1, 2),
(223, 56, 'Rajshahi', 0, 3),
(224, 56, 'Barishal', 0, 4),
(225, 57, 'Northern region', 0, 1),
(226, 57, 'Eastern region', 0, 2),
(227, 57, 'Southwestern region', 1, 3),
(228, 57, 'Central region', 0, 4),
(229, 58, 'WHO', 0, 1),
(230, 58, 'UNICEF', 0, 2),
(231, 58, 'FFWC (Flood Forecasting and Warning Centre)', 1, 3),
(232, 58, 'IMF', 0, 4),
(233, 59, '1947', 0, 1),
(234, 59, '1952', 1, 2),
(235, 59, '1971', 0, 3),
(236, 59, '1969', 0, 4),
(237, 60, 'Sheikh Mujibur Rahman', 1, 1),
(238, 60, 'Ziaur Rahman', 0, 2),
(239, 60, 'Abu Sayeed Chowdhury', 0, 3),
(240, 60, 'Chowdhury   Rahman', 0, 4),
(241, 61, 'Economic disparity', 0, 1),
(242, 61, 'Political rights denial', 1, 2),
(243, 61, 'Religious conflict', 0, 3),
(244, 61, 'Cultural suppression', 0, 4),
(245, 62, 'Swadhin Bangla Betar Kendra', 1, 1),
(246, 62, 'BBC', 0, 2),
(247, 62, 'Akashvani', 0, 3),
(248, 62, 'Voice of America', 0, 4),
(249, 63, '1930', 1, 1),
(250, 63, '1942', 0, 2),
(251, 63, '1920', 0, 3),
(252, 63, '1919', 0, 4),
(253, 64, 'Aristotle', 0, 1),
(254, 64, 'Herodotus', 1, 2),
(255, 64, 'Socrates', 0, 3),
(256, 64, 'Plato', 0, 4),
(257, 65, 'Greek', 0, 1),
(258, 65, 'Roman', 0, 2),
(259, 65, 'Mesopotamian', 1, 3),
(260, 65, 'Chinese', 0, 4),
(261, 66, 'Egypt', 0, 1),
(262, 66, 'India', 1, 2),
(263, 66, 'China', 0, 3),
(264, 66, 'Iran', 0, 4),
(265, 67, 'Iraq', 0, 1),
(266, 67, 'Egypt', 1, 2),
(267, 67, 'Greece', 0, 3),
(268, 67, 'Mexico', 0, 4),
(269, 68, 'Ashoka', 0, 1),
(270, 68, 'Harshavardhana', 0, 2),
(271, 68, 'Chandragupta Maurya', 1, 3),
(272, 68, 'Bindusara', 0, 4),
(273, 69, 'Hinduism', 0, 1),
(274, 69, 'Jainism', 0, 2),
(275, 69, 'Christianity', 0, 3),
(276, 69, 'Buddhism', 1, 4),
(277, 70, 'Pali', 0, 1),
(278, 70, 'Sanskrit', 1, 2),
(279, 70, 'Persian', 0, 3),
(280, 70, 'Tamil', 0, 4),
(281, 71, '1757', 1, 1),
(282, 71, '1857', 0, 2),
(283, 71, '1800', 0, 3),
(284, 71, '1707', 0, 4),
(285, 72, 'Babur', 0, 1),
(286, 72, 'Shah Jahan', 0, 2),
(287, 72, 'Bahadur Shah II', 1, 3),
(288, 72, 'Aurangzeb', 0, 4),
(289, 73, 'Pataliputra', 1, 1),
(290, 73, 'Delhi', 0, 2),
(291, 73, 'Mathura', 0, 3),
(292, 73, 'Ujjain', 0, 4),
(293, 74, 'Ashoka', 0, 1),
(294, 74, 'Chandragupta Maurya', 0, 2),
(295, 74, 'Dhanananda', 1, 3),
(296, 74, 'Harsha', 0, 4),
(297, 75, 'Old Stone Age', 0, 1),
(298, 75, 'Middle Stone Age', 0, 2),
(299, 75, 'New Stone Age', 1, 3),
(300, 75, 'Bronze Age', 0, 4),
(301, 76, 'Harappa', 0, 1),
(302, 76, 'Mohenjo-daro', 0, 2),
(303, 76, 'Mehrgarh', 1, 3),
(304, 76, 'Lothal', 0, 4),
(305, 77, 'Iron Age', 0, 1),
(306, 77, 'Stone Age', 0, 2),
(307, 77, 'Bronze Age', 1, 3),
(308, 77, 'Modern Age', 0, 4),
(309, 78, 'Medicine', 0, 1),
(310, 78, 'Buddhism', 1, 2),
(311, 78, 'Astronomy', 0, 3),
(312, 78, 'Trade', 0, 4),
(313, 79, 'Rituals', 0, 1),
(314, 79, 'Law', 0, 2),
(315, 79, 'Philosophy', 1, 3),
(316, 79, 'Astronomy', 0, 4),
(317, 80, 'Pali', 0, 1),
(318, 80, 'Persian', 0, 2),
(319, 80, 'Sanskrit', 1, 3),
(320, 80, 'Tamil', 0, 4),
(321, 81, 'Maharashtra', 1, 1),
(322, 81, 'Bihar', 0, 2),
(323, 81, 'Gujarat', 0, 3),
(324, 81, 'Madhya Pradesh', 0, 4),
(325, 82, 'Mahavira', 1, 1),
(326, 82, 'Buddha', 0, 2),
(327, 82, 'Ashoka', 0, 3),
(328, 82, 'Chanakya', 0, 4),
(329, 83, 'Hinduism', 0, 1),
(330, 83, 'Buddhism', 1, 2),
(331, 83, 'Jainism', 0, 3),
(332, 83, 'Islam', 0, 4),
(333, 84, 'Mohenjo-daro', 0, 1),
(334, 84, 'Harappa', 0, 2),
(335, 84, 'Mehrgarh', 0, 3),
(336, 84, 'Mohenjo-daro', 1, 4),
(337, 85, 'Old Stone Age', 0, 1),
(338, 85, 'Middle Stone Age', 1, 2),
(339, 85, 'New Stone Age', 0, 3),
(340, 85, 'Iron Age', 0, 4),
(341, 86, 'Akbar', 0, 1),
(342, 86, 'Ashoka', 0, 2),
(343, 86, 'Chandragupta Maurya', 1, 3),
(344, 86, 'Harsha', 0, 4),
(345, 87, 'Vikramaditya', 1, 1),
(346, 87, 'Chandragupta', 0, 2),
(347, 87, 'Kanishka', 0, 3),
(348, 87, 'Bimbisara', 0, 4),
(349, 88, '1', 0, 1),
(350, 88, '2', 1, 2),
(351, 88, '3', 0, 3),
(352, 88, '4', 0, 4),
(353, 89, 'Oxygen', 0, 1),
(354, 89, 'Water', 0, 2),
(355, 89, 'Milk', 1, 3),
(356, 89, 'Carbon dioxid', 0, 4),
(357, 90, 'Na < Mg < Al', 0, 1),
(358, 90, 'Fâ» < OÂ²â» < NÂ³â»', 0, 2),
(359, 90, 'FeÂ³âº < FeÂ²âº < Fe', 1, 3),
(360, 90, 'Cl < Br < I', 0, 4),
(361, 91, 'F', 1, 1),
(362, 91, 'Cl', 0, 2),
(363, 91, 'O', 0, 3),
(364, 91, 'N', 0, 4),
(365, 92, 'Linear', 0, 1),
(366, 92, 'Trigonal planar', 0, 2),
(367, 92, 'Tetrahedral', 0, 3),
(368, 92, 'Pyramidal', 1, 4),
(369, 93, 'Hâ‚‚', 0, 1),
(370, 93, 'Oâ‚‚', 0, 2),
(371, 93, 'Clâ‚‚', 1, 3),
(372, 93, 'Na', 0, 4),
(373, 94, 'Robert Hooke', 1, 1),
(374, 94, 'Anton van Leeuwenhoek', 0, 2),
(375, 94, 'Schleiden & Schwann', 0, 3),
(376, 94, 'Rudolf Virchow', 0, 4),
(377, 95, 'Nucleus', 0, 1),
(378, 95, 'Mitochondria', 1, 2),
(379, 95, 'Golgi body', 0, 3),
(380, 95, 'Endoplasmic reticulum', 0, 4),
(381, 96, 'Starch (b) Glycogen', 0, 1),
(382, 96, 'Glycogen', 0, 2),
(383, 96, 'Cellulose', 0, 3),
(384, 96, 'Glucose', 1, 4),
(385, 97, 'Fatty acid', 0, 1),
(386, 97, 'Amino acid', 1, 2),
(387, 97, 'Nucleotide', 0, 3),
(388, 97, 'Monosaccharide', 0, 4),
(389, 98, 'Carbohydrates', 0, 1),
(390, 98, 'Proteins', 1, 2),
(391, 98, 'Lipids', 0, 3),
(392, 98, 'Nucleic acids', 0, 4),
(393, 99, 'Catalase', 1, 1),
(394, 99, 'Amylase', 0, 2),
(395, 99, 'Pepsin', 0, 3),
(396, 99, 'Lipase', 0, 4),
(397, 100, 'Joule', 0, 1),
(398, 100, 'Newton', 1, 2),
(399, 100, 'Watt', 0, 3),
(400, 100, 'Pascal', 0, 4),
(401, 101, '2 m/s', 0, 1),
(402, 101, '5 m/s', 0, 2),
(403, 101, '10 m/s', 1, 3),
(404, 101, '20 m/s', 0, 4),
(405, 102, 'Mass only', 0, 1),
(406, 102, 'Velocity only', 0, 2),
(407, 102, 'Both mass and velocity', 1, 3),
(408, 102, 'Neither mass nor velocity', 0, 4),
(409, 103, 'Vacuum', 0, 1),
(410, 103, 'Air', 0, 2),
(411, 103, 'Water', 0, 3),
(412, 103, 'Steel', 1, 4),
(413, 104, 'Granite', 0, 1),
(414, 104, 'Basalt', 0, 2),
(415, 104, 'Limestone', 1, 3),
(416, 104, 'Marble', 0, 4),
(417, 105, 'Earthquakes and volcanoes', 1, 1),
(418, 105, 'Tropical cyclones', 0, 2),
(419, 105, 'Desert formation', 0, 3),
(420, 105, 'Glacial erosion', 0, 4),
(421, 106, 'Fluvial deposition', 0, 1),
(422, 106, 'Aeolian transport', 1, 2),
(423, 106, 'Glacial erosion', 0, 3),
(424, 106, 'Volcanic activity', 0, 4),
(425, 107, 'Extreme temperature variations', 0, 1),
(426, 107, 'High rainfall year-round', 1, 2),
(427, 107, 'Permanent frost', 0, 3),
(428, 107, 'Low humidity', 0, 4),
(429, 108, 'Speed up in a straight line', 0, 1),
(430, 108, 'Curve left/right due to Earthâ€™s rotation', 1, 2),
(431, 108, 'Blow only at night', 0, 3),
(432, 108, 'Stop at the equator', 0, 4),
(433, 109, 'Oxygen (Oâ‚‚)', 0, 1),
(434, 109, 'Nitrogen (Nâ‚‚)', 0, 2),
(435, 109, 'Carbon dioxide (COâ‚‚)', 1, 3),
(436, 109, 'Argon (Ar)', 0, 4),
(437, 110, 'Africa', 0, 1),
(438, 110, 'Asia', 1, 2),
(439, 110, 'Europe', 0, 3),
(440, 110, 'South America', 0, 4),
(441, 111, 'Stage 1', 0, 1),
(442, 111, 'Stage 2', 1, 2),
(443, 111, 'Stage 2', 0, 3),
(444, 111, 'Stage 4', 0, 4),
(445, 112, 'War/conflict', 0, 1),
(446, 112, 'Job opportunities', 1, 2),
(447, 112, 'Natural disasters', 0, 3),
(448, 112, 'Political persecution', 0, 4),
(449, 113, 'Shrinking city populations', 0, 1),
(450, 113, 'Unplanned expansion of cities into rural areas', 1, 2),
(451, 113, 'Decline of industrial zones', 0, 3),
(452, 113, 'Growth of slums only', 0, 4),
(453, 114, '1 million', 0, 1),
(454, 114, '5 million', 0, 2),
(455, 114, '10 million', 1, 3),
(456, 114, '50 million', 0, 4),
(457, 115, 'Hoytâ€™s Sector Model', 0, 1),
(458, 115, 'Burgessâ€™s Concentric Zone Model', 1, 2),
(459, 115, 'Multiple Nuclei Model', 0, 3),
(460, 115, 'Von ThÃ¼nenâ€™s Model', 0, 4),
(461, 116, 'Making laws', 0, 1),
(462, 116, 'Enforcing laws', 1, 2),
(463, 116, 'Interpreting laws', 0, 3),
(464, 116, 'Amending laws', 0, 4),
(465, 117, 'USA', 0, 1),
(466, 117, 'India', 0, 2),
(467, 117, 'Bangladesh', 1, 3),
(468, 117, 'Germany', 0, 4),
(469, 118, 'Rule by a king', 0, 1),
(470, 118, 'Rule by the military', 0, 2),
(471, 118, 'Rule by the people', 1, 3),
(472, 118, 'Rule by a single party', 0, 4),
(473, 119, 'Penal Code', 0, 1),
(474, 119, 'Constitution', 1, 2),
(475, 119, 'Parliament Act', 0, 3),
(476, 119, 'International Treaty', 0, 4),
(477, 120, '1971', 0, 1),
(478, 120, '1972', 1, 2),
(479, 120, '1975', 0, 3),
(480, 120, '1991', 0, 4),
(481, 121, 'Right to Education', 0, 1),
(482, 121, 'Right to Property', 0, 2),
(483, 121, 'Right to Equality', 1, 3),
(484, 121, 'Right to Religion', 0, 4),
(485, 122, 'To provide military aid', 0, 1),
(486, 122, 'To regulate global trade rules', 1, 2),
(487, 122, 'To control national economies', 0, 3),
(488, 122, 'To issue global currency', 0, 4),
(489, 123, 'Government controls all production', 0, 1),
(490, 123, 'Private ownership and free competition', 1, 2),
(491, 123, 'Equal distribution of wealth', 0, 3),
(492, 123, 'No role for supply and demand', 0, 4),
(493, 124, 'A countryâ€™s total military power', 0, 1),
(494, 124, 'A countryâ€™s total economic output', 1, 2),
(495, 124, 'A countryâ€™s population growth', 0, 3),
(496, 124, 'A countryâ€™s education level', 0, 4),
(497, 125, 'To enforce global laws', 0, 1),
(498, 125, 'To promote international peace and cooperation', 1, 2),
(499, 125, 'To control world trade', 0, 3),
(500, 125, 'To replace national governments', 0, 4),
(501, 126, 'Avoiding taxes', 0, 1),
(502, 126, 'Obeying laws and voting', 1, 2),
(503, 126, 'Ignoring social issues', 0, 3),
(504, 126, 'Supporting corruption', 0, 4),
(505, 127, 'Economic growth at any cost', 0, 1),
(506, 127, 'Rapid industrialization without environmental concerns', 0, 2),
(507, 127, 'Dependence on foreign aid', 0, 3),
(508, 127, 'Development that meets present needs without harming future generations', 1, 4),
(509, 128, 'Ampere', 0, 1),
(510, 128, 'Coulomb', 1, 2),
(511, 128, 'Ohm', 0, 3),
(512, 128, 'Volt', 0, 4),
(513, 129, 'Ohmâ€™s Law', 0, 1),
(514, 129, 'Lenzâ€™s Law', 0, 2),
(515, 129, 'Coulombâ€™s Law', 0, 3),
(516, 129, 'Faradayâ€™s Law of Electromagnetic Induction', 1, 4),
(517, 130, 'Pascalâ€™s Principle', 0, 1),
(518, 130, 'Bernoulliâ€™s Principle', 0, 2),
(519, 130, 'Archimedesâ€™ Principle', 1, 3),
(520, 130, 'Hookeâ€™s Law', 0, 4),
(521, 131, 'Conservation of energy', 1, 1),
(522, 131, 'Entropy always increases', 0, 2),
(523, 131, 'Heat flows from cold to hot', 0, 3),
(524, 131, 'Work done in adiabatic processes', 0, 4),
(525, 132, 'Moderator', 0, 1),
(526, 132, 'Fuel rods', 0, 2),
(527, 132, 'Coolant', 0, 3),
(528, 132, 'Control rods', 1, 4),
(529, 133, 'Reflection', 0, 1),
(530, 133, 'Refraction', 0, 2),
(531, 133, 'Dispersion', 1, 3),
(532, 133, 'Diffraction', 0, 4),
(533, 134, 'Temperature only', 0, 1),
(534, 134, 'Concentration of reactants only', 0, 2),
(535, 134, 'Catalyst presence only', 0, 3),
(536, 134, 'All of the above', 1, 4),
(537, 135, 'Sodium chloride', 1, 1),
(538, 135, 'Ethanol', 0, 2),
(539, 135, 'Acetic acid', 0, 3),
(540, 135, 'Sugar solution', 0, 4),
(541, 136, 'Density', 1, 1),
(542, 136, 'Freezing point depression Freezing point depression', 0, 2),
(543, 136, 'Boiling point elevation', 0, 3),
(544, 136, 'Osmotic pressure', 0, 4),
(545, 137, 'Hydrolysis', 1, 1),
(546, 137, 'Esterification', 0, 2),
(547, 137, 'Hydrogenation', 0, 3),
(548, 137, 'Dehydration', 0, 4),
(549, 138, 'Iron', 1, 1),
(550, 138, 'Copper', 0, 2),
(551, 138, 'Nickel', 0, 3),
(552, 138, 'Platinum', 0, 4),
(553, 139, 'Ester', 1, 1),
(554, 139, 'Esters', 0, 2),
(555, 139, 'Aldehyde', 0, 3),
(556, 139, 'Ketone', 0, 4),
(557, 140, 'Chloroplast', 1, 1),
(558, 140, 'Mitochondria', 0, 2),
(559, 140, 'Nucleus', 0, 3),
(560, 140, 'Ribosome', 0, 4),
(561, 141, 'Nephron', 1, 1),
(562, 141, 'Neuron', 0, 2),
(563, 141, 'Alveoli', 0, 3),
(564, 141, 'Villi', 0, 4),
(565, 142, 'Tuberculosis', 0, 1),
(566, 142, 'Hemophilia', 1, 2),
(567, 142, 'Diabetes', 0, 3),
(568, 142, 'Asthma', 0, 4),
(569, 143, 'Transpiration', 1, 1),
(570, 143, 'Respiration', 0, 2),
(571, 143, 'Photosynthesis', 0, 3),
(572, 143, 'Guttation', 0, 4),
(573, 144, 'Carbon dioxide', 1, 1),
(574, 144, 'Nitrogen', 0, 2),
(575, 144, 'Hydrogen', 0, 3),
(576, 144, 'Oxygen', 0, 4);

-- --------------------------------------------------------

--
-- Table structure for table `question_tags`
--

CREATE TABLE `question_tags` (
  `id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `tag` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive','pending') DEFAULT 'active',
  `category_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `event_id` int(11) DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `title`, `description`, `status`, `category_id`, `class_id`, `subject_id`, `event_id`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'what is the right ans?', NULL, 'active', NULL, NULL, NULL, NULL, NULL, '2025-06-01 10:37:32', '2025-06-01 10:37:32'),
(2, 'What is the largest ocean in the world?', NULL, 'active', 1, 1, 4, NULL, NULL, '2025-06-01 10:37:32', '2025-06-21 18:26:14'),
(4, '', NULL, 'active', 3, 3, 6, 2, NULL, '2025-06-02 19:35:56', '2025-06-02 19:35:56'),
(6, 'Test Question?', 'Test Question?', 'active', 1, NULL, NULL, NULL, NULL, '2025-06-03 04:35:04', '2025-06-03 04:35:04'),
(7, 'How many fingers we have?', 'How many fingers we have?', 'active', 4, 4, 7, NULL, 1, '2025-06-03 04:42:51', '2025-06-03 04:42:51'),
(8, 'Apple is', 'Apple is', 'active', 4, 4, 7, NULL, 1, '2025-06-03 04:46:23', '2025-06-03 04:46:23'),
(9, 'Cherry is', 'Cherry is', 'active', 4, 4, 7, NULL, 1, '2025-06-03 04:47:15', '2025-06-03 04:47:15'),
(10, 'What is the title of Unit 7.2.2 in the Class 8 Eng...', 'What is the title of Unit 7.2.2 in the Class 8 Eng...', 'active', 1, 1, 8, NULL, 2, '2025-06-21 16:54:34', '2025-06-21 16:54:34'),
(11, 'What is the main topic of Unit 1, Lesson 3 in the ...', 'What is the main topic of Unit 1, Lesson 3 in the ...', 'active', 1, 1, 8, NULL, 2, '2025-06-21 16:55:32', '2025-06-21 16:55:32'),
(12, 'What is the main focus of Unit 1, Lesson 1 in the ...', 'What is the main focus of Unit 1, Lesson 1 in the ...', 'active', 1, 1, 8, NULL, 2, '2025-06-21 16:58:51', '2025-06-21 16:58:51'),
(13, 'What does \\\"Monsoon\\\" refer to in the passage abou...', 'What does \\\"Monsoon\\\" refer to in the passage abou...', 'active', 1, 1, 8, NULL, 2, '2025-06-21 16:59:41', '2025-06-21 16:59:41'),
(14, 'Where was Begum Rokeya born?', 'Where was Begum Rokeya born?', 'active', 1, 1, 8, NULL, 2, '2025-06-21 17:00:24', '2025-06-21 17:00:24'),
(15, 'Which of the following sentences uses the correct ...', 'Which of the following sentences uses the correct ...', 'active', 1, 1, 9, NULL, 2, '2025-06-21 17:07:28', '2025-06-21 17:07:28'),
(16, 'Identify the sentence with the correct use of an a...', 'Identify the sentence with the correct use of an a...', 'active', 1, 1, 9, NULL, 2, '2025-06-21 17:09:00', '2025-06-21 17:09:00'),
(17, 'What is the past participle of the verb \\\"begin\\\"?', 'What is the past participle of the verb \\\"begin\\\"?', 'active', 1, 1, 9, NULL, 2, '2025-06-21 18:04:43', '2025-06-21 18:04:43'),
(18, 'What does the idiom \\\"break a leg\\\" mean?', 'What does the idiom \\\"break a leg\\\" mean?', 'active', 1, 1, 9, NULL, 2, '2025-06-21 18:05:48', '2025-06-21 18:05:48'),
(19, 'Which gas is primarily responsible for the greenho...', 'Which gas is primarily responsible for the greenho...', 'active', 1, 1, 10, NULL, 2, '2025-06-21 18:17:15', '2025-06-21 18:17:15'),
(20, 'What is the name of the process by which plants ma...', 'What is the name of the process by which plants ma...', 'active', 1, 1, 10, NULL, 2, '2025-06-21 18:18:11', '2025-06-21 18:18:11'),
(21, 'What is the SI unit of force?', 'What is the SI unit of force?', 'active', 1, 1, 10, NULL, 2, '2025-06-21 18:19:21', '2025-06-21 18:19:21'),
(22, 'What is the capital of Australia?', 'What is the capital of Australia?', 'active', 1, 1, 4, NULL, 2, '2025-06-21 18:20:37', '2025-06-21 18:20:37'),
(23, 'Which planet is known as the \\\"Red Planet\\\"?', 'Which planet is known as the \\\"Red Planet\\\"?', 'active', 1, 1, 4, NULL, 2, '2025-06-21 18:21:36', '2025-06-21 18:21:36'),
(24, 'Which of the following is NOT one of the Five Pill...', 'Which of the following is NOT one of the Five Pill...', 'active', 1, 1, 11, NULL, 2, '2025-06-21 18:30:21', '2025-06-21 18:30:21'),
(25, 'What is the name of the first mosque built in Isla...', 'What is the name of the first mosque built in Isla...', 'active', 1, 1, 11, NULL, 2, '2025-06-21 18:31:40', '2025-06-21 18:31:40'),
(26, 'What is the degree of the polynomial 5x³ - 4x² +...', 'What is the degree of the polynomial 5x³ - 4x² +...', 'active', 2, 2, 5, NULL, 2, '2025-06-21 18:33:06', '2025-06-21 18:33:06'),
(27, 'If (x+1) is a factor of the polynomial 2x² + kx, ...', 'If (x+1) is a factor of the polynomial 2x² + kx, ...', 'active', 2, 2, 5, NULL, 2, '2025-06-21 18:41:13', '2025-06-21 18:41:13'),
(28, 'If (2, 3) is a solution of the equation 3x + ay = ...', 'If (2, 3) is a solution of the equation 3x + ay = ...', 'active', 2, 2, 5, NULL, 2, '2025-06-21 18:42:08', '2025-06-21 18:42:08'),
(29, 'The sides of a triangle are 6cm, 8cm, and 10cm. Wh...', 'The sides of a triangle are 6cm, 8cm, and 10cm. Wh...', 'active', 2, 2, 5, NULL, 2, '2025-06-21 18:43:01', '2025-06-21 18:43:01'),
(30, 'Which of the following is a renewable energy sourc...', 'Which of the following is a renewable energy sourc...', 'active', 2, 2, 17, NULL, 2, '2025-06-21 18:44:33', '2025-06-21 18:44:33'),
(31, 'The formula for potential energy is:', 'The formula for potential energy is:', 'active', 2, 2, 17, NULL, 2, '2025-06-21 18:45:25', '2025-06-21 18:45:25'),
(32, 'What is the speed of light in a vacuum?', 'What is the speed of light in a vacuum?', 'active', 2, 2, 17, NULL, 2, '2025-06-21 18:46:17', '2025-06-21 18:46:17'),
(33, 'Which of the following is NOT a form of matter?', 'Which of the following is NOT a form of matter?', 'active', 2, 2, 18, NULL, 2, '2025-06-21 18:48:02', '2025-06-21 18:48:02'),
(34, 'What is the basic unit of matter?', 'What is the basic unit of matter?', 'active', 2, 2, 18, NULL, 2, '2025-06-21 18:49:58', '2025-06-21 18:49:58'),
(35, 'What is the name of the scientist who proposed the...', 'What is the name of the scientist who proposed the...', 'active', 2, 2, 18, NULL, 2, '2025-06-21 18:52:52', '2025-06-21 18:52:52'),
(36, 'Which of the following is NOT a function of the ce...', 'Which of the following is NOT a function of the ce...', 'active', 2, 2, 19, NULL, 2, '2025-06-21 18:54:18', '2025-06-21 18:54:18'),
(37, 'What is the primary function of the xylem in plant...', 'What is the primary function of the xylem in plant...', 'active', 2, 2, 19, NULL, 2, '2025-06-21 18:55:23', '2025-06-21 18:55:23'),
(38, 'An object moves with a velocity v=2t2−4tv=2t2−...', 'An object moves with a velocity v=2t2−4tv=2t2−...', 'active', 3, 3, 6, NULL, 2, '2025-06-21 19:01:43', '2025-06-21 19:01:43'),
(39, 'If the radius of Earth were reduced to half while ...', 'If the radius of Earth were reduced to half while ...', 'active', 3, 3, 6, NULL, 2, '2025-06-21 19:02:59', '2025-06-21 19:02:59'),
(40, 'The drugs were innocuous and had no side effect.', 'The drugs were innocuous and had no side effect.', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:20:02', '2025-06-22 07:20:02'),
(41, 'The affluence of most visiting Arabs is astonishin...', 'The affluence of most visiting Arabs is astonishin...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:22:26', '2025-06-22 07:22:26'),
(42, 'He was a contemplative person.', 'He was a contemplative person.', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:23:45', '2025-06-22 07:23:45'),
(43, 'The story which Gaurav narrated was very exciting.', 'The story which Gaurav narrated was very exciting.', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:26:07', '2025-06-22 07:26:07'),
(44, 'He was dismissed from service because they found h...', 'He was dismissed from service because they found h...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:27:34', '2025-06-22 07:27:34'),
(45, 'It is so gratifying to know that there are not man...', 'It is so gratifying to know that there are not man...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:32:10', '2025-06-22 07:32:10'),
(46, 'The cordial talks between the two foreign minister...', 'The cordial talks between the two foreign minister...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:33:16', '2025-06-22 07:33:16'),
(47, 'We should always try to maintain and promote commu...', 'We should always try to maintain and promote commu...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:34:45', '2025-06-22 07:34:45'),
(48, 'He has won great admiration amongst his students b...', 'He has won great admiration amongst his students b...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:36:03', '2025-06-22 07:36:03'),
(49, 'The novel was so interesting that I was oblivious ...', 'The novel was so interesting that I was oblivious ...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:37:39', '2025-06-22 07:37:39'),
(50, 'Choose the correct synonym of the given word:', 'Choose the correct synonym of the given word:', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:40:22', '2025-06-22 07:40:22'),
(51, 'Choose the correct synonym of the given word:\\r\\nL...', 'Choose the correct synonym of the given word:\\r\\nL...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:45:35', '2025-06-22 07:45:35'),
(52, 'Choose the correct synonym of the given word:\\r\\nM...', 'Choose the correct synonym of the given word:\\r\\nM...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:47:34', '2025-06-22 07:47:34'),
(53, 'Choose the correct synonym of the given word:\\r\\nD...', 'Choose the correct synonym of the given word:\\r\\nD...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:48:47', '2025-06-22 07:48:47'),
(54, 'Choose the correct synonym of the given word:\\r\\nE...', 'Choose the correct synonym of the given word:\\r\\nE...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:50:05', '2025-06-22 07:50:05'),
(55, 'Choose the correct synonym of the given word:\\r\\nF...', 'Choose the correct synonym of the given word:\\r\\nF...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:55:44', '2025-06-22 07:55:44'),
(56, 'Choose the correct synonym of the given word:\\r\\nV...', 'Choose the correct synonym of the given word:\\r\\nV...', 'active', 2, 9, 13, NULL, 2, '2025-06-22 07:59:31', '2025-06-22 07:59:31'),
(57, 'What is the largest river in Bangladesh by water f...', 'What is the largest river in Bangladesh by water f...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 15:55:28', '2025-06-22 15:55:28'),
(58, 'Which district of Bangladesh is known for tea prod...', 'Which district of Bangladesh is known for tea prod...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 15:56:54', '2025-06-22 15:56:54'),
(59, 'The Sundarbans is located in which part of Banglad...', 'The Sundarbans is located in which part of Banglad...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 15:58:03', '2025-06-22 15:58:03'),
(60, 'Which international organization helps Bangladesh ...', 'Which international organization helps Bangladesh ...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:00:10', '2025-06-22 16:00:10'),
(61, 'When did the Language Movement take place in Bangl...', 'When did the Language Movement take place in Bangl...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:01:23', '2025-06-22 16:01:23'),
(62, 'Who was the first President of Bangladesh?', 'Who was the first President of Bangladesh?', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:03:08', '2025-06-22 16:03:08'),
(63, 'What was the main cause of the 1971 Liberation War...', 'What was the main cause of the 1971 Liberation War...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:04:28', '2025-06-22 16:04:28'),
(64, 'Which sector played a key role in the 1971 war by ...', 'Which sector played a key role in the 1971 war by ...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:05:42', '2025-06-22 16:05:42'),
(65, 'When was the Chittagong Armory Raid led by Masterd...', 'When was the Chittagong Armory Raid led by Masterd...', 'active', 2, 5, 15, NULL, 2, '2025-06-22 16:06:38', '2025-06-22 16:06:38'),
(66, 'Who is called the father of history?', 'Who is called the father of history?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:17:54', '2025-06-22 16:17:54'),
(67, 'Which civilization is known as the earliest in hum...', 'Which civilization is known as the earliest in hum...', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:19:05', '2025-06-22 16:19:05'),
(68, 'Where did the Indus Valley Civilization develop?', 'Where did the Indus Valley Civilization develop?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:19:56', '2025-06-22 16:19:56'),
(69, 'The Great Pyramid of Giza is located in:', 'The Great Pyramid of Giza is located in:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:21:35', '2025-06-22 16:21:35'),
(70, 'Who was the founder of the Maurya Empire?', 'Who was the founder of the Maurya Empire?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:22:29', '2025-06-22 16:22:29'),
(71, 'Ashoka adopted which religion after the Kalinga Wa...', 'Ashoka adopted which religion after the Kalinga Wa...', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:23:45', '2025-06-22 16:23:45'),
(72, 'The Rigveda is written in which language?', 'The Rigveda is written in which language?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:24:33', '2025-06-22 16:24:33'),
(73, 'The Battle of Plassey was fought in:', 'The Battle of Plassey was fought in:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:25:26', '2025-06-22 16:25:26'),
(74, 'Who was the last ruler of the Mughal dynasty?', 'Who was the last ruler of the Mughal dynasty?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:26:38', '2025-06-22 16:26:38'),
(75, 'The capital of the Gupta Empire was:', 'The capital of the Gupta Empire was:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:27:33', '2025-06-22 16:27:33'),
(76, 'Alexander the Great came to India during the reign...', 'Alexander the Great came to India during the reign...', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:28:55', '2025-06-22 16:28:55'),
(77, 'The term â€œNeolithicâ€ means:', 'The term â€œNeolithicâ€ means:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:31:22', '2025-06-22 16:31:22'),
(78, 'The earliest evidence of agriculture in India is f...', 'The earliest evidence of agriculture in India is f...', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:33:22', '2025-06-22 16:33:22'),
(79, 'The Harappan civilization belonged to which age?', 'The Harappan civilization belonged to which age?', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:34:13', '2025-06-22 16:34:13'),
(80, 'Nalanda University was a center for:', 'Nalanda University was a center for:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:35:03', '2025-06-22 16:35:03'),
(81, 'The Upanishads are books of:', 'The Upanishads are books of:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:35:54', '2025-06-22 16:35:54'),
(82, 'The language spoken by the Aryans was:', 'The language spoken by the Aryans was:', 'active', 3, 6, 20, NULL, 2, '2025-06-22 16:37:42', '2025-06-22 16:37:42'),
(83, 'The Ajanta Caves are located in:', 'The Ajanta Caves are located in:', 'active', 3, 7, 23, NULL, 2, '2025-06-22 16:45:30', '2025-06-22 16:45:30'),
(84, 'Who was the founder of Jainism?', 'Who was the founder of Jainism?', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:03:44', '2025-06-22 17:03:44'),
(85, 'The â€˜Tripitakasâ€™ are sacred books of:', 'The â€˜Tripitakasâ€™ are sacred books of:', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:04:58', '2025-06-22 17:04:58'),
(86, 'Which site has the earliest remains of cotton?', 'Which site has the earliest remains of cotton?', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:06:15', '2025-06-22 17:06:15'),
(87, 'The term â€˜Mesolithicâ€™ refers to:', 'The term â€˜Mesolithicâ€™ refers to:', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:07:23', '2025-06-22 17:07:23'),
(88, 'Which ruler had a council of ministers called \\\'Ma...', 'Which ruler had a council of ministers called \\\'Ma...', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:08:58', '2025-06-22 17:08:58'),
(89, 'Vikram Samvat was started by:', 'Vikram Samvat was started by:', 'active', 3, 7, 23, NULL, 2, '2025-06-22 17:09:57', '2025-06-22 17:09:57'),
(90, 'The number of significant figures in 0.0050 isâ€”', 'The number of significant figures in 0.0050 isâ€”', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:06:17', '2025-06-22 18:06:17'),
(91, 'Which one is not a pure substance?', 'Which one is not a pure substance?', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:07:33', '2025-06-22 18:07:33'),
(92, 'The correct order of increasing radii isâ€”', 'The correct order of increasing radii isâ€”', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:08:50', '2025-06-22 18:08:50'),
(93, 'Which element has the highest electronegativity?', 'Which element has the highest electronegativity?', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:10:04', '2025-06-22 18:10:04'),
(94, 'The shape of NHâ‚ƒ molecule isâ€”', 'The shape of NHâ‚ƒ molecule isâ€”', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:11:25', '2025-06-22 18:11:25'),
(95, 'In the electrolysis of NaCl (aq), the product at t...', 'In the electrolysis of NaCl (aq), the product at t...', 'active', 3, 3, 24, NULL, 2, '2025-06-22 18:12:42', '2025-06-22 18:12:42'),
(96, 'Who first discovered the cell?', 'Who first discovered the cell?', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:15:12', '2025-06-22 18:15:12'),
(97, 'Which organelle is called the \\\"powerhouse of the ...', 'Which organelle is called the \\\"powerhouse of the ...', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:16:18', '2025-06-22 18:16:18'),
(98, 'Which of the following is not a polysaccharide?', 'Which of the following is not a polysaccharide?', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:17:20', '2025-06-22 18:17:20'),
(99, 'The basic unit of a protein isâ€”', 'The basic unit of a protein isâ€”', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:18:27', '2025-06-22 18:18:27'),
(100, 'Enzymes are generallyâ€”', 'Enzymes are generallyâ€”', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:19:21', '2025-06-22 18:19:21'),
(101, 'The enzyme that breaks down hydrogen peroxide (Hâ‚...', 'The enzyme that breaks down hydrogen peroxide (Hâ‚...', 'active', 3, 3, 25, NULL, 2, '2025-06-22 18:20:19', '2025-06-22 18:20:19'),
(102, 'What is the SI unit of force?-1...', 'What is the SI unit of force?-1...', 'active', 3, 3, 6, NULL, 2, '2025-06-23 03:09:55', '2025-06-23 03:09:55'),
(103, 'If a car accelerates from rest at 2 m/sÂ² for 5 se...', 'If a car accelerates from rest at 2 m/sÂ² for 5 se...', 'active', 3, 3, 6, NULL, 2, '2025-06-23 03:10:57', '2025-06-23 03:10:57'),
(104, 'The momentum of an object depends on:', 'The momentum of an object depends on:', 'active', 3, 3, 6, NULL, 2, '2025-06-23 03:13:13', '2025-06-23 03:13:13'),
(105, 'The speed of sound is highest in:', 'The speed of sound is highest in:', 'active', 3, 3, 6, NULL, 2, '2025-06-23 03:14:11', '2025-06-23 03:14:11'),
(106, 'Which of the following is a sedimentary rock?', 'Which of the following is a sedimentary rock?', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:23:49', '2025-06-23 03:23:49'),
(107, 'The \\\"Ring of Fire\\\" is associated with:', 'The \\\"Ring of Fire\\\" is associated with:', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:24:54', '2025-06-23 03:24:54'),
(108, 'Which process is responsible for the formation of ...', 'Which process is responsible for the formation of ...', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:25:57', '2025-06-23 03:25:57'),
(109, 'The equatorial region is characterized by:', 'The equatorial region is characterized by:', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:26:57', '2025-06-23 03:26:57'),
(110, 'The Coriolis Effect causes winds to:', 'The Coriolis Effect causes winds to:', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:27:54', '2025-06-23 03:27:54'),
(111, 'Which gas is most responsible for global warming?', 'Which gas is most responsible for global warming?', 'active', 3, 6, 41, NULL, 2, '2025-06-23 03:28:55', '2025-06-23 03:28:55'),
(112, 'The most densely populated continent is:', 'The most densely populated continent is:', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:30:26', '2025-06-23 03:30:26'),
(113, 'The \\\"Demographic Transition Model\\\" stage with hi...', 'The \\\"Demographic Transition Model\\\" stage with hi...', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:31:29', '2025-06-23 03:31:29'),
(114, 'Which factor is NOT a push factor for migration?', 'Which factor is NOT a push factor for migration?', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:32:28', '2025-06-23 03:32:28'),
(115, 'The process of urban sprawl refers to:', 'The process of urban sprawl refers to:', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:33:58', '2025-06-23 03:33:58'),
(116, 'A megacity has a population of at least:', 'A megacity has a population of at least:', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:35:18', '2025-06-23 03:35:18'),
(117, 'Which urban model proposes concentric zones radiat...', 'Which urban model proposes concentric zones radiat...', 'active', 3, 7, 42, NULL, 2, '2025-06-23 03:36:27', '2025-06-23 03:36:27'),
(118, 'What is the primary function of the executive bran...', 'What is the primary function of the executive bran...', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:38:59', '2025-06-23 03:38:59'),
(119, 'Which country follows a unitary system of governme...', 'Which country follows a unitary system of governme...', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:39:56', '2025-06-23 03:39:56'),
(120, 'A democracy is best defined as:', 'A democracy is best defined as:', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:41:02', '2025-06-23 03:41:02'),
(121, 'The highest law of a country is its:', 'The highest law of a country is its:', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:42:00', '2025-06-23 03:42:00'),
(122, 'The first constitution of Bangladesh was adopted i...', 'The first constitution of Bangladesh was adopted i...', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:43:18', '2025-06-23 03:43:18'),
(123, 'Which fundamental right ensures equality before th...', 'Which fundamental right ensures equality before th...', 'active', 3, 6, 43, NULL, 2, '2025-06-23 03:44:21', '2025-06-23 03:44:21'),
(124, 'What is the main function of the World Trade Organ...', 'What is the main function of the World Trade Organ...', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:50:36', '2025-06-23 03:50:36'),
(125, 'Which of the following is a key feature of a marke...', 'Which of the following is a key feature of a marke...', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:51:40', '2025-06-23 03:51:40'),
(126, 'What is \\\"GDP\\\" an indicator of?', 'What is \\\"GDP\\\" an indicator of?', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:53:16', '2025-06-23 03:53:16'),
(127, 'What is the main role of the United Nations (UN)?', 'What is the main role of the United Nations (UN)?', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:55:39', '2025-06-23 03:55:39'),
(128, 'Which of the following is a characteristic of good...', 'Which of the following is a characteristic of good...', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:57:40', '2025-06-23 03:57:40'),
(129, 'What does \\\"sustainable development\\\" mean?', 'What does \\\"sustainable development\\\" mean?', 'active', 3, 7, 44, NULL, 2, '2025-06-23 03:59:11', '2025-06-23 03:59:11'),
(130, 'What is the SI unit of electric charge?', 'What is the SI unit of electric charge?', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:02:21', '2025-06-23 04:02:21'),
(131, 'Which law states that the induced EMF is proportio...', 'Which law states that the induced EMF is proportio...', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:03:43', '2025-06-23 04:03:43'),
(132, 'Which principle explains why ships float in water?', 'Which principle explains why ships float in water?', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:19:33', '2025-06-23 04:19:33'),
(133, 'The first law of thermodynamics is a statement of:', 'The first law of thermodynamics is a statement of:', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:20:44', '2025-06-23 04:20:44'),
(134, 'In a nuclear reactor, what is used to control the ...', 'In a nuclear reactor, what is used to control the ...', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:21:39', '2025-06-23 04:21:39'),
(135, 'The phenomenon of splitting white light into its c...', 'The phenomenon of splitting white light into its c...', 'active', 3, 8, 26, NULL, 2, '2025-06-23 04:22:41', '2025-06-23 04:22:41'),
(136, 'The rate of a chemical reaction depends on:', 'The rate of a chemical reaction depends on:', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:25:19', '2025-06-23 04:25:19'),
(137, 'Which of the following is a strong electrolyte?', 'Which of the following is a strong electrolyte?', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:26:22', '2025-06-23 04:26:22'),
(138, 'Which of the following is NOT a colligative proper...', 'Which of the following is NOT a colligative proper...', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:27:25', '2025-06-23 04:27:25'),
(139, 'The process of converting alkyl halides into alcoh...', 'The process of converting alkyl halides into alcoh...', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:28:27', '2025-06-23 04:28:27'),
(140, 'Which metal is used as a catalyst in the Haber pro...', 'Which metal is used as a catalyst in the Haber pro...', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:30:57', '2025-06-23 04:30:57'),
(141, 'What is the product of the reaction between a carb...', 'What is the product of the reaction between a carb...', 'active', 3, 8, 27, NULL, 2, '2025-06-23 04:32:14', '2025-06-23 04:32:14'),
(142, 'Which of the following is the site of photosynthes...', 'Which of the following is the site of photosynthes...', 'active', 3, 8, 28, NULL, 2, '2025-06-23 04:34:18', '2025-06-23 04:34:18'),
(143, 'What is the functional unit of the kidney?', 'What is the functional unit of the kidney?', 'active', 3, 8, 28, NULL, 2, '2025-06-23 04:35:17', '2025-06-23 04:35:17'),
(144, 'Which of the following is a sex-linked disease?', 'Which of the following is a sex-linked disease?', 'active', 3, 8, 28, NULL, 2, '2025-06-23 04:36:27', '2025-06-23 04:36:27'),
(145, 'The process by which plants lose water vapor is ca...', 'The process by which plants lose water vapor is ca...', 'active', 3, 8, 28, NULL, 2, '2025-06-23 04:37:24', '2025-06-23 04:37:24'),
(146, 'Which of the following is a greenhouse gas?', 'Which of the following is a greenhouse gas?', 'active', 3, 8, 28, NULL, 2, '2025-06-23 04:38:52', '2025-06-23 04:38:52');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_analytics`
--

CREATE TABLE `quiz_analytics` (
  `id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `total_attempts` int(11) DEFAULT 0,
  `average_score` decimal(5,2) DEFAULT 0.00,
  `completion_rate` decimal(5,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_attempts`
--

CREATE TABLE `quiz_attempts` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_time` timestamp NULL DEFAULT NULL,
  `total_questions` int(11) NOT NULL,
  `correct_answers` int(11) DEFAULT 0,
  `wrong_answers` int(11) DEFAULT 0,
  `unanswered` int(11) DEFAULT 0,
  `score` decimal(5,2) NOT NULL,
  `percentage` decimal(5,2) NOT NULL,
  `passed` tinyint(1) DEFAULT 0,
  `time_taken` int(11) DEFAULT NULL,
  `status` enum('in_progress','completed','abandoned') DEFAULT 'in_progress'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_ratings`
--

CREATE TABLE `quiz_ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `rating` tinyint(4) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quiz_reports`
--

CREATE TABLE `quiz_reports` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quiz_id` int(11) NOT NULL,
  `report_type` enum('inappropriate','wrong_answer','spam','other') NOT NULL,
  `description` text NOT NULL,
  `status` enum('pending','resolved','dismissed') DEFAULT 'pending',
  `admin_response` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `resolved_at` timestamp NULL DEFAULT NULL,
  `resolved_by` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` int(11) NOT NULL,
  `class_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `class_id`, `category_id`, `name`, `slug`, `description`, `status`, `created_by`, `created_at`, `updated_at`) VALUES
(4, 1, 1, 'General Knowledge', 'General Knowledge', 'For the Students of Class 8', 'active', 1, '2025-05-31 18:16:45', '2025-06-21 17:02:59'),
(5, 2, 2, 'Math', 'Math', 'For the Students of Class 9-10(Science)', 'active', 1, '2025-05-31 19:19:43', '2025-06-21 17:04:25'),
(6, 3, 3, 'Physics 1st Paper', 'Physics 1st Paper', 'for the Students of Collage 1st Year', 'active', 1, '2025-06-02 19:35:11', '2025-06-21 17:06:09'),
(7, 4, 4, 'COLOR', 'color', 'color names', 'active', 1, '2025-06-03 03:29:22', '2025-06-03 03:29:22'),
(8, 1, 1, 'English 1st Paper', 'English 1st Paper', 'English for Today', 'active', 1, '2025-06-21 16:41:40', '2025-06-21 16:41:40'),
(9, 1, 1, 'English 2st Paper', 'English 2st Paper', 'English Grammer', 'active', 1, '2025-06-21 16:42:11', '2025-06-21 16:42:11'),
(10, 1, 1, 'Science', 'Science', 'Science (Board Book)', 'active', 1, '2025-06-21 16:43:06', '2025-06-21 16:43:06'),
(11, 1, 1, 'Religion (Islam)', 'Religion (Islam)', 'Islam Teachings', 'active', 1, '2025-06-21 16:43:51', '2025-06-21 16:43:51'),
(13, 9, 2, 'English Grammer', 'English Grammer', 'English Grammer', 'active', 1, '2025-06-21 16:45:05', '2025-06-21 16:45:05'),
(14, 9, 2, 'Social Science', 'Social Science', 'Social Science', 'active', 1, '2025-06-21 16:45:36', '2025-06-21 16:45:36'),
(15, 5, 2, 'Geography', 'Geography', 'Geography', 'active', 1, '2025-06-21 16:45:59', '2025-06-21 16:45:59'),
(16, 5, 2, 'History', 'History', 'History', 'active', 1, '2025-06-21 16:46:23', '2025-06-21 16:46:23'),
(17, 2, 2, 'Physics', 'Physics', 'Physics', 'active', 1, '2025-06-21 16:46:41', '2025-06-21 16:46:41'),
(18, 2, 2, 'Chemistry', 'Chemistry', 'Chemistry', 'active', 1, '2025-06-21 16:46:58', '2025-06-21 16:46:58'),
(19, 2, 2, 'Biology', 'Biology', 'Biology', 'active', 1, '2025-06-21 16:47:14', '2025-06-21 16:47:14'),
(20, 6, 3, 'History', 'hsc-history', '', 'active', 1, '2025-06-22 16:13:44', '2025-06-22 16:13:44'),
(23, 7, 3, 'History', 'hsc-history-2nd year', '', 'active', 1, '2025-06-22 16:44:23', '2025-06-22 16:44:23'),
(24, 3, 3, 'Chemistry 1st Paper', 'chemistry-1st', '', 'active', 1, '2025-06-22 17:16:29', '2025-06-22 17:16:29'),
(25, 3, 3, 'Biology 1st Paper', 'biology-1st', '', 'active', 1, '2025-06-22 17:21:15', '2025-06-22 17:21:15'),
(26, 8, 3, 'Physics 2nd Paper', 'physics-2nd', '', 'active', 1, '2025-06-22 17:27:41', '2025-06-22 17:27:41'),
(27, 8, 3, 'Chemistry 2nd Paper', 'chemistry-2nd', '', 'active', 1, '2025-06-22 17:28:52', '2025-06-22 17:28:52'),
(28, 8, 3, 'Biology 2nd Paper', 'biology-2nd', '', 'active', 1, '2025-06-22 17:29:56', '2025-06-22 17:29:56'),
(29, 10, 5, 'Buet 2023', 'buet-2023', '', 'active', 1, '2025-06-22 17:42:37', '2025-06-22 17:42:37'),
(32, 12, 5, 'CU 2023', 'cu-2023', '', 'active', 1, '2025-06-22 17:47:31', '2025-06-22 18:03:27'),
(33, 10, 5, 'Ruet 2023', 'ruet-2023', '', 'active', 1, '2025-06-22 17:49:21', '2025-06-22 17:49:21'),
(35, 11, 5, 'RMC 2023', 'rmc-2023', '', 'active', 1, '2025-06-22 17:50:13', '2025-06-22 18:00:55'),
(36, 12, 5, 'RU 2023', 'ru-2023', '', 'active', 1, '2025-06-22 17:50:40', '2025-06-22 18:03:03'),
(37, 10, 5, 'Cuet 2023', 'cuet-2023', '', 'active', 1, '2025-06-22 17:51:56', '2025-06-22 17:51:56'),
(38, 11, 5, 'BMC 2023', 'bmc-2023', '', 'active', 1, '2025-06-22 17:52:26', '2025-06-22 17:59:51'),
(39, 12, 5, 'DU 2023', 'du-2023', '', 'active', 1, '2025-06-22 17:52:49', '2025-06-22 18:02:15'),
(40, 11, 5, 'DMC 2023', 'dmc-2023', '', 'active', 1, '2025-06-22 17:55:03', '2025-06-22 17:57:19'),
(41, 6, 3, 'Geography 1st paper', 'geography-1st-paper', '', 'active', 1, '2025-06-23 03:18:57', '2025-06-23 03:18:57'),
(42, 7, 3, 'Geography 2nd paper', 'geography-2nd-paper', '', 'active', 1, '2025-06-23 03:20:02', '2025-06-23 03:20:02'),
(43, 6, 3, 'Civics 1st paper', 'civics-1st-paper', '', 'active', 1, '2025-06-23 03:20:56', '2025-06-23 03:20:56'),
(44, 7, 3, 'Civics 2nd paper', 'civics-2nd-paper', '', 'active', 1, '2025-06-23 03:21:41', '2025-06-23 03:21:41');

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `plan_name` varchar(100) NOT NULL,
  `start_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `end_date` timestamp NULL DEFAULT NULL,
  `status` enum('active','expired','cancelled') DEFAULT 'active',
  `amount` decimal(10,2) NOT NULL,
  `currency` varchar(3) DEFAULT 'BDT',
  `payment_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `topics`
--

CREATE TABLE `topics` (
  `id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role` enum('admin','user') DEFAULT 'user',
  `status` enum('active','inactive','banned') DEFAULT 'active',
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `first_name`, `last_name`, `phone`, `avatar`, `role`, `status`, `email_verified_at`, `created_at`, `updated_at`) VALUES
(1, 'mamun', 'mamun@gmail.com', '$2y$10$ir17G8h..cn8aUHbZ2jVuuW5VNmXpBNqb1.gTGcn1CcRlJcBtl4Uy', 'ASA', 'MAMUN', '34568975984u', 'http://google.com', 'admin', 'active', NULL, '2025-05-29 04:16:21', '2025-05-29 04:59:17'),
(2, 'mm', 'mmsoft@gmail.com', '$2y$10$Ke8bRfaOx6gUlmIKIi4xK.BYEU0vuuyIgOWCW.d1QUXjRq.gWthvq', 'Muntasir', 'Mahmud', '845015050', 'https://www.google.com', 'admin', 'active', NULL, '2025-06-01 03:39:11', '2025-06-01 03:40:12'),
(3, 'emni', 'test@gmail.com', '$2y$10$EU0Xcq2uYrA.hg1q6ZKafuhYfU1YTpu7yRtxb6NVz9ZO.sW3Da.Wi', 'm', 'm', '75683275325', 'https://www.google.com', 'user', 'active', NULL, '2025-06-01 04:06:59', '2025-06-01 04:06:59'),
(4, 'rakib', 'rakib@gmail.com', '$2y$10$3/1XhIEJl07vw1M64TP4Y.xtf7pxL.Orv5w/mEqJh2Rm7WE7r3pD2', 'Mohammad', 'Rakib', '123456789', 'http://google.com/', 'user', 'active', NULL, '2025-06-21 17:19:41', '2025-06-21 17:19:41');

-- --------------------------------------------------------

--
-- Table structure for table `user_answers`
--

CREATE TABLE `user_answers` (
  `id` int(11) NOT NULL,
  `attempt_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `selected_option_id` int(11) DEFAULT NULL,
  `answer_text` text DEFAULT NULL,
  `is_correct` tinyint(1) DEFAULT 0,
  `marks_obtained` decimal(3,1) DEFAULT 0.0,
  `answered_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `earned_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `quiz_attempt_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_progress`
--

CREATE TABLE `user_progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `category_id` int(11) DEFAULT NULL,
  `class_id` int(11) DEFAULT NULL,
  `subject_id` int(11) DEFAULT NULL,
  `topic_id` int(11) DEFAULT NULL,
  `progress_percentage` decimal(5,2) DEFAULT 0.00,
  `last_updated` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `classes`
--
ALTER TABLE `classes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `exams`
--
ALTER TABLE `exams`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `exam_quizzes`
--
ALTER TABLE `exam_quizzes`
  ADD PRIMARY KEY (`exam_id`,`quiz_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `exam_quiz_idx` (`exam_id`,`quiz_id`);

--
-- Indexes for table `leaderboards`
--
ALTER TABLE `leaderboards`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transaction_id` (`transaction_id`),
  ADD UNIQUE KEY `bkash_transaction_id` (`bkash_transaction_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `verified_by` (`verified_by`);

--
-- Indexes for table `pending_questions`
--
ALTER TABLE `pending_questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `questions`
--
ALTER TABLE `questions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `question_options`
--
ALTER TABLE `question_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`);

--
-- Indexes for table `question_tags`
--
ALTER TABLE `question_tags`
  ADD PRIMARY KEY (`id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `tag` (`tag`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `title_unique` (`title`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `event_id` (`event_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `status` (`status`);

--
-- Indexes for table `quiz_analytics`
--
ALTER TABLE `quiz_analytics`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `status` (`status`),
  ADD KEY `user_quiz_idx` (`user_id`,`quiz_id`,`status`);

--
-- Indexes for table `quiz_ratings`
--
ALTER TABLE `quiz_ratings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_quiz_rating` (`user_id`,`quiz_id`),
  ADD KEY `quiz_id` (`quiz_id`);

--
-- Indexes for table `quiz_reports`
--
ALTER TABLE `quiz_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `quiz_id` (`quiz_id`),
  ADD KEY `resolved_by` (`resolved_by`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug_unique` (`slug`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `payment_id` (`payment_id`);

--
-- Indexes for table `topics`
--
ALTER TABLE `topics`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_topic_subject` (`subject_id`,`slug`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attempt_id` (`attempt_id`),
  ADD KEY `question_id` (`question_id`),
  ADD KEY `selected_option_id` (`selected_option_id`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `badge_id` (`badge_id`),
  ADD KEY `quiz_attempt_id` (`quiz_attempt_id`);

--
-- Indexes for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `class_id` (`class_id`),
  ADD KEY `subject_id` (`subject_id`),
  ADD KEY `topic_id` (`topic_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `classes`
--
ALTER TABLE `classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `exams`
--
ALTER TABLE `exams`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `leaderboards`
--
ALTER TABLE `leaderboards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pending_questions`
--
ALTER TABLE `pending_questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `questions`
--
ALTER TABLE `questions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=577;

--
-- AUTO_INCREMENT for table `question_tags`
--
ALTER TABLE `question_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=147;

--
-- AUTO_INCREMENT for table `quiz_analytics`
--
ALTER TABLE `quiz_analytics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_ratings`
--
ALTER TABLE `quiz_ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quiz_reports`
--
ALTER TABLE `quiz_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topics`
--
ALTER TABLE `topics`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `user_answers`
--
ALTER TABLE `user_answers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_badges`
--
ALTER TABLE `user_badges`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `user_progress`
--
ALTER TABLE `user_progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `classes`
--
ALTER TABLE `classes`
  ADD CONSTRAINT `classes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `classes_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `exams`
--
ALTER TABLE `exams`
  ADD CONSTRAINT `exams_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exams_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exams_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exams_ibfk_4` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `exams_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `exam_quizzes`
--
ALTER TABLE `exam_quizzes`
  ADD CONSTRAINT `exam_quizzes_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `exam_quizzes_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `leaderboards`
--
ALTER TABLE `leaderboards`
  ADD CONSTRAINT `leaderboards_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `leaderboards_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaderboards_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `leaderboards_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_ibfk_3` FOREIGN KEY (`verified_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pending_questions`
--
ALTER TABLE `pending_questions`
  ADD CONSTRAINT `pending_questions_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pending_questions_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pending_questions_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pending_questions_ibfk_4` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pending_questions_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `questions`
--
ALTER TABLE `questions`
  ADD CONSTRAINT `questions_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_options`
--
ALTER TABLE `question_options`
  ADD CONSTRAINT `question_options_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `question_tags`
--
ALTER TABLE `question_tags`
  ADD CONSTRAINT `question_tags_ibfk_1` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD CONSTRAINT `quizzes_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_2` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_3` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_4` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `quizzes_ibfk_5` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quiz_analytics`
--
ALTER TABLE `quiz_analytics`
  ADD CONSTRAINT `quiz_analytics_ibfk_1` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_attempts`
--
ALTER TABLE `quiz_attempts`
  ADD CONSTRAINT `quiz_attempts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_attempts_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_ratings`
--
ALTER TABLE `quiz_ratings`
  ADD CONSTRAINT `quiz_ratings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_ratings_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quiz_reports`
--
ALTER TABLE `quiz_reports`
  ADD CONSTRAINT `quiz_reports_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_reports_ibfk_2` FOREIGN KEY (`quiz_id`) REFERENCES `quizzes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quiz_reports_ibfk_3` FOREIGN KEY (`resolved_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_ibfk_1` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subscriptions_ibfk_2` FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `topics`
--
ALTER TABLE `topics`
  ADD CONSTRAINT `topics_ibfk_1` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `topics_ibfk_2` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_answers`
--
ALTER TABLE `user_answers`
  ADD CONSTRAINT `user_answers_ibfk_1` FOREIGN KEY (`attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_answers_ibfk_3` FOREIGN KEY (`selected_option_id`) REFERENCES `question_options` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_badges_ibfk_3` FOREIGN KEY (`quiz_attempt_id`) REFERENCES `quiz_attempts` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `user_progress`
--
ALTER TABLE `user_progress`
  ADD CONSTRAINT `user_progress_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_progress_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_progress_ibfk_3` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_progress_ibfk_4` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `user_progress_ibfk_5` FOREIGN KEY (`topic_id`) REFERENCES `topics` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
