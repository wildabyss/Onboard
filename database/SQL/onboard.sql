
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- activity
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `activity`;

CREATE TABLE `activity`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- activity_category_assoc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `activity_category_assoc`;

CREATE TABLE `activity_category_assoc`
(
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `activity_id` int(10) unsigned NOT NULL,
    `category_id` mediumint(8) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `activity_id` (`activity_id`),
    INDEX `category_id` (`category_id`),
    CONSTRAINT `activity_category_assoc_ibfk_1`
        FOREIGN KEY (`activity_id`)
        REFERENCES `activity` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `activity_category_assoc_ibfk_2`
        FOREIGN KEY (`category_id`)
        REFERENCES `category` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- activity_list
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `activity_list`;

CREATE TABLE `activity_list`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `user_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `user_id` (`user_id`),
    CONSTRAINT `activity_list_ibfk_1`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- activity_list_assoc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `activity_list_assoc`;

CREATE TABLE `activity_list_assoc`
(
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `activity_id` int(10) unsigned NOT NULL,
    `list_id` int(10) unsigned NOT NULL,
    `status` tinyint(3) unsigned NOT NULL,
    `date_added` DATETIME NOT NULL,
    `alias` VARCHAR(255),
    `description` VARCHAR(255),
    `is_owner` tinyint(3) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `activity_id` (`activity_id`),
    INDEX `list_id` (`list_id`),
    CONSTRAINT `activity_user_assoc_ibfk_1`
        FOREIGN KEY (`activity_id`)
        REFERENCES `activity` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `activity_user_assoc_ibfk_3`
        FOREIGN KEY (`list_id`)
        REFERENCES `activity_list` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- category
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `category`;

CREATE TABLE `category`
(
    `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- discussion
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `discussion`;

CREATE TABLE `discussion`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255),
    `activity_id` int(10) unsigned NOT NULL,
    `owner_id` int(10) unsigned NOT NULL,
    `status` tinyint(3) unsigned NOT NULL,
    `date_created` DATETIME NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `activity_id` (`activity_id`),
    INDEX `owner_id` (`owner_id`),
    CONSTRAINT `discussion_ibfk_1`
        FOREIGN KEY (`activity_id`)
        REFERENCES `activity` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `discussion_ibfk_2`
        FOREIGN KEY (`owner_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- discussion_user_assoc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `discussion_user_assoc`;

CREATE TABLE `discussion_user_assoc`
(
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `discussion_id` int(10) unsigned NOT NULL,
    `user_id` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `discussion_id` (`discussion_id`),
    INDEX `user_id` (`user_id`),
    CONSTRAINT `discussion_user_assoc_ibfk_1`
        FOREIGN KEY (`discussion_id`)
        REFERENCES `discussion` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `discussion_user_assoc_ibfk_2`
        FOREIGN KEY (`user_id`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user`;

CREATE TABLE `user`
(
    `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
    `fb_id` VARCHAR(22),
    `google_id` VARCHAR(22),
    `status` tinyint(3) unsigned NOT NULL,
    `date_joined` DATETIME NOT NULL,
    `display_name` VARCHAR(255),
    `email` VARCHAR(255),
    `phone` VARCHAR(25),
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- user_community_assoc
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `user_community_assoc`;

CREATE TABLE `user_community_assoc`
(
    `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
    `user_id_left` int(10) unsigned NOT NULL,
    `user_id_right` int(10) unsigned NOT NULL,
    PRIMARY KEY (`id`),
    INDEX `user_id_left` (`user_id_left`),
    INDEX `user_id_right` (`user_id_right`),
    CONSTRAINT `user_community_assoc_ibfk_1`
        FOREIGN KEY (`user_id_left`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `user_community_assoc_ibfk_2`
        FOREIGN KEY (`user_id_right`)
        REFERENCES `user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
