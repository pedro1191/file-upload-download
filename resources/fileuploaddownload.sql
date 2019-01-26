-- phpMyAdmin SQL Dump
-- version 4.8.2
-- https://www.phpmyadmin.net/
--
-- Host: 192.168.0.128
-- Generation Time: Jan 26, 2019 at 11:29 PM
-- Server version: 5.7.25
-- PHP Version: 7.2.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `fileuploaddownload`
--
CREATE DATABASE IF NOT EXISTS `fileuploaddownload` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `fileuploaddownload`;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `name`, `password`, `created_at`, `email`) VALUES
(1, 'Test', '$2y$10$n8kqSgrsHM5rEo/ZIrNS1ua.geOD4ZyEnxsiWDe7xJu6Wm7Ueij.G', '2018-08-20 12:12:40', 'test@gmail.com'),
(2, 'Kesia', '$2y$10$f0/v1Q0YkndbS5gE0AXk2OvrgZNVMhHZq0zGbXHZmcruJ8SJVoeY.', '2018-08-20 14:06:56', 'kesia@gmail.com'),
(3, 'New User', '$2y$10$tvcwq8EXY7Of8fulafcdIujkSIaDKCaigeZCDe0PRLbd.Vk4dEQ8O', '2019-01-26 22:27:39', 'newuser@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
