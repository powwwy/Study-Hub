-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Jul 05, 2025 at 07:26 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `study_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `AdminID` varchar(30) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`AdminID`, `Name`, `password`) VALUES
('admin001', 'Maxwell', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `groupmemberships`
--

CREATE TABLE `groupmemberships` (
  `UserID` varchar(20) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `JoinedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupmemberships`
--

INSERT INTO `groupmemberships` (`UserID`, `GroupID`, `JoinedAt`) VALUES
('101010', 1, '2025-07-04 10:18:42'),
('152531', 1, '2025-07-04 09:28:45'),
('152531', 2, '2025-07-04 09:28:48');

-- --------------------------------------------------------

--
-- Table structure for table `groupmessages`
--

CREATE TABLE `groupmessages` (
  `MessageID` int(11) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `UserID` int(11) NOT NULL,
  `Message` text NOT NULL,
  `Timestamp` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `groupmessages`
--

INSERT INTO `groupmessages` (`MessageID`, `GroupID`, `UserID`, `Message`, `Timestamp`) VALUES
(1, 1, 1, 'Hello! This is a test message.', '2025-07-05 20:25:39'),
(2, 1, 2, 'Second test message from another user.', '2025-07-05 20:25:39'),
(3, 1, 1, 'Final test message to check chat UI.', '2025-07-05 20:25:39'),
(4, 1, 1, 'Glad it workd', '2025-07-05 20:26:09');

-- --------------------------------------------------------

--
-- Table structure for table `studygroups`
--

CREATE TABLE `studygroups` (
  `groupID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `ImageURL` varchar(255) DEFAULT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `studygroups`
--

