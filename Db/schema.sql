-- Schema-only database setup. Seed accounts and personal data are intentionally omitted.
-- phpMyAdmin SQL Dump
-- version 4.8.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Jun 11, 2020 at 01:04 PM
-- Server version: 10.1.38-MariaDB
-- PHP Version: 7.1.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `martdevelopers_iLibrary`
--

-- --------------------------------------------------------

--
-- Table structure for table `iL_BookCategories`
--

CREATE TABLE `iL_BookCategories` (
  `bc_id` int(20) NOT NULL,
  `bc_code` varchar(200) NOT NULL,
  `bc_name` varchar(200) NOT NULL,
  `bc_desc` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_BookCategories`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Books`
--

CREATE TABLE `iL_Books` (
  `b_id` int(20) NOT NULL,
  `b_title` varchar(200) NOT NULL,
  `b_author` varchar(200) NOT NULL,
  `b_isbn_no` varchar(200) NOT NULL,
  `b_publisher` varchar(200) NOT NULL,
  `b_coverimage` varchar(200) NOT NULL,
  `bc_id` varchar(200) NOT NULL,
  `bc_name` varchar(200) NOT NULL,
  `b_status` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `b_summary` longtext NOT NULL,
  `b_copies` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Books`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_ChargesRates`
--

CREATE TABLE `iL_ChargesRates` (
  `cr_id` int(20) NOT NULL,
  `cr_code` varchar(200) NOT NULL,
  `cr_name` varchar(200) NOT NULL,
  `cr_amount` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_ChargesRates`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Fines`
--

CREATE TABLE `iL_Fines` (
  `f_id` int(200) NOT NULL,
  `f_type` varchar(200) NOT NULL,
  `f_amt` varchar(200) NOT NULL,
  `f_payment_code` varchar(200) NOT NULL,
  `s_id` varchar(200) NOT NULL,
  `s_number` varchar(200) NOT NULL,
  `s_name` varchar(200) NOT NULL,
  `f_status` varchar(200) NOT NULL,
  `f_checksum` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Fines`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Librarians`
--

CREATE TABLE `iL_Librarians` (
  `l_id` int(20) NOT NULL,
  `l_name` varchar(200) NOT NULL,
  `l_number` varchar(200) NOT NULL,
  `l_email` varchar(200) NOT NULL,
  `l_pwd` varchar(200) NOT NULL,
  `l_bio` longtext NOT NULL,
  `l_phone` varchar(200) NOT NULL,
  `l_adr` varchar(200) NOT NULL,
  `l_dpic` varchar(200) NOT NULL,
  `l_acc_status` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Librarians`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_LibraryOperations`
--

CREATE TABLE `iL_LibraryOperations` (
  `lo_id` int(20) NOT NULL,
  `lo_type` varchar(200) NOT NULL,
  `lo_number` varchar(200) NOT NULL,
  `s_id` varchar(200) NOT NULL,
  `s_name` varchar(200) NOT NULL,
  `s_number` varchar(200) NOT NULL,
  `l_id` varchar(200) NOT NULL,
  `l_name` varchar(200) NOT NULL,
  `l_number` varchar(200) NOT NULL,
  `bc_id` varchar(200) NOT NULL,
  `bc_name` varchar(200) NOT NULL,
  `b_id` varchar(200) NOT NULL,
  `b_title` varchar(200) NOT NULL,
  `b_isbn_no` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `lo_checksum` varchar(200) NOT NULL,
  `lo_status` varchar(200) NOT NULL,
  `lo_return_date` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_LibraryOperations`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_messages`
--

CREATE TABLE `iL_messages` (
  `id` int(20) NOT NULL,
  `sender` varchar(200) NOT NULL,
  `receiver` varchar(200) NOT NULL,
  `content` longtext NOT NULL,
  `sender_id` varchar(200) NOT NULL,
  `receiver_id` varchar(200) NOT NULL,
  `send_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_messages`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_notifications`
--

CREATE TABLE `iL_notifications` (
  `id` int(20) NOT NULL,
  `content` longtext NOT NULL,
  `user_id` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_notifications`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_PasswordResets`
--

CREATE TABLE `iL_PasswordResets` (
  `pr_id` int(20) NOT NULL,
  `pr_useremail` varchar(200) NOT NULL,
  `pr_usertype` varchar(200) NOT NULL,
  `pr_dummypwd` varchar(200) NOT NULL,
  `pr_token` varchar(200) NOT NULL,
  `pr_status` varchar(200) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_PasswordResets`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Reccomendations`
--

CREATE TABLE `iL_Reccomendations` (
  `iR_id` int(20) NOT NULL,
  `iR_Booktitle` varchar(200) NOT NULL,
  `iR_author` varchar(200) NOT NULL,
  `iR_desc` longtext NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Reccomendations`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_receivedMails`
--

CREATE TABLE `iL_receivedMails` (
  `rm_id` int(20) NOT NULL,
  `sm_id` varchar(200) NOT NULL,
  `sm_title` varchar(200) NOT NULL,
  `sm_receiverNo` varchar(200) NOT NULL,
  `sm_senderNo` varchar(200) NOT NULL,
  `sm_senderName` varchar(200) NOT NULL,
  `sm_receiverName` varchar(200) NOT NULL,
  `sm_reply` longtext NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `iL_sendMails`
--

CREATE TABLE `iL_sendMails` (
  `sm_id` int(20) NOT NULL,
  `sm_title` varchar(200) NOT NULL,
  `sm_senderNo` varchar(200) NOT NULL,
  `sm_receiverNo` varchar(200) NOT NULL,
  `sm_senderName` varchar(200) NOT NULL,
  `sm_receiverName` varchar(200) NOT NULL,
  `sm_sentMail` longtext NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_sendMails`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Students`
--

CREATE TABLE `iL_Students` (
  `s_id` int(20) NOT NULL,
  `s_name` varchar(200) NOT NULL,
  `s_number` varchar(200) NOT NULL,
  `s_email` varchar(200) NOT NULL,
  `s_pwd` varchar(200) NOT NULL,
  `s_sex` varchar(200) NOT NULL,
  `s_phone` varchar(200) NOT NULL,
  `s_bio` longtext NOT NULL,
  `s_adr` varchar(200) NOT NULL,
  `s_dpic` varchar(200) NOT NULL,
  `s_acc_status` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Students`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_Subscriptions`
--

CREATE TABLE `iL_Subscriptions` (
  `s_id` int(20) NOT NULL,
  `s_title` varchar(200) NOT NULL,
  `s_code` varchar(200) NOT NULL,
  `s_category` varchar(200) NOT NULL,
  `s_desc` longtext NOT NULL,
  `s_cover_img` varchar(200) NOT NULL,
  `s_created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6),
  `s_file` varchar(200) NOT NULL,
  `s_publisher` varchar(200) NOT NULL,
  `s_year` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_Subscriptions`
--


-- --------------------------------------------------------

--
-- Table structure for table `iL_sudo`
--

CREATE TABLE `iL_sudo` (
  `id` int(20) NOT NULL,
  `username` varchar(200) NOT NULL,
  `number` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` varchar(200) NOT NULL,
  `profile_pic` varchar(200) NOT NULL,
  `bio` longtext NOT NULL,
  `phone` varchar(200) NOT NULL,
  `created_at` timestamp(6) NOT NULL DEFAULT CURRENT_TIMESTAMP(6) ON UPDATE CURRENT_TIMESTAMP(6)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `iL_sudo`
--


--
-- Indexes for dumped tables
--

--
-- Indexes for table `iL_BookCategories`
--
ALTER TABLE `iL_BookCategories`
  ADD PRIMARY KEY (`bc_id`);

--
-- Indexes for table `iL_Books`
--
ALTER TABLE `iL_Books`
  ADD PRIMARY KEY (`b_id`);

--
-- Indexes for table `iL_ChargesRates`
--
ALTER TABLE `iL_ChargesRates`
  ADD PRIMARY KEY (`cr_id`);

--
-- Indexes for table `iL_Fines`
--
ALTER TABLE `iL_Fines`
  ADD PRIMARY KEY (`f_id`);

--
-- Indexes for table `iL_Librarians`
--
ALTER TABLE `iL_Librarians`
  ADD PRIMARY KEY (`l_id`);

--
-- Indexes for table `iL_LibraryOperations`
--
ALTER TABLE `iL_LibraryOperations`
  ADD PRIMARY KEY (`lo_id`);

--
-- Indexes for table `iL_messages`
--
ALTER TABLE `iL_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iL_notifications`
--
ALTER TABLE `iL_notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `iL_PasswordResets`
--
ALTER TABLE `iL_PasswordResets`
  ADD PRIMARY KEY (`pr_id`);

--
-- Indexes for table `iL_Reccomendations`
--
ALTER TABLE `iL_Reccomendations`
  ADD PRIMARY KEY (`iR_id`);

--
-- Indexes for table `iL_receivedMails`
--
ALTER TABLE `iL_receivedMails`
  ADD PRIMARY KEY (`rm_id`);

--
-- Indexes for table `iL_sendMails`
--
ALTER TABLE `iL_sendMails`
  ADD PRIMARY KEY (`sm_id`);

--
-- Indexes for table `iL_Students`
--
ALTER TABLE `iL_Students`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `iL_Subscriptions`
--
ALTER TABLE `iL_Subscriptions`
  ADD PRIMARY KEY (`s_id`);

--
-- Indexes for table `iL_sudo`
--
ALTER TABLE `iL_sudo`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `iL_BookCategories`
--
ALTER TABLE `iL_BookCategories`
  MODIFY `bc_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `iL_Books`
--
ALTER TABLE `iL_Books`
  MODIFY `b_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `iL_ChargesRates`
--
ALTER TABLE `iL_ChargesRates`
  MODIFY `cr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `iL_Fines`
--
ALTER TABLE `iL_Fines`
  MODIFY `f_id` int(200) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `iL_Librarians`
--
ALTER TABLE `iL_Librarians`
  MODIFY `l_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `iL_LibraryOperations`
--
ALTER TABLE `iL_LibraryOperations`
  MODIFY `lo_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `iL_messages`
--
ALTER TABLE `iL_messages`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `iL_notifications`
--
ALTER TABLE `iL_notifications`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `iL_PasswordResets`
--
ALTER TABLE `iL_PasswordResets`
  MODIFY `pr_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `iL_Reccomendations`
--
ALTER TABLE `iL_Reccomendations`
  MODIFY `iR_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `iL_receivedMails`
--
ALTER TABLE `iL_receivedMails`
  MODIFY `rm_id` int(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `iL_sendMails`
--
ALTER TABLE `iL_sendMails`
  MODIFY `sm_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `iL_Students`
--
ALTER TABLE `iL_Students`
  MODIFY `s_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `iL_Subscriptions`
--
ALTER TABLE `iL_Subscriptions`
  MODIFY `s_id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `iL_sudo`
--
ALTER TABLE `iL_sudo`
  MODIFY `id` int(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

CREATE TABLE IF NOT EXISTS `iL_AccountRequests` (
  `ar_id` int(20) NOT NULL AUTO_INCREMENT,
  `ar_name` varchar(200) NOT NULL,
  `ar_email` varchar(200) NOT NULL,
  `ar_phone` varchar(200) NOT NULL,
  `ar_password` varchar(255) NOT NULL,
  `ar_role` varchar(20) NOT NULL,
  `ar_gender` varchar(20) NOT NULL DEFAULT '',
  `ar_address` varchar(255) NOT NULL DEFAULT '',
  `ar_status` varchar(20) NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`ar_id`),
  KEY `ar_status_created_at` (`ar_status`, `created_at`),
  KEY `ar_email` (`ar_email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
