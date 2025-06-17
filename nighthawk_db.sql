-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3308
-- Generation Time: Jun 16, 2025 at 03:55 PM
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
-- Database: `nighthawk_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `booking_date` datetime NOT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `service_id`, `booking_date`, `notes`, `status`, `created_at`) VALUES
(3, 3, 4, '2025-06-24 22:01:00', 'dfgdfghbedgh', 'pending', '2025-06-15 12:58:07'),
(4, 3, 1, '2025-06-25 03:10:00', 'Could be late for few min. Picking up kids from school.', 'pending', '2025-06-15 13:27:03');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `thread_id` int(11) DEFAULT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `subject` varchar(150) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('unread','read') DEFAULT 'unread',
  `user_id` int(11) NOT NULL,
  `sender_role` enum('user','admin') NOT NULL DEFAULT 'user',
  `receiver_role` enum('user','admin') DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `thread_id`, `full_name`, `email`, `subject`, `message`, `created_at`, `status`, `user_id`, `sender_role`, `receiver_role`) VALUES
(8, NULL, '', '', 'Meeting', 'Hi,\r\nCan I receive some updates about the meeting?\r\nRegards,\r\nJohn', '2025-06-15 16:53:38', 'unread', 3, 'user', 'admin'),
(9, NULL, '', '', 'Call back', 'Hi,\r\nI tried to reach you today but there was no answer. \r\nPlease call back asap.\r\nRegards,\r\nJohn', '2025-06-15 17:03:29', 'unread', 3, 'user', 'admin'),
(10, NULL, '', '', 'Meeting', 'Hi, \r\nMeeting will be held tommorow at 15:45.\r\nRegards,\r\nAdmin', '2025-06-15 17:19:27', 'unread', 3, 'admin', 'admin'),
(11, NULL, '', '', 'Call back', 'Hi,\r\nWe will call you back asap.\r\nRegards,\r\nAdmin', '2025-06-15 17:19:54', 'unread', 3, 'admin', 'admin'),
(12, NULL, '', '', '1', '1', '2025-06-15 17:23:56', 'unread', 3, 'user', 'admin');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `service_name` varchar(100) NOT NULL,
  `service_description` text DEFAULT NULL,
  `service_image` varchar(255) DEFAULT NULL,
  `description` text NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `service_name`, `service_description`, `service_image`, `description`, `image`, `created_at`) VALUES
(1, 'Fiber Optic Installation', 'Full-service fiber optic installation with high-speed internet connectivity for businesses and smart buildings.', 'service_1749979796_3579.png', 'Professional structured cabling services for businesses and data centres.', 'img/IMG1.jpg', '2025-06-10 09:53:25'),
(3, 'Structured Cabling Installation', 'Professional structured cabling for commercial buildings and data centers to ensure high-speed and reliable network infrastructure.', 'service_1749977938_4401.png', '', NULL, '2025-06-15 08:58:58'),
(4, 'Smart Home Automation', 'Custom smart home systems including lighting, HVAC, security, and voice control for modern living.', 'service_1749979855_3596.png', '', NULL, '2025-06-15 09:30:55'),
(5, 'Wireless Network Setup', 'Design and deployment of secure, high-performance wireless networks across large office or industrial spaces.', 'service_1749982815_9430.png', '', NULL, '2025-06-15 10:20:15'),
(6, 'CCTV & Security Systems', 'Installation and integration of advanced CCTV, alarm, and remote monitoring systems for enhanced building security.', 'service_1749982903_6069.png', '', NULL, '2025-06-15 10:21:43'),
(7, 'Server Room Setup & Maintenance', 'Professional server room design, cabling, equipment installation, cooling, and ongoing maintenance services.', 'service_1749982941_4747.png', '', NULL, '2025-06-15 10:22:21');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` enum('admin','client') NOT NULL DEFAULT 'client',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password_hash`, `role`, `created_at`) VALUES
(3, 'John', 'john@mail.com', '$2y$10$ZOMS7e.9uNYv5LqRccU7bucCNzieWChQY8rBxLHaRA4J7SPZw55Wq', 'client', '2025-06-10 10:54:05'),
(4, 'Admin', 'admin@nighthawk.com', '$2y$10$zhHFpi.rPn4hVvHV.v2eyOSIaFAyzi4kXUmk.FwmZF/sctZa10Bca', 'admin', '2025-06-10 10:58:44'),
(5, 'Client', 'client@nighthawk.com', '$2y$10$bE6D9NJLYvg1aYfSk67/COh7OhNOyIg6RvDGP22OFoR3LQo.7NCTC', 'client', '2025-06-10 12:19:13'),
(6, 'Anna', 'anna@mail.com', '$2y$10$dyuxH4We33OTxmSx.HTOTe31l9qtO2y6MEFW5DlNbCi/HbSYXv5vS', 'client', '2025-06-10 12:57:11'),
(7, 'Leo', 'leo@mail.com', '$2y$10$WL3D/VGFLhZElRVNsaaJU.KAFptCOH4EfjTXirN8OTWXNhpoTIDL2', 'client', '2025-06-10 13:32:38');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

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
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `fk_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
