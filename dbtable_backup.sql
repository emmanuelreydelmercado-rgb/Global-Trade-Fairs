-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: dbtable
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4','i:1;',1766059916),('ac3478d69a3c81fa62e60f5c3696165a4e5e6ac4:timer','i:1766059916;',1766059916),('emmanuelsvks2004@gmail.com|127.0.0.1','i:2;',1766058189),('emmanuelsvks2004@gmail.com|127.0.0.1:timer','i:1766058189;',1766058189),('emmanuelvks2004@gmail.com|127.0.0.1','i:1;',1765974574),('emmanuelvks2004@gmail.com|127.0.0.1:timer','i:1765974574;',1765974574),('muneesmmw@gmail.com|127.0.0.1','i:1;',1766058154),('muneesmmw@gmail.com|127.0.0.1:timer','i:1766058154;',1766058154),('perf-0','s:7:\"value-0\";',1766750676),('perf-1','s:7:\"value-1\";',1766750676),('perf-10','s:8:\"value-10\";',1766750676),('perf-11','s:8:\"value-11\";',1766750676),('perf-12','s:8:\"value-12\";',1766750676),('perf-13','s:8:\"value-13\";',1766750676),('perf-14','s:8:\"value-14\";',1766750676),('perf-15','s:8:\"value-15\";',1766750676),('perf-16','s:8:\"value-16\";',1766750676),('perf-17','s:8:\"value-17\";',1766750676),('perf-18','s:8:\"value-18\";',1766750676),('perf-19','s:8:\"value-19\";',1766750676),('perf-2','s:7:\"value-2\";',1766750676),('perf-20','s:8:\"value-20\";',1766750676),('perf-21','s:8:\"value-21\";',1766750676),('perf-22','s:8:\"value-22\";',1766750676),('perf-23','s:8:\"value-23\";',1766750676),('perf-24','s:8:\"value-24\";',1766750676),('perf-25','s:8:\"value-25\";',1766750676),('perf-26','s:8:\"value-26\";',1766750676),('perf-27','s:8:\"value-27\";',1766750676),('perf-28','s:8:\"value-28\";',1766750676),('perf-29','s:8:\"value-29\";',1766750676),('perf-3','s:7:\"value-3\";',1766750676),('perf-30','s:8:\"value-30\";',1766750676),('perf-31','s:8:\"value-31\";',1766750676),('perf-32','s:8:\"value-32\";',1766750676),('perf-33','s:8:\"value-33\";',1766750676),('perf-34','s:8:\"value-34\";',1766750676),('perf-35','s:8:\"value-35\";',1766750676),('perf-36','s:8:\"value-36\";',1766750676),('perf-37','s:8:\"value-37\";',1766750676),('perf-38','s:8:\"value-38\";',1766750676),('perf-39','s:8:\"value-39\";',1766750676),('perf-4','s:7:\"value-4\";',1766750676),('perf-40','s:8:\"value-40\";',1766750676),('perf-41','s:8:\"value-41\";',1766750676),('perf-42','s:8:\"value-42\";',1766750676),('perf-43','s:8:\"value-43\";',1766750676),('perf-44','s:8:\"value-44\";',1766750676),('perf-45','s:8:\"value-45\";',1766750676),('perf-46','s:8:\"value-46\";',1766750676),('perf-47','s:8:\"value-47\";',1766750676),('perf-48','s:8:\"value-48\";',1766750676),('perf-49','s:8:\"value-49\";',1766750676),('perf-5','s:7:\"value-5\";',1766750676),('perf-50','s:8:\"value-50\";',1766750676),('perf-51','s:8:\"value-51\";',1766750676),('perf-52','s:8:\"value-52\";',1766750676),('perf-53','s:8:\"value-53\";',1766750676),('perf-54','s:8:\"value-54\";',1766750676),('perf-55','s:8:\"value-55\";',1766750676),('perf-56','s:8:\"value-56\";',1766750676),('perf-57','s:8:\"value-57\";',1766750676),('perf-58','s:8:\"value-58\";',1766750676),('perf-59','s:8:\"value-59\";',1766750676),('perf-6','s:7:\"value-6\";',1766750676),('perf-60','s:8:\"value-60\";',1766750676),('perf-61','s:8:\"value-61\";',1766750676),('perf-62','s:8:\"value-62\";',1766750676),('perf-63','s:8:\"value-63\";',1766750676),('perf-64','s:8:\"value-64\";',1766750676),('perf-65','s:8:\"value-65\";',1766750676),('perf-66','s:8:\"value-66\";',1766750676),('perf-67','s:8:\"value-67\";',1766750676),('perf-68','s:8:\"value-68\";',1766750676),('perf-69','s:8:\"value-69\";',1766750676),('perf-7','s:7:\"value-7\";',1766750676),('perf-70','s:8:\"value-70\";',1766750676),('perf-71','s:8:\"value-71\";',1766750676),('perf-72','s:8:\"value-72\";',1766750676),('perf-73','s:8:\"value-73\";',1766750676),('perf-74','s:8:\"value-74\";',1766750676),('perf-75','s:8:\"value-75\";',1766750676),('perf-76','s:8:\"value-76\";',1766750676),('perf-77','s:8:\"value-77\";',1766750676),('perf-78','s:8:\"value-78\";',1766750676),('perf-79','s:8:\"value-79\";',1766750676),('perf-8','s:7:\"value-8\";',1766750676),('perf-80','s:8:\"value-80\";',1766750676),('perf-81','s:8:\"value-81\";',1766750676),('perf-82','s:8:\"value-82\";',1766750676),('perf-83','s:8:\"value-83\";',1766750676),('perf-84','s:8:\"value-84\";',1766750676),('perf-85','s:8:\"value-85\";',1766750676),('perf-86','s:8:\"value-86\";',1766750676),('perf-87','s:8:\"value-87\";',1766750676),('perf-88','s:8:\"value-88\";',1766750676),('perf-89','s:8:\"value-89\";',1766750676),('perf-9','s:7:\"value-9\";',1766750676),('perf-90','s:8:\"value-90\";',1766750676),('perf-91','s:8:\"value-91\";',1766750676),('perf-92','s:8:\"value-92\";',1766750676),('perf-93','s:8:\"value-93\";',1766750676),('perf-94','s:8:\"value-94\";',1766750676),('perf-95','s:8:\"value-95\";',1766750676),('perf-96','s:8:\"value-96\";',1766750676),('perf-97','s:8:\"value-97\";',1766750676),('perf-98','s:8:\"value-98\";',1766750676),('perf-99','s:8:\"value-99\";',1766750676),('reydel.online@gmail.com|127.0.0.1','i:1;',1766058284),('reydel.online@gmail.com|127.0.0.1:timer','i:1766058284;',1766058284),('test-key','s:22:\"Redis is working! 🚀\";',1766750675);
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
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
-- Table structure for table `dbtable`
--

