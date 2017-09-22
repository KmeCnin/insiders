<?php

namespace App\Service;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\StuffKind;
use App\Entity\Rule\StuffProperty;
use App\Entity\Stuff;
use Doctrine\Common\Collections\Criteria;
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

    public function pickStuffCheaperThan(int $budget): ?Stuff
    {
        return $this->pickCanonicalStuffCheaperThan($budget)->getStuff();
    }

    public function pickWeaponCheaperThan(int $budget): ?Stuff
    {
    }

    public function pickPropertyCompatible(Stuff $stuff, float $maxFp): ?StuffProperty
    {
        $woundProperties = $this->getWoundProperties();

        $excluded = [];
        foreach ($stuff->getProperties() as $property) {
            /** @var StuffProperty $property */
            $excluded[] = $property->getId();
        }

        $list = $this->propertyRepository->createQueryBuilder('property')
            ->join('property.stuffKinds', 'kind', Join::WITH, 'kind = :kind')
            ->andWhere('property.fp <= :maxFp')
            ->andWhere('property.id NOT IN (:excluded)')
            ->andWhere('property.enabled = 1')
            ->setParameter('maxFp', $maxFp)
            ->setParameter('excluded', $excluded)
            ->setParameter('kind', $stuff->getKind())
            ->getQuery()
            ->getResult()
        ;

        return $this->pickOne($list);
    }

    public function getWoundProperties(): array
    {
        return $this->propertyRepository->createQueryBuilder('property')
            ->andWhere('property.slug LIKE :match')
            ->andWhere('property.enabled = 1')
            ->setParameter('match', 'wound-%')
            ->getQuery()
            ->getResult()
        ;
    }

    protected function pickOne(array $list)
    {
        return count($list) ? $list[array_rand($list)] : null;
    }
}
