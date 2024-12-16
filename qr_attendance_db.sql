-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 13, 2024 at 07:33 AM
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
-- Database: `qr_attendance_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_attendance`
--

CREATE TABLE `tbl_attendance` (
  `tbl_attendance_id` int(11) NOT NULL,
  `tbl_student_id` int(11) NOT NULL,
  `time_in` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `attendance_date` date DEFAULT NULL,
  `time_out` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_attendance`
--

INSERT INTO `tbl_attendance` (`tbl_attendance_id`, `tbl_student_id`, `time_in`, `attendance_date`, `time_out`) VALUES
(158, 119, '2024-11-03 12:17:04', NULL, NULL),
(159, 118, '2024-11-03 12:17:08', NULL, NULL),
(160, 117, '2024-11-03 12:17:11', NULL, NULL),
(161, 116, '2024-11-03 12:17:13', NULL, NULL),
(163, 119, '2024-11-08 02:22:00', NULL, NULL),
(164, 118, '2024-11-08 03:35:08', NULL, NULL),
(165, 117, '2024-11-08 03:35:25', NULL, NULL),
(166, 116, '2024-11-08 03:35:27', NULL, NULL),
(167, 611, '2024-11-08 05:35:48', NULL, NULL),
(168, 612, '2024-11-08 05:39:27', NULL, NULL),
(169, 619, '2024-11-08 06:31:02', NULL, NULL),
(170, 618, '2024-11-08 06:31:11', NULL, NULL),
(171, 614, '2024-11-08 06:31:17', NULL, NULL),
(172, 620, '2024-11-08 07:43:09', NULL, NULL),
(173, 623, '2024-11-08 07:52:05', NULL, NULL),
(174, 624, '2024-11-08 08:22:02', NULL, NULL),
(175, 627, '2024-11-08 08:27:07', NULL, NULL),
(176, 626, '2024-11-08 08:31:03', NULL, NULL),
(177, 625, '2024-11-08 08:32:44', NULL, NULL),
(178, 119, '2024-11-10 04:45:46', NULL, NULL),
(179, 630, '2024-11-10 04:49:21', NULL, NULL),
(180, 631, '2024-11-10 04:51:51', NULL, NULL),
(181, 632, '2024-11-11 05:28:57', NULL, NULL),
(182, 634, '2024-11-11 05:35:56', NULL, NULL),
(183, 649, '2024-11-11 13:47:45', NULL, NULL),
(184, 650, '2024-11-12 01:53:23', NULL, NULL),
(185, 650, '2024-11-21 04:00:11', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_holidays`
--

CREATE TABLE `tbl_holidays` (
  `holiday_id` int(11) NOT NULL,
  `holiday_date` date NOT NULL,
  `holiday_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_by` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_holidays`
--

INSERT INTO `tbl_holidays` (`holiday_id`, `holiday_date`, `holiday_name`, `description`, `created_by`, `created_at`) VALUES
(4, '2024-11-13', 'add holiday', '', NULL, '2024-11-10 04:42:52'),
(6, '2024-11-21', 'EX HOLIDAY', '', NULL, '2024-11-11 13:30:29'),
(7, '2024-11-15', 'Sample', '', NULL, '2024-11-13 06:24:57'),
(8, '2024-11-14', 'GGG', '', NULL, '2024-11-13 06:26:13');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_student`
--

CREATE TABLE `tbl_student` (
  `tbl_student_id` int(11) NOT NULL,
  `student_name` varchar(255) NOT NULL,
  `generated_code` varchar(255) NOT NULL,
  `attendance_date` date DEFAULT NULL,
  `course` varchar(255) NOT NULL,
  `section` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbl_student`
--

INSERT INTO `tbl_student` (`tbl_student_id`, `student_name`, `generated_code`, `attendance_date`, `course`, `section`) VALUES
(649, 'Randhel Alibay', 'Randhel Alibay - BSIT - 4A', NULL, 'BSIT', '4A'),
(650, 'Daniel Ivan Mel R. Base', 'Daniel Ivan Mel R. Base - BSIT - 4A', NULL, 'BSIT', '4A'),
(651, 'Yamee Sheen Eula T. Tavita', 'Yamee Sheen Eula T. Tavita - BSIT - 4A', NULL, 'BSIT', '4A'),
(652, 'Lee Andrei T. Enriquez', 'Lee Andrei T. Enriquez - BSIT - 4A', NULL, 'BSIT', '4A'),
(653, 'Joshua Mark Doza', 'Joshua Mark Doza - BSIT - 4A', NULL, 'BSIT', '4A'),
(656, 'Sample', '', NULL, 'BSCS', '3A'),
(657, 'Sample 3', 'Sample 2 - BSCS - 3B', NULL, 'BSCS', '3B'),
(662, 'Sample 10', 'Sample 10 - BSCS - 3B', NULL, 'BSCS', '3B');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('attendance','admin','','') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'Testing', 'marvinjaytead@gmail.com', '$2y$10$SKRhlQkOwgLkb9NzCooxt.QaYkfLEyXXIGcP3VAcwr2XRf0jAED9G', 'attendance'),
(2, 'Yamee', 'Yamee123@gmail.com', '$2y$10$FDu4y6FW7huaBq82gp138O9HOPCCIs7/91IGHwa8A6XleY8mW9Sfu', 'attendance'),
(3, 'Yamee', 'Yamee@gmail.com', '$2y$10$zIMoxAIfD7uRlB1TJAhshOxWZTqtKgO.VQQep1yLv/F.Eyg6Ip1ZW', 'attendance'),
(4, 'keme keme', 'kemekeme@gmail.com', '$2y$10$J5tUTRbx2KoNMj0NZCMyWOZv64dygycifTNjFJlfZy0UUZa7bHpRy', 'attendance'),
(5, 'Yamee Tavita', 'yameesheen@gmail.com', '$2y$10$b2ZgTZuZEM5xwtcw7UwoX.CoSPOu.RuDV/tGErClXeEQgOUHpHjJ2', 'attendance'),
(6, 'Ivan Base', 'ivan@gmail.com', '$2y$10$AGv/J27fz6L1Txi3welxxu8748B7YScZlcN5d8uJZr25KfGDUGXHy', 'attendance'),
(7, 'heeee', 'heeee@gmail.com', '$2y$10$ASLPJ3Dp9tR5Ga/Q6O9Qo.T7x3FzpbFgmePhHe3LPdRSVqTOycndK', 'attendance'),
(8, 'TRY ACCOUNT', 'Tryaccount@gmail.com', '$2y$10$ZzgmhFWazYMEnmP11XCKBeoxvLSFsmElMfCkedxSdGUbN5l1ixjtW', 'attendance'),
(9, 'ivanbase', 'ivanbase@gmail.com', '$2y$10$HHAwFldNwTRi/RZzcaJpreFU1ZYzx9BYHLtxedycyj6MwdT0VzpTu', 'attendance'),
(12, 'admin', 'admin@gmail.com', '$2y$10$vrE6mATgtc6EL/IzI8auweJ6tdy7.kiHyViPgx7t5xmiNikj9syxi', 'admin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  ADD PRIMARY KEY (`tbl_attendance_id`);

--
-- Indexes for table `tbl_holidays`
--
ALTER TABLE `tbl_holidays`
  ADD PRIMARY KEY (`holiday_id`),
  ADD KEY `idx_holiday_date` (`holiday_date`);

--
-- Indexes for table `tbl_student`
--
ALTER TABLE `tbl_student`
  ADD PRIMARY KEY (`tbl_student_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbl_attendance`
--
ALTER TABLE `tbl_attendance`
  MODIFY `tbl_attendance_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=186;

--
-- AUTO_INCREMENT for table `tbl_holidays`
--
ALTER TABLE `tbl_holidays`
  MODIFY `holiday_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tbl_student`
--
ALTER TABLE `tbl_student`
  MODIFY `tbl_student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=663;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
