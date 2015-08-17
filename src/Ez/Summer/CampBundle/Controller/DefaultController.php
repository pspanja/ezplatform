<?php

namespace Ez\Summer\CampBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('EzSummerCampBundle:Default:index.html.twig', array('name' => $name));
    }
}
