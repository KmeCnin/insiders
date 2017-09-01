<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Champion extends AbstractRule
{
    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    protected $dignity;

    /**
     * @var Deity
     *
     * @ORM\OneToOne(targetEntity="Deity")
     * @ORM\JoinColumn(nullable=false)
     */
    private $deity;

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    protected $description;

    public function getDignity(): ?string
    {
        return $this->dignity;
    }

    public function setDignity(string $dignity): self
    {
        $this->dignity = $dignity;

        return $this;
    }

    public function getDeity(): ?Deity
    {
        return $this->deity;
    }

    public function setDeity(Deity $deity): self
    {
        $this->deity = $deity;

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
}
