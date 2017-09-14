<?php

namespace App\Entity;

/**
 * @ORM\Entity
 */
class Stuff extends Entity
{
    public function getPrice(): int
    {
        return $this->price;
    }

    public function setPrice(int $price): self
    {
        $this->price = $price;

        return $this;
    }
}
