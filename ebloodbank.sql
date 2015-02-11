-- phpMyAdmin SQL Dump
-- version 4.2.0
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Feb 11, 2015 at 12:53 PM
-- Server version: 5.6.17
-- PHP Version: 5.4.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `ebloodbank`
--
CREATE DATABASE IF NOT EXISTS `ebloodbank` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `ebloodbank`;

-- --------------------------------------------------------

--
-- Table structure for table `bank`
--

CREATE TABLE IF NOT EXISTS `bank` (
`bank_id` int(11) NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `bank_phone` varchar(50) NOT NULL,
  `bank_email` varchar(100) DEFAULT NULL,
  `bank_distr_id` int(11) NOT NULL,
  `bank_address` varchar(255) NOT NULL,
  `bank_rtime` datetime NOT NULL,
  `bank_status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `bank_meta`
--

CREATE TABLE IF NOT EXISTS `bank_meta` (
`meta_id` int(11) NOT NULL,
  `bank_id` int(11) NOT NULL,
  `meta_key` varchar(45) NOT NULL,
  `meta_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `city`
--

CREATE TABLE IF NOT EXISTS `city` (
`city_id` int(11) NOT NULL,
  `city_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `district`
--

CREATE TABLE IF NOT EXISTS `district` (
`distr_id` int(11) NOT NULL,
  `distr_name` varchar(255) NOT NULL,
  `distr_city_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `donation`
--

CREATE TABLE IF NOT EXISTS `donation` (
`donat_id` int(11) NOT NULL,
  `donat_amount` int(11) DEFAULT NULL,
  `donat_purpose` varchar(255) DEFAULT NULL,
  `donat_donor_id` int(11) NOT NULL,
  `donat_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `donor`
--

CREATE TABLE IF NOT EXISTS `donor` (
`donor_id` int(11) NOT NULL,
  `donor_name` varchar(255) NOT NULL,
  `donor_gender` varchar(45) NOT NULL,
  `donor_weight` smallint(6) NOT NULL,
  `donor_birthdate` date NOT NULL,
  `donor_blood_group` varchar(45) NOT NULL,
  `donor_distr_id` int(11) NOT NULL,
  `donor_address` varchar(255) NOT NULL,
  `donor_phone` varchar(50) NOT NULL,
  `donor_email` varchar(100) DEFAULT NULL,
  `donor_rtime` datetime NOT NULL,
  `donor_status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `donor_meta`
--

CREATE TABLE IF NOT EXISTS `donor_meta` (
`meta_id` int(11) NOT NULL,
  `donor_id` int(11) NOT NULL,
  `meta_key` varchar(45) NOT NULL,
  `meta_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE IF NOT EXISTS `stock` (
`stock_id` int(11) NOT NULL,
  `stock_bank_id` int(11) NOT NULL,
  `stock_blood_group` varchar(45) NOT NULL,
  `stock_quantity` int(11) NOT NULL,
  `stock_status` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE IF NOT EXISTS `user` (
`user_id` int(11) NOT NULL,
  `user_logon` varchar(100) NOT NULL,
  `user_pass` varchar(100) NOT NULL,
  `user_role` varchar(45) NOT NULL,
  `user_rtime` datetime NOT NULL,
  `user_status` varchar(45) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `user_logon`, `user_pass`, `user_role`, `user_rtime`, `user_status`) VALUES
(1, 'admin', '$2y$10$var5Z14/rjdaMT534M3W2e1K1Y15IeTyjVp3UG6NJosp0JVcOLzii', 'administrator', '2015-02-11 12:26:55', 'activated');

-- --------------------------------------------------------

--
-- Table structure for table `user_meta`
--

CREATE TABLE IF NOT EXISTS `user_meta` (
`meta_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `meta_key` varchar(45) NOT NULL,
  `meta_value` longtext
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bank`
--
ALTER TABLE `bank`
 ADD PRIMARY KEY (`bank_id`), ADD KEY `bank_district_id_idx` (`bank_distr_id`);

--
-- Indexes for table `bank_meta`
--
ALTER TABLE `bank_meta`
 ADD PRIMARY KEY (`meta_id`), ADD KEY `bm_bank_id_idx` (`bank_id`);

--
-- Indexes for table `city`
--
ALTER TABLE `city`
 ADD PRIMARY KEY (`city_id`);

--
-- Indexes for table `district`
--
ALTER TABLE `district`
 ADD PRIMARY KEY (`distr_id`), ADD KEY `dis_idx` (`distr_city_id`);

--
-- Indexes for table `donation`
--
ALTER TABLE `donation`
 ADD PRIMARY KEY (`donat_id`), ADD KEY `donat_donor_id_idx` (`donat_donor_id`);

--
-- Indexes for table `donor`
--
ALTER TABLE `donor`
 ADD PRIMARY KEY (`donor_id`), ADD KEY `donor_district_id_idx` (`donor_distr_id`);

--
-- Indexes for table `donor_meta`
--
ALTER TABLE `donor_meta`
 ADD PRIMARY KEY (`meta_id`), ADD KEY `dm_donor_id_idx` (`donor_id`);

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
 ADD PRIMARY KEY (`stock_id`), ADD KEY `stock_bank_id_idx` (`stock_bank_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
 ADD PRIMARY KEY (`user_id`), ADD UNIQUE KEY `user_logon_UNIQUE` (`user_logon`);

--
-- Indexes for table `user_meta`
--
ALTER TABLE `user_meta`
 ADD PRIMARY KEY (`meta_id`), ADD KEY `um_user_id_idx` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bank`
--
ALTER TABLE `bank`
MODIFY `bank_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `bank_meta`
--
ALTER TABLE `bank_meta`
MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `city`
--
ALTER TABLE `city`
MODIFY `city_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `district`
--
ALTER TABLE `district`
MODIFY `distr_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `donation`
--
ALTER TABLE `donation`
MODIFY `donat_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `donor`
--
ALTER TABLE `donor`
MODIFY `donor_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `donor_meta`
--
ALTER TABLE `donor_meta`
MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `user_meta`
--
ALTER TABLE `user_meta`
MODIFY `meta_id` int(11) NOT NULL AUTO_INCREMENT;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `bank`
--
ALTER TABLE `bank`
ADD CONSTRAINT `bank_district_id` FOREIGN KEY (`bank_distr_id`) REFERENCES `district` (`distr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `bank_meta`
--
ALTER TABLE `bank_meta`
ADD CONSTRAINT `bm_bank_id` FOREIGN KEY (`bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `district`
--
ALTER TABLE `district`
ADD CONSTRAINT `district_city_id` FOREIGN KEY (`distr_city_id`) REFERENCES `city` (`city_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `donation`
--
ALTER TABLE `donation`
ADD CONSTRAINT `donat_donor_id` FOREIGN KEY (`donat_donor_id`) REFERENCES `donor` (`donor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `donor`
--
ALTER TABLE `donor`
ADD CONSTRAINT `donor_district_id` FOREIGN KEY (`donor_distr_id`) REFERENCES `district` (`distr_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `donor_meta`
--
ALTER TABLE `donor_meta`
ADD CONSTRAINT `dm_donor_id` FOREIGN KEY (`donor_id`) REFERENCES `donor` (`donor_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
ADD CONSTRAINT `stock_bank_id` FOREIGN KEY (`stock_bank_id`) REFERENCES `bank` (`bank_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `user_meta`
--
ALTER TABLE `user_meta`
ADD CONSTRAINT `um_user_id` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
