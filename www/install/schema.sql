SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;


DROP TABLE IF EXISTS `ArchivedMessages`;
CREATE TABLE IF NOT EXISTS `ArchivedMessages` ( `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(10) unsigned NOT NULL, `topic_id` int(10) unsigned NOT NULL, `message` varchar(2048) NOT NULL, `posted` int(10) unsigned NOT NULL, PRIMARY KEY (`message_id`), KEY `user_id` (`user_id`), KEY `topic_id` (`topic_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ArchivedTopics`;
CREATE TABLE IF NOT EXISTS `ArchivedTopics` (`topic_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(10) unsigned NOT NULL, `board_id` int(10) unsigned NOT NULL, `title` varchar(45) NOT NULL, `created` int(11) NOT NULL, PRIMARY KEY (`topic_id`), KEY `user_id` (`user_id`), KEY `board_id` (`board_id`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `BoardCategories`;
CREATE TABLE IF NOT EXISTS `BoardCategories` (`category_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `title` varchar(45) NOT NULL, PRIMARY KEY (`category_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Boards`;
CREATE TABLE IF NOT EXISTS `Boards` (`board_id` int(10) unsigned NOT NULL AUTO_INCREMENT, `category_id` int(10) unsigned NOT NULL, `title` varchar(45) NOT NULL, `description` varchar(45) NOT NULL, PRIMARY KEY (`board_id`), KEY `category_id` (`category_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `DisciplineHistory`;
CREATE TABLE IF NOT EXISTS `DisciplineHistory` (`discipline_id` int(11) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(11) unsigned NOT NULL, `mod_id` int(11) unsigned NOT NULL, `action_taken` varchar(1024) NOT NULL, `description` varchar(4096) NOT NULL, `plea_topic` int(11) unsigned DEFAULT NULL, `date` int(11) unsigned NOT NULL, `date_reversed` int(11) unsigned DEFAULT NULL, `description_reversed` varchar(4096) DEFAULT NULL, PRIMARY KEY (`discipline_id`), KEY `user_id` (`user_id`), KEY `mod_id` (`mod_id`), KEY `plea_topic` (`plea_topic`)) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Inventory`;
CREATE TABLE IF NOT EXISTS `Inventory` (`inventory_id` int(11) unsigned NOT NULL AUTO_INCREMENT, `user_id` int(11) unsigned NOT NULL, `transaction_id` int(11) unsigned NOT NULL, PRIMARY KEY(`inventory_id`), KEY `user_id` (`user_id`), KEY `transaction_id` (`transaction_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `InviteCodes`;
CREATE TABLE IF NOT EXISTS `InviteCodes` (`invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT, `invite_code` varchar(45) NOT NULL, `email` text NOT NULL, `user_id` int(11) unsigned NOT NULL, `created` int(11) unsigned NOT NULL, PRIMARY KEY (`invite_id`), KEY `user_id` (`user_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `InviteTree`;
CREATE TABLE IF NOT EXISTS `InviteTree` (`invite_id` int(11) unsigned NOT NULL AUTO_INCREMENT, `invited_by` int(11) unsigned NOT NULL, `Invited_user` int(11) unsigned DEFAULT NULL, `invite_code` varchar(45) NOT NULL, `email` text NOT NULL, `created` int(11) unsigned NOT NULL, PRIMARY KEY (`invite_id`), KEY `invited_by` (`invited_by`), KEY `Invited_user` (`Invited_user`), KEY `invite_code` (`invite_code`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ItemClass`;
CREATE TABLE IF NOT EXISTS `ItemClass` (`class_id` int(4) unsigned NOT NULL AUTO_INCREMENT,`type` varchar(12) NOT NULL, PRIMARY KEY (`class_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Karma`;
CREATE TABLE IF NOT EXISTS `Karma` (`karma_id` int(10) unsigned NOT NULL AUTO_INCREMENT,`user_id` int(11) unsigned NOT NULL,`value` int(4) NOT NULL,`created` int(11) NOT NULL,PRIMARY KEY (`karma_id`),KEY `user_id` (`user_id`)) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinkCategories`;
CREATE TABLE IF NOT EXISTS `LinkCategories` (
  `category_id` smallint(6) unsigned NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinkFavorites`;
CREATE TABLE IF NOT EXISTS `LinkFavorites` (
  `link_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  PRIMARY KEY (`link_id`,`user_id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinkHistory`;
CREATE TABLE IF NOT EXISTS `LinkHistory` (
  `link_history_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`link_history_id`),
  UNIQUE KEY `link_id` (`link_id`,`user_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinkMessages`;
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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Links`;
CREATE TABLE IF NOT EXISTS `Links` (
  `link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(80) NOT NULL,
  `url` varchar(512) NOT NULL,
  `description` text NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinksCategorized`;
CREATE TABLE IF NOT EXISTS `LinksCategorized` (
  `link_cat_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `link_id` int(11) unsigned NOT NULL,
  `category_id` smallint(6) unsigned NOT NULL,
  PRIMARY KEY (`link_cat_id`),
  KEY `link_id` (`link_id`,`category_id`),
  KEY `category_id` (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinksReported`;
CREATE TABLE IF NOT EXISTS `LinksReported` (
  `report_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned NOT NULL,
  `reason` varchar(1024) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  PRIMARY KEY (`report_id`),
  KEY `link_id` (`link_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `LinkVotes`;
CREATE TABLE IF NOT EXISTS `LinkVotes` (
  `user_id` int(11) unsigned NOT NULL,
  `link_id` int(11) unsigned NOT NULL,
  `vote` smallint(2) NOT NULL,
  `created` int(11) NOT NULL,
  PRIMARY KEY (`user_id`,`link_id`),
  KEY `user_id` (`user_id`),
  KEY `link_id` (`link_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Messages`;
CREATE TABLE IF NOT EXISTS `Messages` (
  `message_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `topic_id` int(10) unsigned NOT NULL,
  `revision_no` int(10) unsigned NOT NULL,
  `message` varchar(8192) NOT NULL,
  `posted` int(10) unsigned NOT NULL,
  PRIMARY KEY (`message_id`,`revision_no`),
  KEY `user_id` (`user_id`),
  KEY `topic_id` (`topic_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `Sessions`;
CREATE TABLE IF NOT EXISTS `Sessions` (
  `session_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `session_key1` varchar(64) NOT NULL,
  `session_key2` varchar(64) NOT NULL,
  `created` int(10) unsigned NOT NULL,
  `last_active` int(11) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `useragent` varchar(256) NOT NULL,
  PRIMARY KEY (`session_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ShopItems`;
CREATE TABLE IF NOT EXISTS `ShopItems` (
  `item_id` int(3) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(28) NOT NULL,
  `price` int(4) NOT NULL,
  `description` varchar(128) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `class_id` int(4) unsigned NOT NULL,
  PRIMARY KEY (`item_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `ShopTransactions`;
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

DROP TABLE IF EXISTS `SiteOptions`;
CREATE TABLE IF NOT EXISTS `SiteOptions` (
  `sitename` varchar(256) NOT NULL,
  `sitekey` varchar(512) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `StaffPermissions`;
CREATE TABLE IF NOT EXISTS `StaffPermissions` (
  `position_id` int(11) unsigned NOT NULL,
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
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `StaffPositions`;
CREATE TABLE IF NOT EXISTS `StaffPositions` (
  `position_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(32) NOT NULL,
  PRIMARY KEY (`position_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `StickiedTopics`;
CREATE TABLE IF NOT EXISTS `StickiedTopics` (
  `sticky_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `created` int(11) unsigned NOT NULL,
  `mod` int(1) NOT NULL,
  PRIMARY KEY (`sticky_id`),
  KEY `topic_id` (`topic_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;

DROP TABLE IF EXISTS `TopicHistory`;
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

DROP TABLE IF EXISTS `Topics`;
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

DROP TABLE IF EXISTS `Users`;
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
  `avatar` varchar(256) DEFAULT NULL,
  `signature` varchar(128) DEFAULT NULL,
  `quote` varchar(128) DEFAULT NULL,
  `timezone` varchar(45) NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `username_UNIQUE` (`username`),
  KEY `level` (`level`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1;


ALTER TABLE `ArchivedMessages`
  ADD CONSTRAINT `ArchivedMessages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ArchivedMessages_ibfk_2` FOREIGN KEY (`topic_id`) REFERENCES `ArchivedTopics` (`topic_id`);

ALTER TABLE `ArchivedTopics`
  ADD CONSTRAINT `ArchivedTopics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ArchivedTopics_ibfk_2` FOREIGN KEY (`board_id`) REFERENCES `Boards` (`board_id`);

ALTER TABLE `Boards`
  ADD CONSTRAINT `Boards_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `BoardCategories` (`category_id`);

ALTER TABLE `DisciplineHistory`
  ADD CONSTRAINT `DisciplineHistory_ibfk_3` FOREIGN KEY (`plea_topic`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `DisciplineHistory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `DisciplineHistory_ibfk_2` FOREIGN KEY (`mod_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `Inventory`
  ADD CONSTRAINT `Inventory_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Inventory_ibfk_2` FOREIGN KEY (`transaction_id`) REFERENCES `ShopTransactions` (`transaction_id`);

ALTER TABLE `InviteCodes`
  ADD CONSTRAINT `InviteCodes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `InviteTree`
  ADD CONSTRAINT `InviteTree_ibfk_1` FOREIGN KEY (`invited_by`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `InviteTree_ibfk_2` FOREIGN KEY (`Invited_user`) REFERENCES `Users` (`user_id`);

ALTER TABLE `Karma`
  ADD CONSTRAINT `Karma_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `LinkFavorites`
  ADD CONSTRAINT `LinkFavorites_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinkFavorites_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `LinkHistory`
  ADD CONSTRAINT `LinkHistory_ibfk_1` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinkHistory_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `LinkMessages`
  ADD CONSTRAINT `LinkMessages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinkMessages_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

ALTER TABLE `Links`
  ADD CONSTRAINT `Links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `LinksCategorized`
  ADD CONSTRAINT `LinksCategorized_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`),
  ADD CONSTRAINT `LinksCategorized_ibfk_3` FOREIGN KEY (`category_id`) REFERENCES `LinkCategories` (`category_id`);

ALTER TABLE `LinksReported`
  ADD CONSTRAINT `LinksReported_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinksReported_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

ALTER TABLE `LinkVotes`
  ADD CONSTRAINT `LinkVotes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `LinkVotes_ibfk_2` FOREIGN KEY (`link_id`) REFERENCES `Links` (`link_id`);

ALTER TABLE `Messages`
  ADD CONSTRAINT `Messages_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `Messages_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `Sessions`
  ADD CONSTRAINT `Sessions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `ShopTransactions`
  ADD CONSTRAINT `ShopTransactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `ShopTransactions_ibfk_2` FOREIGN KEY (`item_id`) REFERENCES `ShopItems` (`item_id`);

ALTER TABLE `StaffPermissions`
  ADD CONSTRAINT `StaffPermissions_ibfk_1` FOREIGN KEY (`position_id`) REFERENCES `StaffPositions` (`position_id`);

ALTER TABLE `StickiedTopics`
  ADD CONSTRAINT `StickiedTopics_ibfk_1` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `StickiedTopics_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`);

ALTER TABLE `TopicHistory`
  ADD CONSTRAINT `TopicHistory_ibfk_6` FOREIGN KEY (`topic_id`) REFERENCES `Topics` (`topic_id`),
  ADD CONSTRAINT `TopicHistory_ibfk_7` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `TopicHistory_ibfk_8` FOREIGN KEY (`message_id`) REFERENCES `Messages` (`message_id`);

ALTER TABLE `Topics`
  ADD CONSTRAINT `Topics_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `Users` (`user_id`),
  ADD CONSTRAINT `Topics_ibfk_2` FOREIGN KEY (`board_id`) REFERENCES `Boards` (`board_id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

INSERT INTO `BoardCategories` (`category_id`, `title`) VALUES
(1, 'Social Boards'),
(2, 'Special Boards');

INSERT INTO `Boards` (`board_id`, `category_id`, `title`, `description`) VALUES
(42, 1, 'Life, the Universe, and Everything', 'What ever.'),
(754, 2, 'Fish Farm', 'Staff only');

INSERT INTO `ItemClass` (`class_id`, `type`) VALUES
(1, 'topic');

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

INSERT INTO `ShopItems` (`item_id`, `name`, `price`, `description`, `active`, `class_id`) VALUES
(4, 'Invite', 50, 'Buy an invite to give to another user.', 1, 0),
(5, 'Pin Topic', 10, 'pin a topic on the main board for 24 hours', 1, 1);

INSERT INTO `StaffPositions` (`position_id`, `title`) VALUES
(1, 'Adminstrator');

INSERT INTO `StaffPermissions` (`position_id`, `user_ban`, `user_edit`, `user_suspend`, `user_maps`, `link_reports`, `link_delete`, `link_vote`, `link_edit`, `link_view_deleted`, `topic_close`, `topic_delete_message`, `topic_message_history`, `topic_pin`) VALUES
(1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1, 1);

