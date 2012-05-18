-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 14, 2012 at 05:28 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.9-ZS5.6.0

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `pupolitis`
--

-- --------------------------------------------------------

--
-- Table structure for table `ads`
--

CREATE TABLE IF NOT EXISTS `ads` (
  `adId` int(11) NOT NULL AUTO_INCREMENT,
  `clicks` int(11) NOT NULL,
  `views` int(11) NOT NULL,
  `creationTime` datetime NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY (`adId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ads`
--


-- --------------------------------------------------------

--
-- Table structure for table `apmekletiba`
--

CREATE TABLE IF NOT EXISTS `apmekletiba` (
  `apmekletibaId` int(11) NOT NULL AUTO_INCREMENT,
  `apmekletibaUserId` int(11) NOT NULL,
  `apmekletibaEventId` int(11) NOT NULL,
  `apmekletibaTipsId` int(11) NOT NULL,
  PRIMARY KEY (`apmekletibaId`),
  KEY `apmekletibaEventId` (`apmekletibaEventId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=220 ;

--
-- Dumping data for table `apmekletiba`
--

INSERT INTO `apmekletiba` (`apmekletibaId`, `apmekletibaUserId`, `apmekletibaEventId`, `apmekletibaTipsId`) VALUES
(136, 9, 2, 1),
(137, 10, 2, 1),
(138, 11, 2, 1),
(139, 13, 2, 1),
(140, 14, 2, 1),
(141, 15, 2, 1),
(142, 16, 2, 1),
(143, 17, 2, 1),
(144, 18, 2, 1),
(145, 19, 2, 1),
(146, 20, 2, 1),
(147, 21, 2, 1),
(148, 22, 2, 2),
(149, 23, 2, 1),
(150, 24, 2, 1),
(151, 25, 2, 1),
(152, 26, 2, 1),
(153, 28, 2, 1),
(154, 29, 2, 1),
(155, 30, 2, 1),
(156, 32, 2, 1),
(157, 9, 3, 1),
(158, 10, 3, 1),
(159, 11, 3, 1),
(160, 13, 3, 1),
(161, 14, 3, 1),
(162, 15, 3, 1),
(163, 16, 3, 1),
(164, 17, 3, 1),
(165, 18, 3, 1),
(166, 19, 3, 2),
(167, 20, 3, 2),
(168, 21, 3, 1),
(169, 22, 3, 2),
(170, 23, 3, 1),
(171, 24, 3, 1),
(172, 25, 3, 1),
(173, 26, 3, 3),
(174, 28, 3, 1),
(175, 29, 3, 1),
(176, 30, 3, 1),
(177, 9, 4, 1),
(178, 10, 4, 1),
(179, 11, 4, 1),
(180, 13, 4, 1),
(181, 14, 4, 1),
(182, 15, 4, 1),
(183, 16, 4, 1),
(184, 17, 4, 1),
(185, 18, 4, 1),
(186, 19, 4, 1),
(187, 20, 4, 1),
(188, 21, 4, 1),
(189, 22, 4, 1),
(190, 23, 4, 1),
(191, 24, 4, 3),
(192, 25, 4, 1),
(193, 26, 4, 1),
(194, 28, 4, 1),
(195, 29, 4, 1),
(196, 30, 4, 2),
(197, 9, 6, 1),
(198, 10, 6, 1),
(199, 11, 6, 1),
(200, 13, 6, 1),
(201, 14, 6, 2),
(202, 15, 6, 2),
(203, 16, 6, 1),
(204, 17, 6, 1),
(205, 18, 6, 3),
(206, 19, 6, 2),
(207, 20, 6, 1),
(208, 21, 6, 1),
(209, 22, 6, 1),
(210, 23, 6, 1),
(211, 24, 6, 1),
(212, 25, 6, 1),
(213, 26, 6, 2),
(214, 28, 6, 1),
(215, 29, 6, 1),
(216, 30, 6, 1),
(217, 31, 6, 1),
(218, 32, 6, 1),
(219, 33, 6, 2);

-- --------------------------------------------------------

--
-- Table structure for table `apmekletibatipi`
--

CREATE TABLE IF NOT EXISTS `apmekletibatipi` (
  `apmekletibaTipsId` int(11) NOT NULL AUTO_INCREMENT,
  `apmekletibaTipsTitle` text NOT NULL,
  `apmekletibaTipsColor` text NOT NULL,
  PRIMARY KEY (`apmekletibaTipsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `apmekletibatipi`
--

INSERT INTO `apmekletibatipi` (`apmekletibaTipsId`, `apmekletibaTipsTitle`, `apmekletibaTipsColor`) VALUES
(1, 'Ieradās', 'green'),
(2, 'Neieradās, attaisnojoši', 'yellow'),
(3, 'Neieradās, neattaisnoti', 'red');

-- --------------------------------------------------------

--
-- Table structure for table `apmekletibatipikrutums`
--

CREATE TABLE IF NOT EXISTS `apmekletibatipikrutums` (
  `atipikrutumsId` int(11) NOT NULL AUTO_INCREMENT,
  `apmekletibaTipsId` int(11) NOT NULL,
  `pasakumaTipsId` int(11) NOT NULL,
  `krutumsValue` int(11) NOT NULL,
  PRIMARY KEY (`atipikrutumsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `apmekletibatipikrutums`
--

INSERT INTO `apmekletibatipikrutums` (`atipikrutumsId`, `apmekletibaTipsId`, `pasakumaTipsId`, `krutumsValue`) VALUES
(1, 1, 2, 5),
(2, 2, 2, 0),
(3, 3, 2, -7),
(4, 1, 3, 15),
(5, 2, 3, 0),
(6, 3, 3, -20);

-- --------------------------------------------------------

--
-- Table structure for table `apmekletibatokrutums`
--

CREATE TABLE IF NOT EXISTS `apmekletibatokrutums` (
  `apmekletibaId` int(11) NOT NULL,
  `krutumsId` int(11) NOT NULL,
  `dateModified` datetime NOT NULL,
  PRIMARY KEY (`apmekletibaId`,`krutumsId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `apmekletibatokrutums`
--

INSERT INTO `apmekletibatokrutums` (`apmekletibaId`, `krutumsId`, `dateModified`) VALUES
(136, 180, '2012-05-01 23:34:46'),
(137, 181, '2012-05-01 23:34:46'),
(138, 182, '2012-05-01 23:34:46'),
(139, 183, '2012-05-01 23:34:46'),
(140, 184, '2012-05-01 23:34:46'),
(141, 185, '2012-05-01 23:34:46'),
(142, 186, '2012-05-01 23:34:46'),
(143, 187, '2012-05-01 23:34:46'),
(144, 188, '2012-05-01 23:34:46'),
(145, 189, '2012-05-01 23:34:47'),
(146, 190, '2012-05-01 23:34:47'),
(147, 191, '2012-05-01 23:34:47'),
(148, 192, '2012-05-01 23:34:47'),
(149, 193, '2012-05-01 23:34:47'),
(150, 194, '2012-05-01 23:34:47'),
(151, 195, '2012-05-01 23:34:47'),
(152, 196, '2012-05-01 23:34:47'),
(153, 197, '2012-05-01 23:34:47'),
(154, 198, '2012-05-01 23:34:47'),
(155, 199, '2012-05-01 23:34:47'),
(156, 200, '2012-05-01 23:34:48'),
(157, 201, '2012-05-01 23:37:16'),
(158, 202, '2012-05-01 23:37:16'),
(159, 203, '2012-05-01 23:37:16'),
(160, 204, '2012-05-01 23:37:16'),
(161, 205, '2012-05-01 23:37:16'),
(162, 206, '2012-05-01 23:37:16'),
(163, 207, '2012-05-01 23:37:16'),
(164, 208, '2012-05-01 23:37:17'),
(165, 209, '2012-05-01 23:37:17'),
(166, 210, '2012-05-01 23:37:17'),
(167, 211, '2012-05-01 23:37:17'),
(168, 212, '2012-05-01 23:37:17'),
(169, 213, '2012-05-01 23:37:17'),
(170, 214, '2012-05-01 23:37:17'),
(171, 215, '2012-05-01 23:37:17'),
(172, 216, '2012-05-01 23:37:17'),
(173, 217, '2012-05-01 23:37:17'),
(174, 218, '2012-05-01 23:37:18'),
(175, 219, '2012-05-01 23:37:18'),
(176, 220, '2012-05-01 23:37:18'),
(177, 221, '2012-05-01 23:38:24'),
(178, 222, '2012-05-01 23:38:24'),
(179, 223, '2012-05-01 23:38:25'),
(180, 224, '2012-05-01 23:38:25'),
(181, 225, '2012-05-01 23:38:25'),
(182, 226, '2012-05-01 23:38:25'),
(183, 227, '2012-05-01 23:38:25'),
(184, 228, '2012-05-01 23:38:25'),
(185, 229, '2012-05-01 23:38:25'),
(186, 230, '2012-05-01 23:38:25'),
(187, 231, '2012-05-01 23:38:25'),
(188, 232, '2012-05-01 23:38:26'),
(189, 233, '2012-05-01 23:38:26'),
(190, 234, '2012-05-01 23:38:26'),
(191, 235, '2012-05-01 23:38:26'),
(192, 236, '2012-05-01 23:38:26'),
(193, 237, '2012-05-01 23:38:26'),
(194, 238, '2012-05-01 23:38:26'),
(195, 239, '2012-05-01 23:38:26'),
(196, 240, '2012-05-01 23:38:26'),
(197, 241, '2012-05-01 23:41:25'),
(198, 242, '2012-05-01 23:41:25'),
(199, 243, '2012-05-01 23:41:25'),
(200, 244, '2012-05-01 23:41:25'),
(201, 245, '2012-05-01 23:41:26'),
(202, 246, '2012-05-01 23:41:26'),
(203, 247, '2012-05-01 23:41:26'),
(204, 248, '2012-05-01 23:41:26'),
(205, 249, '2012-05-01 23:41:26'),
(206, 250, '2012-05-01 23:41:26'),
(207, 251, '2012-05-01 23:41:26'),
(208, 252, '2012-05-01 23:41:26'),
(209, 253, '2012-05-01 23:41:26'),
(210, 254, '2012-05-01 23:41:27'),
(211, 255, '2012-05-01 23:41:27'),
(212, 256, '2012-05-01 23:41:27'),
(213, 257, '2012-05-01 23:41:27'),
(214, 258, '2012-05-01 23:41:27'),
(215, 259, '2012-05-01 23:41:27'),
(216, 260, '2012-05-01 23:41:27'),
(217, 261, '2012-05-01 23:41:27'),
(218, 262, '2012-05-01 23:41:27'),
(219, 263, '2012-05-01 23:41:28');

-- --------------------------------------------------------

--
-- Table structure for table `attachments`
--

CREATE TABLE IF NOT EXISTS `attachments` (
  `attachmentId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `file` text NOT NULL,
  `tournamentId` int(11) NOT NULL,
  PRIMARY KEY (`attachmentId`),
  KEY `pielikumsTurnirsId` (`tournamentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `attachments`
--


-- --------------------------------------------------------

--
-- Table structure for table `galleries`
--

CREATE TABLE IF NOT EXISTS `galleries` (
  `galleryId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `tournamentId` int(11) NOT NULL,
  PRIMARY KEY (`galleryId`),
  KEY `galerijaTurnirsId` (`tournamentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `galleries`
--


-- --------------------------------------------------------

--
-- Table structure for table `jaunumukategorijas`
--

CREATE TABLE IF NOT EXISTS `jaunumukategorijas` (
  `kategorijaId` int(11) NOT NULL AUTO_INCREMENT,
  `kategorijaTitle` text NOT NULL,
  `kategorijaDescription` text NOT NULL,
  `kategorijaCreated` datetime NOT NULL,
  PRIMARY KEY (`kategorijaId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `jaunumukategorijas`
--

INSERT INTO `jaunumukategorijas` (`kategorijaId`, `kategorijaTitle`, `kategorijaDescription`, `kategorijaCreated`) VALUES
(1, 'sdf', 'sdf', '2012-04-27 22:07:43');

-- --------------------------------------------------------

--
-- Table structure for table `punkti`
--

CREATE TABLE IF NOT EXISTS `krutums` (
  `krutumsId` int(11) NOT NULL AUTO_INCREMENT,
  `krutumsValue` int(11) NOT NULL DEFAULT '0',
  `krutumsEvent` int(11) NOT NULL DEFAULT '0',
  `krutumsNote` text NOT NULL,
  `krutumsUserId` int(11) NOT NULL,
  `krutumsDate` datetime NOT NULL,
  PRIMARY KEY (`krutumsId`),
  KEY `krutumsUserId` (`krutumsUserId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `punkti`
--


-- --------------------------------------------------------

--
-- Table structure for table `maksajumi`
--

CREATE TABLE IF NOT EXISTS `maksajumi` (
  `maksajumsId` int(11) NOT NULL AUTO_INCREMENT,
  `maksajumsTitle` text NOT NULL,
  `maksajumsValue` decimal(10,0) NOT NULL,
  `maksajumsUserId` int(11) NOT NULL,
  `maksajumsCreated` datetime NOT NULL,
  `maksajumsFinished` datetime DEFAULT NULL,
  `maksajumsCompleted` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`maksajumsId`),
  KEY `maksajumsUserId` (`maksajumsUserId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=62 ;

--
-- Dumping data for table `maksajumi`
--

INSERT INTO `maksajumi` (`maksajumsId`, `maksajumsTitle`, `maksajumsValue`, `maksajumsUserId`, `maksajumsCreated`, `maksajumsFinished`, `maksajumsCompleted`) VALUES
(1, 'Fonda nauda par Septembri', '3', 33, '2012-05-03 00:54:31', NULL, 0),
(2, 'Fonda nauda par Septembri', '3', 31, '2012-05-03 00:54:32', NULL, 0),
(3, 'Fonda nauda par Septembri', '3', 35, '2012-05-03 00:54:32', NULL, 0),
(4, 'Fonda nauda par Septembri', '3', 26, '2012-05-03 00:54:32', NULL, 0),
(5, 'Fonda nauda par Oktobri', '3', 33, '2012-05-03 00:55:31', NULL, 0),
(6, 'Fonda nauda par Oktobri', '3', 31, '2012-05-03 00:55:31', NULL, 0),
(7, 'Fonda nauda par Oktobri', '3', 35, '2012-05-03 00:55:31', NULL, 0),
(8, 'Fonda nauda par Oktobri', '3', 26, '2012-05-03 00:55:31', NULL, 0),
(9, 'Fonda nauda par Oktobri', '3', 36, '2012-05-03 00:55:31', NULL, 0),
(10, 'Fonda nauda par Novembri', '3', 18, '2012-05-03 00:56:40', NULL, 0),
(11, 'Fonda nauda par Novembri', '3', 33, '2012-05-03 00:56:41', NULL, 0),
(12, 'Fonda nauda par Novembri', '3', 31, '2012-05-03 00:56:41', NULL, 0),
(13, 'Fonda nauda par Novembri', '3', 35, '2012-05-03 00:56:41', NULL, 0),
(14, 'Fonda nauda par Novembri', '3', 26, '2012-05-03 00:56:42', NULL, 0),
(15, 'Fonda nauda par Novembri', '3', 36, '2012-05-03 00:56:43', NULL, 0),
(16, 'Fonda nauda par Decembri', '3', 14, '2012-05-03 00:57:49', NULL, 0),
(17, 'Fonda nauda par Decembri', '3', 18, '2012-05-03 00:57:49', NULL, 0),
(18, 'Fonda nauda par Decembri', '3', 33, '2012-05-03 00:57:49', NULL, 0),
(19, 'Fonda nauda par Decembri', '3', 31, '2012-05-03 00:57:49', NULL, 0),
(20, 'Fonda nauda par Decembri', '3', 35, '2012-05-03 00:57:49', NULL, 0),
(21, 'Fonda nauda par Decembri', '3', 26, '2012-05-03 00:57:49', NULL, 0),
(22, 'Fonda nauda par Decembri', '3', 36, '2012-05-03 00:57:49', NULL, 0),
(23, 'Fonda nauda par Janvāri', '3', 14, '2012-05-03 00:59:09', NULL, 0),
(24, 'Fonda nauda par Janvāri', '3', 18, '2012-05-03 00:59:09', NULL, 0),
(25, 'Fonda nauda par Janvāri', '3', 24, '2012-05-03 00:59:09', NULL, 0),
(26, 'Fonda nauda par Janvāri', '3', 33, '2012-05-03 00:59:09', NULL, 0),
(27, 'Fonda nauda par Janvāri', '3', 31, '2012-05-03 00:59:09', NULL, 0),
(28, 'Fonda nauda par Janvāri', '3', 35, '2012-05-03 00:59:09', NULL, 0),
(29, 'Fonda nauda par Janvāri', '3', 36, '2012-05-03 00:59:09', NULL, 0),
(30, 'Fonda nauda par Februāri', '3', 38, '2012-05-03 01:03:27', NULL, 0),
(31, 'Fonda nauda par Februāri', '3', 14, '2012-05-03 01:03:28', NULL, 0),
(32, 'Fonda nauda par Februāri', '3', 18, '2012-05-03 01:03:28', NULL, 0),
(33, 'Fonda nauda par Februāri', '3', 16, '2012-05-03 01:03:28', NULL, 0),
(34, 'Fonda nauda par Februāri', '3', 24, '2012-05-03 01:03:28', NULL, 0),
(35, 'Fonda nauda par Februāri', '3', 9, '2012-05-03 01:03:28', NULL, 0),
(36, 'Fonda nauda par Februāri', '3', 39, '2012-05-03 01:03:28', NULL, 0),
(37, 'Fonda nauda par Februāri', '3', 33, '2012-05-03 01:03:28', NULL, 0),
(38, 'Fonda nauda par Februāri', '3', 19, '2012-05-03 01:03:28', NULL, 0),
(39, 'Fonda nauda par Februāri', '3', 31, '2012-05-03 01:03:28', NULL, 0),
(40, 'Fonda nauda par Februāri', '3', 35, '2012-05-03 01:03:28', NULL, 0),
(41, 'Fonda nauda par Februāri', '3', 10, '2012-05-03 01:03:28', NULL, 0),
(42, 'Fonda nauda par Februāri', '3', 26, '2012-05-03 01:03:28', NULL, 0),
(43, 'Fonda nauda par Februāri', '3', 40, '2012-05-03 01:03:28', NULL, 0),
(44, 'Fonda nauda par Februāri', '3', 36, '2012-05-03 01:03:28', NULL, 0),
(45, 'Fonda nauda par Martu', '3', 38, '2012-05-03 01:05:37', NULL, 0),
(46, 'Fonda nauda par Martu', '3', 14, '2012-05-03 01:05:37', NULL, 0),
(47, 'Fonda nauda par Martu', '3', 15, '2012-05-03 01:05:37', NULL, 0),
(48, 'Fonda nauda par Martu', '3', 18, '2012-05-03 01:05:37', NULL, 0),
(49, 'Fonda nauda par Martu', '3', 16, '2012-05-03 01:05:37', NULL, 0),
(50, 'Fonda nauda par Martu', '3', 9, '2012-05-03 01:05:37', NULL, 0),
(51, 'Fonda nauda par Martu', '3', 39, '2012-05-03 01:05:37', NULL, 0),
(52, 'Fonda nauda par Martu', '3', 37, '2012-05-03 01:05:37', NULL, 0),
(53, 'Fonda nauda par Martu', '3', 33, '2012-05-03 01:05:37', NULL, 0),
(54, 'Fonda nauda par Martu', '3', 19, '2012-05-03 01:05:37', NULL, 0),
(55, 'Fonda nauda par Martu', '3', 31, '2012-05-03 01:05:37', NULL, 0),
(56, 'Fonda nauda par Martu', '3', 35, '2012-05-03 01:05:37', NULL, 0),
(57, 'Fonda nauda par Martu', '3', 10, '2012-05-03 01:05:37', NULL, 0),
(58, 'Fonda nauda par Martu', '3', 26, '2012-05-03 01:05:37', NULL, 0),
(59, 'Fonda nauda par Martu', '3', 17, '2012-05-03 01:05:37', NULL, 0),
(60, 'Fonda nauda par Martu', '3', 40, '2012-05-03 01:05:37', NULL, 0),
(61, 'Fonda nauda par Martu', '3', 36, '2012-05-03 01:05:37', NULL, 0);

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `newsId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `content` longtext NOT NULL,
  `pictureId` int(11) DEFAULT NULL,
  `galleryId` int(11) DEFAULT NULL,
  `ownerId` int(11) NOT NULL,
  `time` datetime NOT NULL,
  PRIMARY KEY (`newsId`),
  KEY `thumbnail` (`pictureId`),
  KEY `galerija` (`galleryId`),
  KEY `ownerId` (`ownerId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`newsId`, `title`, `content`, `pictureId`, `galleryId`, `ownerId`, `time`) VALUES
(1, 'ghj', 'ghjghj', NULL, NULL, 6, '2012-02-19 02:12:30');

-- --------------------------------------------------------

--
-- Table structure for table `participants`
--

CREATE TABLE IF NOT EXISTS `participants` (
  `userId` int(11) NOT NULL,
  `tournamentId` int(11) NOT NULL,
  PRIMARY KEY (`userId`,`tournamentId`),
  KEY `userId` (`userId`),
  KEY `turnirsId` (`tournamentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `participants`
--


-- --------------------------------------------------------

--
-- Table structure for table `pasakumi`
--

CREATE TABLE IF NOT EXISTS `pasakumi` (
  `pasakumsId` int(11) NOT NULL AUTO_INCREMENT,
  `pasakumsTitle` text NOT NULL,
  `pasakumsDescription` longtext NOT NULL,
  `pasakumsTime` datetime NOT NULL,
  `pasakumsLocation` text NOT NULL,
  `pasakumsImage` text,
  `pasakumsAccess` int(11) NOT NULL DEFAULT '1',
  `pasakumsCategory` int(11) NOT NULL,
  PRIMARY KEY (`pasakumsId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `pasakumi`
--

INSERT INTO `pasakumi` (`pasakumsId`, `pasakumsTitle`, `pasakumsDescription`, `pasakumsTime`, `pasakumsLocation`, `pasakumsImage`, `pasakumsAccess`, `pasakumsCategory`) VALUES
(2, 'Mēģinājums', '', '2012-04-16 20:00:00', 'K/n Lielvārde', NULL, 1, 2),
(3, 'Mēģinājums', '', '2012-04-19 20:00:00', 'K/n Lielvārde', NULL, 1, 2),
(4, 'Mēģinājums', '', '2012-04-23 20:00:00', 'K/n Lielvārde', NULL, 1, 2),
(5, 'Koncerts Mālpilī', 'Pirms/Pēc koncerta - sporta spēles!\r\n\r\nĢērbjamies atbilstoši!', '2012-05-05 13:00:00', 'Mālpils K/n', NULL, 1, 3),
(6, 'Mēģinājums', '', '2012-04-26 20:00:00', 'K/n Lielvārde', NULL, 1, 2),
(7, 'dfg', 'dfgdfg', '2012-05-17 00:00:00', 'dfg', NULL, 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `pasakumitype`
--

CREATE TABLE IF NOT EXISTS `pasakumitype` (
  `typeId` int(11) NOT NULL AUTO_INCREMENT,
  `typeTitle` text NOT NULL,
  `typeDescription` text NOT NULL,
  `typeParent` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`typeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

--
-- Dumping data for table `pasakumitype`
--

INSERT INTO `pasakumitype` (`typeId`, `typeTitle`, `typeDescription`, `typeParent`) VALUES
(2, 'Mēģinājums', 'Ikdienišķs mēģinājums', 0),
(3, 'Koncerts', 'Ikdienišķs koncerts!', 0);

-- --------------------------------------------------------

--
-- Table structure for table `pictures`
--

CREATE TABLE IF NOT EXISTS `pictures` (
  `pictureId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` text,
  `galleryId` int(11) NOT NULL,
  PRIMARY KEY (`pictureId`),
  KEY `galerijaId` (`galleryId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `pictures`
--


-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE IF NOT EXISTS `ratings` (
  `ratingId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `rating` decimal(10,0) NOT NULL,
  `raterId` int(11) NOT NULL,
  PRIMARY KEY (`ratingId`),
  KEY `lietotajsId` (`userId`),
  KEY `vertetajsId` (`raterId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `rent`
--

CREATE TABLE IF NOT EXISTS `rent` (
  `rentId` int(11) NOT NULL AUTO_INCREMENT,
  `description` text NOT NULL,
  `place` varchar(255) NOT NULL,
  `link` text,
  `email` varchar(320) NOT NULL,
  `phone` varchar(32) NOT NULL,
  PRIMARY KEY (`rentId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `rent`
--


-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE IF NOT EXISTS `teams` (
  `teamId` int(11) NOT NULL AUTO_INCREMENT,
  `tournamentId` int(11) NOT NULL,
  `teamName` varchar(45) NOT NULL,
  `teamOwner` int(11) NOT NULL,
  PRIMARY KEY (`teamId`),
  KEY `tournamentId` (`tournamentId`),
  KEY `teamOwner` (`teamOwner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `teams`
--


-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(320) NOT NULL,
  `role` set('user','editor','admin') NOT NULL,
  `draugiemId` bigint(20) NOT NULL,
  `registered` datetime NOT NULL,
  `isApproved` tinyint(4) NOT NULL DEFAULT '0',
  `icon` text NOT NULL,
  PRIMARY KEY (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=42 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `name`, `email`, `role`, `draugiemId`, `registered`, `isApproved`, `icon`) VALUES
(9, 'Jānis Peisenieks', 'sdf', 'admin', 72275, '2012-04-16 23:32:21', 1, 'http://i5.ifrype.com/profile/072/275/v2/sm_72275.jpg'),
(10, 'Mārtiņš StanevichZ', 'sdf', 'user', 1497614, '2012-04-17 05:02:20', 1, 'http://i4.ifrype.com/profile/497/614/v1334450700/sm_1497614.jpg'),
(11, 'Ilmārs Pokšans', 'sdf', 'user', 1227236, '2012-04-17 05:43:24', 1, 'http://i6.ifrype.com/profile/227/236/v1321713013/sm_1227236.jpg'),
(13, 'Līva Prekele', 'sdf', 'user', 892039, '2012-04-17 09:09:31', 1, 'http://i9.ifrype.com/profile/892/039/v1334578518/sm_892039.jpg'),
(14, 'Artis Skrīvelis', 'sdf', 'user', 201272, '2012-04-17 12:08:07', 1, 'http://i2.ifrype.com/profile/201/272/v1333921564/sm_201272.jpg'),
(15, 'Beate Klipa', 'sdf', 'user', 3224491, '2012-04-17 15:23:19', 1, 'http://i1.ifrype.com/profile/224/491/v1333735316/sm_3224491.jpg'),
(16, 'Haralds Rimeicāns', 'sdf', 'user', 4452987, '2012-04-17 15:28:53', 1, ''),
(17, 'Santa Kudreņicka', 'sdf', 'user', 1345782, '2012-04-17 16:29:51', 1, 'http://i2.ifrype.com/profile/345/782/v1319630312/sm_1345782.jpg'),
(18, 'Beate Preimane', 'sdf', 'user', 3862317, '2012-04-17 16:41:53', 1, 'http://i7.ifrype.com/profile/862/317/v1334168829/sm_3862317.jpg'),
(19, 'Krišjānis Kārkliņš', 'sdf', 'user', 554222, '2012-04-17 18:42:59', 1, ''),
(20, 'Anna Stanka', 'sdf', 'user', 644280, '2012-04-17 19:32:40', 1, 'http://i0.ifrype.com/profile/644/280/v1332945147/sm_644280.jpg'),
(21, 'Artūrs Pokšāns', 'sdf', 'user', 1245771, '2012-04-18 15:50:07', 1, 'http://i1.ifrype.com/profile/245/771/v1298495208/sm_1245771.jpg'),
(22, 'Ansis Uškaurs', 'sdf', 'user', 454854, '2012-04-18 19:41:44', 1, 'http://i4.ifrype.com/profile/454/854/v1331722102/sm_454854.jpg'),
(23, 'Austra Peltmane', 'sdf', 'user', 884699, '2012-04-18 19:46:03', 1, 'http://i9.ifrype.com/profile/884/699/v1334481325/sm_884699.jpg'),
(24, 'Igors Mitušovs', 'sdf', 'user', 218011, '2012-04-19 14:11:22', 1, 'http://i1.ifrype.com/profile/218/011/v1315167304/sm_218011.jpg'),
(25, 'Vita Skrīvele', 'sdf', 'user', 239596, '2012-04-19 19:35:39', 1, 'http://i6.ifrype.com/profile/239/596/v1334173834/sm_239596.jpg'),
(26, 'Sabīne Grīna', 'sdf', 'user', 1148831, '2012-04-19 23:35:39', 1, ''),
(28, 'Kristiāna Kārkliņa', 'sdf', 'user', 3238883, '2012-04-20 14:47:33', 1, 'http://i3.ifrype.com/profile/238/883/v1333726406/sm_3238883.jpg'),
(29, 'Elīna Upmane', 'sdf', 'user', 124019, '2012-04-20 17:11:34', 1, 'http://i9.ifrype.com/profile/124/019/v1334524131/sm_124019.jpg'),
(30, 'Agita Valtasa', 'sdf', 'user', 388062, '2012-04-20 21:55:10', 1, 'http://i2.ifrype.com/profile/388/062/v1321481117/sm_388062.jpg'),
(31, 'Kristians Nolmanis', 'sdf', 'user', 350688, '2012-04-24 06:51:19', 1, 'http://i8.ifrype.com/profile/350/688/v1318948877/sm_350688.jpg'),
(32, 'Patrīcija Mize', 'sdf', 'user', 1413321, '2012-04-24 14:52:22', 1, 'http://i1.ifrype.com/profile/413/321/v1334668457/sm_1413321.jpg'),
(33, 'Kaspars Ušpelis', 'sdf', 'user', 25956, '2012-04-24 20:05:40', 1, 'http://i6.ifrype.com/profile/025/956/v1322547842/sm_25956.jpg'),
(35, 'Liene Kalniņa', 'sdf', 'user', 187331, '2012-05-02 21:46:08', 1, ''),
(36, 'Sindija Grīna', 'sdf', 'user', 2742761, '2012-05-02 21:46:31', 1, ''),
(37, 'Karlīna Kvēpa', 'sdf', 'user', 527947, '2012-05-03 00:41:00', 1, ''),
(38, 'agnese tauriņa', 'sdf', 'user', 1307405, '2012-05-03 00:41:45', 1, ''),
(39, 'Jānis Priževoits', 'sdf', 'user', 158536, '2012-05-03 00:42:47', 1, ''),
(40, 'Sigita Batraga', 'sdf', 'user', 70529, '2012-05-03 02:00:00', 1, ''),
(41, 'Linda Zelmene', 'sdf', 'user', 1448298, '2012-05-03 01:00:00', 0, '');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `apmekletiba`
--
ALTER TABLE `apmekletiba`
  ADD CONSTRAINT `apmekletiba_ibfk_1` FOREIGN KEY (`apmekletibaEventId`) REFERENCES `pasakumi` (`pasakumsId`);

--
-- Constraints for table `punkti`
--
ALTER TABLE `krutums`
  ADD CONSTRAINT `krutums_ibfk_1` FOREIGN KEY (`krutumsUserId`) REFERENCES `users` (`userId`) ON DELETE CASCADE;
