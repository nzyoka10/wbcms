-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 19, 2024 at 01:11 PM
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
-- Database: `wbcms_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `meter_readings`
--

CREATE TABLE `meter_readings` (
  `reading_id` int(11) NOT NULL,
  `meter_id` int(11) DEFAULT NULL,
  `reading_date` date DEFAULT NULL,
  `reading_value` decimal(10,2) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_billinglist`
--

CREATE TABLE `tbl_billinglist` (
  `bill_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reading_date` date DEFAULT NULL,
  `due_date` date NOT NULL,
  `current_reading` float(12,2) NOT NULL DEFAULT 0.00,
  `previous_reading` float(12,2) NOT NULL DEFAULT 0.00,
  `rate` float(12,2) NOT NULL DEFAULT 0.00,
  `total` float(12,2) NOT NULL DEFAULT 0.00,
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0= pending,\r\n1= paid',
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_billinglist`
--

INSERT INTO `tbl_billinglist` (`bill_id`, `user_id`, `reading_date`, `due_date`, `current_reading`, `previous_reading`, `rate`, `total`, `status`, `created_at`, `updated_at`) VALUES
(44, 122, '2024-08-15', '2024-08-20', 12.00, 5.00, 14.00, 98.00, 0, '2024-08-15 12:37:32', '2024-08-15 19:38:50'),
(45, 138, '2024-08-15', '2024-08-20', 10.00, 5.00, 14.00, 70.00, 1, '2024-08-15 12:45:07', '2024-08-17 12:42:30'),
(47, 126, '2024-08-15', '2024-08-20', 17.00, 7.00, 14.00, 140.00, 1, '2024-08-15 19:02:59', '2024-08-15 19:50:24'),
(48, 129, '2024-08-15', '2024-08-20', 16.00, 5.00, 14.00, 154.00, 1, '2024-08-15 20:11:41', '2024-08-16 13:24:48'),
(49, 139, '2024-08-17', '2024-08-21', 12.00, 2.00, 14.00, 140.00, 1, '2024-08-17 09:23:50', '2024-08-17 09:24:40');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_clients`
--

CREATE TABLE `tbl_clients` (
  `user_id` int(11) NOT NULL,
  `client_name` varchar(50) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `meter_number` int(10) NOT NULL,
  `meter_reading` int(20) NOT NULL,
  `status` enum('inactive','active') DEFAULT 'inactive',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_clients`
--

INSERT INTO `tbl_clients` (`user_id`, `client_name`, `contact_number`, `address`, `meter_number`, `meter_reading`, `status`, `created_at`, `updated_at`) VALUES
(122, 'Test User', '0719314567', '208, Katani', 10001011, 5, 'active', '2024-08-09 08:18:03', '2024-08-18 08:55:56'),
(126, 'Aimee York', '0789123564', '208, Syokimau', 10001012, 7, 'active', '2024-08-09 08:40:21', '2024-08-18 08:55:34'),
(128, 'Leilani Holcomb', '0789341833', '012, Kawala', 10001014, 0, 'inactive', '2024-08-09 14:03:38', '2024-08-18 08:55:21'),
(129, 'Omollo Julio', '0721456728', '208, Syokimau', 10001015, 5, 'active', '2024-08-09 14:09:22', '2024-08-13 06:42:18'),
(130, 'Lilian Chebet', '0722456754', '210, Kisaju', 10001016, 3, 'active', '2024-08-09 14:38:54', '2024-08-18 08:55:45'),
(139, 'Hiroko Morrow', '0718543471', '056, Kinoo', 10001017, 2, 'active', '2024-08-17 05:58:50', '2024-08-18 08:56:04');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_invoices`
--

CREATE TABLE `tbl_invoices` (
  `invoice_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meter_id` int(11) DEFAULT NULL,
  `billing_date` date DEFAULT NULL,
  `due_date` date DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('paid','unpaid') DEFAULT 'unpaid',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_meters`
--

CREATE TABLE `tbl_meters` (
  `meter_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `meter_number` varchar(50) NOT NULL,
  `installation_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_notifications`
--

CREATE TABLE `tbl_notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('SMS','Email') DEFAULT NULL,
  `message` text DEFAULT NULL,
  `sent_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('sent','failed') DEFAULT 'sent'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_settings`
--

CREATE TABLE `tbl_settings` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_email` varchar(255) NOT NULL,
  `billing_rate` decimal(10,2) NOT NULL,
  `enable_notifications` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_settings`
--

INSERT INTO `tbl_settings` (`id`, `company_name`, `company_email`, `billing_rate`, `enable_notifications`) VALUES
(1, 'Water Billing Customer Management System ', 'wbcms@sys.mail', 14.00, 1);

-- --------------------------------------------------------

--
-- Table structure for table `tbl_tariffrates`
--

CREATE TABLE `tbl_tariffrates` (
  `tariff_id` int(11) NOT NULL,
  `rate` decimal(10,2) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_tariffrates`
--

INSERT INTO `tbl_tariffrates` (`tariff_id`, `rate`, `created_at`, `updated_at`) VALUES
(1, 15.00, '2024-08-13 07:08:27', '2024-08-13 07:21:37');

-- --------------------------------------------------------

--
-- Table structure for table `tbl_users`
--

CREATE TABLE `tbl_users` (
  `user_id` int(11) NOT NULL,
  `user_name` varchar(150) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `user_password` varchar(150) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tbl_users`
--

INSERT INTO `tbl_users` (`user_id`, `user_name`, `user_email`, `user_password`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'admin@app.com', '$2y$10$E5GluGsB.afNcEC0mf/kFObJUtGbhsCgJrUO9oNP5Afyh4DufT91e', '2024-06-27 11:52:32', '2024-08-18 11:52:58'),
(2, 'test', 'test@user.com', '$2y$10$ZjWBP/FcCKmYIRGbVubL0eosP5EngoHijnM1Pvm0bAhPrStYr5ebi', '2024-07-17 11:53:12', '2024-08-15 11:53:25');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD PRIMARY KEY (`reading_id`),
  ADD KEY `meter_id` (`meter_id`);

--
-- Indexes for table `tbl_billinglist`
--
ALTER TABLE `tbl_billinglist`
  ADD PRIMARY KEY (`bill_id`),
  ADD UNIQUE KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`client_name`);

--
-- Indexes for table `tbl_invoices`
--
ALTER TABLE `tbl_invoices`
  ADD PRIMARY KEY (`invoice_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `meter_id` (`meter_id`);

--
-- Indexes for table `tbl_meters`
--
ALTER TABLE `tbl_meters`
  ADD PRIMARY KEY (`meter_id`),
  ADD UNIQUE KEY `meter_number` (`meter_number`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbl_tariffrates`
--
ALTER TABLE `tbl_tariffrates`
  ADD PRIMARY KEY (`tariff_id`);

--
-- Indexes for table `tbl_users`
--
ALTER TABLE `tbl_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `meter_readings`
--
ALTER TABLE `meter_readings`
  MODIFY `reading_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_billinglist`
--
ALTER TABLE `tbl_billinglist`
  MODIFY `bill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=50;

--
-- AUTO_INCREMENT for table `tbl_clients`
--
ALTER TABLE `tbl_clients`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

--
-- AUTO_INCREMENT for table `tbl_invoices`
--
ALTER TABLE `tbl_invoices`
  MODIFY `invoice_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_meters`
--
ALTER TABLE `tbl_meters`
  MODIFY `meter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tbl_settings`
--
ALTER TABLE `tbl_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tbl_tariffrates`
--
ALTER TABLE `tbl_tariffrates`
  MODIFY `tariff_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tbl_users`
--
ALTER TABLE `tbl_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `meter_readings`
--
ALTER TABLE `meter_readings`
  ADD CONSTRAINT `meter_readings_ibfk_1` FOREIGN KEY (`meter_id`) REFERENCES `tbl_meters` (`meter_id`);

--
-- Constraints for table `tbl_invoices`
--
ALTER TABLE `tbl_invoices`
  ADD CONSTRAINT `tbl_invoices_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_clients` (`user_id`),
  ADD CONSTRAINT `tbl_invoices_ibfk_2` FOREIGN KEY (`meter_id`) REFERENCES `tbl_meters` (`meter_id`);

--
-- Constraints for table `tbl_meters`
--
ALTER TABLE `tbl_meters`
  ADD CONSTRAINT `tbl_meters_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_clients` (`user_id`);

--
-- Constraints for table `tbl_notifications`
--
ALTER TABLE `tbl_notifications`
  ADD CONSTRAINT `tbl_notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `tbl_clients` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
