<?php

namespace App\Repository;

use App\Entity\Rule\Arcane;

class AbilityRepository extends AbstractRuleRepository
{
    public function findByArcane(Arcane $arcane)
    {
        return $this->findBy([
            'enabled' => true,
            'public' => true,
            'arcane' => $arcane,
        ], [
            'name' => 'ASC',
        ]);
    }
}
