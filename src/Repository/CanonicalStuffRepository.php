<?php

namespace App\Repository;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\StuffCategory;
use App\Entity\Rule\StuffKind;
use App\Entity\Stuff;
use Doctrine\ORM\Query\Expr\Join;

class CanonicalStuffRepository extends AbstractRuleRepository
{
    public function findByCategory(StuffCategory $category)
    {
        return $this->findBy([
            'enabled' => true,
            'public' => true,
            'category' => $category,
        ], [
            'name' => 'ASC',
        ]);
    }

    public function findAllWeapons(): array
    {
        return $this->getEntityManager()
            ->createQueryBuilder()
            ->select('canonicalStuff')
            ->from(CanonicalStuff::class, 'canonicalStuff')
            ->join(Stuff::class, 'stuff', Join::WITH, 'stuff = canonicalStuff.stuff')
            ->join(StuffKind::class, 'kind', Join::WITH, 'kind = stuff.kind')
            ->where('kind.id = :kind')
            ->andWhere('canonicalStuff.enabled = true')
            ->andWhere('canonicalStuff.public = true')
            ->andWhere('stuff.expendable = false')
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
            ->andWhere('canonicalStuff.enabled = true')
            ->andWhere('canonicalStuff.public = true')
            ->andWhere('stuff.expendable = false')
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
            ->andWhere('canonicalStuff.enabled = true')
            ->andWhere('canonicalStuff.public = true')
            ->andWhere('stuff.expendable = false')
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
            ->andWhere('canonicalStuff.enabled = true')
            ->andWhere('canonicalStuff.public = true')
            ->andWhere('stuff.expendable = true')
            ->getQuery()
            ->getResult();
    }
}
