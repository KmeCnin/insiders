<?php

namespace App\Service;

use App\Entity\Rule\StuffProperty;
use App\Entity\Stuff;

class StuffGenerator
{
    private $picker;

    public function __construct(StuffPicker $picker)
    {
        $this->picker = $picker;
    }

    public function generatePermanentStuff(int $budget): Stuff
    {
        $stuff = $this->picker->pickPermanentStuff();

        if ($stuff->getPrice() < $budget) {
            $this->upgrade($stuff, $budget);
        } elseif ($stuff->getPrice() > $budget) {
            $this->downgrade($stuff, $budget);
        }

        $this->updateName($stuff);

        return $stuff;
    }

    private function upgrade(Stuff $stuff, int $budget): void
    {
        if (rand(1, 100) <= 95) {
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

    private function downgrade(Stuff $stuff, int $budget): void
    {
    }

    private function updateName(Stuff $stuff): void
    {
    }

    private function improveQuality(Stuff $stuff, int $budget): bool
    {
        if (rand(1, 100) <= 50) {
            $upgraded = $this->improveEffectiveness($stuff, $budget);
            if (!$upgraded) {
                $upgraded = $this->improveSharpness($stuff, $budget);
            }
        } else {
            $upgraded = $this->improveSharpness($stuff, $budget);
            if (!$upgraded) {
                $upgraded = $this->improveEffectiveness($stuff, $budget);
            }
        }

        return $upgraded;
    }

    private function improveSharpness(Stuff $stuff, int $budget): bool
    {
        if (!$stuff->isWeapon()) {
            return false;
        }

        foreach ($stuff->getProperties() as $property)
        {
            if ($property->isWound())
            {
                switch ($property->getSlug()) {
                    case 'ranged':
                    case 'wound-III':
                        return false;
                    case 'wound-I':
                        $newProperty = $this->picker->getProperty('wound-II');
                        if (Stuff::fpToPrice($stuff, $property->getFp()) <= $budget) {
                            $stuff->removeProperty($property);
                            $stuff->addProperty($newProperty);
                            return true;
                        }
                        return false;
                    case 'wound-II':
                        $newProperty = $this->picker->getProperty('wound-III');
                        if (Stuff::fpToPrice($stuff, $property->getFp()) <= $budget) {
                            $stuff->removeProperty($property);
                            $stuff->addProperty($newProperty);
                            return true;
                        }
                        return false;
                }
            }
        }

        return false;
    }

    private function improveEffectiveness(Stuff $stuff, int $budget): bool
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
        if ($stuff->getProperties()->count() >= 3) {
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
