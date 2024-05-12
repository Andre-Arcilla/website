-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 12, 2024 at 06:10 PM
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
-- Database: `dct`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user` varchar(20) NOT NULL,
  `password` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `items`
--

CREATE TABLE `items` (
  `itemID` varchar(5) NOT NULL,
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
('BAND', 50, 450, 'ID Bands', 100, 10),
('CTTN', 50, 450, 'Cotton Applicators', 100, 10),
('FACE', 15, 300, 'Face Masks', 100, 25),
('GLOV', 50, 250, 'Gloves', 100, 12),
('HEAD', 50, 250, 'Head Caps', 100, 0),
('SHOE', 50, 250, 'Shoe Covers', 100, 0),
('SPLT', 50, 250, 'Splints', 100, 0),
('TISS', 50, 250, 'Tissues', 100, 0),
('UNDR', 50, 250, 'Under Pads', 100, 0);

-- --------------------------------------------------------

--
-- Table structure for table `order_info`
--

CREATE TABLE `order_info` (
  `trackingNum` int(11) NOT NULL,
  `orderID` int(11) NOT NULL,
  `customerName` varchar(100) NOT NULL,
  `orderAddress` varchar(100) NOT NULL,
  `orderEmail` varchar(100) NOT NULL,
  `orderPNum` int(11) NOT NULL,
  `orderDate` date NOT NULL,
  `orderTotal` float NOT NULL,
  `orderComment` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_info`
--

INSERT INTO `order_info` (`trackingNum`, `orderID`, `customerName`, `orderAddress`, `orderEmail`, `orderPNum`, `orderDate`, `orderTotal`, `orderComment`) VALUES
(123452, 3, 'Jane Doe', 'qfdsfg2fwdqfq3r', 'd2wecwqfd', 909090909, '2024-05-01', 211, NULL),
(123456798, 1, 'John Smith', '123 example st example city', 'example@email.com', 909090909, '2024-05-01', 999, NULL),
(312310000, 2, 'Mike Smith', '2131 sad12', '123#asdqs1', 909090909, '2024-05-01', 200, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `orderID` int(11) NOT NULL,
  `itemID` varchar(4) NOT NULL,
  `bulk` varchar(10) NOT NULL,
  `itemAmount` int(11) NOT NULL,
  `totalPrice` double NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`orderID`, `itemID`, `bulk`, `itemAmount`, `totalPrice`) VALUES
(1, 'BAND', 'n', 21, 500),
(2, 'CTTN', 'n', 21, 200),
(1, 'FACE', 'y', 21, 200),
(3, 'GLOV', 'n', 12, 211),
(1, 'TISS', 'n', 3, 299);

--
-- Triggers `order_items`
--
DELIMITER $$
CREATE TRIGGER `update_order_total` AFTER INSERT ON `order_items` FOR EACH ROW BEGIN
    UPDATE order_info oi
    SET oi.orderTotal = (
        SELECT SUM(totalPrice)
        FROM order_items
        WHERE orderID = NEW.orderID
    )
    WHERE oi.orderID = NEW.orderID;
END
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `update_order_total_on_update` AFTER UPDATE ON `order_items` FOR EACH ROW BEGIN
    UPDATE order_info oi
    SET oi.orderTotal = (
        SELECT SUM(totalPrice)
        FROM order_items
        WHERE orderID = NEW.orderID
    )
    WHERE oi.orderID = NEW.orderID;
END
$$
DELIMITER ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `items`
--
ALTER TABLE `items`
  ADD PRIMARY KEY (`itemID`);

--
-- Indexes for table `order_info`
--
ALTER TABLE `order_info`
  ADD PRIMARY KEY (`trackingNum`),
  ADD UNIQUE KEY `orderID` (`orderID`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD UNIQUE KEY `itemID` (`itemID`),
  ADD KEY `orderID` (`orderID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `order_info`
--
ALTER TABLE `order_info`
  MODIFY `orderID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`itemID`) REFERENCES `items` (`itemID`),
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`orderID`) REFERENCES `order_info` (`orderID`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
