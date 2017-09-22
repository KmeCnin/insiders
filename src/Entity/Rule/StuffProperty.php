<?php

namespace App\Entity\Rule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StuffProperty extends AbstractRule
{
    /**
     * @var float
     *
     * @ORM\Column(type="float", nullable=false)
     */
    protected $fp;
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $short;

    /**
     * @var StuffKind[]
     *
     * @ORM\ManyToMany(targetEntity="StuffKind")
     */
    protected $stuffKinds;

    public function __construct()
    {
        parent::__construct();

        $this->stuffKinds = new ArrayCollection([]);
    }

    public function getFp(): ?float
    {
        return $this->fp;
    }

    public function setFp(float $fp): self
    {
        $this->fp = $fp;

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

    public function getStuffKinds(): \Traversable
    {
        return $this->stuffKinds;
    }

    public function setStuffKinds(\Traversable $kinds): self
    {
        $this->stuffKinds->clear();
        foreach ($kinds as $kind) {
            $this->addStuffKind($kind);
        }

        return $this;
    }

    public function addStuffKind(StuffKind $kind)
    {
        if (!$this->stuffKinds->contains($kind)) {
            $this->stuffKinds->add($kind);
        }

        return $this;
    }

    public function removeStuffKind(StuffKind $kind): self
    {
        $this->stuffKinds->removeElement($kind);

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'fp' => $this->getFp(),
            'short' => $this->getShort(),
            'stuffKinds' => array_map(function (StuffKind $kind) {
                return $kind->getSlug();
            }, iterator_to_array($this->getStuffKinds())),
        ]);
    }
}
