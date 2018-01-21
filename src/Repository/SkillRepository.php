<?php

namespace App\Repository;

use App\Entity\Rule\Characteristic;

class SkillRepository extends AbstractRuleRepository
{
    public function findByCharacteristic(Characteristic $characteristic)
    {
        return $this->findBy([
            'enabled' => true,
            'public' => true,
            'characteristic' => $characteristic,
        ], [
            'name' => 'ASC',
        ]);
    }
}
