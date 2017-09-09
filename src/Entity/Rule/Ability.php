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
     * @var Ability[]
     *
     * @ORM\ManyToMany(targetEntity="Ability", mappedBy="abilitiesUnlocked")
     */
    private $abilitiesRequired;

    /**
     * @var Ability[]
     *
     * @ORM\ManyToMany(targetEntity="Ability", inversedBy="abilitiesRequired")
     * @ORM\JoinTable(
     *     name="abilitiesTree",
     *     joinColumns={@ORM\JoinColumn(
     *         name="ability_id",
     *         referencedColumnName="id"
     *     )},
     *     inverseJoinColumns={@ORM\JoinColumn(
     *         name="ability_required_id",
     *         referencedColumnName="id"
     *     )}
     * )
     */
    private $abilitiesUnlocked;

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
        $this->abilitiesRequired = new ArrayCollection();
        $this->abilitiesUnlocked = new ArrayCollection();
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
            $ability->addAbilityUnlocked($this);
        }

        return $this;
    }

    public function removeAbilityRequired(Ability $ability): self
    {
        $this->abilitiesRequired->removeElement($ability);

        return $this;
    }

    public function getAbilitiesUnlocked(): \Traversable
    {
        return $this->abilitiesUnlocked;
    }

    public function setAbilitiesUnlocked(\Traversable $abilities): self
    {
        $this->abilitiesUnlocked->clear();
        foreach ($abilities as $ability) {
            $this->addAbilityUnlocked($ability);
        }

        return $this;
    }

    public function addAbilityUnlocked(Ability $ability)
    {
        if (!$this->abilitiesUnlocked->contains($ability)) {
            $this->abilitiesUnlocked->add($ability);
            $ability->addAbilityRequired($this);
        }

        return $this;
    }

    public function removeAbilityUnlocked(Ability $ability): self
    {
        $this->abilitiesUnlocked->removeElement($ability);

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
            $increase->setAbility($this);
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
            'arcane' => $this->getArcane()->getSlug(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
            'increases' => array_map(function (Increase $increase) {
                return $increase->normalize();
            }, iterator_to_array($this->getIncreases())),
        ]);
    }
}
