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
     * @var Collection|array
     *
     * @ORM\Column(type="json")
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

    public function getAbilitiesRequired(): Collection
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

    /** @return Increase[] */
    public function getIncreases(): Collection
    {
        $increases = $this->increases instanceof Collection
            ? $this->increases
            : new ArrayCollection($this->increases);
        return $increases->map(function (array $increase) {
            return new Increase($increase['short'], $increase['description']);
        });
    }

    public function setIncreases(Collection $increases): self
    {
        $this->increases = $increases->map(function ($increase) {
            if ($increase instanceof Increase) {
                return $increase->normalize();
            }
            return $increase;
        })->toArray();

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'arcane' => $this->getArcane()->getId(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
            'increases' => $this->getIncreases()->map(
                function (Increase $increase) {
                    return $increase->normalize();
                }
            )->toArray(),
            'abilitiesRequired' => $this->getAbilitiesRequired()->map(
                function (Ability $ability) {
                    return $ability->getId();
                }
            )->toArray(),
        ]);
    }
}
