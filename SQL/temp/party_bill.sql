-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2018 at 02:29 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `yearstech_arabbd_temp`
--

-- --------------------------------------------------------

--
-- Table structure for table `party_bill`
--

CREATE TABLE `party_bill` (
  `bill_id` int(10) NOT NULL,
  `party_id` int(10) NOT NULL,
  `voucher_id` int(10) NOT NULL,
  `account_id` int(10) NOT NULL,
  `purpose` varchar(200) NOT NULL,
  `type` varchar(10) NOT NULL,
  `deails` varchar(200) NOT NULL,
  `total` int(11) NOT NULL,
  `paid` int(11) NOT NULL,
  `due` int(11) NOT NULL,
  `date` date NOT NULL,
  `added` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `party_bill`
--

INSERT INTO `party_bill` (`bill_id`, `party_id`, `voucher_id`, `account_id`, `purpose`, `type`, `deails`, `total`, `paid`, `due`, `date`, `added`) VALUES
(1, 0, 0, 0, '', '', '', 0, 0, 0, '0000-00-00', '2018-06-14 16:32:19'),
(2, 1001, 123, 1001, 'Advertisement', 'Deposit', '46', 100, 100, 0, '2018-06-14', '2018-06-14 16:46:56'),
(3, 1001, 123, 1005, 'Advertisement', 'Cost', '4654', 500, 100, 400, '2018-06-14', '2018-06-14 18:19:58');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `party_bill`
--
ALTER TABLE `party_bill`
  ADD PRIMARY KEY (`bill_id`),
  ADD KEY `purpose` (`purpose`),
  ADD KEY `account_id` (`account_id`),
  ADD KEY `party_id` (`party_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `party_bill`
--
ALTER TABLE `party_bill`
  MODIFY `bill_id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
