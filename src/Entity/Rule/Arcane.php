<?php

namespace App\Entity\Rule;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ArcaneRepository")
 */
class Arcane extends AbstractRule
{
    use ShortTrait;
    use DescriptionTrait;

    public const CODE = 'arcane';

    public function __construct()
    {
        parent::__construct();

        $this->setShort('');
        $this->setDescription('');
    }

    public function normalize(): array
    {
        return array_merge(parent::normalize(), [
            'short' => $this->getShort(),
            'description' => $this->getDescription(),
        ]);
    }
}
