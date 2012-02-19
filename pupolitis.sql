-- phpMyAdmin SQL Dump
-- version 3.3.3
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 20, 2012 at 04:25 PM
-- Server version: 5.1.50
-- PHP Version: 5.3.3

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
-- Table structure for table `sponsors`
--

CREATE TABLE IF NOT EXISTS `sponsors` (
  `sponsorId` int(11) NOT NULL AUTO_INCREMENT,
  `userId` int(11) NOT NULL,
  `demands` text NOT NULL,
  `offers` text NOT NULL,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`sponsorId`),
  KEY `lietotajsId` (`userId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `sponsors`
--


-- --------------------------------------------------------

--
-- Table structure for table `teammembers`
--

CREATE TABLE IF NOT EXISTS `teammembers` (
  `memberId` int(11) NOT NULL,
  `teamId` int(11) NOT NULL,
  KEY `PK` (`memberId`,`teamId`),
  KEY `userId` (`memberId`),
  KEY `teamId` (`teamId`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `teammembers`
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
-- Table structure for table `tournaments`
--

CREATE TABLE IF NOT EXISTS `tournaments` (
  `tournamentId` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `description` longtext,
  `time` datetime NOT NULL,
  `tournamentOwner` int(11) NOT NULL,
  `logo` text,
  PRIMARY KEY (`tournamentId`),
  KEY `turnirsOwner` (`tournamentOwner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Dumping data for table `tournaments`
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
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=8 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userId`, `name`, `email`, `role`, `draugiemId`, `registered`, `isApproved`, `icon`) VALUES
(7, 'Jānis Peisenieks', 'sdf', 'admin', 72275, '2012-02-20 14:20:31', 1, 'http://i5.ifrype.com/profile/072/275/v2/sm_72275.jpg');
