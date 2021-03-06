<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DeityRepository")
 */
class Deity extends AbstractRule
{
    use DescriptionTrait;

    public const CODE = 'deity';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $dignity;

    /**
     * @var Arcane
     *
     * @ORM\ManyToOne(targetEntity="Arcane")
     * @ORM\JoinColumn(nullable=false)
     */
    private $arcane;

    /**
     * @var Champion
     *
     * @ORM\OneToOne(targetEntity="Champion")
     */
    private $champion;

    public function getDignity(): ?string
    {
        return $this->dignity;
    }

    public function setDignity(string $dignity): self
    {
        $this->dignity = $dignity;

        return $this;
    }

    public function getShort(): ?string
    {
        return $this->getDignity();
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

    public function getChampion(): ?Champion
    {
        return $this->champion;
    }

    public function setChampion(?Champion $champion): self
    {
        $this->champion = $champion;
        if (null !== $champion) {
            $champion->setDeity($this);
        }

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'dignity' => $this->getDignity(),
            'arcane' => $this->getArcane()->getId(),
            'champion' => $this->getChampion()
                ? $this->getChampion()->getId()
                : null,
            'description' => $this->getDescription(),
        ]);
    }
}
