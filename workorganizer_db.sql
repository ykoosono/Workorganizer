-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 09, 2025 at 06:13 PM
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
-- Database: `workorganizer_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `calendars`
--

CREATE TABLE `calendars` (
  `id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `month` varchar(11) NOT NULL,
  `year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `title`, `description`, `month`, `year`) VALUES
(1, 'Test Calend', '', 'March', '2025'),
(2, 'Test Calendar', '', 'February', '2025'),
(3, 'Test Calendar', '', 'February', '2025'),
(4, 'Test Calendar 2', '', 'April', '2025'),
(5, 'Test Calendar 3', 'Test Calendar', 'April', '2025'),
(6, 'Test Calendar 4', 'Test Calendar', 'December', '2020'),
(7, 'Test Calendar 5', 'Test Calendar', 'April', '2025'),
(8, 'Test Calendar 6', 'Test Calendar', 'March', '2025'),
(9, 'Test Calendar 7', 'Test Calendar', 'December', '2020'),
(10, 'Test Calendar 8', 'Test Calendar', 'March', '2025'),
(11, 'Test Calendar 8', 'Test Calendar', 'March', '2020'),
(12, 'Test Calendar 9', 'Test Calendar', 'March', '2025'),
(13, 'Test Calendar 8', 'Test Calendar', 'March', '2020'),
(14, 'Test Calendar 8', 'Test Calendar', 'March', '2025'),
(15, 'Test Calendar 7', 'Test Calendar', 'December', '2020'),
(16, 'Test Calendar 6', 'Test Calendar', 'March', '2025'),
(17, 'Test Calendar 6', 'Test Calendar', 'March', '2025'),
(18, 'Test Calendar 9', 'Test Calendar', 'March', '2025'),
(19, 'Test Calendar 1', 'Test Calendar', 'March', '2025'),
(20, 'Test Calendar 10', 'Test Calendar', 'December', '2020'),
(21, 'Test Calendar 11', 'Test Calendar', 'December', '2025'),
(22, 'Test Calendar 12', 'Test Calendar', 'March', '2025'),
(23, 'Test Calendar 13', 'Test Calendar', 'April', '2020'),
(24, 'Test Calendar 21', 'Test Calendar', 'April', '2020'),
(25, 'Test Calendar 22', 'Test Calendar', 'December', '2025'),
(26, 'Test Calendar 24', 'Test Calendar Update', 'July', '2024');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `event_id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `details` text NOT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`event_id`, `calendar_id`, `title`, `date`, `details`, `is_complete`) VALUES
(1, 1, 'Kickoff', '2025-05-06', 'Initial meeting with stakeholders.', 0),
(2, 1, 'Design Review', '2025-05-10', 'Present and review wireframes.', 1),
(3, 1, 'Final Presentation', '2025-06-01', 'Deliver final demo and report.', 1),
(4, 2, 'Campaign Launch', '2025-05-15', 'Launch summer campaign.', 1),
(5, 2, 'Email Blast', '2025-05-20', 'Send marketing emails to subscribers.', 1),
(6, 3, 'Doctor Appointment', '2025-05-02', 'Routine checkup at 10:00 AM.', 1),
(7, 3, 'Gym Session', '2025-05-03', 'Leg day workout.', 1),
(8, 1, 'dd', '2025-05-07', 'ghfgfg', 0),
(9, 1, 'jkgjkgkj', '2025-05-13', 'hkjh', 1),
(0, 11, 'dfdsG', '2025-05-14', 'dsgdgvvv', 0),
(0, 11, 'dfdsG', '2025-05-14', 'dsgdgvvv', 0),
(0, 11, 'dfdsG', '2025-05-14', 'dsgdgvvv', 0),
(0, 11, 'dddsdgs', '2025-05-04', 'sdfdgsdgg', 0),
(0, 11, 'hhhhh', '2025-05-05', 'hjhfghjhghghj', 0),
(0, 11, 'hhhhh', '2025-05-05', 'hjhfghjhghghj', 0),
(0, 11, 'hhhhh', '2025-05-05', 'hjhfghjhghghj', 0),
(0, 2, 'hjhjhjh', '2025-05-16', 'kjhkjghjkhjhk', 0),
(0, 2, 'adaf', '2025-05-13', 'khjkgjk', 0),
(0, 2, 'jhhgh', '2025-05-06', 'vhnvhvhvm', 0),
(0, 11, 'nnn', '2025-05-14', 'dsgdgvvv', 0),
(0, 11, 'dfdsG', '2025-05-14', 'dsgdgvvv', 0),
(0, 1, 'jgjkgkjkb', '2025-05-07', ',mn,n,mnm', 0),
(0, 1, 'bbbbbbbb', '2025-05-16', 'bbbbbbbbbbbbbbbbbbbbbbbbbbbbb', 0);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `password_resets`
--

