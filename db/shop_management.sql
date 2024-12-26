-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 04:59 PM
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
(2, 1, 'Service 2 for Shop 1', 'Description for Service 2', 150.00, 'service2@shop1.com'),
(3, 2, 'Service 1 for Shop 2', 'Description for Service 1', 200.00, 'service1@shop2.com'),
(4, 2, 'Service 2 for Shop 2', 'Description for Service 2', 250.00, 'service2@shop2.com'),
(5, 3, 'Service 1 for Shop 3', 'Description for Service 1', 300.00, 'service1@shop3.com'),
(6, 3, 'Service 2 for Shop 3', 'Description for Service 2', 350.00, 'service2@shop3.com'),
(7, 1, 'eeddh', NULL, 33.00, ''),
(8, 1, 'akgdjas', NULL, 555.00, '');

-- --------------------------------------------------------

--
-- Table structure for table `service_requests`
--

CREATE TABLE `service_requests` (
  `id` int(11) NOT NULL,
  `shop_name` varchar(255) NOT NULL,
  `shop_address` text NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `current_location` text NOT NULL,
  `service_needed` text NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(50) NOT NULL,
  `shop_id` int(11) NOT NULL,
  `customer_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(5, 'Shop B', 'Thiruvalla, Pathanamthitta', 'Pathanamthitta', 9.38345200, 76.57405900, '0987654321', 'shopb@example.com', 'http://shopb.com', 'owner2@example.com'),
(6, 'Shop C', 'Thiruvalla, Pathanamthitta', 'Pathanamthitta', 9.38345200, 76.57405900, '1122334455', 'shopc@example.com', 'http://shopc.com', 'owner3@example.com');

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
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `service_requests`
--
ALTER TABLE `service_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `shops`
--
ALTER TABLE `shops`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

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
