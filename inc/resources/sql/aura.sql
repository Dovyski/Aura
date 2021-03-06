SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


CREATE TABLE IF NOT EXISTS `devices` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(30) NOT NULL,
  `alias` varchar(255) NOT NULL DEFAULT '',
  `desc` varchar(255) NOT NULL DEFAULT '',
  `hash` varchar(32) NOT NULL DEFAULT '',
  `type` int(11) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  KEY `hash` (`hash`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

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
  UNIQUE KEY `name` (`name`),
  KEY `alias` (`alias`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `active_devices` (
  `fk_device` int(10) unsigned NOT NULL,
  `client` varchar(255) NOT NULL,
  `os` varchar(100) NOT NULL,
  `data` text NOT NULL,
  `time` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fk_device`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `active_users` (
  `fk_device` int(10) unsigned NOT NULL,
  `user_name` varchar(60) NOT NULL,
  `time` int(10) unsigned NOT NULL,
  UNIQUE KEY `fk_device` (`fk_device`,`user_name`),
  KEY `time` (`time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `pings` (
  `fk_device` int(10) unsigned NOT NULL,
  `time` int(10) unsigned NOT NULL,
  `client` varchar(255) NOT NULL DEFAULT '',
  `data` text NOT NULL,
  KEY `time` (`time`),
  KEY `fk_device` (`fk_device`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `time` int(10) unsigned NOT NULL,
  `priority` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  `exec` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `priority` (`priority`),
  KEY `status` (`status`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tasks_log` (
  `fk_task` int(10) unsigned NOT NULL,
  `fk_device` int(10) unsigned NOT NULL DEFAULT '0',
  `time_start` int(10) unsigned NOT NULL DEFAULT '0',
  `time_end` int(10) unsigned NOT NULL DEFAULT '0',
  `result` text NOT NULL,
  PRIMARY KEY (`fk_task`,`fk_device`),
  KEY `fk_device` (`fk_device`),
  KEY `fk_task` (`fk_task`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `teams` (
  `fk_user` varchar(30) NOT NULL,
  `fk_group` int(10) unsigned NOT NULL,
  PRIMARY KEY (`fk_user`,`fk_group`),
  KEY `fk_group` (`fk_group`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `users` (
  `login` varchar(30) NOT NULL,
  `name` varchar(60) NOT NULL,
  `type` int(11) NOT NULL DEFAULT '0',
  `contact` text NOT NULL,
  `email` varchar(100) NOT NULL,
  `alias` varchar(255) NOT NULL,
  PRIMARY KEY (`login`),
  KEY `alias` (`alias`),
  KEY `name` (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `grooming`
  ADD CONSTRAINT `grooming_ibfk_1` FOREIGN KEY (`fk_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `grooming_ibfk_2` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `pings`
  ADD CONSTRAINT `pings_ibfk_1` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `tasks_log`
  ADD CONSTRAINT `tasks_log_ibfk_1` FOREIGN KEY (`fk_task`) REFERENCES `tasks` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tasks_log_ibfk_2` FOREIGN KEY (`fk_device`) REFERENCES `devices` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`fk_user`) REFERENCES `users` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `teams_ibfk_2` FOREIGN KEY (`fk_group`) REFERENCES `groups` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
