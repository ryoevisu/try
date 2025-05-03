-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 01, 2024 at 11:26 AM
-- Server version: 5.6.38
-- PHP Version: 7.4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bl`
--

-- --------------------------------------------------------

--
-- Table structure for table `cooldown`
--

CREATE TABLE `cooldown` (
  `id` int(6) UNSIGNED NOT NULL,
  `id_value` varchar(30) NOT NULL,
  `last_used` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cooldown`
--

INSERT INTO `cooldown` (`id`, `id_value`, `last_used`) VALUES
(1, '61557085521167', '2024-03-01 09:00:17');

-- --------------------------------------------------------

--
-- Table structure for table `Likers`
--

CREATE TABLE `Likers` (
  `id` int(20) NOT NULL,
  `user_id` varchar(32) NOT NULL,
  `name` varchar(32) NOT NULL,
  `access_token` varchar(255) NOT NULL,
  `activate` int(1) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Likers`
--

INSERT INTO `Likers` (`id`, `user_id`, `name`, `access_token`, `activate`) VALUES
(1, '61557085521167', 'Lloyd Vincent', 'EAAAAUaZA8jlABOwV3MrSLKcEDZAfAT90bCFlf3FZCCIYKkcOQMBm1zKxiDwYv6OKXTLMZCnJAdQFVZCPImsMJdcN0cGapZCY62ImSUDeZBLL0KkhrXNRjQZAUnMvMI6AbW0iixIuOerh8Q0943qpisWZCIMuy6XcBTNcIywJrSyhYbcM4QajgS7y5jpLCFgYRpM7Q4GbN1zSaOAZDZD', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cooldown`
--
ALTER TABLE `cooldown`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `Likers`
--
ALTER TABLE `Likers`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cooldown`
--
ALTER TABLE `cooldown`
  MODIFY `id` int(6) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `Likers`
--
ALTER TABLE `Likers`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
