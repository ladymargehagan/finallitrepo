-- Create the database
DROP DATABASE IF EXISTS litdb;
CREATE DATABASE litdb;
USE litdb;

-- Set character encoding
SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- Create users table
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `joinDate` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create languages table (combined with courses)
CREATE TABLE `languages` (
  `languageId` int(11) NOT NULL AUTO_INCREMENT,
  `languageName` varchar(50) NOT NULL,
  `level` enum('Beginner','Intermediate','Advanced') NOT NULL,
  `description` text,
  PRIMARY KEY (`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create words table
CREATE TABLE `words` (
  `wordId` int(11) NOT NULL AUTO_INCREMENT,
  `languageId` int(11) NOT NULL,
  `word` varchar(100) NOT NULL,
  `translation` varchar(100) NOT NULL,
  `pronunciation` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`wordId`),
  KEY `language_idx` (`languageId`),
  KEY `category_idx` (`category`),
  KEY `difficulty_idx` (`difficulty`),
  FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create user enrollments table
CREATE TABLE `user_enrollments` (
  `enrollmentId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `status` enum('active','completed','dropped') NOT NULL DEFAULT 'active',
  `enrollmentDate` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`enrollmentId`),
  UNIQUE KEY `user_language_unique` (`userId`, `languageId`),
  FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create learned words table
CREATE TABLE `learned_words` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `wordId` int(11) NOT NULL,
  `proficiency` enum('learning','familiar','mastered') DEFAULT 'learning',
  `learnedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastReviewed` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_word_unique` (`userId`, `wordId`),
  FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- First, create a categories table to maintain our standard categories
CREATE TABLE `word_categories` (
  `categoryId` int(11) NOT NULL AUTO_INCREMENT,
  `categoryName` varchar(50) NOT NULL,
  `categorySlug` varchar(50) NOT NULL,  -- For URLs (e.g., 'food-drinks')
  `description` text,
  `icon` varchar(50),  -- Store the Font Awesome icon class
  PRIMARY KEY (`categoryId`),
  UNIQUE KEY `categorySlug_unique` (`categorySlug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Update words table to include category reference
ALTER TABLE `words` 
  ADD COLUMN `categoryId` int(11) NOT NULL AFTER `languageId`,
  ADD FOREIGN KEY (`categoryId`) REFERENCES `word_categories` (`categoryId`);

-- Insert our standard categories
INSERT INTO `word_categories` 
  (`categoryName`, `categorySlug`, `description`, `icon`) 
VALUES
  ('Greetings & Basics', 'greetings', 'Learn essential greetings and basic phrases', 'fas fa-hand-wave'),
  ('People & Family', 'people', 'Learn words about people and family members', 'fas fa-users'),
  ('Places & Directions', 'places', 'Learn about locations and how to get around', 'fas fa-map-marker-alt'),
  ('Food & Drinks', 'food', 'Learn vocabulary for food and beverages', 'fas fa-utensils'),
  ('Numbers & Time', 'numbers', 'Learn numbers and telling time', 'fas fa-clock'),
  ('Daily Activities', 'daily', 'Learn words for everyday activities', 'fas fa-calendar-day');

-- Example word insertions with categories
INSERT INTO `words` 
  (`word`, `translation`, `languageId`, `categoryId`, `pronunciation`, `difficulty`) 
VALUES
  ('pain', 'bread', 1, (SELECT categoryId FROM word_categories WHERE categorySlug = 'food'), 'pah', 'easy'),
  ('eau', 'water', 1, (SELECT categoryId FROM word_categories WHERE categorySlug = 'food'), 'oh', 'easy'),
  ('bonjour', 'hello', 1, (SELECT categoryId FROM word_categories WHERE categorySlug = 'greetings'), 'bohn-ZHOOR', 'easy'),
  ('au revoir', 'goodbye', 1, (SELECT categoryId FROM word_categories WHERE categorySlug = 'greetings'), 'oh ruh-VWAHR', 'easy');

SET FOREIGN_KEY_CHECKS = 1;