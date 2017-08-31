<?php

namespace App\Service\Serializer\Normalizer;

interface NormalizerInterface
{
    public function normalize($data): array;

    public function denormalize(array $array);
}
