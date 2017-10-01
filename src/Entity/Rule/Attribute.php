<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Attribute extends AbstractRule
{
    /**
     * @var int
     *
     * @ORM\Column(type="integer")
     */
    protected $pc;

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

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'pc' => $this->getPC(),
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
