-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Dec 15, 2024 at 04:21 PM
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
(138, 102, 128, 'easy', 'translation', '2024-12-15 12:14:26'),
(150, 103, 140, 'easy', 'translation', '2024-12-15 15:12:43'),
(151, 104, 141, 'easy', 'translation', '2024-12-15 15:12:43'),
(152, 105, 142, 'easy', 'translation', '2024-12-15 15:12:43'),
(153, 106, 143, 'easy', 'translation', '2024-12-15 15:12:43'),
(154, 107, 144, 'easy', 'translation', '2024-12-15 15:12:43'),
(155, 108, 145, 'easy', 'translation', '2024-12-15 15:12:43'),
(156, 109, 146, 'easy', 'translation', '2024-12-15 15:12:43'),
(157, 110, 147, 'easy', 'translation', '2024-12-15 15:12:43'),
(158, 111, 148, 'easy', 'translation', '2024-12-15 15:12:43'),
(159, 112, 149, 'easy', 'translation', '2024-12-15 15:12:43'),
(160, 113, 150, 'easy', 'translation', '2024-12-15 15:12:43'),
(161, 114, 151, 'easy', 'translation', '2024-12-15 15:12:43'),
(162, 115, 152, 'easy', 'translation', '2024-12-15 15:12:43'),
(163, 116, 153, 'easy', 'translation', '2024-12-15 15:12:43'),
(164, 117, 154, 'easy', 'translation', '2024-12-15 15:12:43'),
(165, 118, 155, 'easy', 'translation', '2024-12-15 15:12:43'),
(166, 119, 156, 'easy', 'translation', '2024-12-15 15:12:43'),
(167, 120, 157, 'easy', 'translation', '2024-12-15 15:12:43'),
(168, 121, 158, 'easy', 'translation', '2024-12-15 15:12:43'),
(169, 122, 159, 'easy', 'translation', '2024-12-15 15:12:43'),
(170, 123, 160, 'easy', 'translation', '2024-12-15 15:12:43'),
(171, 124, 161, 'easy', 'translation', '2024-12-15 15:12:43'),
(172, 125, 162, 'easy', 'translation', '2024-12-15 15:12:43'),
(173, 126, 163, 'easy', 'translation', '2024-12-15 15:12:43'),
(174, 127, 164, 'easy', 'translation', '2024-12-15 15:12:43'),
(175, 128, 165, 'easy', 'translation', '2024-12-15 15:12:43'),
(176, 129, 166, 'easy', 'translation', '2024-12-15 15:12:43'),
(177, 130, 167, 'easy', 'translation', '2024-12-15 15:12:43'),
(178, 131, 168, 'easy', 'translation', '2024-12-15 15:12:43'),
(179, 132, 169, 'easy', 'translation', '2024-12-15 15:12:43'),
(180, 133, 170, 'easy', 'translation', '2024-12-15 15:12:43'),
(181, 134, 171, 'easy', 'translation', '2024-12-15 15:12:43'),
(182, 135, 172, 'easy', 'translation', '2024-12-15 15:12:43'),
(183, 136, 173, 'easy', 'translation', '2024-12-15 15:12:43'),
(184, 137, 174, 'easy', 'translation', '2024-12-15 15:12:43'),
(185, 138, 175, 'easy', 'translation', '2024-12-15 15:12:43'),
(186, 139, 176, 'easy', 'translation', '2024-12-15 15:12:43'),
(187, 140, 177, 'easy', 'translation', '2024-12-15 15:12:43'),
(188, 141, 178, 'easy', 'translation', '2024-12-15 15:12:43'),
(189, 142, 179, 'easy', 'translation', '2024-12-15 15:12:43'),
(190, 143, 180, 'easy', 'translation', '2024-12-15 15:12:43'),
(191, 144, 181, 'easy', 'translation', '2024-12-15 15:12:43'),
(192, 145, 182, 'easy', 'translation', '2024-12-15 15:12:43'),
(193, 146, 183, 'easy', 'translation', '2024-12-15 15:12:43'),
(194, 147, 184, 'easy', 'translation', '2024-12-15 15:12:43'),
(195, 148, 185, 'easy', 'translation', '2024-12-15 15:12:43'),
(196, 149, 186, 'easy', 'translation', '2024-12-15 15:12:43'),
(197, 150, 187, 'easy', 'translation', '2024-12-15 15:12:43'),
(198, 151, 188, 'easy', 'translation', '2024-12-15 15:12:43'),
(199, 152, 189, 'easy', 'translation', '2024-12-15 15:12:43'),
(200, 153, 190, 'easy', 'translation', '2024-12-15 15:12:43'),
(201, 154, 191, 'easy', 'translation', '2024-12-15 15:12:43'),
(202, 155, 192, 'easy', 'translation', '2024-12-15 15:12:43'),
(203, 156, 193, 'easy', 'translation', '2024-12-15 15:12:43'),
(204, 157, 194, 'easy', 'translation', '2024-12-15 15:12:43'),
(205, 158, 195, 'easy', 'translation', '2024-12-15 15:12:43'),
(206, 159, 196, 'easy', 'translation', '2024-12-15 15:12:43'),
(207, 160, 197, 'easy', 'translation', '2024-12-15 15:12:43'),
(208, 161, 198, 'easy', 'translation', '2024-12-15 15:12:43'),
(209, 162, 199, 'easy', 'translation', '2024-12-15 15:12:43'),
(210, 163, 200, 'easy', 'translation', '2024-12-15 15:12:43'),
(211, 164, 201, 'easy', 'translation', '2024-12-15 15:12:43'),
(212, 165, 202, 'easy', 'translation', '2024-12-15 15:12:43'),
(213, 166, 203, 'easy', 'translation', '2024-12-15 15:12:43'),
(214, 167, 204, 'easy', 'translation', '2024-12-15 15:12:43'),
(215, 168, 205, 'easy', 'translation', '2024-12-15 15:12:43'),
(216, 169, 206, 'easy', 'translation', '2024-12-15 15:12:43'),
(217, 170, 207, 'easy', 'translation', '2024-12-15 15:12:43'),
(218, 171, 208, 'easy', 'translation', '2024-12-15 15:12:43'),
(219, 172, 209, 'easy', 'translation', '2024-12-15 15:12:43'),
(220, 173, 210, 'easy', 'translation', '2024-12-15 15:12:43'),
(221, 174, 211, 'easy', 'translation', '2024-12-15 15:12:43'),
(222, 175, 212, 'easy', 'translation', '2024-12-15 15:12:43'),
(223, 176, 213, 'easy', 'translation', '2024-12-15 15:12:43'),
(224, 177, 214, 'easy', 'translation', '2024-12-15 15:12:43'),
(225, 178, 215, 'easy', 'translation', '2024-12-15 15:12:43'),
(226, 179, 216, 'easy', 'translation', '2024-12-15 15:12:43'),
(227, 180, 217, 'easy', 'translation', '2024-12-15 15:12:43'),
(228, 181, 218, 'easy', 'translation', '2024-12-15 15:12:43'),
(229, 182, 219, 'easy', 'translation', '2024-12-15 15:12:43'),
(230, 183, 220, 'easy', 'translation', '2024-12-15 15:12:43'),
(231, 184, 221, 'easy', 'translation', '2024-12-15 15:12:43'),
(232, 185, 222, 'easy', 'translation', '2024-12-15 15:12:43'),
(233, 186, 223, 'easy', 'translation', '2024-12-15 15:12:43'),
(234, 187, 224, 'easy', 'translation', '2024-12-15 15:12:43'),
(235, 188, 225, 'easy', 'translation', '2024-12-15 15:12:43'),
(236, 189, 226, 'easy', 'translation', '2024-12-15 15:12:43'),
(237, 190, 227, 'easy', 'translation', '2024-12-15 15:12:43'),
(238, 191, 228, 'easy', 'translation', '2024-12-15 15:12:43'),
(239, 192, 229, 'easy', 'translation', '2024-12-15 15:12:43');

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

