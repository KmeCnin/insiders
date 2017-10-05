<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Wound extends AbstractEntity
{
    public static function createDegree(int $degree): self
    {
        $wound = new static();
        $wound->setInstinctForce($degree);
        $wound->setInstinctResist($degree);
        $wound->setInstinctReveal($degree);
        $wound->setInstinctHide($degree);

        return $wound;
    }
}
