-- Eftakhairul Islam <eftakhairul@gmail.com>
-- Dated: 23 March, 2013.

SET FOREIGN_KEY_CHECKS=0;
SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS `customer` (
  `First_Name` char(50) DEFAULT NULL,
  `Last_Name` char(50) DEFAULT NULL,
  `Address` char(50) DEFAULT NULL,
  `City` char(50) DEFAULT NULL,
  `Country` char(25) DEFAULT NULL,
  `Birth_Date` date DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE IF NOT EXISTS `grace_statuses` (
  `grace_status_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(30) NOT NULL,
  PRIMARY KEY (`grace_status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=10 ;

CREATE TABLE IF NOT EXISTS `groups` (
  `group_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `associated_name` varchar(150) NOT NULL,
  `print_title` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=20 ;

CREATE TABLE IF NOT EXISTS `profiles` (
  `profile_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `user_id` smallint(6) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email_address` varchar(100) NOT NULL,
  PRIMARY KEY (`profile_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=41 ;

CREATE TABLE IF NOT EXISTS `schedules` (
  `schedule_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(150) NOT NULL,
  `description` text NOT NULL,
  `venue` varchar(100) NOT NULL,
  `status_id` tinyint(2) NOT NULL,
  `grace_status_id` tinyint(2) NOT NULL,
  `date` date NOT NULL,
  `is_date_not_confirmed` tinyint(1) NOT NULL,
  `time` time NOT NULL,
  `is_time_not_confirmed` tinyint(1) NOT NULL,
  `created_date` date NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `group_id` smallint(6) NOT NULL,
  PRIMARY KEY (`schedule_id`),
  KEY `status_id` (`status_id`),
  KEY `grace_status_id` (`grace_status_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=3013 ;

CREATE TABLE IF NOT EXISTS `statuses` (
  `status_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

CREATE TABLE IF NOT EXISTS `users` (
  `user_id` smallint(6) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(35) NOT NULL,
  `user_type_id` tinyint(2) NOT NULL,
  `created_date` date NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `group_id` smallint(6) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username` (`username`),
  KEY `user_type_id` (`user_type_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=43 ;

CREATE TABLE IF NOT EXISTS `user_types` (
  `user_type_id` tinyint(2) NOT NULL AUTO_INCREMENT,
  `title` varchar(20) NOT NULL,
  PRIMARY KEY (`user_type_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=4 ;

CREATE TABLE IF NOT EXISTS `venues` (
  `venue_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  PRIMARY KEY (`venue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

CREATE TABLE IF NOT EXISTS `venues_meetings` (
  `venue_meeting_id` int(11) NOT NULL AUTO_INCREMENT,
  `group_id` smallint(6) NOT NULL,
  `topic` varchar(100) NOT NULL,
  `description` text,
  `contact_person` varchar(200) NOT NULL,
  `venue_id` int(11) NOT NULL,
  `starting_date_time` datetime NOT NULL,
  `ending_date_time` datetime NOT NULL,
  `created_date` date NOT NULL,
  `modified_date` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' ON UPDATE CURRENT_TIMESTAMP,
  `is_cancelled` tinyint(1) NOT NULL,
  PRIMARY KEY (`venue_meeting_id`),
  KEY `group_id` (`group_id`),
  KEY `venue_id` (`venue_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=135 ;


ALTER TABLE `profiles`
  ADD CONSTRAINT `profiles_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_ibfk_1` FOREIGN KEY (`status_id`) REFERENCES `statuses` (`status_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `schedules_ibfk_2` FOREIGN KEY (`grace_status_id`) REFERENCES `grace_statuses` (`grace_status_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`user_type_id`) REFERENCES `user_types` (`user_type_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `venues_meetings`
  ADD CONSTRAINT `venues_meetings_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `venues_meetings_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`venue_id`) ON DELETE CASCADE ON UPDATE CASCADE;
SET FOREIGN_KEY_CHECKS=1;
