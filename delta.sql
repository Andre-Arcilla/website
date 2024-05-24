-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 24, 2024 at 05:56 PM
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
(1, 'Simp4Suisei123', '$2y$10$0lBECBVGNRSnilAkfAp9Jeele3y1JLGqmGGbQvdGQ.yhPa3v.IwrW', 123, 'hoshimachi.suisei@gmail.com', 'admin', '659 Hagenes Orchard, Port Terrellton, IN');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` varchar(4) NOT NULL,
  `itemPrice` double NOT NULL,
  `itemName` varchar(30) NOT NULL,
  `itemStock` int(11) NOT NULL,
  `soldAmount` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemPrice`, `itemName`, `itemStock`, `soldAmount`) VALUES
('BAND', 20, 'ID Bands', 97, 3),
('BDGA', 50, 'Adhesive Bandage', 142, 1096),
('BDGB', 123, 'Elastic Bandage', 95, 5),
('CTTN', 50, 'Cotton Applicators', 94, 2),
('FACE', 15, 'Face Masks', 100, 465),
('GAUZ', 100, 'Gauze', 15, 0),
('GLOV', 75, 'Gloves', 95, 5),
('HEAD', 10, 'Head Caps', 97, 989),
('SHOE', 35, 'Shoe Covers', 100, 0),
('SNTZ', 100, 'Hand Sanitizer', 100, 0),
('SPLA', 300, 'Wrist Splints', 100, 0),
('SPLB', 20, 'Traction Splints', 100, 0),
('STSC', 100, 'Stethoscope', 100, 0),
('THRM', 213, 'Thermometer', 87, 0),
('TISS', 20, 'Tissues', 94, 4653),
('UNDR', 150, 'Under Pads', 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `orderID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTotal` float NOT NULL,
  `orderStatus` varchar(20) NOT NULL DEFAULT 'processing'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_info`
--

INSERT INTO `order_info` (`orderID`, `accountID`, `orderDate`, `orderTotal`, `orderStatus`) VALUES
(11, 1, '2024-05-24', 100, 'cancelled'),
(12, 1, '2024-05-24', 100, 'cancelled'),
(13, 1, '2024-05-24', 100, 'processing');

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
(11, 'BDGA', 2, 100),
(12, 'BDGA', 2, 100),
(13, 'CTTN', 2, 100);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `orderID` int(11) NOT NULL,
  `gcashName` varchar(100) NOT NULL,
  `gcashNumber` bigint(20) NOT NULL,
  `gcashReferenceNum` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`orderID`, `gcashName`, `gcashNumber`, `gcashReferenceNum`) VALUES
(11, 'Suisei Hoshimachi', 123456, '963852741'),
(12, 'Suisei Hoshimachi', 123456, '963852741'),
(13, 'Suisei Hoshimachi', 123456, '963852741');

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
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`orderID`);

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
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

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

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`orderID`) REFERENCES `order_info` (`orderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
