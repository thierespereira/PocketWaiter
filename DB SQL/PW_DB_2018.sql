-- --------------------------------------------------------
-- Host:                         130.211.77.217
-- Server version:               5.7.14-google-log - (Google)
-- Server OS:                    Linux
-- HeidiSQL Version:             9.4.0.5125
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Dumping database structure for pocketwaiter
CREATE DATABASE IF NOT EXISTS `pocketwaiter` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `pocketwaiter`;

-- Dumping structure for table pocketwaiter.company
CREATE TABLE IF NOT EXISTS `company` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `email` varchar(70) DEFAULT NULL,
  `desc` varchar(1000) DEFAULT NULL,
  `website` varchar(50) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(250) DEFAULT NULL,
  `logo` blob,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1;

-- Dumping data for table pocketwaiter.company: ~1 rows (approximately)
/*!40000 ALTER TABLE `company` DISABLE KEYS */;
INSERT INTO `company` (`id`, `name`, `email`, `desc`, `website`, `phone`, `address`, `logo`) VALUES
	(1, 'WOW Burger', 'info@wowburger.ie', 'The best burgers in town!', 'www.wowburger.ie', '012346789', '10, Wellington Quay, Dublin 2', NULL),
	(2, 'Pocket Waiter', 'info@pocketwaiter.ie', 'Your own personal waiter in your pocket.', 'www.pocketwaiter.ie', '0871925550', 'Dublin 2', NULL);
/*!40000 ALTER TABLE `company` ENABLE KEYS */;

-- Dumping structure for table pocketwaiter.order
CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `total` decimal(10,2) NOT NULL DEFAULT '0.00',
  `date_time_of_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `status` varchar(35) NOT NULL DEFAULT 'Open',
  `comp_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_order_company` (`comp_id`),
  KEY `FK_order_user` (`user_id`),
  CONSTRAINT `FK_order_company` FOREIGN KEY (`comp_id`) REFERENCES `company` (`id`),
  CONSTRAINT `FK_order_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pocketwaiter.order: ~0 rows (approximately)
/*!40000 ALTER TABLE `order` DISABLE KEYS */;
/*!40000 ALTER TABLE `order` ENABLE KEYS */;

-- Dumping structure for table pocketwaiter.order_items
CREATE TABLE IF NOT EXISTS `order_items` (
  `order_id` int(11) NOT NULL,
  `item` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`item`),
  KEY `FK_order_items_product` (`product_id`),
  CONSTRAINT `FK_order_items_product` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pocketwaiter.order_items: ~0 rows (approximately)
/*!40000 ALTER TABLE `order_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `order_items` ENABLE KEYS */;

-- Dumping structure for table pocketwaiter.product
CREATE TABLE IF NOT EXISTS `product` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `description` varchar(250) NOT NULL,
  `price` decimal(10,2) NOT NULL DEFAULT '0.00',
  `status` int(11) DEFAULT NULL,
  `comp_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_product_company` (`comp_id`),
  CONSTRAINT `FK_product_company` FOREIGN KEY (`comp_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- Dumping data for table pocketwaiter.product: ~0 rows (approximately)
/*!40000 ALTER TABLE `product` DISABLE KEYS */;
/*!40000 ALTER TABLE `product` ENABLE KEYS */;

-- Dumping structure for table pocketwaiter.user
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(100) NOT NULL,
  `password` varchar(150) NOT NULL,
  `salt` varchar(150) NOT NULL,
  `type` varchar(20) NOT NULL,
  `phone_number` varchar(20) NOT NULL,
  `address` varchar(150) NOT NULL,
  `comp_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `FK_user_company` (`comp_id`),
  CONSTRAINT `FK_user_company` FOREIGN KEY (`comp_id`) REFERENCES `company` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1;

-- Dumping data for table pocketwaiter.user: ~7 rows (approximately)
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` (`id`, `email`, `password`, `salt`, `type`, `phone_number`, `address`, `comp_id`) VALUES
	(1, 'admin@gmail.com', '62d2658576e4fd3102554d96c8516fba', 'thisisntasalt', 'admin', '0871925550', 'Dublin 8', NULL),
	(2, 'staff@gmail.com', 'c9bc4f9f5cb8d582e061aeb3af964d71', 'thisisntasalt', 'staffadmin', '0871925554', 'Dublin 11', NULL),
	(3, 'customer@gmail.com', 'c9bc4f9f5cb8d582e061aeb3af964d71', 'thisisntasalt', 'customer', '0871925555', 'Dublin 1', NULL),
	(4, 'thieresluiz@gmail.com', 'c9bc4f9f5cb8d582e061aeb3af964d71', 'thisisntasalt', 'admin', '0871925550', 'Dublin 8', NULL),
	(5, 'palomino.fe@gmail.com', 'c9bc4f9f5cb8d582e061aeb3af964d71', 'thisisntasalt', 'staff', '0871923334', 'Dublin 2', NULL),
	(8, 'rob.lowney@gmail.com', 'e017f7271d654197e0c031913f285a16', 'thisisntasalt', 'customer', '871925551', 'Long\'s Place Street, D8, Apt 18, The Granary', 1);
/*!40000 ALTER TABLE `user` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
