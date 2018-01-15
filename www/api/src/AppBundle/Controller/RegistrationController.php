<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\User;

class RegistrationController extends Controller
{

    /**
     * @Route("/register", name="register", methods={"POST"})
     */
    public function registerAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $formData = json_decode($request->getContent(), true);
        
        $email = $formData["email"];
        $password = $formData["password"];

        $user = $em->getRepository('AppBundle\Entity\User')->findBy(array('email' => $email));

        $response = new Response();

        if ($user != null) {
            $reponse->setStatusCode(409);
        } else {
            $user = new User();

            $user->setUserEmail($email);
            $user->setUserPassword($password);

            $response->setStatusCode(201);
        }
        
        return $response;
    }
}