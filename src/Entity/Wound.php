<?php

namespace App\Entity;

/**
 * @ORM\Entity
 */
class Wound extends Entity
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
