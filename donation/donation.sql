-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 17, 2025 at 04:56 PM
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
-- Database: `donation`
--

-- --------------------------------------------------------

--
-- Table structure for table `donationlisting`
--

CREATE TABLE `donationlisting` (
  `id` int(11) NOT NULL,
  `Donor_Name` varchar(255) NOT NULL,
  `Amount` decimal(10,2) NOT NULL,
  `Payment_Method` varchar(255) NOT NULL,
  `Chapter` varchar(255) NOT NULL,
  `Date_of_Payment` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donationlisting`
--

INSERT INTO `donationlisting` (`id`, `Donor_Name`, `Amount`, `Payment_Method`, `Chapter`, `Date_of_Payment`) VALUES
(8, 'Winston', 500.00, 'Gcash', 'Quezon City', '2025-11-16'),
(10, 'Brian', 8500.00, 'Gcash', 'Quezon City', '2025-11-07'),
(11, 'Kervin Bailes', 1000.00, 'Cash', 'Montalban', '2025-11-15'),
(12, 'Abraham', 550.00, 'Cash', 'Rizal', '2025-11-16');

-- --------------------------------------------------------

--
-- Table structure for table `donation_campaign`
--

CREATE TABLE `donation_campaign` (
  `id` int(11) NOT NULL,
  `Purpose` varchar(255) NOT NULL,
  `Start_Date` date NOT NULL,
  `Due_Date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `donation_campaign`
--

INSERT INTO `donation_campaign` (`id`, `Purpose`, `Start_Date`, `Due_Date`, `created_at`) VALUES
(20, 'to help', '2025-11-08', '2025-11-21', '2025-11-15 17:03:48'),
(21, 'Charity', '2025-11-06', '2025-11-20', '2025-11-16 02:12:42'),
(22, 'Sponsor', '2025-11-14', '2025-11-20', '2025-11-16 02:59:41'),
(23, 'Charity', '2025-11-09', '2025-11-22', '2025-11-16 07:31:23');

-- --------------------------------------------------------

--
-- Table structure for table `receipts_batches`
--

CREATE TABLE `receipts_batches` (
  `id` int(11) NOT NULL,
  `batch_folder` varchar(255) NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `batch_date` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts_batches`
--

INSERT INTO `receipts_batches` (`id`, `batch_folder`, `event_name`, `batch_date`, `created_at`) VALUES
(10, '2025-11-16_to_help', 'to help', '2025-11-16', '2025-11-15 17:04:53'),
(11, '2025-11-12_ss', 'ss', '2025-11-12', '2025-11-16 01:22:12'),
(12, '2025-11-12_ss', 'ss', '2025-11-12', '2025-11-16 01:28:28'),
(13, '2025-11-07_Charity', 'Charity', '2025-11-07', '2025-11-16 01:48:41'),
(14, '2025-11-07_Charity', 'Charity', '2025-11-07', '2025-11-16 01:58:56'),
(15, '2025-11-20_Charity', 'Charity', '2025-11-20', '2025-11-16 02:13:35'),
(16, '2025-11-21_sad', 'sad', '2025-11-21', '2025-11-16 02:16:08'),
(17, '2025-11-20_Sponsor', 'Sponsor', '2025-11-20', '2025-11-16 03:01:24'),
(18, '2025-11-22_Charity', 'Charity', '2025-11-22', '2025-11-16 07:33:04');

-- --------------------------------------------------------

--
-- Table structure for table `receipts_files`
--

CREATE TABLE `receipts_files` (
  `id` int(11) NOT NULL,
  `batch_id` int(11) NOT NULL,
  `filename_original` varchar(255) NOT NULL,
  `filename_stored` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `receipts_files`
--

INSERT INTO `receipts_files` (`id`, `batch_id`, `filename_original`, `filename_stored`, `uploaded_at`) VALUES
(22, 10, '000.jpg', '000_1763226301_bf4040d3.jpg', '2025-11-15 17:05:01'),
(23, 12, '000.jpg', '000_1763256515_f4a2e233.jpg', '2025-11-16 01:28:35'),
(24, 13, 'CUSTOMER ASSOCIATE.jpg', 'CUSTOMER_ASSOCIATE_1763257738_93e3265c.jpg', '2025-11-16 01:48:58'),
(25, 14, '000.jpg', '000_1763258342_0bb200fb.jpg', '2025-11-16 01:59:02'),
(26, 15, 'Classic-&-Sports-Car-Best-classic-car-events-of-2025-24.jpg', 'Classic-_-Sports-Car-Best-classic-car-events-of-2025-24_1763259223_95ebfd42.jpg', '2025-11-16 02:13:43'),
(27, 17, 'Classic-&-Sports-Car-Best-classic-car-events-of-2025-24.jpg', 'Classic-_-Sports-Car-Best-classic-car-events-of-2025-24_1763262098_d9aadc0f.jpg', '2025-11-16 03:01:38'),
(28, 18, 'upload receipt.jpg', 'upload_receipt_1763278391_df465dbb.jpg', '2025-11-16 07:33:11');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `role`) VALUES
(1, 'super'),
(2, 'administrator');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `donationlisting`
--
ALTER TABLE `donationlisting`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `donation_campaign`
--
ALTER TABLE `donation_campaign`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts_batches`
--
ALTER TABLE `receipts_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `receipts_files`
--
ALTER TABLE `receipts_files`
  ADD PRIMARY KEY (`id`),
  ADD KEY `batch_id` (`batch_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `donationlisting`
--
ALTER TABLE `donationlisting`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `donation_campaign`
--
ALTER TABLE `donation_campaign`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `receipts_batches`
--
ALTER TABLE `receipts_batches`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `receipts_files`
--
ALTER TABLE `receipts_files`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
