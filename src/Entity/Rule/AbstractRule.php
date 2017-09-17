<?php

namespace App\Entity\Rule;

use App\Entity\NormalizableInterface;
use Doctrine\ORM\Mapping as ORM;

abstract class AbstractRule implements RuleInterface, NormalizableInterface
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
     * @var string
     *
     * @ORM\Column(type="string", unique=true)
     */
    protected $slug;

    /**
     * @var string
     *
     * @ORM\Column(type="string", nullable=false)
     */
    protected $name;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", nullable=false)
     */
    protected $enabled;

    public function __construct()
    {
        $this->setEnabled(true);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function isEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
    }

    public function normalize(): array
    {
        return [
            'id' => $this->getId(),
            'slug' => $this->getSlug(),
            'name' => $this->getName(),
            'enabled' => $this->isEnabled(),
        ];
    }
}
