-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Dec 20, 2022 at 07:36 AM
-- Server version: 5.7.36
-- PHP Version: 7.4.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `store_project`
--
CREATE DATABASE IF NOT EXISTS `store_project` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `store_project`;
-- --------------------------------------------------------

--
-- Table structure for table `account_user`
--

DROP TABLE IF EXISTS `account_user`;
CREATE TABLE IF NOT EXISTS `account_user` (
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `account_admin`
--

DROP TABLE IF EXISTS `account_admin`;
CREATE TABLE IF NOT EXISTS `account_admin` (
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
CREATE TABLE IF NOT EXISTS `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `category_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `category_name`) VALUES
(1, 'Electronic Device'),
(2, 'Household Electrical Appliances'),
(3, 'Household Appliances');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

DROP TABLE IF EXISTS `products`;
CREATE TABLE IF NOT EXISTS `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `product_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_price` float NOT NULL,
  `product_photo` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_description` text COLLATE utf8mb4_unicode_ci,
  `product_star` int DEFAULT NULL,
  `product_like` int DEFAULT '0',
  `product_view` int DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `product_name`, `product_price`, `product_photo`, `product_description`, `product_star`) VALUES
(1, 'Beoplay Silver', 888, 'beoplay-white.jpg,beoplay-white-2.jpg,beoplay-white-3.jpg', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3),
(2, 'Beoplay Black', 888, 'beoplay-black.jpg,beoplay-black-2.jpg,beoplay-black-3.jpg', 'The Beoplay HX from Bang & Olufsen are elegant ANC headphones with precise sound and maximum wearer comfort. They\'re equipped with the latest generation of adaptive Active Noise Cancellation and efficiently eliminate any background noise, while customised 40 mm drivers with neodymium magnets ensure impressive sound. Thanks to high-quality materials and their close-fitting design, these headphones block out sound effectively - perfect for when you\'re working from home or don\'t want to be disturbed. And four special microphones ensure maximum clarity of speech and sound while you\'re speaking on the phone. Simply perfect.', 4),
(3, 'Holo', 12, 'holo-orange.jpg', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3),
(4, 'Corda', 10, 'corda-black.jpg', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 2),
(5, 'Maracas', 10, 'maracas.jpg,maracas-2.jpg', 'Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.', 3),
(6, 'Loop Bottle Opener', 15, 'loop-bottle-opener.jpg,loop-bottle-opener-2.jpg', 'Diaz approached this bottle opener as an industrial tool, simplified in a manner to provoke curiosity. Cast in Milwaukee, WI, the design is equally informed by its end use, the rugged material and the manufacturing process. The Loop is finished in a satin electropolish, and will open bottles for as long as you\'re drinking them. The Loop is manufactured in Milwaukee, WI and electro-polished in Chicago, IL.', 4),
(7, 'Kettle Teapot', 115, 'kettle-teapot.jpg,kettle-teapot-2.jpg,kettle-teapot-3.jpg', 'Menu\'s Glass Kettle Teapot by Norm Architects uniquely embraces the meeting of two traditions - Asian zen philosophy and modern Scandinavian design. A special feature is the teapot’s transparency that grants a visual experience of the tea, and stimulates the senses of sight, touch, and smell alike. As a fine design detail, the tea egg is placed in the center of the pot and is easily raised by pulling the attached silicon string when the tea is ready for serving.', 5),
(8, 'Lift Coaster', 6, 'lift-coaster.jpg,lift-coaster-2.jpg,lift-coaster-3.jpg', 'Reduced to elegant simplicity, the Lift Coasters are designed to elevate and present your glassware, celebrating the relationship between hand, glass, and table. This set of industrial solid brass coasters adds a sculptural practicality to any tabletop. Comes in a set of 4.', 5),
(9, 'Lift Trivet', 3, 'lift-trivet.jpg,lift-trivet-2.jpg,lift-trivet-3.jpg', 'Lift Trivet is a minimal object with a simple purpose—use from oven to table, with hot pots and warm dishes. Graceful protection for your counter and table tops. Uncoated brass will age over time-getting darker and richer. To restore brass to its original finish, use a polishing compound and/or cloth.', 4),
(10, 'Meet Bench', 160, 'meet-bench.jpg,meet-bench-2.jpg', 'The Meet Bench takes inspiration from the traditional piano bench. A versatile, multi-use piece, its name refers to the two stabilizing tubes that elegantly join under the seat. The bench is also just big enough for two people to sit together – and \'meet\'. Use Meet Bench in the hallway, as a chair in front of your writing desk or in the bedroom. It also works as an occasional table on which to display books or a lamp. Timeless in character, the design is based on contrast, the opposition between its masculine, industrial legs and the expressive embracing curves of the seat. Removable non-slip felt topper included.', 2),
(11, 'Mega Daybed', 990, 'mega-daybed.jpg,mega-daybed-2.jpg,mega-daybed-3.jpg', 'Designer Chris Martin saw a rack of freshly baked loaves in a bakery and found something instantly appealing about the risen bread against the metal wires. The idea became a sofa with thin steel tube side panels that enclose and lift the plump cushions. Mega offers superb comfort and sculptural expression to offices, hotels and homes.', 3),
(12, 'Dome Lamp', 120, 'dome-lamp-black.jpg', 'The Mater Dome Lamp is an award-winning piece of luminaire designed by Todd Bracher. Timeless and iconic with a spherical shade, which creates a gentle glow. Available in two sizes and in various finishes, Dome is a minimalist and streamlined table lamp designed to complement both residential and commercial decor.', 2),
(13, 'Jwda Concrete', 36.99, 'jwda-concrete.jpg,jwda-concrete-2.jpg,jwda-concrete-3.jpg', 'The JWDA Concrete Table Lamp from Menu exhibits a softer side to the roughness of industrial materials, smoothing pure concrete into a cylindrical base that supports a rounded glass diffuser. The white glass is delicately cradled within the base and glows warmly once lit. A stylish brass rotary switch buttons the collar of the grey base, acting as a dimmer that can control the amount of ambient light from the fixture.', 2),
(14, 'Lamp w082', 75, 'lamp-w082.jpg,lamp-w082-2.jpg,lamp-w082-3.jpg', 'The theme of the task lamp is one of those design projects that always has to measure itself with great masterpieces from the past. Hundreds have been invented over the years. Some of them so brilliant they are hard to beat. They are full of springs and knobs and complicated hinges. Sure, you can design another one of these, but perhaps there is space for a simpli ed mechanism. An object that is calm. It does move, but does not do everything. For some people that is enough. What about you?', 3),
(15, 'Lodge Flush', 99, 'lodge-flush.jpg', 'The Lodge Flush Mount is composed of a singular wooden column suspended from a metal canopy. The fixture swivels from side to side, directing light from a softly glowing bulb.', 3),
(16, 'Lodge Sconce', 29, 'lodge-sconce.jpg', 'The Lodge Sconce is composed of a singular wooden column mounted on a metal back plate. The fixture swivels 360 degrees, directing light from a softly glowing bulb. Dimmer switch located at top of fixture. ADA Compliant. Made in the USA, UL Listed.', 2);

-- --------------------------------------------------------

--
-- Table structure for table `products_categories`
--

DROP TABLE IF EXISTS `products_categories`;
CREATE TABLE IF NOT EXISTS `products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products_categories`
--

INSERT INTO `products_categories` (`product_id`, `category_id`) VALUES
(1, 1),
(2, 1),
(3, 2),
(4, 2),
(5, 1),
(6, 3),
(7, 2),
(8, 3),
(9, 3),
(10, 3),
(11, 3),
(12, 2),
(13, 2),
(14, 2),
(15, 2),
(16, 2);

-- --------------------------------------------------------

--
-- Table structure for table `purchased_product`
--

DROP TABLE IF EXISTS `purchased_product`;
CREATE TABLE IF NOT EXISTS `purchased_product` (
  `username` varchar(20) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`username`, `product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_product_like`
--

DROP TABLE IF EXISTS `user_product_like`;
CREATE TABLE IF NOT EXISTS `user_product_like` (
  `username` varchar(20) NOT NULL,
  `product_id` int NOT NULL,
  PRIMARY KEY (`username`, `product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(12) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
CREATE TABLE IF NOT EXISTS `admin` (
  `username` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `fullname` varchar(50) CHARACTER SET utf8 NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
