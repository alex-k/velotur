<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use \DateTime;

use AppBundle\Entity\User;

class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function registerAction(Request $request)
    {
        $formData = json_decode($request->getContent(), true);

        $email = $formData['email'];
        $password = $formData['password'];

        $connectionParams = array(
            'dbname' => 'velotur',
            'user' => 'root',
            'password' => '',
            'host' => 'mysql',
            'driver' => 'pdo_mysql'
        );

        $em = $this->getDoctrine()->getManager();
        $em = \Doctrine\ORM\EntityManager::create($connectionParams, $em->getConfiguration(), $em->getEventManager());

        $user = $em->getRepository('AppBundle\Entity\User')->findBy(array('userEmail' => $email));

        if ($user == null) {
            $user = new User();

            $russianName = '';
            $latinName = '';
            $citizenship = '';
            $country = '';
            $city = '';
            $address = '';
            $job = '';
            $phone = '';
            $type = 'regular';
            $referalID = 0;
            $registrationDate = new DateTime("now");
            $infoHowFound = '';
            $completedTours = 0;
            $subscribeNews = 0;

            $user->setUserEmail($email);
            $user->setUserPassword($password);
            $user->setUserRussianName($russianName);
            $user->setUserLatinName($latinName);
            $user->setUserCitizenship($citizenship);
            $user->setUserCountry($country);
            $user->setUserCity($city);
            $user->setUserAddress($address);
            $user->setUserJob($job);
            $user->setUserPhone($phone);
            $user->setUserType($type);
            $user->setUserReferalID($referalID);
            $user->setUserRegistrationDate($registrationDate);
            $user->setUserInfoHowFound($infoHowFound);
            $user->setUserCompletedTours($completedTours);
            $user->setUserSubscribeNews($subscribeNews);

            $em->persist($user);
            $em->flush();

            return new Response("201 Created: created new User entry", 201);
        } else {
            return new Response("409 Conflict: User entry already exists", 409);
        }
    }
}