<?php

namespace App\Service\Serializer\Encoder;

interface EncoderInterface
{
    public function encode(array $data): string;

    public function decode(string $string): array;
}
