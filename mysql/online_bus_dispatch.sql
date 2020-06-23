-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Anamakine: 127.0.0.1:3308
-- Üretim Zamanı: 07 Haz 2020, 11:01:30
-- Sunucu sürümü: 8.0.18
-- PHP Sürümü: 7.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Veritabanı: `online_bus_dispatch`
--

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tblcouch`
--

DROP TABLE IF EXISTS `tblcouch`;
CREATE TABLE IF NOT EXISTS `tblcouch` (
  `couch_id` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`couch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `tblcouch`
--

INSERT INTO `tblcouch` (`couch_id`) VALUES
('1A'),
('1B'),
('2A'),
('2B'),
('3A'),
('3B');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tblschedule`
--

DROP TABLE IF EXISTS `tblschedule`;
CREATE TABLE IF NOT EXISTS `tblschedule` (
  `schedule_id` varchar(10) COLLATE utf8_turkish_ci NOT NULL,
  `origin_location` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `destination_location` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `departure_date` date DEFAULT NULL,
  `departure_time` time DEFAULT NULL,
  PRIMARY KEY (`schedule_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `tblschedule`
--

INSERT INTO `tblschedule` (`schedule_id`, `origin_location`, `destination_location`, `departure_date`, `departure_time`) VALUES
('3', 'KONYA', 'ANKARA', '2020-06-08', '16:00:00'),
('2', 'KONYA', 'BURSA', '2020-06-08', '19:00:00'),
('1', 'KONYA', 'ANKARA', '2020-06-08', '22:00:00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tblticket`
--

DROP TABLE IF EXISTS `tblticket`;
CREATE TABLE IF NOT EXISTS `tblticket` (
  `ticket_user_identity` varchar(11) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ticket_schedule_id` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ticket_departure_date` date DEFAULT NULL,
  `ticket_departure_time` time DEFAULT NULL,
  `ticket_couch_id` varchar(10) COLLATE utf8_turkish_ci DEFAULT NULL,
  `ticket_price` decimal(5,2) DEFAULT NULL,
  UNIQUE KEY `Un_Ticket` (`ticket_schedule_id`,`ticket_couch_id`),
  KEY `user_identity_fk` (`ticket_user_identity`),
  KEY `departure_date_fk` (`ticket_departure_date`),
  KEY `departure_time_fk` (`ticket_departure_time`),
  KEY `couch_id_fk` (`ticket_couch_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `tblticket`
--

INSERT INTO `tblticket` (`ticket_user_identity`, `ticket_schedule_id`, `ticket_departure_date`, `ticket_departure_time`, `ticket_couch_id`, `ticket_price`) VALUES
('38996290006', '1', '2020-06-08', '22:00:00', '1A', '55.00'),
('38996290006', '2', '2020-06-08', '19:00:00', '1A', '45.00'),
('38996290006', '1', '2020-06-08', '22:00:00', '3B', '75.00');

-- --------------------------------------------------------

--
-- Tablo için tablo yapısı `tbluser`
--

DROP TABLE IF EXISTS `tbluser`;
CREATE TABLE IF NOT EXISTS `tbluser` (
  `user_identity` varchar(11) COLLATE utf8_turkish_ci NOT NULL,
  `user_name` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `user_surname` varchar(50) COLLATE utf8_turkish_ci NOT NULL,
  `user_password` varchar(50) CHARACTER SET utf8 COLLATE utf8_turkish_ci NOT NULL,
  PRIMARY KEY (`user_identity`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_turkish_ci;

--
-- Tablo döküm verisi `tbluser`
--

INSERT INTO `tbluser` (`user_identity`, `user_name`, `user_surname`, `user_password`) VALUES
('12254185716', 'Merve', 'Alpu', '12345AB'),
('38996290006', 'Yunus Emre', 'Alpu', '12345ABC');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
