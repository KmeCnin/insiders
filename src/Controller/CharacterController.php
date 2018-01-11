<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\Rule\Ability;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Routing\Annotation\Route;

class CharacterController extends AbstractAppController
{
    private $serializer;

    public function __construct(
        SerializerInterface $serializer
    ) {
        $this->serializer = $serializer;
    }

    /**
     * @Route("/personnage", name="character_sheet")
     */
    public function sheetAction()
    {
        $repoAbility = $this->getDoctrine()->getRepository(Ability::class);

        return $this->render('pages/character/sheet.html.twig', [
            'props' => $this->serializer->serialize(
                ['abilities' => $repoAbility->findAll()], 'json')
        ]);
    }
}
