-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 05, 2024 at 04:49 PM
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
  `type` varchar(5) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`accountID`, `name`, `password`, `phonenumber`, `emailaddress`, `type`) VALUES
(1, 'admin1', '$2y$10$bUunUstloQkUtcCIdxlhi.SyUQpJpcdRVyCGnhPPfokCmfOEavwry', 123, 'admin1@gmail.com', 'admin'),
(2, 'admin2', '$2y$10$xZg1wpxIPGsVAYmJ.sEyQ./IYsrwxt8gslm7vBj5HYuKt.4IjqnOC', 123, 'admin2@gmail.com', 'admin'),
(3, 'Rudeus Greyrat', '$2y$10$UdWQZV.TslsyLGRBHOw7Q.nk6xBpuWE0gV6BI6IxEFS2UcAzC34m6', 123, 'sad@gmail.com', 'user');

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` varchar(4) NOT NULL,
  `itemPrice` double NOT NULL,
  `itemName` varchar(30) NOT NULL,
  `itemStock` int(11) NOT NULL,
  `soldAmount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `items`
--

INSERT INTO `items` (`itemID`, `itemPrice`, `itemName`, `itemStock`, `soldAmount`) VALUES
('BAND', 20, 'ID Bands', 8995, 1850),
('BDGA', 50, 'Adhesive Bandage', 0, 3434),
('BDGB', 123, 'Elastic Bandage', 1995, 1438),
('CTTN', 50, 'Cotton Applicators', 2450, 2052),
('FACE', 15, 'Face Masks', 4950, 1075),
('GAUZ', 100, 'Gauze', 0, 15),
('GLOV', 75, 'Gloves', 42, 850),
('HEAD', 10, 'Head Caps', 43, 1043),
('SHOE', 35, 'Shoe Covers', 50, 50),
('SNTZ', 100, 'Hand Sanitizer', 46, 54),
('SPLA', 300, 'Wrist Splints', 44, 56),
('SPLB', 20, 'Traction Splints', 50, 50),
('STSC', 100, 'Stethoscope', 50, 100050),
('THRM', 213, 'Thermometer', 36, 61),
('TISS', 20, 'Tissues', 35, 4712),
('UNDR', 150, 'Under Pads', 45, 55);

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `orderID` int(11) NOT NULL,
  `accountID` int(11) NOT NULL,
  `orderAddress` varchar(200) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTotal` double NOT NULL,
  `orderStatus` varchar(20) NOT NULL DEFAULT 'processing',
  `orderPWD` varchar(50) DEFAULT NULL,
  `orderSeniorCitizen` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_info`
--

INSERT INTO `order_info` (`orderID`, `accountID`, `orderAddress`, `orderDate`, `orderTotal`, `orderStatus`, `orderPWD`, `orderSeniorCitizen`) VALUES
(45, 1, 'street, barangay city, province, 1234', '2024-06-02', 33808.824, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: NO SC'),
(46, 1, 'street, barangay city, province, 1234', '2024-06-02', 19837.44, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: NO SC'),
(47, 1, 'street, barangay city, province, 1234', '2024-06-02', 22400, 'processing', 'PWD ID: NO PWD', 'Senior Citizen ID: NO SC'),
(48, 1, 'streetsdadad, asdasd dsadsa, saddsa, adsdas', '2024-06-02', 44083.2, 'processing', 'PWD ID: 55555555', 'Senior Citizen ID: 333333333'),
(49, 1, 'street, barangay city, province, 1234', '2024-06-02', 14336, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: sc num'),
(50, 1, 'street, barangay city, province, 1234', '2024-06-02', 4462.08, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: sc num'),
(51, 1, 'street, barangay city, province, 1234', '2024-06-03', 47797.12, 'delivered', 'PWD ID: pwd id num', 'Senior Citizen ID: sc num'),
(52, 1, 'street, barangay city, province, 1234', '2024-06-04', 50.4, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: NO SC'),
(53, 1, 'street, barangay city, province, 1234', '2024-06-05', 32040.96, 'processing', 'PWD ID: pwd id num', 'Senior Citizen ID: sc num');

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
(45, 'BDGA', 95, 4512.5),
(45, 'BDGB', 295, 29028),
(46, 'BDGB', 200, 19680),
(47, 'CTTN', 500, 20000),
(48, 'BDGB', 500, 49200),
(49, 'BAND', 1000, 16000),
(50, 'FACE', 415, 4980),
(51, 'BAND', 5, 100),
(51, 'BDGA', 5, 250),
(51, 'BDGB', 5, 615),
(51, 'CTTN', 50, 2375),
(51, 'FACE', 50, 712.5),
(51, 'GAUZ', 7, 700),
(51, 'GLOV', 50, 3562.5),
(51, 'HEAD', 50, 475),
(51, 'SHOE', 50, 1662.5),
(51, 'SNTZ', 50, 4750),
(51, 'SPLA', 50, 14250),
(51, 'SPLB', 50, 950),
(51, 'STSC', 50, 4750),
(51, 'THRM', 50, 10117.5),
(51, 'TISS', 50, 950),
(51, 'UNDR', 50, 7125),
(52, 'BDGA', 1, 50),
(53, 'BDGA', 894, 35760);

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
(45, 'John Smith', 9223523234, '963852741'),
(46, 'John Smith', 9223523234, '963852741'),
(47, 'John Smith', 9223523234, '963852741'),
(48, 'arcilla', 9999999999, '999999'),
(49, 'John Smith', 9223523234, '963852741'),
(50, 'John Smith', 9223523234, '963852741'),
(51, 'John Smith', 9223523234, '963852741'),
(52, 'John Smith', 9223523234, '963852741'),
(53, 'John Smith', 9223523234, '963852741');

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
  MODIFY `accountID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=54;

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
