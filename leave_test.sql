-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 08, 2024 at 12:03 PM
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
  `rejection_reason` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `leaves`
--

INSERT INTO `leaves` (`id`, `eid`, `empID`, `ename`, `descr`, `fromdate`, `todate`, `ActorDepartment`, `ActorEmployeeID`, `Actorfullname`, `status`, `rejection_reason`, `file_path`) VALUES
(1, 3, 'E002', 'user-2', 'Sick : ...............', '2024-11-09', '2024-11-12', 'Physics', 'E002', 'user-2', 'Rejected', NULL, NULL),
(2, 3, 'E002', 'user-2', 'Casual : ............', '2024-11-09', '2024-11-11', 'Physics', 'E002', 'user-2', 'Rejected', 'mmmmmmmmmmmmm', NULL),
(3, 3, 'E002', 'user-2', 'Sick : safsjskjhfsdfvbvfsfhes', '2024-11-14', '2024-11-16', 'Physics', 'E002', 'user-2', 'Rejected', 'm.m.m.m.m.m', NULL);

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
  `department` varchar(200) NOT NULL,
  `phone` varchar(150) NOT NULL,
  `reset_token` varchar(255) NOT NULL,
  `reset_expires` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `empID`, `password`, `type`, `email`, `gender`, `department`, `phone`, `reset_token`, `reset_expires`) VALUES
(1, 'ADMIN', 'Admin', '$2y$10$OWEyMDU2YjUzMjdmZWI0Z.aDy53IvthsNNJHAR8lZmF4b5QR4qVwS', 'admin', 'leavemanagementsystem.dcs@outlook.com', 'Male', '', '12345678', '', 0),
(2, 'user-1', 'E001', '$2y$10$MWMzNGRlNDFhZTkwZWUzMutHsu0Bp9R73Kd5ASvnbPf/D8aTtSDb.', 'employee', 'testdata1324@gmail.com', 'Male', 'Computer Science', '0712345678', '', 0),
(3, 'user-2', 'E002', '$2y$10$YzVkNzcyZTU2ZWExYmYwNOjWgYvQX2fhdhWnIwGLGv1RD4ytu3GeW', 'employee', 'user2@gmail.com', 'Female', 'Physics', '0742565651', '', 0),
(4, 'user-3', 'E003', '$2y$10$NTBmMjkwMTgwYjljZGFlYOEvWSU7vHjs1Po8ZMSsCeDmwO9vmZxpy', 'employee', 'user3@gmail.com', 'Female', 'Mathematics and Statistics', '0752346514', '', 0),
(5, 'user-4', 'E004', '$2y$10$ZWMwOTA4ODU2YzEwMDVjNO1fjpBa1O425zymKO4TtRUYuNCiYcy2.', 'employee', 'user4@gmail.com', 'Male', 'Chemistry', '0760132446', '', 0),
(6, 'user-5', 'E005', '$2y$10$MjFlNzdhZDI1MTI0MWJmNuYTe/Dyd1TCmS.rTpbaxDJHVhQzFeiEG', 'employee', 'user5@gmail.com', 'Male', 'Botany', '0112465464', '', 0),
(7, 'user-6', 'E006', '$2y$10$MjdhNDI1NTMzZWY2ZWE0YOslbaRvAxob3xOHf9.HcXEYKFvQTM2sq', 'employee', 'user6@gmail.com', 'Male', 'Fisheries', '0750321564', '', 0),
(8, 'user-7', 'E007', '$2y$10$MjhhZGRhNDk3NzI4ZmFlMuIJ2V6yzItz7fouebdO6zypk6qclO2v2', 'employee', 'user7@gmail.com', 'Male', 'Computer Science', '0741221564', '', 0),
(9, 'user-8', 'E008', '$2y$10$YzBjZWNjZjY4MDdkNTJlM.i9zEQocKOU9GtdbrA98yJuc2izezo8e', 'employee', 'user8@gmail.com', 'Male', 'Computer Science', '0724658457', '', 0);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
