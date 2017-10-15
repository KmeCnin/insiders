<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ToolController extends AbstractController
{
    /**
     * @Route("/outils", name="tools")
     */
    public function indexAction()
    {
        return $this->render('pages/tools/index.html.twig');
    }
}
