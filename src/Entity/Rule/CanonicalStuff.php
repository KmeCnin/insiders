<?php

namespace App\Entity\Rule;

use App\Entity\Stuff;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CanonicalStuffRepository")
 */
class CanonicalStuff extends AbstractRule
{
    /**
     * @var Stuff
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Stuff", cascade={"all"})
     */
    protected $stuff;

    public function __construct()
    {
        parent::__construct();

        $this->stuff = new Stuff();
    }

    public function getStuff(): ?Stuff
    {
        return $this->stuff;
    }

    public function setStuff(Stuff $stuff): self
    {
        $this->stuff = $stuff;

        return $this;
    }

    public function setName(string $name): AbstractRule
    {
        parent::setName($name);
        $this->stuff->setName($name);

        return $this;
    }

    public function getKind(): ?StuffKind
    {
        return $this->stuff->getKind();
    }

    public function setKind(StuffKind $kind): self
    {
        $this->stuff->setKind($kind);

        return $this;
    }

    public function getEffectiveness(): ?int
    {
        return $this->stuff->getEffectiveness();
    }

    public function setEffectiveness(int $effectiveness): self
    {
        $this->stuff->setEffectiveness($effectiveness);

        return $this;
    }

    public function getProperties(): Collection
    {
        return $this->stuff->getProperties();
    }

    public function setProperties(Collection $properties): self
    {
        $this->stuff->getProperties()->clear();
        foreach ($properties as $property) {
            $this->AddProperty($property);
        }

        return $this;
    }

    public function addProperty(StuffProperty $property): self
    {
        if (!$this->stuff->getProperties()->contains($property)) {
            $this->stuff->getProperties()->add($property);
        }

        return $this;
    }

    public function removeProperty(StuffProperty $property): self
    {
        $this->stuff->getProperties()->removeElement($property);

        return $this;
    }

    public function isExpendable(): ?bool
    {
        return $this->stuff->isExpendable();
    }

    public function setExpendable(bool $expendable): self
    {
        $this->stuff->setExpendable($expendable);

        return $this;
    }

    public function getPrice(): int
    {
        return $this->stuff->getPrice();
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'effectiveness' => $this->getEffectiveness(),
            'kind' => $this->getKind()->getId(),
            'expendable' => $this->isExpendable(),
            'properties' => array_map(function (StuffProperty $property) {
                return $property->getId();
            }, iterator_to_array($this->getProperties())),
        ]);
    }
}
