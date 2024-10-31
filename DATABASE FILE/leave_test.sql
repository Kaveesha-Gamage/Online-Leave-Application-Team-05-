-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 31, 2024 at 10:26 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `leave_test`
--

-- --------------------------------------------------------

--
-- Table structure for table `leaves`
--

CREATE TABLE `leaves` (
  `id` int(11) NOT NULL,
  `eid` int(11) NOT NULL,
  `empID` varchar(255) NOT NULL,
  `ename` varchar(255) NOT NULL COMMENT 'Employee''s Username',
  `descr` varchar(255) NOT NULL COMMENT 'Leave Reason',
  `fromdate` date NOT NULL,
  `todate` date NOT NULL,
  `ActorDepartment` varchar(255) NOT NULL,
  `ActorEmployeeID` varchar(200) NOT NULL,
  `Actorfullname` varchar(200) NOT NULL,
  `status` varchar(255) NOT NULL,
  `rejection_reason` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `eid`, `empID`, `ename`, `descr`, `fromdate`, `todate`, `ActorDepartment`, `ActorEmployeeID`, `Actorfullname`, `status`, `rejection_reason`) VALUES
(46, 7, 'E001', 'user-1', 'Vacation : ..............', '2024-10-31', '2024-11-02', 'Select your Department', 'E002', 'abcd user ', 'Pending', NULL),
(47, 9, 'E002', 'user-2', 'Vacation : lorem ipusm lorem ipusm lorem ipusm lorem ipusm ', '2024-11-01', '2024-11-02', 'Chemistry', 'E002', 'abcd user ', 'Accepted', NULL),
(48, 7, 'E001', 'user-1', 'Other : reasons for your leave days', '2024-10-31', '2024-11-03', 'Computer Science', 'E002', 'user_2', 'Accepted', NULL),
(51, 7, 'E001', 'user-1', 'Casual : casual leave under personal reason.', '2024-10-31', '2024-11-02', 'Computer Science', 'E002', 'user_2', 'Rejected', NULL),
(52, 7, 'E001', 'user-1', 'Casual : leave request under personal reason.', '2024-11-01', '2024-11-03', 'Mathematics and Statistics', 'E003', 'user_3', 'Rejected', 'no more leaves available.'),
(53, 7, 'E001', 'user-1', ' : lorem ipusm lorem ipusm lorem ipusm lorem ipusm ', '2024-10-24', '2024-10-31', 'Physics', '', 'user-9', 'Pending', NULL),
(54, 7, 'E001', 'user-1', ' : lorem ipusm lorem ipusm lorem ipusm lorem ipusm ', '2024-10-24', '2024-10-31', 'Physics', '', 'user-9', 'Pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(255) NOT NULL,
  `empID` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `type` varchar(150) NOT NULL,
  `email` varchar(255) NOT NULL,
  `gender` varchar(150) NOT NULL,
  `department` varchar(20) NOT NULL,
  `phone` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `empID`, `password`, `type`, `email`, `gender`, `department`, `phone`) VALUES
(1, 'TEST_ADMIN', 'Admin', '$2y$10$OWEyMDU2YjUzMjdmZWI0Z.aDy53IvthsNNJHAR8lZmF4b5QR4qVwS', 'admin', 'testdata1324@gmail.com', 'Male', '', '12345678'),
(7, 'user-1', 'E001', '$2y$10$NzM1NzAyNzMwZTJhYjA5YeMfi9IdUIkruR197l8QOPDwTAfTZFL7y', 'employee', 'testdata1324@gmail.com', 'Male', 'Computer Science', '0123456789'),
(9, 'user-2', 'E002', '$2y$10$NDg2Yjk0YjBjZGI1NTY4MeuFeKkGhxDjfy050IQGGbK4HSG7lD4aC', 'employee', 'testdata1324@gmail.com', 'Female', 'Physics', '0123456789'),
(10, 'user-3', 'E003', '$2y$10$ZjUyNmZhYWQ4MjJlZTJjZOpDqayEEkiOMSGoxp1vw0XozOBLSlIJu', 'employee', 'testdata1324@gmail.com', 'Female', 'Mathematics and Stat', '0123456789'),
(11, 'user-4', 'E004', '$2y$10$ZDdlMTcwMTgwZDU3YzJlYeAiw8o7AVCuCaGgyJV/eBCWChX3PuL4O', 'employee', 'testdata1324@gmail.com', 'Male', 'Chemistry', '0123456789'),
(12, 'user-5', 'E005', '$2y$10$NWU1MzhjYjRhOWE3NDg4ZOKA.6QX2YIIW5wWB5Ck.mMPlfc5A3Vxa', 'employee', 'testdata1324@gmail.com', 'Male', 'Botany', '0123456789'),
(13, 'user-6', 'E006', '$2y$10$M2Y3OTNmZjZjNzEyZThmN.IqIJaruzlKaEHakux6bujaXy8sBeqf2', 'employee', 'testdata1324@gmail.com', 'Female', 'Fisheries', '0123456789'),
(14, 'user-7', 'E007', '$2y$10$NTliMDUzOWY4MDY5NDdkYOuwWD6TA2WTU5sxhWdby5DZ5pnn/4B8a', 'employee', 'testdata1324@gmail.com', 'Male', 'Zoology', '0123456789'),
(15, 'user-8', 'E008', '$2y$10$YWFhNDk4YTVjZGE1Y2UzYus1C4iq85Y1E.O0G9W7pCP.eOEu1oRlC', 'employee', 'testdata1324@gmail.com', 'Male', 'Computer Science', '0123456789'),
(16, 'user-9', 'E009', '$2y$10$MWM5YTVlMGIwMmE2MGE3ZO67338bbyYd0RkIu7gdPhXrZP9V3nRoy', 'employee', 'testdata1324@gmail.com', 'Female', 'Physics', '0123456789'),
(17, 'user-10', 'E010', '$2y$10$YWEwOWVhMTczOGFjNmRjYuQjggQLueh7ceHaUmXXo8XIaK17Pl40W', 'employee', 'testdata1324@gmail.com', 'Male', 'Computer Science', '0123456789'),
(18, 'user-11', 'E011', '$2y$10$MjAwYTdmMDgwNjZiNzE4NeJP3OEbVmiJvbqwZEP.cGqCxwM/0So3S', 'employee', 'testdata1324@gmail.com', 'Male', 'Physics', '0123456789'),
(19, 'user-12', 'E012', '$2y$10$YTBmMGIyMzM5NDY0NDkxNOlvqPmobdgfQigDc5B8jCYQz8USYsLzS', 'employee', 'testdata1324@gmail.com', 'Female', 'Physics', '0123456789');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `leaves`
--
ALTER TABLE `leaves`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `leaves`
--
ALTER TABLE `leaves`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
