<?php
declare(strict_types=1);

namespace App\Service;

use App\Entity\Rule\Ability;
use App\Entity\Rule\AbstractRule;
use Symfony\Component\Routing\RouterInterface;

class RulesHub
{
    private $router;

    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    public function linkFromEntity(AbstractRule $rule): string
    {
        switch (true) {
            case $rule instanceof Ability:
                return $this->router->generate('rules.abilities', [
                    'slug' => $rule->getSlug()
                ]);
            default:
                throw new \Exception(sprintf('Unhandled rule %s', \get_class($rule)));
        }
    }
}
