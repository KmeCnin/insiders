<?php

namespace App\Controller;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\Skill;
use App\Entity\Rule\StuffCategory;
use App\Service\RulesAugmenter;
use Symfony\Component\Routing\Annotation\Route;

class RulesController extends AbstractAppController
{
    private $rulesAugmenter;

    public function __construct(RulesAugmenter $rulesAugmenter)
    {
        $this->rulesAugmenter = $rulesAugmenter;
    }

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
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'arcane' => $arcane,
            'arcanes' => $arcanesRepo->findAll(),
            'abilities' => $repo->findByArcane($arcane),
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
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
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
        $pc = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('pc');

        return $this->render('pages/rules/attributes.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'attributes' => $repo->findAll(),
            'pc' => $pc,
        ]);
    }

    /**
     * @Route("/règles/décharges-d'arcanite/{slug}", name="rules.bursts")
     */
    public function burstsAction(string $slug)
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('burst');
        $arcanesRepo = $this->getDoctrine()->getRepository(Arcane::class);
        $arcane = $arcanesRepo->findOneBy(['slug' => $slug]);

        if (null === $arcane) {
            throw new \InvalidArgumentException(sprintf(
                'Arcane with slug %s does not exist.',
                $slug
            ));
        }

        $repo = $this->getDoctrine()->getRepository(Burst::class);

        return $this->render('pages/rules/bursts.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'arcane' => $arcane,
            'arcanes' => $arcanesRepo->findAll(),
            'bursts' => $repo->findByArcane($arcane),
        ]);
    }

    /**
     * @Route("/règles/décharges-d'arcanite", name="rules.all_bursts")
     */
    public function allBurstsAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('burst');
        $arcanes = $this->getDoctrine()->getRepository(Arcane::class)->findAll();
        $repo = $this->getDoctrine()->getRepository(Burst::class);

        $map = [];
        foreach ($arcanes as $arcane) {
            $map[] = [
                'arcane' => $arcane,
                'bursts' => $repo->findByArcane($arcane),
            ];
        }

        return $this->render('pages/rules/all_bursts.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'arcanes' => $arcanes,
            'map' => $map,
        ]);
    }

    /**
     * @Route("/règles/équipements/{slug}", name="rules.stuff")
     */
    public function stuffAction(string $slug)
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('stuff');
        $categoriesRepo = $this->getDoctrine()->getRepository(StuffCategory::class);
        $category = $categoriesRepo->findOneBy(['slug' => $slug]);

        if (null === $category) {
            throw new \InvalidArgumentException(sprintf(
                'StuffCategory with slug %s does not exist.',
                $slug
            ));
        }

        $repo = $this->getDoctrine()->getRepository(CanonicalStuff::class);

        return $this->render('pages/rules/stuff.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'category' => $category,
            'categories' => $categoriesRepo->findAll(),
            'stuff' => $repo->findByCategory($category),
        ]);
    }

    /**
     * @Route("/règles/équipements", name="rules.all_stuff")
     */
    public function allStuffAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('stuff');
        $categories = $this->getDoctrine()->getRepository(StuffCategory::class)->findAll();
        $repo = $this->getDoctrine()->getRepository(CanonicalStuff::class);

        $map = [];
        foreach ($categories as $category) {
            $map[] = [
                'category' => $category,
                'stuff' => $repo->findByCategory($category),
            ];
        }

        return $this->render('pages/rules/all_stuff.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'categories' => $categories,
            'map' => $map,
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
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'characteristics' => $repo->findAll(),
        ]);
    }

    /**
     * @Route("/règles/compétences", name="rules.skills")
     */
    public function skillsAction()
    {
        $lexicon = $this->getDoctrine()->getRepository(LexiconEntry::class)->find('skill');
        $repo = $this->getDoctrine()->getRepository(Skill::class);

        return $this->render('pages/rules/skills.html.twig', [
            'description' => $this->rulesAugmenter->augment(
                $lexicon->getDescription()
            ),
            'skills' => $repo->findAll(),
        ]);
    }
}
