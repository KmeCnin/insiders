<?php

namespace App\Repository;

use App\Entity\Rule\Arcane;

class AbilityRepository extends AbstractRuleRepository
{
    public function findAll(): array
    {
        $abilities = $this->findBy([
            'enabled' => true,
            'public' => true,
        ], [
            'name' => 'ASC',
        ]);

        return $this->smartSort($abilities, 'getAbilitiesRequired');
    }

    public function findByArcane(Arcane $arcane)
    {
        $abilities = $this->findBy([
            'enabled' => true,
            'public' => true,
            'arcane' => $arcane,
        ], [
            'name' => 'ASC',
        ]);

        return $this->smartSort($abilities, 'getAbilitiesRequired');
    }
}
