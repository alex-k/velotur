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
  private $id;

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
   * @ORM\Column(type="text")
   */  
  private $russianFirstName;

  /**
   * @ORM\Column(type="text")
   */  
  private $russianMiddleName;

  /**
   * @ORM\Column(type="text")
   */  
  private $russianLastName;

  /**
   * @ORM\Column(type="string")
   */  
  private $latinFirstName;

  /**
   * @ORM\Column(type="string")
   */  
  private $latinLastName;

  /**
   * @ORM\Column(type="string")
   */  
  private $birthday;

  /**
   * @ORM\Column(type="string")
   */  
  private $citizenship;

  /**
   * @ORM\Column(type="string")
   * note: maps to mysql enum('Male', 'Female')
   */  
  private $sex;

  /**
   * @ORM\Column(type="string")
   */  
  private $city;

  /**
   * @ORM\Column(type="string")
   */  
  private $passportNumber;

  /**
   * @ORM\Column(type="text")
   */
  private $passportIssuedBy;

  /**
   * @ORM\Column(type="string")
   */  
  private $passportIssuedDate;

  /**
   * @ORM\Column(type="string")
   */  
  private $passportValidThrough;

  /**
   * @ORM\Column(type="string")
   */  
  private $phone;

  /**
   * @ORM\Column(type="string")
   */
  private $vpNumber;

  /**
   * @ORM\Column(type="string")
   */ 
  private $registrationDate;

  /**
   * @ORM\Column(type="string")
   */  
  private $howFound;
 
  /**
   * @ORM\Column(type="integer")
   */ 
  private $comments;

  /* ==============================================================================================
  *  GETTERS
  *  ============================================================================================*/
  
  public function getId() {
    return $this->id;
  }
  public function getCreatorUserId() {
    return $this->addedByUserId();
  }
  public function getTourId() {
    return $this->tourId;
  }
  public function getRussianFirstName() {
    return $this->russianFirstName;
  }
  public function getRussianMiddleName() {
    return $this->russianMiddleName;
  }
  public function getRussianLastName() {
    return $this->russianLastName;
  }
  public function getLatinFirstName() {
    return $this->latinFirstName;
  }
  public function getLatinLastName() {
    return $this->latinLastName;
  }
  public function getBirthday() {
    return $this->birthday;
  }
  public function getCitizenship() {
    return $this->citizenship;
  }
  public function getSex() {
    return $this->sex;
  }
  public function getCity() {
    return $this->city;
  }
  public function getPassportNumber() {
    return $this->passportNumber;
  }
  public function getPassportIssuedBy() {
    return $this->passportIssuedBy;
  }
  public function getPassportIssuedDate() {
    return $this->passportIssuedDate;
  }
  public function getPassportValidThrough() {
    return $this->passportValidThrough;
  }
  public function getPhone() {
    return $this->phone;
  }
  public function getVpNumber() {
    return $this->vpNumber;
  }
  public function getRegistrationDate() {
    return $this->registrationDate;
  }
  public function getHowFound() {
    return $this->howFound;
  }
  public function getComments() {
    return $this->comments;
  }

  /* ==============================================================================================
  *  SETTERS
  *  ============================================================================================*/
  
  public function setId($id) {
    $this->id = $id;
  }
  public function setCreatorUserId($creatorUserId) {
    $this->creatorUserId = $creatorUserId;
  }
  public function setTourId($tourId) {
    $this->tourId = $tourId;
  }
  public function setRussianFirstName($russianFirstName) {
    $this->russianFirstName = $russianFirstName;
  }
  public function setRussianMiddleName($russianMiddleName) {
    $this->russianMiddleName = $russianMiddleName;
  }
  public function setRussianLastName($russianLastName) {
    $this->russianLastName = $russianLastName;
  }
  public function setLatinFirstName($latinFirstName) {
    $this->latinFirstName = $latinFirstName;
  }
  public function setLatinLastName($latinLastName) {
    $this->latinLastName = $latinLastName;
  }
  public function setParticipantLatinName($participantLatinName) {
    $this->participantLatinName = $participantLatinName;
  }
  public function setBirthday($birthday) {
    $this->birthday = $birthday;
  }
  public function setCitizenship($citizenship) {
    $this->citizenship = $citizenship;
  }
  public function setSex($sex) {
    $this->sex = $sex;
  }
  public function setCity($city) {
    $this->city = $city;
  }
  public function setPassportNumber($passportNumber) {
    $this->passportNumber = $passportNumber;
  }
  public function setPassportIssuedBy($passportIssuedBy) {
    $this->passportIssuedBy = $passportIssuedBy;
  }
  public function setPassportIssuedDate($passportIssuedDate) {
    $this->passportIssuedDate = $passportIssuedDate;
  }
  public function setPassportValidThrough($passportValidThrough) {
    $this->passportValidThrough = $passportValidThrough;
  }
  public function setPhone($phone) {
    $this->phone = $phone;
  }
  public function setVpNumber($vpNumber) {
    $this->vpNumber = $vpNumber;
  }
  public function setRegistrationDate($registrationDate) {
    $this->registrationDate = $registrationDate;
  }
  public function setHowFound($howFound) {
    $this->howFound = $howFound;
  }
  public function setComments($comments) {
    $this->comments = $comments;
  }
}