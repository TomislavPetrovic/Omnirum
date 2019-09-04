-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2019 at 03:54 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.3.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `omnirum`
--
CREATE DATABASE IF NOT EXISTS `omnirum` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `omnirum`;

-- --------------------------------------------------------

--
-- Table structure for table `reply`
--

CREATE TABLE `reply` (
  `id` int(11) NOT NULL,
  `reply_text` text NOT NULL,
  `time_created` datetime NOT NULL,
  `time_edited` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reply_topic`
--

CREATE TABLE `reply_topic` (
  `id` int(11) NOT NULL,
  `id_reply` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `reply_user`
--

CREATE TABLE `reply_user` (
  `id` int(11) NOT NULL,
  `id_reply` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `topic`
--

CREATE TABLE `topic` (
  `id` int(11) NOT NULL,
  `topic_title` varchar(200) NOT NULL,
  `topic_text` text NOT NULL,
  `time_created` datetime NOT NULL,
  `last_active` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `topic_user`
--

CREATE TABLE `topic_user` (
  `id` int(11) NOT NULL,
  `id_topic` int(11) NOT NULL,
  `id_user` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `about` text,
  `username` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `admin` tinyint(1) NOT NULL DEFAULT '0',
  `time_registered` datetime NOT NULL,
  `last_active` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `email`, `about`, `username`, `password`, `admin`, `time_registered`, `last_active`) VALUES
(1, 'Admin', 'Admin', 'admin@admin.admin', NULL, 'admin', '$2y$10$J.eEXtnixGTP6bvnxHErkuQbkKtKXrUAEZMt8X0w0h4iCeI22Mok6', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `reply`
--
ALTER TABLE `reply`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reply_topic`
--
ALTER TABLE `reply_topic`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reply_topic_ibfk_1` (`id_reply`),
  ADD KEY `reply_topic_ibfk_2` (`id_topic`);

--
-- Indexes for table `reply_user`
--
ALTER TABLE `reply_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_reply` (`id_reply`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `topic`
--
ALTER TABLE `topic`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `topic_user`
--
ALTER TABLE `topic_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_topic` (`id_topic`),
  ADD KEY `id_user` (`id_user`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `reply`
--
ALTER TABLE `reply`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_topic`
--
ALTER TABLE `reply_topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reply_user`
--
ALTER TABLE `reply_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topic`
--
ALTER TABLE `topic`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `topic_user`
--
ALTER TABLE `topic_user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `reply_topic`
--
ALTER TABLE `reply_topic`
  ADD CONSTRAINT `reply_topic_ibfk_1` FOREIGN KEY (`id_reply`) REFERENCES `reply` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reply_topic_ibfk_2` FOREIGN KEY (`id_topic`) REFERENCES `topic` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `reply_user`
--
ALTER TABLE `reply_user`
  ADD CONSTRAINT `reply_user_ibfk_1` FOREIGN KEY (`id_reply`) REFERENCES `reply` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `reply_user_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `topic_user`
--
ALTER TABLE `topic_user`
  ADD CONSTRAINT `topic_user_ibfk_1` FOREIGN KEY (`id_topic`) REFERENCES `topic` (`id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `topic_user_ibfk_2` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
