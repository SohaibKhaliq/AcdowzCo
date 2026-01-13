-- MySQL dump 10.13  Distrib 8.4.4, for macos15 (arm64)
--
-- Host: 127.0.0.1    Database: farmart
-- ------------------------------------------------------
-- Server version	8.4.4

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `activations`
--

DROP TABLE IF EXISTS `activations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `activations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `code` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `completed` tinyint(1) NOT NULL DEFAULT '0',
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activations_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activations`
--

LOCK TABLES `activations` WRITE;
/*!40000 ALTER TABLE `activations` DISABLE KEYS */;
INSERT INTO `activations` VALUES (1,1,'IsNP35Ha1xsh735MLXP20cD41uZO8OWW',1,'2025-09-11 01:45:50','2025-09-11 01:45:50','2025-09-11 01:45:50');
/*!40000 ALTER TABLE `activations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `admin_notifications`
--

DROP TABLE IF EXISTS `admin_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `admin_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `action_label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `permission` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin_notifications`
--

LOCK TABLES `admin_notifications` WRITE;
/*!40000 ALTER TABLE `admin_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `admin_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads`
--

DROP TABLE IF EXISTS `ads`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired_at` datetime DEFAULT NULL,
  `location` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `clicked` bigint NOT NULL DEFAULT '0',
  `order` int DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `open_in_new_tab` tinyint(1) NOT NULL DEFAULT '1',
  `tablet_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ads_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `google_adsense_slot_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ads_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads`
--

LOCK TABLES `ads` WRITE;
/*!40000 ALTER TABLE `ads` DISABLE KEYS */;
INSERT INTO `ads` VALUES (1,'Top Slider Image 1','2030-09-11 00:00:00','not_set','VC2C8Q1UGCBG','promotion/1.jpg','/products',0,1,'published','2025-09-11 01:46:59','2025-09-11 01:46:59',1,NULL,NULL,NULL,NULL),(2,'Homepage middle 1','2030-09-11 00:00:00','not_set','IZ6WU8KUALYD','promotion/2.png','/products',0,2,'published','2025-09-11 01:46:59','2025-09-11 01:46:59',1,NULL,NULL,NULL,NULL),(3,'Homepage middle 2','2030-09-11 00:00:00','not_set','ILSFJVYFGCPZ','promotion/3.png','/products',0,3,'published','2025-09-11 01:46:59','2025-09-11 01:46:59',1,NULL,NULL,NULL,NULL),(4,'Homepage middle 3','2030-09-11 00:00:00','not_set','ZDOZUZZIU7FT','promotion/4.png','/products',0,4,'published','2025-09-11 01:46:59','2025-09-11 01:46:59',1,NULL,NULL,NULL,NULL),(5,'Products list 1','2030-09-11 00:00:00','not_set','ZDOZUZZIU7FZ','promotion/5.png','/products/beat-headphone',0,5,'published','2025-09-11 01:46:59','2025-09-11 01:46:59',1,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `ads` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ads_translations`
--

DROP TABLE IF EXISTS `ads_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ads_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ads_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tablet_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `mobile_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ads_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ads_translations`
--

LOCK TABLES `ads_translations` WRITE;
/*!40000 ALTER TABLE `ads_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ads_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `audit_histories`
--

DROP TABLE IF EXISTS `audit_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `audit_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `module` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` longtext COLLATE utf8mb4_unicode_ci,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `actor_id` bigint unsigned NOT NULL,
  `actor_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'Botble\\ACL\\Models\\User',
  `reference_id` bigint unsigned NOT NULL,
  `reference_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `audit_histories_user_id_index` (`user_id`),
  KEY `audit_histories_module_index` (`module`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `audit_histories`
--

LOCK TABLES `audit_histories` WRITE;
/*!40000 ALTER TABLE `audit_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `audit_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Botble\\ACL\\Models\\User',
  `icon` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int unsigned NOT NULL DEFAULT '0',
  `is_featured` tinyint NOT NULL DEFAULT '0',
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_index` (`parent_id`),
  KEY `categories_status_index` (`status`),
  KEY `categories_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Ecommerce',0,'Omnis quo laudantium aut adipisci assumenda rerum eum. Aperiam minima consequatur iste quibusdam est. Totam necessitatibus et libero earum nesciunt quos.','published',1,'Botble\\ACL\\Models\\User',NULL,0,0,1,'2025-09-11 01:46:56','2025-09-11 01:46:56'),(2,'Fashion',0,'Facilis nam natus officiis consequatur temporibus quas velit nam. Earum at illum non error. Earum et repellendus magnam illum soluta voluptatibus.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-09-11 01:46:56','2025-09-11 01:46:56'),(3,'Electronic',0,'Dolorum et rem error. Sint ipsum voluptas et ut sequi. Earum et nam autem ut earum ratione dignissimos et.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(4,'Commercial',0,'Veniam id iusto incidunt dolorum et eum. Aut voluptatem expedita ex accusantium in qui nemo voluptatem. Temporibus adipisci vel voluptas sit. Sed velit deleniti rerum harum consequatur quasi.','published',1,'Botble\\ACL\\Models\\User',NULL,0,1,0,'2025-09-11 01:46:57','2025-09-11 01:46:57');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories_translations`
--

DROP TABLE IF EXISTS `categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categories_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `categories_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories_translations`
--

LOCK TABLES `categories_translations` WRITE;
/*!40000 ALTER TABLE `categories_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities`
--

DROP TABLE IF EXISTS `cities`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state_id` bigint unsigned DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `record_id` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `cities_slug_unique` (`slug`),
  KEY `idx_cities_name` (`name`),
  KEY `idx_cities_state_status` (`state_id`,`status`),
  KEY `idx_cities_status` (`status`),
  KEY `idx_cities_state_id` (`state_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities`
--

LOCK TABLES `cities` WRITE;
/*!40000 ALTER TABLE `cities` DISABLE KEYS */;
/*!40000 ALTER TABLE `cities` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cities_translations`
--

DROP TABLE IF EXISTS `cities_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cities_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `cities_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`cities_id`),
  KEY `idx_cities_trans_city_lang` (`cities_id`,`lang_code`),
  KEY `idx_cities_trans_name` (`name`),
  KEY `idx_cities_trans_cities_id` (`cities_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cities_translations`
--

LOCK TABLES `cities_translations` WRITE;
/*!40000 ALTER TABLE `cities_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `cities_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options`
--

DROP TABLE IF EXISTS `contact_custom_field_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `custom_field_id` bigint unsigned NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` int NOT NULL DEFAULT '999',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options`
--

LOCK TABLES `contact_custom_field_options` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_field_options_translations`
--

DROP TABLE IF EXISTS `contact_custom_field_options_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_field_options_translations` (
  `contact_custom_field_options_id` bigint unsigned NOT NULL,
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `label` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_field_options_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_field_options_translations`
--

LOCK TABLES `contact_custom_field_options_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_field_options_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields`
--

DROP TABLE IF EXISTS `contact_custom_fields`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `placeholder` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int NOT NULL DEFAULT '999',
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields`
--

LOCK TABLES `contact_custom_fields` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_custom_fields_translations`
--

DROP TABLE IF EXISTS `contact_custom_fields_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_custom_fields_translations` (
  `contact_custom_fields_id` bigint unsigned NOT NULL,
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `placeholder` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`contact_custom_fields_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_custom_fields_translations`
--

LOCK TABLES `contact_custom_fields_translations` WRITE;
/*!40000 ALTER TABLE `contact_custom_fields_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_custom_fields_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contact_replies`
--

DROP TABLE IF EXISTS `contact_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contact_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `message` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `contact_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contact_replies`
--

LOCK TABLES `contact_replies` WRITE;
/*!40000 ALTER TABLE `contact_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `contact_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `subject` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `custom_fields` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'unread',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'Christiana Orn III','lulu.runte@example.com','641-757-2355','4438 Mills Ford\nMarioville, SD 39894-4738','Quos adipisci totam odit quod.','Qui aut ut aperiam dolor inventore eum. Esse laudantium molestiae quasi id sunt sed voluptas. Dignissimos doloribus modi voluptatibus iusto. Necessitatibus ut sed similique dolore incidunt quae quam. Quaerat iure ipsum velit. Unde non recusandae minus quae provident officiis suscipit. Ratione perferendis dignissimos modi. Qui nemo quia ea voluptatem perspiciatis qui. Aut enim deserunt voluptas eveniet illo velit.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55'),(2,'Luella Jacobi','laverna.sipes@example.com','+1.541.887.4799','11007 Upton Centers\nPourosside, KS 89586','Eligendi velit eius voluptatem aut in dolorem.','Eaque et et beatae ipsum nesciunt veniam. Voluptates tempore sint eos labore. Sed amet distinctio architecto. Ab sint ut nobis sunt. Reprehenderit mollitia debitis ut sint enim tempora quia dolorum. Voluptates et earum ipsum dolores ratione facilis eligendi. Quos et provident rerum optio adipisci labore. Impedit amet ipsam quae distinctio aut dolorem quam. Similique aut est temporibus. Sed voluptatibus ipsa assumenda quaerat.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55'),(3,'Vidal Bosco','wbode@example.net','1-254-260-3462','8937 Renner Flats\nWardville, NY 23731','Illum voluptatem deserunt soluta fugiat ut minus.','Perspiciatis ex laboriosam dolore aut harum dolore. Iure cum consectetur eaque. Atque at exercitationem nostrum voluptatibus quis. Enim accusantium occaecati sint ipsam repudiandae. Et quos qui expedita ducimus. Laborum sint error dolorem soluta. Voluptas qui eos numquam alias. Aut ut aspernatur occaecati voluptas et. Enim sint voluptatem dolore vitae. Qui placeat accusantium ea voluptatem suscipit officiis sint. Vel magni iusto hic dolores dicta sit. Deserunt voluptates totam iure ut.',NULL,'unread','2025-09-11 01:46:55','2025-09-11 01:46:55'),(4,'Kaleigh Konopelski','collins.ben@example.net','717.285.6709','960 Lebsack Isle\nEast Mollieton, IN 59770-2362','Quo culpa est quam corporis.','Asperiores est repudiandae consectetur nesciunt dolorum quis. Rem et ipsam quod soluta. Voluptas corrupti animi voluptates tempora. Dignissimos accusamus adipisci porro atque voluptatum vero dolorem. Nulla placeat ipsam inventore nihil ratione. Eveniet odit provident repellat quas. Eaque velit dolorum ex aperiam quis nisi. Aperiam at dolor qui laborum aut. Ipsum voluptas odit in totam qui aliquid. Omnis ullam eius maxime. Tempore voluptas sint et similique quidem similique.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55'),(5,'Gaetano Pacocha','reyna16@example.com','+1-442-427-4835','6019 Mayert Throughway\nJamelport, LA 58134','Ab voluptates earum libero odit.','Praesentium quod aut voluptas. Quia consequatur quia voluptate sed provident tenetur. Aut aut perferendis maiores sit pariatur saepe eveniet. Expedita aspernatur et quaerat quia sequi molestias et. Quaerat quia aspernatur perspiciatis magnam dolorum minima et. Enim error id magni vel soluta. Distinctio ut impedit ab ab dicta.',NULL,'unread','2025-09-11 01:46:55','2025-09-11 01:46:55'),(6,'Desiree Schiller','joesph.ullrich@example.net','+1.947.298.6815','6104 Ines Unions Suite 080\nNorth Mabelchester, MA 81073','Delectus vel tempore voluptas porro.','Consequatur praesentium voluptatibus minus nam aut optio laudantium. Suscipit perspiciatis suscipit similique. Ea voluptas molestias veritatis dicta facilis. Maxime in voluptate necessitatibus. Exercitationem aut magnam quod id dolorem officiis quae. Aliquam qui tempora vel possimus suscipit illo quia. In ullam placeat doloremque impedit adipisci ullam unde. Architecto iure voluptas illum dolorum accusamus ut.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55'),(7,'Alexis Okuneva','oveum@example.com','972.405.3557','31174 Dare Islands\nWalkershire, NH 77900','Et velit qui distinctio aut.','Quia ipsum non quia veritatis eaque. Consequuntur rerum suscipit eos debitis sunt iste deserunt porro. Molestias nihil debitis eos ea deserunt voluptatem. Perferendis non sint dolore sed perspiciatis doloremque tenetur. Labore qui vel est sed deleniti. Cumque autem voluptatem quae. Quo nam quis voluptate tempore occaecati sunt eos. Perferendis dolor placeat voluptatem quibusdam. Aperiam ut voluptatem amet officia voluptas vel saepe.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55'),(8,'Hilbert Gerhold','ikoelpin@example.com','915.581.2952','973 Laurie Gateway\nBaileymouth, DE 32935','Quibusdam nisi provident perspiciatis.','Dolore fuga fuga est quis possimus asperiores voluptatem. Voluptas similique praesentium nisi unde accusamus. Aliquid maiores voluptate est beatae consequuntur. Perferendis qui sed pariatur praesentium culpa. Modi maiores voluptatem fugit excepturi alias quas. Vero quia nisi esse nisi modi. Fuga tempore tempora quam qui perspiciatis est excepturi. Voluptas vel et ut quo repudiandae. Deserunt ipsum blanditiis similique eius porro.',NULL,'unread','2025-09-11 01:46:55','2025-09-11 01:46:55'),(9,'Aliya Pacocha','jaquelin23@example.com','1-240-574-1006','979 Theodora Summit Suite 695\nMayeberg, LA 30314-9962','At quaerat maxime temporibus aut error et et ab.','Aperiam qui nulla fugiat necessitatibus est. Laborum aliquid beatae dolorum expedita ipsam. Veniam tempore aut maiores quae similique fugit et. Consequatur voluptatem asperiores deserunt illo doloribus. Error alias labore explicabo et quisquam eius. Vel ullam rerum aut accusamus. Ad totam consequatur rerum illo. Voluptatum totam voluptatum et quas autem.',NULL,'unread','2025-09-11 01:46:55','2025-09-11 01:46:55'),(10,'Carolina Okuneva','yroberts@example.org','+1-209-233-6498','1196 Everette Parkway\nLake Declan, ND 67282','Corporis est quia dolor id dolor.','Voluptatum rerum aspernatur deleniti similique ad molestiae qui. Molestias quia perspiciatis temporibus molestiae vero non assumenda quia. Ipsam excepturi ut tempora sit omnis. Recusandae recusandae ab nemo distinctio modi. Quo voluptatum minus provident odit laborum asperiores numquam. Eius perspiciatis molestiae velit et tempora. Voluptate non ea quod fuga. Architecto et illum vero iure reiciendis porro dolorum. Odit quod quasi explicabo ipsum.',NULL,'read','2025-09-11 01:46:55','2025-09-11 01:46:55');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries`
--

DROP TABLE IF EXISTS `countries`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nationality` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_countries_name` (`name`),
  KEY `idx_countries_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries`
--

LOCK TABLES `countries` WRITE;
/*!40000 ALTER TABLE `countries` DISABLE KEYS */;
/*!40000 ALTER TABLE `countries` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `countries_translations`
--

DROP TABLE IF EXISTS `countries_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `countries_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `countries_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nationality` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`countries_id`),
  KEY `idx_countries_trans_country_lang` (`countries_id`,`lang_code`),
  KEY `idx_countries_trans_name` (`name`),
  KEY `idx_countries_trans_countries_id` (`countries_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `countries_translations`
--

LOCK TABLES `countries_translations` WRITE;
/*!40000 ALTER TABLE `countries_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `countries_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widget_settings`
--

DROP TABLE IF EXISTS `dashboard_widget_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widget_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `settings` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `widget_id` bigint unsigned NOT NULL,
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `status` tinyint unsigned NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `dashboard_widget_settings_user_id_index` (`user_id`),
  KEY `dashboard_widget_settings_widget_id_index` (`widget_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widget_settings`
--

LOCK TABLES `dashboard_widget_settings` WRITE;
/*!40000 ALTER TABLE `dashboard_widget_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widget_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dashboard_widgets`
--

DROP TABLE IF EXISTS `dashboard_widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dashboard_widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dashboard_widgets`
--

LOCK TABLES `dashboard_widgets` WRITE;
/*!40000 ALTER TABLE `dashboard_widgets` DISABLE KEYS */;
/*!40000 ALTER TABLE `dashboard_widgets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `device_tokens`
--

DROP TABLE IF EXISTS `device_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `device_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `platform` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `app_version` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `device_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `device_tokens_token_unique` (`token`),
  KEY `device_tokens_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `device_tokens_platform_is_active_index` (`platform`,`is_active`),
  KEY `device_tokens_is_active_index` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `device_tokens`
--

LOCK TABLES `device_tokens` WRITE;
/*!40000 ALTER TABLE `device_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `device_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_abandoned_carts`
--

DROP TABLE IF EXISTS `ec_abandoned_carts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_abandoned_carts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `session_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cart_data` json NOT NULL,
  `total_amount` decimal(15,2) NOT NULL DEFAULT '0.00',
  `items_count` int NOT NULL DEFAULT '0',
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abandoned_at` timestamp NULL DEFAULT NULL,
  `reminder_sent_at` timestamp NULL DEFAULT NULL,
  `reminders_sent` int NOT NULL DEFAULT '0',
  `is_recovered` tinyint(1) NOT NULL DEFAULT '0',
  `recovered_at` timestamp NULL DEFAULT NULL,
  `recovered_order_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_abandoned_carts_abandoned_at_is_recovered_index` (`abandoned_at`,`is_recovered`),
  KEY `ec_abandoned_carts_created_at_is_recovered_index` (`created_at`,`is_recovered`),
  KEY `ec_abandoned_carts_customer_id_index` (`customer_id`),
  KEY `ec_abandoned_carts_session_id_index` (`session_id`),
  KEY `ec_abandoned_carts_email_index` (`email`),
  KEY `ec_abandoned_carts_recovered_order_id_index` (`recovered_order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_abandoned_carts`
--

LOCK TABLES `ec_abandoned_carts` WRITE;
/*!40000 ALTER TABLE `ec_abandoned_carts` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_abandoned_carts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_brands`
--

DROP TABLE IF EXISTS `ec_brands`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_brands` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `website` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_brands`
--

LOCK TABLES `ec_brands` WRITE;
/*!40000 ALTER TABLE `ec_brands` DISABLE KEYS */;
INSERT INTO `ec_brands` VALUES (1,'FoodPound','New Snacks Release',NULL,'brands/1.png','published',0,1,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(2,'iTea JSC','Happy Tea 100% Organic. From $29.9',NULL,'brands/2.png','published',1,1,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(3,'Soda Brand','Fresh Meat Sausage. BUY 2 GET 1!',NULL,'brands/3.png','published',2,1,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(4,'Farmart','Fresh Meat Sausage. BUY 2 GET 1!',NULL,'brands/4.png','published',3,1,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(5,'Soda Brand','Fresh Meat Sausage. BUY 2 GET 1!',NULL,'brands/3.png','published',4,1,'2025-09-11 01:45:51','2025-09-11 01:45:51');
/*!40000 ALTER TABLE `ec_brands` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_brands_translations`
--

DROP TABLE IF EXISTS `ec_brands_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_brands_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_brands_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`ec_brands_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_brands_translations`
--

LOCK TABLES `ec_brands_translations` WRITE;
/*!40000 ALTER TABLE `ec_brands_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_brands_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_cart`
--

DROP TABLE IF EXISTS `ec_cart`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_cart` (
  `identifier` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instance` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`identifier`,`instance`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_cart`
--

LOCK TABLES `ec_cart` WRITE;
/*!40000 ALTER TABLE `ec_cart` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_cart` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_currencies`
--

DROP TABLE IF EXISTS `ec_currencies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_currencies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_prefix_symbol` tinyint unsigned NOT NULL DEFAULT '0',
  `decimals` tinyint unsigned DEFAULT '0',
  `order` int unsigned DEFAULT '0',
  `is_default` tinyint NOT NULL DEFAULT '0',
  `exchange_rate` double NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_currencies`
--

LOCK TABLES `ec_currencies` WRITE;
/*!40000 ALTER TABLE `ec_currencies` DISABLE KEYS */;
INSERT INTO `ec_currencies` VALUES (1,'USD','$',1,2,0,1,1,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(2,'EUR','€',0,2,1,0,0.84,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(3,'VND','₫',0,0,2,0,23203,'2025-09-11 01:45:51','2025-09-11 01:45:51'),(4,'NGN','₦',1,2,2,0,895.52,'2025-09-11 01:45:51','2025-09-11 01:45:51');
/*!40000 ALTER TABLE `ec_currencies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customer_addresses`
--

DROP TABLE IF EXISTS `ec_customer_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customer_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint unsigned NOT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customer_addresses`
--

LOCK TABLES `ec_customer_addresses` WRITE;
/*!40000 ALTER TABLE `ec_customer_addresses` DISABLE KEYS */;
INSERT INTO `ec_customer_addresses` VALUES (1,'Evie Veum','customer@botble.com','+18035169335','LU','Pennsylvania','O\'Haraburgh','674 Anjali Orchard',1,1,'2025-09-11 01:45:54','2025-09-11 01:45:54','62746'),(2,'Evie Veum','customer@botble.com','+13468887029','TO','Illinois','Goodwinhaven','56661 Alicia Wells',1,0,'2025-09-11 01:45:54','2025-09-11 01:45:54','31232'),(3,'Mr. Isaac Hayes','vendor@botble.com','+16519935304','KH','District of Columbia','Hansborough','93053 Ramon Bridge',2,1,'2025-09-11 01:45:55','2025-09-11 01:45:55','49518-9612'),(4,'Mr. Isaac Hayes','vendor@botble.com','+18727665900','SA','Arkansas','Lake Sonnyshire','8929 Vincenzo Wall',2,0,'2025-09-11 01:45:55','2025-09-11 01:45:55','83180-1053'),(5,'Archibald Schmeler MD','ghartmann@example.org','+15015851943','RS','Wyoming','Port Connieshire','7034 Kuvalis Fords',3,1,'2025-09-11 01:45:55','2025-09-11 01:45:55','08071-3720'),(6,'Kaya Morar','kiara.corkery@example.net','+13092560718','NG','Mississippi','Lake Novellaview','49217 Jast Garden',4,1,'2025-09-11 01:45:56','2025-09-11 01:45:56','93389-6276'),(7,'Kristoffer Terry Jr.','allie37@example.net','+14635192656','VE','New Hampshire','North Fern','7145 Will Mall',5,1,'2025-09-11 01:45:57','2025-09-11 01:45:57','06818-5493'),(8,'Samantha Collins','sbruen@example.net','+12483659980','MX','West Virginia','Henriborough','303 O\'Conner Wells Suite 223',6,1,'2025-09-11 01:45:57','2025-09-11 01:45:57','07888'),(9,'Columbus Barrows','mabelle.ferry@example.com','+18709002088','GU','North Dakota','South Julio','900 Idell Brooks Suite 633',7,1,'2025-09-11 01:45:58','2025-09-11 01:45:58','45002-1532'),(10,'Lois Kling','hwillms@example.com','+17209887149','PY','Hawaii','Faheytown','565 Irving Mill Suite 341',8,1,'2025-09-11 01:45:58','2025-09-11 01:45:58','00166'),(11,'Jasper Fahey','luettgen.salvatore@example.org','+16127493495','ID','California','Audreyberg','357 Jacobson Ramp',9,1,'2025-09-11 01:45:58','2025-09-11 01:45:58','18237-9145'),(12,'Bessie Flatley','marcia71@example.com','+18154510257','NA','Ohio','Domingobury','12449 Lynch Branch Suite 170',10,1,'2025-09-11 01:45:59','2025-09-11 01:45:59','46910');
/*!40000 ALTER TABLE `ec_customer_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customer_deletion_requests`
--

DROP TABLE IF EXISTS `ec_customer_deletion_requests`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customer_deletion_requests` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'waiting_for_confirmation',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `confirmed_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_customer_deletion_requests_token_unique` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customer_deletion_requests`
--

LOCK TABLES `ec_customer_deletion_requests` WRITE;
/*!40000 ALTER TABLE `ec_customer_deletion_requests` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_customer_deletion_requests` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customer_password_resets`
--

DROP TABLE IF EXISTS `ec_customer_password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customer_password_resets` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `ec_customer_password_resets_email_index` (`email`),
  KEY `ec_customer_password_resets_token_index` (`token`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customer_password_resets`
--

LOCK TABLES `ec_customer_password_resets` WRITE;
/*!40000 ALTER TABLE `ec_customer_password_resets` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_customer_password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customer_recently_viewed_products`
--

DROP TABLE IF EXISTS `ec_customer_recently_viewed_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customer_recently_viewed_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customer_recently_viewed_products`
--

LOCK TABLES `ec_customer_recently_viewed_products` WRITE;
/*!40000 ALTER TABLE `ec_customer_recently_viewed_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_customer_recently_viewed_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customer_used_coupons`
--

DROP TABLE IF EXISTS `ec_customer_used_coupons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customer_used_coupons` (
  `discount_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`discount_id`,`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customer_used_coupons`
--

LOCK TABLES `ec_customer_used_coupons` WRITE;
/*!40000 ALTER TABLE `ec_customer_used_coupons` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_customer_used_coupons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_customers`
--

DROP TABLE IF EXISTS `ec_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_customers` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `confirmed_at` datetime DEFAULT NULL,
  `email_verify_token` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'activated',
  `block_reason` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8mb4_unicode_ci,
  `is_vendor` tinyint(1) NOT NULL DEFAULT '0',
  `vendor_verified_at` datetime DEFAULT NULL,
  `stripe_account_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `stripe_account_active` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_customers`
--

LOCK TABLES `ec_customers` WRITE;
/*!40000 ALTER TABLE `ec_customers` DISABLE KEYS */;
INSERT INTO `ec_customers` VALUES (1,'Evie Veum','customer@botble.com','$2y$12$.dSQLgBiTPGSJlHftpWwgeuktAZrZsry6mgs013shskn/8zNuh/rm','customers/1.jpg','1979-08-27','+15083617536',NULL,'2025-09-11 01:45:54','2025-09-11 01:45:54','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,0,NULL,NULL,0),(2,'Mr. Isaac Hayes','vendor@botble.com','$2y$12$.9.Duu1cdE9fVVulO4TKluPg6MrqthfvrBIDr3g8fVN84sZTfXWIm','customers/8.jpg','1992-08-14','+18306074303',NULL,'2025-09-11 01:45:55','2025-09-11 01:46:37','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(3,'Archibald Schmeler MD','ghartmann@example.org','$2y$12$YsLcmZoUF3pg3Xb6GWlONed/gjwxn94q7QNnEORkkW5W8BsALO.Gm','customers/1.jpg','1987-08-23','+14174703751',NULL,'2025-09-11 01:45:55','2025-09-11 01:46:38','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(4,'Kaya Morar','kiara.corkery@example.net','$2y$12$CxhAh0bc4vzuAYqzOtZL8.6cvLEP52ULPDNKGK4PhdwbVnCP23qKO','customers/2.jpg','1985-09-01','+19018577931',NULL,'2025-09-11 01:45:56','2025-09-11 01:46:39','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(5,'Kristoffer Terry Jr.','allie37@example.net','$2y$12$jRZuXIo9jyCj1LBVKbLb3uDkgLcVRjq2xKjJCB1b/woBDddibu2rS','customers/3.jpg','1991-08-19','+15129611314',NULL,'2025-09-11 01:45:57','2025-09-11 01:46:39','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(6,'Samantha Collins','sbruen@example.net','$2y$12$lDs5jyNxzEKE3P/b4dBv7OAte92sYC6CtJJjzTRJFAlCVGYHLxUd2','customers/4.jpg','1979-08-27','+18704842106',NULL,'2025-09-11 01:45:57','2025-09-11 01:46:40','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(7,'Columbus Barrows','mabelle.ferry@example.com','$2y$12$qJtBE7MnlL7WmCPipJMHpOS1lpdQ5q/eXp.ZRNJUL/d98Vbhx4psi','customers/5.jpg','1988-08-27','+16368182828',NULL,'2025-09-11 01:45:58','2025-09-11 01:46:41','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(8,'Lois Kling','hwillms@example.com','$2y$12$Wpgydmh2OpQg0hT1D2UwE.x3CUzHTOuxNB6nGNqE4BUTM/OCdO4ny','customers/6.jpg','1975-08-16','+12835793294',NULL,'2025-09-11 01:45:58','2025-09-11 01:46:41','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,1,'2025-09-11 08:46:37',NULL,0),(9,'Jasper Fahey','luettgen.salvatore@example.org','$2y$12$oObh4QlUZQ6BbkkBvT/lzurhLNlpHNrfKb1O8epHtfQToNt/gd.VC','customers/7.jpg','1986-09-09','+15418374398',NULL,'2025-09-11 01:45:58','2025-09-11 01:46:42','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,0,NULL,NULL,0),(10,'Bessie Flatley','marcia71@example.com','$2y$12$aPwNBS7BfK.y0oSfWd55DerUa.lDEXzvG5KA8747dbEDsAIm1ZyvK','customers/8.jpg','1997-08-29','+14848187860',NULL,'2025-09-11 01:45:59','2025-09-11 01:46:42','2025-09-11 08:45:54',NULL,'activated',NULL,NULL,0,NULL,NULL,0);
/*!40000 ALTER TABLE `ec_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_customers`
--

DROP TABLE IF EXISTS `ec_discount_customers`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_customers` (
  `discount_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`discount_id`,`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_customers`
--

LOCK TABLES `ec_discount_customers` WRITE;
/*!40000 ALTER TABLE `ec_discount_customers` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_customers` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_product_categories`
--

DROP TABLE IF EXISTS `ec_discount_product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_product_categories` (
  `discount_id` bigint unsigned NOT NULL,
  `product_category_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`discount_id`,`product_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_product_categories`
--

LOCK TABLES `ec_discount_product_categories` WRITE;
/*!40000 ALTER TABLE `ec_discount_product_categories` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_product_collections`
--

DROP TABLE IF EXISTS `ec_discount_product_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_product_collections` (
  `discount_id` bigint unsigned NOT NULL,
  `product_collection_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`discount_id`,`product_collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_product_collections`
--

LOCK TABLES `ec_discount_product_collections` WRITE;
/*!40000 ALTER TABLE `ec_discount_product_collections` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_product_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discount_products`
--

DROP TABLE IF EXISTS `ec_discount_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discount_products` (
  `discount_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`discount_id`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discount_products`
--

LOCK TABLES `ec_discount_products` WRITE;
/*!40000 ALTER TABLE `ec_discount_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_discount_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_discounts`
--

DROP TABLE IF EXISTS `ec_discounts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_discounts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `start_date` datetime DEFAULT NULL,
  `end_date` datetime DEFAULT NULL,
  `quantity` int DEFAULT NULL,
  `total_used` int unsigned NOT NULL DEFAULT '0',
  `value` double DEFAULT NULL,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'coupon',
  `can_use_with_promotion` tinyint(1) NOT NULL DEFAULT '0',
  `can_use_with_flash_sale` tinyint(1) NOT NULL DEFAULT '0',
  `discount_on` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_quantity` int unsigned DEFAULT NULL,
  `type_option` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'amount',
  `target` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'all-orders',
  `min_order_price` decimal(15,2) DEFAULT NULL,
  `apply_via_url` tinyint(1) NOT NULL DEFAULT '0',
  `display_at_checkout` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `store_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_discounts_code_unique` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_discounts`
--

LOCK TABLES `ec_discounts` WRITE;
/*!40000 ALTER TABLE `ec_discounts` DISABLE KEYS */;
INSERT INTO `ec_discounts` VALUES (1,'Discount 1','220B4P8T9X2A','2025-09-10 08:46:33',NULL,NULL,0,100,'coupon',0,0,NULL,NULL,'percentage','all-orders',NULL,0,1,'2025-09-11 01:46:33','2025-09-11 01:46:33',NULL,NULL),(2,'Discount 2','9HHAND6AM0DM','2025-09-10 08:46:33',NULL,NULL,0,25,'coupon',0,0,NULL,NULL,'percentage','all-orders',NULL,0,1,'2025-09-11 01:46:33','2025-09-11 01:46:33',NULL,NULL),(3,'Discount 3','309EMHG6PHAF','2025-09-10 08:46:33','2025-09-25 08:46:33',NULL,0,781,'coupon',0,0,NULL,NULL,'amount','all-orders',NULL,0,1,'2025-09-11 01:46:33','2025-09-11 01:46:33',NULL,NULL),(4,'Discount 4','E34QFUNRZVU6','2025-09-10 08:46:33','2025-10-11 08:46:33',NULL,0,450,'coupon',0,0,NULL,NULL,'amount','all-orders',NULL,0,1,'2025-09-11 01:46:33','2025-09-11 01:46:33',NULL,NULL),(5,'Discount 5','KPAAAHVMGOG4','2025-09-10 08:46:33','2025-10-09 08:46:33',NULL,0,835,'coupon',0,0,NULL,NULL,'shipping','all-orders',NULL,0,1,'2025-09-11 01:46:33','2025-09-11 01:46:33',NULL,NULL),(6,'Discount 6','LHYGEVRL2WS6','2025-09-10 08:46:33',NULL,NULL,0,42,'coupon',0,0,NULL,NULL,'percentage','all-orders',NULL,0,1,'2025-09-11 01:46:34','2025-09-11 01:46:34',NULL,NULL),(7,'Discount 7','LPPJLX0QWAWL','2025-09-10 08:46:33','2025-09-21 08:46:33',NULL,0,725,'coupon',0,0,NULL,NULL,'shipping','all-orders',NULL,0,1,'2025-09-11 01:46:34','2025-09-11 01:46:34',NULL,NULL),(8,'Discount 8','JFMZBMSSW5C0','2025-09-10 08:46:33',NULL,NULL,0,270,'coupon',0,0,NULL,NULL,'amount','all-orders',NULL,0,1,'2025-09-11 01:46:34','2025-09-11 01:46:34',NULL,NULL),(9,'Discount 9','GBBLNFZKKE3C','2025-09-10 08:46:33','2025-09-20 08:46:33',NULL,0,530,'coupon',0,0,NULL,NULL,'amount','all-orders',NULL,0,1,'2025-09-11 01:46:34','2025-09-11 01:46:34',NULL,NULL),(10,'Discount 10','QYUZDAQMPDL3','2025-09-10 08:46:33','2025-10-06 08:46:33',NULL,0,534,'coupon',0,0,NULL,NULL,'same-price','all-orders',NULL,0,1,'2025-09-11 01:46:34','2025-09-11 01:46:34',NULL,NULL);
/*!40000 ALTER TABLE `ec_discounts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_flash_sale_products`
--

DROP TABLE IF EXISTS `ec_flash_sale_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_flash_sale_products` (
  `flash_sale_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `price` double unsigned DEFAULT NULL,
  `quantity` int unsigned DEFAULT NULL,
  `sold` int unsigned NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_flash_sale_products`
--

LOCK TABLES `ec_flash_sale_products` WRITE;
/*!40000 ALTER TABLE `ec_flash_sale_products` DISABLE KEYS */;
INSERT INTO `ec_flash_sale_products` VALUES (1,51,802.62,9,1),(1,47,7.8,16,2),(1,26,430.35,6,4),(1,59,720.72,9,1),(1,4,145.86,15,3),(1,22,249.9,9,1),(1,12,152.8488,11,1),(1,28,176.29,8,5),(1,35,722.06,12,5),(1,3,15.2,19,3);
/*!40000 ALTER TABLE `ec_flash_sale_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_flash_sales`
--

DROP TABLE IF EXISTS `ec_flash_sales`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_flash_sales` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `end_date` datetime NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_flash_sales`
--

LOCK TABLES `ec_flash_sales` WRITE;
/*!40000 ALTER TABLE `ec_flash_sales` DISABLE KEYS */;
INSERT INTO `ec_flash_sales` VALUES (1,'Winter Sale','2025-10-11 00:00:00','published','2025-09-11 01:46:33','2025-09-11 01:46:33');
/*!40000 ALTER TABLE `ec_flash_sales` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_flash_sales_translations`
--

DROP TABLE IF EXISTS `ec_flash_sales_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_flash_sales_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_flash_sales_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_flash_sales_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_flash_sales_translations`
--

LOCK TABLES `ec_flash_sales_translations` WRITE;
/*!40000 ALTER TABLE `ec_flash_sales_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_flash_sales_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_global_option_value`
--

DROP TABLE IF EXISTS `ec_global_option_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_global_option_value` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_id` bigint unsigned NOT NULL COMMENT 'option id',
  `option_value` tinytext COLLATE utf8mb4_unicode_ci COMMENT 'option value',
  `affect_price` double DEFAULT NULL COMMENT 'value of price of this option affect',
  `order` int NOT NULL DEFAULT '9999',
  `affect_type` tinyint NOT NULL DEFAULT '0' COMMENT '0. fixed 1. percent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_global_option_value`
--

LOCK TABLES `ec_global_option_value` WRITE;
/*!40000 ALTER TABLE `ec_global_option_value` DISABLE KEYS */;
INSERT INTO `ec_global_option_value` VALUES (1,1,'1 Year',0,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(2,1,'2 Year',10,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(3,1,'3 Year',20,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(4,2,'4GB',0,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(5,2,'8GB',10,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(6,2,'16GB',20,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(7,3,'Core i5',0,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(8,3,'Core i7',10,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(9,3,'Core i9',20,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(10,4,'128GB',0,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(11,4,'256GB',10,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(12,4,'512GB',20,9999,0,'2025-09-11 01:45:53','2025-09-11 01:45:53');
/*!40000 ALTER TABLE `ec_global_option_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_global_option_value_translations`
--

DROP TABLE IF EXISTS `ec_global_option_value_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_global_option_value_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_global_option_value_id` bigint unsigned NOT NULL,
  `option_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_global_option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_global_option_value_translations`
--

LOCK TABLES `ec_global_option_value_translations` WRITE;
/*!40000 ALTER TABLE `ec_global_option_value_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_global_option_value_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_global_options`
--

DROP TABLE IF EXISTS `ec_global_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_global_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of options',
  `option_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'option type',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Checked if this option is required',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_global_options`
--

LOCK TABLES `ec_global_options` WRITE;
/*!40000 ALTER TABLE `ec_global_options` DISABLE KEYS */;
INSERT INTO `ec_global_options` VALUES (1,'Warranty','Botble\\Ecommerce\\Option\\OptionType\\RadioButton',1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(2,'RAM','Botble\\Ecommerce\\Option\\OptionType\\RadioButton',1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(3,'CPU','Botble\\Ecommerce\\Option\\OptionType\\RadioButton',1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(4,'HDD','Botble\\Ecommerce\\Option\\OptionType\\Dropdown',0,'2025-09-11 01:45:53','2025-09-11 01:45:53');
/*!40000 ALTER TABLE `ec_global_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_global_options_translations`
--

DROP TABLE IF EXISTS `ec_global_options_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_global_options_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_global_options_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_global_options_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_global_options_translations`
--

LOCK TABLES `ec_global_options_translations` WRITE;
/*!40000 ALTER TABLE `ec_global_options_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_global_options_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_grouped_products`
--

DROP TABLE IF EXISTS `ec_grouped_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_grouped_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `parent_product_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `fixed_qty` int NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_grouped_products`
--

LOCK TABLES `ec_grouped_products` WRITE;
/*!40000 ALTER TABLE `ec_grouped_products` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_grouped_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_invoice_items`
--

DROP TABLE IF EXISTS `ec_invoice_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_invoice_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `invoice_id` bigint unsigned NOT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int unsigned NOT NULL,
  `price` decimal(15,2) NOT NULL DEFAULT '0.00',
  `sub_total` decimal(15,2) unsigned NOT NULL,
  `tax_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `discount_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `amount` decimal(15,2) unsigned NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_invoice_items_reference_type_reference_id_index` (`reference_type`,`reference_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_invoice_items`
--

LOCK TABLES `ec_invoice_items` WRITE;
/*!40000 ALTER TABLE `ec_invoice_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_invoice_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_invoices`
--

DROP TABLE IF EXISTS `ec_invoices`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_invoices` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company_logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_phone` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_country` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_state` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_city` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_zip_code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_address_line` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_tax_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sub_total` decimal(15,2) unsigned NOT NULL,
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `shipping_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `payment_fee` decimal(15,2) DEFAULT '0.00',
  `discount_amount` decimal(15,2) unsigned NOT NULL DEFAULT '0.00',
  `shipping_option` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `coupon_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) unsigned NOT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `payment_id` bigint unsigned DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_invoices_code_unique` (`code`),
  KEY `ec_invoices_reference_type_reference_id_index` (`reference_type`,`reference_id`),
  KEY `ec_invoices_payment_id_index` (`payment_id`),
  KEY `ec_invoices_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_invoices`
--

LOCK TABLES `ec_invoices` WRITE;
/*!40000 ALTER TABLE `ec_invoices` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_invoices` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_option_value`
--

DROP TABLE IF EXISTS `ec_option_value`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_option_value` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `option_id` bigint unsigned NOT NULL COMMENT 'option id',
  `option_value` tinytext COLLATE utf8mb4_unicode_ci COMMENT 'option value',
  `affect_price` double DEFAULT NULL COMMENT 'value of price of this option affect',
  `order` int NOT NULL DEFAULT '9999',
  `affect_type` tinyint NOT NULL DEFAULT '0' COMMENT '0. fixed 1. percent',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_option_value`
--

LOCK TABLES `ec_option_value` WRITE;
/*!40000 ALTER TABLE `ec_option_value` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_option_value` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_option_value_translations`
--

DROP TABLE IF EXISTS `ec_option_value_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_option_value_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_option_value_id` bigint unsigned NOT NULL,
  `option_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_option_value_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_option_value_translations`
--

LOCK TABLES `ec_option_value_translations` WRITE;
/*!40000 ALTER TABLE `ec_option_value_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_option_value_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_options`
--

DROP TABLE IF EXISTS `ec_options`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_options` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Name of options',
  `option_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'option type',
  `product_id` bigint unsigned NOT NULL DEFAULT '0',
  `order` int NOT NULL DEFAULT '9999',
  `required` tinyint(1) NOT NULL DEFAULT '0' COMMENT 'Checked if this option is required',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_options`
--

LOCK TABLES `ec_options` WRITE;
/*!40000 ALTER TABLE `ec_options` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_options` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_options_translations`
--

DROP TABLE IF EXISTS `ec_options_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_options_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_options_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_options_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_options_translations`
--

LOCK TABLES `ec_options_translations` WRITE;
/*!40000 ALTER TABLE `ec_options_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_options_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_addresses`
--

DROP TABLE IF EXISTS `ec_order_addresses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_addresses` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint unsigned NOT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'shipping_address',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_addresses`
--

LOCK TABLES `ec_order_addresses` WRITE;
/*!40000 ALTER TABLE `ec_order_addresses` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_addresses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_histories`
--

DROP TABLE IF EXISTS `ec_order_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned NOT NULL,
  `extras` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_histories`
--

LOCK TABLES `ec_order_histories` WRITE;
/*!40000 ALTER TABLE `ec_order_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_product`
--

DROP TABLE IF EXISTS `ec_order_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_product` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `qty` int NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `options` text COLLATE utf8mb4_unicode_ci,
  `product_options` text COLLATE utf8mb4_unicode_ci COMMENT 'product option data',
  `product_id` bigint unsigned DEFAULT NULL,
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `weight` double DEFAULT '0',
  `restock_quantity` int unsigned DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `product_type` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'physical',
  `times_downloaded` int NOT NULL DEFAULT '0',
  `license_code` text COLLATE utf8mb4_unicode_ci,
  `downloaded_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_product`
--

LOCK TABLES `ec_order_product` WRITE;
/*!40000 ALTER TABLE `ec_order_product` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_referrals`
--

DROP TABLE IF EXISTS `ec_order_referrals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_referrals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `ip` varchar(39) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landing_domain` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landing_page` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `landing_params` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referral` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `gclid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fclid` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_source` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_campaign` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_medium` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_term` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `utm_content` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referrer_url` text COLLATE utf8mb4_unicode_ci,
  `referrer_domain` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_order_referrals_order_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_referrals`
--

LOCK TABLES `ec_order_referrals` WRITE;
/*!40000 ALTER TABLE `ec_order_referrals` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_referrals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_return_histories`
--

DROP TABLE IF EXISTS `ec_order_return_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_return_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned DEFAULT NULL,
  `order_return_id` bigint unsigned NOT NULL,
  `action` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_return_histories`
--

LOCK TABLES `ec_order_return_histories` WRITE;
/*!40000 ALTER TABLE `ec_order_return_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_return_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_return_items`
--

DROP TABLE IF EXISTS `ec_order_return_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_return_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_return_id` bigint unsigned NOT NULL COMMENT 'Order return id',
  `order_product_id` bigint unsigned NOT NULL COMMENT 'Order product id',
  `product_id` bigint unsigned NOT NULL COMMENT 'Product id',
  `product_name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `qty` int NOT NULL COMMENT 'Quantity return',
  `price` decimal(15,2) NOT NULL COMMENT 'Price Product',
  `reason` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `refund_amount` decimal(12,2) DEFAULT '0.00',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_return_items`
--

LOCK TABLES `ec_order_return_items` WRITE;
/*!40000 ALTER TABLE `ec_order_return_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_return_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_returns`
--

DROP TABLE IF EXISTS `ec_order_returns`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_returns` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order_id` bigint unsigned NOT NULL COMMENT 'Order ID',
  `store_id` bigint unsigned DEFAULT NULL COMMENT 'Store ID',
  `user_id` bigint unsigned NOT NULL COMMENT 'Customer ID',
  `reason` text COLLATE utf8mb4_unicode_ci COMMENT 'Reason return order',
  `order_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Order current status',
  `return_status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Return status',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_order_returns_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_returns`
--

LOCK TABLES `ec_order_returns` WRITE;
/*!40000 ALTER TABLE `ec_order_returns` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_returns` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_order_tax_information`
--

DROP TABLE IF EXISTS `ec_order_tax_information`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_order_tax_information` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `company_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_tax_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `company_email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_order_tax_information_order_id_index` (`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_order_tax_information`
--

LOCK TABLES `ec_order_tax_information` WRITE;
/*!40000 ALTER TABLE `ec_order_tax_information` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_order_tax_information` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_orders`
--

DROP TABLE IF EXISTS `ec_orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_orders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `shipping_option` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_method` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'default',
  `status` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `amount` decimal(15,2) NOT NULL,
  `tax_amount` decimal(15,2) DEFAULT '0.00',
  `shipping_amount` decimal(15,2) DEFAULT NULL,
  `payment_fee` decimal(15,2) DEFAULT '0.00',
  `description` text COLLATE utf8mb4_unicode_ci,
  `coupon_code` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `discount_amount` decimal(15,2) DEFAULT NULL,
  `sub_total` decimal(15,2) NOT NULL,
  `is_confirmed` tinyint(1) NOT NULL DEFAULT '0',
  `discount_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_finished` tinyint(1) DEFAULT '0',
  `cancellation_reason` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cancellation_reason_description` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `token` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `proof_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `private_notes` text COLLATE utf8mb4_unicode_ci,
  `store_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_orders_code_unique` (`code`),
  KEY `ec_orders_user_id_status_created_at_index` (`user_id`,`status`,`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_orders`
--

LOCK TABLES `ec_orders` WRITE;
/*!40000 ALTER TABLE `ec_orders` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_orders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_attribute_sets`
--

DROP TABLE IF EXISTS `ec_product_attribute_sets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_attribute_sets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `display_layout` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'dropdown',
  `is_searchable` tinyint unsigned NOT NULL DEFAULT '1',
  `is_comparable` tinyint unsigned NOT NULL DEFAULT '1',
  `is_use_in_product_listing` tinyint unsigned NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `use_image_from_product_variation` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `idx_order_id` (`order`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_attribute_sets`
--

LOCK TABLES `ec_product_attribute_sets` WRITE;
/*!40000 ALTER TABLE `ec_product_attribute_sets` DISABLE KEYS */;
INSERT INTO `ec_product_attribute_sets` VALUES (1,'Color','color','visual',1,1,1,'published',0,'2025-09-11 01:45:53','2025-09-11 01:45:53',0),(2,'Size','size','text',1,1,1,'published',1,'2025-09-11 01:45:53','2025-09-11 01:45:53',0),(3,'Weight','weight','text',1,1,1,'published',0,'2025-09-11 01:45:53','2025-09-11 01:45:53',0),(4,'Boxes','boxes','text',1,1,1,'published',1,'2025-09-11 01:45:53','2025-09-11 01:45:53',0);
/*!40000 ALTER TABLE `ec_product_attribute_sets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_attribute_sets_translations`
--

DROP TABLE IF EXISTS `ec_product_attribute_sets_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_attribute_sets_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_attribute_sets_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_product_attribute_sets_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_attribute_sets_translations`
--

LOCK TABLES `ec_product_attribute_sets_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_attribute_sets_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_attribute_sets_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_attributes`
--

DROP TABLE IF EXISTS `ec_product_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_attributes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `attribute_set_id` bigint unsigned NOT NULL,
  `title` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `attribute_set_status_index` (`attribute_set_id`),
  KEY `idx_attribute_set_id` (`attribute_set_id`),
  KEY `idx_attribute_set_order_id` (`attribute_set_id`,`order`,`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_attributes`
--

LOCK TABLES `ec_product_attributes` WRITE;
/*!40000 ALTER TABLE `ec_product_attributes` DISABLE KEYS */;
INSERT INTO `ec_product_attributes` VALUES (1,1,'Green','green','#5FB7D4',NULL,1,1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(2,1,'Blue','blue','#333333',NULL,0,2,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(3,1,'Red','red','#DA323F',NULL,0,3,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(4,1,'Black','black','#2F366C',NULL,0,4,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(5,1,'Brown','brown','#87554B',NULL,0,5,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(6,2,'S','s',NULL,NULL,1,1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(7,2,'M','m',NULL,NULL,0,2,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(8,2,'L','l',NULL,NULL,0,3,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(9,2,'XL','xl',NULL,NULL,0,4,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(10,2,'XXL','xxl',NULL,NULL,0,5,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(11,3,'1KG','1kg',NULL,NULL,1,1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(12,3,'2KG','2kg',NULL,NULL,0,2,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(13,3,'3KG','3kg',NULL,NULL,0,3,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(14,3,'4KG','4kg',NULL,NULL,0,4,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(15,3,'5KG','5kg',NULL,NULL,0,5,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(16,4,'1 Box','1-box',NULL,NULL,1,1,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(17,4,'2 Boxes','2-boxes',NULL,NULL,0,2,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(18,4,'3 Boxes','3-boxes',NULL,NULL,0,3,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(19,4,'4 Boxes','4-boxes',NULL,NULL,0,4,'2025-09-11 01:45:53','2025-09-11 01:45:53'),(20,4,'5 Boxes','5-boxes',NULL,NULL,0,5,'2025-09-11 01:45:53','2025-09-11 01:45:53');
/*!40000 ALTER TABLE `ec_product_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_attributes_translations`
--

DROP TABLE IF EXISTS `ec_product_attributes_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_attributes_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_attributes_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_product_attributes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_attributes_translations`
--

LOCK TABLES `ec_product_attributes_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_attributes_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_attributes_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_categories`
--

DROP TABLE IF EXISTS `ec_product_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `order` int unsigned NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `icon` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_product_categories_parent_id_status_created_at_index` (`parent_id`,`status`,`created_at`),
  KEY `ec_product_categories_parent_id_status_index` (`parent_id`,`status`),
  KEY `idx_categories_status_order` (`status`,`order`),
  KEY `idx_categories_order` (`order`),
  KEY `ec_product_categories_slug_index` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_categories`
--

LOCK TABLES `ec_product_categories` WRITE;
/*!40000 ALTER TABLE `ec_product_categories` DISABLE KEYS */;
INSERT INTO `ec_product_categories` VALUES (1,'Fruits & Vegetables','fruits-vegetables',0,NULL,'published',0,'product-categories/1.png',1,'2025-09-11 01:45:51','2025-09-11 01:45:51','icon-star',NULL),(2,'Fruits','fruits',1,NULL,'published',0,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(3,'Apples','apples',2,NULL,'published',0,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(4,'Bananas','bananas',2,NULL,'published',1,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(5,'Berries','berries',2,NULL,'published',2,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(6,'Oranges & Easy Peelers','oranges-easy-peelers',2,NULL,'published',3,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(7,'Grapes','grapes',2,NULL,'published',4,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(8,'Lemons & Limes','lemons-limes',2,NULL,'published',5,NULL,0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,NULL),(9,'Peaches & Nectarines','peaches-nectarines',2,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(10,'Pears','pears',2,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(11,'Melon','melon',2,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(12,'Avocados','avocados',2,NULL,'published',9,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(13,'Plums & Apricots','plums-apricots',2,NULL,'published',10,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(14,'Vegetables','vegetables',1,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(15,'Potatoes','potatoes',14,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(16,'Carrots & Root Vegetables','carrots-root-vegetables',14,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(17,'Broccoli & Cauliflower','broccoli-cauliflower',14,NULL,'published',2,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(18,'Cabbage, Spinach & Greens','cabbage-spinach-greens',14,NULL,'published',3,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(19,'Onions, Leeks & Garlic','onions-leeks-garlic',14,NULL,'published',4,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(20,'Mushrooms','mushrooms',14,NULL,'published',5,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(21,'Tomatoes','tomatoes',14,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(22,'Beans, Peas & Sweetcorn','beans-peas-sweetcorn',14,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(23,'Freshly Drink Orange Juice','freshly-drink-orange-juice',14,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(24,'Breads Sweets','breads-sweets',0,NULL,'published',1,'product-categories/2.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-bread',NULL),(25,'Crisps, Snacks & Nuts','crisps-snacks-nuts',24,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(26,'Crisps & Popcorn','crisps-popcorn',25,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(27,'Nuts & Seeds','nuts-seeds',25,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(28,'Lighter Options','lighter-options',25,NULL,'published',2,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(29,'Cereal Bars','cereal-bars',25,NULL,'published',3,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(30,'Breadsticks & Pretzels','breadsticks-pretzels',25,NULL,'published',4,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(31,'Fruit Snacking','fruit-snacking',25,NULL,'published',5,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(32,'Rice & Corn Cakes','rice-corn-cakes',25,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(33,'Protein & Energy Snacks','protein-energy-snacks',25,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(34,'Toddler Snacks','toddler-snacks',25,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(35,'Meat Snacks','meat-snacks',25,NULL,'published',9,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(36,'Beans','beans',25,NULL,'published',10,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(37,'Lentils','lentils',25,NULL,'published',11,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(38,'Chickpeas','chickpeas',25,NULL,'published',12,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(39,'Tins & Cans','tins-cans',24,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(40,'Tomatoes','tomatoes',39,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(41,'Baked Beans, Spaghetti','baked-beans-spaghetti',39,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(42,'Fish','fish',39,NULL,'published',2,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(43,'Beans & Pulses','beans-pulses',39,NULL,'published',3,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(44,'Fruit','fruit',39,NULL,'published',4,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(45,'Coconut Milk & Cream','coconut-milk-cream',39,NULL,'published',5,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(46,'Lighter Options','lighter-options',39,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(47,'Olives','olives',39,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(48,'Sweetcorn','sweetcorn',39,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(49,'Carrots','carrots',39,NULL,'published',9,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(50,'Peas','peas',39,NULL,'published',10,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(51,'Mixed Vegetables','mixed-vegetables',39,NULL,'published',11,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(52,'Frozen Seafoods','frozen-seafoods',0,NULL,'published',2,'product-categories/3.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-hamburger',NULL),(53,'Raw Meats','raw-meats',0,NULL,'published',3,'product-categories/4.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-steak',NULL),(54,'Wines & Alcohol Drinks','wines-alcohol-drinks',0,NULL,'published',4,'product-categories/5.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-glass',NULL),(55,'Ready Meals','ready-meals',54,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(56,'Meals for 1','meals-for-1',55,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(57,'Meals for 2','meals-for-2',55,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(58,'Indian','indian',55,NULL,'published',2,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(59,'Italian','italian',55,NULL,'published',3,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(60,'Chinese','chinese',55,NULL,'published',4,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(61,'Traditional British','traditional-british',55,NULL,'published',5,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(62,'Thai & Oriental','thai-oriental',55,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(63,'Mediterranean & Moroccan','mediterranean-moroccan',55,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(64,'Mexican & Caribbean','mexican-caribbean',55,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(65,'Lighter Meals','lighter-meals',55,NULL,'published',9,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(66,'Lunch & Veg Pots','lunch-veg-pots',55,NULL,'published',10,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(67,'Salad & Herbs','salad-herbs',54,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(68,'Salad Bags','salad-bags',67,NULL,'published',0,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(69,'Cucumber','cucumber',67,NULL,'published',1,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(70,'Tomatoes','tomatoes',67,NULL,'published',2,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(71,'Lettuce','lettuce',67,NULL,'published',3,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(72,'Lunch Salad Bowls','lunch-salad-bowls',67,NULL,'published',4,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(73,'Lunch Salad Bowls','lunch-salad-bowls',67,NULL,'published',5,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(74,'Fresh Herbs','fresh-herbs',67,NULL,'published',6,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(75,'Avocados','avocados',67,NULL,'published',7,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(76,'Peppers','peppers',67,NULL,'published',8,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(77,'Coleslaw & Potato Salad','coleslaw-potato-salad',67,NULL,'published',9,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(78,'Spring Onions','spring-onions',67,NULL,'published',10,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(79,'Chilli, Ginger & Garlic','chilli-ginger-garlic',67,NULL,'published',11,NULL,0,'2025-09-11 01:45:52','2025-09-11 01:45:52',NULL,NULL),(80,'Tea & Coffee','tea-coffee',0,NULL,'published',5,'product-categories/6.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-teacup',NULL),(81,'Milks and Dairies','milks-and-dairies',0,NULL,'published',6,'product-categories/7.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-coffee-cup',NULL),(82,'Pet Foods','pet-foods',0,NULL,'published',7,'product-categories/8.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:52','icon-hotdog',NULL),(83,'Food Cupboard','food-cupboard',0,NULL,'published',8,'product-categories/1.png',1,'2025-09-11 01:45:52','2025-09-11 01:45:53','icon-cheese',NULL);
/*!40000 ALTER TABLE `ec_product_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_categories_translations`
--

DROP TABLE IF EXISTS `ec_product_categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_categories_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_categories_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`ec_product_categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_categories_translations`
--

LOCK TABLES `ec_product_categories_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_categories_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_categorizables`
--

DROP TABLE IF EXISTS `ec_product_categorizables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_categorizables` (
  `category_id` bigint unsigned NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`,`reference_id`,`reference_type`),
  KEY `ec_product_categorizables_category_id_index` (`category_id`),
  KEY `ec_product_categorizables_reference_id_index` (`reference_id`),
  KEY `ec_product_categorizables_reference_type_index` (`reference_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_categorizables`
--

LOCK TABLES `ec_product_categorizables` WRITE;
/*!40000 ALTER TABLE `ec_product_categorizables` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_categorizables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_category_product`
--

DROP TABLE IF EXISTS `ec_product_category_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_category_product` (
  `category_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`category_id`),
  KEY `ec_product_category_product_category_id_index` (`category_id`),
  KEY `ec_product_category_product_product_id_index` (`product_id`),
  KEY `idx_product_category` (`product_id`,`category_id`),
  KEY `idx_product_category_composite` (`product_id`,`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_category_product`
--

LOCK TABLES `ec_product_category_product` WRITE;
/*!40000 ALTER TABLE `ec_product_category_product` DISABLE KEYS */;
INSERT INTO `ec_product_category_product` VALUES (1,38),(1,54),(1,60),(2,20),(2,36),(2,37),(2,51),(2,61),(3,15),(3,22),(3,28),(3,48),(3,56),(4,2),(4,22),(4,36),(5,18),(5,42),(5,62),(6,23),(6,28),(6,30),(6,31),(6,42),(6,45),(6,47),(7,13),(7,43),(8,29),(8,45),(9,15),(9,16),(9,59),(10,27),(10,39),(11,13),(11,21),(11,48),(11,63),(12,17),(12,54),(13,3),(13,12),(13,27),(13,63),(14,9),(14,10),(14,15),(14,19),(15,57),(16,19),(16,42),(16,51),(16,64),(17,25),(17,49),(17,57),(18,6),(18,7),(18,34),(18,39),(18,41),(19,2),(19,23),(19,52),(20,21),(20,33),(20,44),(21,9),(21,20),(21,31),(22,16),(23,9),(23,14),(23,28),(23,32),(23,37),(23,44),(23,45),(23,50),(24,17),(24,22),(24,29),(24,41),(24,51),(25,1),(25,10),(25,15),(25,43),(25,63),(26,21),(26,30),(26,37),(26,58),(26,61),(27,24),(27,43),(28,8),(28,37),(28,61),(29,36),(30,3),(30,6),(30,12),(30,13),(30,33),(31,14),(31,57),(32,38),(32,61),(33,39),(33,59),(34,46),(36,4),(36,22),(36,23),(36,46),(37,4),(37,45),(38,11),(38,47),(39,52),(40,17),(40,24),(40,26),(40,46),(40,49),(40,50),(41,24),(41,51),(41,55),(42,26),(42,35),(42,44),(42,55),(43,7),(43,26),(43,36),(44,2),(44,12),(44,47),(44,55),(44,56),(45,38),(45,54),(46,11),(46,14),(46,32),(46,48),(48,52),(48,58),(49,43),(50,34),(50,42),(51,25),(52,3),(52,27),(52,49),(53,2),(53,57),(54,5),(54,12),(54,18),(54,40),(54,56),(55,53),(55,65),(56,3),(56,8),(56,18),(56,29),(56,58),(57,1),(57,29),(57,34),(57,41),(57,60),(58,30),(58,31),(59,32),(59,38),(59,54),(59,64),(60,11),(60,32),(61,1),(61,10),(61,14),(61,26),(62,27),(62,34),(62,41),(62,53),(62,65),(63,48),(63,65),(64,44),(65,30),(65,33),(65,50),(66,5),(66,11),(66,19),(66,25),(66,35),(66,63),(67,46),(67,53),(67,62),(67,64),(68,1),(68,9),(68,23),(68,39),(69,5),(69,58),(69,62),(70,55),(71,7),(71,16),(71,20),(71,40),(71,56),(71,60),(72,5),(72,17),(72,25),(72,40),(73,8),(73,19),(73,35),(73,53),(74,4),(74,31),(74,33),(74,60),(75,28),(75,35),(75,62),(76,6),(77,52),(77,59),(78,6),(78,49),(78,50),(79,7),(79,16),(79,18),(79,64),(79,65),(80,13),(80,59),(81,20),(81,40),(82,8),(83,4),(83,10),(83,21),(83,24),(83,47);
/*!40000 ALTER TABLE `ec_product_category_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_collection_products`
--

DROP TABLE IF EXISTS `ec_product_collection_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_collection_products` (
  `product_collection_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`product_collection_id`),
  KEY `ec_product_collection_products_product_collection_id_index` (`product_collection_id`),
  KEY `ec_product_collection_products_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_collection_products`
--

LOCK TABLES `ec_product_collection_products` WRITE;
/*!40000 ALTER TABLE `ec_product_collection_products` DISABLE KEYS */;
INSERT INTO `ec_product_collection_products` VALUES (1,1),(1,5),(1,7),(1,8),(1,11),(1,12),(1,16),(1,17),(1,19),(1,23),(1,24),(1,26),(1,27),(1,29),(1,31),(1,34),(1,37),(1,41),(1,52),(1,54),(1,62),(1,64),(2,6),(2,9),(2,10),(2,15),(2,18),(2,20),(2,25),(2,28),(2,30),(2,36),(2,38),(2,39),(2,40),(2,43),(2,45),(2,47),(2,49),(2,50),(2,51),(2,53),(2,56),(2,57),(2,58),(2,61),(3,2),(3,3),(3,4),(3,13),(3,14),(3,21),(3,22),(3,32),(3,33),(3,35),(3,42),(3,44),(3,46),(3,48),(3,55),(3,59),(3,60),(3,63),(3,65);
/*!40000 ALTER TABLE `ec_product_collection_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_collections`
--

DROP TABLE IF EXISTS `ec_product_collections`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_collections` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_collections`
--

LOCK TABLES `ec_product_collections` WRITE;
/*!40000 ALTER TABLE `ec_product_collections` DISABLE KEYS */;
INSERT INTO `ec_product_collections` VALUES (1,'New Arrival','new-arrival',NULL,NULL,'published','2025-09-11 01:45:53','2025-09-11 01:45:53',0),(2,'Best Sellers','best-sellers',NULL,NULL,'published','2025-09-11 01:45:53','2025-09-11 01:45:53',0),(3,'Special Offer','special-offer',NULL,NULL,'published','2025-09-11 01:45:53','2025-09-11 01:45:53',0);
/*!40000 ALTER TABLE `ec_product_collections` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_collections_translations`
--

DROP TABLE IF EXISTS `ec_product_collections_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_collections_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_collections_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_product_collections_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_collections_translations`
--

LOCK TABLES `ec_product_collections_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_collections_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_collections_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_cross_sale_relations`
--

DROP TABLE IF EXISTS `ec_product_cross_sale_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_cross_sale_relations` (
  `from_product_id` bigint unsigned NOT NULL,
  `to_product_id` bigint unsigned NOT NULL,
  `is_variant` tinyint(1) NOT NULL DEFAULT '0',
  `price` decimal(15,2) DEFAULT '0.00',
  `price_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'fixed',
  `apply_to_all_variations` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`from_product_id`,`to_product_id`),
  KEY `ec_product_cross_sale_relations_from_product_id_index` (`from_product_id`),
  KEY `ec_product_cross_sale_relations_to_product_id_index` (`to_product_id`),
  KEY `idx_product_cross_sale` (`from_product_id`,`to_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_cross_sale_relations`
--

LOCK TABLES `ec_product_cross_sale_relations` WRITE;
/*!40000 ALTER TABLE `ec_product_cross_sale_relations` DISABLE KEYS */;
INSERT INTO `ec_product_cross_sale_relations` VALUES (1,23,0,0.00,'fixed',1),(1,35,0,0.00,'fixed',1),(1,37,0,0.00,'fixed',1),(1,38,0,0.00,'fixed',1),(1,54,0,0.00,'fixed',1),(1,64,0,0.00,'fixed',1),(2,1,0,0.00,'fixed',1),(2,5,0,0.00,'fixed',1),(2,25,0,0.00,'fixed',1),(2,28,0,0.00,'fixed',1),(2,32,0,0.00,'fixed',1),(2,38,0,0.00,'fixed',1),(3,9,0,0.00,'fixed',1),(3,21,0,0.00,'fixed',1),(3,23,0,0.00,'fixed',1),(3,25,0,0.00,'fixed',1),(3,34,0,0.00,'fixed',1),(3,55,0,0.00,'fixed',1),(4,10,0,0.00,'fixed',1),(4,19,0,0.00,'fixed',1),(4,22,0,0.00,'fixed',1),(4,27,0,0.00,'fixed',1),(4,33,0,0.00,'fixed',1),(4,39,0,0.00,'fixed',1),(4,47,0,0.00,'fixed',1),(5,18,0,0.00,'fixed',1),(5,33,0,0.00,'fixed',1),(5,41,0,0.00,'fixed',1),(5,43,0,0.00,'fixed',1),(5,48,0,0.00,'fixed',1),(5,63,0,0.00,'fixed',1),(6,9,0,0.00,'fixed',1),(6,21,0,0.00,'fixed',1),(6,26,0,0.00,'fixed',1),(6,38,0,0.00,'fixed',1),(6,39,0,0.00,'fixed',1),(6,58,0,0.00,'fixed',1),(7,2,0,0.00,'fixed',1),(7,22,0,0.00,'fixed',1),(7,27,0,0.00,'fixed',1),(7,36,0,0.00,'fixed',1),(7,60,0,0.00,'fixed',1),(8,9,0,0.00,'fixed',1),(8,12,0,0.00,'fixed',1),(8,15,0,0.00,'fixed',1),(8,27,0,0.00,'fixed',1),(8,32,0,0.00,'fixed',1),(8,40,0,0.00,'fixed',1),(8,62,0,0.00,'fixed',1),(9,6,0,0.00,'fixed',1),(9,13,0,0.00,'fixed',1),(9,18,0,0.00,'fixed',1),(9,47,0,0.00,'fixed',1),(9,55,0,0.00,'fixed',1),(9,58,0,0.00,'fixed',1),(9,64,0,0.00,'fixed',1),(10,2,0,0.00,'fixed',1),(10,14,0,0.00,'fixed',1),(10,24,0,0.00,'fixed',1),(10,42,0,0.00,'fixed',1),(10,46,0,0.00,'fixed',1),(10,49,0,0.00,'fixed',1),(10,64,0,0.00,'fixed',1),(11,1,0,0.00,'fixed',1),(11,5,0,0.00,'fixed',1),(11,12,0,0.00,'fixed',1),(11,24,0,0.00,'fixed',1),(11,33,0,0.00,'fixed',1),(11,38,0,0.00,'fixed',1),(11,39,0,0.00,'fixed',1),(12,10,0,0.00,'fixed',1),(12,14,0,0.00,'fixed',1),(12,16,0,0.00,'fixed',1),(12,19,0,0.00,'fixed',1),(12,26,0,0.00,'fixed',1),(12,51,0,0.00,'fixed',1),(12,63,0,0.00,'fixed',1),(13,17,0,0.00,'fixed',1),(13,22,0,0.00,'fixed',1),(13,29,0,0.00,'fixed',1),(13,32,0,0.00,'fixed',1),(13,56,0,0.00,'fixed',1),(13,65,0,0.00,'fixed',1),(14,15,0,0.00,'fixed',1),(14,16,0,0.00,'fixed',1),(14,46,0,0.00,'fixed',1),(14,49,0,0.00,'fixed',1),(14,51,0,0.00,'fixed',1),(14,56,0,0.00,'fixed',1),(14,65,0,0.00,'fixed',1),(15,5,0,0.00,'fixed',1),(15,11,0,0.00,'fixed',1),(15,12,0,0.00,'fixed',1),(15,32,0,0.00,'fixed',1),(15,58,0,0.00,'fixed',1),(15,60,0,0.00,'fixed',1),(15,65,0,0.00,'fixed',1),(16,7,0,0.00,'fixed',1),(16,17,0,0.00,'fixed',1),(16,26,0,0.00,'fixed',1),(16,33,0,0.00,'fixed',1),(16,43,0,0.00,'fixed',1),(16,47,0,0.00,'fixed',1),(16,57,0,0.00,'fixed',1),(17,10,0,0.00,'fixed',1),(17,26,0,0.00,'fixed',1),(17,31,0,0.00,'fixed',1),(17,48,0,0.00,'fixed',1),(17,54,0,0.00,'fixed',1),(17,62,0,0.00,'fixed',1),(18,4,0,0.00,'fixed',1),(18,10,0,0.00,'fixed',1),(18,16,0,0.00,'fixed',1),(18,47,0,0.00,'fixed',1),(18,49,0,0.00,'fixed',1),(18,62,0,0.00,'fixed',1),(19,3,0,0.00,'fixed',1),(19,5,0,0.00,'fixed',1),(19,16,0,0.00,'fixed',1),(19,33,0,0.00,'fixed',1),(19,49,0,0.00,'fixed',1),(19,57,0,0.00,'fixed',1),(19,62,0,0.00,'fixed',1),(20,9,0,0.00,'fixed',1),(20,18,0,0.00,'fixed',1),(20,25,0,0.00,'fixed',1),(20,30,0,0.00,'fixed',1),(20,35,0,0.00,'fixed',1),(20,53,0,0.00,'fixed',1),(20,63,0,0.00,'fixed',1),(21,3,0,0.00,'fixed',1),(21,10,0,0.00,'fixed',1),(21,15,0,0.00,'fixed',1),(21,38,0,0.00,'fixed',1),(21,40,0,0.00,'fixed',1),(21,53,0,0.00,'fixed',1),(21,59,0,0.00,'fixed',1),(22,10,0,0.00,'fixed',1),(22,25,0,0.00,'fixed',1),(22,28,0,0.00,'fixed',1),(22,29,0,0.00,'fixed',1),(22,30,0,0.00,'fixed',1),(22,32,0,0.00,'fixed',1),(22,62,0,0.00,'fixed',1),(23,1,0,0.00,'fixed',1),(23,7,0,0.00,'fixed',1),(23,32,0,0.00,'fixed',1),(23,33,0,0.00,'fixed',1),(23,38,0,0.00,'fixed',1),(23,47,0,0.00,'fixed',1),(24,10,0,0.00,'fixed',1),(24,15,0,0.00,'fixed',1),(24,16,0,0.00,'fixed',1),(24,56,0,0.00,'fixed',1),(24,57,0,0.00,'fixed',1),(24,62,0,0.00,'fixed',1),(24,65,0,0.00,'fixed',1),(25,20,0,0.00,'fixed',1),(25,32,0,0.00,'fixed',1),(25,36,0,0.00,'fixed',1),(25,40,0,0.00,'fixed',1),(25,59,0,0.00,'fixed',1),(25,60,0,0.00,'fixed',1),(25,63,0,0.00,'fixed',1),(26,16,0,0.00,'fixed',1),(26,17,0,0.00,'fixed',1),(26,18,0,0.00,'fixed',1),(26,19,0,0.00,'fixed',1),(26,37,0,0.00,'fixed',1),(26,62,0,0.00,'fixed',1),(27,5,0,0.00,'fixed',1),(27,14,0,0.00,'fixed',1),(27,16,0,0.00,'fixed',1),(27,26,0,0.00,'fixed',1),(27,49,0,0.00,'fixed',1),(27,50,0,0.00,'fixed',1),(27,57,0,0.00,'fixed',1),(28,8,0,0.00,'fixed',1),(28,9,0,0.00,'fixed',1),(28,11,0,0.00,'fixed',1),(28,45,0,0.00,'fixed',1),(28,46,0,0.00,'fixed',1),(28,56,0,0.00,'fixed',1),(28,62,0,0.00,'fixed',1),(29,12,0,0.00,'fixed',1),(29,22,0,0.00,'fixed',1),(29,24,0,0.00,'fixed',1),(29,26,0,0.00,'fixed',1),(29,44,0,0.00,'fixed',1),(29,52,0,0.00,'fixed',1),(29,65,0,0.00,'fixed',1),(30,8,0,0.00,'fixed',1),(30,32,0,0.00,'fixed',1),(30,35,0,0.00,'fixed',1),(30,48,0,0.00,'fixed',1),(30,49,0,0.00,'fixed',1),(30,52,0,0.00,'fixed',1),(30,54,0,0.00,'fixed',1),(31,8,0,0.00,'fixed',1),(31,10,0,0.00,'fixed',1),(31,15,0,0.00,'fixed',1),(31,54,0,0.00,'fixed',1),(31,59,0,0.00,'fixed',1),(32,3,0,0.00,'fixed',1),(32,8,0,0.00,'fixed',1),(32,19,0,0.00,'fixed',1),(32,20,0,0.00,'fixed',1),(32,39,0,0.00,'fixed',1),(32,45,0,0.00,'fixed',1),(32,46,0,0.00,'fixed',1),(33,16,0,0.00,'fixed',1),(33,18,0,0.00,'fixed',1),(33,19,0,0.00,'fixed',1),(33,38,0,0.00,'fixed',1),(33,62,0,0.00,'fixed',1),(33,64,0,0.00,'fixed',1),(34,3,0,0.00,'fixed',1),(34,10,0,0.00,'fixed',1),(34,20,0,0.00,'fixed',1),(34,23,0,0.00,'fixed',1),(34,40,0,0.00,'fixed',1),(34,61,0,0.00,'fixed',1),(34,63,0,0.00,'fixed',1),(35,15,0,0.00,'fixed',1),(35,16,0,0.00,'fixed',1),(35,21,0,0.00,'fixed',1),(35,32,0,0.00,'fixed',1),(35,53,0,0.00,'fixed',1),(35,63,0,0.00,'fixed',1),(36,8,0,0.00,'fixed',1),(36,16,0,0.00,'fixed',1),(36,26,0,0.00,'fixed',1),(36,27,0,0.00,'fixed',1),(36,28,0,0.00,'fixed',1),(36,29,0,0.00,'fixed',1),(36,49,0,0.00,'fixed',1),(37,11,0,0.00,'fixed',1),(37,15,0,0.00,'fixed',1),(37,28,0,0.00,'fixed',1),(37,31,0,0.00,'fixed',1),(37,51,0,0.00,'fixed',1),(37,55,0,0.00,'fixed',1),(38,12,0,0.00,'fixed',1),(38,13,0,0.00,'fixed',1),(38,15,0,0.00,'fixed',1),(38,22,0,0.00,'fixed',1),(38,31,0,0.00,'fixed',1),(38,34,0,0.00,'fixed',1),(38,40,0,0.00,'fixed',1),(39,8,0,0.00,'fixed',1),(39,20,0,0.00,'fixed',1),(39,29,0,0.00,'fixed',1),(39,41,0,0.00,'fixed',1),(39,42,0,0.00,'fixed',1),(39,45,0,0.00,'fixed',1),(40,5,0,0.00,'fixed',1),(40,27,0,0.00,'fixed',1),(40,32,0,0.00,'fixed',1),(40,39,0,0.00,'fixed',1),(40,42,0,0.00,'fixed',1),(40,46,0,0.00,'fixed',1),(40,54,0,0.00,'fixed',1),(41,2,0,0.00,'fixed',1),(41,30,0,0.00,'fixed',1),(41,44,0,0.00,'fixed',1),(41,51,0,0.00,'fixed',1),(41,57,0,0.00,'fixed',1),(41,58,0,0.00,'fixed',1),(42,2,0,0.00,'fixed',1),(42,5,0,0.00,'fixed',1),(42,19,0,0.00,'fixed',1),(42,24,0,0.00,'fixed',1),(42,40,0,0.00,'fixed',1),(42,47,0,0.00,'fixed',1),(42,52,0,0.00,'fixed',1),(43,1,0,0.00,'fixed',1),(43,18,0,0.00,'fixed',1),(43,34,0,0.00,'fixed',1),(43,39,0,0.00,'fixed',1),(43,40,0,0.00,'fixed',1),(43,58,0,0.00,'fixed',1),(44,14,0,0.00,'fixed',1),(44,22,0,0.00,'fixed',1),(44,31,0,0.00,'fixed',1),(44,33,0,0.00,'fixed',1),(44,40,0,0.00,'fixed',1),(44,50,0,0.00,'fixed',1),(45,4,0,0.00,'fixed',1),(45,23,0,0.00,'fixed',1),(45,25,0,0.00,'fixed',1),(45,38,0,0.00,'fixed',1),(45,47,0,0.00,'fixed',1),(45,64,0,0.00,'fixed',1),(46,11,0,0.00,'fixed',1),(46,15,0,0.00,'fixed',1),(46,29,0,0.00,'fixed',1),(46,39,0,0.00,'fixed',1),(46,42,0,0.00,'fixed',1),(46,63,0,0.00,'fixed',1),(47,4,0,0.00,'fixed',1),(47,24,0,0.00,'fixed',1),(47,27,0,0.00,'fixed',1),(47,44,0,0.00,'fixed',1),(47,48,0,0.00,'fixed',1),(47,55,0,0.00,'fixed',1),(48,14,0,0.00,'fixed',1),(48,17,0,0.00,'fixed',1),(48,29,0,0.00,'fixed',1),(48,32,0,0.00,'fixed',1),(48,35,0,0.00,'fixed',1),(48,53,0,0.00,'fixed',1),(48,56,0,0.00,'fixed',1),(49,5,0,0.00,'fixed',1),(49,16,0,0.00,'fixed',1),(49,21,0,0.00,'fixed',1),(49,55,0,0.00,'fixed',1),(49,58,0,0.00,'fixed',1),(49,59,0,0.00,'fixed',1),(50,34,0,0.00,'fixed',1),(50,41,0,0.00,'fixed',1),(50,53,0,0.00,'fixed',1),(50,56,0,0.00,'fixed',1),(50,57,0,0.00,'fixed',1),(50,58,0,0.00,'fixed',1),(50,63,0,0.00,'fixed',1),(51,8,0,0.00,'fixed',1),(51,19,0,0.00,'fixed',1),(51,29,0,0.00,'fixed',1),(51,32,0,0.00,'fixed',1),(51,49,0,0.00,'fixed',1),(51,56,0,0.00,'fixed',1),(51,65,0,0.00,'fixed',1),(52,1,0,0.00,'fixed',1),(52,8,0,0.00,'fixed',1),(52,13,0,0.00,'fixed',1),(52,20,0,0.00,'fixed',1),(52,44,0,0.00,'fixed',1),(52,50,0,0.00,'fixed',1),(52,62,0,0.00,'fixed',1),(53,15,0,0.00,'fixed',1),(53,27,0,0.00,'fixed',1),(53,37,0,0.00,'fixed',1),(53,47,0,0.00,'fixed',1),(53,50,0,0.00,'fixed',1),(53,62,0,0.00,'fixed',1),(53,63,0,0.00,'fixed',1),(54,7,0,0.00,'fixed',1),(54,27,0,0.00,'fixed',1),(54,28,0,0.00,'fixed',1),(54,29,0,0.00,'fixed',1),(54,30,0,0.00,'fixed',1),(54,32,0,0.00,'fixed',1),(54,55,0,0.00,'fixed',1),(55,7,0,0.00,'fixed',1),(55,8,0,0.00,'fixed',1),(55,11,0,0.00,'fixed',1),(55,23,0,0.00,'fixed',1),(55,33,0,0.00,'fixed',1),(55,38,0,0.00,'fixed',1),(55,64,0,0.00,'fixed',1),(56,2,0,0.00,'fixed',1),(56,16,0,0.00,'fixed',1),(56,25,0,0.00,'fixed',1),(56,30,0,0.00,'fixed',1),(56,51,0,0.00,'fixed',1),(56,54,0,0.00,'fixed',1),(56,61,0,0.00,'fixed',1),(57,14,0,0.00,'fixed',1),(57,22,0,0.00,'fixed',1),(57,33,0,0.00,'fixed',1),(57,37,0,0.00,'fixed',1),(57,38,0,0.00,'fixed',1),(57,44,0,0.00,'fixed',1),(57,55,0,0.00,'fixed',1),(58,16,0,0.00,'fixed',1),(58,19,0,0.00,'fixed',1),(58,24,0,0.00,'fixed',1),(58,26,0,0.00,'fixed',1),(58,38,0,0.00,'fixed',1),(58,56,0,0.00,'fixed',1),(58,57,0,0.00,'fixed',1),(59,3,0,0.00,'fixed',1),(59,12,0,0.00,'fixed',1),(59,28,0,0.00,'fixed',1),(59,36,0,0.00,'fixed',1),(59,40,0,0.00,'fixed',1),(59,55,0,0.00,'fixed',1),(59,60,0,0.00,'fixed',1),(60,5,0,0.00,'fixed',1),(60,25,0,0.00,'fixed',1),(60,31,0,0.00,'fixed',1),(60,35,0,0.00,'fixed',1),(60,36,0,0.00,'fixed',1),(60,50,0,0.00,'fixed',1),(60,63,0,0.00,'fixed',1),(61,2,0,0.00,'fixed',1),(61,9,0,0.00,'fixed',1),(61,16,0,0.00,'fixed',1),(61,26,0,0.00,'fixed',1),(61,36,0,0.00,'fixed',1),(61,38,0,0.00,'fixed',1),(61,39,0,0.00,'fixed',1),(62,6,0,0.00,'fixed',1),(62,25,0,0.00,'fixed',1),(62,28,0,0.00,'fixed',1),(62,29,0,0.00,'fixed',1),(62,48,0,0.00,'fixed',1),(62,55,0,0.00,'fixed',1),(62,64,0,0.00,'fixed',1),(63,11,0,0.00,'fixed',1),(63,18,0,0.00,'fixed',1),(63,24,0,0.00,'fixed',1),(63,37,0,0.00,'fixed',1),(63,47,0,0.00,'fixed',1),(63,58,0,0.00,'fixed',1),(63,61,0,0.00,'fixed',1),(64,20,0,0.00,'fixed',1),(64,33,0,0.00,'fixed',1),(64,38,0,0.00,'fixed',1),(64,45,0,0.00,'fixed',1),(64,52,0,0.00,'fixed',1),(64,55,0,0.00,'fixed',1),(64,59,0,0.00,'fixed',1),(65,10,0,0.00,'fixed',1),(65,22,0,0.00,'fixed',1),(65,47,0,0.00,'fixed',1),(65,49,0,0.00,'fixed',1),(65,56,0,0.00,'fixed',1),(65,57,0,0.00,'fixed',1),(65,60,0,0.00,'fixed',1);
/*!40000 ALTER TABLE `ec_product_cross_sale_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_files`
--

DROP TABLE IF EXISTS `ec_product_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `url` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `extras` mediumtext COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_product_files_product_id_index` (`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_files`
--

LOCK TABLES `ec_product_files` WRITE;
/*!40000 ALTER TABLE `ec_product_files` DISABLE KEYS */;
INSERT INTO `ec_product_files` VALUES (1,4,'ecommerce/digital-product-files/4.jpg','{\"filename\":\"4.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/4.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"4\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(2,4,'ecommerce/digital-product-files/4-1.jpg','{\"filename\":\"4-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/4-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"4-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(3,8,'ecommerce/digital-product-files/8.jpg','{\"filename\":\"8.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/8.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"8\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(4,8,'ecommerce/digital-product-files/8-1.jpg','{\"filename\":\"8-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/8-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"8-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(5,12,'ecommerce/digital-product-files/12.jpg','{\"filename\":\"12.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(6,12,'ecommerce/digital-product-files/12-1.jpg','{\"filename\":\"12-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(7,16,'ecommerce/digital-product-files/16.jpg','{\"filename\":\"16.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/16.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"16\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(8,20,'ecommerce/digital-product-files/20.jpg','{\"filename\":\"20.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/20.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"20\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(9,20,'ecommerce/digital-product-files/20-1.jpg','{\"filename\":\"20-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/20-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"20-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(10,24,'ecommerce/digital-product-files/24.jpg','{\"filename\":\"24.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/24.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"24\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(11,24,'ecommerce/digital-product-files/24-1.jpg','{\"filename\":\"24-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/24-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"24-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(12,28,'ecommerce/digital-product-files/28.jpg','{\"filename\":\"28.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/28.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"28\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(13,28,'ecommerce/digital-product-files/28-1.jpg','{\"filename\":\"28-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/28-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"28-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(14,32,'ecommerce/digital-product-files/32.jpg','{\"filename\":\"32.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/32.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"32\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(15,32,'ecommerce/digital-product-files/32-1.jpg','{\"filename\":\"32-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/32-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"32-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(16,36,'ecommerce/digital-product-files/36.jpg','{\"filename\":\"36.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/36.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"36\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(17,36,'ecommerce/digital-product-files/36-1.jpg','{\"filename\":\"36-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/36-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"36-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(18,40,'ecommerce/digital-product-files/40.jpg','{\"filename\":\"40.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/40.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"40\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(19,40,'ecommerce/digital-product-files/40-1.jpg','{\"filename\":\"40-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/40-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"40-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(20,44,'ecommerce/digital-product-files/44.jpg','{\"filename\":\"44.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/44.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"44\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(21,44,'ecommerce/digital-product-files/44-1.jpg','{\"filename\":\"44-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/44-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"44-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(22,48,'ecommerce/digital-product-files/48.jpg','{\"filename\":\"48.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/48.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"48\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(23,48,'ecommerce/digital-product-files/48-1.jpg','{\"filename\":\"48-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/48-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"48-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(24,52,'ecommerce/digital-product-files/52.jpg','{\"filename\":\"52.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/52.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"52\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(25,52,'ecommerce/digital-product-files/52-1.jpg','{\"filename\":\"52-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/52-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"52-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(26,56,'ecommerce/digital-product-files/56.jpg','{\"filename\":\"56.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/56.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"56\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(27,56,'ecommerce/digital-product-files/56-1.jpg','{\"filename\":\"56-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/56-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"56-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(28,60,'ecommerce/digital-product-files/60.jpg','{\"filename\":\"60.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/60.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"60\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(29,60,'ecommerce/digital-product-files/60-1.jpg','{\"filename\":\"60-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/60-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"60-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(30,64,'ecommerce/digital-product-files/64.jpg','{\"filename\":\"64.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/64.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"64\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(31,64,'ecommerce/digital-product-files/64-1.jpg','{\"filename\":\"64-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/64-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"64-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(32,82,'ecommerce/digital-product-files/8-1.jpg','{\"filename\":\"8-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/8-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"8-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(33,84,'ecommerce/digital-product-files/12-1.jpg','{\"filename\":\"12-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(34,85,'ecommerce/digital-product-files/12-2.jpg','{\"filename\":\"12-2.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12-2.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12-2\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(35,86,'ecommerce/digital-product-files/12-3.jpg','{\"filename\":\"12-3.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12-3.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12-3\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(36,87,'ecommerce/digital-product-files/12.jpg','{\"filename\":\"12.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/12.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"12\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(37,115,'ecommerce/digital-product-files/40-1.jpg','{\"filename\":\"40-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/40-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"40-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(38,116,'ecommerce/digital-product-files/40.jpg','{\"filename\":\"40.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/40.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"40\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(39,122,'ecommerce/digital-product-files/44-1.jpg','{\"filename\":\"44-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/44-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"44-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(40,123,'ecommerce/digital-product-files/44-2.jpg','{\"filename\":\"44-2.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/44-2.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"44-2\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(41,124,'ecommerce/digital-product-files/44.jpg','{\"filename\":\"44.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/44.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"44\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(42,130,'ecommerce/digital-product-files/48-1.jpg','{\"filename\":\"48-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/48-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"48-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(43,140,'ecommerce/digital-product-files/60-1.jpg','{\"filename\":\"60-1.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/60-1.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"60-1\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(44,141,'ecommerce/digital-product-files/60-2.jpg','{\"filename\":\"60-2.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/60-2.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"60-2\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33'),(45,142,'ecommerce/digital-product-files/60.jpg','{\"filename\":\"60.jpg\",\"url\":\"ecommerce\\/digital-product-files\\/60.jpg\",\"mime_type\":\"image\\/jpeg\",\"size\":9803,\"modified\":\"2025-09-11 08:46:33\",\"name\":\"60\",\"extension\":\"jpg\"}','2025-09-11 01:46:33','2025-09-11 01:46:33');
/*!40000 ALTER TABLE `ec_product_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_label_products`
--

DROP TABLE IF EXISTS `ec_product_label_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_label_products` (
  `product_label_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_label_id`,`product_id`),
  KEY `ec_product_label_products_product_label_id_index` (`product_label_id`),
  KEY `ec_product_label_products_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_label_products`
--

LOCK TABLES `ec_product_label_products` WRITE;
/*!40000 ALTER TABLE `ec_product_label_products` DISABLE KEYS */;
INSERT INTO `ec_product_label_products` VALUES (1,3),(1,12),(1,18),(1,30),(1,39),(1,42),(1,57),(2,24),(2,36),(2,48),(2,54),(2,60),(3,6),(3,9),(3,15),(3,21),(3,27),(3,33),(3,45),(3,51),(3,63);
/*!40000 ALTER TABLE `ec_product_label_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_labels`
--

DROP TABLE IF EXISTS `ec_product_labels`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_labels` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `color` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `text_color` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_labels`
--

LOCK TABLES `ec_product_labels` WRITE;
/*!40000 ALTER TABLE `ec_product_labels` DISABLE KEYS */;
INSERT INTO `ec_product_labels` VALUES (1,'Hot','#d71e2d','published','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL),(2,'New','#02856e','published','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL),(3,'Sale','#fe9931','published','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL);
/*!40000 ALTER TABLE `ec_product_labels` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_labels_translations`
--

DROP TABLE IF EXISTS `ec_product_labels_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_labels_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_labels_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_product_labels_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_labels_translations`
--

LOCK TABLES `ec_product_labels_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_labels_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_labels_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_license_codes`
--

DROP TABLE IF EXISTS `ec_product_license_codes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_license_codes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `license_code` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'available',
  `assigned_order_product_id` bigint unsigned DEFAULT NULL,
  `assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_product_license_codes_product_id_status_index` (`product_id`,`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_license_codes`
--

LOCK TABLES `ec_product_license_codes` WRITE;
/*!40000 ALTER TABLE `ec_product_license_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_license_codes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_related_relations`
--

DROP TABLE IF EXISTS `ec_product_related_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_related_relations` (
  `from_product_id` bigint unsigned NOT NULL,
  `to_product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`from_product_id`,`to_product_id`),
  KEY `ec_product_related_relations_from_product_id_index` (`from_product_id`),
  KEY `ec_product_related_relations_to_product_id_index` (`to_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_related_relations`
--

LOCK TABLES `ec_product_related_relations` WRITE;
/*!40000 ALTER TABLE `ec_product_related_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_related_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_specification_attribute`
--

DROP TABLE IF EXISTS `ec_product_specification_attribute`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_specification_attribute` (
  `product_id` bigint unsigned NOT NULL,
  `attribute_id` bigint unsigned NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `hidden` tinyint(1) NOT NULL DEFAULT '0',
  `order` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_specification_attribute`
--

LOCK TABLES `ec_product_specification_attribute` WRITE;
/*!40000 ALTER TABLE `ec_product_specification_attribute` DISABLE KEYS */;
INSERT INTO `ec_product_specification_attribute` VALUES (1,6,'1.2 cm',0,0),(1,7,'9.69 cm',0,0),(1,8,'2560x1440',0,0),(2,6,'63.53 cm',0,0),(2,7,'81.8 cm',0,0),(2,8,'3840x2160',0,0),(3,1,'6.26 cm',0,0),(3,2,'12.85 cm',0,0),(3,3,'29.96 cm',0,0),(3,4,'88 cm',0,0),(3,5,'40.11 cm',0,0),(4,6,'27.48 cm',0,0),(4,7,'70.99 cm',0,0),(4,8,'3840x2160',0,0),(5,1,'19.68 cm',0,0),(5,2,'39.29 cm',0,0),(5,3,'61.08 cm',0,0),(5,4,'12.52 cm',0,0),(5,5,'16.55 cm',0,0),(6,6,'84.33 cm',0,0),(6,7,'17.79 cm',0,0),(6,8,'1920x1080',0,0),(7,1,'66.01 cm',0,0),(7,2,'5.96 cm',0,0),(7,3,'81.77 cm',0,0),(7,4,'90.6 cm',0,0),(7,5,'76.27 cm',0,0),(8,6,'38.69 cm',0,0),(8,7,'85.43 cm',0,0),(8,8,'3840x2160',0,0),(9,1,'8.69 cm',0,0),(9,2,'78.39 cm',0,0),(9,3,'88.88 cm',0,0),(9,4,'1.94 cm',0,0),(9,5,'75.91 cm',0,0),(10,1,'47.43 cm',0,0),(10,2,'6.49 cm',0,0),(10,3,'46.34 cm',0,0),(10,4,'59.11 cm',0,0),(10,5,'32.97 cm',0,0),(11,1,'94.82 cm',0,0),(11,2,'82.4 cm',0,0),(11,3,'79.11 cm',0,0),(11,4,'68.09 cm',0,0),(11,5,'97.64 cm',0,0),(12,6,'61.27 cm',0,0),(12,7,'54.05 cm',0,0),(12,8,'3840x2160',0,0),(13,1,'90.68 cm',0,0),(13,2,'66.17 cm',0,0),(13,3,'75.54 cm',0,0),(13,4,'9.61 cm',0,0),(13,5,'7.89 cm',0,0),(14,6,'88.46 cm',0,0),(14,7,'52.81 cm',0,0),(14,8,'1920x1080',0,0),(15,6,'76.72 cm',0,0),(15,7,'31.11 cm',0,0),(15,8,'3840x2160',0,0),(16,1,'86.44 cm',0,0),(16,2,'39.12 cm',0,0),(16,3,'48.49 cm',0,0),(16,4,'71.77 cm',0,0),(16,5,'40.76 cm',0,0),(17,1,'47.99 cm',0,0),(17,2,'20.12 cm',0,0),(17,3,'64.91 cm',0,0),(17,4,'74.89 cm',0,0),(17,5,'6.65 cm',0,0),(18,1,'72.33 cm',0,0),(18,2,'27.79 cm',0,0),(18,3,'33.57 cm',0,0),(18,4,'67.41 cm',0,0),(18,5,'59.24 cm',0,0),(19,1,'79.01 cm',0,0),(19,2,'73.49 cm',0,0),(19,3,'57.6 cm',0,0),(19,4,'31.04 cm',0,0),(19,5,'52.6 cm',0,0),(20,6,'14.14 cm',0,0),(20,7,'88.97 cm',0,0),(20,8,'2560x1440',0,0),(21,1,'19.93 cm',0,0),(21,2,'58.41 cm',0,0),(21,3,'37.3 cm',0,0),(21,4,'27.98 cm',0,0),(21,5,'62.42 cm',0,0),(22,6,'91.28 cm',0,0),(22,7,'33.15 cm',0,0),(22,8,'1920x1080',0,0),(23,6,'34.78 cm',0,0),(23,7,'30.5 cm',0,0),(23,8,'1920x1080',0,0),(24,1,'42.7 cm',0,0),(24,2,'68.56 cm',0,0),(24,3,'95.88 cm',0,0),(24,4,'27.04 cm',0,0),(24,5,'77.63 cm',0,0),(25,6,'52.29 cm',0,0),(25,7,'41.21 cm',0,0),(25,8,'1920x1080',0,0),(26,1,'23.02 cm',0,0),(26,2,'65.57 cm',0,0),(26,3,'38.27 cm',0,0),(26,4,'77.58 cm',0,0),(26,5,'5.14 cm',0,0),(27,1,'95.64 cm',0,0),(27,2,'71.92 cm',0,0),(27,3,'58.7 cm',0,0),(27,4,'24.01 cm',0,0),(27,5,'13.69 cm',0,0),(28,1,'55.18 cm',0,0),(28,2,'15.86 cm',0,0),(28,3,'36.29 cm',0,0),(28,4,'43.08 cm',0,0),(28,5,'39.71 cm',0,0),(29,6,'75.04 cm',0,0),(29,7,'75.24 cm',0,0),(29,8,'1920x1080',0,0),(30,1,'56.89 cm',0,0),(30,2,'57.93 cm',0,0),(30,3,'79.21 cm',0,0),(30,4,'60.83 cm',0,0),(30,5,'14.92 cm',0,0),(31,1,'95.36 cm',0,0),(31,2,'60.66 cm',0,0),(31,3,'41.37 cm',0,0),(31,4,'54.24 cm',0,0),(31,5,'19.11 cm',0,0),(32,1,'45.25 cm',0,0),(32,2,'57.24 cm',0,0),(32,3,'68.07 cm',0,0),(32,4,'96.27 cm',0,0),(32,5,'24.99 cm',0,0),(33,6,'76.88 cm',0,0),(33,7,'69.87 cm',0,0),(33,8,'2560x1440',0,0),(34,6,'9.54 cm',0,0),(34,7,'65.83 cm',0,0),(34,8,'3840x2160',0,0),(35,6,'40.13 cm',0,0),(35,7,'75.53 cm',0,0),(35,8,'2560x1440',0,0),(36,1,'70.33 cm',0,0),(36,2,'42.05 cm',0,0),(36,3,'78.95 cm',0,0),(36,4,'65.9 cm',0,0),(36,5,'92.94 cm',0,0),(37,1,'62.65 cm',0,0),(37,2,'31.69 cm',0,0),(37,3,'59.21 cm',0,0),(37,4,'6.14 cm',0,0),(37,5,'33.22 cm',0,0),(38,1,'80.83 cm',0,0),(38,2,'39.8 cm',0,0),(38,3,'93.04 cm',0,0),(38,4,'55.47 cm',0,0),(38,5,'92.53 cm',0,0),(39,6,'77.98 cm',0,0),(39,7,'91.15 cm',0,0),(39,8,'3840x2160',0,0),(40,1,'26.6 cm',0,0),(40,2,'39.68 cm',0,0),(40,3,'92.74 cm',0,0),(40,4,'49.82 cm',0,0),(40,5,'49.89 cm',0,0),(41,1,'9 cm',0,0),(41,2,'69.91 cm',0,0),(41,3,'42.89 cm',0,0),(41,4,'32.88 cm',0,0),(41,5,'93.96 cm',0,0),(42,1,'33.66 cm',0,0),(42,2,'10.38 cm',0,0),(42,3,'4.72 cm',0,0),(42,4,'70.16 cm',0,0),(42,5,'57.16 cm',0,0),(43,1,'18.3 cm',0,0),(43,2,'69.74 cm',0,0),(43,3,'61.38 cm',0,0),(43,4,'34.59 cm',0,0),(43,5,'54.97 cm',0,0),(44,1,'19.39 cm',0,0),(44,2,'18.02 cm',0,0),(44,3,'54.03 cm',0,0),(44,4,'64.8 cm',0,0),(44,5,'1.58 cm',0,0),(45,6,'74.72 cm',0,0),(45,7,'69.98 cm',0,0),(45,8,'2560x1440',0,0),(46,1,'27.96 cm',0,0),(46,2,'3.56 cm',0,0),(46,3,'75.96 cm',0,0),(46,4,'63.25 cm',0,0),(46,5,'52.13 cm',0,0),(47,1,'67.37 cm',0,0),(47,2,'89.55 cm',0,0),(47,3,'61.75 cm',0,0),(47,4,'70.19 cm',0,0),(47,5,'2.52 cm',0,0),(48,6,'90.24 cm',0,0),(48,7,'32.33 cm',0,0),(48,8,'2560x1440',0,0),(49,1,'78.38 cm',0,0),(49,2,'61.89 cm',0,0),(49,3,'42.24 cm',0,0),(49,4,'61.59 cm',0,0),(49,5,'58.3 cm',0,0),(50,6,'96.68 cm',0,0),(50,7,'88.41 cm',0,0),(50,8,'1920x1080',0,0),(51,1,'15.71 cm',0,0),(51,2,'95.51 cm',0,0),(51,3,'46.45 cm',0,0),(51,4,'87.1 cm',0,0),(51,5,'33.09 cm',0,0),(52,1,'82.19 cm',0,0),(52,2,'20.35 cm',0,0),(52,3,'12.41 cm',0,0),(52,4,'45.65 cm',0,0),(52,5,'57.44 cm',0,0),(53,6,'68.84 cm',0,0),(53,7,'44.36 cm',0,0),(53,8,'2560x1440',0,0),(54,6,'25.87 cm',0,0),(54,7,'45.64 cm',0,0),(54,8,'2560x1440',0,0),(55,6,'62.64 cm',0,0),(55,7,'25.5 cm',0,0),(55,8,'3840x2160',0,0),(56,6,'67.93 cm',0,0),(56,7,'75.54 cm',0,0),(56,8,'3840x2160',0,0),(57,1,'21.06 cm',0,0),(57,2,'84.67 cm',0,0),(57,3,'9.86 cm',0,0),(57,4,'62.78 cm',0,0),(57,5,'86.9 cm',0,0),(58,1,'40.58 cm',0,0),(58,2,'76.52 cm',0,0),(58,3,'89.71 cm',0,0),(58,4,'73.03 cm',0,0),(58,5,'31.72 cm',0,0),(59,1,'92.88 cm',0,0),(59,2,'77.46 cm',0,0),(59,3,'78.8 cm',0,0),(59,4,'47.4 cm',0,0),(59,5,'84.8 cm',0,0),(60,6,'82.43 cm',0,0),(60,7,'28.15 cm',0,0),(60,8,'2560x1440',0,0),(61,1,'33.58 cm',0,0),(61,2,'35.14 cm',0,0),(61,3,'33.29 cm',0,0),(61,4,'82.78 cm',0,0),(61,5,'91.47 cm',0,0),(62,6,'93.62 cm',0,0),(62,7,'30.32 cm',0,0),(62,8,'3840x2160',0,0),(63,6,'41.28 cm',0,0),(63,7,'84.37 cm',0,0),(63,8,'1920x1080',0,0),(64,1,'55.9 cm',0,0),(64,2,'75.5 cm',0,0),(64,3,'41.32 cm',0,0),(64,4,'15.94 cm',0,0),(64,5,'11.84 cm',0,0),(65,6,'55.96 cm',0,0),(65,7,'83.31 cm',0,0),(65,8,'2560x1440',0,0);
/*!40000 ALTER TABLE `ec_product_specification_attribute` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_specification_attribute_translations`
--

DROP TABLE IF EXISTS `ec_product_specification_attribute_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_specification_attribute_translations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `attribute_id` bigint unsigned NOT NULL,
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `psat_unique` (`product_id`,`attribute_id`,`lang_code`),
  KEY `psat_product_attribute_index` (`product_id`,`attribute_id`),
  KEY `psat_product_id_index` (`product_id`),
  KEY `psat_attribute_id_index` (`attribute_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_specification_attribute_translations`
--

LOCK TABLES `ec_product_specification_attribute_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_specification_attribute_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_specification_attribute_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_tag_product`
--

DROP TABLE IF EXISTS `ec_product_tag_product`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_tag_product` (
  `product_id` bigint unsigned NOT NULL,
  `tag_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`tag_id`),
  KEY `ec_product_tag_product_product_id_index` (`product_id`),
  KEY `ec_product_tag_product_tag_id_index` (`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_tag_product`
--

LOCK TABLES `ec_product_tag_product` WRITE;
/*!40000 ALTER TABLE `ec_product_tag_product` DISABLE KEYS */;
INSERT INTO `ec_product_tag_product` VALUES (1,1),(1,3),(1,4),(2,1),(2,3),(2,4),(3,1),(3,4),(3,6),(4,1),(4,3),(4,6),(5,2),(5,3),(5,5),(6,1),(6,4),(6,5),(7,3),(7,4),(7,5),(8,2),(8,5),(8,6),(9,2),(9,5),(9,6),(10,2),(10,4),(10,6),(11,2),(11,3),(11,4),(12,1),(12,2),(12,4),(13,1),(13,4),(13,6),(14,2),(14,4),(14,5),(15,2),(15,4),(15,5),(16,1),(16,2),(16,5),(17,1),(17,5),(17,6),(18,1),(18,3),(18,5),(19,1),(19,2),(19,5),(20,1),(20,3),(20,5),(21,2),(21,4),(21,5),(22,3),(22,4),(22,6),(23,1),(23,3),(23,6),(24,2),(24,3),(24,4),(25,1),(25,3),(25,6),(26,1),(26,3),(26,4),(27,3),(27,4),(27,6),(28,1),(28,3),(28,5),(29,3),(29,5),(29,6),(30,2),(30,3),(30,6),(31,1),(31,3),(31,5),(32,2),(32,3),(32,5),(33,1),(33,3),(33,5),(34,4),(34,5),(34,6),(35,1),(35,4),(35,5),(36,4),(36,5),(36,6),(37,1),(37,2),(37,5),(38,1),(38,4),(38,6),(39,1),(39,2),(39,5),(40,1),(40,2),(40,3),(41,2),(41,4),(41,5),(42,2),(42,4),(42,5),(43,2),(43,3),(43,6),(44,1),(44,2),(44,4),(45,1),(45,4),(45,6),(46,1),(46,2),(46,3),(47,1),(47,2),(47,6),(48,2),(48,4),(48,6),(49,1),(49,4),(49,5),(50,1),(50,3),(50,6),(51,2),(51,4),(51,5),(52,3),(52,4),(52,6),(53,1),(53,4),(53,6),(54,1),(54,3),(54,4),(55,1),(55,3),(55,5),(56,1),(56,3),(56,4),(57,1),(57,3),(57,4),(58,1),(58,4),(58,5),(59,3),(59,4),(59,5),(60,1),(60,2),(60,5),(61,2),(61,5),(61,6),(62,1),(62,2),(62,4),(63,1),(63,2),(63,4),(64,3),(64,4),(64,6),(65,1),(65,3),(65,4);
/*!40000 ALTER TABLE `ec_product_tag_product` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_tags`
--

DROP TABLE IF EXISTS `ec_product_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_tags`
--

LOCK TABLES `ec_product_tags` WRITE;
/*!40000 ALTER TABLE `ec_product_tags` DISABLE KEYS */;
INSERT INTO `ec_product_tags` VALUES (1,'Electronic',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(2,'Mobile',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(3,'Iphone',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(4,'Printer',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(5,'Office',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(6,'IT',NULL,'published','2025-09-11 01:45:59','2025-09-11 01:45:59');
/*!40000 ALTER TABLE `ec_product_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_tags_translations`
--

DROP TABLE IF EXISTS `ec_product_tags_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_tags_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_product_tags_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_product_tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_tags_translations`
--

LOCK TABLES `ec_product_tags_translations` WRITE;
/*!40000 ALTER TABLE `ec_product_tags_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_tags_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_up_sale_relations`
--

DROP TABLE IF EXISTS `ec_product_up_sale_relations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_up_sale_relations` (
  `from_product_id` bigint unsigned NOT NULL,
  `to_product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`from_product_id`,`to_product_id`),
  KEY `ec_product_up_sale_relations_from_product_id_index` (`from_product_id`),
  KEY `ec_product_up_sale_relations_to_product_id_index` (`to_product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_up_sale_relations`
--

LOCK TABLES `ec_product_up_sale_relations` WRITE;
/*!40000 ALTER TABLE `ec_product_up_sale_relations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_up_sale_relations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_variation_items`
--

DROP TABLE IF EXISTS `ec_product_variation_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_variation_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `attribute_id` bigint unsigned NOT NULL,
  `variation_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_product_variation_items_attribute_id_variation_id_unique` (`attribute_id`,`variation_id`),
  KEY `attribute_variation_index` (`attribute_id`,`variation_id`),
  KEY `idx_variation_id` (`variation_id`),
  KEY `idx_variation_attribute_covering` (`variation_id`,`attribute_id`)
) ENGINE=InnoDB AUTO_INCREMENT=163 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_variation_items`
--

LOCK TABLES `ec_product_variation_items` WRITE;
/*!40000 ALTER TABLE `ec_product_variation_items` DISABLE KEYS */;
INSERT INTO `ec_product_variation_items` VALUES (29,1,15),(33,1,17),(35,1,18),(43,1,22),(53,1,27),(63,1,32),(79,1,40),(83,1,42),(1,2,1),(7,2,4),(21,2,11),(25,2,13),(27,2,14),(49,2,25),(55,2,28),(59,2,30),(65,2,33),(75,2,38),(77,2,39),(19,3,10),(37,3,19),(41,3,21),(61,3,31),(69,3,35),(3,4,2),(5,4,3),(11,4,6),(15,4,8),(17,4,9),(23,4,12),(47,4,24),(57,4,29),(67,4,34),(71,4,36),(85,4,43),(9,5,5),(13,5,7),(31,5,16),(39,5,20),(45,5,23),(51,5,26),(73,5,37),(81,5,41),(87,5,44),(2,6,1),(22,6,11),(28,6,14),(34,6,17),(36,6,18),(10,7,5),(24,7,12),(26,7,13),(52,7,26),(56,7,28),(62,7,31),(6,8,3),(14,8,7),(18,8,9),(20,8,10),(32,8,16),(40,8,20),(46,8,23),(50,8,25),(58,8,29),(60,8,30),(64,8,32),(66,8,33),(80,8,40),(88,8,44),(4,9,2),(38,9,19),(44,9,22),(68,9,34),(70,9,35),(76,9,38),(82,9,41),(84,9,42),(86,9,43),(8,10,4),(12,10,6),(16,10,8),(30,10,15),(42,10,21),(48,10,24),(54,10,27),(72,10,36),(74,10,37),(78,10,39),(93,11,47),(105,11,53),(107,11,54),(123,11,62),(135,11,68),(159,11,80),(91,12,46),(113,12,57),(121,12,61),(137,12,69),(145,12,73),(147,12,74),(157,12,79),(95,13,48),(99,13,50),(101,13,51),(109,13,55),(111,13,56),(115,13,58),(117,13,59),(119,13,60),(129,13,65),(143,13,72),(103,14,52),(125,14,63),(139,14,70),(149,14,75),(153,14,77),(89,15,45),(97,15,49),(127,15,64),(131,15,66),(133,15,67),(141,15,71),(151,15,76),(155,15,78),(161,15,81),(96,16,48),(128,16,64),(140,16,70),(156,16,78),(162,16,81),(100,17,50),(102,17,51),(106,17,53),(114,17,57),(120,17,60),(122,17,61),(134,17,67),(146,17,73),(148,17,74),(154,17,77),(118,18,59),(124,18,62),(126,18,63),(130,18,65),(132,18,66),(142,18,71),(144,18,72),(90,19,45),(92,19,46),(98,19,49),(108,19,54),(110,19,55),(136,19,68),(152,19,76),(160,19,80),(94,20,47),(104,20,52),(112,20,56),(116,20,58),(138,20,69),(150,20,75),(158,20,79);
/*!40000 ALTER TABLE `ec_product_variation_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_variations`
--

DROP TABLE IF EXISTS `ec_product_variations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_variations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned DEFAULT NULL,
  `configurable_product_id` bigint unsigned NOT NULL,
  `is_default` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_product_variations_product_id_configurable_product_id_unique` (`product_id`,`configurable_product_id`),
  KEY `configurable_product_index` (`product_id`,`configurable_product_id`),
  KEY `idx_configurable_product_id` (`configurable_product_id`),
  KEY `idx_product_variations_config` (`configurable_product_id`,`is_default`)
) ENGINE=InnoDB AUTO_INCREMENT=82 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_variations`
--

LOCK TABLES `ec_product_variations` WRITE;
/*!40000 ALTER TABLE `ec_product_variations` DISABLE KEYS */;
INSERT INTO `ec_product_variations` VALUES (1,66,1,1),(2,67,1,0),(3,68,1,0),(4,69,2,1),(5,70,2,0),(6,71,2,0),(7,72,2,0),(8,73,2,0),(9,74,3,1),(10,75,3,0),(11,76,5,1),(12,77,5,0),(13,78,5,0),(14,79,5,0),(15,80,6,1),(16,81,6,0),(17,82,8,1),(18,83,9,1),(19,84,12,1),(20,85,12,0),(21,86,12,0),(22,87,12,0),(23,88,14,1),(24,89,14,0),(25,90,15,1),(26,91,15,0),(27,92,15,0),(28,93,15,0),(29,94,17,1),(30,95,17,0),(31,96,19,1),(32,97,19,0),(33,98,19,0),(34,99,22,1),(35,100,22,0),(36,101,22,0),(37,102,27,1),(38,103,27,0),(39,104,27,0),(40,105,30,1),(41,106,30,0),(42,107,30,0),(43,108,30,0),(44,109,31,1),(45,110,34,1),(46,111,37,1),(47,112,37,0),(48,113,37,0),(49,114,39,1),(50,115,40,1),(51,116,40,0),(52,117,41,1),(53,118,43,1),(54,119,43,0),(55,120,43,0),(56,121,43,0),(57,122,44,1),(58,123,44,0),(59,124,44,0),(60,125,45,1),(61,126,45,0),(62,127,45,0),(63,128,45,0),(64,129,45,0),(65,130,48,1),(66,131,49,1),(67,132,49,0),(68,133,51,1),(69,134,55,1),(70,135,55,0),(71,136,55,0),(72,137,55,0),(73,138,57,1),(74,139,57,0),(75,140,60,1),(76,141,60,0),(77,142,60,0),(78,143,63,1),(79,144,63,0),(80,145,65,1),(81,146,65,0);
/*!40000 ALTER TABLE `ec_product_variations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_views`
--

DROP TABLE IF EXISTS `ec_product_views`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_views` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `views` int NOT NULL DEFAULT '1',
  `date` date NOT NULL DEFAULT '2025-09-11',
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_product_views_product_id_date_unique` (`product_id`,`date`),
  KEY `ec_product_views_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_views`
--

LOCK TABLES `ec_product_views` WRITE;
/*!40000 ALTER TABLE `ec_product_views` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_product_views` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_product_with_attribute_set`
--

DROP TABLE IF EXISTS `ec_product_with_attribute_set`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_product_with_attribute_set` (
  `attribute_set_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `order` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`product_id`,`attribute_set_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_product_with_attribute_set`
--

LOCK TABLES `ec_product_with_attribute_set` WRITE;
/*!40000 ALTER TABLE `ec_product_with_attribute_set` DISABLE KEYS */;
INSERT INTO `ec_product_with_attribute_set` VALUES (1,1,0),(2,1,0),(1,2,0),(2,2,0),(1,3,0),(2,3,0),(1,5,0),(2,5,0),(1,6,0),(2,6,0),(1,8,0),(2,8,0),(1,9,0),(2,9,0),(1,12,0),(2,12,0),(1,14,0),(2,14,0),(1,15,0),(2,15,0),(1,17,0),(2,17,0),(1,19,0),(2,19,0),(1,22,0),(2,22,0),(1,27,0),(2,27,0),(1,30,0),(2,30,0),(1,31,0),(2,31,0),(3,34,0),(4,34,0),(3,37,0),(4,37,0),(3,39,0),(4,39,0),(3,40,0),(4,40,0),(3,41,0),(4,41,0),(3,43,0),(4,43,0),(3,44,0),(4,44,0),(3,45,0),(4,45,0),(3,48,0),(4,48,0),(3,49,0),(4,49,0),(3,51,0),(4,51,0),(3,55,0),(4,55,0),(3,57,0),(4,57,0),(3,60,0),(4,60,0),(3,63,0),(4,63,0),(3,65,0),(4,65,0);
/*!40000 ALTER TABLE `ec_product_with_attribute_set` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_products`
--

DROP TABLE IF EXISTS `ec_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `images` text COLLATE utf8mb4_unicode_ci,
  `video_media` text COLLATE utf8mb4_unicode_ci,
  `sku` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `order` int unsigned NOT NULL DEFAULT '0',
  `quantity` int unsigned DEFAULT NULL,
  `allow_checkout_when_out_of_stock` tinyint unsigned NOT NULL DEFAULT '0',
  `with_storehouse_management` tinyint unsigned NOT NULL DEFAULT '0',
  `stock_status` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'in_stock',
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `brand_id` bigint unsigned DEFAULT NULL,
  `is_variation` tinyint NOT NULL DEFAULT '0',
  `variations_count` int unsigned NOT NULL DEFAULT '0',
  `reviews_count` int unsigned NOT NULL DEFAULT '0',
  `reviews_avg` decimal(3,2) NOT NULL DEFAULT '0.00',
  `sale_type` tinyint NOT NULL DEFAULT '0',
  `price` double unsigned DEFAULT NULL,
  `sale_price` double unsigned DEFAULT NULL,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `length` double DEFAULT NULL,
  `wide` double DEFAULT NULL,
  `height` double DEFAULT NULL,
  `weight` double DEFAULT NULL,
  `tax_id` bigint unsigned DEFAULT NULL,
  `views` bigint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `created_by_id` bigint unsigned DEFAULT '0',
  `created_by_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Botble\\ACL\\Models\\User',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'physical',
  `barcode` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cost_per_item` double DEFAULT NULL,
  `generate_license_code` tinyint(1) NOT NULL DEFAULT '0',
  `license_code_type` enum('auto_generate','pick_from_list') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'auto_generate',
  `minimum_order_quantity` int unsigned DEFAULT '0',
  `maximum_order_quantity` int unsigned DEFAULT '0',
  `notify_attachment_updated` tinyint(1) NOT NULL DEFAULT '0',
  `specification_table_id` bigint unsigned DEFAULT NULL,
  `store_id` bigint unsigned DEFAULT NULL,
  `approved_by` bigint unsigned DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `ec_products_brand_id_status_is_variation_created_at_index` (`brand_id`,`status`,`is_variation`,`created_at`),
  KEY `sale_type_index` (`sale_type`),
  KEY `start_date_index` (`start_date`),
  KEY `end_date_index` (`end_date`),
  KEY `sale_price_index` (`sale_price`),
  KEY `is_variation_index` (`is_variation`),
  KEY `ec_products_sku_index` (`sku`),
  KEY `idx_products_export` (`id`,`is_variation`),
  KEY `idx_variation_name_id` (`is_variation`,`name`,`id`),
  KEY `ec_products_variations_count_index` (`variations_count`),
  KEY `ec_products_reviews_count_index` (`reviews_count`),
  KEY `ec_products_reviews_avg_index` (`reviews_avg`),
  KEY `ec_products_slug_index` (`slug`),
  KEY `idx_products_status_variation` (`status`,`is_variation`,`id`),
  KEY `idx_products_price_sale` (`sale_type`,`sale_price`,`price`),
  KEY `idx_products_order_created` (`order`,`created_at`),
  KEY `idx_products_stock` (`with_storehouse_management`,`stock_status`,`quantity`),
  KEY `idx_store_variation_status` (`store_id`,`is_variation`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_products`
--

LOCK TABLES `ec_products` WRITE;
/*!40000 ALTER TABLE `ec_products` DISABLE KEYS */;
INSERT INTO `ec_products` VALUES (1,'Dual Camera 20MP','dual-camera-20mp','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/1.jpg\"]',NULL,'85-106-A1',0,19,0,1,'in_stock',1,5,0,3,6,3.33,0,80.25,NULL,NULL,NULL,18,11,15,762,NULL,84900,'2025-09-11 01:46:13','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','8090408051416',NULL,0,'auto_generate',0,0,0,2,7,0),(2,'Smart Watches','smart-watches','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/2.jpg\",\"products\\/2-1.jpg\",\"products\\/2-2.jpg\",\"products\\/2-3.jpg\"]',NULL,'CL-126-A1',0,17,0,1,'in_stock',1,5,0,5,5,2.40,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,182076,'2025-09-11 01:46:13','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','3538407237769',NULL,0,'auto_generate',0,0,0,2,7,0),(3,'Beat Headphone','beat-headphone','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/3.jpg\"]',NULL,'8J-115-A1',0,18,0,1,'in_stock',1,1,0,2,8,2.88,0,20,NULL,NULL,NULL,20,20,17,826,NULL,16375,'2025-09-11 01:46:13','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','9045561053035',NULL,0,'auto_generate',0,0,0,1,5,0),(4,'Red &amp; Black Headphone (Digital)','red-black-headphone','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/4.jpg\",\"products\\/4-1.jpg\",\"products\\/4-2.jpg\",\"products\\/4-3.jpg\"]',NULL,'0L-102',0,16,0,1,'in_stock',1,3,0,0,7,2.43,0,545,374,NULL,NULL,16,11,11,706,NULL,106043,'2025-09-11 01:46:14','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'digital','4844054320983',NULL,0,'auto_generate',0,0,0,2,8,0),(5,'Smart Watch External','smart-watch-external','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/5.jpg\",\"products\\/5-1.jpg\",\"products\\/5-2.jpg\",\"products\\/5-3.jpg\"]',NULL,'LH-160-A1',0,13,0,1,'in_stock',1,1,0,4,7,3.57,0,839,NULL,NULL,NULL,12,20,17,805,NULL,154112,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3044434710576',NULL,0,'auto_generate',0,0,0,1,8,0),(6,'Nikon HD camera','nikon-hd-camera','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/6.jpg\"]',NULL,'2Q-162-A1',0,15,0,1,'in_stock',1,4,0,2,9,2.67,0,416,NULL,NULL,NULL,13,19,20,692,NULL,169747,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3022089721864',NULL,0,'auto_generate',0,0,0,2,2,0),(7,'Audio Equipment','audio-equipment','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/7.jpg\"]',NULL,'PT-194',0,10,0,1,'in_stock',1,4,0,0,8,3.50,0,558,5,NULL,NULL,10,19,12,790,NULL,78392,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','5745912455733',NULL,0,'auto_generate',0,0,0,1,4,0),(8,'Smart Televisions (Digital)','smart-televisions','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/8.jpg\",\"products\\/8-1.jpg\",\"products\\/8-2.jpg\",\"products\\/8-3.jpg\"]',NULL,'XR-154-A1',0,17,0,1,'in_stock',1,2,0,1,10,2.60,0,1117,804.24,NULL,NULL,13,10,15,687,NULL,81795,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','3343281095585',NULL,0,'auto_generate',0,0,0,2,2,0),(9,'Samsung Smart Phone','samsung-smart-phone','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/9.jpg\",\"products\\/9-1.jpg\",\"products\\/9-2.jpg\"]',NULL,'EQ-112-A1',0,13,0,1,'in_stock',1,2,0,1,5,2.40,0,569,NULL,NULL,NULL,12,20,15,868,NULL,126479,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3549302476887',NULL,0,'auto_generate',0,0,0,1,1,0),(10,'Herschel Leather Duffle Bag In Brown Color','herschel-leather-duffle-bag-in-brown-color','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/10.jpg\",\"products\\/10-1.jpg\",\"products\\/10-2.jpg\"]',NULL,'ZH-154',0,18,0,1,'in_stock',0,3,0,0,6,2.00,0,1219,848,NULL,NULL,10,15,19,529,NULL,85993,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3214081729911',NULL,0,'auto_generate',0,0,0,1,3,0),(11,'Xbox One Wireless Controller Black Color','xbox-one-wireless-controller-black-color','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/11.jpg\",\"products\\/11-1.jpg\",\"products\\/11-2.jpg\",\"products\\/11-3.jpg\"]',NULL,'RP-148',0,20,0,1,'in_stock',0,4,0,0,8,3.50,0,1170,611,NULL,NULL,13,13,16,635,NULL,93925,'2025-09-11 01:46:14','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','7024246894516',NULL,0,'auto_generate',0,0,0,1,2,0),(12,'EPSION Plaster Printer (Digital)','epsion-plaster-printer','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/12.jpg\",\"products\\/12-1.jpg\",\"products\\/12-2.jpg\",\"products\\/12-3.jpg\"]',NULL,'JD-179-A1',0,19,0,1,'in_stock',0,1,0,4,7,3.57,0,552,391.92,NULL,NULL,15,12,15,739,NULL,53173,'2025-09-11 01:46:15','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','3752299226131',NULL,0,'auto_generate',0,0,0,2,1,0),(13,'Sound Intone I65 Earphone White Version','sound-intone-i65-earphone-white-version','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/13.jpg\",\"products\\/13-1.jpg\"]',NULL,'A9-182',0,18,0,1,'in_stock',1,4,0,0,6,3.33,0,582,197,NULL,NULL,10,12,14,715,NULL,173116,'2025-09-11 01:46:15','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','5519541107208',NULL,0,'auto_generate',0,0,0,1,1,0),(14,'B&amp;O Play Mini Bluetooth Speaker','bo-play-mini-bluetooth-speaker','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/14.jpg\"]',NULL,'AG-194-A1',0,20,0,1,'in_stock',1,5,0,2,7,2.14,0,545,NULL,NULL,NULL,11,19,17,535,NULL,47792,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','1916092442729',NULL,0,'auto_generate',0,0,0,2,5,0),(15,'Apple MacBook Air Retina 13.3-Inch Laptop','apple-macbook-air-retina-133-inch-laptop','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/15.jpg\",\"products\\/15-1.jpg\"]',NULL,'SQ-142-A1',0,12,0,1,'in_stock',0,2,0,4,8,2.88,0,566,NULL,NULL,NULL,16,11,20,638,NULL,43217,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','2947379830730',NULL,0,'auto_generate',0,0,0,2,4,0),(16,'Apple MacBook Air Retina 12-Inch Laptop (Digital)','apple-macbook-air-retina-12-inch-laptop','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/16.jpg\"]',NULL,'IE-106',0,13,0,1,'in_stock',0,5,0,0,8,3.63,0,509,307,NULL,NULL,13,11,14,644,NULL,51588,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'digital','3883770906065',NULL,0,'auto_generate',0,0,0,1,2,0),(17,'Samsung Gear VR Virtual Reality Headset','samsung-gear-vr-virtual-reality-headset','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/17.jpg\",\"products\\/17-1.jpg\",\"products\\/17-2.jpg\",\"products\\/17-3.jpg\"]',NULL,'TO-124-A1',0,12,0,1,'in_stock',0,3,0,2,8,4.00,0,524,NULL,NULL,NULL,12,11,11,671,NULL,15387,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','2648939737291',NULL,0,'auto_generate',0,0,0,1,1,0),(18,'Aveeno Moisturizing Body Shower 450ml','aveeno-moisturizing-body-shower-450ml','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/18.jpg\",\"products\\/18-1.jpg\",\"products\\/18-2.jpg\",\"products\\/18-3.jpg\"]',NULL,'3I-127',0,11,0,1,'in_stock',1,2,0,0,6,3.83,0,1008,695,NULL,NULL,18,14,12,753,NULL,63817,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','1816289248809',NULL,0,'auto_generate',0,0,0,1,6,0),(19,'NYX Beauty Couton Pallete Makeup 12','nyx-beauty-couton-pallete-makeup-12','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/19.jpg\",\"products\\/19-1.jpg\",\"products\\/19-2.jpg\",\"products\\/19-3.jpg\"]',NULL,'84-190-A1',0,18,0,1,'in_stock',0,4,0,3,6,3.50,0,1105,NULL,NULL,NULL,14,20,20,689,NULL,179097,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','9456245184974',NULL,0,'auto_generate',0,0,0,1,8,0),(20,'NYX Beauty Couton Pallete Makeup 12 (Digital)','nyx-beauty-couton-pallete-makeup-12','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/20.jpg\",\"products\\/20-1.jpg\",\"products\\/20-2.jpg\",\"products\\/20-3.jpg\"]',NULL,'UC-179',0,15,0,1,'in_stock',1,4,0,0,9,3.44,0,850,474,NULL,NULL,16,10,14,542,NULL,68251,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'digital','9000527807923',NULL,0,'auto_generate',0,0,0,2,8,0),(21,'MVMTH Classical Leather Watch In Black','mvmth-classical-leather-watch-in-black','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/21.jpg\",\"products\\/21-1.jpg\",\"products\\/21-2.jpg\"]',NULL,'NY-175',0,16,0,1,'in_stock',0,5,0,0,10,2.70,0,916,425,NULL,NULL,20,11,19,754,NULL,35102,'2025-09-11 01:46:15','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','5600792918207',NULL,0,'auto_generate',0,0,0,1,2,0),(22,'Baxter Care Hair Kit For Bearded Mens','baxter-care-hair-kit-for-bearded-mens','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/22.jpg\",\"products\\/22-1.jpg\",\"products\\/22-2.jpg\",\"products\\/22-3.jpg\"]',NULL,'QS-154-A1',0,12,0,1,'in_stock',1,4,0,3,10,2.90,0,510,NULL,NULL,NULL,19,13,18,554,NULL,199177,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','1326237311085',NULL,0,'auto_generate',0,0,0,2,4,0),(23,'Ciate Palemore Lipstick Bold Red Color','ciate-palemore-lipstick-bold-red-color','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/23.jpg\",\"products\\/23-1.jpg\",\"products\\/23-2.jpg\",\"products\\/23-3.jpg\"]',NULL,'QP-141',0,14,0,1,'in_stock',0,2,0,0,8,3.25,0,972,270,NULL,NULL,11,17,15,814,NULL,114849,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','3656141256881',NULL,0,'auto_generate',0,0,0,2,4,0),(24,'Vimto Squash Remix Apple 1.5 Litres (Digital)','vimto-squash-remix-apple-15-litres','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/24.jpg\",\"products\\/24-1.jpg\",\"products\\/24-2.jpg\"]',NULL,'CD-172',0,19,0,1,'in_stock',0,5,0,0,9,3.00,0,591,531,NULL,NULL,13,13,20,593,NULL,40663,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'digital','7722877542966',NULL,0,'auto_generate',0,0,0,1,2,0),(25,'Crock Pot Slow Cooker','crock-pot-slow-cooker','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/25.jpg\",\"products\\/25-1.jpg\",\"products\\/25-2.jpg\"]',NULL,'GJ-166',0,15,0,1,'in_stock',0,3,0,0,5,2.20,0,870,740,NULL,NULL,12,14,13,808,NULL,75397,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','5682114330539',NULL,0,'auto_generate',0,0,0,2,2,0),(26,'Taylors of Harrogate Yorkshire Coffee','taylors-of-harrogate-yorkshire-coffee','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/26.jpg\",\"products\\/26-1.jpg\"]',NULL,'SI-128',0,19,0,1,'in_stock',0,4,0,0,10,3.00,0,959,755,NULL,NULL,19,12,11,757,NULL,83236,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','2334364138504',NULL,0,'auto_generate',0,0,0,1,7,0),(27,'Soft Mochi &amp; Galeto Ice Cream','soft-mochi-galeto-ice-cream','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/27.jpg\",\"products\\/27-1.jpg\"]',NULL,'XQ-192-A1',0,19,0,1,'in_stock',1,3,0,3,6,2.67,0,585,NULL,NULL,NULL,10,17,20,840,NULL,97359,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','0934743105732',NULL,0,'auto_generate',0,0,0,1,7,0),(28,'Naked Noodle Egg Noodles Singapore (Digital)','naked-noodle-egg-noodles-singapore','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/28.jpg\",\"products\\/28-1.jpg\",\"products\\/28-2.jpg\"]',NULL,'MM-189',0,10,0,1,'in_stock',0,2,0,0,6,2.67,0,957,289,NULL,NULL,15,20,11,582,NULL,165594,'2025-09-11 01:46:16','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'digital','8049577536194',NULL,0,'auto_generate',0,0,0,1,4,0),(29,'Saute Pan Silver','saute-pan-silver','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/29.jpg\",\"products\\/29-1.jpg\",\"products\\/29-2.jpg\"]',NULL,'KL-124',0,17,0,1,'in_stock',1,1,0,0,8,3.00,0,988,419,NULL,NULL,10,18,14,668,NULL,87636,'2025-09-11 01:46:16','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','6759439517118',NULL,0,'auto_generate',0,0,0,2,8,0),(30,'Bar S – Classic Bun Length Franks','bar-s-classic-bun-length-franks','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/30.jpg\",\"products\\/30-1.jpg\",\"products\\/30-2.jpg\"]',NULL,'S0-176-A1',0,12,0,1,'in_stock',0,5,0,4,10,3.90,0,1153,NULL,NULL,NULL,12,14,12,770,NULL,65067,'2025-09-11 01:46:16','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','1454071425955',NULL,0,'auto_generate',0,0,0,1,2,0),(31,'Broccoli Crowns','broccoli-crowns','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/31.jpg\",\"products\\/31-1.jpg\"]',NULL,'O5-133-A1',0,11,0,1,'in_stock',0,3,0,1,5,2.60,0,620,NULL,NULL,NULL,17,15,10,743,NULL,150925,'2025-09-11 01:46:16','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','1918785916340',NULL,0,'auto_generate',0,0,0,1,5,0),(32,'Slimming World Vegan Mac Greens (Digital)','slimming-world-vegan-mac-greens','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/32.jpg\",\"products\\/32-1.jpg\",\"products\\/32-2.jpg\"]',NULL,'H4-172',0,15,0,1,'in_stock',0,5,0,0,8,3.00,0,654,619,NULL,NULL,17,13,17,705,NULL,106749,'2025-09-11 01:46:17','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'digital','8218283778352',NULL,0,'auto_generate',0,0,0,1,6,0),(33,'Häagen-Dazs Salted Caramel','haagen-dazs-salted-caramel','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/33.jpg\",\"products\\/33-1.jpg\",\"products\\/33-2.jpg\"]',NULL,'NF-146',0,15,0,1,'in_stock',1,5,0,0,8,2.88,0,1072,610,NULL,NULL,17,15,11,511,NULL,151329,'2025-09-11 01:46:17','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','7639254290122',NULL,0,'auto_generate',0,0,0,2,3,0),(34,'Iceland 3 Solo Exotic Burst','iceland-3-solo-exotic-burst','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/34.jpg\",\"products\\/34-1.jpg\"]',NULL,'AD-161-A1',0,12,0,1,'in_stock',0,3,0,1,7,2.43,0,592,NULL,NULL,NULL,17,18,14,790,NULL,44458,'2025-09-11 01:46:17','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','7913237701059',NULL,0,'auto_generate',0,0,0,2,5,0),(35,'Extreme Budweiser Light Can','extreme-budweiser-light-can','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/35.jpg\",\"products\\/35-1.jpg\"]',NULL,'O1-190',0,11,0,1,'in_stock',0,2,0,0,7,2.71,0,1061,914,NULL,NULL,19,14,14,644,NULL,134948,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','4069023106447',NULL,0,'auto_generate',0,0,0,2,1,0),(36,'Iceland Macaroni Cheese Traybake (Digital)','iceland-macaroni-cheese-traybake','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/36.jpg\",\"products\\/36-1.jpg\",\"products\\/36-2.jpg\"]',NULL,'PA-109',0,20,0,1,'in_stock',0,5,0,0,10,2.60,0,1076,644,NULL,NULL,19,17,17,570,NULL,33364,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'digital','6746698212595',NULL,0,'auto_generate',0,0,0,1,6,0),(37,'Dolmio Bolognese Pasta Sauce','dolmio-bolognese-pasta-sauce','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/37.jpg\",\"products\\/37-1.jpg\",\"products\\/37-2.jpg\"]',NULL,'EA-137-A1',0,10,0,1,'in_stock',1,5,0,3,8,3.13,0,980,NULL,NULL,NULL,16,19,20,638,NULL,83161,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','9627380707773',NULL,0,'auto_generate',0,0,0,1,8,0),(38,'Sitema BakeIT Plastic Box','sitema-bakeit-plastic-box','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/38.jpg\",\"products\\/38-1.jpg\",\"products\\/38-2.jpg\",\"products\\/38-3.jpg\"]',NULL,'ZY-196',0,15,0,1,'in_stock',1,1,0,0,7,2.29,0,1036,833,NULL,NULL,20,20,18,788,NULL,199046,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','8457420695613',NULL,0,'auto_generate',0,0,0,1,4,0),(39,'Wayfair Basics Dinner Plate Storage','wayfair-basics-dinner-plate-storage','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/39.jpg\",\"products\\/39-1.jpg\",\"products\\/39-2.jpg\"]',NULL,'GK-185-A1',0,16,0,1,'in_stock',1,4,0,1,10,3.00,0,1289,NULL,NULL,NULL,15,12,17,891,NULL,75934,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','6862081819476',NULL,0,'auto_generate',0,0,0,2,2,0),(40,'Miko The Panda Water Bottle (Digital)','miko-the-panda-water-bottle','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/40.jpg\",\"products\\/40-1.jpg\"]',NULL,'RK-104-A1',0,17,0,1,'in_stock',0,1,0,2,7,2.71,0,736,537.28,NULL,NULL,18,11,17,584,NULL,189032,'2025-09-11 01:46:17','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'digital','5693003128991',NULL,0,'auto_generate',0,0,0,1,6,0),(41,'Sesame Seed Bread','sesame-seed-bread','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/41.jpg\",\"products\\/41-1.jpg\",\"products\\/41-2.jpg\"]',NULL,'DP-179-A1',0,16,0,1,'in_stock',1,5,0,1,7,2.71,0,938,NULL,NULL,NULL,17,16,16,577,NULL,48321,'2025-09-11 01:46:18','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','4986944377414',NULL,0,'auto_generate',0,0,0,1,7,0),(42,'Morrisons The Best Beef','morrisons-the-best-beef','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/42.jpg\",\"products\\/42-1.jpg\",\"products\\/42-2.jpg\"]',NULL,'GM-194',0,10,0,1,'in_stock',0,4,0,0,8,3.38,0,919,807,NULL,NULL,16,19,14,840,NULL,183714,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','1146552509338',NULL,0,'auto_generate',0,0,0,1,6,0),(43,'Avocado, Hass Large','avocado-hass-large','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/43.jpg\",\"products\\/43-1.jpg\",\"products\\/43-2.jpg\"]',NULL,'GS-154-A1',0,14,0,1,'in_stock',1,4,0,4,9,3.22,0,1176,NULL,NULL,NULL,12,10,20,526,NULL,188070,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','2499465083617',NULL,0,'auto_generate',0,0,0,1,5,0),(44,'Italia Beef Lasagne (Digital)','italia-beef-lasagne','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/44.jpg\",\"products\\/44-1.jpg\",\"products\\/44-2.jpg\"]',NULL,'0S-181-A1',0,13,0,1,'in_stock',0,5,0,3,7,2.86,0,1098,812.52,NULL,NULL,14,16,12,570,NULL,23892,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','4585304713567',NULL,0,'auto_generate',0,0,0,1,5,0),(45,'Maxwell House Classic Roast Mocha','maxwell-house-classic-roast-mocha','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/45.jpg\",\"products\\/45-1.jpg\"]',NULL,'26-154-A1',0,20,0,1,'in_stock',0,1,0,5,6,2.50,0,786,NULL,NULL,NULL,18,13,17,587,NULL,45332,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','6349341275710',NULL,0,'auto_generate',0,0,0,2,1,0),(46,'Bottled Pure Water 500ml','bottled-pure-water-500ml','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/46.jpg\",\"products\\/46-1.jpg\"]',NULL,'BQ-141',0,10,0,1,'in_stock',0,4,0,0,8,3.00,0,706,351,NULL,NULL,13,20,10,728,NULL,21989,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','9532785201095',NULL,0,'auto_generate',0,0,0,1,7,0),(47,'Famart Farmhouse Soft White','famart-farmhouse-soft-white','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/47.jpg\",\"products\\/47-1.jpg\"]',NULL,'5L-134',0,15,0,1,'in_stock',0,1,0,0,8,2.63,0,905,13,NULL,NULL,12,11,19,760,NULL,186627,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','0767963534705',NULL,0,'auto_generate',0,0,0,1,1,0),(48,'Coca-Cola Original Taste (Digital)','coca-cola-original-taste','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/48.jpg\",\"products\\/48-1.jpg\",\"products\\/48-2.jpg\"]',NULL,'TM-133-A1',0,18,0,1,'in_stock',1,4,0,1,9,2.78,0,780,624,NULL,NULL,17,13,16,535,NULL,189483,'2025-09-11 01:46:18','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','8711198174658',NULL,0,'auto_generate',0,0,0,2,6,0),(49,'Casillero Diablo Cabernet Sauvignon','casillero-diablo-cabernet-sauvignon','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/49.jpg\",\"products\\/49-1.jpg\",\"products\\/49-2.jpg\"]',NULL,'2H-135-A1',0,18,0,1,'in_stock',0,4,0,2,9,3.00,0,999,NULL,NULL,NULL,10,15,15,725,NULL,30013,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','4904415635108',NULL,0,'auto_generate',0,0,0,1,7,0),(50,'Arla Organic Free Range Milk','arla-organic-free-range-milk','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/50.jpg\",\"products\\/50-1.jpg\"]',NULL,'F4-150',0,18,0,1,'in_stock',1,3,0,0,10,3.60,0,1045,593,NULL,NULL,10,19,11,732,NULL,90044,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','8002360638055',NULL,0,'auto_generate',0,0,0,2,1,0),(51,'Aptamil Follow On Baby Milk','aptamil-follow-on-baby-milk','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/51.jpg\"]',NULL,'3S-183-A1',0,11,0,1,'in_stock',0,1,0,1,7,2.86,0,1274,NULL,NULL,NULL,18,12,18,658,NULL,188050,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','8952287181038',NULL,0,'auto_generate',0,0,0,1,6,0),(52,'Cuisinart Chef’S Classic Hard-Anodized (Digital)','cuisinart-chefs-classic-hard-anodized','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/52.jpg\",\"products\\/52-1.jpg\",\"products\\/52-2.jpg\"]',NULL,'GM-140',0,15,0,1,'in_stock',0,3,0,0,7,2.86,0,1186,801,NULL,NULL,13,19,14,806,NULL,107654,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'digital','5728051731845',NULL,0,'auto_generate',0,0,0,1,2,0),(53,'Corn, Yellow Sweet','corn-yellow-sweet','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/53.jpg\",\"products\\/53-1.jpg\"]',NULL,'QW-119',0,11,0,1,'in_stock',0,1,0,0,8,3.25,0,933,692,NULL,NULL,18,15,10,678,NULL,80139,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','3507203866411',NULL,0,'auto_generate',0,0,0,2,6,0),(54,'Hobnobs The Nobbly Biscuit','hobnobs-the-nobbly-biscuit','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/54.jpg\",\"products\\/54-1.jpg\"]',NULL,'8W-161',0,15,0,1,'in_stock',0,3,0,0,7,2.57,0,1058,425,NULL,NULL,18,12,13,846,NULL,88115,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','0015924768738',NULL,0,'auto_generate',0,0,0,2,4,0),(55,'Honest Organic Still Lemonade','honest-organic-still-lemonade','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/55.jpg\",\"products\\/55-1.jpg\",\"products\\/55-2.jpg\"]',NULL,'EE-111-A1',0,10,0,1,'in_stock',0,4,0,4,7,2.14,0,648,NULL,NULL,NULL,18,16,13,759,NULL,94330,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','2585215066280',NULL,0,'auto_generate',0,0,0,2,5,0),(56,'Ice Beck’s Beer 350ml x 24 Pieces (Digital)','ice-becks-beer-350ml-x-24-pieces','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/56.jpg\",\"products\\/56-1.jpg\",\"products\\/56-2.jpg\"]',NULL,'HM-107',0,11,0,1,'in_stock',0,3,0,0,9,2.22,0,1197,591,NULL,NULL,15,20,15,596,NULL,37267,'2025-09-11 01:46:19','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'digital','0164295491643',NULL,0,'auto_generate',0,0,0,2,4,0),(57,'Iceland 6 Hot Cross Buns','iceland-6-hot-cross-buns','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/57.jpg\",\"products\\/57-1.jpg\"]',NULL,'PK-160-A1',0,13,0,1,'in_stock',1,3,0,2,10,3.80,0,627,NULL,NULL,NULL,18,13,16,738,NULL,176274,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','2992559004623',NULL,0,'auto_generate',0,0,0,1,6,0),(58,'Iceland Luxury 4 Panini Rolls','iceland-luxury-4-panini-rolls','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/58.jpg\",\"products\\/58-1.jpg\",\"products\\/58-2.jpg\"]',NULL,'YV-169',0,10,0,1,'in_stock',1,3,0,0,8,3.50,0,1109,766,NULL,NULL,20,10,12,783,NULL,16763,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','6092266649622',NULL,0,'auto_generate',0,0,0,1,2,0),(59,'Iceland Soft Scoop Vanilla','iceland-soft-scoop-vanilla','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/59.jpg\",\"products\\/59-1.jpg\",\"products\\/59-2.jpg\",\"products\\/59-3.jpg\"]',NULL,'GB-191',0,19,0,1,'in_stock',1,2,0,0,8,2.13,0,1009,936,NULL,NULL,13,20,18,832,NULL,197606,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','8210205027760',NULL,0,'auto_generate',0,0,0,1,4,0),(60,'Iceland Spaghetti Bolognese (Digital)','iceland-spaghetti-bolognese','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/60.jpg\",\"products\\/60-1.jpg\",\"products\\/60-2.jpg\"]',NULL,'Z9-135-A1',0,13,0,1,'in_stock',0,2,0,3,7,2.86,0,1275,994.5,NULL,NULL,16,16,10,809,NULL,166356,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'digital','0305049779840',NULL,0,'auto_generate',0,0,0,2,7,0),(61,'Kellogg’s Coco Pops Cereal','kelloggs-coco-pops-cereal','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/61.jpg\",\"products\\/61-1.jpg\"]',NULL,'KI-192',0,12,0,1,'in_stock',0,1,0,0,8,3.38,0,1124,509,NULL,NULL,11,10,20,818,NULL,171826,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','8725513492365',NULL,0,'auto_generate',0,0,0,1,4,0),(62,'Kit Kat Chunky Milk Chocolate','kit-kat-chunky-milk-chocolate','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/62.jpg\",\"products\\/62-1.jpg\"]',NULL,'UT-134',0,13,0,1,'in_stock',1,4,0,0,9,3.33,0,526,191,NULL,NULL,17,10,16,594,NULL,22744,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','2324553640161',NULL,0,'auto_generate',0,0,0,2,1,0),(63,'Large Green Bell Pepper','large-green-bell-pepper','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/63.jpg\",\"products\\/63-1.jpg\"]',NULL,'C4-136-A1',0,15,0,1,'in_stock',0,2,0,2,9,2.22,0,1188,NULL,NULL,NULL,14,16,13,500,NULL,124219,'2025-09-11 01:46:20','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','9380816161982',NULL,0,'auto_generate',0,0,0,2,3,0),(64,'Pice 94w Beasley Journal (Digital)','pice-94w-beasley-journal','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/64.jpg\",\"products\\/64-1.jpg\"]',NULL,'7C-114',0,15,0,1,'in_stock',1,1,0,0,7,3.00,0,901,105,NULL,NULL,13,16,13,726,NULL,167444,'2025-09-11 01:46:21','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'digital','8681908582281',NULL,0,'auto_generate',0,0,0,1,4,0),(65,'Province Piece Glass Drinking Glass','province-piece-glass-drinking-glass','<ul><li> Unrestrained and portable active stereo speaker</li>\n            <li> Free from the confines of wires and chords</li>\n            <li> 20 hours of portable capabilities</li>\n            <li> Double-ended Coil Cord with 3.5mm Stereo Plugs Included</li>\n            <li> 3/4″ Dome Tweeters: 2X and 4″ Woofer: 1X</li></ul>','<p>Short Hooded Coat features a straight body, large pockets with button flaps, ventilation air holes, and a string detail along the hemline. The style is completed with a drawstring hood, featuring Rains’ signature built-in cap. Made from waterproof, matte PU, this lightweight unisex rain jacket is an ode to nostalgia through its classic silhouette and utilitarian design details.</p>\n                                <p>- Casual unisex fit</p>\n\n                                <p>- 64% polyester, 36% polyurethane</p>\n\n                                <p>- Water column pressure: 4000 mm</p>\n\n                                <p>- Model is 187cm tall and wearing a size S / M</p>\n\n                                <p>- Unisex fit</p>\n\n                                <p>- Drawstring hood with built-in cap</p>\n\n                                <p>- Front placket with snap buttons</p>\n\n                                <p>- Ventilation under armpit</p>\n\n                                <p>- Adjustable cuffs</p>\n\n                                <p>- Double welted front pockets</p>\n\n                                <p>- Adjustable elastic string at hempen</p>\n\n                                <p>- Ultrasonically welded seams</p>\n\n                                <p>This is a unisex item, please check our clothing &amp; footwear sizing guide for specific Rains jacket sizing information. RAINS comes from the rainy nation of Denmark at the edge of the European continent, close to the ocean and with prevailing westerly winds; all factors that contribute to an average of 121 rain days each year. Arising from these rainy weather conditions comes the attitude that a quick rain shower may be beautiful, as well as moody- but first and foremost requires the right outfit. Rains focus on the whole experience of going outside on rainy days, issuing an invitation to explore even in the most mercurial weather.</p>','published','[\"products\\/65.jpg\",\"products\\/65-1.jpg\",\"products\\/65-2.jpg\"]',NULL,'LE-105-A1',0,20,0,1,'in_stock',1,3,0,2,9,3.22,0,708,NULL,NULL,NULL,12,14,18,885,NULL,182297,'2025-09-11 01:46:21','2025-09-11 01:47:09',0,'Botble\\ACL\\Models\\User',NULL,'physical','2448781555995',NULL,0,'auto_generate',0,0,0,2,1,0),(66,'Dual Camera 20MP',NULL,NULL,NULL,'published','[\"products\\/1.jpg\"]',NULL,'85-106-A1',0,19,0,1,'in_stock',0,5,1,0,0,0.00,0,80.25,NULL,NULL,NULL,18,11,15,762,NULL,0,'2025-09-11 01:46:21','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7946546989924',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(67,'Dual Camera 20MP',NULL,NULL,NULL,'published','[\"products\\/1.jpg\"]',NULL,'85-106-A1-A2',0,19,0,1,'in_stock',0,5,1,0,0,0.00,0,80.25,NULL,NULL,NULL,18,11,15,762,NULL,0,'2025-09-11 01:46:21','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','8479559674833',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(68,'Dual Camera 20MP',NULL,NULL,NULL,'published','[\"products\\/1.jpg\"]',NULL,'85-106-A1-A3',0,19,0,1,'in_stock',0,5,1,0,0,0.00,0,80.25,NULL,NULL,NULL,18,11,15,762,NULL,0,'2025-09-11 01:46:21','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','3626204830365',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(69,'Smart Watches',NULL,NULL,NULL,'published','[\"products\\/2-1.jpg\"]',NULL,'CL-126-A1',0,17,0,1,'in_stock',0,5,1,0,0,0.00,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,0,'2025-09-11 01:46:21','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7868537854618',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(70,'Smart Watches',NULL,NULL,NULL,'published','[\"products\\/2-2.jpg\"]',NULL,'CL-126-A1-A2',0,17,0,1,'in_stock',0,5,1,0,0,0.00,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,0,'2025-09-11 01:46:21','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7858726611986',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(71,'Smart Watches',NULL,NULL,NULL,'published','[\"products\\/2-3.jpg\"]',NULL,'CL-126-A1-A3',0,17,0,1,'in_stock',0,5,1,0,0,0.00,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7152168915801',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(72,'Smart Watches',NULL,NULL,NULL,'published','[\"products\\/2.jpg\"]',NULL,'CL-126-A1-A4',0,17,0,1,'in_stock',0,5,1,0,0,0.00,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7202473983436',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(73,'Smart Watches',NULL,NULL,NULL,'published','[\"products\\/2.jpg\"]',NULL,'CL-126-A1-A5',0,17,0,1,'in_stock',0,5,1,0,0,0.00,0,40.5,NULL,NULL,NULL,15,18,10,593,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7712018832810',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(74,'Beat Headphone',NULL,NULL,NULL,'published','[\"products\\/3.jpg\"]',NULL,'8J-115-A1',0,18,0,1,'in_stock',0,1,1,0,0,0.00,0,20,NULL,NULL,NULL,20,20,17,826,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','7327307582095',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(75,'Beat Headphone',NULL,NULL,NULL,'published','[\"products\\/3.jpg\"]',NULL,'8J-115-A1-A2',0,18,0,1,'in_stock',0,1,1,0,0,0.00,0,20,NULL,NULL,NULL,20,20,17,826,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:00',0,'Botble\\ACL\\Models\\User',NULL,'physical','0182959959070',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(76,'Smart Watch External',NULL,NULL,NULL,'published','[\"products\\/5-1.jpg\"]',NULL,'LH-160-A1',0,13,0,1,'in_stock',0,1,1,0,0,0.00,0,839,NULL,NULL,NULL,12,20,17,805,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','5624680576331',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(77,'Smart Watch External',NULL,NULL,NULL,'published','[\"products\\/5-2.jpg\"]',NULL,'LH-160-A1-A2',0,13,0,1,'in_stock',0,1,1,0,0,0.00,0,839,NULL,NULL,NULL,12,20,17,805,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3868999409436',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(78,'Smart Watch External',NULL,NULL,NULL,'published','[\"products\\/5-3.jpg\"]',NULL,'LH-160-A1-A3',0,13,0,1,'in_stock',0,1,1,0,0,0.00,0,839,NULL,NULL,NULL,12,20,17,805,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','2728839224074',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(79,'Smart Watch External',NULL,NULL,NULL,'published','[\"products\\/5.jpg\"]',NULL,'LH-160-A1-A4',0,13,0,1,'in_stock',0,1,1,0,0,0.00,0,839,NULL,NULL,NULL,12,20,17,805,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','3684291335835',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(80,'Nikon HD camera',NULL,NULL,NULL,'published','[\"products\\/6.jpg\"]',NULL,'2Q-162-A1',0,15,0,1,'in_stock',0,4,1,0,0,0.00,0,416,NULL,NULL,NULL,13,19,20,692,NULL,0,'2025-09-11 01:46:22','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','8645756986868',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(81,'Nikon HD camera',NULL,NULL,NULL,'published','[\"products\\/6.jpg\"]',NULL,'2Q-162-A1-A2',0,15,0,1,'in_stock',0,4,1,0,0,0.00,0,416,NULL,NULL,NULL,13,19,20,692,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','2759325486959',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(82,'Smart Televisions (Digital)',NULL,NULL,NULL,'published','[\"products\\/8-1.jpg\"]',NULL,'XR-154-A1',0,17,0,1,'in_stock',0,2,1,0,0,0.00,0,1117,804.24,NULL,NULL,13,10,15,687,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','3352400914553',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(83,'Samsung Smart Phone',NULL,NULL,NULL,'published','[\"products\\/9-1.jpg\"]',NULL,'EQ-112-A1',0,13,0,1,'in_stock',0,2,1,0,0,0.00,0,569,NULL,NULL,NULL,12,20,15,868,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'physical','1110916303122',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(84,'EPSION Plaster Printer (Digital)',NULL,NULL,NULL,'published','[\"products\\/12-1.jpg\"]',NULL,'JD-179-A1',0,19,0,1,'in_stock',0,1,1,0,0,0.00,0,552,391.92,NULL,NULL,15,12,15,739,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','9565500393850',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(85,'EPSION Plaster Printer (Digital)',NULL,NULL,NULL,'published','[\"products\\/12-2.jpg\"]',NULL,'JD-179-A1-A2',0,19,0,1,'in_stock',0,1,1,0,0,0.00,0,552,391.92,NULL,NULL,15,12,15,739,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','4043800818341',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(86,'EPSION Plaster Printer (Digital)',NULL,NULL,NULL,'published','[\"products\\/12-3.jpg\"]',NULL,'JD-179-A1-A3',0,19,0,1,'in_stock',0,1,1,0,0,0.00,0,552,408.48,NULL,NULL,15,12,15,739,NULL,0,'2025-09-11 01:46:23','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','4403993157564',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(87,'EPSION Plaster Printer (Digital)',NULL,NULL,NULL,'published','[\"products\\/12.jpg\"]',NULL,'JD-179-A1-A4',0,19,0,1,'in_stock',0,1,1,0,0,0.00,0,552,485.76,NULL,NULL,15,12,15,739,NULL,0,'2025-09-11 01:46:24','2025-09-11 01:47:01',0,'Botble\\ACL\\Models\\User',NULL,'digital','8879635128357',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(88,'B&O Play Mini Bluetooth Speaker',NULL,NULL,NULL,'published','[\"products\\/14.jpg\"]',NULL,'AG-194-A1',0,20,0,1,'in_stock',0,5,1,0,0,0.00,0,545,NULL,NULL,NULL,11,19,17,535,NULL,0,'2025-09-11 01:46:24','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','9452273083043',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(89,'B&O Play Mini Bluetooth Speaker',NULL,NULL,NULL,'published','[\"products\\/14.jpg\"]',NULL,'AG-194-A1-A2',0,20,0,1,'in_stock',0,5,1,0,0,0.00,0,545,NULL,NULL,NULL,11,19,17,535,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','8489386924308',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(90,'Apple MacBook Air Retina 13.3-Inch Laptop',NULL,NULL,NULL,'published','[\"products\\/15-1.jpg\"]',NULL,'SQ-142-A1',0,12,0,1,'in_stock',0,2,1,0,0,0.00,0,566,NULL,NULL,NULL,16,11,20,638,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','4162762116071',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(91,'Apple MacBook Air Retina 13.3-Inch Laptop',NULL,NULL,NULL,'published','[\"products\\/15.jpg\"]',NULL,'SQ-142-A1-A2',0,12,0,1,'in_stock',0,2,1,0,0,0.00,0,566,NULL,NULL,NULL,16,11,20,638,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','5082740511478',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(92,'Apple MacBook Air Retina 13.3-Inch Laptop',NULL,NULL,NULL,'published','[\"products\\/15.jpg\"]',NULL,'SQ-142-A1-A3',0,12,0,1,'in_stock',0,2,1,0,0,0.00,0,566,NULL,NULL,NULL,16,11,20,638,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','7536927585700',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(93,'Apple MacBook Air Retina 13.3-Inch Laptop',NULL,NULL,NULL,'published','[\"products\\/15.jpg\"]',NULL,'SQ-142-A1-A4',0,12,0,1,'in_stock',0,2,1,0,0,0.00,0,566,NULL,NULL,NULL,16,11,20,638,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','4349669136108',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(94,'Samsung Gear VR Virtual Reality Headset',NULL,NULL,NULL,'published','[\"products\\/17-1.jpg\"]',NULL,'TO-124-A1',0,12,0,1,'in_stock',0,3,1,0,0,0.00,0,524,NULL,NULL,NULL,12,11,11,671,NULL,0,'2025-09-11 01:46:25','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','8754219020589',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(95,'Samsung Gear VR Virtual Reality Headset',NULL,NULL,NULL,'published','[\"products\\/17-2.jpg\"]',NULL,'TO-124-A1-A2',0,12,0,1,'in_stock',0,3,1,0,0,0.00,0,524,NULL,NULL,NULL,12,11,11,671,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','0822004602574',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(96,'NYX Beauty Couton Pallete Makeup 12',NULL,NULL,NULL,'published','[\"products\\/19-1.jpg\"]',NULL,'84-190-A1',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,1105,NULL,NULL,NULL,14,20,20,689,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','0156538411723',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(97,'NYX Beauty Couton Pallete Makeup 12',NULL,NULL,NULL,'published','[\"products\\/19-2.jpg\"]',NULL,'84-190-A1-A2',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,1105,NULL,NULL,NULL,14,20,20,689,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','7453773010128',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(98,'NYX Beauty Couton Pallete Makeup 12',NULL,NULL,NULL,'published','[\"products\\/19-3.jpg\"]',NULL,'84-190-A1-A3',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,1105,NULL,NULL,NULL,14,20,20,689,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:02',0,'Botble\\ACL\\Models\\User',NULL,'physical','6120858771595',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(99,'Baxter Care Hair Kit For Bearded Mens',NULL,NULL,NULL,'published','[\"products\\/22-1.jpg\"]',NULL,'QS-154-A1',0,12,0,1,'in_stock',0,4,1,0,0,0.00,0,510,NULL,NULL,NULL,19,13,18,554,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','5973199683130',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(100,'Baxter Care Hair Kit For Bearded Mens',NULL,NULL,NULL,'published','[\"products\\/22-2.jpg\"]',NULL,'QS-154-A1-A2',0,12,0,1,'in_stock',0,4,1,0,0,0.00,0,510,NULL,NULL,NULL,19,13,18,554,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','8008536425603',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(101,'Baxter Care Hair Kit For Bearded Mens',NULL,NULL,NULL,'published','[\"products\\/22-3.jpg\"]',NULL,'QS-154-A1-A3',0,12,0,1,'in_stock',0,4,1,0,0,0.00,0,510,NULL,NULL,NULL,19,13,18,554,NULL,0,'2025-09-11 01:46:26','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','6027310610327',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(102,'Soft Mochi & Galeto Ice Cream',NULL,NULL,NULL,'published','[\"products\\/27-1.jpg\"]',NULL,'XQ-192-A1',0,19,0,1,'in_stock',0,3,1,0,0,0.00,0,585,NULL,NULL,NULL,10,17,20,840,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','4323455577030',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(103,'Soft Mochi & Galeto Ice Cream',NULL,NULL,NULL,'published','[\"products\\/27.jpg\"]',NULL,'XQ-192-A1-A2',0,19,0,1,'in_stock',0,3,1,0,0,0.00,0,585,NULL,NULL,NULL,10,17,20,840,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','3955317828212',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(104,'Soft Mochi & Galeto Ice Cream',NULL,NULL,NULL,'published','[\"products\\/27.jpg\"]',NULL,'XQ-192-A1-A3',0,19,0,1,'in_stock',0,3,1,0,0,0.00,0,585,NULL,NULL,NULL,10,17,20,840,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:03',0,'Botble\\ACL\\Models\\User',NULL,'physical','4830386788959',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(105,'Bar S – Classic Bun Length Franks',NULL,NULL,NULL,'published','[\"products\\/30-1.jpg\"]',NULL,'S0-176-A1',0,12,0,1,'in_stock',0,5,1,0,0,0.00,0,1153,NULL,NULL,NULL,12,14,12,770,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','0639900659768',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(106,'Bar S – Classic Bun Length Franks',NULL,NULL,NULL,'published','[\"products\\/30-2.jpg\"]',NULL,'S0-176-A1-A2',0,12,0,1,'in_stock',0,5,1,0,0,0.00,0,1153,NULL,NULL,NULL,12,14,12,770,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','7200812630898',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(107,'Bar S – Classic Bun Length Franks',NULL,NULL,NULL,'published','[\"products\\/30.jpg\"]',NULL,'S0-176-A1-A3',0,12,0,1,'in_stock',0,5,1,0,0,0.00,0,1153,NULL,NULL,NULL,12,14,12,770,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','1231923067873',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(108,'Bar S – Classic Bun Length Franks',NULL,NULL,NULL,'published','[\"products\\/30.jpg\"]',NULL,'S0-176-A1-A4',0,12,0,1,'in_stock',0,5,1,0,0,0.00,0,1153,NULL,NULL,NULL,12,14,12,770,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','9871915129244',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(109,'Broccoli Crowns',NULL,NULL,NULL,'published','[\"products\\/31-1.jpg\"]',NULL,'O5-133-A1',0,11,0,1,'in_stock',0,3,1,0,0,0.00,0,620,NULL,NULL,NULL,17,15,10,743,NULL,0,'2025-09-11 01:46:27','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','7873266737255',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(110,'Iceland 3 Solo Exotic Burst',NULL,NULL,NULL,'published','[\"products\\/34-1.jpg\"]',NULL,'AD-161-A1',0,12,0,1,'in_stock',0,3,1,0,0,0.00,0,592,NULL,NULL,NULL,17,18,14,790,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:04',0,'Botble\\ACL\\Models\\User',NULL,'physical','9821464618430',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(111,'Dolmio Bolognese Pasta Sauce',NULL,NULL,NULL,'published','[\"products\\/37-1.jpg\"]',NULL,'EA-137-A1',0,10,0,1,'in_stock',0,5,1,0,0,0.00,0,980,NULL,NULL,NULL,16,19,20,638,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','7603269359129',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(112,'Dolmio Bolognese Pasta Sauce',NULL,NULL,NULL,'published','[\"products\\/37-2.jpg\"]',NULL,'EA-137-A1-A2',0,10,0,1,'in_stock',0,5,1,0,0,0.00,0,980,NULL,NULL,NULL,16,19,20,638,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','0407795330488',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(113,'Dolmio Bolognese Pasta Sauce',NULL,NULL,NULL,'published','[\"products\\/37.jpg\"]',NULL,'EA-137-A1-A3',0,10,0,1,'in_stock',0,5,1,0,0,0.00,0,980,NULL,NULL,NULL,16,19,20,638,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','8061353436238',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(114,'Wayfair Basics Dinner Plate Storage',NULL,NULL,NULL,'published','[\"products\\/39-1.jpg\"]',NULL,'GK-185-A1',0,16,0,1,'in_stock',0,4,1,0,0,0.00,0,1289,NULL,NULL,NULL,15,12,17,891,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','5394099186949',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(115,'Miko The Panda Water Bottle (Digital)',NULL,NULL,NULL,'published','[\"products\\/40-1.jpg\"]',NULL,'RK-104-A1',0,17,0,1,'in_stock',0,1,1,0,0,0.00,0,736,537.28,NULL,NULL,18,11,17,584,NULL,0,'2025-09-11 01:46:28','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'digital','1913165486406',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(116,'Miko The Panda Water Bottle (Digital)',NULL,NULL,NULL,'published','[\"products\\/40.jpg\"]',NULL,'RK-104-A1-A2',0,17,0,1,'in_stock',0,1,1,0,0,0.00,0,736,647.68,NULL,NULL,18,11,17,584,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'digital','7209045796807',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(117,'Sesame Seed Bread',NULL,NULL,NULL,'published','[\"products\\/41-1.jpg\"]',NULL,'DP-179-A1',0,16,0,1,'in_stock',0,5,1,0,0,0.00,0,938,NULL,NULL,NULL,17,16,16,577,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:05',0,'Botble\\ACL\\Models\\User',NULL,'physical','0673658050408',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(118,'Avocado, Hass Large',NULL,NULL,NULL,'published','[\"products\\/43-1.jpg\"]',NULL,'GS-154-A1',0,14,0,1,'in_stock',0,4,1,0,0,0.00,0,1176,NULL,NULL,NULL,12,10,20,526,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','1712845088714',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(119,'Avocado, Hass Large',NULL,NULL,NULL,'published','[\"products\\/43-2.jpg\"]',NULL,'GS-154-A1-A2',0,14,0,1,'in_stock',0,4,1,0,0,0.00,0,1176,NULL,NULL,NULL,12,10,20,526,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','2207032259477',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(120,'Avocado, Hass Large',NULL,NULL,NULL,'published','[\"products\\/43.jpg\"]',NULL,'GS-154-A1-A3',0,14,0,1,'in_stock',0,4,1,0,0,0.00,0,1176,NULL,NULL,NULL,12,10,20,526,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','3806599935948',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(121,'Avocado, Hass Large',NULL,NULL,NULL,'published','[\"products\\/43.jpg\"]',NULL,'GS-154-A1-A4',0,14,0,1,'in_stock',0,4,1,0,0,0.00,0,1176,NULL,NULL,NULL,12,10,20,526,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','3764861954946',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(122,'Italia Beef Lasagne (Digital)',NULL,NULL,NULL,'published','[\"products\\/44-1.jpg\"]',NULL,'0S-181-A1',0,13,0,1,'in_stock',0,5,1,0,0,0.00,0,1098,812.52,NULL,NULL,14,16,12,570,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','8657032238431',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(123,'Italia Beef Lasagne (Digital)',NULL,NULL,NULL,'published','[\"products\\/44-2.jpg\"]',NULL,'0S-181-A1-A2',0,13,0,1,'in_stock',0,5,1,0,0,0.00,0,1098,922.32,NULL,NULL,14,16,12,570,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','5380882617974',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(124,'Italia Beef Lasagne (Digital)',NULL,NULL,NULL,'published','[\"products\\/44.jpg\"]',NULL,'0S-181-A1-A3',0,13,0,1,'in_stock',0,5,1,0,0,0.00,0,1098,790.56,NULL,NULL,14,16,12,570,NULL,0,'2025-09-11 01:46:29','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','1476746023877',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(125,'Maxwell House Classic Roast Mocha',NULL,NULL,NULL,'published','[\"products\\/45-1.jpg\"]',NULL,'26-154-A1',0,20,0,1,'in_stock',0,1,1,0,0,0.00,0,786,NULL,NULL,NULL,18,13,17,587,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','7419694041993',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(126,'Maxwell House Classic Roast Mocha',NULL,NULL,NULL,'published','[\"products\\/45.jpg\"]',NULL,'26-154-A1-A2',0,20,0,1,'in_stock',0,1,1,0,0,0.00,0,786,NULL,NULL,NULL,18,13,17,587,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','8728958959349',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(127,'Maxwell House Classic Roast Mocha',NULL,NULL,NULL,'published','[\"products\\/45.jpg\"]',NULL,'26-154-A1-A3',0,20,0,1,'in_stock',0,1,1,0,0,0.00,0,786,NULL,NULL,NULL,18,13,17,587,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','5473166899941',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(128,'Maxwell House Classic Roast Mocha',NULL,NULL,NULL,'published','[\"products\\/45.jpg\"]',NULL,'26-154-A1-A4',0,20,0,1,'in_stock',0,1,1,0,0,0.00,0,786,NULL,NULL,NULL,18,13,17,587,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','3531666832263',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(129,'Maxwell House Classic Roast Mocha',NULL,NULL,NULL,'published','[\"products\\/45.jpg\"]',NULL,'26-154-A1-A5',0,20,0,1,'in_stock',0,1,1,0,0,0.00,0,786,NULL,NULL,NULL,18,13,17,587,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'physical','7247532447955',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(130,'Coca-Cola Original Taste (Digital)',NULL,NULL,NULL,'published','[\"products\\/48-1.jpg\"]',NULL,'TM-133-A1',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,780,624,NULL,NULL,17,13,16,535,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:06',0,'Botble\\ACL\\Models\\User',NULL,'digital','8001850430155',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(131,'Casillero Diablo Cabernet Sauvignon',NULL,NULL,NULL,'published','[\"products\\/49-1.jpg\"]',NULL,'2H-135-A1',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,999,NULL,NULL,NULL,10,15,15,725,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','3168455106405',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(132,'Casillero Diablo Cabernet Sauvignon',NULL,NULL,NULL,'published','[\"products\\/49-2.jpg\"]',NULL,'2H-135-A1-A2',0,18,0,1,'in_stock',0,4,1,0,0,0.00,0,999,NULL,NULL,NULL,10,15,15,725,NULL,0,'2025-09-11 01:46:30','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','3977541440480',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(133,'Aptamil Follow On Baby Milk',NULL,NULL,NULL,'published','[\"products\\/51.jpg\"]',NULL,'3S-183-A1',0,11,0,1,'in_stock',0,1,1,0,0,0.00,0,1274,NULL,NULL,NULL,18,12,18,658,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','8505299766269',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(134,'Honest Organic Still Lemonade',NULL,NULL,NULL,'published','[\"products\\/55-1.jpg\"]',NULL,'EE-111-A1',0,10,0,1,'in_stock',0,4,1,0,0,0.00,0,648,NULL,NULL,NULL,18,16,13,759,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','1918645577711',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(135,'Honest Organic Still Lemonade',NULL,NULL,NULL,'published','[\"products\\/55-2.jpg\"]',NULL,'EE-111-A1-A2',0,10,0,1,'in_stock',0,4,1,0,0,0.00,0,648,NULL,NULL,NULL,18,16,13,759,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','1193337824223',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(136,'Honest Organic Still Lemonade',NULL,NULL,NULL,'published','[\"products\\/55.jpg\"]',NULL,'EE-111-A1-A3',0,10,0,1,'in_stock',0,4,1,0,0,0.00,0,648,NULL,NULL,NULL,18,16,13,759,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','2945144656691',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(137,'Honest Organic Still Lemonade',NULL,NULL,NULL,'published','[\"products\\/55.jpg\"]',NULL,'EE-111-A1-A4',0,10,0,1,'in_stock',0,4,1,0,0,0.00,0,648,NULL,NULL,NULL,18,16,13,759,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:07',0,'Botble\\ACL\\Models\\User',NULL,'physical','1192054689740',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(138,'Iceland 6 Hot Cross Buns',NULL,NULL,NULL,'published','[\"products\\/57-1.jpg\"]',NULL,'PK-160-A1',0,13,0,1,'in_stock',0,3,1,0,0,0.00,0,627,NULL,NULL,NULL,18,13,16,738,NULL,0,'2025-09-11 01:46:31','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','5520599066898',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(139,'Iceland 6 Hot Cross Buns',NULL,NULL,NULL,'published','[\"products\\/57.jpg\"]',NULL,'PK-160-A1-A2',0,13,0,1,'in_stock',0,3,1,0,0,0.00,0,627,NULL,NULL,NULL,18,13,16,738,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','5875631880797',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(140,'Iceland Spaghetti Bolognese (Digital)',NULL,NULL,NULL,'published','[\"products\\/60-1.jpg\"]',NULL,'Z9-135-A1',0,13,0,1,'in_stock',0,2,1,0,0,0.00,0,1275,994.5,NULL,NULL,16,16,10,809,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'digital','5820121571884',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(141,'Iceland Spaghetti Bolognese (Digital)',NULL,NULL,NULL,'published','[\"products\\/60-2.jpg\"]',NULL,'Z9-135-A1-A2',0,13,0,1,'in_stock',0,2,1,0,0,0.00,0,1275,918,NULL,NULL,16,16,10,809,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'digital','9515516893004',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(142,'Iceland Spaghetti Bolognese (Digital)',NULL,NULL,NULL,'published','[\"products\\/60.jpg\"]',NULL,'Z9-135-A1-A3',0,13,0,1,'in_stock',0,2,1,0,0,0.00,0,1275,1109.25,NULL,NULL,16,16,10,809,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'digital','7163887909767',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(143,'Large Green Bell Pepper',NULL,NULL,NULL,'published','[\"products\\/63-1.jpg\"]',NULL,'C4-136-A1',0,15,0,1,'in_stock',0,2,1,0,0,0.00,0,1188,NULL,NULL,NULL,14,16,13,500,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','1899510927742',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(144,'Large Green Bell Pepper',NULL,NULL,NULL,'published','[\"products\\/63.jpg\"]',NULL,'C4-136-A1-A2',0,15,0,1,'in_stock',0,2,1,0,0,0.00,0,1188,NULL,NULL,NULL,14,16,13,500,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:08',0,'Botble\\ACL\\Models\\User',NULL,'physical','6079657117090',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(145,'Province Piece Glass Drinking Glass',NULL,NULL,NULL,'published','[\"products\\/65-1.jpg\"]',NULL,'LE-105-A1',0,20,0,1,'in_stock',0,3,1,0,0,0.00,0,708,NULL,NULL,NULL,12,14,18,885,NULL,0,'2025-09-11 01:46:32','2025-09-11 01:47:09',0,'Botble\\ACL\\Models\\User',NULL,'physical','9175535463956',NULL,0,'auto_generate',0,0,0,NULL,NULL,0),(146,'Province Piece Glass Drinking Glass',NULL,NULL,NULL,'published','[\"products\\/65-2.jpg\"]',NULL,'LE-105-A1-A2',0,20,0,1,'in_stock',0,3,1,0,0,0.00,0,708,NULL,NULL,NULL,12,14,18,885,NULL,0,'2025-09-11 01:46:33','2025-09-11 01:47:09',0,'Botble\\ACL\\Models\\User',NULL,'physical','7068344886004',NULL,0,'auto_generate',0,0,0,NULL,NULL,0);
/*!40000 ALTER TABLE `ec_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_products_translations`
--

DROP TABLE IF EXISTS `ec_products_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_products_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_products_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `content` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`ec_products_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_products_translations`
--

LOCK TABLES `ec_products_translations` WRITE;
/*!40000 ALTER TABLE `ec_products_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_products_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_review_replies`
--

DROP TABLE IF EXISTS `ec_review_replies`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_review_replies` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `review_id` bigint unsigned NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_review_replies_review_id_user_id_unique` (`review_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_review_replies`
--

LOCK TABLES `ec_review_replies` WRITE;
/*!40000 ALTER TABLE `ec_review_replies` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_review_replies` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_reviews`
--

DROP TABLE IF EXISTS `ec_reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_reviews` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `customer_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_email` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `product_id` bigint unsigned NOT NULL,
  `star` double NOT NULL,
  `comment` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `images` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_reviews_product_id_customer_id_unique` (`product_id`,`customer_id`),
  KEY `ec_reviews_product_id_customer_id_status_created_at_index` (`product_id`,`customer_id`,`status`,`created_at`),
  KEY `review_relation_index` (`product_id`,`customer_id`,`status`)
) ENGINE=InnoDB AUTO_INCREMENT=1001 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_reviews`
--

LOCK TABLES `ec_reviews` WRITE;
/*!40000 ALTER TABLE `ec_reviews` DISABLE KEYS */;
INSERT INTO `ec_reviews` VALUES (1,9,NULL,NULL,40,5,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/7.jpg\",\"products\\/58-1.jpg\"]'),(2,6,NULL,NULL,47,1,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29.jpg\"]'),(3,5,NULL,NULL,65,1,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\"]'),(4,7,NULL,NULL,37,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-1.jpg\",\"products\\/24.jpg\",\"products\\/45.jpg\",\"products\\/62-1.jpg\"]'),(5,7,NULL,NULL,17,4,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\"]'),(6,8,NULL,NULL,29,5,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/38-1.jpg\",\"products\\/45.jpg\",\"products\\/46.jpg\",\"products\\/49-1.jpg\"]'),(7,8,NULL,NULL,10,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/58-2.jpg\"]'),(8,3,NULL,NULL,33,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/7.jpg\",\"products\\/19-3.jpg\"]'),(9,10,NULL,NULL,8,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/29.jpg\",\"products\\/38.jpg\",\"products\\/65-1.jpg\"]'),(10,8,NULL,NULL,8,1,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/32-1.jpg\",\"products\\/36-2.jpg\",\"products\\/41-1.jpg\"]'),(11,8,NULL,NULL,62,5,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/54.jpg\",\"products\\/56.jpg\"]'),(12,5,NULL,NULL,1,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-1.jpg\",\"products\\/38-3.jpg\"]'),(13,10,NULL,NULL,28,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/59-1.jpg\",\"products\\/61-1.jpg\"]'),(14,3,NULL,NULL,40,1,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44.jpg\"]'),(15,4,NULL,NULL,49,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/31-1.jpg\",\"products\\/48.jpg\"]'),(16,1,NULL,NULL,36,1,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\",\"products\\/37-2.jpg\",\"products\\/41.jpg\",\"products\\/47-1.jpg\"]'),(17,7,NULL,NULL,55,4,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/39-2.jpg\"]'),(18,4,NULL,NULL,24,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\"]'),(19,8,NULL,NULL,35,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-2.jpg\"]'),(20,3,NULL,NULL,10,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/35-1.jpg\",\"products\\/37-1.jpg\",\"products\\/50.jpg\"]'),(21,9,NULL,NULL,30,4,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\",\"products\\/23-2.jpg\",\"products\\/29-1.jpg\",\"products\\/44.jpg\"]'),(22,8,NULL,NULL,7,4,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44.jpg\",\"products\\/54.jpg\",\"products\\/57-1.jpg\"]'),(23,4,NULL,NULL,48,1,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/58-1.jpg\"]'),(24,7,NULL,NULL,6,5,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/18.jpg\",\"products\\/34.jpg\",\"products\\/41-1.jpg\"]'),(25,3,NULL,NULL,61,2,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/19-2.jpg\",\"products\\/35.jpg\",\"products\\/56-2.jpg\"]'),(26,10,NULL,NULL,65,5,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/46-1.jpg\"]'),(27,2,NULL,NULL,39,1,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\",\"products\\/28.jpg\",\"products\\/40.jpg\",\"products\\/41-1.jpg\"]'),(28,2,NULL,NULL,26,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/32-2.jpg\",\"products\\/42.jpg\",\"products\\/50.jpg\",\"products\\/56.jpg\"]'),(29,7,NULL,NULL,49,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-2.jpg\"]'),(30,7,NULL,NULL,19,4,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-2.jpg\"]'),(31,10,NULL,NULL,11,5,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\",\"products\\/20-3.jpg\",\"products\\/46.jpg\",\"products\\/60-1.jpg\"]'),(32,9,NULL,NULL,23,5,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\",\"products\\/44-1.jpg\",\"products\\/50.jpg\",\"products\\/54-1.jpg\"]'),(33,10,NULL,NULL,12,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/42.jpg\"]'),(34,1,NULL,NULL,9,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-3.jpg\",\"products\\/62-1.jpg\"]'),(35,7,NULL,NULL,27,5,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/10-2.jpg\",\"products\\/26-1.jpg\",\"products\\/65.jpg\"]'),(36,9,NULL,NULL,6,1,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/20-1.jpg\",\"products\\/29-2.jpg\"]'),(37,6,NULL,NULL,26,2,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-3.jpg\"]'),(38,9,NULL,NULL,29,4,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-1.jpg\",\"products\\/34.jpg\",\"products\\/44.jpg\"]'),(39,6,NULL,NULL,25,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-1.jpg\",\"products\\/23-3.jpg\",\"products\\/60.jpg\"]'),(40,3,NULL,NULL,35,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/60.jpg\",\"products\\/63.jpg\",\"products\\/64.jpg\"]'),(41,3,NULL,NULL,36,3,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/35.jpg\"]'),(42,1,NULL,NULL,57,5,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\",\"products\\/18.jpg\",\"products\\/56.jpg\"]'),(43,7,NULL,NULL,2,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-2.jpg\",\"products\\/56.jpg\"]'),(44,3,NULL,NULL,45,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\",\"products\\/18-1.jpg\"]'),(45,10,NULL,NULL,7,2,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/26.jpg\",\"products\\/37-1.jpg\",\"products\\/58.jpg\"]'),(46,4,NULL,NULL,58,3,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33-1.jpg\"]'),(47,3,NULL,NULL,65,4,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\"]'),(48,1,NULL,NULL,5,4,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10.jpg\",\"products\\/42-1.jpg\",\"products\\/49-1.jpg\",\"products\\/65-1.jpg\"]'),(49,7,NULL,NULL,34,2,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/64-1.jpg\"]'),(50,5,NULL,NULL,62,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/34.jpg\",\"products\\/57-1.jpg\"]'),(51,9,NULL,NULL,48,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/48.jpg\",\"products\\/50.jpg\"]'),(52,6,NULL,NULL,55,1,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/55-2.jpg\"]'),(53,2,NULL,NULL,32,1,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/23.jpg\",\"products\\/24-2.jpg\",\"products\\/37.jpg\"]'),(54,6,NULL,NULL,4,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/40.jpg\"]'),(55,2,NULL,NULL,41,1,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-1.jpg\",\"products\\/59.jpg\"]'),(56,7,NULL,NULL,36,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\"]'),(57,10,NULL,NULL,14,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\",\"products\\/56-1.jpg\"]'),(58,9,NULL,NULL,7,5,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25.jpg\",\"products\\/48-2.jpg\"]'),(59,4,NULL,NULL,59,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25-2.jpg\",\"products\\/54.jpg\",\"products\\/58-1.jpg\"]'),(61,7,NULL,NULL,48,2,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/49-2.jpg\"]'),(62,9,NULL,NULL,51,1,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-2.jpg\",\"products\\/22-2.jpg\",\"products\\/53-1.jpg\"]'),(63,6,NULL,NULL,45,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/4-3.jpg\",\"products\\/36-2.jpg\"]'),(64,9,NULL,NULL,24,1,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/25-1.jpg\"]'),(66,6,NULL,NULL,46,2,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/27.jpg\",\"products\\/57-1.jpg\"]'),(68,9,NULL,NULL,39,4,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\",\"products\\/55.jpg\",\"products\\/64.jpg\"]'),(69,10,NULL,NULL,50,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/15.jpg\",\"products\\/18-2.jpg\",\"products\\/28-2.jpg\"]'),(70,10,NULL,NULL,32,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-3.jpg\"]'),(71,7,NULL,NULL,53,1,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/13-1.jpg\",\"products\\/20-1.jpg\",\"products\\/34.jpg\"]'),(72,1,NULL,NULL,56,4,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13.jpg\"]'),(73,1,NULL,NULL,58,5,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-2.jpg\",\"products\\/43-2.jpg\"]'),(74,1,NULL,NULL,53,5,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-1.jpg\",\"products\\/21.jpg\",\"products\\/58-1.jpg\",\"products\\/64-1.jpg\"]'),(76,2,NULL,NULL,57,4,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-1.jpg\"]'),(77,2,NULL,NULL,20,4,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/36-1.jpg\",\"products\\/38-3.jpg\",\"products\\/56-1.jpg\"]'),(78,4,NULL,NULL,45,2,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/45.jpg\",\"products\\/55-1.jpg\"]'),(79,6,NULL,NULL,22,1,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-3.jpg\",\"products\\/45-1.jpg\",\"products\\/49-1.jpg\"]'),(80,8,NULL,NULL,57,3,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/56-2.jpg\"]'),(81,10,NULL,NULL,1,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13.jpg\"]'),(82,5,NULL,NULL,5,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28-1.jpg\"]'),(83,1,NULL,NULL,33,2,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/9-2.jpg\",\"products\\/41.jpg\"]'),(84,6,NULL,NULL,19,4,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28.jpg\",\"products\\/38-2.jpg\"]'),(85,9,NULL,NULL,14,1,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/57-1.jpg\"]'),(86,5,NULL,NULL,9,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/36-1.jpg\",\"products\\/37.jpg\",\"products\\/52.jpg\"]'),(87,6,NULL,NULL,38,2,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/15-1.jpg\",\"products\\/17.jpg\",\"products\\/65-2.jpg\"]'),(90,6,NULL,NULL,12,4,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\",\"products\\/28-1.jpg\",\"products\\/32-2.jpg\"]'),(91,7,NULL,NULL,7,2,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/18-3.jpg\"]'),(92,9,NULL,NULL,2,1,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-2.jpg\",\"products\\/59.jpg\"]'),(93,8,NULL,NULL,14,2,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/21-1.jpg\"]'),(94,1,NULL,NULL,3,5,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\",\"products\\/19.jpg\",\"products\\/62.jpg\",\"products\\/65.jpg\"]'),(96,9,NULL,NULL,49,2,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/27.jpg\",\"products\\/33-1.jpg\",\"products\\/55-1.jpg\",\"products\\/59.jpg\"]'),(97,1,NULL,NULL,59,3,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/50.jpg\"]'),(99,2,NULL,NULL,40,1,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-1.jpg\",\"products\\/22-1.jpg\",\"products\\/29.jpg\"]'),(100,3,NULL,NULL,16,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-2.jpg\",\"products\\/47-1.jpg\",\"products\\/62.jpg\",\"products\\/65.jpg\"]'),(101,3,NULL,NULL,50,5,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/46-1.jpg\"]'),(102,1,NULL,NULL,39,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-1.jpg\"]'),(103,3,NULL,NULL,59,3,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28.jpg\",\"products\\/29.jpg\",\"products\\/34-1.jpg\",\"products\\/45.jpg\"]'),(104,9,NULL,NULL,50,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28-1.jpg\"]'),(105,7,NULL,NULL,3,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/36-2.jpg\"]'),(106,4,NULL,NULL,10,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\",\"products\\/26.jpg\",\"products\\/40-1.jpg\",\"products\\/42-1.jpg\"]'),(107,10,NULL,NULL,15,4,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/19-1.jpg\",\"products\\/28.jpg\"]'),(108,1,NULL,NULL,32,1,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-3.jpg\",\"products\\/58-1.jpg\",\"products\\/61.jpg\"]'),(109,6,NULL,NULL,24,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\",\"products\\/23-3.jpg\",\"products\\/41.jpg\"]'),(110,2,NULL,NULL,10,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-2.jpg\",\"products\\/30.jpg\"]'),(111,9,NULL,NULL,56,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/35.jpg\",\"products\\/52-1.jpg\",\"products\\/55.jpg\"]'),(112,9,NULL,NULL,1,4,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\",\"products\\/36.jpg\",\"products\\/45.jpg\",\"products\\/50-1.jpg\"]'),(114,5,NULL,NULL,34,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\",\"products\\/59-2.jpg\"]'),(115,9,NULL,NULL,54,1,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/47.jpg\",\"products\\/59-3.jpg\"]'),(116,1,NULL,NULL,6,3,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-2.jpg\",\"products\\/19-3.jpg\",\"products\\/29-1.jpg\",\"products\\/50-1.jpg\"]'),(117,8,NULL,NULL,42,4,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/57.jpg\"]'),(119,1,NULL,NULL,50,5,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/65-1.jpg\"]'),(120,3,NULL,NULL,53,2,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\",\"products\\/10-2.jpg\",\"products\\/50.jpg\"]'),(121,3,NULL,NULL,55,3,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/32-1.jpg\"]'),(122,9,NULL,NULL,27,2,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/39.jpg\",\"products\\/41-2.jpg\",\"products\\/59-3.jpg\"]'),(124,5,NULL,NULL,6,1,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-1.jpg\",\"products\\/15-1.jpg\",\"products\\/41-1.jpg\",\"products\\/48.jpg\"]'),(125,4,NULL,NULL,60,4,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-3.jpg\",\"products\\/24-2.jpg\",\"products\\/48-2.jpg\",\"products\\/55-2.jpg\"]'),(127,9,NULL,NULL,57,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-1.jpg\",\"products\\/23.jpg\",\"products\\/42-2.jpg\",\"products\\/64-1.jpg\"]'),(128,8,NULL,NULL,54,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-1.jpg\",\"products\\/36-2.jpg\",\"products\\/49-1.jpg\",\"products\\/61.jpg\"]'),(129,4,NULL,NULL,16,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-1.jpg\",\"products\\/36-1.jpg\",\"products\\/62.jpg\"]'),(130,6,NULL,NULL,43,1,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\",\"products\\/8-1.jpg\",\"products\\/48-2.jpg\"]'),(131,5,NULL,NULL,17,3,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/42-1.jpg\"]'),(133,2,NULL,NULL,42,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/31-1.jpg\"]'),(135,3,NULL,NULL,23,1,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/15.jpg\",\"products\\/39.jpg\"]'),(136,4,NULL,NULL,47,5,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\",\"products\\/48-2.jpg\"]'),(138,2,NULL,NULL,35,5,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\",\"products\\/33.jpg\",\"products\\/50.jpg\"]'),(139,8,NULL,NULL,63,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\",\"products\\/26-1.jpg\",\"products\\/27-1.jpg\",\"products\\/56-2.jpg\"]'),(142,3,NULL,NULL,63,1,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\",\"products\\/20.jpg\",\"products\\/34.jpg\",\"products\\/59.jpg\"]'),(143,3,NULL,NULL,54,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30.jpg\",\"products\\/58-1.jpg\"]'),(144,7,NULL,NULL,4,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/52-2.jpg\"]'),(145,7,NULL,NULL,29,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-3.jpg\"]'),(146,3,NULL,NULL,52,5,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-1.jpg\",\"products\\/65-2.jpg\"]'),(147,8,NULL,NULL,9,3,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\"]'),(148,9,NULL,NULL,25,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/27-1.jpg\",\"products\\/36-1.jpg\",\"products\\/48-2.jpg\",\"products\\/62.jpg\"]'),(150,1,NULL,NULL,15,1,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-2.jpg\",\"products\\/55-1.jpg\"]'),(151,3,NULL,NULL,9,2,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/54-1.jpg\"]'),(152,2,NULL,NULL,3,1,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/31.jpg\",\"products\\/50.jpg\",\"products\\/51.jpg\"]'),(153,5,NULL,NULL,57,5,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/53-1.jpg\"]'),(154,10,NULL,NULL,43,3,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/39-1.jpg\",\"products\\/49.jpg\"]'),(155,10,NULL,NULL,24,3,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/43-1.jpg\"]'),(157,7,NULL,NULL,21,4,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/63.jpg\"]'),(158,9,NULL,NULL,64,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/18-1.jpg\",\"products\\/27-1.jpg\",\"products\\/50-1.jpg\"]'),(159,2,NULL,NULL,14,2,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-3.jpg\",\"products\\/38-1.jpg\"]'),(160,9,NULL,NULL,28,3,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33.jpg\"]'),(161,3,NULL,NULL,57,5,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/39-1.jpg\"]'),(162,9,NULL,NULL,16,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/6.jpg\",\"products\\/22-1.jpg\",\"products\\/41-2.jpg\"]'),(164,4,NULL,NULL,6,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/15-1.jpg\",\"products\\/23-2.jpg\",\"products\\/24-2.jpg\",\"products\\/58-2.jpg\"]'),(165,2,NULL,NULL,22,4,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-3.jpg\"]'),(166,4,NULL,NULL,3,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-1.jpg\"]'),(169,10,NULL,NULL,4,2,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-1.jpg\",\"products\\/11-2.jpg\",\"products\\/11.jpg\"]'),(170,9,NULL,NULL,26,4,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25-1.jpg\",\"products\\/48-1.jpg\"]'),(172,8,NULL,NULL,30,3,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/15-1.jpg\"]'),(173,5,NULL,NULL,60,2,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25-1.jpg\",\"products\\/25-2.jpg\",\"products\\/28-1.jpg\",\"products\\/46-1.jpg\"]'),(175,1,NULL,NULL,51,1,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/26.jpg\"]'),(176,10,NULL,NULL,2,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-1.jpg\",\"products\\/29.jpg\",\"products\\/55.jpg\",\"products\\/60-1.jpg\"]'),(177,1,NULL,NULL,41,1,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/59.jpg\"]'),(178,6,NULL,NULL,6,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\",\"products\\/40-1.jpg\"]'),(179,6,NULL,NULL,11,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41.jpg\"]'),(180,4,NULL,NULL,15,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-1.jpg\",\"products\\/39-1.jpg\"]'),(181,10,NULL,NULL,33,4,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/60.jpg\"]'),(182,6,NULL,NULL,39,5,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/60-1.jpg\"]'),(184,9,NULL,NULL,35,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\"]'),(185,6,NULL,NULL,16,4,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-3.jpg\"]'),(186,1,NULL,NULL,62,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\",\"products\\/32-2.jpg\",\"products\\/32.jpg\"]'),(187,10,NULL,NULL,61,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-1.jpg\",\"products\\/12.jpg\",\"products\\/44-2.jpg\"]'),(188,9,NULL,NULL,18,4,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-1.jpg\",\"products\\/51.jpg\",\"products\\/52-2.jpg\",\"products\\/65-1.jpg\"]'),(189,9,NULL,NULL,52,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/23-3.jpg\",\"products\\/53.jpg\"]'),(190,8,NULL,NULL,60,3,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-1.jpg\",\"products\\/27.jpg\",\"products\\/38.jpg\"]'),(191,8,NULL,NULL,16,4,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/31.jpg\",\"products\\/65-1.jpg\"]'),(193,10,NULL,NULL,49,4,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\"]'),(194,3,NULL,NULL,13,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-1.jpg\",\"products\\/18-3.jpg\",\"products\\/37-2.jpg\"]'),(195,3,NULL,NULL,18,5,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-2.jpg\",\"products\\/45-1.jpg\"]'),(198,4,NULL,NULL,55,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25.jpg\",\"products\\/33-1.jpg\"]'),(199,9,NULL,NULL,33,3,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-3.jpg\",\"products\\/32.jpg\"]'),(201,7,NULL,NULL,26,5,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/59-3.jpg\"]'),(202,7,NULL,NULL,24,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/37-2.jpg\",\"products\\/41-1.jpg\",\"products\\/43-1.jpg\"]'),(204,5,NULL,NULL,22,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-3.jpg\",\"products\\/24-2.jpg\"]'),(207,3,NULL,NULL,12,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\",\"products\\/29.jpg\",\"products\\/33.jpg\",\"products\\/61.jpg\"]'),(208,4,NULL,NULL,5,4,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/33.jpg\",\"products\\/38-2.jpg\",\"products\\/60-1.jpg\"]'),(209,10,NULL,NULL,52,3,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\",\"products\\/24.jpg\"]'),(210,6,NULL,NULL,1,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/40-1.jpg\"]'),(211,2,NULL,NULL,46,1,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-2.jpg\",\"products\\/36-2.jpg\",\"products\\/55-1.jpg\"]'),(212,10,NULL,NULL,40,3,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/60-2.jpg\"]'),(213,8,NULL,NULL,55,1,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/45.jpg\",\"products\\/48-2.jpg\"]'),(214,9,NULL,NULL,62,3,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/38-3.jpg\",\"products\\/38.jpg\"]'),(215,2,NULL,NULL,36,2,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30.jpg\",\"products\\/45.jpg\",\"products\\/50-1.jpg\"]'),(219,8,NULL,NULL,36,5,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\"]'),(221,4,NULL,NULL,37,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/27.jpg\",\"products\\/38-2.jpg\"]'),(223,3,NULL,NULL,64,4,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\"]'),(224,2,NULL,NULL,48,4,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-3.jpg\",\"products\\/26-1.jpg\"]'),(225,6,NULL,NULL,10,2,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29.jpg\",\"products\\/31-1.jpg\"]'),(226,7,NULL,NULL,60,3,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/49-2.jpg\",\"products\\/57.jpg\"]'),(227,5,NULL,NULL,59,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30.jpg\",\"products\\/55.jpg\",\"products\\/59-1.jpg\"]'),(228,6,NULL,NULL,20,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/26.jpg\",\"products\\/38-1.jpg\",\"products\\/55-1.jpg\"]'),(229,1,NULL,NULL,19,4,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-2.jpg\",\"products\\/28.jpg\"]'),(230,4,NULL,NULL,46,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10.jpg\",\"products\\/23-3.jpg\"]'),(232,9,NULL,NULL,20,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/27-1.jpg\",\"products\\/28.jpg\",\"products\\/64-1.jpg\"]'),(234,5,NULL,NULL,44,3,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\",\"products\\/28-2.jpg\",\"products\\/42-2.jpg\",\"products\\/64-1.jpg\"]'),(236,3,NULL,NULL,1,4,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11.jpg\",\"products\\/35-1.jpg\"]'),(237,9,NULL,NULL,22,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-2.jpg\",\"products\\/15.jpg\",\"products\\/20-1.jpg\"]'),(238,10,NULL,NULL,29,3,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/56-1.jpg\",\"products\\/65.jpg\"]'),(239,1,NULL,NULL,16,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/34-1.jpg\"]'),(241,2,NULL,NULL,29,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/29.jpg\"]'),(242,8,NULL,NULL,43,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\",\"products\\/35-1.jpg\",\"products\\/39.jpg\",\"products\\/50.jpg\"]'),(244,2,NULL,NULL,28,1,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-3.jpg\",\"products\\/60-1.jpg\"]'),(245,2,NULL,NULL,34,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33-1.jpg\",\"products\\/36.jpg\",\"products\\/40-1.jpg\",\"products\\/42-2.jpg\"]'),(246,3,NULL,NULL,43,3,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/22-2.jpg\",\"products\\/48.jpg\"]'),(248,5,NULL,NULL,40,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-1.jpg\",\"products\\/19-3.jpg\",\"products\\/30-1.jpg\"]'),(250,6,NULL,NULL,33,2,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/54.jpg\"]'),(251,4,NULL,NULL,8,3,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/48-1.jpg\"]'),(252,7,NULL,NULL,8,4,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-2.jpg\",\"products\\/49-1.jpg\"]'),(254,2,NULL,NULL,37,5,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\"]'),(256,7,NULL,NULL,23,5,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\",\"products\\/31-1.jpg\",\"products\\/39-1.jpg\",\"products\\/48-1.jpg\"]'),(258,7,NULL,NULL,32,4,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/32-1.jpg\"]'),(260,3,NULL,NULL,41,2,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/15.jpg\",\"products\\/23.jpg\"]'),(261,9,NULL,NULL,65,5,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/25-1.jpg\",\"products\\/25.jpg\",\"products\\/33.jpg\"]'),(263,4,NULL,NULL,36,1,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-1.jpg\",\"products\\/32.jpg\",\"products\\/33.jpg\",\"products\\/53.jpg\"]'),(264,3,NULL,NULL,39,5,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\",\"products\\/44-1.jpg\"]'),(265,5,NULL,NULL,11,4,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\",\"products\\/22-2.jpg\",\"products\\/30-1.jpg\"]'),(266,4,NULL,NULL,51,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13.jpg\",\"products\\/54-1.jpg\"]'),(267,4,NULL,NULL,25,2,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-2.jpg\",\"products\\/39.jpg\"]'),(268,2,NULL,NULL,58,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/43-2.jpg\"]'),(270,3,NULL,NULL,26,5,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/54-1.jpg\"]'),(272,4,NULL,NULL,26,5,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-1.jpg\",\"products\\/48-1.jpg\",\"products\\/52-2.jpg\"]'),(273,6,NULL,NULL,29,4,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/42.jpg\"]'),(275,10,NULL,NULL,17,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20.jpg\",\"products\\/65.jpg\"]'),(278,3,NULL,NULL,20,2,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-3.jpg\",\"products\\/38-3.jpg\",\"products\\/43.jpg\",\"products\\/61.jpg\"]'),(279,8,NULL,NULL,51,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/37-2.jpg\",\"products\\/50-1.jpg\",\"products\\/53-1.jpg\"]'),(281,2,NULL,NULL,62,2,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/22-3.jpg\",\"products\\/41-2.jpg\"]'),(283,2,NULL,NULL,27,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/49.jpg\"]'),(284,2,NULL,NULL,16,5,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\"]'),(285,1,NULL,NULL,65,4,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/34.jpg\"]'),(287,5,NULL,NULL,30,5,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-3.jpg\",\"products\\/23-1.jpg\",\"products\\/34-1.jpg\",\"products\\/46-1.jpg\"]'),(288,10,NULL,NULL,36,4,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\"]'),(289,10,NULL,NULL,23,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20.jpg\",\"products\\/28-1.jpg\",\"products\\/40.jpg\",\"products\\/49.jpg\"]'),(290,1,NULL,NULL,63,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/17-3.jpg\"]'),(295,5,NULL,NULL,63,1,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/4.jpg\",\"products\\/12-1.jpg\",\"products\\/25.jpg\"]'),(296,4,NULL,NULL,11,2,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-2.jpg\",\"products\\/44-1.jpg\",\"products\\/56-2.jpg\"]'),(298,5,NULL,NULL,31,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-2.jpg\",\"products\\/44.jpg\"]'),(299,6,NULL,NULL,56,4,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\",\"products\\/32-1.jpg\",\"products\\/37.jpg\"]'),(300,8,NULL,NULL,40,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/61.jpg\"]'),(301,6,NULL,NULL,37,1,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/11.jpg\",\"products\\/46.jpg\",\"products\\/52-1.jpg\"]'),(302,1,NULL,NULL,7,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/33.jpg\",\"products\\/48-1.jpg\"]'),(303,7,NULL,NULL,63,3,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-2.jpg\",\"products\\/37-2.jpg\"]'),(304,8,NULL,NULL,23,3,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/40-1.jpg\",\"products\\/42-2.jpg\",\"products\\/43.jpg\"]'),(305,4,NULL,NULL,42,4,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/45.jpg\"]'),(306,10,NULL,NULL,44,5,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/65-1.jpg\"]'),(307,8,NULL,NULL,24,4,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/54-1.jpg\",\"products\\/55-1.jpg\",\"products\\/57-1.jpg\"]'),(308,1,NULL,NULL,49,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-2.jpg\",\"products\\/28.jpg\",\"products\\/62-1.jpg\",\"products\\/64-1.jpg\"]'),(309,2,NULL,NULL,1,5,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/16.jpg\",\"products\\/65-1.jpg\"]'),(310,9,NULL,NULL,55,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\",\"products\\/34.jpg\",\"products\\/49-1.jpg\"]'),(311,9,NULL,NULL,34,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21.jpg\",\"products\\/30-2.jpg\"]'),(315,10,NULL,NULL,27,2,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25.jpg\",\"products\\/44-2.jpg\"]'),(316,1,NULL,NULL,38,2,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-2.jpg\",\"products\\/19-1.jpg\"]'),(319,7,NULL,NULL,61,2,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-1.jpg\",\"products\\/63-1.jpg\"]'),(320,6,NULL,NULL,59,2,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\",\"products\\/11-1.jpg\",\"products\\/57-1.jpg\"]'),(322,6,NULL,NULL,27,1,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/47-1.jpg\",\"products\\/59.jpg\",\"products\\/62.jpg\",\"products\\/65-2.jpg\"]'),(323,2,NULL,NULL,63,3,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-1.jpg\"]'),(324,3,NULL,NULL,38,2,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/42-2.jpg\",\"products\\/53.jpg\"]'),(326,5,NULL,NULL,20,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-2.jpg\",\"products\\/63-1.jpg\"]'),(329,9,NULL,NULL,11,1,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/16.jpg\",\"products\\/41.jpg\",\"products\\/55-1.jpg\"]'),(330,4,NULL,NULL,13,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\",\"products\\/22.jpg\",\"products\\/58-2.jpg\"]'),(331,8,NULL,NULL,41,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-1.jpg\",\"products\\/31-1.jpg\",\"products\\/51.jpg\"]'),(332,8,NULL,NULL,25,4,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-1.jpg\",\"products\\/64-1.jpg\"]'),(334,6,NULL,NULL,64,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\",\"products\\/23.jpg\",\"products\\/36-2.jpg\",\"products\\/38.jpg\"]'),(335,3,NULL,NULL,21,4,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/26-1.jpg\"]'),(336,10,NULL,NULL,20,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/16.jpg\",\"products\\/36.jpg\",\"products\\/41-2.jpg\",\"products\\/44-2.jpg\"]'),(337,5,NULL,NULL,54,3,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\"]'),(338,2,NULL,NULL,6,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20.jpg\",\"products\\/23.jpg\"]'),(339,2,NULL,NULL,17,5,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/17-1.jpg\",\"products\\/57-1.jpg\"]'),(340,3,NULL,NULL,11,3,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\"]'),(341,10,NULL,NULL,26,1,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/36-1.jpg\",\"products\\/53.jpg\"]'),(342,5,NULL,NULL,43,5,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/32.jpg\",\"products\\/52-2.jpg\",\"products\\/59-1.jpg\",\"products\\/59.jpg\"]'),(344,8,NULL,NULL,13,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-3.jpg\",\"products\\/34-1.jpg\"]'),(345,4,NULL,NULL,38,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/48-1.jpg\"]'),(348,5,NULL,NULL,56,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-2.jpg\",\"products\\/46-1.jpg\"]'),(350,7,NULL,NULL,54,2,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\",\"products\\/12-2.jpg\",\"products\\/16.jpg\",\"products\\/54.jpg\"]'),(351,3,NULL,NULL,34,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/62.jpg\"]'),(354,10,NULL,NULL,57,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/35-1.jpg\",\"products\\/38-1.jpg\"]'),(357,9,NULL,NULL,53,1,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22.jpg\",\"products\\/29-1.jpg\",\"products\\/54.jpg\"]'),(358,10,NULL,NULL,64,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44.jpg\"]'),(360,2,NULL,NULL,7,2,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-2.jpg\",\"products\\/52-1.jpg\",\"products\\/58-1.jpg\",\"products\\/62.jpg\"]'),(361,6,NULL,NULL,40,4,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-2.jpg\"]'),(362,1,NULL,NULL,21,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\",\"products\\/39.jpg\"]'),(363,1,NULL,NULL,45,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/52-1.jpg\"]'),(364,1,NULL,NULL,60,2,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-2.jpg\",\"products\\/17-3.jpg\",\"products\\/53-1.jpg\"]'),(365,7,NULL,NULL,13,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-3.jpg\",\"products\\/37.jpg\",\"products\\/48.jpg\"]'),(366,7,NULL,NULL,25,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\",\"products\\/40-1.jpg\"]'),(369,9,NULL,NULL,5,1,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-2.jpg\",\"products\\/46-1.jpg\",\"products\\/62.jpg\"]'),(372,5,NULL,NULL,38,2,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/32-1.jpg\",\"products\\/57.jpg\"]'),(373,9,NULL,NULL,63,1,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/37-2.jpg\"]'),(374,4,NULL,NULL,56,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-1.jpg\",\"products\\/21.jpg\",\"products\\/47.jpg\"]'),(377,3,NULL,NULL,58,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/21-1.jpg\"]'),(379,4,NULL,NULL,4,4,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\"]'),(383,6,NULL,NULL,53,4,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/40.jpg\",\"products\\/58.jpg\"]'),(391,4,NULL,NULL,54,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/42-1.jpg\",\"products\\/56-1.jpg\"]'),(392,6,NULL,NULL,8,3,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/10-1.jpg\",\"products\\/33-2.jpg\",\"products\\/43.jpg\"]'),(393,8,NULL,NULL,52,2,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/45-1.jpg\",\"products\\/49-1.jpg\",\"products\\/65-2.jpg\"]'),(397,10,NULL,NULL,16,1,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\"]'),(398,1,NULL,NULL,4,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\",\"products\\/30-2.jpg\",\"products\\/30.jpg\",\"products\\/33-2.jpg\"]'),(400,5,NULL,NULL,8,2,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/60.jpg\"]'),(402,9,NULL,NULL,15,3,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/6.jpg\",\"products\\/33.jpg\",\"products\\/38.jpg\",\"products\\/54.jpg\"]'),(403,7,NULL,NULL,57,4,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\",\"products\\/19-2.jpg\",\"products\\/29-1.jpg\"]'),(404,2,NULL,NULL,55,2,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/11.jpg\"]'),(405,5,NULL,NULL,24,4,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\"]'),(406,2,NULL,NULL,45,1,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-1.jpg\"]'),(407,5,NULL,NULL,12,1,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-1.jpg\"]'),(408,8,NULL,NULL,58,3,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\",\"products\\/18-3.jpg\",\"products\\/20-2.jpg\"]'),(414,3,NULL,NULL,42,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\",\"products\\/31-1.jpg\",\"products\\/44.jpg\",\"products\\/60-2.jpg\"]'),(415,7,NULL,NULL,38,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\",\"products\\/48-2.jpg\"]'),(416,9,NULL,NULL,3,2,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/52-2.jpg\",\"products\\/52.jpg\"]'),(417,3,NULL,NULL,46,4,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44-1.jpg\"]'),(418,5,NULL,NULL,19,2,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/58-2.jpg\"]'),(421,6,NULL,NULL,44,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-2.jpg\",\"products\\/18.jpg\",\"products\\/38.jpg\"]'),(426,2,NULL,NULL,51,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\",\"products\\/12-2.jpg\",\"products\\/29.jpg\",\"products\\/63.jpg\"]'),(427,5,NULL,NULL,2,1,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/33-1.jpg\",\"products\\/54-1.jpg\"]'),(431,10,NULL,NULL,39,3,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/24.jpg\",\"products\\/49.jpg\"]'),(434,4,NULL,NULL,65,2,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\"]'),(435,8,NULL,NULL,4,1,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/43.jpg\",\"products\\/44-2.jpg\"]'),(436,6,NULL,NULL,32,5,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/5-3.jpg\"]'),(439,9,NULL,NULL,8,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/63.jpg\"]'),(440,7,NULL,NULL,59,1,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-2.jpg\",\"products\\/33-1.jpg\"]'),(445,3,NULL,NULL,22,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/58-1.jpg\"]'),(449,4,NULL,NULL,29,1,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44-2.jpg\",\"products\\/54.jpg\"]'),(450,4,NULL,NULL,21,2,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/42-1.jpg\",\"products\\/46-1.jpg\"]'),(451,5,NULL,NULL,3,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\"]'),(452,6,NULL,NULL,35,2,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/46.jpg\"]'),(453,5,NULL,NULL,7,4,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/24-1.jpg\",\"products\\/25-1.jpg\",\"products\\/30-1.jpg\"]'),(454,6,NULL,NULL,60,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\"]'),(455,4,NULL,NULL,62,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\",\"products\\/41.jpg\"]'),(456,4,NULL,NULL,9,3,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\",\"products\\/9-2.jpg\",\"products\\/19-3.jpg\",\"products\\/57.jpg\"]'),(457,9,NULL,NULL,58,1,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/34.jpg\"]'),(458,3,NULL,NULL,44,4,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/16.jpg\",\"products\\/24.jpg\",\"products\\/44-1.jpg\"]'),(462,7,NULL,NULL,10,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\"]'),(463,2,NULL,NULL,50,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2.jpg\",\"products\\/48-2.jpg\"]'),(464,7,NULL,NULL,39,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\"]'),(465,2,NULL,NULL,38,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\"]'),(469,8,NULL,NULL,32,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-1.jpg\",\"products\\/48-2.jpg\"]'),(476,10,NULL,NULL,21,1,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/37-2.jpg\",\"products\\/47-1.jpg\"]'),(480,7,NULL,NULL,12,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28-1.jpg\",\"products\\/30.jpg\",\"products\\/58-1.jpg\",\"products\\/64-1.jpg\"]'),(481,10,NULL,NULL,3,3,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/37.jpg\",\"products\\/47.jpg\"]'),(489,7,NULL,NULL,42,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44-2.jpg\",\"products\\/48.jpg\"]'),(501,4,NULL,NULL,61,3,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-2.jpg\",\"products\\/25.jpg\",\"products\\/38.jpg\",\"products\\/65-2.jpg\"]'),(506,8,NULL,NULL,37,2,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/12-1.jpg\"]'),(507,7,NULL,NULL,20,5,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\"]'),(509,8,NULL,NULL,26,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41.jpg\"]'),(510,10,NULL,NULL,46,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/25.jpg\",\"products\\/36-2.jpg\",\"products\\/52.jpg\"]'),(512,8,NULL,NULL,27,3,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28-1.jpg\",\"products\\/31.jpg\"]'),(513,4,NULL,NULL,20,2,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\",\"products\\/19-3.jpg\",\"products\\/23-1.jpg\"]'),(515,9,NULL,NULL,31,4,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-3.jpg\",\"products\\/30-1.jpg\",\"products\\/31.jpg\"]'),(516,2,NULL,NULL,54,4,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/11-3.jpg\",\"products\\/30-2.jpg\",\"products\\/52-2.jpg\"]'),(517,2,NULL,NULL,8,5,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/44.jpg\",\"products\\/46-1.jpg\",\"products\\/63-1.jpg\"]'),(518,1,NULL,NULL,17,5,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\",\"products\\/24.jpg\",\"products\\/26-1.jpg\"]'),(519,7,NULL,NULL,22,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\"]'),(522,9,NULL,NULL,43,5,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-1.jpg\",\"products\\/21.jpg\",\"products\\/61-1.jpg\"]'),(523,3,NULL,NULL,48,4,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-2.jpg\",\"products\\/57-1.jpg\"]'),(524,2,NULL,NULL,65,1,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/6.jpg\",\"products\\/59-2.jpg\"]'),(526,7,NULL,NULL,50,3,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-3.jpg\",\"products\\/23-2.jpg\"]'),(527,6,NULL,NULL,49,3,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/61-1.jpg\",\"products\\/62.jpg\",\"products\\/63.jpg\"]'),(528,3,NULL,NULL,17,3,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/17-2.jpg\",\"products\\/30-2.jpg\",\"products\\/45.jpg\"]'),(531,1,NULL,NULL,26,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/39.jpg\"]'),(532,10,NULL,NULL,62,2,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/12-2.jpg\",\"products\\/18-1.jpg\",\"products\\/62.jpg\"]'),(536,7,NULL,NULL,47,5,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/56-1.jpg\"]'),(537,6,NULL,NULL,57,5,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/11-2.jpg\",\"products\\/55-1.jpg\"]'),(543,3,NULL,NULL,24,3,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/59.jpg\"]'),(545,7,NULL,NULL,56,1,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/41-1.jpg\"]'),(547,7,NULL,NULL,30,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/37.jpg\"]'),(552,8,NULL,NULL,46,4,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\"]'),(553,3,NULL,NULL,30,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\"]'),(560,10,NULL,NULL,13,3,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-1.jpg\"]'),(563,10,NULL,NULL,5,5,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-3.jpg\",\"products\\/24-2.jpg\",\"products\\/25.jpg\",\"products\\/59-2.jpg\"]'),(566,4,NULL,NULL,39,1,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-1.jpg\",\"products\\/45-1.jpg\"]'),(575,8,NULL,NULL,47,2,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-1.jpg\",\"products\\/30-2.jpg\",\"products\\/62-1.jpg\",\"products\\/64-1.jpg\"]'),(577,8,NULL,NULL,61,5,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-3.jpg\",\"products\\/38.jpg\"]'),(578,10,NULL,NULL,31,1,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-2.jpg\",\"products\\/33.jpg\",\"products\\/34-1.jpg\",\"products\\/55-2.jpg\"]'),(581,8,NULL,NULL,33,1,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-2.jpg\",\"products\\/29-1.jpg\",\"products\\/55-2.jpg\"]'),(583,4,NULL,NULL,30,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\"]'),(585,3,NULL,NULL,60,5,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\"]'),(587,6,NULL,NULL,50,2,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/63.jpg\",\"products\\/64.jpg\"]'),(590,6,NULL,NULL,28,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/36.jpg\",\"products\\/60.jpg\"]'),(595,2,NULL,NULL,49,5,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\",\"products\\/42.jpg\"]'),(596,4,NULL,NULL,44,2,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/15-1.jpg\",\"products\\/17-2.jpg\",\"products\\/23-1.jpg\"]'),(599,1,NULL,NULL,35,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\",\"products\\/45.jpg\",\"products\\/62-1.jpg\",\"products\\/63.jpg\"]'),(600,3,NULL,NULL,56,2,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\"]'),(601,6,NULL,NULL,58,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\"]'),(604,10,NULL,NULL,30,5,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/30-2.jpg\",\"products\\/43-1.jpg\"]'),(605,2,NULL,NULL,12,4,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-1.jpg\",\"products\\/8-2.jpg\",\"products\\/63-1.jpg\",\"products\\/64.jpg\"]'),(606,1,NULL,NULL,30,3,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17.jpg\",\"products\\/22-2.jpg\",\"products\\/53-1.jpg\"]'),(610,5,NULL,NULL,51,2,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/26-1.jpg\",\"products\\/33-1.jpg\",\"products\\/61-1.jpg\"]'),(616,7,NULL,NULL,5,2,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-1.jpg\",\"products\\/33-1.jpg\",\"products\\/35-1.jpg\",\"products\\/44-1.jpg\"]'),(618,1,NULL,NULL,14,2,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19.jpg\",\"products\\/52-2.jpg\",\"products\\/55.jpg\"]'),(619,2,NULL,NULL,59,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\"]'),(621,10,NULL,NULL,34,1,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\",\"products\\/47-1.jpg\"]'),(622,1,NULL,NULL,13,3,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21-1.jpg\",\"products\\/29-1.jpg\",\"products\\/49.jpg\"]'),(623,6,NULL,NULL,34,1,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33-2.jpg\",\"products\\/47.jpg\",\"products\\/48.jpg\"]'),(624,5,NULL,NULL,36,1,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21.jpg\",\"products\\/22-2.jpg\"]'),(628,10,NULL,NULL,53,3,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/32-2.jpg\"]'),(630,3,NULL,NULL,6,4,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/15.jpg\",\"products\\/52-1.jpg\"]'),(631,8,NULL,NULL,50,4,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/31.jpg\",\"products\\/62-1.jpg\"]'),(632,8,NULL,NULL,22,2,'Second or third time that I buy a Botble product, happy with the products and support. You guys do a good job :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/18-1.jpg\",\"products\\/30-2.jpg\",\"products\\/64-1.jpg\"]'),(635,9,NULL,NULL,45,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-1.jpg\",\"products\\/23.jpg\"]'),(644,10,NULL,NULL,41,5,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33.jpg\"]'),(647,10,NULL,NULL,18,5,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\",\"products\\/20.jpg\",\"products\\/43-2.jpg\"]'),(652,4,NULL,NULL,22,2,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\",\"products\\/42-1.jpg\"]'),(653,3,NULL,NULL,62,5,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-2.jpg\",\"products\\/23-3.jpg\",\"products\\/39.jpg\",\"products\\/63-1.jpg\"]'),(654,1,NULL,NULL,37,3,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/7.jpg\",\"products\\/35.jpg\"]'),(655,1,NULL,NULL,24,3,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-3.jpg\",\"products\\/36-1.jpg\",\"products\\/63.jpg\"]'),(657,10,NULL,NULL,37,2,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-2.jpg\",\"products\\/33-1.jpg\",\"products\\/44-2.jpg\",\"products\\/52-1.jpg\"]'),(659,8,NULL,NULL,3,2,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\",\"products\\/49.jpg\",\"products\\/63.jpg\"]'),(665,6,NULL,NULL,65,5,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/21.jpg\",\"products\\/54.jpg\"]'),(666,9,NULL,NULL,4,2,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/17.jpg\",\"products\\/31.jpg\",\"products\\/50-1.jpg\"]'),(667,7,NULL,NULL,18,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-3.jpg\",\"products\\/29-2.jpg\"]'),(668,1,NULL,NULL,48,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/11.jpg\",\"products\\/39-1.jpg\",\"products\\/46.jpg\"]'),(669,4,NULL,NULL,7,4,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25-2.jpg\"]'),(672,1,NULL,NULL,28,3,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/32-1.jpg\",\"products\\/64.jpg\"]'),(676,5,NULL,NULL,21,3,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29.jpg\",\"products\\/34-1.jpg\",\"products\\/40-1.jpg\"]'),(678,10,NULL,NULL,48,4,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/48-1.jpg\",\"products\\/52-2.jpg\",\"products\\/55-1.jpg\"]'),(680,8,NULL,NULL,56,2,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9.jpg\",\"products\\/41-1.jpg\",\"products\\/52-2.jpg\",\"products\\/59-1.jpg\"]'),(687,10,NULL,NULL,59,1,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29.jpg\"]'),(691,6,NULL,NULL,61,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33-1.jpg\",\"products\\/45-1.jpg\",\"products\\/59-3.jpg\",\"products\\/62.jpg\"]'),(697,8,NULL,NULL,19,4,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/38.jpg\",\"products\\/56.jpg\",\"products\\/59-3.jpg\"]'),(698,2,NULL,NULL,53,5,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/53.jpg\"]'),(700,5,NULL,NULL,50,5,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\"]'),(703,4,NULL,NULL,17,5,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-1.jpg\"]'),(704,8,NULL,NULL,65,2,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/48-1.jpg\",\"products\\/52.jpg\",\"products\\/62.jpg\",\"products\\/65.jpg\"]'),(707,4,NULL,NULL,31,5,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/65.jpg\"]'),(708,9,NULL,NULL,44,2,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/13.jpg\",\"products\\/18-2.jpg\",\"products\\/51.jpg\"]'),(709,6,NULL,NULL,63,3,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\",\"products\\/13.jpg\",\"products\\/25.jpg\"]'),(710,1,NULL,NULL,18,3,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-2.jpg\",\"products\\/18-3.jpg\",\"products\\/49-2.jpg\"]'),(711,6,NULL,NULL,2,1,'It\'s not my first experience here on Codecanyon and I can honestly tell you all that Botble puts a LOT of effort into the support. They answer so fast, they helped me tons of times. REALLY by far THE BEST EXPERIENCE on Codecanyon. Those guys at Botble are so good that they deserve 5 stars. I recommend them, I trust them and I can\'t wait to see what they will sell in a near future. Thank you Botble :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28.jpg\",\"products\\/47-1.jpg\"]'),(714,3,NULL,NULL,8,1,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18.jpg\"]'),(725,5,NULL,NULL,48,2,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/21.jpg\"]'),(727,7,NULL,NULL,15,5,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/65-2.jpg\"]'),(728,3,NULL,NULL,49,2,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\"]'),(729,1,NULL,NULL,46,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\"]'),(730,5,NULL,NULL,15,4,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\"]'),(734,2,NULL,NULL,30,3,'This web app is really good in design, code quality & features. Besides, the customer support provided by the Botble team was really fast & helpful. You guys are awesome!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-3.jpg\",\"products\\/16.jpg\",\"products\\/18-1.jpg\",\"products\\/45-1.jpg\"]'),(736,2,NULL,NULL,44,1,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/27.jpg\"]'),(739,7,NULL,NULL,62,4,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/13-1.jpg\",\"products\\/37-1.jpg\"]'),(740,10,NULL,NULL,56,2,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-2.jpg\",\"products\\/6.jpg\",\"products\\/16.jpg\",\"products\\/62.jpg\"]'),(747,8,NULL,NULL,48,1,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/20-1.jpg\",\"products\\/44-1.jpg\"]'),(748,8,NULL,NULL,39,2,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\",\"products\\/55-2.jpg\",\"products\\/63-1.jpg\"]'),(750,6,NULL,NULL,30,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/37.jpg\",\"products\\/53-1.jpg\",\"products\\/64.jpg\"]'),(751,4,NULL,NULL,18,4,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-1.jpg\",\"products\\/38-2.jpg\",\"products\\/47-1.jpg\",\"products\\/55.jpg\"]'),(753,8,NULL,NULL,11,4,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\"]'),(755,8,NULL,NULL,17,2,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\",\"products\\/37-1.jpg\",\"products\\/58-2.jpg\",\"products\\/63.jpg\"]'),(757,6,NULL,NULL,42,2,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\"]'),(766,4,NULL,NULL,43,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/5-2.jpg\",\"products\\/11.jpg\",\"products\\/18.jpg\"]'),(770,1,NULL,NULL,29,3,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12.jpg\"]'),(771,8,NULL,NULL,64,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\",\"products\\/18-2.jpg\",\"products\\/22-1.jpg\"]'),(778,4,NULL,NULL,57,2,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\",\"products\\/11.jpg\",\"products\\/53-1.jpg\"]'),(782,2,NULL,NULL,21,1,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-2.jpg\",\"products\\/19.jpg\",\"products\\/33.jpg\",\"products\\/41.jpg\"]'),(785,1,NULL,NULL,47,1,'Those guys now what they are doing, the release such a good product that it\'s a pleasure to work with ! Even when I was stuck on the project, I created a ticket and the next day it was replied by the team. GOOD JOB guys. I love working with them :)','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-2.jpg\"]'),(791,6,NULL,NULL,36,5,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-2.jpg\",\"products\\/63-1.jpg\"]'),(795,8,NULL,NULL,6,1,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-2.jpg\"]'),(804,4,NULL,NULL,41,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/8-1.jpg\",\"products\\/41-2.jpg\",\"products\\/45.jpg\"]'),(808,5,NULL,NULL,42,2,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-3.jpg\",\"products\\/11-3.jpg\",\"products\\/12-3.jpg\",\"products\\/38-3.jpg\"]'),(812,8,NULL,NULL,21,5,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/22-2.jpg\",\"products\\/24-2.jpg\",\"products\\/61.jpg\"]'),(814,1,NULL,NULL,42,5,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-2.jpg\",\"products\\/38-2.jpg\",\"products\\/58.jpg\",\"products\\/63.jpg\"]'),(822,5,NULL,NULL,58,3,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/28-1.jpg\"]'),(824,4,NULL,NULL,52,4,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/29-2.jpg\",\"products\\/48.jpg\"]'),(825,4,NULL,NULL,12,1,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\",\"products\\/20-3.jpg\",\"products\\/57-1.jpg\"]'),(837,7,NULL,NULL,11,5,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/22-2.jpg\",\"products\\/47.jpg\",\"products\\/58-2.jpg\"]'),(841,5,NULL,NULL,39,4,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13-1.jpg\",\"products\\/28-1.jpg\",\"products\\/55-1.jpg\"]'),(843,5,NULL,NULL,28,3,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/30-2.jpg\",\"products\\/52-2.jpg\"]'),(848,4,NULL,NULL,19,3,'Perfect +++++++++ i love it really also i get to fast ticket answers... Thanks Lot BOTBLE Teams','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4.jpg\",\"products\\/17-3.jpg\",\"products\\/24-2.jpg\",\"products\\/48-1.jpg\"]'),(849,7,NULL,NULL,31,1,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/27-1.jpg\",\"products\\/28.jpg\",\"products\\/42-1.jpg\"]'),(852,2,NULL,NULL,61,4,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/33.jpg\",\"products\\/49-2.jpg\"]'),(855,2,NULL,NULL,43,4,'Customer Support are grade (A*), however the code is a way too over engineered for it\'s purpose.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/51.jpg\",\"products\\/52-1.jpg\",\"products\\/54.jpg\"]'),(856,3,NULL,NULL,14,5,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13.jpg\",\"products\\/32.jpg\"]'),(858,9,NULL,NULL,21,2,'The code is good, in general, if you like it, can you give it 5 stars?','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/62-1.jpg\"]'),(859,9,NULL,NULL,36,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-2.jpg\",\"products\\/43-2.jpg\",\"products\\/46.jpg\",\"products\\/55-1.jpg\"]'),(861,1,NULL,NULL,22,4,'The script is the best of its class, fast, easy to implement and work with , and the most important thing is the great support team , Recommend with no doubt.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-3.jpg\",\"products\\/39.jpg\",\"products\\/52-2.jpg\"]'),(862,4,NULL,NULL,50,4,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-3.jpg\",\"products\\/36.jpg\"]'),(864,9,NULL,NULL,32,2,'Very enthusiastic support! Excellent code is written. It\'s a true pleasure working with.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23-1.jpg\",\"products\\/36.jpg\",\"products\\/41-2.jpg\",\"products\\/52-1.jpg\"]'),(865,7,NULL,NULL,43,1,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/57.jpg\"]'),(866,2,NULL,NULL,47,2,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/9-2.jpg\",\"products\\/55.jpg\",\"products\\/62-1.jpg\"]'),(875,9,NULL,NULL,47,4,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/16.jpg\"]'),(877,5,NULL,NULL,33,5,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-3.jpg\",\"products\\/47-1.jpg\"]'),(883,8,NULL,NULL,5,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-2.jpg\"]'),(884,10,NULL,NULL,35,1,'Solution is too robust for our purpose so we didn\'t use it at the end. But I appreciate customer support during initial configuration.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\",\"products\\/27-1.jpg\"]'),(886,6,NULL,NULL,15,4,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-3.jpg\",\"products\\/5.jpg\",\"products\\/25-1.jpg\",\"products\\/29-1.jpg\"]'),(888,5,NULL,NULL,23,5,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/56-2.jpg\"]'),(890,2,NULL,NULL,64,2,'As a developer I reviewed this script. This is really awesome ecommerce script. I have convinced when I noticed that it\'s built on fully WordPress concept.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/3.jpg\",\"products\\/42.jpg\",\"products\\/64.jpg\"]'),(891,5,NULL,NULL,14,2,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\",\"products\\/13.jpg\",\"products\\/34-1.jpg\",\"products\\/59.jpg\"]'),(904,8,NULL,NULL,15,1,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/53.jpg\"]'),(910,1,NULL,NULL,52,1,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5.jpg\",\"products\\/57.jpg\"]'),(913,10,NULL,NULL,47,1,'Cool template. Excellent code quality. The support responds very quickly, which is very rare on themeforest and codecanyon.net, I buy a lot of templates, and everyone will have a response from technical support for two or three days. Thanks to tech support. I recommend to buy.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8.jpg\",\"products\\/12-1.jpg\",\"products\\/42-1.jpg\"]'),(917,6,NULL,NULL,23,2,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/10-1.jpg\"]'),(920,5,NULL,NULL,64,5,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-3.jpg\"]'),(926,9,NULL,NULL,37,3,'Ok good product. I have some issues in customizations. But its not correct to blame the developer. The product is good. Good luck for your business.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/24-2.jpg\"]'),(928,6,NULL,NULL,21,2,'Great E-commerce system. And much more : Wonderful Customer Support.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/15-1.jpg\",\"products\\/48.jpg\",\"products\\/59-1.jpg\"]'),(931,4,NULL,NULL,63,1,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/18-1.jpg\"]'),(932,9,NULL,NULL,61,5,'Amazing code, amazing support. Overall, im really confident in Botble and im happy I made the right choice! Thank you so much guys for coding this masterpiece','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/14.jpg\"]'),(933,6,NULL,NULL,41,4,'These guys are amazing! Responses immediately, amazing support and help... I immediately feel at ease after Purchasing..','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/25-1.jpg\"]'),(942,5,NULL,NULL,46,3,'Great system, great support, good job Botble. I\'m looking forward to more great functional plugins.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/1.jpg\",\"products\\/21-1.jpg\",\"products\\/27.jpg\",\"products\\/52-1.jpg\"]'),(947,2,NULL,NULL,33,5,'Good app, good backup service and support. Good documentation.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/13.jpg\",\"products\\/20-3.jpg\",\"products\\/40-1.jpg\"]'),(952,10,NULL,NULL,22,5,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/12-3.jpg\",\"products\\/19-3.jpg\",\"products\\/43-2.jpg\"]'),(955,1,NULL,NULL,20,5,'For me the best eCommerce script on Envato at this moment: modern, clean code, a lot of great features. The customer support is great too: I always get an answer within hours!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/2-1.jpg\",\"products\\/18-2.jpg\",\"products\\/64-1.jpg\",\"products\\/65-2.jpg\"]'),(958,8,NULL,NULL,53,5,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/5-1.jpg\",\"products\\/27.jpg\",\"products\\/42-1.jpg\"]'),(963,5,NULL,NULL,32,1,'The best store template! Excellent coding! Very good support! Thank you so much for all the help, I really appreciated.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/36.jpg\",\"products\\/61.jpg\"]'),(975,2,NULL,NULL,23,2,'I Love this Script. I also found how to add other fees. Now I just wait the BIG update for the Marketplace with the Bulk Import. Just do not forget to make it to be Multi-language for us the Botble Fans.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/17-2.jpg\"]'),(977,5,NULL,NULL,26,4,'This script is well coded and is super fast. The support is pretty quick. Very patient and helpful team. I strongly recommend it and they deserve more than 5 stars.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/4-2.jpg\",\"products\\/21-2.jpg\",\"products\\/53.jpg\"]'),(986,7,NULL,NULL,51,4,'The best ecommerce CMS! Excellent coding! best support service! Thank you so much..... I really like your hard work.','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/8-2.jpg\",\"products\\/52.jpg\",\"products\\/64.jpg\"]'),(991,1,NULL,NULL,8,2,'Clean & perfect source code','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/23.jpg\"]'),(993,7,NULL,NULL,52,4,'We have received brilliant service support and will be expanding the features with the developer. Nice product!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/29-2.jpg\"]'),(999,8,NULL,NULL,49,5,'Best ecommerce CMS online store!','published','2025-09-11 01:46:34','2025-09-11 01:46:34','[\"products\\/19-1.jpg\",\"products\\/47-1.jpg\"]');
/*!40000 ALTER TABLE `ec_reviews` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shared_wishlists`
--

DROP TABLE IF EXISTS `ec_shared_wishlists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shared_wishlists` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_ids` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ec_shared_wishlists_code_unique` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shared_wishlists`
--

LOCK TABLES `ec_shared_wishlists` WRITE;
/*!40000 ALTER TABLE `ec_shared_wishlists` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_shared_wishlists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shipment_histories`
--

DROP TABLE IF EXISTS `ec_shipment_histories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shipment_histories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `shipment_id` bigint unsigned NOT NULL,
  `order_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'Botble\\ACL\\Models\\User',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shipment_histories`
--

LOCK TABLES `ec_shipment_histories` WRITE;
/*!40000 ALTER TABLE `ec_shipment_histories` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_shipment_histories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shipments`
--

DROP TABLE IF EXISTS `ec_shipments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shipments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `weight` double DEFAULT '0',
  `shipment_id` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rate_id` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `note` text COLLATE utf8mb4_unicode_ci,
  `status` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cod_amount` decimal(15,2) DEFAULT '0.00',
  `cod_status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `cross_checking_status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `price` decimal(15,2) DEFAULT '0.00',
  `store_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tracking_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_company_name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tracking_link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `estimate_date_shipped` datetime DEFAULT NULL,
  `date_shipped` datetime DEFAULT NULL,
  `customer_delivered_confirmed_at` timestamp NULL DEFAULT NULL,
  `label_url` text COLLATE utf8mb4_unicode_ci,
  `metadata` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shipments`
--

LOCK TABLES `ec_shipments` WRITE;
/*!40000 ALTER TABLE `ec_shipments` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_shipments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shipping`
--

DROP TABLE IF EXISTS `ec_shipping`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shipping` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shipping`
--

LOCK TABLES `ec_shipping` WRITE;
/*!40000 ALTER TABLE `ec_shipping` DISABLE KEYS */;
INSERT INTO `ec_shipping` VALUES (1,'All',NULL,'2025-09-11 01:45:59','2025-09-11 01:45:59');
/*!40000 ALTER TABLE `ec_shipping` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shipping_rule_items`
--

DROP TABLE IF EXISTS `ec_shipping_rule_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shipping_rule_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `shipping_rule_id` bigint unsigned NOT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adjustment_price` decimal(15,2) DEFAULT '0.00',
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shipping_rule_items`
--

LOCK TABLES `ec_shipping_rule_items` WRITE;
/*!40000 ALTER TABLE `ec_shipping_rule_items` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_shipping_rule_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_shipping_rules`
--

DROP TABLE IF EXISTS `ec_shipping_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_shipping_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_id` bigint unsigned NOT NULL,
  `type` varchar(24) COLLATE utf8mb4_unicode_ci DEFAULT 'based_on_price',
  `from` decimal(15,2) DEFAULT '0.00',
  `to` decimal(15,2) DEFAULT '0.00',
  `price` decimal(15,2) DEFAULT '0.00',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_shipping_rules`
--

LOCK TABLES `ec_shipping_rules` WRITE;
/*!40000 ALTER TABLE `ec_shipping_rules` DISABLE KEYS */;
INSERT INTO `ec_shipping_rules` VALUES (1,'Free delivery',1,'based_on_price',1000.00,NULL,0.00,'2025-09-11 01:45:59','2025-09-11 01:45:59'),(2,'Flat Rate',1,'based_on_price',0.00,NULL,20.00,'2025-09-11 01:45:59','2025-09-11 01:45:59'),(3,'Local Pickup',1,'based_on_price',0.00,NULL,0.00,'2025-09-11 01:45:59','2025-09-11 01:45:59');
/*!40000 ALTER TABLE `ec_shipping_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_attributes`
--

DROP TABLE IF EXISTS `ec_specification_attributes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_attributes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `group_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `default_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_specification_attributes_author_type_author_id_index` (`author_type`,`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_attributes`
--

LOCK TABLES `ec_specification_attributes` WRITE;
/*!40000 ALTER TABLE `ec_specification_attributes` DISABLE KEYS */;
INSERT INTO `ec_specification_attributes` VALUES (1,1,'Height','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(2,1,'Width','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(3,1,'Weight','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(4,2,'Power','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(5,2,'Speed','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(6,3,'Battery Life','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(7,4,'Screen Size','text',NULL,NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(8,4,'Resolution','select','[\"1920x1080\",\"2560x1440\",\"3840x2160\"]',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL);
/*!40000 ALTER TABLE `ec_specification_attributes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_attributes_translations`
--

DROP TABLE IF EXISTS `ec_specification_attributes_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_attributes_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_specification_attributes_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `default_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_specification_attributes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_attributes_translations`
--

LOCK TABLES `ec_specification_attributes_translations` WRITE;
/*!40000 ALTER TABLE `ec_specification_attributes_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_specification_attributes_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_groups`
--

DROP TABLE IF EXISTS `ec_specification_groups`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_groups` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_specification_groups_author_type_author_id_index` (`author_type`,`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_groups`
--

LOCK TABLES `ec_specification_groups` WRITE;
/*!40000 ALTER TABLE `ec_specification_groups` DISABLE KEYS */;
INSERT INTO `ec_specification_groups` VALUES (1,'Dimensions',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(2,'Performance',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(3,'Battery',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(4,'Display',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL);
/*!40000 ALTER TABLE `ec_specification_groups` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_groups_translations`
--

DROP TABLE IF EXISTS `ec_specification_groups_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_groups_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_specification_groups_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_specification_groups_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_groups_translations`
--

LOCK TABLES `ec_specification_groups_translations` WRITE;
/*!40000 ALTER TABLE `ec_specification_groups_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_specification_groups_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_table_group`
--

DROP TABLE IF EXISTS `ec_specification_table_group`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_table_group` (
  `table_id` bigint unsigned NOT NULL,
  `group_id` bigint unsigned NOT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  PRIMARY KEY (`table_id`,`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_table_group`
--

LOCK TABLES `ec_specification_table_group` WRITE;
/*!40000 ALTER TABLE `ec_specification_table_group` DISABLE KEYS */;
INSERT INTO `ec_specification_table_group` VALUES (1,1,0),(1,2,0),(2,3,0),(2,4,0);
/*!40000 ALTER TABLE `ec_specification_table_group` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_tables`
--

DROP TABLE IF EXISTS `ec_specification_tables`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_tables` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `ec_specification_tables_author_type_author_id_index` (`author_type`,`author_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_tables`
--

LOCK TABLES `ec_specification_tables` WRITE;
/*!40000 ALTER TABLE `ec_specification_tables` DISABLE KEYS */;
INSERT INTO `ec_specification_tables` VALUES (1,'General Specification',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL),(2,'Technical Specification',NULL,'2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,NULL);
/*!40000 ALTER TABLE `ec_specification_tables` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_specification_tables_translations`
--

DROP TABLE IF EXISTS `ec_specification_tables_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_specification_tables_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_specification_tables_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_specification_tables_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_specification_tables_translations`
--

LOCK TABLES `ec_specification_tables_translations` WRITE;
/*!40000 ALTER TABLE `ec_specification_tables_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_specification_tables_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_store_locators`
--

DROP TABLE IF EXISTS `ec_store_locators`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_store_locators` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_primary` tinyint(1) DEFAULT '0',
  `is_shipping_location` tinyint(1) DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_store_locators`
--

LOCK TABLES `ec_store_locators` WRITE;
/*!40000 ALTER TABLE `ec_store_locators` DISABLE KEYS */;
INSERT INTO `ec_store_locators` VALUES (1,'Farmart','sales@botble.com','1800979769','502 New Street','AU','Brighton VIC','Brighton VIC',1,1,'2025-09-11 01:46:36','2025-09-11 01:46:36',NULL);
/*!40000 ALTER TABLE `ec_store_locators` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_tax_products`
--

DROP TABLE IF EXISTS `ec_tax_products`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_tax_products` (
  `tax_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  PRIMARY KEY (`product_id`,`tax_id`),
  KEY `ec_tax_products_tax_id_index` (`tax_id`),
  KEY `ec_tax_products_product_id_index` (`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_tax_products`
--

LOCK TABLES `ec_tax_products` WRITE;
/*!40000 ALTER TABLE `ec_tax_products` DISABLE KEYS */;
INSERT INTO `ec_tax_products` VALUES (1,4),(1,6),(1,7),(1,9),(1,10),(1,11),(1,15),(1,17),(1,19),(1,25),(1,26),(1,31),(1,36),(1,37),(1,42),(1,49),(1,50),(1,51),(1,56),(1,59),(1,61),(1,65),(2,2),(2,12),(2,13),(2,14),(2,20),(2,21),(2,22),(2,24),(2,29),(2,30),(2,34),(2,35),(2,39),(2,41),(2,43),(2,44),(2,45),(2,48),(2,54),(2,55),(2,57),(2,58),(2,60),(2,63),(2,64),(3,1),(3,3),(3,5),(3,8),(3,16),(3,18),(3,23),(3,27),(3,28),(3,32),(3,33),(3,38),(3,40),(3,46),(3,47),(3,52),(3,53),(3,62);
/*!40000 ALTER TABLE `ec_tax_products` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_tax_rules`
--

DROP TABLE IF EXISTS `ec_tax_rules`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_tax_rules` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tax_id` bigint unsigned NOT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `is_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_tax_rules`
--

LOCK TABLES `ec_tax_rules` WRITE;
/*!40000 ALTER TABLE `ec_tax_rules` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_tax_rules` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_taxes`
--

DROP TABLE IF EXISTS `ec_taxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_taxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percentage` float DEFAULT NULL,
  `priority` int DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_taxes`
--

LOCK TABLES `ec_taxes` WRITE;
/*!40000 ALTER TABLE `ec_taxes` DISABLE KEYS */;
INSERT INTO `ec_taxes` VALUES (1,'VAT',10,1,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(2,'None',0,2,'published','2025-09-11 01:45:59','2025-09-11 01:45:59'),(3,'Import Tax',15,3,'published','2025-09-11 01:45:59','2025-09-11 01:45:59');
/*!40000 ALTER TABLE `ec_taxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_taxes_translations`
--

DROP TABLE IF EXISTS `ec_taxes_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_taxes_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ec_taxes_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`ec_taxes_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_taxes_translations`
--

LOCK TABLES `ec_taxes_translations` WRITE;
/*!40000 ALTER TABLE `ec_taxes_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_taxes_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ec_wish_lists`
--

DROP TABLE IF EXISTS `ec_wish_lists`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `ec_wish_lists` (
  `customer_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`customer_id`,`product_id`),
  KEY `wishlist_relation_index` (`product_id`,`customer_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ec_wish_lists`
--

LOCK TABLES `ec_wish_lists` WRITE;
/*!40000 ALTER TABLE `ec_wish_lists` DISABLE KEYS */;
/*!40000 ALTER TABLE `ec_wish_lists` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_categories`
--

DROP TABLE IF EXISTS `faq_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_categories`
--

LOCK TABLES `faq_categories` WRITE;
/*!40000 ALTER TABLE `faq_categories` DISABLE KEYS */;
INSERT INTO `faq_categories` VALUES (1,'SHIPPING',0,'published','2025-09-11 01:45:50','2025-09-11 01:45:50',NULL),(2,'PAYMENT',1,'published','2025-09-11 01:45:50','2025-09-11 01:45:50',NULL),(3,'ORDER &amp; RETURNS',2,'published','2025-09-11 01:45:50','2025-09-11 01:45:50',NULL);
/*!40000 ALTER TABLE `faq_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faq_categories_translations`
--

DROP TABLE IF EXISTS `faq_categories_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faq_categories_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faq_categories_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`faq_categories_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faq_categories_translations`
--

LOCK TABLES `faq_categories_translations` WRITE;
/*!40000 ALTER TABLE `faq_categories_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `faq_categories_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs`
--

DROP TABLE IF EXISTS `faqs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `question` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `answer` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_id` bigint unsigned NOT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs`
--

LOCK TABLES `faqs` WRITE;
/*!40000 ALTER TABLE `faqs` DISABLE KEYS */;
INSERT INTO `faqs` VALUES (1,'What Shipping Methods Are Available?','Ex Portland Pitchfork irure mustache. Eutra fap before they sold out literally. Aliquip ugh bicycle rights actually mlkshk, seitan squid craft beer tempor.',1,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(2,'Do You Ship Internationally?','Hoodie tote bag mixtape tofu. Typewriter jean shorts wolf quinoa, messenger bag organic freegan cray.',1,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(3,'How Long Will It Take To Get My Package?','Swag slow-carb quinoa VHS typewriter pork belly brunch, paleo single-origin coffee Wes Anderson. Flexitarian Pitchfork forage, literally paleo fap pour-over. Wes Anderson Pinterest YOLO fanny pack meggings, deep v XOXO chambray sustainable slow-carb raw denim church-key fap chillwave Etsy. +1 typewriter kitsch, American Apparel tofu Banksy Vice.',1,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(4,'What Payment Methods Are Accepted?','Fashion axe DIY jean shorts, swag kale chips meh polaroid kogi butcher Wes Anderson chambray next level semiotics gentrify yr. Voluptate photo booth fugiat Vice. Austin sed Williamsburg, ea labore raw denim voluptate cred proident mixtape excepteur mustache. Twee chia photo booth readymade food truck, hoodie roof party swag keytar PBR DIY.',2,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(5,'Is Buying On-Line Safe?','Art party authentic freegan semiotics jean shorts chia cred. Neutra Austin roof party Brooklyn, synth Thundercats swag 8-bit photo booth. Plaid letterpress leggings craft beer meh ethical Pinterest.',2,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(6,'How do I place an Order?','Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb Odd Future seitan normcore. Master cleanse American Apparel gentrify flexitarian beard slow-carb next level. Raw denim polaroid paleo farm-to-table, put a bird on it lo-fi tattooed Wes Anderson Pinterest letterpress. Fingerstache McSweeney’s pour-over, letterpress Schlitz photo booth master cleanse bespoke hashtag chillwave gentrify.',3,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(7,'How Can I Cancel Or Change My Order?','Plaid letterpress leggings craft beer meh ethical Pinterest. Art party authentic freegan semiotics jean shorts chia cred. Neutra Austin roof party Brooklyn, synth Thundercats swag 8-bit photo booth.',3,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(8,'Do I need an account to place an order?','Thundercats swag 8-bit photo booth. Plaid letterpress leggings craft beer meh ethical Pinterest. Twee chia photo booth readymade food truck, hoodie roof party swag keytar PBR DIY. Cray ugh 3 wolf moon fap, fashion axe irony butcher cornhole typewriter chambray VHS banjo street art.',3,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(9,'How Do I Track My Order?','Keytar cray slow-carb, Godard banh mi salvia pour-over. Slow-carb @Odd Future seitan normcore. Master cleanse American Apparel gentrify flexitarian beard slow-carb next level.',3,'published','2025-09-11 01:45:50','2025-09-11 01:45:50'),(10,'How Can I Return a Product?','Kale chips Truffaut Williamsburg, hashtag fixie Pinterest raw denim c hambray drinking vinegar Carles street art Bushwick gastropub. Wolf Tumblr paleo church-key. Plaid food truck Echo Park YOLO bitters hella, direct trade Thundercats leggings quinoa before they sold out. You probably haven’t heard of them wayfarers authentic umami drinking vinegar Pinterest Cosby sweater, fingerstache fap High Life.',3,'published','2025-09-11 01:45:50','2025-09-11 01:45:50');
/*!40000 ALTER TABLE `faqs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `faqs_translations`
--

DROP TABLE IF EXISTS `faqs_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `faqs_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `faqs_id` bigint unsigned NOT NULL,
  `question` text COLLATE utf8mb4_unicode_ci,
  `answer` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`faqs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `faqs_translations`
--

LOCK TABLES `faqs_translations` WRITE;
/*!40000 ALTER TABLE `faqs_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `faqs_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `language_meta`
--

DROP TABLE IF EXISTS `language_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `language_meta` (
  `lang_meta_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_meta_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_meta_origin` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`lang_meta_id`),
  KEY `language_meta_reference_id_index` (`reference_id`),
  KEY `meta_code_index` (`lang_meta_code`),
  KEY `meta_origin_index` (`lang_meta_origin`),
  KEY `meta_reference_type_index` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `language_meta`
--

LOCK TABLES `language_meta` WRITE;
/*!40000 ALTER TABLE `language_meta` DISABLE KEYS */;
INSERT INTO `language_meta` VALUES (1,'en_US','d87a1d82c8a039f15364e37f16ce4039',1,'Botble\\SimpleSlider\\Models\\SimpleSlider'),(2,'en_US','5e54c0129d556d5f9f524252850fa019',1,'Botble\\Menu\\Models\\MenuLocation'),(3,'en_US','31da396ddddefd2e25b73e783fa3e8e7',1,'Botble\\Menu\\Models\\Menu'),(4,'en_US','8b4bf534d3b999c7d09ba05f7555ff26',2,'Botble\\Menu\\Models\\MenuLocation'),(5,'en_US','4923001d7f9cdef8ff8aaa3af3c5027f',2,'Botble\\Menu\\Models\\Menu'),(6,'en_US','e1cf7c5e912615026133064057f29868',3,'Botble\\Menu\\Models\\Menu'),(7,'en_US','df03582a0720e811c76c7cb77ec42557',4,'Botble\\Menu\\Models\\Menu'),(8,'en_US','8fae566ff53cd3a1cfeb587b1b9ad911',5,'Botble\\Menu\\Models\\Menu');
/*!40000 ALTER TABLE `language_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `languages`
--

DROP TABLE IF EXISTS `languages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `languages` (
  `lang_id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `lang_name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_locale` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lang_flag` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lang_is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `lang_order` int NOT NULL DEFAULT '0',
  `lang_is_rtl` tinyint unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`lang_id`),
  KEY `lang_locale_index` (`lang_locale`),
  KEY `lang_code_index` (`lang_code`),
  KEY `lang_is_default_index` (`lang_is_default`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `languages`
--

LOCK TABLES `languages` WRITE;
/*!40000 ALTER TABLE `languages` DISABLE KEYS */;
INSERT INTO `languages` VALUES (1,'English','en','en_US','us',1,0,0);
/*!40000 ALTER TABLE `languages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_files`
--

DROP TABLE IF EXISTS `media_files`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_files` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alt` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `folder_id` bigint unsigned NOT NULL DEFAULT '0',
  `mime_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `size` int NOT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `visibility` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'public',
  PRIMARY KEY (`id`),
  KEY `media_files_user_id_index` (`user_id`),
  KEY `media_files_index` (`folder_id`,`user_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=271 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_files`
--

LOCK TABLES `media_files` WRITE;
/*!40000 ALTER TABLE `media_files` DISABLE KEYS */;
INSERT INTO `media_files` VALUES (1,0,'1','1',1,'image/png',5403,'brands/1.png','[]','2025-09-11 01:45:50','2025-09-11 01:45:50',NULL,'public'),(2,0,'2','2',1,'image/png',5403,'brands/2.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(3,0,'3','3',1,'image/png',5403,'brands/3.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(4,0,'4','4',1,'image/png',5403,'brands/4.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(5,0,'1','1',2,'image/png',5327,'product-categories/1.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(6,0,'2','2',2,'image/png',5327,'product-categories/2.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(7,0,'3','3',2,'image/png',5327,'product-categories/3.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(8,0,'4','4',2,'image/png',5327,'product-categories/4.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(9,0,'5','5',2,'image/png',5327,'product-categories/5.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(10,0,'6','6',2,'image/png',5327,'product-categories/6.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(11,0,'7','7',2,'image/png',5327,'product-categories/7.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(12,0,'8','8',2,'image/png',5327,'product-categories/8.png','[]','2025-09-11 01:45:51','2025-09-11 01:45:51',NULL,'public'),(13,0,'1','1',3,'image/jpeg',9803,'customers/1.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(14,0,'10','10',3,'image/jpeg',9803,'customers/10.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(15,0,'2','2',3,'image/jpeg',9803,'customers/2.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(16,0,'3','3',3,'image/jpeg',9803,'customers/3.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(17,0,'4','4',3,'image/jpeg',9803,'customers/4.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(18,0,'5','5',3,'image/jpeg',9803,'customers/5.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(19,0,'6','6',3,'image/jpeg',9803,'customers/6.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(20,0,'7','7',3,'image/jpeg',9803,'customers/7.jpg','[]','2025-09-11 01:45:53','2025-09-11 01:45:53',NULL,'public'),(21,0,'8','8',3,'image/jpeg',9803,'customers/8.jpg','[]','2025-09-11 01:45:54','2025-09-11 01:45:54',NULL,'public'),(22,0,'9','9',3,'image/jpeg',9803,'customers/9.jpg','[]','2025-09-11 01:45:54','2025-09-11 01:45:54',NULL,'public'),(23,0,'1','1',4,'image/jpeg',9803,'products/1.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(24,0,'10-1','10-1',4,'image/jpeg',9803,'products/10-1.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(25,0,'10-2','10-2',4,'image/jpeg',9803,'products/10-2.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(26,0,'10','10',4,'image/jpeg',9803,'products/10.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(27,0,'11-1','11-1',4,'image/jpeg',9803,'products/11-1.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(28,0,'11-2','11-2',4,'image/jpeg',9803,'products/11-2.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(29,0,'11-3','11-3',4,'image/jpeg',9803,'products/11-3.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(30,0,'11','11',4,'image/jpeg',9803,'products/11.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(31,0,'12-1','12-1',4,'image/jpeg',9803,'products/12-1.jpg','[]','2025-09-11 01:46:00','2025-09-11 01:46:00',NULL,'public'),(32,0,'12-2','12-2',4,'image/jpeg',9803,'products/12-2.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(33,0,'12-3','12-3',4,'image/jpeg',9803,'products/12-3.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(34,0,'12','12',4,'image/jpeg',9803,'products/12.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(35,0,'13-1','13-1',4,'image/jpeg',9803,'products/13-1.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(36,0,'13','13',4,'image/jpeg',9803,'products/13.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(37,0,'14','14',4,'image/jpeg',9803,'products/14.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(38,0,'15-1','15-1',4,'image/jpeg',9803,'products/15-1.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(39,0,'15','15',4,'image/jpeg',9803,'products/15.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(40,0,'16','16',4,'image/jpeg',9803,'products/16.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(41,0,'17-1','17-1',4,'image/jpeg',9803,'products/17-1.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(42,0,'17-2','17-2',4,'image/jpeg',9803,'products/17-2.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(43,0,'17-3','17-3',4,'image/jpeg',9803,'products/17-3.jpg','[]','2025-09-11 01:46:01','2025-09-11 01:46:01',NULL,'public'),(44,0,'17','17',4,'image/jpeg',9803,'products/17.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(45,0,'18-1','18-1',4,'image/jpeg',9803,'products/18-1.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(46,0,'18-2','18-2',4,'image/jpeg',9803,'products/18-2.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(47,0,'18-3','18-3',4,'image/jpeg',9803,'products/18-3.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(48,0,'18','18',4,'image/jpeg',9803,'products/18.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(49,0,'19-1','19-1',4,'image/jpeg',9803,'products/19-1.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(50,0,'19-2','19-2',4,'image/jpeg',9803,'products/19-2.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(51,0,'19-3','19-3',4,'image/jpeg',9803,'products/19-3.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(52,0,'19','19',4,'image/jpeg',9803,'products/19.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(53,0,'2-1','2-1',4,'image/jpeg',9803,'products/2-1.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(54,0,'2-2','2-2',4,'image/jpeg',9803,'products/2-2.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(55,0,'2-3','2-3',4,'image/jpeg',9803,'products/2-3.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(56,0,'2','2',4,'image/jpeg',9803,'products/2.jpg','[]','2025-09-11 01:46:02','2025-09-11 01:46:02',NULL,'public'),(57,0,'20-1','20-1',4,'image/jpeg',9803,'products/20-1.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(58,0,'20-2','20-2',4,'image/jpeg',9803,'products/20-2.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(59,0,'20-3','20-3',4,'image/jpeg',9803,'products/20-3.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(60,0,'20','20',4,'image/jpeg',9803,'products/20.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(61,0,'21-1','21-1',4,'image/jpeg',9803,'products/21-1.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(62,0,'21-2','21-2',4,'image/jpeg',9803,'products/21-2.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(63,0,'21','21',4,'image/jpeg',9803,'products/21.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(64,0,'22-1','22-1',4,'image/jpeg',9803,'products/22-1.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(65,0,'22-2','22-2',4,'image/jpeg',9803,'products/22-2.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(66,0,'22-3','22-3',4,'image/jpeg',9803,'products/22-3.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(67,0,'22','22',4,'image/jpeg',9803,'products/22.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(68,0,'23-1','23-1',4,'image/jpeg',9803,'products/23-1.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(69,0,'23-2','23-2',4,'image/jpeg',9803,'products/23-2.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(70,0,'23-3','23-3',4,'image/jpeg',9803,'products/23-3.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(71,0,'23','23',4,'image/jpeg',9803,'products/23.jpg','[]','2025-09-11 01:46:03','2025-09-11 01:46:03',NULL,'public'),(72,0,'24-1','24-1',4,'image/jpeg',9803,'products/24-1.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(73,0,'24-2','24-2',4,'image/jpeg',9803,'products/24-2.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(74,0,'24','24',4,'image/jpeg',9803,'products/24.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(75,0,'25-1','25-1',4,'image/jpeg',9803,'products/25-1.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(76,0,'25-2','25-2',4,'image/jpeg',9803,'products/25-2.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(77,0,'25','25',4,'image/jpeg',9803,'products/25.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(78,0,'26-1','26-1',4,'image/jpeg',9803,'products/26-1.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(79,0,'26','26',4,'image/jpeg',9803,'products/26.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(80,0,'27-1','27-1',4,'image/jpeg',9803,'products/27-1.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(81,0,'27','27',4,'image/jpeg',9803,'products/27.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(82,0,'28-1','28-1',4,'image/jpeg',9803,'products/28-1.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(83,0,'28-2','28-2',4,'image/jpeg',9803,'products/28-2.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(84,0,'28','28',4,'image/jpeg',9803,'products/28.jpg','[]','2025-09-11 01:46:04','2025-09-11 01:46:04',NULL,'public'),(85,0,'29-1','29-1',4,'image/jpeg',9803,'products/29-1.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(86,0,'29-2','29-2',4,'image/jpeg',9803,'products/29-2.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(87,0,'29','29',4,'image/jpeg',9803,'products/29.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(88,0,'3','3',4,'image/jpeg',9803,'products/3.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(89,0,'30-1','30-1',4,'image/jpeg',9803,'products/30-1.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(90,0,'30-2','30-2',4,'image/jpeg',9803,'products/30-2.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(91,0,'30','30',4,'image/jpeg',9803,'products/30.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(92,0,'31-1','31-1',4,'image/jpeg',9803,'products/31-1.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(93,0,'31','31',4,'image/jpeg',9803,'products/31.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(94,0,'32-1','32-1',4,'image/jpeg',9803,'products/32-1.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(95,0,'32-2','32-2',4,'image/jpeg',9803,'products/32-2.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(96,0,'32','32',4,'image/jpeg',9803,'products/32.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(97,0,'33-1','33-1',4,'image/jpeg',9803,'products/33-1.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(98,0,'33-2','33-2',4,'image/jpeg',9803,'products/33-2.jpg','[]','2025-09-11 01:46:05','2025-09-11 01:46:05',NULL,'public'),(99,0,'33','33',4,'image/jpeg',9803,'products/33.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(100,0,'34-1','34-1',4,'image/jpeg',9803,'products/34-1.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(101,0,'34','34',4,'image/jpeg',9803,'products/34.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(102,0,'35-1','35-1',4,'image/jpeg',9803,'products/35-1.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(103,0,'35','35',4,'image/jpeg',9803,'products/35.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(104,0,'36-1','36-1',4,'image/jpeg',9803,'products/36-1.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(105,0,'36-2','36-2',4,'image/jpeg',9803,'products/36-2.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(106,0,'36','36',4,'image/jpeg',9803,'products/36.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(107,0,'37-1','37-1',4,'image/jpeg',9803,'products/37-1.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(108,0,'37-2','37-2',4,'image/jpeg',9803,'products/37-2.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(109,0,'37','37',4,'image/jpeg',9803,'products/37.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(110,0,'38-1','38-1',4,'image/jpeg',9803,'products/38-1.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(111,0,'38-2','38-2',4,'image/jpeg',9803,'products/38-2.jpg','[]','2025-09-11 01:46:06','2025-09-11 01:46:06',NULL,'public'),(112,0,'38-3','38-3',4,'image/jpeg',9803,'products/38-3.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(113,0,'38','38',4,'image/jpeg',9803,'products/38.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(114,0,'39-1','39-1',4,'image/jpeg',9803,'products/39-1.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(115,0,'39-2','39-2',4,'image/jpeg',9803,'products/39-2.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(116,0,'39','39',4,'image/jpeg',9803,'products/39.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(117,0,'4-1','4-1',4,'image/jpeg',9803,'products/4-1.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(118,0,'4-2','4-2',4,'image/jpeg',9803,'products/4-2.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(119,0,'4-3','4-3',4,'image/jpeg',9803,'products/4-3.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(120,0,'4','4',4,'image/jpeg',9803,'products/4.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(121,0,'40-1','40-1',4,'image/jpeg',9803,'products/40-1.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(122,0,'40','40',4,'image/jpeg',9803,'products/40.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(123,0,'41-1','41-1',4,'image/jpeg',9803,'products/41-1.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(124,0,'41-2','41-2',4,'image/jpeg',9803,'products/41-2.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(125,0,'41','41',4,'image/jpeg',9803,'products/41.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(126,0,'42-1','42-1',4,'image/jpeg',9803,'products/42-1.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(127,0,'42-2','42-2',4,'image/jpeg',9803,'products/42-2.jpg','[]','2025-09-11 01:46:07','2025-09-11 01:46:07',NULL,'public'),(128,0,'42','42',4,'image/jpeg',9803,'products/42.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(129,0,'43-1','43-1',4,'image/jpeg',9803,'products/43-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(130,0,'43-2','43-2',4,'image/jpeg',9803,'products/43-2.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(131,0,'43','43',4,'image/jpeg',9803,'products/43.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(132,0,'44-1','44-1',4,'image/jpeg',9803,'products/44-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(133,0,'44-2','44-2',4,'image/jpeg',9803,'products/44-2.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(134,0,'44','44',4,'image/jpeg',9803,'products/44.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(135,0,'45-1','45-1',4,'image/jpeg',9803,'products/45-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(136,0,'45','45',4,'image/jpeg',9803,'products/45.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(137,0,'46-1','46-1',4,'image/jpeg',9803,'products/46-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(138,0,'46','46',4,'image/jpeg',9803,'products/46.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(139,0,'47-1','47-1',4,'image/jpeg',9803,'products/47-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(140,0,'47','47',4,'image/jpeg',9803,'products/47.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(141,0,'48-1','48-1',4,'image/jpeg',9803,'products/48-1.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(142,0,'48-2','48-2',4,'image/jpeg',9803,'products/48-2.jpg','[]','2025-09-11 01:46:08','2025-09-11 01:46:08',NULL,'public'),(143,0,'48','48',4,'image/jpeg',9803,'products/48.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(144,0,'49-1','49-1',4,'image/jpeg',9803,'products/49-1.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(145,0,'49-2','49-2',4,'image/jpeg',9803,'products/49-2.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(146,0,'49','49',4,'image/jpeg',9803,'products/49.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(147,0,'5-1','5-1',4,'image/jpeg',9803,'products/5-1.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(148,0,'5-2','5-2',4,'image/jpeg',9803,'products/5-2.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(149,0,'5-3','5-3',4,'image/jpeg',9803,'products/5-3.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(150,0,'5','5',4,'image/jpeg',9803,'products/5.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(151,0,'50-1','50-1',4,'image/jpeg',9803,'products/50-1.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(152,0,'50','50',4,'image/jpeg',9803,'products/50.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(153,0,'51','51',4,'image/jpeg',9803,'products/51.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(154,0,'52-1','52-1',4,'image/jpeg',9803,'products/52-1.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(155,0,'52-2','52-2',4,'image/jpeg',9803,'products/52-2.jpg','[]','2025-09-11 01:46:09','2025-09-11 01:46:09',NULL,'public'),(156,0,'52','52',4,'image/jpeg',9803,'products/52.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(157,0,'53-1','53-1',4,'image/jpeg',9803,'products/53-1.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(158,0,'53','53',4,'image/jpeg',9803,'products/53.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(159,0,'54-1','54-1',4,'image/jpeg',9803,'products/54-1.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(160,0,'54','54',4,'image/jpeg',9803,'products/54.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(161,0,'55-1','55-1',4,'image/jpeg',9803,'products/55-1.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(162,0,'55-2','55-2',4,'image/jpeg',9803,'products/55-2.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(163,0,'55','55',4,'image/jpeg',9803,'products/55.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(164,0,'56-1','56-1',4,'image/jpeg',9803,'products/56-1.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(165,0,'56-2','56-2',4,'image/jpeg',9803,'products/56-2.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(166,0,'56','56',4,'image/jpeg',9803,'products/56.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(167,0,'57-1','57-1',4,'image/jpeg',9803,'products/57-1.jpg','[]','2025-09-11 01:46:10','2025-09-11 01:46:10',NULL,'public'),(168,0,'57','57',4,'image/jpeg',9803,'products/57.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(169,0,'58-1','58-1',4,'image/jpeg',9803,'products/58-1.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(170,0,'58-2','58-2',4,'image/jpeg',9803,'products/58-2.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(171,0,'58','58',4,'image/jpeg',9803,'products/58.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(172,0,'59-1','59-1',4,'image/jpeg',9803,'products/59-1.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(173,0,'59-2','59-2',4,'image/jpeg',9803,'products/59-2.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(174,0,'59-3','59-3',4,'image/jpeg',9803,'products/59-3.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(175,0,'59','59',4,'image/jpeg',9803,'products/59.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(176,0,'6','6',4,'image/jpeg',9803,'products/6.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(177,0,'60-1','60-1',4,'image/jpeg',9803,'products/60-1.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(178,0,'60-2','60-2',4,'image/jpeg',9803,'products/60-2.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(179,0,'60','60',4,'image/jpeg',9803,'products/60.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(180,0,'61-1','61-1',4,'image/jpeg',9803,'products/61-1.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(181,0,'61','61',4,'image/jpeg',9803,'products/61.jpg','[]','2025-09-11 01:46:11','2025-09-11 01:46:11',NULL,'public'),(182,0,'62-1','62-1',4,'image/jpeg',9803,'products/62-1.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(183,0,'62','62',4,'image/jpeg',9803,'products/62.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(184,0,'63-1','63-1',4,'image/jpeg',9803,'products/63-1.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(185,0,'63','63',4,'image/jpeg',9803,'products/63.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(186,0,'64-1','64-1',4,'image/jpeg',9803,'products/64-1.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(187,0,'64','64',4,'image/jpeg',9803,'products/64.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(188,0,'65-1','65-1',4,'image/jpeg',9803,'products/65-1.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(189,0,'65-2','65-2',4,'image/jpeg',9803,'products/65-2.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(190,0,'65','65',4,'image/jpeg',9803,'products/65.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(191,0,'7','7',4,'image/jpeg',9803,'products/7.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(192,0,'8-1','8-1',4,'image/jpeg',9803,'products/8-1.jpg','[]','2025-09-11 01:46:12','2025-09-11 01:46:12',NULL,'public'),(193,0,'8-2','8-2',4,'image/jpeg',9803,'products/8-2.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(194,0,'8-3','8-3',4,'image/jpeg',9803,'products/8-3.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(195,0,'8','8',4,'image/jpeg',9803,'products/8.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(196,0,'9-1','9-1',4,'image/jpeg',9803,'products/9-1.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(197,0,'9-2','9-2',4,'image/jpeg',9803,'products/9-2.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(198,0,'9','9',4,'image/jpeg',9803,'products/9.jpg','[]','2025-09-11 01:46:13','2025-09-11 01:46:13',NULL,'public'),(199,0,'1','1',5,'image/png',2691,'stores/1.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(200,0,'10','10',5,'image/png',2691,'stores/10.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(201,0,'11','11',5,'image/png',2691,'stores/11.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(202,0,'12','12',5,'image/png',2691,'stores/12.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(203,0,'13','13',5,'image/png',2691,'stores/13.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(204,0,'14','14',5,'image/png',2691,'stores/14.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(205,0,'15','15',5,'image/png',2691,'stores/15.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(206,0,'16','16',5,'image/png',2732,'stores/16.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(207,0,'17','17',5,'image/png',2732,'stores/17.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(208,0,'2','2',5,'image/png',2691,'stores/2.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(209,0,'3','3',5,'image/png',2691,'stores/3.png','[]','2025-09-11 01:46:36','2025-09-11 01:46:36',NULL,'public'),(210,0,'4','4',5,'image/png',2691,'stores/4.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(211,0,'5','5',5,'image/png',2691,'stores/5.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(212,0,'6','6',5,'image/png',2691,'stores/6.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(213,0,'7','7',5,'image/png',2691,'stores/7.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(214,0,'8','8',5,'image/png',2691,'stores/8.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(215,0,'9','9',5,'image/png',2691,'stores/9.png','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(216,0,'background-1','background-1',5,'image/jpeg',5286,'stores/background-1.jpg','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(217,0,'background-2','background-2',5,'image/jpeg',5286,'stores/background-2.jpg','[]','2025-09-11 01:46:37','2025-09-11 01:46:37',NULL,'public'),(218,0,'1','1',6,'image/jpeg',9803,'news/1.jpg','[]','2025-09-11 01:46:55','2025-09-11 01:46:55',NULL,'public'),(219,0,'10','10',6,'image/jpeg',9803,'news/10.jpg','[]','2025-09-11 01:46:55','2025-09-11 01:46:55',NULL,'public'),(220,0,'11','11',6,'image/jpeg',9803,'news/11.jpg','[]','2025-09-11 01:46:55','2025-09-11 01:46:55',NULL,'public'),(221,0,'2','2',6,'image/jpeg',9803,'news/2.jpg','[]','2025-09-11 01:46:55','2025-09-11 01:46:55',NULL,'public'),(222,0,'3','3',6,'image/jpeg',9803,'news/3.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(223,0,'4','4',6,'image/jpeg',9803,'news/4.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(224,0,'5','5',6,'image/jpeg',9803,'news/5.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(225,0,'6','6',6,'image/jpeg',9803,'news/6.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(226,0,'7','7',6,'image/jpeg',9803,'news/7.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(227,0,'8','8',6,'image/jpeg',9803,'news/8.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(228,0,'9','9',6,'image/jpeg',9803,'news/9.jpg','[]','2025-09-11 01:46:56','2025-09-11 01:46:56',NULL,'public'),(229,0,'01-sm','01-sm',7,'image/jpeg',10737,'sliders/01-sm.jpg','[]','2025-09-11 01:46:57','2025-09-11 01:46:57',NULL,'public'),(230,0,'01','01',7,'image/jpeg',11704,'sliders/01.jpg','[]','2025-09-11 01:46:57','2025-09-11 01:46:57',NULL,'public'),(231,0,'02-sm','02-sm',7,'image/jpeg',10737,'sliders/02-sm.jpg','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(232,0,'02','02',7,'image/jpeg',11704,'sliders/02.jpg','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(233,0,'1','1',8,'image/jpeg',7935,'promotion/1.jpg','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(234,0,'2','2',8,'image/png',14659,'promotion/2.png','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(235,0,'3','3',8,'image/png',10632,'promotion/3.png','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(236,0,'4','4',8,'image/png',10076,'promotion/4.png','[]','2025-09-11 01:46:58','2025-09-11 01:46:58',NULL,'public'),(237,0,'5','5',8,'image/png',12274,'promotion/5.png','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(238,0,'cod','cod',9,'image/png',12121,'payments/cod.png','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(239,0,'bank-transfer','bank-transfer',9,'image/png',29089,'payments/bank-transfer.png','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(240,0,'stripe','stripe',9,'image/webp',7516,'payments/stripe.webp','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(241,0,'paypal','paypal',9,'image/png',3001,'payments/paypal.png','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(242,0,'mollie','mollie',9,'image/png',8968,'payments/mollie.png','[]','2025-09-11 01:46:59','2025-09-11 01:46:59',NULL,'public'),(243,0,'paystack','paystack',9,'image/png',31015,'payments/paystack.png','[]','2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,'public'),(244,0,'razorpay','razorpay',9,'image/png',8489,'payments/razorpay.png','[]','2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,'public'),(245,0,'sslcommerz','sslcommerz',9,'image/png',3482,'payments/sslcommerz.png','[]','2025-09-11 01:47:00','2025-09-11 01:47:00',NULL,'public'),(246,0,'404','404',10,'image/png',31297,'general/404.png','[]','2025-09-11 01:47:09','2025-09-11 01:47:09',NULL,'public'),(247,0,'app-android','app-android',10,'image/png',630,'general/app-android.png','[]','2025-09-11 01:47:09','2025-09-11 01:47:09',NULL,'public'),(248,0,'app-bg','app-bg',10,'image/png',11530,'general/app-bg.png','[]','2025-09-11 01:47:09','2025-09-11 01:47:09',NULL,'public'),(249,0,'app-ios','app-ios',10,'image/png',630,'general/app-ios.png','[]','2025-09-11 01:47:09','2025-09-11 01:47:09',NULL,'public'),(250,0,'background','background',10,'image/jpeg',26676,'general/background.jpg','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(251,0,'blog-bg','blog-bg',10,'image/jpeg',81226,'general/blog-bg.jpg','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(252,0,'coming-soon','coming-soon',10,'image/jpeg',26506,'general/coming-soon.jpg','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(253,0,'facebook','facebook',10,'image/png',732,'general/facebook.png','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(254,0,'favicon','favicon',10,'image/png',6337,'general/favicon.png','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(255,0,'footer-payments','footer-payments',10,'image/png',439,'general/footer-payments.png','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(256,0,'icon-protect','icon-protect',10,'image/png',1677,'general/icon-protect.png','[]','2025-09-11 01:47:10','2025-09-11 01:47:10',NULL,'public'),(257,0,'icon-reload','icon-reload',10,'image/png',1457,'general/icon-reload.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(258,0,'icon-rocket','icon-rocket',10,'image/png',1640,'general/icon-rocket.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(259,0,'icon-support','icon-support',10,'image/png',1854,'general/icon-support.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(260,0,'icon-tag','icon-tag',10,'image/png',1151,'general/icon-tag.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(261,0,'instagram','instagram',10,'image/png',2476,'general/instagram.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(262,0,'logo-light','logo-light',10,'image/png',12206,'general/logo-light.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(263,0,'logo','logo',10,'image/png',18245,'general/logo.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(264,0,'newsletter-popup','newsletter-popup',10,'image/png',17253,'general/newsletter-popup.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(265,0,'open-graph-image','open-graph-image',10,'image/png',406719,'general/open-graph-image.png','[]','2025-09-11 01:47:11','2025-09-11 01:47:11',NULL,'public'),(266,0,'pinterest','pinterest',10,'image/png',2103,'general/pinterest.png','[]','2025-09-11 01:47:12','2025-09-11 01:47:12',NULL,'public'),(267,0,'placeholder','placeholder',10,'image/png',2675,'general/placeholder.png','[]','2025-09-11 01:47:12','2025-09-11 01:47:12',NULL,'public'),(268,0,'slider-bg','slider-bg',10,'image/jpeg',26676,'general/slider-bg.jpg','[]','2025-09-11 01:47:12','2025-09-11 01:47:12',NULL,'public'),(269,0,'twitter','twitter',10,'image/png',1749,'general/twitter.png','[]','2025-09-11 01:47:12','2025-09-11 01:47:12',NULL,'public'),(270,0,'youtube','youtube',10,'image/png',1035,'general/youtube.png','[]','2025-09-11 01:47:12','2025-09-11 01:47:12',NULL,'public');
/*!40000 ALTER TABLE `media_files` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_folders`
--

DROP TABLE IF EXISTS `media_folders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_folders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `color` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_folders_user_id_index` (`user_id`),
  KEY `media_folders_index` (`parent_id`,`user_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_folders`
--

LOCK TABLES `media_folders` WRITE;
/*!40000 ALTER TABLE `media_folders` DISABLE KEYS */;
INSERT INTO `media_folders` VALUES (1,0,'brands',NULL,'brands',0,'2025-09-11 01:45:50','2025-09-11 01:45:50',NULL),(2,0,'product-categories',NULL,'product-categories',0,'2025-09-11 01:45:51','2025-09-11 01:45:51',NULL),(3,0,'customers',NULL,'customers',0,'2025-09-11 01:45:53','2025-09-11 01:45:53',NULL),(4,0,'products',NULL,'products',0,'2025-09-11 01:45:59','2025-09-11 01:45:59',NULL),(5,0,'stores',NULL,'stores',0,'2025-09-11 01:46:36','2025-09-11 01:46:36',NULL),(6,0,'news',NULL,'news',0,'2025-09-11 01:46:55','2025-09-11 01:46:55',NULL),(7,0,'sliders',NULL,'sliders',0,'2025-09-11 01:46:57','2025-09-11 01:46:57',NULL),(8,0,'promotion',NULL,'promotion',0,'2025-09-11 01:46:58','2025-09-11 01:46:58',NULL),(9,0,'payments',NULL,'payments',0,'2025-09-11 01:46:59','2025-09-11 01:46:59',NULL),(10,0,'general',NULL,'general',0,'2025-09-11 01:47:09','2025-09-11 01:47:09',NULL);
/*!40000 ALTER TABLE `media_folders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media_settings`
--

DROP TABLE IF EXISTS `media_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `media_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `media_id` bigint unsigned DEFAULT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media_settings`
--

LOCK TABLES `media_settings` WRITE;
/*!40000 ALTER TABLE `media_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `media_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_locations`
--

DROP TABLE IF EXISTS `menu_locations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_locations` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `location` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_locations_menu_id_created_at_index` (`menu_id`,`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_locations`
--

LOCK TABLES `menu_locations` WRITE;
/*!40000 ALTER TABLE `menu_locations` DISABLE KEYS */;
INSERT INTO `menu_locations` VALUES (1,1,'main-menu','2025-09-11 01:47:09','2025-09-11 01:47:09'),(2,2,'header-navigation','2025-09-11 01:47:09','2025-09-11 01:47:09');
/*!40000 ALTER TABLE `menu_locations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menu_nodes`
--

DROP TABLE IF EXISTS `menu_nodes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menu_nodes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `menu_id` bigint unsigned NOT NULL,
  `parent_id` bigint unsigned NOT NULL DEFAULT '0',
  `reference_id` bigint unsigned DEFAULT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `icon_font` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `css_class` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '_self',
  `has_child` tinyint unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `menu_nodes_menu_id_index` (`menu_id`),
  KEY `menu_nodes_parent_id_index` (`parent_id`),
  KEY `reference_id` (`reference_id`),
  KEY `reference_type` (`reference_type`)
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menu_nodes`
--

LOCK TABLES `menu_nodes` WRITE;
/*!40000 ALTER TABLE `menu_nodes` DISABLE KEYS */;
INSERT INTO `menu_nodes` VALUES (1,1,0,NULL,NULL,'/products/smart-watches','icon icon-tag',0,'Special Prices',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(2,1,0,NULL,NULL,'#',NULL,1,'Pages',NULL,'_self',1,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(3,1,2,2,'Botble\\Page\\Models\\Page','/about-us',NULL,0,'About us',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(4,1,2,3,'Botble\\Page\\Models\\Page','/terms-of-use',NULL,1,'Terms Of Use',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(5,1,2,4,'Botble\\Page\\Models\\Page','/terms-conditions',NULL,2,'Terms & Conditions',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(6,1,2,5,'Botble\\Page\\Models\\Page','/refund-policy',NULL,3,'Refund Policy',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(7,1,2,12,'Botble\\Page\\Models\\Page','/coming-soon',NULL,4,'Coming soon',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(8,1,0,NULL,NULL,'/products',NULL,2,'Shop',NULL,'_self',1,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(9,1,8,NULL,NULL,'/products',NULL,0,'All products',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(10,1,8,15,'Botble\\Ecommerce\\Models\\ProductCategory',NULL,NULL,1,'Products Of Category',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(11,1,8,NULL,NULL,'/products/beat-headphone',NULL,2,'Product Single',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(12,1,0,NULL,NULL,'/stores',NULL,3,'Stores',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(13,1,0,6,'Botble\\Page\\Models\\Page',NULL,NULL,4,'Blog',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(14,1,0,7,'Botble\\Page\\Models\\Page',NULL,NULL,5,'FAQs',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(15,1,0,8,'Botble\\Page\\Models\\Page',NULL,NULL,6,'Contact',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(16,2,0,2,'Botble\\Page\\Models\\Page',NULL,NULL,0,'About Us',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(17,2,0,NULL,NULL,'wishlist',NULL,1,'Wishlist',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(18,2,0,NULL,NULL,'orders/tracking',NULL,2,'Order Tracking',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(19,3,0,3,'Botble\\Page\\Models\\Page',NULL,NULL,0,'Terms Of Use',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(20,3,0,4,'Botble\\Page\\Models\\Page',NULL,NULL,1,'Terms & Conditions',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(21,3,0,5,'Botble\\Page\\Models\\Page',NULL,NULL,2,'Refund Policy',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(22,3,0,7,'Botble\\Page\\Models\\Page',NULL,NULL,3,'FAQs',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(23,3,0,NULL,NULL,'/nothing',NULL,4,'404 Page',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(24,4,0,2,'Botble\\Page\\Models\\Page',NULL,NULL,0,'About us',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(25,4,0,10,'Botble\\Page\\Models\\Page',NULL,NULL,1,'Affiliate',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(26,4,0,11,'Botble\\Page\\Models\\Page',NULL,NULL,2,'Career',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(27,4,0,8,'Botble\\Page\\Models\\Page',NULL,NULL,3,'Contact us',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(28,5,0,6,'Botble\\Page\\Models\\Page',NULL,NULL,0,'Our blog',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(29,5,0,NULL,NULL,'/cart',NULL,1,'Cart',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(30,5,0,NULL,NULL,'/customer/overview',NULL,2,'My account',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09'),(31,5,0,NULL,NULL,'/products',NULL,3,'Shop',NULL,'_self',0,'2025-09-11 01:47:09','2025-09-11 01:47:09');
/*!40000 ALTER TABLE `menu_nodes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `menus`
--

DROP TABLE IF EXISTS `menus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `menus` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `menus_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `menus`
--

LOCK TABLES `menus` WRITE;
/*!40000 ALTER TABLE `menus` DISABLE KEYS */;
INSERT INTO `menus` VALUES (1,'Main menu','main-menu','published','2025-09-11 01:47:09','2025-09-11 01:47:09'),(2,'Header menu','header-menu','published','2025-09-11 01:47:09','2025-09-11 01:47:09'),(3,'Useful Links','useful-links','published','2025-09-11 01:47:09','2025-09-11 01:47:09'),(4,'Help Center','help-center','published','2025-09-11 01:47:09','2025-09-11 01:47:09'),(5,'Business','business','published','2025-09-11 01:47:09','2025-09-11 01:47:09');
/*!40000 ALTER TABLE `menus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `meta_boxes`
--

DROP TABLE IF EXISTS `meta_boxes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `meta_boxes` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `meta_key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_value` text COLLATE utf8mb4_unicode_ci,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `meta_boxes_reference_id_index` (`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=86 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `meta_boxes`
--

LOCK TABLES `meta_boxes` WRITE;
/*!40000 ALTER TABLE `meta_boxes` DISABLE KEYS */;
INSERT INTO `meta_boxes` VALUES (1,'faq_ids','[[2,4,6,7,9]]',1,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:13','2025-09-11 01:46:13'),(2,'faq_ids','[[1,2,3,6,10]]',2,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:13','2025-09-11 01:46:13'),(3,'faq_ids','[[2,5,6,8,9]]',3,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(4,'faq_ids','[[2,4,5,6,10]]',4,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(5,'faq_ids','[[1,5,7,9,10]]',5,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(6,'faq_ids','[[3,5,6,7,9]]',6,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(7,'faq_ids','[[1,3,5,6,9]]',7,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(8,'faq_ids','[[2,4,6,7,8]]',8,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(9,'faq_ids','[[1,5,6,7,8]]',9,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(10,'faq_ids','[[2,4,5,6,10]]',10,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:14','2025-09-11 01:46:14'),(11,'faq_ids','[[1,2,4,8,10]]',11,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(12,'faq_ids','[[1,3,4,7,9]]',12,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(13,'faq_ids','[[2,4,7,9,10]]',13,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(14,'faq_ids','[[2,5,6,7,10]]',14,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(15,'faq_ids','[[1,4,5,7,8]]',15,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(16,'faq_ids','[[2,3,4,6,10]]',16,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(17,'faq_ids','[[3,5,6,7,9]]',17,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(18,'faq_ids','[[2,5,8,9,10]]',18,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(19,'faq_ids','[[3,4,5,7,8]]',19,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(20,'faq_ids','[[2,5,7,8,10]]',20,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:15','2025-09-11 01:46:15'),(21,'faq_ids','[[1,4,5,7,8]]',21,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(22,'faq_ids','[[3,4,5,6,7]]',22,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(23,'faq_ids','[[1,2,6,9,10]]',23,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(24,'faq_ids','[[3,6,8,9,10]]',24,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(25,'faq_ids','[[2,4,6,7,8]]',25,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(26,'faq_ids','[[4,5,6,8,10]]',26,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(27,'faq_ids','[[3,5,6,9,10]]',27,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(28,'faq_ids','[[1,2,5,8,9]]',28,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(29,'faq_ids','[[3,4,5,6,10]]',29,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(30,'faq_ids','[[1,4,5,9,10]]',30,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:16','2025-09-11 01:46:16'),(31,'faq_ids','[[4,5,6,7,9]]',31,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(32,'faq_ids','[[1,2,7,8,10]]',32,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(33,'faq_ids','[[1,4,6,8,10]]',33,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(34,'faq_ids','[[2,4,7,8,10]]',34,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(35,'faq_ids','[[4,5,6,7,9]]',35,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(36,'faq_ids','[[2,4,5,6,10]]',36,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(37,'faq_ids','[[2,3,7,8,9]]',37,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(38,'faq_ids','[[1,2,5,7,9]]',38,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(39,'faq_ids','[[1,2,3,4,5]]',39,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:17','2025-09-11 01:46:17'),(40,'faq_ids','[[4,5,7,8,9]]',40,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(41,'faq_ids','[[3,7,8,9,10]]',41,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(42,'faq_ids','[[1,4,7,8,9]]',42,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(43,'faq_ids','[[1,2,6,7,8]]',43,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(44,'faq_ids','[[1,3,6,8,9]]',44,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(45,'faq_ids','[[5,6,7,8,10]]',45,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(46,'faq_ids','[[1,2,3,7,8]]',46,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(47,'faq_ids','[[3,4,6,7,8]]',47,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:18','2025-09-11 01:46:18'),(48,'faq_ids','[[5,6,8,9,10]]',48,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(49,'faq_ids','[[2,3,5,7,10]]',49,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(50,'faq_ids','[[3,4,6,8,9]]',50,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(51,'faq_ids','[[2,4,7,9,10]]',51,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(52,'faq_ids','[[1,2,4,9,10]]',52,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(53,'faq_ids','[[1,2,3,4,6]]',53,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(54,'faq_ids','[[1,2,4,5,6]]',54,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(55,'faq_ids','[[1,5,6,7,8]]',55,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:19','2025-09-11 01:46:19'),(56,'faq_ids','[[3,4,6,7,9]]',56,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(57,'faq_ids','[[4,7,8,9,10]]',57,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(58,'faq_ids','[[1,3,6,7,8]]',58,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(59,'faq_ids','[[2,3,7,8,9]]',59,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(60,'faq_ids','[[2,3,5,8,10]]',60,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(61,'faq_ids','[[2,3,4,6,9]]',61,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(62,'faq_ids','[[1,2,4,5,9]]',62,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:20','2025-09-11 01:46:20'),(63,'faq_ids','[[1,5,6,8,10]]',63,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:21','2025-09-11 01:46:21'),(64,'faq_ids','[[1,2,4,8,9]]',64,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:21','2025-09-11 01:46:21'),(65,'faq_ids','[[3,4,7,8,9]]',65,'Botble\\Ecommerce\\Models\\Product','2025-09-11 01:46:21','2025-09-11 01:46:21'),(66,'background','[\"stores\\/background-1.jpg\"]',1,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(67,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',1,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(68,'background','[\"stores\\/background-2.jpg\"]',2,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(69,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',2,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(70,'background','[\"stores\\/background-1.jpg\"]',3,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(71,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',3,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(72,'background','[\"stores\\/background-2.jpg\"]',4,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(73,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',4,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(74,'background','[\"stores\\/background-2.jpg\"]',5,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:54','2025-09-11 01:46:54'),(75,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',5,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(76,'background','[\"stores\\/background-1.jpg\"]',6,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(77,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',6,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(78,'background','[\"stores\\/background-2.jpg\"]',7,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(79,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',7,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(80,'background','[\"stores\\/background-2.jpg\"]',8,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(81,'socials','[{\"facebook\":\"https:\\/\\/www.facebook.com\\/\",\"twitter\":\"https:\\/\\/www.twitter.com\\/\",\"youtube\":\"https:\\/\\/www.youtube.com\\/\",\"linkedin\":\"https:\\/\\/www.linkedin.com\\/\"}]',8,'Botble\\Marketplace\\Models\\Store','2025-09-11 01:46:55','2025-09-11 01:46:55'),(82,'tablet_image','[\"sliders\\/01.jpg\"]',1,'Botble\\SimpleSlider\\Models\\SimpleSliderItem','2025-09-11 01:46:58','2025-09-11 01:46:58'),(83,'mobile_image','[\"sliders\\/01-sm.jpg\"]',1,'Botble\\SimpleSlider\\Models\\SimpleSliderItem','2025-09-11 01:46:58','2025-09-11 01:46:58'),(84,'tablet_image','[\"sliders\\/02.jpg\"]',2,'Botble\\SimpleSlider\\Models\\SimpleSliderItem','2025-09-11 01:46:58','2025-09-11 01:46:58'),(85,'mobile_image','[\"sliders\\/02-sm.jpg\"]',2,'Botble\\SimpleSlider\\Models\\SimpleSliderItem','2025-09-11 01:46:58','2025-09-11 01:46:58');
/*!40000 ALTER TABLE `meta_boxes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=259 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000001_create_cache_table',1),(2,'2013_04_09_032329_create_base_tables',1),(3,'2013_04_09_062329_create_revisions_table',1),(4,'2014_10_12_000000_create_users_table',1),(5,'2014_10_12_100000_create_password_reset_tokens_table',1),(6,'2016_06_10_230148_create_acl_tables',1),(7,'2016_06_14_230857_create_menus_table',1),(8,'2016_06_28_221418_create_pages_table',1),(9,'2016_10_05_074239_create_setting_table',1),(10,'2016_11_28_032840_create_dashboard_widget_tables',1),(11,'2016_12_16_084601_create_widgets_table',1),(12,'2017_05_09_070343_create_media_tables',1),(13,'2017_11_03_070450_create_slug_table',1),(14,'2019_01_05_053554_create_jobs_table',1),(15,'2019_08_19_000000_create_failed_jobs_table',1),(16,'2019_12_14_000001_create_personal_access_tokens_table',1),(17,'2022_04_20_100851_add_index_to_media_table',1),(18,'2022_04_20_101046_add_index_to_menu_table',1),(19,'2022_07_10_034813_move_lang_folder_to_root',1),(20,'2022_08_04_051940_add_missing_column_expires_at',1),(21,'2022_09_01_000001_create_admin_notifications_tables',1),(22,'2022_10_14_024629_drop_column_is_featured',1),(23,'2022_11_18_063357_add_missing_timestamp_in_table_settings',1),(24,'2022_12_02_093615_update_slug_index_columns',1),(25,'2023_01_30_024431_add_alt_to_media_table',1),(26,'2023_02_16_042611_drop_table_password_resets',1),(27,'2023_04_23_005903_add_column_permissions_to_admin_notifications',1),(28,'2023_05_10_075124_drop_column_id_in_role_users_table',1),(29,'2023_08_21_090810_make_page_content_nullable',1),(30,'2023_09_14_021936_update_index_for_slugs_table',1),(31,'2023_12_07_095130_add_color_column_to_media_folders_table',1),(32,'2023_12_17_162208_make_sure_column_color_in_media_folders_nullable',1),(33,'2024_04_04_110758_update_value_column_in_user_meta_table',1),(34,'2024_05_04_030654_improve_social_links',1),(35,'2024_05_12_091229_add_column_visibility_to_table_media_files',1),(36,'2024_07_07_091316_fix_column_url_in_menu_nodes_table',1),(37,'2024_07_12_100000_change_random_hash_for_media',1),(38,'2024_09_30_024515_create_sessions_table',1),(39,'2024_12_19_000001_create_device_tokens_table',1),(40,'2024_12_19_000002_create_push_notifications_table',1),(41,'2024_12_19_000003_create_push_notification_recipients_table',1),(42,'2024_12_30_000001_create_user_settings_table',1),(43,'2025_07_06_030754_add_phone_to_users_table',1),(44,'2025_07_31_add_performance_indexes_to_slugs_table',1),(45,'2020_11_18_150916_ads_create_ads_table',2),(46,'2021_12_02_035301_add_ads_translations_table',2),(47,'2023_04_17_062645_add_open_in_new_tab',2),(48,'2023_11_07_023805_add_tablet_mobile_image',2),(49,'2024_04_01_043317_add_google_adsense_slot_id_to_ads_table',2),(50,'2025_04_21_000000_add_tablet_mobile_image_to_ads_translations_table',2),(51,'2024_04_27_100730_improve_analytics_setting',3),(52,'2015_06_29_025744_create_audit_history',4),(53,'2023_11_14_033417_change_request_column_in_table_audit_histories',4),(54,'2025_05_05_000001_add_user_type_to_audit_histories_table',4),(55,'2015_06_18_033822_create_blog_table',5),(56,'2021_02_16_092633_remove_default_value_for_author_type',5),(57,'2021_12_03_030600_create_blog_translations',5),(58,'2022_04_19_113923_add_index_to_table_posts',5),(59,'2023_08_29_074620_make_column_author_id_nullable',5),(60,'2024_07_30_091615_fix_order_column_in_categories_table',5),(61,'2025_01_06_033807_add_default_value_for_categories_author_type',5),(62,'2016_06_17_091537_create_contacts_table',6),(63,'2023_11_10_080225_migrate_contact_blacklist_email_domains_to_core',6),(64,'2024_03_20_080001_migrate_change_attribute_email_to_nullable_form_contacts_table',6),(65,'2024_03_25_000001_update_captcha_settings_for_contact',6),(66,'2024_04_19_063914_create_custom_fields_table',6),(67,'2020_03_05_041139_create_ecommerce_tables',7),(68,'2021_01_01_044147_ecommerce_create_flash_sale_table',7),(69,'2021_01_17_082713_add_column_is_featured_to_product_collections_table',7),(70,'2021_01_18_024333_add_zip_code_into_table_customer_addresses',7),(71,'2021_02_18_073505_update_table_ec_reviews',7),(72,'2021_03_10_024419_add_column_confirmed_at_to_table_ec_customers',7),(73,'2021_03_10_025153_change_column_tax_amount',7),(74,'2021_03_20_033103_add_column_availability_to_table_ec_products',7),(75,'2021_04_28_074008_ecommerce_create_product_label_table',7),(76,'2021_05_31_173037_ecommerce_create_ec_products_translations',7),(77,'2021_08_17_105016_remove_column_currency_id_in_some_tables',7),(78,'2021_08_30_142128_add_images_column_to_ec_reviews_table',7),(79,'2021_10_04_030050_add_column_created_by_to_table_ec_products',7),(80,'2021_10_05_122616_add_status_column_to_ec_customers_table',7),(81,'2021_11_03_025806_nullable_phone_number_in_ec_customer_addresses',7),(82,'2021_11_23_071403_correct_languages_for_product_variations',7),(83,'2021_11_28_031808_add_product_tags_translations',7),(84,'2021_12_01_031123_add_featured_image_to_ec_products',7),(85,'2022_01_01_033107_update_table_ec_shipments',7),(86,'2022_02_16_042457_improve_product_attribute_sets',7),(87,'2022_03_22_075758_correct_product_name',7),(88,'2022_04_19_113334_add_index_to_ec_products',7),(89,'2022_04_28_144405_remove_unused_table',7),(90,'2022_05_05_115015_create_ec_customer_recently_viewed_products_table',7),(91,'2022_05_18_143720_add_index_to_table_ec_product_categories',7),(92,'2022_06_16_095633_add_index_to_some_tables',7),(93,'2022_06_30_035148_create_order_referrals_table',7),(94,'2022_07_24_153815_add_completed_at_to_ec_orders_table',7),(95,'2022_08_14_032836_create_ec_order_returns_table',7),(96,'2022_08_14_033554_create_ec_order_return_items_table',7),(97,'2022_08_15_040324_add_billing_address',7),(98,'2022_08_30_091114_support_digital_products_table',7),(99,'2022_09_13_095744_create_options_table',7),(100,'2022_09_13_104347_create_option_value_table',7),(101,'2022_10_05_163518_alter_table_ec_order_product',7),(102,'2022_10_12_041517_create_invoices_table',7),(103,'2022_10_12_142226_update_orders_table',7),(104,'2022_10_13_024916_update_table_order_returns',7),(105,'2022_10_21_030830_update_columns_in_ec_shipments_table',7),(106,'2022_10_28_021046_update_columns_in_ec_shipments_table',7),(107,'2022_11_16_034522_update_type_column_in_ec_shipping_rules_table',7),(108,'2022_11_19_041643_add_ec_tax_product_table',7),(109,'2022_12_12_063830_update_tax_defadult_in_ec_tax_products_table',7),(110,'2022_12_17_041532_fix_address_in_order_invoice',7),(111,'2022_12_26_070329_create_ec_product_views_table',7),(112,'2023_01_04_033051_fix_product_categories',7),(113,'2023_01_09_050400_add_ec_global_options_translations_table',7),(114,'2023_01_10_093754_add_missing_option_value_id',7),(115,'2023_01_17_082713_add_column_barcode_and_cost_per_item_to_product_table',7),(116,'2023_01_26_021854_add_ec_customer_used_coupons_table',7),(117,'2023_02_08_015900_update_options_column_in_ec_order_product_table',7),(118,'2023_02_27_095752_remove_duplicate_reviews',7),(119,'2023_03_20_115757_add_user_type_column_to_ec_shipment_histories_table',7),(120,'2023_04_21_082427_create_ec_product_categorizables_table',7),(121,'2023_05_03_011331_add_missing_column_price_into_invoice_items_table',7),(122,'2023_05_17_025812_fix_invoice_issue',7),(123,'2023_05_26_073140_move_option_make_phone_field_optional_at_checkout_page_to_mandatory_fields',7),(124,'2023_05_27_144611_fix_exchange_rate_setting',7),(125,'2023_06_22_084331_add_generate_license_code_to_ec_products_table',7),(126,'2023_06_30_042512_create_ec_order_tax_information_table',7),(127,'2023_07_14_022724_remove_column_id_from_ec_product_collection_products',7),(128,'2023_08_09_012940_remove_column_status_in_ec_product_attributes',7),(129,'2023_08_15_064505_create_ec_tax_rules_table',7),(130,'2023_08_21_021819_make_column_address_in_ec_customer_addresses_nullable',7),(131,'2023_08_22_094114_drop_unique_for_barcode',7),(132,'2023_08_30_031811_add_apply_via_url_column_to_ec_discounts_table',7),(133,'2023_09_07_094312_add_index_to_product_sku_and_translations',7),(134,'2023_09_19_024955_create_discount_product_categories_table',7),(135,'2023_10_17_070728_add_icon_and_icon_image_to_product_categories_table',7),(136,'2023_11_22_154643_add_unique_in_table_ec_products_variations',7),(137,'2023_11_27_032313_add_price_columns_to_ec_product_cross_sale_relations_table',7),(138,'2023_12_06_023945_add_display_on_checkout_column_to_ec_discounts_table',7),(139,'2023_12_25_040604_ec_create_review_replies_table',7),(140,'2023_12_26_090340_add_private_notes_column_to_ec_customers_table',7),(141,'2024_01_16_070706_fix_translation_tables',7),(142,'2024_01_23_075227_add_proof_file_to_ec_orders_table',7),(143,'2024_03_26_041531_add_cancel_reason_to_ec_orders_table',7),(144,'2024_03_27_062402_create_ec_customer_deletion_requests_table',7),(145,'2024_03_29_042242_migrate_old_captcha_settings',7),(146,'2024_03_29_093946_create_ec_order_return_histories_table',7),(147,'2024_04_01_063523_add_customer_columns_to_ec_reviews_table',7),(148,'2024_04_15_092654_migrate_ecommerce_google_tag_manager_code_setting',7),(149,'2024_04_16_035713_add_min_max_order_quantity_columns_to_products_table',7),(150,'2024_05_07_073153_improve_table_wishlist',7),(151,'2024_05_07_093703_add_missing_zip_code_into_table_store_locators',7),(152,'2024_05_15_021503_fix_invoice_path',7),(153,'2024_06_20_160724_create_ec_shared_wishlists_table',7),(154,'2024_06_28_025104_add_notify_attachment_updated_column_to_ec_products_table',7),(155,'2024_07_03_030900_add_downloaded_at_column_to_ec_order_product_table',7),(156,'2024_07_14_071826_make_customer_email_nullable',7),(157,'2024_07_15_104916_add_video_media_column_to_ec_products_table',7),(158,'2024_07_26_052530_add_percentage_to_tax_rules_table',7),(159,'2024_08_14_123028_add_customer_delivered_confirmed_at_column_to_ec_shipments_table',7),(160,'2024_08_19_132849_create_specification_tables',7),(161,'2024_09_07_060744_add_author_column_to_specification_tables',7),(162,'2024_09_14_064023_add_can_use_with_flash_sale_column_to_ec_discounts_table',7),(163,'2024_09_25_073928_remove_wrong_product_slugs',7),(164,'2025_01_10_000000_fix_order_invoice_rounding_issues',7),(165,'2025_01_15_050230_migrate_old_theme_options',7),(166,'2025_01_15_optimize_products_export_index',7),(167,'2025_01_17_082713_correct_column_barcode_and_cost_per_item_to_product_table',7),(168,'2025_01_24_044641_migrate_old_country_data',7),(169,'2025_01_28_233602_add_private_notes_into_ec_orders_table',7),(170,'2025_02_13_021247_add_tax_translations',7),(171,'2025_02_24_152621_add_text_color_to_product_labels_table',7),(172,'2025_04_12_000001_add_payment_fee_to_ec_orders_table',7),(173,'2025_04_12_000002_add_payment_fee_to_ec_invoices_table',7),(174,'2025_05_05_092036_make_user_id_and_tax_amount_nullable',7),(175,'2025_05_15_082342_drop_email_unique_index_in_ec_customers_table',7),(176,'2025_06_07_081731_add_translations_for_specification_groups_and_tables',7),(177,'2025_06_17_091813_increase_note_in_shipments_table',7),(178,'2025_06_24_000001_create_ec_product_license_codes_table',7),(179,'2025_06_24_080427_add_license_code_type_to_products_table',7),(180,'2025_07_06_062402_create_ec_customer_deletion_requests_table',7),(181,'2025_07_07_161729_change_license_code_to_text_in_ec_product_license_codes_table',7),(182,'2025_07_08_162756_increase_license_code_column_size_in_ec_order_product_table',7),(183,'2025_07_09_000001_add_customer_address_fields_to_ec_invoices_table',7),(184,'2025_07_15_090809_create_ec_abandoned_carts_table',7),(185,'2025_07_24_120510_increase_barcode_column_length_in_ec_products_table',7),(186,'2025_07_31_133600_add_performance_indexes_to_ec_product_categories_table',7),(187,'2025_08_01_161205_optimize_product_variation_query_indexes',7),(188,'2025_08_08_145059_correct_tax_amount_in_order_and_invoice_tables',7),(189,'2025_09_05_025247_create_ec_product_specification_attribute_translations_table',7),(190,'2025_09_08_025516_add_variations_count_to_ec_products_table',7),(191,'2025_09_08_030755_add_reviews_cache_to_ec_products_table',7),(192,'2025_09_08_080248_add_slug_column_to_ec_product_categories_table',7),(193,'2025_09_08_080330_add_slug_column_to_ec_product_categories_translations_table',7),(194,'2025_09_08_080443_populate_slug_column_for_product_categories',7),(195,'2025_09_08_081216_add_slug_column_to_ec_products_table',7),(196,'2025_09_08_081237_add_slug_column_to_ec_products_translations_table',7),(197,'2025_09_08_081321_populate_slug_column_for_products',7),(198,'2025_09_10_073321_add_performance_indexes_to_ecommerce_tables',7),(199,'2018_07_09_221238_create_faq_table',8),(200,'2021_12_03_082134_create_faq_translations',8),(201,'2023_11_17_063408_add_description_column_to_faq_categories_table',8),(202,'2016_10_03_032336_create_languages_table',9),(203,'2023_09_14_022423_add_index_for_language_table',9),(204,'2021_10_25_021023_fix-priority-load-for-language-advanced',10),(205,'2021_12_03_075608_create_page_translations',10),(206,'2023_07_06_011444_create_slug_translations_table',10),(207,'2019_11_18_061011_create_country_table',11),(208,'2021_12_03_084118_create_location_translations',11),(209,'2021_12_03_094518_migrate_old_location_data',11),(210,'2021_12_10_034440_switch_plugin_location_to_use_language_advanced',11),(211,'2022_01_16_085908_improve_plugin_location',11),(212,'2022_08_04_052122_delete_location_backup_tables',11),(213,'2023_04_23_061847_increase_state_translations_abbreviation_column',11),(214,'2023_07_26_041451_add_more_columns_to_location_table',11),(215,'2023_07_27_041451_add_more_columns_to_location_translation_table',11),(216,'2023_08_15_073307_drop_unique_in_states_cities_translations',11),(217,'2023_10_21_065016_make_state_id_in_table_cities_nullable',11),(218,'2024_08_17_094600_add_image_into_countries',11),(219,'2025_01_08_093652_add_zip_code_to_cities',11),(220,'2025_07_31_083459_add_indexes_for_location_search_performance',11),(221,'2021_07_06_030002_create_marketplace_table',12),(222,'2021_09_04_150137_add_vendor_verified_at_to_ec_customers_table',12),(223,'2021_10_04_033903_add_column_approved_by_into_table_ec_products',12),(224,'2021_10_06_124943_add_transaction_id_column_to_mp_customer_withdrawals_table',12),(225,'2021_10_10_054216_add_columns_to_mp_customer_revenues_table',12),(226,'2021_12_06_031304_update_table_mp_customer_revenues',12),(227,'2022_10_19_152916_add_columns_to_mp_stores_table',12),(228,'2022_10_20_062849_create_mp_category_sale_commissions_table',12),(229,'2022_11_02_071413_add_more_info_for_store',12),(230,'2022_11_02_080444_add_tax_info',12),(231,'2023_02_01_062030_add_store_translations',12),(232,'2023_02_13_032133_update_fee_column_mp_customer_revenues_table',12),(233,'2023_02_17_023648_fix_store_prefix',12),(234,'2024_04_03_062451_add_cover_image_to_table_mp_stores',12),(235,'2024_05_07_082630_create_mp_messages_table',12),(236,'2024_07_19_131849_add_documents_to_mp_stores_table',12),(237,'2024_08_18_083119_add_tax_id_column_to_mp_stores_table',12),(238,'2024_08_27_141244_add_block_reason_to_ec_customers_table',12),(239,'2024_09_17_125408_add_square_logo_to_stores_table',12),(240,'2025_07_31_021805_add_indexes_for_vendor_categories_optimization',12),(241,'2025_08_07_073854_add_verification_fields_to_mp_stores_table',12),(242,'2017_10_24_154832_create_newsletter_table',13),(243,'2024_03_25_000001_update_captcha_settings_for_newsletter',13),(244,'2017_05_18_080441_create_payment_tables',14),(245,'2021_03_27_144913_add_customer_type_into_table_payments',14),(246,'2021_05_24_034720_make_column_currency_nullable',14),(247,'2021_08_09_161302_add_metadata_column_to_payments_table',14),(248,'2021_10_19_020859_update_metadata_field',14),(249,'2022_06_28_151901_activate_paypal_stripe_plugin',14),(250,'2022_07_07_153354_update_charge_id_in_table_payments',14),(251,'2024_07_04_083133_create_payment_logs_table',14),(252,'2025_04_12_000003_add_payment_fee_to_payments_table',14),(253,'2025_05_22_000001_add_payment_fee_type_to_settings_table',14),(254,'2017_07_11_140018_create_simple_slider_table',15),(255,'2025_04_08_040931_create_social_logins_table',16),(256,'2024_09_14_100108_add_stripe_connect_details_to_ec_customers_table',17),(257,'2016_10_07_193005_create_translations_table',18),(258,'2023_12_12_105220_drop_translations_table',18);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_category_sale_commissions`
--

DROP TABLE IF EXISTS `mp_category_sale_commissions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_category_sale_commissions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_category_id` bigint unsigned NOT NULL,
  `commission_percentage` decimal(8,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `mp_category_sale_commissions_product_category_id_unique` (`product_category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_category_sale_commissions`
--

LOCK TABLES `mp_category_sale_commissions` WRITE;
/*!40000 ALTER TABLE `mp_category_sale_commissions` DISABLE KEYS */;
/*!40000 ALTER TABLE `mp_category_sale_commissions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_customer_revenues`
--

DROP TABLE IF EXISTS `mp_customer_revenues`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_customer_revenues` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `order_id` bigint unsigned DEFAULT NULL,
  `sub_amount` decimal(15,2) DEFAULT '0.00',
  `fee` decimal(15,2) unsigned DEFAULT '0.00',
  `amount` decimal(15,2) DEFAULT '0.00',
  `current_balance` decimal(15,2) DEFAULT '0.00',
  `currency` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `type` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_customer_revenues`
--

LOCK TABLES `mp_customer_revenues` WRITE;
/*!40000 ALTER TABLE `mp_customer_revenues` DISABLE KEYS */;
/*!40000 ALTER TABLE `mp_customer_revenues` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_customer_withdrawals`
--

DROP TABLE IF EXISTS `mp_customer_withdrawals`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_customer_withdrawals` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned DEFAULT NULL,
  `fee` decimal(15,2) unsigned DEFAULT '0.00',
  `amount` decimal(15,2) unsigned DEFAULT '0.00',
  `current_balance` decimal(15,2) unsigned DEFAULT '0.00',
  `currency` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `bank_info` text COLLATE utf8mb4_unicode_ci,
  `payment_channel` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
  `images` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `transaction_id` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_customer_withdrawals`
--

LOCK TABLES `mp_customer_withdrawals` WRITE;
/*!40000 ALTER TABLE `mp_customer_withdrawals` DISABLE KEYS */;
/*!40000 ALTER TABLE `mp_customer_withdrawals` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_messages`
--

DROP TABLE IF EXISTS `mp_messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_messages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `store_id` bigint unsigned NOT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `name` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_messages`
--

LOCK TABLES `mp_messages` WRITE;
/*!40000 ALTER TABLE `mp_messages` DISABLE KEYS */;
/*!40000 ALTER TABLE `mp_messages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_stores`
--

DROP TABLE IF EXISTS `mp_stores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_stores` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `state` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `city` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `customer_id` bigint unsigned DEFAULT NULL,
  `logo` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `logo_square` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `is_verified` tinyint(1) NOT NULL DEFAULT '0',
  `verified_at` timestamp NULL DEFAULT NULL,
  `verified_by` bigint unsigned DEFAULT NULL,
  `verification_note` text COLLATE utf8mb4_unicode_ci,
  `vendor_verified_at` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `zip_code` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tax_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `certificate_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `government_id_file` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_stores`
--

LOCK TABLES `mp_stores` WRITE;
/*!40000 ALTER TABLE `mp_stores` DISABLE KEYS */;
INSERT INTO `mp_stores` VALUES (1,'GoPro','uheidenreich@example.org','+19345526348','94870 Schumm Oval','GT','Hawaii','Nathanielville',2,'stores/1.png',NULL,NULL,'Facilis incidunt itaque et voluptatem eaque eum animi ipsa. Architecto quas dolor enim repellendus. Aliquid sit voluptate perspiciatis voluptatem dolorum in at temporibus. Dolore sint sit error adipisci et ex. Fugit quod rerum quia iusto repudiandae. Mollitia qui amet omnis quidem voluptate.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-03-20 10:16:31',NULL,'Verified business with valid documentation',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(2,'Global Office','eparisian@example.org','+13167190129','5773 Walter Lakes Apt. 584','AL','South Carolina','Schambergermouth',2,'stores/2.png',NULL,NULL,'Accusamus placeat est autem exercitationem temporibus. Nam molestiae praesentium aut et perspiciatis doloremque. Ut velit voluptatem enim soluta animi magni. Consequatur illo omnis blanditiis vero voluptates ullam. Cumque in dolores cumque in.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-05-30 06:14:23',NULL,'Verified business with valid documentation',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(3,'Young Shop','jada00@example.net','+19402338663','3386 Hintz Pass Suite 365','ML','Arkansas','Port Destiny',3,'stores/3.png',NULL,NULL,'Id soluta et nesciunt et. Esse molestias nulla ipsum nam. Quia earum quos voluptas aut tempore magnam. Aliquid enim et magni commodi doloribus atque. Eius numquam voluptatem vero non. Ex quam dolore sunt mollitia. Facilis amet quasi iusto fugit. Nihil fugit repellendus unde quibusdam nemo. Odit a ducimus optio excepturi.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-07-09 18:44:33',NULL,'Verified business with valid documentation',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(4,'Global Store','adella39@example.org','+13475196639','24937 Geovanny Divide Suite 939','VI','Texas','Murazikhaven',6,'stores/4.png',NULL,NULL,'Voluptas sit possimus sequi consectetur. Nobis repellat autem qui repellendus dolor. Voluptatem eum qui sequi eos. Consectetur aspernatur hic aut ut rerum hic doloribus. Dolores voluptatem consequatur voluptatum. Dolore minus voluptates consequatur repellendus facere ut. Molestiae sequi ratione pariatur quasi minus aperiam assumenda.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',0,NULL,NULL,NULL,NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(5,'Robert’s Store','larson.felicia@example.com','+13305084409','9914 Marquardt Roads Suite 517','AX','Oregon','Macejkovictown',6,'stores/5.png',NULL,NULL,'Facilis qui quidem rerum occaecati. Rem et maiores officiis possimus molestiae quas sint maiores. Possimus nihil omnis et sapiente et. Blanditiis sint ut voluptas est eum unde aut. Error voluptatem nulla sunt consequatur omnis assumenda sunt. Nihil vel voluptatibus natus fuga nisi. Libero non officia ab quos.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-03-14 00:07:57',NULL,'Verified business with valid documentation',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(6,'Stouffer','greta.wyman@example.com','+14787602873','5632 Cartwright Knoll','TK','Utah','Auroreborough',8,'stores/6.png',NULL,NULL,'Ratione natus dolorem quia quibusdam qui magnam temporibus. Laboriosam quam est laborum esse aut magni dolor. Rerum eligendi quasi quod occaecati eius molestias. Magnam magni aut aut sit a error praesentium. Sint necessitatibus reiciendis in accusantium. Hic saepe neque rerum eos voluptatem fuga. Odit aut unde perspiciatis ipsa qui ullam veniam cupiditate. Id non maiores a sapiente.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-09-05 23:25:52',NULL,'Verified business with valid documentation',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(7,'StarKist','janelle.kiehn@example.com','+19346649268','45323 Ernestina Pass','PY','California','North Kaylinmouth',6,'stores/7.png',NULL,NULL,'Ut placeat ad enim iusto possimus. Accusamus ipsam asperiores fugiat et aspernatur. Cum quaerat consequatur nesciunt totam. Ut optio doloribus reiciendis quia delectus odit ut id. Minima exercitationem quo non. Voluptatem perferendis ut vitae sequi. Eos vel consequatur et doloremque soluta est omnis aliquam.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',0,NULL,NULL,NULL,NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL),(8,'Old El Paso','moses.haley@example.net','+13517793040','52483 Petra Station Apt. 221','IR','Maryland','Leannonview',3,'stores/8.png',NULL,NULL,'Quasi ipsa culpa laborum. Occaecati earum magnam explicabo ut quia ratione. Soluta eaque accusantium ut ut sit natus dolore. Facilis et saepe eligendi animi. Adipisci velit hic accusamus esse voluptates labore dignissimos. Placeat explicabo voluptas rerum. Aperiam dolorem nihil saepe ut exercitationem.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f4\" src=\"/storage/news/1.jpg\"></p>\n\n<p><br>\n </p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men’s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even <strong>minimalist style</strong> is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don’t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f5\" src=\"/storage/news/2.jpg\"></p>\n\n<p><br>\n </p>\n<hr>\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don’t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers’ pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don’t need anymore.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f1\" src=\"/storage/news/3.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 3: Don’t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f6\" src=\"/storage/news/4.jpg\"></p>\n\n<p><br>\n </p>\n\n<hr>\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center;\"><img alt=\"f2\" src=\"/storage/news/5.jpg\"></p>\n\n<p> </p>\n','published',1,'2025-08-24 03:59:26',NULL,'Established vendor with proven track record',NULL,'2025-09-11 01:46:42','2025-09-11 01:46:42',NULL,NULL,NULL,NULL,NULL);
/*!40000 ALTER TABLE `mp_stores` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_stores_translations`
--

DROP TABLE IF EXISTS `mp_stores_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_stores_translations` (
  `lang_code` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mp_stores_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `address` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `company` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cover_image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`mp_stores_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_stores_translations`
--

LOCK TABLES `mp_stores_translations` WRITE;
/*!40000 ALTER TABLE `mp_stores_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `mp_stores_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `mp_vendor_info`
--

DROP TABLE IF EXISTS `mp_vendor_info`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `mp_vendor_info` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `customer_id` bigint unsigned NOT NULL DEFAULT '0',
  `balance` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_fee` decimal(15,2) NOT NULL DEFAULT '0.00',
  `total_revenue` decimal(15,2) NOT NULL DEFAULT '0.00',
  `signature` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bank_info` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `payout_payment_method` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT 'bank_transfer',
  `tax_info` text COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `mp_vendor_info`
--

LOCK TABLES `mp_vendor_info` WRITE;
/*!40000 ALTER TABLE `mp_vendor_info` DISABLE KEYS */;
INSERT INTO `mp_vendor_info` VALUES (1,2,0.00,0.00,0.00,'$2y$12$FdD1lQgtTtm0RptaDsACR.JB.bumc29mDlEScwNDWH7aE8dz49QhS','{\"name\":\"Mrs. Erica Frami\",\"number\":\"+18289065211\",\"full_name\":\"Prof. Dashawn Streich\",\"description\":\"Prof. Doug Simonis\"}','2025-09-11 01:46:38','2025-09-11 01:46:38','bank_transfer',NULL),(2,3,0.00,0.00,0.00,'$2y$12$3x2tO6Ipljn0dAiQEzbtX.B6wCrCkN2UtBs2X4L51wktLFqEQnM1K','{\"name\":\"Gerry Rosenbaum\",\"number\":\"+15753572998\",\"full_name\":\"Mr. Jake Dach\",\"description\":\"Maverick Weissnat\"}','2025-09-11 01:46:39','2025-09-11 01:46:39','bank_transfer',NULL),(3,4,0.00,0.00,0.00,'$2y$12$BGwz9b6blg6b9WbbEZ6YpeaL8c.87YbMdCQYWxY7YCAhHJV/VO5fq','{\"name\":\"Ahmad Pfannerstill\",\"number\":\"+17639332700\",\"full_name\":\"Vernice Windler\",\"description\":\"Ellen Kris III\"}','2025-09-11 01:46:39','2025-09-11 01:46:39','bank_transfer',NULL),(4,5,0.00,0.00,0.00,'$2y$12$12SDPTn27J62U102mp5kveQ7S5quxXOsqbEhdJzw8jzyBCGs7KCvO','{\"name\":\"Mr. Jamar Goyette DDS\",\"number\":\"+16206914968\",\"full_name\":\"Dr. Beatrice Murazik Sr.\",\"description\":\"Bobby Blanda MD\"}','2025-09-11 01:46:40','2025-09-11 01:46:40','bank_transfer',NULL),(5,6,0.00,0.00,0.00,'$2y$12$gvupqUVlP/pN8CWE6hFjx..ofrncAbDAs5q9IMm587N4bl6O6yAcq','{\"name\":\"Mr. Colby Gaylord IV\",\"number\":\"+13368066801\",\"full_name\":\"Nichole Spinka PhD\",\"description\":\"Louie Reynolds\"}','2025-09-11 01:46:41','2025-09-11 01:46:41','bank_transfer',NULL),(6,7,0.00,0.00,0.00,'$2y$12$XL3qOtEhx9NzLlad3ppLnetezDsTc9ZSozOgmhYMvVFLo8U7BGhY6','{\"name\":\"Eulalia Fritsch\",\"number\":\"+14349668691\",\"full_name\":\"Orpha Frami\",\"description\":\"Theo Larson I\"}','2025-09-11 01:46:41','2025-09-11 01:46:41','bank_transfer',NULL),(7,8,0.00,0.00,0.00,'$2y$12$zHHvFtxgRY6zA/gds2IqFuJn9r93CduoPFrRGpvFbfL2We5qIzEbu','{\"name\":\"Candida Miller\",\"number\":\"+17079608619\",\"full_name\":\"Mustafa Hegmann\",\"description\":\"Enid Morissette\"}','2025-09-11 01:46:42','2025-09-11 01:46:42','bank_transfer',NULL);
/*!40000 ALTER TABLE `mp_vendor_info` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `newsletters`
--

DROP TABLE IF EXISTS `newsletters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `newsletters` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'subscribed',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `newsletters`
--

LOCK TABLES `newsletters` WRITE;
/*!40000 ALTER TABLE `newsletters` DISABLE KEYS */;
/*!40000 ALTER TABLE `newsletters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages`
--

DROP TABLE IF EXISTS `pages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `template` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pages_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages`
--

LOCK TABLES `pages` WRITE;
/*!40000 ALTER TABLE `pages` DISABLE KEYS */;
INSERT INTO `pages` VALUES (1,'Home','<div>[simple-slider key=\"home-slider\" is_autoplay=\"yes\" autoplay_speed=\"5000\" ads=\"VC2C8Q1UGCBG\" background=\"general/slider-bg.jpg\"][/simple-slider]</div><div>[featured-product-categories title=\"Browse by Category\"][/featured-product-categories]</div><div>[featured-brands title=\"Featured Brands\"][/featured-brands]</div><div>[flash-sale title=\"Top Saver Today\" flash_sale_id=\"1\"][/flash-sale]</div><div>[product-category-products title=\"Just Landing\" category_id=\"23\"][/product-category-products]</div><div>[theme-ads key_1=\"IZ6WU8KUALYD\" key_2=\"ILSFJVYFGCPZ\" key_3=\"ZDOZUZZIU7FT\"][/theme-ads]</div><div>[featured-products title=\"Featured products\"][/featured-products]</div><div>[product-collections title=\"Essential Products\"][/product-collections]</div><div>[product-category-products category_id=\"18\"][/product-category-products]</div><div>[featured-posts title=\"Health Daily\" background=\"general/blog-bg.jpg\"\n                        app_enabled=\"1\"\n                        app_title=\"Shop faster with Farmart App\"\n                        app_description=\"Available on both iOS & Android\"\n                        app_bg=\"general/app-bg.png\"\n                        app_android_img=\"general/app-android.png\"\n                        app_android_link=\"#\"\n                        app_ios_img=\"general/app-ios.png\"\n                        app_ios_link=\"#\"][/featured-posts]</div>',1,NULL,'homepage',NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(2,'About us',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(3,'Terms Of Use',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(4,'Terms &amp; Conditions',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(5,'Refund Policy',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(6,'Blog','<p>---</p>',1,NULL,'full-width',NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(7,'FAQs','<div>[faq title=\"Frequently Asked Questions\"][/faq]</div>',1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(8,'Contact','<div>[google-map]502 New Street, Brighton VIC, Australia[/google-map]</div><div>[contact-info-boxes title=\"Contact Info\" subtitle=\"Location\" name_1=\"Store\" address_1=\"68 Atlantic Ave St, Brooklyn, NY 90002, USA\" phone_1=\"(+005) 5896 72 78 79\" email_1=\"support@farmart.com\" name_2=\"Warehouse\" address_2=\"172 Richmond Hill Ave St, Stamford, NY 90002, USA\" phone_2=\"(+005) 5896 03 04 05\" show_contact_form=\"1\" ][/contact-info-boxes]</div>',1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(9,'Cookie Policy','<h3>EU Cookie Consent</h3><p>To use this Website we are using Cookies and collecting some Data. To be compliant with the EU GDPR we give you to choose if you allow us to use certain Cookies and to collect some Data.</p><h4>Essential Data</h4><p>The Essential Data is needed to run the Site you are visiting technically. You can not deactivate them.</p><p>- Session Cookie: PHP uses a Cookie to identify user sessions. Without this Cookie the Website is not working.</p><p>- XSRF-Token Cookie: Laravel automatically generates a CSRF \"token\" for each active user session managed by the application. This token is used to verify that the authenticated user is the one actually making the requests to the application.</p>',1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(10,'Affiliate',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(11,'Career',NULL,1,NULL,NULL,NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58'),(12,'Coming soon','<div>[coming-soon time=\"2026-09-11 08:46:58\" title=\"We’re coming soon.\" subtitle=\"Currently we’re working on our brand new website and will be\nlaunching soon.\" social_title=\"Connect us on social networks\" image=\"general/coming-soon.jpg\"][/coming-soon]</div>',1,NULL,'coming-soon',NULL,'published','2025-09-11 01:46:58','2025-09-11 01:46:58');
/*!40000 ALTER TABLE `pages` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `pages_translations`
--

DROP TABLE IF EXISTS `pages_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `pages_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pages_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`pages_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `pages_translations`
--

LOCK TABLES `pages_translations` WRITE;
/*!40000 ALTER TABLE `pages_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `pages_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payment_logs`
--

DROP TABLE IF EXISTS `payment_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payment_logs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `payment_method` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `request` longtext COLLATE utf8mb4_unicode_ci,
  `response` longtext COLLATE utf8mb4_unicode_ci,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payment_logs`
--

LOCK TABLES `payment_logs` WRITE;
/*!40000 ALTER TABLE `payment_logs` DISABLE KEYS */;
/*!40000 ALTER TABLE `payment_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `payments` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `currency` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` bigint unsigned NOT NULL DEFAULT '0',
  `charge_id` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `payment_channel` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amount` decimal(15,2) unsigned NOT NULL,
  `payment_fee` decimal(15,2) DEFAULT '0.00',
  `order_id` bigint unsigned DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT 'pending',
  `payment_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT 'confirm',
  `customer_id` bigint unsigned DEFAULT NULL,
  `refunded_amount` decimal(15,2) unsigned DEFAULT NULL,
  `refund_note` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `customer_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` mediumtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `personal_access_tokens`
--

LOCK TABLES `personal_access_tokens` WRITE;
/*!40000 ALTER TABLE `personal_access_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `personal_access_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_categories`
--

DROP TABLE IF EXISTS `post_categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_categories` (
  `category_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_categories_category_id_index` (`category_id`),
  KEY `post_categories_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_categories`
--

LOCK TABLES `post_categories` WRITE;
/*!40000 ALTER TABLE `post_categories` DISABLE KEYS */;
INSERT INTO `post_categories` VALUES (1,1),(4,1),(1,2),(4,2),(1,3),(3,3),(1,4),(4,4),(2,5),(3,5),(1,6),(4,6),(1,7),(4,7),(1,8),(4,8),(2,9),(4,9),(1,10),(3,10),(1,11),(3,11);
/*!40000 ALTER TABLE `post_categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tags`
--

DROP TABLE IF EXISTS `post_tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `post_tags` (
  `tag_id` bigint unsigned NOT NULL,
  `post_id` bigint unsigned NOT NULL,
  KEY `post_tags_tag_id_index` (`tag_id`),
  KEY `post_tags_post_id_index` (`post_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tags`
--

LOCK TABLES `post_tags` WRITE;
/*!40000 ALTER TABLE `post_tags` DISABLE KEYS */;
INSERT INTO `post_tags` VALUES (1,1),(2,1),(3,1),(4,1),(5,1),(1,2),(2,2),(3,2),(4,2),(5,2),(1,3),(2,3),(3,3),(4,3),(5,3),(1,4),(2,4),(3,4),(4,4),(5,4),(1,5),(2,5),(3,5),(4,5),(5,5),(1,6),(2,6),(3,6),(4,6),(5,6),(1,7),(2,7),(3,7),(4,7),(5,7),(1,8),(2,8),(3,8),(4,8),(5,8),(1,9),(2,9),(3,9),(4,9),(5,9),(1,10),(2,10),(3,10),(4,10),(5,10),(1,11),(2,11),(3,11),(4,11),(5,11);
/*!40000 ALTER TABLE `post_tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_featured` tinyint unsigned NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `views` int unsigned NOT NULL DEFAULT '0',
  `format_type` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `posts_status_index` (`status`),
  KEY `posts_author_id_index` (`author_id`),
  KEY `posts_author_type_index` (`author_type`),
  KEY `posts_created_at_index` (`created_at`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,'4 Expert Tips On How To Choose The Right Men’s Wallet','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/1.jpg',1600,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(2,'Sexy Clutches: How to Buy &amp; Wear a Designer Clutch Bag','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/2.jpg',2051,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(3,'The Top 2020 Handbag Trends to Know','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/3.jpg',545,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(4,'How to Match the Color of Your Handbag With an Outfit','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/4.jpg',321,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(5,'How to Care for Leather Bags','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/5.jpg',1567,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(6,'We\'re Crushing Hard on Summer\'s 10 Biggest Bag Trends','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/6.jpg',196,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(7,'Essential Qualities of Highly Successful Music','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/7.jpg',871,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(8,'9 Things I Love About Shaving My Head','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/8.jpg',1273,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(9,'Why Teamwork Really Makes The Dream Work','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/9.jpg',2291,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(10,'The World Caters to Average People','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',1,'news/10.jpg',585,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57'),(11,'The litigants on the screen are not actors','You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.','<p>I have seen many people underestimating the power of their wallets. To them, they are just a functional item they use to carry. As a result, they often end up with the wallets which are not really suitable for them.</p>\n\n<p>You should pay more attention when you choose your wallets. There are a lot of them on the market with the different designs and styles. When you choose carefully, you would be able to buy a wallet that is catered to your needs. Not to mention that it will help to enhance your style significantly.</p>\n\n<p style=\"text-align:center\"><img alt=\"f4\" src=\"/storage/news/1.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<p><strong><em>For all the reason above, here are 7 expert tips to help you pick up the right men&rsquo;s wallet for you:</em></strong></p>\n\n<h4><strong>Number 1: Choose A Neat Wallet</strong></h4>\n\n<p>The wallet is an essential accessory that you should go simple. Simplicity is the best in this case. A simple and neat wallet with the plain color and even&nbsp;<strong>minimalist style</strong>&nbsp;is versatile. It can be used for both formal and casual events. In addition, that wallet will go well with most of the clothes in your wardrobe.</p>\n\n<p>Keep in mind that a wallet will tell other people about your personality and your fashion sense as much as other clothes you put on. Hence, don&rsquo;t go cheesy on your wallet or else people will think that you have a funny and particular style.</p>\n\n<p style=\"text-align:center\"><img alt=\"f5\" src=\"/storage/news/2.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n<hr />\n<h4><strong>Number 2: Choose The Right Size For Your Wallet</strong></h4>\n\n<p>You should avoid having an over-sized wallet. Don&rsquo;t think that you need to buy a big wallet because you have a lot to carry with you. In addition, a fat wallet is very ugly. It will make it harder for you to slide the wallet into your trousers&rsquo; pocket. In addition, it will create a bulge and ruin your look.</p>\n\n<p>Before you go on to buy a new wallet, clean out your wallet and place all the items from your wallet on a table. Throw away things that you would never need any more such as the old bills or the expired gift cards. Remember to check your wallet on a frequent basis to get rid of all of the old stuff that you don&rsquo;t need anymore.</p>\n\n<p style=\"text-align:center\"><img alt=\"f1\" src=\"/storage/news/3.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 3: Don&rsquo;t Limit Your Options Of Materials</strong></h4>\n\n<p>The types and designs of wallets are not the only things that you should consider when you go out searching for your best wallet. You have more than 1 option of material rather than leather to choose from as well.</p>\n\n<p>You can experiment with other available options such as cotton, polyester and canvas. They all have their own pros and cons. As a result, they will be suitable for different needs and requirements. You should think about them all in order to choose the material which you would like the most.</p>\n\n<p style=\"text-align:center\"><img alt=\"f6\" src=\"/storage/news/4.jpg\" /></p>\n\n<p><br />\n&nbsp;</p>\n\n<hr />\n<h4><strong>Number 4: Consider A Wallet As A Long Term Investment</strong></h4>\n\n<p>Your wallet is indeed an investment that you should consider spending a decent amount of time and effort on it. Another factor that you need to consider is how much you want to spend on your wallet. The price ranges of wallets on the market vary a great deal. You can find a wallet which is as cheap as about 5 to 7 dollars. On the other hand, you should expect to pay around 250 to 300 dollars for a high-quality wallet.</p>\n\n<p>In case you need a wallet to use for a long time, it is a good idea that you should invest a decent amount of money on a wallet. A high quality wallet from a reputational brand with the premium quality such as cowhide leather will last for a long time. In addition, it is an accessory to show off your fashion sense and your social status.</p>\n\n<p style=\"text-align:center\"><img alt=\"f2\" src=\"/storage/news/5.jpg\" /></p>\n\n<p>&nbsp;</p>\n','published',1,'Botble\\ACL\\Models\\User',0,'news/11.jpg',1373,NULL,'2025-09-11 01:46:57','2025-09-11 01:46:57');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts_translations`
--

DROP TABLE IF EXISTS `posts_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `posts_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `posts_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `content` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`lang_code`,`posts_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts_translations`
--

LOCK TABLES `posts_translations` WRITE;
/*!40000 ALTER TABLE `posts_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `posts_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notification_recipients`
--

DROP TABLE IF EXISTS `push_notification_recipients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notification_recipients` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `push_notification_id` bigint unsigned NOT NULL,
  `user_type` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `device_token` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `platform` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_at` timestamp NULL DEFAULT NULL,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `clicked_at` timestamp NULL DEFAULT NULL,
  `fcm_response` json DEFAULT NULL,
  `error_message` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `pnr_notification_user_index` (`push_notification_id`,`user_type`,`user_id`),
  KEY `pnr_user_status_index` (`user_type`,`user_id`,`status`),
  KEY `pnr_user_read_index` (`user_type`,`user_id`,`read_at`),
  KEY `pnr_status_index` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notification_recipients`
--

LOCK TABLES `push_notification_recipients` WRITE;
/*!40000 ALTER TABLE `push_notification_recipients` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notification_recipients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `push_notifications`
--

DROP TABLE IF EXISTS `push_notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `push_notifications` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'general',
  `target_type` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `target_value` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `action_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image_url` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `data` json DEFAULT NULL,
  `status` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'sent',
  `sent_count` int NOT NULL DEFAULT '0',
  `failed_count` int NOT NULL DEFAULT '0',
  `delivered_count` int NOT NULL DEFAULT '0',
  `read_count` int NOT NULL DEFAULT '0',
  `scheduled_at` timestamp NULL DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT NULL,
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `push_notifications_type_created_at_index` (`type`,`created_at`),
  KEY `push_notifications_status_scheduled_at_index` (`status`,`scheduled_at`),
  KEY `push_notifications_created_by_index` (`created_by`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `push_notifications`
--

LOCK TABLES `push_notifications` WRITE;
/*!40000 ALTER TABLE `push_notifications` DISABLE KEYS */;
/*!40000 ALTER TABLE `push_notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `revisions`
--

DROP TABLE IF EXISTS `revisions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `revisions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `revisionable_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revisionable_id` bigint unsigned NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `old_value` text COLLATE utf8mb4_unicode_ci,
  `new_value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `revisions_revisionable_id_revisionable_type_index` (`revisionable_id`,`revisionable_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `revisions`
--

LOCK TABLES `revisions` WRITE;
/*!40000 ALTER TABLE `revisions` DISABLE KEYS */;
/*!40000 ALTER TABLE `revisions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `role_users`
--

DROP TABLE IF EXISTS `role_users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `role_users` (
  `user_id` bigint unsigned NOT NULL,
  `role_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`user_id`,`role_id`),
  KEY `role_users_user_id_index` (`user_id`),
  KEY `role_users_role_id_index` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `role_users`
--

LOCK TABLES `role_users` WRITE;
/*!40000 ALTER TABLE `role_users` DISABLE KEYS */;
/*!40000 ALTER TABLE `role_users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `roles`
--

DROP TABLE IF EXISTS `roles`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `roles` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `created_by` bigint unsigned NOT NULL,
  `updated_by` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `roles_slug_unique` (`slug`),
  KEY `roles_created_by_index` (`created_by`),
  KEY `roles_updated_by_index` (`updated_by`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `roles`
--

LOCK TABLES `roles` WRITE;
/*!40000 ALTER TABLE `roles` DISABLE KEYS */;
INSERT INTO `roles` VALUES (1,'admin','Admin','{\"users.index\":true,\"users.create\":true,\"users.edit\":true,\"users.destroy\":true,\"roles.index\":true,\"roles.create\":true,\"roles.edit\":true,\"roles.destroy\":true,\"core.system\":true,\"core.cms\":true,\"core.manage.license\":true,\"systems.cronjob\":true,\"core.tools\":true,\"tools.data-synchronize\":true,\"media.index\":true,\"files.index\":true,\"files.create\":true,\"files.edit\":true,\"files.trash\":true,\"files.destroy\":true,\"folders.index\":true,\"folders.create\":true,\"folders.edit\":true,\"folders.trash\":true,\"folders.destroy\":true,\"settings.index\":true,\"settings.common\":true,\"settings.options\":true,\"settings.email\":true,\"settings.media\":true,\"settings.admin-appearance\":true,\"settings.cache\":true,\"settings.datatables\":true,\"settings.email.rules\":true,\"settings.others\":true,\"menus.index\":true,\"menus.create\":true,\"menus.edit\":true,\"menus.destroy\":true,\"optimize.settings\":true,\"pages.index\":true,\"pages.create\":true,\"pages.edit\":true,\"pages.destroy\":true,\"plugins.index\":true,\"plugins.edit\":true,\"plugins.remove\":true,\"plugins.marketplace\":true,\"sitemap.settings\":true,\"core.appearance\":true,\"theme.index\":true,\"theme.activate\":true,\"theme.remove\":true,\"theme.options\":true,\"theme.custom-css\":true,\"theme.custom-js\":true,\"theme.custom-html\":true,\"theme.robots-txt\":true,\"settings.website-tracking\":true,\"widgets.index\":true,\"ads.index\":true,\"ads.create\":true,\"ads.edit\":true,\"ads.destroy\":true,\"ads.settings\":true,\"analytics.general\":true,\"analytics.page\":true,\"analytics.browser\":true,\"analytics.referrer\":true,\"analytics.settings\":true,\"audit-log.index\":true,\"audit-log.destroy\":true,\"backups.index\":true,\"backups.create\":true,\"backups.restore\":true,\"backups.destroy\":true,\"plugins.blog\":true,\"posts.index\":true,\"posts.create\":true,\"posts.edit\":true,\"posts.destroy\":true,\"categories.index\":true,\"categories.create\":true,\"categories.edit\":true,\"categories.destroy\":true,\"tags.index\":true,\"tags.create\":true,\"tags.edit\":true,\"tags.destroy\":true,\"blog.settings\":true,\"posts.export\":true,\"posts.import\":true,\"captcha.settings\":true,\"contacts.index\":true,\"contacts.edit\":true,\"contacts.destroy\":true,\"contact.custom-fields\":true,\"contact.settings\":true,\"plugins.ecommerce\":true,\"ecommerce.report.index\":true,\"products.index\":true,\"products.create\":true,\"products.edit\":true,\"products.destroy\":true,\"products.duplicate\":true,\"ecommerce.product-prices.index\":true,\"ecommerce.product-prices.edit\":true,\"ecommerce.product-inventory.index\":true,\"ecommerce.product-inventory.edit\":true,\"product-categories.index\":true,\"product-categories.create\":true,\"product-categories.edit\":true,\"product-categories.destroy\":true,\"product-tag.index\":true,\"product-tag.create\":true,\"product-tag.edit\":true,\"product-tag.destroy\":true,\"brands.index\":true,\"brands.create\":true,\"brands.edit\":true,\"brands.destroy\":true,\"product-collections.index\":true,\"product-collections.create\":true,\"product-collections.edit\":true,\"product-collections.destroy\":true,\"product-attribute-sets.index\":true,\"product-attribute-sets.create\":true,\"product-attribute-sets.edit\":true,\"product-attribute-sets.destroy\":true,\"product-attributes.index\":true,\"product-attributes.create\":true,\"product-attributes.edit\":true,\"product-attributes.destroy\":true,\"tax.index\":true,\"tax.create\":true,\"tax.edit\":true,\"tax.destroy\":true,\"reviews.index\":true,\"reviews.create\":true,\"reviews.destroy\":true,\"reviews.publish\":true,\"reviews.reply\":true,\"ecommerce.shipments.index\":true,\"ecommerce.shipments.create\":true,\"ecommerce.shipments.edit\":true,\"ecommerce.shipments.destroy\":true,\"orders.index\":true,\"orders.create\":true,\"orders.edit\":true,\"orders.destroy\":true,\"discounts.index\":true,\"discounts.create\":true,\"discounts.edit\":true,\"discounts.destroy\":true,\"customers.index\":true,\"customers.create\":true,\"customers.edit\":true,\"customers.destroy\":true,\"ecommerce.customers.import\":true,\"ecommerce.customers.export\":true,\"flash-sale.index\":true,\"flash-sale.create\":true,\"flash-sale.edit\":true,\"flash-sale.destroy\":true,\"product-label.index\":true,\"product-label.create\":true,\"product-label.edit\":true,\"product-label.destroy\":true,\"ecommerce.import.products.index\":true,\"ecommerce.export.products.index\":true,\"order_returns.index\":true,\"order_returns.edit\":true,\"order_returns.destroy\":true,\"global-option.index\":true,\"global-option.create\":true,\"global-option.edit\":true,\"global-option.destroy\":true,\"ecommerce.invoice.index\":true,\"ecommerce.invoice.edit\":true,\"ecommerce.invoice.destroy\":true,\"ecommerce.settings\":true,\"ecommerce.settings.general\":true,\"ecommerce.invoice-template.index\":true,\"ecommerce.settings.currencies\":true,\"ecommerce.settings.products\":true,\"ecommerce.settings.product-search\":true,\"ecommerce.settings.digital-products\":true,\"ecommerce.settings.store-locators\":true,\"ecommerce.settings.invoices\":true,\"ecommerce.settings.product-reviews\":true,\"ecommerce.settings.customers\":true,\"ecommerce.settings.shopping\":true,\"ecommerce.settings.taxes\":true,\"ecommerce.settings.shipping\":true,\"ecommerce.shipping-rule-items.index\":true,\"ecommerce.shipping-rule-items.create\":true,\"ecommerce.shipping-rule-items.edit\":true,\"ecommerce.shipping-rule-items.destroy\":true,\"ecommerce.shipping-rule-items.bulk-import\":true,\"ecommerce.settings.tracking\":true,\"ecommerce.settings.standard-and-format\":true,\"ecommerce.settings.checkout\":true,\"ecommerce.settings.return\":true,\"ecommerce.settings.flash-sale\":true,\"ecommerce.settings.product-specification\":true,\"product-categories.export\":true,\"product-categories.import\":true,\"orders.export\":true,\"ecommerce.product-specification.index\":true,\"ecommerce.specification-groups.index\":true,\"ecommerce.specification-groups.create\":true,\"ecommerce.specification-groups.edit\":true,\"ecommerce.specification-groups.destroy\":true,\"ecommerce.specification-attributes.index\":true,\"ecommerce.specification-attributes.create\":true,\"ecommerce.specification-attributes.edit\":true,\"ecommerce.specification-attributes.destroy\":true,\"ecommerce.specification-tables.index\":true,\"ecommerce.specification-tables.create\":true,\"ecommerce.specification-tables.edit\":true,\"ecommerce.specification-tables.destroy\":true,\"plugin.faq\":true,\"faq.index\":true,\"faq.create\":true,\"faq.edit\":true,\"faq.destroy\":true,\"faq_category.index\":true,\"faq_category.create\":true,\"faq_category.edit\":true,\"faq_category.destroy\":true,\"faqs.settings\":true,\"languages.index\":true,\"languages.create\":true,\"languages.edit\":true,\"languages.destroy\":true,\"translations.import\":true,\"translations.export\":true,\"property-translations.import\":true,\"property-translations.export\":true,\"plugin.location\":true,\"country.index\":true,\"country.create\":true,\"country.edit\":true,\"country.destroy\":true,\"state.index\":true,\"state.create\":true,\"state.edit\":true,\"state.destroy\":true,\"city.index\":true,\"city.create\":true,\"city.edit\":true,\"city.destroy\":true,\"marketplace.index\":true,\"marketplace.store.index\":true,\"marketplace.store.create\":true,\"marketplace.store.edit\":true,\"marketplace.store.destroy\":true,\"marketplace.store.view\":true,\"marketplace.store.revenue.create\":true,\"marketplace.withdrawal.index\":true,\"marketplace.withdrawal.edit\":true,\"marketplace.withdrawal.destroy\":true,\"marketplace.withdrawal.invoice\":true,\"marketplace.vendors.index\":true,\"marketplace.unverified-vendors.index\":true,\"marketplace.vendors.control\":true,\"marketplace.unverified-vendors.edit\":true,\"marketplace.reports\":true,\"marketplace.settings\":true,\"marketplace.messages.index\":true,\"marketplace.messages.edit\":true,\"marketplace.messages.destroy\":true,\"newsletter.index\":true,\"newsletter.destroy\":true,\"newsletter.settings\":true,\"payment.index\":true,\"payments.settings\":true,\"payment.destroy\":true,\"payments.logs\":true,\"payments.logs.show\":true,\"payments.logs.destroy\":true,\"simple-slider.index\":true,\"simple-slider.create\":true,\"simple-slider.edit\":true,\"simple-slider.destroy\":true,\"simple-slider-item.index\":true,\"simple-slider-item.create\":true,\"simple-slider-item.edit\":true,\"simple-slider-item.destroy\":true,\"simple-slider.settings\":true,\"social-login.settings\":true,\"plugins.translation\":true,\"translations.locales\":true,\"translations.theme-translations\":true,\"translations.index\":true,\"theme-translations.export\":true,\"other-translations.export\":true,\"theme-translations.import\":true,\"other-translations.import\":true,\"api.settings\":true,\"api.sanctum-token.index\":true,\"api.sanctum-token.create\":true,\"api.sanctum-token.destroy\":true}','Admin users role',1,1,1,'2025-09-11 01:45:50','2025-09-11 01:45:50');
/*!40000 ALTER TABLE `roles` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB AUTO_INCREMENT=84 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES (1,'media_random_hash','3a68dadbd7e1cf203c5aa7c9f03d2796',NULL,'2025-09-11 01:47:12'),(2,'api_enabled','0',NULL,'2025-09-11 01:47:12'),(3,'activated_plugins','[\"language\",\"language-advanced\",\"ads\",\"analytics\",\"audit-log\",\"backup\",\"blog\",\"captcha\",\"contact\",\"cookie-consent\",\"ecommerce\",\"faq\",\"location\",\"marketplace\",\"mollie\",\"newsletter\",\"payment\",\"paypal\",\"paypal-payout\",\"paystack\",\"razorpay\",\"shippo\",\"simple-slider\",\"social-login\",\"sslcommerz\",\"stripe\",\"stripe-connect\",\"translation\"]',NULL,'2025-09-11 01:47:12'),(4,'analytics_dashboard_widgets','0','2025-09-11 01:45:42','2025-09-11 01:45:42'),(5,'enable_recaptcha_botble_contact_forms_fronts_contact_form','1','2025-09-11 01:45:43','2025-09-11 01:45:43'),(6,'api_layer_api_key','',NULL,'2025-09-11 01:47:12'),(9,'enable_recaptcha_botble_newsletter_forms_fronts_newsletter_form','1','2025-09-11 01:45:48','2025-09-11 01:45:48'),(10,'payment_cod_fee_type','fixed',NULL,'2025-09-11 01:47:12'),(11,'payment_bank_transfer_fee_type','fixed',NULL,'2025-09-11 01:47:12'),(12,'theme','farmart',NULL,'2025-09-11 01:47:12'),(13,'show_admin_bar','1',NULL,'2025-09-11 01:47:12'),(14,'language_hide_default','1',NULL,'2025-09-11 01:47:12'),(16,'language_display','all',NULL,'2025-09-11 01:47:12'),(17,'language_hide_languages','[]',NULL,'2025-09-11 01:47:12'),(18,'ecommerce_store_name','Farmart',NULL,'2025-09-11 01:47:12'),(19,'ecommerce_store_phone','1800979769',NULL,'2025-09-11 01:47:12'),(20,'ecommerce_store_address','502 New Street',NULL,'2025-09-11 01:47:12'),(21,'ecommerce_store_state','Brighton VIC',NULL,'2025-09-11 01:47:12'),(22,'ecommerce_store_city','Brighton VIC',NULL,'2025-09-11 01:47:12'),(23,'ecommerce_store_country','AU',NULL,'2025-09-11 01:47:12'),(24,'simple_slider_using_assets','0',NULL,'2025-09-11 01:47:12'),(25,'language_switcher_display','dropdown',NULL,'2025-09-11 01:47:12'),(26,'admin_favicon','general/favicon.png',NULL,'2025-09-11 01:47:12'),(27,'admin_logo','general/logo-light.png',NULL,'2025-09-11 01:47:12'),(28,'payment_cod_status','1',NULL,'2025-09-11 01:47:12'),(29,'payment_bank_transfer_status','1',NULL,'2025-09-11 01:47:12'),(30,'payment_cod_description','Please pay money directly to the postman, if you choose cash on delivery method (COD).',NULL,'2025-09-11 01:47:12'),(31,'payment_bank_transfer_description','Please send money to our bank account: ACB - 69270 213 19.',NULL,'2025-09-11 01:47:12'),(32,'payment_stripe_payment_type','stripe_checkout',NULL,'2025-09-11 01:47:12'),(33,'plugins_ecommerce_customer_new_order_status','0',NULL,'2025-09-11 01:47:12'),(34,'plugins_ecommerce_admin_new_order_status','0',NULL,'2025-09-11 01:47:12'),(35,'ecommerce_is_enabled_support_digital_products','1',NULL,'2025-09-11 01:47:12'),(36,'ecommerce_enable_license_codes_for_digital_products','1',NULL,'2025-09-11 01:47:12'),(37,'ecommerce_auto_complete_digital_orders_after_payment','1',NULL,'2025-09-11 01:47:12'),(38,'ecommerce_load_countries_states_cities_from_location_plugin','0',NULL,'2025-09-11 01:47:12'),(39,'ecommerce_product_sku_format','SF-2443-%s%s%s%s',NULL,'2025-09-11 01:47:12'),(40,'ecommerce_store_order_prefix','SF',NULL,'2025-09-11 01:47:12'),(41,'ecommerce_enable_product_specification','1',NULL,'2025-09-11 01:47:12'),(42,'payment_bank_transfer_display_bank_info_at_the_checkout_success_page','1',NULL,'2025-09-11 01:47:12'),(43,'payment_cod_logo','payments/cod.png',NULL,'2025-09-11 01:47:12'),(44,'payment_bank_transfer_logo','payments/bank-transfer.png',NULL,'2025-09-11 01:47:12'),(45,'payment_stripe_logo','payments/stripe.webp',NULL,'2025-09-11 01:47:12'),(46,'payment_paypal_logo','payments/paypal.png',NULL,'2025-09-11 01:47:12'),(47,'payment_mollie_logo','payments/mollie.png',NULL,'2025-09-11 01:47:12'),(48,'payment_paystack_logo','payments/paystack.png',NULL,'2025-09-11 01:47:12'),(49,'payment_razorpay_logo','payments/razorpay.png',NULL,'2025-09-11 01:47:12'),(50,'payment_sslcommerz_logo','payments/sslcommerz.png',NULL,'2025-09-11 01:47:12'),(51,'permalink-botble-blog-models-post','blog',NULL,'2025-09-11 01:47:12'),(52,'permalink-botble-blog-models-category','blog',NULL,'2025-09-11 01:47:12'),(53,'theme-farmart-site_title','Farmart - Laravel Ecommerce system',NULL,NULL),(54,'theme-farmart-seo_description','Farmart is a modern and flexible Multipurpose Marketplace Laravel script. This script is suited for electronic, organic and grocery store, furniture store, clothing store, hitech store and accessories store… With the theme, you can create your own marketplace and allow vendors to sell just like Amazon, Envato, eBay…',NULL,NULL),(55,'theme-farmart-copyright','© %Y Farmart. All Rights Reserved.',NULL,NULL),(56,'theme-farmart-favicon','general/favicon.png',NULL,NULL),(57,'theme-farmart-logo','general/logo.png',NULL,NULL),(58,'theme-farmart-seo_og_image','general/open-graph-image.png',NULL,NULL),(59,'theme-farmart-image-placeholder','general/placeholder.png',NULL,NULL),(60,'theme-farmart-address','502 New Street, Brighton VIC, Australia',NULL,NULL),(61,'theme-farmart-hotline','8 800 332 65-66',NULL,NULL),(62,'theme-farmart-email','contact@fartmart.co',NULL,NULL),(63,'theme-farmart-working_time','Mon - Fri: 07AM - 06PM',NULL,NULL),(64,'theme-farmart-payment_methods_image','general/footer-payments.png',NULL,NULL),(65,'theme-farmart-homepage_id','1',NULL,NULL),(66,'theme-farmart-blog_page_id','6',NULL,NULL),(67,'theme-farmart-cookie_consent_message','Your experience on this site will be improved by allowing cookies ',NULL,NULL),(68,'theme-farmart-cookie_consent_learn_more_url','/cookie-policy',NULL,NULL),(69,'theme-farmart-cookie_consent_learn_more_text','Cookie Policy',NULL,NULL),(70,'theme-farmart-number_of_products_per_page','40',NULL,NULL),(71,'theme-farmart-number_of_cross_sale_product','6',NULL,NULL),(72,'theme-farmart-logo_in_the_checkout_page','general/logo.png',NULL,NULL),(73,'theme-farmart-logo_in_invoices','general/logo.png',NULL,NULL),(74,'theme-farmart-logo_vendor_dashboard','general/logo.png',NULL,NULL),(75,'theme-farmart-404_page_image','general/404.png',NULL,NULL),(76,'theme-farmart-social_links','[[{\"key\":\"name\",\"value\":\"Facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.facebook.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#3b5999\"}],[{\"key\":\"name\",\"value\":\"X (Twitter)\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"url\",\"value\":\"https:\\/\\/x.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#000\"}],[{\"key\":\"name\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"url\",\"value\":\"https:\\/\\/www.linkedin.com\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background-color\",\"value\":\"#0a66c2\"}]]',NULL,NULL),(77,'theme-farmart-social_sharing','[[{\"key\":\"name\",\"value\":\"Facebook\"},{\"key\":\"social\",\"value\":\"facebook\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-facebook\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#3b5999\"}],[{\"key\":\"name\",\"value\":\"X (Twitter)\"},{\"key\":\"social\",\"value\":\"x\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-x\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#55acee\"}],[{\"key\":\"name\",\"value\":\"Pinterest\"},{\"key\":\"social\",\"value\":\"pinterest\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-pinterest\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#b10c0c\"}],[{\"key\":\"name\",\"value\":\"Linkedin\"},{\"key\":\"social\",\"value\":\"linkedin\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-linkedin\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#0271ae\"}],[{\"key\":\"name\",\"value\":\"Whatsapp\"},{\"key\":\"social\",\"value\":\"whatsapp\"},{\"key\":\"icon\",\"value\":\"ti ti-brand-whatsapp\"},{\"key\":\"icon_image\",\"value\":null},{\"key\":\"color\",\"value\":\"#fff\"},{\"key\":\"background_color\",\"value\":\"#25d366\"}]]',NULL,NULL),(78,'theme-farmart-primary_font','Mulish',NULL,NULL),(79,'theme-farmart-newsletter_popup_enable','1',NULL,NULL),(80,'theme-farmart-newsletter_popup_image','general/newsletter-popup.png',NULL,NULL),(81,'theme-farmart-newsletter_popup_title','Subscribe Now',NULL,NULL),(82,'theme-farmart-newsletter_popup_subtitle','Newsletter',NULL,NULL),(83,'theme-farmart-newsletter_popup_description','Subscribe to our newsletter and get 10% off your first purchase',NULL,NULL);
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_slider_items`
--

DROP TABLE IF EXISTS `simple_slider_items`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `simple_slider_items` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `simple_slider_id` bigint unsigned NOT NULL,
  `title` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `image` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `link` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text COLLATE utf8mb4_unicode_ci,
  `order` int unsigned NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_slider_items`
--

LOCK TABLES `simple_slider_items` WRITE;
/*!40000 ALTER TABLE `simple_slider_items` DISABLE KEYS */;
INSERT INTO `simple_slider_items` VALUES (1,1,'Slider 1','sliders/01.jpg','/products',NULL,1,'2025-09-11 01:46:58','2025-09-11 01:46:58'),(2,1,'Slider 2','sliders/02.jpg','/products',NULL,2,'2025-09-11 01:46:58','2025-09-11 01:46:58');
/*!40000 ALTER TABLE `simple_slider_items` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `simple_sliders`
--

DROP TABLE IF EXISTS `simple_sliders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `simple_sliders` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `simple_sliders`
--

LOCK TABLES `simple_sliders` WRITE;
/*!40000 ALTER TABLE `simple_sliders` DISABLE KEYS */;
INSERT INTO `simple_sliders` VALUES (1,'Home slider','home-slider','The main slider on homepage','published','2025-09-11 01:46:58','2025-09-11 01:46:58');
/*!40000 ALTER TABLE `simple_sliders` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs`
--

DROP TABLE IF EXISTS `slugs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `reference_id` bigint unsigned NOT NULL,
  `reference_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `slugs_reference_id_index` (`reference_id`),
  KEY `slugs_key_index` (`key`),
  KEY `slugs_prefix_index` (`prefix`),
  KEY `slugs_reference_index` (`reference_id`,`reference_type`),
  KEY `idx_slugs_reference` (`reference_type`,`reference_id`)
) ENGINE=InnoDB AUTO_INCREMENT=281 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs`
--

LOCK TABLES `slugs` WRITE;
/*!40000 ALTER TABLE `slugs` DISABLE KEYS */;
INSERT INTO `slugs` VALUES (1,'foodpound',1,'Botble\\Ecommerce\\Models\\Brand','brands','2025-09-11 01:45:51','2025-09-11 01:45:51'),(2,'itea-jsc',2,'Botble\\Ecommerce\\Models\\Brand','brands','2025-09-11 01:45:51','2025-09-11 01:45:51'),(3,'soda-brand',3,'Botble\\Ecommerce\\Models\\Brand','brands','2025-09-11 01:45:51','2025-09-11 01:45:51'),(4,'farmart',4,'Botble\\Ecommerce\\Models\\Brand','brands','2025-09-11 01:45:51','2025-09-11 01:45:51'),(5,'soda-brand',5,'Botble\\Ecommerce\\Models\\Brand','brands','2025-09-11 01:45:51','2025-09-11 01:45:51'),(6,'fruits-vegetables',1,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(7,'fruits',2,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(8,'apples',3,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(9,'bananas',4,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(10,'berries',5,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(11,'oranges-easy-peelers',6,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(12,'grapes',7,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(13,'lemons-limes',8,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:51','2025-09-11 01:45:51'),(14,'peaches-nectarines',9,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(15,'pears',10,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(16,'melon',11,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(17,'avocados',12,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(18,'plums-apricots',13,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(19,'vegetables',14,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(20,'potatoes',15,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(21,'carrots-root-vegetables',16,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(22,'broccoli-cauliflower',17,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(23,'cabbage-spinach-greens',18,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(24,'onions-leeks-garlic',19,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(25,'mushrooms',20,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(26,'tomatoes',21,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(27,'beans-peas-sweetcorn',22,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(28,'freshly-drink-orange-juice',23,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(29,'breads-sweets',24,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(30,'crisps-snacks-nuts',25,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(31,'crisps-popcorn',26,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(32,'nuts-seeds',27,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(33,'lighter-options',28,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(34,'cereal-bars',29,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(35,'breadsticks-pretzels',30,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(36,'fruit-snacking',31,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(37,'rice-corn-cakes',32,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(38,'protein-energy-snacks',33,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(39,'toddler-snacks',34,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(40,'meat-snacks',35,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(41,'beans',36,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(42,'lentils',37,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(43,'chickpeas',38,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(44,'tins-cans',39,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(45,'tomatoes',40,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(46,'baked-beans-spaghetti',41,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(47,'fish',42,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(48,'beans-pulses',43,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(49,'fruit',44,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(50,'coconut-milk-cream',45,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(51,'lighter-options',46,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(52,'olives',47,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(53,'sweetcorn',48,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(54,'carrots',49,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(55,'peas',50,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(56,'mixed-vegetables',51,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(57,'frozen-seafoods',52,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(58,'raw-meats',53,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(59,'wines-alcohol-drinks',54,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(60,'ready-meals',55,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(61,'meals-for-1',56,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(62,'meals-for-2',57,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(63,'indian',58,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(64,'italian',59,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(65,'chinese',60,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(66,'traditional-british',61,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(67,'thai-oriental',62,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(68,'mediterranean-moroccan',63,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(69,'mexican-caribbean',64,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(70,'lighter-meals',65,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(71,'lunch-veg-pots',66,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(72,'salad-herbs',67,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(73,'salad-bags',68,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(74,'cucumber',69,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(75,'tomatoes',70,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(76,'lettuce',71,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(77,'lunch-salad-bowls',72,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(78,'lunch-salad-bowls',73,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(79,'fresh-herbs',74,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(80,'avocados',75,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(81,'peppers',76,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(82,'coleslaw-potato-salad',77,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(83,'spring-onions',78,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(84,'chilli-ginger-garlic',79,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(85,'tea-coffee',80,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(86,'milks-and-dairies',81,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(87,'pet-foods',82,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:52','2025-09-11 01:45:52'),(88,'food-cupboard',83,'Botble\\Ecommerce\\Models\\ProductCategory','product-categories','2025-09-11 01:45:53','2025-09-11 01:45:53'),(89,'electronic',1,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(90,'mobile',2,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(91,'iphone',3,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(92,'printer',4,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(93,'office',5,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(94,'it',6,'Botble\\Ecommerce\\Models\\ProductTag','product-tags','2025-09-11 01:45:59','2025-09-11 01:45:59'),(95,'dual-camera-20mp',1,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:13','2025-09-11 01:46:13'),(96,'smart-watches',2,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:13','2025-09-11 01:46:13'),(97,'beat-headphone',3,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(98,'red-black-headphone-digital',4,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:47:12'),(99,'smart-watch-external',5,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(100,'nikon-hd-camera',6,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(101,'audio-equipment',7,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(102,'smart-televisions-digital',8,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:47:12'),(103,'samsung-smart-phone',9,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(104,'herschel-leather-duffle-bag-in-brown-color',10,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(105,'xbox-one-wireless-controller-black-color',11,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:14','2025-09-11 01:46:14'),(106,'epsion-plaster-printer-digital',12,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:47:12'),(107,'sound-intone-i65-earphone-white-version',13,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(108,'bo-play-mini-bluetooth-speaker',14,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(109,'apple-macbook-air-retina-133-inch-laptop',15,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(110,'apple-macbook-air-retina-12-inch-laptop-digital',16,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:47:12'),(111,'samsung-gear-vr-virtual-reality-headset',17,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(112,'aveeno-moisturizing-body-shower-450ml',18,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(113,'nyx-beauty-couton-pallete-makeup-12',19,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(114,'nyx-beauty-couton-pallete-makeup-12-digital',20,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:47:12'),(115,'mvmth-classical-leather-watch-in-black',21,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:15','2025-09-11 01:46:15'),(116,'baxter-care-hair-kit-for-bearded-mens',22,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(117,'ciate-palemore-lipstick-bold-red-color',23,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(118,'vimto-squash-remix-apple-15-litres-digital',24,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:47:12'),(119,'crock-pot-slow-cooker',25,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(120,'taylors-of-harrogate-yorkshire-coffee',26,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(121,'soft-mochi-galeto-ice-cream',27,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(122,'naked-noodle-egg-noodles-singapore-digital',28,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:47:12'),(123,'saute-pan-silver',29,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(124,'bar-s-classic-bun-length-franks',30,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:16','2025-09-11 01:46:16'),(125,'broccoli-crowns',31,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(126,'slimming-world-vegan-mac-greens-digital',32,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:47:12'),(127,'haagen-dazs-salted-caramel',33,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(128,'iceland-3-solo-exotic-burst',34,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(129,'extreme-budweiser-light-can',35,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(130,'iceland-macaroni-cheese-traybake-digital',36,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:47:12'),(131,'dolmio-bolognese-pasta-sauce',37,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(132,'sitema-bakeit-plastic-box',38,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(133,'wayfair-basics-dinner-plate-storage',39,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:17','2025-09-11 01:46:17'),(134,'miko-the-panda-water-bottle-digital',40,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:47:12'),(135,'sesame-seed-bread',41,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(136,'morrisons-the-best-beef',42,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(137,'avocado-hass-large',43,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(138,'italia-beef-lasagne-digital',44,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:47:12'),(139,'maxwell-house-classic-roast-mocha',45,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(140,'bottled-pure-water-500ml',46,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(141,'famart-farmhouse-soft-white',47,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:18','2025-09-11 01:46:18'),(142,'coca-cola-original-taste-digital',48,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:47:12'),(143,'casillero-diablo-cabernet-sauvignon',49,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(144,'arla-organic-free-range-milk',50,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(145,'aptamil-follow-on-baby-milk',51,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(146,'cuisinart-chefs-classic-hard-anodized-digital',52,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:47:13'),(147,'corn-yellow-sweet',53,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(148,'hobnobs-the-nobbly-biscuit',54,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(149,'honest-organic-still-lemonade',55,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:46:19'),(150,'ice-becks-beer-350ml-x-24-pieces-digital',56,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:19','2025-09-11 01:47:13'),(151,'iceland-6-hot-cross-buns',57,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(152,'iceland-luxury-4-panini-rolls',58,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(153,'iceland-soft-scoop-vanilla',59,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(154,'iceland-spaghetti-bolognese-digital',60,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:47:13'),(155,'kelloggs-coco-pops-cereal',61,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(156,'kit-kat-chunky-milk-chocolate',62,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(157,'large-green-bell-pepper',63,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:20','2025-09-11 01:46:20'),(158,'pice-94w-beasley-journal-digital',64,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:21','2025-09-11 01:47:13'),(159,'province-piece-glass-drinking-glass',65,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:46:21','2025-09-11 01:46:21'),(160,'gopro',1,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(161,'global-office',2,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(162,'young-shop',3,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(163,'global-store',4,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(164,'roberts-store',5,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(165,'stouffer',6,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(166,'starkist',7,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(167,'old-el-paso',8,'Botble\\Marketplace\\Models\\Store','stores','2025-09-11 01:46:42','2025-09-11 01:46:42'),(168,'ecommerce',1,'Botble\\Blog\\Models\\Category','blog','2025-09-11 01:46:56','2025-09-11 01:47:00'),(169,'fashion',2,'Botble\\Blog\\Models\\Category','blog','2025-09-11 01:46:56','2025-09-11 01:47:00'),(170,'electronic',3,'Botble\\Blog\\Models\\Category','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(171,'commercial',4,'Botble\\Blog\\Models\\Category','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(172,'general',1,'Botble\\Blog\\Models\\Tag','tag','2025-09-11 01:46:57','2025-09-11 01:46:57'),(173,'design',2,'Botble\\Blog\\Models\\Tag','tag','2025-09-11 01:46:57','2025-09-11 01:46:57'),(174,'fashion',3,'Botble\\Blog\\Models\\Tag','tag','2025-09-11 01:46:57','2025-09-11 01:46:57'),(175,'branding',4,'Botble\\Blog\\Models\\Tag','tag','2025-09-11 01:46:57','2025-09-11 01:46:57'),(176,'modern',5,'Botble\\Blog\\Models\\Tag','tag','2025-09-11 01:46:57','2025-09-11 01:46:57'),(177,'4-expert-tips-on-how-to-choose-the-right-mens-wallet',1,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(178,'sexy-clutches-how-to-buy-wear-a-designer-clutch-bag',2,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(179,'the-top-2020-handbag-trends-to-know',3,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(180,'how-to-match-the-color-of-your-handbag-with-an-outfit',4,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(181,'how-to-care-for-leather-bags',5,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(182,'were-crushing-hard-on-summers-10-biggest-bag-trends',6,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(183,'essential-qualities-of-highly-successful-music',7,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(184,'9-things-i-love-about-shaving-my-head',8,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(185,'why-teamwork-really-makes-the-dream-work',9,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(186,'the-world-caters-to-average-people',10,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(187,'the-litigants-on-the-screen-are-not-actors',11,'Botble\\Blog\\Models\\Post','blog','2025-09-11 01:46:57','2025-09-11 01:47:00'),(188,'home',1,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(189,'about-us',2,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(190,'terms-of-use',3,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(191,'terms-conditions',4,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(192,'refund-policy',5,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(193,'blog',6,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(194,'faqs',7,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(195,'contact',8,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(196,'cookie-policy',9,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(197,'affiliate',10,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(198,'career',11,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(199,'coming-soon',12,'Botble\\Page\\Models\\Page','','2025-09-11 01:46:58','2025-09-11 01:46:58'),(200,'dual-camera-20mp',66,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(201,'dual-camera-20mp',67,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(202,'dual-camera-20mp',68,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(203,'smart-watches',69,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(204,'smart-watches',70,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(205,'smart-watches',71,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(206,'smart-watches',72,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(207,'smart-watches',73,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(208,'beat-headphone',74,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(209,'beat-headphone',75,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(210,'smart-watch-external',76,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(211,'smart-watch-external',77,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(212,'smart-watch-external',78,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(213,'smart-watch-external',79,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(214,'nikon-hd-camera',80,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(215,'nikon-hd-camera',81,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(216,'smart-televisions-digital',82,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(217,'samsung-smart-phone',83,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(218,'epsion-plaster-printer-digital',84,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(219,'epsion-plaster-printer-digital',85,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(220,'epsion-plaster-printer-digital',86,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(221,'epsion-plaster-printer-digital',87,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(222,'bo-play-mini-bluetooth-speaker',88,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(223,'bo-play-mini-bluetooth-speaker',89,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(224,'apple-macbook-air-retina-133-inch-laptop',90,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(225,'apple-macbook-air-retina-133-inch-laptop',91,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(226,'apple-macbook-air-retina-133-inch-laptop',92,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(227,'apple-macbook-air-retina-133-inch-laptop',93,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(228,'samsung-gear-vr-virtual-reality-headset',94,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(229,'samsung-gear-vr-virtual-reality-headset',95,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(230,'nyx-beauty-couton-pallete-makeup-12',96,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(231,'nyx-beauty-couton-pallete-makeup-12',97,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(232,'nyx-beauty-couton-pallete-makeup-12',98,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(233,'baxter-care-hair-kit-for-bearded-mens',99,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(234,'baxter-care-hair-kit-for-bearded-mens',100,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(235,'baxter-care-hair-kit-for-bearded-mens',101,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(236,'soft-mochi-galeto-ice-cream',102,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(237,'soft-mochi-galeto-ice-cream',103,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(238,'soft-mochi-galeto-ice-cream',104,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(239,'bar-s-classic-bun-length-franks',105,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(240,'bar-s-classic-bun-length-franks',106,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(241,'bar-s-classic-bun-length-franks',107,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(242,'bar-s-classic-bun-length-franks',108,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(243,'broccoli-crowns',109,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(244,'iceland-3-solo-exotic-burst',110,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(245,'dolmio-bolognese-pasta-sauce',111,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(246,'dolmio-bolognese-pasta-sauce',112,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(247,'dolmio-bolognese-pasta-sauce',113,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(248,'wayfair-basics-dinner-plate-storage',114,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(249,'miko-the-panda-water-bottle-digital',115,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(250,'miko-the-panda-water-bottle-digital',116,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(251,'sesame-seed-bread',117,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(252,'avocado-hass-large',118,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(253,'avocado-hass-large',119,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(254,'avocado-hass-large',120,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(255,'avocado-hass-large',121,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(256,'italia-beef-lasagne-digital',122,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(257,'italia-beef-lasagne-digital',123,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(258,'italia-beef-lasagne-digital',124,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(259,'maxwell-house-classic-roast-mocha',125,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(260,'maxwell-house-classic-roast-mocha',126,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(261,'maxwell-house-classic-roast-mocha',127,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(262,'maxwell-house-classic-roast-mocha',128,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(263,'maxwell-house-classic-roast-mocha',129,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(264,'coca-cola-original-taste-digital',130,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(265,'casillero-diablo-cabernet-sauvignon',131,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(266,'casillero-diablo-cabernet-sauvignon',132,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(267,'aptamil-follow-on-baby-milk',133,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(268,'honest-organic-still-lemonade',134,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(269,'honest-organic-still-lemonade',135,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(270,'honest-organic-still-lemonade',136,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(271,'honest-organic-still-lemonade',137,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(272,'iceland-6-hot-cross-buns',138,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(273,'iceland-6-hot-cross-buns',139,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(274,'iceland-spaghetti-bolognese-digital',140,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(275,'iceland-spaghetti-bolognese-digital',141,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(276,'iceland-spaghetti-bolognese-digital',142,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(277,'large-green-bell-pepper',143,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(278,'large-green-bell-pepper',144,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(279,'province-piece-glass-drinking-glass',145,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13'),(280,'province-piece-glass-drinking-glass',146,'Botble\\Ecommerce\\Models\\Product','products','2025-09-11 01:47:13','2025-09-11 01:47:13');
/*!40000 ALTER TABLE `slugs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `slugs_translations`
--

DROP TABLE IF EXISTS `slugs_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `slugs_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slugs_id` bigint unsigned NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prefix` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT '',
  PRIMARY KEY (`lang_code`,`slugs_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `slugs_translations`
--

LOCK TABLES `slugs_translations` WRITE;
/*!40000 ALTER TABLE `slugs_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `slugs_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `social_logins`
--

DROP TABLE IF EXISTS `social_logins`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `social_logins` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `provider` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `provider_id` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` text COLLATE utf8mb4_unicode_ci,
  `refresh_token` text COLLATE utf8mb4_unicode_ci,
  `token_expires_at` timestamp NULL DEFAULT NULL,
  `provider_data` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `social_logins_provider_provider_id_unique` (`provider`,`provider_id`),
  KEY `social_logins_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `social_logins_user_id_user_type_index` (`user_id`,`user_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `social_logins`
--

LOCK TABLES `social_logins` WRITE;
/*!40000 ALTER TABLE `social_logins` DISABLE KEYS */;
/*!40000 ALTER TABLE `social_logins` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states`
--

DROP TABLE IF EXISTS `states`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abbreviation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `country_id` bigint unsigned DEFAULT NULL,
  `order` tinyint NOT NULL DEFAULT '0',
  `image` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_default` tinyint unsigned NOT NULL DEFAULT '0',
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `states_slug_unique` (`slug`),
  KEY `idx_states_name` (`name`),
  KEY `idx_states_status` (`status`),
  KEY `idx_states_country_id` (`country_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states`
--

LOCK TABLES `states` WRITE;
/*!40000 ALTER TABLE `states` DISABLE KEYS */;
/*!40000 ALTER TABLE `states` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `states_translations`
--

DROP TABLE IF EXISTS `states_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `states_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `states_id` bigint unsigned NOT NULL,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `slug` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `abbreviation` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`states_id`),
  KEY `idx_states_trans_state_lang` (`states_id`,`lang_code`),
  KEY `idx_states_trans_name` (`name`),
  KEY `idx_states_trans_states_id` (`states_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `states_translations`
--

LOCK TABLES `states_translations` WRITE;
/*!40000 ALTER TABLE `states_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `states_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `author_id` bigint unsigned DEFAULT NULL,
  `author_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'published',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (1,'General',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-09-11 01:46:57','2025-09-11 01:46:57'),(2,'Design',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-09-11 01:46:57','2025-09-11 01:46:57'),(3,'Fashion',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-09-11 01:46:57','2025-09-11 01:46:57'),(4,'Branding',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-09-11 01:46:57','2025-09-11 01:46:57'),(5,'Modern',1,'Botble\\ACL\\Models\\User',NULL,'published','2025-09-11 01:46:57','2025-09-11 01:46:57');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags_translations`
--

DROP TABLE IF EXISTS `tags_translations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `tags_translations` (
  `lang_code` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tags_id` bigint unsigned NOT NULL,
  `name` varchar(191) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` varchar(400) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`lang_code`,`tags_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags_translations`
--

LOCK TABLES `tags_translations` WRITE;
/*!40000 ALTER TABLE `tags_translations` DISABLE KEYS */;
/*!40000 ALTER TABLE `tags_translations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_meta`
--

DROP TABLE IF EXISTS `user_meta`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_meta` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `key` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `value` text COLLATE utf8mb4_unicode_ci,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `user_meta_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_meta`
--

LOCK TABLES `user_meta` WRITE;
/*!40000 ALTER TABLE `user_meta` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_meta` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user_settings`
--

DROP TABLE IF EXISTS `user_settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `user_settings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_type` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned NOT NULL,
  `key` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` json NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_settings_user_type_user_id_key_unique` (`user_type`,`user_id`,`key`),
  KEY `user_settings_user_type_user_id_index` (`user_type`,`user_id`),
  KEY `user_settings_key_index` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user_settings`
--

LOCK TABLES `user_settings` WRITE;
/*!40000 ALTER TABLE `user_settings` DISABLE KEYS */;
/*!40000 ALTER TABLE `user_settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(191) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `first_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_name` varchar(120) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `username` varchar(60) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avatar_id` bigint unsigned DEFAULT NULL,
  `super_user` tinyint(1) NOT NULL DEFAULT '0',
  `manage_supers` tinyint(1) NOT NULL DEFAULT '0',
  `permissions` text COLLATE utf8mb4_unicode_ci,
  `last_login` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'zemlak.franco@wisozk.com',NULL,NULL,'$2y$12$nn/f8ouBy98zkbKgkCoMvOiXDPPdIn105gIpWgF05vRLyUC2YRBWe',NULL,'2025-09-11 01:45:50','2025-09-11 01:45:50','Coralie','Stark','admin',NULL,1,1,NULL,NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `widgets`
--

DROP TABLE IF EXISTS `widgets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `widgets` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `widget_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sidebar_id` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `theme` varchar(120) COLLATE utf8mb4_unicode_ci NOT NULL,
  `position` tinyint unsigned NOT NULL DEFAULT '0',
  `data` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=20 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `widgets`
--

LOCK TABLES `widgets` WRITE;
/*!40000 ALTER TABLE `widgets` DISABLE KEYS */;
INSERT INTO `widgets` VALUES (1,'SiteInfoWidget','footer_sidebar','farmart',0,'{\"id\":\"SiteInfoWidget\",\"name\":\"Farmart \\u2013 Your Online Foods & Grocery\",\"about\":\"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed finibus viverra iaculis. Etiam vulputate et justo eget scelerisque.\",\"phone\":\"(+965) 7492-4277\",\"address\":\"959 Homestead Street Eastlake, NYC\",\"email\":\"support@farmart.com\",\"working_time\":\"Mon - Fri: 07AM - 06PM\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(2,'CustomMenuWidget','footer_sidebar','farmart',1,'{\"id\":\"CustomMenuWidget\",\"name\":\"Useful Links\",\"menu_id\":\"useful-links\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(3,'CustomMenuWidget','footer_sidebar','farmart',2,'{\"id\":\"CustomMenuWidget\",\"name\":\"Help Center\",\"menu_id\":\"help-center\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(4,'CustomMenuWidget','footer_sidebar','farmart',3,'{\"id\":\"CustomMenuWidget\",\"name\":\"Business\",\"menu_id\":\"business\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(5,'NewsletterWidget','footer_sidebar','farmart',4,'{\"id\":\"NewsletterWidget\",\"title\":\"Newsletter\",\"subtitle\":\"Register now to get updates on promotions and coupon. Don\\u2019t worry! We not spam\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(6,'BlogSearchWidget','primary_sidebar','farmart',1,'{\"id\":\"BlogSearchWidget\",\"name\":\"Search\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(7,'BlogCategoriesWidget','primary_sidebar','farmart',2,'{\"id\":\"BlogCategoriesWidget\",\"name\":\"Categories\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(8,'RecentPostsWidget','primary_sidebar','farmart',3,'{\"id\":\"RecentPostsWidget\",\"name\":\"Recent Posts\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(9,'BlogTagsWidget','primary_sidebar','farmart',4,'{\"id\":\"BlogTagsWidget\",\"name\":\"Popular Tags\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(10,'SiteFeaturesWidget','pre_footer_sidebar','farmart',1,'{\"id\":\"SiteFeaturesWidget\",\"title\":\"Site Features\",\"data\":{\"1\":{\"icon\":\"general\\/icon-rocket.png\",\"title\":\"Free Shipping\",\"subtitle\":\"For all orders over $200\"},\"2\":{\"icon\":\"general\\/icon-reload.png\",\"title\":\"1 & 1 Returns\",\"subtitle\":\"Cancellation after 1 day\"},\"3\":{\"icon\":\"general\\/icon-protect.png\",\"title\":\"100% Secure Payment\",\"subtitle\":\"Guarantee secure payments\"},\"4\":{\"icon\":\"general\\/icon-support.png\",\"title\":\"24\\/7 Dedicated Support\",\"subtitle\":\"Anywhere & anytime\"},\"5\":{\"icon\":\"general\\/icon-tag.png\",\"title\":\"Daily Offers\",\"subtitle\":\"Discount up to 70% OFF\"}}}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(11,'AdsWidget','products_list_sidebar','farmart',1,'{\"id\":\"AdsWidget\",\"title\":\"Ads\",\"ads_key\":\"ZDOZUZZIU7FZ\",\"background\":\"general\\/background.jpg\",\"size\":\"full-width\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(12,'SiteFeaturesWidget','product_detail_sidebar','farmart',1,'{\"id\":\"SiteFeaturesWidget\",\"title\":\"Site Features\",\"data\":{\"1\":{\"icon\":\"general\\/icon-rocket.png\",\"title\":\"Free Shipping\",\"subtitle\":\"For all orders over $200\"},\"2\":{\"icon\":\"general\\/icon-reload.png\",\"title\":\"1 & 1 Returns\",\"subtitle\":\"Cancellation after 1 day\"},\"3\":{\"icon\":\"general\\/icon-protect.png\",\"title\":\"Secure Payment\",\"subtitle\":\"Guarantee secure payments\"}}}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(13,'SiteInfoWidget','product_detail_sidebar','farmart',2,'{\"id\":\"SiteInfoWidget\",\"name\":\"Store information\",\"phone\":\"(+965) 7492-4277\",\"working_time\":\"Mon - Fri: 07AM - 06PM\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(14,'BecomeVendorWidget','product_detail_sidebar','farmart',3,'{\"id\":\"BecomeVendorWidget\",\"name\":\"Become a Vendor?\"}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(15,'ProductCategoriesWidget','bottom_footer_sidebar','farmart',1,'{\"id\":\"ProductCategoriesWidget\",\"name\":\"Consumer Electric\",\"categories\":[18,2,3,4,5,6,7]}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(16,'ProductCategoriesWidget','bottom_footer_sidebar','farmart',2,'{\"id\":\"ProductCategoriesWidget\",\"name\":\"Clothing & Apparel\",\"categories\":[8,9,10,11,12]}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(17,'ProductCategoriesWidget','bottom_footer_sidebar','farmart',3,'{\"id\":\"ProductCategoriesWidget\",\"name\":\"Home, Garden & Kitchen\",\"categories\":[13,14,15,16,17]}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(18,'ProductCategoriesWidget','bottom_footer_sidebar','farmart',4,'{\"id\":\"ProductCategoriesWidget\",\"name\":\"Health & Beauty\",\"categories\":[20,21,22,23,24]}','2025-09-11 01:47:12','2025-09-11 01:47:12'),(19,'ProductCategoriesWidget','bottom_footer_sidebar','farmart',5,'{\"id\":\"ProductCategoriesWidget\",\"name\":\"Computer & Technologies\",\"categories\":[25,26,27,28,29,19]}','2025-09-11 01:47:12','2025-09-11 01:47:12');
/*!40000 ALTER TABLE `widgets` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-09-11 15:47:19
