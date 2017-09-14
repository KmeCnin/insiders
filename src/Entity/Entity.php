<?php

namespace App\Entity;

/**
 * @ORM\Entity
 */
class Entity extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $instinctForce;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $instinctResist;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $instinctReveal;

    /**
     * @var int
     *
     * @ORM\Column(type="int", nullable=false)
     */
    protected $instinctHide;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getInstinctForce(): int
    {
        return $this->instinctForce;
    }

    public function setInstinctForce(int $instinctForce): self
    {
        $this->instinctForce = $instinctForce;

        return $this;
    }

    public function getInstinctResist(): int
    {
        return $this->instinctResist;
    }

    public function setInstinctResist(int $instinctResist): self
    {
        $this->instinctResist = $instinctResist;

        return $this;
    }

    public function getInstinctReveal(): int
    {
        return $this->instinctReveal;
    }

    public function setInstinctReveal(int $instinctReveal): self
    {
        $this->instinctReveal = $instinctReveal;

        return $this;
    }

    public function getInstinctHide(): int
    {
        return $this->instinctHide;
    }

    public function setInstinctHide(int $instinctHide): self
    {
        $this->instinctHide = $instinctHide;

        return $this;
    }
}
