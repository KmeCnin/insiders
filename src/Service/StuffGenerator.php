<?php

namespace App\Service;

use App\Entity\Rule\CanonicalStuff;
use App\Entity\Stuff;
use Doctrine\ORM\EntityManagerInterface;

class StuffGenerator
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function pickRandomStuff(): CanonicalStuff
    {
        return $this->pickOne(
            $this->em->getRepository(CanonicalStuff::class)->findAll()
        );
    }

    public function pickBudgetedStuff(int $budget): CanonicalStuff
    {
        $list = $this->em->getRepository(CanonicalStuff::class)->findAll();
        usort($list, function (CanonicalStuff $a, CanonicalStuff $b) {
            return $a->getPrice() > $b->getPrice();
        });

        $bestPrice = reset($list)->getPrice();
        foreach ($list as $key => $stuff) {
            if ($stuff->getPrice() > $budget) {
                $bestPrice = isset($list[$key-1])
                    ? $list[$key-1]->getPrice()
                    : reset($list)->getPrice();
                break;
            }
        }

        return $this->pickOne(
            array_filter($list, function (CanonicalStuff $stuff) use ($bestPrice) {
                return $stuff->getPrice() === $bestPrice;
            })
        );
    }

    public function generateRandomStuff(): Stuff
    {
        return new Stuff();
    }

    private function pickOne(array $list)
    {
        return $list[array_rand($list)];
    }
}
