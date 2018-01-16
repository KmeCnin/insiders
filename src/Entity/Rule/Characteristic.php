<?php

namespace App\Entity\Rule;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CharacteristicRepository")
 */
class Characteristic extends AbstractRule
{
    use ShortTrait;
    use DescriptionTrait;

    public const CODE = 'characteristic';

    /**
     * @var Skill[]|ArrayCollection
     *
     * @ORM\OneToMany(targetEntity="Skill", mappedBy="characteristic")
     * @ORM\JoinColumn(nullable=false)
     */
    private $skills;

    public function __construct()
    {
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
        $this->setSkills([]);
    }

    public function getSkills(): array
    {
        return $this->skills->toArray();
    }

    public function setSkills(array $skills): self
    {
        $this->skills = new ArrayCollection($skills);

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
