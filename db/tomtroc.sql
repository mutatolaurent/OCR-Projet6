-- Création de la base de données `blog_forteroche`
CREATE DATABASE IF NOT EXISTS tomtroc CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE tomtroc;

-- structure de la table book_state
DROP TABLE IF EXISTS `book_state`;
CREATE TABLE IF NOT EXISTS `book_state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `state` varchar(128) NOT NULL,
  `created_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- Structure de la table user
DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pseudo` varchar(255) NOT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `unique_pseudo` (`pseudo`)
) ENGINE=InnoDB;

-- Structure de la table book
DROP TABLE IF EXISTS `book`;
CREATE TABLE IF NOT EXISTS `book` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_user` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `id_state` int(11) DEFAULT 1,
  `photo` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_book_user` FOREIGN KEY (`id_user`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_book_state` FOREIGN KEY (`id_state`) REFERENCES `book_state` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB;
ALTER TABLE book ADD FULLTEXT idx_search (title, author);

-- structure de la table conversation 
DROP TABLE IF EXISTS `thread`;
CREATE TABLE IF NOT EXISTS `thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_one_id` int(11) NOT NULL,
  `user_two_id` int(11) NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  /* Cette ligne empêche les doublons pour un même duo */
  UNIQUE KEY `unique_conversation_duo` (`user_one_id`, `user_two_id`),
  CONSTRAINT `fk_thread_user_one` FOREIGN KEY (`user_one_id`) REFERENCES `user` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_thread_user_two` FOREIGN KEY (`user_two_id`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;

-- structure de la table message
DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_thread` int(11) NOT NULL,
  `id_sender` int(11) NOT NULL,
  `content` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_message_thread` FOREIGN KEY (`id_thread`) REFERENCES `thread` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_message_sender` FOREIGN KEY (`id_sender`) REFERENCES `user` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB;