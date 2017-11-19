-- phpMyAdmin SQL Dump
-- version 4.7.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 19, 2017 at 05:52 PM
-- Server version: 5.6.35
-- PHP Version: 7.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `book_db`
--
CREATE DATABASE IF NOT EXISTS `book_db` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `book_db`;

-- --------------------------------------------------------

--
-- Table structure for table `Book`
--

CREATE TABLE `Book` (
  `id` int(11) NOT NULL,
  `title` varchar(128) NOT NULL,
  `author` varchar(64) NOT NULL DEFAULT '',
  `checkedOut` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Book`
--

INSERT INTO `Book` (`id`, `title`, `author`, `checkedOut`) VALUES
(1, 'The Hobbit', 'Tolkien, J.R.R.', 0),
(2, 'The Fellowship of the Ring', 'Tolkien, J.R.R.', 0),
(4, 'Transcending CSS', 'Clarke, Andy', 0);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `Book`
--
ALTER TABLE `Book`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `Book`
--
ALTER TABLE `Book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
