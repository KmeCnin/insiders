<?php

namespace App\Repository;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\StuffKind;
use App\Entity\Stuff;
use Doctrine\ORM\Query\Expr\Join;

class CanonicalStuffRepository extends AbstractRuleRepository
{
    public function findAllWeapons(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('canonicalStuff')
            ->from(CanonicalStuff::class, 'canonicalStuff')
            ->join(Stuff::class, 'stuff', Join::WITH, 'stuff = canonicalStuff.stuff')
            ->join(StuffKind::class, 'kind', Join::WITH, 'kind = stuff.kind')
            ->where('kind.id = :kind')
            ->andwhere('stuff.expendable = false')
            ->setParameter('kind', StuffKind::KIND_WEAPON)
            ->getQuery()
            ->getResult();
    }

    public function findAllArmors(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('canonicalStuff')
            ->from(CanonicalStuff::class, 'canonicalStuff')
            ->join(Stuff::class, 'stuff', Join::WITH, 'stuff = canonicalStuff.stuff')
            ->join(StuffKind::class, 'kind', Join::WITH, 'kind = stuff.kind')
            ->where('kind.id IN (:kinds)')
            ->andwhere('stuff.expendable = false')
            ->setParameter('kinds', [StuffKind::KIND_ARMOR, StuffKind::KIND_SHIELD])
            ->getQuery()
            ->getResult();
    }

    public function findAllObjects(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('canonicalStuff')
            ->from(CanonicalStuff::class, 'canonicalStuff')
            ->join(Stuff::class, 'stuff', Join::WITH, 'stuff = canonicalStuff.stuff')
            ->join(StuffKind::class, 'kind', Join::WITH, 'kind = stuff.kind')
            ->where('kind.id = :kind')
            ->andwhere('stuff.expendable = false')
            ->setParameter('kind', StuffKind::KIND_OBJECT)
            ->getQuery()
            ->getResult();
    }

    public function findAllExpendables(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('canonicalStuff')
            ->from(CanonicalStuff::class, 'canonicalStuff')
            ->join(Stuff::class, 'stuff', Join::WITH, 'stuff = canonicalStuff.stuff')
            ->andwhere('stuff.expendable = true')
            ->getQuery()
            ->getResult();
    }
}