--
-- Dumping data for table `exercise_word_bank`
--

INSERT INTO `exercise_word_bank` (`exerciseId`, `bankWordId`, `is_answer`, `position`) VALUES
(57, 296, 1, 1),
(57, 297, 0, 2),
(57, 298, 0, 2),
(57, 299, 0, 2),
(57, 300, 0, 2),
(58, 296, 0, 4),
(58, 297, 1, 3),
(58, 298, 0, 4),
(58, 299, 0, 4),
(58, 300, 0, 4),
(59, 296, 0, 3),
(59, 297, 0, 3),
(59, 298, 1, 2),
(59, 299, 0, 3),
(59, 300, 0, 3),
(60, 296, 0, 4),
(60, 297, 0, 4),
(60, 298, 0, 4),
(60, 299, 1, 3),
(60, 300, 0, 4),
(61, 296, 0, 4),
(61, 297, 0, 4),
(61, 298, 0, 4),
(61, 299, 0, 4),
(61, 300, 1, 3),
(62, 301, 1, 4),
(62, 302, 0, 1),
(62, 303, 0, 1),
(62, 304, 0, 1),
(62, 305, 0, 1),
(63, 301, 0, 4),
(63, 302, 1, 3),
(63, 303, 0, 4),
(63, 304, 0, 4),
(63, 305, 0, 4),
(64, 301, 0, 4),
(64, 302, 0, 4),
(64, 303, 1, 3),
(64, 304, 0, 4),
(64, 305, 0, 4),
(65, 301, 0, 2),
(65, 302, 0, 2),
(65, 303, 0, 2),
(65, 304, 1, 1),
(65, 305, 0, 2),
(66, 301, 0, 3),
(66, 302, 0, 3),
(66, 303, 0, 3),
(66, 304, 0, 3),
(66, 305, 1, 2),
(67, 306, 1, 1),
(67, 307, 0, 2),
(67, 308, 0, 2),
(67, 309, 0, 2),
(67, 310, 0, 2),
(68, 306, 0, 3),
(68, 307, 1, 2),
(68, 308, 0, 3),
(68, 309, 0, 3),
(68, 310, 0, 3),
(69, 306, 0, 3),
(69, 307, 0, 3),
(69, 308, 1, 2),
(69, 309, 0, 3),
(69, 310, 0, 3),
(70, 306, 0, 4),
(70, 307, 0, 4),
(70, 308, 0, 4),
(70, 309, 1, 3),
(70, 310, 0, 4),
(71, 306, 0, 2),
(71, 307, 0, 2),
(71, 308, 0, 2),
(71, 309, 0, 2),
(71, 310, 1, 1),
(72, 311, 1, 4),
(72, 312, 0, 1),
(72, 313, 0, 1),
(72, 314, 0, 1),
(72, 315, 0, 1),
(73, 311, 0, 4),
(73, 312, 1, 3),
(73, 313, 0, 4),
(73, 314, 0, 4),
(73, 315, 0, 4),
(74, 311, 0, 1),
(74, 312, 0, 1),
(74, 313, 1, 4),
(74, 314, 0, 1),
(74, 315, 0, 1),
(75, 311, 0, 4),
(75, 312, 0, 4),
(75, 313, 0, 4),
(75, 314, 1, 3),
(75, 315, 0, 4),
(76, 311, 0, 4),
(76, 312, 0, 4),
(76, 313, 0, 4),
(76, 314, 0, 4),
(76, 315, 1, 3),
(88, 296, 1, 3),
(88, 297, 0, 4),
(88, 298, 0, 4),
(88, 299, 0, 4),
(88, 300, 0, 4),
(89, 296, 0, 2),
(89, 297, 1, 1),
(89, 298, 0, 2),
(89, 299, 0, 2),
(89, 300, 0, 2),
(90, 296, 0, 2),
(90, 297, 0, 2),
(90, 298, 1, 1),
(90, 299, 0, 2),
(90, 300, 0, 2),
(91, 296, 0, 1),
(91, 297, 0, 1),
(91, 298, 0, 1),
(91, 299, 1, 4),
(91, 300, 0, 1),
(92, 296, 0, 2),
(92, 297, 0, 2),
(92, 298, 0, 2),
(92, 299, 0, 2),
(92, 300, 1, 1),
(93, 301, 1, 1),
(93, 302, 0, 2),
(93, 303, 0, 2),
(93, 304, 0, 2),
(93, 305, 0, 2),
(94, 301, 0, 2),
(94, 302, 1, 1),
(94, 303, 0, 2),
(94, 304, 0, 2),
(94, 305, 0, 2),
(95, 301, 0, 4),
(95, 302, 0, 4),
(95, 303, 1, 3),
(95, 304, 0, 4),
(95, 305, 0, 4),
(96, 301, 0, 2),
(96, 302, 0, 2),
(96, 303, 0, 2),
(96, 304, 1, 1),
(96, 305, 0, 2),
(97, 301, 0, 3),
(97, 302, 0, 3),
(97, 303, 0, 3),
(97, 304, 0, 3),
(97, 305, 1, 2),
(98, 306, 1, 3),
(98, 307, 0, 4),
(98, 308, 0, 4),
(98, 309, 0, 4),
(98, 310, 0, 4),
(99, 306, 0, 4),
(99, 307, 1, 3),
(99, 308, 0, 4),
(99, 309, 0, 4),
(99, 310, 0, 4),
(100, 306, 0, 1),
(100, 307, 0, 1),
(100, 308, 1, 4),
(100, 309, 0, 1),
(100, 310, 0, 1),
(101, 306, 0, 3),
(101, 307, 0, 3),
(101, 308, 0, 3),
(101, 309, 1, 2),
(101, 310, 0, 3),
(102, 306, 0, 4),
(102, 307, 0, 4),
(102, 308, 0, 4),
(102, 309, 0, 4),
(102, 310, 1, 3),
(103, 311, 1, 3),
(103, 312, 0, 4),
(103, 313, 0, 4),
(103, 314, 0, 4),
(103, 315, 0, 4),
(104, 311, 0, 2),
(104, 312, 1, 1),
(104, 313, 0, 2),
(104, 314, 0, 2),
(104, 315, 0, 2),
(105, 311, 0, 1),
(105, 312, 0, 1),
(105, 313, 1, 4),
(105, 314, 0, 1),
(105, 315, 0, 1),
(106, 311, 0, 2),
(106, 312, 0, 2),
(106, 313, 0, 2),
(106, 314, 1, 1),
(106, 315, 0, 2),
(107, 311, 0, 4),
(107, 312, 0, 4),
(107, 313, 0, 4),
(107, 314, 0, 4),
(107, 315, 1, 3),
(119, 296, 1, 3),
(119, 297, 0, 4),
(119, 298, 0, 4),
(119, 299, 0, 4),
(119, 300, 0, 4),
(120, 296, 0, 2),
(120, 297, 1, 1),
(120, 298, 0, 2),
(120, 299, 0, 2),
(120, 300, 0, 2),
(121, 296, 0, 3),
(121, 297, 0, 3),
(121, 298, 1, 2),
(121, 299, 0, 3),
(121, 300, 0, 3),
(122, 296, 0, 2),
(122, 297, 0, 2),
(122, 298, 0, 2),
(122, 299, 1, 1),
(122, 300, 0, 2),
(123, 296, 0, 4),
(123, 297, 0, 4),
(123, 298, 0, 4),
(123, 299, 0, 4),
(123, 300, 1, 3),
(124, 301, 1, 4),
(124, 302, 0, 1),
(124, 303, 0, 1),
(124, 304, 0, 1),
(124, 305, 0, 1),
(125, 301, 0, 2),
(125, 302, 1, 1),
(125, 303, 0, 2),
(125, 304, 0, 2),
(125, 305, 0, 2),
(126, 301, 0, 1),
(126, 302, 0, 1),
(126, 303, 1, 4),
(126, 304, 0, 1),
(126, 305, 0, 1),
(127, 301, 0, 1),
(127, 302, 0, 1),
(127, 303, 0, 1),
(127, 304, 1, 4),
(127, 305, 0, 1),
(128, 301, 0, 4),
(128, 302, 0, 4),
(128, 303, 0, 4),
(128, 304, 0, 4),
(128, 305, 1, 3),
(129, 306, 1, 1),
(129, 307, 0, 2),
(129, 308, 0, 2),
(129, 309, 0, 2),
(129, 310, 0, 2),
(130, 306, 0, 3),
(130, 307, 1, 2),
(130, 308, 0, 3),
(130, 309, 0, 3),
(130, 310, 0, 3),
(131, 306, 0, 2),
(131, 307, 0, 2),
(131, 308, 1, 1),
(131, 309, 0, 2),
(131, 310, 0, 2),
(132, 306, 0, 3),
(132, 307, 0, 3),
(132, 308, 0, 3),
(132, 309, 1, 2),
(132, 310, 0, 3),
(133, 306, 0, 3),
(133, 307, 0, 3),
(133, 308, 0, 3),
(133, 309, 0, 3),
(133, 310, 1, 2),
(134, 311, 1, 1),
(134, 312, 0, 2),
(134, 313, 0, 2),
(134, 314, 0, 2),
(134, 315, 0, 2),
(135, 311, 0, 2),
(135, 312, 1, 1),
(135, 313, 0, 2),
(135, 314, 0, 2),
(135, 315, 0, 2),
(136, 311, 0, 4),
(136, 312, 0, 4),
(136, 313, 1, 3),
(136, 314, 0, 4),
(136, 315, 0, 4),
(137, 311, 0, 1),
(137, 312, 0, 1),
(137, 313, 0, 1),
(137, 314, 1, 4),
(137, 315, 0, 1),
(138, 311, 0, 4),
(138, 312, 0, 4),
(138, 313, 0, 4),
(138, 314, 0, 4),
(138, 315, 1, 3),
(150, 417, 1, 3),
(150, 418, 0, 4),
(150, 419, 0, 4),
(150, 420, 0, 4),
(151, 418, 1, 2),
(152, 419, 1, 1),
(153, 420, 1, 3),
(154, 341, 1, 1),
(154, 421, 1, 4),
(155, 417, 1, 2),
(156, 418, 1, 2),
(157, 419, 1, 2),
(158, 420, 1, 2),
(159, 341, 1, 3),
(159, 421, 1, 3),
(160, 417, 1, 2),
(161, 418, 1, 4),
(162, 419, 1, 1),
(163, 420, 1, 2),
(164, 341, 1, 3),
(164, 421, 1, 3),
(165, 422, 1, 1),
(166, 423, 1, 2),
(167, 424, 1, 2),
(168, 425, 1, 4),
(169, 426, 1, 3),
(170, 422, 1, 1),
(171, 423, 1, 1),
(172, 427, 1, 2),
(173, 425, 1, 2),
(174, 426, 1, 1),
(175, 422, 1, 3),
(176, 423, 1, 1),
(177, 424, 1, 4),
(178, 425, 1, 4),
(179, 426, 1, 2),
(180, 428, 1, 4),
(181, 429, 1, 4),
(182, 430, 1, 1),
(183, 431, 1, 2),
(184, 371, 1, 2),
(184, 432, 1, 2),
(185, 428, 1, 2),
(186, 429, 1, 2),
(187, 430, 1, 3),
(188, 431, 1, 4),
(189, 371, 1, 1),
(189, 432, 1, 3),
(190, 428, 1, 4),
(191, 429, 1, 1),
(192, 430, 1, 4),
(193, 431, 1, 2),
(194, 371, 1, 4),
(194, 432, 1, 3),
(195, 433, 1, 4),
(196, 373, 1, 4),
(196, 383, 1, 3),
(197, 434, 1, 3),
(198, 435, 1, 3),
(199, 436, 1, 3),
(200, 433, 1, 4),
(201, 373, 1, 4),
(201, 383, 1, 3),
(202, 434, 1, 4),
(203, 435, 1, 1),
(204, 436, 1, 2),
(205, 433, 1, 4),
(206, 373, 1, 1),
(206, 383, 1, 4),
(207, 434, 1, 3),
(208, 435, 1, 3),
(209, 436, 1, 3),
(210, 437, 1, 1),
(211, 438, 1, 4),
(212, 439, 1, 2),
(213, 440, 1, 2),
(214, 441, 1, 3),
(215, 437, 1, 2),
(216, 438, 1, 2),
(217, 439, 1, 2),
(218, 440, 1, 1),
(219, 441, 1, 4),
(220, 437, 1, 3),
(221, 438, 1, 2),
(222, 439, 1, 2),
(223, 440, 1, 2),
(224, 441, 1, 2),
(225, 442, 1, 2),
(226, 413, 1, 2),
(226, 443, 1, 2),
(227, 444, 1, 4),
(228, 445, 1, 3),
(229, 446, 1, 1),
(230, 442, 1, 1),
(231, 413, 1, 2),
(231, 443, 1, 2),
(232, 444, 1, 4),
(233, 445, 1, 2),
(234, 446, 1, 4),
(235, 442, 1, 3),
(236, 413, 1, 4),
(236, 443, 1, 2),
(237, 444, 1, 1),
(238, 445, 1, 2),
(239, 446, 1, 3);

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
(3, 'German', 'Beginner', 'Learn German from basics to the intermediate level', 1, 'en'),
(4, 'Italian', 'Beginner', 'Learn Italian from basics to the intermediate level', 0, 'en');

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
(128, 102, 'black', 1, '2024-12-15 12:11:21'),
(140, 103, 'mother', 1, '2024-12-15 15:12:43'),
(141, 104, 'father', 1, '2024-12-15 15:12:43'),
(142, 105, 'sister', 1, '2024-12-15 15:12:43'),
(143, 106, 'brother', 1, '2024-12-15 15:12:43'),
(144, 107, 'baby', 1, '2024-12-15 15:12:43'),
(145, 108, 'mother', 1, '2024-12-15 15:12:43'),
(146, 109, 'father', 1, '2024-12-15 15:12:43'),
(147, 110, 'sister', 1, '2024-12-15 15:12:43'),
(148, 111, 'brother', 1, '2024-12-15 15:12:43'),
(149, 112, 'baby', 1, '2024-12-15 15:12:43'),
(150, 113, 'mother', 1, '2024-12-15 15:12:43'),
(151, 114, 'father', 1, '2024-12-15 15:12:43'),
(152, 115, 'sister', 1, '2024-12-15 15:12:43'),
(153, 116, 'brother', 1, '2024-12-15 15:12:43'),
(154, 117, 'baby', 1, '2024-12-15 15:12:43'),
(155, 118, 'how are you?', 1, '2024-12-15 15:12:43'),
(156, 119, 'good night', 1, '2024-12-15 15:12:43'),
(157, 120, 'see you soon', 1, '2024-12-15 15:12:43'),
(158, 121, 'enjoy your meal', 1, '2024-12-15 15:12:43'),
(159, 122, 'I don\'t know', 1, '2024-12-15 15:12:43'),
(160, 123, 'how are you?', 1, '2024-12-15 15:12:43'),
(161, 124, 'good night', 1, '2024-12-15 15:12:43'),
(162, 125, 'see you later', 1, '2024-12-15 15:12:43'),
(163, 126, 'enjoy your meal', 1, '2024-12-15 15:12:43'),
(164, 127, 'I don\'t know', 1, '2024-12-15 15:12:43'),
(165, 128, 'how are you?', 1, '2024-12-15 15:12:43'),
(166, 129, 'good night', 1, '2024-12-15 15:12:43'),
(167, 130, 'see you soon', 1, '2024-12-15 15:12:43'),
(168, 131, 'enjoy your meal', 1, '2024-12-15 15:12:43'),
(169, 132, 'I don\'t know', 1, '2024-12-15 15:12:43'),
(170, 133, 'sun', 1, '2024-12-15 15:12:43'),
(171, 134, 'rain', 1, '2024-12-15 15:12:43'),
(172, 135, 'snow', 1, '2024-12-15 15:12:43'),
(173, 136, 'cloud', 1, '2024-12-15 15:12:43'),
(174, 137, 'wind', 1, '2024-12-15 15:12:43'),
(175, 138, 'sun', 1, '2024-12-15 15:12:43'),
(176, 139, 'rain', 1, '2024-12-15 15:12:43'),
(177, 140, 'snow', 1, '2024-12-15 15:12:43'),
(178, 141, 'cloud', 1, '2024-12-15 15:12:43'),
(179, 142, 'wind', 1, '2024-12-15 15:12:43'),
(180, 143, 'sun', 1, '2024-12-15 15:12:43'),
(181, 144, 'rain', 1, '2024-12-15 15:12:43'),
(182, 145, 'snow', 1, '2024-12-15 15:12:43'),
(183, 146, 'cloud', 1, '2024-12-15 15:12:43'),
(184, 147, 'wind', 1, '2024-12-15 15:12:43'),
(185, 148, 'hour', 1, '2024-12-15 15:12:43'),
(186, 149, 'minute', 1, '2024-12-15 15:12:43'),
(187, 150, 'morning', 1, '2024-12-15 15:12:43'),
(188, 151, 'evening', 1, '2024-12-15 15:12:43'),
(189, 152, 'night', 1, '2024-12-15 15:12:43'),
(190, 153, 'hour', 1, '2024-12-15 15:12:43'),
(191, 154, 'minute', 1, '2024-12-15 15:12:43'),
(192, 155, 'morning', 1, '2024-12-15 15:12:43'),
(193, 156, 'evening', 1, '2024-12-15 15:12:43'),
(194, 157, 'night', 1, '2024-12-15 15:12:43'),
(195, 158, 'hour', 1, '2024-12-15 15:12:43'),
(196, 159, 'minute', 1, '2024-12-15 15:12:43'),
(197, 160, 'morning', 1, '2024-12-15 15:12:43'),
(198, 161, 'evening', 1, '2024-12-15 15:12:43'),
(199, 162, 'night', 1, '2024-12-15 15:12:43'),
(200, 163, 'cat', 1, '2024-12-15 15:12:43'),
(201, 164, 'dog', 1, '2024-12-15 15:12:43'),
(202, 165, 'bird', 1, '2024-12-15 15:12:43'),
(203, 166, 'fish', 1, '2024-12-15 15:12:43'),
(204, 167, 'rabbit', 1, '2024-12-15 15:12:43'),
(205, 168, 'cat', 1, '2024-12-15 15:12:43'),
(206, 169, 'dog', 1, '2024-12-15 15:12:43'),
(207, 170, 'bird', 1, '2024-12-15 15:12:43'),
(208, 171, 'fish', 1, '2024-12-15 15:12:43'),
(209, 172, 'rabbit', 1, '2024-12-15 15:12:43'),
(210, 173, 'cat', 1, '2024-12-15 15:12:43'),
(211, 174, 'dog', 1, '2024-12-15 15:12:43'),
(212, 175, 'bird', 1, '2024-12-15 15:12:43'),
(213, 176, 'fish', 1, '2024-12-15 15:12:43'),
(214, 177, 'rabbit', 1, '2024-12-15 15:12:43'),
(215, 178, 'head', 1, '2024-12-15 15:12:43'),
(216, 179, 'hand', 1, '2024-12-15 15:12:43'),
(217, 180, 'foot', 1, '2024-12-15 15:12:43'),
(218, 181, 'nose', 1, '2024-12-15 15:12:43'),
(219, 182, 'mouth', 1, '2024-12-15 15:12:43'),
(220, 183, 'head', 1, '2024-12-15 15:12:43'),
(221, 184, 'hand', 1, '2024-12-15 15:12:43'),
(222, 185, 'foot', 1, '2024-12-15 15:12:43'),
(223, 186, 'nose', 1, '2024-12-15 15:12:43'),
(224, 187, 'mouth', 1, '2024-12-15 15:12:43'),
(225, 188, 'head', 1, '2024-12-15 15:12:43'),
(226, 189, 'hand', 1, '2024-12-15 15:12:43'),
(227, 190, 'foot', 1, '2024-12-15 15:12:43'),
(228, 191, 'nose', 1, '2024-12-15 15:12:43'),
(229, 192, 'mouth', 1, '2024-12-15 15:12:43');

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
(102, 3, 'Schwarz', 4, 'schwarz', 'black', 'shvarts', 'colors', 'easy', '2024-12-15 12:06:37', 'adjective'),
(103, 1, 'mère', 5, 'mère', 'mother', 'mehr', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(104, 1, 'père', 5, 'père', 'father', 'pehr', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(105, 1, 'soeur', 5, 'soeur', 'sister', 'suhr', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(106, 1, 'frère', 5, 'frère', 'brother', 'frehr', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(107, 1, 'bébé', 5, 'bébé', 'baby', 'bay-bay', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(108, 2, 'madre', 5, 'madre', 'mother', 'MAH-dreh', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(109, 2, 'padre', 5, 'padre', 'father', 'PAH-dreh', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(110, 2, 'hermana', 5, 'hermana', 'sister', 'er-MAH-nah', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(111, 2, 'hermano', 5, 'hermano', 'brother', 'er-MAH-noh', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(112, 2, 'bebé', 5, 'bebé', 'baby', 'beh-BEH', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(113, 3, 'Mutter', 5, 'mutter', 'mother', 'MOOT-ter', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(114, 3, 'Vater', 5, 'vater', 'father', 'FAH-ter', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(115, 3, 'Schwester', 5, 'schwester', 'sister', 'SHVES-ter', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(116, 3, 'Bruder', 5, 'bruder', 'brother', 'BROO-der', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(117, 3, 'Baby', 5, 'baby', 'baby', 'BA-bee', 'family', 'easy', '2024-12-15 15:09:59', 'noun'),
(118, 1, 'Comment allez-vous?', 6, 'comment allez-vous', 'how are you?', 'koh-mahn tah-lay voo', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(119, 1, 'Bonne nuit', 6, 'bonne nuit', 'good night', 'bun nwee', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(120, 1, 'À bientôt', 6, 'à bientôt', 'see you soon', 'ah bee-ahn-toh', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(121, 1, 'Bon appétit', 6, 'bon appétit', 'enjoy your meal', 'bohn ah-pay-tee', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(122, 1, 'Je ne sais pas', 6, 'je ne sais pas', 'I don\'t know', 'zhuh nuh say pah', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(123, 2, '¿Cómo estás?', 6, 'cómo estás', 'how are you?', 'KOH-moh es-TAHS', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(124, 2, 'Buenas noches', 6, 'buenas noches', 'good night', 'BWEH-nahs NOH-chehs', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(125, 2, 'Hasta luego', 6, 'hasta luego', 'see you later', 'AHS-tah LWEH-goh', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(126, 2, '¡Buen provecho!', 6, 'buen provecho', 'enjoy your meal', 'bwen proh-VEH-choh', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(127, 2, 'No sé', 6, 'no sé', 'I don\'t know', 'noh SEH', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(128, 3, 'Wie geht es dir?', 6, 'wie geht es dir', 'how are you?', 'vee gayt es deer', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(129, 3, 'Gute Nacht', 6, 'gute nacht', 'good night', 'GOO-tuh nakht', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(130, 3, 'Bis bald', 6, 'bis bald', 'see you soon', 'bis balt', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(131, 3, 'Guten Appetit', 6, 'guten appetit', 'enjoy your meal', 'GOO-ten ah-peh-teet', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(132, 3, 'Ich weiß nicht', 6, 'ich weiß nicht', 'I don\'t know', 'ikh vays nikht', 'phrases', 'easy', '2024-12-15 15:09:59', 'phrase'),
(133, 1, 'soleil', 7, 'soleil', 'sun', 'soh-lay', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(134, 1, 'pluie', 7, 'pluie', 'rain', 'plwee', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(135, 1, 'neige', 7, 'neige', 'snow', 'nezh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(136, 1, 'nuage', 7, 'nuage', 'cloud', 'noo-ahzh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(137, 1, 'vent', 7, 'vent', 'wind', 'vahn', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(138, 2, 'sol', 7, 'sol', 'sun', 'sohl', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(139, 2, 'lluvia', 7, 'lluvia', 'rain', 'YOO-vee-ah', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(140, 2, 'nieve', 7, 'nieve', 'snow', 'NYEH-veh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(141, 2, 'nube', 7, 'nube', 'cloud', 'NOO-beh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(142, 2, 'viento', 7, 'viento', 'wind', 'vee-EN-toh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(143, 3, 'Sonne', 7, 'sonne', 'sun', 'ZON-nuh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(144, 3, 'Regen', 7, 'regen', 'rain', 'REH-gen', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(145, 3, 'Schnee', 7, 'schnee', 'snow', 'shnay', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(146, 3, 'Wolke', 7, 'wolke', 'cloud', 'VOL-kuh', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(147, 3, 'Wind', 7, 'wind', 'wind', 'vint', 'weather', 'easy', '2024-12-15 15:09:59', 'noun'),
(148, 1, 'heure', 8, 'heure', 'hour', 'uhr', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(149, 1, 'minute', 8, 'minute', 'minute', 'mee-noot', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(150, 1, 'matin', 8, 'matin', 'morning', 'mah-tan', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(151, 1, 'soir', 8, 'soir', 'evening', 'swahr', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(152, 1, 'nuit', 8, 'nuit', 'night', 'nwee', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(153, 2, 'hora', 8, 'hora', 'hour', 'OH-rah', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(154, 2, 'minuto', 8, 'minuto', 'minute', 'mee-NOO-toh', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(155, 2, 'mañana', 8, 'mañana', 'morning', 'mah-NYAH-nah', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(156, 2, 'tarde', 8, 'tarde', 'evening', 'TAR-deh', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(157, 2, 'noche', 8, 'noche', 'night', 'NOH-cheh', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(158, 3, 'Stunde', 8, 'stunde', 'hour', 'SHTUN-duh', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(159, 3, 'Minute', 8, 'minute', 'minute', 'mi-NOO-tuh', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(160, 3, 'Morgen', 8, 'morgen', 'morning', 'MOR-gen', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(161, 3, 'Abend', 8, 'abend', 'evening', 'AH-bent', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(162, 3, 'Nacht', 8, 'nacht', 'night', 'nakht', 'time', 'easy', '2024-12-15 15:09:59', 'noun'),
(163, 1, 'chat', 9, 'chat', 'cat', 'shah', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(164, 1, 'chien', 9, 'chien', 'dog', 'shyan', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(165, 1, 'oiseau', 9, 'oiseau', 'bird', 'wah-zoh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(166, 1, 'poisson', 9, 'poisson', 'fish', 'pwah-sohn', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(167, 1, 'lapin', 9, 'lapin', 'rabbit', 'lah-pan', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(168, 2, 'gato', 9, 'gato', 'cat', 'GAH-toh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(169, 2, 'perro', 9, 'perro', 'dog', 'PEH-roh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(170, 2, 'pájaro', 9, 'pájaro', 'bird', 'PAH-ha-roh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(171, 2, 'pez', 9, 'pez', 'fish', 'pehs', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(172, 2, 'conejo', 9, 'conejo', 'rabbit', 'koh-NEH-hoh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(173, 3, 'Katze', 9, 'katze', 'cat', 'KAT-tsuh', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(174, 3, 'Hund', 9, 'hund', 'dog', 'hoont', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(175, 3, 'Vogel', 9, 'vogel', 'bird', 'FOH-gel', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(176, 3, 'Fisch', 9, 'fisch', 'fish', 'fish', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(177, 3, 'Kaninchen', 9, 'kaninchen', 'rabbit', 'KAH-nin-khen', 'animals', 'easy', '2024-12-15 15:09:59', 'noun'),
(178, 1, 'tête', 10, 'tête', 'head', 'tet', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(179, 1, 'main', 10, 'main', 'hand', 'man', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(180, 1, 'pied', 10, 'pied', 'foot', 'pyay', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(181, 1, 'nez', 10, 'nez', 'nose', 'nay', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(182, 1, 'bouche', 10, 'bouche', 'mouth', 'boosh', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(183, 2, 'cabeza', 10, 'cabeza', 'head', 'kah-BEH-sah', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(184, 2, 'mano', 10, 'mano', 'hand', 'MAH-noh', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(185, 2, 'pie', 10, 'pie', 'foot', 'pee-EH', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(186, 2, 'nariz', 10, 'nariz', 'nose', 'nah-REES', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(187, 2, 'boca', 10, 'boca', 'mouth', 'BOH-kah', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(188, 3, 'Kopf', 10, 'kopf', 'head', 'kopf', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(189, 3, 'Hand', 10, 'hand', 'hand', 'hant', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(190, 3, 'Fuß', 10, 'fuß', 'foot', 'foos', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(191, 3, 'Nase', 10, 'nase', 'nose', 'NAH-zuh', 'body', 'easy', '2024-12-15 15:09:59', 'noun'),
(192, 3, 'Mund', 10, 'mund', 'mouth', 'moont', 'body', 'easy', '2024-12-15 15:09:59', 'noun');

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
(100, 1, 47, 1, '2024-12-15 13:39:46'),
(101, 1, 43, 0, '2024-12-15 14:38:24'),
(102, 1, 43, 1, '2024-12-15 14:38:31'),
(103, 1, 43, 1, '2024-12-15 14:43:18'),
(104, 1, 44, 1, '2024-12-15 14:43:24'),
(105, 1, 44, 1, '2024-12-15 14:43:27'),
(106, 1, 44, 1, '2024-12-15 14:43:31'),
(107, 1, 44, 1, '2024-12-15 14:43:35'),
(108, 1, 46, 1, '2024-12-15 14:43:38'),
(109, 1, 47, 1, '2024-12-15 14:44:23'),
(110, 1, 46, 1, '2024-12-15 14:44:26'),
(111, 1, 45, 1, '2024-12-15 14:44:29'),
(112, 1, 47, 1, '2024-12-15 14:44:33'),
(113, 1, 46, 1, '2024-12-15 14:44:36'),
(114, 1, 46, 1, '2024-12-15 14:44:40'),
(115, 1, 47, 1, '2024-12-15 14:55:00'),
(116, 1, 47, 1, '2024-12-15 14:55:05'),
(117, 1, 46, 1, '2024-12-15 14:55:10'),
(118, 1, 48, 1, '2024-12-15 14:55:25'),
(119, 1, 52, 0, '2024-12-15 14:55:30'),
(120, 1, 58, 1, '2024-12-15 15:02:35'),
(121, 1, 62, 1, '2024-12-15 15:02:39'),
(122, 1, 149, 0, '2024-12-15 15:20:17'),
(123, 1, 149, 0, '2024-12-15 15:20:20'),
(124, 1, 149, 0, '2024-12-15 15:20:24');

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
(292, 'schwarz', 3, 'adjective', 'easy', 0),
(296, 'hello', 1, 'other', 'easy', 0),
(297, 'goodbye', 1, 'other', 'easy', 0),
(298, 'thank you', 1, 'other', 'easy', 0),
(299, 'please', 1, 'other', 'easy', 0),
(300, 'you\'re welcome', 1, 'other', 'easy', 0),
(301, 'one', 1, 'other', 'easy', 0),
(302, 'two', 1, 'other', 'easy', 0),
(303, 'three', 1, 'other', 'easy', 0),
(304, 'four', 1, 'other', 'easy', 0),
(305, 'five', 1, 'other', 'easy', 0),
(306, 'bread', 1, 'noun', 'easy', 0),
(307, 'coffee', 1, 'noun', 'easy', 0),
(308, 'milk', 1, 'noun', 'easy', 0),
(309, 'water', 1, 'noun', 'easy', 0),
(310, 'cheese', 1, 'noun', 'easy', 0),
(311, 'red', 1, 'adjective', 'easy', 0),
(312, 'blue', 1, 'adjective', 'easy', 0),
(313, 'green', 1, 'adjective', 'easy', 0),
(314, 'yellow', 1, 'adjective', 'easy', 0),
(315, 'black', 1, 'adjective', 'easy', 0),
(327, 'mère', 1, 'noun', 'easy', 0),
(328, 'père', 1, 'noun', 'easy', 0),
(329, 'soeur', 1, 'noun', 'easy', 0),
(330, 'frère', 1, 'noun', 'easy', 0),
(331, 'bébé', 1, 'noun', 'easy', 0),
(332, 'madre', 2, 'noun', 'easy', 0),
(333, 'padre', 2, 'noun', 'easy', 0),
(334, 'hermana', 2, 'noun', 'easy', 0),
(335, 'hermano', 2, 'noun', 'easy', 0),
(336, 'bebé', 2, 'noun', 'easy', 0),
(337, 'mutter', 3, 'noun', 'easy', 0),
(338, 'vater', 3, 'noun', 'easy', 0),
(339, 'schwester', 3, 'noun', 'easy', 0),
(340, 'bruder', 3, 'noun', 'easy', 0),
(341, 'baby', 3, 'noun', 'easy', 0),
(342, 'comment allez-vous', 1, 'phrase', 'easy', 0),
(343, 'bonne nuit', 1, 'phrase', 'easy', 0),
(344, 'à bientôt', 1, 'phrase', 'easy', 0),
(345, 'bon appétit', 1, 'phrase', 'easy', 0),
(346, 'je ne sais pas', 1, 'phrase', 'easy', 0),
(347, 'cómo estás', 2, 'phrase', 'easy', 0),
(348, 'buenas noches', 2, 'phrase', 'easy', 0),
(349, 'hasta luego', 2, 'phrase', 'easy', 0),
(350, 'buen provecho', 2, 'phrase', 'easy', 0),
(351, 'no sé', 2, 'phrase', 'easy', 0),
(352, 'wie geht es dir', 3, 'phrase', 'easy', 0),
(353, 'gute nacht', 3, 'phrase', 'easy', 0),
(354, 'bis bald', 3, 'phrase', 'easy', 0),
(355, 'guten appetit', 3, 'phrase', 'easy', 0),
(356, 'ich weiß nicht', 3, 'phrase', 'easy', 0),
(357, 'soleil', 1, 'noun', 'easy', 0),
(358, 'pluie', 1, 'noun', 'easy', 0),
(359, 'neige', 1, 'noun', 'easy', 0),
(360, 'nuage', 1, 'noun', 'easy', 0),
(361, 'vent', 1, 'noun', 'easy', 0),
(362, 'sol', 2, 'noun', 'easy', 0),
(363, 'lluvia', 2, 'noun', 'easy', 0),
(364, 'nieve', 2, 'noun', 'easy', 0),
(365, 'nube', 2, 'noun', 'easy', 0),
(366, 'viento', 2, 'noun', 'easy', 0),
(367, 'sonne', 3, 'noun', 'easy', 0),
(368, 'regen', 3, 'noun', 'easy', 0),
(369, 'schnee', 3, 'noun', 'easy', 0),
(370, 'wolke', 3, 'noun', 'easy', 0),
(371, 'wind', 3, 'noun', 'easy', 0),
(372, 'heure', 1, 'noun', 'easy', 0),
(373, 'minute', 1, 'noun', 'easy', 0),
(374, 'matin', 1, 'noun', 'easy', 0),
(375, 'soir', 1, 'noun', 'easy', 0),
(376, 'nuit', 1, 'noun', 'easy', 0),
(377, 'hora', 2, 'noun', 'easy', 0),
(378, 'minuto', 2, 'noun', 'easy', 0),
(379, 'mañana', 2, 'noun', 'easy', 0),
(380, 'tarde', 2, 'noun', 'easy', 0),
(381, 'noche', 2, 'noun', 'easy', 0),
(382, 'stunde', 3, 'noun', 'easy', 0),
(383, 'minute', 3, 'noun', 'easy', 0),
(384, 'morgen', 3, 'noun', 'easy', 0),
(385, 'abend', 3, 'noun', 'easy', 0),
(386, 'nacht', 3, 'noun', 'easy', 0),
(387, 'chat', 1, 'noun', 'easy', 0),
(388, 'chien', 1, 'noun', 'easy', 0),
(389, 'oiseau', 1, 'noun', 'easy', 0),
(390, 'poisson', 1, 'noun', 'easy', 0),
(391, 'lapin', 1, 'noun', 'easy', 0),
(392, 'gato', 2, 'noun', 'easy', 0),
(393, 'perro', 2, 'noun', 'easy', 0),
(394, 'pájaro', 2, 'noun', 'easy', 0),
(395, 'pez', 2, 'noun', 'easy', 0),
(396, 'conejo', 2, 'noun', 'easy', 0),
(397, 'katze', 3, 'noun', 'easy', 0),
(398, 'hund', 3, 'noun', 'easy', 0),
(399, 'vogel', 3, 'noun', 'easy', 0),
(400, 'fisch', 3, 'noun', 'easy', 0),
(401, 'kaninchen', 3, 'noun', 'easy', 0),
(402, 'tête', 1, 'noun', 'easy', 0),
(403, 'main', 1, 'noun', 'easy', 0),
(404, 'pied', 1, 'noun', 'easy', 0),
(405, 'nez', 1, 'noun', 'easy', 0),
(406, 'bouche', 1, 'noun', 'easy', 0),
(407, 'cabeza', 2, 'noun', 'easy', 0),
(408, 'mano', 2, 'noun', 'easy', 0),
(409, 'pie', 2, 'noun', 'easy', 0),
(410, 'nariz', 2, 'noun', 'easy', 0),
(411, 'boca', 2, 'noun', 'easy', 0),
(412, 'kopf', 3, 'noun', 'easy', 0),
(413, 'hand', 3, 'noun', 'easy', 0),
(414, 'fuß', 3, 'noun', 'easy', 0),
(415, 'nase', 3, 'noun', 'easy', 0),
(416, 'mund', 3, 'noun', 'easy', 0),
(417, 'mother', 1, 'noun', 'easy', 0),
(418, 'father', 1, 'noun', 'easy', 0),
(419, 'sister', 1, 'noun', 'easy', 0),
(420, 'brother', 1, 'noun', 'easy', 0),
(421, 'baby', 1, 'noun', 'easy', 0),
(422, 'how are you?', 1, 'phrase', 'easy', 0),
(423, 'good night', 1, 'phrase', 'easy', 0),
(424, 'see you soon', 1, 'phrase', 'easy', 0),
(425, 'enjoy your meal', 1, 'phrase', 'easy', 0),
(426, 'I don\'t know', 1, 'phrase', 'easy', 0),
(427, 'see you later', 1, 'phrase', 'easy', 0),
(428, 'sun', 1, 'noun', 'easy', 0),
(429, 'rain', 1, 'noun', 'easy', 0),
(430, 'snow', 1, 'noun', 'easy', 0),
(431, 'cloud', 1, 'noun', 'easy', 0),
(432, 'wind', 1, 'noun', 'easy', 0),
(433, 'hour', 1, 'noun', 'easy', 0),
(434, 'morning', 1, 'noun', 'easy', 0),
(435, 'evening', 1, 'noun', 'easy', 0),
(436, 'night', 1, 'noun', 'easy', 0),
(437, 'cat', 1, 'noun', 'easy', 0),
(438, 'dog', 1, 'noun', 'easy', 0),
(439, 'bird', 1, 'noun', 'easy', 0),
(440, 'fish', 1, 'noun', 'easy', 0),
(441, 'rabbit', 1, 'noun', 'easy', 0),
(442, 'head', 1, 'noun', 'easy', 0),
(443, 'hand', 1, 'noun', 'easy', 0),
(444, 'foot', 1, 'noun', 'easy', 0),
(445, 'nose', 1, 'noun', 'easy', 0),
(446, 'mouth', 1, 'noun', 'easy', 0);

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
  MODIFY `exerciseId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=277;

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
  MODIFY `translationId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=267;

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
  MODIFY `wordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=193;

--
-- AUTO_INCREMENT for table `word_attempts`
--
ALTER TABLE `word_attempts`
  MODIFY `attemptId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=125;

--
-- AUTO_INCREMENT for table `word_bank`
--
ALTER TABLE `word_bank`
  MODIFY `bankWordId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=454;

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
