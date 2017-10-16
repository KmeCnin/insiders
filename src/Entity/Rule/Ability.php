<?php

namespace App\Entity\Rule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AbilityRepository")
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
     * @var Ability[]
     *
     * @ORM\ManyToMany(targetEntity="Ability")
     */
    private $abilitiesRequired;

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
     * @var Increase[]
     *
     * @ORM\Column(type="array")
     */
    protected $increases;

    public function __construct()
    {
        parent::__construct();

        $this->increases = new ArrayCollection([]);
        $this->abilitiesRequired = new ArrayCollection();
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

    public function getAbilitiesRequired(): \Traversable
    {
        return $this->abilitiesRequired;
    }

    public function setAbilitiesRequired(\Traversable $abilities): self
    {
        $this->abilitiesRequired->clear();
        foreach ($abilities as $ability) {
            $this->addAbilityRequired($ability);
        }

        return $this;
    }

    public function addAbilityRequired(Ability $ability)
    {
        if (!$this->abilitiesRequired->contains($ability)) {
            $this->abilitiesRequired->add($ability);
        }

        return $this;
    }

    public function removeAbilityRequired(Ability $ability): self
    {
        $this->abilitiesRequired->removeElement($ability);

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

    public function getIncreases(): \Traversable
    {
        return $this->increases;
    }

    public function setIncreases(\Traversable $increases): self
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
            $this->increases->add($increase);
        }

        return $this;
    }

    public function removeIncrease(Increase $increase): self
    {
        $this->increases->removeElement($increase);

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'arcane' => $this->getArcane()->getId(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
            'increases' => array_map(function (Increase $increase) {
                return $increase->normalize();
            }, iterator_to_array($this->getIncreases())),
            'abilitiesRequired' => array_map(function (Ability $ability) {
                return $ability->getId();
            }, iterator_to_array($this->getAbilitiesRequired())),
        ]);
    }
}
