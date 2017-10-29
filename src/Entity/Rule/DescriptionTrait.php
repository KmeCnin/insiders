<?php

namespace App\Entity\Rule;

trait DescriptionTrait
{
    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDescriptionRaw(): ?string
    {
        return strip_tags($this->description);
    }
}
