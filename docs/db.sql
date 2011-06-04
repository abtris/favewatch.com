-- Adminer 3.3.0-dev MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `tweets`;
CREATE TABLE `tweets` (
  `tweet_id` bigint(20) NOT NULL,
  `user_id` bigint(20) NOT NULL,
  `profile_image_url` text NOT NULL,
  `text` text NOT NULL,
  `created_at` bigint(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `screen_name` varchar(255) NOT NULL,
  `inserted_at` datetime DEFAULT NULL,
  PRIMARY KEY (`tweet_id`),
  UNIQUE KEY `tweet_id` (`tweet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `user_id` bigint(20) NOT NULL,
  `last_login_at` datetime NOT NULL,
  `first_login_at` datetime NOT NULL,
  `friends` longtext,
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2011-06-04 18:13:40
