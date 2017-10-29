<?php

namespace App\Entity\Rule;

trait ShortTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $short;

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }
}
