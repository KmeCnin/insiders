<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('pages/landing/home.html.twig');
    }
}