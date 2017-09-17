<?php

namespace App\Entity\Rule;

use App\Entity\Stuff;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class CanonicalStuff extends AbstractRule
{
    /**
     * @var Stuff
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Stuff", cascade={"all"})
     */
    protected $stuff;

    public function getStuff(): ?Stuff
    {
        return $this->stuff;
    }

    public function setStuff(Stuff $stuff): self
    {
        $this->stuff = $stuff;

        return $this;
    }
}
