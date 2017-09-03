<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Increase extends AbstractSecondaryRule
{
    /**
     * @var Ability
     *
     * @ORM\ManyToOne(targetEntity="Ability", inversedBy="increases")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ability;

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

    public function getAbility(): ?Ability
    {
        return $this->ability;
    }

    public function setAbility(Ability $ability): self
    {
        $this->ability = $ability;

        return $this;
    }
}
