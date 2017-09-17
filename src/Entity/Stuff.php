<?php

namespace App\Entity;

use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Stuff extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $quality;

    /**
     * @var StuffKind
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Rule\StuffKind")
     */
    protected $kind;

    /**
     * @var StuffProperty[]
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Rule\StuffProperty")
     */
    protected $properties;

    public function __construct()
    {
        parent::__construct();

        $this->properties = new ArrayCollection([]);
    }

    public function getQuality(): ?int
    {
        return $this->quality;
    }

    public function setQuality(int $quality): self
    {
        $this->quality = $quality;
        $this->setRank($quality);

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

    public function getProperties(): \Traversable
    {
        return $this->properties;
    }

    public function setProperties(\Traversable $properties): self
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

    public function getPrice(): int
    {
        return array_reduce(
            $this->properties,
            function (int $price, StuffProperty $property) {
                return $price + $property->getPrice();
            },
            0
        ) + ($this->quality * 9000);
    }
}
