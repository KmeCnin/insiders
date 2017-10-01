<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class StuffKind extends AbstractRule
{
    const KIND_WEAPON = 'weapon';
    const KIND_ARMOR = 'armor';
    const KIND_SHIELD = 'shield';
    const KIND_OBJECT = 'object';
}
