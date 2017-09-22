<?php

namespace App\Service;

use App\Entity\Stuff;

class StuffGenerator
{
    private $picker;

    public function __construct(StuffPicker $picker)
    {
        $this->picker = $picker;
    }

    public function generateBudgetedStuff(int $budget): Stuff
    {
        $stuff = $this->picker->pickStuffCheaperThan($budget);

        if ($stuff->getPrice() < $budget) {
            $this->upgrade($stuff, $budget);
        }

        return $stuff;
    }

    private function upgrade(Stuff $stuff, int $budget): void
    {
        if (rand(1, 100) <= 75) {
            $upgraded = $this->improveQuality($stuff, $budget);
            if (!$upgraded) {
                $upgraded = $this->addProperty($stuff, $budget);
            }
        } else {
            $upgraded = $this->addProperty($stuff, $budget);
            if (!$upgraded) {
                $upgraded = $this->improveQuality($stuff, $budget);
            }
        }

        if ($upgraded && $stuff->getPrice() < $budget) {
            $this->upgrade($stuff, $budget);
        }
    }

    private function improveQuality(Stuff $stuff, int $budget): bool
    {
        if ($stuff->getEffectiveness() >= 5) {
            return false;
        }

        $required = Stuff::fpToPrice($stuff, Stuff::EFFECTIVENESS_FP);
        if ($required <= $budget - $stuff->getPrice()) {
            $stuff->incrementEffectiveness();
            return true;
        }

        return false;
    }

    private function addProperty(Stuff $stuff, int $budget): bool
    {
        if ($stuff->getProperties()->count() >= 5) {
            return false;
        }

        $property = $this->picker->pickPropertyCompatible(
            $stuff,
            Stuff::priceToFp($stuff, $budget - $stuff->getPrice())
        );

        if ($property) {
            $stuff->addProperty($property);
            return true;
        }

        return false;
    }
}
