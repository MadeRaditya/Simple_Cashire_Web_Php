-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2025 at 04:00 PM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u403191482_kasir_app`
--

-- --------------------------------------------------------

--
-- Table structure for table `menu_items`
--

CREATE TABLE `menu_items` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` enum('food','beverage','dessert') NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `menu_items`
--

INSERT INTO `menu_items` (`id`, `name`, `category`, `price`, `description`, `image`, `created_at`, `updated_at`) VALUES
(1, 'ayam goren', 'food', 15000.00, 'ayam goreng enak', 'ayam goren.jpg', '2024-12-16 13:27:07', '2024-12-16 13:27:07'),
(2, 'Es teh', 'beverage', 5000.00, 'Es teh Manis', 'Es teh.jpg', '2024-12-16 14:22:45', '2024-12-18 07:54:18'),
(3, 'ayam bakar', 'food', 17000.00, 'Ayam Bakar Enakkkkk', 'ayam bakar.jpg', '2024-12-18 15:49:05', '2024-12-18 16:00:16'),
(5, 'Lele Goreng', 'food', 13000.00, 'Lele Goreng enak', 'Lele Goreng.jpg', '2024-12-20 06:28:54', '2024-12-20 06:28:54'),
(6, 'Es Jeruk', 'beverage', 5000.00, 'Es jeruk peras Seger', 'Es Jeruk.jpg', '2024-12-23 04:06:13', '2024-12-23 04:06:13');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `table_id` int(11) DEFAULT NULL,
  `order_type` enum('dine-in','take-away') NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `table_id`, `order_type`, `total_amount`, `status`, `created_at`, `updated_at`) VALUES
