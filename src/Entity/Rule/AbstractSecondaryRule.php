<?php

namespace App\Entity\Rule;

use App\Entity\AbstractEntity;
use App\Entity\NormalizableInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractSecondaryRule implements SecondaryRuleInterface, NormalizableInterface
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    protected $id;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }
}
