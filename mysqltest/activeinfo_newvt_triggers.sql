-- MySQL dump 10.11
--
-- Host: velotur.ru    Database: activeinfo_newvt
-- ------------------------------------------------------
-- Server version	5.5.43-0ubuntu0.12.04.1
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

/*!50003 SET @SAVE_SQL_MODE=@@SQL_MODE*/;

DELIMITER ;;
/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`%` */ /*!50003 TRIGGER `tourusers_insert` AFTER INSERT ON `TourUsers` FOR EACH ROW BEGIN
    call tourusers_counters(NEW.userID,NEW.tourID);
 END */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`%` */ /*!50003 TRIGGER `tourusers_update` AFTER UPDATE ON `TourUsers` FOR EACH ROW BEGIN
    call tourusers_counters(OLD.userID,OLD.tourID);
    call tourusers_counters(NEW.userID,NEW.tourID);
 END */;;

/*!50003 SET SESSION SQL_MODE="" */;;
/*!50003 CREATE */ /*!50017 DEFINER=`root`@`%` */ /*!50003 TRIGGER `tourusers_delete` AFTER DELETE ON `TourUsers` FOR EACH ROW BEGIN
    call tourusers_counters(OLD.userID,OLD.tourID);
 END */;;

DELIMITER ;
/*!50003 SET SESSION SQL_MODE=@SAVE_SQL_MODE*/;

--
-- Dumping routines for database 'activeinfo_newvt'
--
DELIMITER ;;
/*!50003 SET SESSION SQL_MODE=""*/;;
/*!50003 CREATE*/ /*!50020 DEFINER=`root`@`localhost`*/ /*!50003 PROCEDURE `tourusers_counters`(IN _userID INT, IN _tourID INT)
BEGIN
	update User set userCompletedTours=(select count(tourID) from TourUsers where userID=_userID and tourUserType='completed') where userID=_userID;

	update Tour set completedUsersCount=(select count(userID) from TourUsers where tourID=_tourID and tourUserType='completed') where tourID=_tourID;
	update Tour set tourUsersApply=(select count(userID) from TourUsers  where tourID=_tourID and tourUserType='apply') where tourID=_tourID;
    update Tour set tourAvPlaces=tourPlaces-tourUsersApply where tourID=_tourID;
 END */;;
/*!50003 SET SESSION SQL_MODE=@OLD_SQL_MODE*/;;
DELIMITER ;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2016-02-20 15:31:47