INSERT INTO `password_resets` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(1, 'yawkoosono@yahoo.com', 'ee563e877b9bc7a08ec82e896ff9a2b7e4f717efee391a669bd2690e268cddc0', '2025-05-05 03:04:22', '2025-05-05 04:04:22'),
(2, 'yawkoosono@yahoo.com', '569d66c6739f11da1822f0d71849de9fd4db93a84a5ff449aa70cc9bbec01b35', '2025-05-05 03:04:37', '2025-05-05 04:04:37'),
(3, 'yawkoosono@yahoo.com', '33590c226760508933475c46d17ad882e01193e13d4588e34784dbcee211a43b', '2025-05-05 03:11:28', '2025-05-05 04:11:28');


-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` int(11) NOT NULL,
  `permission_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `permissions`
--

INSERT INTO `permissions` (`id`, `permission_name`) VALUES
(1, 'Add Member'),
(7, 'Add Task'),
(3, 'Assign Task'),
(8, 'Delete Task'),
(4, 'Edit Task'),
(2, 'Remove Member'),
(5, 'Toggle Control'),
(6, 'View only');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'team lead'),
(2, 'team member'),
(3, 'view only');

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

-- --------------------------------------------------------

--
-- Table structure for table `role_permissions`
--

CREATE TABLE `role_permissions` (
  `id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role_permissions`
--

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(7, 2, 1),
(8, 1, 5),
(9, 2, 5),
(10, 3, 6),
(11, 1, 7),
(12, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_task` datetime NOT NULL,
  `end_task` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `user_id`, `event_id`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 0),
(4, 1, 0),
(5, 1, 0),
(6, 2, 0),
(7, 2, 0),
(8, 2, 0),
(9, 1, 0),
(10, 1, 0),
(11, 1, 0),
(12, 2, 1),
(13, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

-- --------------------------------------------------------

--
-- Table structure for table `tasks`
--

CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `start_task` datetime NOT NULL,
  `end_task` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task_assignments`
--

CREATE TABLE `task_assignments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `task_assignments`
--

INSERT INTO `task_assignments` (`id`, `user_id`, `event_id`) VALUES
(1, 1, 1),
(2, 1, 1),
(3, 1, 0),
(4, 1, 0),
(5, 1, 0),
(6, 2, 0),
(7, 2, 0),
(8, 2, 0),
(9, 1, 0),
(10, 1, 0),
(11, 1, 0),
(12, 2, 1),
(13, 1, 8);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'clacombe', 'clacombe@fitchburg.edu', 'clacombe', '0000-00-00 00:00:00'),
(14, 'calacombe', 'calacombe@fitchburg.edu', 'clacombe', '2025-03-26 02:10:36'),
(16, 'mahadev', 'abc@123.com', 'clacombe', '2025-03-26 15:50:12'),
(18, 'alacombe', 'clacombe@fitchburgstate.edu', 'clacombe', '2025-04-07 15:17:53');

-- --------------------------------------------------------
--
-- Table structure for table `users_calendars`
--

CREATE TABLE `users_calendars` (
  `id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `role_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users_calendars`
--

INSERT INTO `users_calendars` (`id`, `calendar_id`, `user_id`, `role_id`) VALUES
(1, 0, 1, 1),
(2, 0, 1, 1),
(3, 0, 1, 1),
(4, 0, 1, 1),
(5, 0, 1, 1),
(6, 0, 1, 1),
(7, 0, 1, 1),
(8, 0, 1, 1),
(9, 1, 2, 2),
(10, 1, 3, 2),
(11, 1, 1, 3),
(12, 11, 1, 1),
(13, 11, 2, 3),
(14, 11, 3, 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `calendars`
--
ALTER TABLE `calendars`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`event_id`),
  ADD KEY `calendar_id` (`calendar_id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`);


--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permission_name` (`permission_name`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `permission_id` (`permission_id`);

--
-- Indexes for table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `task_assignments`
--
ALTER TABLE `task_assignments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `event_id` (`event_id`);


--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `users_calendars`
--
ALTER TABLE `users_calendars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `calendar_id` (`calendar_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `role_id` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `password_resets`
--
ALTER TABLE `password_resets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `role_permissions`
--
ALTER TABLE `role_permissions`
   MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_ibfk_1` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`);

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `users_calendars`
--
ALTER TABLE `users_calendars`
  ADD CONSTRAINT `users_calendars_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `users_calendars_ibfk_2` FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`),
  ADD CONSTRAINT `users_calendars_ibfk_3` FOREIGN KEY (`role_id`) REFERENCES `role_permissions` (`id`);
--
-- AUTO_INCREMENT for table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task_assignments`
--
ALTER TABLE `task_assignments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `users_calendars`
--
ALTER TABLE `users_calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
