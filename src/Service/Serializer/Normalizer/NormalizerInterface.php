<?php

namespace App\Service\Serializer\Normalizer;

interface NormalizerInterface
{
    public function normalize($data, $extended = null): array;

    public function denormalize(array $array, $extended = null);
}
