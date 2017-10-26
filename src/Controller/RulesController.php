<?php

namespace App\Controller;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\LexiconEntry;
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
     * @Route("/règles/capacités-arcaniques/{slug}", name="rules.abilities")
     */
    public function abilitiesAction(string $slug)
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('ability');
        $arcanesRepo = $this->getDoctrine()->getRepository(Arcane::class);
        $arcane = $arcanesRepo->findOneBy(['slug' => $slug]);

        if (null === $arcane) {
            throw new \InvalidArgumentException(sprintf(
                'Arcane with slug %s does not exist.',
                $slug
            ));
        }

        $repo = $this->getDoctrine()->getRepository(Ability::class);

        return $this->render('pages/rules/abilities.html.twig', [
            'description' => $lexicon->getDescription(),
            'arcane' => $arcane,
            'arcanes' => $arcanesRepo->findAll(),
            'abilities' => $repo->findBy(['arcane' => $arcane]),
        ]);
    }

    /**
     * @Route("/règles/capacités-arcaniques", name="rules.all_abilities")
     */
    public function allAbilitiesAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('ability');
        $arcanes = $this->getDoctrine()->getRepository(Arcane::class)->findAll();
        $repo = $this->getDoctrine()->getRepository(Ability::class);

        $map = [];
        foreach ($arcanes as $arcane) {
            $map[] = [
                'arcane' => $arcane,
                'abilities' => $repo->findByArcane($arcane),
            ];
        }

        return $this->render('pages/rules/all_abilities.html.twig', [
            'description' => $lexicon->getDescription(),
            'arcanes' => $arcanes,
            'map' => $map,
        ]);
    }

    /**
     * @Route("/règles/attributs-physiques", name="rules.attributes")
     */
    public function attributesAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('attribute');
        $repo = $this->getDoctrine()->getRepository(Attribute::class);

        return $this->render('pages/rules/attributes.html.twig', [
            'description' => $lexicon->getDescription(),
            'attributes' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/décharges-d'arcanite", name="rules.bursts")
     */
    public function burstsAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('burst');
        $repo = $this->getDoctrine()->getRepository(Burst::class);

        return $this->render('pages/rules/bursts.html.twig', [
            'description' => $lexicon->getDescription(),
            'bursts' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/équipements", name="rules.stuff")
     */
    public function stuffAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('stuff');
        $repo = $this->getDoctrine()->getRepository(CanonicalStuff::class);

        return $this->render('pages/rules/stuff.html.twig', [
            'description' => $lexicon->getDescription(),
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
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('characteristic');
        $repo = $this->getDoctrine()->getRepository(Characteristic::class);

        return $this->render('pages/rules/characteristics.html.twig', [
            'description' => $lexicon->getDescription(),
            'characteristics' => $repo->findAll(),
        ]);
    }
}
