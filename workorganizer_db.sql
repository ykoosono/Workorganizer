CREATE DATABASE IF NOT EXISTS workorganizer_db;
USE workorganizer_db;


CREATE TABLE `permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `permission_name` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);

INSERT INTO `permissions` (`id`, `permission_name`) VALUES
(1, 'View Project'),
(2, 'Edit Project'),
(3, 'Add Team Members'),
(4, 'Assign Tasks'),
(5, 'View Only Access');

CREATE TABLE `roles` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_name` VARCHAR(50) NOT NULL UNIQUE,
  PRIMARY KEY (`id`)
);

INSERT INTO `roles` (`id`, `role_name`) VALUES
(1, 'Lead Member'),
(2, 'Team Member'),
(3, 'View-Only Member');

CREATE TABLE `role_permissions` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `role_id` INT(11) NOT NULL,
  `permission_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE
);

INSERT INTO `role_permissions` (`id`, `role_id`, `permission_id`) VALUES
(1, 1, 1),
(2, 1, 2),
(3, 1, 3),
(4, 1, 4),
(5, 2, 1),
(6, 2, 2),
(7, 3, 1);

CREATE TABLE `users` (
  `user_id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `created_at` TIMESTAMP NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`user_id`)
);



CREATE TABLE IF NOT EXISTS calendars (
  id INT AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(255) NOT NULL,
  description TEXT,
  user_id INT DEFAULT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE `users_calendars` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `calendar_id` INT(11) NOT NULL,
  `user_id` INT(11) NOT NULL,
  `role_id` INT(11) NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`calendar_id`) REFERENCES `calendars` (`id`) ON DELETE CASCADE,
  FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS events (
  id INT AUTO_INCREMENT PRIMARY KEY,
  calendar_id INT NOT NULL,
  title VARCHAR(255) NOT NULL,
  date DATE NOT NULL,
  details TEXT,
  FOREIGN KEY (calendar_id) REFERENCES calendars(id) ON DELETE CASCADE
);

INSERT INTO calendars (title, description) VALUES
('Team Project Calendar', 'Track milestones and deadlines for our current project.'),
('Marketing Events', 'All key marketing campaign dates and events.'),
('Personal Planner', 'Appointments and tasks for personal goals.');

INSERT INTO events (calendar_id, title, date, details) VALUES
(1, 'Kickoff Meeting', '2025-05-01', 'Initial meeting with stakeholders.'),
(1, 'Design Review', '2025-05-10', 'Present and review wireframes.'),
(1, 'Final Presentation', '2025-06-01', 'Deliver final demo and report.'),
(2, 'Campaign Launch', '2025-05-15', 'Launch summer campaign.'),
(2, 'Email Blast', '2025-05-20', 'Send marketing emails to subscribers.'),
(3, 'Doctor Appointment', '2025-05-02', 'Routine checkup at 10:00 AM.'),
(3, 'Gym Session', '2025-05-03', 'Leg day workout.');

ALTER TABLE calendars
ADD COLUMN title VARCHAR(255) NOT NULL,
ADD COLUMN description TEXT;
