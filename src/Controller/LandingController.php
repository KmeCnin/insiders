<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractAppController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        return $this->render('pages/landing/home.html.twig');
    }
}
