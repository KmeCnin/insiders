<?php

namespace App\Controller;

use App\Entity\Rule\Ability;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
use Symfony\Component\Routing\Annotation\Route;

class LandingController extends AbstractAppController
{
    /**
     * @Route("/", name="home")
     */
    public function indexAction()
    {
        $repo = $this->getDoctrine()->getRepository(LexiconEntry::class);

        $entries = [
            Arcane::CODE => 'universe.arcanes',
            Deity::CODE => 'universe.deities',
            Attribute::CODE => 'rules.attributes',
            Ability::CODE => 'rules.all_abilities',
            Characteristic::CODE => 'rules.characteristics',
            Burst::CODE => 'rules.all_bursts',
            CanonicalStuff::CODE => 'rules.all_stuff',
        ];

        $categories = [];
        foreach ($entries as $id => $route) {
            $entity = $repo->find($id);
            $categories[] = [
                'id' => $id,
                'name' => $entity->getName(),
                'short' => $entity->getShort(),
                'route' => $route,
            ];
        }

        return $this->render(
            'pages/landing/home.html.twig',
            ['categories' => $categories]
        );
    }
}
