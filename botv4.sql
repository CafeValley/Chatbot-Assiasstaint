-- Chatbot v4 Schema - Intent-Based Architecture
-- Run this after botv3.sql to upgrade to the teachable assistant structure

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Table: intents
-- Groups related questions under a single intent
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `intents` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `idx_intent_name` (`name`),
  KEY `idx_intent_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: training_phrases
-- Multiple ways to ask the same thing, linked to an intent
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `training_phrases` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `intent_id` INT NOT NULL,
  `phrase` TEXT NOT NULL,
  `phrase_normalized` VARCHAR(500) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`intent_id`) REFERENCES `intents`(`id`) ON DELETE CASCADE,
  KEY `idx_phrase_intent` (`intent_id`),
  KEY `idx_phrase_normalized` (`phrase_normalized`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table: intent_responses
-- Multiple responses per intent with confidence scores
-- (Named intent_responses to avoid conflict with existing responses table)
-- --------------------------------------------------------

CREATE TABLE IF NOT EXISTS `intent_responses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `intent_id` INT NOT NULL,
  `response` TEXT NOT NULL,
  `confidence` FLOAT NOT NULL DEFAULT 1.0,
  `is_active` TINYINT(1) NOT NULL DEFAULT 1,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (`intent_id`) REFERENCES `intents`(`id`) ON DELETE CASCADE,
  KEY `idx_response_intent` (`intent_id`),
  KEY `idx_response_active` (`is_active`),
  KEY `idx_response_confidence` (`confidence`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Update learning table to support intent assignment
-- Note: Run these ALTER statements manually if columns don't exist
-- --------------------------------------------------------

-- Check and add columns to learning table (MySQL 5.7 compatible)
-- These are wrapped in a procedure to handle "column already exists" gracefully

DELIMITER //
DROP PROCEDURE IF EXISTS add_learning_columns//
CREATE PROCEDURE add_learning_columns()
BEGIN
    DECLARE CONTINUE HANDLER FOR 1060 BEGIN END; -- Column already exists
    ALTER TABLE `learning` ADD COLUMN `assigned_intent_id` INT DEFAULT NULL;
    ALTER TABLE `learning` ADD COLUMN `status` ENUM('pending', 'assigned', 'rejected') DEFAULT 'pending';
    ALTER TABLE `learning` ADD COLUMN `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP;
END//
DELIMITER ;

CALL add_learning_columns();
DROP PROCEDURE IF EXISTS add_learning_columns;

-- --------------------------------------------------------
-- Update feedback table structure (if exists)
-- --------------------------------------------------------

-- feedback table is created dynamically in message.php, so we just ensure it exists
CREATE TABLE IF NOT EXISTS `feedback` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `message_id` VARCHAR(100) NOT NULL,
  `query` TEXT,
  `reply` TEXT,
  `intent_id` INT DEFAULT NULL,
  `feedback_type` ENUM('thumbs_up', 'thumbs_down') NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  KEY `idx_message_id` (`message_id`),
  KEY `idx_feedback_type` (`feedback_type`),
  KEY `idx_feedback_intent` (`intent_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

