-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 05, 2024 at 11:43 AM
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
-- Database: `milk_tea_store`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `email`, `password`, `created_at`) VALUES
(1, 'chris', 'patrick@gmail.com', '$2y$10$xgi0gyd7nMy/OCj3mECSg.2RTCsAby0H039u04PJKuFlXCGbAIp.2', '2024-11-05 10:14:46'),
(2, 'piston', 'chriss@gmail.com', '$2y$10$/GvhAd144atmXb06YkBMNuyOs.67aBG9VbSyeXMH36Or7miQISinO', '2024-11-05 10:27:04'),
(3, 'pat', 'pat@gmail.com', '$2y$10$hxH4r.uH0LvjVmqPEQvWS.R5yCJDnSwQElI/Cdmst5ZaGDEm2MbMO', '2024-11-05 10:29:47'),
(4, 'pota', 'hanep@gmail.com', '$2y$10$ZCuicWtQV.dVAMFifn6LbeaFEJyru4IY.GR8iklmzxeSZ5uLZ3x8m', '2024-11-05 10:30:18'),
(5, 'cardo', 'cardo@gmail.com', '$2y$10$GMRoa4hW4YPNLc9.bbHaGOSgsx57uJS1MsX4toNyjMIt13yogD2Ym', '2024-11-05 10:32:12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
