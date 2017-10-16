<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\BurstRepository")
 */
class Burst extends AbstractRule
{
    /**
     * @var Arcane
     *
     * @ORM\ManyToOne(targetEntity="Arcane")
     * @ORM\JoinColumn(nullable=false)
     */
    private $arcane;

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
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
    }

    public function getArcane(): ?Arcane
    {
        return $this->arcane;
    }

    public function setArcane(Arcane $arcane): self
    {
        $this->arcane = $arcane;

        return $this;
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
        return array_merge(parent::normalize(), [
            'arcane' => $this->getArcane()->getId(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
