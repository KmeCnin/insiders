<?php

namespace App\Service\Serializer\Normalizer;

use App\Entity\Rule\Arcane;
use Doctrine\ORM\EntityManagerInterface;

class SQLNormalizer implements NormalizerInterface
{
    protected $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function normalize($data): array
    {
        return $data;
    }

    public function denormalize(array $array)
    {
        return $array;
    }
}
