<?php

namespace App\Repository;

use App\Entity\Rule\RuleInterface;
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

    /**
     * @param RuleInterface[] $list
     */
    protected function smartSort(array $list, string $getter): array
    {
        $tree = [];
        foreach ($list as $rule) {
            $required = array_map(
                function (RuleInterface $rule) {
                    return $rule->getId();
                },
                $rule->{$getter}()->toArray()
            );
            $tree[$rule->getId()]['rule'] = $rule;
            $tree[$rule->getId()]['needs'] = $required;
            foreach ($required as $id) {
                $tree[$id]['unlocks'][] = $rule->getId();
            }
        }

        $sorted = [];
        foreach ($tree as $entree) {
            if (empty($entree['needs']) && empty($entree['unlocks'])) {
                $sorted[] = $entree['rule'];
                continue;
            }
            if (empty($entree['needs'])) {
                $sorted[] = $entree['rule'];
                while(!empty($entree['unlocks'])) {
                    foreach ($entree['unlocks'] as $unlock) {
                        $entree = $tree[$unlock];
                        $sorted[] = $entree['rule'];
                    }
                }
            }
        }

        return $sorted;
    }
}
