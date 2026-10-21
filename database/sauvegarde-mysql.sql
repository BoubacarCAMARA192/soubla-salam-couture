-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: soubla_salam_couture
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
-- Current Database: `soubla_salam_couture`
--

/*!40000 DROP DATABASE IF EXISTS `soubla_salam_couture`*/;

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `soubla_salam_couture` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci */;

USE `soubla_salam_couture`;

--
-- Table structure for table `activity_logs`
--

DROP TABLE IF EXISTS `activity_logs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `activity_logs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `action` varchar(50) NOT NULL,
  `entity_type` varchar(40) DEFAULT NULL,
  `entity_id` bigint(20) unsigned DEFAULT NULL,
  `details` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `activity_logs_user_id_foreign` (`user_id`),
  KEY `activity_logs_entity_type_entity_id_index` (`entity_type`,`entity_id`),
  CONSTRAINT `activity_logs_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=58 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `activity_logs`
--

LOCK TABLES `activity_logs` WRITE;
/*!40000 ALTER TABLE `activity_logs` DISABLE KEYS */;
INSERT INTO `activity_logs` VALUES (1,NULL,'system.seed',NULL,NULL,'{\"jeu\":\"donn├®es de d├®monstration\"}',NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,2,'login','user',2,NULL,NULL,'2026-10-21 11:17:51','2026-10-21 11:17:51'),(3,2,'export.csv','fabric',NULL,'{\"alertes\":2}',NULL,'2026-10-21 11:17:54','2026-10-21 11:17:54'),(4,2,'login','user',2,NULL,NULL,'2026-10-21 11:18:13','2026-10-21 11:18:13'),(5,2,'login','user',2,NULL,NULL,'2026-10-21 11:19:33','2026-10-21 11:19:33'),(6,2,'export.csv','fabric',NULL,'{\"alertes\":2}',NULL,'2026-10-21 11:19:37','2026-10-21 11:19:37'),(7,1,'login','user',1,NULL,NULL,'2026-10-21 11:19:51','2026-10-21 11:19:51'),(8,1,'login','user',1,NULL,NULL,'2026-10-21 11:28:04','2026-10-21 11:28:04'),(9,1,'login','user',1,NULL,NULL,'2026-10-21 11:29:29','2026-10-21 11:29:29'),(10,4,'login','user',4,NULL,NULL,'2026-10-21 11:29:46','2026-10-21 11:29:46'),(11,1,'login','user',1,NULL,NULL,'2026-10-21 11:31:20','2026-10-21 11:31:20'),(12,2,'login','user',2,NULL,NULL,'2026-10-21 11:32:36','2026-10-21 11:32:36'),(13,2,'export.csv','fabric',NULL,'{\"alertes\":2}',NULL,'2026-10-21 11:32:46','2026-10-21 11:32:46'),(14,1,'login','user',1,NULL,NULL,'2026-10-21 11:32:59','2026-10-21 11:32:59'),(15,1,'login','user',1,NULL,NULL,'2026-10-21 11:33:35','2026-10-21 11:33:35'),(16,1,'login','user',1,NULL,NULL,'2026-10-21 11:34:26','2026-10-21 11:34:26'),(17,1,'export.csv',NULL,NULL,'{\"type\":\"orders\"}',NULL,'2026-10-21 11:34:26','2026-10-21 11:34:26'),(18,1,'export.csv',NULL,NULL,'{\"type\":\"payments\"}',NULL,'2026-10-21 11:34:26','2026-10-21 11:34:26'),(19,1,'export.csv',NULL,NULL,'{\"type\":\"models\"}',NULL,'2026-10-21 11:34:27','2026-10-21 11:34:27'),(20,1,'export.csv',NULL,NULL,'{\"type\":\"expenses\"}',NULL,'2026-10-21 11:34:27','2026-10-21 11:34:27'),(21,1,'export.csv',NULL,NULL,'{\"type\":\"activity\"}',NULL,'2026-10-21 11:34:27','2026-10-21 11:34:27'),(22,1,'export.csv',NULL,NULL,'{\"type\":\"clients\"}',NULL,'2026-10-21 11:34:27','2026-10-21 11:34:27'),(23,1,'export.csv',NULL,NULL,'{\"type\":\"sauvegarde-base\"}',NULL,'2026-10-21 11:34:28','2026-10-21 11:34:28'),(24,1,'login','user',1,NULL,NULL,'2026-10-21 11:35:22','2026-10-21 11:35:22'),(25,1,'export.csv',NULL,NULL,'{\"type\":\"sauvegarde-base\"}',NULL,'2026-10-21 11:35:22','2026-10-21 11:35:22'),(26,1,'login','user',1,NULL,NULL,'2026-10-21 11:35:51','2026-10-21 11:35:51'),(27,1,'export.csv',NULL,NULL,'{\"type\":\"sauvegarde\"}',NULL,'2026-10-21 11:35:52','2026-10-21 11:35:52'),(28,1,'login','user',1,NULL,NULL,'2026-10-21 11:36:03','2026-10-21 11:36:03'),(29,1,'export.csv',NULL,NULL,'{\"type\":\"sauvegarde\"}',NULL,'2026-10-21 11:36:03','2026-10-21 11:36:03'),(30,1,'login','user',1,NULL,NULL,'2026-10-21 11:36:15','2026-10-21 11:36:15'),(31,1,'export.csv',NULL,NULL,'{\"type\":\"sauvegarde\"}',NULL,'2026-10-21 11:36:15','2026-10-21 11:36:15'),(32,4,'login','user',4,NULL,NULL,'2026-10-21 11:37:12','2026-10-21 11:37:12'),(33,2,'login','user',2,NULL,NULL,'2026-10-21 11:40:55','2026-10-21 11:40:55'),(34,2,'login','user',2,NULL,NULL,'2026-10-21 11:41:10','2026-10-21 11:41:10'),(35,2,'login','user',2,NULL,NULL,'2026-10-21 11:41:26','2026-10-21 11:41:26'),(36,2,'login','user',2,NULL,NULL,'2026-10-21 11:41:51','2026-10-21 11:41:51'),(37,2,'login','user',2,NULL,NULL,'2026-10-21 11:42:13','2026-10-21 11:42:13'),(38,2,'login','user',2,NULL,NULL,'2026-10-21 11:43:29','2026-10-21 11:43:29'),(39,2,'login','user',2,NULL,NULL,'2026-10-21 11:44:04','2026-10-21 11:44:04'),(40,2,'order.update','order',8,NULL,NULL,'2026-10-21 11:44:34','2026-10-21 11:44:34'),(41,2,'order.status','order',8,'{\"reference\":\"SSC-26-LCS3R\",\"de\":\"requested\",\"vers\":\"validated\"}',NULL,'2026-10-21 11:44:51','2026-10-21 11:44:51'),(42,2,'payment.create','payment',5,'{\"reference\":\"REC-20261021-VZU\",\"montant\":\"10000\"}',NULL,'2026-10-21 11:46:42','2026-10-21 11:46:42'),(43,1,'login','user',1,NULL,NULL,'2026-10-21 11:47:40','2026-10-21 11:47:40'),(44,1,'order.status','order',4,'{\"reference\":\"SSC-26-R2SN3\",\"de\":\"requested\",\"vers\":\"cancelled\"}',NULL,'2026-10-21 11:47:42','2026-10-21 11:47:42'),(45,1,'login','user',1,NULL,NULL,'2026-10-21 11:48:12','2026-10-21 11:48:12'),(46,1,'model.publish','catalog_model',13,'{\"titre\":\"Robe veil├®e brod├®e ivoire\"}',NULL,'2026-10-21 11:48:39','2026-10-21 11:48:39'),(47,4,'login','user',4,NULL,NULL,'2026-10-21 11:50:37','2026-10-21 11:50:37'),(48,4,'login','user',4,NULL,NULL,'2026-10-21 11:51:04','2026-10-21 11:51:04'),(49,2,'login','user',2,NULL,NULL,'2026-10-21 11:51:05','2026-10-21 11:51:05'),(50,2,'message.send','order',1,NULL,NULL,'2026-10-21 11:51:10','2026-10-21 11:51:10'),(51,2,'fabric.movement','fabric',2,'{\"tissu\":\"Bazin getzner bordeaux\",\"mouvement\":\"├ëclaircie\"}',NULL,'2026-10-21 11:51:12','2026-10-21 11:51:12'),(52,2,'appointment.create','appointment',NULL,'{\"date\":\"2026-10-26T10:30\",\"type\":\"Essayage\"}',NULL,'2026-10-21 11:52:05','2026-10-21 11:52:05'),(53,2,'message.send','client',1,NULL,NULL,'2026-10-21 11:52:32','2026-10-21 11:52:32'),(54,NULL,'contact.form','client',5,'{\"nom\":\"Marc Diop\",\"tel\":\"221770101010\"}',NULL,'2026-10-21 11:55:01','2026-10-21 11:55:01'),(55,4,'login','user',4,NULL,NULL,'2026-10-21 11:57:06','2026-10-21 11:57:06'),(56,4,'login','user',4,NULL,NULL,'2026-10-21 11:58:16','2026-10-21 11:58:16'),(57,4,'login','user',4,NULL,NULL,'2026-10-21 11:59:03','2026-10-21 11:59:03');
/*!40000 ALTER TABLE `activity_logs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `appointments`
--

