-- Modified Events table to support event series
ALTER TABLE `events` 
ADD COLUMN `event_series` VARCHAR(100) NULL AFTER `name`,
ADD COLUMN `event_number` INT NULL AFTER `event_series`,
ADD COLUMN `total_questions` INT DEFAULT 100 AFTER `description`,
ADD COLUMN `duration_minutes` INT DEFAULT 120 AFTER `total_questions`,
ADD COLUMN `start_datetime` DATETIME NULL AFTER `event_date`,
ADD COLUMN `end_datetime` DATETIME NULL AFTER `start_datetime`,
ADD COLUMN `registration_deadline` DATETIME NULL AFTER `end_datetime`;

-- Add event_id to leaderboards table for event-specific rankings
ALTER TABLE `leaderboards` 
ADD COLUMN `event_id` INT NULL AFTER `subject_id`,
ADD COLUMN `exam_id` INT NULL AFTER `event_id`,
ADD INDEX `event_id` (`event_id`),
ADD INDEX `exam_id` (`exam_id`);

-- Add foreign key constraints for leaderboards
ALTER TABLE `leaderboards`
ADD CONSTRAINT `leaderboards_ibfk_5` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `leaderboards_ibfk_6` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL;

-- Create a new table for event-specific question pools
CREATE TABLE `event_question_pools` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `event_id` int(11) NOT NULL,
  `subject_id` int(11) NOT NULL,
  `questions_per_subject` int(11) DEFAULT 10,
  `difficulty_distribution` JSON DEFAULT NULL, -- {"easy": 40, "medium": 40, "hard": 20}
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_event_subject` (`event_id`, `subject_id`),
  KEY `event_id` (`event_id`),
  KEY `subject_id` (`subject_id`),
  CONSTRAINT `event_question_pools_ibfk_1` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  CONSTRAINT `event_question_pools_ibfk_2` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create exam_questions table to store specific questions for each exam attempt
CREATE TABLE `exam_questions` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `exam_id` int(11) NOT NULL,
  `question_id` int(11) NOT NULL,
  `order_index` int(11) NOT NULL,
  `marks` decimal(3,1) DEFAULT 1.0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_exam_question_order` (`exam_id`, `order_index`),
  KEY `exam_id` (`exam_id`),
  KEY `question_id` (`question_id`),
  CONSTRAINT `exam_questions_ibfk_1` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE CASCADE,
  CONSTRAINT `exam_questions_ibfk_2` FOREIGN KEY (`question_id`) REFERENCES `questions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Add event_id to quiz_attempts for better tracking
ALTER TABLE `quiz_attempts` 
ADD COLUMN `event_id` INT NULL AFTER `quiz_id`,
ADD COLUMN `exam_id` INT NULL AFTER `event_id`,
ADD INDEX `event_id` (`event_id`),
ADD INDEX `exam_id` (`exam_id`);

-- Add foreign key constraints for quiz_attempts
ALTER TABLE `quiz_attempts`
ADD CONSTRAINT `quiz_attempts_ibfk_3` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
ADD CONSTRAINT `quiz_attempts_ibfk_4` FOREIGN KEY (`exam_id`) REFERENCES `exams` (`id`) ON DELETE SET NULL;

-- Sample data for O Level event series
INSERT INTO `events` (`name`, `event_series`, `event_number`, `slug`, `event_date`, `description`, `total_questions`, `duration_minutes`, `start_datetime`, `end_datetime`, `status`, `created_by`) VALUES
('O Level Exam Test 1', 'O Level Exam', 1, 'o-level-exam-test-1', '2025-07-01', 'First comprehensive O Level examination covering all subjects', 100, 120, '2025-07-01 09:00:00', '2025-07-01 11:00:00', 'active', 1),
('O Level Exam Test 2', 'O Level Exam', 2, 'o-level-exam-test-2', '2025-07-15', 'Second comprehensive O Level examination covering all subjects', 100, 120, '2025-07-15 09:00:00', '2025-07-15 11:00:00', 'active', 1),
('O Level Exam Test 3', 'O Level Exam', 3, 'o-level-exam-test-3', '2025-08-01', 'Third comprehensive O Level examination covering all subjects', 100, 120, '2025-08-01 09:00:00', '2025-08-01 11:00:00', 'active', 1);

-- Sample O Level category and subjects (assuming they exist)
INSERT INTO `categories` (`name`, `slug`, `description`, `icon`, `status`, `created_by`) VALUES
('O Level', 'o-level', 'Ordinary Level Education', 'graduation-cap', 'active', 1);

-- Sample subjects for O Level
INSERT INTO `subjects` (`class_id`, `category_id`, `name`, `slug`, `description`, `status`, `created_by`) VALUES
(1, (SELECT id FROM categories WHERE slug = 'o-level'), 'Mathematics', 'o-level-mathematics', 'O Level Mathematics', 'active', 1),
(1, (SELECT id FROM categories WHERE slug = 'o-level'), 'English', 'o-level-english', 'O Level English', 'active', 1),
(1, (SELECT id FROM categories WHERE slug = 'o-level'), 'Physics', 'o-level-physics', 'O Level Physics', 'active', 1),
(1, (SELECT id FROM categories WHERE slug = 'o-level'), 'Chemistry', 'o-level-chemistry', 'O Level Chemistry', 'active', 1),
(1, (SELECT id FROM categories WHERE slug = 'o-level'), 'Biology', 'o-level-biology', 'O Level Biology', 'active', 1);

-- Create event-specific question pools
INSERT INTO `event_question_pools` (`event_id`, `subject_id`, `questions_per_subject`, `difficulty_distribution`) VALUES
(1, (SELECT id FROM subjects WHERE slug = 'o-level-mathematics'), 20, '{"easy": 8, "medium": 8, "hard": 4}'),
(1, (SELECT id FROM subjects WHERE slug = 'o-level-english'), 20, '{"easy": 8, "medium": 8, "hard": 4}'),
(1, (SELECT id FROM subjects WHERE slug = 'o-level-physics'), 20, '{"easy": 8, "medium": 8, "hard": 4}'),
(1, (SELECT id FROM subjects WHERE slug = 'o-level-chemistry'), 20, '{"easy": 8, "medium": 8, "hard": 4}'),
(1, (SELECT id FROM subjects WHERE slug = 'o-level-biology'), 20, '{"easy": 8, "medium": 8, "hard": 4}');
