<?php

namespace App\Service\Serializer\Normalizer;

interface NormalizerInterface
{
    public function normalize($data, $from = null): array;

    public function denormalize(array $array, $to = null);
}
