-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3307
-- Generation Time: May 11, 2026 at 11:49 PM
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
-- Database: `ecotourismdb2`
--

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `booking_date` date DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `total_price` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bookings`
--

INSERT INTO `bookings` (`id`, `user_id`, `trip_id`, `booking_date`, `status`, `total_price`, `created_at`) VALUES
(1, 4, 1, '2026-06-09', 'pending', 50.00, '2026-05-03 18:23:15'),
(10, 4, 6, '2026-06-03', 'pending', 120.00, '2026-05-03 21:07:31'),
(11, 6, 13, '2026-07-02', 'pending', 200.00, '2026-05-08 18:07:20');

-- --------------------------------------------------------

--
-- Table structure for table `carbon_offset`
--

CREATE TABLE `carbon_offset` (
  `id` int(11) NOT NULL,
  `project_name` varchar(255) NOT NULL,
  `location` varchar(100) NOT NULL,
  `cost_per_kg` decimal(8,2) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `certificate_file` varchar(255) NOT NULL,
  `status` enum('active','expired','revoked') DEFAULT 'active',
  `version` int(11) DEFAULT 1,
  `translation_needed` tinyint(1) DEFAULT 0,
  `action` enum('upload','renew') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `field_reports`
--

CREATE TABLE `field_reports` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `report_text` text DEFAULT NULL,
  `photo_url` varchar(255) DEFAULT NULL,
  `posted_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `guides`
--

