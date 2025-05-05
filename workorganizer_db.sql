-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 05, 2025 at 03:55 PM
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
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `calendars`
--

INSERT INTO `calendars` (`id`, `title`, `description`, `user_id`, `created_at`) VALUES
(1, 'Project Alpha', 'Calendar for tracking Project Alpha tasks.ddd', 1, '2025-05-04 17:46:30'),
(2, 'Marketing Campaign', 'Campaign planning and deadlines.', 1, '2025-05-04 17:46:30'),
(3, 'Dev Sprint 1', 'Development sprint 1 timeline.', 1, '2025-05-04 17:46:30'),
(4, 'HR Calendar', 'Internal HR-related events.', 1, '2025-05-04 17:46:30'),
(5, 'QA Testing', 'QA schedules and deadlines.', 1, '2025-05-04 17:46:30'),
(6, 'Product Launch', 'Roadmap to product launch.', 1, '2025-05-04 17:46:30'),
(7, 'Team Meetings', 'Schedule for weekly team meetings.', 1, '2025-05-04 17:46:30'),
(8, 'Client Work', 'Tasks related to client deliverables.', 1, '2025-05-04 17:46:30'),
(9, 'Company Events', 'Company-wide event planning.', 1, '2025-05-04 17:46:30'),
(10, 'Freelance Schedule', 'Freelance client calendar.', 1, '2025-05-04 17:46:30'),
(11, 'dsgfg', 'fdsggda', 1, '2025-05-04 20:51:30');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `calendar_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `details` text DEFAULT NULL,
  `is_complete` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `calendar_id`, `title`, `date`, `details`, `is_complete`) VALUES
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
(1, 'yawkoosono@yahoo.com', 'ee563e877b9bc7a08ec82e896ff9a2b7e4f717efee391a669bd2690e268cddc0', '2025-05-05 03:04:22', '2025-05-05 00:04:22'),
(2, 'yawkoosono@yahoo.com', '569d66c6739f11da1822f0d71849de9fd4db93a84a5ff449aa70cc9bbec01b35', '2025-05-05 03:04:37', '2025-05-05 00:04:37'),
(3, 'yawkoosono@yahoo.com', '33590c226760508933475c46d17ad882e01193e13d4588e34784dbcee211a43b', '2025-05-05 03:11:28', '2025-05-05 00:11:28');

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

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'yaw', 'yawkoosono@yahoo.com', '$2y$10$cLXSYBkH9EGlCxPS5mMiJe3zthsyZu9L40j/Q/9ByRLs1T811ul3q', '2025-05-02 18:51:27'),
(2, 'casey', 'casey@yahoo.com', '$2y$10$9eKY6MfPHLgttscxYa.1r.vy74WJymMI3rOq.WI2IdCtbPtBwwKw6', '2025-05-04 18:13:38'),
(3, 'elias', 'elias@yahoo.com', '$2y$10$0u42FLTDRD07CE1vYMNIIeiMckYdbP46eqGErggZWQZCGay80Mw3m', '2025-05-04 18:14:32');

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `email` (`email`),
  ADD KEY `token` (`token`);

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
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users_calendars`
--
ALTER TABLE `users_calendars`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `calendars`
--
ALTER TABLE `calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

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
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users_calendars`
--
ALTER TABLE `users_calendars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `role_permissions`
--
ALTER TABLE `role_permissions`
  ADD CONSTRAINT `role_permissions_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_permissions_ibfk_2` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
