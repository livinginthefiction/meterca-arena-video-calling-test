-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Sep 11, 2023 at 06:38 AM
-- Server version: 10.6.14-MariaDB-cll-lve
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u313289538_arena`
--

-- --------------------------------------------------------

--
-- Table structure for table `session_participants`
--

CREATE TABLE `session_participants` (
  `sessionid` int(11) DEFAULT NULL,
  `userid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `userid` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password_hash` varchar(255) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`userid`, `username`, `email`, `password_hash`) VALUES
(1, 'shubham', 's@a.com', '$2y$10$JQn4ORVJC2ow8yU.Osf8sOEj8NlP4dF7IbHVw04pR1zZeUBKQxXuW'),
(2, 'arena', 'a@a.com', '$2y$10$uex7aQxlp9AnSyix2nzGQuCAKWJEeuHb0kzx43M5f.CZW3Hqmqw8O'),
(3, 'Gene', 'elloringene@gmail.com', '$2y$10$qibr3KHNDS1YJiTexPzc..ARf9BddWBI7wwHcJYCtvvZzSOrfNkxa'),
(4, 'Tapiwa', 'tapiwa@arenacapital.com', '$2y$10$a1hYKSHE8uxb5u4Vsgnp3OJ7VzeD9jJ7FUY1eLO9v52EfChXuVwL.'),
(5, 'Shara', 'shara@gmail.com', '$2y$10$DkeQnDESmknsdSQ8Fg0CUOW/SkeXsbhI5.PjohRwjFD7SVNeuaLDC'),
(6, 'Tapiwa 2', 'taps@mail.com', '$2y$10$HlXztc7i/GvSqcqGfTNcs.xK9KHT74y5GAbxPjwSLeQLNDeFVgSQi');

-- --------------------------------------------------------

--
-- Table structure for table `video_call_sessions`
--

CREATE TABLE `video_call_sessions` (
  `sessionid` int(11) NOT NULL,
  `starttime` datetime NOT NULL,
  `receivetime` datetime DEFAULT NULL,
  `endtime` datetime DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `callerid` int(11) DEFAULT NULL,
  `receiverid` int(11) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `video_call_sessions`
--

INSERT INTO `video_call_sessions` (`sessionid`, `starttime`, `receivetime`, `endtime`, `duration`, `callerid`, `receiverid`) VALUES
(15, '2023-09-11 06:23:27', '2023-09-11 06:23:32', '2023-09-11 06:24:10', 38, 2, 1),
(14, '2023-09-11 06:12:38', '2023-09-11 06:12:42', '2023-09-11 06:13:00', 18, 2, 1),
(13, '2023-09-11 05:50:49', '2023-09-11 05:51:01', '2023-09-11 05:51:13', 12, 2, 1),
(12, '2023-09-11 05:47:28', '2023-09-11 05:47:32', '2023-09-11 05:48:33', 61, 2, 1),
(11, '2023-09-11 05:45:00', '2023-09-11 05:45:04', '2023-09-11 05:46:04', 60, 2, 1),
(10, '2023-09-11 03:53:51', '2023-09-11 03:53:55', '2023-09-11 03:54:05', 10, 2, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `session_participants`
--
ALTER TABLE `session_participants`
  ADD KEY `sessionid` (`sessionid`),
  ADD KEY `userid` (`userid`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`userid`);

--
-- Indexes for table `video_call_sessions`
--
ALTER TABLE `video_call_sessions`
  ADD PRIMARY KEY (`sessionid`),
  ADD KEY `callerid` (`callerid`),
  ADD KEY `receiverid` (`receiverid`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `userid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `video_call_sessions`
--
ALTER TABLE `video_call_sessions`
  MODIFY `sessionid` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
