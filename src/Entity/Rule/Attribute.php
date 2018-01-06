<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AttributeRepository")
 */
class Attribute extends AbstractRule
{
    use ShortTrait;
    use DescriptionTrait;

    public const CODE = 'attribute';

    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $pc;

    public function __construct()
    {
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
    }

    public function getPC(): ?int
    {
        return $this->pc;
    }

    public function setPC(int $pc): self
    {
        $this->pc = $pc;

        return $this;
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'pc' => $this->getPC(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
