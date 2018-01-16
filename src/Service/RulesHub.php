<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Rule\Ability;
use App\Entity\Rule\AbstractRule;
use App\Entity\Rule\Arcane;
use App\Entity\Rule\Attribute;
use App\Entity\Rule\Burst;
use App\Entity\Rule\CanonicalStuff;
use App\Entity\Rule\Champion;
use App\Entity\Rule\Characteristic;
use App\Entity\Rule\Deity;
use App\Entity\Rule\LexiconEntry;
use App\Entity\Rule\Page;
use App\Entity\Rule\Skill;
use App\Entity\Rule\StuffProperty;
use Psr\Log\InvalidArgumentException;
use Symfony\Component\Routing\RouterInterface;

class RulesHub
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function codeToClass(string $code)
    {
        switch ($code) {
            case Ability::CODE:
                return Ability::class;
            case Arcane::CODE:
                return Arcane::class;
            case Attribute::CODE:
                return Attribute::class;
            case Burst::CODE:
                return Burst::class;
            case CanonicalStuff::CODE:
                return CanonicalStuff::class;
            case Champion::CODE:
                return Champion::class;
            case Characteristic::CODE:
                return Characteristic::class;
            case Deity::CODE:
                return Deity::class;
            case LexiconEntry::CODE:
                return LexiconEntry::class;
            case Page::CODE:
                return Page::class;
            case Skill::CODE:
                return Skill::class;
            case StuffProperty::CODE:
                return StuffProperty::class;
            default:
                throw new InvalidArgumentException(sprintf(
                    'Unhandled entity code %s',
                    $code
                ));
        }
    }

    public function linkFromEntity(AbstractRule $rule): string
    {
        switch (true) {
            case $rule instanceof Ability:
                return $this->router->generate(
                    'rules.abilities',
                    ['slug' => $rule->getArcane()->getSlug()]
                ).'#'.$rule->getSlug();
            case $rule instanceof Arcane:
                return $this->router->generate('universe.arcanes').'#'.$rule->getSlug();
            case $rule instanceof Attribute:
                return $this->router->generate('rules.attributes').'#'.$rule->getSlug();
            case $rule instanceof Burst:
                return $this->router->generate('rules.bursts').'#'.$rule->getSlug();
            case $rule instanceof Champion:
                return $this->router->generate('rules.champions').'#'.$rule->getSlug();
            case $rule instanceof CanonicalStuff:
                return $this->router->generate(
                        'rules.stuff',
                        ['slug' => $rule->getStuff()->getKind()->getSlug()]
                    ).'#'.$rule->getSlug();
            case $rule instanceof Characteristic:
                return $this->router->generate('rules.characteristics').'#'.$rule->getSlug();
            case $rule instanceof Deity:
                return $this->router->generate('universe.deities').'#'.$rule->getSlug();
            case $rule instanceof LexiconEntry:
                return $this->router->generate('lexicon').'#'.$rule->getSlug();
            case $rule instanceof Page:
                return $this->router->generate('page').'#'.$rule->getSlug();
            case $rule instanceof Skill:
                return $this->router->generate('rules.skills').'#'.$rule->getSlug();
            case $rule instanceof StuffProperty:
                return $this->router->generate('rules.stuff_property').'#'.$rule->getSlug();
            default:
                throw new \InvalidArgumentException(sprintf('Unhandled rule %s', \get_class($rule)));
        }
    }
}
