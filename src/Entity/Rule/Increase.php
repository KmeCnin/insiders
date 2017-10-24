<?php

namespace App\Entity\Rule;

class Increase
{
    /**
     * @var string
     */
    protected $short;

    /**
     * @var string
     */
    protected $description;

    public function __construct(string $short = '', string $description = '')
    {
        $this->setShort($short);
        $this->setDescription($description);
    }

    public function getShort(): ?string
    {
        return $this->short;
    }

    public function setShort(string $short): self
    {
        $this->short = $short;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function normalize(): array
    {
        return [
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ];
    }

    public function __toString(): string
    {
        return $this->short;
    }
}
