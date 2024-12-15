-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2024 at 12:46 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `litdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `exercise_sessions`
--

CREATE TABLE `exercise_sessions` (
  `sessionId` int(11) NOT NULL,
  `userId` int(11) DEFAULT NULL,
  `exerciseSetId` int(11) DEFAULT NULL,
  `startTime` timestamp NOT NULL DEFAULT current_timestamp(),
  `endTime` timestamp NULL DEFAULT NULL,
  `totalWords` int(11) DEFAULT NULL,
  `correctWords` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_sets`
--

CREATE TABLE `exercise_sets` (
  `exerciseId` int(11) NOT NULL,
  `wordId` int(11) NOT NULL,
  `translationId` int(11) NOT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `type` enum('translation','matching','fill-in') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_templates`
--

CREATE TABLE `exercise_templates` (
  `templateId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `question_text` varchar(255) NOT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `type` enum('translation','matching','fill-in') DEFAULT 'translation',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exercise_word_bank`
--

CREATE TABLE `exercise_word_bank` (
  `exerciseId` int(11) NOT NULL,
  `bankWordId` int(11) NOT NULL,
  `is_answer` tinyint(1) NOT NULL DEFAULT 0,
  `position` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `languageId` int(11) NOT NULL,
  `languageName` varchar(50) NOT NULL,
  `level` enum('Beginner','Intermediate','Advanced') NOT NULL,
  `description` text DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1,
  `languageCode` varchar(2) NOT NULL DEFAULT 'en'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`languageId`, `languageName`, `level`, `description`, `active`, `languageCode`) VALUES
(1, 'French', 'Beginner', 'Learn French from basics to the intermediate level', 1, 'en'),
(2, 'Spanish', 'Beginner', 'Learn Spanish from basics to the intermediate level', 1, 'en'),
(3, 'German', 'Beginner', 'Coming Soon', 0, 'en'),
(4, 'Italian', 'Beginner', NULL, 0, 'en');

-- --------------------------------------------------------

--
-- Table structure for table `learned_words`
--

CREATE TABLE `learned_words` (
  `id` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `wordId` int(11) NOT NULL,
  `proficiency` enum('learning','familiar','mastered') DEFAULT 'learning',
  `learnedDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `lastReviewed` timestamp NULL DEFAULT NULL,
  `correct_attempts` int(11) DEFAULT 0,
  `total_attempts` int(11) DEFAULT 0,
  `last_attempt_date` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `session_word_progress`
--

CREATE TABLE `session_word_progress` (
  `sessionId` int(11) DEFAULT NULL,
  `wordId` int(11) DEFAULT NULL,
  `correctAttempts` int(11) DEFAULT 0,
  `totalAttempts` int(11) DEFAULT 0,
  `mastered` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `translations`
--

CREATE TABLE `translations` (
  `translationId` int(11) NOT NULL,
  `wordId` int(11) NOT NULL,
  `translated_text` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `translations`
--

INSERT INTO `translations` (`translationId`, `wordId`, `translated_text`, `is_primary`, `created_at`) VALUES
(1, 1, 'Hello', 1, '2024-12-14 13:13:11'),
(2, 1, 'Good morning', 0, '2024-12-14 13:13:11'),
(3, 2, 'Goodbye', 1, '2024-12-14 13:13:11'),
(4, 2, 'Bye', 0, '2024-12-14 13:13:11'),
(5, 3, 'Please', 1, '2024-12-14 13:13:11'),
(6, 4, 'Thank you', 1, '2024-12-14 13:13:11'),
(7, 4, 'Thanks', 0, '2024-12-14 13:13:11'),
(8, 5, 'You\'re welcome', 1, '2024-12-14 13:13:11'),
(9, 5, 'No problem', 0, '2024-12-14 13:13:11'),
(10, 6, 'One', 1, '2024-12-14 13:16:29'),
(11, 7, 'Two', 1, '2024-12-14 13:16:29'),
(12, 8, 'Three', 1, '2024-12-14 13:16:29'),
(13, 9, 'Four', 1, '2024-12-14 13:16:29'),
(14, 10, 'Five', 1, '2024-12-14 13:16:29'),
(15, 11, 'Six', 1, '2024-12-14 13:19:27'),
(16, 12, 'Seven', 1, '2024-12-14 13:19:27'),
(17, 13, 'Eight', 1, '2024-12-14 13:19:27'),
(18, 14, 'Nine', 1, '2024-12-14 13:19:27'),
(19, 15, 'Ten', 1, '2024-12-14 13:19:27'),
(20, 16, 'Eleven', 1, '2024-12-14 13:19:27'),
(21, 17, 'Twelve', 1, '2024-12-14 13:19:27'),
(22, 18, 'Thirteen', 1, '2024-12-14 13:19:27'),
(23, 19, 'Fourteen', 1, '2024-12-14 13:19:27'),
(24, 20, 'Fifteen', 1, '2024-12-14 13:19:27'),
(25, 21, 'Bread', 1, '2024-12-14 13:19:36'),
(26, 22, 'Water', 1, '2024-12-14 13:19:36'),
(27, 23, 'Coffee', 1, '2024-12-14 13:19:36'),
(28, 24, 'Milk', 1, '2024-12-14 13:19:36'),
(29, 25, 'Apple', 1, '2024-12-14 13:19:36'),
(30, 26, 'Cheese', 1, '2024-12-14 13:19:36'),
(31, 27, 'Wine', 1, '2024-12-14 13:19:36'),
(32, 28, 'Soup', 1, '2024-12-14 13:19:36'),
(33, 29, 'Red', 1, '2024-12-14 13:19:43'),
(34, 30, 'Blue', 1, '2024-12-14 13:19:43'),
(35, 31, 'Green', 1, '2024-12-14 13:19:43'),
(36, 32, 'Yellow', 1, '2024-12-14 13:19:43'),
(37, 33, 'Black', 1, '2024-12-14 13:19:43'),
(38, 34, 'White', 1, '2024-12-14 13:19:43'),
(41, 36, 'See you soon', 1, '2024-12-15 01:34:14'),
(43, 39, 'please', 1, '2024-12-15 02:25:21'),
(44, 40, 'one million', 1, '2024-12-15 02:28:02'),
(45, 41, 'hello', 1, '2024-12-15 11:08:33'),
(46, 42, 'Hello', 1, '2024-12-15 11:24:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstName` varchar(50) NOT NULL,
  `lastName` varchar(50) NOT NULL,
  `email` varchar(80) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','teacher','student') NOT NULL DEFAULT 'student',
  `joinDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_admin` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstName`, `lastName`, `email`, `password`, `role`, `joinDate`, `is_admin`) VALUES
(1, 'Marge', 'Hagan', 'margenana@home.com', '$2y$10$bJ7d/5Ete0x/Z72tTio8QOh01F5ekPwf0pFp14Hjspcm7bWK0oHUu', 'student', '2024-12-12 18:22:15', 0),
(2, 'Lady', 'Hagan', 'mh@gmail.com', '$2y$10$1w.Ysh/jfd7ogsyK7HquLuozxqcxJwLMIQKt1EvyVfGOgZ5FxtqHS', 'student', '2024-12-14 00:08:28', 0),
(3, 'Nana', 'Amoako', 'nana.amoako@ashesi.edu.gh', '$2y$10$lIcC6A5XtC4PGL.vMt.1EO9nAEUuN80SALBv7hAASqfFydmfhq6Bu', 'student', '2024-12-14 00:09:08', 0),
(4, 'marge', 'hagan', 'margehagan@gmail.com', '$2y$10$lFXIu.dHl4Jz6bkgVenvROYl6i7SaBi7s68SzXJ0dWzQ0IfPtomfW', 'student', '2024-12-14 00:10:14', 0),
(5, 'Lady', 'Hagan', 'ladyhagan@gmail.com', '$2y$10$szJJBE0bCJNHZkuaqITc.e1sHiH7suVE4UQW/891X/LKHP.ukJmky', 'student', '2024-12-14 12:17:48', 0),
(6, 'admin', 'user', 'admin@lit.com', '$2y$10$2PDBbtLhdk8h.jevgScscuV5JtgH8yQJmFqePDrk/TgG1nlwnVbKa', 'admin', '2024-12-14 22:22:34', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_enrollments`
--

CREATE TABLE `user_enrollments` (
  `enrollmentId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `status` enum('active','completed','dropped') NOT NULL DEFAULT 'active',
  `enrollmentDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_enrollments`
--

INSERT INTO `user_enrollments` (`enrollmentId`, `userId`, `languageId`, `status`, `enrollmentDate`) VALUES
(1, 1, 1, 'active', '2024-12-12 18:37:26'),
(2, 3, 1, 'active', '2024-12-14 00:09:27'),
(3, 4, 1, 'active', '2024-12-14 00:18:16'),
(4, 5, 1, 'active', '2024-12-14 12:18:08');

-- --------------------------------------------------------

--
-- Table structure for table `words`
--

CREATE TABLE `words` (
  `wordId` int(11) NOT NULL,
  `languageId` int(11) NOT NULL,
  `original_text` varchar(255) NOT NULL,
  `categoryId` int(11) NOT NULL,
  `word` varchar(100) NOT NULL,
  `translation` varchar(100) NOT NULL,
  `pronunciation` varchar(100) DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') NOT NULL DEFAULT 'medium',
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp(),
  `context_type` enum('noun','verb','adjective','phrase','other') NOT NULL DEFAULT 'other'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `words`
--

INSERT INTO `words` (`wordId`, `languageId`, `original_text`, `categoryId`, `word`, `translation`, `pronunciation`, `category`, `difficulty`, `createdAt`, `context_type`) VALUES
(1, 1, 'Bonjour', 1, 'Bonjour', 'Hello', 'bohn-ZHOOR', NULL, 'easy', '2024-12-14 13:12:53', 'phrase'),
(2, 1, 'Au revoir', 1, 'Au revoir', 'Goodbye', 'oh ruh-VWAR', NULL, 'easy', '2024-12-14 13:12:53', 'phrase'),
(3, 1, 'S\'il vous plaît', 1, 'S\'il vous plaît', 'Please', 'seel voo PLEH', NULL, 'easy', '2024-12-14 13:12:53', 'phrase'),
(4, 1, 'Merci', 1, 'Merci', 'Thank you', 'mair-SEE', NULL, 'easy', '2024-12-14 13:12:53', 'phrase'),
(5, 1, 'De rien', 1, 'De rien', 'You\'re welcome', 'duh ree-YEN', NULL, 'easy', '2024-12-14 13:12:53', 'phrase'),
(6, 1, 'Un', 2, 'Un', 'One', 'uhn', NULL, 'easy', '2024-12-14 13:16:21', 'other'),
(7, 1, 'Deux', 2, 'Deux', 'Two', 'duh', NULL, 'easy', '2024-12-14 13:16:21', 'other'),
(8, 1, 'Trois', 2, 'Trois', 'Three', 'twah', NULL, 'easy', '2024-12-14 13:16:21', 'other'),
(9, 1, 'Quatre', 2, 'Quatre', 'Four', 'kat-ruh', NULL, 'easy', '2024-12-14 13:16:21', 'other'),
(10, 1, 'Cinq', 2, 'Cinq', 'Five', 'sank', NULL, 'easy', '2024-12-14 13:16:21', 'other'),
(11, 1, 'Six', 2, 'Six', 'Six', 'seess', NULL, 'easy', '2024-12-14 13:19:27', 'other'),
(12, 1, 'Sept', 2, 'Sept', 'Seven', 'set', NULL, 'easy', '2024-12-14 13:19:27', 'other'),
(13, 1, 'Huit', 2, 'Huit', 'Eight', 'weet', NULL, 'easy', '2024-12-14 13:19:27', 'other'),
(14, 1, 'Neuf', 2, 'Neuf', 'Nine', 'nuhf', NULL, 'easy', '2024-12-14 13:19:27', 'other'),
(15, 1, 'Dix', 2, 'Dix', 'Ten', 'deess', NULL, 'easy', '2024-12-14 13:19:27', 'other'),
(16, 1, 'Onze', 2, 'Onze', 'Eleven', 'onz', NULL, 'medium', '2024-12-14 13:19:27', 'other'),
(17, 1, 'Douze', 2, 'Douze', 'Twelve', 'dooz', NULL, 'medium', '2024-12-14 13:19:27', 'other'),
(18, 1, 'Treize', 2, 'Treize', 'Thirteen', 'trez', NULL, 'medium', '2024-12-14 13:19:27', 'other'),
(19, 1, 'Quatorze', 2, 'Quatorze', 'Fourteen', 'kah-torz', NULL, 'medium', '2024-12-14 13:19:27', 'other'),
(20, 1, 'Quinze', 2, 'Quinze', 'Fifteen', 'kanz', NULL, 'medium', '2024-12-14 13:19:27', 'other'),
(21, 1, 'Pain', 3, 'Pain', 'Bread', 'pan', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(22, 1, 'Eau', 3, 'Eau', 'Water', 'oh', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(23, 1, 'Café', 3, 'Café', 'Coffee', 'ka-fay', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(24, 1, 'Lait', 3, 'Lait', 'Milk', 'lay', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(25, 1, 'Pomme', 3, 'Pomme', 'Apple', 'pom', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(26, 1, 'Fromage', 3, 'Fromage', 'Cheese', 'froh-mahj', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(27, 1, 'Vin', 3, 'Vin', 'Wine', 'van', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(28, 1, 'Soupe', 3, 'Soupe', 'Soup', 'soop', NULL, 'easy', '2024-12-14 13:19:36', 'noun'),
(29, 1, 'Rouge', 4, 'Rouge', 'Red', 'roozh', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(30, 1, 'Bleu', 4, 'Bleu', 'Blue', 'bluh', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(31, 1, 'Vert', 4, 'Vert', 'Green', 'vair', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(32, 1, 'Jaune', 4, 'Jaune', 'Yellow', 'zhohn', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(33, 1, 'Noir', 4, 'Noir', 'Black', 'nwahr', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(34, 1, 'Blanc', 4, 'Blanc', 'White', 'blahn', NULL, 'easy', '2024-12-14 13:19:43', 'adjective'),
(35, 1, 'À bientôt', 1, 'À bientôt', 'See you soon', NULL, NULL, 'easy', '2024-12-15 01:33:06', 'other'),
(36, 1, 'À bientôt', 1, 'À bientôt', 'See you soon', NULL, NULL, 'easy', '2024-12-15 01:34:14', 'other'),
(38, 1, 'À bientôt', 1, 'À bientôt', 'See you soon', NULL, NULL, 'easy', '2024-12-15 01:56:59', 'other'),
(39, 1, 'S\'il vous plaît', 1, 'S\'il vous plaît', 'please', NULL, NULL, 'easy', '2024-12-15 02:25:21', 'other'),
(40, 1, 'un million', 2, 'un million', 'one million', NULL, NULL, 'easy', '2024-12-15 02:28:02', 'other'),
(41, 1, 'Bonjour', 1, 'Bonjour', 'hello', NULL, NULL, 'easy', '2024-12-15 11:08:33', 'other'),
(42, 2, 'Hola', 1, 'Hola', 'Hello', NULL, NULL, 'easy', '2024-12-15 11:24:25', 'other');

-- --------------------------------------------------------

--
-- Table structure for table `word_attempts`
--

CREATE TABLE `word_attempts` (
  `attemptId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `wordId` int(11) NOT NULL,
  `isCorrect` tinyint(1) NOT NULL,
  `attemptDate` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `word_attempts`
--

INSERT INTO `word_attempts` (`attemptId`, `userId`, `wordId`, `isCorrect`, `attemptDate`) VALUES
(1, 1, 1, 1, '2024-12-14 20:19:54'),
(2, 1, 1, 1, '2024-12-14 20:20:08'),
(3, 1, 4, 1, '2024-12-14 20:21:29'),
(4, 1, 1, 1, '2024-12-14 20:31:16'),
(5, 1, 2, 1, '2024-12-14 20:31:23'),
(6, 1, 1, 1, '2024-12-14 20:31:33'),
(7, 1, 4, 1, '2024-12-14 20:31:39'),
(8, 1, 5, 1, '2024-12-14 20:33:43'),
(9, 1, 4, 1, '2024-12-14 20:33:49'),
(10, 1, 5, 1, '2024-12-14 20:34:00'),
(11, 1, 1, 1, '2024-12-14 20:34:06'),
(12, 1, 4, 1, '2024-12-14 20:36:14'),
(13, 1, 3, 1, '2024-12-14 20:36:25'),
(14, 1, 4, 0, '2024-12-14 20:36:34'),
(15, 1, 4, 1, '2024-12-14 20:38:18'),
(16, 1, 2, 1, '2024-12-14 20:44:47'),
(17, 1, 2, 0, '2024-12-14 20:45:15'),
(18, 1, 2, 0, '2024-12-14 20:46:20'),
(19, 1, 2, 0, '2024-12-14 20:47:26'),
(20, 1, 2, 0, '2024-12-14 20:47:42'),
(21, 1, 4, 0, '2024-12-14 20:47:51'),
(22, 1, 4, 0, '2024-12-14 20:48:18'),
(23, 1, 4, 0, '2024-12-14 20:48:22'),
(24, 1, 2, 1, '2024-12-14 20:54:26'),
(25, 1, 3, 0, '2024-12-14 20:54:32'),
(26, 1, 5, 0, '2024-12-14 20:57:10'),
(27, 1, 2, 0, '2024-12-14 20:57:28'),
(28, 1, 2, 0, '2024-12-14 20:57:48'),
(29, 1, 2, 0, '2024-12-14 20:57:55'),
(30, 1, 2, 0, '2024-12-14 20:58:12'),
(31, 1, 2, 0, '2024-12-14 20:58:18'),
(32, 1, 2, 0, '2024-12-14 20:58:24'),
(33, 1, 3, 0, '2024-12-14 20:59:20'),
(34, 1, 3, 0, '2024-12-14 20:59:25'),
(35, 1, 3, 0, '2024-12-14 20:59:31'),
(36, 1, 3, 1, '2024-12-14 21:09:51'),
(37, 1, 3, 1, '2024-12-14 21:09:51'),
(38, 1, 5, 1, '2024-12-14 21:09:58'),
(39, 1, 5, 1, '2024-12-14 21:09:58'),
(40, 1, 2, 1, '2024-12-14 21:10:16'),
(41, 1, 2, 1, '2024-12-14 21:10:16'),
(42, 1, 2, 1, '2024-12-14 21:12:34'),
(43, 1, 2, 1, '2024-12-14 21:12:34'),
(44, 1, 4, 1, '2024-12-14 21:12:59'),
(45, 1, 5, 1, '2024-12-14 21:13:04'),
(46, 1, 4, 1, '2024-12-14 21:14:15'),
(47, 1, 2, 1, '2024-12-14 21:14:23'),
(48, 1, 3, 1, '2024-12-14 21:14:31'),
(49, 1, 5, 1, '2024-12-14 21:24:40'),
(50, 1, 3, 1, '2024-12-14 21:24:50'),
(51, 1, 4, 0, '2024-12-14 21:26:04'),
(52, 1, 4, 0, '2024-12-14 21:26:19'),
(53, 1, 4, 1, '2024-12-14 21:26:26'),
(54, 1, 4, 1, '2024-12-14 21:29:47'),
(55, 1, 4, 1, '2024-12-14 21:29:51'),
(56, 1, 5, 1, '2024-12-14 21:29:55'),
(57, 1, 4, 1, '2024-12-14 21:37:14'),
(58, 1, 3, 1, '2024-12-14 22:02:16'),
(59, 1, 4, 0, '2024-12-14 22:02:21'),
(60, 1, 4, 0, '2024-12-14 22:02:27'),
(61, 1, 4, 1, '2024-12-14 22:02:32'),
(62, 1, 5, 1, '2024-12-14 22:02:37'),
(63, 1, 2, 1, '2024-12-14 22:02:41'),
(64, 1, 3, 1, '2024-12-14 22:02:46'),
(65, 1, 3, 1, '2024-12-14 22:02:50'),
(66, 1, 3, 1, '2024-12-14 22:02:56'),
(67, 1, 4, 1, '2024-12-14 22:03:04'),
(68, 1, 4, 1, '2024-12-14 22:03:09'),
(69, 1, 3, 1, '2024-12-14 22:03:14'),
(70, 1, 5, 1, '2024-12-14 22:03:19'),
(71, 1, 5, 1, '2024-12-14 22:03:23'),
(72, 1, 5, 1, '2024-12-14 22:03:36'),
(73, 1, 3, 1, '2024-12-14 22:03:41'),
(74, 1, 2, 1, '2024-12-14 22:06:27'),
(75, 1, 2, 1, '2024-12-14 22:06:29'),
(76, 1, 5, 0, '2024-12-14 22:06:40'),
(77, 1, 3, 0, '2024-12-14 22:09:41'),
(78, 1, 3, 1, '2024-12-14 22:10:21'),
(79, 1, 2, 0, '2024-12-15 11:27:53'),
(80, 1, 2, 0, '2024-12-15 11:27:59'),
(81, 1, 2, 0, '2024-12-15 11:28:05'),
(82, 1, 2, 0, '2024-12-15 11:28:11'),
(83, 1, 2, 0, '2024-12-15 11:30:18'),
(84, 1, 2, 0, '2024-12-15 11:30:24'),
(85, 1, 4, 0, '2024-12-15 11:30:28'),
(86, 1, 4, 0, '2024-12-15 11:31:48'),
(87, 1, 4, 0, '2024-12-15 11:31:51'),
(88, 1, 3, 0, '2024-12-15 11:31:56'),
(89, 1, 3, 0, '2024-12-15 11:32:01'),
(90, 1, 36, 1, '2024-12-15 11:33:08'),
(91, 1, 5, 0, '2024-12-15 11:34:23'),
(92, 1, 21, 0, '2024-12-15 11:35:16');

-- --------------------------------------------------------

--
-- Table structure for table `word_bank`
--

CREATE TABLE `word_bank` (
  `bankWordId` int(11) NOT NULL,
  `segment_text` varchar(100) NOT NULL,
  `languageId` int(11) NOT NULL,
  `part_of_speech` varchar(50) DEFAULT NULL,
  `difficulty` enum('easy','medium','hard') DEFAULT 'medium',
  `usage_frequency` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `word_bank`
--

INSERT INTO `word_bank` (`bankWordId`, `segment_text`, `languageId`, `part_of_speech`, `difficulty`, `usage_frequency`) VALUES
(1, 'Bonjour', 1, 'greeting', 'easy', 0),
(2, 'Au revoir', 1, 'greeting', 'easy', 0),
(3, 'S\'il vous plaît', 1, 'phrase', 'easy', 0),
(4, 'Merci', 1, 'phrase', 'easy', 0),
(5, 'De rien', 1, 'phrase', 'easy', 0),
(6, 'Un', 1, 'number', 'easy', 0),
(7, 'Deux', 1, 'number', 'easy', 0),
(8, 'Trois', 1, 'number', 'easy', 0),
(9, 'Quatre', 1, 'number', 'easy', 0),
(10, 'Cinq', 1, 'number', 'easy', 0),
(11, 'Six', 1, 'number', 'easy', 0),
(12, 'Sept', 1, 'number', 'easy', 0),
(13, 'Huit', 1, 'number', 'easy', 0),
(14, 'Neuf', 1, 'number', 'easy', 0),
(15, 'Dix', 1, 'number', 'easy', 0),
(16, 'Onze', 1, 'number', 'medium', 0),
(17, 'Douze', 1, 'number', 'medium', 0),
(18, 'Treize', 1, 'number', 'medium', 0),
(19, 'Quatorze', 1, 'number', 'medium', 0),
(20, 'Quinze', 1, 'number', 'medium', 0),
(21, 'Pain', 1, 'noun', 'easy', 0),
(22, 'Eau', 1, 'noun', 'easy', 0),
(23, 'Café', 1, 'noun', 'easy', 0),
(24, 'Lait', 1, 'noun', 'easy', 0),
(25, 'Pomme', 1, 'noun', 'easy', 0),
(26, 'Fromage', 1, 'noun', 'easy', 0),
(27, 'Vin', 1, 'noun', 'easy', 0),
(28, 'Soupe', 1, 'noun', 'easy', 0),
(29, 'Rouge', 1, 'adjective', 'easy', 0),
(30, 'Bleu', 1, 'adjective', 'easy', 0),
(31, 'Vert', 1, 'adjective', 'easy', 0),
(32, 'Jaune', 1, 'adjective', 'easy', 0),
(33, 'Noir', 1, 'adjective', 'easy', 0),
(34, 'Blanc', 1, 'adjective', 'easy', 0),
(35, 'hello', 1, 'greeting', 'easy', 0),
(36, 'goodbye', 1, 'greeting', 'easy', 0),
(37, 'please', 1, 'phrase', 'easy', 0),
(38, 'thank you', 1, 'phrase', 'easy', 0),
(39, 'you\'re welcome', 1, 'phrase', 'easy', 0),
(40, 'one', 1, 'number', 'easy', 0),
(41, 'two', 1, 'number', 'easy', 0),
(42, 'three', 1, 'number', 'easy', 0),
(43, 'four', 1, 'number', 'easy', 0),
(44, 'five', 1, 'number', 'easy', 0),
(45, 'bread', 1, 'noun', 'easy', 0),
(46, 'water', 1, 'noun', 'easy', 0),
(47, 'coffee', 1, 'noun', 'easy', 0),
(48, 'milk', 1, 'noun', 'easy', 0),
(49, 'apple', 1, 'noun', 'easy', 0),
(50, 'cheese', 1, 'noun', 'easy', 0),
(51, 'wine', 1, 'noun', 'easy', 0),
(52, 'soup', 1, 'noun', 'easy', 0),
(53, 'red', 1, 'adjective', 'easy', 0),
(54, 'blue', 1, 'adjective', 'easy', 0),
(55, 'green', 1, 'adjective', 'easy', 0),
(56, 'yellow', 1, 'adjective', 'easy', 0),
(57, 'black', 1, 'adjective', 'easy', 0),
(58, 'white', 1, 'adjective', 'easy', 0),
(59, 'See you soon', 1, NULL, 'medium', 0),
(60, 'Hello', 1, NULL, 'medium', 0),
(61, 'Goodbye', 1, NULL, 'medium', 0),
(62, 'See you soon', 1, NULL, 'medium', 0),
(63, 'Hello', 1, NULL, 'medium', 0),
(64, 'Goodbye', 1, NULL, 'medium', 0),
(65, 'please', 1, NULL, 'medium', 0),
(66, 'thank you', 1, NULL, 'medium', 0),
(67, 'you\'re welcome', 1, NULL, 'medium', 0),
(68, 'one million', 1, NULL, 'medium', 0),
(69, 'one thousand', 1, NULL, 'medium', 0),
(70, 'one', 1, NULL, 'medium', 0),
(71, 'hello', 1, NULL, 'medium', 0),
(72, 'goodbye', 1, NULL, 'medium', 0),
(73, 'please', 1, NULL, 'medium', 0),
(74, 'Hello', 2, NULL, 'medium', 0),
(75, 'Goodbye', 2, NULL, 'medium', 0),
(76, 'Thank you', 2, NULL, 'medium', 0);

-- --------------------------------------------------------

--
-- Table structure for table `word_categories`
--

CREATE TABLE `word_categories` (
  `categoryId` int(11) NOT NULL,
  `categoryName` varchar(50) NOT NULL,
  `categorySlug` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `icon` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `word_categories`
--

INSERT INTO `word_categories` (`categoryId`, `categoryName`, `categorySlug`, `description`, `icon`) VALUES
(1, 'Greetings', 'greetings', 'Basic greetings and introductions', 'wave'),
(2, 'Numbers', 'numbers', 'Cardinal and ordinal numbers', 'numbers'),
(3, 'Food & Drinks', 'food-drinks', 'Common food and beverage terms', 'food'),
(4, 'Colors', 'colors', 'Basic colors and shades', 'palette'),
(5, 'Family', 'family', 'Family members and relationships', 'family'),
(6, 'Common Phrases', 'phrases', 'Everyday useful phrases', 'chat'),
(7, 'Weather', 'weather', 'Weather-related terms and expressions', 'cloud'),
(8, 'Time', 'time', 'Time-related words and expressions', 'clock'),
(9, 'Animals', 'animals', 'Common animal names', 'pet'),
(10, 'Body Parts', 'body-parts', 'Human body parts', 'body');

-- --------------------------------------------------------

--
-- Table structure for table `word_relationships`
--

CREATE TABLE `word_relationships` (
  `relationshipId` int(11) NOT NULL,
  `word1_id` int(11) NOT NULL,
  `word2_id` int(11) NOT NULL,
  `relationship_type` enum('synonym','antonym','related') NOT NULL,
  `strength` int(11) NOT NULL DEFAULT 5
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `word_relationships`
--

INSERT INTO `word_relationships` (`relationshipId`, `word1_id`, `word2_id`, `relationship_type`, `strength`) VALUES
(1, 6, 7, 'related', 5),
(2, 7, 8, 'related', 5),
(3, 8, 9, 'related', 5),
(4, 9, 10, 'related', 5),
(5, 21, 26, 'related', 5),
(6, 22, 24, 'related', 5),
(7, 23, 27, 'related', 5),
(8, 29, 30, 'related', 5),
(9, 31, 32, 'related', 5),
(10, 33, 34, 'antonym', 5);

-- --------------------------------------------------------

--
-- Table structure for table `word_segments`
--

CREATE TABLE `word_segments` (
  `segmentId` int(11) NOT NULL,
  `translationId` int(11) NOT NULL,
  `segment_text` varchar(100) NOT NULL,
  `position` int(11) NOT NULL,
  `part_of_speech` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `exercise_sessions`
--
ALTER TABLE `exercise_sessions`
  ADD PRIMARY KEY (`sessionId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `exerciseSetId` (`exerciseSetId`);

--
-- Indexes for table `exercise_sets`
--
ALTER TABLE `exercise_sets`
  ADD PRIMARY KEY (`exerciseId`),
  ADD KEY `wordId_idx` (`wordId`),
  ADD KEY `translationId_idx` (`translationId`);

--
-- Indexes for table `exercise_templates`
--
ALTER TABLE `exercise_templates`
  ADD PRIMARY KEY (`templateId`),
  ADD KEY `languageId` (`languageId`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `exercise_word_bank`
--
ALTER TABLE `exercise_word_bank`
  ADD PRIMARY KEY (`exerciseId`,`bankWordId`),
  ADD KEY `bankWordId` (`bankWordId`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`languageId`);

--
-- Indexes for table `learned_words`
--
ALTER TABLE `learned_words`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_word_unique` (`userId`,`wordId`),
  ADD KEY `wordId` (`wordId`);

--
-- Indexes for table `session_word_progress`
--
ALTER TABLE `session_word_progress`
  ADD KEY `sessionId` (`sessionId`),
  ADD KEY `wordId` (`wordId`);

--
-- Indexes for table `translations`
--
ALTER TABLE `translations`
  ADD PRIMARY KEY (`translationId`),
  ADD KEY `wordId_idx` (`wordId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- Indexes for table `user_enrollments`
--
ALTER TABLE `user_enrollments`
  ADD PRIMARY KEY (`enrollmentId`),
  ADD UNIQUE KEY `user_language_unique` (`userId`,`languageId`),
  ADD KEY `languageId` (`languageId`);

--
-- Indexes for table `words`
--
ALTER TABLE `words`
  ADD PRIMARY KEY (`wordId`),
  ADD KEY `language_idx` (`languageId`),
  ADD KEY `category_idx` (`category`),
  ADD KEY `difficulty_idx` (`difficulty`),
  ADD KEY `categoryId` (`categoryId`);

--
-- Indexes for table `word_attempts`
--
ALTER TABLE `word_attempts`
  ADD PRIMARY KEY (`attemptId`),
  ADD KEY `userId` (`userId`),
  ADD KEY `wordId` (`wordId`);

--
-- Indexes for table `word_bank`
--
ALTER TABLE `word_bank`
  ADD PRIMARY KEY (`bankWordId`),
  ADD KEY `languageId_idx` (`languageId`);

--
-- Indexes for table `word_categories`
--
ALTER TABLE `word_categories`
  ADD PRIMARY KEY (`categoryId`),
  ADD UNIQUE KEY `categorySlug_unique` (`categorySlug`);

--
-- Indexes for table `word_relationships`
--
ALTER TABLE `word_relationships`
  ADD PRIMARY KEY (`relationshipId`),
  ADD KEY `word1_idx` (`word1_id`),
  ADD KEY `word2_idx` (`word2_id`);

--
-- Indexes for table `word_segments`
--
ALTER TABLE `word_segments`
  ADD PRIMARY KEY (`segmentId`),
  ADD KEY `translationId_idx` (`translationId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `exercise_sessions`
--
ALTER TABLE `exercise_sessions`
  MODIFY `sessionId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `exercise_sets`
--
ALTER TABLE `exercise_sets`
  MODIFY `exerciseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `exercise_templates`
--
ALTER TABLE `exercise_templates`
  MODIFY `templateId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `languageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `learned_words`
--
ALTER TABLE `learned_words`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `translations`
--
ALTER TABLE `translations`
  MODIFY `translationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `user_enrollments`
--
ALTER TABLE `user_enrollments`
  MODIFY `enrollmentId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `words`
--
ALTER TABLE `words`
  MODIFY `wordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=43;

--
-- AUTO_INCREMENT for table `word_attempts`
--
ALTER TABLE `word_attempts`
  MODIFY `attemptId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=93;

--
-- AUTO_INCREMENT for table `word_bank`
--
ALTER TABLE `word_bank`
  MODIFY `bankWordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `word_categories`
--
ALTER TABLE `word_categories`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `word_relationships`
--
ALTER TABLE `word_relationships`
  MODIFY `relationshipId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `word_segments`
--
ALTER TABLE `word_segments`
  MODIFY `segmentId` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `exercise_sessions`
--
ALTER TABLE `exercise_sessions`
  ADD CONSTRAINT `exercise_sessions_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `exercise_sessions_ibfk_2` FOREIGN KEY (`exerciseSetId`) REFERENCES `exercise_sets` (`exerciseId`);

--
-- Constraints for table `exercise_sets`
--
ALTER TABLE `exercise_sets`
  ADD CONSTRAINT `exercise_sets_ibfk_1` FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`),
  ADD CONSTRAINT `exercise_sets_ibfk_2` FOREIGN KEY (`translationId`) REFERENCES `translations` (`translationId`);

--
-- Constraints for table `exercise_templates`
--
ALTER TABLE `exercise_templates`
  ADD CONSTRAINT `exercise_templates_ibfk_1` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`),
  ADD CONSTRAINT `exercise_templates_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `word_categories` (`categoryId`);

--
-- Constraints for table `exercise_word_bank`
--
ALTER TABLE `exercise_word_bank`
  ADD CONSTRAINT `exercise_word_bank_ibfk_1` FOREIGN KEY (`exerciseId`) REFERENCES `exercise_sets` (`exerciseId`),
  ADD CONSTRAINT `exercise_word_bank_ibfk_2` FOREIGN KEY (`bankWordId`) REFERENCES `word_bank` (`bankWordId`);

--
-- Constraints for table `learned_words`
--
ALTER TABLE `learned_words`
  ADD CONSTRAINT `learned_words_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `learned_words_ibfk_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`);

--
-- Constraints for table `session_word_progress`
--
ALTER TABLE `session_word_progress`
  ADD CONSTRAINT `session_word_progress_ibfk_1` FOREIGN KEY (`sessionId`) REFERENCES `exercise_sessions` (`sessionId`),
  ADD CONSTRAINT `session_word_progress_ibfk_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`);

--
-- Constraints for table `translations`
--
ALTER TABLE `translations`
  ADD CONSTRAINT `translations_ibfk_1` FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`) ON DELETE CASCADE;

--
-- Constraints for table `user_enrollments`
--
ALTER TABLE `user_enrollments`
  ADD CONSTRAINT `user_enrollments_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_enrollments_ibfk_2` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`);

--
-- Constraints for table `words`
--
ALTER TABLE `words`
  ADD CONSTRAINT `words_ibfk_1` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`),
  ADD CONSTRAINT `words_ibfk_2` FOREIGN KEY (`categoryId`) REFERENCES `word_categories` (`categoryId`);

--
-- Constraints for table `word_attempts`
--
ALTER TABLE `word_attempts`
  ADD CONSTRAINT `word_attempts_ibfk_1` FOREIGN KEY (`userId`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `word_attempts_ibfk_2` FOREIGN KEY (`wordId`) REFERENCES `words` (`wordId`);

--
-- Constraints for table `word_bank`
--
ALTER TABLE `word_bank`
  ADD CONSTRAINT `word_bank_ibfk_1` FOREIGN KEY (`languageId`) REFERENCES `languages` (`languageId`);

--
-- Constraints for table `word_relationships`
--
ALTER TABLE `word_relationships`
  ADD CONSTRAINT `word_relationships_ibfk_1` FOREIGN KEY (`word1_id`) REFERENCES `word_bank` (`bankWordId`),
  ADD CONSTRAINT `word_relationships_ibfk_2` FOREIGN KEY (`word2_id`) REFERENCES `word_bank` (`bankWordId`);

--
-- Constraints for table `word_segments`
--
ALTER TABLE `word_segments`
  ADD CONSTRAINT `word_segments_ibfk_1` FOREIGN KEY (`translationId`) REFERENCES `translations` (`translationId`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
