<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 * @ORM\Entity
 * @ORM\Table(name="User")
 */
class User {
  
  /**
   * ORM\Id
   * ORM\Column(type="integer")
   * ORM\GeneratedValue(strategy="AUTO")
   */
  private $userID;

  /**
   * ORM\Column(type="text")
   */
  private $userEmail;
  
  /**
   * ORM\Column(type="text")
   */
  private $userName;

  /**
   * ORM\Column(type="text")
   */
  private $userPassword;

  /**
   * ORM\Column(type="text")
   */
  private $userRussianName;
  
  /**
   * ORM\Column(type="text")
   */
  private $userRussianName1;
  
  /**
   * ORM\Column(type="text")
   */
  private $userRussianName2;
  
  /**
   * ORM\Column(type="text")
   */
  private $userRussianName3;
  
  /**
   * ORM\Column(type="text")
   */
  private $userLatinName;
  
  /**
   * ORM\Column(type="text")
   */
  private $userLatinName1;
  
  /**
   * ORM\Column(type="text")
   */
  private $userLatinName2;
  
  /**
   * ORM\Column(type="text")
   */
  private $userLatinName3;
  
  /**
   * ORM\Column(type="text")
   */
  private $userBirthDay;
  
  /**
   * ORM\Column(type="text")
   */
  private $userCitizenship;
  
  /**
   * ORM\Column(type="text")
   */
  private $userSex;
  
  /**
   * ORM\Column(type="text")
   */
  private $userCountry;
  
  /**
   * ORM\Column(type="text")
   */
  private $userCity;
  
  /**
   * ORM\Column(type="text")
   */
  private $userAddress;
  
  /**
   * ORM\Column(type="text")
   */
  private $userJob;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPassportType;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPassport;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPassportIssuedBy;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPassportIssuedDate;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPassportValidThrow;
  
  /**
   * ORM\Column(type="text")
   */
  private $userPhone;

  /**
   * ORM\Column(type="integer")
   */
  private $userVPNumber;
  
  /**
   * ORM\Column(type="text")
   */
  private $userType;

  /**
   * ORM\Column(type="integer")
   */
  private $userReferalID;

  /**
   * ORM\Column(type="date")
   */
  private $userRegistrationDate;
  
  /**
   * ORM\Column(type="text")
   */
  private $userInfoHowFound;

  /**
   * ORM\Column(type="integer")
   */
  private $userPartnerID;

  /**
   * ORM\Column(type="integer")
   */
  private $userCompletedTours;

  /**
   * ORM\Column(type="integer")
   */
  private $userSubscribeNews;
  
   /* ==============================================================================================
  *  GETTERS
  *  ============================================================================================*/
  
  public function getUserID() {
    return $this->userID;
  }
  private function getUserEmail() {
    return $this->userEmail;
  }
  public function getUserName() {
    return $this->userName; 
  }
  public function getUserPassword() {
    return $this->userPassword; 
  }
  public function getUserRussianName() {
    return $this->userRussianName; 
  }
  public function getUserRussianName1() {
    return $this->userRussianName1; 
  }
  public function getUserRussianName2() {
    return $this->userRussianName2; 
  }
  public function getUserRussianName3() {
    return $this->userRussianName3; 
  }
  public function getUserLatinName() {
    return $this->userLatinName; 
  }
  public function getUserLatinName1() {
    return $this->userLatinName1; 
  }
  public function getUserLatinName2() {
    return $this->userLatinName2; 
  }
  public function getUserLatinName3() {
    return $this->userLatinName3; 
  }
  public function getUserBirthDay() {
    return $this->userBirthDay; 
  }
  public function getUserCitizenship() {
    return $this->userCitizenship; 
  }
  public function getUserSex() {
    return $this->userSex; 
  }
  public function getUserCountry() {
    return $this->userCountry; 
  }
  public function getUserCity() {
    return $this->userCity; 
  }
  public function getUserAddress() {
    return $this->userAddress; 
  }
  public function getUserJob() {
    return $this->userJob; 
  }
  public function getUserPassportType() {
    return $this->userPassportType; 
  }
  public function getUserPassport() {
    return $this->userPassport; 
  }
  public function getUserPassportIssuedBy() {
    return $this->userPassportIssuedBy; 
  }
  public function getUserPassportIssuedDate() {
    return $this->userPassportIssuedDate; 
  }
  public function getUserPassportValidThrow() {
    return $this->userPassportValidThrow; 
  }
  public function getUserPhone() {
    return $this->userPhone; 
  }
  public function getUserVPNumber() {
    return $this->userVPNumber; 
  }
  public function getUserType() {
    return $this->userType; 
  }
  public function getUserReferalID() {
    return $this->userReferalID; 
  }
  public function getUserRegistrationDate() {
    return $this->userRegistrationDate; 
  }
  public function getUserInfoHowFound() {
    return $this->userInfoHowFound; 
  }
  public function getUserPartnerID() {
    return $this->userPartnerID; 
  }
  public function getUserCompletedTours() {
    return $this->userCompletedTours; 
  }
  public function getUserSubscribeNews() {
    return $this->userSubscribeNews; 
  }

