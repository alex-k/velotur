<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\TourParticipant;

class TourParticipantController extends Controller
{

    /**
     * @Route("/submitParticipant", name="submit_participant")
     */
    public function submitAction(Request $request)
    {
        $tourParticipant = new TourParticipant();

        $participantId = $_POST['participantId'];
        $creatorUserId = $_POST['creatorUserId'];
        $tourId = $_POST['tourId'];
        $russianFirstName = $_POST['russianFirstName'];
        $russianLastName = $_POST['russianLastName'];
        $russianMiddleName = $_POST['russianMiddleName'];
        $russianName = $russianFirstName . ' ' . $russianMiddleName . ' ' . $russianLastName;
        $latinFirstName = $_POST['latinFirstName'];
        $latinLastName = $_POST['latinLastName'];
        $latinName = $latinFirstName . ' ' . $latinLastName;
        $birthday = $_POST['birthday'];
        $citizenship = $_POST['citizenship'];
        $sex = $_POST['sex'];
        $city = $_POST['city']; 
        $passportNumber = $_POST['passportNumber'];
        $passportIssuedBy = $_POST['passportIssuedBy'];
        $passportIssuedDate = $_POST['passportIssuedDate']; 
        $passportValidThrough = $_POST['passportValidThrough'];
        $phone = $_POST['phone'];
        $vpNumber = $_POST['vpNumber'];
        $registrationDate = date('Y-m-d');
        $howFound = $_POST['howFound'];
        if ($howFound == "") {
            $howFound = $_POST['howFoundText'];
        }
        $comments = $_POST['comments'];

        $tourParticipant->setParticipantId($participantId);
        $tourParticipant->setCreatorUserId($creatorUserId);
        $tourParticipant->setParticipantRussianName($russianName);
        $tourParticipant->setParticipantLatinName($latinName);
        $tourParticipant->setParticipantBirthDay($birthDay);
        $tourParticipant->setParticipantCitizenship($citizenship);
        $tourParticipant->setParticipantSex($sex);
        $tourParticipant->setParticipantCity($city);
        $tourParticipant->setParticipantPassport($passportNumber);
        $tourParticipant->setParticipantPassportIssuedBy($passportIssuedBy);
        $tourParticipant->setParticipantPassportIssuedDate($passportIssuedDate);
        $tourParticipant->setParticipantPassportValidThrow($passportValidThrow);
        $tourParticipant->setParticipantPhone($phone);
        $tourParticipant->setParticipantVPNumber($vpNumber);
        $tourParticipant->setParticipantRegistrationDate($registrationDate);
        $tourParticipant->setParticipantInfoHowFound($howFound);

        $em = $this->getDoctrine()->getManager();
        $em->persist($tourParticipant);
        $em->flush();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent('Added new TourParticipant');

        return $response;           
    }
}