CREATE TABLE `guides` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `languages` varchar(255) DEFAULT NULL,
  `experience_years` int(11) DEFAULT NULL,
  `rating` float DEFAULT 0,
  `sustainability_score` float DEFAULT 0,
  `years_of_residency` int(11) DEFAULT 0,
  `community_score` int(11) DEFAULT 0,
  `local_cred_score` decimal(5,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `guides`
--

INSERT INTO `guides` (`id`, `user_id`, `bio`, `languages`, `experience_years`, `rating`, `sustainability_score`, `years_of_residency`, `community_score`, `local_cred_score`) VALUES
(1, 3, 'Eco guide', 'English, Arabic', 5, 0, 0, 0, 0, 0.00),
(2, 3, 'Experienced eco tour guide in Sinai', 'English, Arabic', 5, 0, 0, 0, 0, 0.00),
(3, 4, 'Specialist in desert and Nile eco tours', 'English, French', 3, 0, 0, 0, 0, 0.00),
(4, 3, 'Experienced eco tour guide in Sinai', 'English, Arabic', 5, 0, 0, 0, 0, 0.00),
(5, 4, 'Specialist in desert and Nile eco tours', 'English, French', 3, 0, 0, 0, 0, 0.00),
(6, 3, 'Experienced eco tour guide in Sinai', 'English, Arabic', 5, 0, 0, 0, 0, 0.00),
(7, 4, 'Specialist in desert and Nile eco tours', 'English, French', 3, 0, 0, 0, 0, 0.00),
(8, 3, 'Experienced eco tour guide in Sinai', 'English, Arabic', 5, 0, 0, 0, 0, 0.00),
(9, 4, 'Specialist in desert and Nile eco tours', 'English, French', 3, 0, 0, 0, 0, 0.00),
(10, 6, '', NULL, NULL, 0, 0, 0, 0, 0.00),
(11, 8, '', NULL, NULL, 0, 0, 0, 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `guide_languages`
--

CREATE TABLE `guide_languages` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) NOT NULL,
  `language` varchar(100) NOT NULL,
  `verification_method` enum('certificate','peer_review') DEFAULT 'certificate',
  `proof_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','active','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `trip_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `eco_rating` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `guide_id` int(11) DEFAULT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `capacity` int(11) DEFAULT NULL,
  `available_from` date DEFAULT NULL,
  `available_to` date DEFAULT NULL,
  `sustainability_score` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `transport_type` varchar(50) DEFAULT NULL COMMENT 'plane, walking, bus, etc.',
  `distance_km` decimal(8,2) DEFAULT 100.00,
  `is_protected_area` tinyint(1) DEFAULT 0,
  `indigenous_consent_status` enum('pending','approved','rejected','not_required') DEFAULT 'not_required',
  `type` varchar(50) DEFAULT NULL COMMENT 'desert, marine, forest, etc.',
  `tags` text DEFAULT NULL,
  `equipment_type` varchar(50) DEFAULT NULL,
  `equipment_total` int(11) DEFAULT NULL,
  `waste_management_level` enum('zero_waste','recycling','basic','none') DEFAULT 'basic',
  `carbon_score` decimal(10,2) DEFAULT 0.00
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `guide_id`, `title`, `description`, `location`, `price`, `capacity`, `available_from`, `available_to`, `sustainability_score`, `created_at`, `transport_type`, `distance_km`, `is_protected_area`, `indigenous_consent_status`, `type`, `tags`, `equipment_type`, `equipment_total`, `waste_management_level`, `carbon_score`) VALUES
(1, 1, 'Desert Trip', 'Eco desert tour', 'Sinai', 50.00, 10, '2026-06-01', '2026-06-30', 0, '2026-05-03 01:30:42', NULL, 100.00, 0, 'not_required', NULL, NULL, NULL, NULL, 'basic', 0.00),
(6, 1, 'Sinai Desert Adventure', 'Eco-friendly desert hiking trip', 'Sinai', 120.00, 10, '2026-06-01', '2026-06-05', 0, '2026-05-03 18:57:41', NULL, 100.00, 0, 'not_required', NULL, NULL, NULL, NULL, 'basic', 0.00),
(7, 1, 'Mountain Hiking', 'Climb mountains with eco guide', 'Saint Catherine', 150.00, 8, '2026-06-10', '2026-06-15', 0, '2026-05-03 18:57:41', NULL, 100.00, 0, 'not_required', NULL, NULL, NULL, NULL, 'basic', 0.00),
(13, 2, 'Nile Eco Trip', 'Eco boat ride and cultural experience', 'Aswan', 200.00, 20, '2026-07-01', '2026-07-03', 40, '2026-05-03 18:58:04', NULL, 100.00, 0, 'not_required', NULL, NULL, NULL, NULL, 'basic', 0.00),
(16, 2, 'Fayoum Farm Visit', 'Visit organic farms and learn sustainability', 'Fayoum', 80.00, 15, '2026-06-20', '2026-06-20', 0, '2026-05-03 18:58:36', NULL, 100.00, 0, 'not_required', NULL, NULL, NULL, NULL, 'basic', 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `trip_locations`
--

CREATE TABLE `trip_locations` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `lat` decimal(10,8) NOT NULL,
  `lng` decimal(11,8) NOT NULL,
  `order_index` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_shadows`
--

CREATE TABLE `trip_shadows` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `trainee_guide_id` int(11) NOT NULL,
  `senior_guide_id` int(11) DEFAULT NULL,
  `status` enum('pending','active','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trip_translations`
--

CREATE TABLE `trip_translations` (
  `id` int(11) NOT NULL,
  `trip_id` int(11) NOT NULL,
  `language` varchar(10) NOT NULL,
  `translation_needed` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `role` enum('tourist','guide','admin') DEFAULT 'tourist',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `created_at`) VALUES
(1, 'Ahmed', 'ahmed@test.com', '123456', 'tourist', '2026-05-03 01:30:42'),
(2, 'Sara', 'sara@test.com', '123456', 'tourist', '2026-05-03 01:30:42'),
(3, 'Omar', 'omar@test.com', '123456', 'guide', '2026-05-03 01:30:42'),
(4, 'assem', 'assemosama00@gmail.com', '$2y$10$L.4j4mUEcsFL9Ae80FTZSON.A58AYdesVt8UxHHUteRl1JeBqBgLG', 'tourist', '2026-05-03 17:47:24'),
(5, 'ali', 'ali343434@gmail.com', '$2y$10$erj9rS2bamA0pUaGWZgu8.zs9vKHq/t6v/IqbQfci2/14LxwOeWS6', 'tourist', '2026-05-03 20:25:09'),
(6, 'ganna', 'ganna@gmail.com', '$2y$10$caG8BdKXSRst7ghjhcMHVuOIS3s7aihhNnX81mvaL2ScvUKBJKH3a', 'guide', '2026-05-08 14:52:46'),
(7, 'ali', 'ali@gmail.com', '$2y$10$8wxICslFTPTQLqDWJKumkuge9G.thfapz2aFDNZ/XpEzqPAlmMTTy', 'admin', '2026-05-08 14:59:15'),
(8, 'ppp', 'ppp@gmail.com', '$2y$10$tzw60jjbIC6kOfDdDOcTLeMUOexUkw0kuc8/stLLDtEPbv.NcmWFK', 'guide', '2026-05-09 15:20:53');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `carbon_offset`
--
ALTER TABLE `carbon_offset`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `field_reports`
--
ALTER TABLE `field_reports`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `guides`
--
ALTER TABLE `guides`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `guide_languages`
--
ALTER TABLE `guide_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `guide_id` (`guide_id`);

--
-- Indexes for table `trip_locations`
--
ALTER TABLE `trip_locations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`);

--
-- Indexes for table `trip_shadows`
--
ALTER TABLE `trip_shadows`
  ADD PRIMARY KEY (`id`),
  ADD KEY `trip_id` (`trip_id`),
  ADD KEY `trainee_guide_id` (`trainee_guide_id`),
  ADD KEY `senior_guide_id` (`senior_guide_id`);

--
-- Indexes for table `trip_translations`
--
ALTER TABLE `trip_translations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_trip_lang` (`trip_id`,`language`);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `carbon_offset`
--
ALTER TABLE `carbon_offset`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `field_reports`
--
ALTER TABLE `field_reports`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `guides`
--
ALTER TABLE `guides`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `guide_languages`
--
ALTER TABLE `guide_languages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `trip_locations`
--
ALTER TABLE `trip_locations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_shadows`
--
ALTER TABLE `trip_shadows`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trip_translations`
--
ALTER TABLE `trip_translations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`);

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `field_reports`
--
ALTER TABLE `field_reports`
  ADD CONSTRAINT `field_reports_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `field_reports_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `guides`
--
ALTER TABLE `guides`
  ADD CONSTRAINT `guides_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `guide_languages`
--
ALTER TABLE `guide_languages`
  ADD CONSTRAINT `guide_languages_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`);

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`guide_id`) REFERENCES `guides` (`id`);

--
-- Constraints for table `trip_locations`
--
ALTER TABLE `trip_locations`
  ADD CONSTRAINT `trip_locations_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `trip_shadows`
--
ALTER TABLE `trip_shadows`
  ADD CONSTRAINT `trip_shadows_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trip_shadows_ibfk_2` FOREIGN KEY (`trainee_guide_id`) REFERENCES `guides` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `trip_shadows_ibfk_3` FOREIGN KEY (`senior_guide_id`) REFERENCES `guides` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `trip_translations`
--
ALTER TABLE `trip_translations`
  ADD CONSTRAINT `trip_translations_ibfk_1` FOREIGN KEY (`trip_id`) REFERENCES `trips` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
