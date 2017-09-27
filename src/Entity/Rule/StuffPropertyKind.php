<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StuffPropertyKind extends AbstractRule
{
    const KIND_DEFAULT = 'default';
    const KIND_WOUND = 'wound';
}
