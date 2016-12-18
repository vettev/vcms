-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Nov 25, 2016 at 10:28 PM
-- Server version: 5.5.53-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `vcms`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `commentsEnabled` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_3AF346685E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `commentsEnabled`) VALUES
(1, 'Default', 1),
(2, 'Page', 0),
(3, 'Tutoriale', 1);

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE IF NOT EXISTS `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `postId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `createdAt` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_5F9E962A64B64DCC` (`userId`),
  KEY `IDX_5F9E962AE094D20D` (`postId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`id`, `postId`, `userId`, `content`, `createdAt`) VALUES
(10, 5, 1, 'KOLEÅ»KO', '2016-11-21 18:30:24'),
(11, 5, 2, 'OKEJ', '2016-11-23 18:37:33');

-- --------------------------------------------------------

--
-- Table structure for table `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `routeId` int(11) DEFAULT NULL,
  `displayFor` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `customUrl` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_7D053A93EF2EA341` (`routeId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `menu`
--

INSERT INTO `menu` (`id`, `name`, `routeId`, `displayFor`, `customUrl`) VALUES
(1, 'Homepage', 1, 'everyone', NULL),
(2, 'Login', 2, 'nologin', NULL),
(3, 'Register', 3, 'nologin', NULL),
(5, 'Admin panel', 4, 'admin', NULL),
(8, 'Logout', 8, 'user', NULL),
(11, 'Admin profile', NULL, 'everyone', '/user/Vette');

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE IF NOT EXISTS `posts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categoryId` int(11) NOT NULL,
  `userId` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8_unicode_ci NOT NULL,
  `image` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `distinguished` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_885DBAFA9C370B71` (`categoryId`),
  KEY `IDX_885DBAFA64B64DCC` (`userId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dumping data for table `posts`
--

INSERT INTO `posts` (`id`, `categoryId`, `userId`, `title`, `content`, `image`, `createdAt`, `distinguished`) VALUES
(5, 1, 1, 'TEST', '<p><strong>qweert</strong>sda dasd ad as dqwe</p>', NULL, '2016-11-21 16:06:56', 0),
(9, 2, 1, 'Homepage', '<hr>This is example page.\r\nYou can test VCMS\r\nRedactors can post posts, you can manage categories, menu, pages, users.\r\nIn page settings you can set title, description etc...\r\nRemember, posts in page category doesn''t display in blog!', NULL, '2016-11-23 22:27:40', 0);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE IF NOT EXISTS `roles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_B63E2EC75E237E06` (`name`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`) VALUES
(2, 'Admin'),
(3, 'Redact'),
(1, 'User');

-- --------------------------------------------------------

--
-- Table structure for table `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `route` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_32D5C2B35E237E06` (`name`),
  UNIQUE KEY `UNIQ_32D5C2B32C42079` (`route`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=12 ;

--
-- Dumping data for table `routes`
--

INSERT INTO `routes` (`id`, `name`, `route`) VALUES
(1, 'Homepage', 'homepage'),
(2, 'Login', 'user_login'),
(3, 'Register', 'user_new'),
(4, 'Admin panel', 'admin_panel'),
(5, 'User management', 'admin_users'),
(6, 'Category management', 'admin_categories'),
(7, 'Menu management', 'menu_management'),
(8, 'Logout', 'user_logout'),
(9, 'New post', 'post_new'),
(10, 'Pages management', 'admin_pages'),
(11, 'Blog', 'blog');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE IF NOT EXISTS `settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8_unicode_ci NOT NULL,
  `customMenu` tinyint(1) NOT NULL DEFAULT '0',
  `footerContent` longtext COLLATE utf8_unicode_ci,
  `homepage` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E545A0C52B36786B` (`title`),
  KEY `homepage` (`homepage`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `email` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `isActive` tinyint(1) NOT NULL,
  `roleId` int(11) NOT NULL,
  `avatar` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `createdAt` datetime NOT NULL,
  `about` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1483A5E9F85E0677` (`username`),
  UNIQUE KEY `UNIQ_1483A5E9E7927C74` (`email`),
  KEY `IDX_1483A5E9B8C2FD88` (`roleId`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `isActive`, `roleId`, `avatar`, `createdAt`, `about`) VALUES
(1, 'Vette', 'gie.dominik@gmail.com', '$2y$13$gx8WVFVzZcyc7.prLFbBD.XJQZF1zQ0sl7PGUlqM8IlbTq45O/fbe', 1, 2, NULL, '2016-11-20 21:28:50', NULL),
(2, 'mazi', 'ciota@mail.com', '$2y$13$gg1m95Kuqd48sbPREavRnexMyqFuXDjP5FdHSAF.3GM5L3XUtcNFq', 1, 1, 'f8b4f21b957d4674624a196415b4defd.jpeg', '2016-11-23 18:37:17', 'MAZI JEST GÃ“PI');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `FK_5F9E962A64B64DCC` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_5F9E962AE094D20D` FOREIGN KEY (`postId`) REFERENCES `posts` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `menu`
--
ALTER TABLE `menu`
  ADD CONSTRAINT `FK_7D053A93EF2EA341` FOREIGN KEY (`routeId`) REFERENCES `routes` (`id`);

--
-- Constraints for table `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `FK_885DBAFA64B64DCC` FOREIGN KEY (`userId`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_885DBAFA9C370B71` FOREIGN KEY (`categoryId`) REFERENCES `categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `FK_1483A5E9B8C2FD88` FOREIGN KEY (`roleId`) REFERENCES `roles` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
