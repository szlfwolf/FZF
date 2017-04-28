-- phpMyAdmin SQL Dump
-- version 4.1.14.8
-- http://www.phpmyadmin.net
--
-- Host: 10.17.202.219:3306
-- Generation Time: 2016-07-29 14:27:19
-- 服务器版本： 5.6.20-baidu-20150209-log
-- PHP Version: 5.4.11

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `options`
--

-- --------------------------------------------------------

create table if not exists `configs` (
  `id` int(11) not null AUTO_INCREMENT,
  `limit_num` int (11) not null default 10,
  `limit_amount` int (11) not null default 5000,
  `return_rate_60` int(11) not null default 0.77,
  `return_rate_180` int(11) not null default 0.77,
  `return_rate_300` int(11) not null default 0.80,
  `return_rate_600` int(11) not null default 0.85,
  `return_rate_1800` int(11) not null default 0.87,
  `return_rate_3600` int(11) not null default 0.90,
  `open_time` int(11) not null default 4,
  `stop_time` int(11) not null default 4,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;
insert into  configs (id) value (1);

--
-- 表的结构 `objects`
--

CREATE TABLE IF NOT EXISTS `objects` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `body_profit` decimal(8,2) NOT NULL DEFAULT '0.80',
  `body_rank` int(11) NOT NULL DEFAULT '0',
  `body_name` varchar(255) NOT NULL,
  `body_status` int(1) NOT NULL DEFAULT '1',
  `body_name_english` varchar(50) NOT NULL,
  `body_tag` varchar(255) NOT NULL,
  `body_tag_forex` varchar(20) NOT NULL,
  `body_price` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `body_price_previous` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `body_price_min` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `body_price_max` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `body_price_interval` decimal(10,5) NOT NULL DEFAULT '0.00000',
  `body_price_decimal` int(1) NOT NULL DEFAULT '5',
  `body_price_repeat` int(11) NOT NULL DEFAULT '0',
  `is_disabled` int(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  `updated_at` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=13 ;

--
-- 转存表中的数据 `objects`
--

INSERT INTO `objects` (`id`, `body_profit`, `body_rank`, `body_name`, `body_status`, `body_name_english`, `body_tag`, `body_tag_forex`, `body_price`, `body_price_previous`, `body_price_min`, `body_price_max`, `body_price_interval`, `body_price_decimal`, `body_price_repeat`, `is_disabled`, `created_at`, `updated_at`) VALUES
(1, '0.80', 3, '澳元兑美元', 1, 'AUDUSD', 'fx_saudusd', 'AUDUSD', '0.75108', '0.75106', '0.75108', '0.75108', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:18'),
(2, '0.80', 1, '欧元兑美元', 1, 'EURUSD', 'fx_seurusd', 'EURUSD', '1.10824', '1.10818', '1.10824', '1.10824', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:18'),
(3, '0.80', 0, '英镑兑美元', 1, 'GBPUSD', 'fx_sgbpusd', 'GBPUSD', '1.31886', '1.31852', '1.31886', '1.31886', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:18'),
(4, '0.80', 5, '英镑兑日元', 1, 'GBPJPY', 'fx_sgbpjpy', 'GBPJPY', '136.76300', '136.73700', '136.76300', '136.76300', '0.00000', 3, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:18'),
(5, '0.80', 5, '美元兑日元', 1, 'USDJPY', 'fx_susdjpy', 'USDJPY', '103.69500', '103.69300', '103.69500', '103.69500', '0.00000', 3, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:14'),
(6, '0.80', 5, '欧元兑日元', 1, 'EURJPY', 'fx_seurjpy', 'EURJPY', '114.92100', '114.92000', '114.92100', '114.92100', '0.00000', 3, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:19'),
(7, '0.80', 4, '欧元兑澳元', 1, 'EURAUD', 'fx_seuraud', 'EURAUD', '1.47526', '1.47528', '1.47526', '1.47526', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:19'),
(8, '0.80', 0, '美元兑加元', 1, 'USDCAD', 'fx_susdcad', 'USDCAD', '1.31686', '1.31691', '1.31686', '1.31686', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:19'),
(9, '0.80', 0, '美元兑法郎', 1, 'USDCHF', 'fx_susdchf', 'USDCHF', '0.97961', '0.97962', '0.97961', '0.97961', '0.00000', 5, 0, 0, '2016-04-17 16:00:00', '2016-07-29 06:27:19'),
(11, '0.80', 0, '纽交所黄金', 1, 'XAUUSD', 'hf_GC', 'XAUUSD', '1334.01000', '1334.24000', '1334.01000', '1334.01000', '0.00000', 2, 0, 0, '2016-05-16 16:00:00', '2016-07-29 06:27:17'),
(12, '0.80', 0, '纽交所白银', 1, 'XAGUSD', 'hf_SI', 'XAGUSD', '20.07400', '20.07800', '20.07400', '20.07400', '0.00000', 3, 0, 0, '2016-05-16 16:00:00', '2016-07-29 06:27:17');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
