-- Create the database
CREATE DATABASE IF NOT EXISTS `litdb` 
DEFAULT CHARACTER SET utf8mb4 
COLLATE utf8mb4_general_ci;

USE `litdb`;

-- Core tables
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(225) NOT NULL,
  `role` ENUM('admin', 'teacher', 'student') NOT NULL DEFAULT 'student',
  `joinDate` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE INDEX `email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `courses` (
  `courseId` int(11) NOT NULL AUTO_INCREMENT,
  `courseName` varchar(50) NOT NULL,
  `language` varchar(50) NOT NULL,
  `level` enum('Basics','Intermediate','Advanced') NOT NULL,
  `description` text NOT NULL,
  PRIMARY KEY (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `words` (
  `wordId` int(11) NOT NULL AUTO_INCREMENT,
  `word` varchar(100) NOT NULL,
  `sourceLanguage` varchar(20) NOT NULL,
  `targetLanguage` varchar(20) NOT NULL,
  `pronunciation` varchar(100) DEFAULT NULL,
  `translation` varchar(255) NOT NULL,
  `courseId` int(11) NULL,
  `category` varchar(50) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `createdAt` date NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`wordId`),
  INDEX `category_index` (`category`),
  INDEX `difficulty_index` (`difficulty`),
  INDEX `language_index` (`sourceLanguage`, `targetLanguage`),
  FOREIGN KEY (`courseId`) REFERENCES `courses`(`courseId`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `user_enrollments` (
  `enrollmentId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `enrollmentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('active','completed','dropped') NOT NULL DEFAULT 'active',
  PRIMARY KEY (`enrollmentId`),
  UNIQUE KEY `unique_enrollment` (`userId`, `courseId`),
  INDEX `idx_user_status` (`userId`, `status`),
  FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `quiz_scores` (
  `scoreId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `courseId` int(11) NOT NULL,
  `score` int(11) NOT NULL,
  `dateTaken` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`scoreId`),
  FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  FOREIGN KEY (`courseId`) REFERENCES `courses` (`courseId`),
  CONSTRAINT `valid_score` CHECK (`score` >= 0 AND `score` <= 100)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `learned_words` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `user_id` INT NOT NULL,
    `word_id` INT NOT NULL,
    `learned_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`),
    FOREIGN KEY (`word_id`) REFERENCES `words`(`wordId`),
    INDEX `idx_user_word` (`user_id`, `word_id`),
    INDEX `idx_learned_date` (`learned_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `word_of_day` (
    `id` INT PRIMARY KEY AUTO_INCREMENT,
    `wordId` INT NOT NULL,
    `dateShown` DATE NOT NULL DEFAULT CURRENT_DATE,
    FOREIGN KEY (`wordId`) REFERENCES `words`(`wordId`),
    UNIQUE KEY `unique_date` (`dateShown`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create view for quiz statistics
CREATE OR REPLACE VIEW `user_quiz_stats` AS
SELECT 
    userId,
    courseId,
    COUNT(*) as quizzes_taken,
    AVG(score) as average_score,
    SUM(score) as total_score
FROM quiz_scores
GROUP BY userId, courseId;

-- Insert initial data
INSERT INTO `users` (`firstName`, `lastName`, `email`, `password`, `role`, `joinDate`) VALUES
('trial', 'test', 'nana.marge@home.com', '$2y$10$33s8q.GmM.LSzsR.uDmcbePksMGKzjY7WBtUCqfeLmTGRarHxXMXu', 'student', '2024-11-30'),
('Marge', 'Hagan', 'margenana@home.com', '$2y$10$DQWj8ucgDbBEpZCqRZOMWOutJS53JVyyQqKdqwVRdvbUaMULoxEQW', 'student', '2024-11-30');

-- Insert courses
INSERT INTO `courses` (`courseName`, `language`, `level`, `description`) VALUES
('French', 'French', 'Basics', 'Learn the fundamentals of French language including basic vocabulary and phrases'),
('Spanish', 'Spanish', 'Basics', 'Learn the fundamentals of Spanish language including basic vocabulary and phrases');

-- Insert sample words
INSERT INTO `words` (`word`, `sourceLanguage`, `targetLanguage`, `pronunciation`, `translation`, `courseId`, `category`, `difficulty`) VALUES
('C''est mal', 'French', 'English', 'seh mahl', 'It''s bad', 1, 'Basic Phrases', 'easy'),
('Bonjour', 'French', 'English', 'bohn-ZHOOR', 'Hello', 1, 'Basic Phrases', 'easy'),
('Au revoir', 'French', 'English', 'oh ruh-VWAHR', 'Goodbye', 1, 'Basic Phrases', 'easy');