DROP TABLE IF EXISTS `dbtable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `dbtable` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `Orgname` varchar(255) NOT NULL,
  `VenueName` varchar(255) NOT NULL,
  `Date` date NOT NULL,
  `ExponName` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dbtable`
--

LOCK TABLES `dbtable` WRITE;
/*!40000 ALTER TABLE `dbtable` DISABLE KEYS */;
/*!40000 ALTER TABLE `dbtable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
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
-- Table structure for table `forms`
--

DROP TABLE IF EXISTS `forms`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `forms` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `Orgname` varchar(255) NOT NULL,
  `VenueName` varchar(255) NOT NULL,
  `city` varchar(255) DEFAULT NULL,
  `country` varchar(255) DEFAULT NULL,
  `Date` date NOT NULL,
  `ExponName` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `hallno` varchar(255) DEFAULT NULL,
  `reglink` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `forms`
--

LOCK TABLES `forms` WRITE;
/*!40000 ALTER TABLE `forms` DISABLE KEYS */;
INSERT INTO `forms` VALUES (1,'approved','qwe','qwe',NULL,NULL,'2025-12-10','qwe',NULL,NULL,NULL,NULL,NULL),(2,'approved','REED X','BHARAT MANDAPAM',NULL,NULL,'2025-12-17','GIFT SHOW',NULL,NULL,NULL,NULL,NULL),(3,'approved','HBO','Pragati Maidan',NULL,NULL,'2025-12-17','FILM EXPO',NULL,NULL,NULL,NULL,NULL),(4,'approved','uahs','asdasd',NULL,NULL,'2025-12-16','asdasd',NULL,NULL,NULL,NULL,NULL),(5,'approved','et','wer',NULL,NULL,'2025-12-01','wer',NULL,NULL,NULL,NULL,NULL),(6,'approved','Emmanuel Tech','Klonge Germany',NULL,NULL,'2025-12-22','Tech Expo 2026',NULL,NULL,NULL,NULL,NULL),(7,'approved','Reed Exhibition','BHARAT MANDAPAM',NULL,NULL,'2025-12-15','Auto Expo 2026',NULL,NULL,NULL,NULL,NULL),(8,'approved','Messe Frankfurt','ICCC',NULL,NULL,'2025-12-25','Christmas Expo 2025',NULL,NULL,NULL,NULL,NULL),(9,'approved','Messe Muenchen','Marina Trench',NULL,NULL,'2025-12-31','End Year Expo',NULL,NULL,NULL,NULL,NULL),(10,'approved','qwe','qwe',NULL,NULL,'2025-12-10','qw',NULL,NULL,NULL,NULL,NULL),(11,'approved','sdfd','sdf',NULL,NULL,'2025-12-10','sdf',NULL,NULL,NULL,NULL,NULL),(12,'approved','no show dir','heaven',NULL,NULL,'2026-01-02','god visit',NULL,NULL,NULL,NULL,NULL),(13,'approved','asfa','sda',NULL,NULL,'2025-12-20','asd',NULL,NULL,NULL,NULL,NULL),(14,'rejected','Munees','sfsfsd',NULL,NULL,'2025-12-07','sdfd',NULL,NULL,NULL,NULL,NULL),(15,'approved','qwewq','qweqw',NULL,NULL,'2025-12-10','qww','1765372337.jpg',NULL,NULL,NULL,NULL),(16,'approved','qweqw','qwewe',NULL,NULL,'2025-12-02','ffwwe','1765452403.jpg',NULL,NULL,NULL,NULL),(17,'approved','Messe Frankfurt(New Delhi)','BHARAT MANDAPAM',NULL,NULL,'2025-12-24','Christmas Expo 2025','1765457933.png',NULL,NULL,NULL,NULL),(18,'approved','234','234',NULL,NULL,'2025-12-08','234','1765535954.png',NULL,NULL,NULL,NULL),(19,'approved','Messe Frankfurt','Pragati Maidan',NULL,NULL,'2025-12-25','Christmas Gifts shop Expo 2025','1765540862.jpg','9011881134','manuel@gmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(20,'approved','India Org Association','ICCC Hyderabad',NULL,NULL,'2025-12-17','Book Fair Hyderabad 2025','1765543096.gif','8825608159','ok2@gmail.com','1',NULL),(21,'rejected','Messe Frankfurt','IICC',NULL,NULL,'2025-12-19','END YEAR EXPO 2025','1765968617.gif','8890929743','emmanuelreydelmercado@gmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(22,'approved','Messe Germany Ltd','Nurburngring','Guangzhou',NULL,'2025-12-17','March Against Them','1765969642.gif','9011881134','cowitive@kanonmail.com','5','https://www.holidayexpo.in/registrations/registration.html'),(23,'approved','Qatar Traders','Qatar Grand Prix',NULL,NULL,'2025-12-27','F1 2025 Party','1765970162.jpg','8890929743','cowitive@kanonmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(24,'approved','ORACLE','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-27','Tech Expo 2025','1765978073.gif','8825608158','makelave@auth2fa.com','4','https://www.holidayexpo.in/registrations/registration.html'),(25,'approved','Digital Network Ltd.','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-20','India Fence Expo','1766207072.gif','8923105382','cowitive@kanonmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(26,'rejected','Digital Network Ltd.','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-20','India Fence Expo','1766207075.gif','8923105382','cowitive@kanonmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(27,'approved','Wx Ltd.','Marina Beach Stadium','Chennai',NULL,'2025-12-31','Cosmoprof','1766210068.gif','9299324563','superadmin@gmail.com','2','https://www.holidayexpo.in/registrations/registration.html'),(28,'approved','Messe Frankfurt','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-24','Christmas Expo 2025','1766210169.gif','8964378905','superadmin@gmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(29,'approved','Messe Frankfurt','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-30','Industry Machine Expo','1766210383.gif','8934684215','superadmin@gmail.com','4','https://www.holidayexpo.in/registrations/registration.html'),(30,'approved','India steel Organizers Co.Ltd','ICCC Hyderabad','Hyderabad',NULL,'2025-12-27','Steel Manufactures Expo','1766211891.gif','9063853578','cowitive@kanonmail.com','7','https://www.holidayexpo.in/registrations/registration.html'),(31,'approved','All India Cashew Association','BHARAT MANDAPAM','New Delhi',NULL,'2025-12-31','Cashew Expo','1766228527.gif','9789234591','superadmin@gmail.com','1','https://www.holidayexpo.in/registrations/registration.html'),(32,'rejected','Test Organizer','Test Venue','Test City',NULL,'2025-12-31','Test Event',NULL,'1234567890','test@example.com','Hall 1',NULL),(33,'rejected','Test Organizer','Test Venue','Test City',NULL,'2025-12-31','Test Event',NULL,'1234567890','test@example.com','Hall 1',NULL),(34,'rejected','Test Organizer','Test Venue','Test City',NULL,'2025-12-31','Test Event','1766228801.txt','1234567890','test@example.com','Hall 1',NULL),(35,'pending','Messe Frankfurt','Ja Bose','Sivakasi',NULL,'2025-12-26','BMW show Expo','1766229140.gif','9437528547','makelave@auth2fa.com','5','https://www.holidayexpo.in/registrations/registration.html'),(36,'pending','SI Exhibitions','Marina Beach','Chennai',NULL,'2025-12-27','Industry growth Expo 2025','1766231113.gif','9178369234','cowitive@kanonmail.com','1',NULL),(37,'approved','sdf','NYC Convention Center','New York','United States','2025-10-10','Global Tech Fair 2025',NULL,'1234567890','test@example.com',NULL,NULL),(38,'approved','Orange Technologies Ltd','Pragati Maidan','New Delhi','India','2025-12-27','Hardware Generations Expo 2025','1766492546.gif','9086298361','manuel@gmail.com','2','https://www.holidayexpo.in/registrations/registration.html');
/*!40000 ALTER TABLE `forms` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2025_12_05_104938_create_dbtable_table',1),(5,'2025_12_09_111808_create_forms_table',2),(6,'2025_12_10_121004_add_status_to_forms',3),(7,'2025_12_10_125135_add_image_to_forms',4),(8,'2025_12_12_113902_add_more_fields_to_forms_table',5),(9,'2025_12_15_105225_create_visitors_table',6),(10,'2025_12_16_115841_add_profilepic_and_role_to_users_table',7),(11,'2025_12_17_125429_add_city_to_forms_table',8),(12,'2025_12_23_114406_add_country_to_forms_table',9);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
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
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
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
INSERT INTO `sessions` VALUES ('IA7XaynZfLaJJqLrteRmM1sDo9GBdt5zwIRdGxgc',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo1OntzOjY6Il90b2tlbiI7czo0MDoiR1Q2NzFkaVlUTVhXN1M2SlkzWUxUMU5JSzRVZVk5OVB6MEsybmlBRyI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjI6e3M6MzoidXJsIjtzOjIxOiJodHRwOi8vMTI3LjAuMC4xOjgwMDAiO3M6NToicm91dGUiO3M6NDoiaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjI7fQ==',1766491407),('RcNfclbYGFez4xRLYweO6qRNy0NBToe66kWp5zfR',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoidThOcDNzbVpheFFCQVZ5bXYzR1ZKSWNZMWhaMTdxenFNdnhsNm9sMiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6MzoidXJsIjthOjE6e3M6ODoiaW50ZW5kZWQiO3M6Mzc6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO319',1766493087),('Swtq1s9B4Ykequ98YtiADlM5CD40M4VfaA6xMBu5',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjZnS3RFc1RZdWxid1JNQVJPMXgyeGs1amJ4dUZ4cXk5dkNYaG9ITyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC8/cGFnZT0xIjtzOjU6InJvdXRlIjtzOjQ6ImhvbWUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1766569907),('vYs92U1bDnGREwUdEeGWxHjHltNeCmjHfYT9x9Fh',2,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiaG5PYlcybmxKVXhpRkpjY2hBOXUycXJnNFhncUFMZFh0U1lWajNIMyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6Mjt9',1766495733),('ZPjVnrSEHqWlkYuOa6Mo6do1L06g85MSEJN9fFKf',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/143.0.0.0 Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoibkNBMlh3b28wcjhuTnhxZzZPS29iQjNCbGFkOWlwNXh5cU8wM2J5WCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1766750873);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `profilepic` varchar(255) DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'manuel','manuel@gmail.com','2025-01-01 04:30:00','$2y$12$Jylg9r8tZAucNyZp.GDASeLiL87t1B9HW9VEYHkFm8o03BvMBeMtK',NULL,'user',NULL,'2025-12-10 06:23:02','2025-12-10 06:25:01'),(2,'Emmanuel','emmanuelreydelmercado@gmail.com','2025-01-01 04:30:00','$2y$12$vj/o6fVA.P3rA5jYdUQOEuIt2ptDf48WaBgIzyf9RnLFhbSGADM72','1766487154_images.png','admin','RdZ1lJkLE4P4ogEEjDeX3eb3pS7W4qqxr2DSeDwfatmuH3NIWENrHftGr57B','2025-12-15 05:45:32','2025-12-23 05:22:34'),(3,'Jeyaseelan Emmanuel','emmanuelsvks2004@gmail.com',NULL,'$2y$12$6806flp6sDl1mjP7niR63eRrDlTU8AGdZLtCOJQkBT1RjZlac8CS2','1765974694_red1.jpg','user',NULL,'2025-12-17 07:01:35','2025-12-17 07:01:35'),(4,'Muneeswaran','muneesmmw@gmail.com',NULL,'$2y$12$rmlxHNV7AFqUiswLOAbUsOTOtgFI7EBqHrjDohG2pvIOo07R.RMi.','default.jpg','user',NULL,'2025-12-18 06:14:41','2025-12-18 06:14:41'),(5,'Jeyaseelan Emmanuel','22ul28@anjaconline.org',NULL,'$2y$12$ArAvrqayqQvoEyhErtyapuJ9jK92ePuzdz424T0C74ZQFynRYRqPe','1766059249_images.png','user',NULL,'2025-12-18 06:30:50','2025-12-18 06:30:50'),(6,'Jazz','emmjazz18@gmail.com',NULL,'$2y$12$Q6nXFTImLKv8a.LkUs7NnuKvHT2PrRIzmWlzNG4k598.oE0Rnu2by','1766060171_images.png','user',NULL,'2025-12-18 06:46:12','2025-12-18 06:46:12');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-12-29 16:58:17
