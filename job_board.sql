-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Feb 09, 2025 at 02:27 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_board`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resume` varchar(255) DEFAULT NULL,
  `status` enum('pending','reviewed','accepted','rejected') DEFAULT 'pending',
  `applied_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `job_id`, `user_id`, `resume`, `status`, `applied_at`) VALUES
(8, 10, 1, 'uploads/resumes/resume_1_1739105835.pdf', 'pending', '2025-02-09 12:57:15'),
(9, 8, 1, 'uploads/resumes/resume_1_1739106657.pdf', 'pending', '2025-02-09 13:10:57'),
(10, 6, 1, 'uploads/resumes/resume_1_1739107279.pdf', 'pending', '2025-02-09 13:21:19');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `salary` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `employer_id`, `title`, `description`, `category`, `location`, `salary`, `created_at`) VALUES
(6, 6, 'sfsdf', 'sdfdsf', 'sdfdsf', 'dsfdsf', 123213.00, '2025-02-09 12:00:43'),
(8, 2, 'Software Engineer', 'Develop and maintain web applications. Collaborate with cross-functional teams to design and implement features.', ' IT/Software', 'Remote', 6000.00, '2025-02-09 12:53:31'),
(9, 2, ' Marketing Specialist', 'Plan and execute digital marketing campaigns, analyze trends, and manage social media accounts.', 'Marketing', 'Makati, Philippines', 45000.00, '2025-02-09 12:56:10'),
(10, 2, 'Graphic Designer', 'Create visual concepts, design layouts, and develop branding materials for digital and print media.', 'Design', 'Quezon City, Philippines', 123123.00, '2025-02-09 12:56:44');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('employer','job_seeker') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'developer', 'dev@gmail.com', '$2y$10$C1Ie2jtwEo5L2xHlSQGNi.dODWCbPesbdiYHJG.MJCxrM.MqTl7aC', 'job_seeker', '2025-02-09 09:41:14'),
(2, 'jimwelmanguiat', 'hehe@gmail.com', '$2y$10$3DESZb.QqLHEOi4PQjepzun3pBXnyvb/VHiWhDINnEAka5NXCgANu', 'employer', '2025-02-09 09:47:47'),
(3, 'Jimwel Manguiat', 'jimwel@gmail.com', '$2y$10$XrAxFtt3g3hE3/irIlUyeeDwYeVJUaTekUCMuHrRzR6/LQqa8HsdW', 'job_seeker', '2025-02-09 10:38:38'),
(4, 'gelai dela cruz', 'gelai@gmail.com', '$2y$10$SoVdxh31bN.kNGTM8rB1AuK5zPEJSG1Oq.mcTcBbwU.to9I0ZJSmG', 'employer', '2025-02-09 10:39:21'),
(5, 'pogi', 'pogi@gmail.com', '$2y$10$4qwtKytHV12TRFnSHUBD0uP8DL9u7bUWCgJ.ouqFCzqFK4XSQ937.', 'job_seeker', '2025-02-09 11:46:42'),
(6, 'mtt', 'mtt@gmail.com', '$2y$10$7TdNPKTgQyh4DB4UJjEmFulGF4HCb4MIUa55/c/yJuFHMclySShWi', 'employer', '2025-02-09 12:00:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employer_id` (`employer_id`);

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
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `jobs` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jobs`
--
ALTER TABLE `jobs`
  ADD CONSTRAINT `jobs_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
