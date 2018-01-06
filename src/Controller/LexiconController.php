<?php

namespace App\Controller;

use App\Entity\Rule\LexiconEntry;
use Symfony\Component\Routing\Annotation\Route;

class LexiconController extends AbstractAppController
{
    /**
     * @Route("/lexique", name="lexicon")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository(LexiconEntry::class);

        return $this->render('pages/lexicon/index.html.twig', [
            'lexicon' => $repo->findAll(),
        ]);
    }
}
