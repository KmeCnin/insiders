<?php

namespace App\Entity;

interface NormalizableInterface
{
    public function normalize(): array;
}