DROP TABLE IF EXISTS `appointments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `appointments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `tailleur_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(20) NOT NULL,
  `scheduled_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `duration_minutes` int(10) unsigned NOT NULL DEFAULT 30,
  `status` varchar(15) NOT NULL DEFAULT 'planned',
  `notes` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `appointments_client_id_foreign` (`client_id`),
  KEY `appointments_tailleur_id_foreign` (`tailleur_id`),
  KEY `appointments_order_id_foreign` (`order_id`),
  KEY `appointments_scheduled_at_index` (`scheduled_at`),
  CONSTRAINT `appointments_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `appointments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `appointments_tailleur_id_foreign` FOREIGN KEY (`tailleur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `appointments`
--

LOCK TABLES `appointments` WRITE;
/*!40000 ALTER TABLE `appointments` DISABLE KEYS */;
INSERT INTO `appointments` VALUES (1,2,3,2,'fitting','2026-10-24 16:00:00',30,'planned','Proposer le lien exhaustif de finitions.','2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,1,2,NULL,'measurements','2026-10-21 11:30:00',30,'planned','Nouvelle proposition corot : robe marron clair.','2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,1,2,NULL,'measurements','2026-10-01 10:45:00',30,'done',NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,3,2,NULL,'measurements','2026-10-26 09:30:00',30,'planned','V├®rifier l\'├®paule du mokar.','2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,2,2,NULL,'fitting','2026-10-26 10:30:00',30,'planned','Essayage robe mariage','2026-10-21 11:52:05','2026-10-21 11:52:05');
/*!40000 ALTER TABLE `appointments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
INSERT INTO `cache` VALUES ('soubla-salam-couture-cache-settings.all','a:19:{s:10:\"about_text\";s:329:\"Depuis 2018, l\'atelier Soubla Salam Couture accompagne les familles de Dakar pour toutes leurs tenues d\'├®v├®nement : Korit├®, Tabaski, mariages, bapt├¬mes. Chaque confection suit un circuit ma├«tris├® : mesures, coupe, couture, essayage, livraison ÔÇö avec des photos de progression prises ├á chaque ├®tape pour un suivi serein.\";s:15:\"atelier_address\";s:29:\"Sacr├®-C┼ôur 3 n┬░5415, Dakar\";s:13:\"atelier_email\";s:22:\"contact@soublasalam.sn\";s:13:\"atelier_hours\";s:47:\"Lun ÔÇö Sam : 9 h ÔÇö 19 h\nDimanche : sur devis\";s:15:\"atelier_map_url\";s:46:\"https://maps.google.com/?q=Sacre-Coeur-3-Dakar\";s:12:\"atelier_name\";s:20:\"Soubla Salam Couture\";s:13:\"atelier_phone\";s:17:\"+221 77 555 88 90\";s:14:\"atelier_slogan\";s:38:\"L\'├®l├®gance confectionn├®e ├á la main\";s:16:\"atelier_whatsapp\";s:13:\"+221775558890\";s:11:\"banner_text\";s:93:\"Commandes Korit├® : tout d├®p├┤t avant le 15 du mois ÔÇö dans la limite de la charge atelier.\";s:20:\"default_deposit_rate\";s:2:\"50\";s:12:\"facebook_url\";s:0:\"\";s:8:\"hero_cta\";s:23:\"D├®couvrir le catalogue\";s:13:\"hero_subtitle\";s:79:\"Catalogue, commandes & suivi confie mesures ÔÇö codza lis ├á Dakar depuis 2018.\";s:10:\"hero_title\";s:41:\"Vos tenues de f├¬te, taill├®es sur mesure\";s:13:\"instagram_url\";s:0:\"\";s:17:\"payment_om_number\";s:12:\"78 555 88 90\";s:19:\"payment_wave_number\";s:12:\"77 555 88 90\";s:14:\"receipt_footer\";s:114:\"Merci de votre confiance ÔÇö un acompte vers├® n\'est pas remboursable si la commande est annul├®e apr├¿s la coupe.\";}',1792584126);
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
  `expiration` bigint(20) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
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
-- Table structure for table `catalog_models`
--

DROP TABLE IF EXISTS `catalog_models`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `catalog_models` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(20) NOT NULL,
  `type` varchar(20) DEFAULT NULL,
  `occasion` varchar(30) DEFAULT NULL,
  `suggested_fabric` varchar(255) DEFAULT NULL,
  `suggested_colors` varchar(255) DEFAULT NULL,
  `indicative_price` decimal(10,2) DEFAULT NULL,
  `delay_days` tinyint(3) unsigned DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'draft',
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `views_count` int(10) unsigned NOT NULL DEFAULT 0,
  `orders_count` int(10) unsigned NOT NULL DEFAULT 0,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `catalog_models_slug_unique` (`slug`),
  KEY `catalog_models_created_by_foreign` (`created_by`),
  KEY `catalog_models_category_index` (`category`),
  KEY `catalog_models_status_index` (`status`),
  CONSTRAINT `catalog_models_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `catalog_models`
--

LOCK TABLES `catalog_models` WRITE;
/*!40000 ALTER TABLE `catalog_models` DISABLE KEYS */;
INSERT INTO `catalog_models` VALUES (1,'Robe bazin brod├®e or','robe-bazin-brodee-or','Tenue traditionnel pour Korit├®. Coupe ajust├®e, finitions main. Tissus conseill├®s : Bazin getzner bleu or. D├®lai indicatif sans p├®riode de f├¬te : 7 jours.','femme','traditionnel','Korit├®','Bazin getzner bleu or','bleu, or, ivoire',35000.00,7,'published',1,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,'Boubou homme premier roi','boubou-homme-premier-roi','Tenue traditionnel pour Tabaski. Coupe ajust├®e, finitions main. Tissus conseill├®s : Bazin riche rips. D├®lai indicatif sans p├®riode de f├¬te : 10 jours.','homme','traditionnel','Tabaski','Bazin riche rips','blanc, dor├®',50000.00,10,'published',1,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,'Ensemble wax c├®r├®monie','ensemble-wax-ceremonie','Tenue traditionnel pour mariage. Coupe ajust├®e, finitions main. Tissus conseill├®s : Wax afro. D├®lai indicatif sans p├®riode de f├¬te : 6 jours.','femme','traditionnel','mariage','Wax afro','rouge, multicolore',28000.00,6,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,'Robe soirette ville','robe-soirette-ville','Tenue moderne pour ville. Coupe ajust├®e, finitions main. Tissus conseill├®s : Rips satin. D├®lai indicatif sans p├®riode de f├¬te : 5 jours.','femme','moderne','ville','Rips satin','noir, bordeaux',22000.00,5,'published',1,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,'Suit 3 pi├¿ces mari├®','suit-3-pieces-marie','Tenue moderne pour mariage. Coupe ajust├®e, finitions main. Tissus conseill├®s : Laine m├®lang├®e. D├®lai indicatif sans p├®riode de f├¬te : 14 jours.','homme','moderne','mariage','Laine m├®lang├®e','gris perle, marine',75000.00,14,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(6,'Peau Toyota brod├®e','peau-toyota-brodee','Tenue traditionnel pour Tabaski (taille enfant au-del├á de 4 ans). Tissus conseill├®s : Percale brod├®e. D├®lai indicatif sans p├®riode de f├¬te : 5 jours.','enfant','traditionnel','Tabaski','Percale brod├®e','vert, blanc, or',15000.00,5,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(7,'Grand boubou bapt├¬me','grand-boubou-bapteme','Tenue traditionnel pour bapt├¬me (taille enfant au-del├á de 4 ans). Tissus conseill├®s : Voile bazin. D├®lai indicatif sans p├®riode de f├¬te : 7 jours.','enfant','traditionnel','bapt├¬me','Voile bazin','blanc',18000.00,7,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(8,'Robe espa├▒ola moderne','robe-espanola-moderne','Tenue moderne pour bapt├¬me. Coupe ajust├®e, finitions main. Tissus conseill├®s : Dentelle. D├®lai indicatif sans p├®riode de f├¬te : 5 jours.','femme','moderne','bapt├¬me','Dentelle','poudr├®, saumon',20000.00,5,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(9,'Djellaba homme zat rouge','djellaba-homme-zat-rouge','Tenue traditionnel pour Korit├®. Coupe ajust├®e, finitions main. Tissus conseill├®s : Bazin getzner. D├®lai indicatif sans p├®riode de f├¬te : 8 jours.','homme','traditionnel','Korit├®','Bazin getzner','rouge d├®mon',30000.00,8,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(10,'Jupe + d├®bardeur duo','jupe-debardeur-duo','Tenue moderne pour ville. Coupe ajust├®e, finitions main. Tissus conseill├®s : Kente coton. D├®lai indicatif sans p├®riode de f├¬te : 4 jours.','femme','moderne','ville','Kente coton','jaune Emperor',16000.00,4,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(11,'Tailleur fusion mariage','tailleur-fusion-mariage','Tenue moderne pour mariage. Coupe ajust├®e, finitions main. Tissus conseill├®s : Organza brod├®. D├®lai indicatif sans p├®riode de f├¬te : 12 jours.','femme','moderne','mariage','Organza brod├®','champagne',65000.00,12,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(12,'Grand mbubb, 3 waars','grand-mbubb-3-waars','Tenue traditionnel pour Tabaski. Coupe ajust├®e, finitions main. Tissus conseill├®s : Bazin getzner bordeaux. D├®lai indicatif sans p├®riode de f├¬te : 11 jours.','femme','traditionnel','Tabaski','Bazin getzner bordeaux','bordeaux, vert',60000.00,11,'published',0,0,0,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(13,'Robe veil├®e brod├®e ivoire','robe-veilee-brodee-ivoire','Proposition : robe bazin ivoire avec broderie charg├®e pour soir├®e mariage.','femme','traditionnel','mariage','Voile bazin',NULL,42000.00,NULL,'published',0,0,0,3,'2026-10-21 11:16:52','2026-10-21 11:48:39');
/*!40000 ALTER TABLE `catalog_models` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clients`
--

DROP TABLE IF EXISTS `clients`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `first_visit_at` date DEFAULT NULL,
  `last_visit_at` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `clients_user_id_unique` (`user_id`),
  CONSTRAINT `clients_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clients`
--

LOCK TABLES `clients` WRITE;
/*!40000 ALTER TABLE `clients` DISABLE KEYS */;
INSERT INTO `clients` VALUES (1,4,'Fatou Ba','770000004','Fass Delorme, rue 45','Pr├®f├¿re les finitions main, coupe ajust├®e.','2026-08-21','2026-10-18','2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,5,'Bineta Fall','770000005','Sicap Baobab 2','Pr├®f├¿re les finitions main, coupe ajust├®e.','2026-08-21','2026-10-18','2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,NULL,'Astou Gueye','770000006','Libert├® 6 ext',NULL,'2026-10-01',NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,NULL,'Awa Camara','770000007','Grand Yoff',NULL,'2026-10-01',NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,NULL,'Marc Diop','221770101010',NULL,'Email : marc.diop@example.com ÔÇö Source : formulaire de contact','2026-10-21','2026-10-21','2026-10-21 11:55:01','2026-10-21 11:55:01'),(6,6,'Ndiaye Ousmane','221770909091',NULL,NULL,'2026-10-21',NULL,'2026-10-21 11:55:44','2026-10-21 11:55:44');
/*!40000 ALTER TABLE `clients` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `clothing_types`
--

DROP TABLE IF EXISTS `clothing_types`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `clothing_types` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `measurement_fields` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`measurement_fields`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `clothing_types`
--

LOCK TABLES `clothing_types` WRITE;
/*!40000 ALTER TABLE `clothing_types` DISABLE KEYS */;
INSERT INTO `clothing_types` VALUES (1,'boubou homme',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,'boubou femme',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,'robe',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,'ensemble',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,'jupe',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(6,'chemise',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(7,'tissu',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52');
/*!40000 ALTER TABLE `clothing_types` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `expenses`
--

DROP TABLE IF EXISTS `expenses`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `expenses` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `label` varchar(255) NOT NULL,
  `category` varchar(50) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `spent_at` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `expenses_created_by_foreign` (`created_by`),
  CONSTRAINT `expenses_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `expenses`
--

LOCK TABLES `expenses` WRITE;
/*!40000 ALTER TABLE `expenses` DISABLE KEYS */;
INSERT INTO `expenses` VALUES (1,'Rouleaux de fil (12 tons)','fournitures',14500.00,'2026-10-18',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,'Machines : entretien annuel','divers',25000.00,'2026-10-19',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,'Transport livraisons Fass','transport',4500.00,'2026-10-20',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,'Publicit├® page Facebook','publicit├®',10000.00,'2026-10-11',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,'Kontour ciseaux pro','fournitures',12000.00,'2026-10-20',NULL,1,'2026-10-21 11:16:52','2026-10-21 11:16:52');
/*!40000 ALTER TABLE `expenses` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fabric_movements`
--

DROP TABLE IF EXISTS `fabric_movements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fabric_movements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `fabric_id` bigint(20) unsigned NOT NULL,
  `type` varchar(10) NOT NULL,
  `quantity` decimal(10,2) NOT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fabric_movements_fabric_id_foreign` (`fabric_id`),
  KEY `fabric_movements_order_id_foreign` (`order_id`),
  KEY `fabric_movements_user_id_foreign` (`user_id`),
  CONSTRAINT `fabric_movements_fabric_id_foreign` FOREIGN KEY (`fabric_id`) REFERENCES `fabrics` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fabric_movements_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fabric_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fabric_movements`
--

LOCK TABLES `fabric_movements` WRITE;
/*!40000 ALTER TABLE `fabric_movements` DISABLE KEYS */;
INSERT INTO `fabric_movements` VALUES (1,2,'in',6.00,NULL,2,'Entr├®e de stock','2026-10-21 11:51:12','2026-10-21 11:51:12');
/*!40000 ALTER TABLE `fabric_movements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `fabrics`
--

DROP TABLE IF EXISTS `fabrics`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `fabrics` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `quantity` decimal(10,2) NOT NULL DEFAULT 0.00,
  `unit` varchar(10) NOT NULL DEFAULT 'm',
  `alert_threshold` decimal(10,2) NOT NULL DEFAULT 5.00,
  `photo` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `fabrics`
--

LOCK TABLES `fabrics` WRITE;
/*!40000 ALTER TABLE `fabrics` DISABLE KEYS */;
INSERT INTO `fabrics` VALUES (1,'Bazin getzner bleu','bleu marine',12.00,'m',5.00,NULL,'Getzner original','2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,'Bazin getzner bordeaux','bordeaux',10.00,'m',5.00,NULL,'quasi ├®puis├®','2026-10-21 11:16:52','2026-10-21 11:51:12'),(3,'Wax afro dor├®','or / brun',9.00,'m',4.00,NULL,NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,'Rips satin ivoire','ivoire',3.00,'m',4.00,NULL,NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,'Percale brod├®e verte','vert fonc├®',7.00,'m',4.00,NULL,NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52');
/*!40000 ALTER TABLE `fabrics` ENABLE KEYS */;
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
  `connection` varchar(255) NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`),
  KEY `failed_jobs_connection_queue_failed_at_index` (`connection`,`queue`,`failed_at`)
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
-- Table structure for table `favorites`
--

DROP TABLE IF EXISTS `favorites`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `favorites` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned NOT NULL,
  `catalog_model_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `favorites_user_id_catalog_model_id_unique` (`user_id`,`catalog_model_id`),
  KEY `favorites_catalog_model_id_foreign` (`catalog_model_id`),
  CONSTRAINT `favorites_catalog_model_id_foreign` FOREIGN KEY (`catalog_model_id`) REFERENCES `catalog_models` (`id`) ON DELETE CASCADE,
  CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `favorites`
--

LOCK TABLES `favorites` WRITE;
/*!40000 ALTER TABLE `favorites` DISABLE KEYS */;
INSERT INTO `favorites` VALUES (1,4,2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,4,4,'2026-10-21 11:16:52','2026-10-21 11:16:52');
/*!40000 ALTER TABLE `favorites` ENABLE KEYS */;
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
  `attempts` smallint(5) unsigned NOT NULL,
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
-- Table structure for table `measurements`
--

DROP TABLE IF EXISTS `measurements`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `measurements` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` bigint(20) unsigned NOT NULL,
  `clothing_type_id` bigint(20) unsigned DEFAULT NULL,
  `clothing_type` varchar(255) NOT NULL,
  `chest` decimal(6,1) DEFAULT NULL,
  `waist` decimal(6,1) DEFAULT NULL,
  `hips` decimal(6,1) DEFAULT NULL,
  `shoulders` decimal(6,1) DEFAULT NULL,
  `sleeve_length` decimal(6,1) DEFAULT NULL,
  `top_length` decimal(6,1) DEFAULT NULL,
  `pants_length` decimal(6,1) DEFAULT NULL,
  `thigh` decimal(6,1) DEFAULT NULL,
  `extras` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`extras`)),
  `notes` text DEFAULT NULL,
  `taken_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `measurements_client_id_foreign` (`client_id`),
  KEY `measurements_clothing_type_id_foreign` (`clothing_type_id`),
  KEY `measurements_taken_by_foreign` (`taken_by`),
  CONSTRAINT `measurements_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `measurements_clothing_type_id_foreign` FOREIGN KEY (`clothing_type_id`) REFERENCES `clothing_types` (`id`) ON DELETE SET NULL,
  CONSTRAINT `measurements_taken_by_foreign` FOREIGN KEY (`taken_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `measurements`
--

LOCK TABLES `measurements` WRITE;
/*!40000 ALTER TABLE `measurements` DISABLE KEYS */;
INSERT INTO `measurements` VALUES (1,1,NULL,'robe',92.0,74.0,98.0,38.0,56.0,108.0,NULL,NULL,NULL,NULL,2,'2026-10-09 11:16:52','2026-10-09 11:16:52'),(2,1,NULL,'boubou femme',94.0,75.0,99.0,39.0,58.0,128.0,NULL,NULL,NULL,NULL,2,'2026-10-09 11:16:52','2026-10-09 11:16:52'),(3,1,NULL,'jupe',NULL,73.0,98.0,NULL,NULL,58.0,NULL,57.0,NULL,'Longueur du bas au ┬¥ des mollets',2,'2026-10-09 11:16:52','2026-10-09 11:16:52');
/*!40000 ALTER TABLE `measurements` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `messages`
--

DROP TABLE IF EXISTS `messages`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `messages` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `sender_id` bigint(20) unsigned NOT NULL,
  `body` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `messages_order_id_foreign` (`order_id`),
  KEY `messages_sender_id_foreign` (`sender_id`),
  KEY `messages_client_id_order_id_index` (`client_id`,`order_id`),
  CONSTRAINT `messages_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `messages`
--

LOCK TABLES `messages` WRITE;
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` VALUES (1,1,1,4,'Tenue parfaite, merci beaucoup!','2026-10-21 11:51:10','2026-10-21 11:51:06','2026-10-21 11:51:10'),(2,1,1,2,'Merci ! A bientot','2026-10-21 11:51:10','2026-10-21 11:51:10','2026-10-21 11:51:10'),(3,NULL,1,2,'Bien vouloir venir avec le bazin samedi SVP','2026-10-21 11:52:32','2026-10-21 11:52:32','2026-10-21 11:52:32'),(4,NULL,5,5,'[Tunique homme] \nBonjour, pouvez-vous confectionner une tunique homme pour novembre?',NULL,'2026-10-21 11:55:01','2026-10-21 11:55:01');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_10_000001_create_clothing_types_table',1),(5,'2026_01_10_000002_create_clients_table',1),(6,'2026_01_10_000003_create_measurements_table',1),(7,'2026_01_10_000010_create_catalog_models_table',1),(8,'2026_01_10_000011_create_model_photos_table',1),(9,'2026_01_10_000012_create_favorites_table',1),(10,'2026_01_10_000020_create_orders_table',1),(11,'2026_01_10_000030_create_appointments_table',1),(12,'2026_01_10_000040_create_fabrics_table',1),(13,'2026_01_10_000050_create_messages_table',1),(14,'2026_01_10_000060_create_reviews_table',1),(15,'2026_01_10_000070_create_settings_table',1),(16,'2026_10_21_093331_create_notifications_table',1),(17,'2026_10_21_110417_add_last_visit_at_to_clients_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `model_photos`
--

DROP TABLE IF EXISTS `model_photos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `model_photos` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `catalog_model_id` bigint(20) unsigned NOT NULL,
  `path` varchar(255) NOT NULL,
  `alt` varchar(150) DEFAULT NULL,
  `position` tinyint(3) unsigned NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `model_photos_catalog_model_id_foreign` (`catalog_model_id`),
  CONSTRAINT `model_photos_catalog_model_id_foreign` FOREIGN KEY (`catalog_model_id`) REFERENCES `catalog_models` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `model_photos`
--

LOCK TABLES `model_photos` WRITE;
/*!40000 ALTER TABLE `model_photos` DISABLE KEYS */;
INSERT INTO `model_photos` VALUES (1,1,'models/robe-bazin-brodee-or.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,2,'models/boubou-homme-premier-roi.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,3,'models/ensemble-wax-ceremonie.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,4,'models/robe-soirette-ville.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,5,'models/suit-3-pieces-marie.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(6,6,'models/peau-toyota-brodee.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(7,7,'models/grand-boubou-bapteme.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(8,8,'models/robe-espanola-moderne.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(9,9,'models/djellaba-homme-zat-rouge.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(10,10,'models/jupe-debardeur-duo.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(11,11,'models/tailleur-fusion-mariage.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(12,12,'models/grand-mbubb-3-waars.svg',NULL,0,'2026-10-21 11:16:52','2026-10-21 11:16:52');
/*!40000 ALTER TABLE `model_photos` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notifications`
--

DROP TABLE IF EXISTS `notifications`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) unsigned NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notifications`
--

LOCK TABLES `notifications` WRITE;
/*!40000 ALTER TABLE `notifications` DISABLE KEYS */;
INSERT INTO `notifications` VALUES ('8df9b2ca-c6d3-4364-be14-d573caff315f','App\\Notifications\\ShopNotification','App\\Models\\User',4,'{\"title\":\"Nouveau message de l\'atelier\",\"body\":\"L\'atelier Soubla Salam Couture a r\\u00e9pondu : \\u00ab Bien vouloir venir avec le bazin samedi SVP \\u00bb\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/espace-client\",\"icon\":\"chat-dots\"}','2026-10-21 11:59:03','2026-10-21 11:52:32','2026-10-21 11:59:03'),('a78fb35d-7613-40b7-a685-a9e6fb562e99','App\\Notifications\\ShopNotification','App\\Models\\User',4,'{\"title\":\"Paiement de 10 000 FCFA enregistr\\u00e9\",\"body\":\"Votre Acompte de 10000 FCFA (r\\u00e9f. REC-20261021-VZU) a \\u00e9t\\u00e9 enregistr\\u00e9 sur la commande SSC-26-LCS3R.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/espace-client\\/commandes\\/SSC-26-LCS3R\",\"icon\":\"cash-coin\"}','2026-10-21 11:59:03','2026-10-21 11:46:44','2026-10-21 11:59:03'),('aee6106f-e26d-4383-af0d-68b01dcb5567','App\\Notifications\\ShopNotification','App\\Models\\User',5,'{\"title\":\"Commande SSC-26-R2SN3 annul\\u00e9e\",\"body\":\"Votre commande a \\u00e9t\\u00e9 annul\\u00e9e par l\'atelier : Evenement reporte par le client, commande annulee avec accord de l atelier\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/espace-client\\/commandes\\/SSC-26-R2SN3\",\"icon\":\"x-circle\"}',NULL,'2026-10-21 11:47:42','2026-10-21 11:47:42'),('d13d3b37-75b3-45fe-903d-451fa1bc8533','App\\Notifications\\ShopNotification','App\\Models\\User',5,'{\"title\":\"Rendez-vous fix\\u00e9 : 26 octobre 2026 \\u00e0 10h30\",\"body\":\"L\'atelier a programm\\u00e9 votre rendez-vous (Essayage). Merci d\'\\u00eatre ponctuel(le) et de pr\\u00e9venir en cas d\'impr\\u00e9vu.\",\"url\":\"http:\\/\\/127.0.0.1:8000\\/espace-client\\/rendez-vous\",\"icon\":\"calendar-check\"}',NULL,'2026-10-21 11:52:05','2026-10-21 11:52:05');
/*!40000 ALTER TABLE `notifications` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `order_status_history`
--

DROP TABLE IF EXISTS `order_status_history`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `order_status_history` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `status` varchar(20) NOT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `changed_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `order_status_history_order_id_foreign` (`order_id`),
  KEY `order_status_history_changed_by_foreign` (`changed_by`),
  CONSTRAINT `order_status_history_changed_by_foreign` FOREIGN KEY (`changed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  CONSTRAINT `order_status_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `order_status_history`
--

LOCK TABLES `order_status_history` WRITE;
/*!40000 ALTER TABLE `order_status_history` DISABLE KEYS */;
INSERT INTO `order_status_history` VALUES (1,1,'validated','Devis valid├®, cr├®ation de la commande.',NULL,2,'2026-09-30 11:16:52','2026-10-21 11:16:52'),(2,1,'measures_taken','Mensurations relev├®es ├á l\'atelier.',NULL,2,'2026-10-01 11:16:52','2026-10-21 11:16:52'),(3,1,'cutting',NULL,NULL,2,'2026-10-04 11:16:52','2026-10-21 11:16:52'),(4,1,'sewing','Machine thread 03 ; broderie sous-trait├®e',NULL,2,'2026-10-07 11:16:52','2026-10-21 11:16:52'),(5,1,'ready','Termin├®e ÔÇö contr├┤le des finitions.',NULL,2,'2026-10-12 11:16:52','2026-10-21 11:16:52'),(6,1,'delivered','Livraison ├á domicile (Fass).',NULL,2,'2026-10-13 11:16:52','2026-10-21 11:16:52'),(7,2,'validated','Acompte re├ºu.',NULL,2,'2026-10-11 11:16:52','2026-10-21 11:16:52'),(8,2,'measures_taken',NULL,NULL,2,'2026-10-12 11:16:52','2026-10-21 11:16:52'),(9,2,'cutting','Coupe du 2e pain de bazin',NULL,2,'2026-10-16 11:16:52','2026-10-21 11:16:52'),(10,2,'sewing',NULL,NULL,2,'2026-10-19 11:16:52','2026-10-21 11:16:52'),(11,3,'validated','Acompte re├ºu.',NULL,3,'2026-10-11 11:16:52','2026-10-21 11:16:52'),(12,3,'measures_taken',NULL,NULL,3,'2026-10-12 11:16:52','2026-10-21 11:16:52'),(13,3,'cutting','Coupe du 2e pain de bazin',NULL,3,'2026-10-16 11:16:52','2026-10-21 11:16:52'),(14,3,'sewing',NULL,NULL,3,'2026-10-19 11:16:52','2026-10-21 11:16:52'),(15,6,'validated',NULL,NULL,3,'2026-10-07 11:16:52','2026-10-21 11:16:52'),(16,6,'cutting',NULL,NULL,3,'2026-10-09 11:16:52','2026-10-21 11:16:52'),(17,6,'cancelled','MARIAGE REPORTE PAR LE CLIENT ÔÇö tissu de r├®serve en stock.',NULL,3,'2026-10-10 11:16:52','2026-10-21 11:16:52'),(18,7,'validated',NULL,NULL,2,'2026-10-08 11:16:52','2026-10-21 11:16:52'),(19,7,'measures_taken',NULL,NULL,2,'2026-10-09 11:16:52','2026-10-21 11:16:52'),(20,7,'ready','Repass├® + housse.',NULL,2,'2026-10-20 11:16:52','2026-10-21 11:16:52'),(21,8,'requested','Demande de devis envoy├®e par le client.',NULL,4,'2026-10-21 11:37:13','2026-10-21 11:37:13'),(22,8,'validated','Devis accepte par le client',NULL,2,'2026-10-21 11:44:51','2026-10-21 11:44:51'),(23,4,'cancelled','Evenement reporte par le client, commande annulee avec accord de l atelier',NULL,1,'2026-10-21 11:47:42','2026-10-21 11:47:42');
/*!40000 ALTER TABLE `order_status_history` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `orders`
--

DROP TABLE IF EXISTS `orders`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `orders` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(20) NOT NULL,
  `client_id` bigint(20) unsigned NOT NULL,
  `tailleur_id` bigint(20) unsigned DEFAULT NULL,
  `catalog_model_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(10) NOT NULL DEFAULT 'order',
  `description` text DEFAULT NULL,
  `inspiration_photo` varchar(255) DEFAULT NULL,
  `fabric` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `delivery_date` date DEFAULT NULL,
  `status` varchar(20) NOT NULL DEFAULT 'requested',
  `comment` varchar(150) DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `tailleur_notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `orders_reference_unique` (`reference`),
  KEY `orders_client_id_foreign` (`client_id`),
  KEY `orders_tailleur_id_foreign` (`tailleur_id`),
  KEY `orders_catalog_model_id_foreign` (`catalog_model_id`),
  KEY `orders_delivery_date_index` (`delivery_date`),
  KEY `orders_status_index` (`status`),
  CONSTRAINT `orders_catalog_model_id_foreign` FOREIGN KEY (`catalog_model_id`) REFERENCES `catalog_models` (`id`) ON DELETE SET NULL,
  CONSTRAINT `orders_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE CASCADE,
  CONSTRAINT `orders_tailleur_id_foreign` FOREIGN KEY (`tailleur_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `orders`
--

LOCK TABLES `orders` WRITE;
/*!40000 ALTER TABLE `orders` DISABLE KEYS */;
INSERT INTO `orders` VALUES (1,'SSC-26-V33O1',1,2,1,'order','Robe bazin brod├®e or (Korit├®), coupe ajust├®e, manches longues.',NULL,'Bazin getzner bleu',NULL,'2026-10-13','delivered',NULL,35000.00,NULL,'2026-09-30 11:16:52','2026-10-13 11:16:52'),(2,'SSC-26-1NX65',1,2,2,'order','Boubou premier roi blanc/dor├® pour Tabaski, col rond, 2 poches appliqu├®es.',NULL,'Bazin riche rips blanc','2026-11-06','2026-11-02','sewing',NULL,50000.00,NULL,'2026-10-11 11:16:52','2026-10-19 11:16:52'),(3,'SSC-26-VOCMU',2,3,6,'order','Boubou premier roi blanc/dor├® pour Tabaski, col rond, 2 poches appliqu├®es.',NULL,'Bazin riche rips blanc','2026-11-06','2026-11-02','sewing',NULL,50000.00,NULL,'2026-10-11 11:16:52','2026-10-19 11:16:52'),(4,'SSC-26-R2SN3',2,NULL,8,'order','Robe espagnole pour bapt├¬me, dans tons saumon, taille dorsal ajust├®e.',NULL,NULL,'2026-11-12',NULL,'cancelled',NULL,0.00,NULL,'2026-10-19 11:16:52','2026-10-21 11:47:42'),(5,'SSC-26-XWRQR',3,2,12,'quote','Grand mbubb 3 waars pour Tabaski, tissu fourni (bazin bordeaux), broderie esperada col.',NULL,NULL,'2026-11-07',NULL,'requested',NULL,0.00,NULL,'2026-10-18 11:16:52','2026-10-21 11:16:52'),(6,'SSC-26-AIXKB',4,3,NULL,'order','Robe du soir dentelle poudr├® pour mariage fin du mois.',NULL,NULL,NULL,NULL,'cancelled',NULL,68000.00,NULL,'2026-10-07 11:16:52','2026-10-10 11:16:52'),(7,'SSC-26-AVRBU',2,2,9,'order','Djellaba zat rouge pour Korit├® ÔÇö termin├®, en attente d\'encaissement.',NULL,'Bazin getzner bordeaux',NULL,'2026-10-23','ready',NULL,30000.00,NULL,'2026-10-08 11:16:52','2026-10-20 11:16:52'),(8,'SSC-26-LCS3R',1,2,NULL,'quote','Robe bazin couture pour mariage',NULL,'bazin getzner bleu',NULL,'2026-12-15','validated',NULL,35000.00,NULL,'2026-10-21 11:37:13','2026-10-21 11:44:51');
/*!40000 ALTER TABLE `orders` ENABLE KEYS */;
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
-- Table structure for table `payments`
--

DROP TABLE IF EXISTS `payments`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `payments` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` bigint(20) unsigned NOT NULL,
  `receipt_number` varchar(30) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `method` varchar(20) NOT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'deposit',
  `paid_at` date NOT NULL,
  `recorded_by` bigint(20) unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `payments_receipt_number_unique` (`receipt_number`),
  KEY `payments_order_id_foreign` (`order_id`),
  KEY `payments_recorded_by_foreign` (`recorded_by`),
  CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `payments_recorded_by_foreign` FOREIGN KEY (`recorded_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `payments`
--

LOCK TABLES `payments` WRITE;
/*!40000 ALTER TABLE `payments` DISABLE KEYS */;
INSERT INTO `payments` VALUES (1,1,'REC-20261021-AVZ',17500.00,'cash','deposit','2026-09-30',2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(2,1,'REC-20261021-MOX',17500.00,'wave','balance','2026-10-13',2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(3,2,'REC-20261021-UXT',25000.00,'orange_money','deposit','2026-10-11',2,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(4,3,'REC-20261021-DOJ',25000.00,'orange_money','deposit','2026-10-11',3,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,8,'REC-20261021-VZU',10000.00,'wave','deposit','2026-10-21',2,'2026-10-21 11:46:42','2026-10-21 11:46:42');
/*!40000 ALTER TABLE `payments` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `reviews`
--

DROP TABLE IF EXISTS `reviews`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `reviews` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` bigint(20) unsigned DEFAULT NULL,
  `catalog_model_id` bigint(20) unsigned DEFAULT NULL,
  `order_id` bigint(20) unsigned DEFAULT NULL,
  `author_name` varchar(100) NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  `comment` text DEFAULT NULL,
  `status` varchar(15) NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reviews_user_id_foreign` (`user_id`),
  KEY `reviews_client_id_foreign` (`client_id`),
  KEY `reviews_catalog_model_id_foreign` (`catalog_model_id`),
  KEY `reviews_order_id_foreign` (`order_id`),
  KEY `reviews_status_index` (`status`),
  CONSTRAINT `reviews_catalog_model_id_foreign` FOREIGN KEY (`catalog_model_id`) REFERENCES `catalog_models` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_client_id_foreign` FOREIGN KEY (`client_id`) REFERENCES `clients` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `reviews`
--

LOCK TABLES `reviews` WRITE;
/*!40000 ALTER TABLE `reviews` DISABLE KEYS */;
INSERT INTO `reviews` VALUES (1,4,1,1,1,'Fatou B.',5,'Coupe impeccable','pending','2026-10-21 11:16:52','2026-10-21 11:51:09');
/*!40000 ALTER TABLE `reviews` ENABLE KEYS */;
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
INSERT INTO `sessions` VALUES ('0pYkHN5hA9kczAW4KBFADHVzMasveQFeJckmE0oW',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJQNHpUcHlmdDRaQ0cyZFlmakZiODNOVTk1eUd4S1JLRWZGaWx2NEZtIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jb21tYW5kZXNcL1NTQy0yNi1MQ1MzUiIsInJvdXRlIjoidGFpbGxldXIub3JkZXJzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6WyJfb2xkX2lucHV0IiwiZXJyb3JzIl0sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0IiwiX29sZF9pbnB1dCI6W10sImVycm9ycyI6eyJkZWZhdWx0Ijp7ImZvcm1hdCI6IjptZXNzYWdlIiwibWVzc2FnZXMiOnsiYW1vdW50IjpbIkluZGlxdWV6IGxlIG1vbnRhbnQgdG90YWwgZGUgbGEgY29tbWFuZGUuIl19fX19',1792583010),('1MVDLgZPMDyOhdvUG8E4ZPBqK8xkzjOdTQSoXkHt',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJiblBrZDROaTJ6OERvU0U1YVZ2OEV6TUpBeFk0WFNDNmZmOTZ0R0VJIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb25uZXhpb24iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792583345),('24wmMezpsQsYVM3bzw4lRUchp32uWO5dDrrLTqLq',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJHdEdnOEdzNkR6UUtpeDNJMWd0ak9UYUc2WXF6QXVmcVVCd0g3RnBNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyIiwicm91dGUiOiJ0YWlsbGV1ci5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792581505),('2NBFGaZLEzzSLtWZI7npbPmfNWpXckwH4bgNoZo9',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJzM0VlS0h2V3lpdVU1SWZGeUlVUk1VYVdEOUt3RmpTaFFpaVRtMkRJIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvYXRlbGllclwvcGxhbm5pbmcifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9wbGFubmluZyIsInJvdXRlIjoidGFpbGxldXIucGxhbm5pbmcifX0=',1792583384),('45cbvfLSSTaUJnNvik8wSSC0pd86i13eOyr7gyO1',6,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJhcW0zWE9jV0UyQmI1NUhES2t1SEZ1YWVnTWJVd0VEbHZENVVJeVBTIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lc3BhY2UtY2xpZW50Iiwicm91dGUiOiJjbGllbnQuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjYsInBhc3N3b3JkX2hhc2hfd2ViIjoiMDY1Mjc3MzhhOThmNTEwMmRiYzE2ZjJhNzQwOWNlYjc1MGUwZGUxZWMwOWQxZTBiZjdjNjU3ZGI0M2E5MjRmMSJ9',1792583745),('4IzoLtnckqn0dnRV0VoTG1BGZaDWk97ZwUscPFMO',4,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJTQmtLS2s2a01QZjc3VFVlRkkyVDdqekJJMXZDN0FVSUlvN0VYR0NKIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ub3RpZmljYXRpb25zIiwicm91dGUiOiJub3RpZmljYXRpb25zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792583828),('5hbnSwfkQnuqrqBDmbiYupByPFzUQF2EIYtW8kYI',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiI5a0puS1BkSEx6TkFpSWh2VTJuaElpT2E5ajhKdUJwZjVtcGRjVUliIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvcGFyYW1ldHJlcyIsInJvdXRlIjoiYWRtaW4uc2V0dGluZ3MifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIxMThjZmEyNDVkYTUwZDI5NWY3OTczZDIyNzhkZjU2MWZlNWM5ODMwYWJhMDhkYzI4MWZkMDM1Y2ZkYjkyOWExIn0=',1792582102),('72vg5310iPj6Io9zO2yDZ727Wn17qiBAtIREjnxe',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ2Q3duTVVaNjZndjNucGFRbldJdzBYYmtiVXYxVkFpZGdFb3BTVkRaIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jb21tYW5kZXNcL1NTQy0yNi1MQ1MzUiIsInJvdXRlIjoidGFpbGxldXIub3JkZXJzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792582912),('76YojIT0RAWjEAvT7IvauoU8Vhjjp05GIFaKVO2s',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiIwdkZJNEQ2TlVKUWhYcnRnT3k1Ym9DZDBHcXdiTmxKOFNNa3JHWEJKIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119fQ==',1792582610),('7hZnmzR3vID7kRYQws02zuZ8yxtz5sj5IoAKfIOD',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJJenVHeGx3bWxNUENOSFpRQ1lkaDJ0RDFpa3lnRHNWYTJvSmZEcFQzIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0XC9zYXV2ZWdhcmRlLWJhc2UiLCJyb3V0ZSI6ImFkbWluLmV4cG9ydC5kb3dubG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjExOGNmYTI0NWRhNTBkMjk1Zjc5NzNkMjI3OGRmNTYxZmU1Yzk4MzBhYmEwOGRjMjgxZmQwMzVjZmRiOTI5YTEifQ==',1792582393),('9feI3Vd4dW0aWqEYDpJloenEn8laAN4EFxoTeD7L',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJFT2pjR1RqSXFCS201ZTlSUzQ2ZEgzUEZLWFRCckhnVE1Pc3FHM3ZNIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9pbnNjcmlwdGlvbiIsInJvdXRlIjoicmVnaXN0ZXIifSwiX2ZsYXNoIjp7Im9sZCI6WyJfb2xkX2lucHV0IiwiZXJyb3JzIl0sIm5ldyI6W119LCJfb2xkX2lucHV0Ijp7Il90b2tlbiI6IkVPamNHVGpJcUJLbTVlOVJTNDZkSDNQRktYVEJySGdUTU9zcUczdk0iLCJsYXN0X25hbWUiOiJOZGlheWUiLCJmaXJzdF9uYW1lIjoiT3VzbWFuZSIsImVtYWlsIjoib3VzbWFuZS5uZGlheWVAZXhhbXBsZS5zbiIsInBob25lIjoiMjIxNzcwOTA5MDkxIn0sImVycm9ycyI6eyJkZWZhdWx0Ijp7ImZvcm1hdCI6IjptZXNzYWdlIiwibWVzc2FnZXMiOnsiY29uc2VudCI6WyJWb3VzIGRldmV6IGFjY2VwdGVyIGxlIHRyYWl0ZW1lbnQgZGUgdm9zIGRvbm5cdTAwZTllcyBwb3VyIGNyXHUwMGU5ZXIgdW4gY29tcHRlLiJdfX19fQ==',1792583719),('a1b8Ne7jOn4r8xHcp0hybkd2BpbkBmZiVoghUg7S',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiI3V0YxQTZUdGdsU0pQT1cwdm5HV2tSczRQbVljUmNVWHFYdHUxd000IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jb21tYW5kZXNcL1NTQy0yNi1MQ1MzUiIsInJvdXRlIjoidGFpbGxldXIub3JkZXJzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792582857),('a8rQU15kyrBzkaMAprGdX5HgW9PnMoSBadY84TzA',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiI2aWhGWWIzSE0wcXE3V2NIM3JkajlZYWNpMHpmWGs2ODdxYjdaNVhLIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0Iiwicm91dGUiOiJhZG1pbi5leHBvcnQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIxMThjZmEyNDVkYTUwZDI5NWY3OTczZDIyNzhkZjU2MWZlNWM5ODMwYWJhMDhkYzI4MWZkMDM1Y2ZkYjkyOWExIn0=',1792581610),('AgqI9pAVS8H4y1xPWbD6kEQNiFgGqXxrGCYhqI6y',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiIwdHM4YzVObGtEN3RJSHhlVTFPVk5KQ1M2TTVIVTB6RzlvcjhRVGFGIiwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJ1cmwiOnsiaW50ZW5kZWQiOiJodHRwOlwvXC8xMjcuMC4wLjE6ODAwMFwvZXNwYWNlLWNsaWVudFwvY29tbWFuZGVzXC9TU0MtMjYtVjMzTzEifSwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lc3BhY2UtY2xpZW50XC9jb21tYW5kZXNcL1NTQy0yNi1WMzNPMSIsInJvdXRlIjoiY2xpZW50Lm9yZGVycy5zaG93In19',1792583416),('cs8OPxkTPrpXPKCGLNVOAf3i4IIEZUenY7Z3BhCJ',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJud0h2UnVrS1hDUFFzOGJUbm1BM1NTaFhWb1VuUUhtRmtzbmxpcE8yIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250YWN0Iiwicm91dGUiOiJjb250YWN0In0sIl9mbGFzaCI6eyJvbGQiOlsic3VjY2VzcyJdLCJuZXciOltdfSwic3VjY2VzcyI6Ik1lcmNpICEgVm90cmUgbWVzc2FnZSBhIFx1MDBlOXRcdTAwZTkgdHJhbnNtaXMgXHUwMGUwIGwnYXRlbGllci4gTm91cyB2b3VzIHJcdTAwZTlwb25kb25zIHRyXHUwMGU4cyB2aXRlLiJ9',1792583701),('DnyzOamxtnC26ZQS76nFMRJGi3W0CK3pwR3v734I',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJQNnZUWGw3MnIyaE1UM2NCbVVyTTZaWU9VZG4za3hIbElMaXI3QmJqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMTE4Y2ZhMjQ1ZGE1MGQyOTVmNzk3M2QyMjc4ZGY1NjFmZTVjOTgzMGFiYTA4ZGMyODFmZDAzNWNmZGI5MjlhMSJ9',1792582552),('f36adNMyhWrw7S0sf4h6UvvumHHj4ZdqgB1iXCTv',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJZYW9XNVVoYWZwUGEzWkJjSk13WTJVVlJxM1R4TklMYVlPUE55Y2lqIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9yZWNoZXJjaGU/cT1iYXppbiIsInJvdXRlIjoidGFpbGxldXIuc2VhcmNoIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiY2FmZTM5OWMxN2I4YjFhMjEwNjA5OWU2YTRiNjJiMTI2NzdiMDdiNmRkZDU4NTZjODRjOWJmZjY4YTc0N2I2NCJ9',1792581579),('HQtBBS1gJXM0dwgt1mLy5yJXfS9DYXGuqPuuxyOR',4,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJJOE9lUGFPeVpoTlU0bGY3NlZDOXRvRmxSa1hSTVQyZE1nSU5qUUJJIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ub3RpZmljYXRpb25zIiwicm91dGUiOiJub3RpZmljYXRpb25zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792583944),('j7Ak88Kur149Jc5UIa8zocvIM86zPJxQ9mAXchx0',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJFM293ZTFKS3luQThSWkhuZklRd085VVVVY0NMYk1RdlU3TlZZUU56IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMTE4Y2ZhMjQ1ZGE1MGQyOTVmNzk3M2QyMjc4ZGY1NjFmZTVjOTgzMGFiYTA4ZGMyODFmZDAzNWNmZGI5MjlhMSJ9',1792582564),('kDTVqUMa7RjTmxqqqeE4t7foZcHV9knBkY5rfzCi',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ0SVdYbzJMTzZUY3laRU9WVzk5cm0yemtjbm5zazJPNWpvS3NpRVAxIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvbiIsInJvdXRlIjoiYWRtaW4uZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMTE4Y2ZhMjQ1ZGE1MGQyOTVmNzk3M2QyMjc4ZGY1NjFmZTVjOTgzMGFiYTA4ZGMyODFmZDAzNWNmZGI5MjlhMSJ9',1792582576),('l7Toi07gnejUWyRgADjhEmOsPh49xfpVEMWRyGuL',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJrQW1LRkRkS3dab0RBd0ZCS3RRaGlmU3JoMTQzUURJcDJkcjBzWFkyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jb21tYW5kZXNcL1NTQy0yNi1MQ1MzUiIsInJvdXRlIjoidGFpbGxldXIub3JkZXJzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792582887),('l8krQI0OOait8hzN0sfmdRkKWsnuSUiAxqxD4YPq',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJjbkJWalkycUZnaGF5RGRXd2lCcTE2RkJSUTJqNFlmQXlDZHVid0M3IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9zdG9ja1wvYWxlcnRlcyIsInJvdXRlIjoidGFpbGxldXIuZmFicmljcy5hbGVydHMifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792582366),('lSy4sQpf6OWZJMyEim8ke3bxqcxm2ZxtY4Yqi1tz',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJZV0VwS3FBZEd4Um5pbEh6NmFWZ0JvQnVuak5xRFNSUVNJdDBiS0prIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvY2F0YWxvZ3VlIiwicm91dGUiOiJhZG1pbi5jYXRhbG9nLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMTE4Y2ZhMjQ1ZGE1MGQyOTVmNzk3M2QyMjc4ZGY1NjFmZTVjOTgzMGFiYTA4ZGMyODFmZDAzNWNmZGI5MjlhMSJ9',1792583262),('MOn25zkuHOYIzrtxumGCMvnYRwnqIKDSqzjCd41M',4,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJGcTA3UFRZUzVzdUpmUlZFbnlvbjVocUZDS0I4S09meWFJaUhkaEt5IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ub3RpZmljYXRpb25zIiwicm91dGUiOiJub3RpZmljYXRpb25zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792583908),('NqRXpan1QPKvywjmH1Tc8GwPpRaJrcUouJpD7gjp',2,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJIMHZSRTZvZXpsN2tva1lkNGlkRWY2RXFqY1d1TDVDQVRtN0hFWWNsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9wYWllbWVudHNcLzVcL3JlY3UiLCJyb3V0ZSI6InRhaWxsZXVyLnJlY2VpcHQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792583231),('o3Heu1ohB7LMNoUZqDNME2gmMKpCdGtPTIVEw3QK',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJya20wVE9CYlFselVKZENkRXhLaXJVNFBnbkNWYmUzRjhiQjAyNzA1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb25uZXhpb24iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792583345),('P5ayTVRwSrR6Ag7l3ihUljNs7lDifduW8VDemzQZ',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJWWFU4SnB3R3BUbjNBdVdkVUQxR2FkMnR5ZXBxTGVQdlhuTlBFVnJuIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0XC9zYXV2ZWdhcmRlLWJhc2UiLCJyb3V0ZSI6ImFkbWluLmV4cG9ydC5kb3dubG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjExOGNmYTI0NWRhNTBkMjk1Zjc5NzNkMjI3OGRmNTYxZmU1Yzk4MzBhYmEwOGRjMjgxZmQwMzVjZmRiOTI5YTEifQ==',1792582424),('PCOQFrOhMqhTAmo5IjZeDsjdJuGyn5FDDv8yblR6',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiIzbFZYb1dVc2Q3bm5PalM5TTR1a2xPdWV6YVIxellLOVFlNjl0b01vIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyIiwicm91dGUiOiJ0YWlsbGV1ci5kYXNoYm9hcmQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0In0=',1792582871),('PIDiKElqvKU2i9t8hUyyrozJ5IFb3q2g7XrZ3SLd',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ2YkhNaW1WNmpQSExuZ1BuSDNvc1JwdGg4RlFjMnMzYk9WYXdwbXllIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0XC9zYXV2ZWdhcmRlLWJhc2UiLCJyb3V0ZSI6ImFkbWluLmV4cG9ydC5kb3dubG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjExOGNmYTI0NWRhNTBkMjk1Zjc5NzNkMjI3OGRmNTYxZmU1Yzk4MzBhYmEwOGRjMjgxZmQwMzVjZmRiOTI5YTEifQ==',1792582522),('r8YQzmzd1thohrub3Ha2NPyR8bUR72wBNpvlx1zZ',4,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiI2UFJ3YmQ4bm5hOW5JQU5hV25vYzA3NmpoZERxVVBZeHlRT3cwTjFlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250YWN0P3NlbnQ9Iiwicm91dGUiOiJjb250YWN0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792582190),('rk0dOcHYMQHMIFwDavhaQ1anFCN2bpJTlkLP74C8',4,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJZSGZDTUxmWXNOM3ZwelpnSzhMRFM2TkdHTklmUmw4dWlzYkFjZVM1IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lc3BhY2UtY2xpZW50Iiwicm91dGUiOiJjbGllbnQuZGFzaGJvYXJkIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792583437),('sAEWGABLWmPHSo1vo8CmFy5kIihuRgnxrLt6Z2BX',NULL,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJPSld5cUdtOVBUQVpBYnZKNFYxTjNaOFZDdzJrVHRKVUZzbkRIckVsIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb25uZXhpb24iLCJyb3V0ZSI6ImxvZ2luIn0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792583031),('sF5Qd0cXrTPSPLliVhJO2RyQmKbn82CzZGvaJJTc',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJUTURjMHgyWEpBRFN2ZkJuYWtzT21QSVVjZUlJSkhVcFNQcVc2QUc4IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvcGFyYW1ldHJlcyIsInJvdXRlIjoiYWRtaW4uc2V0dGluZ3MifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIxMThjZmEyNDVkYTUwZDI5NWY3OTczZDIyNzhkZjU2MWZlNWM5ODMwYWJhMDhkYzI4MWZkMDM1Y2ZkYjkyOWExIn0=',1792582283),('SJ4Huq6UdaJjBdVPDisdWYEmEMNU92qO83YQUPlX',2,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJHcFpEUmFFVHdOM3VvUnpzWlNsTW1iamRtN3I4UkZncG5va2pCYlJrIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9tZXNzYWdlcyIsInJvdXRlIjoidGFpbGxldXIubWVzc2FnZXMuaW5kZXgifSwiX2ZsYXNoIjp7Im9sZCI6WyJzdWNjZXNzIl0sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0Iiwic3VjY2VzcyI6IlJcdTAwZTlwb25zZSBlbnZveVx1MDBlOWUuIn0=',1792583552),('sTo08MWKAYUO4jQJ3jYsxtLGZ1jKErsc6CCnsNvn',4,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ5UmwyN3RNYkczU2xYQWxRcUx4N2ZXWFdQRENFdktwalB1R1QwME93IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9lc3BhY2UtY2xpZW50XC9jb21tYW5kZXNcL25vdXZlbGxlIiwicm91dGUiOiJjbGllbnQub3JkZXJzLmNyZWF0ZSJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjo0LCJwYXNzd29yZF9oYXNoX3dlYiI6Ijk5MDg4ODJiYTQyY2NlOTk2N2Q0OWQwYWQ4YWQ2OWM3Mjk5ZjEzYjU5MzRiZWVlYjU2OWY0MTc1OTM3MzQ2MzMifQ==',1792582647),('U1GxQGpUwv4Dry2TLJhLIwySP0OCGs8jUOF9pJuE',4,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJBNThpMUtnc01tc3BjcW0wQ2NkUUpXbHhNakw2QXVuV1hsM2NXa01yIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9ub3RpZmljYXRpb25zIiwicm91dGUiOiJub3RpZmljYXRpb25zLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjQsInBhc3N3b3JkX2hhc2hfd2ViIjoiOTkwODg4MmJhNDJjY2U5OTY3ZDQ5ZDBhZDhhZDY5YzcyOTlmMTNiNTkzNGJlZWViNTY5ZjQxNzU5MzczNDYzMyJ9',1792583793),('vRUKgfQzDwrMipDJbzHVsBcO8y6eIBsy48p91WXH',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJMeUNNYWhURlhrR1dPYVFkazI2amUyUk1aQ1FqMUl0OGdnTWJrV2F0IiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0XC9zYXV2ZWdhcmRlLWJhc2UiLCJyb3V0ZSI6ImFkbWluLmV4cG9ydC5kb3dubG9hZCJ9LCJfZmxhc2giOnsib2xkIjpbXSwibmV3IjpbXX0sImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjoxLCJwYXNzd29yZF9oYXNoX3dlYiI6IjExOGNmYTI0NWRhNTBkMjk1Zjc5NzNkMjI3OGRmNTYxZmU1Yzk4MzBhYmEwOGRjMjgxZmQwMzVjZmRiOTI5YTEifQ==',1792582468),('x70BppRgBeLuPgUaeKDOmCz25w8BkINVpSwsJU3t',NULL,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJ3V3NxN1NBUGQzMjJmNzI1SkJ4U2k0OWJHZHdjamYxQ2VIVHpybWdyIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9jb250YWN0Iiwicm91dGUiOiJjb250YWN0In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfX0=',1792583575),('YK7jiakTCeeohqQ81QzFdJEHcmaNLjjoVHthfwfo',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ0YnFkWFlXek9hQW0wc0w2c0prSm9tcTREcmk0TW9GdmpzVmpBMkxvIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jbGllbnRzIiwicm91dGUiOiJ0YWlsbGV1ci5jbGllbnRzLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOltdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjIsInBhc3N3b3JkX2hhc2hfd2ViIjoiY2FmZTM5OWMxN2I4YjFhMjEwNjA5OWU2YTRiNjJiMTI2NzdiMDdiNmRkZDU4NTZjODRjOWJmZjY4YTc0N2I2NCJ9',1792581475),('yqDMzLKK57ZGJxLj1I4rCZmeFH4Gwv7BLRGPnqZE',2,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJsSHRMRXA0c0VCWTdZMVdGZTBlWGc4VHI2OG53Zll5Y1IybnR2YXZlIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hdGVsaWVyXC9jb21tYW5kZXNcL1NTQy0yNi1MQ1MzUiIsInJvdXRlIjoidGFpbGxldXIub3JkZXJzLnNob3cifSwiX2ZsYXNoIjp7Im9sZCI6WyJfb2xkX2lucHV0IiwiZXJyb3JzIl0sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MiwicGFzc3dvcmRfaGFzaF93ZWIiOiJjYWZlMzk5YzE3YjhiMWEyMTA2MDk5ZTZhNGI2MmIxMjY3N2IwN2I2ZGRkNTg1NmM4NGM5YmZmNjhhNzQ3YjY0IiwiX29sZF9pbnB1dCI6W10sImVycm9ycyI6eyJkZWZhdWx0Ijp7ImZvcm1hdCI6IjptZXNzYWdlIiwibWVzc2FnZXMiOnsiYW1vdW50IjpbIkluZGlxdWV6IGxlIG1vbnRhbnQgdG90YWwgZGUgbGEgY29tbWFuZGUuIl19fX19',1792582947),('YSpffGAFPtBRMmEb1hVc5oihh6TzVb1999exJ2tE',1,'127.0.0.1','curl/8.21.0','eyJfdG9rZW4iOiJiaWlnRkZnTUhtQWNYWnE4Yldha3Z1RUJhSDdkbUlYNFJvVDdxM05jIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvY2F0YWxvZ3VlIiwicm91dGUiOiJhZG1pbi5jYXRhbG9nLmluZGV4In0sIl9mbGFzaCI6eyJvbGQiOlsic3VjY2VzcyJdLCJuZXciOltdfSwibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiOjEsInBhc3N3b3JkX2hhc2hfd2ViIjoiMTE4Y2ZhMjQ1ZGE1MGQyOTVmNzk3M2QyMjc4ZGY1NjFmZTVjOTgzMGFiYTA4ZGMyODFmZDAzNWNmZGI5MjlhMSIsInN1Y2Nlc3MiOiJcdTAwYWIgUm9iZSB2ZWlsXHUwMGU5ZSBicm9kXHUwMGU5ZSBpdm9pcmUgXHUwMGJiIGVzdCBtYWludGVuYW50IHB1YmxpXHUwMGU5IHN1ciBsZSBwb3J0YWlsLiJ9',1792583319),('zHZhrRdS7vJ2UycVEgijjVY7Vu22eVJQh4dNhtDL',1,'127.0.0.1','Mozilla/5.0 (Windows NT; Windows NT 10.0; fr-FR) WindowsPowerShell/5.1.26100.9711','eyJfdG9rZW4iOiJ4enk4Q2FRUXU4OTM5VFhuNUROM1RUcjFYWEtGN0VCZ09QTUc1ZW9TIiwiX3ByZXZpb3VzIjp7InVybCI6Imh0dHA6XC9cLzEyNy4wLjAuMTo4MDAwXC9hZG1pbmlzdHJhdGlvblwvZXhwb3J0Iiwicm91dGUiOiJhZG1pbi5leHBvcnQifSwiX2ZsYXNoIjp7Im9sZCI6W10sIm5ldyI6W119LCJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI6MSwicGFzc3dvcmRfaGFzaF93ZWIiOiIxMThjZmEyNDVkYTUwZDI5NWY3OTczZDIyNzhkZjU2MWZlNWM5ODMwYWJhMDhkYzI4MWZkMDM1Y2ZkYjkyOWExIn0=',1792582172);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `settings`
--

DROP TABLE IF EXISTS `settings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `settings` (
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `settings`
--

LOCK TABLES `settings` WRITE;
/*!40000 ALTER TABLE `settings` DISABLE KEYS */;
INSERT INTO `settings` VALUES ('about_text','Depuis 2018, l\'atelier Soubla Salam Couture accompagne les familles de Dakar pour toutes leurs tenues d\'├®v├®nement : Korit├®, Tabaski, mariages, bapt├¬mes. Chaque confection suit un circuit ma├«tris├® : mesures, coupe, couture, essayage, livraison ÔÇö avec des photos de progression prises ├á chaque ├®tape pour un suivi serein.','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_address','Sacr├®-C┼ôur 3 n┬░5415, Dakar','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_email','contact@soublasalam.sn','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_hours','Lun ÔÇö Sam : 9 h ÔÇö 19 h\nDimanche : sur devis','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_map_url','https://maps.google.com/?q=Sacre-Coeur-3-Dakar','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_name','Soubla Salam Couture','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_phone','+221 77 555 88 90','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_slogan','L\'├®l├®gance confectionn├®e ├á la main','2026-10-21 11:16:51','2026-10-21 11:16:51'),('atelier_whatsapp','+221775558890','2026-10-21 11:16:51','2026-10-21 11:16:51'),('banner_text','Commandes Korit├® : tout d├®p├┤t avant le 15 du mois ÔÇö dans la limite de la charge atelier.','2026-10-21 11:16:51','2026-10-21 11:16:51'),('default_deposit_rate','50','2026-10-21 11:16:51','2026-10-21 11:16:51'),('facebook_url','','2026-10-21 11:16:51','2026-10-21 11:16:51'),('hero_cta','D├®couvrir le catalogue','2026-10-21 11:16:51','2026-10-21 11:16:51'),('hero_subtitle','Catalogue, commandes & suivi confie mesures ÔÇö codza lis ├á Dakar depuis 2018.','2026-10-21 11:16:51','2026-10-21 11:16:51'),('hero_title','Vos tenues de f├¬te, taill├®es sur mesure','2026-10-21 11:16:51','2026-10-21 11:16:51'),('instagram_url','','2026-10-21 11:16:51','2026-10-21 11:16:51'),('payment_om_number','78 555 88 90','2026-10-21 11:16:51','2026-10-21 11:16:51'),('payment_wave_number','77 555 88 90','2026-10-21 11:16:51','2026-10-21 11:16:51'),('receipt_footer','Merci de votre confiance ÔÇö un acompte vers├® n\'est pas remboursable si la commande est annul├®e apr├¿s la coupe.','2026-10-21 11:16:51','2026-10-21 11:16:51');
/*!40000 ALTER TABLE `settings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'client',
  `status` varchar(20) NOT NULL DEFAULT 'active',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_phone_unique` (`phone`),
  KEY `users_role_index` (`role`),
  KEY `users_status_index` (`status`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'DIOP','Aminata','admin@demo.sn','770000001','2026-10-21 11:16:51','$2y$12$Nq52al4.EvXbAO1oz2aowO1pkOfncWD7V0Z8amGwFt5ibwDw5GAxi','admin','active',NULL,NULL,'2026-10-21 11:16:51','2026-10-21 11:16:51'),(2,'Ndiaye','Moussa','tailleur@demo.sn','770000002','2026-10-21 11:16:51','$2y$12$k4bon1.J.2E4zgoNrH97vedS.zWR8goiIvWUSiwBkMZVnjH9RCe8m','tailleur','active',NULL,NULL,'2026-10-21 11:16:51','2026-10-21 11:16:51'),(3,'Sarr','Khady','tailleur2@demo.sn','770000003','2026-10-21 11:16:51','$2y$12$vtzl5lH7o.PUDeNNXpEsZOfx9KrpAJiCwuxYLvikBsYsSlQOTqzJe','tailleur','active',NULL,NULL,'2026-10-21 11:16:51','2026-10-21 11:16:51'),(4,'Ba','Fatou','client@demo.sn','770000004','2026-10-21 11:16:52','$2y$12$u0c.7pj011Yx4EnGN8oEHO.altraXJIK4pDXNpYqMsASTLYzVHip.','client','active',NULL,NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(5,'Fall','Bineta','client2@demo.sn','770000005','2026-10-21 11:16:52','$2y$12$JDazbiqNlnmp5DL5Vo/XseZ0IWmxcjaswc2.0PaQaXO45epGr9Fva','client','active',NULL,NULL,'2026-10-21 11:16:52','2026-10-21 11:16:52'),(6,'Ndiaye','Ousmane','ousmane.ndiaye@example.sn','221770909091',NULL,'$2y$12$qe3G8YkQCZ7Tm6TqoA3ec.rwyJQPuCe/RSZkujd2YsnQqfxrdMd7e','client','active',NULL,NULL,'2026-10-21 11:55:44','2026-10-21 11:55:44');
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

-- Dump completed on 2026-10-21 11:59:20
