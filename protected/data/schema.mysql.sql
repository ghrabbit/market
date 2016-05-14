-- Adminer 3.3.3 MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DELIMITER ;;

DROP PROCEDURE IF EXISTS `testCall`;;
CREATE DEFINER=`eshop`@`localhost` PROCEDURE `testCall`(IN intV int, OUT strV VARCHAR(100))
begin
  select cast(intV as char) into strV;
end;;

DELIMITER ;

DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) DEFAULT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `img_file` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`,`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `order_history`;
CREATE TABLE `order_history` (
  `order_id` int(11) NOT NULL,
  `status` varchar(32) NOT NULL,
  `description` text,
  `ordered` varchar(32) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `price` double NOT NULL,
  `qty` int(11) NOT NULL,
  PRIMARY KEY (`order_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `ordered` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `approved` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `customer` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text CHARACTER SET utf8,
  `email` varchar(64) CHARACTER SET utf8 DEFAULT NULL,
  `amount` double NOT NULL,
  `qty` int(11) NOT NULL,
  `payment_system` varchar(16) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `username` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(64) CHARACTER SET utf8 NOT NULL,
  `description` varchar(512) CHARACTER SET utf8 DEFAULT NULL,
  `price` double NOT NULL,
  `on_special` int(11) NOT NULL DEFAULT '0',
  `img_file` varchar(128) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `products_categories`;
CREATE TABLE `products_categories` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `password` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `firstname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `lastname` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `family` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(11) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` text COLLATE utf8_unicode_ci,
  `salt` char(10) COLLATE utf8_unicode_ci NOT NULL,
  `newpassword` varchar(64) COLLATE utf8_unicode_ci DEFAULT NULL,
  `_roles` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `users` (`id`, `username`, `password`, `firstname`, `lastname`, `family`, `email`, `phone`, `address`, `salt`, `newpassword`, `_roles`) VALUES
(37,	'customer',	'p+2hK5TpvQvpI',	'Cust',	'Omer',	NULL,	'customer@home.local.one',	'10223322223',	'Краснодар. ул. Рабочая.',	'p+|0rko3tm',	NULL,	NULL),
(40,	'admin',	'SKUCsJUL7pJiw',	'Admin',	'Nimda',	NULL,	'webmaster@home.local.one',	'11223322223',	'The city.',	'\"SK!Qcbs-6',	NULL,	'admin');

-- 2016-05-12 18:33:10
