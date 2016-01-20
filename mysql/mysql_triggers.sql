drop trigger if exists tourusers_insert;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tourusers_insert` AFTER INSERT ON `TourUsers`
 FOR EACH ROW BEGIN
    set @tc=(select count(tourID) from TourUsers where userID=NEW.userID and tourUserType='completed');
    update User set userCompletedTours=@tc where userID=NEW.userID;
    set @uc=(select count(userID) from TourUsers where tourID=NEW.tourID and tourUserType='completed');
    update Tour set completedUsersCount=@uc where tourID=NEW.tourID;
    update Tour set tourUsersApply=(select count(userID) from TourUsers  where tourID=NEW.tourID and tourUserType='apply');
    update Tour set tourAvPlaces=tourPlaces-tourUsersApply where tourID=NEW.tourID;
 END */;;
DELIMITER ;
drop trigger if exists tourusers_update;
DELIMITER ;;
/*!50003 CREATE*/ /*!50017 DEFINER=`root`@`%`*/ /*!50003 TRIGGER `tourusers_update` AFTER UPDATE ON `TourUsers`
 FOR EACH ROW BEGIN
    set @tc=(select count(tourID) from TourUsers where userID=NEW.userID and tourUserType='completed');
    update User set userCompletedTours=@tc where userID=NEW.userID;
    set @uc=(select count(userID) from TourUsers where tourID=NEW.tourID and tourUserType='completed');
    update Tour set completedUsersCount=@uc where tourID=NEW.tourID;
    update Tour set tourUsersApply=(select count(userID) from TourUsers  where tourID=NEW.tourID and tourUserType='apply');
    update Tour set tourAvPlaces=tourPlaces-tourUsersApply where tourID=NEW.tourID;
 END */;;
DELIMITER ;
