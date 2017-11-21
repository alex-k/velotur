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
  *  GETTERS AND SETTERS
  *  ============================================================================================*/
  
  public function getParticipantId() {
    return $this->participantId;
  }

  public function setParticipantId($participantId) {
    $this->participantId = $participantId;
  }

  public function getCreatorUserId() {
    return $this->addedByUserId();
  }

  public function setCreatorUserId($creatorUserId) {
    $this->creatorUserId = $creatorUserId;
  }

  public function getTourId() {
    return $this->tourId;
  }
  
  public function setTourId($tourId) {
    $this->tourId = $tourId;
  }

  public function getParticipantEmail() {
    return $this->participantEmail;
  }

  public function setParticipantEmail($participantEmail) {
    $this->participantEmail = $participantEmail;
  }
}