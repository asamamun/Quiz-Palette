-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 21, 2025 at 09:25 PM
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
(9, 2, 'Class 9-10 (Common)', 'Class 9-10 (Common)', 'For All the Students of Class 9-10', 'active', 1, '2025-06-21 16:40:40', '2025-06-21 16:40:40');

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
(37, 39, 'If the radius of Earth were reduced to half while keeping its mass constant, the value of gg at the surface would:', 'multiple_choice', 1, NULL, NULL, 1, 'active', '2025-06-21 19:02:59', '2025-06-21 19:02:59', 'medium', NULL);

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
(148, 37, 'Remain unchanged', 0, 4);

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
(39, 'If the radius of Earth were reduced to half while ...', 'If the radius of Earth were reduced to half while ...', 'active', 3, 3, 6, NULL, 2, '2025-06-21 19:02:59', '2025-06-21 19:02:59');

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
(19, 2, 2, 'Biology', 'Biology', 'Biology', 'active', 1, '2025-06-21 16:47:14', '2025-06-21 16:47:14');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `question_options`
--
ALTER TABLE `question_options`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=149;

--
-- AUTO_INCREMENT for table `question_tags`
--
ALTER TABLE `question_tags`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

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
