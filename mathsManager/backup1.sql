-- MariaDB dump 10.19  Distrib 10.5.21-MariaDB, for debian-linux-gnu (x86_64)
-- command : mysqldump -u root -p mathsManager > backup.sql
-- pour renvoi : mysql -u root -p mathsManager < backup.sql
-- Host: localhost    Database: mathsManager
-- ------------------------------------------------------
-- Server version	10.5.21-MariaDB-0+deb11u1

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
-- Table structure for table `chapters`
--

DROP TABLE IF EXISTS `chapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `chapters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `class_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `theme` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `chapters_class_id_foreign` (`class_id`),
  CONSTRAINT `chapters_class_id_foreign` FOREIGN KEY (`class_id`) REFERENCES `classes` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `chapters`
--

LOCK TABLES `chapters` WRITE;
/*!40000 ALTER TABLE `chapters` DISABLE KEYS */;
INSERT INTO `chapters` VALUES (1,1,'Suites','yesssss','2024-03-25 04:12:05','2024-03-25 15:59:04','#7CE6D0'),(2,1,'Trigonométrie','color changedaaaaaaaaaaaaaaaaaaaaaaa','2024-03-25 15:08:13','2024-03-25 16:03:26','#E6AA74');
/*!40000 ALTER TABLE `chapters` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `classes`
--

DROP TABLE IF EXISTS `classes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `classes` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `classes`
--

