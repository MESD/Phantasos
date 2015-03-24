<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/app/example", name="homepage")
     */
    public function indexAction()
    {
        $apiUser = $this->get('security.context')->getToken()->getUser()->getUsername();
        return $this->render('default/index.html.twig', array('user' => $apiUser));
    }
}
