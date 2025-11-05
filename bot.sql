-- -------------------------------------------------------------
-- TablePlus 6.7.2(638)
--
-- https://tableplus.com/
--
-- Database: bot
-- Generation Time: 2025-11-05 20:52:53.4310
-- -------------------------------------------------------------


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


DROP TABLE IF EXISTS `chatbot`;
CREATE TABLE `chatbot` (
  `id` int NOT NULL AUTO_INCREMENT,
  `queries` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `history_chat`;
CREATE TABLE `history_chat` (
  `id` int NOT NULL AUTO_INCREMENT,
  `text` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `replay` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

DROP TABLE IF EXISTS `learning`;
CREATE TABLE `learning` (
  `id` int NOT NULL AUTO_INCREMENT,
  `queries` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `replies` varchar(300) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `chatbot` (`id`, `queries`, `replies`) VALUES
(1, 'hi', 'hello amro'),
(2, 'hello', 'fuck you'),
(3, 'hi mom', 'i love you'),
(4, 'Pauloes', ' hi boss'),
(5, 'joho', ' hi bro'),
(6, 'how can i teach you ?', 'just write in admin');

INSERT INTO `history_chat` (`id`, `text`, `replay`) VALUES
(1, 'repliaed_with:', 0),
(2, 'hi', 1),
(3, 'hi mom', 0),
(4, 'replay_with:i love you', 0),
(5, 'hi mom', 1),
(6, 'hi mom', 1),
(7, 'test love ', 0),
(8, 'Pauloes', 0),
(9, 'replay_with: hi boss', 0),
(10, 'Pauloes', 1),
(11, 'Pauloes', 1),
(12, 'joho', 0),
(13, 'replay_with: hi bro', 0),
(14, 'joho', 1),
(15, 'hi', 1),
(16, 'hi', 1),
(17, 'how can i', 0),
(18, 'how can i teach you ?', 0),
(19, 'how can i teach you ?', 1),
(20, 'hi', 1),
(21, 'ho\\', 0),
(22, 'ho', 0),
(23, 'hi', 1);

INSERT INTO `learning` (`id`, `queries`, `replies`) VALUES
(3, 'hello to mom', '?'),
(4, 'repliaed_with:', '?'),
(5, 'hi mom', '?'),
(6, 'replay_with:i love you', '?'),
(7, 'test love ', '?'),
(9, 'replay_with: hi boss', '?'),
(11, 'replay_with: hi bro', '?'),
(12, 'how can i', '?'),
(14, 'ho\\', '?'),
(15, 'ho', '?');



/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;