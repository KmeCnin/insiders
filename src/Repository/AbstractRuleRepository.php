<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;

abstract class AbstractRuleRepository extends EntityRepository
{
    public function findAll(): array
    {
        return $this->findBy([
            'enabled' => true,
            'public' => true,
        ], [
            'name' => 'ASC',
        ]);
    }
}
