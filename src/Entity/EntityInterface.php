<?php

namespace App\Entity;

interface EntityInterface
{
    public function getId(): int;

    public function setId(int $id);

    public function getInstinctForce(): int;

    public function setInstinctForce(int $instinctForce);

    public function getInstinctResist(): int;

    public function setInstinctResist(int $instinctResist);

    public function getInstinctReveal(): int;

    public function setInstinctReveal(int $instinctReveal);

    public function getInstinctHide(): int;

    public function setInstinctHide(int $instinctHide);
}
