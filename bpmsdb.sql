-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 02:54 AM
-- Server version: 10.4.27-MariaDB
-- PHP Version: 8.0.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `bpmsdb`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbladmin`
--

CREATE TABLE `tbladmin` (
  `ID` int(10) NOT NULL,
  `AdminName` char(50) DEFAULT NULL,
  `UserName` char(50) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `Password` varchar(200) DEFAULT NULL,
  `AdminRegdate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbladmin`
--

INSERT INTO `tbladmin` (`ID`, `AdminName`, `UserName`, `MobileNumber`, `Email`, `Password`, `AdminRegdate`) VALUES
(1, 'Admin', 'admin', 9100200300, 'tester1@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-10-19 06:21:50');

-- --------------------------------------------------------

--
-- Table structure for table `tblappointment`
--

CREATE TABLE `tblappointment` (
  `ID` int(10) NOT NULL,
  `AptNumber` varchar(80) DEFAULT NULL,
  `Name` varchar(120) DEFAULT NULL,
  `Email` varchar(120) DEFAULT NULL,
  `PhoneNumber` bigint(11) DEFAULT NULL,
  `AptDate` varchar(120) DEFAULT NULL,
  `AptTime` varchar(120) DEFAULT NULL,
  `Services` varchar(120) DEFAULT NULL,
  `ApplyDate` timestamp NULL DEFAULT current_timestamp(),
  `Remark` varchar(250) NOT NULL,
  `Status` varchar(50) NOT NULL,
  `RemarkDate` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `Stylist` varchar(250) NOT NULL,
  `UserID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblappointment`
--

INSERT INTO `tblappointment` (`ID`, `AptNumber`, `Name`, `Email`, `PhoneNumber`, `AptDate`, `AptTime`, `Services`, `ApplyDate`, `Remark`, `Status`, `RemarkDate`, `Stylist`, `UserID`) VALUES
(1, '806208348', 'jc azores', 'jcazores@gmail.com', 913136546, '2025-03-13', '09:30', 'Haircut with Shampoo and Blowdry', '2025-03-13 00:07:58', '', '', '2025-03-13 00:07:58', 'Mari Chu', 0),
(2, '495234732', 'jc azores', 'jcazores@gmail.com', 934565465, '2025-03-14', '09:30', 'Haircut with Blowdry', '2025-03-13 00:11:20', '', '', '2025-03-13 00:11:20', 'Mari Chu', 2),
(3, '162115309', 'ralph Enisimo', 'enisimo@gmail.com', 987498787, '2025-03-14', '09:00', 'Haircut with Shampoo and Blowdry', '2025-03-13 00:14:01', '', '', '2025-03-13 00:14:01', 'Mari Chu', 3),
(4, '301990601', 'ralph Enisimo', 'enisimo@gmail.com', 987498787, '2025-03-14', '13:00', 'Hair Cut', '2025-03-13 00:14:52', '', '', '2025-03-13 00:14:52', 'Mari Chu', 3),
(5, '249320541', 'jc azores', 'jcazores@gmail.com', 946546546, '2025-03-13', '09:00', 'Pedicure', '2025-03-13 00:22:51', '', '', '2025-03-13 00:22:51', 'Mari Chu', 2),
(6, '278648203', 'jc azores', 'jcazores@gmail.com', 987498787, '2025-03-13', '12:00', 'Manicure', '2025-03-13 00:26:02', '', '', '2025-03-13 00:26:02', 'Norma Beunaflor', 2),
(7, '453967413', 'jc azores', 'jcazores@gmail.com', 987498787, '2025-03-13', '12:00', 'Manicure', '2025-03-13 00:26:34', '', '', '2025-03-13 00:26:34', 'Norma Beunaflor', 2),
(8, '438583538', 'jc azores', 'jcazores@gmail.com', 987498787, '2025-03-13', '12:00', 'Manicure', '2025-03-13 00:26:54', '', '', '2025-03-13 00:26:54', 'Norma Beunaflor', 2),
(9, '106104810', 'ralph Enisimo', 'enisimo@gmail.com', 987498787, '2025-03-14', '16:00', 'Manicure', '2025-03-13 00:27:19', '', '', '2025-03-13 00:27:19', 'Mari Chu', 3);

-- --------------------------------------------------------

--
-- Table structure for table `tblcustomers`
--

CREATE TABLE `tblcustomers` (
  `ID` int(10) NOT NULL,
  `Name` varchar(120) DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` bigint(11) DEFAULT NULL,
  `Gender` enum('Female','Male','Transgender') DEFAULT NULL,
  `Details` mediumtext DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblcustomers`
--

INSERT INTO `tblcustomers` (`ID`, `Name`, `Email`, `MobileNumber`, `Gender`, `Details`, `CreationDate`, `UpdationDate`) VALUES
(6, 'leih', 'leih@gmail.com', 912345678, 'Female', 'haircut', '2024-11-19 06:42:17', NULL),
(7, 'gio', 'gio@gmail.com', 945679891, 'Male', 'sdasdas', '2024-11-19 08:43:20', NULL),
(8, 'Ryan', 'ryan@gmail.com', 982165462, 'Male', 'pakikalbo', '2024-11-19 09:24:50', NULL),
(9, 'jeycee', 'jeycee@gmail.com', 9568839281, 'Male', 'qweqeqwe', '2024-11-19 09:47:13', NULL),
(10, 'mikka', 'mikka@gmail.com', 9664470622, 'Female', 'qwerty', '2024-11-24 14:17:13', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblinvoice`
--

