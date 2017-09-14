<?php

namespace App\Entity;

/**
 * @ORM\Entity
 */
class Character extends Entity
{
    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $proficiencyForce;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $proficiencyResist;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $proficiencyReveal;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $proficiencyHide;

    public function getProficiencyForce(): int
    {
        return $this->proficiencyForce;
    }

    public function setProficiencyForce(int $proficiencyForce): Entity
    {
        $this->proficiencyForce = $proficiencyForce;

        return $this;
    }

    public function getProficiencyResist(): int
    {
        return $this->proficiencyResist;
    }

    public function setProficiencyResist(int $proficiencyResist): Entity
    {
        $this->proficiencyResist = $proficiencyResist;

        return $this;
    }

    public function getProficiencyReveal(): int
    {
        return $this->proficiencyReveal;
    }

    public function setProficiencyReveal(int $proficiencyReveal): Entity
    {
        $this->proficiencyReveal = $proficiencyReveal;

        return $this;
    }

    public function getProficiencyHide(): int
    {
        return $this->proficiencyHide;
    }

    public function setProficiencyHide(int $proficiencyHide): Entity
    {
        $this->proficiencyHide = $proficiencyHide;

        return $this;
    }
}
