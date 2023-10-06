-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Oct 05, 2023 at 04:14 PM
-- Server version: 8.0.28
-- PHP Version: 8.1.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `chat_application`
--

-- --------------------------------------------------------

--
-- Table structure for table `group_chat_messages`
--

CREATE TABLE `group_chat_messages` (
  `id` int NOT NULL,
  `userid` int NOT NULL,
  `group_chat_message` varchar(200) NOT NULL,
  `created_on` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group_chat_messages`
--

INSERT INTO `group_chat_messages` (`id`, `userid`, `group_chat_message`, `created_on`) VALUES
(6, 11, 'First chat message', '2023-09-28 03:56:09'),
(7, 11, 'Test chat \n', '2023-09-28 03:57:35'),
(8, 11, 'Fine\n', '2023-09-28 03:59:19'),
(9, 11, 'Hey\n', '2023-09-29 09:23:19'),
(10, 11, 'f', '2023-09-29 09:24:29'),
(11, 11, 'ff', '2023-09-29 09:27:05'),
(12, 11, 'g', '2023-09-29 09:31:53'),
(13, 11, 'test', '2023-09-30 01:25:41'),
(14, 11, 'test 2', '2023-09-30 01:25:52'),
(15, 11, 'test', '2023-09-30 01:26:01'),
(16, 11, 'hello\n', '2023-09-30 01:54:51'),
(17, 11, 'f', '2023-09-30 01:56:44'),
(18, 11, 'f', '2023-09-30 01:56:49'),
(19, 11, 'yh', '2023-09-30 02:25:20'),
(20, 11, 'teas\n', '2023-09-30 02:36:36'),
(21, 11, 'jkn\nnlkhnjhn\n', '2023-09-30 02:37:18'),
(22, 11, 'joipjiojoijio', '2023-09-30 02:37:23'),
(23, 11, 'd', '2023-09-30 03:51:33'),
(24, 11, 'dafasg', '2023-09-30 03:51:43'),
(25, 11, 'g', '2023-09-30 03:53:18'),
(26, 11, 'sghsdzhg', '2023-09-30 03:54:42'),
(27, 11, 'sgsg', '2023-09-30 03:57:08'),
(28, 11, 'sfgs', '2023-09-30 03:57:16'),
(29, 11, 'f', '2023-09-30 04:05:51'),
(30, 11, 'ff', '2023-09-30 04:06:48'),
(31, 11, 'fffff', '2023-09-30 04:10:31'),
(32, 11, 'gsfdghsdfhg', '2023-09-30 04:11:05'),
(33, 11, 'ashasdhdsh', '2023-09-30 04:11:06'),
(34, 11, 'ashsadhdsah', '2023-09-30 04:11:08'),
(35, 15, 'f', '2023-09-30 04:15:16'),
(36, 15, 'im fatma', '2023-09-30 04:15:31'),
(37, 11, 'fff', '2023-09-30 04:17:34'),
(38, 11, 'ff', '2023-09-30 04:17:49'),
(39, 11, 'g', '2023-09-30 04:19:12'),
(40, 11, 'hsdh', '2023-09-30 04:19:19'),
(41, 11, 'n', '2023-09-30 04:19:24'),
(42, 11, 'f', '2023-09-30 04:20:02'),
(43, 11, 'test', '2023-09-30 04:20:23'),
(44, 11, 'testtttt', '2023-09-30 04:21:04'),
(45, 11, 'ffffffffffffffffffffffff', '2023-09-30 04:32:50'),
(46, 11, 'take care', '2023-09-30 04:37:35'),
(47, 11, 'ffff', '2023-09-30 04:38:13'),
(48, 11, 'fff', '2023-09-30 04:39:32'),
(49, 11, 'q', '2023-09-30 04:39:55'),
(50, 11, 'f', '2023-09-30 04:43:33'),
(51, 11, 'f', '2023-09-30 04:43:55'),
(52, 15, 'fat', '2023-09-30 04:56:24'),
(53, 15, 'fat', '2023-09-30 04:56:47'),
(54, 11, 'd', '2023-09-30 04:57:26'),
(55, 11, 'q', '2023-09-30 04:57:46'),
(56, 15, 'fffffff', '2023-09-30 04:57:57'),
(57, 11, 'f', '2023-09-30 05:29:52'),
(58, 15, 'f', '2023-09-30 05:30:24'),
(59, 11, 'test', '2023-09-30 05:32:42'),
(60, 15, 'f', '2023-09-30 05:32:51'),
(61, 11, 'f', '2023-09-30 05:35:49'),
(62, 11, 'f', '2023-09-30 05:36:25'),
(63, 15, 'f', '2023-09-30 05:38:41'),
(64, 11, 'f', '2023-09-30 05:38:56'),
(65, 15, 'f', '2023-09-30 05:39:03'),
(66, 11, 'f', '2023-09-30 05:39:42'),
(67, 15, 'f', '2023-09-30 05:39:47'),
(68, 11, 'f', '2023-09-30 05:49:52'),
(69, 11, 'a', '2023-09-30 05:50:00'),
(70, 11, 'f', '2023-09-30 05:55:22'),
(71, 11, 'f', '2023-09-30 05:55:31'),
(72, 11, 'ffff', '2023-09-30 05:55:52'),
(73, 11, 'ffff', '2023-09-30 05:56:22'),
(74, 11, 'fffff', '2023-09-30 05:56:28'),
(75, 11, 'f', '2023-09-30 05:57:16'),
(76, 11, 'qqqqqq', '2023-09-30 05:57:30'),
(77, 11, 'q', '2023-09-30 05:57:37'),
(78, 11, 'q', '2023-09-30 05:57:39'),
(79, 11, 'q', '2023-09-30 05:57:47'),
(80, 11, 'f', '2023-09-30 05:58:48'),
(81, 11, 'fasgsagasg', '2023-09-30 05:59:13'),
(82, 11, 'f', '2023-09-30 06:00:07'),
(83, 11, 'q', '2023-09-30 06:03:53'),
(84, 11, 'qq', '2023-09-30 06:04:01'),
(85, 11, 'f', '2023-09-30 06:07:15'),
(86, 11, 'd', '2023-09-30 06:07:52'),
(87, 11, 'd', '2023-09-30 06:10:46'),
(88, 11, 'd', '2023-09-30 06:10:48'),
(89, 11, 'a', '2023-09-30 06:10:52'),
(90, 11, 'qq', '2023-09-30 06:11:19'),
(91, 11, 'fffff', '2023-09-30 06:13:49'),
(92, 11, 'c', '2023-09-30 06:14:14'),
(93, 11, 'ccccc', '2023-09-30 06:14:28'),
(94, 11, 'c', '2023-09-30 06:26:12'),
(95, 11, 'f', '2023-09-30 09:06:46'),
(96, 11, 'q', '2023-09-30 10:39:54'),
(97, 11, 'qqqq', '2023-09-30 11:04:43'),
(98, 11, 'q', '2023-09-30 11:39:27'),
(99, 11, 'qq', '2023-09-30 11:39:42'),
(100, 11, 'qq', '2023-09-30 11:39:43'),
(101, 11, 'qwe', '2023-09-30 11:41:48'),
(102, 11, 'qwe', '2023-09-30 11:42:09'),
(103, 11, 'qweq', '2023-09-30 11:42:11'),
(104, 11, 'q', '2023-09-30 11:42:16'),
(105, 11, 'qqqq', '2023-09-30 11:42:24'),
(106, 11, 'qqqqqqqqqqqqqqqqqqqqqqq', '2023-09-30 11:42:52'),
(107, 11, 'qqq', '2023-09-30 11:43:19'),
(108, 11, 'q', '2023-09-30 11:44:19'),
(109, 15, 'qqqqq', '2023-09-30 11:44:53'),
(110, 11, 'q', '2023-09-30 11:44:57'),
(111, 15, 'fatma', '2023-09-30 11:45:45'),
(112, 15, 'fatma', '2023-09-30 11:45:46'),
(113, 11, 'its me fatma', '2023-09-30 11:46:13'),
(114, 15, 'f', '2023-09-30 11:46:44'),
(115, 11, 'q', '2023-09-30 11:54:51'),
(116, 11, 'q', '2023-09-30 11:54:57'),
(117, 11, 'q', '2023-09-30 11:55:10'),
(118, 15, 'q', '2023-10-01 12:06:09'),
(119, 15, 'q', '2023-10-01 01:11:04'),
(120, 15, 'fat', '2023-10-01 01:11:19'),
(121, 15, 'f', '2023-10-03 04:51:51'),
(122, 15, 'f', '2023-10-03 04:52:02'),
(123, 11, 'q', '2023-10-03 04:53:21'),
(124, 11, 'q', '2023-10-03 04:58:14'),
(125, 11, 'test', '2023-10-03 05:05:24'),
(126, 15, 'q', '2023-10-03 05:06:01'),
(127, 11, 'q', '2023-10-03 05:24:56'),
(128, 11, 'qqq', '2023-10-03 07:38:06'),
(129, 11, 'This is a test', '2023-10-03 08:18:03'),
(130, 15, 'this is a test\n', '2023-10-03 08:18:33'),
(131, 11, 'test test', '2023-10-03 08:21:57'),
(132, 11, 'How are you all?', '2023-10-03 08:25:31'),
(133, 11, 'How are you all?', '2023-10-03 08:25:51'),
(134, 11, 'q', '2023-10-04 12:00:41');

-- --------------------------------------------------------

--
-- Table structure for table `private_chat_messages`
--

CREATE TABLE `private_chat_messages` (
  `chat_message_id` int NOT NULL,
  `to_user_id` int NOT NULL,
  `from_user_id` int NOT NULL,
  `private_chat_message` mediumtext COLLATE utf8mb4_bin NOT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Yes','No') CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Chat Message is either ''Read'' or ''Unread'''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_bin;

--
-- Dumping data for table `private_chat_messages`
--

INSERT INTO `private_chat_messages` (`chat_message_id`, `to_user_id`, `from_user_id`, `private_chat_message`, `timestamp`, `status`) VALUES
(1, 15, 11, 'HelloFatma', '2023-10-03 05:14:38', 'Yes'),
(2, 15, 11, 'test test', '2023-10-03 05:24:30', 'Yes'),
(3, 15, 11, 'How are you', '2023-10-03 05:24:48', 'Yes'),
(4, 15, 11, 'How are you Fatma?\n', '2023-10-03 05:26:39', 'Yes'),
(5, 15, 11, 'd', '2023-10-03 06:23:25', 'Yes'),
(6, 11, 15, 'qqq', '2023-10-03 06:23:33', 'Yes'),
(7, 11, 15, 'q', '2023-10-03 06:23:37', 'Yes'),
(8, 11, 15, 'q', '2023-10-03 06:23:47', 'Yes'),
(9, 11, 15, 'q', '2023-10-03 06:23:58', 'Yes'),
(10, 15, 11, 'q', '2023-10-03 06:37:44', 'Yes'),
(11, 15, 11, 'q', '2023-10-03 06:37:50', 'Yes'),
(12, 11, 15, 'q', '2023-10-03 06:38:01', 'Yes'),
(13, 15, 11, 'test', '2023-10-03 06:38:33', 'Yes'),
(14, 11, 15, 'g', '2023-10-03 06:38:49', 'Yes'),
(15, 11, 15, 'tttt', '2023-10-03 06:39:19', 'Yes'),
(16, 11, 15, 'test', '2023-10-03 06:48:20', 'Yes'),
(17, 15, 11, 'q', '2023-10-03 07:18:22', 'Yes'),
(18, 12, 11, 'q', '2023-10-03 07:47:37', 'Yes'),
(19, 12, 11, 'q', '2023-10-03 07:47:45', 'Yes'),
(20, 15, 11, 'q', '2023-10-03 07:48:03', 'Yes'),
(21, 11, 15, 'q', '2023-10-03 07:48:29', 'Yes'),
(22, 15, 11, 'h', '2023-10-04 08:29:15', 'Yes'),
(23, 11, 15, 'h', '2023-10-04 08:29:34', 'Yes'),
(24, 11, 15, 'Hello Ahmed', '2023-10-04 08:30:47', 'Yes'),
(25, 11, 15, 'Where Have you been?\n', '2023-10-04 08:31:00', 'Yes'),
(26, 11, 15, 'Where are you???', '2023-10-04 08:31:46', 'Yes'),
(27, 11, 15, 'Hmmm', '2023-10-04 08:32:19', 'Yes'),
(28, 11, 15, 'qqq', '2023-10-04 08:32:29', 'Yes'),
(29, 15, 11, 'Im here\n', '2023-10-04 08:32:47', 'Yes'),
(30, 15, 11, 'how are you?', '2023-10-04 08:33:04', 'Yes'),
(31, 15, 11, 'test', '2023-10-04 08:33:09', 'Yes'),
(32, 11, 15, 'hey', '2023-10-04 08:35:10', 'Yes'),
(33, 11, 15, 'Hello Ahmed', '2023-10-04 22:47:04', 'Yes'),
(34, 11, 15, 'g', '2023-10-04 23:21:55', 'Yes'),
(35, 11, 15, 'ggggg', '2023-10-04 23:22:14', 'Yes'),
(36, 11, 15, 'tesst', '2023-10-04 23:22:24', 'Yes'),
(37, 15, 11, 'q', '2023-10-04 23:23:39', 'Yes'),
(38, 11, 15, 'q', '2023-10-04 23:23:44', 'Yes'),
(39, 11, 15, 'q', '2023-10-04 23:23:51', 'Yes'),
(40, 11, 15, 'qqqqq', '2023-10-04 23:24:00', 'Yes'),
(41, 15, 11, 'q', '2023-10-04 23:24:03', 'Yes'),
(42, 11, 15, 'qqq', '2023-10-04 23:24:07', 'Yes'),
(43, 11, 15, 'qqq', '2023-10-04 23:40:36', 'Yes'),
(44, 11, 15, 'f', '2023-10-05 00:11:43', 'Yes'),
(45, 12, 11, 'q', '2023-10-05 00:11:52', 'No'),
(46, 11, 15, 'q', '2023-10-05 00:11:56', 'Yes'),
(47, 11, 15, 'q', '2023-10-05 00:30:55', 'Yes'),
(48, 11, 15, 'test', '2023-10-05 00:31:04', 'Yes'),
(49, 11, 15, 'q', '2023-10-05 00:32:57', 'Yes'),
(50, 11, 15, 'q', '2023-10-05 00:33:04', 'Yes'),
(51, 11, 15, 'q', '2023-10-05 00:33:10', 'Yes'),
(52, 11, 15, 'q', '2023-10-05 00:34:14', 'Yes'),
(53, 11, 15, 'q', '2023-10-05 00:34:18', 'Yes'),
(54, 11, 15, 'q', '2023-10-05 00:34:23', 'Yes'),
(55, 11, 15, 'q', '2023-10-05 00:37:20', 'Yes'),
(56, 11, 15, 'q', '2023-10-05 00:37:34', 'Yes'),
(57, 11, 15, 'q', '2023-10-05 00:37:45', 'Yes');

-- --------------------------------------------------------

--
-- Table structure for table `chat_application_users`
--

CREATE TABLE `chat_application_users` (
  `user_id` int NOT NULL,
  `user_name` varchar(250) NOT NULL,
  `user_email` varchar(250) NOT NULL,
  `user_password` varchar(100) NOT NULL,
  `user_profile` varchar(100) NOT NULL,
  `user_status` enum('Disabled','Enable') NOT NULL,
  `user_created_on` datetime NOT NULL,
  `user_verification_code` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'Registration Verification Code sent to the user''s email',
  `user_login_status` enum('Logout','Login') NOT NULL,
  `user_token` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL COMMENT 'A random unique string we generate with every login to be used in conjunction with `user_connection_id` to implement One-to-One Private Chat',
  `user_connection_id` int DEFAULT NULL COMMENT 'Unique Connection ID generated by the WebSocket Connection for every connected client (used in conjuntion with `user_token` column to implement One-to-One Private Chat)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `chat_application_users`
--

INSERT INTO `chat_application_users` (`user_id`, `user_name`, `user_email`, `user_password`, `user_profile`, `user_status`, `user_created_on`, `user_verification_code`, `user_login_status`, `user_token`, `user_connection_id`) VALUES
(11, 'Ahmed Yahya', 'ahmed.yahya@test.com', '123456', 'images/1816579302.jpg', 'Enable', '2023-09-25 21:45:58', '2576115082b182fd3228040013a12bf7', 'Logout', 'decd9bb4ac91fb155d98f7a140e29651', 225),
(12, 'Samir', 'samir@test.com', '123456', 'images/1695766436.png', 'Enable', '2023-09-26 22:13:56', '627f28af6a96f5f128cb4a039abf7f6f', 'Logout', NULL, NULL),
(13, 'Niazy', 'niazy@yahoo.com', '123456', 'images/1695766941.png', 'Enable', '2023-09-26 22:22:21', '59fcb028adfd03b3c404ec46d858258a', 'Logout', NULL, NULL),
(14, 'Baher', 'baher@test.com', '123456', 'images/1695770137.png', 'Disabled', '2023-09-26 23:15:37', 'ebde936036ec38c6edc2f04660525d8a', 'Logout', NULL, NULL),
(15, 'Fatma', 'fatma@test.com', '123456', 'images/1136324244.jpg', 'Enable', '2023-09-26 23:59:03', 'e2b598d14a3abce067afe26808cbb4b7', 'Logout', '890358e83c2c9a5854d3e7875ff0bacf', 191);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `group_chat_messages`
--
ALTER TABLE `group_chat_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `private_chat_messages`
--
ALTER TABLE `private_chat_messages`
  ADD PRIMARY KEY (`chat_message_id`);

--
-- Indexes for table `chat_application_users`
--
ALTER TABLE `chat_application_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `group_chat_messages`
--
ALTER TABLE `group_chat_messages`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `private_chat_messages`
--
ALTER TABLE `private_chat_messages`
  MODIFY `chat_message_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `chat_application_users`
--
ALTER TABLE `chat_application_users`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
