SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;

-- Table structure for table `media`
DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `post_id` int(11) DEFAULT NULL,
    `caption` longtext COLLATE utf8mb4_unicode_ci,
    `creation_date` datetime NOT NULL,
    `filename` longtext COLLATE utf8mb4_unicode_ci,
    `extension` longtext COLLATE utf8mb4_unicode_ci,
    `width` longtext COLLATE utf8mb4_unicode_ci,
    `height` longtext COLLATE utf8mb4_unicode_ci,
    `tags` longtext COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`id`),
    KEY (`post_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Table structure for table `post`
DROP TABLE IF EXISTS `post`;
CREATE TABLE IF NOT EXISTS `post` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `text` longtext COLLATE utf8mb4_unicode_ci,
    `happened_date` datetime NOT NULL,
    `creation_date` datetime NOT NULL,
    `medias` longtext COLLATE utf8mb4_unicode_ci,
    `tags` longtext COLLATE utf8mb4_unicode_ci,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Table structure `user`
DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
    `id` int(11) NOT NULL,
    `username` varchar(255) NOT NULL,
    `password` varchar(255) NOT NULL,
    `creationDate` datetime NOT NULL,
    `lastLogin` datetime DEFAULT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


INSERT INTO `user` (`id`, `username`, `password`, `creationDate`)
VALUES (1, 'Admin', '$2y$10$0m5pGWXI5ZcSCcvsm/v3Zu69G2KVRZHJz8UYMdUSI2x83r2XY.xhO', NOW());

-- Constraints for table `media`
ALTER TABLE `media`
    ADD CONSTRAINT FOREIGN KEY (`post_id`) REFERENCES `post` (`id`);
    SET FOREIGN_KEY_CHECKS=1;
COMMIT;