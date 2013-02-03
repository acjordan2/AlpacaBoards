SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Dumping data for table `BoardCategories`
--

INSERT INTO `BoardCategories` (`category_id`, `title`) VALUES
(1, 'Social Boards'),
(2, 'Special Boards');

--
-- Dumping data for table `Boards`
--

INSERT INTO `Boards` (`board_id`, `category_id`, `title`, `description`) VALUES
(42, 1, 'Life, the Universe, and Everything', 'A loosely Moderated Board');

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
-- Dumping data for table `Users`
--

INSERT INTO `Users` (`user_id`, `username`, `email`, `private_email`, `instant_messaging`, `password`, `old_password`, `account_created`, `last_active`, `status`, `avatar`, `signature`, `quote`, `timezone`) VALUES
(1, 'admin', 'admin@example.com', 'admin@example.com', 'Admin', '$8V9monSKrKy3Te0Ihj3VhA==$b25f8e2690485633b2d1240c05db7cf13b61e59f51dd70c4e9186218e681a95e', NULL, 1359611540, NULL, 0, NULL, 'Sample Signature', NULL, '');

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
