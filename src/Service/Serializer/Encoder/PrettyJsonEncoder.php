<?php

namespace App\Service\Serializer\Encoder;

class PrettyJsonEncoder implements EncoderInterface
{
    public function encode(array $data): string
    {
        return json_encode(
            $data,
            JSON_PRETTY_PRINT+JSON_UNESCAPED_UNICODE
        );
    }

    public function decode(string $string): array
    {
        return json_decode($string, true);
    }
}
