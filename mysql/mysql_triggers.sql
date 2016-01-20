use activeinfo_newvt;

drop procedure if exists tourusers_counters;
DELIMITER ;;
  CREATE PROCEDURE `tourusers_counters` (IN _userID INT, IN _tourID INT)
  BEGIN
    update User set userCompletedTours=(select count(tourID) from TourUsers where userID=_userID and tourUserType='completed') where userID=_userID;

    update Tour set completedUsersCount=(select count(userID) from TourUsers where tourID=_tourID and tourUserType='completed') where tourID=_tourID;
    update Tour set tourUsersApply=(select count(userID) from TourUsers  where tourID=_tourID and tourUserType='apply') where tourID=_tourID;
    update Tour set tourAvPlaces=tourPlaces-tourUsersApply where tourID=_tourID;
 END;;
DELIMITER ;

drop trigger if exists tourusers_insert;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tourusers_insert` AFTER INSERT ON `TourUsers`
 FOR EACH ROW BEGIN
    call tourusers_counters(NEW.userID,NEW.tourID);
 END */;;
DELIMITER ;
drop trigger if exists tourusers_update;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tourusers_update` AFTER UPDATE ON `TourUsers`
 FOR EACH ROW BEGIN
    call tourusers_counters(OLD.userID,OLD.tourID);
    call tourusers_counters(NEW.userID,NEW.tourID);
 END */;;
DELIMITER ;

drop trigger if exists tourusers_delete;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tourusers_delete` AFTER DELETE ON `TourUsers`
 FOR EACH ROW BEGIN
    call tourusers_counters(OLD.userID,OLD.tourID);
 END */;;
DELIMITER ;


update User set userCompletedTours=(select count(tourID) from TourUsers where tourUserType='completed' and userID=User.userID);
update Tour set completedUsersCount=(select count(userID) from  TourUsers where tourUserType='completed' and tourID=Tour.tourID);
update Tour set tourUsersApply=(select count(userID) from  TourUsers where tourUserType='apply' and tourID=Tour.tourID);
update Tour set tourUsersWL=(select count(userID) from  TourUsers where tourUserType='WL' and tourID=Tour.tourID);
update Tour set tourAvPlaces=tourPlaces-tourUsersApply;


