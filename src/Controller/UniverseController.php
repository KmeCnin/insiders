<?php

namespace App\Controller;

use App\Entity\Rule\Arcane;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
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
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('arcane');
        $repo = $this->getDoctrine()->getRepository(Arcane::class);

        return $this->render('pages/universe/arcanes.html.twig', [
            'description' => $lexicon->getDescription(),
            'arcanes' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/univers/divinités", name="universe.deities")
     */
    public function deitiesAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('deity');
        $repo = $this->getDoctrine()->getRepository(Deity::class);

        return $this->render('pages/universe/deities.html.twig', [
            'description' => $lexicon->getDescription(),
            'deities' => $repo->findAll(),
        ]);
    }
}
