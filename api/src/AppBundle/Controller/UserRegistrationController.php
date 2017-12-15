<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class UserRegistrationController extends Controller
{

    /**
     * @Route("/submitUserRegistration", name="submit_user_registration")
     */
    public function submitAction(Request $request)
    {
        $user = new User();

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
        $user->setUserPassportValidThrouw($passportValidThrough);
        $user->setUserPhone($phone);
        $user->setUserVPNumber($vpNumber);
        $user->setUserRegistrationDate($registrationDate);
        $user->setUserInfoHowFound($howFound);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        $response = new Response();
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'text/html');
        $response->setContent('Added new User' . $_POST['russianLastName']);

        return $response;           
    }
}