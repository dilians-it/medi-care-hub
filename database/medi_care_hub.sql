-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 15, 2025 at 10:59 PM
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
-- Database: `medi_care_hub`
--

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `appointment_date` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled','rescheduled') DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `reschedule_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `doctor_id`, `patient_id`, `appointment_date`, `status`, `reason`, `notes`, `reschedule_reason`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2024-01-20 10:00:00', 'confirmed', NULL, NULL, NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(2, 2, 2, '2024-01-21 14:30:00', 'pending', NULL, NULL, NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(3, 2, 1, '2025-04-16 05:39:00', 'pending', 'Weak', NULL, NULL, '2025-04-15 20:37:11', '2025-04-15 20:37:11'),
(4, 2, 1, '2025-04-16 05:39:00', 'pending', 'Weak', NULL, NULL, '2025-04-15 20:37:12', '2025-04-15 20:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `chatbot_templates`
--

CREATE TABLE `chatbot_templates` (
  `id` int(11) NOT NULL,
  `question` varchar(255) DEFAULT NULL,
  `answer` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `keywords` text DEFAULT NULL,
  `created_by` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `chatbot_templates`
--

INSERT INTO `chatbot_templates` (`id`, `question`, `answer`, `category`, `keywords`, `created_by`, `created_at`, `updated_at`) VALUES
(1, 'How do I book an appointment?', 'You can book an appointment by logging in and selecting your preferred doctor and time slot.', NULL, NULL, 1, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(2, 'What are your visiting hours?', 'Our general visiting hours are from 9 AM to 8 PM daily.', NULL, NULL, 1, '2025-04-15 20:30:46', '2025-04-15 20:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `chat_messages`
--

CREATE TABLE `chat_messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) DEFAULT NULL,
  `receiver_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `attachment_path` varchar(255) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `specialization` varchar(100) DEFAULT NULL,
  `qualification` text DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `consultation_fee` decimal(10,2) DEFAULT NULL,
  `available_days` varchar(100) DEFAULT NULL,
  `time_slots` text DEFAULT NULL,
  `about` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `user_id`, `hospital_id`, `first_name`, `last_name`, `specialization`, `qualification`, `experience_years`, `consultation_fee`, `available_days`, `time_slots`, `about`) VALUES
(1, 3, 1, 'Tamim', 'Ahasan', 'Cardiology', NULL, 10, NULL, NULL, NULL, NULL),
(2, 4, 1, 'Shaun', 'Mia', 'Specialist', NULL, 8, NULL, NULL, NULL, NULL),
(3, 7, 1, 'Shafin', ' Tamim', 'Neurologist', NULL, 30, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `doctor_schedules`
--

CREATE TABLE `doctor_schedules` (
  `id` int(11) NOT NULL,
  `doctor_id` int(11) DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') DEFAULT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `max_appointments` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feed_posts`
--

CREATE TABLE `feed_posts` (
  `id` int(11) NOT NULL,
  `hospital_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `type` enum('news','event','announcement') DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('draft','published','archived') DEFAULT 'published',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feed_posts`
--

INSERT INTO `feed_posts` (`id`, `hospital_id`, `title`, `description`, `image_path`, `type`, `start_date`, `end_date`, `status`, `created_at`) VALUES
(1, 1, 'New Cardiology Department', 'We are excited to announce our new state-of-the-art cardiology department', NULL, NULL, NULL, NULL, 'published', '2025-04-15 20:30:46'),
(2, 1, 'COVID-19 Vaccination Drive', 'Free vaccination drive this weekend', NULL, NULL, NULL, NULL, 'published', '2025-04-15 20:30:46');

-- --------------------------------------------------------

--
-- Table structure for table `hospitals`
--

CREATE TABLE `hospitals` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `name` varchar(100) NOT NULL,
  `address` text DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `facilities` text DEFAULT NULL,
  `working_hours` text DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `hospitals`
--

INSERT INTO `hospitals` (`id`, `user_id`, `name`, `address`, `phone`, `email`, `website`, `description`, `facilities`, `working_hours`, `emergency_contact`, `latitude`, `longitude`) VALUES
(1, 2, 'City Hospital', '123 Main St, City', '555-0123', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `is_read`, `created_at`) VALUES
(1, 7, 'Account Created', 'Your doctor account has been created. Username: d tamim, Password: bcba9c5c', 'account', 0, '2025-04-15 20:34:07'),
(2, 4, 'New Appointment Request', 'You have a new appointment request', 'appointment', 0, '2025-04-15 20:37:11'),
(3, 4, 'New Appointment Request', 'You have a new appointment request', 'appointment', 0, '2025-04-15 20:37:12');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `first_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) DEFAULT NULL,
  `date_of_birth` date DEFAULT NULL,
  `gender` enum('male','female','other') DEFAULT NULL,
  `blood_group` varchar(5) DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `emergency_contact` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `patients`
--

INSERT INTO `patients` (`id`, `user_id`, `first_name`, `last_name`, `date_of_birth`, `gender`, `blood_group`, `allergies`, `emergency_contact`, `address`) VALUES
(1, 5, NULL, NULL, '1990-05-15', NULL, 'A+', NULL, NULL, NULL),
(2, 6, NULL, NULL, '1985-08-22', NULL, 'O-', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `report_attachments`
--

CREATE TABLE `report_attachments` (
  `id` int(11) NOT NULL,
  `report_id` int(11) DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `file_type` enum('image','pdf','document') DEFAULT NULL,
  `file_size` int(11) DEFAULT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `role` enum('admin','hospital','doctor','patient') NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`, `profile_picture`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin123', 'admin@medicarehub.com', 'admin', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(2, 'cityhospital', 'hospital123', 'city@hospital.com', 'hospital', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(3, 'drmike', 'doctor123', 'mike@doctor.com', 'doctor', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(4, 'drjane', 'doctor123', 'jane@doctor.com', 'doctor', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(5, 'patient1', 'patient123', 'patient1@email.com', 'patient', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(6, 'patient2', 'patient123', 'patient2@email.com', 'patient', NULL, 'active', NULL, '2025-04-15 20:30:46', '2025-04-15 20:30:46'),
(7, 'd tamim', 'bcba9c5c', 'shafin@doctor.com', 'doctor', NULL, 'active', NULL, '2025-04-15 20:34:07', '2025-04-15 20:34:07');

-- --------------------------------------------------------

--
-- Table structure for table `visit_reports`
--

CREATE TABLE `visit_reports` (
  `id` int(11) NOT NULL,
  `appointment_id` int(11) DEFAULT NULL,
  `symptoms` text DEFAULT NULL,
  `diagnosis` text DEFAULT NULL,
  `prescription` text DEFAULT NULL,
  `treatment_plan` text DEFAULT NULL,
  `next_visit_date` date DEFAULT NULL,
  `vital_signs` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`vital_signs`)),
  `lab_results` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`),
  ADD KEY `patient_id` (`patient_id`);

--
-- Indexes for table `chatbot_templates`
--
ALTER TABLE `chatbot_templates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_by` (`created_by`);

--
-- Indexes for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sender_id` (`sender_id`),
  ADD KEY `receiver_id` (`receiver_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `doctor_id` (`doctor_id`);

--
-- Indexes for table `feed_posts`
--
ALTER TABLE `feed_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `hospital_id` (`hospital_id`);

--
-- Indexes for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `report_attachments`
--
ALTER TABLE `report_attachments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `report_id` (`report_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `visit_reports`
--
ALTER TABLE `visit_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `appointment_id` (`appointment_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `chatbot_templates`
--
ALTER TABLE `chatbot_templates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `chat_messages`
--
ALTER TABLE `chat_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feed_posts`
--
ALTER TABLE `feed_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `hospitals`
--
ALTER TABLE `hospitals`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `report_attachments`
--
ALTER TABLE `report_attachments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `visit_reports`
--
ALTER TABLE `visit_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `appointments`
--
ALTER TABLE `appointments`
  ADD CONSTRAINT `appointments_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `appointments_ibfk_2` FOREIGN KEY (`patient_id`) REFERENCES `patients` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `chatbot_templates`
--
ALTER TABLE `chatbot_templates`
  ADD CONSTRAINT `chatbot_templates_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);

--
-- Constraints for table `chat_messages`
--
ALTER TABLE `chat_messages`
  ADD CONSTRAINT `chat_messages_ibfk_1` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `chat_messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `doctors_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `doctors_ibfk_2` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctor_schedules`
--
ALTER TABLE `doctor_schedules`
  ADD CONSTRAINT `doctor_schedules_ibfk_1` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `feed_posts`
--
ALTER TABLE `feed_posts`
  ADD CONSTRAINT `feed_posts_ibfk_1` FOREIGN KEY (`hospital_id`) REFERENCES `hospitals` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `hospitals`
--
ALTER TABLE `hospitals`
  ADD CONSTRAINT `hospitals_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `patients`
--
ALTER TABLE `patients`
  ADD CONSTRAINT `patients_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `report_attachments`
--
ALTER TABLE `report_attachments`
  ADD CONSTRAINT `report_attachments_ibfk_1` FOREIGN KEY (`report_id`) REFERENCES `visit_reports` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `visit_reports`
--
ALTER TABLE `visit_reports`
  ADD CONSTRAINT `visit_reports_ibfk_1` FOREIGN KEY (`appointment_id`) REFERENCES `appointments` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
