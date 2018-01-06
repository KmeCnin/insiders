<?php

namespace App\Controller;

use App\Entity\Rule\Arcane;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
use App\Service\RulesAugmenter;
use Symfony\Component\Routing\Annotation\Route;

class UniverseController extends AbstractAppController
{
    private $rulesAugmenter;

    public function __construct(RulesAugmenter $rulesAugmenter)
    {
        $this->rulesAugmenter = $rulesAugmenter;
    }

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
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
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
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'deities' => $repo->findAll(),
        ]);
    }
}
