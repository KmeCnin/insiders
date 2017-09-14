<?php

namespace App\Entity;

use App\Entity\Rule\AbstractRule;

/**
 * @ORM\Entity
 */
class StuffProperty extends AbstractRule
{
    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $basePrice;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $multiplier;

    public function getBasePrice(): int
    {
        return $this->basePrice;
    }

    public function setBasePrice(int $price): self
    {
        $this->basePrice = $price;

        return $this;
    }

    public function getMultiplier(): int
    {
        return $this->multiplier;
    }

    public function setMultiplier(int $times): self
    {
        $this->multiplier = $times;

        return $this;
    }

    public function getPrice(): int
    {
        return $this->basePrice * $this->multiplier;
    }
}
