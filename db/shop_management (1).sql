-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2024 at 09:21 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `shop_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `password`, `email`, `created_at`) VALUES
(1, 'amith', '$2y$10$x8KJTOYnGh9dAbA.PFhp1eCp8oHVWSEj8Cp0oPbQvD5eeylY1KHFC', 'amithabey13@gmail.com', '2024-12-07 16:56:24'),
(2, 'meruz', '$2y$10$Y2OpgOR9aF8xxWq7FM6WreAEXvkbQs6kdMzUVH3fm4UWGLU52Wvcm', 'merina@gmail.com', '2024-12-26 08:17:35');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `service_name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `shop_id`, `service_name`, `description`, `price`, `email`) VALUES
(1, 1, 'Service 1 for Shop 1', 'Description for Service 1', 100.00, 'service1@shop1.com'),
(2, 1, 'Service 2 for Shop 1', 'Description for Service 2', 5550.00, 'service2@shop1.com'),
(3, 2, 'Service 1 for Shop 2', 'Description for Service 1', 200.00, 'service1@shop2.com'),
(4, 2, 'Service 2 for Shop 2', 'Description for Service 2', 250.00, 'service2@shop2.com'),
(5, 3, 'Service 1 for Shop 3', 'Description for Service 1', 300.00, 'service1@shop3.com'),
(6, 3, 'Service 2 for Shop 3', 'Description for Service 2', 350.00, 'service2@shop3.com'),
(7, 1, 'eeddh', NULL, 33.00, ''),
(8, 1, 'akgdjas', NULL, 555.00, ''),
(9, 1, 'geloo', NULL, 5.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_address` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `current_location` text NOT NULL,
  `service_needed` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_requests`
--

INSERT INTO `service_requests` (`id`, `shop_id`, `shop_name`, `shop_address`, `name`, `email`, `phone`, `current_location`, `service_needed`, `timestamp`, `status`) VALUES
(2, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'Amith Abey Stephen', 'jishma1234@gmail.com', '09188550674', 'sdd', 'dsbkbsdkbewkb', '2024-12-26 04:48:31', 'Completed'),
(4, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'Amith Abey Stephen', 'amithabey13@gmail.com', '09188550674', '  mn cm sd dc', 'nsdlsjdnkndkbnkjbeqkbnkew m kdnjknds', '2024-12-26 04:56:21', 'In Progress'),
(5, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'Amith Abey Stephen', 'amithabey13@gmail.com', '09188550674', '  mn cm sd dc', 'c,new pull req', '2024-12-26 05:04:43', 'Pending'),
(6, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'Amith Abey Stephen', 'amithabey13@gmail.com', 'jjnnjn', '  mn cm sd dc', 'nsdlsjdnkndkbnkjbeqkbnkew m   msdnkskdknkewbnkweabjkewbkebkewjlbwkbewk', '2024-12-26 05:35:23', 'Pending'),
(7, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'hiii', '1234@gmail.com', 'jjnnjn', '  mn cm sd dc', 'kcxkcxnk', '2024-12-26 07:49:17', 'Pending'),
(8, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'hiii', '1234@gmail.com', 'jjnnjn', '  mn cm sd dc', 'kcxkcxnk', '2024-12-26 07:50:07', 'Pending'),
(9, 4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'hiii', '1234@gmail.com', 'jjnnjn', '  mn cm sd dc', 'nnns', '2024-12-26 07:50:16', 'Pending');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `site_name` varchar(255) NOT NULL,
  `admin_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `site_name`, `admin_email`) VALUES
(1, 'lover', 'admin@example.com');

-- --------------------------------------------------------

--
-- Table structure for table `shops`
--

CREATE TABLE `shops` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `address` text NOT NULL,
  `district` varchar(255) NOT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `website` varchar(255) DEFAULT NULL,
  `owner_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shops`
--

INSERT INTO `shops` (`id`, `shop_name`, `address`, `district`, `latitude`, `longitude`, `phone_number`, `email`, `website`, `owner_email`) VALUES
(1, 'Shop 1', 'Valiyakulam, Kottayam', 'Kottayam', 9.59156600, 76.52215300, '1234567890', 'shop1@example.com', 'http://shop1.com', 'amithabey13@gmail.com'),
(2, 'Shop 2', 'Valiyakulam, Kottayam', 'Kottayam', 9.59156600, 76.52215300, '0987654321', 'shop2@example.com', 'http://shop2.com', 'owner2@example.com'),
(3, 'Shop 3', 'Valiyakulam', 'Kottayam', 9.59156600, 76.52215300, '1122334455', 'shop3@example.com', 'http://shop3.com', 'owner3@example.com'),
(4, 'Shop A', 'Thiruvalla, Pathanamthitta', 'Pathanamthitta', 9.38345200, 76.57405900, '1234567890', 'shopa@example.com', 'http://shopa.com', 'amithabey13@gmail.com'),
(9, 'Amith Abey Stephen', 'skjx', '', 0.00000000, 0.00000000, '', '', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `shop_owners`
--

CREATE TABLE `shop_owners` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shop_owners`
--

INSERT INTO `shop_owners` (`id`, `name`, `email`, `phone_number`, `password`) VALUES
(1, 'amith', 'amithabey13@gmail.com', '', '$2y$10$AthGjOJAoCV9r6ZKTkZ3PuCg/TzuEbHxsv0EbQccg0l/AzB7Feuo6');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `shop_id` (`shop_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shops`
--
ALTER TABLE `shops`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `shop_owners`
--
ALTER TABLE `shop_owners`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `shop_owners`
--
ALTER TABLE `shop_owners`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `services`
--
ALTER TABLE `services`
  ADD CONSTRAINT `services_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`);

--
-- Constraints for table `service_requests`
--
ALTER TABLE `service_requests`
  ADD CONSTRAINT `service_requests_ibfk_1` FOREIGN KEY (`shop_id`) REFERENCES `shops` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
