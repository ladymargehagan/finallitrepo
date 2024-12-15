-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2024 at 02:42 PM
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

--
-- Dumping data for table `exercise_sets`
--

INSERT INTO `exercise_sets` (`exerciseId`, `wordId`, `translationId`, `difficulty`, `type`, `created_at`) VALUES
(57, 43, 47, 'easy', 'translation', '2024-12-15 12:14:26'),
(58, 44, 48, 'easy', 'translation', '2024-12-15 12:14:26'),
(59, 45, 49, 'easy', 'translation', '2024-12-15 12:14:26'),
(60, 46, 50, 'easy', 'translation', '2024-12-15 12:14:26'),
(61, 47, 51, 'easy', 'translation', '2024-12-15 12:14:26'),
(62, 48, 52, 'easy', 'translation', '2024-12-15 12:14:26'),
(63, 49, 53, 'easy', 'translation', '2024-12-15 12:14:26'),
(64, 50, 54, 'easy', 'translation', '2024-12-15 12:14:26'),
(65, 51, 55, 'easy', 'translation', '2024-12-15 12:14:26'),
(66, 52, 56, 'easy', 'translation', '2024-12-15 12:14:26'),
(67, 53, 57, 'easy', 'translation', '2024-12-15 12:14:26'),
(68, 54, 58, 'easy', 'translation', '2024-12-15 12:14:26'),
(69, 55, 59, 'easy', 'translation', '2024-12-15 12:14:26'),
(70, 56, 60, 'easy', 'translation', '2024-12-15 12:14:26'),
(71, 57, 61, 'easy', 'translation', '2024-12-15 12:14:26'),
(72, 58, 62, 'easy', 'translation', '2024-12-15 12:14:26'),
(73, 59, 63, 'easy', 'translation', '2024-12-15 12:14:26'),
(74, 60, 64, 'easy', 'translation', '2024-12-15 12:14:26'),
(75, 61, 65, 'easy', 'translation', '2024-12-15 12:14:26'),
(76, 62, 66, 'easy', 'translation', '2024-12-15 12:14:26'),
(88, 63, 78, 'easy', 'translation', '2024-12-15 12:14:26'),
(89, 64, 79, 'easy', 'translation', '2024-12-15 12:14:26'),
(90, 65, 80, 'easy', 'translation', '2024-12-15 12:14:26'),
(91, 66, 81, 'easy', 'translation', '2024-12-15 12:14:26'),
(92, 67, 82, 'easy', 'translation', '2024-12-15 12:14:26'),
(93, 68, 83, 'easy', 'translation', '2024-12-15 12:14:26'),
(94, 69, 84, 'easy', 'translation', '2024-12-15 12:14:26'),
(95, 70, 85, 'easy', 'translation', '2024-12-15 12:14:26'),
(96, 71, 86, 'easy', 'translation', '2024-12-15 12:14:26'),
(97, 72, 87, 'easy', 'translation', '2024-12-15 12:14:26'),
(98, 73, 88, 'easy', 'translation', '2024-12-15 12:14:26'),
(99, 74, 89, 'easy', 'translation', '2024-12-15 12:14:26'),
(100, 75, 90, 'easy', 'translation', '2024-12-15 12:14:26'),
(101, 76, 91, 'easy', 'translation', '2024-12-15 12:14:26'),
(102, 77, 92, 'easy', 'translation', '2024-12-15 12:14:26'),
(103, 78, 93, 'easy', 'translation', '2024-12-15 12:14:26'),
(104, 79, 94, 'easy', 'translation', '2024-12-15 12:14:26'),
(105, 80, 95, 'easy', 'translation', '2024-12-15 12:14:26'),
(106, 81, 96, 'easy', 'translation', '2024-12-15 12:14:26'),
(107, 82, 97, 'easy', 'translation', '2024-12-15 12:14:26'),
(119, 83, 109, 'easy', 'translation', '2024-12-15 12:14:26'),
(120, 84, 110, 'easy', 'translation', '2024-12-15 12:14:26'),
(121, 85, 111, 'easy', 'translation', '2024-12-15 12:14:26'),
(122, 86, 112, 'easy', 'translation', '2024-12-15 12:14:26'),
(123, 87, 113, 'easy', 'translation', '2024-12-15 12:14:26'),
(124, 88, 114, 'easy', 'translation', '2024-12-15 12:14:26'),
(125, 89, 115, 'easy', 'translation', '2024-12-15 12:14:26'),
(126, 90, 116, 'easy', 'translation', '2024-12-15 12:14:26'),
(127, 91, 117, 'easy', 'translation', '2024-12-15 12:14:26'),
(128, 92, 118, 'easy', 'translation', '2024-12-15 12:14:26'),
(129, 93, 119, 'easy', 'translation', '2024-12-15 12:14:26'),
(130, 94, 120, 'easy', 'translation', '2024-12-15 12:14:26'),
(131, 95, 121, 'easy', 'translation', '2024-12-15 12:14:26'),
(132, 96, 122, 'easy', 'translation', '2024-12-15 12:14:26'),
(133, 97, 123, 'easy', 'translation', '2024-12-15 12:14:26'),
(134, 98, 124, 'easy', 'translation', '2024-12-15 12:14:26'),
(135, 99, 125, 'easy', 'translation', '2024-12-15 12:14:26'),
(136, 100, 126, 'easy', 'translation', '2024-12-15 12:14:26'),
(137, 101, 127, 'easy', 'translation', '2024-12-15 12:14:26'),
(138, 102, 128, 'easy', 'translation', '2024-12-15 12:14:26');

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
(3, 'German', 'Beginner', 'Coming Soon', 1, 'en'),
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
(47, 43, 'hello', 1, '2024-12-15 12:11:21'),
(48, 44, 'goodbye', 1, '2024-12-15 12:11:21'),
(49, 45, 'thank you', 1, '2024-12-15 12:11:21'),
(50, 46, 'please', 1, '2024-12-15 12:11:21'),
(51, 47, 'you\'re welcome', 1, '2024-12-15 12:11:21'),
(52, 48, 'one', 1, '2024-12-15 12:11:21'),
(53, 49, 'two', 1, '2024-12-15 12:11:21'),
(54, 50, 'three', 1, '2024-12-15 12:11:21'),
(55, 51, 'four', 1, '2024-12-15 12:11:21'),
(56, 52, 'five', 1, '2024-12-15 12:11:21'),
(57, 53, 'bread', 1, '2024-12-15 12:11:21'),
(58, 54, 'coffee', 1, '2024-12-15 12:11:21'),
(59, 55, 'milk', 1, '2024-12-15 12:11:21'),
(60, 56, 'water', 1, '2024-12-15 12:11:21'),
(61, 57, 'cheese', 1, '2024-12-15 12:11:21'),
(62, 58, 'red', 1, '2024-12-15 12:11:21'),
(63, 59, 'blue', 1, '2024-12-15 12:11:21'),
(64, 60, 'green', 1, '2024-12-15 12:11:21'),
(65, 61, 'yellow', 1, '2024-12-15 12:11:21'),
(66, 62, 'black', 1, '2024-12-15 12:11:21'),
(78, 63, 'hello', 1, '2024-12-15 12:11:21'),
(79, 64, 'goodbye', 1, '2024-12-15 12:11:21'),
(80, 65, 'thank you', 1, '2024-12-15 12:11:21'),
(81, 66, 'please', 1, '2024-12-15 12:11:21'),
(82, 67, 'you\'re welcome', 1, '2024-12-15 12:11:21'),
(83, 68, 'one', 1, '2024-12-15 12:11:21'),
(84, 69, 'two', 1, '2024-12-15 12:11:21'),
(85, 70, 'three', 1, '2024-12-15 12:11:21'),
(86, 71, 'four', 1, '2024-12-15 12:11:21'),
(87, 72, 'five', 1, '2024-12-15 12:11:21'),
(88, 73, 'bread', 1, '2024-12-15 12:11:21'),
(89, 74, 'coffee', 1, '2024-12-15 12:11:21'),
(90, 75, 'milk', 1, '2024-12-15 12:11:21'),
(91, 76, 'water', 1, '2024-12-15 12:11:21'),
(92, 77, 'cheese', 1, '2024-12-15 12:11:21'),
(93, 78, 'red', 1, '2024-12-15 12:11:21'),
(94, 79, 'blue', 1, '2024-12-15 12:11:21'),
(95, 80, 'green', 1, '2024-12-15 12:11:21'),
(96, 81, 'yellow', 1, '2024-12-15 12:11:21'),
(97, 82, 'black', 1, '2024-12-15 12:11:21'),
(109, 83, 'hello', 1, '2024-12-15 12:11:21'),
(110, 84, 'goodbye', 1, '2024-12-15 12:11:21'),
(111, 85, 'thank you', 1, '2024-12-15 12:11:21'),
(112, 86, 'please', 1, '2024-12-15 12:11:21'),
(113, 87, 'you\'re welcome', 1, '2024-12-15 12:11:21'),
(114, 88, 'one', 1, '2024-12-15 12:11:21'),
(115, 89, 'two', 1, '2024-12-15 12:11:21'),
(116, 90, 'three', 1, '2024-12-15 12:11:21'),
(117, 91, 'four', 1, '2024-12-15 12:11:21'),
(118, 92, 'five', 1, '2024-12-15 12:11:21'),
(119, 93, 'bread', 1, '2024-12-15 12:11:21'),
(120, 94, 'coffee', 1, '2024-12-15 12:11:21'),
(121, 95, 'milk', 1, '2024-12-15 12:11:21'),
(122, 96, 'water', 1, '2024-12-15 12:11:21'),
(123, 97, 'cheese', 1, '2024-12-15 12:11:21'),
(124, 98, 'red', 1, '2024-12-15 12:11:21'),
(125, 99, 'blue', 1, '2024-12-15 12:11:21'),
(126, 100, 'green', 1, '2024-12-15 12:11:21'),
(127, 101, 'yellow', 1, '2024-12-15 12:11:21'),
(128, 102, 'black', 1, '2024-12-15 12:11:21');

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
(43, 1, 'Bonjour', 1, 'bonjour', 'hello', 'bohn-ZHOOR', 'greetings', 'easy', '2024-12-15 12:04:02', 'other'),
(44, 1, 'Au revoir', 1, 'au revoir', 'goodbye', 'oh ruh-VWAR', 'greetings', 'easy', '2024-12-15 12:04:02', 'other'),
(45, 1, 'Merci', 1, 'merci', 'thank you', 'mair-SEE', 'greetings', 'easy', '2024-12-15 12:04:02', 'other'),
(46, 1, 'S\'il vous plaît', 1, 's\'il vous plaît', 'please', 'seel voo PLEH', 'greetings', 'easy', '2024-12-15 12:04:02', 'other'),
(47, 1, 'De rien', 1, 'de rien', 'you\'re welcome', 'duh ree-YEN', 'greetings', 'easy', '2024-12-15 12:04:02', 'other'),
(48, 1, 'Un', 2, 'un', 'one', 'uhn', 'numbers', 'easy', '2024-12-15 12:04:02', 'other'),
(49, 1, 'Deux', 2, 'deux', 'two', 'duh', 'numbers', 'easy', '2024-12-15 12:04:02', 'other'),
(50, 1, 'Trois', 2, 'trois', 'three', 'twah', 'numbers', 'easy', '2024-12-15 12:04:02', 'other'),
(51, 1, 'Quatre', 2, 'quatre', 'four', 'KAT-ruh', 'numbers', 'easy', '2024-12-15 12:04:02', 'other'),
(52, 1, 'Cinq', 2, 'cinq', 'five', 'sank', 'numbers', 'easy', '2024-12-15 12:04:02', 'other'),
(53, 1, 'Pain', 3, 'pain', 'bread', 'pan', 'food', 'easy', '2024-12-15 12:04:02', 'noun'),
(54, 1, 'Café', 3, 'café', 'coffee', 'ka-FAY', 'food', 'easy', '2024-12-15 12:04:02', 'noun'),
(55, 1, 'Lait', 3, 'lait', 'milk', 'lay', 'food', 'easy', '2024-12-15 12:04:02', 'noun'),
(56, 1, 'Eau', 3, 'eau', 'water', 'oh', 'food', 'easy', '2024-12-15 12:04:02', 'noun'),
(57, 1, 'Fromage', 3, 'fromage', 'cheese', 'froh-MAHZH', 'food', 'easy', '2024-12-15 12:04:02', 'noun'),
(58, 1, 'Rouge', 4, 'rouge', 'red', 'roozh', 'colors', 'easy', '2024-12-15 12:04:02', 'adjective'),
(59, 1, 'Bleu', 4, 'bleu', 'blue', 'bluh', 'colors', 'easy', '2024-12-15 12:04:02', 'adjective'),
(60, 1, 'Vert', 4, 'vert', 'green', 'vair', 'colors', 'easy', '2024-12-15 12:04:02', 'adjective'),
(61, 1, 'Jaune', 4, 'jaune', 'yellow', 'zhohn', 'colors', 'easy', '2024-12-15 12:04:02', 'adjective'),
(62, 1, 'Noir', 4, 'noir', 'black', 'nwahr', 'colors', 'easy', '2024-12-15 12:04:02', 'adjective'),
(63, 2, 'Hola', 1, 'hola', 'hello', 'OH-lah', 'greetings', 'easy', '2024-12-15 12:05:07', 'other'),
(64, 2, 'Adiós', 1, 'adiós', 'goodbye', 'ah-dee-OHS', 'greetings', 'easy', '2024-12-15 12:05:07', 'other'),
(65, 2, 'Gracias', 1, 'gracias', 'thank you', 'GRAH-see-ahs', 'greetings', 'easy', '2024-12-15 12:05:07', 'other'),
(66, 2, 'Por favor', 1, 'por favor', 'please', 'por fah-VOR', 'greetings', 'easy', '2024-12-15 12:05:07', 'other'),
(67, 2, 'De nada', 1, 'de nada', 'you\'re welcome', 'day NAH-dah', 'greetings', 'easy', '2024-12-15 12:05:07', 'other'),
(68, 2, 'Uno', 2, 'uno', 'one', 'OO-noh', 'numbers', 'easy', '2024-12-15 12:05:07', 'other'),
(69, 2, 'Dos', 2, 'dos', 'two', 'dohs', 'numbers', 'easy', '2024-12-15 12:05:07', 'other'),
(70, 2, 'Tres', 2, 'tres', 'three', 'trehs', 'numbers', 'easy', '2024-12-15 12:05:07', 'other'),
(71, 2, 'Cuatro', 2, 'cuatro', 'four', 'KWAH-troh', 'numbers', 'easy', '2024-12-15 12:05:07', 'other'),
(72, 2, 'Cinco', 2, 'cinco', 'five', 'SEEN-koh', 'numbers', 'easy', '2024-12-15 12:05:07', 'other'),
(73, 2, 'Pan', 3, 'pan', 'bread', 'pahn', 'food', 'easy', '2024-12-15 12:05:07', 'noun'),
(74, 2, 'Café', 3, 'café', 'coffee', 'kah-FEH', 'food', 'easy', '2024-12-15 12:05:07', 'noun'),
(75, 2, 'Leche', 3, 'leche', 'milk', 'LEH-cheh', 'food', 'easy', '2024-12-15 12:05:07', 'noun'),
(76, 2, 'Agua', 3, 'agua', 'water', 'AH-gwah', 'food', 'easy', '2024-12-15 12:05:07', 'noun'),
(77, 2, 'Queso', 3, 'queso', 'cheese', 'KEH-soh', 'food', 'easy', '2024-12-15 12:05:07', 'noun'),
(78, 2, 'Rojo', 4, 'rojo', 'red', 'ROH-hoh', 'colors', 'easy', '2024-12-15 12:05:07', 'adjective'),
(79, 2, 'Azul', 4, 'azul', 'blue', 'ah-SOOL', 'colors', 'easy', '2024-12-15 12:05:07', 'adjective'),
(80, 2, 'Verde', 4, 'verde', 'green', 'BEHR-deh', 'colors', 'easy', '2024-12-15 12:05:07', 'adjective'),
(81, 2, 'Amarillo', 4, 'amarillo', 'yellow', 'ah-mah-REE-yoh', 'colors', 'easy', '2024-12-15 12:05:07', 'adjective'),
(82, 2, 'Negro', 4, 'negro', 'black', 'NEH-groh', 'colors', 'easy', '2024-12-15 12:05:07', 'adjective'),
(83, 3, 'Hallo', 1, 'hallo', 'hello', 'HAL-oh', 'greetings', 'easy', '2024-12-15 12:06:37', 'other'),
(84, 3, 'Auf Wiedersehen', 1, 'auf wiedersehen', 'goodbye', 'owf VEE-der-zey-en', 'greetings', 'easy', '2024-12-15 12:06:37', 'other'),
(85, 3, 'Danke', 1, 'danke', 'thank you', 'DAHN-kuh', 'greetings', 'easy', '2024-12-15 12:06:37', 'other'),
(86, 3, 'Bitte', 1, 'bitte', 'please', 'BIT-tuh', 'greetings', 'easy', '2024-12-15 12:06:37', 'other'),
(87, 3, 'Gern geschehen', 1, 'gern geschehen', 'you\'re welcome', 'gern guh-SHEY-en', 'greetings', 'easy', '2024-12-15 12:06:37', 'other'),
(88, 3, 'Eins', 2, 'eins', 'one', 'EYNS', 'numbers', 'easy', '2024-12-15 12:06:37', 'other'),
(89, 3, 'Zwei', 2, 'zwei', 'two', 'TSVEY', 'numbers', 'easy', '2024-12-15 12:06:37', 'other'),
(90, 3, 'Drei', 2, 'drei', 'three', 'DREY', 'numbers', 'easy', '2024-12-15 12:06:37', 'other'),
(91, 3, 'Vier', 2, 'vier', 'four', 'FEER', 'numbers', 'easy', '2024-12-15 12:06:37', 'other'),
(92, 3, 'Fünf', 2, 'fünf', 'five', 'FUENF', 'numbers', 'easy', '2024-12-15 12:06:37', 'other'),
(93, 3, 'Brot', 3, 'brot', 'bread', 'BROHT', 'food', 'easy', '2024-12-15 12:06:37', 'noun'),
(94, 3, 'Kaffee', 3, 'kaffee', 'coffee', 'KAF-fey', 'food', 'easy', '2024-12-15 12:06:37', 'noun'),
(95, 3, 'Milch', 3, 'milch', 'milk', 'MILH', 'food', 'easy', '2024-12-15 12:06:37', 'noun'),
(96, 3, 'Wasser', 3, 'wasser', 'water', 'VAS-ser', 'food', 'easy', '2024-12-15 12:06:37', 'noun'),
(97, 3, 'Käse', 3, 'käse', 'cheese', 'KEY-zuh', 'food', 'easy', '2024-12-15 12:06:37', 'noun'),
(98, 3, 'Rot', 4, 'rot', 'red', 'roht', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective'),
(99, 3, 'Blau', 4, 'blau', 'blue', 'blow', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective'),
(100, 3, 'Grün', 4, 'grün', 'green', 'gruen', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective'),
(101, 3, 'Gelb', 4, 'gelb', 'yellow', 'gelp', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective'),
(102, 3, 'Schwarz', 4, 'schwarz', 'black', 'shvarts', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective');

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
(95, 1, 45, 1, '2024-12-15 12:38:13'),
(96, 1, 44, 0, '2024-12-15 12:49:53'),
(97, 1, 44, 0, '2024-12-15 12:49:57'),
(98, 1, 44, 0, '2024-12-15 12:50:02'),
(99, 1, 45, 1, '2024-12-15 13:03:35'),
(100, 1, 47, 1, '2024-12-15 13:39:46');

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
(233, 'bonjour', 1, 'other', 'easy', 0),
(234, 'au revoir', 1, 'other', 'easy', 0),
(235, 'merci', 1, 'other', 'easy', 0),
(236, 's\'il vous plaît', 1, 'other', 'easy', 0),
(237, 'de rien', 1, 'other', 'easy', 0),
(238, 'un', 1, 'other', 'easy', 0),
(239, 'deux', 1, 'other', 'easy', 0),
(240, 'trois', 1, 'other', 'easy', 0),
(241, 'quatre', 1, 'other', 'easy', 0),
(242, 'cinq', 1, 'other', 'easy', 0),
(243, 'pain', 1, 'noun', 'easy', 0),
(244, 'café', 1, 'noun', 'easy', 0),
(245, 'lait', 1, 'noun', 'easy', 0),
(246, 'eau', 1, 'noun', 'easy', 0),
(247, 'fromage', 1, 'noun', 'easy', 0),
(248, 'rouge', 1, 'adjective', 'easy', 0),
(249, 'bleu', 1, 'adjective', 'easy', 0),
(250, 'vert', 1, 'adjective', 'easy', 0),
(251, 'jaune', 1, 'adjective', 'easy', 0),
(252, 'noir', 1, 'adjective', 'easy', 0),
(253, 'hola', 2, 'other', 'easy', 0),
(254, 'adiós', 2, 'other', 'easy', 0),
(255, 'gracias', 2, 'other', 'easy', 0),
(256, 'por favor', 2, 'other', 'easy', 0),
(257, 'de nada', 2, 'other', 'easy', 0),
(258, 'uno', 2, 'other', 'easy', 0),
(259, 'dos', 2, 'other', 'easy', 0),
(260, 'tres', 2, 'other', 'easy', 0),
(261, 'cuatro', 2, 'other', 'easy', 0),
(262, 'cinco', 2, 'other', 'easy', 0),
(263, 'pan', 2, 'noun', 'easy', 0),
(264, 'café', 2, 'noun', 'easy', 0),
(265, 'leche', 2, 'noun', 'easy', 0),
(266, 'agua', 2, 'noun', 'easy', 0),
(267, 'queso', 2, 'noun', 'easy', 0),
(268, 'rojo', 2, 'adjective', 'easy', 0),
(269, 'azul', 2, 'adjective', 'easy', 0),
(270, 'verde', 2, 'adjective', 'easy', 0),
(271, 'amarillo', 2, 'adjective', 'easy', 0),
(272, 'negro', 2, 'adjective', 'easy', 0),
(273, 'hallo', 3, 'other', 'easy', 0),
(274, 'auf wiedersehen', 3, 'other', 'easy', 0),
(275, 'danke', 3, 'other', 'easy', 0),
(276, 'bitte', 3, 'other', 'easy', 0),
(277, 'gern geschehen', 3, 'other', 'easy', 0),
(278, 'eins', 3, 'other', 'easy', 0),
(279, 'zwei', 3, 'other', 'easy', 0),
(280, 'drei', 3, 'other', 'easy', 0),
(281, 'vier', 3, 'other', 'easy', 0),
(282, 'fünf', 3, 'other', 'easy', 0),
(283, 'brot', 3, 'noun', 'easy', 0),
(284, 'kaffee', 3, 'noun', 'easy', 0),
(285, 'milch', 3, 'noun', 'easy', 0),
(286, 'wasser', 3, 'noun', 'easy', 0),
(287, 'käse', 3, 'noun', 'easy', 0),
(288, 'rot', 3, 'adjective', 'easy', 0),
(289, 'blau', 3, 'adjective', 'easy', 0),
(290, 'grün', 3, 'adjective', 'easy', 0),
(291, 'gelb', 3, 'adjective', 'easy', 0),
(292, 'schwarz', 3, 'adjective', 'easy', 0);

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
(270, 233, 234, 'related', 5),
(271, 233, 235, 'related', 5),
(272, 233, 236, 'related', 5),
(273, 233, 237, 'related', 5),
(274, 234, 235, 'related', 5),
(275, 234, 236, 'related', 5),
(276, 234, 237, 'related', 5),
(277, 235, 236, 'related', 5),
(278, 235, 237, 'related', 5),
(279, 236, 237, 'related', 5),
(280, 238, 239, 'related', 5),
(281, 238, 240, 'related', 5),
(282, 238, 241, 'related', 5),
(283, 238, 242, 'related', 5),
(284, 239, 240, 'related', 5),
(285, 239, 241, 'related', 5),
(286, 239, 242, 'related', 5),
(287, 240, 241, 'related', 5),
(288, 240, 242, 'related', 5),
(289, 241, 242, 'related', 5),
(290, 243, 244, 'related', 5),
(291, 243, 264, 'related', 5),
(292, 243, 245, 'related', 5),
(293, 243, 246, 'related', 5),
(294, 243, 247, 'related', 5),
(295, 244, 245, 'related', 5),
(296, 244, 246, 'related', 5),
(297, 244, 247, 'related', 5),
(298, 244, 263, 'related', 5),
(299, 244, 265, 'related', 5),
(300, 244, 266, 'related', 5),
(301, 244, 267, 'related', 5),
(302, 245, 264, 'related', 5),
(303, 245, 246, 'related', 5),
(304, 245, 247, 'related', 5),
(305, 246, 264, 'related', 5),
(306, 246, 247, 'related', 5),
(307, 247, 264, 'related', 5),
(308, 248, 249, 'related', 5),
(309, 248, 250, 'related', 5),
(310, 248, 251, 'related', 5),
(311, 248, 252, 'related', 5),
(312, 249, 250, 'related', 5),
(313, 249, 251, 'related', 5),
(314, 249, 252, 'related', 5),
(315, 250, 251, 'related', 5),
(316, 250, 252, 'related', 5),
(317, 251, 252, 'related', 5),
(318, 253, 254, 'related', 5),
(319, 253, 255, 'related', 5),
(320, 253, 256, 'related', 5),
(321, 253, 257, 'related', 5),
(322, 254, 255, 'related', 5),
(323, 254, 256, 'related', 5),
(324, 254, 257, 'related', 5),
(325, 255, 256, 'related', 5),
(326, 255, 257, 'related', 5),
(327, 256, 257, 'related', 5),
(328, 258, 259, 'related', 5),
(329, 258, 260, 'related', 5),
(330, 258, 261, 'related', 5),
(331, 258, 262, 'related', 5),
(332, 259, 260, 'related', 5),
(333, 259, 261, 'related', 5),
(334, 259, 262, 'related', 5),
(335, 260, 261, 'related', 5),
(336, 260, 262, 'related', 5),
(337, 261, 262, 'related', 5),
(338, 263, 264, 'related', 5),
(339, 263, 265, 'related', 5),
(340, 263, 266, 'related', 5),
(341, 263, 267, 'related', 5),
(342, 264, 265, 'related', 5),
(343, 264, 266, 'related', 5),
(344, 264, 267, 'related', 5),
(345, 265, 266, 'related', 5),
(346, 265, 267, 'related', 5),
(347, 266, 267, 'related', 5),
(348, 268, 269, 'related', 5),
(349, 268, 270, 'related', 5),
(350, 268, 271, 'related', 5),
(351, 268, 272, 'related', 5),
(352, 269, 270, 'related', 5),
(353, 269, 271, 'related', 5),
(354, 269, 272, 'related', 5),
(355, 270, 271, 'related', 5),
(356, 270, 272, 'related', 5),
(357, 271, 272, 'related', 5),
(358, 273, 274, 'related', 5),
(359, 273, 275, 'related', 5),
(360, 273, 276, 'related', 5),
(361, 273, 277, 'related', 5),
(362, 274, 275, 'related', 5),
(363, 274, 276, 'related', 5),
(364, 274, 277, 'related', 5),
(365, 275, 276, 'related', 5),
(366, 275, 277, 'related', 5),
(367, 276, 277, 'related', 5),
(368, 278, 279, 'related', 5),
(369, 278, 280, 'related', 5),
(370, 278, 281, 'related', 5),
(371, 278, 282, 'related', 5),
(372, 279, 280, 'related', 5),
(373, 279, 281, 'related', 5),
(374, 279, 282, 'related', 5),
(375, 280, 281, 'related', 5),
(376, 280, 282, 'related', 5),
(377, 281, 282, 'related', 5),
(378, 283, 284, 'related', 5),
(379, 283, 285, 'related', 5),
(380, 283, 286, 'related', 5),
(381, 283, 287, 'related', 5),
(382, 284, 285, 'related', 5),
(383, 284, 286, 'related', 5),
(384, 284, 287, 'related', 5),
(385, 285, 286, 'related', 5),
(386, 285, 287, 'related', 5),
(387, 286, 287, 'related', 5),
(388, 288, 289, 'related', 5),
(389, 288, 290, 'related', 5),
(390, 288, 291, 'related', 5),
(391, 288, 292, 'related', 5),
(392, 289, 290, 'related', 5),
(393, 289, 291, 'related', 5),
(394, 289, 292, 'related', 5),
(395, 290, 291, 'related', 5),
(396, 290, 292, 'related', 5),
(397, 291, 292, 'related', 5);

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
  MODIFY `exerciseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=150;

--
-- AUTO_INCREMENT for table `exercise_templates`
--
ALTER TABLE `exercise_templates`
  MODIFY `templateId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

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
  MODIFY `translationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

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
  MODIFY `wordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `word_attempts`
--
ALTER TABLE `word_attempts`
  MODIFY `attemptId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=101;

--
-- AUTO_INCREMENT for table `word_bank`
--
ALTER TABLE `word_bank`
  MODIFY `bankWordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=296;

--
-- AUTO_INCREMENT for table `word_categories`
--
ALTER TABLE `word_categories`
  MODIFY `categoryId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `word_relationships`
--
ALTER TABLE `word_relationships`
  MODIFY `relationshipId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=525;

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
