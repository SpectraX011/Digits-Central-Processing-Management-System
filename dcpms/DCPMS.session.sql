-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 16, 2025 at 12:23 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';
START TRANSACTION;
SET time_zone = '+00:00';


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dcpms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `eventID` int NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text,
  `startDate` date NOT NULL,
  `endDate` date NOT NULL,
  `isRequired` tinyint DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`eventID`, `title`, `description`, `startDate`, `endDate`, `isRequired`) VALUES
(1, 'DIGITS Orientation', 'Intro to DIGITS and upcoming projects.', '2025-11-20', '2025-11-20', 1),
(3, 'Wellness Workshop', 'Learn stress management and mindfulness techniques', '2025-12-05', '2025-12-05', 0),
(4, 'Campus Clean-Up Drive', 'Volunteer to beautify the campus grounds', '2025-12-10', '2025-12-10', 0),
(5, 'Fitness Challenge', 'Team-based physical fitness competition', '2025-12-15', '2025-12-15', 1),
(6, 'Mental Health Talk', 'Guest speaker on student mental health and resilience', '2025-12-20', '2025-12-20', 0),
(8, 'Wellness Workshop', 'Learn stress management and mindfulness techniques\r\n', '0000-00-00', '0000-00-00', 0),
(9, 'Machine Learning ', 'required to attend', '2025-12-09', '2025-12-09', 0),
(10, 'Christmas party', 'optional ', '2025-12-17', '2025-12-17', 0);

-- --------------------------------------------------------

--
-- Table structure for table `registrations`
--

CREATE TABLE `registrations` (
  `registrationID` int NOT NULL,
  `studentID` varchar(20),
  `eventID` int,
  `registeredOn` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `registrations`
--

INSERT INTO `registrations` (`registrationID`, `studentID`, `eventID`, `registeredOn`) VALUES
(1, '2400704', 1, '2025-11-17 06:36:10'),
(2, '2400704', 8, '2025-12-02 23:17:58'),
(3, '2400704', 4, '2025-12-02 23:24:15'),
(4, '2400704', 9, '2025-12-08 21:53:59'),
(5, '2400704', 5, '2025-12-12 15:01:46');

-- --------------------------------------------------------

--
-- Table structure for table `sanctions`
--

CREATE TABLE `sanctions` (
  `sanctionID` int NOT NULL,
  `studentID` varchar(20),
  `eventID` int,
  `reason` text,
  `status` enum('pending','resolved') DEFAULT 'pending',
  `penaltyAmount` decimal(10,2),
  `resolvedOn` date
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sanctions`
--

INSERT INTO `sanctions` (`sanctionID`, `studentID`, `eventID`, `reason`, `status`, `penaltyAmount`, `resolvedOn`) VALUES
(1, '2400704', 1, 'did not attend', 'pending', 50.00, '2025-11-17'),
(2, '2400704', 5, 'did not attend', 'pending', 50.00, '2025-12-03'),
(3, '2400704', 5, 'did not attend ', 'resolved', 50.00, '2025-12-04'),
(4, '2400704', 6, 'did not attend', 'pending', 100.00, '2025-12-09');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `studentID` varchar(20) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `fullName` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('student','digit_officer') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`studentID`, `password_hash`, `fullName`, `email`, `role`) VALUES
('2400703', '$2y$10$4dFKRMU0kDNqXDhOxOViWulLx9efh.BgcWCOV7JFAgTsQXWhs8oqC', 'John Alberto Digit_PIO ', 'johnalberto@digits.com', 'digit_officer'),
('2400704', '$2y$10$W12MALd9.4kKlHAVcZ/5o.qF4LugKJUt.jm.jCtUQzA8l6cPu1BhO', 'Daniel Prudenciado', 'danielprudenciado@student.com', 'student');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`eventID`);

--
-- Indexes for table `registrations`
--
ALTER TABLE `registrations`
  ADD PRIMARY KEY (`registrationID`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `eventID` (`eventID`);

--
-- Indexes for table `sanctions`
--
ALTER TABLE `sanctions`
  ADD PRIMARY KEY (`sanctionID`),
  ADD KEY `studentID` (`studentID`),
  ADD KEY `eventID` (`eventID`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`studentID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `eventID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `registrations`
--
ALTER TABLE `registrations`
  MODIFY `registrationID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `sanctions`
--
ALTER TABLE `sanctions`
  MODIFY `sanctionID` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `registrations`
--
ALTER TABLE `registrations`
  ADD CONSTRAINT `registrations_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`),
  ADD CONSTRAINT `registrations_ibfk_2` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`);

--
-- Constraints for table `sanctions`
--
ALTER TABLE `sanctions`
  ADD CONSTRAINT `sanctions_ibfk_1` FOREIGN KEY (`studentID`) REFERENCES `students` (`studentID`),
  ADD CONSTRAINT `sanctions_ibfk_2` FOREIGN KEY (`eventID`) REFERENCES `events` (`eventID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


DELETE FROM events 
WHERE eventID = 10;

DELETE FROM registrations

 WHERE registrationID = 5;

DELETE FROM sanctions 
WHERE sanctionID = 4;

DELETE FROM students 
WHERE studentID = '2400705';



UPDATE events
SET title = 'Hackathon Event 2026 - Updated',
    description = 'Updated description: 2-day coding event',
    endDate = '2026-01-17'
WHERE eventID = 11;


UPDATE registrations
SET eventID = 5
WHERE registrationID = 6;


UPDATE sanctions
SET status = 'resolved',
    resolvedOn = '2026-01-20'
WHERE sanctionID = 5;


UPDATE students
SET email = 'maria.santos@digits.com',
    role = 'digit_officer'
WHERE studentID = '2400706';