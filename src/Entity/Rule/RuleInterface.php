<?php

namespace App\Entity\Rule;

interface RuleInterface
{
    public const CODE = '';

    public function getId(): ?string;

    public function setId(string $id);

    public function getSlug(): ?string;

    public function setSlug(string $slug);

    public function getName(): ?string;

    public function setName(string $name);

    public function isEnabled(): bool;

    public function setEnabled(bool $enabled);

    public function __toString(): string;
}
