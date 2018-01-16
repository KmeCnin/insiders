<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\SkillRepository")
 */
class Skill extends AbstractRule
{
    use ShortTrait;
    use DescriptionTrait;

    public const CODE = 'skill';

    /**
     * @var Characteristic
     *
     * @ORM\ManyToOne(targetEntity="Characteristic", inversedBy="skills")
     * @ORM\JoinColumn(nullable=false)
     */
    private $characteristic;

    public function __construct()
    {
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
    }

    public function getCharacteristic(): ?Characteristic
    {
        return $this->characteristic;
    }

    public function setCharacteristic(Characteristic $characteristic): self
    {
        $this->characteristic = $characteristic;

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
            'characteristic' => $this->getCharacteristic()->getId(),
        ]);
    }
}
