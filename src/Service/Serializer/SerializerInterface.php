<?php

namespace App\Service\Serializer;

interface SerializerInterface
{
    public function serialize($data): string;

    public function deserialize(string $data);
}
