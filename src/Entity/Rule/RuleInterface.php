<?php

namespace App\Entity\Rule;

interface RuleInterface
{
    public function getId(): ?int;

    public function setId(int $id);

    public function getIdString(): ?string;

    public function setIdString(string $id);

    public function getName(): ?string;

    public function setName(string $name);

    public function __toString(): string;
}
