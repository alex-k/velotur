<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Tour participant.
 * Участник тура.
 * @ORM\Entity
 * @ORM\Table(name="TourParticipant")
 */
class TourParticipant
{

  /**
   * Id of the tour participant.
   * Id участника тура.
   * @ORM\Id
   * @ORM\Column(type="integer")
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  private $participantId;

  /**
   * Id of the user who added the participant.
   * Id пользователя который добавил участника.
   * @ORM\Column(type="integer")
   */
  private $creatorUserId;

  /**
   * @ORM\Column(type="integer")
   */
  private $tourId;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantEmail;

  /**
   * @ORM\Column(type="text")
   */  
  private $participantRussianName;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantLatinName;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantBirthday;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantCitizenship;

  /**
   * @ORM\Column(type="string")
   * note: maps to mysql enum('Male', 'Female')
   */  
  private $participantSex;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantCountry;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantCity;

  /**
   * @ORM\Column(type="text")
   */  
  private $participantAddress;

  /**
   * @ORM\Column(type="text")
   */  
  private $participantJob;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantPassportType;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantPassport;

  /**
   * @ORM\Column(type="text")
   */
  private $participantPassportIssuedBy;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantPassportIssuedDate;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantPassportValidThrough;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantPhone;

  /**
   * @ORM\Column(type="string")
   */
  private $participantVPNumber;

  /**
   * @ORM\Column(type="string")
   * note: maps to mysql enum('regular', 'guard', 'block')
   */
  private $participantType;

  /**
   * @ORM\Column(type="integer")
   */ 
  private $participantReferalID;

  /**
   * @ORM\Column(type="datetime")
   */ 
  private $participantRegistrationDate;

  /**
   * @ORM\Column(type="string")
   */  
  private $participantInfoHowFound;
  
  /**
   * @ORM\Column(type="integer")
   */ 
  private $participantPartnerID;
 
  /**
   * @ORM\Column(type="integer")
   */ 
  private $participantCompletedTours;
 
  /**
   * @ORM\Column(type="integer")
   */ 
  private $participantSubscribeNews;

  /* ==============================================================================================
  *  GETTERS
  *  ============================================================================================*/
  
  public function getParticipantId() {
    return $this->participantId;
  }
  public function getCreatorUserId() {
    return $this->addedByUserId();
  }
  public function getTourId() {
    return $this->tourId;
  }
  public function getParticipantEmail() {
    return $this->participantEmail;
  }
  public function getParticipantRussianName() {
    return $this->participantRussianName;
  }
  public function getParticipantLatinName() {
    return $this->participantLatinName;
  }
  public function getParticipantBirthday() {
    return $this->participantBirthday;
  }
  public function getParticipantCitizenship() {
    return $this->participantCitizenship;
  }
  public function getParticipantSex() {
    return $this->participantSex;
  }
  public function getParticipantCountry() {
    return $this->participantCountry;
  }
  public function getParticipantCity() {
    return $this->participantCity;
  }
  public function getParticipantAddress() {
    return $this->participantAddress;
  }
  public function getParticipantJob() {
    return $this->participantJob;
  }
  public function getParticipantPassportType() {
    return $this->participantPassportType;
  }
  public function getParticipantPassport() {
    return $this->participantPassport;
  }
  public function getParticipantPassportIssuedBy() {
    return $this->participantIssuedBy;
  }
  public function getParticipantPassportIssuedDate() {
    return $this->participantIssuedDate;
  }
  public function getParticipantPassportValidThrough() {
    return $this->participantValidThrough;
  }
  public function getParticipantPhone() {
    return $this->participantPhone;
  }
  public function getParticipantVPNumber() {
    return $this->participantVPNumber;
  }
  public function getParticipantType() {
    return $this->participantType;
  }
  public function getParticipantReferalID() {
    return $this->participantReferalID;
  }
  public function getParticipantRegistrationDate() {
    return $this->participantRegistrationDate;
  }
  public function getParticipantInfoHowFound() {
    return $this->participantInfoHowFound;
  }
  public function getParticipantPartnerID() {
    return $this->participantPartnerID;
  }
  public function getParticipantCompletedTours() {
    return $this->participantCompletedTours;
  }
  public function getParticipantSubscribeNews() {
    return $this->participantSubscribeNews;
  }

  /* ==============================================================================================
  *  SETTERS
  *  ============================================================================================*/
  
  public function setParticipantId($participantId) {
    $this->participantId = $participantId;
  }
  public function setCreatorUserId($creatorUserId) {
    $this->creatorUserId = $creatorUserId;
  }
  public function setTourId($tourId) {
    $this->tourId = $tourId;
  }
  public function setParticipantEmail($participantEmail) {
    $this->participantEmail = $participantEmail;
  }
  public function getParticipantEmail($participantEmail) {
    $this->participantEmail = $participantEmail;
  }
  public function getParticipantRussianName($participantRussianName) {
    $this->participantRussianName = $participantRussianName;
  }
  public function getParticipantLatinName($participantLatinName) {
    $this->participantLatinName = $participantLatinName;
  }
  public function getParticipantBirthday($participantBirthday) {
    $this->participantBirthday = $participantBirthday;
  }
  public function getParticipantCitizenship($participantCitizenship) {
    $this->participantCitizenship = $participantCitizenship;
  }
  public function getParticipantSex($participantSex) {
    $this->participantSex = $participantSex;
  }
  public function getParticipantCountry($participantCountry) {
    $this->participantCountry = $participantCountry;
  }
  public function getParticipantCity($participantCity) {
    $this->participantCity = $participantCity;
  }
  public function getParticipantAddress($participantAddress) {
    $this->participantAddress = $participantAddress;
  }
  public function getParticipantJob($participantJob) {
    $this->participantJob = $participantJob;
  }
  public function getParticipantPassportType($participantPassportType) {
    $this->participantPassportType = $participantPassportType;
  }
  public function getParticipantPassport($participantPassport) {
    $this->participantPassport = $participantPassport;
  }
  public function getParticipantPassportIssuedBy($participantIssuedBy) {
    $this->participantIssuedBy = $participantIssuedBy;
  }
  public function getParticipantPassportIssuedDate($participantIssuedDate) {
    $this->participantIssuedDate = $participantIssuedDate;
  }
  public function getParticipantPassportValidThrough($participantValidThrough) {
    $this->participantValidThrough = $participantValidThrough;
  }
  public function getParticipantPhone($participantPhone) {
    $this->participantPhone = $participantPhone;
  }
  public function getParticipantVPNumber($participantVPNumber) {
    $this->participantVPNumber = $participantVPNumber;
  }
  public function getParticipantType($participantType) {
    $this->participantType = $participantType;
  }
  public function getParticipantReferalID($participantReferalID) {
    $this->participantReferalID = $participantReferalID;
  }
  public function getParticipantRegistrationDate($participantRegistrationDate) {
    $this->participantRegistrationDate = $participantRegistrationDate;
  }
  public function getParticipantInfoHowFound($participantInfoHowFound) {
    $this->participantInfoHowFound = $participantInfoHowFound;
  }
  public function getParticipantPartnerID($participantPartnerID) {
    $this->participantPartnerID = $participantPartnerID;
  }
  public function getParticipantCompletedTours($participantCompletedTours) {
    $this->participantCompletedTours = $participantCompletedTours;
  }
  public function getParticipantSubscribeNews($participantSubscribeNews) {
    $this->participantSubscribeNews = $participantSubscribeNews;
  }
}