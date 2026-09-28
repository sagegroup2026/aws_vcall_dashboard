-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 25, 2025 at 08:06 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `call_logs`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(255) NOT NULL,
  `name` varchar(250) NOT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `username` varchar(200) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(500) DEFAULT NULL,
  `mobile` varchar(20) DEFAULT NULL,
  `designation` varchar(500) DEFAULT NULL,
  `team` int(20) DEFAULT NULL,
  `last_login` datetime(6) DEFAULT NULL,
  `user_role` varchar(200) DEFAULT NULL,
  `status` int(10) NOT NULL DEFAULT 1,
  `created_on` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `gender`, `username`, `password`, `email`, `mobile`, `designation`, `team`, `last_login`, `user_role`, `status`, `created_on`) VALUES
(1, 'Ashutosh Vaidya', 'Male', 'ashutoshv75', 'I2n7t9r@', 'ashutoshv75@gmail.co.in', '9752971101', 'Web Administrator', 0, '2025-10-24 05:22:24.000000', 'Admin', 1, '2025-10-07 17:26:14'),
(2, 'Abhay Dubey', 'Male', 'AbhayD', 'Sage@123', 'abhayd.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(3, 'Abhishek Gour', 'Male', 'AbhishekG', 'Sage@124', 'abhishekg.acc@thesage.co.in', '2147483647', 'Marketing Executive', 3, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(4, 'Akansha Jain', 'Female', 'AkanshaJ', 'Sage@125', 'akanshajain@gmail.com', '8982366063', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(5, 'Aman Malviya', 'Male', 'AmanM', 'Sage@126', 'Aman.M@sage.co.in', '7440814439', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(6, 'Anamika Shukla', 'Female', 'AnamikaS', 'Sage@127', 'anamikas@sage.co.in', '7489766875', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(7, 'Anand Chaturvedi', 'Male', 'AnandC', 'Sage@128', 'anandc.acc@thesage.co.in', '2147483647', 'Marketing Executive', 11, '0000-00-00 00:00:00.000000', '', 0, '2025-10-24 18:36:59'),
(8, 'Anjali Saxena', 'Female', 'AnjaliS', 'Sage@129', 'anjalis.acc@thesage.co.in', '9165127113', 'DGM', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(9, 'Arpan Shrivastava', 'Male', 'ArpanS', 'Sage@130', 'arpans.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(10, 'Arvind Singh', 'Male', 'ArvindS', 'Sage@131', 'arvinds.acc@thesage.co.in', '2147483647', 'Marketing Executive', 10, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(11, 'Ashish Patil', 'Male', 'AshishP', 'Sage@132', 'ashishs.acc@thesage.co.in', '2147483647', 'Marketing Executive', 9, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(12, 'Ashish Phate', 'Male', 'AshishPh', 'Sage@133', 'ashish2306@sagerealty.com', '9575556651', 'Marketing Executive', 9, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(13, 'Ashish Thakre', 'Male', 'AshishT', 'Sage@134', 'ashisht.acc@thesage.co.in', '2147483647', 'Marketing Executive', 9, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(14, 'Avinash Chouhan', 'Male', 'AvinashC', 'Sage@135', 'avinash.c@sage.co.in', '8878026339', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(15, 'Bhupesh Pandey', 'Male', 'BhupeshP', 'Sage@136', 'bhupeshp.acc@thesage.co.in', '2147483647', 'Marketing Executive', 4, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(16, 'Devendra Joshi', 'Male', 'DevendraJ', 'Sage@137', 'devendraj.acc@thesage.co.in', '2147483647', 'Marketing Executive', 3, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(17, 'Faizal Ahmad', 'Male', 'FaizalA', 'Sage@138', 'faizalr.acc@thesage.co.in', '2147483647', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(18, 'Gaurav Shrivastav', 'Male', 'GauravSh', 'Sage@139', 'Gaurav.s@sage.co.in', '7415825362', 'Marketing Executive', 10, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(19, 'Jyoti Chouhan', 'Female', 'JyotiC', 'Sage@140', 'jyotic.acc@thesage.co.in', '2147483647', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(20, 'Kshitij Saxena', 'Male', 'KshitijS', 'Sage@141', 'kshitijs.acc@thesage.co.in', '2147483647', 'DGM', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(21, 'Madhu Patil', 'Female', 'MadhuP', 'Sage@142', 'madhu@sage.co.in', '9752279054', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(22, 'Manju Ingle', 'Female', 'ManjuI', 'Sage@143', 'manjui.acc@thesage.co.in', '2147483647', 'Marketing Executive', 4, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(23, 'Neeraj Kamboj', 'Male', 'NeerajK', 'Sage@144', 'neerajk.acc@thesage.co.in', '2147483647', 'DGM', 3, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(24, 'Nishant  Saxena', 'Male', 'NishantSx', 'Sage@145', 'nishantsx.acc@thesage.co.in', '2147483647', 'DGM', 4, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(25, 'Noopur Gupta', 'Female', 'NoopurG', 'Sage@146', 'noopur.26@sage.co.in', '7828522813', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(26, 'Omika Joshi ', 'Female', 'OmikaJ', 'Sage@147', 'omikaj.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 0, '2025-10-24 18:36:59'),
(27, 'Pawan Pandey', 'Male', 'PawanP', 'Sage@148', 'pawanp.acc@thesage.co.in', '2147483647', 'Marketing Executive', 4, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(28, 'Piyanjali Suman', 'Female', 'PiyanjaliS', 'Sage@149', 'piyanjali.s@sage.co.in', '9630053346', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(29, 'Preeti Shikharwar', 'Female', 'PreetiS', 'Sage@150', 'preetis.acc@thesage.co.in', '2147483647', 'Marketing Executive', 3, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(30, 'Ratna Patel', 'Female', 'RatnaP', 'Sage@151', 'ratna.patel@sagerealty', '8770605820', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(31, 'Roop Soni', 'Male', 'RoopS', 'Sage@152', 'roops.acc@thesage.co.in', '2147483647', 'Marketing Executive', 9, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(32, 'Sachin Mirapurkar', 'Male', 'SachinM', 'Sage@153', 'SachinM.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(33, 'Sanjay Maheshwari', 'Male', 'SanjayM', 'Sage@154', 'sanjaym.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(34, 'Shadab Khan', 'Male', 'ShadabK', 'Sage@155', 'ShadabK.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(35, 'Shailendra Parihar', 'Male', 'ShailendraP', 'Sage@156', 'shailendrap.acc@thesage.co.in', '2147483647', 'DGM', 10, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(36, 'Sheetal Gargav', 'Female', 'SheetalG', 'Sage@157', 'sheetalg.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(37, 'Shivani khare', 'Female', 'ShivaniK', 'Sage@158', 'shivanik.acc@thesage.co.in', '2147483647', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(38, 'Shubhi Parmar', 'Female', 'ShubhiP', 'Sage@159', 'shubhip.acc@thesage.co.in', '2147483647', 'Marketing Executive', 10, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(39, 'SIC', 'Other', 'SoniyaK', 'Sage@160', 'sic.acc@thesage.co.in', '2147483647', 'Marketing Executive', 0, '0000-00-00 00:00:00.000000', '', 0, '2025-10-24 18:36:59'),
(40, 'Subroto Das', 'Male', 'SubrotoD', 'Sage@161', 'subrotod.acc@thesage.co.in', '2147483647', 'Marketing Executive', 10, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(41, 'Surbhi Sen', 'Female', 'SurbhiS', 'Sage@162', 'Surbhi.S@sage.co.in', '9630053394', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(42, 'Tina Rajpoot', 'Female', 'TinaR', 'Sage@163', 'tinar.acc@thesage.co.in', '2147483647', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(43, 'Vaishali Suryawanshi', 'Female', 'VaishaliS', 'Sage@164', 'vaishalis.acc@thesage.co.in', '2147483647', 'Support Office Executive', 6, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(44, 'Viresh Pandey', 'Male', 'VireshP', 'Sage@165', 'vireshp.acc@thesage.co.in', '2147483647', 'Marketing Executive', 9, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(45, 'Vivek Bhargav', 'Male', 'VivekB', 'Sage@166', 'vivek@sage.co.in', '7999380979', 'Marketing Executive', 1, '0000-00-00 00:00:00.000000', '', 1, '2025-10-24 18:36:59'),
(46, 'Zeeshan Khan', 'Male', 'ZeeshanK', 'Sage@123', 'zeeshan.sr@thesage.co.in', '7987306684', 'Assistant General Manager', 0, '2025-10-24 06:47:57.000000', 'Admin', 1, '2025-10-24 18:45:56');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