LOCK TABLES `classes` WRITE;
/*!40000 ALTER TABLE `classes` DISABLE KEYS */;
INSERT INTO `classes` VALUES (1,'Première Spé','1spe','2024-03-25 04:11:49','2024-03-25 04:11:49'),(2,'Terminale Spé','termSpe','2024-03-25 15:48:29','2024-03-25 15:54:11'),(3,'Maths Expertes','mathsExp','2024-03-25 15:54:33','2024-03-25 15:54:33'),(4,'TestsDav','tests','2024-03-25 15:54:33','2024-03-25 15:54:33');
/*!40000 ALTER TABLE `classes` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `exercises`
--

DROP TABLE IF EXISTS `exercises`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `exercises` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `subchapter_id` bigint(20) unsigned NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `statement` text NOT NULL,
  `solution` text DEFAULT NULL,
  `clue` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `exercises_subchapter_id_foreign` (`subchapter_id`),
  CONSTRAINT `exercises_subchapter_id_foreign` FOREIGN KEY (`subchapter_id`) REFERENCES `subchapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `exercises`
--

LOCK TABLES `exercises` WRITE;
/*!40000 ALTER TABLE `exercises` DISABLE KEYS */;
INSERT INTO `exercises` VALUES (1,1,'Mimipack2','<ol class=\'enumb\'> <li> <ol class=\'enumb\'> <li> Montrer que pour tout réel <span class=\'latex\'>x</span>, <div class=\'latex\'>x^2-xe+e > 0 </div>\r\n<li> On note, pour tout réel <span class=\'latex\'>x</span>, <span class=\'latex\'>P(x) =2x^2-(2e)x+e^2-2e</span>. <br>\r\nCalculer les racines de <span class=\'latex\'>P</span>, notées <span class=\'latex\'>\\alpha</span> et <span class=\'latex\'>\\beta</span>, et en déduire le signe de <span class=\'latex\'>P(x)</span> en fonction des différentes valeurs de <span class=\'latex\'>x</span>. <br>\r\n<div class=\'listpart\'>On considère alors la f onction <span class=\'latex\'>f</span> définie par <div class=\'latex\'> f(x) = 1-\\ln(x^2-xe+e)</div></div>\r\nOn note <span class=\'latex\'>\\Cf</span> la courbe représentative de <span class=\'latex\'>f</span> dans un repère orthonormé.\r\n</ol>\r\n<li> <ol class=\'enumb\'> <li> Déterminer le domaine de définition de <span class=\'latex\'>f</span>.\r\n<li> Calculer <span class=\'latex\'>f(0)</span>, <span class=\'latex\'>f(1)</span>, <span class=\'latex\'>f(e-1)</span> et <span class=\'latex\'>f(e)</span>. Vérifier que <div class=\'latex\'> f\\parenthese{\\Frac{e}{2}} = - \\ln\\parenthese{1-\\frac{e}{4}}</div>\r\n<li> Calculer <span class=\'latex\'>\\limoins f(x)</span> et <span class=\'latex\'>\\limplus f(x)</span>.\r\n<li> Calculer <span class=\'latex\'>f\'(x)</span>, puis étudier son signe. Vérifier que <span class=\'latex\'>f\'(0)=1</span> et <span class=\'latex\'>f\'(e)=-1</span>.\r\n<li> En utilisant <span class=\'latex\'>e \\approx 2,7</span>, ordonner les nombres <span class=\'latex\'>0</span>, <span class=\'latex\'>1</span>, <span class=\'latex\'>e-1</span>, <span class=\'latex\'>e</span>, <span class=\'latex\'>\\frac{e}{2}</span> puis dresser le tableau de variations de <span class=\'latex\'>f</span> en faisant figurer les valeurs étudiées en 2.b.\r\n</ol>\r\n<li> Montrer que <span class=\'latex\'>f</span> admet un maximum sur son intervalle de définition et le déterminer.\r\n<li> Déterminer l\'équation des tangentes à la courbe <span class=\'latex\'>\\Cf</span> aux points d\'abscisse <span class=\'latex\'>0</span> et <span class=\'latex\'>e</span>.\r\n</ol>','On considère la suite <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0 = 1</span> et <div class=\'latex\'> \\forall n \\in \\N, \\: u_{n+1} = u_0 + u_1 + \\hdots + u_n </div>\r\nMontrer que <div class=\'latex\'> \\forall n \\in \\N, \\: u_n \\leqslant 2^n </div>','toujours pas','2024-03-26 17:48:43','2024-03-27 00:08:03'),(12,3,NULL,'<p class=\'textit\'>Bac 2022</p> <br>\r\n<p class=\'textbf\'>Partie A</p> <br>\r\nOn considère la fonction <span class=\'latex\'>f</span> définie sur l\'intervalle <span class=\'latex\'>[1;+\\infty[</span> par <div class=\'latex\'> f(x) = \\Frac{\\ln{x}}{x} </div> \r\n<ol class=\'enumb\'> <li> Donner la limite de la fonction <span class=\'latex\'>f</span> en <span class=\'latex\'>+\\infty</span>.\r\n<li> On admet que <span class=\'latex\'>f</span> est dérivable sur <span class=\'latex\'>[1;+\\infty[</span>.\r\n<ol class=\'enumb\'> <li> Montrer que pour tout réel <span class=\'latex\'>x \\geqslant 1</span>, <span class=\'latex\'>f\'(x) = \\Frac{1-\\ln{x}}{x^2}</span>. \r\n<li> Déterminer le tableau de signes de <span class=\'latex\'>f\'(x)</span> suivant les valeurs de <span class=\'latex\'>x</span>.\r\n<li> Dresser le tableau de variations complet de la fonction <span class=\'latex\'>f</span>. \r\n</ol>\r\n<li> Soit <span class=\'latex\'>k</span> un nombre réel positif ou nul. \r\n<ol class=\'enumb\'> <li> Montrer que si <span class=\'latex\'>0 \\leqslant k \\leqslant \\Frac{1}{e}</span>, l\'équation <span class=\'latex\'>f(x) = k</span> admet une unique sur l\'intervalle <span class=\'latex\'>[1;e]</span>. \r\n<li> Si <span class=\'latex\'>k > \\Frac{1}{e}</span>, l\'équation <span class=\'latex\'>f(x)=k</span> admet-elle des solutions sur l\'intervalle <span class=\'latex\'>[1;+\\infty[</span> ? <br>\r\nJustifier.\r\n</ol>\r\n</ol>\r\n<p class=\'textbf\'>Partie B</p> <br>\r\nSoit <span class=\'latex\'>g</span> la fonction définie sur <span class=\'latex\'>\\R</span> par <div class=\'latex\'> g(x) = e^{\\tfrac{x}{4}} </div>\r\nOn considère la suite <span class=\'latex\'>\\un</span> définie par <span class=\'latex\'>u_0 = 1</span> et pour tout entier naturel <span class=\'latex\'>n</span> <div class=\'latex\'> u_{n+1} = g(u_n) = e^{ \\tfrac{u_n}{4} } </div> \r\n<ol class=\'enumb\'> <li> Justifier que <span class=\'latex\'>g</span> est croissante sur <span class=\'latex\'>\\R</span>.\r\n<li> Montrer par récurrence que, pour tout entier naturel <span class=\'latex\'>n</span>, on a : <span class=\'latex\'>u_n \\leqslant u_{n+1} \\leqslant e</span>.\r\n<li> En déduire que <span class=\'latex\'>\\un</span> est convergente.\r\n\\listpart{On note <span class=\'latex\'>\\ell</span> la limite de <span class=\'latex\'>\\un</span> et on admet que <span class=\'latex\'>\\ell</span> est solution de l\'équation <span class=\'latex\'>e^{ \\tfrac{x}{4}} = x</span>.}\r\n<li> En déduire que <span class=\'latex\'>\\ell</span> est solution de l\'équation <span class=\'latex\'>f(x) = \\Frac{1}{4}</span>, où <span class=\'latex\'>f</span> est la fonction étudiée dans la <p class=\'textbf\'>Partie A</p>.\r\n<li> Donner une valeur approchée à <span class=\'latex\'>10^{-2}</span> près la limite <span class=\'latex\'>\\ell</span> de la suite <span class=\'latex\'>\\un</span>. \r\n</ol>','',NULL,'2024-03-26 21:21:40','2024-03-26 21:23:51'),(15,1,NULL,'<div class=\'latex-center\'>\r\n<span class=\'latex\'>u_0 = \\Frac{1}{q_0} <br> u_1 = \\Frac{1}{q_0}+\\Frac{1}{q_0q_1} <br> \\hdots = \\hdots <br>u_n = \\Frac{1}{q_0}+  \\Frac{1}{q_0q_1} +  \\hdots + \\Frac{1}{q_0q_1\\hdots q_n}</span>\r\n</div>','',NULL,'2024-03-26 21:52:26','2024-03-27 00:34:35'),(16,1,NULL,'<ol class=\'enumb\'> <li> Dadougl fils de pute \r\n<ol class=\'enumb\'> <li> <span class=\'latex\'>f(x) = 2</span> \r\n<li> Ta mère la pute \r\n</ol>\r\n</ol>\r\nDu coup la c\'est en gras ou pas ?','',NULL,'2024-03-26 22:17:54','2024-03-27 00:18:37'),(19,1,NULL,'<p class=\'textbf\'>Exercice 2</p> <span class=\'latex\'>(*)</span>. Soit  <span class=\'latex\'>A  = <div class=\'latex-pmatrix\'> 1 & 2 & 3 <br> 4 &  5 & 6 <br> 7 & 8 & 9</div> </span>.\r\n<ol>\r\n<li> Que vaut <span class=\'latex\'>a_{1,3}</span> ? <span class=\'latex\'>a_{3,1}</span> ? \r\n<li> Calculer <span class=\'latex\'>\\displaystyle \\sum_{j=1}^{3} a_{j,j}</span>, <span class=\'latex\'>\\displaystyle \\sum_{j=1}^{3} a_{2,j}</span>, <span class=\'latex\'>\\displaystyle \\sum_{j=1}^{3} a_{4-j,j}</span>\r\n</ol>','',NULL,'2024-03-27 00:39:58','2024-03-27 00:44:34'),(20,1,NULL,'<span class=\'latex\'>x^{x} = (\\sqrt{x})^{\\sqrt{x}}</span>','',NULL,'2024-03-27 00:42:35','2024-03-27 00:50:08');
/*!40000 ALTER TABLE `exercises` ENABLE KEYS */;
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
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'2024_03_14_180715_create_classes_table',1),(4,'2024_03_14_180723_create_chapters_table',1),(5,'2024_03_14_180731_create_subchapters_table',1),(6,'2024_03_15_223220_add_role_to_users_table',1),(7,'2024_03_15_223310_add_role_to_users_table',1),(8,'2024_03_20_034838_create_exercises_table',1),(9,'2024_03_25_154641_add_theme_to_chapitres_table',2);
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
INSERT INTO `sessions` VALUES ('c95KdfTzlEF8QVCibu5MHoeeGiea02RiJGufwEZb',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1 OPX/2.3.3','YTozOntzOjY6Il90b2tlbiI7czo0MDoiaHdnRDdMRG1HRmpGYTJRU2hqNGVtMXVkeGFvam40RmNrNFJjUVZMdSI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NzY6Imh0dHA6Ly9jMDQwLTJhMDEtZTBhLTJlMy1mZDYwLTQ4YWMtN2MwZS02OTI0LWQ0MTMubmdyb2stZnJlZS5hcHAvY2xhc3NlLzFzcGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19',1712256107),('EgiqHlHcHLABIAOeTpuOmYABTTjtt4apgifCWxqz',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 16_3 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) GSA/309.1.619045077 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoiQlBjMWhWM1lhRUJyOW5wTXhlY3huQTlxSURwT1RRUXpQTHNmSTlOayI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9jMDQwLTJhMDEtZTBhLTJlMy1mZDYwLTQ4YWMtN2MwZS02OTI0LWQ0MTMubmdyb2stZnJlZS5hcHAvaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1712255669),('g4LZDa8pO5lUV5blp9TPo0qtYSVhqLfSUh4OSZqK',19,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiT2NxWGxaWGVJWEduRmRoOGpoT0FYQU5lNldCQjNpUXljaTZEbWVCaiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Q6ODAwMC9jbGFzc2UvMXNwZSI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE5O30=',1712257271),('J0eTgMCRjnVsGZizzUoiWyIAKxphN7RBzadxLmGA',NULL,'127.0.0.1','Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/123.0.0.0 Mobile Safari/537.36','YTozOntzOjY6Il90b2tlbiI7czo0MDoiTFNVYWk4UXBIQjRFRnRXOG5WemRkUkJPSE94emxSUHgwNjRaQjNaMCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Njk6Imh0dHA6Ly9jMDQwLTJhMDEtZTBhLTJlMy1mZDYwLTQ4YWMtN2MwZS02OTI0LWQ0MTMubmdyb2stZnJlZS5hcHAvaG9tZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=',1712256038),('VeJKkRaHXhEUVcthuBx5Qcyqnxdrcbgu1ZJxQGxe',NULL,'127.0.0.1','Mozilla/5.0 (iPhone; CPU iPhone OS 16_3_1 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.3 Mobile/15E148 Safari/604.1','YTozOntzOjY6Il90b2tlbiI7czo0MDoidldPNE9raU5QaTJJYVdrMUo5MW0yTjJsZFRmTGxoUXNNMnZLOEZJVCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Nzc6Imh0dHA6Ly9jMDQwLTJhMDEtZTBhLTJlMy1mZDYwLTQ4YWMtN2MwZS02OTI0LWQ0MTMubmdyb2stZnJlZS5hcHAvc3ViY2hhcHRlci8xIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1712255811);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `subchapters`
--

DROP TABLE IF EXISTS `subchapters`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `subchapters` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `chapter_id` bigint(20) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `subchapters_chapter_id_foreign` (`chapter_id`),
  CONSTRAINT `subchapters_chapter_id_foreign` FOREIGN KEY (`chapter_id`) REFERENCES `chapters` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `subchapters`
--

LOCK TABLES `subchapters` WRITE;
/*!40000 ALTER TABLE `subchapters` DISABLE KEYS */;
INSERT INTO `subchapters` VALUES (1,1,'Suites en d ultime','bien kek les suites sah','2024-03-25 04:12:25','2024-03-25 04:12:25'),(2,2,'trigo1','oui oui baguette','2024-03-25 16:02:09','2024-03-25 16:02:09'),(3,1,'ta grand mère',NULL,'2024-03-25 16:04:59','2024-03-25 16:04:59'),(4,1,'caca','pipi','2024-04-04 17:01:11','2024-04-04 17:01:11');
/*!40000 ALTER TABLE `subchapters` ENABLE KEYS */;
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
  `role` varchar(255) NOT NULL DEFAULT 'student',
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `provider` varchar(255) DEFAULT NULL,
  `provider_id` varchar(255) DEFAULT NULL,
  `provider_token` varchar(255) DEFAULT NULL,
  `avatar` varchar(255) NOT NULL DEFAULT 'default.jpg',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (19,'DavidMgr_','admin','davidmgr93@gmail.com',NULL,NULL,'google','111164376983001905055','ya29.a0Ad52N3-7DQzhovlzvmcFqh_k6CmLkwK4hvvaJWkJNylcQb_3fdw-PuOCo8dwGdr4Iuyw6UE0Rx6sN6GJX5d1hyJLUKKhPwlZLyJ8bF-Kg_8BreNS7OZuN5NQFbVrB3fK9gFxoRSgVzz94bSPDcxOAzh1doAGgP4rOQ5xaCgYKAaMSARESFQHGX2Mis6KKv14amXKxXWBNX4bO0w0171','https://lh3.googleusercontent.com/a/ACg8ocIqMvg-Qi3cplD8_N0zR6eR5NelJ6DvVuE7Upbm9ho5i6c=s96-c',NULL,'2024-03-25 02:40:16','2024-03-25 02:40:16'),(26,'dadou','student','david@gmail.com',NULL,'$2y$12$kpEuDza8AyrUMWGN/2eU7ejENl3SVl4UxRzubtXjn72uEB/VeKL9O',NULL,NULL,NULL,'david@gmail.com-cyberpunk.jpg',NULL,'2024-03-25 03:26:58','2024-03-25 18:03:31'),(27,'mimigl','admin','mimigl@gmail.com',NULL,'$2y$12$EqkTRuxVbD4wAg1GsantcuX0Av8Zjse4DHQswYf78nnqHgRGd.sNm',NULL,NULL,NULL,'mimigl@gmail.com-tr-mikey-moto-min.jpeg',NULL,'2024-03-25 13:44:46','2024-03-25 14:06:08'),(29,'David Meguira','student','davidmeguira6@gmail.com',NULL,NULL,'google','111520259489012703862','ya29.a0Ad52N3_tHAedec3E2LruJ2vwWJ_nXK_-GPvslFfQYywme6pjdM2dAPgx-9GJHdBXxbKZ0kS93pHPPga697uypEEFHNPXZpYD1iY_ZkzgpB_pmsuSLR4M4bQo7ohdZYfGJ-pydPoUcn1xK4DtXLEvZn_Zk3oVejsKr7rQaCgYKAf0SARISFQHGX2Mi7we75-8aazpeRAY4OJ3Sbw0171','default.jpg',NULL,'2024-03-26 17:39:47','2024-03-26 19:51:28');
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

-- Dump completed on 2024-04-04 21:05:15
