-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 18, 2021 at 01:37 PM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hamzcad`
--

-- --------------------------------------------------------

--
-- Table structure for table `911call`
--

CREATE TABLE `911call` (
  `ID` int(255) NOT NULL,
  `info` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `activecalls`
--

CREATE TABLE `activecalls` (
  `ID` int(255) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `calltype` varchar(1024) NOT NULL,
  `location` varchar(1024) NOT NULL,
  `postal` varchar(1024) NOT NULL,
  `narrative` text NOT NULL,
  `attachedunits` text NOT NULL,
  `status` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `arrests`
--

CREATE TABLE `arrests` (
  `ID` int(255) NOT NULL,
  `civid` varchar(1024) NOT NULL,
  `civname` varchar(1024) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `arresttype` varchar(1024) NOT NULL,
  `reason` text NOT NULL,
  `fine` varchar(1024) NOT NULL,
  `jailtime` varchar(1024) NOT NULL,
  `note` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bans`
--

CREATE TABLE `bans` (
  `ID` int(255) NOT NULL,
  `discordid` varchar(1024) NOT NULL,
  `name` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `bolos`
--

CREATE TABLE `bolos` (
  `ID` int(255) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `type` varchar(1024) NOT NULL,
  `details` text NOT NULL,
  `plate` varchar(255) DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `characters`
--

CREATE TABLE `characters` (
  `ID` int(255) NOT NULL,
  `discordid` varchar(1024) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `dob` varchar(1024) NOT NULL,
  `haircolor` varchar(1024) NOT NULL,
  `address` varchar(1024) NOT NULL,
  `gender` varchar(1024) NOT NULL,
  `race` varchar(1024) NOT NULL,
  `build` varchar(1024) NOT NULL,
  `occupation` varchar(1024) NOT NULL DEFAULT 'Unemployed',
  `ssn` varchar(1024) NOT NULL,
  `image` varchar(1024) NOT NULL,
  `dead` int(1) NOT NULL DEFAULT 0,
  `driverspoints` int(255) NOT NULL DEFAULT 0,
  `drivers` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `weapons` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `hunting` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `fishing` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `commercial` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `boating` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `aviation` varchar(1024) NOT NULL DEFAULT 'Unobtained',
  `bloodtype` varchar(1024) NOT NULL DEFAULT 'Unknown',
  `emergcontact` varchar(1024) NOT NULL DEFAULT 'None',
  `allergies` varchar(1024) DEFAULT 'None',
  `medication` varchar(1024) DEFAULT 'None',
  `organdonor` int(1) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `citations`
--

CREATE TABLE `citations` (
  `ID` int(255) NOT NULL,
  `civid` varchar(1024) NOT NULL,
  `civname` varchar(1024) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `offences` text NOT NULL,
  `fine` int(11) NOT NULL,
  `note` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `divisions`
--

CREATE TABLE `divisions` (
  `ID` int(255) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `type` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `gallery`
--

CREATE TABLE `gallery` (
  `ID` int(255) NOT NULL,
  `link` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `medicalrecords`
--

CREATE TABLE `medicalrecords` (
  `ID` int(255) NOT NULL,
  `civid` varchar(1024) NOT NULL,
  `civname` varchar(1024) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `details` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `ID` int(255) NOT NULL,
  `roleid` varchar(1024) NOT NULL,
  `permission` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `ID` int(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `discordid` varchar(255) NOT NULL,
  `avatar` varchar(400) NOT NULL,
  `identifier` varchar(1024) DEFAULT NULL,
  `currdept` varchar(1024) NOT NULL DEFAULT 'None',
  `currdivision` varchar(1024) NOT NULL DEFAULT 'None',
  `currapparatus` varchar(1024) NOT NULL DEFAULT 'None',
  `currstatus` varchar(1024) NOT NULL DEFAULT '10-7',
  `currpanic` int(11) NOT NULL DEFAULT 0,
  `currpaniclocation` varchar(1024) DEFAULT NULL,
  `currsignal` int(11) NOT NULL DEFAULT 0,
  `currsound` int(11) NOT NULL,
  `currping` int(11) NOT NULL DEFAULT 0,
  `currfiretone` int(1) NOT NULL DEFAULT 0,
  `notepad` text NOT NULL,
  `showsupervisor` int(11) NOT NULL DEFAULT 0
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `vehicles`
--

CREATE TABLE `vehicles` (
  `ID` int(255) NOT NULL,
  `discordid` varchar(1024) NOT NULL,
  `charid` varchar(1024) NOT NULL,
  `plate` varchar(1024) NOT NULL,
  `makemodel` varchar(1024) NOT NULL,
  `color` varchar(1024) NOT NULL,
  `insurance` varchar(1024) NOT NULL,
  `regstate` varchar(1024) NOT NULL,
  `flags` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warnings`
--

CREATE TABLE `warnings` (
  `ID` int(255) NOT NULL,
  `civid` varchar(1024) NOT NULL,
  `civname` varchar(1024) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `offences` text NOT NULL,
  `note` text NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `warrants`
--

CREATE TABLE `warrants` (
  `ID` int(255) NOT NULL,
  `civid` varchar(1024) NOT NULL,
  `unitidentifier` varchar(1024) NOT NULL,
  `unitdiscordid` varchar(1024) NOT NULL,
  `date` varchar(1024) NOT NULL,
  `time` varchar(1024) NOT NULL,
  `details` text NOT NULL,
  `requestingunit` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `weapons`
--

CREATE TABLE `weapons` (
  `ID` int(255) NOT NULL,
  `discordid` varchar(1024) NOT NULL,
  `charid` varchar(1024) NOT NULL,
  `type` varchar(1024) NOT NULL,
  `name` varchar(1024) NOT NULL,
  `serialnumber` varchar(1024) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `911call`
--
ALTER TABLE `911call`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `activecalls`
--
ALTER TABLE `activecalls`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `arrests`
--
ALTER TABLE `arrests`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `bans`
--
ALTER TABLE `bans`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `bolos`
--
ALTER TABLE `bolos`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `characters`
--
ALTER TABLE `characters`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `citations`
--
ALTER TABLE `citations`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `divisions`
--
ALTER TABLE `divisions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `gallery`
--
ALTER TABLE `gallery`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `medicalrecords`
--
ALTER TABLE `medicalrecords`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`ID`),
  ADD UNIQUE KEY `steamid` (`discordid`);

--
-- Indexes for table `vehicles`
--
ALTER TABLE `vehicles`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `warnings`
--
ALTER TABLE `warnings`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `warrants`
--
ALTER TABLE `warrants`
  ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `weapons`
--
ALTER TABLE `weapons`
  ADD PRIMARY KEY (`ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `911call`
--
ALTER TABLE `911call`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `activecalls`
--
ALTER TABLE `activecalls`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `arrests`
--
ALTER TABLE `arrests`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `bans`
--
ALTER TABLE `bans`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `bolos`
--
ALTER TABLE `bolos`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `characters`
--
ALTER TABLE `characters`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `citations`
--
ALTER TABLE `citations`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `divisions`
--
ALTER TABLE `divisions`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `gallery`
--
ALTER TABLE `gallery`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `medicalrecords`
--
ALTER TABLE `medicalrecords`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `vehicles`
--
ALTER TABLE `vehicles`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warnings`
--
ALTER TABLE `warnings`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `warrants`
--
ALTER TABLE `warrants`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `weapons`
--
ALTER TABLE `weapons`
  MODIFY `ID` int(255) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
