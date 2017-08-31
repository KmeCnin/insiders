<?php

namespace App\Service\Serializer;

use App\Service\Serializer\Encoder\EncoderInterface;
use App\Service\Serializer\Normalizer\NormalizerInterface;

class Serializer implements SerializerInterface
{
    protected $normalizer;
    protected $encoder;

    public function __construct(NormalizerInterface $normalizer, EncoderInterface $encoder)
    {
        $this->normalizer = $normalizer;
        $this->encoder = $encoder;
    }

    public function serialize($data): string
    {
        $normalized = $this->normalizer->normalize($data);
        return $this->encoder->encode($normalized);
    }

    public function deserialize(string $data)
    {
        $decoded = $this->encoder->decode($data);
        return $this->normalizer->denormalize($decoded);
    }
}