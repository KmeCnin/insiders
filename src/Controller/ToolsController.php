<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;

class ToolsController extends AbstractAppController
{
    /**
     * @Route("/outils", name="tools")
     */
    public function indexAction()
    {
        return $this->render('pages/tools/index.html.twig');
    }
}
