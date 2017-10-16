<?php

namespace App\Controller;

use App\Entity\Rule\Arcane;
use App\Entity\Rule\Deity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UniverseController extends AbstractController
{
    /**
     * @Route("/univers", name="universe")
     */
    public function indexAction()
    {
        return $this->render('pages/universe/index.html.twig');
    }

    /**
     * @Route("/univers/arcanes", name="universe.arcanes")
     */
    public function arcanesAction()
    {
        $repo = $this->getDoctrine()->getRepository(Arcane::class);

        return $this->render('pages/universe/arcanes.html.twig', [
            'arcanes' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/univers/divinités", name="universe.deities")
     */
    public function deitiesAction()
    {
        $repo = $this->getDoctrine()->getRepository(Deity::class);

        return $this->render('pages/universe/deities.html.twig', [
            'deities' => $repo->findAll(),
        ]);
    }
}
