<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

class Increase
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $short;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    public function __construct()
    {
        $this->setShort('');
        $this->setDescription('');
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
}
