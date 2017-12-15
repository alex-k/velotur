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

        $id = $_POST['participantId'] ?? 0;
        $creatorUserId = $_POST['creatorUserId'] ?? 0;
        $tourId = $_POST['tourId'] ?? 0;
        $russianFirstName = $_POST['russianFirstName'] ?? '';
        $russianLastName = $_POST['russianLastName'] ?? '';
        $russianMiddleName = $_POST['russianMiddleName'] ?? '';
        $latinFirstName = $_POST['latinFirstName'] ?? '';
        $latinLastName = $_POST['latinLastName'] ?? '';
        $birthday = $_POST['birthday'] ?? '';
        $citizenship = $_POST['citizenship'] ?? '';
        $sex = $_POST['sex'] ?? '';
        $city = $_POST['city'] ?? '';
        $passportNumber = $_POST['passportNumber'] ?? '';
        $passportIssuedBy = $_POST['passportIssuedBy'] ?? '';
        $passportIssuedDate = $_POST['passportIssuedDate'] ?? ''; 
        $passportValidThrough = $_POST['passportValidThrough'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $vpNumber = $_POST['vpNumber'] ?? '';
        $registrationDate = date('Y-m-d') ?? '';
        $howFound = $_POST['howFound'] ?? '';
        if ($howFound == "") {
            $howFound = $_POST['howFoundText'] ?? '';
        }
        $comments = $_POST['comments'] ?? '';

        $tourParticipant->setId($id);
        $tourParticipant->setCreatorUserId($creatorUserId);
        $tourParticipant->setTourId($tourId);
        $tourParticipant->setRussianFirstName($russianFirstName);
        $tourParticipant->setRussianMiddleName($russianMiddleName);
        $tourParticipant->setRussianLastName($russianLastName);
        $tourParticipant->setLatinFirstName($latinFirstName);
        $tourParticipant->setLatinLastName($latinLastName);
        $tourParticipant->setBirthday($birthday);
        $tourParticipant->setCitizenship($citizenship);
        $tourParticipant->setSex($sex);
        $tourParticipant->setCity($city);
        $tourParticipant->setPassportNumber($passportNumber);
        $tourParticipant->setPassportIssuedBy($passportIssuedBy);
        $tourParticipant->setPassportIssuedDate($passportIssuedDate);
        $tourParticipant->setPassportValidThrough($passportValidThrough);
        $tourParticipant->setPhone($phone);
        $tourParticipant->setVpNumber($vpNumber);
        $tourParticipant->setRegistrationDate($registrationDate);
        $tourParticipant->setHowFound($howFound);
        $tourParticipant->setComments($comments);

        $em = $this->getDoctrine()->getManager();
        $em->persist($tourParticipant);
        $em->flush();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent('Added new TourParticipant' . $_POST['russianLastName']);

        return $response;           
    }
}