<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\TourParticipant;

class TourParticipantController extends Controller
{

    /**
     * @Route("/tourParticipantSubmit", name="tourParticipantSubmit")
     * @Method("POST")
     */
    public function submitAction(Request $request)
    {
        $tourParticipant = new TourParticipant();

        $creatorUserId = $_POST['creatorUserId'];
        $tourId = $_POST['tourId'];
        $participantEmail = $_POST['participantEmail'];
        $participantRussianName = $_POST['participantRussianName'];
        $participantLatinName = $_POST['participantLatinName'];
        $participantBirthDay = $_POST['participantBirthDay'];
        $participantCitizenship = $_POST['participantCitizenship'];
        $participantSex = $_POST['participantSex']; 
        $participantCountry = $_POST['participantCountry'];
        $participantCity = $_POST['participantCity']; 
        $participantAddress = $_POST['participantAddress']; 
        $participantJob = $_POST['participantJob'];
        $participantPassportType = $_POST['participantPassportType']; 
        $participantPassport = $_POST['participantPassport'];
        $participantPassportIssuedBy = $_POST['participantPassportIssuedBy'];
        $participantPassportIssuedDate = $_POST['participantPassportIssuedDate']; 
        $participantPassportValidThrow = $_POST['participantPassportValidThrow'];
        $participantPhone = $_POST['participantPhone'];
        $participantVPNumber = $_POST['participantVPNumber'];
        $participantType = $_POST['participantType'];
        $participantReferalID = $_POST['participantReferalID'];
        $participantRegistrationDate = $_POST['participantRegistrationDate'];
        $participantInfoHowFound = $_POST['participantInfoHowFound'];
        $participantPartnerID = $_POST['participantPartnerID'];
        $participantCompletedTours = $_POST['participantCompletedTours'];
        $participantSubscribeNews = $_POST['participantSubscribeNews'];

        $tourParticipant->setCreatorUserId($creatorUserId);
        $tourParticipant->setParticipantEmail($participantEmail);
        $tourParticipant->setParticipantRussianName($participantRussianName);
        $tourParticipant->setParticipantLatinName($participantLatinName);
        $tourParticipant->setParticipantBirthDay($participantBirthDay);
        $tourParticipant->setParticipantCitizenship($participantCitizenship);
        $tourParticipant->setParticipantSex($participantSex);
        $tourParticipant->setParticipantCountry($participantCountry);
        $tourParticipant->setParticipantCity($participantCity);
        $tourParticipant->setParticipantAddress($participantAddress);
        $tourParticipant->setParticipantJob($participantJob);
        $tourParticipant->setParticipantPassportType($participantPassportType);
        $tourParticipant->setParticipantPassport($participantPassport);
        $tourParticipant->setParticipantPassportIssuedBy($participantPassportIssuedBy);
        $tourParticipant->setParticipantPassportIssuedDate($participantPassportIssuedDate);
        $tourParticipant->setParticipantPassportValidThrow($participantPassportValidThrow);
        $tourParticipant->setParticipantPhone($participantPhone);
        $tourParticipant->setParticipantVPNumber($participantVPNumber);
        $tourParticipant->setParticipantType($participantType);
        $tourParticipant->setParticipantReferalID($participantReferalID);
        $tourParticipant->setParticipantRegistrationDate($participantRegistrationDate);
        $tourParticipant->setParticipantInfoHowFound($participantInfoHowFound);
        $tourParticipant->setParticipantPartnerID($participantPartnerID);
        $tourParticipant->setParticipantCompletedTours($participantCompletedTours);
        $tourParticipant->setParticipantSubscribeNews($participantSubscribeNews);

        $em = $this->getDoctrine()->getManager();
        $em->persist($tourParticipant);
        $em->flush();

        return new Response("Added new TourParticipant with participantId " . $participantId);
    }
}