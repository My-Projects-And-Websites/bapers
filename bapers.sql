-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 17, 2021 at 12:02 AM
-- Server version: 5.7.26
-- PHP Version: 7.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `test`
--

-- --------------------------------------------------------

--
-- Table structure for table `cardpayment`
--

CREATE TABLE `cardpayment` (
  `card_num` char(16) COLLATE utf8_unicode_ci NOT NULL,
  `cardholder_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `card_expiry` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `card_cvv` char(3) COLLATE utf8_unicode_ci NOT NULL,
  `Paymentpayment_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE `customer` (
  `cust_id` int(11) NOT NULL,
  `cust_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_sname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_email` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `cust_type` tinyint(1) NOT NULL,
  `cust_mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
  `discount_plan` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `payment_type` tinyint(1) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job`
--

CREATE TABLE `job` (
  `job_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `job_urgency` int(1) DEFAULT NULL,
  `job_deadline` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `special_instructions` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `job_status` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `expected_finish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `actual_finish` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Customercust_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_task`
--

CREATE TABLE `job_task` (
  `JobTaskID` int(11) NOT NULL,
  `Jobjob_id` int(10) NOT NULL,
  `Tasktask_id` int(10) NOT NULL,
  `start_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `task_date` date DEFAULT NULL,
  `task_status` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `message_id` int(11) NOT NULL,
  `message_desc` text COLLATE utf8_unicode_ci NOT NULL,
  `message_status` tinyint(1) NOT NULL,
  `send_time` datetime NOT NULL,
  `Staffstaff_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `payment_id` int(11) NOT NULL,
  `payment_total` double NOT NULL,
  `payment_late` tinyint(1) NOT NULL,
  `Customercust_id` int(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `staff`
--

CREATE TABLE `staff` (
  `staff_id` int(11) NOT NULL,
  `staff_fname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_sname` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_role` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `staff_department` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `total_time` time NOT NULL,
  `username_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `password_login` varchar(255) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `task_id` int(11) NOT NULL,
  `task_desc` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_location` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `task_price` double NOT NULL,
  `task_duration` time NOT NULL,
  `Jobjob_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `Staffstaff_id` int(10) NOT NULL,
  `start_time` time NOT NULL,
  `task_date` date NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cardpayment`
--
ALTER TABLE `cardpayment`
  ADD PRIMARY KEY (`card_num`,`Paymentpayment_id`),
  ADD UNIQUE KEY `card_num` (`card_num`),
  ADD KEY `Paymentpayment_id` (`Paymentpayment_id`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
  ADD PRIMARY KEY (`cust_id`);

--
-- Indexes for table `job`
--
ALTER TABLE `job`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `Customercust_id` (`Customercust_id`);

--
-- Indexes for table `job_task`
--
ALTER TABLE `job_task`
  ADD PRIMARY KEY (`JobTaskID`),
  ADD KEY `Jobjob_id` (`Jobjob_id`),
  ADD KEY `Tasktask_id` (`Tasktask_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `Staffstaff_id` (`Staffstaff_id`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `Customercust_id` (`Customercust_id`);

--
-- Indexes for table `staff`
--
ALTER TABLE `staff`
  ADD PRIMARY KEY (`staff_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`task_id`),
  ADD KEY `Jobjob_id` (`Jobjob_id`),
  ADD KEY `Staffstaff_id` (`Staffstaff_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
  MODIFY `cust_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `job_task`
--
ALTER TABLE `job_task`
  MODIFY `JobTaskID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payment`
--
ALTER TABLE `payment`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `staff`
--
ALTER TABLE `staff`
  MODIFY `staff_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `task_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
