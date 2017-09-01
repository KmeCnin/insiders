<?php

namespace App\Service\Serializer;

interface SerializerInterface
{
    public function serialize($data, $from = null): string;

    public function deserialize(string $data, $to = null);
}
