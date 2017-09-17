<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

abstract class AbstractEntity implements EntityInterface
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
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $instinctForce;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $instinctResist;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $instinctReveal;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", nullable=false)
     */
    protected $instinctHide;

    public function __construct()
    {
        $this->setRank(0);
    }

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

    public function setRank(int $rank): self
    {
        $this->setInstinctForce($rank);
        $this->setInstinctResist($rank);
        $this->setInstinctReveal($rank);
        $this->setInstinctHide($rank);

        return $this;
    }
}
