-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 23, 2024 at 04:59 PM
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
-- Database: `delta`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `accountID` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `password` varchar(100) NOT NULL,
  `phonenumber` bigint(11) NOT NULL,
  `emailaddress` varchar(100) NOT NULL,
  `type` varchar(5) NOT NULL,
  `address` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountID`, `name`, `password`, `phonenumber`, `emailaddress`, `type`, `address`) VALUES
(1, 'Simp4Suisei123', '$2y$10$0lBECBVGNRSnilAkfAp9Jeele3y1JLGqmGGbQvdGQ.yhPa3v.IwrW', 123, 'hoshimachi.suisei@gmail.com', 'user', '659 Hagenes Orchard, Port Terrellton, IN');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` varchar(4) NOT NULL,
  `itemPrice` double NOT NULL,
  `bulkPrice` double NOT NULL,
  `itemName` varchar(30) NOT NULL,
  `itemStock` int(11) NOT NULL,
  `bulkAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemPrice`, `bulkPrice`, `itemName`, `itemStock`, `bulkAmount`) VALUES
('BAND', 20, 100, 'ID Bands', 100, 5),
('BDGA', 50, 100, 'Adhesive Bandage', 1238, 100),
('BDGB', 123, 1244, 'Elastic Bandage', 100, 214),
('CTTN', 50, 500, 'Cotton Applicators', 100, 10),
('FACE', 15, 150, 'Face Masks', 100, 10),
('GAUZ', 100, 500, 'Gauze', 23, 23),
('GLOV', 75, 375, 'Gloves', 100, 5),
('HEAD', 10, 100, 'Head Caps', 100, 50),
('SHOE', 35, 175, 'Shoe Covers', 100, 5),
('SNTZ', 100, 250, 'Hand Sanitizer', 100, 23),
('SPLA', 300, 1500, 'Wrist Splints', 100, 5),
('SPLB', 20, 100, 'Traction Splints', 100, 5),
('STSC', 100, 500, 'Stethoscope', 100, 5),
('THRM', 213, 123, 'Thermometer', 87, 54),
('TISS', 20, 200, 'Tissues', 94, 10),
('UNDR', 150, 750, 'Under Pads', 100, 5);

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `orderID` int(11) NOT NULL,
  `accountID` int(11) DEFAULT NULL,
  `orderDate` date NOT NULL,
  `orderTotal` float NOT NULL,
  `orderComment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_info`
--

INSERT INTO `order_info` (`orderID`, `accountID`, `orderDate`, `orderTotal`, `orderComment`) VALUES
(1, 1, '2024-05-23', 100, NULL),
(2, 1, '2024-05-23', 469, NULL),
(3, 1, '2024-05-23', 3413, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderID` int(11) NOT NULL,
  `itemID` varchar(4) NOT NULL,
  `itemAmount` int(11) NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderID`, `itemID`, `itemAmount`, `totalPrice`) VALUES
(1, 'CTTN', 2, 100),
(2, 'BDGB', 3, 369),
(2, 'CTTN', 2, 100),
(3, 'BAND', 14, 280),
(3, 'BDGA', 11, 550),
(3, 'BDGB', 21, 2583);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`accountID`);

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `order_info`
--
ALTER TABLE `order_info`
  ADD PRIMARY KEY (`orderID`),
  ADD KEY `order_info_ibfk_1` (`accountID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`orderID`,`itemID`),
  ADD KEY `itemID` (`itemID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_info`
--
ALTER TABLE `order_info`
  ADD CONSTRAINT `order_info_ibfk_1` FOREIGN KEY (`accountID`) REFERENCES `accounts` (`accountID`);

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order_info` (`orderID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
