truncate users;
truncate friends;
truncate friends_tweets;
truncate tweets;

-- Adminer 3.3.0-dev MySQL dump

SET NAMES utf8;
SET foreign_key_checks = 0;
SET time_zone = 'SYSTEM';
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

DROP TABLE IF EXISTS `friends`;
CREATE TABLE `friends` (
  `friend_id` bigint(20) NOT NULL,
  `screen_name` varchar(255) DEFAULT NULL,
  `profile_image_url` varchar(255) DEFAULT NULL,
  `url` varchar(255) DEFAULT NULL,
  `user_id` bigint(20) NOT NULL,
  `last_update` datetime DEFAULT NULL,
  PRIMARY KEY (`friend_id`),
  UNIQUE KEY `friend_id` (`friend_id`,`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


DROP TABLE IF EXISTS `friends_tweets`;
CREATE TABLE `friends_tweets` (
  `friend_id` bigint(20) NOT NULL,
  `tweet_id` bigint(20) NOT NULL,
  `created` datetime DEFAULT NULL,
  PRIMARY KEY (`friend_id`,`tweet_id`),
  UNIQUE KEY `friend_id` (`friend_id`,`tweet_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


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
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


-- 2011-06-06 16:09:18