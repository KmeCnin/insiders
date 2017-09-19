<?php

namespace App\Entity;

use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Stuff extends AbstractEntity
{
    const BASE_PRICE = 9000;

    const FRACTION_PRICE = 50;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $effectiveness;

    /**
     * @var StuffKind
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Rule\StuffKind")
     */
    protected $kind;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $expendable;

    /**
     * @var StuffProperty[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Rule\StuffProperty")
     */
    protected $properties;

    public function __construct()
    {
        parent::__construct();

        $this->expendable = false;
        $this->properties = new ArrayCollection([]);
    }

    public function getEffectiveness(): ?int
    {
        return $this->effectiveness;
    }

    public function setEffectiveness(int $effectiveness): self
    {
        $this->effectiveness = $effectiveness;
        $this->setRank($effectiveness);

        return $this;
    }

    public function getKind(): ?StuffKind
    {
        return $this->kind;
    }

    public function setKind(StuffKind $kind): self
    {
        $this->kind = $kind;

        return $this;
    }

    public function getProperties(): Collection
    {
        return $this->properties;
    }

    public function setProperties(Collection $properties): self
    {
        $this->properties->clear();
        foreach ($properties as $property) {
            $this->AddProperty($property);
        }

        return $this;
    }

    public function addProperty(StuffProperty $property): self
    {
        if (!$this->properties->contains($property)) {
            $this->properties->add($property);
        }

        return $this;
    }

    public function removeProperty(StuffProperty $property): self
    {
        $this->properties->removeElement($property);

        return $this;
    }

    public function isExpendable(): ?bool
    {
        return $this->expendable;
    }

    public function setExpendable(bool $expendable): self
    {
        $this->expendable = $expendable;

        return $this;
    }

    public function getPrice(): int
    {
        $price = 0;
        foreach ($this->properties as $property) {
            $price += $property->getPrice();
        }

        $price += $this->effectiveness * self::BASE_PRICE;
        return $this->expendable ? $price/self::FRACTION_PRICE : $price;
    }
}
