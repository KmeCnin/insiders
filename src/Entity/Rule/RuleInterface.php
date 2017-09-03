<?php

namespace App\Entity\Rule;

interface RuleInterface
{
    public function getId(): ?int;

    public function setId(int $id);

    public function getSlug(): ?string;

    public function setSlug(string $slug);

    public function getName(): ?string;

    public function setName(string $name);

    public function isDeleted(): bool;

    public function setDeleted(bool $deleted);

    public function __toString(): string;
}
