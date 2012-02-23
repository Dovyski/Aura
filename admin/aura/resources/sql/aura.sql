SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `commands` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `type` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `type` (`type`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `command_log` (
  `fk_command` int(10) unsigned NOT NULL,
  `fk_device` int(10) unsigned NOT NULL,
  `time_start` int(10) unsigned NOT NULL,
  `time_end` int(10) unsigned NOT NULL,
  `result` text NOT NULL,
  PRIMARY KEY (`fk_command`,`fk_device`),
  KEY `fk_device` (`fk_device`),
  KEY `fk_command` (`fk_command`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `desc` int(255) NOT NULL,
  `hash` int(32) NOT NULL,
  `type` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `grooming` (
  `fk_group` int(10) unsigned NOT NULL,
  `fk_device` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  UNIQUE KEY `unique` (`fk_group`,`fk_device`),
  KEY `fk_device` (`fk_device`),
  KEY `fk_group` (`fk_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `groups` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `desc` varchar(255) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `name` (`name`,`alias`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pings` (
  `fk_device` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `data` text NOT NULL,
  PRIMARY KEY (`fk_device`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `command_log`
  ADD CONSTRAINT `command_log_ibfk_2` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `command_log_ibfk_1` FOREIGN KEY (`fk_command`) REFERENCES `commands` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `grooming`
  ADD CONSTRAINT `grooming_ibfk_2` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grooming_ibfk_1` FOREIGN KEY (`fk_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pings`
  ADD CONSTRAINT `pings_ibfk_1` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
