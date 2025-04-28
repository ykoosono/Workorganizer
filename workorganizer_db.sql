-- Create Database
CREATE DATABASE IF NOT EXISTS workorganizer_db;
USE workorganizer_db;

-- Set defaults
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT;
SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS;
SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION;
SET NAMES utf8mb4;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: calendars
-- --------------------------------------------------------
CREATE TABLE `calendars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `description` TEXT,
  `user_id` INT(11) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users`(`user_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: calendar (for time blocks or events)
-- --------------------------------------------------------
CREATE TABLE `calendar` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `start_date` DATETIME NOT NULL,
  `end_date` DATETIME NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- Table: permissions
-- --------------------------------------------------------
CREATE TABLE `permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `permission_name` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert permissions
INSERT INTO `permissions` (`id`, `permission_name`) VALUES
(1, 'View Project'),
(2, 'Edit Project'),
(3, 'Add Team Members'),
(4, 'Assign Tasks'),
(5, 'View Only Access');

-- --------------------------------------------------------
-- Table: roles
-- --------------------------------------------------------
CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert roles
INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Lead Member'),
(2, 'Team Member'),
(3, 'View-Only Member');

-- --------------------------------------------------------
-- Table: role_permissions
-- --------------------------------------------------------
CREATE TABLE `role_permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) NOT NULL,
  `permission_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Insert role-permission mappings
INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 3, 1);

-- --------------------------------------------------------
-- Table: users_calendars
-- --------------------------------------------------------
CREATE TABLE `users_calendars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`calendar_id`) REFERENCES `calendar` (`id`),
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  FOREIGN KEY (`role_id`) REFERENCES `role_permissions` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------
-- AUTO_INCREMENT Definitions
-- --------------------------------------------------------
ALTER TABLE `calendar`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `permissions`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

ALTER TABLE `roles`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

ALTER TABLE `role_permissions`
  MODIFY `id` INT(3) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

ALTER TABLE `users`
  MODIFY `user_id` INT(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users_calendars`
  MODIFY `id` INT(3) NOT NULL AUTO_INCREMENT;

ALTER TABLE `calendars`
  MODIFY `id` INT(11) NOT NULL AUTO_INCREMENT;

-- --------------------------------------------------------
-- End Transaction
-- --------------------------------------------------------
COMMIT;

-- Restore settings
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
 /*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
 /*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
