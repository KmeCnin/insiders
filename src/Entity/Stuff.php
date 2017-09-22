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
    // Price equivalent to 1 FP.
    const FP_PRICE = 10000;
    // Number of expendables equivalent to the same permanent stuff.
    const EXPENDABLE_FRACTION = 50;
    // FP of one degree of effectiveness.
    const EFFECTIVENESS_FP = 0.9;

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

    public function incrementEffectiveness(): self
    {
        $this->effectiveness++;
        $this->setRank($this->effectiveness);

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
        // Properties
        $fp = 0;
        foreach ($this->properties as $property) {
            $fp += $property->getFp();
        }

        // Effectiveness
        $fp += $this->effectiveness * self::EFFECTIVENESS_FP;

        return self::fpToPrice($this, $fp);
    }

    public static function priceToFp(Stuff $stuff, int $price): float
    {
        return $stuff->expendable
            ? $price / (self::FP_PRICE*self::EXPENDABLE_FRACTION)
            : $price / self::FP_PRICE;
    }

    public static function fpToPrice(Stuff $stuff, float $fp): int
    {
        return $stuff->expendable
            ? $fp * (self::FP_PRICE/self::EXPENDABLE_FRACTION)
            : $fp * self::FP_PRICE;
    }
}
