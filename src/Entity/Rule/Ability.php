<?php

namespace App\Entity\Rule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Ability extends AbstractRule
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

    /**
     * @var Increase[]|Collection
     *
     * @ORM\OneToMany(targetEntity="Increase", mappedBy="ability", cascade={"all"}, orphanRemoval=true)
     */
    protected $increases;

    public function __construct()
    {
        parent::__construct();

        $this->increases = new ArrayCollection([]);
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

    public function getIncreases(): iterable
    {
        return $this->increases;
    }

    public function setIncreases(iterable $increases): self
    {
        $this->increases->clear();
        foreach ($increases as $increase) {
            $this->addIncrease($increase);
        }

        return $this;
    }

    public function addIncrease(Increase $increase): self
    {
        if (!$this->increases->contains($increase)) {
            $increase->setAbility($this);
            $this->increases->add($increase);
        }

        return $this;
    }

    public function removeIncrease(Increase $increase): self
    {
        $this->increases->removeElement($increase);

        return $this;
    }
}