CREATE TABLE `tblinvoice` (
  `id` int(11) NOT NULL,
  `Userid` int(11) DEFAULT NULL,
  `ServiceId` int(11) DEFAULT NULL,
  `BillingId` int(11) DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblinvoice`
--

INSERT INTO `tblinvoice` (`id`, `Userid`, `ServiceId`, `BillingId`, `PostingDate`) VALUES
(19, 7, 18, 681496499, '2024-11-19 06:39:09'),
(20, 6, 1, 255869626, '2024-11-19 06:42:27'),
(21, 6, 2, 499730880, '2024-11-19 06:42:44'),
(22, 6, 3, 499730880, '2024-11-19 06:42:44'),
(23, 7, 20, 947627921, '2024-11-19 09:23:34'),
(24, 7, 21, 947627921, '2024-11-19 09:23:34'),
(25, 8, 17, 356390553, '2024-11-19 09:25:13'),
(26, 9, 4, 214497137, '2024-11-19 09:47:22'),
(27, 10, 6, 299316746, '2024-11-24 14:18:05'),
(28, 10, 12, 299316746, '2024-11-24 14:18:05'),
(29, 10, 6, 628047013, '2024-11-24 14:19:54'),
(30, 10, 19, 628047013, '2024-11-24 14:19:54'),
(31, 10, 1, 329939449, '2024-11-24 14:26:36'),
(32, 10, 2, 329939449, '2024-11-24 14:26:36'),
(33, 10, 3, 329939449, '2024-11-24 14:26:36'),
(34, 6, 6, 282660526, '2024-11-24 14:28:16'),
(35, 6, 7, 282660526, '2024-11-24 14:28:16'),
(36, 6, 19, 282660526, '2024-11-24 14:28:16'),
(37, 7, 3, 585499452, '2024-11-24 14:29:58'),
(38, 7, 4, 585499452, '2024-11-24 14:29:58'),
(39, 7, 5, 585499452, '2024-11-24 14:29:58'),
(40, 8, 7, 212498577, '2024-11-24 14:30:19'),
(41, 8, 8, 212498577, '2024-11-24 14:30:19'),
(42, 8, 10, 212498577, '2024-11-24 14:30:19'),
(43, 9, 6, 738306091, '2024-11-24 14:32:10'),
(44, 9, 7, 738306091, '2024-11-24 14:32:10'),
(45, 9, 9, 738306091, '2024-11-24 14:32:10'),
(46, 9, 19, 738306091, '2024-11-24 14:32:10');

-- --------------------------------------------------------

--
-- Table structure for table `tblpage`
--

CREATE TABLE `tblpage` (
  `ID` int(10) NOT NULL,
  `PageType` varchar(200) DEFAULT NULL,
  `PageTitle` mediumtext DEFAULT NULL,
  `PageDescription` mediumtext DEFAULT NULL,
  `Email` varchar(200) DEFAULT NULL,
  `MobileNumber` varchar(30) DEFAULT NULL,
  `UpdationDate` date DEFAULT NULL,
  `Timing` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpage`
--

INSERT INTO `tblpage` (`ID`, `PageType`, `PageTitle`, `PageDescription`, `Email`, `MobileNumber`, `UpdationDate`, `Timing`) VALUES
(1, 'aboutus', 'About Us', '        Welcome to Rosa Flora Beauty Salon, where beauty meets excellence! We offer top-tier beauty and wellness services in a warm, luxurious space. From haircuts to facials, our experts help you look and feel your best.', 'rosaflorabeautysalon@gmail.com', NULL, NULL, ''),
(2, 'contactus', 'Contact Us', '133 damong maliit st. novaliches, Quezon City, Philippines', 'rosaflorabeautysalon@gmail.com', '0917-694-3931/0976-569-4619', NULL, '9:00 am to 8:00 pm');

-- --------------------------------------------------------

--
-- Table structure for table `tblservices`
--

CREATE TABLE `tblservices` (
  `ID` int(10) NOT NULL,
  `ServiceName` varchar(200) DEFAULT NULL,
  `Cost` varchar(15) DEFAULT NULL,
  `CreationDate` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblservices`
--

INSERT INTO `tblservices` (`ID`, `ServiceName`, `Cost`, `CreationDate`) VALUES
(1, 'Haircut with Blowdry', '150', '2024-10-20 11:22:38'),
(2, 'Haircut with Shampoo and Blowdry', '250', '2024-10-20 11:22:38'),
(3, 'Hair Color with Hair Mask', '350', '2024-10-20 11:22:38'),
(4, 'Hair Cut', '300', '2024-10-20 11:22:38'),
(5, 'Cellophane with Hair Mask', '500', '2024-10-20 11:22:38'),
(6, 'Brazilian Hair Botox with Hair Mask', '1300', '2024-10-20 11:22:38'),
(7, 'Rebond with Cellophaneand Hair Mask', '2400', '2024-10-20 11:22:38'),
(8, 'Perm(Kulot) with Hair Mask', '500', '2024-10-20 11:22:38'),
(9, 'Highlight + Hair Color + Hair Treatment', '2000', '2024-10-20 11:22:38'),
(10, 'Manicure', '150', '2024-10-20 11:22:38'),
(11, 'Pedicure', '150', '2024-10-20 11:22:38'),
(12, 'Foot Spa with Pedicure', '400', '2024-10-20 11:22:38'),
(14, 'Paraffin Wax', '350', '2024-10-20 11:22:38'),
(15, 'Flat Iron', '250', '2024-10-20 11:22:38'),
(16, 'Semi Kalbo', '90', '2024-10-22 01:48:02'),
(17, 'Mid Fade', '120', '2024-10-22 09:40:14'),
(18, 'Curl Setting', '500', '2024-11-18 15:24:16'),
(19, 'Hair and Make-Up', '1000', '2024-11-18 15:24:47'),
(20, 'PACKAGE 1- Rebond + Brazilian Botox + Haircut + Hair Mask Treatment', '2500', '2024-11-18 15:26:47'),
(21, 'PACKAGE 2- Highlights + Hair Color + Haircut + Brazilian Botox', '3000', '2024-11-18 15:27:50'),
(22, 'PACKAGE 3- Manicure + Pedicure + Foot Spa + Paraffin Wax (Foot)', '800', '2024-11-18 15:28:40'),
(26, 'shane', '250', '2024-11-24 11:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `tblstylist`
--

CREATE TABLE `tblstylist` (
  `ID` int(11) NOT NULL,
  `StylistName` varchar(100) NOT NULL,
  `CreationDate` timestamp NOT NULL DEFAULT current_timestamp(),
  `Age` int(15) NOT NULL,
  `Salary` int(30) NOT NULL,
  `Day Off` varchar(250) NOT NULL,
  `Year of Experience` varchar(30) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblstylist`
--

INSERT INTO `tblstylist` (`ID`, `StylistName`, `CreationDate`, `Age`, `Salary`, `Day Off`, `Year of Experience`) VALUES
(1, 'Mari Chu', '2024-10-22 09:28:12', 25, 250, 'Tuesday', '2 years'),
(2, 'Norma Beunaflor', '2024-10-22 09:28:12', 30, 250, 'Wednesday', '5 years'),
(3, 'Rose Dela Cruz', '2024-10-22 09:28:12', 27, 250, 'Friday', '4 years');

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `ID` int(11) NOT NULL,
  `UserName` varchar(50) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `Email` varchar(100) NOT NULL,
  `DateCreated` timestamp NOT NULL DEFAULT current_timestamp(),
  `LastLogin` timestamp NULL DEFAULT NULL,
  `Status` enum('active','inactive') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`ID`, `UserName`, `Password`, `Email`, `DateCreated`, `LastLogin`, `Status`) VALUES
(1, 'Jcsaminiano_1', '3a462b3dde74d162099f229dae6036fc', 'saminiano@gmail.com', '2025-02-13 00:39:48', NULL, 'active'),
(2, 'Jc_azores', 'c8230af0603a9f1f0e64e2a3412f36d3', 'jcazores@gmail.com', '2025-03-12 23:11:36', NULL, 'active'),
(3, 'Rc_enisimo', 'cf5710776c9a46e97f33c2ca1e4fdd4a', 'enisimo@gmail.com', '2025-03-13 00:12:44', NULL, 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tbladmin`
--
ALTER TABLE `tbladmin`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblappointment`
--
ALTER TABLE `tblappointment`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblinvoice`
--
ALTER TABLE `tblinvoice`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `tblpage`
--
ALTER TABLE `tblpage`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblservices`
--
ALTER TABLE `tblservices`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblstylist`
--
ALTER TABLE `tblstylist`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `UserName` (`UserName`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tbladmin`
--
ALTER TABLE `tbladmin`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblappointment`
--
ALTER TABLE `tblappointment`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblcustomers`
--
ALTER TABLE `tblcustomers`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tblinvoice`
--
ALTER TABLE `tblinvoice`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `tblpage`
--
ALTER TABLE `tblpage`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tblservices`
--
ALTER TABLE `tblservices`
  MODIFY `ID` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `tblstylist`
--
ALTER TABLE `tblstylist`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `ID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
