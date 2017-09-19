<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Character extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $proficiencyForce;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $proficiencyResist;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $proficiencyReveal;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $proficiencyHide;

    public function getProficiencyForce(): int
    {
        return $this->proficiencyForce;
    }

    public function setProficiencyForce(int $proficiencyForce): self
    {
        $this->proficiencyForce = $proficiencyForce;

        return $this;
    }

    public function getProficiencyResist(): int
    {
        return $this->proficiencyResist;
    }

    public function setProficiencyResist(int $proficiencyResist): self
    {
        $this->proficiencyResist = $proficiencyResist;

        return $this;
    }

    public function getProficiencyReveal(): int
    {
        return $this->proficiencyReveal;
    }

    public function setProficiencyReveal(int $proficiencyReveal): self
    {
        $this->proficiencyReveal = $proficiencyReveal;

        return $this;
    }

    public function getProficiencyHide(): int
    {
        return $this->proficiencyHide;
    }

    public function setProficiencyHide(int $proficiencyHide): self
    {
        $this->proficiencyHide = $proficiencyHide;

        return $this;
    }
}
