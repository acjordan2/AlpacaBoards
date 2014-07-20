-- phpMyAdmin SQL Dump
-- version 4.0.6deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 01, 2014 at 12:27 AM
-- Server version: 5.5.37-0ubuntu0.13.10.1
-- PHP Version: 5.5.3-1ubuntu2.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `spergs`
--

-- --------------------------------------------------------

--
-- Table structure for table `ArchivedMessages`
--

CREATE TABLE IF NOT EXISTS `ArchivedMessages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned NOT NULL,
  `message` varchar(2048) NOT NULL,
  `posted` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ArchivedTopics`
--

CREATE TABLE IF NOT EXISTS `ArchivedTopics` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `board_id` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `board_id` (`board_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `BoardCategories`
--

CREATE TABLE IF NOT EXISTS `BoardCategories` (
  `category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(45) NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Boards`
--

CREATE TABLE IF NOT EXISTS `Boards` (
  `board_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `title` varchar(45) NOT NULL,
  `description` varchar(45) NOT NULL,
  PRIMARY KEY (`board_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `DisciplineHistory`
--

CREATE TABLE IF NOT EXISTS `DisciplineHistory` (
  `discipline_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `mod_id` int(11) unsigned NOT NULL,
  `action_taken` varchar(1024) NOT NULL,
  `description` varchar(4096) NOT NULL,
  `plea_topic` int(11) unsigned DEFAULT NULL,
  `date` int(11) unsigned NOT NULL,
  `date_reversed` int(11) unsigned DEFAULT NULL,
  `description_reversed` varchar(4096) DEFAULT NULL,
  PRIMARY KEY (`discipline_id`),
  KEY `user_id` (`user_id`),
  KEY `mod_id` (`mod_id`),
  KEY `plea_topic` (`plea_topic`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ImageMap`
--

CREATE TABLE IF NOT EXISTS `ImageMap` (
  `map_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `image_id` int(11) NOT NULL,
  `topic_id` int(11) NOT NULL,
  PRIMARY KEY (`map_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Inventory`
--

CREATE TABLE IF NOT EXISTS `Inventory` (
  `inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `transaction_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`inventory_id`),
  KEY `user_id` (`user_id`),
  KEY `transaction_id` (`transaction_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `InviteCodes`
--

CREATE TABLE IF NOT EXISTS `InviteCodes` (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invite_code` varchar(45) NOT NULL,
  `email` text NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`invite_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `InviteTree`
--

CREATE TABLE IF NOT EXISTS `InviteTree` (
  `invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `invited_by` int(11) unsigned NOT NULL,
  `Invited_user` int(11) unsigned DEFAULT NULL,
  `invite_code` varchar(45) NOT NULL,
  `email` text NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`invite_id`),
  KEY `invited_by` (`invited_by`),
  KEY `Invited_user` (`Invited_user`),
  KEY `invite_code` (`invite_code`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ItemClass`
--

CREATE TABLE IF NOT EXISTS `ItemClass` (
  `class_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(12) NOT NULL,
  PRIMARY KEY (`class_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Karma`
--

CREATE TABLE IF NOT EXISTS `Karma` (
  `karma_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `value` int(4) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`karma_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinkCategories`
--

CREATE TABLE IF NOT EXISTS `LinkCategories` (
  `category_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinkFavorites`
--

CREATE TABLE IF NOT EXISTS `LinkFavorites` (
  `link_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`user_id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinkHistory`
--

CREATE TABLE IF NOT EXISTS `LinkHistory` (
  `link_history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`link_history_id`),
  UNIQUE KEY `link_id` (`link_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinkMessages`
--

CREATE TABLE IF NOT EXISTS `LinkMessages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned NOT NULL,
  `revision_no` int(11) unsigned NOT NULL,
  `message` varchar(5120) NOT NULL,
  `posted` int(11) unsigned NOT NULL,
  PRIMARY KEY (`message_id`,`revision_no`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Links`
--

CREATE TABLE IF NOT EXISTS `Links` (
  `link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `url` varchar(512) DEFAULT NULL,
  `description` text NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinksCategorized`
--

CREATE TABLE IF NOT EXISTS `LinksCategorized` (
  `link_cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(11) unsigned NOT NULL,
  `category_id` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`link_cat_id`),
  KEY `link_id` (`link_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinksReported`
--

CREATE TABLE IF NOT EXISTS `LinksReported` (
  `report_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `LinkVotes`
--

CREATE TABLE IF NOT EXISTS `LinkVotes` (
  `user_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned NOT NULL,
  `vote` smallint(2) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`link_id`),
  KEY `user_id` (`user_id`),
  KEY `link_id` (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Messages`
--

CREATE TABLE IF NOT EXISTS `Messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned NOT NULL,
  `revision_no` int(10) unsigned NOT NULL,
  `message` varchar(8192) NOT NULL,
  `deleted` int(1) NOT NULL,
  `posted` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`,`revision_no`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `PasswordResetRequests`
--

CREATE TABLE IF NOT EXISTS `PasswordResetRequests` (
  `request_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `request_ip` varchar(15) NOT NULL,
  `token` varchar(32) NOT NULL,
  `used` int(1) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`request_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Sessions`
--

CREATE TABLE IF NOT EXISTS `Sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `session_key1` varchar(64) NOT NULL,
  `session_key2` varchar(64) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `last_active` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `x_forwarded_for` varchar(16) DEFAULT NULL,
  `useragent` varchar(256) NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ShopItems`
--

CREATE TABLE IF NOT EXISTS `ShopItems` (
  `item_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(28) NOT NULL,
  `price` int(4) NOT NULL,
  `description` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `class_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `ShopTransactions`
--

CREATE TABLE IF NOT EXISTS `ShopTransactions` (
  `transaction_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `item_id` int(4) unsigned NOT NULL,
  `value` int(4) NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`transaction_id`),
  KEY `user_id` (`user_id`),
  KEY `item_id` (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `SiteOptions`
--

CREATE TABLE IF NOT EXISTS `SiteOptions` (
  `sitename` varchar(256) NOT NULL,
  `domain` varchar(256) NOT NULL,
  `sitekey` varchar(512) NOT NULL,
  `registration` int(1) NOT NULL,
  `invites` int(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StaffPermissions`
--

CREATE TABLE IF NOT EXISTS `StaffPermissions` (
  `position_id` int(11) unsigned NOT NULL,
  `title` varchar(32) NOT NULL,
  `title_color` varchar(12) NOT NULL,
  `user_ban` int(11) NOT NULL,
  `user_edit` int(1) unsigned NOT NULL,
  `user_suspend` int(1) unsigned NOT NULL,
  `user_maps` int(1) unsigned NOT NULL,
  `link_reports` int(1) unsigned NOT NULL,
  `link_delete` int(1) unsigned NOT NULL,
  `link_vote` int(1) unsigned NOT NULL,
  `link_edit` int(1) unsigned NOT NULL,
  `link_view_deleted` int(1) unsigned NOT NULL,
  `topic_close` int(1) unsigned NOT NULL,
  `topic_delete_message` int(1) unsigned NOT NULL,
  `topic_message_history` int(1) unsigned NOT NULL,
  `topic_pin` int(1) unsigned NOT NULL,
  `site_options` int(1) NOT NULL,
  `tag_create` int(1) unsigned NOT NULL,
  `tag_edit` int(1) unsigned NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StaffPositions`
--

CREATE TABLE IF NOT EXISTS `StaffPositions` (
  `position_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `StickiedTopics`
--

CREATE TABLE IF NOT EXISTS `StickiedTopics` (
  `sticky_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `mod` int(1) NOT NULL,
  PRIMARY KEY (`sticky_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Tagged`
--

CREATE TABLE IF NOT EXISTS `Tagged` (
  `tagged_id` int(11) NOT NULL AUTO_INCREMENT,
  `data_id` int(11) NOT NULL,
  `tag_id` int(11) NOT NULL,
  `type` int(1) NOT NULL,
  PRIMARY KEY (`tagged_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TopicalTagParentRelationship`
--

CREATE TABLE IF NOT EXISTS `TopicalTagParentRelationship` (
  `relation_id` int(11) NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL,
  `child_id` int(11) NOT NULL,
  PRIMARY KEY (`relation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TopicalTags`
--

CREATE TABLE IF NOT EXISTS `TopicalTags` (
  `tag_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(128) NOT NULL,
  `description` varchar(256) NOT NULL,
  `type` int(1) NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`tag_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `TopicHistory`
--

CREATE TABLE IF NOT EXISTS `TopicHistory` (
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `message_id` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  `page` int(5) unsigned NOT NULL,
  PRIMARY KEY (`topic_id`,`user_id`),
  KEY `message_id` (`message_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Topics`
--

CREATE TABLE IF NOT EXISTS `Topics` (
  `topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `board_id` int(10) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `user_id` (`user_id`),
  KEY `board_id` (`board_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UploadedImages`
--

CREATE TABLE IF NOT EXISTS `UploadedImages` (
  `image_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `sha1_sum` varchar(40) NOT NULL,
  `width` int(5) NOT NULL,
  `height` int(5) NOT NULL,
  `thumb_width` int(3) NOT NULL,
  `thumb_height` int(3) NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`image_id`),
  UNIQUE KEY `sha1_sum` (`sha1_sum`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `UploadLog`
--

CREATE TABLE IF NOT EXISTS `UploadLog` (
  `uploadlog_id` int(11) NOT NULL AUTO_INCREMENT,
  `image_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `filename` varchar(256) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`uploadlog_id`),
  KEY `image_id` (`image_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

CREATE TABLE IF NOT EXISTS `Users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(45) NOT NULL,
  `email` varchar(45) DEFAULT NULL,
  `private_email` varchar(45) DEFAULT NULL,
  `instant_messaging` varchar(45) DEFAULT NULL,
  `password` varchar(90) NOT NULL,
  `old_password` varchar(32) DEFAULT NULL,
  `account_created` int(11) DEFAULT NULL,
  `last_active` int(11) DEFAULT NULL,
  `status` int(3) DEFAULT NULL,
  `level` int(11) unsigned NOT NULL,
  `avatar` int(11) unsigned DEFAULT NULL,
  `avatar_id` int(11) unsigned DEFAULT NULL,
  `signature` varchar(128) DEFAULT NULL,
  `quote` varchar(128) DEFAULT NULL,
  `timezone` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `level` (`level`),
  KEY `avatar_id` (`avatar_id`),
  KEY `avatar` (`avatar`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `ArchivedMessages`
--
ALTER TABLE `ArchivedMessages`
  ADD CONSTRAINT `ArchivedMessages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ArchivedMessages_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `ArchivedTopics` (`topic_id`);

--
-- Constraints for table `ArchivedTopics`
--
ALTER TABLE `ArchivedTopics`
  ADD CONSTRAINT `ArchivedTopics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ArchivedTopics_ibfk_2` FOREIGN KEY (`board_id`) REFERENCES `Boards` (`board_id`);

--
-- Constraints for table `Boards`
--
ALTER TABLE `Boards`
  ADD CONSTRAINT `Boards_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `BoardCategories` (`category_id`);

--
-- Constraints for table `DisciplineHistory`
--
ALTER TABLE `DisciplineHistory`
  ADD CONSTRAINT `DisciplineHistory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `DisciplineHistory_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `DisciplineHistory_ibfk_3` FOREIGN KEY (`plea_topic`) REFERENCES `Topics` (`topic_id`);

--
-- Constraints for table `Inventory`
--
ALTER TABLE `Inventory`
  ADD CONSTRAINT `Inventory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Inventory_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `ShopTransactions` (`transaction_id`);

--
-- Constraints for table `InviteCodes`
--
ALTER TABLE `InviteCodes`
  ADD CONSTRAINT `InviteCodes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `InviteTree`
--
ALTER TABLE `InviteTree`
  ADD CONSTRAINT `InviteTree_ibfk_1` FOREIGN KEY (`invited_by`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `InviteTree_ibfk_2` FOREIGN KEY (`Invited_user`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `Karma`
--
ALTER TABLE `Karma`
  ADD CONSTRAINT `Karma_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `LinkFavorites`
--
ALTER TABLE `LinkFavorites`
  ADD CONSTRAINT `LinkFavorites_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinkFavorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `LinkHistory`
--
ALTER TABLE `LinkHistory`
  ADD CONSTRAINT `LinkHistory_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinkHistory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `LinkMessages`
--
ALTER TABLE `LinkMessages`
  ADD CONSTRAINT `LinkMessages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinkMessages_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

--
-- Constraints for table `Links`
--
ALTER TABLE `Links`
  ADD CONSTRAINT `Links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `LinksCategorized`
--
ALTER TABLE `LinksCategorized`
  ADD CONSTRAINT `LinksCategorized_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinksCategorized_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `LinkCategories` (`category_id`);

--
-- Constraints for table `LinksReported`
--
ALTER TABLE `LinksReported`
  ADD CONSTRAINT `LinksReported_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinksReported_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

--
-- Constraints for table `LinkVotes`
--
ALTER TABLE `LinkVotes`
  ADD CONSTRAINT `LinkVotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinkVotes_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

--
-- Constraints for table `Messages`
--
ALTER TABLE `Messages`
  ADD CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `PasswordResetRequests`
--
ALTER TABLE `PasswordResetRequests`
  ADD CONSTRAINT `PasswordResetRequests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Sessions`
--
ALTER TABLE `Sessions`
  ADD CONSTRAINT `Sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `ShopTransactions`
--
ALTER TABLE `ShopTransactions`
  ADD CONSTRAINT `ShopTransactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ShopTransactions_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ShopItems` (`item_id`);

--
-- Constraints for table `StaffPermissions`
--
ALTER TABLE `StaffPermissions`
  ADD CONSTRAINT `StaffPermissions_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `StaffPositions` (`position_id`);

--
-- Constraints for table `StickiedTopics`
--
ALTER TABLE `StickiedTopics`
  ADD CONSTRAINT `StickiedTopics_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `StickiedTopics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

--
-- Constraints for table `TopicHistory`
--
ALTER TABLE `TopicHistory`
  ADD CONSTRAINT `TopicHistory_ibfk_6` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `TopicHistory_ibfk_7` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `TopicHistory_ibfk_8` FOREIGN KEY (`message_id`) REFERENCES `Messages` (`message_id`);

--
-- Constraints for table `Topics`
--
ALTER TABLE `Topics`
  ADD CONSTRAINT `Topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Topics_ibfk_2` FOREIGN KEY (`board_id`) REFERENCES `Boards` (`board_id`);

--
-- Constraints for table `UploadedImages`
--
ALTER TABLE `UploadedImages`
  ADD CONSTRAINT `UploadedImages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UploadLog`
--
ALTER TABLE `UploadLog`
  ADD CONSTRAINT `UploadLog_ibfk_1` FOREIGN KEY (`image_id`) REFERENCES `UploadedImages` (`image_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UploadLog_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Users`
--
ALTER TABLE `Users`
  ADD CONSTRAINT `Users_ibfk_3` FOREIGN KEY (`avatar_id`) REFERENCES `UploadedImages` (`image_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;


INSERT INTO `BoardCategories` (`category_id`, `title`) VALUES
(1, 'Social Boards'),
(2, 'Special Boards');

--
-- Dumping data for table `Boards`
--

INSERT INTO `Boards` (`board_id`, `category_id`, `title`, `description`) VALUES
(42, 1, 'Life, the Universe, and Everything', 'What ever.'),
(754, 2, 'Fish Farm', 'Staff only');

--
-- Dumping data for table `ItemClass`
--

INSERT INTO `ItemClass` (`class_id`, `type`) VALUES
(1, 'topic');

--
-- Dumping data for table `LinkCategories`
--

INSERT INTO `LinkCategories` (`category_id`, `name`) VALUES
(1, 'Funny'),
(2, 'News'),
(3, 'Videos'),
(4, 'Educational'),
(5, 'Wacky'),
(6, 'Pictures'),
(7, 'Fighting'),
(8, 'Trailers'),
(9, 'Music'),
(10, 'Adult'),
(11, 'Sports'),
(12, 'Gaming'),
(13, 'Fail'),
(14, 'Website');

--
-- Dumping data for table `ShopItems`
--

INSERT INTO `ShopItems` (`item_id`, `name`, `price`, `description`, `active`, `class_id`) VALUES
(4, 'Invite', 50, 'Buy an invite to give to another user.', 1, 0),
(5, 'Pin Topic', 10, 'pin a topic on the main board for 24 hours', 1, 1);

--
-- Dumping data for table `SiteOptions`
--

INSERT INTO `SiteOptions` (`sitename`, `registration`, `invites`) VALUES
('Sper.gs', 2, 2);



--
-- Dumping data for table `StaffPositions`
--

INSERT INTO `StaffPositions` (`position_id`, `title`) VALUES
(1, 'Administrator');

--
-- Dumping data for table `StaffPermissions`
--

INSERT INTO `StaffPermissions` (`position_id`, `title`, `title_color`, `user_ban`, `user_edit`, `user_suspend`, `user_maps`, `link_reports`, `link_delete`, `link_vote`, `link_edit`, `link_view_deleted`, `topic_close`, `topic_delete_message`, `topic_message_history`, `topic_pin`, `site_options`, `tag_edit`, `tag_create`) VALUES
(1, "Administrator", "red", 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

INSERT INTO `TopicalTags` (`tag_id`, `title`, `description`, `type`, `user_id`, `created`)
VALUES (1, 'LUE', 'Main Social Board', 1, 1, UNIX_TIMESTAMP(NOW()));


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

ALTER TABLE  `DisciplineHistory` ADD  `message_id` INT( 11 ) UNSIGNED NULL AFTER  `mod_id` ;

