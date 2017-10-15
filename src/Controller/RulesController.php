<?php

namespace App\Controller;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Characteristic;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractController
{
    /**
     * @Route("/règles", name="rules")
     */
    public function indexAction()
    {
        return $this->render('pages/rules/index.html.twig');
    }

    /**
     * @Route("/règles/capacités-arcaniques/{arcane}", name="rules.abilities")
     */
    public function abilitiesAction()
    {
        $repo = $this->getDoctrine()->getRepository(Ability::class);

        return $this->render('pages/rules/abilities.html.twig', [
            'abilities' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/capacités-arcaniques", name="rules.all_abilities")
     */
    public function allAbilitiesAction()
    {
        $repo = $this->getDoctrine()->getRepository(Ability::class);

        return $this->render('pages/rules/all_abilities.html.twig', [
            'abilities' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/attributs-physiques", name="rules.attributes")
     */
    public function attributesAction()
    {
        $repo = $this->getDoctrine()->getRepository(Attribute::class);

        return $this->render('pages/rules/attributes.html.twig', [
            'attributes' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/décharges-d'arcanite", name="rules.bursts")
     */
    public function burstsAction()
    {
        $repo = $this->getDoctrine()->getRepository(Burst::class);

        return $this->render('pages/rules/bursts.html.twig', [
            'bursts' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/équipements", name="rules.stuff")
     */
    public function stuffAction()
    {
        $repo = $this->getDoctrine()->getRepository(CanonicalStuff::class);

        return $this->render('pages/rules/stuff.html.twig', [
            'weapons' => $repo->findAllWeapons(),
            'armors' => $repo->findAllArmors(),
            'objects' => $repo->findAllObjects(),
            'expendables' => $repo->findAllExpendables(),
        ]);
    }

    /**
     * @Route("/règles/caractéristiques", name="rules.characteristics")
     */
    public function characteristicsAction()
    {
        $repo = $this->getDoctrine()->getRepository(Characteristic::class);

        return $this->render('pages/rules/characteristics.html.twig', [
            'characteristics' => $repo->findAll(),
        ]);
    }
}