(1, 2, 1, 'dine-in', 42000.00, 'completed', '2024-12-28 14:40:42', '2024-12-28 14:47:01'),
(2, 2, NULL, 'take-away', 68000.00, 'completed', '2024-12-28 14:42:02', '2024-12-28 14:47:12'),
(3, 2, 2, 'dine-in', 31000.00, 'completed', '2024-12-28 14:42:34', '2024-12-28 14:44:16'),
(4, 3, 2, 'dine-in', 20000.00, 'completed', '2024-12-28 14:45:04', '2025-01-13 04:16:00'),
(5, 3, NULL, 'take-away', 33000.00, 'pending', '2024-12-28 14:45:34', '2024-12-28 14:45:34'),
(6, 2, 1, 'dine-in', 42000.00, 'pending', '2024-12-28 15:14:13', '2024-12-28 15:14:13'),
(7, 2, NULL, 'take-away', 22000.00, 'pending', '2025-01-03 06:51:52', '2025-01-03 06:51:52'),
(8, 2, 3, 'dine-in', 37000.00, 'cancelled', '2025-01-13 04:14:32', '2025-01-29 04:41:50');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `menu_item_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `menu_item_id`, `quantity`, `price`) VALUES
(1, 1, 1, 1, 15000.00),
(2, 1, 2, 1, 5000.00),
(3, 1, 3, 1, 17000.00),
(4, 1, 6, 1, 5000.00),
(5, 2, 1, 3, 45000.00),
(6, 2, 2, 2, 5000.00),
(7, 3, 5, 2, 26000.00),
(8, 3, 6, 1, 5000.00),
(9, 4, 1, 1, 15000.00),
(10, 4, 2, 1, 5000.00),
(11, 5, 1, 1, 15000.00),
(12, 5, 5, 1, 13000.00),
(13, 5, 6, 1, 5000.00),
(14, 2, 5, 1, 13000.00),
(15, 2, 6, 1, 5000.00),
(16, 6, 1, 1, 15000.00),
(17, 6, 2, 1, 5000.00),
(18, 6, 3, 1, 17000.00),
(19, 6, 6, 1, 5000.00),
(20, 7, 2, 1, 5000.00),
(21, 7, 3, 1, 17000.00),
(22, 8, 1, 1, 15000.00),
(23, 8, 2, 1, 5000.00),
(24, 8, 3, 1, 17000.00);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `amount_paid` decimal(10,2) NOT NULL,
  `change_given` decimal(10,2) DEFAULT 0.00,
  `payment_method` enum('cash','card','other') NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `order_id`, `amount_paid`, `change_given`, `payment_method`, `created_at`) VALUES
(1, 3, 35000.00, 4000.00, 'cash', '2024-12-28 14:44:16'),
(2, 1, 50000.00, 8000.00, 'cash', '2024-12-28 14:47:01'),
(3, 2, 70000.00, 2000.00, 'cash', '2024-12-28 14:47:12'),
(4, 4, 20000.00, 0.00, 'cash', '2025-01-13 04:16:00');

-- --------------------------------------------------------

--
-- Table structure for table `tables`
--

CREATE TABLE `tables` (
  `id` int(11) NOT NULL,
  `table_number` varchar(50) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` enum('available','occupied') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tables`
--

INSERT INTO `tables` (`id`, `table_number`, `capacity`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Meja 1', 4, 'occupied', '2024-12-16 13:50:42', '2024-12-28 15:14:13'),
(2, 'Meja 2', 2, 'available', '2024-12-16 13:50:42', '2025-01-13 04:16:00'),
(3, 'Meja 3', 4, 'occupied', '2024-12-16 13:50:42', '2025-01-13 04:14:32'),
(4, 'Meja 4', 6, 'available', '2024-12-16 13:50:42', '2024-12-17 13:17:13'),
(5, 'Meja 5', 4, 'available', '2024-12-16 13:50:42', '2024-12-17 13:17:13'),
(6, 'Meja 6', 2, 'available', '2024-12-16 13:50:42', '2024-12-17 12:57:15'),
(7, 'Meja 7', 4, 'available', '2024-12-16 13:50:42', '2024-12-16 13:50:42'),
(8, 'Meja 8', 6, 'available', '2024-12-16 13:50:42', '2024-12-16 13:50:42'),
(9, 'Meja 9', 4, 'available', '2024-12-16 13:50:42', '2024-12-16 13:50:42'),
(10, 'Meja 10', 2, 'available', '2024-12-16 13:50:42', '2024-12-16 13:50:42');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','kasir','pelayan') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `role`, `created_at`, `updated_at`) VALUES
(2, 'kasir', '$2y$10$2FUy2Tc7dHljTbTvL9RA/O8Wne4o.ls/dIW3zAdNewDeOD0J.ZgDi', 'kasir', '2024-12-16 13:20:03', '2024-12-16 13:20:03'),
(3, 'pelayan', '$2y$10$IIp7Zc1LKfuxG.oaqebqUuUcwdrdLyTdApmL3KCgZPaAQE7tsKuHu', 'pelayan', '2024-12-16 13:20:19', '2024-12-16 13:20:19'),
(5, 'admin', '$2y$10$pNMduPTuXWdf4x6LRVjcAuCoAs4VS2kPbNdIoina/Xfefb77Pa9n.', 'admin', '2024-12-18 15:08:49', '2024-12-18 15:08:49'),
(7, 'testing', '$2y$10$e9tV3dvDgzfo0bJYFQoAY.OrInMskdFtoD2EjX6Gwch9kSWAD7WXW', 'admin', '2024-12-18 15:15:10', '2024-12-18 15:15:10'),
(8, 'ikhwan', '$2y$10$VDoHgCtE9vsJ7Xl1OEaqhe6FJzqmmhQckqtPJ8t/ZnPZgsfA0PyrW', 'kasir', '2024-12-20 08:19:10', '2024-12-20 08:19:10'),
(9, 'contohUser', '$2y$10$Q.WaGbwkye5vjQiwpjxLbOqv/BJckvukGdeCkPKGjRP8inTbEsxoO', 'pelayan', '2024-12-28 14:34:03', '2024-12-28 14:34:03'),
(11, 'casaos', '$2y$10$cuw9AtrwKz68di4/Lfpqy.LDAzE35/0e6T4TGVYTcCZkHrmfpcfvu', 'admin', '2025-02-10 12:46:47', '2025-02-10 12:46:47'),
(12, 'mualifkama70', '$2y$10$QUNBrfOdJgbhe6EKC1/CeOlqzORipQ6jjgvYynlm75XzfCdtt.ITi', 'kasir', '2025-02-10 12:48:06', '2025-02-10 12:48:06');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `menu_items`
--
ALTER TABLE `menu_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `table_id` (`table_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `menu_item_id` (`menu_item_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`);

--
-- Indexes for table `tables`
--
ALTER TABLE `tables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `menu_items`
--
ALTER TABLE `menu_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tables`
--
ALTER TABLE `tables`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
