-- phpMyAdmin SQL Dump
-- version 3.4.11.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jan 07, 2014 at 02:18 PM
-- Server version: 5.5.34
-- PHP Version: 5.4.6-1ubuntu1.5

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `product_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `tbl_products`
--

CREATE TABLE IF NOT EXISTS `tbl_products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `code` varchar(10) DEFAULT NULL,
  `product_type` varchar(50) DEFAULT NULL,
  `location` varchar(50) DEFAULT NULL,
  `cost_price` mediumint(9) DEFAULT NULL,
  `marked_price` mediumint(9) DEFAULT NULL,
  `selling_price` mediumint(9) DEFAULT NULL,
  `gross_profit` mediumint(9) DEFAULT NULL,
  `description` text,
  `date` date DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `tbl_product_type`
--

CREATE TABLE IF NOT EXISTS `tbl_product_type` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_type` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_product_type` (`product_type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

--
-- Dumping data for table `tbl_product_type`
--

INSERT INTO `tbl_product_type` (`id`, `product_type`) VALUES
(1, 'Bag'),
(2, 'Band'),
(3, 'Bangle'),
(4, 'Belt'),
(5, 'Blazer'),
(6, 'Body Mist'),
(7, 'Boots'),
(8, 'Boyfriend Shoes'),
(9, 'Cardigan'),
(10, 'Clutch'),
(11, 'Coat'),
(12, 'Dress'),
(13, 'Earrings'),
(14, 'Finger Rings'),
(15, 'Hair Band'),
(17, 'Half Pant'),
(18, 'Jacket'),
(19, 'Jeans Pant'),
(20, 'Jumper'),
(21, 'Leggings'),
(22, 'Mala'),
(23, 'Muffler'),
(24, 'Pant'),
(25, 'Pump Shoes'),
(26, 'Quarter Pant'),
(27, 'Sandal'),
(28, 'Saree Top'),
(29, 'Scarf'),
(30, 'Shirt'),
(31, 'Shoes'),
(16, 'Skirt'),
(32, 'Stocking'),
(33, 'Sweater'),
(34, 'Top'),
(35, 'Topi'),
(36, 'Wallet'),
(37, 'Watch');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
