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
     * @Route("/submitParticipant", name="submit_participant", methods={"POST"})
     */
    public function submitAction(Request $request)
    {
        $tourParticipant = new TourParticipant();
        $formData = json_decode($request->getContent(), true);
        
        $id = $formData['participantId'] ?: 0;
        $creatorUserId = $formData['creatorUserId'] ?: 0;
        $tourId = $formData['tourId'] ?: 0;
        $russianFirstName = $formData['russianFirstName'] ?: '';
        $russianLastName = $formData['russianLastName'] ?: '';
        $russianMiddleName = $formData['russianMiddleName'] ?: '';
        $latinFirstName = $formData['latinFirstName'] ?: '';
        $latinLastName = $formData['latinLastName'] ?: '';
        $birthday = $formData['birthday'] ?: '';
        $citizenship = $formData['citizenship'] ?: '';
        $sex = $formData['sex'] ?: '';
        $city = $formData['city'] ?: '';
        $passportNumber = $formData['passportNumber'] ?: '';
        $passportIssuedBy = $formData['passportIssuedBy'] ?: '';
        $passportIssuedDate = $formData['passportIssuedDate'] ?: ''; 
        $passportValidThrough = $formData['passportValidThrough'] ?: '';
        $phone = $formData['phone'] ?: '';
        $vpNumber = $formData['vpNumber'] ?: '';
        $registrationDate = date('Y-m-d') ?: '';
        $howFound = $formData['howFound'] ?: '';
        if ($howFound == '') {
            $howFound = $formData['howFoundText'] ?: '';
        }
        $comments = $formData['comments'] ?: '';

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

        return $response;           
    }
}