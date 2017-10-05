<?php

namespace App\Service;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\StuffProperty;
use App\Entity\Rule\StuffPropertyKind;
use App\Entity\Stuff;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class StuffPicker
{
    /** @var EntityRepository */
    private $stuffRepository;
    /** @var EntityRepository */
    private $propertyRepository;

    public function __construct(EntityManagerInterface $em)
    {
        $this->stuffRepository = $em->getRepository(CanonicalStuff::class);
        $this->propertyRepository = $em->getRepository(StuffProperty::class);
    }

    public function pickCanonicalStuffCheaperThan(int $budget): ?CanonicalStuff
    {
        $list = $this->stuffRepository->findAll();

        return $this->pickOne(
            array_filter($list, function (CanonicalStuff $stuff) use ($budget) {
                return $stuff->getPrice() <= $budget;
            })
        );
    }

    public function pickPermanentStuff(): ?Stuff
    {
        return $this->pickCanonicalStuff(false)->getStuff();
    }

    public function pickExpendableStuff(): ?Stuff
    {
        return $this->pickCanonicalStuff(true)->getStuff();
    }

    protected function pickCanonicalStuff(bool $expendable): ?CanonicalStuff
    {
        $list = $this->stuffRepository->createQueryBuilder('canonical')
            ->join('canonical.stuff', 'stuff')
            ->andWhere('stuff.expendable = :expendable')
            ->andWhere('canonical.enabled = 1')
            ->setParameter('expendable', $expendable)
            ->getQuery()
            ->getResult()
        ;

        return $this->pickOne($list);
    }

    public function pickStuffCheaperThan(int $budget): ?Stuff
    {
        return $this->pickCanonicalStuffCheaperThan($budget)->getStuff();
    }

    public function pickWeaponCheaperThan(int $budget): ?Stuff
    {
    }

    public function pickPropertyCompatible(Stuff $stuff, float $maxFp): ?StuffProperty
    {
        $woundProperties = array_map(
            function (StuffProperty $property) {
                return $property->getId();
            },
            $this->getWoundProperties()
        );

        $excluded = [];
        foreach ($stuff->getProperties() as $property) {
            if ($property->isWound()) {
                array_merge($excluded, $woundProperties);
                continue;
            }
            $excluded[] = $property->getId();
        }

        $builder = $this->propertyRepository->createQueryBuilder('property')
            ->join('property.stuffKinds', 'kind', Join::WITH, 'kind = :kind')
            ->andWhere('property.fp <= :maxFp')
            ->andWhere('property.enabled = 1')
            ->setParameter('maxFp', $maxFp)
            ->setParameter('kind', $stuff->getKind())
        ;

        if (count($excluded)) {
            $builder
                ->andWhere('property.id NOT IN (:excluded)')
                ->setParameter('excluded', $excluded);
        }

        $list = $builder->getQuery()->getResult();

        return $this->pickOne($list);
    }

    public function getWoundProperties(): array
    {
        return $this->propertyRepository->createQueryBuilder('property')
            ->join('property.kind', 'kind')
            ->andWhere('kind.slug != :wound')
            ->andWhere('property.enabled = 1')
            ->setParameter('wound', StuffPropertyKind::KIND_WOUND)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getProperty(string $slug): StuffProperty
    {
        return $this->propertyRepository->createQueryBuilder('property')
            ->andWhere('property.slug = :slug')
            ->andWhere('property.enabled = 1')
            ->setParameter('slug', $slug)
            ->getQuery()
            ->getSingleResult()
        ;
    }

    protected function pickOne(array $list)
    {
        return count($list) ? $list[array_rand($list)] : null;
    }
}
