<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;

class UserRegistrationController extends Controller
{

    /**
     * @Route("/submitUserRegistration", name="submit_user_registration", methods={"POST"})
     */
    public function submitAction(Request $request, LoggerInterface $logger)
    {
        $user = new User();
        $formData = json_decode($request->getContent(), true);

        $russianFirstName = $formData['russianFirstName'] ?? null;
        $russianLastName = $formData['russianLastName'] ?? null;
        $russianMiddleName = $formData['russianMiddleName'] ?? null;
        $latinFirstName = $formData['latinFirstName'] ?? null;
        $latinLastName = $formData['latinLastName'] ?? null;
        $birthday = $formData['birthday'] ?? null;
        $citizenship = $formData['citizenship'] ?? null;
        $sex = $formData['sex'] ?? null;
        $city = $formData['city'] ?? null; 
        $passportNumber = $formData['passportNumber'] ?? null;
        $passportIssuedBy = $formData['passportIssuedBy'] ?? null; 
        $passportIssuedDate = $formData['passportIssuedDate'] ?? null;
        $passportValidThrough = $formData['passportValidThrough'] ?? null;
        $phone = $formData['phone'] ?? null;
        $vpNumber = $formData['vpNumber'] ?? null;
        $registrationDate = date_create();
        $howFound = $formData['howFound'] ?? null;
        if ($howFound == null) {
            $howFound = $formData['howFoundText'] ?? null;
        }

        $user->setUserRussianName1($russianFirstName);
        $user->setUserRussianName2($russianMiddleName);
        $user->setUserRussianName3($russianLastName);
        $user->setUserLatinName1($latinFirstName);
        $user->setUserLatinName2($latinLastName);
        $user->setUserBirthDay($birthday);
        $user->setUserCitizenship($citizenship);
        $user->setUserSex($sex);
        $user->setUserCity($city);
        $user->setUserPassport($passportNumber);
        $user->setUserPassportIssuedBy($passportIssuedBy);
        $user->setUserPassportIssuedDate($passportIssuedDate);
        $user->setUserPassportValidThrow($passportValidThrough);
        $user->setUserPhone($phone);
        $user->setUserVPNumber($vpNumber);
        $user->setUserRegistrationDate($registrationDate);
        $user->setUserInfoHowFound($howFound);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $logger->info(count($formData));

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        
        return $response;           
    }
}