  /* ==============================================================================================
  *  SETTERS
  *  ============================================================================================*/
  public function setUserId($userID) {
    $this->userID = $userID;
  }
  public function setUserEmail($userEmail) {
    $this->userEmail = $userEmail;
  }
  public function setUserName($userName) {
    $this->userName = $userName; 
  }
  public function setUserPassword($userPassword) {
    $this->userPassword = $userPassword; 
  }
  public function setUserRussianName($userRussianName) {
    $this->userRussianName = $userRussianName; 
  }
  public function setUserRussianName1($userRussianName1) {
    $this->userRussianName1 = $userRussianName1; 
  }
  public function setUserRussianName2($userRussianName2) {
    $this->userRussianName2 = $userRussianName2; 
  }
  public function setUserRussianName3($userRussianName3) {
    $this->userRussianName3 = $userRussianName3; 
  }
  public function userLatinName($userLatinName) {
   $this->userLatinName = $userLatinName;
  }
  public function setUserLatinName1($userLatinName1) {
    $this->userLatinName1 = $userLatinName1; 
  }
  public function setUserLatinName2($userLatinName2) {
    $this->userLatinName2 = $userLatinName2; 
  }
  public function setUserLatinName3($userLatinName3) {
    $this->userLatinName3 = $userLatinName3; 
  }
  public function setUserBirthDay($userBirthDay) {
    $this->userBirthDay = $userBirthDay; 
  }
  public function setUserCitizenship($userCitizenship) {
    $this->userCitizenship = $userCitizenship; 
  }
  public function setUserSex($userSex) {
    $this->userSex = $userSex; 
  }
  public function setUserCountry($userCountry) {
    $this->userCountry = $userCountry; 
  }
  public function setUserCity($userCity) {
    $this->userCity = $userCity; 
  }
  public function setUserAddress($userAddress) {
    $this->userAddress = $userAddress; 
  }
  public function setUserJob($userJob) {
    $this->userJob = $userJob; 
  }
  public function setUserPassportType($userPassportType) {
    $this->userPassportType = $userPassportType; 
  }
  public function setUserPassport($userPassport) {
    $this->userPassport = $userPassport; 
  }
  public function setUserPassportIssuedBy($userPassportIssuedBy) {
    $this->userPassportIssuedBy = $userPassportIssuedBy; 
  }
  public function setUserPassportIssuedDate($userPassportIssuedDate) {
    $this->userPassportIssuedDate = $userPassportIssuedDate; 
  }
  public function setUserPassportValidThrow($userPassportValidThrow) {
    $this->userPassportValidThrow = $userPassportValidThrow; 
  }
  public function setUserPhone($userPhone) {
    $this->userPhone = $userPhone; 
  }
  public function setUserVPNumber($userVPNumber) {
    $this->userVPNumber = $userVPNumber; 
  }
  public function setUserType($userType) {
    $this->userType = $userType; 
  }
  public function setUserReferalID($userReferalID) {
    $this->userReferalID = $userReferalID; 
  }
  public function setUserRegistrationDate($userRegistrationDate) {
    $this->userRegistrationDate = $userRegistrationDate; 
  }
  public function setUserInfoHowFound($userInfoHowFound) {
    $this->userInfoHowFound = $userInfoHowFound; 
  }
  public function setUserPartnerID($userPartnerID) {
    $this->userPartnerID = $userPartnerID; 
  }
  public function setUserCompletedTours($userCompletedTours) {
    $this->userCompletedTours = $userCompletedTours; 
  }
  public function setUserSubscribeNews($userSubscribeNews) {
    $this->userSubscribeNews = $userSubscribeNews; 
  }
}