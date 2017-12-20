-- MySQL dump 10.13  Distrib 5.7.20, for Linux (x86_64)
--
-- Host: localhost    Database: velotur
-- ------------------------------------------------------
-- Server version	5.7.20-0ubuntu0.16.04.1

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `TourParticipant`
--

DROP TABLE IF EXISTS `TourParticipant`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `TourParticipant` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `creator_user_id` int(11) DEFAULT NULL,
  `tour_id` int(11) DEFAULT NULL,
  `russian_first_name` longtext COLLATE utf8_unicode_ci,
  `russian_middle_name` longtext COLLATE utf8_unicode_ci,
  `russian_last_name` longtext COLLATE utf8_unicode_ci,
  `latin_first_name` longtext COLLATE utf8_unicode_ci,
  `latin_last_name` longtext COLLATE utf8_unicode_ci,
  `birthday` longtext COLLATE utf8_unicode_ci,
  `citizenship` longtext COLLATE utf8_unicode_ci,
  `sex` longtext COLLATE utf8_unicode_ci,
  `city` longtext COLLATE utf8_unicode_ci,
  `passport_number` longtext COLLATE utf8_unicode_ci,
  `passport_issued_by` longtext COLLATE utf8_unicode_ci,
  `passport_issued_date` longtext COLLATE utf8_unicode_ci,
  `passport_valid_through` longtext COLLATE utf8_unicode_ci,
  `phone` longtext COLLATE utf8_unicode_ci,
  `vp_number` longtext COLLATE utf8_unicode_ci,
  `registration_date` longtext COLLATE utf8_unicode_ci,
  `how_found` longtext COLLATE utf8_unicode_ci,
  `comments` longtext COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TourParticipant`
--

LOCK TABLES `TourParticipant` WRITE;
/*!40000 ALTER TABLE `TourParticipant` DISABLE KEYS */;
INSERT INTO `TourParticipant` VALUES (1,0,0,'Иван','Иванович','Пупкин','IVAN','PUPKIN','31-12-1981','Россия','null','Москва','12 14105010','РУВД 123','31-12-1981','31-12-1981','12121314214','2112412414','2017-12-20','Пришел по линкам с других сайтов','Наши пожелания');
/*!40000 ALTER TABLE `TourParticipant` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `User`
--

DROP TABLE IF EXISTS `User`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `User` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_email` longtext COLLATE utf8_unicode_ci,
  `user_name` longtext COLLATE utf8_unicode_ci,
  `user_password` longtext COLLATE utf8_unicode_ci,
  `user_russian_name` longtext COLLATE utf8_unicode_ci,
  `user_russian_name1` longtext COLLATE utf8_unicode_ci,
  `user_russian_name2` longtext COLLATE utf8_unicode_ci,
  `user_russian_name3` longtext COLLATE utf8_unicode_ci,
  `user_latin_name` longtext COLLATE utf8_unicode_ci,
  `user_latin_name1` longtext COLLATE utf8_unicode_ci,
  `user_latin_name2` longtext COLLATE utf8_unicode_ci,
  `user_latin_name3` longtext COLLATE utf8_unicode_ci,
  `user_birth_day` longtext COLLATE utf8_unicode_ci,
  `user_citizenship` longtext COLLATE utf8_unicode_ci,
  `user_sex` longtext COLLATE utf8_unicode_ci,
  `user_country` longtext COLLATE utf8_unicode_ci,
  `user_city` longtext COLLATE utf8_unicode_ci,
  `user_address` longtext COLLATE utf8_unicode_ci,
  `user_job` longtext COLLATE utf8_unicode_ci,
  `user_passport_type` longtext COLLATE utf8_unicode_ci,
  `user_passport` longtext COLLATE utf8_unicode_ci,
  `user_passport_issued_by` longtext COLLATE utf8_unicode_ci,
  `user_passport_issued_date` longtext COLLATE utf8_unicode_ci,
  `user_passport_valid_throw` longtext COLLATE utf8_unicode_ci,
  `user_phone` longtext COLLATE utf8_unicode_ci,
  `user_vpnumber` int(11) DEFAULT NULL,
  `user_type` longtext COLLATE utf8_unicode_ci,
  `user_referal_id` int(11) DEFAULT NULL,
  `user_registration_date` date DEFAULT NULL,
  `user_info_how_found` longtext COLLATE utf8_unicode_ci,
  `user_partner_id` int(11) DEFAULT NULL,
  `user_completed_tours` int(11) DEFAULT NULL,
  `user_subscribe_news` int(11) DEFAULT NULL,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `User`
--

LOCK TABLES `User` WRITE;
/*!40000 ALTER TABLE `User` DISABLE KEYS */;
INSERT INTO `User` VALUES (2,'pupkina@mail.ru','Мария Петровна Пупкина','asdf','Мария Петровна Пупкина','Мария','Петровна','Пупкина',NULL,'MARIA','PUPKINA',NULL,'31-12-1977','Россия','femail',NULL,'Санкт-Петербург',NULL,NULL,NULL,'70 123115','РУВД 124','31-12-1981','12-12-2012','1 1235851801805',12341423,NULL,NULL,'2017-12-20','Друзья рассказали',NULL,NULL,NULL);
/*!40000 ALTER TABLE `User` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-12-20 10:57:11