INSERT INTO `studygroups` (`groupID`, `name`, `category`, `description`, `ImageURL`, `createdAt`) VALUES
(1, 'Data Structures and Algorithms', 'Computer Science', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\r\n', 'uploads/dsa.jpg', '2025-07-04 09:03:32'),
(2, 'Business Management', 'Business', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.\r\n', 'uploads/68679be9d0c8c_business-management.png', '2025-07-04 09:16:25');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `UserID` int(11) NOT NULL,
  `StudentID` varchar(20) NOT NULL,
  `Name` varchar(100) NOT NULL,
  `Course` varchar(100) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `PasswordHash` varchar(255) NOT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`UserID`, `StudentID`, `Name`, `Course`, `Email`, `PasswordHash`, `CreatedAt`) VALUES
(2, '101010', 'Lamcura', 'BCOM', 'lamcu@email', '$2y$10$C8yxaJG3haencQzU1YuOJuALVjHYq8iXbYCBacn5NJH56CD74ysdi', '2025-07-04 11:14:45'),
(1, '152531', 'Maxwell Kimani', 'BICS', 'maxwell@gmail.com', '$2y$10$5hCFrW4vLcsb8x5Z5NCFsu8XqMPPhfKIFHEtjBhVw6lMX5aSkhlvu', '2025-07-04 07:50:27');

-- --------------------------------------------------------

--
-- Table structure for table `user_exam_targets`
--

CREATE TABLE `user_exam_targets` (
  `UserID` varchar(20) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `Score` float NOT NULL,
  `OutOf` float NOT NULL,
  `updatedAt` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_exam_targets`
--

INSERT INTO `user_exam_targets` (`UserID`, `GroupID`, `Score`, `OutOf`, `updatedAt`) VALUES
('152531', 1, 40, 60, '2025-07-04 10:54:34'),
('152531', 2, 53, 70, '2025-07-04 10:55:23');

-- --------------------------------------------------------

--
-- Table structure for table `user_targets`
--

CREATE TABLE `user_targets` (
  `TargetID` int(11) NOT NULL,
  `UserID` varchar(20) NOT NULL,
  `GroupID` int(11) NOT NULL,
  `CategoryName` varchar(100) NOT NULL,
  `Score` float NOT NULL,
  `OutOf` float NOT NULL,
  `createdAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_targets`
--

INSERT INTO `user_targets` (`TargetID`, `UserID`, `GroupID`, `CategoryName`, `Score`, `OutOf`, `createdAt`) VALUES
(21, '152531', 1, 'CAT 1', 10, 10, '2025-07-04 10:54:34'),
(22, '152531', 1, 'CAT 2', 9, 10, '2025-07-04 10:54:34'),
(23, '152531', 1, 'CAT 3', 6, 10, '2025-07-04 10:54:34'),
(24, '152531', 1, 'CAT 4', 7, 10, '2025-07-04 10:54:34'),
(25, '152531', 1, 'CAT 5', 0, 0, '2025-07-04 10:54:34'),
(26, '152531', 2, 'CAT 1', 3, 5, '2025-07-04 10:55:23'),
(27, '152531', 2, 'CAT 2', 8, 10, '2025-07-04 10:55:23'),
(28, '152531', 2, 'CAT 3', 5, 8, '2025-07-04 10:55:23'),
(29, '152531', 2, 'CAT 4', 6, 7, '2025-07-04 10:55:23'),
(30, '152531', 2, 'CAT 5', 0, 0, '2025-07-04 10:55:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`AdminID`);

--
-- Indexes for table `groupmemberships`
--
ALTER TABLE `groupmemberships`
  ADD PRIMARY KEY (`UserID`,`GroupID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Indexes for table `groupmessages`
--
ALTER TABLE `groupmessages`
  ADD PRIMARY KEY (`MessageID`),
  ADD KEY `GroupID` (`GroupID`),
  ADD KEY `UserID` (`UserID`);

--
-- Indexes for table `studygroups`
--
ALTER TABLE `studygroups`
  ADD PRIMARY KEY (`groupID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`StudentID`),
  ADD UNIQUE KEY `Email` (`Email`),
  ADD UNIQUE KEY `UserID` (`UserID`);

--
-- Indexes for table `user_exam_targets`
--
ALTER TABLE `user_exam_targets`
  ADD PRIMARY KEY (`UserID`,`GroupID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- Indexes for table `user_targets`
--
ALTER TABLE `user_targets`
  ADD PRIMARY KEY (`TargetID`),
  ADD KEY `UserID` (`UserID`),
  ADD KEY `GroupID` (`GroupID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `groupmessages`
--
ALTER TABLE `groupmessages`
  MODIFY `MessageID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `studygroups`
--
ALTER TABLE `studygroups`
  MODIFY `groupID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `UserID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_targets`
--
ALTER TABLE `user_targets`
  MODIFY `TargetID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `groupmemberships`
--
ALTER TABLE `groupmemberships`
  ADD CONSTRAINT `groupmemberships_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `groupmemberships_ibfk_2` FOREIGN KEY (`GroupID`) REFERENCES `studygroups` (`groupID`) ON DELETE CASCADE;

--
-- Constraints for table `groupmessages`
--
ALTER TABLE `groupmessages`
  ADD CONSTRAINT `groupmessages_ibfk_1` FOREIGN KEY (`GroupID`) REFERENCES `studygroups` (`groupID`),
  ADD CONSTRAINT `groupmessages_ibfk_2` FOREIGN KEY (`UserID`) REFERENCES `users` (`UserID`);

--
-- Constraints for table `user_exam_targets`
--
ALTER TABLE `user_exam_targets`
  ADD CONSTRAINT `user_exam_targets_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_exam_targets_ibfk_2` FOREIGN KEY (`GroupID`) REFERENCES `studygroups` (`groupID`) ON DELETE CASCADE;

--
-- Constraints for table `user_targets`
--
ALTER TABLE `user_targets`
  ADD CONSTRAINT `user_targets_ibfk_1` FOREIGN KEY (`UserID`) REFERENCES `users` (`StudentID`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_targets_ibfk_2` FOREIGN KEY (`GroupID`) REFERENCES `studygroups` (`groupID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
